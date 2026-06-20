<?php
include_once '../inc/class.db.php';
include_once '../inc/class.jatwitter.php';
include_once '../inc/config.php';


//echo 'mobile'.$accountData['mobile'];
//echo 'brand'.$accountData['brandOrig'];

$accountId = (isset($_REQUEST['a'])) ? $_REQUEST['a'] : '';
$zip = (isset($_REQUEST['z'])) ? $_REQUEST['z'] : '';
$mobileId = (isset($_REQUEST['m'])) ? $_REQUEST['m'] : '';
$url = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : 'http://www.jobalarm.com/search.php';
$mobile = '';
//$acctDb = Config::get('db')->get_results("SELECT * FROM `account` where `id`={$accountId}");
$brandOrig = (isset($_REQUEST['b'])) ? $_REQUEST['b'] : '';

$brData = Config::get('db') -> get_results("SELECT s.*, a.status as acctStatus, a.fullName, a.logo, a.logopic, a.rolex as atype, c.id as candidateId, c.mobile from `sms_brand` s left join `account` as a on a.id ={$accountId} left join `candidate` as c on c.mobile = {$mobileId} where s.id ={$brandOrig}");
$keyword = $brData[0]['keyword'];
$mobile = $brData[0]['mobile'];
$acctStatus = $brData[0]['acctStatus'];
$acctType = $brData[0]['atype'];
$positions = $brData[0]['positions'];
$brColor = $brData[0]['color'];
$storeBrand = $brData[0]['storeBrand'];
$acctName = $brData[0]['fullName'];
$image = $brData[0]['storeImage'];
$minAge = $brData[0]['minAge'];	
$candidateId = $brData[0]['candidateId'];					

$outjobs = '';
$outlocations = '';
$bgColor = '';
$franchise = '';

if (strlen($brColor)>0) {
	$bgColor = " style=\"background-color:#".$brColor."\"";	
}

if (strlen($positions) > 10) {
	$jobs = explode(',',$positions);
	foreach($jobs as $job){
	$outjobs .= "<option value=\"".$job."\">".$job."</option> ";
	}	
}else{
	$outjobs .= "<option value=\"Part Time\">Part Time</option>
<option value=\"Full Time\">Full Time</option>
<option value=\"Manager\">Manager</option>";
	
}
if (intval($acctType) != 2) {
	$franchise = $acctName.", a franchisee of ".$storeBrand;
}else{
	$franchise = $acctName;
}


	//echo 'accst'.$acctStatus;
	$dbStore = Config::get('db') -> get_results("SELECT * FROM `cities_extended` where `zip`={$zip}");
	$lat = $dbStore[0]['latitude'];
	$lon = $dbStore[0]['longitude'];
	$zipDist = 20;
	$locData = Config::get('db') -> get_results("SELECT s.id, s.address, s.city, s.st, (3963*ACOS(SIN({$lat}/57.2958)*SIN(ce.latitude/57.2958)+ COS({$lat}/57.2958) *COS(ce.latitude/57.2958)*COS((ce.longitude/57.2958)-({$lon}/57.2958)))) as dist from `sms_stores` s left join `cities_extended` as ce on ce.zip =s.zip where s.accountId ={$accountId} and (3963*ACOS(SIN({$lat}/57.2958)*SIN(ce.latitude/57.2958)+ COS({$lat}/57.2958) *COS(ce.latitude/57.2958)*COS((ce.longitude/57.2958)-({$lon}/57.2958)))<={$zipDist}) order by dist ASC");
	
	if (count($locData)==0){
	$zipDist = 20;
	$locData = Config::get('db') -> get_results("SELECT s.id, s.address, s.city, s.st, (3963*ACOS(SIN({$lat}/57.2958)*SIN(ce.latitude/57.2958)+ COS({$lat}/57.2958) *COS(ce.latitude/57.2958)*COS((ce.longitude/57.2958)-({$lon}/57.2958)))) as dist from `sms_stores` s left join `cities_extended` as ce on ce.zip =s.zip where s.accountId ={$accountId} and (3963*ACOS(SIN({$lat}/57.2958)*SIN(ce.latitude/57.2958)+ COS({$lat}/57.2958) *COS(ce.latitude/57.2958)*COS((ce.longitude/57.2958)-({$lon}/57.2958)))<={$zipDist}) order by dist ASC");
	}
	foreach($locData as $loc){
	$outlocations .= "<option value=\"".$loc['id']."\">".$loc['address'].", ".$loc['city'].", ".$loc['st']."</option> ";
	}



