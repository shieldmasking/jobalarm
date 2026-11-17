<?php
require_once __DIR__ . '/../inc/class.db.php';
require_once __DIR__ . '/../inc/config.php';

function validate_export_token(): int {
    $token = isset($_GET['token']) ? preg_replace('/[^a-f0-9]/', '', $_GET['token']) : '';
    if (strlen($token) !== 64) { http_response_code(401); exit('Unauthorized'); }
    $db = Config::get('db');
    $row = $db->get_results("SELECT accountId FROM export_tokens WHERE token='{$token}' AND active=1 LIMIT 1");
    if (!$row) { http_response_code(401); exit('Unauthorized'); }
    return intval($row[0]['accountId']);
}

function get_date_range(): array {
    $from = isset($_GET['from']) ? preg_replace('/[^0-9\-]/', '', $_GET['from']) : date('Y-m-d');
    $to   = isset($_GET['to'])   ? preg_replace('/[^0-9\-]/', '', $_GET['to'])   : date('Y-m-d');
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $from)) $from = date('Y-m-d');
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $to))   $to   = date('Y-m-d');
    return [$from, $to];
}

function emit_csv(array $headers, array $rows): void {
    header('Content-Type: text/csv; charset=utf-8');
    header('Cache-Control: no-cache');
    $out = fopen('php://output', 'w');
    fputcsv($out, $headers);
    foreach ($rows as $r) fputcsv($out, $r);
    fclose($out);
}
