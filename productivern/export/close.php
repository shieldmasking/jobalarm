<?php
require_once __DIR__ . '/_auth.php';

$accountId = validate_export_token();
$type      = isset($_GET['type']) ? $_GET['type'] : '';
$id        = intval($_GET['id'] ?? 0);

header('Content-Type: application/json');

if (!in_array($type, ['esc', 'safety'], true) || $id < 1) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$db = Config::get('db');

if ($type === 'esc') {
    $check = $db->get_results("SELECT id FROM productiveEscalations WHERE id={$id} AND accountId={$accountId} AND active=1");
    if (!$check) { echo json_encode(['success' => false, 'message' => 'Not found']); exit; }
    $db->update('productiveEscalations', ['active' => 0], ['id' => $id]);
} else {
    $check = $db->get_results("SELECT id FROM productiveSafety WHERE id={$id} AND accountId={$accountId} AND active=1");
    if (!$check) { echo json_encode(['success' => false, 'message' => 'Not found']); exit; }
    $db->update('productiveSafety', ['active' => 0, 'closedDate' => date('Y-m-d H:i:s')], ['id' => $id]);
}

echo json_encode(['success' => true]);
