<?php
session_start();
ini_set('display_errors',1);
include './inc/class.db.php';
include './inc/config.php';

if (!isset($_SESSION['account'])) {
    header('location: index.php');
}

$account_data = $_SESSION['account'];
$user = $account_data['id'];
$labelName = $account_data['labelName'];
$logo = $account_data['logo'];
$favicon = $account_data['favicon'];
$login = $account_data['labelLogin'];

$dbData = Config::get('db')->get_results("select * from `productiveUser` where `id`={$user}");
$user = $dbData[0]['userName'];

if (!isset($account_data['accountId'])) {
	header('location: index.php');
	exit();
}

$changepwdstatus = 0;
$changepwderror = '';
if (isset($_REQUEST['changing_pwd'])) {
    $old_password = (isset($_REQUEST['orig_password'])) ? trim($_REQUEST['orig_password']) : '';
    $new_password = (isset($_REQUEST['new_password'])) ? trim($_REQUEST['new_password']) : '';
    $cfm_password = (isset($_REQUEST['cfm_password'])) ? trim($_REQUEST['cfm_password']) : '';
	$new_username = (isset($_REQUEST['userName'])) ? trim($_REQUEST['userName']) : '';
	$userName = strtolower(trim($new_username));

    if (!(strlen($old_password) > 0)) {
        $changepwdstatus = 2;
        $changepwderror .= 'You must enter your original password.<br />';
    }
    
    if (!(strlen($new_password) > 7)) {
        $changepwdstatus = 2;
        $changepwderror .= 'Your new password must be at least 8 characters.<br />';
    } 
	
	if (!(strlen($new_username) > 7)) {
        $changepwdstatus = 2;
        $changepwderror .= 'Your new username must be at least 8 characters.<br />';
    } 

    if (!($new_password == $cfm_password)) {
        $changepwdstatus = 2;
        $changepwderror .= 'Your confirmation password must match the new password.<br />';
    }
   
    if ($changepwdstatus == 0) {
        $old_password = md5($_REQUEST['orig_password']);
        $new_password = md5($_REQUEST['new_password']);
        $cfm_password = md5($_REQUEST['cfm_password']);
		$new_username = htmlspecialchars($_REQUEST['userName']);
		$dbUsername = Config::get('db')->get_results("select * from `productiveUser` where LOWER(`userName`)='{$userName}' and `id`!={$account_data['id']}");
        $dbData = Config::get('db')->get_results("select `id` from `productiveUser` where `id`={$account_data['id']} and `temp`='{$old_password}'");
        if ($dbUsername) {
            $changepwdstatus = 2;
            $changepwderror .= 'Username is already in use.  Please select a new Username<br />';            
        }else if(!$dbData && !$dbUsername) {
            $changepwdstatus = 2;
            $changepwderror .= 'Current password is incorrect.  Please retry.<br />';            
        }else{
            $changepwdstatus = 1;
            $data = array('pwd'=>$new_password,
						'userName'=>$new_username
						);
            $where = array('id'=>$account_data['id']);
            Config::get('db')->update('productiveUser',$data,$where,1);
			session_destroy();
			header('Location: ' . $login . '');
        }                
    }    

}


//echo json_encode($account_data);

//var_dump($account_data);
//var_dump($twitter_login_url);
// $_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
// $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
// switch ($connection->http_code) {
// 	case 200:
// 		$url = $connection->getAuthorizeURL($token);
// 		break;
// 	default:
// 		$error = 'Could not connect to Twitter. Refresh the page or try again later.';
// }

