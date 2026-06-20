<?php

ini_set('display_errors',1);
session_start();

include_once 'inc/class.db.php';
include_once 'inc/class.jatwitter.php';
include_once 'inc/config.php';

$login_error = 0;

if (isset($_POST['sitelogin'])) {
    $mobile = ($_POST['username']);
    $password =($_POST['password']);
    $query = "SELECT * FROM candidate WHERE mobile='{$mobile}' AND pin='".md5($password)."'";
    $dbData = Config::get('db')->get_results($query);
    if (count($dbData) > 0) {
        $_SESSION['profile'] = $dbData[0];
        $data = array('lastlogin_date'=>date('Y-m-d H:i:s'));
        $where = array('id'=>$dbData[0]['id']);
        Config::get('db')->update('candidate',$data,$where,1);
        header('location: accounts.php');
        exit();
    } else {
        $login_error = 1;
	 }
}

?>
<!DOCTYPE html>
<!-- 
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.3.2
Version: 3.2.0
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title>JobAlarm Login</title>
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
<link href="theme/assets/admin/pages/css/login3.css" rel="stylesheet" type="text/css"/>
<!-- END PAGE LEVEL STYLES -->
<!-- BEGIN THEME STYLES -->
<link href="theme/assets/global/css/components-rounded.css" id="style_components" rel="stylesheet" type="text/css">
<link href="theme/assets/global/css/plugins.css" rel="stylesheet" type="text/css">
<link href="theme/assets/admin/layout3/css/layout.css" rel="stylesheet" type="text/css">
<link href="theme/assets/admin/layout3/css/themes/blue-steel.css" rel="stylesheet" type="text/css" id="style_color">
<link href="theme/assets/admin/layout3/css/custom.css" rel="stylesheet" type="text/css">
<!-- END THEME STYLES -->
<link rel="shortcut icon" href="img/favicon.ico"/>



<style type="text/css">

