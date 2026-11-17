<?php
ini_set('display_errors',1);
include "../inc/initializer.php";
require_once '.././inc/class.db.php';
require_once '.././inc/config.php';
//require_once '.././inc/FPDF/fpdf.php';
require_once '.././inc/class.fpdf.php';

if (!isset($_SESSION['account']) || ($_SESSION['account']['pwd'] == $_SESSION['account']['temp'])) {
	session_destroy();
    header('location: login.php');
    exit();
}


//$payPeriod = intval($_SESSION['account']['payPeriod']);
//$currentDay = intval($_SESSION['account']['currentDay']);
//$payFirst = intval($_SESSION['account']['firstDay']);
$role = intval($_SESSION['account']['role']);
$countDept = $_SESSION['account']['countDept'];
$displayProd = intval($_SESSION['account']['rnVariance']);
$userId = intval($_SESSION['account']['id']);
$prodCount = intval($_SESSION['account']['prodCount']);
$prodMeasure = intval($_SESSION['account']['prodMeasure']);
$secLab = $_SESSION['account']['secLabel'];
$accountId = $_SESSION['account']['accountId'];
$dayRank = intval($_SESSION['account']['useDayRank']);
$useEpic = intval($_SESSION['account']['eas']);
$vids = intval($_SESSION['account']['trainingVids']);
$acctEscalation = intval($_SESSION['account']['acctEscalation']);
$prodTerm = $_SESSION['account']['prodTerm'];
$hrsTerm = $_SESSION['account']['hrsTerm'];
$startPay = (isset($_SESSION['account']['newStartPay']) ? $_SESSION['account']['newStartPay']:0);
$endPay = (isset($_SESSION['account']['newEndPay']) ? $_SESSION['account']['newEndPay']:0);

if(intval($countDept)==1){
	$deptId = $_SESSION['account']['deptNum'];
}else{
	$deptId = 0;
}


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

