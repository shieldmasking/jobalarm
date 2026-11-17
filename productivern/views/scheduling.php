<?php
include "../inc/initializer.php";
require_once '.././inc/class.db.php';
require_once '.././inc/config.php';

$role = intval($_SESSION['account']['role']);
?>

<style>
.ehr-letterhead {
    background: linear-gradient(135deg, #0d2240 0%, #163358 60%, #1e4a7a 100%);
    border-radius: 8px 8px 0 0;
    padding: 2rem 2.5rem 1.5rem;
    position: relative;
    overflow: hidden;
}
.ehr-letterhead::after {
    content: '';
    position: absolute;
    bottom: -30px; right: -30px;
    width: 160px; height: 160px;
    border-radius: 50%;
    border: 32px solid rgba(10,138,126,0.18);
    pointer-events: none;
}
.ehr-brand h2 {
    font-size: 1.6rem;
    font-weight: 800;
    color: #fff;
    margin: 0;
    letter-spacing: -0.02em;
}
.ehr-brand p {
    font-size: 0.7rem;
    color: rgba(255,255,255,0.5);
    letter-spacing: 0.12em;
    text-transform: uppercase;
    margin: 0.2rem 0 0;
}
.ehr-banner {
    background: linear-gradient(90deg, #0a8a7e, #0eb8a9);
    padding: 0.7rem 2.5rem;
    font-size: 0.82rem;
    color: #fff;
    font-weight: 500;
    letter-spacing: 0.02em;
}
.ehr-body {
    background: #fff;
    padding: 2rem 2.5rem 2.5rem;
    border: 1px solid #d0dae6;
    border-top: none;
    border-radius: 0 0 8px 8px;
}
.ehr-section-label {
    font-size: 0.62rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.14em;
    color: #0a8a7e;
    margin-bottom: 0.3rem;
}
.ehr-section-title {
    font-size: 1.15rem;
    font-weight: 700;
    color: #0d2240;
    margin-bottom: 0.9rem;
}
.ehr-p {
    font-size: 0.95rem;
    color: #3d4f63;
    line-height: 1.8;
    margin-bottom: 1.2rem;
}
.ehr-fields-block {
    border: 1px solid #d0dae6;
    border-radius: 6px;
    overflow: hidden;
    margin: 1.2rem 0;
}
.ehr-fields-header {
    background: #0d2240;
    padding: 0.6rem 1rem;
    font-size: 0.7rem;
    font-weight: 600;
    color: rgba(255,255,255,0.65);
    text-transform: uppercase;
    letter-spacing: 0.08em;
    display: flex;
    gap: 1.5rem;
}
.ehr-fields-header span:first-child { flex: 0 0 180px; }
.ehr-field-row {
    display: flex;
    align-items: baseline;
    gap: 1.5rem;
    padding: 0.7rem 1rem;
    border-bottom: 1px solid #e8eef5;
    font-size: 0.875rem;
}
.ehr-field-row:last-child { border-bottom: none; }
.ehr-field-row:nth-child(even) { background: rgba(10,138,126,0.03); }
.ehr-field-name {
    font-weight: 600;
    color: #0d2240;
    flex: 0 0 180px;
    font-size: 0.835rem;
}
.ehr-badge {
    display: inline-block;
    padding: 0.12rem 0.5rem;
    border-radius: 4px;
    font-size: 0.65rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-right: 0.5rem;
    flex-shrink: 0;
}
.ehr-badge.num  { background: rgba(201,150,58,0.12); color: #8a6020; }
.ehr-badge.sel  { background: rgba(10,138,126,0.1);  color: #0a8a7e; }
.ehr-badge.multi{ background: rgba(13,34,64,0.08);   color: #0d2240; }
.ehr-badge.bool { background: rgba(99,102,241,0.1);  color: #4f46e5; }
.ehr-badge.date { background: rgba(239,68,68,0.1);   color: #b91c1c; }
.ehr-field-desc { font-size: 0.825rem; color: #6b7f96; flex: 1; }
.ehr-comp-pills { margin-top: 0.4rem; display: flex; flex-wrap: wrap; gap: 0.35rem; }
.ehr-pill {
    display: inline-block;
    padding: 0.15rem 0.5rem;
    background: rgba(13,34,64,0.05);
    border: 1px solid #d0dae6;
    border-radius: 100px;
    font-size: 0.68rem;
    color: #3d4f63;
}
.ehr-callout {
    border-left: 4px solid #0a8a7e;
    background: #e6f7f5;
    padding: 1rem 1.25rem;
    border-radius: 0 6px 6px 0;
    margin: 1.2rem 0;
}
.ehr-callout h5 {
    font-size: 0.85rem;
    font-weight: 700;
    color: #0a8a7e;
    margin-bottom: 0.35rem;
}
.ehr-callout p { font-size: 0.875rem; color: #3d4f63; margin: 0; line-height: 1.65; }
.ehr-callout.navy {
    border-left-color: #0d2240;
    background: rgba(13,34,64,0.04);
}
.ehr-callout.navy h5 { color: #0d2240; }
.ehr-flow {
    display: flex;
    gap: 0;
    margin: 1.2rem 0;
    position: relative;
}
.ehr-flow::before {
    content: '';
    position: absolute;
    top: 24px; left: 24px; right: 24px;
    height: 2px;
    background: linear-gradient(90deg, #0d2240, #0a8a7e);
    z-index: 0;
}
.ehr-flow-step { flex: 1; text-align: center; position: relative; z-index: 1; padding: 0 0.4rem; }
.ehr-step-num {
    width: 48px; height: 48px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; font-weight: 700;
    margin: 0 auto 0.6rem;
    border: 3px solid #fff;
    box-shadow: 0 0 0 3px #d0dae6;
    color: #fff;
}
.ehr-flow-step:nth-child(1) .ehr-step-num { background: #0d2240; }
.ehr-flow-step:nth-child(2) .ehr-step-num { background: #163358; }
.ehr-flow-step:nth-child(3) .ehr-step-num { background: #0a8a7e; }
.ehr-flow-step:nth-child(4) .ehr-step-num { background: #0eb8a9; }
.ehr-step-title { font-size: 0.78rem; font-weight: 700; color: #0d2240; margin-bottom: 0.25rem; }
.ehr-step-desc  { font-size: 0.72rem; color: #6b7f96; line-height: 1.45; }
.ehr-shield {
    display: flex; gap: 1.25rem; align-items: flex-start;
    background: rgba(13,34,64,0.03);
    border: 1px solid #d0dae6;
    border-radius: 6px;
    padding: 1.25rem;
    margin: 1.2rem 0;
}
.ehr-shield-icon {
    width: 50px; height: 50px;
    background: linear-gradient(135deg, #0d2240, #1e4a7a);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; flex-shrink: 0;
}
.ehr-shield-content h5 { font-size: 0.95rem; font-weight: 700; color: #0d2240; margin-bottom: 0.4rem; }
.ehr-shield-content p  { font-size: 0.85rem; color: #3d4f63; line-height: 1.65; margin-bottom: 0.6rem; }
.ehr-check-item {
    display: flex; align-items: flex-start; gap: 0.5rem;
    font-size: 0.82rem; color: #3d4f63;
    margin-bottom: 0.3rem;
}
.ehr-check-item::before {
    content: '✓';
    width: 16px; height: 16px;
    background: #0a8a7e; color: #fff;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.55rem; font-weight: 800;
    flex-shrink: 0; margin-top: 1px;
}
.ehr-stat-strip {
    background: linear-gradient(135deg, #0d2240, #163358);
    border-radius: 6px;
    padding: 1.25rem 1.5rem;
    display: flex;
    gap: 1.5rem;
    margin: 1.2rem 0;
    justify-content: space-around;
}
.ehr-stat-num   { font-size: 1.8rem; font-weight: 800; color: #0eb8a9; line-height: 1; margin-bottom: 0.3rem; }
.ehr-stat-label { font-size: 0.68rem; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 0.07em; line-height: 1.35; }
.ehr-stat-div   { width: 1px; background: rgba(255,255,255,0.12); flex-shrink: 0; }
.ehr-benefit-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0.85rem; margin: 1.2rem 0; }
.ehr-benefit-card {
    background: #f8fafc;
    border: 1px solid #d0dae6;
    border-radius: 6px;
    padding: 1rem;
    position: relative; overflow: hidden;
}
.ehr-benefit-card::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 3px;
    background: linear-gradient(90deg, #0d2240, #0a8a7e);
}
.ehr-benefit-card h6 { font-size: 0.85rem; font-weight: 700; color: #0d2240; margin: 0.4rem 0 0.35rem; }
.ehr-benefit-card p  { font-size: 0.8rem; color: #6b7f96; line-height: 1.5; margin: 0; }
.ehr-divider { border: none; border-top: 2px solid #e8eef5; margin: 1.8rem 0 1.5rem; }

@media print {
    .m-header, #m_aside_left, .m-aside-left-close, .m-subheader, #printPitchBtn, .ehr-back, footer { display: none !important; }
    .m-wrapper { margin: 0 !important; padding: 0 !important; }
    .m-content { padding: 0 !important; }
    body { background: #fff !important; }
    #printPitchWrap { max-width: 100% !important; }

    /* Force color backgrounds to print */
    .ehr-letterhead, .ehr-banner, .ehr-callout, .ehr-stat-strip, .ehr-benefit-card,
    .ehr-shield, .ehr-fields-header, .ehr-step-num, .ehr-fields-block {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    /* Never break inside these elements */
    .ehr-callout, .ehr-shield, .ehr-stat-strip, .ehr-benefit-card,
    .ehr-field-row, .ehr-flow, .ehr-fields-block { page-break-inside: avoid; }

    /* Keep section label + title together with the content that follows */
    .ehr-section-label, .ehr-section-title { page-break-after: avoid; }

    /* Force new page before sections 2, 3, and the chart */
    .ehr-page-break { page-break-before: always; }
}
</style>

<div class="m-content">
    <div class="m-subheader">
        <div class="mr-auto">
            <h3 class="m-subheader__title">StorkSense Pilot</h3>
        </div>
    </div>

    <div id="printPitchWrap" style="max-width: 860px; margin: 0 auto;">

        <button id="printPitchBtn" onclick="window.print()" style="margin-bottom:1rem;display:flex;align-items:center;gap:0.5rem;background:#0d2240;color:#fff;border:none;border-radius:6px;padding:0.55rem 1.1rem;font-size:0.82rem;font-weight:600;cursor:pointer;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;">
            🖨️ Print / Save as PDF
        </button>

        <a class="ehr-back" onclick="location.hash='#hospitalpitch';" style="display:flex;align-items:center;gap:0.4rem;font-size:0.82rem;color:#2d7a2d;cursor:pointer;margin-bottom:1rem;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;font-weight:600;text-decoration:none;">
            🏥 View Hospital Partnership Pitch &rarr;
        </a>

        <!-- LETTERHEAD -->
        <div class="ehr-letterhead">
            <div class="ehr-brand">
                <h2><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 112 72" width="52" height="34" style="vertical-align:middle;margin-right:0.45rem;margin-bottom:0.1rem;">
  <!-- Upper wing -->
  <path d="M48,40 Q28,14 4,8 Q16,24 36,36 Z" fill="white"/>
  <path d="M4,8 Q0,10 0,16 Q8,16 18,22 Q10,14 4,8 Z" fill="#0d2240"/>
  <!-- Body -->
  <ellipse cx="50" cy="44" rx="18" ry="9" fill="white"/>
  <ellipse cx="41" cy="41" rx="7" ry="4" fill="#0d2240" transform="rotate(-15,41,41)"/>
  <!-- Tail feathers -->
  <path d="M33,48 Q16,56 10,60 Q20,52 30,50 Z" fill="white"/>
  <path d="M34,52 Q20,62 16,66 Q25,58 33,54 Z" fill="#e0e0e0"/>
  <!-- Neck -->
  <path d="M62,38 Q72,28 76,22" stroke="white" stroke-width="7" fill="none" stroke-linecap="round"/>
  <!-- Head -->
  <circle cx="77" cy="20" r="7" fill="white"/>
  <path d="M71,15 Q77,11 83,15 Q80,20 77,20 Q74,20 71,15 Z" fill="#0d2240"/>
  <!-- Eye -->
  <circle cx="79" cy="19" r="2.2" fill="#0d2240"/>
  <circle cx="79.8" cy="18.2" r="0.8" fill="white"/>
  <!-- Beak -->
  <path d="M83,20 L110,27 L83,24 Z" fill="#e06000"/>
  <!-- Bundle string -->
  <path d="M108,27 Q104,31 96,36" stroke="#bbb" stroke-width="1.3" fill="none"/>
  <!-- Bundle cloth (teardrop) -->
  <path d="M96,36 C97,36 103,40 103,46 C103,52 100,54 96,54 C92,54 89,52 89,46 C89,40 95,36 96,36 Z" fill="#f5e6c8" stroke="#c9a860" stroke-width="1.5"/>
  <!-- Knot -->
  <path d="M92,37 Q96,34 100,37" stroke="#c9a860" stroke-width="1.8" fill="none" stroke-linecap="round"/>
  <!-- Bundle texture dots -->
  <circle cx="93" cy="46" r="1" fill="#c9a860" opacity="0.4"/>
  <circle cx="99" cy="44" r="0.9" fill="#c9a860" opacity="0.4"/>
  <circle cx="96" cy="50" r="1" fill="#c9a860" opacity="0.4"/>
</svg>StorkSense</h2>
                <p>Obstetric Delivery Intelligence &nbsp;·&nbsp; EHR Integration Request</p>
            </div>
        </div>
        <div class="ehr-banner">
            A 5-minute EHR configuration. A measurable impact on every patient your hospital delivers.
        </div>

        <!-- BODY -->
        <div class="ehr-body">

            <div class="ehr-section-label">Overview</div>
            <h4 class="ehr-section-title" style="margin-top:0.5rem;">Partnering for Success</h4>

            <p class="ehr-p">
                StorkSense is an obstetric delivery forecasting platform built for labor and delivery floors.
                It predicts delivery volume, high-risk concentration, first-time mother load, and triage pressure
                on a rolling 30-day basis — giving OB managers the visibility they need to staff proactively
                rather than react to what's already walking through the door.
            </p>

            <p class="ehr-p">
                StorkSense works because hospitals and physician practices work together.
                The forecast is only as good as the patient data feeding it — and that data
                comes from the OB practices in your referral network.
            </p>

            <div class="ehr-callout navy">
                <h5>🏥 For the Hospital</h5>
                <p>The hospital's role is straightforward: identify the OB practices delivering
                to your floor, and invite them to participate. Practices follow their hospital's
                lead. A single conversation from your OB director or CNO carries more weight
                than anything we can send on our own. Once participating practices go live,
                your OB managers gain access to a rolling 30-day delivery forecast — total volume,
                high-risk concentration, first-time mother load, and triage pressure — built from
                the actual patient panels delivering to your floor.</p>
            </div>

            <div class="ehr-callout">
                <h5>🩺 For the Doctors</h5>
                <p>We are requesting a small, one-time configuration in your EHR implementation:
                the addition of custom OB fields to your patient charts. This is the only thing
                we need. The benefit flows directly to your patients.
            </p>
            <p class="ehr-p">
                StorkSense reads these fields via the EHR API, runs our delivery forecast algorithm, and
                <strong>immediately discards the source data</strong>. No PHI is ever retained —
                only an anonymized delivery window estimate.</p>
            </div>

            <hr class="ehr-divider">

            <!-- THE ASK -->
            <div class="ehr-section-label">Section 1</div>
            <h4 class="ehr-section-title">Custom Fields to Add</h4>

            <div class="ehr-fields-block">
                <div class="ehr-fields-header">
                    <span>Field Name</span>
                    <span>Type / Description</span>
                </div>
                <div class="ehr-field-row">
                    <div class="ehr-field-name">EDD</div>
                    <div><span class="ehr-badge date">Date</span><span class="ehr-field-desc">Estimated Due Date (40-week EDD)</span></div>
                </div>
                <div class="ehr-field-row">
                    <div class="ehr-field-name">Gravida</div>
                    <div><span class="ehr-badge num">Number</span><span class="ehr-field-desc">Total number of pregnancies, including current</span></div>
                </div>
                <div class="ehr-field-row">
                    <div class="ehr-field-name">Para</div>
                    <div><span class="ehr-badge num">Number</span><span class="ehr-field-desc">Number of prior deliveries past 20 weeks gestation</span></div>
                </div>
                <div class="ehr-field-row">
                    <div class="ehr-field-name">Previous Delivery Type</div>
                    <div><span class="ehr-badge sel">Select</span><span class="ehr-field-desc">Vaginal &nbsp;/&nbsp; C-Section &nbsp;/&nbsp; None</span></div>
                </div>
                <div class="ehr-field-row">
                    <div class="ehr-field-name">Active Complications / History</div>
                    <div style="flex:1;">
                        <span class="ehr-badge multi">Multi-Select</span>
                        <span class="ehr-field-desc">Any applicable active complicating diagnoses or history of diagnosis</span>
                        <div class="ehr-comp-pills">
                            <span class="ehr-pill">Preeclampsia</span>
                            <span class="ehr-pill">Chronic Hypertension</span>
                            <span class="ehr-pill">IUGR</span>
                            <span class="ehr-pill">Multiple Gestation</span>
                            <span class="ehr-pill">Gestational Diabetes</span>
                            <span class="ehr-pill">Cholestasis</span>
                            <span class="ehr-pill">GBS Positive</span>
                            <span class="ehr-pill">Placenta Previa</span>
                            <span class="ehr-pill">Preterm Labor</span>
                            <span class="ehr-pill">Oligohydramnios</span>
                            <span class="ehr-pill">Polyhydramnios</span>
                            <span class="ehr-pill">Placenta Accreta</span>
                            <span class="ehr-pill">Maternal Cardiac Disease</span>
                        </div>
                    </div>
                </div>
                <div class="ehr-field-row">
                    <div class="ehr-field-name">Planned Induction Date</div>
                    <div><span class="ehr-badge date">Date</span><span class="ehr-field-desc">Target date if induction is planned</span></div>
                </div>
                <div class="ehr-field-row">
                    <div class="ehr-field-name">Scheduled C-Section Date</div>
                    <div><span class="ehr-badge date">Date</span><span class="ehr-field-desc">Scheduled date for elective or repeat cesarean delivery</span></div>
                </div>
            </div>

            <div class="ehr-callout navy">
                <h5>⏱ Implementation Note</h5>
                <p>Adding custom fields is a routine administrative task requiring no coding and no workflow disruption.
                Most EHR administrators can complete this configuration in under 30 minutes. We are happy to provide
                step-by-step guidance or coordinate directly with your EHR representative.</p>
            </div>

            <div class="ehr-callout" style="border-left-color:#c9963a; background:#fdf8f0;">
                <h5 style="color:#8a6020;">📋 One-Time Entry — No Ongoing Commitment</h5>
                <p>These fields are completed once at patient intake. There is no expectation that your office will
                update them during the pregnancy. Our algorithm works entirely from the initial clinical picture
                captured at first visit, combined with the patient's EDD to track gestational age over time.</p>
                <p style="margin-top:0.6rem;">The Planned Induction Date and Scheduled C-Section Date fields are optional.
                When populated, they sharpen the forecast — but the system functions fully without them.</p>
            </div>

            <hr class="ehr-divider">

            <!-- HOW IT WORKS -->
            <div class="ehr-section-label">Section 2</div>
            <h4 class="ehr-section-title">How It Works — And What We Do With Your Data</h4>

            <div class="ehr-flow">
                <div class="ehr-flow-step">
                    <div class="ehr-step-num">1</div>
                    <div class="ehr-step-title">API Read</div>
                    <div class="ehr-step-desc">StorkSense reads OB fields via the EHR API on a scheduled basis</div>
                </div>
                <div class="ehr-flow-step">
                    <div class="ehr-step-num">2</div>
                    <div class="ehr-step-title">Algorithm</div>
                    <div class="ehr-step-desc">Our forecasting model calculates the patient's likely delivery window</div>
                </div>
                <div class="ehr-flow-step">
                    <div class="ehr-step-num">3</div>
                    <div class="ehr-step-title">Output Only</div>
                    <div class="ehr-step-desc">Only the predicted date range is stored — nothing identifiable</div>
                </div>
                <div class="ehr-flow-step">
                    <div class="ehr-step-num">4</div>
                    <div class="ehr-step-title">Discard</div>
                    <div class="ehr-step-desc">All source patient data is immediately discarded after calculation</div>
                </div>
            </div>

            <div class="ehr-shield">
                <div class="ehr-shield-icon">🔒</div>
                <div class="ehr-shield-content">
                    <h5>Zero PHI Retention — By Design, Not by Policy</h5>
                    <p>StorkSense's architecture was built from day one around a single principle: we never need to store
                    what we only need to read. Our system ingests clinical indicators, runs its forecast calculation
                    in memory, and permanently discards the source data. What remains is a predicted delivery window —
                    an anonymized number, not a patient.</p>
                    <div class="ehr-check-item">No patient names, MRNs, or contact information are ever stored</div>
                    <div class="ehr-check-item">Read-only API access — we cannot write to or modify your records</div>
                    <div class="ehr-check-item">No data is sold, shared, or used for any secondary purpose</div>
                    <div class="ehr-check-item">Full audit log of every API call available upon request</div>
                    <div class="ehr-check-item">HIPAA-aligned data handling with Business Associate Agreement available</div>
                </div>
            </div>

            <div class="ehr-callout">
                <h5>🧠 The Algorithm in Plain Language</h5>
                <p>Think of it as a weather forecast for the labor and delivery floor. Not a guarantee —
                but a well-calibrated probability model that tells the hospital: <em>"Based on your current patient
                panel, expect 4–6 deliveries on Thursday, with elevated C-section likelihood."</em> That's the
                intelligence that changes staffing decisions.</p>
            </div>

            <hr class="ehr-divider">

            <!-- THE PAYOFF -->
            <div class="ehr-section-label ehr-page-break">Section 3</div>
            <h4 class="ehr-section-title">What Your Patients Gain</h4>

            <div class="ehr-benefit-grid">
                <div class="ehr-benefit-card">
                    <div style="font-size:1.5rem;">👩‍⚕️</div>
                    <h6>The Right Team, Every Time</h6>
                    <p>When the hospital knows a high-volume delivery day is coming, they staff for it. Patients
                    arrive to find an experienced nurse already briefed, ready, and not stretched thin across six rooms.</p>
                </div>
                <div class="ehr-benefit-card">
                    <div style="font-size:1.5rem;">⚡</div>
                    <h6>Faster Response in Critical Moments</h6>
                    <p>Understaffed floors respond slower. Predictive staffing means adequate coverage during
                    the hours and days when patients are statistically most likely to need immediate attention.</p>
                </div>
                <div class="ehr-benefit-card">
                    <div style="font-size:1.5rem;">💙</div>
                    <h6>A Better Birth Experience</h6>
                    <p>A calm, supported labor and delivery isn't just a nice-to-have — it's a measurable clinical
                    outcome. Patients who receive consistent, attentive care report significantly higher satisfaction.</p>
                </div>
                <div class="ehr-benefit-card">
                    <div style="font-size:1.5rem;">📉</div>
                    <h6>Fewer Diverted Patients</h6>
                    <p>Overwhelmed OB floors divert laboring patients to other hospitals. With accurate volume forecasting,
                    capacity decisions are made proactively — not reactively.</p>
                </div>
                <div class="ehr-benefit-card">
                    <div style="font-size:1.5rem;">📊</div>
                    <h6>Visibility Into Your Own Panel</h6>
                    <p>Participating physicians gain access to the StorkSense forecaster for their
                    own patient panel — see projected delivery volume, high-risk concentrations, and triage potential
                    for your patients specifically.</p>
                </div>
                <div class="ehr-benefit-card" style="border-top:3px solid #6b7f96; opacity:0.85;">
                    <div style="font-size:1.5rem;">🔭</div>
                    <h6>Coming Soon: Perinatal Navigation</h6>
                    <p>A future StorkSense module will enable automated, structured communication from the hospital directly to patients throughout their pregnancy — preparation checklists, what-to-expect milestones, and timely guidance in the final weeks before delivery. The goal: every patient arrives informed, prepared, and supported — extending the care the OB team has already started.</p>
                </div>
            </div>

            <p class="ehr-p" style="margin-top:1.5rem;">
                This is not a technology project. It is a patient care initiative that happens to use technology.
                The time it takes to add these fields in your EHR will, in aggregate, contribute to thousands of
                better-staffed delivery shifts for years to come.
            </p>

            <hr class="ehr-divider">

            <!-- SAMPLE FORECAST CHART -->
            <div class="ehr-section-label ehr-page-break">Sample Output</div>
            <h4 class="ehr-section-title">What the Forecast Looks Like</h4>
            <p class="ehr-p">
                Below is a representative 30-day delivery forecast generated from a sample patient panel.
                Each data series helps the hospital make a different staffing decision.
            </p>

            <div style="background:#f8fafc; border:1px solid #d0dae6; border-radius:8px; padding:1.25rem 1.5rem; margin-bottom:1rem;">
                <canvas id="ehrForecastChart" height="120"></canvas>
            </div>

            <div style="display:flex; flex-wrap:wrap; gap:0.75rem; margin-bottom:1.5rem;">
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
                <div class="ehr-callout" style="margin:0;">
                    <h5>📊 Total &amp; First-Time Deliveries</h5>
                    <p>Total deliveries drive overall staffing levels. First-time deliveries (nulliparous patients) require more nursing attention and longer labor support — so even a moderate-volume day with many nullips may need heavier staffing than a high-volume day of experienced multips.</p>
                </div>
                <div class="ehr-callout" style="margin:0; border-left-color:#c9963a; background:#fdf8f0;">
                    <h5 style="color:#8a6020;">⚠️ High-Risk &amp; Triage Potential</h5>
                    <p>High-risk deliveries flag days requiring specialized resources — NICU standby, additional anesthesia coverage, or MFM availability.</p>
                    <p style="margin-top:0.5rem;"><strong>Triage Potential</strong> is a 0–10 pressure index representing expected unscheduled L&amp;D visits — patients who arrive without a delivery admission. It is driven by three factors: <strong>office access</strong> (when the doctor's office is closed, the hospital becomes the clinic), <strong>panel composition</strong> (first-time mothers at 37–39 weeks have a low threshold to present), and <strong>active complications</strong> (preeclampsia, IUGR, oligohydramnios, GDM, and preterm labor all increase unscheduled visits). A high-triage day consumes nursing bandwidth before a single patient is admitted — making it a critical staffing signal even on low-delivery-volume days.</p>
                </div>
            </div>

            <p class="ehr-p" style="margin-top:1rem;">
                If you are interested in piloting StorkSense at your facility,
                we would welcome a 30-minute call with your OB Director or CNO.
            </p>

            <hr class="ehr-divider" style="margin:1.2rem 0 1rem;">

            <div style="font-size:0.85rem; color:#3d4f63;">
                <strong style="color:#0d2240;">Ryan Strenger</strong>
                &nbsp;&middot;&nbsp;
                <a href="mailto:rstrenger@productivern.com" style="color:#0a8a7e; text-decoration:none;">rstrenger@productivern.com</a>
                &nbsp;&middot;&nbsp;
                <a href="tel:2148500163" style="color:#0a8a7e; text-decoration:none;">214-850-0163</a>
            </div>

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
        var dow = d.getDay();
        var mon = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'][d.getMonth()];
        labels.push(mon + ' ' + d.getDate());
    }

    // Seed-based pseudo-random for reproducible sample data
    function seeded(seed) {
        var x = Math.sin(seed + 1) * 10000;
        return x - Math.floor(x);
    }

    // Smooth pressure envelope — 3 broad peaks that build and taper like a rolling average
    function envelope(i) {
        return 2.8 * Math.exp(-Math.pow(i - 5,  2) / 10)
             + 3.8 * Math.exp(-Math.pow(i - 14, 2) / 12)
             + 3.0 * Math.exp(-Math.pow(i - 24, 2) / 10);
    }

    var highRisk = [], firstTime = [], other = [], triage = [];
    for (var i = 0; i < 30; i++) {
        var d = new Date(today);
        d.setDate(today.getDate() + i);
        var dow = d.getDay();
        var isWeekend = (dow === 0 || dow === 6);

        var base = 2.8 + envelope(i) + seeded(i * 3) * 0.7;
        if (isWeekend) base *= 0.4;
        base = Math.round(base * 10) / 10;

        var hr = Math.round(base * (0.18 + seeded(i * 7) * 0.1) * 10) / 10;
        var ft = Math.round(base * (0.28 + seeded(i * 11) * 0.1) * 10) / 10;
        var ot = Math.round(Math.max(0, base - hr - ft) * 10) / 10;
        var tr = Math.round((envelope(i) * 0.6 + seeded(i * 19) * 1.2) * 10) / 10;

        highRisk.push(hr);
        firstTime.push(ft);
        other.push(ot);
        triage.push(tr);
    }

    var ctx = document.getElementById('ehrForecastChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'High-Risk',
                    data: highRisk,
                    backgroundColor: 'rgba(201,150,58,0.85)',
                    borderWidth: 0
                },
                {
                    label: 'First-Time',
                    data: firstTime,
                    backgroundColor: 'rgba(10,138,126,0.85)',
                    borderWidth: 0
                },
                {
                    label: 'Other Deliveries',
                    data: other,
                    backgroundColor: 'rgba(13,34,64,0.75)',
                    borderWidth: 0
                },
                {
                    label: 'Triage Potential',
                    data: triage,
                    backgroundColor: 'rgba(231,76,94,0.7)',
                    borderWidth: 0
                }
            ]
        },
        options: {
            responsive: true,
            legend: { display: false },
            tooltips: { mode: 'index', intersect: false },
            scales: {
                xAxes: [{
                    stacked: true,
                    ticks: { maxTicksLimit: 10, fontSize: 11, fontColor: '#6b7f96' },
                    gridLines: { color: 'rgba(0,0,0,0.05)' }
                }],
                yAxes: [{
                    stacked: true,
                    ticks: { beginAtZero: true, fontSize: 11, fontColor: '#6b7f96' },
                    gridLines: { color: 'rgba(0,0,0,0.05)' },
                    scaleLabel: { display: true, labelString: 'Expected Deliveries / Triage', fontSize: 11, fontColor: '#6b7f96' }
                }]
            }
        }
    });
})();
</script>
