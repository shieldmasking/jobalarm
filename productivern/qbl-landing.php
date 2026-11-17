<?php
require_once 'inc/class.db.php';
require_once 'inc/config.php';
$_ip = $_SERVER['REMOTE_ADDR'] ?? null;
$_ref = isset($_SERVER['HTTP_REFERER']) ? substr($_SERVER['HTTP_REFERER'], 0, 500) : null;
Config::get('db')->insert('qbl_page_views', ['page' => 'landing', 'ip' => $_ip, 'referrer' => $_ref]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- SEO -->
<title>QBL Alerts for OB Teams — Real-Time Hemorrhage Detection | ProductivRN</title>
<meta name="description" content="ProductivRN QBL Alerts delivers instant quantitative blood loss notifications to your entire OB team the moment a hemorrhage threshold is reached. AWHONN and ACOG compliant. No app required.">
<meta name="keywords" content="quantitative blood loss, QBL monitoring, postpartum hemorrhage prevention, OB nursing alerts, hemorrhage detection, AWHONN QBL, ACOG blood loss, labor and delivery alerts, obstetric hemorrhage, maternal safety, QBL calculator, nurse staffing alerts">
<link rel="canonical" href="https://qblalerts.com/">

<!-- Open Graph -->
<meta property="og:type" content="website">
<meta property="og:url" content="https://qbl.productivern.com/qbl-landing.php">
<meta property="og:title" content="QBL Alerts — Real-Time Hemorrhage Detection for OB Teams">
<meta property="og:description" content="Instant quantitative blood loss alerts delivered to your charge nurse, house supervisor, pharmacy, and CNO the moment a threshold is crossed.">
<meta property="og:image" content="https://qbl.productivern.com/og-image.png">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="fb:app_id" content="1409442457242108">
<meta property="og:site_name" content="ProductivRN">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:image" content="https://qbl.productivern.com/og-image.png">
<meta name="twitter:title" content="QBL Alerts — Real-Time Hemorrhage Detection for OB Teams">
<meta name="twitter:description" content="Instant QBL alerts for your entire OB team. AWHONN and ACOG compliant.">

<!-- Structured Data -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "SoftwareApplication",
      "name": "ProductivRN QBL Alerts",
      "url": "https://qbl.productivern.com/",
      "applicationCategory": "HealthApplication",
      "operatingSystem": "Web",
      "description": "Real-time quantitative blood loss (QBL) alerts for labor and delivery teams. Delivers instant postpartum hemorrhage notifications to charge nurses, house supervisors, pharmacy, and CNO the moment a threshold is crossed.",
      "offers": {
        "@type": "Offer",
        "price": "0",
        "priceCurrency": "USD",
        "description": "Free demo available"
      },
      "publisher": {
        "@type": "Organization",
        "name": "ProductivRN",
        "url": "https://productivern.com"
      },
      "featureList": [
        "Real-time QBL threshold alerts via SMS",
        "AWHONN and ACOG compliant QBL calculation",
        "No app download required",
        "Configurable supply lists per unit",
        "Simultaneous alerts to entire care team"
      ],
      "audience": {
        "@type": "MedicalAudience",
        "audienceType": "Labor and Delivery nurses, OB charge nurses, house supervisors, CNOs"
      }
    },
    {
      "@type": "MedicalWebPage",
      "url": "https://qbl.productivern.com/",
      "name": "Real-Time QBL Alerts for Labor & Delivery — Detect Postpartum Hemorrhage",
      "description": "ProductivRN QBL Alerts delivers instant quantitative blood loss notifications to your entire OB team the moment a hemorrhage threshold is reached. AWHONN and ACOG compliant.",
      "medicalAudience": {
        "@type": "MedicalAudience",
        "audienceType": "Clinician"
      },
      "about": {
        "@type": "MedicalCondition",
        "name": "Postpartum Hemorrhage",
        "alternateName": "PPH",
        "code": {
          "@type": "MedicalCode",
          "codeValue": "O72",
          "codingSystem": "ICD-10"
        }
      }
    },
    {
      "@type": "FAQPage",
      "mainEntity": [
        {
          "@type": "Question",
          "name": "What is quantitative blood loss (QBL)?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Quantitative blood loss (QBL) is a standardized method of measuring blood loss during and after delivery by weighing blood-soaked materials and subtracting their dry weight. AWHONN and ACOG recommend QBL measurement for all deliveries to enable early detection of postpartum hemorrhage."
          }
        },
        {
          "@type": "Question",
          "name": "How does ProductivRN QBL Alerts work?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "A nurse scans a QR code on the scale, enters the total weight and supplies used, and the system calculates QBL instantly. If the result exceeds a configured threshold, SMS alerts are sent simultaneously to the charge nurse, house supervisor, pharmacy, and any other designated recipients — all in under 60 seconds."
          }
        },
        {
          "@type": "Question",
          "name": "Is ProductivRN QBL Alerts compliant with AWHONN and ACOG guidelines?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Yes. ProductivRN QBL Alerts uses the gravimetric QBL calculation method recommended by AWHONN and ACOG for all deliveries, and alert thresholds align with their postpartum hemorrhage staging guidelines."
          }
        }
      ]
    }
  ]
}
</script>

