<?php
session_start();
ini_set('display_errors',1);
include_once './inc/class.db.php';
include_once './inc/config.php';

$dataId = (isset($_REQUEST['i'])) ? $_REQUEST['i'] : '';

// If not logged in, redirect.
if (!isset($_SESSION['account'])) {
    header('location: login.php?i=' . $dataId);
    exit();
}

// Set the user account
Config::set('account',$_SESSION['account']);

$deptName = $_SESSION['account']['deptName'] . " (" . $_SESSION['account']['unitId'] . ")";
/////Test Data


if ($dataId) {
$dbData = Config::get('db')->get_results("select n.*, DATE_FORMAT(n.dayDate,'%m/%d/%y') as newDate, DATE_FORMAT(n.entered,'%H:%i') as timeOnly, IFNULL(c.note,'') as escalationNote, IFNULL(l.escalation,'') as escalationType, a.image, d.viewPage, d.dept as deptName, d.oneto1 as acuity1, d.oneto2 as acuity2, d.oneto3 as acuity3, d.oneto4 as acuity4, d.oneto5 as acuity5, d.oneto6 as acuity6, d.dept as deptName, d.totalbeds, d.desc1, d.desc2, d.desc3, d.desc4, d.desc5, d.desc6, u.last_name, u.first_name, s.shift as reportshift, b.blockedBeds, b.comments from `productiveNewData` n LEFT JOIN `ProductiveDept` as d on d.id = n.deptId LEFT JOIN `productiveAccount` as a on a.id = n.accountId left join `productiveShifts` as s on s.id = n.shift left join `productiveUser` as u on u.id = n.userid LEFT OUTER JOIN `productiveblockedBeds` as b on b.accountId = n.accountId and b.deptId = n.deptId LEFT OUTER JOIN `productiveEscalations` as c on c.dataId = n.id LEFT OUTER JOIN `productiveAcctEscalations` as l on l.id = c.escalation where n.id={$dataId}");
$account_data = $dbData[0];
$user = $dbData[0]['first_name'] . " " . $dbData[0]['last_name'];
$dataId = $dbData[0]['id'];
$viewPage = $dbData[0]['viewPage'] . $dataId;
$time = $dbData[0]['timeOnly'];
$deptName = $dbData[0]['deptName'];
$escalationType = $dbData[0]['escalationType'];
$escalationNote = $dbData[0]['escalationNote'];
$charges = floatval($dbData[0]['chargecount']);
$blockedBeds = $dbData[0]['blockedBeds'];
$openbeds = intval($dbData[0]['totalbeds']) - intval($dbData[0]['atotal']) - intval($dbData[0]['blockedBeds']);

if ($charges < 1 && $charges > 0) {
	$chargeCount = round($charges,1);
}else{
	$chargeCount = round($charges,0);
}
if ($dbData[0]['image']) {
$logo = "../img/productive.png";
}else{
$logo = "../img/logo1.png";
}

if ($dbData[0]['comments']) {
$comments = "(" . $dbData[0]['comments'] . ")";
}else{
$comments = '';
}

if ($escalationType) {
$esc1 = '';
$escalation = $escalationType. " - " . $escalationNote;
}else{
$esc1 = "hidden";
}

if (intval($dbData[0]['acuity1'])==1) {
$hidden1 = '';
}else{
$hidden1 = "hidden";
}
if (intval($dbData[0]['acuity2'])==1) {
$hidden2 = '';
}else{
$hidden2 = "hidden";
}
if (intval($dbData[0]['acuity3'])==1) {
$hidden3 = '';
}else{
$hidden3 = "hidden";
}
if (intval($dbData[0]['acuity4'])==1) {
$hidden4 = '';
}else{
$hidden4 = "hidden";
}
if (intval($dbData[0]['acuity5'])==1) {
$hidden5 = '';
}else{
$hidden5 = "hidden";
}
if (intval($dbData[0]['acuity6'])==1) {
$hidden6 = '';
}else{
$hidden6 = "hidden";
}



}



?>


<style type="text/css">
div#viewreport {
    width:595px;
    height:842px;
    background-color:#fff;
    position:relative;
}
</style>
<style>
body {
	font-size: 16px;
    }
</style>
<style>

