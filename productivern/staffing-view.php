<?php
define('DISPLAY_DEBUG', false);
require_once 'inc/class.db.php';
require_once 'inc/config.php';

$token = isset($_GET['t']) ? preg_replace('/[^a-f0-9]/', '', $_GET['t']) : '';
if (empty($token)) die('<p style="font-family:sans-serif;text-align:center;margin-top:40px;">Invalid link.</p>');

$db = Config::get('db');

$tokenRow = $db->get_results("SELECT * FROM staffing_tokens WHERE token = '{$token}'");
if (!$tokenRow) die('<p style="font-family:sans-serif;text-align:center;margin-top:40px;">Link not found.</p>');
$tok = $tokenRow[0];
if (strtotime($tok['expires_at']) < time()) die('<p style="font-family:sans-serif;text-align:center;margin-top:40px;">This link has expired.</p>');

$userId    = intval($tok['user_id']);
$accountId = intval($tok['account_id']);

$depts = $db->get_results("SELECT x.deptId, d.dept FROM productiveDeptXref x JOIN ProductiveDept d ON d.id = x.deptId WHERE x.userId = {$userId} AND d.active = 1 ORDER BY d.dept");
if (!$depts) die('<p style="font-family:sans-serif;text-align:center;margin-top:40px;">No departments found.</p>');

