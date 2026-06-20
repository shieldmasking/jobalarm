<?php
session_start();

//var_dump($_SESSION);

include 'inc/class.db.php';
include 'inc/class.jatwitter.php';
include "inc/config.php";
use Abraham\TwitterOAuth\TwitterOAuth;

if (isset($_REQUEST['signup']) && $_REQUEST['signup'] == 1) {
    

    $connection = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, $_POST['oa'],$_POST['oas']);
    $account_data = (array) $connection->get("account/verify_credentials", array("include_entities" => false, "skip_status" => true));
    //var_dump($account_data);
    //var_dump($_POST);
    $data = array(
        'fullName' => $account_data['name'],
        'email' => $_POST['email_address'],
        'twitter_handle' => $_POST['twitter_handle'],
        'password' => md5($_POST['password']),
        'twitter_key' => $_POST['oa'],
        'twitter_passcode' => $_POST['oas'],
        'signup_date' => date('Y-m-d H:i:s'),
        'website' => $account_data['url']                
        );
    Config::get('db')->insert('account',$data);
    $lastId = Config::get('db')->lastid();
    if ($lastId > 0) {
        $location = 'login.php?sc=1';
    } else {
        $location = 'login.php?se=1';
    }
    header('location: '.$location);
    exit();

}

$oauth_token = isset($_GET['oauth_token']) ? $_GET['oauth_token'] : '';
$oauth_verifier = isset($_GET['oauth_verifier']) ? $_GET['oauth_verifier'] : '';

if ($oauth_token == '' || $oauth_verifier == '') {
    
    if (isset($_GET['denied'])) {
        header('location: login.php');
    }
    
	die('Error with Twitter Login, Please report to admin@jobalarm.com');

}
//var_dump($oauth_verifier);

$connection = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, $oauth_token,$oauth_verifier);

