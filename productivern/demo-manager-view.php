<?php
require_once 'inc/class.db.php';
require_once 'inc/config.php';

$token = isset($_GET['t']) ? preg_replace('/[^a-f0-9]/', '', $_GET['t']) : '';
if (empty($token)) die('<p style="font-family:sans-serif;text-align:center;margin-top:40px;">Invalid link.</p>');

$db = Config::get('db');
$tokenRow = $db->get_results("SELECT * FROM manager_tokens WHERE token='{$token}' AND context='qbl_demo' LIMIT 1");
if (!$tokenRow) die('<p style="font-family:sans-serif;text-align:center;margin-top:40px;">Link not found.</p>');
$tok = $tokenRow[0];
if (strtotime($tok['expires_at']) < time()) {
    die('<p style="font-family:sans-serif;text-align:center;margin-top:40px;">This demo link has expired. <a href="https://qbl.productivern.com/demo.php">Try the demo again.</a></p>');
}

$leadId = intval($tok['ref_id']);
$lead = $leadId > 0 ? $db->get_results("SELECT * FROM qbl_demo_leads WHERE id={$leadId} LIMIT 1") : null;
$qblValue = ($lead && isset($lead[0]['qbl_value'])) ? floatval($lead[0]['qbl_value']) : 1255;
$totalWt  = ($lead && !empty($lead[0]['total_weight'])) ? floatval($lead[0]['total_weight']) : round($qblValue * 1.06, 0);
$contactSubmitted = ($lead && !empty($lead[0]['contact_email']));

$qblClass = $qblValue >= 1000 ? 'red' : ($qblValue >= 500 ? 'orange' : '');
$pct      = $totalWt > 0 ? round(($qblValue / $totalWt) * 100, 1) : 0;

