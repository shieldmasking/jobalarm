<?php
ini_set('display_errors', 1);
ini_set("allow_url_fopen", 1);
session_start();

include_once '../inc/class.db.php';
include_once '../inc/config.php';


$login_error = 0;
//$page = (isset($_REQUEST['p'])) ? $_REQUEST['p'] : 0;
$dataId = (isset($_REQUEST['i'])) ? $_REQUEST['i'] : 0;


if (isset($_POST['sitelogin'])) {

        $email = htmlspecialchars($_POST['username']);
        $password = md5($_POST['password']);
        $p = (isset($_POST['page'])) ? $_POST['page'] : 0;
//$p = (isset($_POST['page'])) ? $_POST['page'] : 0;
        if (intval($p) > 0) {
            $header = 'location: /dashboard.php#reportView?i=' . $p . '';
        }else {
            $header = 'location: /dashboard.php';
        }


        $query = "SELECT u.*, UNIX_TIMESTAMP() as login_time, IFNULL(d.prodMeasure,1) as prodMeasure, IFNULL(a.payPeriod,0) as payPeriod, DAYOFYEAR(a.payPeriodFirst) as firstDay, a.payPeriodFirst as payStart, IFNULL(a.image,'') as image, count(IFNULL(d.prodMeasure,1)) as prodCount, a.type, a.status, a.name as company, IFNULL(l.login,'') as labelLogin, IFNULL(l.id,'') as labelId, IFNULL(l.logo,'') as logo, IFNULL(l.favicon,'') as favicon, IFNULL(l.labelName,'') as labelName, u.deptId as deptid, d.dept as deptName, d.unitId, a.active as acctActive FROM `productiveUser` u LEFT OUTER JOIN `productiveDeptXref` as x on x.userId = u.id LEFT OUTER JOIN `ProductiveDept` as d on d.id = x.deptId LEFT JOIN `productiveAccount` as a on a.id = u.accountId LEFT JOIN `productiveLabel` as l on l.id = a.label WHERE (u.email='{$email}' OR u.userName='{$email}') AND u.pwd='{$password}' GROUP BY u.id";
        $dbData = Config::get('db')->get_results($query);
        if (count($dbData) > 0) {
            $_SESSION['account'] = $dbData[0];
            $now = time() - 21600;
            $data = array('lastlogin' => date("Y-m-d H:i:s", $now));
            $where = array('id' => $dbData[0]['id']);
            Config::get('db')->update('productiveUser', $data, $where, 1);
			
			$logindata = array(
				'userId' =>$dbData[0]['id'],
				'lastlogin' =>date("Y-m-d H:i:s", $now)				
				);
			Config::get('db')->insert('productiveLogin', $logindata);


            if ($_SESSION['account']['active'] == 0 || $_SESSION['account']['acctActive'] == 0) {
                header('location: /contact.php');
            }else if (trim($_SESSION['account']['pwd']) == $_SESSION['account']['temp']) {
                header('location: /pwupdate.php');
            }else if ($_SESSION['account']['role'] == 4 && $_SESSION['account']['prodMeasure'] != 2 && $p==0) {
                header('location: /dashboard.php#staffing');
            }else if ($_SESSION['account']['role'] == 4 && $_SESSION['account']['prodMeasure'] == 2 && $p==0) {
                header('location: /dashboard.php#supportstaffing');
            }else {
                header($header);
            }
            exit();
        } else {
            $login_error = 1;
        }
    //}

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
<title>Login</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta content="" name="description"/>
<meta content="" name="author"/>
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<!--<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css">-->
<link href="/theme/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="/theme/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css">
<link href="/theme/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="/theme/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css">
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="/theme/assets/global/plugins/bootstrap-datepicker/css/datepicker3.css"/>
<link rel="stylesheet" type="text/css" href="/theme/assets/global/plugins/select2/select2.css"/>
<link href="/theme/assets/admin/pages/css/login3.css" rel="stylesheet" type="text/css"/>
<!-- END PAGE LEVEL STYLES -->
<!-- BEGIN THEME STYLES -->
<link href="/theme/assets/global/css/components-rounded.css" id="style_components" rel="stylesheet" type="text/css">
<link href="/theme/assets/global/css/plugins.css" rel="stylesheet" type="text/css">
<link href="/theme/assets/admin/layout3/css/layout.css" rel="stylesheet" type="text/css">
<link href="/theme/assets/admin/layout3/css/themes/blue-steel.css" rel="stylesheet" type="text/css" id="style_color">
<link href="/theme/assets/admin/layout3/css/custom.css" rel="stylesheet" type="text/css">
<!-- END THEME STYLES -->
<!--
<link rel="shortcut icon" href="/favicon.ico"/>
-->
<SCRIPT TYPE="text/javascript">
<!--
function popup(mylink, windowname)
{
if (! window.focus)return true;
var href;
if (typeof(mylink) == 'string')
   href=mylink;
else
   href=mylink.href;
window.open(href, windowname, 'width=400,height=380,scrollbars=no');
return false;
}
//-->
</SCRIPT>

<style type="text/css">

.page-footer {
	position:absolute;
	bottom:0;
	width:100%;
}
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
<!--<script src='https://www.google.com/recaptcha/api.js'></script>-->
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<!-- DOC: Apply "page-header-menu-fixed" class to set the mega menu fixed  -->
<!-- DOC: Apply "page-header-top-fixed" class to set the top menu fixed  -->
<body class="page-boxed page-header-fixed page-container-bg-solid page-sidebar-closed-hide-logo page-sidebar-closed">
    <!-- BEGIN HEADER -->
	

<div class="page-header">
        <!-- BEGIN HEADER TOP -->
        
        <!-- END HEADER TOP -->
        <!-- BEGIN HEADER MENU -->
        <div class="page-header-menu" style="background-color: #282A3C">
            <div class="container">
                <a href="https://www.vizientinc.com">
                        <img src="../img/vizientLogin.png" alt="logo" class="logo-default" />
                    </a>
                <!-- BEGIN MEGA MENU -->
                <!-- DOC: Apply "hor-menu-light" class after the "hor-menu" class below to have a horizontal menu with white background -->
                <!-- DOC: Remove data-hover="dropdown" and data-close-others="true" attributes below to disable the dropdown opening on mouse hover -->
               
            </div>
        </div>
        <!-- END HEADER MENU -->
    </div>
    <!-- END TOP BAR -->

<!-- BEGIN HEADER -->

<!-- END HEADER -->
<!-- BEGIN PAGE CONTAINER -->

<div class="page-container login">
	<!-- BEGIN PAGE CONTENT -->
	
    <div class="content">
	
        <?php
        if (isset($_GET['ae'])) {
            echo '<div style="font-weight:bold;color:red">Account is already signed up.<br />Please login below</div>';
        }
        if (isset($_GET['sc'])) {
            echo '<div style="font-weight:bold;color:green">Account created.<br />Please login below</div>';
        
		$success = mail( "rstrenger@jobalarm.biz", "ProductiveRN user request to join.", "User: ".  $email. " wishes to trial ProductiveRN");
		}
        if (isset($_GET['se'])) {
            echo '<div style="font-weight:bold;color:red">Problem creating account.<br />Please try again.</div>';
        }
        if ($login_error == 1) {
            echo '<div style="font-weight:bold;color:red">Invalid login.  Please try again.</div>';
        }
        ?>
	    <form class="login-form" action="index.php" method="post">
            <input type="hidden" name="sitelogin" value="1" />
			<input type="hidden" name="page" value="<?php echo $dataId; ?>" />
		    <h3 class="form-title">Sign In</h3>
		    <div class="alert alert-danger display-hide">
			    <button class="close" data-close="alert"></button>
			    <span>
			    Enter Email or Username and password. </span>
		    </div>
		    <div class="form-group">
			    <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
			    <label class="control-label visible-ie8 visible-ie9">Email or Username</label>
			    <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Email Address or Username" name="username"/>
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
	<!-- END PAGE CONTENT -->
</div>

<!-- BEGIN FOOTER -->
<div class="page-footer">
	<div class="container">
	<div align="center">2018 &copy; Harrelson Group LLC. All Rights Reserved.<br>
	 <a href="privacy.html" target="_blank" style="font-size:12px;color:white">Privacy Policy | Terms of Use</a> </div>
	
	</div>
</div>
<div class="scroll-to-top">
	<i class="icon-arrow-up"></i>
</div>

</div>

<div id="forgot" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-light">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h5 id="forgotLabel">Password Reset Request</h5>
      </div>
      <div class="modal-body">
        <form class="form col-sm-12">
         
          <div class="form-group">
		  <label for="forgotemail">Please provide your email address and we will reset your password.</label>
		  <input class="form-control" id="forgotemail" placeholder="Your Email" data-placement="top" data-trigger="manual" type="text" required>
		  </div>  
        </form>
      </div>
     <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="forgotbutton" class="btn btn-primary" onclick="tj.forgot();">Submit</button>
                </div> 
      
    </div>
  </div>
</div>


</div>
<!-- END FOOTER -->
<!-- END PAGE CONTAINER -->

<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="theme/assets/global/plugins/respond.min.js"></script>
<script src="theme/assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
<script src="/theme/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="/theme/assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="/theme/assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="/theme/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="/theme/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="/theme/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="/theme/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="/theme/assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="/theme/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE PLUGINS & SCRIPTS -->
<script type="text/javascript" src="/theme/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="/theme/assets/global/plugins/select2/select2.min.js"></script>
<script src="/theme/assets/admin/pages/scripts/todo.js" type="text/javascript"></script>
<!-- END PAGE PLUGINS & SCRIPTS -->
<script src="/theme/assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="/theme/assets/admin/layout3/scripts/layout.js" type="text/javascript"></script>
<script src="/theme/assets/admin/layout3/scripts/demo.js" type="text/javascript"></script>
<script src="/scripts/main.js" type="text/javascript"></script>
<!--
<script>
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core components
Layout.init(); // init current layout
Demo.init(); // init demo features
Todo.init(); // init todo page
});
</script>
-->
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
