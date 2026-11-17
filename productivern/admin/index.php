<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../inc/class.db.php';
require_once __DIR__ . '/../inc/config.php';

$db  = Config::get('db');
$me  = $_SESSION['platform_admin'];
$msg = '';
$err = '';

/* ── POST handlers ─────────────────────────────────────────────── */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $name    = trim($db->filter($_POST['name'] ?? ''));
        $tz      = intval($_POST['timeZone'] ?? 0);
        $pp      = intval($_POST['payPeriod'] ?? 14);
        $ppFirst = $db->filter($_POST['payPeriodFirst'] ?? date('Y-m-d'));

        if ($name === '') {
            $err = 'Hospital name is required.';
        } else {
            $db->insert('productiveAccount', [
                'name'           => $name,
                'active'         => 1,
                'timeZone'       => $tz,
                'payPeriod'      => $pp,
                'payPeriodFirst' => $ppFirst,
            ]);
            $msg = "Account <strong>" . htmlspecialchars($name) . "</strong> created.";
        }

    } elseif ($action === 'archive' || $action === 'unarchive') {
        $id  = intval($_POST['id'] ?? 0);
        $val = ($action === 'archive') ? 0 : 1;
        if ($id > 0) {
            $db->update('productiveAccount', ['active' => $val], ['id' => $id], 1);
            $msg = $action === 'archive' ? 'Account archived.' : 'Account restored.';
        }

    } elseif ($action === 'edit') {
        $id      = intval($_POST['id'] ?? 0);
        $name    = trim($db->filter($_POST['name'] ?? ''));
        $tz      = intval($_POST['timeZone'] ?? 0);
        $pp      = intval($_POST['payPeriod'] ?? 14);
        $ppFirst = $db->filter($_POST['payPeriodFirst'] ?? '');

        if ($id > 0 && $name !== '') {
            $db->update('productiveAccount', [
                'name'           => $name,
                'timeZone'       => $tz,
                'payPeriod'      => $pp,
                'payPeriodFirst' => $ppFirst,
            ], ['id' => $id], 1);
            $msg = 'Account updated.';
        }
    }
}

/* ── Fetch accounts ─────────────────────────────────────────────── */
$filter = ($_GET['f'] ?? 'active') === 'archived' ? 'archived' : 'active';
$activeWhere = $filter === 'archived' ? '0' : '1';

