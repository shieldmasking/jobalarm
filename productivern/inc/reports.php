<?php
include "initializer.php";
require_once '.././inc/class.db.php';
//require_once '.././inc/class.jatwitter.php';
require_once '.././inc/config.php';
//require_once '.././inc/class.phpmailer.php';
//require_once '.././inc/FPDF/fpdf.php';




if ($_REQUEST['req'] != "forgot") {

// If not logged in, error.
    if (!isset($_SESSION['account'])) {
        echo json_encode(array('success' => false, 'message' => 'Not logged In'));
        exit();
    }

// Set the user account
    Config::set('account', $_SESSION['account']);

// If no account ID, error
    if (!isset(Config::get('account')['accountId'])) {
        echo json_encode(array('success' => false, 'message' => 'Not logged In'));
        exit();
    }
}
// Get the request
if (isset($_REQUEST['req'])) {
    Config::set('Request',$_REQUEST['req']);
}


// Route the request
switch(Config::get('Request')) {

    ////////////////////////////////
    // REPORTS PAGE
	case 'downloadcsv':
		header('Content-Type: application/json');
		DownloadCSV();
		break;
	case 'downloadgrid':
		header('Content-Type: application/json');
		DownloadGrid();
		break;
	case 'getpdf':
		fpdf();
		break;
	case 'rprt':
		require_once '.././inc/report_rprt.php';
		getrprtDompdf();
		break;
	case 'allshift':
		require_once '.././inc/reports_dompdf.php';
		allShiftDompdf();
		break;
	case 'allcrash':
		require_once '.././inc/reports_dompdf.php';
		allCrashDompdf();
		break;
	case 'allvoice':
		require_once '.././inc/report_voice.php';
		getVoiceDompdf();
		break;
	case 'allescalation':
		require_once '.././inc/reports_dompdf.php';
		getEscalationDompdf();
		break;
	case 'alluser':
		require_once '.././inc/reports_dompdf.php';
		allUserDompdf();
		break;
	case 'allunit':
		require_once '.././inc/reports_dompdf.php';
		allrprtDompdf();
		break;
	case 'allunits':
		require_once '.././inc/reports_dompdf.php';
		allUnitsDompdf();
		break;
	case 'reportCSV':
		header('Content-Type: application/json');
		ReportCSV();
		break;
	case 'crashCSV':
		header('Content-Type: application/json');
		CrashCSV();
		break;
	case 'getcsvtable':
		header('Content-Type: application/json');
		GetCSVTableData();
		break;
	case 'pdf1':
        header('Content-Type: application/json');
        getpdf1();
        break;
	case 'pdf2':
        header('Content-Type: application/json');
        getpdf2();
        break;
	case 'pdf7':
        header('Content-Type: application/json');
        getpdf7();
        break;
	case 'pdf99':
        header('Content-Type: application/json');
        getpdf99();
        break;
	case 'pdf366':
        header('Content-Type: application/json');
        getpdf366();
        break;
	case 'pdf285':
        header('Content-Type: application/json');
        getpdf285();
        break;
	case 'pdf392':
        header('Content-Type: application/json');
        getpdf392();
        break;
	case 'pdf363':
        header('Content-Type: application/json');
        getpdf363();
        break;
	case 'pdf364':
        header('Content-Type: application/json');
        getpdf364();
        break;
	case 'pdf387':
        header('Content-Type: application/json');
        getpdf387();
        break;
	case 'pdf356':
        header('Content-Type: application/json');
        getpdf356();
        break;
	case 'pdf365':
        header('Content-Type: application/json');
        getpdf365();
        break;
	case 'pdf358':
        header('Content-Type: application/json');
        getpdf358();
        break;
	case 'pdf11':
        header('Content-Type: application/json');
        getpdf11();
        break;
	case 'pdf3':
        header('Content-Type: application/json');
        getpdf3();
        break;
	case 'pdf4':
        header('Content-Type: application/json');
        getpdf4();
        break;
	case 'pdf5':
        header('Content-Type: application/json');
        getpdf5();
        break;
	case 'pdf6':
        header('Content-Type: application/json');
        getpdf6();
        break;
	case 'pdf9':
        header('Content-Type: application/json');
        getpdf9();
        break;
	case 'pdf10':
        header('Content-Type: application/json');
        getpdf10();
        break;
}

function fpdf(){
$userId = Config::get('account')['id'];
$accountId = Config::get('account')['accountId'];
$role = Config::get('account')['role'];
$enterpriseId = Config::get('account')['enterpriseId'];
$prodCount = Config::get('account')['prodCount'];
$deptId =  Config::get('account')['deptNum'];

if(intval($prodCount)==1 && intval($deptId)==323){
	require_once '.././inc/class.fpdf8.php';
}else{
	require_once '.././inc/class.fpdf.php';
}

if(intval($role)<8 && !$idsearch){
		$searchAdd = "AND n.accountId ={$accountId} AND n.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId` ={$userId})";
	}else if(intval($role)>7 && intval($role)<=12 && !$idsearch){
		$searchAdd = "AND n.accountId ={$accountId}";
	}else if(intval($role)>12 && !$idsearch){
		$searchAdd = "AND a.enterpriseId ={$enterpriseId}";
	}else{
		$searchAdd = '';
	}
	
$data2 = Config::get('db') -> get_results("select n.*, n.id as recordId, d.rnVariance, a.displayProd, a.name as accountName, (n.skill1val + n.skill2val + n.skill3val + n.skill4val + n.skill5val + n.skill6val + n.skill7val + n.skill8val + n.skill9val + n.skill10val + n.addResourceHrs) as Hours, (select count(`dayDate`) from `productiveNewData` where `deptId`=n.deptId and `whpPlan`=0 and `dayDate`=n.dayDate) as shiftsperDay, s.shift as shiftName, d.shiftsperDay as unused, d.prodMeasure, d.prodMeasure1, n.antecount as rn, CONCAT(ROUND(n.nproductivity,1),'%') as prod, n.techcount as techs, u.last_name as charge, d.dept as deptName, DATE_FORMAT(n.dayDate,'%m/%d/%y') as newDate, DATE_FORMAT(CURDATE(),'%m%d%y') as filedate, u.last_name as Director, DATE_SUB(CURDATE(), INTERVAL (a.payPeriod-(ROUND(a.payPeriod-((((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod)-FLOOR((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod))*a.payPeriod),0))) DAY) AS startDay, DATE_ADD(CURDATE(), INTERVAL (ROUND(a.payPeriod-((((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod)-FLOOR((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod))*a.payPeriod),0)-1) DAY) AS endDay, a.id as accountId, SUM(CASE WHEN n.actualWHPimport>0 then n.hrsVarianceActual ELSE n.hrsVariance END) as hrsVarianceTotal, SUM(CASE WHEN n.actualWHPimport>0 then n.actualWHPimport ELSE n.actualWHP END) as actualWHPTotal FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveShifts` as s on s.id = n.shift left join `productiveAccount` as a on a.id=n.accountId left outer Join `productiveUser` as u on u.id={$userId} where n.activeRecord>0 AND d.active>0 AND n.userId>0 AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate $searchAdd group by n.accountId, n.deptId, n.dayDate, n.shift  
ORDER BY d.dept ASC, n.dayDate DESC, n.shift DESC");

$pdf = new PDF_MC_Table();
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',10);
$pdf->AddPage('L');
if(intval($prodCount)==1 && intval($deptId)==323){
$pdf->SetWidths(array(45, 20, 18, 15, 15, 15, 15, 15, 15, 15, 15, 15, 60));
}else{
$pdf->SetWidths(array(50, 20, 15, 20, 20, 20, 15, 15, 15, 15, 65));	
}


foreach($data2 as $row2){

$shifts = intval($row2['shiftsperDay']);
if($shifts<=0){
	$shiftcount=1;
}else{
	$shiftcount=$shifts;
}
	
if(intval($row2['shiftsperDay'])>1){
	$date = $row2['newDate'] . ' - ' . $row2['shiftName']; 
}else{
	$date = $row2['newDate'];
}



if((intval($row2['prodMeasure1'])==2 || intval($row2['prodMeasure'])==3) && intval($deptId)!=323){
	$rn = $row2['skill2val'] + $row2['skill3val'];
	$techs = $row2['skill4val'] + $row2['skill5val'];
	$other = $row2['skill6val'] + $row2['skill7val'] + $row2['skill10val'];
	$patients = $row2['procedureCount'];
	$totalHours = $row2['Hours'] / $shiftcount;
	$actualWHP = $row2['actualWHPTotal'];
	$note = stripslashes($row2['note']);
	$sitters = $row2['skill8val'];
	$orien = $row2['skill9val'];
}else if(intval($prodCount)==1 && intval($deptId)==323){
	$charge = round($row2['chargecount'],1);
	$triage = $row2['customNurse'];
	$blt = $row2['seccount'];
	$rn = $row2['antecount'];
	$techs = $row2['techs'];
	$sitters = $row2['sittercount'];
	$orien = $row2['otherNurse1'];
	$other = $row2['otherNurse2'] + $row2['otherNurse3'];
	$patients = $row2['atotal'] + $row2['ltotal'] + $row2['patientCount2'];
	$rnVar = '-';
	$note = stripslashes($row2['note']);
	$totalRes = round(($row2['antecount'] + $row2['chargecount'] + $row2['customNurse'] + $row2['otherNurse1'] + $row2['otherNurse2'] + $row2['otherNurse3'] + $row2['seccount'] + $row2['sittercount'] + $row2['techcount']),1);
}else{
	$rn = (24 * ($row2['antecount'] + $row2['chargecount'] + $row2['ocount'] + $row2['ldcount'])) / $shiftcount;
	$techs = (24 * $row2['techs']) / $shiftcount;
	$other = (24 * ($row2['otherNurse1'] + $row2['otherNurse3'])) / $shiftcount;
	$patients = $row2['atotal'] + $row2['ltotal'] + $row2['obed'] + $row2['patientCount2'];
	$actualWHP = '-';
	$note = stripslashes($row2['note']);
	$sitters = (24 * $row2['sittercount']) / $shiftcount;
	$orien = (24 * $row2['otherNurse2']) / $shiftcount;
	//$prod = $row2['prod'];
	$totalHours = round((((($row2['antecount'] + $row2['ocount'] + $row2['ldcount'] + $row2['chargecount'] + $row2['customNurse'] + $row2['otherNurse1'] + $row2['otherNurse2'] + $row2['otherNurse3'] + $row2['seccount'] + $row2['sittercount'] + $row2['techcount']) * 24) / $shiftcount) + $row2['addResourceHrs']),1);
}	

if(intval($deptId)==323){
	$variance = '-';
}else if(intval($row2['rnVariance'])==1 && intval($deptId)!=323){
	$variance = $row2['hrsVarianceTotal'];
}else{
	$variance = $row2['nvariance'];
}

if(intval($row2['deptId'])==1 || intval($deptId)==323){
	$prod = '-';
}else{
	$prod = $row2['prod'];
}

if(intval($prodCount)==1 && intval($deptId)==323){
$pdf->Row(array($row2['deptName'],$date,$patients,$charge,$triage,$blt,$rn,$techs,$sitters,$orien,$other,$totalRes,$note));
}else{
$pdf->Row(array($row2['deptName'],$date,$prod,$actualWHP,$variance,$patients,$totalHours,$rn,$techs,$other,$note));	
}
}

if(intval($prodCount)==1 && intval($deptId)==323){

//$pdf->AliasNbPages();
$pdf->SetFont('Arial','',10);
$pdf->AddPage2('L');
$pdf->SetWidths2(array(30, 20, 16, 12, 12, 12, 12, 12, 20, 12, 13, 27, 18, 15, 47));


foreach($data2 as $row2){
	
$shifts = intval($row2['shiftsperDay']);

if($shifts<=0){
	$shiftcount=1;
}else{
	$shiftcount=$shifts;
}
	
	$date = $row2['newDate'] . ' - ' . $row2['shiftName']; 
	$prod = $row2['prod'];
	$es1 = $row2['oneto1'];
	$es2 = $row2['oneto2'];
	$es3 = $row2['oneto3'];
	$es4 = $row2['oneto4'];
	$es5 = $row2['oneto5'];
	$esp = $row2['oneto6'];
	$gr10 = $row2['oneto7'];
	$gr5 = $row2['oneto8'];
	$pdg = $row2['oneto9'];
	$brd = $row2['oneto10'];
	$wait = $row2['oneto11'];
	
	$patients = $row2['atotal'] + $row2['ltotal'] + $row2['patientCount2'];
	
	$note = stripslashes($row2['note']);
	

if(intval($row2['rnVariance'])==1){
	$variance = $row2['hrsVarianceTotal'];
}else{
	$variance = $row2['nvariance'];
}
$pdf->Row2(array($row2['deptName'],$date,$patients,$es1,$es2,$es3,$es4,$es5,$esp,$gr5,$gr10,$pdg,$brd,$wait,$note));

}

}else{
	//do nothing;
}


$filename=substr($data2[0]['accountName'],0,4) . 'download' . $data2[0]['newDate'] . '.pdf';
$filelocation="/home/prnadmin/public_html/dailyreports/" . substr($data2[0]['accountName'],0,4) . 'download' . $data2[0]['newDate'] . ".pdf";
$pdf->Output($filename,'D');

exit();

}

function getpdf1(){
$deptId = (isset($_REQUEST['deptId'])?$_REQUEST['deptId']:'');
$pdfId = (isset($_REQUEST['pdfId'])?$_REQUEST['pdfId']:'');
$userId = Config::get('account')['id'];
$accountId = Config::get('account')['accountId'];
//$accountId = 8;
$role = Config::get('account')['role'];
//$role = 11;
$enterpriseId = Config::get('account')['enterpriseId'];
$prodCount = Config::get('account')['prodCount'];
$deptId =  Config::get('account')['deptNum'];
$shifts = 6;

require_once '.././inc/class.fpdf1.php';
	
$data2 = Config::get('db') -> get_results("select n.*, (CASE WHEN n.hrs1Submitted>0 THEN 17.71 ELSE 0 END) as addHrs, d.dept as deptName, IFNULL((n.hrs1Submitted / n.visitsSubmitted),0) as hrs1v, IFNULL((n.hrs2Submitted / n.visitsSubmitted),0) as hrs2v, IFNULL((n.hrs3Submitted / n.visitsSubmitted),0) as hrs3v, IFNULL(((n.hrs1Submitted + n.hrs2Submitted + n.hrs3Submitted + n.addResourceHrs) / n.visitsSubmitted),0) as hrstv, DATE_FORMAT(n.dayDate,'%m/%d/%y') as newDate, a.id as accountId FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveShifts` as s on s.id = n.shift left join `productiveAccount` as a on a.id=n.accountId left outer Join `productiveUser` as u on u.id={$userId} where n.activeRecord>0 AND d.active>0 AND n.userId>0 AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate and n.deptId=323 group by n.accountId, n.deptId, n.dayDate ORDER BY d.dept ASC, n.dayDate DESC");

$pdf = new PDF_MC_Table();
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',10);
$pdf->AddPage('L');

$pdf->SetWidths(array(50, 20, 20, 25, 15, 20, 15, 21, 15, 20, 20, 15));

foreach($data2 as $row2){
	$date = $row2['newDate'];
	$visits = $row2['visitsSubmitted'];
	$hrs1 = $row2['hrs1Submitted'];
	$hrs1v = ROUND(FLOATVAL($row2['hrs1v']),2);
	
	$hrs2 = $row2['hrs2Submitted'];
	$hrs2v = ROUND(FLOATVAL($row2['hrs2v']),2);
	
	$hrs3 = $row2['hrs3Submitted'];
	$hrs3v = ROUND(FLOATVAL($row2['hrs3v']),2);
	
	$addHrs = ROUND(FLOATVAL($row2['addHrs']),2);
	
	$hrsTotal = $hrs1 + $hrs2 + $hrs3 + $addHrs;
	
	if(intval($visits)==0){
		$hrstv = 0;
	}else{
	$hrstv = ROUND(($hrsTotal / FLOATVAL($row2['visitsSubmitted'])),2);
	}

	$pdf->Row(array($row2['deptName'],$date,$visits,$hrs1,$hrs1v,$hrs2,$hrs2v,$hrs3,$hrs3v,$addHrs,$hrsTotal,$hrstv));	
}

$data3 = Config::get('db') -> get_results("select n.*, d.dept as deptName, SUM(CASE WHEN n.hrs1Submitted>0 THEN n.visitsSubmitted ELSE 0 END) as totalVisits, SUM(CASE WHEN n.hrs1Submitted>0 THEN 17.71 ELSE 0 END) as addHrstot, SUM(CASE WHEN n.hrs1Submitted>0 THEN n.hrs1Submitted ELSE 0 END) as hrs1tot, SUM(CASE WHEN n.hrs1Submitted>0 THEN n.hrs2Submitted ELSE 0 END) as hrs2tot, SUM(CASE WHEN n.hrs1Submitted>0 THEN n.hrs3Submitted ELSE 0 END) as hrs3tot FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveShifts` as s on s.id = n.shift left join `productiveAccount` as a on a.id=n.accountId left outer Join `productiveUser` as u on u.id={$userId} where n.shift=52 AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate and n.deptId=323 group by n.deptId");

$visitsT = ROUND(FLOATVAL($data3[0]['totalVisits']),2);
if(intval($visitsT)>0){
//$hrs1sum = ROUND(FLOATVAL($data3[0]['hrs1sum']),2);
$hrs1tot = ROUND(FLOATVAL($data3[0]['hrs1tot']),3);
$hrs1tv = ROUND(FLOATVAL($data3[0]['hrs1tot']) / $visitsT,2);
//$hrs2sum = ROUND(FLOATVAL($data3[0]['hrs2sum']),2);
$hrs2tot = ROUND(FLOATVAL($data3[0]['hrs2tot']),3);
$hrs2tv = ROUND(FLOATVAL($data3[0]['hrs2tot']) / $visitsT,2);
//$hrs3sum = ROUND(FLOATVAL($data3[0]['hrs3sum']),2);
$hrs3tot = ROUND(FLOATVAL($data3[0]['hrs3tot']),3);
$hrs3tv = ROUND(FLOATVAL($data3[0]['hrs3tot']) / $visitsT,2);
//$hrstsum = ROUND(FLOATVAL($data3[0]['hrstsum']),2);
$hrsAdd = ROUND(FLOATVAL($data3[0]['addHrstot']),2);
$hrsttot = $hrs1tot + $hrs2tot + $hrs3tot + $hrsAdd;
$hrsttv = ROUND($hrsttot / $visitsT,2);
$pdf->Row(array('TOTALS',' ',$visitsT,$hrs1tot,$hrs1tv,$hrs2tot,$hrs2tv,$hrs3tot,$hrs3tv,$hrsAdd,$hrsttot,$hrsttv));
}else{
//DO NOTHING;
}
$filename=substr($data2[0]['accountName'],0,4) . 'download' . $data2[0]['newDate'] . '.pdf';
$filelocation="/home/prnadmin/public_html/dailyreports/" . substr($data2[0]['accountName'],0,5) . $data2[0]['id'] . 'download' . $data2[0]['newDate'] . ".pdf";
$pdf->Output($filename,'D');

exit();

}

function getpdf5(){
$deptId = (isset($_POST['deptNum'])?$_POST['deptNum']:0);
//$pdfId = (isset($_REQUEST['pdfId'])?$_REQUEST['pdfId']:'');
$userId = Config::get('account')['id'];
$accountId = Config::get('account')['accountId'];
//$accountId = 8;
$role = Config::get('account')['role'];
//$role = 11;
$enterpriseId = Config::get('account')['enterpriseId'];
$prodCount = Config::get('account')['prodCount'];
//$deptId =  Config::get('account')['deptNum'];
$shifts = 6;

require_once '.././inc/class.fpdf5.php';
	
$data2 = Config::get('db') -> get_results("select n.*, (n.addResourceHrs / (select count(`dept`) from `productiveAccountXref` where `type`<2 AND `whpPlan` !=1 and `dept`={$deptId})) as countShifts, f.shiftName, SUM(n.procedureCount) as icvisits, SUM(n.whpCustom) as vvisits, SUM(n.whpCustom2) as icnoshows, SUM(n.whpCustom3) as vnoshows, SUM(n.skill4val) as icsupporthrs, SUM(n.skill5val) as vsupporthrs, SUM(n.skill11val) as phrs, SUM(n.whpCustom3) as vnoshows, d.dept as deptName, d.hppd, DATE_FORMAT(n.dayDate,'%m/%d/%y') as newDate, a.id as accountId FROM `productiveNewData` n left join `productiveAccountXref` as f on f.dept = n.deptId and f.shift = n.shift left join `ProductiveDept` as d on d.id = n.deptId left join `productiveShifts` as s on s.id = n.shift left join `productiveAccount` as a on a.id=n.accountId left outer Join `productiveUser` as u on u.id={$userId} where n.type<2 AND d.active>0 AND n.userId>0 AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate and n.deptId={$deptId} group by n.accountId, n.deptId, n.dayDate, n.shift ORDER BY d.dept ASC, n.dayDate DESC, n.shift DESC");

$pdf = new PDF_MC_Table();
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',10);
$pdf->AddPage('L');

$pdf->SetWidths(array(35, 25, 19, 19, 22, 20, 22, 20, 20, 14, 14, 14, 35));

foreach($data2 as $row2){
	$date = $row2['newDate'] . "-" . $row2['shiftName'];
	$icvisits = intval($row2['icvisits']);
	$vvisits = intval($row2['vvisits']);
	$totalvisits = $icvisits + $vvisits;
	//$icnoshows = $row2['icnoshows'];
	//$vnoshows = $row2['vnoshows'];
	$icsupporthrs = floatval($row2['icsupporthrs']);
	$vsupporthrs = floatval($row2['vsupporthrs']);
	$addhrs =  floatval($row2['addResourceHrs']);
	$totalhrs = $icsupporthrs + $vsupporthrs + $addhrs;
	$phrs = $row2['phrs'];
	$hppd = round($row2['hppd'],3);
	$actualwhp = $row2['actualWHP'];
	$prod =  round($row2['nproductivity'],1) . "%";
	$note =  $row2['note'];
	
	
	
	$pdf->Row(array($row2['deptName'],$date,$icvisits,$vvisits,$totalvisits,$icsupporthrs,$vsupporthrs,$totalhrs,$phrs,$hppd,$actualwhp,$prod,$note));	
}

$filename=substr($data2[0]['accountName'],0,4) . 'download' . $data2[0]['newDate'] . '.pdf';
$filelocation="/home/prnadmin/public_html/dailyreports/" . substr($data2[0]['accountName'],0,5) . $data2[0]['id'] . 'download' . $data2[0]['newDate'] . ".pdf";
$pdf->Output($filename,'D');

exit();

}

function getpdf9(){
$deptId = (isset($_POST['deptNum'])?$_POST['deptNum']:1);
//$pdfId = (isset($_REQUEST['pdfId'])?$_REQUEST['pdfId']:'');
$userId = Config::get('account')['id'];
$accountId = Config::get('account')['accountId'];
//$accountId = 8;
$role = Config::get('account')['role'];
//$role = 11;
$enterpriseId = Config::get('account')['enterpriseId'];
$prodCount = Config::get('account')['prodCount'];
//$deptId =  Config::get('account')['deptNum'];
$shifts = 6;

require_once '.././inc/class.fpdf9.php';
	
$data2 = Config::get('db') -> get_results("select n.nvariance, d.dept as deptName, sum(n.atotal + n.ltotal + n.patientCount2) as total, DATE_FORMAT(n.dayDate,'%m/%d/%y') as newDate, sum(n.acs) as acs, sum(n.am1 + n.scs + n.cr) as am1, sum(n.obed1) as obed, sum(n.pp) as pp, sum(n.awcm + n.pt + n.ccs + n.ps1) as awcm, n.note, f.shiftName FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveAccountXref` as f on f.dept = n.deptId and f.shift = n.shift left outer Join `productiveUser` as u on u.id={$userId} where n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate and n.deptId=1 and n.userId>0 group by n.accountId, n.deptId, n.dayDate, n.shift ORDER BY d.dept ASC, n.dayDate DESC, n.shift DESC");

$pdf = new PDF_MC_Table();
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',10);
$pdf->AddPage('L');

$pdf->SetWidths(array(35, 35, 25, 25, 20, 20, 20, 20, 20, 50));

foreach($data2 as $row2){
	$date = $row2['newDate'] . "-" . $row2['shiftName'];
	$acs = intval($row2['acs']);
	$am1 = intval($row2['am1']);
	$awcm = intval($row2['awcm']);
	$obed = intval($row2['obed']);
	$pp = intval($row2['pp']);
	$total = intval($row2['total']);
	$note =  $row2['note'];
	$var = intval($row2['nvariance']);
	
	
	
	$pdf->Row(array($row2['deptName'],$date,$var,$total,$awcm,$am1,$obed,$acs,$pp,$note));	
}

$filename=substr($data2[0]['accountName'],0,4) . 'download' . $data2[0]['newDate'] . '.pdf';
$filelocation="/home/prnadmin/public_html/dailyreports/" . substr($data2[0]['accountName'],0,5) . $data2[0]['id'] . 'download' . $data2[0]['newDate'] . ".pdf";
$pdf->Output($filename,'D');

exit();

}

function getpdf10(){
$deptId = (isset($_POST['deptNum'])?$_POST['deptNum']:1);
//$pdfId = (isset($_REQUEST['pdfId'])?$_REQUEST['pdfId']:'');
$userId = Config::get('account')['id'];
$accountId = Config::get('account')['accountId'];
//$accountId = 8;
$role = Config::get('account')['role'];
//$role = 11;
$enterpriseId = Config::get('account')['enterpriseId'];
$prodCount = Config::get('account')['prodCount'];
//$deptId =  Config::get('account')['deptNum'];
$shifts = 6;
$capacity = 3216;

require_once '.././inc/class.fpdf10.php';
	
$data2 = Config::get('db') -> get_results("select n.*, d.dept as deptName, d.hppd, DATE_FORMAT(n.dayDate,'%m/%d/%y') as newDate, f.shiftName FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveAccountXref` as f on f.dept = n.deptId and f.shift = n.shift left outer Join `productiveUser` as u on u.id={$userId} where n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate and n.deptId=350 and n.userId>0 group by n.accountId, n.deptId, n.dayDate, n.shift ORDER BY d.dept ASC, n.dayDate DESC, n.shift DESC");

$pdf = new PDF_MC_Table();
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',10);
$pdf->AddPage('L');

$pdf->SetWidths(array(35, 18, 15, 15, 15, 15, 15, 15, 15, 20, 15, 15, 20, 20, 30));

foreach($data2 as $row2){
	$date = $row2['newDate'];
	$visits = intval($row2['procedureCount']);
	$lvl1 = intval($row2['whpCustom']);
	$lvl2 = intval($row2['whpCustom2']);
	$lvl3 = intval($row2['whpCustom3']);
	$lvl4 = intval($row2['whpCustom4']);
	$lvl5 = intval($row2['whpCustom5']);
	$whp = ROUND(($row2['actualWHP']),1);
	$prod = ROUND($row2['nproductivity'],1);
	$target = ROUND($row2['hppd'],3);
	$hours = intval($row2['skill4val']);
	$minutes = floatval($row2['skill5val']);
	$utilization = ROUND(($minutes / $capacity)*100,1) .'%';
	$points = ROUND(((($lvl1 * 150) + ($lvl2 * 300) + ($lvl3 * 400) + ($lvl4 * 550) + ($lvl5 * 700)) / $visits),0);
	
	
	
	
	$pdf->Row(array($row2['deptName'],$date,$visits,$lvl1,$lvl2,$lvl3,$lvl4,$lvl5,$hours,$whp,$target,$prod,$minutes,$utilization,$points));	
}

$filename=substr($data2[0]['accountName'],0,4) . 'download' . $data2[0]['newDate'] . '.pdf';
$filelocation="/home/prnadmin/public_html/dailyreports/" . substr($data2[0]['accountName'],0,5) . $data2[0]['id'] . 'download' . $data2[0]['newDate'] . ".pdf";
$pdf->Output($filename,'D');

exit();

}

function getpdf6(){
$deptId = (isset($_POST['deptNum'])?$_POST['deptNum']:0);
//$pdfId = (isset($_REQUEST['pdfId'])?$_REQUEST['pdfId']:'');
$userId = Config::get('account')['id'];
$accountId = Config::get('account')['accountId'];
//$accountId = 8;
$role = Config::get('account')['role'];
//$role = 11;
$enterpriseId = Config::get('account')['enterpriseId'];
$prodCount = Config::get('account')['prodCount'];
//$deptId =  Config::get('account')['deptNum'];
$shifts = 6;

require_once '.././inc/class.fpdf6.php';
	
$data2 = Config::get('db') -> get_results("select n.*, DATE_FORMAT(n.dayDate,'%m/%d/%y') as newDate, d.dept FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left outer Join `productiveUser` as u on u.id={$userId} where n.type<2 AND d.active>0 AND n.userId>0 AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate and n.deptId={$deptId} and n.shift=52 group by n.accountId, n.deptId, n.dayDate, n.shift ORDER BY d.dept ASC, n.dayDate DESC");

$pdf = new PDF_MC_Table();
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',10);
$pdf->AddPage('L');

$pdf->SetWidths(array(60, 40, 50, 50, 50));

foreach($data2 as $row2){
	$date = $row2['newDate'];
	$totalvisits = floatval($row2['visitsSubmitted']);
	$totalhrs = ROUND($row2['hrs1Submitted'],2);
	
	if($totalvisits>0){
	$actualwhp = ROUND((floatval($totalhrs) / $totalvisits),2);
	}else{
	$actualwhp = 0.00;	
	}
	
	$pdf->Row(array(stripslashes($row2['dept']),$date,$totalvisits,$totalhrs,$actualwhp));	
}

$data3 = Config::get('db') -> get_results("select n.*, d.dept as deptName, SUM(CASE WHEN n.visitsSubmitted>0 THEN n.visitsSubmitted ELSE 0 END) as totalVisits, SUM(CASE WHEN n.hrs1Submitted>0 THEN 1 ELSE 0 END) as countwhp, SUM(n.hrs1Submitted) as hrstot, SUM(CASE WHEN n.hrs1Submitted>0 THEN (n.hrs1Submitted / n.visitsSubmitted) ELSE 0 END) as whptot from `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left outer Join `productiveUser` as u on u.id={$userId} where n.activeRecord>0 AND n.deptId={$deptId} AND n.shift=52 AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate group by n.deptId");

$visitsT = ROUND(FLOATVAL($data3[0]['totalVisits']),1);

if(intval($visitsT)>0){
//$hrs1sum = ROUND(FLOATVAL($data3[0]['hrs1sum']),2);
$hrstot = ROUND(FLOATVAL($data3[0]['hrstot']),2);
$whptot = ROUND(FLOATVAL($data3[0]['whptot']),2);
$countwhp = ROUND(FLOATVAL($data3[0]['countwhp']),2);

$avgwhp = ROUND(($hrstot / $visitsT),2);

$pdf->Row(array('TOTALS',' ',$visitsT,$hrstot,$avgwhp));
}else{
//DO NOTHING;
}

$filename=substr($data2[0]['accountName'],0,4) . 'download' . $data2[0]['newDate'] . '.pdf';
$filelocation="/home/prnadmin/public_html/dailyreports/" . substr($data2[0]['accountName'],0,5) . $data2[0]['id'] . 'download' . $data2[0]['newDate'] . ".pdf";
$pdf->Output($filename,'D');

exit();

}

function getpdf2(){
$deptId = (isset($_REQUEST['deptId'])?$_REQUEST['deptId']:'');
$pdfId = (isset($_REQUEST['pdfId'])?$_REQUEST['pdfId']:'');
$userId = Config::get('account')['id'];
//$userId = 411;
$prodCount = Config::get('account')['prodCount'];

require_once '.././inc/class.fpdf2.php';
	
$data2 = Config::get('db') -> get_results("select n.*, n.id as recordId, d.rnVariance, a.displayProd, a.name as accountName, (n.skill1val + n.skill2val + n.skill3val + n.skill4val + n.skill5val + n.skill6val + n.skill7val + n.skill8val + n.skill9val + n.skill10val + n.addResourceHrs) as Hours, (select count(`dayDate`) from `productiveNewData` where `deptId`=n.deptId and `whpPlan`=0 and `dayDate`=n.dayDate) as shiftsperDay, s.shift as shiftName, d.shiftsperDay as unused, d.prodMeasure, n.antecount as rn, CONCAT(ROUND(n.nproductivity,1),'%') as prod, n.techcount as techs, u.last_name as charge, d.dept as deptName, DATE_FORMAT(n.dayDate,'%m/%d/%y') as newDate, DATE_FORMAT(CURDATE(),'%m%d%y') as filedate, u.last_name as Director, DATE_SUB(CURDATE(), INTERVAL (a.payPeriod-(ROUND(a.payPeriod-((((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod)-FLOOR((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod))*a.payPeriod),0))) DAY) AS startDay, DATE_ADD(CURDATE(), INTERVAL (ROUND(a.payPeriod-((((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod)-FLOOR((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod))*a.payPeriod),0)-1) DAY) AS endDay, a.id as accountId, SUM(CASE WHEN n.actualWHPimport>0 then n.hrsVarianceActual ELSE n.hrsVariance END) as hrsVarianceTotal, SUM(CASE WHEN n.actualWHPimport>0 then n.actualWHPimport ELSE n.actualWHP END) as actualWHPTotal FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveShifts` as s on s.id = n.shift left join `productiveAccount` as a on a.id=n.accountId left outer Join `productiveUser` as u on u.id={$userId} where n.activeRecord>0 AND d.active>0 AND n.userId>0 AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate and n.deptId=323 group by n.accountId, n.deptId, n.dayDate, n.shift  
ORDER BY d.dept ASC, n.dayDate DESC, n.shift DESC");

$pdf = new PDF_MC_Table();
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',10);
$pdf->AddPage('L');

$pdf->SetWidths(array(45, 20, 18, 15, 15, 15, 15, 15, 15, 15, 15, 15, 60));

//$shifts = intval($row2['shiftsperDay']);

//if($shifts<=0){
//	$shiftcount=1;
//}else{
//	$shiftcount=$shifts;
//}

foreach($data2 as $row2){
	
$date = $row2['newDate'] . ' - ' . $row2['shiftName']; 
$prod = $row2['prod'];

	$charge = round($row2['chargecount'],1);
	$triage = $row2['customNurse'];
	$blt = $row2['seccount'];
	$rn = $row2['antecount'];
	$techs = $row2['techs'];
	$sitters = $row2['sittercount'];
	$orien = $row2['otherNurse1'];
	$other = $row2['otherNurse2'] + $row2['otherNurse3'];
	$patients = $row2['atotal'] + $row2['ltotal'] + $row2['patientCount2'];
	$rnVar = '-';
	$note = stripslashes($row2['note']);
	$totalRes = round(($row2['antecount'] + $row2['chargecount'] + $row2['customNurse'] + $row2['otherNurse1'] + $row2['otherNurse2'] + $row2['otherNurse3'] + $row2['seccount'] + $row2['sittercount'] + $row2['techcount']),1);


if(intval($row2['rnVariance'])==1){
	$variance = $row2['hrsVarianceTotal'];
}else{
	$variance = $row2['nvariance'];
}
$pdf->Row(array($row2['deptName'],$date,$patients,$charge,$triage,$blt,$rn,$techs,$sitters,$orien,$other,$totalRes,$note));
}

$pdf->AliasNbPages();
$pdf->SetFont('Arial','',10);
$pdf->AddPage2('L');
$pdf->SetWidths2(array(45, 20, 25, 25, 35, 35, 60));
//$shifts = intval($row2['shiftsperDay']);

//if($shifts<=0){
//	$shiftcount=1;
//}else{
//	$shiftcount=$shifts;
//}

foreach($data2 as $row2){
	
	$date = $row2['newDate'] . ' - ' . $row2['shiftName']; 
	$prod = $row2['prod'];
	$es1 = $row2['oneto1'];
	$es2 = $row2['oneto2'];
	$es3 = $row2['oneto3'];
	$es4 = $row2['oneto4'];
	$es5 = $row2['oneto5'];
	$esp = $row2['oneto6'];
	$gr10 = $row2['oneto7'];
	$gr5 = $row2['oneto8'];
	$pdg = $row2['oneto9'];
	$brd = $row2['oneto10'];
	$wait = $row2['oneto11'];
	
	$patients = $row2['atotal'] + $row2['ltotal'] + $row2['patientCount2'];
	
	$note = stripslashes($row2['note']);
	

if(intval($row2['rnVariance'])==1){
	$variance = $row2['hrsVarianceTotal'];
}else{
	$variance = $row2['nvariance'];
}
$pdf->Row2(array($row2['deptName'],$date,$patients,$gr5,$brd,$wait,$note));

}



$filename=substr($data2[0]['accountName'],0,4) . 'download' . $data2[0]['newDate'] . '.pdf';
$filelocation="/home/prnadmin/public_html/dailyreports/" . substr($data2[0]['accountName'],0,5) . $data2[0]['id'] . 'download' . $data2[0]['newDate'] . ".pdf";
$pdf->Output($filename,'D');

exit();

}

function getpdf11(){
$deptId = 30; //(isset($_REQUEST['deptId'])?$_REQUEST['deptId']:'');
$pdfId = (isset($_REQUEST['pdfId'])?$_REQUEST['pdfId']:'');
$userId = Config::get('account')['id'];
//$userId = 411;
$prodCount = Config::get('account')['prodCount'];

require_once '.././inc/class.fpdf11.php';
	
$data2 = Config::get('db') -> get_results("select n.*, n.id as recordId, d.rnVariance, a.displayProd, a.name as accountName, (n.skill1val + n.skill2val + n.skill3val + n.skill4val + n.skill5val + n.skill6val + n.skill7val + n.skill8val + n.skill9val + n.skill10val + n.addResourceHrs) as Hours, (select count(`dayDate`) from `productiveNewData` where `deptId`=n.deptId and `whpPlan`=0 and `dayDate`=n.dayDate) as shiftsperDay, s.shift as shiftName, d.shiftsperDay as unused, d.prodMeasure, n.antecount as rn, CONCAT(ROUND(n.nproductivity,1),'%') as prod, n.techcount as techs, u.last_name as charge, d.dept as deptName, DATE_FORMAT(n.dayDate,'%m/%d/%y') as newDate, DATE_FORMAT(CURDATE(),'%m%d%y') as filedate, u.last_name as Director, DATE_SUB(CURDATE(), INTERVAL (a.payPeriod-(ROUND(a.payPeriod-((((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod)-FLOOR((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod))*a.payPeriod),0))) DAY) AS startDay, DATE_ADD(CURDATE(), INTERVAL (ROUND(a.payPeriod-((((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod)-FLOOR((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod))*a.payPeriod),0)-1) DAY) AS endDay, a.id as accountId, SUM(CASE WHEN n.actualWHPimport>0 then n.hrsVarianceActual ELSE n.hrsVariance END) as hrsVarianceTotal, SUM(CASE WHEN n.actualWHPimport>0 then n.actualWHPimport ELSE n.actualWHP END) as actualWHPTotal FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveShifts` as s on s.id = n.shift left join `productiveAccount` as a on a.id=n.accountId left outer Join `productiveUser` as u on u.id={$userId} where n.activeRecord>0 AND d.active>0 AND n.userId>0 AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate and n.deptId={$deptId} group by n.accountId, n.deptId, n.dayDate, n.shift  
ORDER BY d.dept ASC, n.dayDate DESC, n.shift DESC");

$pdf = new PDF_MC_Table();
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',10);
$pdf->AddPage('L');

$pdf->SetWidths(array(45, 20, 18, 15, 25, 19, 15, 17, 14, 14, 18, 60));

$shifts = intval($row2['shiftsperDay']);

if($shifts<=0){
	$shiftcount=1;
}else{
	$shiftcount=$shifts;
}

foreach($data2 as $row2){
	
$date = $row2['newDate'] . ' - ' . $row2['shiftName']; 
$prod = $row2['prod'];

	$charge = round($row2['chargecount'],1);
	$triage = $row2['customNurse'];
	$blt = $row2['seccount'];
	$rn = round($row2['antecount'],0);
	$techs = $row2['techs'];
	$sitters = $row2['sittercount'];
	$psa = $row2['otherNurse1'];
	$other = $row2['otherNurse3'];
	$orien = $row2['otherNurse2'];
	$patients = $row2['atotal'] + $row2['ltotal'] + $row2['patientCount2'];
	$rnVar = '-';
	$note = stripslashes($row2['note']);
	$totalRes = round(($row2['antecount'] + $row2['chargecount'] + $row2['customNurse'] + $row2['otherNurse1'] + $row2['otherNurse2'] + $row2['otherNurse3'] + $row2['seccount'] + $row2['sittercount'] + $row2['techcount']),1);


if(intval($row2['rnVariance'])==1){
	$variance = $row2['hrsVarianceTotal'];
}else{
	$variance = $row2['nvariance'];
}
$pdf->Row(array($row2['deptName'],$date,$patients,$charge,$triage,$techs,$rn,$psa,$orien,$other,$sitters,$note));
}

//$pdf->AliasNbPages();
$pdf->SetFont('Arial','',10);
$pdf->AddPage2('L');
$pdf->SetWidths2(array(30, 20, 16, 13, 13, 13, 13, 13, 25, 20, 25, 60));
$shifts = intval($row2['shiftsperDay']);

if($shifts<=0){
	$shiftcount=1;
}else{
	$shiftcount=$shifts;
}

foreach($data2 as $row2){
	
	$date = $row2['newDate'] . ' - ' . $row2['shiftName']; 
	$prod = $row2['prod'];
	$es1 = $row2['oneto1'];
	$es2 = $row2['oneto2'];
	$es3 = $row2['oneto3'];
	$es4 = $row2['oneto4'];
	$es5 = $row2['oneto5'];
	$var = round($row2['nvariance'],0);
	$totalResources = round(($row2['antecount'] + $row2['chargecount'] + $row2['customNurse'] + $row2['otherNurse1'] + $row2['otherNurse2'] + $row2['otherNurse3'] + $row2['seccount'] + $row2['sittercount'] + $row2['techcount']),1);
	$totalRN = $row2['antecount'] + $row2['customNurse'];
	
	$patients = $row2['atotal'] + $row2['ltotal'] + $row2['patientCount2'];
	
	$note = stripslashes($row2['note']);
	

if(intval($row2['rnVariance'])==1){
	$variance = $row2['hrsVarianceTotal'];
}else{
	$variance = $row2['nvariance'];
}
$pdf->Row2(array($row2['deptName'],$date,$patients,$es1,$es2,$es3,$es4,$es5,$var,$totalRN,$totalResources,$note));

}



$filename=substr($data2[0]['accountName'],0,4) . 'download' . $data2[0]['newDate'] . '.pdf';
$filelocation="/home/prnadmin/public_html/dailyreports/" . substr($data2[0]['accountName'],0,5) . $data2[0]['id'] . 'download' . $data2[0]['newDate'] . ".pdf";
$pdf->Output($filename,'D');

exit();

}

function getpdf7(){
$deptId = 165; //(isset($_REQUEST['deptId'])?$_REQUEST['deptId']:'');
$pdfId = (isset($_REQUEST['pdfId'])?$_REQUEST['pdfId']:'');
$userId = Config::get('account')['id'];
//$userId = 411;
$prodCount = Config::get('account')['prodCount'];

require_once '.././inc/class.fpdf7.php';
	
$data2 = Config::get('db') -> get_results("select n.*, n.id as recordId, d.rnVariance, a.displayProd, a.name as accountName, (n.skill1val + n.skill2val + n.skill3val + n.skill4val + n.skill5val + n.skill6val + n.skill7val + n.skill8val + n.skill9val + n.skill10val + n.addResourceHrs) as Hours, (select count(`dayDate`) from `productiveNewData` where `deptId`=n.deptId and `whpPlan`=0 and `dayDate`=n.dayDate) as shiftsperDay, s.shift as shiftName, d.shiftsperDay as unused, d.prodMeasure, n.antecount as rn, CONCAT(ROUND(n.nproductivity,1),'%') as prod, n.techcount as techs, u.last_name as charge, d.dept as deptName, DATE_FORMAT(n.dayDate,'%m/%d/%y') as newDate, DATE_FORMAT(CURDATE(),'%m%d%y') as filedate, u.last_name as Director, DATE_SUB(CURDATE(), INTERVAL (a.payPeriod-(ROUND(a.payPeriod-((((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod)-FLOOR((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod))*a.payPeriod),0))) DAY) AS startDay, DATE_ADD(CURDATE(), INTERVAL (ROUND(a.payPeriod-((((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod)-FLOOR((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod))*a.payPeriod),0)-1) DAY) AS endDay, a.id as accountId, SUM(CASE WHEN n.actualWHPimport>0 then n.hrsVarianceActual ELSE n.hrsVariance END) as hrsVarianceTotal, SUM(CASE WHEN n.actualWHPimport>0 then n.actualWHPimport ELSE n.actualWHP END) as actualWHPTotal FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveShifts` as s on s.id = n.shift left join `productiveAccount` as a on a.id=n.accountId left outer Join `productiveUser` as u on u.id={$userId} where n.activeRecord>0 AND d.active>0 AND n.userId>0 AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate and n.deptId={$deptId} group by n.accountId, n.deptId, n.dayDate, n.shift  
ORDER BY d.dept ASC, n.dayDate DESC, n.shift DESC");

$pdf = new PDF_MC_Table();
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',10);
$pdf->AddPage('L');

$pdf->SetWidths(array(45, 20, 18, 15, 25, 19, 15, 17, 14, 14, 18, 60));

$shifts = intval($row2['shiftsperDay']);

if($shifts<=0){
	$shiftcount=1;
}else{
	$shiftcount=$shifts;
}

foreach($data2 as $row2){
	
$date = $row2['newDate'] . ' - ' . $row2['shiftName']; 
$prod = $row2['prod'];

	$charge = round($row2['chargecount'],1);
	$triage = $row2['customNurse'];
	$blt = $row2['seccount'];
	$rn = round($row2['antecount'],0);
	$techs = $row2['techs'];
	$sitters = $row2['sittercount'];
	$psa = $row2['otherNurse1'];
	$other = $row2['otherNurse3'];
	$orien = $row2['otherNurse2'];
	$patients = $row2['atotal'] + $row2['ltotal'] + $row2['patientCount2'];
	$rnVar = '-';
	$note = stripslashes($row2['note']);
	$totalRes = round(($row2['antecount'] + $row2['chargecount'] + $row2['customNurse'] + $row2['otherNurse1'] + $row2['otherNurse2'] + $row2['otherNurse3'] + $row2['seccount'] + $row2['sittercount'] + $row2['techcount']),1);


if(intval($row2['rnVariance'])==1){
	$variance = $row2['hrsVarianceTotal'];
}else{
	$variance = $row2['nvariance'];
}
$pdf->Row(array($row2['deptName'],$date,$patients,$charge,$triage,$techs,$rn,$psa,$orien,$other,$sitters,$note));
}

//$pdf->AliasNbPages();
$pdf->SetFont('Arial','',10);
$pdf->AddPage2('L');
$pdf->SetWidths2(array(30, 20, 16, 13, 13, 13, 13, 13, 25, 20, 25, 60));
$shifts = intval($row2['shiftsperDay']);

if($shifts<=0){
	$shiftcount=1;
}else{
	$shiftcount=$shifts;
}

foreach($data2 as $row2){
	
	$date = $row2['newDate'] . ' - ' . $row2['shiftName']; 
	$prod = $row2['prod'];
	$es1 = $row2['oneto1'];
	$es2 = $row2['oneto2'];
	$es3 = $row2['oneto3'];
	$es4 = $row2['oneto4'];
	$es5 = $row2['oneto5'];
	$var = round($row2['nvariance'],0);
	$totalResources = round(($row2['antecount'] + $row2['chargecount'] + $row2['customNurse'] + $row2['otherNurse1'] + $row2['otherNurse2'] + $row2['otherNurse3'] + $row2['seccount'] + $row2['sittercount'] + $row2['techcount']),1);
	$totalRN = $row2['antecount'] + $row2['customNurse'];
	
	$patients = $row2['atotal'] + $row2['ltotal'] + $row2['patientCount2'];
	
	$note = stripslashes($row2['note']);
	

if(intval($row2['rnVariance'])==1){
	$variance = $row2['hrsVarianceTotal'];
}else{
	$variance = $row2['nvariance'];
}
$pdf->Row2(array($row2['deptName'],$date,$patients,$es1,$es2,$es3,$es4,$es5,$var,$totalRN,$totalResources,$note));

}



$filename=substr($data2[0]['accountName'],0,4) . 'download' . $data2[0]['newDate'] . '.pdf';
$filelocation="/home/prnadmin/public_html/dailyreports/" . substr($data2[0]['accountName'],0,5) . $data2[0]['id'] . 'download' . $data2[0]['newDate'] . ".pdf";
$pdf->Output($filename,'D');

exit();

}

function getpdf99(){

//require_once '.././inc/FPDF/fpdf.php';

class PDF_MC_Table extends FPDF{
var $widths;
var $aligns = array('L','C','L','R','R','R','R','R','R','R','L');
//$acct = 2;

function SetWidths($w){
    //Set the array of column widths
    $this->widths=$w;
}

function SetAligns($a){
    //Set the array of column alignments
    $this->aligns=$a;
}

function Row($data2){
	
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data2);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data2[$i]));
    $h=5*$nb;
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of the row
    for($i=0;$i<count($data2);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
        $this->Rect($x,$y,$w,$h);
        //Print the text
        $this->MultiCell($w,5,$data2[$i],0,$a);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
}

function CheckPageBreak($h){
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation);
}

function NbLines($w,$txt){
    //Computes the number of lines a MultiCell of width w will take
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r",'',$txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)
    {
        $c=$s[$i];
        if($c=="\n")
        {
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
            continue;
        }
        if($c==' ')
            $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax)
        {
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
            }
            else
                $i=$sep+1;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
        }
        else
            $i++;
    }
    return $nl;
}

function Header(){
$userId = Config::get('account')['id'];
$accountId = Config::get('account')['accountId'];
$enterpriseId = Config::get('account')['enterpriseId'];
$role = Config::get('account')['role'];
	
if(intval($role)<8){
		$searchAdd = " AND n.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId` ={$userId})";
	}else if(intval($role)>=8 && intval($role)<=10) {
		$searchAdd = " AND n.accountId={$accountId}";
	}else if(intval($role)>10 && intval($role)<=12) {
		$searchAdd = " AND n.accountId={$accountId}";
	}else if(intval($role)>12 && intval($role)<100) {
		$searchAdd = " AND t.enterpriseId={$enterpriseId}";
	}else{
		$searchAdd = " AND n.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId` ={$userId})";
}


$deptCount = Config::get('db') -> get_results("select n.deptId, d.dept as deptName  FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveAccount` as a on a.id=n.accountId where d.active>0$searchAdd group by n.accountId, n.deptId");

if(count($deptCount)>38){
	$pages = 2;
}else{
	$pages = 1;
}


$newdata = Config::get('db') -> get_results("select a.name as accountName, a.displayProd, DATE_FORMAT(u.lastStartDate,'%m/%d/%y') AS startDay, DATE_FORMAT(u.lastEndDate,'%m/%d/%y') AS endDay FROM `productiveAccount` a left join `productiveUser` as u on u.id={$userId} where a.id={$accountId} group by a.id");

	$header = array('Unit', 'Director', 'Manager', 'Compliance');
	$header2 = array('Unit', 'Date/Shift', 'User', 'PTs/Procs', 'RNs', 'Techs', 'Other', 'RN Var', 'GRID Var' , 'HRS Var' , 'Notes');
   
	
	// Logo
    //$this->Image('logo.png',10,6,30);
    // Arial bold 15
    $this->SetFont('Arial','B',15);
    
	// Move to the right
	if ($this->page <= $pages){
		// Move to the right
    $this->Cell(80);
    // Title
    $this->Cell(30,10,$newdata[0]['accountName'],0,0,'C');
    // Line break
    $this->Ln(6);
    $this->Cell(80);
	$this->SetFont('Arial','B',12);
	$this->Cell(30,10,'Compliance Report Summary',0,0,'C');
	// Line break
    $this->Ln(6);
	// Move to the right
    $this->Cell(80);
	$this->SetFont('Arial','B',12);
	$this->Cell(30,10,$newdata[0]['startDay'] . ' - ' . $newdata[0]['endDay'],0,0,'C');
	//$this->Cell(30,10,'Date: ' . $newdata[0]['reportDate'],0,0,'C');
	// Line break
    $this->Ln(15);
	// Column widths
    $w = array(85, 37, 37, 30);
    // Header
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C');
    $this->Ln();
    // Data
	}else{
		// Move to the right
    $this->Cell(120);
    // Title
    $this->Cell(30,10,$newdata[0]['accountName'],0,0,'C');
    // Line break
    $this->Ln(6);
	$this->Cell(120);
	$this->SetFont('Arial','B',12);
	$this->Cell(30,10,'Compliance Report Detail',0,0,'C');
	// Line break
    $this->Ln(6);
	// Move to the right
    $this->Cell(120);
	$this->SetFont('Arial','B',12);
	//$this->Cell(30,10,'Date: ' . $newdata[0]['reportDate'],0,0,'C');
	$this->Cell(30,10,$newdata[0]['startDay'] . ' - ' . $newdata[0]['endDay'],0,0,'C');
	// Line break
    $this->Ln(15);
	// Column widths
    $w = array(55, 30, 20, 20, 10, 15, 15, 20, 20, 20, 55);
    // Header
	$this->SetFont('Arial','B',10);
    for($i=0;$i<count($header2);$i++)
        $this->Cell($w[$i],7,$header2[$i],1,0,'C');
    $this->Ln();
	}
	 
    
}

// Page footer
function Footer(){
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
}

function ImprovedTable($data){
    $w = array(85, 37, 37, 30);
    foreach($data as $row){
	if($row['compliance']>0.000){
		$compliance = ROUND($row['compliance'],1) . '%';
	}else{
		$compliance = 'No Data';
	}
    
        $this->Cell($w[0],6,$row['deptName'],'LRB');
        $this->Cell($w[1],6,$row['Director'],'LRB');
		$this->Cell($w[2],6,$row['Manager'],'LRB');
        $this->Cell($w[3],6,$compliance,'LRB',1,'R');
        
    }
    // Closing line
    //$this->Cell(array_sum($w),0,'','T');
}

function header2($header2){
    // Column widths
    $w = array(55, 30, 20, 20, 10, 15, 15, 20, 20, 20, 55);
    // Header
    for($i=0;$i<count($header2);$i++)
        $this->Cell($w[$i],7,$header2[$i],1,0,'C');
    $this->Ln();
}


}

$pdf = new PDF_MC_Table();

$userId = Config::get('account')['id'];
$accountId = Config::get('account')['accountId'];
$enterpriseId = Config::get('account')['enterpriseId'];
$role = Config::get('account')['role'];
	
if(intval($role)<8){
		$searchAdd = " AND n.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId` ={$userId})";
	}else if(intval($role)>=8 && intval($role)<=10) {
		$searchAdd = " AND n.accountId={$accountId}";
	}else if(intval($role)>10 && intval($role)<=12) {
		$searchAdd = " AND n.accountId={$accountId}";
	}else if(intval($role)>12 && intval($role)<100) {
		$searchAdd = " AND t.enterpriseId={$enterpriseId}";
	}else{
		$searchAdd = " AND n.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId` ={$userId})";
}

$data = Config::get('db') -> get_results("select n.deptId, SUM(CASE WHEN n.userId>0 THEN n.nvariance ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END) as varAvg, a.displayProd, a.productivityPosNeg, n.actualWHP, a.id as accountId, a.name as accountName, d.dept as deptName, d.prodMeasure, DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%m%d%y') as filedate, u.last_name as Director, i.last_name as Manager, a.id as accountId, ROUND(SUM((CASE WHEN n.actualWHPimport>0 and n.activeRecord>0 then n.hrsVarianceActual ELSE 0 END) + (CASE WHEN n.actualWHPimport=0.000 and n.activeRecord>0 then n.hrsVariance ELSE 0 END)),1) as hrsVarianceTotal, SUM((CASE WHEN n.userId>0 and n.activeRecord>0 THEN 1 ELSE 0 END)) as report, SUM((CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END)) as totalreports, ((SUM(CASE WHEN (n.userId>0 and n.activeRecord>0) THEN 1 ELSE 0 END) + (CASE WHEN n.userId=0 and n.activeRecord>0 and n.planSubmitted=1 THEN 1 ELSE 0 END)) / (SUM((CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END))) * 100) as compliance FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveAccount` as a on a.id=n.accountId left outer join `productiveUser` as i on i.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id AND `primaryUnit`=6 AND `unitAssigned`=0 order by `primaryUnit` ASC LIMIT 0,1) left join `productiveUser` as b on b.id={$userId} left outer Join `productiveUser` as u on u.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id AND `primaryUnit`=7 order by `primaryUnit` ASC LIMIT 0,1) where d.active>0 AND n.dayDate >=b.lastStartDate AND n.dayDate <=b.lastEndDate$searchAdd group by n.accountId, n.deptId  
ORDER BY compliance DESC, d.dept ASC");
$data2 = Config::get('db') -> get_results("select n.*, l.noreply, l.alertName, a.name as accountName, a.displayProd, (n.skill1val + n.skill2val + n.skill3val + n.skill4val + n.skill5val + n.skill6val) as Hours, DATE_FORMAT(n.dayDate,'%m/%d') as newDate, s.shift as shiftName, d.shiftsperDay, d.prodMeasure, CONCAT(ROUND(n.nproductivity,1),'%') as prod, u.last_name as charge, o.last_name as submitted, d.dept as deptName, DATE_FORMAT(CURDATE(),'%m%d%y') as filedate, u.last_name as Director, DATE_SUB(CURDATE(), INTERVAL (a.payPeriod-(ROUND(a.payPeriod-((((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod)-FLOOR((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod))*a.payPeriod),0))) DAY) AS startDay, DATE_ADD(CURDATE(), INTERVAL (ROUND(a.payPeriod-((((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod)-FLOOR((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod))*a.payPeriod),0)-1) DAY) AS endDay, a.id as accountId, SUM(CASE WHEN n.actualWHPimport>0 then n.hrsVarianceActual ELSE n.hrsVariance END) as hrsVarianceTotal, SUM(CASE WHEN n.actualWHPimport>0 then n.actualWHPimport ELSE n.actualWHP END) as actualWHPTotal, d.useGrid FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveShifts` as s on s.id = n.shift left join `productiveAccount` as a on a.id=n.accountId LEFT OUTER JOIN `productiveLabel` as l on l.id=a.label left outer join `productiveUser` as o on o.id=n.userId left outer Join `productiveUser` as u on u.id={$userId} where d.active>0 AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate$searchAdd group by n.accountId, n.deptId, n.dayDate, n.shift  
ORDER BY d.dept ASC, n.dayDate DESC, n.shift DESC");
//$data3 = Config::get('db') -> get_results("select n.atotal, (CASE n.deptId>=0 then 2 ELSE 2 END) as type, n.totalHours as hours, n.procedureCount as visits, CONCAT(DATE_FORMAT(n.dayDate,'%m/%d'), ' - ', s.shift) as shiftName, n.antecount as rn, CONCAT(ROUND(n.nproductivity,1),'%') as prod, n.hrsVariance, n.techcount as techs, n.note, u.last_name as charge, d.dept as deptName, DATE_FORMAT(CURDATE(),'%m%d%y') as filedate, u.last_name as Director, DATE_SUB(CURDATE(), INTERVAL (a.payPeriod-(ROUND(a.payPeriod-((((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod)-FLOOR((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod))*a.payPeriod),0))) DAY) AS startDay, DATE_ADD(CURDATE(), INTERVAL (ROUND(a.payPeriod-((((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod)-FLOOR((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod))*a.payPeriod),0)-1) DAY) AS endDay, a.id as accountId, SUM(CASE WHEN n.actualWHPimport>0 then n.hrsVarianceActual ELSE n.hrsVariance END) as hrsVarianceTotal, SUM((CASE WHEN n.userId>0 THEN 1 ELSE 0 END)) as report, SUM((CASE WHEN n.userId=0 THEN 1 ELSE 0 END)) as noreport, ((SUM((CASE WHEN n.userId>0 THEN 1 ELSE 0 END)) / (SUM((CASE WHEN n.userId>0 THEN 1 ELSE 0 END)) + SUM((CASE WHEN n.userId=0 THEN 1 ELSE 0 END)))) * 100) as compliance FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveShifts` as s on s.id = n.shift left join `productiveAccount` as a on a.id=n.accountId left outer Join `productiveUser` as u on u.id=n.userId where d.active>0 AND n.accountId=19 AND d.prodMeasure=2 AND n.activeRecord>0 AND n.dayDate BETWEEN DATE_SUB(CURDATE(), INTERVAL (a.payPeriod-(ROUND(a.payPeriod-((((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod)-FLOOR((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod))*a.payPeriod),0))) DAY) AND DATE_ADD(CURDATE(), INTERVAL (ROUND(a.payPeriod-((((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod)-FLOOR((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod))*a.payPeriod),0)-1) DAY) group by n.accountId, n.deptId, n.dayDate, n.shift  
//ORDER BY d.dept ASC, n.dayDate DESC, n.shift DESC");

// Data loading
//$data = $pdf->LoadData('countries.txt');
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',12);
$pdf->AddPage('P');
$pdf->ImprovedTable($data);

if($role <=6){
$pdf->SetFont('Arial','',10);
$pdf->AddPage('L');
$pdf->SetWidths(array(55, 30, 20, 20, 10, 15, 15, 20, 20, 20, 55));
//$pdf->header2($header2);

foreach($data2 as $row2){
	
if($row2['shiftName']){
	$date = $row2['newDate'] . ' - ' . $row2['shiftName']; 
}else{
	$date = $row2['newDate'];
}

	
if(intval($row2['prodMeasure'])==2 || intval($row2['prodMeasure'])==3){
	$rn = '-';
	$techs = '-';
	$patients = round($row2['procedureCount'],0);
	$totalHours = $row2['Hours'];
	$actualWHP = $row2['actualWHPTotal'];
	$other = $row2['customNurse'] + $row2['otherNurse1'] + $row2['otherNurse2'] + $row2['otherNurse3'];
	$submitted = $row2['submitted'];
}else{
	$rn = $row2['antecount'] + $row2['customNurse'] + $row2['customNurse2'] + $row2['chargecount'];
	$patients = $row2['atotal'] + $row2['ltotal'] + $row2['patientCount2'];	
	$techs = $row2['techcount'];
	$other =  + $row2['otherNurse1'] + $row2['otherNurse2'] + $row2['otherNurse3'] + $row2['sittercount'] + $row2['seccount'];
	$actualWHP = $row2['actualWHP'];
	$submitted = $row2['submitted'];
}

if(intval($row2['useGrid'])==1 && intval($row2['userId'])!=0){
	$gvariance = $row2['gvariance'];
}else{
	$gvariance = '-';
}

if(intval($row2['userId'])==0){
	$nvariance = '-';
	$hrsvariance = '-';
}else{
	$hrsvariance = round($row2['hrsVariance'],1);
	$nvariance = round($row2['nvariance'],1);
}

$pdf->Row(array($row2['deptName'],$date,$submitted,$patients,$rn,$techs,$other,$nvariance,$gvariance,$hrsvariance,$row2['note']));
}
}

$filename=substr($data2[0]['accountName'],0,4) . 'download' . $data2[0]['newDate'] . '.pdf';
$filelocation="/home/prnadmin/public_html/dailyreports/" . substr($data2[0]['accountName'],0,4) . $data2[0]['id'] . 'download' . $data2[0]['newDate'] . ".pdf";
$pdf->Output($filename,'D');

exit();

}

function getpdf285(){
//$deptId = 366;
//$userId = Config::get('account')['id'];
//$userId = 411;
//$prodCount = Config::get('account')['prodCount'];

//require_once '.././inc/class.fpdf.php';

class PDF_MC_Table extends FPDF{

var $widths;
var $aligns = array('L','C','L','R','R','R','R','R','R','R','R','R','L');


function SetWidths($w){
    //Set the array of column widths
    $this->widths=$w;
}

function SetAligns($a){
    //Set the array of column alignments
    $this->aligns=$a;
}

function Row($data2){
	
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data2);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data2[$i]));
    $h=5*$nb;
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of the row
    for($i=0;$i<count($data2);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
        $this->Rect($x,$y,$w,$h);
        //Print the text
        //$this->MultiCell($w,5,$data2[$i],0,$a);
		$this->MultiCell($w,5,$data2[$i],0,$a);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
}

function CheckPageBreak($h){
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation);
}

function NbLines($w,$txt){
    //Computes the number of lines a MultiCell of width w will take
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r",'',$txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)
    {
        $c=$s[$i];
        if($c=="\n")
        {
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
            continue;
        }
        if($c==' ')
            $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax)
        {
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
            }
            else
                $i=$sep+1;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
        }
        else
            $i++;
    }
    return $nl;
}

function Header(){
$userId = Config::get('account')['id'];
$accountId = 8;
$deptId = 285;
	

//$deptCount = Config::get('db') -> get_results("select n.deptId, d.dept as deptName  FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveAccount` as a on a.id=n.accountId left outer Join `productiveUser` as u on u.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id and `unitAssigned`=0 AND (`primaryUnit`=7 or `primaryUnit`=6) order by `primaryUnit` DESC LIMIT 0,1) where d.active>0 AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate$searchAdd group by n.accountId, n.deptId");

$newdata = Config::get('db') -> get_results("select a.name as accountName, a.displayProd, DATE_FORMAT(u.lastStartDate,'%m/%d/%y') AS startDay, DATE_FORMAT(u.lastEndDate,'%m/%d/%y') AS endDay FROM `productiveAccount` a left join `productiveUser` as u on u.id={$userId} where a.id={$accountId} group by a.id");
	
	$header = array('Unit', 'Leader', 'Compliance', 'Avg. Variance');
	$header2 = array('Unit', 'Date/Shift', 'User', 'Minutes', 'RNs', 'Techs', 'Support', 'Sitters', 'Orien.', 'Other', 'Variance', 'Notes');

	
	// Logo
    //$this->Image('logo.png',10,6,30);
    // Arial bold 15
    $this->SetFont('Arial','B',15);
    
	// Move to the right
    $this->Cell(120);
    // Title
    $this->Cell(30,10,$newdata[0]['accountName'],0,0,'C');
    // Line break
    $this->Ln(6);
	$this->Cell(120);
	$this->SetFont('Arial','B',12);
	$this->Cell(30,10,'Staffing Detail',0,0,'C');
	// Line break
    $this->Ln(6);
	// Move to the right
    $this->Cell(120);
	$this->SetFont('Arial','B',12);
	$this->Cell(30,10,$newdata[0]['startDay'] . ' - ' . $newdata[0]['endDay'],0,0,'C');
	// Line break
    $this->Ln(15);
	// Column widths
    $w = array(43, 30, 25, 15, 15, 15, 22, 15, 15, 15, 20, 55);
    // Header
	$this->SetFont('Arial','B',10);
    for($i=0;$i<count($header2);$i++)
        $this->Cell($w[$i],7,$header2[$i],1,0,'C');
    $this->Ln();
	
	 
    
}

// Page footer
function Footer(){
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
}

function ImprovedTable($data){
    $w = array(75, 30, 40, 40);
    foreach($data as $row)
    {
        $this->Cell($w[0],6,$row['deptName'],'LRB');
        $this->Cell($w[1],6,$row['Director'],'LRB');
        $this->Cell($w[2],6,number_format($row['compliance'],1,'.','') . '%','LRB',0,'R');
		if($row['displayProd']==1){
        $this->Cell($w[3],6,number_format($row['hrsVarianceTotal'],1,'.',','),'LRB',1,'R');
		}else{
		$this->Cell($w[3],6,number_format($row['varAvg'],1,'.',','),'LRB',1,'R');
		}
        //$this->Ln();
    }
    // Closing line
    //$this->Cell(array_sum($w),0,'','T');
}


var $widths2;
var $aligns2 = array('L','C','L','R','R','R','R','R','R','R','R','R');


function SetWidths2($w){
    //Set the array of column widths
    $this->widths=$w;
}

function SetAligns2($a){
    //Set the array of column alignments
    $this->aligns=$a;
}

function Row2($data2){
	
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data2);$i++)
        $nb=max($nb,$this->NbLines2($this->widths[$i],$data2[$i]));
    $h=5*$nb;
    //Issue a page break first if needed
    $this->CheckPageBreak2($h);
    //Draw the cells of the row
    for($i=0;$i<count($data2);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns2[$i]) ? $this->aligns2[$i] : 'L';
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
        $this->Rect($x,$y,$w,$h);
        //Print the text
        //$this->MultiCell($w,5,$data2[$i],0,$a);
		$this->MultiCell($w,5,$data2[$i],0,$a);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
}

function CheckPageBreak2($h){
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage2($this->CurOrientation);
}

function NbLines2($w,$txt){
    //Computes the number of lines a MultiCell of width w will take
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r",'',$txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)
    {
        $c=$s[$i];
        if($c=="\n")
        {
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
            continue;
        }
        if($c==' ')
            $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax)
        {
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
            }
            else
                $i=$sep+1;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
        }
        else
            $i++;
    }
    return $nl;
}

function Header2(){
$userId = Config::get('account')['id'];
$accountId = 8;
$enterpriseId = Config::get('account')['enterpriseId'];
$deptId = 285;
	

$newdata = Config::get('db') -> get_results("select d.*, a.name as accountName, a.displayProd, DATE_FORMAT(u.lastStartDate,'%m/%d/%y') AS startDay, DATE_FORMAT(u.lastEndDate,'%m/%d/%y') AS endDay FROM `ProductiveDept` d left join `productiveAccount` as a on a.id = d.accountId left join `productiveUser` as u on u.id={$userId} where d.id={$deptId} group by d.id");

$header3 = array('Unit', 'Date/Shift', 'User', 'ER Pts', 'Hold/Psy', '>5hrs', 'Hold/IP', 'Wtg Rm',$newdata[0]['track1Desc'],$newdata[0]['track2Desc'],$newdata[0]['track3Desc'],$newdata[0]['track4Desc']);

	
	// Logo
    //$this->Image('logo.png',10,6,30);
    // Arial bold 15
    $this->SetFont('Arial','B',15);
    
	// Move to the right
    $this->Cell(120);
    // Title
    $this->Cell(30,10,$newdata[0]['accountName'],0,0,'C');
    // Line break
    $this->Ln(6);
	$this->Cell(120);
	$this->SetFont('Arial','B',12);
	$this->Cell(30,10,'Patient Detail',0,0,'C');
	// Line break
    $this->Ln(6);
	// Move to the right
    $this->Cell(120);
	$this->SetFont('Arial','B',12);
	$this->Cell(30,10,$newdata[0]['startDay'] . ' - ' . $newdata[0]['endDay'],0,0,'C');
	// Line break
    $this->Ln(15);
	// Column widths
    $w = array(43, 30, 25, 20, 20, 20, 20, 20, 20, 20, 20, 20);
    // Header
	//$this->SetFont('Arial','B',11);
    for($i=0;$i<count($header3);$i++){
		$x=$this->GetX();
        $y=$this->GetY();
		if(strlen($header3[$i])>$w[$i]){
		$this->SetFont('Arial','B',7);	
		}else{
		$this->SetFont('Arial','B',10);	
		}
        $this->Rect($x,$y,$w[$i],10);
		$this->MultiCell($w[$i],5,$header3[$i],0,'C');
		$this->SetXY($x+$w[$i],$y);
	}
    $this->Ln();
	$this->Ln();
	
	 
    
}

// Page footer
function Footer2(){
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
}


}
	
$pdf = new PDF_MC_Table();

$userId = Config::get('account')['id'];
$accountId = 8;
$enterpriseId = Config::get('account')['enterpriseId'];
$deptId = 285;
	

$data2 = Config::get('db') -> get_results("select n.*, l.noreply, l.alertName, a.name as accountName, a.displayProd, (n.skill1val + n.skill2val + n.skill3val + n.skill4val + n.skill5val + n.skill6val) as Hours, DATE_FORMAT(n.dayDate,'%m/%d') as newDate, s.shift as shiftName, d.shiftsperDay, d.prodMeasure, CONCAT(ROUND(n.nproductivity,1),'%') as prod, u.last_name as charge, o.last_name as submitted, d.dept as deptName, DATE_FORMAT(CURDATE(),'%m%d%y') as filedate, u.last_name as Director, DATE_SUB(CURDATE(), INTERVAL (a.payPeriod-(ROUND(a.payPeriod-((((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod)-FLOOR((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod))*a.payPeriod),0))) DAY) AS startDay, DATE_ADD(CURDATE(), INTERVAL (ROUND(a.payPeriod-((((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod)-FLOOR((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod))*a.payPeriod),0)-1) DAY) AS endDay, a.id as accountId, SUM(CASE WHEN n.actualWHPimport>0 then n.hrsVarianceActual ELSE n.hrsVariance END) as hrsVarianceTotal, SUM(CASE WHEN n.actualWHPimport>0 then n.actualWHPimport ELSE n.actualWHP END) as actualWHPTotal, d.useGrid FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveShifts` as s on s.id = n.shift left join `productiveAccount` as a on a.id=n.accountId LEFT OUTER JOIN `productiveLabel` as l on l.id=a.label left outer join `productiveUser` as o on o.id=n.userId left outer Join `productiveUser` as u on u.id={$userId} where d.active>0 AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate AND n.deptId={$deptId} group by n.dayDate, n.shift  
ORDER BY d.dept ASC, n.dayDate DESC, n.shift DESC");

$pdf->AliasNbPages();
$pdf->SetFont('Arial','',10);
$pdf->AddPage('L');

$pdf->SetWidths(array(43, 30, 25, 15, 15, 15, 22, 15, 15, 15, 20, 55));


//$shifts = intval($row2['shiftsperDay']);

//if($shifts<=0){
//	$shiftcount=1;
//}else{
//	$shiftcount=$shifts;
//}

foreach($data2 as $row2){
	
$date = $row2['newDate'] . ' - ' . $row2['shiftName']; 

if(intval($row2['userId'])==0){
	$submittedby = "-";
	$variance = "-";
	$minutes = "-";
	$pos1 = "-";
	$pos2 = "-";
	$pos3 = "-";
	$pos4 = "-";
	$pos5 = "-";
	$pos6 = "-";
	$note = "-";	
}else{
	$submittedby = $row2['submitted'];
	$variance = round(floatval($row2['hrsVariance']),1);
	//$gvariance = round(floatval($row2['gvariance']),1);
	$minutes = round(floatval($row2['procedureCount']),0);
	$pos4 = $row2['skill8val'];
	$pos1 = $row2['skill2val'];
	$pos6 = $row2['skill10val'];
	$pos2 = $row2['skill4val'];
	$pos5 = $row2['skill9val'];
	$pos3 = $row2['skill6val'];
	
	$note = stripslashes($row2['note']);
}

$pdf->Row(array($row2['deptName'],$date,$submittedby,$minutes,$pos1,$pos2,$pos3,$pos4,$pos5,$pos6,$variance,$note));
}

//$pdf->AliasNbPages();
//$pdf->SetFont('Arial','',10);
//$pdf->AddPage2('L');
//$pdf->SetWidths2(array(43, 30, 25, 20, 20, 20, 20, 20, 20, 20, 20, 20));

/*
foreach($data2 as $row2){
	
	$date = $row2['newDate'] . ' - ' . $row2['shiftName']; 

if(intval($row2['userId'])==0){
	$es1 = "-";
	$es2 = "-";
	$es3 = "-";
	$es4 = "-";
	$es5 = "-";
	$esp = "-";
	$gr10 = "-";
	$gr5 = "-";
	$pdg = "-";
	$brd = "-";
	$wait = "-";
	$patients = "-";
	$note = "-";
	$track1 = "-";
	$track2 = "-";
	$track3 = "-";
	$track4= "-";
	$submittedby = "-";
}else{
	$submittedby = $row2['submittedby'];
	$track1 = $row2['track1'];
	$track2 = $row2['track2'];
	$track3 = $row2['track3'];
	$track4= $row2['track4'];
	$es1 = $row2['oneto1'];
	$es2 = $row2['oneto2'];
	$es3 = $row2['oneto3'];
	$es4 = $row2['oneto4'];
	$es5 = $row2['oneto5'];
	$esp = $row2['oneto6'];
	$gr10 = $row2['oneto7'];
	$gr5 = $row2['oneto8'];
	$pdg = $row2['oneto9'];
	$brd = $row2['oneto10'];
	$wait = $row2['oneto11'];
	$patients = $row2['atotal'] + $row2['ltotal'] + $row2['patientCount2'];
	$note = stripslashes($row2['note']);
}
$pdf->Row2(array($row2['deptName'],$date,$submittedby,$patients,$gr10,$gr5,$pdg,$brd,$track1,$track2,$track3,$track4));

}
*/



$filename=substr($data2[0]['deptName'],0,4) . 'download' . $data2[0]['newDate'] . '.pdf';
$filelocation="/home/prnadmin/public_html/dailyreports/" . substr($data2[0]['deptName'],0,5) . $data2[0]['id'] . 'download' . $data2[0]['newDate'] . ".pdf";
$pdf->Output($filename,'D');

exit();

}

function allUser(){
$userId = Config::get('account')['id'];
$accountId = Config::get('account')['accountId'];
$enterpriseId = Config::get('account')['enterpriseId'];
$role = Config::get('account')['role'];

require_once '.././inc/FPDF/fpdf3.php';

class PDF_MC_Table extends FPDF{

function CheckPageBreak($h){
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation);
}


// Page footer
function Footer(){
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
}

function Header(){
$userId = Config::get('account')['id'];
$accountId = Config::get('account')['accountId'];
$enterpriseId = Config::get('account')['enterpriseId'];
$role = Config::get('account')['role'];
$deptNum = (isset($_REQUEST['e'])) ? $_REQUEST['e'] : 0;

if(intval($deptNum)>0){
	$newdata = Config::get('db') -> get_results("select a.name as accountName, d.dept as deptName,  DATE_FORMAT(u.lastStartDate,'%m/%d/%y') AS startDay, DATE_FORMAT(u.lastEndDate,'%m/%d/%y') AS endDay, a.displayProd, DATE_FORMAT(CURDATE(),'%m/%d/%y') AS reportDate FROM `productiveAccount` a LEFT JOIN `productiveUser` as u on u.id={$userId} left join `ProductiveDept` as d on d.id={$deptNum} where a.id={$accountId} group by a.id");	
	$deptName = " - " . $newdata[0]['deptName'];
	}else{
	$newdata = Config::get('db') -> get_results("select a.name as accountName,  DATE_FORMAT(u.lastStartDate,'%m/%d/%y') AS startDay, DATE_FORMAT(u.lastEndDate,'%m/%d/%y') AS endDay, a.displayProd, DATE_FORMAT(CURDATE(),'%m/%d/%y') AS reportDate FROM `productiveAccount` a LEFT JOIN `productiveUser` as u on u.id={$userId} where a.id={$accountId} group by a.id");
	$deptName='';
	}
	$header = array('Unit', 'Director', 'Manager', 'Reports', 'User', 'Avg RN Var', 'Avg Grid Var');
    $this->SetFont('Arial','B',14);
	// Move to the right
		// Move to the right
    $this->Cell(80);
    // Title
    $this->Cell(30,10,$newdata[0]['accountName'],0,0,'C');
    // Line break
    $this->Ln(6);
    $this->Cell(80);
	$this->SetFont('Arial','B',12);
	$this->Cell(30,10,'User Variance Summary' . $deptName,0,0,'C');
	// Line break
    $this->Ln(6);
	// Move to the right
    $this->Cell(80);
	$this->SetFont('Arial','B',10);
	$this->Cell(30,10,$newdata[0]['startDay'] . ' - ' . $newdata[0]['endDay'],0,0,'C');
	//$this->Cell(30,10,'Date: ' . $newdata[0]['reportDate'],0,0,'C');
	// Line break
    $this->Ln(15);
	// Column widths
    $w = array(50, 25, 25, 20, 20, 25, 25);
    // Header
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C');
    $this->Ln();
    // Data
    
}

function ImprovedTable($data4){
    $w = array(50, 25, 25, 20, 20, 25, 25);
    foreach($data4 as $row){
		if(intval($row['report'])>0){
		$this->SetTextColor(0,0,0);
        $this->Cell($w[0],6,$row['deptName'],'LRB');
        $this->Cell($w[1],6,$row['Director'],'LRB');
		$this->Cell($w[2],6,$row['Manager'],'LRB');
		$this->Cell($w[3],6,$row['report'],'LRB',0,'C');
	    $this->Cell($w[4],6,$row['userName'],'LRB');
		$this->Cell($w[5],6,ROUND($row['varAvg'],1),'LRB',0,'C');
		$this->Cell($w[5],6,ROUND($row['varGrid'],1),'LRB',1,'C');
		}  
    }

}

}
	
$pdf = new PDF_MC_Table();
$userId = Config::get('account')['id'];
$accountId = Config::get('account')['accountId'];
$enterpriseId = Config::get('account')['enterpriseId'];
$role = Config::get('account')['role'];
$deptNum = (isset($_REQUEST['e'])) ? $_REQUEST['e'] : 0;

if(intval($deptNum)>0){
	$shiftAdd = "AND n.deptID={$deptNum} ";
}else{
	$shiftAdd = '';
}

$data4 = Config::get('db') -> get_results("select n.deptId, n.userId, e.last_name as userName, s.shift as shiftName, n.shift, SUM(CASE WHEN n.userId>0 THEN n.nvariance ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END) as varAvg, SUM(CASE WHEN n.userId>0 THEN n.gvariance ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END) as varGrid, a.displayProd, a.productivityPosNeg, n.actualWHP, a.id as accountId, a.name as accountName, d.dept as deptName, d.prodMeasure, DATE_FORMAT(CURDATE(),'%m%d%y') as filedate, u.last_name as Director, i.last_name as Manager, a.id as accountId, ROUND(SUM((CASE WHEN n.actualWHPimport>0 and n.activeRecord>0 then n.hrsVarianceActual ELSE 0 END) + (CASE WHEN n.actualWHPimport=0.000 and n.activeRecord>0 then n.hrsVariance ELSE 0 END)),1) as hrsVarianceTotal, SUM((CASE WHEN n.userId>0 and n.activeRecord>0 THEN 1 ELSE 0 END)) as report, SUM((CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END)) as totalreports, ((SUM(CASE WHEN (n.userId>0 and n.activeRecord>0) THEN 1 ELSE 0 END) + (CASE WHEN n.userId=0 and n.activeRecord>0 and n.planSubmitted=1 THEN 1 ELSE 0 END)) / (SUM((CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END))) * 100) as compliance FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveAccount` as a on a.id=n.accountId left outer join `productiveUser` as i on i.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id AND `primaryUnit`=6 AND `unitAssigned`=0 order by `primaryUnit` ASC LIMIT 0,1) left outer Join `productiveUser` as u on u.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id AND `primaryUnit`=7 order by `primaryUnit` ASC LIMIT 0,1) left join `productiveUser` as z on z.id={$userId} left join `productiveUser` as e on e.id=n.userId left join `productiveShifts` as s on s.id=n.shift where d.active>0 AND d.prodMeasure !=2 AND d.prodMeasure !=3 AND n.dayDate>=z.lastStartDate AND n.dayDate <=z.lastEndDate and n.accountId={$accountId} $shiftAdd group by n.accountId, n.deptId, n.userId ORDER BY varAvg DESC");
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',9);
$pdf->AddPage('P');
$pdf->ImprovedTable($data4);

$filename=substr($data4[0]['accountName'],0,4) . 'uservar' . $data4[0]['filedate'] . '.pdf';
$filelocation="/home/prnadmin/public_html/dailyreports/" . substr($data4[0]['accountName'],0,5) . $data4[0]['deptId'] . 'uservar' . $data4[0]['filedate'] . ".pdf";
$pdf->Output($filename,'D');

//$filename="/home/prnadmin/public_html/dailyreports/Variance". $data4[0]['filedate'] . ".pdf";
//$filelocation="/home/prnadmin/public_html/dailyreports/Variance" . $data4[0]['filedate'] . ".pdf";
//$pdf->Output($filename,'D');


exit();
}

function allCrash(){
$userId = Config::get('account')['id'];
$accountId = Config::get('account')['accountId'];
$enterpriseId = Config::get('account')['enterpriseId'];
$role = Config::get('account')['role'];

require_once '.././inc/FPDF/fpdf3.php';

class PDF_MC_Table extends FPDF{

function CheckPageBreak($h){
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation);
}


// Page footer
function Footer(){
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
}

function Header(){
$userId = Config::get('account')['id'];
$accountId = Config::get('account')['accountId'];
$enterpriseId = Config::get('account')['enterpriseId'];
$role = Config::get('account')['role'];
$deptNum = (isset($_REQUEST['r'])) ? $_REQUEST['r'] : 0;
//$deptNum = 0;

if(intval($deptNum)>0){
	//$newdata = Config::get('db') -> get_results("select n.deptId, n.dayDate, a.name as accountName,  DATE_FORMAT(z.lastStartDate,'%m/%d/%y') AS startDay, DATE_FORMAT(z.lastEndDate,'%m/%d/%y') AS endDay, a.displayProd, DATE_FORMAT(CURDATE(),'%m/%d/%y') AS reportDate, (SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END) / SUM(CASE WHEN n.userId>=0 THEN 1 ELSE 0 END) * 100) as reporting, SUM(CASE WHEN n.userId>0 THEN n.nvariance ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END) as varAvg, SUM(CASE WHEN n.userId>0 THEN n.gvariance ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END) as varGrid, SUM(CASE WHEN n.userId>0 and n.atotal>0 and n.shift=d.shift THEN n.atotal ELSE 0 END) / SUM(CASE WHEN n.userId>0 and n.atotal>0 and n.shift=d.shift THEN 1 ELSE 0 END) as censusShift, SUM(CASE WHEN n.userId>0 THEN n.atotal ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END) as censusAvg, SUM(CASE WHEN n.userId>0 THEN n.hrsVariance ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END) as hrsvarAvg, a.displayProd, a.productivityPosNeg, n.actualWHP, a.id as accountId, a.name as accountName, d.dept as deptName, d.prodMeasure, DATE_FORMAT(CURDATE(),'%m%d%y') as filedate, DATE_FORMAT(n.dayDate,'%m/%d/%y') as newDate, u.last_name as Director, i.last_name as Manager, a.id as accountId, ROUND(SUM((CASE WHEN n.actualWHPimport>0 and n.activeRecord>0 then n.hrsVarianceActual ELSE 0 END) + (CASE WHEN n.actualWHPimport=0.000 and n.activeRecord>0 then n.hrsVariance ELSE 0 END)),1) as hrsVarianceTotal, SUM((CASE WHEN n.userId>0 and n.activeRecord>0 THEN 1 ELSE 0 END)) as report, SUM((CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END)) as totalreports, ((SUM(CASE WHEN (n.userId>0 and n.activeRecord>0) THEN 1 ELSE 0 END) + (CASE WHEN n.userId=0 and n.activeRecord>0 and n.planSubmitted=1 THEN 1 ELSE 0 END)) / (SUM((CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END))) * 100) as compliance FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveAccount` as a on a.id=n.accountId left outer join `productiveUser` as i on i.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id AND `primaryUnit`=6 AND `unitAssigned`=0 order by `primaryUnit` ASC LIMIT 0,1) left outer Join `productiveUser` as u on u.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id AND `primaryUnit`=7 order by `primaryUnit` ASC LIMIT 0,1) left join `productiveUser` as z on z.id={$userId} where d.active>0 AND d.active<3 AND n.deptId={$deptNum} AND n.dayDate>=z.lastStartDate AND n.dayDate <=z.lastEndDate and n.accountId={$accountId} group by n.accountId, n.deptId ORDER BY d.dept ASC");
	$newdata = Config::get('db') -> get_results("SELECT c.*, d.dept, l.logType, DATE_FORMAT(CURDATE(),'%m%d%y') as filedate, IFNULL(z.last_name,'') as manager, DATE_FORMAT(r.lastStartDate,'%m/%d/%y') AS startDay, DATE_FORMAT(r.lastEndDate,'%m/%d/%y') AS endDay, l.title, a.name as accountName, DATE_FORMAT(s.shiftTime, '%H:%i') as shiftTime, s.shift as shiftName, DATE_FORMAT(CURDATE(), '%Y-%m-%d') as currentDate, DATE_FORMAT(c.dayDate, '%m/%d/%y') as crashDate, (case when c.submittedDateTime='0000-00-00 00:00:00' then '' else DATE_FORMAT(c.submittedDatetime, '%m-%d-%y %H:%i') end) as nDate, d.dept, IFNULL(u.first_name,'') as first_name, IFNULL(u.last_name,'') as last_name, IFNULL(u.practiceId,'0') as practiceId FROM
            `productivecrashLog` c
        LEFT JOIN 
			`productivelogData` as l on l.id = c.logId
		LEFT JOIN 
			`ProductiveDept` as d on d.id = c.deptId
		LEFT JOIN 
			`productiveDeptXref` as x on x.deptId = c.deptId AND x.primaryUnit=6
		LEFT JOIN 
			`productiveAccount` as a on a.id = d.accountId
		LEFT JOIN 
			`productiveShifts` as s on s.id = c.shift
		LEFT JOIN
        	`productiveUser` as r on r.id =$userId
		LEFT JOIN
        	`productiveUser` as z on z.id = x.userId
		LEFT OUTER JOIN
        	`productiveUser` as u on u.id = c.userId
        WHERE
            l.logType=1 AND c.dayDate >= r.lastStartDate AND c.dayDate <= r.lastEndDate AND l.active>0 AND d.id={$deptNum} AND d.active>0 AND d.active<4 AND d.accountId=$accountId		
		ORDER BY d.dept ASC, c.dayDate DESC, c.shift DESC LIMIT 0,1");
	$deptName = stripslashes($newdata[0]['dept']);
	}else{
	//$newdata = Config::get('db') -> get_results("select a.name as accountName, DATE_FORMAT(u.lastStartDate,'%m/%d/%y') AS startDay, DATE_FORMAT(u.lastEndDate,'%m/%d/%y') AS endDay, a.displayProd, DATE_FORMAT(CURDATE(),'%m/%d/%y') AS reportDate FROM `productiveAccount` a LEFT JOIN `productiveUser` as u on u.id={$userId} where a.id={$accountId} group by a.id");
	$newdata = Config::get('db') -> get_results("SELECT c.*, l.logType, DATE_FORMAT(CURDATE(),'%m%d%y') as filedate, IFNULL(z.last_name,'') as manager, DATE_FORMAT(r.lastStartDate,'%m/%d/%y') AS startDay, DATE_FORMAT(r.lastEndDate,'%m/%d/%y') AS endDay, l.title, a.name as accountName, DATE_FORMAT(s.shiftTime, '%H:%i') as shiftTime, s.shift as shiftName, DATE_FORMAT(CURDATE(), '%Y-%m-%d') as currentDate, DATE_FORMAT(c.dayDate, '%m/%d/%y') as crashDate, (case when c.submittedDateTime='0000-00-00 00:00:00' then '' else DATE_FORMAT(c.submittedDatetime, '%m-%d-%y %H:%i') end) as nDate, d.dept, IFNULL(u.first_name,'') as first_name, IFNULL(u.last_name,'') as last_name, IFNULL(u.practiceId,'0') as practiceId FROM
            `productivecrashLog` c
        LEFT JOIN 
			`productivelogData` as l on l.id = c.logId
		LEFT JOIN 
			`ProductiveDept` as d on d.id = c.deptId
		LEFT JOIN 
			`productiveDeptXref` as x on x.deptId = c.deptId AND x.primaryUnit=6
		LEFT JOIN 
			`productiveAccount` as a on a.id = d.accountId
		LEFT JOIN 
			`productiveShifts` as s on s.id = c.shift
		LEFT JOIN
        	`productiveUser` as r on r.id =$userId
		LEFT JOIN
        	`productiveUser` as z on z.id = x.userId
		LEFT OUTER JOIN
        	`productiveUser` as u on u.id = c.userId
        WHERE
            l.logType=1 AND c.dayDate >= r.lastStartDate AND c.dayDate <= r.lastEndDate AND l.active>0 AND d.unitCategory !=4 AND d.active>0 AND d.active<4 AND d.accountId=$accountId		
		ORDER BY d.dept ASC, c.dayDate DESC, c.shift DESC LIMIT 0,1");
	$deptName='';
	}
	$header = array('Unit', 'Task', 'Leader', 'Date (Due)', 'Submitted By', 'Date / Time');
    $this->SetFont('Arial','B',14);
	// Move to the right
		// Move to the right
    $this->Cell(80);
    // Title
    $this->Cell(30,10,$newdata[0]['accountName'],0,0,'C');
    // Line break
    $this->Ln(6);
    $this->Cell(80);
	$this->SetFont('Arial','B',12);
	$this->Cell(30,10,'Crash Cart Summary',0,0,'C');
	$this->Ln(6);
    $this->Cell(80);
	$this->SetFont('Arial','B',12);
	$this->Cell(30,10,$deptName,0,0,'C');
	// Line break
    $this->Ln(6);
	// Move to the right
    $this->Cell(80);
	$this->SetFont('Arial','B',10);
	$this->Cell(30,10,$newdata[0]['startDay'] . ' - ' . $newdata[0]['endDay'],0,0,'C');
	//$this->Cell(30,10,'Date: ' . $newdata[0]['reportDate'],0,0,'C');
	//$this->Ln(6);
	//$this->Cell(80);
	//$this->SetFont('Arial','B',10);
    
	// Line break
    $this->Ln(15);
	// Column widths
    $w = array(40, 55, 25, 20, 28, 28);
    // Header
			for($i=0;$i<count($header);$i++)
			    $this->Cell($w[$i],7,$header[$i],1,0,'C');
			$this->Ln();
    // Data
	/*
	for($i=0;$i<count($header);$i++){
		$x=$this->GetX();
        $y=$this->GetY();
		//if(strlen($header2[$i])>12){
		$this->SetFont('Arial','B',7);	
		//}else{
		//$this->SetFont('Arial','B',8);	
		//}
        $this->Rect($x,$y,$w[$i],15);
		$this->MultiCell($w[$i],5,$header[$i],0,'C');
		$this->SetXY($x+$w[$i],$y);
        //$this->Cell($w[$i],7,$header3[$i],1,0,'C');
	}
	$i=$i-1;
	$this->SetXY($x+$w[$i],$y+5);
    $this->Ln();
	$this->Ln();
	*/
    
}

function ImprovedTable($data4){
    $w = array(40, 55, 25, 20, 28, 28);
    foreach($data4 as $row){
		$dateCompleted = $row['crashDate'];
		$shiftName = $row['shiftTime'];
		$dateCompleted . " (" . $shiftName. ")";
		$this->SetTextColor(0,0,0);
        $this->Cell($w[0],6,$row['deptName'],'LRB');
		$this->Cell($w[1],6,$row['title'],'LRB');
		$this->Cell($w[2],6,$row['manager'],'LRB');
	    $this->Cell($w[3],6,$dateCompleted,'LRB',0,'C');
		$this->Cell($w[4],6,$row['submittedBy'],'LRB',0,'C');
		$this->Cell($w[5],6,$row['nDate'],'LRB',1,'C');
        
    }

}

}
	
$pdf = new PDF_MC_Table();
$userId = Config::get('account')['id'];
$accountId = Config::get('account')['accountId'];
$enterpriseId = Config::get('account')['enterpriseId'];
$role = Config::get('account')['role'];
$deptId = (isset($_REQUEST['r'])) ? $_REQUEST['r'] : 0;
$shiftId = (isset($_REQUEST['s'])) ? $_REQUEST['s'] : 0;

if(intval($shiftId)>0){
	$shiftAdd = "AND n.deptID={$shiftId} ";
}else{
	$shiftAdd = '';
}

if(intval($deptId)>0){
$data4 = Config::get('db') -> get_results("SELECT c.*, IFNULL(c.submittedBy,'') as submittedBy, (case when c.submittedDateTime='0000-00-00 00:00:00' then '' else DATE_FORMAT(c.submittedDatetime, '%m-%d-%y %H:%i') end) as nDate, l.logType, DATE_FORMAT(CURDATE(),'%m%d%y') as filedate, IFNULL(z.last_name,'') as manager, DATE_FORMAT(r.lastStartDate,'%m/%d/%y') AS startDay, DATE_FORMAT(r.lastEndDate,'%m/%d/%y') AS endDay, l.title, a.name as accountName, l.active, d.active, DATE_FORMAT(s.shiftTime, '%H:%i') as shiftTime, s.shift as shiftName, DATE_FORMAT(CURDATE(), '%Y-%m-%d') as currentDate, DATE_FORMAT(c.dayDate, '%m/%d/%y') as crashDate, (case when c.submittedDateTime='0000-00-00 00:00:00' then '' else c.submittedDateTime end) as newDate, d.dept as deptName, IFNULL(u.first_name,'') as first_name, IFNULL(u.last_name,'') as last_name, IFNULL(u.practiceId,'0') as practiceId FROM
            `productivecrashLog` c
        LEFT JOIN 
			`productivelogData` as l on l.id = c.logId
		LEFT JOIN 
			`ProductiveDept` as d on d.id = c.deptId
		LEFT JOIN 
			`productiveDeptXref` as x on x.deptId = c.deptId AND x.primaryUnit=6
		LEFT JOIN 
			`productiveAccount` as a on a.id = d.accountId
		LEFT JOIN 
			`productiveShifts` as s on s.id = c.shift
		LEFT JOIN
        	`productiveUser` as r on r.id =$userId
		LEFT JOIN
        	`productiveUser` as z on z.id = x.userId
		LEFT OUTER JOIN
        	`productiveUser` as u on u.id = c.userId
        WHERE
            l.logType=1 AND c.dayDate >= r.lastStartDate AND c.dayDate <= r.lastEndDate AND l.active>0 AND d.id={$deptId} AND d.active>0 AND d.active<4 AND d.accountId=$accountId		
		ORDER BY d.dept ASC, c.dayDate DESC, c.shift DESC");
}else{
$data4 = Config::get('db') -> get_results("SELECT c.*, IFNULL(c.submittedBy,'') as submittedBy, (case when c.submittedDateTime='0000-00-00 00:00:00' then '' else DATE_FORMAT(c.submittedDatetime, '%m-%d-%y %H:%i') end) as nDate, l.logType, DATE_FORMAT(CURDATE(),'%m%d%y') as filedate, IFNULL(z.last_name,'') as manager, DATE_FORMAT(r.lastStartDate,'%m/%d/%y') AS startDay, DATE_FORMAT(r.lastEndDate,'%m/%d/%y') AS endDay, l.title, a.name as accountName, l.active, d.active, DATE_FORMAT(s.shiftTime, '%H:%i') as shiftTime, s.shift as shiftName, DATE_FORMAT(CURDATE(), '%Y-%m-%d') as currentDate, DATE_FORMAT(c.dayDate, '%m/%d/%y') as crashDate, (case when c.submittedDateTime='0000-00-00 00:00:00' then '' else c.submittedDateTime end) as newDate, d.dept as deptName, IFNULL(u.first_name,'') as first_name, IFNULL(u.last_name,'') as last_name, IFNULL(u.practiceId,'0') as practiceId FROM
            `productivecrashLog` c
        LEFT JOIN 
			`productivelogData` as l on l.id = c.logId
		LEFT JOIN 
			`ProductiveDept` as d on d.id = c.deptId
		LEFT JOIN 
			`productiveDeptXref` as x on x.deptId = c.deptId AND x.primaryUnit=6
		LEFT JOIN 
			`productiveAccount` as a on a.id = d.accountId
		LEFT JOIN 
			`productiveShifts` as s on s.id = c.shift
		LEFT JOIN
        	`productiveUser` as r on r.id =$userId
		LEFT JOIN
        	`productiveUser` as z on z.id = x.userId
		LEFT OUTER JOIN
        	`productiveUser` as u on u.id = c.userId
        WHERE
            l.logType=1 AND c.dayDate >= r.lastStartDate AND c.dayDate <= r.lastEndDate AND l.active>0 AND d.unitCategory !=4 AND d.active>0 AND d.active<4 AND d.accountId=$accountId		
		ORDER BY d.dept ASC, c.dayDate DESC, c.shift DESC");
}
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',9);
$pdf->AddPage('P');
$pdf->ImprovedTable($data4);

$filename=substr($data4[0]['deptName'],0,4) . 'crashcart' . $data4[0]['filedate'] . '.pdf';
$filelocation="/home/prnadmin/public_html/dailyreports/" . substr($data4[0]['deptName'],0,5) . $data4[0]['deptId'] . 'crashcart' . $data4[0]['filedate'] . ".pdf";
$pdf->Output($filename,'D');

//$filename="/home/prnadmin/public_html/dailyreports/Variance". $data4[0]['filedate'] . ".pdf";
//$filelocation="/home/prnadmin/public_html/dailyreports/Variance" . $data4[0]['filedate'] . ".pdf";
//$pdf->Output($filename,'D');

exit();
}

function allShift(){
$userId = Config::get('account')['id'];
$accountId = Config::get('account')['accountId'];
$enterpriseId = Config::get('account')['enterpriseId'];
$role = Config::get('account')['role'];

require_once '.././inc/FPDF/fpdf3.php';

class PDF_MC_Table extends FPDF{

function CheckPageBreak($h){
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation);
}


// Page footer
function Footer(){
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
}

function Header(){
$userId = Config::get('account')['id'];
$accountId = Config::get('account')['accountId'];
$enterpriseId = Config::get('account')['enterpriseId'];
$role = Config::get('account')['role'];
$deptNum = (isset($_REQUEST['s'])) ? $_REQUEST['s'] : 0;

if(intval($deptNum)>0){
	//$newdata = Config::get('db') -> get_results("select a.name as accountName, d.dept as deptName, d.prodMeasure, DATE_FORMAT(u.lastStartDate,'%m/%d/%y') AS startDay, DATE_FORMAT(u.lastEndDate,'%m/%d/%y') AS endDay, a.displayProd, DATE_FORMAT(CURDATE(),'%m/%d/%y') AS reportDate FROM `productiveAccount` a LEFT JOIN `productiveUser` as u on u.id={$userId} left join `ProductiveDept` as d on d.id={$deptNum} where a.id={$accountId} group by a.id");	
	$newdata = Config::get('db') -> get_results("select n.deptId, n.dayDate, a.name as accountName,  DATE_FORMAT(z.lastStartDate,'%m/%d/%y') AS startDay, DATE_FORMAT(z.lastEndDate,'%m/%d/%y') AS endDay, a.displayProd, DATE_FORMAT(CURDATE(),'%m/%d/%y') AS reportDate, (SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END) / SUM(CASE WHEN n.userId>=0 THEN 1 ELSE 0 END) * 100) as reporting, SUM(CASE WHEN n.userId>0 THEN n.nvariance ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END) as varAvg, SUM(CASE WHEN n.userId>0 THEN n.gvariance ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END) as varGrid, SUM(CASE WHEN n.userId>0 and n.atotal>0 and n.shift=d.shift THEN n.atotal ELSE 0 END) / SUM(CASE WHEN n.userId>0 and n.atotal>0 and n.shift=d.shift THEN 1 ELSE 0 END) as censusShift, SUM(CASE WHEN n.userId>0 THEN n.atotal ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END) as censusAvg, SUM(CASE WHEN n.userId>0 THEN n.hrsVariance ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END) as hrsvarAvg, a.displayProd, a.productivityPosNeg, n.actualWHP, a.id as accountId, a.name as accountName, d.dept as deptName, d.prodMeasure, DATE_FORMAT(CURDATE(),'%m%d%y') as filedate, DATE_FORMAT(n.dayDate,'%m/%d/%y') as newDate, u.last_name as Director, i.last_name as Manager, a.id as accountId, ROUND(SUM((CASE WHEN n.actualWHPimport>0 and n.activeRecord>0 then n.hrsVarianceActual ELSE 0 END) + (CASE WHEN n.actualWHPimport=0.000 and n.activeRecord>0 then n.hrsVariance ELSE 0 END)),1) as hrsVarianceTotal, SUM((CASE WHEN n.userId>0 and n.activeRecord>0 THEN 1 ELSE 0 END)) as report, SUM((CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END)) as totalreports, ((SUM(CASE WHEN (n.userId>0 and n.activeRecord>0) THEN 1 ELSE 0 END) + (CASE WHEN n.userId=0 and n.activeRecord>0 and n.planSubmitted=1 THEN 1 ELSE 0 END)) / (SUM((CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END))) * 100) as compliance FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveAccount` as a on a.id=n.accountId left outer join `productiveUser` as i on i.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id AND `primaryUnit`=6 AND `unitAssigned`=0 order by `primaryUnit` ASC LIMIT 0,1) left outer Join `productiveUser` as u on u.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id AND `primaryUnit`=7 order by `primaryUnit` ASC LIMIT 0,1) left join `productiveUser` as z on z.id={$userId} where d.active>0 AND n.deptId={$deptNum} AND n.dayDate>=z.lastStartDate AND n.dayDate <=z.lastEndDate and n.accountId={$accountId} group by n.accountId, n.deptId ORDER BY d.dept ASC");
	$deptName = $newdata[0]['deptName'];
	$prodMeasure = $newdata[0]['prodMeasure'];
	}else{
	//$newdata = Config::get('db') -> get_results("select a.name as accountName, DATE_FORMAT(u.lastStartDate,'%m/%d/%y') AS startDay, DATE_FORMAT(u.lastEndDate,'%m/%d/%y') AS endDay, a.displayProd, DATE_FORMAT(CURDATE(),'%m/%d/%y') AS reportDate FROM `productiveAccount` a LEFT JOIN `productiveUser` as u on u.id={$userId} where a.id={$accountId} group by a.id");
	$newdata = Config::get('db') -> get_results("select n.deptId, n.dayDate, a.name as accountName,  DATE_FORMAT(z.lastStartDate,'%m/%d/%y') AS startDay, DATE_FORMAT(z.lastEndDate,'%m/%d/%y') AS endDay, a.displayProd, DATE_FORMAT(CURDATE(),'%m/%d/%y') AS reportDate, (SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END) / SUM(CASE WHEN n.userId>=0 THEN 1 ELSE 0 END) * 100) as reporting, SUM(CASE WHEN n.userId>0 THEN n.nvariance ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END) as varAvg, SUM(CASE WHEN n.userId>0 THEN n.gvariance ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END) as varGrid, SUM(CASE WHEN n.userId>0 and n.atotal>0 and n.shift=d.shift THEN n.atotal ELSE 0 END) / SUM(CASE WHEN n.userId>0 and n.atotal>0 and n.shift=d.shift THEN 1 ELSE 0 END) as censusShift, SUM(CASE WHEN n.userId>0 THEN n.atotal ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END) as censusAvg, SUM(CASE WHEN n.userId>0 THEN n.hrsVariance ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END) as hrsvarAvg, a.displayProd, a.productivityPosNeg, n.actualWHP, a.id as accountId, a.name as accountName, d.dept as deptName, d.prodMeasure, DATE_FORMAT(CURDATE(),'%m%d%y') as filedate, DATE_FORMAT(n.dayDate,'%m/%d/%y') as newDate, u.last_name as Director, i.last_name as Manager, a.id as accountId, ROUND(SUM((CASE WHEN n.actualWHPimport>0 and n.activeRecord>0 then n.hrsVarianceActual ELSE 0 END) + (CASE WHEN n.actualWHPimport=0.000 and n.activeRecord>0 then n.hrsVariance ELSE 0 END)),1) as hrsVarianceTotal, SUM((CASE WHEN n.userId>0 and n.activeRecord>0 THEN 1 ELSE 0 END)) as report, SUM((CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END)) as totalreports, ((SUM(CASE WHEN (n.userId>0 and n.activeRecord>0) THEN 1 ELSE 0 END) + (CASE WHEN n.userId=0 and n.activeRecord>0 and n.planSubmitted=1 THEN 1 ELSE 0 END)) / (SUM((CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END))) * 100) as compliance FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveAccount` as a on a.id=n.accountId left outer join `productiveUser` as i on i.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id AND `primaryUnit`=6 AND `unitAssigned`=0 order by `primaryUnit` ASC LIMIT 0,1) left outer Join `productiveUser` as u on u.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id AND `primaryUnit`=7 order by `primaryUnit` ASC LIMIT 0,1) left join `productiveUser` as z on z.id={$userId} where n.dayDate>=z.lastStartDate AND n.dayDate <=z.lastEndDate and n.accountId={$accountId} group by n.accountId ORDER BY a.name ASC");
	$deptName='';
	$prodMeasure=0;
	}
	$header = array('Shift', 'Leader', 'Reports', 'Avg RN Var', 'Avg Grid Var');
    $this->SetFont('Arial','B',14);
	// Move to the right
		// Move to the right
    $this->Cell(80);
    // Title
    $this->Cell(30,10,$newdata[0]['accountName'],0,0,'C');
    // Line break
    $this->Ln(6);
    $this->Cell(80);
	$this->SetFont('Arial','B',12);
	$this->Cell(30,10,'Shift Variance Summary',0,0,'C');
	// Line break
    $this->Ln(6);
	$this->Cell(80);
	$this->Cell(30,10,$deptName,0,0,'C');
	// Line break
    $this->Ln(6);
	// Move to the right
    $this->Cell(80);
	$this->SetFont('Arial','B',10);
	$this->Cell(30,10,$newdata[0]['startDay'] . ' - ' . $newdata[0]['endDay'],0,0,'C');
	//$this->Cell(30,10,'Date: ' . $newdata[0]['reportDate'],0,0,'C');
	//if(intval($prodMeasure)==2 || intval($prodMeasure)==3 || intval($prodMeasure)==0){	
	$this->Ln(6);
	$this->Cell(80);
	$this->SetFont('Arial','B',10);
    //}else{
	//$this->Ln(10);
	//$this->Cell(80);
	//$this->SetFont('Arial','B',10);
	//$this->Cell(30,10, 'Reporting: ' . ROUND(floatval($newdata[0]['reporting']),1) . '%    Avg Midnight Census: ' . ROUND(floatval($newdata[0]['censusShift']),1) . '    Avg Census: ' . ROUND(floatval($newdata[0]['censusAvg']),1) .'    Avg RN Variance: ' . ROUND(floatval($newdata[0]['varAvg']),2) . '    Avg Grid Variance: ' . ROUND(floatval($newdata[0]['varGrid']),2),0,0,'C');	
	//}
	// Line break
    $this->Ln(15);
	// Column widths
    $w = array(55, 35, 25, 30, 30);
    // Header
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C');
    $this->Ln();
    // Data
    
}

function ImprovedTable($data4,$rpt){
    $w = array(55, 35, 25, 30, 30);
	if($rpt==3){
		$s = ' (NIGHTS)';
	}else if($rpt==2){
		$s = ' (DAYS)';
	}else{
		$s = ' (ALL)';
	}
    foreach($data4 as $row){
		$this->SetTextColor(0,0,0);
        $this->Cell($w[0],6,$row['deptName'] . $s,'LRB');
		$this->Cell($w[1],6,$row['Manager'],'LRB');
		$this->Cell($w[2],6,$row['report'] . '/' . $row['totalreports'],'LRB',0,'C');
		
		if(intval($row['varAvg'])==99){
			$varavg = '';
		}else{
			$varavg = ROUND($row['varAvg'],1);
		}
		if(intval($row['varGrid'])==99){
			$vargrid = '';
		}else{
			$vargrid = ROUND($row['varGrid'],1);
		}
		$this->Cell($w[3],6,$varavg,'LRB',0,'C');
		$this->Cell($w[4],6,$vargrid,'LRB',1,'C');
        
    }

}

}
	
$pdf = new PDF_MC_Table();
$userId = Config::get('account')['id'];
$accountId = Config::get('account')['accountId'];
$enterpriseId = Config::get('account')['enterpriseId'];
$role = Config::get('account')['role'];
$shiftId = (isset($_REQUEST['s'])) ? $_REQUEST['s'] : 0;

if(intval($shiftId)>0){
	$shiftAdd = "AND n.deptID={$shiftId} ";
}else{
	$shiftAdd = '';
}

$data4 = Config::get('db') -> get_results("select n.deptId, s.shift as shiftName, n.shift, IFNULL((SUM(CASE WHEN n.userId>0 THEN n.nvariance ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END)),99) as varAvg, IFNULL((SUM(CASE WHEN n.userId>0 THEN n.gvariance ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END)),99) as varGrid, a.displayProd, a.productivityPosNeg, n.actualWHP, a.id as accountId, a.name as accountName, d.dept as deptName, d.prodMeasure, DATE_FORMAT(CURDATE(),'%m%d%y') as filedate, u.last_name as Director, i.last_name as Manager, a.id as accountId, ROUND(SUM((CASE WHEN n.actualWHPimport>0 and n.activeRecord>0 then n.hrsVarianceActual ELSE 0 END) + (CASE WHEN n.actualWHPimport=0.000 and n.activeRecord>0 then n.hrsVariance ELSE 0 END)),1) as hrsVarianceTotal, SUM((CASE WHEN n.userId>0 and n.activeRecord>0 THEN 1 ELSE 0 END)) as report, SUM((CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END)) as totalreports, ((SUM(CASE WHEN (n.userId>0 and n.activeRecord>0) THEN 1 ELSE 0 END) + (CASE WHEN n.userId=0 and n.activeRecord>0 and n.planSubmitted=1 THEN 1 ELSE 0 END)) / (SUM((CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END))) * 100) as compliance FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveAccount` as a on a.id=n.accountId left outer join `productiveUser` as i on i.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id AND `primaryUnit`=6 AND `unitAssigned`=0 order by `primaryUnit` ASC LIMIT 0,1) left outer Join `productiveUser` as u on u.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id AND `primaryUnit`=7 order by `primaryUnit` ASC LIMIT 0,1) left join `productiveUser` as z on z.id={$userId} left join `productiveShifts` as s on s.id=n.shift where d.active>0 AND d.prodMeasure !=2 AND n.dayDate>=z.lastStartDate AND n.dayDate <=z.lastEndDate and n.accountId={$accountId} $shiftAdd group by n.accountId, n.deptId ORDER BY d.dept ASC");

$data5 = Config::get('db') -> get_results("select n.deptId, s.shift as shiftName, n.shift, IFNULL((SUM(CASE WHEN n.userId>0 THEN n.nvariance ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END)),99) as varAvg, IFNULL((SUM(CASE WHEN n.userId>0 THEN n.gvariance ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END)),99) as varGrid, a.displayProd, a.productivityPosNeg, n.actualWHP, a.id as accountId, a.name as accountName, d.dept as deptName, d.prodMeasure, DATE_FORMAT(CURDATE(),'%m%d%y') as filedate, u.last_name as Director, i.last_name as Manager, a.id as accountId, ROUND(SUM((CASE WHEN n.actualWHPimport>0 and n.activeRecord>0 then n.hrsVarianceActual ELSE 0 END) + (CASE WHEN n.actualWHPimport=0.000 and n.activeRecord>0 then n.hrsVariance ELSE 0 END)),1) as hrsVarianceTotal, SUM((CASE WHEN n.userId>0 and n.activeRecord>0 THEN 1 ELSE 0 END)) as report, SUM((CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END)) as totalreports, ((SUM(CASE WHEN (n.userId>0 and n.activeRecord>0) THEN 1 ELSE 0 END) + (CASE WHEN n.userId=0 and n.activeRecord>0 and n.planSubmitted=1 THEN 1 ELSE 0 END)) / (SUM((CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END))) * 100) as compliance FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveAccount` as a on a.id=n.accountId left outer join `productiveUser` as i on i.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id AND `primaryUnit`=6 AND `unitAssigned`=0 order by `primaryUnit` ASC LIMIT 0,1) left outer Join `productiveUser` as u on u.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id AND `primaryUnit`=7 order by `primaryUnit` ASC LIMIT 0,1) left join `productiveUser` as z on z.id={$userId} left join `productiveShifts` as s on s.id=n.shift where d.active>0 AND n.shift<=38 AND n.shift>14 AND d.prodMeasure !=2 AND n.dayDate>=z.lastStartDate AND n.dayDate <=z.lastEndDate and n.accountId={$accountId} $shiftAdd group by n.accountId, n.deptId ORDER BY d.dept ASC");

$data6 = Config::get('db') -> get_results("select n.deptId, s.shift as shiftName, n.shift, IFNULL((SUM(CASE WHEN n.userId>0 THEN n.nvariance ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END)),99) as varAvg, IFNULL((SUM(CASE WHEN n.userId>0 THEN n.gvariance ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END)),99) as varGrid, a.displayProd, a.productivityPosNeg, n.actualWHP, a.id as accountId, a.name as accountName, d.dept as deptName, d.prodMeasure, DATE_FORMAT(CURDATE(),'%m%d%y') as filedate, u.last_name as Director, i.last_name as Manager, a.id as accountId, ROUND(SUM((CASE WHEN n.actualWHPimport>0 and n.activeRecord>0 then n.hrsVarianceActual ELSE 0 END) + (CASE WHEN n.actualWHPimport=0.000 and n.activeRecord>0 then n.hrsVariance ELSE 0 END)),1) as hrsVarianceTotal, SUM((CASE WHEN n.userId>0 and n.activeRecord>0 THEN 1 ELSE 0 END)) as report, SUM((CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END)) as totalreports, ((SUM(CASE WHEN (n.userId>0 and n.activeRecord>0) THEN 1 ELSE 0 END) + (CASE WHEN n.userId=0 and n.activeRecord>0 and n.planSubmitted=1 THEN 1 ELSE 0 END)) / (SUM((CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END))) * 100) as compliance FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveAccount` as a on a.id=n.accountId left outer join `productiveUser` as i on i.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id AND `primaryUnit`=6 AND `unitAssigned`=0 order by `primaryUnit` ASC LIMIT 0,1) left outer Join `productiveUser` as u on u.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id AND `primaryUnit`=7 order by `primaryUnit` ASC LIMIT 0,1) left join `productiveUser` as z on z.id={$userId} left join `productiveShifts` as s on s.id=n.shift where d.active>0 AND (n.shift>38 OR n.shift<=14) AND d.prodMeasure !=2 AND n.dayDate>=z.lastStartDate AND n.dayDate <=z.lastEndDate and n.accountId={$accountId} $shiftAdd group by n.accountId, n.deptId ORDER BY d.dept ASC");

if($data4){
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',9);
$pdf->AddPage('P');
$pdf->ImprovedTable($data4,1);
$pdf->ImprovedTable($data5,2);
$pdf->ImprovedTable($data6,3);

$filename=substr($data4[0]['deptName'],0,4) . 'shiftvar' . $data4[0]['filedate'] . '.pdf';
$filelocation="/home/prnadmin/public_html/dailyreports/" . substr($data4[0]['deptName'],0,5) . $data4[0]['deptId'] . 'shiftvar' . $data4[0]['filedate'] . ".pdf";
$pdf->Output($filename,'D');

}
exit();
}


function allrprt(){
$userId = Config::get('account')['id'];
$accountId = Config::get('account')['accountId'];
$enterpriseId = Config::get('account')['enterpriseId'];
$role = Config::get('account')['role'];
$i=0;
require_once '.././inc/FPDF/fpdf2.php';

class PDF_MC_Table extends FPDF{

function CheckPageBreak($h){
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation);
}


// Page footer
function Footer(){
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
}

function Header4(){
$userId = Config::get('account')['id'];
$accountId = Config::get('account')['accountId'];
$enterpriseId = Config::get('account')['enterpriseId'];
$role = Config::get('account')['role'];
//$reportId = (isset($_REQUEST['r'])) ? $_REQUEST['r'] : 1;
//$i=$i+1;

$newdata = Config::get('db') -> get_results("select a.name as accountName, DATE_FORMAT(u.lastStartDate,'%m/%d/%y') AS startDay, DATE_FORMAT(u.lastEndDate,'%m/%d/%y') AS endDay, a.displayProd, DATE_FORMAT(CURDATE(),'%m/%d/%y') AS reportDate FROM `productiveAccount` a LEFT JOIN `productiveUser` as u on u.id={$userId} where a.id={$accountId} group by a.id");
	$header = array('Unit', 'Director', 'Manager', 'Reports', 'Compliance', 'RN Variance', 'Grid Variance');
    $this->SetFont('Arial','B',14);
	// Move to the right
		// Move to the right
    $this->Cell(80);
    // Title
    $this->Cell(30,10,$newdata[0]['accountName'],0,0,'C');
    // Line break
    $this->Ln(6);
    $this->Cell(80);
	$this->SetFont('Arial','B',12);
	$this->Cell(30,10,'Unit Variance Summary',0,0,'C');
	// Line break
    $this->Ln(6);
	// Move to the right
    $this->Cell(80);
	$this->SetFont('Arial','B',12);
	$this->Cell(30,10,$newdata[0]['startDay'] . ' - ' . $newdata[0]['endDay'],0,0,'C');
	//$this->Cell(30,10,'Date: ' . $newdata[0]['reportDate'],0,0,'C');
	// Line break
    $this->Ln(15);
	// Column widths
    $w = array(45, 22, 22, 20, 27, 27, 30);
    // Header
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C');
    $this->Ln();
    // Data
    
}

function ImprovedTable($data4){
    $w = array(45, 22, 22, 20, 27, 27, 30);
    foreach($data4 as $row){
		$this->SetTextColor(0,0,0);
        $this->Cell($w[0],6,$row['deptName'],'LRB');
        $this->Cell($w[1],6,$row['Director'],'LRB');
		$this->Cell($w[2],6,$row['Manager'],'LRB');
		$this->Cell($w[3],6,$row['report'],'LRB',0,'C');
	if(floatval($row['compliance'])>0){
		$compliance = ROUND($row['compliance']) . '%';
		$varAvg = ROUND($row['varAvg'],2);
		$gridAvg = ROUND($row['gridAvg'],2);
	}else{
		$compliance = 'No Data';
		$varAvg = '';
		$gridAvg = '';
	}
        $this->Cell($w[4],6,$compliance,'LRB',0,'C');
		$this->Cell($w[5],6,$varAvg,'LRB',0,'C');
		$this->Cell($w[6],6,$gridAvg,'LRB',1,'C');
        
    }

}

}
	
$pdf = new PDF_MC_Table();
$userId = Config::get('account')['id'];
$accountId = Config::get('account')['accountId'];
$enterpriseId = Config::get('account')['enterpriseId'];
$role = Config::get('account')['role'];

$data4 = Config::get('db') -> get_results("select n.deptId, IFNULL((SUM(CASE WHEN n.userId>0 THEN n.nvariance ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END)),0) as varAvg, IFNULL((SUM(CASE WHEN n.userId>0 THEN n.gvariance ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END)),0) as gridAvg, a.displayProd, a.productivityPosNeg, n.actualWHP, a.id as accountId, a.name as accountName, d.dept as deptName, d.prodMeasure, DATE_FORMAT(CURDATE(),'%m%d%y') as filedate, u.last_name as Director, i.last_name as Manager, a.id as accountId, ROUND(SUM((CASE WHEN n.actualWHPimport>0 and n.activeRecord>0 then n.hrsVarianceActual ELSE 0 END) + (CASE WHEN n.actualWHPimport=0.000 and n.activeRecord>0 then n.hrsVariance ELSE 0 END)),1) as hrsVarianceTotal, SUM((CASE WHEN n.userId>0 and n.activeRecord>0 THEN 1 ELSE 0 END)) as report, SUM((CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END)) as totalreports, ((SUM(CASE WHEN (n.userId>0 and n.activeRecord>0) THEN 1 ELSE 0 END) + (CASE WHEN n.userId=0 and n.activeRecord>0 and n.planSubmitted=1 THEN 1 ELSE 0 END)) / (SUM((CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END))) * 100) as compliance FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveAccount` as a on a.id=n.accountId left outer join `productiveUser` as i on i.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id AND `primaryUnit`=6 AND `unitAssigned`=0 order by `primaryUnit` ASC LIMIT 0,1) left outer Join `productiveUser` as u on u.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id AND `primaryUnit`=7 order by `primaryUnit` ASC LIMIT 0,1) left join `productiveUser` as z on z.id={$userId} where d.active>0 AND d.prodMeasure!=2 AND d.prodMeasure!=3 AND n.dayDate>=z.lastStartDate AND n.dayDate <=z.lastEndDate and n.accountId={$accountId} group by n.accountId, n.deptId ORDER BY varAvg DESC");
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',9);
$pdf->AddPage4('P');
$pdf->ImprovedTable($data4);
/*
$data5 = Config::get('db') -> get_results("select n.deptId, SUM(CASE WHEN n.userId>0 THEN n.ahrsVariance ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END) as varAvg, a.displayProd, a.productivityPosNeg, n.actualWHP, a.id as accountId, a.name as accountName, d.dept as deptName, d.prodMeasure, DATE_FORMAT(CURDATE(),'%m%d%y') as filedate, u.last_name as Director, i.last_name as Manager, a.id as accountId, ROUND(SUM((CASE WHEN n.actualWHPimport>0 and n.activeRecord>0 then n.hrsVarianceActual ELSE 0 END) + (CASE WHEN n.actualWHPimport=0.000 and n.activeRecord>0 then n.hrsVariance ELSE 0 END)),1) as hrsVarianceTotal, SUM((CASE WHEN n.userId>0 and n.activeRecord>0 THEN 1 ELSE 0 END)) as report, SUM((CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END)) as totalreports, ((SUM(CASE WHEN (n.userId>0 and n.activeRecord>0) THEN 1 ELSE 0 END) + (CASE WHEN n.userId=0 and n.activeRecord>0 and n.planSubmitted=1 THEN 1 ELSE 0 END)) / (SUM((CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END))) * 100) as compliance FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveAccount` as a on a.id=n.accountId left outer join `productiveUser` as i on i.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id AND `primaryUnit`=6 order by `primaryUnit` ASC LIMIT 0,1) left outer Join `productiveUser` as u on u.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id AND `primaryUnit`=7 order by `primaryUnit` ASC LIMIT 0,1) left join `productiveUser` as z on z.id={$userId} where d.active>0 AND (d.prodMeasure=2 OR d.prodMeasure=3) AND n.dayDate>=z.lastStartDate AND n.dayDate <=z.lastEndDate and n.accountId={$accountId} group by n.accountId, n.deptId ORDER BY varAvg DESC");
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',9);
$pdf->AddPage4('P');
$pdf->ImprovedTable($data5);
*/
$filename=substr($data4[0]['accountName'],0,4) . 'variance' . $data4[0]['filedate'] . '.pdf';
$filelocation="/home/prnadmin/public_html/dailyreports/" . substr($data4[0]['accountName'],0,5) . $data4[0]['deptId'] . 'variance' . $data4[0]['filedate'] . ".pdf";
$pdf->Output($filename,'D');

//$filename="/home/prnadmin/public_html/dailyreports/Variance". $data4[0]['filedate'] . ".pdf";
//$filelocation="/home/prnadmin/public_html/dailyreports/Variance" . $data4[0]['filedate'] . ".pdf";
//$pdf->Output($filename,'D');


exit();
}


function allUnits(){
$userId = Config::get('account')['id'];
$accountId = Config::get('account')['accountId'];
$enterpriseId = Config::get('account')['enterpriseId'];
$role = Config::get('account')['role'];

require_once '.././inc/FPDF/fpdf3.php';

class PDF_MC_Table extends FPDF{

function CheckPageBreak($h){
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation);
}


// Page footer
function Footer(){
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
}

function Header(){
$userId = Config::get('account')['id'];
$accountId = Config::get('account')['accountId'];
$enterpriseId = Config::get('account')['enterpriseId'];
$role = Config::get('account')['role'];
//$reportId = (isset($_REQUEST['r'])) ? $_REQUEST['r'] : 1;
$deptNum = (isset($_REQUEST['u'])) ? $_REQUEST['u'] : 0;

if(intval($deptNum)>0){
	//$newdata = Config::get('db') -> get_results("select a.name as accountName, d.dept as deptName, d.prodMeasure, DATE_FORMAT(u.lastStartDate,'%m/%d/%y') AS startDay, DATE_FORMAT(u.lastEndDate,'%m/%d/%y') AS endDay, a.displayProd, DATE_FORMAT(CURDATE(),'%m/%d/%y') AS reportDate FROM `productiveAccount` a LEFT JOIN `productiveUser` as u on u.id={$userId} left join `ProductiveDept` as d on d.id={$deptNum} where a.id={$accountId} group by a.id");	
	$newdata = Config::get('db') -> get_results("select n.deptId, n.dayDate, a.name as accountName,  DATE_FORMAT(z.lastStartDate,'%m/%d/%y') AS startDay, DATE_FORMAT(z.lastEndDate,'%m/%d/%y') AS endDay, a.displayProd, DATE_FORMAT(CURDATE(),'%m/%d/%y') AS reportDate, (SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END) / SUM(CASE WHEN n.userId>=0 THEN 1 ELSE 0 END) * 100) as reporting, SUM(CASE WHEN n.userId>0 THEN n.nvariance ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END) as varAvg, SUM(CASE WHEN n.userId>0 THEN n.gvariance ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END) as varGrid, SUM(CASE WHEN n.userId>0 and n.atotal>0 and n.shift=d.shift THEN n.atotal ELSE 0 END) / SUM(CASE WHEN n.userId>0 and n.atotal>0 and n.shift=d.shift THEN 1 ELSE 0 END) as censusShift, SUM(CASE WHEN n.userId>0 THEN n.atotal ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END) as censusAvg, SUM(CASE WHEN n.userId>0 THEN n.hrsVariance ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END) as hrsvarAvg, a.displayProd, a.productivityPosNeg, n.actualWHP, a.id as accountId, a.name as accountName, d.dept as deptName, d.prodMeasure, DATE_FORMAT(CURDATE(),'%m%d%y') as filedate, DATE_FORMAT(n.dayDate,'%m/%d/%y') as newDate, u.last_name as Director, i.last_name as Manager, a.id as accountId, ROUND(SUM((CASE WHEN n.actualWHPimport>0 and n.activeRecord>0 then n.hrsVarianceActual ELSE 0 END) + (CASE WHEN n.actualWHPimport=0.000 and n.activeRecord>0 then n.hrsVariance ELSE 0 END)),1) as hrsVarianceTotal, SUM((CASE WHEN n.userId>0 and n.activeRecord>0 THEN 1 ELSE 0 END)) as report, SUM((CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END)) as totalreports, ((SUM(CASE WHEN (n.userId>0 and n.activeRecord>0) THEN 1 ELSE 0 END) + (CASE WHEN n.userId=0 and n.activeRecord>0 and n.planSubmitted=1 THEN 1 ELSE 0 END)) / (SUM((CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END))) * 100) as compliance FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveAccount` as a on a.id=n.accountId left outer join `productiveUser` as i on i.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id AND `primaryUnit`=6 AND `unitAssigned`=0 order by `primaryUnit` ASC LIMIT 0,1) left outer Join `productiveUser` as u on u.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id AND `primaryUnit`=7 order by `primaryUnit` ASC LIMIT 0,1) left join `productiveUser` as z on z.id={$userId} where d.active>0 AND n.deptId={$deptNum} AND n.dayDate>=z.lastStartDate AND n.dayDate <=z.lastEndDate and n.accountId={$accountId} group by n.accountId, n.deptId ORDER BY d.dept ASC");
	$deptName = "-" . $newdata[0]['deptName'];
	$prodMeasure = $newdata[0]['prodMeasure'];
	}else{
	//$newdata = Config::get('db') -> get_results("select a.name as accountName,  DATE_FORMAT(u.lastStartDate,'%m/%d/%y') AS startDay, DATE_FORMAT(u.lastEndDate,'%m/%d/%y') AS endDay, a.displayProd, DATE_FORMAT(CURDATE(),'%m/%d/%y') AS reportDate FROM `productiveAccount` a LEFT JOIN `productiveUser` as u on u.id={$userId} where a.id={$accountId} group by a.id");
	$newdata = Config::get('db') -> get_results("select n.deptId, n.dayDate, a.name as accountName,  DATE_FORMAT(z.lastStartDate,'%m/%d/%y') AS startDay, DATE_FORMAT(z.lastEndDate,'%m/%d/%y') AS endDay, a.displayProd, DATE_FORMAT(CURDATE(),'%m/%d/%y') AS reportDate, (SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END) / SUM(CASE WHEN n.userId>=0 THEN 1 ELSE 0 END) * 100) as reporting, SUM(CASE WHEN n.userId>0 THEN n.nvariance ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END) as varAvg, SUM(CASE WHEN n.userId>0 THEN n.gvariance ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END) as varGrid, SUM(CASE WHEN n.userId>0 and n.atotal>0 and n.shift=d.shift THEN n.atotal ELSE 0 END) / SUM(CASE WHEN n.userId>0 and n.atotal>0 and n.shift=d.shift THEN 1 ELSE 0 END) as censusShift, SUM(CASE WHEN n.userId>0 THEN n.atotal ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END) as censusAvg, SUM(CASE WHEN n.userId>0 THEN n.hrsVariance ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END) as hrsvarAvg, a.displayProd, a.productivityPosNeg, n.actualWHP, a.id as accountId, a.name as accountName, d.dept as deptName, d.prodMeasure, DATE_FORMAT(CURDATE(),'%m%d%y') as filedate, DATE_FORMAT(n.dayDate,'%m/%d/%y') as newDate, u.last_name as Director, i.last_name as Manager, a.id as accountId, ROUND(SUM((CASE WHEN n.actualWHPimport>0 and n.activeRecord>0 then n.hrsVarianceActual ELSE 0 END) + (CASE WHEN n.actualWHPimport=0.000 and n.activeRecord>0 then n.hrsVariance ELSE 0 END)),1) as hrsVarianceTotal, SUM((CASE WHEN n.userId>0 and n.activeRecord>0 THEN 1 ELSE 0 END)) as report, SUM((CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END)) as totalreports, ((SUM(CASE WHEN (n.userId>0 and n.activeRecord>0) THEN 1 ELSE 0 END) + (CASE WHEN n.userId=0 and n.activeRecord>0 and n.planSubmitted=1 THEN 1 ELSE 0 END)) / (SUM((CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END))) * 100) as compliance FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveAccount` as a on a.id=n.accountId left outer join `productiveUser` as i on i.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id AND `primaryUnit`=6 AND `unitAssigned`=0 order by `primaryUnit` ASC LIMIT 0,1) left outer Join `productiveUser` as u on u.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id AND `primaryUnit`=7 order by `primaryUnit` ASC LIMIT 0,1) left join `productiveUser` as z on z.id={$userId} where n.dayDate>=z.lastStartDate AND n.dayDate <=z.lastEndDate and n.accountId={$accountId} group by n.accountId ORDER BY a.name ASC");
	$deptName='';
	$prodMeasure = 0;
	}
//$newdata = Config::get('db') -> get_results("select a.name as accountName, DATE_FORMAT(u.lastStartDate,'%m/%d/%y') AS startDay, DATE_FORMAT(u.lastEndDate,'%m/%d/%y') AS endDay, a.displayProd, DATE_FORMAT(CURDATE(),'%m/%d/%y') AS reportDate FROM `productiveAccount` a LEFT JOIN `productiveUser` as u on u.id={$userId} where a.id={$accountId} group by a.id");
	if(intval($prodMeasure)==2 || intval($prodMeasure)==3 || intval($prodMeasure)==0){	
	$header = array('Unit', 'Manager', 'Submitted', 'Total Reports', 'Comp %', 'Date', 'Avg Variance');
    }else{
	$header = array('Unit', 'Manager', 'Submitted', 'Total Reports', 'Comp %', 'Date', 'Census', 'Avg Census', 'Avg RN Var', 'Avg Grid Var');	
	}
	
	$this->SetFont('Arial','B',14);
	// Move to the right
		// Move to the right
    $this->Cell(120);
    // Title
    $this->Cell(30,10,$newdata[0]['accountName'] . $deptName,0,0,'C');
    // Line break
    $this->Ln(6);
    $this->Cell(120);
	$this->SetFont('Arial','B',12);
	$this->Cell(30,10,'Unit Variance Summary',0,0,'C');
	// Line break
    $this->Ln(6);
	// Move to the right
    $this->Cell(120);
	$this->SetFont('Arial','B',10);
	$this->Cell(30,10,$newdata[0]['startDay'] . ' - ' . $newdata[0]['endDay'],0,0,'C');
	//$this->Cell(30,10,'Date: ' . $newdata[0]['reportDate'],0,0,'C');
	
	if(intval($prodMeasure)==2 || intval($prodMeasure)==3 || intval($prodMeasure)==0){	
	$this->Ln(6);
	$this->Cell(120);
	$this->SetFont('Arial','B',10);
    }else{
	$this->Ln(10);
	$this->Cell(120);
	$this->SetFont('Arial','B',10);
	$this->Cell(30,10, 'Reporting: ' . ROUND(floatval($newdata[0]['reporting']),1) . '%    Avg Midnight Census: ' . ROUND(floatval($newdata[0]['censusShift']),1) . '    Avg Census: ' . ROUND(floatval($newdata[0]['censusAvg']),1) .'    Avg RN Variance: ' . ROUND(floatval($newdata[0]['varAvg']),2) . '    Avg Grid Variance: ' . ROUND(floatval($newdata[0]['varGrid']),2),0,0,'C');	
	}
	// Line break
    $this->Ln(15);
	// Column widths
    $w = array(50, 25, 25, 25, 20, 20, 25, 25, 25, 25);
    // Header
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C');
    $this->Ln();
    // Data
	
    
}

function ImprovedTable($data4){
    $w = array(50, 25, 25, 25, 20, 20, 25, 25, 25, 25);
    
	foreach($data4 as $row){
		$this->SetTextColor(0,0,0);
        $this->Cell($w[0],6,$row['deptName'],'LRB');
       
		$this->Cell($w[1],6,$row['Manager'],'LRB');
		$this->Cell($w[2],6,$row['report'],'LRB',0,'C');
		$this->Cell($w[3],6,$row['totalreports'],'LRB',0,'C');
			
			if(floatval($row['compliance'])>0){
				$compliance = ROUND($row['compliance']) . '%';
			}else{
				$compliance = 'No Data';
			}
        $this->Cell($w[4],6,$compliance,'LRB',0,'C');
		$this->Cell($w[5],6,$row['newDate'],'LRB',0,'C');
		
		if(intval($row['varAvg'])==99){
			$varavg = '';
		}else{
			$varavg = ROUND($row['varAvg'],1);
		}
		
		if(intval($row['varGrid'])==99){
			$vargrid = '';
		}else{
			$vargrid = ROUND($row['varGrid'],1);
		}
		if(intval($row['censusShift'])==99){
			$cshift = '';
		}else{
			$cshift = ROUND($row['censusShift'],1);
		}
		if(intval($row['censusAvg'])==99){
			$cavg = '';
		}else{
			$cavg = ROUND($row['censusAvg'],1);
		}
		if(intval($row['hrsvarAvg'])==99){
			$havg = '';
		}else{
			$havg = ROUND($row['hrsvarAvg'],1);
		}
		
		if(intval($row['prodMeasure'])!=2 && intval($row['prodMeasure'])!=3){	
		$this->Cell($w[8],6,$cshift,'LRB',0,'C');
		$this->Cell($w[9],6,$cavg,'LRB',0,'C');
		$this->Cell($w[6],6,$varavg,'LRB',0,'C');			
		$this->Cell($w[7],6,$vargrid,'LRB',1,'C');
		}else{
		$this->Cell($w[6],6,$havg,'LRB',1,'C');
		}
    }

}


}
	
$pdf = new PDF_MC_Table();
$userId = Config::get('account')['id'];
$accountId = Config::get('account')['accountId'];
$enterpriseId = Config::get('account')['enterpriseId'];
$role = Config::get('account')['role'];
$deptNum = (isset($_REQUEST['u'])) ? $_REQUEST['u'] : 0;
if(intval($deptNum)>0){
$data4 = Config::get('db') -> get_results("select n.deptId, n.dayDate, IFNULL((SUM(CASE WHEN n.userId>0 THEN n.nvariance ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END)),99) as varAvg, IFNULL((SUM(CASE WHEN n.userId>0 THEN n.gvariance ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END)),99) as varGrid, IFNULL((SUM(CASE WHEN n.userId>0 and n.atotal>0 and n.shift=d.shift THEN n.atotal ELSE 0 END) / SUM(CASE WHEN n.userId>0 and n.atotal>0 and n.shift=d.shift THEN 1 ELSE 0 END)),99) as censusShift, IFNULL((SUM(CASE WHEN n.userId>0 THEN n.atotal ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END)),99) as censusAvg, IFNULL((SUM(CASE WHEN n.userId>0 THEN n.hrsVariance ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END)),99) as hrsvarAvg, a.displayProd, a.productivityPosNeg, n.actualWHP, a.id as accountId, a.name as accountName, d.dept as deptName, d.prodMeasure, DATE_FORMAT(CURDATE(),'%m%d%y') as filedate, DATE_FORMAT(n.dayDate,'%m/%d/%y') as newDate, u.last_name as Director, i.last_name as Manager, a.id as accountId, ROUND(SUM((CASE WHEN n.actualWHPimport>0 and n.activeRecord>0 then n.hrsVarianceActual ELSE 0 END) + (CASE WHEN n.actualWHPimport=0.000 and n.activeRecord>0 then n.hrsVariance ELSE 0 END)),1) as hrsVarianceTotal, SUM((CASE WHEN n.userId>0 and n.activeRecord>0 THEN 1 ELSE 0 END)) as report, SUM((CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END)) as totalreports, ((SUM(CASE WHEN (n.userId>0 and n.activeRecord>0) THEN 1 ELSE 0 END) + (CASE WHEN n.userId=0 and n.activeRecord>0 and n.planSubmitted=1 THEN 1 ELSE 0 END)) / (SUM((CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END))) * 100) as compliance FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveAccount` as a on a.id=n.accountId left outer join `productiveUser` as i on i.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id AND `primaryUnit`=6 AND `unitAssigned`=0 order by `primaryUnit` ASC LIMIT 0,1) left outer Join `productiveUser` as u on u.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id AND `primaryUnit`=7 order by `primaryUnit` ASC LIMIT 0,1) left join `productiveUser` as z on z.id={$userId} where d.active>0 AND n.deptId={$deptNum} AND n.dayDate>=z.lastStartDate AND n.dayDate <=z.lastEndDate and n.accountId={$accountId} group by n.accountId, n.deptId, n.dayDate ORDER BY n.dayDate DESC");
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',9);
$pdf->AddPage('L');
$pdf->ImprovedTable($data4);

$filename=substr($data4[0]['deptName'],0,7) . 'UnitVar' . $data4[0]['filedate'] . '.pdf';
$filelocation="/home/prnadmin/public_html/dailyreports/" . substr($data4[0]['deptName'],0,7) . $data4[0]['deptId'] . 'UnitVar' . $data4[0]['filedate'] . ".pdf";
$pdf->Output($filename,'D');
}
//$filename="/home/prnadmin/public_html/dailyreports/Variance". $data4[0]['filedate'] . ".pdf";
//$filelocation="/home/prnadmin/public_html/dailyreports/Variance" . $data4[0]['filedate'] . ".pdf";
//$pdf->Output($filename,'D');


exit();
}


function getrprt(){
//include '../inc/class.db.php';
//include '../inc/config.php';


//require_once '.././inc/class.fpdf2.php';
require_once '.././inc/FPDF/fpdf2.php';


class PDF_MC_Table extends FPDF{

var $widths;
var $aligns = array('L','C','L','C','C','C','C','C','C','C','C','C','C','C');


function SetWidths($w){
    //Set the array of column widths
    $this->widths=$w;
}

function SetAligns($a){
    //Set the array of column alignments
    $this->aligns=$a;
}

function Row($data2,$dept2){
	
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data2);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data2[$i]));
    $h=5*$nb;
    //Issue a page break first if needed
    $this->CheckPageBreak($h,$dept2);
    //Draw the cells of the row
    for($i=0;$i<count($data2);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
        $this->Rect($x,$y,$w,$h);
        //Print the text
        //$this->MultiCell($w,5,$data2[$i],0,$a);
		$this->MultiCell($w,5,$data2[$i],0,$a);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
}

function CheckPageBreak($h,$deptId){
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation,'',0,$deptId);
}

function NbLines($w,$txt){
    //Computes the number of lines a MultiCell of width w will take
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r",'',$txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)
    {
        $c=$s[$i];
        if($c=="\n")
        {
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
            continue;
        }
        if($c==' ')
            $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax)
        {
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
            }
            else
                $i=$sep+1;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
        }
        else
            $i++;
    }
    return $nl;
}

function Header($dept1){
$user1 = Config::get('account')['id'];
//echo "dept: " . $dept1;
$newDept = intval($dept1);
//echo "dept: " . $newDept;
//echo "user: " . $user1;
$newdata = Config::get('db') -> get_results("SELECT d.*, a.label, a.name as accountName, a.displayProd, DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%m/%d/%y') as reportDate, DATE_FORMAT(u.lastStartDate,'%m/%d/%y') AS startDay, DATE_FORMAT(u.lastEndDate,'%m/%d/%y') AS endDay from `ProductiveDept` d LEFT JOIN `productiveAccount` as a on a.id=d.accountId left join `productiveUser` as u on u.id={$user1} WHERE d.id={$newDept} GROUP BY d.id");
//$newdata = Config::get('db') -> get_results("select a.name as accountName, a.displayProd, DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%m/%d/%y') AS reportDate FROM `productiveAccount` a where a.id={$accountId} group by a.id");

$otherDesc = "Other";
$w5=12;
$w6=14;
$w7=18;
$w8=14;
$w9=14;
$w10=14;
//$w11=14;

$x4=19;
$x5=18;
$x6=18;
$x7=19;
$x8=19;
$x9=19;
$x10=19;

$skilldesc1 = $newdata[0]['skilldesc1'];
$skilldesc2 = $newdata[0]['skilldesc2'];
$skilldesc3 = $newdata[0]['skilldesc3'];
$skilldesc4 = $newdata[0]['skilldesc4'];
$skilldesc5 = $newdata[0]['skilldesc5'];
$skilldesc6 = $newdata[0]['skilldesc6'];
$hcount = 0;

if(strlen($newdata[0]['chargeDesc'] ?? '')==0){
	$w5 = 0;
}
if(strlen($newdata[0]['nurseDesc'] ?? '')==0){
	$w6 = 0;
}
if(strlen($newdata[0]['nurse1Desc'] ?? '')==0){
	$w7 = 0;
}
if(strlen($newdata[0]['nurse2Desc'] ?? '')==0){
	$w8 = 0;
}
if(strlen($newdata[0]['techLabel'] ?? '')==0){
	$w9 = 0;
}
if(strlen($newdata[0]['secLabel'] ?? '')==0){
	$w10 = 0;
}

if(strlen($newdata[0]['sittersNEWDesc'] ?? '')==0 && strlen($newdata[0]['other1Desc'] ?? '')==0 && strlen($newdata[0]['other2Desc'] ?? '')==0 && strlen($newdata[0]['other3Desc'] ?? '')==0){
	$w11 = 0;
	$otherDesc = '';
}else{
	$w11 = 14;
	$otherDesc = "Other";
}

if(intval($newdata[0]['skill1'])==0){
	$x4 = 0;
	$skilldesc1 = '';
}
if(intval($newdata[0]['skill2'])==0){
	$x5 = 0;
	$skilldesc2 = '';
}
if(intval($newdata[0]['skill3'])==0){
	$x6 = 0;
	$skilldesc3 = '';
}
if(intval($newdata[0]['skill4'])==0){
	$x7 = 0;
	$skilldesc4 = '';
}
if(intval($newdata[0]['skill5'])==0){
	$x8 = 0;
	$skilldesc5 = '';
}
if(intval($newdata[0]['skill6'])==0){
	$x9 = 0;
	$skilldesc6 = '';
}
if(intval($newdata[0]['skill7'])==0 && intval($newdata[0]['skill8'])==0 && intval($newdata[0]['skill9'])==0 && intval($newdata[0]['skill10'])==0){
	$x10 = 0;
	$otherDesc = '';
}
	

if(intval($newdata[0]['prodMeasure'])!=2 && intval($newdata[0]['prodMeasure'])!=3 && $w11==0){
$header2 = array('Unit', 'Date/Shift', 'User', 'Pts', ''. $newdata[0]['chargeDesc'] .'', ''. $newdata[0]['nurseDesc'] .'', ''. $newdata[0]['nurse1Desc'] .'', ''. $newdata[0]['nurse2Desc'] .'', ''. $newdata[0]['techLabel'] .'', ''. $newdata[0]['secLabel'] .'', ''. $otherDesc . '', 'RN Var', 'Grid Var', 'Notes');
$w = array(33, 27, 24, 10, $w5, $w6, $w7, $w8, $w9, $w10, $w11, 12, 15, 62);
}else if(intval($newdata[0]['prodMeasure'])!=2 && intval($newdata[0]['prodMeasure'])!=3 && $w11!=0){
$header2 = array('Unit', 'Date/Shift', 'User', 'Pts', ''. $newdata[0]['chargeDesc'] .'', ''. $newdata[0]['nurseDesc'] .'', ''. $newdata[0]['nurse1Desc'] .'', ''. $newdata[0]['nurse2Desc'] .'', ''. $newdata[0]['techLabel'] .'', ''. $newdata[0]['secLabel'] .'', 'Other', 'RN Var', 'Grid Var', 'Notes');
$w = array(33, 27, 24, 10, $w5, $w6, $w7, $w8, $w9, $w10, $w11, 12, 15, 62);
}else{
$header2 = array('Unit', 'Date/Shift', 'User', ''. $newdata[0]['uosDesc'] .'', ''. $skilldesc1 .'', ''. $skilldesc2 .'', ''. $skilldesc3 .'', ''. $skilldesc4 .'', ''. $skilldesc5 .'', ''. $skilldesc6 .'', '' . $otherDesc . '', 'Variance3', 'Notes');
$w = array(33, 27, 24, 20, $x4, $x5, $x6, $x7, $x8, $x9, $x10, 17, 55);
}
//echo 'header count ' . count($header2);
$dName = "Staffing Detail: " . stripslashes($newdata[0]['dept']);
	
	// Logo
    //$this->Image('logo.png',10,6,30);
    // Arial bold 15
    $this->SetFont('Arial','B',14);
    
	// Move to the right
    $this->Cell(120);
    // Title
    $this->Cell(30,10,$newdata[0]['accountName'],0,0,'C');
    // Line break
    $this->Ln(6);
	$this->Cell(120);
	$this->SetFont('Arial','B',12);
	$this->Cell(30,10,$dName,0,0,'C');
	// Line break
    $this->Ln(6);
	// Move to the right
    $this->Cell(120);
	$this->SetFont('Arial','B',12);
	$this->Cell(30,10,$newdata[0]['startDay'] . ' - ' . $newdata[0]['endDay'],0,0,'C');
	//$this->Cell(30,10,$newdata[0]['reportDate'],0,0,'C');
	// Line break
    $this->Ln(15);
	// Column widths
    
    // Header
	//$this->SetFont('Arial','B',10);
    //for($i=0;$i<count($header2);$i++)
       // $this->Cell($w[$i],7,$header2[$i],1,0,'C');
    //$this->Ln();
	for($i=0;$i<count($header2);$i++){
		$x=$this->GetX();
        $y=$this->GetY();
		//if(strlen($header2[$i])>12){
		$this->SetFont('Arial','B',7);	
		//}else{
		//$this->SetFont('Arial','B',8);	
		//}
        $this->Rect($x,$y,$w[$i],15);
		$this->MultiCell($w[$i],5,$header2[$i],0,'C');
		$this->SetXY($x+$w[$i],$y);
        //$this->Cell($w[$i],7,$header3[$i],1,0,'C');
	}
	$i=$i-1;
	$this->SetXY($x+$w[$i],$y+5);
    $this->Ln();
	$this->Ln();
	
	 //echo 'xy ' . $x+$w[$i] . ' , ' . $y . ', i , ' . $i . ' wi ' . $w[$i];
    
}

// Page footer
function Footer(){
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
}


var $widths3;
var $aligns3 = array('L','C','L','C','C','C','C','C','C','C','C','L');


function SetWidths3($w){
    //Set the array of column widths
    $this->widths=$w;
}

function SetAligns3($a){
    //Set the array of column alignments
    $this->aligns=$a;
}

function Row3($data2,$dept3){
	
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data2);$i++)
        $nb=max($nb,$this->NbLines3($this->widths[$i],$data2[$i]));
    $h=5*$nb;
    //Issue a page break first if needed
    $this->CheckPageBreak3($h,$dept3);
    //Draw the cells of the row
    for($i=0;$i<count($data2);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns3[$i]) ? $this->aligns3[$i] : 'L';
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
        $this->Rect($x,$y,$w,$h);
        //Print the text
        //$this->MultiCell($w,5,$data2[$i],0,$a);
		$this->MultiCell($w,5,$data2[$i],0,$a);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
}

function CheckPageBreak3($h,$deptId){
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage3($this->CurOrientation,'',0,$deptId);
}

function NbLines3($w,$txt){
    //Computes the number of lines a MultiCell of width w will take
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r",'',$txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)
    {
        $c=$s[$i];
        if($c=="\n")
        {
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
            continue;
        }
        if($c==' ')
            $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax)
        {
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
            }
            else
                $i=$sep+1;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
        }
        else
            $i++;
    }
    return $nl;
}

function Header3($dept3){
//echo "dept3 :" . $dept3;
$user3 = Config::get('account')['id'];
//echo "user3 :" . $user3;
$newdata = Config::get('db') -> get_results("SELECT d.*, a.name as accountName, a.displayProd, DATE_FORMAT(u.lastStartDate,'%m/%d/%y') AS startDay, DATE_FORMAT(u.lastEndDate,'%m/%d/%y') AS endDay, DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%m/%d/%y') as reportDate from `ProductiveDept` d LEFT JOIN `productiveAccount` as a on a.id=d.accountId left join `productiveUser` as u on u.id={$user3} WHERE d.id={$dept3} GROUP BY d.id");
if($newdata){

$count = 0;

if(strlen($newdata[0]['desc7'] ?? '')==0){
$b7=0;
}else{
$b7=1;	
$count=$count+1;
}
if(strlen($newdata[0]['desc8'] ?? '')==0){
$b8=0;	
}else{
$b8=1;	
$count=$count+1;
}
if(strlen($newdata[0]['desc9'] ?? '')==0){
$b9=0;	
}else{
$b9=1;	
$count=$count+1;
}
if(strlen($newdata[0]['desc10'] ?? '')==0){
$b10=0;	
}else{
$b10=1;	
$count=$count+1;
}
if(strlen($newdata[0]['desc11'] ?? '')==0){
$b11=0;	
}else{
$b11=1;	
$count=$count+1;
}
if(strlen($newdata[0]['desc12'] ?? '')==0){
$b12=0;	
}else{
$b12=1;	
$count=$count+1;
}

if($count>0){
	$count1 = ROUND((170 / $count),0);
}else{
	$count1= 0;
}

if($count1 >40){
	$count1 = 40;
}

$b7= $b7 * $count1;
$b8= $b8 * $count1;
$b9= $b9 * $count1;
$b10= $b10 * $count1;
$b11= $b11 * $count1;
$b12= $b12 * $count1;

$header3 = array('Unit', 'Date/Shift', 'User', 'Pts','' . $newdata[0]['desc7'] . '','' . $newdata[0]['desc8'] . '','' . $newdata[0]['desc9'] . '','' . $newdata[0]['desc10'] . '','' . $newdata[0]['desc11'] . '','' . $newdata[0]['desc12'] . '');
$w = array(42, 27, 25, 10, $b7, $b8, $b9, $b10, $b11, $b12);
$sName = "Additional Staffing Detail: " . stripslashes($newdata[0]['dept']);
	$this->SetFont('Arial','B',14);
    
	// Move to the right
    $this->Cell(120);
    // Title
    $this->Cell(30,10,$newdata[0]['accountName'],0,0,'C');
    // Line break
    $this->Ln(6);
	$this->Cell(120);
	$this->SetFont('Arial','B',12);
	$this->Cell(30,10,$sName,0,0,'C');
	// Line break
    $this->Ln(6);
	// Move to the right
    $this->Cell(120);
	$this->SetFont('Arial','B',12);
	$this->Cell(30,10,$newdata[0]['startDay'] . ' - ' . $newdata[0]['endDay'],0,0,'C');
	//$this->Cell(30,10,$newdata[0]['reportDate'],0,0,'C');
	// Line break
    $this->Ln(15);
	// Column widths
    
    // Header
	//$this->SetFont('Arial','B',10);
    //for($i=0;$i<count($header2);$i++)
       // $this->Cell($w[$i],7,$header2[$i],1,0,'C');
    //$this->Ln();
	for($i=0;$i<count($header3);$i++){
		$x=$this->GetX();
        $y=$this->GetY();
		//if(strlen($header2[$i])>12){
		$this->SetFont('Arial','B',7);	
		//}else{
		//$this->SetFont('Arial','B',8);	
		//}
        $this->Rect($x,$y,$w[$i],15);
		$this->MultiCell($w[$i],5,$header3[$i],0,'C');
		$this->SetXY($x+$w[$i],$y);
        //$this->Cell($w[$i],7,$header3[$i],1,0,'C');
	}
	$i=$i-1;
	$this->SetXY($x+$w[$i],$y+5);
    $this->Ln();
	$this->Ln();
}	 
    
}
// Page footer

function Footer3(){
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
}


var $widths2;
var $aligns2 = array('L','C','L','C','C','C','C','C','C','C','C','L');


function SetWidths2($w){
    //Set the array of column widths
    $this->widths=$w;
}

function SetAligns2($a){
    //Set the array of column alignments
    $this->aligns=$a;
}

function Row2($data2,$dept3){
	
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data2);$i++)
        $nb=max($nb,$this->NbLines2($this->widths[$i],$data2[$i]));
    $h=5*$nb;
    //Issue a page break first if needed
    $this->CheckPageBreak2($h,$dept3);
    //Draw the cells of the row
    for($i=0;$i<count($data2);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns2[$i]) ? $this->aligns2[$i] : 'L';
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
        $this->Rect($x,$y,$w,$h);
        //Print the text
        //$this->MultiCell($w,5,$data2[$i],0,$a);
		$this->MultiCell($w,5,$data2[$i],0,$a);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
}

function CheckPageBreak2($h,$deptId){
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage2($this->CurOrientation,'',0,$deptId);
}

function NbLines2($w,$txt){
    //Computes the number of lines a MultiCell of width w will take
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r",'',$txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)
    {
        $c=$s[$i];
        if($c=="\n")
        {
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
            continue;
        }
        if($c==' ')
            $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax)
        {
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
            }
            else
                $i=$sep+1;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
        }
        else
            $i++;
    }
    return $nl;
}

function Header2($dept){
//echo "dept2 :" . $dept;
$user2 = Config::get('account')['id'];
//echo "user2 :" . $user2;
$newdata = Config::get('db') -> get_results("SELECT d.*, a.name as accountName, a.displayProd, DATE_FORMAT(u.lastStartDate,'%m/%d/%y') AS startDay, DATE_FORMAT(u.lastEndDate,'%m/%d/%y') AS endDay, DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%m/%d/%y') as reportDate from `ProductiveDept` d LEFT JOIN `productiveAccount` as a on a.id=d.accountId left join `productiveUser` as u on u.id={$user2} WHERE d.id={$dept} GROUP BY d.id");
if($newdata){
$a1=38;
$a2=38;
$a3=38;
$a4=40;

$b1=30;
$b2=30;
$b3=30;
$b4=25;
$b5=25;
$b6=25;


if(strlen($newdata[0]['track1Desc'] ?? '')==0){
$a1=0;
$b3=0;	
}
if(strlen($newdata[0]['track2Desc'] ?? '')==0){
$a2=0;	
$b4=0;
}
if(strlen($newdata[0]['track3Desc'] ?? '')==0){
$a3=0;
$b5=0;	
}
if(strlen($newdata[0]['track4Desc'] ?? '')==0){
$a4=0;
$b6=0;	
}
if(strlen($newdata[0]['customDesc'] ?? '')==0){
$b1=0;	
}
if(strlen($newdata[0]['customDesc2'] ?? '')==0){
$b2=0;	
}
if(intval($newdata[0]['prodMeasure'])!=2 && intval($newdata[0]['showEpic'])==1){
$header3 = array('Unit', 'Date/Shift', 'User', 'Pts','Epic','' . $newdata[0]['track1Desc'] . '','' . $newdata[0]['track2Desc'] . '','' . $newdata[0]['track3Desc'] . '','' . $newdata[0]['track4Desc'] . '');
$w = array(42, 27, 25, 10, 20, $a1, $a2, $a3, $a4);
}else if(intval($newdata[0]['prodMeasure'])!=2 && intval($newdata[0]['showEpic'])==0){
$header3 = array('Unit', 'Date/Shift', 'User', 'Pts','' . $newdata[0]['track1Desc'] . '','' . $newdata[0]['track2Desc'] . '','' . $newdata[0]['track3Desc'] . '','' . $newdata[0]['track4Desc'] . '');
$w = array(42, 27, 25, 10, $a1, $a2, $a3, $a4);
}else{
$header3 = array('Unit', 'Date/Shift','User','' . $newdata[0]['uosDesc'] . '','' . $newdata[0]['customDesc'] . '','' . $newdata[0]['customDesc2'] . '','' . $newdata[0]['track1Desc'] . '','' . $newdata[0]['track2Desc'] . '','' . $newdata[0]['track3Desc'] . '','' . $newdata[0]['track4Desc'] . '');
$w = array(42, 27, 30, 20, $b1, $b2, $b3, $b4, $b5, $b6);
//$header3 = array('Unit', 'Date/Shift', 'User', 'Pts','' . $newdata[0]['desc7'] . '','' . $newdata[0]['track1Desc'] . '','' . $newdata[0]['track2Desc'] . '','' . $newdata[0]['track3Desc'] . '','' . $newdata[0]['track4Desc'] . '');
}

$deptName = "Tracking Detail: " . stripslashes($newdata[0]['dept']);

	// Logo
    //$this->Image('logo.png',10,6,30);
    // Arial bold 15
    $this->SetFont('Arial','B',14);
    
	// Move to the right
    $this->Cell(120);
    // Title
    $this->Cell(30,10,$newdata[0]['accountName'],0,0,'C');
    // Line break
    $this->Ln(6);
	$this->Cell(120);
	$this->SetFont('Arial','B',12);
	$this->Cell(30,10,$deptName,0,0,'C');
	// Line break
    $this->Ln(6);
	// Move to the right
    $this->Cell(120);
	$this->SetFont('Arial','B',12);
	$this->Cell(30,10,$newdata[0]['startDay'] . ' - ' . $newdata[0]['endDay'],0,0,'C');
	//$this->Cell(30,10,$newdata[0]['reportDate'],0,0,'C');
	// Line break
    $this->Ln(15);
	// Column widths
    
    // Header
	//$this->SetFont('Arial','B',8);
    for($i=0;$i<count($header3);$i++){
		$x=$this->GetX();
        $y=$this->GetY();
		if(strlen($header3[$i])>12){
		$this->SetFont('Arial','B',8);	
		}else{
		$this->SetFont('Arial','B',10);	
		}
        $this->Rect($x,$y,$w[$i],10);
		$this->MultiCell($w[$i],5,$header3[$i],0,'C');
		$this->SetXY($x+$w[$i],$y);
        //$this->Cell($w[$i],7,$header3[$i],1,0,'C');
	}
    $this->Ln();
	$this->Ln();
	
}	 
    
}

function Footer2(){
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
}

}
	
$pdf = new PDF_MC_Table();

//$data4 = Config::get('db') -> get_results("select n.deptId, SUM(CASE WHEN n.userId>0 THEN n.nvariance ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END) as varAvg, a.displayProd, a.productivityPosNeg, n.actualWHP, a.id as accountId, a.name as accountName, d.dept as deptName, d.prodMeasure, DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%m%d%y') as filedate, u.last_name as Director, i.last_name as Manager, a.id as accountId, ROUND(SUM((CASE WHEN n.actualWHPimport>0 and n.activeRecord>0 then n.hrsVarianceActual ELSE 0 END) + (CASE WHEN n.actualWHPimport=0.000 and n.activeRecord>0 then n.hrsVariance ELSE 0 END)),1) as hrsVarianceTotal, SUM((CASE WHEN n.userId>0 and n.activeRecord>0 THEN 1 ELSE 0 END)) as report, SUM((CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END)) as totalreports, ((SUM(CASE WHEN (n.userId>0 and n.activeRecord>0) THEN 1 ELSE 0 END) + (CASE WHEN n.userId=0 and n.activeRecord>0 and n.planSubmitted=1 THEN 1 ELSE 0 END)) / (SUM((CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END))) * 100) as compliance FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveAccount` as a on a.id=n.accountId left outer join `productiveUser` as i on i.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id AND `primaryUnit`=6 order by `primaryUnit` ASC LIMIT 0,1) left outer Join `productiveUser` as u on u.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id AND `primaryUnit`=7 order by `primaryUnit` ASC LIMIT 0,1) where d.active>0 AND n.dayDate=DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%Y-%m-%d') and n.accountId={$accountId} group by n.accountId, n.deptId ORDER BY compliance DESC, d.dept ASC");
//$pdf->AliasNbPages();
//$pdf->SetFont('Arial','',9);
//$pdf->AddPage4('P');
//$pdf->ImprovedTable($data4);

$userId = Config::get('account')['id'];
$accountId = Config::get('account')['accountId'];
$enterpriseId = Config::get('account')['enterpriseId'];
$role = Config::get('account')['role'];
$reportId = (isset($_REQUEST['r'])) ? $_REQUEST['r'] : 0;


 if(intval($reportId)==1){
		$searchAdd = "AND d.active>0 AND d.id in (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId} and `primaryUnit`>0) AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate";
	}else if(intval($reportId)==0){
		$searchAdd = "AND d.active>0 AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate";
	}else{
		$searchAdd = "AND n.deptId={$reportId} AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate";	
	}

$getDept = Config::get('db') -> get_results("SELECT n.deptId, n.dayDate, d.active, d.dept, d.prodMeasure1, a.name as accountName, a.displayProd, u.lastStartDate, u.lastEndDate, DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%m/%d/%y') as reportDate from `productiveNewData` n LEFT JOIN `productiveAccount` as a on a.id=n.accountId LEFT JOIN `ProductiveDept` as d on d.id=n.deptId left join `productiveUser` as u on u.id={$userId} WHERE n.accountId={$accountId} $searchAdd GROUP BY n.deptId ORDER BY d.prodMeasure ASC, d.dept ASC");
/*
if($getDept){
$getDept1 = array();
$getDept1 = $getDept;
}else{
$getDept1[] = array(
				'deptId' => $reportId
            );
}
*/
	
foreach($getDept as $newData){

$deptId = $newData['deptId'];

$data2 = Config::get('db') -> get_results("select n.*, u.lastStartDate, u.lastEndDate, d.prodMeasure1, d.skill1, d.skill2, d.skill3, d.skill4, d.skill5, d.skill6, d.skill7, d.skill8, d.skill9, d.skill10, o.practiceId, (n.skill1val + n.skill2val + n.skill3val + n.skill4val + n.skill5val + n.skill6val + n.skill7val + n.skill8val + n.skill9val + n.skill10val) as Hours, DATE_FORMAT(n.dayDate,'%m/%d') as newDate, s.shift as shiftName, d.chargeDesc, d.sittersNEWDesc, (CASE WHEN d.customDesc='' THEN '' ELSE d.customDesc END) as customDesc, (CASE WHEN d.customDesc2='' THEN '' ELSE d.customDesc2 END) as customDesc2, (CASE WHEN d.skilldesc1='' THEN '' ELSE d.skilldesc1 END) as skilldesc1, (CASE WHEN d.skilldesc2='' THEN '' ELSE d.skilldesc2 END) as skilldesc2, (CASE WHEN d.skilldesc3='' THEN '' ELSE d.skilldesc3 END) as skilldesc3, (CASE WHEN d.skilldesc4='' THEN '' ELSE d.skilldesc4 END) as skilldesc4, (CASE WHEN d.skilldesc5='' THEN '' ELSE d.skilldesc5 END) as skilldesc5, (CASE WHEN d.skilldesc6='' THEN '' ELSE d.skilldesc6 END) as skilldesc6, (CASE WHEN d.skilldesc7='' THEN '' ELSE d.skilldesc7 END) as skilldesc7, (CASE WHEN d.skilldesc8='' THEN '' ELSE d.skilldesc8 END) as skilldesc8, (CASE WHEN d.skilldesc9='' THEN '' ELSE d.skilldesc9 END) as skilldesc9, (CASE WHEN d.skilldesc10='' THEN '' ELSE d.skilldesc10 END) as skilldesc10, (CASE WHEN d.skilldesc11='' THEN '' ELSE d.skilldesc11 END) as skilldesc11, (CASE WHEN d.nurseDesc='' THEN '' ELSE d.nurseDesc END) as nurseDesc, (CASE WHEN d.nurse1Desc='' THEN '' ELSE d.nurse1Desc END) as nurse1Desc, (CASE WHEN d.nurse2Desc='' THEN '' ELSE d.nurse2Desc END) as nurse2Desc, (CASE WHEN d.other1Desc='' THEN '' ELSE d.other1Desc END) as nother1Desc, (CASE WHEN d.techLabel='' THEN '' ELSE d.techLabel END) as techLabel, (CASE WHEN d.secLabel='' THEN '' ELSE d.secLabel END) as secLabel, (CASE WHEN d.other1Desc='' THEN '' ELSE d.other1Desc END) as other1Desc, (CASE WHEN d.other2Desc='' THEN '' ELSE d.other2Desc END) as other2Desc, (CASE WHEN d.other3Desc='' THEN '' ELSE d.other3Desc END) as other3Desc, (CASE WHEN d.desc7='' THEN '' ELSE d.desc7 END) as desc7, (CASE WHEN d.desc8='' THEN '' ELSE d.desc8 END) as desc8, (CASE WHEN d.desc9='' THEN '' ELSE d.desc9 END) as desc9, (CASE WHEN d.desc10='' THEN '' ELSE d.desc10 END) as desc10, (CASE WHEN d.desc11='' THEN '' ELSE d.desc11 END) as desc11, (CASE WHEN d.desc12='' THEN '' ELSE d.desc12 END) as desc12, (CASE WHEN d.track1Desc='' THEN '' ELSE d.track1Desc END) as track1Desc, (CASE WHEN d.track2Desc='' THEN '' ELSE d.track2Desc END) as track2Desc, (CASE WHEN d.track3Desc='' THEN '' ELSE d.track3Desc END) as track3Desc, (CASE WHEN d.track4Desc='' THEN '' ELSE d.track4Desc END) as track4Desc, d.shiftsperDay, d.prodMeasure, d.rnCount, d.rn1Count, d.rn2Count, d.showEpic, CONCAT(ROUND(n.nproductivity,1),'%') as prod, o.last_name as submitted, d.oneto1Acuity, d.oneto2Acuity, d.oneto3Acuity, d.oneto4Acuity, d.oneto5Acuity, d.oneto6Acuity, d.dept as deptName, DATE_FORMAT(CURDATE(),'%m%d%y') as filedate, a.id as accountId, l.noreply, l.alertName, a.name as accountName, a.displayProd, n.hrsVariance as hrsVarianceTotal, n.actualWHP as actualWHPTotal, d.useGrid FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveShifts` as s on s.id = n.shift left join `productiveAccount` as a on a.id=n.accountId LEFT JOIN `productiveLabel` as l on l.id=a.label left outer join `productiveUser` as o on o.id=n.userId left Join `productiveUser` as u on u.id={$userId} where n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate AND n.deptId={$deptId} group by n.accountId, n.deptId, n.dayDate, n.shift ORDER BY d.prodMeasure ASC, d.dept ASC, n.dayDate DESC, n.shift DESC");

$pdf->AliasNbPages();
$pdf->SetFont('Arial','',9);
$pdf->AddPage('L','',0,$deptId);

$w5=12;
$w6=14;
$w7=18;
$w8=14;
$w9=14;
$w10=14;
$w11=14;
$x4=19;
$x5=18;
$x6=18;
$x7=19;
$x8=19;
$x9=19;
$x10=19;

if(strlen($data2[0]['chargeDesc'] ?? '')==0){
	$w5 = 0;
}else{
	$w5 = 12;
}
if(strlen($data2[0]['nurseDesc'] ?? '')==0){
	$w6 = 0;
}else{
	$w6 = 14;
}
if(strlen($data2[0]['nurse1Desc'] ?? '')==0){
	$w7 = 0;
}else{
	$w7 = 18;
}
if(strlen($data2[0]['nurse2Desc'] ?? '')==0){
	$w8 = 0;
}else{
	$w8 = 14;
}
if(strlen($data2[0]['techLabel'] ?? '')==0){
	$w9 = 0;
}else{
	$w9 = 14;
}
if(strlen($data2[0]['secLabel'] ?? '')==0){
	$w10 = 0;
}else{
	$w10 = 14;
}
if(strlen($data2[0]['sittersNEWDesc'] ?? '')==0 && strlen($data2[0]['other1Desc'] ?? '')==0 && strlen($data2[0]['other2Desc'] ?? '')==0 && strlen($data2[0]['other3Desc'] ?? '')==0){
	$w11 = 0;
}else{
	$w11 = 14;
}
if(intval($data2[0]['skill1'])==0){
	$x4 = 0;
}
if(intval($data2[0]['skill2'])==0){
	$x5 = 0;
}
if(intval($data2[0]['skill3'])==0){
	$x6 = 0;
}
if(intval($data2[0]['skill4'])==0){
	$x7 = 0;
}
if(intval($data2[0]['skill5'])==0){
	$x8 = 0;
}
if(intval($data2[0]['skill6'])==0){
	$x9 = 0;
}
if(intval($data2[0]['skill7'])==0 && intval($data2[0]['skill8'])==0 && intval($data2[0]['skill9'])==0 && intval($data2[0]['skill10'])==0){
	$x10 = 0;
}

if(intval($data2[0]['prodMeasure'])==2 || intval($data2[0]['prodMeasure'])==3){
$pdf->SetWidths(array(33, 27, 24, 20, $x4, $x5, $x6, $x7, $x8, $x9, $x10, 17, 55));
}else{
$pdf->SetWidths(array(33, 27, 24, 10, $w5, $w6, $w7, $w8, $w9, $w10, $w11, 12, 15, 62));	
}

foreach($data2 as $row2){
	$date = $row2['newDate'] . ' - ' . $row2['shiftName']; 
	
	if($row2['userId']==0){
	$deptId = $row2['deptId'];
	$charge = '';
	$triage = '';
	$blt = '';
	$rn = '';
	$techs = '';
	$sec = '';
	$nurse2 = '';
	$sitters = '';
	$orien = '';
	$other = '';
	$patients = "-";
	$note = '';
	$nvariance = "-";
	$gvariance = '';
	$submittedby = "-";
	$res1 ='';
	$res2 ='';
	$res3 ='';
	$res4 ='';
	$res5 ='';
	$res6 ='';
	$res8 ='';
	$otherval ='';
	$procedureCount ='';
	}else{
	$deptId = $row2['deptId'];
	/*
	if(floatval($row2['grid1var'])==0 || intval($row2['useGrid'])!=2){
	$leadvar = '';
	}else{
	$leadvar = "(" . floatval($row2['grid1var']) . ")";
	}
	if(floatval($row2['grid3var'])==0 || intval($row2['useGrid'])!=2){
	$dcvar = '';
	}else{
	$dcvar = "(" . floatval($row2['grid3var']) . ")";
	}
	if(floatval($row2['grid4var'])==0 || intval($row2['useGrid'])!=2){
	$fswvar = '';
	}else{
	$fswvar = "(" . floatval($row2['grid4var']) . ")";
	}
	if(floatval($row2['grid5var'])==0 || intval($row2['useGrid'])!=2){
	$retailvar = '';
	}else{
	$retailvar = "(" . floatval($row2['grid5var']) . ")";
	}
	if(floatval($row2['grid2var'])==0 || intval($row2['useGrid'])!=2){
	$cookvar = '';
	}else{
	$cookvar = "(" . floatval($row2['grid2var']) . ")";
	}
	if(floatval($row2['grid6var'])==0 || intval($row2['useGrid'])!=2){
	$coffeevar = '';
	}else{
	$coffeevar = "(" . floatval($row2['grid6var']) . ")";
	}
	if(floatval($row2['grid7var'])==0 || intval($row2['useGrid'])!=2){
	$lleadvar = '';
	}else{
	$lleadvar = "(" . floatval($row2['grid7var']) . ")";
	}
	if(floatval($row2['grid8var'])==0 || intval($row2['useGrid'])!=2){
	$lfswvar = '';
	}else{
	$lfswvar = "(" . floatval($row2['grid8var']) . ")";
	}
	if(floatval($row2['grid9var'])==0 || intval($row2['useGrid'])!=2){
	$skill9var = '';
	}else{
	$skill9var = "(" . floatval($row2['grid9var']) . ")";
	}
	if(floatval($row2['grid10var'])==0 || intval($row2['useGrid'])!=2){
	$skill10var = '';
	}else{
	$skill10var = "(" . floatval($row2['grid10var']) . ")";
	}
	if(intval($row2['useGrid'])==2 && (floatval($row2['grid7var'])!=0 || floatval($row2['grid8var'])!=0 || floatval($row2['grid9var'])!=0 || floatval($row2['grid10var'])!=0)){
	$othervar1 = floatval($row2['grid7var']) + floatval($row2['grid8var']) + floatval($row2['grid9var']) + floatval($row2['grid10var']);
	$othervar = "(" . $othervar1 . ")";
	}else{
	$othervar = '';
	}
	*/
	if(intval($row2['skill7'])==0 && intval($row2['skill8'])==0 && intval($row2['skill9'])==0 && intval($row2['skill10'])==0){
	$otherval = '';
	}else{
	$otherval2 = floatval($row2['skill8val']) + floatval($row2['skill8val']) + floatval($row2['skill9val']) + floatval($row2['skill10val']);
	$otherval = $otherval2 . $othervar;
	}
	//$prod = $row2['prod'];
	if(intval($row2['skill1'])==0){
	$res1 ='';
	}else{
	$res1 = floatval($row2['skill1val']);
	//$res1 = floatval($row2['skill1val']) . $leadvar;
	}
	
	if(intval($row2['skill2'])==0){
	$res2 ='';
	}else{
	$res2 = floatval($row2['skill2val']);
	//$res2 = floatval($row2['skill2val']) . $cookvar;
	}
	
	if(intval($row2['skill3'])==0){
	$res3 ='';
	}else{
	$res3 = floatval($row2['skill3val']);
	//$res3 = floatval($row2['skill3val']) . $dcvar;
	}
	
	if(intval($row2['skill4'])==0){
	$res4 ='';
	}else{
	$res4 = floatval($row2['skill4val']);
	//$res4 = floatval($row2['skill4val']) . $fswvar;
	}
	
	if(intval($row2['skill5'])==0){
	$res5 ='';
	}else{
	$res5 = floatval($row2['skill5val']);
	//$res5 = floatval($row2['skill5val']) . $retailvar;
	}
	
	if(intval($row2['skill6'])==0){
	$res6 ='';
	}else{
	$res6 = floatval($row2['skill6val']);
	//$res6 = floatval($row2['skill6val']) . $coffeevar;
	}
	
	if(intval($row2['skill7'])==0 && intval($row2['skill8'])==0 && intval($row2['skill9'])==0 && intval($row2['skill10'])==0){
	$res8 ='';
	}else{
	$res8 = $otherval;
	}
	
	if(strlen($row2['submittedby'] ?? '')>0){
	$submittedby = $row2['submittedby'];
	}else{
	$submittedby = $row2['submitted'];	
	}

	//$charge = round($row2['chargecount'],1);
	if(strlen($row2['chargeDesc'] ?? '')==0){
	$charge='';
	}else{
	$charge = round(floatval($row2['chargecount']),0);
	}
	if(strlen($row2['nurseDesc'] ?? '')==0){
	$rn='';
	}else{
	$rn = round(floatval($row2['antecount']),0);
	}
	if(strlen($row2['nurse1Desc'] ?? '')==0){
	$triage='';
	}else{
	$triage = $row2['customNurse'];
	}
	if(strlen($row2['nurse2Desc'] ?? '')==0){
	$nurse2='';
	}else{
	$nurse2 = $row2['customNurse2'];
	}
	if(strlen($row2['techLabel'] ?? '')==0){
	$techs='';
	}else{
	$techs = $row2['techcount'];
	}
	if(strlen($row2['secLabel'] ?? '')==0){
	$sec='';
	}else{
	$sec = $row2['seccount'];
	}
	$other =  $row2['sittercount'] + $row2['otherNurse1'] + $row2['otherNurse2'] + $row2['otherNurse3'];
	$patients = $row2['atotal'] + $row2['ltotal'] + $row2['patientCount2'];
	$nvariance = round(floatval($row2['nvariance']),1);
	$gvariance = round(floatval($row2['gvariance']),1);
	$procedureCount = round(floatval($row2['procedureCount']),0);

		
	$rnVar = '-';
	$note = stripslashes($row2['note']);
	
	//$totalRes = round(($row2['antecount'] + $row2['chargecount'] + $row2['customNurse'] + $row2['otherNurse1'] + $row2['otherNurse2'] + $row2['otherNurse3'] + $row2['seccount'] + $row2['sittercount'] + $row2['techcount']),1);
	}
if(intval($data2[0]['prodMeasure'])!=2){
$pdf->Row(array($row2['deptName'],$date,$submittedby,$patients,$charge,$rn,$triage,$nurse2,$techs,$sec,$other,$nvariance,$gvariance,$note),$deptId);
}else{
$pdf->Row(array($row2['deptName'],$date,$submittedby,$procedureCount,$res1,$res2,$res3,$res4,$res5,$res6,$otherval,$gvariance,$note),$deptId);	
}

}

if(intval($row2['prodMeasure'])!=2 && intval($row2['prodMeasure'])!=3 && (strlen($row2['desc7'])>0 || strlen($row2['desc8'])>0 || strlen($row2['desc9'])>0 || strlen($row2['desc10'])>0 || strlen($row2['desc11'])>0 || strlen($row2['desc12'])>0)){
//if(strlen($row2['desc7'])>0 || strlen($row2['desc8'])>0 || strlen($row2['desc9'])>0 || strlen($row2['desc10'])>0 || strlen($row2['desc11'])>0 || strlen($row2['desc12'])>0){
$pdf->AliasNbPages3();
$pdf->SetFont('Arial','',9);
$pdf->AddPage3('L','',0,$deptId);
//$pdf->Ln();
//$pdf->Ln();
//$pdf->Header3($deptId);
//$pdf->SetFont('Arial','',9);

$count = 0;

if(strlen($row2['desc7'] ?? '')==0){
$b7=0;
}else{
$b7=1;	
$count=$count+1;
}
if(strlen($row2['desc8'] ?? '')==0){
$b8=0;	
}else{
$b8=1;	
$count=$count+1;
}
if(strlen($row2['desc9'] ?? '')==0){
$b9=0;	
}else{
$b9=1;	
$count=$count+1;
}
if(strlen($row2['desc10'] ?? '')==0){
$b10=0;	
}else{
$b10=1;	
$count=$count+1;
}
if(strlen($row2['desc11'] ?? '')==0){
$b11=0;	
}else{
$b11=1;	
$count=$count+1;
}
if(strlen($row2['desc12'] ?? '')==0){
$b12=0;	
}else{
$b12=1;	
$count=$count+1;
}

if($count>0){
	$count1 = ROUND((170 / $count),0);
}else{
	$count1= 0;
}

if($count1 >40){
	$count1 = 40;
}

$b7= $b7 * $count1;
$b8= $b8 * $count1;
$b9= $b9 * $count1;
$b10= $b10 * $count1;
$b11= $b11 * $count1;
$b12= $b12 * $count1;

$pdf->SetWidths2(array(42, 27, 25, 10, $b7, $b8, $b9, $b10, $b11, $b12));


foreach($data2 as $row2){
	$date = $row2['newDate'] . ' - ' . $row2['shiftName'];
	$deptId = $row2['deptId'];
	
	if($row2['userId']==0){
	$note = "-";
	$submittedby = "-";
	$ptd7 = '';
	$ptd8 = '';
	$ptd9 = '';
	$ptd10 = '';
	$ptd11 = '';
	$ptd12 = '';
	$patients = '';
	}else{
		
	$patients = $row2['atotal'];
	if(strlen($row2['desc7'] ?? '')==0){
	$ptd7='';
	}else{
	$ptd7 = $row2['oneto7'];
	}
	if(strlen($row2['desc8'] ?? '')==0){
	$ptd8='';
	}else{
	$ptd8 = $row2['oneto8'];
	}
	if(strlen($row2['desc9'] ?? '')==0){
	$ptd9='';
	}else{
	$ptd9 = $row2['oneto9'];
	}
	if(strlen($row2['desc10'] ?? '')==0){
	$ptd10='';
	}else{
	$ptd10 = $row2['oneto10'];
	}
	if(strlen($row2['desc11'] ?? '')==0){
	$ptd11='';
	}else{
	$ptd11 = $row2['oneto11'];
	}
	if(strlen($row2['desc12'] ?? '')==0){
	$ptd12='';
	}else{
	$ptd12 = $row2['oneto12'];
	}
	
	if(intval($row2['practiceId'])==1){
	$submittedby = $row2['submittedby'];
	}else{
	$submittedby = $row2['submitted'];	
	}
	}	

$pdf->Row3(array($row2['deptName'],$date,$submittedby,$patients,$ptd7,$ptd8,$ptd9,$ptd10,$ptd11,$ptd12),$deptId);	




}
}
if(strlen($row2['track1Desc'] ?? '')>1 || strlen($row2['track2Desc'] ?? '')>1 || strlen($row2['track3Desc'] ?? '')>1 || strlen($row2['track4Desc'] ?? '')>1){
$pdf->AliasNbPages2();
$pdf->SetFont('Arial','',9);
$pdf->AddPage2('L','',0,$deptId);


$a1=38;
$a2=38;
$a3=38;
$a4=40;

$b1=30;
$b2=30;
$b3=30;
$b4=25;
$b5=25;
$b6=25;


if(strlen($row2['track1Desc'] ?? '')==0){
$a1=0;
$b3=0;	
}
if(strlen($row2['track2Desc'] ?? '')==0){
$a2=0;	
$b4=0;
}
if(strlen($row2['track3Desc'] ?? '')==0){
$a3=0;
$b5=0;	
}
if(strlen($row2['track4Desc'] ?? '')==0){
$a4=0;
$b6=0;	
}
if(strlen($row2['customDesc'] ?? '')==0){
$b1=0;	
}
if(strlen($row2['customDesc2'] ?? '')==0){
$b2=0;	
}


if(intval($row2['prodMeasure'])==2 || intval($row2['prodMeasure'])==3){
$pdf->SetWidths2(array(42, 27, 30, 20, $b1, $b2, $b3, $b4, $b5, $b6));
}else if(intval($row2['prodMeasure'])!=2 && intval($row2['showEpic'])==1){
$pdf->SetWidths2(array(42, 27, 25, 10, 20, $a1, $a2, $a3, $a4));	
}else{
$pdf->SetWidths2(array(42, 27, 25, 10, $a1, $a2, $a3, $a4));	
}

foreach($data2 as $row2){
	$date = $row2['newDate'] . ' - ' . $row2['shiftName'];
	$deptId = $row2['deptId'];
	$oneto1 = 0;
	$oneto2 = 0;
	$oneto3 = 0;
	$oneto4 = 0;
	$oneto5 = 0;
	$oneto6 = 0;
	$twoto1 = 0;
	
	if($row2['userId']==0){
	$es1 = "-";
	$es2 = "-";
	$es3 = "-";
	$es4 = "-";
	$es5 = "-";
	$esp = "-";
	$gr10 = "-";
	$gr5 = "-";
	$pdg = "-";
	$brd = "-";
	$wait = "-";
	$patients = "-";
	$note = "-";
	$track1 = '';
	$track2 = '';
	$track3 = '';
	$track4 = '';
	$submittedby = "-";
	$custom1 = '';
	$custom2 = '';
	$custom3 = '';
	$custom4 = '';
	$census = "-";
	}else{
	
	if(intval($row2['showEpic'])==1){
	$gr10 = ROUND(intval($row2['epicScore']) / intval($row2['antecount']),1);
	}else{
	$gr10 = $row2['epicScore'];
	}
	
	$patients = $row2['atotal'] + $row2['patientCount2'];
	if(strlen($row2['track1Desc'] ?? '')==0){
	$track1='';
	}else{
	$track1 = $row2['track1'];
	}
	if(strlen($row2['track2Desc'] ?? '')==0){
	$track2='';
	}else{
	$track2 = $row2['track2'];
	}
	if(strlen($row2['track3Desc'] ?? '')==0){
	$track3='';
	}else{
	$track3 = $row2['track3'];
	}
	if(strlen($row2['track4Desc'] ?? '')==0){
	$track4='';
	}else{
	$track4 = $row2['track4'];
	}
	$note = stripslashes($row2['note']);
	$census = intval($row2['procedureCount']);
	
	if(strlen($row2['customDesc'] ?? '')==0){
	$custom1 ='';
	}else{
	$custom1 = intval($row2['whpCustom']);
	}
	if(strlen($row2['customDesc2'] ?? '')==0){
	$custom2 ='';
	}else{
	$custom2 = intval($row2['whpCustom2']);
	}
	$custom3 = intval($row2['whpCustom3']);
	$custom4 = intval($row2['whpCustom4']);
	if(intval($row2['practiceId'])==1){
	$submittedby = $row2['submittedby'];
	}else{
	$submittedby = $row2['submitted'];	
	}
	}	
if(intval($row2['prodMeasure'])==2 || intval($row2['prodMeasure'])==3){
$pdf->Row2(array($row2['deptName'],$date,$submittedby,$census,$custom1,$custom2,$track1,$track2,$track3,$track4),$deptId);	
}else if(intval($row2['prodMeasure'])!=2 && intval($row2['showEpic'])==1){
$pdf->Row2(array($row2['deptName'],$date,$submittedby,$patients,$gr10,$track1,$track2,$track3,$track4),$deptId);	
}else{
$pdf->Row2(array($row2['deptName'],$date,$submittedby,$patients,$track1,$track2,$track3,$track4),$deptId);
}


}
}
}

if(intval($reportId)==0){
	$fname = "ALLUNITS";
}else if(intval($reportId)==1){
	$fname = "MYUNITS";
}else{
	$fname = substr($data2[0]['deptName'],0,4);
}
$filename=$fname . 'download' . $data2[0]['newDate'] . '.pdf';
$filelocation="/home/prnadmin/public_html/dailyreports/" . $fname . 'download' . $data2[0]['newDate'] . ".pdf";
$pdf->Output($filename,'D');

exit();

}

function getVoice(){

require_once '.././inc/FPDF/fpdf2.php';


class PDF_MC_Table extends FPDF {

var $widths;
var $aligns = array('L','L','L','C','C','L');

function SetWidths($w){
    //Set the array of column widths
    $this->widths=$w;
}

function SetAligns($a){
    //Set the array of column alignments
    $this->aligns=$a;
}

function Row($data2,$dept2){
	
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data2);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data2[$i]));
    $h=5*$nb;
    //Issue a page break first if needed
    $this->CheckPageBreak($h,$dept2);
    //Draw the cells of the row
    for($i=0;$i<count($data2);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : $a;
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
        $this->Rect($x,$y,$w,$h);
        //Print the text
        //$this->MultiCell($w,5,$data2[$i],0,$a);
		$this->MultiCell($w,5,$data2[$i],0,$a);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
}

function CheckPageBreak($h,$deptId){
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation,'',0,$deptId);
}

function NbLines($w,$txt){
    //Computes the number of lines a MultiCell of width w will take
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    //$s=str_replace("\r",'',$txt);
	$s=htmlspecialchars($txt ?? '');
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)
    {
        $c=$s[$i];
        if($c=="\n")
        {
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
            continue;
        }
        if($c==' ')
            $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax)
        {
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
            }
            else
                $i=$sep+1;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
        }
        else
            $i++;
    }
    return $nl;
}

function Header(){
$userId = Config::get('account')['id'];
$accountId = Config::get('account')['accountId'];
$enterpriseId = Config::get('account')['enterpriseId'];
$role = Config::get('account')['role'];
$deptNum = (isset($_REQUEST['r'])) ? $_REQUEST['r'] : 0;

if(intval($deptNum)>0){
	//$newdata = Config::get('db') -> get_results("select a.name as accountName, d.dept as deptName, d.prodMeasure, DATE_FORMAT(u.lastStartDate,'%m/%d/%y') AS startDay, DATE_FORMAT(u.lastEndDate,'%m/%d/%y') AS endDay, a.displayProd, DATE_FORMAT(CURDATE(),'%m/%d/%y') AS reportDate FROM `productiveAccount` a LEFT JOIN `productiveUser` as u on u.id={$userId} left join `ProductiveDept` as d on d.id={$deptNum} where a.id={$accountId} group by a.id");	
	$newdata = Config::get('db') -> get_results("select n.deptId, n.dayDate, a.name as accountName,  DATE_FORMAT(z.lastStartDate,'%m/%d/%y') AS startDay, DATE_FORMAT(z.lastEndDate,'%m/%d/%y') AS endDay, a.displayProd, DATE_FORMAT(CURDATE(),'%m/%d/%y') AS reportDate, (SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END) / SUM(CASE WHEN n.userId>=0 THEN 1 ELSE 0 END) * 100) as reporting, SUM(CASE WHEN n.userId>0 THEN n.nvariance ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END) as varAvg, SUM(CASE WHEN n.userId>0 THEN n.gvariance ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END) as varGrid, SUM(CASE WHEN n.userId>0 and n.atotal>0 and n.shift=d.shift THEN n.atotal ELSE 0 END) / SUM(CASE WHEN n.userId>0 and n.atotal>0 and n.shift=d.shift THEN 1 ELSE 0 END) as censusShift, SUM(CASE WHEN n.userId>0 THEN n.atotal ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END) as censusAvg, SUM(CASE WHEN n.userId>0 THEN n.hrsVariance ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END) as hrsvarAvg, a.displayProd, a.productivityPosNeg, n.actualWHP, a.id as accountId, a.name as accountName, d.dept as deptName, d.prodMeasure, DATE_FORMAT(CURDATE(),'%m%d%y') as filedate, DATE_FORMAT(n.dayDate,'%m/%d/%y') as newDate, u.last_name as Director, i.last_name as Manager, a.id as accountId, ROUND(SUM((CASE WHEN n.actualWHPimport>0 and n.activeRecord>0 then n.hrsVarianceActual ELSE 0 END) + (CASE WHEN n.actualWHPimport=0.000 and n.activeRecord>0 then n.hrsVariance ELSE 0 END)),1) as hrsVarianceTotal, SUM((CASE WHEN n.userId>0 and n.activeRecord>0 THEN 1 ELSE 0 END)) as report, SUM((CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END)) as totalreports, ((SUM(CASE WHEN (n.userId>0 and n.activeRecord>0) THEN 1 ELSE 0 END) + (CASE WHEN n.userId=0 and n.activeRecord>0 and n.planSubmitted=1 THEN 1 ELSE 0 END)) / (SUM((CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END))) * 100) as compliance FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveAccount` as a on a.id=n.accountId left outer join `productiveUser` as i on i.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id AND `primaryUnit`=6 AND `unitAssigned`=0 order by `primaryUnit` ASC LIMIT 0,1) left outer Join `productiveUser` as u on u.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id AND `primaryUnit`=7 order by `primaryUnit` ASC LIMIT 0,1) left join `productiveUser` as z on z.id={$userId} where d.active>0 AND n.deptId={$deptNum} AND n.dayDate>=z.lastStartDate AND n.dayDate <=z.lastEndDate and n.accountId={$accountId} group by n.accountId, n.deptId ORDER BY d.dept ASC");
	$deptName = stripslashes($newdata[0]['deptName']);
	$prodMeasure = $newdata[0]['prodMeasure'];
	}else{
	//$newdata = Config::get('db') -> get_results("select a.name as accountName, DATE_FORMAT(u.lastStartDate,'%m/%d/%y') AS startDay, DATE_FORMAT(u.lastEndDate,'%m/%d/%y') AS endDay, a.displayProd, DATE_FORMAT(CURDATE(),'%m/%d/%y') AS reportDate FROM `productiveAccount` a LEFT JOIN `productiveUser` as u on u.id={$userId} where a.id={$accountId} group by a.id");
	$newdata = Config::get('db') -> get_results("select n.deptId, n.dayDate, a.name as accountName,  DATE_FORMAT(z.lastStartDate,'%m/%d/%y') AS startDay, DATE_FORMAT(z.lastEndDate,'%m/%d/%y') AS endDay, a.displayProd, DATE_FORMAT(CURDATE(),'%m/%d/%y') AS reportDate, (SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END) / SUM(CASE WHEN n.userId>=0 THEN 1 ELSE 0 END) * 100) as reporting, SUM(CASE WHEN n.userId>0 THEN n.nvariance ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END) as varAvg, SUM(CASE WHEN n.userId>0 THEN n.gvariance ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END) as varGrid, SUM(CASE WHEN n.userId>0 and n.atotal>0 and n.shift=d.shift THEN n.atotal ELSE 0 END) / SUM(CASE WHEN n.userId>0 and n.atotal>0 and n.shift=d.shift THEN 1 ELSE 0 END) as censusShift, SUM(CASE WHEN n.userId>0 THEN n.atotal ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END) as censusAvg, SUM(CASE WHEN n.userId>0 THEN n.hrsVariance ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END) as hrsvarAvg, a.displayProd, a.productivityPosNeg, n.actualWHP, a.id as accountId, a.name as accountName, d.dept as deptName, d.prodMeasure, DATE_FORMAT(CURDATE(),'%m%d%y') as filedate, DATE_FORMAT(n.dayDate,'%m/%d/%y') as newDate, u.last_name as Director, i.last_name as Manager, a.id as accountId, ROUND(SUM((CASE WHEN n.actualWHPimport>0 and n.activeRecord>0 then n.hrsVarianceActual ELSE 0 END) + (CASE WHEN n.actualWHPimport=0.000 and n.activeRecord>0 then n.hrsVariance ELSE 0 END)),1) as hrsVarianceTotal, SUM((CASE WHEN n.userId>0 and n.activeRecord>0 THEN 1 ELSE 0 END)) as report, SUM((CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END)) as totalreports, ((SUM(CASE WHEN (n.userId>0 and n.activeRecord>0) THEN 1 ELSE 0 END) + (CASE WHEN n.userId=0 and n.activeRecord>0 and n.planSubmitted=1 THEN 1 ELSE 0 END)) / (SUM((CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END))) * 100) as compliance FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveAccount` as a on a.id=n.accountId left outer join `productiveUser` as i on i.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id AND `primaryUnit`=6 AND `unitAssigned`=0 order by `primaryUnit` ASC LIMIT 0,1) left outer Join `productiveUser` as u on u.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id AND `primaryUnit`=7 order by `primaryUnit` ASC LIMIT 0,1) left join `productiveUser` as z on z.id={$userId} where n.dayDate>=z.lastStartDate AND n.dayDate <=z.lastEndDate and n.accountId={$accountId} group by n.accountId ORDER BY a.name ASC");
	$deptName='';
	$prodMeasure=0;
	}
	$header = array('Unit', 'Shift', 'User', 'RN Variance', 'Grid Variance', 'Note');
    $this->SetFont('Arial','B',14);
	// Move to the right
		// Move to the right
    $this->Cell(120);
    // Title
    $this->Cell(30,10,$newdata[0]['accountName'],0,0,'C');
    // Line break
    $this->Ln(6);
    $this->Cell(120);
	$this->SetFont('Arial','B',12);
	$this->Cell(30,10,'Voice of the Unit',0,0,'C');
	$this->Ln(6);
    $this->Cell(120);
	$this->SetFont('Arial','B',12);
	$this->Cell(30,10,$deptName,0,0,'C');
	// Line break
    $this->Ln(6);
	// Move to the right
    $this->Cell(120);
	$this->SetFont('Arial','B',10);
	$this->Cell(30,10,$newdata[0]['startDay'] . ' - ' . $newdata[0]['endDay'],0,0,'C');
	//$this->Cell(30,10,'Date: ' . $newdata[0]['reportDate'],0,0,'C');
	$this->Ln(6);
	$this->Cell(120);
	$this->SetFont('Arial','B',10);
	// Line break
    $this->Ln(15);
	// Column widths
    $w = array(45, 40, 30, 25, 25, 100);
    // Header
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C');
    $this->Ln();
    // Data
    
}

// Page footer
function Footer(){
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
}



}
	
$pdf = new PDF_MC_Table();

$userId = Config::get('account')['id'];
$accountId = Config::get('account')['accountId'];
$enterpriseId = Config::get('account')['enterpriseId'];
$role = Config::get('account')['role'];
$reportId = (isset($_REQUEST['r'])) ? $_REQUEST['r'] : 0;

 if(intval($reportId)==1){
		$searchAdd = "AND d.active>0 AND d.id in (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId} and `primaryUnit`>0) AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate";
		$newId = '';
	}else if(intval($reportId)==0){
		$searchAdd = "AND d.active>0 AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate";
		$newId = '';
	}else{
		$searchAdd = "AND n.deptId={$reportId} AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate";	
		$newId = $reportId;
	}

$getDept = Config::get('db') -> get_results("SELECT n.deptId, n.note, n.dayDate, d.active, d.dept, d.prodMeasure1, a.name as accountName, a.displayProd, u.lastStartDate, u.lastEndDate, DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%m/%d/%y') as reportDate from `productiveNewData` n LEFT JOIN `productiveAccount` as a on a.id=n.accountId LEFT JOIN `ProductiveDept` as d on d.id=n.deptId left join `productiveUser` as u on u.id={$userId} WHERE n.accountId={$accountId} AND LENGTH(n.note)>5 $searchAdd GROUP BY n.deptId ORDER BY n.dayDate DESC, d.dept ASC");

if($getDept){
$getDept1 = array();
$getDept1 = $getDept;
}else{
$getDept1[] = array(
				'deptId' => $reportId
            );
}

$pdf->AliasNbPages();
$pdf->SetFont('Arial','',9);
$pdf->AddPage('L','',0,$newId);
$pdf->SetWidths(array(45, 40, 30, 25, 25, 100));
	
foreach($getDept1 as $newData){

$deptId = $newData['deptId'];

$data2 = Config::get('db') -> get_results("select n.*, u.lastStartDate, u.lastEndDate, d.prodMeasure1, o.practiceId, DATE_FORMAT(n.dayDate,'%m/%d') as newDate, s.shift as shiftName, o.last_name as submitted, d.dept as deptName, DATE_FORMAT(CURDATE(),'%m%d%y') as filedate, a.name as accountName FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveShifts` as s on s.id = n.shift left join `productiveAccount` as a on a.id=n.accountId left outer join `productiveUser` as o on o.id=n.userId left join `productiveUser` as u on u.id={$userId} where n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate AND LENGTH(n.note)>5 AND n.deptId={$deptId} group by n.accountId, n.deptId, n.dayDate, n.shift ORDER BY d.dept ASC, n.dayDate DESC, n.shift DESC");


foreach($data2 as $row2){
	$date = $row2['newDate'] . ' - ' . $row2['shiftName']; 
	$deptId = $row2['deptId'];
	if(strlen($row2['submittedby'] ?? '')>0){
	$submittedby = $row2['submittedby'];
	}else{
	$submittedby = $row2['submitted'];	
	}
	$deptName = iconv('UTF-8', 'windows-1252', $row2['deptName']);
	$note = iconv('UTF-8', 'windows-1252', $row2['note']);
	
	$pdf->Row(array($deptName,$date,$submittedby,ROUND($row2['nvariance'],1),ROUND($row2['gvariance'],1),$note),$deptId);


}
}

if(intval($reportId)==0){
	$fname = "ALLUNITSVOICE";
}else if(intval($reportId)==1){
	$fname = "MYUNITSVOICE";
}else{
	$fname = substr($data2[0]['deptName'] . "VOICE",0,4);
}
$filename=$fname . 'download' . $data2[0]['newDate'] . '.pdf';
$filelocation="/home/prnadmin/public_html/dailyreports/" . $fname . 'download' . $data2[0]['newDate'] . ".pdf";
$pdf->Output($filename,'D');

exit();

}



function getEscalation(){

require_once '.././inc/FPDF/fpdf2.php';


class PDF_MC_Table extends FPDF {

var $widths;
var $aligns = array('L','L','L','L','L','L');

function SetWidths($w){
    //Set the array of column widths
    $this->widths=$w;
}

function SetAligns($a){
    //Set the array of column alignments
    $this->aligns=$a;
}

function Row($data2,$dept2){
	
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data2);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data2[$i]));
    $h=5*$nb;
    //Issue a page break first if needed
    $this->CheckPageBreak($h,$dept2);
    //Draw the cells of the row
    for($i=0;$i<count($data2);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : $a;
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
        $this->Rect($x,$y,$w,$h);
        //Print the text
        //$this->MultiCell($w,5,$data2[$i],0,$a);
		$this->MultiCell($w,5,$data2[$i],0,$a);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
}

function CheckPageBreak($h,$deptId){
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation,'',0,$deptId);
}

function NbLines($w,$txt){
    //Computes the number of lines a MultiCell of width w will take
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r",'',$txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)
    {
        $c=$s[$i];
        if($c=="\n")
        {
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
            continue;
        }
        if($c==' ')
            $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax)
        {
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
            }
            else
                $i=$sep+1;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
        }
        else
            $i++;
    }
    return $nl;
}

function Header(){
$userId = Config::get('account')['id'];
$accountId = Config::get('account')['accountId'];
$enterpriseId = Config::get('account')['enterpriseId'];
$role = Config::get('account')['role'];
$deptNum = (isset($_REQUEST['r'])) ? $_REQUEST['r'] : 0;

if(intval($deptNum)>0){	
	$newdata = Config::get('db') -> get_results("select d.*, a.name as accountName, u.lastStartDate as startDay, u.lastEndDate as endDay FROM `ProductiveDept` d LEFT JOIN `productiveAccount` as a on a.id=d.accountId LEFT JOIN `productiveUser` as u on u.id={$userId} WHERE d.id={$deptNum}");
	$deptName = stripslashes($newdata[0]['dept']);
	}else{
	$newdata = Config::get('db') -> get_results("select a.name as accountName, u.lastStartDate as startDay, u.lastEndDate as endDay FROM `productiveAccount` a LEFT JOIN `productiveUser` as u on u.id={$userId} WHERE a.id={$accountId}");
	$deptName='';
	}
	$header = array('Date/Time', 'Unit', 'Submitted By', 'Type', 'Comments');
    $this->SetFont('Arial','B',14);
	// Move to the right
		// Move to the right
    $this->Cell(120);
    // Title
    $this->Cell(30,10,$newdata[0]['accountName'],0,0,'C');
    // Line break
    $this->Ln(6);
    $this->Cell(120);
	$this->SetFont('Arial','B',12);
	$this->Cell(30,10,'Escalation Summary',0,0,'C');
	$this->Ln(6);
    $this->Cell(120);
	$this->SetFont('Arial','B',12);
	$this->Cell(30,10,$deptName,0,0,'C');
	// Line break
    $this->Ln(6);
	// Move to the right
    $this->Cell(120);
	$this->SetFont('Arial','B',10);
	if($newdata[0]['startDay'] == $newdata[0]['endDay']){
	$this->Cell(30,10,$newdata[0]['startDay'],0,0,'C');	
	}else{
	$this->Cell(30,10,$newdata[0]['startDay'] . ' - ' . $newdata[0]['endDay'],0,0,'C');
	}
	//$this->Cell(30,10,'Date: ' . $newdata[0]['reportDate'],0,0,'C');
	$this->Ln(6);
	$this->Cell(120);
	$this->SetFont('Arial','B',10);
	// Line break
    $this->Ln(15);
	// Column widths
    $w = array(40, 40, 30, 40, 120);
    // Header
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C');
    $this->Ln();
    // Data
    
}

// Page footer
function Footer(){
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
}



}
	
$pdf = new PDF_MC_Table();

$userId = Config::get('account')['id'];
$accountId = Config::get('account')['accountId'];
$enterpriseId = Config::get('account')['enterpriseId'];
$role = Config::get('account')['role'];
$reportId = (isset($_REQUEST['r'])) ? $_REQUEST['r'] : 0;


 if(intval($reportId)==1){
		$searchAdd = "WHERE e.active>0 AND e.accountId ={$accountId} AND d.active>0 AND d.id in (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId} and `primaryUnit`>0) AND DATE(e.dateSubmitted) >=y.lastStartDate AND DATE(e.dateSubmitted) <=y.lastEndDate";
		$newId = '';
	}else if(intval($reportId)==0){
		$searchAdd = "WHERE e.active>0 AND e.accountId ={$accountId} AND d.active>0 AND DATE(e.dateSubmitted) >=y.lastStartDate AND DATE(e.dateSubmitted) <=y.lastEndDate";
		$newId = '';
	}else{
		$searchAdd = "WHERE e.active>0 AND e.accountId ={$accountId} AND e.deptId={$reportId} AND DATE(e.dateSubmitted) >=y.lastStartDate AND DATE(e.dateSubmitted) <=y.lastEndDate";	
		$newId = $reportId;
	}

//$getDept = Config::get('db') -> get_results("SELECT n.*, d.active, d.dept from `productiveEscalations` n LEFT JOIN `ProductiveDept` as d on d.id=n.deptId left join `productiveUser` as u on u.id={$userId} WHERE n.accountId={$accountId} $searchAdd GROUP BY n.deptId ORDER BY d.dept ASC");

//if($getDept){


	
//foreach($getDept as $newData){

//$deptId = $newData['deptId'];
//$data2 = Config::get('db') -> get_results("select e.*, c.last_name as closedLast, c.first_name as closedFirst, a.escalation as escType, IFNULL(n.submittedby,'') as submittedby, d.dept, u.last_name, u.first_name FROM `productiveEscalations` e LEFT OUTER JOIN `productiveUser` as c on c.id = e.closedId LEFT JOIN `productiveAcctEscalations` as a on a.id=e.escalation LEFT OUTER JOIN `productiveNewData` as n on n.id=e.dataId LEFT JOIN `productiveUser` as y on y.id = {$userId} LEFT JOIN `ProductiveDept` as d on d.id=e.deptId LEFT OUTER JOIN `productiveUser` as u on u.id=e.userId WHERE DATE(e.dateSubmitted) >=y.lastStartDate AND DATE(e.dateSubmitted) <=y.lastEndDate AND e.deptId={$deptId} group by e.id ORDER BY e.dateSubmitted DESC");

$data2 = Config::get('db') -> get_results("select e.*, DATE_FORMAT(e.dateSubmitted,'%m%d%y') as newDate, c.last_name as closedLast, c.first_name as closedFirst, a.escalation as escType, IFNULL(n.submittedby,'') as submittedby, d.dept, u.last_name, u.first_name FROM `productiveEscalations` e LEFT OUTER JOIN `productiveUser` as c on c.id = e.closedId LEFT JOIN `productiveAcctEscalations` as a on a.id=e.escalation LEFT OUTER JOIN `productiveNewData` as n on n.id=e.dataId LEFT JOIN `productiveUser` as y on y.id = {$userId} LEFT JOIN `ProductiveDept` as d on d.id=e.deptId LEFT OUTER JOIN `productiveUser` as u on u.id=e.userId $searchAdd group by e.id ORDER BY e.dateSubmitted DESC");

if($data2){
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',9);
$pdf->AddPage('L','',0,$newId);
$pdf->SetWidths(array(40, 40, 30, 40, 120));
	
foreach($data2 as $row2){
	$date = $row2['dateSubmitted']; 
	$deptId = $row2['deptId'];
	$escId = $row2['id'];
	$dept = stripslashes($row2['dept']);
	if(strlen($row2['submittedby'] ?? '')>0){
	$submittedby = $row2['submittedby'];
	}else{
	$submittedby = $row2['first_name'] . ' ' . $row2['last_name'];	
	}
	if(intval($row2['active'])==2){
			$type = $row2['escType'] . " (Closed by " . $row2['closedFirst'] . " " . $row2['closedLast'] . " on " . $row2['closedDate'] . ")";
			}else{
			$type = $row2['escType'];
			}
	$comment = '';
	$comment .= '';

	/*
	$respData = Config::get('db') -> get_results("select r.*, u.last_name, u.first_name FROM `productiveEscResponse` r LEFT OUTER JOIN `productiveUser` as u on u.id=r.userId WHERE r.escId={$escId} AND r.response !='' GROUP BY r.id ORDER BY r.responseTime DESC");
	if($respData){	
		foreach($respData as $resp){
		$comment .= $resp['responseTime'] . ' - ' . $resp['first_name'] . ' ' . $resp['last_name'] . ': ' . $resp['response'] . ' < > ';
		}
	}
	*/
	$pdf->Row(array($date,$dept,$submittedby,$type,$row2['note']),$deptId);


}
//}

if(intval($reportId)==0){
	$fname = "ALLUNITSESC";
}else if(intval($reportId)==1){
	$fname = "MYUNITSESC";
}else{
	$fname = substr($data2[0]['dept'] . "ESC",0,4);
}
$filename=$fname . 'download' . $data2[0]['newDate'] . '.pdf';
$filelocation="/home/prnadmin/public_html/dailyreports/" . $fname . 'download' . $data2[0]['newDate'] . ".pdf";
$pdf->Output($filename,'D');

exit();
}
}



/////////////////////////////////////
// GET CSV MAP INFO
function GetCSVTableData() {
	$userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
	//$deptId = Config::get('account')['deptid'];
	$role = Config::get('account')['role'];
	$enterpriseId = Config::get('account')['enterpriseId'];
	$column8 ="";
	$column9 ="";
	$column10 ="";
	$column11 ="";
	$column12 ="";
	$column13 ="";
	$column14 ="";
	$column15 ="";
	$column16 ="";
	$column17 ="";
	$column18 ="";
	$column19 ="";
	$column20 ="";
	$column21 ="";
	$column22 ="";
	$column23 ="";
	$column24 ="";
	$column25 ="";
	$column27 ="";
	$column28 ="";
	$column29 ="";
	$column30 ="";
	$column31 ="";
	$column32 ="";
	$column33 ="";
	$column34 ="";
	$column35 ="";
	$column36 ="";
	$column37 ="";
	$column50 ="";
	$column51 ="";	
	$column52 ="";
	$column53 ="";	
	$column54 ="";
	$column55 ="";
	$column56 ="";	
	$column57 ="";
	$column58 ="";
	$column59 ="";
	$column60 ="";
	$column61 ="";
	$column62 ="";
	$column63 ="";
	$column64 ="";	
	$column65 ="";	
	$column66 ="";	
	$column68 ="";
	$column69 ="";
	$column70 ="";	
	$column71 ="";	
	$column72 ="";
	$column73 ="";	
	$column74 ="";
	$column75 ="";
	$column76 ="";	
	$column77 ="";
	$column78 ="";
	$column79 ="";
	$column80 ="";
	$column81 ="";
	$column82 ="";
	$column83 ="";
	$column84 ="";
	$column85 ="";
	$column86 ="";
	$column87 ="";	
	$column88 ="";
	$column89 ="";
	$column90 ="";	
	$column91 ="";
	$column92 ="";
	$column93 ="";
	$column94 ="";
	
	$column10 .="";
	$column13 .="";
	$column14 .="";
	$column15 .="";
	$column16 .="";
	$column23 .="";
	$column24 .="";
	$column25 .="";
	$column27 .="";
	$column28 .="";
	$column29 .="";
	$column30 .="";
	$column31 .="";
	$column32 .="";
	$column33 .="";
	$column34 .="";
	$column35 .="";
	$column36 .="";
	$column37 .="";
	$column50 .="";	
	$column51 .="";	
	$column52 .="";
	$column53 .="";	
	$column54 .="";
	$column55 .="";
	$column56 .="";	
	$column57 .="";
	$column58 .="";
	$column59 .="";
	$column60 .="";
	$column61 .="";
	$column62 .="";
	$column63 .="";
	$column64 .="";	
	$column68 .="";
	$column69 .="";
	$column70 .="";	
	$column71 .="";	
	$column72 .="";
	$column73 .="";	
	$column74 .="";
	$column75 .="";
	$column76 .="";	
	$column77 .="";
	$column78 .="";
	$column79 .="";
	$column80 .="";
	$column81 .="";
	$column82 .="";
	$column83 .="";
	$column84 .="";
	$column85 .="";
	$column86 .="";
	$column87 .="";	
	$column88 .="";
	$column89 .="";
	$column90 .="";	
	$column91 .="";
	$column92 .="";
	$column93 .="";
	$column94 .="";
	
	
	if(intval($role)<=7){
		$searchAdd = " AND d.id in (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId} and `primaryUnit`>0)";
	}else if(intval($role)>7 && intval($role)<=12){
		$searchAdd = " AND d.accountId={$accountId}";
	}else{
		$searchAdd = " AND a.enterpriseId={$enterpriseId}";
	}
	
	$dbData = Config::get('db')->get_results("SELECT d.* FROM `ProductiveDept` d WHERE d.active>0$searchAdd GROUP BY d.id");
    
	//$dbDept = Config::get('db')->get_results("n.procedureCount as support_UOS, n.skill1val as support_hours, n.skill2val as support_rn1_hours, n.skill3val as support_rn2_hours, n.skill4val as support_tech1_hours, n.skill5val as support_tech2_hours, n.skill6val as support_admin_hours, n.skill7val as support_custom1_hours, n.skill8val as support_custom2_hours, n.skill9val as support_custom3_hours, n.skill10val as support_other_hours, n.hrsVariance as hrs_variance_submitted, n.actualWHP as WHPUOS_submitted, n.nproductivity as prod_submitted, n.hrsVarianceActual as hrs_variance_imported, n.actualWHPimport as WHPUOS_imported, n.totalHours as totalHrs_imported, n.addResourceHrs as indirect_hrs, IFNULL(l.escalation,'') as escalation_Type, IFNULL(c.note,'') as escalation_Note, n.admits as churn_admits, n.discharges as churn_discharges, n.transfers as churn_transfers, n.customChurn as churn_custom FROM `productiveNewData` n LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as a on a.id = n.deptId LEFT JOIN `productiveMeasures` as m on m.id=a.prodMeasure LEFT JOIN `productiveAccount` as t on t.id = a.accountId LEFT JOIN `productiveUser` as u on u.id ={$userId} LEFT OUTER JOIN `productiveUser` as i on i.id=n.userId LEFT OUTER JOIN `productiveEscalations` as c on c.dataId = n.id LEFT OUTER JOIN `productiveAcctEscalations` as l on l.id = c.escalation WHERE n.userId>0 AND n.activeRecord>0 AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate $searchAdd$searchAdd2$searchAdd3 group by n.deptId, n.dayDate, n.shift$orderBy");
    
    if($dbData){
		$jobArray = array();
		$deptDesc = array();
		foreach($dbData as $ante){
			
			if(strlen($ante['nurse1Desc'] ?? '')>0){
			$column10 .= stripslashes($ante['dept']) . "(" . $ante['nurse1Desc'] . "), ";
			}else{
			//do nothing;
			}
			if(strlen($ante['chargeDesc'] ?? '')>0){
			$column8 .= stripslashes($ante['dept']) . "(" . $ante['chargeDesc'] . "), ";
			}else{
			//do nothing;
			}
			if(strlen($ante['nurseDesc'] ?? '')>0){
			$column9 .= stripslashes($ante['dept']) . "(" . $ante['nurseDesc'] . "), ";
			}else{
			//do nothing;
			}
			if(strlen($ante['sittersNEWDesc'] ?? '')>0){
			$column13 .= stripslashes($ante['dept']) . "(" . $ante['sittersNEWDesc'] . "), ";
			}else{
			//do nothing;
			}
			if(strlen($ante['other1Desc'] ?? '')>0){
			$column14 .= stripslashes($ante['dept']) . "(" . $ante['other1Desc'] . "), ";
			}else{
			//do nothing;
			}
			if(strlen($ante['other2Desc'] ?? '')>0){
			$column15 .= stripslashes($ante['dept']) . "(" . $ante['other2Desc'] . "), ";
			}else{
			//do nothing;
			}
			if(strlen($ante['other3Desc'] ?? '')>0){
			$column16 .= stripslashes($ante['dept']) . "(" . $ante['other3Desc'] . "), ";
			}else{
			//do nothing;
			}
			if(strlen($ante['desc1'] ?? '')>0){
			$column17 .= stripslashes($ante['dept']) . "(" . $ante['desc1'] . "), ";
			}else{
			//do nothing;
			}
			if(strlen($ante['desc2'] ?? '')>0){
			$column18 .= stripslashes($ante['dept']) . "(" . $ante['desc2'] . "), ";
			}else{
			//do nothing;
			}
			if(strlen($ante['desc3'] ?? '')>0){
			$column19 .= stripslashes($ante['dept']) . "(" . $ante['desc3'] . "), ";
			}else{
			//do nothing;
			}
			if(strlen($ante['desc4'] ?? '')>0){
			$column20 .= stripslashes($ante['dept']) . "(" . $ante['desc4'] . "), ";
			}else{
			//do nothing;
			}
			if(strlen($ante['desc5'] ?? '')>0){
			$column21 .= stripslashes($ante['dept']) . "(" . $ante['desc5'] . "), ";
			}else{
			//do nothing;
			}
			if(strlen($ante['desc6'] ?? '')>0){
			$column22 .= stripslashes($ante['dept']) . "(" . $ante['desc6'] . "), ";
			}else{
			//do nothing;
			}
			if(strlen($ante['desc7'] ?? '')>0){
			$column23 .= stripslashes($ante['dept']) . "(" . $ante['desc7'] . "), ";
			$columnP1 = 'Value for pt1Info';
			}else{
			$columnP1 = 'Value for pt1Info';
			//do nothing;
			}
			if(strlen($ante['desc8'] ?? '')>0){
			$column24 .= stripslashes($ante['dept']) . "(" . $ante['desc8'] . "), ";
			$columnP2 = 'Value for pt2Info';
			}else{
			$columnP2 = 'Value for pt2Info';
			//do nothing;
			}
			if(strlen($ante['desc9'] ?? '')>0){
			$column25 .= stripslashes($ante['dept']) . "(" . $ante['desc9'] . "), ";
			$columnP3 = 'Value for pt3Info';
			}else{
			$columnP3 = 'Value for pt3Info';
			//do nothing;
			}
			if(strlen($ante['uosDesc'] ?? '')>0){
			$column27 .= stripslashes($ante['dept']) . "(" . $ante['uosDesc'] . "), ";
			}else{
			//do nothing;
			}
			if(strlen($ante['skilldesc1'] ?? '')>0){
			$column28 .= stripslashes($ante['dept']) . "(" . $ante['skilldesc1'] . "), ";
			}else{
			//do nothing;
			}
			if(strlen($ante['skilldesc2'] ?? '')>0){
			$column29 .= stripslashes($ante['dept']) . "(" . $ante['skilldesc2'] . "), ";
			}else{
			//do nothing;
			}
			if(strlen($ante['skilldesc3'] ?? '')>0){
			$column30 .= stripslashes($ante['dept']) . "(" . $ante['skilldesc3'] . "), ";
			}else{
			//do nothing;
			}
			if(strlen($ante['skilldesc4'] ?? '')>0){
			$column31 .= stripslashes($ante['dept']) . "(" . $ante['skilldesc4'] . "), ";
			}else{
			//do nothing;
			}
			if(strlen($ante['skilldesc5'] ?? '')>0){
			$column32 .= stripslashes($ante['dept']) . "(" . $ante['skilldesc5'] . "), ";
			}else{
			//do nothing;
			}
			if(strlen($ante['skilldesc6'] ?? '')>0){
			$column33 .= stripslashes($ante['dept']) . "(" . $ante['skilldesc6'] . "), ";
			}else{
			//do nothing;
			}
			if(strlen($ante['skilldesc7'] ?? '')>0){
			$column34 .= stripslashes($ante['dept']) . "(" . $ante['skilldesc7'] . "), ";
			}else{
			//do nothing;
			}
			if(strlen($ante['skilldesc8'] ?? '')>0){
			$column35 .= stripslashes($ante['dept']) . "(" . $ante['skilldesc8'] . "), ";
			}else{
			//do nothing;
			}
			if(strlen($ante['skilldesc9'] ?? '')>0){
			$column36 .= stripslashes($ante['dept']) . "(" . $ante['skilldesc9'] . "), ";
			}else{
			//do nothing;
			}
			if(strlen($ante['skilldesc10'] ?? '')>0){
			$column37 .= stripslashes($ante['dept']) . "(" . $ante['skilldesc10'] . "), ";
			}else{
			//do nothing;
			}
			if(strlen($ante['customChurnLabel'] ?? '')>0){
			$column50 .= stripslashes($ante['dept']) . "(" . $ante['customChurnLabel'] . "), ";
			}else{
			//do nothing;
			}
			if(strlen($ante['customDesc'] ?? '')>0){
			$column51 .= stripslashes($ante['dept']) . "(" . $ante['customDesc'] . "), ";
			}else{
			//do nothing;
			}
			if(strlen($ante['customDesc2'] ?? '')>0){
			$column56 .= stripslashes($ante['dept']) . "(" . $ante['customDesc2'] . "), ";
			}else{
			//do nothing;
			}
			if(strlen($ante['customDesc3'] ?? '')>0){
			$column57 .= stripslashes($ante['dept']) . "(" . $ante['customDesc3'] . "), ";
			}else{
			//do nothing;
			}
			if(strlen($ante['nurse2Desc'] ?? '')>0){
			$column65 .= stripslashes($ante['dept']) . "(" . $ante['nurse2Desc'] . "), ";
			}else{
			//do nothing;
			}
			if(strlen($ante['desc10'] ?? '')>0){
			$column66 .= stripslashes($ante['dept']) . "(" . $ante['desc10'] . "), ";
			$columnP4 = 'Value for pt4Info';
			}else{
			$columnP4 = 'Value for pt4Info';
			//do nothing;
			}
			if(strlen($ante['techLabel'] ?? '')>0){
			$column11 .= stripslashes($ante['dept']) . "(" . $ante['techLabel'] . "), ";
			}else{
			//do nothing;
			}
			if(strlen($ante['secLabel'] ?? '')>0){
			$column12 .= stripslashes($ante['dept']) . "(" . $ante['secLabel'] . "), ";
			}else{
			//do nothing;
			}
			if(strlen($ante['track1Desc'] ?? '')>0){
			$column83 .= stripslashes($ante['dept']) . "(" . $ante['track1Desc'] . "), ";
			$columnT1 = 'Value for track1Info';
			}else{
			$columnT1 = 'Value for track1Info';
			//do nothing;
			}
			if(strlen($ante['track2Desc'] ?? '')>0){
			$column84 .= stripslashes($ante['dept']) . "(" . $ante['track2Desc'] . "), ";
			$columnT2 = 'Value for track2Info';
			}else{
			$columnT2 = 'Value for track2Info';
			//do nothing;
			}
			if(strlen($ante['track3Desc'] ?? '')>0){
			$column85 .= stripslashes($ante['dept']) . "(" . $ante['track3Desc'] . "), ";
			$columnT3 = 'Value for track3Info';
			}else{
			$columnT3 = 'Value for track3Info';
			//do nothing;
			}
			if(strlen($ante['track4Desc'] ?? '')>0){
			$column86 .= stripslashes($ante['dept']) . "(" . $ante['track4Desc'] . "), ";
			$columnT4 = 'Value for track4Info';
			}else{
			$columnT4 = 'Value for track4Info';
			//do nothing;
			}
		}	
		$deptDesc[] = array(
		'1' => '','2' => '','3' => '','4' => '','5' => '','6' => '','7' => '','8' => $column8,'9' => $column9,'10' => $column10,'11' => $column11,'12' => $column12,
		'13' => $column13,'14' => $column14,'15' => $column15,'16' => $column16,'17' => $column17,'18' => $column18,'19' => $column19,'20' => $column20,'21' => $column21,'22' => $column22,'23' => $columnP1,'24' => $columnP2,
		'25' => $columnP3,'26' => '','27' => $column27,'28' => $column28,'29' => $column29,'30' => $column30,'31' => $column31,'32' => $column32,'33' => $column33,'34' => $column34,'35' => $column35,'36' => $column36,
		'37' => $column37,'38' => '','39' => '','40' => '','41' => '','42' => '','43' => '','44' => '','45' => '','46' => '','47' => '','48' => '',
		'49' => '','50' => $column50,'51' => $column51,'52' => '','53' => '','54' => '','55' => '','56' => $column56,'57' => $column57,'58' => '',
		'59' => '','60' => '','61' => '','62' => '','63' => '','64' => '','65' => $column65,'66' => $columnP4,'67' => '','68' => '','69' => '','70' => '','71' => '','72' => '','73' => '','74' => '','75' => '','76' => '','77' => '',
		'78' => '','79' => '','80' => $column80,'81' => '','82' => '','83' =>$columnT1,'84' => $columnT2,'85' => $columnT3,'86' => $columnT4,'87' => '','88' => '','89' => '','90' => '','91' => '','92' => '','93' => '','94' => '','95' => '','96' => '','97' => '','98' => '','99' => '','100' => '','101' =>'','104' => $column83,'105' => $column84,'106' => $column85,'107' => $column86,'108' => $column23,'109' => $column24,'110' => $column25,'111' => $column66);
		
		//$dbkeys = array('unit_Name','unit_ID','prod_Measure','UOS_Value','date_shift','date_submitted','submitted_by','nursing_charge_hours','nursing_rn_hours','nursing_custom_rn_hours','nursing_tech_hours','nursing_admin_hours','nursing_sitter_hours','nursing_custom1_hours','nursing_custom2_hours','nursing_custom3_hours','nursing_acuity_1to1','nursing_acuity_1to2','nursing_acuity_1to3','nursing_acuity_1to4','nursing_acuity_1to5','nursing_acuity_1to6','nursing_acuity_custom1','nursing_acuity_custom2','nursing_acuity_custom3','nursing_total_pts','support_UOS','','support_hours','support_rn1_hours','support_rn2_hours','support_tech1_hours','support_tech2_hours','support_admin_hours','support_custom1_hours','support_custom2_hours','support_custom3_hours','support_other_hours','hrs_variance_submitted','WHPUOS_submitted','prod_submitted','hrs_variance_imported','WHPUOS_imported','totalHrs_imported','indirect_hrs','escalation_Type','escalation_Note','churn_admits','churn_discharges','churn_transfers','churn_custom');
		$dbkeys = Config::get('db')->get_results("SELECT * FROM `productiveCSVColumns` order by `id` asc");
		$i=0;
		foreach($dbkeys as $keys){
			$i=$i+1;
			$jobArray[] = array(
				'column' => $keys['column_Name'],
				'unit' => $keys['column_Value'].$deptDesc[0][''.$i.''],
				'desc' => $keys['column_Desc']
            );
			
		}	
			//$outJobs[] = $ante;
         	
		$outData = array();
		$outData['data'] = $jobArray;
		echo json_encode(array('success' => true,'data'=>$jobArray,'deptDesc'=>$deptDesc));
		}else {
        $outData = array();
		$jobArray[] = array(
				'column' => '',
				'unit' => '',
				'desc' => ''
            );
        $outData['data'] = $jobArray;
        echo json_encode($outData,JSON_NUMERIC_CHECK);
		}
}

/////DOWNLOAD REPORT DATA TO CSV

function DownloadCSV() {
	//ini_set('memory_limit', '-1');
	$idsearch = isset($_REQUEST['id']) && ($_REQUEST['id'] != 0) ? $_REQUEST['id'] : '';
    //$user = isset($_REQUEST['user']) ? $_REQUEST['user'] : '';
	$categoryId = isset($_REQUEST['category']) ? intval($_REQUEST['category']) : 0;
    $userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
	$enterpriseId = Config::get('account')['enterpriseId'];
	//$deptId = Config::get('account')['deptid'];
    $role = Config::get('account')['role'];
	$total = 0;
    $variance = 0;
	$deptCount = false;

	if(intval($role)<8){
		$searchAdd = "AND x.userId={$userId})";
		//$depts = Config::get('db')->get_results("SELECT COUNT(DISTINCT(`deptId`)) as deptCount FROM `productiveDeptXref` where `userId`={$userId}");
		//$deptCount = intval($depts[0]['deptCount']);
	}else if(intval($role)>7 && intval($role)<=10) {
		$searchAdd = "AND d.accountId={$accountId} AND d.active>0";
		//$deptCount = 2;
	}else if(intval($role)>10 && intval($role)<=12) {
		$searchAdd = "AND d.accountId={$accountId}";
		//$deptCount = 2;
	}else if(intval($role)>12 && intval($role)<100) {
		$searchAdd = "AND d.accountId={$enterpriseId} AND d.active>0";
		//$deptCount = 2;
	}else{
		$searchAdd = '';
	}

	
	//$dbData2 = Config::get('db')->get_results("SELECT a.accountId as account_ID, a.unitId as unit_ID, a.dept as unit_Name, m.name as prod_Measure, a.hppd as UOS_Value, CONCAT(DATE_FORMAT(n.dayDate,'%m/%d/%y'), '  ', s.shift) as date_shift, n.entered as date_submitted, CONCAT(SUBSTRING(i.first_name,1,1), '. ', i.last_name) as submitted_by, n.chargecount as nursing_charge_hours, (n.antecount+n.ldcount+n.ocount) as nursing_rn_hours, n.customNurse as nursing_custom_rn_hours, n.techcount as nursing_tech_hours, n.seccount as nursing_admin_hours, n.sittercount as nursing_sitter_hours, n.otherNurse1 as nursing_custom1_hours, n.otherNurse2 as nursing_custom2_hours, n.otherNurse3 as nursing_custom3_hours, n.oneto1 as nursing_acuity_1to1, n.oneto2 as nursing_acuity_1to2, n.oneto3 as nursing_acuity_1to3, n.oneto4 as nursing_acuity_1to4, n.oneto5 as nursing_acuity_1to5, n.oneto6 as nursing_acuity_1to6, n.oneto7 as nursing_acuity_custom1, n.oneto8 as nursing_acuity_custom2, n.oneto9 as nursing_acuity_custom3, (n.oneto1+n.oneto2+n.oneto3+n.oneto4+n.oneto5+n.oneto6) as nursing_total_pts, n.procedureCount as support_UOS, n.whpCustom as customUOS1, n.whpCustom2 as customUOS2, n.whpCustom3 as customUOS3, n.skill1val as support_hours, n.skill2val as support_rn1_hours, n.skill3val as support_rn2_hours, n.skill4val as support_tech1_hours, n.skill5val as support_tech2_hours, n.skill6val as support_admin_hours, n.skill7val as support_custom1_hours, n.skill8val as support_custom2_hours, n.skill9val as support_custom3_hours, n.skill10val as support_other_hours, n.skill11val as support_provider_hours, n.hrsVariance as hrs_variance_submitted, n.actualWHP as WHPUOS_submitted, n.nproductivity as prod_submitted, n.hrsVarianceActual as hrs_variance_imported, n.actualWHPimport as WHPUOS_imported, n.totalHours as totalHrs_imported, n.addResourceHrs as indirect_hrs, IFNULL(l.escalation,'') as escalation_Type, IFNULL(c.note,'') as escalation_Note, n.admits as churn_admits, n.discharges as churn_discharges, n.transfers as churn_transfers, n.customChurn as churn_custom, n.visitsSubmitted, n.hoursSubmitted, n.hrs1Submitted, n.hrs2Submitted, n.hrs3Submitted, n.note as report_notes, (select count(`dept`) from `productiveAccountXref` where `type`<2 AND `whpPlan` !=1 and `dept`=n.deptId) as shift_count, r.dayRank as SD, r.empEngagement as EE, n.deptId as deptID, n.type as Provider, r.dateNew, r.dateExisting, n.provId as provName, n.id as recId, a.escalations as escResponse, a.escalations as escResponder, r.referrals, n.procedureCount as totalVisits FROM `productiveNewData` n LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as a on a.id = n.deptId LEFT JOIN `productiveMeasures` as m on m.id=a.prodMeasure LEFT JOIN `productiveAccount` as t on t.id = a.accountId LEFT JOIN `productiveUser` as u on u.id ={$userId} LEFT OUTER JOIN `productiveUser` as i on i.id=n.userId LEFT OUTER JOIN `productiveEscalations` as c on c.dataId = n.id LEFT OUTER JOIN `productiveDayRank` as r on r.dayDate=n.dayDate AND r.deptId=n.deptId LEFT OUTER JOIN `productiveAcctEscalations` as l on l.id = c.escalation WHERE n.userId>0 AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate $searchAdd group by n.deptId order by a.dept ASC");
    $dbData2 = Config::get('db')->get_results("SELECT x.*, d.dept, d.active, (SELECT COUNT(`dept`) from `productiveAccountXref` where `dept`=x.deptId AND `whpPlan`=0) as shift_count FROM `productiveDeptXref` x LEFT JOIN `ProductiveDept` as d on d.id=x.deptId where d.active>0 $searchAdd group by x.deptId order by d.dept ASC");
    $deptCount = count($dbData2);
	
	$csv_filename = "download_" . date('Ymd') . ".csv";
	$output = fopen("php://output",'w') or die("Can't open php://output");
	//header('Content-Type: application/csv;
    header("Content-Disposition: attachment; filename=".$csv_filename."");
	$out = array();
	
	if(INTVAL($deptCount)!=1) {
		//$firstRow = $dbData2[0];
	    //$keys = array_keys($firstRow);
		$keys = array(
		'account_ID',
		'unit_ID',
		'unit_Name',
		'prod_Measure',
		'UOS_Value',
		'date_shift',
		'date_submitted',
		'submitted_by',
		'nursing_charge_hours',
		'nursing_rn_hours',
		'nursing_custom_rn_hours',
		'nursing_tech_hours',
		'nursing_admin_hours',
		'nursing_sitter_hours',
		'nursing_custom1_hours',
		'nursing_custom2_hours',
		'nursing_custom3_hours',
		'nursing_acuity_1to1',
		'nursing_acuity_1to2',
		'nursing_acuity_1to3',
		'nursing_acuity_1to4',
		'nursing_acuity_1to5',
		'nursing_acuity_1to6',
		'nursing_acuity_custom1',
		'nursing_acuity_custom2',
		'nursing_acuity_custom3',
		'nursing_total_pts',
		'support_UOS',
		'customUOS1',
		'customUOS2',
		'customUOS3',
		'support_hours',
		'support_rn1_hours',
		'support_rn2_hours',
		'support_tech1_hours',
		'support_tech2_hours',
		'support_admin_hours',
		'support_custom1_hours',
		'support_custom2_hours',
		'support_custom3_hours',
		'support_other_hours',
		'support_provider_hours',
		'hrs_variance_submitted',
		'WHPUOS_submitted',
		'prod_submitted',
		'hrs_variance_imported',
		'WHPUOS_imported',
		'totalHrs_imported',
		'indirect_hrs',
		'escalation_Type',
		'escalation_Note',
		'churn_admits',
		'churn_discharges',
		'churn_transfers',
		'churn_custom',
		'visitsSubmitted',
		'hoursSubmitted',
		'hrs1Submitted',
		'hrs2Submitted',
		'hrs3Submitted',
		'report_notes',
		'shift_count',
		'SD',
		'EE',
		'deptID',
		'Provider',
		'dateNew',
		'dateExisting',
		'provName',
		'recId',
		'escResponse',
		'escResponder',
		'referrals',
		'totalVisits',
		'customUOS4',
		'customUOS5',
		'nursing_custom10',
		'nursing_custom11',
		'nursing_rn2_hours',
		'nursing_acuity_custom4',
		'rnVariance',
		'gridRNVariance',
		'gridTotalVariance',
		'hrsProductivity',
		'rnProductivity',
		'gridRNProductivity',
		'gridTotalProductivity',
		'gridHrsVariance',
		'rnHrsVariance',
		'gridrnHrsVariance',
		'hrsVariance',
		'trackDischarges',
		'hrsbudgetVar',
		'gridTotalbudgetVariance',
		'rnbudgetVariance',
		'gridrnbudgetVariance',
		'trackItem1',
		'trackItem2',
		'trackItem3',
		'trackItem4',
		'trackNote1',
		'trackNote2',
		'trackNote3',
		'trackNote4',
		'submittedCount',
		'oneto1Count',
		'oneto2Count',
		'oneto3Count',
		'oneto4Count',
		'oneto5Count',
		'oneto6Count',
		'oneto3p5Count',
		'oneto4p5Count',
		'oneto5p5Count',
		'twoto1Count',
		'threeto1Count',
		'Epic',
		'track1Info',
		'track2Info',
		'track3Info',
		'track4Info',
		'epicScore2',
		'pt1Info',
		'pt2Info',
		'pt3Info',
		'pt4Info',
		'nursing1Desc',
		'nursing1Count',
		'nursing2Desc',
		'nursing2Count',
		'nursing3Desc',
		'nursing3Count',
		'nursing4Desc',
		'nursing4Count',
		'nursing5Desc',
		'nursing5Count',
		'nursing6Desc',
		'nursing6Count',
		'nursing7Desc',
		'nursing7Count',
		'nursing8Desc',
		'nursing8Count',
		'nursing9Desc',
		'nursing9Count',
		'nursing10Desc',
		'nursing10Count',
		'prov_AM_1',
		'prov_AM_1_procs',
		'prov_AM_1_hrs',
		'prov_AM_2',
		'prov_AM_2_procs',
		'prov_AM_2_hrs',
		'prov_PM_1',
		'prov_PM_1_procs',
		'prov_PM_1_hrs',
		'prov_PM_2',
		'prov_PM_2_procs',
		'prov_PM_2_hrs',
		'prov_EV_1',
		'prov_EV_1_procs',
		'prov_EV_1_hrs',
		'pt5Info',
		'nursing_acuity_custom5',
		'pt6Info',
		'nursing_acuity_custom6',
		'WIT/RN',
		'ptRatio');
     
        fputcsv($output,$keys);
		foreach($dbData2 as $dbDept){
			$shiftCount = intval($dbDept['shift_count']);
			$deptId = $dbDept['deptId'];
			//$dbData = Config::get('db')->get_results("SELECT a.accountId as account_ID, a.unitId as unit_ID, a.dept as unit_Name, m.name as prod_Measure, a.hppd as UOS_Value, CONCAT(DATE_FORMAT(n.dayDate,'%m/%d/%y'), '  ', s.shift) as date_shift, DATE_FORMAT(n.entered,'%m/%d/%Y %T') as date_submitted, CONCAT(SUBSTRING(i.first_name,1,1), '. ', i.last_name) as submitted_by, (n.chargecount * 24 / {$shiftCount}) as nursing_charge_hours, ((n.antecount+n.ldcount+n.ocount) * 24 / {$shiftCount}) as nursing_rn_hours, ((n.customNurse) * 24 / {$shiftCount}) as nursing_custom_rn_hours, ((n.techcount) * 24 / {$shiftCount}) as nursing_tech_hours, ((n.seccount) * 24 / {$shiftCount}) as nursing_admin_hours, ((n.sittercount) * 24 / {$shiftCount}) as nursing_sitter_hours, ((n.otherNurse1) * 24 / {$shiftCount}) as nursing_custom1_hours, ((n.otherNurse2) * 24 / {$shiftCount}) as nursing_custom2_hours, ((n.otherNurse3) * 24 / {$shiftCount}) as nursing_custom3_hours, n.oneto1 as nursing_acuity_1to1, n.oneto2 as nursing_acuity_1to2, n.oneto3 as nursing_acuity_1to3, n.oneto4 as nursing_acuity_1to4, n.oneto5 as nursing_acuity_1to5, n.oneto6 as nursing_acuity_1to6, n.oneto7 as nursing_acuity_custom1, n.oneto8 as nursing_acuity_custom2, n.oneto9 as nursing_acuity_custom3, (n.oneto1+n.oneto2+n.oneto3+n.oneto4+n.oneto5+n.oneto6) as nursing_total_pts, n.procedureCount as support_UOS, n.whpCustom as custom1visits, n.whpCustom2 as custom2visits, n.whpCustom3 as custom3visits, n.skill1val as support_hours, n.skill2val as support_rn1_hours, n.skill3val as support_rn2_hours, n.skill4val as support_tech1_hours, n.skill5val as support_tech2_hours, n.skill6val as support_admin_hours, n.skill7val as support_custom1_hours, n.skill8val as support_custom2_hours, n.skill9val as support_custom3_hours, n.skill10val as support_other_hours, n.skill11val as support_provider_hours, n.hrsVariance as hrs_variance_submitted, n.actualWHP as WHPUOS_submitted, n.nproductivity as prod_submitted, n.hrsVarianceActual as hrs_variance_imported, n.actualWHPimport as WHPUOS_imported, n.totalHours as totalHrs_imported, n.addResourceHrs as indirect_hrs, IFNULL(l.escalation,'') as escalation_Type, IFNULL(c.note,'') as escalation_Note, n.admits as churn_admits, n.discharges as churn_discharges, n.transfers as churn_transfers, n.customChurn as churn_custom, n.visitsSubmitted, n.hoursSubmitted, n.hrs1Submitted, n.hrs2Submitted, n.hrs3Submitted, n.note as report_notes,(select count(`dayDate`) from `productiveNewData` where `deptId`=n.deptId and `whpPlan`=0 and `dayDate`=n.dayDate) as shift_count, (CASE WHEN r.dayRank >0 THEN r.dayRank ELSE '' END) as SD, (CASE WHEN r.empEngagement>0 THEN r.empEngagement ELSE '' END) as EE, n.deptId as deptID, (CASE WHEN n.type=2 THEN 'Yes' ELSE 'No' END) as provider, (CASE WHEN r.dateNew='0000-00-00' THEN '' ELSE r.dateNew END) as dateNew, (CASE WHEN r.dateExisting='0000-00-00' THEN '' ELSE r.dateExisting END) as dateExst, IFNULL((select CONCAT(SUBSTRING(`first_name`,1,1), '. ', `last_name`) from `productiveUser` where `id`=n.provId),'') as provName, n.id, (select `response` from `productiveEscalations` where `dataId`=0 limit 0,1) as escResponse, (select i.last_name from `productiveUser` i  inner join `productiveEscalations` as b on b.lastupdateId=i.id and b.dataId=0 limit 0,1) as escResponder, r.referrals FROM `productiveNewData` n LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as a on a.id = n.deptId LEFT JOIN `productiveMeasures` as m on m.id=a.prodMeasure LEFT OUTER JOIN `productiveDayRank` as r on r.dayDate=n.dayDate AND r.deptId=n.deptId LEFT JOIN `productiveAccount` as t on t.id = a.accountId LEFT JOIN `productiveUser` as u on u.id ={$userId} LEFT OUTER JOIN `productiveUser` as i on i.id=n.userId LEFT OUTER JOIN `productiveEscalations` as c on c.dataId = n.id LEFT OUTER JOIN `productiveAcctEscalations` as l on l.id = c.escalation WHERE n.deptId={$deptId} AND n.userId>0 AND n.activeRecord>0 AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate $searchAdd2$searchAdd3 group by n.deptId, n.dayDate, n.id ORDER BY n.dayDate DESC, a.dept ASC, n.shift DESC");
			$dbData = Config::get('db')->get_results("SELECT n.*, (CASE WHEN n.Prov1>0 THEN (SELECT CONCAT(`last_name`,', ',`first_name`) FROM `productiveUser` where `id`=n.Prov1) ELSE '' END) as prov1, (CASE WHEN n.Prov2>0 THEN (SELECT CONCAT(`last_name`,', ',`first_name`) FROM `productiveUser` where `id`=n.Prov2) ELSE '' END) as prov2, (CASE WHEN n.Prov3>0 THEN (SELECT CONCAT(`last_name`,', ',`first_name`) FROM `productiveUser` where `id`=n.Prov3) ELSE '' END) as prov3, (CASE WHEN n.Prov4>0 THEN (SELECT CONCAT(`last_name`,', ',`first_name`) FROM `productiveUser` where `id`=n.Prov4) ELSE '' END) as prov4, (CASE WHEN n.Prov5>0 THEN (SELECT CONCAT(`last_name`,', ',`first_name`) FROM `productiveUser` where `id`=n.Prov5) ELSE '' END) as prov5, a.chargeDesc, a.nurseDesc, a.nurse1Desc, a.nurse2Desc, a.techLabel, a.secLabel, a.other1Desc, a.sittersNEWDesc, a.other2Desc, a.other3Desc, a.desc7, a.desc8, a.desc9, a.desc10, a.desc11, a.desc12, e.shiftOrder as aShift, e.epicScore as aEpic, b.epicScore as bEpic, b.shiftOrder as bShift, (CASE WHEN n.shiftOrder=(SELECT max(`shiftOrder`) FROM `productiveAccountXref` where `dept`={$deptId}) THEN b.epicScore ELSE e.epicScore END) as newEpic, b.dayDate as newDate, i.practiceId, a.unitId as unit_ID, a.dept as unit_Name, a.oneto1Acuity, a.oneto2Acuity, a.oneto3Acuity, a.oneto4Acuity, a.oneto5Acuity, a.oneto6Acuity, m.name as prod_Measure, a.hppd as UOS_Value, CONCAT(DATE_FORMAT(n.dayDate,'%m/%d/%y'), '  ', s.shift) as date_shift, DATE_FORMAT(n.entered,'%m/%d/%Y %T') as date_submitted, (CASE WHEN i.practiceId=1 THEN n.submittedby ELSE CONCAT(SUBSTRING(i.first_name,1,1), '. ', i.last_name) END) as submitted_by, (n.chargecount * 24 / {$shiftCount}) as nursing_charge_hours, ((n.antecount+n.ldcount+n.ocount) * 24 / {$shiftCount}) as nursing_rn_hours, ((n.customNurse2) * 24 / {$shiftCount}) as nursing_rn2_hours, ((n.customNurse) * 24 / {$shiftCount}) as nursing_custom_rn_hours, ((n.techcount) * 24 / {$shiftCount}) as nursing_tech_hours, ((n.seccount) * 24 / {$shiftCount}) as nursing_admin_hours, ((n.sittercount) * 24 / {$shiftCount}) as nursing_sitter_hours, ((n.otherNurse1) * 24 / {$shiftCount}) as nursing_custom1_hours, ((n.otherNurse2) * 24 / {$shiftCount}) as nursing_custom2_hours, ((n.otherNurse3) * 24 / {$shiftCount}) as nursing_custom3_hours, n.oneto1 as nursing_acuity_1to1, n.oneto2 as nursing_acuity_1to2, n.oneto3 as nursing_acuity_1to3, n.oneto4 as nursing_acuity_1to4, n.oneto5 as nursing_acuity_1to5, n.oneto6 as nursing_acuity_1to6, n.oneto7 as nursing_acuity_custom1, n.oneto8 as nursing_acuity_custom2, n.oneto9 as nursing_acuity_custom3, n.oneto10 as nursing_acuity_custom4, (n.oneto1+n.oneto2+n.oneto3+n.oneto4+n.oneto5+n.oneto6) as nursing_total_pts, n.procedureCount as support_UOS, n.whpCustom as custom1visits, n.whpCustom2 as custom2visits, n.whpCustom3 as custom3visits, n.whpCustom4 as custom4visits, n.whpCustom5 as custom5visits, n.skill1val as support_hours, n.skill2val as support_rn1_hours, n.skill3val as support_rn2_hours, n.skill4val as support_tech1_hours, n.skill5val as support_tech2_hours, n.skill6val as support_admin_hours, n.skill7val as support_custom1_hours, n.skill8val as support_custom2_hours, n.skill9val as support_custom3_hours, n.skill10val as support_other_hours, n.skill11val as support_provider_hours, n.hrsVariance as hrs_variance_submitted, n.actualWHP as WHPUOS_submitted, n.nproductivity as prod_submitted, n.hrsVarianceActual as hrs_variance_imported, n.actualWHPimport as WHPUOS_imported, n.totalHours as totalHrs_imported, n.addResourceHrs as indirect_hrs, IFNULL(l.escalation,'') as escalation_Type, IFNULL(c.note,'') as escalation_Note, n.admits as churn_admits, n.discharges as churn_discharges, n.transfers as churn_transfers, n.customChurn as churn_custom, n.visitsSubmitted, n.hoursSubmitted, n.hrs1Submitted, n.hrs2Submitted, n.hrs3Submitted, n.note as report_notes,(select count(`dayDate`) from `productiveNewData` where `deptId`=n.deptId and `whpPlan`=0 and `dayDate`=n.dayDate) as shift_count, (CASE WHEN r.dayRank >0 THEN r.dayRank ELSE '' END) as SD, (CASE WHEN r.empEngagement>0 THEN r.empEngagement ELSE '' END) as EE, n.deptId as deptID, (CASE WHEN n.type=2 THEN 'Yes' ELSE 'No' END) as provider, (CASE WHEN r.dateNew='0000-00-00' THEN '' ELSE r.dateNew END) as dateNew, (CASE WHEN r.dateExisting='0000-00-00' THEN '' ELSE r.dateExisting END) as dateExst, IFNULL((select CONCAT(SUBSTRING(`first_name`,1,1), '. ', `last_name`) from `productiveUser` where `id`=n.provId),'') as provName, c.response as escResponse, (select `last_name` from `productiveUser` where `id` = c.lastupdateId) as escResponder, (CASE WHEN r.userId=0 AND r.referrals=0 THEN '' ELSE r.referrals END) as referrals, a.customWHPvalue, a.customWHP2value, a.customWHP3value, a.customWHP4value, a.customWHP5value, (CASE WHEN a.prodMeasure1=2 THEN (n.procedureCount + (n.whpCustom * a.customWHPvalue) + (n.whpCustom2 * a.customWHP2value) + (n.whpCustom3 * a.customWHP3value) + (n.whpCustom4 * a.customWHP4value) + (n.whpCustom5 * a.customWHP5value)) ELSE '' END) as totalVisits FROM `productiveNewData` n LEFT JOIN `productiveNewData` as e on e.deptId=n.deptId and e.dayDate=n.dayDate and e.shiftOrder=(n.shiftOrder+1) LEFT JOIN `productiveNewData` as b on b.deptId=n.deptId and b.dayDate=DATE_SUB(n.dayDate,INTERVAL 1 DAY) and b.shiftOrder=1 LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as a on a.id = n.deptId LEFT JOIN `productiveMeasures` as m on m.id=a.prodMeasure1 LEFT OUTER JOIN `productiveDayRank` as r on r.dayDate=n.dayDate AND r.deptId=n.deptId LEFT JOIN `productiveAccount` as t on t.id = a.accountId LEFT JOIN `productiveUser` as u on u.id ={$userId} LEFT OUTER JOIN `productiveUser` as i on i.id=n.userId LEFT OUTER JOIN `productiveEscalations` as c on c.dataId = n.id LEFT OUTER JOIN `productiveAcctEscalations` as l on l.id = c.escalation WHERE n.deptId={$deptId} AND n.userId>0 AND n.activeRecord>0 AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate group by n.deptId, n.dayDate, n.id ORDER BY n.dayDate DESC, a.dept ASC, n.shift DESC");
			 			 
			if($dbData) {
				foreach($dbData as $k=>$v){
					if(intval($v['antecount'])==0){
					 $wit=0;
					}else{
					 $wit = intval($v['epicScore']) / intval($v['antecount']);
					}
					$out = array(
					'account_ID' => $v['accountId'],
					'unit_ID' => $v['unit_ID'],
					'unit_Name' => $v['unit_Name'],
					'prod_Measure' => $v['prod_Measure'],
					'UOS_Value' => $v['UOS_Value'],
					'date_shift' => $v['date_shift'],
					'date_submitted' => $v['date_submitted'],	
					'submitted_by' => $v['submitted_by'],
					'nursing_charge_hours' => $v['nursing_charge_hours'],	
					'nursing_rn_hours' => $v['nursing_rn_hours'],
					'nursing_custom_rn_hours' => $v['nursing_custom_rn_hours'],	
					'nursing_tech_hours' => $v['nursing_tech_hours'],	
					'nursing_admin_hours' => $v['nursing_admin_hours'],	
					'nursing_sitter_hours' => $v['nursing_sitter_hours'],	
					'nursing_custom1_hours' => $v['nursing_custom1_hours'],
					'nursing_custom2_hours' => $v['nursing_custom2_hours'],
					'nursing_custom3_hours' => $v['nursing_custom3_hours'],
					'nursing_acuity_1to1' => $v['nursing_acuity_1to1'],	
					'nursing_acuity_1to2' => $v['nursing_acuity_1to2'],	
					'nursing_acuity_1to3' => $v['nursing_acuity_1to3'],
					'nursing_acuity_1to4' => $v['nursing_acuity_1to4'],
					'nursing_acuity_1to5' => $v['nursing_acuity_1to5'],
					'nursing_acuity_1to6' => $v['nursing_acuity_1to6'],
					'nursing_acuity_custom1' => $v['nursing_acuity_custom1'],	
					'nursing_acuity_custom2' => $v['nursing_acuity_custom2'],
					'nursing_acuity_custom3' => $v['nursing_acuity_custom3'],
					'nursing_total_pts' => $v['nursing_total_pts'],	
					'support_UOS' => $v['support_UOS'],
					'customUOS1' => $v['custom1visits'],	
					'customUOS2' => $v['custom2visits'],	
					'customUOS3' => $v['custom3visits'],
					'support_hours' => $v['support_hours'],
					'support_rn1_hours' => $v['support_rn1_hours'],
					'support_rn2_hours' => $v['support_rn2_hours'],
					'support_tech1_hours' => $v['support_tech1_hours'],	
					'support_tech2_hours' => $v['support_tech2_hours'],	
					'support_admin_hours' => $v['support_admin_hours'],	
					'support_custom1_hours' => $v['support_custom1_hours'],	
					'support_custom2_hours' => $v['support_custom2_hours'],
					'support_custom3_hours' => $v['support_custom3_hours'],
					'support_other_hours' => $v['support_other_hours'],	
					'support_provider_hours' => $v['support_provider_hours'],	
					'hrs_variance_submitted' => $v['hrs_variance_submitted'],	
					'WHPUOS_submitted' => $v['WHPUOS_submitted'],
					'prod_submitted' => $v['prod_submitted'],
					'hrs_variance_imported' => $v['hrs_variance_imported'],	
					'WHPUOS_imported' => $v['WHPUOS_imported'],
					'totalHrs_imported' => $v['totalHrs_imported'],	
					'indirect_hrs' => $v['indirect_hrs'],
					'escalation_Type' => $v['escalation_Type'],	
					'escalation_Note' => $v['escalation_Note'],
					'churn_admits' => $v['churn_admits'],
					'churn_discharges' => $v['churn_discharges'],
					'churn_transfers' => $v['churn_transfers'],	
					'churn_custom' => $v['churn_custom'],
					'visitsSubmitted' => $v['visitsSubmitted'],
					'hoursSubmitted' => $v['hoursSubmitted'],
					'hrs1Submitted' => $v['hrs1Submitted'],
					'hrs2Submitted' => $v['hrs2Submitted'],
					'hrs3Submitted' => $v['hrs3Submitted'],	
					'report_notes' => $v['report_notes'],
					'shift_count' => $v['shift_count'],
					'SD' => $v['SD'],	
					'EE' => $v['EE'],
					'deptID' => $v['deptId'],	
					'provider' => $v['provider'],
					'dateNew' => $v['dateNew'],
					'dateExisting' => $v['dateExst'],
					'provName' => $v['provName'],
					'recId' => $v['recId'],	
					'escResponse' => $v['escResponse'],	
					'escResponder' => $v['escResponder'],
					'referrals' => $v['referrals'],
					'totalVisits' => $v['totalVisits'],
					'customUOS4' => $v['custom4visits'],	
					'customUOS5' => $v['custom5visits'],
					'oneto10' => '0',
					'oneto11' => $v['oneto11'],
					'nursing_rn2_hours' => $v['nursing_rn2_hours'],
					'nursing_acuity_custom4' => $v['nursing_acuity_custom4'],
					'nvariance'=> $v['nvariance'],
					'gridrnvariance'=> $v['gridrnvariance'],
					'gvariance'=> $v['gvariance'],
					'lproductivity'=> $v['lproductivity'],
					'nproductivity'=> $v['nproductivity'],
					'gridrnproductivity'=> $v['gridrnproductivity'],
					'customProd'=> $v['customProd'],
					'ghrsVariance'=> $v['ghrsVariance'],
					'rhrsVariance'=> $v['rhrsVariance'],
					'gridrnhrsVariance'=> $v['gridrnhrsVariance'],
					'hrsVariance'=> $v['hrsVariance'],
					'trackDischarges'=> $v['trackDischarges'],
					'budgetVar'=> $v['budgetVar'],
					'gbudgetVar'=> $v['gbudgetVar'],
					'rbudgetVar'=> $v['rbudgetVar'],
					'gridrnbudgetVar'=> $v['gridrnbudgetVar'],
					'track1'=> $v['track1'],
					'track2'=> $v['track2'],
					'track3'=> $v['track3'],
					'track4'=> $v['track4'],
					'notetrack1'=> $v['notetrack1'],
					'notetrack2'=> $v['notetrack2'],
					'notetrack3'=> $v['notetrack3'],
					'notetrack4'=> $v['notetrack4'],
					'submitCount'=> $v['submitCount'],
					'oneto1Count'=> $v['oneto1Count'],
					'oneto2Count'=> $v['oneto2Count'],
					'oneto3Count'=> $v['oneto3Count'],
					'oneto4Count'=> $v['oneto4Count'],
					'oneto5Count'=> $v['oneto5Count'],
					'oneto6Count'=> $v['oneto6Count'],
					'oneto3p5Count'=> $v['oneto3p5Count'],
					'oneto4p5Count'=> $v['oneto4p5Count'],
					'oneto5p5Count'=> $v['oneto5p5Count'],
					'twoto1Count'=> $v['twoto1Count'],
					'threeto1Count'=> $v['threeto1Count'],
					'epicScore'=> $v['epicScore'],
					'track1Info'=> $v['track1Detail'],
					'track2Info'=> $v['track2Detail'],
					'track3Info'=> $v['track3Detail'],
					'track4Info'=> $v['track4Detail'],
					'epicScore2'=> $v['newEpic'],
					'pt1Info'=> $v['desc7'],
					'pt2Info'=> $v['desc8'],
					'pt3Info'=> $v['desc9'],
					'pt4Info'=> $v['desc10'],
					'nursing1Desc' => $v['chargeDesc'],
					'nursing1Count' => $v['chargecount'],
					'nursing2Desc' => $v['nurseDesc'],
					'nursing2Count' => $v['antecount'],
					'nursing3Desc' => $v['nurse1Desc'],
					'nursing3Count' => $v['customNurse'],
					'nursing4Desc' => $v['nurse2Desc'],
					'nursing4Count' => $v['customNurse2'],
					'nursing5Desc' => $v['techLabel'],
					'nursing5Count' => $v['techcount'],
					'nursing6Desc' => $v['secLabel'],
					'nursing6Count' => $v['seccount'],
					'nursing7Desc' => $v['other1Desc'],
					'nursing7Count' => $v['otherNurse1'],
					'nursing8Desc' => $v['sittersNEWDesc'],
					'nursing8Count' => $v['sittercount'],
					'nursing9Desc' => $v['other2Desc'],
					'nursing9Count' => $v['otherNurse2'],
					'nursing10Desc' => $v['other3Desc'],
					'nursing10Count' => $v['otherNurse3'],
					'prov_AM_1' => $v['prov1'],
					'prov_AM_1_procs' =>$v['provProcs1'],
					'prov_AM_1_hrs' =>$v['provHours1'],
					'prov_AM_2' => $v['prov2'],
					'prov_AM_2_procs' =>$v['provProcs2'],
					'prov_AM_2_hrs' =>$v['provHours2'],
					'prov_PM_1' => $v['prov3'],
					'prov_PM_1_procs' =>$v['provProcs3'],
					'prov_PM_1_hrs' =>$v['provHours3'],
					'prov_PM_2' => $v['prov4'],
					'prov_PM_2_procs' =>$v['provProcs4'],
					'prov_PM_2_hrs' =>$v['provHours4'],
					'prov_EV_1' => $v['prov5'],
					'prov_EV_1_procs' =>$v['provProcs5'],
					'prov_EV_1_hrs' =>$v['provHours5'],
					'pt5Info'=> $v['desc11'],
					'nursing_acuity_custom5' => $v['oneto11'],
					'pt6Info'=> $v['desc12'],
					'nursing_acuity_custom6' => $v['oneto12'],
					'WIT/RN' => $wit,
					'ptRatio' => $v['ptRatio']
					);
					
					fputcsv($output,$out);
				}
			}else{
			//do nothing;
			}
			}
	}else if($dbData2 && INTVAL($deptCount)==1){
		$deptKeys = Config::get('db')->get_results("SELECT (CASE WHEN d.nurse1Desc='' THEN 'nursing_custom_rn_hours' ELSE d.nurse1Desc END) as nurse1Desc, (CASE WHEN d.other1Desc='' THEN 'nursing_custom1_hours' ELSE d.other1Desc END) as other1Desc, (CASE WHEN d.other2Desc='' THEN 'nursing_custom2_hours' ELSE d.other2Desc END) as other2Desc, (CASE WHEN d.other3Desc='' THEN 'nursing_custom3_hours' ELSE d.other3Desc END) as other3Desc, (CASE WHEN d.desc7='' THEN 'nursing_acuity_custom1' ELSE d.desc7 END) as desc7, (CASE WHEN d.desc8='' THEN 'nursing_acuity_custom2' ELSE d.desc8 END) as desc8, (CASE WHEN d.desc9='' THEN 'nursing_acuity_custom3' ELSE d.desc9 END) as desc9, (CASE WHEN d.desc10='' THEN 'nursing_acuity_custom4' ELSE d.desc10 END) as desc10, (CASE WHEN d.skilldesc1='' THEN 'support_hours' ELSE d.skilldesc1 END) as skilldesc1, (CASE WHEN d.skilldesc2='' THEN 'support_rn1_hours' ELSE d.skilldesc2 END) as skilldesc2, (CASE WHEN d.skilldesc3='' THEN 'support_rn2_hours' ELSE d.skilldesc3 END) as skilldesc3, (CASE WHEN d.skilldesc4='' THEN 'support_tech1_hours' ELSE d.skilldesc4 END) as skilldesc4, (CASE WHEN d.skilldesc5='' THEN 'support_tech2_hours' ELSE d.skilldesc5 END) as skilldesc5, (CASE WHEN d.skilldesc6='' THEN 'support_admin_hours' ELSE d.skilldesc6 END) as skilldesc6, (CASE WHEN d.skilldesc7='' THEN 'support_custom1_hours' ELSE d.skilldesc7 END) as skilldesc7, (CASE WHEN d.skilldesc8='' THEN 'support_custom2_hours' ELSE d.skilldesc8 END) as skilldesc8, (CASE WHEN d.skilldesc9='' THEN 'support_custom3_hours' ELSE d.skilldesc9 END) as skilldesc9, (CASE WHEN d.skilldesc10='' THEN 'support_other_hours' ELSE d.skilldesc10 END) as skilldesc10, (CASE WHEN d.skilldesc11='' THEN 'support_provider_hours' ELSE d.skilldesc11 END) as skilldesc11, (CASE WHEN d.customChurnLabel='' THEN 'churn_custom' ELSE d.customChurnLabel END) as customChurnLabel, (CASE WHEN d.customDesc='' THEN 'visits_custom' ELSE d.customDesc END) as customVisits, (CASE WHEN d.customDesc2='' THEN 'visits_custom2' ELSE d.customDesc2 END) as customVisits2, (CASE WHEN d.customDesc3='' THEN 'visits_custom3' ELSE d.customDesc3 END) as customVisits3 FROM `ProductiveDept` d WHERE `id`=(SELECT `deptId` FROM `productiveDeptXref` WHERE `userId`={$userId})");
		$keys = array('account_ID','unit_ID','unit_Name','prod_Measure','UOS_Value','date_shift','date_submitted','submitted_by','nursing_charge_hours','nursing_rn_hours','' . $deptKeys[0]['nurse1Desc'] . '','nursing_tech_hours','nursing_admin_hours','nursing_sitter_hours','' . $deptKeys[0]['other1Desc'] . '','' . $deptKeys[0]['other2Desc'] . '','' . $deptKeys[0]['other3Desc'] . '','nursing_acuity_1to1','nursing_acuity_1to2','nursing_acuity_1to3','nursing_acuity_1to4','nursing_acuity_1to5','nursing_acuity_1to6','nursing_acuity_custom1','nursing_acuity_custom2','nursing_acuity_custom3','nursing_total_pts','support_UOS','' . $deptKeys[0]['customVisits'] . '','' . $deptKeys[0]['customVisits2'] . '','' . $deptKeys[0]['customVisits3'] . '','support_hours','' . $deptKeys[0]['skilldesc2'] . '','support_rn2_hours','support_tech1_hours','support_tech2_hours','support_admin_hours','support_custom1_hours','support_custom2_hours','support_custom3_hours','support_other_hours','support_provider_hours','hrs_variance_submitted','WHPUOS_submitted','prod_submitted','hrs_variance_imported','WHPUOS_imported','totalHrs_imported','indirect_hrs','escalation_Type','escalation_Note','churn_admits','churn_discharges','churn_transfers','churn_custom','submitted_visits','submitted_total_hrs','submitted_hrs1','submitted_hrs2','submitted_hrs3','report_notes','shift_count','SD','EE','deptID','Provider','dateNew','dateExst','provName','escResponse','escResponder','referrals','totalVisits','customUOS4','customUOS5','nursing_custom10','nursing_custom11','nursing_rn2_hours','' . $deptKeys[0]['nurse2Desc'] . '','nursing_acuity_custom4','rnVariance','gridRNVariance','gridTotalVariance','hrsProductivity','rnProductivity','gridRNProductivity','gridTotalProductivity','gridHrsVariance','rnHrsVariance','gridrnHrsVariance','hrsVariance','trackDischarges','hrsbudgetVar','gridTotalbudgetVariance','rnbudgetVariance','gridrnbudgetVariance','trackItem1','trackItem2','trackItem3','trackItem4','trackNote1','trackNote2','trackNote3','trackNote4','submittedCount','oneto1Count','oneto2Count','oneto3Count','oneto4Count','oneto5Count','oneto6Count','oneto3p5Count','oneto4p5Count','oneto5p5Count','twoto1Count','threeto1Count','Epic','track1Info','track2Info','track3Info','track4Info','epicScore2','pt1Info','pt2Info','pt3Info','pt4Info','nursing1Desc','nursing1Count','nursing2Desc','nursing2Count','nursing3Desc','nursing3Count','nursing4Desc','nursing4Count','nursing5Desc','nursing5Count','nursing6Desc','nursing6Count','nursing7Desc','nursing7Count','nursing8Desc','nursing8Count','nursing9Desc','nursing9Count','nursing10Desc','nursing10Count','prov_AM_1','prov_AM_1_procs','prov_AM_1_hrs','prov_AM_2','prov_AM_2_procs','prov_AM_2_hrs','prov_PM_1','prov_PM_1_procs','prov_PM_1_hrs','prov_PM_2','prov_PM_2_procs','prov_PM_2_hrs','prov_EV_1','prov_EV_1_procs','prov_EV_1_hrs','pt5Info','nursing_acuity_custom5','pt6Info','nursing_acuity_custom6','WIT/RN','ptRatio');
		fputcsv($output,$keys);
		foreach($dbData2 as $dbDept){
			$shiftCount = intval($dbDept['shift_count']);
			$deptId = $dbDept['deptId'];
			//$dbData = Config::get('db')->get_results("SELECT n.*, a.unitId as unit_ID, a.oneto1Acuity, a.oneto2Acuity, a.oneto3Acuity, a.oneto4Acuity, a.oneto5Acuity, a.oneto6Acuity, a.dept as unit_Name, m.name as prod_Measure, a.hppd as UOS_Value, CONCAT(DATE_FORMAT(n.dayDate,'%m/%d/%y'), '  ', s.shift) as date_shift, DATE_FORMAT(n.entered,'%m/%d/%Y %T') as date_submitted, CONCAT(SUBSTRING(i.first_name,1,1), '. ', i.last_name) as submitted_by, (n.chargecount * 24 / {$shiftCount}) as nursing_charge_hours, ((n.antecount+n.ldcount+n.ocount) * 24 / {$shiftCount}) as nursing_rn_hours, ((n.customNurse2) * 24 / {$shiftCount}) as nursing_rn2_hours, ((n.customNurse) * 24 / {$shiftCount}) as nursing_custom_rn_hours, ((n.techcount) * 24 / {$shiftCount}) as nursing_tech_hours, ((n.seccount) * 24 / {$shiftCount}) as nursing_admin_hours, ((n.sittercount) * 24 / {$shiftCount}) as nursing_sitter_hours, ((n.otherNurse1) * 24 / {$shiftCount}) as nursing_custom1_hours, ((n.otherNurse2) * 24 / {$shiftCount}) as nursing_custom2_hours, ((n.otherNurse3) * 24 / {$shiftCount}) as nursing_custom3_hours, n.oneto1 as nursing_acuity_1to1, n.oneto2 as nursing_acuity_1to2, n.oneto3 as nursing_acuity_1to3, n.oneto4 as nursing_acuity_1to4, n.oneto5 as nursing_acuity_1to5, n.oneto6 as nursing_acuity_1to6, n.oneto7 as nursing_acuity_custom1, n.oneto8 as nursing_acuity_custom2, n.oneto9 as nursing_acuity_custom3, n.oneto10 as nursing_acuity_custom4, (n.oneto1+n.oneto2+n.oneto3+n.oneto4+n.oneto5+n.oneto6) as nursing_total_pts, n.procedureCount as support_UOS, n.whpCustom as custom1visits, n.whpCustom2 as custom2visits, n.whpCustom3 as custom3visits, n.whpCustom4 as custom4visits, n.whpCustom5 as custom5visits, n.skill1val as support_hours, n.skill2val as support_rn1_hours, n.skill3val as support_rn2_hours, n.skill4val as support_tech1_hours, n.skill5val as support_tech2_hours, n.skill6val as support_admin_hours, n.skill7val as support_custom1_hours, n.skill8val as support_custom2_hours, n.skill9val as support_custom3_hours, n.skill10val as support_other_hours, n.skill11val as support_provider_hours, n.hrsVariance as hrs_variance_submitted, n.actualWHP as WHPUOS_submitted, n.nproductivity as prod_submitted, n.hrsVarianceActual as hrs_variance_imported, n.actualWHPimport as WHPUOS_imported, n.totalHours as totalHrs_imported, n.addResourceHrs as indirect_hrs, IFNULL(l.escalation,'') as escalation_Type, IFNULL(c.note,'') as escalation_Note, n.admits as churn_admits, n.discharges as churn_discharges, n.transfers as churn_transfers, n.customChurn as churn_custom, n.visitsSubmitted, n.hoursSubmitted, n.hrs1Submitted, n.hrs2Submitted, n.hrs3Submitted, n.note as report_notes,(select count(`dayDate`) from `productiveNewData` where `deptId`=n.deptId and `whpPlan`=0 and `dayDate`=n.dayDate) as shift_count, (CASE WHEN r.dayRank >0 THEN r.dayRank ELSE '' END) as SD, (CASE WHEN r.empEngagement>0 THEN r.empEngagement ELSE '' END) as EE, n.deptId as deptID, (CASE WHEN n.type=2 THEN 'Yes' ELSE 'No' END) as provider, (CASE WHEN r.dateNew='0000-00-00' THEN '' ELSE r.dateNew END) as dateNew, (CASE WHEN r.dateExisting='0000-00-00' THEN '' ELSE r.dateExisting END) as dateExst, IFNULL((select CONCAT(SUBSTRING(`first_name`,1,1), '. ', `last_name`) from `productiveUser` where `id`=n.provId),'') as provName, c.response as escResponse, (select `last_name` from `productiveUser` where `id` = c.lastupdateId) as escResponder, (CASE WHEN r.userId=0 AND r.referrals=0 THEN '' ELSE r.referrals END) as referrals, a.customWHPvalue, a.customWHP2value, a.customWHP3value, a.customWHP4value, a.customWHP5value, (CASE WHEN a.prodMeasure1=2 THEN (n.procedureCount + (n.whpCustom * a.customWHPvalue) + (n.whpCustom2 * a.customWHP2value) + (n.whpCustom3 * a.customWHP3value) + (n.whpCustom4 * a.customWHP4value) + (n.whpCustom5 * a.customWHP5value)) ELSE '' END) as totalVisits FROM `productiveNewData` n LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as a on a.id = n.deptId LEFT JOIN `productiveMeasures` as m on m.id=a.prodMeasure1 LEFT OUTER JOIN `productiveDayRank` as r on r.dayDate=n.dayDate AND r.deptId=n.deptId LEFT JOIN `productiveAccount` as t on t.id = a.accountId LEFT JOIN `productiveUser` as u on u.id ={$userId} LEFT OUTER JOIN `productiveUser` as i on i.id=n.userId LEFT OUTER JOIN `productiveEscalations` as c on c.dataId = n.id LEFT OUTER JOIN `productiveAcctEscalations` as l on l.id = c.escalation WHERE n.deptId={$deptId} AND n.userId>0 AND n.activeRecord>0 AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate $searchAdd2$searchAdd3 group by n.deptId, n.dayDate, n.id ORDER BY n.dayDate DESC, a.dept ASC, n.shift DESC");
			$dbData = Config::get('db')->get_results("SELECT n.*, (CASE WHEN n.Prov1>0 THEN (SELECT CONCAT(`last_name`,', ',`first_name`) FROM `productiveUser` where `id`=n.Prov1) ELSE '' END) as prov1, (CASE WHEN n.Prov2>0 THEN (SELECT CONCAT(`last_name`,', ',`first_name`) FROM `productiveUser` where `id`=n.Prov2) ELSE '' END) as prov2, (CASE WHEN n.Prov3>0 THEN (SELECT CONCAT(`last_name`,', ',`first_name`) FROM `productiveUser` where `id`=n.Prov3) ELSE '' END) as prov3, (CASE WHEN n.Prov4>0 THEN (SELECT CONCAT(`last_name`,', ',`first_name`) FROM `productiveUser` where `id`=n.Prov4) ELSE '' END) as prov4, (CASE WHEN n.Prov5>0 THEN (SELECT CONCAT(`last_name`,', ',`first_name`) FROM `productiveUser` where `id`=n.Prov5) ELSE '' END) as prov5, a.chargeDesc, a.nurseDesc, a.nurse1Desc, a.nurse2Desc, a.techLabel, a.secLabel, a.other1Desc, a.sittersNEWDesc, a.other2Desc, a.other3Desc, e.shiftOrder as aShift, e.epicScore as aEpic, b.epicScore as bEpic, b.shiftOrder as bShift, (CASE WHEN n.shiftOrder=(SELECT max(`shiftOrder`) FROM `productiveAccountXref` where `dept`={$deptId}) THEN b.epicScore ELSE e.epicScore END) as newEpic, b.dayDate as newDate, i.practiceId, a.unitId as unit_ID, a.dept as unit_Name, a.oneto1Acuity, a.oneto2Acuity, a.oneto3Acuity, a.oneto4Acuity, a.oneto5Acuity, a.oneto6Acuity, a.desc7, a.desc8, a.desc9, a.desc10, a.desc11, a.desc12, m.name as prod_Measure, a.hppd as UOS_Value, CONCAT(DATE_FORMAT(n.dayDate,'%m/%d/%y'), '  ', s.shift) as date_shift, DATE_FORMAT(n.entered,'%m/%d/%Y %T') as date_submitted, (CASE WHEN i.practiceId=1 THEN n.submittedby ELSE CONCAT(SUBSTRING(i.first_name,1,1), '. ', i.last_name) END) as submitted_by, (n.chargecount * 24 / {$shiftCount}) as nursing_charge_hours, ((n.antecount+n.ldcount+n.ocount) * 24 / {$shiftCount}) as nursing_rn_hours, ((n.customNurse2) * 24 / {$shiftCount}) as nursing_rn2_hours, ((n.customNurse) * 24 / {$shiftCount}) as nursing_custom_rn_hours, ((n.techcount) * 24 / {$shiftCount}) as nursing_tech_hours, ((n.seccount) * 24 / {$shiftCount}) as nursing_admin_hours, ((n.sittercount) * 24 / {$shiftCount}) as nursing_sitter_hours, ((n.otherNurse1) * 24 / {$shiftCount}) as nursing_custom1_hours, ((n.otherNurse2) * 24 / {$shiftCount}) as nursing_custom2_hours, ((n.otherNurse3) * 24 / {$shiftCount}) as nursing_custom3_hours, n.oneto1 as nursing_acuity_1to1, n.oneto2 as nursing_acuity_1to2, n.oneto3 as nursing_acuity_1to3, n.oneto4 as nursing_acuity_1to4, n.oneto5 as nursing_acuity_1to5, n.oneto6 as nursing_acuity_1to6, n.oneto7 as nursing_acuity_custom1, n.oneto8 as nursing_acuity_custom2, n.oneto9 as nursing_acuity_custom3, n.oneto10 as nursing_acuity_custom4, (n.oneto1+n.oneto2+n.oneto3+n.oneto4+n.oneto5+n.oneto6) as nursing_total_pts, n.procedureCount as support_UOS, n.whpCustom as custom1visits, n.whpCustom2 as custom2visits, n.whpCustom3 as custom3visits, n.whpCustom4 as custom4visits, n.whpCustom5 as custom5visits, n.skill1val as support_hours, n.skill2val as support_rn1_hours, n.skill3val as support_rn2_hours, n.skill4val as support_tech1_hours, n.skill5val as support_tech2_hours, n.skill6val as support_admin_hours, n.skill7val as support_custom1_hours, n.skill8val as support_custom2_hours, n.skill9val as support_custom3_hours, n.skill10val as support_other_hours, n.skill11val as support_provider_hours, n.hrsVariance as hrs_variance_submitted, n.actualWHP as WHPUOS_submitted, n.nproductivity as prod_submitted, n.hrsVarianceActual as hrs_variance_imported, n.actualWHPimport as WHPUOS_imported, n.totalHours as totalHrs_imported, n.addResourceHrs as indirect_hrs, IFNULL(l.escalation,'') as escalation_Type, IFNULL(c.note,'') as escalation_Note, n.admits as churn_admits, n.discharges as churn_discharges, n.transfers as churn_transfers, n.customChurn as churn_custom, n.visitsSubmitted, n.hoursSubmitted, n.hrs1Submitted, n.hrs2Submitted, n.hrs3Submitted, n.note as report_notes,(select count(`dayDate`) from `productiveNewData` where `deptId`=n.deptId and `whpPlan`=0 and `dayDate`=n.dayDate) as shift_count, (CASE WHEN r.dayRank >0 THEN r.dayRank ELSE '' END) as SD, (CASE WHEN r.empEngagement>0 THEN r.empEngagement ELSE '' END) as EE, n.deptId as deptID, (CASE WHEN n.type=2 THEN 'Yes' ELSE 'No' END) as provider, (CASE WHEN r.dateNew='0000-00-00' THEN '' ELSE r.dateNew END) as dateNew, (CASE WHEN r.dateExisting='0000-00-00' THEN '' ELSE r.dateExisting END) as dateExst, IFNULL((select CONCAT(SUBSTRING(`first_name`,1,1), '. ', `last_name`) from `productiveUser` where `id`=n.provId),'') as provName, c.response as escResponse, (select `last_name` from `productiveUser` where `id` = c.lastupdateId) as escResponder, (CASE WHEN r.userId=0 AND r.referrals=0 THEN '' ELSE r.referrals END) as referrals, a.customWHPvalue, a.customWHP2value, a.customWHP3value, a.customWHP4value, a.customWHP5value, (CASE WHEN a.prodMeasure1=2 THEN (n.procedureCount + (n.whpCustom * a.customWHPvalue) + (n.whpCustom2 * a.customWHP2value) + (n.whpCustom3 * a.customWHP3value) + (n.whpCustom4 * a.customWHP4value) + (n.whpCustom5 * a.customWHP5value)) ELSE '' END) as totalVisits FROM `productiveNewData` n LEFT JOIN `productiveNewData` as e on e.deptId=n.deptId and e.dayDate=n.dayDate and e.shiftOrder=(n.shiftOrder+1) LEFT JOIN `productiveNewData` as b on b.deptId=n.deptId and b.dayDate=DATE_SUB(n.dayDate,INTERVAL 1 DAY) and b.shiftOrder=1 LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as a on a.id = n.deptId LEFT JOIN `productiveMeasures` as m on m.id=a.prodMeasure1 LEFT OUTER JOIN `productiveDayRank` as r on r.dayDate=n.dayDate AND r.deptId=n.deptId LEFT JOIN `productiveAccount` as t on t.id = a.accountId LEFT JOIN `productiveUser` as u on u.id ={$userId} LEFT OUTER JOIN `productiveUser` as i on i.id=n.userId LEFT OUTER JOIN `productiveEscalations` as c on c.dataId = n.id LEFT OUTER JOIN `productiveAcctEscalations` as l on l.id = c.escalation WHERE n.deptId={$deptId} AND n.userId>0 AND n.activeRecord>0 AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate group by n.deptId, n.dayDate, n.id ORDER BY n.dayDate DESC, a.dept ASC, n.shift DESC");
			 
			if($dbData) {
				foreach($dbData as $k=>$v){
					if(intval($v['antecount'])==0){
					 $wit=0;
					}else{
					 $wit = intval($v['epicScore']) / intval($v['antecount']);
					}
					$out = array(
					'account_ID' => $v['accountId'],
					'unit_ID' => $v['unit_ID'],
					'unit_Name' => $v['unit_Name'],
					'prod_Measure' => $v['prod_Measure'],
					'UOS_Value' => $v['UOS_Value'],
					'date_shift' => $v['date_shift'],
					'date_submitted' => $v['date_submitted'],	
					'submitted_by' => $v['submitted_by'],
					'nursing_charge_hours' => $v['nursing_charge_hours'],	
					'nursing_rn_hours' => $v['nursing_rn_hours'],
					'nursing_custom_rn_hours' => $v['nursing_custom_rn_hours'],	
					'nursing_tech_hours' => $v['nursing_tech_hours'],	
					'nursing_admin_hours' => $v['nursing_admin_hours'],	
					'nursing_sitter_hours' => $v['nursing_sitter_hours'],	
					'nursing_custom1_hours' => $v['nursing_custom1_hours'],
					'nursing_custom2_hours' => $v['nursing_custom2_hours'],
					'nursing_custom3_hours' => $v['nursing_custom3_hours'],
					'nursing_acuity_1to1' => $v['nursing_acuity_1to1'],	
					'nursing_acuity_1to2' => $v['nursing_acuity_1to2'],	
					'nursing_acuity_1to3' => $v['nursing_acuity_1to3'],
					'nursing_acuity_1to4' => $v['nursing_acuity_1to4'],
					'nursing_acuity_1to5' => $v['nursing_acuity_1to5'],
					'nursing_acuity_1to6' => $v['nursing_acuity_1to6'],
					'nursing_acuity_custom1' => $v['nursing_acuity_custom1'],	
					'nursing_acuity_custom2' => $v['nursing_acuity_custom2'],
					'nursing_acuity_custom3' => $v['nursing_acuity_custom3'],
					'nursing_total_pts' => $v['nursing_total_pts'],	
					'support_UOS' => $v['support_UOS'],
					'customUOS1' => $v['custom1visits'],	
					'customUOS2' => $v['custom2visits'],	
					'customUOS3' => $v['custom3visits'],
					'support_hours' => $v['support_hours'],
					'support_rn1_hours' => $v['support_rn1_hours'],
					'support_rn2_hours' => $v['support_rn2_hours'],
					'support_tech1_hours' => $v['support_tech1_hours'],	
					'support_tech2_hours' => $v['support_tech2_hours'],	
					'support_admin_hours' => $v['support_admin_hours'],	
					'support_custom1_hours' => $v['support_custom1_hours'],	
					'support_custom2_hours' => $v['support_custom2_hours'],
					'support_custom3_hours' => $v['support_custom3_hours'],
					'support_other_hours' => $v['support_other_hours'],	
					'support_provider_hours' => $v['support_provider_hours'],	
					'hrs_variance_submitted' => $v['hrs_variance_submitted'],	
					'WHPUOS_submitted' => $v['WHPUOS_submitted'],
					'prod_submitted' => $v['prod_submitted'],
					'hrs_variance_imported' => $v['hrs_variance_imported'],	
					'WHPUOS_imported' => $v['WHPUOS_imported'],
					'totalHrs_imported' => $v['totalHrs_imported'],	
					'indirect_hrs' => $v['indirect_hrs'],
					'escalation_Type' => $v['escalation_Type'],	
					'escalation_Note' => $v['escalation_Note'],
					'churn_admits' => $v['churn_admits'],
					'churn_discharges' => $v['churn_discharges'],
					'churn_transfers' => $v['churn_transfers'],	
					'churn_custom' => $v['churn_custom'],
					'visitsSubmitted' => $v['visitsSubmitted'],
					'hoursSubmitted' => $v['hoursSubmitted'],
					'hrs1Submitted' => $v['hrs1Submitted'],
					'hrs2Submitted' => $v['hrs2Submitted'],
					'hrs3Submitted' => $v['hrs3Submitted'],	
					'report_notes' => $v['report_notes'],
					'shift_count' => $v['shift_count'],
					'SD' => $v['SD'],	
					'EE' => $v['EE'],
					'deptID' => $v['deptId'],	
					'provider' => $v['provider'],
					'dateNew' => $v['dateNew'],
					'dateExisting' => $v['dateExst'],
					'provName' => $v['provName'],
					'recId' => $v['recId'],	
					'escResponse' => $v['escResponse'],	
					'escResponder' => $v['escResponder'],
					'referrals' => $v['referrals'],
					'totalVisits' => $v['totalVisits'],
					'customUOS4' => $v['custom4visits'],	
					'customUOS5' => $v['custom5visits'],
					'oneto10' => '0',
					'oneto11' => $v['oneto11'],
					'nursing_rn2_hours' => $v['nursing_rn2_hours'],
					'nursing_acuity_custom4' => $v['nursing_acuity_custom4'],
					'nvariance'=> $v['nvariance'],
					'gridrnvariance'=> $v['gridrnvariance'],
					'gvariance'=> $v['gvariance'],
					'lproductivity'=> $v['lproductivity'],
					'nproductivity'=> $v['nproductivity'],
					'gridrnproductivity'=> $v['gridrnproductivity'],
					'customProd'=> $v['customProd'],
					'ghrsVariance'=> $v['ghrsVariance'],
					'rhrsVariance'=> $v['rhrsVariance'],
					'gridrnhrsVariance'=> $v['gridrnhrsVariance'],
					'hrsVariance'=> $v['hrsVariance'],
					'trackDischarges'=> $v['trackDischarges'],
					'budgetVar'=> $v['budgetVar'],
					'gbudgetVar'=> $v['gbudgetVar'],
					'rbudgetVar'=> $v['rbudgetVar'],
					'gridrnbudgetVar'=> $v['gridrnbudgetVar'],
					'track1'=> $v['track1'],
					'track2'=> $v['track2'],
					'track3'=> $v['track3'],
					'track4'=> $v['track4'],
					'notetrack1'=> $v['notetrack1'],
					'notetrack2'=> $v['notetrack2'],
					'notetrack3'=> $v['notetrack3'],
					'notetrack4'=> $v['notetrack4'],
					'submitCount'=> $v['submitCount'],
					'oneto1Count'=> $v['oneto1Count'],
					'oneto2Count'=> $v['oneto2Count'],
					'oneto3Count'=> $v['oneto3Count'],
					'oneto4Count'=> $v['oneto4Count'],
					'oneto5Count'=> $v['oneto5Count'],
					'oneto6Count'=> $v['oneto6Count'],
					'oneto3p5Count'=> $v['oneto3p5Count'],
					'oneto4p5Count'=> $v['oneto4p5Count'],
					'oneto5p5Count'=> $v['oneto5p5Count'],
					'twoto1Count'=> $v['twoto1Count'],
					'threeto1Count'=> $v['threeto1Count'],
					'epicScore'=> $v['epicScore'],
					'track1Info'=> $v['track1Detail'],
					'track2Info'=> $v['track2Detail'],
					'track3Info'=> $v['track3Detail'],
					'track4Info'=> $v['track4Detail'],
					'epicScore2'=> $v['newEpic'],
					'pt1Info'=> $v['desc7'],
					'pt2Info'=> $v['desc8'],
					'pt3Info'=> $v['desc9'],
					'pt4Info'=> $v['desc10'],
					'nursing1Desc' => $v['chargeDesc'],
					'nursing1Count' => $v['chargecount'],
					'nursing2Desc' => $v['nurseDesc'],
					'nursing2Count' => $v['antecount'],
					'nursing3Desc' => $v['nurse1Desc'],
					'nursing3Count' => $v['customNurse'],
					'nursing4Desc' => $v['nurse2Desc'],
					'nursing4Count' => $v['customNurse2'],
					'nursing5Desc' => $v['techLabel'],
					'nursing5Count' => $v['techcount'],
					'nursing6Desc' => $v['secLabel'],
					'nursing6Count' => $v['seccount'],
					'nursing7Desc' => $v['other1Desc'],
					'nursing7Count' => $v['otherNurse1'],
					'nursing8Desc' => $v['sittersNEWDesc'],
					'nursing8Count' => $v['sittercount'],
					'nursing9Desc' => $v['other2Desc'],
					'nursing9Count' => $v['otherNurse2'],
					'nursing10Desc' => $v['other3Desc'],
					'nursing10Count' => $v['otherNurse3'],
					'prov_AM_1' => $v['prov1'],
					'prov_AM_1_procs' =>$v['provProcs1'],
					'prov_AM_1_hrs' =>$v['provHours1'],
					'prov_AM_2' => $v['prov2'],
					'prov_AM_2_procs' =>$v['provProcs2'],
					'prov_AM_2_hrs' =>$v['provHours2'],
					'prov_PM_1' => $v['prov3'],
					'prov_PM_1_procs' =>$v['provProcs3'],
					'prov_PM_1_hrs' =>$v['provHours3'],
					'prov_PM_2' => $v['prov4'],
					'prov_PM_2_procs' =>$v['provProcs4'],
					'prov_PM_2_hrs' =>$v['provHours4'],
					'prov_EV_1' => $v['prov5'],
					'prov_EV_1_procs' =>$v['provProcs5'],
					'prov_EV_1_hrs' =>$v['provHours5'],
					'pt5Info'=> $v['desc11'],
					'nursing_acuity_custom5' => $v['oneto11'],
					'pt6Info'=> $v['desc12'],
					'nursing_acuity_custom6' => $v['oneto12'],
					'WIT/RN' => $wit,
					'ptRatio' => $v['ptRatio'],
					);
					fputcsv($output,$out);
				}
			}else{
			//do nothing;
			}
			}
	}else{
	//do nothing;	
	}

	

	

	return true;
}

/////DOWNLOAD REPORT DATA TO CSV

function CrashCSV() {

    $deptId1 = (isset($_REQUEST['x'])) ? $_REQUEST['x'] : 0;
    $userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
	$enterpriseId = Config::get('account')['enterpriseId'];
	//$deptId = Config::get('account')['deptid'];
    $role = Config::get('account')['role'];
	$total = 0;
    $variance = 0;
	
	if(intval($deptId1)>0){
	$dbData2 = Config::get('db')->get_results("SELECT c.*, l.qType1, l.qType2, l.qType3, l.qType4, l.qType5, l.qType6, l.qType7, l.qType8, l.qType9, l.qType10, l.qType11, l.qType12, l.qType13, l.qType14, l.qType15, l.qType16, l.qType17, l.qType18, l.qType19, l.qType20, l.q1 as qt1, l.q2 as qt2, l.q3 as qt3, l.q4 as qt4, l.q5 as qt5, l.q6 as qt6, l.q7 as qt7, l.q8 as qt8, l.q9 as qt9, l.q10 as qt10, l.q11 as qt11, l.q12 as qt12, l.q13 as qt13, l.q14 as qt14, l.q15 as qt15, l.q16 as qt16, l.q17 as qt17, l.q18 as qt18, l.q19 as qt19, l.q20 as qt20, l.title, d.dept FROM `productivecrashLog` c LEFT JOIN `ProductiveDept` as d on d.id=c.deptId LEFT JOIN `productivelogData` as l on l.id=c.logId LEFT JOIN `productiveUser` as u on u.id={$userId} where l.logType=1 AND c.dayDate >=u.lastStartDate AND c.dayDate <=u.lastEndDate and c.deptId={$deptId1} GROUP by c.logId ORDER BY c.dayDate DESC");
	$csv_filename = str_replace(" ","_",$dbData2[0]['dept']) . date('Ymd') . ".csv";
	$output = fopen("php://output",'w') or die("Can't open php://output");
    header("Content-Disposition: attachment; filename=".$csv_filename."");
	$out = array();
	$out2 = array();
	
	if($dbData2) {
		foreach($dbData2 as $dbLog){
			$logId = $dbLog['logId'];
					if(strlen($dbLog['qt1'] ?? '')==0){
						$pos1 = '';
					}else{
						$pos1 = $dbLog['qt1'];
					}
					if(strlen($dbLog['qt2'] ?? '')==0){
						$pos2 = '';
					}else{
						$pos2 = $dbLog['qt2'];
					}
					if(strlen($dbLog['qt3'] ?? '')==0){
						$pos3 = '';
					}else{
						$pos3 = $dbLog['qt3'];
					}
					if(strlen($dbLog['qt4'] ?? '')==0){
						$pos4 = '';
					}else{
						$pos4 = $dbLog['qt4'];
					}
					if(strlen($dbLog['qt5'] ?? '')==0){
						$pos5 = '';
					}else{
						$pos5 = $dbLog['qt5'];
					}
					if(strlen($dbLog['qt6'] ?? '')==0){
						$pos6 = '';
					}else{
						$pos6 = $dbLog['qt6'];
					}
					if(strlen($dbLog['qt7'] ?? '')==0){
						$pos7 = '';
					}else{
						$pos7 = $dbLog['qt7'];
					}
					if(strlen($dbLog['qt8'] ?? '')==0){
						$pos8 = '';
					}else{
						$pos8 = $dbLog['qt8'];
					}
					if(strlen($dbLog['qt9'] ?? '')==0){
						$pos9 = '';
					}else{
						$pos9 = $dbLog['qt9'];
					}
					if(strlen($dbLog['qt10'] ?? '')==0){
						$pos10 = '';
					}else{
						$pos10 = $dbLog['qt10'];
					}
					if(strlen($dbLog['qt11'] ?? '')==0){
						$pos11 = '';
					}else{
						$pos11 = $dbLog['qt11'];
					}
					if(strlen($dbLog['qt12'] ?? '')==0){
						$pos12 = '';
					}else{
						$pos12 = $dbLog['qt12'];
					}
					if(strlen($dbLog['qt13'] ?? '')==0){
						$pos13 = '';
					}else{
						$pos13 = $dbLog['qt13'];
					}
					if(strlen($dbLog['qt14'] ?? '')==0){
						$pos14 = '';
					}else{
						$pos14 = $dbLog['qt14'];
					}
					if(strlen($dbLog['qt15'] ?? '')==0){
						$pos15 = '';
					}else{
						$pos15 = $dbLog['qt15'];
					}
					if(strlen($dbLog['qt16'] ?? '')==0){
						$pos16 = '';
					}else{
						$pos16 = $dbLog['qt16'];
					}
					if(strlen($dbLog['qt17'] ?? '')==0){
						$pos17 = '';
					}else{
						$pos17 = $dbLog['qt17'];
					}
					if(strlen($dbLog['qt18'] ?? '')==0){
						$pos18 = '';
					}else{
						$pos18 = $dbLog['qt18'];
					}
					if(strlen($dbLog['qt19'] ?? '')==0){
						$pos19 = '';
					}else{
						$pos19 = $dbLog['qt19'];
					}
					if(strlen($dbLog['qt20'] ?? '')==0){
						$pos20 = '';
					}else{
						$pos20 = $dbData2[0]['qt20'];
					}
					
					
					
			$keys = array('Unit','Task','Date Due','Submitted By','Completed','' . $pos1 . '','' . $pos2 . '','' . $pos3 . '','' . $pos4 . '','' . $pos5 . '','' . $pos6 . '','' . $pos7 . '','' . $pos8 . '','' . $pos9 . '','' . $pos10 . '','' . $pos11 . '','' . $pos12 . '','' . $pos13 . '','' . $pos14 . '','' . $pos15 . '','' . $pos16 . '','' . $pos17 . '','' . $pos18 . '','' . $pos19 . '','' . $pos20 . '');
            fputcsv($output,$keys);
			
			$dbData3= Config::get('db')->get_results("SELECT c.*, l.qType1, l.qType2, l.qType3, l.qType4, l.qType5, l.qType6, l.qType7, l.qType8, l.qType9, l.qType10, l.qType11, l.qType12, l.qType13, l.qType14, l.qType15, l.qType16, l.qType17, l.qType18, l.qType19, l.qType20, l.q1 as qt1, l.q2 as qt2, l.q3 as qt3, l.q4 as qt4, l.q5 as qt5, l.q6 as qt6, l.q7 as qt7, l.q8 as qt8, l.q9 as qt9, l.q10 as qt10, l.q11 as qt11, l.q12 as qt12, l.q13 as qt13, l.q14 as qt14, l.q15 as qt15, l.q16 as qt16, l.q17 as qt17, l.q18 as qt18, l.q19 as qt19, l.q20 as qt20, l.title, d.dept FROM `productivecrashLog` c LEFT JOIN `ProductiveDept` as d on d.id=c.deptId LEFT JOIN `productivelogData` as l on l.id=c.logId LEFT JOIN `productiveUser` as u on u.id={$userId} where c.dayDate >=u.lastStartDate AND c.dayDate <=u.lastEndDate and c.logId={$logId} GROUP by c.id ORDER BY d.dept ASC, c.dayDate DESC");
	
			foreach($dbData3 as $dbDept){
					if(strlen($dbDept['qt1'])==0 || intval($dbDept['userId'])==0 || intval($dbDept['qType1'])==0 || $dbDept['q1']==''){
						$q1 = '';
					}else if($dbDept['qType1']==1 && $dbDept['q1']==1){
						$q1 = 'Yes';
					}else if($dbDept['qType1']==1 && $dbDept['q1']==2){
						$q1 = 'No';
					}else if($dbDept['qType1']==1 && $dbDept['q1']==3){
						$q1 = 'N/A';
					}else{
						$q1 = $dbDept['q1'];
					}
					if(strlen($dbDept['qt2'])==0 || intval($dbDept['userId'])==0 || intval($dbDept['qType2'])==0 || $dbDept['q2']==''){
						$q2 = '';
					}else if($dbDept['qType2']==1 && $dbDept['q2']==1){
						$q2 = 'Yes';
					}else if($dbDept['qType2']==1 && $dbDept['q2']==2){
						$q2 = 'No';
					}else if($dbDept['qType2']==1 && $dbDept['q2']==3){
						$q2 = 'N/A';
					}else{
						$q2 = $dbDept['q2'];
					}
					if(strlen($dbDept['qt3'])==0 || intval($dbDept['userId'])==0 || intval($dbDept['qType3'])==0 || $dbDept['q3']==''){
						$q3 = '';
					}else if($dbDept['qType3']==1 && $dbDept['q3']==1){
						$q3 = 'Yes';
					}else if($dbDept['qType3']==1 && $dbDept['q3']==2){
						$q3 = 'No';
					}else if($dbDept['qType3']==1 && $dbDept['q3']==3){
						$q3 = 'N/A';
					}else{
						$q3 = $dbDept['q3'];
					}
					if(strlen($dbDept['qt4'])==0 || intval($dbDept['userId'])==0 || intval($dbDept['qType4'])==0 || $dbDept['q4']==''){
						$q4 = '';
					}else if($dbDept['qType4']==1 && $dbDept['q4']==1){
						$q4 = 'Yes';
					}else if($dbDept['qType4']==1 && $dbDept['q4']==2){
						$q4 = 'No';
					}else if($dbDept['qType4']==1 && $dbDept['q4']==3){
						$q4 = 'N/A';
					}else{
						$q4 = $dbDept['q4'];
					}
					if(strlen($dbDept['qt5'])==0 || intval($dbDept['userId'])==0 || intval($dbDept['qType5'])==0 || $dbDept['q5']==''){
						$q5 = '';
					}else if($dbDept['qType5']==1 && $dbDept['q5']==1){
						$q5 = 'Yes';
					}else if($dbDept['qType5']==1 && $dbDept['q5']==2){
						$q5 = 'No';
					}else if($dbDept['qType5']==1 && $dbDept['q5']==3){
						$q5 = 'N/A';
					}else{
						$q5 = $dbDept['q5'];
					}
					if(strlen($dbDept['qt6'])==0 || intval($dbDept['userId'])==0 || intval($dbDept['qType6'])==0 || $dbDept['q6']==''){
						$q6 = '';
					}else if($dbDept['qType6']==1 && $dbDept['q6']==1){
						$q6 = 'Yes';
					}else if($dbDept['qType6']==1 && $dbDept['q6']==2){
						$q6 = 'No';
					}else if($dbDept['qType6']==1 && $dbDept['q6']==3){
						$q6 = 'N/A';
					}else{
						$q6 = $dbDept['q6'];
					}
					if(strlen($dbDept['qt7'])==0 || intval($dbDept['userId'])==0 || intval($dbDept['qType7'])==0 || $dbDept['q7']==''){
						$q7 = '';
					}else if($dbDept['qType7']==1 && $dbDept['q7']==1){
						$q7 = 'Yes';
					}else if($dbDept['qType7']==1 && $dbDept['q7']==2){
						$q7 = 'No';
					}else if($dbDept['qType7']==1 && $dbDept['q7']==3){
						$q7 = 'N/A';
					}else{
						$q7 = $dbDept['q7'];
					}
					if(strlen($dbDept['qt8'])==0 || intval($dbDept['userId'])==0 || intval($dbDept['qType8'])==0 || $dbDept['q8']==''){
						$q8 = '';
					}else if($dbDept['qType8']==1 && $dbDept['q8']==1){
						$q8 = 'Yes';
					}else if($dbDept['qType8']==1 && $dbDept['q8']==2){
						$q8 = 'No';
					}else if($dbDept['qType8']==1 && $dbDept['q8']==3){
						$q8 = 'N/A';
					}else{
						$q8 = $dbDept['q8'];
					}
					if(strlen($dbDept['qt9'])==0 || intval($dbDept['userId'])==0 || intval($dbDept['qType9'])==0 || $dbDept['q9']==''){
						$q9 = '';
					}else if($dbDept['qType9']==1 && $dbDept['q9']==1){
						$q9 = 'Yes';
					}else if($dbDept['qType9']==1 && $dbDept['q9']==2){
						$q9 = 'No';
					}else if($dbDept['qType9']==1 && $dbDept['q9']==3){
						$q9 = 'N/A';
					}else{
						$q9 = $dbDept['q9'];
					}
					if(strlen($dbDept['qt10'])==0 || intval($dbDept['userId'])==0 || intval($dbDept['qType10'])==0 || $dbDept['q10']==''){
						$q10 = '';
					}else if($dbDept['qType10']==1 && $dbDept['q10']==1){
						$q10 = 'Yes';
					}else if($dbDept['qType10']==1 && $dbDept['q10']==2){
						$q10 = 'No';
					}else if($dbDept['qType10']==1 && $dbDept['q10']==3){
						$q10 = 'N/A';
					}else{
						$q10 = $dbDept['q10'];
					}
					if(strlen($dbDept['qt11'])==0 || intval($dbDept['userId'])==0 || intval($dbDept['qType11'])==0 || $dbDept['q11']==''){
						$q11 = '';
					}else if($dbDept['qType11']==1 && $dbDept['q11']==1){
						$q11 = 'Yes';
					}else if($dbDept['qType11']==1 && $dbDept['q11']==2){
						$q11 = 'No';
					}else if($dbDept['qType11']==1 && $dbDept['q11']==3){
						$q11 = 'N/A';
					}else{
						$q11 = $dbDept['q11'];
					}
					if(strlen($dbDept['qt12'])==0 || intval($dbDept['userId'])==0 || intval($dbDept['qType12'])==0 || $dbDept['q12']==''){
						$q12 = '';
					}else if($dbDept['qType12']==1 && $dbDept['q12']==1){
						$q12 = 'Yes';
					}else if($dbDept['qType12']==1 && $dbDept['q12']==2){
						$q12 = 'No';
					}else if($dbDept['qType12']==1 && $dbDept['q12']==3){
						$q12 = 'N/A';
					}else{
						$q12 = $dbDept['q12'];
					}
					if(strlen($dbDept['qt13'])==0 || intval($dbDept['userId'])==0 || intval($dbDept['qType13'])==0 || $dbDept['q13']==''){
						$q13 = '';
					}else if($dbDept['qType13']==1 && $dbDept['q13']==1){
						$q13 = 'Yes';
					}else if($dbDept['qType13']==1 && $dbDept['q13']==2){
						$q13 = 'No';
					}else if($dbDept['qType13']==1 && $dbDept['q13']==3){
						$q13 = 'N/A';
					}else{
						$q13 = $dbDept['q13'];
					}
					if(strlen($dbDept['qt14'])==0 || intval($dbDept['userId'])==0 || intval($dbDept['qType14'])==0 || $dbDept['q14']==''){
						$q14 = '';
					}else if($dbDept['qType14']==1 && $dbDept['q14']==1){
						$q14 = 'Yes';
					}else if($dbDept['qType14']==1 && $dbDept['q14']==2){
						$q14 = 'No';
					}else if($dbDept['qType14']==1 && $dbDept['q14']==3){
						$q14 = 'N/A';
					}else{
						$q14 = $dbDept['q14'];
					}
					if(strlen($dbDept['qt15'])==0 || intval($dbDept['userId'])==0 || intval($dbDept['qType15'])==0 || $dbDept['q15']==''){
						$q15 = '';
					}else if($dbDept['qType15']==1 && $dbDept['q15']==1){
						$q15 = 'Yes';
					}else if($dbDept['qType15']==1 && $dbDept['q15']==2){
						$q15 = 'No';
					}else if($dbDept['qType15']==1 && $dbDept['q15']==3){
						$q15 = 'N/A';
					}else{
						$q15 = $dbDept['q15'];
					}
					if(strlen($dbDept['qt16'])==0 || intval($dbDept['userId'])==0 || intval($dbDept['qType16'])==0 || $dbDept['q16']==''){
						$q16 = '';
					}else if($dbDept['qType16']==1 && $dbDept['q16']==1){
						$q16 = 'Yes';
					}else if($dbDept['qType16']==1 && $dbDept['q16']==2){
						$q16 = 'No';
					}else if($dbDept['qType16']==1 && $dbDept['q16']==3){
						$q16 = 'N/A';
					}else{
						$q16 = $dbDept['q16'];
					}
					if(strlen($dbDept['qt17'])==0 || intval($dbDept['userId'])==0 || intval($dbDept['qType17'])==0 || $dbDept['q17']==''){
						$q17 = '';
					}else if($dbDept['qType17']==1 && $dbDept['q17']==1){
						$q17 = 'Yes';
					}else if($dbDept['qType17']==1 && $dbDept['q17']==2){
						$q17 = 'No';
					}else if($dbDept['qType17']==1 && $dbDept['q17']==3){
						$q17 = 'N/A';
					}else{
						$q17 = $dbDept['q17'];
					}
					if(strlen($dbDept['qt18'])==0 || intval($dbDept['userId'])==0 || intval($dbDept['qType18'])==0 || $dbDept['q18']==''){
						$q18 = '';
					}else if($dbDept['qType18']==1 && $dbDept['q18']==1){
						$q18 = 'Yes';
					}else if($dbDept['qType18']==1 && $dbDept['q18']==2){
						$q18 = 'No';
					}else if($dbDept['qType18']==1 && $dbDept['q18']==3){
						$q18 = 'N/A';
					}else{
						$q18 = $dbDept['q18'];
					}
					if(strlen($dbDept['qt19'])==0 || intval($dbDept['userId'])==0 || intval($dbDept['qType19'])==0 || $dbDept['q19']==''){
						$q19 = '';
					}else if($dbDept['qType19']==1 && $dbDept['q19']==1){
						$q19 = 'Yes';
					}else if($dbDept['qType19']==1 && $dbDept['q19']==2){
						$q19 = 'No';
					}else if($dbDept['qType19']==1 && $dbDept['q19']==3){
						$q19 = 'N/A';
					}else{
						$q19 = $dbDept['q19'];
					}
					if(strlen($dbDept['qt20'])==0 || intval($dbDept['userId'])==0 || intval($dbDept['qType20'])==0 || $dbDept['q20']==''){
						$q20 = '';
					}else if($dbDept['qType20']==1 && $dbDept['q20']==1){
						$q20 = 'Yes';
					}else if($dbDept['qType20']==1 && $dbDept['q20']==2){
						$q20 = 'No';
					}else if($dbDept['qType20']==1 && $dbDept['q20']==3){
						$q20 = 'N/A';
					}else{
						$q20 = $dbDept['q20'];
					}
					
					if($dbDept['submittedDateTime']=='0000-00-00 00:00:00'){
						$submit = '';
					}else{
						$submit = $dbDept['submittedDateTime'];
					}
					
				
					$out = array(
					'unit' => $dbDept['dept'],
					'task' => $dbDept['title'],
					'dayDate' => $dbDept['dayDate'],
					'submit' => $dbDept['submittedBy'],
					'dateTime' => $submit,
					'q1' => $q1,
					'q2' => $q2,
					'q3' => $q3,
					'q4' => $q4,
					'q5' => $q5,
					'q6' => $q6,
					'q7' => $q7,
					'q8' => $q8,
					'q9' => $q9,
					'q10' => $q10,
					'q11' => $q11,
					'q12' => $q12,
					'q13' => $q13,
					'q14' => $q14,
					'q15' => $q15,
					'q16' => $q16,
					'q17' => $q17,
					'q18' => $q18,
					'q19' => $q19,
					'q20' => $q20
					
					);
					fputcsv($output,$out);
				}
				$out = array(
					'unit' => '',
					'task' => '',
					'dateTime' => '',
					'q1' => ''
					
					);
					fputcsv($output,$out);
		
			}			
		}
	
	}	
	

	

	return true;
}

/////DOWNLOAD REPORT DATA TO CSV

function ReportCSV() {

    $deptId1 = (isset($_REQUEST['x'])) ? $_REQUEST['x'] : 0;
    $userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
	$enterpriseId = Config::get('account')['enterpriseId'];
	//$deptId = Config::get('account')['deptid'];
    $role = Config::get('account')['role'];
	$total = 0;
    $variance = 0;
	
	if(intval($deptId1)!=0){
	$dbData2 = Config::get('db')->get_results("SELECT d.*, IFNULL(t.trackType1,'') as trackType1, IFNULL(t.trackType2,'') as trackType2, IFNULL(t.trackType3,'') as trackType3, IFNULL(t.trackType4,'') as trackType4 FROM `ProductiveDept` d LEFT JOIN `productiveUser` as u on u.id =1466 LEFT OUTER JOIN `productiveTracking` as t on t.deptId=d.id AND t.dayDate >=u.lastStartDate AND t.dayDate <=u.lastEndDate WHERE d.id={$deptId1} AND d.accountId={$accountId} group by d.id order by d.unitCategory ASC, d.dept ASC");
	$csv_filename = str_replace(" ","_",$dbData2[0]['dept']) . date('Ymd') . ".csv";
	$output = fopen("php://output",'w') or die("Can't open php://output");
    header("Content-Disposition: attachment; filename=".$csv_filename."");
	$out = array();
	$out2 = array();
	
	if($dbData2) {
		
		foreach($dbData2 as $dbDept){
			if(intval($dbDept['prodMeasure'])!=2){
					if(strlen($dbDept['chargeDesc'] ?? '')==0){
						$pos1 = '<chargeDesc>';
					}else{
						$pos1 = $dbDept['chargeDesc'];
					}
					if(strlen($dbDept['nurseDesc'] ?? '')==0){
						$pos2 = '<nurseDesc>';
					}else{
						$pos2 = $dbDept['nurseDesc'];
					}
					if(strlen($dbDept['nurse1Desc'] ?? '')==0){
						$pos3 = '<nurseDesc1>';
					}else{
						$pos3 = $dbDept['nurse1Desc'];
					}
					if(strlen($dbDept['nurse2Desc'] ?? '')==0){
						$pos4 = '<nurseDesc2>';
					}else{
						$pos4 = $dbDept['nurse2Desc'];
					}
					if(strlen($dbDept['techLabel'] ?? '')==0){
						$pos5 = '<techDesc>';
					}else{
						$pos5 = $dbDept['techLabel'];
					}
					if(strlen($dbDept['secLabel'] ?? '')==0){
						$pos6 = '<secDesc>';
					}else{
						$pos6 = $dbDept['secLabel'];
					}
					if(strlen($dbDept['sittersNEWDesc'] ?? '')==0){
						$pos7 = '<customDesc1>';
					}else{
						$pos7 = $dbDept['sittersNEWDesc'];
					}
					if(strlen($dbDept['other1Desc'] ?? '')==0){
						$pos8 = '<customDesc2>';
					}else{
						$pos8 = $dbDept['other1Desc'];
					}
					if(strlen($dbDept['other2Desc'] ?? '')==0){
						$pos9 = '<customDesc3>';
					}else{
						$pos9 = $dbDept['other2Desc'];
					}
					if(strlen($dbDept['other3Desc'] ?? '')==0){
						$pos10 = '<customDesc4>';
					}else{
						$pos10 = $dbDept['other3Desc'];
					}
					if(intval($dbDept['oneto1'])==0){
						$acc1 = '<acuityDesc1>';
					}else{
						$acc1 = $dbDept['desc1'];
					}
					if(intval($dbDept['oneto2'])==0){
						$acc2 = '<acuityDesc2>';
					}else{
						$acc2 = $dbDept['desc2'];
					}
					if(intval($dbDept['oneto3'])==0){
						$acc3 = '<acuityDesc3>';
					}else{
						$acc3 = $dbDept['desc3'];
					}
					if(intval($dbDept['oneto4'])==0){
						$acc4 = '<acuityDesc4>';
					}else{
						$acc4 = $dbDept['desc4'];
					}
					if(intval($dbDept['oneto5'])==0){
						$acc5 = '<acuityDesc5>';
					}else{
						$acc5 = $dbDept['desc5'];
					}
					if(intval($dbDept['oneto6'])==0){
						$acc6 = '<acuityDesc6>';
					}else{
						$acc6 = $dbDept['desc6'];
					}
					if(strlen($dbDept['desc7'] ?? '')==0){
						$customDesc1 = '<dataDesc1>';
					}else{
						$customDesc1 = $dbDept['desc7'];
					}
					if(strlen($dbDept['desc8'] ?? '')==0){
						$customDesc2 = '<dataDesc2>';
					}else{
						$customDesc2 = $dbDept['desc8'];
					}
					if(strlen($dbDept['desc9'] ?? '')==0){
						$customDesc3 = '<dataDesc3>';
					}else{
						$customDesc3 = $dbDept['desc9'];
					}
					if(strlen($dbDept['desc10'] ?? '')==0){
						$customDesc4 = '<dataDesc4>';
					}else{
						$customDesc4 = $dbDept['desc10'];
					}
					if(strlen($dbDept['desc11'] ?? '')==0){
						$customDesc5 = '<dataDesc5>';
					}else{
						$customDesc5 = $dbDept['desc11'];
					}
					if(strlen($dbDept['desc12'] ?? '')==0){
						$customDesc6 = '<dataDesc6>';
					}else{
						$customDesc6 = $dbDept['desc12'];
					}
					if(strlen($dbDept['track1Desc'] ?? '')==0){
						$trackDesc1 = '<trackDesc1>';
					}else{
						$trackDesc1 = $dbDept['trackType1'];
					}
					if(strlen($dbDept['track2Desc'] ?? '')==0){
						$trackDesc2 = '<trackDesc2>';
					}else{
						$trackDesc2 = $dbDept['trackType2'];
					}
					if(strlen($dbDept['track3Desc'] ?? '')==0){
						$trackDesc3 = '<trackDesc3>';
					}else{
						$trackDesc3 = $dbDept['trackType3'];
					}
					if(strlen($dbDept['track4Desc'] ?? '')==0){
						$trackDesc4 = '<trackDesc4>';
					}else{
						$trackDesc4 = $dbDept['trackType4'];
					}
			$keys = array('Unit','Date/Shift','User','Patients','Epic Score','' . $pos1 . '','' . $pos2 . '','' . $pos3 . '','' . $pos4 . '','' . $pos5 . '','' . $pos6 . '','' . $pos7 . '','' . $pos8 . '','' . $pos9 . '','' . $pos10 . '','' . $acc1 . '','' . $acc2 . '','' . $acc3 . '','' . $acc4 . '','' . $acc5 . '','' . $acc6 . '','RN Variance','Grid Variance','Note','' . $customDesc1 . '','' . $customDesc2 . '','' . $customDesc3 . '','' . $customDesc4 . '','' . $customDesc5 . '','' . $customDesc6 . '','' . $trackDesc1 . '','Track Note 1','' . $trackDesc2 . '','Track Note 2','' . $trackDesc3 . '','Track Note 3','' . $trackDesc4 . '','Track Note 4','HPPD est.','ptRatio');
            fputcsv($output,$keys);
	
			$deptId = $dbDept['id'];
						
			$dbData = Config::get('db')->get_results("SELECT n.*, a.dept as unit_Name, a.prodMeasure, CONCAT(DATE_FORMAT(n.dayDate,'%m/%d/%y'), '  ', s.shift) as date_shift, DATE_FORMAT(n.entered,'%m/%d/%Y %T') as date_submitted, (CASE WHEN i.practiceId=1 THEN n.submittedby ELSE CONCAT(SUBSTRING(i.first_name,1,1), '. ', i.last_name) END) as submitted_by, n.oneto1 as nursing_acuity_1to1, n.oneto2 as nursing_acuity_1to2, n.oneto3 as nursing_acuity_1to3, n.oneto4 as nursing_acuity_1to4, n.oneto5 as nursing_acuity_1to5, n.oneto6 as nursing_acuity_1to6, n.oneto7 as nursing_acuity_custom1, n.oneto8 as nursing_acuity_custom2, n.oneto9 as nursing_acuity_custom3, n.oneto10 as nursing_acuity_custom4, (n.oneto1+n.oneto2+n.oneto3+n.oneto4+n.oneto5+n.oneto6) as nursing_total_pts, n.procedureCount as support_UOS, n.whpCustom as custom1visits, n.whpCustom2 as custom2visits, n.whpCustom3 as custom3visits, n.whpCustom4 as custom4visits, n.whpCustom5 as custom5visits, n.skill1val as support_hours, n.skill2val as support_rn1_hours, n.skill3val as support_rn2_hours, n.skill4val as support_tech1_hours, n.skill5val as support_tech2_hours, n.skill6val as support_admin_hours, n.skill7val as support_custom1_hours, n.skill8val as support_custom2_hours, n.skill9val as support_custom3_hours, n.skill10val as support_other_hours, n.skill11val as support_provider_hours, n.hrsVariance as hrs_variance_submitted, n.actualWHP as WHPUOS_submitted, n.nproductivity as prod_submitted, n.hrsVarianceActual as hrs_variance_imported, n.actualWHPimport as WHPUOS_imported, n.totalHours as totalHrs_imported, n.addResourceHrs as indirect_hrs, n.visitsSubmitted, n.hoursSubmitted, n.hrs1Submitted, n.hrs2Submitted, n.hrs3Submitted, n.note as report_notes, a.customWHPvalue, a.customWHP2value, a.customWHP3value, a.customWHP4value, a.customWHP5value, (CASE WHEN a.prodMeasure1=2 THEN (n.procedureCount + (n.whpCustom * a.customWHPvalue) + (n.whpCustom2 * a.customWHP2value) + (n.whpCustom3 * a.customWHP3value) + (n.whpCustom4 * a.customWHP4value) + (n.whpCustom5 * a.customWHP5value)) ELSE '' END) as totalVisits FROM `productiveNewData` n LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as a on a.id = n.deptId LEFT JOIN `productiveUser` as u on u.id ={$userId} LEFT OUTER JOIN `productiveUser` as i on i.id=n.userId WHERE n.deptId={$deptId} AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate group by n.deptId, n.dayDate, n.id ORDER BY n.dayDate DESC, a.dept ASC, n.shift DESC");
			 
			if($dbData) {
				foreach($dbData as $k=>$v){
					if(strlen($dbDept['chargeDesc'])==0 || intval($v['userId'])==0){
						$chargecount = '';
					}else{
						$chargecount = $v['chargecount'];
					}
					if(strlen($dbDept['nurseDesc'])==0 || intval($v['userId'])==0){
						$antecount = '';
					}else{
						$antecount = $v['antecount'];
					}
					if(strlen($dbDept['nurse1Desc'])==0 || intval($v['userId'])==0){
						$customNurse = '';
					}else{
						$customNurse = $v['customNurse'];
					}
					if(strlen($dbDept['nurse2Desc'])==0 || intval($v['userId'])==0){
						$customNurse2 = '';
					}else{
						$customNurse2 = $v['customNurse2'];
					}
					if(strlen($dbDept['techLabel'])==0 || intval($v['userId'])==0){
						$techcount = '';
					}else{
						$techcount = $v['techcount'];
					}
					if(strlen($dbDept['secLabel'])==0 || intval($v['userId'])==0){
						$seccount = '';
					}else{
						$seccount = $v['seccount'];
					}
					if(intval($dbDept['showEpic'])==0 || intval($v['userId'])==0){
						$epicScore = '';
					}else{
						$epicScore = $v['epicScore'];
					}
					if(strlen($dbDept['sittersNEWDesc'])==0 || intval($v['userId'])==0){
						$sittercount = '';
					}else{
						$sittercount = $v['sittercount'];
					}
					if(strlen($dbDept['other1Desc'])==0 || intval($v['userId'])==0){
						$otherNurse1 = '';
					}else{
						$otherNurse1 = $v['otherNurse1'];
					}
					if(strlen($dbDept['other2Desc'])==0 || intval($v['userId'])==0){
						$otherNurse2 = '';
					}else{
						$otherNurse2 = $v['otherNurse2'];
					}
					if(strlen($dbDept['other3Desc'])==0 || intval($v['userId'])==0){
						$otherNurse3 = '';
					}else{
						$otherNurse3 = $v['otherNurse3'];
					}
					if(intval($dbDept['oneto1'])==0 || intval($v['userId'])==0){
						$acuity1 = '';
					}else{
						$acuity1 = $v['nursing_acuity_1to1'];
					}
					if(intval($dbDept['oneto2'])==0 || intval($v['userId'])==0){
						$acuity2 = '';
					}else{
						$acuity2 = $v['nursing_acuity_1to2'];
					}
					if(intval($dbDept['oneto3'])==0 || intval($v['userId'])==0){
						$acuity3 = '';
					}else{
						$acuity3 = $v['nursing_acuity_1to3'];
					}
					if(intval($dbDept['oneto4'])==0 || intval($v['userId'])==0){
						$acuity4 = '';
					}else{
						$acuity4 = $v['nursing_acuity_1to4'];
					}
					if(intval($dbDept['oneto5'])==0 || intval($v['userId'])==0){
						$acuity5 = '';
					}else{
						$acuity5 = $v['nursing_acuity_1to5'];
					}
					if(intval($dbDept['oneto6'])==0 || intval($v['userId'])==0){
						$acuity6 = '';
					}else{
						$acuity6 = $v['nursing_acuity_1to6'];
					}
					if(strlen($dbDept['track1Desc'])==0 || intval($v['userId'])==0){
						$track1 = '';
					}else{
						$track1 = $v['track1'];
					}
					if(strlen($dbDept['track2Desc'])==0 || intval($v['userId'])==0){
						$track2 = '';
					}else{
						$track2 = $v['track2'];
					}
					if(strlen($dbDept['track3Desc'])==0 || intval($v['userId'])==0){
						$track3 = '';
					}else{
						$track3 = $v['track3'];
					}
					if(strlen($dbDept['track4Desc'])==0 || intval($v['userId'])==0){
						$track4 = '';
					}else{
						$track4 = $v['track4'];
					}
					if(intval($dbDept['useGrid'])==0 || intval($v['userId'])==0){
						$gvariance = '';
					}else{
						$gvariance = $v['gvariance'];
					}
					if(strlen($dbDept['desc7'])==0 || intval($v['userId'])==0){
						$ptdetail1 = '';
					}else{
						$ptdetail1 = $v['oneto7'];
					}
					if(strlen($dbDept['desc8'])==0 || intval($v['userId'])==0){
						$ptdetail2 = '';
					}else{
						$ptdetail2 = $v['oneto8'];
					}
					if(strlen($dbDept['desc9'])==0 || intval($v['userId'])==0){
						$ptdetail3 = '';
					}else{
						$ptdetail3 = $v['oneto9'];
					}
					if(strlen($dbDept['desc10'])==0 || intval($v['userId'])==0){
						$ptdetail4 = '';
					}else{
						$ptdetail4 = $v['oneto10'];
					}
					if(strlen($dbDept['desc11'])==0 || intval($v['userId'])==0){
						$ptdetail5 = '';
					}else{
						$ptdetail5 = $v['oneto11'];
					}
					if(strlen($dbDept['desc12'])==0 || intval($v['userId'])==0){
						$ptdetail6 = '';
					}else{
						$ptdetail6 = $v['oneto12'];
					}
					if(intval($v['userId'])==0){
						$ptTotal = '';
						$rnVariance = '';
					}else{
						$ptTotal = $v['nursing_total_pts'];
						$rnVariance = $v['nvariance'];
					}
					if(intval($v['prodMeasure'])==1){
						$hppd2 = ROUND(floatval($v['hppd2']),2);
					}else{
						$hppd2 = '';
					}
				
					$out = array(
					'unit_Name' => $v['unit_Name'],
					'date_shift' => $v['date_shift'],
					'submitted_by' => $v['submitted_by'],
					'nursing_total_pts' => $ptTotal,	
					'epicScore'=> $epicScore,
					'nursing_charge_hours' => $chargecount,
					'nursing_rn_hours' => $antecount,
					'nursing_custom_hours' => $customNurse,
					'nursing_custom2_hours' => $customNurse2,
					'nursing_tech_hours' => $techcount,
					'nursing_admin_hours' => $seccount,
					'nursing_sitter_hours' => $sittercount,
					'other_1' => $otherNurse1,
					'other_2' => $otherNurse2,
					'other_3' => $otherNurse3,
					'nursing_acuity_1to1' => $acuity1,	
					'nursing_acuity_1to2' => $acuity2,	
					'nursing_acuity_1to3' => $acuity3,
					'nursing_acuity_1to4' => $acuity4,
					'nursing_acuity_1to5' => $acuity5,
					'nursing_acuity_1to6' => $acuity6,
					'nvariance'=> $rnVariance,
					'gvariance'=> $gvariance,
					'report_notes' => $v['report_notes'],
					'ptdetail1'=> $ptdetail1,
					'ptdetail2'=> $ptdetail2,
					'ptdetail3'=> $ptdetail3,
					'ptdetail4'=> $ptdetail4,
					'ptdetail5'=> $ptdetail5,
					'ptdetail6'=> $ptdetail6,
					'track1'=> $track1,
					'trackNote1' => $v['notetrack1'],
					'track2'=> $track2,
					'trackNote2' => $v['notetrack2'],
					'track3'=> $track3,
					'trackNote3' => $v['notetrack3'],
					'track4'=> $track4,
					'trackNote4' => $v['notetrack4'],
					'hppd' => $hppd2,
					'ptRatio' => $v['ptRatio']
					
					);
					fputcsv($output,$out);
				}
		
			}
			}else{
				if(strlen($dbDept['uosDesc'] ?? '')==0){
						$uos1 = '<uosDesc>';
					}else{
						$uos1 = $dbDept['uosDesc'];
					}
				if(strlen($dbDept['skilldesc1'] ?? '')==0){
						$pos1 = '<posDesc1>';
					}else{
						$pos1 = $dbDept['skilldesc1'];
					}
					if(strlen($dbDept['skilldesc2'] ?? '')==0){
						$pos2 = '<posDesc2>';
					}else{
						$pos2 = $dbDept['skilldesc2'];
					}
					if(strlen($dbDept['skilldesc3'] ?? '')==0){
						$pos3 = '<posDesc3>';
					}else{
						$pos3 = $dbDept['skilldesc3'];
					}
					if(strlen($dbDept['skilldesc4'] ?? '')==0){
						$pos4 = '<posDesc4>';
					}else{
						$pos4 = $dbDept['skilldesc4'];
					}
					if(strlen($dbDept['skilldesc5'] ?? '')==0){
						$pos5 = '<posDesc5>';
					}else{
						$pos5 = $dbDept['skilldesc5'];
					}
					if(strlen($dbDept['skilldesc6'] ?? '')==0){
						$pos6 = '<posDesc6>';
					}else{
						$pos6 = $dbDept['skilldesc6'];
					}
					if(strlen($dbDept['skilldesc7'] ?? '')==0){
						$pos7 = '<posDesc7>';
					}else{
						$pos7 = $dbDept['skilldesc7'];
					}
					if(strlen($dbDept['skilldesc8'] ?? '')==0){
						$pos8 = '<posDesc8>';
					}else{
						$pos8 = $dbDept['skilldesc8'];
					}
					if(strlen($dbDept['skilldesc9'] ?? '')==0){
						$pos9 = '<posDesc9>';
					}else{
						$pos9 = $dbDept['skilldesc9'];
					}
					if(strlen($dbDept['skilldesc10'] ?? '')==0){
						$pos10 = '<posDesc10>';
					}else{
						$pos10 = $dbDept['skilldesc10'];
					}
					if(strlen($dbDept['customDesc'] ?? '')==0){
						$customDesc1 = '<dataDesc1>';
					}else{
						$customDesc1 = $dbDept['customDesc'];
					}
					if(strlen($dbDept['customDesc2'] ?? '')==0){
						$customDesc2 = '<dataDesc2>';
					}else{
						$customDesc2 = $dbDept['customDesc2'];
					}
					if(strlen($dbDept['customDesc3'] ?? '')==0){
						$customDesc3 = '<dataDesc3>';
					}else{
						$customDesc3 = $dbDept['customDesc3'];
					}
					if(strlen($dbDept['customDesc4'] ?? '')==0){
						$customDesc4 = '<dataDesc4>';
					}else{
						$customDesc4 = $dbDept['customDesc4'];
					}
					if(strlen($dbDept['customDesc5'] ?? '')==0){
						$customDesc5 = '<dataDesc5>';
					}else{
						$customDesc5 = $dbDept['customDesc5'];
					}
					if(strlen($dbDept['track1Desc'] ?? '')==0){
						$trackDesc1 = '<trackDesc1>';
					}else{
						$trackDesc1 = $dbDept['track1Desc'];
					}
					if(strlen($dbDept['track2Desc'] ?? '')==0){
						$trackDesc2 = '<trackDesc2>';
					}else{
						$trackDesc2 = $dbDept['track2Desc'];
					}
					if(strlen($dbDept['track3Desc'] ?? '')==0){
						$trackDesc3 = '<trackDesc3>';
					}else{
						$trackDesc3 = $dbDept['track3Desc'];
					}
					if(strlen($dbDept['track4Desc'] ?? '')==0){
						$trackDesc4 = '<trackDesc4>';
					}else{
						$trackDesc4 = $dbDept['track4Desc'];
					}
			$keys = array('Unit','Date/Shift','User','' . $uos1 . '','' . $pos1 . '','' . $pos2 . '','' . $pos3 . '','' . $pos4 . '','' . $pos5 . '','' . $pos6 . '','' . $pos7 . '','' . $pos8 . '','' . $pos9 . '','' . $pos10 . '','' . $customDesc1 . '','' . $customDesc2 . '','' . $customDesc3 . '','' . $customDesc4 . '','' . $customDesc5 . '','Hrs Variance','Grid Variance','Note','' . $trackDesc1 . '','Track Note 1','' . $trackDesc2 . '','Track Note 2','' . $trackDesc3 . '','Track Note 3','' . $trackDesc4 . '','Track Note 4');
            fputcsv($output,$keys);
	
			$deptId = $dbDept['id'];
						
			$dbData = Config::get('db')->get_results("SELECT n.*, a.dept as unit_Name, a.prodMeasure, CONCAT(DATE_FORMAT(n.dayDate,'%m/%d/%y'), '  ', s.shift) as date_shift, DATE_FORMAT(n.entered,'%m/%d/%Y %T') as date_submitted, (CASE WHEN i.practiceId=1 THEN n.submittedby ELSE CONCAT(SUBSTRING(i.first_name,1,1), '. ', i.last_name) END) as submitted_by, n.oneto1 as nursing_acuity_1to1, n.oneto2 as nursing_acuity_1to2, n.oneto3 as nursing_acuity_1to3, n.oneto4 as nursing_acuity_1to4, n.oneto5 as nursing_acuity_1to5, n.oneto6 as nursing_acuity_1to6, n.oneto7 as nursing_acuity_custom1, n.oneto8 as nursing_acuity_custom2, n.oneto9 as nursing_acuity_custom3, n.oneto10 as nursing_acuity_custom4, (n.oneto1+n.oneto2+n.oneto3+n.oneto4+n.oneto5+n.oneto6) as nursing_total_pts, n.procedureCount as support_UOS, n.whpCustom as custom1visits, n.whpCustom2 as custom2visits, n.whpCustom3 as custom3visits, n.whpCustom4 as custom4visits, n.whpCustom5 as custom5visits, n.skill1val as support_hours, n.skill2val as support_rn1_hours, n.skill3val as support_rn2_hours, n.skill4val as support_tech1_hours, n.skill5val as support_tech2_hours, n.skill6val as support_admin_hours, n.skill7val as support_custom1_hours, n.skill8val as support_custom2_hours, n.skill9val as support_custom3_hours, n.skill10val as support_other_hours, n.skill11val as support_provider_hours, n.hrsVariance as hrs_variance_submitted, n.actualWHP as WHPUOS_submitted, n.nproductivity as prod_submitted, n.hrsVarianceActual as hrs_variance_imported, n.actualWHPimport as WHPUOS_imported, n.totalHours as totalHrs_imported, n.addResourceHrs as indirect_hrs, n.visitsSubmitted, n.hoursSubmitted, n.hrs1Submitted, n.hrs2Submitted, n.hrs3Submitted, n.note as report_notes, a.customWHPvalue, a.customWHP2value, a.customWHP3value, a.customWHP4value, a.customWHP5value, (CASE WHEN a.prodMeasure1=2 THEN (n.procedureCount + (n.whpCustom * a.customWHPvalue) + (n.whpCustom2 * a.customWHP2value) + (n.whpCustom3 * a.customWHP3value) + (n.whpCustom4 * a.customWHP4value) + (n.whpCustom5 * a.customWHP5value)) ELSE '' END) as totalVisits FROM `productiveNewData` n LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as a on a.id = n.deptId LEFT JOIN `productiveUser` as u on u.id ={$userId} LEFT OUTER JOIN `productiveUser` as i on i.id=n.userId WHERE n.deptId={$deptId} AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate group by n.deptId, n.dayDate, n.id ORDER BY n.dayDate DESC, a.dept ASC, n.shift DESC");
			 
			if($dbData) {
				foreach($dbData as $k=>$v){
					if(strlen($dbDept['skilldesc1'] ?? '')==0 || intval($v['userId'])==0){
						$chargecount = '';
					}else{
						$chargecount = $v['skill1val'];
					}
					if(strlen($dbDept['skilldesc2'] ?? '')==0 || intval($v['userId'])==0){
						$antecount = '';
					}else{
						$antecount = $v['skill2val'];
					}
					if(strlen($dbDept['skilldesc3'] ?? '')==0 || intval($v['userId'])==0){
						$customNurse = '';
					}else{
						$customNurse = $v['skill3val'];
					}
					if(strlen($dbDept['skilldesc4'] ?? '')==0 || intval($v['userId'])==0){
						$customNurse2 = '';
					}else{
						$customNurse2 = $v['skill4val'];
					}
					if(strlen($dbDept['skilldesc5'] ?? '')==0 || intval($v['userId'])==0){
						$techcount = '';
					}else{
						$techcount = $v['skill5val'];
					}
					if(strlen($dbDept['skilldesc6'] ?? '')==0 || intval($v['userId'])==0){
						$seccount = '';
					}else{
						$seccount = $v['skill6val'];
					}
					if(strlen($dbDept['skilldesc7'] ?? '')==0 || intval($v['userId'])==0){
						$sittercount = '';
					}else{
						$sittercount = $v['skill7val'];
					}
					if(strlen($dbDept['skilldesc8'] ?? '')==0 || intval($v['userId'])==0){
						$otherNurse1 = '';
					}else{
						$otherNurse1 = $v['skill8val'];
					}
					if(strlen($dbDept['skilldesc9'] ?? '')==0 || intval($v['userId'])==0){
						$otherNurse2 = '';
					}else{
						$otherNurse2 = $v['skill9val'];
					}
					if(strlen($dbDept['skilldesc10'] ?? '')==0 || intval($v['userId'])==0){
						$otherNurse3 = '';
					}else{
						$otherNurse3 = $v['skill10val'];
					}
					if(strlen($dbDept['customDesc'] ?? '')==0 || intval($v['userId'])==0){
						$custom1 = '';
					}else{
						$custom1 = $v['whpCustom'];
					}
					if(strlen($dbDept['customDesc2'] ?? '')==0 || intval($v['userId'])==0){
						$custom2 = '';
					}else{
						$custom2 = $v['whpCustom2'];
					}
					if(strlen($dbDept['customDesc3'] ?? '')==0 || intval($v['userId'])==0){
						$custom3 = '';
					}else{
						$custom3 = $v['whpCustom3'];
					}
					if(strlen($dbDept['customDesc4'] ?? '')==0 || intval($v['userId'])==0){
						$custom4 = '';
					}else{
						$custom4 = $v['whpCustom4'];
					}
					if(strlen($dbDept['customDesc5'] ?? '')==0 || intval($v['userId'])==0){
						$custom5 = '';
					}else{
						$custom5 = $v['whpCustom5'];
					}
					if(strlen($dbDept['track1Desc'] ?? '')==0 || intval($v['userId'])==0){
						$track1 = '';
					}else{
						$track1 = $v['track1'];
					}
					if(strlen($dbDept['track2Desc'] ?? '')==0 || intval($v['userId'])==0){
						$track2 = '';
					}else{
						$track2 = $v['track2'];
					}
					if(strlen($dbDept['track3Desc'] ?? '')==0 || intval($v['userId'])==0){
						$track3 = '';
					}else{
						$track3 = $v['track3'];
					}
					if(strlen($dbDept['track4Desc'] ?? '')==0 || intval($v['userId'])==0){
						$track4 = '';
					}else{
						$track4 = $v['track4'];
					}
					
					if(intval($v['userId'])==0){
						$gridVariance = '';
						$hrsvariance = '';
					}else if(intval($v['userId'])>0 && intval($dbDept['useGrid'])>=1 && intval($dbDept['useGrid'])<=4){
						$gridVariance = $v['gvariance'];
						$hrsvariance = '';
					}else{
						$gridVariance = '';
						$hrsvariance = $v['hrsVariance'];
					}
					
					$out = array(
					'unit_Name' => $v['unit_Name'],
					'date_shift' => $v['date_shift'],
					'submitted_by' => $v['submitted_by'],
					'uos_Desc' => $v['procedureCount'],
					'nursing_charge_hours' => $chargecount,
					'nursing_rn_hours' => $antecount,
					'nursing_custom_hours' => $customNurse,
					'nursing_custom2_hours' => $customNurse2,
					'nursing_tech_hours' => $techcount,
					'nursing_admin_hours' => $seccount,
					'nursing_sitter_hours' => $sittercount,
					'other_1' => $otherNurse1,
					'other_2' => $otherNurse2,
					'other_3' => $otherNurse3,
					'nursing_acuity_1to1' => $custom1,	
					'nursing_acuity_1to2' => $custom2,	
					'nursing_acuity_1to3' => $custom3,
					'nursing_acuity_1to4' => $custom4,
					'nursing_acuity_1to5' => $custom5,
					'nvariance'=> $hrsvariance,
					'gvariance'=> $gridVariance,
					'report_notes' => $v['report_notes'],
					'track1'=> $track1,
					'trackNote1' => $v['notetrack1'],
					'track2'=> $track2,
					'trackNote2' => $v['notetrack2'],
					'track3'=> $track3,
					'trackNote3' => $v['notetrack3'],
					'track4'=> $track4,
					'trackNote4' => $v['notetrack4']
					
					);
					fputcsv($output,$out);
				}
		
			}
			}			
		}
	}
	}
	

	

	return true;
}

function ReportCSV1() {

    $deptId1 = (isset($_REQUEST['x'])) ? $_REQUEST['x'] : 0;
    $userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
	$enterpriseId = Config::get('account')['enterpriseId'];
	//$deptId = Config::get('account')['deptid'];
    $role = Config::get('account')['role'];
	$total = 0;
    $variance = 0;
	
	if(intval($deptId1)!=0){
	$dbData2 = Config::get('db')->get_results("SELECT d.* FROM `ProductiveDept` d where d.active>0 AND d.accountId={$accountId} AND d.id={$deptId1} group by d.id order by d.unitCategory ASC, d.dept ASC");
	$csv_filename = str_replace(" ","_",$dbData2[0]['dept']) . date('Ymd') . ".csv";
	$output = fopen("php://output",'w') or die("Can't open php://output");
    header("Content-Disposition: attachment; filename=".$csv_filename."");
	$out = array();
	$out2 = array();
	
	if($dbData2) {
		
		foreach($dbData2 as $dbDept){
			if(intval($dbDept['unitCategory'])==1){
			$keys = array('Unit','Date/Shift','User','Patients','Epic Score','' . $dbDept['chargeDesc'] . '','' . $dbDept['nurseDesc'] . '','' . $dbDept['nurse1Desc'] . '','' . $dbDept['nurse2Desc'] . '','' . $dbDept['techLabel'] . '','' . $dbDept['secLabel'] . '','' . $dbDept['sittersNEWDesc'] . '','' . $dbDept['other1Desc'] . '','' . $dbDept['other2Desc'] . '','' . $dbDept['other3Desc'] . '','' . $dbDept['desc1'] . '','' . $dbDept['desc2'] . '','' . $dbDept['desc3'] . '','' . $dbDept['desc4'] . '','' . $dbDept['desc5'] . '','' . $dbDept['desc6'] . '','RN Variance','Grid Variance','Note');
            fputcsv($output,$keys);
	
			$deptId = $dbDept['id'];
						
			$dbData = Config::get('db')->get_results("SELECT n.*, a.dept as unit_Name, CONCAT(DATE_FORMAT(n.dayDate,'%m/%d/%y'), '  ', s.shift) as date_shift, DATE_FORMAT(n.entered,'%m/%d/%Y %T') as date_submitted, (CASE WHEN i.practiceId=1 THEN n.submittedby ELSE CONCAT(SUBSTRING(i.first_name,1,1), '. ', i.last_name) END) as submitted_by, n.oneto1 as nursing_acuity_1to1, n.oneto2 as nursing_acuity_1to2, n.oneto3 as nursing_acuity_1to3, n.oneto4 as nursing_acuity_1to4, n.oneto5 as nursing_acuity_1to5, n.oneto6 as nursing_acuity_1to6, n.oneto7 as nursing_acuity_custom1, n.oneto8 as nursing_acuity_custom2, n.oneto9 as nursing_acuity_custom3, n.oneto10 as nursing_acuity_custom4, (n.oneto1+n.oneto2+n.oneto3+n.oneto4+n.oneto5+n.oneto6) as nursing_total_pts, n.procedureCount as support_UOS, n.whpCustom as custom1visits, n.whpCustom2 as custom2visits, n.whpCustom3 as custom3visits, n.whpCustom4 as custom4visits, n.whpCustom5 as custom5visits, n.skill1val as support_hours, n.skill2val as support_rn1_hours, n.skill3val as support_rn2_hours, n.skill4val as support_tech1_hours, n.skill5val as support_tech2_hours, n.skill6val as support_admin_hours, n.skill7val as support_custom1_hours, n.skill8val as support_custom2_hours, n.skill9val as support_custom3_hours, n.skill10val as support_other_hours, n.skill11val as support_provider_hours, n.hrsVariance as hrs_variance_submitted, n.actualWHP as WHPUOS_submitted, n.nproductivity as prod_submitted, n.hrsVarianceActual as hrs_variance_imported, n.actualWHPimport as WHPUOS_imported, n.totalHours as totalHrs_imported, n.addResourceHrs as indirect_hrs, n.visitsSubmitted, n.hoursSubmitted, n.hrs1Submitted, n.hrs2Submitted, n.hrs3Submitted, n.note as report_notes, a.customWHPvalue, a.customWHP2value, a.customWHP3value, a.customWHP4value, a.customWHP5value, (CASE WHEN a.prodMeasure1=2 THEN (n.procedureCount + (n.whpCustom * a.customWHPvalue) + (n.whpCustom2 * a.customWHP2value) + (n.whpCustom3 * a.customWHP3value) + (n.whpCustom4 * a.customWHP4value) + (n.whpCustom5 * a.customWHP5value)) ELSE '' END) as totalVisits FROM `productiveNewData` n LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as a on a.id = n.deptId LEFT JOIN `productiveUser` as u on u.id ={$userId} LEFT OUTER JOIN `productiveUser` as i on i.id=n.userId WHERE n.deptId={$deptId} AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate group by n.deptId, n.dayDate, n.id ORDER BY n.dayDate DESC, a.dept ASC, n.shift DESC");
			 
			if($dbData) {
				foreach($dbData as $k=>$v){
					$out = array(
					'unit_Name' => $v['unit_Name'],
					'date_shift' => $v['date_shift'],
					'submitted_by' => $v['submitted_by'],
					'nursing_total_pts' => $v['nursing_total_pts'],	
					'epicScore'=> $v['epicScore'],
					'nursing_charge_hours' => $v['chargecount'],
					'nursing_rn_hours' => $v['antecount'],
					'nursing_custom_hours' => $v['customNurse'],
					'nursing_custom2_hours' => $v['customNurse2'],
					'nursing_tech_hours' => $v['techcount'],
					'nursing_admin_hours' => $v['seccount'],
					'nursing_sitter_hours' => $v['sittercount'],
					'other_1' => $v['otherNurse1'],
					'other_2' => $v['otherNurse2'],
					'other_3' => $v['otherNurse3'],
					'nursing_acuity_1to1' => $v['nursing_acuity_1to1'],	
					'nursing_acuity_1to2' => $v['nursing_acuity_1to2'],	
					'nursing_acuity_1to3' => $v['nursing_acuity_1to3'],
					'nursing_acuity_1to4' => $v['nursing_acuity_1to4'],
					'nursing_acuity_1to5' => $v['nursing_acuity_1to5'],
					'nursing_acuity_1to6' => $v['nursing_acuity_1to6'],
					'nvariance'=> $v['nvariance'],
					'gvariance'=> $v['gvariance'],
					'report_notes' => $v['report_notes']
					
					);
					fputcsv($output,$out);
				}
				$keys2 = array('Unit','Date/Shift','User','Patients','Epic Score','' . $dbDept['track1Desc'] . '','' . $dbDept['track2Desc'] . '','' . $dbDept['track3Desc'] . '','' . $dbDept['track4Desc'] . '');
				fputcsv($output,$keys2);
				foreach($dbData as $k=>$v){
										
					$out2 = array(
					'unit_Name' => $v['unit_Name'],
					'date_shift' => $v['date_shift'],
					'submitted_by' => $v['submitted_by'],
					'nursing_total_pts' => $v['nursing_total_pts'],	
					'epicScore'=> $v['epicScore'],
					'track1'=> $v['track1'],
					'track2'=> $v['track2'],
					'track3'=> $v['track3'],
					'track4'=> $v['track4']					
					);
					fputcsv($output,$out2);
				}
			}
			}			
		}
	}
	}
	

	

	return true;
}

function DownloadGrid() {

	$deptId1 = isset($_POST['griddeptId']) ? intval($_POST['griddeptId']) : '';
	//$griddeptId = isset($_POST['griddept']) ? intval($_POST['griddept']) : '';
	//$gridtype = isset($_POST['newgriddeptId']) ? intval($_POST['newgriddeptId']) : '';
	
	if($deptId1){
		$deptId = $deptId1;
		$depName = $deptId1;
			
    $userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
	$total = 0;
    $variance = 0;
	//$deptCount = false;
	$dbData = Config::get('db')->get_results("SELECT g.*, d.dept, (SELECT `dept` from `ProductiveDept` where `id`={$depName}) as deptName, d.prodMeasure, x.shift as shiftName FROM `productiveStaffingGrid` g LEFT JOIN `ProductiveDept` as d on d.id=g.griddeptId LEFT JOIN `productiveShifts` as x on x.id=g.dayNight where g.griddeptId={$deptId} GROUP BY g.id ORDER BY g.dow ASC, g.dayNight DESC, g.census DESC");
			
			if(intval($dbData[0]['prodMeasure'])==2 || intval($dbData[0]['prodMeasure'])==3){
			$deptKeys = Config::get('db')->get_results("SELECT (CASE WHEN d.skilldesc1='' THEN 'Profile1' ELSE d.skilldesc1 END) as chargeDesc, (CASE WHEN d.skilldesc2='' THEN 'Profile2' ELSE d.skilldesc2 END) as nurse2Desc, (CASE WHEN d.skilldesc3='' THEN 'Profile3' ELSE d.skilldesc3 END) as nurseDesc, (CASE WHEN d.skilldesc4='' THEN 'Profile4' ELSE d.skilldesc4 END) as other1, (CASE WHEN d.skilldesc9='' THEN 'Open1' ELSE d.skilldesc9 END) as techLabel, (CASE WHEN d.skilldesc10='' THEN 'Open2' ELSE d.skilldesc10 END) as sitter, (CASE WHEN d.skilldesc5='' THEN 'Profile5' ELSE d.skilldesc5 END) as nurse1Desc, (CASE WHEN d.skilldesc6='' THEN 'Profile6' ELSE d.skilldesc6 END) as secLabel, (CASE WHEN d.skilldesc7='' THEN 'Profile7' ELSE d.skilldesc7 END) as other2, (CASE WHEN d.skilldesc8='' THEN 'Profile8' ELSE d.skilldesc8 END) as other3 FROM `ProductiveDept` d WHERE d.id={$deptId}");
			$keys = array('id','acct','unitId','unit_Name','shiftTime','census','' . $deptKeys[0]['chargeDesc'] . '','' . $deptKeys[0]['nurse2Desc'] . '','' . $deptKeys[0]['nurseDesc'] . '','' . $deptKeys[0]['other1'] . '','' . $deptKeys[0]['techLabel'] . '','' . $deptKeys[0]['sitter'] . '','' . $deptKeys[0]['nurse1Desc'] . '','' . $deptKeys[0]['secLabel'] . '','' . $deptKeys[0]['other2'] . '','' . $deptKeys[0]['other3'] . '','shiftValue','open','dayValue','day');
			}else{
			$deptKeys = Config::get('db')->get_results("SELECT (CASE WHEN d.chargeDesc='' THEN 'charge' ELSE d.chargeDesc END) as chargeDesc, (CASE WHEN d.nurseDesc='' THEN 'RN' ELSE d.nurseDesc END) as nurseDesc, (CASE WHEN d.nurse1Desc='' THEN 'RN1' ELSE d.nurse1Desc END) as nurse1Desc, (CASE WHEN d.nurse2Desc='' THEN 'RN2' ELSE d.nurse2Desc END) as nurse2Desc, (CASE WHEN d.techLabel='' THEN 'Tech' ELSE d.techLabel END) as techLabel, (CASE WHEN d.sittersNEWDesc='' THEN 'Sitter' ELSE d.sittersNEWDesc END) as sitter, (CASE WHEN d.secLabel='' THEN 'Unit Sec' ELSE d.secLabel END) as secLabel, (CASE WHEN d.other1Desc='' THEN 'Other1' ELSE d.other1Desc END) as other1, (CASE WHEN d.other2Desc='' THEN 'Other2' ELSE d.other2Desc END) as other2, (CASE WHEN d.other3Desc='' THEN 'Other3' ELSE d.other3Desc END) as other3 FROM `ProductiveDept` d WHERE d.id={$deptId}");
			$keys = array('id','acct','unitId','unit_Name','shiftTime','census','' . $deptKeys[0]['chargeDesc'] . '','' . $deptKeys[0]['nurseDesc'] . '','' . $deptKeys[0]['nurse1Desc'] . '','' . $deptKeys[0]['nurse2Desc'] . '','' . $deptKeys[0]['techLabel'] . '','' . $deptKeys[0]['sitter'] . '','' . $deptKeys[0]['secLabel'] . '','' . $deptKeys[0]['other1'] . '','' . $deptKeys[0]['other2'] . '','' . $deptKeys[0]['other3'] . '','shiftValue','open','dayValue','day');
			}
			
			if($dbData) {
			$i=1;
			$a =$accountId;
			$d =$depName;
			$csv_filename = substr($dbData[0]['dept'],0,5) . "grid_" . date('Ymd') . ".csv";
			$output = fopen("php://output",'w') or die("Can't open php://output");
			header("Content-Disposition: attachment; filename=".$csv_filename."");
			fputcsv($output,$keys);
			$out = array();
				foreach($dbData as $k=>$v){
							switch ($v['dow']) {
							case "1":
								$day="Sun";
								break;
							case "2":
								$day="Mon";
								break;
							case "3":
								$day="Tue";
								break;
							case "4":
								$day="Wed";
								break;
							case "5":
								$day="Thu";
								break;
							case "6":
								$day="Fri";
								break;
							case "7":
								$day="Sat";
								break;
							default:
								$day='n/a';
							}
					$out = array(
					'id' => $i,
					'acct' => $a,
					'unitId' => $d,
					'unit_Name' => $v['deptName'],
					'shift_Time' => $v['shiftName'],
					'census' => $v['census'],
					'cn' => $v['cn'],
					'rn' => $v['rn'],
					'rn1' => $v['rn1'],
					'rn2' => $v['rn2'],
					'tech' => $v['pct'],
					'sitter' => $v['sitter'],
					'sec' => $v['sec'],
					'other1' => $v['other1'],
					'other2' => $v['other2'],
					'other3' => $v['other3'],
					'shift' => $v['dayNight'],
					'open' => $v['unused'],
					'dayValue' => $v['dow'],
					'dayName' => $day

					);
					fputcsv($output,$out);
					$a = $a+1;
					$d = $d+1;
					$i = $i+1;
				}
			}

	return true;
	}else{
	return true;
	}
}

function newGrid() {

	$deptId1 = isset($_POST['griddeptId']) ? intval($_POST['griddeptId']) : '';
	$griddeptId = isset($_POST['griddept']) ? intval($_POST['griddept']) : '';
	$gridtype = isset($_POST['newgriddeptId']) ? intval($_POST['newgriddeptId']) : '';
	
	if($gridtype){
		$deptId = $gridtype;
		$depName = $griddeptId;
	}else{
		$deptId = $deptId1;
		$depName = $deptId1;
	}
		
    $userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
	$total = 0;
    $variance = 0;
	//$deptCount = false;
	$dbData = Config::get('db')->get_results("SELECT g.*, d.dept, (SELECT `dept` from `ProductiveDept` where `id`={$depName}) as deptName, d.prodMeasure, x.shift as shiftName FROM `productiveStaffingGrid` g LEFT JOIN `ProductiveDept` as d on d.id=g.griddeptId LEFT JOIN `productiveShifts` as x on x.id=g.dayNight where g.griddeptId={$deptId} GROUP BY g.id ORDER BY g.dow ASC, g.dayNight DESC, g.census DESC");
			
			if(intval($dbData[0]['prodMeasure'])==2 || intval($dbData[0]['prodMeasure'])==3){
			$deptKeys = Config::get('db')->get_results("SELECT (CASE WHEN d.skilldesc1='' THEN 'Profile1' ELSE d.skilldesc1 END) as chargeDesc, (CASE WHEN d.skilldesc2='' THEN 'Profile2' ELSE d.skilldesc2 END) as nurse2Desc, (CASE WHEN d.skilldesc3='' THEN 'Profile3' ELSE d.skilldesc3 END) as nurseDesc, (CASE WHEN d.skilldesc4='' THEN 'Profile4' ELSE d.skilldesc4 END) as other1, (CASE WHEN d.skilldesc9='' THEN 'Open1' ELSE d.skilldesc9 END) as techLabel, (CASE WHEN d.skilldesc10='' THEN 'Open2' ELSE d.skilldesc10 END) as sitter, (CASE WHEN d.skilldesc5='' THEN 'Profile5' ELSE d.skilldesc5 END) as nurse1Desc, (CASE WHEN d.skilldesc6='' THEN 'Profile6' ELSE d.skilldesc6 END) as secLabel, (CASE WHEN d.skilldesc7='' THEN 'Profile7' ELSE d.skilldesc7 END) as other2, (CASE WHEN d.skilldesc8='' THEN 'Profile8' ELSE d.skilldesc8 END) as other3 FROM `ProductiveDept` d WHERE d.id={$deptId}");
			$keys = array('id','acct','unitId','unit_Name','shiftTime','census','' . $deptKeys[0]['chargeDesc'] . '','' . $deptKeys[0]['nurse2Desc'] . '','' . $deptKeys[0]['nurseDesc'] . '','' . $deptKeys[0]['other1'] . '','' . $deptKeys[0]['techLabel'] . '','' . $deptKeys[0]['sitter'] . '','' . $deptKeys[0]['nurse1Desc'] . '','' . $deptKeys[0]['secLabel'] . '','' . $deptKeys[0]['other2'] . '','' . $deptKeys[0]['other3'] . '','shiftValue','open','dayValue','day');
			}else{
			$deptKeys = Config::get('db')->get_results("SELECT (CASE WHEN d.chargeDesc='' THEN 'charge' ELSE d.chargeDesc END) as chargeDesc, (CASE WHEN d.nurseDesc='' THEN 'RN' ELSE d.nurseDesc END) as nurseDesc, (CASE WHEN d.nurse1Desc='' THEN 'RN1' ELSE d.nurse1Desc END) as nurse1Desc, (CASE WHEN d.nurse2Desc='' THEN 'RN2' ELSE d.nurse2Desc END) as nurse2Desc, (CASE WHEN d.techLabel='' THEN 'Tech' ELSE d.techLabel END) as techLabel, (CASE WHEN d.sittersNEWDesc='' THEN 'Sitter' ELSE d.sittersNEWDesc END) as sitter, (CASE WHEN d.secLabel='' THEN 'Unit Sec' ELSE d.secLabel END) as secLabel, (CASE WHEN d.other1Desc='' THEN 'Other1' ELSE d.other1Desc END) as other1, (CASE WHEN d.other2Desc='' THEN 'Other2' ELSE d.other2Desc END) as other2, (CASE WHEN d.other3Desc='' THEN 'Other3' ELSE d.other3Desc END) as other3 FROM `ProductiveDept` d WHERE d.id={$deptId}");
			$keys = array('id','acct','unitId','unit_Name','shiftTime','census','' . $deptKeys[0]['chargeDesc'] . '','' . $deptKeys[0]['nurseDesc'] . '','' . $deptKeys[0]['nurse1Desc'] . '','' . $deptKeys[0]['nurse2Desc'] . '','' . $deptKeys[0]['techLabel'] . '','' . $deptKeys[0]['sitter'] . '','' . $deptKeys[0]['secLabel'] . '','' . $deptKeys[0]['other1'] . '','' . $deptKeys[0]['other2'] . '','' . $deptKeys[0]['other3'] . '','shiftValue','open','dayValue','day');
			}
			
			if($dbData) {
			$i=1;
			$a =$accountId;
			$d =$depName;
			$csv_filename = substr($dbData[0]['dept'],0,5) . "grid_" . date('Ymd') . ".csv";
			$output = fopen("php://output",'w') or die("Can't open php://output");
			header("Content-Disposition: attachment; filename=".$csv_filename."");
			fputcsv($output,$keys);
			$out = array();
				foreach($dbData as $k=>$v){
							switch ($v['dow']) {
							case "1":
								$day="Sun";
								break;
							case "2":
								$day="Mon";
								break;
							case "3":
								$day="Tue";
								break;
							case "4":
								$day="Wed";
								break;
							case "5":
								$day="Thu";
								break;
							case "6":
								$day="Fri";
								break;
							case "7":
								$day="Sat";
								break;
							default:
								$day='n/a';
							}
					$out = array(
					'id' => $i,
					'acct' => $a,
					'unitId' => $d,
					'unit_Name' => $v['deptName'],
					'shift_Time' => $v['shiftName'],
					'census' => $v['census'],
					'cn' => $v['cn'],
					'rn' => $v['rn'],
					'rn1' => $v['rn1'],
					'rn2' => $v['rn2'],
					'tech' => $v['pct'],
					'sitter' => $v['sitter'],
					'sec' => $v['sec'],
					'other1' => $v['other1'],
					'other2' => $v['other2'],
					'other3' => $v['other3'],
					'shift' => $v['dayNight'],
					'open' => $v['unused'],
					'dayValue' => $v['dow'],
					'dayName' => $day

					);
					fputcsv($output,$out);
					$a = $a+1;
					$d = $d+1;
					$i = $i+1;
				}
			}

	return true;
}



