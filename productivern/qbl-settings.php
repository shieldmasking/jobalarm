<?php
require_once 'inc/initializer.php';
require_once 'inc/class.db.php';
require_once 'inc/config.php';
if (empty($_SESSION['account']['accountId'])) {
    header('location: /login.php?r=qbl');
    exit;
}
$accountId = intval($_SESSION['account']['accountId']);
$company   = htmlspecialchars($_SESSION['account']['company'] ?? '');
$userRole  = intval($_SESSION['account']['role'] ?? 0);
$userId    = intval($_SESSION['account']['id'] ?? 0);

// ── POST handlers ─────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');

    if ($_POST['action'] === 'addUnit' && $userRole >= 8) {
        $unitName = substr(trim($_POST['unitName'] ?? ''), 0, 100);
        if ($unitName === '') { echo json_encode(['success' => false, 'message' => 'Unit name required']); exit; }
        Config::get('db')->insert('ProductiveDept', ['dept' => $unitName, 'accountId' => $accountId, 'active' => 1]);
        $newDeptId = intval(Config::get('db')->lastid());
        Config::get('db')->insert('productiveQBLconfig', ['deptId' => $newDeptId, 'accountId' => $accountId, 'active' => 1]);
        $newConfigId = intval(Config::get('db')->lastid());
        echo json_encode(['success' => true, 'deptId' => $newDeptId, 'configId' => $newConfigId, 'unitName' => htmlspecialchars($unitName)]);
        exit;
    }

    if ($_POST['action'] === 'editUnit') {
        $deptId   = intval($_POST['deptId'] ?? 0);
        $unitName = substr(trim($_POST['unitName'] ?? ''), 0, 100);
        if ($deptId < 1 || $unitName === '') { echo json_encode(['success' => false, 'message' => 'Invalid']); exit; }
        $check = Config::get('db')->get_results("SELECT id FROM ProductiveDept WHERE id={$deptId} AND accountId={$accountId} LIMIT 1");
        if (!$check) { echo json_encode(['success' => false, 'message' => 'Not authorized']); exit; }
        Config::get('db')->update('ProductiveDept', ['dept' => $unitName], ['id' => $deptId, 'accountId' => $accountId]);
        echo json_encode(['success' => true]);
        exit;
    }

    if ($_POST['action'] === 'deleteQBL' && $userRole >= 6) {
        $deptId = intval($_POST['deptId'] ?? 0);
        if ($deptId < 1) { echo json_encode(['success' => false]); exit; }
        $check = Config::get('db')->get_results("SELECT id FROM ProductiveDept WHERE id={$deptId} AND accountId={$accountId} LIMIT 1");
        if (!$check) { echo json_encode(['success' => false, 'message' => 'Not authorized']); exit; }
        Config::get('db')->query("UPDATE productiveQBLconfig SET active=0 WHERE deptId={$deptId} AND accountId={$accountId}");
        Config::get('db')->query("DELETE FROM productiveQrTokens WHERE deptId={$deptId} AND page='qbl'");
        echo json_encode(['success' => true]);
        exit;
    }

    if ($_POST['action'] === 'deleteUnit' && $userRole >= 6) {
        $deptId = intval($_POST['deptId'] ?? 0);
        if ($deptId < 1) { echo json_encode(['success' => false]); exit; }
        $check = Config::get('db')->get_results("SELECT id FROM ProductiveDept WHERE id={$deptId} AND accountId={$accountId} LIMIT 1");
        if (!$check) { echo json_encode(['success' => false, 'message' => 'Not authorized']); exit; }
        Config::get('db')->update('ProductiveDept', ['active' => 0], ['id' => $deptId, 'accountId' => $accountId]);
        Config::get('db')->query("UPDATE productiveQBLconfig SET active=0 WHERE deptId={$deptId} AND accountId={$accountId}");
        Config::get('db')->query("DELETE FROM productiveQrTokens WHERE deptId={$deptId} AND page='qbl'");
        echo json_encode(['success' => true]);
        exit;
    }

    echo json_encode(['success' => false]);
    exit;
}