@media print{

    body {
        margin:0;
    }

    div#viewreport {
        width:100%;
        height:500px;
        background-color:#000;
    }
	
	print {
		#backlink, #editlink {
		display:none !important;
	} 


}
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
<title>ProductiveRN</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta content="" name="description"/>
<meta content="" name="author"/>
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="/theme/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="/theme/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css">
<link href="/theme/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="/theme/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css">
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="../theme/assets/global/plugins/bootstrap-datepicker/css/datepicker3.css"/>
<link rel="stylesheet" type="text/css" href="../theme/assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="../theme/assets/admin/pages/css/todo.css"/>
<!-- END PAGE LEVEL STYLES -->
<!-- BEGIN THEME STYLES -->
<link href="/theme/assets/global/css/components-rounded.css" id="style_components" rel="stylesheet" type="text/css">
<link href="/theme/assets/global/css/plugins.css" rel="stylesheet" type="text/css">
<link href="/theme/assets/admin/layout3/css/layout.css" rel="stylesheet" type="text/css">
<link href="/theme/assets/admin/layout3/css/themes/blue-steel.css" rel="stylesheet" type="text/css" id="style_color">
<link href="/theme/assets/admin/layout3/css/custom.css" rel="stylesheet" type="text/css">
<!-- END THEME STYLES -->

</head>

