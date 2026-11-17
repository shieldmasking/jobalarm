<?php
ini_set('display_errors', 0);
require_once __DIR__ . '/../inc/class.db.php';
require_once __DIR__ . '/../inc/config.php';

$token = trim($_GET['token'] ?? '');
$db    = Config::get('db');
$err   = '';
$done  = false;
$valid = false;

if ($token) {
    $safe = $db->filter($token);
    $row  = $db->get_results("SELECT id FROM platform_admins WHERE reset_token='{$safe}' AND reset_expires > NOW() AND active=1 LIMIT 1");
    $valid = !empty($row);
}

if (!$token || !$valid) {
    $err = 'This reset link is invalid or has expired.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $valid) {
    $pw1 = $_POST['password'] ?? '';
    $pw2 = $_POST['password2'] ?? '';

    if (strlen($pw1) < 8) {
        $err = 'Password must be at least 8 characters.';
    } elseif ($pw1 !== $pw2) {
        $err = 'Passwords do not match.';
    } else {
        $hash = password_hash($pw1, PASSWORD_BCRYPT);
        $db->update('platform_admins', [
            'pwd'           => $hash,
            'reset_token'   => '',
            'reset_expires' => '0000-00-00 00:00:00',
        ], ['id' => $row[0]['id']], 1);
        $done = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>New Password — ProductivRN Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex,nofollow">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #0f1525; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
    .wrap { width: 100%; max-width: 400px; padding: 1rem; }
    .card { background: #1a2235; border: 1px solid rgba(255,255,255,0.07); border-radius: 12px; padding: 2rem 1.75rem; }
    h2 { font-size: 1.05rem; font-weight: 700; color: #f1f5f9; margin-bottom: 0.3rem; }
    .sub { font-size: 0.82rem; color: #64748b; margin-bottom: 1.5rem; }
    .field { margin-bottom: 1rem; }
    .field label { display: block; font-size: 0.78rem; font-weight: 600; color: #94a3b8; margin-bottom: 0.35rem; }
    .field input { width: 100%; padding: 0.62rem 0.8rem; border: 1.5px solid rgba(255,255,255,0.08); border-radius: 6px; font-size: 0.9rem; color: #f1f5f9; background: rgba(255,255,255,0.04); outline: none; }
    .field input:focus { border-color: #3b82f6; }
    .btn { width: 100%; padding: 0.72rem; background: #3b82f6; color: #fff; border: none; border-radius: 6px; font-size: 0.92rem; font-weight: 700; cursor: pointer; margin-top: 0.5rem; }
    .error { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); border-radius: 6px; color: #fca5a5; font-size: 0.83rem; padding: 0.6rem 0.85rem; margin-bottom: 1rem; }
    .success { background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.3); border-radius: 6px; color: #86efac; font-size: 0.85rem; padding: 0.65rem 0.9rem; margin-bottom: 1rem; }
    .back { display: block; text-align: center; margin-top: 1rem; font-size: 0.82rem; color: #64748b; text-decoration: none; }
    .back:hover { color: #94a3b8; }
  </style>
</head>
<body>
<div class="wrap">
  <div class="card">
    <h2>Set New Password</h2>
    <p class="sub">Choose a new password for your admin account.</p>

    <?php if ($done): ?>
      <div class="success">Password updated. You can now sign in.</div>
      <a href="/admin/login.php" class="back">Go to Sign In</a>
    <?php elseif ($err): ?>
      <div class="error"><?php echo htmlspecialchars($err); ?></div>
      <a href="/admin/forgot.php" class="back">Request a new link</a>
    <?php else: ?>
      <form method="post">
        <div class="field">
          <label>New Password</label>
          <input type="password" name="password" minlength="8" required autofocus>
        </div>
        <div class="field">
          <label>Confirm Password</label>
          <input type="password" name="password2" minlength="8" required>
        </div>
        <button type="submit" class="btn">Set Password</button>
      </form>
    <?php endif; ?>
  </div>
</div>
</body>
</html>
