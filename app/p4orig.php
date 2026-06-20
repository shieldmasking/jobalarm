<?php
session_start();
ini_set('display_errors',1);
include_once '../inc/class.db.php';
include_once '../inc/class.jatwitter.php';
include_once '../inc/config.php';


if (isset($_SESSION['candidate'])){
    $accountData = $_SESSION['candidate'];
}

$bgColor = '';

if (isset($_REQUEST['app'])) {
	$applyId = isset($_REQUEST['aId']) ? $_REQUEST['aId'] : '';
	$url = isset($_REQUEST['url']) ? $_REQUEST['url'] : '';	
	$currentEmp = isset($_REQUEST['currentEmp']) ? $_REQUEST['currentEmp'] : '';	
	$pastEmp = isset($_REQUEST['pastEmp']) ? $_REQUEST['pastEmp'] : '';	
	$brandOrig = isset($_REQUEST['brandOrig']) ? $_REQUEST['brandOrig'] : '';		
	$jobType = isset($_REQUEST['jobType']) ? $_REQUEST['jobType'] : '';
	$schedule = isset($_REQUEST['schedule']) ? $_REQUEST['schedule'] : '';
	$accountId = isset($_REQUEST['accountId']) ? $_REQUEST['accountId'] : '';
	//$amount = isset($_REQUEST['amount']) ? $_REQUEST['amount'] : '';
	
}
$acctDb = Config::get('db')->get_results("SELECT * FROM `account` where `id`='{$accountId}'");

if (!isset($_SESSION['candidate'])) {

$dbMobile = Config::get('db')->get_results("SELECT * from `sms_brand` where id={$brandOrig}");


$brColor = ($dbMobile[0]['color']) ? $dbMobile[0]['color'] : '';
$image = ($dbMobile[0]['storeImage']) ? $dbMobile[0]['storeImage'] : 'logo1.png';

}else{
$image = ($accountData['storeImage']) ? $accountData['storeImage'] : '';
$brColor = ($accountData['color']) ? $accountData['color'] : '';
}

if (intval($accountId)==391){
$image = $acctDb[0]['logo'];
}

/////Test Data

	if (strlen($brColor)>0) {
	$bgColor = " style=\"background-color:#".$brColor."\"";	
}
   
   if ($applyId) {
		$updatedata = array(
			'jobType'=>$jobType,
			'currentEmp'=>$currentEmp,
			'schedule'=>$schedule,
			'pastEmp'=>$pastEmp
			);
		$updatewhere = array('id'=>$applyId);
		Config::get('db')->update('candidateApply',$updatedata,$updatewhere);
			
	}
	
	//echo $url;

	
	



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
<title>JobAlarm | Apply</title>
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

    <!-- BEGIN HEADER -->
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
								<form action="../app/p5.php" id="p4" name="p4" method="post">
								
								<input type="hidden" name="app" value="1" />
								
                           				
														
									
												
<h4 class="form-section" style="text-align: center"><strong>Final Step:  Resume & Skills</strong></h4>
		
									
                    <div class="col-md-6">

									<div class="form-group">									
  <label for="resume_paste">Resume/Skills:</label>
  <textarea class="form-control" style="height:80px" id="resume_paste" name="resume_paste" placeholder="Paste your resume or add the skills that make you qualified for this position."></textarea>
   </div>	
  									
								
								
                                        
                                        
										<!--<input type="hidden" id="username" name="username" value="Jobalarm" />-->
									 <input type="hidden" id="aId" name="aId" value="<?php echo $applyId; ?>" />
									 <input type="hidden" id="url" name="url" value="<?php echo $url; ?>" />
									 <input type="hidden" id="brandOrig" name="brandOrig" value="<?php echo $brandOrig; ?>" />
									 <input type="hidden" id="mobile" name="mobile" value="<?php echo $mobile; ?>" />
									 <input type="hidden" id="accountId" name="accountId" value="<?php echo $accountId; ?>" />
									 
									
								  										
								   <div class="form-group">
								   <input type="submit" value="Submit"/>
								   </form>
								   </div>
								<!--<p>By entering my information and clicking "Submit",  I am providing express consent to be contacted by JobAlarm.com via email, phone and text message, regarding job placement, job openings, career services and marketing promotions (if selected above) using automated technology. Standard message and data rates may apply to text messages. You are not required to provide consent to receive services from JobAlarm.com. I also have read and agree to the JobAlarm.com <a href="/terms">Terms of Use.</a></p>
								<p>Text "HELP" to the shortcode from which you're receiving text alerts for help. Text "STOP" to the shortcode from which you're receiving alerts to cancel.</p>
							-->
							</div>
					
							
						 
						  <!-- END FORM-->
													
						</div>
						</div>
					</div>
				




				<!-- END PAGE CONTENT INNER -->
</div>
</div>
<!-- END PAGE CONTENT -->
</div>
</div>
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
<script src="../inc/candidateApply.js" type="text/javascript"></script>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-59491934-1', 'auto');
  ga('send', 'pageview');

</script>
<script>
apply.updateCandidate();
</script>

<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>