if($useEpic==1){
$eas = " (Epic)";
}else{
$eas = '';	
}
/*
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

//$dbText = Config::get('db')->get_results("SELECT d.*, SUM(d.escalations) as sumEsc, (SELECT (`dayRank`) FROM `productiveDayRank` WHERE `deptId`={$deptId} AND `dayDate`=u.lastStartDate AND `dayDate`=u.lastEndDate) as dayRankVal, (SELECT (`id`) FROM `productiveDayRank` WHERE `deptId`={$deptId} AND `dayDate`=u.lastStartDate AND `dayDate`=u.lastEndDate ) as dayRankId, COUNT(d.id) as countDept, DAYOFYEAR(u.lastStartDate) AS lastStartDate, DAYOFYEAR(u.lastEndDate) AS lastEndDate, u.id as userId from `ProductiveDept` d left outer join `productiveUser` as u on u.id ={$userId} left outer join `productiveDeptXref` as x on x.userId = u.id where (d.id IN (SELECT `deptId` from `productiveDeptXref` WHERE `userId`={$userId}) or u.role>10) AND d.accountId={$accountId}");

//if($dbText){
	//$countDept = $dbText[0]['countDept'];
	//$lastStartDate = $dbText[0]['lastStartDate'];
	//$lastEndDate = $dbText[0]['lastEndDate'];
	//$dayRankVal = $dbText[0]['dayRankVal'];
//}else{
	//$countDept = 0;
	//$lastStartDate = '';
	//$lastEndDate = '';
	//$dayRankVal = 0;
//}


if($role==9 || $role==8 || $role>12){
$exec = 'disabled';
}else{
$exec = '';
}

if($role==11){
$escButton = '';
}else{
$escButton = "hidden";	
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
<div class="m-subheader bg-secondary" style="padding-bottom:10px;">
		<div class="mr-auto">
            <h3 class="m-subheader__title ">
                <?php echo $prodTerm; ?> - Nursing
            </h3>
        </div>

<div class="form-group">		
<div class="col-md-12">
<div class="row pull-right">
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
			
			<?php 
			$dbDatam = Config::get('db')->get_results("SELECT d.*, c.id as categoryId, c.categoryName from `ProductiveDept` d left join `productiveCategory` as c on c.id=d.category where d.prodMeasure !=2 AND d.prodMeasure !=3 AND c.id !=0 AND d.accountId={$_SESSION['account']['accountId']} group by c.id order by c.categoryName ASC");
			if($dbDatam && count($dbDatam)>1){ ?>
			<select  class="form-control" id="serviceFilter" name="serviceFilter" onchange="tj.serviceSelect();" hidden>
			<option value="0">All Service Lines</option>
			<?php foreach($dbDatam as $m) { ?>
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
		</div>	
        </div>
</div>
</div>

<!-- END: Subheader -->
<div class="content bg-secondary">
    <!--Begin::Main Portlet-->
	
       
	  <div class="portlet">
	  <div class="portlet-body">
	  <input id="startStaff" type="hidden" value="<?php echo $startPay; ?>" />
	<input id="endStaff" type="hidden" value="<?php echo $endPay; ?>" />
	
	<input id="staffingRole" type="hidden" value="<?php echo $role; ?>" />
	<input id="acctId" type="hidden" value="<?php echo $accountId; ?>" />
	<input id="startNew" name="startNew" type="text" hidden>
	<input id="endNew" name="endNew" type="text" hidden>
	<input id="pdfTitle" type="text" hidden>
	<input id="vids" type="hidden" value="<?php echo $vids; ?>" />
	<input id="acctEscalations" type="hidden" value="<?php echo $acctEscalation; ?>" />
	

	
				<!--<button type="button" id="downloadCSV" class="btn btn-default pull-left" onclick="tj.downloadCSV();">CSV</button>
				-->
				<div class="col-12">
				<div class="form-group">
				
				 <div class="row" style="padding-top: 20px;">
				
				
				
				<div class="col-6 pull-right"hidden>
				<button type="button" id="escButton" class="btn btn-danger pull-right" data-target="#addEscalation3" data-toggle="modal">
                Escalation
                </button> 
				</div>
				 <div class="col-6">
				 <?php if(intval($countDept)==1){ ?>
				<button type="button" id="insertReport" class="btn btn-success"  onclick="tj.editUnscheduled(<?php echo $deptId; ?>);" hidden>Unscheduled Report</button>
                 <?php }else{ ?>
				 <button type="button" id="insertReport" class="btn btn-success" data-target="#unscheduled" data-toggle="modal" hidden>Unscheduled Report</button>
				 <?php } ?>
                 </div>
				
				</div>
				</div>
				
				
						<div class="row">
                        <table id="prodTable"  class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%" style="color:black">
	                   		<thead>
                                <tr>
									<th width="20%" data-priority="1">Unit</th>
									<th width="20%" data-priority="2">Date/Time</th>
									<th width="10%" data-priority="3"><?php echo $varianceHeader; ?></th>
									<th width="12%" data-priority="4">Pts<?php echo $eas; ?></th>
									<th width="10%" data-priority="5">Staff Count</th>
									<th id="col1">Charge</th>
									<th id="col2">RN</th>
									<th id="col3">Orientation</th>
									<th id="col5">Other</th>									
									<th width="25%" data-priority="6">Note</th>
                                </tr>
                            </thead>
                        </table>
				</div>
			</div>
        </div>
    </div>
    <!--End::Main Portlet-->

</div>
		

<!-- /begin modal -->
<div class="modal fade" id="addNEW" tabindex="-1" role="basic" >
        
		<div class="modal-dialog modal-lg">
            <div class="modal-content">
			
                <div class="modal-header bg-info">
				  <h5 class="modal-title" style="color:white"><strong>STAFFING REPORT</strong>
				  </h5>
				  				  				
				<button type="button" class="close" data-dismiss="modal" ></button>
                </div>
				
                <div class="modal-body">
				
				<div class="row">
				<div class="col-md">
                 <div class="modal-title">Unit:  <span id="depNAME"></span>
				  </div> 
				  </div>
				  <div class="col-md">
				  <div class="modal-title">Created By:   <span id="userNameNEW"></span>
				</div>
				</div>
				</div>
				<div class="row">		
				<div class="col-md">
				<div class="modal-title">Report Date/Time:  <span id="reportdateNEW"></span>
				  </div>
				  </div>
				<div class="col-md">		
                 <div class="modal-title"><span id="varianceName"></span>   <span id="varianceNEW"></span>
							</div>
						</div>
				</div>	
				<div class="row">
				<div class="col-md">
				<span id="deleteRecord"></span>
				</div>
				<div class="col-md" id="showratio">		
                 <div class="modal-title">Staffing Ratio: <span id="ratioNEW"></span>
							</div>
						</div>
				</div>
				<div class="col-md" id="showHPPS">		
                 <div class="modal-title"><span id="hppsNEW"></span>
							</div>
						</div>
				
					
                    <!--begin::Form-->
                    <form id="add_prod_formNEW">
						<input id="ptRatio" type="hidden" value="" />
                        <input id="userIdNEW" type="hidden" value="" />
						<input id="newRecord" type="hidden" value="" />
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
						<input id="varmsg" type="hidden" value="" />
						<input id="roundRN" type="hidden" value="" />
						<input id="minrn" type="hidden" value="" />
						<input id="nedocs" type="hidden" value="" />
						<input id="roleNEW" type="hidden" value="" />
						<input id="breakId" type="text" hidden>
						<input id="pttotal2" type="text" hidden>
						<input id="pttotal3" type="text" hidden>
						<input id="pttotal4" type="text" hidden>
						<input id="LinkedId" type="hidden" value="" />
						<input id="LinkedDept" type="hidden" value="" />
						<input id="countNEW" type="hidden" value="" />
						<input id="useEAS" type="hidden" value="" />
						<input id="add1" type="hidden" value="" />
						<input id="add2" type="hidden" value="" />
						<input id="add3" type="hidden" value="" />
						<input id="add4" type="hidden" value="" />
						<input id="add5" type="hidden" value="" />
						<input id="add6" type="hidden" value="" />
						<input id="extadd6" type="hidden" value="" />
						<input id="useEASN" type="hidden" value="" />
						<input id="easTarget" type="hidden" value="" />
						<input id="easMax" type="hidden" value="" />
						<input id="hppdNEW" type="hidden" value="" />
						<input id="prodMeasureNEW" type="hidden" value="" />
						<input id="unlocked" type="hidden" value="" />
						<input id="grid1opt" type="hidden" value="" />
						<input id="grid2opt" type="hidden" value="" />
						<input id="grid3opt" type="hidden" value="" />
						<input id="other1Calc" type="hidden" value="" />
						<input id="rnvar" type="hidden" value="" />
						<input id="zeropts" type="hidden" />
						<input id="unscheduledNEW" type="hidden" value="" />
						<input id="bedCount" type="hidden" value="" />
						
				
				
					<div class="p-2 mb-2 bg-info">
					<h5 class="modal-title" style="color:white"><span id="patientcountTitle"></span></h5>
					
					</div>
					
					<div class="form-group" id="hiddentotal1">
					<div class="bg-secondary">
					<div class="row">							
					<div class="col-md-6">
					<div id="currentPatients">
                                <label for="patienttotalNEW" class="col-6">
                                    <span id="patientTotalDesc"></span>
                                
                                
								<input type="number" min="0" class="form-control number" id="patienttotalNEW"  onchange="tj.gridupdate()" style="text-align: right" />
                                
								</label>	
					</div>			
					
					<div id="currentPatients3">
                                <label for="patienttotalNEW3" class="col-6">
                                    <span id="patientTotalDesc3"></span>
                                
                                
								<input type="number" min="0" class="form-control number" id="patienttotalNEW3"  onchange="tj.gridupdate()" style="text-align: right" />
                                
								</label>
								</div>
				
					<span id="visits1"></span>					
                    </div>		
					<div class="col-md-6 vl">
					<div id="transfers">
					<span id="newTransfers1"></span>
					</div>
					<div id="currentPatients2">
                                <label for="patienttotalNEW2" class="col-6">
                                    <span id="patientTotalDesc2"></span>
                                
                                
								<input type="number" min="0" class="form-control number" id="patienttotalNEW2"  onchange="tj.gridupdate()" style="text-align: right" />
                                
								</label>
								</div>
				
					
					<div id="currentPatients4">
                                <label for="patienttotalNEW4" class="col-6">
                                    <span id="patientTotalDesc4"></span>
                                
                               
								<input type="number" min="0" class="form-control number" id="patienttotalNEW4"  onchange="tj.gridupdate()" style="text-align: right" />
                                
								</label>
								</div>
				
					<span id="discharges3"></span>
					</div>
					</div>
					</div>
					</div>
					
						
							
							<div class="modal-title" id="hiddentotal2"><strong>Patient Breakdown</strong>
							</div>
							<!--<span id="rnacuity"></span>-->
							
							<div class="form-group">
							<div class="bg-secondary">
							<div class="row">							
							
							<div class="col-md-6">
							<div id="hidden1" >
							<div class="col-9">
                                <label for="oneto1" class="col-form-label">
                                    <span id="desc1"></span>
                                <div class="col-9">
								<input type="number" min="0" class="form-control number" id="oneto1" style="text-align: right" onchange="tj.gridupdate2()" />
                                </div>
								</label>
                            </div>
							</div>							
							<div id="hidden3">
							<div class="col-9">
                                <label for="highNEW" class="col-form-label">
                                    <span id="desc3"></span>
                                <div class="col-9">
								<input type="number" min="0" class="form-control number" id="highNEW" style="text-align: right" onchange="tj.gridupdate2()" />
                                </div>
								</label>
							</div>
							</div>
							<div id="hidden5">
							<div class="col-9">
                                <label for="lowNEW" class="col-form-label">
                                    <span id="desc5"></span>
                                 <div class="col-9">
								<input type="number" min="0" class="form-control number" id="lowNEW" style="text-align: right" onchange="tj.gridupdate2()" />
                                </div>
								 </label>
                           </div>
							</div>
								<div id="exthidden6">
							<div class="col-9">
                                <label for="extoneto6" class="col-form-label">
                                    <span id="extdesc6"></span>
                                <div class="col-9">
								<input type="number" min="0" class="form-control number" id="extoneto6" style="text-align: right" onchange="tj.gridupdate2()" />
                                </div>
								</label>
								</div>
                            </div>
					
							
							</div>
							<div class="col-md-6 vl">
							<div id="transfers2">
							<span id="newTransfers2"></span>
							</div>
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
								<div id="hiddencheckBox">
							<div class="col-9">
                                <label for="checkBox" class="col-form-label">
                                    <span id="checkboxName"></span>
                                <div class="col-9">
								<input type="checkbox" id="checkBox" name="checkBox" value="" />
                                </div>
								</label>
								</div>
                            </div>
							<span id="visits2"></span>
							<span id="discharges2"></span>
							
							</div>
							</div>
							</div>
							</div>
							
							<div class="p-2 mb-2 bg-info" id="ptTitle">
						<h5 class="modal-title" style="color:white"><span id="acuityDesc"></span></h5>
						</div>
							
							<div class="form-group">
							<div class="bg-secondary">
							<div class="row">
							<div class="col-md">
						
							<span id="epicScore"></span>
							<span id="epicNurse"></span>
							
							<div class="col-9" id="hidden7">
                                <label for="oneto7" class="col-form-label">
                                    <span id="desc7"></span>
                                <div class="col-9">
								<input type="number" min="0" class="form-control number" id="oneto7" style="text-align: right" onchange="tj.gridupdate2()" />
                                </div>
								</label>
							</div>
							<div class="col-9" id="hidden9">
                                <label for="oneto9" class="col-form-label">
                                    <span id="desc9"></span>
                                <div class="col-9">
								<input type="number" min="0" class="form-control number" id="oneto9" style="text-align: right" />
                                </div>
								</label>
							</div>
							<div class="col-9" id="hidden11">
                                <label for="oneto11" class="col-form-label">
                                    <span id="desc11"></span>
                                <div class="col-9">
								<input type="number" min="0" class="form-control number" id="oneto11" style="text-align: right" />
                                </div>
								</label>
							</div>
							</div>	
						
							<div class="col-md vl">
							<span id="epicVar"></span>
							<div class="col-9" id="hidden8">
                                <label for="oneto8" class="col-form-label">
                                    <span id="desc8"></span>
                                <div class="col-9">
								<input type="number" min="0" class="form-control number" id="oneto8" style="text-align: right" onchange="tj.gridupdate2()" />
                                </div>
								</label>
								</div>
						
							<div class="col-9" id="hidden10">
                                <label for="oneto10" class="col-form-label">
								
                                    <span id="desc10"></span>
                                <div class="col-9">
								<input type="number" min="0" class="form-control number" id="oneto10" style="text-align: right" />
                                </div>
								</label>
								</div>
							<div class="col-9" id="hidden12">
                                <label for="oneto12" class="col-form-label">
								
                                    <span id="desc12"></span>
                                <div class="col-9">
								<input type="number" min="0" class="form-control number" id="oneto12" style="text-align: right" />
                                </div>
								</label>
								</div>
                            </div>
							</div>
							</div>							
          
							</div>
						<!--	
						<div id="showFlow">
						<input id="flowdept1" type="text" hidden>
						<input id="flowdept2" type="text" hidden>
						<input id="flowdept3" type="text" hidden>
						<input id="flowdept4" type="text" hidden>
						<input id="flowdept5" type="text" hidden>
						<input id="flowdept6" type="text" hidden>
						<input id="flowdept7" type="text" hidden>
						<input id="flowdept8" type="text" hidden>
						<input id="UseFlow" type="text" hidden>
						<input id="flowAcuity" type="text" hidden>
					
						
						<div class="p-2 mb-2 bg-info">
						<h5 class="modal-title" style="color:white">Patient Flow (Anticipated Transfers Out, Next 4hrs) </h5>
						</div>
						<div class="bg-secondary">						
						<div class="form-group">
						<div class="row">
						<div class="col-md">
						<div id="hidetrackflow1">
						
                                  <label for="trackflow1">
                                   <span id="trackflow1Desc"></span>
                                
                                <div class="col-6">
                                 <input type="number" min="0" class="form-control" id="trackflow1">
                                </div>
							</label>   	
						</div>
						<div id="hidetrackflow3">
                                <label for="trackflow3">
                                   <span id="trackflow3Desc"></span>
                                
                                <div class="col-6">
                                 <input type="number" min="0" class="form-control" id="trackflow3">
                                </div>
							</label>
						</div>
						<div id="hidetrackflow5">
                                <label for="trackflow5">
                                   <span id="trackflow5Desc"></span>
                                
                                <div class="col-6">
                                 <input type="number" min="0" class="form-control" id="trackflow5">
                                </div>
							</label>
						</div>
						<div id="hidetrackflow7">
                                <label for="trackflow7">
                                   <span id="trackflow7Desc"></span>
                                
                                <div class="col-6">
                                 <input type="number" min="0" class="form-control number" id="trackflow7">
                                </div>
							</label>
						</div>
						
						</div>
							
						<div class="col-md vl" >
						
						<div id="hidetrackflow2">
							<label for="trackflow2">
                                   <span id="trackflow2Desc"></span>
                                
                                <div class="col-6">
								<input type="number" min="0" class="form-control number" id="trackflow2">
                                </div>
							</label>
                        </div>
						<div id="hidetrackflow4">
							<label for="trackflow4">
                                   <span id="trackflow4Desc"></span>
                                
                                <div class="col-6">
								<input type="number" min="0" class="form-control number" id="trackflow4">
                                </div>
							</label>
                        </div>
						<div id="hidetrackflow6">
                                <label for="trackflow6">
                                   <span id="trackflow6Desc"></span>
                                
                                <div class="col-6">
                                 <input type="number" min="0" class="form-control number" id="trackflow6">
                                </div>
							</label>
						</div>
						<div id="hidetrackflow8">
                                <label for="trackflow8">
                                   <span id="trackflow8Desc"></span>
                                
                                <div class="col-6">
                                 <input type="number" min="0" class="form-control number" id="trackflow8">
                                </div>
							</label>
						</div>
						</div>
											
						</div>
						</div>
						</div>
						</div>
						-->
							
						<div class="p-2 mb-2 bg-info">
						<h5 class="modal-title" style="color:white"><span id="resourcesTitle"></span><span id="clockedIn"></span></h5>
						</div>
						
						<div class="form-group">
						<div class="bg-secondary">
						<div class="row">
						<div class="col-md-6">
						<div>
						<div id="charge0">
						
                                <label for="chargecountNEW" id="pos1">
                                   <span id="chargeDesc"></span>
								
                                <div>
								<input type="number" min="0" class="form-control number" id="chargecountNEW" style="text-align: right" onchange="tj.gridupdate5()" />
                                    <!--
									<select id="chargecountNEW" type="select" onchange="tj.gridupdate5()" class="form-control">
										<option value="0">0</option>
										
										<option value="0.5">0.5</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										</select> -->
                                </div>
						   </label>
						   
						   <label for="cnGrid" class="col-md-3" id="cngrid1">
                                   Grid:
								
                                <div>
								<input type="text" class="form-control" style="border:none" id="cnGrid" name="cnGrid" disabled>
                                </div>
						   </label>
						   <label for="cnVariance" class="col-md-3" id="cngrid2">
                                   Variance:
								
                                <div>
								 <input type="text" class="form-control" style="border:none" id="cnVariance" name="cnVariance" disabled>
                                </div>
						   </label>
						   
                            </div>
							</div>
						<div>
						<div id="Nurse1">
						
                                <label for="nurse1_add" id="pos3">
                                   <span id="nurse1Desc"></span>
                                
                                <div>
									<input type="number" min="0" class="form-control number" id="nurse1_add" style="text-align: right" onchange="tj.gridupdate5()" />
                                    <!--
									<select id="nurse1_add" type="select" onchange="tj.gridupdate5()" class="form-control">
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
										</select> -->
                                </div>
							</label>
							<label for="rn1Grid" class="col-md-3" id="rn1grid1">
                                Grid:  
								
                                <div>
                                  <input type="text" class="form-control" style="border:none" id="rn1Grid" style="text-align: left" disabled>
                                </div>
						   </label>
						   <label for="rn1Variance" class="col-md-3" id="rn1grid2">
                                Variance:   
								
                                <div>
                                  <input type="text" class="form-control" style="border:none" id="rn1Variance" style="text-align: left" disabled>
                                </div>
						   </label>					 
                            </div>
							</div>	
		
							<div>
							<div id="techView">
                                <label for="techcountNEW" id="pos5">
                                   <span id="techLabel"></span>
                                
                                <div>
								<input type="number" min="0" class="form-control number" id="techcountNEW" style="text-align: right" onchange="tj.gridupdate5()" />
                                    <!--
									<select id="techcountNEW" type="select" onchange="tj.gridupdate5()" class="form-control">
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
										</select> -->
                                </div>
							</label>
							 <label for="pctGrid" class="col-md-3" id="pctgrid1">
                                Grid:  
								
                                <div>
                                  <input type="text" class="form-control" style="border:none" id="pctGrid" style="text-align: left" disabled>
                                </div>
						   </label>
						   <label for="pctVariance" class="col-md-3" id="pctgrid2">
                                Variance:   
								
                                <div>
                                  <input type="text" class="form-control" style="border:none" id="pctVariance" style="text-align: left" disabled>
                                </div>
						   </label>
						  </div>
                            </div>
						<div>
						<div id="other1hide">
                                <label for="other1" id="pos7">
                                   <span id="other1Desc"></span>
                                
                                <div>
								<input type="number" min="0" class="form-control number" id="other1" style="text-align: right" onchange="tj.gridupdate5()" />
									<!--
								   <select id="other1" type="select" onchange="tj.gridupdate5()" class="form-control">
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
										</select> -->
                                </div>
								</label>
								<label for="other1Grid" class="col-md-3" id="other1grid1">
                                Grid:  
								
                                <div>
                                  <input type="text" class="form-control" style="border:none" id="other1Grid" style="text-align: left" disabled>
                                </div>
						   </label>
						   <label for="other1Variance" class="col-md-3" id="other1grid2">
                                Variance:   
								
                                <div>
                                  <input type="text" class="form-control" style="border:none" id="other1Variance" style="text-align: left" disabled>
                                </div>
						   </label>
						   </div>
                            </div>
							<div>
						<div id="other2hide">
                                <label for="other2" id="pos9">
                                   <span id="other2Desc"></span>
                                
                                <div>
								<input type="number" min="0" class="form-control number" id="other2" style="text-align: right" onchange="tj.gridupdate5()" />
                                    <!--
									<select id="other2" type="select" onchange="tj.gridupdate5()" class="form-control">
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
										</select> -->
                                </div>
								</label>
								<label for="other2Grid" class="col-md-3" id="other2grid1">
                                Grid:  
								
                                <div>
								<span id="other2Gridval" style="color:black; font-size:18px;" hidden></span>
                                 <input type="number" id="other2Grid"  class="form-control" style="border:none" style="text-align: left" disabled>
                                </div>
						   </label>
						   <label for="other2Variance" class="col-md-3" id="other2grid2">
                                Variance:   
								
                                <div>
								<span id="other2Varianceval" style="color:red; font-size:18px;" hidden></span>
                                  <input type="number" class="form-control" style="border:none" id="other2Variance" style="text-align: left" disabled>
                                </div>
						   </label>
						   </div>
                            </div>
							</div>
						<div class="col-md-6 vl">
						<div>
						<div id="nurse0">	
					
                                <label for="nursecountNEW" id="pos2">
                                    <span id="nurseDesc"></span>
                                
                                <div>
								<input type="number" min="0" class="form-control number" id="nursecountNEW" style="text-align: right" onchange="tj.gridupdate5()" />
                                    <!--
									<select id="nursecountNEW" type="select" onchange="tj.gridupdate5()" class="form-control">
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
										<option value="21">21</option>
										<option value="22">22</option>
										<option value="23">23</option>
										<option value="24">24</option>
										<option value="25">25</option>
										<option value="26">26</option>
										<option value="27">27</option>
										<option value="28">28</option>
										<option value="29">29</option>
										<option value="30">30</option>
										<option value="31">31</option>
										<option value="32">32</option>
										<option value="33">33</option>
										<option value="34">34</option>
										<option value="35">35</option>
										<option value="36">36</option>
										<option value="37">37</option>
										<option value="38">38</option>
										<option value="39">39</option>
										<option value="40">40</option>
										<option value="41">41</option>
										<option value="42">42</option>
										<option value="43">43</option>
										<option value="44">44</option>
										<option value="45">45</option>
										<option value="46">46</option>
										<option value="47">47</option>
										<option value="48">48</option>
										<option value="49">49</option>
										<option value="50">50</option>
										</select> -->
                                </div>
								</label>
							<label for="rnGrid" class="col-md-3" id="rngrid1">
                                 Grid: 
								
                                <div>
                                  <input type="text" class="form-control" style="border:none" id="rnGrid" name="rnGrid" disabled>
                                </div>
						   </label>
						   <label for="rnVariance" class="col-md-3" id="rngrid2">
                                 Variance:  
								
                                <div>
                                  <input type="text" class="form-control" style="border:none" id="rnVariance" name="rnVariance" disabled>
                                </div>
						   </label>
								
                            </div>
						</div>
							<div>
							<div id="Nurse2">
                                <label for="nurse2_add" id="pos4">
                                   <span id="nurse2Desc"></span>
                                
                                <div>
								<input type="number" min="0" class="form-control number" id="nurse2_add" style="text-align: right" onchange="tj.gridupdate5()" />
                                    <!--
									<select id="nurse2_add" type="select" onchange="tj.gridupdate5()" class="form-control">
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
										</select> -->
                                </div>
							</label>
							<label for="rn2Grid" class="col-md-3" id="rn2grid1">
                                Grid:  
								
                                <div>
                                  <input type="text" class="form-control" style="border:none" id="rn2Grid" style="text-align: left" disabled>
                                </div>
						   </label>
						   <label for="rn2Variance" class="col-md-3" id="rn2grid2">
                                Variance:   
								
                                <div>
                                  <input type="text" class="form-control" style="border:none" id="rn2Variance" style="text-align: left" disabled>
                                </div>
						   </label>
							</div>
                            </div>
						
						<div>
						<div id="secView">
                                <label for="seccountNEW" id="pos6">
                                   <span id="secLabel"></span>
                                
                                <div>
								<input type="number" min="0" class="form-control number" id="seccountNEW" style="text-align: right" onchange="tj.gridupdate5()" />
                                    <!--
									<select id="seccountNEW" type="select" onchange="tj.gridupdate5()" class="form-control">
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
										</select> -->
                                </div>
							</label>
							<label for="secGrid" class="col-md-3" id="secgrid1">
                                Grid:  
								
                                <div>
                                  <input type="text" class="form-control" style="border:none" id="secGrid" style="text-align: left" disabled>
                                </div>
						   </label>
						   <label for="secVariance" class="col-md-3" id="secgrid2">
                                Variance:   
								
                                <div>
                                  <input type="text" class="form-control" style="border:none" id="secVariance" style="text-align: left" disabled>
                                </div>
						   </label>
						   </div>
                            </div>
						<div>
						<div id="sittersNEWhide">
                                <label for="sittersNEW" id="pos8">
									<span id="sittersNEWDesc"></span>
                                
                                <div>
								<input type="number" min="0" class="form-control number" id="sittersNEW" style="text-align: right" onchange="tj.gridupdate5()" />
                                    <!--
									<select id="sittersNEW" type="select" onchange="tj.gridupdate5()" class="form-control">
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
										</select> -->
                                </div>
								</label>
								<label for="sitterGrid" class="col-md-3" id="sittergrid1">
                                Grid:  
								
                                <div>
                                  <input type="text" class="form-control" style="border:none" id="sitterGrid" style="text-align: left" disabled>
                                </div>
						   </label>
						   <label for="sitterVariance" class="col-md-3" id="sittergrid2">
                                Variance:   
								
                                <div>
                                  <input type="text" class="form-control" style="border:none" id="sitterVariance" style="text-align: left" disabled>
                                </div>
						   </label>
						   </div>
                           </div>
						   <div>
						<div id="other3hide">
                                <label for="other3" id="pos10">
                                   <span id="other3Desc"></span>
                                
                                <div>
								<input type="number" min="0" class="form-control number" id="other3" style="text-align: right" onchange="tj.gridupdate5()" />
                                    <!--
									<select id="other3" type="select" onchange="tj.gridupdate5()" class="form-control">
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
										</select> -->
                                </div>
								</label>
								<label for="other3Grid" class="col-md-3" id="other3grid1">
                                Grid:  
								
                                <div>
                                  <input type="text" class="form-control" style="border:none" id="other3Grid" style="text-align: left" disabled>
                                </div>
						   </label>
						   <label for="other3Variance" class="col-md-3" id="other3grid2">
                                Variance:   
								
                                <div>
                                  <input type="text" class="form-control" style="border:none" id="other3Variance" style="text-align: left" disabled>
                                </div>
						   </label>
						   </div>
                            </div>
						
										
							</div>
							</div>
							</div>
							</div>
						<div class="form-group">
						<div class="bg-secondary">
						<hr></hr>
						<div class="row">
						
						<div class="col-md-6">
						<div id="hidernvariance">
				
							<label for="showrnvariance" class="col-9">
                                <span id="varName1"></span> 
												
                                 <input type="number" class="col-6 form-control" id="showrnvariance" style="text-align: right" disabled>
								 
								 <span id="varComment"></span>
								 </label>		
								
								 </div>
						<div id="hidernvariance1">
				
							<label for="showrnvariance1" class="col-9">
                                <span id="varName101"></span> 
												
                                 <input type="number" class="col-6 form-control" id="showrnvariance1" style="text-align: right" disabled>
								 </label>		
								
								 </div>
						<div id="hidernvariance2">
				
							<label for="showrnvariance2" class="col-9">
                                <span id="varName102"></span> 
												
                                 <input type="number" class="col-6 form-control" id="showrnvariance2" style="text-align: right" disabled>
								 </label>		
								
								 </div>
								 <div id="hidernvariance3">
				
							<label for="showrnvariance3" class="col-9">
                                <span id="varName103"></span> 
												
                                 <input type="number" class="col-6 form-control" id="showrnvariance3" style="text-align: right" disabled>
								 </label>		
								
								 </div>
							</div>
						   <div class="col-md-6 vl">
							
							
							<label for="showgridrnvariance" id="hidegridrnvariance" class="col-6">
                                <span id="varName3"></span>  
					 
								
                                  <input type="text" class="form-control" id="showgridrnvariance" style="text-align: right" disabled>
                            </label>
							
							
							
							<label for="showgridvariance" id="hidegridvariance" class="col-5">
                                <span id="varName2"></span>  
					 
								
                                  <input type="text" class="form-control" id="showgridvariance" style="text-align: right" disabled>
                            </label>
						
							 
						  </div>
						  
						   </div>
						   </div>
							</div>
						
						
						<div id="showtrack">
						
						<div class="p-2 mb-2 bg-info">
						<h5 class="modal-title" style="color:white">Additional Data </h5>
						</div>
						<div class="bg-secondary">						
						<div class="form-group">
						<div class="row">
						<div class="col-md">
						<div id="hidetrack1">
                                <label for="track1">
                                   <span id="track1Desc"></span>
                                
                                <div class="col-6">
                                 <input type="number" min="0" class="form-control number" id="track1">
                                </div>
								
								<div class="col-12" id="hidenote1">					
								<textarea class="form-control" rows="2" placeholder="Note" id="trackNote1"></textarea>
                            </div>
							</label>
						</div>
						<div id="hidetrack3">
                                <label for="track3">
                                   <span id="track3Desc"></span>
                                
                                <div class="col-6">
                                 <input type="number" min="0" class="form-control number" id="track3">
                                </div>
								
								<div class="col-12" id="hidenote3">					
								<textarea class="form-control" rows="2" placeholder="Note" id="trackNote3"></textarea>
                            </div>
							</label>
						</div>
						
						</div>
							
						<div class="col-md vl" >
						
						<div id="hidetrack2">
							<label for="track2">
                                   <span id="track2Desc"></span>
                                
                                <div class="col-6">
								<input type="number" min="0" class="form-control number" id="track2">
                                </div>
								
								<div class="col-12" id="hidenote2">
								<textarea class="form-control" rows="2" placeholder="Note" id="trackNote2"></textarea>
							</div>
							</label>
                        </div>
						<div id="hidetrack4">
							<label for="track4">
                                   <span id="track4Desc"></span>
                                
                                <div class="col-6">
								<input type="number" min="0" class="form-control number" id="track4">
                                </div>
								
								<div class="col-12" id="hidenote4">
								<textarea class="form-control" rows="2" placeholder="Note" id="trackNote4"></textarea>
							</div>
							</label>
                        </div>
						</div>
											
						</div>
						</div>
						</div>
						</div>
							
						<div id="showchurn1">
						
						<div class="p-2 mb-2 bg-info">
						<h5 class="modal-title" style="color:white">Churn </h5>
						</div>
						
							<div class="bg-secondary">
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
							
							<div class="form-group" id="customChurn1">
                                <label for="customChurn" class="col-7 col-form-label">
                                    <span id="customChurnLabel"></span>
                                </label>
                                <div class="col-5">
                                    <input type="number" class="form-control number" id="customChurn">
                                </div>
                            </div>
							</div>
						</div>
					
							<div class="p-2 mb-2 bg-info">
                              <h5 class="modal-title" style="color:white"><span id="actionPlan"></span></h5>
								</div>
								<div class="form-group">
								<div class="bg-secondary">
								<div class="title" style="color:red">Do not include any Protected Health Information (PHI), as defined by the HIPAA Privacy Rule, in your comments.
					</div>	
								<div>
								
                                <textarea class="form-control border-dark" maxlength="350" rows="3" id="prodnoteNEW"></textarea>
                            </div>
							
							<div id="submitted">
                                <label for="submittedby" style="color:red">
                                    Submitted By (Required):
                                 <div>
                                    <input type="text" class="form-control" id="submittedby" placeholder="Enter your name here.">
                                </div>
								</label>
								<span><a href="javascript:;" onclick="tj.showSubmit2();" id="showSubmit2" hidden><u>Show Name Selector</u></a></span>
                            </div>
							<div id="submittedNEW" hidden>
                                <label for="submittedbyNEW" style="color:red">
                                    Submitted By New(Required):
                                 <div>
                                   <select id="submittedbyNEW" type="select" class="form-control">
									<option value="0">Select User</option>
															
								<?php
								
								if(intval($role)>7){
								$dbuser = Config::get('db')->get_results("SELECT x.*, CONCAT(u.last_name, ', ', u.first_name) as newName from `productiveDeptXref` x LEFT JOIN `productiveUser` as u on u.id=x.userId where u.accountId={$accountId} AND u.role<8 AND u.practiceId=0 AND u.active>0 and u.last_name !='' group by x.userId order by u.last_name ASC, u.first_name");	
								}else{
								$dbuser = Config::get('db')->get_results("SELECT x.*, CONCAT(u.last_name, ', ', u.first_name) as newName from `productiveDeptXref` x LEFT JOIN `productiveUser` as u on u.id=x.userId where x.deptId IN (SELECT `deptId` FROM `productiveDeptXref` where `userId`={$userId}) AND u.accountId={$accountId} AND u.role<8 AND u.practiceId=0 AND u.active>0 and u.last_name !='' group by x.userId order by u.last_name ASC, u.first_name");								
								}
								foreach($dbuser as $x) 
								{ 
								?>
								<option value="<?php echo $x['userId'];?>"><?php echo $x['newName'];?> </option>
								<?php 
								} 
								?>
							</select>
                                </div>
								</label>
								<span><a href="javascript:;" onclick="tj.showSubmit();" ><u>Can't Find Your Name?</u></a></span>
                            </div>
											
						   </div>
						   </div>
						   
                    </form>
                </div>
                <div class="modal-footer bg-info">
				     <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.updateProdNEW();" <?php echo $exec; ?>>Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        
		</div>
        <!-- /.modal-dialog -->
 </div>
 
<div class="modal fade" id="visits" tabindex="-1" role="basic" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <button type="button" class="close" data-dismiss="modal"></button>
                    <h4 class="modal-title">Capture <span id="visitsType2"></span> and/or Hours</h4>
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
						Use this feature to capture Units of Service and/or Hours for the entire day.
						</div>
                            <div class="form-group form-md-line-input">
                                <label for="visitTotal"><strong>Enter <span id="visitsType"></span> for <span id="visitsdayDate"></span>:</strong></label>
                                <input type="number" min="0" class="form-control number col-6" id="visitTotal">
                            </div>
							<hr></hr>
							<div class="form-group form-md-line-input" id="hrs0Total">
                                <label for="hoursTotal"><strong>Enter Total Hours for <span id="hoursdayDate"></span>:</strong></label>
								<span id="hoursReqd" hidden></span>
								<input type="number" min="0" class="form-control number col-6" id="hoursTotal">
                            </div>
							<div class="form-group form-md-line-input" id="hrs1Total" hidden>
                                <label for="hrs1"><strong>Enter <span id="hrs1Desc"></span> for <span id="hoursdayDate1"></span>:</strong></label>
								<input type="number" min="0" class="form-control number col-6" id="hrs1">
                            </div>
							<div class="form-group form-md-line-input" id="hrs2Total" hidden>
                                <label for="hrs2"><strong>Enter Total <span id="hrs2Desc"></span> for <span id="hoursdayDate2"></span>:</strong></label>
								<input type="number" min="0" class="form-control number col-6" id="hrs2">
                            </div>
							<div class="form-group form-md-line-input" id="hrs3Total" hidden>
                                <label for="hrs3"><strong>Enter Total <span id="hrs3Desc"></span> for <span id="hoursdayDate3"></span>:</strong></label>
								<input type="number" min="0" class="form-control number col-6" id="hrs3">
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-secondary">
				
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="noteButton" class="btn btn-primary" onclick="tj.visits();">Save</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
 </div>

	<style>
    .sum-boxes{display:grid;grid-template-columns:1fr 1fr;gap:1px;background:#e0e0e0;margin-bottom:1px;}
    .sum-box{background:#fff;padding:10px;text-align:center;}
    .sum-val{font-size:1.4rem;font-weight:800;line-height:1;}
    .sum-lbl{font-size:.65rem;color:#888;text-transform:uppercase;letter-spacing:.5px;margin-top:3px;}
    .sum-neg .sum-val{color:#c0392b;}
    .sum-pos .sum-val{color:#27ae60;}
    .sum-neutral .sum-val{color:#2c3e50;}
    .sum-section-lbl{padding:6px 12px 2px;font-size:.67rem;font-weight:700;color:#1a4a8a;text-transform:uppercase;letter-spacing:.6px;background:#fff;border-top:1px solid #eee;}
    .sum-body-inner{background:#f0f2f5;border-radius:6px;overflow:hidden;margin-bottom:12px;}
    .sum-dept-header{background:#2a5aa0;padding:10px 14px;}
    .sum-dept-name{color:#fff;font-size:1rem;font-weight:700;}
    </style>
    <div class="modal fade" id="staffingSummary" tabindex="-1" role="basic" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background:#1a4a8a;">
                    <h4 class="modal-title" style="color:white">Staffing Report Submitted</h4>
                </div>
                <div class="modal-body">
                    <input id="summaryDataId" type="hidden" value="" />
                    <input id="summaryDeptId" type="hidden" value="" />
                    <input id="summaryEscAlerts" type="hidden" value="" />
                    <input id="summaryVariance" type="hidden" value="0" />
                    <div class="sum-body-inner">
                        <div class="sum-dept-header">
                            <div class="sum-dept-name" id="summaryTitle">—</div>
                        </div>
                        <div id="summaryStaffGrid"></div>
                    </div>
                    <div style="background:#c0392b;color:#fff;font-weight:700;font-size:0.85rem;padding:8px 12px;margin-bottom:10px;border-radius:4px;">
                        &#9888; HIPAA Notice: Do not include any Protected Health Information (PHI) in notes.
                    </div>
                    <div class="form-group">
                        <label for="summaryNote"><strong>Notes</strong> <span id="summaryNoteHint" style="font-size:.82rem;color:#e67e22;font-weight:600;"></span></label>
                        <textarea class="form-control" id="summaryNote" rows="3" maxlength="350" placeholder="Add a note or action plan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="justify-content:space-between;background:#1a4a8a;">
                    <button type="button" class="btn btn-danger" onclick="tj.staffingSummaryEscalation();">Add Escalation</button>
                    <button type="button" class="btn" style="background:#fff;color:#1a4a8a;font-weight:600;border:2px solid #fff;" onclick="tj.staffingSummaryDone();">Done</button>
                </div>
            </div>
        </div>
    </div>
 
	<div class="modal fade" id="addprodnoteOrig" tabindex="-1" role="basic" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <!--<button type="button" class="close" data-dismiss="modal" ></button>-->
                    <h4 class="modal-title">Variance Action Required</h4>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <input id="notedataIdOrig" type="hidden" value="" />
						<input id="notedeptIdOrig" type="hidden" value="" />
                        <div class="form-body">
                            <div class="form-group form-md-line-input">
                                <label for="notebodyOrig">Your Staffing Report shows a possible <span id="varianceTypeOrig"></span> variance for <span id="deptOrig"></span>.  Please add a note with an explanation or an action plan to correct the variance.</label>
                                <textarea class="form-control border-dark" rows="3" maxlength="250" id="notebodyOrig" required ></textarea>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-secondary">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="noteButton" class="btn btn-primary" onclick="tj.addProdNoteOrig();">Save</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
 </div>

 <div class="modal fade" id="blockbeds" tabindex="-1" role="basic" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <button type="button" class="close" data-dismiss="modal" ></button>
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
                                <textarea class="form-control border-dark" rows="3" id="blockedcomment" required ></textarea>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-secondary">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="blockButton" class="btn btn-primary" onclick="updateblockedBeds();">Update</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
	
	
<div class="modal fade" id="addEscalation" tabindex="-1" role="basic" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background:#1a4a8a;">
                    <!--<button type="button" class="close" data-dismiss="modal" ></button>-->
                    <h4 class="modal-title" style="color:white">Add Escalation</h4>
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
								$dbData = Config::get('db')->get_results("SELECT * from `productiveAcctEscalations` where `hideEsc`=0 AND (`accountId`=0 OR `accountId`={$_SESSION['account']['accountId']}) order by `escalation` ASC");
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
                                <textarea class="form-control border-dark" rows="3" id="escalationcomment" ></textarea>
                            </div>
							
				
					  

                        </div>
                    </form>
                </div>
                <div class="modal-footer" style="background:#1a4a8a;">
					<button type="button" id="Esccancelbutton" class="btn btn-secondary" onclick="tj.linkedDept4();">Cancel</button>
                    <!--<button type="button" id="Escbutton" class="btn btn-secondary" data-dismiss="modal">Cancel</button>-->
                    <button type="button" id="EscAddbutton" class="btn btn-primary" onclick="tj.addEscalation();">Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
	
<div class="modal fade" id="addEscalation3" tabindex="-1" role="basic" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <!--<button type="button" class="close" data-dismiss="modal" ></button>-->
                    <h4 class="modal-title">Escalation</h4>
					 </div>
                <div class="modal-body">
                    <form role="form">
                        <input id="dataId3" type="hidden" value="" />
						<div class="form-body">
                            <div class="form-group form-md-line-input">
                                <label for="escval3">Use this tool to Escalate an issue or Recognize a Team Member.  The escalation will appear on the dashboard and your manager will be notified via text and/or email if they are configured to receive notifications.</label>
                              	
										<select id="escval3" name="escval3" class="form-control">
										<option value="0">Select Escalation</option>
										<?php
								$dbData = Config::get('db')->get_results("SELECT * from `productiveAcctEscalations` where `hideEsc`=0 AND (`accountid`=0 OR `accountId`={$_SESSION['account']['accountId']}) order by `escalation` ASC");
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
										<option value="0">Select Dept</option>
										<?php
								$dbUnit = Config::get('db')->get_results("SELECT d.*, u.role from `ProductiveDept` d LEFT JOIN `productiveUser` as u on u.id={$_SESSION['account']['id']} where d.active=1 AND d.prodMeasure !=2 AND ((u.role=11 AND d.accountId={$_SESSION['account']['accountId']}) OR (d.id in (SELECT `deptId` from `productiveDeptXref` where `userId`={$_SESSION['account']['id']}))) group by d.id order by d.dept ASC");
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
                                <textarea class="form-control border-dark" rows="3" id="escalationcomment3" ></textarea>
                            </div>
							
				
					  
                    </form>
                </div>
                <div class="modal-footer bg-secondary">
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

<div class="modal fade" id="editEscalation" tabindex="-1" role="basic" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <button type="button" class="close" data-dismiss="modal" ></button>
                    <h4 class="modal-title">Escalation</h4>
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
                                <textarea class="form-control border-dark" rows="4" id="escalationcommentedit" disabled></textarea>
                            </div>
							<hr></hr>
							<div class="form-group" hidden>
                                <label for="escalationresponse"><strong>Response:</strong></label>
								
                                <textarea class="form-control border-dark" rows="3" id="escalationresponse" disabled></textarea>
                            </div>			  
                    </form>
                </div>
                <div class="modal-footer bg-secondary">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <!--<button type="button" class="btn btn-primary" onclick="tj.updateEscalation();">Submit</button>-->
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>



    <!--end::Modal-->
</div>

<div class="modal fade" id="addDay" tabindex="-1" role="basic" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <button type="button" class="close" data-dismiss="modal"></button>
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
                <div class="modal-footer bg-secondary">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.updatedayRating();">Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

</div>

<div class="modal fade" id="TransfersIn" tabindex="-1" role="basic" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <button type="button" class="close" data-dismiss="modal" ></button>
                    <h4 class="modal-title">Anticipated Transfers Next 4hrs</h4>
                </div>
                <div class="modal-body">
                    <form role="form">
                       <div class="modal-title"><h4><span id="transferDept"></span></h4></div> 
                        <div class="form-body" style="center">
                            <div class="form-group">
							  <div class="row">
							
								<span id="currentTransfers"></span>
							
							</div>
                                
                            </div>
							
							</div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
	</div>
	
<div class="modal fade" id="unscheduled" tabindex="-1" role="basic" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <!--<button type="button" class="close" data-dismiss="modal" ></button>-->
                    <h4 class="modal-title">Unscheduled Report</h4>
					 </div>
                <div class="modal-body">
                    <form role="form">
						<div class="form-body">
							<div class="form-group form-md-line-input">
                                <label for="unscheduleddeptId">Which Unit is this Unscheduled Report for?</label>
                              	
										<select id="unscheduleddeptId" name="unscheduleddeptId" class="form-control">
										<option value="0">Select Unit</option>
										<?php
								$dbUnit = Config::get('db')->get_results("SELECT d.*, u.role from `ProductiveDept` d LEFT JOIN `productiveUser` as u on u.id={$_SESSION['account']['id']} where d.active=1 AND d.prodMeasure !=2 AND ((u.role=11 AND d.accountId={$_SESSION['account']['accountId']}) OR (d.id in (SELECT `deptId` from `productiveDeptXref` where `userId`={$_SESSION['account']['id']}))) group by d.id order by d.dept ASC");
								foreach($dbUnit as $b) 
								{ 
								?>
								<option value="<?php echo $b['id'];?>"><?php echo $b['dept'];?> </option>
								<?php 
								} 
								?>									
										</select>
										
                            </div>	
					  
                    </form>
                </div>
				</div>
                <div class="modal-footer bg-secondary">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="URbutton3" class="btn btn-primary" onclick="tj.editUnscheduled();">Continue</button>
                </div>
            
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!--end::Modal-->
</div>

    <!--end::Modal-->


