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
			<h1 style="width: 600px;float:left;margin:0;padding:5px;color:white">fb Admin Pinned Post Contest</h1>
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
		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title">Modal title</h4>
					</div>
					<div class="modal-body">
						 Widget settings form goes here
					</div>
					<div class="modal-footer">
						<button type="button" class="btn blue">Save changes</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
		<!-- /.modal -->
		<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
		<!-- BEGIN PAGE BREADCRUMB -->
		<ul class="page-breadcrumb breadcrumb">
			<li>
				<a href="index.php">Home</a><i class="fa fa-circle"></i>
			</li>
			<li class="active">
				 Giveaway
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
							<div class="col-md-6">
								<div class="space20">
								</div>
								<h3 align="center" class="form-section style4"><strong>Mention <span class="style3">JobAlarm.com</span> in your Pinned Post and become
								   eligible to win a Samsung Galaxy 7S!!</strong></h3>
								<p>
									 <!-- INSERT CONTACT SUB INFORMATION -->


								</p>
								<div align="center">
								<img src="img/galaxys6.jpg">
								</div>
								<div class="well">
									<h4><strong>Contest Rules</strong></h4>
					                    <address class="margin-bottom-40">
					                        1. You must be an Admin of the fb Group that you submit in order to participate. <br />
											2. The fb Group you submit must already be in our database of Job Related Groups. <br />
					                        3. The pinned post in your fb Group must reference www.jobalarm.com in a positive manner. This will be judged at our discretion.<br/>
					                        4. Only one Admin per fb Group can register.  The first Admin to register from any Group will be the only submission accepted from that Group. <br>
					                        5. Drawing will be held at 3pm CST on June 1, 2016 and the Winner will be notified via email and Facebook. Only one winner per drawing.<br />
											6. Winners are responsible for any taxes and/or fees associate with the prize.<br>
											7. You must be a citizen of the U.S.A. to participate.<br>
											8. If you provide an invalid email or fb URL, your entry will be deemed invalid.<br><br>This contest is in no way associated with Facebook.com or Twitter.com.</address>
					                    <ul class="social-icons margin-bottom-10">
										<li>
											<a href="javascript:;" data-original-title="facebook" class="facebook">
											</a>
										</li>
										<li>
											<a href="javascript:;" data-original-title="twitter" class="twitter">
											</a>
										</li>
										</ul>
					                    
								</div>
							</div>
							<div class="col-md-6">
								<div class="space20">
								</div>
								<!-- BEGIN FORM-->
								<form method="POST" action="giveaway.php?method=email" >
								
									<h3 class="style4 form-section"><strong>Register for your chance to WIN!!</strong></h3>
									<p>&nbsp;</p>
									<div class="form-group">
										<div class="input-icon">
											<i class="fa fa-user"></i>
											<input id="contact_name" name="contact_name" type="text" class="form-control" placeholder="Full Name" required />
										</div>
									</div>
									<div class="form-group">
										<div class="input-icon">
											<i class="fa fa-briefcase"></i>
											<input id="u" name="u" type="text" class="form-control" placeholder="Enter your fb Group URL where you mention Jobalarm.com" required />
										</div>
									</div>
									<div class="form-group">
										<div class="input-icon">
											<i class="fa fa-envelope"></i>
											<input id="contact_email" name="contact_email" type="text" class="form-control" placeholder="Your Email Address" required />
										</div>
									</div>
									<div class="form-group">
										<textarea id="contact_feedback" name="contact_feedback" class="form-control" rows="3=6" placeholder="Feedback: Tell us what you think of JobAlarm.com"></textarea>
									</div>
									<div>
									<input type="checkbox" name="q-8" id="terms" required />
									<label for="terms">I have read and agree to the Contest Rules.</label></div><p></p>
									<input type="submit" name="submit" value="Submit">
									
									<div class="form-group">
										<p class="success" style="display:none">Your entry has been submitted.  You will receive an email to either confirm or deny its acceptance into the contest.</p>
										<p class="error" style="display:none">E-mail must be valid and message must not be longer than 100 characters.</p>
									</div>
								</form>
								<!-- END FORM-->
							<div class="well">
									<h4><strong>About JobAlarm</strong></h4>
					                    <address class="margin-bottom-40"><p>JobAlarm is a great new job search tool that helps Employers deliver REAL LOCAL JOBS to you!</p>
					                    Give us a try at <a href="http://www.jobalarm.com" target="_blank">www.jobalarm.com</a>
										</address>
											
                                        
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