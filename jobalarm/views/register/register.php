<?php
require_once('views/shared/header.php');
?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<style type="text/css">
<!--
.style1 {color: #000000}
-->
</style>

<div style="text-align:center;margin-top:20px">

    <h1>Welcome to JobAlarm!</h1>
    <h2 class="style1">If you are a Job Seeker wanting  to register, please go <a href="http://m.jobalarm.com/s/skills">here</a>.  </h2>
    <h2>Employers and Recruiters, please fill out this form and<br /> a Representative will be in contact with you asap. </h2>

</div>
<div id="form" class="register_box">
	<div class="w2ui-page page-0">
		<div class="w2ui-field">
            <label>Company:</label>
		    <div><input name="company" type="text" maxlength="30" /></div>
		</div>
		<div class="w2ui-field">
            <label>First Name:</label>
		    <div><input name="firstname" type="text" maxlength="30" /></div>
		</div>
		<div class="w2ui-field">
            <label>Last Name:</label>
		    <div><input name="lastname" type="text" maxlength="30" /></div>
		</div>
		<div class="w2ui-field">
            <label>Phone Number:</label>
		    <div><input name="phone" type="text" maxlength="30" /></div>
		</div>
		<div class="w2ui-field">
            <label>Login / Email:</label>
		    <div><input name="email" type="text" maxlength="60" /></div>
		</div>
        <div class="w2ui-field">
            <label>Password:</label>
		    <div><input id="password" name="password" type="password" maxlength="30" /></div>
		</div>
        <div class="w2ui-field">
            <label>Confirm Password:</label>
		    <div><input id="password_cfm" name="password_cfm" type="password" maxlength="30" /></div>
		</div>
<!--        <div class="w2ui-field">
            <center>
            <div class="g-recaptcha" data-sitekey="<?php echo CAPTCHA_URL; ?>"></div>
              </center>
        </div>-->
	</div>
	<div class="w2ui-buttons">
		<button class="btn" name="register">Register</button>
	</div>

</div>
<?php
require_once('views/shared/footer.php');
?>