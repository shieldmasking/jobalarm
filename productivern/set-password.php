<?php
ini_set('display_errors', 0);
require_once './inc/class.db.php';
require_once './inc/config.php';

$token = trim($_GET['t'] ?? $_POST['t'] ?? '');
$error = '';
$done  = false;

if (!$token || strlen($token) !== 64 || !ctype_xdigit($token)) {
    $error = 'Invalid or missing setup link.';
}

$user = null;
if (!$error) {
    $safe = Config::get('db')->filter($token);
    $rows = Config::get('db')->get_results("SELECT id, first_name, email FROM productiveUser WHERE temp='{$safe}' AND active=1 LIMIT 1");
    if (!$rows) $error = 'This link has already been used or is invalid.';
    else $user = $rows[0];
}

if (!$error && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $pw1       = $_POST['new_password']  ?? '';
    $pw2       = $_POST['cfm_password']  ?? '';
    $agreedTou = isset($_POST['agree_terms']) && $_POST['agree_terms'] === '1';
    if (strlen($pw1) < 8)      $error = 'Password must be at least 8 characters.';
    elseif ($pw1 !== $pw2)     $error = 'Passwords do not match.';
    elseif (!$agreedTou)       $error = 'You must agree to the Terms of Use to continue.';
    else {
        $hashed = password_hash($pw1, PASSWORD_BCRYPT);
        Config::get('db')->update('productiveUser', ['pwd' => $hashed, 'temp' => '', 'termsAcceptedAt' => date('Y-m-d H:i:s')], ['id' => intval($user['id'])]);
        $done = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verify Email &amp; Set Password — ProductivRN</title>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f7f9fc; min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; -webkit-font-smoothing: antialiased; }
    .wrap { width: 100%; max-width: 440px; padding: 1rem; }
    .brand { display: flex; align-items: center; justify-content: center; gap: 0.6rem; margin-bottom: 2rem; text-decoration: none; }
    .brand-name { font-size: 1.4rem; font-weight: 800; color: #0f1525; letter-spacing: -0.02em; }
    .brand-name span { color: #1a4a8a; }
    .card { background: #fff; border-radius: 12px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); padding: 2.25rem 2rem; }
    .card h2 { font-size: 1.1rem; font-weight: 700; color: #0f1525; margin-bottom: 0.3rem; }
    .card .sub { font-size: 0.85rem; color: #6b7280; margin-bottom: 1.75rem; }
    .error-msg { background: #fef2f2; border: 1px solid #fecaca; border-radius: 6px; color: #b91c1c; font-size: 0.85rem; padding: 0.65rem 0.9rem; margin-bottom: 1.25rem; }
    .success-msg { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 6px; color: #15803d; font-size: 0.88rem; padding: 0.75rem 1rem; }
    .field { margin-bottom: 1rem; }
    .field label { display: block; font-size: 0.8rem; font-weight: 600; color: #374151; margin-bottom: 0.4rem; }
    .field input { width: 100%; padding: 0.65rem 0.85rem; border: 1.5px solid #d1d5db; border-radius: 6px; font-size: 0.9rem; color: #0f1525; outline: none; transition: border-color 0.2s; }
    .field input:focus { border-color: #1a4a8a; box-shadow: 0 0 0 3px rgba(26,74,138,0.1); }
    .pw-rules { background: #f8fafc; border: 1px solid #e5e7eb; border-radius: 6px; padding: 0.75rem 1rem; margin-bottom: 1rem; font-size: 0.8rem; color: #6b7280; }
    .pw-rules p { margin: 0.2rem 0; }
    .pw-rules .ok  { color: #15803d; }
    .pw-rules .bad { color: #9ca3af; }
    .btn { width: 100%; padding: 0.75rem; background: #1a4a8a; color: #fff; border: none; border-radius: 6px; font-size: 0.95rem; font-weight: 700; cursor: pointer; margin-top: 0.5rem; transition: background 0.2s; }
    .btn:hover { background: #163d73; }
    .tou-check { display: flex; align-items: flex-start; gap: 0.6rem; margin-bottom: 1rem; }
    .tou-check input[type=checkbox] { margin-top: 3px; flex-shrink: 0; width: 16px; height: 16px; accent-color: #1a4a8a; cursor: pointer; }
    .tou-check label { font-size: 0.82rem; color: #374151; line-height: 1.5; cursor: pointer; }
    .tou-check label a { color: #1a4a8a; font-weight: 600; }
    .verified-badge { display: flex; align-items: center; gap: 0.5rem; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 6px; padding: 0.6rem 0.9rem; margin-bottom: 1.25rem; font-size: 0.82rem; color: #15803d; font-weight: 600; }
    footer { margin-top: 2rem; font-size: 0.78rem; color: #9ca3af; text-align: center; }
  </style>
</head>
<body>
<div class="wrap">
  <a href="/" class="brand">
    <svg width="28" height="28" viewBox="0 0 30 30" fill="none"><circle cx="15" cy="15" r="13" stroke="#1a4a8a" stroke-width="1.5" fill="rgba(26,74,138,0.06)"/><path d="M15 8v14M8 15h14" stroke="#1a4a8a" stroke-width="2" stroke-linecap="round"/><circle cx="15" cy="15" r="3" fill="#1a4a8a"/></svg>
    <span class="brand-name">Productiv<span>RN</span></span>
  </a>
  <div class="card">
    <?php if ($error && !$user): ?>
      <h2>Link Unavailable</h2>
      <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
    <?php elseif ($done): ?>
      <h2>Password Set</h2>
      <div class="success-msg">Your account is ready. <a href="/login.php" style="color:#1a4a8a;font-weight:600">Sign in now &rarr;</a></div>
    <?php else: ?>
      <h2>Verify Email &amp; Set Password</h2>
      <p class="sub">Welcome, <?php echo htmlspecialchars($user['first_name']); ?>. Your email has been verified. Choose a password and agree to the Terms of Use to get started.</p>
      <div class="verified-badge">&#10003; Email verified &mdash; <?php echo htmlspecialchars($user['email']); ?></div>
      <?php if ($error): ?><div class="error-msg"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
      <form method="post">
        <input type="hidden" name="t" value="<?php echo htmlspecialchars($token); ?>">
        <div class="field">
          <label>New Password</label>
          <input type="password" name="new_password" id="new_password" autocomplete="new-password" required>
        </div>
        <div class="pw-rules" id="pw-rules">
          <p id="r-length" class="bad">&#10006; Minimum 8 characters</p>
          <p id="r-upper"  class="bad">&#10006; One uppercase letter</p>
          <p id="r-lower"  class="bad">&#10006; One lowercase letter</p>
          <p id="r-number" class="bad">&#10006; One number</p>
        </div>
        <div class="field">
          <label>Confirm Password</label>
          <input type="password" name="cfm_password" autocomplete="new-password" required>
        </div>
        <div class="tou-check">
          <input type="checkbox" name="agree_terms" id="agree_terms" value="1">
          <label for="agree_terms">I have read and agree to the <a href="/qbl-terms.php" target="_blank">Terms of Use</a>. I understand this tool is a clinical support aid and does not replace clinical judgment.</label>
        </div>
        <button type="submit" class="btn">Set Password &amp; Get Started</button>
      </form>
    <?php endif; ?>
  </div>
  <footer>&copy; <?php echo date('Y'); ?> Harrelson Software Group LLC &nbsp;&middot;&nbsp; <a href="/privacy.html">Privacy</a></footer>
</div>
<script>
var inp = document.getElementById('new_password');
if (inp) inp.addEventListener('input', function() {
  var v = this.value;
  rule('r-length', v.length >= 8);
  rule('r-upper',  /[A-Z]/.test(v));
  rule('r-lower',  /[a-z]/.test(v));
  rule('r-number', /[0-9]/.test(v));
});
function rule(id, ok) {
  var el = document.getElementById(id);
  if (!el) return;
  el.className = ok ? 'ok' : 'bad';
  el.innerHTML = (ok ? '&#10004; ' : '&#10006; ') + el.innerHTML.slice(2);
}
</script>
</body>
</html>
