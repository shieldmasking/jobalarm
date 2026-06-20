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

$dbData = Config::get('db')->get_results("select * from account where id=".$account_data['accountId']);
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
  



?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title>JobAlarm | Real Local Jobs</title>
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
<link rel="stylesheet" type="text/css" href="theme/assets/admin/pages/css/todo.css"/>
<!-- END PAGE LEVEL STYLES -->
<!-- BEGIN THEME STYLES -->
<link href="theme/assets/global/css/components-rounded.css" id="style_components" rel="stylesheet" type="text/css">
<link href="theme/assets/global/css/plugins.css" rel="stylesheet" type="text/css">
<link href="theme/assets/admin/layout3/css/layout.css" rel="stylesheet" type="text/css">
<link href="theme/assets/admin/layout3/css/themes/blue-steel.css" rel="stylesheet" type="text/css" id="style_color">
<link href="theme/assets/admin/layout3/css/custom.css" rel="stylesheet" type="text/css">
<!-- END THEME STYLES -->
<link rel="shortcut icon" href="favicon.ico"/>
<style type="text/css">
<!--
.style3 {color: #0099FF}
.style4 {font-size: 20px}
-->
</style>
<style> 
.newspaper {
    -webkit-column-count: 3; /* Chrome, Safari, Opera */
    -moz-column-count: 3; /* Firefox */
    column-count: 3;
    -webkit-column-gap: 40px; /* Chrome, Safari, Opera */
    -moz-column-gap: 40px; /* Firefox */
    column-gap: 40px;
    -webkit-column-rule-style: solid; /* Chrome, Safari, Opera */
    -moz-column-rule-style: solid; /* Firefox */
    column-rule-style: solid;
    -webkit-column-rule-width: 1px; /* Chrome, Safari, Opera */
    -moz-column-rule-width: 1px; /* Firefox */
    column-rule-width: 1px;
    -webkit-column-rule-color: lightblue; /* Chrome, Safari, Opera */
    -moz-column-rule-color: lightblue; /* Firefox */
    column-rule-color: lightblue;
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
				<a href="index.php"><img src="img/logo1.png" longdesc="http://www.jobalarm.com"></a>
			</div>
			<!-- END LOGO -->
			<!-- BEGIN RESPONSIVE MENU TOGGLER -->
			<a href="javascript:;" class="menu-toggler"></a>
			<!-- END RESPONSIVE MENU TOGGLER -->

	  </div>
	</div>
	<!-- END HEADER TOP -->
	<!-- BEGIN HEADER MENU -->
	<div class="page-header-menu">
	  <div class="container">
			<h2 style="float:left;margin:0;padding:10px;color:white"><strong>Find Job-Related FB Groups</strong></h2>
		<!-- BEGIN HEADER SEARCH BOX -->
		<form class="search-form form-inline" role="form" action="<?php echo $_SERVER['PHP_SELF'];?>" method="GET" style="text-align:right;float:right;width:50%;background:none">
		  <!-- <div class="input-group"> -->
       			    <div class="form-group">
						<input type="text" class="form-control" placeholder="Keywords" name="search_keyword" id="search_keyword" value="<?php echo $keywords;?>" />
					</div>
					<div class="form-group">
						<input type="text" class="form-control" placeholder="City, ST" name="search_location" id="search_location" value="<?php echo $location;?>" />
					</div>
					<input type="hidden" name="u" value="<?php echo $username;?>" />
					<input type="hidden" name="i" value="<?php echo $industry;?>" />
		  <div class="form-group">
						<span class="input-group-btn" style="width:42px">
						<a href="javascript:;" class="btn submit" style="color:white">SEARCH</i></a>
						</span>
		  </div>
				<!-- </div> -->
		</form>
			<!-- END HEADER SEARCH BOX -->

	  </div>
	</div>
	<!-- END HEADER MENU -->
</div>
<!-- END HEADER -->
<!-- BEGIN PAGE CONTENT -->
<div class="page-content">
	<div class="container">
		<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->

		<!-- /.modal -->
		<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
		<!-- BEGIN PAGE BREADCRUMB -->
		<ul class="page-breadcrumb breadcrumb">
			<li>
				<a href="index.php">Home</a><i class="fa fa-circle"></i>
			</li>
			<li class="active">
				 Tech Groups
			</li>
		</ul>
		<!-- END PAGE BREADCRUMB -->
		<!-- BEGIN PAGE CONTENT INNER -->
		<div class="portlet light">
			<div class="portlet-body">
				<div class="row">
					<div class="col-md-12">
						<!-- Google Map -->
					  <div class="row margin-bottom-20">
							<div class="col-md-12">
								<div class="space20">								</div>
								<h3 align="center" class="form-section style4"><strong>At <span class="style3">JobAlarm</span>, we are making Social Media Recruiting a reality!!</strong></h3>
								<h5 align="center"><strong>Finding passive candidates through Facebook is easy.....if you know where to go.<br> 
							    Use the search bar above to find Job-Related Groups throughout the U.S.</strong></h5>
								
								
								<h4 align="center">
								  </p>
							    </h4>
								<!-- INSERT CONTACT SUB INFORMATION -->


								
								<div align="center">
								  <p><img src="img/fbgroups2.jpg">							      </p>
								  <h5 align="center"><strong>If you are interested in a tool that integrates your jobs with Twitter & Facebook, maps them to<br> 
						        local job-related facebook groups and tracks your clicks, please check out <a href="http://www.jobalarm.com" target="_blank">JobAlarm.com</a></strong></h5>
								</div>
						</div>
					  <div class="col-md-12 col-sm-12" id="groupManagerCell">
					
					
					
					                     	<div class="portlet light ">
											                       
                            <div class="portlet-title">                                <div class="caption caption-md">                                    <i class="icon-bar-chart theme-font hide"></i>                                    <span class="caption-subject theme-font bold uppercase"><i class="fa fa-facebook"></i> - FB Groups to Join</span></div>

                      <div class="portlet-body" id="groupManagerCellBody">
										
					</div>
									        
                </div>
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
<!-- BEGIN PAGE PLUGINS & SCRIPTS -->
<script type="text/javascript" src="theme/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="theme/assets/global/plugins/select2/select2.min.js"></script>
<script src="theme/assets/admin/pages/scripts/todo.js" type="text/javascript"></script>
<!-- END PAGE PLUGINS & SCRIPTS -->
<script src="http://maps.google.com/maps/api/js?sensor=true" type="text/javascript"></script>
<script src="theme/assets/global/plugins/gmaps/gmaps.min.js" type="text/javascript"></script>
<script src="theme/assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="theme/assets/admin/layout3/scripts/layout.js" type="text/javascript"></script>
<script src="theme/assets/admin/layout3/scripts/demo.js" type="text/javascript"></script>
<script src="theme/assets/admin/pages/scripts/contact-us.js"></script>
<script src="inc/tweetedjobs-mainTest.js" type="text/javascript"></script>
<script>
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core components
Layout.init(); // init current layout
Demo.init(); // init demo features
Todo.init(); // init todo page
tj = {};
tj.selectIndustry = function(id) {
	var base_url = window.location.href.split('?')[0];
	window.location = base_url+"?i="+id+'&k=<?php echo $keywords?>&l=<?php echo $location;?>' ;
}
           ContactUs.init();


});
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