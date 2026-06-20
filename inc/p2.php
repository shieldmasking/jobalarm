<?php
include_once '../inc/class.db.php';
include_once '../inc/class.jatwitter.php';
include_once '../inc/config.php';
include_once '../inc/pagination.class.php';

//require_once 'vendor/autoload.php';

/////Test Data
if (isset($_REQUEST['app'])) {
    //$user = isset($_REQUEST['for_company']) ? $_REQUEST['for_company'] : '';
	$accountId = isset($_REQUEST['accountId']) ? $_REQUEST['accountId'] : '';
	$keyword = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : '';
	$url = isset($_REQUEST['url']) ? $_REQUEST['url'] : '';
	$brandOrig = isset($_REQUEST['brand']) ? $_REQUEST['brand'] :6;
	$first_name = isset($_REQUEST['contact_name']) ? $_REQUEST['contact_name'] : '';
	$last_name = isset($_REQUEST['contact_lastname']) ? $_REQUEST['contact_lastname'] : '';
	$position = isset($_REQUEST['position']) ? $_REQUEST['position'] : '';
    $mobile1 = isset($_REQUEST['contact_mobile']) ? $_REQUEST['contact_mobile'] : '';
	$mobile = preg_replace('/[^\dxX]/', '', $mobile1);
	$mobile = ltrim($mobile,"1");
    $email = isset($_REQUEST['contact_email']) ? $_REQUEST['contact_email'] : '';
    $zip_code = isset($_REQUEST['zipcode']) ? $_REQUEST['zipcode'] : '';
	$zip_code = substr($zip_code,0,5);
	$image = isset($_REQUEST['image']) ? $_REQUEST['image'] : '';
	
	$cData = Config::get('db') -> get_results("SELECT c.id as cid, x.id as xid from `candidate` c left outer join `candidateXref` as x on x.candidateId=c.id and x.brandOrig={$brandOrig} where c.mobile ={$mobile}");
	$candidateId = $cData[0]['cid'];
	$Xid = $cData[0]['xid'];
	
	if ($candidateId) {
		$updatedata = array(
			'first_name'=>$first_name,
			'last_name'=>$last_name,
			'email'=>$email,
			'zip'=>$zip_code
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
			 );
		Config::get('db')->insert('candidateApply',$insertdata);
		$applyId = Config::get('db')->lastid();
	
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
			   				 
				if (strlen($image) > 0) {
				
			        echo '<img src="../img/'.$image.'" />';   
			    } else { 
			?>
				<a href="index.php"><img src="../img/logo1.png" longdesc="http://www.jobalarm.com"></a>			
			<?php } ?>
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
									
									
                

<div class="form-group">									
  <strong> Are you at least 18 years of age?</strong><br>
  <input type="radio" id="age" name="age" value="Yes"> Yes &nbsp &nbsp
  <input type="radio" id="age" name="age" value="No" data-id="<?php echo $applyId; ?>" data-toggle="modal" data-target="#underAge"> No<br>
</div>	
<div class="form-group">									
  <strong> Do you have reliable transportation?</strong><br>
  <input type="radio" id="trans" name="trans" value="Yes"> Yes &nbsp &nbsp 
  <input type="radio" id="trans" name="trans" value="No"> No<br>
</div>	
<div class="form-group">									
  <strong> Are you legally eligible to work in the U.S.A?</strong><br>
  <input type="radio" id="legal" name="legal" value="Yes"> Yes &nbsp &nbsp 
  <input type="radio" id="legal" name="legal" value="No"> No<br>
</div>
<div class="form-group">	
<strong> How much restaurant experience do you have?</strong><br>								
<select id="experience" name="experience" type="select" class="form-control" placeholder="Select a job">

<option value="">Select....</option>
<option value="None">None</option>
<option value="Less than 1 year">Less than 1 year</option>
<option value="1 to 3 years">1 to 3 years</option>
<option value="3 to 5 years">3 to 5 years</option>
<option value="5 to 10 years">5 to 10 years</option>
<option value="10+ years">10+ years</option></select>
</div>
<div class="form-group">									
  <strong> What are your minimum expected earnings?</strong><br>
  <input id="amount" name="amount" type="text" class="form-control">
  <input type="radio" id="per" name="per" value="hour"> Per Hour
  <input type="radio" id="per" name="per" value="year"> Per Year<br>
</div>                                       
                                        
										<!--<input type="hidden" id="username" name="username" value="Jobalarm" />-->
									 <input type="hidden" id="aId" name="aId" value="<?php echo $applyId; ?>" />
									 <input type="hidden" id="cId" name="cId" value="<?php echo $candidateId; ?>" />
									 <input type="hidden" id="url" name="url" value="<?php echo $url; ?>" />
									 <input type="hidden" id="image" name="image" value="<?php echo $image ?>" />
									 
									 
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
										  <strong> Do you have a work permit?</strong><br>
										  <input type="radio" name="permit" id="permit" value="Yes"> Yes <br>
										  <input type="radio" name="permit" id="permit" value="No"> No<br>
										</div>	
										
										<div class="form-group">
										<!--<button type="button" class="btn blue pull-left" onClick="tj.dosomething()">Next</button>-->
										<!--<button type="submit" class="btn btn-success pull-right" id="optinsave">Save</button>-->
										<div class="form-group"><button id="applyModalSubmitButton" type="submit" class="btn btn-success pull-left">Next</button> <p class="help-block pull-left text-danger hide" id="form-error">&nbsp; The form is not valid. </p> &nbsp;&nbsp;<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
										
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

<!-- BEGIN FOOTER -->

<div class="page-footer">
	<div class="container">
		 2015 &copy; Innovar Media LLC. All Rights Reserved.
	</div>
</div>
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