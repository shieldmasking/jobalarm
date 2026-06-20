<?php
// Email Submit
// Note: filter_var() requires PHP >= 5.2.0
include_once("class.phpmailer.php");
  if($_REQUEST[method]=='email'){
      ini_set("SMTP","mail.jobalarm.com");
	  ini_set("sendmail_from","giveaway@jobalarm.com");
	  $name = $_REQUEST[contact_name];
	  $email_addr = $_REQUEST[contact_email];
	  $comp = $_REQUEST[u];
	  $comment = $_REQUEST[contact_feedback];
	  $message = "Name: ".$name."<br>fb URL: ".$comp."<br>Email: ".$email_addr."<br>Comment: ".$comment; 
      if(mail('rstrenger@jobalarm.com','Pinned Post Contest Entry',$message,"From: giveaway@jobalarm\nX-Mailer: PHP4.x\n"."MIME-Version:1.0\n"."Content-type: text/html; charset=iso-8859-1")){
	    echo "<script> alert('Your entry has been submitted for review.  Thank you!');</script>";
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
			<h1 style="width: 600px;float:left;margin:0;padding:5px;color:white">Tech Groups on Facebook</h1>
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
								<h5 align="center"><strong>Below is a list of Technology Job Related Facebook Groups where you can post and share your<br> 
							    IT jobs.  Just follow each link to the Facebook Group to join and please share with others.</strong></h5>
								
								
								<h4 align="center">
								  </p>
							    </h4>
								<!-- INSERT CONTACT SUB INFORMATION -->


								
								<div align="center">
								  <p><img src="img/fbgroups2.jpg">							      </p>
								  <h5 align="center"><strong>If you are interested in a tool that integrates your jobs with Twitter & Facebook, maps them to<br> 
						        local job-related facebook groups and tracks your clicks, please check out <a href="http://www.jobalarm.com" target="_blank">JobAlarm.com</a></strong></h5>
								</div>
								
								<div class="well" align="center">
									<h4><strong>Facebook Groups for posting Tech Jobs:</strong></h4>
									<p>&nbsp;</p>
<div class="newspaper">
										  <address class="margin-bottom-20"><a href="https://www.facebook.com/groups/792130244247000" target="_blank">Atlanta Tech Jobs</a></address>
										  <address class="margin-bottom-20"><a href="https://www.facebook.com/groups/537493346398426" target="_blank">Austin Tech Jobs</a></address>
<address class="margin-bottom-20"><a href="https://www.facebook.com/groups/1064281540259018" target="_blank">Baltimore Tech Jobs</a></address>
<address class="margin-bottom-20"><a href="https://www.facebook.com/groups/321200651337491" target="_blank">Bay Area Tech Jobs</a></address>
<address class="margin-bottom-20"><a href="https://www.facebook.com/groups/1084673714906591" target="_blank">Boston Tech Jobs</a></address>
<address class="margin-bottom-20"><a href="https://www.facebook.com/groups/935873786507350" target="_blank">Charlotte Tech Jobs</a></address>
<address class="margin-bottom-20"><a href="https://www.facebook.com/groups/510552892455187" target="_blank">Chicago Tech Jobs</a></address>
<address class="margin-bottom-20"><a href="https://www.facebook.com/groups/1637904203138866" target="_blank">Cincy Tech Jobs</a></address>
<address class="margin-bottom-20"><a href="https://www.facebook.com/groups/190335657974742" target="_blank">Cleveland Tech Jobs</a></address>
<address class="margin-bottom-20"><a href="https://www.facebook.com/groups/1527619877558883" target="_blank">Columbus Tech Jobs</a></address>
<address class="margin-bottom-20"><a href="https://www.facebook.com/groups/192567151082027" target="_blank">D.C. Tech Jobs</a></address>
<address class="margin-bottom-20"><a href="https://www.facebook.com/groups/1642540426001121" target="_blank">Denver Tech Jobs</a></address>
<address class="margin-bottom-20"><a href="https://www.facebook.com/groups/1642687792660278" target="_blank">Detroit Tech Jobs</a></address>
<address class="margin-bottom-20"><a href="https://www.facebook.com/groups/1003849216340782" target="_blank">DFW Tech Jobs</a></address>
<address class="margin-bottom-20"><a href="https://www.facebook.com/groups/1276491092377811" target="_blank">Houston Tech Jobs</a></address>
<address class="margin-bottom-20"><a href="https://www.facebook.com/groups/1014490908621240" target="_blank">Indy Tech Jobs</a></address>
<address class="margin-bottom-20"><a href="https://www.facebook.com/groups/1282313205118246" target="_blank">Jacksonville Tech Jobs</a></address>
<address class="margin-bottom-20"><a href="https://www.facebook.com/groups/428415847353820" target="_blank">KC Tech Jobs</a></address>
<address class="margin-bottom-20"><a href="https://www.facebook.com/groups/205978499734045" target="_blank">L.A. Tech Jobs</a></address>
<address class="margin-bottom-20"><a href="https://www.facebook.com/groups/941560209264929" target="_blank">Las Vegas Tech Jobs</a></address>
<address class="margin-bottom-20"><a href="https://www.facebook.com/groups/1092409680770127" target="_blank">Miami Tech Jobs</a></address>
<address class="margin-bottom-20"><a href="https://www.facebook.com/groups/140677776293570" target="_blank">Minneapolis Tech Jobs</a></address>
<address class="margin-bottom-20"><a href="https://www.facebook.com/groups/414622282072058" target="_blank">NYC Tech Jobs</a></address>
<address class="margin-bottom-20"><a href="https://www.facebook.com/groups/422442127956254" target="_blank">Philly Tech Jobs</a></address>
<address class="margin-bottom-20"><a href="https://www.facebook.com/groups/108926489475091" target="_blank">Phoenix Tech Jobs</a></address>
<address class="margin-bottom-20"><a href="https://www.facebook.com/groups/1662005557405682" target="_blank">Portland Tech Jobs</a></address>
<address class="margin-bottom-20"><a href="https://www.facebook.com/groups/1512825989016347" target="_blank">Raleigh Tech Jobs</a></address>
<address class="margin-bottom-20"><a href="https://www.facebook.com/groups/626302057508073" target="_blank">Sacramento Tech Jobs</a></address>
<address class="margin-bottom-20"><a href="https://www.facebook.com/groups/1490549821249070" target="_blank">San Antonio Tech Jobs</a></address>
<address class="margin-bottom-20"><a href="https://www.facebook.com/groups/425947747614256" target="_blank">San Diego Tech Jobs</a></address>
<address class="margin-bottom-20"><a href="https://www.facebook.com/groups/145758659115448" target="_blank">Seattle Tech Jobs</a></address>
<address class="margin-bottom-20"><a href="https://www.facebook.com/groups/1022777241114245" target="_blank">SLC Tech Jobs</a></address>
<address class="margin-bottom-20"><a href="https://www.facebook.com/groups/638165106326156" target="_blank">St. Louis Tech Jobs</a></address>
<address class="margin-bottom-20"><a href="https://www.facebook.com/groups/1520770271572062" target="_blank">Tampa Tech Jobs</a></address>


								  </div>
					                    
					                    <ul class="social-icons margin-bottom-10">
										<li>
											<a href="https://www.facebook.com/JobAlarm-80652713176" data-original-title="facebook" class="facebook" target="_blank">											</a>										</li>
										<li>
											<a href="http://www.twitter.com/jobalarm" data-original-title="twitter" class="twitter" target="_blank">											</a>										</li>
										</ul>
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