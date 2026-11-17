<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Page Not Found — ProductivRN</title>
  <meta name="robots" content="noindex, follow">

  <!-- Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-CNFMXB4GYH"></script>
  <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','G-CNFMXB4GYH');</script>

  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
      background: #f7f9fc;
      color: #0f1525;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      -webkit-font-smoothing: antialiased;
    }
    nav {
      background: #fff;
      border-bottom: 1px solid rgba(0,0,0,0.08);
      padding: 1rem 2rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .brand { display: flex; align-items: center; gap: .65rem; text-decoration: none; }
    .brand-name { font-size: 1.15rem; font-weight: 800; color: #0f1525; letter-spacing: -.02em; }
    .brand-name span { color: #1a4f9e; }
    main {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 4rem 2rem;
      text-align: center;
    }
    .wrap { max-width: 480px; }
    .code {
      font-size: 6rem;
      font-weight: 800;
      color: #1a4f9e;
      letter-spacing: -.04em;
      line-height: 1;
      margin-bottom: 1.5rem;
      opacity: .15;
    }
    h1 { font-size: 1.6rem; font-weight: 800; color: #0f1525; margin-bottom: .75rem; letter-spacing: -.02em; }
    p { font-size: .95rem; color: #4a5a72; line-height: 1.7; margin-bottom: 2rem; }
    .actions { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
    .btn-primary {
      padding: .75rem 1.75rem;
      background: #1a4f9e;
      color: #fff;
      border-radius: 6px;
      text-decoration: none;
      font-weight: 700;
      font-size: .9rem;
      transition: background .2s;
    }
    .btn-primary:hover { background: #1d5cb8; }
    .btn-outline {
      padding: .75rem 1.75rem;
      border: 1.5px solid rgba(26,79,158,.3);
      color: #1a4f9e;
      border-radius: 6px;
      text-decoration: none;
      font-weight: 600;
      font-size: .9rem;
      background: #fff;
      transition: border-color .2s;
    }
    .btn-outline:hover { border-color: #1a4f9e; }
    footer {
      text-align: center;
      padding: 1.5rem;
      font-size: .78rem;
      color: #9ca3af;
      border-top: 1px solid rgba(0,0,0,0.06);
      background: #fff;
    }
  </style>
</head>
<body>

<nav>
  <a href="/" class="brand">
    <svg width="26" height="26" viewBox="0 0 30 30" fill="none">
      <circle cx="15" cy="15" r="13" stroke="#1a4f9e" stroke-width="1.5" fill="rgba(26,79,158,0.06)"/>
      <path d="M15 8v14M8 15h14" stroke="#1a4f9e" stroke-width="2" stroke-linecap="round"/>
      <circle cx="15" cy="15" r="3" fill="#1a4f9e"/>
    </svg>
    <span class="brand-name">Productiv<span>RN</span></span>
  </a>
  <a href="/go/index.php" style="font-size:.85rem;color:#1a4f9e;font-weight:600;text-decoration:none;">Client Login</a>
</nav>

<main>
  <div class="wrap">
    <div class="code">404</div>
    <h1>Page not found</h1>
    <p>The page you're looking for doesn't exist or may have moved. Head back to the dashboard or contact us if you need help.</p>
    <div class="actions">
      <a href="/" class="btn-primary">Go to Home</a>
      <a href="/contact.php" class="btn-outline">Contact Us</a>
    </div>
  </div>
</main>

<footer>
  &copy; <?php echo date('Y'); ?> Harrelson Software Group LLC
</footer>

</body>
</html>
