<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../inc/class.db.php';
require_once __DIR__ . '/../inc/config.php';

$db        = Config::get('db');
$me        = $_SESSION['platform_admin'];
$accountId = intval($_GET['id'] ?? 0);

if (!$accountId) { header('location: /admin/index.php'); exit(); }

$acct = $db->get_results("SELECT * FROM productiveAccount WHERE id={$accountId} LIMIT 1");
if (!$acct) { header('location: /admin/index.php'); exit(); }
$acct = $acct[0];

$msg = '';

/* ── POST handlers ─────────────────────────────────────────────── */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $redir  = '/admin/account.php?id=' . $accountId;

    if ($action === 'add_qbl') {
        $unitMode = $_POST['unit_mode'] ?? 'select';
        $deptId   = 0;

        if ($unitMode === 'select') {
            $deptId = intval($_POST['deptId'] ?? 0);
        } else {
            // Create new unit
            $deptName = trim($db->filter($_POST['new_unit_name'] ?? ''));
            $pm       = intval($_POST['new_unit_pm'] ?? 1);
            if ($deptName) {
                $db->insert('ProductiveDept', ['dept' => $deptName, 'accountId' => $accountId, 'prodMeasure' => $pm, 'active' => 1]);
                $deptId = $db->lastid();
            }
        }

        if ($deptId) {
            $exists = $db->get_results("SELECT id FROM productiveQBLconfig WHERE deptId={$deptId} AND accountId={$accountId} LIMIT 1");
            if ($exists) {
                header('location: /admin/qbl-config.php?id=' . $exists[0]['id']); exit();
            } else {
                $db->insert('productiveQBLconfig', ['deptId' => $deptId, 'accountId' => $accountId, 'active' => 1]);
                header('location: /admin/qbl-config.php?id=' . $db->lastid()); exit();
            }
        }

    } elseif ($action === 'toggle_qbl') {
        $qid = intval($_POST['qblId'] ?? 0);
        $val = intval($_POST['val'] ?? 0);
        if ($qid) $db->update('productiveQBLconfig', ['active' => $val], ['id' => $qid, 'accountId' => $accountId], 1);
        header('location: ' . $redir); exit();
    }
}

/* ── Fetch data ─────────────────────────────────────────────────── */
$configs = $db->get_results(
    "SELECT q.id, q.active, d.id as deptId, d.dept, d.prodMeasure,
        (SELECT COUNT(*) FROM productiveUser u JOIN productiveDeptXref x ON x.userId=u.id WHERE x.deptId=d.id AND u.active=1) as userCount,
        (SELECT COUNT(*) FROM (SELECT desc1 FROM productiveQBLconfig WHERE id=q.id AND desc1!=''
         UNION SELECT desc2 FROM productiveQBLconfig WHERE id=q.id AND desc2!=''
         UNION SELECT desc3 FROM productiveQBLconfig WHERE id=q.id AND desc3!=''
         UNION SELECT desc4 FROM productiveQBLconfig WHERE id=q.id AND desc4!=''
         UNION SELECT desc5 FROM productiveQBLconfig WHERE id=q.id AND desc5!='') t) as itemCount
     FROM productiveQBLconfig q
     LEFT JOIN ProductiveDept d ON d.id = q.deptId
     WHERE q.accountId = {$accountId}
     ORDER BY d.dept ASC"
);

// Units without a QBL config (for the select dropdown)
$availableUnits = $db->get_results(
    "SELECT id, dept FROM ProductiveDept
     WHERE accountId={$accountId} AND active=1
     AND id NOT IN (SELECT deptId FROM productiveQBLconfig WHERE accountId={$accountId})
     ORDER BY dept ASC"
);

