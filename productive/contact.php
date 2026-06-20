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
  mail( "rstrenger@jobalarm.com", "ProductiveHR Contact Form: ".$_POST['name'].$company, $_POST['name']."\r\n\r\n".$_POST['company']."\r\n\r\n".$_POST['email']."\r\n\r\n".$_POST['mobile']."\r\n\r\n".$_POST['text'], "From: JobAlarm ContactUs <rstrenger@jobalarm.com>"  );
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
<!-- BEGIN HEADER -->
<div class="page-header">
	<!-- BEGIN HEADER TOP -->
	<div class="page-header-top">
		<div class="container">
			<!-- BEGIN LOGO -->
			<div class="page-logo">
				<a href="index.php"><img src="../img/productive.png" longdesc="http://www.jobalarm.com/productive"></a>			</div>
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
			<h1 style="width:320px;float:left;margin:0;padding:5px;color:white">Contact Us</h1>
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
				 Contact Us
			</li>
		</ul>
		<!-- END PAGE BREADCRUMB -->
		<!-- BEGIN PAGE CONTENT INNER -->
		<div class="portlet light">
			<div class="portlet-body">
				<div class="row">
					<div class="col-md-12">
						<!-- Google Map -->
						<div class="row">
							<div id="map" class="gmaps margin-bottom-40" style="height:400px;">
							</div>
						</div>
						<div class="row margin-bottom-20">
							<div class="col-md-6">
								<div class="space20">
								</div>
								<h3 class="form-section">Contacts</h3>
								<p>
									 <!-- INSERT CONTACT SUB INFORMATION -->


								</p>
								<div class="well">
									<h4>Address</h4>
					                    <address class="margin-bottom-40">
					                        Premier SSG, Inc.<br />
					                        2591 Dallas Pkwy, Suite 300 <br />
					                        Frisco, TX 75034 <br />
											Email: <a href="mailto:rstrenger@jobalarm.com">info@jobalarm.com</a><br /> 
											Phone: 855-603-7073                      
					                    </address>
									<ul class="social-icons margin-bottom-10">
										<li>
											<a href="javascript:;" data-original-title="facebook" class="facebook">
											</a>
										</li>
										<li>
											<a href="javascript:;" data-original-title="github" class="github">
											</a>
										</li>
										<li>
											<a href="javascript:;" data-original-title="Goole Plus" class="googleplus">
											</a>
										</li>
										<li>
											<a href="javascript:;" data-original-title="linkedin" class="linkedin">
											</a>
										</li>
										<li>
											<a href="javascript:;" data-original-title="rss" class="rss">
											</a>
										</li>
										<li>
											<a href="javascript:;" data-original-title="skype" class="skype">
											</a>
										</li>
										<li>
											<a href="javascript:;" data-original-title="twitter" class="twitter">
											</a>
										</li>
										<li>
											<a href="javascript:;" data-original-title="youtube" class="youtube">
											</a>
										</li>
									</ul>
								</div>
							</div>
							<div class="col-md-6">
								<div class="space20">
								</div>
								<!-- BEGIN FORM-->
								<form id="contact" action="contact.php" method="post">
									<h3 class="form-section">Contact Form</h3>
									<p>
										 Thank you for your interest in ProductiveHR. Please fill out the form below and a JobAlarm Rep will be in touch with you asap.
									</p>
									<div class="form-group">
										<div class="input-icon">
											<i class="fa fa-user"></i>
											<input id="contact_name" name="contact_name" type="text" class="form-control" placeholder="Name">
										</div>
									</div>
									<div class="form-group">
										<div class="input-icon">
											<i class="fa fa-briefcase"></i>
											<input id="contact_company" name="contact_company" type="text" class="form-control" placeholder="Company">
										</div>
									</div>
									<div class="form-group">
										<div class="input-icon">
											<i class="fa fa-envelope"></i>
											<input id="contact_email" name="contact_email" type="text" class="form-control" placeholder="Email">
										</div>
									</div>
									<div class="form-group">
										<div class="input-icon">
											<i class="fa fa-phone"></i>
											<input id="contact_phone" name="contact_phone" type="text" class="form-control" placeholder="Phone #">
										</div>
									</div>
									<div class="form-group">
										<textarea id="contact_feedback" name="contact_feedback" class="form-control" rows="3=6" placeholder="Comments"></textarea>
									</div>
									<button id="submit_btn" type="submit" class="btn green">Submit</button>
									<div class="form-group">
										<p class="success" style="display:none">Your message has been sent successfully.</p>
										<p class="error" style="display:none">E-mail must be valid and message must not be longer than 100 characters.</p>
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