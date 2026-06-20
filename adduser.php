<?php
session_start();
ini_set('display_errors',1);
include_once 'inc/class.db.php';
include_once 'inc/class.jatwitter.php';
include_once 'inc/config.php';

if (!isset($_SESSION['account'])) {
    header('location: login.php');
}

$account_data = $_SESSION['account'];
if (!isset($account_data['accountId'])) {
	header('location: login.php');
	exit();
}

if (isset($_REQUEST['adduser'])) {
    $accountId = $account_data['accountId'];
    $role = isset($_REQUEST['role']) ? $_REQUEST['role'] : '';
    $first = isset($_REQUEST['first_name']) ? $_REQUEST['first_name'] : '';
	$last = isset($_REQUEST['last_name']) ? $_REQUEST['last_name'] : '';
	$email= isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
	
	$id="";
	$temp = randomPassword();

	
	
	if ($email) {

	Config::get('db')->query("insert into users (role,accountId,first_name,last_name,email,password,temp) values({$role},{$accountId},'{$first}','{$last}','{$email}',md5('{$temp}'),md5('{$temp}')) on duplicate key update role={$role}");

	
	//if ( isset($_POST['email']) && isset($_POST['first_name']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ) {
	if ( isset($_POST['email']) && isset($_POST['first_name'])) {

 
	  // detect & prevent header injections
	  $test = "/(content-type|bcc:|cc:|to:)/i";
	  foreach ( $_POST as $key => $val ) {
	    if ( preg_match( $test, $val ) ) {
		   echo json_encode(array('success'=>false));
	      exit;
	    }
	  }
  		$company = (isset($_POST["company"])  && strlen($_POST['company']) > 0) ?  ", ".$_POST['company'] : '';
  	//send email
	  //mail( "rstrenger@jobalarm.com", "JobAlarm Login Info", $_POST['name']."\r\n\r\n".$_POST['company']."\r\n\r\n".$_POST['email']."\r\n\r\n".$_POST['text'], "From: JobAlarm ContactUs <rstrenger@jobalarm.com>"  );
	  $message ="Your JobAlarm.com account has been created.  To access JobAlarm, please go to www.jobalarm.com/login.php and use the temporary password: ".$temp." to login.";
	  
	  mail( $_POST['email'], "JobAlarm Access", $_POST['first_name']."\r\n\r\n".$message,"From: JobAlarm Admin <rstrenger@jobalarm.com>". "\r\n" .
"CC: ".$account_data['email'] );
	  	  //echo json_encode(array('success'=>true));
	  exit();
}






   }
	
	if ($_POST['confirm'] == 1) {
	  echo "<script>window.close();</script>";
	}
}

	
	
	//$user = "SELECT * FROM `users` where accountId =" . $account_data['accountId'] . " ORDER BY last_name ASC";
	
	//$dbUser = Config::get('db')->get_results($user);
	

?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title>JobAlarm | Contact Us</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta content="" name="description"/>
<meta content="" name="author"/>
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css">
<link href="theme/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="theme/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css">
<link href="theme/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="theme/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css">
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="theme/assets/global/plugins/bootstrap-datepicker/css/datepicker3.css"/>
<link rel="stylesheet" type="text/css" href="theme/assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="theme/assets/admin/pages/css/todo.css"/>
<!-- END PAGE LEVEL STYLES -->
<!-- BEGIN THEME STYLES -->
<link href="theme/assets/global/css/components-rounded.css" id="style_components" rel="stylesheet" type="text/css">
<link href="theme/assets/global/css/plugins.css" rel="stylesheet" type="text/css">
<link href="theme/assets/admin/layout3/css/layout.css" rel="stylesheet" type="text/css">
<link href="theme/assets/admin/layout3/css/themes/blue-steel.css" rel="stylesheet" type="text/css" id="style_color">
<link href="theme/assets/admin/layout3/css/custom.css" rel="stylesheet" type="text/css">
<!-- END THEME STYLES -->
<link rel="shortcut icon" href="favicon.ico"/>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<!-- DOC: Apply "page-header-menu-fixed" class to set the mega menu fixed  -->
<!-- DOC: Apply "page-header-top-fixed" class to set the top menu fixed  -->
<body class="page-boxed page-header-fixed page-container-bg-solid page-sidebar-closed-hide-logo page-sidebar-closed">
<!-- BEGIN TOP BAR -->
    <div class="pre-header">
        <div class="container">
            <div class="row">
                <!-- BEGIN TOP BAR LEFT PART -->
                
                <!-- END TOP BAR LEFT PART -->
                <!-- BEGIN TOP BAR MENU -->
                
                <!-- END TOP BAR MENU -->
            </div>
        </div>        
    </div>
    <!-- END TOP BAR -->
