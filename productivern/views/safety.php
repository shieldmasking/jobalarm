<?php
include "../inc/initializer.php";
require_once '.././inc/class.db.php';
require_once '.././inc/config.php';
//require_once '.././inc/FPDF/fpdf.php';
require_once '.././inc/class.fpdf.php';

$payPeriod = intval($_SESSION['account']['payPeriod']);
$currentDay = intval($_SESSION['account']['currentDay']);
$payFirst = intval($_SESSION['account']['firstDay']);
$role = intval($_SESSION['account']['role']);
$displayProd = intval($_SESSION['account']['displayProd']);
$userId = intval($_SESSION['account']['id']);
$prodCount = intval($_SESSION['account']['prodCount']);
$deptId = intval($_SESSION['account']['deptId']);
$prodMeasure = intval($_SESSION['account']['prodMeasure']);
$secLab = $_SESSION['account']['secLabel'];
$accountId = $_SESSION['account']['accountId'];


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

$dbText = Config::get('db')->get_results("SELECT d.*, x.userId from `ProductiveDept` d left outer join `productiveDeptXref` as x on x.deptId = d.id left outer join `productiveUser` as u on u.id = x.userId where x.deptId IN (SELECT `deptId` from `productiveDeptXref` WHERE `userId`={$userId}) and d.escalations=0");

if($dbText || $role==11){
	$hidden = '';
}else{
	$hidden = "hidden";
}

?>
<script>
updateform = function() {
	var startDate = tj.prodStartDate;
	var endDate = tj.prodEndDate;
	$("#startDate").val(startDate);
	$("#endDate").val(endDate);
}
</script>
<style>
.modal {
    overflow-y: scroll;
}
</style>
<!-- BEGIN: Subheader -->
<div class="m-subheader bg-secondary" style="padding-bottom: 30px;">
		<div class="mr-auto">
            <h3 class="m-subheader__title ">
                Safety Issues
            </h3>
        </div>

<div class="d-flex pull-right">
			<span class="m-subheader__daterange" id="safety_daterangepicker">						
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
<div>
				<button type="button" class="btn btn-info" onclick="tj.newSafety();">
                Add Record
                </button> 
				</div>

</div>

<!-- END: Subheader -->
<div class="m-content bg-secondary">
    <!--Begin::Main Portlet-->
	
       
	    <div class="portlet">
	  <div class="portlet-body">
				<div class="row col-12">
                        <table id="safetyTable"  class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%" style="color:black">
	                   		<thead>
                                <tr>
									<th width="20%" data-priority="1">Unit  (Created By)</th>
									<th width="10%" data-priority="3">Created  (Days Open)</th>
									<th width="10%" data-priority="4">Location</th>
									<th width="15%" data-priority="5">Type</th>
									<th width="30%" data-priority="2">Note</th>
									<th width="15%" data-priority="6">On Dashboard (Due Date)</th>
                                </tr>
                            </thead>
                        </table>
				</div>
			
        </div>
    </div>
</div>
    <!--End::Main Portlet-->
