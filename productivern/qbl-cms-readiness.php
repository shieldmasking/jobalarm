<?php
require_once 'inc/class.db.php';
require_once 'inc/config.php';
$_ip = $_SERVER['REMOTE_ADDR'] ?? null;
$_ref = isset($_SERVER['HTTP_REFERER']) ? substr($_SERVER['HTTP_REFERER'], 0, 500) : null;
Config::get('db')->insert('qbl_page_views', ['page' => 'cms-readiness', 'ip' => $_ip, 'referrer' => $_ref]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CMS OB Readiness — QBL Alerts | ProductivRN</title>
<meta name="description" content="How QBL Alerts supports your hospital's readiness for the CMS obstetric Conditions of Participation — escalation protocols, team notification, drill mode, and QAPI reporting.">
<link rel="canonical" href="https://qbl.productivern.com/cms-readiness">

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

/* NAV */
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

/* HERO */
.hero {
  background: linear-gradient(160deg, #0d2f5c 0%, var(--navy) 50%, #1a6060 100%);
  color: #fff; padding: 52px 20px 48px; text-align: center;
}
.hero-eyebrow { font-size: .72rem; font-weight: 700; letter-spacing: 1.2px; text-transform: uppercase; opacity: .7; margin-bottom: 12px; }
.hero h1 { font-size: 1.75rem; font-weight: 900; line-height: 1.15; margin-bottom: 16px; }
.hero h1 em { font-style: normal; color: #7ec8c8; }
.hero-sub { font-size: .95rem; opacity: .85; max-width: 440px; margin: 0 auto 24px; line-height: 1.65; }
.hero-disclaimer {
  display: inline-block;
  background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.2);
  border-radius: 8px; padding: 10px 16px;
  font-size: .78rem; opacity: .8; max-width: 420px; line-height: 1.5;
}

/* SECTION SHARED */
section { padding: 40px 20px; }
.section-label { font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--teal); margin-bottom: 8px; }
.section-title { font-size: 1.3rem; font-weight: 800; color: var(--text); line-height: 1.2; margin-bottom: 10px; }
.section-body { font-size: .88rem; color: var(--muted); line-height: 1.7; }

/* PHASES TIMELINE */
.phases-section { background: var(--white); }
.phase-list { margin-top: 24px; display: flex; flex-direction: column; gap: 4px; }
.phase-card {
  border-radius: 12px; padding: 20px 18px;
  display: flex; align-items: flex-start; gap: 16px;
  border: 1px solid var(--border);
  background: var(--bg);
}
.phase-card.past { opacity: .65; }
.phase-card.active { background: #e6f4f4; border-color: #9dd0d0; }
.phase-card.upcoming { background: #e8f0fb; border-color: #9ab8e0; }
.phase-dot {
  width: 36px; height: 36px; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: .75rem; font-weight: 800; flex-shrink: 0; margin-top: 2px;
}
.phase-card.past .phase-dot { background: var(--muted); color: #fff; }
.phase-card.active .phase-dot { background: var(--teal); color: #fff; }
.phase-card.upcoming .phase-dot { background: var(--navy); color: #fff; }
.phase-date { font-size: .72rem; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; margin-bottom: 4px; }
.phase-card.past .phase-date { color: var(--muted); }
.phase-card.active .phase-date { color: var(--teal); }
.phase-card.upcoming .phase-date { color: var(--navy); }
.phase-title { font-size: .95rem; font-weight: 700; color: var(--text); margin-bottom: 4px; }
.phase-desc { font-size: .82rem; color: var(--muted); line-height: 1.5; }
.phase-badge {
  display: inline-block; font-size: .66rem; font-weight: 700; letter-spacing: .5px;
  text-transform: uppercase; padding: 3px 9px; border-radius: 20px; margin-top: 8px;
}
.phase-card.past .phase-badge { background: #e8eaed; color: var(--muted); }
.phase-card.active .phase-badge { background: #c2e8e8; color: var(--teal); }
.phase-card.upcoming .phase-badge { background: #ccd9f0; color: var(--navy); }

/* CROSSWALK */
.crosswalk-section { background: var(--bg); }
.crosswalk-table { margin-top: 20px; border-radius: 12px; overflow: hidden; border: 1px solid var(--border); }
.cw-row { display: grid; grid-template-columns: 1fr 1fr; background: var(--white); border-bottom: 1px solid var(--border); }
.cw-row:last-child { border-bottom: none; }
.cw-row.header { background: var(--navy); color: #fff; font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; }
.cw-cell { padding: 14px 16px; font-size: .85rem; line-height: 1.5; }
.cw-row.header .cw-cell { color: rgba(255,255,255,.8); padding: 12px 16px; }
.cw-req { font-weight: 600; color: var(--text); }
.cw-how { color: var(--muted); }
.cw-how strong { color: var(--teal); font-weight: 700; }
.cw-row:nth-child(even) { background: #fafbfc; }

/* HOW IT HELPS */
.helps-section { background: var(--white); }
.helps-grid { margin-top: 20px; display: flex; flex-direction: column; gap: 12px; }
.help-card { border-radius: 12px; padding: 18px; display: flex; align-items: flex-start; gap: 14px; }
.help-card.navy { background: #e8f0fb; border-left: 4px solid var(--navy); }
.help-card.teal { background: #e6f4f4; border-left: 4px solid var(--teal); }
.help-card.red  { background: #fff0f0; border-left: 4px solid var(--red); }
.help-card.gold { background: #fef9e7; border-left: 4px solid #d4ac0d; }
.help-icon { font-size: 1.4rem; flex-shrink: 0; margin-top: 2px; }
.help-title { font-size: .9rem; font-weight: 700; margin-bottom: 3px; }
.help-card.navy .help-title { color: var(--navy); }
.help-card.teal .help-title { color: var(--teal); }
.help-card.red  .help-title { color: var(--red); }
.help-card.gold .help-title { color: #b7950b; }
.help-desc { font-size: .8rem; color: var(--muted); line-height: 1.5; }

/* DOWNLOAD */
.download-section { background: var(--bg); }
.download-card {
  margin-top: 20px;
  background: linear-gradient(160deg, #0d2f5c 0%, var(--navy) 60%, #1a6060 100%);
  border-radius: 16px; padding: 28px 24px; color: #fff; text-align: center;
}
.download-card h3 { font-size: 1.1rem; font-weight: 800; margin-bottom: 8px; }
.download-card p { font-size: .85rem; opacity: .8; line-height: 1.6; margin-bottom: 20px; max-width: 340px; margin-left: auto; margin-right: auto; }
.btn-download {
  display: inline-block;
  background: #fff; color: var(--navy);
  text-decoration: none; font-size: .9rem; font-weight: 800;
  padding: 13px 24px; border-radius: 10px;
}
.btn-download:hover { background: #e8f0fb; }

/* CTA */
.cta-section {
  background: linear-gradient(160deg, #0d2f5c 0%, var(--navy) 50%, #1a6060 100%);
  color: #fff; text-align: center; padding: 48px 20px;
}
.cta-section .section-label { color: #7ec8c8; }
.cta-section .section-title { color: #fff; font-size: 1.4rem; margin-bottom: 10px; }
.cta-section .section-body { color: rgba(255,255,255,.75); max-width: 360px; margin: 0 auto 24px; }
.cta-btns { display: flex; flex-direction: column; gap: 12px; align-items: center; }
.btn-white { background: #fff; color: var(--navy); text-decoration: none; font-size: .95rem; font-weight: 800; padding: 14px 28px; border-radius: 12px; width: 100%; max-width: 320px; text-align: center; display: block; }
.btn-white:hover { background: #e8f0fb; }
.btn-ghost { background: transparent; color: #fff; text-decoration: none; font-size: .88rem; font-weight: 600; padding: 12px 20px; border-radius: 12px; border: 1px solid rgba(255,255,255,.35); width: 100%; max-width: 320px; text-align: center; display: block; }
.btn-ghost:hover { background: rgba(255,255,255,.08); }

/* FOOTER */
footer { background: var(--navy-dark); color: rgba(255,255,255,.55); text-align: center; padding: 28px 20px; font-size: .78rem; line-height: 1.8; }
footer a { color: rgba(255,255,255,.7); text-decoration: none; }
footer strong { color: #fff; }
</style>
</head>
<body>

<!-- NAV -->
<nav>
  <a class="nav-brand" href="https://qbl.productivern.com">Productive<span>RN</span> &mdash; QBL Alerts</a>
  <div style="display:flex;align-items:center;gap:10px;">
    <a style="color:rgba(255,255,255,.8);text-decoration:none;font-size:.82rem;font-weight:600;" href="/qbl-pricing.php">Pricing</a>
    <a style="color:rgba(255,255,255,.8);text-decoration:none;font-size:.82rem;font-weight:600;" href="/login.php?r=qbl">Login</a>
    <a class="nav-cta" href="/qbl-contact.php">Start a Pilot</a>
  </div>
</nav>

<!-- HERO -->
<section class="hero">
  <div class="hero-eyebrow">CMS Obstetric Conditions of Participation</div>
  <h1>Your Unit Has a <em>Deadline.</em><br>We Help You Meet It.</h1>
  <p class="hero-sub">CMS now requires labor &amp; delivery units to document hemorrhage escalation and participate in QAPI. QBL Alerts supports both — out of the box.</p>
  <div class="hero-disclaimer">QBL Alerts supports your readiness efforts. It does not make your hospital compliant with CMS regulations. Compliance determinations are the responsibility of your institution.</div>
</section>

<!-- PHASES TIMELINE -->
<section class="phases-section">
  <div class="section-label">CMS OB CoP Phases</div>
  <h2 class="section-title">Three Deadlines.<br>One Tool That Covers All of Them.</h2>
  <p class="section-body">The CMS obstetric Conditions of Participation roll out in three phases. Here's where each stands and how QBL Alerts supports readiness for each.</p>

  <div class="phase-list">
    <div class="phase-card past">
      <div class="phase-dot">1</div>
      <div>
        <div class="phase-date">July 1, 2025</div>
        <div class="phase-title">Emergency Readiness</div>
        <div class="phase-desc">Hospitals must have an emergency readiness plan for obstetric emergencies, including hemorrhage response protocols.</div>
        <span class="phase-badge">Effective — July 2025</span>
      </div>
    </div>
    <div class="phase-card active">
      <div class="phase-dot">2</div>
      <div>
        <div class="phase-date">January 1, 2026</div>
        <div class="phase-title">Organization, Staffing &amp; Delivery</div>
        <div class="phase-desc">Requirements around unit organization, staffing protocols, and care delivery standards for labor &amp; delivery units.</div>
        <span class="phase-badge">Effective — Jan 2026</span>
      </div>
    </div>
    <div class="phase-card upcoming">
      <div class="phase-dot">3</div>
      <div>
        <div class="phase-date">January 1, 2027</div>
        <div class="phase-title">Training &amp; QAPI</div>
        <div class="phase-desc">Hospitals must provide documented hemorrhage training and participate in ongoing Quality Assessment and Performance Improvement (QAPI) data collection for OB outcomes.</div>
        <span class="phase-badge">Effective — Jan 2027</span>
      </div>
    </div>
  </div>
</section>

<!-- CROSSWALK TABLE -->
<section class="crosswalk-section">
  <div class="section-label">Requirements Crosswalk</div>
  <h2 class="section-title">What CMS Requires.<br>How QBL Alerts Helps.</h2>

  <div class="crosswalk-table">
    <div class="cw-row header">
      <div class="cw-cell">CMS Requirement</div>
      <div class="cw-cell">How QBL Alerts Supports It</div>
    </div>
    <div class="cw-row">
      <div class="cw-cell cw-req">Documented hemorrhage escalation protocol</div>
      <div class="cw-cell cw-how">Every alert creates a timestamped record: calculation, threshold crossed, who was notified, and when. <strong>Built-in escalation audit trail.</strong></div>
    </div>
    <div class="cw-row">
      <div class="cw-cell cw-req">Simultaneous team notification</div>
      <div class="cw-cell cw-how">Personalized SMS alerts go to every configured recipient the moment a threshold is crossed — charge nurse, OB, pharmacy, anesthesia at once. <strong>No phone tree.</strong></div>
    </div>
    <div class="cw-row">
      <div class="cw-cell cw-req">Documented hemorrhage drills</div>
      <div class="cw-cell cw-how">Drill mode runs the real tool with clearly labeled "DRILL" texts and produces a printable drill report with response times per participant. <strong>Training documentation ready to hand to a surveyor.</strong></div>
    </div>
    <div class="cw-row">
      <div class="cw-cell cw-req">QAPI data collection for OB outcomes</div>
      <div class="cw-cell cw-how">Monthly QAPI summary: encounters, threshold crossings, alert delivery rates, and time-to-acknowledgment — formatted for committee review. <strong>CSV and PDF export.</strong></div>
    </div>
    <div class="cw-row">
      <div class="cw-cell cw-req">Response time measurement</div>
      <div class="cw-cell cw-how">Tracks time from threshold crossed → alert delivered → first view → acknowledgment. <strong>The metric your CNO will present at QAPI.</strong></div>
    </div>
  </div>
</section>

<!-- HOW IT HELPS -->
<section class="helps-section">
  <div class="section-label">Key Features</div>
  <h2 class="section-title">Built for the Bedside.<br>Documented for the Boardroom.</h2>

  <div class="helps-grid">
    <div class="help-card navy">
      <div class="help-icon">📋</div>
      <div>
        <div class="help-title">Automatic Escalation Records</div>
        <div class="help-desc">Every calculation, every alert sent, every view, every acknowledgment — timestamped and stored. No manual documentation required from nurses.</div>
      </div>
    </div>
    <div class="help-card teal">
      <div class="help-icon">📊</div>
      <div>
        <div class="help-title">Monthly QAPI Reports</div>
        <div class="help-desc">One-click PDF summary formatted for your quality committee — blood loss distribution, response times, alert reliability, trended monthly. Email delivery to your CNO on the 1st of each month.</div>
      </div>
    </div>
    <div class="help-card red">
      <div class="help-icon">🚨</div>
      <div>
        <div class="help-title">Drill Mode</div>
        <div class="help-desc">Run live hemorrhage drills using the real tool. Texts are clearly labeled DRILL. Produces a printable drill log with who responded and how fast — documentation your surveyors will ask for.</div>
      </div>
    </div>
    <div class="help-card gold">
      <div class="help-icon">✅</div>
      <div>
        <div class="help-title">"I'm Responding" Acknowledgment</div>
        <div class="help-desc">Recipients tap a single button to acknowledge. The nurse's screen updates live. Time-to-acknowledgment is the single most important QAPI metric you can show CMS.</div>
      </div>
    </div>
  </div>
</section>

<!-- DOWNLOAD CROSSWALK -->
<section class="download-section">
  <div class="section-label">Sales Tool</div>
  <h2 class="section-title">Take This to Your CNO.</h2>
  <p class="section-body">A one-page CMS readiness crosswalk your nurse manager can forward up the chain — what CMS requires, what QBL Alerts covers, and what it doesn't. No overselling.</p>

  <div class="download-card">
    <h3>CMS OB CoP Readiness Crosswalk</h3>
    <p>One page. Formatted for a CNO or quality director. Bring it to your next committee meeting.</p>
    <a class="btn-download" href="/qbl-contact.php">Request the One-Pager &rarr;</a>
  </div>
</section>

<!-- CTA -->
<section class="cta-section">
  <div class="section-label">Get Started</div>
  <h2 class="section-title">January 2027 Is Closer Than It Looks.</h2>
  <p class="section-body">A 90-day pilot gives you live QAPI data before the deadline. Setup takes less than a day.</p>
  <div class="cta-btns">
    <a class="btn-white" href="/qbl-contact.php">Start a Pilot</a>
    <a class="btn-ghost" href="/qbl-pricing.php">See Pricing &rarr;</a>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <strong>ProductivRN</strong><br>
  QBL Alerts &mdash; Real-Time Hemorrhage Detection for OB Teams<br>
  <a href="https://www.productivern.com">productivern.com</a> &nbsp;&middot;&nbsp; <a href="/qbl-contact.php">Contact Us</a> &nbsp;&middot;&nbsp; <a href="/qbl-pricing.php">Pricing</a><br><br>
  &copy; <?php echo date('Y'); ?> ProductivRN. All rights reserved.<br>
  <span style="font-size:.68rem;opacity:.6">QBL Alerts supports readiness for CMS obstetric Conditions of Participation. It does not make your hospital compliant. Compliance is the responsibility of your institution. This tool is not a substitute for your institution's hemorrhage protocols.</span>
</footer>

</body>
</html>
