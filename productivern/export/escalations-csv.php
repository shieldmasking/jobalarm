<?php
require_once __DIR__ . '/_auth.php';

$accountId = validate_export_token();
$db = Config::get('db');

$hasRange = isset($_GET['from']) || isset($_GET['to']);
if ($hasRange) {
    [$from, $to] = get_date_range();
    $where = "e.accountId = {$accountId}
      AND DATE(e.dateSubmitted) BETWEEN '{$from}' AND '{$to}'";
} else {
    $where = "e.accountId = {$accountId}
      AND e.active = 1
      AND (d.active > 0 OR e.deptId IS NULL)";
}

$rows = $db->get_results("
    SELECT
        DATE_FORMAT(e.dateSubmitted,'%m/%d/%Y')          as date,
        IFNULL(d.dept,'')                               as unit,
        IFNULL(a.escalation,'')                         as type,
        IFNULL(e.note,'')                               as notes,
        TRIM(CONCAT(IFNULL(u.first_name,''),' ',IFNULL(u.last_name,''))) as submittedBy,
        TRIM(CONCAT(IFNULL(c.first_name,''),' ',IFNULL(c.last_name,''))) as closedBy,
        IFNULL(e.response,'')                           as response,
        e.deptId                                        as deptId,
        e.id                                            as escalationId
    FROM productiveEscalations e
    LEFT JOIN ProductiveDept           d ON d.id = e.deptId
    LEFT JOIN productiveAcctEscalations a ON a.id = e.escalation
    LEFT JOIN productiveUser            u ON u.id = e.userId
    LEFT JOIN productiveUser            c ON c.id = e.closedId
    WHERE {$where}
    ORDER BY e.dateSubmitted DESC
");

emit_csv(
    ['Date','Unit','Type','Notes','Submitted By','Closed By','Response','Dept ID','Escalation ID'],
    array_map(fn($r) => [
        $r['date'], $r['unit'], $r['type'], $r['notes'],
        $r['submittedBy'], $r['closedBy'], $r['response'],
        $r['deptId'], $r['escalationId'],
    ], $rows ?: [])
);
