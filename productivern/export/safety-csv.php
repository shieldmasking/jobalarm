<?php
require_once __DIR__ . '/_auth.php';

$accountId = validate_export_token();
$db = Config::get('db');

$hasRange = isset($_GET['from']) || isset($_GET['to']);
if ($hasRange) {
    [$from, $to] = get_date_range();
    $where = "s.accountId = {$accountId}
      AND s.openedDate BETWEEN '{$from}' AND '{$to}'";
} else {
    $where = "s.accountId = {$accountId}
      AND s.active = 1
      AND (d.active > 0 OR s.deptId IS NULL)";
}

$rows = $db->get_results("
    SELECT
        DATE_FORMAT(s.openedDate,'%m/%d/%Y')                 as date,
        COALESCE(d.dept,'Hospital')                          as unit,
        CONCAT(IFNULL(t.typeDesc,''),' - ',IFNULL(c.safetyDesc,'')) as issueType,
        IFNULL(s.note1,'')                                   as description,
        IFNULL(s.roomId1,'')                                 as location,
        IFNULL(s.periGen1,'')                                as submittedBy,
        CASE WHEN s.priority=1 THEN 'High' ELSE 'Normal' END as priority,
        CASE WHEN s.dueDate IS NOT NULL AND s.dueDate!='0000-00-00'
             THEN DATE_FORMAT(s.dueDate,'%m/%d/%Y') ELSE '' END as dueDate,
        s.deptId                                             as deptId,
        s.id                                                 as safetyId
    FROM productiveSafety s
    LEFT JOIN ProductiveDept          d ON d.id = s.deptId
    LEFT JOIN productivesafetyConfig  c ON c.id = s.safetyConfig
    LEFT JOIN productivesafetyType    t ON t.id = c.safetyType
    WHERE {$where}
    ORDER BY s.openedDate DESC
");

emit_csv(
    ['Date','Unit','Issue Type','Description','Location','Submitted By','Priority','Due Date','Dept ID','Safety ID'],
    array_map(fn($r) => [
        $r['date'], $r['unit'], $r['issueType'], $r['description'],
        $r['location'], $r['submittedBy'], $r['priority'], $r['dueDate'],
        $r['deptId'], $r['safetyId'],
    ], $rows ?: [])
);
