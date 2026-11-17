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
//$displayProd = intval($_SESSION['account']['displayProd']);
$displayProd = intval($_SESSION['account']['rnVariance']);
$userId = intval($_SESSION['account']['id']);
$prodCount = intval($_SESSION['account']['prodCount']);
$deptId = intval($_SESSION['account']['deptNum']);
$prodMeasure = intval($_SESSION['account']['prodMeasure']);
$secLab = $_SESSION['account']['secLabel'];
$accountId = $_SESSION['account']['accountId'];
$dayRank = intval($_SESSION['account']['useDayRank']);


if($displayProd ==0 && $prodCount==1 && $role<8){
	$varianceHeader = "RN Variance";
}else{
	$varianceHeader = "Variance";
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

$dbText = Config::get('db')->get_results("SELECT d.*, SUM(d.escalations) as sumEsc, (SELECT (`dayRank`) FROM `productiveDayRank` WHERE `deptId`={$deptId} AND `dayDate`=u.lastStartDate AND `dayDate`=u.lastEndDate) as dayRankVal, (SELECT (`id`) FROM `productiveDayRank` WHERE `deptId`={$deptId} AND `dayDate`=u.lastStartDate AND `dayDate`=u.lastEndDate ) as dayRankId, COUNT(d.id) as countDept, DAYOFYEAR(u.lastStartDate) AS lastStartDate, DAYOFYEAR(u.lastEndDate) AS lastEndDate, u.id as userId from `ProductiveDept` d left outer join `productiveUser` as u on u.id ={$userId} left outer join `productiveDeptXref` as x on x.userId = u.id where (d.id IN (SELECT `deptId` from `productiveDeptXref` WHERE `userId`={$userId}) or u.role>10) AND d.accountId={$accountId}");

if($dbText){
	$countDept = $dbText[0]['countDept'];
	$lastStartDate = $dbText[0]['lastStartDate'];
	$lastEndDate = $dbText[0]['lastEndDate'];
	$dayRankVal = $dbText[0]['dayRankVal'];
}else{
	$countDept = 0;
	$lastStartDate = '';
	$lastEndDate = '';
	$dayRankVal = 0;
}

if(($role<7 || $role==11) && intval($dbText[0]['countDept']) != intval($dbText[0]['sumEsc'])){
	$hidden = '';
}else{
	$hidden = "hidden";
}

$dbUnit = Config::get('db')->get_results("SELECT a.*, d.dept, d.active, u.role FROM `productiveAccountXref` a LEFT JOIN `ProductiveDept` as d on d.id=a.dept LEFT OUTER JOIN `productiveDeptXref` as x on x.deptId=d.id LEFT JOIN `productiveUser` as u on u.id={$userId} WHERE (d.id IN (SELECT `deptId` from `productiveDeptXref` WHERE `userId`={$userId}) or u.role>10) AND a.accountId={$accountId} AND d.active>0 GROUP BY a.dept ORDER BY d.dept ASC");

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
<style>
.vl {
  border-left: 3px solid DodgerBlue;
}
</style>
<!-- BEGIN: Subheader -->
<div class="m-subheader bg-light" style="padding-bottom: 30px;">
		<div class="mr-auto">
            <h3 class="m-subheader__title ">
                Productivity Reports - Nursing
            </h3>
        </div>

<div class="d-flex pull-right">

			<?php if($role>12) {
			$dbLocation = Config::get('db')->get_results("SELECT a.* FROM `productiveAccount` a WHERE a.enterpriseId={$_SESSION['account']['enterpriseId']} group by a.id order by a.name ASC");
			}else{
			$dbLocation = false;	
			}
			if($dbLocation){ ?>
			<select  class="bs-select form-control input-sm" id="locationFilter" name="locationFilter" onchange="tj.reportsSelect();" hidden>
			<option value="0">All Locations</option>
			<?php foreach($dbLocation as $a) { ?>
			<option value="<?php echo $a['id'];?>"><?php echo $a['name'];?> </option>
			<?php 	} ?>
			</select>&nbsp;
			<?php }else{ ?>
			<select  class="bs-select form-control input-sm" id="locationFilter" name="locationFilter" onchange="tj.reportsSelect();" hidden>
			</select>
			<?php } ?>
			
			<?php if($role>7 && $role<=12) {
			$dbData = Config::get('db')->get_results("SELECT d.*, c.id as categoryId, c.categoryName from `ProductiveDept` d left join `productiveCategory` as c on c.id=d.category where d.prodMeasure !=2 AND c.id !=0 AND d.accountId={$_SESSION['account']['accountId']} group by c.id order by c.categoryName ASC");
			}else if($role>12) {
			$dbData = Config::get('db')->get_results("SELECT d.*, c.id as categoryId, c.categoryName from `ProductiveDept` d left join `productiveCategory` as c on c.id=d.category left outer join `productiveAccount` as a on a.id=d.accountId where d.prodMeasure !=2 AND c.id !=0 AND a.enterpriseId={$_SESSION['account']['enterpriseId']} group by c.id order by c.categoryName ASC");
			}else{
			$dbData = Config::get('db')->get_results("SELECT d.*, c.id as categoryId, c.categoryName from `ProductiveDept` d left join `productiveCategory` as c on c.id=d.category where c.id !=0 AND d.id IN (SELECT `deptId` from `productiveDeptXref` where d.prodMeasure !=2 AND `userId`={$_SESSION['account']['id']}) group by c.id order by c.categoryName ASC");	
			}
			
			if($dbData && count($dbData)>1){ ?>
			<select  class="bs-select form-control input-sm" id="serviceFilter" name="serviceFilter" onchange="tj.serviceSelect();" hidden>
			<option value="0">All Service Lines</option>
			<?php foreach($dbData as $m) { ?>
			<option value="<?php echo $m['categoryId'];?>"><?php echo $m['categoryName'];?> </option>
			<?php 	} ?>
			</select>
			<span class="m-subheader__daterange" id="staffing_daterangepicker">
			<?php }else{ ?>
			<span class="m-subheader__daterange" id="staffing_daterangepicker">	
			<?php } ?>
						
                <span class="m-subheader__daterange-label">
                    <span class="m-staffing__daterange-title"></span>
                    <span class="m-staffing__daterange-date m--font-brand"></span>
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
<div class="m-content bg-light">
    <!--Begin::Main Portlet-->
	
       
	  <div class="m-portlet m-portlet--mobile">
	  <div class="m-portlet__body">
	  <input id="startStaff" type="hidden" value="<?php echo $startPay; ?>" />
	<input id="endStaff" type="hidden" value="<?php echo $endPay; ?>" />
	
	<input id="staffingRole" type="hidden" value="<?php echo $role; ?>" />
	<input id="acctId" type="hidden" value="<?php echo $accountId; ?>" />
	<input id="startNew" name="startNew" type="text" hidden>
	<input id="endNew" name="endNew" type="text" hidden>
	<input id="pdfTitle" type="text" hidden>

	
	  
	    <div>
				
				
				<!--<button type="button" id="downloadCSV" class="btn btn-default pull-left" onclick="tj.downloadCSV();">CSV</button>
				-->
				 <div class="row pull-left">
							<?php if($role >10): ?>
				<form id="dateForm" name="dateForm" action="inc/data.php?req=getpdf" method="post" hidden>
				<button name="downloadPDF" id="downloadPDF" class="btn btn-info" type="submit">PDF</button>
				</form>
				<?php endif; ?>
				<?php if($role >10): ?>
				<form id="dateFormCSV" name="dateFormCSV" action="inc/data.php?req=downloadcsv" method="post">
				<button name="downloadCSV" id="downloadCSV" class="btn btn-success" type="submit">CSV</button>
				</form>
								<?php endif; ?>
                                <!--end: Dropdown-->
                            </div>
				<button type="button" id="escButton" class="btn btn-danger pull-right" data-target="#addEscalation3" data-toggle="modal" <?php echo $hidden; ?>>
                Escalation
                </button> 
				
				
				<?php if(($role==4|| $role==5 || $role==6 || $role==11) && $dayRank>0): ?>
				<button type="button" id="dayRank" class="btn btn-info pull-right" onclick="tj.getdayRating(<?php echo $deptId .",". $countDept;?>);">
                Rate Day
                </button>
				<?php endif; ?>
				</div>	
						<div class="row col-12">
                        <table id="prodTable"  class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%" style="color:black">
	                   		<thead>
                                <tr>
									<th width="20%" data-priority="1">Unit</th>
									<th width="20%" data-priority="2">Date/Time</th>
									<th width="10%" data-priority="3"><?php echo $varianceHeader; ?></th>
									<th width="10%" data-priority="4">Pts</th>
									<th width="10%" data-priority="5">Total Resources</th>
									<th id="col1">Charge</th>
									<th id="col2">RN</th>
									<th id="col3">Tech/CNA</th>
									<th id="col5">Other</th>									
									<th width="25%" data-priority="6">Note</th>
                                </tr>
                            </thead>
                        </table>
				</div>
			
        </div>
    </div>
    <!--End::Main Portlet-->

</div>
		

<!-- /begin modal -->
<div class="modal fade" id="addNEW" tabindex="-1" role="basic" aria-hidden="true">
        <div class="col-sm-12">
		<div class="modal-dialog modal-lg">
            <div class="modal-content">
			<div style="background-color:#0099FF">
                <div class="modal-header bg-info">
				  <h5 class="modal-title" style="color:white"><strong>STAFFING REPORT</strong>
				  </h5>
				  				  				
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
				</div>
                <div class="modal-body">
				
				<div class="row">
                        
						
						<div class="col-6 modal-title">Report Date/Shift:  <span id="reportdateNEW"></span> - <span id="reportshiftNEW"></span>
				  </div>
						
                           
							<div class="col-6 modal-title">Last Updated By:   <span id="userNameNEW"></span>
							</div>							
							
							<div class="col-6 modal-title">Variance:   <span id="varianceNEW"></span>
							</div>
							<!--
							<div class="modal-title">Open Beds:   <span id="openbedsNEW"></span>
							</div>
							
							<div class="modal-title">Blocked Beds:   <span id="blocked"></span>
							</div>
								-->					
						
							
                        
                       </div>
                    <!--begin::Form-->
                    <form id="add_prod_formNEW" class="m-form m-form--fit m-form--label-align-right" style="padding-top: 20px;">
                        <input id="userIdNEW" type="hidden" value="" />
						<input id="shiftNEW" type="hidden" value="" />
						<input id="dayNEW" type="hidden" value="" />
						<input id="dataIdNEW" type="hidden" value="" />
						<input id="oneto1Acuity" type="hidden" value="" />
						<input id="oneto2Acuity" type="hidden" value="" />
						<input id="oneto3Acuity" type="hidden" value="" />
						<input id="oneto4Acuity" type="hidden" value="" />
						<input id="oneto5Acuity" type="hidden" value="" />
						<input id="oneto6Acuity" type="hidden" value="" />
						<input id="deptIdNEW" type="hidden" value="" />
						<input id="shiftNEW" type="hidden" value="" />			
						<input id="acuityTotal" type="hidden" value="" />
						<input id="trackDC" type="hidden" value="" />
						<input id="trackAdd" type="hidden" value="" />
						<input id="trackInsert" type="hidden" value="" />
						<input id="useGrid" type="hidden" value="" />
						<input id="countchargereport" type="hidden" value="" />
						<input id="rnreport" type="hidden" value="" />
						<input id="rn1report" type="hidden" value="" />
						<input id="rn2report" type="hidden" value="" />
						<input id="showrnreport" type="hidden" value="" />
						<input id="submitreqd" type="hidden" value="" />
						
					<div class="m-portlet__body">
					<div class="p-3 mb-2 bg-info">
					<h5 class="modal-title" style="color:white">Patient Count</h5>
					
					</div>
					<div class="bg-light">
					<div class="form-group">
					<div class="row">							
					<div class="col-6" id="hiddentotal1">
                                <label for="patienttotalNEW">
                                    Current Patients<span id="patientTotalDesc"></span> :
                                
                                <div class="col-6">
								<input type="number" min="0" class="form-control number" id="patienttotalNEW"  onchange="tj.gridupdate()" style="text-align: right" />
                                </div>
					</label>			
                    </div>
					<div class="col-6" id="hiddendischarges">
                                <label for="trackDischarges">
                                    Planned Discharges (Daily) :
                                
                                <div class="col-6">
								<input type="number" min="0" class="form-control number" id="trackDischarges" style="text-align: right" />
                                </div>
								</label>
					</div>
					</div>
					</div>
					
						<hr></hr>
							
							<div class="modal-title" id="hiddentotal2"><strong>Patient counts in this section must add up to Current Patients.</strong>
							</div>
							<span id="rnacuity"></span>
							
							<div class="form-group">
							<div class="row">							
							<div class="col-md" id="hidden1" >
							<div class="col-9">
                                <label for="oneto1" class="col-form-label">
                                    <span id="desc1"></span>
                                <div class="col-9">
								<input type="number" min="0" class="form-control number" id="oneto1" style="text-align: right" onchange="tj.gridupdate2()" />
                                </div>
								</label>
                            </div>
							</div>
							<div class="col-md">
							<div id="hidden2" >
							<div class="col-9">
                                <label for="oneto2" class="col-form-label">
                                    <span id="desc2"></span>
                                <div class="col-9">
								<input type="number" min="0" class="form-control number" id="oneto2" style="text-align: right" onchange="tj.gridupdate2()" />
                                </div>
								</label>
								</div>
                            </div>
							</div>
							</div>
							</div>
							
							<div class="form-group">
							<div class="row">							
							<div class="col-md" id="hidden3">
							<div class="col-9">
                                <label for="highNEW" class="col-form-label">
                                    <span id="desc3"></span>
                                <div class="col-9">
								<input type="number" min="0" class="form-control number" id="highNEW" style="text-align: right" onchange="tj.gridupdate2()" />
                                </div>
								</label>
							</div>
							</div>
							<div class="col-md">
							<div id="hidden4">
							<div class="col-9">
                                <label for="medNEW" class="col-form-label">
                                    <span id="desc4"></span>
                                <div class="col-9">
								<input type="number" min="0" class="form-control number" id="medNEW" style="text-align: right" onchange="tj.gridupdate2()" />
                                </div>
								</label>
								</div>
                            </div>
							</div>
							</div>
							</div>
							
							<span id="patientCustomDivider"></span>
							<div class="form-group">
							<div class="row">							
							<div class="col-md" id="hidden5">
							<div class="col-9">
                                <label for="lowNEW" class="col-form-label">
                                    <span id="desc5"></span>
                                 <div class="col-9">
								<input type="number" min="0" class="form-control number" id="lowNEW" style="text-align: right" onchange="tj.gridupdate2()" />
                                </div>
								 </label>
                           </div>
							</div>
							<div class="col-md">
							<div id="hidden6">
							<div class="col-9">
                                <label for="oneto6" class="col-form-label">
                                    <span id="desc6"></span>
                                <div class="col-9">
								<input type="number" min="0" class="form-control number" id="oneto6" style="text-align: right" onchange="tj.gridupdate2()" />
                                </div>
								</label>
								</div>
                            </div>
							</div>
							</div>
							</div>
							<hr></hr>
							<span id="acuityDesc"></span>
							
							<div class="form-group">
							<div class="row">							
							<div class="col-md" id="hidden8" >
							
                                <label for="oneto8" class="col-form-label">
                                    <span id="desc8"></span>
                                <div class="col-8">
								<input type="number" min="0" class="form-control number" id="oneto8" style="text-align: right" onchange="tj.gridupdate2()" />
                                </div>
								</label>
                            </div>
							<div class="col-md" id="hidden7">
                                <label for="oneto7" class="col-form-label">
                                    <span id="desc7"></span>
                                <div class="col-8">
								<input type="number" min="0" class="form-control number" id="oneto7" style="text-align: right" onchange="tj.gridupdate2()" />
                                </div>
								</label>
                            </div>
							</div>
							</div>
							
							<div class="form-group">
							<div class="row">							
							<div class="col-md" id="hidden9">
                                <label for="oneto9" class="col-form-label">
                                    <span id="desc9"></span>
                                <div class="col-8">
								<input type="number" min="0" class="form-control number" id="oneto9" style="text-align: right" onchange="tj.gridupdate2()" />
                                </div>
								</label>
                            </div>
							<div class="col-md" id="hidden10">
                                <label for="oneto10" class="col-form-label">
                                    <span id="desc10"></span>
                                <div class="col-8">
								<input type="number" min="0" class="form-control number" id="oneto10" style="text-align: right" onchange="tj.gridupdate2()" />
                                </div>
								</label>
                            </div>
							</div>
							</div>							
							
							<div class="form-group">
							<div class="row">							
							<div class="col-md" id="hidden11">
                                <label for="oneto11" class="col-form-label">
                                    <span id="desc11"></span>:
                                <div class="col-8">
								<input type="number" min="0" class="form-control number" id="oneto11" style="text-align: right" onchange="tj.gridupdate2()" />
                                </div>
								</label>
                            </div>
							</div>
							</div>
							</div>
						<div class="p-3 mb-2 bg-info">
						<h5 class="modal-title" style="color:white">Resources Currently in Staffing </h5>
						</div>
						<div class="bg-light">
						<div class="form-group">
						<div class="row">
						<div class="col-6">
                                <label for="chargecountNEW" class="col-5">
                                   <span id="chargeDesc"></span>:
								
                                <div>
                                    <select id="chargecountNEW" type="select" onchange="tj.gridupdate2()" class="form-control">
										<option value="0">0</option>
										
										<option value="0.5">0.5</option>
										<option value="1">1</option>
										<option value="2">2</option>
										</select>
                                </div>
						   </label>
						   
						   <label for="cnGrid" class="col-3" id="cngrid1">
                                   Grid:
								
                                <div>
                                  <input type="text" class="form-control" id="cnGrid" name="cnGrid" disabled>
                                </div>
						   </label>
						   <label for="cnVariance" class="col-3" id="cngrid2">
                                   Variance:
								
                                <div>
                                  <input type="text" class="form-control" id="cnVariance" name="cnVariance" disabled>
                                </div>
						   </label>
						   
                            </div>
							
						<div class="col-6 vl">	
					
                                <label for="nursecountNEW" class="col-5">
                                    <span id="nurseDesc"></span>:
                                
                                <div>
                                    <select id="nursecountNEW" type="select" onchange="tj.gridupdate2()" class="form-control">
										<option value="0">0</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
										<option value="6">6</option>
										<option value="7">7</option>
										<option value="8">8</option>
										<option value="9">9</option>
										<option value="10">10</option>
										<option value="11">11</option>
										<option value="12">12</option>
										<option value="13">13</option>
										<option value="14">14</option>
										<option value="15">15</option>
										<option value="16">16</option>
										<option value="17">17</option>
										<option value="18">18</option>
										<option value="19">19</option>
										<option value="20">20</option>
										</select>
                                </div>
								</label>
							<label for="rnGrid" class="col-3" id="rngrid1">
                                 Grid: 
								
                                <div>
                                  <input type="text" class="form-control" id="rnGrid" name="rnGrid" disabled>
                                </div>
						   </label>
						   <label for="rnVariance" class="col-3" id="rngrid2">
                                 Variance:  
								
                                <div>
                                  <input type="text" class="form-control" id="rnVariance" name="rnVariance" disabled>
                                </div>
						   </label>
								
                            </div>
							</div>
							</div>
						<div class="form-group">
						<div class="row">
						<div class="col-6">
						<div id="Nurse1">
						
                                <label for="nurse1_add" class="col-5">
                                   <span id="nurse1Desc"></span>
                                
                                <div>
                                    <select id="nurse1_add" type="select" onchange="tj.gridupdate2()" class="form-control">
										<option value="0">0</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
										<option value="6">6</option>
										<option value="7">7</option>
										<option value="8">8</option>
										<option value="9">9</option>
										<option value="10">10</option>
										</select>
                                </div>
							</label>
							<label for="rn1Grid" class="col-3" id="rn1grid1">
                                Grid:  
								
                                <div>
                                  <input type="text" class="form-control" id="rn1Grid" style="text-align: left" disabled>
                                </div>
						   </label>
						   <label for="rn1Variance" class="col-3" id="rn1grid2">
                                Variance:   
								
                                <div>
                                  <input type="text" class="form-control" id="rn1Variance" style="text-align: left" disabled>
                                </div>
						   </label>					 
                            </div>
							</div>
							<div class="col-6 vl">
							<div id="Nurse2">
                                <label for="nurse2_add" class="col-5">
                                   <span id="nurse2Desc"></span>
                                
                                <div>
                                    <select id="nurse2_add" type="select" onchange="tj.gridupdate2()" class="form-control">
										<option value="0">0</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
										<option value="6">6</option>
										<option value="7">7</option>
										<option value="8">8</option>
										<option value="9">9</option>
										<option value="10">10</option>
										</select>
                                </div>
							</label>
							<label for="rn2Grid" class="col-3" id="rn2grid1">
                                Grid:  
								
                                <div>
                                  <input type="text" class="form-control" id="rn2Grid" style="text-align: left" disabled>
                                </div>
						   </label>
						   <label for="rn2Variance" class="col-3" id="rn2grid2">
                                Variance:   
								
                                <div>
                                  <input type="text" class="form-control" id="rn2Variance" style="text-align: left" disabled>
                                </div>
						   </label>
							</div>
                            </div>
							</div>
							</div>
							<div class="form-group">
							<div class="row">
							<div class="col-6">
							<div id="techView">
                                <label for="techcountNEW" class="col-5">
                                   <span id="techLabel"></span>
                                
                                <div>
                                    <select id="techcountNEW" type="select" onchange="tj.gridupdate2()" class="form-control">
										<option value="0">0</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
										<option value="6">6</option>
										<option value="7">7</option>
										<option value="8">8</option>
										<option value="9">9</option>
										<option value="10">10</option>
										</select>
                                </div>
							</label>
							 <label for="pctGrid" class="col-3" id="pctgrid1">
                                Grid:  
								
                                <div>
                                  <input type="text" class="form-control" id="pctGrid" style="text-align: left" disabled>
                                </div>
						   </label>
						   <label for="pctVariance" class="col-3" id="pctgrid2">
                                Variance:   
								
                                <div>
                                  <input type="text" class="form-control" id="pctVariance" style="text-align: left" disabled>
                                </div>
						   </label>
						  </div>
                            </div>
						<div class="col-6 vl">
						<div id="secView">
                                <label for="seccountNEW" class="col-5">
                                   <span id="secLabel"></span>
                                
                                <div>
                                    <select id="seccountNEW" type="select" onchange="tj.gridupdate2()" class="form-control">
										<option value="0">0</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
										<option value="6">6</option>
										<option value="7">7</option>
										<option value="8">8</option>
										<option value="9">9</option>
										<option value="10">10</option>
										</select>
                                </div>
							</label>
							<label for="secGrid" class="col-3" id="secgrid1">
                                Grid:  
								
                                <div>
                                  <input type="text" class="form-control" id="secGrid" style="text-align: left" disabled>
                                </div>
						   </label>
						   <label for="secVariance" class="col-3" id="secgrid2">
                                Variance:   
								
                                <div>
                                  <input type="text" class="form-control" id="secVariance" style="text-align: left" disabled>
                                </div>
						   </label>
						   </div>
                            </div>
							</div>
							</div>
						<div class="form-group">
						<div class="row">
						<div class="col-6">
						<div id="other1hide">
                                <label for="other1" class="col-5">
                                   <span id="other1Desc"></span>:
                                
                                <div>
                                    <select id="other1" type="select" onchange="tj.gridupdate2()" class="form-control">
										<option value="0">0</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
										<option value="6">6</option>
										<option value="7">7</option>
										<option value="8">8</option>
										<option value="9">9</option>
										<option value="10">10</option>
										<option value="11">11</option>
										<option value="12">12</option>
										<option value="13">13</option>
										<option value="14">14</option>
										<option value="15">15</option>
										<option value="16">16</option>
										<option value="17">17</option>
										<option value="18">18</option>
										<option value="19">19</option>
										<option value="20">20</option>
										</select>
                                </div>
								</label>
								<label for="other1Grid" class="col-3" id="other1grid1">
                                Grid:  
								
                                <div>
                                  <input type="text" class="form-control" id="other1Grid" style="text-align: left" disabled>
                                </div>
						   </label>
						   <label for="other1Variance" class="col-3" id="other1grid2">
                                Variance:   
								
                                <div>
                                  <input type="text" class="form-control" id="other1Variance" style="text-align: left" disabled>
                                </div>
						   </label>
						   </div>
                            </div>
						<div class="col-6 vl">
						<div id="sittersNEWhide">
                                <label for="sittersNEW" class="col-5">
									<span id="sittersNEWDesc"></span>:
                                
                                <div>
                                    <select id="sittersNEW" type="select" onchange="tj.gridupdate2()" class="form-control">
										<option value="0">0</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
										<option value="6">6</option>
										<option value="7">7</option>
										<option value="8">8</option>
										<option value="9">9</option>
										<option value="10">10</option>
										<option value="11">11</option>
										<option value="12">12</option>
										<option value="13">13</option>
										<option value="14">14</option>
										<option value="15">15</option>
										<option value="16">16</option>
										<option value="17">17</option>
										<option value="18">18</option>
										<option value="19">19</option>
										<option value="20">20</option>
										</select>
                                </div>
								</label>
								<label for="sitterGrid" class="col-3" id="sittergrid1">
                                Grid:  
								
                                <div>
                                  <input type="text" class="form-control" id="sitterGrid" style="text-align: left" disabled>
                                </div>
						   </label>
						   <label for="sitterVariance" class="col-3" id="sittergrid2">
                                Variance:   
								
                                <div>
                                  <input type="text" class="form-control" id="sitterVariance" style="text-align: left" disabled>
                                </div>
						   </label>
						   </div>
                           </div>
						   </div>
						   </div>
						<div class="form-group">
						<div class="row">
						<div class="col-6">
						<div id="other2hide">
                                <label for="other2" class="col-5">
                                   <span id="other2Desc"></span>:
                                
                                <div>
                                    <select id="other2" type="select" onchange="tj.gridupdate2()" class="form-control">
										<option value="0">0</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
										<option value="6">6</option>
										<option value="7">7</option>
										<option value="8">8</option>
										<option value="9">9</option>
										<option value="10">10</option>
										<option value="11">11</option>
										<option value="12">12</option>
										<option value="13">13</option>
										<option value="14">14</option>
										<option value="15">15</option>
										<option value="16">16</option>
										<option value="17">17</option>
										<option value="18">18</option>
										<option value="19">19</option>
										<option value="20">20</option>
										</select>
                                </div>
								</label>
								<label for="other2Grid" class="col-3" id="other2grid1">
                                Grid:  
								
                                <div>
                                  <input type="text" class="form-control" id="other2Grid" style="text-align: left" disabled>
                                </div>
						   </label>
						   <label for="other2Variance" class="col-3" id="other2grid2">
                                Variance:   
								
                                <div>
                                  <input type="text" class="form-control" id="other2Variance" style="text-align: left" disabled>
                                </div>
						   </label>
						   </div>
                            </div>
						<div class="col-6 vl">
						<div id="other3hide">
                                <label for="other3" class="col-5">
                                   <span id="other3Desc"></span>:
                                
                                <div>
                                    <select id="other3" type="select" onchange="tj.gridupdate2()" class="form-control">
										<option value="0">0</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
										<option value="6">6</option>
										<option value="7">7</option>
										<option value="8">8</option>
										<option value="9">9</option>
										<option value="10">10</option>
										<option value="11">11</option>
										<option value="12">12</option>
										<option value="13">13</option>
										<option value="14">14</option>
										<option value="15">15</option>
										<option value="16">16</option>
										<option value="17">17</option>
										<option value="18">18</option>
										<option value="19">19</option>
										<option value="20">20</option>
										</select>
                                </div>
								</label>
								<label for="other3Grid" class="col-3" id="other3grid1">
                                Grid:  
								
                                <div>
                                  <input type="text" class="form-control" id="other3Grid" style="text-align: left" disabled>
                                </div>
						   </label>
						   <label for="other3Variance" class="col-3" id="other3grid2">
                                Variance:   
								
                                <div>
                                  <input type="text" class="form-control" id="other3Variance" style="text-align: left" disabled>
                                </div>
						   </label>
						   </div>
                            </div>
							</div>
							</div>
							<hr></hr>
						<div class="form-group">
						<div class="row">
						<div class="col-6">
						<div id="hidernvariance">
				
							<label for="showrnvariance" class="col-9 form-label">
                                <span id="varName1"></span> 
														
                                 <input type="text" class="col-4 form-control" id="showrnvariance" style="text-align: right" disabled>
								 <span id="varComment"></span>
								 </label>
								
								 </div>
							</div>
						   <div class="col-6">
							<div id="hidegridvariance">
						  
							<label for="showgridvariance" class="col-9 form-label">
                                <span id="varName2"></span>  
					 
								
                                  <input type="text" class="col-4 form-control" id="showgridvariance" style="text-align: right" disabled>
                            </label>
							 
						  </div>
						   </div>
						   </div>
							</div>
						</div>
						<div id="showtrack">
						
						<div class="p-3 mb-2 bg-info">
						<h5 class="modal-title" style="color:white">In-Shift Data Tracking </h5>
						</div>
						<div class="bg-light">						
						<div class="form-group">
						<div class="row">
						<div class="col-md" id="hidetrack1">
                                <label for="track1">
                                   <span id="track1Desc"></span>
                                
                                <div class="col-6">
                                 <input type="number" min="0" class="form-control number" id="track1">
                                </div>
								
								<div class="col-12">					
								<textarea class="form-control" rows="2" placeholder="Note" id="trackNote1"></textarea>
                            </div>
							</label>
						</div>
						
						<div class="col-md" id="hidetrack2">
							<label for="track2">
                                   <span id="track2Desc"></span>
                                
                                <div class="col-6">
								<input type="number" min="0" class="form-control number" id="track2">
                                </div>
								
								<div class="col-12">
								<textarea class="form-control" rows="2" placeholder="Note" id="trackNote2"></textarea>
							</div>
							</label>
                        </div>
						</div>
						</div>
							
						<div class="form-group">
						<div class="row">
						<div class="col-md" id="hidetrack3">
                                <label for="track3">
                                   <span id="track3Desc"></span>
                                
                                <div class="col-6">
                                 <input type="number" min="0" class="form-control number" id="track3">
                                </div>
								
								<div class="col-12">					
								<textarea class="form-control" rows="2" placeholder="Note" id="trackNote3"></textarea>
                            </div>
							</label>
						</div>
						
						<div class="col-md" id="hidetrack4">
							<label for="track4">
                                   <span id="track4Desc"></span>
                                
                                <div class="col-6">
								<input type="number" min="0" class="form-control number" id="track4">
                                </div>
								
								<div class="col-12">
								<textarea class="form-control" rows="2" placeholder="Note" id="trackNote4"></textarea>
							</div>
							</label>
                        </div>
						</div>
						</div>
						</div>						
						</div>
							
						<div id="showchurn1">
						
						<div class="p-3 mb-2 bg-info">
						<h5 class="modal-title" style="color:white">Churn </h5>
						</div>
						
							<div class="bg-light">
							<div class="form-group">
							<div class="row">
							<div class="col-md">
							<label for="admissions1">
                                   Admissions:
                                
                                <div>
                                 <input type="number" class="form-control number" id="admissions1">
                                </div>
								</label>
								</div>
							<div class="col-md">
                                <label for="transfers1">
                                   Transfers:
                                
                                <div>
								<input type="number" class="form-control number" id="transfers1">
                                </div>
								</label>
                            </div>
							<div class="col-md">
                                <label for="discharges1">
                                    Discharges:
                                 <div>
                                    <input type="number" class="form-control number" id="discharges1">
                                </div>
								</label>
                            </div>
							</div>
							</div>
							
							<div class="form-group m-form__group m--margin-top-5 row" id="customChurn1">
                                <label for="customChurn" class="col-7 col-form-label">
                                    <span id="customChurnLabel"></span>:
                                </label>
                                <div class="col-5">
                                    <input type="number" class="form-control number" id="customChurn">
                                </div>
                            </div>
							</div>
						</div>
					
							<div class="p-3 mb-2 bg-info">
                              <h5 class="modal-title" style="color:white"><span id="actionPlan"></span></h5>
								</div>
								<div class="bg-light">
								<div class="title" style="color:red">Do not include any Protected Health Information (PHI), as defined by the HIPAA Privacy Rule, in your comments.
					</div>	
								<div class="form-group m-form__group m--margin-top-10 row">
                                <textarea class="form-control" maxlength="250" rows="3" id="prodnoteNEW"></textarea>
                            </div>
							<div id="submitted">
                                <label for="submittedby" style="color:red">
                                    Submitted By (Required):
                                 <div>
                                    <input type="text" class="form-control" id="submittedby">
                                </div>
								</label>
                            </div>
											
						   </div>
						   </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
				     <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.updateProdNEW();">Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
		</div>
        <!-- /.modal-dialog -->
 </div>

 	<div class="modal fade" id="visits" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Productivity Calculator</h4>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <input id="visitsdataId" type="hidden" value="" />
						<input id="visitsaccountId" type="hidden" value="" />
						<input id="visitsdeptId" type="hidden" value="" />
						<input id="visitsDate" type="hidden" value="" />
						<input id="hoursrqd" type="hidden" value="" />
						
						<div class="form-body">
						<div class="title" style="padding-bottom: 20px;">
						Use this feature to calculate and over-write productivity and variance values for the entire day.  All in-shift productivity and hours variance estimates will be replaced with this value.
						</div>
                            <div class="form-group form-md-line-input">
                                <label for="visitTotal"><strong>Enter <span id="visitsType"></span> for <span id="visitsdayDate"></span>:</strong></label>
                                <input type="number" min="0" class="form-control number col-6" id="visitTotal">
                            </div>
							<hr></hr>
							<div class="form-group form-md-line-input" id="hrs0Total">
                                <label for="hoursTotal"><strong>Enter Total Hours for <span id="hoursdayDate"></span>:</strong></label>
								<span id="hoursReqd"></span>
								<input type="number" min="0" class="form-control number col-6" id="hoursTotal">
                            </div>
							<div class="form-group form-md-line-input" id="hrs1Total">
                                <label for="hrs1"><strong>Enter <span id="hrs1Desc"></span> for <span id="hoursdayDate1"></span>:</strong></label>
								<input type="number" min="0" class="form-control number col-6" id="hrs1">
                            </div>
							<div class="form-group form-md-line-input" id="hrs2Total">
                                <label for="hrs2"><strong>Enter Total <span id="hrs2Desc"></span> for <span id="hoursdayDate2"></span>:</strong></label>
								<input type="number" min="0" class="form-control number col-6" id="hrs2">
                            </div>
							<div class="form-group form-md-line-input" id="hrs3Total">
                                <label for="hrs3"><strong>Enter Total <span id="hrs3Desc"></span> for <span id="hoursdayDate3"></span>:</strong></label>
								<input type="number" min="0" class="form-control number col-6" id="hrs3">
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
				
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="noteButton" class="btn btn-primary" onclick="tj.visits();">Save</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
 </div>

	<div class="modal fade" id="addprodnote" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Variance Action Required</h4>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <input id="notedataId" type="hidden" value="" />
						<input id="notedeptId" type="hidden" value="" />
                        <div class="form-body">
                            <div class="form-group form-md-line-input">
                                <label for="notebody">Your Staffing Report shows a possible <span id="varianceType"></span> variance for <span id="dept"></span>.  Please add a note with an explanation or an action plan to correct the variance.</label>
                                <div class="title" style="color:red">Do not include any Protected Health Information (PHI), as defined by the HIPAA Privacy Rule, in your comments.
					</div>	
								<textarea class="form-control" rows="3" maxlength="250" id="notebody" required ></textarea>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
				<!--
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button> -->
                    <button type="button" id="noteButton" class="btn btn-primary" onclick="tj.addProdNote();">Save</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
 </div>
 
	<div class="modal fade" id="addprodnoteOrig" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Variance Action Required</h4>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <input id="notedataIdOrig" type="hidden" value="" />
						<input id="notedeptIdOrig" type="hidden" value="" />
                        <div class="form-body">
                            <div class="form-group form-md-line-input">
                                <label for="notebodyOrig">Your Staffing Report shows a possible <span id="varianceTypeOrig"></span> variance for <span id="deptOrig"></span>.  Please add a note with an explanation or an action plan to correct the variance.</label>
                                <textarea class="form-control" rows="3" maxlength="250" id="notebodyOrig" required ></textarea>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="noteButton" class="btn btn-primary" onclick="tj.addProdNoteOrig();">Save</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
 </div>

 <div class="modal fade" id="blockbeds" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <div class="modal-title"><span id="deptName"></span></div>
					
                </div>
                <div class="modal-body">
                    <form role="form">
                        <input id="deptId" type="hidden" value="" />
						<input id="accountId" type="hidden" value="" />
                        <div class="form-body">
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="blockedcount" class="col-7 col-form-label">
                                   Currently Blocked Beds:
                                </label>
                                <div class="col-5">
                                    <select id="blockedcount" type="select" class="form-control">
										<option value=0>0</option>
										<option value=1>1</option>
										<option value=2>2</option>
										<option value=3>3</option>
										<option value=4>4</option>
										<option value=5>5</option>
										<option value=1>6</option>
										<option value=2>7</option>
										<option value=3>8</option>
										<option value=4>9</option>
										<option value=5>10</option>
										<option value=1>11</option>
										<option value=2>12</option>
										<option value=3>13</option>
										<option value=4>14</option>
										<option value=5>15</option>
										<option value=1>16</option>
										<option value=2>17</option>
										<option value=3>18</option>
										<option value=4>19</option>
										<option value=5>20</option>
										</select>
                                </div>
                            </div>
												
                            <div class="form-group form-md-line-input">
                                <label for="blockedcomment">Comments:</label>
								<div class="title" style="color:red">Do not include any Protected Health Information (PHI), as defined by the HIPAA Privacy Rule, in your comments.
					</div>
                                <textarea class="form-control" rows="3" id="blockedcomment" required ></textarea>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="blockButton" class="btn btn-primary" onclick="updateblockedBeds();">Update</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
	
<div class="modal fade" id="escalationNEW" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h5 class="modal-title">Your Staffing Report has been submitted.</h5>
                </div>
                <div class="modal-body">
				<input id="esc" type="hidden" value="" />
				<input id="deptIdesc" type="hidden" value="" />
				<input id="dataIdesc" type="hidden" value="" />
                    <div class="modal-title"><strong><p>Do you have any current issues that require Escalation (ie. Staffing, Supplies, Close Calls, Team Member Recognition, etc.)?</p> <p>If so, please click YES below to escalate the issue. Otherwise, click No to complete your Report. </p></strong>
					</div>
                </div>
				
                <div class="modal-footer bg-light">
					<button type="button" id="Escbutton" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <button type="button" id="addescbutton" class="btn btn-secondary" onclick="tj.showaddesc();">Yes</button>
                    <!--<button type="button" id="addescbutton" class="btn btn-primary" data-target="#addEscalation" data-toggle="modal">Yes</button>-->
                </div>
				</div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
   
	
<div class="modal fade" id="addEscalation" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Escalation</h4>
					 </div>
                <div class="modal-body">
                    <form role="form">
                        <input id="dataId2" type="hidden" value="" />
						<input id="deptId2" type="hidden" value="" />
						<div class="form-body">
                            <div class="form-group form-md-line-input">
                                <label for="escval">Use this tool to Escalate an issue or Recognize a Team Member.  Your manager will be immediately notified via text and/or email.  Also, please document this issue as required on your Unit.</label>
                              <div class="form-group">	
										<select id="escval" name="escval" class="form-control">
										<option value="0">Select Escalation</option>
										<?php
								$dbData = Config::get('db')->get_results("SELECT * from `productiveAcctEscalations` where `accountId`={$_SESSION['account']['accountId']} order by `escalation` ASC");
								foreach($dbData as $m) 
								{ 
								?>
								<option value="<?php echo $m['id'];?>"><?php echo $m['escalation'];?> </option>
								<?php 
								} 
								?>									
										</select>
										</div>  
                            </div>
							<div class="form-group form-md-line-input">
                                <label for="escalationcomment">Comments:</label>
								<div class="title" style="color:red">Do not include any Protected Health Information (PHI), as defined by the HIPAA Privacy Rule, in your comments.
					</div>
                                <textarea class="form-control" rows="3" id="escalationcomment" ></textarea>
                            </div>
							
				
					  

                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" id="Escbutton" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="EscAddbutton" class="btn btn-primary" onclick="tj.addEscalation();">Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
	
<div class="modal fade" id="addEscalation3" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Escalation</h4>
					 </div>
                <div class="modal-body">
                    <form role="form">
                        <input id="dataId3" type="hidden" value="" />
						<div class="form-body">
                            <div class="form-group form-md-line-input">
                                <label for="escval3">Use this tool to Escalate an issue or Recognize a Team Member.  Your manager will be immediately notified via text and/or email.  Also, please document this issue as required on your Unit.</label>
                              	
										<select id="escval3" name="escval3" class="form-control">
										<option value="0">Select Escalation</option>
										<?php
								$dbData = Config::get('db')->get_results("SELECT * from `productiveAcctEscalations` where `accountId`={$_SESSION['account']['accountId']} order by `escalation` ASC");
								foreach($dbData as $m) 
								{ 
								?>
								<option value="<?php echo $m['id'];?>"><?php echo $m['escalation'];?> </option>
								<?php 
								} 
								?>									
										</select>
										
                            </div>
							<div class="form-group form-md-line-input">
                                <label for="deptId3">Which Unit is this Escalation for?</label>
                              	
										<select id="deptId3" name="deptId3" class="form-control">
										<?php
								$dbUnit = Config::get('db')->get_results("SELECT d.*, u.role from `ProductiveDept` d LEFT JOIN `productiveDeptXref` as x on x.deptId = d.id LEFT JOIN `productiveUser` as u on u.id={$_SESSION['account']['id']} where d.escalations=0 AND d.accountId={$_SESSION['account']['accountId']} AND (x.deptId in (SELECT `deptId` from `productiveDeptXref`where `userId`={$_SESSION['account']['id']}) OR u.role=11) group by d.id order by d.dept ASC");
								foreach($dbUnit as $b) 
								{ 
								?>
								<option value="<?php echo $b['id'];?>"><?php echo $b['dept'];?> </option>
								<?php 
								} 
								?>									
										</select>
										
                            </div>
							<div class="form-group form-md-line-input">
                                <label for="escalationcomment3">Comments:</label>
								<div class="title" style="color:red">Do not include any Protected Health Information (PHI), as defined by the HIPAA Privacy Rule, in your comments.
					</div>
                                <textarea class="form-control" rows="3" id="escalationcomment3" ></textarea>
                            </div>
							
				
					  
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" id="Escbutton3" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="EscAddbutton3" class="btn btn-primary" onclick="tj.addEscalation3();">Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>



    <!--end::Modal-->
</div>

<div class="modal fade" id="editEscalation" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Escalation Response</h4>
					 </div>
                <div class="modal-body col-12">
                    <form role="form">
                        <input id="dataIdedit" type="hidden" value="" />
						<div class="form-body">
							<div class="form-group">
                            <span id="editSubmitted" ></span>	
							</div>
							<div class="form-group">
                            <label for="editunit">Unit:</label>
							<div class="col-9">
                            <input class="form-control" id="editunit" value="" disabled>	
							</div>
							</div>
                            <div class="form-group">
                            <label for="edittype">Escalation Type:</label>
							<div class="col-9">
                             <input class="form-control" id="edittype" value="" disabled>
							</div>							 
                            </div>
							
							<div class="form-group">
                                <label for="escalationcommentedit">Comments:</label>
                                <textarea class="form-control" rows="4" id="escalationcommentedit" disabled></textarea>
                            </div>
							<hr></hr>
							<div class="form-group">
                                <label for="escalationresponse"><strong>Response:</strong></label>
								<div class="title" style="color:red">Do not include any Protected Health Information (PHI), as defined by the HIPAA Privacy Rule, in your comments.
							</div>
                                <textarea class="form-control" rows="3" id="escalationresponse" ></textarea>
                            </div>			  
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.updateEscalation();">Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>



    <!--end::Modal-->
</div>

<div class="modal fade" id="addDay" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Day Rating</h4>
					 </div>
                <div class="modal-body">
                    <form role="form">
                        <input id="dayrateId" type="hidden" value="" />
						<div class="form-body">
						<div class="form-group">	
								 
						 <div class="modal-title"><span id="dayUpdated"></span></div>
						 <div class="modal-title"><span id="dayUser"></span></div>
						 
					   </div>
					   <div class="form-group form-md-line-input">
                                <label for="dayDate">Select Date:</label>
                              	 <input id="dayDate" type="date" value="" />		
										
                            </div>
							<div class="form-group form-md-line-input">
                                <label for="dayUnit">Select Unit:</label>
                              			<select id="dayUnit" name="dayUnit" class="form-control" onchange="tj.checkday();">
										<option value="">Select</option>
										<?php foreach($dbUnit as $u) { ?>
										<option value="<?php echo $u['id'];?>"><?php echo $u['dept'];?> </option>
										<?php 	} ?>
										</select>
										
                            </div>
							<div class="form-group form-md-line-input">
                                <label for="daySD">Service Deliverable Achieved?</label>
                              			<select id="daySD" name="daySD" class="form-control">
										<option value="0">Select</option>
										<option value="1">Yes</option>
										<option value="2">No</option>
										</select>
										
                            </div>
							<div class="form-group form-md-line-input">
                                <label for="dayEE">Rate the Day for Employee Engagement:</label>
                              			<select id="dayEE" name="dayEE" class="form-control">
										<option value="0">Select</option>
										<option value="5">5 - Excellent</option>
										<option value="4">4 - Above Average</option>
										<option value="3">3 - Average</option>
										<option value="2">2 - Below Average</option>
										<option value="1">1 - Poor</option>										
										</select>
										
                            </div>				
				
					  
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.updatedayRating();">Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>



    <!--end::Modal-->
</div>

