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
/* Cumulative session bar */
.cum-bar{background:var(--white);border-radius:12px;border:1px solid var(--border);margin-bottom:14px;padding:12px 16px;display:none;box-shadow:0 1px 4px rgba(0,0,0,.05)}
.cum-bar-inner{display:flex;align-items:center;justify-content:space-between}
.cum-label{font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--muted)}
.cum-val{font-size:1.4rem;font-weight:900;color:var(--text)}
.cum-val.red{color:var(--red)}
.cum-val.orange{color:var(--orange)}
.cum-entries{font-size:.72rem;color:var(--muted);margin-top:2px}
/* Success screen */
.success-screen{display:none;text-align:center;padding:40px 24px}
.success-screen .icon{font-size:3rem;margin-bottom:16px}
.success-screen h2{font-size:1.3rem;font-weight:800;margin-bottom:8px}
.success-screen p{font-size:.88rem;color:var(--muted);line-height:1.6;margin-bottom:20px}
.success-qbl{font-size:2.5rem;font-weight:800;margin-bottom:4px}
.success-qbl.red{color:var(--red)}
.success-qbl.orange{color:var(--orange)}
.success-cum{font-size:.88rem;color:var(--muted);margin-bottom:20px}
.success-cum strong{color:var(--text)}
.btn-new{background:var(--navy);color:#fff;border:none;border-radius:10px;padding:13px 28px;font-size:.95rem;font-weight:700;cursor:pointer}
/* Session prompt */
.session-prompt{position:fixed;inset:0;z-index:300;background:var(--bg);display:flex;flex-direction:column;align-items:center;justify-content:center;padding:24px}
.session-card{background:var(--white);border-radius:16px;border:1px solid var(--border);box-shadow:0 4px 20px rgba(0,0,0,.1);padding:28px 24px;width:100%;max-width:400px;text-align:center}
.session-card h2{font-size:1.05rem;font-weight:800;margin-bottom:6px}
.session-card p{font-size:.85rem;color:var(--muted);line-height:1.6;margin-bottom:6px}
.session-total{font-size:2rem;font-weight:900;margin:12px 0 4px}
.session-total.red{color:var(--red)}
.session-total.orange{color:var(--orange)}
.session-meta{font-size:.75rem;color:var(--muted);margin-bottom:24px}
.btn-continue{display:block;width:100%;background:var(--navy);color:#fff;border:none;border-radius:10px;padding:14px;font-size:.95rem;font-weight:800;cursor:pointer;margin-bottom:10px}
.btn-newpt{display:block;width:100%;background:none;color:var(--muted);border:1px solid var(--border);border-radius:10px;padding:12px;font-size:.88rem;font-weight:600;cursor:pointer}
.btn-hemorrhage{display:block;width:100%;background:#dc2626;color:#fff;border:none;border-radius:10px;padding:14px;font-size:.95rem;font-weight:800;cursor:pointer;margin-bottom:10px}
.btn-hemorrhage:disabled{opacity:.6;cursor:default}
</style>
</head>
<body>
<!-- Hemorrhage alert prompt (shown on every QR scan) -->
<div class="session-prompt" id="hemorrhagePrompt" style="display:none">
  <div class="session-card">
    <div style="font-size:2rem;margin-bottom:4px">&#9888;</div>
    <h2 style="color:#dc2626;margin-bottom:8px">Hemorrhage Alert</h2>
    <p style="margin-bottom:20px"><strong>Seconds count!</strong> If you are concerned about a possible hemorrhage, send an alert to your Leadership Team now.</p>
    <button class="btn-hemorrhage" id="hemorrhageBtn" onclick="sendHemorrhageAlert()">Yes, Send Alert</button>
    <button class="btn-newpt" onclick="dismissHemorrhage()">No, Open Calculator</button>
  </div>
</div>

<!-- Session resume prompt (shown by JS if active session exists) -->
<div class="session-prompt" id="sessionPrompt" style="display:none">
  <div class="session-card">
    <h2>Active Session Found</h2>
    <p>Is this the same patient?</p>
    <div class="session-total" id="spTotal"></div>
    <div class="session-meta" id="spMeta"></div>
    <button class="btn-continue" onclick="continueSession()">Continue Same Patient</button>
    <button class="btn-newpt" onclick="resetForm()">New Patient</button>
  </div>
</div>

<header>
  <h1><?php echo $unitName; ?></h1>
  <p>QBL Calculator</p>
</header>

<div class="main" id="calcView">

  <div class="cum-bar" id="cumBar">
    <div class="cum-bar-inner">
      <div>
        <div class="cum-label">Session Cumulative QBL</div>
        <div class="cum-val" id="cumVal">0 ml</div>
        <div class="cum-entries" id="cumEntries"></div>
      </div>
      <div style="font-size:.7rem;color:var(--muted);text-align:right;max-width:90px;line-height:1.4">Same device &amp; session only</div>
    </div>
  </div>

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

  <div class="section" style="margin-bottom:14px">
    <div class="section-head">3. Your Name</div>
    <div class="weight-wrap">
      <input type="text" id="nurseName" placeholder="First and last name" autocomplete="name" style="width:100%;border:2px solid var(--border);border-radius:10px;padding:12px 14px;font-size:1rem;font-family:var(--font);outline:none;color:var(--text)" oninput="checkSubmit()">
    </div>
  </div>

  <button class="btn-submit" id="submitBtn" onclick="submitQbl();" disabled>Save</button>
  <p id="submitHint" style="display:none;font-size:.8rem;color:#c0392b;text-align:center;margin:-8px 0 8px;">Your name is required to save.</p>
  <button class="btn-reset" onclick="if(confirm('Clear all entries and start a new patient?'))resetForm()">Clear</button>
  <button class="btn-reset" style="margin-top:8px;color:#c0392b;border-color:#c0392b;" onclick="if(confirm('Cancel this record and return to login?')){window.close();setTimeout(function(){window.location.href='https://qbl.productivern.com/login.php?r=qbl';},150);}">Cancel Record</button>
  <p style="font-size:.72rem;color:var(--muted);text-align:center;line-height:1.5;margin-bottom:24px;">This tool is a clinical support aid and does not replace clinical judgment. Always follow your institution&rsquo;s hemorrhage protocols. <a href="/qbl-terms.php" style="color:var(--muted);">Terms of Use</a></p>
</div>

<div class="success-screen" id="successView">
  <div class="icon">&#9989;</div>
  <div class="success-qbl" id="successQbl"></div>
  <p>Estimated QBL</p>
  <h2>Alert Sent</h2>
  <p id="successMsg">Your charge nurse and supervisor have been notified.</p>
  <div class="success-cum" id="successCum" style="display:none">Session Cumulative: <strong id="successCumVal"></strong></div>
  <button class="btn-new" onclick="addEntry()">Add Entry</button>
  <button class="btn-reset" onclick="window.close()" style="margin-top:.6rem">Close Record</button>
</div>

<div class="toast" id="toast"></div>

<script>
var TOKEN    = <?php echo json_encode($token); ?>;
var DEPTID   = <?php echo json_encode($deptId); ?>;
var CUM_KEY  = 'qbl_cum_' + DEPTID;
var editLogId      = null;
var editOrigQbl    = null;
var editOrigCumQbl = null;

function generateId() {
  return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
    var r = Math.random() * 16 | 0, v = c === 'x' ? r : (r & 0x3 | 0x8);
    return v.toString(16);
  });
}

/* ── Session cumulative (localStorage, same device only) ── */
function getSession() {
  try { return JSON.parse(localStorage.getItem(CUM_KEY)) || null; } catch(e) { return null; }
}
function saveSession(s) {
  try { localStorage.setItem(CUM_KEY, JSON.stringify(s)); } catch(e) {}
}
function clearSession() {
  try { localStorage.removeItem(CUM_KEY); } catch(e) {}
}
function addToSession(qbl, nurseName, sessionId) {
  var s = getSession() || { entries: [], startedAt: Date.now(), sessionId: sessionId };
  if (!s.sessionId) s.sessionId = sessionId;
  s.entries.push({ qbl: qbl, ts: Date.now() });
  s.lastNurse = nurseName;
  s.lastAt = Date.now();
  saveSession(s);
  renderCumBar(s);
}
function updateLastSession(qbl, nurseName) {
  var s = getSession();
  if (!s || !s.entries.length) return;
  s.entries[s.entries.length - 1].qbl = qbl;
  s.lastNurse = nurseName;
  s.lastAt = Date.now();
  saveSession(s);
  renderCumBar(s);
}
function renderCumBar(s) {
  if (!s || !s.entries.length) { document.getElementById('cumBar').style.display = 'none'; return; }
  var total = s.entries.reduce(function(a, e) { return a + e.qbl; }, 0);
  var n = s.entries.length;
  var el = document.getElementById('cumVal');
  el.textContent = total.toLocaleString() + ' ml';
  el.className = 'cum-val' + (total >= 1000 ? ' red' : total >= 500 ? ' orange' : '');
  document.getElementById('cumEntries').textContent = n + ' entr' + (n === 1 ? 'y' : 'ies') + ' this session';
  document.getElementById('cumBar').style.display = 'block';
}

function continueSession() {
  document.getElementById('sessionPrompt').style.display = 'none';
  document.getElementById('calcView').style.display = 'block';
  renderCumBar(getSession());
}

// Init on load — edit mode skips prompts; otherwise hemorrhage first then session check
(function() {
  if (new URLSearchParams(window.location.search).get('edit') === '1') {
    var d = null;
    try { d = JSON.parse(sessionStorage.getItem('qbl_edit_pending')); } catch(e) {}
    if (d) {
      sessionStorage.removeItem('qbl_edit_pending');
      editLogId      = d.logId;
      editOrigCumQbl = d.cumQbl || 0;
      editOrigQbl    = d.origQbl || d.cumQbl || 0;
      document.getElementById('totalWeight').value = d.totalWt;
      document.querySelectorAll('.supply-row').forEach(function(row) {
        if (d['v' + row.dataset.idx] !== undefined) row.querySelector('.qty-val').textContent = d['v' + row.dataset.idx];
      });
      var ni = document.getElementById('nurseName');
      if (ni && d.nurseName) ni.value = d.nurseName;
      recalc();
    }
    document.getElementById('calcView').style.display = 'block';
    return;
  }
  document.getElementById('calcView').style.display = 'none';
  document.getElementById('hemorrhagePrompt').style.display = 'flex';
})();

function dismissHemorrhage() {
  document.getElementById('hemorrhagePrompt').style.display = 'none';
  checkSession();
}

function sendHemorrhageAlert() {
  var btn = document.getElementById('hemorrhageBtn');
  btn.disabled = true;
  btn.textContent = 'Sending\u2026';
  fetch('inc/data.php?req=qblhemorrhage', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'token=' + encodeURIComponent(TOKEN)
  })
  .then(function(r) { return r.json(); })
  .then(function(data) {
    btn.textContent = data.success ? 'Alert Sent \u2713' : 'Error \u2014 try again';
    setTimeout(dismissHemorrhage, 1500);
  })
  .catch(function() {
    btn.textContent = 'Error \u2014 try again';
    setTimeout(dismissHemorrhage, 1500);
  });
}