$reports = [];
foreach ($depts as $dept) {
    $deptId = intval($dept['deptId']);
    $rows = $db->get_results("
        SELECT n.id as dataId, n.nvariance, n.atotal, n.submittedby,
            n.chargecount, n.antecount, n.customNurse, n.customNurse2,
            n.techcount, n.seccount, n.sittercount,
            n.otherNurse1, n.otherNurse2, n.otherNurse3, n.note,
            n.oneto1, n.oneto2, n.oneto3, n.oneto4, n.oneto5, n.oneto6,
            d.totalbeds, d.dept, d.roundrn,
            d.desc1, d.desc2, d.desc3, d.desc4, d.desc5, d.desc6,
            d.staff1, d.staff2, d.staff3, d.staff4, d.staff5, d.staff6, d.staff7,
            d.rnCount, d.rn1Count, d.rn2Count, d.countcharge,
            d.techLabel, d.secLabel, d.nurseDesc, d.nurse1Desc, d.nurse2Desc,
            d.sittersNEWDesc, d.other1Desc, d.other2Desc, d.other3Desc,
            n.dayDate,
            s.shift as shiftName,
            IFNULL(e.id,0) as escId,
            IFNULL(ae.escalation,'') as escalationType,
            IFNULL(e.note,'') as escalationNote
        FROM productiveNewData n
        JOIN ProductiveDept d ON d.id = n.deptId
        LEFT JOIN productiveShifts s ON s.id = n.shift
        LEFT JOIN productiveEscalations e ON e.dataId = n.id AND e.active = 1
        LEFT JOIN productiveAcctEscalations ae ON ae.id = e.escalation
        WHERE n.deptId = {$deptId}
          AND n.userId > 0
          AND n.activeRecord > 0
          AND n.accountId = {$accountId}
        ORDER BY n.entered DESC
        LIMIT 1
    ");
    if ($rows) $reports[] = $rows[0];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Staffing Report</title>
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f0f2f5; color: #222; }
.header { background: #1a4a8a; color: #fff; padding: 16px; }
.header h1 { font-size: 1rem; font-weight: 700; }
.header p  { font-size: .8rem; opacity: .8; margin-top: 2px; }
.dept-card { margin: 12px; background: #fff; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,.1); overflow: hidden; }
.dept-name { background: #1a4a8a; padding: 12px 14px; }
.dept-name a { color: #fff; font-size: 1rem; font-weight: 700; text-decoration: none; }
.dept-name .shift { color: rgba(255,255,255,.7); font-size: .78rem; margin-top: 2px; }
.boxes { display: grid; grid-template-columns: 1fr 1fr; gap: 1px; background: #e8e8e8; }
.box { background: #fff; padding: 12px; text-align: center; }
.box .val { font-size: 1.5rem; font-weight: 800; line-height: 1; }
.box .lbl { font-size: .68rem; color: #888; text-transform: uppercase; letter-spacing: .5px; margin-top: 3px; }
.box.neg .val { color: #c0392b; }
.box.pos .val { color: #27ae60; }
.box.neutral .val { color: #2c3e50; }
.esc-row { padding: 10px 14px; background: #fff4f4; border-top: 1px solid #fcd; display: flex; align-items: center; gap: 8px; }
.esc-label { font-size: .75rem; font-weight: 700; color: #c0392b; text-transform: uppercase; }
.esc-type { font-size: .85rem; color: #333; }
.esc-note { font-size: .78rem; color: #666; margin-top: 2px; }
.no-esc { padding: 10px 14px; background: #f0fff4; border-top: 1px solid #c3e6cb; }
.no-esc span { font-size: .78rem; color: #27ae60; font-weight: 600; }
.staff-toggle { padding: 12px 14px; border-top: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; cursor: pointer; }
.staff-toggle span { font-size: .85rem; font-weight: 600; color: #1a4a8a; }
.staff-toggle .arrow { font-size: .75rem; color: #aaa; transition: transform .2s; }
.staff-toggle.open .arrow { transform: rotate(180deg); }
.staff-detail { display: none; padding: 0 14px 12px; border-top: 1px solid #eee; }
.staff-row { display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px solid #f5f5f5; }
.staff-row:last-child { border-bottom: none; }
.staff-row .sname { font-size: .88rem; color: #444; }
.staff-row .sval  { font-size: .88rem; font-weight: 700; color: #1a4a8a; }
.no-report { padding: 20px 14px; text-align: center; color: #aaa; font-size: .85rem; }
.footer { text-align: center; font-size: .72rem; color: #aaa; padding: 14px 0 30px; }
.login-link { text-align: center; padding: 10px 0 20px; }
.login-link a { color: #1a4a8a; font-size: .85rem; }
.section-lbl { padding: 8px 14px 4px; font-size: .7rem; font-weight: 700; color: #1a4a8a; text-transform: uppercase; letter-spacing: .6px; background: #fff; border-top: 1px solid #eee; }
.note-row { padding: 8px 14px; background: #fff; border-top: 1px solid #eee; font-size: .82rem; color: #444; }
.note-lbl { font-weight: 700; color: #555; }
</style>
</head>
<body>

<div class="header">
    <h1>Staffing Report — <?php echo date('M j, Y'); ?></h1>
    <p>Tap a unit name to log in</p>
</div>

<?php if (empty($reports)): ?>
<p style="text-align:center;padding:40px;color:#888;">No staffing reports submitted today.</p>
<?php else: ?>
<?php foreach ($reports as $r):
    $roundrn = intval($r['roundrn']);
    $rawVar  = floatval($r['nvariance']);
    if ($roundrn == 1)      $nvariance = round($rawVar, 0);
    elseif ($roundrn == 2)  $nvariance = floor($rawVar);
    elseif ($roundrn == 3)  $nvariance = ceil($rawVar);
    else                    $nvariance = round($rawVar, 2);
    $patients   = intval($r['atotal']);
    $totalBeds  = intval($r['totalbeds']);
    $openBeds   = $totalBeds - $patients;

    // RN Count: sum staff types flagged as RN
    $rnCount = 0;
    if (intval($r['countcharge']) == 1) $rnCount += round($r['chargecount'] * $r['staff1'], 1);
    if (intval($r['rnCount'])     == 1) $rnCount += round($r['antecount']   * $r['staff2'], 1);
    if (intval($r['rn1Count'])    == 1) $rnCount += round($r['customNurse'] * $r['staff3'], 1);
    if (intval($r['rn2Count'])    == 1) $rnCount += round($r['customNurse2']* $r['staff4'], 1);

    $varClass = $nvariance < 0 ? 'neg' : ($nvariance > 0 ? 'pos' : 'neutral');

    // Staff detail rows
    $staffRows = [];
    $staffRows[] = ['Charge', round($r['chargecount'] * $r['staff1'], 1)];
    if (!empty($r['nurse1Desc']))      $staffRows[] = [$r['nurse1Desc'],      round($r['customNurse']  * $r['staff3'], 1)];
    if (!empty($r['nurseDesc']))       $staffRows[] = [$r['nurseDesc'],        round($r['antecount']    * $r['staff2'], 1)];
    if (!empty($r['nurse2Desc']))      $staffRows[] = [$r['nurse2Desc'],       round($r['customNurse2'] * $r['staff4'], 1)];
    if (!empty($r['techLabel']))       $staffRows[] = [$r['techLabel'],        round($r['techcount']    * $r['staff5'], 1)];
    if (!empty($r['secLabel']))        $staffRows[] = [$r['secLabel'],         round($r['seccount']     * $r['staff6'], 1)];
    if (!empty($r['sittersNEWDesc'])) $staffRows[] = [$r['sittersNEWDesc'],   round($r['sittercount']  * $r['staff7'], 1)];
    if (!empty($r['other1Desc']))      $staffRows[] = [$r['other1Desc'],       round($r['otherNurse1'],  1)];
    if (!empty($r['other2Desc']))      $staffRows[] = [$r['other2Desc'],       round($r['otherNurse2'],  1)];
    if (!empty($r['other3Desc']))      $staffRows[] = [$r['other3Desc'],       round($r['otherNurse3'],  1)];
    $staffRows = array_filter($staffRows, fn($row) => $row[1] > 0);

    $hasEsc = intval($r['escId']) > 0;
    $cardId = 'card_' . $r['dataId'];
?>
<div class="dept-card">
    <div class="dept-name">
        <a href="https://www.productivern.com/login.php"><?php echo htmlspecialchars($r['dept']); ?></a>
        <div class="shift">
            <?php echo date('M j, Y', strtotime($r['dayDate'])); ?>
            <?php if (!empty($r['shiftName'])): ?>&nbsp;&middot;&nbsp;<?php echo htmlspecialchars($r['shiftName']); ?> shift<?php endif; ?>
        </div>
    </div>

    <div class="section-lbl">Staff</div>
    <div class="boxes">
        <div class="box <?php echo $varClass; ?>">
            <div class="val"><?php echo ($nvariance > 0 ? '+' : '') . $nvariance; ?></div>
            <div class="lbl">RN Variance</div>
        </div>
        <?php foreach ($staffRows as $srow): ?>
        <div class="box neutral">
            <div class="val"><?php echo $srow[1]; ?></div>
            <div class="lbl"><?php echo htmlspecialchars($srow[0]); ?></div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="section-lbl">Patients</div>
    <div class="boxes">
        <div class="box neutral">
            <div class="val"><?php echo $patients; ?></div>
            <div class="lbl">Total</div>
        </div>
        <?php for ($i = 1; $i <= 6; $i++): ?>
        <?php if (!empty($r["desc{$i}"]) && intval($r["oneto{$i}"]) > 0): ?>
        <div class="box neutral">
            <div class="val"><?php echo intval($r["oneto{$i}"]); ?></div>
            <div class="lbl"><?php echo htmlspecialchars($r["desc{$i}"]); ?></div>
        </div>
        <?php endif; ?>
        <?php endfor; ?>
    </div>

    <?php if ($hasEsc): ?>
    <div class="esc-row">
        <div>
            <div class="esc-label">&#9888; Escalation: <span class="esc-type"><?php echo htmlspecialchars($r['escalationType']); ?></span></div>
            <?php if (!empty($r['escalationNote'])): ?>
            <div class="esc-note"><?php echo htmlspecialchars($r['escalationNote']); ?></div>
            <?php endif; ?>
        </div>
    </div>
    <?php else: ?>
    <div class="no-esc"><span>&#10003; No Escalations</span></div>
    <?php endif; ?>

    <?php if (!empty($r['note'])): ?>
    <div class="note-row"><span class="note-lbl">Note:</span> <?php echo htmlspecialchars($r['note']); ?></div>
    <?php endif; ?>

</div>
<?php endforeach; ?>
<?php endif; ?>

<div class="footer">Expires <?php echo date('M j, Y g:i A', strtotime($tok['expires_at'])); ?></div>
<div class="login-link"><a href="https://www.productivern.com/login.php">Login to ProductiveRN</a></div>

<script>
function toggleStaff(id, el) {
    var detail = document.getElementById(id);
    var isOpen = detail.style.display === 'block';
    detail.style.display = isOpen ? 'none' : 'block';
    el.classList.toggle('open', !isOpen);
}
</script>
</body>
</html>
