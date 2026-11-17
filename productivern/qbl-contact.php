<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Contact Us — ProductivRN QBL</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;background:#f0f2f5;color:#1a2332;min-height:100vh}
.header{background:#1a4a8a;color:#fff;padding:16px;text-align:center}
.header h1{font-size:1rem;font-weight:700}
.header p{font-size:.78rem;opacity:.8;margin-top:3px}
.card{margin:24px 20px;background:#fff;border-radius:14px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,.07)}
.card-header{background:#1a4a8a;color:#fff;padding:20px}
.card-header h2{font-size:1.1rem;font-weight:800}
.card-header p{font-size:.82rem;opacity:.82;margin-top:5px;line-height:1.5}
.card-body{padding:20px}
.form-field{margin-bottom:16px}
.form-field label{display:block;font-size:.72rem;font-weight:700;color:#6b7f96;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px}
.form-field input,.form-field textarea,.form-field select{width:100%;border:1px solid #dde1e8;border-radius:8px;padding:11px 12px;font-size:.9rem;outline:none;font-family:inherit;color:#1a2332}
.form-field input:focus,.form-field textarea:focus,.form-field select:focus{border-color:#1a4a8a}
.form-field textarea{resize:vertical;min-height:100px}
.submit-btn{display:block;width:100%;background:#1a4a8a;color:#fff;border:none;border-radius:8px;padding:14px;font-size:1rem;font-weight:700;cursor:pointer}
.submit-btn:active{background:#163d73}
.submit-btn:disabled{background:#a0aec0;cursor:not-allowed}
.success{display:none;text-align:center;padding:30px 20px}
.success .icon{font-size:2.5rem;margin-bottom:14px}
.success h2{font-size:1.2rem;font-weight:800;color:#1a2332;margin-bottom:8px}
.success p{font-size:.88rem;color:#6b7f96;line-height:1.6}
.back-link{display:block;text-align:center;margin:16px 0 32px;color:#1a4a8a;font-size:.85rem;text-decoration:none}
</style>
</head>
<body>

<div class="header">
  <h1>QBL Alerts — ProductivRN</h1>
  <p>Contact Us</p>
</div>

<div class="card" id="formCard">
  <div class="card-header">
    <h2>Get QBL Alerts for Your Unit</h2>
    <p>Setup takes less than a day. No IT involvement required. We'll reach out within one business day.</p>
  </div>
  <div class="card-body">
    <div class="form-field"><label>Name *</label><input type="text" id="cf-name" placeholder="Jane Smith"></div>
    <div class="form-field"><label>Hospital / Organization *</label><input type="text" id="cf-hospital" placeholder="St. Mary's Medical Center"></div>
    <div class="form-field"><label>Your Role</label>
      <select id="cf-role">
        <option value="">Select a role...</option>
        <option>Charge Nurse</option>
        <option>Nurse Manager</option>
        <option>Director of Nursing</option>
        <option>CNO</option>
        <option>OB Anesthesia</option>
        <option>Other</option>
      </select>
    </div>
    <div class="form-field"><label>Email *</label><input type="email" id="cf-email" placeholder="jane@hospital.org"></div>
    <div class="form-field"><label>Phone</label><input type="tel" id="cf-phone" placeholder="(555) 555-5555"></div>
    <div class="form-field"><label>Message</label><textarea id="cf-message" placeholder="Tell us about your unit or any questions..."></textarea></div>
    <button class="submit-btn" id="cf-submit" onclick="submitContact()">Send Message</button>
  </div>
</div>

<div class="success" id="successMsg">
  <div class="icon">&#10003;</div>
  <h2>Message Received!</h2>
  <p>Thanks for reaching out. We'll be in touch within one business day.</p>
</div>

<a class="back-link" href="/qbl-landing.php">&#8592; Back to QBL Alerts</a>
<p style="text-align:center;font-size:.78rem;color:#6b7f96;margin-bottom:32px;">Already ready to go live? <a href="/qbl-baa.php" style="color:#1a4a8a;font-weight:600;">Download &amp; sign the BAA</a></p>

<script>
function submitContact() {
  var name     = document.getElementById('cf-name').value.trim();
  var hospital = document.getElementById('cf-hospital').value.trim();
  var email    = document.getElementById('cf-email').value.trim();
  var role     = document.getElementById('cf-role').value;
  var phone    = document.getElementById('cf-phone').value;
  var message  = document.getElementById('cf-message').value;
  if (!name || !email) { alert('Please enter your name and email.'); return; }
  var btn = document.getElementById('cf-submit');
  btn.disabled = true; btn.textContent = 'Sending...';
  fetch('inc/data.php?req=submitdemolead', {
    method: 'POST',
    headers: {'Content-Type':'application/x-www-form-urlencoded'},
    body: 'name=' + encodeURIComponent(name)
      + '&hospital=' + encodeURIComponent(hospital)
      + '&email=' + encodeURIComponent(email)
      + '&phone=' + encodeURIComponent(phone)
      + '&message=' + encodeURIComponent((role ? role + ': ' : '') + message)
  })
  .then(function(r){ return r.json(); })
  .then(function(data){
    if (data.success) {
      document.getElementById('formCard').style.display = 'none';
      document.getElementById('successMsg').style.display = 'block';
    } else {
      btn.disabled = false; btn.textContent = 'Send Message';
      alert(data.message || 'Error submitting. Please try again.');
    }
  })
  .catch(function(){
    btn.disabled = false; btn.textContent = 'Send Message';
    alert('Network error. Please try again.');
  });
}
</script>
</body>
</html>