function checkSession() {
  var s = getSession();
  if (s && s.lastAt && (Date.now() - s.lastAt) > 2 * 60 * 60 * 1000) { clearSession(); s = null; }
  if (!s || !s.entries.length) { document.getElementById('calcView').style.display = 'block'; return; }
  var total = s.entries.reduce(function(a, e) { return a + e.qbl; }, 0);
  var n = s.entries.length;
  var el = document.getElementById('spTotal');
  el.textContent = total.toLocaleString() + ' ml';
  el.className = 'session-total' + (total >= 1000 ? ' red' : total >= 500 ? ' orange' : '');
  var lastAt = new Date(s.lastAt || s.startedAt);
  var h = lastAt.getHours(), m = lastAt.getMinutes(), ap = h >= 12 ? 'pm' : 'am';
  h = h % 12 || 12;
  var timeStr = h + ':' + (m < 10 ? '0' : '') + m + ap;
  var meta = n + ' entr' + (n === 1 ? 'y' : 'ies');
  if (s.lastNurse) meta += ' \u00b7 last entry by ' + s.lastNurse + ' at ' + timeStr;
  else meta += ' \u00b7 last entry at ' + timeStr;
  document.getElementById('spMeta').textContent = meta;
  document.getElementById('sessionPrompt').style.display = 'flex';
}

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
  checkSubmit();

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

