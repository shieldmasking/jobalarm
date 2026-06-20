<?php
session_start();
ini_set('display_errors',1);
include_once '../inc/class.db.php';
include_once '../inc/class.jatwitter.php';
include_once '../inc/config.php';

if (!isset($_SESSION['profile'])) {
    header('location: ../mobile/index.php');
}

$account_data = $_SESSION['profile'];
if (!isset($account_data['mobile'])) {
	header('location: ../mobile/index.php');
	exit();
}

$dbCandidate = Config::get('db')->get_results("select c.*, ce.city, ce.state_code from `candidate` c left join `cities_extended` as ce on ce.zip = c.zip where c.id={$account_data['id']}");
$dbData = $dbCandidate[0];	
	if ($dbData) {
	$mobile = $dbData['mobile'];
	$target = "../resumes/".$dbData['id']."/".$dbData['resume_file'];
	if (isset($_REQUEST['update_profile'])) {
    
	$candidateId = $dbData['id'];
	$first_name = isset($_REQUEST['contact_name']) ? $_REQUEST['contact_name'] : '';
    $last_name = isset($_REQUEST['contact_lastname']) ? $_REQUEST['contact_lastname'] : '';
    //$mobile1 = isset($_REQUEST['contact_mobile']) ? $_REQUEST['contact_mobile'] : '';
	//$mobile = preg_replace('/[^\dxX]/', '', $mobile1);
    $email = isset($_REQUEST['contact_email']) ? $_REQUEST['contact_email'] : '';
    $zip_code = isset($_REQUEST['zipcode']) ? $_REQUEST['zipcode'] : '';
	$zip_code = intval(substr($zip_code,0,5));
	$resume_paste = isset($_REQUEST['resume_paste']) ? $_REQUEST['resume_paste'] : '';
	
	$resume_filename = '';
    if ($_FILES['resume_file']) {
        $target_dir = "../resumes/".$candidateId."/";
		if (!file_exists($target_dir)) {
		mkdir($target_dir, 0777, true);
		}
		$resume_filename = basename($_FILES["resume_file"]["name"]);
        $target_file = $target_dir . basename($_FILES["resume_file"]["name"]);
        move_uploaded_file($_FILES["resume_file"]["tmp_name"], $target_file);
    }
	
	if ($first_name || $last_name || $email || $zip_code || $resume_paste){
		$updateData = array(
		'first_name'=>$first_name,
		'last_name'=>$last_name,
		'email'=>$email,
		'zip'=>$zip_code,
		'resume'=>$resume_paste,
		'resume_file'=>$resume_filename
		);
		$updateWhere = array('id'=>$candidateId);
		Config::get('db')->update('candidate',$updateData,$updateWhere);
	 	echo "<meta http-equiv='refresh' content='0'>";
	}
	}
}	

//$dbData = Config::get('db')->get_results("select c.*, x.promo, x.brandId, x.brandOrig from `candidate` c left join `candidateXref` as x on x.candidateId=c.id where c.id=".$account_data['id']);

?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8" />
    <title>JobAlarm | My Profile</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link href="../theme/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="../theme/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
    <link href="../theme/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../theme/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE STYLES -->
    <link href="../theme/assets/admin/pages/css/tasks.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="../theme/assets/admin/pages/css/todo.css" />
    <link rel="stylesheet" type="text/css" href="../theme/assets/global/plugins/select2/select2.css"/>
    <link rel="stylesheet" type="text/css" href="../theme/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
    <link rel="stylesheet" type="text/css" href="../theme/assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
    <link rel="stylesheet" type="text/css" href="../theme/assets/global/plugins/jquery-nestable/jquery.nestable.css"/>
    <link rel="stylesheet" type="text/css" href="../theme/assets/global/css/bootstrap-duallistbox.css" />
    <!-- END PAGE STYLES -->
    <!-- BEGIN THEME STYLES -->
    <link href="../theme/assets/global/css/components-rounded.css" id="style_components" rel="stylesheet" type="text/css" />
    <link href="../theme/assets/global/css/plugins.css" rel="stylesheet" type="text/css" />
    <link href="../theme/assets/admin/layout3/css/layout.css" rel="stylesheet" type="text/css" />
    <link href="../theme/assets/admin/layout3/css/themes/blue-steel.css" rel="stylesheet" type="text/css" id="style_color" />
    <link href="../theme/assets/admin/layout3/css/custom.css" rel="stylesheet" type="text/css" />
    <!-- END THEME STYLES -->
    <link rel="shortcut icon" href="../favicon.ico" />
    <style type="text/css">
        .modal-backdrop {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 0;
            background-color: #000;
        }
        .dd3-content,.dd-handle {
            height:60px;
        }
        .dd-handle {
            line-height:48px;
        }
        .dd-item {
            line-height:15px;
        }
        
    </style>
