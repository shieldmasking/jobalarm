<?php
ini_set('display_errors',1);
include "../inc/initializer.php";
$sort = 1;
if (!isset($_SESSION['account']) || ($_SESSION['account']['pwd'] == $_SESSION['account']['temp'])) {
	session_destroy();
    header('location: login.php');
    exit();
}
$startPay = (isset($_SESSION['account']['newStartPay']) ? $_SESSION['account']['newStartPay']:0);
$endPay = (isset($_SESSION['account']['newEndPay']) ? $_SESSION['account']['newEndPay']:0);
//$endPay = $_SESSION['account']['newEndPay'];

/*
$payPeriod = intval($_SESSION['account']['payPeriod']);
$currentDay = intval($_SESSION['account']['currentDay']);
$payFirst = intval($_SESSION['account']['firstDay']);

if($payPeriod>0 && $payFirst>0){
$calcPay = (($currentDay-$payFirst)/$payPeriod)- FLOOR(($currentDay-$payFirst)/$payPeriod);
	if($calcPay==0){
	$endPay=$payPeriod-1;
	$startPay = 0;
	}else{
	$startCalc = ROUND($payPeriod-($calcPay*$payPeriod),0);
	$startPay = $payPeriod-$startCalc;
	$endPay = ROUND($payPeriod-($calcPay*$payPeriod)-1,0);
	}
}else{
$startPay=0;
$endPay=0;
}
*/

?>


<!-- BEGIN: Subheader -->
<div class="m-subheader bg-secondary" style="padding-bottom: 20px;">
    
	<div class="mr-auto">
            <h3 class="m-subheader__title ">
                Performance
            </h3>
        </div>
     <div class="d-flex pull-right">
	 
            <span class="m-subheader__daterange" id="performance_daterangepicker">
                <span class="m-subheader__daterange-label">
                    <span class="m-subheader__daterange-title"></span>
                    <span class="m-subheader__daterange-date m--font-brand"></span>
                </span>
                <a href="javascript:;" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill">
                    <i class="la la-angle-down"></i>
                </a>
            </span>
            <div class="pull-right" style="margin-left:15px;margin-top:2px">
			
            </div>
			
        </div>
		(Select your Date Range prior to downloading your report.)
		<div class="col-2" hidden>
		<span>Group By</span>
		<select id="categoryGroup" class="form-control" onchange="tj.categoryGroup();">
										<option value="0">Unit</option>
										<option value="1">User</option>
										<option value="2">Shift</option>
														
	</select>
	</div>
	<div hidden>
	<span><a href="javascript:;" onclick="tj.categoryGroup(1)" id="group1"><u>Grouped By: Unit</u></a>
</span><span><a href="javascript:;" onclick="tj.categoryGroup(0)" id="group0"><u>Grouped By: User</u></a></span>

</div>	
    </div>

<!-- END: Subheader -->
<div class="m-content bg-secondary" id="content">
    <!--Begin::Main Portlet-->
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__body">
				<input id="currentGroup" type="hidden" value="<?php echo $sort; ?>" />
				<input id="startPerf" type="hidden" value="<?php echo $startPay; ?>" />
				<input id="endPerf" type="hidden" value="<?php echo $endPay; ?>" />						
					
                    <div class="row col-12">
					
					
                        <table id="performanceTable"  class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%" style="color:black">
                         
				
							<thead>
							
                                <tr>
    <!--                                <th></th>-->
    <!--                                <th></th>-->
									<th width="40%" data-priority="1">Unit</th>
									<th width="30%" data-priority="2">Description</th>
									<th width="15%" data-priority="3">Download</th>
							                                
                                </tr>
                            </thead>
                        </table>
						</div>
                    
                </div>
            </div>
    <!--End::Main Portlet-->
</div>
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="theme/assets/global/plugins/flot/jquery.flot.min.js"></script>
<script src="theme/assets/global/plugins/flot/jquery.flot.resize.min.js"></script>
<script src="theme/assets/global/plugins/flot/jquery.flot.pie.min.js"></script>
<script src="theme/assets/global/plugins/flot/jquery.flot.stack.min.js"></script>
<script src="theme/assets/global/plugins/flot/jquery.flot.crosshair.min.js"></script>
<script src="theme/assets/global/plugins/flot/jquery.flot.categories.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="theme/assets/admin/pages/scripts/charts-flotcharts.js"></script>
    <!--begin::Modal-->
