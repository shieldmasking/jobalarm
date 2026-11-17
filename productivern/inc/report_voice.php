<?php
use Dompdf\Dompdf;
use Dompdf\Options;

function getVoiceDompdf() {
    require_once '.././vendor/autoload.php';

    $userId    = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
    $reportId  = isset($_REQUEST['r']) ? intval($_REQUEST['r']) : 0;

    if ($reportId == 1) {
        $searchAdd = "AND d.active>0 AND d.id IN (SELECT deptId FROM productiveDeptXref WHERE userId={$userId} AND primaryUnit>0) AND n.dayDate>=u.lastStartDate AND n.dayDate<=u.lastEndDate";
        $fname = 'MYUNITSVOICE';
    } elseif ($reportId == 0) {
        $searchAdd = "AND d.active>0 AND n.dayDate>=u.lastStartDate AND n.dayDate<=u.lastEndDate";
        $fname = 'ALLUNITSVOICE';
    } else {
        $searchAdd = "AND n.deptId={$reportId} AND n.dayDate>=u.lastStartDate AND n.dayDate<=u.lastEndDate";
        $fname = 'UNITVOICE';
    }

    $rows = Config::get('db')->get_results(
        "SELECT n.note, n.nvariance, n.gvariance, d.dept as deptName,
                DATE_FORMAT(n.dayDate,'%m/%d') as newDate,
                s.shift as shiftName,
                IFNULL(n.submittedby, o.last_name) as submittedby,
                DATE_FORMAT(u.lastStartDate,'%m/%d/%y') as startDay,
                DATE_FORMAT(u.lastEndDate,'%m/%d/%y') as endDay,
                a.name as accountName
         FROM productiveNewData n
         LEFT JOIN ProductiveDept d ON d.id = n.deptId
         LEFT JOIN productiveShifts s ON s.id = n.shift
         LEFT JOIN productiveAccount a ON a.id = n.accountId
         LEFT JOIN productiveUser o ON o.id = n.userId
         LEFT JOIN productiveUser u ON u.id = {$userId}
         WHERE n.accountId = {$accountId}
           AND LENGTH(n.note) > 5
           {$searchAdd}
         ORDER BY d.dept ASC, n.dayDate DESC, n.shift DESC"
    );

    $accountName = $rows ? htmlspecialchars($rows[0]['accountName']) : '';
    $dateRange   = $rows ? htmlspecialchars($rows[0]['startDay'] . ' – ' . $rows[0]['endDay']) : '';

    $tableRows = '';
    foreach ($rows as $r) {
        $tableRows .= sprintf(
            '<tr><td>%s</td><td>%s</td><td>%s</td><td class="num">%s</td><td class="num">%s</td><td class="note">%s</td></tr>',
            htmlspecialchars($r['deptName']),
            htmlspecialchars($r['newDate'] . ' – ' . $r['shiftName']),
            htmlspecialchars($r['submittedby'] ?? ''),
            round($r['nvariance'], 1),
            round($r['gvariance'], 1),
            nl2br(htmlspecialchars($r['note']))
        );
    }

    $html = <<<HTML
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
  body { font-family: Arial, sans-serif; font-size: 9pt; margin: 0; }
  h1 { font-size: 13pt; text-align: center; margin: 0 0 2px; }
  h2 { font-size: 11pt; text-align: center; margin: 0 0 2px; }
  p.sub { text-align: center; font-size: 9pt; margin: 0 0 10px; color: #555; }
  table { width: 100%; border-collapse: collapse; margin-top: 8px; }
  th { background: #1a4f9e; color: #fff; padding: 5px 6px; font-size: 8.5pt; text-align: left; }
  td { border: 1px solid #ccc; padding: 4px 6px; vertical-align: top; font-size: 8.5pt; }
  tr:nth-child(even) td { background: #f4f7fb; }
  td.num { text-align: center; white-space: nowrap; }
  td.note { max-width: 220px; }
  th:nth-child(4), th:nth-child(5) { text-align: center; }
</style>
</head>
<body>
  <h1>{$accountName}</h1>
  <h2>Voice of the Unit</h2>
  <p class="sub">{$dateRange}</p>
  <table>
    <thead>
      <tr>
        <th style="width:16%">Unit</th>
        <th style="width:14%">Shift</th>
        <th style="width:11%">User</th>
        <th style="width:9%">RN Var</th>
        <th style="width:9%">Grid Var</th>
        <th>Note</th>
      </tr>
    </thead>
    <tbody>
      {$tableRows}
    </tbody>
  </table>
</body>
</html>
HTML;

    $options = new Options();
    $options->set('isRemotingEnabled', false);
    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('letter', 'landscape');
    $dompdf->render();
    $dompdf->stream($fname . '.pdf', ['Attachment' => true]);
    exit();
}
