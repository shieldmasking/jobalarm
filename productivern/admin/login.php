<?php
ini_set('display_errors', 0);
session_name('PRNADMIN');
session_set_cookie_params(['lifetime' => 0, 'path' => '/admin/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict']);
session_start();

if (!empty($_SESSION['platform_admin'])) {
    header('location: /admin/index.php');
    exit();
}

require_once __DIR__ . '/../inc/class.db.php';
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/ratelimit.php';

$login_error = 0;

if (isset($_POST['adminlogin'])) {
    $rlKey = 'padmin_' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
    if (!prd_rate_check($rlKey)) {
        $login_error = 2;
    } else {
        $rawPassword = $_POST['password'] ?? '';
        $rawUser     = htmlspecialchars($_POST['username'] ?? '');
        $username    = Config::get('db')->filter($rawUser);

        $row = Config::get('db')->get_results(
            "SELECT * FROM `platform_admins` WHERE (`email`='{$username}' OR `username`='{$username}') AND `active`=1 LIMIT 1"
        );

        $passwordValid = false;
        if ($row && !empty($row[0]['pwd'])) {
            $passwordValid = password_verify($rawPassword, $row[0]['pwd']);
        }

        if ($passwordValid) {
            $_SESSION['platform_admin'] = [
                'id'         => $row[0]['id'],
                'email'      => $row[0]['email'],
                'username'   => $row[0]['username'],
                'first_name' => $row[0]['first_name'],
                'last_name'  => $row[0]['last_name'],
            ];
            Config::get('db')->update('platform_admins', ['lastlogin' => date('Y-m-d H:i:s')], ['id' => $row[0]['id']], 1);
            prd_rate_clear($rlKey);
            header('location: /admin/index.php');
            exit();
        } else {
            prd_rate_hit($rlKey);
            $login_error = 1;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <title>ProductivRN — Platform Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex,nofollow">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
      background: #0f1525;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      -webkit-font-smoothing: antialiased;
    }
    .login-wrap { width: 100%; max-width: 400px; padding: 1rem; }
    .brand {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.6rem;
      margin-bottom: 1.75rem;
    }
    .brand-name { font-size: 1.3rem; font-weight: 800; color: #e5e7eb; letter-spacing: -0.02em; }
    .brand-name span { color: #60a5fa; }
    .badge-admin {
      font-size: 0.65rem;
      font-weight: 700;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      background: rgba(96,165,250,0.15);
      color: #60a5fa;
      border: 1px solid rgba(96,165,250,0.3);
      border-radius: 4px;
      padding: 2px 7px;
      margin-left: 0.25rem;
    }
    .card {
      background: #1a2235;
      border: 1px solid rgba(255,255,255,0.07);
      border-radius: 12px;
      padding: 2rem 1.75rem;
    }
    .card h2 { font-size: 1.05rem; font-weight: 700; color: #f1f5f9; margin-bottom: 0.3rem; }
    .card .subtitle { font-size: 0.82rem; color: #64748b; margin-bottom: 1.5rem; }
    .error-msg {
      background: rgba(239,68,68,0.1);
      border: 1px solid rgba(239,68,68,0.3);
      border-radius: 6px;
      color: #fca5a5;
      font-size: 0.83rem;
      padding: 0.6rem 0.85rem;
      margin-bottom: 1.1rem;
    }
    .field { margin-bottom: 1rem; }
    .field label { display: block; font-size: 0.78rem; font-weight: 600; color: #94a3b8; margin-bottom: 0.35rem; }
    .field input {
      width: 100%;
      padding: 0.62rem 0.8rem;
      border: 1.5px solid rgba(255,255,255,0.08);
      border-radius: 6px;
      font-size: 0.9rem;
      color: #f1f5f9;
      background: rgba(255,255,255,0.04);
      outline: none;
      transition: border-color 0.2s, box-shadow 0.2s;
    }
    .field input:focus {
      border-color: #3b82f6;
      box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
    }
    .btn-login {
      width: 100%;
      padding: 0.72rem;
      background: #3b82f6;
      color: #fff;
      border: none;
      border-radius: 6px;
      font-size: 0.92rem;
      font-weight: 700;
      cursor: pointer;
      transition: background 0.2s;
      margin-top: 0.5rem;
    }
    .btn-login:hover { background: #2563eb; }
    footer { margin-top: 1.75rem; font-size: 0.75rem; color: #374151; text-align: center; }
  </style>
</head>
<body>
  <div class="login-wrap">
    <div class="brand">
      <svg width="26" height="26" viewBox="0 0 30 30" fill="none">
        <circle cx="15" cy="15" r="13" stroke="#3b82f6" stroke-width="1.5" fill="rgba(59,130,246,0.08)"/>
        <path d="M15 8v14M8 15h14" stroke="#3b82f6" stroke-width="2" stroke-linecap="round"/>
        <circle cx="15" cy="15" r="3" fill="#3b82f6"/>
      </svg>
      <span class="brand-name">Productiv<span>RN</span><span class="badge-admin">Admin</span></span>
    </div>

    <div class="card">
      <h2>Platform Administration</h2>
      <p class="subtitle">Restricted access — authorized personnel only</p>

      <?php if ($login_error === 1): ?>
        <div class="error-msg">Invalid credentials. Please try again.</div>
      <?php elseif ($login_error === 2): ?>
        <div class="error-msg">Too many failed attempts. Please wait 15 minutes.</div>
      <?php endif; ?>

      <form method="post" action="/admin/login.php" autocomplete="off">
        <input type="hidden" name="adminlogin" value="1">
        <div class="field">
          <label for="username">Email or Username</label>
          <input type="text" id="username" name="username" autocomplete="off" required>
        </div>
        <div class="field">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" autocomplete="off" required>
        </div>
        <button type="submit" class="btn-login">Sign In</button>
      </form>
      <a href="/admin/forgot.php" style="display:block;text-align:center;margin-top:1rem;font-size:0.82rem;color:#64748b;text-decoration:none;">Forgot password?</a>
    </div>

    <footer>&copy; <?php echo date('Y'); ?> Harrelson Software Group LLC</footer>
  </div>
</body>
</html>
