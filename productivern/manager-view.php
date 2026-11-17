<?php
define('DISPLAY_DEBUG', false);
require_once 'inc/class.db.php';
require_once 'inc/config.php';

$token = isset($_GET['t']) ? preg_replace('/[^a-f0-9]/', '', $_GET['t']) : '';
if (empty($token)) die('<p style="font-family:sans-serif;text-align:center;margin-top:40px;">Invalid link.</p>');

$db = Config::get('db');

$tokenRow = $db->get_results("SELECT * FROM staffing_tokens WHERE token = '{$token}'");
if (!$tokenRow) $tokenRow = $db->get_results("SELECT * FROM manager_tokens WHERE token = '{$token}'");
if (!$tokenRow) die('<p style="font-family:sans-serif;text-align:center;margin-top:40px;">Link not found.</p>');
$tok = $tokenRow[0];
if (strtotime($tok['expires_at']) < time()) die('
<!DOCTYPE html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Link Expired</title>
<style>
body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; background: #f0f2f5; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
.box { text-align: center; padding: 40px 32px; background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); max-width: 320px; width: 90%; }
.icon { font-size: 2.5rem; margin-bottom: 12px; }
h1 { font-size: 1.5rem; color: #1a2332; margin-bottom: 8px; }
p { font-size: 0.9rem; color: #6b7f96; margin-bottom: 24px; line-height: 1.5; }
a { display: block; background: #1a4a8a; color: #fff; text-decoration: none; padding: 12px; border-radius: 8px; font-size: 0.95rem; font-weight: 600; }
a:hover { background: #163d73; }
</style></head>
<body><div class="box">
<div class="icon">🔒</div>
<h1>This Link is Expired</h1>
<p>This link is no longer valid. Please log in to access your dashboard.</p>
<a href="https://www.productivern.com/login.php">Log In to ProductiveRN</a>
</div></body></html>');

$userId    = intval($tok['user_id']);
$accountId = intval($tok['account_id']);
$context   = $tok['context'] ?? 'staffing';

// Generate a fresh close token each time the page is loaded (4-hour window)
$closeToken = bin2hex(random_bytes(32));
$closeExpires = date('Y-m-d H:i:s', strtotime('+4 hours'));
$db->insert('manager_tokens', array('token' => $closeToken, 'user_id' => $userId, 'account_id' => $accountId, 'context' => 'safety-close', 'ref_id' => 0, 'expires_at' => $closeExpires));

// Determine section order
if ($context === 'qbl') {
    $sectionOrder = ['qbl', 'staffing', 'escalations', 'safety'];
} elseif ($context === 'safety') {
    $sectionOrder = ['safety', 'escalations', 'staffing', 'qbl'];
} else {
    $sectionOrder = ['escalations', 'safety', 'staffing', 'qbl'];
}

// 1. User's departments
$depts = $db->get_results("SELECT x.deptId, d.dept FROM productiveDeptXref x JOIN ProductiveDept d ON d.id=x.deptId WHERE x.userId={$userId} AND d.active=1 ORDER BY d.dept");

$deptIds = [];
if ($depts) {
    foreach ($depts as $d) {
        $deptIds[] = intval($d['deptId']);
    }
}
$deptIdList = implode(',', $deptIds ?: [0]);

// 2. Staffing reports — most recent per dept, within last 24 hours
$staffReports = [];
if ($depts) {
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
                n.dayDate, n.deptId, n.entered,
                n.shift as shiftId,
                s.shift as shiftName,
                IFNULL(s.shiftTime,'') as shiftTime,
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
        if ($rows && strtotime($rows[0]['entered']) >= strtotime('-24 hours')) {
            $staffReports[] = $rows[0];
        } else {
            $staffReports[] = ['deptId' => $deptId, 'dept' => $dept['dept'], 'noReport' => true, 'shiftId' => 0];
        }
    }
}

// Set timezone from account for accurate late detection
$tzRow = $db->get_results("SELECT timeZone FROM productiveAccount WHERE id = {$accountId} LIMIT 1");
if ($tzRow && !empty($tzRow[0]['timeZone'])) {
    @date_default_timezone_set($tzRow[0]['timeZone']);
}

// Calculate late status for each staffing report
if ($staffReports) {
    $shiftsByDept = [];
    foreach ($staffReports as $r) {
        $dId = intval($r['deptId'] ?? 0);
        if (!$dId || isset($shiftsByDept[$dId])) continue;
        $srows = $db->get_results("
            SELECT s.shiftTime, s.shift as shiftName, x.shift as shiftId
            FROM productiveAccountXref x
            JOIN productiveShifts s ON s.id = x.shift
            WHERE x.dept = {$dId} AND x.active = 1
            ORDER BY x.shiftOrder ASC
        ");
        $shiftsByDept[$dId] = $srows ? array_map(fn($s) => ['id' => $s['shiftId'], 'time' => $s['shiftTime'], 'name' => $s['shiftName']], $srows) : [];
    }
    $nowRow   = $db->get_results("SELECT DATE_FORMAT(NOW(),'%Y-%m-%d') as today, DATE_FORMAT(NOW(),'%H:%i:%s') as nowTime");
    $todayStr = $nowRow[0]['today'];
    $nowTime  = $nowRow[0]['nowTime'];
    foreach ($staffReports as &$r) {
        $r['isLate'] = false;
        $dId = intval($r['deptId'] ?? 0);
        if (!empty($r['noReport'])) {
            // Find the most recently started shift today (reverse scan), fallback to yesterday's last
            if ($dId && !empty($shiftsByDept[$dId])) {
                $shifts = $shiftsByDept[$dId];
                $numShifts = count($shifts);
                $missed = $shifts[$numShifts - 1]['name']; // default: yesterday's last
                for ($i = $numShifts - 1; $i >= 0; $i--) {
                    if ($nowTime > $shifts[$i]['time']) { $missed = $shifts[$i]['name']; break; }
                }
                $r['isLate'] = $missed;
            }
            continue;
        }
        $curShiftId = intval($r['shiftId'] ?? 0);
        if (!$dId || !$curShiftId || empty($shiftsByDept[$dId])) continue;
        $shifts = $shiftsByDept[$dId];
        $idx = array_search($curShiftId, array_column($shifts, 'id'));
        if ($idx === false) continue;
        $numShifts = count($shifts);
        $checkIdx  = ($idx + 1) % $numShifts;
        $checkDate = ($idx + 1 >= $numShifts)
            ? date('Y-m-d', strtotime($r['dayDate'] . ' +1 day'))
            : $r['dayDate'];
        $mostRecentMissed = false;
        for ($step = 0; $step < $numShifts * 7; $step++) {
            if ($checkDate > $todayStr) break;
            $shift = $shifts[$checkIdx];
            $isPast = ($checkDate < $todayStr)
                   || ($checkDate === $todayStr && $nowTime > $shift['time']);
            if (!$isPast) break;
            $mostRecentMissed = $shift['name'];
            $nextCheckIdx = ($checkIdx + 1) % $numShifts;
            if ($nextCheckIdx === 0) {
                $checkDate = date('Y-m-d', strtotime($checkDate . ' +1 day'));
            }
            $checkIdx = $nextCheckIdx;
        }
        $r['isLate'] = $mostRecentMissed;
    }
    unset($r);
}

// 3. QBL reports — last 24 hours for user's depts
$qblReports = [];
if ($deptIds) {
    $qblClosedCol = $db->get_results("SHOW COLUMNS FROM productiveQBLlog LIKE 'managerClosed'");
    $qblClosedFilter = $qblClosedCol ? "AND q.managerClosed = 0" : "";
    $qblReports = $db->get_results("
        SELECT q.id, q.totalQBL, q.cum_qbl, q.nurse_name, q.session_id, q.totalWt, q.dateTime, q.note, q.deptId,
            q.v1,q.v2,q.v3,q.v4,q.v5,q.v6,q.v7,q.v8,q.v9,q.v10,q.v11,q.v12,q.v13,q.v14,q.v15,
            c.desc1,c.desc2,c.desc3,c.desc4,c.desc5,c.desc6,c.desc7,c.desc8,c.desc9,c.desc10,c.desc11,c.desc12,c.desc13,c.desc14,c.desc15,
            c.value1,c.value2,c.value3,c.value4,c.value5,c.value6,c.value7,c.value8,c.value9,c.value10,c.value11,c.value12,c.value13,c.value14,c.value15,
            d.dept as deptName
        FROM productiveQBLlog q
        JOIN productiveQBLconfig c ON c.id = q.locId
        JOIN ProductiveDept d ON d.id = q.deptId
        WHERE q.deptId IN ({$deptIdList})
        AND q.dateTime >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
        AND q.totalWt > 0
        {$qblClosedFilter}
        ORDER BY q.dateTime DESC
    ") ?: [];
}

// 4. Open escalations — all active for user's depts
$escalations = $db->get_results("
    SELECT e.id, e.deptId, e.note, e.dateSubmitted,
        IFNULL(d.dept,'') as deptName,
        IFNULL(a.escalation,'') as escalationType
    FROM productiveEscalations e
    LEFT JOIN ProductiveDept d ON d.id = e.deptId
    LEFT JOIN productiveAcctEscalations a ON a.id = e.escalation
    WHERE e.active = 1 AND e.accountId = {$accountId} AND e.deptId IN ({$deptIdList})
    ORDER BY d.dept ASC, e.dateSubmitted DESC
") ?: [];

// 5. Safety issues — all open for user's depts
$safetyIssues = $db->get_results("
    SELECT s.id, s.deptId, s.note1, s.periGen1, s.priority, s.openedDate, s.roomId1, s.dueDate,
        COALESCE(d.dept,'Hospital') as deptName,
        CONCAT(IFNULL(t.typeDesc,''), ' - ', IFNULL(c.safetyDesc,'')) as issueType
    FROM productiveSafety s
    LEFT JOIN ProductiveDept d ON d.id = s.deptId
    LEFT JOIN productivesafetyConfig c ON c.id = s.safetyConfig
    LEFT JOIN productivesafetyType t ON t.id = c.safetyType
    WHERE s.active = 1 AND s.accountId = {$accountId} AND s.deptId IN ({$deptIdList})
    ORDER BY s.priority DESC, s.openedDate DESC
") ?: [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Manager View</title>
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f0f2f5; color: #222; }
.header { background: #1a4a8a; color: #fff; padding: 16px; }
.header h1 { font-size: 1rem; font-weight: 700; }
.header p  { font-size: .8rem; opacity: .8; margin-top: 2px; }
/* Section headers */
.section-header { background: #1a4a8a; color: #fff; margin: 16px 12px 0; padding: 10px 14px; border-radius: 10px 10px 0 0; font-size: .85rem; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; }
.section-header-qbl    { background: #0e6b6b; }
.section-header-esc    { background: #8b1a1a; }
.section-header-safety { background: #7c4a00; }
.section-none { margin: 0 12px 16px; background: #fff; border-radius: 0 0 10px 10px; padding: 14px; text-align: center; font-size: .82rem; color: #aaa; border-top: 1px solid #eee; }
/* Staffing cards */
.dept-card { margin: 0 12px 4px; background: #fff; border-radius: 0; box-shadow: none; overflow: hidden; border-top: 1px solid #eee; }
.dept-card:last-of-type { border-radius: 0 0 10px 10px; margin-bottom: 16px; }
.dept-name { background: #2a5aa0; padding: 10px 14px; }
.dept-name a { color: #fff; font-size: .95rem; font-weight: 700; text-decoration: none; }
.dept-name .shift { color: rgba(255,255,255,.7); font-size: .75rem; margin-top: 2px; }
.boxes { display: grid; grid-template-columns: 1fr 1fr; gap: 1px; background: #e8e8e8; }
.box { background: #fff; padding: 10px; text-align: center; }
.box .val { font-size: 1.4rem; font-weight: 800; line-height: 1; }
.box .lbl { font-size: .65rem; color: #888; text-transform: uppercase; letter-spacing: .5px; margin-top: 3px; }
.box.neg .val { color: #c0392b; }
.box.pos .val { color: #27ae60; }
.box.neutral .val { color: #2c3e50; }
.esc-row { padding: 9px 14px; background: #fff4f4; border-top: 1px solid #fcd; display: flex; align-items: center; gap: 8px; }
.esc-label { font-size: .72rem; font-weight: 700; color: #c0392b; text-transform: uppercase; }
.esc-type { font-size: .82rem; color: #333; }
.esc-note { font-size: .75rem; color: #666; margin-top: 2px; }
.no-esc { padding: 9px 14px; background: #f0fff4; border-top: 1px solid #c3e6cb; }
.no-esc span { font-size: .75rem; color: #27ae60; font-weight: 600; }
.note-row { padding: 8px 14px; background: #fff; border-top: 1px solid #eee; font-size: .8rem; color: #444; }
.note-lbl { font-weight: 700; color: #555; }
.section-lbl { padding: 7px 14px 3px; font-size: .67rem; font-weight: 700; color: #1a4a8a; text-transform: uppercase; letter-spacing: .6px; background: #fff; border-top: 1px solid #eee; }
/* QBL cards */
.qbl-card { margin: 0 12px 4px; background: #fff; overflow: hidden; border-top: 1px solid #eee; }
.qbl-card:last-of-type { border-radius: 0 0 10px 10px; margin-bottom: 16px; }
.qbl-header { background: #117a7a; padding: 10px 14px; }
.qbl-header .qbl-dept { color: #fff; font-size: .95rem; font-weight: 700; }
.qbl-header .qbl-time { color: rgba(255,255,255,.7); font-size: .75rem; margin-top: 2px; }
.qbl-totals { display: flex; gap: 1px; background: #e8e8e8; }
.qbl-total { flex: 1; background: #fff; padding: 10px; text-align: center; }
.qbl-total .val { font-size: 1.4rem; font-weight: 800; line-height: 1; color: #2c3e50; }
.qbl-total .val.red { color: #c0392b; }
.qbl-total .val.orange { color: #e67e22; }
.qbl-total .lbl { font-size: .65rem; color: #888; text-transform: uppercase; letter-spacing: .5px; margin-top: 3px; }
.qbl-items { padding: 4px 0; }
.qbl-item { display: flex; justify-content: space-between; padding: 6px 14px; border-bottom: 1px solid #f5f5f5; font-size: .82rem; }
.qbl-item:last-child { border-bottom: none; }
.qbl-item .iname { color: #444; }
.qbl-item .iamt { font-weight: 700; color: #c0392b; }
.qbl-note { padding: 8px 14px; border-top: 1px solid #eee; font-size: .8rem; color: #444; }
.qbl-hemorrhage { padding: 9px 14px; background: #fff0f0; border-top: 1px solid #fcd; font-size: .82rem; font-weight: 700; color: #c0392b; }
/* Safety cards */
.safety-card { margin: 0 12px 4px; background: #fff; overflow: hidden; border-top: 1px solid #eee; }
.safety-card:last-of-type { border-radius: 0 0 10px 10px; margin-bottom: 16px; }
.safety-card-header { background: #944f00; padding: 10px 14px; color: #fff; font-size: .95rem; font-weight: 700; }
.esc-card-header    { background: #a32020; padding: 10px 14px; color: #fff; font-size: .95rem; font-weight: 700; }
.priority-badge { display: inline-block; background: #e74c3c; color: #fff; font-size: .68rem; font-weight: 700; padding: 2px 7px; border-radius: 10px; margin-left: 8px; vertical-align: middle; text-transform: uppercase; }
.late-badge { display: inline-block; background: #e67e22; color: #fff; font-size: .68rem; font-weight: 700; padding: 2px 7px; border-radius: 10px; vertical-align: middle; text-transform: uppercase; float: right; }
.field { padding: 9px 14px; border-bottom: 1px solid #f0f0f0; }
.field:last-child { border-bottom: none; }
.field .lbl { font-size: .68rem; font-weight: 700; color: #888; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 3px; }
.field .val { font-size: .9rem; color: #222; }
.field .val.empty { color: #bbb; font-style: italic; }
.close-btn { display: block; width: calc(100% - 28px); margin: 10px 14px 12px; padding: 11px; background: #c0392b; color: #fff; border: none; border-radius: 8px; font-size: .9rem; font-weight: 700; cursor: pointer; text-align: center; }
.close-btn:active { background: #a93226; }
/* Footer */
.footer { text-align: center; font-size: .72rem; color: #aaa; padding: 14px 0 10px; }
.login-link { text-align: center; padding: 6px 0 30px; }
.login-link a { color: #1a4a8a; font-size: .85rem; }
</style>
</head>
<body>

<div class="header">
    <h1>Manager View &mdash; <?php echo date('M j, Y'); ?></h1>
    <p>ProductiveRN</p>
</div>

<?php
// Helper to render staffing section
function renderStaffing($staffReports) {
    echo '<div class="section-header">Staffing</div>';
    if (empty($staffReports)) {
        echo '<div class="section-none">None</div>';
        return;
    }
    $lastIdx = count($staffReports) - 1;
    foreach ($staffReports as $idx => $r):
        $roundrn = intval($r['roundrn']);
        $rawVar  = floatval($r['nvariance']);
        if ($roundrn == 1)      $nvariance = round($rawVar, 0);
        elseif ($roundrn == 2)  $nvariance = floor($rawVar);
        elseif ($roundrn == 3)  $nvariance = ceil($rawVar);
        else                    $nvariance = round($rawVar, 2);
        $patients  = intval($r['atotal']);
        $totalBeds = intval($r['totalbeds']);

        $rnCount = 0;
        if (intval($r['countcharge']) == 1) $rnCount += round($r['chargecount'] * $r['staff1'], 1);
        if (intval($r['rnCount'])     == 1) $rnCount += round($r['antecount']   * $r['staff2'], 1);
        if (intval($r['rn1Count'])    == 1) $rnCount += round($r['customNurse'] * $r['staff3'], 1);
        if (intval($r['rn2Count'])    == 1) $rnCount += round($r['customNurse2']* $r['staff4'], 1);

        $varClass = $nvariance < 0 ? 'neg' : ($nvariance > 0 ? 'pos' : 'neutral');

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

        $isLast = ($idx === $lastIdx);
        $cardClass = 'dept-card' . ($isLast ? ' last-card' : '');
        echo '<div class="' . $cardClass . '">';
        echo '<div class="dept-name">';
        echo '<a href="https://www.productivern.com/login.php">' . htmlspecialchars($r['dept']) . '</a>';
        if (!empty($r['isLate'])) echo '<span class="late-badge">' . htmlspecialchars($r['isLate']) . ' is Late</span>';
        if (!empty($r['noReport'])) {
            echo '<div class="shift">No report in the last 24 hours</div></div>';
            echo '<div class="note-row" style="color:#aaa;font-style:italic;">No report available</div>';
            echo '</div>';
            continue;
        }
        echo '<div class="shift">' . date('M j, Y', strtotime($r['dayDate']));
        if (!empty($r['shiftName'])) echo ' &middot; ' . htmlspecialchars($r['shiftName']) . ' shift';
        echo '</div></div>';

        echo '<div class="section-lbl">Staff</div>';
        echo '<div class="boxes">';
        echo '<div class="box ' . $varClass . '"><div class="val">' . ($nvariance > 0 ? '+' : '') . $nvariance . '</div><div class="lbl">RN Variance</div></div>';
        foreach ($staffRows as $srow) {
            echo '<div class="box neutral"><div class="val">' . $srow[1] . '</div><div class="lbl">' . htmlspecialchars($srow[0]) . '</div></div>';
        }
        echo '</div>';

        echo '<div class="section-lbl">Patients</div>';
        echo '<div class="boxes">';
        echo '<div class="box neutral"><div class="val">' . $patients . '</div><div class="lbl">Total</div></div>';
        for ($i = 1; $i <= 6; $i++) {
            if (!empty($r["desc{$i}"]) && intval($r["oneto{$i}"]) > 0) {
                echo '<div class="box neutral"><div class="val">' . intval($r["oneto{$i}"]) . '</div><div class="lbl">' . htmlspecialchars($r["desc{$i}"]) . '</div></div>';
            }
        }
        echo '</div>';


        if (!empty($r['note'])) {
            echo '<div class="note-row"><span class="note-lbl">Note:</span> ' . htmlspecialchars($r['note']) . '</div>';
        }

        echo '</div>';
    endforeach;
}

function renderQBL($qblReports, $context = '') {
    $label = $context === 'qbl' ? 'QBL Report' : 'QBL Reports (Last 24 Hours)';
    echo '<div class="section-header section-header-qbl">' . $label . '</div>';
    if (empty($qblReports)) {
        echo '<div class="section-none">None</div>';
        return;
    }
    $lastIdx = count($qblReports) - 1;
    foreach ($qblReports as $idx => $q):
        $totalQBL = round(floatval($q['totalQBL']), 0);
        $cumQbl   = $q['cum_qbl'] !== null ? round(floatval($q['cum_qbl']), 0) : $totalQBL;
        $isCum    = $cumQbl > $totalQBL;
        $totalWt  = round(floatval($q['totalWt']), 0);
        $pct      = $totalWt > 0 ? round(($totalQBL / $totalWt) * 100, 1) : 0;
        $qblClass = $cumQbl >= 1000 ? 'red' : ($cumQbl >= 500 ? 'orange' : '');
        $isLast = ($idx === $lastIdx);
        echo '<div class="qbl-card' . ($isLast ? ' last-card' : '') . '" id="qbl-' . intval($q['id']) . '">';
        echo '<div class="qbl-header">';
        echo '<div class="qbl-dept">' . htmlspecialchars($q['deptName']) . '</div>';
        echo '<div class="qbl-time">' . date('M j, Y g:i A', strtotime($q['dateTime']));
        if (!empty($q['nurse_name'])) echo ' &middot; ' . htmlspecialchars($q['nurse_name']);
        echo '</div>';
        echo '</div>';
        echo '<div class="qbl-totals">';
        if ($isCum) {
            echo '<div class="qbl-total"><div class="val ' . $qblClass . '">' . $cumQbl . 'ml</div><div class="lbl">Cumulative QBL</div></div>';
            echo '<div class="qbl-total"><div class="val">' . $totalQBL . 'ml</div><div class="lbl">This Entry</div></div>';
        } else {
            echo '<div class="qbl-total"><div class="val ' . $qblClass . '">' . $totalQBL . 'ml</div><div class="lbl">Total QBL</div></div>';
        }
        echo '<div class="qbl-total"><div class="val">' . $totalWt . 'g</div><div class="lbl">Total Wt</div></div>';
        echo '<div class="qbl-total"><div class="val">' . $pct . '%</div><div class="lbl">QBL/Wt</div></div>';
        echo '</div>';
        $hasItems = false;
        for ($i = 1; $i <= 15; $i++) {
            $desc = $q["desc$i"] ?? '';
            $qty  = floatval($q["v$i"] ?? 0);
            $cfg  = floatval($q["value$i"] ?? 0);
            if (empty($desc) || $qty == 0 || $cfg == 0) continue;
            if (!$hasItems) { echo '<div class="qbl-items">'; $hasItems = true; }
            $ml = round($qty * $cfg, 0);
            echo '<div class="qbl-item"><span class="iname">' . htmlspecialchars($desc) . ' &times;' . intval($qty) . '</span><span class="iamt">' . $ml . 'ml</span></div>';
        }
        if ($hasItems) echo '</div>';
        if ($cumQbl >= 1000) {
            echo '<div class="qbl-hemorrhage">&#9888; Possible Hemorrhage' . ($isCum ? ' (Cumulative)' : '') . '</div>';
        }
        if (!empty($q['note'])) {
            echo '<div class="qbl-note"><strong>Note:</strong> ' . htmlspecialchars($q['note']) . '</div>';
        }
        echo '<button class="close-btn" onclick="closeQBL(' . intval($q['id']) . ', this)">Close QBL</button>';
        echo '</div>';
    endforeach;
}

function renderEscalations($escalations) {
    echo '<div class="section-header section-header-esc">Escalations</div>';
    if (empty($escalations)) {
        echo '<div class="section-none">No open escalations</div>';
        return;
    }
    $lastIdx = count($escalations) - 1;
    foreach ($escalations as $idx => $e):
        $isLast = ($idx === $lastIdx);
        echo '<div class="safety-card' . ($isLast ? ' last-card' : '') . '" id="esc-' . intval($e['id']) . '">';
        echo '<div class="esc-card-header">' . htmlspecialchars($e['deptName']) . '</div>';
        echo '<div class="field"><div class="lbl">Type</div><div class="val">' . (empty($e['escalationType']) ? '<span class="empty">Not specified</span>' : htmlspecialchars($e['escalationType'])) . '</div></div>';
        if (!empty($e['note'])) echo '<div class="field"><div class="lbl">Note</div><div class="val">' . nl2br(htmlspecialchars($e['note'])) . '</div></div>';
        echo '<div class="field"><div class="lbl">Submitted</div><div class="val">' . date('M j, Y g:i A', strtotime($e['dateSubmitted'])) . '</div></div>';
        echo '<button class="close-btn" onclick="closeEscalation(' . intval($e['id']) . ', this)">Close Escalation</button>';
        echo '</div>';
    endforeach;
}

function renderSafety($safetyIssues, $token) {
    echo '<div class="section-header section-header-safety">Safety Issues</div>';
    if (empty($safetyIssues)) {
        echo '<div class="section-none">None</div>';
        return;
    }
    $lastIdx = count($safetyIssues) - 1;
    foreach ($safetyIssues as $idx => $s):
        $isLast = ($idx === $lastIdx);
        echo '<div class="safety-card' . ($isLast ? ' last-card' : '') . '" id="safety-' . intval($s['id']) . '">';
        echo '<div class="safety-card-header">';
        echo htmlspecialchars($s['deptName']);
        if (intval($s['priority']) == 1) echo '<span class="priority-badge">High Priority</span>';
        echo '</div>';
        echo '<div class="field"><div class="lbl">Issue Type</div><div class="val' . (empty($s['issueType']) || $s['issueType'] === ' - ' ? ' empty' : '') . '">' . ((!empty($s['issueType']) && $s['issueType'] !== ' - ') ? htmlspecialchars($s['issueType']) : 'Not specified') . '</div></div>';
        if (!empty($s['roomId1'])) echo '<div class="field"><div class="lbl">Room / Location</div><div class="val">' . htmlspecialchars($s['roomId1']) . '</div></div>';
        echo '<div class="field"><div class="lbl">Note</div><div class="val' . (empty($s['note1']) ? ' empty' : '') . '">' . (!empty($s['note1']) ? nl2br(htmlspecialchars($s['note1'])) : 'No note provided') . '</div></div>';
        echo '<div class="field"><div class="lbl">Submitted By</div><div class="val' . (empty($s['periGen1']) ? ' empty' : '') . '">' . (!empty($s['periGen1']) ? htmlspecialchars($s['periGen1']) : 'Not provided') . '</div></div>';
        echo '<div class="field"><div class="lbl">Opened</div><div class="val">' . date('M j, Y', strtotime($s['openedDate'])) . '</div></div>';
        if (!empty($s['dueDate']) && $s['dueDate'] !== '0000-00-00') echo '<div class="field"><div class="lbl">Due Date</div><div class="val">' . date('M j, Y', strtotime($s['dueDate'])) . '</div></div>';
        echo '<button class="close-btn" onclick="closeIssue(' . intval($s['id']) . ', this)">Close Issue</button>';
        echo '</div>';
    endforeach;
}

$sectionEmpty = [
    'staffing'     => empty($staffReports),
    'qbl'          => empty($qblReports),
    'escalations'  => empty($escalations),
    'safety'       => empty($safetyIssues),
];
usort($sectionOrder, fn($a, $b) => $sectionEmpty[$a] <=> $sectionEmpty[$b]);

foreach ($sectionOrder as $section) {
    if ($section === 'staffing')     renderStaffing($staffReports);
    elseif ($section === 'qbl')      renderQBL($qblReports, $context);
    elseif ($section === 'escalations') renderEscalations($escalations);
    elseif ($section === 'safety')   renderSafety($safetyIssues, $token);
}
?>

<div class="footer">Expires <?php echo date('M j, Y g:i A', strtotime($tok['expires_at'])); ?></div>
<div class="login-link"><a href="https://www.productivern.com/login.php">Login to ProductiveRN</a></div>

<script>
const CLOSE_TOKEN = <?php echo json_encode($closeToken); ?>;

function closeEscalation(escId, btn) {
    if (!confirm('Close this escalation?')) return;
    btn.disabled = true;
    btn.textContent = 'Closing...';
    fetch('inc/data.php?req=closeescalationtoken', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'token=' + encodeURIComponent(CLOSE_TOKEN) + '&dataId=' + encodeURIComponent(escId)
    })
    .then(function(r){ return r.json(); })
    .then(function(data){
        if (data.success) {
            var row = document.getElementById('esc-' + escId);
            if (row) {
                row.style.transition = 'opacity .4s';
                row.style.opacity = '0';
                setTimeout(function(){ row.style.display = 'none'; }, 420);
            }
        } else {
            btn.disabled = false;
            btn.textContent = 'Close Escalation';
            alert(data.message || 'Error closing escalation.');
        }
    })
    .catch(function(){
        btn.disabled = false;
        btn.textContent = 'Close Escalation';
        alert('Network error. Please try again.');
    });
}

function closeQBL(qblId, btn) {
    if (!confirm('Close this QBL record?')) return;
    btn.disabled = true;
    btn.textContent = 'Closing...';
    fetch('inc/data.php?req=closeqbltoken', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'token=' + encodeURIComponent(CLOSE_TOKEN) + '&dataId=' + encodeURIComponent(qblId)
    })
    .then(function(r){ return r.json(); })
    .then(function(data){
        if (data.success) {
            var card = document.getElementById('qbl-' + qblId);
            if (card) {
                card.style.transition = 'opacity .4s';
                card.style.opacity = '0';
                setTimeout(function(){ card.style.display = 'none'; }, 420);
            }
        } else {
            btn.disabled = false;
            btn.textContent = 'Close QBL';
            alert(data.message || 'Error closing QBL record.');
        }
    })
    .catch(function(){
        btn.disabled = false;
        btn.textContent = 'Close QBL';
        alert('Network error. Please try again.');
    });
}

function closeIssue(safetyId, btn) {
    if (!confirm('Close this safety issue?')) return;
    btn.disabled = true;
    btn.textContent = 'Closing...';
    fetch('inc/data.php?req=closesafetytoken', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'token=' + encodeURIComponent(CLOSE_TOKEN) + '&dataId=' + encodeURIComponent(safetyId)
    })
    .then(function(r){ return r.json(); })
    .then(function(data){
        if (data.success) {
            var card = document.getElementById('safety-' + safetyId);
            if (card) {
                card.style.transition = 'opacity .4s';
                card.style.opacity = '0';
                setTimeout(function(){ card.style.display = 'none'; }, 420);
            }
        } else {
            btn.disabled = false;
            btn.textContent = 'Close Issue';
            alert(data.message || 'Error closing issue. Please try again.');
        }
    })
    .catch(function(){
        btn.disabled = false;
        btn.textContent = 'Close Issue';
        alert('Network error. Please try again.');
    });
}
</script>
</body>
</html>
