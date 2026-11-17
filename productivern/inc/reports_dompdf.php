<?php
use Dompdf\Dompdf;
use Dompdf\Options;

// ─── Shared helpers ──────────────────────────────────────────────────────────

function _dompdf_render($html, $filename, $orientation = 'portrait') {
    require_once '.././vendor/autoload.php';
    $options = new Options();
    $options->set('isRemotingEnabled', false);
    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('letter', $orientation);
    $dompdf->render();
    $dompdf->stream($filename, ['Attachment' => true]);
    exit();
}

function _report_css() {
    return '
        body { font-family: Arial, sans-serif; font-size: 9pt; margin: 0; }
        h1 { font-size: 13pt; text-align: center; margin: 0 0 2px; }
        h2 { font-size: 11pt; text-align: center; margin: 0 0 2px; }
        p.sub { text-align: center; font-size: 9pt; margin: 0 0 8px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th { background: #1a4f9e; color: #fff; padding: 5px 6px; font-size: 8.5pt; text-align: center; }
        td { border: 1px solid #ccc; padding: 4px 6px; vertical-align: top; font-size: 8.5pt; }
        tr:nth-child(even) td { background: #f4f7fb; }
        td.c { text-align: center; white-space: nowrap; }
        td.note { max-width: 220px; word-wrap: break-word; }
        h3.section { font-size: 10pt; margin: 14px 0 4px; border-bottom: 1px solid #ccc; padding-bottom: 3px; }
    ';
}

function _report_head($accountName, $title, $deptName = '', $dateRange = '') {
    $dept = $deptName ? "<h2>" . htmlspecialchars($deptName) . "</h2>" : '';
    $date = $dateRange ? "<p class='sub'>" . htmlspecialchars($dateRange) . "</p>" : '';
    return "<h1>" . htmlspecialchars($accountName) . "</h1><h2>" . htmlspecialchars($title) . "</h2>{$dept}{$date}";
}

function _account_meta($userId, $accountId, $deptId = 0) {
    if ($deptId > 0) {
        $row = Config::get('db')->get_results(
            "SELECT a.name AS accountName, d.dept AS deptName,
                    DATE_FORMAT(u.lastStartDate,'%m/%d/%y') AS startDay,
                    DATE_FORMAT(u.lastEndDate,'%m/%d/%y') AS endDay
             FROM productiveAccount a
             LEFT JOIN productiveUser u ON u.id={$userId}
             LEFT JOIN ProductiveDept d ON d.id={$deptId}
             WHERE a.id={$accountId} LIMIT 1"
        );
    } else {
        $row = Config::get('db')->get_results(
            "SELECT a.name AS accountName, '' AS deptName,
                    DATE_FORMAT(u.lastStartDate,'%m/%d/%y') AS startDay,
                    DATE_FORMAT(u.lastEndDate,'%m/%d/%y') AS endDay
             FROM productiveAccount a
             LEFT JOIN productiveUser u ON u.id={$userId}
             WHERE a.id={$accountId} LIMIT 1"
        );
    }
    return $row ? $row[0] : [];
}

// ─── 1. User Variance Summary (alluser) ──────────────────────────────────────

function allUserDompdf() {
    $userId    = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
    $deptNum   = isset($_REQUEST['e']) ? intval($_REQUEST['e']) : 0;

    $shiftAdd = $deptNum > 0 ? "AND n.deptID={$deptNum}" : '';
    $meta = _account_meta($userId, $accountId, $deptNum);

    $rows = Config::get('db')->get_results(
        "SELECT d.dept AS deptName, e.last_name AS userName,
                u.last_name AS Director, i.last_name AS Manager,
                DATE_FORMAT(CURDATE(),'%m%d%y') AS filedate,
                SUM(CASE WHEN n.userId>0 AND n.activeRecord>0 THEN 1 ELSE 0 END) AS report,
                ROUND(SUM(CASE WHEN n.userId>0 THEN n.nvariance ELSE 0 END) /
                      NULLIF(SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END),0), 1) AS varAvg,
                ROUND(SUM(CASE WHEN n.userId>0 THEN n.gvariance ELSE 0 END) /
                      NULLIF(SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END),0), 1) AS varGrid
         FROM productiveNewData n
         LEFT JOIN ProductiveDept d ON d.id = n.deptId
         LEFT JOIN productiveAccount a ON a.id = n.accountId
         LEFT JOIN productiveUser i ON i.id=(SELECT userId FROM productiveDeptXref WHERE deptId=d.id AND primaryUnit=6 AND unitAssigned=0 ORDER BY primaryUnit ASC LIMIT 1)
         LEFT JOIN productiveUser u ON u.id=(SELECT userId FROM productiveDeptXref WHERE deptId=d.id AND primaryUnit=7 ORDER BY primaryUnit ASC LIMIT 1)
         LEFT JOIN productiveUser z ON z.id={$userId}
         LEFT JOIN productiveUser e ON e.id=n.userId
         LEFT JOIN productiveShifts s ON s.id=n.shift
         WHERE d.active>0 AND d.prodMeasure!=2 AND d.prodMeasure!=3
           AND n.dayDate>=z.lastStartDate AND n.dayDate<=z.lastEndDate
           AND n.accountId={$accountId} {$shiftAdd}
         GROUP BY n.accountId, n.deptId, n.userId
         ORDER BY varAvg DESC"
    );

    $tbody = '';
    foreach ($rows as $r) {
        $tbody .= sprintf(
            '<tr><td>%s</td><td>%s</td><td>%s</td><td class="c">%s</td><td>%s</td><td class="c">%s</td><td class="c">%s</td></tr>',
            htmlspecialchars($r['deptName']),
            htmlspecialchars($r['Director'] ?? ''),
            htmlspecialchars($r['Manager'] ?? ''),
            $r['report'],
            htmlspecialchars($r['userName'] ?? ''),
            $r['varAvg'] ?? '',
            $r['varGrid'] ?? ''
        );
    }

    $deptLabel = $deptNum > 0 ? ' – ' . htmlspecialchars($meta['deptName'] ?? '') : '';
    $html = '<html><head><style>' . _report_css() . '</style></head><body>'
        . _report_head($meta['accountName'] ?? '', 'User Variance Summary', '', ($meta['startDay'] ?? '') . ' – ' . ($meta['endDay'] ?? ''))
        . '<table><thead><tr>
            <th style="width:28%">Unit</th><th style="width:12%">Director</th><th style="width:12%">Manager</th>
            <th style="width:9%">Reports</th><th style="width:15%">User</th>
            <th style="width:12%">Avg RN Var</th><th style="width:12%">Avg Grid Var</th>
           </tr></thead><tbody>' . $tbody . '</tbody></table></body></html>';

    _dompdf_render($html, 'UserVarianceSummary.pdf', 'portrait');
}

// ─── 2. Crash Cart Summary (allcrash) ────────────────────────────────────────

function allCrashDompdf() {
    $userId    = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
    $deptId    = isset($_REQUEST['r']) ? intval($_REQUEST['r']) : 0;

    $whereAdd = $deptId > 0 ? "AND d.id={$deptId}" : "AND d.unitCategory!=4";
    $meta = _account_meta($userId, $accountId, $deptId);

    $rows = Config::get('db')->get_results(
        "SELECT c.*, IFNULL(c.submittedBy,'') AS submittedBy,
                (CASE WHEN c.submittedDateTime='0000-00-00 00:00:00' THEN '' ELSE DATE_FORMAT(c.submittedDatetime,'%m-%d-%y %H:%i') END) AS nDate,
                DATE_FORMAT(CURDATE(),'%m%d%y') AS filedate,
                IFNULL(z.last_name,'') AS manager,
                l.title, a.name AS accountName,
                DATE_FORMAT(c.dayDate,'%m/%d/%y') AS crashDate,
                d.dept AS deptName
         FROM productivecrashLog c
         LEFT JOIN productivelogData l ON l.id = c.logId
         LEFT JOIN ProductiveDept d ON d.id = c.deptId
         LEFT JOIN productiveDeptXref x ON x.deptId = c.deptId AND x.primaryUnit=6
         LEFT JOIN productiveAccount a ON a.id = d.accountId
         LEFT JOIN productiveUser r ON r.id={$userId}
         LEFT JOIN productiveUser z ON z.id = x.userId
         WHERE l.logType=1 AND c.dayDate>=r.lastStartDate AND c.dayDate<=r.lastEndDate
           AND l.active>0 AND d.active>0 AND d.active<4 AND d.accountId={$accountId} {$whereAdd}
         ORDER BY d.dept ASC, c.dayDate DESC, c.shift DESC"
    );

    $tbody = '';
    foreach ($rows as $r) {
        $tbody .= sprintf(
            '<tr><td>%s</td><td>%s</td><td class="c">%s</td><td class="c">%s</td><td class="c">%s</td><td class="c">%s</td></tr>',
            htmlspecialchars($r['deptName']),
            htmlspecialchars($r['title'] ?? ''),
            htmlspecialchars($r['manager']),
            htmlspecialchars($r['crashDate']),
            htmlspecialchars($r['submittedBy']),
            htmlspecialchars($r['nDate'])
        );
    }

    $html = '<html><head><style>' . _report_css() . '</style></head><body>'
        . _report_head($meta['accountName'] ?? '', 'Crash Cart Summary', $deptId > 0 ? ($meta['deptName'] ?? '') : '', ($meta['startDay'] ?? '') . ' – ' . ($meta['endDay'] ?? ''))
        . '<table><thead><tr>
            <th style="width:22%">Unit</th><th style="width:32%">Task</th><th style="width:13%">Leader</th>
            <th style="width:11%">Date (Due)</th><th style="width:11%">Submitted By</th><th style="width:11%">Date / Time</th>
           </tr></thead><tbody>' . $tbody . '</tbody></table></body></html>';

    _dompdf_render($html, 'CrashCartSummary.pdf', 'portrait');
}

// ─── 3. Shift Variance Summary (allshift) ────────────────────────────────────

function allShiftDompdf() {
    $userId    = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
    $shiftId   = isset($_REQUEST['s']) ? intval($_REQUEST['s']) : 0;

    $shiftAdd = $shiftId > 0 ? "AND n.deptID={$shiftId}" : '';
    $meta = _account_meta($userId, $accountId, $shiftId);

    $baseWhere = "d.active>0 AND d.prodMeasure!=2 AND n.dayDate>=z.lastStartDate AND n.dayDate<=z.lastEndDate AND n.accountId={$accountId} {$shiftAdd}";
    $baseFrom = "FROM productiveNewData n
        LEFT JOIN ProductiveDept d ON d.id=n.deptId
        LEFT JOIN productiveAccount a ON a.id=n.accountId
        LEFT JOIN productiveUser i ON i.id=(SELECT userId FROM productiveDeptXref WHERE deptId=d.id AND primaryUnit=6 AND unitAssigned=0 ORDER BY primaryUnit ASC LIMIT 1)
        LEFT JOIN productiveUser u ON u.id=(SELECT userId FROM productiveDeptXref WHERE deptId=d.id AND primaryUnit=7 ORDER BY primaryUnit ASC LIMIT 1)
        LEFT JOIN productiveUser z ON z.id={$userId}
        LEFT JOIN productiveShifts s ON s.id=n.shift";
    $baseSelect = "SELECT d.dept AS deptName, i.last_name AS Manager,
        IFNULL(SUM(CASE WHEN n.userId>0 AND n.activeRecord>0 THEN 1 ELSE 0 END),0) AS report,
        SUM(CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END) AS totalreports,
        IFNULL(ROUND(SUM(CASE WHEN n.userId>0 THEN n.nvariance ELSE 0 END)/NULLIF(SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END),0),1),99) AS varAvg,
        IFNULL(ROUND(SUM(CASE WHEN n.userId>0 THEN n.gvariance ELSE 0 END)/NULLIF(SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END),0),1),99) AS varGrid";

    $dataAll   = Config::get('db')->get_results("{$baseSelect} {$baseFrom} WHERE {$baseWhere} GROUP BY n.accountId, n.deptId ORDER BY d.dept ASC");
    $dataDays  = Config::get('db')->get_results("{$baseSelect} {$baseFrom} WHERE n.shift>14 AND n.shift<=38 AND {$baseWhere} GROUP BY n.accountId, n.deptId ORDER BY d.dept ASC");
    $dataNights= Config::get('db')->get_results("{$baseSelect} {$baseFrom} WHERE (n.shift>38 OR n.shift<=14) AND {$baseWhere} GROUP BY n.accountId, n.deptId ORDER BY d.dept ASC");

    $cols = '<th style="width:40%">Unit</th><th style="width:20%">Manager</th><th style="width:13%">Reports</th><th style="width:13%">Avg RN Var</th><th style="width:14%">Avg Grid Var</th>';

    $buildRows = function($data, $label) {
        $out = "<h3 class='section'>{$label}</h3><table><thead><tr>";
        $out .= '<th style="width:40%">Unit</th><th style="width:20%">Manager</th><th style="width:13%">Reports</th><th style="width:13%">Avg RN Var</th><th style="width:14%">Avg Grid Var</th>';
        $out .= '</tr></thead><tbody>';
        foreach ($data as $r) {
            $out .= sprintf(
                '<tr><td>%s</td><td>%s</td><td class="c">%s/%s</td><td class="c">%s</td><td class="c">%s</td></tr>',
                htmlspecialchars($r['deptName']),
                htmlspecialchars($r['Manager'] ?? ''),
                $r['report'], $r['totalreports'],
                intval($r['varAvg']) == 99 ? '' : $r['varAvg'],
                intval($r['varGrid']) == 99 ? '' : $r['varGrid']
            );
        }
        $out .= '</tbody></table>';
        return $out;
    };

    $html = '<html><head><style>' . _report_css() . '</style></head><body>'
        . _report_head($meta['accountName'] ?? '', 'Shift Variance Summary', '', ($meta['startDay'] ?? '') . ' – ' . ($meta['endDay'] ?? ''))
        . $buildRows($dataAll, 'All Shifts')
        . $buildRows($dataDays, 'Days')
        . $buildRows($dataNights, 'Nights')
        . '</body></html>';

    _dompdf_render($html, 'ShiftVarianceSummary.pdf', 'portrait');
}

// ─── 4. Unit Variance Summary (allunit) ──────────────────────────────────────

function allrprtDompdf() {
    $userId    = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
    $meta = _account_meta($userId, $accountId);

    $rows = Config::get('db')->get_results(
        "SELECT d.dept AS deptName,
                u.last_name AS Director, i.last_name AS Manager,
                SUM(CASE WHEN n.userId>0 AND n.activeRecord>0 THEN 1 ELSE 0 END) AS report,
                SUM(CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END) AS totalreports,
                ((SUM(CASE WHEN (n.userId>0 AND n.activeRecord>0) THEN 1 ELSE 0 END) +
                  CASE WHEN n.userId=0 AND n.activeRecord>0 AND n.planSubmitted=1 THEN 1 ELSE 0 END) /
                 NULLIF(SUM(CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END),0) * 100) AS compliance,
                ROUND(SUM(CASE WHEN n.userId>0 THEN n.nvariance ELSE 0 END)/NULLIF(SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END),0),2) AS varAvg,
                ROUND(SUM(CASE WHEN n.userId>0 THEN n.gvariance ELSE 0 END)/NULLIF(SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END),0),2) AS gridAvg
         FROM productiveNewData n
         LEFT JOIN ProductiveDept d ON d.id=n.deptId
         LEFT JOIN productiveAccount a ON a.id=n.accountId
         LEFT JOIN productiveUser i ON i.id=(SELECT userId FROM productiveDeptXref WHERE deptId=d.id AND primaryUnit=6 AND unitAssigned=0 ORDER BY primaryUnit ASC LIMIT 1)
         LEFT JOIN productiveUser u ON u.id=(SELECT userId FROM productiveDeptXref WHERE deptId=d.id AND primaryUnit=7 ORDER BY primaryUnit ASC LIMIT 1)
         LEFT JOIN productiveUser z ON z.id={$userId}
         WHERE d.active>0 AND d.prodMeasure!=2 AND d.prodMeasure!=3
           AND n.dayDate>=z.lastStartDate AND n.dayDate<=z.lastEndDate AND n.accountId={$accountId}
         GROUP BY n.accountId, n.deptId
         ORDER BY d.dept ASC"
    );

    $tbody = '';
    foreach ($rows as $r) {
        $compliance = floatval($r['compliance']) > 0 ? round($r['compliance']) . '%' : 'No Data';
        $tbody .= sprintf(
            '<tr><td>%s</td><td>%s</td><td>%s</td><td class="c">%s</td><td class="c">%s</td><td class="c">%s</td><td class="c">%s</td></tr>',
            htmlspecialchars($r['deptName']),
            htmlspecialchars($r['Director'] ?? ''),
            htmlspecialchars($r['Manager'] ?? ''),
            $r['report'],
            $compliance,
            $r['varAvg'] ?? '',
            $r['gridAvg'] ?? ''
        );
    }

    $html = '<html><head><style>' . _report_css() . '</style></head><body>'
        . _report_head($meta['accountName'] ?? '', 'Unit Variance Summary', '', ($meta['startDay'] ?? '') . ' – ' . ($meta['endDay'] ?? ''))
        . '<table><thead><tr>
            <th style="width:26%">Unit</th><th style="width:13%">Director</th><th style="width:13%">Manager</th>
            <th style="width:11%">Reports</th><th style="width:13%">Compliance</th>
            <th style="width:12%">RN Variance</th><th style="width:12%">Grid Variance</th>
           </tr></thead><tbody>' . $tbody . '</tbody></table></body></html>';

    _dompdf_render($html, 'UnitVarianceSummary.pdf', 'portrait');
}

