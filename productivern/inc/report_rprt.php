<?php
use Dompdf\Dompdf;
use Dompdf\Options;

function getrprtDompdf() {
    require_once '.././inc/reports_dompdf.php';
    require_once '.././vendor/autoload.php';

    $userId    = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
    $reportId  = isset($_REQUEST['r']) ? intval($_REQUEST['r']) : 0;

    if ($reportId == 1) {
        $searchAdd = "AND d.active>0 AND d.id IN (SELECT deptId FROM productiveDeptXref WHERE userId={$userId} AND primaryUnit>0) AND n.dayDate>=u.lastStartDate AND n.dayDate<=u.lastEndDate";
    } elseif ($reportId == 0) {
        $searchAdd = "AND d.active>0 AND n.dayDate>=u.lastStartDate AND n.dayDate<=u.lastEndDate";
    } else {
        $searchAdd = "AND n.deptId={$reportId} AND n.dayDate>=u.lastStartDate AND n.dayDate<=u.lastEndDate";
    }

    $getDept = Config::get('db')->get_results(
        "SELECT n.deptId FROM productiveNewData n
         LEFT JOIN ProductiveDept d ON d.id=n.deptId
         LEFT JOIN productiveUser u ON u.id={$userId}
         WHERE n.accountId={$accountId} {$searchAdd}
         GROUP BY n.deptId ORDER BY d.prodMeasure ASC, d.dept ASC"
    );

    if (!$getDept) { echo json_encode(['success' => false]); return; }

    $allHtml = '';

    foreach ($getDept as $deptRow) {
        $deptId = intval($deptRow['deptId']);

        $rows = Config::get('db')->get_results(
            "SELECT n.*,
                    u.lastStartDate, u.lastEndDate,
                    d.prodMeasure, d.showEpic, d.useGrid,
                    d.chargeDesc, d.nurseDesc, d.nurse1Desc, d.nurse2Desc,
                    d.techLabel, d.secLabel, d.sittersNEWDesc,
                    d.other1Desc, d.other2Desc, d.other3Desc,
                    d.skill1, d.skill2, d.skill3, d.skill4, d.skill5, d.skill6,
                    d.skill7, d.skill8, d.skill9, d.skill10,
                    d.skilldesc1, d.skilldesc2, d.skilldesc3, d.skilldesc4, d.skilldesc5, d.skilldesc6,
                    d.uosDesc, d.customDesc, d.customDesc2,
                    d.desc7, d.desc8, d.desc9, d.desc10, d.desc11, d.desc12,
                    d.track1Desc, d.track2Desc, d.track3Desc, d.track4Desc,
                    d.dept AS deptName,
                    a.name AS accountName,
                    DATE_FORMAT(u.lastStartDate,'%m/%d/%y') AS startDay,
                    DATE_FORMAT(u.lastEndDate,'%m/%d/%y') AS endDay,
                    DATE_FORMAT(n.dayDate,'%m/%d') AS newDate,
                    s.shift AS shiftName,
                    IFNULL(n.submittedby, o.last_name) AS submittedby
             FROM productiveNewData n
             LEFT JOIN ProductiveDept d ON d.id=n.deptId
             LEFT JOIN productiveShifts s ON s.id=n.shift
             LEFT JOIN productiveAccount a ON a.id=n.accountId
             LEFT JOIN productiveUser o ON o.id=n.userId
             LEFT JOIN productiveUser u ON u.id={$userId}
             WHERE n.dayDate>=u.lastStartDate AND n.dayDate<=u.lastEndDate AND n.deptId={$deptId}
             GROUP BY n.accountId, n.deptId, n.dayDate, n.shift
             ORDER BY d.dept ASC, n.dayDate DESC, n.shift DESC"
        );

        if (!$rows) continue;

        $r0    = $rows[0];
        $pm    = intval($r0['prodMeasure']);
        $isNur = ($pm != 2 && $pm != 3);
        $type  = $isNur ? 'Staffing Detail' : 'Daily Detail';

        $allHtml .= _report_head(
            $r0['accountName'],
            $type . ': ' . htmlspecialchars($r0['deptName']),
            '',
            htmlspecialchars($r0['startDay']) . ' – ' . htmlspecialchars($r0['endDay'])
        );

        $allHtml .= $isNur
            ? _rprt_nursing_table($rows, $r0)
            : _rprt_clinic_table($rows, $r0);

        // Acuity breakdown (desc7–12) — nursing only
        if ($isNur && (strlen($r0['desc7'] ?? '') || strlen($r0['desc8'] ?? '') ||
                        strlen($r0['desc9'] ?? '') || strlen($r0['desc10'] ?? '') ||
                        strlen($r0['desc11'] ?? '') || strlen($r0['desc12'] ?? ''))) {
            $allHtml .= _rprt_acuity_table($rows, $r0);
        }

        // Tracking section (track1–4)
        if (strlen($r0['track1Desc'] ?? '') > 1 || strlen($r0['track2Desc'] ?? '') > 1 ||
            strlen($r0['track3Desc'] ?? '') > 1 || strlen($r0['track4Desc'] ?? '') > 1) {
            $allHtml .= _rprt_tracking_table($rows, $r0);
        }

        $allHtml .= '<div style="page-break-after:always;margin-bottom:20px;"></div>';
    }

    $html = '<html><head><meta charset="UTF-8"><style>' . _report_css() . '
        .section-title { font-size: 10pt; font-weight: bold; margin: 14px 0 4px; border-bottom: 1px solid #ccc; padding-bottom: 3px; }
    </style></head><body>' . $allHtml . '</body></html>';

    $options = new Options();
    $options->set('isRemotingEnabled', false);
    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('letter', 'landscape');
    $dompdf->render();
    $dompdf->stream('ShiftDetail.pdf', ['Attachment' => true]);
    exit();
}

// ─── Nursing unit table ───────────────────────────────────────────────────────

function _rprt_nursing_table($rows, $r0) {
    $showCharge = strlen($r0['chargeDesc'] ?? '') > 0;
    $showRN     = strlen($r0['nurseDesc'] ?? '') > 0;
    $showNurse1 = strlen($r0['nurse1Desc'] ?? '') > 0;
    $showNurse2 = strlen($r0['nurse2Desc'] ?? '') > 0;
    $showTech   = strlen($r0['techLabel'] ?? '') > 0;
    $showSec    = strlen($r0['secLabel'] ?? '') > 0;
    $showOther  = strlen($r0['sittersNEWDesc'] ?? '') > 0 || strlen($r0['other1Desc'] ?? '') > 0
               || strlen($r0['other2Desc'] ?? '') > 0 || strlen($r0['other3Desc'] ?? '') > 0;

    $head = '<thead><tr>'
        . '<th>Unit</th><th>Date / Shift</th><th>User</th><th>Pts</th>'
        . ($showCharge ? '<th>' . htmlspecialchars($r0['chargeDesc']) . '</th>' : '')
        . ($showRN     ? '<th>' . htmlspecialchars($r0['nurseDesc'])  . '</th>' : '')
        . ($showNurse1 ? '<th>' . htmlspecialchars($r0['nurse1Desc']) . '</th>' : '')
        . ($showNurse2 ? '<th>' . htmlspecialchars($r0['nurse2Desc']) . '</th>' : '')
        . ($showTech   ? '<th>' . htmlspecialchars($r0['techLabel'])  . '</th>' : '')
        . ($showSec    ? '<th>' . htmlspecialchars($r0['secLabel'])   . '</th>' : '')
        . ($showOther  ? '<th>Other</th>' : '')
        . '<th>RN Var</th><th>Grid Var</th><th>Notes</th>'
        . '</tr></thead>';

    $tbody = '<tbody>';
    foreach ($rows as $r) {
        if (intval($r['userId']) == 0) {
            $pts = $sub = '-'; $nv = $gv = ''; $charge = $rn = $nurse1 = $nurse2 = $tech = $sec = $other = '';
            $note = '';
        } else {
            $pts    = intval($r['atotal']) + intval($r['ltotal']) + intval($r['patientCount2']);
            $sub    = htmlspecialchars($r['submittedby'] ?? '');
            $charge = $showCharge ? round(floatval($r['chargecount']), 0) : '';
            $rn     = $showRN     ? round(floatval($r['antecount']), 0) : '';
            $nurse1 = $showNurse1 ? htmlspecialchars($r['customNurse'] ?? '') : '';
            $nurse2 = $showNurse2 ? htmlspecialchars($r['customNurse2'] ?? '') : '';
            $tech   = $showTech   ? $r['techcount'] : '';
            $sec    = $showSec    ? $r['seccount'] : '';
            $other  = $showOther  ? (intval($r['sittercount']) + intval($r['otherNurse1']) + intval($r['otherNurse2']) + intval($r['otherNurse3'])) : '';
            $nv     = round(floatval($r['nvariance']), 1);
            $gv     = round(floatval($r['gvariance']), 1);
            $note   = nl2br(htmlspecialchars(stripslashes($r['note'] ?? '')));
        }

        $tbody .= '<tr>'
            . '<td>' . htmlspecialchars($r['deptName']) . '</td>'
            . '<td class="c">' . htmlspecialchars($r['newDate'] . ' – ' . $r['shiftName']) . '</td>'
            . '<td>' . ($r['userId'] > 0 ? $sub : '-') . '</td>'
            . '<td class="c">' . $pts . '</td>'
            . ($showCharge ? '<td class="c">' . $charge . '</td>' : '')
            . ($showRN     ? '<td class="c">' . $rn     . '</td>' : '')
            . ($showNurse1 ? '<td class="c">' . $nurse1 . '</td>' : '')
            . ($showNurse2 ? '<td class="c">' . $nurse2 . '</td>' : '')
            . ($showTech   ? '<td class="c">' . $tech   . '</td>' : '')
            . ($showSec    ? '<td class="c">' . $sec    . '</td>' : '')
            . ($showOther  ? '<td class="c">' . $other  . '</td>' : '')
            . '<td class="c">' . ($r['userId'] > 0 ? $nv : '') . '</td>'
            . '<td class="c">' . ($r['userId'] > 0 ? $gv : '') . '</td>'
            . '<td class="note">' . $note . '</td>'
            . '</tr>';
    }
    $tbody .= '</tbody>';

    return '<table>' . $head . $tbody . '</table>';
}

// ─── Clinic/OPS unit table ───────────────────────────────────────────────────

function _rprt_clinic_table($rows, $r0) {
    $skills = [];
    for ($i = 1; $i <= 6; $i++) {
        if (intval($r0["skill{$i}"]) > 0 && strlen($r0["skilldesc{$i}"] ?? '') > 0) {
            $skills[$i] = htmlspecialchars($r0["skilldesc{$i}"]);
        }
    }
    $showOther = false;
    for ($i = 7; $i <= 10; $i++) {
        if (intval($r0["skill{$i}"]) > 0) { $showOther = true; break; }
    }

    $head = '<thead><tr><th>Unit</th><th>Date / Shift</th><th>User</th>'
        . '<th>' . htmlspecialchars($r0['uosDesc'] ?? 'UOS') . '</th>';
    foreach ($skills as $label) {
        $head .= '<th>' . $label . '</th>';
    }
    if ($showOther) $head .= '<th>Other</th>';
    $head .= '<th>Variance</th><th>Notes</th></tr></thead>';

    $tbody = '<tbody>';
    foreach ($rows as $r) {
        if (intval($r['userId']) == 0) {
            $proc = $gv = ''; $note = ''; $sub = '-';
            $skillVals = array_fill(1, 6, '');
            $otherVal = '';
        } else {
            $proc = round(floatval($r['procedureCount']), 0);
            $gv   = round(floatval($r['gvariance']), 1);
            $sub  = htmlspecialchars($r['submittedby'] ?? '');
            $note = nl2br(htmlspecialchars(stripslashes($r['note'] ?? '')));
            $skillVals = [];
            for ($i = 1; $i <= 6; $i++) {
                $skillVals[$i] = isset($skills[$i]) ? floatval($r["skill{$i}val"]) : '';
            }
            $otherVal = 0;
            for ($i = 7; $i <= 10; $i++) {
                $otherVal += floatval($r["skill{$i}val"] ?? 0);
            }
        }

        $tbody .= '<tr>'
            . '<td>' . htmlspecialchars($r['deptName']) . '</td>'
            . '<td class="c">' . htmlspecialchars($r['newDate'] . ' – ' . $r['shiftName']) . '</td>'
            . '<td>' . $sub . '</td>'
            . '<td class="c">' . $proc . '</td>';
        foreach ($skills as $i => $label) {
            $tbody .= '<td class="c">' . ($skillVals[$i] ?? '') . '</td>';
        }
        if ($showOther) $tbody .= '<td class="c">' . ($r['userId'] > 0 ? $otherVal : '') . '</td>';
        $tbody .= '<td class="c">' . $gv . '</td>'
            . '<td class="note">' . $note . '</td>'
            . '</tr>';
    }
    $tbody .= '</tbody>';

    return '<table>' . $head . $tbody . '</table>';
}

// ─── Acuity breakdown table (desc7–12) ───────────────────────────────────────

function _rprt_acuity_table($rows, $r0) {
    $descs = [];
    $fields = ['desc7'=>'oneto7','desc8'=>'oneto8','desc9'=>'oneto9','desc10'=>'oneto10','desc11'=>'oneto11','desc12'=>'oneto12'];
    foreach ($fields as $dKey => $fKey) {
        if (strlen($r0[$dKey] ?? '') > 0) {
            $descs[$fKey] = htmlspecialchars($r0[$dKey]);
        }
    }
    if (!$descs) return '';

    $head = '<thead><tr><th>Unit</th><th>Date / Shift</th><th>User</th><th>Pts</th>';
    foreach ($descs as $label) $head .= '<th>' . $label . '</th>';
    $head .= '</tr></thead>';

    $tbody = '<tbody>';
    foreach ($rows as $r) {
        $pts = $r['userId'] > 0 ? intval($r['atotal']) : '';
        $sub = $r['userId'] > 0 ? htmlspecialchars($r['submittedby'] ?? '') : '-';
        $tbody .= '<tr>'
            . '<td>' . htmlspecialchars($r['deptName']) . '</td>'
            . '<td class="c">' . htmlspecialchars($r['newDate'] . ' – ' . $r['shiftName']) . '</td>'
            . '<td>' . $sub . '</td>'
            . '<td class="c">' . $pts . '</td>';
        foreach (array_keys($descs) as $fKey) {
            $tbody .= '<td class="c">' . ($r['userId'] > 0 ? htmlspecialchars($r[$fKey] ?? '') : '') . '</td>';
        }
        $tbody .= '</tr>';
    }
    $tbody .= '</tbody>';

    return '<p class="section-title">Acuity Detail</p><table>' . $head . $tbody . '</table>';
}

// ─── Tracking table (track1Desc–track4Desc) ──────────────────────────────────

function _rprt_tracking_table($rows, $r0) {
    $tracks = [];
    $trackFields = ['track1Desc'=>'track1val','track2Desc'=>'track2val','track3Desc'=>'track3val','track4Desc'=>'track4val'];
    foreach ($trackFields as $dKey => $fKey) {
        if (strlen($r0[$dKey] ?? '') > 1) {
            $tracks[$fKey] = htmlspecialchars($r0[$dKey]);
        }
    }
    if (!$tracks) return '';

    $showEpic = intval($r0['showEpic']) == 1 && intval($r0['prodMeasure']) != 2;

    $head = '<thead><tr><th>Unit</th><th>Date / Shift</th><th>User</th><th>Pts</th>';
    if ($showEpic) $head .= '<th>Epic</th>';
    foreach ($tracks as $label) $head .= '<th>' . $label . '</th>';
    $head .= '</tr></thead>';

    $tbody = '<tbody>';
    foreach ($rows as $r) {
        $pts = $r['userId'] > 0 ? (intval($r['atotal']) + intval($r['ltotal']) + intval($r['patientCount2'])) : '';
        $sub = $r['userId'] > 0 ? htmlspecialchars($r['submittedby'] ?? '') : '-';
        $tbody .= '<tr>'
            . '<td>' . htmlspecialchars($r['deptName']) . '</td>'
            . '<td class="c">' . htmlspecialchars($r['newDate'] . ' – ' . $r['shiftName']) . '</td>'
            . '<td>' . $sub . '</td>'
            . '<td class="c">' . $pts . '</td>';
        if ($showEpic) $tbody .= '<td class="c">' . htmlspecialchars($r['epicCount'] ?? '') . '</td>';
        foreach (array_keys($tracks) as $fKey) {
            $tbody .= '<td class="c">' . ($r['userId'] > 0 ? htmlspecialchars($r[$fKey] ?? '') : '') . '</td>';
        }
        $tbody .= '</tr>';
    }
    $tbody .= '</tbody>';

    return '<p class="section-title">Tracking Detail</p><table>' . $head . $tbody . '</table>';
}
