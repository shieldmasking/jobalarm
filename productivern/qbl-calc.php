<?php
require_once 'inc/class.db.php';
require_once 'inc/config.php';

$token = isset($_GET['t']) ? preg_replace('/[^a-f0-9]/i', '', $_GET['t']) : '';

if (strlen($token) !== 64) {
    die('<p style="font-family:sans-serif;text-align:center;margin-top:60px;color:#666">Invalid link. Please scan the QR code on your scale.</p>');
}

$db = Config::get('db');
$tokenRow = $db->get_results("SELECT * FROM productiveQrTokens WHERE token='" . $db->filter($token) . "' AND page='qbl' LIMIT 1");
if (!$tokenRow) {
    die('<p style="font-family:sans-serif;text-align:center;margin-top:60px;color:#666">This QR code is no longer active. Please contact your unit manager.</p>');
}

$deptId = intval($tokenRow[0]['deptId']);
$row = $db->get_results("
    SELECT d.dept, c.id as configId,
           c.desc1,c.desc2,c.desc3,c.desc4,c.desc5,c.desc6,c.desc7,c.desc8,c.desc9,c.desc10,
           c.desc11,c.desc12,c.desc13,c.desc14,c.desc15,c.desc16,c.desc17,c.desc18,c.desc19,c.desc20,
           c.value1,c.value2,c.value3,c.value4,c.value5,c.value6,c.value7,c.value8,c.value9,c.value10,
           c.value11,c.value12,c.value13,c.value14,c.value15,c.value16,c.value17,c.value18,c.value19,c.value20,
           c.image1,c.image2,c.image3,c.image4,c.image5,c.image6,c.image7,c.image8,c.image9,c.image10,
           c.image11,c.image12,c.image13,c.image14,c.image15,c.image16,c.image17,c.image18,c.image19,c.image20
    FROM ProductiveDept d
    JOIN productiveQBLconfig c ON c.deptId = d.id
    WHERE d.id = {$deptId} AND d.active = 1
    LIMIT 1
");
if (!$row) {
    die('<p style="font-family:sans-serif;text-align:center;margin-top:60px;color:#666">Unit not found. Please contact your manager.</p>');
}
$cfg      = $row[0];
$unitName = htmlspecialchars($cfg['dept']);

// Build supplies array (skip empty entries)
$supplies = [];
for ($i = 1; $i <= 20; $i++) {
    $desc = trim($cfg["desc{$i}"] ?? '');
    $val  = floatval($cfg["value{$i}"] ?? 0);
    $img  = $cfg["image{$i}"] ?? '';
    if ($desc !== '' && $val > 0) {
        $supplies[] = ['idx'=>$i, 'name'=>htmlspecialchars($desc), 'g'=>$val, 'img'=>htmlspecialchars($img)];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<title>QBL Calculator — <?php echo $unitName; ?></title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
:root{--navy:#1a4a8a;--navy-dark:#163d73;--teal:#0e6b6b;--red:#c0392b;--orange:#d97706;--bg:#f0f2f5;--white:#fff;--text:#1a2332;--muted:#6b7f96;--border:#dde1e8;--font:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif}
body{font-family:var(--font);background:var(--bg);color:var(--text);min-height:100vh;-webkit-font-smoothing:antialiased}
header{background:var(--navy);padding:14px 16px;text-align:center}
header h1{color:#fff;font-size:.95rem;font-weight:800}
header p{color:rgba(255,255,255,.7);font-size:.75rem;margin-top:2px}
.main{padding:16px;max-width:520px;margin:0 auto}
.section{background:var(--white);border-radius:12px;border:1px solid var(--border);margin-bottom:14px;overflow:hidden;box-shadow:0 1px 4px rgba(0,0,0,.05)}
.section-head{background:#f7f8fa;padding:11px 14px;border-bottom:1px solid var(--border);font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--muted)}
.weight-wrap{padding:14px}
.weight-label{font-size:.75rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px}
.weight-input{display:flex;align-items:center;gap:10px}
.weight-input input{flex:1;border:2px solid var(--border);border-radius:10px;padding:12px 14px;font-size:1.5rem;font-weight:700;text-align:right;outline:none;color:var(--text);font-family:var(--font)}
.weight-input input:focus{border-color:var(--navy)}
.weight-unit{font-size:1rem;font-weight:600;color:var(--muted)}
.supply-row{display:flex;align-items:center;justify-content:space-between;padding:10px 14px;border-bottom:1px solid var(--border)}
.supply-row:last-child{border-bottom:none}
.supply-left{display:flex;align-items:center;gap:10px;flex:1;min-width:0}
.supply-img{width:40px;height:40px;object-fit:contain;border-radius:6px;background:#f7f8fa;flex-shrink:0}
.supply-img-placeholder{width:40px;height:40px;border-radius:6px;background:#e8f0fb;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0}
.supply-name{font-size:.88rem;font-weight:600;color:var(--text)}
.supply-g{font-size:.75rem;color:var(--muted);margin-top:1px}
.supply-qty{display:flex;align-items:center;gap:0;flex-shrink:0}
.qty-btn{width:34px;height:34px;border:1px solid var(--border);background:var(--white);font-size:1.1rem;font-weight:700;cursor:pointer;color:var(--navy);border-radius:8px;display:flex;align-items:center;justify-content:center;transition:background .1s;-webkit-tap-highlight-color:transparent}
.qty-btn:active{background:#e8f0fb}
.qty-val{min-width:32px;text-align:center;font-size:1rem;font-weight:700;color:var(--text)}
.result-card{display:none;background:var(--white);border-radius:12px;border:1px solid var(--border);margin-bottom:14px;overflow:hidden;box-shadow:0 1px 4px rgba(0,0,0,.05)}
.result-head{padding:16px;display:flex;align-items:center;justify-content:space-between;background:#f7f8fa;border-bottom:1px solid var(--border)}
.result-qbl{font-size:2rem;font-weight:800;color:var(--text)}
.result-label{font-size:.75rem;color:var(--muted);margin-top:2px;font-weight:600;text-transform:uppercase;letter-spacing:.4px}
.result-badge{display:none;font-size:.78rem;font-weight:700;padding:6px 12px;border-radius:20px}
.badge-alert{background:#fff0f0;color:var(--red);border:1px solid #f5c6c6}
.badge-warn{background:#fef9e7;color:var(--orange);border:1px solid #f0d97a}
.result-row{padding:12px 16px;display:flex;justify-content:space-between;align-items:center;font-size:.85rem;border-bottom:1px solid var(--border)}
.result-row:last-child{border-bottom:none}
.result-row .lbl{color:var(--muted);font-weight:600}
.result-row .val{font-weight:700;color:var(--text)}
.val-red{color:var(--red)!important}
.btn-submit{display:block;width:100%;background:var(--navy);color:#fff;border:none;border-radius:12px;padding:16px;font-size:1rem;font-weight:800;cursor:pointer;margin-bottom:14px;-webkit-tap-highlight-color:transparent}
.btn-submit:hover{background:var(--navy-dark)}
.btn-submit:disabled{background:#a0aec0;cursor:not-allowed}
.btn-reset{display:block;width:100%;background:none;color:var(--muted);border:1px solid var(--border);border-radius:12px;padding:12px;font-size:.88rem;font-weight:600;cursor:pointer;margin-bottom:24px}
.toast{position:fixed;bottom:24px;left:50%;transform:translateX(-50%);background:var(--teal);color:#fff;padding:12px 22px;border-radius:10px;font-size:.88rem;font-weight:700;box-shadow:0 4px 16px rgba(0,0,0,.2);z-index:999;display:none;white-space:nowrap}
.toast.error{background:var(--red)}
/* Success screen */
.success-screen{display:none;text-align:center;padding:40px 24px}
.success-screen .icon{font-size:3rem;margin-bottom:16px}
.success-screen h2{font-size:1.3rem;font-weight:800;margin-bottom:8px}
.success-screen p{font-size:.88rem;color:var(--muted);line-height:1.6;margin-bottom:20px}
.success-qbl{font-size:2.5rem;font-weight:800;margin-bottom:4px}
.success-qbl.red{color:var(--red)}
.success-qbl.orange{color:var(--orange)}
.btn-new{background:var(--navy);color:#fff;border:none;border-radius:10px;padding:13px 28px;font-size:.95rem;font-weight:700;cursor:pointer}
</style>
</head>
<body>
<header>
  <h1><?php echo $unitName; ?></h1>
  <p>QBL Calculator</p>
</header>

<div class="main" id="calcView">

  <div class="section">
    <div class="section-head">1. Enter Scale Weight</div>
    <div class="weight-wrap">
      <div class="weight-label">Total weight on scale (grams)</div>
      <div class="weight-input">
        <input type="number" id="totalWeight" inputmode="decimal" placeholder="0" min="0" max="99999" onchange="recalc()" oninput="recalc()">
        <span class="weight-unit">g</span>
      </div>
    </div>
  </div>

  <div class="section">
    <div class="section-head">2. Select Supplies Used</div>
    <?php foreach ($supplies as $s): ?>
    <div class="supply-row" data-g="<?php echo $s['g']; ?>" data-idx="<?php echo $s['idx']; ?>">
      <div class="supply-left">
        <?php if ($s['img']): ?>
          <img class="supply-img" src="qblimages/<?php echo $s['img']; ?>" alt="<?php echo $s['name']; ?>" loading="lazy">
        <?php else: ?>
          <div class="supply-img-placeholder">&#129531;</div>
        <?php endif; ?>
        <div>
          <div class="supply-name"><?php echo $s['name']; ?></div>
          <div class="supply-g"><?php echo $s['g']; ?>g dry</div>
        </div>
      </div>
      <div class="supply-qty">
        <button class="qty-btn" onclick="changeQty(this,-1)" aria-label="decrease">&#8722;</button>
        <div class="qty-val">0</div>
        <button class="qty-btn" onclick="changeQty(this,1)" aria-label="increase">&#43;</button>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="result-card" id="resultCard">
    <div class="result-head">
      <div>
        <div class="result-qbl" id="resultQbl">&mdash;</div>
        <div class="result-label">Estimated QBL</div>
      </div>
      <div class="result-badge" id="resultBadge"></div>
    </div>
    <div class="result-row">
      <span class="lbl">Scale Weight</span>
      <span class="val" id="resultWt">&mdash;</span>
    </div>
    <div class="result-row">
      <span class="lbl">QBL / Total Weight</span>
      <span class="val" id="resultRatio">&mdash;</span>
    </div>
  </div>

  <button class="btn-submit" id="submitBtn" onclick="submitQbl()" disabled>Calculate &amp; Send Alert</button>
  <button class="btn-reset" onclick="resetForm()">Clear / New Patient</button>
  <p style="font-size:.72rem;color:var(--muted);text-align:center;line-height:1.5;margin-bottom:24px;">This tool is a clinical support aid and does not replace clinical judgment. Always follow your institution&rsquo;s hemorrhage protocols. <a href="/qbl-terms.php" style="color:var(--muted);">Terms of Use</a></p>
</div>

<div class="success-screen" id="successView">
  <div class="icon">&#9989;</div>
  <div class="success-qbl" id="successQbl"></div>
  <p>Estimated QBL</p>
  <h2>Alert Sent</h2>
  <p id="successMsg">Your charge nurse and supervisor have been notified.</p>
  <button class="btn-new" onclick="resetForm()">New Patient</button>
</div>

<div class="toast" id="toast"></div>

<script>
var TOKEN = <?php echo json_encode($token); ?>;

function changeQty(btn, delta) {
  var row = btn.closest('.supply-row');
  var el  = row.querySelector('.qty-val');
  el.textContent = Math.max(0, parseInt(el.textContent) + delta);
  recalc();
}

function recalc() {
  var totalWt = parseFloat(document.getElementById('totalWeight').value) || 0;
  var supplyDry = 0;
  document.querySelectorAll('.supply-row').forEach(function(row) {
    supplyDry += parseFloat(row.dataset.g) * parseInt(row.querySelector('.qty-val').textContent);
  });
  var qbl = Math.max(0, (totalWt - supplyDry) * 0.9465);
  var card = document.getElementById('resultCard');
  var submitBtn = document.getElementById('submitBtn');

  if (totalWt <= 0) { card.style.display='none'; submitBtn.disabled=true; return; }

  card.style.display = 'block';
  submitBtn.disabled = false;

  var qblRound = Math.round(qbl);
  var ratio    = totalWt > 0 ? Math.round(qbl / totalWt * 100) : 0;
  var badge    = document.getElementById('resultBadge');
  var qblEl    = document.getElementById('resultQbl');
  var ratioEl  = document.getElementById('resultRatio');

  qblEl.textContent = qblRound.toLocaleString() + ' ml';
  ratioEl.textContent = ratio + '%';
  document.getElementById('resultWt').textContent = totalWt.toLocaleString() + ' g';

  if (ratio >= 75) ratioEl.className = 'val val-red';
  else ratioEl.className = 'val';

  if (qblRound >= 1000) {
    badge.textContent = '⚠ Possible Hemorrhage';
    badge.className = 'result-badge badge-alert';
    badge.style.display = '';
    qblEl.style.color = 'var(--red)';
  } else if (qblRound >= 500) {
    badge.textContent = 'Elevated';
    badge.className = 'result-badge badge-warn';
    badge.style.display = '';
    qblEl.style.color = 'var(--orange)';
  } else {
    badge.style.display = 'none';
    qblEl.style.color = 'var(--text)';
  }
}

function submitQbl() {
  var totalWt = parseFloat(document.getElementById('totalWeight').value) || 0;
  if (totalWt <= 0) { showToast('Enter scale weight first', true); return; }

  var supplyDry = 0;
  document.querySelectorAll('.supply-row').forEach(function(row) {
    supplyDry += parseFloat(row.dataset.g) * parseInt(row.querySelector('.qty-val').textContent);
  });
  var totalQBL = Math.max(0, Math.round((totalWt - supplyDry) * 0.9465));

  var params = new URLSearchParams({ token: TOKEN, totalWt: totalWt, totalQBL: totalQBL });
  document.querySelectorAll('.supply-row').forEach(function(row) {
    params.set('v' + row.dataset.idx, row.querySelector('.qty-val').textContent);
  });

  var btn = document.getElementById('submitBtn');
  btn.disabled = true;
  btn.textContent = 'Sending…';

  fetch('inc/data.php?req=submitqblcalc', {
    method: 'POST',
    headers: {'Content-Type':'application/x-www-form-urlencoded'},
    body: params.toString()
  })
  .then(function(r){ return r.json(); })
  .then(function(data) {
    if (!data.success) { showToast(data.message || 'Error — try again', true); btn.disabled=false; btn.textContent='Calculate & Send Alert'; return; }
    showSuccess(totalQBL, data.alertSent);
  })
  .catch(function(){ showToast('Network error — try again', true); btn.disabled=false; btn.textContent='Calculate & Send Alert'; });
}

function showSuccess(qbl, alertSent) {
  document.getElementById('calcView').style.display = 'none';
  var sv = document.getElementById('successView');
  var qblEl = document.getElementById('successQbl');
  qblEl.textContent = qbl.toLocaleString() + ' ml';
  qblEl.className = 'success-qbl' + (qbl >= 1000 ? ' red' : (qbl >= 500 ? ' orange' : ''));
  document.getElementById('successMsg').textContent = alertSent
    ? 'Your charge nurse and supervisor have been notified.'
    : 'Entry recorded. No alert threshold met.';
  sv.style.display = 'block';
}

function resetForm() {
  document.getElementById('totalWeight').value = '';
  document.querySelectorAll('.qty-val').forEach(function(el){ el.textContent = '0'; });
  document.getElementById('resultCard').style.display = 'none';
  document.getElementById('submitBtn').disabled = true;
  document.getElementById('submitBtn').textContent = 'Calculate & Send Alert';
  document.getElementById('calcView').style.display = 'block';
  document.getElementById('successView').style.display = 'none';
}

function showToast(msg, isError) {
  var t = document.getElementById('toast');
  t.textContent = msg;
  t.className = isError ? 'toast error' : 'toast';
  t.style.display = 'block';
  clearTimeout(t._tid);
  t._tid = setTimeout(function(){ t.style.display='none'; }, 3500);
}
</script>
</body>
</html>
