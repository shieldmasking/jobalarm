<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">
                Profile Update
            </h3>
        </div>
    </div>
</div>
<!-- END: Subheader -->
<div class="m-content">
    <!--Begin::Main Portlet-->
    <div class="row">
        <h3>Change Password</h3>
    </div>
    <div class="row">
        <form class="login-form" action="login.php" method="post">
            <input type="hidden" name="sitelogin" value="1" />
            <input type="hidden" name="page" value="<?php echo $dataId; ?>" />
            <h3 class="form-title">Sign In</h3>
            <div class="alert alert-danger display-hide">
                <button class="close" data-close="alert"></button>
                <span>
			    Enter any username and password. </span>
            </div>
            <div class="form-group">
                <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                <label class="control-label visible-ie8 visible-ie9">Username</label>
                <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Email Address" name="username"/>
            </div>
            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">Password</label>
                <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="password"/>
            </div>
            <div class="form-group">
                <div class="g-recaptcha" data-sitekey="6LeeVVMUAAAAAB9qH5Dkdgx3xaOywOVzWR1-snNh">
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-success uppercase">Login</button>
                <!--
                <label class="rememberme check">
                <input type="checkbox" name="remember" value="1"/>Remember </label>
                -->
                <a href="javascript:;" id="forget-password" onclick="" data-toggle="modal" data-target="#forgot">Forgot Password?</a>
            </div>
        </form>
    </div>
    <div class="row">
        <div id="pswd_info">
            <h4>Password must meet the following requirements:</h4>
            <ul>
                <li id="letter" class="invalid">At least <strong>one letter</strong></li>
                <li id="capital" class="invalid">At least <strong>one capital letter</strong></li>
                <li id="number" class="invalid">At least <strong>one number</strong></li>
                <li id="symbol" class="invalid">At least <strong>one symbol</strong></li>
                <li id="length" class="invalid">Be at least <strong>8 characters</strong></li>
            </ul>
        </div>
    </div>
    <!--End::Main Portlet-->
</div>