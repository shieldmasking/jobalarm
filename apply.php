<?php
include_once 'inc/class.db.php';
include_once 'inc/class.jatwitter.php';
include_once 'inc/config.php';
include_once 'inc/pagination.class.php';

require_once 'vendor/autoload.php';

/////Test Data
$brand = (isset($_REQUEST['b'])) ? $_REQUEST['b'] : 6;
$brandOrig = $brand;
if (intval($brand)==22){
	$brand = 6;
}

$referral = (isset($_REQUEST['cx'])) ? ",'web'" : ",'web'";

?>


<style type="text/css">
.auto-style1 {
	width: 1170px;
	text-align: center;
	margin-left: auto;
	margin-right: auto;
	padding-left: 15px;
	padding-right: 15px;
}
.auto-style3 {
	text-align: center;
	font-size: medium;
}
.auto-style4 {
	font-size: medium;
}
.style1 {color: #FF0000}
</style>

<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title>JobAlarm</title>
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
                <div class="col-md-6 col-sm-6 additional-shop-info">
                    <ul class="list-unstyled list-inline">
                        
                        
                    </ul>
                </div>
                <!-- END TOP BAR LEFT PART -->
                <!-- BEGIN TOP BAR MENU -->
                
                <!-- END TOP BAR MENU -->
            </div>
            
        </div>        
    </div>
	<!-- BEGIN HEADER TOP -->
	

		<div class="container" align="center">
		
			<!-- BEGIN LOGO -->
			
			<?php
			   $brandData = Config::get('db')->get_results("SELECT * from sms_brand where id = $brandOrig");
				 
				if (count($brandData) > 0 && strlen($brandData[0]['storeImage']) > 0) {
			        $brData = $brandData[0];
					echo '<img src="img/'.$brData['storeImage'].'" />';   
			    } else { 
			?>
				<img src="img/logo1.png">			
			<?php } ?>
			
			
			<!-- END LOGO -->
			<!-- BEGIN RESPONSIVE MENU TOGGLER -->
			
			<!-- END RESPONSIVE MENU TOGGLER -->

	
		</div>
	
	<!-- END HEADER TOP -->
	<!-- BEGIN HEADER MENU -->
	
	<!-- END HEADER MENU -->

    <!-- END TOP BAR -->
<!-- BEGIN HEADER -->

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
						  <div class="col-md-12">
								<!-- BEGIN FORM-->
								<form action="jobs.php" id="jaform" name="jaform" method="post">
								
								<input type="hidden" name="submitting_candidate" value="1" />
								<input type="hidden" name="apply_type" value="1" />
                           
								<?php
								if (intval($brand) ==6) {
			        			?>
								<h3 class="form-section" style="text-align: center"><strong>GET HIRED!</strong></h3>
								<?php }else if (intval($brand) ==23) {
			        			?>
								<h3 class="form-section" style="text-align: center"><strong>WELCOME TO MALL JOBS!</strong></h3>
								<?php }else{?>
								<h3 class="form-section" style="text-align: center"><strong>WE'RE HIRING!</strong></h3>
								<?php } ?>	
									
									<p class="auto-style3"><strong><?php echo $brData['responseMsg']; ?></strong></p>
									
									<p class="auto-style3">Just provide some basic information and 
									<?php echo $brData['storeBrand']; ?> will be able to instantly contact you 
									via text, phone or email when jobs are open near you. </p>
									<!--<p class="auto-style3">  
									Welcome to JobAlarm.com, the only 
									site that wants to help you find a job and 
									save you money at the same time!</p>-->
									
                    <div class="col-md-6">
<!--
									<?php
	if (intval($brand) ==6 && intval($brandOrig) !=22) {
	?>
	<div class="form-group">									
	<select id="industry_type" name="industry_type" type="select" class="form-control" placeholder="Select an Industry...">
	<option value="0">Industry....</option>
	<option value="1">Call Center</option>
	<option value="2">Construction</option>
	<option value="3">Hospitality/Restaurant</option>
	<option value="4">Manufacturing</option>
	<option value="5">Retail</option></select>
	
	</div>	
	<?php } ?>
	-->
									<div class="form-group">									
<select id="job_type" name="job_type" type="select" class="form-control" placeholder="Select a job">
<?php
	if (intval($brand) !=6) {
	?>
<option value="">Select a job....</option>
<?php } ?>
<option value="Part Time">Part Time</option>
<option value="Full Time">Full Time</option>
<option value="Manager">Manager</option>
<option value="Other">Other</option></select>
</div>	
									
									<div class="form-group">
										<div class="input-icon">
											<i class="fa fa-user"></i>
											<input id="contact_name" name="contact_name" type="text" class="form-control" placeholder="First Name">
										</div>
									</div>
									<div class="form-group">
										<div class="input-icon">
											<i class="fa fa-user"></i>
											<input id="contact_lastname" name="contact_lastname" type="text" class="form-control" placeholder="Last Name">
										</div>
									</div>
									<div class="form-group">
										<div class="input-icon">
											<i class="fa fa-phone"></i>
											<input id="contact_mobile" name="contact_mobile" type="text" class="form-control" placeholder="Mobile Number" maxlength="13">
										</div>
									</div>
									<div class="form-group">
										<div class="input-icon">
											<i class="fa fa-envelope"></i>
											<input id="contact_email" name="contact_email" type="text" class="form-control" placeholder="Email Address">
										</div>
									</div>
									<div class="form-group">
									<div class="input-icon">
									<i class="fa fa-briefcase"></i>
										<input id="zipcode" name="zipcode" type="text" class="form-control" placeholder="Zip Code" required  />
									  </div>
								  </div>
										<?php
								if (intval($brandOrig) ==22) {
			        			?>
								<div class="form-group">
                                            <label for="resume_paste">Preferences:</label>
                                            <textarea class="form-control" style="height:80px" id="resume_paste" name="resume_paste" placeholder="Paste your resume or provide specific construction experience and any certifications.  Leave blank for all."></textarea>
                                        </div>
								<?php }else{?>
								<div class="form-group">
                                            <label for="resume_paste">Preferences:</label>
                                            <textarea class="form-control" style="height:80px" id="resume_paste" name="resume_paste" placeholder="Paste your resume or provide industry preferences such as Clothing, Sporting Goods, Fast Food, etc.  Leave blank for all."></textarea>
                                        </div>
								<?php } ?>
                                        
                                        
										<!--<input type="hidden" id="username" name="username" value="Jobalarm" />-->
									 <input type="hidden" id="accountId" name="accountId" value="0" />
									 
									 <input type="hidden" id="smskey" name="smskey" value="<?php echo $brData['keyword']; ?>" />
									 
									 <input type="hidden" id="brand" name="brand" value="<?php echo $brand; ?>" />
									 
									 <input type="hidden" id="industry" name="industry" value="<?php echo $brandOrig; ?>" />

										<!--
                                        <div class="form-group">
                                            <label for="resume_file">Upload Resume:</label>
                                            <div>
                                                <span class="btn btn-default btn-file">
                                                    Browse <input type="file" id="resume_file" name="resume_file" />
                                                </span>
                                            </div>
                                        </div>
                                        	<button onClick="window.location.href='http://www.jobalarm.com/tweetlist3.php?search_keyword=chuck+e+cheese&zipcode='+cecform["zipcode"]">Submit</button> <p>Note:  If you do not wish to be contacted about future job openings, you can enter just your zip code above and still get a list of current openings in your area. 
								   /> -->
									 <input type="hidden" id="search_keyword" name="search_keyword" value="job" />
									 
									
								  
								<?php if (intval($brand) !=6) {?>
									<div class="form-group">
									<input type="checkbox" name="jobs" value="1">
									<strong> Please Send Me Job Related Text Messages From <?php echo $brData['storeBrand']; ?>.</strong></div>
									<div class="form-group">
									<input type="checkbox" name="promos" value="1">
									<strong> Please Send Me Text Messages with Deals and Discounts From <?php echo $brData['storeBrand']; ?>.</strong></div>
								<?php }else{?>
								<div class="form-group">
								<input type="checkbox" name="allemps" value="2">
									<strong> Please Send Me Job Related Text Messages.</strong></div>
								  <div class="form-group">
								  <input type="checkbox" name="alldeals" value="2"> 
								  <strong>Please Send Me Text Messages with Deals and Discounts.</strong></div>
								<?php } ?>
								
								<?php
								if (intval($brand) !=6) {
			        			?>
								<div class="form-group">
								  <input type="checkbox" name="allemps" value="2"> 
								  <strong>Please Allow <span class="style1">All Employers</span> To Send Me Job Related Text Messages.</strong></div>
								  <div class="form-group">
								  <input type="checkbox" name="alldeals" value="2"> 
								  <strong>Please Allow <span class="style1">All Employers</span> To Send Me Text Messages with Deals & Discounts.</strong></div>
								<?php } ?>	
									<p><strong>Note: You will only 
									receive up to 4 text messages per month.</strong></p>
									
								<!--	<button onClick="window.location.href='http://www.jobalarm.com/tweetlist3.php?search_keyword=chuck+e+cheese&zipcode='+cecform["zipcode"]">Submit</button> <p>Note:  If you do not wish to be contacted about future job openings, you can enter just your zip code above and still get a list of current openings in your area. 
								   /> -->
								   <div class="form-group">
								   <input type="submit" value="Submit"/>
								   </form>
								   </div>
								<p>By entering my information and clicking "Submit",  I am providing express consent to be contacted by JobAlarm.com via email, phone and text message, regarding job placement, job openings, career services and marketing promotions (if selected above) using automated technology. Standard message and data rates may apply to text messages. You are not required to provide consent to receive services from JobAlarm.com. I also have read and agree to the JobAlarm.com <a href="/terms">Terms of Use.</a></p>
								<p>Text "HELP" to the shortcode from which you're receiving text alerts for help. Text "STOP" to the shortcode from which you're receiving alerts to cancel.</p>
							</div>
					
							
						 
						  <!-- END FORM-->
							
				
							
							<div class="col-md-6">
							<div class="image" align="center"><a href="/search.php" target="_blank"><img src="img/star.jpg"></a>
							<?php
							   if (count($brandData) > 0 && strlen($brandData[0]['altImage']) > 0) {
			        			$brData = $brandData[0];
								echo '<div class="image" align="center"><img src="img/'.$brData['altImage'].'"></div>';  
								} else {
								echo '<div class="image" align="center"><img src="img/job2.jpg"></div>';			
								} ?>
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
	<div class="auto-style1">
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
<script src="theme/assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="theme/assets/admin/layout3/scripts/layout.js" type="text/javascript"></script>
<script src="theme/assets/admin/layout3/scripts/demo.js" type="text/javascript"></script>
<script>
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core components
Layout.init(); // init current layout
Demo.init(); // init demo features
Todo.init(); // init todo page
          


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