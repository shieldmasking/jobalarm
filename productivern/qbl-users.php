<?php
require_once 'inc/initializer.php';
header('location: /qbl-settings.php');
exit;
$accountId = intval($_SESSION['account']['accountId']);
$company   = htmlspecialchars($_SESSION['account']['company'] ?? '');

// Ensure coverage table exists
Config::get('db')->query("CREATE TABLE IF NOT EXISTS qbl_coverage (
    id INT AUTO_INCREMENT PRIMARY KEY,
    accountId INT NOT NULL,
    coveredUserId INT NOT NULL,
    backupUserId INT NOT NULL,
    threshold INT DEFAULT 0,
    endsAt DATETIME NOT NULL,
    active TINYINT DEFAULT 1,
    restore_data TEXT,
    created DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_account (accountId),
    INDEX idx_active (active, endsAt)
)");

// ── Auto-expire past coverage ─────────────────────────────────────────────────
function restoreCoverage($c) {
    $rd = json_decode($c['restore_data'] ?? '{}', true);
    foreach (($rd['depts'] ?? []) as $dept) {
        $deptId = intval($dept['deptId']);
        Config::get('db')->update('productiveDeptXref',
            ['escAlerts' => intval($dept['coveredOrigEsc'])],
            ['userId' => intval($c['coveredUserId']), 'deptId' => $deptId]
        );
        if (!empty($dept['backupInserted'])) {
            Config::get('db')->delete('productiveDeptXref',
                ['userId' => intval($c['backupUserId']), 'deptId' => $deptId]
            );
        } else {
            Config::get('db')->update('productiveDeptXref',
                ['escAlerts' => intval($dept['backupOrigEsc'])],
                ['userId' => intval($c['backupUserId']), 'deptId' => $deptId]
            );
        }
    }
    $origThresh = intval($rd['backupOrigThreshold'] ?? 0);
    Config::get('db')->update('productiveUser',
        ['qblWarnMin' => $origThresh],
        ['id' => intval($c['backupUserId'])]
    );
    Config::get('db')->update('qbl_coverage', ['active' => 0], ['id' => intval($c['id'])]);
}
$expired = Config::get('db')->get_results(
    "SELECT * FROM qbl_coverage WHERE accountId={$accountId} AND active=1 AND endsAt < NOW()"
);
if ($expired) foreach ($expired as $exp) restoreCoverage($exp);