<div class="modal fade" id="huddle" tabindex="-1" role="basic">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h3 class="modal-title" style="color:white">Add Safety Issue</h3>
                <button type="button" class="close" data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="safetyForm">
                <input id="safetyId" type="hidden" value="" />

                <!-- Row 1: Unit + Submitted By -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="unit1"><strong>Unit</strong></label>
                            <select id="unit1" class="form-control">
                                <option value="0">Select Unit</option>
                                <option value="1">Hospital</option>
                                <?php
                                if(intval($role)>7){
                                    $dbunits = Config::get('db')->get_results("SELECT x.*, d.dept from `productiveDeptXref` x LEFT JOIN `ProductiveDept` as d on d.id=x.deptId where d.accountId={$accountId} AND (d.active=1 OR d.active=2) group by x.deptId order by d.dept ASC");
                                }else{
                                    $dbunits = Config::get('db')->get_results("SELECT x.*, d.dept from `productiveDeptXref` x LEFT JOIN `ProductiveDept` as d on d.id=x.deptId where x.deptId IN (SELECT `deptId` FROM `productiveDeptXref` where `userId`={$userId}) AND d.accountId={$accountId} AND (d.active=1 OR d.active=2) group by x.deptId order by d.dept ASC");
                                }
                                foreach($dbunits as $x){ ?>
                                <option value="<?php echo $x['deptId'];?>"><?php echo $x['dept'];?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="safetySubmittedBy"><strong>Submitted By</strong></label>
                            <input id="safetySubmittedBy" class="form-control" type="text" placeholder="Your name" />
                        </div>
                    </div>
                </div>

                <!-- Row 2: Primary Concern -->
                <div class="row" id="safetyTypes1">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="peri1"><strong>Primary Concern</strong></label>
                            <select id="peri1" class="form-control">
                                <option value="0">Select Type</option>
                                <?php
                                $s1 = Config::get('db')->get_results("SELECT t.*, c.id as safeId, c.safetyDesc, c.removeId, c.accountId from `productivesafetyType` t left join `productivesafetyConfig` as c on c.safetyType=t.id where (c.accountId={$accountId} OR c.accountId=0) and c.removeId=0 and t.typeId=1 group by c.id order by t.typeDesc ASC, c.safetyDesc ASC");
                                if($s1){ foreach($s1 as $select2){ ?>
                                <option value="<?php echo $select2['safeId']; ?>"><?php echo $select2['typeDesc'] . ' - ' . $select2['safetyDesc']; ?></option>
                                <?php }} ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Row 3: Location -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="loc1"><strong>Room / Location</strong></label>
                            <input id="loc1" class="form-control" type="text" placeholder="e.g. Room 204" />
                        </div>
                    </div>
                </div>
                <input id="due1" type="hidden" value="" />

                <!-- PHI Warning -->
                <div class="alert alert-warning" style="font-size:0.875rem;padding:8px 12px;margin-bottom:8px;">
                    <strong>HIPAA Notice:</strong> Do not include any Protected Health Information (PHI) in the note below.
                </div>

                <!-- Note -->
                <div class="form-group">
                    <label for="rdesc1"><strong>Note</strong></label>
                    <textarea id="rdesc1" class="form-control" rows="4" style="width:100%"></textarea>
                </div>

                <!-- High Priority -->
                <div class="form-group">
                    <div class="checkbox" style="margin:0">
                        <label style="font-weight:600;font-size:0.95rem;">
                            <input type="checkbox" id="priority" name="priority" style="margin-right:6px;" />
                            High Priority
                        </label>
                    </div>
                </div>

                </form>
            </div>
            <div class="modal-footer bg-info">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" id="safetyButton" class="btn btn-primary" onclick="tj.addSafety();">Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="edithuddle" tabindex="-1" role="basic">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h3 class="modal-title" style="color:white">Safety Issue</h3>
                <button type="button" class="close" data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editsafetyForm">
                <input id="editsafetyId" type="hidden" value="" />

                <!-- Row 1: Unit -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="editunit1"><strong>Unit</strong></label>
                            <select id="editunit1" class="form-control">
                                <option value="0">Select Unit</option>
                                <option value="1">Hospital</option>
                                <?php foreach($dbunits as $x){ ?>
                                <option value="<?php echo $x['deptId'];?>"><?php echo $x['dept'];?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <input id="editdue1" type="hidden" value="" />

                <!-- Row 2: Primary Concern -->
                <div class="row" id="safetyTypes2">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="editperi1"><strong>Primary Concern</strong></label>
                            <select id="editperi1" class="form-control">
                                <option value="0">Select Type</option>
                                <?php if($s2){ foreach($s2 as $select1){ ?>
                                <option value="<?php echo $select1['safeId']; ?>"><?php echo $select1['typeDesc'] . ' - ' . $select1['safetyDesc']; ?></option>
                                <?php }} ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Row 3: Location -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="editloc1"><strong>Room / Location</strong></label>
                            <input id="editloc1" class="form-control" type="text" placeholder="e.g. Room 204" />
                        </div>
                    </div>
                </div>

                <!-- PHI Warning -->
                <div class="alert alert-warning" style="font-size:0.875rem;padding:8px 12px;margin-bottom:8px;">
                    <strong>HIPAA Notice:</strong> Do not include any Protected Health Information (PHI) in the note below.
                </div>

                <!-- Note -->
                <div class="form-group">
                    <label for="editrdesc1"><strong>Note</strong></label>
                    <textarea id="editrdesc1" class="form-control" rows="4" style="width:100%"></textarea>
                </div>

                <!-- High Priority -->
                <div class="form-group">
                    <div class="checkbox" style="margin:0">
                        <label style="font-weight:600;font-size:0.95rem;">
                            <input type="checkbox" id="editpriority" name="editpriority" style="margin-right:6px;" />
                            High Priority
                        </label>
                    </div>
                </div>

                </form>
            </div>
            <div class="modal-footer bg-info">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" id="editsafetyButton" class="btn btn-primary" onclick="tj.updateSafety();">Save</button>
            </div>
        </div>
    </div>
</div>

    <!--begin::Modal-->
	    <!--begin::Modal-->





	


   
	