function checkSubmit() {
  var totalWt = parseFloat(document.getElementById('totalWeight').value) || 0;
  var name = document.getElementById('nurseName').value.trim();
  var card = document.getElementById('resultCard');
  var ready = totalWt > 0 && name.length > 0 && card.style.display !== 'none';
  document.getElementById('submitBtn').disabled = !ready;
  document.getElementById('submitHint').style.display = (!ready && totalWt > 0 && card.style.display !== 'none' && name.length === 0) ? 'block' : 'none';
}

function submitQbl() {
  var totalWt = parseFloat(document.getElementById('totalWeight').value) || 0;
  var nurseName = document.getElementById('nurseName').value.trim();
  if (totalWt <= 0) { showToast('Enter scale weight first', true); return; }
  if (!nurseName) { showToast('Enter your name first', true); return; }

  var supplyDry = 0;
  document.querySelectorAll('.supply-row').forEach(function(row) {
    supplyDry += parseFloat(row.dataset.g) * parseInt(row.querySelector('.qty-val').textContent);
  });
  var totalQBL = Math.max(0, Math.round((totalWt - supplyDry) * 0.9465));

  var s = getSession();
  var sessionId = (s && s.sessionId) ? s.sessionId : generateId();
  var cumQbl;
  if (editLogId) {
    // Overwriting existing entry: replace its contribution in the cumulative
    cumQbl = (editOrigCumQbl - editOrigQbl) + totalQBL;
  } else {
    var prevCum = s ? s.entries.reduce(function(a, e) { return a + e.qbl; }, 0) : 0;
    cumQbl = prevCum + totalQBL;
  }

  var params = new URLSearchParams({ token: TOKEN, totalWt: totalWt, totalQBL: totalQBL, nurseName: nurseName, cumQbl: cumQbl, sessionId: sessionId });
  document.querySelectorAll('.supply-row').forEach(function(row) {
    params.set('v' + row.dataset.idx, row.querySelector('.qty-val').textContent);
  });

  var btn = document.getElementById('submitBtn');
  btn.disabled = true;
  btn.textContent = 'Sending…';

  var endpoint = editLogId ? 'updateqblcalc' : 'submitqblcalc';
  if (editLogId) params.set('logId', editLogId);
  fetch('inc/data.php?req=' + endpoint, {
    method: 'POST',
    headers: {'Content-Type':'application/x-www-form-urlencoded'},
    body: params.toString()
  })
  .then(function(r){ return r.json(); })
  .then(function(data) {
    if (!data.success) { showToast(data.message || 'Error — try again', true); btn.disabled=false; btn.textContent='Save'; return; }
    if (editLogId) { updateLastSession(totalQBL, nurseName); } else { addToSession(totalQBL, nurseName, sessionId); }
    var editData = { logId: data.logId, totalWt: totalWt, nurseName: nurseName, cumQbl: cumQbl, origQbl: totalQBL, sessionId: sessionId };
    document.querySelectorAll('.supply-row').forEach(function(row) { editData['v' + row.dataset.idx] = parseInt(row.querySelector('.qty-val').textContent); });
    sessionStorage.setItem('qbl_edit_pending', JSON.stringify(editData));
    window.location.href = '/qbl-thankyou2.php?qbl=' + Math.round(totalQBL) + '&alert=' + (data.alertSent ? '1' : '0') + '&t=' + encodeURIComponent(TOKEN) + '&lid=' + data.logId;
  })
  .catch(function(){ showToast('Network error — try again', true); btn.disabled=false; btn.textContent='Save'; });
}

