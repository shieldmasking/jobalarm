<?php
session_start();
ini_set('display_errors',1);
include 'inc/class.db.php';
include 'inc/class.jatwitter.php';
include 'inc/config.php';
include 'vendor/autoload.php';

if (!isset($_SESSION['account'])) {
    header('location: index.php');
}

$account_data = $_SESSION['account'];
$user = $account_data['id'];

$dbData = Config::get('db')->get_results("select u.*, a.twitter_handle from users u LEFT JOIN account as a on a.id = u.accountId where u.id={$user}");

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

    if (!(strlen($old_password) > 0)) {
        $changepwdstatus = 2;
        $changepwderror .= 'You must enter your original password.<br />';
    }
    
    if (!(strlen($new_password) > 7)) {
        $changepwdstatus = 2;
        $changepwderror .= 'Your new password must be at least 8 characters.<br />';
    }    

    if (!($new_password == $cfm_password)) {
        $changepwdstatus = 2;
        $changepwderror .= 'Your confirmation password must match the new password.<br />';
    }
   
    if ($changepwdstatus == 0) {
        $old_password = md5($_REQUEST['orig_password']);
        $new_password = md5($_REQUEST['new_password']);
        $cfm_password = md5($_REQUEST['cfm_password']);
        $dbData = Config::get('db')->get_results("select id from users where id={$account_data['id']} and temp='{$old_password}'");
        if (!$dbData || count($dbData) == 0) {
            $changepwdstatus = 2;
            $changepwderror .= 'Current password is incorrect.  Please retry.<br />';            
        } else {
            $changepwdstatus = 1;
            $data = array('password'=>$new_password);
            $where = array('id'=>$account_data['id']);
            Config::get('db')->update('users',$data,$where,1);
			session_destroy();
			header('Location: /login.php');
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
    <link rel="shortcut icon" href="favicon.ico" />
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
            <div class="container">
                <!-- BEGIN LOGO -->
                <div class="page-logo">
				<a href="index.php">
                        &nbsp;</a>
						</div>
						<a href="javascript:;" class="menu-toggler"></a>
                    <a href="index.php">
                        <img src="img/logo1.png" alt="logo" class="logo-default" />
                    </a>
                
                <!-- END LOGO -->
                <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                
                <!-- END RESPONSIVE MENU TOGGLER -->

            </div>
        </div>
        <!-- END HEADER TOP -->
        <!-- BEGIN HEADER MENU -->
        <div class="page-header-menu" style="background-color:#444d58">
            <div class="container">
                <h1 style="width:340px;float:left;margin:0;padding:5px;color:white">Password Update</h1>
                <!-- BEGIN MEGA MENU -->
                <!-- DOC: Apply "hor-menu-light" class after the "hor-menu" class below to have a horizontal menu with white background -->
                <!-- DOC: Remove data-hover="dropdown" and data-close-others="true" attributes below to disable the dropdown opening on mouse hover -->
               
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
                            <div class="col-md-3 col-xs-12"><h2 style="color:blue">Please Change Your Password</h2></div>
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
										        <label class="col-md-3 control-label">Current Password</label>
										        <div class="col-md-6">
											        <input type="password" name="orig_password" class="form-control" placeholder="" />
										        </div>
									        </div>
									        <div class="form-group">
										        <label class="col-md-3 control-label">New Password</label>
										        <div class="col-md-6">
												    <input type="password" name="new_password" class="form-control" placeholder="" />
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
    
    <script src="theme/assets/global/scripts/metronic.js" type="text/javascript"></script>
    <script src="theme/assets/admin/layout3/scripts/layout.js" type="text/javascript"></script>
    <script src="theme/assets/admin/layout3/scripts/demo.js" type="text/javascript"></script>
    <script src="theme/assets/admin/pages/scripts/index3.js" type="text/javascript"></script>
    <script src="theme/assets/admin/pages/scripts/tasks.js" type="text/javascript"></script>
    <script src="theme/assets/admin/pages/scripts/table-managed.js"></script>
    <script src="theme/assets/global/scripts/datatable.js"></script>
    <script src="theme/assets/admin/pages/scripts/table-ajax.js"></script>
    <!-- END PAGE LEVEL SCRIPTS -->
    <script src="inc/tweetedjobs-account-settings.js" type="text/javascript"></script>
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