// ── POST handlers ─────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    // ── Form-POST (redirect) actions ──────────────────────────────────────────
    $action = $_POST['action'];

    if ($action === 'create_user' || $action === 'add_existing_user') {
        $redir = '/qbl-users.php';

        if ($action === 'create_user') {
            $first  = trim($_POST['first_name'] ?? '');
            $last   = trim($_POST['last_name']  ?? '');
            $email  = strtolower(trim($_POST['email'] ?? ''));
            $role   = intval($_POST['role'] ?? 5);
            $deptId = intval($_POST['deptId'] ?? 0);
            $mobile = preg_replace('/\D/', '', $_POST['mobile'] ?? '');
            $sms    = (strlen($mobile) === 10) ? intval($_POST['sms'] ?? 0) : 0;
            $thresh = intval($_POST['threshold'] ?? 0);
            if ($role < 5 || $role > 10) $role = 5;
            if ($first && $last && $email) {
                $dupEmail  = Config::get('db')->get_results("SELECT id FROM productiveUser WHERE userName='" . Config::get('db')->filter($email) . "' LIMIT 1");
                $dupMobile = $mobile ? Config::get('db')->get_results("SELECT id FROM productiveUser WHERE mobile='" . Config::get('db')->filter($mobile) . "' LIMIT 1") : false;
                if ($dupEmail)  { header('location: ' . $redir . '?msg=dup_email');  exit; }
                if ($dupMobile) { header('location: ' . $redir . '?msg=dup_mobile'); exit; }
                $token       = bin2hex(random_bytes(32));
                $placeholder = password_hash(bin2hex(random_bytes(16)), PASSWORD_BCRYPT);
                $mobileE     = $mobile ? openssl_encrypt($mobile, 'AES-128-CTR', ENCRYPT_KEY, 0, ENCRYPT_IV) : '';
                $newId = Config::get('db')->insert('productiveUser', [
                    'first_name' => Config::get('db')->filter($first),
                    'last_name'  => Config::get('db')->filter($last),
                    'accountId'  => $accountId,
                    'email'      => Config::get('db')->filter($email),
                    'email2'     => Config::get('db')->filter($email),
                    'userName'   => Config::get('db')->filter($email),
                    'pwd'        => $placeholder,
                    'temp'       => $token,
                    'role'       => $role,
                    'active'     => 1,
                    'mobile'     => $mobile,
                    'mobile_E'   => $mobileE,
                    'qblWarnMin' => $thresh,
                ]);
                if ($newId) {
                    if ($deptId) {
                        $validDept = Config::get('db')->get_results("SELECT id FROM ProductiveDept WHERE id={$deptId} AND accountId={$accountId} LIMIT 1");
                        if ($validDept) Config::get('db')->insert('productiveDeptXref', ['userId' => $newId, 'deptId' => $deptId, 'primaryUnit' => 0, 'unitAssigned' => 0, 'escAlerts' => $sms]);
                    }
                    $setupLink = 'https://productivern.com/set-password.php?t=' . $token;
                    $headers   = "MIME-Version: 1.0\r\nContent-type:text/html;charset=UTF-8\r\nFrom: <noreply@productivern.com>\r\n";
                    $message   = "<html><body style='font-family:-apple-system,BlinkMacSystemFont,Segoe UI,sans-serif;color:#1e293b'>
<p>Hi {$first},</p>
<p>Your ProductivRN account has been created. Click below to set your password:</p>
<p><a href='{$setupLink}' style='background:#1a4a8a;color:#fff;padding:10px 20px;border-radius:6px;text-decoration:none;font-weight:700'>Set Up My Account</a></p>
<p style='font-size:0.85em;color:#64748b'>Or copy: {$setupLink}</p>
<p>Thank you,<br>ProductivRN</p></body></html>";
                    mail($email, 'Set up your ProductivRN account', $message, $headers);
                }
            }
            header('location: ' . $redir . '?msg=saved'); exit;
        }

        if ($action === 'add_existing_user') {
            $uid    = intval($_POST['userId'] ?? 0);
            $deptId = intval($_POST['deptId'] ?? 0);
            $mobile = preg_replace('/\D/', '', $_POST['mobile'] ?? '');
            $sms    = (strlen($mobile) === 10) ? intval($_POST['sms'] ?? 0) : 0;
            $thresh = intval($_POST['threshold'] ?? 0);
            if ($uid && $deptId) {
                $valid = Config::get('db')->get_results("SELECT id FROM productiveUser WHERE id={$uid} AND accountId={$accountId} AND role>=5 AND role<=10 LIMIT 1");
                $validDept = Config::get('db')->get_results("SELECT id FROM ProductiveDept WHERE id={$deptId} AND accountId={$accountId} LIMIT 1");
                if ($valid && $validDept) {
                    if ($mobile) {
                        $mobileE = openssl_encrypt($mobile, 'AES-128-CTR', ENCRYPT_KEY, 0, ENCRYPT_IV);
                        Config::get('db')->update('productiveUser', ['mobile' => $mobile, 'mobile_E' => $mobileE, 'qblWarnMin' => $thresh], ['id' => $uid]);
                    }
                    $xref = Config::get('db')->get_results("SELECT id FROM productiveDeptXref WHERE userId={$uid} AND deptId={$deptId} LIMIT 1");
                    if ($xref) Config::get('db')->update('productiveDeptXref', ['escAlerts' => $sms], ['userId' => $uid, 'deptId' => $deptId]);
                    else Config::get('db')->insert('productiveDeptXref', ['userId' => $uid, 'deptId' => $deptId, 'primaryUnit' => 0, 'unitAssigned' => 0, 'escAlerts' => $sms]);
                }
            }
            header('location: ' . $redir . '?msg=saved'); exit;
        }
    }

    header('Content-Type: application/json');

    if ($_POST['action'] === 'updateUser') {
        $targetId  = intval($_POST['userId'] ?? 0);
        $mobile    = preg_replace('/\D/', '', $_POST['mobile'] ?? '');
        $threshold = intval($_POST['threshold'] ?? 0);
        $check = Config::get('db')->get_results("SELECT id FROM productiveUser WHERE id={$targetId} AND accountId={$accountId} LIMIT 1");
        if (!$check) { echo json_encode(['success' => false]); exit; }
        $ciphering = "AES-128-CTR";
        $mobileE   = openssl_encrypt($mobile, $ciphering, ENCRYPT_KEY, 0, ENCRYPT_IV);
        Config::get('db')->update('productiveUser',
            ['mobile' => $mobile, 'mobile_E' => $mobileE, 'qblWarnMin' => $threshold],
            ['id' => $targetId, 'accountId' => $accountId]
        );
        foreach ($_POST as $key => $val) {
            if (strpos($key, 'esc_') === 0) {
                $deptId = intval(substr($key, 4));
                Config::get('db')->update('productiveDeptXref',
                    ['escAlerts' => intval($val) ? 1 : 0],
                    ['userId' => $targetId, 'deptId' => $deptId]
                );
            }
        }
        echo json_encode(['success' => true]);
        exit;
    }

    if ($_POST['action'] === 'setCoverage') {
        $coveredId = intval($_POST['coveredUserId'] ?? 0);
        $backupId  = intval($_POST['backupUserId']  ?? 0);
        $threshold = intval($_POST['threshold'] ?? 0);
        $endsAt    = $_POST['endsAt'] ?? '';

        // Validate
        if ($coveredId < 1 || $backupId < 1 || $coveredId === $backupId || empty($endsAt)) {
            echo json_encode(['success' => false, 'message' => 'Invalid input']); exit;
        }
        // Validate both users belong to account
        $uc = Config::get('db')->get_results("SELECT id FROM productiveUser WHERE id IN ({$coveredId},{$backupId}) AND accountId={$accountId}");
        if (count($uc) < 2) { echo json_encode(['success' => false, 'message' => 'Not authorized']); exit; }

        // End any existing active coverage for this user
        $prev = Config::get('db')->get_results("SELECT * FROM qbl_coverage WHERE accountId={$accountId} AND coveredUserId={$coveredId} AND active=1 LIMIT 1");
        if ($prev) restoreCoverage($prev[0]);

        // Get covered user's dept assignments
        $covDepts = Config::get('db')->get_results("SELECT deptId, escAlerts FROM productiveDeptXref WHERE userId={$coveredId}");
        $restoreData = ['depts' => []];
        foreach (($covDepts ?: []) as $dept) {
            $deptId = intval($dept['deptId']);
            // Turn off covered user's alerts for this dept
            Config::get('db')->update('productiveDeptXref',
                ['escAlerts' => 0], ['userId' => $coveredId, 'deptId' => $deptId]
            );
            // Check backup user's existing entry
            $bx = Config::get('db')->get_results("SELECT escAlerts FROM productiveDeptXref WHERE userId={$backupId} AND deptId={$deptId} LIMIT 1");
            if ($bx) {
                $backupOrigEsc  = intval($bx[0]['escAlerts']);
                $backupInserted = false;
                Config::get('db')->update('productiveDeptXref',
                    ['escAlerts' => 1], ['userId' => $backupId, 'deptId' => $deptId]
                );
            } else {
                $backupOrigEsc  = 0;
                $backupInserted = true;
                Config::get('db')->insert('productiveDeptXref',
                    ['userId' => $backupId, 'deptId' => $deptId, 'escAlerts' => 1, 'primaryUnit' => 0, 'textAlerts' => 0]
                );
            }
            $restoreData['depts'][] = [
                'deptId'         => $deptId,
                'coveredOrigEsc' => intval($dept['escAlerts']),
                'backupOrigEsc'  => $backupOrigEsc,
                'backupInserted' => $backupInserted,
            ];
        }
        // Store and override backup user's threshold
        $bUser = Config::get('db')->get_results("SELECT qblWarnMin FROM productiveUser WHERE id={$backupId} LIMIT 1");
        $restoreData['backupOrigThreshold'] = intval($bUser[0]['qblWarnMin'] ?? 0);
        Config::get('db')->update('productiveUser', ['qblWarnMin' => $threshold], ['id' => $backupId]);

        Config::get('db')->insert('qbl_coverage', [
            'accountId'     => $accountId,
            'coveredUserId' => $coveredId,
            'backupUserId'  => $backupId,
            'threshold'     => $threshold,
            'endsAt'        => $endsAt,
            'active'        => 1,
            'restore_data'  => json_encode($restoreData),
            'created'       => date('Y-m-d H:i:s'),
        ]);
        echo json_encode(['success' => true]);
        exit;
    }

    if ($_POST['action'] === 'endCoverage') {
        $coverageId = intval($_POST['coverageId'] ?? 0);
        $cov = Config::get('db')->get_results("SELECT * FROM qbl_coverage WHERE id={$coverageId} AND accountId={$accountId} AND active=1 LIMIT 1");
        if (!$cov) { echo json_encode(['success' => false, 'message' => 'Not found']); exit; }
        restoreCoverage($cov[0]);
        echo json_encode(['success' => true]);
        exit;
    }

    echo json_encode(['success' => false]);
    exit;
}

