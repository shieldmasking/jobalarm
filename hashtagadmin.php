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


$hashTagList = array();

$hashtagData = Config::get('db')->get_results('
    SELECT lower(hashTag) as hashTag,count from category order by count desc limit 0,500
');
foreach($hashtagData as $hashtag) {
    $hashTagList[$hashtag['hashTag']] = array('hashtag'=>$hashtag['hashTag'],'count'=>$hashtag['count'],'selected'=>0);
}

$dbData = Config::get('db')->get_results('select lower(hashtag) as hashtag from streamtag where active>0');

foreach($dbData as $hashtag) {
    if (isset($hashTagList[$hashtag['hashtag']])) {
        $hashTagList[$hashtag['hashtag']]['selected'] = 1;
    } else {
        $query = "select count from category where hashTag='{$hashtag['hashtag']}'";
        $subData = Config::get('db')->get_results($query);
        $hashTagList[$hashtag['hashtag']] = array('hashtag'=>$hashtag['hashtag'],'count'=>(count($subData) > 0 ? $subData[0]['count'] : 0),'selected'=>1);
    }
}

function hashtagsorter($a,$b) {
    return $b['count'] - $a['count'];
}
usort($hashTagList,hashtagsorter);

//var_dump($hashTagList);
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
                <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                <a href="javascript:;" class="menu-toggler"></a>
                <!-- END RESPONSIVE MENU TOGGLER -->

            </div>
        </div>
        <!-- END HEADER TOP -->
        <!-- BEGIN HEADER MENU -->
        <div class="page-header-menu" style="background-color:#444d58">
            <div class="container">
                <h1 style="width:340px;float:left;margin:0;padding:5px;color:white">Manage Hashtags</h1>
                <!-- BEGIN MEGA MENU -->
                <!-- DOC: Apply "hor-menu-light" class after the "hor-menu" class below to have a horizontal menu with white background -->
                <!-- DOC: Remove data-hover="dropdown" and data-close-others="true" attributes below to disable the dropdown opening on mouse hover -->
                <div class="hor-menu">
                    <ul class="nav navbar-nav">
                        <li>
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
                        <li class="menu-dropdown classic-menu-dropdown active">
                            <a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown" href="javascript:;">
                                Administration
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu pull-left">
                                <li class="active">
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
                            </ul>
                        </li>
                        <?php endif; ?>                        
                    </ul>
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
			    <div class="modal fade" id="add_campaign" tabindex="-1" role="dialog" aria-hidden="true">
				    <div class="modal-dialog">
					    <div class="modal-content">
						    <div class="modal-header">
							    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
							    <h4 class="modal-title">Add Campaign</h4>
						    </div>
						    <div class="modal-body">
							     <form action="javascript:;" id="add_campaign_form" class="form-horizontal form-row-seperated">
									<div class="form-body">
												
										<div class="form-group">
											<label class="col-md-4 control-label">Campaign Name <span class="required" aria-required="true"> * </span></label>
											<div class="col-md-5">
												<input class="form-control" id="campaign_name" name="campaign_name" placeholder="" type="text" />
                                                <span id="error_campaign_name"></span>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label">Start/End Date <span class="required" aria-required="true"> * </span></label>
											<div class="col-md-5">
											    <div class="input-group input-large date-picker input-daterange" data-date-format="mm/dd/yyyy">
												    <input type="text" class="form-control" id="campaign_from" name="campaign_from" />
												    <span class="input-group-addon"> to </span>
												    <input type="text" class="form-control" id="campaign_to" name="campaign_to" />
											    </div>                    
                                                 <span id="error_campaign_from"></span>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label">Per-Click Budget <span class="required" aria-required="true"> * </span></label>
											<div class="col-md-5">
                                                <div class="input-group">
											        <span class="input-group-addon">
											        <i class="fa fa-dollar"></i>
											        </span>
												    <input class="form-control" id="campaign_budget_click" name="campaign_budget_click" placeholder="" type="text" />
                                                </div>
                                                <span id="error_campaign_budget_click"></span>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label">Daily Budget <span class="required" aria-required="true"> * </span></label>
											<div class="col-md-5">
                                                <div class="input-group">
											        <span class="input-group-addon">
											        <i class="fa fa-dollar"></i>
											        </span>
												    <input class="form-control" id="campaign_budget_daily" name="campaign_budget_daily" placeholder="" type="text" />
                                                </div>
                                                <span id="error_campaign_budget_daily"></span>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label">Total Budget <span class="required" aria-required="true"> * </span></label>
											<div class="col-md-5">
                                                <div class="input-group">
											        <span class="input-group-addon">
											        <i class="fa fa-dollar"></i>
											        </span>
    												<input class="form-control" id="campaign_budget_total" name="campaign_budget_total" placeholder="" type="text" />
                                                </div>
                                                <span id="error_campaign_budget_total"></span>
											</div>
										</div>
											
                                    </div>
                                  </form> 
						    </div>
						    <div class="modal-footer">
							    <button id="save_campaign" type="button" class="btn blue">Save Campaign</button>
							    <button type="button" class="btn default" data-dismiss="modal">Cancel</button>
						    </div>
					    </div>
					    <!-- /.modal-content -->
				    </div>

                </div>
			    <div class="modal fade" id="edit_campaign" tabindex="-1" role="dialog" aria-hidden="true">
				    <div class="modal-dialog modal-lg">
					    <div class="modal-content">
						    <div class="modal-header">
							    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
							    <h4 class="modal-title">Edit Campaign</h4>
						    </div>
						    <div class="modal-body">
							     <form action="javascript:;" id="edit_campaign_form" class="form-horizontal form-row-seperated">
                                    <input type="hidden" id="edit_campaign_jobs" name="edit_campaign_jobs" />
									<div class="form-body">
												
										<div class="form-group">
											<label class="col-md-4 control-label">Campaign Name <span class="required" aria-required="true"> * </span></label>
											<div class="col-md-5">
												<input class="form-control" id="edit_campaign_name" name="edit_campaign_name" placeholder="" type="text" />
                                                <span id="error_edit_campaign_name"></span>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label">Start/End Date <span class="required" aria-required="true"> * </span></label>
											<div class="col-md-5">
											    <div class="input-group input-large date-picker input-daterange" data-date-format="mm/dd/yyyy">
												    <input type="text" class="form-control" id="edit_campaign_from" name="edit_campaign_from" />
												    <span class="input-group-addon"> to </span>
												    <input type="text" class="form-control" id="edit_campaign_to" name="edit_campaign_to" />
											    </div>                    
                                                 <span id="error_edit_campaign_from"></span>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label">Per-Click Budget <span class="required" aria-required="true"> * </span></label>
											<div class="col-md-5">
                                                <div class="input-group">
											        <span class="input-group-addon">
											        <i class="fa fa-dollar"></i>
											        </span>
												    <input class="form-control" id="edit_campaign_budget_click" name="edit_campaign_budget_click" placeholder="" type="text" />
                                                </div>
                                                <span id="error_edit_campaign_budget_click"></span>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label">Daily Budget <span class="required" aria-required="true"> * </span></label>
											<div class="col-md-5">
                                                <div class="input-group">
											        <span class="input-group-addon">
											        <i class="fa fa-dollar"></i>
											        </span>
												    <input class="form-control" id="edit_campaign_budget_daily" name="edit_campaign_budget_daily" placeholder="" type="text" />
                                                </div>
                                                <span id="error_edit_campaign_budget_daily"></span>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label">Total Budget <span class="required" aria-required="true"> * </span></label>
											<div class="col-md-5">
                                                <div class="input-group">
											        <span class="input-group-addon">
											        <i class="fa fa-dollar"></i>
											        </span>
    												<input class="form-control" id="edit_campaign_budget_total" name="edit_campaign_budget_total" placeholder="" type="text" />
                                                </div>
                                                <span id="error_edit_campaign_budget_total"></span>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label">Notes </label>
											<div class="col-md-5">
                                                <div class="input-group">
                                                    <textarea class="form-control" id="edit_campaign_notes" name="edit_campaign_notes"></textarea>
                                                </div>
                                                <span id="error_edit_campaign_notes"></span>
											</div>
										</div>
											
                                    </div>
                                  </form> 
                                  <div class="alert alert-info" style="line-height:16px;padding:5px;margin-bottom:5px">
								        <strong>Instructions</strong> To add/remove jobs to/from this campaign, drag them between the boxes below.
							        </div>
                                    <div class="row">
						                <div class="col-md-6">
							                <div class="portlet box blue">
								                <div class="portlet-title">
									                <div class="caption">
										                <i class="fa fa-list"></i>Campaign Featured Jobs
									                </div>
								                </div>
								                <div class="portlet-body ">
									                <div class="dd" id="nestable_list_1">
                                                        <div class="dd-empty"></div>
									                </div>
								                </div>
							                </div>
						                </div>
						                <div class="col-md-6">
							                <div class="portlet box green">
								                <div class="portlet-title">
									                <div class="caption">
										                <i class="fa fa-list"></i>Your Job Tweets
									                </div>
								                </div>
								                <div class="portlet-body">
									                <div class="dd" id="nestable_list_2">
										                <ol class="dd-list">
											                <li class="dd-item dd3-item" data-id="13">
												                <div class="dd-handle dd3-handle">
												                </div>
                                                                <div class="dd3-content">Job 13<br />Details</div>
											                </li>
											                <li class="dd-item dd3-item" data-id="11">
												                <div class="dd-handle dd3-handle">
												                </div>
                                                                <div class="dd3-content">Job 11<br />Details</div>
											                </li>
										                </ol>	
									                </div>
								                </div>
							                </div>
						                </div>
					                </div>                                  
						    </div>
						    <div class="modal-footer">
							    <button id="save_changes" type="button" class="btn blue">Save Changes</button>
							    <button type="button" class="btn default" data-dismiss="modal">Cancel</button>
						    </div>
					    </div>
					    <!-- /.modal-content -->
				    </div>

                </div>
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
                    <h1>
                        Welcome, <?php echo $account_data['fullName']; ?>
                        <span style="font-size:15px;">
                            (@<?php echo $account_data['twitter_handle']; ?>)
                        </span>
                    </h1>
                </div>
                <div class="row margin-top-10">
                    <div class="col-md-12 col-sm-12">
                        <!-- BEGIN PORTLET-->
                        <div class="portlet light ">
                            <div class="portlet-title">
                                <div class="caption caption-md">
                                    <i class="icon-bar-chart theme-font hide"></i>
                                    <span class="caption-subject theme-font bold uppercase">Twitter Feed Hashtags</span>
                                </div>
                            </div>
                            <div class="portlet-body" id="hastageditbox">
                              <select multiple="multiple" size="10" name="hashtageditor" id="hashtageditor">
                              <?php
                              foreach($hashTagList as $hashtag):
                              ?>                                
                                <option value="<?php echo $hashtag['hashtag']; ?>"<?php echo ($hashtag['selected'] > 0) ? 'selected="selected"' : '' ?>><?php echo $hashtag['hashtag'].' ('.$hashtag['count'].')'; ?></option>
                              <?php
                              endforeach;
                              ?>
                              </select>
                              <br />
                              <button id="save_hashtags_btn" class="btn blue btn-default btn-block">Save</button>
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
    <script type="text/javascript" src="theme/assets/global/plugins/jquery.bootstrap-duallistbox.js"></script>
    <!-- END PAGE LEVEL SCRIPTS -->
    <script src="inc/tweetedjobs-admin-hashtags.js" type="text/javascript"></script>
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