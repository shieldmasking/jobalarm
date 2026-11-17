<?php
require_once __DIR__ . '/_auth.php';

$accountId = validate_export_token();
$recordId  = intval($_GET['id'] ?? 0);
if ($recordId < 1) { http_response_code(400); echo 'Invalid record ID.'; exit; }

$db = Config::get('db');

$r = $db->get_results("
    SELECT n.id, n.nvariance, n.atotal, n.note, n.dayDate,
        n.chargecount, n.antecount, n.customNurse, n.customNurse2,
        n.techcount, n.seccount, n.sittercount,
        n.otherNurse1, n.otherNurse2, n.otherNurse3,
        n.oneto1, n.oneto2, n.oneto3, n.oneto4, n.oneto5, n.oneto6,
        d.dept, d.roundrn, d.totalbeds,
        d.staff1, d.staff2, d.staff3, d.staff4, d.staff5, d.staff6, d.staff7,
        d.rnCount, d.rn1Count, d.rn2Count, d.countcharge,
        d.techLabel, d.secLabel, d.nurseDesc, d.nurse1Desc, d.nurse2Desc,
        d.sittersNEWDesc, d.other1Desc, d.other2Desc, d.other3Desc,
        d.desc1, d.desc2, d.desc3, d.desc4, d.desc5, d.desc6,
        s.shift as shiftName,
        DATE_FORMAT(n.entered, '%H:%i') as submitTime,
        TRIM(CONCAT(IFNULL(u.first_name,''),' ',IFNULL(u.last_name,''))) as chargeNurse
    FROM productiveNewData n
    JOIN ProductiveDept d ON d.id = n.deptId
    LEFT JOIN productiveShifts s ON s.id = n.shift
    LEFT JOIN productiveUser u ON u.id = n.userId
    WHERE n.id = {$recordId} AND n.accountId = {$accountId}
    LIMIT 1
");

if (!$r) { http_response_code(404); echo 'Record not found.'; exit; }
$r = $r[0];

// Staff rows
$roundrn  = intval($r['roundrn']);
$rawVar   = floatval($r['nvariance']);
if ($roundrn == 1)     $variance = round($rawVar, 0);
elseif ($roundrn == 2) $variance = floor($rawVar);
elseif ($roundrn == 3) $variance = ceil($rawVar);
else                   $variance = round($rawVar, 2);

$staffRows = [];
$staffRows[] = ['Charge', round($r['chargecount'] * $r['staff1'], 1)];
if (!empty($r['nurse1Desc']))     $staffRows[] = [$r['nurse1Desc'],     round($r['customNurse']  * $r['staff3'], 1)];
if (!empty($r['nurseDesc']))      $staffRows[] = [$r['nurseDesc'],       round($r['antecount']    * $r['staff2'], 1)];
if (!empty($r['nurse2Desc']))     $staffRows[] = [$r['nurse2Desc'],      round($r['customNurse2'] * $r['staff4'], 1)];
if (!empty($r['techLabel']))      $staffRows[] = [$r['techLabel'],       round($r['techcount']    * $r['staff5'], 1)];
if (!empty($r['secLabel']))       $staffRows[] = [$r['secLabel'],        round($r['seccount']     * $r['staff6'], 1)];
if (!empty($r['sittersNEWDesc'])) $staffRows[] = [$r['sittersNEWDesc'], round($r['sittercount']  * $r['staff7'], 1)];
if (!empty($r['other1Desc']))     $staffRows[] = [$r['other1Desc'],      round($r['otherNurse1'],  1)];
if (!empty($r['other2Desc']))     $staffRows[] = [$r['other2Desc'],      round($r['otherNurse2'],  1)];
if (!empty($r['other3Desc']))     $staffRows[] = [$r['other3Desc'],      round($r['otherNurse3'],  1)];
$staffRows = array_filter($staffRows, fn($row) => $row[1] > 0);

$varClass = $variance < 0 ? '#c0392b' : ($variance > 0 ? '#27ae60' : '#555');
$varSign  = $variance > 0 ? '+' : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Shift Detail — <?php echo htmlspecialchars($r['dept']); ?></title>
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f0f2f5; color: #222; max-width: 600px; margin: 0 auto; }
.header { background: #1a4a8a; color: #fff; padding: 16px; }
.header h1 { font-size: 1.1rem; font-weight: 700; }
.header p  { font-size: .82rem; opacity: .8; margin-top: 3px; }
.section-lbl { font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; color: #888; padding: 14px 16px 6px; }
.boxes { display: flex; flex-wrap: wrap; gap: 8px; padding: 0 16px 14px; }
.box { background: #fff; border-radius: 8px; padding: 10px 14px; min-width: 80px; max-width: 140px; flex: 0 0 auto; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
.box .val { font-size: 1.3rem; font-weight: 700; }
.box .lbl { font-size: .7rem; color: #888; margin-top: 2px; word-wrap: break-word; }
.note { margin: 0 16px 16px; background: #fff; border-radius: 8px; padding: 12px 14px; font-size: .85rem; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
.note span { font-weight: 600; color: #555; }
.footer { text-align: center; font-size: .72rem; color: #aaa; padding: 20px 0 30px; }
</style>
</head>
<body>
<div class="header">
    <h1><?php echo htmlspecialchars($r['dept']); ?> &mdash; <?php echo date('M j, Y', strtotime($r['dayDate'])); ?><?php if (!empty($r['shiftName'])) echo ' &middot; ' . htmlspecialchars($r['shiftName']) . ' shift'; ?></h1>
    <p><?php
        $meta = [];
        if (!empty($r['submitTime'])) $meta[] = 'Submitted ' . htmlspecialchars($r['submitTime']);
        if (!empty($r['chargeNurse'])) $meta[] = 'by ' . htmlspecialchars($r['chargeNurse']);
        echo $meta ? implode(' ', $meta) : 'ProductiveRN Shift Detail';
    ?></p>
</div>

<div class="section-lbl">Staff</div>
<div class="boxes">
    <div class="box"><div class="val" style="color:<?php echo $varClass; ?>"><?php echo $varSign . $variance; ?></div><div class="lbl">RN Variance</div></div>
    <?php foreach ($staffRows as $row): ?>
    <div class="box"><div class="val"><?php echo $row[1]; ?></div><div class="lbl"><?php echo htmlspecialchars($row[0]); ?></div></div>
    <?php endforeach; ?>
</div>

<div class="section-lbl">Patients</div>
<div class="boxes">
    <div class="box"><div class="val"><?php echo intval($r['atotal']); ?></div><div class="lbl">Total</div></div>
    <?php for ($i = 1; $i <= 6; $i++): ?>
        <?php if (!empty($r["desc{$i}"]) && intval($r["oneto{$i}"]) > 0): ?>
        <div class="box"><div class="val"><?php echo intval($r["oneto{$i}"]); ?></div><div class="lbl"><?php echo htmlspecialchars($r["desc{$i}"]); ?></div></div>
        <?php endif; ?>
    <?php endfor; ?>
</div>

<?php if (!empty($r['note'])): ?>
<div class="note"><span>Note:</span> <?php echo htmlspecialchars($r['note']); ?></div>
<?php endif; ?>

<div class="footer">ProductiveRN &mdash; <?php echo htmlspecialchars($r['dept']); ?></div>
</body>
</html>
