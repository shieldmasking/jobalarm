<?php
require_once __DIR__ . '/../inc/initializer.php';
require_once __DIR__ . '/../inc/class.db.php';
require_once __DIR__ . '/../inc/config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['account'])) { http_response_code(401); exit(json_encode(['error'=>'Unauthorized'])); }

$role      = intval($_SESSION['account']['role']);
$accountId = intval($_SESSION['account']['accountId']);

if ($role < 5) { http_response_code(403); exit(json_encode(['error'=>'Forbidden'])); }

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$db = Config::get('db');

switch ($action) {

    case 'list':
        $rows = $db->get_results("SELECT id, label, token, created_at FROM export_tokens WHERE accountId={$accountId} AND active=1 ORDER BY created_at DESC");
        echo json_encode(['tokens' => $rows ?: []]);
        break;

    case 'create':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit; }
        $label = substr(preg_replace('/[^a-zA-Z0-9 \-_]/', '', $_POST['label'] ?? ''), 0, 100);
        $token = bin2hex(random_bytes(32)); // 64 hex chars
        $db->insert('export_tokens', [
            'accountId'  => $accountId,
            'token'      => $token,
            'label'      => $label,
            'created_at' => date('Y-m-d H:i:s'),
            'active'     => 1,
        ]);
        echo json_encode(['success' => true, 'token' => $token]);
        break;

    case 'revoke':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit; }
        $id = intval($_POST['id'] ?? 0);
        if ($id < 1) { echo json_encode(['error'=>'Invalid id']); break; }
        // Confirm it belongs to this account
        $check = $db->get_results("SELECT id FROM export_tokens WHERE id={$id} AND accountId={$accountId} AND active=1 LIMIT 1");
        if (!$check) { http_response_code(403); exit(json_encode(['error'=>'Not found'])); }
        $db->update('export_tokens', ['active' => 0], ['id' => $id]);
        echo json_encode(['success' => true]);
        break;

    default:
        http_response_code(400);
        echo json_encode(['error' => 'Unknown action']);
}
