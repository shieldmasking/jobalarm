<?php
require_once 'inc/class.db.php';
require_once 'inc/config.php';
$_ip = $_SERVER['REMOTE_ADDR'] ?? null;
$_ref = isset($_SERVER['HTTP_REFERER']) ? substr($_SERVER['HTTP_REFERER'], 0, 500) : null;
Config::get('db')->insert('qbl_page_views', ['page' => 'pricing', 'ip' => $_ip, 'referrer' => $_ref]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>QBL Alerts Pricing — $99/mo Per Hospital | ProductivRN</title>
<meta name="description" content="Simple, transparent pricing for QBL Alerts. $99/month per hospital includes unlimited users, unlimited SMS alerts, and full setup support. Discounts for multi-hospital systems.">
<link rel="canonical" href="https://qbl.productivern.com/pricing">

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
.hero-sub { font-size: .95rem; opacity: .85; max-width: 400px; margin: 0 auto 0; line-height: 1.65; }

/* SECTION SHARED */
section { padding: 40px 20px; }
.section-label { font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--teal); margin-bottom: 8px; }
.section-title { font-size: 1.3rem; font-weight: 800; color: var(--text); line-height: 1.2; margin-bottom: 10px; }
.section-body { font-size: .88rem; color: var(--muted); line-height: 1.7; }