// ─── 5. Unit Variance Detail (allunits) ──────────────────────────────────────

function allUnitsDompdf() {
    $userId    = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
    $deptNum   = isset($_REQUEST['u']) ? intval($_REQUEST['u']) : 0;
    $meta = _account_meta($userId, $accountId, $deptNum);

    $whereAdd = $deptNum > 0 ? "AND n.deptId={$deptNum}" : '';
    $groupBy  = $deptNum > 0 ? "n.accountId, n.deptId, n.dayDate" : "n.accountId, n.deptId";
    $orderBy  = $deptNum > 0 ? "ORDER BY n.dayDate DESC" : "ORDER BY d.dept ASC";

    $rows = Config::get('db')->get_results(
        "SELECT d.dept AS deptName, d.prodMeasure,
                i.last_name AS Manager,
                DATE_FORMAT(n.dayDate,'%m/%d/%y') AS newDate,
                DATE_FORMAT(CURDATE(),'%m%d%y') AS filedate,
                SUM(CASE WHEN n.userId>0 AND n.activeRecord>0 THEN 1 ELSE 0 END) AS report,
                SUM(CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END) AS totalreports,
                ((SUM(CASE WHEN (n.userId>0 AND n.activeRecord>0) THEN 1 ELSE 0 END) +
                  CASE WHEN n.userId=0 AND n.activeRecord>0 AND n.planSubmitted=1 THEN 1 ELSE 0 END) /
                 NULLIF(SUM(CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END),0) * 100) AS compliance,
                IFNULL(ROUND(SUM(CASE WHEN n.userId>0 AND n.atotal>0 AND n.shift=d.shift THEN n.atotal ELSE 0 END)/NULLIF(SUM(CASE WHEN n.userId>0 AND n.atotal>0 AND n.shift=d.shift THEN 1 ELSE 0 END),0),1),99) AS censusShift,
                IFNULL(ROUND(SUM(CASE WHEN n.userId>0 THEN n.atotal ELSE 0 END)/NULLIF(SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END),0),1),99) AS censusAvg,
                IFNULL(ROUND(SUM(CASE WHEN n.userId>0 THEN n.nvariance ELSE 0 END)/NULLIF(SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END),0),1),99) AS varAvg,
                IFNULL(ROUND(SUM(CASE WHEN n.userId>0 THEN n.gvariance ELSE 0 END)/NULLIF(SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END),0),1),99) AS varGrid,
                IFNULL(ROUND(SUM(CASE WHEN n.userId>0 THEN n.hrsVariance ELSE 0 END)/NULLIF(SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END),0),1),99) AS hrsvarAvg
         FROM productiveNewData n
         LEFT JOIN ProductiveDept d ON d.id=n.deptId
         LEFT JOIN productiveAccount a ON a.id=n.accountId
         LEFT JOIN productiveUser i ON i.id=(SELECT userId FROM productiveDeptXref WHERE deptId=d.id AND primaryUnit=6 AND unitAssigned=0 ORDER BY primaryUnit ASC LIMIT 1)
         LEFT JOIN productiveUser z ON z.id={$userId}
         WHERE d.active>0 AND n.dayDate>=z.lastStartDate AND n.dayDate<=z.lastEndDate AND n.accountId={$accountId} {$whereAdd}
         GROUP BY {$groupBy} {$orderBy}"
    );

    // Detect if any row uses prodMeasure != 2/3 to decide columns
    $hasNursing = false;
    foreach ($rows as $r) {
        if (intval($r['prodMeasure']) != 2 && intval($r['prodMeasure']) != 3) {
            $hasNursing = true; break;
        }
    }

    $thead = $hasNursing
        ? '<th style="width:20%">Unit</th><th style="width:10%">Manager</th><th style="width:8%">Submitted</th><th style="width:8%">Total</th><th style="width:7%">Comp %</th><th style="width:8%">Date</th><th style="width:9%">Census</th><th style="width:9%">Avg Census</th><th style="width:10%">Avg RN Var</th><th style="width:11%">Avg Grid Var</th>'
        : '<th style="width:26%">Unit</th><th style="width:14%">Manager</th><th style="width:12%">Submitted</th><th style="width:12%">Total</th><th style="width:12%">Comp %</th><th style="width:12%">Date</th><th style="width:12%">Avg Variance</th>';

    $tbody = '';
    foreach ($rows as $r) {
        $comp = floatval($r['compliance']) > 0 ? round($r['compliance']) . '%' : 'No Data';
        $pm = intval($r['prodMeasure']);
        if ($pm != 2 && $pm != 3) {
            $tbody .= sprintf(
                '<tr><td>%s</td><td>%s</td><td class="c">%s</td><td class="c">%s</td><td class="c">%s</td><td class="c">%s</td><td class="c">%s</td><td class="c">%s</td><td class="c">%s</td><td class="c">%s</td></tr>',
                htmlspecialchars($r['deptName']),
                htmlspecialchars($r['Manager'] ?? ''),
                $r['report'], $r['totalreports'], $comp, $r['newDate'],
                intval($r['censusShift']) == 99 ? '' : $r['censusShift'],
                intval($r['censusAvg']) == 99 ? '' : $r['censusAvg'],
                intval($r['varAvg']) == 99 ? '' : $r['varAvg'],
                intval($r['varGrid']) == 99 ? '' : $r['varGrid']
            );
        } else {
            $tbody .= sprintf(
                '<tr><td>%s</td><td>%s</td><td class="c">%s</td><td class="c">%s</td><td class="c">%s</td><td class="c">%s</td><td class="c">%s</td></tr>',
                htmlspecialchars($r['deptName']),
                htmlspecialchars($r['Manager'] ?? ''),
                $r['report'], $r['totalreports'], $comp, $r['newDate'],
                intval($r['hrsvarAvg']) == 99 ? '' : $r['hrsvarAvg']
            );
        }
    }

    $html = '<html><head><style>' . _report_css() . '</style></head><body>'
        . _report_head($meta['accountName'] ?? '', 'Unit Variance Detail', $deptNum > 0 ? ($meta['deptName'] ?? '') : '', ($meta['startDay'] ?? '') . ' – ' . ($meta['endDay'] ?? ''))
        . "<table><thead><tr>{$thead}</tr></thead><tbody>{$tbody}</tbody></table></body></html>";

    _dompdf_render($html, 'UnitVarianceDetail.pdf', 'landscape');
}

