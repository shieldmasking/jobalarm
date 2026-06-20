<?php
session_start();
ini_set('display_errors',1);
include 'inc/class.db.php';
include 'inc/class.jatwitter.php';
include 'inc/config.php';

if (!isset($_SESSION['account'])) {
    header('location: login.php');
}

$account_data = $_SESSION['account'];
if (!isset($account_data['twitter_handle'])) {
	header('location: login.php');
	exit();
}

if ($_SESSION['account']['billing_plan']==0 || $_SESSION['account']['billing_hold']==1) {
    header('location: chooseplan.php');
} 

$dbData = Config::get('db')->get_results("select * from account where id=".$account_data['id']);
if (isset($dbData[0])) {
    $account_balance = $dbData[0]['balance'];
}

if (isset($_GET['la']) && ($account_data['role'] == 10)) {

    $login_as = $_GET['la'];

    $query = "SELECT * FROM account WHERE twitter_handle='{$login_as}'";
    $dbData = Config::get('db')->get_results($query);
    if (count($dbData) > 0) {
        $_SESSION['account'] = $dbData[0];
//        $data = array('lastlogin_date'=>date('Y-m-d H:i:s'));
//        $where = array('id'=>$dbData[0]['id']);
//        Config::get('db')->update('account',$data,$where,1);
        header('location: dashboard.php');
        exit();
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
    <title>TweetJobs | Job Tweet Search</title>
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
            height:70px;
        }
        .dd-handle {
            line-height:58px;
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
<body class="page-boxed page-header-fixed page-container-bg-solid page-sidebar-closed-hide-logo page-sidebar-closed"><script>  function statusChangeCallback(response) {    if (response.status === 'connected') {      testAPI();    } else if (response.status === 'not_authorized') {      document.getElementById('status').innerHTML = 'Please log ' +        'into this app.';    } else {      document.getElementById('status').innerHTML = 'Please log ' +        'into Facebook.';    }  }  function checkLoginState() {    FB.getLoginStatus(function(response) {      statusChangeCallback(response);    });  }   window.fbAsyncInit = function() {    FB.init({      appId      : '1051341108212143',      xfbml      : true,      version    : 'v2.3'    });  FB.getLoginStatus(function(response) {    statusChangeCallback(response);  });  };  (function(d, s, id) {    var js, fjs = d.getElementsByTagName(s)[0];    if (d.getElementById(id)) return;    js = d.createElement(s); js.id = id;    js.src = "//connect.facebook.net/en_US/sdk.js";    fjs.parentNode.insertBefore(js, fjs);  }(document, 'script', 'facebook-jssdk'));  function testAPI() {    FB.api('/me', function(response) {      document.getElementById('status').innerHTML =        'Thanks for logging in, ' + response.name + '!';        tj.alex.getGroupsFb(response.id, response.last_name + ', ' + response.first_name);    });  }</script><!--  Below we include the Login Button social plugin. This button uses  the JavaScript SDK to present a graphical Login button that triggers  the FB.login() function when clicked.-->
    <!-- BEGIN HEADER -->
    <div class="page-header">
        <!-- BEGIN HEADER TOP -->
        <div class="page-header-top">
            <div class="container">
                <!-- BEGIN LOGO -->
                <div class="page-logo">
                    <a href="index.php">
                        <img src="img/logo1.png" alt="logo" class="logo-default" />
                    </a>
                </div>
                <!-- END LOGO -->
                <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                <a href="javascript:;" class="menu-toggler"></a>
                <!-- END RESPONSIVE MENU TOGGLER -->
            </div>
        </div>
        <!-- END HEADER TOP -->
        <!-- BEGIN HEADER MENU -->
        <div class="page-header-menu" style="background-color:#444d58">
            <div class="container">
                <h1 style="width:340px;float:left;margin:0;padding:5px;color:white">Account Dashboard</h1>
                <!-- BEGIN MEGA MENU -->
                <!-- DOC: Apply "hor-menu-light" class after the "hor-menu" class below to have a horizontal menu with white background -->
                <!-- DOC: Remove data-hover="dropdown" and data-close-others="true" attributes below to disable the dropdown opening on mouse hover -->
                <div class="hor-menu">
                    <ul class="nav navbar-nav">
                        <li class="active">
                            <a href="dashboard.php">Dashboard</a>
                        </li>
                        <li class="menu-dropdown classic-menu-dropdown ">
                            <a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown" href="javascript:;">
                                Account
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu pull-left">
                                <li class=" ">
                                    <a href="account_settings.php">
                                        <i class="icon-settings"></i>
                                        Settings
                                    </a>
                                </li>
                                <li class=" ">
                                    <a href="account_billing.php">
                                        <i class="icon-briefcase"></i>
                                        Billing
                                    </a>
                                </li>
                                <li class=" ">
                                    <a href="account_reports.php">
                                        <i class="icon-bar-chart"></i>
                                        Reports
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php if($account_data['role'] == 10): ?>
                        <li class="menu-dropdown classic-menu-dropdown ">
                            <a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown" href="javascript:;">
                                Administration
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu pull-left">
                                <li class=" ">
                                    <a href="useradmin.php">
                                        <i class="icon-users"></i>
                                        Account Manager
                                    </a>
                                </li>
                                <li class=" ">
                                    <a href="couponcodes.php">
                                        <i class="icon-list"></i>
                                        Promo Codes
                                    </a>
                                </li>                                                                
                                <li class=" ">
                                    <a href="hashtagadmin.php">
                                        <i class="icon-settings"></i>
                                        Hashtags
                                    </a>
                                </li>
                                <li class=" ">
                                    <a href="admin_reports.php">
                                        <i class="icon-bar-chart"></i>
                                        Reports
                                    </a>
                                </li>
                                <li class=" ">
                                    <a href="sitestatus.php">
                                        <i class="icon-heart"></i>
                                        System Status
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php endif; ?>     
                    </ul>
                </div>
                <a class="btn blue pull-right margin-top-10" href="logout.php"><i class="fa fa-sign-out"></i>Sign Out</a>
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
                <ul class="page-breadcrumb breadcrumb">
                    <li>
                        <a href="#">Home</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li class="active">
                        Dashboard
                    </li>
                </ul>
                <!-- END PAGE BREADCRUMB -->
                <!-- BEGIN PAGE CONTENT INNER -->
                <div class="row margin-top-10">
                    <div class="col-md-6 col-xs-12">
                        <h1>
                            Welcome, <?php echo $account_data['fullName']; ?>
                            <span style="font-size:15px;">
                                (@<?php echo $account_data['twitter_handle']; ?>)
                            </span>
                        
                        </h1>
                    </div>
                    <div class="col-md-6 col-xs-12">
                       <?php if ($_SESSION['account']['billing_plan'] != 2) : ?>
                    
                        <h1 class="pull-right">Remaining Balance: <strong>$<?php echo $account_balance; ?></strong> </h1>
                       <?php endif; ?>
                    </div>
                </div>
                <div class="row margin-top-10">
                    <div class="col-md-12 col-sm-12">
                        <!-- BEGIN PORTLET-->
                        <div class="portlet light ">
                            <div class="portlet-title">
                                <div class="caption caption-md">
                                    <i class="icon-bar-chart theme-font hide"></i>
                                    <span class="caption-subject theme-font bold uppercase">My Campaigns</span>
                                </div>
                                <div class="actions">
                                <?php if ($_SESSION['account']['billing_plan'] != 2) : ?>
                                    <a class="btn btn-sm green" onclick="tj.verifyFunds(25);" href="javascript:;">
                                        Add Campaign
                                        <i class="fa fa-plus"></i>
                                    </a>
                                <?php endif; ?>
                                </div>
                                <div class="modal fade" id="addfundsdialog" tabindex="-1" role="basic" aria-hidden="true">
								    <div class="modal-dialog">
									    <div class="modal-content">
										    <div class="modal-header">
											    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
											    <h4 class="modal-title">Notice</h4>
										    </div>
										    <div class="modal-body">
												Your account balance after subtracting current campaign totals must have at least 25$ remaining to add new campaigns.<br />Click OK to add Funds.
										    </div>
										    <div class="modal-footer">
											    <button type="button" class="btn default" data-dismiss="modal">Cancel</button>
											    <button type="button" class="btn blue" onclick="window.location='account_billing.php';">OK</button>
										    </div>
									    </div>
									    <!-- /.modal-content -->
								    </div>
								    <!-- /.modal-dialog -->
							    </div>                                
                            </div>
                            <div class="portlet-body">                            	 
                                <div class="table-container" >
								    <table class="table table-striped table-bordered table-hover" id="datatable_ajax">
								    <thead>
								    <tr role="row" class="heading">
									    <th>
										     ID
									    </th>
									    <th width="15%">
										     Name
									    </th>
									    <th width="10%">
										     Start Date
									    </th>
									    <th width="10%">
										     End Date
									    </th>
                                        <th width="5%">
                                             Tweets
                                        </th>
									    <th width="10%">                                             
										     Per Click<br />Budget
									    </th>
									    <th width="10%">
										     Per Day<br />Budget
									    </th>
									    <th width="10%">
										     Total<br />Budget
									    </th>
                                        <th width="10%">
                                             Remaining<br />Budget
                                        </th>
									    <th width="20%">
										     Action
									    </th>
								    </tr>
								    
								    </thead>
								    <tbody>
								    </tbody>
								    </table>
							    </div>  
                            </div>
                        </div>
                        <!-- END PORTLET-->
                    </div>
                </div>
                <div class="row margin-top-10" id="faceBookRow">
                    <div class="col-md-12 col-sm-12" id="faceBookTweetCell">
                        <!-- BEGIN PORTLET-->
                        <div class="portlet light ">
                            <div class="portlet-title">
                                <div class="caption caption-md">
                                    <i class="icon-bar-chart theme-font hide"></i>
                                    <span class="caption-subject theme-font bold uppercase"><i class="fa fa-facebook"></i> - Facebook Groups</span>
                                </div>                                <div class="pull-right">                                <fb:login-button scope="public_profile,email,publish_actions,user_groups, publish_pages" onlogin="checkLoginState();" id="faceBookLoginButton"></fb:login-button><div id="status"></div></div>
                            </div>
                            <div class="portlet-body">
                                <div class="portlet-body">                               								    <table class="table table-striped table-bordered table-hover" id="datatableFacebook_ajax">								    <thead>								    <tr role="row" class="heading">									    <th>										     User Name									    </th>									    <th>										     Post Date									    </th>									    									    <th>										     Text									    </th>									    									    <th>										     Click Count									    </th>									    									    									    									    <th>										     Manage									    </th>									    									                                            									    								    </tr>								    								    </thead>								    <tbody id="fbTweetBody">										<!-- <tr>											<td>												1											</td>											<td>												Test Group											</td>											<td>												Coppell											</td>											<td>												TX											</td>											<td>												test; key; words; go; here;											</td>											<td>												<a class="btn btn-sm blue" >Join <i class="fa fa-gears"></i></a>												<a class="btn btn-sm yellow" >Pending <i class="fa fa-gears"></i></a>												<a class="btn btn-sm green" >Post <i class="fa fa-gears"></i></a>												<a class="btn btn-sm purple" >Posted <i class="fa fa-gears"></i></a>											</td>										</tr> -->								    </tbody>								    </table>							                             </div> 
                            </div>
                        </div>
                        <!-- END PORTLET-->
                    </div>                                         <div class="col-md-12 col-sm-12" id="groupManagerCell">                     	<div class="portlet light ">                            <div class="portlet-title">                                <div class="caption caption-md">                                    <i class="icon-bar-chart theme-font hide"></i>                                    <span class="caption-subject theme-font bold uppercase"><i class="fa fa-facebook"></i> - Manage Groups</span>                                </div>                                                                <div class="actions">                                    <a class="btn btn-sm purple" data-toggle="modal" href="#" id="groupManagerBackButton">                                        Back                                        <i class="fa fa-backward"></i>                                    </a>                                </div>                            </div>                            <div class="portlet-body" id="groupManagerCellBody">                            </div>                     </div>
                </div>
                <div class="row margin-top-10">
                    <div class="col-md-6 col-sm-12">
                        <!-- BEGIN PORTLET-->
                        <div class="portlet light tasks-widget">
                            <div class="portlet-title">
                                <div class="caption caption-md">
                                    <i class="icon-bar-chart theme-font hide"></i>
                                    <span class="caption-subject theme-font bold uppercase">Spend Summary</span>
                                </div>
                            </div>
                            <div class="portlet-body">
<!--                                 <div class="table-scrollable" style="height:474px"> -->
								   <table class="table table-striped table-bordered table-hover" id="datatablespend_ajax">
								    <thead>
								    <tr role="row" class="heading">
									    <th width="0">
										     ID
									    </th>
									    <th width="25%">
										     Campaign
									    </th>
                                        <th width="25%">
                                             Tweet
                                        </th>
									    <th width="25%">
										     Date
									    </th>
									    <th width="25%">
										     Amount
									    </th>
								    </tr>								    
								    </thead>
								    <tbody>
								    </tbody>
								    </table>
							    <!-- </div> -->
                            </div>
                        </div>
                        <!-- END PORTLET-->
                    </div>                
                    <div class="col-md-6 col-sm-12">
                        <!-- BEGIN PORTLET-->
                        <div class="portlet light ">
                            <div class="portlet-title">
                                <div class="caption caption-md">
                                    <i class="icon-bar-chart theme-font hide"></i>
                                    <span class="caption-subject theme-font bold uppercase">My Job Tweets</span>
                                </div>
                                <div class="actions">
                                    <a class="btn btn-sm green" data-toggle="modal" href="#addjobtweet">
                                        Add Job Tweet
                                        <i class="fa fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <ul class="pager" style="margin:0;margin-bottom:5px;">
                                  <li class="prev"><button style="float:left;" class="btn btn-sm" onclick="tj.prevPage();">Previous</button></li>
                                  <li style="border:none;"><span id="pageNum" style="border:none">Page 1 of 1</span></li>
                                  <li class="next"><button style="float:right;" class="btn btn-sm" onclick="tj.nextPage();">Next</button></li>
                                </ul>
                                <div data-always-visible="0" data-rail-visible="0" data-handle-color="#dae3e7">
                                    <div id="tweet-list" class="todo-tasklist">
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="addjobtweet" tabindex="-1" role="basic" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
											<h4 class="modal-title">Add Job Tweet</h4>
										</div>
										<div class="modal-body">
												<input style="width:100%" type="text" id="jobtweetmessage" maxlength="140" name="jobtweetmessage" />
										</div>
										<div class="modal-footer">
											<button type="button" class="btn default" data-dismiss="modal">Close</button>
											<button type="button" class="btn blue" onclick="tj.addJobTweet()">Send</button>
										</div>
									</div>
									<!-- /.modal-content -->
								</div>
								<!-- /.modal-dialog -->
							</div>
                        </div>
                        <!-- END PORTLET-->
                    </div>
                </div>
               
                <!-- END PAGE CONTENT INNER -->
            </div>
        </div>
        <!-- END PAGE CONTENT -->
    </div>
    <!-- END PAGE CONTAINER -->                    <div class="modal fade" id="postModal">  <div class="modal-dialog">    <div class="modal-content">      <div class="modal-header">        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        <h4 class="modal-title">Post to FaceBook</h4>      </div>      <div class="modal-body" id="postModalBody">              </div>      <div class="modal-footer">        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>        <button id="postModalPostButton" type="button" class="btn btn-primary">Post</button>      </div>    </div><!-- /.modal-content -->  </div><!-- /.modal-dialog --></div><!-- /.modal -->
    <!-- BEGIN FOOTER -->
    <div class="page-footer">
        <div class="container">
            2015 &copy; TweetedJobs. All Rights Reserved.
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
    <script src="theme/assets/global/plugins/bootbox/bootbox.min.js" type="text/javascript"></script>
    
    <script src="theme/assets/global/scripts/metronic.js" type="text/javascript"></script>
    <script src="theme/assets/admin/layout3/scripts/layout.js" type="text/javascript"></script>
    <script src="theme/assets/admin/layout3/scripts/demo.js" type="text/javascript"></script>
    <script src="theme/assets/admin/pages/scripts/index3.js" type="text/javascript"></script>
    <script src="theme/assets/admin/pages/scripts/tasks.js" type="text/javascript"></script>
    <script src="theme/assets/admin/pages/scripts/table-managed.js"></script>
    <script src="theme/assets/global/scripts/datatable.js"></script>
    <script src="theme/assets/admin/pages/scripts/table-ajax.js"></script>
    <!-- END PAGE LEVEL SCRIPTS -->
    <script src="inc/tweetedjobs-mainTest.js" type="text/javascript"></script>
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