if (isset($_GET['msg']) && $_GET['msg'] === 'saved') $msg = 'Changes saved.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?php echo htmlspecialchars($acct['name']); ?> — ProductivRN Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex,nofollow">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f1f5f9; color: #1e293b; font-size: 14px; }

    .topbar { background: #0f1525; padding: 0 1.5rem; height: 52px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 100; }
    .topbar-left { display: flex; align-items: center; gap: 1rem; }
    .topbar-brand { display: flex; align-items: center; gap: 0.5rem; font-weight: 800; font-size: 1.05rem; color: #f1f5f9; letter-spacing: -0.02em; text-decoration: none; }
    .topbar-brand span { color: #60a5fa; }
    .badge-admin { font-size: 0.6rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; background: rgba(96,165,250,0.15); color: #60a5fa; border: 1px solid rgba(96,165,250,0.3); border-radius: 4px; padding: 2px 6px; }
    .breadcrumb { font-size: 0.8rem; color: #64748b; display: flex; align-items: center; gap: 0.4rem; }
    .breadcrumb a { color: #64748b; text-decoration: none; } .breadcrumb a:hover { color: #94a3b8; }
    .topbar-right { display: flex; align-items: center; gap: 1rem; }
    .topbar-user { font-size: 0.8rem; color: #94a3b8; }
    .btn-logout { font-size: 0.78rem; color: #64748b; background: none; border: 1px solid #334155; border-radius: 5px; padding: 4px 10px; cursor: pointer; text-decoration: none; }
    .btn-logout:hover { color: #f1f5f9; border-color: #64748b; }

    .page { max-width: 1000px; margin: 0 auto; padding: 2rem 1.5rem; }
    .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.75rem; }
    .page-title { font-size: 1.2rem; font-weight: 700; color: #0f172a; }
    .page-sub { font-size: 0.82rem; color: #64748b; margin-top: 0.2rem; }

    .alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 0.65rem 1rem; border-radius: 6px; font-size: 0.85rem; margin-bottom: 1.25rem; }

    .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; overflow: hidden; }
    table { width: 100%; border-collapse: collapse; }
    thead th { background: #f8fafc; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; padding: 0.65rem 1rem; text-align: left; border-bottom: 1px solid #e2e8f0; white-space: nowrap; }
    tbody tr { border-bottom: 1px solid #f1f5f9; transition: background 0.1s; }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: #f8fafc; }
    td { padding: 0.7rem 1rem; vertical-align: middle; }
    .unit-name { font-weight: 600; color: #1e293b; }
    .pill { display: inline-block; font-size: 0.68rem; font-weight: 700; padding: 2px 8px; border-radius: 20px; }
    .pill-active   { background: #dcfce7; color: #15803d; }
    .pill-inactive { background: #f1f5f9; color: #94a3b8; }
    .count-badge { display: inline-block; background: #f1f5f9; color: #475569; font-size: 0.78rem; font-weight: 600; padding: 2px 8px; border-radius: 12px; }
    .empty-state { padding: 3rem 1rem; text-align: center; color: #94a3b8; font-size: 0.9rem; }

    .btn { display: inline-block; padding: 0.4rem 0.85rem; border-radius: 6px; font-size: 0.8rem; font-weight: 600; cursor: pointer; border: none; text-decoration: none; transition: background 0.15s; }
    .btn-primary { background: #2563eb; color: #fff; } .btn-primary:hover { background: #1d4ed8; }
    .btn-sm { padding: 0.27rem 0.65rem; font-size: 0.76rem; }
    .btn-outline { background: none; border: 1px solid #e2e8f0; color: #475569; } .btn-outline:hover { background: #f8fafc; }
    .btn-danger  { background: none; border: 1px solid #fecaca; color: #dc2626; } .btn-danger:hover  { background: #fef2f2; }
    .btn-restore { background: none; border: 1px solid #bbf7d0; color: #16a34a; } .btn-restore:hover { background: #f0fdf4; }
    .actions { display: flex; gap: 0.35rem; align-items: center; }

    .modal-bg { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.45); z-index: 200; align-items: flex-start; justify-content: center; padding-top: 5rem; }
    .modal-bg.open { display: flex; }
    .modal-box { background: #fff; border-radius: 12px; width: 100%; max-width: 460px; padding: 1.75rem; box-shadow: 0 20px 60px rgba(0,0,0,0.15); }
    .modal-title { font-size: 1rem; font-weight: 700; color: #0f172a; margin-bottom: 1.25rem; }
    .field { margin-bottom: 0.9rem; }
    .field label { display: block; font-size: 0.77rem; font-weight: 600; color: #374151; margin-bottom: 0.3rem; }
    .field input, .field select { width: 100%; padding: 0.52rem 0.75rem; border: 1.5px solid #d1d5db; border-radius: 6px; font-size: 0.88rem; color: #0f172a; background: #fff; outline: none; }
    .field input:focus, .field select:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
    .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
    .modal-footer { display: flex; justify-content: flex-end; gap: 0.6rem; margin-top: 1.25rem; }

    .unit-toggle { display: flex; gap: 0; margin-bottom: 1rem; border: 1.5px solid #d1d5db; border-radius: 6px; overflow: hidden; }
    .unit-toggle label { flex: 1; text-align: center; padding: 0.5rem; font-size: 0.8rem; font-weight: 600; color: #64748b; cursor: pointer; }
    .unit-toggle input[type=radio] { display: none; }
    .unit-toggle input[type=radio]:checked + label { background: #2563eb; color: #fff; }
    .unit-panel { display: none; } .unit-panel.show { display: block; }
  </style>
</head>
<body>

<div class="topbar">
  <div class="topbar-left">
    <a href="/admin/index.php" class="topbar-brand">
      <svg width="20" height="20" viewBox="0 0 30 30" fill="none"><circle cx="15" cy="15" r="13" stroke="#3b82f6" stroke-width="1.5" fill="rgba(59,130,246,0.08)"/><path d="M15 8v14M8 15h14" stroke="#3b82f6" stroke-width="2" stroke-linecap="round"/><circle cx="15" cy="15" r="3" fill="#3b82f6"/></svg>
      Productiv<span>RN</span>&nbsp;<span class="badge-admin">Admin</span>
    </a>
    <div class="breadcrumb">
      <span>/</span>
      <a href="/admin/index.php">Accounts</a>
      <span>/</span>
      <span><?php echo htmlspecialchars($acct['name']); ?></span>
    </div>
  </div>
  <div class="topbar-right">
    <span class="topbar-user"><?php echo htmlspecialchars($me['first_name'] . ' ' . $me['last_name']); ?></span>
    <a href="/admin/logout.php" class="btn-logout">Sign Out</a>
  </div>
</div>

<div class="page">
  <div class="page-header">
    <div>
      <div class="page-title"><?php echo htmlspecialchars($acct['name']); ?></div>
      <div class="page-sub">QBL Configurations — <?php echo count($configs); ?> unit<?php echo count($configs) != 1 ? 's' : ''; ?></div>
    </div>
    <button class="btn btn-primary" onclick="document.getElementById('addModal').classList.add('open')">+ Add QBL Config</button>
  </div>

  <?php if ($msg): ?><div class="alert-success"><?php echo htmlspecialchars($msg); ?></div><?php endif; ?>

  <div class="card">
    <table>
      <thead>
        <tr>
          <th>Unit</th>
          <th>Type</th>
          <th>Items</th>
          <th>Users</th>
          <th>Status</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($configs)): ?>
        <tr><td colspan="6"><div class="empty-state">No QBL configurations yet. Add one to get started.</div></td></tr>
        <?php else: foreach ($configs as $c): ?>
        <tr>
          <td class="unit-name"><?php echo htmlspecialchars($c['dept'] ?? '—'); ?></td>
          <td style="color:#64748b;font-size:0.82rem"><?php echo $c['prodMeasure'] == 2 ? 'Support' : 'Nursing'; ?></td>
          <td><span class="count-badge"><?php echo intval($c['itemCount']); ?></span></td>
          <td><span class="count-badge"><?php echo intval($c['userCount']); ?></span></td>
          <td><span class="pill <?php echo $c['active'] ? 'pill-active' : 'pill-inactive'; ?>"><?php echo $c['active'] ? 'Active' : 'Archived'; ?></span></td>
          <td>
            <div class="actions">
              <a href="/admin/qbl-config.php?id=<?php echo $c['id']; ?>" class="btn btn-sm btn-outline">Configure</a>
              <?php if ($c['active']): ?>
              <form method="post" style="display:inline" onsubmit="return confirm('Archive this config?')">
                <input type="hidden" name="action" value="toggle_qbl">
                <input type="hidden" name="qblId" value="<?php echo $c['id']; ?>">
                <input type="hidden" name="val" value="0">
                <button type="submit" class="btn btn-sm btn-danger">Archive</button>
              </form>
              <?php else: ?>
              <form method="post" style="display:inline">
                <input type="hidden" name="action" value="toggle_qbl">
                <input type="hidden" name="qblId" value="<?php echo $c['id']; ?>">
                <input type="hidden" name="val" value="1">
                <button type="submit" class="btn btn-sm btn-restore">Restore</button>
              </form>
              <?php endif; ?>
            </div>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- ADD QBL MODAL -->
<div class="modal-bg" id="addModal">
  <div class="modal-box">
    <div class="modal-title">Add QBL Configuration</div>
    <form method="post">
      <input type="hidden" name="action" value="add_qbl">

      <div class="unit-toggle">
        <input type="radio" name="unit_mode" id="mode_select" value="select" checked onchange="unitMode('select')">
        <label for="mode_select">Select Existing Unit</label>
        <input type="radio" name="unit_mode" id="mode_new" value="new" onchange="unitMode('new')">
        <label for="mode_new">Create New Unit</label>
      </div>

      <div class="unit-panel show" id="panel_select">
        <div class="field">
          <label>Unit</label>
          <?php if (empty($availableUnits)): ?>
            <p style="font-size:0.82rem;color:#94a3b8">All existing units already have a QBL config. Create a new unit instead.</p>
          <?php else: ?>
          <select name="deptId">
            <option value="">— Select Unit —</option>
            <?php foreach ($availableUnits as $u): ?>
            <option value="<?php echo $u['id']; ?>"><?php echo htmlspecialchars($u['dept']); ?></option>
            <?php endforeach; ?>
          </select>
          <?php endif; ?>
        </div>
      </div>

      <div class="unit-panel" id="panel_new">
        <div class="field">
          <label>Unit Name *</label>
          <input type="text" name="new_unit_name" placeholder="e.g. Labor &amp; Delivery">
        </div>
        <input type="hidden" name="new_unit_pm" value="1">
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="document.getElementById('addModal').classList.remove('open')">Cancel</button>
        <button type="submit" class="btn btn-primary">Create &amp; Configure</button>
      </div>
    </form>
  </div>
</div>

<script>
function unitMode(m) {
  document.getElementById('panel_select').classList.toggle('show', m === 'select');
  document.getElementById('panel_new').classList.toggle('show', m === 'new');
}
document.getElementById('addModal').addEventListener('click', function(e) {
  if (e.target === this) this.classList.remove('open');
});
</script>
</body>
</html>
