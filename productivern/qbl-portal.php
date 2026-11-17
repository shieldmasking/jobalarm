<?php
require_once 'inc/initializer.php';
require_once 'inc/class.db.php';
require_once 'inc/config.php';
if (empty($_SESSION['account']['accountId'])) {
    header('location: /login.php?r=qbl');
    exit;
}
$accountId = intval($_SESSION['account']['accountId']);
$userRole  = intval($_SESSION['account']['role'] ?? 0);
$userId    = intval($_SESSION['account']['id'] ?? 0);
$company   = htmlspecialchars($_SESSION['account']['company'] ?? '');

// AJAX: fetch log detail for modal — keep before id routing
if (isset($_GET['logId'])) {
    header('Content-Type: application/json');
    $logId = intval($_GET['logId']);
    if ($logId < 1) { echo json_encode(['success' => false]); exit; }
    $detail = Config::get('db')->get_results("
        SELECT l.id, l.dateTime, l.totalQBL, l.totalWt, l.note, l.managerClosed,
               l.nurse_name, l.cum_qbl, l.session_id,
               (SELECT MAX(l2.cum_qbl) FROM productiveQBLlog l2 WHERE l2.session_id = l.session_id AND l2.session_id != '' AND l2.session_id IS NOT NULL) as session_cum_total,
               (SELECT SUM(l2.totalWt) FROM productiveQBLlog l2 WHERE l2.session_id = l.session_id AND l2.session_id != '' AND l2.session_id IS NOT NULL) as session_total_wt,
               l.v1,l.v2,l.v3,l.v4,l.v5,l.v6,l.v7,l.v8,l.v9,l.v10,
               l.v11,l.v12,l.v13,l.v14,l.v15,l.v16,l.v17,l.v18,l.v19,l.v20,
               c.desc1,c.desc2,c.desc3,c.desc4,c.desc5,c.desc6,c.desc7,c.desc8,c.desc9,c.desc10,
               c.desc11,c.desc12,c.desc13,c.desc14,c.desc15,c.desc16,c.desc17,c.desc18,c.desc19,c.desc20,
               c.value1,c.value2,c.value3,c.value4,c.value5,c.value6,c.value7,c.value8,c.value9,c.value10,
               c.value11,c.value12,c.value13,c.value14,c.value15,c.value16,c.value17,c.value18,c.value19,c.value20,
               d.dept as deptName,
               CONCAT(u.first_name, ' ', u.last_name) as submittedBy
        FROM productiveQBLlog l
        JOIN productiveQBLconfig c ON c.id = l.locId
        JOIN ProductiveDept d ON d.id = l.deptId
        LEFT JOIN productiveUser u ON u.id = l.userId
        WHERE l.id = {$logId} AND l.accountId = {$accountId}
        LIMIT 1
    ");
    echo json_encode($detail ? array_merge(['success' => true], $detail[0]) : ['success' => false]);
    exit;
}

// Require ?id=
$id = intval($_GET['id'] ?? 0);
if (!$id) { header('location: /qbl-settings.php'); exit; }

// Load config
$cfg = Config::get('db')->get_results(
    "SELECT q.*, d.dept, d.id as deptId FROM productiveQBLconfig q
     JOIN ProductiveDept d ON d.id = q.deptId
     WHERE q.id = {$id} AND q.accountId = {$accountId}
     LIMIT 1"
);
if (!$cfg) { header('location: /qbl-settings.php'); exit; }
$cfg    = $cfg[0];
$deptId = intval($cfg['deptId']);

// Access control for role 5-7
if ($userRole < 8) {
    $access = Config::get('db')->get_results("SELECT id FROM productiveDeptXref WHERE userId={$userId} AND deptId={$deptId} LIMIT 1");
    if (!$access) { header('location: /qbl-settings.php'); exit; }
}

$db  = Config::get('db');
$tab = $_GET['tab'] ?? 'reports';
$redir = '/qbl-portal.php?id=' . $id;

// Scan available images
$imgDir    = __DIR__ . '/qblimages/';
$availImgs = [];
if (is_dir($imgDir)) {
    foreach (scandir($imgDir) as $f) {
        if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $f)) $availImgs[] = $f;
    }
    sort($availImgs);
}

/* ── POST handlers ─────────────────────────────────────────────── */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'upload_image') {
        header('Content-Type: application/json');
        if (!empty($_FILES['img']['tmp_name']) && is_uploaded_file($_FILES['img']['tmp_name'])) {
            $ext  = strtolower(pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION));
            $mime = mime_content_type($_FILES['img']['tmp_name']);
            $allowedMimes = ['image/jpeg', 'image/png', 'image/gif'];
            if (in_array($ext, ['jpg','jpeg','png','gif']) && in_array($mime, $allowedMimes)) {
                $dest = $imgDir . preg_replace('/[^a-z0-9._-]/i', '_', basename($_FILES['img']['name']));
                move_uploaded_file($_FILES['img']['tmp_name'], $dest);
                echo json_encode(['success' => true, 'file' => basename($dest)]);
            } else {
                echo json_encode(['success' => false]);
            }
        } else {
            echo json_encode(['success' => false]);
        }
        exit;
    }

    if ($action === 'save_items') {
        $data = ['active' => intval($_POST['active'] ?? 1)];
        for ($i = 1; $i <= 20; $i++) {
            $data["desc{$i}"]  = $db->filter(trim($_POST["desc{$i}"] ?? ''));
            $data["value{$i}"] = floatval($_POST["value{$i}"] ?? 0);
            $data["image{$i}"] = $db->filter(basename($_POST["image{$i}"] ?? ''));
        }
        $db->update('productiveQBLconfig', $data, ['id' => $id, 'accountId' => $accountId], 1);
        header('location: ' . $redir . '&tab=supplies&msg=saved'); exit;

    } elseif ($action === 'update_sms') {
        $uid    = intval($_POST['userId'] ?? 0);
        $mobile = preg_replace('/\D/', '', $_POST['mobile'] ?? '');
        $sms    = (strlen($mobile) === 10) ? intval($_POST['sms'] ?? 0) : 0;
        if ($uid) {
            $threshold = intval($_POST['threshold'] ?? 0);
            $mobileE = openssl_encrypt($mobile, 'AES-128-CTR', ENCRYPT_KEY, 0, ENCRYPT_IV);
            $userUpdate = ['mobile' => $mobile, 'mobile_E' => $mobileE, 'qblWarnMin' => $threshold];
            if (strlen($mobile) === 10 && $sms === 1) {
                $inactiveCheck = $db->get_results("SELECT active FROM productiveUser WHERE id={$uid} AND accountId={$accountId} LIMIT 1");
                if ($inactiveCheck && !$inactiveCheck[0]['active']) $userUpdate['active'] = 1;
                $userUpdate['txtEscalation'] = 1;
            }
            $db->update('productiveUser', $userUpdate, ['id' => $uid, 'accountId' => $accountId], 1);
            $xref = $db->get_results("SELECT id FROM productiveDeptXref WHERE userId={$uid} AND deptId={$deptId} LIMIT 1");
            if ($xref) {
                $db->update('productiveDeptXref', ['escAlerts' => $sms], ['userId' => $uid, 'deptId' => $deptId]);
            } else {
                $db->insert('productiveDeptXref', ['userId' => $uid, 'deptId' => $deptId, 'primaryUnit' => 0, 'unitAssigned' => 0, 'escAlerts' => $sms]);
            }
        }
        header('location: ' . $redir . '&tab=users&msg=saved'); exit;

    } elseif ($action === 'create_user') {
        $first  = trim($_POST['first_name'] ?? '');
        $last   = trim($_POST['last_name']  ?? '');
        $email  = strtolower(trim($_POST['email'] ?? ''));
        $role   = intval($_POST['role'] ?? 5);
        $mobile = preg_replace('/\D/', '', $_POST['mobile'] ?? '');
        $sms    = (strlen($mobile) === 10) ? intval($_POST['sms'] ?? 0) : 0;
        $thresh = intval($_POST['threshold'] ?? 0);
        if ($role < 5) $role = 5;
        if ($first && $last && $email) {
            $dupEmail  = $db->get_results("SELECT id FROM productiveUser WHERE userName='" . $db->filter($email) . "' LIMIT 1");
            $dupMobile = $mobile ? $db->get_results("SELECT id FROM productiveUser WHERE mobile='" . $db->filter($mobile) . "' LIMIT 1") : false;
            if ($dupEmail) { header('location: ' . $redir . '&tab=users&msg=dup_email'); exit; }
            if ($dupMobile) { header('location: ' . $redir . '&tab=users&msg=dup_mobile'); exit; }
            $token       = bin2hex(random_bytes(32));
            $placeholder = password_hash(bin2hex(random_bytes(16)), PASSWORD_BCRYPT);
            $mobileE     = $mobile ? openssl_encrypt($mobile, 'AES-128-CTR', ENCRYPT_KEY, 0, ENCRYPT_IV) : '';
            $newId = $db->insert('productiveUser', [
                'first_name' => $db->filter($first),
                'last_name'  => $db->filter($last),
                'accountId'  => $accountId,
                'email'      => $db->filter($email),
                'email2'     => $db->filter($email),
                'userName'   => $db->filter($email),
                'pwd'        => $placeholder,
                'temp'       => $token,
                'role'       => $role,
                'active'     => 1,
                'mobile'     => $mobile,
                'mobile_E'   => $mobileE,
                'qblWarnMin' => $thresh,
                'txtEscalation' => $sms === 1 ? 1 : 0,
            ]);
            if ($newId) {
                $db->insert('productiveDeptXref', ['userId' => $newId, 'deptId' => $deptId, 'primaryUnit' => 0, 'unitAssigned' => 0, 'escAlerts' => $sms]);
                $setupLink = 'https://productivern.com/set-password.php?t=' . $token;
                $subject   = 'Verify your email — ProductivRN QBL Alerts';
                $headers   = "MIME-Version: 1.0\r\nContent-type:text/html;charset=UTF-8\r\nFrom: <noreply@productivern.com>\r\n";
                $message   = "
<html><body style='font-family:-apple-system,BlinkMacSystemFont,Segoe UI,sans-serif;color:#1e293b'>
<p>Hi {$first},</p>
<p>Your ProductivRN QBL Alerts account has been created. Click the button below to verify your email address, set your password, and agree to the Terms of Use to get started:</p>
<p><a href='{$setupLink}' style='background:#1a4a8a;color:#fff;padding:10px 20px;border-radius:6px;text-decoration:none;font-weight:700'>Verify Email &amp; Set Password</a></p>
<p style='font-size:0.85em;color:#64748b'>Or copy this link: {$setupLink}</p>
<p style='font-size:0.85em;color:#64748b'>This link expires once used. If you did not expect this email, please ignore it or contact support@productivern.com.</p>
<p>Thank you,<br>ProductivRN</p>
</body></html>";
                mail($email, $subject, $message, $headers);
            }
        }
        header('location: ' . $redir . '&tab=users&msg=saved'); exit;

    } elseif ($action === 'regenerate_qr') {
        $db->query("DELETE FROM productiveQrTokens WHERE deptId={$deptId} AND page='qbl'");
        $newTok = bin2hex(random_bytes(32));
        $db->insert('productiveQrTokens', ['token'=>$newTok, 'userId'=>0, 'deptId'=>$deptId, 'page'=>'qbl']);
        header('location: ' . $redir . '&tab=qr&msg=saved&newqr=1'); exit;

    } elseif ($action === 'add_existing_user') {
        $uid    = intval($_POST['userId'] ?? 0);
        $mobile = preg_replace('/\D/', '', $_POST['mobile'] ?? '');
        $sms    = (strlen($mobile) === 10) ? intval($_POST['sms'] ?? 0) : 0;
        $thresh = intval($_POST['threshold'] ?? 0);
        if ($uid) {
            $valid = $db->get_results("SELECT id FROM productiveUser WHERE id={$uid} AND accountId={$accountId} AND role>=5 AND role<=10 LIMIT 1");
            if ($valid) {
                if ($mobile) {
                    $mobileE = openssl_encrypt($mobile, 'AES-128-CTR', ENCRYPT_KEY, 0, ENCRYPT_IV);
                    $existingUpdate = ['mobile' => $mobile, 'mobile_E' => $mobileE, 'qblWarnMin' => $thresh];
                    if ($sms === 1) $existingUpdate['txtEscalation'] = 1;
                    $db->update('productiveUser', $existingUpdate, ['id' => $uid]);
                }
                $xref = $db->get_results("SELECT id FROM productiveDeptXref WHERE userId={$uid} AND deptId={$deptId} LIMIT 1");
                if ($xref) {
                    $db->update('productiveDeptXref', ['escAlerts' => $sms], ['userId' => $uid, 'deptId' => $deptId]);
                } else {
                    $db->insert('productiveDeptXref', ['userId' => $uid, 'deptId' => $deptId, 'primaryUnit' => 0, 'unitAssigned' => 0, 'escAlerts' => $sms]);
                }
            }
        }
        header('location: ' . $redir . '&tab=users&msg=saved'); exit;
    }
}

