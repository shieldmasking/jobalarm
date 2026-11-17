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


if($displayProd >0 && $prodMeasure !=4){
	$varianceHeader = "Variance";
}else{
	$varianceHeader = "RN Variance";
}

if($prodCount && $secLab && $prodCount==1){
	$secLabel = $secLab;
}else{
	$secLabel = "Sec/Admin";
}

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
/*
// Get the request
if (isset($_REQUEST['req'])) {
    Config::set('Request',$_REQUEST['req']);
}


// Route the request
switch(Config::get('Request')) {

    ////////////////////////////////
    // REPORTS PAGE
    case 'getpdf':
        header('Content-Type: application/json');
        fpdf();
        break;
}

/////////fpdf reports

function fpdf(){
$pdf = new PDF_MC_Table();

$startDate = isset($_REQUEST['start']) && ($_REQUEST['start'] != '') ? $_REQUEST['start'] : '2019-09-14';
$endDate = isset($_REQUEST['end']) && ($_REQUEST['end'] != '') ? $_REQUEST['end'] : '2019-09-14';
//$acct2 = Config::get('account')['accountId'];
$acct2 = 19;
if(intval($acct2)==8){
	$sort = "ASC";
}else{
	$sort = "DESC";
}

$data = Config::get('db') -> get_results("select n.deptId, SUM(CASE WHEN n.userId>0 THEN n.nvariance ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END) as varAvg, a.productivityPosNeg, n.actualWHP, a.name as accountName, a.displayProd, d.dept as deptName, d.prodMeasure, DATE_FORMAT(CURDATE(),'%m%d%y') as filedate, u.last_name as Director, DATE_SUB(CURDATE(), INTERVAL (a.payPeriod-(ROUND(a.payPeriod-((((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod)-FLOOR((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod))*a.payPeriod),0))) DAY) AS startDay, DATE_ADD(CURDATE(), INTERVAL (ROUND(a.payPeriod-((((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod)-FLOOR((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod))*a.payPeriod),0)-1) DAY) AS endDay, a.id as accountId, ROUND(SUM(CASE WHEN n.actualWHPimport>0 then n.hrsVarianceActual ELSE n.hrsVariance END),1) as hrsVarianceTotal, SUM((CASE WHEN n.userId>0 and n.activeRecord>0 THEN 1 ELSE 0 END)) as report, SUM((CASE WHEN (n.activeRecord=1 and n.userId=0 and n.planSubmitted=1) THEN 1 ELSE 0 END)) as plans, SUM((CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END)) as totalreports, ((SUM(CASE WHEN (n.userId>0 and n.activeRecord>0) THEN 1 ELSE 0 END) + SUM(CASE WHEN (n.planSubmitted>0 AND n.userId=0) THEN 1 ELSE 0 END)) / (SUM((CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END))) * 100) as compliance FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveAccount` as a on a.id=n.accountId left outer Join `productiveUser` as u on u.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id and `unitAssigned`=0 AND (`primaryUnit`=7 or `primaryUnit`=6) order by `primaryUnit` DESC LIMIT 0,1) where d.active>0 AND n.accountId={$acct2} AND n.dayDate >= '{$startDate}' AND n.dayDate <= '{$endDate}' group by n.accountId, n.deptId  
ORDER BY hrsVarianceTotal $sort");
$data2 = Config::get('db') -> get_results("select n.atotal, n.deptId, n.id as recordId, n.ltotal, n.ocount, n.nvariance, n.ldcount, n.obed, a.displayProd, n.procedureCount, (n.skill1val + n.skill2val + n.skill3val + n.skill4val + n.skill5val + n.skill6val) as Hours, n.actualWHP, n.antecount, n.chargecount, n.customNurse, n.seccount, n.sittercount, n.techcount, n.totalHours, DATE_FORMAT(n.dayDate,'%m/%d') as newDate, s.shift as shiftName, d.shiftsperDay, d.prodMeasure, n.antecount as rn, CONCAT(ROUND(n.nproductivity,1),'%') as prod, n.hrsVariance, n.techcount as techs, n.note, u.last_name as charge, d.dept as deptName, DATE_FORMAT(CURDATE(),'%m%d%y') as filedate, u.last_name as Director, DATE_SUB(CURDATE(), INTERVAL (a.payPeriod-(ROUND(a.payPeriod-((((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod)-FLOOR((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod))*a.payPeriod),0))) DAY) AS startDay, DATE_ADD(CURDATE(), INTERVAL (ROUND(a.payPeriod-((((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod)-FLOOR((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod))*a.payPeriod),0)-1) DAY) AS endDay, a.id as accountId, SUM(CASE WHEN n.actualWHPimport>0 then n.hrsVarianceActual ELSE n.hrsVariance END) as hrsVarianceTotal, SUM(CASE WHEN n.actualWHPimport>0 then n.actualWHPimport ELSE n.actualWHP END) as actualWHPTotal FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveShifts` as s on s.id = n.shift left join `productiveAccount` as a on a.id=n.accountId left outer Join `productiveUser` as u on u.id=n.userId where n.activeRecord>0 AND d.active>0 AND n.accountId={$acct2} AND n.userId>0 AND n.dayDate >='{$startDate}' AND n.dayDate <= '{$endDate}' group by n.accountId, n.deptId, n.dayDate, n.shift  
ORDER BY d.dept ASC, n.dayDate DESC, n.shift DESC");

$pdf->AliasNbPages();
$pdf->SetFont('Arial','',12);
$pdf->AddPage('P');
$pdf->ImprovedTable($data);
$pdf->SetFont('Arial','',10);
$pdf->AddPage('L');
$pdf->SetWidths(array(55, 30, 15, 20, 20, 20, 15, 15, 15, 65));

foreach($data2 as $row2){
	
if(intval($row2['shiftsperDay'])>1){
	$date = $row2['newDate'] . ' - ' . $row2['shiftName']; 
}else{
	$date = $row2['newDate'];
}

if(intval($row2['deptId'])==1){
	$prod = '-';
}else{
	$prod = $row2['prod'];
}

if(intval($row2['prodMeasure'])==2){
	$rn = '-';
	$techs = '-';
	$patients = $row2['procedureCount'];
	$totalHours = $row2['Hours'];
	$actualWHP = $row2['actualWHPTotal'];
	$note = $row2['note'];
	//$record ="https://productivern.com/dashboard.php#reportView?i=".$row2['recordId'];
}else{
	$rn = $row2['antecount'] + $row2['chargecount'] + $row2['ocount'] + $row2['ldcount'];
	$techs = $row2['techs'];
	$patients = $row2['atotal'] + $row2['ltotal'] + $row2['obed'];
	$actualWHP = '-';
	$note = $row2['note'];
	$totalHours = round((($row2['antecount'] + $row2['ocount'] + $row2['ldcount'] + $row2['chargecount'] + $row2['customNurse'] + $row2['seccount'] + $row2['sittercount'] + $row2['techcount']) * 24 / $row2['shiftsperDay']),1);
	//$record ="https://productivern.com/dashboard.php#reportView?i=".$row2['recordId'];
}	

if(intval($row2['displayProd'])==1){
	$variance = $row2['hrsVarianceTotal'];
}else{
	$variance = $row2['nvariance'];
}

$pdf->Row(array($row2['deptName'],$date,$prod,$actualWHP,$variance,$patients,$totalHours,$rn,$techs,$note));
}
$filename=substr($data[0]['accountName'],0,4) . 'download' . $data[0]['filedate'] . '.pdf';
//$filelocation="/home/prnadmin/public_html/dailyreports/" . substr($data[0]['accountName'],0,4) . 'download' . $data[0]['filedate'] . ".pdf";

header("Content-Type: application/pdf");
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header('Content-Disposition: attachment; filename="'.$filename.'"');
header("Content-Transfer-Encoding: binary ");
$pdf->Output('D',$filename);

}

*/
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
                Tasks
            </h3>
        </div>

