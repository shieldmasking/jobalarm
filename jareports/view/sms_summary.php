<?php
session_start();
ini_set('display_errors',1);

?>
<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>JobAlarm | Dashboard</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />

    <link href="../../theme/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="../../theme/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
    <link href="../../theme/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../../theme/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE STYLES -->
    <link href="../../theme/assets/admin/pages/css/tasks.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="../../theme/assets/admin/pages/css/todo.css" />
    <link rel="stylesheet" type="text/css" href="../../theme/assets/global/plugins/select2/select2.css"/>
    <link rel="stylesheet" type="text/css" href="../../theme/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
    <link rel="stylesheet" type="text/css" href="../../theme/assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
    <link rel="stylesheet" type="text/css" href="../../theme/assets/global/plugins/jquery-nestable/jquery.nestable.css"/>
    <!-- END PAGE STYLES -->
    <!-- BEGIN THEME STYLES -->
    <link href="../../theme/assets/global/css/components-rounded.css" id="style_components" rel="stylesheet" type="text/css" />
    <link href="../../theme/assets/global/css/plugins.css" rel="stylesheet" type="text/css" />
    <link href="../../theme/assets/admin/layout3/css/layout.css" rel="stylesheet" type="text/css" />
    <link href="../../theme/assets/admin/layout3/css/themes/blue-steel.css" rel="stylesheet" type="text/css" id="style_color" />
    <link href="../../theme/assets/admin/layout3/css/custom.css" rel="stylesheet" type="text/css" />
    <!-- END THEME STYLES -->
    <link rel="shortcut icon" href="../../favicon.ico" />
	<style type="text/css">
	#datatableCompx_ajax_length,
	#datatableCompx_ajax_filter
	{
	display:none;
	}
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
		
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<style type="text/css">
${demo.css}
		.auto-style1 {
			margin-bottom: 24px;
		}
		</style>
		

		





</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<!-- DOC: Apply "page-header-menu-fixed" class to set the mega menu fixed  -->
<!-- DOC: Apply "page-header-top-fixed" class to set the top menu fixed  -->
<body class="page-boxed page-header-fixed page-container-bg-solid page-sidebar-closed-hide-logo page-sidebar-closed">

     <!--  Below we include the Login Button social plugin. This button uses  the JavaScript SDK to present a graphical Login button that triggers  the FB.login() function when clicked.-->
    <!-- BEGIN HEADER -->
    <div class="page-header">
        <!-- BEGIN HEADER TOP -->
        <div class="page-header-top">
            <div class="container">
                <!-- BEGIN LOGO -->
                <div class="page-logo">
                    <a href="/index.php">
                        &nbsp;</a></div>
                <!-- END LOGO -->
                <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                <a href="javascript:;" class="menu-toggler"></a>
                <!-- END RESPONSIVE MENU TOGGLER -->
                    <a href="/index.php">
                        <img src="../../img/logo1.png" longdesc="http://www.jobalarm.com"></a></div>
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
                            <a href="../../dashboard/">Dashboard</a>
                        </li>
                        <li class="menu-dropdown classic-menu-dropdown ">
                            <a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown" href="javascript:;">
                                Tools
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu pull-left">
                                <li class=" ">
                                    <a href="/account_settings.php">
                                        <i class="icon-settings"></i>
                                        Account Settings
                                    </a>
                                </li>

								<li class=" ">
                                    <a href="/dashboard.php">
                                        <i class="fa fa-question"></i>
                                        Support
                                    </a>
                                </li>

                            </ul>
                        </li>
                    </ul>
                </div>
                <a class="btn blue pull-right margin-top-10" href="/logout.php"><i class="fa fa-sign-out"></i>Sign Out</a>
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
                <div id="inputsContainer" class="row margin-top-10">
                    <div  class="col-md-3 col-xs-6">
                       <div class="btn-group" role="group" aria-label="...">
                          <button type="button" class="btn btn-default">Bar Chart </button>
                          <button type="button" class="btn btn-default">Line Chart</button>
                        </div>
                    </div>

                    <div  class="col-md-3 col-xs-6">
                       <div class="btn-group" role="group" aria-label="...">
                          <button id="chartDays7Button"type="button" class="btn btn-default">7 Days </button>
                          <button id="chartDays30Button"type="button" class="btn btn-default">30 Days</button>
                           <button id="chartDays90Button"type="button" class="btn btn-default">90 Days</button>
                        </div>
                    </div>
					
					 <div  class="col-md-3 col-xs-6">
                       <div class="dropdown">
							  <button class="btn btn-default dropdown-toggle" type="button" id="brandComboButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
								All
								<span class="caret"></span>
							  </button>
							  <ul class="dropdown-menu" aria-labelledby="brandComboWrapper" id="brandComboWrapper">
								
							  </ul>
							</div>
                    </div>
            </div>
                
				<div class="row margin-top-10">
				
				<div class="col-md-6 col-sm-6" id="incomingChartCell" style="left: 0px; top: 0px">
		         	<div class="portlet light ">
				         <div class="portlet-title">
						       <div class="caption caption-md">
							   <i class="icon-bar-chart theme-font"></i>
							    <span class="caption-subject theme-font bold uppercase"> - Incoming Messages</span></div> 
								</div>
								
								      
								     <div class="portlet-body" id="incomingChartContainer" style="width: 400px; height: 300px; margin: 0 auto">
								     
								     </div>
								     								     

								     </div>

								<!--</div>-->									        
                
			</div>
			
			<div class="col-md-6 col-sm-6" id="outgoingChartCell" style="left: 0px; top: 0px">
		         	<div class="portlet light ">
				         <div class="portlet-title">
						       <div class="caption caption-md">
							   <i class="icon-bar-chart theme-font"></i>
							    <span class="caption-subject theme-font bold uppercase"> - Outgoing Messages</span></div> 
								</div>
								
								      
								     <div class="portlet-body" id="outgoingChartContainer" style="width: 400px; height: 300px; margin: 0 auto">
								     
								     </div>
								     								     

								     </div>

								<!--</div>-->									        
                
			</div>
			
			
					
                                                       
					
					 
     </div>
				</div>

                <!-- END PAGE CONTENT INNER -->
            </div>
            </div>
        
        <!-- END PAGE CONTENT -->
    
    <!-- END PAGE CONTAINER -->                    



  <!-- end bump modal code -->
    <!-- BEGIN FOOTER -->
    <div class="page-footer">
        <div class="container">
            2015 &copy; Premier SSG, Inc. All Rights Reserved.
        </div>
    </div>
    <div class="scroll-to-top">
        <i class="icon-arrow-up"></i>

    </div>





    <!-- /.modal-dialog -->
