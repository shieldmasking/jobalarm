<?php
require_once __DIR__ . '/_auth.php';

$accountId = validate_export_token();
[$from, $to] = get_date_range();
$db = Config::get('db');

// Set account timezone for accurate late detection
$tzRow = $db->get_results("SELECT timeZone FROM productiveAccount WHERE id = {$accountId} LIMIT 1");
if ($tzRow && !empty($tzRow[0]['timeZone'])) {
    @date_default_timezone_set($tzRow[0]['timeZone']);
}

$rows = $db->get_results("
    SELECT
        DATE_FORMAT(n.dayDate,'%m/%d/%Y') as date,
        d.dept                            as unit,
        s.shift                           as shiftName,
        ROUND(n.nvariance,2)              as variance,
        ROUND(n.hrsVariance,2)            as hrsVariance,
        ROUND((n.antecount*d.rnCount + n.customNurse*d.rn1Count + n.customNurse2*d.rn2Count),1) as rnCount,
        ROUND((n.chargecount*d.staff1 + n.antecount*d.staff2 + n.customNurse*d.staff3
             + n.customNurse2*d.staff4 + n.seccount*d.staff5 + n.techcount*d.staff6
             + n.otherNurse1*d.staff7 + n.sittercount*d.staff8
             + n.otherNurse2*d.staff9 + n.otherNurse3*d.staff10),1) as staffCount,
        n.atotal as ptCount,
        IFNULL(n.note,'')                  as notes,
        ROUND(n.nproductivity,1)           as productivity,
        TRIM(CONCAT(IFNULL(u.first_name,''),' ',IFNULL(u.last_name,''))) as chargeNurse,
        n.antecount, n.customNurse, n.customNurse2, n.chargecount,
        n.techcount, n.seccount, n.sittercount, n.otherNurse1, n.otherNurse2, n.otherNurse3,
        IFNULL(d.skilldesc1,'')  as skilldesc1,  IFNULL(d.skilldesc2,'')  as skilldesc2,
        IFNULL(d.skilldesc3,'')  as skilldesc3,  IFNULL(d.skilldesc4,'')  as skilldesc4,
        IFNULL(d.skilldesc5,'')  as skilldesc5,  IFNULL(d.skilldesc6,'')  as skilldesc6,
        IFNULL(d.skilldesc7,'')  as skilldesc7,  IFNULL(d.skilldesc8,'')  as skilldesc8,
        IFNULL(d.skilldesc9,'')  as skilldesc9,  IFNULL(d.skilldesc10,'') as skilldesc10,
        IFNULL(n.skill1val,0)  as skill1val,  IFNULL(n.skill2val,0)  as skill2val,
        IFNULL(n.skill3val,0)  as skill3val,  IFNULL(n.skill4val,0)  as skill4val,
        IFNULL(n.skill5val,0)  as skill5val,  IFNULL(n.skill6val,0)  as skill6val,
        IFNULL(n.skill7val,0)  as skill7val,  IFNULL(n.skill8val,0)  as skill8val,
        IFNULL(n.skill9val,0)  as skill9val,  IFNULL(n.skill10val,0) as skill10val,
        DATE_FORMAT(n.entered, '%H:%i') as submitTime,
        n.id     as reportId,
        n.userId as userId,
        n.deptId as deptId,
        DATE_FORMAT(n.dayDate,'%Y-%m-%d') as sortDate,
        s.shiftTime                        as shiftTime
    FROM productiveNewData n
    LEFT JOIN ProductiveDept    d ON d.id = n.deptId
    LEFT JOIN productiveShifts  s ON s.id = n.shift
    LEFT JOIN productiveUser    u ON u.id = n.userId
    WHERE n.accountId = {$accountId}
      AND n.dayDate BETWEEN '{$from}' AND '{$to}'
    ORDER BY n.dayDate ASC, d.dept ASC, n.shift ASC
");
$rows = $rows ?: [];

// For single-day queries: add late status and no-report placeholder rows
if ($from === $to) {
    // All active depts assigned to users of this account
    $allDepts = $db->get_results("
        SELECT DISTINCT d.id as deptId, d.dept
        FROM ProductiveDept d
        JOIN productiveDeptXref x ON x.deptId = d.id
        JOIN productiveUser u ON u.id = x.userId
        WHERE u.accountId = {$accountId} AND d.active = 1
        ORDER BY d.dept
    ") ?: [];

    if ($allDepts) {
        $allDeptIds = implode(',', array_map(fn($d) => intval($d['deptId']), $allDepts));

        // Most recent report per dept (for late detection — not restricted to today)
        $latestRows = $db->get_results("
            SELECT n.deptId, n.shift as shiftId, n.dayDate
            FROM productiveNewData n
            INNER JOIN (
                SELECT deptId, MAX(entered) as maxEntered
                FROM productiveNewData
                WHERE accountId = {$accountId} AND activeRecord > 0 AND userId > 0
                  AND deptId IN ({$allDeptIds})
                GROUP BY deptId
            ) lx ON n.deptId = lx.deptId AND n.entered = lx.maxEntered
            WHERE n.accountId = {$accountId}
        ") ?: [];
        $latestByDept = [];
        foreach ($latestRows as $r) $latestByDept[intval($r['deptId'])] = $r;

        // Shifts per dept (one query)
        $shiftRows = $db->get_results("
            SELECT x.dept as deptId, x.shift as shiftId, s.shiftTime, s.shift as shiftName
            FROM productiveAccountXref x
            JOIN productiveShifts s ON s.id = x.shift
            WHERE x.dept IN ({$allDeptIds}) AND x.active = 1
            ORDER BY x.dept ASC, x.shiftOrder ASC
        ") ?: [];
        $shiftsByDept = [];
        foreach ($shiftRows as $s) {
            $shiftsByDept[intval($s['deptId'])][] = [
                'id'   => intval($s['shiftId']),
                'time' => $s['shiftTime'],
                'name' => $s['shiftName'],
            ];
        }

        $nowRow   = $db->get_results("SELECT DATE_FORMAT(NOW(),'%Y-%m-%d') as today, DATE_FORMAT(NOW(),'%H:%i:%s') as nowTime");
        $todayStr = $nowRow[0]['today'];
        $nowTime  = $nowRow[0]['nowTime'];

        // Depts that submitted a report on $from
        $deptIdsWithReport = [];
        foreach ($rows as $r) $deptIdsWithReport[intval($r['deptId'])] = true;

        // Compute status per dept
        $statusByDept    = [];
        $missedTimeByDept = [];
        foreach ($allDepts as $dept) {
            $dId      = intval($dept['deptId']);
            $shifts   = $shiftsByDept[$dId] ?? [];
            $numShifts = count($shifts);

            if (!isset($deptIdsWithReport[$dId])) {
                // No report today — find most recent past shift
                $missed     = $numShifts > 0 ? $shifts[$numShifts - 1]['name'] : '';
                $missedTime = $numShifts > 0 ? $shifts[$numShifts - 1]['time'] : '';
                for ($i = $numShifts - 1; $i >= 0; $i--) {
                    if ($nowTime > $shifts[$i]['time']) {
                        $missed     = $shifts[$i]['name'];
                        $missedTime = $shifts[$i]['time'];
                        break;
                    }
                }
                $missedTimeByDept[$dId] = $missedTime ? date('g:i A', strtotime($missedTime)) : '';
                $statusByDept[$dId] = 'No Report';
            } else {
                // Has report — check if a newer shift has since started
                $latest = $latestByDept[$dId] ?? null;
                $statusByDept[$dId] = '';
                if ($latest && $numShifts > 0) {
                    $curShiftId = intval($latest['shiftId']);
                    $idx = array_search($curShiftId, array_column($shifts, 'id'));
                    if ($idx !== false) {
                        $checkIdx  = ($idx + 1) % $numShifts;
                        $checkDate = ($idx + 1 >= $numShifts)
                            ? date('Y-m-d', strtotime($latest['dayDate'] . ' +1 day'))
                            : $latest['dayDate'];
                        for ($step = 0; $step < $numShifts * 7; $step++) {
                            if ($checkDate > $todayStr) break;
                            $shift = $shifts[$checkIdx];
                            $isPast = ($checkDate < $todayStr)
                                   || ($checkDate === $todayStr && $nowTime > $shift['time']);
                            if (!$isPast) break;
                            $statusByDept[$dId] = 'Late';
                            $nextCheckIdx = ($checkIdx + 1) % $numShifts;
                            if ($nextCheckIdx === 0) $checkDate = date('Y-m-d', strtotime($checkDate . ' +1 day'));
                            $checkIdx = $nextCheckIdx;
                        }
                    }
                }
            }
        }

        // Stamp status onto existing rows
        foreach ($rows as &$r) {
            $r['status'] = intval($r['userId']) === 0
                ? 'No Report'
                : ($statusByDept[intval($r['deptId'])] ?? '');
        }
        unset($r);

        // Append placeholder rows for depts with no report today
        $emptyRow = array_fill_keys([
            'date','unit','shiftName','variance','hrsVariance','rnCount','staffCount','ptCount',
            'notes','productivity','chargeNurse',
            'antecount','customNurse','customNurse2','chargecount',
            'techcount','seccount','sittercount','otherNurse1','otherNurse2','otherNurse3',
            'skilldesc1','skilldesc2','skilldesc3','skilldesc4','skilldesc5',
            'skilldesc6','skilldesc7','skilldesc8','skilldesc9','skilldesc10',
            'skill1val','skill2val','skill3val','skill4val','skill5val',
            'skill6val','skill7val','skill8val','skill9val','skill10val',
            'submitTime','reportId','deptId','status','sortDate','shiftTime',
        ], '');
        foreach ($allDepts as $dept) {
            $dId = intval($dept['deptId']);
            if (!isset($deptIdsWithReport[$dId])) {
                $placeholder = $emptyRow;
                $placeholder['date']       = date('m/d/Y', strtotime($from));
                $placeholder['unit']       = $dept['dept'];
                $placeholder['deptId']     = $dId;
                $placeholder['submitTime'] = $missedTimeByDept[$dId] ?? '';
                $placeholder['status']     = $statusByDept[$dId] ?? 'No Report';
                $placeholder['sortDate']   = date('Y-m-d', strtotime($from));
                $placeholder['shiftTime']  = '';
                $rows[] = $placeholder;
            }
        }

        // Re-sort by unit
        usort($rows, fn($a, $b) => strcmp($a['unit'] ?? '', $b['unit'] ?? ''));
    }
} else {
    // Multi-day: no late detection; mark unfilled pre-created shifts
    foreach ($rows as &$r) $r['status'] = intval($r['userId']) === 0 ? 'No Report' : '';
    unset($r);
}

emit_csv(
    ['Date','Unit','Shift','Variance','Hrs Variance','RN Count','Staff Count','Pt Count',
     'Notes','Productivity %','Charge Nurse',
     'Antecount','Nurse1','Nurse2','Charge','Tech','Sec','Sitter','Other1','Other2','Other3',
     'Skill1 Label','Skill2 Label','Skill3 Label','Skill4 Label','Skill5 Label',
     'Skill6 Label','Skill7 Label','Skill8 Label','Skill9 Label','Skill10 Label',
     'Skill1 Hrs','Skill2 Hrs','Skill3 Hrs','Skill4 Hrs','Skill5 Hrs',
     'Skill6 Hrs','Skill7 Hrs','Skill8 Hrs','Skill9 Hrs','Skill10 Hrs',
     'Submit Time','Report ID','Dept ID','Status','Sort Date','Shift Time'],
    array_map(fn($r) => [
        $r['date'], $r['unit'], $r['shiftName'], $r['variance'], $r['hrsVariance'],
        $r['rnCount'], $r['staffCount'], $r['ptCount'], $r['notes'],
        $r['productivity'], $r['chargeNurse'],
        $r['antecount'], $r['customNurse'], $r['customNurse2'], $r['chargecount'],
        $r['techcount'], $r['seccount'], $r['sittercount'],
        $r['otherNurse1'], $r['otherNurse2'], $r['otherNurse3'],
        $r['skilldesc1'], $r['skilldesc2'], $r['skilldesc3'], $r['skilldesc4'], $r['skilldesc5'],
        $r['skilldesc6'], $r['skilldesc7'], $r['skilldesc8'], $r['skilldesc9'], $r['skilldesc10'],
        $r['skill1val'], $r['skill2val'], $r['skill3val'], $r['skill4val'], $r['skill5val'],
        $r['skill6val'], $r['skill7val'], $r['skill8val'], $r['skill9val'], $r['skill10val'],
        $r['submitTime'], $r['reportId'], $r['deptId'], $r['status'],
        $r['sortDate'], $r['shiftTime'],
    ], $rows)
);
