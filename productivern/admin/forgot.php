<?php
ini_set('display_errors', 0);
require_once __DIR__ . '/../inc/class.db.php';
require_once __DIR__ . '/../inc/config.php';

$sent = false;
$err  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $db    = Config::get('db');
    $safe  = $db->filter($email);

    $row = $db->get_results("SELECT id FROM platform_admins WHERE email='{$safe}' AND active=1 LIMIT 1");

    if ($row) {
        $token   = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $db->update('platform_admins', ['reset_token' => $token, 'reset_expires' => $expires], ['id' => $row[0]['id']], 1);

        $link    = 'https://productivern.com/admin/reset.php?token=' . $token;
        $subject = 'ProductivRN Admin — Password Reset';
        $body    = "Click the link below to reset your password (expires in 1 hour):\n\n{$link}\n\nIf you did not request this, ignore this email.";
        $headers = 'From: noreply@productivern.com';
        mail($email, $subject, $body, $headers);
    }

    // Always show success to avoid email enumeration
    $sent = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Reset Password — ProductivRN Admin</title>
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
    .success { background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.3); border-radius: 6px; color: #86efac; font-size: 0.85rem; padding: 0.65rem 0.9rem; margin-bottom: 1rem; }
    .back { display: block; text-align: center; margin-top: 1rem; font-size: 0.82rem; color: #64748b; text-decoration: none; }
    .back:hover { color: #94a3b8; }
  </style>
</head>
<body>
<div class="wrap">
  <div class="card">
    <h2>Reset Password</h2>
    <p class="sub">Enter your admin email and we'll send a reset link.</p>
    <?php if ($sent): ?>
      <div class="success">If that email is registered, a reset link has been sent.</div>
    <?php else: ?>
      <form method="post">
        <div class="field">
          <label>Email Address</label>
          <input type="email" name="email" required autofocus>
        </div>
        <button type="submit" class="btn">Send Reset Link</button>
      </form>
    <?php endif; ?>
    <a href="/admin/login.php" class="back">&larr; Back to Sign In</a>
  </div>
</div>
</body>
</html>