<div class="d-flex pull-right">
			<span class="m-subheader__daterange" id="tasks_daterangepicker">						
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

</div>

<!-- END: Subheader -->
<div class="m-content bg-secondary">
    <!--Begin::Main Portlet-->
	
       
	  <div class="m-portlet m-portlet--mobile">
	  <div class="m-portlet__body">
	  	
				<div class="row col-12">
                        <table id="crashTable"  class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%" style="color:black">
	                   		<thead>
                                <tr>
									<th width="30%" id="unitName" data-priority="1">Description</th>
									<th width="20%" data-priority="2">Date (Due By)</th>
									<th width="25%" data-priority="3">Completed By</th>
									<th width="25%" data-priority="4">Date / Time</th>
                                </tr>
                            </thead>
                        </table>
				</div>
			
        </div>
    </div>
    <!--End::Main Portlet-->

</div>
    <!--begin::Modal-->
	    <!--begin::Modal-->


<!-- /begin modal -->
<div class="modal fade" id="editCrash" tabindex="-1" role="basic" aria-hidden="true">
        <div class="col-sm-5">
		<div class="modal-dialog modal-md">
            <div class="modal-content">
			
                <div class="modal-header bg-info">
				  <h4 class="modal-title text-white"><span id="cartDesc"></span>
				  </h4>
				  
				  				  				
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
				
                <div class="modal-body">
				<input id="crashpracticeId" type="hidden" value="" />
				<input id="crashRole" type="hidden" value="<?php echo $role; ?>" />
				<div class="row" style="padding-bottom: 10px;">
				<div class="modal-title"><span id="cartInstructions"></span>
				  </div>
				  </div>
				<div class="row">
                        <div class="col-sm-12">
						<div class="modal-title">Unit: <span id="cartDept"></span>
				  </div>
				  <div class="modal-title">Due Time: <span id="dueTime"></span>
				  </div>
						<div class="modal-title">Updated:  <span id="crashdate"></span>
				  </div>
						<div class="modal-title">By:   <span id="userNamecrash"></span>
							</div>											
						
							
                        </div>
                       </div>
                    <!--begin::Form-->
                    <form id="addcrashform" class="m-form m-form--fit m-form--label-align-right" style="padding-top: 20px;">
						<input id="dataIdCRASH" type="hidden" value="" />
						<input id="deptIdCRASH" type="hidden" value="" />
						<input id="taskNameCRASH" type="hidden" value="" />
						<input id="taskEscOrig" type="hidden" value="" />
				
						<div class="m-portlet__body">
						<div class="p-3 mb-2 bg-secondary text-dark">
						<h5 class="modal-title"><span id="title1"></span></h5>
						
						
						<span id="a1"></span>
						<span id="a2"></span>
						<span id="a3"></span>
						<span id="a4"></span>
						<span id="a5"></span>
						<span id="a6"></span>
						<span id="a7"></span>
						<span id="a8"></span>
						<span id="a9"></span>
						<span id="a10"></span>
						<span id="a11"></span>
						<span id="a12"></span>
						<span id="a13"></span>
						<span id="a14"></span>
						<span id="a15"></span>
						<!--
						<span id="a16"></span>
						<span id="a17"></span>
						<span id="a18"></span>
						<span id="a19"></span>
						<span id="a20"></span>
						-->
						<input id="at1" type="hidden" value="" />
						<input id="at2" type="hidden" value="" />
						<input id="at3" type="hidden" value="" />
						<input id="at4" type="hidden" value="" />
						<input id="at5" type="hidden" value="" />
						<input id="at6" type="hidden" value="" />
						<input id="at7" type="hidden" value="" />
						<input id="at8" type="hidden" value="" />
						<input id="at9" type="hidden" value="" />
						<input id="at10" type="hidden" value="" />
						<input id="at11" type="hidden" value="" />
						<input id="at12" type="hidden" value="" />
						<input id="at13" type="hidden" value="" />
						<input id="at14" type="hidden" value="" />
						<input id="at15" type="hidden" value="" />
						<input id="ar1" type="hidden" value="" />
						<input id="ar2" type="hidden" value="" />
						<input id="ar3" type="hidden" value="" />
						<input id="ar4" type="hidden" value="" />
						<input id="ar5" type="hidden" value="" />
						<input id="ar6" type="hidden" value="" />
						<input id="ar7" type="hidden" value="" />
						<input id="ar8" type="hidden" value="" />
						<input id="ar9" type="hidden" value="" />
						<input id="ar10" type="hidden" value="" />
						<input id="ar11" type="hidden" value="" />
						<input id="ar12" type="hidden" value="" />
						<input id="ar13" type="hidden" value="" />
						<input id="ar14" type="hidden" value="" />
						<input id="ar15" type="hidden" value="" />
						
						<hr></hr>
							<div class="form-group row">
                                <label for="crashsubmittedby" class="col-9 form-label" style="color:red">
                                    Submitted By (Required):
                                 <div>
                                    <input type="text" class="form-control" id="crashsubmittedby" placeholder="Enter your full name here.">
                                </div>
								</label>
                            </div>
							<input type="checkbox" id="taskEsc" name="taskEsc" onclick="tj.showtaskNote();" value="">
										 <strong>Escalate this Report</strong>
										</input>
							<textarea class="form-control" rows="3" placeholder="Escalation Details" id="taskNote"></textarea>
										
						 </div>  
						   </div>
                    </form>
                </div>
                <div class="modal-footer bg-info">
				     <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="submitCrash" class="btn btn-primary" onclick="tj.saveCrash();">Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
		</div>
        <!-- /.modal-dialog -->
 </div>

