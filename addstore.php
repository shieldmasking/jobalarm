<?php
session_start();
ini_set('display_errors',1);
include_once 'inc/class.db.php';
include_once 'inc/class.jatwitter.php';
include_once 'inc/config.php';

if (!isset($_SESSION['account'])) {
    header('location: login.php');
}

$account_data = $_SESSION['account'];
if (!isset($account_data['accountId'])) {
	header('location: login.php');
	exit();
}

if (isset($_REQUEST['addstore'])) {
    $accountId = $account_data['accountId'];
    $brand = isset($_REQUEST['brand']) ? $_REQUEST['brand'] : '';
	$storeNum = isset($_REQUEST['storeNum']) ? $_REQUEST['storeNum'] : '';
	$address= isset($_REQUEST['address']) ? $_REQUEST['address'] : '';
	$city = isset($_REQUEST['city']) ? $_REQUEST['city'] : '';
    $st = isset($_REQUEST['state']) ? $_REQUEST['state'] : '';
    $zip_code = isset($_REQUEST['zipcode']) ? $_REQUEST['zipcode'] : '';
	$zip_code = substr($zip_code,0,5);
	$user= isset($_REQUEST['assign']) ? $_REQUEST['assign'] : '0';
	$id="";
	
	
	if ($brand) {

	Config::get('db')->query("insert into sms_stores (accountId,brandId,storeNum,address,city,st,zip,userId) values({$accountId},{$brand},'{$storeNum}','{$address}','{$city}','{$st}','{$zip_code}',{$user}) on duplicate key update storeNum='{$storeNum}'");

    $max = Config::get('db')->get_results("SELECT MAX(id) AS `maxid` FROM `sms_stores`");
    $id = $max[0]['maxid'];
		
	$query = "SELECT s.*, u.id as brandUrl FROM `sms_jobs` s LEFT JOIN `accountUrls` as u on u.brandId = s.brandId where s.brandId =" . $brand;

	$dbJobs = Config::get('db')->get_results($query);
	

	foreach($dbJobs as $j) {
	
	$jobid = $j['id'];
	$brandUrl = $j['brandUrl'];
	
	
	Config::get('db')->query("insert into `sms_posts` (jobId,storeId,url) values({$jobid},{$id},{$brandUrl}) on duplicate key update storeId={$id}");

	}
	}
	
	if ($_POST['confirm'] == 1) {
	  echo "<script>window.close();</script>";
	}
}

	$query = "SELECT s.*, b.storeBrand from `sms_stores` s LEFT JOIN `sms_brand` b on b.id = s.brandId WHERE s.accountId =" . $account_data['accountId'] . " GROUP BY s.brandId";
	
	$dbData = Config::get('db')->get_results($query);

	echo "var brands = ".json_encode($dbData);
	
	$user = "SELECT * FROM `users` where accountId =" . $account_data['accountId'] . " ORDER BY last_name ASC";
	
	$dbUser = Config::get('db')->get_results($user);
	
	




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
<title>JobAlarm | Contact Us</title>
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
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<!-- DOC: Apply "page-header-menu-fixed" class to set the mega menu fixed  -->
<!-- DOC: Apply "page-header-top-fixed" class to set the top menu fixed  -->
<body class="page-boxed page-header-fixed page-container-bg-solid page-sidebar-closed-hide-logo page-sidebar-closed">
<!-- BEGIN TOP BAR -->
    <div class="pre-header">
        <div class="container">
            <div class="row">
                <!-- BEGIN TOP BAR LEFT PART -->
                
                <!-- END TOP BAR LEFT PART -->
                <!-- BEGIN TOP BAR MENU -->
                
                <!-- END TOP BAR MENU -->
            </div>
        </div>        
    </div>
    <!-- END TOP BAR -->
<!-- BEGIN HEADER -->
<div class="page-header" style="height: 69px">
	<!-- BEGIN HEADER TOP -->
	
		<div class="container">
			<!-- BEGIN LOGO -->
			
			<div class="page-logo" align="center">
				<img src="img/logo1.png"></div>
			
				
			<!-- END LOGO -->
			<!-- BEGIN RESPONSIVE MENU TOGGLER -->
			
			<!-- END RESPONSIVE MENU TOGGLER -->

		</div>
	
	<!-- END HEADER TOP -->

<!-- BEGIN HEADER MENU -->
        
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
				<!-- END PAGE BREADCRUMB -->
		<!-- BEGIN PAGE CONTENT INNER -->
		<div class="portlet light">
			<div class="portlet-body">
				<div class="row">
					<div class="col-md-12" style="left: 0px; top: 0px">
						<!-- Google Map -->
						<div class="row margin-bottom-20">
						  <div class="col-md-6">
								<div class="space20">
								</div>
								<!-- BEGIN FORM-->
								<form action="addstore.php" id="addstore" name="addstore" method="post" onsubmit="return confirm('Would you like to add another store?')">
								<input type="hidden" name="addstore" value="1" />
								
								
									<h3 class="form-section" style="text-align: center"><strong>Add a Store </strong></h3>
									
									
									
								<div class="form-group">									
								<select id="brand" name="brand" type="select" class="form-control" required />
								<option>Select a Brand:</option>
								
								<?php 
								foreach($dbData as $m) 
								{ 
								?>
								<option value="<?php echo $m['brandId'];?>"><?php echo $m['storeBrand'];?></option>
								<?php 
								} 
								?>
								</select>								
								</div>									
									
									
									
									<div class="form-group">
										<div class="input-icon">
											<i class="fa fa-user"></i>
											<input id="storeNum" name="storeNum" type="text" class="form-control" placeholder="Store Number" required />
										</div>
									</div>
									<div class="form-group">
										<div class="input-icon">
											<i class="fa fa-user"></i>
											<input id="address" name="address" type="text" class="form-control" placeholder="Address" required />
										</div>
									</div>
									<div class="form-group">
										<div class="input-icon">
											<i class="fa fa-user"></i>
											<input id="city" name="city" type="text" class="form-control" placeholder="City" maxlength="25" required />
										</div>
									</div>
									<div class="form-group">
										<div class="input-icon">
											<i class="fa fa-envelope"></i>
											<input id="state" name="state" type="text" class="form-control" placeholder="ST" maxlength="2" required />
										</div>
									</div>
									<div class="form-group">
									<div class="input-icon">
									<i class="fa fa-briefcase"></i>
										<input id="zipcode" name="zipcode" type="text" class="form-control" placeholder="Zip Code" maxlength="5" required />
							
									 
									</div>
									</div>
									
									<div class="form-group">									
								<select id="assign" name="assign" type="select" class="form-control">
								<option value="0">Assign a User:</option>
								
								<?php 
								foreach($dbUser as $u) 
								{ 
								?>
								<option value="<?php echo $u['id'];?>"><?php echo $u['last_name'],$u['first_name'];?></option>
								<?php 
								} 
								?>
								</select>									</div>							

										
								   <div class="form-group">
								   <input type="submit" value="Add"/> 
								   </div> 
								</form>
								
								<!-- END FORM-->
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
<script src="theme/assets/global/plugins/gmaps/gmaps.min.js" type="text/javascript"></script>
<script src="theme/assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="theme/assets/admin/layout3/scripts/layout.js" type="text/javascript"></script>
<script src="theme/assets/admin/layout3/scripts/demo.js" type="text/javascript"></script>
<script src="theme/assets/admin/pages/scripts/contact-us.js"></script>
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