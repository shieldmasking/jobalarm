<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>QBL Demo — ProductivRN</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;background:#f0f2f5;color:#1a2332;min-height:100vh}
.header{background:#1a4a8a;color:#fff;padding:16px;text-align:center}
.header h1{font-size:1rem;font-weight:700}
.header p{font-size:.78rem;opacity:.8;margin-top:3px}

/* Step 1: Mobile entry */
#step-mobile{padding:32px 20px}
#step-mobile h2{font-size:1.2rem;font-weight:800;color:#1a2332;margin-bottom:8px;text-align:center}
#step-mobile p{font-size:.88rem;color:#6b7f96;text-align:center;margin-bottom:28px;line-height:1.5}
.field-group{background:#fff;border-radius:12px;padding:16px;margin-bottom:16px;box-shadow:0 1px 4px rgba(0,0,0,.06)}
.field-group label{display:block;font-size:.72rem;font-weight:700;color:#6b7f96;text-transform:uppercase;letter-spacing:.6px;margin-bottom:8px}
.field-group input{width:100%;border:1px solid #dde1e8;border-radius:8px;padding:12px 14px;font-size:1.1rem;letter-spacing:2px;outline:none;color:#1a2332}
.field-group input:focus{border-color:#1a4a8a}
.btn-primary{display:block;width:100%;background:#1a4a8a;color:#fff;border:none;border-radius:10px;padding:15px;font-size:1rem;font-weight:700;cursor:pointer;letter-spacing:.3px}
.btn-primary:active{background:#163d73}
.btn-primary:disabled{background:#a0aec0;cursor:not-allowed}
.disclaimer{font-size:.72rem;color:#aaa;text-align:center;margin-top:12px;line-height:1.5}

/* Step 2: Calculator */
#step-calc{display:none;padding-bottom:40px}
.demo-banner{background:#0e6b6b;color:#fff;padding:10px 16px;font-size:.8rem;display:flex;align-items:center;gap:8px}
.demo-banner strong{font-weight:700}
.calc-section{margin:16px 16px 0}
.calc-section h3{font-size:.72rem;font-weight:700;color:#6b7f96;text-transform:uppercase;letter-spacing:.6px;margin-bottom:10px}
.supply-row{background:#fff;border-radius:10px;padding:10px 14px;margin-bottom:8px;display:flex;align-items:center;justify-content:space-between;box-shadow:0 1px 4px rgba(0,0,0,.05)}
.supply-left{display:flex;align-items:center;gap:10px;flex:1;min-width:0}
.supply-img{width:44px;height:44px;object-fit:contain;border-radius:6px;background:#f8f9fb;flex-shrink:0;border:1px solid #eee}
.supply-name{font-size:.88rem;color:#1a2332;font-weight:600;line-height:1.2}
.supply-ml{font-size:.72rem;color:#6b7f96;margin-top:2px}
.supply-qty{display:flex;align-items:center;gap:8px;flex-shrink:0}
.qty-btn{width:32px;height:32px;border-radius:8px;border:1px solid #dde1e8;background:#f8f9fb;font-size:1.1rem;font-weight:700;cursor:pointer;color:#1a2332;display:flex;align-items:center;justify-content:center}
.qty-btn:active{background:#e8ecf2}
.qty-val{width:32px;text-align:center;font-size:1rem;font-weight:700;color:#1a2332}
.result-card{margin:16px;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.08)}
.result-header{background:#c0392b;color:#fff;padding:14px 16px;display:flex;align-items:center;justify-content:space-between}
.result-header.normal{background:#1a4a8a}
.result-header.warning{background:#e67e22}
.result-qbl{font-size:2rem;font-weight:800;line-height:1}
.result-label{font-size:.75rem;opacity:.85;margin-top:3px}
.result-badge{font-size:.7rem;font-weight:700;background:rgba(255,255,255,.2);padding:4px 10px;border-radius:20px}
.result-ratio{padding:12px 16px;border-bottom:1px solid #f0f0f0;display:flex;justify-content:space-between;font-size:.85rem}
.result-ratio .lbl{color:#6b7f96}
.result-ratio .val{font-weight:700;color:#1a2332}
.btn-calc{display:block;width:calc(100% - 32px);margin:16px;background:#c0392b;color:#fff;border:none;border-radius:10px;padding:16px;font-size:1rem;font-weight:700;cursor:pointer;letter-spacing:.3px}
.btn-calc:active{background:#a93226}
.btn-calc:disabled{background:#a0aec0;cursor:not-allowed}

/* Step 3: Success */
#step-success{display:none;padding:40px 20px;text-align:center}
#step-success .icon{font-size:3rem;margin-bottom:16px}
#step-success h2{font-size:1.3rem;font-weight:800;color:#1a2332;margin-bottom:10px}
#step-success p{font-size:.9rem;color:#6b7f96;line-height:1.6;max-width:320px;margin:0 auto 24px}
.success-note{background:#fff;border-radius:12px;padding:16px;font-size:.85rem;color:#444;line-height:1.6;box-shadow:0 1px 4px rgba(0,0,0,.06);text-align:left;max-width:360px;margin:0 auto}
.success-note strong{color:#1a4a8a}

/* Popup overlay */
.popup-overlay{position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:100;display:flex;align-items:center;justify-content:center;padding:20px}
.popup-box{background:#fff;border-radius:16px;padding:24px;max-width:340px;width:100%;box-shadow:0 8px 32px rgba(0,0,0,.2)}
.popup-box h3{font-size:1rem;font-weight:800;color:#1a2332;margin-bottom:10px}
.popup-box p{font-size:.875rem;color:#444;line-height:1.6;margin-bottom:20px}
.popup-close{display:block;width:100%;background:#1a4a8a;color:#fff;border:none;border-radius:10px;padding:13px;font-size:.95rem;font-weight:700;cursor:pointer}
</style>
</head>
<body>

<div class="header">
  <h1>QBL Alert Demo</h1>
  <p>ProductivRN</p>
</div>

<!-- Step 1: Mobile Entry -->
<div id="step-mobile">
  <h2>Experience a Live Demo</h2>
  <p>Enter your mobile number and we'll send you a real QBL alert text — just like your team would receive.</p>
  <div class="field-group">
    <label>Your Mobile Number</label>
    <input type="tel" id="mobileInput" placeholder="(555) 555-5555" maxlength="14">
  </div>
  <button class="btn-primary" id="mobileBtn" onclick="startDemo()">Start Demo</button>
  <p class="disclaimer">By entering your number you agree to receive one demo SMS. Standard messaging rates may apply.</p>
</div>

<!-- Step 2: Calculator -->
<div id="step-calc">
  <div class="demo-banner">
    <span>&#9432;</span>
    <div><strong>Demo Mode</strong> &mdash; Enter total weight, select supplies used, then tap Calculate to send a live alert to your phone.</div>
  </div>

  <div class="calc-section">
    <h3>1. Enter Total Weight (g)</h3>
    <div class="field-group" style="margin-bottom:0">
      <input type="number" id="totalWeight" value="2600" inputmode="decimal" style="font-size:1.3rem;font-weight:700;text-align:right;letter-spacing:0">
    </div>
  </div>

  <div class="calc-section" style="margin-top:12px">
    <h3>2. Select Supplies Used</h3>
    <?php
    $supplies = [
      ['img'=>'pinkpad.jpg',     'name'=>'Pink Pad',              'g'=>25,  'qty'=>0],
      ['img'=>'peripad.jpg',     'name'=>'Peri Pad',              'g'=>20,  'qty'=>0],
      ['img'=>'bluechux.jpg',    'name'=>'Blue Paper Chux',       'g'=>95,  'qty'=>1],
      ['img'=>'clothbedpad.jpg', 'name'=>'Cloth Bed Pad',         'g'=>365, 'qty'=>0],
      ['img'=>'bamsheet.jpg',    'name'=>'BAM Sheet',             'g'=>465, 'qty'=>1],
      ['img'=>'drape34.jpg',     'name'=>'3/4 Drape',             'g'=>105, 'qty'=>0],
      ['img'=>'bandage3.jpg',    'name'=>'Single Lap',            'g'=>20,  'qty'=>0],
      ['img'=>'bathtowel.jpg',   'name'=>'Bath Towel',            'g'=>695, 'qty'=>1],
      ['img'=>'bluetowel.jpg',   'name'=>'Blue Towel',            'g'=>50,  'qty'=>0],
      ['img'=>'coldpack.jpg',    'name'=>'Cold Pack',             'g'=>210, 'qty'=>0],
      ['img'=>'lapholder.jpg',   'name'=>'Lap Holder',            'g'=>20,  'qty'=>0],
      ['img'=>'ubdrape.jpg',     'name'=>'Under-Buttocks Drape',  'g'=>125, 'qty'=>0],
      ['img'=>'redbag.jpg',      'name'=>'Red Bag',               'g'=>70,  'qty'=>0],
      ['img'=>'towel.jpg',       'name'=>'Towel',                 'g'=>175, 'qty'=>0],
      ['img'=>'washcloth.jpg',   'name'=>'Wash Cloth',            'g'=>30,  'qty'=>0],
    ];
    foreach ($supplies as $s):
    ?>
    <div class="supply-row" data-g="<?php echo $s['g']; ?>" data-name="<?php echo htmlspecialchars($s['name'], ENT_QUOTES); ?>">
      <div class="supply-left">
        <img class="supply-img" src="qblimages/<?php echo $s['img']; ?>" alt="<?php echo htmlspecialchars($s['name']); ?>">
        <div><div class="supply-name"><?php echo htmlspecialchars($s['name']); ?></div><div class="supply-ml"><?php echo $s['g']; ?>g dry</div></div>
      </div>
      <div class="supply-qty">
        <button class="qty-btn" onclick="changeQty(this,-1)">−</button>
        <div class="qty-val"><?php echo $s['qty']; ?></div>
        <button class="qty-btn" onclick="changeQty(this,1)">+</button>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <button class="btn-calc" id="calcBtn" onclick="calculate()">Calculate &amp; Send Alert</button>

  <div class="result-card" id="resultCard" style="display:none">
    <div class="result-header" id="resultHeader">
      <div><div class="result-qbl" id="resultQbl">&mdash;</div><div class="result-label">Estimated QBL</div></div>
      <div class="result-badge" id="resultBadge" style="display:none">&#9888; Possible Hemorrhage</div>
    </div>
    <div class="result-ratio">
      <span class="lbl">QBL / Total Weight</span>
      <span class="val" id="resultRatio">&mdash;</span>
    </div>
  </div>
</div>

<!-- Success Popup -->
<div class="popup-overlay" id="successPopup" style="display:none">
  <div class="popup-box" style="text-align:center">
    <div style="font-size:2.5rem;margin-bottom:12px">📱</div>
    <h3>Alert Sent!</h3>
    <p style="margin-top:8px">Check your phone for a text message. Tap the link to see exactly what your team receives.</p>
    <p style="margin-top:10px;font-size:.82rem;color:#666;line-height:1.5">In a live deployment, this same alert goes simultaneously to your charge nurse, house supervisor, pharmacy, and any other configured recipients.</p>
    <button class="popup-close" style="margin-top:16px" onclick="document.getElementById('successPopup').style.display='none'">Got It</button>
  </div>
</div>

<!-- Popup -->
<div class="popup-overlay" id="demoPopup">
  <div class="popup-box">
    <h3>&#128247; This Lives on Your Scale</h3>
    <p>In your unit, nurses scan a QR code mounted directly on the scale. It opens this calculator instantly, pre-configured with your exact supplies.</p>
    <p style="margin-top:10px;font-size:.82rem;color:#666">We print and ship QR codes specific to your unit's supply list. Laminate it, stick it on the scale. Setup takes less than a day.</p>
    <button class="popup-close" onclick="document.getElementById('demoPopup').style.display='none'">Got It &mdash; Try the Demo</button>
  </div>
</div>

<script>
var demoMobile = '';

function formatPhone(val) {
  var d = val.replace(/\D/g,'');
  if (d.length <= 3) return d;
  if (d.length <= 6) return '(' + d.slice(0,3) + ') ' + d.slice(3);
  return '(' + d.slice(0,3) + ') ' + d.slice(3,6) + '-' + d.slice(6,10);
}

document.getElementById('mobileInput').addEventListener('input', function() {
  var pos = this.selectionStart;
  this.value = formatPhone(this.value);
});

function startDemo() {
  var raw = document.getElementById('mobileInput').value.replace(/\D/g,'');
  if (raw.length !== 10) { alert('Please enter a valid 10-digit mobile number.'); return; }
  demoMobile = raw;
  document.getElementById('step-mobile').style.display = 'none';
  document.getElementById('step-calc').style.display = 'block';
  document.getElementById('demoPopup').style.display = 'flex';
}

function changeQty(btn, delta) {
  var row = btn.closest('.supply-row');
  var valEl = row.querySelector('.qty-val');
  var val = Math.max(0, parseInt(valEl.textContent) + delta);
  valEl.textContent = val;
}

function calcTotal() {
  var totalWt = parseFloat(document.getElementById('totalWeight').value) || 0;
  var supplyDry = 0;
  document.querySelectorAll('.supply-row').forEach(function(row) {
    var g = parseInt(row.dataset.g);
    var qty = parseInt(row.querySelector('.qty-val').textContent);
    supplyDry += g * qty;
  });
  var qbl = Math.max(0, (totalWt - supplyDry) * 0.9465);
  var header = document.getElementById('resultHeader');
  var qblEl = document.getElementById('resultQbl');
  var badge = document.getElementById('resultBadge');
  document.getElementById('resultCard').style.display = 'block';
  qblEl.textContent = Math.round(qbl).toLocaleString() + ' ml';
  document.getElementById('resultRatio').textContent = totalWt > 0 ? Math.round((qbl / totalWt) * 100) + '%' : '\u2014';
  if (qbl >= 1000) {
    header.className = 'result-header';
    badge.textContent = '\u26A0 Possible Hemorrhage';
    badge.style.display = '';
  } else if (qbl >= 500) {
    header.className = 'result-header warning';
    badge.textContent = 'Elevated';
    badge.style.display = '';
  } else {
    header.className = 'result-header normal';
    badge.style.display = 'none';
  }
  return Math.round(qbl);
}

function calculate() {
  var qbl = calcTotal();
  var btn = document.getElementById('calcBtn');
  btn.disabled = true;
  btn.textContent = 'Sending Alert...';
  fetch('inc/data.php?req=senddemoqblsms', {
    method: 'POST',
    headers: {'Content-Type':'application/x-www-form-urlencoded'},
    body: (function(){
      var qtys = {};
      document.querySelectorAll('.supply-row').forEach(function(row){
        var qty = parseInt(row.querySelector('.qty-val').textContent);
        if (qty > 0) qtys[row.dataset.name] = qty;
      });
      return 'mobile=' + encodeURIComponent(demoMobile) + '&qbl=' + encodeURIComponent(qbl) + '&totalWeight=' + encodeURIComponent(parseFloat(document.getElementById('totalWeight').value) || 0) + '&supplies=' + encodeURIComponent(JSON.stringify(qtys));
    })()
  })
  .then(function(r){ return r.json(); })
  .then(function(data) {
    if (data.success) {
      btn.disabled = false;
      btn.textContent = 'Calculate & Send Alert';
      document.getElementById('successPopup').style.display = 'flex';
    } else {
      btn.disabled = false;
      btn.textContent = 'Calculate & Send Alert';
      alert(data.message || 'Error sending demo. Please try again.');
    }
  })
  .catch(function() {
    btn.disabled = false;
    btn.textContent = 'Calculate & Send Alert';
    alert('Network error. Please try again.');
  });
}
</script>
</body>
</html>