// ── Query users ───────────────────────────────────────────────────────────────
$users = Config::get('db')->get_results("
    SELECT u.id, u.first_name, u.last_name, u.email, u.role, u.active,
           u.qblWarnMin, u.mobile, u.txtPause,
           r.label as roleLabel,
           GROUP_CONCAT(DISTINCT CONCAT(d.dept,'::',x.deptId,'::',IFNULL(x.escAlerts,0))
                        ORDER BY d.dept SEPARATOR '||') as units_data
    FROM productiveUser u
    LEFT JOIN productiveUserRoles r ON r.id = u.role
    LEFT JOIN productiveDeptXref x ON x.userId = u.id
    LEFT JOIN ProductiveDept d ON d.id = x.deptId AND d.accountId = {$accountId}
    WHERE u.accountId = {$accountId} AND u.active > 0
      AND u.role >= 5
      AND (u.role BETWEEN 8 AND 10 OR EXISTS (SELECT 1 FROM productiveDeptXref WHERE userId = u.id))
    GROUP BY u.id
    ORDER BY u.last_name, u.first_name
");
$userCount = is_array($users) ? count($users) : 0;

// Active coverage for this account
$activeCoverage = Config::get('db')->get_results("
    SELECT c.*, CONCAT(bu.first_name,' ',bu.last_name) as backupName
    FROM qbl_coverage c
    JOIN productiveUser bu ON bu.id = c.backupUserId
    WHERE c.accountId = {$accountId} AND c.active = 1 AND c.endsAt > NOW()
");
$coverageMap = []; // coveredUserId => coverage row
if ($activeCoverage) foreach ($activeCoverage as $cov) {
    $coverageMap[intval($cov['coveredUserId'])] = $cov;
}

// Build users-for-picker JS array (id, name, mobile present)
$usersForPicker = [];
if ($users) foreach ($users as $u) {
    $usersForPicker[] = [
        'id'     => intval($u['id']),
        'name'   => trim($u['first_name'] . ' ' . $u['last_name']),
        'mobile' => strlen($u['mobile'] ?? '') === 10,
    ];
}

// Flash message from redirect
$flashMsg  = $_GET['msg'] ?? '';
$flashMsgs = [
    'saved'      => ['text' => 'Changes saved.',                                        'type' => 'success'],
    'dup_email'  => ['text' => 'A user with that email address already exists.',        'type' => 'error'],
    'dup_mobile' => ['text' => 'A user with that mobile number already exists.',        'type' => 'error'],
];

// Add user data
$qblDepts      = Config::get('db')->get_results("SELECT d.id, d.dept FROM ProductiveDept d JOIN productiveQBLconfig q ON q.deptId = d.id WHERE d.accountId = {$accountId} AND q.active = 1 ORDER BY d.dept ASC");
$addRoles      = Config::get('db')->get_results("SELECT id, role FROM productiveUserRoles WHERE id >= 5 AND id <= 10 ORDER BY id ASC");
$addAllUsers   = Config::get('db')->get_results("SELECT u.id, u.first_name, u.last_name, u.mobile, u.qblWarnMin, u.role FROM productiveUser u WHERE u.accountId = {$accountId} AND u.role >= 5 AND u.role <= 10 AND u.active > 0 ORDER BY u.last_name, u.first_name ASC");
$addExec  = array_filter($addAllUsers ?: [], fn($u) => $u['role'] >= 8);
$addOther = array_filter($addAllUsers ?: [], fn($u) => $u['role'] < 8);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Users — <?php echo $company; ?></title>
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
:root {
  --navy: #1a4a8a; --navy-dark: #163d73; --teal: #0e6b6b; --red: #c0392b;
  --bg: #f0f2f5; --white: #ffffff; --text: #1a2332; --muted: #6b7f96; --border: #dde1e8;
  --font: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}
html { scroll-behavior: smooth; }
body { font-family: var(--font); background: var(--bg); color: var(--text); line-height: 1.6; -webkit-font-smoothing: antialiased; }

nav { position: sticky; top: 0; z-index: 100; background: var(--navy); padding: 14px 20px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 2px 8px rgba(0,0,0,.2); }
.nav-brand { color: #fff; font-size: 1.15rem; font-weight: 800; letter-spacing: .3px; text-decoration: none; }
.nav-brand span { font-weight: 400; opacity: .75; }
.nav-links { display: flex; align-items: center; gap: 6px; }
.nav-links a { color: rgba(255,255,255,.75); text-decoration: none; font-size: .82rem; font-weight: 600; padding: 6px 12px; border-radius: 20px; transition: background .15s; }
.nav-links a:hover { color: #fff; background: rgba(255,255,255,.1); }
.nav-links a.active { background: rgba(255,255,255,.2); color: #fff; }
.nav-links a.nav-logout { color: rgba(255,255,255,.55); font-weight: 500; }

.page-header { background: var(--white); border-bottom: 1px solid var(--border); padding: 22px 24px 18px; }
.page-header h1 { font-size: 1.25rem; font-weight: 800; }
.page-header p { font-size: .82rem; color: var(--muted); margin-top: 3px; }

.main { padding: 24px 16px; max-width: 1060px; margin: 0 auto; }
.count-label { font-size: .82rem; color: var(--muted); margin-bottom: 14px; }
.count-label strong { color: var(--text); }

.table-card { background: var(--white); border-radius: 12px; border: 1px solid var(--border); overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,.05); }
.table-scroll { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; font-size: .84rem; }
thead th { background: #f7f8fa; color: var(--muted); font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; padding: 11px 14px; text-align: left; border-bottom: 1px solid var(--border); white-space: nowrap; }
tbody tr.data-row { border-bottom: 1px solid var(--border); }
tbody tr.data-row:hover { background: #fafbfc; }
tbody td { padding: 11px 14px; vertical-align: middle; }
.td-muted { color: var(--muted); font-size: .8rem; }

/* EXPAND ROW */
.expand-row { display: none; border-bottom: 1px solid var(--border); background: #f7f9fc; }
.expand-row.open { display: table-row; }
.expand-cell { padding: 16px 18px; }

/* EDIT FORM */
.edit-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 14px; }
.edit-field label { display: block; font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: var(--muted); margin-bottom: 5px; }
.edit-field input[type="tel"], .edit-field input[type="number"] { width: 100%; padding: 9px 12px; border: 1px solid var(--border); border-radius: 8px; font-size: .9rem; outline: none; transition: border-color .15s; }
.edit-field input:focus { border-color: var(--navy); }
.edit-field .hint { font-size: .72rem; color: var(--muted); margin-top: 4px; }

/* SMS TOGGLES */
.sms-section { margin-bottom: 14px; }
.sms-section-label { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: var(--muted); margin-bottom: 8px; }
.sms-units { display: flex; flex-direction: column; gap: 8px; }
.sms-unit-row { display: flex; align-items: center; justify-content: space-between; background: var(--white); border: 1px solid var(--border); border-radius: 8px; padding: 10px 14px; }
.sms-unit-name { font-size: .88rem; font-weight: 600; }
.sms-no-units { font-size: .84rem; color: var(--muted); font-style: italic; }

/* TOGGLE */
.toggle-wrap { display: flex; align-items: center; gap: 8px; }
.toggle-wrap .toggle-label { font-size: .78rem; color: var(--muted); min-width: 26px; }
.toggle { position: relative; display: inline-block; width: 40px; height: 22px; }
.toggle input { opacity: 0; width: 0; height: 0; }
.slider { position: absolute; cursor: pointer; inset: 0; background: var(--border); border-radius: 22px; transition: .2s; }
.slider::before { content: ''; position: absolute; height: 16px; width: 16px; left: 3px; bottom: 3px; background: #fff; border-radius: 50%; transition: .2s; box-shadow: 0 1px 3px rgba(0,0,0,.2); }
input:checked + .slider { background: var(--teal); }
input:checked + .slider::before { transform: translateX(18px); }

/* COVERAGE CARD */
.coverage-card { background: #fff8e6; border: 1px solid #f0c969; border-radius: 10px; padding: 12px 14px; margin-bottom: 14px; }
.coverage-card-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 4px; }
.coverage-card-title { font-size: .82rem; font-weight: 700; color: #7a5200; }
.coverage-card-body  { font-size: .8rem; color: #7a5200; line-height: 1.5; }
.btn-end-coverage { background: var(--red); color: #fff; border: none; padding: 5px 12px; border-radius: 7px; font-size: .76rem; font-weight: 700; cursor: pointer; white-space: nowrap; }
.btn-end-coverage:hover { opacity: .85; }

/* PAUSED NOTICE */
.paused-notice { display: flex; align-items: center; gap: 8px; background: #fef9e7; border: 1px solid #f0d97a; border-radius: 8px; padding: 10px 14px; margin-bottom: 14px; font-size: .82rem; color: #8a6200; }

/* BUTTONS */
.btn-row { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
.btn-edit     { background: #e8f0fb; color: var(--navy); border: none; padding: 6px 14px; border-radius: 8px; font-size: .78rem; font-weight: 700; cursor: pointer; }
.btn-edit:hover { background: #d0e2f7; }
.btn-coverage { background: #fff3cd; color: #7a5200; border: 1px solid #f0c969; padding: 6px 14px; border-radius: 8px; font-size: .78rem; font-weight: 700; cursor: pointer; }
.btn-coverage:hover { background: #ffe8a0; }
.btn-coverage.active-cov { background: #ffc107; color: #5a3800; border-color: #e0a800; }
.btn-save   { background: var(--navy); color: #fff; border: none; padding: 8px 20px; border-radius: 8px; font-size: .85rem; font-weight: 700; cursor: pointer; }
.btn-save:hover { background: var(--navy-dark); }
.btn-cancel { background: none; color: var(--muted); border: none; font-size: .82rem; cursor: pointer; padding: 8px 10px; }
.btn-cancel:hover { color: var(--text); }

/* BADGES */
.badge { display: inline-block; font-size: .72rem; font-weight: 700; padding: 3px 9px; border-radius: 20px; }
.badge-teal   { background: #e6f4f4; color: var(--teal); border: 1px solid #b2d8d8; }
.badge-gray   { background: #f0f2f5; color: var(--muted); border: 1px solid var(--border); }
.badge-yellow { background: #fff3cd; color: #7a5200; border: 1px solid #f0c969; }

/* ── COVERAGE MODAL ── */
.cov-overlay { display: none; position: fixed; inset: 0; z-index: 300; background: rgba(10,20,40,.5); align-items: flex-end; justify-content: center; }
.cov-overlay.open { display: flex; }
.cov-sheet { background: var(--white); width: 100%; max-width: 480px; border-radius: 20px 20px 0 0; max-height: 90vh; overflow-y: auto; animation: slideUp .22s ease; }
@keyframes slideUp { from { transform: translateY(50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
.cov-handle { width: 40px; height: 4px; background: var(--border); border-radius: 2px; margin: 12px auto 0; }
.cov-header { padding: 16px 20px 12px; border-bottom: 1px solid var(--border); }
.cov-header h3 { font-size: 1rem; font-weight: 800; color: var(--text); }
.cov-header p  { font-size: .8rem; color: var(--muted); margin-top: 2px; }
.cov-body { padding: 18px 20px; display: flex; flex-direction: column; gap: 16px; }
.cov-field label { display: block; font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: var(--muted); margin-bottom: 6px; }
.cov-field select, .cov-field input {
  width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 8px;
  font-size: .9rem; color: var(--text); outline: none; transition: border-color .15s;
  background: var(--white);
}
.cov-field select:focus, .cov-field input:focus { border-color: var(--navy); }
.cov-field .hint { font-size: .72rem; color: var(--muted); margin-top: 4px; }
.cov-footer { padding: 14px 20px 24px; display: flex; gap: 10px; border-top: 1px solid var(--border); }
.cov-footer .btn-save { flex: 1; margin: 0; padding: 12px; }
.btn-cov-cancel { background: var(--bg); color: var(--muted); border: 1px solid var(--border); padding: 12px 18px; border-radius: 8px; font-size: .85rem; font-weight: 600; cursor: pointer; }

/* FLASH */
.flash { padding: 12px 18px; border-radius: 8px; font-size: .85rem; font-weight: 600; margin-bottom: 16px; }
.flash-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
.flash-error   { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }

/* ADD USER MODAL */
.add-modal-bg { display: none; position: fixed; inset: 0; background: rgba(10,20,40,.5); z-index: 200; align-items: flex-start; justify-content: center; padding-top: 3rem; overflow-y: auto; }
.add-modal-bg.open { display: flex; }
.add-modal-box { background: var(--white); border-radius: 14px; width: 100%; max-width: 480px; padding: 1.75rem; box-shadow: 0 20px 60px rgba(0,0,0,.15); margin-bottom: 2rem; }
.add-modal-title { font-size: 1rem; font-weight: 800; color: var(--text); margin-bottom: 1rem; }
.mode-toggle { display: flex; border: 1.5px solid var(--border); border-radius: 7px; overflow: hidden; margin-bottom: 1.25rem; }
.mode-btn { flex: 1; padding: .5rem; font-size: .82rem; font-weight: 600; background: none; border: none; cursor: pointer; color: var(--muted); transition: background .15s; }
.mode-btn.active { background: var(--navy); color: #fff; }
.mode-section { display: none; } .mode-section.active { display: block; }
.add-field { margin-bottom: .85rem; }
.add-field label { display: block; font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: var(--muted); margin-bottom: 5px; }
.add-field input, .add-field select { width: 100%; padding: 9px 12px; border: 1px solid var(--border); border-radius: 8px; font-size: .9rem; color: var(--text); outline: none; background: var(--white); transition: border-color .15s; }
.add-field input:focus, .add-field select:focus { border-color: var(--navy); }
.add-field .hint { font-size: .72rem; color: var(--muted); margin-top: 4px; }
.add-field-row { display: grid; grid-template-columns: 1fr 1fr; gap: .75rem; }
.add-footer { display: flex; justify-content: flex-end; gap: .6rem; margin-top: 1.25rem; }
.btn-add-user { background: var(--navy); color: #fff; border: none; padding: 7px 16px; border-radius: 8px; font-size: .8rem; font-weight: 700; cursor: pointer; }
.btn-add-user:hover { background: var(--navy-dark); }
.btn-add-cancel { background: none; color: var(--muted); border: 1px solid var(--border); padding: 7px 14px; border-radius: 8px; font-size: .8rem; font-weight: 600; cursor: pointer; }

/* TOAST */
#toast { position: fixed; bottom: 24px; right: 24px; background: var(--teal); color: #fff; padding: 12px 20px; border-radius: 10px; font-size: .85rem; font-weight: 600; box-shadow: 0 4px 16px rgba(0,0,0,.2); z-index: 9999; display: none; }
#toast.error { background: var(--red); }

footer { background: #0d2040; color: rgba(255,255,255,.5); text-align: center; padding: 24px 20px; font-size: .75rem; line-height: 1.8; margin-top: 48px; }
footer strong { color: rgba(255,255,255,.8); }
footer a { color: rgba(255,255,255,.5); text-decoration: none; }
footer a:hover { color: #fff; }

@media (max-width: 600px) {
  .nav-links a { padding: 5px 8px; font-size: .76rem; }
  .main { padding: 16px 10px; }
  .edit-grid { grid-template-columns: 1fr; gap: 10px; }
}
@media (min-width: 600px) {
  .cov-overlay { align-items: center; }
  .cov-sheet { border-radius: 16px; max-height: 85vh; }
}
</style>
</head>
<body>

<nav>
  <a class="nav-brand" href="https://qbl.productivern.com">Productive<span>RN</span> &mdash; QBL Alerts</a>
  <div class="nav-links">
    <a href="/qbl-portal.php">Reports</a>
    <a href="/qbl-settings.php">Settings</a>
    <a href="/qbl-users.php" class="active">Users</a>
    <a href="/logout.php" class="nav-logout">Logout</a>
  </div>
</nav>

<div class="page-header">
  <h1>Users &mdash; <?php echo $company; ?></h1>
  <p>Manage mobile numbers, QBL SMS alerts, thresholds, and backup coverage</p>
</div>

<div class="main">
  <?php if ($flashMsg && isset($flashMsgs[$flashMsg])): ?>
  <div class="flash flash-<?php echo $flashMsgs[$flashMsg]['type']; ?>"><?php echo $flashMsgs[$flashMsg]['text']; ?></div>
  <?php endif; ?>
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px">
    <p class="count-label" style="margin-bottom:0"><strong><?php echo $userCount; ?></strong> user<?php echo $userCount !== 1 ? 's' : ''; ?></p>
    <button class="btn-add-user" onclick="document.getElementById('addUserModal').classList.add('open')">+ Add User</button>
  </div>
  <div class="table-card">
    <div class="table-scroll">
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Role</th>
            <th>Mobile</th>
            <th>QBL SMS</th>
            <th>Threshold</th>
            <th>Coverage</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($users)): ?>
          <tr><td colspan="7" style="padding:40px;text-align:center;color:var(--muted);">No users found.</td></tr>
          <?php else: ?>
          <?php foreach ($users as $user):
            $uid       = intval($user['id']);
            $mobile    = $user['mobile'] ?? '';
            $threshold = intval($user['qblWarnMin'] ?? 0);
            $paused    = intval($user['txtPause'] ?? 0);
            $cov       = $coverageMap[$uid] ?? null;

            $unitParts = [];
            if (!empty($user['units_data'])) {
                foreach (explode('||', $user['units_data']) as $chunk) {
                    $parts = explode('::', $chunk);
                    if (count($parts) === 3) {
                        $unitParts[] = ['name' => $parts[0], 'deptId' => intval($parts[1]), 'escAlerts' => intval($parts[2])];
                    }
                }
            }

            $smsOnCount  = count(array_filter($unitParts, fn($u) => $u['escAlerts'] == 1));
            $smsTotalCount = count($unitParts);
            if ($smsTotalCount === 0)  $smsBadge = '<span class="badge badge-gray">No units</span>';
            elseif ($paused)           $smsBadge = '<span class="badge badge-yellow">Paused</span>';
            elseif ($smsOnCount === 0) $smsBadge = '<span class="badge badge-gray">Off</span>';
            elseif ($smsOnCount === $smsTotalCount) $smsBadge = '<span class="badge badge-teal">On</span>';
            else   $smsBadge = '<span class="badge badge-teal">' . $smsOnCount . '/' . $smsTotalCount . ' on</span>';

            $mobileFmt = (strlen($mobile) === 10)
                ? '(' . substr($mobile,0,3) . ') ' . substr($mobile,3,3) . '-' . substr($mobile,6)
                : '<span style="color:var(--muted)">Not set</span>';

            $threshFmt = $threshold > 0 ? number_format($threshold) . ' ml' : '<span style="color:var(--muted)">Not set</span>';

            if ($cov) {
                $covBadge = '<span class="badge badge-yellow">&#9201; Until ' . date('M j g:ia', strtotime($cov['endsAt'])) . '</span>';
            } else {
                $covBadge = '<span style="color:var(--muted);font-size:.8rem;">—</span>';
            }
          ?>
          <tr class="data-row" id="row-<?php echo $uid; ?>">
            <td style="font-weight:600"><?php echo htmlspecialchars(trim($user['first_name'] . ' ' . $user['last_name'])); ?></td>
            <td class="td-muted"><?php echo htmlspecialchars($user['roleLabel'] ?? '—'); ?></td>
            <td class="td-muted" id="mobile-display-<?php echo $uid; ?>"><?php echo $mobileFmt; ?></td>
            <td id="sms-display-<?php echo $uid; ?>"><?php echo $smsBadge; ?></td>
            <td id="threshold-display-<?php echo $uid; ?>"><?php echo $threshFmt; ?></td>
            <td id="cov-display-<?php echo $uid; ?>"><?php echo $covBadge; ?></td>
            <td>
              <div style="display:flex;gap:6px;flex-wrap:wrap;">
                <button class="btn-edit" onclick="toggleEdit(<?php echo $uid; ?>)">Edit</button>
                <button class="btn-coverage<?php echo $cov ? ' active-cov' : ''; ?>" id="cov-btn-<?php echo $uid; ?>"
                        onclick="openCoverage(<?php echo $uid; ?>, '<?php echo addslashes(trim($user['first_name'] . ' ' . $user['last_name'])); ?>', <?php echo $threshold; ?>, <?php echo $cov ? "'" . addslashes($cov['id']) . "'" : 'null'; ?>)">
                  <?php echo $cov ? '&#9201; Coverage' : 'Coverage'; ?>
                </button>
              </div>
            </td>
          </tr>

          <!-- EXPAND ROW -->
          <tr class="expand-row" id="expand-<?php echo $uid; ?>">
            <td class="expand-cell" colspan="7">
              <?php if ($paused): ?>
              <div class="paused-notice">&#9888; This user's SMS is paused globally. QBL alerts will not fire until unpaused in the main app.</div>
              <?php endif; ?>
              <?php if ($cov): ?>
              <div class="coverage-card">
                <div class="coverage-card-header">
                  <span class="coverage-card-title">&#9201; Coverage active — backup: <?php echo htmlspecialchars($cov['backupName']); ?></span>
                  <button class="btn-end-coverage" onclick="endCoverage(<?php echo $cov['id']; ?>, <?php echo $uid; ?>)">End Now</button>
                </div>
                <div class="coverage-card-body">
                  Threshold: <?php echo number_format(intval($cov['threshold'])); ?> ml &nbsp;&middot;&nbsp;
                  Ends: <?php echo date('M j, Y g:i a', strtotime($cov['endsAt'])); ?>
                </div>
              </div>
              <?php endif; ?>

              <div class="edit-grid">
                <div class="edit-field">
                  <label>Mobile Number</label>
                  <input type="tel" id="mobile-<?php echo $uid; ?>" value="<?php echo htmlspecialchars($mobile); ?>" placeholder="10-digit number" maxlength="10" inputmode="numeric">
                  <div class="hint">10 digits, no dashes (e.g. 3145550199)</div>
                </div>
                <div class="edit-field">
                  <label>QBL Alert Threshold (ml)</label>
                  <input type="number" id="threshold-<?php echo $uid; ?>" value="<?php echo $threshold ?: ''; ?>" placeholder="e.g. 500" min="0" step="50">
                  <div class="hint">Alert fires when QBL reaches this value</div>
                </div>
              </div>

              <?php if (!empty($unitParts)): ?>
              <div class="sms-section">
                <div class="sms-section-label">QBL SMS Alerts by Unit</div>
                <div class="sms-units">
                  <?php foreach ($unitParts as $up): ?>
                  <div class="sms-unit-row">
                    <span class="sms-unit-name"><?php echo htmlspecialchars($up['name']); ?></span>
                    <div class="toggle-wrap">
                      <span class="toggle-label" id="esc-label-<?php echo $uid; ?>-<?php echo $up['deptId']; ?>"><?php echo $up['escAlerts'] ? 'On' : 'Off'; ?></span>
                      <label class="toggle">
                        <input type="checkbox" id="esc-<?php echo $uid; ?>-<?php echo $up['deptId']; ?>"
                               data-dept="<?php echo $up['deptId']; ?>"
                               <?php echo $up['escAlerts'] ? 'checked' : ''; ?>
                               onchange="updateToggleLabel(this, <?php echo $uid; ?>)">
                        <span class="slider"></span>
                      </label>
                    </div>
                  </div>
                  <?php endforeach; ?>
                </div>
              </div>
              <?php else: ?>
              <p class="sms-no-units">Not assigned to any units. Assign units in the main app to enable QBL SMS.</p>
              <?php endif; ?>

              <div class="btn-row">
                <button class="btn-save" onclick="saveUser(<?php echo $uid; ?>)">Save</button>
                <button class="btn-cancel" onclick="toggleEdit(<?php echo $uid; ?>)">Cancel</button>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- ADD USER MODAL -->
<div class="add-modal-bg" id="addUserModal">
  <div class="add-modal-box">
    <div class="add-modal-title">Add User</div>
    <div class="mode-toggle">
      <button type="button" class="mode-btn active" onclick="switchAddMode('select',this)">Select Existing</button>
      <button type="button" class="mode-btn" onclick="switchAddMode('create',this)">Create New</button>
    </div>

    <!-- SELECT EXISTING -->
    <div class="mode-section active" id="add-mode-select">
      <form method="post">
        <input type="hidden" name="action" value="add_existing_user">
        <div class="add-field">
          <label>Unit</label>
          <select name="deptId">
            <option value="">— Select a unit —</option>
            <?php foreach (($qblDepts ?: []) as $d): ?>
            <option value="<?php echo intval($d['id']); ?>"><?php echo htmlspecialchars($d['dept']); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="add-field">
          <label>User</label>
          <select name="userId" id="addExistingUser" onchange="prefillAddExisting(this)">
            <option value="">— Select a user —</option>
            <?php if ($addExec): ?>
            <optgroup label="Executive">
              <?php foreach ($addExec as $u): ?>
              <option value="<?php echo intval($u['id']); ?>"
                      data-mobile="<?php echo htmlspecialchars($u['mobile'] ?? ''); ?>"
                      data-threshold="<?php echo intval($u['qblWarnMin']); ?>">
                <?php echo htmlspecialchars($u['last_name'] . ', ' . $u['first_name']); ?>
              </option>
              <?php endforeach; ?>
            </optgroup>
            <?php endif; ?>
            <?php if ($addOther): ?>
            <optgroup label="Staff">
              <?php foreach ($addOther as $u): ?>
              <option value="<?php echo intval($u['id']); ?>"
                      data-mobile="<?php echo htmlspecialchars($u['mobile'] ?? ''); ?>"
                      data-threshold="<?php echo intval($u['qblWarnMin']); ?>">
                <?php echo htmlspecialchars($u['last_name'] . ', ' . $u['first_name']); ?>
              </option>
              <?php endforeach; ?>
            </optgroup>
            <?php endif; ?>
          </select>
        </div>
        <div class="add-field">
          <label>Mobile Number</label>
          <input type="text" name="mobile" id="addExMobile" placeholder="10-digit number">
        </div>
        <div class="add-field-row">
          <div class="add-field">
            <label>Alert Threshold (ml)</label>
            <input type="number" name="threshold" id="addExThreshold" placeholder="e.g. 500" min="0" step="50">
          </div>
          <div class="add-field">
            <label>Receive QBL SMS</label>
            <select name="sms" id="addExSms" disabled>
              <option value="0">No</option>
              <option value="1">Yes</option>
            </select>
          </div>
        </div>
        <div class="add-footer">
          <button type="button" class="btn-add-cancel" onclick="document.getElementById('addUserModal').classList.remove('open')">Cancel</button>
          <button type="submit" class="btn-add-user">Associate to Unit</button>
        </div>
      </form>
    </div>

    <!-- CREATE NEW -->
    <div class="mode-section" id="add-mode-create">
      <form method="post">
        <input type="hidden" name="action" value="create_user">
        <div class="add-field">
          <label>Unit</label>
          <select name="deptId">
            <option value="">— Select a unit (optional) —</option>
            <?php foreach (($qblDepts ?: []) as $d): ?>
            <option value="<?php echo intval($d['id']); ?>"><?php echo htmlspecialchars($d['dept']); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="add-field-row">
          <div class="add-field">
            <label>First Name</label>
            <input type="text" name="first_name">
          </div>
          <div class="add-field">
            <label>Last Name</label>
            <input type="text" name="last_name">
          </div>
        </div>
        <div class="add-field">
          <label>Email (username)</label>
          <input type="email" name="email">
          <div class="hint">A setup link will be emailed to this address</div>
        </div>
        <div class="add-field">
          <label>Role</label>
          <select name="role">
            <?php foreach (($addRoles ?: []) as $r): ?>
            <option value="<?php echo intval($r['id']); ?>"><?php echo htmlspecialchars($r['role']); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="add-field">
          <label>Mobile Number</label>
          <input type="text" name="mobile" id="addNewMobile" placeholder="10-digit number">
        </div>
        <div class="add-field-row">
          <div class="add-field">
            <label>Alert Threshold (ml)</label>
            <input type="number" name="threshold" placeholder="e.g. 500" min="0" step="50">
          </div>
          <div class="add-field">
            <label>Receive QBL SMS</label>
            <select name="sms" id="addNewSms" disabled>
              <option value="0">No</option>
              <option value="1">Yes</option>
            </select>
          </div>
        </div>
        <div class="add-footer">
          <button type="button" class="btn-add-cancel" onclick="document.getElementById('addUserModal').classList.remove('open')">Cancel</button>
          <button type="submit" class="btn-add-user">Create &amp; Associate</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- COVERAGE MODAL -->
<div class="cov-overlay" id="covOverlay">
  <div class="cov-sheet">
    <div class="cov-handle"></div>
    <div class="cov-header">
      <h3 id="cov-modal-title">Set Coverage</h3>
      <p id="cov-modal-sub">Designate a backup to receive QBL SMS alerts while you're away</p>
    </div>
    <div class="cov-body">
      <input type="hidden" id="cov-covered-id">
      <input type="hidden" id="cov-active-id">
      <div class="cov-field">
        <label>Backup Recipient</label>
        <select id="cov-backup-user">
          <option value="">— Select a user —</option>
        </select>
        <div class="hint">This person will receive QBL SMS alerts in their place</div>
      </div>
      <div class="cov-field">
        <label>Alert Threshold (ml)</label>
        <input type="number" id="cov-threshold" placeholder="e.g. 500" min="0" step="50">
        <div class="hint">Backup will be alerted when QBL reaches this value</div>
      </div>
      <div class="cov-field">
        <label>Coverage Ends</label>
        <input type="datetime-local" id="cov-ends-at">
        <div class="hint">Coverage restores automatically at this date &amp; time</div>
      </div>
    </div>
    <div class="cov-footer">
      <button class="btn-cov-cancel" onclick="closeCoverage()">Cancel</button>
      <button class="btn-save" onclick="saveCoverage()">Set Coverage</button>
    </div>
  </div>
</div>

<div id="toast"></div>

<footer>
  <strong>ProductivRN</strong> &mdash; QBL Alerts<br>
  &copy; <?php echo date('Y'); ?> ProductivRN. All rights reserved.<br>
  <span style="font-size:.68rem;opacity:.6">QBL Alerts is a notification tool and does not replace clinical judgment. Always follow your institution's hemorrhage protocols.</span>
</footer>

<script>
const ALL_USERS = <?php echo json_encode($usersForPicker); ?>;

function showToast(msg, isError) {
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.className = isError ? 'error' : '';
  t.style.display = 'block';
  clearTimeout(t._tid);
  t._tid = setTimeout(() => { t.style.display = 'none'; }, 3200);
}

// ── USER EDIT ROW ──────────────────────────────────────────────────
function toggleEdit(uid) {
  document.getElementById('expand-' + uid).classList.toggle('open');
}
function updateToggleLabel(cb, uid) {
  const label = document.getElementById('esc-label-' + uid + '-' + cb.dataset.dept);
  if (label) label.textContent = cb.checked ? 'On' : 'Off';
}
function saveUser(uid) {
  const mobile    = document.getElementById('mobile-' + uid).value.replace(/\D/g,'');
  const threshold = parseInt(document.getElementById('threshold-' + uid).value) || 0;
  if (mobile.length > 0 && mobile.length !== 10) { showToast('Mobile must be exactly 10 digits', true); return; }
  const payload = new URLSearchParams({ action: 'updateUser', userId: uid, mobile, threshold });
  document.querySelectorAll('#expand-' + uid + ' input[type="checkbox"]').forEach(cb => {
    payload.append('esc_' + cb.dataset.dept, cb.checked ? '1' : '0');
  });
  const btn = document.querySelector('#expand-' + uid + ' .btn-save');
  btn.disabled = true; btn.textContent = 'Saving…';
  fetch(window.location.pathname, { method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body: payload.toString() })
  .then(r => r.json())
  .then(data => {
    if (!data.success) { showToast('Save failed', true); return; }
    const md = document.getElementById('mobile-display-' + uid);
    md.innerHTML = mobile.length===10 ? '('+mobile.slice(0,3)+') '+mobile.slice(3,6)+'-'+mobile.slice(6) : '<span style="color:var(--muted)">Not set</span>';
    const td = document.getElementById('threshold-display-' + uid);
    td.innerHTML = threshold > 0 ? threshold.toLocaleString()+' ml' : '<span style="color:var(--muted)">Not set</span>';
    const toggles = document.querySelectorAll('#expand-'+uid+' input[type="checkbox"]');
    const on = Array.from(toggles).filter(c=>c.checked).length;
    const total = toggles.length;
    const sd = document.getElementById('sms-display-' + uid);
    sd.innerHTML = total===0 ? '<span class="badge badge-gray">No units</span>'
                 : on===0   ? '<span class="badge badge-gray">Off</span>'
                 : on===total? '<span class="badge badge-teal">On</span>'
                 : '<span class="badge badge-teal">'+on+'/'+total+' on</span>';
    document.getElementById('expand-'+uid).classList.remove('open');
    showToast('Saved', false);
  })
  .catch(() => showToast('Network error', true))
  .finally(() => { btn.disabled=false; btn.textContent='Save'; });
}

// ── COVERAGE MODAL ─────────────────────────────────────────────────
let _covUserId = null;

function openCoverage(uid, name, currentThreshold, activeId) {
  _covUserId = uid;
  document.getElementById('cov-covered-id').value = uid;
  document.getElementById('cov-active-id').value  = activeId || '';
  document.getElementById('cov-modal-title').textContent = 'Set Coverage — ' + name;
  document.getElementById('cov-threshold').value  = currentThreshold || '';

  // Default end time: 7 days from now
  const d = new Date(); d.setDate(d.getDate() + 7);
  document.getElementById('cov-ends-at').value = toLocalDateTimeInput(d);

  // Populate backup dropdown (exclude the covered user)
  const sel = document.getElementById('cov-backup-user');
  sel.innerHTML = '<option value="">— Select a user —</option>';
  ALL_USERS.forEach(u => {
    if (u.id === uid) return;
    const opt = document.createElement('option');
    opt.value = u.id;
    opt.textContent = u.name + (u.mobile ? '' : ' (no mobile)');
    sel.appendChild(opt);
  });

  document.getElementById('covOverlay').classList.add('open');
  document.body.style.overflow = 'hidden';
}

function closeCoverage() {
  document.getElementById('covOverlay').classList.remove('open');
  document.body.style.overflow = '';
}
document.getElementById('covOverlay').addEventListener('click', function(e) {
  if (e.target === this) closeCoverage();
});

function saveCoverage() {
  const coveredId = document.getElementById('cov-covered-id').value;
  const backupId  = document.getElementById('cov-backup-user').value;
  const threshold = parseInt(document.getElementById('cov-threshold').value) || 0;
  const endsAt    = document.getElementById('cov-ends-at').value;

  if (!backupId)   { showToast('Please select a backup recipient', true); return; }
  if (!endsAt)     { showToast('Please set a coverage end date/time', true); return; }
  if (threshold < 1) { showToast('Please enter an alert threshold', true); return; }

  // Convert local datetime to MySQL format
  const endsAtMysql = endsAt.replace('T', ' ') + ':00';

  const btn = document.querySelector('#covOverlay .btn-save');
  btn.disabled = true; btn.textContent = 'Saving…';

  fetch(window.location.pathname, {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: new URLSearchParams({ action:'setCoverage', coveredUserId:coveredId, backupUserId:backupId, threshold, endsAt:endsAtMysql }).toString()
  })
  .then(r => r.json())
  .then(data => {
    if (!data.success) { showToast(data.message || 'Failed to set coverage', true); return; }
    const backupName = document.getElementById('cov-backup-user').selectedOptions[0].textContent;
    const endsDisp   = new Date(endsAt).toLocaleDateString('en-US',{month:'short',day:'numeric',hour:'numeric',minute:'2-digit'});
    // Update display
    document.getElementById('cov-display-' + coveredId).innerHTML =
      '<span class="badge badge-yellow">&#9201; Until ' + endsDisp + '</span>';
    const covBtn = document.getElementById('cov-btn-' + coveredId);
    if (covBtn) { covBtn.textContent = '⏱ Coverage'; covBtn.classList.add('active-cov'); }
    closeCoverage();
    showToast('Coverage set — backup: ' + backupName, false);
    // Reload to show coverage card in expand row
    setTimeout(() => location.reload(), 1200);
  })
  .catch(() => showToast('Network error', true))
  .finally(() => { btn.disabled=false; btn.textContent='Set Coverage'; });
}

function endCoverage(coverageId, uid) {
  if (!confirm('End coverage now and restore original alert settings?')) return;
  fetch(window.location.pathname, {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: new URLSearchParams({ action:'endCoverage', coverageId }).toString()
  })
  .then(r => r.json())
  .then(data => {
    if (!data.success) { showToast('Failed to end coverage', true); return; }
    showToast('Coverage ended — original settings restored', false);
    setTimeout(() => location.reload(), 1000);
  })
  .catch(() => showToast('Network error', true));
}

// ── ADD USER MODAL ──────────────────────────────────────────────────
function switchAddMode(mode, btn) {
  document.querySelectorAll('.mode-section').forEach(function(s) { s.classList.remove('active'); });
  document.querySelectorAll('.mode-btn').forEach(function(b) { b.classList.remove('active'); });
  document.getElementById('add-mode-' + mode).classList.add('active');
  btn.classList.add('active');
}
function syncAddSms(mobileId, smsId) {
  var mobile = document.getElementById(mobileId).value.replace(/\D/g,'');
  var sel = document.getElementById(smsId);
  if (mobile.length !== 10) { sel.value = '0'; sel.disabled = true; }
  else { sel.disabled = false; }
}
document.getElementById('addExMobile').addEventListener('input', function() { syncAddSms('addExMobile','addExSms'); });
document.getElementById('addNewMobile').addEventListener('input', function() { syncAddSms('addNewMobile','addNewSms'); });
function prefillAddExisting(sel) {
  var opt = sel.options[sel.selectedIndex];
  document.getElementById('addExMobile').value = opt.dataset.mobile || '';
  document.getElementById('addExThreshold').value = parseInt(opt.dataset.threshold) > 0 ? opt.dataset.threshold : '';
  syncAddSms('addExMobile','addExSms');
}
document.getElementById('addUserModal').addEventListener('click', function(e) {
  if (e.target === this) this.classList.remove('open');
});

function toLocalDateTimeInput(d) {
  const pad = n => String(n).padStart(2,'0');
  return d.getFullYear()+'-'+pad(d.getMonth()+1)+'-'+pad(d.getDate())+'T'+pad(d.getHours())+':'+pad(d.getMinutes());
}
</script>
</body>
</html>