//$zipSearch = getDistanceQuery(Config::get('db'),75218,20);
//echo $zipSearch."<br />";

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
    <title>Password Update</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="theme/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="theme/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
    <link href="theme/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="theme/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE STYLES -->
    <link href="theme/assets/admin/pages/css/tasks.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="theme/assets/admin/pages/css/todo.css" />
    <link rel="stylesheet" type="text/css" href="theme/assets/global/plugins/select2/select2.css"/>
    <link rel="stylesheet" type="text/css" href="theme/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
    <link rel="stylesheet" type="text/css" href="theme/assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
    <link rel="stylesheet" type="text/css" href="theme/assets/global/plugins/jquery-nestable/jquery.nestable.css"/>

    <!-- END PAGE STYLES -->
    <!-- BEGIN THEME STYLES -->
    <link href="theme/assets/global/css/components-rounded.css" id="style_components" rel="stylesheet" type="text/css" />
    <link href="theme/assets/global/css/plugins.css" rel="stylesheet" type="text/css" />
    <link href="theme/assets/admin/layout3/css/layout.css" rel="stylesheet" type="text/css" />
    <link href="theme/assets/admin/layout3/css/themes/blue-steel.css" rel="stylesheet" type="text/css" id="style_color" />
    <link href="theme/assets/admin/layout3/css/custom.css" rel="stylesheet" type="text/css" />
    <!-- END THEME STYLES -->
    <link rel="shortcut icon" href="/img/<?php echo $favicon; ?>" />
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
    <style type="text/css">
        .modal-backdrop {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 0;
            background-color: #000;
        }
        .dd3-content,.dd-handle {
            height:60px;
        }
        .dd-handle {
            line-height:48px;
        }
        .dd-item {
            line-height:15px;
        }
        #message {
            display:none;
            background: #f1f1f1;
            color: #000;
            position: relative;
            padding: 20px;
            margin-top: 10px;
        }
        #message p {
            padding: 10px 35px;
            font-size: 18px;
        }
        /* Add a green text color and a checkmark when the requirements are right */
        .valid {
            color: green;
        }

        .valid:before {
            position: relative;
            left: -35px;
            content: "&#10004;";
        }

        /* Add a red text color and an "x" icon when the requirements are wrong */
        .invalid {
            color: red;
        }

        .invalid:before {
            position: relative;
            left: -35px;
            content: "&#10006;";
        }
    </style>
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
        <!-- END HEADER TOP -->
        <!-- BEGIN HEADER MENU -->
        <div class="page-header-menu" style="background-color:#282A3C">
		<div class="container">
            <div class="col-md-6 col-sm-6">
			<a href="index.php">
                        <img src="/img/<?php echo $logo; ?>" alt="logo" class="logo-default" />
                    </a>
			</div>
			<div class="col-md-6 col-sm-6">			
			
                <h1 style="width:340px;float:left;margin:0;padding:5px;color:white">Password Update</h1>
                <!-- BEGIN MEGA MENU -->
                <!-- DOC: Apply "hor-menu-light" class after the "hor-menu" class below to have a horizontal menu with white background -->
                <!-- DOC: Remove data-hover="dropdown" and data-close-others="true" attributes below to disable the dropdown opening on mouse hover -->
               
            </div>
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

                <!-- BEGIN PAGE BREADCRUMB -->
                
                <!-- END PAGE BREADCRUMB -->
                <!-- BEGIN PAGE CONTENT INNER -->
                <div class="row margin-top-10">
                    <h3>
                        Welcome <?php echo $account_data['first_name']; ?>
                        
                    </h3>
                </div>
                <div class="row margin-top-10">
                    <div class="col-md-12 col-xs-12">
                        <div class="portlet-body form">
							<!-- BEGIN FORM-->
                            <div class="col-md-3 col-xs-12"><h4 style="color:blue">Please Change Your Password</h4></div>
                            <div class="col-md-9 col-xs-12">
                                <div class="row">
                                    <div class="col-md-6 col-md-offset-3 col-xs-12">
                                    <?php
                                        if ($changepwdstatus == 2):
                                    ?>
                                        <span style="color:red;font-size:14px;font-weight:bold">
                                        <?php echo $changepwderror; ?>
                                        </span>
                                    <?php
                                        endif;
                                        if ($changepwdstatus == 1):
                                    ?>
                                        <span style="color:green;font-size:14px;font-weight:bold">
                                        Password Changed Successfully</span>
										
                                        
                                    <?php
                                        endif;                                     
                                    ?>
                                    </div>
                                </div>
                                <div class="row">
							        <form name="change_pwd_form" action="pwupdate.php" method="post" class="form-horizontal">
                                        <input type="hidden" name="changing_pwd" value="1" />
								        <div class="form-body">
											<div class="form-group">
										        <label class="col-md-3 control-label">Username</label>
												
												<div class="col-md-6">
											        <input type="text" name="userName" class="form-control" value="<?php echo $user ?>" />
										        
											
												<span class="title">You can change your Username to something other than your email address (Min. 8 characters).
										        </span>
												</div>
									        </div>
									        <div class="form-group">
										        <label class="col-md-3 control-label">Current Password</label>
										        <div class="col-md-6">
											        <input type="password" name="orig_password" class="form-control" placeholder="" />
										        </div>
									        </div>
									        
											<div class="form-group last password-strength">
										<label class="control-label col-md-3">New Password</label>
										<div class="col-md-6">
											<input type="text" class="form-control" name="new_password" id="password_strength">
											<span class="help-block">
											Password Strength Indicator </span>
										</div>
									</div>
									        <div class="form-group">
										        <label class="col-md-3 control-label">Confirm Password</label>
										        <div class="col-md-6">
												    <input type="password" name="cfm_password" class="form-control" placeholder="" />
										        </div>
									        </div>
                                            <div class="form-group">
                                                <div class="col-md-6 col-md-offset-3 col-xs-12">
                                                    <button class="btn blue pull-right" type="submit">Change</button>
                                                </div>
				                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="row">
                                    <div id="message">
                                        <h3>Password must contain the following:</h3>
                                        <p id="letter" class="invalid">A <b>lowercase</b> letter</p>
                                        <p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
                                        <p id="number" class="invalid">A <b>number</b></p>
                                        <p id="length" class="invalid">Minimum <b>8 characters</b></p>
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
    </div>
    <!-- END PAGE CONTAINER -->

    <!-- BEGIN FOOTER -->