<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
:root {
  --navy: #1a4a8a;
  --navy-dark: #163d73;
  --navy-light: #2a5aa0;
  --teal: #0e6b6b;
  --red: #c0392b;
  --bg: #f0f2f5;
  --white: #ffffff;
  --text: #1a2332;
  --muted: #6b7f96;
  --border: #dde1e8;
  --font: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}
html { scroll-behavior: smooth; }
body { font-family: var(--font); background: var(--bg); color: var(--text); line-height: 1.6; -webkit-font-smoothing: antialiased; }

/* ── NAV ── */
nav {
  position: sticky; top: 0; z-index: 100;
  background: var(--navy); padding: 14px 20px;
  display: flex; align-items: center; justify-content: space-between;
  box-shadow: 0 2px 8px rgba(0,0,0,.2);
}
.nav-brand { color: #fff; font-size: 1.15rem; font-weight: 800; letter-spacing: .3px; text-decoration: none; }
.nav-brand span { font-weight: 400; opacity: .75; }
.nav-cta { background: #fff; color: var(--navy); text-decoration: none; font-size: .78rem; font-weight: 700; padding: 7px 14px; border-radius: 20px; letter-spacing: .3px; }
.nav-cta:hover { background: #e8f0fb; }

/* ── HERO ── */
.hero {
  background: linear-gradient(160deg, #0d2f5c 0%, var(--navy) 50%, #1a6060 100%);
  color: #fff; padding: 52px 20px 48px; text-align: center;
}
.hero-eyebrow { font-size: .72rem; font-weight: 700; letter-spacing: 1.2px; text-transform: uppercase; opacity: .7; margin-bottom: 12px; }
.hero h1 { font-size: 1.75rem; font-weight: 900; line-height: 1.15; margin-bottom: 16px; }
.hero h1 em { font-style: normal; color: #7ec8c8; }
.hero-sub { font-size: .95rem; opacity: .85; max-width: 380px; margin: 0 auto 28px; line-height: 1.65; }
.hero-btns { display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; }
.btn-primary { background: #fff; color: var(--navy); text-decoration: none; font-size: .9rem; font-weight: 700; padding: 13px 24px; border-radius: 10px; }
.btn-secondary { background: rgba(255,255,255,.15); color: #fff; text-decoration: none; font-size: .9rem; font-weight: 700; padding: 13px 24px; border-radius: 10px; border: 1px solid rgba(255,255,255,.3); }
.hero-stat-bar { display: flex; justify-content: center; gap: 0; margin-top: 36px; background: rgba(0,0,0,.2); border-radius: 12px; overflow: hidden; }
.hero-stat { flex: 1; padding: 14px 10px; text-align: center; border-right: 1px solid rgba(255,255,255,.1); }
.hero-stat:last-child { border-right: none; }
.hero-stat .num { font-size: 1.3rem; font-weight: 900; color: #7ec8c8; line-height: 1; }
.hero-stat .lbl { font-size: .62rem; opacity: .7; text-transform: uppercase; letter-spacing: .5px; margin-top: 4px; }

/* ── SECTION SHARED ── */
section { padding: 40px 20px; }
.section-label { font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--teal); margin-bottom: 8px; }
.section-title { font-size: 1.3rem; font-weight: 800; color: var(--text); line-height: 1.2; margin-bottom: 10px; }
.section-body { font-size: .88rem; color: var(--muted); line-height: 1.7; }

/* ── CLINICAL IMPORTANCE ── */
.clinical { background: var(--white); }
.stat-cards { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 20px; }
.stat-card { border-radius: 12px; padding: 16px; }
.stat-card.red { background: #fff0f0; border-left: 4px solid var(--red); }
.stat-card.navy { background: #e8f0fb; border-left: 4px solid var(--navy); }
.stat-card.teal { background: #e6f4f4; border-left: 4px solid var(--teal); }
.stat-card.gold { background: #fef9e7; border-left: 4px solid #d4ac0d; }
.stat-card .num { font-size: 1.5rem; font-weight: 900; line-height: 1; margin-bottom: 4px; }
.stat-card.red .num { color: var(--red); }
.stat-card.navy .num { color: var(--navy); }
.stat-card.teal .num { color: var(--teal); }
.stat-card.gold .num { color: #b7950b; }
.stat-card .desc { font-size: .72rem; color: var(--muted); line-height: 1.4; }
.guideline-bar { margin-top: 20px; background: #f8f9fb; border-radius: 12px; padding: 16px; border: 1px solid var(--border); }
.guideline-bar p { font-size: .8rem; color: var(--muted); line-height: 1.6; }
.guideline-bar strong { color: var(--text); }

/* ── HOW IT WORKS ── */
.how { background: var(--bg); }
.steps { margin-top: 20px; display: flex; flex-direction: column; gap: 0; }
.step { display: flex; gap: 14px; align-items: flex-start; padding: 18px; background: var(--white); margin-bottom: 2px; }
.step:first-child { border-radius: 12px 12px 0 0; }
.step:last-child { border-radius: 0 0 12px 12px; margin-bottom: 0; }
.step-num { width: 36px; height: 36px; border-radius: 50%; background: var(--navy); color: #fff; font-size: .9rem; font-weight: 900; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.step-title { font-size: .92rem; font-weight: 700; color: var(--text); margin-bottom: 3px; }
.step-desc { font-size: .8rem; color: var(--muted); line-height: 1.5; }

/* ── FEATURES ── */
.features { background: var(--white); }
.feature-grid { margin-top: 20px; display: flex; flex-direction: column; gap: 10px; }
.feature-item { display: flex; align-items: flex-start; gap: 12px; }
.feature-icon { width: 40px; height: 40px; border-radius: 10px; background: #e8f0fb; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0; }
.feature-title { font-size: .88rem; font-weight: 700; color: var(--text); margin-bottom: 2px; }
.feature-desc { font-size: .8rem; color: var(--muted); line-height: 1.5; }

/* ── QR / DEMO ── */
.demo-section { background: linear-gradient(160deg, #0d2f5c, var(--navy)); color: #fff; text-align: center; }
.demo-section .section-label { color: #7ec8c8; }
.demo-section .section-title { color: #fff; }
.demo-section .section-body { color: rgba(255,255,255,.75); }
#qrcode { display: flex; justify-content: center; margin: 24px 0 16px; }
#qrcode canvas, #qrcode img { border: 8px solid rgba(255,255,255,.15); border-radius: 12px; }
.demo-note { font-size: .75rem; opacity: .6; margin-top: 8px; }
.demo-or { display: flex; align-items: center; gap: 10px; color: rgba(255,255,255,.4); font-size: .75rem; margin: 16px 0; }
.demo-or::before, .demo-or::after { content: ''; flex: 1; height: 1px; background: rgba(255,255,255,.15); }
.btn-contact { display: block; background: rgba(255,255,255,.15); color: #fff; text-decoration: none; font-size: .9rem; font-weight: 700; padding: 13px 24px; border-radius: 10px; border: 1px solid rgba(255,255,255,.3); text-align: center; }

/* ── TESTIMONIAL / TRUST ── */
.trust { background: var(--bg); }
.trust-badges { display: flex; flex-direction: column; gap: 10px; margin-top: 20px; }
.trust-badge { background: var(--white); border-radius: 12px; padding: 16px; display: flex; align-items: center; gap: 12px; box-shadow: 0 1px 4px rgba(0,0,0,.05); }
.trust-badge-icon { font-size: 1.4rem; flex-shrink: 0; }
.trust-badge-text { font-size: .82rem; color: var(--text); line-height: 1.5; }
.trust-badge-text strong { display: block; font-size: .85rem; color: var(--navy); margin-bottom: 2px; }

/* ── FOOTER ── */
footer { background: #0d2040; color: rgba(255,255,255,.5); text-align: center; padding: 24px 20px; font-size: .75rem; line-height: 1.8; }
footer strong { color: rgba(255,255,255,.8); }
footer a { color: rgba(255,255,255,.5); text-decoration: none; }
footer a:hover { color: #fff; }
</style>
</head>
<body>

<!-- NAV -->
<nav>
  <a class="nav-brand" href="https://qbl.productivern.com">Productive<span>RN</span> &mdash; QBL Alerts</a>
  <div style="display:flex;align-items:center;gap:10px;">
    <a style="color:rgba(255,255,255,.8);text-decoration:none;font-size:.82rem;font-weight:600;" href="/qbl-pricing.php">Pricing</a>
    <a style="color:rgba(255,255,255,.8);text-decoration:none;font-size:.82rem;font-weight:600;" href="/qbl-cms-readiness.php">CMS Readiness</a>
    <a style="color:rgba(255,255,255,.8);text-decoration:none;font-size:.82rem;font-weight:600;" href="/login.php?r=qbl">Login</a>
    <a class="nav-cta" href="/qbl-contact.php">Contact Us</a>
  </div>
</nav>

<!-- HERO -->
<section class="hero">
  <div class="hero-eyebrow">Postpartum Hemorrhage Prevention</div>
  <h1>Real-Time QBL Alerts for Labor &amp; Delivery —<br>Detect Postpartum Hemorrhage <em>Before It Becomes Critical</em></h1>
  <p class="hero-sub">Real-time QBL notifications delivered to your entire OB team the moment a blood loss threshold is reached.</p>
  <div class="hero-btns">
    <a class="btn-primary" href="#demo">&#128247; Try the Demo</a>
  </div>
  <div class="hero-stat-bar">
    <div class="hero-stat"><div class="num">#1</div><div class="lbl">Cause of Preventable Maternal Death</div></div>
    <div class="hero-stat"><div class="num">70%</div><div class="lbl">of PPH Occurs in Low-Risk Patients</div></div>
    <div class="hero-stat"><div class="num">&lt;60s</div><div class="lbl">Alert Delivery Time</div></div>
  </div>
</section>

<!-- CLINICAL IMPORTANCE -->
<section class="clinical" id="clinical">
  <div class="section-label">Why QBL Matters</div>
  <h2 class="section-title">Hemorrhage Is Preventable.<br>Delayed Response Is Not.</h2>
  <p class="section-body">Postpartum hemorrhage (PPH) is responsible for more preventable maternal deaths than any other obstetric complication. The difference between a managed hemorrhage and a crisis is time — and time depends on your team knowing immediately.</p>
  <div class="stat-cards">
    <div class="stat-card red"><div class="num">1 in 5</div><div class="desc">OB patients experience PPH — most have no prior risk factors</div></div>
    <div class="stat-card navy"><div class="num">11%</div><div class="desc">of maternal deaths globally are caused by postpartum hemorrhage</div></div>
    <div class="stat-card teal"><div class="num">500ml</div><div class="desc">ACOG threshold for vaginal delivery — early detection starts here</div></div>
    <div class="stat-card gold"><div class="num">Early</div><div class="desc">QBL detection is the single most impactful intervention in PPH management</div></div>
  </div>
  <div class="guideline-bar">
    <p><strong>AWHONN and ACOG recommend</strong> quantitative blood loss measurement for every delivery. Cumulative QBL tracking with real-time escalation is the standard of care — and now it's automated.</p>
  </div>
</section>

<!-- HOW IT WORKS -->
<section class="how" id="how">
  <div class="section-label">How It Works</div>
  <h2 class="section-title">Three Steps.<br>Seconds to Alert.</h2>
  <p class="section-body">No training required. No new devices. Works on any smartphone — and the only setup your nurses need is a QR code on the scale.</p>
  <div class="steps">
    <div class="step">
      <div class="step-num">1</div>
      <div>
        <div class="step-title">Scan the QR Code on the Scale</div>
        <div class="step-desc">A laminated QR code mounted directly on the scale opens the calculator instantly in the phone's browser — pre-configured with your unit's exact supply list.</div>
      </div>
    </div>
    <div class="step">
      <div class="step-num">2</div>
      <div>
        <div class="step-title">Enter Total Weight &amp; Select Supplies</div>
        <div class="step-desc">The nurse reads the weight from the scale, selects the supplies used, and taps Calculate. QBL is computed instantly using the validated gravimetric method.</div>
      </div>
    </div>
    <div class="step">
      <div class="step-num">3</div>
      <div>
        <div class="step-title">Your Entire Team Is Alerted Instantly</div>
        <div class="step-desc">Every configured recipient — charge nurse, house supervisor, pharmacy, anesthesia, CNO — receives a text with a secure tap-to-view link showing the full QBL breakdown. All in under 60 seconds.</div>
      </div>
    </div>
  </div>
</section>

<!-- FEATURES -->
<section class="features" id="features">
  <div class="section-label">Features</div>
  <h2 class="section-title">Built for the Way Your Unit Actually Works</h2>
  <div class="feature-grid">
    <div class="feature-item">
      <div class="feature-icon">📲</div>
      <div><div class="feature-title">Instant SMS Alerts</div><div class="feature-desc">Alerts fire within seconds of calculation — no email, no app push, no delay. Text messages reach your team wherever they are.</div></div>
    </div>
    <div class="feature-item">
      <div class="feature-icon">👥</div>
      <div><div class="feature-title">Multiple Recipients Simultaneously</div><div class="feature-desc">Charge nurse, house supervisor, pharmacy, OB anesthesia, CNO — everyone gets the alert at the same time with their own secure link.</div></div>
    </div>
    <div class="feature-item">
      <div class="feature-icon">🎯</div>
      <div><div class="feature-title">Personalized Thresholds</div><div class="feature-desc">Each team member sets the QBL value that matters to their role. Pharmacy gets alerted at 1000ml. Your charge nurse at 500ml. No unnecessary pages.</div></div>
    </div>
    <div class="feature-item">
      <div class="feature-icon">🔗</div>
      <div><div class="feature-title">Tap-to-View Supply Breakdown</div><div class="feature-desc">Every alert includes a secure link showing the complete QBL breakdown — supply list, quantities, weights, and hemorrhage status.</div></div>
    </div>
    <div class="feature-item">
      <div class="feature-icon">&#128247;</div>
      <div><div class="feature-title">QR Code Deployment</div><div class="feature-desc">We print QR codes specific to your unit's supply configuration. Laminate them, stick them on the scale. Setup takes less than a day and requires zero IT involvement.</div></div>
    </div>
    <div class="feature-item">
      <div class="feature-icon">🔄</div>
      <div><div class="feature-title">Update Alerts</div><div class="feature-desc">If additional supplies are added after the initial calculation, recalculating sends an "Updated" alert so your team always has the most current QBL.</div></div>
    </div>
  </div>
</section>

<!-- TRUST -->
<section class="trust" id="trust">
  <div class="section-label">Designed for Compliance</div>
  <h2 class="section-title">Aligned with National Standards</h2>
  <div class="trust-badges">
    <div class="trust-badge">
      <div class="trust-badge-icon">&#9989;</div>
      <div class="trust-badge-text"><strong>AWHONN Hemorrhage Bundle</strong>Supports cumulative QBL tracking and escalation protocols recommended in the AWHONN Postpartum Hemorrhage Prevention and Treatment bundle.</div>
    </div>
    <div class="trust-badge">
      <div class="trust-badge-icon">&#9989;</div>
      <div class="trust-badge-text"><strong>ACOG Practice Bulletin</strong>QBL thresholds align with ACOG Practice Bulletin No. 183 — 500ml for vaginal delivery, 1000ml for cesarean, with early escalation at elevated ratios.</div>
    </div>
    <div class="trust-badge">
      <div class="trust-badge-icon">&#9989;</div>
      <div class="trust-badge-text"><strong>Gravimetric Method</strong>Uses the validated gravimetric blood loss calculation — total wet weight minus dry supply weight — the same method recommended by ACOG and AWHONN.</div>
    </div>
    <div class="trust-badge">
      <div class="trust-badge-icon">&#9989;</div>
      <div class="trust-badge-text"><strong>No IT Infrastructure Required</strong>Cloud-hosted, browser-based. Works on any smartphone. No hospital network integration, no EMR connection required to get started.</div>
    </div>
  </div>
</section>

<!-- DEMO / QR -->
<section class="demo-section" id="demo">
  <div class="section-label">Live Demo</div>
  <h2 class="section-title">See It Work on Your Phone</h2>
  <p class="section-body">Scan the QR code with your phone camera — no app required. Enter a weight, select supplies, and receive a real QBL alert text in under 30 seconds.</p>
  <div id="qrcode"></div>
  <p class="demo-note">Point your phone camera at the code above</p>
  <div class="demo-or">or</div>
  <a class="btn-demo" href="https://qbl.productivern.com/demo.php" style="display:block;width:100%;background:rgba(255,255,255,.15);color:#fff;border:2px solid rgba(255,255,255,.4);border-radius:10px;padding:14px;font-size:1rem;font-weight:700;text-align:center;text-decoration:none;margin-bottom:16px;box-sizing:border-box;">Try the Demo →</a>
</section>

<!-- FOOTER -->
<footer>
  <strong>ProductivRN</strong><br>
  QBL Alerts &mdash; Real-Time Hemorrhage Detection for OB Teams<br>
  <a href="https://www.productivern.com">productivern.com</a> &nbsp;&middot;&nbsp; <a href="/qbl-contact.php">Contact Us</a><br><br>
  &copy; <?php echo date('Y'); ?> ProductivRN. All rights reserved. &nbsp;&middot;&nbsp; <a href="/qbl-terms.php">Terms of Use</a><br>
  <span style="font-size:.75rem;opacity:.8">This tool is a clinical support aid and does not replace clinical judgment. Always follow your institution&rsquo;s hemorrhage protocols.</span>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
new QRCode(document.getElementById('qrcode'), {
  text: 'https://qbl.productivern.com/demo.php',
  width: 200,
  height: 200,
  colorDark: '#ffffff',
  colorLight: 'rgba(0,0,0,0)',
  correctLevel: QRCode.CorrectLevel.M
});
</script>
</body>
</html>
