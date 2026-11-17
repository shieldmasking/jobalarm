<?php
include "../inc/initializer.php";
require_once '.././inc/class.db.php';
require_once '.././inc/config.php';

$role = intval($_SESSION['account']['role']);
?>

<style>
.hp-back {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.82rem;
    color: #0a8a7e;
    cursor: pointer;
    margin-bottom: 1rem;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    font-weight: 600;
    text-decoration: none;
}
.hp-back:hover { color: #0d2240; }

.hp-letterhead {
    background: linear-gradient(135deg, #1a3a1a 0%, #2d5a2d 60%, #3d7a3d 100%);
    border-radius: 8px 8px 0 0;
    padding: 2rem 2.5rem 1.5rem;
    position: relative;
    overflow: hidden;
}
.hp-letterhead::after {
    content: '';
    position: absolute;
    bottom: -30px; right: -30px;
    width: 160px; height: 160px;
    border-radius: 50%;
    border: 32px solid rgba(255,255,255,0.08);
    pointer-events: none;
}
.hp-brand h2 {
    font-size: 1.6rem;
    font-weight: 800;
    color: #fff;
    margin: 0;
    letter-spacing: -0.02em;
}
.hp-brand p {
    font-size: 0.7rem;
    color: rgba(255,255,255,0.5);
    letter-spacing: 0.12em;
    text-transform: uppercase;
    margin: 0.2rem 0 0;
}
.hp-banner {
    background: linear-gradient(90deg, #2d5a2d, #4a9a4a);
    padding: 0.7rem 2.5rem;
    font-size: 0.82rem;
    color: #fff;
    font-weight: 500;
    letter-spacing: 0.02em;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}
.hp-body {
    background: #fff;
    padding: 2rem 2.5rem 2.5rem;
    border: 1px solid #d0dae6;
    border-top: none;
    border-radius: 0 0 8px 8px;
    font-family: Georgia, 'Times New Roman', serif;
}
.hp-section-label {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    font-size: 0.62rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.14em;
    color: #2d7a2d;
    margin-bottom: 0.3rem;
}
.hp-section-title {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    font-size: 1.15rem;
    font-weight: 700;
    color: #1a3a1a;
    margin-bottom: 0.9rem;
}
.hp-p {
    font-size: 0.95rem;
    color: #3d4f63;
    line-height: 1.85;
    margin-bottom: 1.2rem;
}
.hp-callout {
    border-left: 4px solid #2d7a2d;
    background: #f0f7f0;
    padding: 1rem 1.25rem;
    border-radius: 0 6px 6px 0;
    margin: 1.2rem 0;
}
.hp-callout h5 {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    font-size: 0.85rem;
    font-weight: 700;
    color: #1a5a1a;
    margin-bottom: 0.35rem;
}
.hp-callout p { font-size: 0.875rem; color: #3d4f63; margin: 0; line-height: 1.65; }
.hp-callout.gold {
    border-left-color: #c9963a;
    background: #fdf8f0;
}
.hp-callout.gold h5 { color: #8a6020; }
.hp-callout.navy {
    border-left-color: #0d2240;
    background: rgba(13,34,64,0.04);
}
.hp-callout.navy h5 { color: #0d2240; }
.hp-divider { border: none; border-top: 2px solid #e8eef5; margin: 1.8rem 0 1.5rem; }

.hp-role-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin: 1.2rem 0;
}
.hp-role-card {
    border-radius: 8px;
    padding: 1.25rem;
    border: 1px solid #d0dae6;
}
.hp-role-card.hospital {
    background: linear-gradient(135deg, #f0f7f0, #e6f2e6);
    border-color: #a8d4a8;
}
.hp-role-card.practice {
    background: #f8fafc;
    border-color: #d0dae6;
}
.hp-role-card h5 {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    font-size: 0.85rem;
    font-weight: 700;
    margin-bottom: 0.6rem;
}
.hp-role-card.hospital h5 { color: #1a5a1a; }
.hp-role-card.practice h5 { color: #0d2240; }
.hp-check-item {
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    font-size: 0.82rem;
    color: #3d4f63;
    margin-bottom: 0.4rem;
    line-height: 1.45;
}
.hp-check-item::before {
    content: '✓';
    width: 16px; height: 16px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.55rem; font-weight: 800;
    flex-shrink: 0; margin-top: 1px;
    color: #fff;
}
.hp-role-card.hospital .hp-check-item::before { background: #2d7a2d; }
.hp-role-card.practice .hp-check-item::before { background: #0d2240; }

.hp-stat-strip {
    background: linear-gradient(135deg, #1a3a1a, #2d5a2d);
    border-radius: 6px;
    padding: 1.25rem 1.5rem;
    display: flex;
    gap: 1.5rem;
    margin: 1.2rem 0;
    justify-content: space-around;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}
.hp-stat-num   { font-size: 1.8rem; font-weight: 800; color: #7dd87d; line-height: 1; margin-bottom: 0.3rem; }
.hp-stat-label { font-size: 0.68rem; color: rgba(255,255,255,0.55); text-transform: uppercase; letter-spacing: 0.07em; line-height: 1.35; }
.hp-stat-div   { width: 1px; background: rgba(255,255,255,0.12); flex-shrink: 0; }

.hp-benefit-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.85rem;
    margin: 1.2rem 0;
}
.hp-benefit-card {
    background: #f8fafc;
    border: 1px solid #d0dae6;
    border-radius: 6px;
    padding: 1rem;
    position: relative; overflow: hidden;
}
.hp-benefit-card::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 3px;
    background: linear-gradient(90deg, #1a3a1a, #4a9a4a);
}
.hp-benefit-card h6 {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    font-size: 0.85rem; font-weight: 700; color: #1a3a1a; margin: 0.4rem 0 0.35rem;
}
.hp-benefit-card p { font-size: 0.8rem; color: #6b7f96; line-height: 1.5; margin: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }

.hp-steps {
    counter-reset: step;
    margin: 1.2rem 0;
}
.hp-step {
    display: flex;
    gap: 1rem;
    align-items: flex-start;
    margin-bottom: 1rem;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}
.hp-step-num {
    width: 32px; height: 32px;
    background: linear-gradient(135deg, #1a3a1a, #3d7a3d);
    color: #fff;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.85rem; font-weight: 700;
    flex-shrink: 0;
}
.hp-step-content h6 { font-size: 0.875rem; font-weight: 700; color: #1a3a1a; margin-bottom: 0.2rem; }
.hp-step-content p  { font-size: 0.825rem; color: #6b7f96; margin: 0; line-height: 1.5; }

.hp-signature-line {
    font-size: 1.5rem;
    font-style: italic;
    color: #1a3a1a;
    border-bottom: 1px solid #d0dae6;
    padding-bottom: 0.25rem;
    margin-bottom: 0.5rem;
    width: 260px;
    font-family: Georgia, serif;
}
.hp-signature-name {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    font-weight: 700;
    color: #1a2332;
    font-size: 0.925rem;
}
.hp-signature-title {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    font-size: 0.825rem;
    color: #6b7f96;
}

@media print {
    .m-header, #m_aside_left, .m-aside-left-close, .m-subheader, #hpPrintBtn, .hp-back, footer, #hpDraftEmail { display: none !important; }
    .m-wrapper { margin: 0 !important; padding: 0 !important; }
    .m-content { padding: 0 !important; }
    body { background: #fff !important; }

    .hp-letterhead, .hp-banner, .hp-callout, .hp-stat-strip, .hp-benefit-card,
    .hp-role-card, .hp-fields-header, .hp-step-num {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .hp-callout, .hp-stat-strip, .hp-benefit-card, .hp-role-card,
    .hp-step, .hp-benefit-grid { page-break-inside: avoid; }

    .hp-section-label, .hp-section-title { page-break-after: avoid; }
    .hp-page-break { page-break-before: always; }

    /* Tighten spacing to fit more on page 1 */
    .hp-body { padding: 1.5rem 2rem 1.75rem; }
    .hp-p { line-height: 1.7; margin-bottom: 0.9rem; font-size: 0.9rem; }
    .hp-section-title { margin-bottom: 0.65rem; }
    .hp-divider { margin: 1.25rem 0 1.1rem; }
    .hp-benefit-grid { gap: 0.65rem; margin: 0.9rem 0; }
    .hp-benefit-card { padding: 0.8rem 1rem; }
    .hp-benefit-card h6 { margin: 0.25rem 0 0.25rem; }
    .hp-role-grid { gap: 0.75rem; margin: 0.9rem 0; }
    .hp-role-card { padding: 1rem; }
    .hp-check-item { margin-bottom: 0.3rem; }
    .hp-callout { padding: 0.75rem 1rem; margin: 0.75rem 0; }
    .hp-callout h5 { margin-bottom: 0.2rem; }
    .hp-callout p { font-size: 0.82rem; line-height: 1.55; }
}
</style>

<div class="m-content">
    <div class="m-subheader">
        <div class="mr-auto">
            <h3 class="m-subheader__title">StorkSense Pilot — Hospital Partnership Pitch</h3>
        </div>
    </div>

    <div style="max-width: 860px; margin: 0 auto;">

        <div style="display:flex; gap:0.75rem; align-items:center; margin-bottom:1rem;">
            <a class="hp-back" onclick="location.hash='#scheduling';" style="margin-bottom:0;">&#8592; Back to EHR Integration</a>
            <button id="hpPrintBtn" onclick="window.print()" style="display:flex;align-items:center;gap:0.5rem;background:#1a3a1a;color:#fff;border:none;border-radius:6px;padding:0.55rem 1.1rem;font-size:0.82rem;font-weight:600;cursor:pointer;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;">
                🖨️ Print / Save as PDF
            </button>
        </div>

        <!-- LETTERHEAD -->
        <div class="hp-letterhead">
            <div class="hp-brand">
                <h2><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 112 72" width="52" height="34" style="vertical-align:middle;margin-right:0.45rem;margin-bottom:0.1rem;">
  <path d="M48,40 Q28,14 4,8 Q16,24 36,36 Z" fill="white"/>
  <path d="M4,8 Q0,10 0,16 Q8,16 18,22 Q10,14 4,8 Z" fill="#1a3a1a"/>
  <ellipse cx="50" cy="44" rx="18" ry="9" fill="white"/>
  <ellipse cx="41" cy="41" rx="7" ry="4" fill="#1a3a1a" transform="rotate(-15,41,41)"/>
  <path d="M33,48 Q16,56 10,60 Q20,52 30,50 Z" fill="white"/>
  <path d="M34,52 Q20,62 16,66 Q25,58 33,54 Z" fill="#e0e0e0"/>
  <path d="M62,38 Q72,28 76,22" stroke="white" stroke-width="7" fill="none" stroke-linecap="round"/>
  <circle cx="77" cy="20" r="7" fill="white"/>
  <path d="M71,15 Q77,11 83,15 Q80,20 77,20 Q74,20 71,15 Z" fill="#1a3a1a"/>
  <circle cx="79" cy="19" r="2.2" fill="#1a3a1a"/>
  <circle cx="79.8" cy="18.2" r="0.8" fill="white"/>
  <path d="M83,20 L110,27 L83,24 Z" fill="#e06000"/>
  <path d="M108,27 Q104,31 96,36" stroke="#bbb" stroke-width="1.3" fill="none"/>
  <path d="M96,36 C97,36 103,40 103,46 C103,52 100,54 96,54 C92,54 89,52 89,46 C89,40 95,36 96,36 Z" fill="#f5e6c8" stroke="#c9a860" stroke-width="1.5"/>
  <path d="M92,37 Q96,34 100,37" stroke="#c9a860" stroke-width="1.8" fill="none" stroke-linecap="round"/>
  <circle cx="93" cy="46" r="1" fill="#c9a860" opacity="0.4"/>
  <circle cx="99" cy="44" r="0.9" fill="#c9a860" opacity="0.4"/>
  <circle cx="96" cy="50" r="1" fill="#c9a860" opacity="0.4"/>
</svg>StorkSense</h2>
                <p>Hospital Partnership Request &nbsp;·&nbsp; Confidential</p>
            </div>
        </div>
        <div class="hp-banner">
            Poplar Bluff Regional Medical Center &nbsp;·&nbsp; OB Staffing Intelligence Pilot Program
        </div>

        <!-- BODY -->
        <div class="hp-body">

            <!-- WHAT IT IS -->
            <div class="hp-section-label">Overview</div>
            <h4 class="hp-section-title">What StorkSense Is</h4>

            <p class="hp-p">
                Women are pregnant for up to nine months before they deliver. The clinical data exists long before
                they walk through your doors — who's coming, roughly when, and what's going on in their pregnancy.
                StorkSense is built on a simple premise: that data should be driving your staffing decisions, not last
                month's averages.
            </p>

            <p class="hp-p">
                StorkSense is a delivery volume forecasting platform for labor and delivery floors. It gives your
                L&D leaderss a rolling 30-day view of predicted delivery volume, high-risk concentration,
                first-time mother load, and triage pressure — days before the shift is ever scheduled.
                The result is staffing decisions made proactively, not reactively.
            </p>

            <hr class="hp-divider">

            <!-- WHY PBRMC -->
            <div class="hp-section-label">Section 1</div>
            <h4 class="hp-section-title">Why Poplar Bluff Regional Medical Center</h4>

            <p class="hp-p">
                Not every hospital is the right fit for a pilot like this. Poplar Bluff Regional is.
            </p>

            <div class="hp-benefit-grid">
                <div class="hp-benefit-card">
                    <div style="font-size:1.5rem;">📊</div>
                    <h6>The Right Volume</h6>
                    <p>At approximately 100 deliveries per month, PBRMC has enough volume for the forecast to be
                    meaningful — and consistent enough to measure accuracy against actual outcomes over the pilot period.</p>
                </div>
                <div class="hp-benefit-card">
                    <div style="font-size:1.5rem;">🩺</div>
                    <h6>A Manageable Practice Network</h6>
                    <p>The vast majority of patients delivering at PBRMC come from just 3–4 OB offices. That means
                    near-complete panel coverage with a small number of practice integrations — making this pilot
                    straightforward to launch and simple to scale.</p>
                </div>
            </div>

            <hr class="hp-divider">

            <!-- HOW IT WORKS -->
            <div class="hp-section-label">Section 2</div>
            <h4 class="hp-section-title">How the Pilot Works</h4>

            <p class="hp-p">
                The forecast is powered by structured obstetric data entered by participating physician practices
                into their EHR at patient intake. Our algorithm reads that data via API, calculates a delivery
                probability window for each patient, and surfaces it to your L&D leaderss as a rolling 30-day forecast.
                No patient data is ever stored — only the anonymized delivery window estimate.
            </p>

            <div class="hp-role-grid">
                <div class="hp-role-card hospital">
                    <h5>🏥 Poplar Bluff Regional's Role</h5>
                    <div class="hp-check-item">Serve as the pilot's anchor institution</div>
                    <div class="hp-check-item">Identify OB physician practices in your referral network</div>
                    <div class="hp-check-item">Reach out to each practice and invite them to participate</div>
                    <div class="hp-check-item">Designate an L&D leaders champion to work with us during the pilot</div>
                    <div class="hp-check-item">Provide feedback on forecast accuracy and staffing impact</div>
                </div>
                <div class="hp-role-card practice">
                    <h5>🩺 Participating Practices' Role</h5>
                    <div class="hp-check-item">Add a small set of custom OB fields to their EHR (one-time, ~30 min)</div>
                    <div class="hp-check-item">Complete fields at patient intake — no ongoing updates required</div>
                    <div class="hp-check-item">Grant read-only API access to StorkSense</div>
                    <div class="hp-check-item">Gain visibility into the delivery forecast for their own panel</div>
                </div>
            </div>

            <div class="hp-callout navy">
                <h5>⏱ How Easy Is the Ask for the OB Practices?</h5>
                <p>Adding custom fields to an EHR is a routine administrative task — no coding, no workflow disruption.
                Most EHR administrators can complete the configuration in under 30 minutes. We provide step-by-step
                guidance and are happy to coordinate directly with each practice's EHR representative. When you make
                the call, we handle everything else.</p>
            </div>

            <hr class="hp-divider">

            <!-- WHAT YOU GET -->
            <div class="hp-section-label">Section 3</div>
            <h4 class="hp-section-title">What Poplar Bluff Regional Gains</h4>

            <div class="hp-benefit-grid">
                <div class="hp-benefit-card">
                    <div style="font-size:1.5rem;">📅</div>
                    <h6>Smarter Staffing Decisions</h6>
                    <p>Your L&D leaderss see a rolling 30-day delivery forecast — total volume, high-risk concentration,
                    first-time mother load, and triage pressure — days before the shift is scheduled.</p>
                </div>
                <div class="hp-benefit-card">
                    <div style="font-size:1.5rem;">💰</div>
                    <h6>Reduced Overtime &amp; Agency Spend</h6>
                    <p>Reactive overstaffing and last-minute agency calls are the most expensive staffing failures.
                    Predictive scheduling eliminates the guesswork that drives both.</p>
                </div>
                <div class="hp-benefit-card">
                    <div style="font-size:1.5rem;">🚨</div>
                    <h6>Triage Pressure Prediction</h6>
                    <p>Know in advance when your triage bay is likely to be overwhelmed — driven by office closures,
                    first-time mothers near term, and active complications in the panel. Staff for it before it happens.</p>
                </div>
                <div class="hp-benefit-card">
                    <div style="font-size:1.5rem;">🏆</div>
                    <h6>Regional Leadership</h6>
                    <p>Be the first hospital in the region operating with data-driven OB staffing intelligence.
                    This pilot positions Poplar Bluff Regional as an innovator in maternal care delivery — a
                    distinction that matters to physicians, patients, and payers.</p>
                </div>
                <div class="hp-benefit-card">
                    <div style="font-size:1.5rem;">👩‍⚕️</div>
                    <h6>Better Patient Outcomes</h6>
                    <p>Adequately staffed shifts mean faster response times, lower nurse-to-patient ratios during
                    peak hours, and a calmer environment for laboring patients and their families.</p>
                </div>
                <div class="hp-benefit-card">
                    <div style="font-size:1.5rem;">📉</div>
                    <h6>Fewer Diversions</h6>
                    <p>When volume surges are visible days in advance, you can staff proactively rather than
                    diverting laboring patients to other facilities — keeping your community's deliveries in
                    your hands.</p>
                </div>
            </div>

            <hr class="hp-divider">

            <!-- HOW TO LAUNCH -->
            <div class="hp-section-label">Section 4</div>
            <h4 class="hp-section-title">How We Launch Together</h4>

            <div class="hp-steps">
                <div class="hp-step">
                    <div class="hp-step-num">1</div>
                    <div class="hp-step-content">
                        <h6>Hospital Signs On</h6>
                        <p>Poplar Bluff Regional designates a pilot champion — typically your L&D leaders or CNO —
                        and agrees to participate. No contract, no cost, no IT commitment required to start.</p>
                    </div>
                </div>
                <div class="hp-step">
                    <div class="hp-step-num">2</div>
                    <div class="hp-step-content">
                        <h6>Identify Participating Practices</h6>
                        <p>Work with us to identify OB physician practices in your referral network. We recommend
                        starting with 2–3 practices that account for the majority of your OB admissions.</p>
                    </div>
                </div>
                <div class="hp-step">
                    <div class="hp-step-num">3</div>
                    <div class="hp-step-content">
                        <h6>Hospital Reaches Out to Practices</h6>
                        <p>You invite each practice to participate. We provide you with the physician-facing
                        materials and talking points. The ask is simple: add a one-time EHR configuration that
                        takes about 30 minutes.</p>
                    </div>
                </div>
                <div class="hp-step">
                    <div class="hp-step-num">4</div>
                    <div class="hp-step-content">
                        <h6>Forecast Goes Live</h6>
                        <p>As practices complete their EHR setup and patients are entered, the forecast populates.
                        Your L&D leaderss begin seeing delivery projections for the next 30 days within days of
                        the first practice going live.</p>
                    </div>
                </div>
                <div class="hp-step">
                    <div class="hp-step-num">5</div>
                    <div class="hp-step-content">
                        <h6>Measure &amp; Refine</h6>
                        <p>We compare predicted vs. actual delivery volume over the pilot period. Every data point
                        makes the algorithm more accurate. The more practices that participate, the sharper the forecast.</p>
                    </div>
                </div>
            </div>

            <div class="hp-callout navy">
                <h5>🔒 Zero Risk to Your Systems</h5>
                <p>StorkSense does not connect to your hospital's EHR, EMR, or any internal system. We read
                data from physician practice EHRs only, with their explicit permission. No hospital IT involvement,
                no integration project, no security review required on your end.</p>
            </div>

            <hr class="hp-divider">

            <!-- SAMPLE FORECAST CHART -->
            <div class="hp-section-label">Sample Output</div>
            <h4 class="hp-section-title">What the Forecast Looks Like</h4>
            <p class="hp-p">
                Below is a representative 30-day delivery forecast generated from a sample patient panel.
                Each data series helps your L&D leaderss make a different staffing decision.
            </p>

            <div style="background:#f8fafc; border:1px solid #d0dae6; border-radius:8px; padding:1.25rem 1.5rem; margin-bottom:1rem;">
                <canvas id="hpForecastChart" height="120"></canvas>
            </div>

            <div style="display:flex; flex-wrap:wrap; gap:0.75rem; margin-bottom:1.5rem; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;">
                <div style="display:flex;align-items:center;gap:0.4rem;font-size:0.8rem;color:#3d4f63;">
                    <span style="width:14px;height:10px;background:rgba(13,34,64,0.75);border-radius:2px;display:inline-block;"></span> Other Deliveries
                </div>
                <div style="display:flex;align-items:center;gap:0.4rem;font-size:0.8rem;color:#3d4f63;">
                    <span style="width:14px;height:10px;background:rgba(201,150,58,0.85);border-radius:2px;display:inline-block;"></span> High-Risk Deliveries
                </div>
                <div style="display:flex;align-items:center;gap:0.4rem;font-size:0.8rem;color:#3d4f63;">
                    <span style="width:14px;height:10px;background:rgba(10,138,126,0.85);border-radius:2px;display:inline-block;"></span> First-Time Deliveries
                </div>
                <div style="display:flex;align-items:center;gap:0.4rem;font-size:0.8rem;color:#3d4f63;">
                    <span style="width:14px;height:10px;background:rgba(231,76,94,0.7);border-radius:2px;display:inline-block;flex-shrink:0;"></span> Triage Potential
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;">
                <div class="hp-callout" style="margin:0;">
                    <h5>📊 Total &amp; First-Time Deliveries</h5>
                    <p>Total deliveries drive overall staffing levels. First-time deliveries (nulliparous patients) require more nursing attention and longer labor support — so even a moderate-volume day with many nullips may need heavier staffing than a high-volume day of experienced multips.</p>
                </div>
                <div class="hp-callout gold" style="margin:0;">
                    <h5>⚠️ High-Risk &amp; Triage Potential</h5>
                    <p>High-risk deliveries flag days requiring specialized resources — NICU standby, additional anesthesia coverage, or MFM availability.</p>
                    <p style="margin-top:0.5rem;"><strong>Triage Potential</strong> is a 0–10 pressure index representing expected unscheduled L&amp;D visits — patients who arrive without a delivery admission. It is driven by three factors: <strong>office access</strong> (when the doctor's office is closed, the hospital becomes the clinic), <strong>panel composition</strong> (first-time mothers at 37–39 weeks have a low threshold to present), and <strong>active complications</strong> (preeclampsia, IUGR, oligohydramnios, GDM, and preterm labor all increase unscheduled visits). A high-triage day consumes nursing bandwidth before a single patient is admitted.</p>
                </div>
            </div>

            <!-- CLOSING -->
            <hr class="hp-divider">

            <div class="hp-callout gold">
                <h5>⭐ Why the Hospital is the Key</h5>
                <p>Physician practices participate because their hospital asks them to — and because they gain something
                valuable in return (their own panel forecast). Without a hospital champion to convene the participating
                practices, this pilot cannot launch. Poplar Bluff Regional is uniquely positioned to make this happen
                for your community.</p>
            </div>

            <p style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif; font-size:0.9rem; color:#1a2332; font-weight:600; margin-top:1.25rem;">
                For more information contact: Ryan Strenger
                &nbsp;&middot;&nbsp;
                <a href="mailto:rstrenger@productivern.com" style="color:#1a5a1a; text-decoration:none;">rstrenger@productivern.com</a>
                &nbsp;&middot;&nbsp;
                <a href="tel:2148500163" style="color:#1a5a1a; text-decoration:none;">214-850-0163</a>
            </p>

        </div>
        <!-- END BODY -->


    </div>
</div>

<script>
(function() {
    var labels = [];
    var today = new Date();
    for (var i = 0; i < 30; i++) {
        var d = new Date(today);
        d.setDate(today.getDate() + i);
        var mon = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'][d.getMonth()];
        labels.push(mon + ' ' + d.getDate());
    }
    function seeded(seed) {
        var x = Math.sin(seed + 1) * 10000;
        return x - Math.floor(x);
    }
    var highRisk = [], firstTime = [], other = [], triage = [];
    for (var i = 0; i < 30; i++) {
        var d = new Date(today);
        d.setDate(today.getDate() + i);
        var dow = d.getDay();
        var isWeekend = (dow === 0 || dow === 6);
        var base = isWeekend ? 1 + seeded(i * 3) * 1.5 : 3 + seeded(i * 3) * 3;
        base = Math.round(base * 10) / 10;
        var hr = Math.round(base * (0.2 + seeded(i * 7) * 0.2) * 10) / 10;
        var ft = Math.round(base * (0.3 + seeded(i * 11) * 0.2) * 10) / 10;
        var ot = Math.round(Math.max(0, base - hr - ft) * 10) / 10;
        var tr = Math.round((seeded(i * 13) > 0.75 ? 1 + seeded(i * 17) * 2 : seeded(i * 19) * 0.8) * 10) / 10;
        highRisk.push(hr); firstTime.push(ft); other.push(ot); triage.push(tr);
    }
    var ctx = document.getElementById('hpForecastChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                { label: 'High-Risk',        data: highRisk, backgroundColor: 'rgba(201,150,58,0.85)', borderWidth: 0 },
                { label: 'First-Time',       data: firstTime, backgroundColor: 'rgba(10,138,126,0.85)', borderWidth: 0 },
                { label: 'Other Deliveries', data: other,    backgroundColor: 'rgba(13,34,64,0.75)',   borderWidth: 0 },
                { label: 'Triage Potential', data: triage,   backgroundColor: 'rgba(231,76,94,0.7)',   borderWidth: 0 }
            ]
        },
        options: {
            responsive: true,
            legend: { display: false },
            tooltips: { mode: 'index', intersect: false },
            layout: { padding: { right: 8 } },
            scales: {
                xAxes: [{ stacked: true, offset: true, ticks: { maxTicksLimit: 10, fontSize: 11, fontColor: '#6b7f96' }, gridLines: { color: 'rgba(0,0,0,0.05)' } }],
                yAxes: [{ stacked: true, ticks: { beginAtZero: true, fontSize: 11, fontColor: '#6b7f96' }, gridLines: { color: 'rgba(0,0,0,0.05)' }, scaleLabel: { display: true, labelString: 'Expected Deliveries / Triage', fontSize: 11, fontColor: '#6b7f96' } }]
            }
        }
    });
})();

</script>
