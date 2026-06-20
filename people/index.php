<?php
session_start();
ini_set('display_errors',1);
include_once '../inc/class.db.php';
include_once '../inc/class.jatwitter.php';
include_once '../inc/config.php';

$accountData = $_SESSION['candidate'];

//echo 'mobile'.$accountData['mobile'];
//echo 'brand'.$accountData['brandOrig'];

$accountId = (isset($_REQUEST['a'])) ? $_REQUEST['a'] : '';
$url = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : 'http://www.jobalarm.com/search.php';

if (!isset($_SESSION['candidate'])) {
$brandOrig = 93;
$brData = Config::get('db') -> get_results("SELECT * from sms_brand where id ={$brandOrig}");

$keyword = $brData[0]['keyword'];
$positions = $brData[0]['positions'];
$image = $brData[0]['storeImage'];
$brColor = $brData[0]['color'];
$storeBrand = $brData[0]['storeBrand'];
}else{
$mobile = ($accountData['mobile']) ? $accountData['mobile'] : '';
$keyword = ($accountData['keyword']) ? $accountData['keyword'] : '';
$brandOrig = ($accountData['brandOrig']) ? $accountData['brandOrig'] : 6;
$image = ($accountData['storeImage']) ? $accountData['storeImage'] : '';
$positions = ($accountData['positions']) ? $accountData['positions'] : '';
$storeBrand = ($accountData['storeBrand']) ? $accountData['storeBrand'] : '';
$brColor = ($accountData['color']) ? $accountData['color'] : '';
}


$outjobs = '';
$bgColor = '';

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
.style3 {
	font-size: 11px;
	font-weight: bold;
}
</style>

<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<title>JobAlarm | Order</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta content="" name="description">
<meta content="" name="author">
<meta property="og:url" content="http://www.jobalarm.com">
<meta property="og:image" content="http://www.jobalarm.com/img/logo1.png">

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
								<form action="../people/submit.php" id="apply" name="apply" method="post">
								
								<input type="hidden" name="app" value="1" />
								
                           
								
								<h4 class="form-section font-md" style="text-align: center"><strong>Thank you for choosing People Source!</strong></h4>
								
									
									<div align="center"><span class="style3">Please provide the information below and we will get to work on your requirements immediately. </span>
									    </p>
									  
									    <!--<p class="auto-style3">  
									Welcome to JobAlarm.com, the only 
									site that wants to help you find a job and 
									save you money at the same time!</p>-->
								  </div>
									<div class="form-group">
									<label for="number">Number of Resources Required:</label>
										<select id="number" name="number" type="select" class="form-control" placeholder="Select" >
															
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
										<option value="6">6</option>
										<option value="7">7</option>
										<option value="8">8</option>
										<option value="9">9</option>
										<option value="10">10</option>
										<option value="11">11</option>
										<option value="12">12</option>
										<option value="13">13</option>
										<option value="14">14</option>
										<option value="15">15</option>
										<option value="16">16</option>
										<option value="17">17</option>
										<option value="18">18</option>
										<option value="19">19</option>
										<option value="20">20+</option>										
										</select>
										
									</div>
									
									<div class="form-group">
									  
										<label for="daterequired">Date Required:</label>
									    
										<input id="daterequired" name="daterequired" type="date" class="form-control" />
									</div>
									
									<label for="city">Work Location:</label>
									<div class="input-group">
										
										
																					
											<input name="city" type="text" class="form-control" id="city" size="20" maxlength="20" placeholder="City" />
											<span class="input-group-addon"> </span>
									
											<select id="state" name="state" type="select" class="form-control" placeholder="ST">								
											
	<option value="">ST</option>
	<option value="AL">AL</option>
	<option value="AK">AK</option>
	<option value="AZ">AZ</option>
	<option value="AR">AR</option>
	<option value="CA">CA</option>
	<option value="CO">CO</option>
	<option value="CT">CT</option>
	<option value="DE">DE</option>
	<option value="DC">DC</option>
	<option value="FL">FL</option>
	<option value="GA">GA</option>
	<option value="HI">HI</option>
	<option value="ID">ID</option>
	<option value="IL">IL</option>
	<option value="IN">IN</option>
	<option value="IA">IA</option>
	<option value="KS">KS</option>
	<option value="KY">KY</option>
	<option value="LA">LA</option>
	<option value="ME">ME</option>
	<option value="MD">MD</option>
	<option value="MA">MA</option>
	<option value="MI">MI</option>
	<option value="MN">MN</option>
	<option value="MS">MS</option>
	<option value="MO">MO</option>
	<option value="MT">MT</option>
	<option value="NE">NE</option>
	<option value="NV">NV</option>
	<option value="NH">NH</option>
	<option value="NJ">NJ</option>
	<option value="NM">NM</option>
	<option value="NY">NY</option>
	<option value="NC">NC</option>
	<option value="ND">ND</option>
	<option value="OH">OH</option>
	<option value="OK">OK</option>
	<option value="OR">OR</option>
	<option value="PA">PA</option>
	<option value="RI">RI</option>
	<option value="SC">SC</option>
	<option value="SD">SD</option>
	<option value="TN">TN</option>
	<option value="TX">TX</option>
	<option value="UT">UT</option>
	<option value="VT">VT</option>
	<option value="VA">VA</option>
	<option value="WA">WA</option>
	<option value="WV">WV</option>
	<option value="WI">WI</option>
	<option value="WY">WY</option>
</select>	
										
									</div>
									<div class="form-group">	
									<label for="position">Area of Expertise:</label>								
										<select id="position" name="position" type="select" class="form-control" />
										
										<option value="">Select....</option>
										<option value="LI">Light Industrial / General Labor</option>
										<option value="Clerical">Clerical / Administrative</option>
										<option value="Medical">Medical</option>
										<option value="Legal">Legal</option>
										<option value="Pro">Professional / IT</option>
										<option value="Other">Other</option></select>
								  </div>	
									
									
									
									<div class="form-group">
										
										<label for="special">Special Instructions:</label>
											
											<textarea name="special" rows="2" class="form-control" id="special"></textarea>
										
									</div>
									
									
								
									 <input type="hidden" id="accountId" name="accountId" value="<?php echo $accountId; ?>" />
									 <input type="hidden" id="url" name="url" value="<?php echo $url; ?>" />
									 <input type="hidden" id="brandOrig" name="brandOrig" value="<?php echo $brandOrig; ?>" />
									 
								  
				
								   <div class="form-group">
								   <input type="submit" value="Submit"/>
								   
								   
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
		 2015 &copy; Innovar Media LLC. All Rights Reserved.
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