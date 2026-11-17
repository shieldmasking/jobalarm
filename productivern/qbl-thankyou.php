<?php
session_start();
$qbl = isset($_GET['qbl']) ? number_format((float)$_GET['qbl'], 1) : null;
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>QBL Alerts</title>
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f0f2f5; color: #222; min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 24px; }
.card { background: #fff; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,.1); padding: 32px 24px; max-width: 480px; width: 100%; text-align: center; }
.logo { color: #1a4a8a; font-size: 1rem; font-weight: 700; letter-spacing: .5px; margin-bottom: 24px; }
h1 { font-size: 1.3rem; font-weight: 700; color: #1a4a8a; margin-bottom: 12px; }
.disclaimer { font-size: .75rem; color: #888; line-height: 1.6; margin: 24px 0; padding: 14px; background: #f8f8f8; border-radius: 8px; text-align: left; }
.disclaimer strong { color: #555; }
a.home { display: inline-block; margin-top: 8px; color: #1a4a8a; font-size: .9rem; text-decoration: none; border-bottom: 1px solid #1a4a8a; }
a.btn-edit { display: inline-block; padding: 10px 24px; background: #1a4a8a; color: #fff; border-radius: 8px; font-size: .9rem; text-decoration: none; }
a.btn-done { display: inline-block; padding: 10px 24px; background: #c0392b; color: #fff; border-radius: 8px; font-size: .9rem; text-decoration: none; }
.qbl-result { font-size: 1.4rem; color: #1a4a8a; margin-bottom: 16px; }
</style>
<script>setTimeout(function(){ sessionStorage.removeItem('qblEditId'); window.location.href='/logout.php'; }, 5*60*1000);</script>
</head>
<body>
<div class="card">
    <div class="logo">ProductiveRN</div>
    <?php if($qbl !== null): ?>
    <div class="qbl-result">Your QBL was: <strong><?php echo htmlspecialchars($qbl); ?> ml</strong></div>
    <?php endif; ?>
    <h1>Thank you for using QBL Alerts</h1>
    <div class="disclaimer">
        <strong>Medical Disclaimer:</strong> This tool should NOT be considered as a substitute for any professional medical service, nor as a substitute for clinical judgement. Results are estimates only and should always be interpreted in the context of the patient's full clinical picture. Consult qualified medical personnel for all clinical decisions.
    </div>
    <div style="display:flex;gap:12px;justify-content:center;margin-top:16px;">
        <a class="btn-edit" href="/dashboard.php#qblNow">Edit QBL</a>
        <a class="btn-done" href="/logout.php" onclick="sessionStorage.removeItem('qblEditId');">Done</a>
    </div>
    <br><br>
    <a class="home" href="https://www.qblalerts.com">qblalerts.com</a>
</div>
</body>
</html>
