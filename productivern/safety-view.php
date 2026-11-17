<?php
require_once 'inc/class.db.php';
require_once 'inc/config.php';

$token = isset($_GET['t']) ? preg_replace('/[^a-f0-9]/', '', $_GET['t']) : '';
if (empty($token)) die('<p style="font-family:sans-serif;text-align:center;margin-top:40px;">Invalid link.</p>');

$db = Config::get('db');

$tokenRow = $db->get_results("SELECT * FROM safety_tokens WHERE token = '{$token}'");
if (!$tokenRow) die('<p style="font-family:sans-serif;text-align:center;margin-top:40px;">Link not found.</p>');
$tok = $tokenRow[0];
if (strtotime($tok['expires_at']) < time()) die('<p style="font-family:sans-serif;text-align:center;margin-top:40px;">This link has expired.</p>');

$safetyId  = intval($tok['safety_id']);
$accountId = intval($tok['account_id']);

$row = $db->get_results("
    SELECT s.*,
        COALESCE(d.dept, 'Hospital') as deptName,
        CONCAT(t.typeDesc, ' - ', c.safetyDesc) as issueType
    FROM productiveSafety s
    LEFT JOIN ProductiveDept d ON d.id = s.deptId
    LEFT JOIN productivesafetyConfig c ON c.id = s.safetyConfig
    LEFT JOIN productivesafetyType t ON t.id = c.safetyType
    WHERE s.id = {$safetyId} AND s.accountId = {$accountId}
");
if (!$row) die('<p style="font-family:sans-serif;text-align:center;margin-top:40px;">Record not found.</p>');
$r = $row[0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Safety Issue</title>
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f0f2f5; color: #222; }
.header { background: #1a4a8a; color: #fff; padding: 16px; }
.header h1 { font-size: 1rem; font-weight: 700; }
.header p  { font-size: .8rem; opacity: .8; margin-top: 2px; }
.card { margin: 12px; background: #fff; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,.1); overflow: hidden; }
.card-header { background: #1a4a8a; padding: 12px 14px; color: #fff; font-size: 1rem; font-weight: 700; }
.priority-badge { display: inline-block; background: #e74c3c; color: #fff; font-size: .7rem; font-weight: 700; padding: 2px 8px; border-radius: 10px; margin-left: 8px; vertical-align: middle; text-transform: uppercase; }
.field { padding: 12px 14px; border-bottom: 1px solid #f0f0f0; }
.field:last-child { border-bottom: none; }
.field .lbl { font-size: .7rem; font-weight: 700; color: #888; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 3px; }
.field .val { font-size: .95rem; color: #222; }
.field .val.empty { color: #bbb; font-style: italic; }
.footer { text-align: center; font-size: .72rem; color: #aaa; padding: 14px 0 30px; }
.login-link { text-align: center; padding: 10px 0 20px; }
.login-link a { color: #1a4a8a; font-size: .85rem; }
</style>
</head>
<body>

<div class="header">
    <h1>Safety Issue Report</h1>
    <p><?php echo date('M j, Y', strtotime($r['openedDate'])); ?></p>
</div>

<div class="card">
    <div class="card-header">
        <?php echo htmlspecialchars($r['deptName']); ?>
        <?php if (intval($r['priority']) == 1): ?><span class="priority-badge">High Priority</span><?php endif; ?>
    </div>

    <div class="field">
        <div class="lbl">Issue Type</div>
        <div class="val <?php echo empty($r['issueType']) ? 'empty' : ''; ?>">
            <?php echo !empty($r['issueType']) ? htmlspecialchars($r['issueType']) : 'Not specified'; ?>
        </div>
    </div>

    <?php if (!empty($r['roomId1'])): ?>
    <div class="field">
        <div class="lbl">Room / Location</div>
        <div class="val"><?php echo htmlspecialchars($r['roomId1']); ?></div>
    </div>
    <?php endif; ?>

    <div class="field">
        <div class="lbl">Note</div>
        <div class="val <?php echo empty($r['note1']) ? 'empty' : ''; ?>">
            <?php echo !empty($r['note1']) ? nl2br(htmlspecialchars($r['note1'])) : 'No note provided'; ?>
        </div>
    </div>

    <?php if (!empty($r['periGen1'])): ?>
    <div class="field">
        <div class="lbl">Submitted By</div>
        <div class="val"><?php echo htmlspecialchars($r['periGen1']); ?></div>
    </div>
    <?php endif; ?>

    <?php if (!empty($r['dueDate']) && $r['dueDate'] !== '0000-00-00'): ?>
    <div class="field">
        <div class="lbl">Due Date</div>
        <div class="val"><?php echo date('M j, Y', strtotime($r['dueDate'])); ?></div>
    </div>
    <?php endif; ?>
</div>

<div class="footer">Expires <?php echo date('M j, Y g:i A', strtotime($tok['expires_at'])); ?></div>
<div class="login-link"><a href="https://www.productivern.com/login.php">Login to ProductiveRN</a></div>

</body>
</html>