//echo 'candidate'.$candidateId;




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
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<title>JobAlarm | Mobile Apply</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta content="" name="description">
<meta content="" name="author">
<meta property="og:url" content="http://www.jobalarm.com">
<meta property="og:image" content="http://www.jobalarm.com/img/job2.jpg">
<meta property="og:description" content="JobAlarm give you the ability to communicate with Employers via text.  Search Real Local Jobs">

 <!-- BEGIN GLOBAL MANDATORY STYLES -->          
   <link href="../assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
   <link href="../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
   <!-- END GLOBAL MANDATORY STYLES -->
   
   <!-- BEGIN PAGE LEVEL PLUGIN STYLES --> 
   <link href="../assets/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet" />              
   <link rel="stylesheet" href="../assets/plugins/revolution_slider/css/rs-style.css" media="screen">
   <link rel="stylesheet" href="../assets/plugins/revolution_slider/rs-plugin/css/settings.css" media="screen"> 
   <link href="../assets/plugins/bxslider/jquery.bxslider.css" rel="stylesheet" />      
   
   <!-- END PAGE LEVEL PLUGIN STYLES -->
   <!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css">
<link href="../theme/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css">
<link href="../theme/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css">
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="../theme/assets/global/plugins/bootstrap-datepicker/css/datepicker3.css"/>
<link rel="stylesheet" type="text/css" href="../theme/assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="../theme/assets/admin/pages/css/todo.css"/>

   <!-- BEGIN THEME STYLES --> 
<link href="../assets/css/style-metronic.css" rel="stylesheet" type="text/css"/>
<link href="../assets/css/style.css" rel="stylesheet" type="text/css"/>
<link href="../assets/css/themes/blue.css" rel="stylesheet" type="text/css" id="style_color"/>
<link href="../assets/css/style-responsive.css" rel="stylesheet" type="text/css"/>
<link href="../assets/css/custom.css" rel="stylesheet" type="text/css"/>
<link href="../theme/assets/global/css/components-rounded.css" id="style_components" rel="stylesheet" type="text/css">
<link href="../theme/assets/global/css/plugins.css" rel="stylesheet" type="text/css">
<link href="../theme/assets/admin/layout3/css/layout.css" rel="stylesheet" type="text/css">
<link href="../theme/assets/admin/layout3/css/themes/blue-steel.css" rel="stylesheet" type="text/css" id="style_color">
<link href="../theme/assets/admin/layout3/css/custom.css" rel="stylesheet" type="text/css">
<link href="../assets/css/style-metronic.css" rel="stylesheet" type="text/css"/>
<link href="../assets/css/style.css" rel="stylesheet" type="text/css"/>
<link href="../assets/css/themes/blue.css" rel="stylesheet" type="text/css" id="style_color"/>
<link href="../assets/css/style-responsive.css" rel="stylesheet" type="text/css"/>
<link href="../assets/css/custom.css" rel="stylesheet" type="text/css"/>
   <!-- END THEME STYLES -->

   <link rel="shortcut icon" href="../favicon.ico" />
</head>
<!-- END HEAD -->

<!-- BEGIN BODY -->
<body class="page-boxed page-header-fixed page-container-bg-solid page-sidebar-closed-hide-logo page-sidebar-closed">
	<!-- BEGIN STYLE CUSTOMIZER -->
	
	<!-- END BEGIN STYLE CUSTOMIZER -->    

    	<!-- BEGIN HEADER TOP -->
	<div class="page-header-top">
		<div class="container" align="center">
				<?php
			   				 
				   echo '<img src="../img/'.$image.'" />';   
			    	?>
						</div>
				<!-- END LOGO -->
			</div>
		
			