<div class="modal fade" id="editHuddle" tabindex="-1" role="basic" aria-hidden="true">
        
		<div class="modal-dialog modal-lg">
            <div class="modal-content">
			
                <div class="modal-header bg-info">
				  <h4 class="modal-title text-white">Daily Huddle</span>
				  </h4>
				  
				  				  				
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
				
                <div class="modal-body">
				<input id="HuddlepracticeId" type="hidden" value="" />
				
				<div class="row">
                        <div class="col-sm-12">
						<div class="modal-title">Unit: <span id="HuddleDept"></span>
				  </div>
				  
						<div class="modal-title">Date:  <span id="Huddledate"></span>
				  </div>
																	
						
							
                        </div>
                       </div>
				
                    <!--begin::Form-->
                    <form id="addHuddleform" class="m-form m-form--fit m-form--label-align-right" style="padding-top: 20px;">
						<input id="dataIdHuddle" type="hidden" value="" />
						<input id="deptIdHuddle" type="hidden" value="" />
						<input id="s1Orig" type="hidden" value="" />
						<input id="s2Orig" type="hidden" value="" />
						<input id="s3Orig" type="hidden" value="" />
						<input id="s4Orig" type="hidden" value="" />
						<input id="s1escOrig" type="hidden" value="" />
						<input id="s2escOrig" type="hidden" value="" />
						<input id="s3escOrig" type="hidden" value="" />
				
						<div class="m-portlet__body">
						<div class="p-3 mb-2 bg-secondary text-dark">
						<h5 class="modal-title"><span id="title1"></span></h5>
						<div id="sf1">
						<div class="title"  style="color:red">Safety Concern #1</div>
						
						<div class="form-group row">
						
						<div class="col-6">
                                <label for="s1Room" class="form-label" >
                                    Room / Location
                                 <div>
                                    <input type="text" class="form-control" id="s1Room" />
                                </div>
								</label>
                            </div>
							<div class="col-6">
                                <label for="s1" class="form-label">
                                    Primary Safety Concern
                                </label>
                                <div>
								<select id="s1" class="form-control" >
								<option value=""></option>
								<option value="0">n/a</option>
										<?php
									$s1 = Config::get('db')->get_results("SELECT c.*, s.typeDesc FROM `productivesafetyConfig` c left join `productivesafetyType` as s on s.id = c.safetyType WHERE (c.accountId=0 OR c.deptId IN (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId})) group by c.id order by s.id ASC, c.safetyDesc ASC");
									if($s1){
									foreach($s1 as $select1) { ?>
									<option value="<?php echo $select1['id']; ?>"><?php echo $select1['typeDesc'] ?> - <?php echo $select1['safetyDesc']?></option>
									<?php }
									} ?> 									
								</select>
                                    
                                </div>
							</div>
							
							</div>
							<div class="form-group row">
							
                                <label for="s1Notes" class="form-label col-12">
                                    Details
                                 <div>
                                    <textarea class="form-control" rows="3" id="s1Notes"></textarea>
                                </div>
								</label>
								
								</div>
							<div class="form-group row">
							<div class="col-md">
									<input type="checkbox" id="s1esc" name="s1esc" value="">
										 <strong>Escalate This Issue?</strong>
										</input>
										</div>
							<div class="col-md">
									<input type="checkbox" id="s1chk1" name="s1chk1" value="">
										 <strong>Debrief Completed?</strong>
										</input>
										</div>
							<div class="col-md">
											<input type="checkbox" id="s1chk2" name="s1chk2" value="">
										 <strong>ERS Completed?</strong>
										</input>
										
							</div>
							</div>
							<div class="form-group row">
							<div class="col-6" id="Add2">
							<button type="button" class="btn btn-sm btn-success" onclick="tj.addHuddle(2);">Add Another</button>
							</div>
							</div>
							</div>
					<div id="sf2">
						<hr style="background-color:red"></hr>
						<div class="title"  style="color:red">Safety Concern #2</div>
						
						<div class="form-group row">
						
						<div class="col-6">
                                <label for="s2Room" class="form-label" >
                                    Room / Location
                                 <div>
                                    <input type="text" class="form-control" id="s2Room" />
                                </div>
								</label>
                            </div>
							<div class="col-6">
                                <label for="s2" class="form-label">
                                    Primary Safety Concern
                                </label>
                                <div>
								<select id="s2" class="form-control" >
								<option value=""></option>
								<option value="0">n/a</option>
										<?php
									$s1 = Config::get('db')->get_results("SELECT c.*, s.typeDesc FROM `productivesafetyConfig` c left join `productivesafetyType` as s on s.id = c.safetyType WHERE (c.accountId=0 OR c.deptId IN (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId})) group by c.id order by s.id ASC, c.safetyDesc ASC");
									if($s1){
									foreach($s1 as $select1) { ?>
									<option value="<?php echo $select1['id']; ?>"><?php echo $select1['typeDesc'] ?> - <?php echo $select1['safetyDesc']?></option>
									<?php }
									} ?> 									
								</select>
                                    
                                </div>
							</div>
							
							</div>
							<div class="form-group row">
							
                                <label for="s2Notes" class="form-label col-12">
                                    Details
                                 <div>
                                    <textarea class="form-control" rows="3" id="s2Notes"></textarea>
                                </div>
								</label>
								
								</div>
							<div class="form-group row">
							<div class="col-md">
									<input type="checkbox" id="s2esc" name="s2esc" value="">
										 <strong>Escalate This Issue?</strong>
										</input>
										</div>
							<div class="col-md">
									<input type="checkbox" id="s2chk1" name="s2chk1" value="">
										 <strong>Debrief Completed?</strong>
										</input>
										</div>
							<div class="col-md">
											<input type="checkbox" id="s2chk2" name="s2chk2" value="">
										 <strong>ERS Completed?</strong>
										</input>
										
							</div>
							</div>
							<div class="form-group row">
							<div class="col-6" id="Add3">
							<button type="button" class="btn btn-sm btn-success" onclick="tj.addHuddle(3);">Add Another</button>
							</div>
							</div>
							</div>
							
						<div id="sf3">
						<hr style="background-color:red"></hr>
						<div class="title"  style="color:red">Safety Concern #3</div>
						
						<div class="form-group row">
						
						<div class="col-6">
                                <label for="s3Room" class="form-label" >
                                    Room / Location
                                 <div>
                                    <input type="text" class="form-control" id="s3Room" />
                                </div>
								</label>
                            </div>
							<div class="col-6">
                                <label for="s3" class="form-label">
                                    Primary Safety Concern
                                </label>
                                <div>
								<select id="s3" class="form-control" >
								<option value=""></option>
								<option value="0">n/a</option>
										<?php
									$s1 = Config::get('db')->get_results("SELECT c.*, s.typeDesc FROM `productivesafetyConfig` c left join `productivesafetyType` as s on s.id = c.safetyType WHERE (c.accountId=0 OR c.deptId IN (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId})) group by c.id order by s.id ASC, c.safetyDesc ASC");
									if($s1){
									foreach($s1 as $select1) { ?>
									<option value="<?php echo $select1['id']; ?>"><?php echo $select1['typeDesc'] ?> - <?php echo $select1['safetyDesc']?></option>
									<?php }
									} ?> 									
								</select>
                                    
                                </div>
							</div>
							
							</div>
							<div class="form-group row">
							
                                <label for="s3Notes" class="form-label col-12">
                                    Details
                                 <div>
                                    <textarea class="form-control" rows="3" id="s3Notes"></textarea>
                                </div>
								</label>
								
								</div>
							<div class="form-group row">
							<div class="col-md">
									<input type="checkbox" id="s3esc" name="s3esc" value="">
										 <strong>Escalate This Issue?</strong>
										</input>
										</div>
							<div class="col-md">
									<input type="checkbox" id="s3chk1" name="s3chk1" value="">
										 <strong>Debrief Completed?</strong>
										</input>
										</div>
										<div class="col-md">
											<input type="checkbox" id="s3chk2" name="s3chk2" value="">
										 <strong>ERS Completed?</strong>
										</input>
										
							</div>
							</div>
							<!--
							<div class="form-group row">
							<div class="col-6" id="Add4">
							<button type="button" class="btn btn-sm btn-success" onclick="tj.addHuddle(4);">Add Another</button>
							</div>
							</div>
							-->
							</div>
						<!--	
							<div id="sf4">
						<hr style="background-color:red"></hr>
						<div class="title"  style="color:red">Safety Concern #4</div>
						
						<div class="form-group row">
						
						<div class="col-6">
                                <label for="s4Room" class="form-label" >
                                    Pt Room:
                                 <div>
                                    <input type="text" class="form-control" id="s4Room" />
                                </div>
								</label>
                            </div>
							<div class="col-6">
                                <label for="s4" class="form-label">
                                    Primary Concern:
                                </label>
                                <div>
								<select id="s4" class="form-control" >
								<option value=""></option>
								<option value="0">n/a</option>
																	
								</select>
                                    
                                </div>
							</div>
							
							</div>
							<div class="form-group row">
							
                                <label for="s4Notes" class="form-label col-12">
                                    Details:
                                 <div>
                                    <textarea class="form-control" rows="3" id="s4Notes"></textarea>
                                </div>
								</label>
								
								</div>
							<div class="form-group row">
							<div class="col-6">
									<input type="checkbox" id="s4chk1" name="s4chk1" value="">
										 <strong>Debrief Completed?</strong>
										</input>
										</div>
										<div class="col-6">
											<input type="checkbox" id="s4chk2" name="s4chk2" value="">
										 <strong>ERS Completed?</strong>
										</input>
										
							</div>
							</div>
						
							</div>
							-->
							<hr></hr>
							<div class="form-group row">
                                <label for="dnotes" class="col-12 form-label">
                                    <h4>Leadership Comments:</h4>
                                 <div>
								 <strong><span id="leaderComments"></span></strong>
                                    <textarea class="form-control" rows="4" id="dnotes" hidden></textarea>
                                </div>
								</label>
							</div>
							
						</div>	
					</div>  
						   
                    </form>
                </div>
                <div class="modal-footer bg-info">
				     <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="submitHuddle" class="btn btn-primary" onclick="tj.updateHuddle();">Save</button>
                </div>
          </div>
            <!-- /.modal-content -->
        </div>
</div>
						
				
							