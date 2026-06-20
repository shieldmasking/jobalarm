<?php
require_once('views/shared/header.php');
?>
<div id="form" class="login_box">
	<div class="w2ui-page page-0">
		<div><h3>Enter your email address to have password emailed.</h3></div>
		<div class="w2ui-label">Email Address:</div>
		<div class="w2ui-field">
			<input name="emailaddy" id="emailaddy" type="text" maxlength="40" />
		</div>

	</div>

	<div class="w2ui-buttons">
		<input type="button" value="Send Password" id="resetpw" name="resetpw">
	</div>

</div>
<?php
require_once('views/shared/footer.php');