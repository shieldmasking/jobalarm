<?php
// Email Submit
// Note: filter_var() requires PHP >= 5.2.0
if ( isset($_POST['email']) && isset($_POST['name']) && isset($_POST['text']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ) {
 
  // detect & prevent header injections
  $test = "/(content-type|bcc:|cc:|to:)/i";
  foreach ( $_POST as $key => $val ) {
    if ( preg_match( $test, $val ) ) {
	   echo json_encode(array('success'=>false));
      exit;
    }
  }
  $company = (isset($_POST["company"])  && strlen($_POST['company']) > 0) ?  ", ".$_POST['company'] : '';
  //send email
  mail( "info@jobalarm.com", "JobAlarm Contact Form: ".$_POST['name'].$company, $_POST['name']."\r\n\r\n".$_POST['company']."\r\n\r\n".$_POST['email']."\r\n\r\n".$_POST['text'], "From: Jobalarm ContactUs <info@jobalarm.com>"  );
   echo json_encode(array('success'=>true));
   exit();
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
<title>JobAlarm | Job Tweet Search</title>
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
.style1 {
	font-family: "Kristen ITC";
	color: #0099FF;
}
.style4 {
	font-size: 24px;
	font-weight: bold;
}
.style12 {font-weight: bold}
.style13 {font-weight: bold}
.style14 {font-size: 18px}
.style15 {font-weight: bold}
.style16 {font-family: "Kristen ITC"; color: #0099FF; font-size: 18px; }
.style17 {font-size: 18px; color: #0099FF;}
.style19 {font-family: "Kristen ITC"; color: #0099FF; font-weight: bold; }
.style21 {font-size: 18px; font-weight: bold; }
.style22 {color: #0099FF}
-->
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
				<a href="index.php"><img src="img/logo1.png" longdesc="http://www.jobalarm.com"></a>			</div>
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
			<h1 style="width:320px;float:left;margin:0;padding:5px;color:white">Adding Jobs </h1>
			<!-- BEGIN HEADER SEARCH BOX -->

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
		
		<div class="container">
		
  <div align="center">
      <span class="style15 style4">Make your jobs searchable on <span class="style13 style1">Job Alarm</span> in just one step:</span>
    <span class="style12"><br>
    <br>
    </span><span class="style21"><span class="style1">Step 1:</span> Include <span class="style22">#jobs</span> AND the <span class="style22">City, ST</span>
    (ie. Fremont, NE) <br /> when you post a job tweet on Twitter™. </span>    
    <p class="style16"><strong>THAT'S IT!!     
    </strong>
    <p class="style14"><strong>Your job is now searchable on <span class="style16 style1">JobAlarm.com</span> for millions of Job Seekers.</strong> 
       
    <p class="style14"><span class="style19">Want More?</span>     
    <p class="style14"><strong>Include an industry (ie. <span class="style17">#Healthcare</span>) to enhance your job's searchability.</strong>    
    <br><br>
    <p class="style14"><strong>We are also the <span class="style22">ONLY</span> app that can  match your job to <span class="style22">Local<br>
  Job-Related Facebook Groups</span> and post to them!</strong>    
    <div class="container">
	<iframe title="YouTube video player" class="youtube-player" type="text/html" 
width="420" height="315" src="http://www.youtube.com/embed/8oRr8Q1ZOFs"
frameborder="0" allowFullScreen></iframe></div>
	
      <!-- <p class="style14"><span class="style1">Also,</span> Include a Job Type to help Job Seekers find your position quickly.
	<div align=left>
	<ul>
    <ul>
      <ul>
        <ul>
          <ul>
            <ul>
              <li>For Full Time:  #fulltime or #ft or #perm</a></li>
              <li>For Part Time:  #parttime or #pt</a></li>
              <li>For Temp:  #temp</a></li>
              <li>For Temp to Perm: #temptoperm or #temp2perm or #t2p</a></li>
              <li>For Contract:  #contract</a></li>
              <li>For 1099:  #1099</a></li>
            </ul>
            </ul>
          </ul>
        </ul>
      </ul>
    </ul> 
	</div> -->   
    <p class="style14">
    
    <p class="style12"><a href="JavaScript:window.close()">Close</a>  </div>
</div>
		<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
		<!-- BEGIN PAGE BREADCRUMB -->
		<ul class="page-breadcrumb breadcrumb">
			<li>
				<a href="index.php">Home</a><i class="fa fa-circle"></i>
			</li>
			<li class="active">
				 <a href="contactphp">Contact Us</a><i class="fa fa-circle"></i>
				 			</li>
			<li class="active">
				 <a href="login.php">Employer Login</a><i class="fa fa-circle"></i>
				 			</li>
		</ul>
		<!-- END PAGE BREADCRUMB -->
		<!-- BEGIN PAGE CONTENT INNER -->
		
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