<style type="text/css">
#datatableAccount_ajax_length,
#dataTableCompany_ajax_length,
#datatableAccount_ajax_filter,
#dataTableCompany_ajax_filter
 {
	display:none;
}
</style>
<style>
lable.form-control {
text-align:right;
width: 100px;
float:left;
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
                
                    <a href="../index.php">
                        <img src="../img/logo1.png" alt="logo" class="logo-default" />
                    </a>
                
                <!-- END LOGO -->
                <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                <!--<a href="javascript:;" class="menu-toggler"></a> -->
                <!-- END RESPONSIVE MENU TOGGLER -->
            </div>
        </div>
        <!-- END HEADER TOP -->
        <!-- BEGIN HEADER MENU -->
        <div class="page-header-menu" style="background-color:#444d58">
            <div class="container">
                <h1 style="width:340px;float:left;margin:0;padding:5px;color:white">My Account</h1>
                <!-- BEGIN MEGA MENU -->
                <!-- DOC: Apply "hor-menu-light" class after the "hor-menu" class below to have a horizontal menu with white background -->
                <!-- DOC: Remove data-hover="dropdown" and data-close-others="true" attributes below to disable the dropdown opening on mouse hover -->
                
            <a class="btn blue pull-right margin-top-10" href="../logout.php?a=1"><i class="fa fa-sign-out"></i>Sign Out</a>
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
                    <li class="active">
                        My Account
                    </li>
                </ul>
				<!-- END PAGE BREADCRUMB -->
		<!-- BEGIN PAGE CONTENT INNER -->
		<div class="col-md-6 col-sm-6" id="details">
				<div class="portlet light ">
            	<div class="portlet-title">
                <div class="caption caption-md">
				<i class="icon-bar-chart theme-font hide"></i>
                                    <span class="caption-subject theme-font bold uppercase">My Details</span>
				</div>
				</div>
				
				
				
	<!-- BEGIN FORM-->
	
                          <form action="accounts.php" id="account" name="account" method="post"  enctype="multipart/form-data" style="max-width:500px">

                            <input type="hidden" name="update_profile" value="1" />
                             
							 <div class="form-group col-sm-5">    
								<label for="contact_email">First Name:</label>
								<input id="contact_name" border="0" name="contact_name" type="text" class="form-control"  placeholder="First Name" value="<?php echo $dbData['first_name'];?>">
                           	</div>
							
							<div class="form-group col-sm-7">
							<label for="contact_email">Last Name:</label>
							<input id="contact_lastname" border="0" name="contact_lastname" type="text" class="form-control" placeholder="Last Name" value="<?php echo $dbData['last_name'];?>">
                            </div>
							
							<div class="form-group col-sm-7"> 
                            <label for="contact_email">Email:</label>
                            <input id="contact_email" name="contact_email" type="text" size="30" class="form-control" placeholder="Email Address" value="<?php echo $dbData['email'];?>">
                            </div>
							
							<div class="form-group col-sm-5"> 
                                  <label for="contact_mobile">Mobile Number:</label>
								  <input id="contact_mobile" name="contact_mobile" type="text" cols="15" class="form-control" placeholder="Mobile Number" value="<?php echo $dbData['mobile'];?>" disabled>
                              </div>
							  
							  <div class="form-group col-sm-5"> 
                                  <label for="city">City:</label>
								  <input id="city" name="city" type="text" class="form-control" value="<?php echo $dbData['city'];?>" disabled>
                              </div>
							  
							  <div class="form-group col-sm-4"> 
                                  <label for="state">State:</label>
								  <input id="state" name="state" type="text" class="form-control" value="<?php echo $dbData['state_code'];?>" disabled>
                              </div>
							
							<div class="form-group col-sm-3"> 
                                  <label for="zipcode">Zip Code:</label>
								  <input id="zipcode" name="zipcode" type="text" maxlength="5" class="form-control" placeholder="Zip Code" value="<?php echo $dbData['zip'];?>">
                              </div>
							
							<div class="form-group col-sm-12"> 
                                <label for="resume_paste">Resume Text:</label>
                                <textarea class="form-control" rows="8" cols="20" id="resume_paste" name="resume_paste" placeholder="Copy & Paste your resume or Input skills here..."><?php echo $dbData['resume'];?></textarea>
                            </div>
							
							<div class="form-group col-sm-6"> 
                                <label for="resume_file">Resume:</label> 
								<a href="<?php echo $target;?>"><?php echo $dbData['resume_file'];?></a>
                                <input class="form-control-file" type="file" id="resume_file" name="resume_file"/>
                                                                        
                                <div class="pull-right">                           
                            <input name="submit" type="submit" value="Update" onclick="submitForm()" />
                          </div>
						  </div>
						  
						  <!--</div>-->
						  </form>
	
		</div>
     </div>
		
		 
			<div class="col-md-6 col-sm-6" id="subscriptions">
				<div class="portlet light ">
            	<div class="portlet-title">
                <div class="caption caption-md">
				<i class="icon-bar-chart theme-font hide"></i>
                                    <span class="caption-subject theme-font bold uppercase">My SMS Subscriptions</span>
                                    </div>  
				</div>
				                           
            
            
			
			<div class="portlet-body">
			

	<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#my_jobs">Active</a></li>
    <li><a data-toggle="tab" href="#joinCo">Add </a></li>
	</ul>
    
	<div class="tab-content">
			<div id="my_jobs" class="tab-pane fade in active">
			<table class="table table-striped table-bordered table-hover" id="datatableAccount_ajax">								   			<thead>	
			<tr role="row" class="heading">
			<th>Company</th>
			<th>Subscribed</th>
			<th>Types</th>
			<!--<th>Count</th>-->
			<th>Actions</th></tr>
			</thead>
			<tbody id="maTweetBody"></tbody>
			</table>
				</div>
					<div id="joinCo" class="tab-pane fade">
					<table class="table table-striped table-bordered table-hover stripe" id="dataTableCompany_ajax">
					<thead>
					<tr role="row" class="heading">
					<th>Company</th>
					<th>About</th>
					<th>Subscribe</th>
					</tr>
					</thead>
					<tbody id="fcBody"></tbody>
					</table>
	
						</div>		
						</div>	
				
				</div>				  
			</div>
			</div>
			
    								
		<!-- END PAGE CONTENT INNER -->
	</div>
	
</div>
<!-- END PAGE CONTENT -->
</div>
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
<script src="../theme/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="../theme/assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="../theme/assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="../theme/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script type="text/javascript" src="../theme/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../theme/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
<script src="../theme/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="../theme/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="../theme/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="../theme/assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="../theme/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="../theme/assets/global/plugins/bootbox/bootbox.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE PLUGINS & SCRIPTS -->
<script type="text/javascript" src="../theme/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="../theme/assets/global/plugins/select2/select2.min.js"></script>
<script src="../../theme/assets/admin/pages/scripts/todo.js" type="text/javascript"></script>
<script src="../theme/assets/admin/pages/scripts/index3.js" type="text/javascript"></script>
<script src="../theme/assets/admin/pages/scripts/tasks.js" type="text/javascript"></script>
<script src="../theme/assets/admin/pages/scripts/table-managed.js"></script>
<script src="../theme/assets/global/scripts/datatable.js"></script>
<script src="../theme/assets/admin/pages/scripts/table-ajax.js"></script>

<!-- END PAGE PLUGINS & SCRIPTS -->
<script src="../theme/assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="../theme/assets/admin/layout3/scripts/layout.js" type="text/javascript"></script>
<script src="../theme/assets/admin/layout3/scripts/demo.js" type="text/javascript"></script>
<script src="../theme/assets/admin/pages/scripts/contact-us.js"></script>
<script src="../inc/tweetedjobs-mainTest.js" type="text/javascript"></script>
<script>
jQuery(document).ready(function() {    
Metronic.init(); // init metronic core components
Layout.init(); // init current layout
Demo.init(); // init demo features
Todo.init(); // init todo page
tj.alex.initializeAccountGrid();
});

function submitForm() {
   // Get the first form with the name
   // Hopefully there is only one, but there are more, select the correct index
   var frm = document.getElementsByName('account')[0];
   frm.submit(); // Submit
   frm.reset();  // Reset
   return false; // Prevent page refresh
}
</script>

<!-- END JAVASCRIPTS -->
<div class="modal fade" id="editSMS" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                      <strong><h3 class="modal-title">- Text Messaging (Opt-In)</h3></strong>
                </div>
                <div id="supportBody1" class="modal-body">
                    <div class="form-group">
                	<strong>Job-Related Text Messages</strong>
					</div>
					<div class="form-group">
					<label class="radio control-label">
					<input type="radio" name="supportBody1" value="1">  Please send me Job-Related Text Messages from this Employer.</label>
					</div>
					<div class="form-group">
					<label class="radio control-label">
					<input type="radio" name="supportBody1" value="2">  Please send me Job-Related Text Messages from ALL Employers.</label>
					</div>
					</div>
					
					<div id="supportBody2" class="modal-body">
					<div class="form-group">
                	<strong>Deals & Discount Text Messages</strong>
					</div>
					<div class="form-group">
					<label class="radio control-label">
					<input type="radio" name="supportBody2" value="1">  Please send me Deals & Discount Text Messages from this Company.</label>
					</div>
					<div class="form-group">
					<label class="radio control-label">
					<input type="radio" name="supportBody2" value="2">  Please send me Deals & Discount Text Messages from ALL Companies.</label>
					</div>
			
					
					<div><p><strong>Note: You will only receive up to 4 messages per month.</strong></p></div>
					<!--<button type="submit" class="btn btn-default">Submit</button>-->
					<div class="form-group">
					<input type="hidden" name="mobile" id="mobile" value="<?php echo $mobile; ?>" />
					<input type="hidden" name="brand" id="brand" />
					<button type="button" class="btn blue pull-left" onClick="tj.smsEdit()">Submit</button><p class="help-block pull-left text-danger hide" id="form-error">&nbsp; The form is not valid. </p> &nbsp;&nbsp;<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
					<!--<button id="editsmsSave" type="submit" class="btn btn-success pull-left">Submit</button> <p class="help-block pull-left text-danger hide" id="form-error">&nbsp; The form is not valid. </p> &nbsp;&nbsp;<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>-->
					<div>
					<p>By entering my information and clicking "Submit",  I am providing express consent to be contacted by JobAlarm.com via email, phone and text message, regarding job placement, job openings, career services and marketing promotions (if selected) using automated technology. Standard message and data rates may apply to text messages. You are not required to provide consent to receive services from JobAlarm.com. I also have read and agree to the JobAlarm.com <a href="/terms">Terms of Use and Privacy Policy.</a></p>
					<p>Text "HELP" to the shortcode from which you're receiving text alerts for help. Text "STOP" to the shortcode from which you're receiving alerts to cancel at any time.</p>
					<!--<p>* Conversational messages with Employers and administrative messages from JobAlarm.com do not count towards the 4 messages per month limit.</p>-->
					</div>
				        </div>
                <div class="modal-footer">
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</body>
<!-- END BODY -->
</html>