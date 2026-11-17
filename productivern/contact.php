<?php
session_start();
$sent = false;
$error = false;

// Generate form token + timestamp on GET
if (!isset($_SESSION['contact_token'])) {
    $_SESSION['contact_token'] = bin2hex(random_bytes(16));
}
$form_token = $_SESSION['contact_token'];

if (isset($_POST['submit'])) {
    $name    = trim($_POST['name'] ?? '');
    $company = trim($_POST['company'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $mobile  = trim($_POST['mobile'] ?? '');
    $text    = trim($_POST['text'] ?? '');

    // Header injection prevention
    $test = "/(content-type|bcc:|cc:|to:)/i";
    $clean = true;
    foreach ($_POST as $val) {
        if (preg_match($test, $val)) { $clean = false; break; }
    }

    // Honeypot — bots fill hidden fields, humans don't
    if (!empty($_POST['website'])) { $clean = false; }
    // Block HTML tags and URLs in message (link spam)
    if (preg_match('/<[a-z]/i', $text) || preg_match('/https?:\/\//i', $text)) { $clean = false; }
    // CSRF token check
    if (empty($_POST['_token']) || $_POST['_token'] !== ($_SESSION['contact_token'] ?? '')) { $clean = false; }
    // Timing check — bots submit in <2s; require at least 3s
    $elapsed = time() - (int)($_POST['_ts'] ?? 0);
    if ($elapsed < 3 || $elapsed > 3600) { $clean = false; }
    // Rate limit — 1 submission per 5 minutes per session
    if (!empty($_SESSION['contact_last_sent']) && (time() - $_SESSION['contact_last_sent']) < 300) { $clean = false; }

    if ($clean && filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($name) > 0 && strlen($text) > 0) {
        $subject = "ProductiveRN Contact: " . $name . ($company ? ", $company" : '');
        $body    = "Name: $name\r\nCompany: $company\r\nEmail: $email\r\nPhone: $mobile\r\n\r\n$text";
        mail('rstrenger@productivern.com', $subject, $body, "From: ProductiveRN Contact <rstrenger@productivern.com>");
        $_SESSION['contact_last_sent'] = time();
        $_SESSION['contact_token'] = bin2hex(random_bytes(16)); // rotate token after use
        $form_token = $_SESSION['contact_token'];
        $sent = true;
    } else {
        $error = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-CNFMXB4GYH"></script>
  <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','G-CNFMXB4GYH');</script>
  <title>Contact ProductivRN — Request a Demo or BAA</title>
  <meta name="description" content="Contact the ProductivRN team to request a demo, ask about Joint Commission NPG 12 compliance, or get a Business Associate Agreement (BAA) for your hospital.">
  <link rel="canonical" href="https://www.productivern.com/contact.php">
  <meta name="robots" content="index, follow">
  <meta property="og:type" content="website">
  <meta property="og:url" content="https://www.productivern.com/contact.php">
  <meta property="og:title" content="Contact ProductivRN — Request a Demo or BAA">
  <meta property="og:description" content="Get in touch to request a demo, ask about NPG 12 compliance, or obtain a BAA for your healthcare organization.">
  <meta property="og:site_name" content="ProductivRN">
  <meta name="twitter:card" content="summary">
  <meta name="twitter:title" content="Contact ProductivRN">
  <meta name="twitter:description" content="Request a demo or BAA for ProductivRN — the Joint Commission NPG 12 compliant nurse staffing platform.">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
      background: #f7f9fc;
      color: #1a1f2e;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      -webkit-font-smoothing: antialiased;
    }

    /* NAV */
    .nav {
      background: #fff;
      border-bottom: 1px solid #e5e7eb;
      padding: 0 2rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      height: 60px;
      position: sticky;
      top: 0;
      z-index: 100;
    }
    .nav-brand { display: flex; align-items: center; gap: .55rem; text-decoration: none; }
    .nav-brand-name { font-size: 1.15rem; font-weight: 800; color: #0f1525; letter-spacing: -.02em; }
    .nav-brand-name span { color: #1a4f9e; }
    .nav-links { display: flex; gap: 1.5rem; align-items: center; }
    .nav-links a { font-size: .85rem; color: #4b5563; text-decoration: none; font-weight: 500; }
    .nav-links a:hover { color: #1a4f9e; }
    .btn-login { background: #1a4f9e; color: #fff !important; padding: .45rem 1.1rem; border-radius: 6px; font-size: .85rem; font-weight: 600; }
    .btn-login:hover { background: #1d5cb8 !important; }

    /* MAIN */
    main {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 3rem 1.5rem;
    }
    .wrap { width: 100%; max-width: 520px; }

    h1 {
      font-size: 1.6rem;
      font-weight: 800;
      color: #0f1525;
      letter-spacing: -.02em;
      margin-bottom: .35rem;
    }
    .subtitle { font-size: .9rem; color: #6b7280; margin-bottom: 2rem; line-height: 1.55; }

    .card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 24px rgba(0,0,0,.08), 0 1px 4px rgba(0,0,0,.04);
      padding: 2rem;
    }

    .field { margin-bottom: 1rem; }
    .field label { display: block; font-size: .8rem; font-weight: 600; color: #374151; margin-bottom: .4rem; }
    .field input, .field textarea {
      width: 100%;
      padding: .65rem .85rem;
      border: 1.5px solid #d1d5db;
      border-radius: 6px;
      font-size: .9rem;
      color: #0f1525;
      font-family: inherit;
      background: #fff;
      outline: none;
      transition: border-color .2s, box-shadow .2s;
    }
    .field input:focus, .field textarea:focus {
      border-color: #1a4f9e;
      box-shadow: 0 0 0 3px rgba(26,79,158,.1);
    }
    .field textarea { resize: vertical; min-height: 110px; }

    .row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

    .btn-submit {
      width: 100%;
      padding: .75rem;
      background: #1a4f9e;
      color: #fff;
      border: none;
      border-radius: 6px;
      font-size: .95rem;
      font-weight: 700;
      cursor: pointer;
      margin-top: .5rem;
      transition: background .2s;
      font-family: inherit;
    }
    .btn-submit:hover { background: #1d5cb8; }

    .alert {
      padding: .75rem 1rem;
      border-radius: 7px;
      font-size: .875rem;
      margin-bottom: 1.25rem;
    }
    .alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; }
    .alert-error   { background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; }

    /* FOOTER */
    footer {
      text-align: center;
      padding: 1.5rem;
      font-size: .78rem;
      color: #9ca3af;
      border-top: 1px solid #e5e7eb;
      background: #fff;
    }
    footer a { color: #9ca3af; text-decoration: none; }
    footer a:hover { color: #6b7280; }
  </style>
</head>
<body>

<nav class="nav">
  <a href="/" class="nav-brand">
    <svg width="26" height="26" viewBox="0 0 30 30" fill="none">
      <circle cx="15" cy="15" r="13" stroke="#1a4f9e" stroke-width="1.5" fill="rgba(26,79,158,0.06)"/>
      <path d="M15 8v14M8 15h14" stroke="#1a4f9e" stroke-width="2" stroke-linecap="round"/>
      <circle cx="15" cy="15" r="3" fill="#1a4f9e"/>
    </svg>
    <span class="nav-brand-name">Productiv<span>RN</span></span>
  </a>
  <div class="nav-links">
    <a href="/security.php">Security</a>
    <a href="/login.php" class="btn-login">Sign In</a>
  </div>
</nav>

<main>
  <div class="wrap">
    <h1>Contact Us</h1>
    <p class="subtitle">Have a question, need a demo, or want to request a BAA? We'll get back to you promptly.</p>

    <?php if ($sent): ?>
      <div class="alert alert-success">Your message has been sent. We'll be in touch soon.</div>
    <?php elseif ($error): ?>
      <div class="alert alert-error">Please fill in all required fields with a valid email address.</div>
    <?php endif; ?>

    <?php if (!$sent): ?>
    <div class="card">
      <form method="post" action="contact.php">
        <input type="hidden" name="_token" value="<?php echo htmlspecialchars($form_token); ?>">
        <input type="hidden" name="_ts" value="<?php echo time(); ?>">
        <div class="row2">
          <div class="field">
            <label for="name">Name <span style="color:#e74c3c">*</span></label>
            <input type="text" id="name" name="name" placeholder="Jane Smith" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required>
          </div>
          <div class="field">
            <label for="company">Organization</label>
            <input type="text" id="company" name="company" placeholder="General Hospital" value="<?php echo htmlspecialchars($_POST['company'] ?? ''); ?>">
          </div>
        </div>
        <div class="row2">
          <div class="field">
            <label for="email">Email <span style="color:#e74c3c">*</span></label>
            <input type="email" id="email" name="email" placeholder="you@hospital.org" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
          </div>
          <div class="field">
            <label for="mobile">Phone</label>
            <input type="tel" id="mobile" name="mobile" placeholder="(555) 000-0000" value="<?php echo htmlspecialchars($_POST['mobile'] ?? ''); ?>">
          </div>
        </div>
        <div class="field">
          <label for="text">Message <span style="color:#e74c3c">*</span></label>
          <textarea id="text" name="text" placeholder="Tell us how we can help..." required><?php echo htmlspecialchars($_POST['text'] ?? ''); ?></textarea>
        </div>
        <div style="display:none" aria-hidden="true">
          <label for="website">Leave this empty</label>
          <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
        </div>
        <button type="submit" name="submit" class="btn-submit">Send Message</button>
      </form>
    </div>
    <?php endif; ?>
  </div>
</main>

<footer>
  &copy; <?php echo date('Y'); ?> Harrelson Software Group LLC &nbsp;&middot;&nbsp;
  <a href="/privacy.html">Privacy Policy</a> &nbsp;&middot;&nbsp;
  <a href="/security.php">Security &amp; Trust</a>
</footer>

</body>
</html>
