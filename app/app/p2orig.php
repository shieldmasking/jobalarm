<?php
session_start();
ini_set('display_errors',1);
include_once '../inc/class.db.php';
include_once '../inc/class.jatwitter.php';
include_once '../inc/config.php';


if (isset($_SESSION['candidate'])){
    $accountData = $_SESSION['candidate'];
}

if (isset($_REQUEST['app'])) {
    $first_name = isset($_REQUEST['contact_name']) ? $_REQUEST['contact_name'] : '';
	$brandOrig = isset($_REQUEST['brandOrig']) ? $_REQUEST['brandOrig'] : '';
	$location = isset($_REQUEST['location']) ? $_REQUEST['location'] : 0;
	$last_name = isset($_REQUEST['contact_lastname']) ? $_REQUEST['contact_lastname'] : '';
	$position = isset($_REQUEST['position']) ? $_REQUEST['position'] : '';
    $email = isset($_REQUEST['contact_email']) ? $_REQUEST['contact_email'] : '';
    $zip_code = isset($_REQUEST['zipcode']) ? $_REQUEST['zipcode'] : '';
	$zip_code = substr($zip_code,0,5);
	$accountId = isset($_REQUEST['accountId']) ? $_REQUEST['accountId'] : '';
	$url = isset($_REQUEST['url']) ? $_REQUEST['url'] : '';
	$mobile = isset($_REQUEST['mobile']) ? $_REQUEST['mobile'] : '';	
	$mobile2 = isset($_REQUEST['contact_mobile']) ? $_REQUEST['contact_mobile'] : '';
	if (strlen($mobile2)>=10){
		$mobile3 = preg_replace('/[^\dxX]/', '', $mobile2);
		$mobile = ltrim($mobile3,"1");
	}
	
}

//echo 'mobile'.$mobile;

if (!isset($_SESSION['candidate'])) {
	
$dbMobile = Config::get('db')->get_results("SELECT s.*, c.id as cid, x.id as xid, t.address as storeAddress from `sms_brand` s left outer join `candidate` as c on c.mobile ={$mobile} left outer join `candidateXref` as x on x.candidateId = c.id and x.brandOrig ={$brandOrig} left outer join `sms_stores` as t on t.id ={$location} where s.id ={$brandOrig}");
//$mobile = ($dbMobile[0]['mobile']) ? $dbMobile[0]['mobile'] : '';
$keyword = ($dbMobile[0]['keyword']) ? $dbMobile[0]['keyword'] : '';
//$brandOrig = ($dbMobile[0]['brandOrig']) ? $dbMobile[0]['brandOrig'] : 6;


$image = ($dbMobile[0]['storeImage']) ? $dbMobile[0]['storeImage'] : 'logo1.png';
$positions = ($dbMobile[0]['positions']) ? $dbMobile[0]['positions'] : '';
$storeBrand = ($dbMobile[0]['storeBrand']) ? $dbMobile[0]['storeBrand'] : '';
$brColor = ($dbMobile[0]['color']) ? $dbMobile[0]['color'] : '';
$candidateId = ($dbMobile[0]['cid']) ? $dbMobile[0]['cid'] : '';
$Xid = ($dbMobile[0]['xid']) ? $dbMobile[0]['xid'] : '';
$industry = ($dbMobile[0]['industry']) ? $dbMobile[0]['industry'] : '';
$address  = ($dbMobile[0]['storeAddress']) ? $dbMobile[0]['storeAddress'] : '';
//$_SESSION['candidate'] =$dbMobile[0];
} else {
$mobile = ($accountData['mobile']) ? $accountData['mobile'] : '';
$keyword = ($accountData['keyword']) ? $accountData['keyword'] : '';
$brandOrig = ($accountData['brandOrig']) ? $accountData['brandOrig'] : 6;
$image = ($accountData['storeImage']) ? $accountData['storeImage'] : '';
$positions = ($accountData['positions']) ? $accountData['positions'] : '';
$storeBrand = ($accountData['storeBrand']) ? $accountData['storeBrand'] : '';
$brColor = ($accountData['color']) ? $accountData['color'] : '';
$candidateId = ($accountData['id']) ? $accountData['id'] : '';
$Xid = ($accountData['xid']) ? $accountData['xid'] : '';
$industry = ($accountData['industry']) ? $accountData['industry'] : '';
}