<script>
function goBack() {
    window.history.back();
}
</script>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<!-- DOC: Apply "page-header-menu-fixed" class to set the mega menu fixed  -->
<!-- DOC: Apply "page-header-top-fixed" class to set the top menu fixed  -->
<body class="page-boxed page-header-fixed page-container-bg-solid page-sidebar-closed-hide-logo page-sidebar-closed">
<!-- BEGIN TOP BAR -->
<!-- BEGIN HEADER TOP -->
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
			<div class="col-lg-12">
		
            <div class="content">
                <div class="title">
				<a href="dashboard.php">Home</a>
				</div>
				
				<div class="header" align="center">
				
              
                                <a href="index.php#staffing">
					<img src="../img/productive3.png" id="logoimg" alt="">
				</a>
                         
				
                    <div class="title"><strong><strong><?php echo $deptName; ?> Staffing Report</strong>
					</div>
					
                
                
				<div class="row" style="margin-top:5px;margin-bottom:8px">
                        <div class="col-sm-12">
						
							<div class="title">Report Date/Shift: <span><?php echo $account_data['newDate']; ?></span> - <span><?php echo $account_data['reportshift']; ?></span>							
							</div>	
                           
							<div class="title">Last Updated By: <span><?php echo $user; ?></span>							
							</div>
							<div class="title"><span><?php echo $account_data['entered']; ?></span>							
							</div>
							
						
							
                        </div>
                  </div>
			  </div>
			  </div>
					 <div class="body">
                    <!--begin::Form-->
                    <form id="addSICU" class="m-form m-form--fit m-form--label-align-right">
                		<div class="m-portlet__body">
						<div style="background-color:#E0E0E0; padding-left: 20px; padding-top: 20px;">
						<div class="title"><strong>Variance: <?php echo $account_data['avariance']; ?></strong></div>
						<div class="title"><strong>Est. Productivity: <?php echo $account_data['aproductivity']; ?></strong></div>
						<div class="title"><strong>Open Beds: <?php echo $openbeds ?></strong></div>
						<div class="title"><strong>Blocked Beds: <?php echo $blockedBeds ?> <?php echo $comments ?></strong></div>
						<div class="title" <?php echo $esc1; ?>>Escalation: <span><?php echo $escalation; ?></span>							
							</div>
						<hr></hr>
						<div class="title"><strong>Resources Currently In Staffing </strong>
						</br>
						</div>
						<div class="form-group">
                                <div style="padding-left: 25px; padding-top: 20px; padding-right: 20px;">
								<label for="chargecountSICU">
                                   Charge Nurses
                                </label>
                                
                                    <input id="chargecountSICU" name="chargecountSICU" type="text" class="form-control" value="<?php echo $chargeCount; ?>" disabled>
                                </div>
                          </div>
							
							<div class="form-group">
							<div style="padding-left: 25px; padding-right: 20px;">
                                <label for="techcountSICU" class="col-8 col-form-label">
                                   Techs
                                </label>
                                
                                    <input id="techcountSICU" name="techcountSICU" type="text" class="form-control" value="<?php echo $account_data['techcount']; ?>" disabled>
                                </div>
                            </div>
						<div class="form-group">
						<div style="padding-left: 25px; padding-right: 20px;">
                                <label for="seccountSICU" class="col-8 col-form-label">
                                   Secretaries
                                </label>
                                
                                    <input id="seccountSICU" name="seccountSICU" type="text" class="form-control" value="<?php echo $account_data['seccount']; ?>" disabled>
                                </div>
                          </div>
						 <div class="form-group">
						<div style="padding-left: 25px; padding-right: 20px;">
                                <label for="totalnursesSICU">
                                    Nurses
                                </label>
                                
                                    <input id="totalnursesSICU" name="totalnursesSICU" type="text" class="form-control" value="<?php echo $account_data['antecount']; ?>" disabled>
                                </div>
                          </div>
						<hr></hr>
							<div class="title"><strong>Patients</strong>							
							</div>
						<div class="form-group">
						<div style="padding-left: 25px; padding-right: 20px; padding-top: 20px;">
                                <label for="totalpatientsSICU">
                                    Total                                </label>
                                
                                    <input id="totalpatientsSICU" name="totalpatientsSICU" type="text" class="form-control" value="<?php echo $account_data['atotal']; ?>" disabled>
                          </div>
                          </div>
						
							
							<div class="title"><strong>Patient Breakdown</strong>
							
							</div>
							<div class="form-group" <?php echo $hidden1; ?>>
							<div style="padding-left: 25px; padding-right: 20px; padding-top: 20px;">
                                <label for="one2one">
                                    1:1 Acuity Patients (<?php echo $account_data['desc1']; ?>)
                                </label>
                                
                                    <input id="one2one" name="one2one" type="text" class="form-control" value="<?php echo $account_data['oneto1']; ?>" disabled>
                                </div>
                            </div>
							<div class="form-group" <?php echo $hidden2; ?>>
							<div style="padding-left: 25px; padding-right: 20px; padding-top: 20px;">
                                <label for="one2two">
                                    1:2 Acuity Patients (<?php echo $account_data['desc2']; ?>)
                                </label>
                                
                                    <input id="one2two" name="one2two" type="text" class="form-control" value="<?php echo $account_data['oneto2']; ?>" disabled>
                                </div>
                            </div>
							<div class="form-group" <?php echo $hidden3; ?>>
							<div style="padding-left: 25px; padding-right: 20px; padding-top: 20px;">
                                <label for="highSICU">
                                    1:3 Acuity Patients (<?php echo $account_data['desc3']; ?>)
                                </label>
                                
                                    <input id="highSICU" name="highSICU" type="text" class="form-control" value="<?php echo $account_data['oneto3']; ?>" disabled>
                                </div>
                            </div>
							<div class="form-group" <?php echo $hidden4; ?>>
							<div style="padding-left: 25px; padding-right: 20px;">
                                <label for="mediumSICU">
                                    1:4 Acuity Patients (<?php echo $account_data['desc4']; ?>)
                                </label>
                                
                                    <input id="mediumSICU" name="mediumSICU" type="text" class="form-control" value="<?php echo $account_data['oneto4']; ?>" disabled>
                                </div>
                            </div>
							<div class="form-group" <?php echo $hidden5; ?>>
							<div style="padding-left: 25px; padding-right: 20px;">
                                <label for="lowSICU">
                                    1:5 Acuity Patients (<?php echo $account_data['desc5']; ?>)
                                </label>
                               
                                    <input id="lowSICU" name="lowSICU" type="text" class="form-control" value="<?php echo $account_data['oneto5']; ?>" disabled>
                                </div>
                            </div>
							<div class="form-group" <?php echo $hidden6; ?>>
							<div style="padding-left: 25px; padding-right: 20px; padding-top: 20px;">
                                <label for="one2six">
                                    1:6 Acuity Patients (<?php echo $account_data['desc6']; ?>)
                                </label>
                                
                                    <input id="one2six" name="one2six" type="text" class="form-control" value="<?php echo $account_data['oneto6']; ?>" disabled>
                                </div>
                            </div>
							<hr></hr>
							<div class="form-group">
							<div style="padding-left: 25px; padding-bottom: 10px; padding-right: 20px;">
                                <label for="form_control_1">Variance Note / Action Plan</label>
                                <textarea class="form-control" rows="3" placeholder="" id="prodnoteSICU" disabled><?php echo $account_data['note']; ?></textarea>
                            </div>
								</div>	
							
                    </form>
                
                
            </div>
			<div class="footer" align="center">
				     <div class="title"><strong>ProductiveRN Confidential</strong>
                    
                </div>
            <!-- /.content -->
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
</div>
</div>
</div>
</div>
</div>
<!-- BEGIN FOOTER -->

<div class="scroll-to-top">
	<i class="icon-arrow-up"></i>
</div>
<!-- END FOOTER -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--begin::Base Scripts -->
<script src="//code.jquery.com/jquery-1.12.4.js" type="text/javascript"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.min.js"></script>

<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="//cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js" type="text/javascript"></script>
<script src="//cdn.datatables.net/responsive/2.2.0/js/dataTables.responsive.min.js" type="text/javascript"></script>
<script src="//cdn.datatables.net/responsive/2.2.0/js/responsive.bootstrap4.min.js" type="text/javascript"></script>
<script src="//cdn.datatables.net/select/1.2.3/js/dataTables.select.min.js" type="text/javascript"></script>
<script type="text/javascript" src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.9/js/dataTables.checkboxes.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
<!--end::Base Scripts -->
<!--begin::Page Vendors -->

<!--end::Page Vendors -->
<!--begin::Page Snippets -->

<!--end::Page Snippets -->


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