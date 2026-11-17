<?php
ini_set('display_errors',1);
include "../inc/initializer.php";

if (!isset($_SESSION['account']) || ($_SESSION['account']['pwd'] == $_SESSION['account']['temp'])) {
	session_destroy();
    header('location: login.php');
    exit();
}

$startPay = (isset($_SESSION['account']['newStartPay']) ? $_SESSION['account']['newStartPay']:0);
$endPay = (isset($_SESSION['account']['newEndPay']) ? $_SESSION['account']['newEndPay']:0);
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
<div class="m-subheader bg-secondary" style="padding-bottom: 30px;">
    
	<div class="mr-auto">
            <h3 class="m-subheader__title ">
                Shift Report Details
            </h3>
		</div>

<div class="d-flex pull-right">
			<span class="m-subheader__daterange" id="custom_daterangepicker">						
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
Select a Date Range prior to downloading your report, or  <br/>click on the Unit Name to setup an email of a Daily Report.
</div>
<!-- END: Subheader -->
<div class="m-content bg-secondary" id="pdfcontent">
    <!--Begin::Main Portlet-->
	<input id="startCustom" type="hidden" value="<?php echo $startPay; ?>" />
	<input id="endCustom" type="hidden" value="<?php echo $endPay; ?>" />
    
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__body">
				
										
					
                    <div class="row col-12">
					
					
                        <table id="pdfTable"  class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%" style="color:black">
                         
				
							<thead>
							
                                <tr>
    <!--                                <th></th>-->
    <!--                                <th></th>-->
									<th width="40%" data-priority="1">Unit</th>
									<th width="30%" data-priority="2">Description</th>
									<th width="15%" data-priority="3">PDF</th>
									<th width="15%" data-priority="4">CSV</th>
							                                
                                </tr>
                            </thead>
                        </table>
						</div>
                    
                </div>
            </div>
     
    <!--End::Main Portlet-->
</div>
    <!--begin::Modal-->
<div class="modal fade" id="emailReports" tabindex="-1" role="basic" aria-hidden="true">
        <div class="col-sm-5">
		<div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <h3 class="modal-title"><strong><span id="reportName"></span></strong></h3>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
			
				<input id="reportId" type="hidden" value="" />
				<div class="row">
				<div class="col-6">
				<div class="modal-title">Daily Email Time (ET): <span id="dailyTime"></span>
				  <div class="title"><h4><span id="reportdeptId"></span></h4>
				  <div class="title"><h4><span id="reportuserId"></span></h4>
				 </div>
				</div>
				
				 </div>
				 </div>
				 <div class="col-6">
				 <div class="pull-right">
				 <a href="javascript:;" onclick="tj.addEmail();">Add External Email</a>
				 </div>
				 </div>
				 </div>
				
				 <div class="row">
					<table id="emailTable"  class="table table-striped table-bordered dt-responsive">
                            <thead>
                                <tr>
									<th width="30%" data-priority="1">User</th>
                                    <th width="40%" data-priority="2">Email</th>
									<th width="30%" data-priority="3">Receive Report</th>
                                </tr>
                            </thead>
                        </table>
					</div>
					
			</div>
                <div class="modal-footer">
				<div class="col-sm-6">			
				     <button type="button" class="btn btn-info pull-right" data-dismiss="modal">Close</button>
                    
                </div>
			</div>	
            </div>
            <!-- /.modal-content -->
        
		</div>
        <!-- /.modal-dialog -->
 </div>
 </div>
     <!--begin::Modal-->
<div class="modal fade" id="addNonUser" tabindex="-1" role="basic" aria-hidden="true">
        <div class="col-sm-5">
		<div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <h3 class="modal-title">Add External Email to Distribution</h3>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
		
				<div class="row">
				<div class="col-6">
				<label for="emailFirst">
                First Name:
                <div>
                <input type="text" class="form-control" id="emailFirst">
                </div>
				</label>
				</div>
				
				 <div class="col-6">
				<label for="emailFirst">
                Last Name:
                <div>
                <input type="text" class="form-control" id="emailLast">
                </div>
				</label>
				 </div>
				 </div>
				 
				<div class="row">
				<div class="col-md">
				<label for="emailAdd">
                Email Address:
                <div>
                <input type="text" class="form-control" id="emailAdd">
                </div>
				</label>
				</div>
				 </div>
				 
				 </div>
				
					

                <div class="modal-footer">
				<div class="col-sm-6">			
				     <button type="button" class="btn btn-info pull-right" onclick="tj.addnonUser();">Add</button>
                    
                </div>
			</div>	
            
            <!-- /.modal-content -->
        
		</div>
        <!-- /.modal-dialog -->
 </div>
 </div>
 </div>