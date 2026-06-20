<?php
include_once '../inc/class.db.php';
include_once '../inc/class.jatwitter.php';
include_once '../inc/config.php';


if (isset($_REQUEST['app'])) {
    $applyId = isset($_REQUEST['aId']) ? $_REQUEST['aId'] : '';
	$education = isset($_REQUEST['education']) ? $_REQUEST['education'] : '';
	$url = isset($_REQUEST['url']) ? $_REQUEST['url'] : '';
	$trans = isset($_REQUEST['trans']) ? $_REQUEST['trans'] : '';
	$age18 = isset($_REQUEST['underage']) ? $_REQUEST['underage'] : '';
	$age21 = isset($_REQUEST['age21']) ? $_REQUEST['age21'] : '';
	$legal = isset($_REQUEST['legal']) ? $_REQUEST['legal'] : '';
	$brandOrig = isset($_REQUEST['brandOrig']) ? $_REQUEST['brandOrig'] : '';
	$candidateId = isset($_REQUEST['candidateId']) ? $_REQUEST['candidateId'] : '';
	$experience = isset($_REQUEST['experience']) ? $_REQUEST['experience'] : '';
	$amount = isset($_REQUEST['amount']) ? $_REQUEST['amount'] : '';
	$accountId = isset($_REQUEST['accountId']) ? $_REQUEST['accountId'] : '';
	$image = isset($_REQUEST['storeimage']) ? $_REQUEST['storeimage'] : 'logo1.png';
	$brColor = isset($_REQUEST['brColor']) ? $_REQUEST['brColor'] : '';
	$location = isset($_REQUEST['location']) ? $_REQUEST['location'] : '';
	
}
//echo 'apply: ' . $applyId;
//echo 'candidate: ' . $candidateId;
//echo 'acct: ' . $accountId;