<!-- BEGIN HEADER -->
<div class="page-header" style="height: 69px">
	<!-- BEGIN HEADER TOP -->
	
		<div class="container">
			<!-- BEGIN LOGO -->
			
			<div class="page-logo" align="center">
				<img src="img/logo1.png"></div>
			
				
			<!-- END LOGO -->
			<!-- BEGIN RESPONSIVE MENU TOGGLER -->
			
			<!-- END RESPONSIVE MENU TOGGLER -->

		</div>
	
	<!-- END HEADER TOP -->

<!-- BEGIN HEADER MENU -->
        
	<!-- END HEADER MENU -->
</div>
<!-- END HEADER -->
<!-- BEGIN PAGE CONTENT -->
<div class="page-content">
	<div class="container">
		<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
		
		<!-- /.modal -->
		<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
		<!-- BEGIN PAGE BREADCRUMB -->
				<!-- END PAGE BREADCRUMB -->
		<!-- BEGIN PAGE CONTENT INNER -->
		<div class="portlet light">
			<div class="portlet-body">
				<div class="row">
					<div class="col-md-12" style="left: 0px; top: 0px">
						<!-- Google Map -->
						<div class="row margin-bottom-20">
						  <div class="col-md-6">
								<div class="space20">
								</div>
								<!-- BEGIN FORM-->
								<form action="adduser.php" id="adduser" name="adduser" method="post" onsubmit="return confirm('Would you like to add another user?')">
								<input type="hidden" name="adduser" value="1" />
								
								
									<h3 class="form-section" style="text-align: center"><strong>Add a User </strong></h3>
									
									
									
								<div class="form-group">									
								<select id="role" name="role" type="select" class="form-control" placeholder="User Role" required />
<option value="0">Select a Role</option>
<option value="3">User</option>
<option value="4">Super User (All Stores)</option>
<option value="10">Admin</option></select>
							
								</div>									
									<div class="form-group">
										<div class="input-icon">
											<i class="fa fa-user"></i>
											<input id="first_name" name="first_name" type="text" class="form-control" placeholder="First Name" maxlength="20" required />
										</div>
									</div>
									<div class="form-group">
										<div class="input-icon">
											<i class="fa fa-user"></i>
											<input id="last_name" name="last_name" type="text" class="form-control" placeholder="Last Name" maxlength="25" required />
										</div>
									</div>
									<div class="form-group">
										<div class="input-icon">
											<i class="fa fa-envelope"></i>
											<input id="email" name="email" type="text" class="form-control" placeholder="Email or Store# for Single-Store User" maxlength="30" required />
										</div>
									</div>
																		
								   <div class="form-group">
								   <input type="submit" value="Add"/> 
								   </div> 
								   <p>An email will be sent to the User with login instructions.  For Single-Store Users (Using a Store Number as the ID), an email will be sent to the Admin.</p>
								</form>
								
								<!-- END FORM-->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- END PAGE CONTENT INNER -->
	</div>
</div>
<!-- END PAGE CONTENT -->

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
<!-- BEGIN PAGE PLUGINS & SCRIPTS -->
<script type="text/javascript" src="theme/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="theme/assets/global/plugins/select2/select2.min.js"></script>
<script src="theme/assets/admin/pages/scripts/todo.js" type="text/javascript"></script>
<!-- END PAGE PLUGINS & SCRIPTS -->
<script src="theme/assets/global/plugins/gmaps/gmaps.min.js" type="text/javascript"></script>
<script src="theme/assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="theme/assets/admin/layout3/scripts/layout.js" type="text/javascript"></script>
<script src="theme/assets/admin/layout3/scripts/demo.js" type="text/javascript"></script>
<script src="theme/assets/admin/pages/scripts/contact-us.js"></script>
<script>
//jQuery(document).ready(function() {    
//   Metronic.init(); // init metronic core components
//Layout.init(); // init current layout
//Demo.init(); // init demo features
//Todo.init(); // init todo page
//});
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