// Supplies from config 533 — qtys overridden by saved lead data
$savedQtys = [];
if ($lead && !empty($lead[0]['supplies_json'])) {
    $savedQtys = json_decode($lead[0]['supplies_json'], true) ?: [];
}
$allSupplies = [
    ['img'=>'pinkpad.jpg',     'name'=>'Pink Pad',             'ml'=>25,  'qty'=>0],
    ['img'=>'peripad.jpg',     'name'=>'Peri Pad',             'ml'=>20,  'qty'=>0],
    ['img'=>'bluechux.jpg',    'name'=>'Blue Paper Chux',      'ml'=>95,  'qty'=>1],
    ['img'=>'clothbedpad.jpg', 'name'=>'Cloth Bed Pad',        'ml'=>365, 'qty'=>0],
    ['img'=>'bamsheet.jpg',    'name'=>'BAM Sheet',            'ml'=>465, 'qty'=>1],
    ['img'=>'drape34.jpg',     'name'=>'3/4 Drape',            'ml'=>105, 'qty'=>0],
    ['img'=>'bandage3.jpg',    'name'=>'Single Lap',           'ml'=>20,  'qty'=>0],
    ['img'=>'bathtowel.jpg',   'name'=>'Bath Towel',           'ml'=>695, 'qty'=>1],
    ['img'=>'bluetowel.jpg',   'name'=>'Blue Towel',           'ml'=>50,  'qty'=>0],
    ['img'=>'coldpack.jpg',    'name'=>'Cold Pack',            'ml'=>210, 'qty'=>0],
    ['img'=>'lapholder.jpg',   'name'=>'Lap Holder',           'ml'=>20,  'qty'=>0],
    ['img'=>'ubdrape.jpg',     'name'=>'Under-Buttocks Drape', 'ml'=>125, 'qty'=>0],
    ['img'=>'redbag.jpg',      'name'=>'Red Bag',              'ml'=>70,  'qty'=>0],
    ['img'=>'towel.jpg',       'name'=>'Towel',                'ml'=>175, 'qty'=>0],
    ['img'=>'washcloth.jpg',   'name'=>'Wash Cloth',           'ml'=>30,  'qty'=>0],
];
if (!empty($savedQtys)) {
    foreach ($allSupplies as &$s) {
        $s['qty'] = isset($savedQtys[$s['name']]) ? intval($savedQtys[$s['name']]) : 0;
    }
    unset($s);
}
$supplies = array_filter($allSupplies, fn($s) => $s['qty'] > 0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>QBL Alert — Demo</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;background:#f0f2f5;color:#222;min-height:100vh}
.header{background:#1a4a8a;color:#fff;padding:14px 16px}
.header h1{font-size:.95rem;font-weight:700}
.header p{font-size:.75rem;opacity:.75;margin-top:2px}
.demo-ribbon{background:#0e6b6b;color:#fff;padding:10px 16px;font-size:.8rem;display:flex;align-items:center;gap:8px;line-height:1.4}
.demo-ribbon strong{font-weight:700}

/* Alert card */
.alert-card{margin:16px 14px 0;background:#fff;border-radius:10px 10px 0 0;overflow:hidden}
.alert-header{background:#c0392b;padding:12px 14px;color:#fff}
.alert-dept{font-size:1rem;font-weight:700}
.alert-time{font-size:.75rem;opacity:.75;margin-top:2px}
.qbl-totals{display:flex;gap:1px;background:#e8e8e8}
.qbl-total{flex:1;background:#fff;padding:10px;text-align:center}
.qbl-total .val{font-size:1.4rem;font-weight:800;line-height:1}
.qbl-total .val.red{color:#c0392b}
.qbl-total .val.orange{color:#e67e22}
.qbl-total .lbl{font-size:.62rem;color:#888;text-transform:uppercase;letter-spacing:.5px;margin-top:3px}
.supply-items{padding:4px 0;border-top:1px solid #f0f0f0}
.supply-item{display:flex;align-items:center;justify-content:space-between;padding:7px 14px;border-bottom:1px solid #f5f5f5;font-size:.82rem;gap:8px}
.supply-item:last-child{border-bottom:none}
.supply-item .iinfo{display:flex;align-items:center;gap:8px;flex:1}
.supply-item img{width:36px;height:36px;object-fit:contain;border-radius:4px;background:#f8f9fb;border:1px solid #eee;flex-shrink:0}
.supply-item .iname{color:#444}
.supply-item .iamt{font-weight:700;color:#c0392b;flex-shrink:0}
.hemorrhage-bar{padding:9px 14px;background:#fff0f0;font-size:.82rem;font-weight:700;color:#c0392b;display:flex;align-items:center;gap:6px}
.last-card{border-radius:0 0 10px 10px;margin-bottom:0}
.close-note{margin:0 14px;background:#fff;border-radius:0 0 10px 10px;padding:11px 14px;border-top:1px solid #eee;font-size:.78rem;color:#888;font-style:italic}

/* Demo callout boxes */
.callout{margin:16px 14px 0;border-radius:10px;overflow:hidden}
.callout-header{background:#0e6b6b;color:#fff;padding:10px 14px;font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;display:flex;align-items:center;gap:6px}
.callout-body{background:#fff;padding:14px;font-size:.85rem;color:#333;line-height:1.6}
.callout-body:last-child{border-radius:0 0 10px 10px}
.recipient-list{margin-top:10px}
.recipient-row{display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid #f0f0f0}
.recipient-row:last-child{border-bottom:none}
.recipient-icon{width:36px;height:36px;border-radius:50%;background:#e8f0fb;display:flex;align-items:center;justify-content:center;font-size:.9rem;flex-shrink:0}
.recipient-name{font-size:.85rem;font-weight:700;color:#1a2332}
.recipient-role{font-size:.75rem;color:#6b7f96}
.threshold-table{margin-top:10px;width:100%;border-collapse:collapse;font-size:.82rem}
.threshold-table th{text-align:left;font-size:.68rem;font-weight:700;color:#6b7f96;text-transform:uppercase;letter-spacing:.5px;padding-bottom:6px;border-bottom:2px solid #f0f0f0}
.threshold-table td{padding:7px 0;border-bottom:1px solid #f5f5f5;color:#333}
.threshold-table td:last-child{text-align:right;font-weight:700;color:#1a4a8a}
.threshold-table tr:last-child td{border-bottom:none}

/* Contact form */
.contact-card{margin:16px 14px 32px;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.07)}
.contact-header{background:#1a4a8a;color:#fff;padding:12px 14px;font-size:.95rem;font-weight:700}
.contact-header p{font-size:.78rem;opacity:.8;font-weight:400;margin-top:3px}
.contact-body{padding:16px}
.form-field{margin-bottom:14px}
.form-field label{display:block;font-size:.72rem;font-weight:700;color:#6b7f96;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px}
.form-field input,.form-field textarea{width:100%;border:1px solid #dde1e8;border-radius:8px;padding:11px 12px;font-size:.9rem;outline:none;font-family:inherit;color:#1a2332}
.form-field input:focus,.form-field textarea:focus{border-color:#1a4a8a}
.form-field textarea{resize:vertical;min-height:80px}
.submit-btn{display:block;width:100%;background:#1a4a8a;color:#fff;border:none;border-radius:8px;padding:13px;font-size:.95rem;font-weight:700;cursor:pointer;letter-spacing:.3px}
.submit-btn:active{background:#163d73}
.submit-btn:disabled{background:#a0aec0;cursor:not-allowed}
.success-msg{display:none;background:#e8f8f0;border-radius:8px;padding:14px;text-align:center;font-size:.9rem;color:#1a7a4a;font-weight:600;margin-top:14px}

.footer{text-align:center;font-size:.72rem;color:#aaa;padding:0 0 24px}
</style>
</head>
<body>

<div class="header">
  <h1>QBL Alert &mdash; <?php echo date('M j, Y'); ?></h1>
  <p>ProductivRN</p>
</div>

<div class="demo-ribbon">
  <span>&#9432;</span>
  <div><strong>Interactive Demo</strong> &mdash; This is a sample alert. Scroll down to see how recipients and thresholds work, then request a live setup for your unit.</div>
</div>

<!-- QBL Alert Card -->
<div class="alert-card">
  <div class="alert-header">
    <div class="alert-dept">Labor &amp; Delivery</div>
    <div class="alert-time"><?php echo date('M j, Y g:i A'); ?></div>
  </div>
  <div class="qbl-totals">
    <div class="qbl-total"><div class="val <?php echo $qblClass; ?>"><?php echo number_format($qblValue, 0); ?>ml</div><div class="lbl">Total QBL</div></div>
    <div class="qbl-total"><div class="val"><?php echo $totalWt; ?>g</div><div class="lbl">Total Wt</div></div>
    <div class="qbl-total"><div class="val <?php echo $pct >= 75 ? 'red' : ''; ?>"><?php echo $pct; ?>%</div><div class="lbl">QBL/Wt</div></div>
  </div>
  <div class="supply-items">
    <?php foreach ($supplies as $s): $ml = $s['qty'] * $s['ml']; ?>
    <div class="supply-item">
      <div class="iinfo"><img src="qblimages/<?php echo $s['img']; ?>" alt="<?php echo htmlspecialchars($s['name']); ?>"><span class="iname"><?php echo htmlspecialchars($s['name']); ?> &times;<?php echo $s['qty']; ?></span></div>
      <span class="iamt"><?php echo $ml; ?>ml</span>
    </div>
    <?php endforeach; ?>
  </div>
  <?php if ($qblValue >= 1000): ?>
  <div class="hemorrhage-bar">&#9888; Possible Hemorrhage</div>
  <?php elseif ($pct >= 75): ?>
  <div class="hemorrhage-bar" style="background:#fff3cd;color:#856404;">&#9888; Watch Patient — QBL/Wt ratio over 75%, possible early hemorrhage</div>
  <?php endif; ?>
</div>
<div class="close-note last-card">In a live alert, recipients can close this record once the situation is resolved.</div>

<!-- Callout: Multiple Recipients -->
<div class="callout">
  <div class="callout-header">&#128101; This Alert Goes to Your Entire Team — Instantly</div>
  <div class="callout-body">
    When a QBL alert is created, every configured recipient receives this text at the same time — no manual calls, no delay.
    <div class="recipient-list">
      <div class="recipient-row"><div class="recipient-icon">&#128104;&#8205;&#9877;</div><div><div class="recipient-name">Sarah M.</div><div class="recipient-role">Charge Nurse, L&amp;D</div></div></div>
      <div class="recipient-row"><div class="recipient-icon">&#127968;</div><div><div class="recipient-name">Michael T.</div><div class="recipient-role">House Supervisor</div></div></div>
      <div class="recipient-row"><div class="recipient-icon">&#128138;</div><div><div class="recipient-name">Jennifer K.</div><div class="recipient-role">Pharmacy</div></div></div>
      <div class="recipient-row"><div class="recipient-icon">&#128203;</div><div><div class="recipient-name">Dr. Amanda R.</div><div class="recipient-role">OB Anesthesia</div></div></div>
      <div class="recipient-row"><div class="recipient-icon">&#127970;</div><div><div class="recipient-name">Carla N.</div><div class="recipient-role">Chief Nursing Officer</div></div></div>
    </div>
  </div>
</div>

<!-- Callout: Personalized Thresholds -->
<div class="callout" style="margin-top:12px">
  <div class="callout-header">&#127919; Personalized Alert Thresholds</div>
  <div class="callout-body">
    Each team member sets the QBL value that matters to their role. Nobody gets paged unnecessarily — alerts fire only when the number crosses <em>their</em> threshold.
    <table class="threshold-table">
      <thead><tr><th>Recipient</th><th>Role</th><th>Alerts at</th></tr></thead>
      <tbody>
        <tr><td>Sarah M.</td><td>Charge Nurse</td><td>500ml</td></tr>
        <tr><td>Michael T.</td><td>House Supervisor</td><td>750ml</td></tr>
        <tr><td>Jennifer K.</td><td>Pharmacy</td><td>1000ml</td></tr>
        <tr><td>Dr. Amanda R.</td><td>Anesthesia</td><td>500ml</td></tr>
        <tr><td>Carla N.</td><td>CNO</td><td>1000ml</td></tr>
      </tbody>
    </table>
  </div>
</div>

<!-- Deployment Callout -->
<div class="callout" style="margin-top:12px">
  <div class="callout-header">&#128247; Zero Friction Deployment — QR Codes on the Scale</div>
  <div class="callout-body">
    Nurses access the QBL calculator instantly by scanning a QR code mounted directly on the scale.
    <div style="margin-top:12px;display:flex;flex-direction:column;gap:10px">
      <div style="display:flex;align-items:flex-start;gap:10px">
        <div style="width:32px;height:32px;border-radius:50%;background:#e8f0fb;display:flex;align-items:center;justify-content:center;font-size:.85rem;flex-shrink:0">&#9312;</div>
        <div style="font-size:.85rem;color:#333;line-height:1.5"><strong>Scan the QR code</strong> on the scale — opens directly in the phone's browser, no app required</div>
      </div>
      <div style="display:flex;align-items:flex-start;gap:10px">
        <div style="width:32px;height:32px;border-radius:50%;background:#e8f0fb;display:flex;align-items:center;justify-content:center;font-size:.85rem;flex-shrink:0">&#9313;</div>
        <div style="font-size:.85rem;color:#333;line-height:1.5"><strong>Enter the total weight</strong> from the scale and select supplies used</div>
      </div>
      <div style="display:flex;align-items:flex-start;gap:10px">
        <div style="width:32px;height:32px;border-radius:50%;background:#e8f0fb;display:flex;align-items:center;justify-content:center;font-size:.85rem;flex-shrink:0">&#9314;</div>
        <div style="font-size:.85rem;color:#333;line-height:1.5"><strong>Tap Calculate</strong> — QBL is calculated instantly and alerts go out to the entire team in seconds</div>
      </div>
    </div>
    <div style="margin-top:14px;background:#f0f6ff;border-radius:8px;padding:12px;font-size:.82rem;color:#1a4a8a;line-height:1.5">
      <strong>Setup is simple:</strong> We print QR codes specific to your unit's supply configuration. Laminate them, stick them on the scale. Done.
    </div>
  </div>
</div>

<!-- Contact Form -->
<div class="contact-card" style="margin-top:16px">
  <div class="contact-header">
    <div>Want This for Your Unit?</div>
    <p>Setup takes less than a day. No IT involvement required.</p>
  </div>
  <div class="contact-body">
    <?php if ($contactSubmitted): ?>
    <div style="text-align:center;padding:20px 0;color:#1a7a4a;font-weight:700;font-size:.95rem">&#10003; We received your request — we'll be in touch soon!</div>
    <?php else: ?>
    <div class="form-field"><label>Your Name *</label><input type="text" id="cf-name" placeholder="Jane Smith"></div>
    <div class="form-field"><label>Hospital / Organization *</label><input type="text" id="cf-hospital" placeholder="St. Mary's Medical Center"></div>
    <div class="form-field"><label>Email *</label><input type="email" id="cf-email" placeholder="jane@hospital.org"></div>
    <div class="form-field"><label>Phone</label><input type="tel" id="cf-phone" placeholder="(555) 555-5555"></div>
    <div class="form-field"><label>Message</label><textarea id="cf-message" placeholder="Tell us about your unit or any questions..."></textarea></div>
    <button class="submit-btn" id="cf-submit" onclick="submitContact()">Request a Demo Setup</button>
    <div class="success-msg" id="cf-success">&#10003; Received! We'll be in touch within one business day.</div>
    <?php endif; ?>
  </div>
</div>

<div class="footer">ProductivRN &mdash; productivern.com</div>

<script>
var LEAD_ID = <?php echo $leadId; ?>;

function submitContact() {
  var name = document.getElementById('cf-name').value.trim();
  var hospital = document.getElementById('cf-hospital').value.trim();
  var email = document.getElementById('cf-email').value.trim();
  if (!name || !email) { alert('Please enter your name and email.'); return; }
  var btn = document.getElementById('cf-submit');
  btn.disabled = true; btn.textContent = 'Submitting...';
  fetch('inc/data.php?req=submitdemolead', {
    method: 'POST',
    headers: {'Content-Type':'application/x-www-form-urlencoded'},
    body: 'leadId=' + encodeURIComponent(LEAD_ID)
      + '&name=' + encodeURIComponent(name)
      + '&hospital=' + encodeURIComponent(hospital)
      + '&email=' + encodeURIComponent(email)
      + '&phone=' + encodeURIComponent((document.getElementById('cf-phone').value||''))
      + '&message=' + encodeURIComponent((document.getElementById('cf-message').value||''))
  })
  .then(function(r){ return r.json(); })
  .then(function(data) {
    if (data.success) {
      btn.style.display = 'none';
      document.getElementById('cf-success').style.display = 'block';
    } else {
      btn.disabled = false; btn.textContent = 'Request a Demo Setup';
      alert(data.message || 'Error submitting. Please try again.');
    }
  })
  .catch(function() {
    btn.disabled = false; btn.textContent = 'Request a Demo Setup';
    alert('Network error. Please try again.');
  });
}
</script>
</body>
</html>