try {
    if ($connection) {
        $access_token = $connection->oauth("oauth/access_token", array("oauth_verifier" => $oauth_verifier));
    }
} catch(Exception $e) {
    echo "Invalid login token, please try signing up through twitter again.<br />";
    echo '<script>setTimeout(function(){ window.location = "index.php"; }, 3000);</script>';
    exit();
}
if (!isset($access_token['screen_name'])) {

	header('location: index.php');

} else {
	$_SESSION['oauth_user'] = $access_token;

    $query = "select id from account where twitter_handle='{$access_token['screen_name']}'";
    $dbData = Config::get('db')->get_results($query);
    if (count($dbData) > 0){
        //account already in system, redirect to login page
        header('location: login.php?ae=1');
    } 	
}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8" />
    <title>JobAlarm | Job Tweet Search</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link href="theme/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="theme/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
    <link href="theme/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="theme/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGIN STYLES -->
    <!-- END PAGE LEVEL PLUGIN STYLES -->
    <!-- BEGIN PAGE STYLES -->
    <link href="theme/assets/admin/pages/css/tasks.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="theme/assets/global/plugins/select2/select2.css"/>
    <!-- END PAGE STYLES -->
    <!-- BEGIN THEME STYLES -->
    <link href="theme/assets/global/css/components-rounded.css" id="style_components" rel="stylesheet" type="text/css" />
    <link href="theme/assets/global/css/plugins.css" rel="stylesheet" type="text/css" />
    <link href="theme/assets/admin/layout3/css/layout.css" rel="stylesheet" type="text/css" />
    <link href="theme/assets/admin/layout3/css/themes/blue-steel.css" rel="stylesheet" type="text/css" id="style_color" />
    <link href="theme/assets/admin/layout3/css/custom.css" rel="stylesheet" type="text/css" />
    <!-- END THEME STYLES -->
    <link rel="shortcut icon" href="favicon.ico" />
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<!-- DOC: Apply "page-header-menu-fixed" class to set the mega menu fixed  -->
<!-- DOC: Apply "page-header-top-fixed" class to set the top menu fixed  -->
<body class="page-boxed page-header-fixed page-container-bg-solid page-sidebar-closed-hide-logo page-sidebar-closed">
    <!-- BEGIN HEADER -->
    <div class="page-header">
        <!-- BEGIN HEADER TOP -->
        <div class="page-header-top">
            <div class="container">
                <!-- BEGIN LOGO -->
                <div class="page-logo">
                    <a href="index.php">
                        <img src="img/logo1.png" longdesc="http://www.jobalarm.com">
                    </a>
                </div>
                <!-- END LOGO -->

            </div>
        </div>
        <!-- END HEADER TOP -->
        <!-- BEGIN HEADER MENU -->
        <div class="page-header-menu">
            <div class="container">
                <h1 style="width:340px;float:left;margin:0;padding:5px;color:white">User Signup</h1>               
            </div>
        </div>
        <!-- END HEADER MENU -->
    </div>
    <!-- END HEADER -->
    <!-- BEGIN PAGE CONTAINER -->
    <div class="page-container">

        <!-- BEGIN PAGE CONTENT -->
        <div class="page-content">
            <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light bordered">
						<div class="portlet-body form">
							<!-- BEGIN FORM-->
							<form class="form-horizontal register-form" method="POST" action="twitterlogin.php">
                                <input type="hidden" name="signup" value="1" />
                                <input type="hidden" name="oa" value="<?php echo $access_token['oauth_token']; ?>" />
                                <input type="hidden" name="oas" value="<?php echo $access_token['oauth_token_secret']; ?>" />
								<div class="form-body">
									<div class="form-group">
										<label class="col-md-4 control-label">Twitter Handle</label>
										<div class="col-md-4">
											<input name="twitter_handle" type="text" placeholder="" readonly="true" value="<?php echo $access_token['screen_name']; ?> " class="form-control" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-4 control-label">Company Name</label>
										<div class="col-md-4">
											<input name="company_name" type="text" placeholder="" class="form-control" />
										</div>
                                        <span id="error_company_name"></span>
									</div>
									<div class="form-group">
										<label class="col-md-4 control-label">Email Address / Username</label>
										<div class="col-md-4">
											<div class="input-group">
												<input name="email_address" type="email" placeholder="" class="form-control" />
												<span class="input-group-addon">
												<i class="fa fa-envelope"></i>
												</span>
											</div>
                                            <span id="error_email_address"></span>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-4 control-label">Password</label>
										<div class="col-md-4">
											<div class="input-group">
												<input id="password" name="password" type="password" placeholder="" class="form-control" />
												<span class="input-group-addon">
												<i class="fa fa-user"></i>
												</span>
											</div>
                                            <span id="error_password"></span>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-4 control-label">Confirm Password</label>
										<div class="col-md-4">
											<div class="input-group">
												<input id="password_confirm" name="password_confirm" type="password" placeholder="" class="form-control" />
												<span class="input-group-addon">
												<i class="fa fa-user"></i>
												</span>
											</div>
                                            <span id="error_password_confirm"></span>
										</div>
									</div>
                                    <div class="form-group margin-top-20 margin-bottom-20">
                                        <label class="col-md-4 control-label"></label>
										<div class="col-md-4">
			                                <label class="check">
			                                <input type="checkbox" name="tnc"/>
			                                <span class="loginblue-font">I agree to the </span>
			                                <a target="_blank" href="paymentterms.html" class="loginblue-link">Terms of Service</a>
			                                <span class="loginblue-font">and</span>
			                                <a target="_blank" href="privacy.html" class="loginblue-link">Privacy Policy </a>
			                                </label>
			                                <div id="register_tnc_error">
			                                </div>
                                        </div>
		                            </div>
								</div>
								<div class="form-actions">
									<div class="row">
										<div class="col-md-offset-5 col-md-7">
											<button class="btn green" type="submit">Register</button>
										</div>
									</div>
								</div>
							</form>
							<!-- END FORM-->
						</div>
					</div>
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT -->
    </div>
    <!-- END PAGE CONTAINER -->

    <!-- BEGIN FOOTER -->
    <div class="page-footer">
        <div class="container">
            2015 &copy; Premier SSG, Inc. All Rights Reserved.
        </div>
    </div>
    <div class="scroll-to-top">
        <i class="icon-arrow-up"></i>
    </div>
    <!-- END FOOTER -->
    <!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
    <!-- BEGIN CORE PLUGINS -->
    <!--[if lt IE 9]>
<script src="theme/assets/global/plugins/respond.min.js"></script>
<script src="theme/assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
    <script src="theme/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
    <script src="theme/assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
    <!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
    <script src="theme/assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
    <script src="theme/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="theme/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
    <script src="theme/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <script src="theme/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
    <script src="theme/assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
    <script src="theme/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
    <!-- END CORE PLUGINS -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="theme/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
    <!-- IMPORTANT! fullcalendar depends on jquery-ui.min.js for drag & drop support -->
    <script type="text/javascript" src="theme/assets/global/plugins/select2/select2.min.js"></script>
    <script type="text/javascript" src="theme/assets/global/plugins/bootstrap-markdown/js/bootstrap-markdown.js"></script>
    <script type="text/javascript" src="theme/assets/global/plugins/bootstrap-markdown/lib/markdown.js"></script>   
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="theme/assets/global/scripts/metronic.js" type="text/javascript"></script>
    <script src="theme/assets/admin/layout3/scripts/layout.js" type="text/javascript"></script>
    <script src="theme/assets/admin/layout3/scripts/demo.js" type="text/javascript"></script>
    <script src="theme/assets/admin/pages/scripts/login.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL SCRIPTS -->
    <script>
jQuery(document).ready(function() {
   Metronic.init(); // init metronic core componets
   Layout.init(); // init layout
   Login.init();
});
    </script>
    <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-59491934-1', 'auto');
  ga('send', 'pageview');

    </script>

    <!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>