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


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>JobAlarm | FB Groups Admin</title>
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
<body class="page-boxed page-header-fixed page-container-bg-solid page-sidebar-closed-hide-logo page-sidebar-closed"><script>  function statusChangeCallback(response) {    if (response.status === 'connected') {      testAPI();    } else if (response.status === 'not_authorized') {      document.getElementById('status').innerHTML = 'Please log ' +        'into this app.';    } else {      document.getElementById('status').innerHTML = 'Please log ' +        'into Facebook.';    }  }  function checkLoginState() {    FB.getLoginStatus(function(response) {      statusChangeCallback(response);    });  }   window.fbAsyncInit = function() {    FB.init({      appId      : '1051341108212143',      xfbml      : true,      version    : 'v2.3'    });  FB.getLoginStatus(function(response) {    statusChangeCallback(response);  });  };  (function(d, s, id) {    var js, fjs = d.getElementsByTagName(s)[0];    if (d.getElementById(id)) return;    js = d.createElement(s); js.id = id;    js.src = "//connect.facebook.net/en_US/sdk.js";    fjs.parentNode.insertBefore(js, fjs);  }(document, 'script', 'facebook-jssdk'));  function testAPI() {    FB.api('/me', function(response) {      document.getElementById('status').innerHTML =        'Thanks for logging in, ' + response.name + '!';        tj.alex.getGroups(true);    });  }</script><!--  Below we include the Login Button social plugin. This button uses  the JavaScript SDK to present a graphical Login button that triggers  the FB.login() function when clicked.-->
<script>
FB.api('/me/groups', function(response) {
	  var l = response.data.length;
	  for (i = 0; i < l; i++) {
	    var obj = response.data[i];
	    var groupName = obj.name;
	    var groupId = obj.id;
	  }
	});</script>
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
                </div>                                <div class="pull-right">                    <fb:login-button scope="public_profile,email,publish_actions,user_groups, publish_pages" onlogin="checkLoginState();" id="faceBookLoginButton">					</fb:login-button>														<div id="status">					</div>				</div>
                <!-- END LOGO -->
                <!-- BEGIN RESPONSIVE MENU TOGGLER -->

                <!-- END RESPONSIVE MENU TOGGLER -->
            </div>
        </div>
    </div>
    <div class="page-container">
        <!-- BEGIN PAGE CONTENT -->
        <div class="page-content">
            <div class="container">
                <!-- BEGIN PAGE CONTENT INNER -->
                <div class="row margin-top-10">
                    <div class="col-md-6 col-xs-12" id="leftSection">
                             
                    </div>                    
                    <div class="col-md-6 col-xs-12" id="rightSection">
    
                    </div>
                </div>
                
                
        </div>
        <!-- END PAGE CONTENT -->
    </div>
    <!-- END PAGE CONTAINER --></div>
    <!-- BEGIN FOOTER -->
    <div class="page-footer">
        <div class="container">
            2015 &copy; TweetedJobs. All Rights Reserved.
        </div>
    </div>
    <div class="scroll-to-top">
        <i class="icon-arrow-up"></i>
    </div>
    <!-- END FOOTER -->                <div id="tweetedModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">	  <div class="modal-dialog">	    <div class="modal-content">	      <div class="modal-header">	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>	        <h4 class="modal-title" id="tweetedModalTitle">Modal title</h4>	      </div>	      <div id="tweetedModalBody" class="modal-body">	        ...	      </div>	      <div class="modal-footer">	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>	        <button id="tweetedModalSaveButton" type="button" class="btn btn-primary">Save changes</button>	      </div>	    </div>	  </div></div>
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
    <script src="inc/tweetedjobsGroupAdmin.js" type="text/javascript"></script>
    <!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>