$acctDb = Config::get('db')->get_results("SELECT * FROM `account` where `id`={$accountId}");

$minAge = $acctDb[0]['minAge'];
if (intval($accountId)==391){
$image = $acctDb[0]['logo'];
}

//echo "image: ".$image;


/////Test Data

	
	$bgColor = '';
	
	
	if (strlen($brColor)>0) {
	$bgColor = " style=\"background-color:#".$brColor."\"";	
}

//echo "m ".$mobile;
	
	if ($candidateId) {
		$updatedata = array(
			'first_name'=>$first_name,
			'last_name'=>$last_name,
			'email'=>$email
			);
		$updatewhere = array('id'=>$candidateId);
		Config::get('db')->update('candidate',$updatedata,$updatewhere);
		
	}else{
		$insertdata = array(
			'mobile'=>$mobile,
			'first_name'=>$first_name,
			'last_name'=>$last_name,
			'email'=>$email,
			'zip'=>$zip_code
            );
		Config::get('db')->insert('candidate',$insertdata);
		$candidateId = Config::get('db')->lastid();		
	}
		
	//echo "c ".$candidateId;
	if (!$Xid) {
		$insertdata = array(
			'promo'=>0,
			'candidateId'=>$candidateId,
			'accountId'=>$accountId,
			'brandId'=>$brandOrig,
			'brandOrig'=>$brandOrig,
			'keyword'=>$keyword,
			'keyword2'=>$keyword
            );
		Config::get('db')->insert('candidateXref',$insertdata);		
	}
	
	$insertdata = array(
			'candidateId'=>$candidateId,
			'accountId'=>$accountId,
			'brand'=>$brandOrig,
			'position'=>$position,
			'storeId'=>$location,
			'lastUrl'=>$url
			 );
		Config::get('db')->insert('candidateApply',$insertdata);
		$applyId = Config::get('db')->lastid();
		
	if ($position && $candidateId && $accountId){	
	Config::get('db')->query("update `candidateXref` set `job_type` = '{$position}' WHERE `candidateId`={$candidateId} AND `accountId`={$accountId}");
	}else{
		//do nothing;
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
								
								<form action="../app/p3.php" id="p2" name="p2" method="post">
								
								<input type="hidden" name="app" value="1" />	
														
									
<h4 class="form-section" style="text-align: center"><strong>Step 2:  Eligibility</strong></h4>
									
									
                
<?php if(intval($minAge)==21) { ?>
<div class="form-group">									
  <label><strong> Are you at least 21 years of age?</strong></label><br>
  <input type="radio" id="age21" name="age21" value="Yes" required> Yes &nbsp &nbsp
  <input type="radio" id="age21" name="age21" value="No"> No<br>
</div>
<?php }else{ ?>
<div class="form-group">									
  <label><strong> Are you at least 18 years of age?</strong></label><br>
  <input type="radio" id="underage" name="age" value="Yes" required> Yes &nbsp &nbsp
  <input type="radio" id="underageyes" name="age" value="No" data-id="<?php echo $applyId; ?>" data-toggle="modal" data-target="#underAge"> No<br>
</div>	
<?php } ?>
<div class="form-group">									
  <label><strong> Do you have reliable transportation?</strong></label><br>
  <input type="radio" id="trans" name="trans" value="Yes" required> Yes &nbsp &nbsp 
  <input type="radio" id="trans" name="trans" value="No"> No<br>
</div>	
<div class="form-group">									
  <label><strong> Are you legally eligible to work in the U.S?</strong></label><br>
  <input type="radio" id="legal" name="legal" value="Yes" required> Yes &nbsp &nbsp 
  <input type="radio" id="legal" name="legal" value="No"> No<br>
</div>
	
<label><strong> How much <?php echo $industry; ?> experience do you have?</strong></label>							
<div class="row">
 <div class="col-md-3">
<select id="experience" name="experience" type="select" class="form-control" placeholder="Select a job">

<option value="">Select....</option>
<option value="None">None</option>
<option value="Less than 1 year">Less than 1 year</option>
<option value="1 to 3 years">1 to 3 years</option>
<option value="3 to 5 years">3 to 5 years</option>
<option value="5 to 10 years">5 to 10 years</option>
<option value="10+ years">10+ years</option></select>
<br></div>
<div class="col-md-9"></div>
</div>

<label><strong> What is your highest level of education?</strong></label>							
<div class="row">
 <div class="col-md-3">
<select id="education" name="education" type="select" class="form-control" placeholder="Select a level">

<option value="">Select....</option>
<option value="Some HS">Some High School</option>
<option value="HS Grad or GED">HS Grad or GED</option>
<option value="Some College or Tech School">Some College or Tech School</option>
<option value="College or Tech School Grad">College or Tech School Grad</option>
<option value="Advanced Degree">Advanced Degree</option></select>
<br></div>
<div class="col-md-9"></div>
</div>

                                                                        
										<!--<input type="hidden" id="username" name="username" value="Jobalarm" />-->
									 <input type="hidden" id="aId" name="aId" value="<?php echo $applyId; ?>" />
									 <input type="hidden" id="cId" name="cId" value="<?php echo $candidateId; ?>" />
									 <input type="hidden" id="url" name="url" value="<?php echo $url; ?>" />
									 <input type="hidden" id="brandOrig" name="brandOrig" value="<?php echo $brandOrig; ?>" />
									 <input type="hidden" id="mobile" name="mobile" value="<?php echo $mobile; ?>" />
									 <input type="hidden" id="accountId" name="accountId" value="<?php echo $accountId; ?>" />
									 
								   <div class="form-group">
								   <input type="submit" value="Next"/>
								   
								 
								
							</div>
					</form>
							
						 
						  <!-- END FORM-->
													
						</div>
						</div>
					</div>
				




				<!-- END PAGE CONTENT INNER -->
			


<div class="modal fade" id="underAge" tabindex="-1" role="basic" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
											<h3 class="modal-title"><strong>- Eligibility</strong></h3>
										</div>
										<div id="permitBody" class="modal-body">
																		
										<div class="form-group">									
										  <strong> Can you provide a work permit?</strong><br>
										  <input type="radio" name="permit" id="permitYes" value="Yes"> Yes <br>
										  <input type="radio" name="permit" id="permitNo" value="No"> No<br>
										</div>	
										
										<div class="form-group">
										<!--<button type="button" class="btn blue pull-left" onClick="tj.dosomething()">Next</button>-->
										<!--<button type="submit" class="btn btn-success pull-right" id="optinsave">Save</button>-->
										<button id="applyModalSubmitButton" type="submit" class="btn btn-success pull-left">Next</button> <p class="help-block pull-left text-danger hide" id="form-error">&nbsp; The form is not valid. </p> &nbsp;&nbsp;<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
										
										</div>
										
				
										
									<!-- /.modal-content -->
								<div class="modal-footer">
																						
								</div>
								<!-- /.modal-dialog -->
							
			</div>							
</div>
							
</div>

</div>
<div class="modal fade" id="over18" tabindex="-1" role="basic" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
											<h3 class="modal-title"><strong>- Eligibility</strong></h3>
										</div>
										<div id="over18Body" class="modal-body">
																		
										<div class="form-group">									
										  <strong> Are you at least 18 years of age?</strong><br>
										  <input type="radio" name="over18yes" id="over18yes" value="Yes"> Yes <br>
										  <input type="radio" name="over18no" id="over18no" value="No"> No<br>
										</div>	
										
										<div class="form-group">
										<!--<button type="button" class="btn blue pull-left" onClick="tj.dosomething()">Next</button>-->
										<!--<button type="submit" class="btn btn-success pull-right" id="optinsave">Save</button>-->
										<button id="over18SubmitButton" type="submit" class="btn btn-success pull-left">Next</button> <p class="help-block pull-left text-danger hide" id="form-error">&nbsp; The form is not valid. </p> &nbsp;&nbsp;<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
										
										</div>
																			
									<!-- /.modal-content -->
								<div class="modal-footer">
																						
								</div>
								<!-- /.modal-dialog -->
							
			</div>							
</div>
							
</div>

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