<!-- END HEADER -->
<!-- BEGIN PAGE CONTENT -->
<div class="page-content">
	<div class="container">

			<div class="portlet light">
			<div class="portlet-body">
				<div class="row">
						  <div class="col-md-12">
								<!-- BEGIN FORM-->
								<form action="../app/p2.php" id="apply" name="apply" method="post">
								
								<input type="hidden" name="app" value="1" />
								
                           
								<?php if(intval($acctStatus)!=4){ ?>
								<h4 class="form-section" style="text-align: center"><strong>Welcome to JobAlarm</strong></h4>
								<?php } ?>	
									<?php if(intval($accountId) == 89) { ?>
									<p class="auto-style3">This is the DEMO version of Jobalarm's Mobile Apply.  Please use your email address below and the candidate information you enter will be emailed to you. </p>
									<?php }else if(intval($acctStatus)==4){ ?>									
									<p class="auto-style3">Be the first to hear about great jobs in your area with <?php echo $storeBrand; ?>!  </p>
									<?php }else{ ?>	
									<p class="auto-style3">You are just a few steps away from submitting your employment profile to <?php echo $franchise; ?>. </p>
									<?php } ?>	
									<!--<p class="auto-style3">  
									Welcome to JobAlarm.com, the only 
									site that wants to help you find a job and 
									save you money at the same time!</p>-->
									
 <?php if (intval($acctStatus)<=5) { ?>                   

<div class="form-group">									
<select id="position" name="position" type="select" class="form-control" placeholder="Select a job" required>

<option value="">Select a job....</option>
<?php echo $outjobs; ?>
<!--<option value="Crew Member">Crew Member</option>
<option value="Shift Supervisor">Shift Supervisor</option>
<option value="Assistant Manager">Assistant Manager</option>
<option value="General Manager">General Manager</option>-->
<option value="Other">Other</option></select>
</div>

<?php if ($outlocations) { ?>
<div class="form-group">									
<select id="location" name="location" type="select" class="form-control" placeholder="Preferred Location" required>
<option value="">Preferred Location....</option>
<?php echo $outlocations; ?></select>
</div>
<?php } ?>
<?php } ?>

									
									<div class="form-group">
										<div class="input-icon">
											<i class="fa fa-user"></i>
											<input id="contact_name" name="contact_name" type="text" class="form-control" placeholder="First Name / Nombre" <?php if(intval($accountId) != 89) { ?> required <?php } ?> />
										</div>
									</div>
									<div class="form-group">
										<div class="input-icon">
											<i class="fa fa-user"></i>
											<input id="contact_lastname" name="contact_lastname" type="text" class="form-control" placeholder="Last Name / Apellido" <?php if(intval($accountId) != 89) { ?> required <?php } ?> />
										</div>
									</div>
									<?php
									if ($candidateId==0) {
										?>
									<div class="form-group">
										<div class="input-icon">
											<i class="fa fa-phone"></i>
											<input id="contact_mobile" name="contact_mobile" type="text" class="form-control" placeholder="Mobile Number / Numero de Telefono" maxlength="13" required /></div>
									</div>
									<?php } ?>
									<div class="form-group">
										<div class="input-icon">
											<i class="fa fa-envelope"></i>
											<input id="contact_email" name="contact_email" type="text" class="form-control" placeholder="Email" <?php if(intval($accountId) == 89) { ?> required <?php } ?> />
										</div>
									</div>
									
									<div class="form-group">
									<div class="input-icon">
									<i class="fa fa-briefcase"></i>
										<input id="zipcode" name="zipcode" type="text" class="form-control" placeholder="Zip Code / Código Postal" <?php if(intval($accountId) != 89) { ?> required <?php } ?> />
									  </div>
								  </div>
								
									 <input type="hidden" id="accountId" name="accountId" value="<?php echo $accountId; ?>" />
									 <input type="hidden" id="url" name="url" value="<?php echo $url; ?>" />
									 <input type="hidden" id="brandOrig" name="brandOrig" value="<?php echo $brandOrig; ?>" />
									 <input type="hidden" id="mobile" name="mobile" value="<?php echo $mobile; ?>" />
									 <input type="hidden" id="keyword" name="keyword" value="<?php echo $keyword; ?>" />
									 <input type="hidden" id="storeimage" name="storeimage" value="<?php echo $image; ?>" />
									 <input type="hidden" id="minAge" name="minAge" value="<?php echo $minAge; ?>" />
									 <input type="hidden" id="brColor" name="brColor" value="<?php echo $brColor; ?>" />
									 <input type="hidden" id="candidateId" name="candidateId" value="<?php echo $candidateId; ?>" />
								  
				
								   <div class="form-group">
								   <input type="submit" value="Next"/>
								   
								   
								<!--<p>By entering my information and clicking "Submit",  I am providing express consent to be contacted by JobAlarm.com via email, phone and text message, regarding job placement, job openings, career services and marketing promotions (if selected above) using automated technology. Standard message and data rates may apply to text messages. You are not required to provide consent to receive services from JobAlarm.com. I also have read and agree to the JobAlarm.com <a href="/terms">Terms of Use.</a></p>
								<p>Text "HELP" to the shortcode from which you're receiving text alerts for help. Text "STOP" to the shortcode from which you're receiving alerts to cancel.</p>
							-->
							</div>
							</form>
					
							
						 
						  <!-- END FORM-->
													
						</div>
						</div>
					</div>
				</div>




				<!-- END PAGE CONTENT INNER -->
	</div>
		
	
</div>
<!-- END PAGE CONTENT -->

<!-- BEGIN FOOTER -->

<div class="page-footer"<?php echo $bgColor; ?>>
	<div class="container">
		 2015 &copy; Harrelson Group LLC. All Rights Reserved.
	</div>
</div>

<!-- END FOOTER -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="theme/assets/global/plugins/respond.min.js"></script>
<script src="theme/assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
<script src="../theme/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="../theme/assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="../theme/assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="../theme/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="../theme/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="../theme/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="../theme/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="../theme/assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="../theme/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE PLUGINS & SCRIPTS -->
<script type="text/javascript" src="../theme/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="../theme/assets/global/plugins/select2/select2.min.js"></script>
<script src="../theme/assets/admin/pages/scripts/todo.js" type="text/javascript"></script>
<!-- END PAGE PLUGINS & SCRIPTS -->
<script src="../theme/assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="../theme/assets/admin/layout3/scripts/layout.js" type="text/javascript"></script>
<script src="../theme/assets/admin/layout3/scripts/demo.js" type="text/javascript"></script>

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