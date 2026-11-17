<?php
ob_start();
include "initializer.php";
require_once '.././inc/class.db.php';
require_once '.././inc/class.jatwitter.php';
require_once '.././inc/config.php';
require_once '.././inc/class.phpmailer.php';
require_once '.././inc/FPDF/fpdf.php';
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
    case 'getReports':
        header('Content-Type: application/json');
        GenerateReports();
        break;
	case 'getstaff':
        header('Content-Type: application/json');
        getStaff();
        break;
	case 'getDeliveryReports':
        header('Content-Type: application/json');
        GenerateDeliveryReports();
        break;

		
////////////////////////////////
    // USEER PAGE
    case 'getUserDetails':
        header('Content-Type: application/json');
        GetUserDetails();
        break;
	case 'getUserDetailsActivate':
        header('Content-Type: application/json');
        GetUserDetailsActivate();
        break;
	case 'getAllUsers':
        header('Content-Type: application/json');
        GetAllUsers();
        break;	
	case 'getAllClasses':
        header('Content-Type: application/json');
        GetAllClasses();
        break;
		
	case 'getclassDetails':
        header('Content-Type: application/json');
        GetClassDetails();
        break;
	case 'saveclass':
        header('Content-Type: application/json');
        SaveClass();
        break;
	case 'saveTable':
        header('Content-Type: application/json');
        SaveTable();
        break;
	case 'saveDelivery':
        header('Content-Type: application/json');
        SaveDelivery();
        break;
	case 'deleteTable':
        header('Content-Type: application/json');
        DeleteTable();
        break;
	case 'getclass':
        header('Content-Type: application/json');
        GetClass();
        break;
	case 'addClass':
        header('Content-Type: application/json');
        AddClass();
        break;
	case 'getnewUnitDetails':
        header('Content-Type: application/json');
        GetNewUnitDetails();
        break;
	case 'transferUnitDetails':
        header('Content-Type: application/json');
        TransferUnitDetails();
        break;
	case 'getConfigure':
        header('Content-Type: application/json');
        GetConfigure();
        break;
	case 'gettextConfigure':
        header('Content-Type: application/json');
        GettextConfigure();
        break;
	case 'getunitGrid':
        header('Content-Type: application/json');
        GetunitGrid();
        break;
	case 'updateConfig':
        header('Content-Type: application/json');
        UpdateConfig();
        break;
	case 'getConfigDetails':
        header('Content-Type: application/json');
        GetConfigDetails();
        break;
	case 'getConfigtextDetails':
        header('Content-Type: application/json');
        GetConfigtextDetails();
        break;
	case 'getUserAssigned':
        header('Content-Type: application/json');
        GetAssignedTableData();
        break;
	case 'deleteUser':
        header('Content-Type: application/json');
        DeleteUser();
        break;
	case 'addNewUser':
        header('Content-Type: application/json');
        AddNewUser();
        break;
	case 'addNewAdmin':
        header('Content-Type: application/json');
        AddNewAdmin();
        break;
	case 'updateUser':
        header('Content-Type: application/json');
        UpdateUser();
        break;
	case 'updateTextAlerts':
        header('Content-Type: application/json');
        UpdateTextAlerts();
        break;
	case 'updateUser2':
        header('Content-Type: application/json');
        updateUser2();
        break;
	case 'forgot':
        ob_clean();
        header('Content-Type: application/json');
        Forgot();
        break;
	case 'addNewUnit':
        header('Content-Type: application/json');
        AddUnit();
        break;
	case 'addAccount':
        header('Content-Type: application/json');
        AddAccount();
        break;
	case 'getAllUnits':
        header('Content-Type: application/json');
        GetAllUnits();
        break;
	case 'getAllAccounts':
        header('Content-Type: application/json');
        GetAllAccounts();
        break;
	case 'getUnitDetails':
        header('Content-Type: application/json');
        GetUnitDetails();
        break;
	case 'getAccountDetails':
        header('Content-Type: application/json');
        GetAccountDetails();
        break;
	case 'updateUnit':
        header('Content-Type: application/json');
        UpdateUnit();
        break;
	case 'updateshifts':
        header('Content-Type: application/json');
        updateShifts();
        break;
	case 'gridupdate':
        header('Content-Type: application/json');
        gridUpdate();
        break;
	case 'addserviceLine':
        header('Content-Type: application/json');
        addServiceLine();
        break;
	case 'updateData':
        header('Content-Type: application/json');
        updatedata();
        break;
	case 'updateAccount':
        header('Content-Type: application/json');
        UpdateAccount();
        break;
	case 'updateUnitMgr':
        header('Content-Type: application/json');
        UpdateUnitMgr();
        break;
	case 'deleteUnit':
        header('Content-Type: application/json');
        DeleteUnit();
        break;
    case 'getProd':
        header('Content-Type: application/json');
        GetProdTableData();
        break;
	case 'getProdOPS':
        header('Content-Type: application/json');
        GetProdTableDataOPS();
        break;
	case 'getvisits':
        header('Content-Type: application/json');
        GetVisits();
        break;
	case 'updatevisits':
        header('Content-Type: application/json');
        UpdateVisits();
        break;
	case 'getProdwhp':
        header('Content-Type: application/json');
        GetProdTableDataWHP();
        break;
	case 'getProduserx':
        header('Content-Type: application/json');
        GetProdTableDatauserx();
        break;
	case 'getPerformance':
        header('Content-Type: application/json');
        GetPerformanceTableData();
        break;
	case 'getviewlog':
        header('Content-Type: application/json');
        GetviewlogTableData();
        break;
	case 'getEscalations':
        header('Content-Type: application/json');
        GetEscalationsTableData();
        break;
	case 'getTracking':
        header('Content-Type: application/json');
        GetTracking();
        break;
	case 'getEscalation':
        header('Content-Type: application/json');
        GetEscalation();
        break;
	case 'updateEscalation':
        header('Content-Type: application/json');
        UpdateEscalation();
        break;
	case 'getdayRank':
        header('Content-Type: application/json');
        GetdayRankTableData();
        break;
	case 'editdayRank':
        header('Content-Type: application/json');
        EditDayRank();
        break;
	case 'checkday':
        header('Content-Type: application/json');
        checkDay();
        break;
	case 'logview':
        header('Content-Type: application/json');
        logView();
        break;
	case 'getcompliance':
        header('Content-Type: application/json');
        GetComplianceTableData();
        break;
	case 'getpdfs':
        header('Content-Type: application/json');
        GetPdfs();
        break;
	case 'getcrash':
        header('Content-Type: application/json');
        GetCrashTableData();
        break;
	case 'updateCrash':
        header('Content-Type: application/json');
        UpdateCrash();
        break;
	case 'updateProd':
        header('Content-Type: application/json');
        UpdateProdMatrix();
        break;
	case 'clearRecord':
        header('Content-Type: application/json');
        clearRecord();
        break;
	case 'clearEscalation':
        header('Content-Type: application/json');
        clearEscalation();
        break;
	case 'clearwhpRecord':
        header('Content-Type: application/json');
        clearWHPRecord();
        break;
	case 'copyPlan':
        header('Content-Type: application/json');
        copyPlan();
        break;
	case 'updateProdNew':
        header('Content-Type: application/json');
        UpdateProdNew();
        break;
	case 'updateprodTest':
        header('Content-Type: application/json');
        UpdateProdTest();
        break;
	case 'updateprodWHP':
        header('Content-Type: application/json');
        UpdateProdWHP();
        break;
	case 'getProdDetails':
        header('Content-Type: application/json');
        GetProdDetails();
        break;
	case 'getdayRankdetails':
        header('Content-Type: application/json');
        GetDayRank();
        break;
	case 'getCrashDetails':
        header('Content-Type: application/json');
        GetCrashDetails();
        break;
	case 'prodNote':
        header('Content-Type: application/json');
        AddProdNote();
        break;
	case 'getblocked':
        header('Content-Type: application/json');
        getblockedBeds();
        break;
	case 'updateblocked':
        header('Content-Type: application/json');
        updateblockedBeds();
        break;
	case 'addEscalation':
        header('Content-Type: application/json');
        addEscalation();
        break;
	case 'getResourceDetails':
        header('Content-Type: application/json');
        GetResourceDetails();
        break;
	case 'gettextResourceDetails':
        header('Content-Type: application/json');
        GettextResourceDetails();
        break;
	case 'addResource':
        header('Content-Type: application/json');
        AddResource();
        break;
	case 'addtextResource':
        header('Content-Type: application/json');
        AddtextResource();
        break;
	case 'newUnit':
        header('Content-Type: application/json');
        NewUnit();
        break;
	case 'completeTransfer':
        header('Content-Type: application/json');
        CompleteTransfer();
        break;
	case 'updateResource':
        header('Content-Type: application/json');
        UpdateResource();
        break;
	case 'removeResource':
        header('Content-Type: application/json');
        RemoveResource();
        break;
	case 'removetextResource':
        header('Content-Type: application/json');
        RemovetextResource();
        break;
	case 'removeUnit':
        header('Content-Type: application/json');
        RemoveUnit();
        break;
	case 'updatetextResource':
        header('Content-Type: application/json');
        UpdatetextResource();
        break;
	case 'opt':
		header('Content-Type: application/json');
		optIn();
		break;
	case 'opt2':
		header('Content-Type: application/json');
		optIn2();
		break;
	case 'support':
		header('Content-Type: application/json');
		Support();
		break;
	case 'downloadcsv':
		header('Content-Type: application/json');
		DownloadCSV();
		break;
	case 'getpdf':
		fpdf();
		break;
	case 'setdates':
		header('Content-Type: application/json');
		setDates();
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
	case 'pdf11':
        header('Content-Type: application/json');
        getpdf11();
        break;
	case 'pdf20363':
        header('Content-Type: application/json');
        getpdf20363(363);
        break;
	case 'pdf20364':
        header('Content-Type: application/json');
        getpdf20363(364);
        break;
	case 'pdf20365':
        header('Content-Type: application/json');
        getpdf20365(365);
        break;
	case 'pdf20356':
        header('Content-Type: application/json');
        getpdf20365(356);
        break;
	case 'pdf20358':
        header('Content-Type: application/json');
        getpdf20363(358);
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
	case 'getconcierge':
        header('Content-Type: application/json');
        getConcierge();
        break;
}


/*

        ______ ___________ ___________ _____ _____    ______  ___  _____  _____
        | ___ \  ___| ___ \  _  | ___ \_   _/  ___|   | ___ \/ _ \|  __ \|  ___|
        | |_/ / |__ | |_/ / | | | |_/ / | | \ `--.    | |_/ / /_\ \ |  \/| |__
        |    /|  __||  __/| | | |    /  | |  `--. \   |  __/|  _  | | __ |  __|
        | |\ \| |___| |   \ \_/ / |\ \  | | /\__/ /   | |   | | | | |_\ \| |___
        \_| \_\____/\_|    \___/\_| \_| \_/ \____/    \_|   \_| |_/\____/\____/


*/


/////////////////////////////////////
// PULL THE REPORT PAGE REPORT DATA
function GenerateReports() {
	$idsearch = isset($_REQUEST['idsearch']) && ($_REQUEST['idsearch'] != 0) ? $_REQUEST['idsearch'] : false;
	$categoryId = isset($_REQUEST['categoryId']) && ($_REQUEST['categoryId'] != 0) ? $_REQUEST['categoryId'] : false;
	$locationId = isset($_REQUEST['locationId']) && ($_REQUEST['locationId'] != 0) ? $_REQUEST['locationId'] : false;
	$sort = isset($_REQUEST['sort']) ? intval($_REQUEST['sort']) : 0;
    $startDate = isset($_REQUEST['start']) ? $_REQUEST['start'] : '2010-01-01';
	$start = $startDate;
    $startDate .= " 00:00:00";
    $endDate = isset($_REQUEST['end']) ? $_REQUEST['end'] : '2020-01-01';
	$end = $endDate;
    $endDate .= " 23:59:59";
	$newEscalation = 0;
	$i=0;
	$sortHide=0;
	$accountPos = Config::get('account')['productivityPosNeg'];

	$startTime = time();
	$startval = strtotime($startDate);
	$endval = strtotime($endDate);
	
	$startdiff = ROUND((($startTime - $startval) / (60*60*24)))-1;
	$enddiff = ROUND((($startTime - $endval) / (60*60*24)));
    
	//$testdate = date("Y-m-d",$startTime);
	if($locationId){
	$accountId = $locationId;
	}else{
    $accountId = Config::get('account')['accountId'];
	}
	
	$enterpriseId = Config::get('account')['enterpriseId'];
	$productivityPosNeg = intval(Config::get('account')['productivityPosNeg']);
	/*
	if($productivityPosNeg>=0 && $sort==0){
		$prodSort = " order by budgetVar ASC, d.dept ASC";
	}else if($productivityPosNeg<0 && $sort==0){
		$prodSort = " order by budgetVar DESC, d.dept ASC";
	}else{
		$prodSort = " order by d.dept ASC";
	}
	*/
	if(intval($accountPos)==1){
	$prodSort = " order by activeCount DESC, totalHrs DESC, d.dept ASC";
	}else{
	$prodSort = " order by activeCount DESC, totalHrs ASC, d.dept ASC";
	}
	
	$userId = Config::get('account')['id'];
	
	if($idsearch){
		$deptNum = $idsearch;
	}else{
		$deptNum = '';
	}
	$role = Config::get('account')['role'];
	$nowtime = time()-21600;
	$now = date("Y-m-d",$nowtime);
	
	if(intval($role)<8 && !$idsearch){
		$searchAdd = " where d.active>0 AND d.accountId ={$accountId} AND d.id in (SELECT `deptId` from `productiveDeptXref` where `userId` ={$userId}) AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}'";
	}else if(intval($role)>7 && intval($role)<=12 && !$idsearch){
		$searchAdd = " where d.active>0 AND d.accountId ={$accountId} AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}'";
	}else if(intval($role)>12 && !$idsearch && !$locationId){
		$searchAdd = " where d.active>0 AND a.enterpriseId ={$enterpriseId} AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}'";
	}else if(intval($role)>12 && !$idsearch && $locationId){
		$searchAdd = " where d.active>0 AND d.accountId ={$accountId} AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}'";
	}else{
		$searchAdd = " where d.active>0 AND d.id ={$deptNum} AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}'";
		$sortHide = 1;
	}
	
	if($categoryId){
		$searchAdd2 = " AND d.category={$categoryId} ";
	}else{
		$searchAdd2 = '';
	}
	
	//$dbDept = Config::get('db')->get_results("SELECT n.*, d.trackdata, d.trackdc, d.avgWage, d.useavgWage, d.indMeasure, d.thresholdHigh, d.thresholdLow, d.rnThreshold, (select count(`dayDate`) from `productiveNewData` where `deptId`=n.deptId and `whpPlan`=0 and `dayDate`=n.dayDate) as shiftsperDay, d.category, d.prodMeasure, d.unitCategory, d.showProdWHP, d.hppd, d.churn, d.dept, d.rnVariance, d.budgetMeasure, d.uosValue, SUM(CASE WHEN n.hrsVarianceActual!=0.000 THEN n.hrsVarianceActual ELSE n.hrsVariance END) as hrsVar, a.payPeriod, a.displayCompliance, a.productivityPosNeg, a.displayProd, a.enterpriseId, a.prodIndicator, a.name as accountName, a.payPeriodFirst from `productiveNewData` n LEFT JOIN `ProductiveDept` as d on d.id = n.deptId left join `productiveAccount` as a on a.id = d.accountId$searchAdd$searchAdd2 group by n.deptId$prodSort");
	$dbDept = Config::get('db')->get_results("SELECT n.*, SUM(n.ahrsVariance) as totalHrs, IFNULL(SUM(n.activeRecord) / SUM(n.activeRecord),0) as activeCount, d.track1Desc, d.track2Desc, d.track3Desc, d.trackdata, d.trackdc, d.uosDesc, d.avgWage, d.useGrid, d.useavgWage, d.indMeasure, d.thresholdHigh, d.thresholdLow, d.rnThreshold, d.category, d.prodMeasure, d.prodMeasure1, d.unitCategory, d.showProdWHP, d.hppd, d.churn, d.dept, d.rnVariance, d.budgetMeasure, d.uosValue, SUM(n.hrsVariance) as hrsVar, SUM(n.budgetVar) as budgetVar, a.payPeriod, a.displayCompliance, a.productivityPosNeg, a.displayProd, a.enterpriseId, a.prodIndicator, a.name as accountName, a.payPeriodFirst from `productiveNewData` n LEFT JOIN `ProductiveDept` as d on d.id = n.deptId left join `productiveAccount` as a on a.id = d.accountId$searchAdd$searchAdd2 group by n.deptId$prodSort");
		
	$outData = array();
	$newCandidateGraphLabels = array();
    $newCandidateGraphData = array();
	$smsSentGraphLabels = array();
    $smsSentGraphData = array();
	$laborGraphLabels = array();
    $laborGraphData = array();
	$postpartumGraphLabels = array();
    $postpartumGraphData = array();
	
	
	if($dbDept){
	$deptCount = count($dbDept);
	$compliance = 0;
	
	foreach($dbDept as $dept){
	$i=$i+1;
	$deptId = $dept['deptId'];
	//$shifts = $dept['shiftsperDay'];
	$pMeasure = intval($dept['prodMeasure1']);
	$unitCat = intval($dept['unitCategory']);
	$showProd = intval($dept['showProdWHP']);
	$targethppd = $dept['hppd'];
	$churn = $dept['churn'];
	$acctId = $dept['accountId'];
	$budgetMeasure = $dept['budgetMeasure'];
	$useavgWage = intval($dept['useavgWage']);
	$budgetVal = 0;
	$productivity = '';
	$shiftName = '';
	$totalPromoVal = 0;
	$trackDischarges = 0;
	$displayProd = intval($dept['rnVariance']);
	
	
	//if($displayProd==0){
		//$prodCalc = intval($dept['productivityPosNeg']) * -1;
	//}else{
		$prodCalc = intval($dept['productivityPosNeg']);
	//}
	$avgWage = floatval($dept['avgWage']);
	
	$budgethrsVar = floatval($dept['totalHrs']);
	$budgetVar = round(($avgWage * $budgethrsVar),0);
	
	
	$budgetValcalc = round(floatval($dept['budgetVar']),0);
	
	
	
	$uosValue = $dept['uosValue'];
	//$trackdata = $dept['trackdata'];
	$trackdc = $dept['trackdc'];
	$payPeriod = intval($dept['payPeriod']);
	$dashColor = intval($dept['prodIndicator']);
	$useGrid = intval($dept['useGrid']);
	
	$payFirst = $dept['payPeriodFirst'];
	$accountName = $dept['accountName'];
	$displayCompliance = intval($dept['displayCompliance']);
	$indMeasure = intval($dept['indMeasure']);
	$prodLabel = '';
	$note = '';
	//$trackNumber = '';

	
	if($unitCat==2 || $unitCat==3){
	
	
	$varianceWHP = "Hrs Variance";
	
	$plannedHrs = $dept['uosDesc'];
	$dischargeLabel = '';
	$plannedVar = '';
	$chart1 = "Hours Variance";
	$chart2 = "WHPUOS";
	$chart3 = "Total Hours";
	$chart4 = "Total UOS";
	
	if($showProd==2){
	$prodLabel = "$ Variance";
	}else if($showProd==3){
	$prodLabel = "WHPUOS";
	}else if($showProd==4){
	$prodLabel = "HRS Variance";
	}else if($showProd==5){
	$prodLabel = "RN Variance";
	}else if($showProd==6){
	$prodLabel = "Grid Variance";
	}else if($showProd==1){
	$prodLabel = "Prod% (est.)";
	}else{
	$prodLabel = '';
	}
	
	$hidePlanned = 0;
	$thresholdHighwhp = ($dept['thresholdHigh'] / 100) * $targethppd;
	$thresholdLowwhp = ($dept['thresholdLow'] / 100) * $targethppd;
	$thresholdLow = $dept['thresholdLow'];
	$thresholdHigh = $dept['thresholdHigh'];
	$rnThresholdHigh = intval($dept['rnThreshold']);
	$rnThresholdLow = intval($dept['rnThreshold']) * -1;
	$hrsThresholdLow = intval($dept['rnThreshold']);
	$hrsThresholdHigh = intval($dept['rnThreshold']) * -1;
	$totalCan = round($dept['hrsVar'],2);
	
	if(count($dbDept)>0 && intval($role)<=12){
	$deptName = "<a href=\"#supportstaffing?id=".$deptId."&s=".$startdiff."&e=".$enddiff."\" >" . $dept['dept']."</a>";	
	$chartLink = "<a href=\"#report?id=".$deptId."\" > <i class=\"flaticon-line-graph\"></i></a>";
	}else if(count($dbDept)>0 && intval($role)>12){
	$deptName = "<a href=\"#supportstaffing?id=".$deptId."&s=".$startdiff."&e=".$enddiff."\" >" . $accountName . " - " . $dept['dept']."</a>";
	$chartLink = "<a href=\"#report?id=".$deptId."\" > <i class=\"flaticon-line-graph\"></i></a>";
	}else{
	$deptName = $dept['dept'];	
	}
	
	if($start==$end){
		$compliance= -1;
	}else{
	$complianceData = Config::get('db')->get_results("
		SELECT 
           SUM((CASE WHEN n.userId>0 THEN 1 ELSE 0 END)) as report,  
		   SUM((CASE WHEN n.userId=0 THEN 1 ELSE 0 END)) as noreport
        FROM
            `productiveNewData` n 
        LEFT JOIN 
			`ProductiveDept` as d on d.id = n.deptId 
        WHERE
            n.deptId={$deptId} AND n.activeRecord>0 AND n.type<2
        AND 
            n.dayDate >= '{$startDate}' AND 
            n.dayDate <= '{$endDate}'            
    ");	
	
	$totalreports = (isset($complianceData[0])) ? $complianceData[0]['report'] : 0;
	$noreports = (isset($complianceData[0])) ? $complianceData[0]['noreport'] : 0;
	
		if(intval($totalreports)>0){
			$compliance = ROUND(($totalreports / ($totalreports + $noreports)*100),1);
		}else{
			$compliance = 0;
		}	
	}
		
	
	if($start==$end){

	$canData = Config::get('db')->get_results("
		
        SELECT 
			n.budgetValue AS laborTotal, DATE_FORMAT(n.dayDate, '%m/%d/%y') as dayDate, s.shift as shiftName, n.actualWHP, n.gvariance, n.trackDischarges as avgDischarges, n.nproductivity as prodAvg, d.prodMeasure, d.prodMeasure1, IFNULL(e.escalation,'0') as eType, a.id as escId, a.note as escComment, n.id as dataId, d.hppd, d.avgWage, 
			SUM(n.skill1val+n.skill2val+n.skill3val+n.skill4val+n.skill5val+n.skill6val+n.skill7val+n.skill8val+n.skill9val+n.skill10val+n.addResourceHrs) AS laborHours, n.hrsVariance as hrsVar, 
			(CASE WHEN n.visits>0 THEN n.visits ELSE n.procedureCount END) as procedures, IFNULL(n.note,'0') as prodNote, IFNULL(a.note,'0') as escNote, n.note
        FROM
            `productiveNewData` n 
        LEFT JOIN 
			`ProductiveDept` as d on d.id = n.deptId
		LEFT JOIN
			`productiveShifts` as s on s.id=n.shift
		LEFT OUTER JOIN
            `productiveEscalations` as a on a.deptId=n.deptId AND a.active>0 AND DATE_FORMAT(a.dateSubmitted, '%Y-%m-%d')>='{$start}'
		LEFT OUTER JOIN
			`productiveAcctEscalations` as e on e.id = a.escalation
		WHERE
             n.deptId={$deptId} AND n.type!=2 AND n.userId>0
        AND 
            n.dayDate >= '{$startDate}' AND 
            n.dayDate <= '{$endDate}'
		ORDER BY n.shift ASC
    ");	
		
		$shiftName = (isset($canData[0]['dayDate'])) ? ($canData[0]['dayDate'] . " - " . $canData[0]['shiftName']) : '';
		$newEscalation = (isset($canData[0])) ? ($canData[0]['eType']) : 0;
		$escComment = (isset($canData[0])) ? ($canData[0]['escComment']) : '';
		$escId = (isset($canData[0])) ? ($canData[0]['escId']) : '';
		$prodNote = (isset($canData[0])) ? ($canData[0]['prodNote']) : '';
		$escNote = (isset($canData[0])) ? ($canData[0]['escNote']) : '';
		
		$prodMeasure = (isset($canData[0])) ? $canData[0]['prodMeasure1'] : 0;
		
		$totalMsg1 = (isset($canData[0])) ? $canData[0]['laborHours'] : 0;
		$staffCount = round($totalMsg1,0);
		
		$pCount = round($totalCTR1,0);
		$totalCan = (isset($canData[0])) ? floatval($canData[0]['hrsVar']) : 0;
		$totalBlocked = 0;
		$bedsBlocked = 0;
		$totalpercent = (isset($canData[0])) ? round($canData[0]['prodAvg'],1) : 0;
		$note = (isset($canData[0])) ? $canData[0]['note'] : '';
		
		
	
		
		if($canData[0]['dataId'] && $useGrid==0){
		$productivity = '<a href="javascript:;" style="color:white" onclick="tj.staffSummary('.$canData[0]['dataId'].','.$canData[0]['prodMeasure1']. ')" ><u>Staff Hours</u></a>';
		$totalCTR1 = (isset($canData[0])) ? $canData[0]['procedures'] : 0;
		}else if($useGrid >0){
		$productivity = '<a href="javascript:;" style="color:white" onclick="tj.staffSummary('.$canData[0]['dataId'].','.$canData[0]['prodMeasure1']. ')" ><u>Staff</u></a>';
		$totalCTR1 = 1;
		}else{
		$productivity = "Staff Hours";
		$totalCTR1 = (isset($canData[0])) ? $canData[0]['procedures'] : 0;
		}
		
		if(count($canData) > 0 && intval($canData[0]['procedures'])>0){
			$totalProd = round(floatval($canData[0]['laborHours']) / floatval($canData[0]['procedures']),3);
		}else{
			$totalProd = 0.0;
		}
		
		
		if($showProd==2 && $budgetValcalc<0){
			$totalProdVal =$budgetValcalc;
		}else if($showProd==2 && $budgetValcalc>=0){
			$totalProdVal =0;
		}else if($showProd==3){
			$totalProdVal = $totalProd;
		}else if($showProd==1){
			$totalProdVal = $totalpercent;
		}else if($showProd==6){
			$totalProdVal = round($canData[0]['gVariance'],1);
		}else{
			$totalProdVal = $totalCan;
		}
		
	}else{
	$canData = Config::get('db')->get_results("
		
       SELECT 
			SUM(n.budgetValue) AS laborTotal, AVG(n.actualWHP) as actualWHP, DATE_FORMAT(n.dayDate, '%m/%d/%y') as dayDate, s.shift as shiftName, AVG(n.trackDischarges) as avgDischarges, AVG(n.gvariance) as gvariance, AVG(n.nproductivity) as prodAvg, n.id as dataId, d.hppd, d.prodMeasure1, d.avgWage, 
			SUM(n.skill1val+n.skill2val+n.skill3val+n.skill4val+n.skill5val+n.skill6val+n.skill7val+n.skill8val+n.skill9val+n.skill10val+n.addResourceHrs) AS laborHours, SUM(n.hrsVariance) as hrsVar, 
			SUM((CASE WHEN n.visits>0 THEN n.visits ELSE n.procedureCount END)) as procedures
        FROM
            `productiveNewData` n 
        LEFT JOIN 
			`ProductiveDept` as d on d.id = n.deptId
		LEFT JOIN
			`productiveShifts` as s on s.id=n.shift
        WHERE
             n.deptId={$deptId} AND n.type!=2 AND n.userId>0
        AND 
            n.dayDate >= '{$startDate}' AND 
            n.dayDate <= '{$endDate}'
		GROUP BY n.deptId           
    ");

	//$budgethrsVar = floatval($canData[0]['hrsVar']);
	//$budgetVar = round((floatval($canData[0]['avgWage']) * $budgethrsVar),0);
	//$budgetValcalc = $budgetVar * intval($prodCalc);
		
	//$budgetVal1 = (isset($canData[0])) ? $canData[0]['laborTotal'] : 0;
	//$budgetVal = ROUND($budgetVal1,2);
	$shiftName = (isset($canData[0])) ? ($canData[0]['dayDate'] . " - " . $canData[0]['shiftName']) : '';
	//$shiftName = $canData[0]['dayDate'] . " - " . $canData[0]['shiftName'];
	$prodMeasure = (isset($canData[0])) ? $canData[0]['prodMeasure1'] : 0;
	$totalMsg1 = (isset($canData[0])) ? $canData[0]['laborHours'] : 0;
	$staffCount = round($totalMsg1,0);
	$totalCTR1 = (isset($canData[0])) ? $canData[0]['procedures'] : 0;
	$pCount = round($totalCTR1,0);
	$totalCan = (isset($canData[0])) ? floatval($canData[0]['hrsVar']) : 0;
	$totalpercent = (isset($canData[0])) ? round($canData[0]['prodAvg'],1) : 0;
	$totalBlocked = 0;
	$bedsBlocked = 0;

		
	if(count($canData) > 0 && intval($canData[0]['procedures'])>0){
			$totalProd = round(floatval($canData[0]['laborHours']) / floatval($canData[0]['procedures']),3);
		}else{
			$totalProd = 0.0;
		}
		
	if($showProd==2 && $budgetValcalc<0){
			$totalProdVal =$budgetValcalc;
		}else if($showProd==2 && $budgetValcalc>=0){
			$totalProdVal =0;
		}else if($showProd==3){
			$totalProdVal = $totalProd;
		}else if($showProd==1){
			$totalProdVal = $totalpercent;
		}else if($showProd==6){
			$totalProdVal = round($canData[0]['gvariance'],1);
		}else{
			$totalProdVal = $totalCan;
		}
		
	if($canData[0]['dataId'] && $useGrid==0){
		//$productivity = '<a href="javascript:;" style="color:white" onclick="tj.staffSummary('.$canData[0]['dataId'].','.$canData[0]['prodMeasure1']. ')" ><u>Staff Hours</u></a>';
		$totalCTR1 = (isset($canData[0])) ? $canData[0]['procedures'] : 0;
		}else if($useGrid >0){
		//$productivity = '<a href="javascript:;" style="color:white" onclick="tj.staffSummary('.$canData[0]['dataId'].','.$canData[0]['prodMeasure1']. ')" ><u>Staff</u></a>';
		$totalCTR1 = 1;
		}else{
		//$productivity = "Staff Hours";
		$totalCTR1 = (isset($canData[0])) ? $canData[0]['procedures'] : 0;
		}
	$productivity = "Staff Hours";
	
	$escalationData = Config::get('db')->get_results("
      SELECT 
           a.*, IFNULL(e.escalation,'0') as eType, DATE_FORMAT(a.dateSubmitted,'%m/%d') as displayDate
        FROM
            `productiveEscalations` a
		LEFT JOIN
			`productiveAcctEscalations` as e on e.id = a.escalation
        WHERE
            a.deptId ={$deptId} AND a.active>0
        AND 
            a.dateSubmitted >= '{$startDate}' AND 
            a.dateSubmitted <= '{$endDate}'  
		ORDER BY 
			a.id DESC
    ");
    $newEscalation = (isset($escalationData[0])) ? ($escalationData[0]['eType'] . "-" . $escalationData[0]['displayDate']) : 0;
	$escComment = (isset($escalationData[0])) ? ($escalationData[0]['note']) : '';
	$escId = (isset($escalationData[0])) ? ($escalationData[0]['id']) : '';

	}

	}else{
		
	if(intval($role)<12){
		$plannedVar1 = '<a href="javascript:;" style="color:white" onclick="blockedBeds('.$accountId.','.$deptId.')"><u>/Blocked</u></a>';	
		}else{
		$plannedVar1 = '';	
		}
	
	if($displayProd==0){
	$varianceWHP = "RN Variance";
	//$prodLabel2 = "RN Prod% (est.)";
	}else if($displayProd==3){
	$varianceWHP = "Grid RN Variance";
	//$prodLabel2 = "Grid Prod% (est.)";
	}else if($displayProd==2){
	$varianceWHP = "Grid Variance";
	//$prodLabel2 = "Grid Prod% (est.)";
	}else{
	$varianceWHP = "Hrs Variance";
	//$prodLabel2 = "Hrs Prod% (est.)";	
	}
	
	$plannedHrs = "Patients";
	$dischargeLabel = "Discharges";
	$plannedVar = "Beds" . $plannedVar1;
	
	if($showProd==2){
	$prodLabel = "$ Variance";
	}else if($showProd==3){
	$prodLabel = "WHPUOS";
	}else if($showProd==4){
	$prodLabel = "Hrs Variance";
	}else if($showProd==5){
	$prodLabel = "RN Variance";
	}else if($showProd==6){
	$prodLabel = "Grid Variance";
	}else if($showProd==1 && ($displayProd==0 || $displayProd==3)){
	$prodLabel = "RN Prod% (est.)";
	}else if($showProd==1 && $displayProd==1){
	$prodLabel = "Hrs Prod% (est.)";
	}else if($showProd==1 && $displayProd==2){
	$prodLabel = "Grid Prod% (est.)";
	}else{
		$prodLabel = "Prod% (est.)";
	}
	
	$chart1 = "Avg. Variance";
	$chart2 = "Productivity% (est.)";
	$chart3 = "Patients";
	$chart4 = "Beds";
	
	$thresholdLow = $dept['thresholdLow'];
	$rnThresholdLow = intval($dept['rnThreshold']) * -1;
	$thresholdHigh = $dept['thresholdHigh'];
	$rnThresholdHigh = intval($dept['rnThreshold']);
	$thresholdHighwhp = ($dept['thresholdHigh'] / 100) * $targethppd;
	$thresholdLowwhp = ($dept['thresholdLow'] / 100) * $targethppd;
	$hrsThresholdLow = intval($dept['rnThreshold']);
	$hrsThresholdHigh = intval($dept['rnThreshold']) * -1;
	$totalProd = "n/a";
	$hidePlanned = 1;
	//$rnThresholdLow = $rnThresholdHigh * -1;
	
	if(count($dbDept)>0 && intval($role)<=12){
	$deptName = "<a href=\"#staffing?id=".$deptId."&s=".$startdiff."&e=".$enddiff."\" >" . $dept['dept']."</a>";
	$chartLink = "<a href=\"#report?id=".$deptId."\" > <i class=\"flaticon-line-graph\"></i></a>";	
	}else if(count($dbDept)>0 && intval($role)>12){
	$deptName = "<a href=\"#staffing?id=".$deptId."&s=".$startdiff."&e=".$enddiff."\" >" . $accountName . " - " . $dept['dept']."</a>";	
	$chartLink = "<a href=\"#report?id=".$deptId."\" > <i class=\"flaticon-line-graph\"></i></a>";
	}else{
	$deptName = $dept['dept'];	
	}

    if($start==$end){
		$compliance= -1;
	}else{
	$complianceData = Config::get('db')->get_results("
		SELECT 
           SUM((CASE WHEN n.userId>0 AND n.dayDate <= CURDATE() THEN 1 ELSE 0 END)) as report,  
		   SUM((CASE WHEN n.userId=0 AND n.dayDate <= CURDATE() THEN 1 ELSE 0 END)) as noreport
        FROM
            `productiveNewData` n 
        LEFT JOIN 
			`ProductiveDept` as d on d.id = n.deptId 
        WHERE
            n.deptId={$deptId}
        AND 
            n.dayDate >= '{$startDate}' AND 
            n.dayDate <= '{$endDate}'            
    ");	
	
	$totalreports = (isset($complianceData[0])) ? $complianceData[0]['report'] : 0;
	$noreports = (isset($complianceData[0])) ? $complianceData[0]['noreport'] : 0;
	
		if(intval($totalreports)>0){
			$compliance = ROUND(($totalreports / ($totalreports + $noreports)*100),1);
		}else{
			$compliance = 0;
		}
	}
	
	///n.nvariance AS canTotal, SUM(n.rhrsVariance) as rhrsVariance, SUM(ghrsVariance) as ghrsVariance, SUM(n.budgetVar) as budgetVar, SUM(n.rbudgetVar) as rbudgetVar, SUM(n.gbudgetVar) as gbudgetVar, AVG(n.actualWHP) as actualWHP, n.gvariance as gVariance, AVG(n.customProd) as customProd, AVG(n.lproductivity) as lproductivity, AVG(n.trackDischarges) as avgDischarges, IFNULL(n.note,'0') as prodNote, IFNULL(a.note,'0') as escNote, n.note, IFNULL(b.blockedBeds,0) as bedsBlocked, a.note as escComment, a.id as escId, (d.totalbeds - (n.atotal+n.ltotal)-IFNULL(AVG(b.blockedBeds),0)) AS openBeds, SUM(n.hrsVariance) as hrsVariance, AVG(n.nproductivity) as nproductivity, n.shift, IFNULL(e.escalation,'0') as eType, n.id as dataId, (n.atotal+n.ltotal) AS patientTotal, (n.chargecount + n.customNurse + n.customNurse2 + n.techcount + n.seccount + n.antecount + n.sittercount + n.otherNurse1 + n.otherNurse2 + n.otherNurse3) as staffcount
       
	if($start==$end){
	$canData = Config::get('db')->get_results("
		
        SELECT 
           n.nvariance AS canTotal, DATE_FORMAT(n.dayDate, '%m/%d/%y') as dayDate, s.shift as shiftName, n.gridrnvariance, n.rhrsVariance, n.ghrsVariance, n.budgetVar, n.rbudgetVar, n.gbudgetVar, n.actualWHP, n.gvariance as gVariance, n.customProd, n.lproductivity, n.trackDischarges as avgDischarges, IFNULL(n.note,'0') as prodNote, IFNULL(a.note,'0') as escNote, n.note, IFNULL(b.blockedBeds,0) as bedsBlocked, a.note as escComment, a.id as escId, (d.totalbeds - (n.atotal+n.patientCount2)-IFNULL(b.blockedBeds,0)) AS openBeds, n.hrsVariance, n.nproductivity, n.shift, IFNULL(e.escalation,'0') as eType, n.id as dataId, (n.atotal+n.patientCount2) AS patientTotal, (n.chargecount + n.customNurse + n.customNurse2 + n.techcount + n.seccount + n.antecount + n.sittercount + n.otherNurse1 + n.otherNurse2 + n.otherNurse3) as staffcount
        FROM
            `productiveNewData` n
		LEFT JOIN `ProductiveDept` as d on d.id = n.deptId 
		LEFT JOIN `productiveblockedBeds` as b on b.deptId=n.deptId 
		LEFT JOIN `productiveShifts` as s on s.id=n.shift
		LEFT OUTER JOIN `productiveEscalations` as a on a.deptId=n.deptId AND a.active>0 AND DATE_FORMAT(a.dateSubmitted, '%Y-%m-%d')='{$start}'
		LEFT JOIN `productiveAcctEscalations` as e on e.id = a.escalation
        WHERE
            n.userId>0 AND n.deptId={$deptId} 
        AND 
            n.dayDate >= '{$startDate}' AND 
            n.dayDate <= '{$endDate}' 
		ORDER BY 
			n.shift ASC
    ");

    $newEscalation = (isset($canData[0])) ? ($canData[0]['eType']) : 0;
	$escComment = (isset($canData[0])) ? ($canData[0]['escComment']) : '';
	$escId = (isset($canData[0])) ? ($canData[0]['escId']) : '';
	
	$prodNote = (isset($canData[0])) ? ($canData[0]['prodNote']) : '';
	$escNote = (isset($canData[0])) ? ($canData[0]['escNote']) : '';
	
	//$trackNote1 = (isset($canData[0])) ? $canData[0]['trackNote1'] : '';
	//$trackNote2 = (isset($canData[0])) ? $canData[0]['trackNote2'] : '';
	//$trackuserId = (isset($canData[0])) ? $canData[0]['trackuserId'] : '';
	//$trackdayTime = (isset($canData[0])) ? $canData[0]['dayTime'] : '';
	//$trackType = (isset($canData[0])) ? $canData[0]['trackType'] : 0;
	//$trackId = (isset($canData[0])) ? $canData[0]['trackId'] : '';
	
	if($canData[0]['dataId']){
		$productivity = '<a href="javascript:;" style="color:white" onclick="tj.staffSummary('.$canData[0]['dataId'].')" ><u>Staff Count</u></a>';
		}else{
		$productivity = "Staff Count";
		}
	}else{
	
	$canData = Config::get('db')->get_results("
		
        SELECT 
           AVG(n.nvariance) AS canTotal, DATE_FORMAT(n.dayDate, '%m/%d/%y') as dayDate, s.shift as shiftName, AVG(n.gridrnvariance) as gridrnvariance, SUM(n.rhrsVariance) as rhrsVariance, SUM(ghrsVariance) as ghrsVariance, SUM(n.budgetVar) as budgetVar, SUM(n.rbudgetVar) as rbudgetVar, SUM(n.gbudgetVar) as gbudgetVar, AVG(n.actualWHP) as actualWHP, AVG(n.gvariance) as gVariance, AVG(n.customProd) as customProd, AVG(n.lproductivity) as lproductivity, AVG(n.trackDischarges) as avgDischarges, SUM(n.hrsVariance) as hrsVariance, AVG(n.nproductivity) as nproductivity, AVG(n.chargecount + n.customNurse + n.techcount + n.seccount + n.antecount + n.sittercount + n.otherNurse1 + n.otherNurse2 + n.otherNurse3) as staffcount,
		   n.id as dataId, AVG(n.atotal+n.patientCount2) AS patientTotal, (d.totalbeds - (AVG(n.atotal+n.patientCount2)+IFNULL(AVG(b.blockedBeds),0))) AS openBeds, IFNULL(b.blockedBeds,0) as bedsBlocked, d.prodMeasure1
        FROM
            `productiveNewData` n
		LEFT JOIN `ProductiveDept` as d on d.id = n.deptId
		LEFT JOIN `productiveblockedBeds` as b on b.deptId=n.deptId
		LEFT JOIN `productiveShifts` as s on s.id=n.shift
        WHERE
            n.userId>0 AND n.deptId={$deptId} 
        AND 
            n.dayDate >= '{$startDate}' AND 
            n.dayDate <= '{$endDate}' 
		ORDER BY 
			n.shift ASC
		
    ");

	$escalationData = Config::get('db')->get_results("
      SELECT 
           a.*, IFNULL(e.escalation,'0') as eType, DATE_FORMAT(a.dateSubmitted,'%m/%d') as displayDate
        FROM
            `productiveEscalations` a
		LEFT JOIN
			`productiveAcctEscalations` as e on e.id = a.escalation
        WHERE
            a.deptId ={$deptId} AND a.active>0
        AND 
            a.dateSubmitted >= '{$startDate}' AND 
            a.dateSubmitted <= '{$endDate}' 
		ORDER BY 
			a.id DESC
    ");
    $newEscalation = (isset($escalationData[0])) ? ($escalationData[0]['eType'] . "-" . $escalationData[0]['displayDate']) : 0;
	$escComment = (isset($escalationData[0])) ? ($escalationData[0]['note']) : '';
	$escId = (isset($escalationData[0])) ? ($escalationData[0]['id']) : '';
	$productivity = "Staff Count";
	//$trackNote1 = '';
	//$trackNote2 = '';
	//$trackuserId = '';
	//$trackdayTime = '';
	//$trackType = 0;
	//$trackId = '';
	}
	
	if($displayProd==0){
    $totalCan = (isset($canData[0])) ? $canData[0]['canTotal'] : 0;
	//$totalpercent = (isset($canData[0])) ? round($canData[0]['nproductivity'],1) : 0;
	}else if($displayProd==2){
	$totalCan = (isset($canData[0])) ? round($canData[0]['gVariance'],0) : 0;
	//$totalpercent = (isset($canData[0])) ? round($canData[0]['customProd'],1) : 0;
	}else if($displayProd==3){
	$totalCan = (isset($canData[0])) ? round($canData[0]['gridrnvariance'],0) : 0;
	//$totalpercent = (isset($canData[0])) ? round($canData[0]['lproductivity'],1) : 0;	
	}else{
	$totalCan = (isset($canData[0])) ? round($canData[0]['hrsVariance'],1) : 0;
	//$totalpercent = (isset($canData[0])) ? round($canData[0]['lproductivity'],1) : 0;	
	}
	$shiftName = (isset($canData[0])) ? ($canData[0]['dayDate'] . " - " . $canData[0]['shiftName']) : '';
	//$shiftName = $canData[0]['dayDate'] . " - " . $canData[0]['shiftName'];
	if($showProd==3){
	$totalProdVal = (isset($canData[0])) ? round($canData[0]['actualWHP'],1) : 0;	
	}else if($showProd==5){
	$totalProdVal = (isset($canData[0])) ? round($canData[0]['canTotal'],0) : 0;	
	}else if($showProd==6){
	$totalProdVal = (isset($canData[0])) ? round($canData[0]['gVariance'],1) : 0;	
	}else if($showProd==1 && $displayProd==0){
	$totalProdVal = (isset($canData[0])) ? round($canData[0]['nproductivity'],1) : 0;
	}else if($showProd==1 && $displayProd==1){
	$totalProdVal = (isset($canData[0])) ? round($canData[0]['lproductivity'],1) : 0;
	}else if($showProd==1 && $displayProd==2){
	$totalProdVal = (isset($canData[0])) ? round($canData[0]['customProd'],1) : 0;
	}else if($showProd==4 && $displayProd==0){
	$totalProdVal = (isset($canData[0])) ? round($canData[0]['rhrsVariance'],1) : 0;	
	}else if($showProd==4 && $displayProd==1){
	$totalProdVal = (isset($canData[0])) ? round($canData[0]['hrsVariance'],1) : 0;	
	}else if($showProd==4 && $displayProd==2){
	$totalProdVal = (isset($canData[0])) ? round($canData[0]['ghrsVariance'],1) : 0;	
	}else if($showProd==2 && $displayProd==0 && $budgetValcalc<0){
	$totalProdVal = (isset($canData[0])) ? round($canData[0]['rbudgetVar'],0) : 0;	
	}else if($showProd==2 && $displayProd==1 && $budgetValcalc<0){
	$totalProdVal = (isset($canData[0])) ? round($canData[0]['budgetVar'],0) : 0;	
	}else if($showProd==2 && $displayProd==2 && $budgetValcalc<0){
	$totalProdVal = (isset($canData[0])) ? round($canData[0]['gbudgetVar'],0) : 0;	
	}else{
	$totalProdVal =0;
	}		
	
	$totalMsg1 = (isset($canData[0])) ? $canData[0]['patientTotal'] : 0;
	$pCount = round($totalMsg1,0);
	$staffCount = (isset($canData[0])) ? round($canData[0]['staffcount'],0) : 0;
	$trackDischarges = (isset($canData[0])) ? round($canData[0]['avgDischarges'],0) : 0;
	$totalBlocked = (isset($canData[0])) ? round($canData[0]['openBeds'],0) : 0;
	$bedsBlocked = (isset($canData[0])) ? round($canData[0]['bedsBlocked'],0) : 0;
	
	
	$prodMeasure = (isset($canData[0])) ? $canData[0]['prodMeasure1'] : 0;
	$note = (isset($canData[0])) ? $canData[0]['note'] : '';
	
	
	}
	
	if($start==$end){
    $churnData = Config::get('db')->get_results("
      SELECT 
           SUM(n.churnValue) AS laborTotal
        FROM
            `productiveNewData` n 
        WHERE
            n.deptId={$deptId} AND (n.admits >0 OR n.transfers >0 OR n.discharges >0) 
        AND 
            n.dayDate >= '{$startDate}' AND 
            n.dayDate <= '{$endDate}'
		ORDER BY 
			n.shift ASC
    ");
	}else{
	 $churnData = Config::get('db')->get_results("
      SELECT 
           AVG(n.churnValue) AS laborTotal
        FROM
            `productiveNewData` n 
        WHERE
            n.deptId={$deptId} AND (n.admits >0 OR n.transfers >0 OR n.discharges >0) 
        AND 
            n.dayDate >= '{$startDate}' AND 
            n.dayDate <= '{$endDate}'
    ");	
	}
	$churnVal1 = (isset($churnData[0])) ? $churnData[0]['laborTotal'] : 0;
	$churnVal = ROUND($churnVal1,1);
   
	$outData['shiftName'.$i.''] = $shiftName;
	$outData['sortHide'] = $sortHide;
	$outData['displayCompliance'] = $displayCompliance;
	$outData['startDiff'] = $startdiff;
	$outData['endDiff'] = $enddiff;
	$outData['indMeasure'.$i.''] = $indMeasure;
    //$outData['totalPromo'.$i.''] = $totalPromo;
	//$outData['totalPromoVal'.$i.''] = $totalPromoVal;
	$outData['budgetCalc'.$i.''] =$budgetValcalc;
	$outData['hidePlanned'.$i.''] = $hidePlanned;
	$outData['totalProdVal'.$i.''] = $totalProdVal;
    
	$outData['accountId'.$i.''] = $accountId;
	$outData['deptId'.$i.''] = $deptId;
	
	$outData['newEscalation'.$i.''] = $newEscalation;
	$outData['escComment'.$i.''] = $escComment;
	$outData['escId'.$i.''] = $escId;
	
	$outData['escNote'.$i.''] = $escNote;
	$outData['prodNote'.$i.''] = $prodNote;
	
	$outData['dept'.$i.''] = $deptName;
	$outData['chartLink'.$i.''] = $chartLink;
	$outData['prodMeasure'.$i.''] = $prodMeasure;
	
	$outData['productivity'.$i.''] = $productivity;
	$outData['staffCount'.$i.''] = $staffCount;
	
	$outData['variance'.$i.''] = $varianceWHP;
	$outData['totalCan'.$i.''] = $totalCan;
	
	$outData['prodLabel'.$i.''] = $prodLabel;
	$outData['totalProd'.$i.''] = $totalProd;
	
	$outData['planned'.$i.''] = $plannedHrs;
	$outData['pCount'.$i.''] = $pCount;
	
	$outData['discharges'.$i.''] = $dischargeLabel;
	$outData['totalDischarges'.$i.''] = $trackDischarges;
	
	
	$outData['blockedbeds'.$i.''] = $plannedVar;
	$outData['totalBlocked'.$i.''] = $totalBlocked;
	$outData['bedsBlocked'.$i.''] = $bedsBlocked;
	
	//$outData['trackLabel'.$i.''] = $trackLabel;
	//$outData['trackNumber'.$i.''] = $trackNumber;
	//$outData['trackNote1'.$i.''] = $trackNote1;
	//$outData['trackNote2'.$i.''] = $trackNote2;
	//$outData['trackuserId'.$i.''] = $trackuserId;
	//$outData['trackdayTime'.$i.''] = $trackdayTime;
	//$outData['trackType'.$i.''] = $trackType;
	//$outData['trackId'.$i.''] = $trackId;
		
	$outData['hppd'.$i.''] = $totalPromo;
	$outData['target'.$i.''] = $targethppd;
	$outData['pMeasure'.$i.''] = $pMeasure;
	$outData['thresholdLow'.$i.''] = $thresholdLow;
	$outData['thresholdHigh'.$i.''] = $thresholdHigh;
	$outData['hrsthresholdLow'.$i.''] = $hrsThresholdLow;
	$outData['hrsthresholdHigh'.$i.''] = $hrsThresholdHigh;
	$outData['thresholdLowwhp'.$i.''] = $thresholdLowwhp;
	$outData['thresholdHighwhp'.$i.''] = $thresholdHighwhp;
	$outData['budgetValue'.$i.''] = $budgetVal;
	$outData['chart1'] = $chart1;
	$outData['chart2'] = $chart2;
	$outData['chart3'] = $chart3;
	$outData['chart4'] = $chart4;
	$outData['churn'.$i.''] = $churn;
	$outData['churnVal'.$i.''] = $churnVal;
	$outData['budgetMeasure'.$i.''] = $budgetMeasure;
	//$outData['PeriodStart'.$i.''] = $currentPeriodStart;
	$outData['deptCount'.$i.''] = $deptCount;
	$outData['compliance'.$i.''] = $compliance;
	$outData['rnThresholdHigh'.$i.''] = $rnThresholdHigh;
	$outData['rnThresholdLow'.$i.''] = $rnThresholdLow;
	$outData['dashColor'.$i.''] = $dashColor;
	$outData['showcompliance'.$i.''] = $displayProd;
	$outData['showProd'.$i.''] = $showProd;
	$outData['prodCalc'.$i.''] = $prodCalc;
	//$outData['trackdata'.$i.''] = $trackdata;
	$outData['trackdc'.$i.''] = $trackdc;
	$outData['note'.$i.''] = $note;
	
	
		if($i==1){	
		$outData['hidden11']=1;
		$outData['hidden12']=1;
		$outData['canGraphData'] = array('labels'=>$newCandidateGraphLabels,'data'=>$newCandidateGraphData);
		$outData['smsGraphData'] = array('labels'=>$smsSentGraphLabels,'data'=>$smsSentGraphData);
		$outData['laborGraphData'] = array('labels'=>$laborGraphLabels,'data'=>$laborGraphData);
		$outData['postpartumGraphData'] = array('labels'=>$postpartumGraphLabels,'data'=>$postpartumGraphData);
		}else{
		$outData['hidden11']=0;
		$outData['hidden12']=0;
		$outData['canGraphData'] = array('labels'=>$newCandidateGraphLabels,'data'=>$newCandidateGraphData);
		$outData['smsGraphData'] = array('labels'=>$smsSentGraphLabels,'data'=>$smsSentGraphData);
		$outData['laborGraphData'] = array('labels'=>$laborGraphLabels,'data'=>$laborGraphData);
		$outData['postpartumGraphData'] = array('labels'=>$postpartumGraphLabels,'data'=>$postpartumGraphData);
		}
	}
    echo json_encode(array('success' => true, 'data' => $outData), JSON_NUMERIC_CHECK);
}else{
exit();
}
	
}

/////////////////////////////////////
// PULL THE REPORT PAGE REPORT DATA
function GenerateDeliveryReports() {
	
    $startDate = isset($_REQUEST['start']) ? $_REQUEST['start'] : '2010-01-01';
	$start = $startDate;
    $startDate .= " 00:00:00";
    $endDate = isset($_REQUEST['end']) ? $_REQUEST['end'] : '2020-01-01';
	$end = $endDate;
    $endDate .= " 23:59:59";
	
    $accountId = Config::get('account')['accountId'];
	$userId = Config::get('account')['id'];
	$role = Config::get('account')['role'];
		
	$outData = array();
	$newCandidateGraphLabels = array();
    $newCandidateGraphData = array();
	$today = date("m/d/Y");
	$newGraphData = array();
	$newCount = round((strtotime($end) - strtotime($start))/ (60 * 60 * 24));
	
	for($count = 0; $count<=$newCount; $count++){
	//$todayCount = $today + $count;
	//$todayCount = " +" . $count . " day";
	$now = date('Y-m-d',strtotime($start . " +" . $count . " day"));
			
    $newDeliveryGraphData = Config::get('db')->get_results("
		SELECT x.accountId as accountId, 
		(SUM((CASE WHEN (DAYOFWEEK('{$now}')=1 OR DAYOFWEEK('{$now}')=7) AND p.comp>0 AND p.deliveryPlan!=3 THEN (.15 / ((p.rangeDays / 7) * 2)) ELSE 0 END)) +
		SUM((CASE WHEN (DAYOFWEEK('{$now}')=1 OR DAYOFWEEK('{$now}')=7) AND p.comp>0 AND p.deliveryPlan=3 THEN (.05 / ((p.rangeDays / 7) * 2)) ELSE 0 END)) +
		SUM((CASE WHEN (DAYOFWEEK('{$now}')=1 OR DAYOFWEEK('{$now}')=7) AND p.comp=0 AND p.deliveryPlan!=3 THEN (.3 / ((p.rangeDays / 7) * 2)) ELSE 0 END)) + 
		SUM((CASE WHEN (DAYOFWEEK('{$now}')=1 OR DAYOFWEEK('{$now}')=7) AND p.comp=0 AND p.deliveryPlan=3 THEN (.10 / ((p.rangeDays / 7) * 2)) ELSE 0 END)) +
		SUM((CASE WHEN (DAYOFWEEK('{$now}')!=1 AND DAYOFWEEK('{$now}')!=7 AND p.comp>0 AND p.deliveryPlan!=3) THEN (.85 / ((p.rangeDays / 7) * 5)) ELSE 0 END)) +
		SUM((CASE WHEN (DAYOFWEEK('{$now}')!=1 AND DAYOFWEEK('{$now}')!=7 AND p.comp>0 AND p.deliveryPlan=3) THEN (.95 / ((p.rangeDays / 7) * 5)) ELSE 0 END)) +
		SUM((CASE WHEN (DAYOFWEEK('{$now}')!=1 AND DAYOFWEEK('{$now}')!=7 AND p.comp=0 AND p.deliveryPlan=3) THEN (.9 / ((p.rangeDays / 7) * 5)) ELSE 0 END)) +
		SUM((CASE WHEN (DAYOFWEEK('{$now}')!=1 AND DAYOFWEEK('{$now}')!=7 AND p.comp=0 AND p.deliveryPlan!=3) THEN (.7 / ((p.rangeDays / 7) * 5)) ELSE 0 END))) as dayCount, 
		'{$now}' as groupDate
		FROM `productivePredictor` p
		LEFT JOIN `productivePredictorXref` as x on x.doctorId = p.doctorId 
		WHERE p.rangeHigh >='{$now}' AND p.rangeLow <= '{$now}' AND x.accountId = {$accountId} 
		GROUP BY groupDate
    ");
	
	$newCandidateGraphLabels[] = $newDeliveryGraphData[0]['groupDate'];
    $newCandidateGraphData[] = $newDeliveryGraphData[0]['dayCount'] *3;
	
	//$newGraphData[] = array(
	//						'groupDate' =>$newDeliveryGraphData['groupDate'], 
	//						'daycount' =>$newDeliveryGraphData['dayCount']
	//						);
		
	
	//$newDeliveryGraphData[] = implode(",",$newGraphData);

   // if ($newGraphData) {
   //     foreach ($newGraphData as $deliverygraphItem) {
   //     $newCandidateGraphLabels[] = $deliverygraphItem['groupDate'];
   //     $newCandidateGraphData[] = $deliverygraphItem['dayCount'];
   //    }
   // }

    $riskGraphData = Config::get('db')->get_results("
      SELECT x.accountId as accountId, 
		(SUM((CASE WHEN (DAYOFWEEK('{$now}')=1 OR DAYOFWEEK('{$now}')=7) AND p.comp>0 AND p.deliveryPlan!=3 AND p.grav=1 AND p.par=0 THEN (.15 / ((p.rangeDays / 7) * 2)) ELSE 0 END)) +
		SUM((CASE WHEN (DAYOFWEEK('{$now}')=1 OR DAYOFWEEK('{$now}')=7) AND p.comp>0 AND p.deliveryPlan=3 AND p.grav=1 AND p.par=0 THEN (.05 / ((p.rangeDays / 7) * 2)) ELSE 0 END)) +
		SUM((CASE WHEN (DAYOFWEEK('{$now}')=1 OR DAYOFWEEK('{$now}')=7) AND p.comp=0 AND p.deliveryPlan!=3 AND p.grav=1 AND p.par=0 THEN (.3 / ((p.rangeDays / 7) * 2)) ELSE 0 END)) + 
		SUM((CASE WHEN (DAYOFWEEK('{$now}')=1 OR DAYOFWEEK('{$now}')=7) AND p.comp=0 AND p.deliveryPlan=3 AND p.grav=1 AND p.par=0 THEN (.10 / ((p.rangeDays / 7) * 2)) ELSE 0 END)) +
		SUM((CASE WHEN (DAYOFWEEK('{$now}')!=1 AND DAYOFWEEK('{$now}')!=7 AND p.comp>0 AND p.deliveryPlan!=3) AND p.grav=1 AND p.par=0 THEN (.85 / ((p.rangeDays / 7) * 5)) ELSE 0 END)) +
		SUM((CASE WHEN (DAYOFWEEK('{$now}')!=1 AND DAYOFWEEK('{$now}')!=7 AND p.comp>0 AND p.deliveryPlan=3) AND p.grav=1 AND p.par=0 THEN (.95 / ((p.rangeDays / 7) * 5)) ELSE 0 END)) +
		SUM((CASE WHEN (DAYOFWEEK('{$now}')!=1 AND DAYOFWEEK('{$now}')!=7 AND p.comp=0 AND p.deliveryPlan=3) AND p.grav=1 AND p.par=0 THEN (.9 / ((p.rangeDays / 7) * 5)) ELSE 0 END)) +
		SUM((CASE WHEN (DAYOFWEEK('{$now}')!=1 AND DAYOFWEEK('{$now}')!=7 AND p.comp=0 AND p.deliveryPlan!=3) AND p.grav=1 AND p.par=0 THEN (.7 / ((p.rangeDays / 7) * 5)) ELSE 0 END))) as dayCount, 
		'{$now}' as groupDate
		FROM `productivePredictor` p
		LEFT JOIN `productiveUser` as u on u.id = p.userId 
		LEFT JOIN `productivePredictorXref` as x on x.doctorId = p.doctorId 
		WHERE p.rangeHigh >='{$now}' AND p.rangeLow <= '{$now}' AND x.accountId = {$accountId}
		GROUP BY groupDate
    ");
	
	$smsSentGraphLabels[] = $riskGraphData[0]['groupDate'];
    $smsSentGraphData[] = $riskGraphData[0]['dayCount'] *3;
    }
	//$smsSentGraphLabels = array();
    //$smsSentGraphData = array();

    //if (count($riskGraphData) > 0) {
    //    foreach ($riskGraphData as $riskgraphItem) {
    //        $smsSentGraphLabels[] = $riskgraphItem['groupDate'];
    //        $smsSentGraphData[] = $riskgraphItem['daycount'];
    //    }
    //}
	
	
	
	$outData['deliveryGraphData'] = array('labels'=>$newCandidateGraphLabels,'data'=>$newCandidateGraphData);
	$outData['riskGraphData'] = array('labels'=>$smsSentGraphLabels,'data'=>$smsSentGraphData);
	echo json_encode(array('success' => true, 'data' => $outData, 'date' =>$now), JSON_NUMERIC_CHECK);
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
    $dbData2 = Config::get('db')->get_results("SELECT x.*, d.dept, d.active, (SELECT COUNT(`dept`) from `productiveAccountXref` where `dept`=x.deptId) as shift_count FROM `productiveDeptXref` x LEFT JOIN `ProductiveDept` as d on d.id=x.deptId where d.active>0 $searchAdd group by x.deptId order by d.dept ASC");
    $deptCount = count($dbData2);
	
	$csv_filename = "download_" . date('Ymd') . ".csv";
	$output = fopen("php://output",'w') or die("Can't open php://output");
	//header('Content-Type: application/csv;
    header("Content-Disposition: attachment; filename=".$csv_filename."");
	$out = array();
	
	if(INTVAL($deptCount)!=1) {
		//$firstRow = $dbData2[0];
	    //$keys = array_keys($firstRow);
		$keys = array('account_ID','unit_ID','unit_Name','prod_Measure','UOS_Value','date_shift','date_submitted','submitted_by','nursing_charge_hours','nursing_rn_hours','nursing_custom_rn_hours','nursing_tech_hours','nursing_admin_hours','nursing_sitter_hours','nursing_custom1_hours','nursing_custom2_hours','nursing_custom3_hours','nursing_acuity_1to1','nursing_acuity_1to2','nursing_acuity_1to3','nursing_acuity_1to4','nursing_acuity_1to5','nursing_acuity_1to6','nursing_acuity_custom1','nursing_acuity_custom2','nursing_acuity_custom3','nursing_total_pts','support_UOS','customUOS1','customUOS2','customUOS3','support_hours','support_rn1_hours','support_rn2_hours','support_tech1_hours','support_tech2_hours','support_admin_hours','support_custom1_hours','support_custom2_hours','support_custom3_hours','support_other_hours','support_provider_hours','hrs_variance_submitted','WHPUOS_submitted','prod_submitted','hrs_variance_imported','WHPUOS_imported','totalHrs_imported','indirect_hrs','escalation_Type','escalation_Note','churn_admits','churn_discharges','churn_transfers','churn_custom','visitsSubmitted','hoursSubmitted','hrs1Submitted','hrs2Submitted','hrs3Submitted','report_notes','shift_count','SD','EE','deptID','Provider','dateNew','dateExisting','provName','recId','escResponse','escResponder','referrals','totalVisits','customUOS4','customUOS5','nursing_custom10','nursing_custom11');
     
        fputcsv($output,$keys);
		foreach($dbData2 as $dbDept){
			$shiftCount = intval($dbDept['shift_count']);
			$deptId = $dbDept['deptId'];
			//$dbData = Config::get('db')->get_results("SELECT a.accountId as account_ID, a.unitId as unit_ID, a.dept as unit_Name, m.name as prod_Measure, a.hppd as UOS_Value, CONCAT(DATE_FORMAT(n.dayDate,'%m/%d/%y'), '  ', s.shift) as date_shift, DATE_FORMAT(n.entered,'%m/%d/%Y %T') as date_submitted, CONCAT(SUBSTRING(i.first_name,1,1), '. ', i.last_name) as submitted_by, (n.chargecount * 24 / {$shiftCount}) as nursing_charge_hours, ((n.antecount+n.ldcount+n.ocount) * 24 / {$shiftCount}) as nursing_rn_hours, ((n.customNurse) * 24 / {$shiftCount}) as nursing_custom_rn_hours, ((n.techcount) * 24 / {$shiftCount}) as nursing_tech_hours, ((n.seccount) * 24 / {$shiftCount}) as nursing_admin_hours, ((n.sittercount) * 24 / {$shiftCount}) as nursing_sitter_hours, ((n.otherNurse1) * 24 / {$shiftCount}) as nursing_custom1_hours, ((n.otherNurse2) * 24 / {$shiftCount}) as nursing_custom2_hours, ((n.otherNurse3) * 24 / {$shiftCount}) as nursing_custom3_hours, n.oneto1 as nursing_acuity_1to1, n.oneto2 as nursing_acuity_1to2, n.oneto3 as nursing_acuity_1to3, n.oneto4 as nursing_acuity_1to4, n.oneto5 as nursing_acuity_1to5, n.oneto6 as nursing_acuity_1to6, n.oneto7 as nursing_acuity_custom1, n.oneto8 as nursing_acuity_custom2, n.oneto9 as nursing_acuity_custom3, (n.oneto1+n.oneto2+n.oneto3+n.oneto4+n.oneto5+n.oneto6) as nursing_total_pts, n.procedureCount as support_UOS, n.whpCustom as custom1visits, n.whpCustom2 as custom2visits, n.whpCustom3 as custom3visits, n.skill1val as support_hours, n.skill2val as support_rn1_hours, n.skill3val as support_rn2_hours, n.skill4val as support_tech1_hours, n.skill5val as support_tech2_hours, n.skill6val as support_admin_hours, n.skill7val as support_custom1_hours, n.skill8val as support_custom2_hours, n.skill9val as support_custom3_hours, n.skill10val as support_other_hours, n.skill11val as support_provider_hours, n.hrsVariance as hrs_variance_submitted, n.actualWHP as WHPUOS_submitted, n.nproductivity as prod_submitted, n.hrsVarianceActual as hrs_variance_imported, n.actualWHPimport as WHPUOS_imported, n.totalHours as totalHrs_imported, n.addResourceHrs as indirect_hrs, IFNULL(l.escalation,'') as escalation_Type, IFNULL(c.note,'') as escalation_Note, n.admits as churn_admits, n.discharges as churn_discharges, n.transfers as churn_transfers, n.customChurn as churn_custom, n.visitsSubmitted, n.hoursSubmitted, n.hrs1Submitted, n.hrs2Submitted, n.hrs3Submitted, n.note as report_notes,(select count(`dayDate`) from `productiveNewData` where `deptId`=n.deptId and `whpPlan`=0 and `dayDate`=n.dayDate) as shift_count, (CASE WHEN r.dayRank >0 THEN r.dayRank ELSE '' END) as SD, (CASE WHEN r.empEngagement>0 THEN r.empEngagement ELSE '' END) as EE, n.deptId as deptID, (CASE WHEN n.type=2 THEN 'Yes' ELSE 'No' END) as provider, (CASE WHEN r.dateNew='0000-00-00' THEN '' ELSE r.dateNew END) as dateNew, (CASE WHEN r.dateExisting='0000-00-00' THEN '' ELSE r.dateExisting END) as dateExst, IFNULL((select CONCAT(SUBSTRING(`first_name`,1,1), '. ', `last_name`) from `productiveUser` where `id`=n.provId),'') as provName, n.id, (select `response` from `productiveEscalations` where `dataId`=0 limit 0,1) as escResponse, (select i.last_name from `productiveUser` i  inner join `productiveEscalations` as b on b.lastupdateId=i.id and b.dataId=0 limit 0,1) as escResponder, r.referrals FROM `productiveNewData` n LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as a on a.id = n.deptId LEFT JOIN `productiveMeasures` as m on m.id=a.prodMeasure LEFT OUTER JOIN `productiveDayRank` as r on r.dayDate=n.dayDate AND r.deptId=n.deptId LEFT JOIN `productiveAccount` as t on t.id = a.accountId LEFT JOIN `productiveUser` as u on u.id ={$userId} LEFT OUTER JOIN `productiveUser` as i on i.id=n.userId LEFT OUTER JOIN `productiveEscalations` as c on c.dataId = n.id LEFT OUTER JOIN `productiveAcctEscalations` as l on l.id = c.escalation WHERE n.deptId={$deptId} AND n.userId>0 AND n.activeRecord>0 AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate $searchAdd2$searchAdd3 group by n.deptId, n.dayDate, n.id ORDER BY n.dayDate DESC, a.dept ASC, n.shift DESC");
			$dbData = Config::get('db')->get_results("SELECT n.*, a.unitId as unit_ID, a.dept as unit_Name, m.name as prod_Measure, a.hppd as UOS_Value, CONCAT(DATE_FORMAT(n.dayDate,'%m/%d/%y'), '  ', s.shift) as date_shift, DATE_FORMAT(n.entered,'%m/%d/%Y %T') as date_submitted, CONCAT(SUBSTRING(i.first_name,1,1), '. ', i.last_name) as submitted_by, (n.chargecount * 24 / {$shiftCount}) as nursing_charge_hours, ((n.antecount+n.ldcount+n.ocount) * 24 / {$shiftCount}) as nursing_rn_hours, ((n.customNurse) * 24 / {$shiftCount}) as nursing_custom_rn_hours, ((n.techcount) * 24 / {$shiftCount}) as nursing_tech_hours, ((n.seccount) * 24 / {$shiftCount}) as nursing_admin_hours, ((n.sittercount) * 24 / {$shiftCount}) as nursing_sitter_hours, ((n.otherNurse1) * 24 / {$shiftCount}) as nursing_custom1_hours, ((n.otherNurse2) * 24 / {$shiftCount}) as nursing_custom2_hours, ((n.otherNurse3) * 24 / {$shiftCount}) as nursing_custom3_hours, n.oneto1 as nursing_acuity_1to1, n.oneto2 as nursing_acuity_1to2, n.oneto3 as nursing_acuity_1to3, n.oneto4 as nursing_acuity_1to4, n.oneto5 as nursing_acuity_1to5, n.oneto6 as nursing_acuity_1to6, n.oneto7 as nursing_acuity_custom1, n.oneto8 as nursing_acuity_custom2, n.oneto9 as nursing_acuity_custom3, (n.oneto1+n.oneto2+n.oneto3+n.oneto4+n.oneto5+n.oneto6) as nursing_total_pts, n.procedureCount as support_UOS, n.whpCustom as custom1visits, n.whpCustom2 as custom2visits, n.whpCustom3 as custom3visits, n.whpCustom4 as custom4visits, n.whpCustom5 as custom5visits, n.skill1val as support_hours, n.skill2val as support_rn1_hours, n.skill3val as support_rn2_hours, n.skill4val as support_tech1_hours, n.skill5val as support_tech2_hours, n.skill6val as support_admin_hours, n.skill7val as support_custom1_hours, n.skill8val as support_custom2_hours, n.skill9val as support_custom3_hours, n.skill10val as support_other_hours, n.skill11val as support_provider_hours, n.hrsVariance as hrs_variance_submitted, n.actualWHP as WHPUOS_submitted, n.nproductivity as prod_submitted, n.hrsVarianceActual as hrs_variance_imported, n.actualWHPimport as WHPUOS_imported, n.totalHours as totalHrs_imported, n.addResourceHrs as indirect_hrs, IFNULL(l.escalation,'') as escalation_Type, IFNULL(c.note,'') as escalation_Note, n.admits as churn_admits, n.discharges as churn_discharges, n.transfers as churn_transfers, n.customChurn as churn_custom, n.visitsSubmitted, n.hoursSubmitted, n.hrs1Submitted, n.hrs2Submitted, n.hrs3Submitted, n.note as report_notes,(select count(`dayDate`) from `productiveNewData` where `deptId`=n.deptId and `whpPlan`=0 and `dayDate`=n.dayDate) as shift_count, (CASE WHEN r.dayRank >0 THEN r.dayRank ELSE '' END) as SD, (CASE WHEN r.empEngagement>0 THEN r.empEngagement ELSE '' END) as EE, n.deptId as deptID, (CASE WHEN n.type=2 THEN 'Yes' ELSE 'No' END) as provider, (CASE WHEN r.dateNew='0000-00-00' THEN '' ELSE r.dateNew END) as dateNew, (CASE WHEN r.dateExisting='0000-00-00' THEN '' ELSE r.dateExisting END) as dateExst, IFNULL((select CONCAT(SUBSTRING(`first_name`,1,1), '. ', `last_name`) from `productiveUser` where `id`=n.provId),'') as provName, c.response as escResponse, (select `last_name` from `productiveUser` where `id` = c.lastupdateId) as escResponder, (CASE WHEN r.userId=0 AND r.referrals=0 THEN '' ELSE r.referrals END) as referrals, a.customWHPvalue, a.customWHP2value, a.customWHP3value, a.customWHP4value, a.customWHP5value, (CASE WHEN a.prodMeasure1=2 THEN (n.procedureCount + (n.whpCustom * a.customWHPvalue) + (n.whpCustom2 * a.customWHP2value) + (n.whpCustom3 * a.customWHP3value) + (n.whpCustom4 * a.customWHP4value) + (n.whpCustom5 * a.customWHP5value)) ELSE '' END) as totalVisits FROM `productiveNewData` n LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as a on a.id = n.deptId LEFT JOIN `productiveMeasures` as m on m.id=a.prodMeasure1 LEFT OUTER JOIN `productiveDayRank` as r on r.dayDate=n.dayDate AND r.deptId=n.deptId LEFT JOIN `productiveAccount` as t on t.id = a.accountId LEFT JOIN `productiveUser` as u on u.id ={$userId} LEFT OUTER JOIN `productiveUser` as i on i.id=n.userId LEFT OUTER JOIN `productiveEscalations` as c on c.dataId = n.id LEFT OUTER JOIN `productiveAcctEscalations` as l on l.id = c.escalation WHERE n.deptId={$deptId} AND n.userId>0 AND n.activeRecord>0 AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate $searchAdd2$searchAdd3 group by n.deptId, n.dayDate, n.id ORDER BY n.dayDate DESC, a.dept ASC, n.shift DESC");
			 
			if($dbData) {
				foreach($dbData as $k=>$v){
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
					'oneto10' => $v['oneto10'],
					'oneto11' => $v['oneto11']

					);
					
					fputcsv($output,$out);
				}
			}else{
			//do nothing;
			}
			}
	}else if($dbData2 && INTVAL($deptCount)==1){
		$deptKeys = Config::get('db')->get_results("SELECT (CASE WHEN d.nurse1Desc='' THEN 'nursing_custom_rn_hours' ELSE d.nurse1Desc END) as nurse1Desc, (CASE WHEN d.other1Desc='' THEN 'nursing_custom1_hours' ELSE d.other1Desc END) as other1Desc, (CASE WHEN d.other2Desc='' THEN 'nursing_custom2_hours' ELSE d.other2Desc END) as other2Desc, (CASE WHEN d.other3Desc='' THEN 'nursing_custom3_hours' ELSE d.other3Desc END) as other3Desc, (CASE WHEN d.desc7='' THEN 'nursing_acuity_custom1' ELSE d.desc7 END) as desc7, (CASE WHEN d.desc8='' THEN 'nursing_acuity_custom2' ELSE d.desc8 END) as desc8, (CASE WHEN d.desc9='' THEN 'nursing_acuity_custom3' ELSE d.desc9 END) as desc9, (CASE WHEN d.skilldesc1='' THEN 'support_hours' ELSE d.skilldesc1 END) as skilldesc1, (CASE WHEN d.skilldesc2='' THEN 'support_rn1_hours' ELSE d.skilldesc2 END) as skilldesc2, (CASE WHEN d.skilldesc3='' THEN 'support_rn2_hours' ELSE d.skilldesc3 END) as skilldesc3, (CASE WHEN d.skilldesc4='' THEN 'support_tech1_hours' ELSE d.skilldesc4 END) as skilldesc4, (CASE WHEN d.skilldesc5='' THEN 'support_tech2_hours' ELSE d.skilldesc5 END) as skilldesc5, (CASE WHEN d.skilldesc6='' THEN 'support_admin_hours' ELSE d.skilldesc6 END) as skilldesc6, (CASE WHEN d.skilldesc7='' THEN 'support_custom1_hours' ELSE d.skilldesc7 END) as skilldesc7, (CASE WHEN d.skilldesc8='' THEN 'support_custom2_hours' ELSE d.skilldesc8 END) as skilldesc8, (CASE WHEN d.skilldesc9='' THEN 'support_custom3_hours' ELSE d.skilldesc9 END) as skilldesc9, (CASE WHEN d.skilldesc10='' THEN 'support_other_hours' ELSE d.skilldesc10 END) as skilldesc10, (CASE WHEN d.skilldesc11='' THEN 'support_provider_hours' ELSE d.skilldesc11 END) as skilldesc11, (CASE WHEN d.customChurnLabel='' THEN 'churn_custom' ELSE d.customChurnLabel END) as customChurnLabel, (CASE WHEN d.customDesc='' THEN 'visits_custom' ELSE d.customDesc END) as customVisits, (CASE WHEN d.customDesc2='' THEN 'visits_custom2' ELSE d.customDesc2 END) as customVisits2, (CASE WHEN d.customDesc3='' THEN 'visits_custom3' ELSE d.customDesc3 END) as customVisits3 FROM `ProductiveDept` d WHERE `id`=(SELECT `deptId` FROM `productiveDeptXref` WHERE `userId`={$userId})");
		$keys = array('account_ID','unit_ID','unit_Name','prod_Measure','UOS_Value','date_shift','date_submitted','submitted_by','nursing_charge_hours','nursing_rn_hours','' . $deptKeys[0]['nurse1Desc'] . '','nursing_tech_hours','nursing_admin_hours','nursing_sitter_hours','' . $deptKeys[0]['other1Desc'] . '','' . $deptKeys[0]['other2Desc'] . '','' . $deptKeys[0]['other3Desc'] . '','nursing_acuity_1to1','nursing_acuity_1to2','nursing_acuity_1to3','nursing_acuity_1to4','nursing_acuity_1to5','nursing_acuity_1to6','nursing_acuity_custom1','nursing_acuity_custom2','nursing_acuity_custom3','nursing_total_pts','support_UOS','' . $deptKeys[0]['customVisits'] . '','' . $deptKeys[0]['customVisits2'] . '','' . $deptKeys[0]['customVisits3'] . '','support_hours','' . $deptKeys[0]['skilldesc2'] . '','support_rn2_hours','support_tech1_hours','support_tech2_hours','support_admin_hours','support_custom1_hours','support_custom2_hours','support_custom3_hours','support_other_hours','support_provider_hours','hrs_variance_submitted','WHPUOS_submitted','prod_submitted','hrs_variance_imported','WHPUOS_imported','totalHrs_imported','indirect_hrs','escalation_Type','escalation_Note','churn_admits','churn_discharges','churn_transfers','churn_custom','submitted_visits','submitted_total_hrs','submitted_hrs1','submitted_hrs2','submitted_hrs3','report_notes','shift_count','SD','EE','deptID','Provider','dateNew','dateExst','provName','escResponse','escResponder','referrals','totalVisits','customUOS4','customUOS5','nursing_custom10','nursing_custom11');
		fputcsv($output,$keys);
		foreach($dbData2 as $dbDept){
			$shiftCount = intval($dbDept['shift_count']);
			$deptId = $dbDept['deptId'];
			$dbData = Config::get('db')->get_results("SELECT n.*, a.unitId as unit_ID, a.dept as unit_Name, m.name as prod_Measure, a.hppd as UOS_Value, CONCAT(DATE_FORMAT(n.dayDate,'%m/%d/%y'), '  ', s.shift) as date_shift, DATE_FORMAT(n.entered,'%m/%d/%Y %T') as date_submitted, CONCAT(SUBSTRING(i.first_name,1,1), '. ', i.last_name) as submitted_by, (n.chargecount * 24 / {$shiftCount}) as nursing_charge_hours, ((n.antecount+n.ldcount+n.ocount) * 24 / {$shiftCount}) as nursing_rn_hours, ((n.customNurse) * 24 / {$shiftCount}) as nursing_custom_rn_hours, ((n.techcount) * 24 / {$shiftCount}) as nursing_tech_hours, ((n.seccount) * 24 / {$shiftCount}) as nursing_admin_hours, ((n.sittercount) * 24 / {$shiftCount}) as nursing_sitter_hours, ((n.otherNurse1) * 24 / {$shiftCount}) as nursing_custom1_hours, ((n.otherNurse2) * 24 / {$shiftCount}) as nursing_custom2_hours, ((n.otherNurse3) * 24 / {$shiftCount}) as nursing_custom3_hours, n.oneto1 as nursing_acuity_1to1, n.oneto2 as nursing_acuity_1to2, n.oneto3 as nursing_acuity_1to3, n.oneto4 as nursing_acuity_1to4, n.oneto5 as nursing_acuity_1to5, n.oneto6 as nursing_acuity_1to6, n.oneto7 as nursing_acuity_custom1, n.oneto8 as nursing_acuity_custom2, n.oneto9 as nursing_acuity_custom3, (n.oneto1+n.oneto2+n.oneto3+n.oneto4+n.oneto5+n.oneto6) as nursing_total_pts, n.procedureCount as support_UOS, n.whpCustom as custom1visits, n.whpCustom2 as custom2visits, n.whpCustom3 as custom3visits, n.whpCustom4 as custom4visits, n.whpCustom5 as custom5visits, n.skill1val as support_hours, n.skill2val as support_rn1_hours, n.skill3val as support_rn2_hours, n.skill4val as support_tech1_hours, n.skill5val as support_tech2_hours, n.skill6val as support_admin_hours, n.skill7val as support_custom1_hours, n.skill8val as support_custom2_hours, n.skill9val as support_custom3_hours, n.skill10val as support_other_hours, n.skill11val as support_provider_hours, n.hrsVariance as hrs_variance_submitted, n.actualWHP as WHPUOS_submitted, n.nproductivity as prod_submitted, n.hrsVarianceActual as hrs_variance_imported, n.actualWHPimport as WHPUOS_imported, n.totalHours as totalHrs_imported, n.addResourceHrs as indirect_hrs, IFNULL(l.escalation,'') as escalation_Type, IFNULL(c.note,'') as escalation_Note, n.admits as churn_admits, n.discharges as churn_discharges, n.transfers as churn_transfers, n.customChurn as churn_custom, n.visitsSubmitted, n.hoursSubmitted, n.hrs1Submitted, n.hrs2Submitted, n.hrs3Submitted, n.note as report_notes,(select count(`dayDate`) from `productiveNewData` where `deptId`=n.deptId and `whpPlan`=0 and `dayDate`=n.dayDate) as shift_count, (CASE WHEN r.dayRank >0 THEN r.dayRank ELSE '' END) as SD, (CASE WHEN r.empEngagement>0 THEN r.empEngagement ELSE '' END) as EE, n.deptId as deptID, (CASE WHEN n.type=2 THEN 'Yes' ELSE 'No' END) as provider, (CASE WHEN r.dateNew='0000-00-00' THEN '' ELSE r.dateNew END) as dateNew, (CASE WHEN r.dateExisting='0000-00-00' THEN '' ELSE r.dateExisting END) as dateExst, IFNULL((select CONCAT(SUBSTRING(`first_name`,1,1), '. ', `last_name`) from `productiveUser` where `id`=n.provId),'') as provName, c.response as escResponse, (select `last_name` from `productiveUser` where `id` = c.lastupdateId) as escResponder, (CASE WHEN r.userId=0 AND r.referrals=0 THEN '' ELSE r.referrals END) as referrals, a.customWHPvalue, a.customWHP2value, a.customWHP3value, a.customWHP4value, a.customWHP5value, (CASE WHEN a.prodMeasure1=2 THEN (n.procedureCount + (n.whpCustom * a.customWHPvalue) + (n.whpCustom2 * a.customWHP2value) + (n.whpCustom3 * a.customWHP3value) + (n.whpCustom4 * a.customWHP4value) + (n.whpCustom5 * a.customWHP5value)) ELSE '' END) as totalVisits FROM `productiveNewData` n LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as a on a.id = n.deptId LEFT JOIN `productiveMeasures` as m on m.id=a.prodMeasure1 LEFT OUTER JOIN `productiveDayRank` as r on r.dayDate=n.dayDate AND r.deptId=n.deptId LEFT JOIN `productiveAccount` as t on t.id = a.accountId LEFT JOIN `productiveUser` as u on u.id ={$userId} LEFT OUTER JOIN `productiveUser` as i on i.id=n.userId LEFT OUTER JOIN `productiveEscalations` as c on c.dataId = n.id LEFT OUTER JOIN `productiveAcctEscalations` as l on l.id = c.escalation WHERE n.deptId={$deptId} AND n.userId>0 AND n.activeRecord>0 AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate $searchAdd2$searchAdd3 group by n.deptId, n.dayDate, n.id ORDER BY n.dayDate DESC, a.dept ASC, n.shift DESC");
			 
			if($dbData) {
				foreach($dbData as $k=>$v){
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
					'oneto10' => $v['oneto10'],
					'oneto11' => $v['oneto11']

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

function updateUser2() {
    $userId = (isset($_REQUEST['userId'])?$_REQUEST['userId']:0);
	$alertTimes = (isset($_REQUEST['times'])?$_REQUEST['times']:'');
	$mobile = (isset($_REQUEST['mobile'])?$_REQUEST['mobile']:'');
	$vtext = (isset($_REQUEST['report'])) ? $_REQUEST['report'] : 0;
	$atext = (isset($_REQUEST['escalation'])) ? $_REQUEST['escalation'] : 0;
	$mtext = (isset($_REQUEST['missed'])) ? $_REQUEST['missed'] : 0;
	$stop = (isset($_REQUEST['pause'])) ? $_REQUEST['pause'] : 0;
	$emailAlerts = (isset($_REQUEST['emailAlerts'])) ? $_REQUEST['emailAlerts'] : 0;
	$emailMissed = (isset($_REQUEST['emailMissed'])) ? $_REQUEST['emailMissed'] : 0;
	
	$dbUser = Config::get('db')->get_results("SELECT * FROM `productiveUser` WHERE `id`={$userId}");
	$role = intval($dbUser[0]['role']);
	
	if($role==6 && intval($stop)!=1) {
		
		$xdata = array(
            'textAlerts' =>1
        );
        $xwhere = array('userId'=>$userId,
						'primaryUnit'=>$role);
						
        Config::get('db')->update('productiveDeptXref',$xdata,$xwhere);
		
	}else{
		//do nothing;
	}
	
    if (intval($stop)==1) {
		$data = array(
            'txt' => $vtext,
			'reportMissed' => $mtext,
			'txtEscalation' => $atext,
			'txtPause' => $stop,
			'mobile' => $mobile,
			'alertTimes' => $alertTimes,
			'emailAlerts' => $emailAlerts,
			'emailMissed' => $emailMissed,
			'emailEscalations' => $emailAlerts
        );
        $where = array('id'=>$userId);
        Config::get('db')->update('productiveUser',$data,$where);
        echo json_encode(array('success'=>true,'message'=>'User Updated'));
        return true;
    }else{
		$data = array(
            'txt' => $vtext,
			'reportMissed' => $mtext,
			'txtEscalation' => $atext,
			'txtPause' => $stop,
			'mobile' => $mobile,
			'alertTimes' => $alertTimes,
			'emailAlerts' => $emailAlerts,
			'emailMissed' => $emailMissed,
			'emailEscalations' => $emailAlerts
        );
        $where = array('id'=>$userId);
        Config::get('db')->update('productiveUser',$data,$where);
        echo json_encode(array('success'=>true,'message'=>'User Updated'));
        return true;		
	}
    echo json_encode(array('success'=>false,'message'=>'User Failed to Update'));
    return false;
}












/*
 
 CLASSES
  ______  ___  _____  _____
  | ___ \/ _ \|  __ \|  ___|
  | |_/ / /_\ \ |  \/| |__
  |  __/|  _  | | __ |  __|
  | |   | | | | |_\ \| |___
  \_|   \_| |_/\____/\____/


*/

/////////////////////////////////////
// CREATE CLASSES TABLE

/////////////////////////////////////
// GET ALL USERS


function GetAllClasses() {
    $accountId = Config::get('account')['accountId'];
	$userId = Config::get('account')['id'];
	$role = Config::get('account')['role'];
	$startDate = isset($_REQUEST['start']) ? $_REQUEST['start'] : date('yyyy-mm-dd');
    $endDate = isset($_REQUEST['end']) ? $_REQUEST['end'] : date('yyyy-mm-dd');
	$searchAdd = '';
	$practiceId = 1;

	if(intval($role)>3){
	$searchAdd =" AND p.doctorId in (SELECT `doctorId` from `productivePredictorXref` where `accountId`={$accountId}) ";
	$groupBy = " GROUP BY p.accountId ";
	}else{
	$searchAdd =" AND p.doctorId in (SELECT `doctorId` from `productivePredictorDrs` where `practiceId`={$practiceId}) ";	
	$groupBy = " GROUP BY p.doctorId ";
	}
	
    $query = "SELECT SQL_CALC_FOUND_ROWS 
	p.*, a.id as practiceId, a.practiceName, count(p.id) as deliveries, DATE_FORMAT(p.edc,'%m/%d/%y') as dateEntered, 
	SUM(CASE WHEN p.comp >0 THEN 1 ELSE 0 END) `risk`, 
	SUM(CASE WHEN p.grav =1 THEN 1 ELSE 0 END) `primips` 
	FROM `productivePredictor` p 
	LEFT JOIN `productivePredictorDrs` as d on d.id = p.doctorId 
	LEFT JOIN `productivePredictorAcct` as a on a.id = d.practiceId 
	WHERE ((p.rangeLow <'{$startDate}' AND p.rangeHigh >'{$endDate}') 
	OR (p.rangeLow >='{$startDate}' AND p.rangeLow <='{$endDate}') 
	OR (p.rangeHigh >='{$startDate}' AND p.rangeHigh <='{$endDate}'))$searchAdd$groupBy
	ORDER BY p.entered DESC
		";

    $dbData = Config::get('db')->get_results($query);
    $query = "SELECT FOUND_ROWS() AS found_rows;";
    $countData = Config::get('db')->get_results($query);
    $total = $countData[0]['found_rows'];
    $jobArray = array();
    	
	if ($dbData){
		
    foreach ($dbData as $job) {
        //$className = "Name";
		$doctorName = '<a href="javascript:;" onclick="tj.getpractice('.$job['accountId'].')" > ALL </a>';
		$doctorName2 = '<a href="javascript:;" onclick="tj.getclass('.$job['doctorId'].')" >' . $job['doctorName'] . '</a>';
		$DateTime = $job['entered'];
		$deliveries = $job['deliveries'];
		$risk = $job['risk'];
		
		$jobArray[] = array(
            'practice'=>$doctorName,
            'update'=>$DateTime,
			'patients'=>$deliveries,
			'risk'=>$risk,
			'primips'=>$job['primips'] 	
        );
    }
    $outData = array();
    $outData['data'] = $jobArray;
    echo json_encode($outData,JSON_NUMERIC_CHECK);
	return true;
	}else{
    $outData = array();
        $outData['data'] = array();
        echo json_encode($outData,JSON_NUMERIC_CHECK);
		return false;
	}

}

function GetClassDetails() {
	$classId = isset($_REQUEST['classId']) ? $_REQUEST['classId'] : '';
	$accountId = Config::get('account')['accountId'];
	$role = Config::get('account')['role'];
	
	$query = "SELECT SQL_CALC_FOUND_ROWS
		p.*, DATE_FORMAT(p.edc,'%m/%d/%y') as edc, IFNULL(l.desc,'') as plan, (CASE WHEN p.age=0 THEN '' ELSE p.age end) as newAge, a.id as classId, a.name, IFNULL(a.classDate,'') as classDate, IFNULL(a.classTime,'') as classTime, IFNULL(c.desc,'') as complication
        FROM `productivePredictor` p
		LEFT JOIN `productivePredictorClasses` as a on a.id = p.classId 
		LEFT JOIN `productiveComplications` as c on c.id = p.comp
		LEFT JOIN `productivePredictorPlan` as l on l.id = p.deliveryPlan
        WHERE a.id={$classId} 
		GROUP BY p.id ORDER BY p.id DESC
		";

    $dbData = Config::get('db')->get_results($query);
    $query = "SELECT FOUND_ROWS() AS found_rows;";
    $countData = Config::get('db')->get_results($query);
    $total = $countData[0]['found_rows'];
	//$action = "<button type=\"button\" class=\"btn btn-danger\" onclick=\"tj.deleteResource(" . $dbData['rid'] . ")\">Remove</button>";
	
    $jobArray = array();
    	
	if ($dbData){
		
    foreach ($dbData as $job) {
		$button = "<button type=\"button\" name=\"remove\" class=\"btn btn-danger btn-sm remove\" onclick=\"tj.deleteTable(" . $job['id'] . ")\">-</button>";
	//$edc = date("m:d:Y",$job['edc']);
       $jobArray[] = array(
            'edc'=>$job['edc'],
            'age'=>$job['newAge'],
			'grav'=>$job['grav'],
			'par'=>$job['par'],
			'plan'=>$job['plan'],
			'comp'=>$job['complication'],
			'remove'=>$button,
			'recordId'=>$job['id']
        );
    }
    $outData = array();
    $outData['data'] = $jobArray;
    echo json_encode($outData,JSON_NUMERIC_CHECK);
	return true;
	}else{
    $outData = array();
        $outData['data'] = array();
        echo json_encode($outData,JSON_NUMERIC_CHECK);
	}

}

function GetClass() {
	$classId = isset($_REQUEST['classId']) ? $_REQUEST['classId'] : '';
	$accountId = Config::get('account')['accountId'];
	$role = Config::get('account')['role'];
	
	//$query = "SELECT SQL_CALC_FOUND_ROWS * FROM `productivePredictorClasses` WHERE `id`={$classId}";

    $dbData = Config::get('db')->get_results("SELECT c.*, u.first_name, u.last_name, DATE_FORMAT(c.dateEntered,'%m/%d/%y') as newEntered, DATE_FORMAT(c.classDate,'%m/%d/%y') as newDate, DATE_FORMAT(c.classTime,'%H:%i') as newTime FROM `productivePredictorClasses` c left outer join `productivePredictor` as p on p.classId = c.id left outer join `productiveUser` as u on u.id = p.userId WHERE c.id={$classId}");
    //$query = "SELECT FOUND_ROWS() AS found_rows;";
    //$countData = Config::get('db')->get_results($query);
    //$total = $countData[0]['found_rows'];
	//$action = "<button type=\"button\" class=\"btn btn-danger\" onclick=\"tj.deleteResource(" . $dbData['rid'] . ")\">Remove</button>";
	//$button = "<button type=\"button\" name=\"remove\" class=\"btn btn-danger btn-sm remove\">Delete</button>";
    $jobArray = array();
    $lastEdit = $dbData[0]['last_name'] . ", " . $dbData[0]['first_name'];
	if ($dbData){
       $jobArray = array(
            'reportName'=>$dbData[0]['name'],
			'reportDate'=>$dbData[0]['newDate'],
			'reportTime'=>$dbData[0]['newTime'],
			'lastEdit'=>$lastEdit,
			'reportId'=>$classId,
			'dateEntered'=>$dbData[0]['newEntered'],
        );
    
    echo json_encode(array('success'=>true,'data'=>$jobArray));
	return true;
	}else{
    $jobArray = array(
            'reportName'=>'',
			'reportDate'=>'',
			'reportTime'=>'',
			'lastEdit'=>'',
			'reportId'=>'',
			'dateEntered'=>''
        );
        echo json_encode(array('success'=>true,'data'=>$outData));
	}

}

function SaveClass() {
	$records = isset($_REQUEST['form_data']) ? $_REQUEST['form_data'] : '';
	$classId = isset($_REQUEST['classId']) ? $_REQUEST['classId'] : '';
	$accountId = Config::get('account')['accountId'];
	$role = Config::get('account')['role'];
	
	if($records){		
		foreach($records as $record){
			if(intval($record['id'])>0){
				//do nothing;
			}else{
				$data = array(
				'classId' => $classId,
				'grav' => $record['grav'],
				'par' => $record['par'],
				'grav' => $record['grav'],
				'comp' => $record['comp'],
				'deliveryPlan' => $record['plan'],
				'edc' => date('yyyy-mm-dd',$record['edc'])				
				);
			Config::get('db')->insert('productivePredictor',$data);
			}
		}
		echo json_encode(array('success'=>true,'message'=>'Update Successful'));
		return true;
	}
       echo json_encode(array('success'=>false,'message'=>'No Update'));
	   return false;
}

function SaveTable() {
	$edc = isset($_REQUEST['edc']) ? $_REQUEST['edc'] : '';
	$grav = isset($_REQUEST['grav']) ? $_REQUEST['grav'] : '';
	$par = isset($_REQUEST['par']) ? $_REQUEST['par'] : '';
	$plan = isset($_REQUEST['plan']) ? $_REQUEST['plan'] : '';
	$comp = isset($_REQUEST['comp']) ? $_REQUEST['comp'] : '';
	$age = isset($_REQUEST['age']) ? $_REQUEST['age'] : '';
	$classId = isset($_REQUEST['classId']) ? $_REQUEST['classId'] : '';
	$accountId = Config::get('account')['accountId'];
	$role = Config::get('account')['role'];
	if($edc){
		for($count = 0; $count<count($edc); $count++){
			if($edc[$count]){
				$newEdc = strtotime($edc[$count]);
				//$newEdc = date_create($edc[$count]);
				$newId = $age[$count] . str_replace("-","",$edc[$count]) . $grav[$count] . $par[$count];
				$newComp = intval($comp[$count]);
				if($newComp>0){
				$compDb = Config::get('db')->get_results("SELECT * FROM `productiveComplications` WHERE `id` ={$newComp}");
				$rangeLow = intval($compDb[0]['weeksValue']);
				$rangeHigh = round((intval($compDb[0]['weeksValue']) *.5),0);
				//$lowRange = strtotime("-". $rangeLow . " days", $newEdc);
				//$highRange = strtotime("-". $rangeHigh . " days", $newEdc);
				$low = "-" . $rangeLow . " days";
				$high = "-". $rangeHigh . " days";
				//$lowRange = date_sub($newEdc, date_interval_create_from_date_string(" . $low . "));
				//$highRange = date_sub($newEdc, date_interval_create_from_date_string(" . $high . "));
				$lowRange = date('Y-m-d', strtotime($edc[$count].$low));
				$highRange = date('Y-m-d', strtotime($edc[$count].$high));
				$rangeDays = $rangeLow - $rangeHigh;
				}else{
				$highRange = $edc[$count];
				$lowRange = date('Y-m-d', strtotime($edc[$count]. '- 10 days'));
				//$lowRange = date_sub($newEdc, date_interval_create_from_date_string("10 days"));
				$rangeDays = 10;
				}	
				
				$data = array(
				'grav' => $grav[$count],
				'newId' => $newId,
				'age' => $age[$count],
				'par' => $par[$count],
				'deliveryPlan' => $plan[$count],
				'comp' => $comp[$count],
				'edc' => $edc[$count],
				'classId' => $classId,
				'rangeLow' => $lowRange,
				'rangeHigh' => $highRange,
				'rangeDays' => $rangeDays
				);
			Config::get('db')->insert('productivePredictor',$data);
			}
		}
		echo json_encode(array('success'=>true,'message'=>'Update Successful'));
		return true;
	}
       echo json_encode(array('success'=>false,'message'=>'No Update'));
	   return false;
}

function SaveDelivery() {
	$edc = isset($_REQUEST['edc']) ? $_REQUEST['edc'] : '';
	$grav = isset($_REQUEST['grav']) ? $_REQUEST['grav'] : '';
	$par = isset($_REQUEST['par']) ? $_REQUEST['par'] : '';
	$plan = isset($_REQUEST['plan']) ? $_REQUEST['plan'] : '';
	$comp = isset($_REQUEST['comp']) ? $_REQUEST['comp'] : '';
	$age = isset($_REQUEST['age']) ? $_REQUEST['age'] : '';
	$classId = isset($_REQUEST['classId']) ? $_REQUEST['classId'] : '';
	$accountId = Config::get('account')['accountId'];
	$role = Config::get('account')['role'];
	if($edc){
				$newEdc = strtotime($edc);
				//$newEdc = date_create($edc[$count]);
				$newId = $age . str_replace("-","",$edc) . $grav . $par;
				$newComp = intval($comp);
				if($newComp>0){
				$compDb = Config::get('db')->get_results("SELECT * FROM `productiveComplications` WHERE `id` ={$newComp}");
				$rangeLow = intval($compDb[0]['weeksValue']);
				$rangeHigh = round((intval($compDb[0]['weeksValue']) *.5),0);
				//$lowRange = strtotime("-". $rangeLow . " days", $newEdc);
				//$highRange = strtotime("-". $rangeHigh . " days", $newEdc);
				$low = "-" . $rangeLow . " days";
				$high = "-". $rangeHigh . " days";
				//$lowRange = date_sub($newEdc, date_interval_create_from_date_string(" . $low . "));
				//$highRange = date_sub($newEdc, date_interval_create_from_date_string(" . $high . "));
				$lowRange = date('Y-m-d', strtotime($edc.$low));
				$highRange = date('Y-m-d', strtotime($edc.$high));
				$rangeDays = $rangeLow - $rangeHigh;
				}else{
				$highRange = $edc;
				$lowRange = date('Y-m-d', strtotime($edc. '- 10 days'));
				//$lowRange = date_sub($newEdc, date_interval_create_from_date_string("10 days"));
				$rangeDays = 10;
				}	
				
				$data = array(
				'grav' => $grav,
				'newId' => $newId,
				'age' => $age,
				'par' => $par,
				'deliveryPlan' => $plan,
				'comp' => $comp,
				'edc' => $edc,
				'classId' => $classId,
				'rangeLow' => $lowRange,
				'rangeHigh' => $highRange,
				'rangeDays' => $rangeDays
				);
			Config::get('db')->insert('productivePredictor',$data);
			
		
		echo json_encode(array('success'=>true,'message'=>'Update Successful'));
		return true;
	}
       echo json_encode(array('success'=>false,'message'=>'No Update'));
	   return false;
}

function DeleteTable() {
	$recordId = isset($_REQUEST['recordId']) ? $_REQUEST['recordId'] : '';
	$accountId = Config::get('account')['accountId'];
	$role = Config::get('account')['role'];
	if($recordId){
				$where = array(
				'id' => $recordId,
				);
			Config::get('db')->delete('productivePredictor',$where);
		echo json_encode(array('success'=>true,'message'=>'Update Successful'));
		return true;
	}
       echo json_encode(array('success'=>false,'message'=>'No Update'));
	   return false;
}


function AddClass() {
    $className = isset($_REQUEST['name']) ? $_REQUEST['name'] : '';
	$date = isset($_REQUEST['date']) ? $_REQUEST['date'] : '';
	$time = isset($_REQUEST['time']) ? $_REQUEST['time'] : '';
	//$total = isset($_REQUEST['total']) ? $_REQUEST['total'] : 0;
	$accountId = Config::get('account')['accountId'];
	$userId = Config::get('account')['id'];
	
	if($className){
		
	$classdata = array(
			'name' => stripslashes($className),
			'accountId' => $accountId,
			'classDate' => $date,
			'classTime' => $time,
			'userId' => $userId
        );
	Config::get('db')->insert('productivePredictorClasses',$classdata);
	
	$classDb = Config::get('db')->get_results("SELECT * FROM `productivePredictorClasses` WHERE `userId` ={$userId} order by `id` DESC");
	$classId = $classDb[0]['id'];
		
		$data = array(
			'classId' => $classId,
			'reportName' => stripslashes($className),
			'reportDate' => $date,
			'reportTime' => $time
        );
	echo json_encode(array('success'=>true,'data'=>$data));
      return true;
	}else{
		echo json_encode(array('success'=>false));
      return false;
	}
}




/*
 
 USERS
  ______  ___  _____  _____
  | ___ \/ _ \|  __ \|  ___|
  | |_/ / /_\ \ |  \/| |__
  |  __/|  _  | | __ |  __|
  | |   | | | | |_\ \| |___
  \_|   \_| |_/\____/\____/


*/

/////////////////////////////////////
// GET ALL USERS
function GetAllUsers() {
    $accountId = Config::get('account')['accountId'];
	$userId = intval(Config::get('account')['id']);
	$role = Config::get('account')['role'];
	$enterpriseId = Config::get('account')['enterpriseId'];
	$label = Config::get('account')['label'];
	
	if(intval($role)<=9){
		$searchAdd = "WHERE u.accountId={$accountId} AND u.active>0 AND u.last_name !='' and u.role <={$role} and x.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId} and `primaryUnit`>0 and `primaryUnit`<8)";
	}else if(intval($role)>9 && intval($role)<11){
		$searchAdd = "WHERE u.accountId={$accountId} AND u.role<=10 ";
	}else if(intval($role)>10 && intval($role)<=12){
		$searchAdd = "WHERE u.accountId={$accountId} ";
	}else if(intval($role)>12 && intval($role)<90){
		$searchAdd = "WHERE u.enterpriseId={$enterpriseId} AND u.last_name !='' ";
	}else{
		$searchAdd = '';
	}
     if(intval($role)<90){
        $query = "
		 SELECT SQL_CALC_FOUND_ROWS
		u.*, a.name as accountName, IFNULL(x.deptId,0) as depNum, x.textAlerts, x.userId, count(x.deptId) as deptCount, d.dept as depName, r.role as userRole FROM `productiveUser` u
        LEFT OUTER JOIN `productiveDeptXref` as x on x.userId=u.id
        LEFT JOIN `productiveUserRoles` as r on r.id=u.role
        LEFT JOIN `ProductiveDept` as d on d.id=x.deptId
		LEFT JOIN `productiveAccount` as a on a.id=u.accountId
        $searchAdd
        GROUP BY u.id ORDER BY a.name,u.last_name,u.first_name ASC
		";
	 }else{
		$query = "
		 SELECT SQL_CALC_FOUND_ROWS
		u.*, a.name as accountName, IFNULL(x.deptId,0) as depNum, x.textAlerts, x.userId, count(x.deptId) as deptCount, CONCAT(a.name, ' - ', d.dept) as depName, r.role as userRole FROM `productiveUser` u
        LEFT OUTER JOIN `productiveDeptXref` as x on x.userId=u.id
        LEFT JOIN `productiveUserRoles` as r on r.id=u.role
        LEFT JOIN `ProductiveDept` as d on d.id=x.deptId
		LEFT JOIN `productiveAccount` as a on a.id=u.accountId
        $searchAdd
        GROUP BY u.id ORDER BY a.name,u.last_name,u.first_name ASC
		"; 
		 
	 }
    $dbData = Config::get('db')->get_results($query);
    $query = "SELECT FOUND_ROWS() AS found_rows;";
    $countData = Config::get('db')->get_results($query);
    $total = $countData[0]['found_rows'];
    $jobArray = array();
    $link = 0;

    //$brandcandidates = Config::get('db')->get_results("SELECT c.*, x.keyword as keyword FROM `candidate` as c LEFT JOIN candidateXref as x on x.candidateId = c.id WHERE x.brandId={$brand} and c.active=1 and (x.promo=1 or x.promo=2)");

    if($dbData){
	foreach ($dbData as $job) {
        $name = $job['last_name'].", ".$job['first_name'];
		$role = $job['userRole'];
		$dpCount = intval($job['deptCount']);
		$loggedIn = intval($job['id']);
		
		$text = intval($job['txtactive']);
		$textPaused = intval($job['txtPause']);
		
		if($dpCount>1){
			$depName = "Multiple";
		}else{
			$depName = $job['depName'];
		}
		
		if($userId==$loggedIn){
			$textInactive = '<a href="javascript:;" onclick="tj.activate('.$job['id'].')">Inactive (User must activate texting)</a>';
			$textActive = '<a href="javascript:;" onclick="tj.activate('.$job['id'].')">Active (User receiving Text Alerts)</a>';
			$textPaused = '<a href="javascript:;" onclick="tj.activate('.$job['id'].')">Paused (User not receiving texts)</a>';
			$textUnit = '<a href="javascript:;" onclick="tj.activate('.$job['id'].')">Active (Not for this Unit)</a>';
			$textA = '<a href="javascript:;" onclick="tj.activate('.$job['id'].')">Active</a>';		
		}else{
			$textInactive = "Inactive (User must activate texting)";
			$textActive = "Active (User receiving Text Alerts)";
			$textPaused = "Paused (User not receiving texts)";
			$textUnit = "Active (Not for this Unit)";
			$textA = "Active";
		}
		
		if (intval($job['txtPause'])==0 && intval($job['textAlerts'])>=1 && intval($job['txtactive'])>=1 && $dpCount<2){
			$textsub = $textActive;
		}else if (intval($job['txtPause'])>=1 && intval($job['textAlerts'])>=1 && intval($job['txtactive'])>=1 && $dpCount<2){
			$textsub = $textPaused;
		}else if (intval($job['textAlerts'])==0 && intval($job['txtactive'])>=1 && $dpCount<2){
			$textsub = $textUnit;
		}else if (intval($job['txtactive'])>=1 && $dpCount>1){
			$textsub = $textA;
		}else{
			$textsub = $textInactive;
		}
		
		
        //if(intval($role)<8){
		$nameLink = '<a href="javascript:;" onclick="tj.editUser('.$job['id'].' , ' . $job['depNum'] .')">'. $name .'</a>';
		//$locationsLink = $textsub;
		//$locationsLink = '<a href="javascript:;" onclick="tj.showUserLocations('.$job['id'].');return false;" data-toggle="modal" data-target="#usersAssigned">'. $job['stores'] .'</a>';
        //$storeButton = "<button class=\"btn btn-primary btn-sm blue\">Jobs</button>";
				
        $jobArray[] = array(
            'Name'=>$nameLink,
			'accountName'=>$job['accountName'],
			'Unit'=>$depName,
			'Role'=>$role,
			'Login'=>$job['lastlogin'],
			'Locations'=>$textsub
        );
    }
    $outData = array();
        $outData['data'] = $jobArray;
        echo json_encode($outData,JSON_NUMERIC_CHECK);
	}else{
	$outData['data'] = array();
        echo json_encode($outData,JSON_NUMERIC_CHECK);
	}
}

/////////////////////////////////////
// GET DETAILS FOR SINGLE USER
function GetUserDetails() {
    $userId = isset($_REQUEST['userId']) ? $_REQUEST['userId'] : '';
	$deptId = isset($_REQUEST['deptId']) ? $_REQUEST['deptId'] : '';
	$loggedinUser = Config::get('account')['id'];
	$accountId = Config::get('account')['accountId'];
	
	$userRole = array(
			'userRole'=>$loggedinUser
			);
	
    if (intval($deptId)>0 && $deptId) {
        $dbLocation = Config::get('db')->get_results("select u.*, x.textAlerts as Alerts, x.userId, x.deptId, d.dept as depName, r.id as roleId, r.role as roleName from `productiveUser` u LEFT JOIN `productiveDeptXref` as x on x.userId = u.id LEFT JOIN `ProductiveDept` as d on d.id = x.deptId LEFT OUTER JOIN `productiveUserRoles` as r on r.id=u.role where x.userId={$userId} and x.deptId={$deptId}");
        $dbData = $dbLocation[0];
        echo json_encode(array('success' => true,'data'=>$dbData,'user'=>$userRole));
        return true;
    }else{
		$dbLocation = Config::get('db')->get_results("select u.*, u.id as userId, IFNULL(x.deptId,0) as deptId, r.id as roleId, r.role as roleName from `productiveUser` u LEFT OUTER JOIN `productiveDeptXref` as x on x.userId = u.id LEFT OUTER JOIN `productiveUserRoles` as r on r.id=u.role where u.id={$userId}");
        $dbData = $dbLocation[0];
        echo json_encode(array('success' => true,'data'=>$dbData,'user'=>$userRole));
        return true;
	}
    echo json_encode(array('success'=>false,'message'=>'User not found.'));
    return false;
}


/////////////////////////////////////
// GET DETAILS FOR SINGLE USER
function GetNewUnitDetails() {
    //$userRole = (isset($_REQUEST['role'])) ? $_REQUEST['role'] : '';
	$userId = Config::get('account')['id'];
	$accountId = Config::get('account')['accountId'];
	//echo "store:".$storeId;
    $dbUnits = Config::get('db')->get_results("select x.*, u.role, count(x.deptId) as deptCount FROM `productiveDeptXref` x LEFT OUTER JOIN `productiveUser` as u on u.id = x.userId WHERE x.userId={$userId} group by x.userId");
	if($dbUnits){
		$dbCount = $dbUnits[0];
			$dcount = array(
				'count'=>$dbCount['deptCount']
				);
			echo json_encode(array('success' => true,'data'=>$dcount));
			return true;
	}else{
		$dcount = array(
				'count'=>0
				);
    echo json_encode(array('success'=>false,'data'=>$dcount));
    return false;
	}
}

/////////////////////////////////////
// GET TRANSFER DETAILS
function TransferUnitDetails() {
    //$recordId = isset($_REQUEST['recordId']) ? $_REQUEST['recordId'] : '';
	$user = isset($_REQUEST['user']) ? $_REQUEST['user'] : '';
	$deptId= isset($_REQUEST['dep']) ? $_REQUEST['dep'] : '';
	$userId = Config::get('account')['id'];
	$accountId = Config::get('account')['accountId'];
	$role = Config::get('account')['role'];
	//echo "store:".$storeId;
    
	if (intval($role)<=8){
	$dbUnits = Config::get('db')->get_results("select *, count(`deptId`) as deptCount FROM `productiveDeptXref` WHERE `userId`={$userId} group by `userId`");
	}else{
	$dbUnitsU= Config::get('db')->get_results("select *, count(`id`) as deptCount FROM `ProductiveDept` WHERE `accountId`={$accountId} group by `id`");	
	}
	$dbXref = Config::get('db')->get_results("select * FROM `productiveDeptXref` WHERE `userId`={$user} and `deptId`={$deptId}");
	if($dbXref && $dbUnits){
		$dbCount = $dbUnits[0];
		$dbData = $dbXref[0];
			echo json_encode(array('success' => true,'data'=>$dbData,'count'=>$dbCount));
			return true;
	}else{
    echo json_encode(array('success'=>false,'message'=>'User not found.'));
    return false;
	}
}

/////////////////////////////////////
// ADD RESOURCE
function CompleteTransfer() {
    $deptId = (isset($_REQUEST['deptId'])) ? $_REQUEST['deptId'] : '';
	$userId = (isset($_REQUEST['userId'])) ? $_REQUEST['userId'] : '';
	$deptOrig = (isset($_REQUEST['deptIdOrig'])) ? $_REQUEST['deptIdOrig'] : '';
	$grantText = (isset($_REQUEST['grantText'])) ? $_REQUEST['grantText'] : '';
	
	if($deptId && $userId) {		       
						
		$data = array('deptId'=> $deptId,
					'textAlerts'=>$grantText
					);
		$where = array('userId'=>$userId,
					'deptId'=>$deptOrig
					);
        
		Config::get('db')->update('productiveDeptXref',$data,$where);
        echo json_encode(array('success'=>true,'message'=>'Resource Transfered Successfully'));
        return true;
    }	
    echo json_encode(array('success'=>false,'message'=>'Resource not transfered.'));
    return false;
}

/////////////////////////////////////
// ADD RESOURCE
function NewUnit() {
    $deptId = (isset($_REQUEST['deptId'])) ? $_REQUEST['deptId'] : '';
	$userId = (isset($_REQUEST['userId'])) ? $_REQUEST['userId'] : '';
	$grantText = (isset($_REQUEST['grantText'])) ? $_REQUEST['grantText'] : 0;
	$role = (isset($_REQUEST['role'])) ? $_REQUEST['role'] : 0;
	
	if(intval($role)==6 || intval($role)==7){
		$delete = array(
					'deptId' => $deptId,
					'primaryUnit' => $role
					);
		Config::get('db')->delete('productiveDeptXref',$delete);
	}else{
		//do nothing;
	}
	
	if(intval($role)==6){
		$txt = 1;
	}else{
		$txt = $grantText;
	}
	
	if($deptId && $userId){
		$dbText = Config::get('db')->get_results("select * from `productiveDeptXref` where `deptId`={$deptId} and `userId`={$userId}");
		//$dbXref = Config::get('db')->get_results("select * from `productiveDeptXref` where `userId`={$userId} and `primaryUnit` >0 and `deptId` !={$deptId}");
		
	if($dbText) {
		$xId = $dbText[0]['id'];
       
		$data = array('primaryUnit' => $role,
					'textAlerts' => $txt
					);
		$where = array('id' =>$xId);
        
		Config::get('db')->update('productiveDeptXref',$data,$where);
        echo json_encode(array('success'=>true,'message'=>'Unit Added Successfully'));
        return true;
    }else{
		$data = array(
			'userId' => $userId,
			'primaryUnit' =>$role,
			'deptId' => $deptId,
			'textAlerts' => $txt
        );
		Config::get('db')->insert('productiveDeptXref',$data);
        echo json_encode(array('success'=>true,'message'=>'Unit Added Successfully'));
        return true;		
	}	
    
}else{
	echo json_encode(array('success'=>false,'message'=>'Resource not added.'));
    return false;
}
}


/////////////////////////////////////
// GET DETAILS FOR SINGLE USER
function GetUserDetailsActivate() {
	$userId = Config::get('account')['id'];
    
	//echo "store:".$storeId;
    if ($userId) {
        $dbLocation = Config::get('db')->get_results("select * from `productiveUser` where `id`={$userId}");
        $dbData = $dbLocation[0];
        echo json_encode(array('success' => true,'data'=>$dbData));
        return true;
    }
    echo json_encode(array('success'=>false,'message'=>'User not found.'));
    return false;
}

/////////////////////////////////////
// GET CONFIGURE RESOURCES
function GetConfigure() {
    $accountId = Config::get('account')['accountId'];
	//$deptId = Config::get('account')['deptid'];
	$deptId = (isset($_REQUEST['deptId'])) ? $_REQUEST['deptId'] : '';
	$outData = array();
	
	//echo "store:".$storeId;
    if ($deptId) {
        $dbLocation = Config::get('db')->get_results("SELECT d.*, r.id as rid, r.position, r.Sun, r.Mon, r.Tue, r.Wed, r.Thu, r.Fri, r.Sat FROM `ProductiveDept` d left join `productiveResources` as r on r.deptId = d.id WHERE d.id={$deptId}");
    if ($dbLocation){
	foreach ($dbLocation as $dbData){
		$position = "<a href=\"javascript:;\" onclick=\"tj.editResource(".$dbData['rid'].");return false;\">". $dbData['position'] ."</a>";
		$action = "<button type=\"button\" class=\"btn btn-danger\" onclick=\"tj.deleteResource(" . $dbData['rid'] . ")\">Remove</button>";	
				
        $jobArray[] = array(
			'position'=>$position,
            'sunday'=>$dbData['Sun'],
            'monday'=>$dbData['Mon'],
            'tuesday'=>$dbData['Tue'],
			'wednesday'=>$dbData['Wed'],
            'thursday'=>$dbData['Thu'],
            'friday'=>$dbData['Fri'],
            'saturday'=>$dbData['Sat'],
			'action'=>$action
        );
    }
     $outData['data'] = $jobArray;
    echo json_encode($outData,JSON_NUMERIC_CHECK);  
	}else{
     	$jobArray[] = array(
				'position' => '',
				'sunday' => '',
				'monday' => '',
				'tuesday' => '',
				'wednesday' => '',
				'thursday' => '',
				'friday' => '',
				'saturday' => '',
				'action' => ''
            );
        $outData['data'] = $jobArray;
        echo json_encode($outData,JSON_NUMERIC_CHECK);
	}
  
}
}

/////////////////////////////////////
// GET CONFIGURE RESOURCES

function GetConfigDetails() {
        $accountId = Config::get('account')['accountId'];
        //$deptId = Config::get('account')['deptid'];
		$deptId = (isset($_REQUEST['unitId'])) ? $_REQUEST['unitId'] : '';
	
		//if (intval($dept)!=0){
		//	$deptId = $dept;
		//}else{
		//	$deptId = Config::get('account')['deptid'];
		//}
   
   if ($deptId) {
        $dbProd = Config::get('db')->get_results("select * from `ProductiveDept` where id={$deptId} group by id");
        
        $dbData = $dbProd[0];
        echo json_encode(array('success' => true,'data'=>$dbData));
        return true;
    }else{
    echo json_encode(array('success'=>false,'message'=>'Config not found.'));
    return false;
	}
}

/////////////////////////////////////
// UPDATE CONFIG
function UpdateConfig() {
	$deptName = (isset($_REQUEST['deptName'])) ? $_REQUEST['deptName'] : '';
	$unitId = (isset($_REQUEST['unitId'])) ? $_REQUEST['unitId'] : '';
	$totalbeds = (isset($_REQUEST['totalbeds'])) ? $_REQUEST['totalbeds'] : 0;
    $deptId = (isset($_REQUEST['deptId'])) ? $_REQUEST['deptId'] : 0;
	$accountId = (isset($_REQUEST['accountId'])) ? $_REQUEST['accountId'] : 0;
	$target = (isset($_REQUEST['target'])) ? $_REQUEST['target'] : 0;
	$prodMeasure = (isset($_REQUEST['prodMeasure'])) ? $_REQUEST['prodMeasure'] : 0;
	$prodValue = (isset($_REQUEST['prodValue'])) ? $_REQUEST['prodValue'] : 0;
	$censusShift = (isset($_REQUEST['censusShift'])) ? $_REQUEST['censusShift'] : 0;
	$one2one = (isset($_REQUEST['one2one'])) ? $_REQUEST['one2one'] : 0;
	$desc1 = (isset($_REQUEST['desc1'])) ? $_REQUEST['desc1'] : '';
	$one2two = (isset($_REQUEST['one2two'])) ? $_REQUEST['one2two'] : 0;
	$desc2 = (isset($_REQUEST['desc2'])) ? $_REQUEST['desc2'] : '';
	$one2three = (isset($_REQUEST['one2three'])) ? $_REQUEST['one2three'] : 0;
	$desc3 = (isset($_REQUEST['desc3'])) ? $_REQUEST['desc3'] : '';
	$one2four = (isset($_REQUEST['one2four'])) ? $_REQUEST['one2four'] : 0;
	$desc4 = (isset($_REQUEST['desc4'])) ? $_REQUEST['desc4'] : '';
	$one2five = (isset($_REQUEST['one2five'])) ? $_REQUEST['one2five'] : 0;
	$desc5 = (isset($_REQUEST['desc5'])) ? $_REQUEST['desc5'] : '';
	$one2six = (isset($_REQUEST['one2six'])) ? $_REQUEST['one2six'] : 0;
	$desc6 = (isset($_REQUEST['desc6'])) ? $_REQUEST['desc6'] : '';

	
    if (intval($deptId) > 0) {
       $data = array(
            'dept' => $deptName,
			'unitId' => $unitId,
            'totalbeds' => $totalbeds,
			'target' => $target,
			'prodMeasure' => $prodMeasure,
			'prodMeasure1' => $prodMeasure,
			'hppd' => $prodValue,
			'shift' => $censusShift,
			'oneto1' => $one2one,
			'desc1' => $desc1,
			'oneto2' => $one2two,
			'desc2' => $desc2,
			'oneto3' => $one2three,
			'desc3' => $desc3,
			'oneto4' => $one2four,
			'desc4' => $desc4,
			'oneto5' => $one2five,
			'desc5' => $desc5,
			'oneto6' => $one2six,
			'desc6' => $desc6
        );
        $where = array('id'=>$deptId);
        Config::get('db')->update('ProductiveDept',$data,$where);
        echo json_encode(array('success'=>true,'message'=>'Config Updated'));
        return true;
    }
    echo json_encode(array('success'=>false,'message'=>'Resource not found.'));
    return false;
}

/////////////////////////////////////
// ADD NEW ADMIN
function AddNewAdmin() {
    $first = (isset($_REQUEST['first'])) ? $_REQUEST['first'] : '';
	$last = (isset($_REQUEST['last'])) ? $_REQUEST['last'] : '';
	$newusername = (isset($_REQUEST['username'])) ? trim($_REQUEST['username']) : '';
	$role = (isset($_REQUEST['role'])) ? $_REQUEST['role'] : 4;
	$username = strtolower($newusername);
	$dbUser = '';
	$userRole = Config::get('account')['role'];
	$accountId = (isset($_REQUEST['accountId'])) ? $_REQUEST['accountId'] : 0;
	
	$dbUser= Config::get('db')->get_results("select * FROM `productiveUser` where `userName`='{$username}'");
	
	if(!$dbUser){	
		
	$pw = "productive2019";
	$pword = md5($pw);
			$data = array(
				'first_name'=>$first,
                'last_name'=>$last,
				'accountId'=>$accountId,
				'email'=>$username,
				'userName'=>$username,
				'pwd'=>$pword,
				'temp'=>$pword,
				'role'=>$role,
                'active'=>1
            );
			
    $newAdmin = Config::get('db')->insert('productiveUser',$data);
					
			$exists = array(
			'exist'=>false
			);
            echo json_encode(array('success'=>true,'data'=>$exists));
            return true;
	}else{
			$exists = array(
			'exist'=>true
			);
            echo json_encode(array('success'=>true,'data'=>$exists));
            return true;
	}
			
}

/////////////////////////////////////
// ADD NEW USER
function AddNewUser() {
    $first = (isset($_REQUEST['first'])) ? $_REQUEST['first'] : '';
	$last = (isset($_REQUEST['last'])) ? $_REQUEST['last'] : '';
	$newemail = (isset($_REQUEST['email'])) ? trim($_REQUEST['email']) : '';
	$role = (isset($_REQUEST['role'])) ? $_REQUEST['role'] : 4;
	$deptId = (isset($_REQUEST['unit'])) ? $_REQUEST['unit'] : '';
	$sendemail = (isset($_REQUEST['sendemail'])) ? intval($_REQUEST['sendemail']) : 0;
	$email = strtolower(addslashes($newemail));
	
	$dbUser = '';
	//$txt = $vtext + $atext;
	$userRole = Config::get('account')['role'];
	
	if(intval($role)==3){
		$provider = 1;
	}else{
		$provider = (isset($_REQUEST['provider'])) ? $_REQUEST['provider'] : 0;
	}
	
	if(intval($userRole)>89){
		$accountId = (isset($_REQUEST['accountId'])) ? $_REQUEST['accountId'] : '';
	}else{
		$accountId = Config::get('account')['accountId'];
	}
	
	if(intval($role)==6 && intval($deptId)>0){
		$txt = 1;
		$emailAlerts = 1;
		$emailMissed = 1;
		$emailEscalations= 1;
		$newDelete = array(
			'deptId'=>$deptId,
			'primaryUnit'=>$role,
			'unitAssigned'=>0
			);
		$managerDelete = Config::get('db')->delete('productiveDeptXref',$newDelete);
	}else if(intval($role)==7 && intval($deptId)>0){
		$txt = 0;
		$emailAlerts = 0;
		$emailMissed = 0;
		$emailEscalations=0;
		$dirDelete = array(
			'deptId'=>$deptId,
			'primaryUnit'=>$role,
			'unitAssigned'=>0
			);
		$directorDelete = Config::get('db')->delete('productiveDeptXref',$dirDelete);
	}else{
		$txt = 0;
		$emailAlerts = 0;
		$emailMissed = 0;
		$emailEscalations=0;
	}
	
	$dbUser= Config::get('db')->get_results("select * from `productiveUser` where `email`='{$email}' and `accountId`!={$accountId}");
	if($dbUser){
		$exists = array(
			'exist'=>true
			);
	echo json_encode(array('success'=>false,'data'=>$exists));
    return true;
	}else{
	   //do nothing;
	}
	//if (intval($deptId)>0){
	//$dbUser= Config::get('db')->get_results("select d.*, IFNULL(u.id,'') as newUserId, IFNULL(u.active,'') as userActive, IFNULL(l.labelName,'ProductiveRN') as labelName, IFNULL(l.login,'') as login, IFNULL(x.deptId,'') as deptId, IFNULL(x.primaryUnit,'') as primaryUnit, IFNULL(d.dept,'') as dept FROM `ProductiveDept` d LEFT OUTER JOIN `productiveUser` as u on u.email ='{$email}' LEFT OUTER JOIN `productiveDeptXref` as x on x.userId = u.id LEFT JOIN `productiveAccount` as a on a.id = d.accountId LEFT OUTER JOIN `productiveLabel` as l on l.id = a.label where d.id={$deptId}");
	if(intval($accountId)>0){	
	$dbNew= Config::get('db')->get_results("select a.*, IFNULL(u.id,0) as userId, u.active, x.deptId, l.id as labelId, l.labelName, l.noreply, l.userLogin, l.videoLink, l.login, d.dept, u.first_name, u.last_name from `productiveAccount` a LEFT JOIN `productiveLabel` as l on l.id = a.label LEFT OUTER JOIN `productiveUser` as u on u.accountId = a.id and u.email='{$email}' LEFT OUTER JOIN `productiveDeptXref` as x on x.userId=u.id LEFT OUTER JOIN `ProductiveDept` as d on d.id = x.deptId WHERE a.id={$accountId}");
	$userId = intval($dbNew[0]['userId']);
	$userActive = intval($dbNew[0]['active']);
	$deptName = $dbNew[0]['dept'];
	$noreply = $dbNew[0]['noreply'];
	$xdeptId = intval($dbNew[0]['deptId']);
	$labelName = $dbNew[0]['labelName'];
	$labelId = intval($dbNew[0]['labelId']);
	$login = $dbNew[0]['userLogin'];
	$enterpriseId = $dbNew[0]['enterpriseId'];
	$video = $dbNew[0]['videoLink'];
	}else{
	echo json_encode(array('success'=>false));
    return true;	
	}
	
	if($userId>0){
	
	if ($userActive>0 && $xdeptId>0) {
		$exists = array(
			'exist'=>true,
			'email'=>stripslashes($email),
			'dept'=>$deptName,
			'user'=>$userId
			);
			echo json_encode(array('success'=>true,'data'=>$exists));
            return true;
		}else{
			$dataUpdate = array(
				'first_name'=>$first,
                'last_name'=>$last,
				'accountId'=>$accountId,
				'email'=>$email,
				'role'=>$role,
				'txt'=>$txt,
				'emailAlerts'=>$emailAlerts,
				'emailMissed'=>$emailMissed,
				'emailEscalations'=>$emailEscalations,
                'active'=>1,
				'txtactive'=>$txt,
				'reportMissed'=>$txt,
				'txtEscalation'=>$txt,
				'provider'=>$provider
            );
			$whereUpdate = array(
				'id'=>$userId
			);
			
            $updateUser = Config::get('db')->update('productiveUser',$dataUpdate,$whereUpdate);
						
			$dataDelete = array(
				'userId'=>$userId
			);
			$newUserDelete = Config::get('db')->delete('productiveDeptXref',$dataDelete);
			
			if(intval($deptId)>0){
			$dataUpdate = array(
				'primaryUnit'=>$role,
				'userId'=>$userId,
				'deptId'=>$deptId,
				'textAlerts'=>$txt
			);
			$newUserUpdate = Config::get('db')->insert('productiveDeptXref',$dataUpdate);
			
			$exists = array(
			'exist'=>false,
			'email'=>stripslashes($email),
			'dept'=>$deptName,
			'user'=>$userId
			);
			
			}else{
			$exists = array(
			'exist'=>false,
			'email'=>stripslashes($email),
			'dept'=>'',
			'user'=>$userId
			);	
			}
			
			echo json_encode(array('success'=>true,'data'=>$exists));
            return true;
			
		}
			
			
		}else{
			
			if($labelId==2){
				$pw="perfect";
				//$support="https://www.perfectshift.com/learn/smtpublic"
				$support="For additional support videos, go <a href=\"https://www.perfectshift.com/learn/smtpublic\">here.</a>";
			}else{
				$pw = generateRandomString();
				$support = '';
			}	
			
			$pword = md5($pw);
		
            $data = array(
				'first_name'=>$first,
                'last_name'=>$last,
				'accountId'=>$accountId,
				'email'=>$email,
				'userName'=>$email,
				'pwd'=>$pword,
				'temp'=>$pword,
				'role'=>$role,
				'txt'=>$txt,
                'active'=>1,
				'reportMissed'=>$txt,
				'txtEscalation'=>$txt,
				'txtactive'=>$txt,
				'emailAlerts'=>$emailAlerts,
				'emailMissed'=>$emailMissed,
				'emailEscalations'=>$emailEscalations,
				'provider'=>$provider

            );
			
            $newLoc = Config::get('db')->insert('productiveUser',$data);
			$dbNew = Config::get('db')->get_results("select * from `productiveUser` where email='{$email}'");
			$newId = $dbNew[0]['id'];
			
			if(intval($deptId)>0){
			$data = array(
				'primaryUnit'=>$role,
				'userId'=>$newId,
				'deptId'=>$deptId,
				'textAlerts'=>$txt
			);
			$newUser = Config::get('db')->insert('productiveDeptXref',$data);
			}
		
		$subject = $labelName . " New Account";
		$to = $email;
		$url = "https://$login";
		$mail = stripslashes($email);

		$message = "<html>
		<head>
		<title>Welcome to $labelName</title>
		</head>
		<body>
		<table>
		<h5>$first,</h5>
		
		<tr>A $labelName ID has been created for you.  Your ID and temporary password are below.</tr>
		
		<p>
		<tr>ID: $mail</tr>
		</p>
		<p>
		<tr>Temporary Password: $pw</tr>
		</p>
		<p>
		<tr>To login, go to <a href=" . $url . ">$labelName</a></tr>
		</p>
		<tr>To see how to submit a Staffing Report on $labelName, click <a href=" . $video . ">here.</a></tr>
		
		<th>Thank you for using $labelName!</th>
		</table>
		<h5>
		NOTE: The information contained in this message may be privileged and confidential and protected from disclosure.  If the reader of this message is not the intended recipient, or an employee or agent responsible for delivering this message to the intended recipient, you are hereby notified that any dissemination, distribution or copying of this communication is strictly prohibited. If you have received this communication in error, please notify us immediately by replying to support@productivern.com and deleting it from your computer.
		</h5>
		<tr><h5>
		Thank you for considering the impact of printing emails on our environment.  Please don’t print unless it is necessary!
		</h5></tr>
		</body>
		</html>
		";

		// Always set content-type when sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

		// More headers
		//$headers .= 'From: <noreply@productivern.com>' . "\r\n";
		$headers .= 'From: <' . $noreply . '>' . "\r\n";
		
		if($sendemail !=0){
		mail($to,$subject,$message,$headers);
		}			
			$exists = array(
			'exist'=>false,
			'role'=>$role
			);
            echo json_encode(array('success'=>true,'data'=>$exists));
            return true;
								
			}
			
}

/////////////////////////////////////
// Forgot
function Forgot() {
    $email1 = (isset($_REQUEST['email'])) ? $_REQUEST['email'] : '';
	$email = trim($email1);
	 //echo json_encode(array('email'=>$email));
	$dbData = Config::get('db')->get_results("select u.*, l.userLogin, l.labelName, l.noreply, l.tip1_filename, a.label from `productiveUser` u left join `productiveAccount` as a on a.id=u.accountId LEFT JOIN `productiveLabel` as l on l.id = a.label where u.email='{$email}'");
	
    if ($dbData && intval($dbData[0]['active']) !=0) {
				
		$first = $dbData[0]['first_name'];
		$login = $dbData[0]['userLogin'];
		$label = $dbData[0]['labelName'];
		$noreply = $dbData[0]['noreply'];
		$labelId = intval($dbData[0]['label']);
		$file1 = $dbData[0]['tip1_filename'];
		
		
				$support = '';
				$pw = generateRandomString(10);
				$pword = password_hash($pw, PASSWORD_BCRYPT);
			
		
		//$text = intval($atext)+intval($rtext);
            $data = array(
				'pwd'=>$pword,
				'temp'=>$pword,
                'active'=>1
            );
			
		$updateWhere = array('email' =>addslashes($email));

       $newLoc = Config::get('db')->update('productiveUser',$data,$updateWhere);
	   
	   if(strlen($file1)>3){
		$attachment = "/home/prnadmin/public_html/docs/" . $file1;   
	   }else{
		$attachment = '';   
	   }
		
		
		$subject = $label . " Password Reset";
		$to = stripslashes($email);

$message = "
<html>
<head>
<title>$label Password Reset</title>
</head>
<body>
<table>
<tr>
<td>$first,</td>
</tr>
<tr></tr>
<tr>
<td>Your $label password has been reset.  Your Temporary password is below.</td>
</tr>
<tr></tr>
<tr></tr>
<tr>
<td>Temporary Password: $pw</td>
</tr>
<th></th>
<tr>
<td>To login, go to https://$login</td>
</tr>
<tr>$support</tr>
<th></th>
<tr>
<th>Thank you for using $label!</th>
</tr>
</table>
<tr></tr><tr></tr>
<tr><p><h5>
NOTE: The information contained in this message may be privileged and confidential and protected from disclosure.  If the reader of this message is not the intended recipient, or an employee or agent responsible for delivering this message to the intended recipient, you are hereby notified that any dissemination, distribution or copying of this communication is strictly prohibited. If you have received this communication in error, please notify us immediately by replying to support@productivern.com and deleting it from your computer.
</h5></p></tr>
<tr><p><h5>
Thank you for considering the impact of printing emails on our environment.  Please don’t print unless it is necessary!
</h5></p></tr>
</body>
</html>
";
/*
// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
//$headers .= 'From: <noreply@productivern.com>' . "\r\n";
$headers .= 'From: <' . $noreply . '>' . "\r\n";

mail($to,$subject,$message,$headers);
*/	
/////////////PHP Mailer
		$mail = new PHPMailer(true);
		$mail->IsSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'productivern.com';                 // Specify main and backup server
		//$mail->Host = gethostbyname("smtp.productivern.com"); 
		$mail->Port = 465;                                    // Set the SMTP port
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		//$mail->Encoding = '7bit';
		$mail->Username = 'noreply@productivern.com';                // SMTP username
		$mail->Password = 'xBWQ#231KyCv';                  // SMTP password
		$mail->SMTPSecure = 'ssl';                            // Enable encryption, 'ssl' also accepted

		$mail->From = 'noreply@productivern.com';
		$mail->FromName = ''. $label . '';
		$mail->AddAddress(''.$to.'');  // Add a recipient

		// Attachments
		if ($attachment) $mail->addAttachment($attachment);
		//$mail->AddEmbeddedImage('/home/prnadmin/public_html/dailyreports/vcImage'.$data2[0]['filedate'].'.jpg', 'vcImage');
		$mail->IsHTML(true);                                  // Set email format to HTML
		$mail->Subject = ''. $subject .'';
		$mail->Body    = ''.$message.'';

		if(!$mail->Send()) {
		   echo 'Message could not be sent.';
		   echo 'Mailer Error: ' . $mail->ErrorInfo;
		   exit;
		}	
		/////////////End PHP Mailer				
			
            echo json_encode(array('success'=>true,'message'=>'Password reset successfully.'));
            return true;
		}
	
    echo json_encode(array('success'=>false));
    return false;
}


function generateRandomString($length = 6) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

/////////////////////////////////////
// DELETE USER
function DeleteUser() {
    $userId = (isset($_REQUEST['userId'])?$_REQUEST['userId']:'');
	
	if ($userId) {
		//$userDb = Config::get('db')->get_results("SELECT * from `productiveUser` where `id`={$userId}");
		$data = array(
                'txt'=>0,
				'txtactive'=>0,
				'active'=>0
            );
        $where = array('id'=>$userId);
        Config::get('db')->update('productiveUser',$data,$where);
		
		$wherex = array('userId'=>$userId);
        Config::get('db')->delete('productiveDeptXref',$wherex);
		
        echo json_encode(array('success'=>true,'message'=>'User Deleted'));
        return true;
    }
    echo json_encode(array('success'=>false,'message'=>'User Failed to Delete'));
    return false;
}

/////////////////////////////////////
// GET DETAILS FOR RESOURCE
function GetResourceDetails() {
    $recordId = (isset($_REQUEST['recordId'])) ? $_REQUEST['recordId'] : 0;
	//echo "store:".$storeId;
    if (intval($recordId) > 0) {
        $dbLocation = Config::get('db')->get_results("select * from `productiveResources` where id={$recordId}");
        $dbData = $dbLocation[0];
        echo json_encode(array('success' => true,'data'=>$dbData));
        return true;
    }
    echo json_encode(array('success'=>false,'message'=>'Resource not found.'));
    return false;
}

/////////////////////////////////////
// GET DETAILS FOR RESOURCE
function GettextResourceDetails() {
    $recordId = (isset($_REQUEST['recordId'])) ? $_REQUEST['recordId'] : '';
	//echo "store:".$storeId;
    if ($recordId) {
        $dbLocation = Config::get('db')->get_results("select * from `productiveDeptXref` where id={$recordId}");
        $dbData = $dbLocation[0];
        echo json_encode(array('success' => true,'data'=>$dbData));
        return true;
    }
    echo json_encode(array('success'=>false,'message'=>'Record not found.'));
    return false;
}

/////////////////////////////////////
// ADD RESOURCE
function AddResource() {
    $deptId = (isset($_REQUEST['deptId'])) ? $_REQUEST['deptId'] : 0;
	$accountId = (isset($_REQUEST['accountId'])) ? $_REQUEST['accountId'] : 0;
	$name = (isset($_REQUEST['name'])) ? $_REQUEST['name'] : '';
	$resourcevalue = (isset($_REQUEST['resourcevalue'])) ? $_REQUEST['resourcevalue'] : 0;
	$sun = (isset($_REQUEST['sun'])) ? $_REQUEST['sun'] : 0;
	$mon = (isset($_REQUEST['mon'])) ? $_REQUEST['mon'] : 0;
	$tue = (isset($_REQUEST['tue'])) ? $_REQUEST['tue'] : 0;
	$wed = (isset($_REQUEST['wed'])) ? $_REQUEST['wed'] : 0;
	$thu = (isset($_REQUEST['thu'])) ? $_REQUEST['thu'] : 0;
	$fri = (isset($_REQUEST['fri'])) ? $_REQUEST['fri'] : 0;
	$sat = (isset($_REQUEST['sat'])) ? $_REQUEST['sat'] : 0;
	//echo "store:".$storeId;
    if (intval($deptId)>0) {
       $data = array(
			'accountId' => $accountId,
			'deptId' => $deptId,
            'position' => $name,
			'resourcevalue' => $resourcevalue,
			'Sun' => $sun,
            'Mon' => $mon,
			'Tue' => $tue,
			'Wed' => $wed,
			'Thu' => $thu,
			'Fri' => $fri,
			'Sat' => $sat
        );
        Config::get('db')->insert('productiveResources',$data);
        echo json_encode(array('success'=>true,'message'=>'Resource Added Successfully'));
        return true;
    }
    echo json_encode(array('success'=>false,'message'=>'Resource not added.'));
    return false;
}

/////////////////////////////////////
// UPDATE RESOURCE
function UpdateResource() {
    $recordId = (isset($_REQUEST['recordId'])) ? $_REQUEST['recordId'] : 0;
	$name = (isset($_REQUEST['name'])) ? $_REQUEST['name'] : 0;
	$resourcevalue = (isset($_REQUEST['resourcevalue'])) ? $_REQUEST['resourcevalue'] : 0;
	$sun = (isset($_REQUEST['sun'])) ? $_REQUEST['sun'] : 0;
	$mon = (isset($_REQUEST['mon'])) ? $_REQUEST['mon'] : 0;
	$tue = (isset($_REQUEST['tue'])) ? $_REQUEST['tue'] : 0;
	$wed = (isset($_REQUEST['wed'])) ? $_REQUEST['wed'] : 0;
	$thu = (isset($_REQUEST['thu'])) ? $_REQUEST['thu'] : 0;
	$fri = (isset($_REQUEST['fri'])) ? $_REQUEST['fri'] : 0;
	$sat = (isset($_REQUEST['sat'])) ? $_REQUEST['sat'] : 0;
	//echo "store:".$storeId;
    if (intval($recordId) > 0) {
       $data = array(
            'position' => $name,
			'Sun' => $sun,
            'Mon' => $mon,
			'Tue' => $tue,
			'Wed' => $wed,
			'Thu' => $thu,
			'Fri' => $fri,
			'Sat' => $sat,
			'resourcevalue' =>$resourcevalue
        );
        $where = array('id'=>$recordId);
        Config::get('db')->update('productiveResources',$data,$where);
        echo json_encode(array('success'=>true,'message'=>'Resource Updated'));
        return true;
    }
    echo json_encode(array('success'=>false,'message'=>'Resource not found.'));
    return false;
}

/////////////////////////////////////
// REMOVE RESOURCE
function RemoveResource() {
    $recordId = (isset($_REQUEST['recordId'])?$_REQUEST['recordId']:0);
	
	if (intval($recordId) > 0) {
		//$userDb = Config::get('db')->get_results("SELECT * from `productiveUser` where `id`={$userId}");
		    $where = array('id' => $recordId);
            Config::get('db')->delete('productiveResources', $where);
		
        echo json_encode(array('success'=>true,'message'=>'Resource Deleted'));
        return true;
    }
    echo json_encode(array('success'=>false,'message'=>'Resource Failed to Delete'));
    return false;
}

/////////////////////////////////////
// UPDATE USER
function UpdateUser() {
    $userId = (isset($_REQUEST['userId'])?$_REQUEST['userId']:'');
    $email = (isset($_REQUEST['email'])?$_REQUEST['email']:'');
    $role = (isset($_REQUEST['role'])?$_REQUEST['role']:'');
	$stop = (isset($_REQUEST['stop'])) ? $_REQUEST['stop'] : 0;
	$unitNew = (isset($_REQUEST['unit'])) ? $_REQUEST['unit'] : 0;
	$unitOrig = (isset($_REQUEST['unitOrig'])) ? $_REQUEST['unitOrig'] : 0;
	$roleOrig = (isset($_REQUEST['roleOrig'])?$_REQUEST['roleOrig'] : '');
	$provider = (isset($_REQUEST['provider'])) ? $_REQUEST['provider'] : 0;
	
	if($userId){

	$dataU = array(
		'role' =>$role,
		'provider' =>$provider
			);
			
	$whereU = array(
		'id' => $userId
			);
			Config::get('db')->update('productiveUser',$dataU,$whereU);
			
			echo json_encode(array('success'=>true,'message'=>'User Updated'));
			return true;
	}else{
		echo json_encode(array('success'=>true,'message'=>'Update Failed'));
			return false;
	}
}

/////////////////////////////////////
// UPDATE USER
function UpdateTextAlerts() {
    $recordId = (isset($_REQUEST['recordId'])?$_REQUEST['recordId']:'');
    $textAlerts = (isset($_REQUEST['textAlerts'])?$_REQUEST['textAlerts']:0);
	
	if($recordId){
		$datax = array(
            'textAlerts' =>$textAlerts
        );
        $wherex = array(
			'id'=>$recordId
		);
        Config::get('db')->update('productiveDeptXref',$datax,$wherex);
		echo json_encode(array('success'=>true,'message'=>'User Updated'));
		return true;
	}else{
		echo json_encode(array('success'=>true,'message'=>'Update Failed'));
		return false;
	}
}

function GetunitGrid() {
    $accountId = Config::get('account')['accountId'];
	$userRole = Config::get('account')['role'];
	$loggedinUser = Config::get('account')['id'];
	$userId = isset($_REQUEST['userId']) ? $_REQUEST['userId'] : '';
	$jobArray = array();
	$outData = array();
	
    if ($userId) {
        $dbLocation = Config::get('db')->get_results("SELECT u.*, x.id as Xid, x.textAlerts, x.userId, x.unitAssigned, x.deptId as depNum, r.role as roleName, x.id as Xid, x.primaryUnit, d.dept as deptName FROM `productiveUser` u LEFT OUTER JOIN `productiveDeptXref` as x on x.userId = u.id LEFT JOIN `ProductiveDept` as d on d.id = x.deptId LEFT OUTER JOIN `productiveUserRoles` as r on r.id = x.primaryUnit WHERE u.id={$userId} order by x.primaryUnit DESC, d.dept ASC");
    if (intval($dbLocation[0]['depNum'])>0){
		
	foreach ($dbLocation as $dbData){
		if (intval($dbData['txtPause'])==0 && intval($dbData['textAlerts'])>0 && intval($dbData['txtactive'])>0){
			$Status = "Yes - Active (User receiving Text Alerts)";
		}else if (intval($dbData['txtPause'])>0 && intval($dbData['textAlerts'])>0 && intval($dbData['txtactive'])>0){
			$Status = "Yes - Paused (User not receiving texts)";
		}else if (intval($dbData['textAlerts'])>0 && intval($dbData['txtactive'])==0){
			$Status = "Yes - Inactive (User must activate texting)";
		}else{
			$Status = "No";
		}
		//if(intval($dbData['primaryUnit'])>0 && intval($dbData['role'])==4 && intval($dbData['primaryUnit'])==4) {
		if(intval($dbData['unitAssigned'])==1 && intval($userRole)<7){
			$primary = " (Unit Assigned)";
			//$action = "<a href=\"#units\">Change in Unit Settings</a>";
			$action = "Change in Unit Settings";
		}else if(intval($dbData['unitAssigned'])==1 && intval($userRole)>=7){
			$primary = " (Unit Assigned)";
			$action = "<button type=\"button\" class=\"btn btn-success\" onclick=\"tj.removeuserUnit(" . $dbData['depNum'] . " , " . $dbData['userId'] . ")\">Remove</button>";
		}else{
			$primary = '';
			$action = "<button type=\"button\" class=\"btn btn-success\" onclick=\"tj.removeuserUnit(" . $dbData['depNum'] . " , " . $dbData['userId'] . ")\">Remove</button>";
		}
		//$user = $dbData['last_name'] . ", " . $dbData['first_name'];
		$txtStatus = "<a href=\"javascript:;\" onclick=\"tj.edittextStatus(" . $dbData['textAlerts'] . "," . $dbData['Xid'] . ");return false;\" >". $Status ."</a>";
		$position = $dbData['last_name'] . ", " . $dbData['first_name'];
			
				
        $jobArray[] = array(
			'unit'=>$dbData['deptName'],
            'text'=>$txtStatus,
            'role'=>$dbData['roleName'].$primary,
			'action'=>$action
        );
    }
    $outData['data'] = $jobArray;
    echo json_encode($outData,JSON_NUMERIC_CHECK);  
	}else{
	$jobArray[] = array(
				'unit' => '',
				'text' => '',
				'role' => '',
				'action' => ''
            );
        $outData['data'] = $jobArray;
        echo json_encode($outData,JSON_NUMERIC_CHECK);  
	}
}
}
	////////////////////////////////////////////////
//////// OptIn
function optIn(){
//include_once 'inc/class.db.php';
//include_once 'inc/config.php';
	$mobile = isset($_POST['mobile']) ? $_POST['mobile'] : false;
	$id = isset($_POST['id']) ? $_POST['id'] : '';
	$report = isset($_POST['report']) ? $_POST['report'] : 0;
	$missed = isset($_POST['missed']) ? $_POST['missed'] : 0;
	$escalation = isset($_POST['escalation']) ? $_POST['escalation'] : 0;
	$times = isset($_POST['times']) ? $_POST['times'] : 0;
	$pause = isset($_POST['pause']) ? $_POST['pause'] : 0;
	
	$dbLabel = Config::get('db')->get_results("SELECT l.* FROM `productiveLabel` l LEFT JOIN `productiveAccount` as a on a.label = l.id LEFT JOIN `productiveUser` u on u.accountId = a.id WHERE u.mobile={$mobile}");
	
	if($dbLabel){
	$labelName = $dbLabel[0]['labelName'];
	}else{
	$labelName = "ProductiveRN";
	}
	
	if ($mobile){		
		
			$number = "1" . $mobile;
		    $now = time();
			
			$msgId = "";
			$msgId .= "";
			$msgId .= $mobile . $now;
			$psword = "J8775bcgEE2065";
			$keyword = "JOBALARM58046";
			$slooce = "jobalarm45";
			$content = "PRODUCTIVE";

			$xmlmsg = "";
			$xmlmsg .= "";		
			$xmlmsg .= "<message id=\"".$msgId."\">";
			$xmlmsg .= "<partnerpassword>".$psword."</partnerpassword>";
			$xmlmsg .= "<content>".$content."</content>";
			$xmlmsg .= "</message>"
			;
			
			$startoptions = Array(
					'numbers' => $number,
					'message' => $xmlmsg,
					'login' => $slooce,
					'keyword' => $keyword

				);
			$sms_result = '';
		
		$sms_result = send_messages_start($startoptions);
		
		$data = array(
                'txtactive'=>1,
				'mobile'=>$mobile,
				'txt'=> $report,
				'reportMissed'=> $missed,
				'txtEscalation'=> $escalation,
				'txtPause'=> $pause,
				'alertTimes'=> $times				
            );
        $where = array('id'=>$id);
        Config::get('db')->update('productiveUser',$data,$where);
		
		echo json_encode(array('success'=>true,'mobile activated'=>$mobile,'label'=>$label));
		return true;
	}
	echo json_encode(array('success'=>false,'mobile'=>$mobile));
	return false;
	
	}
	
function optIn2(){
//include_once 'inc/class.db.php';
//include_once 'inc/config.php';
	$mobile = isset($_POST['mobile']) ? $_POST['mobile'] : false;
	$id = isset($_POST['id']) ? $_POST['id'] : '';
	
	if ($mobile){		
		
			$number = "1" . $mobile;
		    $now = time();
			
			$msgId = "";
			$msgId .= "";
			$msgId .= $mobile . $now;
			$psword = "J8775bcgEE2065";
			$keyword = "JOBALARM58046";
			$slooce = "jobalarm45";
			$content = "PRODUCTIVE";

			$xmlmsg = "";
			$xmlmsg .= "";		
			$xmlmsg .= "<message id=\"".$msgId."\">";
			$xmlmsg .= "<partnerpassword>".$psword."</partnerpassword>";
			$xmlmsg .= "<content>".$content."</content>";
			$xmlmsg .= "</message>"
			;
			
			$startoptions = Array(
					'numbers' => $number,
					'message' => $xmlmsg,
					'login' => $slooce,
					'keyword' => $keyword

				);
			$sms_result = '';
		
		$sms_result = send_messages_start($startoptions);
		
		$data = array(
                'txtactive'=>1,
				'txtEscalation'=>1,
				'alertTimes'=>3,
				'mobile'=>$mobile
            );
        $where = array('id'=>$id);
        Config::get('db')->update('productiveUser',$data,$where);
		
		echo json_encode(array('success'=>true));
		return true;
	//exit();
	}
	echo json_encode(array('success'=>false));
	//exit();
	return false;
	}
	
function GettextConfigure() {
    $accountId = Config::get('account')['accountId'];
	//$deptId = Config::get('account')['deptid'];
	$deptId = (isset($_REQUEST['deptId'])) ? $_REQUEST['deptId'] : 0;
	$jobArray = array();
	$outData = array();
	//if (intval($dept)!=0){
	//	$deptId = $dept;
	//}else{
	//	$deptId = Config::get('account')['deptid'];
	//}
	
	//echo "store:".$storeId;
    if ($deptId) {
        $dbLocation = Config::get('db')->get_results("SELECT x.*, r.role as roleName, u.last_name, u.first_name, u.role, u.txtactive, u.txtPause FROM `productiveDeptXref` x LEFT OUTER JOIN `productiveUser` as u on u.id = x.userId LEFT JOIN `productiveUserRoles` as r on r.id = u.role WHERE x.deptid={$deptId} and x.unitAssigned=1");
    if ($dbLocation){
	foreach ($dbLocation as $dbData){
		if (intval($dbData['txtPause'])==0 && intval($dbData['textAlerts'])>0 && intval($dbData['txtactive'])>0){
			$Status = "Yes - Active (User receiving Text Alerts)";
		}else if (intval($dbData['txtPause'])>0 && intval($dbData['textAlerts'])>0 && intval($dbData['txtactive'])>0){
			$Status = "Yes - Paused (User not receiving texts)";
		}else if (intval($dbData['textAlerts'])>0 && intval($dbData['txtactive'])==0){
			$Status = "Yes - Inactive (User must activate texting)";
		}else{
			$Status = "No";
		}
		//$user = $dbData['last_name'] . ", " . $dbData['first_name'];
		$txtStatus = "<a href=\"javascript:;\" onclick=\"tj.edittextStatus(".$dbData['id'].");return false;\" >". $Status ."</a>";
		$position = $dbData['last_name'] . ", " . $dbData['first_name'];
		$action = "<button type=\"button\" class=\"btn btn-danger\" onclick=\"tj.deletetextResource(" . $dbData['id'] . ")\">Remove</button>";	
				
        $jobArray[] = array(
			'user'=>$position,
            'role'=>$dbData['roleName'],
            'status'=>$txtStatus,
			'action'=>$action
        );
    }
    $outData['data'] = $jobArray;
    echo json_encode($outData,JSON_NUMERIC_CHECK);  
	}else{
	$jobArray[] = array(
				'user' => '',
				'role' => '',
				'status' => '',
				'action' => ''
            );
        $outData['data'] = $jobArray;
        echo json_encode($outData,JSON_NUMERIC_CHECK);  
	}
}
}



/////////////////////////////////////
// ADD RESOURCE
function AddtextResource() {
    $deptId = (isset($_REQUEST['deptId'])) ? $_REQUEST['deptId'] : '';
	$userId = (isset($_REQUEST['userId'])) ? $_REQUEST['userId'] : '';
	$grantText = (isset($_REQUEST['grantText'])) ? $_REQUEST['grantText'] : '';
	
	if($deptId && $userId){
		$dbText = Config::get('db')->get_results("select u.*, x.deptId, x.unitAssigned from `productiveUser` u LEFT OUTER JOIN `productiveDeptXref` as x on x.userId =u.id and x.deptId={$deptId} where u.id={$userId}");
		//$assigned = $dbText[0]['deptId'];
	if(intval($dbText[0]['deptId'])>0) {
		//$xId = $dbText[0]['id'];
       
		//$data = array('textAlerts' => $grantText);
		//$where = array('id' =>$xId);
        
		//Config::get('db')->update('productiveDeptXref',$data,$where);
        echo json_encode(array('success'=>true,'message'=>false));
        return true;
    }else{
		$data = array(
			'textAlerts' => $grantText,
			'userId' => $userId,
			'deptId' => $deptId,
			'primaryUnit' => $dbText[0]['role'],
			'unitAssigned' =>1
        );
		Config::get('db')->insert('productiveDeptXref',$data);
        echo json_encode(array('success'=>true,'message'=>true));
        return true;		
	}	
    echo json_encode(array('success'=>false,'message'=>'Resource not added.'));
    return false;
}
}

/////////////////////////////////////
// REMOVE RESOURCE
function RemovetextResource() {
    $recordId = (isset($_REQUEST['recordId'])?$_REQUEST['recordId']:'');
	
	if ($recordId) {
		//$userDb = Config::get('db')->get_results("SELECT * from `productiveUser` where `id`={$userId}");
		    $where = array('id' => $recordId);
            Config::get('db')->delete('productiveDeptXref', $where);
		
        echo json_encode(array('success'=>true,'message'=>'User Removed'));
        return true;
    }
    echo json_encode(array('success'=>false,'message'=>'Update Failed'));
    return false;
}

/////////////////////////////////////
// REMOVE RESOURCE
function RemoveUnit() {
    //$recordId = (isset($_REQUEST['recordId'])?$_REQUEST['recordId']:'');
	 $userId = isset($_REQUEST['user']) ? $_REQUEST['user']:'';
	 $deptId = isset($_REQUEST['dept']) ? $_REQUEST['dept']:'';
	
	if(intval($userId)>0) {
		//$userDb = Config::get('db')->get_results("SELECT * from `productiveUser` where `id`={$userId}");
		    $whereD = array('deptId'=> $deptId,
							'userId'=> $userId);
            Config::get('db')->delete('productiveDeptXref', $whereD);
		
        echo json_encode(array('success'=>true,'message'=>'User Removed'));
        return true;
    }else{
    echo json_encode(array('success'=>false,'message'=>$userId));
    return false;
	}
}

/////////////////////////////////////
// UPDATE RESOURCE
function UpdatetextResource() {
    $recordId = (isset($_REQUEST['recordId'])?$_REQUEST['recordId']:'');
	$textval = (isset($_REQUEST['updateval'])?$_REQUEST['updateval']:0);
	
	if ($recordId) {
		//$userDb = Config::get('db')->get_results("SELECT * from `productiveUser` where `id`={$userId}");
		    $data = array('textAlerts' => $textval);
			$where = array('id' => $recordId);
            Config::get('db')->update('productiveDeptXref',$data,$where);
		
        echo json_encode(array('success'=>true,'message'=>'User Update'));
        return true;
    }
    echo json_encode(array('success'=>false,'message'=>'Update Failed'));
    return false;
}
	
/*
 
 UNITS
  ______  ___  _____  _____
  | ___ \/ _ \|  __ \|  ___|
  | |_/ / /_\ \ |  \/| |__
  |  __/|  _  | | __ |  __|
  | |   | | | | |_\ \| |___
  \_|   \_| |_/\____/\____/


*/

/////////////////////////////////////
// GET ALL USERS
function GetAllUnits() {
    $accountId = Config::get('account')['accountId'];
	$userId = Config::get('account')['id'];
	$role = Config::get('account')['role'];
	$enterpriseId = Config::get('account')['enterpriseId'];
	$label = Config::get('account')['label'];
	
	if(intval($role)<8){
		$searchAdd = "WHERE d.accountId ={$accountId} AND d.id in (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId} and `primaryUnit`>=5)";
	}else if(intval($role)>=8 && intval($role)<11){
		$searchAdd = "WHERE d.accountId ={$accountId} AND d.active !=0";
	}else if(intval($role)==11){
		$searchAdd = "WHERE d.accountId ={$accountId}";
	}else if(intval($role)>=12 && intval($role)<90){
		$searchAdd = "WHERE a.enterpriseId ={$enterpriseId}";
	}else{
		$searchAdd = '';
	}
    
        $query = "SELECT SQL_CALC_FOUND_ROWS
		d.*, c.categoryName, (SELECT `id` FROM `productiveStaffingGrid` WHERE `griddeptId`= d.id LIMIT 0,1) as hasGrid, unitId as number, a.enterpriseId, a.name as accountName, IFNULL(u.last_name,'') as manager, m.name as prodname, IFNULL(u.first_name,'') as first_name, IFNULL(u.role,0) as role    
        FROM `ProductiveDept` d
		LEFT JOIN `productiveMeasures` as m on m.id = d.prodMeasure1
		LEFT JOIN `productiveAccount` as a on a.id = d.accountId		
		LEFT OUTER JOIN `productiveDeptXref` as x on x.deptId = d.id AND x.primaryUnit=6
		LEFT OUTER JOIN `productiveUser` as u on u.id = x.userId
		LEFT OUTER JOIN `productiveCategory` as c on c.id = d.category
        $searchAdd 
		GROUP BY d.id ORDER BY a.name ASC, d.dept ASC
		";

    $dbData = Config::get('db')->get_results($query);
    $query = "SELECT FOUND_ROWS() AS found_rows;";
    $countData = Config::get('db')->get_results($query);
    $total = $countData[0]['found_rows'];
    $jobArray = array();
    $link = 0;
	
	if ($dbData){
    //$brandcandidates = Config::get('db')->get_results("SELECT c.*, x.keyword as keyword FROM `candidate` as c LEFT JOIN candidateXref as x on x.candidateId = c.id WHERE x.brandId={$brand} and c.active=1 and (x.promo=1 or x.promo=2)");
	
    foreach ($dbData as $job) {
        
		if(intval($role)>12){
		$name = $job['accountName'] . " - " . $job['dept'];
		}else{
		$name = $job['dept'];
		}
		
		if(intval($job['active'])==0){
			$name = $name. " (inactive)";
		}else{
			//do nothing;
		}
		
		$number = $job['number'];
		if(floatval($job['hppd'])>0){
			$value = $job['hppd'];
		}else{
			$value = '';
		}
		$prodName = $job['prodname'];
		$target = $job['target'];
		if($job['manager']){
		$manager = $job['manager'].", ".$job['first_name'];
		}else{
		$manager = '';	
		}
		
		$unitLink = '<a href="javascript:;" onclick="tj.editUnitMgr('.$job['id'].' , '.$role.');return false;" >' . $name . '</a>';	
		
		if($job['hasGrid']){
		
		$grid = "<form id=\"dateFormgrid\" name=\"dateFormgrid\" action=\"inc/reports.php?req=downloadgrid\" method=\"post\"><button id=\"downloadGrid\" class=\"btn btn-info btn-sm\" type=\"submit\">Grid</button><input type=\"number\" name=\"griddeptId\" value=\"" . $job['id'] . "\" hidden></form>";;
		//$grid2 = '';
		}else{		
		$grid="<button type=\"button\" id=\"addGrid\" class=\"btn btn-success btn-sm\" onclick=\"tj.addGrid(" . $job['id'] . "); \" hidden>Add</button>";
		}
				
        $jobArray[] = array(
            'Name'=>$unitLink,
			'Category'=>$job['categoryName'],
            'ProdName'=>$prodName,
            'Value'=>$value,
			'Manager'=>$manager,
			'Number'=>$grid
        );
    }
    $outData = array();
    $outData['data'] = $jobArray;
    echo json_encode($outData,JSON_NUMERIC_CHECK);
	return true;
	}else{
    $outData = array();
        $outData['data'] = array();
        echo json_encode($outData,JSON_NUMERIC_CHECK);
		
	}

}

/////////////////////////////////////
// GET ALL USERS
function GetAllAccounts() {
    $accountId = Config::get('account')['accountId'];
	$enterpriseId = Config::get('account')['enterpriseId'];
	$role = Config::get('account')['role'];
	$label = Config::get('account')['label'];
	$accountArray=array();
	
	if(intval($role)>=7 && intval($role)<=12){
		$searchAdd = "WHERE a.active>0 AND a.id={$accountId}";
	}else if(intval($role)>12 && intval($role)<90){
		$searchAdd = "WHERE a.active>0 AND a.enterpriseId={$enterpriseId}";
	}else if(intval($role)>=90 && intval($role)<100){
		$searchAdd = "WHERE a.label={$label}";
	}else{
		$searchAdd = '';
	}
    
     $dbAccounts = Config::get('db')->get_results("SELECT a.*, IFNULL(l.labelName,'') as labelName 
		FROM `productiveAccount` a
		LEFT JOIN `productiveLabel` as l on l.id=a.label 
		$searchAdd 
		GROUP BY a.id
		");
	if($dbAccounts){
	foreach ($dbAccounts as $account) {
        $nameLink = '<a href="javascript:;" onclick="tj.editAccount('.$account['id'].')">'. $account['name'] .'</a>';
		
		$accountArray[] = array(
            'name'=>$nameLink,
            'city'=>$account['accountCity'],
			'state'=>$account['accountState'],
            'label'=>$account['labelName']
        );
		}
		$outData = array();
		$outData['data'] = $accountArray;
		echo json_encode($outData,JSON_NUMERIC_CHECK);
		return true;
		}else{
	    echo json_encode(array('success'=>false,'message'=>'Account not found.'));
		return false;
		}

}


/////////////////////////////////////
// GET DETAILS FOR SINGLE USER
function GetUnitDetails() {
    $deptId = (isset($_REQUEST['deptId'])) ? $_REQUEST['deptId'] : '';
	$accountId = Config::get('account')['accountId'];
	$role = Config::get('account')['role'];
	$enterpriseId = Config::get('account')['enterpriseId'];
	
        $dbUnits = Config::get('db')->get_results("select d.*, (SELECT GROUP_CONCAT(`shift` ORDER BY `shift` DESC SEPARATOR ',') from `productiveAccountXref` where `dept`=d.id) as Shifts, d.dept as unitName, m.prodDesc, IFNULL(u.last_name,'') as last_name, IFNULL(u.id,0) as userId, IFNULL(u.first_name,'') as first_name, (SELECT `id` FROM `productiveStaffingGrid` WHERE `griddeptId`= d.id LIMIT 0,1) as hasGrid from `ProductiveDept` d LEFT JOIN `productiveDeptXref` as x on x.deptId = d.id and x.primaryUnit=6 LEFT JOIN `productiveUser` as u on u.id=x.userId and x.primaryUnit=6 LEFT JOIN `productiveMeasures` as m on m.id=d.prodMeasure where d.id={$deptId}");
        $dbData = $dbUnits[0];
		$dbDir = Config::get('db')->get_results("select d.*, d.dept as unitName, m.prodDesc, IFNULL(u.last_name,'') as last_name2, IFNULL(u.id,0) as userId2, IFNULL(u.first_name,'') as first_name2 from `ProductiveDept` d LEFT JOIN `productiveDeptXref` as x on x.deptId = d.id and x.primaryUnit=7 LEFT JOIN `productiveUser` as u on u.id=x.userId and x.primaryUnit=7 LEFT JOIN `productiveMeasures` as m on m.id=d.prodMeasure1 where d.id={$deptId}");
        $dbdirector = $dbDir[0];
		$dbshifts = Config::get('db')->get_results("select * FROM `productiveAccountXref` WHERE `dept`={$deptId} ORDER by `shift` DESC");
        //$shift1 = str_replace(",","\",\"",$dbData['Shifts']);
		//$shifts = json_encode(array($shift1));
		//str_replace("'","",$comments);
		$shifts = array();
		$shifts = explode (",", $dbData['Shifts']); 
		$shiftname = "";
		$shiftname .="";
		foreach($dbshifts as $shiftn){
		$shiftname .= $shiftn['shiftName'] . '</br>';
		}
			
        
		if($dbUnits){
		echo json_encode(array('success' => true,'data'=>$dbData,'data2'=>$dbdirector,'shifts'=>$shifts,'shiftnames'=>$shiftname));
        return true;
		}else{
	    echo json_encode(array('success'=>false,'message'=>'Unit not found.'));
		return false;
		}
}

// GET DETAILS FOR SINGLE USER
function GetAccountDetails() {
    $accountId = (isset($_REQUEST['accountId'])) ? $_REQUEST['accountId'] : '';
	//$accountId = Config::get('account')['accountId'];
	
        $dbUnits = Config::get('db')->get_results("select a.*, a.image as accountImage, l.id as labelId from `productiveAccount` a LEFT JOIN `productiveLabel` as l on l.id = a.label where a.id ={$accountId}");
             
		if($dbUnits){
		$dbData = $dbUnits[0];	
		echo json_encode(array('success' => true,'data'=>$dbData));
        return true;
		}else{
	    echo json_encode(array('success'=>false,'message'=>'Account not found.'));
		return false;
		}
}
/////////////////////////////////////
// ADD NEW USER
function AddUnit() {
    $name = (isset($_REQUEST['unitName'])) ? $_REQUEST['unitName'] : '';
	$number = (isset($_REQUEST['unitNumber'])) ? $_REQUEST['unitNumber'] : '';
	$prodMeasure = (isset($_REQUEST['unitprodMeasure'])) ? $_REQUEST['unitprodMeasure'] : 0;
	$prodValue = (isset($_REQUEST['unitprodValue'])) ? $_REQUEST['unitprodValue'] : 0;
	$unitCategory = (isset($_REQUEST['unitCategory'])) ? $_REQUEST['unitCategory'] : 0;
	$uosDesc = (isset($_REQUEST['uosDesc'])) ? $_REQUEST['uosDesc'] : '';
	$target = (isset($_REQUEST['unitTarget'])) ? $_REQUEST['unitTarget'] : 100;
	$manager = (isset($_REQUEST['manager'])) ? $_REQUEST['manager'] : 0;
	$director = (isset($_REQUEST['director'])) ? $_REQUEST['director'] : 0;
	$beds = (isset($_REQUEST['unitBeds'])) ? $_REQUEST['unitBeds'] : 0;
	$census = (isset($_REQUEST['censusShift'])) ? $_REQUEST['censusShift'] : 52;
	$shiftsDay = (isset($_REQUEST['shiftsDay'])) ? $_REQUEST['shiftsDay'] : 1;
	$userId = Config::get('account')['id'];	
	$role = Config::get('account')['role'];	
	$currentDate = (isset($_REQUEST['currentDate'])) ? $_REQUEST['currentDate'] : '';
	$opDays = (isset($_REQUEST['opDays'])) ? intval($_REQUEST['opDays']) : 0;
	$accountId = Config::get('account')['accountId'];
	
	
	if(intval($prodMeasure)==2){
		$viewPage ="w.php?i=";
		$censusShift =0;
		$rnThreshold =0;
		$inshiftProd =0;
		$charge =0.0;
		$shiftsperDay = 0;
		$showVisits = 0;
		$showrn = 0;
		$uosDesc = "Units of Service";
		$skill1 = 1;
	}else if(intval($prodMeasure)==3){
		$viewPage ="w.php?i=";
		$censusShift =0;
		$rnThreshold =0;
		$inshiftProd =0;
		$charge =0.0;
		$shiftsperDay = 0;
		$showVisits = 0;
		$showrn = 0;
		$uosDesc = "Units of Service";
		$skill1 = 1;
	}else if(intval($prodMeasure)==4){
		$viewPage ="v.php?i=";
		$censusShift =52;
		$rnThreshold =2;
		$inshiftProd =1;
		$charge =1.0;
		$shiftsperDay = 0;
		$showVisits = 1;
		$showrn = 1;
		$uosDesc = "Units of Service";
		$skill1 = 0;
	}else{
		$viewPage ="v.php?i=";
		$censusShift =52;
		$rnThreshold =2;
		$inshiftProd =1;
		$charge =1.0;
		$shiftsperDay = 0;
		$showVisits = 0;
		$showrn = 1;
		$uosDesc = "Units of Service";
		$skill1 = 0;
	}
	
    $dbUnit = Config::get('db')->get_results("select * FROM `ProductiveDept` WHERE `accountId`={$accountId} and `unitId`='{$number}'");
	
	if (!$dbUnit) {
			
            $data = array(
				'dept'=>$name,
                'unitId'=>$number,
				'prodMeasure'=>$prodMeasure,
				'prodMeasure1'=>$prodMeasure,
				'hppd'=>$prodValue,
				'uosDesc'=>$uosDesc,
				'viewPage'=>$viewPage,
                'target'=>$target,
				'totalBeds'=>$beds,
				'accountId'=>$accountId,
				'shift'=>$censusShift,
                'active'=>0,
				'inshiftProd'=>$inshiftProd,
				'rnThreshold'=>$rnThreshold,
				'charge'=>$charge,
				'createdBy'=>$userId,
				'unitCategory'=>$unitCategory,
				'showVisits'=>$showVisits,
				'rnVariance'=>0,
				'uosDesc'=>$uosDesc,
				'skill1'=>$skill1
            );

            $newLoc = Config::get('db')->insert('ProductiveDept',$data);
		
			$dbNew = Config::get('db')->get_results("select * FROM `ProductiveDept` WHERE `dept`='{$name}' and `hppd`={$prodValue} and `accountId`={$accountId} and `unitId`='{$number}'");
            $newId = $dbNew[0]['id'];
		
			
			$dataNewDir = array(
				'primaryUnit'=>7,
				'deptId'=>$newId,
				'userId'=>$director
            );
				
            $newDir = Config::get('db')->insert('productiveDeptXref',$dataNewDir);
			
			$dataNewMgr = array(
				'primaryUnit'=>6,
				'textAlerts'=>1,
				'deptId'=>$newId,
				'userId'=>$manager
            );
				
            $newMgr = Config::get('db')->insert('productiveDeptXref',$dataNewMgr);
				
		
			if(intval($prodMeasure)==2){
			$dbXref = Config::get('db')->get_results("select * FROM `productiveShifts` WHERE `shiftsperDay`=1");
			}else{
			$dbXref = Config::get('db')->get_results("select * FROM `productiveShifts` WHERE `shiftsperDay`=6");	
			}
			
			foreach ($dbXref as $NewXref){
				$shift = $NewXref['id'];
				$whpPlan=0;
				$name = $NewXref['shift'];
			
			
			$dataNewXref = array(
				'accountId'=>$accountId,
				'dept'=>$newId,
				'shift'=>$shift,
				'value'=>0,
				'shifthrs'=>0,
				'whpPlan'=>$whpPlan,
				'shiftName'=>$name,
				'active'=>1
            );
				
            $newMgr = Config::get('db')->insert('productiveAccountXref',$dataNewXref);
			
			
			$dataNewData = array(
				'accountId'=>$accountId,
				'deptId'=>$newId,
				'shift'=>$shift,
				'whpPlan'=>0,
				'type'=>1,
				'dayDate'=>$currentDate,
				'chargecount'=>$charge
            );
				
            $newMgr = Config::get('db')->insert('productiveNewData',$dataNewData);
			
			
			
			}
			
			for($i=1;$i<7;$i++)	{			
			$date = strtotime($currentDate . " +" . $i . " day");
			//$date = strtotime($currentDate);
			$newDate = date('Y-m-d',$date);
			$dayofweek = date('w', $date);
			$weekDay = intval($dayofweek);
			
			if(($prodMeasure==2 or $prodMeasure==3) && $opDays==1){
			
			$dataNew = array(
				'accountId'=>$accountId,
				'deptId'=>$newId,
				'shift'=>$shift,
				'whpPlan'=>0,
				'type'=>1,
				'dayDate'=>$newDate
            );
				
            $newMgr = Config::get('db')->insert('productiveNewData',$dataNew);
			
			}else if(($prodMeasure==2 or $prodMeasure==3) && $opDays==2 && $weekDay !=6){
			
			$dataNew = array(
				'accountId'=>$accountId,
				'deptId'=>$newId,
				'shift'=>$shift,
				'whpPlan'=>0,
				'type'=>1,
				'dayDate'=>$newDate
            );
				
            $newMgr = Config::get('db')->insert('productiveNewData',$dataNew);
			}else if(($prodMeasure==2 or $prodMeasure==3) && $opDays==3 && $weekDay !=0 && $weekDay !=6){
			
			$dataNew = array(
				'accountId'=>$accountId,
				'deptId'=>$newId,
				'shift'=>$shift,
				'whpPlan'=>0,
				'type'=>1,
				'dayDate'=>$newDate
            );
				
            $newMgr = Config::get('db')->insert('productiveNewData',$dataNew);
			}else{
			//do nothing;
			}
			
			}
			
			echo json_encode(array('success'=>true,'exists'=>false,'message'=>'Unit entered successfully.'));
            return true;
	}else{
    echo json_encode(array('success'=>true,'exists'=>true,'message'=>'Duplicate Unit'));
    return true;
	}
}

/////////////////////////////////////
// DELETE USER
function DeleteUnit() {
    $userId = (isset($_REQUEST['userId'])?$_REQUEST['userId']:0);
	
	if ($userId > 0) {
		//$userDb = Config::get('db')->get_results("SELECT * from `productiveUser` where `id`={$userId}");
		$data = array(
                'txt'=>0,
				'txtactive'=>0,
				'active'=>0
            );
        $where = array('id'=>$userId);
        Config::get('db')->update('productiveUser',$data,$where);
		
        echo json_encode(array('success'=>true,'message'=>'User Deleted'));
        return true;
    }
    echo json_encode(array('success'=>false,'message'=>'User Failed to Delete'));
    return false;
}

/////////////////////////////////////
// UPDATE REPORT SHIFTS
function updateShifts() {
    $deptId = (isset($_REQUEST['deptId'])?$_REQUEST['deptId']:'');
	$shifts = (isset($_REQUEST['shifts'])?$_REQUEST['shifts']:'');
	$accountId = Config::get('account')['accountId'];
    
	if ($deptId && $shifts) {
		$whereRemove = array(
				'dept'=> $deptId
				);
		Config::get('db')->delete('productiveAccountXref',$whereRemove);
	
	foreach($shifts as $shift){
		$shiftName = Config::get('db')->get_results("SELECT * from `productiveShifts` WHERE `id`={$shift}");
				
		$data = array(
            'accountId' => $accountId,
            'dept' => $deptId,
			'shiftName' => $shiftName[0]['shift'],
			'shift' => $shift,
			'active' =>1
        );
		
        Config::get('db')->insert('productiveAccountXref',$data);
	}
	echo json_encode(array('success'=>true));
        return true;
    }else{
	 echo json_encode(array('success'=>false,'message'=>'Unit Failed to Update'));
    return true;		
	}
   
}

/////////////////////////////////////
// UPDATE USER
function UpdateUnit() {
    $unitId = (isset($_REQUEST['unitId'])?$_REQUEST['unitId']:'');
	$unitName = (isset($_REQUEST['name'])?$_REQUEST['name']:'');
    $unitTarget = (isset($_REQUEST['unitTarget'])?$_REQUEST['unitTarget']:100);
    $unitNumber = (isset($_REQUEST['unitNumber'])?$_REQUEST['unitNumber']:'');
	$bedCount = (isset($_REQUEST['bedCount'])?$_REQUEST['bedCount']:0);
	$prodMeasure = (isset($_REQUEST['prodMeasure'])) ? $_REQUEST['prodMeasure'] :0;
	$value = (isset($_REQUEST['value'])) ? $_REQUEST['value'] : 0;
	$censusShift = (isset($_REQUEST['censusShift'])) ? $_REQUEST['censusShift'] : 0;
	$newMgr = (isset($_REQUEST['newMgr'])) ? $_REQUEST['newMgr'] : 0;
	$newDir = (isset($_REQUEST['newDir'])) ? $_REQUEST['newDir'] : 0;


    if ($unitId) {
		
		if (intval($newMgr)!=0 && intval($newDir)!=0){
		$data = array(
            'target' => $unitTarget,
            'dept' => $unitName,
			'unitId' => $unitNumber,
			'totalBeds' => $bedCount,
			'prodMeasure' => $prodMeasure,
			'prodMeasure1' => $prodMeasure,
			'hppd' => $value,
			'director' => $newDir,
			'shift' => $censusShift
        );
		}else if (intval($newMgr)!=0 && intval($newDir)==0){
		$data = array(
            'target' => $unitTarget,
            'dept' => $unitName,
			'unitId' => $unitNumber,
			'totalBeds' => $bedCount,
			'prodMeasure' => $prodMeasure,
			'prodMeasure1' => $prodMeasure,
			'hppd' => $value,
			'mgrId' => $newMgr,
			'shift' => $censusShift
        );
		}else if (intval($newMgr)==0 && intval($newDir)!=0){
		$data = array(
            'target' => $unitTarget,
            'dept' => $unitName,
			'unitId' => $unitNumber,
			'totalBeds' => $bedCount,
			'prodMeasure' => $prodMeasure,
			'prodMeasure1' => $prodMeasure,
			'hppd' => $value,
			'director' => $newDir,
			'shift' => $censusShift
        );
		}else{
		$data = array(
            'target' => $unitTarget,
            'dept' => $unitName,
			'unitId' => $unitNumber,
			'totalBeds' => $bedCount,
			'prodMeasure' => $prodMeasure,
			'prodMeasure1' => $prodMeasure,
			'hppd' => $value,
			'shift' => $censusShift
        );
		}
        $where = array('id'=>$unitId);
        Config::get('db')->update('ProductiveDept',$data,$where);
        echo json_encode(array('success'=>true,'message'=>'Unit Updated'));
        return true;
    }else{
	 echo json_encode(array('success'=>false,'message'=>'Unit Failed to Update'));
    return false;		
	}
   
}

/////////////////////////////////////
// UPDATE USER

function addServiceLine() {
    $serviceName = (isset($_REQUEST['serviceName']) ? $_REQUEST['serviceName']:'');
	$accountId = Config::get('account')['accountId'];
    if ($serviceName) {
		$svcDb = Config::get('db')->get_results("SELECT * from `productiveCategory` WHERE (`accountId`={$accountId} OR `accountId`=0) AND `categoryName`='{$serviceName}'");
		
		if(!$svcDb){
		$data = array(
            'categoryName' => $serviceName,
            'accountId' => $accountId
        );
        Config::get('db')->insert('productiveCategory',$data);
		
		$newSvc = Config::get('db')->get_results("SELECT * from `productiveCategory` WHERE `accountId`={$accountId} AND `categoryName`='{$serviceName}'");
		$serviceId = $newSvc[0]['id'];
	
		$serviceArray = array(
		'serviceNum' => $serviceId,
		'message' => true,
		'serviceName' => $serviceName
		);
        echo json_encode(array('success'=>true,'data'=>$serviceArray));
        return true;
    }else{
		$duplicateArray = array(
		'message' => false
		);
		
	 echo json_encode(array('success'=>true,'data'=>$duplicateArray));
    return true;		
	}
}else{
	echo json_encode(array('success'=>true,'message'=>'No Service Added'));
    return false;
	}
}

/////////////////////////////////////
// UPDATE DATA

function updatedata() {
    $dept = (isset($_REQUEST['deptId']) ? $_REQUEST['deptId']:'');
	$startDate = (isset($_REQUEST['startDate']) ? $_REQUEST['startDate']:'');
	$endDate = (isset($_REQUEST['endDate']) ? $_REQUEST['endDate']:'');
	$hppd = (isset($_REQUEST['uosValue']) ? $_REQUEST['uosValue']:'');
	$accountId = Config::get('account')['accountId'];
    
		$dbData = Config::get('db')->get_results("SELECT n.*, a.productivityPosNeg, a.displayProd, s.shifthrs, (select count(`dept`) from `productiveAccountXref` where `type`<2 AND `whpPlan` !=1 and `dept`={$dept}) as shiftCount, d.rnThreshold, d.skillbudget1, d.skillbudget2, d.skillbudget3, d.skillbudget4, d.skillbudget5, d.prodMeasure, d.adjustment, d.rnVariance, d.indMeasure, d.budgetMeasure, d.customWHP2value, d.customWHP3value, d.skill1calc, d.skill2calc, d.skill3calc, d.skill4calc, d.skill5calc, d.skill6calc, d.skill7calc, d.skill8calc, d.skill9calc, d.skill10calc from `productiveNewData` n LEFT JOIN `ProductiveDept` as d on d.id=n.deptId LEFT JOIN `productiveAccount` as a on a.id=n.accountId LEFT JOIN `productiveAccountXref` as s on s.dept = n.deptId and s.shift=n.shift WHERE n.accountId={$accountId} AND n.deptId={$dept} AND n.dayDate >='{$startDate}' AND n.dayDate<='{$endDate}' AND n.userId>0 AND n.type<2");
		
		if($dbData){
		foreach($dbData as $data){
		//$actualWHP = floatval($data['actualWHP']);
		$dataId = intval($data['id']);
		$userId = intval($data['userId']);
		$adjustment = floatval($data['adjustment']);
		$deptId = $data['deptId'];
		$day = $data['dayDate'];
		$prodMeasure = intval($data['prodMeasure']);
		$indMeasure = intval($data['indMeasure']);
		$type = intval($data['type']);
		$budgetMeasure = intval($data['budgetMeasure']);
		$customWHP2value = floatval($data['customWHP2value']);
		$customWHP3value = floatval($data['customWHP3value']);
		$procedures = floatval($data['procedureCount']);
		$customVal = floatval($data['whpCustom']);
		$customVal2 = floatval($data['whpCustom2']);
		$customVal3 = floatval($data['whpCustom3']);
		
		$procedureCount = $procedures + $customVal + ($customWHP2value * $customVal2) + ($customWHP3value * $customVal3);
		
		$plannedVal = intval($data['whpPlan']);
		$shift = intval($data['shift']);
		$shifthrs = floatval($data['shifthrs']);
		$dayDate = $data['dayDate'];
		$skillbudget1 = floatval($data['skillbudget1']);
		$skillbudget2 = floatval($data['skillbudget2']);
		$skillbudget3 = floatval($data['skillbudget3']);
		$skillbudget4 = floatval($data['skillbudget4']);
		$skillbudget5 = floatval($data['skillbudget5']);
		
		//$displayProd = intval($data['displayProd']);
		$displayProd = intval($data['rnVariance']);
		$rnThresholdHigh = intval($data['rnThreshold']);
		$rnThresholdLow = $rnThresholdHigh * -1;
		$prodCalc = intval($data['productivityPosNeg']);
		$shiftCount = intval($data['shiftCount']);
		$type = intval($data['type']);
		
		$skillval1 = floatval($data['skill1val']);
		$skillval2 = floatval($data['skill2val']);
		$skillval3 = floatval($data['skill3val']);
		$skillval4 = floatval($data['skill4val']);
		$skillval5 = floatval($data['skill5val']);
		$skillval6 = floatval($data['skill6val']);
		$skillval7 = floatval($data['skill7val']);
		$skillval8 = floatval($data['skill8val']);
		$skillval9 = floatval($data['skill9val']);
		$skillval10 = floatval($data['skill10val']);
		$skill1calc = floatval($data['skill1calc']);
		$skill2calc = floatval($data['skill2calc']);
		$skill3calc = floatval($data['skill3calc']);
		$skill4calc = floatval($data['skill4calc']);
		$skill5calc = floatval($data['skill5calc']);
		$skill6calc = floatval($data['skill6calc']);
		$skill7calc = floatval($data['skill7calc']);
		$skill8calc = floatval($data['skill8calc']);
		$skill9calc = floatval($data['skill9calc']);
		$skill10calc = floatval($data['skill10calc']);
		
		if($shiftCount<=0){
		$shiftCount =1;
		}
		
		if($budgetMeasure ==1 && $hppd>0){
			$budgetAdd=0;
			//$budgetHours = (($skillval1 * $skillbudget1) + ($skillval2 * $skillbudget2) + ($skillval3 * $skillbudget3) + ($skillval4 * $skillbudget4) + ($skill1val5 * $skillbudget5));
			//$budgetUOS = $uosValue * $procedureCount;
		
			$dbResource = Config::get('db')->get_results("select p.id, p.deptId, p.resourcevalue, sum(p.Sun) as SunTotal, sum(p.Mon) as MonTotal, sum(p.Tue) as TueTotal, sum(p.Wed) as WedTotal, sum(p.Thu) as ThuTotal, sum(p.Fri) as FriTotal, sum(p.Sat) as SatTotal, DAYOFWEEK('{$day}') as day FROM `productiveResources` p where p.deptId={$deptId} group by `position`");
			
				if($dbResource){
				$resDay = $dbResource[0]['day'];
				
				foreach($dbResource as $budgetresource){
				switch ($resDay) {
				case "1":
					$addBudget = floatval($budgetresource['SunTotal']) * floatval($budgetresource['resourcevalue']);
					break;
				case "2":
					$addBudget = floatval($budgetresource['MonTotal']) * floatval($budgetresource['resourcevalue']);
					break;
				case "3":
					$addBudget = floatval($budgetresource['TueTotal']) * floatval($budgetresource['resourcevalue']);
					break;
				case "4":
					$addBudget = floatval($budgetresource['WedTotal']) * floatval($budgetresource['resourcevalue']);
					break;
				case "5":
					$addBudget = floatval($budgetresource['ThuTotal']) * floatval($budgetresource['resourcevalue']);
					break;
				case "6":
					$addBudget = floatval($budgetresource['FriTotal']) * floatval($budgetresource['resourcevalue']);
					break;
				case "7":
					$addBudget = floatval($budgetresource['SatTotal']) * floatval($budgetresource['resourcevalue']);
					break;
				default:
					$addBudget = 0.00;
				}	
					$budgetAdd = $budgetAdd + $addBudget;
				}		
				$budgetHours = (($skillval1 * $skillbudget1) + ($skillval2 * $skillbudget2) + ($skillval3 * $skillbudget3) + ($skillval4 * $skillbudget4) + ($skill1val5 * $skillbudget5) + $budgetAdd);
				//$budgetUOS = $uosValue * $procedureCount;
				$budgetTotal = ROUND((($budgetHours/$uosValue)*100),1);	
				}else{
				$budgetTotal = 0.0;;
				}
			}else{
				$budgetTotal = 0.0;
			}
				
		$dbAddResource = Config::get('db')->get_results("select p.id, p.deptId, IFNULL(sum(p.Sun),0) as SunTotal, IFNULL(sum(p.Mon),0) as MonTotal, IFNULL(sum(p.Tue),0) as TueTotal, IFNULL(sum(p.Wed),0) as WedTotal, IFNULL(sum(p.Thu),0) as ThuTotal, IFNULL(sum(p.Fri),0) as FriTotal, IFNULL(sum(p.Sat),0) as SatTotal, DAYOFWEEK('{$day}') as day FROM `productiveResources` p where p.deptId={$deptId}");
			
		if(intval($dbAddResource[0]['deptId'])>0){
		$dbDay = $dbAddResource[0]['day'];
		
		switch ($dbDay) {
		case "1":
			$addHours = floatval($dbAddResource[0]['SunTotal']);
			break;
		case "2":
			$addHours = floatval($dbAddResource[0]['MonTotal']);
			break;
		case "3":
			$addHours = floatval($dbAddResource[0]['TueTotal']);
			break;
		case "4":
			$addHours = floatval($dbAddResource[0]['WedTotal']);
			break;
		case "5":
			$addHours = floatval($dbAddResource[0]['ThuTotal']);
			break;
		case "6":
			$addHours = floatval($dbAddResource[0]['FriTotal']);
			break;
		case "7":
			$addHours = floatval($dbAddResource[0]['SatTotal']);
			break;
		default:
			$addHours = 0;
		}
		}else{
		$addHours = 0;
		}
		
	$nurseAdd = $addHours / $shiftCount;
	
	$totHours = ((($skillval1 * $skill1calc) + ($skillval2 * $skill2calc) + ($skillval3 * $skill3calc) + ($skillval4 * $skill4calc) + ($skillval5 * $skill5calc) + ($skillval6 * $skill6calc) + ($skillval7 * $skill7calc) + ($skillval8 * $skill8calc) + ($skillval9 * $skill9calc) + ($skillval10 * $skill10calc))*$shifthrs) + $nurseAdd;
	
	//$totHours = (($skillval1 + $skillval2 + $skillval3 + $skillval4 + $skillval5 + $skillval6 + $skillval7 + $skillval8 + $skillval9 + $skillval10)*$shifthrs) + $nurseAdd;
	if($totHours ==0){
		$totalHours=$hppd;
	}else{
		$totalHours=$totHours;
	}
				
	if($procedureCount >0){
	$actualWHP = round($totalHours / $procedureCount,3);
	$aprod = ($hppd / $actualWHP) * 100;
	$actualHoursVariance = round((($hppd * $procedureCount) - $totalHours) * $prodCalc,2);
	}else if($procedureCount==0 && $totHours>0){
	$actualWHP = round($totalHours,3);
	$aprod = ($hppd / $actualWHP) * 100;
	$actualHoursVariance = round((0 - $totalHours) * $prodCalc,2);
	}else{
	$actualWHP = 0;
	$aprod =100;
	$actualHoursVariance =0;
	}
	$aproductivity = round($aprod,1);
	$nvariance = $actualHoursVariance;
	
			
	if ($procedureCount>=0) {
			//$now = time()-21600;		
		    $data = array(
                'hrsVariance' => $nvariance,
				'actualWHP' => $actualWHP,
				'nproductivity' => $aproductivity
            );
			$updateWhere = array('id' => $dataId);
            
			$newProd = Config::get('db')->update('productiveNewData',$data,$updateWhere);
			
		}else{
			
			$data = array(
                'hrsVariance' => $nvariance,
				'actualWHP' => $actualWHP,
				'nproductivity' => $aproductivity
            );
			$updateWhere = array('id' => $dataId);
            
			$newProd = Config::get('db')->update('productiveNewData',$data,$updateWhere);
			
		}
		$updatedept = array(
                'hppd' => $hppd
            );
			$wheredept = array('id' => $dept);
            
			$newUpdate = Config::get('db')->update('ProductiveDept',$updatedept,$wheredept);
		
		}	
		echo json_encode(array('success'=>true,'message'=>true));
		return true;
	
	}else{
	echo json_encode(array('success'=>true,'message'=>false));
    return false;
	}
}

/////////////////////////////////////
// UPDATE ACCOUNT
function UpdateAccount() {
    $accountId = (isset($_REQUEST['accountId'])?$_REQUEST['accountId']:'');
	$enterpriseId = (isset($_REQUEST['enterpriseId'])?$_REQUEST['enterpriseId']:'');
	$address = (isset($_REQUEST['address'])?$_REQUEST['address']:'');
    $city = (isset($_REQUEST['city'])?$_REQUEST['city']:'');
    $state = (isset($_REQUEST['state'])?$_REQUEST['state']:'');
	$zip = (isset($_REQUEST['zip'])?$_REQUEST['zip']:'');
	$contactName = (isset($_REQUEST['contactName'])) ? $_REQUEST['contactName'] :'';
	$contactEmail = (isset($_REQUEST['contactEmail'])) ? $_REQUEST['contactEmail'] : '';
	$contactPhone = (isset($_REQUEST['contactPhone'])) ? $_REQUEST['contactPhone'] : '';
	$payPeriod = (isset($_REQUEST['payPeriod'])) ? $_REQUEST['payPeriod'] : 0;
	$payFirst = (isset($_REQUEST['payFirst'])) ? $_REQUEST['payFirst'] : date('yyyy-mm-dd');
	//$payLast = (isset($_REQUEST['payLast'])) ? $_REQUEST['payLast'] : date('yyyy-mm-dd');
	$label = (isset($_REQUEST['accountLabel'])) ? $_REQUEST['accountLabel'] : 1;
	$image = (isset($_REQUEST['accountImage'])) ? $_REQUEST['accountImage'] : '';
	$dashColor = (isset($_REQUEST['dashColor'])) ? $_REQUEST['dashColor'] : 1;
	$prodCalc = (isset($_REQUEST['prodCalc'])) ? $_REQUEST['prodCalc'] : -1;

    if ($accountId) {
		$data = array(
            'accountAddress' => $address,
            'accountCity' => $city,
			'accountState' => $state,
			'accountZip' => $zip,
			'contactName' => $contactName,
			'contactEmail' => $contactEmail,
			'contactPhone' => $contactPhone,
			'payPeriod' => $payPeriod,
			'payPeriodFirst' => $payFirst,
			'image' => $image,
			'label' => $label,
			'enterpriseId' => $enterpriseId,
			'prodIndicator' => $dashColor,
			'productivityPosNeg' => $prodCalc
        );
        $where = array('id'=>$accountId);
        Config::get('db')->update('productiveAccount',$data,$where);
        echo json_encode(array('success'=>true,'message'=>'Account Updated'));
        return true;
		}else{
	 echo json_encode(array('success'=>false,'message'=>'Unit Failed to Update'));
    return false;		
	}
   
}


/////////////////////////////////////
// UPDATE UNIT MANAGER
function UpdateUnitMgr() {
	$userId = Config::get('account')['id'];
    $unitId = (isset($_REQUEST['deptId'])?$_REQUEST['deptId']:'');
	$active = (isset($_REQUEST['active'])?$_REQUEST['active']:1);
	$unitCategory = (isset($_REQUEST['unitCategory'])?$_REQUEST['unitCategory']:0);
	$unitName = (isset($_REQUEST['name'])?$_REQUEST['name']:'');
    $unitTarget = (isset($_REQUEST['unitTarget'])?$_REQUEST['unitTarget']:100);
    $unitNumber = (isset($_REQUEST['unitNumber'])?$_REQUEST['unitNumber']:'');
	$bedCount = (isset($_REQUEST['bedCount'])?$_REQUEST['bedCount']:0);
	$prodMeasure = (isset($_REQUEST['prodMeasure'])) ? $_REQUEST['prodMeasure'] :0;
	$serviceLine = (isset($_REQUEST['serviceLine'])) ? $_REQUEST['serviceLine'] :0;
	$value = (isset($_REQUEST['value'])) ? $_REQUEST['value'] : 0;
	$uosDesc = (isset($_REQUEST['uosDesc'])) ? $_REQUEST['uosDesc'] : '';
	$censusShift = (isset($_REQUEST['censusShift'])) ? $_REQUEST['censusShift'] : 0;
	$shiftsDay = (isset($_REQUEST['shiftsDay'])) ? $_REQUEST['shiftsDay'] : 0;
	$shiftsOrig = (isset($_REQUEST['shiftsOrig'])) ? $_REQUEST['shiftsOrig'] : 0;
	$one2one = (isset($_REQUEST['one2one'])) ? $_REQUEST['one2one'] : 0;
	$desc1 = (isset($_REQUEST['desc1'])) ? $_REQUEST['desc1'] : '';
	$one2two = (isset($_REQUEST['one2two'])) ? $_REQUEST['one2two'] : 0;
	$desc2 = (isset($_REQUEST['desc2'])) ? $_REQUEST['desc2'] : '';
	$one2three = (isset($_REQUEST['one2three'])) ? $_REQUEST['one2three'] : 0;
	$desc3 = (isset($_REQUEST['desc3'])) ? $_REQUEST['desc3'] : '';
	$one2four = (isset($_REQUEST['one2four'])) ? $_REQUEST['one2four'] : 0;
	$desc4 = (isset($_REQUEST['desc4'])) ? $_REQUEST['desc4'] : '';
	$one2five = (isset($_REQUEST['one2five'])) ? $_REQUEST['one2five'] : 0;
	$desc5 = (isset($_REQUEST['desc5'])) ? $_REQUEST['desc5'] : '';
	$one2six = (isset($_REQUEST['one2six'])) ? $_REQUEST['one2six'] : 0;
	$desc6 = (isset($_REQUEST['desc6'])) ? $_REQUEST['desc6'] : '';
	$newMgr = (isset($_REQUEST['newMgr'])) ? $_REQUEST['newMgr'] : 0;
	$newDir = (isset($_REQUEST['newDir'])) ? $_REQUEST['newDir'] : 0;
	$escalations = (isset($_REQUEST['escalations'])) ? $_REQUEST['escalations'] : 0;
	$mgrOrig = (isset($_REQUEST['mgrOrig'])) ? $_REQUEST['mgrOrig'] : 0;
	$dirOrig = (isset($_REQUEST['dirOrig'])) ? $_REQUEST['dirOrig'] : 0;
	$skill1 = (isset($_REQUEST['skill1'])) ? $_REQUEST['skill1'] : 0;
	$skilldesc1 = (isset($_REQUEST['skilldesc1'])) ? $_REQUEST['skilldesc1'] : '';
	$skill2 = (isset($_REQUEST['skill2'])) ? $_REQUEST['skill2'] : 0;
	$skilldesc2 = (isset($_REQUEST['skilldesc2'])) ? $_REQUEST['skilldesc2'] : '';
	$skill3 = (isset($_REQUEST['skill3'])) ? $_REQUEST['skill3'] : 0;
	$skilldesc3 = (isset($_REQUEST['skilldesc3'])) ? $_REQUEST['skilldesc3'] : '';
	$skill4 = (isset($_REQUEST['skill4'])) ? $_REQUEST['skill4'] : 0;
	$skilldesc4 = (isset($_REQUEST['skilldesc4'])) ? $_REQUEST['skilldesc4'] : '';
	$skill5 = (isset($_REQUEST['skill5'])) ? $_REQUEST['skill5'] : 0;
	$skilldesc5 = (isset($_REQUEST['skilldesc5'])) ? $_REQUEST['skilldesc5'] : '';
	$skill6 = (isset($_REQUEST['skill6'])) ? $_REQUEST['skill6'] : 0;
	$skilldesc6 = (isset($_REQUEST['skilldesc6'])) ? $_REQUEST['skilldesc6'] : '';
	$skill7 = (isset($_REQUEST['skill7'])) ? $_REQUEST['skill7'] : 0;
	$skilldesc7 = (isset($_REQUEST['skilldesc7'])) ? $_REQUEST['skilldesc7'] : '';
	$skill8 = (isset($_REQUEST['skill8'])) ? $_REQUEST['skill8'] : 0;
	$skilldesc8 = (isset($_REQUEST['skilldesc8'])) ? $_REQUEST['skilldesc8'] : '';
	
	$uos1 = (isset($_REQUEST['uos1'])) ? $_REQUEST['uos1'] : 0;
	$uosdesc1 = (isset($_REQUEST['uosdesc1'])) ? $_REQUEST['uosdesc1'] : '';
	$uos2 = (isset($_REQUEST['uos2'])) ? $_REQUEST['uos2'] : 0;
	$uosdesc2 = (isset($_REQUEST['uosdesc2'])) ? $_REQUEST['uosdesc2'] : '';
	$uos3 = (isset($_REQUEST['uos3'])) ? $_REQUEST['uos3'] : 0;
	$uosdesc3 = (isset($_REQUEST['uosdesc3'])) ? $_REQUEST['uosdesc3'] : '';
	$uos4 = (isset($_REQUEST['uos4'])) ? $_REQUEST['uos4'] : 0;
	$uosdesc4 = (isset($_REQUEST['uosdesc4'])) ? $_REQUEST['uosdesc4'] : '';
	$thresholdLow = (isset($_REQUEST['thresholdLow'])) ? $_REQUEST['thresholdLow'] : 0;
	$thresholdHigh = (isset($_REQUEST['thresholdHigh'])) ? $_REQUEST['thresholdHigh'] : 0;
	$churn = (isset($_REQUEST['churn'])) ? $_REQUEST['churn'] : 0;
	$grid = (isset($_REQUEST['grid'])) ? $_REQUEST['grid'] : 0;
	$charge = (isset($_REQUEST['charge'])) ? $_REQUEST['charge'] : 0;
	$budgetMeasure = (isset($_REQUEST['budgetMeasure'])) ? $_REQUEST['budgetMeasure'] : 0;
	$budgetValue = (isset($_REQUEST['budgetValue'])) ? $_REQUEST['budgetValue'] : 0;
	$skillbudget1 = (isset($_REQUEST['skillbudget1'])) ? $_REQUEST['skillbudget1'] : 0;
	$skillbudget2 = (isset($_REQUEST['skillbudget2'])) ? $_REQUEST['skillbudget2'] : 0;
	$skillbudget3 = (isset($_REQUEST['skillbudget3'])) ? $_REQUEST['skillbudget3'] : 0;
	$skillbudget4 = (isset($_REQUEST['skillbudget4'])) ? $_REQUEST['skillbudget4'] : 0;
	$skillbudget5 = (isset($_REQUEST['skillbudget5'])) ? $_REQUEST['skillbudget5'] : 0;
	$accountId = Config::get('account')['accountId'];
	$inshiftProd = (isset($_REQUEST['inshiftProd'])) ? $_REQUEST['inshiftProd'] : 0;
	$rnThreshold = (isset($_REQUEST['rnThreshold'])) ? $_REQUEST['rnThreshold'] : 0;
	$showProd = (isset($_REQUEST['showProd'])) ? $_REQUEST['showProd'] : 0;
	$showPlanned = (isset($_REQUEST['showPlanned'])) ? $_REQUEST['showPlanned'] : 0;
	$avgWage = (isset($_REQUEST['avgWage'])) ? $_REQUEST['avgWage'] : 1;
	$daysWk = (isset($_REQUEST['daysWk'])) ? $_REQUEST['daysWk'] : 7;
	$rndisplay = (isset($_REQUEST['rndisplay'])) ? $_REQUEST['rndisplay'] : 1;
	$countcharge = (isset($_REQUEST['countcharge'])) ? $_REQUEST['countcharge'] : 0;
	$showrn = (isset($_REQUEST['showrn'])) ? $_REQUEST['showrn'] : 0;
	$showrntest = (isset($_REQUEST['showrntest'])) ? $_REQUEST['showrntest'] : '';
	$roundrn = (isset($_REQUEST['roundrn'])) ? $_REQUEST['roundrn'] : 0;
	
	$dc1 = (isset($_REQUEST['dc1'])) ? $_REQUEST['dc1'] : 0;
	$trackdesc1 = (isset($_REQUEST['trackdesc1'])) ? $_REQUEST['trackdesc1'] : '';
	$trackdesc2 = (isset($_REQUEST['trackdesc2'])) ? $_REQUEST['trackdesc2'] : '';
	$trackdesc3 = (isset($_REQUEST['trackdesc3'])) ? $_REQUEST['trackdesc3'] : '';
	$trackdesc4 = (isset($_REQUEST['trackdesc4'])) ? $_REQUEST['trackdesc4'] : '';
	$typetrack1 = (isset($_REQUEST['typetrack1'])) ? $_REQUEST['typetrack1'] : 0;
	$typetrack2 = (isset($_REQUEST['typetrack2'])) ? $_REQUEST['typetrack2'] : 0;
	$typetrack3 = (isset($_REQUEST['typetrack3'])) ? $_REQUEST['typetrack3'] : 0;
	$typetrack4 = (isset($_REQUEST['typetrack4'])) ? $_REQUEST['typetrack4'] : 0;
	$curTime = (isset($_REQUEST['currentTime'])) ? $_REQUEST['currentTime'] : time();
	$viewDate = strtotime($curTime);
	
	$chargeDesc = (isset($_REQUEST['chargeDesc'])) ? $_REQUEST['chargeDesc'] : '';
	$nurseDesc = (isset($_REQUEST['nurseDesc'])) ? $_REQUEST['nurseDesc'] : '';
	$nurse1Desc = (isset($_REQUEST['nurse1Desc'])) ? $_REQUEST['nurse1Desc'] : '';
	$nurse2Desc = (isset($_REQUEST['nurse2Desc'])) ? $_REQUEST['nurse2Desc'] : '';
	$secLabel = (isset($_REQUEST['secLabel'])) ? $_REQUEST['secLabel'] : '';
	$techLabel = (isset($_REQUEST['techLabel'])) ? $_REQUEST['techLabel'] : '';
	$other1Desc = (isset($_REQUEST['other1Desc'])) ? $_REQUEST['other1Desc'] : '';
	$other2Desc = (isset($_REQUEST['other2Desc'])) ? $_REQUEST['other2Desc'] : '';
	$other3Desc = (isset($_REQUEST['other3Desc'])) ? $_REQUEST['other3Desc'] : '';
	$sittersNEWDesc = (isset($_REQUEST['sittersNEWDesc'])) ? $_REQUEST['sittersNEWDesc'] : '';
	
	$uosDefault = (isset($_REQUEST['uosDefault'])) ? $_REQUEST['uosDefault'] : 0;
	
	$chargeRN = (isset($_REQUEST['chargeRN'])) ? $_REQUEST['chargeRN'] : 0;
	$nurseRN = (isset($_REQUEST['nurseRN'])) ? $_REQUEST['nurseRN'] : 0;
	$nurse1RN = (isset($_REQUEST['nurse1RN'])) ? $_REQUEST['nurse1RN'] : 0;
	$nurse2RN = (isset($_REQUEST['nurse2RN'])) ? $_REQUEST['nurse2RN'] : 0;
	$showVisits = (isset($_REQUEST['showVisits'])) ? $_REQUEST['showVisits'] : 0;
	
	$desc7 = (isset($_REQUEST['desc7'])) ? $_REQUEST['desc7'] : '';
	$oneto7 = (isset($_REQUEST['oneto7'])) ? $_REQUEST['oneto7'] : 0;
	
	$desc8 = (isset($_REQUEST['desc8'])) ? $_REQUEST['desc8'] : '';
	$oneto8 = (isset($_REQUEST['oneto8'])) ? $_REQUEST['oneto8'] : 0;
	
	$desc9 = (isset($_REQUEST['desc9'])) ? $_REQUEST['desc9'] : '';
	$oneto9 = (isset($_REQUEST['oneto9'])) ? $_REQUEST['oneto9'] : 0;
	
	$desc10 = (isset($_REQUEST['desc10'])) ? $_REQUEST['desc10'] : '';
	$oneto10 = (isset($_REQUEST['oneto10'])) ? $_REQUEST['oneto10'] : 0;
	
	$actionPlan = (isset($_REQUEST['actionPlan'])) ? addslashes($_REQUEST['actionPlan']) : '';
	
	if(strlen($trackdesc1)>1 || strlen($trackdesc2)>1){
		$trackdata = 1;
	}else{
		$trackdata = 0;
	}
	
	if ($unitId) {
		$data = array(
			'chargeDesc' => $chargeDesc,
			'nurseDesc' => $nurseDesc,
			'nurse1Desc' => $nurse1Desc,
			'nurse2Desc' => $nurse2Desc,
			'secLabel' => $secLabel,
			'techLabel' => $techLabel,
			'other1Desc' => $other1Desc,
			'other2Desc' => $other2Desc,
			'other3Desc' => $other3Desc,
			'sittersNEWDesc' => $sittersNEWDesc,
			'countcharge' => $chargeRN,
			'rnCount' => $nurseRN,
			'rn1Count' => $nurse1RN,
			'rn2Count' => $nurse2RN,
            'target' => $unitTarget,
            'dept' => $unitName,
			'unitId' => $unitNumber,
			'totalBeds' => $bedCount,
			'prodMeasure' => $prodMeasure,
			'prodMeasure1' => $prodMeasure,
			'hppd' => $value,
			'shift' => $censusShift,
			'shiftsperDay' => $shiftsDay,
			'oneto1' => $one2one,
			'desc1' => $desc1,
			'oneto2' => $one2two,
			'desc2' => $desc2,
			'oneto3' => $one2three,
			'desc3' => $desc3,
			'oneto4' => $one2four,
			'desc4' => $desc4,
			'oneto5' => $one2five,
			'desc5' => $desc5,
			'oneto6' => $one2six,
			'desc6' => $desc6,
			'oneto7' => $oneto7,
			'desc7' => $desc7,
			'oneto8' => $oneto8,
			'desc8' => $desc8,
			'oneto9' => $oneto9,
			'desc9' => $desc9,
			'oneto10' => $oneto10,
			'desc10' => $desc10,
			'escalations' => $escalations,
			'category' => $serviceLine,
			'skill1' => $skill1,
			'skilldesc1' => $skilldesc1,
			'skill2' => $skill2,
			'skilldesc2' => $skilldesc2,
			'skill3' => $skill3,
			'skilldesc3' => $skilldesc3,
			'skill4' => $skill4,
			'skilldesc4' => $skilldesc4,
			'skill5' => $skill5,
			'skilldesc5' => $skilldesc5,
			'skill6' => $skill6,
			'skilldesc6' => $skilldesc6,
			'skill7' => $skill7,
			'skilldesc7' => $skilldesc7,
			'skill8' => $skill8,
			'skilldesc8' => $skilldesc8,
			'thresholdLow' => $thresholdLow,
			'thresholdHigh' => $thresholdHigh,
			'budgetMeasure' => $budgetMeasure,
			'uosValue' => $budgetValue,
			'uosDesc' => $uosDesc,
			'churn' => $churn,
			'inshiftProd' => $inshiftProd,
			'rnThreshold' => $rnThreshold,
			'charge' => $charge,
			'showProdWHP' => $showProd,
			'active' => $active,
			'showPlanned' => $showPlanned,
			'avgWage' => $avgWage,
			'crashLog' => $logs,
			'daysWk' => $daysWk,
			'unitCategory' => $unitCategory,
			'trackdc' => $dc1,
			'trackdata' => $trackdata,
			'track1Desc' => $trackdesc1,
			'track2Desc' => $trackdesc2,
			'track3Desc' => $trackdesc3,
			'track4Desc' => $trackdesc4,
			'rnVariance' => $rndisplay,
			'countcharge' => $countcharge,
			'updatedDate' =>date("Y-m-d H:i:s",$viewDate),
			'updatedBy'=>$userId,
			'useGrid'=>$grid,
			'showrn'=>$showrn,
			'showrntest'=>$showrntest,
			'roundrn'=>$roundrn,
			'typetrack1'=>$typetrack1,
			'typetrack2'=>$typetrack2,
			'typetrack3'=>$typetrack3,
			'typetrack4'=>$typetrack4,
			'uosDefault'=>$uosDefault,
			'actionPlan'=>$actionPlan,
			'showVisits'=>$showVisits,
			'customWHP' => $uos1,
			'customDesc' => $uosdesc1,
			'customWHP2' => $uos2,
			'customDesc2' => $uosdesc2,
			'customWHP3' => $uos3,
			'customDesc3' => $uosdesc3,
			'customWHP4' => $uos4,
			'customDesc4' => $uosdesc4
        );
	    $where = array('id'=>$unitId);
        Config::get('db')->update('ProductiveDept',$data,$where);
		
		if (intval($newMgr) != intval($mgrOrig) && intval($newMgr)>0){
			$whereRemove = array(
				'userId'=> $mgrOrig,
				'deptId'=> $unitId
				);
			Config::get('db')->delete('productiveDeptXref',$whereRemove);
			
			$dbMgr = Config::get('db')->get_results("select * FROM `productiveDeptXref` WHERE `deptId`={$unitId} and `userId`={$newMgr}");
			if($dbMgr){
				
				$dataNM = array(
				'primaryUnit'=>6
				);
				$whereW = array(
				'userId'=> $newMgr,
				'deptId'=> $unitId
				);
			Config::get('db')->update('productiveDeptXref',$dataNM,$whereW);
			}else{
				$dataNewM = array(
				'primaryUnit'=>6,
				'userId'=> $newMgr,
				'deptId'=> $unitId
				);
			Config::get('db')->insert('productiveDeptXref',$dataNewM);
				
			}
		}else if(intval($newMgr)<0 && intval($mgrOrig) >0){
				$whereRM = array(
				'userId'=> $mgrOrig,
				'deptId'=> $unitId
				);
			Config::get('db')->delete('productiveDeptXref',$whereRM);
			
		}else{
			//do nothing;
		}
		
		if (intval($newDir) != intval($dirOrig)&& intval($newDir)>0){
			$whereremove = array(
				'userId'=> $dirOrig,
				'deptId'=> $unitId
				);
			Config::get('db')->delete('productiveDeptXref',$whereremove);
			
			$dbDir = Config::get('db')->get_results("select * FROM `productiveDeptXref` WHERE `deptId`={$newDir} and `userId`={$unitId}");
			if($dbDir){
				$dataD = array(
				'primaryUnit'=>7
				);
				$whereD = array(
				'userId'=> $newDir,
				'deptId'=> $unitId
				);
			Config::get('db')->update('productiveDeptXref',$dataD,$whereD);
			}else{
				$dataNewD = array(
				'primaryUnit'=>7,
				'userId'=> $newDir,
				'deptId'=> $unitId
				);
			Config::get('db')->insert('productiveDeptXref',$dataNewD);
				
			}
		}else if(intval($newDir)<0 && intval($dirOrig) >0){
				$whereRD = array(
				'userId'=> $dirOrig,
				'deptId'=> $unitId
				);
			Config::get('db')->delete('productiveDeptXref',$whereRD);
			
		}else{
			//do nothing;
		}
		
		if(intval($shiftsDay) != intval($shiftsOrig) && intval($shiftsDay)>0){
		
		$where = array(
		'dept' => $unitId
		);
		
		$deleteShifts = Config::get('db')->delete('productiveAccountXref',$where);
		
		if(intval($shiftsDay)==2){
			$dbXref = Config::get('db')->get_results("select * FROM `productiveShifts` WHERE `shiftsperDay`=2");
			}else if(intval($shiftsDay)==4){
			$dbXref = Config::get('db')->get_results("select * FROM `productiveShifts` WHERE `shiftsperDay`=4 OR `shiftsperDay`=2 OR `shiftsperDay`=3");	
			}else{
			$dbXref = Config::get('db')->get_results("select * FROM `productiveShifts` WHERE `shiftsperDay`=6 OR `shiftsperDay`=4");	
			}
			
			foreach ($dbXref as $NewXref){
				$shift = $NewXref['id'];
				$value = ROUND((1/intval($shiftsDay)),3);
				$shifthrs = 24 / intval($shiftsDay);
				
			if(intval($shift)==38 && intval($prodMeasure)==2){
				$whpPlan=1;
			}else{
				$whpPlan=0;
			}
			
			$dataNewXref = array(
				'accountId'=>$accountId,
				'dept'=>$unitId,
				'shift'=>$shift,
				'value'=>$value,
				'shifthrs'=>$shifthrs,
				'whpPlan'=>$whpPlan,
				'active'=>1
            );
				
            $newMgr = Config::get('db')->insert('productiveAccountXref',$dataNewXref);
			}
		}else{
			//do nothing;
		}
		
        echo json_encode(array('success'=>true,'message'=>'Unit Updated'));
        return true;
    }else{
	 echo json_encode(array('success'=>false,'message'=>'Unit Failed to Update'));
    return false;		
	}
   
}

/////////////////////////////////////
// ADD NEW USER
function AddAccount() {
    $name = (isset($_REQUEST['name'])) ? $_REQUEST['name'] : '';
	$address = (isset($_REQUEST['address'])) ? $_REQUEST['address'] : '';
	$city = (isset($_REQUEST['city'])) ? $_REQUEST['city'] : '';
	$state = (isset($_REQUEST['state'])) ? $_REQUEST['state'] : '';
	$zip = (isset($_REQUEST['zip'])) ? $_REQUEST['zip'] : '';
	$contactName = (isset($_REQUEST['contactName'])) ? $_REQUEST['contactName'] : '';
	$contactEmail = (isset($_REQUEST['contactEmail'])) ? $_REQUEST['contactEmail'] : '';
	$contactPhone = (isset($_REQUEST['contactPhone'])) ? $_REQUEST['contactPhone'] : '';
	$payPeriod = (isset($_REQUEST['payPeriod'])) ? $_REQUEST['payPeriod'] : 0;
	$payStart = (isset($_REQUEST['payStart'])) ? $_REQUEST['payStart'] : '';
	$label = (isset($_REQUEST['label'])) ? $_REQUEST['label'] : 1;
	$image = (isset($_REQUEST['accountImage'])) ? $_REQUEST['accountImage'] : '';
	
	
    if ($name) {
			$data = array(
				'name'=>$name,
                'status'=>1,
				'image'=>$image,
				'label'=>$label,
				'contactName'=>$contactName,
				'contactEmail'=>$contactEmail,
				'contactPhone'=>$contactPhone,
                'accountAddress'=>$address,
				'accountCity'=>$city,
				'accountState'=>$state,
				'accountZip'=>$zip,
				'payPeriod'=>$payPeriod,
                'payPeriodFirst'=>$payStart,
				'productivityPosNeg' => -1
            );
            $newAcct = Config::get('db')->insert('productiveAccount',$data);
			echo json_encode(array('success'=>true,'message'=>'Account entered successfully.'));
            return true;
	}else{
    echo json_encode(array('success'=>false));
    return false;
	}
}



/*
 
 PROD
  ______  ___  _____  _____
  | ___ \/ _ \|  __ \|  ___|
  | |_/ / /_\ \ |  \/| |__
  |  __/|  _  | | __ |  __|
  | |   | | | | |_\ \| |___
  \_|   \_| |_/\____/\____/


*/

/////////////////////////////////////
// GET PROD INFO
function GetProdTableData() {

    //$storeId = isset($_REQUEST['storeId']) ? $_REQUEST['storeId'] : 0;
    //$date = isset($_REQUEST['day']) ? $_REQUEST['day'] : '';
	$idsearch = isset($_REQUEST['id']) && ($_REQUEST['id'] != 0) ? $_REQUEST['id'] : '';
	$prodStart = isset($_REQUEST['prodStart']) && ($_REQUEST['prodStart'] != '') ? $_REQUEST['prodStart'] : '';
	$prodEnd = isset($_REQUEST['prodEnd']) && ($_REQUEST['prodEnd'] != '') ? $_REQUEST['prodEnd'] : '';
    $user = isset($_REQUEST['user']) ? $_REQUEST['user'] : '';
	$categoryId = isset($_REQUEST['category']) ? intval($_REQUEST['category']) : 0;
    $userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
	$enterpriseId = Config::get('account')['enterpriseId'];
	//$deptId = Config::get('account')['deptid'];
    $role = Config::get('account')['role'];
	$total = 0;
    $variance = 0;
	$now = time()-18000;
	$nowDate = date('m/d/y',$now);
	
	//$pdfTitle = "'Staffing Reports' + '\n' + '" . $startDate . "'";
	//if($prodStart && $prodEnd){
	//	$startDate = $prodStart;
	//	$endDate = $prodEnd;
	//}else{
		$startDate = isset($_REQUEST['start']) ? $_REQUEST['start'] : date('yyyy-mm-dd');
		$endDate = isset($_REQUEST['end']) ? $_REQUEST['end'] : date('yyyy-mm-dd');
	//}
	
	
	if(intval($role)<8){
		$searchAdd = " AND a.unitCategory !=5 AND n.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId` ={$userId}) AND a.active>0";
	}else if(intval($role)>=8 && intval($role)<=10) {
		$searchAdd = " AND a.unitCategory !=5 AND n.accountId={$accountId} AND a.active>0";
	}else if(intval($role)>10 && intval($role)<=12) {
		$searchAdd = " AND a.unitCategory !=5 AND n.accountId={$accountId} AND a.active>0";
	}else if(intval($role)>12 && intval($role)<100) {
		$searchAdd = " AND a.unitCategory !=5 AND t.enterpriseId={$enterpriseId} AND a.active>0";
	}else{
		$searchAdd = " AND a.unitCategory !=5 AND n.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId` ={$userId})";
	}
	
	if($categoryId>0){
		$searchAdd2 = " AND a.category={$categoryId} ";
	}else{
		$searchAdd2 = '';
	}
	
	if($idsearch){
		$searchAdd3 = " AND n.deptId={$idsearch} ";
	}else{
		$searchAdd3 = '';
	}
	
	//if($user && intval($user)>0){
	//	$userAdd = " AND n.userId={$user}";
	//}else{
	//	$userAdd = '';
	//}
	
	if(strtotime($startDate)> strtotime('today UTC')){
		$orderBy = " ORDER BY n.dayDate ASC, a.dept ASC, n.shift DESC";
	}else{
		$orderBy = " ORDER BY n.dayDate DESC, a.dept ASC, n.shift DESC";
	}
	
    $dbData = Config::get('db')->get_results("SELECT n.*, DATE_FORMAT(curdate(),'%Y-%m-%d') as currDate, (CASE WHEN n.dayDate=curdate() THEN 1 ELSE 0 END) as todayDate, n.id as nid, t.enterpriseId, t.name as accountName, t.varMessage, t.prodIndicator, t.displayProd, a.o_Calc, a.dashColor, a.prodMeasure, a.inshiftProd, a.modalPage, a.rnVariance, a.hppd, a.dept, (select count(`dept`) from `productiveAccountXref` where `type`<2 AND `whpPlan` !=1 and `dept`=a.id) as shiftCount, a.thresholdHigh, a.rnThreshold, a.active as activeDept, a.thresholdLow, DATE_FORMAT(n.dayDate,'%m/%d/%y') as newDate, c.id as escId, c.response, IFNULL(c.note,'') as escalationNote, IFNULL(l.escalation,'') as escalationType, IFNULL(c.escalation,0) as escalationId, a.viewPage, s.shift as shiftName, SUBSTRING(u.first_name,1,1) as first_init, u.last_name FROM `productiveNewData` n LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as a on a.id = n.deptId LEFT JOIN `productiveAccount` as t on t.id = a.accountId LEFT OUTER JOIN `productiveUser` as u on u.id = n.userId LEFT OUTER JOIN `productiveEscalations` as c on c.dataId = n.id LEFT OUTER JOIN `productiveAcctEscalations` as l on l.id = c.escalation WHERE a.prodMeasure !=2 AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}'$searchAdd$searchAdd2$searchAdd3 group by n.deptId, n.dayDate, n.shift$orderBy");
    
	
    if($dbData){
		$outJobs = array();
        $jobArray = array();
		
		
		foreach($dbData as $ante){
			$nUser = intval($ante['userId']);
			$qty = intval($ante['ldcount']);
			$antecount = floatval($ante['antecount']);
			$ocount = intval($ante['ocount']);
			$sitters = intval($ante['sittercount']);
			$other1count = intval($ante['otherNurse1']);
			$other2count = intval($ante['otherNurse2']);
			$other3count = intval($ante['otherNurse3']);
			$chargecount = floatval($ante['chargecount']);
			$nurse1 = intval($ante['customNurse']);
			$nurse2 = intval($ante['customNurse2']);
			$atotal = intval($ante['atotal']);
			$patientCount2 = intval($ante['patientCount2']);
			$ltotal = intval($ante['ltotal']);
			$obed = intval($ante['obed']);
			
			$dashColor = intval($ante['prodIndicator']);
			$patients = $atotal + $ltotal + $obed + $patientCount2;
			$totalNurses = $qty + $antecount + $ocount + $nurse1 + $nurse2;
			$totaltechcount = intval($ante['techcount']);
			$seccount = intval($ante['seccount']);
			$otherCount = $sitters + $other1count + $other2count + $other3count + $seccount;
			$totalCount = $chargecount + $totalNurses + $totaltechcount + $otherCount;
			$displayProd = intval($ante['rnVariance']);
			//$displayProd = intval($ante['displayProd']);
			$inshiftProd = intval($ante['inshiftProd']);
			$shiftCount = intval($ante['shiftCount']);
			$activeDept = intval($ante['activeDept']);
			$oCalc = intval($ante['o_Calc']);
						
			$prodMeasure = intval($ante['prodMeasure']);
			$hppd = $ante['hppd'];
			
			
			$whpPlan = intval($ante['whpPlan']);
			$shift = intval($ante['shift']);
			$depName = $ante['dept'];
			$accountName = $ante['accountName'];
			if($nUser>0){
				$userName = "(" . $ante['first_init'] .". " . $ante['last_name'] . ")";
			}else{
				$userName = '';
			}
			if(intval($role)>12){
			$deptName = $accountName . " - " . $depName; // . " " . $userName;	
			}else{
			$deptName = $depName; // . " " . $userName;	
			}
			/*
			if(floatval($ante['nproductivity'])>0 && $inshiftProd==1){
				$currentProductivity = floatval($ante['nproductivity']) . "%";
			}else if(floatval($ante['nproductivity'])>0 && $inshiftProd==2 && intval($ante['todayDate'])==0){
				$currentProductivity = floatval($ante['nproductivity']) . "%";
			}else if(floatval($ante['nproductivity'])>0 && $inshiftProd==2 && intval($ante['todayDate'])>0){
				$currentProductivity = '';
			}else{
				$currentProductivity = '';
			}
			*/
			//$deptName = $depName;
			$deptId = intval($ante['deptId']);
			$date = $ante['newDate'];
			$dayDate = $ante['dayDate'];
			$currDate = $ante['currDate'];
			$shiftName = $date . " - " . $ante['shiftName'];
			$dateshift =  strtotime($date) + intval($shift);
			$thresholdHigh = floatval($ante['thresholdHigh']);
			$thresholdLow = floatval($ante['thresholdLow']);			
			$aprod = floatval($ante['aproductivity']);
			//$modalPage = $ante['modalPage'];
			$escalationType = $ante['escalationType'];
			$escalationNote = $ante['escalationNote'];
			$escalationResponse = $ante['response'];
			$escalationId = intval($ante['escalationId']);
			$escId = intval($ante['escId']);
			$newnote = stripslashes($ante['note']);
			$dataId = intval($ante['nid']);
			$viewPage = $ante['viewPage'];
			$rnThresholdHigh = intval($ante['rnThreshold']);
			$rnThresholdLow = $rnThresholdHigh * -1;
			$hoursThresholdHigh = ($rnThresholdHigh * 24) / $shiftCount;
			$hoursThresholdLow = (($rnThresholdHigh * 24) / $shiftCount) * -1;
			
			if($displayProd==1){
				$hours = " hrs";
				$nvariance = floatval($ante['hrsVariance']) . $hours;
			}else if($displayProd==0){
				$hours = " RNs";
				$nvariance = floatval($ante['nvariance']) . $hours;
			}else if($displayProd==3){
				$hours = " RNs";
				$nvariance = floatval($ante['gridrnvariance']) . $hours;
			}else{
				$hours = '';
				$nvariance = floatval($ante['gvariance']) . $hours;	
			}
			
			//$nvariance = floatval($ante['avariance']) . $hours;
			/*
			if($displayProd==0){
				$nvariance = round(floatval($ante['nvariance']),0);
			}else if($displayProd==2){
				$nvariance = round(floatval($ante['gvariance']),0);
			}else if(floatval($ante['hrsVarianceActual'])==0.000 && floatval($ante['hrsVariance'])==0.000){
				$nvariance = '';
			}else if(floatval($ante['hrsVarianceActual'])>0.000 || floatval($ante['hrsVarianceActual'])<0.000){
				$nvariance = round(floatval($ante['hrsVarianceActual']),2) . $hours;
			}else if(floatval($ante['hrsVarianceActual'])==0.000 && (floatval($ante['hrsVariance'])>0.000 || floatval($ante['hrsVariance'])<0.000)){
				$nvariance = round(floatval($ante['hrsVariance']),2);
			}else{
				$nvariance = round(floatval($ante['nvariance']),0);
			}
			*/
			
			if($aprod > 0){
				$aproductivity = $aprod . "%";
			}else{
				$aproductivity ='';
			}
			
			
		if($nUser==0){
				/*
				if((intval($role)<8 || intval($role)==11) && strtotime($date) < strtotime($nowDate)){
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . "</a></br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-info btn-sm\" onclick=\"tj.editNEW(" . $dataId . "); \" disabled >Edit</button><button type=\"button\" id=\"addVisits\" class=\"btn btn-success btn-sm\" onclick=\"tj.addvisits(" . $dataId . "); \" hidden>Prod</button>";
				}else if(intval($role)<8 || intval($role)==11){
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . "</a></br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-info btn-sm\" onclick=\"tj.editNEW(" . $dataId . "); \">Edit</button>";
				}else if(intval($role)>7 && intval($role)!=11){
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . " </a>";
				}else{
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . "</a></br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-info btn-sm\" onclick=\"tj.editNEW(" . $dataId . "); \">Edit</button>";
				}
				
				*/
				if((intval($role)==11 || intval($role)==7) && $prodMeasure !=2 && strtotime($date) < strtotime($nowDate) && intval($shift)==52 && $oCalc>0){
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . "</a></br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-info btn-sm\" onclick=\"tj.editNEW(" . $dataId . "); \">Edit</button><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-danger btn-sm\" onclick=\"tj.clearRecord(" . $dataId . "); \">Del</button><button type=\"button\" id=\"addVisits\" class=\"btn btn-success btn-sm\" onclick=\"tj.addvisits(" . $dataId . "); \" >Prod</button>";
				}else if(intval($role)<10 && $prodMeasure !=2 && strtotime($date) < strtotime($nowDate) && $oCalc>0 && intval($shift)==52){
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . "</a></br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-info btn-sm\" onclick=\"tj.editNEW(" . $dataId . "); \" >Edit</button><button type=\"button\" id=\"addVisits\" class=\"btn btn-success btn-sm\" onclick=\"tj.addvisits(" . $dataId . "); \" >Prod</button>";
				}else if(intval($role)<10 && $prodMeasure !=2 && strtotime($date) < strtotime($nowDate)){
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . "</a></br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-info btn-sm\" onclick=\"tj.editNEW(" . $dataId . "); \" >Edit</button>";
				}else if(intval($role)==11 || intval($role)==7){
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . "</a></br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-info btn-sm\" onclick=\"tj.editNEW(" . $dataId . "); \">Edit</button><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-danger btn-sm\" onclick=\"tj.clearRecord(" . $dataId . "); \">Del</button>";
				}else if(intval($role)<10){
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . "</a></br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-info btn-sm\" onclick=\"tj.editNEW(" . $dataId . "); \">Edit</button>";
				}else{
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . " </a>";
				}
				
				
				$style="0";
				$note=$newnote;

			
			$nvarianceNull = '';
			$aprodNull = '';
			$nurseNull = '';
			$techNull = '';
			$secNull = '';
			$patientsNull = '';
			$chargeNull = '';
			$otherNull = '';
			$totalNull = '';
			
			if($nvariance>0 || $nvariance <0){
				$nvarianceNew = $nvariance;
			}else{
				$nvarianceNew = '';
			
			}
				$jobArray[] = array(
					'unit' => $deptName,
					'shift' => $shiftDisplay,
					'variance' => $nvarianceNew,
					'patients' => $patientsNull,
					'total' => $totalNull,
					'charge' => $chargeNull,
					'nursecount' => $nurseNull,
					'techs' => $techNull,
					'other' => $otherNull,
					'note' => '',
					'shiftnum' => $dateshift,
					'escalationType' => '',
					'escalationId' => '',
					'style' => $style,
					'nowDate' => $nowDate,
					'nowDayDate' => $date
					
				);
			
			}else{
				/*
				if((intval($role)==11 && $prodMeasure !=2 && intval($shift)!=52) || (strtotime($date) == strtotime($nowDate) && intval($role)>5 && intval($role)!=10)){
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . "</a></br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-info btn-sm\" onclick=\"tj.editNEW(" . $dataId . "); \">Edit</button><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-danger btn-sm\" onclick=\"tj.clearRecord(" . $dataId . "); \">Del</button>";
				}else if(intval($role)==11 && $prodMeasure !=2 && strtotime($date) < strtotime($nowDate) && $oCalc>0 && (intval($shift)==52 || intval($shift)==34)){
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . "</a></br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-info btn-sm\" onclick=\"tj.editNEW(" . $dataId . "); \">Edit</button><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-danger btn-sm\" onclick=\"tj.clearRecord(" . $dataId . "); \">Del</button><button type=\"button\" id=\"addVisits\" class=\"btn btn-success btn-sm\" onclick=\"tj.addvisits(" . $dataId . "); \" hidden>Prod</button>";
				}else if(intval($role)==11){
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . "</a></br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-info btn-sm\" onclick=\"tj.editNEW(" . $dataId . "); \">Edit</button><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-danger btn-sm\" onclick=\"tj.clearRecord(" . $dataId . "); \">Del</button>";
				}else if(intval($role)<8 && $prodMeasure !=2 && strtotime($date) < strtotime($nowDate) && $oCalc>0 && (intval($shift)==52 || intval($shift)==34)){
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . "</a></br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-info btn-sm\" onclick=\"tj.editNEW(" . $dataId . "); \">Edit</button><button type=\"button\" id=\"addVisits\" class=\"btn btn-success btn-sm\" onclick=\"tj.addvisits(" . $dataId . "); \" hidden>Prod</button>";
				}else if(intval($role)<8){
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . "</a></br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-info btn-sm\" onclick=\"tj.editNEW(" . $dataId . "); \">Edit</button>";
				}else if(intval($role)>7 && intval($role)!=11){
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . " </a>";
				}else{
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . "</a></br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-info btn-sm\" onclick=\"tj.editNEW(" . $dataId . "); \">Edit</button>";
				}
				*/
				
				if((intval($role)==11 || intval($role)==7) && $prodMeasure !=2 && strtotime($date) < strtotime($nowDate) && intval($shift)==52 && $oCalc>0){
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . "</a></br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-info btn-sm\" onclick=\"tj.editNEW(" . $dataId . "); \">Edit</button><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-danger btn-sm\" onclick=\"tj.clearRecord(" . $dataId . "); \">Del</button><button type=\"button\" id=\"addVisits\" class=\"btn btn-success btn-sm\" onclick=\"tj.addvisits(" . $dataId . "); \" >Prod</button>";
				}else if(intval($role)<10 && $prodMeasure !=2 && strtotime($date) < strtotime($nowDate) && $oCalc>0 && intval($shift)==52){
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . "</a></br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-info btn-sm\" onclick=\"tj.editNEW(" . $dataId . "); \">Edit</button><button type=\"button\" id=\"addVisits\" class=\"btn btn-success btn-sm\" onclick=\"tj.addvisits(" . $dataId . "); \" >Prod</button>";
				}else if(intval($role)<10 && $prodMeasure !=2 && strtotime($date) < strtotime($nowDate)){
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . "</a></br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-info btn-sm\" onclick=\"tj.editNEW(" . $dataId . "); \">Edit</button>";
				}else if(intval($role)==11 || intval($role)==7){
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . "</a></br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-info btn-sm\" onclick=\"tj.editNEW(" . $dataId . "); \">Edit</button><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-danger btn-sm\" onclick=\"tj.clearRecord(" . $dataId . "); \">Del</button>";
				}else if(intval($role)<10){
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . "</a></br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-info btn-sm\" onclick=\"tj.editNEW(" . $dataId . "); \">Edit</button>";
				}else{
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . " </a>";
				}
				
								
				if($escalationId >=1){
				$style = "1";
				$note = $newnote. "<a href=\"javascript:;\" onclick=\"tj.getEscalation(" . $escId . ");\"> ESCALATION: " . strtoupper($escalationType) . "</a> - " . $escalationNote . " " . $escalationResponse;
				}else if($displayProd==0 && $dashColor==0 && $aprod>0 && (($nvariance>=$rnThresholdHigh) || ($nvariance<=$rnThresholdLow))){
				$style = "1";
				$note = $newnote;
				}else if($displayProd>0 && $dashColor==0 && $aprod>0 && (($aprod>=$thresholdHigh) || ($aprod<=$thresholdLow))){
				$style = "1";
				$note = $newnote;
				}else{
				$style = "0";
				$note = $newnote;
				}
				
				/*
				else if($prodMeasure!=4 && (($aprod >0 && $thresholdHigh >0 && $nUser>0 && (($aprod > $thresholdHigh && $dashColor>=1) || ($aprod < $thresholdLow && $dashColor<=1))) || ($nvariance>=$rnThresholdHigh && $dashColor<=1 && $displayProd==0) || ($nvariance<=$rnThresholdLow && $dashColor>=1 && $displayProd==0))){
				$style = "1";
				$note = $newnote;
				}else if($dashColor>=1 && $prodMeasure!=4 && (($aprod >0 && $thresholdHigh >0 && $aprod <=$thresholdHigh && $aprod >=$thresholdLow && $nUser>0) || ($nvariance>$rnThresholdLow && $nvariance<$rnThresholdHigh && $nUser>0 && $displayProd==0))){
				$style = "2";
				$note = $newnote;
				}else if($prodMeasure==4 && (($rnvariance >=$rnThresholdHigh && $dashColor<=1) || ($rnvariance <= $rnThresholdLow && $dashColor>=1))){
				$style = "1";
				$note = $newnote;
				}else if($dashColor>=1 && $prodMeasure!=4 && $nvariance <$rnThresholdHigh && $nvariance >$rnThresholdLow && $nUser>0){
				$style = "2";
				$note = $newnote;
				}else if($dashColor>=1 && $prodMeasure==4 && $rnvariance <$rnThresholdHigh && $rnvariance >$rnThresholdLow && $nUser>0){
				$style = "2";
				$note = $newnote;
				}else{
				$style = "0";
				$note = $newnote;
				}
				*/
				
				//if(intval($deptId)==1){
				//	$style= "0";
				//	$shiftDisplay = "<a href=\"view.php?u=" . $dataId . "\">" . $shiftName . "</a><a href=\"javascript:;\" onclick=\"" . $modalPage . "(" . $dataId . ");\"> (Edit)</a>";	
				//}
				
				
				$jobArray[] = array(
					'unit' => $deptName,
					'shift' => $shiftDisplay,
					'variance' => $nvariance,
					'patients' => $patients,
					'total' => $totalCount,
					'charge' => $chargecount,
					'nursecount' => $totalNurses,
					'techs' => $totaltechcount,
					'other' => $otherCount,
					'dashColor' => $dashColor,
					'note' => $note,
					'shiftnum' => $dateshift,
					'escalationType' => $escalationType,
					'escalationId' => $escalationId,
					'style' => $style,
					'nowDate' => $nowDate,
					'nowDayDate' => $date
				);
           
			}
		}
		$outData = array();
		$outData['data'] = $jobArray;
		echo json_encode($outData,JSON_NUMERIC_CHECK);
	
		}else {
        $outData = array();
        $outData['data'] = array();
        echo json_encode($outData,JSON_NUMERIC_CHECK);
		}
}

/////////////////////////////////////
// GET PROD OPS INFO
function GetProdTableDataOPS() {

    //$storeId = isset($_REQUEST['storeId']) ? $_REQUEST['storeId'] : 0;
    //$date = isset($_REQUEST['day']) ? $_REQUEST['day'] : '';
	$idsearch = isset($_REQUEST['id']) && ($_REQUEST['id'] != 0) ? $_REQUEST['id'] : '';
	$prodStart = isset($_REQUEST['prodStart']) && ($_REQUEST['prodStart'] != '') ? $_REQUEST['prodStart'] : '';
	$prodEnd = isset($_REQUEST['prodEnd']) && ($_REQUEST['prodEnd'] != '') ? $_REQUEST['prodEnd'] : '';
    $user = isset($_REQUEST['user']) ? $_REQUEST['user'] : '';
	$categoryId = isset($_REQUEST['category']) ? intval($_REQUEST['category']) : 0;
    $userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
	$enterpriseId = Config::get('account')['enterpriseId'];
	//$deptId = Config::get('account')['deptid'];
    $role = Config::get('account')['role'];
	$total = 0;
    $variance = 0;
	$now = time()-18000;
	$nowDate = date('m/d/y',$now);
	
	//$pdfTitle = "'Staffing Reports' + '\n' + '" . $startDate . "'";
	//if($prodStart && $prodEnd){
	//	$startDate = $prodStart;
	//	$endDate = $prodEnd;
	//}else{
		$startDate = isset($_REQUEST['start']) ? $_REQUEST['start'] : date('yyyy-mm-dd');
		$endDate = isset($_REQUEST['end']) ? $_REQUEST['end'] : date('yyyy-mm-dd');
	//}
	
	
	if(intval($role)<8){
		$searchAdd = " AND a.unitCategory=5 AND n.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId` ={$userId}) AND a.active>0";
	}else if(intval($role)>=8 && intval($role)<=10) {
		$searchAdd = " AND a.unitCategory=5 AND n.accountId={$accountId} AND a.active>0";
	}else if(intval($role)>10 && intval($role)<=12) {
		$searchAdd = " AND a.unitCategory=5 AND n.accountId={$accountId} AND a.active>0";
	}else if(intval($role)>12 && intval($role)<100) {
		$searchAdd = " AND a.unitCategory=5 AND t.enterpriseId={$enterpriseId} AND a.active>0";
	}else{
		$searchAdd = " AND a.unitCategory=5 AND n.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId` ={$userId})";
	}
	
	if($categoryId>0){
		$searchAdd2 = " AND a.category={$categoryId} ";
	}else{
		$searchAdd2 = '';
	}
	
	if($idsearch){
		$searchAdd3 = " AND n.deptId={$idsearch} ";
	}else{
		$searchAdd3 = '';
	}
	
	//if($user && intval($user)>0){
	//	$userAdd = " AND n.userId={$user}";
	//}else{
	//	$userAdd = '';
	//}
	
	if(strtotime($startDate)> strtotime('today UTC')){
		$orderBy = " ORDER BY n.dayDate ASC, a.dept ASC, n.shift DESC";
	}else{
		$orderBy = " ORDER BY n.dayDate DESC, a.dept ASC, n.shift DESC";
	}
	
    $dbData = Config::get('db')->get_results("SELECT n.*, DATE_FORMAT(curdate(),'%Y-%m-%d') as currDate, (CASE WHEN n.dayDate=curdate() THEN 1 ELSE 0 END) as todayDate, n.id as nid, t.enterpriseId, t.name as accountName, t.varMessage, t.prodIndicator, t.displayProd, a.o_Calc, a.dashColor, a.prodMeasure, a.inshiftProd, a.modalPage, a.rnVariance, a.hppd, a.dept, (select count(`dept`) from `productiveAccountXref` where `type`<2 AND `whpPlan` !=1 and `dept`=a.id) as shiftCount, a.thresholdHigh, a.rnThreshold, a.active as activeDept, a.thresholdLow, DATE_FORMAT(n.dayDate,'%m/%d/%y') as newDate, c.id as escId, c.response, IFNULL(c.note,'') as escalationNote, IFNULL(l.escalation,'') as escalationType, IFNULL(c.escalation,0) as escalationId, a.viewPage, s.shift as shiftName, SUBSTRING(u.first_name,1,1) as first_init, u.last_name FROM `productiveNewData` n LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as a on a.id = n.deptId LEFT JOIN `productiveAccount` as t on t.id = a.accountId LEFT OUTER JOIN `productiveUser` as u on u.id = n.userId LEFT OUTER JOIN `productiveEscalations` as c on c.dataId = n.id LEFT OUTER JOIN `productiveAcctEscalations` as l on l.id = c.escalation WHERE a.prodMeasure !=2 AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}'$searchAdd$searchAdd2$searchAdd3 group by n.deptId, n.dayDate, n.shift$orderBy");
    
	
    if($dbData){
		$outJobs = array();
        $jobArray = array();
		
		
		foreach($dbData as $ante){
			$nUser = intval($ante['userId']);
			$qty = intval($ante['ldcount']);
			$antecount = floatval($ante['antecount']);
			$ocount = intval($ante['ocount']);
			$sitters = intval($ante['sittercount']);
			$other1count = intval($ante['otherNurse1']);
			$other2count = intval($ante['otherNurse2']);
			$other3count = intval($ante['otherNurse3']);
			$chargecount = floatval($ante['chargecount']);
			$nurse1 = intval($ante['customNurse']);
			$nurse2 = intval($ante['customNurse2']);
			$atotal = intval($ante['atotal']);
			$patientCount2 = intval($ante['patientCount2']);
			$ltotal = intval($ante['ltotal']);
			$obed = intval($ante['obed']);
			
			$dashColor = intval($ante['prodIndicator']);
			$patients = $atotal + $ltotal + $obed + $patientCount2;
			$totalNurses = $qty + $antecount + $ocount + $nurse1 + $nurse2;
			$totaltechcount = intval($ante['techcount']);
			$seccount = intval($ante['seccount']);
			$otherCount = $sitters + $other1count + $other2count + $other3count + $seccount;
			$totalCount = $chargecount + $totalNurses + $totaltechcount + $otherCount;
			$displayProd = intval($ante['rnVariance']);
			//$displayProd = intval($ante['displayProd']);
			$inshiftProd = intval($ante['inshiftProd']);
			$shiftCount = intval($ante['shiftCount']);
			$activeDept = intval($ante['activeDept']);
			$oCalc = intval($ante['o_Calc']);
						
			$prodMeasure = intval($ante['prodMeasure']);
			$hppd = $ante['hppd'];
			
			
			$whpPlan = intval($ante['whpPlan']);
			$shift = intval($ante['shift']);
			$depName = $ante['dept'];
			$accountName = $ante['accountName'];
			if($nUser>0){
				$userName = "(" . $ante['first_init'] .". " . $ante['last_name'] . ")";
			}else{
				$userName = '';
			}
			if(intval($role)>12){
			$deptName = $accountName . " - " . $depName; // . " " . $userName;	
			}else{
			$deptName = $depName; // . " " . $userName;	
			}
			/*
			if(floatval($ante['nproductivity'])>0 && $inshiftProd==1){
				$currentProductivity = floatval($ante['nproductivity']) . "%";
			}else if(floatval($ante['nproductivity'])>0 && $inshiftProd==2 && intval($ante['todayDate'])==0){
				$currentProductivity = floatval($ante['nproductivity']) . "%";
			}else if(floatval($ante['nproductivity'])>0 && $inshiftProd==2 && intval($ante['todayDate'])>0){
				$currentProductivity = '';
			}else{
				$currentProductivity = '';
			}
			*/
			//$deptName = $depName;
			$deptId = intval($ante['deptId']);
			$date = $ante['newDate'];
			$dayDate = $ante['dayDate'];
			$currDate = $ante['currDate'];
			$shiftName = $date . " - " . $ante['shiftName'];
			$dateshift =  strtotime($date) + intval($shift);
			$thresholdHigh = floatval($ante['thresholdHigh']);
			$thresholdLow = floatval($ante['thresholdLow']);			
			$aprod = floatval($ante['aproductivity']);
			//$modalPage = $ante['modalPage'];
			$escalationType = $ante['escalationType'];
			$escalationNote = $ante['escalationNote'];
			$escalationResponse = $ante['response'];
			$escalationId = intval($ante['escalationId']);
			$escId = intval($ante['escId']);
			$newnote = stripslashes($ante['note']);
			$dataId = intval($ante['nid']);
			$viewPage = $ante['viewPage'];
			$rnThresholdHigh = intval($ante['rnThreshold']);
			$rnThresholdLow = $rnThresholdHigh * -1;
			$hoursThresholdHigh = ($rnThresholdHigh * 24) / $shiftCount;
			$hoursThresholdLow = (($rnThresholdHigh * 24) / $shiftCount) * -1;
			
			if($displayProd==1){
				$hours = " hrs";
				$nvariance = floatval($ante['hrsVariance']) . $hours;
			}else if($displayProd==0){
				$hours = " RNs";
				$nvariance = floatval($ante['nvariance']) . $hours;
			}else if($displayProd==3){
				$hours = " RNs";
				$nvariance = floatval($ante['gridrnvariance']) . $hours;
			}else{
				$hours = '';
				$nvariance = floatval($ante['gvariance']) . $hours;	
			}
			
			//$nvariance = floatval($ante['avariance']) . $hours;
			/*
			if($displayProd==0){
				$nvariance = round(floatval($ante['nvariance']),0);
			}else if($displayProd==2){
				$nvariance = round(floatval($ante['gvariance']),0);
			}else if(floatval($ante['hrsVarianceActual'])==0.000 && floatval($ante['hrsVariance'])==0.000){
				$nvariance = '';
			}else if(floatval($ante['hrsVarianceActual'])>0.000 || floatval($ante['hrsVarianceActual'])<0.000){
				$nvariance = round(floatval($ante['hrsVarianceActual']),2) . $hours;
			}else if(floatval($ante['hrsVarianceActual'])==0.000 && (floatval($ante['hrsVariance'])>0.000 || floatval($ante['hrsVariance'])<0.000)){
				$nvariance = round(floatval($ante['hrsVariance']),2);
			}else{
				$nvariance = round(floatval($ante['nvariance']),0);
			}
			*/
			
			if($aprod > 0){
				$aproductivity = $aprod . "%";
			}else{
				$aproductivity ='';
			}
			
			
		if($nUser==0){
				/*
				if((intval($role)<8 || intval($role)==11) && strtotime($date) < strtotime($nowDate)){
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . "</a></br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-info btn-sm\" onclick=\"tj.editNEW(" . $dataId . "); \" disabled >Edit</button><button type=\"button\" id=\"addVisits\" class=\"btn btn-success btn-sm\" onclick=\"tj.addvisits(" . $dataId . "); \" hidden>Prod</button>";
				}else if(intval($role)<8 || intval($role)==11){
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . "</a></br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-info btn-sm\" onclick=\"tj.editNEW(" . $dataId . "); \">Edit</button>";
				}else if(intval($role)>7 && intval($role)!=11){
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . " </a>";
				}else{
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . "</a></br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-info btn-sm\" onclick=\"tj.editNEW(" . $dataId . "); \">Edit</button>";
				}
				
				*/
				if((intval($role)==11 || intval($role)==7) && $prodMeasure !=2 && strtotime($date) < strtotime($nowDate) && intval($shift)==52 && $oCalc>0){
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . "</a></br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-info btn-sm\" onclick=\"tj.editOPS(" . $dataId . "); \">Edit</button><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-danger btn-sm\" onclick=\"tj.clearRecord(" . $dataId . "); \">Del</button><button type=\"button\" id=\"addVisits\" class=\"btn btn-success btn-sm\" onclick=\"tj.addvisits(" . $dataId . "); \" >Prod</button>";
				}else if(intval($role)<10 && $prodMeasure !=2 && strtotime($date) < strtotime($nowDate) && $oCalc>0 && intval($shift)==52){
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . "</a></br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-info btn-sm\" onclick=\"tj.editOPS(" . $dataId . "); \" >Edit</button><button type=\"button\" id=\"addVisits\" class=\"btn btn-success btn-sm\" onclick=\"tj.addvisits(" . $dataId . "); \" >Prod</button>";
				}else if(intval($role)<10 && $prodMeasure !=2 && strtotime($date) < strtotime($nowDate)){
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . "</a></br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-info btn-sm\" onclick=\"tj.editOPS(" . $dataId . "); \" >Edit</button>";
				}else if(intval($role)==11 || intval($role)==7){
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . "</a></br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-info btn-sm\" onclick=\"tj.editOPS(" . $dataId . "); \">Edit</button><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-danger btn-sm\" onclick=\"tj.clearRecord(" . $dataId . "); \">Del</button>";
				}else if(intval($role)<10){
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . "</a></br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-info btn-sm\" onclick=\"tj.editOPS(" . $dataId . "); \">Edit</button>";
				}else{
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . " </a>";
				}
				
				
				$style="0";
				$note=$newnote;

			
			$nvarianceNull = '';
			$aprodNull = '';
			$nurseNull = '';
			$techNull = '';
			$secNull = '';
			$patientsNull = '';
			$chargeNull = '';
			$otherNull = '';
			$totalNull = '';
			
			if($nvariance>0 || $nvariance <0){
				$nvarianceNew = $nvariance;
			}else{
				$nvarianceNew = '';
			
			}
				$jobArray[] = array(
					'unit' => $deptName,
					'shift' => $shiftDisplay,
					'variance' => $nvarianceNew,
					'patients' => $patientsNull,
					'total' => $totalNull,
					'charge' => $chargeNull,
					'nursecount' => $nurseNull,
					'techs' => $techNull,
					'other' => $otherNull,
					'note' => '',
					'shiftnum' => $dateshift,
					'escalationType' => '',
					'escalationId' => '',
					'style' => $style,
					'nowDate' => $nowDate,
					'nowDayDate' => $date
					
				);
			
			}else{
				/*
				if((intval($role)==11 && $prodMeasure !=2 && intval($shift)!=52) || (strtotime($date) == strtotime($nowDate) && intval($role)>5 && intval($role)!=10)){
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . "</a></br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-info btn-sm\" onclick=\"tj.editNEW(" . $dataId . "); \">Edit</button><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-danger btn-sm\" onclick=\"tj.clearRecord(" . $dataId . "); \">Del</button>";
				}else if(intval($role)==11 && $prodMeasure !=2 && strtotime($date) < strtotime($nowDate) && $oCalc>0 && (intval($shift)==52 || intval($shift)==34)){
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . "</a></br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-info btn-sm\" onclick=\"tj.editNEW(" . $dataId . "); \">Edit</button><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-danger btn-sm\" onclick=\"tj.clearRecord(" . $dataId . "); \">Del</button><button type=\"button\" id=\"addVisits\" class=\"btn btn-success btn-sm\" onclick=\"tj.addvisits(" . $dataId . "); \" hidden>Prod</button>";
				}else if(intval($role)==11){
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . "</a></br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-info btn-sm\" onclick=\"tj.editNEW(" . $dataId . "); \">Edit</button><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-danger btn-sm\" onclick=\"tj.clearRecord(" . $dataId . "); \">Del</button>";
				}else if(intval($role)<8 && $prodMeasure !=2 && strtotime($date) < strtotime($nowDate) && $oCalc>0 && (intval($shift)==52 || intval($shift)==34)){
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . "</a></br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-info btn-sm\" onclick=\"tj.editNEW(" . $dataId . "); \">Edit</button><button type=\"button\" id=\"addVisits\" class=\"btn btn-success btn-sm\" onclick=\"tj.addvisits(" . $dataId . "); \" hidden>Prod</button>";
				}else if(intval($role)<8){
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . "</a></br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-info btn-sm\" onclick=\"tj.editNEW(" . $dataId . "); \">Edit</button>";
				}else if(intval($role)>7 && intval($role)!=11){
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . " </a>";
				}else{
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . "</a></br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-info btn-sm\" onclick=\"tj.editNEW(" . $dataId . "); \">Edit</button>";
				}
				*/
				
				if((intval($role)==11 || intval($role)==7) && $prodMeasure !=2 && strtotime($date) < strtotime($nowDate) && intval($shift)==52 && $oCalc>0){
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . "</a></br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-info btn-sm\" onclick=\"tj.editOPS(" . $dataId . "); \">Edit</button><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-danger btn-sm\" onclick=\"tj.clearopsRecord(" . $dataId . "); \">Del</button><button type=\"button\" id=\"addVisits\" class=\"btn btn-success btn-sm\" onclick=\"tj.addvisits(" . $dataId . "); \" >Prod</button>";
				}else if(intval($role)<10 && $prodMeasure !=2 && strtotime($date) < strtotime($nowDate) && $oCalc>0 && intval($shift)==52){
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . "</a></br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-info btn-sm\" onclick=\"tj.editOPS(" . $dataId . "); \">Edit</button><button type=\"button\" id=\"addVisits\" class=\"btn btn-success btn-sm\" onclick=\"tj.addvisits(" . $dataId . "); \" >Prod</button>";
				}else if(intval($role)<10 && $prodMeasure !=2 && strtotime($date) < strtotime($nowDate)){
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . "</a></br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-info btn-sm\" onclick=\"tj.editOPS(" . $dataId . "); \">Edit</button>";
				}else if(intval($role)==11 || intval($role)==7){
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . "</a></br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-info btn-sm\" onclick=\"tj.editOPS(" . $dataId . "); \">Edit</button><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-danger btn-sm\" onclick=\"tj.clearopsRecord(" . $dataId . "); \">Del</button>";
				}else if(intval($role)<10){
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . "</a></br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-info btn-sm\" onclick=\"tj.editOPS(" . $dataId . "); \">Edit</button>";
				}else{
					$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $shiftName . " </a>";
				}
				
								
				if($escalationId >=1){
				$style = "1";
				$note = $newnote. "<a href=\"javascript:;\" onclick=\"tj.getEscalation(" . $escId . ");\"> ESCALATION: " . strtoupper($escalationType) . "</a> - " . $escalationNote . " " . $escalationResponse;
				}else if($displayProd==0 && $dashColor==0 && $aprod>0 && (($nvariance>=$rnThresholdHigh) || ($nvariance<=$rnThresholdLow))){
				$style = "1";
				$note = $newnote;
				}else if($displayProd>0 && $dashColor==0 && $aprod>0 && (($aprod>=$thresholdHigh) || ($aprod<=$thresholdLow))){
				$style = "1";
				$note = $newnote;
				}else{
				$style = "0";
				$note = $newnote;
				}
				
				/*
				else if($prodMeasure!=4 && (($aprod >0 && $thresholdHigh >0 && $nUser>0 && (($aprod > $thresholdHigh && $dashColor>=1) || ($aprod < $thresholdLow && $dashColor<=1))) || ($nvariance>=$rnThresholdHigh && $dashColor<=1 && $displayProd==0) || ($nvariance<=$rnThresholdLow && $dashColor>=1 && $displayProd==0))){
				$style = "1";
				$note = $newnote;
				}else if($dashColor>=1 && $prodMeasure!=4 && (($aprod >0 && $thresholdHigh >0 && $aprod <=$thresholdHigh && $aprod >=$thresholdLow && $nUser>0) || ($nvariance>$rnThresholdLow && $nvariance<$rnThresholdHigh && $nUser>0 && $displayProd==0))){
				$style = "2";
				$note = $newnote;
				}else if($prodMeasure==4 && (($rnvariance >=$rnThresholdHigh && $dashColor<=1) || ($rnvariance <= $rnThresholdLow && $dashColor>=1))){
				$style = "1";
				$note = $newnote;
				}else if($dashColor>=1 && $prodMeasure!=4 && $nvariance <$rnThresholdHigh && $nvariance >$rnThresholdLow && $nUser>0){
				$style = "2";
				$note = $newnote;
				}else if($dashColor>=1 && $prodMeasure==4 && $rnvariance <$rnThresholdHigh && $rnvariance >$rnThresholdLow && $nUser>0){
				$style = "2";
				$note = $newnote;
				}else{
				$style = "0";
				$note = $newnote;
				}
				*/
				
				//if(intval($deptId)==1){
				//	$style= "0";
				//	$shiftDisplay = "<a href=\"view.php?u=" . $dataId . "\">" . $shiftName . "</a><a href=\"javascript:;\" onclick=\"" . $modalPage . "(" . $dataId . ");\"> (Edit)</a>";	
				//}
				
				
				$jobArray[] = array(
					'unit' => $deptName,
					'shift' => $shiftDisplay,
					'variance' => $nvariance,
					'patients' => $patients,
					'total' => $totalCount,
					'charge' => $chargecount,
					'nursecount' => $totalNurses,
					'techs' => $totaltechcount,
					'other' => $otherCount,
					'dashColor' => $dashColor,
					'note' => $note,
					'shiftnum' => $dateshift,
					'escalationType' => $escalationType,
					'escalationId' => $escalationId,
					'style' => $style,
					'nowDate' => $nowDate,
					'nowDayDate' => $date
				);
           
			}
		}
		$outData = array();
		$outData['data'] = $jobArray;
		echo json_encode($outData,JSON_NUMERIC_CHECK);
	
		}else {
        $outData = array();
        $outData['data'] = array();
        echo json_encode($outData,JSON_NUMERIC_CHECK);
		}
}

/////////////////////////////////////
// GET PROD INFO
function GetProdTableDataWHP() {
	ini_set('memory_limit', '-1');
    //$storeId = isset($_REQUEST['storeId']) ? $_REQUEST['storeId'] : 0;
    //$date = isset($_REQUEST['day']) ? $_REQUEST['day'] : '';
    $idsearch = isset($_REQUEST['id']) && ($_REQUEST['id'] != 0) ? $_REQUEST['id'] : '';
	$prodStart = isset($_REQUEST['prodStart']) && ($_REQUEST['prodStart'] != '') ? $_REQUEST['prodStart'] : '';
	$prodEnd = isset($_REQUEST['prodEnd']) && ($_REQUEST['prodEnd'] != '') ? $_REQUEST['prodEnd'] : '';
	
	$deptType = isset($_REQUEST['type']) && ($_REQUEST['type'] != '') ? $_REQUEST['type'] : false;
	
	$now = time()-18000;
	$nowDate = date('m/d/y',$now);
	/*
	if($deptType){
		$typeAdd = " AND a.deptType = 3";
	}else{
		$typeAdd = " AND (a.deptType=3 OR a.deptType=2)";
	}
	*/	
    
    $userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
	$enterpriseId = Config::get('account')['enterpriseId'];
	//$deptId = Config::get('account')['deptid'];
    $role = Config::get('account')['role'];
	$individual = Config::get('account')['indMeasure'];
	
	$total = 0;
    $variance = 0;
	
	//if($prodStart && $prodEnd){
		//$startDate = date('YYYY-MM-DD',$newstartDate);
		//$endDate = date('YYYY-MM-DD',$newendDate);
		//$startDate = $prodStart;
		//$endDate = $prodEnd;
	//}else{
		$startDate = isset($_REQUEST['start']) ? $_REQUEST['start'] : date('yyyy-mm-dd');
		$endDate = isset($_REQUEST['end']) ? $_REQUEST['end'] : date('yyyy-mm-dd');
	//}
	
	if(intval($role)<5){
		$searchAdd = "  AND n.accountId={$accountId} AND a.active>0 AND n.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId` ={$userId}) AND (n.userId=0 OR n.userId={$userId})";
	}else if(intval($role)<8 && intval($role)>=5){
		$searchAdd = "  AND n.accountId={$accountId} AND a.active>0 AND n.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId` ={$userId})";
	}else if(intval($role)>=8 && intval($role)<11) {
		$searchAdd = " AND a.active>0 AND n.accountId={$accountId}";
	}else if(intval($role)==11) {
		$searchAdd = " AND n.accountId={$accountId}";
	}else if(intval($role)>=12 && intval($role)<100) {
		$searchAdd = " AND a.active>0 AND n.accountId={$accountId}";
	}else{
		$searchAdd = " AND a.active>0 AND n.accountId={$accountId} AND n.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId` ={$userId})";
	}
	
	if($idsearch){
		$searchAdd3 = " AND n.deptId={$idsearch}";
	}else{
		$searchAdd3 = '';
	}
	
	//if($user && intval($user)>0){
	//	$userAdd = " AND n.userId={$user}";
	//}else{
	//	$userAdd = '';
	//}
	
	if(strtotime($startDate)> strtotime('today UTC')){
		$orderBy = " ORDER BY a.dept ASC, n.dayDate ASC, n.shift DESC";
	}else{
		$orderBy = " ORDER BY a.dept ASC, n.dayDate DESC, n.shift DESC, n.entered ASC";
	}
	
	
	//$group = " GROUP BY CASE WHEN a.indMeasure <1 THEN n.deptId ELSE n.deptId END, CASE WHEN a.indMeasure <1 THEN n.dayDate ELSE n.dayDate END, CASE WHEN a.indMeasure <1 THEN n.shift ELSE n.userId END";	
	$group = " GROUP BY n.deptId, n.type, n.dayDate, n.shift, CASE WHEN (a.indMeasure <1 or n.type<2) THEN n.shift ELSE n.provId END";	
    
	$dbData = Config::get('db')->get_results("SELECT n.*, IFNULL(g.categoryName,'') as categoryName, DATE_FORMAT(CURDATE(),'%Y-%m-%d') as dateToday, u.last_name, IFNULL((select `last_name` from `productiveUser` where `id`=n.provId),'') as provName, n.id as nid, x.shiftName as shiftTitle, t.name as accountName, t.enterpriseId, t.prodIndicator, a.useGrid, a.dashColor, a.skill1calc, a.skill2calc, a.skill3calc, a.skill4calc, a.skill5calc, a.skill6calc, a.skill7calc, a.skill8calc, a.skill9calc, a.skill10calc, a.prodMeasure, a.showPlanned, a.modalPage, a.hppd, a.customWHPvalue, a.customWHP2value, a.customWHP3value, a.dept, a.indMeasure, a.thresholdHigh, a.thresholdLow, DATE_FORMAT(n.dayDate,'%m/%d/%y') as newDate, IFNULL(c.note,'') as escalationNote, IFNULL(l.escalation,'') as escalationType, c.id as escId, c.response, IFNULL(c.escalation,0) as escalationId, a.viewPage, a.active, s.shift as shiftName, u.first_name, u.last_name FROM `productiveNewData` n LEFT JOIN `productiveAccountXref` as x on x.dept = n.deptId and x.shift = n.shift LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as a on a.id = n.deptId LEFT JOIN `productiveCategory` as g on g.id = a.category LEFT JOIN `productiveAccount` as t on t.id = a.accountId LEFT OUTER JOIN `productiveUser` as u on u.id = n.userId LEFT OUTER JOIN `productiveEscalations` as c on c.dataId = n.id LEFT OUTER JOIN `productiveAcctEscalations` as l on l.id = c.escalation WHERE a.unitCategory>1 AND a.unitCategory <5 AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}'$searchAdd$searchAdd3$typeAdd$group$orderBy");
    //$dbData = Config::get('db')->get_results("SELECT n.*, CURDATE() as dateToday, n.id as nid, x.shiftName as shiftTitle, t.name as accountName, t.enterpriseId, t.prodIndicator, a.prodMeasure, a.showPlanned, a.modalPage, a.hppd, a.dept, a.indMeasure, a.thresholdHigh, a.thresholdLow, DATE_FORMAT(n.dayDate,'%m/%d/%y') as newDate, IFNULL(c.note,'') as escalationNote, IFNULL(l.escalation,'') as escalationType, IFNULL(c.escalation,0) as escalationId, a.viewPage, s.shift as shiftName, u.first_name, u.last_name FROM `productiveNewData` n LEFT JOIN `productiveAccountXref` as x on x.dept = n.deptId and x.shift = n.shift LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as a on a.id = n.deptId LEFT JOIN `productiveAccount` as t on t.id = a.accountId LEFT OUTER JOIN `productiveUser` as u on u.id = n.userId LEFT OUTER JOIN `productiveEscalations` as c on c.dataId = n.id LEFT OUTER JOIN `productiveAcctEscalations` as l on l.id = c.escalation WHERE a.active>0 AND a.prodMeasure=2 AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}'$searchAdd$searchAdd3 group by n.deptId, n.dayDate, n.shift$orderBy");
     $style=0;
    if($dbData){
		$outJobs = array();
        $jobArray = array();
		foreach($dbData as $ante){
			$whpPlan = intval($ante['whpPlan']);
			$showPlan = intval($ante['showPlanned']);
			
			if($whpPlan==0 || $whpPlan==1){
			$nUser = intval($ante['userId']);
			$qty = intval($ante['ldcount']);
			$antecount = intval($ante['antecount']);
			
			$shiftName = $ante['shiftName'];
			$ocount = intval($ante['ocount']);
			$type = intval($ante['type']);
			//$shifthrs = floatval($ante['shifthrs']);
			
			$today = date('Y-m-d',strtotime($ante['dateToday']));
			$daydate = date('Y-m-d',strtotime($ante['dayDate']));
			
			if($daydate>$today){
					$style = "3";
					$planned = " (Planned)";
				}else{
					$style = "0";
					$planned = '';
				}
			
			$shiftTitle = $ante['shiftTitle'] . $planned;
			$sitters = intval($ante['sittercount']);
			$atotal = intval($ante['atotal']);
			$ltotal = intval($ante['ltotal']);
			$obed = intval($ante['obed']);
			$dashColor = intval($ante['prodIndicator']);
			$patients = $atotal + $ltotal + $obed;
			$chargecount = intval($ante['chargecount']);
			$totalNurses = $qty + $antecount + $ocount;
			$totaltechcount = intval($ante['techcount']);
			$seccount = intval($ante['seccount']);
			$totalCount = $chargecount + $totalNurses + $seccount + $totaltechcount + $sitters;
			$prodMeasure = intval($ante['prodMeasure']);
			$indMeasure = intval($ante['indMeasure']);
			$lastName = $ante['last_name'];
			$provName = $ante['provName'];
			$hppd = $ante['hppd'];
			//$userCount = $ante['userCount'];
			$type = intval($ante['type']);
			$categoryName = $ante['categoryName'];
			
			
			$depName = $ante['dept'];
			$accountName = $ante['accountName'];
			
			if(intval($role)>12){
			$deptName = $accountName . " - " . $depName;	
			}else{
			$deptName = $depName;	
			}
			
			
			
			$deptId = $ante['deptId'];
			$dayDate = strtotime($ante['newDate'])-21600;
			$date = $ante['newDate'];
			$shift = intval($ante['shift']);
			$dateP = $ante['newDate'] . " " . $shiftTitle;
			
			if($indMeasure>0 && $type==2){
			$dateA = $ante['newDate'] . " " . $shiftTitle . " - " . $provName;
			}else{
			$dateA = $ante['newDate'] . " " . $shiftTitle;
			}
			
			$dateshift = strtotime($date) + intval($shift);
			$thresholdHigh = floatval($ante['thresholdHigh']);
			$thresholdLow = floatval($ante['thresholdLow']);			
			$aprod = floatval($ante['nproductivity']);
			$nvariance = $ante['nvariance'];
			//$modalPage = $ante['modalPage'];
			$escalationType = $ante['escalationType'];
			$escalationNote = $ante['escalationNote'];
			$escalationResponse = $ante['response'];
			$escalationId = intval($ante['escalationId']);
			$escId = intval($ante['escId']);
			$newnote = stripslashes($ante['note']);
			$dataId = intval($ante['nid']);
			$viewPage = $ante['viewPage'];
			
	
			
			///WHPUOS Data
			$actualWHP = floatval($ante['actualWHP']);
			$skill1 = floatval($ante['skill1val']);
			$skill2 = floatval($ante['skill2val']);
			$skill3 = floatval($ante['skill3val']);
			$skill4 = floatval($ante['skill4val']);
			$skill5 = floatval($ante['skill5val']);
			$skill6 = floatval($ante['skill6val']);
			$skill7 = floatval($ante['skill7val']);
			$skill8 = floatval($ante['skill8val']);
			$skill9 = floatval($ante['skill9val']);
			$skill10 = floatval($ante['skill10val']);
			$skill11 = floatval($ante['skill11val']);
			
		$skill1calc = floatval($ante['skill1calc']);
		$skill2calc = floatval($ante['skill2calc']);
		$skill3calc = floatval($ante['skill3calc']);
		$skill4calc = floatval($ante['skill4calc']);
		$skill5calc = floatval($ante['skill5calc']);
		$skill6calc = floatval($ante['skill6calc']);
		$skill7calc = floatval($ante['skill7calc']);
		$skill8calc = floatval($ante['skill8calc']);
		$skill9calc = floatval($ante['skill9calc']);
		$skill10calc = floatval($ante['skill10calc']);
		
			$procedures = floatval($ante['procedureCount']);
			$customVal = floatval($ante['whpCustom']);
			$customWHPvalue = floatval($ante['customWHPvalue']);
			$customWHP2value = floatval($ante['customWHP2value']);
			$customWHP3value = floatval($ante['customWHP3value']);
			$whpCustom2 = floatval($ante['whpCustom2']);
			$whpCustom3 = floatval($ante['whpCustom3']);
			$addHrs = floatval($ante['addResourceHrs']);
			$gvariance = intval($ante['gvariance']);
			$hoursVariance = round($ante['hrsVariance'],2);
			
			$procedureCount = $procedures + ($customVal * $customWHPvalue) + ($customWHP2value * $whpCustom2) + ($customWHP3value * $whpCustom3);
			$skillTotal = round(((($skill1 * $skill1calc) + ($skill2 * $skill2calc) + ($skill3 * $skill3calc) + ($skill4 * $skill4calc) + ($skill5 * $skill5calc) + ($skill6 * $skill6calc) + ($skill7 * $skill7calc) + ($skill8 * $skill8calc) + ($skill9 * $skill9calc) + ($skill10 * $skill10calc))) + $addHrs,1);
			
			if($type==2){
			$skillTotal = round($skill11,1);
			}
			
			if(intval($ante['useGrid'])==2){
			$skillTotal = $skill1 + $skill2 + $skill3 + $skill4 + $skill5 + $skill6 + $skill7 + $skill8 + $skill9 + $skill10 + $skill11;
			$hoursVariance = $gvariance;
			}
			
						
			//if(floatval($aprod) > 0 && intval($deptId)!=1){
			if($aprod > 0){
				$aproductivity = $aprod . "%";
			}else{
				$aproductivity ='';
			}
			
			
			//if($nUser==0 && $indMeasure==1 && $userCount>0 && $role==4){
			//do nothing;
			//}else 
			if($nUser==0){
				
			if((intval($role)==11 || intval($role)==7) && $whpPlan==1){
				$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $dateP . "</a></br><button type=\"button\" id=\"addButtonWHP\" class=\"btn btn-info btn-sm\" onclick=\"tj.editWHP(" . $dataId . "); \">Edit</button><button type=\"button\" id=\"addButtonWHP\" class=\"btn btn-danger btn-sm\" onclick=\"tj.clearwhpRecord(" . $dataId . "); \">Del</button>";
			}else if((intval($role)==11 || intval($role)==7) && $whpPlan!=1){
				$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $dateA . "</a></br><button type=\"button\" id=\"addButtonWHP\" class=\"btn btn-info btn-sm\" onclick=\"tj.editWHP(" . $dataId . "); \">Edit</button><button type=\"button\" id=\"addButtonWHP\" class=\"btn btn-danger btn-sm\" onclick=\"tj.clearwhpRecord(" . $dataId . "); \">Del</button>";
			}else if(intval($role)<10 && (strtotime($date) < strtotime($nowDate)) && $whpPlan!=1 ){
				$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $dateA . "</a></br><button type=\"button\" id=\"addButtonWHP\" class=\"btn btn-info btn-sm\" onclick=\"tj.editWHP(" . $dataId . "); \">Edit</button>";
			}else if(intval($role)<10 && (strtotime($date) < strtotime($nowDate)) && $whpPlan==1 ){
				$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $dateP . "</a></br><button type=\"button\" id=\"addButtonWHP\" class=\"btn btn-info btn-sm\" onclick=\"tj.editWHP(" . $dataId . "); \">Edit</button>";;
			}else if(intval($role)<10 && $whpPlan==1){
				$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $dateP . "</a></br><button type=\"button\" id=\"addButtonWHP\" class=\"btn btn-info btn-sm\" onclick=\"tj.editWHP(" . $dataId . "); \">Edit</button>";
			}else if(intval($role)<10 && $whpPlan!=1){
				$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $dateA . "</a></br><button type=\"button\" id=\"addButtonWHP\" class=\"btn btn-info btn-sm\" onclick=\"tj.editWHP(" . $dataId . "); \">Edit</button>";
			}else if($whpPlan!=1){
				$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $dateA . "</a>";
			}else{
				$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $dateP . "</a>";
			}
			
			
			/*	
			if(intval($role)>7 && $whpPlan==1 && intval($role)!=11){
				$shiftDisplay = $dateP;
			}else if(intval($role)>7 && $whpPlan!=1 && intval($role)!=11){
				$shiftDisplay = $dateA;
			}else if((intval($role)<8 || intval($role)==11) && $whpPlan==1){
				$shiftDisplay = $dateP . "</br><button type=\"button\" id=\"addButtonWHP\" class=\"btn btn-success btn-sm\" onclick=\"" . $modalPage . "(" . $dataId . "); \">Add</button><button type=\"button\" id=\"copyButtonWHP\" class=\"btn btn-info btn-sm\" onclick=\"tj.copyPlan(" . $dataId . "," . $deptId . "," . $shift . "," . $dayDate . "); \">Copy</button>";
				//<a href=\"javascript:;\" onclick=\"" . $modalPage . "(" . $dataId . ");\">  (Add)</a>";
			}else if((intval($role)<8 || intval($role)==11) && $whpPlan!=1){
				$shiftDisplay = $dateA . "</br><button type=\"button\" id=\"addButtonWHP\" class=\"btn btn-success btn-sm\" onclick=\"" . $modalPage . "(" . $dataId . "); \">Add</button>";
			}else{
				$shiftDisplay = $dateA;
			}
			*/
			$nvarianceNull = '';
			$aprodNull = '';
			$nurseNull = '';
			$techNull = '';
			$secNull = '';
			$patientsNull = '';
			$chargeNull = '';
			$otherNull = '';
			$totalNull = '';
			
				$jobArray[] = array(
					'unit' => $deptName,
					'shift' => $shiftDisplay,
					'aprod' => $aprodNull,
					'variance' => $nvarianceNull,
					'patients' => $patientsNull,
					'total' => $totalNull,
					'charge' => $chargeNull,
					'nursecount' => $nurseNull,
					'techs' => $techNull,
					'secs' => $secNull,
					'other' => $otherNull,
					'note' => '',
					'categoryName' => '',
					'shiftnum' => $dateshift,
					'escalationType' => '',
					'escalationId' => '',
					'style' => $style
				);
			
			}else{
				
			
			
			
			//if(intval($role)>7 && $whpPlan==1 && intval($role)!=11){
			//	$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $dateP . "</a>";
			//}else if(intval($role)>7 && $whpPlan!=1 && intval($role)!=11){
			//	$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $dateA . "</a>";
			//}else 
				
			if((intval($role)==11 || intval($role)==7) && $whpPlan==1){
				$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $dateP . "</a></br><button type=\"button\" id=\"addButtonWHP\" class=\"btn btn-info btn-sm\" onclick=\"tj.editWHP(" . $dataId . "); \">Edit</button><button type=\"button\" id=\"addButtonWHP\" class=\"btn btn-danger btn-sm\" onclick=\"tj.clearwhpRecord(" . $dataId . "); \">Del</button>";
			}else if((intval($role)==11 || intval($role)==7) && $whpPlan!=1){
				$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $dateA . "</a></br><button type=\"button\" id=\"addButtonWHP\" class=\"btn btn-info btn-sm\" onclick=\"tj.editWHP(" . $dataId . "); \">Edit</button><button type=\"button\" id=\"addButtonWHP\" class=\"btn btn-danger btn-sm\" onclick=\"tj.clearwhpRecord(" . $dataId . "); \">Del</button>";
			}else if(intval($role)<10 && (strtotime($date) < strtotime($nowDate)) && $whpPlan!=1 ){
				$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $dateA . "</a></br><button type=\"button\" id=\"addButtonWHP\" class=\"btn btn-info btn-sm\" onclick=\"tj.editWHP(" . $dataId . "); \">Edit</button>";
			}else if(intval($role)<10 && (strtotime($date) < strtotime($nowDate)) && $whpPlan==1 ){
				$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $dateP . "</a></br><button type=\"button\" id=\"addButtonWHP\" class=\"btn btn-info btn-sm\" onclick=\"tj.editWHP(" . $dataId . "); \">Edit</button>";;
			}else if(intval($role)<10 && $whpPlan==1){
				$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $dateP . "</a></br><button type=\"button\" id=\"addButtonWHP\" class=\"btn btn-info btn-sm\" onclick=\"tj.editWHP(" . $dataId . "); \">Edit</button>";
			}else if(intval($role)<10 && $whpPlan!=1){
				$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $dateA . "</a></br><button type=\"button\" id=\"addButtonWHP\" class=\"btn btn-info btn-sm\" onclick=\"tj.editWHP(" . $dataId . "); \">Edit</button>";
			}else if($whpPlan!=1){
				$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $dateA . "</a>";
			}else{
				$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $dateP . "</a>";
			}
			
				
				if($escalationId >0 && $nUser >0){
				$style = "1";
				$note = $newnote.  "<a href=\"javascript:;\" onclick=\"tj.getEscalationwhp(" . $escId . ");\"> ESCALATION: " . strtoupper($escalationType) . "</a> - " . stripslashes($escalationNote) . " " . stripslashes($escalationResponse);
				}else if($escalationId==0 && $nUser >0 && (($actualWHP < (($thresholdLow / 100) * $hppd) && $dashColor>=1) || ($actualWHP > (($thresholdHigh / 100) * $hppd) && $dashColor<=1))){
				$style = "1";
				$note = $newnote;
				}else{
				$style = "0";
				$note = $newnote;
				}
				
				
				$jobArray[] = array(
					'unit' => $deptName,
					'shift' => $shiftDisplay,
					'aprod' => $actualWHP,
					'variance' => $hoursVariance,
					'patients' => $procedureCount,
					'total' => $skillTotal,
					'charge' => $skill1,
					'nursecount' => $skill2,
					'techs' => $skill3,
					'secs' => $skill4,
					'other' => $skill5,
					'note' => $note,
					'categoryName' => $categoryName,
					'shiftnum' => $dateshift,
					'escalationType' => $escalationType,
					'escalationId' => $escalationId,
					'style' => $style
				);
			}			
			//$outJobs[] = $ante;
			}else{
				//do nothing;
			}
		}
		$outData = array();
		$outData['data'] = $jobArray;
		echo json_encode($outData,JSON_NUMERIC_CHECK);
		}else {
        $outData = array();
        $outData['data'] = array();
        echo json_encode($outData,JSON_NUMERIC_CHECK);
    }

}

/////////////////////////////////////
// GET PROD INFO USERX
function GetProdTableDatauserx() {

    $total = 0;
    $variance = 0;

    $dbData = Config::get('db')->get_results("SELECT n.*, DATE_FORMAT(n.dayDate,'%m/%d/%y') as newDate, s.shift as shiftName FROM `productiveNewData` n LEFT JOIN `productiveShifts` as s on s.id = n.shift WHERE n.dayDate=CURRENT_DATE and n.antecount>0 group by n.accountId, n.deptId, n.shift order by n.accountId ASC, n.deptId ASC, n.shift DESC");
     
    if($dbData){
		$outJobs = array();
        $jobArray = array();
		foreach($dbData as $ante){
			$acct = intval($ante['accountId']);
			$shift = intval($ante['shift']);
			$shiftName = $ante['shiftName'];
			$prod = $ante['nproductivity'];
			$antecount = $ante['antecount'];
			$var = intval($ante['avariance']) + intval($ante['lvariance']);
			$deptId = $ante['deptId'];
			$date = $ante['newDate'];
			$dateshift = $date.$shift;
			
			
			if(floatval($prod) > 0){
			$aproductivity = $prod . "%";	
			}else{
			$aproductivity ='';
			}
			
			if($antecount ==0){
			$acct = '';
			$deptId = '';
			$aproductivity = '';
			$var = '';
			$shiftName = '';
			$dateshift = '';
			
			
			$jobArray[] = array(
				'acct' => $acct,
				'dept' => $deptId,
				'prod' => $aproductivity,
                'var' => $var,
				'time' => $shiftName,
                'shiftnum' => $dateshift
            );
			}else{
					
			$jobArray[] = array(
				'acct' => $acct,
				'dept' => $deptId,
				'prod' => $aproductivity,
                'var' => $var,
				'time' => $shiftName,
                'shiftnum' => $dateshift
            );
			}
			
			$outJobs[] = $ante;
            
		}
		$outData = array();
		$outData['data'] = $jobArray;
		echo json_encode($outData,JSON_NUMERIC_CHECK);
	
		}else {
        $outData = array();
        $outData['data'] = array();
        echo json_encode($outData,JSON_NUMERIC_CHECK);
    }

}

/////////////////////////////////////
// GET PERFORMANCE INFO
function GetPerformanceTableData() {
	$idsearch = isset($_REQUEST['id']) && ($_REQUEST['id'] != 0) ? $_REQUEST['id'] : '';
	$deptId = isset($_REQUEST['dept']) && ($_REQUEST['dept'] != 0) ? $_REQUEST['dept'] : '';
    $startDate = isset($_REQUEST['start']) ? $_REQUEST['start'] : date('yyyy-mm-dd');
    $endDate = isset($_REQUEST['end']) ? $_REQUEST['end'] : date('yyyy-mm-dd');
    $userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
	//$deptId = Config::get('account')['deptid'];
	$role = Config::get('account')['role'];
	
	if(intval($role)==4 && !$idsearch){
	$dbData = Config::get('db')->get_results("SELECT n.id as nid, n.dayDate, n.deptId, s.shift as shiftName, AVG(NULLIF(n.hrsVariance ,0)) as avgprod, SUM(n.nvariance) as runningVar, AVG(n.actualWHP) as avgWHP, n.userId, n.entered as lastEntered, AVG(n.avariance + n.lvariance ) as avgvar, AVG(n.atotal+n.patientCount2) as avgpat, count(n.id) as records, u.first_name, u.last_name, d.id as did, d.hppd, d.viewPage, d.dept as depName, d.prodMeasure FROM `productiveNewData` n LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as d on d.id = n.deptId LEFT JOIN `productiveUser` as u on u.id = n.userId WHERE d.active>0 AND n.userId>0 AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}' AND n.accountId={$accountId} AND n.whpPlan !=1 AND n.userId={$userId} group by n.userId, n.deptId order by `avgprod` DESC");	
	}else if(intval($role)>=5 && intval($role)<9 && !$idsearch){
    //$dbData = Config::get('db')->get_results("SELECT n.id as nid, AVG(NULLIF(n.nproductivity ,0)) as avgprod, SUM(n.nvariance) as runningVar, AVG(n.actualWHP) as avgWHP, n.userId, n.entered, DATE_FORMAT(z.entered,'%m/%d/%Y') as lastEntered, AVG(n.avariance + n.lvariance ) as avgvar, AVG(n.atotal+n.ltotal) as avgpat, count(n.id) as records, u.first_name, u.last_name, d.id as did, d.hppd, d.viewPage, d.dept as depName, d.prodMeasure FROM `productiveNewData` n LEFT JOIN `productiveNewData` as z on z.id =(SELECT `id` from `productiveNewData` where `userId` = n.userId order by `id` DESC LIMIT 1) LEFT JOIN `ProductiveDept` as d on d.id = n.deptId LEFT JOIN `productiveUser` as u on u.id = n.userId WHERE n.userId>0 AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}' AND n.accountId={$accountId} AND n.whpPlan !=1 AND n.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId} and `primaryUnit`>5) group by n.userId order by `avgprod` DESC");
	$dbData = Config::get('db')->get_results("SELECT n.id as nid, n.dayDate, n.deptId, s.shift as shiftName, AVG(NULLIF(n.hrsVariance ,0)) as avgprod, SUM(n.nvariance) as runningVar, AVG(n.actualWHP) as avgWHP, n.userId, n.entered as lastEntered, AVG(n.avariance + n.lvariance ) as avgvar, AVG(n.atotal+n.patientCount2) as avgpat, count(n.id) as records, u.first_name, u.last_name, d.id as did, d.hppd, d.viewPage, d.dept as depName, d.prodMeasure FROM `productiveNewData` n LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as d on d.id = n.deptId LEFT JOIN `productiveUser` as u on u.id = n.userId WHERE d.active>0 AND n.userId>0 AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}' AND n.accountId={$accountId} AND n.whpPlan !=1 AND n.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId} and `primaryUnit`>=5) group by n.userId, n.deptId order by `avgprod` DESC");
	}else if(intval($role)>=9 && !$idsearch){
    $dbData = Config::get('db')->get_results("SELECT n.id as nid, n.dayDate, n.deptId, s.shift as shiftName, AVG(NULLIF(n.hrsVariance ,0)) as avgprod, SUM(n.nvariance) as runningVar, AVG(n.actualWHP) as avgWHP, n.userId, n.entered as lastEntered, AVG(n.avariance + n.lvariance ) as avgvar, AVG(n.atotal+n.patientCount2) as avgpat, count(n.id) as records, u.first_name, u.last_name, d.id as did, d.hppd, d.viewPage, d.dept as depName, d.prodMeasure, a.id FROM `productiveNewData` n LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as d on d.id = n.deptId LEFT JOIN `productiveUser` as u on u.id = n.userId LEFT JOIN `productiveAccount` as a on a.id = d.accountId WHERE d.active>0 AND n.userId>0 AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}' AND n.whpPlan !=1 AND n.accountId={$accountId} group by n.userId, n.deptId order by `avgprod` DESC");
	}else if(intval($role)<9 && intval($role)>=5 && $idsearch){
	$dbData = Config::get('db')->get_results("SELECT n.id as nid, n.dayDate, n.deptId, s.shift as shiftName, AVG(NULLIF(n.hrsVariance ,0)) as avgprod, SUM(n.nvariance) as runningVar, AVG(n.actualWHP) as avgWHP, n.userId, n.entered as lastEntered, AVG(n.avariance + n.lvariance ) as avgvar, AVG(n.atotal+n.patientCount2) as avgpat, count(n.id) as records, u.first_name, u.last_name, d.id as did, d.hppd, d.viewPage, d.dept as depName, d.prodMeasure FROM `productiveNewData` n LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as d on d.id = n.deptId LEFT JOIN `productiveUser` as u on u.id = n.userId WHERE d.active>0 AND n.userId>0 AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}' AND n.accountId={$accountId} AND n.userId={$idsearch} and n.deptId={$deptId} AND n.whpPlan !=1 AND n.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId}) group by n.dayDate, n.deptId, n.shift order by n.dayDate DESC, n.shift DESC");		
	}else if(intval($role)<5 && $idsearch){
	$dbData = Config::get('db')->get_results("SELECT n.id as nid, n.dayDate, n.deptId, s.shift as shiftName, AVG(NULLIF(n.hrsVariance ,0)) as avgprod, SUM(n.nvariance) as runningVar, AVG(n.actualWHP) as avgWHP, n.userId, n.entered as lastEntered, AVG(n.avariance + n.lvariance ) as avgvar, AVG(n.atotal+n.patientCount2) as avgpat, count(n.id) as records, u.first_name, u.last_name, d.id as did, d.hppd, d.viewPage, d.dept as depName, d.prodMeasure FROM `productiveNewData` n LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as d on d.id = n.deptId LEFT JOIN `productiveUser` as u on u.id = n.userId WHERE d.active>0 AND n.userId>0 AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}' AND n.accountId={$accountId} AND n.whpPlan !=1 AND n.userId={$idsearch} and n.deptId={$deptId} group by n.dayDate, n.shift order by n.dayDate DESC, n.shift DESC");		
	}else if(intval($role)>=9 && $idsearch){
	$dbData = Config::get('db')->get_results("SELECT n.id as nid, n.dayDate, n.deptId, s.shift as shiftName, AVG(NULLIF(n.hrsVariance ,0)) as avgprod, SUM(n.nvariance) as runningVar, AVG(n.actualWHP) as avgWHP, n.userId, n.entered as lastEntered, AVG(n.avariance + n.lvariance ) as avgvar, AVG(n.atotal+n.patientCount2) as avgpat, count(n.id) as records, u.first_name, u.last_name, d.id as did, d.hppd, d.viewPage, d.dept as depName, d.prodMeasure FROM `productiveNewData` n LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as d on d.id = n.deptId LEFT JOIN `productiveUser` as u on u.id = n.userId WHERE d.active>0 AND n.userId>0 AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}' AND n.accountId={$accountId} AND n.whpPlan !=1 AND n.userId={$idsearch} and n.deptId={$deptId} group by n.dayDate, n.deptId, n.shift order by n.dayDate DESC, n.shift DESC");		
	}else{
	$dbData='';
	}
	
    if($dbData){
		$outJobs = array();
        $jobArray = array();
		foreach($dbData as $ante){
			
			if($idsearch && intval($accountId)!=1){
			$user = "<a href=\"#reportView?i=" . $ante['nid'] . "\">" . $ante['last_name'].", ".$ante['first_name'] . "</a>";
			$ndate = $ante['dayDate'] . " - " . $ante['shiftName'];
			//$ndate = $ante['lastEntered'];
			}else{
			$user = "<a href=\"#performance?id=".$ante['userId']."&d=".$ante['deptId']."\" >" . $ante['last_name'].", ".$ante['first_name'] . "</a>";
			$ndate = '';
			}
			
			if($idsearch && intval($accountId)==1){
			//$ndate = $ante['lastEntered'];
			$ndate = $ante['dayDate'] . " - " . $ante['shiftName'];
			$user = "<a href=\"view.php?u=" . $ante['nid'] . "\">" . $ante['last_name'].", ".$ante['first_name'] . "</a>";	
			}
			
			if(intval($ante['prodMeasure'])!=2){
			$prod = round($ante['avgprod'],1);
			$var = '';
			$hppd = '';
			$unit = $ante['depName'];
			//$ndate = $ante['lastEntered'];
			$avgpat = '';
			$count = intval($ante['records']);
			$style = 0;
			$header1 = "Avg. Variance";
			$header2 = "Avg. Patients";
			}else{
			$prod = round($ante['avgprod'],1);
			$var = round($ante['runningVar'],2);
			$unit = $ante['depName'];
			$hppd = $ante['hppd'];
			//$ndate = $ante['lastEntered'];
			$avgpat = round($ante['avgWHP'],3);
			$count = intval($ante['records']);
			$header1 = "Running Variance";
			$header2 = "Avg. WHP";
				$style= 0;
			}	
			
			$jobArray[] = array(
				'user' => $user,
				'unit' => $unit,
				'target' => $hppd,
				'prod' => $prod,
				'date' => $ndate,
				'variance' => $var,
                'patients' => $avgpat,
				'records' => $count,
				'id'=> $idsearch,
				'Style'=> $style,
				'header1'=> $header1,
				'header2'=> $header2
            );
			}
			
			//$outJobs[] = $ante;
         	
		$outData = array();
		$outData['data'] = $jobArray;
		echo json_encode($outData,JSON_NUMERIC_CHECK);
		}else {
        $outData = array();
        $outData['data'] = array();
        echo json_encode($outData,JSON_NUMERIC_CHECK);
		}
}

/////////////////////////////////////
// GET VIEW LOG INFO
function GetviewlogTableData() {
	$idsearch = isset($_REQUEST['id']) && ($_REQUEST['id'] != 0) ? $_REQUEST['id'] : '';
	$deptId = isset($_REQUEST['dept']) && ($_REQUEST['dept'] != 0) ? $_REQUEST['dept'] : '';
    $startDate = isset($_REQUEST['start']) ? $_REQUEST['start'] : date('yyyy-mm-dd');
    $endDate = isset($_REQUEST['end']) ? $_REQUEST['end'] : date('yyyy-mm-dd');
    $userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
	//$deptId = Config::get('account')['deptid'];
	$role = Config::get('account')['role'];
	
	if(intval($role)==4 && !$idsearch){
	$dbData = Config::get('db')->get_results("SELECT n.id as nid, n.dayDate, n.deptId, s.shift as shiftName, AVG(NULLIF(n.hrsVariance ,0)) as avgprod, SUM(n.nvariance) as runningVar, AVG(n.actualWHP) as avgWHP, n.userId, n.entered as lastEntered, AVG(n.avariance + n.lvariance ) as avgvar, AVG(n.atotal+n.patientCount2) as avgpat, count(n.id) as records, u.first_name, u.last_name, d.id as did, d.hppd, d.viewPage, d.dept as depName, d.prodMeasure FROM `productiveNewData` n LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as d on d.id = n.deptId LEFT JOIN `productiveUser` as u on u.id = n.userId WHERE d.active>0 AND n.userId>0 AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}' AND n.accountId={$accountId} AND n.whpPlan !=1 AND n.userId={$userId} group by n.userId, n.deptId order by `avgprod` DESC");	
	}else if(intval($role)>=5 && intval($role)<=8 && !$idsearch){
    //$dbData = Config::get('db')->get_results("SELECT n.id as nid, AVG(NULLIF(n.nproductivity ,0)) as avgprod, SUM(n.nvariance) as runningVar, AVG(n.actualWHP) as avgWHP, n.userId, n.entered, DATE_FORMAT(z.entered,'%m/%d/%Y') as lastEntered, AVG(n.avariance + n.lvariance ) as avgvar, AVG(n.atotal+n.ltotal) as avgpat, count(n.id) as records, u.first_name, u.last_name, d.id as did, d.hppd, d.viewPage, d.dept as depName, d.prodMeasure FROM `productiveNewData` n LEFT JOIN `productiveNewData` as z on z.id =(SELECT `id` from `productiveNewData` where `userId` = n.userId order by `id` DESC LIMIT 1) LEFT JOIN `ProductiveDept` as d on d.id = n.deptId LEFT JOIN `productiveUser` as u on u.id = n.userId WHERE n.userId>0 AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}' AND n.accountId={$accountId} AND n.whpPlan !=1 AND n.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId} and `primaryUnit`>5) group by n.userId order by `avgprod` DESC");
	$dbData = Config::get('db')->get_results("SELECT n.id as nid, n.dayDate, n.deptId, s.shift as shiftName, AVG(NULLIF(n.hrsVariance ,0)) as avgprod, SUM(n.nvariance) as runningVar, AVG(n.actualWHP) as avgWHP, n.userId, n.entered as lastEntered, AVG(n.avariance + n.lvariance ) as avgvar, AVG(n.atotal+n.patientCount2) as avgpat, count(n.id) as records, u.first_name, u.last_name, d.id as did, d.hppd, d.viewPage, d.dept as depName, d.prodMeasure FROM `productiveNewData` n LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as d on d.id = n.deptId LEFT JOIN `productiveUser` as u on u.id = n.userId WHERE d.active>0 AND n.userId>0 AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}' AND n.accountId={$accountId} AND n.whpPlan !=1 AND n.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId} and `primaryUnit`>5) group by n.userId, n.deptId order by `avgprod` DESC");
	}else if(intval($role)>8 && !$idsearch){
    $dbData = Config::get('db')->get_results("SELECT n.id as nid, n.dayDate, n.deptId, s.shift as shiftName, AVG(NULLIF(n.hrsVariance ,0)) as avgprod, SUM(n.nvariance) as runningVar, AVG(n.actualWHP) as avgWHP, n.userId, n.entered as lastEntered, AVG(n.avariance + n.lvariance ) as avgvar, AVG(n.atotal+n.patientCount2) as avgpat, count(n.id) as records, u.first_name, u.last_name, d.id as did, d.hppd, d.viewPage, d.dept as depName, d.prodMeasure, a.id FROM `productiveNewData` n LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as d on d.id = n.deptId LEFT JOIN `productiveUser` as u on u.id = n.userId LEFT JOIN `productiveAccount` as a on a.id = d.accountId WHERE d.active>0 AND n.userId>0 AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}' AND n.whpPlan !=1 AND n.accountId={$accountId} group by n.userId, n.deptId order by `avgprod` DESC");
	}else if(intval($role)<=8 && intval($role)>=5 && $idsearch){
	$dbData = Config::get('db')->get_results("SELECT n.id as nid, n.dayDate, n.deptId, s.shift as shiftName, AVG(NULLIF(n.hrsVariance ,0)) as avgprod, SUM(n.nvariance) as runningVar, AVG(n.actualWHP) as avgWHP, n.userId, n.entered as lastEntered, AVG(n.avariance + n.lvariance ) as avgvar, AVG(n.atotal+n.patientCount2) as avgpat, count(n.id) as records, u.first_name, u.last_name, d.id as did, d.hppd, d.viewPage, d.dept as depName, d.prodMeasure FROM `productiveNewData` n LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as d on d.id = n.deptId LEFT JOIN `productiveUser` as u on u.id = n.userId WHERE d.active>0 AND n.userId>0 AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}' AND n.accountId={$accountId} AND n.userId={$idsearch} and n.deptId={$deptId} AND n.whpPlan !=1 AND n.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId}) group by n.dayDate, n.deptId, n.shift order by n.dayDate DESC, n.shift DESC");		
	}else if(intval($role)<5 && $idsearch){
	$dbData = Config::get('db')->get_results("SELECT n.id as nid, n.dayDate, n.deptId, s.shift as shiftName, AVG(NULLIF(n.hrsVariance ,0)) as avgprod, SUM(n.nvariance) as runningVar, AVG(n.actualWHP) as avgWHP, n.userId, n.entered as lastEntered, AVG(n.avariance + n.lvariance ) as avgvar, AVG(n.atotal+n.patientCount2) as avgpat, count(n.id) as records, u.first_name, u.last_name, d.id as did, d.hppd, d.viewPage, d.dept as depName, d.prodMeasure FROM `productiveNewData` n LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as d on d.id = n.deptId LEFT JOIN `productiveUser` as u on u.id = n.userId WHERE d.active>0 AND n.userId>0 AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}' AND n.accountId={$accountId} AND n.whpPlan !=1 AND n.userId={$idsearch} and n.deptId={$deptId} group by n.dayDate, n.shift order by n.dayDate DESC, n.shift DESC");		
	}else if(intval($role)>8 && $idsearch){
	$dbData = Config::get('db')->get_results("SELECT n.id as nid, n.dayDate, n.deptId, s.shift as shiftName, AVG(NULLIF(n.hrsVariance ,0)) as avgprod, SUM(n.nvariance) as runningVar, AVG(n.actualWHP) as avgWHP, n.userId, n.entered as lastEntered, AVG(n.avariance + n.lvariance ) as avgvar, AVG(n.atotal+n.patientCount2) as avgpat, count(n.id) as records, u.first_name, u.last_name, d.id as did, d.hppd, d.viewPage, d.dept as depName, d.prodMeasure FROM `productiveNewData` n LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as d on d.id = n.deptId LEFT JOIN `productiveUser` as u on u.id = n.userId WHERE d.active>0 AND n.userId>0 AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}' AND n.accountId={$accountId} AND n.whpPlan !=1 AND n.userId={$idsearch} and n.deptId={$deptId} group by n.dayDate, n.deptId, n.shift order by n.dayDate DESC, n.shift DESC");		
	}else{
	$dbData='';
	}
	
    if($dbData){
		$outJobs = array();
        $jobArray = array();
		foreach($dbData as $ante){
			
			if($idsearch && intval($accountId)!=1){
			$user = "<a href=\"#reportView?i=" . $ante['nid'] . "\">" . $ante['last_name'].", ".$ante['first_name'] . "</a>";
			$ndate = $ante['dayDate'] . " - " . $ante['shiftName'];
			//$ndate = $ante['lastEntered'];
			}else{
			$user = "<a href=\"#performance?id=".$ante['userId']."&d=".$ante['deptId']."\" >" . $ante['last_name'].", ".$ante['first_name'] . "</a>";
			$ndate = '';
			}
			
			if($idsearch && intval($accountId)==1){
			//$ndate = $ante['lastEntered'];
			$ndate = $ante['dayDate'] . " - " . $ante['shiftName'];
			$user = "<a href=\"view.php?u=" . $ante['nid'] . "\">" . $ante['last_name'].", ".$ante['first_name'] . "</a>";	
			}
			
			if(intval($ante['prodMeasure'])!=2){
			$prod = round($ante['avgprod'],1);
			$var = '';
			$hppd = '';
			$unit = $ante['depName'];
			//$ndate = $ante['lastEntered'];
			$avgpat = '';
			$count = intval($ante['records']);
			$style = 0;
			$header1 = "Avg. Variance";
			$header2 = "Avg. Patients";
			}else{
			$prod = round($ante['avgprod'],1);
			$var = round($ante['runningVar'],2);
			$unit = $ante['depName'];
			$hppd = $ante['hppd'];
			//$ndate = $ante['lastEntered'];
			$avgpat = round($ante['avgWHP'],3);
			$count = intval($ante['records']);
			$header1 = "Running Variance";
			$header2 = "Avg. WHP";
				$style= 0;
			}	
			
			$jobArray[] = array(
				'user' => $user,
				'unit' => $unit,
				'target' => $hppd,
				'prod' => $prod,
				'date' => $ndate,
				'variance' => $var,
                'patients' => $avgpat,
				'records' => $count,
				'id'=> $idsearch,
				'Style'=> $style,
				'header1'=> $header1,
				'header2'=> $header2
            );
			}
			
			//$outJobs[] = $ante;
         	
		$outData = array();
		$outData['data'] = $jobArray;
		echo json_encode($outData,JSON_NUMERIC_CHECK);
		}else {
        $outData = array();
        $outData['data'] = array();
        echo json_encode($outData,JSON_NUMERIC_CHECK);
		}
}

/////////////////////////////////////
// GET COMPLIANCE INFO
function GetComplianceTableData() {
	$idsearch = isset($_REQUEST['id']) && ($_REQUEST['id'] != 0) ? $_REQUEST['id'] : '';
    $startDate = isset($_REQUEST['start']) ? $_REQUEST['start'] : date('yyyy-mm-dd');
    $endDate = isset($_REQUEST['end']) ? $_REQUEST['end'] : date('yyyy-mm-dd');
    $userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
	//$deptId = Config::get('account')['deptid'];
	$role = Config::get('account')['role'];
	$enterpriseId = Config::get('account')['enterpriseId'];
	
	if(intval($role)<=7 && !$idsearch){
		$searchAdd = " AND n.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId} and `primaryUnit`>0)";
	}else if(intval($role)>7 && intval($role)<=12 && !$idsearch){
		$searchAdd = " AND n.accountId={$accountId}";
	}else if(Intval($role)>12 && !$idsearch){
		$searchAdd = " AND a.enterpriseId={$enterpriseId}";
	}else{
		$searchAdd = " AND n.deptId={$idsearch}";	
	}

    $dbData = Config::get('db')->get_results("SELECT 
        SUM(CASE WHEN n.userId>0 and n.activeRecord>0 THEN 1 ELSE 0 END) as submitted,
		SUM(CASE WHEN n.userId>0 and l.userId>0 THEN 1 ELSE 0 END) as viewed,
		SUM(CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END) as totalReports,
		SUM(CASE WHEN n.userId=0 and n.activeRecord>0 and n.planSubmitted=1 THEN 1 ELSE 0 END) as plans, 
		d.dept, IFNULL(g.categoryName,'') as categoryName, CONCAT(u.last_name, ', ', u.first_name) as mgrName, CONCAT(z.last_name, ', ', z.first_name) as dirName, a.name as accountName, n.deptId, d.prodMeasure
        FROM
            `productiveNewData` n
		LEFT JOIN 
			`productiveviewLog` as l on l.dataId = n.id
        LEFT JOIN 
			`ProductiveDept` as d on d.id = n.deptId
		LEFT JOIN 
			`productiveCategory` as g on g.id = d.category
		LEFT JOIN 
			`productiveAccount` as a on a.id = d.accountId
        LEFT OUTER JOIN
        	`productiveDeptXref` as x on x.deptId = d.id and x.primaryUnit=6
		LEFT OUTER JOIN
        	`productiveDeptXref` as y on y.deptId = d.id and y.primaryUnit=7
        LEFT OUTER JOIN
        	`productiveUser` as u on u.id = x.userId
		LEFT OUTER JOIN
        	`productiveUser` as z on z.id = y.userId
        WHERE
            n.type<2 AND d.active>0$searchAdd
        AND 
            DATE_FORMAT(n.dayDate, '%Y-%m-%d') >= '{$startDate}' AND 
            DATE_FORMAT(n.dayDate, '%Y-%m-%d') <= '{$endDate}' 
        GROUP BY n.deptId");
      
    if($dbData){
		$outJobs = array();
        $jobArray = array();
		foreach($dbData as $ante){
			
			if(!$idsearch){
			//$user = "<a href=\"#compliance?id=".$ante['deptId']."\" >" . $ante['dept'] . "</a>";
			$unit = $ante['dept'];
			}else{
			$unit = $ante['dept'];
			}
			$accountName = $ante['accountName'];
			$categoryName = $ante['categoryName'];
			$director = $ante['dirName'];
			$manager = $ante['mgrName'];
			$prodMeasure = $ante['prodMeasure'];
			//$reports = round(floatval($ante['compliance']),1);
			$reports = round((intval($ante['submitted']) + intval($ante['plans']))/intval($ante['totalReports'])*100,1);
			$viewed = round((intval($ante['viewed']))/intval($ante['totalReports'])*100,1);;
			
			if(intval($prodMeasure)!=2){
				$view = "staffing";
			}else{
				$view = "supportstaffing";
			}
		
			//$unitName = "<a href=\"javascript:;\" onclick=\"tj.staffingView(" .$ante['dept'] . "," . $startDate . "," . $endDate . ")\">" . $ante['dept'] . "</a>";
			$unitName = "<a href=\"#" . $view . "?id=" . $ante['deptId'] . "&s=" . $startDate . "&e=" . $endDate . "\" >" . $ante['dept'] . "</a>";
			//$unitName = $unit;	
			
						
			$jobArray[] = array(
				'unit' => $unitName,
				'categoryName' => $categoryName,
				'director' => $director,
				'manager' => $manager,
				'reports' => $reports
            );
			}
			
			//$outJobs[] = $ante;
         	
		$outData = array();
		$outData['data'] = $jobArray;
		echo json_encode($outData,JSON_NUMERIC_CHECK);
		}else {
        $outData = array();
		$jobArray[] = array(
				'unit' => '',
				'categoryName' => '',
				'director' => '',
				'manager' => '',
				'reports' => ''
            );
        $outData['data'] = $jobArray;
        echo json_encode($outData,JSON_NUMERIC_CHECK);
		}
}

/////////////////////////////////////
// GET PDF INFO
function Getpdfs() {
	$deptId = (isset($_REQUEST['deptId'])?$_REQUEST['deptId']:'');
	$pdfId = (isset($_REQUEST['pdfId'])?$_REQUEST['pdfId']:'');
	$idsearch = isset($_REQUEST['id']) && ($_REQUEST['id'] != 0) ? $_REQUEST['id'] : '';
    $startDate = isset($_REQUEST['start']) ? $_REQUEST['start'] : date('yyyy-mm-dd');
    $endDate = isset($_REQUEST['end']) ? $_REQUEST['end'] : date('yyyy-mm-dd');
    $userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
	//$deptId = Config::get('account')['deptid'];
	$role = Config::get('account')['role'];
	$enterpriseId = Config::get('account')['enterpriseId'];
	
	if(intval($role)<=7 && !$idsearch){
		$searchAdd = " d.active>0 AND r.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId} and `primaryUnit`>0) OR r.deptId=99 OR r.deptId=999";
	}else if(intval($role)>7 && intval($role)<11 && !$idsearch){
		$searchAdd = " d.active>0 AND d.accountId={$accountId}";
	}else if(intval($role)==11 && !$idsearch){
		$searchAdd = " d.accountId={$accountId}";
	}else if(Intval($role)>=12 && !$idsearch){
		$searchAdd = " d.active>0 AND a.accountId={$enterpriseId}";
	}else{
		$searchAdd = " d.active>0 AND r.deptId={$idsearch}";	
	}

    $dbData = Config::get('db')->get_results("SELECT r.*, d.dept
        FROM
            `productiveReports` r 
        LEFT JOIN 
			`ProductiveDept` as d on d.id = r.deptId
        WHERE
            $searchAdd
        ORDER BY d.dept asc, r.reportName asc");
      
    if($dbData){
		$outJobs = array();
        $jobArray = array();
		foreach($dbData as $ante){
		
			//$pdfbutton = "<form id=\"dateFormwhp\" name=\"dateFormwhp\" action=\"inc/data.php?req=pdf" . $ante['pdfId'] . "\" method=\"post\"><button id=\"downloadPDF\" class=\"btn btn-info\" type=\"submit\">PDF</button><input type=\"number\" id=\"deptNum\" name=\"deptNum\" value=\"" . $ante['deptId'] . "\" hidden></form>";
			
			$pdfbutton = "<form id=\"dateFormwhp" . $ante['id'] . "\" name=\"dateFormwhp" . $ante['id'] . "\" action=\"inc/reports.php?req=pdf" . $ante['pdfId'] . "\" method=\"post\"><button id=\"downloadPDF" . $ante['id'] . "\" class=\"btn btn-info\" type=\"submit\">PDF</button><input type=\"number\" name=\"deptNum\" value=\"" . $ante['deptId'] . "\" hidden></form>";
			
						
			$jobArray[] = array(
				'unit' => $ante['dept'],
				'name' => $ante['reportName'],
				'desc' => $ante['reportDesc'],
				'updated' => $ante['updated'],
				'type' => $pdfbutton
            );
			}
			
			//$outJobs[] = $ante;
         	
		$outData = array();
		$outData['data'] = $jobArray;
		echo json_encode($outData,JSON_NUMERIC_CHECK);
		}else {
        $outData = array();
		$jobArray[] = array(
				'unit' => '',
				'name' => '',
				'desc' => '',
				'updated' => '',
				'type' => ''
            );
        $outData['data'] = $jobArray;
        echo json_encode($outData,JSON_NUMERIC_CHECK);
		}
}


/////////////////////////////////////
// GET CRASH INFO
function GetCrashTableData() {
	//$idsearch = isset($_REQUEST['id']) && ($_REQUEST['id'] != 0) ? $_REQUEST['id'] : '';
    $startDate = isset($_REQUEST['start']) ? $_REQUEST['start'] : date('yyyy-mm-dd');
    $endDate = isset($_REQUEST['end']) ? $_REQUEST['end'] : date('yyyy-mm-dd');
    $userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
	//$deptId = Config::get('account')['deptid'];
	$role = Config::get('account')['role'];
	$enterpriseId = Config::get('account')['enterpriseId'];
	
	if(intval($role)<=7){
		$searchAdd = " AND c.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId} and `primaryUnit`>0)";
	}else if(intval($role)>7 && intval($role)<=12){
		$searchAdd = " AND d.accountId={$accountId}";
	}else if(Intval($role)>12){
		$searchAdd = " AND a.enterpriseId={$enterpriseId}";
	}else{
		$searchAdd = " AND d.accountId={$accountId}";	
	}

    $dbData = Config::get('db')->get_results("SELECT c.*, DATE_FORMAT(c.dayDate, '%m/%d/%y') as crashDate, (case when c.dateEntered='0000-00-00 00:00:00' then 'incomplete' else c.dateEntered end) as newDate, d.dept, u.first_name, u.last_name FROM
            `crashcartlogs` c
        LEFT JOIN 
			`ProductiveDept` as d on d.id = c.deptId
		LEFT JOIN 
			`productiveAccount` as a on a.id = d.accountId
		LEFT OUTER JOIN
        	`productiveUser` as u on u.id = c.userId
        WHERE
            a.logs>0 AND d.active>0$searchAdd
        AND 
            DATE_FORMAT(c.dayDate, '%Y-%m-%d') = CURDATE() 
        GROUP BY c.dateEntered, c.deptId");
      
    if($dbData){
		$outJobs = array();
        $jobArray = array();
		foreach($dbData as $ante){
			
			//$unitName = $ante['dept'];
			$userName = substr($ante['first_name'],0,1) . ". " . $ante['last_name'] ;
			$dateCompleted = $ante['crashDate'];
			$cartDesc= $ante['cartDesc'];
			if(intval($ante['userId'])>0){
			$dateEntered= $ante['newDate'];
			}else{
			$dateEntered= '';
			}
		
			//$unitName = "<a href=\"#" . $view . "?id=" . $ante['deptId'] . "&s=" . $startDate . "&e=" . $endDate . "\" >" . $ante['dept'] . "</a>";
			$unitName = "<a href=\"javascript:;\" onclick=\"tj.editCrash(" . $ante['id'] . ");\">" . $ante['dept'] . "</a>";	
			
						
			$jobArray[] = array(
				'unit' => $unitName,
				'date' => $dateCompleted,
				'cart' => $cartDesc,
				'user' => $userName,
				'entered' => $dateEntered
            );
			}
			
			//$outJobs[] = $ante;
         	
		$outData = array();
		$outData['data'] = $jobArray;
		echo json_encode($outData,JSON_NUMERIC_CHECK);
		}else {
        $outData = array();
		$jobArray[] = array(
				'unit' => '',
				'date' => '',
				'cart' => '',
				'user' => '',
				'entered' => ''
            );
        $outData['data'] = $jobArray;
        echo json_encode($outData,JSON_NUMERIC_CHECK);
		}
}



/////////////////////////////////////
// GET PERFORMANCE INFO
function GetEscalationsTableData() {
	$idsearch = isset($_REQUEST['id']) && ($_REQUEST['id'] != 0) ? $_REQUEST['id'] : '';
    $startDate = isset($_REQUEST['start']) ? $_REQUEST['start'] : date('yyyy-mm-dd');
    $endDate = isset($_REQUEST['end']) ? $_REQUEST['end'] : date('yyyy-mm-dd');
    $userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
	//$deptId = Config::get('account')['deptid'];
	$role = Config::get('account')['role'];
	$enterpriseId = Config::get('account')['enterpriseId'];
	
	if(intval($role)<=8 && !$idsearch){
		$searchAdd = " AND e.escalation !=0 AND e.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId} and `primaryUnit`>0)";
	}else if(intval($role)>8 && intval($role)<=12 && !$idsearch){
		$searchAdd = " AND e.accountId={$accountId} and e.escalation !=0";
	}else if(intval($role)>12 && !$idsearch){
		$searchAdd = " AND t.enterpriseId={$enterpriseId} and e.escalation !=0";
	}else{
		$searchAdd = " AND e.userId={$idsearch} and e.escalation !=0";	
	}

    $dbData = Config::get('db')->get_results("SELECT e.*, t.name as accountName, t.enterpriseId, a.escalation as escalationName, u.first_name, u.last_name, d.dept FROM `productiveEscalations` e LEFT JOIN `productiveAcctEscalations` as a on a.id = e.escalation LEFT JOIN `ProductiveDept` as d on d.id = e.deptId LEFT JOIN `productiveAccount` as t on t.id = d.accountId LEFT JOIN `productiveUser` as u on u.id = e.userId WHERE d.active>0 AND e.active>0 AND DATE_FORMAT(e.dateSubmitted,'%Y-%m-%d') >='{$startDate}' AND DATE_FORMAT(e.dateSubmitted,'%Y-%m-%d') <='{$endDate}'$searchAdd group by e.id order by e.dateSubmitted desc");
      
    if($dbData){
		$outJobs = array();
        $jobArray = array();
		foreach($dbData as $ante){
			
			if(!$idsearch){
			$user = "<a href=\"#escalations?id=".$ante['userId']."\" >" . $ante['last_name'].", ".$ante['first_name'] . "</a>";
			}else{
			$user = $ante['last_name'].", ".$ante['first_name'];
			}
			//$user = $ante['last_name'].", ".$ante['first_name'];
			if(intval($role)==11){
			$date = "<a href=\"javascript:;\" onclick=\"tj.getEscalationtable(" . $ante['id'] . ");\">" . $ante['dateSubmitted'] . "</a>" . " " . "<button type=\"button\" id=\"clearEsc\" class=\"btn btn-danger btn-sm\" onclick=\"tj.clearEsc(" . $ante['id'] . "); \">Del</button>";
			}else{
			$date = "<a href=\"javascript:;\" onclick=\"tj.getEscalationtable(" . $ante['id'] . ");\">" . $ante['dateSubmitted'] . "</a>";
			}
			$unit = $ante['dept'];
			$accountName = $ante['accountName'];
			if(Intval($role)>12){
			$unitName = $accountName . " - " . $unit;	
			}else{
			$unitName = $unit;	
			}
			$type = $ante['escalationName'];
			$note = $ante['note'] . " " . $ante['response'];
				
			
			$jobArray[] = array(
				'date' => $date,
				'unit' => $unitName,
				'user' => $user,
				'type' => $type,
				'notes' => $note,
				'accountName' => $accountName
            );
			}
			
			//$outJobs[] = $ante;
         	
		$outData = array();
		$outData['data'] = $jobArray;
		echo json_encode($outData,JSON_NUMERIC_CHECK);
		}else {
        $outData = array();
		$jobArray[] = array(
				'date' => '',
				'unit' => '',
				'user' => '',
				'type' => '',
				'notes' => ''
            );
        $outData['data'] = $jobArray;
        echo json_encode($outData,JSON_NUMERIC_CHECK);
		}
}

/////////////////////////////////////
// GET DAY RANK
function GetdayRankTableData() {
	$idsearch = isset($_REQUEST['id']) && ($_REQUEST['id'] != 0) ? $_REQUEST['id'] : '';
    $startDate = isset($_REQUEST['start']) ? $_REQUEST['start'] : date('yyyy-mm-dd');
    $endDate = isset($_REQUEST['end']) ? $_REQUEST['end'] : date('yyyy-mm-dd');
    $userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
	$countDept = Config::get('account')['countDept'];
	$role = Config::get('account')['role'];
	$enterpriseId = Config::get('account')['enterpriseId'];
	
	if(intval($role)<=8){
		$searchAdd = " AND e.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId} and `primaryUnit`>0)";
	}else if(intval($role)>8 && intval($role)<=12){
		$searchAdd = " AND d.accountId={$accountId}";
	}else if(intval($role)>12){
		$searchAdd = " AND t.enterpriseId={$enterpriseId}";
	}else{
		$searchAdd = " AND e.userId={$idsearch}";	
	}
	
	if($idsearch){
		$groupBy = " AND e.deptId ={$idsearch} GROUP BY e.id";
	}else{
		$groupBy = " GROUP BY e.deptId";
	}
	

    $dbData = Config::get('db')->get_results("SELECT e.*, SUM(e.empEngagement) as engageSum, SUM((CASE WHEN e.empEngagement>0 THEN 1 ELSE 0 END)) as engageCount, count(e.id) as eCount, t.name as accountName, t.enterpriseId, u.first_name, u.last_name, d.dept FROM `productiveDayRank` e LEFT JOIN `ProductiveDept` as d on d.id = e.deptId LEFT JOIN `productiveAccount` as t on t.id = d.accountId LEFT JOIN `productiveUser` as u on u.id = e.userId WHERE d.active>0 AND DATE_FORMAT(e.dayDate,'%Y-%m-%d') >='{$startDate}' AND DATE_FORMAT(e.dayDate,'%Y-%m-%d') <='{$endDate}'$searchAdd$groupBy order by e.dayDate desc, d.dept asc");
      
    if($dbData){
		$outJobs = array();
        $jobArray = array();
		foreach($dbData as $ante){			
			$countDept = intval($ante['countDept']);
			$unit = "<a href=\"#dayRankPage?id=".$ante['deptId']."\" >" . $ante['dept']. "</a>";
			
			if(intval($ante['dayRank'])==1){
				$dayR = "YES";
			}else if(intval($ante['dayRank'])==2){
				$dayR = "NO";
			}else{
				$dayR = '';
			}
			
			$action = '';	
			$date = '';
			$user = '';
			$dayRank = '';
			$empEngage = '';
			
			if(intval($ante['engageCount'])>0 && intval($ante['engageCount'])==intval($ante['eCount'])){
			$engageAvg = ROUND(intval($ante['engageSum']) / intval($ante['engageCount']),1);
			}else if(intval($ante['engageCount'])>0 && intval($ante['engageCount'])!=intval($ante['eCount'])){
			$engageAvg = ROUND(intval($ante['engageSum']) / intval($ante['engageCount']),1) . "*";
			$action = "* Missing Records"; 
			}else{
			$engageAvg = 0;
			}
			
			if($startDate==$endDate || $idsearch){
			$date = $ante['dayDate'];
			$user = $ante['last_name'];
			$dayRank = $dayR;
			$empEngage = $ante['empEngagement'];
			$action = "<button type=\"button\" id=\"editDayRankbutton\" class=\"btn btn-info btn-sm\" onclick=\"tj.getdayRank(" . $ante['id'] . "); \">Edit</button>";
			$engageAvg = $ante['empEngagement'];
			}
			
				//$accountName = $ante['accountName'];
			if(Intval($role)>12){
			$unitName = $accountName . " - " . $unit;	
			}else{
			$unitName = $unit;	
			}
			//$type = $ante['escalationName'];
			//$note = $ante['note'];
				
			
			$jobArray[] = array(
				'date' => $date,
				'unit' => $unitName,
				'user' => $user,
				'dayRank' => $dayRank,
				'engage' => $empEngage,
				'engageAvg' => $engageAvg,
				'action' => $action
            );
			}
			
			//$outJobs[] = $ante;
         	
		$outData = array();
		$outData['data'] = $jobArray;
		echo json_encode($outData,JSON_NUMERIC_CHECK);
		}else {
        $outData = array();
		$jobArray[] = array(
				'date' => '',
				'unit' => '',
				'user' => '',
				'dayRank' => '',
				'engage' => '',
				'engageAvg' => '',
				'action' => ''
            );
        $outData['data'] = $jobArray;
        echo json_encode($outData,JSON_NUMERIC_CHECK);
		}
}

/////////////////////////////////////
// GET DETAILS FOR DAY RATING
function GetDayRank() {
	$dataId = (isset($_REQUEST['dataId'])?$_REQUEST['dataId']:0);
	$newDate = (isset($_REQUEST['newDate'])?$_REQUEST['newDate']:date('Y-m-d'));
	$deptId = (isset($_REQUEST['deptId'])?$_REQUEST['deptId']:0);
		
		$userId = Config::get('account')['id'];
        $accountId = Config::get('account')['accountId'];
        $role = Config::get('account')['role'];
		$dbProd = Config::get('db')->get_results("select n.*, (SELECT COUNT(`deptId`) FROM `productiveDeptXref` WHERE `userId`={$userId} AND `primaryUnit`>0) as countDept, DATE_FORMAT(n.dayDate,'%m/%d/%y') as dayRankDate, d.dept, u.first_name, u.last_name from `productiveDayRank` n left outer join `ProductiveDept` as d on d.id=n.deptId left outer join `productiveUser` as u on u.id = n.userId where n.id={$dataId} OR (n.dayDate='{$newDate}' AND n.deptId={$deptId})");
        //$dbProd = Config::get('db')->get_results("select n.*, ROUND(n.chargecount,1) as chargecount1, ROUND(n.nvariance,0) as nvariance1, IFNULL(e.escalation,0) as escalationValue, IFNULL(e.note,'') as escalationNote, IFNULL(ae.escalation,'') as escalationType, z.procedureCount as todayProcs, z.actualWHP as todayWHP, z.plannedHours as todayHours, ROUND((z.actualWHP - a.hppd)* z.procedureCount,2) as todayVar, a.dept as depname, a.prodMeasure, a.hppd, ROUND((n.actualWHP - a.hppd)* n.procedureCount,2) as currentVar, n.id as dataId2, DATE_FORMAT(n.dayDate,'%m/%d/%Y') as reportdate, s.shift as reportshift, n.atotal+n.ltotal as totalpatients, a.totalbeds, a.churn, a.uosDesc, (a.oneto1 + a.oneto2 + a.oneto3 + a.oneto4 + a.oneto5 + a.oneto6) as acuityTotal,  a.oneto1 as acuity1, a.oneto2 as acuity2, a.oneto3 as acuity3, a.oneto4 as acuity4, a.oneto5 as acuity5, a.oneto6 as acuity6, a.desc1, a.desc2, a.desc3, a.desc4, a.desc5, a.desc6, a.skill1, a.skill2, a.skill3, a.skill4, a.skill5, a.skilldesc1, a.skilldesc2, a.skilldesc3, a.skilldesc4, a.skilldesc5, IFNULL(u.first_name,'') as first_name, IFNULL(u.last_name,'') as last_name, IFNULL(b.blockedBeds,0) as blockedBeds, a.totalbeds-n.atotal-n.ltotal-b.blockedBeds as openbeds from `productiveNewData` n left join `productiveShifts` as s on s.id = n.shift left join `ProductiveDept` as a on a.accountId = n.accountId and a.id = n.deptId left join `productiveUser` as u on u.id = n.userId left outer join `productiveblockedBeds` as b on b.accountId = n.accountId and b.deptId = n.deptId LEFT JOIN `productiveNewData` as z on z.deptId = n.deptId and z.dayDate = n.dayDate and z.whpPlan=1 LEFT OUTER JOIN `productiveEscalations` as e on e.dataId = n.id LEFT OUTER JOIN `productiveAcctEscalations` as ae on ae.id = e.escalation where n.accountId ={$accountId} AND n.id={$dataId} AND {$userId} IN (SELECT x.userId FROM `productiveDeptXref` x where x.deptId=n.deptId) ");
       		
		if($dbProd){
        $dbData = $dbProd[0];
        echo json_encode(array('success' => true,'data'=>$dbData));
        return true;
    }else{
    echo json_encode(array('success'=>false,'message'=>'Record not found.'));
	return false;
	}
}

/////////////////////////////////////
// GET DETAILS FOR DAY RATING
function EditDayRank() {
	$dataId = (isset($_REQUEST['dataId'])?$_REQUEST['dataId']:0);
	$sd = (isset($_REQUEST['sd'])?$_REQUEST['sd']:0);
	$ee = (isset($_REQUEST['ee'])?$_REQUEST['ee']:0);
	$dayDate1 = (isset($_REQUEST['dayDate'])?$_REQUEST['dayDate']:'');
	$dateNew1 = (isset($_REQUEST['dateNew'])?$_REQUEST['dateNew']:'');
	$dateExisting1 = (isset($_REQUEST['dateExisting'])?$_REQUEST['dateExisting']:'');
	$referrals = (isset($_REQUEST['referrals'])?$_REQUEST['referrals']:0);
	$deptId = (isset($_REQUEST['deptId'])?$_REQUEST['deptId']:'');
	$currentTime = (isset($_REQUEST['currentTime'])?$_REQUEST['currentTime']: '');
	$userId = Config::get('account')['id'];
	
	$dayDate = date('Y-m-d',strtotime($dayDate1));
	
	if($dateNew1 ==''){
		$dateNew = '0000-00-00';
	}else{
		$dateNew = date('Y-m-d',strtotime($dateNew1));
	}
	
	if($dateExisting1 == ''){
		$dateExisting = '0000-00-00';
	}else{
		$dateExisting = date('Y-m-d',strtotime($dateExisting1));
	}
		
	
	
		if($dataId && intval($dataId)!=0){
		$updatedata = array(
		'userId' => $userId,
		'submittedDate' => $currentTime,
		'dayRank' => $sd,
		'dateNew' => $dateNew,
		'dateExisting' => $dateExisting,
		'empEngagement' => $ee,
		'referrals' => $referrals,
		'd1' => $dateNew1,
		'e1' => $dateExisting1
		);
		$updateWhere = array('id' => $dataId);
				
		$updateDR = Config::get('db')->update('productiveDayRank',$updatedata,$updateWhere);
       
        echo json_encode(array('success' => true));
        return true;
    }else{
		$updatedata = array(
		'userId' => $userId,
		'submittedDate' => $currentTime,
		'dateNew' => $dateNew,
		'dateExisting' => $dateExisting,
		'dayRank' => $sd,
		'empEngagement' => $ee,
		'referrals' => $referrals,
		'd1' => $dateNew1,
		'e1' => $dateExisting1
		);
		$updateWhere = array('dayDate' =>$dayDate, 'deptId' =>$deptId);
				
		$updateDR = Config::get('db')->update('productiveDayRank',$updatedata,$updateWhere);
		
    echo json_encode(array('success'=>true, 'message'=>false));
	return true;
	}
}

/////////////////////////////////////
// CHECK FOR DUPES
function checkDay() {
	$dayDate = (isset($_REQUEST['dayDate'])?$_REQUEST['dayDate']:'');
	$deptId = (isset($_REQUEST['deptId'])?$_REQUEST['deptId']:'');
	
	if($deptId && $dayDate){	
	$dbCheck = Config::get('db')->get_results("select * FROM `productiveDayRank` where `dayDate`= '{$dayDate}' AND `deptId`={$deptId} AND `dayRank`>0 AND `empEngagement`>0");
    if($dbCheck){
	$dbTrue = $dbCheck[0];
    echo json_encode(array('message' =>true,'data'=>$dbTrue));
    return true;
    }else{		
    echo json_encode(array('message'=>false));
	return true;
	}
	}else{
	return false;	
	}
}

/////////////////////////////////////
// GET DETAILS FOR SINGLE USER
function GetProdDetails() {
	$dataId = (isset($_REQUEST['dataId']) ? $_REQUEST['dataId']:'');
		
		$userId = Config::get('account')['id'];
        $accountId = Config::get('account')['accountId'];
        $role = Config::get('account')['role'];
		$dbProd = Config::get('db')->get_results("select n.*, p.id as adminId, p.role, g.griddeptId, g.census, g.cn, g.rn, g.rn1, g.rn2, g.pct, g.sitter, g.sec, g.other1, g.other2, g.other3, g.dayNight, h.griddeptId as ggriddeptId, h.census as gcensus, h.cn as gcn, h.rn as grn, h.rn1 as grn1, h.rn2 as grn2, h.pct as gpct, h.sitter as gsitter, h.sec as gsec, h.other1 as gother1, h.other2 as gother2, h.other3 as gother3, h.dayNight as gdayNight, a.patientcountTitle, h.dow as gdow, IFNULL(a.customChurnLabel,0) as churnLabel, ROUND(n.chargecount,1) as chargecount1, ROUND(n.antecount,1) as antecount1, ROUND(n.nvariance,1) as nvariance1, IFNULL(e.escalation,0) as escalationValue, IFNULL(e.note,'') as escalationNote, IFNULL(ae.escalation,'') as escalationType, z.procedureCount as todayProcs, z.actualWHP as todayWHP, z.plannedHours as todayHours, ROUND((z.actualWHP - a.hppd)* z.procedureCount,2) as todayVar, IFNULL(a.other1Desc,0) as other1Label, a.showrntest, a.chargeDesc, IFNULL(a.sittersNEWDesc,0) as sittersLabel, IFNULL(a.other2Desc,0) as other2Label, IFNULL(a.nurse1Desc,0) as nurse1Label, IFNULL(a.nurse2Desc,0) as nurse2Label, IFNULL(a.other3Desc,0) as other3Label, a.sittersNEWDesc, a.uosDefault, a.unitCategory, a.trackdc, a.trackdata, a.roundrn, a.track1Desc, a.countcharge, a.actionPlan, a.rnCount, a.rn1Count, a.rn2Count, a.track2Desc, a.track3Desc, a.track4Desc, a.typetrack1, a.typetrack2, a.typetrack3, a.typetrack4, a.indHRS, a.indUOS, a.secView, a.indHRSDesc, a.indUOSDesc, a.uosDisplay, a.rnVariance as displayProd, a.dept as depname, a.nurseDesc, a.showVisits, a.patientCustomDivider, a.customWHP, a.customDesc, a.customWHP2, a.customDesc2, a.customWHP3, a.customDesc3, a.customWHP4, a.customDesc4, a.customWHP5, a.customDesc5, a.halfNurse, a.inshiftProd, a.prodMeasure, a.hppd, a.useGrid, a.nurse1, a.nurse1Desc, a.nurse2Desc, a.showrn, a.indMeasure, a.patientTotalDesc, a.patientTotalDesc2, ROUND(a.charge,1) as charge1, ROUND(n.hrsVariance,2) as currentVar, n.id as dataId2, DATE_FORMAT(DATE_SUB(n.dayDate, INTERVAL 1 DAY),'%m/%d/%Y') as reportdate2, DATE_FORMAT(n.dayDate,'%m/%d/%Y') as reportdate, s.shiftName as reportshift, (n.atotal+n.patientCount2) as totalpatients, a.totalbeds, a.churn, a.uosDesc, (a.oneto1 + a.oneto2 + a.oneto3 + a.oneto4 + a.oneto5 + a.oneto6) as acuityTotal, (a.oneto1 + a.oneto2 + a.oneto3 + a.oneto4 + a.oneto5 + a.oneto6) as acuityTotal2, a.oneto1Acuity, a.oneto2Acuity, a.oneto3Acuity, a.oneto4Acuity, a.oneto5Acuity, a.oneto6Acuity, a.oneto1 as acuity1, a.oneto2 as acuity2, a.oneto3 as acuity3, a.oneto4 as acuity4, a.oneto5 as acuity5, a.oneto6 as acuity6, a.oneto7 as acuity7, a.oneto7Acuity, a.oneto8 as acuity8, a.oneto8Acuity, a.oneto9 as acuity9, a.oneto9Acuity, c.varMessage, c.rnvarianceLive, a.oneto10 as acuity10, a.oneto10Acuity, a.oneto11 as acuity11, a.oneto11Acuity, a.desc1, a.desc2, a.desc3, a.desc4, a.desc5, a.desc6, a.desc7, a.desc8, a.desc9, a.desc10, a.desc11, a.skill1, a.skill2, a.skill3, a.skill4, a.skill5, a.skill6, a.skill7, a.skill8, a.skill9, a.skill10, a.skill11, IFNULL(a.secLabel,0) as sLabel, a.secLabel, IFNULL(a.techLabel,0) as tLabel, a.techLabel, a.skilldesc1, a.skilldesc2, a.skilldesc3, a.skilldesc4, a.skilldesc5, a.skilldesc6, a.skilldesc7, a.skilldesc8, a.skilldesc9, a.skilldesc10, a.skilldesc11, IFNULL(u.first_name,'') as first_name, IFNULL(u.last_name,'') as last_name, p.practiceId, IFNULL(b.blockedBeds,0) as blockedBeds, (a.totalbeds-n.atotal-n.patientCount2-b.blockedBeds) as openbeds, (n.chargecount - g.cn) as cnvar, (n.antecount - g.rn) as rnvar, (n.techcount - g.pct) as pctvar, (n.seccount - g.sec) as secvar, (n.customNurse - g.rn1) as rn1var, (n.customNurse2 - g.rn2) as rn2var, (n.otherNurse1 - g.other1) as other1var, (n.otherNurse2 - g.other2) as other2var, (n.otherNurse3 - g.other3) as other3var, (n.sittercount - g.sitter) as sitvar, (n.chargecount - h.cn) as gcnvar, (n.antecount - h.rn) as grnvar, (n.techcount - h.pct) as gpctvar, (n.seccount - h.sec) as gsecvar, (n.customNurse - h.rn1) as grn1var, (n.customNurse2 - h.rn2) as grn2var, (n.otherNurse1 - h.other1) as gother1var, (n.otherNurse2 - h.other2) as gother2var, (n.otherNurse3 - h.other3) as gother3var, (n.sittercount - h.sitter) as gsitvar from `productiveNewData` n left outer join `productiveStaffingGrid` as h on h.griddeptId=n.deptId AND (h.dayNight=n.shift OR h.dayNight=((n.shift)-1)) AND h.dow=DAYOFWEEK(n.dayDate) left outer join `productiveStaffingGrid` as g on g.griddeptId=n.deptId and g.dayNight=n.shift and g.census=(n.atotal + n.patientCount2) left join `productiveAccount` as c on c.id=n.accountId left join `productiveAccountXref` as s on s.shift = n.shift AND s.dept=n.deptId left join `ProductiveDept` as a on a.accountId = n.accountId and a.id = n.deptId left join `productiveUser` as p on p.id={$userId} left join `productiveUser` as u on u.id = n.userId left outer join `productiveblockedBeds` as b on b.accountId = n.accountId and b.deptId = n.deptId LEFT JOIN `productiveNewData` as z on z.deptId = n.deptId and z.dayDate = n.dayDate and z.shift=n.shift LEFT OUTER JOIN `productiveEscalations` as e on e.dataId = n.id LEFT OUTER JOIN `productiveAcctEscalations` as ae on ae.id = e.escalation where n.accountId ={$accountId} AND n.id={$dataId} group by n.id");
        //$dbProd = Config::get('db')->get_results("select n.*, ROUND(n.chargecount,1) as chargecount1, ROUND(n.nvariance,0) as nvariance1, IFNULL(e.escalation,0) as escalationValue, IFNULL(e.note,'') as escalationNote, IFNULL(ae.escalation,'') as escalationType, z.procedureCount as todayProcs, z.actualWHP as todayWHP, z.plannedHours as todayHours, ROUND((z.actualWHP - a.hppd)* z.procedureCount,2) as todayVar, a.dept as depname, a.prodMeasure, a.hppd, ROUND((n.actualWHP - a.hppd)* n.procedureCount,2) as currentVar, n.id as dataId2, DATE_FORMAT(n.dayDate,'%m/%d/%Y') as reportdate, s.shift as reportshift, n.atotal+n.ltotal as totalpatients, a.totalbeds, a.churn, a.uosDesc, (a.oneto1 + a.oneto2 + a.oneto3 + a.oneto4 + a.oneto5 + a.oneto6) as acuityTotal,  a.oneto1 as acuity1, a.oneto2 as acuity2, a.oneto3 as acuity3, a.oneto4 as acuity4, a.oneto5 as acuity5, a.oneto6 as acuity6, a.desc1, a.desc2, a.desc3, a.desc4, a.desc5, a.desc6, a.skill1, a.skill2, a.skill3, a.skill4, a.skill5, a.skilldesc1, a.skilldesc2, a.skilldesc3, a.skilldesc4, a.skilldesc5, IFNULL(u.first_name,'') as first_name, IFNULL(u.last_name,'') as last_name, IFNULL(b.blockedBeds,0) as blockedBeds, a.totalbeds-n.atotal-n.ltotal-b.blockedBeds as openbeds from `productiveNewData` n left join `productiveShifts` as s on s.id = n.shift left join `ProductiveDept` as a on a.accountId = n.accountId and a.id = n.deptId left join `productiveUser` as u on u.id = n.userId left outer join `productiveblockedBeds` as b on b.accountId = n.accountId and b.deptId = n.deptId LEFT JOIN `productiveNewData` as z on z.deptId = n.deptId and z.dayDate = n.dayDate and z.whpPlan=1 LEFT OUTER JOIN `productiveEscalations` as e on e.dataId = n.id LEFT OUTER JOIN `productiveAcctEscalations` as ae on ae.id = e.escalation where n.accountId ={$accountId} AND n.id={$dataId} AND {$userId} IN (SELECT x.userId FROM `productiveDeptXref` x where x.deptId=n.deptId) ");
       	$prData = Config::get('db')->get_results("select u.id, u.last_name, u.first_name, d.dept from `productiveUser` u left join `productiveDeptXref` as x on x.userId=u.id left join `productiveNewData` as n on n.deptId=x.deptId left join `ProductiveDept` as d on d.id = n.deptId where n.id={$dataId} and u.role=3 group by u.id order by u.last_name asc");
		$i=0;
		$outpr = array();
		//$outdata = array();
		if($prData){
		foreach($prData as $prDetail){
			$outpr['id'.$i.''] = $prDetail['id'];
			$outpr['last'.$i.''] = $prDetail['last_name'];
			$outpr['first'.$i.''] = $prDetail['first_name'];
			$outpr['dept'.$i.''] = $prDetail['dept'];
			$i=$i+1;
		}
		//$outdata[] = $outpr;
		}
		//$userData = Config::get('db')->get_results("select x.*, u.last_name, u.first_name FROM `productiveDeptXref` x left join `productiveUser` as u on u.id=x.userId left join `productiveNewData` as n on n.deptId=x.deptId where n.id={$dataId} and u.practiceId=0 group by x.userId order by u.last_name asc");
		//$j=0;
		
		//$outuser .= '';
		//$outdata = array();
		//if($userData){
		//foreach($userData as $userDetail){
		//	$outuser .="<option value=\"" . $userDetail['userId'] . "\">" . $userDetail['last_name'] . ", " . $userDetail['first_name'] . "</option>";
		//}
		//$outdata[] = $outpr;
		//}
		
		
		if($dbProd){
        $dbData = $dbProd[0];
		//$userD = $userData[0];
        echo json_encode(array('success' => true,'data'=>$dbData,'providers'=>$prData), JSON_NUMERIC_CHECK);
        return true;
    }else{
    echo json_encode(array('success'=>false,'message'=>'Record not found.'));
	return false;
	}
}

/////////////////////////////////////
// GET DETAILS FOR SINGLE USER
function gridUpdate() {
	$ccount=0;
	$userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
	
	$census = (isset($_REQUEST['census']) ? $_REQUEST['census']:'');
	$deptId = (isset($_REQUEST['deptId']) ? $_REQUEST['deptId']:'');
	$shift = (isset($_REQUEST['shift']) ? $_REQUEST['shift']:'');
	$useGrid = (isset($_REQUEST['useGrid']) ? $_REQUEST['useGrid']:0);
	$dayDate = (isset($_REQUEST['dayDate']) ? $_REQUEST['dayDate']:0);
	
	$oneto1 = (isset($_REQUEST['oneto1']) ? $_REQUEST['oneto1']:0);
	$oneto2 = (isset($_REQUEST['oneto2']) ? $_REQUEST['oneto2']:0);
	$oneto3 = (isset($_REQUEST['oneto3']) ? $_REQUEST['oneto3']:0);
	$oneto4 = (isset($_REQUEST['oneto4']) ? $_REQUEST['oneto4']:0);
	$oneto5 = (isset($_REQUEST['oneto5']) ? $_REQUEST['oneto5']:0);
	$oneto6 = (isset($_REQUEST['oneto6']) ? $_REQUEST['oneto6']:0);
	$oneto7 = (isset($_REQUEST['oneto7']) ? $_REQUEST['oneto7']:0);
	$oneto8 = (isset($_REQUEST['oneto8']) ? $_REQUEST['oneto8']:0);
	$oneto9 = (isset($_REQUEST['oneto9']) ? $_REQUEST['oneto9']:0);
	$oneto10 = (isset($_REQUEST['oneto10']) ? $_REQUEST['oneto10']:0);
	$oneto11 = (isset($_REQUEST['oneto11']) ? $_REQUEST['oneto11']:0);
	$acuityTotal = (isset($_REQUEST['acuityTotal']) ? $_REQUEST['acuityTotal']:0);
	
	
		
	$ccount1 = intval($oneto1) + intval($oneto2) + intval($oneto3) + intval($oneto4) + intval($oneto5) + intval($oneto6) + intval($oneto7) + intval($oneto8) + intval($oneto9) + intval($oneto10) + intval($oneto11);
    
	if(intval($acuityTotal)==1){
		$ccount = $ccount1;
	}else{
		$ccount = $census;
	}
	
	if(intval($useGrid)==2){
		$gridtype = "`griddeptId`={$deptId} AND `dayNight`={$shift} AND `dow`=DAYOFWEEK('{$dayDate}')";
	}else{
		$gridtype = "`griddeptId`={$deptId} AND `dayNight`={$shift} AND `census`={$ccount}";
	}
		
		if($ccount>0 && $deptId && $shift){
		$dbProd = Config::get('db')->get_results("select * from `productiveStaffingGrid` where $gridtype");
       	}else{
		$dbProd = false;
		}
	
	if($dbProd){
        $dbData = $dbProd[0];
        echo json_encode(array('success' => true,'data'=>$dbData), JSON_NUMERIC_CHECK);
        return true;
    }else{
    echo json_encode(array('success'=>false,'message'=>'Record not found.'));
	return false;
	}
}

/////////////////////////////////////
// GET DETAILS FOR SINGLE USER
function GetCrashDetails() {
	$dataId = (isset($_REQUEST['dataId'])?$_REQUEST['dataId']:'');
		
		$userId = Config::get('account')['id'];
        $accountId = Config::get('account')['accountId'];
        $role = Config::get('account')['role'];
		$dbProd = Config::get('db')->get_results("select c.*, d.dept, u.first_name, u.last_name FROM `crashcartlogs` c LEFT OUTER JOIN `ProductiveDept` as d on d.id=c.deptId LEFT OUTER JOIN `productiveUser` as u on u.id=c.userId WHERE c.id={$dataId} GROUP BY c.id");
        //$dbProd = Config::get('db')->get_results("select n.*, ROUND(n.chargecount,1) as chargecount1, ROUND(n.nvariance,0) as nvariance1, IFNULL(e.escalation,0) as escalationValue, IFNULL(e.note,'') as escalationNote, IFNULL(ae.escalation,'') as escalationType, z.procedureCount as todayProcs, z.actualWHP as todayWHP, z.plannedHours as todayHours, ROUND((z.actualWHP - a.hppd)* z.procedureCount,2) as todayVar, a.dept as depname, a.prodMeasure, a.hppd, ROUND((n.actualWHP - a.hppd)* n.procedureCount,2) as currentVar, n.id as dataId2, DATE_FORMAT(n.dayDate,'%m/%d/%Y') as reportdate, s.shift as reportshift, n.atotal+n.ltotal as totalpatients, a.totalbeds, a.churn, a.uosDesc, (a.oneto1 + a.oneto2 + a.oneto3 + a.oneto4 + a.oneto5 + a.oneto6) as acuityTotal,  a.oneto1 as acuity1, a.oneto2 as acuity2, a.oneto3 as acuity3, a.oneto4 as acuity4, a.oneto5 as acuity5, a.oneto6 as acuity6, a.desc1, a.desc2, a.desc3, a.desc4, a.desc5, a.desc6, a.skill1, a.skill2, a.skill3, a.skill4, a.skill5, a.skilldesc1, a.skilldesc2, a.skilldesc3, a.skilldesc4, a.skilldesc5, IFNULL(u.first_name,'') as first_name, IFNULL(u.last_name,'') as last_name, IFNULL(b.blockedBeds,0) as blockedBeds, a.totalbeds-n.atotal-n.ltotal-b.blockedBeds as openbeds from `productiveNewData` n left join `productiveShifts` as s on s.id = n.shift left join `ProductiveDept` as a on a.accountId = n.accountId and a.id = n.deptId left join `productiveUser` as u on u.id = n.userId left outer join `productiveblockedBeds` as b on b.accountId = n.accountId and b.deptId = n.deptId LEFT JOIN `productiveNewData` as z on z.deptId = n.deptId and z.dayDate = n.dayDate and z.whpPlan=1 LEFT OUTER JOIN `productiveEscalations` as e on e.dataId = n.id LEFT OUTER JOIN `productiveAcctEscalations` as ae on ae.id = e.escalation where n.accountId ={$accountId} AND n.id={$dataId} AND {$userId} IN (SELECT x.userId FROM `productiveDeptXref` x where x.deptId=n.deptId) ");
       		
		if($dbProd){
        $dbData = $dbProd[0];
        echo json_encode(array('success' => true,'data'=>$dbData));
        return true;
    }else{
    echo json_encode(array('success'=>false,'message'=>'Record not found.'));
	return false;
	}
}

function logView() {
	$dataId = (isset($_REQUEST['dataId'])?$_REQUEST['dataId']:'');	
	$userId = Config::get('account')['id'];
	$curTime = (isset($_REQUEST['currentTime'])) ? $_REQUEST['currentTime'] : time();
	$viewDate = strtotime($curTime);	
	
	
	if($dataId){
	$data = array(
            'dataId' =>$dataId,
			'userId' =>$userId,
			'viewDate' =>date("Y-m-d H:i:s",$viewDate)
        );
            
	$newView = Config::get('db')->insert('productiveviewLog',$data); 

        echo json_encode(array('success' => true));
        return true;
	}else{
    echo json_encode(array('success'=>false,'message'=>'update failed.'));
    return false;
	}
}

function UpdateCrash() {
	$dataId = (isset($_REQUEST['dataId'])?$_REQUEST['dataId']:'');
	$opencrash = (isset($_REQUEST['opencrash'])?$_REQUEST['opencrash']:0);
	$locked = (isset($_REQUEST['locked'])?$_REQUEST['locked']:0);
	$front = (isset($_REQUEST['front'])?$_REQUEST['front']:'');
	$toplock = (isset($_REQUEST['toplock'])?$_REQUEST['toplock']:'');
	$plugged = (isset($_REQUEST['plugged'])?$_REQUEST['plugged']:0);
	$unplugged = (isset($_REQUEST['unplugged'])?$_REQUEST['unplugged']:0);
	$ambubag = (isset($_REQUEST['ambubag'])?$_REQUEST['ambubag']:0);
	$o2flow = (isset($_REQUEST['o2flow'])?$_REQUEST['o2flow']:0);
	$o2tank = (isset($_REQUEST['o2tank'])?$_REQUEST['o2tank']:0);
	$intulock = (isset($_REQUEST['intulock'])?$_REQUEST['intulock']:'');
	$ecgpaper = (isset($_REQUEST['ecgpaper'])?$_REQUEST['ecgpaper']:0);
	$ecgpads = (isset($_REQUEST['ecgpads'])?$_REQUEST['ecgpads']:0);
	$quickcombo = (isset($_REQUEST['quickcombo'])?$_REQUEST['quickcombo']:0);
	$amaalgo = (isset($_REQUEST['amaalgo'])?$_REQUEST['amaalgo']:0);
	
	$userId = Config::get('account')['id'];
	
	
	if(intval($dataId)>0){
	$data = array(
            'unitOpen' =>$opencrash,
			'cartLocked' =>$locked,
			'frontLockNum' =>$locked,
			'topLockNum' =>$toplock,
			'passusertestplugged' =>$plugged,
			'passusertestunplugged' =>$unplugged,
			'ambubag' =>$ambubag,
			'o2flow' =>$o2flow,
			'o2tank' =>$o2tank,
			'intuLockNum' =>$intulock,
			'ecgpaper' =>$ecgpaper,
			'ecgpads' =>$ecgpads,
			'combopads' =>$quickcombo,
			'amaalgo' =>$amaalgo,
			'userId' =>$userId
        );
	$updateWhere = array('id' =>$dataId);
            
	$newBlock = Config::get('db')->update('crashcartlogs',$data,$updateWhere); 

        echo json_encode(array('success' => true));
        return true;
	}else{
    echo json_encode(array('success'=>false,'message'=>'update failed.'));
    return false;
	}
}

/////////////////////////////////////
// GET 	Visits
function GetVisits() {
	$dataId = (isset($_REQUEST['dataId'])?$_REQUEST['dataId']:'');
		
		$userId = Config::get('account')['id'];
        $accountId = Config::get('account')['accountId'];
        $role = Config::get('account')['role'];
		$dbVisits = Config::get('db')->get_results("select n.*, a.uosDesc, ROUND(n.chargecount,1) as chargecount1, (select count(`dept`) from `productiveAccountXref` where `type`<2 AND `whpPlan` !=1 and `dept`=n.deptId) as shiftCount, (select count(`dayDate`) from `productiveNewData` where `deptId`=n.deptId and `userId`>0 and `whpPlan`=0 and `type`<2 and `dayDate`=n.dayDate) as shiftActual, ROUND(n.nvariance,0) as nvariance1, IFNULL(e.escalation,0) as escalationValue, IFNULL(e.note,'') as escalationNote, IFNULL(ae.escalation,'') as escalationType, z.procedureCount as todayProcs, z.actualWHP as todayWHP, z.plannedHours as todayHours, ROUND((z.actualWHP - a.hppd)* z.procedureCount,2) as todayVar, a.dept as depname, a.visitHoursReqd, a.prodMeasure, a.hppd, a.hrs1Calc, a.hrs2Calc, a.hrs3Calc, a.hrs1Desc, a.hrs2Desc, a.hrs3Desc, ROUND((n.actualWHP - a.hppd)* n.procedureCount,2) as currentVar, n.id as dataId2, DATE_FORMAT(n.dayDate,'%m/%d/%Y') as reportdate, s.shiftName as reportshift, (n.atotal+n.patientCount2) as totalpatients, a.totalbeds, a.churn, a.uosDesc, (a.oneto1 + a.oneto2 + a.oneto3 + a.oneto4 + a.oneto5 + a.oneto6) as acuityTotal,  a.oneto1 as acuity1, a.oneto2 as acuity2, a.oneto3 as acuity3, a.oneto4 as acuity4, a.oneto5 as acuity5, a.oneto6 as acuity6, a.desc1, a.desc2, a.desc3, a.desc4, a.desc5, a.desc6, a.skill1, a.skill2, a.skill3, a.skill4, a.skill5, a.skilldesc1, a.skilldesc2, a.skilldesc3, a.skilldesc4, a.skilldesc5, IFNULL(u.first_name,'') as first_name, IFNULL(u.last_name,'') as last_name, IFNULL(b.blockedBeds,0) as blockedBeds, a.totalbeds-n.atotal-n.patientCount2-b.blockedBeds as openbeds from `productiveNewData` n left join `productiveAccountXref` as s on s.shift = n.shift and s.dept = n.deptId left join `ProductiveDept` as a on a.accountId = n.accountId and a.id = n.deptId left join `productiveUser` as u on u.id = n.userId left outer join `productiveblockedBeds` as b on b.accountId = n.accountId and b.deptId = n.deptId LEFT JOIN `productiveNewData` as z on z.deptId = n.deptId and z.dayDate = n.dayDate and z.shift=n.shift LEFT OUTER JOIN `productiveEscalations` as e on e.dataId = n.id LEFT OUTER JOIN `productiveAcctEscalations` as ae on ae.id = e.escalation where n.id={$dataId} group by n.id");
               		
		if($dbVisits){
        $dbData = $dbVisits[0];
        echo json_encode(array('success' => true,'data'=>$dbData));
        return true;
    }else{
    echo json_encode(array('success'=>false,'message'=>'Record not found.'));
	return false;
	}
}

/////////////////////////////////////
// GET 	Visits
function UpdateVisits() {
	$dataId = (isset($_REQUEST['dataId']) ? $_REQUEST['dataId']:'');
	$dayDate = (isset($_REQUEST['dayDate']) ? $_REQUEST['dayDate']:'');
	$accountId = (isset($_REQUEST['accountId']) ? $_REQUEST['accountId']:'');
	$deptId = (isset($_REQUEST['deptId']) ? $_REQUEST['deptId']:'');
	$visitTotal = (isset($_REQUEST['visitTotal']) ? $_REQUEST['visitTotal']:0);
	$hoursTotal = (isset($_REQUEST['hoursTotal']) ? floatval($_REQUEST['hoursTotal']):0);
	$hrs1 = (isset($_REQUEST['hrs1']) ? floatval($_REQUEST['hrs1']):0);
	$hrs2 = (isset($_REQUEST['hrs2']) ? floatval($_REQUEST['hrs2']):0);
	$hrs3 = (isset($_REQUEST['hrs3']) ? floatval($_REQUEST['hrs3']):0);
	//$hoursrqd = (isset($_REQUEST['hoursrqd']) ? intval($_REQUEST['hoursrqd']):1);
	
	
	$addHours = 0;
	
		if($dayDate && $deptId && intval($visitTotal)>=0){			
				$updateProd = Config::get('db')->get_results("select n.*, (select count(`dept`) from `productiveAccountXref` where `type`<2 AND `whpPlan` !=1 and `dept`=n.deptId) as shiftCount, (select count(`dayDate`) from `productiveNewData` where `deptId`=n.deptId and `userId`>0 and `whpPlan`=0 and `dayDate`=n.dayDate) as shiftActual, (select (SUM(`atotal`) + SUM(`ltotal`)) from `productiveNewData` where `deptId`=n.deptId and `userId`>0 and `whpPlan`=0 and `dayDate`=n.dayDate) as shiftAvg, h.shiftsperDay, s.value, h.visitHoursReqd, h.hppd, h.hrs1Calc, h.hrs2Calc, h.hrs3Calc, a.productivityPosNeg, h.prodMeasure from `productiveNewData` n left join `productiveAccount` as a on a.id = n.accountId left join `ProductiveDept` as h on h.accountId = n.accountId and h.id=n.deptId left join `productiveAccountXref` as s on s.dept = n.deptId and s.shift = n.shift where n.dayDate='{$dayDate}' AND n.accountId={$accountId} AND n.deptId={$deptId} group by n.shift order by n.shift DESC");
							
				$prodCalc = intval($updateProd[0]['productivityPosNeg']);
				$hoursrqd = intval($updateProd[0]['visitHoursReqd']);
				$hrs1Calc = intval($updateProd[0]['hrs1Calc']);
				$hrs2Calc = intval($updateProd[0]['hrs2Calc']);
				$hrs3Calc = intval($updateProd[0]['hrs3Calc']);
				
				if($hrs1Calc>1){
					$hrsCalc1 = $hrs1Calc - 2;
				}else{
					$hrsCalc1 = $hrs1Calc;
				}
				
				if($hrs2Calc>1){
					$hrsCalc2 = $hrs2Calc - 2;
				}else{
					$hrsCalc2 = $hrs2Calc;
				}
				
				if($hrs3Calc>1){
					$hrsCalc3 = $hrs3Calc - 2;
				}else{
					$hrsCalc3 = $hrs3Calc;
				}
				
				$newTotal = $hoursTotal+($hrs1 * $hrsCalc1)+($hrs2 * $hrsCalc2)+($hrs3 * $hrsCalc3);
				
				$AddResource = Config::get('db')->get_results("select p.id, p.deptId, sum(p.Sun) as SunTotal, sum(p.Mon) as MonTotal, sum(p.Tue) as TueTotal, sum(p.Wed) as WedTotal, sum(p.Thu) as ThuTotal, sum(p.Fri) as FriTotal, sum(p.Sat) as SatTotal, DAYOFWEEK('{$dayDate}') as day FROM `productiveResources` p where p.deptId={$deptId}");
		
				if($AddResource){
				$dbDay = $AddResource[0]['day'];;
				
				switch ($dbDay) {
				case "1":
					$addHours = floatval($AddResource[0]['SunTotal']);
					break;
				case "2":
					$addHours = floatval($AddResource[0]['MonTotal']);
					break;
				case "3":
					$addHours = floatval($AddResource[0]['TueTotal']);
					break;
				case "4":
					$addHours = floatval($AddResource[0]['WedTotal']);
					break;
				case "5":
					$addHours = floatval($AddResource[0]['ThuTotal']);
					break;
				case "6":
					$addHours = floatval($AddResource[0]['FriTotal']);
					break;
				case "7":
					$addHours = floatval($AddResource[0]['SatTotal']);
					break;
				default:
					$addHours = 0;
				}
			}
				if($addHours>0 && $shiftCount>0){
				$addHrs = $addHours;
				}else{
				$addHrs = 0;
				}
				
			foreach($updateProd as $prodUpdate){
				$userID = intval($prodUpdate['userId']);				
				$prodId = $prodUpdate['id'];
				$shiftAvg = intval($prodUpdate['shiftAvg']);
				$shiftCount = intval($prodUpdate['shiftCount']);
				$shiftActual = intval($prodUpdate['shiftActual']);
				$hppd = floatval($prodUpdate['hppd']);
				
				if($shiftCount==$shiftActual && intval($visitTotal)>0){
					$shiftCalc = (intval($prodUpdate['atotal']) + intval($prodUpdate['ltotal'])) / $shiftAvg;
					//$shiftCalc = .5;
				}else{
					$shiftCalc = 1 / $shiftCount;
				}
				
				if(intval($newTotal)>0){
					$antetotal = floatval($visitTotal);
					$antehours = $antetotal * $hppd;
					$aprodCalc = ((1+(($antehours - $newTotal) / $antehours))*100);
					$aprod = $aprodCalc * $shiftCount * $shiftCalc;
					$aproductivity = round($aprod,1);
					$whpuos = ($antetotal / $newTotal) * $shiftCalc;
					$hrsVariance = round(((($antetotal * $hppd) - ($newTotal)) * $shiftCalc) * $prodCalc,3);
					$shiftVisits = 0;
				}else if($hoursrqd==0 && $userID>0){
					$chargecount = $prodUpdate['chargecount'];
					$nurse1count = $prodUpdate['customNurse'];
					$other1count = $prodUpdate['otherNurse1'];
					$other2count = $prodUpdate['otherNurse2'];
					$other3count = $prodUpdate['otherNurse3'];
					$techcount = $prodUpdate['techcount'];
					$seccount = $prodUpdate['seccount'];
					$nursecount = $prodUpdate['antecount'];
					$sittercount = $prodUpdate['sittercount'];
					$antetotal = floatval($visitTotal);
					$antenurses = ((((floatval($nursecount) + floatval($chargecount) + intval($nurse1count) + intval($other1count) + intval($other2count) + intval($other3count) + intval($seccount) + intval($sittercount) + intval($techcount)) * 24) + $addHrs) / $shiftCount);
					$shiftVisits = $antetotal * $shiftCalc * $hppd;
					$aprodCalc = ((1+(($shiftVisits - $antenurses) / $shiftVisits))*100);
					$aprod = $aprodCalc * $shiftCount * $shiftCalc;
					//$aprod = (($shiftVisits * $hppd) / $antenurses)*100;
					$aproductivity = round($aprod,1);
					$whpuos = round(($antenurses / $shiftVisits),3);
					$hrsVariance = round(((($shiftVisits * $hppd) - ($antenurses)) * $prodCalc),3);
				}else{
					$aproductivity=0.00;
					$shiftVisits = 0;
					$antenurses = 0;
					$hrsVariance = 0.00;
					$whpuos = 0.00;					
				}
				
				$data = array(
					'aproductivity' => $aproductivity,
					'nproductivity' => $aproductivity,
					'visits' => $shiftVisits,
					'totalHours' => $antenurses,
					'hrsVarianceActual' => $hrsVariance,
					'actualWHPimport' => $whpuos,
					'visitsSubmitted' => $visitTotal,
					'hoursSubmitted' => $hoursTotal,
					'hrs1Submitted' => $hrs1,
					'hrs2Submitted' => $hrs2,
					'hrs3Submitted' => $hrs3
					
				);
				$updateWhere = array('id' => $prodId);
				$newProd = Config::get('db')->update('productiveNewData',$data,$updateWhere);
				}
				echo json_encode(array('success' => true,'updated'=>true));
				return true;
				}else{
				echo json_encode(array('success'=>true,'updated'=>false));
				return true;
				}
}

/////////////////////////////////////
// Clear Nursing Record
function clearRecord() {
	$dataId = (isset($_REQUEST['dataId']) ? $_REQUEST['dataId']:'');
		
		$dbProd = Config::get('db')->get_results("SELECT n.*, d.charge, IFNULL(e.id,'') as escalationId from `productiveNewData` n LEFT JOIN `ProductiveDept` as d on d.id=n.deptId LEFT OUTER JOIN `productiveEscalations` as e on e.dataId = n.id WHERE n.id={$dataId} group by n.id");
     if($dbProd){
        $dbData = $dbProd[0];
		$escId = $dbData['escalationId'];
		$charge = $dbData['charge'];
		$note = '';
		$data = array(
				'chargecount' =>$charge,
                'techcount' =>0,
				'aptechcount' =>0,
				'seccount' =>0,
				'sittercount' =>0,
				'antecount' =>0,
				'userId' =>0,
				'acs' =>0,
				'am1' =>0,
                'awcm' =>0,
				'obed' =>0,
				'obed1' =>0,
				'ldcount' =>0,
				'ocount' =>0,
                'ev' =>0,
				'scs' =>0,
				'cr' =>0,
				'pt' =>0,
                'ccs' =>0,
				'ps1' =>0,
				'pp' =>0,
				'avariance' =>0,
				'lvariance' =>0,
				'nvariance' =>0,
				'hrsVariance' =>0,
				'hrsVarianceActual' =>0,
				'atotal' =>0,
				'patientCount2' =>0,
				'ltotal' =>0,
				'aproductivity' =>0,
				'note' =>$note,
				'lproductivity' =>0,
				'nproductivity' =>0,
				'procedureCount' =>0.0,
				'actualWHP' =>0.0,
				'oneto1' =>0,
				'oneto2' =>0,
				'oneto3' =>0,
				'oneto4' =>0,
				'oneto5' =>0,
				'oneto6' =>0,
				'oneto7' =>0,
				'oneto8' =>0,
				'oneto9' =>0,
				'oneto10' =>0,
				'twoto1' =>0,
				'skill1val' =>0.0,
				'skill2val' =>0.0,
				'skill3val' =>0.0,
				'skill4val' =>0.0,
				'skill5val' =>0.0,
				'skill6val' =>0.0,
				'skill7val' =>0.0,
				'skill8val' =>0.0,
				'skill9val' =>0.0,
				'skill10val' =>0.0,
				'skill11val' =>0.0,
				'addResourceHrs' =>0,
				'plannedHours' =>0.0,
				'plannedProcs' =>0.0,
				'budgetValue' =>0.0,
				'admits' =>0,
				'transfers' =>0,
				'discharges' =>0,
				'churnValue' =>0.0,
				'customNurse' =>0,
				'customNurse2' =>0,
				'otherNurse1' =>0,
				'otherNurse2' =>0,
				'otherNurse3' =>0,
				'activeRecord' =>0,
				'hrsVariance' =>0,
				'budgetVar' =>0,
				'track1' =>0,
				'track2' =>0,
				'track3' =>0,
				'track4' =>0,
				'gvariance' =>0.00,
				'notetrack1' =>'',
				'notetrack2' =>'',
				'notetrack3' =>'',
				'notetrack4' =>'',
				'submittedby' =>'',
				'countDischarges' =>0,
				'customProd' =>0.00,
				'gridrnVariance' =>0,
				'gridrnProductivity' =>0.00,
				'gbudgetVar' =>0.00,
				'rbudgetVar' =>0.00,
				'gridrnbudgetVar' =>0.00,
				'ghrsVariance' =>0.00,
				'ahrsVariance' =>0.00,
				'rhrsVariance' =>0.00,
				'gridrnhrsVariance' =>0.00,
				'submitCount' =>0
				);
		$where = array(
				'id'=>$dataId
				);
		Config::get('db')->update('productiveNewData',$data,$where);
		
		if($escId){
		$whereEsc = array(
				'dataId'=>$dataId
				);
		Config::get('db')->delete('productiveEscalations',$whereEsc);
		}else{
		//do nothing;	
		}
        echo json_encode(array('success' => true,'data'=>$dbData));
        return true;
    }else{
    echo json_encode(array('success'=>false,'message'=>'Record not found.'));
	return false;
	}
}

/////////////////////////////////////
// Clear Escalation
function clearEscalation() {
	$escId = (isset($_REQUEST['escId']) ? $_REQUEST['escId']:'');
		
	$dbProd = Config::get('db')->get_results("SELECT * from `productiveEscalations` WHERE `id`={$escId}");
    
	if($dbProd){
		
		$updateEsc = array(
				'active'=>0
				);
        
		$whereEsc = array(
				'id'=>$escId
				);
		Config::get('db')->update('productiveEscalations',$updateEsc,$whereEsc);

        echo json_encode(array('success' => true,'message'=>'Record Archived.'));
        return true;
    }else{
    echo json_encode(array('success'=>false,'message'=>'Record not found.'));
	return false;
	}
}

/////////////////////////////////////
// Clear WHP Record
function clearWHPRecord() {
	$dataId = (isset($_REQUEST['dataId']) ? $_REQUEST['dataId']:'');
		
		$dbProd = Config::get('db')->get_results("SELECT n.*, IFNULL(e.id,'') as escalationId from `productiveNewData` n LEFT OUTER JOIN `productiveEscalations` as e on e.dataId = n.id WHERE n.id={$dataId} group by n.id");
     if($dbProd){
        $dbData = $dbProd[0];
		$type = $dbData['type'];
		$escId = $dbData['escalationId'];
		$note = '';
	if(intval($type)!=2){
		$data = array(
				'chargecount' =>0,
                'techcount' =>0,
				'aptechcount' =>0,
				'seccount' =>0,
				'sittercount' =>0,
				'antecount' =>0,
				'userId' =>0,
				'acs' =>0,
				'am1' =>0,
                'awcm' =>0,
				'obed' =>0,
				'obed1' =>0,
				'ldcount' =>0,
				'ocount' =>0,
                'ev' =>0,
				'scs' =>0,
				'cr' =>0,
				'pt' =>0,
                'ccs' =>0,
				'ps1' =>0,
				'pp' =>0,
				'avariance' =>0,
				'lvariance' =>0,
				'nvariance' =>0,
				'hrsVariance' =>0,
				'hrsVarianceActual' =>0,
				'atotal' =>0,
				'patientCount2' =>0,
				'ltotal' =>0,
				'aproductivity' =>0,
				'note' =>$note,
				'lproductivity' =>0,
				'nproductivity' =>0,
				'procedureCount' =>0.0,
				'actualWHP' =>0.0,
				'oneto1' =>0,
				'oneto2' =>0,
				'oneto3' =>0,
				'oneto4' =>0,
				'oneto5' =>0,
				'oneto6' =>0,
				'oneto7' =>0,
				'oneto8' =>0,
				'oneto9' =>0,
				'oneto10' =>0,
				'twoto1' =>0,
				'skill1val' =>0.0,
				'skill2val' =>0.0,
				'skill3val' =>0.0,
				'skill4val' =>0.0,
				'skill5val' =>0.0,
				'skill6val' =>0.0,
				'skill7val' =>0.0,
				'skill8val' =>0.0,
				'skill9val' =>0.0,
				'skill10val' =>0.0,
				'skill11val' =>0.0,
				'addResourceHrs' =>0,
				'plannedHours' =>0.0,
				'plannedProcs' =>0.0,
				'budgetValue' =>0.0,
				'admits' =>0,
				'transfers' =>0,
				'discharges' =>0,
				'churnValue' =>0.0,
				'customNurse' =>0,
				'customNurse2' =>0,
				'otherNurse1' =>0,
				'otherNurse2' =>0,
				'otherNurse3' =>0,
				'activeRecord' =>0,
				'hrsVariance' =>0,
				'budgetVar' =>0,
				'track1' =>0,
				'track2' =>0,
				'track3' =>0,
				'track4' =>0,
				'gvariance' =>0.00,
				'notetrack1' =>'',
				'notetrack2' =>'',
				'notetrack3' =>'',
				'notetrack4' =>'',
				'submittedby' =>'',
				'countDischarges' =>0,
				'customProd' =>0.00,
				'gridrnVariance' =>0,
				'gridrnProductivity' =>0.00,
				'gbudgetVar' =>0.00,
				'rbudgetVar' =>0.00,
				'gridrnbudgetVar' =>0.00,
				'ghrsVariance' =>0.00,
				'ahrsVariance' =>0.00,
				'rhrsVariance' =>0.00,
				'gridrnhrsVariance' =>0.00,
				'submitCount' =>0				
				);
		$where = array(
				'id'=> $dataId
				);
		Config::get('db')->update('productiveNewData',$data,$where);
		}else{
		$where = array(
				'id'=> $dataId
				);
		Config::get('db')->delete('productiveNewData',$where);	
		}
		if($escId){
		$whereEsc = array(
				'dataId'=> $dataId
				);
		Config::get('db')->delete('productiveEscalations',$whereEsc);
		}else{
			//do nothing;
		}
        echo json_encode(array('success' => true,'data'=>$dbData));
        return true;
    }else{
    echo json_encode(array('success'=>false,'message'=>'Record not found.'));
	return false;
	}
}

/////////////////////////////////////
// Copy Plan
function copyPlan() {
	$dataId = (isset($_REQUEST['dataId']) ? $_REQUEST['dataId']:'');
	$deptId = (isset($_REQUEST['deptId']) ? $_REQUEST['deptId']:'');
	$shift = (isset($_REQUEST['shift']) ? $_REQUEST['shift']:'');
	$dayDate = (isset($_REQUEST['dayDate']) ? $_REQUEST['dayDate']:'');
	$date = $dayDate-518400;
	//$newDate = date_format($date,"Y/m/d");
	$newDate = date('Y-m-d',$date);
	$userId = Config::get('account')['id'];
	
	if($dataId){
	$dbProd = Config::get('db')->get_results("SELECT n.* from `productiveNewData` n WHERE n.dayDate='{$newDate}' AND n.deptId={$deptId} AND n.shift={$shift} AND n.userId>0");
	}else{
		echo json_encode(array('success'=>false,'message'=>false,'date'=>$newDate));
		return true;
	}
	if($dbProd){
	$demo = $dbProd[0];
	$chargecount = $demo['chargecount'];
	$techcount = $demo['techcount'];
	$aptechcount = $demo['aptechcount'];
	$seccount = $demo['seccount'];
	$sittercount = $demo['sittercount'];
	$antecount = $demo['antecount'];
	$ldcount = $demo['ldcount'];
	$ocount = $demo['ocount'];
	$acs = $demo['acs'];
	$am1 = $demo['am1'];
	$awcm = $demo['awcm'];
	$obed = $demo['obed'];
	$obed1 = $demo['obed1'];
	$ev = $demo['ev'];
	$scs = $demo['scs'];
	$cr = $demo['cr'];
	$pt = $demo['pt'];
	$ccs = $demo['ccs'];
	$ps1 = $demo['ps1'];
	$pp = $demo['pp'];
	$avariance = $demo['avariance'];
	$lvariance = $demo['lvariance'];
	$nvariance = $demo['nvariance'];
	$hrsVariance = $demo['hrsVariance'];
	$note = $demo['note'];
	$atotal = $demo['atotal'];
	$ltotal = $demo['ltotal'];
	$aproductivity = $demo['aproductivity'];
	$lproductivity = $demo['lproductivity'];
	$nproductivity = $demo['nproductivity'];
	$oneto1 = $demo['oneto1'];
	$oneto2 = $demo['oneto2'];
	$oneto3 = $demo['oneto3'];
	$oneto4 = $demo['oneto4'];
	$oneto5 = $demo['oneto5'];
	$oneto6 = $demo['oneto6'];
	$whpPlan = $demo['whpPlan']; 
	$skill1val = $demo['skill1val'];
	$skill2val = $demo['skill2val'];
	$skill3val = $demo['skill3val'];
	$skill4val = $demo['skill4val'];
	$skill5val = $demo['skill5val'];
	$skill6val = $demo['skill6val'];
	$addResourceHrs = $demo['addResourceHrs'];
	$twoto1 = $demo['twoto1'];
	$procedureCount = $demo['procedureCount'];
	$actualWHP = $demo['actualWHP'];
	$plannedHours = $demo['plannedHours'];
	$plannedProcs = $demo['plannedProcs'];
	$admits = $demo['admits'];
	$transfers = $demo['transfers'];
	$discharges = $demo['discharges'];
	$budgetValue = $demo['budgetValue'];
	$churnValue = $demo['churnValue'];
	
		$demodata = array(
                'userId' => $userId,
				'chargecount' => $chargecount, 
				'techcount' => $techcount,
				'aptechcount' => $aptechcount,
				'seccount' => $seccount,
				'sittercount' => $sittercount,
				'antecount' => $antecount,
				'ldcount' => $ldcount,
				'ocount' => $ocount,
				'acs' => $acs,
				'am1' => $am1,
				'awcm' => $awcm,
				'obed' => $obed,
				'obed1' => $obed1,
				'ev' => $ev,
				'scs' => $scs,
				'cr' => $cr,
				'pt' => $pt,
				'ccs' => $ccs,
				'ps1' => $ps1,
				'pp' => $pp,
				'avariance' => $avariance,
				'lvariance' => $lvariance,
				'nvariance' => $nvariance,
				'hrsVariance' => $hrsVariance,
				'note' => $note,
				'atotal' => $atotal,
				'ltotal' => $ltotal,
				'lproductivity' => $lproductivity,
				'nproductivity' => $nproductivity,
				'oneto1' => $oneto1,
				'oneto2' => $oneto2,
				'oneto3' => $oneto3,
				'oneto4' => $oneto4,
				'oneto5' => $oneto5,
				'oneto6' => $oneto6,
				'whpPlan' => $whpPlan,
				'skill1val' => $skill1val,
				'skill2val' => $skill2val,
				'skill3val' => $skill3val,
				'skill4val' => $skill4val,
				'skill5val' => $skill5val,
				'skill6val' => $skill6val,
				'addResourceHrs' => $addResourceHrs,
				'twoto1' => $twoto1,
				'procedureCount' => $procedureCount, 
				'actualWHP' => $actualWHP,
				'plannedHours' => $plannedHours,
				'plannedProcs' => $plannedProcs,
				'admits' => $admits,
				'transfers' => $transfers,
				'discharges' => $discharges,
				'budgetValue' => $budgetValue,
				'churnValue' => $churnValue
            );
		$demowhere = array(
                'id'=>$dataId
            );
			
            Config::get('db')->update('productiveNewData',$demodata,$demowhere);
			echo json_encode(array('success'=>true,'message'=>true));
	}else{
		echo json_encode(array('success'=>true,'message'=>false,'date'=>$newDate));
		return true;
	}
	
}


/////////////////////////////////////
// Get Blocked Beds
function getblockedBeds() {
	$deptId = (isset($_REQUEST['deptId'])?$_REQUEST['deptId']:'');
	//$accountId = (isset($_REQUEST['accountId'])?$_REQUEST['accountId']:'');
	$userId = Config::get('account')['id'];
	$accountId = Config::get('account')['accountId'];
	//$deptId = Config::get('account')['deptid'];
     
	$dbProd = Config::get('db')->get_results("select b.*, d.dept as deptName from `productiveblockedBeds` b left join `ProductiveDept` as d on d.id = b.deptId where b.accountId={$accountId} and b.deptId={$deptId} group by b.deptId");
	if ($dbProd) {
        $dbData = $dbProd[0];
        echo json_encode(array('success' => true,'data'=>$dbData));
        return true;
    }else{
	 $data = array(
            'accountId' => Config::get('db')->filter($accountId),
			'deptId' => Config::get('db')->filter($deptId),
			'blockedBeds' =>0
        );

        Config::get('db')->insert('productiveblockedBeds', $data);
		
		$dbProd = Config::get('db')->get_results("select b.*, d.dept as deptName from `productiveblockedBeds` b left join `ProductiveDept` as d on d.id = b.deptId where b.accountId={$accountId} and b.deptId={$deptId} group by b.deptId");
        
        $dbData = $dbProd[0];
        echo json_encode(array('success' => true,'data'=>$dbData));
        return true;
	 
		
	}
    echo json_encode(array('success'=>false,'message'=>'No blocked beds  found.'));
    return false;
}

/////////////////////////////////////
// Update Blocked Beds
function updateblockedBeds() {
	$deptId = (isset($_REQUEST['deptId'])?$_REQUEST['deptId']:'');
	$accountId = (isset($_REQUEST['accountId'])?$_REQUEST['accountId']:'');
	$userId = Config::get('account')['id'];
	$comments = (isset($_REQUEST['comment'])?$_REQUEST['comment']:'');
	$count = (isset($_REQUEST['count'])?$_REQUEST['count']:0);
	
	if(intval($count)>0){
	$comment = str_replace("'","",$comments);
	}else{
	$comment = '';
	}
     
	if ($deptId){
	$data = array(
            'blockedBeds' =>$count,
			'comments' =>$comment
        );
	$updateWhere = array('deptId' =>$deptId);
            
	$newBlock = Config::get('db')->update('productiveblockedBeds',$data,$updateWhere); 

        echo json_encode(array('success' => true));
        return true;
	}
    echo json_encode(array('success'=>false,'message'=>'update failed.'));
    return false;
}

// Staff Summary
function getStaff() {
	$dataId = (isset($_REQUEST['dataId'])?$_REQUEST['dataId']:'');
	$prodMeasure = (isset($_REQUEST['prodMeasure'])?$_REQUEST['prodMeasure']:1);
	$userId = Config::get('account')['id'];
	$accountId = Config::get('account')['accountId'];
     
	if(intval($prodMeasure==2) || intval($prodMeasure)==3){
	$staffSummary = Config::get('db')->get_results("select SUM(n.chargecount) as chargeCount, SUM(n.antecount) as anteCount, SUM(n.customNurse) as customNurse, SUM(n.customNurse2) as customNurse2, SUM(n.techcount) as techcount, SUM(n.seccount) as seccount, SUM(n.sittercount) as sittercount, SUM(n.otherNurse1) as otherNurse1, SUM(n.otherNurse2) as otherNurse2, SUM(otherNurse3) as otherNurse3, d.dept, d.prodMeasure, IFNULL(d.techLabel,0) as techLabel, IFNULL(d.secLabel,0) as secLabel, IFNULL(d.nurseDesc,0) as nurseDesc, IFNULL(d.nurse1Desc,0) as nurse1Desc, IFNULL(d.sittersNEWDesc,0) as sittersNEWDesc, IFNULL(d.other1Desc,0) as other1Desc, IFNULL(d.other2Desc,0) as other2Desc, IFNULL(d.other3Desc,0) as other3Desc, d.skilldesc1, d.skilldesc2, d.skilldesc3, d.skilldesc4, d.skilldesc5, d.skilldesc6, d.skilldesc7, d.skilldesc8, d.skilldesc9, d.skilldesc10,  d.skill1, d.skill2, d.skill3, d.skill4, d.skill5, d.skill6, d.skill7, d.skill8, d.skill9, d.skill10, SUM(n.skill1val) as skill1val, SUM(n.skill2val) as skill2val, SUM(n.skill3val) as skill3val, SUM(n.skill4val) as skill4val, SUM(n.skill5val) as skill5val, SUM(n.skill6val) as skill6val, SUM(n.skill7val) as skill7val, SUM(n.skill8val) as skill8val, SUM(n.skill9val) as skill9val, SUM(n.skill10val) as skill10val, n.note  from `productiveNewData` n left join `ProductiveDept` as d on d.id=n.deptId where n.deptId=(SELECT `deptId` FROM `productiveNewData` where `id`={$dataId}) and n.dayDate=(SELECT `dayDate` FROM `productiveNewData` where `id`={$dataId}) and n.type=1 and n.activeRecord>0 AND n.accountId={$accountId} GROUP BY n.deptId");           
	}else{
	$staffSummary = Config::get('db')->get_results("select n.*, (CASE WHEN n.chargecount<1 THEN round(n.chargecount,1) ELSE round(n.chargecount,0) END) as chargeCount, round(n.antecount,0) as anteCount, d.dept, d.prodMeasure, IFNULL(d.techLabel,0) as techLabel, IFNULL(d.secLabel,0) as secLabel, IFNULL(d.nurseDesc,0) as nurseDesc, IFNULL(d.nurse1Desc,0) as nurse1Desc, IFNULL(d.nurse2Desc,0) as nurse2Desc, IFNULL(d.sittersNEWDesc,0) as sittersNEWDesc, IFNULL(d.other1Desc,0) as other1Desc, IFNULL(d.other2Desc,0) as other2Desc, IFNULL(d.other3Desc,0) as other3Desc, d.skilldesc1, d.skilldesc2, d.skilldesc3, d.skilldesc4, d.skilldesc5, d.skilldesc6, d.skilldesc7, d.skilldesc8, d.skilldesc9, d.skilldesc10, d.skill1, d.skill2, d.skill3, d.skill4, d.skill5, d.skill6, d.skill7, d.skill8, d.skill9, d.skill10  from `productiveNewData` n left join `ProductiveDept` as d on d.id=n.deptId where n.id={$dataId} and n.activeRecord>0 AND n.accountId={$accountId}");
    }
	
	if($staffSummary){
	echo json_encode(array('success' => true, 'data'=>$staffSummary[0]));
    return true;
	}else{
	echo json_encode(array('success'=>false,'message'=>'update failed.'));
    return false;
	}
}


/////////////////////////////////////
// ADD NEW MATRIX
function UpdateProdMatrix() {
    $shift = (isset($_REQUEST['shift'])) ? $_REQUEST['shift'] : '';
	$dataId = (isset($_REQUEST['dataId'])) ? $_REQUEST['dataId'] : '';
	$day = (isset($_REQUEST['day'])) ? $_REQUEST['day'] : '';
	$chargecount = (isset($_REQUEST['chargecount'])) ? $_REQUEST['chargecount'] : 0;
	$techcount = (isset($_REQUEST['techcount'])) ? $_REQUEST['techcount'] : 0;
	$aptechcount = (isset($_REQUEST['aptechcount'])) ? $_REQUEST['aptechcount'] : 0;
	$seccount = (isset($_REQUEST['seccount'])) ? $_REQUEST['seccount'] : 0;
	$antecount = (isset($_REQUEST['antecount'])) ? $_REQUEST['antecount'] : 0;
	$acs = (isset($_REQUEST['acs'])) ? $_REQUEST['acs'] : 0;
	$am1 = (isset($_REQUEST['am1'])) ? $_REQUEST['am1'] : 0;
	$awcm = (isset($_REQUEST['awcm'])) ? $_REQUEST['awcm'] : 0;
	$obed = (isset($_REQUEST['obed'])) ? $_REQUEST['obed'] : 0;
	$obedNew = (isset($_REQUEST['obed1'])) ? $_REQUEST['obed1'] : 0;
	$ldcount = (isset($_REQUEST['ldcount'])) ? $_REQUEST['ldcount'] : 0;
	$ocount = (isset($_REQUEST['ocount'])) ? $_REQUEST['ocount'] : 0;
	$ev = (isset($_REQUEST['ev'])) ? $_REQUEST['ev'] : 0;
	$scs = (isset($_REQUEST['scs'])) ? $_REQUEST['scs'] : 0;
	$cr = (isset($_REQUEST['cr'])) ? $_REQUEST['cr'] : 0;
	$pt = (isset($_REQUEST['pt'])) ? $_REQUEST['pt'] : 0;
	$ccs = (isset($_REQUEST['ccs'])) ? $_REQUEST['ccs'] : 0;
	$deptId = (isset($_REQUEST['deptId'])) ? $_REQUEST['deptId'] : 0;
	$ps1 = (isset($_REQUEST['ps1'])) ? $_REQUEST['ps1'] : 0;
	$atotal = (isset($_REQUEST['atotal'])) ? $_REQUEST['atotal'] : 0;
	$ltotal = (isset($_REQUEST['ltotal'])) ? $_REQUEST['ltotal'] : 0;	
	$note = (isset($_REQUEST['note'])) ? $_REQUEST['note'] : '';
	$pp = (isset($_REQUEST['pp'])) ? $_REQUEST['pp'] : 0;
	$oneto7 = (isset($_REQUEST['oneto7'])) ? $_REQUEST['oneto7'] : 0;
	$oneto8 = (isset($_REQUEST['oneto8'])) ? $_REQUEST['oneto8'] : 0;
	$oneto9 = (isset($_REQUEST['oneto9'])) ? $_REQUEST['oneto9'] : 0;
	//$note2 = str_replace("'","",$note);
	$note2 = addslashes($note);
	$antecheck = 0;
	$laborcheck = 0;
	$antetotal = 0;
	$acheck = 0;
	$lchek = 0;
	
	$dbData = Config::get('db')->get_results("select n.*, s.value, h.hppd, h.id, h.viewPage from `productiveNewData` n left join `ProductiveDept` as h on h.accountId = n.accountId and h.id=n.deptId left join `productiveAccountXref` as s on s.accountId = n.accountId and s.dept = n.deptId and s.shift = n.shift where n.id={$dataId}");
	
	if($dbData){
		$hppd = floatval($dbData[0]['hppd']);
		$value = floatval($dbData[0]['value']);
		$deptId = $dbData[0]['deptId'];
		$censusShift = 4;
		$viewPage = $dbData[0]['viewPage'];

	}else{
		header('location: dashboard.php');
		exit();
	}
	
	$accountId = Config::get('account')['accountId'];
	$userId = Config::get('account')['id'];	
	
			$acs1 = intval($acs) * 0.25;
			$am11 = intval($am1) * 0.5;
			$awcm1 = intval($awcm);
			
			
			$antepartumnurses = ceil($acs1+$am11+$awcm1);
			$avariance = intval($antecount) - $antepartumnurses;
			
			$ev1 = intval($ev) * 0.5;
			$scs1 = intval($scs) * 0.5;
			$cr1 = intval($cr) * 0.5;
			$pt1 = intval($pt);
			$ccs1 = intval($ccs);
			$ps11 = intval($ps1);
			$obed1 = intval($obed) * 0.333;
			$obed2 = intval($obedNew) * 0.333;
			$pp1 = intval($pp) * 0.167;
			
			$labornurses = ceil($ev1+$scs1+$cr1+$pt1+$ccs1+$ps11+$obed1+$pp1+$obed2);
			$lvariance = intval($ldcount) - $labornurses;			
			$nvariance = ($antecount + $ldcount) - ($antepartumnurses + $labornurses);
			
			if (intval($shift) !=4){
				$dbAprod = Config::get('db')->get_results("select n.* from `productiveNewData` n where n.dayDate='{$day}' AND n.deptId={$deptId} AND n.shift=4");
				$antetotal = intval($dbAprod[0]['atotal']);
			}else{
				$antetotal = intval($atotal);
			}
			
			if($antetotal>0 && $antecount>0){
			$antehours = $value * $antetotal * $hppd;
			$antenurses = ($antecount + intval($aptechcount)) * 24 * $value;
			$aprod = ($antehours / $antenurses)*95;
			$aproductivity = round($aprod,1);
			}else{
			$aproductivity = 0.0;
			}	
			
			$acheck = $acs+$am1+$awcm;
			if($acheck == $atotal){
				$antecheck = true;
			}else{
				$antecheck = false;
			}
			$lcheck = $ev+$scs+$cr+$pt+$ccs+$ps1+$obed+$pp+$obedNew;
			if($lcheck == $ltotal){
				$laborcheck = true;
			}else{
				$laborcheck = false;
			}	
	if (intval($ldcount)>0) {
			$now = time()-21600;
			 $data = array(
                'chargecount' => $chargecount,
                'techcount' => $techcount,
				'aptechcount' => $aptechcount,
				'seccount' => $seccount,
				'antecount' => $antecount,
				'entered' =>date("Y-m-d H:i:s",$now),
				'userId' => $userId,
				'acs' => $acs,
				'am1' => $am1,
                'awcm' => $awcm,
				'obed' => $obed,
				'obed1' => $obedNew,
				'ldcount' => $ldcount,
				'ocount' => $ocount,
                'ev' => $ev,
				'scs' => $scs,
				'cr' => $cr,
				'pt' => $pt,
                'ccs' => $ccs,
				'ps1' => $ps1,
				'pp' => $pp,
				'avariance' => $avariance,
				'lvariance' => $lvariance,
				'nvariance' => $nvariance,
				'atotal' => $atotal,
				'ltotal' => $ltotal,
				'nproductivity' => $aproductivity,
				'note' => $note2,
				'activeRecord' =>1,
				'oneto7' => $oneto7,
				'oneto8' => $oneto8,
				'oneto9' => $oneto9
            );
			$updateWhere = array('id' => $dataId);
            
			$newProd = Config::get('db')->update('productiveNewData',$data,$updateWhere);
			
			}
			
			if($avariance < 100 || $lvariance < 100){
						
				if($avariance !=0 && $lvariance ==0){
					$rvariance = $avariance;
					$dep = "in Antepartum";
					if($avariance <0){
						$varianceType = "under-staffed";
					}else{
						$varianceType = "over-staffed";
					}
				}else if($avariance ==0 && $lvariance !=0){
					$rvariance = $lvariance;
					$dep = "in Labor Delivery";
					if($lvariance <0){
						$varianceType = "under-staffed";
					}else{
						$varianceType = "over-staffed";
					}
				}else{
					$rvariance = $avariance + $lvariance;
					$dep = "in Antepartum and L&D";
					$varianceType = '';
				}
				
			if($avariance <= -2 || $lvariance <= -2 || $avariance >= 2 || $lvariance >= 2){			
				$var = array(
				'message'=>true,
				'antecheck'=>$antecheck,
				'acheck'=>$acheck,
				'antetotal'=>$atotal,
				'labortotal'=>$ltotal,
				'laborcheck'=>$laborcheck,
				'a1'=>$acs,
				'a2'=>$am1,
				'a3'=>$awcm,
				'variance'=>$nvariance,
				'dataId'=>$dataId,
				'deptId'=>$deptId,
				'dept'=>$dep,
				'varianceType'=>$varianceType,
				'note'=>$note
				);
			}else{
				$var = array(
				'message'=>false,
				'antecheck'=>$antecheck,
				'acheck'=>$acheck,
				'antetotal'=>$atotal,
				'labortotal'=>$ltotal,
				'laborcheck'=>$laborcheck,
				'a1'=>$acs,
				'a2'=>$am1,
				'a3'=>$awcm,
				'variance'=>$nvariance,
				'dataId'=>$dataId,
				'deptId'=>$deptId,
				'dept'=>$dep,
				'varianceType'=>$varianceType,
				'note'=>$note
				);				
			}
			
			if($antecheck == false){
				echo json_encode(array('success'=>true,'data'=>$var));
				exit();
			}else{
				//do nothing;
			}
			
				$dbAdmin = Config::get('db')->get_results("select p.*, l.noreply, l.labelName FROM `productiveUser` p LEFT OUTER JOIN `productiveDeptXref` as x on x.userId = p.id LEFT JOIN `ProductiveDept` as d on d.id = x.deptId LEFT JOIN `productiveAccount` as a on a.id=d.accountId LEFT JOIN `productiveLabel` as l on l.id=a.label where p.role>=5 AND p.accountId={$accountId} and p.txt>0 and p.txtactive>=1 and p.txtPause=0 and p.active>0 and x.deptId={$deptId} and x.textAlerts >=1 group by p.id");
				
				if($dbAdmin){
				foreach ($dbAdmin as $dbText){
				$mobile = $dbText['mobile'];
				$noreply = $dbText['noreply'];
				$alertName = $dbText['labelName'];
				//$varnumber = $nvariance * -1;
				$message = $alertName . " Report Alert:  A Staffing Report has just been submitted with a variance of ". $rvariance ." in ". $dep .".  www.productivern.com/view.php?u=" . $dataId;
				$psword = "J8775bcgEE2065";
				$keyword2 = "JOBALARM58046";
				$shortcode = "58046";
				$slooce = "jobalarm45";
				
				$now = time();
				
                $msgId = "";
                $msgId .= "";
                $msgId .= $mobile . $now;
                
                $xmlMsg = "";
                $xmlMsg .= "<message id=\"".$msgId."\">";
                $xmlMsg .= "<partnerpassword>".$psword."</partnerpassword>";
                $xmlMsg .= "<content><![CDATA[" . $message . "]]></content>";
                $xmlMsg .= "</message>";

                $messages[] = $xmlMsg;
                $mobile = "1" . $mobile;
                $numbers[] = $mobile;
                $keywords[] = $keyword2;
               
            
				$smsoptions = Array(
					'numbers' => $numbers,
					'message' => $messages,
					'keyword' => $keywords,
					'login' => $slooce,
					'shortCode' => $shortCode

				);
				$result = '';
				}
				$result = sendNow($smsoptions);
				echo json_encode(array('success'=>true,'data'=>$var));
				return true;
				}
				echo json_encode(array('success'=>true,'data'=>$var));
				return true;
			
			
			}else{
			$var = array(
			'message'=>false,
			'antecheck'=>$antecheck,
			'laborcheck'=>$laborcheck,
			'a1'=>$acs,
			'a2'=>$am1,
			'a3'=>$awcm,
			'variance'=>$nvariance,
			'dataId'=>$dataId,
			'deptId'=>$deptId,
			'note'=>$note
			);
			}	
			echo json_encode(array('success'=>true,'data'=>$var));
		return true;
			
	}

/////////////////////////////////////
// UPDATE PROD NEW 
/*
function UpdateProdNew() {
    $shift = (isset($_REQUEST['shift'])) ? $_REQUEST['shift'] : '';
	$dataId = (isset($_REQUEST['dataId'])) ? $_REQUEST['dataId'] : '';
	$day = (isset($_REQUEST['day'])) ? $_REQUEST['day'] : '';
	$chargecount = (isset($_REQUEST['chargecount'])) ? $_REQUEST['chargecount'] : 0;
	$techcount = (isset($_REQUEST['techcount'])) ? $_REQUEST['techcount'] : 0;
	$seccount = (isset($_REQUEST['seccount'])) ? $_REQUEST['seccount'] : 0;
	$nursecount = (isset($_REQUEST['nursecount'])) ? $_REQUEST['nursecount'] : 0;
	$high = (isset($_REQUEST['high'])) ? $_REQUEST['high'] : 0;
	$med = (isset($_REQUEST['med'])) ? $_REQUEST['med'] : 0;
	$low = (isset($_REQUEST['low'])) ? $_REQUEST['low'] : 0;
	$deptId = (isset($_REQUEST['deptId'])) ? $_REQUEST['deptId'] : 0;
	$patienttotal = (isset($_REQUEST['patienttotal'])) ? $_REQUEST['patienttotal'] : 0;
	$note = (isset($_REQUEST['note'])) ? $_REQUEST['note'] : '';
	$antecheck = 0;
	$antetotal = 0;
	$acheck = 0;
	$pct = 0;
	$sec = 0;
	$accountId = Config::get('account')['accountId'];
	$userId = Config::get('account')['id'];	
	
	$dbData = Config::get('db')->get_results("select n.*, s.value, h.hppd, h.id, h.viewPage, h.shift as censusShift from `productiveNewData` n left join `ProductiveDept` as h on h.accountId = n.accountId and h.id=n.deptId left join `productiveAccountXref` as s on s.accountId = n.accountId and s.dept = n.deptId and s.shift = n.shift where n.id={$dataId}");
	
	if($dbData){
		$hppd = floatval($dbData[0]['hppd']);
		$value = floatval($dbData[0]['value']);
		$deptId = $dbData[0]['deptId'];
		$censusShift = $dbData[0]['censusShift'];
		$viewPage = $dbData[0]['viewPage'];
	}else{
		$hppd =0;
		$value =0;
		$viewPage = '';
		$censusShift=52;
		//$shifthrs=0;
	}
	
			if(intval($shift)>= 14 && intval($shift)<= 38){
				if(intval($patienttotal)<=23 && intval($patienttotal)>=22){
				$pct = 1;
				$sec = 0;
				}else if(intval($patienttotal)>=24){
				$pct = 2;
				$sec = 1;
				}else{
				$pct = 0;
				$sec = 0;
				}
			}else if(intval($shift)> 38 || intval($shift)< 14){
				if(intval($patienttotal)>=25 && intval($patienttotal)<=28){
				$pct = 1;
				$sec = 0;
				}else if(intval($patienttotal)>=29){
				$pct = 2;
				$sec = 0;
				}else{
				$pct = 0;
				$sec = 0;
				}
			}else{
				$pct = 0;
				$sec = 0;
			}
	
			$highacuity = intval($high) * 0.33;
			$medacuity = intval($med) * 0.25;
			$lowacuity = intval($low) * 0.2;
			
			$pctVariance = intval($techcount) - $pct;
				if($pctVariance <>0){
					$pctmsg = " and " . $pctVariance . "PCT";
				}else{
					$pctmsg = '';
				}
			$secVariance = intval($seccount) - $sec;
				if($secVariance <>0){
					$secmsg = " and " . $secVariance . "Secretary";
				}else{
					$secmsg = '';
				}
			
			
			$requirednurses = round($highacuity+$medacuity+$lowacuity);
			$avariance = intval($nursecount) - $requirednurses + $pctVariance + $secVariance;
						
			$nvariance = $avariance;
			
			
			if (intval($shift) != intval($censusShift)){
				$dbAprod = Config::get('db')->get_results("select n.* from `productiveNewData` n where n.dayDate='{$day}' AND n.accountId={$accountId} AND n.deptId={$deptId} AND n.shift={$censusShift}");
				$antetotal = intval($dbAprod[0]['atotal']);
			}else{
				$antetotal = intval($patienttotal);
			}
			
			if (intval(date("N")) >= 1 && intval(date("N"))<=4){
				$nurseAdd = 2 * .33;
			}else if(intval(date("N")) == 5){
				$nurseAdd = 1 * .33;
			}else{
				$nurseAdd = 0;
			}
			
			
			if($antetotal >=1 && intval($nursecount) >=1){
			$antehours = $value * $antetotal * $hppd;
			$antenurses = (intval($nursecount) + $nurseAdd + intval($chargecount) + intval($seccount) + intval($techcount)) * 24 * $value;
			$aprod = ($antehours / $antenurses)*95;
			$aproductivity = round($aprod,2);
			}else{
			$aproductivity = 0.0;
			}	
			
			$acheck = $high+$med+$low;
			if($acheck == $patienttotal){
				$antecheck = true;
			}else{
				$antecheck = false;
			}
			
	if (intval($patienttotal)>0) {	
			$now = time()-18000;
			 $data = array(
                'chargecount' => $chargecount,
                'techcount' => $techcount,
				'seccount' => $seccount,
				'antecount' => $nursecount,
				'entered' =>date("Y-m-d H:i:s",$now),
				'userId' => $userId,
				'oneto3' => $high,
				'oneto4' => $med,
                'oneto5' => $low,
				'avariance' => $avariance,
				'nvariance' => $avariance,
				'atotal' => $patienttotal,
				'aproductivity' => $aproductivity,
				'nproductivity' => $aproductivity,
				'note' => $note
            );
			$updateWhere = array('id' => $dataId);
            
			$newProd = Config::get('db')->update('productiveNewData',$data,$updateWhere);
			
			}
			
			if($nvariance < 10){
						
				if($nvariance <0){
					$varianceType = "under-staffed";
				}else if($nvariance >0){
					$varianceType = "over-staffed";
				}else{
					$varianceType = '';
				}
				
			if($avariance != 0){			
				$var = array(
				'message'=>true,
				'patientcheck'=>$antecheck,
				'variance'=>$nvariance,
				'dataId'=>$dataId,
				'dept'=>$dep,
				'varianceType'=>$varianceType,
				'note'=>$note
				);
			}else{
				$var = array(
				'message'=>false,
				'patientcheck'=>$antecheck,
				'variance'=>$nvariance,
				'dataId'=>$dataId,
				'dept'=>$dep,
				'varianceType'=>$varianceType,
				'note'=>$note
				);				
			}
			
			if($antecheck == false){
				echo json_encode(array('success'=>true,'data'=>$var));
				exit();
			}else{
				//do nothing;
			}
				
			
						
				
				$dbAdmin = Config::get('db')->get_results("select p.* FROM `productiveUser` p LEFT JOIN `productiveDeptXref` as x on x.userId = p.id where p.accountId={$accountId} and (p.txt=1 or p.txt=3) and p.txtactive=1 and x.deptId={$deptId} group by p.id");
				
				if($dbAdmin && intval($shift)!=44 && intval($shift)!=4 && intval($shift)!=52){
				foreach ($dbAdmin as $dbText){
				$mobile = $dbText['mobile'];
				//$varnumber = $nvariance * -1;
				$message = "ProductiveRN Alert:  A Staffing Report has just been submitted with a variance of ". $rvariance ." in ". $dep .".  www.jobalarm.com/productive/" . $viewPage . $dataId;
				$psword = "J8775bcgEE2065";
				$keyword2 = "JOBALARM58046";
				$shortcode = "58046";
				$slooce = "jobalarm45";
				
				$now = time();
				
                $msgId = "";
                $msgId .= "";
                $msgId .= $mobile . $now;
                
                $xmlMsg = "";
                $xmlMsg .= "<message id=\"".$msgId."\">";
                $xmlMsg .= "<partnerpassword>".$psword."</partnerpassword>";
                $xmlMsg .= "<content><![CDATA[" . $message . "]]></content>";
                $xmlMsg .= "</message>";

                $messages[] = $xmlMsg;
                $mobile = "1" . $mobile;
                $numbers[] = $mobile;
                $keywords[] = $keyword2;
               
            
				$smsoptions = Array(
					'numbers' => $numbers,
					'message' => $messages,
					'keyword' => $keywords,
					'login' => $slooce,
					'shortCode' => $shortCode

				);
				$result = '';
				}
				$result = sendNow($smsoptions);
				echo json_encode(array('success'=>true,'data'=>$var));
				return true;
				}
				echo json_encode(array('success'=>true,'data'=>$var));
				return true;
			
			
			}else{
			$var = array(
			'message'=>false,
			'antecheck'=>$antecheck,
			'laborcheck'=>$laborcheck,
			'a1'=>$acs,
			'a2'=>$am1,
			'a3'=>$awcm,
			'variance'=>$nvariance,
			'dataId'=>$dataId,
			'note'=>$note
			);
			}	
			echo json_encode(array('success'=>true,'data'=>$var));
		return true;
			
	}
	*/
	
/////////////////////////////////////
// UPDATE PROD TEST 
function UpdateProdTest() {
	$shift = (isset($_REQUEST['shift'])) ? $_REQUEST['shift'] : '';
	$dataId = (isset($_REQUEST['dataId'])) ? $_REQUEST['dataId'] : '';
	$day = (isset($_REQUEST['day'])) ? $_REQUEST['day'] : '';
	$chargecount = (isset($_REQUEST['chargecount'])) ? $_REQUEST['chargecount'] : 0;
	$nurse1count = (isset($_REQUEST['nurse1'])) ? $_REQUEST['nurse1'] : 0;
	$nurse2count = (isset($_REQUEST['nurse2'])) ? $_REQUEST['nurse2'] : 0;
	$other1count = (isset($_REQUEST['other1'])) ? $_REQUEST['other1'] : 0;
	$other2count = (isset($_REQUEST['other2'])) ? $_REQUEST['other2'] : 0;
	$other3count = (isset($_REQUEST['other3'])) ? $_REQUEST['other3'] : 0;
	$techcount = (isset($_REQUEST['techcount'])) ? $_REQUEST['techcount'] : 0;
	$seccount = (isset($_REQUEST['seccount'])) ? $_REQUEST['seccount'] : 0;
	$nursecount = (isset($_REQUEST['nursecount'])) ? $_REQUEST['nursecount'] : 0;
	$sittercount = (isset($_REQUEST['sitters'])) ? $_REQUEST['sitters'] : 0;
	$curTime = (isset($_REQUEST['currentTime'])) ? $_REQUEST['currentTime'] : time();
	$currentTime = strtotime($curTime);
	$oneto1 = (isset($_REQUEST['oneto1'])) ? $_REQUEST['oneto1'] : 0;
	//$acuity2 = 0;
	$oneto2 = (isset($_REQUEST['oneto2'])) ? $_REQUEST['oneto2'] : 0;
	//$acuity6 = 0;
	$oneto6 = (isset($_REQUEST['oneto6'])) ? $_REQUEST['oneto6'] : 0;
	$oneto7 = (isset($_REQUEST['oneto7'])) ? $_REQUEST['oneto7'] : 0;
	$oneto8 = (isset($_REQUEST['oneto8'])) ? $_REQUEST['oneto8'] : 0;
	$oneto9 = (isset($_REQUEST['oneto9'])) ? $_REQUEST['oneto9'] : 0;
	$oneto10 = (isset($_REQUEST['oneto10'])) ? $_REQUEST['oneto10'] : 0;
	$oneto11 = (isset($_REQUEST['oneto11'])) ? $_REQUEST['oneto11'] : 0;
	
	$gvariance = (isset($_REQUEST['gvariance'])) ? INTVAL($_REQUEST['gvariance']) : 0;
	$gridrnvariance = (isset($_REQUEST['rnvariance'])) ? INTVAL($_REQUEST['rnvariance']) : 0;
	$nvariance = (isset($_REQUEST['showrnvariance'])) ? FLOATVAL($_REQUEST['showrnvariance']) : 0;
	$gridCount = floatval($nursecount) + floatval($chargecount) + intval($nurse1count) + intval($nurse2count) + intval($other1count) + intval($other2count) + intval($other3count) + intval($seccount) + intval($sittercount) + intval($techcount);
		
	//$oneto11Acuity = (isset($_REQUEST['oneto11Acuity'])) ? $_REQUEST['oneto11Acuity'] : 0;
	$high = (isset($_REQUEST['high'])) ? $_REQUEST['high'] : 0;
	$med = (isset($_REQUEST['med'])) ? $_REQUEST['med'] : 0;
	$low = (isset($_REQUEST['low'])) ? $_REQUEST['low'] : 0;
	$acuityTotal = (isset($_REQUEST['acuityTotal'])) ? $_REQUEST['acuityTotal'] : 0;
	$admissions = (isset($_REQUEST['admissions'])) ? $_REQUEST['admissions'] : 0;
	$transfers = (isset($_REQUEST['transfers'])) ? $_REQUEST['transfers'] : 0;
	$discharges = (isset($_REQUEST['discharges'])) ? $_REQUEST['discharges'] : 0;
	$trackDischarges = (isset($_REQUEST['trackDischarges'])) ? intval($_REQUEST['trackDischarges']) : 0;
	$trackInsert = (isset($_REQUEST['trackInsert'])) ? $_REQUEST['trackInsert'] : '';
	$track1 = (isset($_REQUEST['track1'])) ? $_REQUEST['track1'] : 0;
	$track2 = (isset($_REQUEST['track2'])) ? $_REQUEST['track2'] : 0;
	$track3 = (isset($_REQUEST['track3'])) ? $_REQUEST['track3'] : 0;
	$track4 = (isset($_REQUEST['track4'])) ? $_REQUEST['track4'] : 0;
	$trackNote1 = (isset($_REQUEST['trackNote1'])) ? $_REQUEST['trackNote1'] : '';
	$trackNote2 = (isset($_REQUEST['trackNote2'])) ? $_REQUEST['trackNote2'] : '';
	$trackNote3 = (isset($_REQUEST['trackNote3'])) ? $_REQUEST['trackNote3'] : '';
	$trackNote4 = (isset($_REQUEST['trackNote4'])) ? $_REQUEST['trackNote4'] : '';
	$submittedby = (isset($_REQUEST['submittedby'])) ? $_REQUEST['submittedby'] : '';
	
	$trackVisits = (isset($_REQUEST['trackVisits'])) ? $_REQUEST['trackVisits'] : 0;
	$submitreqd = (isset($_REQUEST['submitreqd'])) ? $_REQUEST['submitreqd'] : 0;
	
	$newDate = date('Y-m-d', strtotime('-1 day', strtotime($day)));
	$userIdNEW = (isset($_REQUEST['submittedbyNEW'])) ? $_REQUEST['submittedbyNEW'] : 0;
	$note2 = (isset($_REQUEST['note'])) ? $_REQUEST['note'] : '';
	
	if($note2){
	$note = stripslashes($note2);
	$isnote = true;
	}else{
	$note = '';	
	$isnote = false;
	}
	$admissions = (isset($_REQUEST['admissions'])) ? INTVAL($_REQUEST['admissions']) : 0;
	$customChurn = (isset($_REQUEST['customChurn'])) ? INTVAL($_REQUEST['customChurn']) : 0;
	$transfers = (isset($_REQUEST['transfers'])) ? INTVAL($_REQUEST['transfers']) : 0;
	$discharges = (isset($_REQUEST['discharges'])) ? INTVAL($_REQUEST['discharges']) : 0;
	$accountId = Config::get('account')['accountId'];
	
	$acheck = 0;
	$nurseAdd = 0;
	$requirednurses = 0;
	$var = array();
	$patienttotal1 = (isset($_REQUEST['patienttotal'])) ? intval($_REQUEST['patienttotal']) : 0;	
	$patienttotal2 = (isset($_REQUEST['patienttotal2'])) ? intval($_REQUEST['patienttotal2']) : 0;
	$patienttotal = $patienttotal1 + $patienttotal2;
	
	if(intval($userIdNEW)==0){
	$userId = Config::get('account')['id'];
	}else{
	$userId = $userIdNEW;	
	}
	
	$dbData = Config::get('db')->get_results("select n.*, (select count(`dept`) from `productiveAccountXref` where `type`<2 AND `whpPlan` !=1 and `dept`=n.deptId) as shiftCount, s.value, h.avgWage, h.countcharge, h.useGrid, h.typetrack1, h.typetrack2, h.typetrack3, h.typetrack4, h.rnCount, h.rn1Count, h.rn2Count, h.roundrn, h.oneto1Acuity, h.oneto2Acuity, h.oneto3Acuity, h.oneto4Acuity, h.oneto5Acuity, h.oneto6Acuity, h.hppd, h.escalations, h.trackdc, h.trackdata, h.prodMeasure1, h.thresholdHigh, h.thresholdLow, h.rnThreshold, h.adjustment, a.displayProd, a.productivityPosNeg, h.viewPage, h.inshiftProd, h.los, h.dept, h.rnVariance, h.churn, h.shiftsperDay, h.nurse1Variance, h.shift as censusShift from `productiveNewData` n left join `ProductiveDept` as h on h.id=n.deptId left join `productiveAccount` as a on a.id = h.accountId left join `productiveShifts` as t on t.id = n.shift left join `productiveAccountXref` as s on s.dept = n.deptId and s.shift = n.shift LEFT OUTER JOIN `productiveDeptXref` as x on x.deptId=n.deptId where n.id={$dataId} group by n.id");
	
		$hppd = floatval($dbData[0]['hppd']);
		$thresholdHigh = floatval($dbData[0]['thresholdHigh']);
		$thresholdLow = floatval($dbData[0]['thresholdLow']);
		$value = floatval($dbData[0]['value']);
		$useGrid = intval($dbData[0]['useGrid']);
		$deptId = intval($dbData[0]['deptId']);
		$trackdc = $dbData[0]['trackdc'];
		$trackdata = $dbData[0]['trackdata'];
		$currentDischarges = intval($dbData[0]['trackDischarges']);
		$dayDate = $dbData[0]['dayDate'];
		$censusShift = $dbData[0]['censusShift'];
		$inshiftProd = intval($dbData[0]['inshiftProd']);
		$viewPage = $dbData[0]['viewPage'];
		$prodUpdate = $dbData[0]['nproductivity'];
		$escalations = intval($dbData[0]['escalations']);
		$user = intval($dbData[0]['userId']);
		$dep = $dbData[0]['dept'];
		$countcharge = intval($dbData[0]['countcharge']);
		$rnCount = intval($dbData[0]['rnCount']);
		$rn1Count = intval($dbData[0]['rn1Count']);
		$rn2Count = intval($dbData[0]['rn2Count']);
		$roundrn = intval($dbData[0]['roundrn']);
		$shiftCount = intval($dbData[0]['shiftCount']);
		$entered = $dbData[0]['entered'];
		$adjustment = floatval($dbData[0]['adjustment']);
		$recordId = $dbData[0]['id'];
		$prodMeasure = intval($dbData[0]['prodMeasure1']);
		$churnMeasure = intval($dbData[0]['churn']);
		//$visits = $dbData[0]['visits'];
		$los = floatval($dbData[0]['los']);
		$nurse1Variance = intval($dbData[0]['nurse1Variance']);
		$censusTotal = intval($dbData[0]['censusTotal']);
		$procedureCount = floatval($dbData[0]['procedureCount']);
		$actualWHP = floatval($dbData[0]['actualWHP']);
		$displayProd = intval($dbData[0]['rnVariance']);
		$prodnurseCount = ((floatval($chargecount) * $countcharge) + (floatval($nursecount) * $rnCount) + (floatval($nurse1count) * $rn1Count) + (floatval($nurse2count) * $rn2Count));
		$visitsToday = intval($trackVisits) / $shiftCount;
		
		
		$typetrack1 = intval($dbData[0]['typetrack1']);
		$typetrack2 = intval($dbData[0]['typetrack2']);
		$typetrack3 = intval($dbData[0]['typetrack3']);
		$typetrack4 = intval($dbData[0]['typetrack4']);
		$submitCount1 = intval($dbData[0]['submitCount']);
		$submitCount = $submitCount1 + 1;
		
		//if($displayProd==0){
		//$prodCalc = intval($dbData[0]['productivityPosNeg']) * -1;
		//}else{
		$prodCalc = intval($dbData[0]['productivityPosNeg']);	
		//}
		
		
		$rnThresholdHigh = intval($dbData[0]['rnThreshold']);
		$rnThresholdLow = $rnThresholdHigh * -1;
		
		$oneto1Acuity = $dbData[0]['oneto1Acuity'];
		$oneto2Acuity = $dbData[0]['oneto2Acuity'];
		$oneto3Acuity = $dbData[0]['oneto3Acuity'];
		$oneto4Acuity = $dbData[0]['oneto4Acuity'];
		$oneto5Acuity = $dbData[0]['oneto5Acuity'];
		$oneto6Acuity = $dbData[0]['oneto6Acuity'];
		$aproductivity2 = 0;
		$aproductivity = 0;
		$lproductivity = 0;
		$actualWHP = 0;
		$avgWage = floatval($dbData[0]['avgWage']);
		

		$dbAddResource = Config::get('db')->get_results("select p.id, p.deptId, sum(p.Sun) as SunTotal, sum(p.Mon) as MonTotal, sum(p.Tue) as TueTotal, sum(p.Wed) as WedTotal, sum(p.Thu) as ThuTotal, sum(p.Fri) as FriTotal, sum(p.Sat) as SatTotal, DAYOFWEEK('{$day}') as day FROM `productiveResources` p where p.deptId={$deptId}");
	
		if($dbAddResource){
		$dbDay = $dbAddResource[0]['day'];;
		
		switch ($dbDay) {
		case "1":
			$addHours = floatval($dbAddResource[0]['SunTotal']);
			break;
		case "2":
			$addHours = floatval($dbAddResource[0]['MonTotal']);
			break;
		case "3":
			$addHours = floatval($dbAddResource[0]['TueTotal']);
			break;
		case "4":
			$addHours = floatval($dbAddResource[0]['WedTotal']);
			break;
		case "5":
			$addHours = floatval($dbAddResource[0]['ThuTotal']);
			break;
		case "6":
			$addHours = floatval($dbAddResource[0]['FriTotal']);
			break;
		case "7":
			$addHours = floatval($dbAddResource[0]['SatTotal']);
			break;
		default:
			$addHours = 0;
		}
		}else{
		$addHours = 0;
		}
		
		if($addHours>0 && $shiftCount>0){
		$addResourceHrs = $addHours / $shiftCount;
		$nurseAdd = $addHours / $shiftCount;
		}else{
		$addResourceHrs = 0;
		$nurseAdd = 0;
		}
		
		if(intval($oneto1Acuity)==0){
		$oneto1Acuity=1;
		}
		if(intval($oneto2Acuity)==0){
		$oneto2Acuity=2;
		}
		if(intval($oneto3Acuity)==0){
		$oneto3Acuity=3;
		}
		if(intval($oneto4Acuity)==0){
		$oneto4Acuity=4;
		}
		if(intval($oneto5Acuity)==0){
		$oneto5Acuity=5;
		}
		if(intval($oneto6Acuity)==0){
		$oneto6Acuity=6;
		}
	
		$acuityOne = floatval($oneto1) / floatval($oneto1Acuity);
		$acuityTwo = floatval($oneto2) / floatval($oneto2Acuity);
		$highacuity = floatval($high) / floatval($oneto3Acuity);
		$medacuity = floatval($med) / floatval($oneto4Acuity);
		$lowacuity = floatval($low) / floatval($oneto5Acuity);
		$acuitySix = floatval($oneto6) / floatval($oneto6Acuity);
		
		/*
		if($roundrn==1){
		$requirednurses = round($highacuity+$medacuity+$lowacuity+$acuityOne+$acuityTwo+$acuitySix);
		}else{
		$requirednurses = $highacuity+$medacuity+$lowacuity+$acuityOne+$acuityTwo+$acuitySix;	
		}
		$nursevariance = ((floatval($chargecount) * $countcharge) + (floatval($nursecount) * $rnCount) + (floatval($nurse1count) * $rn1Count) + (floatval($nurse2count) * $rn2Count));
		$nvariance = $nursevariance - $requirednurses;
		*/
		//$avariance = $nvariance;
		 
		//HRS PRODUCTIVITY
		
		
		
		$updated=true;
		
			if(intval($nursecount)==0){
				$nursecheck = false;
			}else{
				$nursecheck = true;
			}
		
			$acheck = $high+$med+$low+$oneto1+$oneto2+$oneto6;
			if($acheck == $patienttotal){
			$antecheck = true;
			}else{
			$antecheck = false;
			}
			
			if($useGrid==1 && $gridCount>0){
				$gridReqd = $gridCount - $gvariance;
				$prodnurseReqd = $prodnurseCount - $gridrnvariance;
				$gproductivity = (1-(($gridCount - $gridReqd) / $gridReqd)) * 100;
				//$gproductivity = ((($gridReqd - $gridCount) / $gridCount) + 1) * 100;
				//$budgetVar = round((($gvariance * 24) / $shiftcount),1) * $avgWage * $prodCalc;
				$gridrnproductivity = (1-(($prodnurseCount - $prodnurseReqd) / $prodnurseReqd)) * 100;
				//$gridrnproductivity=0.00;
				}else{
					$gproductivity = 0.00;
					$gridrnproductivity=0.00;
				}
			
			//$nurseprodReqd = $prodnurseCount - $nvariance;
					if($prodnurseCount>0){
					$nurseprodReqd = $prodnurseCount - $nvariance;
					$nproductivity = (1-(($prodnurseCount - $nurseprodReqd) / $nurseprodReqd)) * 100;
					//$nproductivity = ((($nurseprodReqd - $prodnurseCount) / $prodnurseCount) + 1) * 100;
					}else{
					$nproductivity = 0.0;
					}
			
			$antenurses = ((floatval($nursecount) + floatval($chargecount) + intval($nurse1count) + intval($nurse2count) + intval($other1count) + intval($other2count) + intval($other3count) + intval($seccount) + intval($sittercount) + intval($techcount)) * 24 / $shiftCount) + $nurseAdd;
			$antehours = ($patienttotal * $hppd) / $shiftCount;
					if(intval($antehours)>0){
					$aprod = (1-(($antenurses - $antehours) / $antehours)) * 100;
					//$aprod = ((($antehours - $antenurses) / $antehours) + 1) * 100;
					$lproductivity = round($aprod,2);
					}else{
					$lproductivity = 0.0;	
					}
			
			if($prodCalc!=1){
			$hrsVariance = round((($patienttotal * $hppd / $shiftCount) - $antenurses),3);
			$hrsbudgetVar = $hrsVariance * $avgWage;
				
			$gridhrsVariance = round((($gvariance * 24) / $shiftCount) * -1,1);
			$gridbudgetVar = $gridhrsVariance * $avgWage;
			$gridrnhrsVariance = round((($gridrnvariance * 24) / $shiftCount) * -1,1);
			$gridrnbudgetVar = $gridrnhrsVariance * $avgWage;
				
			$rnhrsVariance = round((($nvariance * 24) / $shiftCount) * -1,1);
			$rnbudgetVar = $rnhrsVariance * $avgWage;
			
			}else{
			$hrsVariance = round((($patienttotal * $hppd / $shiftCount) - $antenurses) * -1,3);
			$hrsbudgetVar = $hrsVariance * $avgWage * -1;
				
			$gridhrsVariance = round((($gvariance * 24) / $shiftCount),1);
			$gridbudgetVar = $gridhrsVariance * $avgWage * -1;
			$gridrnhrsVariance = round((($gridrnvariance * 24) / $shiftCount),1);
			$gridrnbudgetVar = $gridrnhrsVariance * $avgWage * -1;
				
			$rnhrsVariance = round((($nvariance * 24) / $shiftCount),1);
			$rnbudgetVar = $rnhrsVariance * $avgWage * -1;
			}				
				
			if(intval($trackVisits)>0){
				$gridactualWHP = $antenurses / (intval($trackVisits) / $shiftCount);
			}else{
				$gridactualWHP = 0.0;
			}
			
			if(intval($prodMeasure)==4){	
			$hrsVariance = 0;
			$hrsbudgetVar = 0;
			}	
				
			if(intval($displayProd)==1){
				$aproductivity = $lproductivity;
				$avariance = $hrsVariance;
				$ahrsVariance = $hrsVariance;
			}else if(intval($displayProd)==0){
				$aproductivity = $nproductivity;
				$avariance = $nvariance;
				$ahrsVariance = $rnhrsVariance;
			}else if(intval($displayProd)==2){
				$aproductivity = $gproductivity;
				$avariance = $gvariance;
				$ahrsVariance = $gridhrsVariance;
			}else if(intval($displayProd)==3){
				$aproductivity = $gridrnproductivity;
				$avariance = $gridrnvariance;
				$ahrsVariance = $gridrnhrsVariance;
			}else{
				//add another;				
			}
			
				$budgetVar = $hrsbudgetVar;
				$rbudgetVar = $rnbudgetVar;
				$gbudgetVar = $gridbudgetVar;
				$hrsVariance = $hrsVariance;
				$rhrsVariance = $rnhrsVariance;
				$ghrsVariance = $gridhrsVariance;
			
			if(intval($prodMeasure)==1 && intval($shift) == intval($censusShift)){
				$updateProd = Config::get('db')->get_results("select n.*, s.value, h.hppd from `productiveNewData` n left join `ProductiveDept` as h on h.accountId = n.accountId and h.id=n.deptId left join `productiveShifts` as t on t.id = n.shift left join `productiveAccountXref` as s on s.dept = n.deptId and s.shift = n.shift where n.dayDate=DATE_SUB('{$day}', INTERVAL 1 DAY) AND n.accountId={$accountId} AND n.deptId={$deptId} and n.userId>0 group by n.shift order by n.shift DESC");
								
				foreach($updateProd as $updateShift){
					
					$updatehppd = floatval($updateShift['hppd']);
					//$updatevalue = floatval($updateShift['value']);
					$updatenurse1 = intval($updateShift['customNurse']);
					$updatecustom2 = intval($updateShift['customNurse2']);
					$updateother1 = intval($updateShift['otherNurse1']);
					$updateother2 = intval($updateShift['otherNurse2']);
					$updateother3 = intval($updateShift['otherNurse3']);
					$updatenurseAdd = $nurseAdd;
					$updatetechcount = intval($updateShift['techcount']);
					$updateseccount = intval($updateShift['seccount']);
					$updatesitter = intval($updateShift['sittercount']);
					$updatechargecount = floatval($updateShift['chargecount']);
					$updateantecount = intval($updateShift['antecount']);
					$updatenow = $updateShift['entered'];
					$updatedataId = $updateShift['id'];
					
					
					$updateantehours = ($patienttotal * $updatehppd) / $shiftCount;
					$updateantenurses = (($updateantecount + $updatechargecount + $updatenurse1 + $updatecustom2 + $updateother1 + $updateother2 + $updateother3 + $updateseccount + $updatesitter + $updatetechcount) * 24 / $shiftCount) + $updatenurseAdd;
					
					if($updateantehours >0){
					$updateaprod = ((($updateantehours - $updateantenurses) / $updateantehours) + 1) * 100;
					$updatelproductivity = round($updateaprod,2);
					}else{
					$updatelproductivity = 0.0;
					}
					
					$updatehrsVariance = round((($patienttotal * $updatehppd / $shiftCount) - $updateantenurses),3);
					$updatebudgetVar = $updatehrsVariance * $avgWage * $prodCalc;
						
					$updatedata = array(
						'lproductivity' => $updatelproductivity,
						'hrsVariance' => $updatehrsVariance,
						'censusTotal' => $patienttotal,
						'budgetVar'=>$updatebudgetVar
					);
					$updateWhere = array('id' => $updatedataId);
					
					$newProd = Config::get('db')->update('productiveNewData',$updatedata,$updateWhere);
					}										
								
			}else if(intval($prodMeasure)==4 && intval($trackVisits)>0 && intval($shift)==52){
				$updateProd = Config::get('db')->get_results("select n.*, h.hppd from `productiveNewData` n left join `ProductiveDept` as h on h.id=n.deptId where n.dayDate=DATE_SUB('{$day}', INTERVAL 1 DAY) AND n.accountId={$accountId} AND n.deptId={$deptId} and n.userId>0 group by n.shift order by n.shift DESC");
				
				foreach($updateProd as $updateShift){
					//$updatepatienttotal = intval($updateShift['atotal']);
					
					$updatehppd = floatval($updateShift['hppd']);
					//$updatevalue = floatval($updateShift['value']);
					$updatenurse1 = intval($updateShift['customNurse']);
					$updatecustomnurse2 = intval($updateShift['customNurse2']);
					$updateother1 = intval($updateShift['otherNurse1']);
					$updateother2 = intval($updateShift['otherNurse2']);
					$updateother3 = intval($updateShift['otherNurse3']);
					$updatenurseAdd = $nurseAdd;
					$updatetechcount = intval($updateShift['techcount']);
					$updateseccount = intval($updateShift['seccount']);
					$updatesittercount = intval($updateShift['sittercount']);
					$updatechargecount = floatval($updateShift['chargecount']);
					$updateantecount = floatval($updateShift['antecount']);
					$updatenow = $updateShift['entered'];
					$updatedataId = $updateShift['id'];
					$updateuserid = intval($updateShift['userId']);
					
					$updateantehours = ($trackVisits * $updatehppd) / $shiftCount;
					//$updateantenurses = ((floatval($updatenursecount) + floatval($updatechargecount) + intval($updatenurse1count) + intval($updateother1count) + intval($updateother2count) + intval($updateother3count) + intval($updateseccount) + intval($updatesittercount) + intval($updatetechcount)) * 24 / $shiftCount) + $updatenurseAdd;

					$updateantenurses = (($updateantecount + $updatechargecount + $updatenurse1 + $updatecustomnurse2 + $updateother1 + $updateother2 + $updateother3 + $updateseccount + $updatesitter + $updatetechcount) * 24 / $shiftCount) + $updatenurseAdd;
					$updateaprod = ((($updateantehours - $updateantenurses) / $updateantehours) + 1) * 100;
					$updatelproductivity = round($updateaprod,2);
					
					$updatehrsVariance = round(($updateantehours - $updateantenurses),3);
					$updatebudgetVar = $updatehrsVariance * $avgWage * $prodCalc;
					$updateactualWHP = $updateantenurses / (intval($trackVisits) / $shiftCount);
						
					$updatedata = array(
						'customProd' => $updatelproductivity,
						'aproductivity' => $updatelproductivity,
						'hrsVariance' => $updatehrsVariance,
						'visitsSubmitted' => $trackVisits,
						'budgetVar'=>$updatebudgetVar,
						'actualWHP'=>$updateactualWHP
					);
					$updateWhere = array('id' => $updatedataId);
					
					$newProd = Config::get('db')->update('productiveNewData',$updatedata,$updateWhere);
					}

			}else if(intval($prodMeasure)==4 && intval($trackVisits)>0 && intval($shift)!=52){
				$updateProd = Config::get('db')->get_results("select n.*, h.hppd from `productiveNewData` n left join `ProductiveDept` as h on h.id=n.deptId where n.dayDate='{$day}' AND n.accountId={$accountId} AND n.deptId={$deptId} and n.userId>0 group by n.shift order by n.shift DESC");
				
				foreach($updateProd as $updateShift){
					//$updatepatienttotal = intval($updateShift['atotal']);
					
					$updatehppd = floatval($updateShift['hppd']);
					//$updatevalue = floatval($updateShift['value']);
					$updatenurse1 = intval($updateShift['customNurse']);
					$updatecustomnurse2 = intval($updateShift['customNurse2']);
					$updateother1 = intval($updateShift['otherNurse1']);
					$updateother2 = intval($updateShift['otherNurse2']);
					$updateother3 = intval($updateShift['otherNurse3']);
					$updatenurseAdd = $nurseAdd;
					$updatetechcount = intval($updateShift['techcount']);
					$updateseccount = intval($updateShift['seccount']);
					$updatesittercount = intval($updateShift['sittercount']);
					$updatechargecount = floatval($updateShift['chargecount']);
					$updateantecount = floatval($updateShift['antecount']);
					$updatenow = $updateShift['entered'];
					$updatedataId = $updateShift['id'];
					
					$updateantehours = ($trackVisits * $updatehppd) / $shiftCount;
					//$updateantenurses = ((floatval($updatenursecount) + floatval($updatechargecount) + intval($updatenurse1count) + intval($updateother1count) + intval($updateother2count) + intval($updateother3count) + intval($updateseccount) + intval($updatesittercount) + intval($updatetechcount)) * 24 / $shiftCount) + $updatenurseAdd;

					$updateantenurses = (($updateantecount + $updatechargecount + $updatenurse1 + $updatecustomnurse2 + $updateother1 + $updateother2 + $updateother3 + $updateseccount + $updatesitter + $updatetechcount) * 24 / $shiftCount) + $updatenurseAdd;
					$updateaprod = ((($updateantehours - $updateantenurses) / $updateantehours) + 1) * 100;
					$updatelproductivity = round($updateaprod,2);
					
					$updatehrsVariance = round(($updateantehours - $updateantenurses),3);
					$updatebudgetVar = $updatehrsVariance * $avgWage * $prodCalc;
					$updateactualWHP = $updateantenurses / (intval($trackVisits) / $shiftCount);
						
					$updatedata = array(
						'customProd' => $updatelproductivity,
						'aproductivity' => $updatelproductivity,
						'hrsVariance' => $updatehrsVariance,
						'visitsSubmitted' => $trackVisits,
						'budgetVar'=>$updatebudgetVar,
						'actualWHP'=>$updateactualWHP
					);
					$updateWhere = array('id' => $updatedataId);
					
					$newProd = Config::get('db')->update('productiveNewData',$updatedata,$updateWhere);
					}

			}else{
				//do nothing;
			}
					
			if($churnMeasure==1 && $user>0 && $patienttotal>0){
				$churnValue = ROUND(((($transfers + $discharges + $admissions + $customChurn)/$patienttotal)*100),1);
			}else{
				$churnValue = 0;
			}
			
			if($displayProd==0){
				$aMsg = '';
				$hrsVar = $nvariance . " RNs";
			}else if($displayProd==1){
				$aMsg = '';
				$hrsVar = $hrsVariance . " hours";	
			}else{
				$aMsg = '';
				$hrsVar = $gvariance;
			}
			
				
			
			if($isnote==false && $displayProd !=1 && (($nvariance <= $rnThresholdLow || $gvariance <= $rnThresholdLow))){
				$varianceType = "under-staffed";
				$staffing = true;
			}else if($isnote==false && $displayProd !=1 && (($nvariance >= $rnThresholdHigh || $gvariance >= $rnThresholdHigh))){
				$varianceType = "over-staffed";
				$staffing = true;
			}else if($isnote==false && $aproductivity < $thresholdLow && $displayProd==1){
				$varianceType = "over-staffed";
				$staffing = true;
			}else if($isnote==false && $aproductivity > $thresholdHigh && $displayProd==1){
				$varianceType = "under-staffed";
				$staffing = true;
			}else{
				$varianceType = '';
				$staffing = false;
			}
			
			if($updated !=false){
			$now = time()-21600;		
		    $data = array(
                'chargecount' => $chargecount,
                'techcount' => $techcount,
				'seccount' => $seccount,
				'sittercount' => $sittercount,
				'antecount' => $nursecount,
				'entered' =>date("Y-m-d H:i:s",$currentTime),
				'userId' => $userId,
				'oneto3' => $high,
				'oneto4' => $med,
                'oneto5' => $low,
				'oneto1' => $oneto1,
				'oneto2' => $oneto2,
                'oneto6' => $oneto6,
				'oneto7' => $oneto7,
				'oneto8' => $oneto8,
				'oneto9' => $oneto9,
				'oneto10' => $oneto10,
				'oneto11' => $oneto11,
				'avariance' => $avariance,
				'nvariance' => $nvariance,
				'atotal' => $patienttotal1,
				'patientCount2' => $patienttotal2,
				'addResourceHrs' => $addResourceHrs,
				'aproductivity' => $aproductivity,
				'nproductivity' => $nproductivity,
				'lproductivity' => $lproductivity,
				'customProd' => $gproductivity,
				'admits' => $admissions,
				'transfers' => $transfers,
				'discharges' => $discharges,
				'customChurn' => $customChurn,
				'churnValue' => $churnValue,
				'customNurse' => $nurse1count,
				'customNurse2' => $nurse2count,
				'otherNurse1' => $other1count,
				'otherNurse2' => $other2count,
				'otherNurse3' => $other3count,
				'note' => addslashes($note),
				'hrsVariance' => $hrsVariance,
				'ahrsVariance' => $ahrsVariance,
				'ghrsVariance' => $ghrsVariance,
				'rhrsVariance' => $rhrsVariance,
				'actualWHP' => $actualWHP,
				'activeRecord' =>1,
				'budgetVar' =>$budgetVar,
				'gbudgetVar' =>$gbudgetVar,
				'rbudgetVar' =>$rbudgetVar,
				'trackDischarges' => $trackDischarges,
                'track1' => $track1,
				'track2' => $track2,
				'notetrack1' => $trackNote1,
				'notetrack2' => $trackNote2,
				'track3' => $track3,
				'notetrack3' => $trackNote3,
				'notetrack4' => $trackNote4,
				'track4' => $track4,
				'submittedby' => $submittedby,
				'gvariance' => $gvariance,
				'actualWHP' => $actualWHP,
				'visitsSubmitted' => $trackVisits,
				'submitCount' => $submitCount,
				'gridrnvariance' => $gridrnvariance,
				'gridrnproductivity' => $gridrnproductivity,
				'gridrnbudgetVar' => $gridrnbudgetVar,
				'gridrnhrsVariance' => $gridrnhrsVariance
            );
			$updateWhere = array('id' => $dataId);
            
			$newProd = Config::get('db')->update('productiveNewData',$data,$updateWhere);
			}
		
		if($currentDischarges != $trackDischarges){
		$updateDischarges = updatedischarges($deptId,$dayDate,$trackDischarges);
		}
		
		
		if($submitCount==1){
		$updateMail = sendupdateEmail($dataId,$aMsg,$hrsVar);	
		}
		
		$dbAdmin = Config::get('db')->get_results("select n.*, l.login as accountLogin, l.noreply, l.labelName, s.dayNight, u.alertTimes, u.mobile, u.txt, u.txtactive as TextActive, u.emailAlerts, u.emailEscalations, u.txtEscalation as TextEscalation, u.txtPause, x.textAlerts FROM `productiveNewData` n LEFT OUTER JOIN `productiveDeptXref` as x on x.deptId = n.deptId LEFT OUTER JOIN `productiveUser` as u on u.id = x.userId LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `productiveAccount` as a on a.id = n.accountId LEFT JOIN `productiveLabel` as l on l.id = a.label where n.id={$dataId} and u.txtactive>=1 and u.txtPause=0 and n.daydate = curdate() and u.active>=1 and u.txt>=1 and u.role>=5 and x.textAlerts >=1 and u.mobile !='' AND (s.dayNight=u.alertTimes or u.alertTimes=3) group by u.mobile");
	
			if($dbAdmin && $submitCount==1){
			//$text = intval($dbAdmin[0]['txt']);
			$textAlerts = $dbAdmin[0]['textAlerts'];
			$textactive = $dbAdmin[0]['TextActive'];
			$textescalation = $dbAdmin[0]['TextEscalation'];
			$txtpause = $dbAdmin[0]['txtPause'];
			$mailescalation = $dbAdmin[0]['emailEscalations'];
			$noreply = $dbAdmin[0]['noreply'];
			$alertName = $dbAdmin[0]['labelName'];
			
			$var = array(
				'message'=>$staffing,
				'prodMeasure'=>$prodMeasure,
				'variance'=>$nvariance,
				'dataId'=>$dataId,
				'deptId'=>$deptId,
				'updated'=>$updated,
				'escalations'=>$escalations,
				'textAlerts'=>$textAlerts,
				'txtactive'=>$textactive,
				'txtescalation'=>$textescalation,
				'mailescalation'=>$mailescalation,
				'txtpause'=>$txtpause,
				'dept'=>$dep,
				'varianceType'=>$varianceType,
				'note'=>$note,
				'rnThresholdHigh'=>$rnThresholdHigh,
				'rnThresholdLow'=>$rnThresholdLow,
				'emailAlerts'=>$updateMail
				);
			
			foreach ($dbAdmin as $dbText){
				$mobile = $dbText['mobile'];
				$accountLogin = $dbText['accountLogin'];
				//$varnumber = $nvariance * -1;
				$message = $alertName . " Report Alert: A Staffing Report has been submitted with ".$aMsg."a variance of ". $hrsVar ." in ". $dep .".  productivern.com/views/v.php?i=" . $dataId . "&l=" . $accountLogin;
				$psword = "J8775bcgEE2065";
				$keyword2 = "JOBALARM58046";
				$shortcode = "58046";
				$slooce = "jobalarm45";
				
				$now = time();
				
                $msgId = "";
                $msgId .= "";
                $msgId .= $mobile . $now;
                
                $xmlMsg = "";
                $xmlMsg .= "<message id=\"".$msgId."\">";
                $xmlMsg .= "<partnerpassword>".$psword."</partnerpassword>";
                $xmlMsg .= "<content><![CDATA[" . $message . "]]></content>";
                $xmlMsg .= "</message>";

                $messages[] = $xmlMsg;
                $mobile = "1" . $mobile;
                $numbers[] = $mobile;
                $keywords[] = $keyword2;
               
            
				$smsoptions = Array(
					'numbers' => $numbers,
					'message' => $messages,
					'keyword' => $keywords,
					'login' => $slooce,
					'shortCode' => $shortCode

				);
				$result = '';				
				}
				$result = sendNow($smsoptions);
				echo json_encode(array('success'=>true,'data'=>$var));
				return true;
				}else{
				$var = array(
				'message'=>$staffing,
				'prodMeasure'=>$prodMeasure,
				'variance'=>$nvariance,
				'dataId'=>$dataId,
				'deptId'=>$deptId,
				'updated'=>$updated,
				'escalations'=>$escalations,
				'visits'=>$visits,
				'textAlerts'=>0,
				'txtactive'=>0,
				'txtescalation'=>0,
				'txtpause'=>0,
				'mailescalation'=>0,
				'dept'=>$dep,
				'varianceType'=>$varianceType,
				'note'=>$note,
				'rnThresholdHigh'=>$rnThresholdHigh,
				'rnThresholdLow'=>$rnThresholdLow,
				'emailAlerts'=>$updateMail
				);
				echo json_encode(array('success'=>true,'data'=>$var));
				return true;
				};
			
}

function updateobed($other1count,$patienttotal,$shift,$day,$count,$currentTime,$userId) {
				$updateProd = Config::get('db')->get_results("select n.*, h.hppd, h.adjustment, a.productivityPosNeg, h.shift as censusShift from `productiveNewData` n left join `ProductiveDept` as h on h.id=n.deptId left join `productiveAccount` as a on a.id = h.accountId where n.dayDate='{$day}' AND n.shift={$shift} AND n.deptId=325 group by n.shift DESC");
				
				
				$dept = 325;
				$censusTotal = intval($updateProd[0]['censusTotal']);
				$hppd = floatval($updateProd[0]['hppd']);
				$adjustment = floatval($updateProd[0]['adjustment']);
				$prodCalc = intval($updateProd[0]['productivityPosNeg']);
				$dataId = intval($updateProd[0]['id']);
				$shiftCount = intval($count);
				
				$antenurses = ((intval($other1count) * 24)/ $shiftCount);
				
				if($censusTotal>0){
					$antetotal = $censusTotal;
				}else if($censusTotal==0 && intval($patienttotal)>0){
					$antetotal = intval($patienttotal);
				}else{
					$antetotal = getCensusAvg($day,$deptId,$shift);
				}	
				
				$requirednurses = round(intval($patienttotal)*.333);
				$avariance = floatval($other1count) - $requirednurses;
				
				$antehours = ($antetotal * $hppd) / $shiftCount;
				$aprod = ($antehours / $antenurses) * $adjustment;
				$aproductivity = round($aprod,1);
				$hrsVariance = round((($antetotal * $hppd / $shiftCount) - $antenurses) * ($adjustment/100) * $prodCalc,3);
				
				$data = array(
                'antecount' => $other1count,
				'entered' =>date("Y-m-d H:i:s",$currentTime),
				'userId' => $userId,
				'oneto3' => $patienttotal,
				'avariance' => $avariance,
				'nvariance' => $avariance,
				'atotal' => $patienttotal,
				'nproductivity' => $aproductivity,
				'hrsVariance' => $hrsVariance,
				'userId' => $userId,
				'activeRecord' =>1
            );
			$updateWhere = array('id' => $dataId);
            
			$newProd = Config::get('db')->update('productiveNewData',$data,$updateWhere);
			
			if(intval($shift)==52){
				//$antenurses = ((intval($nursecount) + floatval($chargecount) + intval($nurse1count) + intval($seccount) + intval($sittercount) + intval($techcount)) * 24 / $shiftCount) + $nurseAdd;
				$updateProd = Config::get('db')->get_results("select n.*, count(n.id) as newCount, s.value, h.hppd from `productiveNewData` n left join `ProductiveDept` as h on h.accountId = n.accountId and h.id=n.deptId left join `productiveShifts` as t on t.id = n.shift left join `productiveAccountXref` as s on s.dept = n.deptId and s.shift = n.shift where n.dayDate='{$day}' AND n.deptId=325 AND n.userId>0 group by n.shift order by n.shift DESC");
				foreach($updateProd as $updateShift){
					$updatehppd = floatval($updateShift['hppd']);
					//$updatevalue = floatval($updateShift['value']);
					$updatenurse1 = intval($updateShift['customNurse']);
					$updateother1 = intval($updateShift['otherNurse1']);
					$updateother2 = intval($updateShift['otherNurse2']);
					$updateother3 = intval($updateShift['otherNurse3']);
					$updatenurseAdd = $nurseAdd;
					$updatetechcount = intval($updateShift['techcount']);
					$updateseccount = intval($updateShift['seccount']);
					$updatesitter = intval($updateShift['sittercount']);
					$updatechargecount = floatval($updateShift['chargecount']);
					$updateantecount = intval($updateShift['antecount']);
					$updatenow = $updateShift['entered'];
					$updatedataId = $updateShift['id'];
					$newCount = intval($updateShift['newCount']);
					//$censusId = intval($updateShift['censusId']);
										
					$updateantehours = ($patienttotal * $updatehppd) / $shiftCount;
					$updateantenurses = (($updateantecount + $updatechargecount + $updatenurse1 + $updateother1 + $updateother2 + $updateother3 + $updateseccount + $updatesitter + $updatetechcount) * 24 / $shiftCount) + $updatenurseAdd;
					
					$updateaprod = ($updateantehours / $updateantenurses)*$adjustment;
					$newproductivity = round($updateaprod,1);
					$newhrsVariance = round((($patienttotal * $updatehppd / $shiftCount) - $updateantenurses) * ($adjustment/100) * $prodCalc,3);
						
					$updatedata = array(
						'nproductivity' => $newproductivity,
						'hrsVariance' => $newhrsVariance,
						'censusTotal' => $patienttotal
					);
					$updateWhere = array('id' => $updatedataId);
					
					$newProd = Config::get('db')->update('productiveNewData',$updatedata,$updateWhere);
					}
				return true;
			}
return true;					
	
}
		
	
/////////////////////////////////////
// UPDATE PROD NEW
function UpdateProdNew() {
    $shift = (isset($_REQUEST['shift'])) ? $_REQUEST['shift'] : '';
	$dataId = (isset($_REQUEST['dataId'])) ? $_REQUEST['dataId'] : '';
	$day = (isset($_REQUEST['day'])) ? $_REQUEST['day'] : '';
	$chargecount = (isset($_REQUEST['chargecount'])) ? $_REQUEST['chargecount'] : 0;
	$techcount = (isset($_REQUEST['techcount'])) ? $_REQUEST['techcount'] : 0;
	$seccount = (isset($_REQUEST['seccount'])) ? $_REQUEST['seccount'] : 0;
	$nursecount = (isset($_REQUEST['nursecount'])) ? $_REQUEST['nursecount'] : 0;
	$acuity1 = 0;
	//(isset($_REQUEST['acuity1'])) ? $_REQUEST['acuity1'] : 0;
	$acuity2 = 0;
	//(isset($_REQUEST['acuity2'])) ? $_REQUEST['acuity2'] : 0;
	$acuity6 = 0;
	//(isset($_REQUEST['acuity6'])) ? $_REQUEST['acuity6'] : 0;
	$high = (isset($_REQUEST['high'])) ? $_REQUEST['high'] : 0;
	$med = (isset($_REQUEST['med'])) ? $_REQUEST['med'] : 0;
	$low = (isset($_REQUEST['low'])) ? $_REQUEST['low'] : 0;
	$deptId = (isset($_REQUEST['deptId'])) ? $_REQUEST['deptId'] : 0;
	$patienttotal = (isset($_REQUEST['patienttotal'])) ? $_REQUEST['patienttotal'] : 0;
	$note = (isset($_REQUEST['note'])) ? $_REQUEST['note'] : '';
	$antecheck = 0;
	$antetotal = 0;
	$acheck = 0;
	$pct = 0;
	$sec = 0;
	$nurseAdd = 0;
	$pctVariance = 0;
	$secVariance = 0;
	$requirednurses = 0;
	$accountId = Config::get('account')['accountId'];
	$userId = Config::get('account')['id'];	
	
	$dbData = Config::get('db')->get_results("select n.*, s.value, h.hppd, h.id, h.viewPage, h.shift as censusShift from `productiveNewData` n left join `ProductiveDept` as h on h.accountId = n.accountId and h.id=n.deptId left join `productiveShifts` as t on t.id = n.shift left join `productiveAccountXref` as s on s.accountId = n.accountId and s.dept = n.deptId and s.shift = n.shift where n.id={$dataId}");
	
	if($dbData){
		$hppd = floatval($dbData[0]['hppd']);
		$value = floatval($dbData[0]['value']);
		$deptId = $dbData[0]['deptId'];
		$censusShift = $dbData[0]['censusShift'];
		$viewPage = $dbData[0]['viewPage'];
		//$pct = $dbData[0]['pct'];
		//$sec = $dbData[0]['sec'];
		$addhrs = 0;
	}else{
		$hppd =0;
		//$pct = 0;
		//$sec = 0;
		$addhrs = 0;
		$value =0;
		$viewPage = '';
		$censusShift=52;
		//$shifthrs=0;
	}
	
	
			$nurseAdd = intval($addhrs) / 24;
			$acuityOne = intval($acuity1);
			$acuityTwo = intval($acuity2) * 0.5;
			$highacuity = intval($high) * 0.33;
			$medacuity = intval($med) * 0.25;
			$lowacuity = intval($low) * 0.2;
			$acuitySix = intval($acuity6) * 0.17;
			
			$pctVariance = intval($techcount) - intval($pct);
				if($pctVariance <>0){
					$pctmsg = " and " . $pctVariance . "PCT";
				}else{
					$pctmsg = '';
				}
			$secVariance = intval($seccount) - intval($sec);
				if($secVariance <>0){
					$secmsg = " and " . $secVariance . "Secretary";
				}else{
					$secmsg = '';
				}
			
			
			$requirednurses = round($highacuity+$medacuity+$lowacuity);
			$avariance = intval($nursecount) - $requirednurses + $pctVariance + $secVariance;
						
			$nvariance = $avariance;
			
			
			if (intval($shift) != intval($censusShift)){
				$dbAprod = Config::get('db')->get_results("select n.* from `productiveNewData` n where n.dayDate='{$day}' AND n.accountId={$accountId} AND n.deptId={$deptId} AND n.shift={$censusShift}");
				$antetotal = intval($dbAprod[0]['atotal']);
			}else{
				$antetotal = intval($patienttotal);
			}
			
			if($antetotal >=1 && intval($nursecount) >=1){
			$antehours = $value * $antetotal * $hppd;
			$antenurses = (intval($nursecount) + $nurseAdd + floatval($chargecount) + intval($seccount) + intval($techcount)) * 24 * $value;
			$aprod = ($antehours / $antenurses)*98;
			$aproductivity = round($aprod,1);
			}else{
			$aproductivity = 0.0;
			}	
			
			$acheck = $high+$med+$low;
			if($acheck == $patienttotal){
				$antecheck = true;
			}else{
				$antecheck = false;
			}
			
	if (intval($patienttotal)>0) {
			$now = time()-21600;		
		    $data = array(
                'chargecount' => $chargecount,
                'techcount' => $techcount,
				'seccount' => $seccount,
				'antecount' => $nursecount,
				'entered' =>date("Y-m-d H:i:s",$now),
				'userId' => $userId,
				'oneto3' => $high,
				'oneto4' => $med,
                'oneto5' => $low,
				'avariance' => $avariance,
				'nvariance' => $avariance,
				'atotal' => $patienttotal,
				'nproductivity' => $aproductivity,
				'note' => $note
            );
			$updateWhere = array('id' => $dataId);
            
			$newProd = Config::get('db')->update('productiveNewData',$data,$updateWhere);
			
			}
			
			if($nvariance < 10){
						
				if($nvariance <0){
					$varianceType = "under-staffed";
				}else if($nvariance >0){
					$varianceType = "over-staffed";
				}else{
					$varianceType = '';
				}
				
			if($avariance != 0){			
				$var = array(
				'message'=>true,
				'patientcheck'=>$antecheck,
				'variance'=>$nvariance,
				'dataId'=>$dataId,
				'deptId'=>$deptId,
				'dept'=>$dep,
				'varianceType'=>$varianceType,
				'note'=>$note
				);
			}else{
				$var = array(
				'message'=>false,
				'patientcheck'=>$antecheck,
				'variance'=>$nvariance,
				'dataId'=>$dataId,
				'deptId'=>$deptId,
				'dept'=>$dep,
				'varianceType'=>$varianceType,
				'note'=>$note
				);				
			}
			
			if($antecheck == false){
				echo json_encode(array('success'=>true,'data'=>$var));
				exit();
			}else{
				//do nothing;
			}
				
			
						
				
				$dbAdmin = Config::get('db')->get_results("select p.* FROM `productiveUser` p LEFT JOIN `productiveDeptXref` as x on x.userId = p.id where p.accountId={$accountId} and p.txt>0 and p.txtactive>=1 and p.txtPause=0 and p.active>0 and x.deptId={$deptId} group by p.id");
				
				if($dbAdmin && intval($shift)!=44 && intval($shift)!=4){
				foreach ($dbAdmin as $dbText){
				$mobile = $dbText['mobile'];
				//$varnumber = $nvariance * -1;
				$message = "ProductiveRN Alert:  A Staffing Report has just been submitted with a variance of ". $rvariance ." in ". $dep .".  www.productivern.com/views/v.php?i=" . $dataId;
				$psword = "J8775bcgEE2065";
				$keyword2 = "JOBALARM58046";
				$shortcode = "58046";
				$slooce = "jobalarm45";
				
				$now = time();
				
                $msgId = "";
                $msgId .= "";
                $msgId .= $mobile . $now;
                
                $xmlMsg = "";
                $xmlMsg .= "<message id=\"".$msgId."\">";
                $xmlMsg .= "<partnerpassword>".$psword."</partnerpassword>";
                $xmlMsg .= "<content><![CDATA[" . $message . "]]></content>";
                $xmlMsg .= "</message>";

                $messages[] = $xmlMsg;
                $mobile = "1" . $mobile;
                $numbers[] = $mobile;
                $keywords[] = $keyword2;
               
            
				$smsoptions = Array(
					'numbers' => $numbers,
					'message' => $messages,
					'keyword' => $keywords,
					'login' => $slooce,
					'shortCode' => $shortCode

				);
				$result = '';
				}
				$result = sendNow($smsoptions);
				echo json_encode(array('success'=>true,'data'=>$var));
				return true;
				}
				echo json_encode(array('success'=>true,'data'=>$var));
				return true;
			
			
			}else{
			$var = array(
			'message'=>false,
			'antecheck'=>$antecheck,
			'laborcheck'=>$laborcheck,
			'a1'=>$acs,
			'a2'=>$am1,
			'a3'=>$awcm,
			'variance'=>$nvariance,
			'dataId'=>$dataId,
			'deptId'=>$deptId,
			'note'=>$note
			);
			}	
			echo json_encode(array('success'=>true,'data'=>$var));
		return true;
			
	}
	
/////////////////////////////////////
// UPDATE PROD WHP 
function UpdateProdWHP() {
    //$shift = (isset($_REQUEST['shift'])) ? $_REQUEST['shift'] : '';
	$dataId = (isset($_REQUEST['dataId'])) ? $_REQUEST['dataId'] : '';
	$day = (isset($_REQUEST['day'])) ? $_REQUEST['day'] : '';
	$procedures = (isset($_REQUEST['procedureCount'])) ? floatval($_REQUEST['procedureCount']) : 0;
	$customVal = isset($_REQUEST['customVal']) ? floatval($_REQUEST['customVal']) : 0.00;
	//$customVal = floatval($customVal1);
	$customVal2 = isset($_REQUEST['customVal2']) ? floatval($_REQUEST['customVal2']) : 0.00;
	$customVal3 = isset($_REQUEST['customVal3']) ? floatval($_REQUEST['customVal3']) : 0.00;
	$customVal4 = isset($_REQUEST['customVal4']) ? floatval($_REQUEST['customVal4']) : 0.00;
	$customVal5 = isset($_REQUEST['customVal5']) ? floatval($_REQUEST['customVal5']) : 0.00;
	//$indHRS = isset($_REQUEST['indHRS']) ? $_REQUEST['indHRS'] : 0.00;
	//$indUOS = isset($_REQUEST['indUOS']) ? $_REQUEST['indUOS'] : 0.00;
	//$customWHP = (isset($_REQUEST['customVal'])) ? floatval($_REQUEST['customVal']) : 0.00;
	$skillval1 = (isset($_REQUEST['skill1'])) ? floatval($_REQUEST['skill1']) : 0.00;
	$skillval2 = (isset($_REQUEST['skill2'])) ? floatval($_REQUEST['skill2']) : 0.00;
	$skillval3 = (isset($_REQUEST['skill3'])) ? floatval($_REQUEST['skill3']) : 0.00;
	$skillval4 = (isset($_REQUEST['skill4'])) ? floatval($_REQUEST['skill4']) : 0.00;
	$skillval5 = (isset($_REQUEST['skill5'])) ? floatval($_REQUEST['skill5']) : 0.00;
	$skillval6 = (isset($_REQUEST['skill6'])) ? floatval($_REQUEST['skill6']) : 0.00;
	$skillval7 = (isset($_REQUEST['skill7'])) ? floatval($_REQUEST['skill7']) : 0.00;
	$skillval8 = (isset($_REQUEST['skill8'])) ? floatval($_REQUEST['skill8']) : 0.00;
	$skillval9 = (isset($_REQUEST['skill9'])) ? floatval($_REQUEST['skill9']) : 0.00;
	$skillval10 = (isset($_REQUEST['skill10'])) ? floatval($_REQUEST['skill10']) : 0.00;
	$skillval11 = (isset($_REQUEST['skill11'])) ? floatval($_REQUEST['skill11']) : 0.00;
	$selectUser = (isset($_REQUEST['userId'])) ? $_REQUEST['userId'] : 0;
	$curTime = (isset($_REQUEST['currentTime'])) ? $_REQUEST['currentTime'] : time();
	$currentTime = strtotime($curTime);
	//$procedureCount = $procedures + $customVal;
	$trackInsert = (isset($_REQUEST['trackInsert'])) ? $_REQUEST['trackInsert'] : '';
	$track1 = (isset($_REQUEST['track1'])) ? $_REQUEST['track1'] : 0;
	$track2 = (isset($_REQUEST['track2'])) ? $_REQUEST['track2'] : 0;
	$track3 = (isset($_REQUEST['track3'])) ? $_REQUEST['track3'] : 0;
	$track4 = (isset($_REQUEST['track4'])) ? $_REQUEST['track4'] : 0;
	$trackNote1 = (isset($_REQUEST['trackNote1'])) ? $_REQUEST['trackNote1'] : '';
	$trackNote2 = (isset($_REQUEST['trackNote2'])) ? $_REQUEST['trackNote2'] : '';
	$trackNote3 = (isset($_REQUEST['trackNote3'])) ? $_REQUEST['trackNote3'] : '';
	$trackNote4 = (isset($_REQUEST['trackNote4'])) ? $_REQUEST['trackNote4'] : '';
	
	//$pHours = ($skillval1 + $skillval2 + $skillval3 + $skillval4 + $skillval5 + $skillval6 + $skillval7 + $skillval8 + $skillval9 + $skillval10);
	$admissions = (isset($_REQUEST['admissions'])) ? INTVAL($_REQUEST['admissions']) : 0;
	$transfers = (isset($_REQUEST['transfers'])) ? INTVAL($_REQUEST['transfers']) : 0;
	$discharges = (isset($_REQUEST['discharges'])) ? INTVAL($_REQUEST['discharges']) : 0;
	$submittedby = (isset($_REQUEST['submittedby'])) ? $_REQUEST['submittedby'] : '';
	//$pHours = floatval($plannedHours);
	//$procedureCount = floatval($procedures);
	//$resourceCount = floatval($chargecount)+intval($techcount)+intval($seccount)+intval($sittercount)+intval($nursecount);
	$note2 = (isset($_REQUEST['note'])) ? $_REQUEST['note'] : '';
	$skillTotal = $skillval1 +  $skillval2 +  $skillval3 +  $skillval4 +  $skillval5 +  $skillval6 +  $skillval7 +  $skillval8 +  $skillval9 +  $skillval10 +  $skillval11;
	//$gridCount = $skillTotal;
	
	if($note2){
	$note = stripslashes($note2);
	}else{
	$note = false;	
	}
	//$prodCalc = -1;
	$nurseAdd = 0;
	$accountId = Config::get('account')['accountId'];
	
	$userId = Config::get('account')['id'];	
	
	$dbData = Config::get('db')->get_results("select n.*, g.griddeptId, g.census, IFNULL(g.cn,0) AS cn, IFNULL(g.rn,0) AS rn, IFNULL(g.rn1,0) AS rn1, IFNULL(g.rn2,0) AS rn2, IFNULL(g.pct,0) AS pct, IFNULL(g.sitter,0) AS sitter, IFNULL(g.sec,0) as sec, IFNULL(g.other1,0) AS other1, IFNULL(g.other2,0) AS other2, IFNULL(g.other3,0) AS other3, g.dayNight, g.dow, (select count(`dept`) from `productiveAccountXref` where `type`<2 AND `whpPlan` !=1 and `dept`=n.deptId) as shiftCount, a.payPeriod, a.displayProd, a.payPeriodFirst, a.productivityPosNeg, h.typetrack1, h.typetrack2, h.typetrack3, h.typetrack4, h.hppd, h.rnVariance, h.indMeasure, h.useGrid, h.rnThreshold, h.thresholdHigh, h.thresholdLow, h.adjustment, h.avgWage, h.trackdata, h.churn, h.uosValue, h.budgetMeasure, h.escalations, h.prodMeasure1, h.id, h.viewPage, h.dept, h.skill1calc, h.skill2calc, h.skill3calc, h.skill4calc, h.skill5calc, h.skill6calc, h.skill7calc, h.skill8calc, h.skill9calc, h.skill10calc, h.skill11calc, h.skillbudget1, h.customWHPvalue, h.customWHP2value, h.customWHP3value, h.customWHP4value, h.customWHP5value, h.skillbudget2, h.skillbudget3, h.skillbudget4, h.skillbudget5, h.shift as censusShift, s.shifthrs from `productiveNewData` n left join `ProductiveDept` as h on h.id=n.deptId left join `productiveAccount` as a on a.id = h.accountId left outer join `productiveStaffingGrid` as g on g.griddeptId=n.deptId AND (g.dayNight=n.shift OR g.dayNight=((n.shift)-1)) AND g.dow=DAYOFWEEK(n.dayDate) LEFT JOIN `productiveAccountXref` as s on s.dept = n.deptId and s.shift=n.shift where n.id={$dataId}");
	
	if($dbData){
		$hppd = floatval($dbData[0]['hppd']);
		//$value = floatval($dbData[0]['value']);
		$nUser = intval($dbData[0]['userId']);
		$adjustment = floatval($dbData[0]['adjustment']);
		$deptId = $dbData[0]['deptId'];
		$viewPage = $dbData[0]['viewPage'];
		$escalations = intval($dbData[0]['escalations']);
		$dep = $dbData[0]['dept'];
		$prodMeasure = intval($dbData[0]['prodMeasure1']);
		$unitCat = intval($dbData[0]['unitCategory']);
		$indMeasure = intval($dbData[0]['indMeasure']);
		$type = intval($dbData[0]['type']);
		$budgetMeasure = intval($dbData[0]['budgetMeasure']);
		$uosValue = floatval($dbData[0]['uosValue']);
		$customWHPvalue = floatval($dbData[0]['customWHP']);
		$customWHP2value = floatval($dbData[0]['customWHP2']);
		$customWHP3value = floatval($dbData[0]['customWHP3']);
		$customWHP4value = floatval($dbData[0]['customWHP4']);
		$customWHP5value = floatval($dbData[0]['customWHP5']);
		$avgWage = floatval($dbData[0]['avgWage']);
		$trackdata = $dbData[0]['trackdata'];
		$gridTotal = intval($dbData[0]['cn'])+intval($dbData[0]['rn'])+intval($dbData[0]['rn1'])+intval($dbData[0]['rn2'])+intval($dbData[0]['pct'])+intval($dbData[0]['sitter'])+intval($dbData[0]['sec'])+intval($dbData[0]['other1'])+intval($dbData[0]['other2'])+intval($dbData[0]['other3']);
		
		$typetrack1 = intval($dbData[0]['typetrack1']);
		$typetrack2 = intval($dbData[0]['typetrack2']);
		$typetrack3 = intval($dbData[0]['typetrack3']);
		$typetrack4 = intval($dbData[0]['typetrack4']);
		
		$procedureCount = $procedures + ($customVal * $customWHPvalue) + ($customWHP2value * $customVal2) + ($customWHP3value * $customVal3) + ($customWHP4value * $customVal4);
		
		$churnMeasure = intval($dbData[0]['churn']);
		$plannedVal = intval($dbData[0]['whpPlan']);
		$shift = intval($dbData[0]['shift']);
		$shifthrs = floatval($dbData[0]['shifthrs']);
		$dayDate = $dbData[0]['dayDate'];
		$skillbudget1 = floatval($dbData[0]['skillbudget1']);
		$skillbudget2 = floatval($dbData[0]['skillbudget2']);
		$skillbudget3 = floatval($dbData[0]['skillbudget3']);
		$skillbudget4 = floatval($dbData[0]['skillbudget4']);
		$skillbudget5 = floatval($dbData[0]['skillbudget5']);
		
		$skill1calc = floatval($dbData[0]['skill1calc']);
		$skill2calc = floatval($dbData[0]['skill2calc']);
		$skill3calc = floatval($dbData[0]['skill3calc']);
		$skill4calc = floatval($dbData[0]['skill4calc']);
		$skill5calc = floatval($dbData[0]['skill5calc']);
		$skill6calc = floatval($dbData[0]['skill6calc']);
		$skill7calc = floatval($dbData[0]['skill7calc']);
		$skill8calc = floatval($dbData[0]['skill8calc']);
		$skill9calc = floatval($dbData[0]['skill9calc']);
		$skill10calc = floatval($dbData[0]['skill10calc']);
		$skill11calc = floatval($dbData[0]['skill11calc']);
		
		$useGrid = $dbData[0]['useGrid'];
		
		
		
		$rnThresholdHigh = intval($dbData[0]['rnThreshold']);
		$rnThresholdLow = $rnThresholdHigh * -1;
		$prodCalc = intval($dbData[0]['productivityPosNeg']);
		
		$thresholdHigh = floatval($dbData[0]['thresholdHigh']) / 100;
		$thresholdLow = floatval($dbData[0]['thresholdLow']) / 100;
		
		//if($indMeasure==1){
		//$shiftCount = intval($dbData[0]['userCount']);
		//}else{
		$shiftCount = intval($dbData[0]['shiftCount']);
		//}
		
		if($shiftCount<=0){
		$shiftCount =1;
		}
		
		if($budgetMeasure ==1 && $uosValue>0 && $type<2){
			$budgetAdd=0;
			//$budgetHours = (($skillval1 * $skillbudget1) + ($skillval2 * $skillbudget2) + ($skillval3 * $skillbudget3) + ($skillval4 * $skillbudget4) + ($skill1val5 * $skillbudget5));
			//$budgetUOS = $uosValue * $procedureCount;
		
			$dbResource = Config::get('db')->get_results("select p.id, p.deptId, p.resourcevalue, sum(p.Sun) as SunTotal, sum(p.Mon) as MonTotal, sum(p.Tue) as TueTotal, sum(p.Wed) as WedTotal, sum(p.Thu) as ThuTotal, sum(p.Fri) as FriTotal, sum(p.Sat) as SatTotal, DAYOFWEEK('{$day}') as day FROM `productiveResources` p where p.deptId={$deptId} group by `position`");
			
				if($dbResource){
				$resDay = $dbResource[0]['day'];
				
				foreach($dbResource as $budgetresource){
				switch ($resDay) {
				case "1":
					$addBudget = floatval($budgetresource['SunTotal']) * floatval($budgetresource['resourcevalue']);
					break;
				case "2":
					$addBudget = floatval($budgetresource['MonTotal']) * floatval($budgetresource['resourcevalue']);
					break;
				case "3":
					$addBudget = floatval($budgetresource['TueTotal']) * floatval($budgetresource['resourcevalue']);
					break;
				case "4":
					$addBudget = floatval($budgetresource['WedTotal']) * floatval($budgetresource['resourcevalue']);
					break;
				case "5":
					$addBudget = floatval($budgetresource['ThuTotal']) * floatval($budgetresource['resourcevalue']);
					break;
				case "6":
					$addBudget = floatval($budgetresource['FriTotal']) * floatval($budgetresource['resourcevalue']);
					break;
				case "7":
					$addBudget = floatval($budgetresource['SatTotal']) * floatval($budgetresource['resourcevalue']);
					break;
				default:
					$addBudget = 0.00;
				}	
					$budgetAdd = $budgetAdd + $addBudget;
				}		
				$budgetHours = (($skillval1 * $skillbudget1) + ($skillval2 * $skillbudget2) + ($skillval3 * $skillbudget3) + ($skillval4 * $skillbudget4) + ($skill1val5 * $skillbudget5) + $budgetAdd);
				//$budgetUOS = $uosValue * $procedureCount;
				$budgetTotal = ROUND((($budgetHours/$uosValue)*100),1);	
				}else{
				$budgetTotal = 0.0;;
				}
			}else{
				$budgetTotal = 0.0;
			}
				
		$dbAddResource = Config::get('db')->get_results("select p.id, p.deptId, IFNULL(sum(p.Sun),0) as SunTotal, IFNULL(sum(p.Mon),0) as MonTotal, IFNULL(sum(p.Tue),0) as TueTotal, IFNULL(sum(p.Wed),0) as WedTotal, IFNULL(sum(p.Thu),0) as ThuTotal, IFNULL(sum(p.Fri),0) as FriTotal, IFNULL(sum(p.Sat),0) as SatTotal, DAYOFWEEK('{$day}') as day FROM `productiveResources` p where p.deptId={$deptId}");
			
		if(intval($dbAddResource[0]['deptId'])>0 && $type<2){
		$dbDay = $dbAddResource[0]['day'];
		
		switch ($dbDay) {
		case "1":
			$addHours = floatval($dbAddResource[0]['SunTotal']);
			break;
		case "2":
			$addHours = floatval($dbAddResource[0]['MonTotal']);
			break;
		case "3":
			$addHours = floatval($dbAddResource[0]['TueTotal']);
			break;
		case "4":
			$addHours = floatval($dbAddResource[0]['WedTotal']);
			break;
		case "5":
			$addHours = floatval($dbAddResource[0]['ThuTotal']);
			break;
		case "6":
			$addHours = floatval($dbAddResource[0]['FriTotal']);
			break;
		case "7":
			$addHours = floatval($dbAddResource[0]['SatTotal']);
			break;
		default:
			$addHours = 0;
		}
		}else{
		$addHours = 0;
		}
		
	$nurseAdd = $addHours / $shiftCount;
	
	$totHours = (($skillval1 * $skill1calc) + ($skillval2 * $skill2calc) + ($skillval3 * $skill3calc) + ($skillval4 * $skill4calc) + ($skillval5 * $skill5calc) + ($skillval6 * $skill6calc) + ($skillval7 * $skill7calc) + ($skillval8 * $skill8calc) + ($skillval9 * $skill9calc) + ($skillval10 * $skill10calc) + ($skillval11 * $skill11calc)) + $nurseAdd;
	if($totHours ==0){
		$totalHours=$hppd;
	}else{
		$totalHours=$totHours;
	}
				
	if($procedureCount >0 && $totHours>0){
		if($unitCat==3){
		$actualWHP = round($procedureCount / $totalHours,2);
		}else{
		$actualWHP = round($totalHours / $procedureCount,3);
		}
	//$aprod = ($hppd / $actualWHP) * 100;
	$aprod = (1-(($actualWHP - $hppd) / $hppd)) * 100;
	$actualHoursVariance = round(($totalHours - ($hppd * $procedureCount)),2);
		
	}else if($procedureCount==0 && $totHours>0){
		if($prodMeasure==3){
		$actualWHP = round($procedureCount / $totalHours,2);
		}else{
		$actualWHP = round($totalHours,3);
		}
	$aprod = 0;
	$actualHoursVariance = round(($totalHours),2);
	//$actualbudgetVar = $totalHours * $prodCalc;
	
	}else{
	$actualWHP = 0;
	$aprod =0;
	$actualHoursVariance =0;
	}
	
	if(intval($useGrid)==2 && $skillTotal>0 && $gridTotal>0){
				$gridReqd = $gridTotal;
				$gproductivity = (1-(($skillTotal - $gridReqd) / $gridReqd)) * 100;
				$gvariance = $skillTotal - $gridTotal;
				$ghrsVariance = $gvariance * 4;
				$actualHoursVariance = $gvariance * 4;
				$actualWHP = $skillTotal * 8;
				
	}else{
				$gproductivity = 0.00;
				$gbudgetVar = 0.0;
				$gvariance = 0.0;
				$ghrsVariance = 0.0;
	}
	
	if($prodCalc==1){
	$aproductivity = round($aprod,1);
	$nvariance = $actualHoursVariance;
	$budgetVar = $nvariance * $avgWage * -1;
	$gvariance = $gvariance;
	$ghrsVariance = $ghrsVariance;
	$gbudgetVar = 4 * $gvariance * $avgWage * -1;
	}else{
	$aproductivity = round($aprod,1);
	$nvariance = $actualHoursVariance * -1;
	$gvariance = $gvariance * -1;
	$ghrsVariance = $ghrsVariance * -1;
	$budgetVar = $nvariance * $avgWage;	
	$gbudgetVar = 4 * $gvariance * $avgWage;
	}
	
	//$admits = $discharges + $transfers;
	
	//if($churnMeasure==1 && $admits >0){
	//	$churnValue = ROUND(((($transfers + $discharges)/$admissions)*100),1);
	//}else{
	//	$churnValue =0.00;
	//}
			
	if ($procedureCount>=0 && $indMeasure==0 && $type!=2) {
			//$now = time()-21600;		
		    $data = array(
                'entered' =>date("Y-m-d H:i:s",$currentTime),
				'userId' => $userId,
				'hrsVariance' => $nvariance,
				'ghrsVariance' => $ghrsVariance,
				'customProd' => $gproductivity,
				'gvariance' => $gvariance,
				'gbudgetVar' => $gbudgetVar,
				'hrsVarianceActual' => 0,
				'procedureCount' => $procedures,
				'whpCustom' => $customVal,
				'whpCustom2' => $customVal2,
				'whpCustom3' => $customVal3,
				'whpCustom4' => $customVal4,
				'whpCustom5' => $customVal5,
				'actualWHP' => $actualWHP,
				'actualWHPimport' => 0,
				'skill1val' => $skillval1,
				'skill2val' => $skillval2,
				'skill3val' => $skillval3,
				'skill4val' => $skillval4,
				'skill5val' => $skillval5,
				'skill6val' => $skillval6,
				'skill7val' => $skillval7,
				'skill8val' => $skillval8,
				'skill9val' => $skillval9,
				'skill10val' => $skillval10,
				'skill11val' => $skillval11,
				'addResourceHrs' => $nurseAdd,
				'budgetValue' => $budgetTotal,
				'type' => $type,
				'provId' => $selectUser,
				'activeRecord' => 1,
				'note' => addslashes($note),
				'budgetVar' => $budgetVar,
				'track1' => $track1,
				'track2' => $track2,
				'notetrack1' => $trackNote1,
				'notetrack2' => $trackNote2,
				'track3' => $track3,
				'notetrack3' => $trackNote3,
				'notetrack4' => $trackNote4,
				'track4' => $track4,
				'submittedby' => $submittedby
            );
			$updateWhere = array('id' => $dataId);
            
			$newProd = Config::get('db')->update('productiveNewData',$data,$updateWhere);
			
		}else if($indMeasure==1 && $nUser==0 && $type==2){
			
			$data = array(
                'entered' =>date("Y-m-d H:i:s",$currentTime),
				'dayDate' =>$dayDate,
				'shift' =>$shift,
				'accountId' =>$accountId,
				'deptId' =>$deptId,
				'userId' => $userId,
				'hrsVariance' => $nvariance,
				'hrsVarianceActual' => 0,
				'procedureCount' => $procedures,
				'ghrsVariance' => $ghrsVariance,
				'customProd' => $gproductivity,
				'gvariance' => $gvariance,
				'gbudgetVar' => $gbudgetVar,
				'whpCustom' => $customVal,
				'whpCustom2' => $customVal2,
				'whpCustom3' => $customVal3,
				'whpCustom4' => $customVal4,
				'whpCustom5' => $customVal5,
				'actualWHP' => $actualWHP,
				'actualWHPimport' => 0,
				'skill1val' => $skillval1,
				'skill2val' => $skillval2,
				'skill3val' => $skillval3,
				'skill4val' => $skillval4,
				'skill5val' => $skillval5,
				'skill6val' => $skillval6,
				'skill7val' => $skillval7,
				'skill8val' => $skillval8,
				'skill9val' => $skillval9,
				'skill10val' => $skillval10,
				'skill11val' => $skillval11,
				'addResourceHrs' => $nurseAdd,
				'budgetValue' => $budgetTotal,
				'type' => $type,
				'provId' => $selectUser,
				'activeRecord' => 1,
				'note' => addslashes($note),
				'budgetVar' => $budgetVar,
				'track1' => $track1,
				'track2' => $track2,
				'notetrack1' => $trackNote1,
				'notetrack2' => $trackNote2,
				'track3' => $track3,
				'notetrack3' => $trackNote3,
				'notetrack4' => $trackNote4,
				'track4' => $track4,
				'submittedby' => $submittedby
            );
			            
			$newProd = Config::get('db')->insert('productiveNewData',$data);
			$newdataId = Config::get('db')->get_results("select `id` from `productiveNewData` where `provId`={$selectUser} and `dayDate`='{$dayDate}' and `shift`={$shift} and `procedureCount`={$procedureCount}");
			$dataId = $newdataId[0]['id'];
		}else{
			
			$data = array(
                'entered' =>date("Y-m-d H:i:s",$currentTime),
				'userId' => $userId,
				'hrsVariance' => $nvariance,
				'hrsVarianceActual' => 0,
				'procedureCount' => $procedures,
				'whpCustom' => $customVal,
				'whpCustom2' => $customVal2,
				'whpCustom3' => $customVal3,
				'whpCustom4' => $customVal4,
				'whpCustom5' => $customVal5,
				'actualWHP' => $actualWHP,
				'actualWHPimport' => 0,
				'skill1val' => $skillval1,
				'skill2val' => $skillval2,
				'skill3val' => $skillval3,
				'skill4val' => $skillval4,
				'skill5val' => $skillval5,
				'skill6val' => $skillval6,
				'skill7val' => $skillval7,
				'skill8val' => $skillval8,
				'skill9val' => $skillval9,
				'skill10val' => $skillval10,
				'skill11val' => $skillval11,
				'addResourceHrs' => $nurseAdd,
				'ghrsVariance' => $ghrsVariance,
				'customProd' => $gproductivity,
				'gvariance' => $gvariance,
				'gbudgetVar' => $gbudgetVar,
				'budgetValue' => $budgetTotal,
				'type' => $type,
				'provId' => $selectUser,
				'activeRecord' => 1,
				'note' => addslashes($note),
				'budgetVar' => $budgetVar,
				'track1' => $track1,
				'track2' => $track2,
				'notetrack1' => $trackNote1,
				'notetrack2' => $trackNote2,
				'track3' => $track3,
				'notetrack3' => $trackNote3,
				'notetrack4' => $trackNote4,
				'track4' => $track4,
				'submittedby' => $submittedby
            );
			$updateWhere = array('id' => $dataId);
            
			$newProd = Config::get('db')->update('productiveNewData',$data,$updateWhere);
			
		}
			
			if($plannedVal==0){ 
			$newData = array(
                'activeRecord' => 0
            );
			$newWhere = array(	'dayDate' => $dayDate,
								'deptId' => $deptId,
								'whpPlan' => 1
							);
            
			$newUpdate = Config::get('db')->update('productiveNewData',$newData,$newWhere);
			
			}else{
			$updateActive = array(
				'planSubmitted' =>1
			);
			$whereActive = array(
				'deptId' => $deptId,
				'dayDate' => $dayDate,
				'whpPlan' =>0
			);
			Config::get('db')->update('productiveNewData',$updateActive,$whereActive);
			}
		
		/*	
		if(intval($trackInsert)==1 && intval($trackdata)==1){
		
		    $dataTracking = array(
                'track1' => $track1,
				'track2' => $track2,
				'trackNote1' => $trackNote1,
				'trackNote2' => $trackNote2,
				'track3' => $track3,
				'trackNote3' => $trackNote3,
				'trackNote4' => $trackNote4,
				'track4' => $track4,
				'deptId' => $deptId,
				'dayDate' => $dayDate,
				'dayTime' => date("Y-m-d H:i:s",$currentTime),
				'userId' => $userId
            );
            
			$newTracking = Config::get('db')->insert('productiveTracking',$dataTracking);
		}
		
		if(intval($trackInsert)==0 && intval($trackdata)==1){
			$dataTracking = array(
                'track1' => $track1,
				'track2' => $track2,
				'trackNote1' => $trackNote1,
				'trackNote2' => $trackNote2,
				'track3' => $track3,
				'trackNote3' => $trackNote3,
				'trackNote4' => $trackNote4,
				'track4' => $track4,
				'dayTime' => date("Y-m-d H:i:s",$currentTime),
				'userId' => $userId
            );
			$updateTracking = array('deptId' => $deptId,
								'dayDate' => $dayDate);
            
			$newTrack = Config::get('db')->update('productiveTracking',$dataTracking,$updateTracking);
		}
		*/
			
			if($actualWHP >0){
				$aMsg = "a WHPUOS of ".$actualWHP." and ";
				$hrsVar = $nvariance . "hours";
			}else{
				$aMsg = '';
				$hrsVar = '';
			}
			
			$updateMail = sendupdateEmail($dataId,$aMsg,$hrsVar);
			$dbAdmin = Config::get('db')->get_results("select n.*, l.login as accountLogin, l.noreply, l.labelName, s.dayNight, u.alertTimes, u.mobile, u.txt, u.txtactive, u.txtEscalation, x.textAlerts, u.txtPause FROM `productiveNewData` n LEFT OUTER JOIN `productiveDeptXref` as x on x.deptId = n.deptId LEFT OUTER JOIN `productiveUser` as u on u.id = x.userId LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `productiveAccount` as a on a.id=n.accountId LEFT JOIN `productiveLabel` as l on l.id = a.label where n.id={$dataId} and u.txtactive>=1 and n.daydate = curdate() and u.txtPause=0 and u.active>=1 and u.txt>=1 and u.role>=5 and x.textAlerts >=1 and (s.dayNight=u.alertTimes or u.alertTimes=3) and n.whpPlan=0 group by u.mobile");
			
			if($dbAdmin){
			$txtescalation = $dbAdmin[0]['txtEscalation'];
			$textAlerts = $dbAdmin[0]['textAlerts'];
			$txtactive = $dbAdmin[0]['txtactive'];
			$txtpause = $dbAdmin[0]['txtPause'];
			$noreply = $dbAdmin[0]['noreply'];
			$alertName = $dbAdmin[0]['labelName'];
			}else{
			$txtescalation=0;
			$textAlerts=0;
			$txtactive=0;
			$txtpause=0;
			$noreply="noreply@productivern.com";
			$alertName="ProductiveRN";
			}
			/*	
			if($actualHoursVariance > $rnThresholdHigh && $displayProd==1 && $prodCalc <0){
					$varianceType = "over-staffed";
				}else if($actualHoursVariance < $rnThresholdLow && $displayProd==1 && $prodCalc <0){
					$varianceType = "under-staffed";
				}else if($actualHoursVariance < $rnThresholdLow && $displayProd==1 && $prodCalc >0){
					$varianceType = "over-staffed";
				}else if($actualHoursVariance > $rnThresholdHigh && $displayProd==1 && $prodCalc >0){
					$varianceType = "under-staffed";
				}else{
					$varianceType = '';
				}
			*/	
			if($actualWHP < ($hppd * $thresholdLow)){
					$varianceType = "under-staffed";
				}else if($actualWHP > ($hppd * $thresholdHigh)){
					$varianceType = "over-staffed";
				}else{
					$varianceType = '';
				}
						
	
			if($type==2){
				$type1 = false;
			}else{
				$type1 = true;
			}
			
			if($note==false && ($actualWHP < ($hppd * $thresholdLow) || $actualWHP > ($hppd * $thresholdHigh))){			
				$var = array(
				'message'=>true,
				'variance'=>$nvariance,
				'dataId'=>$dataId,
				'deptId'=>$deptId,
				'hppd'=>$hppd,
				'escalations'=>$escalations,
				'textAlerts'=>$textAlerts,
				'txtactive'=>$txtactive,
				'txtescalation'=>$txtescalation,
				'txtpause'=>$txtpause,
				'budgetHours'=>$budgetHours,
				'budgetDaily'=>$uosValue,
				'procedureCount'=>$procedureCount,
				'whpCustom'=>$customVal,
				'actualWHP'=>$actualWHP,
				'totalHours'=>$totalHours,
				'skillval1'=>$skillval1,
				'skillval6'=>$skillval6,
				'dept'=>$dep,
				'type'=>$type1,
				'varianceType'=>$varianceType,
				'hrsVariance'=>$actualHoursVariance,
				'note'=>$note,
				'emailAlerts'=>$updateMail
				);
			}else{
				$var = array(
				'message'=>false,
				'variance'=>$nvariance,
				'dataId'=>$dataId,
				'dept'=>$dep,
				'type'=>$type1,
				'hppd'=>$hppd,
				'deptId'=>$deptId,
				'escalations'=>$escalations,
				'textAlerts'=>$textAlerts,
				'skillval1'=>$budgetTotal,
				'txtactive'=>$txtactive,
				'txtescalation'=>$txtescalation,
				'txtpause'=>$txtpause,
				'procedureCount'=>$procedureCount,
				'whpCustom'=>$customVal,
				'actualWHP'=>$avariance,
				'totalHours'=>$totalHours,
				'skillval1'=>$skillval1,
				'skillval6'=>$skillval6,
				'varianceType'=>$varianceType,
				'hrsVariance'=>$actualHoursVariance,
				'rnThresholdHigh'=>$rnThresholdHigh,
				'note'=>$note,
				'emailAlerts'=>$updateMail
				);				
				}
							
			if($dbAdmin){
				
			
			
			foreach ($dbAdmin as $dbText){
				$mobile = $dbText['mobile'];
				$accountLogin = $dbText['accountLogin'];
				$message = $alertName . " Report Alert: A Productivity Report has been submitted with ".$aMsg."a variance of ". $nvariance ." hours in ". $dep .".  productivern.com/views/v.php?i=" . $dataId . "&l=" . $accountLogin;
				$psword = "J8775bcgEE2065";
				$keyword2 = "JOBALARM58046";
				$shortcode = "58046";
				$slooce = "jobalarm45";
				
				$now = time();
				
                $msgId = "";
                $msgId .= "";
                $msgId .= $mobile . $now;
                
                $xmlMsg = "";
                $xmlMsg .= "<message id=\"".$msgId."\">";
                $xmlMsg .= "<partnerpassword>".$psword."</partnerpassword>";
                $xmlMsg .= "<content><![CDATA[" . $message . "]]></content>";
                $xmlMsg .= "</message>";

                $messages[] = $xmlMsg;
                $mobile = "1" . $mobile;
                $numbers[] = $mobile;
                $keywords[] = $keyword2;
               
            
				$smsoptions = Array(
					'numbers' => $numbers,
					'message' => $messages,
					'keyword' => $keywords,
					'login' => $slooce,
					'shortCode' => $shortCode

				);
				$result = '';				
				}
				$result = sendNow($smsoptions);
				echo json_encode(array('success'=>true,'data'=>$var));
				return true;
				}else{
				echo json_encode(array('success'=>true,'data'=>$var));
				return true;
				}
			
	}else{
			echo json_encode(array('success'=>false));
		return true;
			
	}
	
}

/////////////////////////////////////
// ADD CANDIDATE NOTE
function AddProdNote() {
    $note = isset($_REQUEST['note']) ? $_REQUEST['note'] : '';
    $dataId = isset($_REQUEST['dataId']) ? $_REQUEST['dataId'] : '';
	$accountId = Config::get('account')['accountId'];
	$userId = Config::get('account')['id'];
    $dbEsca = Config::get('db')->get_results("select n.*, IFNULL(d.escalations,0) as escalations, IFNULL(u.txtactive,0) as txtactive, IFNULL(u.txtPause,0) as txtpause, IFNULL(u.txtEscalation,0) as txtescalation, IFNULL(x.textAlerts,0) as textAlerts, IFNULL(u.emailAlerts,0) as emailAlerts from `productiveNewData` n LEFT JOIN `ProductiveDept` as d on d.id = n.deptId LEFT OUTER JOIN `productiveDeptXref` as x on x.deptId = n.deptId LEFT OUTER JOIN `productiveUser` as u on u.id = x.userId WHERE n.id={$dataId} and ((u.txtactive>0 and u.txtescalation >0 and x.textAlerts>0) or u.emailAlerts>0) group by u.mobile");
    
	if ($note && $dataId) {
	  $note2 = addslashes($note);
      $data = array(
	        'note' => Config::get('db')->filter($note2),
            'userId' => Config::get('db')->filter($userId)
        );
		$updateWhere = array('id' => $dataId);

        Config::get('db')->update('productiveNewData', $data, $updateWhere);
//            Config::get('db')->update('sms_messages',array("groupId"=>intval($group)),array('id'=>intval($target)),1);
	}
	if($dbEsca){
		echo json_encode(array('success'=>true,'data'=>$dbEsca[0]));
        return true;
    }else{
    echo json_encode(array('success' => true, 'data' =>'no escalation'));
    return true;
	}
}

/////////////////////////////////////
// ADD ESCALATION
function addEscalation() {
    $esc = isset($_REQUEST['esc']) ? $_REQUEST['esc'] : '';
	$comment = isset($_REQUEST['comment']) ? $_REQUEST['comment'] : '';
    $daId = isset($_REQUEST['dataId']) ? $_REQUEST['dataId'] : 0;
	$deptId = isset($_REQUEST['deptId']) ? $_REQUEST['deptId'] : false;
	//$sendtext = isset($_REQUEST['sendtext']) ? $_REQUEST['sendtext'] : 0;
	$accountId = Config::get('account')['accountId'];
	$userId = Config::get('account')['id'];
	//$deptId = Config::get('account')['deptid'];
	$role = Config::get('account')['role'];
	$fname = Config::get('account')['first_name'];
	$currentTime = isset($_REQUEST['currentTime']) ? $_REQUEST['currentTime'] : false;
	$dayDate = date('Y-m-d',strtotime($currentTime));
	
	if(intval($daId)==0 && $deptId && $currentTime){
	$dbescId = Config::get('db')->get_results("select * from `productiveNewData` where `deptId`={$deptId} AND `dayDate`='{$dayDate}' AND `type`<2 order by `shift` desc");
	$dataId = $dbescId[0]['id'];
    }else{
	$dataId = $daId;
	}
	
	if ($userId) {
		$data = array(
            'escalation' => Config::get('db')->filter($esc),
			'dataId' => Config::get('db')->filter($dataId),
			'deptId' => Config::get('db')->filter($deptId),
			'accountId' => Config::get('db')->filter($accountId),
			'note' => Config::get('db')->filter($comment),
            'userId' => Config::get('db')->filter($userId),
			'dateSubmitted' => $currentTime
        );

        Config::get('db')->insert('productiveEscalations', $data);
		}
		$dbEsc = Config::get('db')->get_results("select e.*, l.login, d.prodMeasure1 from `productiveEscalations` e LEFT JOIN `ProductiveDept` as d on d.id=e.deptId LEFT JOIN `productiveAccount` as a on a.id=e.accountId LEFT JOIN `productiveLabel` as l on l.id=a.label where e.userId={$userId} order by e.id DESC");
		$escId = $dbEsc[0]['id'];
		$label = $dbEsc[0]['login'];
		$prodMeasure = $dbEsc[0]['prodMeasure1'];
		
		if(intval($deptId)>0){
		$dbAdmin = Config::get('db')->get_results("select p.*, x.textAlerts, a.escalation, l.labelName FROM `productiveUser` p LEFT JOIN `productiveDeptXref` as x on x.userId = p.id LEFT JOIN `productiveAcctEscalations` as a on a.id={$esc} LEFT JOIN `productiveAccount` as c on c.id=p.accountId LEFT JOIN `productiveLabel` as l on l.id=c.label where p.accountId={$accountId} and p.txtEscalation>=1 and p.txtactive>=1 and p.txtPause=0 and p.active>=1 and x.deptId={$deptId} and x.textAlerts >=1");
		}else{
		$dbAdmin = Config::get('db')->get_results("select p.*, x.textAlerts, a.escalation, l.labelName FROM `productiveUser` p LEFT JOIN `productiveDeptXref` as x on x.userId = p.id LEFT JOIN `productiveAcctEscalations` as a on a.id={$esc} LEFT JOIN `productiveAccount` as c on c.id=p.accountId LEFT JOIN `productiveLabel` as l on l.id=c.label where p.accountId={$accountId} and p.txtEscalation>=1 and p.txtactive>=1 and p.txtPause=0 and p.active>=1 and x.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId}) and x.textAlerts >=1 group by p.id");
		$deptId = $dbAdmin[0]['deptId'];
		}
		
			
		if($dbAdmin){
				foreach ($dbAdmin as $dbText){
				$mobile = $dbText['mobile'];
				$escalation = $dbText['escalation'];
				$alertName = $dbText['labelName'];
				if(intval($prodMeasure)==2){
				$message = $alertName . " Alert:  An Escalation regarding " . $escalation . " was just submitted by " . $fname . ".  www.productivern.com/views/sts.php?c=" . $escId . "&l=" . $label;
				}else{
				$message = $alertName . " Alert:  An Escalation regarding " . $escalation . " was just submitted by " . $fname . ".  www.productivern.com/views/st.php?c=" . $escId . "&l=" . $label;	
				}
				$psword = "J8775bcgEE2065";
				$keyword2 = "JOBALARM58046";
				$shortcode = "58046";
				$slooce = "jobalarm45";
				
				$now = time();
				
                $msgId = "";
                $msgId .= "";
                $msgId .= $mobile . $now;
                
                $xmlMsg = "";
                $xmlMsg .= "<message id=\"".$msgId."\">";
                $xmlMsg .= "<partnerpassword>".$psword."</partnerpassword>";
                $xmlMsg .= "<content><![CDATA[" . $message . "]]></content>";
                $xmlMsg .= "</message>";

                $messages[] = $xmlMsg;
                $mobile = "1" . $mobile;
                $numbers[] = $mobile;
                $keywords[] = $keyword2;
               
            
				$smsoptions = Array(
					'numbers' => $numbers,
					'message' => $messages,
					'keyword' => $keywords,
					'login' => $slooce,
					'shortCode' => $shortCode

				);
				$result = '';
				}
				$result = sendNow($smsoptions);
			}else{
		//do nothing;
			}
		
	$escalationMail = sendescalationEmail($dataId,$deptId,$esc,$comment,$escId,$label);
	echo json_encode(array('success' => true, 'note' => 'Added Successfully'));
    return true;
    
}

/////////////////////////////////////
// GET DETAILS FOR SINGLE USER
function GetEscalation() {
	$dataId = (isset($_REQUEST['dataId'])?$_REQUEST['dataId']:'');

		$dbProd = Config::get('db')->get_results("select e.*, d.dept, u.first_name, u.last_name, a.escalation as escalationName FROM `productiveEscalations` e LEFT JOIN `productiveAcctEscalations` as a on a.id=e.escalation LEFT OUTER JOIN `ProductiveDept` as d on d.id=e.deptId LEFT OUTER JOIN `productiveUser` as u on u.id=e.userId WHERE e.id={$dataId}");
		
		if($dbProd){
        $dbEsca = $dbProd[0];
        echo json_encode(array('success' => true,'data'=>$dbEsca));
        return true;
    }else{
    echo json_encode(array('success'=>false,'message'=>'Record not found.'));
	return false;
	}
}

/////////////////////////////////////
// GET TRACKING

function GetTracking() {
	$dataId = (isset($_REQUEST['dataId'])?$_REQUEST['dataId']:'');

		$dbProd = Config::get('db')->get_results("select t.*, d.dept, d.track1Desc, d.track2Desc, d.track3Desc, d.track4Desc, IFNULL(d.track1Desc,0) as track1Show, IFNULL(d.track2Desc,0) as track2Show, IFNULL(d.track3Desc,0) as track3Show, IFNULL(d.track4Desc,0) as track4Show, u.first_name, u.last_name FROM `productiveTracking` t LEFT OUTER JOIN `ProductiveDept` as d on d.id=t.deptId LEFT OUTER JOIN `productiveUser` as u on u.id=t.userId WHERE t.id={$dataId}");
		
		if($dbProd){
        $dbEsca = $dbProd[0];
        echo json_encode(array('success' => true,'data'=>$dbEsca));
        return true;
    }else{
    echo json_encode(array('success'=>false,'message'=>'Record not found.'));
	return false;
	}
}


/////////////////////////////////////
// UPDATE ESCALATION
function UpdateEscalation() {
    $response = isset($_REQUEST['response']) ? $_REQUEST['response'] : '';
    $dataId = isset($_REQUEST['dataId']) ? $_REQUEST['dataId'] : '';
	$userId = Config::get('account')['id'];
	$currentTime = isset($_REQUEST['currentTime']) ? $_REQUEST['currentTime'] : '';
    
	if($dataId){
		$dbAdmin = Config::get('db')->get_results("select p.*, u.first_name, u.last_name FROM `productiveEscalations` p LEFT JOIN `productiveUser` as u on u.id={$userId} where p.id={$dataId}");
		$resp = $dbAdmin[0]['response']; 
		$lastId = intval($dbAdmin[0]['lastupdateId']);
		
		if($lastId>0){
		$comment = $resp . '; ' . $currentTime . ' - ' . $dbAdmin[0]['first_name'] . ' ' . $dbAdmin[0]['last_name'] . ': ' . $response;
		}else{
		$comment = $currentTime . ' - ' . $dbAdmin[0]['first_name'] . ' ' . $dbAdmin[0]['last_name'] . ': ' . $response;
		}

		
		$data = array(
            'lastupdateId' =>$userId,
			'lastUpdated' =>$currentTime,
			'response' =>$comment		
        );
		$where = array(
            'id' =>$dataId
        );

        Config::get('db')->update('productiveEscalations', $data, $where);

	$escalationMail = sendescalationResponse($dataId,$comment);
	echo json_encode(array('success' => true, 'note' => 'Escalation Updated'));
    return true;
    
}else{
	echo json_encode(array('success' => false, 'note' => 'Nothing to update'));
    return false;
}

}




/////////////////////////////////////
// UPDATE MATRIX
function UpdateMatrix() {
    $userId = (isset($_REQUEST['userId'])?$_REQUEST['userId']:0);
    $email = (isset($_REQUEST['email'])?$_REQUEST['email']:'');
    $role = (isset($_REQUEST['role'])?$_REQUEST['role']:'');


    if ($userId > 0) {
        $data = array(
            'email' => addslashes($email),
            'role' => $role
        );
        $where = array('id'=>$userId);
        Config::get('db')->update('users',$data,$where);
        echo json_encode(array('success'=>true,'message'=>'User Updated'));
        return true;
    }
    echo json_encode(array('success'=>false,'message'=>'User Failed to Update'));
    return false;
}





/*

         _   _ _____ _____ _     _____ _____ _____ _____ _____
        | | | |_   _|_   _| |   |_   _|_   _|_   _|  ___/  ___|
        | | | | | |   | | | |     | |   | |   | | | |__ \ `--.
        | | | | | |   | | | |     | |   | |   | | |  __| `--. \
        | |_| | | |  _| |_| |_____| |_  | |  _| |_| |___/\__/ /
         \___/  \_/  \___/\_____/\___/  \_/  \___/\____/\____/


*/

/////////fpdf reports

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



if(intval($row2['prodMeasure1'])==2 && intval($deptId)!=323){
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
	$patients = $row2['atotal'] + $row2['ltotal'];
	$rnVar = '-';
	$note = stripslashes($row2['note']);
	$totalRes = round(($row2['antecount'] + $row2['chargecount'] + $row2['customNurse'] + $row2['otherNurse1'] + $row2['otherNurse2'] + $row2['otherNurse3'] + $row2['seccount'] + $row2['sittercount'] + $row2['techcount']),1);
}else{
	$rn = (24 * ($row2['antecount'] + $row2['chargecount'] + $row2['ocount'] + $row2['ldcount'])) / $shiftcount;
	$techs = (24 * $row2['techs']) / $shiftcount;
	$other = (24 * ($row2['otherNurse1'] + $row2['otherNurse3'])) / $shiftcount;
	$patients = $row2['atotal'] + $row2['ltotal'] + $row2['obed'];
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
	
	$patients = $row2['atotal'] + $row2['ltotal'];
	
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
	
$data2 = Config::get('db') -> get_results("select n.nvariance, d.dept as deptName, sum(n.atotal + n.patientCount2) as total, DATE_FORMAT(n.dayDate,'%m/%d/%y') as newDate, sum(n.acs) as acs, sum(n.am1 + n.scs + n.cr) as am1, sum(n.obed1) as obed, sum(n.pp) as pp, sum(n.awcm + n.pt + n.ccs + n.ps1) as awcm, n.note, f.shiftName FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveAccountXref` as f on f.dept = n.deptId and f.shift = n.shift left outer Join `productiveUser` as u on u.id={$userId} where n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate and n.deptId=1 and n.userId>0 group by n.accountId, n.deptId, n.dayDate, n.shift ORDER BY d.dept ASC, n.dayDate DESC, n.shift DESC");

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
	
	$pdf->Row(array($row2['dept'],$date,$totalvisits,$totalhrs,$actualwhp));	
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
	$patients = $row2['atotal'] + $row2['ltotal'];
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
	
	$patients = $row2['atotal'] + $row2['ltotal'];
	
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
	$patients = $row2['atotal'] + $row2['ltotal'];
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
	
	$patients = $row2['atotal'] + $row2['ltotal'];
	
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
	$patients = $row2['atotal'] + $row2['ltotal'];
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
	
	$patients = $row2['atotal'] + $row2['ltotal'];
	
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
    $w = array(80, 40, 40, 30);
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
	$this->SetFont('Arial','B',11);
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
    $w = array(80, 40, 40, 30);
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

$data = Config::get('db') -> get_results("select n.deptId, SUM(CASE WHEN n.userId>0 THEN n.nvariance ELSE 0 END) / SUM(CASE WHEN n.userId>0 THEN 1 ELSE 0 END) as varAvg, a.displayProd, a.productivityPosNeg, n.actualWHP, a.id as accountId, a.name as accountName, d.dept as deptName, d.prodMeasure, DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%m%d%y') as filedate, u.last_name as Director, i.last_name as Manager, a.id as accountId, ROUND(SUM((CASE WHEN n.actualWHPimport>0 and n.activeRecord>0 then n.hrsVarianceActual ELSE 0 END) + (CASE WHEN n.actualWHPimport=0.000 and n.activeRecord>0 then n.hrsVariance ELSE 0 END)),1) as hrsVarianceTotal, SUM((CASE WHEN n.userId>0 and n.activeRecord>0 THEN 1 ELSE 0 END)) as report, SUM((CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END)) as totalreports, ((SUM(CASE WHEN (n.userId>0 and n.activeRecord>0) THEN 1 ELSE 0 END) + (CASE WHEN n.userId=0 and n.activeRecord>0 and n.planSubmitted=1 THEN 1 ELSE 0 END)) / (SUM((CASE WHEN n.whpPlan!=1 THEN 1 ELSE 0 END))) * 100) as compliance FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveAccount` as a on a.id=n.accountId left outer join `productiveUser` as i on i.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id and `unitAssigned`=0 AND `primaryUnit`=6 order by `primaryUnit` ASC LIMIT 0,1) left join `productiveUser` as b on b.id={$userId} left outer Join `productiveUser` as u on u.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id and `unitAssigned`=0 AND `primaryUnit`=7 order by `primaryUnit` ASC LIMIT 0,1) where d.active>0 AND n.dayDate >=b.lastStartDate AND n.dayDate <=b.lastEndDate$searchAdd group by n.accountId, n.deptId  
ORDER BY compliance DESC, d.dept DESC");
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

	
if(intval($row2['prodMeasure'])==2){
	$rn = '-';
	$techs = '-';
	$patients = round($row2['procedureCount'],0);
	$totalHours = $row2['Hours'];
	$actualWHP = $row2['actualWHPTotal'];
	$other = $row2['customNurse'] + $row2['otherNurse1'] + $row2['otherNurse2'] + $row2['otherNurse3'];
	$submitted = $row2['submitted'];
}else{
	$rn = $row2['antecount'] + $row2['customNurse'] + $row2['customNurse2'] + $row2['chargecount'];
	$patients = $row2['atotal'] + $row2['ltotal'];	
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

function getpdf366(){
//$deptId = 366;
//$userId = Config::get('account')['id'];
//$userId = 411;
//$prodCount = Config::get('account')['prodCount'];

//require_once '.././inc/class.fpdf.php';

class PDF_MC_Table extends FPDF{

var $widths;
var $aligns = array('L','C','R','R','R','R','R','R','R','R','R','R','L');


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
$accountId = 20;
$deptId = 366;
	

//$deptCount = Config::get('db') -> get_results("select n.deptId, d.dept as deptName  FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveAccount` as a on a.id=n.accountId left outer Join `productiveUser` as u on u.id=(SELECT `userId` from `productiveDeptXref` where `deptId`=d.id and `unitAssigned`=0 AND (`primaryUnit`=7 or `primaryUnit`=6) order by `primaryUnit` DESC LIMIT 0,1) where d.active>0 AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate$searchAdd group by n.accountId, n.deptId");

$newdata = Config::get('db') -> get_results("select a.name as accountName, a.displayProd, DATE_FORMAT(u.lastStartDate,'%m/%d/%y') AS startDay, DATE_FORMAT(u.lastEndDate,'%m/%d/%y') AS endDay FROM `productiveAccount` a left join `productiveUser` as u on u.id={$userId} where a.id={$accountId} group by a.id");
	
	$header = array('Unit', 'Leader', 'Compliance', 'Avg. Variance');
	$header2 = array('Unit', 'Date/Shift', 'User', 'ER Pts', 'Charge', 'RN', 'EMS/Triage', 'Techs', 'Other', 'RN Var', 'Grid Var', 'Notes');

	
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
	$this->Cell(30,10,'Variance Report Detail',0,0,'C');
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
	$this->SetFont('Arial','B',11);
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
var $aligns2 = array('L','C','R','R','R','R','R','R','R','R','R','R');


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
$accountId = 20;
$enterpriseId = Config::get('account')['enterpriseId'];
$deptId = 366;
	

$newdata = Config::get('db') -> get_results("select a.name as accountName, a.displayProd, DATE_FORMAT(u.lastStartDate,'%m/%d/%y') AS startDay, DATE_FORMAT(u.lastEndDate,'%m/%d/%y') AS endDay FROM `productiveAccount` a left join `productiveUser` as u on u.id={$userId} where a.id={$accountId} group by a.id");

//$newdata = Config::get('db') -> get_results("select a.name as accountName, a.displayProd, DATE_FORMAT(u.lastStartDate,'%m/%d/%y') AS startDay, DATE_FORMAT(u.lastEndDate,'%m/%d/%y') AS endDay FROM `productiveAccount` a LEFT JOIN `productiveUser` as u on u.id={$userId} where a.id={$acct} group by a.id");
$header3 = array('Unit', 'Date/Shift', 'User', 'ER Pts', 'Hold/Psy', '>5hrs', 'Hold/IP', 'Wtg Rm','51:50s','1:1 & 1:2','Code 3s','LWBS');

	
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
	$this->SetFont('Arial','B',11);
    for($i=0;$i<count($header3);$i++)
        $this->Cell($w[$i],7,$header3[$i],1,0,'C');
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
$accountId = 20;
$enterpriseId = Config::get('account')['enterpriseId'];
$deptId = 366;
	

$data2 = Config::get('db') -> get_results("select n.*, l.noreply, l.alertName, a.name as accountName, a.displayProd, (n.skill1val + n.skill2val + n.skill3val + n.skill4val + n.skill5val + n.skill6val) as Hours, DATE_FORMAT(n.dayDate,'%m/%d') as newDate, s.shift as shiftName, d.shiftsperDay, d.prodMeasure, CONCAT(ROUND(n.nproductivity,1),'%') as prod, u.last_name as charge, o.last_name as submitted, d.dept as deptName, DATE_FORMAT(CURDATE(),'%m%d%y') as filedate, u.last_name as Director, DATE_SUB(CURDATE(), INTERVAL (a.payPeriod-(ROUND(a.payPeriod-((((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod)-FLOOR((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod))*a.payPeriod),0))) DAY) AS startDay, DATE_ADD(CURDATE(), INTERVAL (ROUND(a.payPeriod-((((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod)-FLOOR((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod))*a.payPeriod),0)-1) DAY) AS endDay, a.id as accountId, SUM(CASE WHEN n.actualWHPimport>0 then n.hrsVarianceActual ELSE n.hrsVariance END) as hrsVarianceTotal, SUM(CASE WHEN n.actualWHPimport>0 then n.actualWHPimport ELSE n.actualWHP END) as actualWHPTotal, d.useGrid FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveShifts` as s on s.id = n.shift left join `productiveAccount` as a on a.id=n.accountId LEFT OUTER JOIN `productiveLabel` as l on l.id=a.label left outer join `productiveUser` as o on o.id=n.userId left outer Join `productiveUser` as u on u.id={$userId} where d.active>0 AND n.userId>0 AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate AND n.deptId=366 group by n.accountId, n.deptId, n.dayDate, n.shift  
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
$prod = $row2['prod'];

	$charge = round($row2['chargecount'],1);
	$triage = $row2['customNurse2']+ $row2['customNurse'];
	$blt = $row2['seccount'];
	$rn = $row2['antecount'];
	$techs = $row2['techcount']+$row2['sittercount'];
	$sitters = $row2['sittercount'];
	$orien = $row2['otherNurse1'];
	$other = $row2['otherNurse1']+$row2['otherNurse2'] + $row2['otherNurse3'];
	$patients = $row2['atotal'] + $row2['ltotal'];
	$rnVar = '-';
	$note = stripslashes($row2['note']);
	$totalRes = round(($row2['antecount'] + $row2['chargecount'] + $row2['customNurse'] + $row2['otherNurse1'] + $row2['otherNurse2'] + $row2['otherNurse3'] + $row2['seccount'] + $row2['sittercount'] + $row2['techcount']),1);


if(intval($row2['rnVariance'])==1){
	$variance = $row2['hrsVarianceTotal'];
}else{
	$variance = $row2['nvariance'];
}
$pdf->Row(array($row2['deptName'],$date,$row2['submitted'],$patients,$charge,$rn,$triage,$techs,$other,round(floatval($row2['nvariance']),1),round(floatval($row2['gvariance']),1),$note));
}

$pdf->AliasNbPages();
$pdf->SetFont('Arial','',10);
$pdf->AddPage2('L');
$pdf->SetWidths2(array(43, 30, 25, 20, 20, 20, 20, 20, 20, 20, 20, 20));
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
	
	$patients = $row2['atotal'] + $row2['ltotal'];
	
	$note = stripslashes($row2['note']);
	

if(intval($row2['rnVariance'])==1){
	$variance = $row2['hrsVarianceTotal'];
}else{
	$variance = $row2['nvariance'];
}
$pdf->Row2(array($row2['deptName'],$date,$row2['submitted'],$patients,$gr10,$gr5,$pdg,$brd,$row2['track1'],$row2['track2'],$row2['track3'],$row2['track4']));

}



$filename=substr($data2[0]['accountName'],0,4) . 'download' . $data2[0]['newDate'] . '.pdf';
$filelocation="/home/prnadmin/public_html/dailyreports/" . substr($data2[0]['accountName'],0,5) . $data2[0]['id'] . 'download' . $data2[0]['newDate'] . ".pdf";
$pdf->Output($filename,'D');

exit();

}

function getpdf20363($deptId){
//$deptId = $id; //(isset($_REQUEST['deptId'])?$_REQUEST['deptId']:'');
$pdfId = (isset($_REQUEST['pdfId'])?$_REQUEST['pdfId']:'');
$userId = Config::get('account')['id'];
//$userId = 411;
$prodCount = Config::get('account')['prodCount'];

require_once '.././inc/class.fpdf207.php';
	
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
	$triage = $row2['customNurse'] + round($row2['antecount'],0);
	$techs = $row2['techs'];
	$rn = $row2['customNurse2'];
	$psa = $row2['otherNurse1'];
	$orien = $row2['otherNurse2'];
	$sitters = $row2['sittercount'];
	$other = $row2['otherNurse3'] + $row2['seccount'];
	
	$patients = $row2['atotal'] + $row2['ltotal'];
	$rnVar = '-';
	$note = stripslashes($row2['note']);
	$totalRes = round(($row2['antecount'] + $row2['chargecount'] + $row2['customNurse'] + $row2['customNurse2'] + $row2['otherNurse1'] + $row2['otherNurse2'] + $row2['otherNurse3'] + $row2['seccount'] + $row2['sittercount'] + $row2['techcount']),1);


if(intval($row2['rnVariance'])==1){
	$variance = $row2['hrsVarianceTotal'];
}else{
	$variance = $row2['nvariance'];
}
$pdf->Row(array($row2['deptName'],$date,$patients,$charge,$triage,$techs,$rn,$psa,$orien,$sitters,$other,$note));
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
	$totalResources = round(($row2['antecount'] + $row2['chargecount'] + $row2['customNurse'] + $row2['customNurse2'] + $row2['otherNurse1'] + $row2['otherNurse2'] + $row2['otherNurse3'] + $row2['seccount'] + $row2['sittercount'] + $row2['techcount']),1);
	$totalRN = $row2['oneto10'];
	
	$patients = $row2['atotal'] + $row2['ltotal'];
	
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

function getpdf20364(){
$deptId = 364; //(isset($_REQUEST['deptId'])?$_REQUEST['deptId']:'');
$pdfId = (isset($_REQUEST['pdfId'])?$_REQUEST['pdfId']:'');
$userId = Config::get('account')['id'];
//$userId = 411;
$prodCount = Config::get('account')['prodCount'];

require_once '.././inc/class.fpdf207.php';
	
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
	$patients = $row2['atotal'] + $row2['ltotal'];
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
	
	$patients = $row2['atotal'] + $row2['ltotal'];
	
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

function getpdf20365($deptId){
//(isset($_REQUEST['deptId'])?$_REQUEST['deptId']:'');
$pdfId = (isset($_REQUEST['pdfId'])?$_REQUEST['pdfId']:'');
$userId = Config::get('account')['id'];
//$userId = 411;
$prodCount = Config::get('account')['prodCount'];

require_once '.././inc/class.fpdf20365.php';
	
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
	$triage = $row2['customNurse'] + round($row2['antecount'],0);
	$techs = $row2['techs'];
	$rn = $row2['customNurse2'];
	$psa = $row2['otherNurse1'];
	$orien = $row2['otherNurse2'];
	$sitters = $row2['sittercount'];
	$other = $row2['otherNurse3'] + $row2['seccount'];
	
	$patients = $row2['atotal'] + $row2['ltotal'];
	$rnVar = '-';
	$note = stripslashes($row2['note']);
	$totalRes = round(($row2['antecount'] + $row2['chargecount'] + $row2['customNurse'] + $row2['customNurse2'] + $row2['otherNurse1'] + $row2['otherNurse2'] + $row2['otherNurse3'] + $row2['seccount'] + $row2['sittercount'] + $row2['techcount']),1);


if(intval($row2['rnVariance'])==1){
	$variance = $row2['hrsVarianceTotal'];
}else{
	$variance = $row2['nvariance'];
}
$pdf->Row(array($row2['deptName'],$date,$patients,$charge,$triage,$techs,$rn,$psa,$orien,$sitters,$other,$note));
}

//$pdf->AliasNbPages();
$pdf->SetFont('Arial','',10);
$pdf->AddPage2('L');
$pdf->SetWidths2(array(30, 20, 16, 19, 19, 19, 25, 23, 25, 65));
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
	$totalResources = round(($row2['antecount'] + $row2['chargecount'] + $row2['customNurse'] + $row2['customNurse2'] + $row2['otherNurse1'] + $row2['otherNurse2'] + $row2['otherNurse3'] + $row2['seccount'] + $row2['sittercount'] + $row2['techcount']),1);
	$totalRN = $row2['oneto10'];
	
	$patients = $row2['atotal'] + $row2['ltotal'];
	
	$note = stripslashes($row2['note']);
	

if(intval($row2['rnVariance'])==1){
	$variance = $row2['hrsVarianceTotal'];
}else{
	$variance = $row2['nvariance'];
}
$pdf->Row2(array($row2['deptName'],$date,$patients,$es3,$es4,$es5,$var,$totalRN,$totalResources,$note));

}



$filename=substr($data2[0]['accountName'],0,4) . 'download' . $data2[0]['newDate'] . '.pdf';
$filelocation="/home/prnadmin/public_html/dailyreports/" . substr($data2[0]['accountName'],0,5) . $data2[0]['id'] . 'download' . $data2[0]['newDate'] . ".pdf";
$pdf->Output($filename,'D');

exit();

}

function getpdf20358(){
$deptId = 358; //(isset($_REQUEST['deptId'])?$_REQUEST['deptId']:'');
$pdfId = (isset($_REQUEST['pdfId'])?$_REQUEST['pdfId']:'');
$userId = Config::get('account')['id'];
//$userId = 411;
$prodCount = Config::get('account')['prodCount'];

require_once '.././inc/class.fpdf207.php';
	
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
	$patients = $row2['atotal'] + $row2['ltotal'];
	$rnVar = '-';
	$note = stripslashes($row2['note']);
	$totalRes = round(($row2['antecount'] + $row2['chargecount'] + $row2['customNurse'] + $row2['customNurse2'] + $row2['otherNurse1'] + $row2['otherNurse2'] + $row2['otherNurse3'] + $row2['seccount'] + $row2['sittercount'] + $row2['techcount']),1);


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
	
	$patients = $row2['atotal'] + $row2['ltotal'];
	
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

function getpdf20356(){
$deptId =  356; //(isset($_REQUEST['deptId'])?$_REQUEST['deptId']:'');
$pdfId = (isset($_REQUEST['pdfId'])?$_REQUEST['pdfId']:'');
$userId = Config::get('account')['id'];
//$userId = 411;
$prodCount = Config::get('account')['prodCount'];

require_once '.././inc/class.fpdf207.php';
	
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
	$patients = $row2['atotal'] + $row2['ltotal'];
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
	
	$patients = $row2['atotal'] + $row2['ltotal'];
	
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

function getpdf3(){
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

require_once '.././inc/class.fpdf3.php';
	
$data2 = Config::get('db') -> get_results("select n.*, n.id as recordId, d.rnVariance, a.displayProd, a.name as accountName, (n.skill1val + n.skill2val + n.skill3val + n.skill4val + n.skill5val + n.skill6val + n.skill7val + n.skill8val + n.skill9val + n.skill10val + n.addResourceHrs) as Hours, (select count(`dayDate`) from `productiveNewData` where `deptId`=n.deptId and `whpPlan`=0 and `dayDate`=n.dayDate) as shiftsperDay, s.shift as shiftName, d.shiftsperDay as unused, d.prodMeasure, n.antecount as rn, CONCAT(ROUND(n.nproductivity,1),'%') as prod, n.techcount as techs, u.last_name as charge, d.dept as deptName, DATE_FORMAT(n.dayDate,'%m/%d/%y') as newDate, DATE_FORMAT(CURDATE(),'%m%d%y') as filedate, u.last_name as Director, DATE_SUB(CURDATE(), INTERVAL (a.payPeriod-(ROUND(a.payPeriod-((((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod)-FLOOR((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod))*a.payPeriod),0))) DAY) AS startDay, DATE_ADD(CURDATE(), INTERVAL (ROUND(a.payPeriod-((((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod)-FLOOR((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod))*a.payPeriod),0)-1) DAY) AS endDay, a.id as accountId, SUM(CASE WHEN n.actualWHPimport>0 then n.hrsVarianceActual ELSE n.hrsVariance END) as hrsVarianceTotal, SUM(CASE WHEN n.actualWHPimport>0 then n.actualWHPimport ELSE n.actualWHP END) as actualWHPTotal FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveShifts` as s on s.id = n.shift left join `productiveAccount` as a on a.id=n.accountId left outer Join `productiveUser` as u on u.id={$userId} where n.activeRecord>0 AND d.active>0 AND n.userId>0 AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate and n.deptId=30 group by n.accountId, n.deptId, n.dayDate ORDER BY d.dept ASC, n.dayDate DESC");

$pdf = new PDF_MC_Table();
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',10);
$pdf->AddPage('L');

$pdf->SetWidths(array(45, 25, 40, 40, 40, 40, 40));

foreach($data2 as $row2){
	$date = $row2['newDate'];
	$visits = $row2['visitsSubmitted'];
	$hrs1 = $row2['hrs1Submitted'];
	$hrs2 = $row2['hrs2Submitted'];
	$hrs3 = $row2['hrs3Submitted'];
	$hrsTotal = round(($hrs1 + $hrs2 + $hrs3),3);
	
	$pdf->Row(array($row2['deptName'],$date,$visits,$hrs1,$hrs2,$hrs3,$hrsTotal));	
}


$filename=substr($data2[0]['accountName'],0,4) . 'download' . $data2[0]['newDate'] . '.pdf';
$filelocation="/home/prnadmin/public_html/dailyreports/" . substr($data2[0]['accountName'],0,5) . $data2[0]['id'] . 'download' . $data2[0]['newDate'] . ".pdf";
$pdf->Output($filename,'D');

exit();

}

function getpdf4(){
$deptId = (isset($_REQUEST['deptId'])?$_REQUEST['deptId']:'');
$pdfId = (isset($_REQUEST['pdfId'])?$_REQUEST['pdfId']:'');
$userId = Config::get('account')['id'];
$accountId = Config::get('account')['accountId'];
$role = Config::get('account')['role'];
$enterpriseId = Config::get('account')['enterpriseId'];
$prodCount = Config::get('account')['prodCount'];
$deptId =  Config::get('account')['deptNum'];


require_once '.././inc/class.fpdf4.php';
	
$data2 = Config::get('db') -> get_results("select n.*, n.id as recordId, d.rnVariance, a.displayProd, a.name as accountName, (n.skill1val + n.skill2val + n.skill3val + n.skill4val + n.skill5val + n.skill6val + n.skill7val + n.skill8val + n.skill9val + n.skill10val + n.addResourceHrs) as Hours, (select count(`dayDate`) from `productiveNewData` where `deptId`=n.deptId and `whpPlan`=0 and `dayDate`=n.dayDate) as shiftsperDay, s.shift as shiftName, d.shiftsperDay as unused, d.prodMeasure, n.antecount as rn, CONCAT(ROUND(n.nproductivity,1),'%') as prod, n.techcount as techs, u.last_name as charge, d.dept as deptName, DATE_FORMAT(n.dayDate,'%m/%d/%y') as newDate, DATE_FORMAT(CURDATE(),'%m%d%y') as filedate, u.last_name as Director, DATE_SUB(CURDATE(), INTERVAL (a.payPeriod-(ROUND(a.payPeriod-((((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod)-FLOOR((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod))*a.payPeriod),0))) DAY) AS startDay, DATE_ADD(CURDATE(), INTERVAL (ROUND(a.payPeriod-((((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod)-FLOOR((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod))*a.payPeriod),0)-1) DAY) AS endDay, a.id as accountId, SUM(CASE WHEN n.actualWHPimport>0 then n.hrsVarianceActual ELSE n.hrsVariance END) as hrsVarianceTotal, SUM(CASE WHEN n.actualWHPimport>0 then n.actualWHPimport ELSE n.actualWHP END) as actualWHPTotal FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveShifts` as s on s.id = n.shift left join `productiveAccount` as a on a.id=n.accountId left outer Join `productiveUser` as u on u.id={$userId} where n.activeRecord>0 AND d.active>0 AND n.userId>0 AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate AND n.deptId=166 group by n.accountId, n.deptId, n.dayDate, n.shift  
ORDER BY d.dept ASC, n.dayDate DESC, n.shift DESC");

$pdf = new PDF_MC_Table();
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',10);
$pdf->AddPage('L');
$pdf->SetWidths(array(45, 20, 15, 25, 20, 20, 18, 15, 15, 15, 65));	

$shifts = intval($row2['shiftsperDay']);

if($shifts<=0){
	$shiftcount=1;
}else{
	$shiftcount=$shifts;
}

foreach($data2 as $row2){
	
	$date = $row2['newDate'] . ' - ' . $row2['shiftName']; 
	$prod = $row2['prod'];
	$rn = $row2['antecount'] + $row2['ocount'] + $row2['ldcount'];
	$techs = $row2['techs'];
	$mothers = $row2['oneto1'];
	$other = $row2['otherNurse1'] + $row2['otherNurse3'] + $row2['otherNurse2'];
	$patients = $row2['atotal'] + $row2['ltotal'] + $row2['obed'];
	$actualWHP = round(floatval($row2['customProd']),1);
	$note = stripslashes($row2['note']);
	$sitters = $row2['sittercount'];
	//$prod = $row2['prod'];
	$totalHours = round((((($row2['antecount'] + $row2['ocount'] + $row2['ldcount'] + $row2['chargecount'] + $row2['customNurse'] + $row2['otherNurse1'] + $row2['otherNurse2'] + $row2['otherNurse3'] + $row2['seccount'] + $row2['sittercount'] + $row2['techcount']) * 24) / $shiftcount) + $row2['addResourceHrs']),1);
	

if(intval($row2['rnVariance'])==1){
	$variance = $row2['hrsVarianceTotal'];
}else{
	$variance = round(floatval($row2['nvariance']),0);
}
$pdf->Row(array($row2['deptName'],$date,$prod,$actualWHP,$variance,$patients,$mothers,$rn,$techs,$other,$note));	
}


$filename=substr($data2[0]['accountName'],0,4) . 'download' . $data2[0]['newDate'] . '.pdf';
$filelocation="/home/prnadmin/public_html/dailyreports/" . substr($data2[0]['accountName'],0,4) . 'download' . $data2[0]['newDate'] . ".pdf";
$pdf->Output($filename,'D');

exit();

}



function fpdfTuality(){
$userId = Config::get('account')['id'];
$accountId = Config::get('account')['accountId'];
$role = Config::get('account')['role'];
$enterpriseId = Config::get('account')['enterpriseId'];

$pdf = new PDF_MC_Table();

if(intval($role)<8 && !$idsearch){
		$searchAdd = "AND n.accountId ={$accountId} AND n.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId` ={$userId})";
	}else if(intval($role)>7 && intval($role)<=12 && !$idsearch){
		$searchAdd = "AND n.accountId ={$accountId}";
	}else if(intval($role)>12 && !$idsearch){
		$searchAdd = "AND a.enterpriseId ={$enterpriseId}";
	}else{
		$searchAdd = '';
	}
	
$data2 = Config::get('db') -> get_results("select n.atotal, n.deptId, n.otherNurse1, n.otherNurse2, n.otherNurse3, n.id as recordId, n.ocount, n.nvariance, n.ldcount, n.obed, n.addResourceHrs, a.displayProd, a.name as accountName, n.procedureCount, n.skill1val, n.skill2val, n.skill3val, n.skill4val, n.skill5val, n.skill6val, n.skill7val, n.skill8val, n.skill9val, n.skill10val, (n.skill1val + n.skill2val + n.skill3val + n.skill4val + n.skill5val + n.skill6val + n.skill7val + n.skill8val + n.skill9val + n.skill10val + n.addResourceHrs) as Hours, n.actualWHP, (select count(`dayDate`) from `productiveNewData` where `deptId`=n.deptId and `whpPlan`=0 and `dayDate`=n.dayDate) as shiftsperDay, n.antecount, n.chargecount, n.customNurse, n.seccount, n.sittercount, n.techcount, n.totalHours, s.shift as shiftName, d.shiftsperDay as unused, d.rnVariance, d.prodMeasure, n.antecount as rn, CONCAT(ROUND(n.nproductivity,1),'%') as prod, n.hrsVariance, n.techcount as techs, n.note, u.last_name as charge, d.dept as deptName, DATE_FORMAT(n.dayDate,'%m/%d/%y') as newDate, DATE_FORMAT(CURDATE(),'%m%d%y') as filedate, u.last_name as Director, DATE_SUB(CURDATE(), INTERVAL (a.payPeriod-(ROUND(a.payPeriod-((((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod)-FLOOR((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod))*a.payPeriod),0))) DAY) AS startDay, DATE_ADD(CURDATE(), INTERVAL (ROUND(a.payPeriod-((((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod)-FLOOR((DAYOFYEAR(curdate())-a.payPeriodFirst)/a.payPeriod))*a.payPeriod),0)-1) DAY) AS endDay, a.id as accountId, SUM(CASE WHEN n.actualWHPimport>0 then n.hrsVarianceActual ELSE n.hrsVariance END) as hrsVarianceTotal, SUM(CASE WHEN n.actualWHPimport>0 then n.actualWHPimport ELSE n.actualWHP END) as actualWHPTotal FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveShifts` as s on s.id = n.shift left join `productiveAccount` as a on a.id=n.accountId left outer Join `productiveUser` as u on u.id={$userId} where n.activeRecord>0 AND d.active>0 AND n.userId>0 AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate $searchAdd group by n.accountId, n.deptId, n.dayDate, n.shift  
ORDER BY d.dept ASC, n.dayDate DESC, n.shift DESC");
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',10);
$pdf->AddPage('L');
$pdf->SetWidths(array(45, 20, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 65));
$shifts = intval($row2['shiftsperDay']);

if($shifts<=0){
	$shiftcount=1;
}else{
	$shiftcount=$shifts;
}

foreach($data2 as $row2){
	
	$date = $row2['newDate'] . ' - ' . $row2['shiftName']; 
	$prod = $row2['prod'];
	$rn = (24 * ($row2['antecount'] + $row2['chargecount'] + $row2['ocount'] + $row2['ldcount'])) / $shiftcount;
	$techs = (24 * $row2['techs']) / $shiftcount;
	$other = (24 * ($row2['sittercount'] + $row2['otherNurse1'] + $row2['otherNurse2'] + $row2['otherNurse3'])) / $shiftcount;
	$patients = $row2['atotal'] + $row2['ltotal'] + $row2['obed'];
	$actualWHP = '-';
	$note = stripslashes($row2['note']);
	$totalHours = round((((($row2['antecount'] + $row2['ocount'] + $row2['ldcount'] + $row2['chargecount'] + $row2['customNurse'] + $row2['otherNurse1'] + $row2['otherNurse2'] + $row2['otherNurse3'] + $row2['seccount'] + $row2['sittercount'] + $row2['techcount']) * 24) / $shiftcount) + $row2['addResourceHrs']),1);

if(intval($row2['rnVariance'])==1){
	$variance = $row2['hrsVarianceTotal'];
}else{
	$variance = $row2['nvariance'];
}

$pdf->Row(array($row2['deptName'],$date,$prod,$actualWHP,$variance,$patients,$totalHours,$rn,$techs,$other,$note));
}
$filename=substr($data2[0]['accountName'],0,4) . 'download' . $data2[0]['newDate'] . '.pdf';
$filelocation="/home/prnadmin/public_html/dailyreports/" . substr($data2[0]['accountName'],0,4) . 'download' . $data2[0]['newDate'] . ".pdf";
$pdf->Output($filename,'D');
exit();

}

/////////set dates

function setDates(){
$userId = Config::get('account')['id'];

$start = isset($_REQUEST['start']) && ($_REQUEST['start'] != '') ? $_REQUEST['start'] : date('Y-m-d');
$end = isset($_REQUEST['end']) && ($_REQUEST['end'] != '') ? $_REQUEST['end'] : date('Y-m-d');
$startDate = date('Y-m-d',strtotime($start));
$endDate = date('Y-m-d',strtotime($end));

if($startDate && $endDate){
$data = array(
				'lastStartDate'=>$startDate,
				'lastEndDate'=>$endDate
            );
			
$updateWhere = array('id' =>$userId);
$newDates = Config::get('db')->update('productiveUser',$data,$updateWhere);
echo json_encode(array('success'=>true,'start'=>$startDate));
return true;
}
echo json_encode(array('success'=>true,'message'=>'dates not updated'));
return true;
}

////Update Discharges`
function updatedischarges($deptId,$dayDate,$trackDischarges) {

		$output = 1;
		if($deptId && $dayDate){
		$data = array(
				'trackDischarges'=>$trackDischarges
            );
			
		$updateWhere = array('deptId' =>$deptId,
							'dayDate' =>$dayDate
							);
		$newDates = Config::get('db')->update('productiveNewData',$data,$updateWhere);
		
		}
		return $output;
}

////SEND Update Email`
function sendupdateEmail($dataId,$aMsg,$hrsVar) {
	
		$dbEmail = Config::get('db')->get_results("select n.*, d.dept, u.first_name, u.email, l.labelName, l.login, l.noreply, l.labelName, l.supportEmail FROM `productiveNewData` n LEFT JOIN `ProductiveDept` as d on d.id = n.deptId LEFT OUTER JOIN `productiveDeptXref` as x on x.deptId = n.deptId LEFT OUTER JOIN `productiveUser` as u on u.id = x.userId LEFT JOIN `productiveAccount` as a on a.id = n.accountId LEFT JOIN `productiveLabel` as l on l.id = a.label where n.id={$dataId} and n.daydate = curdate() AND x.unitAssigned=0 AND u.emailAlerts>0 and u.active>=1 group by u.id");
		//$output = '';
		$output = 1;
		if($dbEmail){
		
		foreach($dbEmail as $mail){
		$subject = $mail['labelName'] . " Report Alert";
		$labelName = $mail['labelName'];
		$noreply = $mail['noreply'];
		$supportEmail = $mail['supportEmail'];
		$alertName = $mail['labelName'];
		$first = $mail['first_name'];
		$dept = $mail['dept'];
		$prod = $mail['nproductivity'] . "%";
		$var = $mail['nvariance'];
		$to = stripslashes($mail['email']);
		$link = "views/v.php?i=" . $dataId . "&l=" . $mail['login'];
		$url = "https://www.productivern.com/" . $link;


		$message = "<html>
		<head>
		<title>$labelName Report Alert</title>
		</head>
		<body>
		<table>
		<tr>
		<td>$first,</td>
		</tr>
		<tr></tr>
		<tr>
		<td>A $labelName Report has been submitted with $aMsg a variance of $hrsVar in $dept.</td>
		</tr>
		<tr></tr>
		<tr>
		<td>To view this report, go to $url</td>
		</tr>
		<tr></tr>
		<tr></tr>
		<tr>
		<th>Thank you for using $labelName!</th>
		</tr>
		</table>
		<tr></tr><tr></tr>
		<tr><p><h5>
		NOTE: The information contained in this message may be privileged and confidential and protected from disclosure.  If the reader of this message is not the intended recipient, or an employee or agent responsible for delivering this message to the intended recipient, you are hereby notified that any dissemination, distribution or copying of this communication is strictly prohibited. If you have received this communication in error, please notify us immediately by replying to $supportEmail and deleting it from your computer.
		</h5></p></tr>
		<tr><p><h5>
		Thank you for considering the impact of printing emails on our environment.  Please don’t print unless it is necessary!
		</h5></p></tr>
		</body>
		</html>
		";

		// Always set content-type when sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

		// More headers
		$headers .= 'From: <' . $noreply . '>' . "\r\n";

		mail($to,$subject,$message,$headers);
		}
		return $output;
		}else{
		return $output;
		}
}

////SEND Update Email`
function sendescalationResponse($dataId,$response) {
$userId = Config::get('account')['id'];
$accountId = Config::get('account')['accountId'];
$role = Config::get('account')['role'];
		
		$dbEscalation = Config::get('db')->get_results("select n.*, d.dept, ae.escalation as escType, x.primaryUnit, u.first_name, u.role, u.email, u.email, l.labelName, l.noreply, l.login FROM `productiveEscalations` n LEFT JOIN `ProductiveDept` as d on d.id = n.deptId LEFT OUTER JOIN `productiveAcctEscalations` as ae on ae.id =n.escalation LEFT OUTER JOIN `productiveDeptXref` as x on x.deptId=n.deptId LEFT OUTER JOIN `productiveUser` as u on u.id=x.userId LEFT JOIN `productiveAccount` as a on a.id = n.accountId LEFT JOIN `productiveLabel` as l on l.id = a.label where (u.id=n.userId OR (x.primaryUnit>=5 AND u.role>=5 AND u.emailEscalations>0)) and u.active>=1 AND n.id={$dataId} and u.email like '%@%' group by u.email");
		$output = '';
		
		if($dbEscalation){
		foreach($dbEscalation as $mail){
		$subject = $mail['labelName'] . " Escalation Update";
		$labelName = $mail['labelName'];
		$noreply = $mail['noreply'];
		$first = $mail['first_name'];
		$dept = $mail['dept'];
		$escType = $mail['escType'];
		$label = $mail['login'];
		//$note = $mail['enote'];
		$to = $mail['email'];
		$url = "https://www.productivern.com/views/esc.php?l=" . $label;


		$message = "<html>
		<head>
		<title>$labelName $escType Escalation Update for $dept</title>
		</head>
		<body>
		<table>
		<tr>
		<td>$first,</td>
		</tr>
		<tr></tr>
		<tr>
		<td>The Escalation Alert of $escType for $dept was just updated.</td>
		</tr>
		<tr></tr>
		<tr>Escalation Response:</tr>
		<td>$response</td>
		<tr>
		<td>To view this escalation, go to $url</td>
		</tr>
		<tr></tr>
		<tr></tr>
		<tr>
		<th>Thank you for using $labelName!</th>
		</tr>
		</table>
		<tr></tr><tr></tr>
		<tr><p><h5>
		NOTE: The information contained in this message may be privileged and confidential and protected from disclosure.  If the reader of this message is not the intended recipient, or an employee or agent responsible for delivering this message to the intended recipient, you are hereby notified that any dissemination, distribution or copying of this communication is strictly prohibited. If you have received this communication in error, please notify us immediately by replying to support@productivern.com and deleting it from your computer.
		</h5></p></tr>
		<tr><p><h5>
		Thank you for considering the impact of printing emails on our environment.  Please don’t print unless it is necessary!
		</h5></p></tr>
		</body>
		</html>
		";

		// Always set content-type when sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

		// More headers
		//$headers .= 'From: <noreply@productivern.com>' . "\r\n";
		$headers .= 'From: <' . $noreply . '>' . "\r\n";

		mail($to,$subject,$message,$headers);
		}
		return $output;
		}else{
		return $output;
		}
}

////SEND Update Email`
function sendescalationEmail($dataId,$deptId,$esc,$comment,$escId,$label) {
$userId = Config::get('account')['id'];
//$accountId = Config::get('account')['accountId'];
$role = Config::get('account')['role'];
		
		if(intval($dataId)>0){
		$dbEscalation = Config::get('db')->get_results("select n.*, d.dept, d.prodMeasure, d.prodMeasure1, ae.escalation as escType, u.first_name, u.role, u.email, l.labelName, l.supportEmail, l.noreply, l.login FROM `productiveNewData` n LEFT JOIN `ProductiveDept` as d on d.id = n.deptId LEFT OUTER JOIN `productiveAcctEscalations` as ae on ae.id ={$esc} LEFT OUTER JOIN `productiveDeptXref` as x on x.deptId = n.deptId LEFT OUTER JOIN `productiveUser` as u on u.id =x.userId LEFT JOIN `productiveAccount` as a on a.id = n.accountId LEFT JOIN `productiveLabel` as l on l.id = a.label where x.primaryUnit>=5 AND u.role>=5 AND n.id={$dataId} and u.emailEscalations>0 and u.active>=1 and u.email like '%@%' group by u.id");
		}else{
		$dbEscalation = Config::get('db')->get_results("select n.*, d.dept, d.prodMeasure, d.prodMeasure1, ae.escalation as escType, u.first_name, u.role, u.email, l.labelName, l.supportEmail, l.noreply, l.login FROM `productiveNewData` n LEFT JOIN `ProductiveDept` as d on d.id = n.deptId LEFT OUTER JOIN `productiveAcctEscalations` as ae on ae.id ={$esc} LEFT OUTER JOIN `productiveDeptXref` as x on x.deptId = n.deptId LEFT OUTER JOIN `productiveUser` as u on u.id =x.userId LEFT JOIN `productiveAccount` as a on a.id = n.accountId LEFT JOIN `productiveLabel` as l on l.id = a.label where x.primaryUnit>=5 AND u.role>=5 AND n.deptId={$deptId} and u.emailEscalations>0 and u.active>=1 and u.email like '%@%' group by u.id");
		}
		
		$output = '';
		
		if($dbEscalation){
		foreach($dbEscalation as $mail){
		$subject = $mail['labelName'] . " Escalation Alert";
		$labelName = $mail['labelName'];
		$first = $mail['first_name'];
		$dept = $mail['dept'];
		$escType = $mail['escType'];
		$prodMeasure = intval($mail['prodMeasure1']);
		$to = $mail['email'];
		$noreply = $mail['noreply'];
		$supportEmail = $mail['supportEmail'];
		
		if($prodMeasure==2){
		$url = "https://www.productivern.com/views/st.php?c=" . $escId . "&l=" . $label;
		}else{
		$url = "https://www.productivern.com/views/sts.php?c=" . $escId . "&l=" . $label;	
		}

		$message = "<html>
		<head>
		<title>$labelName Escalation Alert for $dept</title>
		</head>
		<body>
		<table>
		<tr>
		<td>$first,</td>
		</tr>
		<tr></tr>
		<tr>
		<td>An Escalation Alert for $escType was just submitted for $dept.</td>
		</tr>
		<tr></tr>
		<tr>Escalation Notes:</tr>
		<td>$comment</td>
		<tr>
		<td>To view this report, go to $url</td>
		</tr>
		<tr></tr>
		<tr></tr>
		<tr>
		<th>Thank you for using $labelName!</th>
		</tr>
		</table>
		<tr></tr><tr></tr>
		<tr><p><h5>
		NOTE: The information contained in this message may be privileged and confidential and protected from disclosure.  If the reader of this message is not the intended recipient, or an employee or agent responsible for delivering this message to the intended recipient, you are hereby notified that any dissemination, distribution or copying of this communication is strictly prohibited. If you have received this communication in error, please notify us immediately by replying to $supportEmail and deleting it from your computer.
		</h5></p></tr>
		<tr><p><h5>
		Thank you for considering the impact of printing emails on our environment.  Please don’t print unless it is necessary!
		</h5></p></tr>
		</body>
		</html>
		";

		// Always set content-type when sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

		// More headers
		$headers .= 'From: <' . $noreply . '>' . "\r\n";
		//$headers .= 'From: <noreply@productivern.com>' . "\r\n";

		mail($to,$subject,$message,$headers);
		}
		return $output;
		}else{
		return $output;
		}
}



/////////////////////////////////////
// SEND AN SMS
function SendSMS() {
    $userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
//    $role = Config::get('account')['role'];

    $message = isset($_REQUEST['message']) ? $_REQUEST['message'] : '';
    $group = isset($_REQUEST['group']) ? $_REQUEST['group'] : '';
    $recips = isset($_REQUEST['recipients']) ? $_REQUEST['recipients'] : array();


    //echo "group".$group;

    if (count($recips) > 0 && $message) {
        $now = time();
        $type = 1;
        $numbers = array();
        $messages = array();
        $keywords = array();
        $psword = "J8775bcgEE2065";
        $slooce = "jobalarm45";
        $brandId = 0;


        foreach ($recips as $k => $n) {
            $candidateId = $n['id'];

              /* $query = "select c.*,sum((case m.type when 1 then 1 else 0 end) + (case m.type when 2 then 1 else 0 end) - (case m.type when 3 then 1 else 0 end)) AS mgCount, x.keyword, x.keyword2, x.brandId as brandX, x.brandOrig, x.promo, x.shortCode, g.id as cgGroup 
				from `candidate` c
				LEFT OUTER JOIN `group` as g on g.Id=(select cg.groupId from `candidate_group` cg where cg.candidateId=c.id and cg.accountId={$accountId} order by cg.groupdate desc limit 0,1)				
				LEFT OUTER JOIN `sms_messages` as m on m.candidateId = c.id
				LEFT JOIN `candidateXref` as x on x.candidateId = c.id 
				LEFT JOIN `account_brand` as a on a.brandId=x.brandId 
				where c.id ={$candidateId} and (x.brandId = a.brandId or x.brandId=6) and (m.type=1 OR m.type=2 or m.type=3) group by c.mobile"; */
			
			$query = "select x.*, 
			sum((case m.type when 1 then 1 else 0 end) + (case m.type when 2 then 1 else 0 end) - (case m.type when 3 then 1 else 0 end)) AS mgCount, 
			c.mobile, c.zip FROM `candidateXref` x 
			LEFT JOIN `sms_messages` as m on m.candidateId = x.candidateId 
			LEFT JOIN `candidate` as c on c.id = x.candidateId 
			WHERE x.candidateId ={$candidateId} and x.accountId ={$accountId} and (m.type=1 OR m.type=2 or m.type=3 or m.type=8) group by x.candidateId";


            $dbData = Config::get('db')->get_results($query);
            $mobile = (isset($dbData[0]['mobile'])) ? $dbData[0]['mobile'] : 0;
            $shortCode = (isset($dbData[0]['shortCode'])) ? $dbData[0]['shortCode'] : 0;
            $promo = (isset($dbData[0]['promo'])) ? $dbData[0]['promo'] : 0;
			$count = (isset($dbData[0]['mgCount'])) ? $dbData[0]['mgCount'] : 0;
            $zip = (isset($dbData[0]['zip'])) ? $dbData[0]['zip'] : 0;
            $brandId = (isset($dbData[0]['brandX'])) ? $dbData[0]['brandX'] : 0;
            $brandOrig = (isset($dbData[0]['brandOrig'])) ? $dbData[0]['brandOrig'] : 0;
            $keyword = (isset($dbData[0]['keyword'])) ? $dbData[0]['keyword'] : '';
            $keyword2 = (isset($dbData[0]['keyword2'])) ? $dbData[0]['keyword2'] : '';
            //$cxId = (isset($dbData[0]['id'])) ? $dbData[0]['candidateId'] : 0;
            //$accountXref = (isset($dbData[0]['accountXref'])) ? $dbData[0]['accountXref'] : 0;
            //$userXref = (isset($dbData[0]['userXref'])) ? $dbData[0]['userXref'] : 0;
            //$recId = (isset($dbData[0]['recId'])) ? $dbData[0]['recId'] : 0;

            if (intval($promo) > 0 && intval($promo) < 3 && intval($count)<4) {

                $msgId = "";
                $msgId .= "";
                $msgId .= $mobile . $zip . $now;


                $data = array(
                    'accountId'=>$accountId,
                    'userId'=>$userId,
                    'brandId'=>$brandOrig,
                    'candidateId'=>$candidateId,
                    'type'=>$type,
                    'message'=>Config::get('db')->filter($message),
                    'messageId'=>Config::get('db')->filter($msgId)
                );
                Config::get('db')->insert('sms_messages',$data);

                if ($candidateId){
                    Config::get('db')->query("update `candidate` set `stageId` =2 WHERE `id` ='{$candidateId}'");
					Config::get('db')->query("update `candidateXref` set `type` =1 WHERE `candidateId` ='{$candidateId}' AND `brandOrig`='{$brandOrig}'");
					}

                $xmlMsg = "";
                $xmlMsg .= "<message id=\"".$msgId."\">";
                $xmlMsg .= "<partnerpassword>".$psword."</partnerpassword>";
                $xmlMsg .= "<content><![CDATA[" . $message . "]]></content>";
                $xmlMsg .= "</message>";

                $messages[] = $xmlMsg;
                $mobile = "1" . $mobile;
                $numbers[] = $mobile;
                $keywords[] = $keyword2;

                //if (!$dbData[0]['cgGroup'] && !$group){
                //$grp = 13;
                // updateCandidateGroup($accountId,$candidateId,$grp,$userId);
                // }

//                if (!$dbData[0]['cgGroup'] && !$group){
//               	 }

                if ($group) {
                    updateCandidateGroup($accountId,$candidateId,$group,$userId);
				} else {
                    $group = 13;
                    updateCandidateGroup($accountId,$candidateId,$group,$userId);
                }

            }
        }

        $smsoptions = Array(
            'numbers' => $numbers,
            'message' => $messages,
            'keyword' => $keywords,
            'login' => $slooce,
            'shortCode' => $shortCode

        );
        $result = '';

        if ($message && strlen(trim($message)) > 0) {

            $result = sendNow($smsoptions);

        }
		
		//Config::get('db')->query("update `candidateXref` set `userXref`='{$userId}', `groupOld`='{$group}' WHERE `candidateId`='{$candidateId}' AND `accountId`='{$accountId}'");
		
        echo json_encode(array('success' => true, 'msg' => 'Message Sent', 'output'=>$result));
        //echo json_encode($result);
    }else{
		if ($group) {
			foreach ($recips as $k => $n) {
            $candidateId = $n['id'];
			updateCandidateGroup($accountId,$candidateId,$group,$userId);
			Config::get('db')->query("update `candidateXref` set `userXref`='{$userId}', `groupOld`='{$group}' WHERE `candidateId`='{$candidateId}' AND `accountId`='{$accountId}'");
			}
		}
        echo json_encode(array('success' => false, 'group' => 'Updated'));
    }
}



/////////////////////////////////////
// SEND SMS NOW?
function sendNow($options) {
    //if (is_array($options['numbers']) && count($options['numbers']) > 0) {
    file_put_contents("sendsmslog.txt", print_r($options,true));
    $post = '';
    $keyword = '';
    $header = Array("Content-Type: application/xml");
    $mobile = '';
    $post = $options['message'];
    $keyword = $options['keyword'];
    $slooce = $options['login'];
    $shortCode = $options['shortCode'];
    $keyword2 = "JOBALARM58046";
	$keyword3 = "PN18339330602";
    $output = "";

    foreach($options['numbers'] as $k => $n) {
        //foreach($options['numbers'] as $n) {
        $mobile = $n;
        //if (intval($shortCode)==47711){
        //    $url = 'http://sloocetech.net:8084/spi-war/spi/jobalarm45/' . $mobile . '/' . $keyword[$k] . '/messages/mt';
        //}else{
        //    $url = 'https://jobalarm.cloud.sloocetech.net/slooce_apps/spi/jobalarm45/' . $mobile . '/' . $keyword2 . '/messages/mt';
        //}
      
	   $url = 'https://jobalarm.cloud.sloocetech.net/slooce_apps/spi/jobalarm45/' . $mobile . '/' . $keyword3 . '/messages/startmt';



        $output .= curl_request($slooce, $url, $post[$k], $header);

    }
    return $output;
}

/////////////////////////////////////
// Get Census Average
function getCensusAvg($endDate,$deptId,$shift) {
	$output = 0;
	$avgCensus = Config::get('db')->get_results("		
        SELECT 
           AVG(n.oneto1 + n.oneto2 + n.oneto3 + n.oneto4 + n.oneto5 + n.oneto6 + n.oneto7 + n.oneto8) AS censusAvg
        FROM
            `productiveNewData` n
        WHERE
            n.userId>0 AND n.deptId={$deptId} AND n.shift={$shift} 
        AND 
			n.dayDate BETWEEN DATE_SUB('{$endDate}', INTERVAL 30 DAY) AND '{$endDate}'         
    ");	

	if($agvCensus){
		$output = round($avgCensus[0]['censusAvg'],1);
	}else{
		$output = 0;
	}
    return $output;
}

function send_messages_start($options) {
    $post = '';
    $mobile = '';
	$keyword = '';
    $header = Array('Content-Type: application/xml');
    $post = $options['message'];
	$mobile = $options['numbers'];
    $output = "";
	$slooce = "jobalarm45";
	$keyword="JOBALARM58046";
	
	$keyword3 = "PN18339330602";
	$url = 'https://jobalarm.cloud.sloocetech.net/slooce_apps/spi/jobalarm45/' . $mobile . '/' . $keyword3 . '/messages/startmt';
    //$url = 'https://jobalarm.cloud.sloocetech.net/slooce_apps/spi/jobalarm45/' . $mobile . '/' . $keyword . '/messages/start';
    $output = curl_request($slooce, $url, $post, $header);
	
    
   
    return $output; 
    
}

///////////////////////////////////
///SUPPORT REQUEST

function Support() {
if ( isset($_POST['email']) && isset($_POST['name']) && isset($_POST['message']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ) {
 
  // detect & prevent header injections
  $test = "/(content-type|bcc:|cc:|to:)/i";
  foreach ( $_POST as $key => $val ) {
    if ( preg_match( $test, $val ) ) {
	   echo json_encode(array('success'=>false));
      exit();
    }
  }
  $company = (isset($_POST["name"])  && strlen($_POST['name']) > 0) ?  ", ".$_POST['company'] : '';
  //send email
  mail( "rstrenger@productivern.com", "ProductiveRN Support Request: ".$_POST['type'].$company, $_POST['name']."\r\n\r\n".$_POST['phone']."\r\n\r\n".$_POST['email']."\r\n\r\n".$_POST['message'], "From: ProductiveRN Support <support@productivern.com>"  );
   echo json_encode(array('success'=>true));
   exit();
	}
}


////////////////////////////////////////
// SEND A WEB CURL REQUEST
function curl_request($user, $url, $postdata = null, $header){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    //curl_setopt($ch, CURLOPT_SSLVERSION, 3);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    //curl_setopt($ch, CURLOPT_HEADER, false);
    //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
    $server_output = curl_exec($ch);
    curl_close($ch);
    // echo $server_output;
    return $server_output;
}