/////Test Data

	
	$bgColor = '';
	$yes = "YES";
	
	if (strlen($brColor)>0) {
	$bgColor = " style=\"background-color:#".$brColor."\"";	
	}
	
	if ($applyId) {
		$updatedata = array(
			'trans'=>$trans,
			'legal'=>$legal,
			'experience'=>$experience,
			'education'=>$education,
			'age'=>$age18,
			'over21'=>$age21
			);
		$updatewhere = array('id'=>$applyId);
		Config::get('db')->update('candidateApply',$updatedata,$updatewhere);
		
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
<!-- END HEADER -->
<!-- BEGIN PAGE CONTENT -->
<div class="page-content">
	<div class="container">

			<div class="portlet light">
			<div class="portlet-body">
				<div class="row">
						  <div class="col-md-12">
								<!-- BEGIN FORM-->
								<form action="../app/p4.php" id="p3" name="p3" method="post">
								
								<input type="hidden" name="app" value="1" />
								
                           				
														
									
														
<h4 class="form-section" style="text-align: center"><strong>Step 3:  Employment</strong></h4>
									
									
            

									<div class="form-group">									
  <strong> Are you currently employed?</strong><br>
  <input type="radio" id="currentEmpYes" name="currentEmp" value="Yes" data-id="<?php echo $applyId; ?>" data-toggle="modal" data-target="#currentEmpModal" required> Yes &nbsp &nbsp
	<input type="radio" id="currentEmp" name="currentEmp" value="No"> No
  </div>	
  <div class="form-group">									
  <strong> Were you employed previously?</strong><br>
  <input type="radio" id="pastEmpYes" name="pastEmp" value="Yes" data-id="<?php echo $applyId; ?>" data-toggle="modal" data-target="#pastEmpModal"> Yes &nbsp &nbsp
	<input type="radio" id="pastEmp" name="pastEmp" value="No"> No
  </div>	
  <div class="form-group">	
<strong> What type of employment do you desire?</strong><br>								
<select id="jobType" name="jobType" type="select" class="form-control" placeholder="Select a job" required>
<option value="">Select....</option>
<option value="PT">Part Time</option>
<option value="FT">Full Time</option></select>
</div>

<?php if ($brandOrig != 93) { ?> 

<div class="form-group">									
  <strong> Can you work a flexible schedule including nights and/or weekends?</strong><br>
  <input type="radio" id="schedule" name="schedule" value="Yes" required> Yes &nbsp &nbsp
  <input type="radio" id="scheduleNo" name="schedule" value="No" data-id="<?php echo $applyId; ?>" data-toggle="modal" data-target="#scheduleModal"> No
  </div>
 
<?php }else{ ?> 
 <div class="form-group">									
  <strong> Job Preference</strong><br>
  <input type="checkbox" id="liPref" name="liPref" data-id="<?php echo $applyId; ?>" data-toggle="modal" data-target="#liModal"> Light Industrial/General Labor &nbsp
  <input type="checkbox" id="clericalPref" name="clericalPref" data-id="<?php echo $applyId; ?>" data-toggle="modal" data-target="#clericalModal"> Clerical </br>
  <input type="checkbox" id="medicalPref" name="medicalPref" data-id="<?php echo $applyId; ?>" data-toggle="modal" data-target="#medicalModal"> Medical &nbsp
  <input type="checkbox" id="legalPref" name="legalPref" data-id="<?php echo $applyId; ?>" data-toggle="modal" data-target="#legalModal"> Legal &nbsp
  <input type="checkbox" id="proPref" name="proPref" data-id="<?php echo $applyId; ?>" data-toggle="modal" data-target="#proModal"> Professional/IT
  </div>
<?php } ?> 
 <!--<label><strong> What minimum wage do you expect?</strong></label>
<div class="row">
 <div class="col-md-1">
<input id="amount" name="amount" type="text" class="form-control">
</div>
<div class="col-md-2">
<select id="per" name="per" type="select" class="form-control" required>
<option value="Per Hour">Per Hour</option>
<option value="Per Year">Per Year</option></select>
</div>
<div class="col-md-9"></div>
</div> --> 


									
								
								
                                        
                                        
										<!--<input type="hidden" id="username" name="username" value="Jobalarm" />-->
									 <input type="hidden" id="aId" name="aId" value="<?php echo $applyId; ?>" />
									 <input type="hidden" id="url" name="url" value="<?php echo $url; ?>" />
									 <input type="hidden" id="brandOrig" name="brandOrig" value="<?php echo $brandOrig; ?>" />
									 <input type="hidden" id="accountId" name="accountId" value="<?php echo $accountId; ?>" />
									<input type="hidden" id="storeimage" name="storeimage" value="<?php echo $image; ?>" />
									 <input type="hidden" id="brColor" name="brColor" value="<?php echo $brColor; ?>" />
									 <input type="hidden" id="location" name="location" value="<?php echo $location; ?>" />
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
			




				<!-- END PAGE CONTENT INNER -->
				
<div class="modal fade" id="currentEmpModal" tabindex="-1" role="basic" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
											<h3 class="modal-title"><strong>- Current Employer</strong></h3>
										</div>
										<div id="currentEmpBody" class="modal-body">
										<div class="form-group">
										<strong> Where do you currently work?</strong><br>
										<input id="current" name="current" type="text" class="form-control" placeholder="Name of Current Employer" required>
										</div>	
										<div class="form-group">
										<strong> How long have you worked there?</strong><br>
										<input id="long" name="long" type="text" class="form-control" placeholder="Ex. 6 months, 2yrs, etc." required>
										</div>
										<div class="form-group">									
										  <strong> Can we call this Employer for a reference?</strong><br>
										  <input type="radio" name="reference" id="referenceYes" value="Yes"> Yes<br> 
										  <input type="radio" name="reference" id="referenceNo" value="No"> No<br>
										</div>	
										
										<div class="form-group">
										<button id="applyModalSubmitButton2" type="submit" class="btn btn-success pull-left">Next</button> <p class="help-block pull-left text-danger hide" id="form-error">&nbsp; The form is not valid. </p> &nbsp;&nbsp;<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
										<!--<button type="submit" class="btn btn-success pull-right" id="optinsave">Save</button>-->
										<!--<button type="button" class="btn default pull-left" data-dismiss="modal">Close</button>-->
										
										</div>
										
				
										
									<!-- /.modal-content -->
								<div class="modal-footer">
																						
								</div>
								<!-- /.modal-dialog -->
							
			</div>							
</div>
							
</div>
</div>

<div class="modal fade" id="pastEmpModal" tabindex="-1" role="basic" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
											<h3 class="modal-title"><strong>- Previous Employer</strong></h3>
										</div>
										<div id="pastEmpBody" class="modal-body">
										<div class="form-group">
										<strong> Where do you currently work?</strong><br>
											<input id="previous" name="previous" type="text" class="form-control" placeholder="Name of Previous Employer" required>
										</div>	
										<div class="form-group">
										<strong> How long have you worked there?</strong><br>
										<input id="pastLong" name="pastLong" type="text" class="form-control" placeholder="Ex. 6 months, 2yrs, etc." required>
										</div>
										<div class="form-group">									
										  <strong> Can we call this Employer for a reference?</strong><br>
										  <input type="radio" name="pastReference" id="pastReferenceYes" value="Yes"> Yes<br> 
										  <input type="radio" name="pastReference" id="pastReferenceNo" value="No"> No<br>
										</div>	
										
										<div class="form-group">
										<button id="applyModalSubmitButton3" type="submit" class="btn btn-success pull-left">Next</button> <p class="help-block pull-left text-danger hide" id="form-error">&nbsp; The form is not valid. </p> &nbsp;&nbsp;<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
										<!--<button type="submit" class="btn btn-success pull-right" id="optinsave">Save</button>-->
										<!--<button type="button" class="btn default pull-left" data-dismiss="modal">Close</button>-->
										
										</div>
										
				
										
									<!-- /.modal-content -->
								<div class="modal-footer">
																						
								</div>
								<!-- /.modal-dialog -->
							
			</div>							
</div>
							
</div>
</div>


<div class="modal fade" id="scheduleModal" tabindex="-1" role="basic" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
											<h3 class="modal-title"><strong>- Schedule Preferences</strong></h3>
										</div>
										<div id="scheduleBody" class="modal-body">
										<div class="form-group">	
										<strong> When do you prefer to work (first choice)?</strong><br>								
										<select id="prefer1" name="prefer1" type="select" class="form-control">
										<option value="">Select....</option>
										<option value="Mornings">Morning</option>
										<option value="Afternoon">Afternoon</option>
										<option value="Evening">Evening</option>
										<option value="Over Night">Over Night</option></select>
										</div>
										
										<div class="form-group">	
										<strong> When do you prefer to work (second choice)?</strong><br>								
										<select id="prefer2" name="prefer2" type="select" class="form-control">
										<option value="">Select....</option>
										<option value="Mornings">Morning</option>
										<option value="Afternoon">Afternoon</option>
										<option value="Evening">Evening</option>
										<option value="Over Night">Over Night</option></select>
										</div>
										
										<div class="form-group">	
										<strong> Which days do you prefer to work?</strong><br>								
										<select multiple="multiple" id="day" name="day" class="form-control">
										<option value="">Select Multiple....</option>
										<option value="Sunday">Sunday</option>
										<option value="Monday">Monday</option>
										<option value="Tuesday">Tuesday</option>
										<option value="Wednesday">Wednesday</option>
										<option value="Thursday">Thursday</option>
										<option value="Friday">Friday</option>
										<option value="Saturday">Saturday</option></select>
										</div>
											
											
										<!--<div class="form-group">
										<p>*Subject to JobAlarm <a href="/terms" target="_blank"> Terms & Conditions.</a> </p>
										</div>-->
										<div class="form-group">
										<button id="applyModalSubmitButton4" type="submit" class="btn btn-success pull-left">Next</button> <p class="help-block pull-left text-danger hide" id="form-error">&nbsp; The form is not valid. </p> &nbsp;&nbsp;<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
										<!--<button type="submit" class="btn btn-success pull-right" id="optinsave">Save</button>-->
										<!--<button type="button" class="btn default pull-left" data-dismiss="modal">Close</button>-->
										
										</div>
										
				
										
									<!-- /.modal-content -->
								<div class="modal-footer">
																						
								</div>
								<!-- /.modal-dialog -->
							
			</div>							
</div>
							
</div>
	</div>

	<div class="modal fade" id="liModal" tabindex="-1" role="basic" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
											<h3 class="modal-title"><strong>- Job Preference</strong></h3>
										</div>
										<div id="liBody" class="modal-body">
																				
										<div class="form-group">	
										<strong> Light Industrial (Skilled & Unskilled)</strong><br>								
										<select multiple="multiple" id="type3" name="type3" class="form-control">
										<option value="">Select Multiple....</option>
										<option value="Warehouse">Warehouse</option>
										<option value="General Labor">General Labor / Assembly</option>
										<option value="Packing">Packing</option>
										<option value="Shipping / Receiving">Shipping / Receiving</option>
										<option value="Forklift Drive">Forklift Drive</option>
										<option value="Maintenance Mechanic">Maintenance Mechanic</option>
										<option value="Welder">Welder</option></select>
										</div>
										
																			
										<!--<div class="form-group">
										<p>*Subject to JobAlarm <a href="/terms" target="_blank"> Terms & Conditions.</a> </p>
										</div>-->
										<div class="form-group">
										<button id="liButton" type="submit" class="btn btn-success pull-left">Next</button> <p class="help-block pull-left text-danger hide" id="form-error">&nbsp; The form is not valid. </p> &nbsp;&nbsp;<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
										<!--<button type="submit" class="btn btn-success pull-right" id="optinsave">Save</button>-->
										<!--<button type="button" class="btn default pull-left" data-dismiss="modal">Close</button>-->
										
										</div>
										
				
										
									<!-- /.modal-content -->
								<div class="modal-footer">
																						
								</div>
								<!-- /.modal-dialog -->
							
			</div>							
</div>
							
</div>
	</div>

		<div class="modal fade" id="clericalModal" tabindex="-1" role="basic" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
											<h3 class="modal-title"><strong>- Job Preference</strong></h3>
										</div>
										<div id="clericalBody" class="modal-body">
																				
												
										<div class="form-group">	
										<strong> Clerical</strong><br>								
										<select multiple="multiple" id="type4" name="type4" class="form-control">
										<option value="">Select Multiple....</option>
										<option value="Administrative Assistant">Administrative Assistant</option>
										<option value="Executive Assistant">Executive Assistant</option>
										<option value="Receptionist">Receptionist</option>
										<option value="Data Entry Clerk">Data Entry Clerk</option>
										<option value="AP/AR Clerk">AP/AR Clerk</option>
										<option value="Office Support">Other Office Support Staff</option></select>
										</div>
										
																				
											
										<!--<div class="form-group">
										<p>*Subject to JobAlarm <a href="/terms" target="_blank"> Terms & Conditions.</a> </p>
										</div>-->
										<div class="form-group">
										<button id="clericalButton" type="submit" class="btn btn-success pull-left">Next</button> <p class="help-block pull-left text-danger hide" id="form-error">&nbsp; The form is not valid. </p> &nbsp;&nbsp;<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
										<!--<button type="submit" class="btn btn-success pull-right" id="optinsave">Save</button>-->
										<!--<button type="button" class="btn default pull-left" data-dismiss="modal">Close</button>-->
										
										</div>
										
				
										
									<!-- /.modal-content -->
								<div class="modal-footer">
																						
								</div>
								<!-- /.modal-dialog -->
							
			</div>							
</div>
							
</div>
	</div>
	
	<div class="modal fade" id="medicalModal" tabindex="-1" role="basic" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
											<h3 class="modal-title"><strong>- Job Preference</strong></h3>
										</div>
										<div id="medicalBody" class="modal-body">
																				
										<div class="form-group">	
										<strong> Medical</strong><br>								
										<select multiple="multiple" id="type5" name="type5" class="form-control">
										<option value="">Select Multiple....</option>
										<option value="Front Office">Front Office</option>
										<option value="Medical Secretary">Medical Secretary</option>
										<option value="Office Administrator">Office Administrator</option>
										<option value="Medical Assistant / Back Office">Medical Assistant / Back Office</option>
										<option value="Biller">Biller</option>
										<option value="Coder">Coder</option>
										<option value="Medical Records">Medical Records</option>
										<option value="Transcriptionist">Transcriptionist</option></select>
										</div>
										
																				
											
										<!--<div class="form-group">
										<p>*Subject to JobAlarm <a href="/terms" target="_blank"> Terms & Conditions.</a> </p>
										</div>-->
										<div class="form-group">
										<button id="medicalButton" type="submit" class="btn btn-success pull-left">Next</button> <p class="help-block pull-left text-danger hide" id="form-error">&nbsp; The form is not valid. </p> &nbsp;&nbsp;<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
										<!--<button type="submit" class="btn btn-success pull-right" id="optinsave">Save</button>-->
										<!--<button type="button" class="btn default pull-left" data-dismiss="modal">Close</button>-->
										
										</div>
										
				
										
									<!-- /.modal-content -->
								<div class="modal-footer">
																						
								</div>
								<!-- /.modal-dialog -->
							
			</div>							
</div>
							
</div>
	</div>
	
	<div class="modal fade" id="legalModal" tabindex="-1" role="basic" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
											<h3 class="modal-title"><strong>- Job Preference</strong></h3>
										</div>
										<div id="legalBody" class="modal-body">
																				
										<div class="form-group">	
										<strong> Legal</strong><br>								
										<select multiple="multiple" id="type6" name="type6" class="form-control">
										<option value="">Select Multiple....</option>
										<option value="Legal Assistant">Legal Assistant</option>
										<option value="Paralegal">Paralegal</option>
										<option value="Office Administrator">Office Administrator</option>
										<option value="Courier/Runner">Courier/Runner</option>
										<option value="Legal Support Staff">Other Legal Support Staff</option></select>
										</div>
										
																
											
										<!--<div class="form-group">
										<p>*Subject to JobAlarm <a href="/terms" target="_blank"> Terms & Conditions.</a> </p>
										</div>-->
										<div class="form-group">
										<button id="legalButton" type="submit" class="btn btn-success pull-left">Next</button> <p class="help-block pull-left text-danger hide" id="form-error">&nbsp; The form is not valid. </p> &nbsp;&nbsp;<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
										<!--<button type="submit" class="btn btn-success pull-right" id="optinsave">Save</button>-->
										<!--<button type="button" class="btn default pull-left" data-dismiss="modal">Close</button>-->
										
										</div>
										
				
										
									<!-- /.modal-content -->
								<div class="modal-footer">
																						
								</div>
								<!-- /.modal-dialog -->
							
			</div>							
</div>
							
</div>
	</div>

		<div class="modal fade" id="proModal" tabindex="-1" role="basic" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
											<h3 class="modal-title"><strong>- Job Preference</strong></h3>
										</div>
										<div id="proBody" class="modal-body">
																				
										<div class="form-group">	
										<strong> Professional</strong><br>								
										<select multiple="multiple" id="type7" name="type7" class="form-control">
										<option value="">Select Multiple....</option>
										<option value="Legal Assistant">Administration</option>
										<option value="Paralegal">Accounting</option>
										<option value="Office Administrator">Outside Sales</option>
										<option value="Courier/Runner">IT</option></select>
										</div>
											
											
										<!--<div class="form-group">
										<p>*Subject to JobAlarm <a href="/terms" target="_blank"> Terms & Conditions.</a> </p>
										</div>-->
										<div class="form-group">
										<button id="proButton" type="submit" class="btn btn-success pull-left">Next</button> <p class="help-block pull-left text-danger hide" id="form-error">&nbsp; The form is not valid. </p> &nbsp;&nbsp;<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
										<!--<button type="submit" class="btn btn-success pull-right" id="optinsave">Save</button>-->
										<!--<button type="button" class="btn default pull-left" data-dismiss="modal">Close</button>-->
										
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