// ── Role-filtered units query ─────────────────────────────────────────────────
if ($userRole >= 8) {
    $units = Config::get('db')->get_results("
        SELECT d.id as deptId, d.dept as unitName, c.id as configId, c.active as configActive
        FROM ProductiveDept d
        LEFT JOIN productiveQBLconfig c ON c.deptId = d.id AND c.accountId = {$accountId}
        WHERE d.accountId = {$accountId} AND d.active = 1
        ORDER BY d.dept
    ");
} else {
    $units = Config::get('db')->get_results("
        SELECT DISTINCT d.id as deptId, d.dept as unitName, c.id as configId, c.active as configActive
        FROM ProductiveDept d
        JOIN productiveDeptXref x ON x.deptId = d.id AND x.userId = {$userId}
        LEFT JOIN productiveQBLconfig c ON c.deptId = d.id AND c.accountId = {$accountId}
        WHERE d.accountId = {$accountId} AND d.active = 1
        ORDER BY d.dept
    ");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>QBL Units — <?php echo $company; ?></title>
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
:root {
  --navy: #1a4a8a; --navy-dark: #163d73; --teal: #0e6b6b; --red: #c0392b;
  --bg: #f0f2f5; --white: #ffffff; --text: #1a2332; --muted: #6b7f96; --border: #dde1e8;
  --font: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}
body { font-family: var(--font); background: var(--bg); color: var(--text); line-height: 1.6; -webkit-font-smoothing: antialiased; }

nav { position: sticky; top: 0; z-index: 100; background: var(--navy); padding: 14px 20px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 2px 8px rgba(0,0,0,.2); }
.nav-brand { color: #fff; font-size: 1.15rem; font-weight: 800; letter-spacing: .3px; text-decoration: none; }
.nav-brand span { font-weight: 400; opacity: .75; }
.nav-links { display: flex; align-items: center; gap: 6px; }
.nav-links a { color: rgba(255,255,255,.75); text-decoration: none; font-size: .82rem; font-weight: 600; padding: 6px 12px; border-radius: 20px; transition: background .15s; }
.nav-links a:hover { color: #fff; background: rgba(255,255,255,.1); }
.nav-links a.nav-logout { color: rgba(255,255,255,.55); font-weight: 500; }

.page-header { background: var(--white); border-bottom: 1px solid var(--border); padding: 22px 24px 18px; }
.page-header h1 { font-size: 1.25rem; font-weight: 800; }
.page-header p { font-size: .82rem; color: var(--muted); margin-top: 3px; }

.main { padding: 24px 16px; max-width: 860px; margin: 0 auto; }

.toolbar { display: flex; justify-content: flex-end; margin-bottom: 16px; }
.btn-add-unit { background: var(--navy); color: #fff; border: none; padding: 9px 18px; border-radius: 8px; font-size: .85rem; font-weight: 700; cursor: pointer; }
.btn-add-unit:hover { background: var(--navy-dark); }

.unit-card { background: var(--white); border-radius: 12px; border: 1px solid var(--border); margin-bottom: 14px; box-shadow: 0 1px 4px rgba(0,0,0,.05); overflow: hidden; display: flex; align-items: center; justify-content: space-between; padding: 16px 18px; gap: 12px; }
.unit-card:hover { border-color: var(--navy); }
.unit-left { display: flex; align-items: center; gap: 12px; flex: 1; min-width: 0; }
.unit-icon { width: 40px; height: 40px; border-radius: 10px; background: #e8f0fb; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0; }
.unit-name { font-size: .97rem; font-weight: 700; color: var(--text); }
.unit-meta { font-size: .78rem; color: var(--muted); margin-top: 1px; }
.btn-edit-unit { background: none; border: none; cursor: pointer; color: var(--muted); font-size: .85rem; padding: 4px 8px; border-radius: 6px; }
.btn-edit-unit:hover { background: var(--border); color: var(--text); }
.btn-delete-unit { background: none; border: none; cursor: pointer; color: var(--muted); font-size: .85rem; padding: 4px 8px; border-radius: 6px; }
.btn-delete-unit:hover { background: #fff0f0; color: var(--red); }
.unit-name-edit { display: none; align-items: center; gap: 8px; flex: 1; }
.unit-name-edit.open { display: flex; }
.unit-name-edit input { flex: 1; padding: 6px 10px; border: 1px solid var(--navy); border-radius: 7px; font-size: .9rem; outline: none; min-width: 0; }
.btn-unit-save   { background: var(--navy); color: #fff; border: none; padding: 6px 14px; border-radius: 7px; font-size: .8rem; font-weight: 700; cursor: pointer; white-space: nowrap; }
.btn-unit-cancel { background: none; color: var(--muted); border: none; font-size: .8rem; cursor: pointer; padding: 6px 8px; }

.badge { display: inline-block; font-size: .7rem; font-weight: 700; padding: 2px 8px; border-radius: 20px; }
.badge-active   { background: #e6f4f4; color: var(--teal); border: 1px solid #b2d8d8; }
.badge-inactive { background: #f0f2f5; color: var(--muted); border: 1px solid var(--border); }

.btn-open { background: var(--navy); color: #fff; border: none; padding: 8px 18px; border-radius: 8px; font-size: .82rem; font-weight: 700; cursor: pointer; text-decoration: none; white-space: nowrap; }
.btn-open:hover { background: var(--navy-dark); }

.empty-state { text-align: center; padding: 52px 20px; color: var(--muted); }
.empty-state .icon { font-size: 2rem; margin-bottom: 12px; }

/* ADD UNIT MODAL */
.modal-overlay { display: none; position: fixed; inset: 0; z-index: 400; background: rgba(10,20,40,.5); align-items: center; justify-content: center; padding: 20px; }
.modal-overlay.open { display: flex; }
.modal-box { background: var(--white); border-radius: 14px; padding: 28px 24px; width: 100%; max-width: 420px; box-shadow: 0 8px 32px rgba(0,0,0,.18); }
.modal-box h3 { font-size: 1rem; font-weight: 800; margin-bottom: 16px; }
.modal-box label { display: block; font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: var(--muted); margin-bottom: 6px; }
.modal-box input[type="text"] { width: 100%; padding: 9px 10px; border: 1px solid var(--border); border-radius: 8px; font-size: .9rem; outline: none; margin-bottom: 18px; }
.modal-box input:focus { border-color: var(--navy); }
.modal-btns { display: flex; gap: 8px; }
.btn-modal-save { flex: 1; background: var(--navy); color: #fff; border: none; padding: 10px; border-radius: 8px; font-size: .85rem; font-weight: 700; cursor: pointer; }
.btn-modal-save:hover { background: var(--navy-dark); }
.btn-modal-cancel { background: var(--bg); color: var(--muted); border: 1px solid var(--border); padding: 10px 18px; border-radius: 8px; font-size: .85rem; font-weight: 600; cursor: pointer; }

.btn-delete-qbl { width: 100%; background: none; border: 1px solid var(--border); color: var(--text); padding: 11px 14px; border-radius: 8px; font-size: .88rem; font-weight: 600; cursor: pointer; text-align: left; margin-bottom: 8px; }
.btn-delete-qbl:hover { border-color: var(--red); color: var(--red); background: #fff0f0; }
.btn-delete-unit-confirm { width: 100%; background: var(--red); border: none; color: #fff; padding: 11px 14px; border-radius: 8px; font-size: .88rem; font-weight: 700; cursor: pointer; text-align: left; margin-bottom: 8px; }
.btn-delete-unit-confirm:hover { background: #a93226; }
.delete-modal-name { font-weight: 800; color: var(--text); }
.delete-modal-note { font-size: .78rem; color: var(--muted); margin-top: 12px; line-height: 1.5; }

#toast { position: fixed; bottom: 24px; right: 24px; background: var(--teal); color: #fff; padding: 12px 20px; border-radius: 10px; font-size: .85rem; font-weight: 600; box-shadow: 0 4px 16px rgba(0,0,0,.2); z-index: 9999; display: none; }
#toast.error { background: var(--red); }

footer { background: #0d2040; color: rgba(255,255,255,.5); text-align: center; padding: 24px 20px; font-size: .75rem; line-height: 1.8; margin-top: 48px; }
footer strong { color: rgba(255,255,255,.8); }
</style>
</head>
<body>

<nav>
  <a class="nav-brand" href="/qbl-settings.php">Productive<span>RN</span> &mdash; QBL Alerts</a>
  <div class="nav-links">
    <a href="/logout.php" class="nav-logout">Logout</a>
  </div>
</nav>

<div class="page-header">
  <h1>QBL Units &mdash; <?php echo $company; ?></h1>
  <p>Select a unit to view reports, configure supplies, and manage users</p>
</div>

<div class="main">
  <?php if ($userRole >= 8): ?>
  <div class="toolbar">
    <button class="btn-add-unit" onclick="openAddUnit()">&#43; Add Unit</button>
  </div>
  <?php endif; ?>

  <div id="unit-list">
  <?php if (empty($units)): ?>
    <div class="empty-state">
      <div class="icon">&#127973;</div>
      <p>No units found. <?php echo $userRole >= 8 ? 'Add one above.' : 'Contact your administrator.'; ?></p>
    </div>
  <?php else: foreach ($units as $unit):
    $configId   = intval($unit['configId'] ?? 0);
    $deptId     = intval($unit['deptId']);
    $unitName   = htmlspecialchars($unit['unitName']);
    $hasConfig  = $configId > 0;
    $isActive   = $hasConfig && intval($unit['configActive']) === 1;
  ?>
  <div class="unit-card" id="unit-card-<?php echo $deptId; ?>">
    <div class="unit-left">
      <div class="unit-icon">&#127973;</div>
      <div id="unit-name-display-<?php echo $deptId; ?>">
        <div style="display:flex;align-items:center;gap:8px">
          <span class="unit-name"><?php echo $unitName; ?></span>
          <button class="btn-edit-unit" title="Rename" onclick="openEditUnit(<?php echo $deptId; ?>, '<?php echo addslashes($unit['unitName']); ?>')">&#9998;</button>
          <?php if ($userRole >= 6): ?>
          <button class="btn-delete-unit" title="Delete" onclick="openDeleteModal(<?php echo $deptId; ?>, '<?php echo addslashes($unit['unitName']); ?>', <?php echo $hasConfig ? 'true' : 'false'; ?>)">&#128465;</button>
          <?php endif; ?>
        </div>
        <div class="unit-meta">
          <?php if ($isActive): ?>
            <span class="badge badge-active">Active</span>
          <?php elseif ($hasConfig): ?>
            <span class="badge badge-inactive">Inactive</span>
          <?php else: ?>
            <span class="badge badge-inactive">No config</span>
          <?php endif; ?>
        </div>
      </div>
      <div class="unit-name-edit" id="unit-name-edit-<?php echo $deptId; ?>">
        <input type="text" id="unit-name-input-<?php echo $deptId; ?>" value="<?php echo $unitName; ?>" maxlength="100">
        <button class="btn-unit-save" onclick="saveUnitName(<?php echo $deptId; ?>)">Save</button>
        <button class="btn-unit-cancel" onclick="cancelEditUnit(<?php echo $deptId; ?>)">Cancel</button>
      </div>
    </div>
    <?php if ($hasConfig): ?>
    <a class="btn-open" href="/qbl-portal.php?id=<?php echo $configId; ?>">Open &rarr;</a>
    <?php endif; ?>
  </div>
  <?php endforeach; endif; ?>
  </div>
</div>

<?php if ($userRole >= 8): ?>
<div class="modal-overlay" id="addUnitModal">
  <div class="modal-box">
    <h3>Add New Unit</h3>
    <label for="new-unit-name">Unit Name</label>
    <input type="text" id="new-unit-name" placeholder="e.g. Labor &amp; Delivery" maxlength="100">
    <div class="modal-btns">
      <button class="btn-modal-cancel" onclick="closeAddUnit()">Cancel</button>
      <button class="btn-modal-save" onclick="submitAddUnit()">Add Unit</button>
    </div>
  </div>
</div>
<?php endif; ?>

<div id="toast"></div>

<?php if ($userRole >= 6): ?>
<div class="modal-overlay" id="deleteModal">
  <div class="modal-box">
    <h3>Delete &mdash; <span class="delete-modal-name" id="deleteModalName"></span></h3>
    <p style="font-size:.85rem;color:var(--muted);margin-bottom:16px">Choose what to delete:</p>
    <button class="btn-delete-qbl" id="btnDeleteQBL" onclick="confirmDelete('qbl')">&#128465; Delete QBL Config Only<br><span style="font-size:.75rem;font-weight:400;color:var(--muted)">Removes supplies, QR code, and alert config. Keeps the unit.</span></button>
    <button class="btn-delete-unit-confirm" id="btnDeleteUnit" onclick="confirmDelete('unit')">&#128465; Delete Entire Unit<br><span style="font-size:.75rem;font-weight:400;opacity:.85">Removes the unit, QBL config, and QR code permanently.</span></button>
    <button class="btn-modal-cancel" style="width:100%;margin-top:4px" onclick="closeDeleteModal()">Cancel</button>
    <p class="delete-modal-note">These actions cannot be undone. Historical QBL reports will be retained.</p>
  </div>
</div>
<?php endif; ?>

<footer>
  <strong>ProductivRN</strong> &mdash; QBL Alerts<br>
  &copy; <?php echo date('Y'); ?> ProductivRN. All rights reserved.
</footer>

<script>
function showToast(msg, isError) {
  const t = document.getElementById('toast');
  t.textContent = msg; t.className = isError ? 'error' : '';
  t.style.display = 'block'; clearTimeout(t._tid);
  t._tid = setTimeout(() => { t.style.display = 'none'; }, 3000);
}

function openAddUnit() {
  document.getElementById('new-unit-name').value = '';
  document.getElementById('addUnitModal').classList.add('open');
  setTimeout(() => document.getElementById('new-unit-name').focus(), 80);
}
function closeAddUnit() { document.getElementById('addUnitModal').classList.remove('open'); }
document.getElementById('addUnitModal')?.addEventListener('click', function(e) { if (e.target === this) closeAddUnit(); });
document.getElementById('new-unit-name')?.addEventListener('keydown', function(e) {
  if (e.key === 'Enter') submitAddUnit();
  if (e.key === 'Escape') closeAddUnit();
});

function submitAddUnit() {
  const name = document.getElementById('new-unit-name').value.trim();
  if (!name) { showToast('Please enter a unit name', true); return; }
  const btn = document.querySelector('#addUnitModal .btn-modal-save');
  btn.disabled = true; btn.textContent = 'Adding…';
  fetch(window.location.pathname, {
    method: 'POST', headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: new URLSearchParams({action: 'addUnit', unitName: name}).toString()
  })
  .then(r => r.json())
  .then(data => {
    if (!data.success) { showToast(data.message || 'Failed', true); return; }
    closeAddUnit(); showToast('Unit added', false);
    location.reload();
  })
  .catch(() => showToast('Network error', true))
  .finally(() => { btn.disabled = false; btn.textContent = 'Add Unit'; });
}

function openEditUnit(deptId, currentName) {
  document.getElementById('unit-name-display-' + deptId).style.display = 'none';
  const editEl = document.getElementById('unit-name-edit-' + deptId);
  editEl.classList.add('open');
  const inp = document.getElementById('unit-name-input-' + deptId);
  inp.value = currentName; inp.focus(); inp.select();
  inp.onkeydown = function(e) {
    if (e.key === 'Enter') saveUnitName(deptId);
    if (e.key === 'Escape') cancelEditUnit(deptId);
  };
}
function cancelEditUnit(deptId) {
  document.getElementById('unit-name-display-' + deptId).style.display = '';
  document.getElementById('unit-name-edit-' + deptId).classList.remove('open');
}
var _deleteDeptId = null;
var _deleteHasConfig = false;

function openDeleteModal(deptId, name, hasConfig) {
  _deleteDeptId = deptId;
  _deleteHasConfig = hasConfig;
  document.getElementById('deleteModalName').textContent = name;
  document.getElementById('btnDeleteQBL').style.display = hasConfig ? '' : 'none';
  document.getElementById('deleteModal').classList.add('open');
}
function closeDeleteModal() { document.getElementById('deleteModal').classList.remove('open'); }
document.getElementById('deleteModal')?.addEventListener('click', function(e) { if (e.target === this) closeDeleteModal(); });

function confirmDelete(type) {
  if (!_deleteDeptId) return;
  const action = type === 'unit' ? 'deleteUnit' : 'deleteQBL';
  fetch(window.location.pathname, {
    method: 'POST', headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: new URLSearchParams({action, deptId: _deleteDeptId}).toString()
  })
  .then(r => r.json())
  .then(data => {
    if (!data.success) { showToast(data.message || 'Failed', true); return; }
    closeDeleteModal();
    showToast(type === 'unit' ? 'Unit deleted' : 'QBL config deleted', false);
    location.reload();
  })
  .catch(() => showToast('Network error', true));
}

function saveUnitName(deptId) {
  const name = document.getElementById('unit-name-input-' + deptId).value.trim();
  if (!name) { showToast('Name cannot be empty', true); return; }
  const btn = document.querySelector('#unit-card-' + deptId + ' .btn-unit-save');
  btn.disabled = true; btn.textContent = 'Saving…';
  fetch(window.location.pathname, {
    method: 'POST', headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: new URLSearchParams({action: 'editUnit', deptId, unitName: name}).toString()
  })
  .then(r => r.json())
  .then(data => {
    if (!data.success) { showToast('Failed to rename', true); return; }
    document.querySelector('#unit-name-display-' + deptId + ' .unit-name').textContent = name;
    cancelEditUnit(deptId); showToast('Unit renamed', false);
  })
  .catch(() => showToast('Network error', true))
  .finally(() => { btn.disabled = false; btn.textContent = 'Save'; });
}
</script>
</body>
</html>