</div>

    <!-- END FOOTER -->
    <!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
    <!-- BEGIN CORE PLUGINS -->
    <!--[if lt IE 9]>
<script src="theme/assets/global/plugins/respond.min.js"></script>
<script src="theme/assets/global/plugins/excanvas.min.js"></script>
<![endif]-->
    <script src="../../theme/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
    <script src="../../theme/assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
    <!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
    <script src="../../theme/assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
    <script src="../../theme/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="../../theme/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
    <script src="../../theme/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <script src="../../theme/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
    <script src="../../theme/assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
    <script src="../../theme/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
    <!-- END CORE PLUGINS -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="../../theme/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
	<script src="https://code.highcharts.com/modules/exporting.js"></script>
    <!-- IMPORTANT! fullcalendar depends on jquery-ui.min.js for drag & drop support -->
    <script src="../../theme/assets/global/plugins/jquery-nestable/jquery.nestable.js"></script>
    <script type="text/javascript" src="../../theme/assets/global/plugins/select2/select2.min.js"></script>
    <script type="text/javascript" src="../../theme/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="../../theme/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
    <script type="text/javascript" src="../../theme/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <script type="text/javascript" src="../../theme/assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>
    <script type="text/javascript" src="../../theme/assets/global/plugins/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="../../theme/assets/global/plugins/bootstrap-markdown/js/bootstrap-markdown.js"></script>
    <script type="text/javascript" src="../../theme/assets/global/plugins/bootstrap-markdown/lib/markdown.js"></script>
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="../../theme/assets/admin/pages/scripts/ui-nestable.js"></script>
    <script src="../../theme/assets/global/plugins/bootbox/bootbox.min.js" type="text/javascript"></script>

    <script src="../../theme/assets/global/scripts/metronic.js" type="text/javascript"></script>
    <script src="../../theme/assets/admin/layout3/scripts/layout.js" type="text/javascript"></script>
    <script src="../../theme/assets/admin/layout3/scripts/demo.js" type="text/javascript"></script>
    <script src="../../theme/assets/admin/pages/scripts/index3.js" type="text/javascript"></script>
    <script src="../../theme/assets/admin/pages/scripts/tasks.js" type="text/javascript"></script>
    <script src="../../theme/assets/admin/pages/scripts/table-managed.js"></script>
    <script src="../../theme/assets/global/scripts/datatable.js"></script>
    <script src="../../theme/assets/admin/pages/scripts/table-ajax.js"></script>
    <!-- END PAGE LEVEL SCRIPTS -->
   
      <script src="script/jaReports.js" type="text/javascript"></script>
      <script type="text/javascript">
        var logInToSMS = function() {

            // $.ajax({
            //     url:'http://admin.jobalarm.com/login/smslogin/<?php echo $accountId?>',
            //     method:'POST',
            //     dataType:'json',
            //     crossDomain:true,
            //     success: function(data) {
            //         if (data.status == 'success') {
            //             window.location='http://admin.jobalarm.com/globals';
            //         }
            //     }
            // });

        }
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