/* PRICING CARD */
.pricing-section { background: var(--white); }
.pricing-card {
  margin-top: 24px;
  background: linear-gradient(160deg, #0d2f5c 0%, var(--navy) 60%, #1a6060 100%);
  border-radius: 20px; padding: 32px 24px; color: #fff; text-align: center;
  box-shadow: 0 8px 32px rgba(26,74,138,.25);
  position: relative; overflow: hidden;
}
.pricing-card::before {
  content: ''; position: absolute; top: -40px; right: -40px;
  width: 160px; height: 160px; border-radius: 50%;
  background: rgba(126,200,200,.08);
}
.pricing-badge {
  display: inline-block; background: rgba(126,200,200,.2); border: 1px solid rgba(126,200,200,.4);
  color: #7ec8c8; font-size: .68rem; font-weight: 700; letter-spacing: 1px;
  text-transform: uppercase; padding: 4px 12px; border-radius: 20px; margin-bottom: 16px;
}
.price-amount { font-size: 3.5rem; font-weight: 900; line-height: 1; color: #fff; }
.price-amount sup { font-size: 1.5rem; font-weight: 700; vertical-align: top; margin-top: .6rem; display: inline-block; }
.price-unit { font-size: .82rem; opacity: .65; margin-top: 4px; margin-bottom: 24px; }
.price-includes { list-style: none; text-align: left; margin-bottom: 28px; display: flex; flex-direction: column; gap: 10px; }
.price-includes li { display: flex; align-items: flex-start; gap: 10px; font-size: .87rem; line-height: 1.4; }
.price-includes li::before { content: '✓'; color: #7ec8c8; font-weight: 900; font-size: 1rem; flex-shrink: 0; margin-top: .05em; }
.btn-pricing-cta {
  display: block; width: 100%;
  background: #fff; color: var(--navy);
  text-decoration: none; font-size: 1rem; font-weight: 800;
  padding: 15px 24px; border-radius: 12px; text-align: center;
}
.btn-pricing-cta:hover { background: #e8f0fb; }

/* MULTI-HOSPITAL DISCOUNT */
.multi-section { background: var(--bg); }
.discount-table { margin-top: 20px; border-radius: 12px; overflow: hidden; border: 1px solid var(--border); }
.discount-row { display: flex; align-items: center; background: var(--white); border-bottom: 1px solid var(--border); padding: 14px 16px; gap: 12px; }
.discount-row:last-child { border-bottom: none; }
.discount-row.header { background: var(--navy); color: #fff; font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; }
.discount-row.header .dc { color: #7ec8c8; }
.dr-hospitals { flex: 1.4; font-size: .88rem; font-weight: 600; }
.dr-rate { flex: 1; font-size: .88rem; color: var(--muted); }
.dr-savings { flex: 1; font-size: .88rem; font-weight: 700; }
.discount-row.header .dr-hospitals,
.discount-row.header .dr-rate,
.discount-row.header .dr-savings { color: rgba(255,255,255,.75); font-size: .72rem; }
.tag-savings { display: inline-block; background: #e6f4f4; color: var(--teal); font-size: .72rem; font-weight: 700; padding: 2px 8px; border-radius: 20px; }
.tag-custom { display: inline-block; background: #fef9e7; color: #b7950b; font-size: .72rem; font-weight: 700; padding: 2px 8px; border-radius: 20px; }
.multi-note { margin-top: 14px; font-size: .8rem; color: var(--muted); line-height: 1.6; }

/* VALUE PROPS */
.value-section { background: var(--white); }
.value-grid { margin-top: 20px; display: flex; flex-direction: column; gap: 12px; }
.value-card { border-radius: 12px; padding: 18px; display: flex; align-items: flex-start; gap: 14px; }
.value-card.red { background: #fff0f0; border-left: 4px solid var(--red); }
.value-card.navy { background: #e8f0fb; border-left: 4px solid var(--navy); }
.value-card.teal { background: #e6f4f4; border-left: 4px solid var(--teal); }
.value-card.gold { background: #fef9e7; border-left: 4px solid #d4ac0d; }
.value-icon { font-size: 1.4rem; flex-shrink: 0; margin-top: 2px; }
.value-title { font-size: .9rem; font-weight: 700; margin-bottom: 3px; }
.value-card.red .value-title { color: var(--red); }
.value-card.navy .value-title { color: var(--navy); }
.value-card.teal .value-title { color: var(--teal); }
.value-card.gold .value-title { color: #b7950b; }
.value-desc { font-size: .8rem; color: var(--muted); line-height: 1.5; }

/* COMPARE SECTION */
.compare-section { background: var(--bg); }
.compare-table { margin-top: 20px; border-radius: 12px; overflow: hidden; border: 1px solid var(--border); }
.compare-row { display: flex; background: var(--white); border-bottom: 1px solid var(--border); }
.compare-row:last-child { border-bottom: none; }
.compare-row.header { background: var(--navy); }
.cr-feature { flex: 2; padding: 12px 14px; font-size: .82rem; display: flex; align-items: center; border-right: 1px solid var(--border); }
.cr-qbl { flex: 1; padding: 12px 10px; font-size: .82rem; text-align: center; display: flex; align-items: center; justify-content: center; border-right: 1px solid rgba(255,255,255,.1); }
.cr-manual { flex: 1; padding: 12px 10px; font-size: .82rem; text-align: center; display: flex; align-items: center; justify-content: center; }
.compare-row.header .cr-feature { color: rgba(255,255,255,.7); font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; border-right-color: rgba(255,255,255,.1); }
.compare-row.header .cr-qbl { color: #7ec8c8; font-size: .8rem; font-weight: 800; }
.compare-row.header .cr-manual { color: rgba(255,255,255,.5); font-size: .78rem; font-weight: 600; }
.cr-feature { color: var(--text); font-weight: 500; }
.check { color: var(--teal); font-weight: 900; font-size: 1.1rem; }
.cross { color: #ccc; font-size: 1rem; }

/* CTA SECTION */
.cta-section {
  background: linear-gradient(160deg, #0d2f5c 0%, var(--navy) 50%, #1a6060 100%);
  color: #fff; text-align: center; padding: 48px 20px;
}
.cta-section .section-label { color: #7ec8c8; }
.cta-section .section-title { color: #fff; font-size: 1.4rem; }
.cta-section .section-body { color: rgba(255,255,255,.75); max-width: 380px; margin: 0 auto 28px; }
.cta-btns { display: flex; flex-direction: column; gap: 10px; }
.btn-white { background: #fff; color: var(--navy); text-decoration: none; font-size: .95rem; font-weight: 800; padding: 15px 24px; border-radius: 12px; display: block; text-align: center; }
.btn-ghost { background: rgba(255,255,255,.12); color: #fff; text-decoration: none; font-size: .9rem; font-weight: 700; padding: 13px 24px; border-radius: 12px; border: 1px solid rgba(255,255,255,.25); display: block; text-align: center; }

/* FOOTER */
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
    <a style="color:rgba(255,255,255,.8);text-decoration:none;font-size:.82rem;font-weight:600;" href="/qbl-cms-readiness.php">CMS Readiness</a>
    <a style="color:rgba(255,255,255,.8);text-decoration:none;font-size:.82rem;font-weight:600;" href="/login.php?r=qbl">Login</a>
    <a class="nav-cta" href="/qbl-contact.php">Contact Us</a>
  </div>
</nav>

<!-- HERO -->
<section class="hero">
  <div class="hero-eyebrow">Simple, Transparent Pricing</div>
  <h1>One Price.<br><em>Everything Included.</em></h1>
  <p class="hero-sub">No per-user fees. No SMS caps. No surprise charges. Just $99/month and your team is protected.</p>
</section>

<!-- PRICING CARD -->
<section class="pricing-section">
  <div class="section-label">Pricing</div>
  <h2 class="section-title">Straightforward.<br>No Hidden Fees.</h2>
  <p class="section-body">One flat rate per hospital covers your entire OB unit — every nurse, every supervisor, every alert.</p>

  <div class="pricing-card">
    <div class="pricing-badge">Per Hospital &middot; Monthly</div>
    <div class="price-amount"><sup>$</sup>99</div>
    <div class="price-unit">per hospital / per month</div>
    <div style="font-size:.78rem;opacity:.55;margin-top:-18px;margin-bottom:24px;">$1,188 / year paid annually</div>
    <ul class="price-includes">
      <li>Unlimited users — nurses, charge nurses, supervisors, CNO, pharmacy</li>
      <li>Unlimited SMS alerts — no caps, no overages, ever</li>
      <li>Custom supply lists configured for your unit</li>
      <li>QR code setup — printed and ready to mount on your scale</li>
      <li>Personalized thresholds per recipient role</li>
      <li>Tap-to-view secure alert links</li>
      <li>Update alerts when additional supplies are added</li>
      <li>Onboarding &amp; setup support included</li>
    </ul>
    <a class="btn-pricing-cta" href="/qbl-contact.php">Get Started &rarr;</a>
  </div>
</section>

<!-- MULTI-HOSPITAL DISCOUNT -->
<section class="multi-section" id="multi">
  <div class="section-label">Multi-Hospital Systems</div>
  <h2 class="section-title">Volume Discounts<br>for Health Systems</h2>
  <p class="section-body">Running QBL Alerts across multiple facilities? We offer straightforward volume pricing — the more hospitals you add, the less you pay per site.</p>

  <div class="discount-table">
    <div class="discount-row header">
      <div class="dr-hospitals">Hospitals</div>
      <div class="dr-rate">Rate / Hospital</div>
      <div class="dr-savings dc">Savings</div>
    </div>
    <div class="discount-row">
      <div class="dr-hospitals">1 hospital</div>
      <div class="dr-rate">$99 / mo<br><span style="font-size:.75rem;color:var(--muted)">$1,188 / yr</span></div>
      <div class="dr-savings">&mdash;</div>
    </div>
    <div class="discount-row">
      <div class="dr-hospitals">2&ndash;4 hospitals</div>
      <div class="dr-rate">$85 / mo<br><span style="font-size:.75rem;color:var(--muted)">$1,020 / yr</span></div>
      <div class="dr-savings"><span class="tag-savings">Save 14%</span></div>
    </div>
    <div class="discount-row">
      <div class="dr-hospitals">5&ndash;9 hospitals</div>
      <div class="dr-rate">$75 / mo<br><span style="font-size:.75rem;color:var(--muted)">$900 / yr</span></div>
      <div class="dr-savings"><span class="tag-savings">Save 24%</span></div>
    </div>
    <div class="discount-row">
      <div class="dr-hospitals">10+ hospitals</div>
      <div class="dr-rate">Custom</div>
      <div class="dr-savings"><span class="tag-custom">Contact Us</span></div>
    </div>
  </div>

  <p class="multi-note">All tiers include the same unlimited users, unlimited SMS, and full support. <a href="/qbl-contact.php" style="color:var(--teal);font-weight:600;">Contact us</a> for health system or enterprise pricing.</p>
</section>

<!-- VALUE PROPS -->
<section class="value-section" id="value">
  <div class="section-label">Why It's Worth It</div>
  <h2 class="section-title">What $99/Month<br>Actually Buys Your Hospital</h2>

  <div class="value-grid">
    <div class="value-card red">
      <div class="value-icon">🩸</div>
      <div>
        <div class="value-title">Earlier Hemorrhage Detection</div>
        <div class="value-desc">Every minute matters in a PPH event. QBL Alerts fires in under 60 seconds — before a hemorrhage becomes a crisis. The cost of one avoidable complication dwarfs a year of subscription fees.</div>
      </div>
    </div>
    <div class="value-card navy">
      <div class="value-icon">👥</div>
      <div>
        <div class="value-title">Your Entire Team. One Alert.</div>
        <div class="value-desc">Charge nurse, house supervisor, pharmacy, anesthesia, CNO — all notified simultaneously with role-specific thresholds. No phone trees. No calling around. Everyone knows at the same time.</div>
      </div>
    </div>
    <div class="value-card teal">
      <div class="value-icon">⚡</div>
      <div>
        <div class="value-title">Zero IT Lift. Live in One Day.</div>
        <div class="value-desc">No EMR integration. No hospital network access. No software installs. A QR code on your scale and phone numbers in a config — your unit is live the same day you sign up.</div>
      </div>
    </div>
    <div class="value-card gold">
      <div class="value-icon">📋</div>
      <div>
        <div class="value-title">Supports AWHONN &amp; ACOG Compliance</div>
        <div class="value-desc">Automated cumulative QBL tracking and real-time escalation directly supports the AWHONN Hemorrhage Bundle and ACOG Practice Bulletin No. 183 — quantitative measurement with documented escalation.</div>
      </div>
    </div>
  </div>
</section>

<!-- COMPARE -->
<section class="compare-section">
  <div class="section-label">How It Compares</div>
  <h2 class="section-title">QBL Alerts vs.<br>Manual Escalation</h2>

  <div class="compare-table">
    <div class="compare-row header">
      <div class="cr-feature"></div>
      <div class="cr-qbl">QBL Alerts</div>
      <div class="cr-manual">Manual Process</div>
    </div>
    <div class="compare-row">
      <div class="cr-feature">Alert delivered in &lt; 60 seconds</div>
      <div class="cr-qbl"><span class="check">✓</span></div>
      <div class="cr-manual"><span class="cross">✗</span></div>
    </div>
    <div class="compare-row">
      <div class="cr-feature">Entire team notified simultaneously</div>
      <div class="cr-qbl"><span class="check">✓</span></div>
      <div class="cr-manual"><span class="cross">✗</span></div>
    </div>
    <div class="compare-row">
      <div class="cr-feature">Validated gravimetric calculation</div>
      <div class="cr-qbl"><span class="check">✓</span></div>
      <div class="cr-manual"><span class="cross">✗</span></div>
    </div>
    <div class="compare-row">
      <div class="cr-feature">No app download for nurses</div>
      <div class="cr-qbl"><span class="check">✓</span></div>
      <div class="cr-manual"><span class="check">✓</span></div>
    </div>
    <div class="compare-row">
      <div class="cr-feature">Tap-to-view secure alert links</div>
      <div class="cr-qbl"><span class="check">✓</span></div>
      <div class="cr-manual"><span class="check">✓</span></div>
    </div>
    <div class="compare-row">
      <div class="cr-feature">Personalized thresholds per role</div>
      <div class="cr-qbl"><span class="check">✓</span></div>
      <div class="cr-manual"><span class="cross">✗</span></div>
    </div>
    <div class="compare-row">
      <div class="cr-feature">Documented escalation record</div>
      <div class="cr-qbl"><span class="check">✓</span></div>
      <div class="cr-manual"><span class="cross">✗</span></div>
    </div>
    <div class="compare-row">
      <div class="cr-feature">No IT infrastructure required</div>
      <div class="cr-qbl"><span class="check">✓</span></div>
      <div class="cr-manual"><span class="check">✓</span></div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta-section">
  <div class="section-label">Get Started</div>
  <h2 class="section-title">Ready to Protect Your Unit?</h2>
  <p class="section-body">Setup takes less than a day. No contracts. No IT involvement. Cancel anytime.</p>
  <div class="cta-btns">
    <a class="btn-white" href="/qbl-contact.php">Contact Us to Get Started</a>
    <a class="btn-ghost" href="https://qbl.productivern.com/demo.php">Try the Free Demo First &rarr;</a>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <strong>ProductivRN</strong><br>
  QBL Alerts &mdash; Real-Time Hemorrhage Detection for OB Teams<br>
  <a href="https://www.productivern.com">productivern.com</a> &nbsp;&middot;&nbsp; <a href="/qbl-contact.php">Contact Us</a><br><br>
  &copy; <?php echo date('Y'); ?> ProductivRN. All rights reserved.<br>
  <span style="font-size:.68rem;opacity:.6">QBL Alerts is a notification tool and does not replace clinical judgment. Always follow your institution's hemorrhage protocols.</span>
</footer>

</body>
</html>