<div class="page-footer">
	<div class="container">
	<div align="center" >2018 &copy; Harrelson Group LLC. All Rights Reserved.<br>
	<a href="privacy.html" target="_blank" style="font-size:12px;color:white">Privacy Policy</a> | <a href="/terms/index.html" target="_blank" style="font-size:12px;color:white">Terms of Use</a> </div>
	
	</div>
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
    <script src="theme/assets/global/plugins/jquery-nestable/jquery.nestable.js"></script>
    <script type="text/javascript" src="theme/assets/global/plugins/select2/select2.min.js"></script>
    <script type="text/javascript" src="theme/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="theme/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>    
    <script type="text/javascript" src="theme/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <script type="text/javascript" src="theme/assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>
    <script type="text/javascript" src="theme/assets/global/plugins/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="theme/assets/global/plugins/bootstrap-markdown/js/bootstrap-markdown.js"></script>
    <script type="text/javascript" src="theme/assets/global/plugins/bootstrap-markdown/lib/markdown.js"></script>    
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="theme/assets/admin/pages/scripts/ui-nestable.js"></script>
    <script src="theme/assets/global/plugins/bootstrap-pwstrength/pwstrength-bootstrap.min.js" type="text/javascript"></script>
    <script src="theme/assets/global/scripts/metronic.js" type="text/javascript"></script>
    <script src="theme/assets/admin/layout3/scripts/layout.js" type="text/javascript"></script>
    <script src="theme/assets/admin/layout3/scripts/demo.js" type="text/javascript"></script>
    <script src="theme/assets/admin/pages/scripts/index3.js" type="text/javascript"></script>
    <script src="theme/assets/admin/pages/scripts/tasks.js" type="text/javascript"></script>
    <script src="theme/assets/admin/pages/scripts/table-managed.js"></script>
    <script src="theme/assets/global/scripts/datatable.js"></script>
    <script src="theme/assets/admin/pages/scripts/table-ajax.js"></script>
    
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
<script src="theme/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="theme/assets/global/plugins/fuelux/js/spinner.min.js"></script>
<script type="text/javascript" src="theme/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js"></script>
<script type="text/javascript" src="theme/assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js"></script>
<script type="text/javascript" src="theme/assets/global/plugins/jquery.input-ip-address-control-1.0.min.js"></script>
<script src="theme/assets/global/plugins/bootstrap-pwstrength/pwstrength-bootstrap.min.js" type="text/javascript"></script>
<script src="theme/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<script src="theme/assets/global/plugins/jquery-tags-input/jquery.tagsinput.min.js" type="text/javascript"></script>
<script src="theme/assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js" type="text/javascript"></script>
<script src="theme/assets/global/plugins/bootstrap-touchspin/bootstrap.touchspin.js" type="text/javascript"></script>

<script src="theme/assets/global/plugins/typeahead/handlebars.min.js" type="text/javascript"></script>

<script src="theme/assets/global/plugins/typeahead/typeahead.bundle.min.js" type="text/javascript"></script>

<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->

<script src="theme/assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
<script src="theme/assets/admin/pages/scripts/components-form-tools.js"></script>
	<!-- END PAGE LEVEL SCRIPTS -->
 <script>   
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-59491934-1', 'auto');
  ga('send', 'pageview');

    </script>
	<script>
        jQuery(document).ready(function() {       
    Metronic.init(); // init metronic core componets
    Layout.init(); // init layout
    Demo.init(); // init demo(theme settings page)
    Index.init(); // init index page
	 ComponentsFormTools.init();
        });   
    </script>

    <!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>