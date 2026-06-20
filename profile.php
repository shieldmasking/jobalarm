<?php
session_start();
ini_set('display_errors',1);
include 'inc/class.db.php';
//include 'inc/class.jatwitter.php';
include 'inc/config.php';

if (!isset($_SESSION['profile'])) {
    header('location: login3.php');
}

$account_data = $_SESSION['profile'];
if (!isset($account_data['mobile'])) {
	header('location: login3.php');
	exit();
}


$dbData = Config::get('db')->get_results("select * from candidate where id=".$account_data['id']);


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
    <title>My Account</title>
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

    .style1 {
	color: #FF0000;
	font-weight: bold;
	font-size: 12px;
}
    </style>
		
					
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<!-- DOC: Apply "page-header-menu-fixed" class to set the mega menu fixed  -->
<!-- DOC: Apply "page-header-top-fixed" class to set the top menu fixed  -->
<body class="page-boxed page-header-fixed page-container-bg-solid page-sidebar-closed-hide-logo page-sidebar-closed">
<!--<script>  function statusChangeCallback(response)
{

   if (response.status === 'connected') {
      testAPI();    }
   else if (response.status === 'not_authorized') {      document.getElementById('status').innerHTML = 'Please log ' +        'into this app.';    }
   else {      document.getElementById('status').innerHTML = 'Please log ' +        'into Facebook.';    }
   }

function checkLoginState()
  {
     FB.getLoginStatus(function(response)
      {
         statusChangeCallback(response);
      });
   }
   window.fbAsyncInit = function()
   {
       FB.init({      appId      : '1051341108212143',      xfbml      : true,      version    : 'v2.3'    });
      FB.getLoginStatus(function(response)
      {    statusChangeCallback(response);  });
   };
   (function(d, s, id)
 {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs); } (document, 'script', 'facebook-jssdk')
    );


    function testAPI() {
// for FB troubleshooting
/*
    var body = 'Reading JS SDK documentation';
    FB.api('/me/feed', 'post', { message: body },
       function(response)
       {
         if (!response || response.error) {
      console.log(response);
       } else {
      console.log(response);
      }
   });
*/

    FB.api('/me', function(response)
     {
//        testAPI(console.log());
        document.getElementById('status').innerHTML =        'Thanks for logging in, ' + response.name + '!';
        tj.alex.getGroupsFb(response.id, response.last_name + ', ' + response.first_name);
     });

     }


   </script>-->
     <!--  Below we include the Login Button social plugin. This button uses  the JavaScript SDK to present a graphical Login button that triggers  the FB.login() function when clicked.-->
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
                <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                <a href="javascript:;" class="menu-toggler"></a>
                <!-- END RESPONSIVE MENU TOGGLER -->
            </div>
        </div>
        <!-- END HEADER TOP -->
        <!-- BEGIN HEADER MENU -->
        <div class="page-header-menu" style="background-color:#444d58">
            <div class="container">
                <h1 style="width:340px;float:left;margin:0;padding:5px;color:white">My Account</h1>
                
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
                        <a href="index.php">Home</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li class="active">
                        My Account
                    </li>
                </ul>
                <!-- END PAGE BREADCRUMB -->
                <!-- BEGIN PAGE CONTENT INNER -->
                <div class="row margin-top-10">
                    <div class="col-md-6 col-xs-12">
                        <h2>
                            Welcome, <?php echo $account_data['first_name']; ?>
                            
                        </h2>
                    </div>
					
                    
                </div>
                
                <div class="row margin-top-10" id="accountRow">
				
				<div class="col-md-12 col-sm-12" id="accountDetails">
                        <!-- BEGIN PORTLET-->
                        <div class="portlet light ">
                            <div class="portlet-title">
                                <div class="caption caption-md">
                                    <i class="icon-bar-chart theme-font hide"></i>
                                    <span class="caption-subject theme-font bold uppercase"><i class="fa fa-gears"></i> - My Details</span></div>
									<div class="pull-right">
                                
								<a class="btn btn-sm green" data-toggle="modal" href="#" id="editDetails">Edit<i class="fa fa-edit"></i></a></div></div>
								                              
								
														
								
                            
                            <div class="portlet-body">
                                <h4>
                            Name: <?php echo $account_data['first_name']; ?>  <?php echo $account_data['last_name']; ?>
                            <p>
							Location: <?php echo $account_data['zip']; ?> <p>
							Mobile Number:<?php echo $account_data['mobile']; ?><p>
							Resume:<?php echo $account_data['resume']; ?></p>
                            
                        </h4>
	                        
                        
                    
							
							
							</div>
							
                            </div>
                        </div>
                        <!-- END PORTLET-->
                    
				
		
				
				
                    <div class="col-md-12 col-sm-12" id="accountCell">
                        <!-- BEGIN PORTLET-->
                        <div class="portlet light ">
                            <div class="portlet-title">
                                <div class="caption caption-md">
                                    <i class="icon-bar-chart theme-font hide"></i>
                                    <span class="caption-subject theme-font bold uppercase"><i class="fa fa-settings"></i> - My Companies</span>
                                </div>                                <!--<div class="pull-right">                                <fb:login-button scope="public_profile,email,publish_actions,user_groups" onlogin="checkLoginState();" id="faceBookLoginButton"></fb:login-button><div id="status"></div></div>
                            </div>-->
                            <div class="portlet-body">
                                <div class="portlet-body">
	
	<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#my_jobs">Active</a></li>
    <li><a data-toggle="tab" href="#joinCo">Add </a></li>
	</ul>
    
	<div class="tab-content">
	<div id="my_jobs" class="tab-pane fade in active">
	<table class="table table-striped table-bordered table-hover" id="datatableAccount_ajax">								    <thead>	
	<tr role="row" class="heading">
	<th>Employer</th>
	<th>Date Subscribed</th>
	<th>Message Types</th>
	<th>Message Count</th>
	<th>Actions</th></tr>
	</thead>
	<tbody id="maTweetBody"></tbody>
	</table>
	</div>	
	
	<div id="joinCo" class="tab-pane fade">
	<table class="table table-striped table-bordered table-hover stripe" id="joinCompany">
	<thead><tr role="row" class="heading">
	<th>Groups</th>
	<th>Members</th>
	<th>Action</th>
	</tr></thead>
	<tbody id="majoinBody"></tbody>
	</table>
	</div>   </div>                         
							</div>
                            </div>
                        </div>
                        <!-- END PORTLET-->
                    </div>   
					</div>                                      
					
					<!--<div class="col-md-12 col-sm-12" id="groupManagerCell">
					
					
					
					                     	<div class="portlet light ">
											                       
                            <div class="portlet-title">                                <div class="caption caption-md">                                    <i class="icon-bar-chart theme-font hide"></i>                                    <span class="caption-subject theme-font bold uppercase"><i class="fa fa-facebook"></i> - Manage Groups</span></div>

                      <div class="actions">                                    <a class="btn btn-sm purple" data-toggle="modal" href="#" id="groupManagerBackButton">                                        Back                                        <i class="fa fa-backward"></i></a>
                                                    </div>                            
                            </div>                           <div class="portlet-body" id="groupManagerCellBody">
										
					</div>
									        
                </div>
</div>-->
                

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
    <!--<script src="https://code.highcharts.com/highcharts.js"></script>
	<script src="https://code.highcharts.com/modules/exporting.js"></script>-->
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
	<script>
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core components
Layout.init(); // init current layout
Demo.init(); // init demo features
Todo.init(); // init todo page
//do it, do it do it do it
tj.alex.initializeAccountGrid();
});
</script>
    <!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>