function showSuccess(qbl, alertSent, nurseName, sessionId) {
  addToSession(qbl, nurseName, sessionId);
  document.getElementById('calcView').style.display = 'none';
  var sv = document.getElementById('successView');
  var qblEl = document.getElementById('successQbl');
  qblEl.textContent = qbl.toLocaleString() + ' ml';
  qblEl.className = 'success-qbl' + (qbl >= 1000 ? ' red' : (qbl >= 500 ? ' orange' : ''));
  document.getElementById('successMsg').textContent = alertSent
    ? 'Your charge nurse and supervisor have been notified.'
    : 'Entry recorded. No alert threshold met.';
  var s = getSession();
  if (s && s.entries.length > 1) {
    var total = s.entries.reduce(function(a, e) { return a + e.qbl; }, 0);
    document.getElementById('successCumVal').textContent = total.toLocaleString() + ' ml (' + s.entries.length + ' entries)';
    document.getElementById('successCum').style.display = 'block';
  } else {
    document.getElementById('successCum').style.display = 'none';
  }
  sv.style.display = 'block';
}

function addEntry() {
  document.getElementById('successView').style.display = 'none';
  document.getElementById('totalWeight').value = '';
  document.querySelectorAll('.qty-val').forEach(function(el){ el.textContent = '0'; });
  document.getElementById('resultCard').style.display = 'none';
  document.getElementById('submitBtn').disabled = true;
  document.getElementById('submitBtn').textContent = 'Save';
  document.getElementById('calcView').style.display = 'block';
}

function resetForm() {
  clearSession();
  document.getElementById('totalWeight').value = '';
  document.querySelectorAll('.qty-val').forEach(function(el){ el.textContent = '0'; });
  document.getElementById('resultCard').style.display = 'none';
  document.getElementById('submitBtn').disabled = true;
  document.getElementById('submitBtn').textContent = 'Save';
  document.getElementById('sessionPrompt').style.display = 'none';
  document.getElementById('calcView').style.display = 'block';
  document.getElementById('successView').style.display = 'none';
  document.getElementById('cumBar').style.display = 'none';
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