// ─── 6. Escalation Summary (allescalation) ───────────────────────────────────

function getEscalationDompdf() {
    $userId    = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
    $reportId  = isset($_REQUEST['r']) ? intval($_REQUEST['r']) : 0;

    if ($reportId == 1) {
        $whereAdd = "WHERE e.active>0 AND e.accountId={$accountId} AND d.active>0
                     AND d.id IN (SELECT deptId FROM productiveDeptXref WHERE userId={$userId} AND primaryUnit>0)
                     AND DATE(e.dateSubmitted)>=y.lastStartDate AND DATE(e.dateSubmitted)<=y.lastEndDate";
        $fname = 'MYUNITSESC';
    } elseif ($reportId == 0) {
        $whereAdd = "WHERE e.active>0 AND e.accountId={$accountId} AND d.active>0
                     AND DATE(e.dateSubmitted)>=y.lastStartDate AND DATE(e.dateSubmitted)<=y.lastEndDate";
        $fname = 'ALLUNITSESC';
    } else {
        $whereAdd = "WHERE e.active>0 AND e.accountId={$accountId} AND e.deptId={$reportId}
                     AND DATE(e.dateSubmitted)>=y.lastStartDate AND DATE(e.dateSubmitted)<=y.lastEndDate";
        $fname = 'UNITESC';
    }

    $deptMeta = $reportId > 1
        ? Config::get('db')->get_results("SELECT d.dept, a.name AS accountName, y.lastStartDate AS startDay, y.lastEndDate AS endDay FROM ProductiveDept d LEFT JOIN productiveAccount a ON a.id=d.accountId LEFT JOIN productiveUser y ON y.id={$userId} WHERE d.id={$reportId} LIMIT 1")
        : Config::get('db')->get_results("SELECT a.name AS accountName, y.lastStartDate AS startDay, y.lastEndDate AS endDay FROM productiveAccount a LEFT JOIN productiveUser y ON y.id={$userId} WHERE a.id={$accountId} LIMIT 1");

    $meta = $deptMeta ? $deptMeta[0] : [];

    $rows = Config::get('db')->get_results(
        "SELECT e.note, e.dateSubmitted, e.active,
                DATE_FORMAT(e.dateSubmitted,'%m%d%y') AS newDate,
                IFNULL(n.submittedby,'') AS submittedby,
                IFNULL(u.first_name,'') AS first_name, IFNULL(u.last_name,'') AS last_name,
                a.escalation AS escType,
                c.last_name AS closedLast, c.first_name AS closedFirst, e.closedDate,
                d.dept
         FROM productiveEscalations e
         LEFT JOIN productiveUser c ON c.id=e.closedId
         LEFT JOIN productiveAcctEscalations a ON a.id=e.escalation
         LEFT JOIN productiveNewData n ON n.id=e.dataId
         LEFT JOIN productiveUser y ON y.id={$userId}
         LEFT JOIN ProductiveDept d ON d.id=e.deptId
         LEFT JOIN productiveUser u ON u.id=e.userId
         {$whereAdd}
         GROUP BY e.id ORDER BY e.dateSubmitted DESC"
    );

    $tbody = '';
    foreach ($rows as $r) {
        $submittedBy = strlen($r['submittedby']) > 0 ? $r['submittedby'] : trim($r['first_name'] . ' ' . $r['last_name']);
        $type = intval($r['active']) == 2
            ? $r['escType'] . " (Closed by {$r['closedFirst']} {$r['closedLast']} on {$r['closedDate']})"
            : $r['escType'];
        $tbody .= sprintf(
            '<tr><td class="c">%s</td><td>%s</td><td>%s</td><td>%s</td><td class="note">%s</td></tr>',
            htmlspecialchars($r['dateSubmitted']),
            htmlspecialchars($r['dept']),
            htmlspecialchars($submittedBy),
            htmlspecialchars($type ?? ''),
            nl2br(htmlspecialchars($r['note'] ?? ''))
        );
    }

    $startDay = isset($meta['startDay']) ? date('m/d/y', strtotime($meta['startDay'])) : '';
    $endDay   = isset($meta['endDay'])   ? date('m/d/y', strtotime($meta['endDay']))   : '';
    $deptLabel = ($reportId > 1 && isset($meta['dept'])) ? $meta['dept'] : '';

    $html = '<html><head><style>' . _report_css() . '</style></head><body>'
        . _report_head($meta['accountName'] ?? '', 'Escalation Summary', $deptLabel, $startDay . ' – ' . $endDay)
        . '<table><thead><tr>
            <th style="width:13%">Date / Time</th><th style="width:14%">Unit</th>
            <th style="width:13%">Submitted By</th><th style="width:18%">Type</th>
            <th>Comments</th>
           </tr></thead><tbody>' . $tbody . '</tbody></table></body></html>';

    _dompdf_render($html, $fname . '.pdf', 'landscape');
}
