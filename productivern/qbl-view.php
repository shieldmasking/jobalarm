<?php
define('DISPLAY_DEBUG', false);
require_once 'inc/class.db.php';
require_once 'inc/config.php';

$token = isset($_GET['t']) ? preg_replace('/[^a-f0-9]/', '', $_GET['t']) : '';

if (empty($token)) {
    die('<p style="font-family:sans-serif;text-align:center;margin-top:40px;">Invalid link.</p>');
}

$db = Config::get('db');

$row = $db->get_results("SELECT qt.qbl_id, qt.expires_at,
    q.totalQBL, q.totalWt, q.dateTime, q.note,
    q.v1,q.v2,q.v3,q.v4,q.v5,q.v6,q.v7,q.v8,q.v9,q.v10,q.v11,q.v12,q.v13,q.v14,q.v15,
    c.desc1,c.desc2,c.desc3,c.desc4,c.desc5,c.desc6,c.desc7,c.desc8,c.desc9,c.desc10,
    c.desc11,c.desc12,c.desc13,c.desc14,c.desc15,
    c.value1,c.value2,c.value3,c.value4,c.value5,c.value6,c.value7,c.value8,c.value9,c.value10,
    c.value11,c.value12,c.value13,c.value14,c.value15,
    d.dept as deptName
    FROM qbl_tokens qt
    JOIN productiveQBLlog q ON q.id = qt.qbl_id
    JOIN productiveQBLconfig c ON c.id = q.locId
    JOIN ProductiveDept d ON d.id = q.deptId
    WHERE qt.token = '" . $token . "'");

if (!$row) {
    die('<p style="font-family:sans-serif;text-align:center;margin-top:40px;">Report not found.</p>');
}

$data = $row[0];

if (strtotime($data['expires_at']) < time()) {
    die('<p style="font-family:sans-serif;text-align:center;margin-top:40px;">This link has expired.</p>');
}

$totalQBL = round(floatval($data['totalQBL']), 0);
$totalWt  = round(floatval($data['totalWt']), 0);
$pct      = $totalWt > 0 ? round(($totalQBL / $totalWt) * 100, 1) : 0;
$dateStr  = date('M j, Y g:i A', strtotime($data['dateTime']));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>QBL Report — <?php echo htmlspecialchars($data['deptName']); ?></title>
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f0f2f5; color: #222; }
.header { background: #c0392b; color: #fff; padding: 18px 16px 14px; }
.header h1 { font-size: 1.1rem; font-weight: 700; letter-spacing: .5px; }
.header p  { font-size: .85rem; opacity: .85; margin-top: 2px; }
.totals { display: flex; gap: 10px; padding: 14px 12px; }
.total-card { flex: 1; background: #fff; border-radius: 10px; padding: 14px 10px; text-align: center; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
.total-card .val { font-size: 1.7rem; font-weight: 800; color: #c0392b; line-height: 1; }
.total-card .lbl { font-size: .72rem; color: #666; margin-top: 4px; text-transform: uppercase; letter-spacing: .5px; }
.section-title { font-size: .75rem; font-weight: 700; color: #888; text-transform: uppercase; letter-spacing: .8px; padding: 0 14px 6px; }
.items { padding: 0 12px 20px; display: flex; flex-direction: column; gap: 8px; }
.item { background: #fff; border-radius: 10px; padding: 12px 14px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 1px 3px rgba(0,0,0,.07); }
.item .name { font-size: .95rem; color: #333; }
.item .amount { font-size: 1rem; font-weight: 700; color: #c0392b; white-space: nowrap; margin-left: 10px; }
.note-box { margin: 0 12px 20px; background: #fff; border-radius: 10px; padding: 12px 14px; box-shadow: 0 1px 3px rgba(0,0,0,.07); }
.note-box .note-label { font-size: .72rem; color: #888; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 4px; }
.note-box p { font-size: .95rem; color: #333; }
.footer { text-align: center; font-size: .75rem; color: #aaa; padding: 10px 0 30px; }
</style>
</head>
<body>

<div class="header">
    <h1>QBL Report — <?php echo htmlspecialchars($data['deptName']); ?></h1>
    <p><?php echo $dateStr; ?></p>
</div>

<div class="totals">
    <div class="total-card">
        <div class="val"><?php echo $totalQBL; ?>ml</div>
        <div class="lbl">Total QBL</div>
    </div>
    <div class="total-card">
        <div class="val"><?php echo $totalWt; ?>g</div>
        <div class="lbl">Total Weight</div>
    </div>
    <div class="total-card">
        <div class="val"><?php echo $pct; ?>%</div>
        <div class="lbl">QBL / Wt</div>
    </div>
</div>

<div class="section-title">Measurements</div>
<div class="items">
<?php for ($i = 1; $i <= 15; $i++):
    $desc = $data["desc$i"] ?? '';
    $val  = floatval($data["v$i"] ?? 0);
    $cfg  = floatval($data["value$i"] ?? 0);
    if (empty($desc) || $cfg == 0) continue;
    $ml = round($val * $cfg, 0);
    if ($ml <= 0) continue;
?>
    <div class="item">
        <span class="name"><?php echo htmlspecialchars($desc); ?></span>
        <span class="amount"><?php echo $ml; ?>ml</span>
    </div>
<?php endfor; ?>
</div>

<?php if (!empty($data['note'])): ?>
<div class="note-box">
    <div class="note-label">Note</div>
    <p><?php echo htmlspecialchars($data['note']); ?></p>
</div>
<?php endif; ?>

<div class="footer">This link expires <?php echo date('M j, Y g:i A', strtotime($data['expires_at'])); ?></div>
<div class="footer" style="padding-bottom:40px;"><a href="https://www.productivern.com/login.php" style="color:#c0392b;">Login to ProductiveRN</a></div>

</body>
</html>