$accounts = $db->get_results(
    "SELECT a.id, a.name, a.active, a.timeZone, a.payPeriod, a.payPeriodFirst,
        (SELECT COUNT(*) FROM productiveUser WHERE accountId=a.id AND active=1) as userCount,
        (SELECT COUNT(*) FROM ProductiveDept WHERE accountId=a.id AND active=1) as unitCount
     FROM productiveAccount a
     WHERE a.active = {$activeWhere}
     ORDER BY a.name ASC"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Accounts — ProductivRN Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex,nofollow">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f1f5f9; color: #1e293b; font-size: 14px; }

    /* ── Top bar ── */
    .topbar { background: #0f1525; padding: 0 1.5rem; height: 52px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 100; }
    .topbar-brand { display: flex; align-items: center; gap: 0.5rem; font-weight: 800; font-size: 1.05rem; color: #f1f5f9; letter-spacing: -0.02em; }
    .topbar-brand span { color: #60a5fa; }
    .badge-admin { font-size: 0.6rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; background: rgba(96,165,250,0.15); color: #60a5fa; border: 1px solid rgba(96,165,250,0.3); border-radius: 4px; padding: 2px 6px; }
    .topbar-right { display: flex; align-items: center; gap: 1rem; }
    .topbar-user { font-size: 0.8rem; color: #94a3b8; }
    .btn-logout { font-size: 0.78rem; color: #64748b; background: none; border: 1px solid #334155; border-radius: 5px; padding: 4px 10px; cursor: pointer; text-decoration: none; transition: color 0.15s, border-color 0.15s; }
    .btn-logout:hover { color: #f1f5f9; border-color: #64748b; }

    /* ── Layout ── */
    .page { max-width: 1100px; margin: 0 auto; padding: 2rem 1.5rem; }
    .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.75rem; }
    .page-title { font-size: 1.2rem; font-weight: 700; color: #0f172a; }

    /* ── Tabs ── */
    .tabs { display: flex; gap: 0; border-bottom: 2px solid #e2e8f0; margin-bottom: 1.5rem; }
    .tab { padding: 0.5rem 1.1rem; font-size: 0.85rem; font-weight: 600; color: #64748b; text-decoration: none; border-bottom: 2px solid transparent; margin-bottom: -2px; transition: color 0.15s, border-color 0.15s; }
    .tab:hover { color: #1e293b; }
    .tab.active { color: #2563eb; border-bottom-color: #2563eb; }

    /* ── Alert ── */
    .alert { padding: 0.65rem 1rem; border-radius: 6px; margin-bottom: 1.25rem; font-size: 0.85rem; }
    .alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
    .alert-error   { background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; }

    /* ── Table ── */
    .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; overflow: hidden; }
    table { width: 100%; border-collapse: collapse; }
    thead th { background: #f8fafc; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; padding: 0.65rem 1rem; text-align: left; border-bottom: 1px solid #e2e8f0; white-space: nowrap; }
    tbody tr { border-bottom: 1px solid #f1f5f9; transition: background 0.1s; }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: #f8fafc; }
    td { padding: 0.7rem 1rem; vertical-align: middle; }
    .acct-name { font-weight: 600; color: #1e293b; }
    .acct-name a { color: #2563eb; text-decoration: none; }
    .acct-name a:hover { text-decoration: underline; }
    .pill { display: inline-block; font-size: 0.7rem; font-weight: 700; padding: 2px 8px; border-radius: 20px; }
    .pill-active   { background: #dcfce7; color: #15803d; }
    .pill-archived { background: #f1f5f9; color: #94a3b8; }
    .count-badge { display: inline-block; background: #f1f5f9; color: #475569; font-size: 0.78rem; font-weight: 600; padding: 2px 8px; border-radius: 12px; }
    .empty-state { padding: 3rem 1rem; text-align: center; color: #94a3b8; font-size: 0.9rem; }

    /* ── Buttons ── */
    .btn { display: inline-block; padding: 0.42rem 0.9rem; border-radius: 6px; font-size: 0.82rem; font-weight: 600; cursor: pointer; border: none; text-decoration: none; transition: background 0.15s, opacity 0.15s; }
    .btn-primary { background: #2563eb; color: #fff; }
    .btn-primary:hover { background: #1d4ed8; }
    .btn-sm { padding: 0.28rem 0.7rem; font-size: 0.78rem; }
    .btn-outline { background: none; border: 1px solid #e2e8f0; color: #475569; }
    .btn-outline:hover { background: #f8fafc; border-color: #cbd5e1; }
    .btn-danger-outline { background: none; border: 1px solid #fecaca; color: #dc2626; }
    .btn-danger-outline:hover { background: #fef2f2; }
    .btn-restore { background: none; border: 1px solid #bbf7d0; color: #16a34a; }
    .btn-restore:hover { background: #f0fdf4; }
    .actions { display: flex; gap: 0.4rem; align-items: center; }

    /* ── Modal ── */
    .modal-bg { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.45); z-index: 200; align-items: center; justify-content: center; }
    .modal-bg.open { display: flex; }
    .modal-box { background: #fff; border-radius: 12px; width: 100%; max-width: 480px; padding: 1.75rem; box-shadow: 0 20px 60px rgba(0,0,0,0.15); }
    .modal-title { font-size: 1rem; font-weight: 700; color: #0f172a; margin-bottom: 1.25rem; }
    .field { margin-bottom: 1rem; }
    .field label { display: block; font-size: 0.78rem; font-weight: 600; color: #374151; margin-bottom: 0.35rem; }
    .field input, .field select { width: 100%; padding: 0.55rem 0.75rem; border: 1.5px solid #d1d5db; border-radius: 6px; font-size: 0.88rem; color: #0f172a; background: #fff; outline: none; }
    .field input:focus, .field select:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
    .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
    .modal-footer { display: flex; justify-content: flex-end; gap: 0.6rem; margin-top: 1.5rem; }
  </style>
</head>
<body>

<!-- Top bar -->
<div class="topbar">
  <div class="topbar-brand">
    <svg width="22" height="22" viewBox="0 0 30 30" fill="none">
      <circle cx="15" cy="15" r="13" stroke="#3b82f6" stroke-width="1.5" fill="rgba(59,130,246,0.08)"/>
      <path d="M15 8v14M8 15h14" stroke="#3b82f6" stroke-width="2" stroke-linecap="round"/>
      <circle cx="15" cy="15" r="3" fill="#3b82f6"/>
    </svg>
    Productiv<span>RN</span>&nbsp;<span class="badge-admin">Admin</span>
  </div>
  <div class="topbar-right">
    <span class="topbar-user"><?php echo htmlspecialchars($me['first_name'] . ' ' . $me['last_name']); ?></span>
    <a href="logout.php" class="btn-logout">Sign Out</a>
  </div>
</div>

<div class="page">
  <div class="page-header">
    <div class="page-title">Hospital Accounts</div>
    <button class="btn btn-primary" onclick="openAddModal()">+ Add Account</button>
  </div>

  <?php if ($msg): ?><div class="alert alert-success"><?php echo $msg; ?></div><?php endif; ?>
  <?php if ($err): ?><div class="alert alert-error"><?php echo htmlspecialchars($err); ?></div><?php endif; ?>

  <?php
  $pvStats = $db->get_results("SELECT page, COUNT(*) AS total, SUM(visited_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)) AS last7, SUM(visited_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)) AS last30 FROM qbl_page_views GROUP BY page");
  $pv = [];
  foreach ($pvStats as $r) { $pv[$r['page']] = $r; }
  $pvPages = ['landing' => 'QBL Landing', 'pricing' => 'QBL Pricing'];
  ?>
  <div style="display:flex;gap:1rem;margin-bottom:1.5rem;flex-wrap:wrap;">
    <?php foreach ($pvPages as $key => $label): $r = $pv[$key] ?? ['total'=>0,'last7'=>0,'last30'=>0]; ?>
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:1rem 1.25rem;min-width:200px;flex:1;">
      <div style="font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:#64748b;margin-bottom:0.6rem;"><?php echo $label; ?> Page Views</div>
      <div style="display:flex;gap:1.5rem;">
        <div><div style="font-size:1.4rem;font-weight:800;color:#0f172a;"><?php echo intval($r['last7']); ?></div><div style="font-size:0.72rem;color:#94a3b8;">Last 7 days</div></div>
        <div><div style="font-size:1.4rem;font-weight:800;color:#0f172a;"><?php echo intval($r['last30']); ?></div><div style="font-size:0.72rem;color:#94a3b8;">Last 30 days</div></div>
        <div><div style="font-size:1.4rem;font-weight:800;color:#0f172a;"><?php echo intval($r['total']); ?></div><div style="font-size:0.72rem;color:#94a3b8;">All time</div></div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="tabs">
    <a href="?f=active"   class="tab <?php echo $filter === 'active'   ? 'active' : ''; ?>">Active</a>
    <a href="?f=archived" class="tab <?php echo $filter === 'archived' ? 'active' : ''; ?>">Archived</a>
  </div>

  <div class="card">
    <table>
      <thead>
        <tr>
          <th>Hospital / Account</th>
          <th>Users</th>
          <th>Units</th>
          <th>Status</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($accounts)): ?>
        <tr><td colspan="5"><div class="empty-state">No <?php echo $filter; ?> accounts found.</div></td></tr>
        <?php else: ?>
        <?php foreach ($accounts as $a): ?>
        <tr>
          <td class="acct-name">
            <a href="account.php?id=<?php echo $a['id']; ?>"><?php echo htmlspecialchars($a['name']); ?></a>
          </td>
          <td><span class="count-badge"><?php echo intval($a['userCount']); ?></span></td>
          <td><span class="count-badge"><?php echo intval($a['unitCount']); ?></span></td>
          <td>
            <?php if ($a['active']): ?>
              <span class="pill pill-active">Active</span>
            <?php else: ?>
              <span class="pill pill-archived">Archived</span>
            <?php endif; ?>
          </td>
          <td>
            <div class="actions">
              <a href="account.php?id=<?php echo $a['id']; ?>" class="btn btn-sm btn-outline">Manage</a>
              <button class="btn btn-sm btn-outline"
                onclick="openEditModal(<?php echo $a['id']; ?>, <?php echo htmlspecialchars(json_encode($a['name']), ENT_QUOTES); ?>, <?php echo intval($a['timeZone']); ?>, <?php echo intval($a['payPeriod']); ?>, <?php echo htmlspecialchars(json_encode($a['payPeriodFirst']), ENT_QUOTES); ?>)">
                Edit
              </button>
              <?php if ($a['active']): ?>
              <form method="post" onsubmit="return confirm('Archive this account?');" style="display:inline">
                <input type="hidden" name="action" value="archive">
                <input type="hidden" name="id" value="<?php echo $a['id']; ?>">
                <button type="submit" class="btn btn-sm btn-danger-outline">Archive</button>
              </form>
              <?php else: ?>
              <form method="post" style="display:inline">
                <input type="hidden" name="action" value="unarchive">
                <input type="hidden" name="id" value="<?php echo $a['id']; ?>">
                <button type="submit" class="btn btn-sm btn-restore">Restore</button>
              </form>
              <?php endif; ?>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Add Account Modal -->
<div class="modal-bg" id="addModal">
  <div class="modal-box">
    <div class="modal-title">Add New Account</div>
    <form method="post">
      <input type="hidden" name="action" value="add">
      <div class="field">
        <label>Hospital Name *</label>
        <input type="text" name="name" placeholder="Mercy Hospital" required>
      </div>
      <div class="field-row">
        <div class="field">
          <label>Pay Period (days)</label>
          <select name="payPeriod">
            <option value="14">14 — Bi-weekly</option>
            <option value="7">7 — Weekly</option>
          </select>
        </div>
        <div class="field">
          <label>Pay Period Start</label>
          <input type="date" name="payPeriodFirst" value="<?php echo date('Y-m-d'); ?>">
        </div>
      </div>
      <div class="field">
        <label>Time Zone Offset (hrs from UTC)</label>
        <select name="timeZone">
          <option value="-5">-5 Eastern</option>
          <option value="-6">-6 Central</option>
          <option value="-7">-7 Mountain</option>
          <option value="-8">-8 Pacific</option>
          <option value="-9">-9 Alaska</option>
          <option value="-10">-10 Hawaii</option>
        </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('addModal')">Cancel</button>
        <button type="submit" class="btn btn-primary">Create Account</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Account Modal -->
<div class="modal-bg" id="editModal">
  <div class="modal-box">
    <div class="modal-title">Edit Account</div>
    <form method="post">
      <input type="hidden" name="action" value="edit">
      <input type="hidden" name="id" id="editId">
      <div class="field">
        <label>Hospital Name *</label>
        <input type="text" name="name" id="editName" required>
      </div>
      <div class="field-row">
        <div class="field">
          <label>Pay Period (days)</label>
          <select name="payPeriod" id="editPayPeriod">
            <option value="14">14 — Bi-weekly</option>
            <option value="7">7 — Weekly</option>
          </select>
        </div>
        <div class="field">
          <label>Pay Period Start</label>
          <input type="date" name="payPeriodFirst" id="editPayPeriodFirst">
        </div>
      </div>
      <div class="field">
        <label>Time Zone Offset (hrs from UTC)</label>
        <select name="timeZone" id="editTimeZone">
          <option value="-5">-5 Eastern</option>
          <option value="-6">-6 Central</option>
          <option value="-7">-7 Mountain</option>
          <option value="-8">-8 Pacific</option>
          <option value="-9">-9 Alaska</option>
          <option value="-10">-10 Hawaii</option>
        </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('editModal')">Cancel</button>
        <button type="submit" class="btn btn-primary">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<script>
function openAddModal() {
  document.getElementById('addModal').classList.add('open');
}
function openEditModal(id, name, tz, pp, ppFirst) {
  document.getElementById('editId').value = id;
  document.getElementById('editName').value = name;
  document.getElementById('editPayPeriod').value = pp;
  document.getElementById('editPayPeriodFirst').value = ppFirst;
  var tzSel = document.getElementById('editTimeZone');
  for (var i = 0; i < tzSel.options.length; i++) {
    if (parseInt(tzSel.options[i].value) === tz) { tzSel.selectedIndex = i; break; }
  }
  document.getElementById('editModal').classList.add('open');
}
function closeModal(id) {
  document.getElementById(id).classList.remove('open');
}
document.querySelectorAll('.modal-bg').forEach(function(m) {
  m.addEventListener('click', function(e) { if (e.target === m) m.classList.remove('open'); });
});
</script>
</body>
</html>
