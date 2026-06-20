<?php
session_start();
ini_set('display_errors',1);
include_once 'inc/class.db.php';
include_once 'inc/class.jatwitter.php';
include_once 'inc/config.php';

if (!isset($_SESSION['account'])) {
    header('location: login.php');
}

$account_data = $_SESSION['account'];
if (!isset($account_data['accountId'])) {
	header('location: login.php');
	exit();
}

//var_dump($account_data);

	
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title>JobAlarm | QuickPost</title>
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
<style type="text/css">
#datatableCompx_ajax_length,
#datatableCompx_ajax_filter
 {
	display:none;
}
</style>
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
    <!-- END TOP BAR -->
<!-- BEGIN HEADER -->
<div class="page-header">
	<!-- BEGIN HEADER TOP -->
	
		<div class="container">
			<!-- BEGIN LOGO -->
			
			<div class="page-logo" align="center">

			<?php
			    //$accData = Config::get('db')->get_results("select * from account where id=".$account_data['accountId']);
			    
				$accData = Config::get('db')->get_results("SELECT s.*, b.storeBrand, b.storeImage FROM `sms_stores` as s LEFT JOIN `sms_brand` as b on b.id = s.brandId where s.userId=".$account_data['id']." group by s.id");
				 
				if (count($accData) > 0 && strlen($accData[0]['storeImage']) > 0) {
			        $account = $accData[0];
			 		echo '<img src="img/'.$account['storeImage'].'" />';   
			    } else { 
			?>
				<img src="img/logo1.png">			
			<?php } ?>
			</div>
			
				
			<!-- END LOGO -->
			<!-- BEGIN RESPONSIVE MENU TOGGLER -->
			
			<!-- END RESPONSIVE MENU TOGGLER -->

		</div>
	
	<!-- END HEADER TOP -->
	<!-- BEGIN HEADER MENU -->
	
	<!-- END HEADER MENU -->
</div>
<!-- END HEADER -->
<!-- BEGIN PAGE CONTENT -->
<div class="page-content">
	<div class="container">
	<h3 class="form-section" align="center"><strong> Manager's Quick Post/Text </strong></h3>
		
		<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
		
		<!-- /.modal -->
		<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
		<!-- BEGIN PAGE BREADCRUMB -->
				<!-- END PAGE BREADCRUMB -->
		<!-- BEGIN PAGE CONTENT INNER -->
		<div class="auto-style" id="CompxCell" style="left: 0px; top: 0px">
		<div class="portlet light ">
            <div class="portlet-title">
                <div class="caption caption-md">
                    <i class="icon-bar-chart theme-font hide"></i>
                    <span class="caption-subject theme-font bold uppercase"> Store #<?php echo $account['storeNum']; ?><P><?php echo $account['address']; ?><br><?php echo $account['city']; ?>, <?php echo $account['st']; ?>  <?php echo $account['zip']; ?></span>
                </div>                                
            </div>
            <div class="portlet-body">
                <div class="portlet-body">
					<table class="table table-striped table-bordered table-hover" id="datatableCompx_ajax">								    
						<thead>	
							<tr role="row" class="heading">
							<th>Position</th>
							<th>Last Posted</th>
							<th>Last Texted</th>
							<th>Actions</th></tr>
						</thead>
						<tbody id="CompxBody"></tbody>
					</table>
				</div>
            </div>
			</div>
        </div>

	<a class="btn blue pull-right margin-top-10" href="logout.php?"><i class="fa fa-sign-out"></i>Sign Out</a>
									
		<!-- END PAGE CONTENT INNER -->
	</div>
	
</div>
<!-- END PAGE CONTENT -->

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
<script src="theme/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="theme/assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="theme/assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="theme/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script type="text/javascript" src="theme/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="theme/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
<script src="theme/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="theme/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="theme/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="theme/assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="theme/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="theme/assets/global/plugins/bootbox/bootbox.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE PLUGINS & SCRIPTS -->
<script type="text/javascript" src="theme/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="theme/assets/global/plugins/select2/select2.min.js"></script>
<script src="theme/assets/admin/pages/scripts/todo.js" type="text/javascript"></script>
<script src="theme/assets/admin/pages/scripts/index3.js" type="text/javascript"></script>
<script src="theme/assets/admin/pages/scripts/tasks.js" type="text/javascript"></script>
<script src="theme/assets/admin/pages/scripts/table-managed.js"></script>
<script src="theme/assets/global/scripts/datatable.js"></script>
<script src="theme/assets/admin/pages/scripts/table-ajax.js"></script>

<!-- END PAGE PLUGINS & SCRIPTS -->
<script src="theme/assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="theme/assets/admin/layout3/scripts/layout.js" type="text/javascript"></script>
<script src="theme/assets/admin/layout3/scripts/demo.js" type="text/javascript"></script>
<script src="theme/assets/admin/pages/scripts/contact-us.js"></script>
<script src="inc/tweetedjobs-mainTest.js" type="text/javascript"></script>
<script>
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core components
Layout.init(); // init current layout
Demo.init(); // init demo features
Todo.init(); // init todo page
//do it, do it do it do it
tj.alex.jobxGrid();
});
</script>

<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>