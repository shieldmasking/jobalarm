<?php
session_start();
ini_set('display_errors',1);
include_once '../inc/class.db.php';
include_once '../inc/config.php';

$dataId = (isset($_REQUEST['i'])) ? $_REQUEST['i'] : '';
// If not logged in, redirect.
if (!isset($_SESSION['account'])) {
    header('location: logout.php?i=' . $dataId);
    exit();
}

// Set the user account
Config::set('account',$_SESSION['account']);

// If no account ID, redirect
if (!isset(Config::get('account')['accountId'])) {
    header('location: logout.php?i=' . $dataId);
    exit();
}


/////Test Data


if ($dataId) {
$dbData = Config::get('db')->get_results("select n.*, u.last_name from `productiveNewData` n left join `productiveUser` as u on u.id = n.userid where n.id=52");
$account_data = $dbData[0];
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

@media print{

    body {
        margin:0;
    }

    div#viewreport {
        width:100%;
        height:500px;
        background-color:#000;
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
<title>ProductiveHR</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta content="" name="description"/>
<meta content="" name="author"/>
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css">
<link href="../theme/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="../theme/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css">
<link href="../theme/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="../theme/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css">
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="../theme/assets/global/plugins/bootstrap-datepicker/css/datepicker3.css"/>
<link rel="stylesheet" type="text/css" href="../theme/assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="../theme/assets/admin/pages/css/todo.css"/>
<!-- END PAGE LEVEL STYLES -->
<!-- BEGIN THEME STYLES -->
<link href="../theme/assets/global/css/components-rounded.css" id="style_components" rel="stylesheet" type="text/css">
<link href="../theme/assets/global/css/plugins.css" rel="stylesheet" type="text/css">
<link href="../theme/assets/admin/layout3/css/layout.css" rel="stylesheet" type="text/css">
<link href="../theme/assets/admin/layout3/css/themes/blue-steel.css" rel="stylesheet" type="text/css" id="style_color">
<link href="../theme/assets/admin/layout3/css/custom.css" rel="stylesheet" type="text/css">
<!-- END THEME STYLES -->
<link rel="shortcut icon" href="favicon.ico"/>
</head>
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
                <div class="header" align="center">
                    <div class="title"><strong>Staffing Report</strong></div>
					
                
                
				<div class="row" style="margin-top:5px;margin-bottom:8px">
                        <div class="col-sm-12">
						
							<div class="title">Report Date: </span><span><?php echo $account_data['dayDate']; ?></span>							
							</div>	
                           
							<div class="title">Last Updated By: </span><span><?php echo $account_data['techcount']; ?></span>							
							</div>							
						
							
                        </div>
                  </div>
			  </div>
					 <div class="body">
                    <!--begin::Form-->
                    <form id="add_prod_form" class="m-form m-form--fit m-form--label-align-right">
                		<div class="m-portlet__body">
						<div style="background-color:#E0E0E0; padding-left: 20px;">
						<div class="title"><strong>Shared Resources </strong>
						</br>
						</div>
						<div class="form-group">
                                <div style="padding-left: 25px;">
								<label for="chargecount_add">
                                   Charge Nurses:
                                </label>
                                
                                    <input id="chargecount_add" name="chargecount_add" type="text" class="form-control" value="<?php echo $account_data['chargecount']; ?>" disabled;>
                                </div>
                          </div>
							
							<div class="form-group">
							<div style="padding-left: 25px;">
                                <label for="techcount_add" class="col-8 col-form-label">
                                   Techs:
                                </label>
                                
                                    <input id="techcount_add" name="techcount_add" type="text" class="form-control" value="<?php echo $account_data['techcount']; ?>" disabled>
                                </div>
                            </div>
						<div class="form-group">
						<div style="padding-left: 25px;">
                                <label for="seccount_add" class="col-8 col-form-label">
                                   Secretaries:
                                </label>
                                <
                                    <input id="seccount_add" name="seccount_add" type="text" class="form-control" value="<?php echo $account_data['seccount']; ?>" disabled>
                                </div>
                          </div>
						<div style="background-color:#E0E0E0; padding-left: 20px;">
						<div class="title"><strong>Antepartum </strong>
						<div class="title"><strong>Variance: <?php echo $account_data['nvariance']; ?></span></strong></div>
						<div class="title"><strong>Productivity: <?php echo $account_data['aproductivity']; ?></span></strong></div>
						</div>
						<div class="form-group">
						<div style="padding-left: 25px;">
                                <label for="atotal">
                                    Total Antepartem Patients:                                </label>
                                
                                    <input id="atotal" name="atotal" type="text" class="form-control" value="<?php echo $account_data['atotal']; ?>" disabled>
                          </div>
                          </div>
						<div class="form-group">
						<div style="padding-left: 25px;">
                                <label for="antecount_add">
                                    Antepartum Nurses currently in staffing:
                                </label>
                                
                                    <input id="antecount_add" name="antecount_add" type="text" class="form-control" value="<?php echo $account_data['antecount']; ?>" disabled>
                                </div>
                          </div>
							<hr></hr>
							<div class="form-group">
							<div style="padding-left: 25px;">
                                <label for="acs_add">
                                    Antepartum with Complications but stable:
                                </label>
                                
                                    <input id="acs_add" name="acs_add" type="text" class="form-control" value="<?php echo $account_data['acs']; ?>" disabled>
                                </div>
                            </div>
							<div class="form-group">
							<div style="padding-left: 25px;">
                                <label for="am1_add">
                                    Antepartum Magnesium after 1st hour:
                                </label>
                                
                                    <input id="am1" name="am1" type="text" class="form-control" value="<?php echo $account_data['am1']; ?>" disabled>
                                </div>
                            </div>
							<div class="form-group">
							<div style="padding-left: 25px;">
                                <label for="awcm_add">
                                    Antepartum with complications, 1st hour Mag:
                                </label>
                               
                                    <input id="awcm" name="awcm" type="text" class="form-control" value="<?php echo $account_data['awcm']; ?>" disabled>
                                </div>
                            </div>
							<div class="form-group">
							<div style="padding-left: 25px;">
                                <label for="obed_add">
                                    Outpatient Observation / OBED:
                                </label>
                                
                                    <input id="obed" name="obed" type="text" class="form-control" value="<?php echo $account_data['obed']; ?>" disabled>
                                </div>
                            </div>
							<div style="background-color:#E0E0E0; padding-left: 20px;">
					<div class="title"><strong>Labor</strong>
					<div class="title"><strong>Variance: <span id="lvariance"></span></strong></div>
					</div>
					<div class="form-group">
					<div style="padding-left: 25px;">
                                <label for="ltotal">
                                    Total Labor Patients:
                                </label>
                                
                                    <input id="ltotal" name="ltotal" type="text" class="form-control" value="<?php echo $account_data['ltotal']; ?>" disabled>
                                </div>
                          </div>
						<div class="form-group">
						<div style="padding-left: 25px;">
                                <label for="ldcount_add">
                                    Labor Nurses currently in staffing:
                                </label>
                                
                                    <input id="ldcount" name="ldcount" type="text" class="form-control" value="<?php echo $account_data['ldcount']; ?>" disabled>
                                </div>
                          </div>
						<hr></hr>
						<div class="form-group">
						<div style="padding-left: 25px;">
                                <label for="ev_add">
                                    External Version:
                                </label>
                               
                                    <input id="ev" name="ev" type="text" class="form-control" value="<?php echo $account_data['ev']; ?>" disabled>
                                </div>
                          </div>
							<div class="form-group">
							<div style="padding-left: 25px;">
                                <label for="scs_add">
                                    Scheduled C/S Prep:
                                </label>
                                
                                    <input id="scs" name="scs" type="text" class="form-control" value="<?php echo $account_data['scs']; ?>" disabled>
                                </div>
                            </div>
							<div class="form-group">
							<div style="padding-left: 25px;">
                                <label for="cr_add">
                                    Cervical ripening, Labor, Pitocin induction/augmentation:
                                </label>
                                
                                    <input id="cr" name="cr" type="text" class="form-control" value="<?php echo $account_data['cr']; ?>" disabled>
                                </div>
                            </div>
							
							<div class="form-group">
							<div style="padding-left: 25px;">
                                <label for="pt_add">
                                    Pt with Medical/OB complications (mag, insulin, twins, IUFD):
                                </label>
                                
                                    <input id="pt" name="pt" type="text" class="form-control" value="<?php echo $account_data['pt']; ?>" disabled>
                                </div>
                            </div>
							<div class="form-group">
							<div style="padding-left: 25px;">
                                <label for="ccs_add">
                                    Circulator C/S, PACU:
                                </label>
                                
                                    <input id="ccs" name="ccs" type="text" class="form-control" value="<?php echo $account_data['ccs']; ?>" disabled>
                                </div>
                            </div>
							<div class="form-group">
							<div style="padding-left: 25px;">
                                <label for="psl_add">
                                    Patient in 2nd stage labor, recovery:
                                </label>
                                
                                    <input id="ps1" name="ps1" type="text" class="form-control" value="<?php echo $account_data['ps1']; ?>" disabled>
                                </div>
                            </div>
						
							
							<div class="form-group">
							<div style="padding-left: 25px;">
                                <label for="form_control_1">Variance Note / Action Plan</label>
                                <textarea class="form-control" rows="3" placeholder="" id="prodnote"><?php echo $account_data['note']; ?></textarea>
                            </div>
								</div>					
						   
				      </div>
                    </form>
                
                <div class="footer">
				     
                    
                </div>
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

<!-- BEGIN FOOTER -->
<div class="scroll-to-top">
	<i class="icon-arrow-up"></i>
</div>
<!-- END FOOTER -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--begin::Base Scripts -->
<script src="//code.jquery.com/jquery-1.12.4.js" type="text/javascript"></script>

<script src="./theme/assets/vendors/base/vendors.bundle.js" type="text/javascript"></script>
<script src="../theme/assets/demo/default/base/scripts.bundle.js" type="text/javascript"></script>
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
<script src="../theme/assets/vendors/custom/fullcalendar/fullcalendar.bundle.js" type="text/javascript"></script>
<!--end::Page Vendors -->
<!--begin::Page Snippets -->
<script src="../theme/assets/app/js/dashboard.js" type="text/javascript"></script>
<!--end::Page Snippets -->
<script src="../scripts/main.js" type="text/javascript"></script>

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