/* ── Fetch data ─────────────────────────────────────────────────── */
if (isset($_GET['msg'])) $msg = $_GET['msg'];
else $msg = '';

// Re-fetch config after possible save
$cfg = $db->get_results("SELECT q.*, d.dept, d.id as deptId FROM productiveQBLconfig q JOIN ProductiveDept d ON d.id=q.deptId WHERE q.id={$id} AND q.accountId={$accountId} LIMIT 1")[0];
$unitName = htmlspecialchars($cfg['dept']);

// Reports tab data
$logs = $db->get_results("
    SELECT q.id, q.dateTime, q.totalQBL, q.totalWt, q.note, q.managerClosed,
           q.nurse_name, q.cum_qbl, q.session_id,
           CONCAT(u.first_name, ' ', u.last_name) as submittedBy
    FROM productiveQBLlog q
    LEFT JOIN productiveUser u ON u.id = q.userId
    WHERE q.accountId = {$accountId} AND q.deptId = {$deptId}
    ORDER BY q.dateTime DESC
    LIMIT 500
");

// Build session totals: max cum_qbl, sum of weights, and count per session_id
$sessionTotals  = [];
$sessionWeights = [];
$sessionCounts  = [];
if ($logs) {
    foreach ($logs as $r) {
        if (!empty($r['session_id'])) {
            $sid = $r['session_id'];
            $cum = floatval($r['cum_qbl']);
            if (!isset($sessionTotals[$sid]) || $cum > $sessionTotals[$sid]) {
                $sessionTotals[$sid] = $cum;
            }
            $sessionWeights[$sid] = ($sessionWeights[$sid] ?? 0) + floatval($r['totalWt']);
            $sessionCounts[$sid]  = ($sessionCounts[$sid]  ?? 0) + 1;
        }
    }
}

// Users tab data
$allUsers = $db->get_results(
    "SELECT u.id, u.first_name, u.last_name, u.email, u.role, u.active, r.role as roleLabel,
            u.mobile, IFNULL(x.escAlerts, 0) as escAlerts, IFNULL(u.qblWarnMin, 0) as qblWarnMin,
            IFNULL(u.coverUserId, 0) as coverUserId, IFNULL(u.coverPauseSms, 0) as coverPauseSms,
            c.first_name as coverFirst, c.last_name as coverLast
     FROM productiveUser u
     JOIN productiveUserRoles r ON r.id = u.role
     LEFT JOIN productiveDeptXref x ON x.userId = u.id AND x.deptId = {$deptId}
     LEFT JOIN productiveUser c ON c.id = u.coverUserId
     WHERE u.accountId = {$accountId}
       AND u.role >= 5
       AND (u.role >= 8 OR x.userId IS NOT NULL)
     ORDER BY u.active DESC, u.last_name ASC"
);

$roles = $db->get_results("SELECT id, role FROM productiveUserRoles WHERE id >= 5 AND id <= 10 ORDER BY id ASC");

$availableUsers = $db->get_results(
    "SELECT u.id, u.first_name, u.last_name, u.mobile, u.qblWarnMin, u.role,
            IF(x.userId IS NOT NULL, 1, 0) as hasXref
     FROM productiveUser u
     LEFT JOIN productiveDeptXref x ON x.userId = u.id AND x.deptId = {$deptId}
     WHERE u.accountId = {$accountId}
       AND u.role >= 5 AND u.role <= 9 AND u.active > 0
       AND x.userId IS NULL
     ORDER BY u.last_name, u.first_name ASC"
);

// QR token — auto-generate if missing
$qrToken = $db->get_results("SELECT token FROM productiveQrTokens WHERE deptId={$deptId} AND page='qbl' LIMIT 1");
if (!$qrToken) {
    $newTok = bin2hex(random_bytes(32));
    $db->insert('productiveQrTokens', ['token'=>$newTok, 'userId'=>0, 'deptId'=>$deptId, 'page'=>'qbl']);
    $qrToken = [['token'=>$newTok]];
}
$qrCalcUrl = 'https://productivern.com/qbl-entry.php?t=' . $qrToken[0]['token'];

$msgText = [
    'saved'      => ['text' => 'Changes saved.',                                 'type' => 'success'],
    'dup_email'  => ['text' => 'A user with that email address already exists.',  'type' => 'error'],
    'dup_mobile' => ['text' => 'A user with that mobile number already exists.',  'type' => 'error'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $unitName; ?> — QBL Portal</title>
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
:root {
  --navy: #1a4a8a; --navy-dark: #163d73; --teal: #0e6b6b; --red: #c0392b;
  --bg: #f0f2f5; --white: #ffffff; --text: #1a2332; --muted: #6b7f96; --border: #dde1e8;
  --font: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}
html { scroll-behavior: smooth; }
body { font-family: var(--font); background: var(--bg); color: var(--text); line-height: 1.6; -webkit-font-smoothing: antialiased; font-size: 14px; }

nav { position: sticky; top: 0; z-index: 100; background: var(--navy); padding: 14px 20px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 2px 8px rgba(0,0,0,.2); }
.nav-brand { color: #fff; font-size: 1.15rem; font-weight: 800; letter-spacing: .3px; text-decoration: none; }
.nav-brand span { font-weight: 400; opacity: .75; }
.nav-links { display: flex; align-items: center; gap: 6px; }
.nav-links a { color: rgba(255,255,255,.75); text-decoration: none; font-size: .82rem; font-weight: 600; padding: 6px 12px; border-radius: 20px; transition: background .15s; }
.nav-links a:hover { color: #fff; background: rgba(255,255,255,.1); }
.nav-links a.nav-logout { color: rgba(255,255,255,.55); font-weight: 500; }

.page-header { background: var(--white); border-bottom: 1px solid var(--border); padding: 20px 24px 0; }
.page-header h1 { font-size: 1.2rem; font-weight: 800; }
.page-header p { font-size: .82rem; color: var(--muted); margin-top: 2px; }

.tabs { display: flex; border-bottom: none; margin-top: 14px; gap: 2px; }
.tab-link { padding: 9px 18px; font-size: .85rem; font-weight: 600; color: var(--muted); cursor: pointer; border-bottom: 3px solid transparent; text-decoration: none; display: inline-block; transition: color .15s; }
.tab-link:hover { color: var(--text); }
.tab-link.active { color: var(--navy); border-bottom-color: var(--navy); }

.main { padding: 24px 16px; max-width: 1100px; margin: 0 auto; }

.alert { padding: 0.65rem 1rem; border-radius: 6px; font-size: 0.85rem; margin-bottom: 1.25rem; }
.alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
.alert-error   { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }

/* ── Reports ── */
.table-card { background: var(--white); border-radius: 12px; border: 1px solid var(--border); overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,.05); }
.table-scroll { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; font-size: .84rem; }
thead th { background: #f7f8fa; color: var(--muted); font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; padding: 11px 14px; text-align: left; border-bottom: 1px solid var(--border); white-space: nowrap; }
tbody tr { border-bottom: 1px solid var(--border); cursor: pointer; }
tbody tr:last-child { border-bottom: none; }
tbody tr:hover { background: #eef3fb; }
tbody td { padding: 11px 14px; color: var(--text); vertical-align: middle; }
.td-muted { color: var(--muted); font-size: .8rem; }
.td-note { max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

.badge { display: inline-block; font-size: .72rem; font-weight: 700; padding: 3px 9px; border-radius: 20px; white-space: nowrap; }
.badge-red    { background: #fff0f0; color: var(--red); border: 1px solid #f5c6c6; }
.badge-yellow { background: #fef9e7; color: #8a6200; border: 1px solid #f0d97a; }
.badge-teal   { background: #e6f4f4; color: var(--teal); border: 1px solid #b2d8d8; }
.badge-green  { background: #e9f7ef; color: #1e7a43; border: 1px solid #a9daba; }
.badge-gray   { background: #f0f2f5; color: var(--muted); border: 1px solid var(--border); }

.empty-state { text-align: center; padding: 52px 20px; color: var(--muted); }
.empty-state .icon { font-size: 2.2rem; margin-bottom: 12px; }
.empty-state p { font-size: .9rem; }

/* ── Supplies ── */
.card { background: var(--white); border: 1px solid var(--border); border-radius: 10px; overflow: hidden; }
.card-header { padding: 0.85rem 1rem; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; }
.card-title { font-size: 0.88rem; font-weight: 700; color: #0f172a; }

.items-form { padding: 1.25rem; }
.status-row { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.25rem; padding-bottom: 1.25rem; border-bottom: 1px solid #f1f5f9; }
.status-row label { font-size: 0.82rem; font-weight: 600; color: #374151; }
.col-headers { display: grid; grid-template-columns: 2rem 3.5rem 1fr 8rem; gap: 0.75rem; padding-bottom: 0.5rem; border-bottom: 1px solid #e2e8f0; margin-bottom: 0.25rem; }
.col-header { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; }
.col-header:last-child { text-align: right; }
.item-row { display: grid; grid-template-columns: 2rem 3.5rem 1fr 8rem; gap: 0.75rem; align-items: center; padding: 0.5rem 0; border-bottom: 1px solid #f8fafc; }
.item-row:last-child { border-bottom: none; }
.item-num { font-size: 0.75rem; font-weight: 700; color: #94a3b8; text-align: center; }
.img-thumb { width: 48px; height: 48px; border-radius: 7px; border: 2px solid #e2e8f0; object-fit: cover; cursor: pointer; display: block; transition: border-color 0.15s; }
.img-thumb:hover { border-color: #2563eb; }
.img-placeholder { width: 48px; height: 48px; border-radius: 7px; border: 2px dashed #e2e8f0; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; cursor: pointer; color: #cbd5e1; transition: border-color 0.15s; }
.img-placeholder:hover { border-color: #2563eb; color: #2563eb; }
.item-row input[type=text] { width: 100%; padding: 0.42rem 0.65rem; border: 1.5px solid #e2e8f0; border-radius: 5px; font-size: 0.85rem; outline: none; }
.item-row input[type=number] { width: 100%; padding: 0.42rem 0.65rem; border: 1.5px solid #e2e8f0; border-radius: 5px; font-size: 0.85rem; outline: none; text-align: right; }
.item-row input:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.08); }
.form-footer { display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.25rem; }

.picker-overlay { display: none; position: fixed; inset: 0; z-index: 300; background: rgba(0,0,0,0.5); align-items: flex-end; justify-content: center; }
.picker-overlay.open { display: flex; }
.picker-sheet { background: #fff; width: 100%; max-width: 560px; border-radius: 16px 16px 0 0; max-height: 82vh; display: flex; flex-direction: column; }
.picker-handle { width: 36px; height: 4px; background: #e2e8f0; border-radius: 2px; margin: 10px auto 0; flex-shrink: 0; }
.picker-header { padding: 12px 16px 10px; border-bottom: 1px solid #f1f5f9; flex-shrink: 0; display: flex; align-items: center; justify-content: space-between; }
.picker-header h3 { font-size: 0.92rem; font-weight: 700; color: #0f172a; }
.picker-body { padding: 12px 14px; overflow-y: auto; flex: 1; }
.picker-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; }
.picker-img { aspect-ratio: 1; border-radius: 8px; border: 2px solid #e2e8f0; object-fit: cover; cursor: pointer; width: 100%; transition: border-color 0.15s; }
.picker-img:hover, .picker-img.selected { border-color: #2563eb; }
.picker-none { aspect-ratio: 1; border-radius: 8px; border: 2px dashed #e2e8f0; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; color: #94a3b8; cursor: pointer; text-align: center; padding: 4px; }
.picker-none:hover, .picker-none.selected { border-color: #dc2626; color: #dc2626; }
.picker-footer { padding: 10px 14px 16px; border-top: 1px solid #f1f5f9; display: flex; gap: 8px; flex-shrink: 0; }
.btn-upload-label { flex: 1; background: #f8fafc; color: #475569; font-size: 0.82rem; font-weight: 600; padding: 9px 14px; border-radius: 7px; border: 1px solid #e2e8f0; cursor: pointer; text-align: center; display: block; }
.btn-upload-label:hover { background: #f1f5f9; }

/* ── Users ── */
.users-table thead th { background: #f8fafc; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; padding: 0.6rem 1rem; text-align: left; border-bottom: 1px solid #e2e8f0; white-space: nowrap; cursor: default; }
.users-table tbody tr { border-bottom: 1px solid #f1f5f9; cursor: default; }
.users-table tbody tr:hover { background: #f8fafc; }
.users-table td { padding: 0.65rem 1rem; vertical-align: middle; }
.pill { display: inline-block; font-size: 0.68rem; font-weight: 700; padding: 2px 7px; border-radius: 20px; }
.pill-active   { background: #dcfce7; color: #15803d; }
.pill-inactive { background: #f1f5f9; color: #94a3b8; }
.role-badge { display: inline-block; background: #f1f5f9; color: #475569; font-size: 0.72rem; font-weight: 600; padding: 2px 7px; border-radius: 4px; }

.btn { display: inline-block; padding: 0.4rem 0.85rem; border-radius: 6px; font-size: 0.8rem; font-weight: 600; cursor: pointer; border: none; text-decoration: none; transition: background 0.15s; }
.btn-primary { background: #2563eb; color: #fff; } .btn-primary:hover { background: #1d4ed8; }
.btn-sm { padding: 0.25rem 0.62rem; font-size: 0.74rem; }
.btn-outline { background: none; border: 1px solid #e2e8f0; color: #475569; } .btn-outline:hover { background: #f8fafc; }

/* ── Modals (reports detail) ── */
.modal-overlay { display: none; position: fixed; inset: 0; z-index: 200; background: rgba(10,20,40,.55); align-items: flex-end; justify-content: center; }
.modal-overlay.open { display: flex; }
.modal-sheet { background: var(--white); width: 100%; max-width: 560px; border-radius: 20px 20px 0 0; max-height: 90vh; overflow-y: auto; animation: slideUp .22s ease; }
@keyframes slideUp { from { transform: translateY(60px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
.modal-handle { width: 40px; height: 4px; background: var(--border); border-radius: 2px; margin: 12px auto 0; }
.modal-header { background: linear-gradient(135deg, #0d2f5c, var(--navy)); color: #fff; padding: 20px 20px 16px; }
.modal-header h2 { font-size: 1rem; font-weight: 800; }
.modal-header p  { font-size: .82rem; opacity: .75; margin-top: 3px; }
.modal-totals { display: flex; gap: 8px; padding: 14px 16px 0; }
.modal-total { flex: 1; background: var(--bg); border-radius: 10px; padding: 12px 8px; text-align: center; border: 1px solid var(--border); }
.modal-total .val { font-size: 1.3rem; font-weight: 900; line-height: 1; }
.modal-total .val.red { color: var(--red); }
.modal-total .val.teal { color: var(--teal); }
.modal-total .lbl { font-size: .65rem; color: var(--muted); text-transform: uppercase; letter-spacing: .5px; margin-top: 4px; }
.modal-section-label { font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .8px; color: var(--muted); padding: 16px 16px 6px; }
.modal-items { padding: 0 16px; display: flex; flex-direction: column; gap: 6px; }
.modal-item { background: var(--bg); border-radius: 8px; padding: 10px 12px; display: flex; justify-content: space-between; align-items: center; border: 1px solid var(--border); }
.modal-item .name { font-size: .88rem; color: var(--text); }
.modal-item .qty  { font-size: .78rem; color: var(--muted); margin-left: 6px; }
.modal-item .ml   { font-size: .9rem; font-weight: 700; color: var(--red); white-space: nowrap; }
.modal-note { margin: 12px 16px; background: #fef9e7; border-radius: 8px; padding: 12px; border: 1px solid #f0d97a; }
.modal-note .note-label { font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: #8a6200; margin-bottom: 4px; }
.modal-note p { font-size: .88rem; color: var(--text); }
.modal-meta { padding: 12px 16px; font-size: .78rem; color: var(--muted); border-top: 1px solid var(--border); margin-top: 12px; }
.modal-close-btn { display: block; margin: 14px 16px 20px; background: var(--bg); color: var(--muted); font-size: .88rem; font-weight: 700; padding: 12px; border-radius: 10px; border: 1px solid var(--border); text-align: center; cursor: pointer; width: calc(100% - 32px); }
.modal-close-btn:hover { background: var(--border); }
.modal-spinner { text-align: center; padding: 40px; color: var(--muted); font-size: .9rem; }

/* ── User/SMS modals ── */
.modal-bg { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.45); z-index: 200; align-items: flex-start; justify-content: center; padding-top: 4rem; overflow-y: auto; }
.modal-bg.open { display: flex; }
.modal-box { background: #fff; border-radius: 12px; width: 100%; max-width: 480px; padding: 1.75rem; box-shadow: 0 20px 60px rgba(0,0,0,0.15); margin-bottom: 2rem; }
.modal-title { font-size: 1rem; font-weight: 700; color: #0f172a; margin-bottom: 1.25rem; }
.field { margin-bottom: 0.9rem; }
.field label { display: block; font-size: 0.77rem; font-weight: 600; color: #374151; margin-bottom: 0.3rem; }
.field input, .field select { width: 100%; padding: 0.52rem 0.75rem; border: 1.5px solid #d1d5db; border-radius: 6px; font-size: 0.88rem; color: #0f172a; background: #fff; outline: none; }
.field input:focus, .field select:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
.field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
.field-hint { font-size: 0.72rem; color: #9ca3af; margin-top: 0.2rem; }
.modal-footer { display: flex; justify-content: flex-end; gap: 0.6rem; margin-top: 1.25rem; }
optgroup { font-weight: 700; }
.mode-toggle { display: flex; border: 1.5px solid #e2e8f0; border-radius: 7px; overflow: hidden; margin-bottom: 1.25rem; }
.mode-btn { flex: 1; padding: 0.5rem; font-size: 0.82rem; font-weight: 600; background: none; border: none; cursor: pointer; color: #64748b; transition: background 0.15s; }
.mode-btn.active { background: #2563eb; color: #fff; }
.mode-section { display: none; } .mode-section.active { display: block; }

/* ── QR Code ── */
.qr-card { background: var(--white); border-radius: 12px; border: 1px solid var(--border); box-shadow: 0 1px 4px rgba(0,0,0,.05); overflow: hidden; max-width: 420px; margin: 0 auto; }
.qr-body { padding: 24px; text-align: center; }
.qr-desc { font-size: .85rem; color: var(--muted); line-height: 1.6; margin-bottom: 20px; text-align: left; }
.qr-code-box { display: inline-block; padding: 12px; background: #fff; border: 1px solid var(--border); border-radius: 10px; margin-bottom: 12px; }
.qr-code-box canvas, .qr-code-box img { display: block; }
.qr-url { font-size: .72rem; color: var(--muted); word-break: break-all; margin-top: 4px; }
.qr-actions { display: flex; gap: 10px; padding: 0 24px 20px; }
.btn-qr-print { flex: 1; background: var(--navy); color: #fff; border: none; border-radius: 8px; padding: 10px 14px; font-size: .85rem; font-weight: 700; cursor: pointer; }
.btn-qr-print:hover { background: var(--navy-dark); }
.btn-qr-regen { flex: 1; background: none; color: var(--muted); border: 1px solid var(--border); border-radius: 8px; padding: 10px 14px; font-size: .85rem; font-weight: 600; cursor: pointer; }
.btn-qr-regen:hover { border-color: var(--red); color: var(--red); }
.qr-regen-panel { display: none; border-top: 2px solid var(--red); background: #fff8f8; padding: 18px 24px 20px; }
.qr-regen-panel.open { display: block; }
.qr-regen-warning { font-size: .88rem; line-height: 1.65; color: #7b1a1a; margin-bottom: 16px; }
.qr-regen-btns { display: flex; gap: 10px; }
.btn-qr-confirm { flex: 1; background: var(--red); color: #fff; border: none; border-radius: 8px; padding: 11px 14px; font-size: .85rem; font-weight: 700; cursor: pointer; }
.btn-qr-confirm:hover { background: #a93226; }
.btn-qr-cancel { flex: 1; background: none; color: var(--muted); border: 1px solid var(--border); border-radius: 8px; padding: 11px 14px; font-size: .85rem; font-weight: 600; cursor: pointer; }
@media print {
  nav, .page-header, .qr-actions, .qr-regen-panel, footer { display: none !important; }
  .main { padding: 0 !important; }
  .qr-card { border: none !important; box-shadow: none !important; max-width: 100% !important; }
  .qr-body { padding: 20px !important; }
  .qr-desc { font-size: 1rem !important; color: #000 !important; }
  .qr-url { font-size: .85rem !important; color: #333 !important; }
}

footer { background: #0d2040; color: rgba(255,255,255,.5); text-align: center; padding: 24px 20px; font-size: .75rem; line-height: 1.8; margin-top: 48px; }
footer strong { color: rgba(255,255,255,.8); }

@media (max-width: 600px) {
  .nav-links a { padding: 5px 8px; font-size: .76rem; }
  .main { padding: 16px 10px; }
  .modal-sheet { border-radius: 16px 16px 0 0; }
}
@media (min-width: 600px) {
  .modal-overlay { align-items: center; }
  .modal-sheet { border-radius: 16px; max-height: 85vh; }
}
</style>
</head>
<body>

<nav>
  <a class="nav-brand" href="/qbl-settings.php">Productive<span>RN</span> &mdash; QBL Alerts</a>
  <div class="nav-links">
    <a href="/qbl-settings.php">&larr; Units</a>
    <a href="/logout.php" class="nav-logout">Logout</a>
  </div>
</nav>

<div class="page-header">
  <h1><?php echo $unitName; ?></h1>
  <p><?php echo $company; ?> &nbsp;&middot;&nbsp; <span class="badge <?php echo $cfg['active'] ? 'badge-teal' : 'badge-gray'; ?>"><?php echo $cfg['active'] ? 'Active' : 'Inactive'; ?></span></p>
  <div class="tabs">
    <a class="tab-link <?php echo $tab==='reports'  ? 'active' : ''; ?>" href="?id=<?php echo $id; ?>&tab=reports">Reports <span style="background:#e2e8f0;color:#475569;border-radius:10px;padding:1px 6px;font-size:.7rem;font-weight:700;margin-left:3px"><?php echo count($logs); ?></span></a>
    <a class="tab-link <?php echo $tab==='supplies' ? 'active' : ''; ?>" href="?id=<?php echo $id; ?>&tab=supplies">Supplies</a>
    <a class="tab-link <?php echo $tab==='users'    ? 'active' : ''; ?>" href="?id=<?php echo $id; ?>&tab=users">Users <span style="background:#e2e8f0;color:#475569;border-radius:10px;padding:1px 6px;font-size:.7rem;font-weight:700;margin-left:3px"><?php echo count($allUsers); ?></span></a>
    <a class="tab-link <?php echo $tab==='qr' ? 'active' : ''; ?>" href="?id=<?php echo $id; ?>&tab=qr">QR Code</a>
  </div>
</div>

<div class="main">
  <?php if ($msg && isset($msgText[$msg])): ?>
    <div class="alert alert-<?php echo $msgText[$msg]['type']; ?>"><?php echo $msgText[$msg]['text']; ?></div>
  <?php endif; ?>

  <?php if ($tab === 'reports'): ?>
  <!-- REPORTS TAB -->
  <div class="table-card">
    <?php if (empty($logs)): ?>
      <div class="empty-state">
        <div class="icon">&#128203;</div>
        <p>No QBL reports yet for this unit.</p>
      </div>
    <?php else: ?>
    <div class="table-scroll">
      <table>
        <thead>
          <tr>
            <th>Date / Time</th>
            <th>Submitted By</th>
            <th>QBL (ml)</th>
            <th>Wt (g)</th>
            <th>QBL/Wt %</th>
            <th>Cumulative (ml)</th>
            <th>Cumulative (%)</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($logs as $row):
            $qbl        = floatval($row['totalQBL']);
            $hasSession = !empty($row['session_id']) && ($sessionCounts[$row['session_id']] ?? 1) > 1;
            $cumQbl     = $hasSession ? ($sessionTotals[$row['session_id']] ?? $qbl) : $qbl;
            $wt         = floatval($row['totalWt']);
            $ratioVal   = ($wt > 0) ? ($qbl / $wt) * 100 : null;
            $ratio      = $ratioVal !== null ? number_format($ratioVal, 1) : '—';
            $sessionWt   = $hasSession ? ($sessionWeights[$row['session_id']] ?? 0) : 0;
            $cumRatioVal = ($hasSession && $sessionWt > 0) ? ($cumQbl / $sessionWt) * 100 : null;
            $cumRatio   = $cumRatioVal !== null ? number_format($cumRatioVal, 1) : '—';
            $cumBadge   = $cumQbl >= 1000 ? 'badge-red' : ($cumQbl >= 500 ? 'badge-yellow' : 'badge-teal');
            if ($qbl >= 1000)      $qblBadge = 'badge-red';
            elseif ($qbl >= 500)   $qblBadge = 'badge-yellow';
            else                   $qblBadge = 'badge-teal';
          ?>
          <tr data-id="<?php echo intval($row['id']); ?>">
            <td class="td-muted"><?php echo htmlspecialchars($row['dateTime']); ?></td>
            <td>
              <?php echo htmlspecialchars($row['submittedBy'] ?? ''); ?>
              <?php if (!empty($row['nurse_name'])): ?>
                <div style="font-size:.7rem;color:var(--muted);margin-top:2px;"><?php echo htmlspecialchars($row['nurse_name']); ?></div>
              <?php endif; ?>
            </td>
            <td><span class="badge <?php echo $qblBadge; ?>"><?php echo number_format($qbl); ?> ml</span></td>
            <td><?php echo $wt > 0 ? number_format($wt) : '—'; ?></td>
            <td><?php if ($ratioVal !== null): ?>
              <?php if ($ratioVal > 75): ?>
                <span class="badge badge-red"><?php echo $ratio; ?>%</span>
              <?php else: ?>
                <?php echo $ratio; ?>%
              <?php endif; ?>
            <?php else: ?>—<?php endif; ?></td>
            <td><?php if ($hasSession): ?><span class="badge <?php echo $cumBadge; ?>"><?php echo number_format($cumQbl); ?> ml</span><?php else: ?>—<?php endif; ?></td>
            <td><?php if ($cumRatioVal !== null): ?>
              <?php if ($cumRatioVal > 75): ?>
                <span class="badge badge-red"><?php echo $cumRatio; ?>%</span>
              <?php else: ?>
                <?php echo $cumRatio; ?>%
              <?php endif; ?>
            <?php else: ?>—<?php endif; ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>

  <?php elseif ($tab === 'supplies'): ?>
  <!-- SUPPLIES TAB -->
  <div class="card">
    <form method="post" class="items-form">
      <input type="hidden" name="action" value="save_items">
      <div class="status-row">
        <label>Status:</label>
        <select name="active" style="padding:0.4rem 0.7rem;border:1.5px solid #e2e8f0;border-radius:5px;font-size:0.86rem">
          <option value="1" <?php echo $cfg['active']==1?'selected':''; ?>>Active</option>
          <option value="0" <?php echo $cfg['active']==0?'selected':''; ?>>Inactive</option>
        </select>
      </div>
      <div class="col-headers">
        <div class="col-header">#</div>
        <div class="col-header">Img</div>
        <div class="col-header">Supply Description</div>
        <div class="col-header">Value</div>
      </div>
      <?php for ($i = 1; $i <= 20; $i++):
          $imgVal = $cfg["image{$i}"] ?? '';
      ?>
      <div class="item-row">
        <div class="item-num"><?php echo $i; ?></div>
        <div>
          <input type="hidden" name="image<?php echo $i; ?>" id="imgInput<?php echo $i; ?>" value="<?php echo htmlspecialchars($imgVal); ?>">
          <?php if ($imgVal): ?>
            <img src="/qblimages/<?php echo htmlspecialchars($imgVal); ?>" class="img-thumb" onclick="openPicker(<?php echo $i; ?>)" title="Click to change">
          <?php else: ?>
            <div class="img-placeholder" onclick="openPicker(<?php echo $i; ?>)">＋</div>
          <?php endif; ?>
        </div>
        <input type="text" name="desc<?php echo $i; ?>" value="<?php echo htmlspecialchars($cfg["desc{$i}"] ?? ''); ?>" placeholder="Supply description">
        <input type="number" name="value<?php echo $i; ?>" value="<?php echo floatval($cfg["value{$i}"] ?? 0); ?>" step="0.01" min="0">
      </div>
      <?php endfor; ?>
      <div class="form-footer">
        <button type="submit" class="btn btn-primary">Save Items</button>
      </div>
    </form>
  </div>

  <?php elseif ($tab === 'users'): ?>
  <!-- USERS TAB -->
  <div class="card">
    <div class="card-header">
      <span class="card-title">QBL SMS Configuration</span>
      <button class="btn btn-sm btn-primary" onclick="openModal('createUserModal')">+ Add User</button>
    </div>
    <table class="users-table">
      <thead><tr><th>Name</th><th>Role</th><th>Mobile</th><th>Threshold</th><th>SMS Alerts</th><th>Coverage</th></tr></thead>
      <tbody>
      <?php if (empty($allUsers)): ?>
        <tr><td colspan="6"><div class="empty-state">No users associated with this unit yet.</div></td></tr>
      <?php else: foreach ($allUsers as $u): ?>
      <tr>
        <td>
          <?php echo htmlspecialchars($u['first_name'] . ' ' . $u['last_name']); ?>
          <?php if (!$u['active']): ?><span class="pill pill-inactive" style="margin-left:4px">Inactive</span><?php endif; ?>
        </td>
        <td><span class="role-badge"><?php echo htmlspecialchars($u['roleLabel']); ?></span></td>
        <td style="font-size:0.82rem">
          <span style="cursor:pointer;color:#2563eb;text-decoration:underline;text-decoration-style:dotted" onclick="openSmsEdit(<?php echo htmlspecialchars(json_encode(['id'=>$u['id'],'first_name'=>$u['first_name'],'last_name'=>$u['last_name'],'mobile'=>$u['mobile'],'escAlerts'=>intval($u['escAlerts']),'qblWarnMin'=>$u['qblWarnMin'],'active'=>intval($u['active'])]), ENT_QUOTES); ?>)"><?php echo $u['mobile'] ? htmlspecialchars($u['mobile']) : '<span style="color:#94a3b8">Add</span>'; ?></span>
        </td>
        <td style="color:#64748b;font-size:0.82rem"><?php echo $u['qblWarnMin'] > 0 ? number_format($u['qblWarnMin']) . ' ml' : '<span style="color:#cbd5e1">—</span>'; ?></td>
        <td><?php
          if ($u['coverUserId'] && $u['coverPauseSms']):
        ?><span class="pill pill-inactive" style="background:#fef3c7;color:#92400e;border-color:#fde68a">Paused</span><?php
          elseif ($u['escAlerts']):
        ?><span class="pill pill-active">Yes</span><?php
          else:
        ?><span class="pill pill-inactive">No</span><?php
          endif;
        ?></td>
        <td>
          <?php if ($u['coverUserId']): ?>
            <span style="font-size:0.8rem;color:#0284c7"><?php echo htmlspecialchars($u['coverFirst'].' '.$u['coverLast']); ?></span>
            <button class="btn btn-sm btn-outline" onclick="openCoverage(<?php echo $u['id']; ?>, <?php echo htmlspecialchars(json_encode($u['first_name'].' '.$u['last_name']), ENT_QUOTES); ?>)" style="margin-left:4px">Edit</button>
          <?php else: ?>
            <button class="btn btn-sm btn-outline" onclick="openCoverage(<?php echo $u['id']; ?>, <?php echo htmlspecialchars(json_encode($u['first_name'].' '.$u['last_name']), ENT_QUOTES); ?>)">Set</button>
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
  <?php elseif ($tab === 'qr'): ?>
  <!-- QR CODE TAB -->
  <div class="qr-card">
    <div class="qr-body">
      <p class="qr-desc">Nurses scan this QR code on the scale to open the QBL calculator for <strong><?php echo $unitName; ?></strong>.</p>
      <div id="qrcode" class="qr-code-box"></div>
      <div class="qr-url"><?php echo htmlspecialchars($qrCalcUrl); ?></div>
    </div>
    <div class="qr-actions">
      <button class="btn-qr-print" onclick="printQR()">&#128438; Print QR Code</button>
      <button class="btn-qr-regen" onclick="showRegenWarning()">&#8635; Create New QR Code</button>
    </div>

    <div class="qr-regen-panel" id="regenPanel">
      <div class="qr-regen-warning">
        &#9888; <strong>YOU MUST IMMEDIATELY PRINT THE NEW QR CODE AND REPLACE ALL EXISTING CODES POSTED ON YOUR EQUIPMENT.</strong> The current QR code will stop working the moment a new one is created. Any nurse who scans the old code will receive an error. Do not create a new code unless you are ready to print and replace it right now.
      </div>
      <form method="POST" action="?id=<?php echo $id; ?>&tab=qr">
        <input type="hidden" name="action" value="regenerate_qr">
        <div class="qr-regen-btns">
          <button type="submit" class="btn-qr-confirm">Yes, Create New Code &amp; Print Now</button>
          <button type="button" class="btn-qr-cancel" onclick="hideRegenWarning()">Cancel</button>
        </div>
      </form>
    </div>
  </div>
  <?php endif; ?>
</div>

<!-- REPORTS: Detail modal -->
<div class="modal-overlay" id="detailModal">
  <div class="modal-sheet" id="modalSheet">
    <div class="modal-handle"></div>
    <div id="modalContent"><div class="modal-spinner">Loading&hellip;</div></div>
    <button class="modal-close-btn" onclick="closeDetailModal()">Close</button>
  </div>
</div>

<!-- ADD USER MODAL -->
<div class="modal-bg" id="createUserModal">
  <div class="modal-box">
    <div class="modal-title">Add User &mdash; <?php echo $unitName; ?></div>
    <div class="mode-toggle">
      <button type="button" class="mode-btn active" onclick="switchMode('select', this)">Select Existing</button>
      <button type="button" class="mode-btn" onclick="switchMode('create', this)">Create New</button>
    </div>

    <!-- SELECT EXISTING -->
    <div class="mode-section active" id="mode-select">
      <form method="post">
        <input type="hidden" name="action" value="add_existing_user">
        <div class="field">
          <label>User</label>
          <select name="userId" id="existingUserId" onchange="prefillExisting(this)">
            <option value="">— Select a user —</option>
            <?php foreach (($availableUsers ?: []) as $au): ?>
            <option value="<?php echo intval($au['id']); ?>"
                    data-mobile="<?php echo htmlspecialchars($au['mobile'] ?? ''); ?>"
                    data-threshold="<?php echo intval($au['qblWarnMin']); ?>">
              <?php echo htmlspecialchars($au['last_name'] . ', ' . $au['first_name']); ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="field">
          <label>Mobile Number</label>
          <input type="text" name="mobile" id="existingMobile" placeholder="10-digit number">
        </div>
        <div class="field-row">
          <div class="field">
            <label>Alert Threshold (ml)</label>
            <input type="number" name="threshold" id="existingThreshold" placeholder="e.g. 500" min="0" step="50">
          </div>
          <div class="field">
            <label>Receive QBL SMS Alerts</label>
            <select name="sms" id="existingSms" disabled>
              <option value="0">No</option>
              <option value="1">Yes</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline" onclick="closeModal('createUserModal')">Cancel</button>
          <button type="submit" class="btn btn-primary">Associate to Unit</button>
        </div>
      </form>
    </div>

    <!-- CREATE NEW -->
    <div class="mode-section" id="mode-create">
      <form method="post">
        <input type="hidden" name="action" value="create_user">
        <div class="field-row">
          <div class="field">
            <label>First Name</label>
            <input type="text" name="first_name">
          </div>
          <div class="field">
            <label>Last Name</label>
            <input type="text" name="last_name">
          </div>
        </div>
        <div class="field">
          <label>Email (used as username)</label>
          <input type="email" name="email">
          <div class="field-hint">A setup link will be emailed to this address</div>
        </div>
        <div class="field">
          <label>Role</label>
          <select name="role">
            <?php foreach (($roles ?: []) as $r): ?>
            <option value="<?php echo intval($r['id']); ?>"><?php echo htmlspecialchars($r['role']); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="field">
          <label>Mobile Number</label>
          <input type="text" name="mobile" id="createMobile" placeholder="10-digit number">
        </div>
        <div class="field-row">
          <div class="field">
            <label>Alert Threshold (ml)</label>
            <input type="number" name="threshold" placeholder="e.g. 500" min="0" step="50">
          </div>
          <div class="field">
            <label>Receive QBL SMS Alerts</label>
            <select name="sms" id="createSms" disabled>
              <option value="0">No</option>
              <option value="1">Yes</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline" onclick="closeModal('createUserModal')">Cancel</button>
          <button type="submit" class="btn btn-primary">Create &amp; Associate</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- SMS EDIT MODAL -->
<div class="modal-bg" id="coverageModal">
  <div class="modal-box">
    <div class="modal-title">Coverage &mdash; <span id="coverageName"></span></div>
    <input type="hidden" id="coverageUserId">
    <div class="field">
      <label>Person Covering</label>
      <select id="coverCoverUserId" class="field select">
        <option value="0">— select user —</option>
        <?php foreach ($allUsers as $cu): if (!$cu['mobile'] || $cu['escAlerts']) continue; ?>
        <option value="<?php echo intval($cu['id']); ?>" data-uid="<?php echo intval($cu['id']); ?>"><?php echo htmlspecialchars($cu['last_name'].', '.$cu['first_name']); ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="field-row">
      <div class="field">
        <label>Start Date</label>
        <input type="date" id="coverStartDate">
      </div>
      <div class="field">
        <label>Start Time</label>
        <select id="coverStartTime">
          <?php for($h=0;$h<24;$h++){$t=str_pad($h,2,'0',STR_PAD_LEFT).':00';echo "<option value=\"$t\">$t</option>";}?>
        </select>
      </div>
    </div>
    <div class="field-row">
      <div class="field">
        <label>End Date</label>
        <input type="date" id="coverEndDate">
      </div>
      <div class="field">
        <label>End Time</label>
        <select id="coverEndTime">
          <option value="">— no end time —</option>
          <?php for($h=0;$h<24;$h++){$t=str_pad($h,2,'0',STR_PAD_LEFT).':00';echo "<option value=\"$t\">$t</option>";}?>
        </select>
      </div>
    </div>
    <div class="field">
      <label>QBL Minimum (ml)</label>
      <input type="number" id="coverQblMin" value="300" min="0" step="50">
    </div>
    <div class="field" style="display:flex;align-items:center;gap:.5rem">
      <input type="checkbox" id="coverPauseSms" value="1" style="width:auto">
      <label for="coverPauseSms" style="margin:0;font-size:0.82rem;font-weight:400;color:#374151">Pause my SMS messages during coverage</label>
    </div>
    <div class="modal-footer" style="display:flex;gap:.5rem;margin-top:1rem">
      <button type="button" class="btn btn-outline" onclick="closeModal('coverageModal')">Cancel</button>
      <button type="button" id="coverRemoveBtn" class="btn btn-outline" style="color:#dc2626;border-color:#dc2626;display:none" onclick="removeCoverage()">Remove</button>
      <button type="button" class="btn btn-primary" onclick="saveCoverage()">Save</button>
    </div>
  </div>
</div>

<div class="modal-bg" id="smsModal">
  <div class="modal-box">
    <div class="modal-title">SMS Settings &mdash; <span id="smsName"></span></div>
    <form method="post">
      <input type="hidden" name="action" value="update_sms">
      <input type="hidden" name="userId" id="smsUserId">
      <input type="hidden" id="smsActive">
      <div class="field">
        <label>Mobile Number</label>
        <input type="text" name="mobile" id="smsMobile" placeholder="10-digit number">
      </div>
      <div class="field">
        <label>Alert Threshold (ml)</label>
        <input type="number" name="threshold" id="smsThreshold" placeholder="e.g. 500" min="0" step="50">
        <div class="field-hint">Send SMS when QBL reaches this value. Leave 0 for no threshold.</div>
      </div>
      <div class="field">
        <label>Receive QBL SMS Alerts</label>
        <select name="sms" id="smsSms">
          <option value="0">No</option>
          <option value="1">Yes</option>
        </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('smsModal')">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="submitSmsEdit(this)">Save</button>
      </div>
    </form>
  </div>
</div>

<!-- IMAGE PICKER -->
<div class="picker-overlay" id="pickerOverlay">
  <div class="picker-sheet">
    <div class="picker-handle"></div>
    <div class="picker-header">
      <h3>Select Image</h3>
      <button type="button" onclick="closePicker()" style="background:none;border:none;font-size:1.3rem;color:#94a3b8;cursor:pointer;line-height:1">&times;</button>
    </div>
    <div class="picker-body">
      <div class="picker-grid" id="pickerGrid">
        <div class="picker-none" id="pickerNone" onclick="selectImage('')">No Image</div>
        <?php foreach ($availImgs as $img): ?>
        <img src="/qblimages/<?php echo htmlspecialchars($img); ?>" class="picker-img" data-file="<?php echo htmlspecialchars($img); ?>" onclick="selectImage('<?php echo htmlspecialchars($img, ENT_QUOTES); ?>')">
        <?php endforeach; ?>
      </div>
    </div>
    <div class="picker-footer">
      <label class="btn-upload-label">
        Upload Image
        <input type="file" accept="image/*" style="display:none" onchange="uploadImage(this)">
      </label>
      <button type="button" class="btn btn-primary" onclick="closePicker()">Done</button>
    </div>
  </div>
</div>

<footer>
  <strong>ProductivRN</strong> &mdash; QBL Alerts<br>
  &copy; <?php echo date('Y'); ?> ProductivRN. All rights reserved.<br>
  <span style="font-size:.68rem;opacity:.6">QBL Alerts is a notification tool and does not replace clinical judgment. Always follow your institution's hemorrhage protocols.</span>
</footer>

<script>
var portalId = <?php echo $id; ?>;

/* ── Reports detail modal ── */
var overlay = document.getElementById('detailModal');
var content = document.getElementById('modalContent');
document.querySelectorAll('tbody tr[data-id]').forEach(function(row) {
  row.addEventListener('click', function() { openDetailModal(this.dataset.id); });
});
function openDetailModal(id) {
  content.innerHTML = '<div class="modal-spinner">Loading&hellip;</div>';
  overlay.classList.add('open');
  document.body.style.overflow = 'hidden';
  fetch('/qbl-portal.php?logId=' + encodeURIComponent(id))
    .then(function(r) { return r.json(); })
    .then(function(d) { renderModal(d); })
    .catch(function() { content.innerHTML = '<div class="modal-spinner">Error loading report.</div>'; });
}
function closeDetailModal() {
  overlay.classList.remove('open');
  document.body.style.overflow = '';
}
overlay.addEventListener('click', function(e) { if (e.target === overlay) closeDetailModal(); });
document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeDetailModal(); });

function renderModal(d) {
  if (!d.success) { content.innerHTML = '<div class="modal-spinner">Report not found.</div>'; return; }
  var qbl       = Math.round(parseFloat(d.totalQBL) || 0);
  var wt        = Math.round(parseFloat(d.totalWt)  || 0);
  var pct       = wt > 0 ? ((qbl / wt) * 100).toFixed(1) : '—';
  var qblClass  = qbl >= 1000 ? 'red' : 'teal';
  var hasSession = d.session_id && d.session_id !== '';
  var sessionCum = hasSession && d.session_cum_total ? Math.round(parseFloat(d.session_cum_total)) : null;
  var sessionWt  = hasSession && d.session_total_wt  ? parseFloat(d.session_total_wt) : 0;
  var cumPct     = (sessionCum !== null && sessionWt > 0) ? ((sessionCum / sessionWt) * 100).toFixed(1) : null;
  var itemsHtml = ''; var hasItems = false;
  for (var i = 1; i <= 20; i++) {
    var desc = d['desc' + i] || '';
    var qty  = parseFloat(d['v' + i] || 0);
    var cfg  = parseFloat(d['value' + i] || 0);
    if (!desc || cfg === 0 || qty === 0) continue;
    var ml = Math.round(qty * cfg);
    if (ml <= 0) continue;
    hasItems = true;
    itemsHtml += '<div class="modal-item"><span class="name">' + esc(desc) + '<span class="qty">&#215;&nbsp;' + qty + '</span></span><span class="ml">' + ml + ' ml</span></div>';
  }
  var nurseHtml = d.nurse_name ? '<span style="font-size:.75rem;color:var(--muted);margin-left:4px;">&#8226; ' + esc(d.nurse_name) + '</span>' : '';
  var cumHtml = '';
  if (sessionCum !== null) {
    var cumClass = sessionCum >= 1000 ? 'red' : 'teal';
    cumHtml = '<div class="modal-totals" style="border-top:1px solid var(--border)">' +
      '<div class="modal-total"><div class="val ' + cumClass + '">' + sessionCum + '</div><div class="lbl">Cumulative (ml)</div></div>' +
      (cumPct !== null ? '<div class="modal-total"><div class="val' + (parseFloat(cumPct) > 75 ? ' red' : '') + '">' + cumPct + '%</div><div class="lbl">Cumulative (%)</div></div>' : '') +
    '</div>';
  }
  content.innerHTML =
    '<div class="modal-header"><h2>' + esc(d.deptName || '—') + '</h2><p>' + esc(d.dateTime || '') + ' &mdash; ' + esc(d.submittedBy || esc(d.nurse_name || 'Unknown')) + nurseHtml + '</p></div>' +
    '<div class="modal-totals">' +
      '<div class="modal-total"><div class="val ' + qblClass + '">' + qbl + '</div><div class="lbl">QBL (ml)</div></div>' +
      '<div class="modal-total"><div class="val">' + wt + '</div><div class="lbl">Weight (g)</div></div>' +
      '<div class="modal-total"><div class="val' + (pct !== '—' && parseFloat(pct) > 75 ? ' red' : '') + '">' + (pct !== '—' ? pct + '%' : '—') + '</div><div class="lbl">QBL / Wt</div></div>' +
    '</div>' +
    cumHtml +
    (hasItems ? '<div class="modal-section-label">Supply Breakdown</div><div class="modal-items">' + itemsHtml + '</div>' : '') +
    '<p style="font-size:.72rem;color:var(--muted);line-height:1.5;padding:12px 16px;margin:0;border-top:1px solid var(--border);">This tool is a clinical support aid and does not replace clinical judgment. Always follow your institution\u2019s hemorrhage protocols.</p>';
}
function esc(str) {
  return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

/* ── Image picker ── */
var pickerItemIndex = null;
function openPicker(i) {
  pickerItemIndex = i;
  var current = document.getElementById('imgInput' + i).value;
  document.querySelectorAll('.picker-img').forEach(function(img) { img.classList.toggle('selected', img.dataset.file === current); });
  document.getElementById('pickerNone').classList.toggle('selected', !current);
  document.getElementById('pickerOverlay').classList.add('open');
}
function closePicker() { document.getElementById('pickerOverlay').classList.remove('open'); }
function selectImage(file) {
  if (pickerItemIndex === null) return;
  var row = document.getElementById('imgInput' + pickerItemIndex).parentElement;
  if (file) {
    row.innerHTML = '<input type="hidden" name="image' + pickerItemIndex + '" id="imgInput' + pickerItemIndex + '" value="' + file + '"><img src="/qblimages/' + file + '" class="img-thumb" onclick="openPicker(' + pickerItemIndex + ')" title="Click to change">';
  } else {
    row.innerHTML = '<input type="hidden" name="image' + pickerItemIndex + '" id="imgInput' + pickerItemIndex + '" value=""><div class="img-placeholder" onclick="openPicker(' + pickerItemIndex + ')">＋</div>';
  }
  document.querySelectorAll('.picker-img').forEach(function(img) { img.classList.toggle('selected', img.dataset.file === file); });
  document.getElementById('pickerNone').classList.toggle('selected', !file);
}
function uploadImage(input) {
  if (!input.files[0]) return;
  var fd = new FormData();
  fd.append('action', 'upload_image');
  fd.append('img', input.files[0]);
  fetch('/qbl-portal.php?id=' + portalId, { method: 'POST', body: fd })
    .then(function(r) { return r.json(); })
    .then(function(data) {
      if (data.success) {
        var grid = document.getElementById('pickerGrid');
        var img = document.createElement('img');
        img.src = '/qblimages/' + data.file;
        img.className = 'picker-img'; img.dataset.file = data.file;
        img.onclick = function() { selectImage(data.file); };
        grid.appendChild(img);
        selectImage(data.file);
      }
    });
}
document.getElementById('pickerOverlay').addEventListener('click', function(e) { if (e.target === this) closePicker(); });

/* ── User/SMS modals ── */
function openModal(id) { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }
document.querySelectorAll('.modal-bg').forEach(function(m) {
  m.addEventListener('click', function(e) { if (e.target === m) m.classList.remove('open'); });
});

function syncSmsToMobile() {
  var mobile = document.getElementById('smsMobile').value.replace(/\D/g,'');
  if (mobile.length !== 10) { document.getElementById('smsSms').value = '0'; }
}
function syncMobileToSms(mobileId, smsId) {
  var mobile = document.getElementById(mobileId).value.replace(/\D/g,'');
  var sel = document.getElementById(smsId);
  if (mobile.length !== 10) { sel.value = '0'; sel.disabled = true; }
  else { sel.disabled = false; }
}
document.getElementById('smsMobile').addEventListener('input', syncSmsToMobile);
document.getElementById('createMobile').addEventListener('input', function() { syncMobileToSms('createMobile','createSms'); });
document.getElementById('existingMobile').addEventListener('input', function() { syncMobileToSms('existingMobile','existingSms'); });

function openCoverage(userId, userName) {
  document.getElementById('coverageUserId').value = userId;
  document.getElementById('coverageName').textContent = userName;
  // Hide the user being covered from the dropdown
  document.querySelectorAll('#coverCoverUserId option[data-uid]').forEach(function(opt) {
    opt.hidden = (parseInt(opt.dataset.uid) === parseInt(userId));
  });
  // Set defaults
  var today = new Date();
  var todayStr = today.getFullYear()+'-'+String(today.getMonth()+1).padStart(2,'0')+'-'+String(today.getDate()).padStart(2,'0');
  var nowHH = String(today.getHours()).padStart(2,'0')+':00';
  document.getElementById('coverCoverUserId').value = '0';
  document.getElementById('coverStartDate').value = todayStr;
  document.getElementById('coverStartTime').value = nowHH;
  document.getElementById('coverEndDate').value = '';
  document.getElementById('coverEndTime').value = '';
  document.getElementById('coverQblMin').value = '300';
  document.getElementById('coverPauseSms').checked = false;
  document.getElementById('coverRemoveBtn').style.display = 'none';
  // Fetch current coverage
  fetch('inc/data.php?req=getusercoverage', {
    method: 'POST',
    headers: {'Content-Type':'application/x-www-form-urlencoded'},
    body: 'userId='+userId
  }).then(function(r){return r.json();}).then(function(res){
    if (!res.success) { openModal('coverageModal'); return; }
    var d = res.data;
    if (d.coverUserId > 0) {
      document.getElementById('coverCoverUserId').value = d.coverUserId;
      document.getElementById('coverStartDate').value = (d.coverStartDate && d.coverStartDate !== '0000-00-00') ? d.coverStartDate : todayStr;
      document.getElementById('coverStartTime').value = (d.coverStartTime && d.coverStartTime !== '00:00') ? d.coverStartTime : nowHH;
      document.getElementById('coverEndDate').value = d.coverEndDate || '';
      document.getElementById('coverEndTime').value = d.coverEndTime || '';
      document.getElementById('coverQblMin').value = d.coverQblMin !== undefined ? d.coverQblMin : 300;
      document.getElementById('coverPauseSms').checked = d.coverPauseSms == 1;
      document.getElementById('coverRemoveBtn').style.display = 'inline-block';
    }
    openModal('coverageModal');
  }).catch(function(){ openModal('coverageModal'); });
}

function saveCoverage() {
  var coverUserId = document.getElementById('coverCoverUserId').value;
  var userId = document.getElementById('coverageUserId').value;
  var startDate = document.getElementById('coverStartDate').value;
  var startTime = document.getElementById('coverStartTime').value;
  var endDate = document.getElementById('coverEndDate').value;
  var endTime = document.getElementById('coverEndTime').value;
  var qblMin = document.getElementById('coverQblMin').value || '300';
  var varianceMin = '1';
  var pauseSms = document.getElementById('coverPauseSms').checked ? 1 : 0;

  if (!coverUserId || coverUserId == '0') { alert('Please select a covering user.'); return; }
  if (!startDate) { alert('Start date is required.'); return; }
  if (!endDate) { alert('End date is required.'); return; }

  var params = 'userId='+encodeURIComponent(userId)+'&coverUserId='+encodeURIComponent(coverUserId)
    +'&startDate='+encodeURIComponent(startDate)+'&startTime='+encodeURIComponent(startTime)
    +'&endDate='+encodeURIComponent(endDate)+'&endTime='+encodeURIComponent(endTime)
    +'&qblMin='+encodeURIComponent(qblMin)+'&varianceMin='+encodeURIComponent(varianceMin)+'&pauseSms='+pauseSms;

  fetch('inc/data.php?req=changeusercoverage', {
    method: 'POST',
    headers: {'Content-Type':'application/x-www-form-urlencoded'},
    body: params
  }).then(function(r){return r.json();}).then(function(res){
    if (res.success) { closeModal('coverageModal'); location.reload(); }
    else { alert(res.message || 'Error saving coverage.'); }
  });
}

function removeCoverage() {
  var userId = document.getElementById('coverageUserId').value;
  if (!confirm('Remove coverage for this user?')) return;
  fetch('inc/data.php?req=changeusercoverage', {
    method: 'POST',
    headers: {'Content-Type':'application/x-www-form-urlencoded'},
    body: 'userId='+encodeURIComponent(userId)+'&coverUserId=0'
  }).then(function(r){return r.json();}).then(function(res){
    closeModal('coverageModal'); location.reload();
  });
}

function openSmsEdit(u) {
  document.getElementById('smsUserId').value = u.id;
  document.getElementById('smsActive').value = u.active ? '1' : '0';
  document.getElementById('smsName').textContent = u.first_name + ' ' + u.last_name;
  document.getElementById('smsMobile').value = u.mobile || '';
  document.getElementById('smsThreshold').value = u.qblWarnMin > 0 ? u.qblWarnMin : '';
  document.getElementById('smsSms').value = u.escAlerts ? '1' : '0';
  openModal('smsModal');
}

function submitSmsEdit(btn) {
  var active = document.getElementById('smsActive').value;
  var mobile = document.getElementById('smsMobile').value.replace(/\D/g, '');
  var sms    = document.getElementById('smsSms').value;
  if (active === '0' && mobile.length === 10 && sms === '1') {
    if (!confirm('This user is inactive. Saving will reactivate their account and restore system access. Continue?')) return;
  }
  btn.closest('form').submit();
}

function prefillExisting(sel) {
  var opt = sel.options[sel.selectedIndex];
  document.getElementById('existingMobile').value = opt.dataset.mobile || '';
  document.getElementById('existingThreshold').value = opt.dataset.threshold > 0 ? opt.dataset.threshold : '';
  syncMobileToSms('existingMobile','existingSms');
}

function switchMode(mode, btn) {
  document.querySelectorAll('.mode-section').forEach(function(s) { s.classList.remove('active'); });
  document.querySelectorAll('.mode-btn').forEach(function(b) { b.classList.remove('active'); });
  document.getElementById('mode-' + mode).classList.add('active');
  btn.classList.add('active');
}

/* ── QR Code ── */
(function() {
  var el = document.getElementById('qrcode');
  if (!el) return;
  var url = <?php echo json_encode($qrCalcUrl); ?>;
  var autoprint = <?php echo (isset($_GET['newqr']) && $_GET['newqr'] === '1') ? 'true' : 'false'; ?>;
  var script = document.createElement('script');
  script.src = 'https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js';
  script.onload = function() {
    new QRCode(el, { text: url, width: 200, height: 200, correctLevel: QRCode.CorrectLevel.M });
    if (autoprint) { setTimeout(function() { window.print(); }, 600); }
  };
  document.head.appendChild(script);
})();

function printQR() { window.print(); }
function showRegenWarning() {
  var panel = document.getElementById('regenPanel');
  panel.classList.add('open');
  setTimeout(function() { panel.scrollIntoView({ behavior: 'smooth', block: 'start' }); }, 50);
}
function hideRegenWarning() { document.getElementById('regenPanel').classList.remove('open'); }
</script>
</body>
</html>
