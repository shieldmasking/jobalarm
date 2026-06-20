<?php
require_once('views/shared/header.php');
?>
<div id="form" class="login_box">
	<div class="w2ui-page page-0">
		<div class="w2ui-field">
            <label>Username:</label>
		    <div><input name="username" type="text" maxlength="60" /></div>
		</div>
        <div class="w2ui-field">
            <label>Password:</label>
		    <div><input id="password" name="password" type="password" maxlength="30" /></div>
		</div>
        <div class="w2ui-field">
    
            <center><span style="font-size:10px"><a href="forgot">forgot password?</a></span></center>
    
        </div>
	</div>

	<div class="w2ui-buttons">
		<button class="btn" name="login">Login</button>
	</div>

</div>
<?php
require_once('views/shared/footer.php');
?>