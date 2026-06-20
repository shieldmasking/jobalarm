<?php
session_start();
ini_set('display_errors',1);
include 'inc/class.db.php';
include 'inc/class.jatwitter.php';
include 'inc/config.php';
if (!isset($_SESSION['account'])) {
    header('location: index.php');
}
$account_data = $_SESSION['account'];
if (!($account_data['role'] >2)) { header('location: index.php'); exit(); }

$name = isset($_POST['name']) ? $_POST['name'] : '';
$userId = isset($_POST['userId']) ? $_POST['userId'] : '';

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
    <title>JobAlarm | New Users</title>
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
    <link rel="stylesheet" type="text/css" href="theme/assets/global/css/bootstrap-duallistbox.css" />
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

<script>   
    function adduser() {
    var myWindow = window.open("adduser.php", "", "width=420, height=500");  
}
</script>
<script>   
    function assign() {
    var myWindow = window.open("assign.php", "", "width=400, height=300");  
}
</script>


    

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
                        <img src="img/logo1.png" alt="logo" class="logo-default" style="margin-top: 16px" />
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
                <h1 style="width:340px;float:left;margin:0;padding:5px;color:white">User Manager</h1>
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
                                Tools
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu pull-left">
                                <li class=" ">
                                    <a href="account_settings.php">
                                        <i class="icon-settings"></i>
                                        Account Settings
                                    </a>
                                </li>
                                <?php if ($_SESSION['account']['role'] > 2) : ?>

                                <li class=" ">
                                    <a href="http://admin.jobalarm.com/globals" target="_blank">
                                        <i class="fa fa-comment-o"></i>
                                        SMS Manager
                                    </a>
                                </li>
                                <li class=" ">
                                    <a href="users.php">
                                        <i class="fa fa-users"></i>
                                        User Manager
                                    </a>
                                </li>

                                <?php endif; ?>

								<li class=" ">
                                    <a href="dashboard.php">
                                        <i class="fa fa-question"></i>
                                        Support
                                    </a>
                                </li>

                            </ul>
                        </li>
                        <?php if($account_data['role'] > 10): ?>
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
                        <a href="dashboard.php">Home</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li class="active">
                        User Manager
                    </li>
                </ul>
                <!-- END PAGE BREADCRUMB -->
                <!-- BEGIN PAGE CONTENT INNER -->
                <div class="row margin-top-10">
                    <div class="col-md-12 col-sm-12">
                        <!-- BEGIN PORTLET-->
                        
                            <div class="portlet-body">
                  <div class="col-md-12 col-sm-12" id="ManageBannersCell" style="left: 0px; top: 0px">
                        <!-- BEGIN PORTLET-->
                        <div class="portlet light ">
                            <div class="portlet-title">
                                <div class="caption caption-md">
                                    <i class="icon-bar-chart theme-font hide"></i>
                                    <span class="caption-subject theme-font bold uppercase"><i class="fa fa-users"></i> - Banner Locations</span>
                                </div>  
                                </div>                             
                            <div class="portlet-body">
                                
	
	   
	

	<table class="table table-striped table-bordered table-hover" id="datatableUserb_ajax">								    <thead>	
	<tr role="row" class="heading">
	<th>Store Number</th>
	<th>Address</th>
	<th>City</th>
	<th>ST</th>
	<th>Zip</th>
	<th>Dist</th></tr>
	</thead>
	<tbody id="UserbBody"></tbody>
	</table>
	</div>	
	                        
							
                            </div>
                        </div>
                        <!-- END PORTLET-->
                       
 
				
                            
                            </div>
                        </div>
                        <!-- END PORTLET-->
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
<script type="text/javascript" src="theme/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="theme/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
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
<script src="theme/assets/admin/pages/scripts/index3.js" type="text/javascript"></script>
<script src="theme/assets/admin/pages/scripts/tasks.js" type="text/javascript"></script>
<script src="theme/assets/admin/pages/scripts/table-managed.js"></script>
<script src="theme/assets/global/scripts/datatable.js"></script>
<script src="theme/assets/admin/pages/scripts/table-ajax.js"></script>

<!-- END PAGE PLUGINS & SCRIPTS -->
<script src="theme/assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="theme/assets/admin/layout3/scripts/layout.js" type="text/javascript"></script>
<script src="theme/assets/admin/layout3/scripts/demo.js" type="text/javascript"></script>
<script src="theme/assets/admin/pages/scripts/contact-us.js"></script>
<script src="inc/tweetedjobs-mainTest.js" type="text/javascript"></script>

    <!-- END PAGE LEVEL SCRIPTS -->
<script>
//jQuery(document).ready(function() {    
//   Metronic.init(); // init metronic core components
//Layout.init(); // init current layout
//Demo.init(); // init demo features
//do it, do it do it do it
tj.alex.initializeuserbGrid();
//});
</script>




    <!-- /.modal-dialog -->
</div>
    <!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>