.searchbtn {
  background: #3498db;
  background-image: -webkit-linear-gradient(top, #3498db, #2980b9);
  background-image: -moz-linear-gradient(top, #3498db, #2980b9);
  background-image: -ms-linear-gradient(top, #3498db, #2980b9);
  background-image: -o-linear-gradient(top, #3498db, #2980b9);
  background-image: linear-gradient(to bottom, #3498db, #2980b9);
  -webkit-border-radius: 6;
  -moz-border-radius: 6;
  border-radius: 6px;
  -webkit-box-shadow: 0px 1px 3px #666666;
  -moz-box-shadow: 0px 1px 3px #666666;
  box-shadow: 0px 1px 3px #666666;
  font-family: Arial;
  color: #ffffff;
  font-size: 20px;
  padding: 10px 20px 10px 20px;
  text-decoration: none;
}

.searchbtn:hover {
  background: #3cb0fd;
  background-image: -webkit-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -moz-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -ms-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -o-linear-gradient(top, #3cb0fd, #3498db);
  background-image: linear-gradient(to bottom, #3cb0fd, #3498db);
  text-decoration: none;
}
.style1 {font-family: "Kristen ITC"}
.style2 {font-size: 18px}
</style>
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
                <div class="col-md-6 col-sm-6 additional-shop-info">
                    <ul class="list-unstyled list-inline">
                        
                        
                    </ul>
                </div>
                <!-- END TOP BAR LEFT PART -->
                <!-- BEGIN TOP BAR MENU -->
                
                <!-- END TOP BAR MENU -->
            </div>
        </div>        
    </div>
    <!-- END TOP BAR -->

<!-- BEGIN HEADER -->

	<!-- BEGIN HEADER TOP -->
	<div class="page-header-top">
		<div class="container">
			<!-- BEGIN LOGO -->
			<div class="page-logo">
				<div align="center"><a href="index.php"><img src="img/logo1.png"></a>			    
				</div>
			</div>
			<!-- END LOGO -->
		</div>
	</div>
	<!-- END HEADER TOP -->


<!-- END HEADER -->
<!-- BEGIN PAGE CONTAINER -->
<div class="page-container login" style="margin-bottom:30px">
	<!-- BEGIN PAGE CONTENT -->
    <div class="content">
        <?php
        if (isset($_GET['ae'])) {
            echo '<div style="font-weight:bold;color:red">Account is already signed up.<br />Please login below</div>';
        }
        if (isset($_GET['sc'])) {
            echo '<div style="font-weight:bold;color:green">Account created.<br />Please login below</div>';
        }
        if (isset($_GET['se'])) {
            echo '<div style="font-weight:bold;color:red">Problem creating account.<br />Please try again.</div>';
        }
        if ($login_error == 1) {
            echo '<div style="font-weight:bold;color:red">Invalid login.  Please try again.</div>';
        }
        ?>
	    <form class="login-form" action="login3.php" method="post">
            <input type="hidden" name="sitelogin" value="1" />
		    <h3 class="form-title">Log In</h3>
		    <div class="alert alert-danger display-hide">
			    <button class="close" data-close="alert"></button>
			    <span>
			    Enter Mobile Number and PIN. </span>
		    </div>
		    <div class="form-group">
			    <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
			    <label class="control-label visible-ie8 visible-ie9">Mobile Number</label>
			    <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Mobile #" name="username"/>
		    </div>
		    <div class="form-group" align="right">
			    <label class="control-label visible-ie8 visible-ie9">PIN</label>
				
			    <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Password/PIN" name="password"/>
				
				<!--<a class="btn btn-sm green" data-toggle="modal" href="#requestPin">
                                        Request PIN
                                        <i class="fa fa-plus"></i>
                                    </a>-->
		    </div>
		    <div class="form-actions" style="text-align: left">
			    <button type="submit" class="btn btn-sm green">LOGIN</button>
			    <button type="button" class="btn btn-info btn-sm" style="float:right" data-toggle="modal" data-target="#pinRequest"><i class="fa fa-gears"></i>Request PIN</button>
				<!--<a class="btn btn-sm blue" style="float:right" href="#pinRequest"><i class="fa fa-gears"></i>Request PIN </a>-->
				
				</div>
			   
			    

            <div class="container">
  <div class="row"><br>
    
    <a href="#myModal" id="forget-password" class="forget-password" data-toggle="modal">Need Support?</a>
    
  </div>
</div>

<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 id="myModalLabel">Please provide additional details and we will contact you within 24 hours.</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal col-sm-12">
          <div class="form-group">									
			<select id="type" name="type" type="select" class="form-control" placeholder="Select">
			<option value="Select">Support Type....</option>
			<option value="Password">Password Support</option>
			<option value="Tech Support">Technical Support</option></select>
		</div>
          <div class="form-group"><label>Name</label><input class="form-control required" placeholder="Your name" data-placement="top" data-trigger="manual" data-content="Must be at least 3 characters long, and must only contain letters." type="text"></div>
          <div class="form-group"><label>Phone</label><input class="form-control phone" placeholder="999-999-9999" data-placement="top" data-trigger="manual" data-content="Must be a valid phone number (999-999-9999)" type="text"></div>
          <div class="form-group"><label>Message</label><textarea class="form-control" placeholder="Your message here.." data-placement="top" data-trigger="manual"></textarea></div>
    	  <div class="form-group"><button type="submit" class="btn btn-success pull-left">Send</button> <p class="help-block pull-left text-danger hide" id="form-error">&nbsp; The form is not valid. </p> &nbsp;&nbsp;<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
</div>  
        </form>
      </div>
      
      
    </div>
  </div>
</div>
			    
			    
			    </div>
		    <!-- 		    <div class="create-account">
			    <p>
				    <a href="javascript:;" id="register-btn" class="uppercase">Create an account</a>
			    </p>
		    </div> -->
        </form>
    </div>
	<!-- END PAGE CONTENT -->
</div>
<!-- BEGIN FOOTER -->
<div class="page-footer">
	<div class="container">
	<div align="center">2015 &copy; Premier SSG, Inc. All Rights Reserved.<br>
	  Terms. Privacy Policy </div>
	
	</div>
</div>
<div class="scroll-to-top">
	<i class="icon-arrow-up"></i>
</div>
<!-- END FOOTER -->
<!-- END PAGE CONTAINER -->

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
<script src="theme/assets/global/plugins/bootbox/bootbox.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE PLUGINS & SCRIPTS -->
<script type="text/javascript" src="theme/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="theme/assets/global/plugins/select2/select2.min.js"></script>
<script src="theme/assets/admin/pages/scripts/todo.js" type="text/javascript"></script>
<!-- END PAGE PLUGINS & SCRIPTS -->
<script src="theme/assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="theme/assets/admin/layout3/scripts/layout.js" type="text/javascript"></script>
<script src="theme/assets/admin/layout3/scripts/demo.js" type="text/javascript"></script>
<script src="inc/tweetedjobs-mainTest.js" type="text/javascript"></script>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-59491934-1', 'auto');
  ga('send', 'pageview');

</script>

<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>


<!-- END JAVASCRIPTS -->
<div class="modal fade" id="pinRequest" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                      <h4 class="modal-title">Pin Request</h4>
                </div>
                <div id="pinBody" class="modal-body">
				
                <div class="form-group"><label>Submit your 10-Digit mobile number (numbers only) and we will text you a new PIN#.</label><input id="mobile" class="form-control phone" placeholder="2125551234" data-placement="top" data-trigger="manual" data-content="Must be a valid phone number (numbers only)" type="text">
				</div>
				<button type="button" class="btn blue pull-right" onClick="tj.pinRequest()">Send</button><p class="help-block pull-left text-danger hide" id="form-error">&nbsp; The form is not valid. </p> &nbsp;&nbsp;<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
				<!--<div class="form-group"><button id="pinSubmit" type="submit" class="btn btn-success pull-left">Send</button> <p class="help-block pull-left text-danger hide" id="form-error">&nbsp; The form is not valid. </p> &nbsp;&nbsp;<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>-->
                </div>
							
				</div>
				
                </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</body>
<!-- END BODY -->
</html>