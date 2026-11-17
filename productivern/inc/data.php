<?php
ini_set('display_errors',0);
include "initializer.php";
require_once '.././inc/class.db.php';
//require_once '.././inc/class.jatwitter.php';
require_once '.././inc/config.php';
//require_once '.././inc/class.phpmailer.php';
//require_once '.././inc/FPDF/fpdf.php';
//require_once '.././inc/class.smtp.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../inc/PHPMailer/src/Exception.php';
require '../inc/PHPMailer/src/PHPMailer.php';
require '../inc/PHPMailer/src/SMTP.php';



if ($_REQUEST['req'] != "forgot" && $_REQUEST['req'] != "closesafetytoken" && $_REQUEST['req'] != "closeescalationtoken" && $_REQUEST['req'] != "closeqbltoken" && $_REQUEST['req'] != "senddemoqblsms" && $_REQUEST['req'] != "submitdemolead" && $_REQUEST['req'] != "submitqblcalc" && $_REQUEST['req'] != "updateqblcalc" && $_REQUEST['req'] != "qblhemorrhage") {

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

// CSRF check for state-changing requests
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $csrfHeader = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        if (empty($_SESSION['csrf_token']) || $csrfHeader !== $_SESSION['csrf_token']) {
            echo json_encode(array('success' => false, 'message' => 'Invalid request'));
            exit();
        }
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
	case 'demomode':
        header('Content-Type: application/json');
        demoMode();
        break;
	case 'getstaff':
        header('Content-Type: application/json');
        getStaff();
        break;
	case 'getportal':
        header('Content-Type: application/json');
        getPortal();
        break;
	case 'getportaldepts':
        header('Content-Type: application/json');
        getportalDepts();
        break;
	case 'changetransfer':
        header('Content-Type: application/json');
        changeTransfer();
        break;
	case 'updateportal':
        header('Content-Type: application/json');
        updatePortal();
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
	case 'getPolicies':
        header('Content-Type: application/json');
        GetPoliciesTableData();
        break;
	case 'uploadPolicy':
        header('Content-Type: application/json');
        UploadPolicy();
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
	case 'newuseremail':
        header('Content-Type: application/json');
        newUserEmail();
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
	case 'updatetxtVar':
        header('Content-Type: application/json');
        UpdatetxtVar();
        break;
	case 'updateEscAlerts':
        header('Content-Type: application/json');
        UpdateEscAlerts();
        break;
	case 'updateemailAlerts':
        header('Content-Type: application/json');
        UpdateemailAlerts();
        break;
	case 'updateUser2':
        header('Content-Type: application/json');
        updateUser2();
        break;
	case 'forgot':
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
	case 'getchartData':
        header('Content-Type: application/json');
        GetchartData();
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
	case 'getcoverage':
        header('Content-Type: application/json');
        Getcoverage();
        break;
	case 'getunitcoverage':
        header('Content-Type: application/json');
        GetUnitCoverage();
        break;
	case 'getusercoverage':
        header('Content-Type: application/json');
        GetUserCoverage();
        break;
	case 'changeusercoverage':
        header('Content-Type: application/json');
        ChangeUserCoverage();
        break;
	case 'changecoverage':
        header('Content-Type: application/json');
        ChangeCoverage();
        break;
	case 'gridupdate':
        header('Content-Type: application/json');
        gridUpdate();
        break;
	case 'gridupdate5':
        header('Content-Type: application/json');
        gridUpdate5();
        break;
	case 'gridupdate3':
        header('Content-Type: application/json');
        gridUpdate3();
        break;
	case 'addserviceLine':
        header('Content-Type: application/json');
        addServiceLine();
        break;
	case 'updateData':
        header('Content-Type: application/json');
        UpdateData();
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
	case 'GetTransfers':
        header('Content-Type: application/json');
        getTransfers();
        break;
	case 'getProdOPS':
        header('Content-Type: application/json');
        GetProdTableDataOPS();
        break;
	case 'gridupdatewhp':
        header('Content-Type: application/json');
        gridUpdatewhp();
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
	case 'getreportdetails':
        header('Content-Type: application/json');
        GetReportDetails();
        break;
	case 'updateEscalation':
        header('Content-Type: application/json');
        UpdateEscalation();
        break;
	case 'getdayRank':
        header('Content-Type: application/json');
        GetdayRankTableData();
        break;
	case 'getdayData':
        header('Content-Type: application/json');
        GetdayData();
        break;
	case 'savedayData':
        header('Content-Type: application/json');
        SavedayData();
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
	case 'getemaildist':
        header('Content-Type: application/json');
        GetEmailDist();
        break;
	case 'emailChange':
        header('Content-Type: application/json');
        EmailChange();
        break;
	case 'unitcheck':
        header('Content-Type: application/json');
        UnitCheck();
        break;
	case 'addnonUser':
        header('Content-Type: application/json');
        AddNonUser();
        break;
	case 'getcrash':
        header('Content-Type: application/json');
        GetCrashTableData();
        break;
	case 'getMessages':
        header('Content-Type: application/json');
        GetMsgTableData();
        break;
	case 'getqrcodes':
        header('Content-Type: application/json');
        GetqrcodesTable();
        break;
	case 'printqrcodes':
        header('Content-Type: application/json');
        Printqrcodes();
        break;
	case 'getqbl':
        header('Content-Type: application/json');
        GetqblTableData();
        break;
	case 'resetqrcodes':
        header('Content-Type: application/json');
        Resetqrcodes();
        break;
	case 'getqblconfig':
        header('Content-Type: application/json');
        GetQBLConfig();
        break;
	case 'updateqblconfig':
        header('Content-Type: application/json');
        UpdateQBLConfig();
        break;
	case 'uploadqblimage':
        header('Content-Type: application/json');
        UploadQBLImage();
        break;
	case 'sendqblesc':
        header('Content-Type: application/json');
        sendqblAlert();
        break;
	case 'qbltraintest':
        header('Content-Type: application/json');
        echo json_encode(array('success'=>true));
        break;
	case 'gettasks':
        header('Content-Type: application/json');
        GetTasksTableData();
        break;
	case 'getsafety':
        header('Content-Type: application/json');
        GetSafetyTableData();
        break;
	case 'addsafety':
        header('Content-Type: application/json');
        addSafety();
        break;
	case 'updatetaskText':
        header('Content-Type: application/json');
        updateTaskText();
        break;
	case 'CopyTask':
        header('Content-Type: application/json');
        copyTask();
        break;
	case 'closeTask':
        header('Content-Type: application/json');
        CloseTask();
        break;
	case 'updateCrash':
        header('Content-Type: application/json');
        UpdateCrash();
        break;
	case 'updateMsg':
        header('Content-Type: application/json');
        UpdateMsg();
        break;
	case 'newMsg':
        header('Content-Type: application/json');
        NewMsg();
        break;
	case 'clearMsg':
        header('Content-Type: application/json');
        ClearMsg();
        break;
	case 'getqblbyid':
        header('Content-Type: application/json');
        GetQblById();
        break;
	case 'updateqbl':
        header('Content-Type: application/json');
        Updateqbl();
        break;
	case 'sendqblsms':
        header('Content-Type: application/json');
        SendQblSms();
        break;
	case 'senddemoqblsms':
        header('Content-Type: application/json');
        SendDemoQblSms();
        break;
	case 'submitdemolead':
        header('Content-Type: application/json');
        SubmitDemoLead();
        break;
	case 'submitqblcalc':
        header('Content-Type: application/json');
        SubmitQblCalc();
        break;
	case 'updateqblcalc':
        header('Content-Type: application/json');
        UpdateQblCalc();
        break;
	case 'qblhemorrhage':
        header('Content-Type: application/json');
        SendQblHemorrhageAlert();
        break;
	case 'SaveTask':
        header('Content-Type: application/json');
        saveTask();
        break;
	case 'UpdateTask':
        header('Content-Type: application/json');
        updateTask();
        break;
	case 'updatesafety':
        header('Content-Type: application/json');
        updateSafety();
        break;
	case 'closesafety':
        header('Content-Type: application/json');
        closeSafety();
        break;
	case 'closesafetytoken':
        header('Content-Type: application/json');
        closeSafetyToken();
        break;
	case 'closeescalationtoken':
        header('Content-Type: application/json');
        closeEscalationToken();
        break;
	case 'closeqbltoken':
        header('Content-Type: application/json');
        closeQBLToken();
        break;
	case 'clearRecord':
        header('Content-Type: application/json');
        clearRecord();
        break;
	case 'clearEscalation':
        header('Content-Type: application/json');
        clearEscalation();
        break;
	case 'CopyUnit':
        header('Content-Type: application/json');
        copyUnit();
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
	case 'getunscheduledDetails':
        header('Content-Type: application/json');
        GetunscheduledDetails();
        break;
	case 'getProdDetailswhp':
        header('Content-Type: application/json');
        GetProdDetailswhp();
        break;
	case 'getdayRankdetails':
        header('Content-Type: application/json');
        GetDayRank();
        break;
	case 'getCrashDetails':
        header('Content-Type: application/json');
        GetCrashDetails();
        break;
	case 'getmsgDetails':
        header('Content-Type: application/json');
        GetMsgDetails();
        break;
	case 'getqbldetails':
        header('Content-Type: application/json');
        Getqbl();
        break;
	case 'getabldetails':
        header('Content-Type: application/json');
        Getabl();
        break;
	case 'qbldetails':
        header('Content-Type: application/json');
        qblDetails();
        break;
	case 'gettaskDetails':
        header('Content-Type: application/json');
        GettaskDetails();
        break;
	case 'getsafetyDetails':
        header('Content-Type: application/json');
        GetsafetyDetails();
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
	case 'compReports':
		header('Content-Type: application/json');
		CompReports();
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
	case 'getPolicy':
        header('Content-Type: application/json');
        getpolicy();
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
	$sortId = isset($_REQUEST['sortId']) ? $_REQUEST['sortId'] : '';
	$sortId = 0;
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
	$demoMode = Config::get('account')['demoMode'];

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

	if(intval($accountPos)==1 && intval($sort)==0){
	$prodSort = " order by `dept` ASC";
	}else if(intval($accountPos)==1 && intval($sort)==1){
	$prodSort = " order by totalHrs DESC, `dept` ASC";
	//$prodSort = " order by activeCount DESC, totalHrs DESC, `dept` ASC";
	}else if(intval($accountPos)!= 1 && intval($sort)==1){
	//$prodSort = " order by activeCount DESC, totalHrs ASC, `dept` ASC";
	$prodSort = " order by totalHrs ASC, `dept` ASC";
	}else{
	$prodSort = " order by `dept` ASC";
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
	
	if($startDate != $endDate){
	$dateSearch = "AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}'";	
	}else{
	$dateSearch = "AND n.dayDate>=DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
	}
	
	if(intval($role)<=8 && !$idsearch){
		$searchAdd = " where d.active>0 AND d.active<3 AND n.accountId ={$accountId} AND n.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId` ={$userId}) $dateSearch";
	}else if(intval($role)>=9 && intval($role)<=10){
		$searchAdd = " where (d.active=1 OR d.active=2) AND n.accountId ={$accountId} $dateSearch";
	}else if(intval($demoMode)==1 && !$idsearch && (intval($role)==11 || intval($role)==12)){
		$searchAdd = " where n.userId>0 AND (d.active=1 OR d.active=2) AND n.accountId ={$accountId} $dateSearch";
	}else if(intval($demoMode)==0 && !$idsearch && (intval($role)==11 || intval($role)==12)){
		$searchAdd = " where d.active>0 AND n.accountId ={$accountId} $dateSearch";
	}else if(intval($role)==13 && !$idsearch && !$locationId){
		$searchAdd = " where d.active>0 AND d.active<3 AND a.enterpriseId ={$enterpriseId} AND n.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId` ={$userId}) $dateSearch";
	}else if(intval($role)==13 && !$idsearch && $locationId){
		$searchAdd = " where d.active>0 AND d.active<3 AND d.accountId ={$locationId} AND n.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId` ={$userId}) $dateSearch";
	}else if(intval($role)==14 && !$idsearch && !$locationId){
		$searchAdd = " where d.active>0 AND d.active<3 AND a.enterpriseId ={$enterpriseId} $dateSearch";
	}else if(intval($role)==14 && !$idsearch && $locationId){
		$searchAdd = " where d.active>0 AND d.active<3 AND n.accountId ={$locationId} $dateSearch";
	}else{
		$searchAdd = " where d.active>0 AND d.active=1 AND n.accountId ={$accountId} AND n.deptId ={$deptNum} $dateSearch";
		$sortHide = 1;
	}
	
	if($categoryId){
		$searchAdd2 = " AND d.category={$categoryId} ";
	}else{
		$searchAdd2 = '';
	}
	
	if($sortId && intval($sortId)>0){
	$searchAdd3 = " where n.accountId={$accountId} AND n.deptId ={$sortId}";
	$searchAdd4 = " AND n.deptId !={$sortId}";
	$dbDept = Config::get('db')->get_results("
	SELECT n.*, IFNULL(x.unitAssigned,0) as unitAssigned, x.userId as xuser, (SELECT `ahrsVariance` from `productiveNewData` where `userId`>0 and `deptId`=n.deptId and `dayDate`=n.dayDate ORDER BY `shift` ASC LIMIT 0,1) as totalHrs, IFNULL(SUM(n.activeRecord) / SUM(n.activeRecord),0) as activeCount, d.roundrn, d.minRN, d.track1Desc, d.track2Desc, d.track3Desc, d.active, d.showEpic, d.showEpicNurse, d.trackdata, d.trackdc, d.uosDesc, d.avgWage, d.useGrid, d.useavgWage, d.indMeasure, d.thresholdHigh, d.thresholdLow, d.rnThreshold, d.gThreshold, d.hrsThreshold, d.category, d.prodMeasure, d.prodMeasure1, d.unitCategory, d.showProdWHP, d.hppd, d.churn, d.dept, d.rnVariance, d.budgetMeasure, d.uosValue, a.payPeriod, a.prodTerm, a.hrsTerm, a.displayCompliance, a.useSafety, a.productivityPosNeg, a.nickName, a.displayProd, a.enterpriseId, a.prodIndicator, a.name as accountName, a.payPeriodFirst from `productiveNewData` n LEFT JOIN `ProductiveDept` as d on d.id = n.deptId left outer join `productiveDeptXref` as x on x.deptId=n.deptId AND x.userId={$userId} left join `productiveAccount` as a on a.id = d.accountId$searchAdd3
	UNION
	SELECT n.*, IFNULL(x.unitAssigned,0) as unitAssigned, x.userId as xuser, (SELECT `ahrsVariance` from `productiveNewData` where `userId`>0 and `deptId`=n.deptId and `dayDate`=n.dayDate ORDER BY `shift` ASC LIMIT 0,1) as totalHrs, IFNULL(SUM(n.activeRecord) / SUM(n.activeRecord),0) as activeCount, d.roundrn, d.minRN, d.track1Desc, d.track2Desc, d.track3Desc, d.active, d.showEpic, d.showEpicNurse, d.trackdata, d.trackdc, d.uosDesc, d.avgWage, d.useGrid, d.useavgWage, d.indMeasure, d.thresholdHigh, d.thresholdLow, d.rnThreshold, d.gThreshold, d.hrsThreshold, d.category, d.prodMeasure, d.prodMeasure1, d.unitCategory, d.showProdWHP, d.hppd, d.churn, d.dept, d.rnVariance, d.budgetMeasure, d.uosValue, a.payPeriod, a.prodTerm, a.hrsTerm, a.displayCompliance, a.useSafety, a.productivityPosNeg, a.nickName, a.displayProd, a.enterpriseId, a.prodIndicator, a.name as accountName, a.payPeriodFirst from `productiveNewData` n LEFT JOIN `ProductiveDept` as d on d.id = n.deptId left outer join `productiveDeptXref` as x on x.deptId=n.deptId AND x.userId={$userId} left join `productiveAccount` as a on a.id = d.accountId$searchAdd$searchAdd2$searchAdd4 group by n.deptId");
	}else{
	$dbDept = Config::get('db')->get_results("SELECT n.*, IFNULL(x.unitAssigned,0) as unitAssigned, x.userId as xuser, (SELECT `nvariance` from `productiveNewData` where `userId`>0 and `deptId`=n.deptId and `dayDate`=n.dayDate ORDER BY `shift` ASC LIMIT 0,1) as totalHrs, IFNULL(SUM(n.activeRecord) / SUM(n.activeRecord),0) as activeCount, d.roundrn, d.minRN, d.track1Desc, d.track2Desc, d.track3Desc, d.active, d.showEpic, d.showEpicNurse, d.trackdata, d.trackdc, d.uosDesc, d.avgWage, d.useGrid, d.useavgWage, d.indMeasure, d.thresholdHigh, d.thresholdLow, d.rnThreshold, d.gThreshold, d.hrsThreshold, d.category, d.prodMeasure, d.prodMeasure1, d.unitCategory, d.showProdWHP, d.hppd, d.churn, d.dept, d.rnVariance, d.budgetMeasure, d.uosValue, a.payPeriod, a.useSafety, a.prodTerm, a.hrsTerm, a.displayCompliance, a.productivityPosNeg, a.nickName, a.displayProd, a.enterpriseId, a.prodIndicator, a.name as accountName, a.payPeriodFirst from `productiveNewData` n LEFT JOIN `ProductiveDept` as d on d.id = n.deptId left outer join `productiveDeptXref` as x on x.deptId=n.deptId AND x.userId={$userId} left join `productiveAccount` as a on a.id = d.accountId$searchAdd$searchAdd2 group by n.deptId$prodSort");
	}	
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
		
	if(intval($dept['unitAssigned'])==2 && intval($dept['xuser'])==intval($userId)){
	$closeButton = '<button type="button" onclick="tj.hidebox(' . $dept['deptId'] . ');" class="close">x</button>';
	}else{
	$closeButton = '';	
	}
	$i=$i+1;
	$deptId = $dept['deptId'];
	$active = $dept['active'];
	//$shifts = $dept['shiftsperDay'];
	$prodTerm = $dept['prodTerm'];
	$hrsTerm = $dept['hrsTerm'];
	$pMeasure = intval($dept['prodMeasure1']);
	$useSafety = intval($dept['useSafety']);
	$unitCat = intval($dept['unitCategory']);
	$showProd = intval($dept['showProdWHP']);
	$displayProd = intval($dept['rnVariance']);
	$targethppd = $dept['hppd'];
	$churn = $dept['churn'];
	$acctId = $dept['accountId'];
	$budgetMeasure = $dept['budgetMeasure'];
	$useavgWage = intval($dept['useavgWage']);
	$showEpic = intval($dept['showEpic']);
	$showEpicNurse = intval($dept['showEpicNurse']);
	$roundrn = intval($dept['roundrn']);
	$minRN = intval($dept['minRN']);
	$budgetVal = 0;
	$productivity = '';
	$shiftName = '';
	$totalPromoVal = 0;
	$trackDischarges = 0;
	
	$style=0;
	$stylered = '';
	$timeDiff = 0;
	
	$prodCalc = intval($dept['productivityPosNeg']);
	
	$avgWage = floatval($dept['avgWage']);
	
	$budgethrsVar = floatval($dept['totalHrs']);
	$budgetVar = round(($avgWage * $budgethrsVar),0);
	$budgetValcalc = round(floatval($dept['budgetVar']),0);
	$uosValue = $dept['uosValue'];
	$trackdc = $dept['trackdc'];
	$payPeriod = intval($dept['payPeriod']);
	$dashColor = intval($dept['prodIndicator']);
	$useGrid = intval($dept['useGrid']);
	
	$payFirst = $dept['payPeriodFirst'];
	$accountName = $dept['nickName'];
	$displayCompliance = intval($dept['displayCompliance']);
	$indMeasure = intval($dept['indMeasure']);
	$prodLabel = '';
	$note = '';
	//$trackNumber = '';

	
	if($unitCat==2){
	/*	
	if(count($dbDept)>0 && intval($role)<14){
	$deptName = "<a href=\"#supportstaffing?id=".$deptId."\" >" . $dept['dept']."</a>";	
	}else if(count($dbDept)>0 && intval($role)>=14){
	$deptName = "<a href=\"#supportstaffing?id=".$deptId."\" >" . $accountName . " - " . $dept['dept']."</a>";
	}else{
	$deptName = $dept['dept'];	
	}
	*/
	
	if(intval($active)==2){
	$deptName = "<a href=\"#supportstaffing?id=".$deptId."\" >" . $dept['dept']." (Customer Setup)</a>";	
	}else if(intval($active)==3){
	$deptName = "<a href=\"#supportstaffing?id=".$deptId."\" >" . $dept['dept']." (Admin Setup)</a>";	
	}else{
	$deptName = "<a href=\"#supportstaffing?id=".$deptId."\" >" . $dept['dept']."</a>";		
	}
	
	$plannedHrs = $dept['uosDesc'];
	$dischargeLabel = '';
	$plannedVar = '';
	$chart1 = "Hours Variance";
	$chart2 = $dept['hrsTerm'];
	$chart3 = "Total Hours";
	$chart4 = "Total UOS";
	
	if($showProd==2){
	$prodLabel = "$ Variance";
	}else if($showProd==3){
	$prodLabel = $dept['hrsTerm'];
	}else if($showProd==4){
	$prodLabel = "HRS Variance";
	}else if($showProd==5){
	$prodLabel = "Grid RN Variance";
	}else if($showProd==6){
	$prodLabel = "Grid Variance";
	}else if($showProd==7){
	$prodLabel = "RN Count";
	}else if($showProd==8){
	$prodLabel = "Grid Hrs Variance";
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
	$rnThresholdHigh = floatval($dept['rnThreshold']);
	$rnThresholdLow = floatval($dept['rnThreshold']) * -1;
	$gThresholdHigh = floatval($dept['gThreshold']);
	$gThresholdLow = floatval($dept['gThreshold']) * -1;
	$hrsThresholdLow = floatval($dept['hrsThreshold']);
	$hrsThresholdHigh = floatval($dept['hrsThreshold']) * -1;
	//$totalCan = round($dept['hrsVar'],2);
	$totalCan = 0;
	$gvariance = 0;
	$grnvariance = 0;
	$newRNvar = 0;
	
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
		
	
	//if($start==$end){

	$canData = Config::get('db')->get_results("
		
        SELECT 
			n.budgetValue AS laborTotal, a.escType, a.escalationName, IFNULL(u.last_name,'') as last_name, IFNULL(u.first_name,'') as first_name, n.userId, n.submittedby, n.shift, n.entered - CURDATE() as reportHrs, DATE_FORMAT(n.entered, '%H:%i') as submitTime, DATE_FORMAT(CURDATE(), '%m/%d/%y') as todayDate, DATE_FORMAT(n.dayDate, '%m/%d/%y') as dayDate, s.shift as shiftName, n.rnAcuity, n.actualWHP, n.gvariance, n.nproductivity as prodAvg, d.prodMeasure, d.useGrid, d.prodMeasure1, IFNULL(e.escalation,'0') as eType, a.id as escId, a.note as escComment, n.id as dataId, d.hppd, d.avgWage, 
			SUM(n.skill1val+n.skill2val+n.skill3val+n.skill4val+n.skill5val+n.skill6val+n.skill7val+n.skill8val+n.skill9val+n.skill10val+n.addResourceHrs) AS laborHours, n.hrsVariance as hrsVar, 
			(CASE WHEN n.visits>0 THEN n.visits ELSE n.procedureCount END) as procedures, IFNULL(n.note,'0') as prodNote, IFNULL(a.note,'0') as escNote, IFNULL(n.note,'') as note
        FROM
            `productiveNewData` n 
        LEFT JOIN 
			`ProductiveDept` as d on d.id = n.deptId
		LEFT JOIN
			`productiveShifts` as s on s.id=n.shift
		LEFT OUTER JOIN
			`productiveUser` as u on u.id=n.userId
		LEFT OUTER JOIN
            `productiveEscalations` as a on a.deptId=n.deptId AND a.active=1 AND DATE_FORMAT(a.dateSubmitted, '%Y-%m-%d')>='{$start}'
		LEFT OUTER JOIN
			`productiveAcctEscalations` as e on e.id = a.escalation
		WHERE
             n.deptId={$deptId} AND n.type!=2 AND n.userId>0
		AND
			n.dayDate >= '{$startDate}' AND 
            n.dayDate <= '{$endDate}'
		ORDER BY n.dayDate DESC, n.shift ASC
    ");	
		$escType = (isset($canData[0])) ? ($canData[0]['escType']) : 0;
		$escalationName = (isset($canData[0])) ? ($canData[0]['escalationName']) : '';
		
		if(intval($escType)==1){
		$newEscalation = $escalationName;
		}else{
		$newEscalation = (isset($canData[0])) ? ($canData[0]['eType']) : '';
		}
		$shiftName1 = (isset($canData[0]['dayDate'])) ? ($canData[0]['dayDate'] . " @ " . $canData[0]['submitTime']) : '';
		//$newEscalation = (isset($canData[0])) ? ($canData[0]['eType']) : 0;
		$escComment = (isset($canData[0])) ? ($canData[0]['escComment']) : '';
		$escId = (isset($canData[0])) ? ($canData[0]['escId']) : '';
		$prodNote = (isset($canData[0])) ? ($canData[0]['prodNote']) : '';
		$escNote = (isset($canData[0])) ? ($canData[0]['escNote']) : '';
		$submittedby = (isset($canData[0])) ? ($canData[0]['submittedby']) : '';
		
		$prodMeasure = (isset($canData[0])) ? $canData[0]['prodMeasure'] : 0;
		
		$totalMsg1 = (isset($canData[0])) ? $canData[0]['laborHours'] : 0;
		$staffCount = round($totalMsg1,0);
				
		if(strlen($shiftName1 ?? '')>5){
		$shiftName = " (" . $shiftName1 . ")";
		}else{
			$shiftName = '';
		}
		
		
		$totalBlocked = 0;
		$bedsBlocked = 0;
		$dash5 = 0;
		$discharges = 0;
		$totalpercent = (isset($canData[0])) ? round($canData[0]['prodAvg'],1) : 0;
		$note1 = (isset($canData[0])) ? $canData[0]['note'] : '';
		
		$note1 = (isset($canData[0])) ? $canData[0]['note'] : '';
		
		if(strlen($submittedby ?? '')>0){
			$submitName = $submittedby;
		}else{
			$submitName = $canData[0]['first_name'] . " " . $canData[0]['last_name'];
		}
		
		if(strlen($note1 ?? '')>0){
			$note = "Note: " . $note1 . " - " . $submitName;
		}else if(intval($canData[0]['userId'])>0){
			$note = "Submitted by: " . $submitName;
		}else{
				$note='';
		}
		
		
		if($displayProd==2){
		$varianceWHP = "Grid Variance";
		$totalCan = (isset($canData[0])) ? floatval($canData[0]['gvariance']) : 0;
		}else if($displayProd==4){
		$varianceWHP = "Grid Hrs Variance";
		$totalCan = (isset($canData[0])) ? floatval($canData[0]['ghrsVariance']) : 0;
		}else{
		$varianceWHP = "Hrs Variance";
		$totalCan = (isset($canData[0])) ? floatval($canData[0]['hrsVar']) : 0;
		}
	
		
		if($canData[0]['dataId'] && $displayProd!=5){
		$productivity = '<a href="javascript:;" style="color:white" onclick="tj.staffSummary('.$canData[0]['dataId'].','.$canData[0]['prodMeasure']. ')" ><u>Staff Hours</u></a>';
		}else if($canData[0]['dataId'] && $displayProd==5){
		$productivity = '<a href="javascript:;" style="color:white" onclick="tj.staffSummary('.$canData[0]['dataId'].','.$canData[0]['prodMeasure']. ')" ><u>Staff Count</u></a>';
		}else{
		$productivity = "Staff Hours";
		}
		$totalCTR1 = (isset($canData[0])) ? $canData[0]['procedures'] : 0;
		$pCount = round($totalCTR1,0);
		//if(count($canData) > 0 && intval($canData[0]['procedures'])>0){
		//	$totalProd = round(floatval($canData[0]['laborHours']) / floatval($canData[0]['procedures']),3);
		//}else{
		//	$totalProd = 0.0;
		//}
		$totalProd = round(floatval($canData[0]['actualWHP']),2);
		
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
		}else if($showProd==7){
			$totalProdVal = round($canData[0]['rnAcuity'],0);
			$style=1;
			$budgetValcalc=0;
		}else{
			$totalProdVal = $totalCan;
		}
	/*	
	}else{
	$canData = Config::get('db')->get_results("
		
       SELECT 
			SUM(n.budgetValue) AS laborTotal, DATE_FORMAT(n.entered, '%H:%i') as submitTime, AVG(n.actualWHP) as actualWHP, DATE_FORMAT(CURDATE(), '%m/%d/%y') as todayDate, DATE_FORMAT(n.dayDate, '%m/%d/%y') as dayDate, n.rnAcuity, s.shift as shiftName, AVG(n.trackDischarges) as avgDischarges, AVG(n.gvariance) as gvariance, AVG(n.nproductivity) as prodAvg, n.id as dataId, d.hppd, d.prodMeasure1, d.useGrid, d.avgWage, 
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

	$shiftName1 = (isset($canData[0])) ? ($canData[0]['dayDate'] . " @ " . $canData[0]['submitTime']) : '';
	
	$prodMeasure = (isset($canData[0])) ? $canData[0]['prodMeasure'] : 0;
	$totalMsg1 = (isset($canData[0])) ? $canData[0]['laborHours'] : 0;
	$staffCount = round($totalMsg1,0);
	$totalCTR1 = (isset($canData[0])) ? $canData[0]['procedures'] : 0;
	$pCount = round($totalCTR1,0);
	$totalCan = (isset($canData[0])) ? floatval($canData[0]['hrsVar']) : 0;
	$totalpercent = (isset($canData[0])) ? round($canData[0]['prodAvg'],1) : 0;
	$totalBlocked = 0;
	$bedsBlocked = 0;
	
	if(strlen($shiftName1)>5){
		$shiftName = " (" . $shiftName1 . ")";
	}else{
		$shiftName = '';
	}
		
	
	if($useGrid==3){
	$totalProd = '';
	}else{
	$totalProd = round(floatval($canData[0]['actualWHP']),2);
	}	
	//if(count($canData) > 0 && intval($canData[0]['procedures'])>0){
	//		$totalProd = round(floatval($canData[0]['laborHours']) / floatval($canData[0]['procedures']),3);
	//	}else{
	//$totalProd = 0.0;
	//	}
		
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
		}else if($showProd==7){
			$totalProdVal = round($canData[0]['rnAcuity'],0);
			$style=1;
			$budgetValcalc=0;
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
            a.deptId ={$deptId} AND a.active=1
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
	*/

	}else{
		
	if(intval($role)<14){
		$plannedVar1 = '<a href="javascript:;" style="color:white" onclick="blockedBeds('.$accountId.','.$deptId.')"><u> / Blocked</u></a>';	
		}else{
		$plannedVar1 = ' / Blocked';	
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
	}else if($displayProd==4){
	$varianceWHP = "Grid Hrs Variance";
	//$prodLabel2 = "Grid Prod% (est.)";
	}else if($displayProd==5){
	$varianceWHP = "NEDOCS";
	}else{
	$varianceWHP = "Hrs Variance";
	//$prodLabel2 = "Hrs Prod% (est.)";	
	}
	
	
	if($showEpic==1 && $showEpicNurse==0){
	$plannedHrs = "Patients (Epic Score)";
	}else if($showEpic==1 && $showEpicNurse==1){
	$plannedHrs = "Patients (WIT/RN)";
	}else{
	$plannedHrs = "Patients";	
	}	
	
	$dischargeLabel = "Discharges";
	$plannedVar = "Beds" . $plannedVar1;
	
	if($showProd==2){
	$prodLabel = "$ Variance";
	}else if($showProd==3){
	$prodLabel = $dept['hrsTerm'];
	}else if($showProd==4){
	$prodLabel = "Hrs Variance";
	}else if($showProd==5){
	$prodLabel = "Grid RN Variance";
	}else if($showProd==8){
	$prodLabel = "Grid Hrs Variance";
	}else if($showProd==6){
	$prodLabel = "Grid Variance";
	}else if($showProd==7){
	$prodLabel = "RN Count";
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
	$rnThresholdLow = floatval($dept['rnThreshold']) * -1;
	$gThresholdLow = floatval($dept['gThreshold']) * -1;
	$gThresholdHigh = floatval($dept['gThreshold']);
	$thresholdHigh = $dept['thresholdHigh'];
	$rnThresholdHigh = floatval($dept['rnThreshold']);
	$thresholdHighwhp = ($dept['thresholdHigh'] / 100) * $targethppd;
	$thresholdLowwhp = ($dept['thresholdLow'] / 100) * $targethppd;
	$hrsThresholdLow = floatval($dept['rnThreshold']);
	$hrsThresholdHigh = floatval($dept['rnThreshold']) * -1;
	$totalProd = "n/a";
	$hidePlanned = 1;
	//$rnThresholdLow = $rnThresholdHigh * -1;
	
	if(intval($active)==2){
	$deptName = "<a href=\"#staffing?id=".$deptId."\" >" . $dept['dept']." (Customer Setup)</a>";	
	}else if(intval($active)==3){
	$deptName = "<a href=\"#staffing?id=".$deptId."\" >" . $dept['dept']." (Admin Setup)</a>";	
	}else{
	$deptName = "<a href=\"#staffing?id=".$deptId."\" >" . $dept['dept']."</a>";	
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
       
	//if($start==$end){
	$canData = Config::get('db')->get_results("		
        SELECT 
           n.nvariance AS canTotal, n.nedocScore, n.oneto7, d.dash5, a.escType, a.escalationName, n.avariance, IFNULL(u.last_name,'') as last_name, IFNULL(u.first_name,'') as first_name, n.minAdjusted, n.nvarAdjusted, d.prodMeasure1, n.deptId, n.userId, n.submittedby, n.antecount, c.timeZone, TIMESTAMPDIFF(HOUR,n.entered,CURRENT_TIME()) as timeDiff, n.epicScore, n.shift, n.entered - CURDATE() as reportHrs, DATE_FORMAT(n.entered, '%H:%i') as submitTime, DATE_FORMAT(CURDATE(), '%m/%d/%y') as todayDate, n.rnAcuity, DATE_FORMAT(n.dayDate, '%m/%d/%y') as dayDate, s.shift as shiftName, n.gridrnvariance, n.rhrsVariance, n.ghrsVariance, n.budgetVar, n.rbudgetVar, n.gbudgetVar, n.actualWHP, n.gvariance as gVariance, n.customProd, n.lproductivity, IFNULL(n.note,'0') as prodNote, IFNULL(a.note,'0') as escNote, IFNULL(n.note,'') as note, IFNULL(b.blockedBeds,0) as bedsBlocked, a.note as escComment, a.id as escId, (d.totalbeds - ((n.oneto1 * d.add1 * d.bedcount1)+(n.oneto2 * d.add2 * d.bedcount2)+(n.oneto3 * d.add3 * d.bedcount3)+(n.oneto4 * d.add4 * d.bedcount4)+(n.oneto5 * d.add5 * d.bedcount5)+(n.oneto6 * d.add6 * d.bedcount6)) - IFNULL(b.blockedBeds,0)) AS openBeds, n.hrsVariance, n.nproductivity, IFNULL(e.escalation,'0') as eType, n.id as dataId, n.atotal AS patientTotal, ((n.chargecount * d.staff1) + (n.customNurse * d.staff3) + (n.customNurse2 * d.staff4) + (n.techcount * d.staff5) + (n.seccount * d.staff6) + (n.antecount * d.staff2) + (n.sittercount * d.staff7) + (n.otherNurse1 * d.staff8) + (n.otherNurse2 * d.staff9) + (n.otherNurse3 * d.staff10)) as staffcount
        FROM
            `productiveNewData` n
		LEFT JOIN `ProductiveDept` as d on d.id = n.deptId
		LEFT OUTER JOIN `productiveUser` as u on u.id = n.userId
		LEFT JOIN `productiveAccount` as c on c.id = n.accountId		
		LEFT JOIN `productiveblockedBeds` as b on b.deptId=n.deptId 
		LEFT JOIN `productiveShifts` as s on s.id=n.shift
		LEFT OUTER JOIN `productiveEscalations` as a on a.deptId={$deptId} AND a.active=1 AND DATE_FORMAT(a.dateSubmitted, '%Y-%m-%d')='{$start}'
		LEFT JOIN `productiveAcctEscalations` as e on e.id = a.escalation
        WHERE
            n.userId>0 AND n.deptId={$deptId} AND (n.dayDate= '{$startDate}' OR (n.dayDate=DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND n.shift<=14))
		ORDER BY n.dayDate DESC, n.shift ASC, a.id DESC
    ");
	
	//$now = time() + (2 - intval($canData[0]['timeZone'])) * 60 *60;
	$escType = (isset($canData[0])) ? ($canData[0]['escType']) : 0;
	$escalationName = (isset($canData[0])) ? ($canData[0]['escalationName']) : '';
	
	if(intval($escType)==1){
	$newEscalation = $escalationName;
	}else{
    $newEscalation = (isset($canData[0])) ? ($canData[0]['eType']) : '';
	}
	
	$escComment = (isset($canData[0])) ? ($canData[0]['escComment']) : '';
	$escId = (isset($canData[0])) ? ($canData[0]['escId']) : '';
	
	$prodNote = (isset($canData[0])) ? ($canData[0]['prodNote']) : '';
	$escNote = (isset($canData[0])) ? ($canData[0]['escNote']) : '';
	$submittedby = (isset($canData[0])) ? ($canData[0]['submittedby']) : '';
	
	$mina = (isset($canData[0])) ? intval($canData[0]['minAdjusted']) : 0;
	$nvar = (isset($canData[0])) ? floatval($canData[0]['nvarAdjusted']) : 0;
	$cant = (isset($canData[0])) ? floatval($canData[0]['canTotal']) : 0;
	$avar = (isset($canData[0])) ? floatval($canData[0]['avariance']) : 0;
	$ndataId = (isset($canData[0])) ? floatval($canData[0]['dataId']) : '';
	
	//$timeDiff = 2 - intval($canData[0]['timeZone']) + intval($canData[0]['timeDiff']);
	
	//if($timeDiff>6 && strtotime($canData[0]['todayDate'])==strtotime($startDate)){
	//$stylered = "style=\"color:red\"";
	//}else{
	$stylered = '';
	//}
	
	if($canData && $ndataId){
		$productivity = '<a href="javascript:;" style="color:white" onclick="tj.staffSummary('.$canData[0]['dataId'].')" ><u>Staff Summary</u></a>';
		}else{
		$productivity = "Staff Summary";
		}
	

	if($mina==1 && $cant < $avar){
		//$newVar = $cant;
		//$newVarnote = "(*Adjusted for Core Staffing) ";
		$newVarnote = '';
		$newAst = "*";
		$newRNvar = "Actual Acuity Variance: " . round($avar,1) . "";
	}else{
		//$newVar = $cant;
		$newVarnote = '';
		$newAst = '';
		$newRNvar = '';
	}
	
	if($displayProd==0 && $roundrn==1){
    $totalCan = round($cant,0). $newAst;
	//$totalpercent = (isset($canData[0])) ? round($canData[0]['nproductivity'],1) : 0;
	}else if($displayProd==0 && $roundrn==0){
    $totalCan = round($cant,2) . $newAst;
	//$totalpercent = (isset($canData[0])) ? round($canData[0]['nproductivity'],1) : 0;
	}else if($displayProd==0 && $roundrn==2){
    $totalCan = floor($cant) . $newAst;
	//$totalpercent = (isset($canData[0])) ? round($canData[0]['nproductivity'],1) : 0;
	}else if($displayProd==0 && $roundrn==3){
    $totalCan = ceil($cant) . $newAst;
	//$totalpercent = (isset($canData[0])) ? round($canData[0]['nproductivity'],1) : 0;
	}else if($displayProd==2){
	$totalCan = (isset($canData[0])) ? round($canData[0]['gVariance'],1) : 0;
	//$totalpercent = (isset($canData[0])) ? round($canData[0]['customProd'],1) : 0;
	}else if($displayProd==3){
	$totalCan = (isset($canData[0])) ? round($canData[0]['gridrnvariance'],0) : 0;
	//$totalpercent = (isset($canData[0])) ? round($canData[0]['lproductivity'],1) : 0;	
	}else if($displayProd==4){
	$totalCan = (isset($canData[0])) ? round($canData[0]['ghrsVariance'],1) : 0;
	//$totalpercent = (isset($canData[0])) ? round($canData[0]['lproductivity'],1) : 0;	
	}else if($displayProd==5){
	$totalCan = (isset($canData[0])) ? round(intval($canData[0]['nedocScore']),0) : 0;
	//$totalpercent = (isset($canData[0])) ? round($canData[0]['lproductivity'],1) : 0;	
	}else{
	$totalCan = (isset($canData[0])) ? round($canData[0]['hrsVariance'],1) : 0;
	//$totalpercent = (isset($canData[0])) ? round($canData[0]['lproductivity'],1) : 0;	
	}
	$shiftName1 = (isset($canData[0])) ? ($canData[0]['dayDate'] . " @ " . $canData[0]['submitTime']) : '';
	
	if($showProd==3){
	$totalProdVal = (isset($canData[0])) ? round($canData[0]['actualWHP'],1) : 0;	
	}else if($showProd==5){
	$totalProdVal = (isset($canData[0])) ? round($canData[0]['gridrnvariance'],0) : 0;
	$budgetValcalc=0;
	}else if($showProd==6){
	$totalProdVal = (isset($canData[0])) ? round($canData[0]['gVariance'],1) : 0;
	$budgetValcalc=0;	
	}else if($showProd==8){
	$totalProdVal = (isset($canData[0])) ? round($canData[0]['ghrsVariance'],0) : 0;
	$budgetValcalc=0;	
	}else if($showProd==7){
	$totalProdVal = (isset($canData[0])) ? round($canData[0]['rnAcuity'],0) : 0;
	$style=1;
	$budgetValcalc=0;
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
	$staffCount = (isset($canData[0])) ? round($canData[0]['staffcount'],0) : 0;
	$trackDischarges = 0;
	$totalBlocked = (isset($canData[0])) ? round($canData[0]['openBeds'],0) : 0;
	$bedsBlocked = (isset($canData[0])) ? round($canData[0]['bedsBlocked'],0) : 0;
	$dash5 = (isset($canData[0])) ? $canData[0]['dash5'] : 0;
	$discharges = (isset($canData[0])) ? $canData[0]['oneto7'] : 0;
	$prodMeasure = (isset($canData[0])) ? $canData[0]['prodMeasure1'] : 0;
	
	$epicScore = (isset($canData[0])) ? $canData[0]['epicScore'] : 0;
	$gvariance = (isset($canData[0])) ? $canData[0]['gVariance'] : 0;
	$grnvariance = (isset($canData[0])) ? $canData[0]['gridrnvariance'] : 0;
	$antecount = (isset($canData[0])) ? $canData[0]['antecount'] : 0;
	$canUser = (isset($canData[0])) ? $canData[0]['userId'] : 0;
	$note1 = (isset($canData[0])) ? $canData[0]['note'] : '';
	
	if(strlen($submittedby ?? '')>0){
			$submitName = $submittedby;
	}else{
			$submitName = (isset($canData[0]) ? $canData[0]['first_name'] : '') . " " . (isset($canData[0]) ? $canData[0]['last_name'] : '');
	}
		
	if(strlen($note1 ?? '')>0){
			$note = "Note: " . $note1 . " " . $newVarnote . " - " . $submitName;
	}else if(intval($canUser)>0){
			$note = "Submitted by: " . $submitName . " " . $newVarnote;
	}else{
			$note='';
	}
	
	if(strlen($shiftName1 ?? '')>5){
		$shiftName = " (" . $shiftName1 . ")";
	}else{
		$shiftName = '';
	}
	
	
	if(intval($antecount)>0){
		$wit = ROUND(floatval($epicScore) / floatval($antecount),2);
	}else{
		$wit = 0;
	}
	if($showEpic==1 && $showEpicNurse==0){
	$pCount = round($totalMsg1,0) . " (" . round($epicScore,0) . ")";
	}else if($showEpic==1 && $showEpicNurse==1){
	$pCount = round($totalMsg1,0) . " (" . $wit . ")";
	}else{
	$pCount = round($totalMsg1,0);
	}
	
	}
	$dayData = Config::get('db')->get_results("SELECT a.*,  DATE_FORMAT(a.updateTime,'%m/%d %H:%i') as upTime, IFNULL(u.id,'0') as userId, (a.dayCount1 + a.dayCount2 + a.dayCount3 + a.dayCount4) as dayCount, d.dayDatatitle, d.track1Desc, d.track2Desc, d.track3Desc, d.track4Desc 
		FROM `productivedeptDayData` a
		LEFT JOIN `productiveDeptXref` as x on x.deptId=a.deptId and x.primaryUnit=7
		LEFT JOIN `ProductiveDept` as d on d.id = a.deptId
		LEFT OUTER JOIN `productiveUser` as u on u.id = x.userId
		WHERE d.accountId ={$accountId} AND d.unitCategory=4 AND d.active>0 AND LENGTH(d.dayDatatitle)>0 AND a.dayDate >= '{$startDate}' AND a.dayDate <= '{$endDate}'
		GROUP BY a.id
		ORDER BY d.dept ASC
		");
	
	if($dayData){
		$c=1;
	$outData['daytotal'.$c.''] = count($dayData);
	foreach($dayData as $day){
		$daycount = $day['dayCount'];
		
		if($day['updateTime']=='0000-00-00 00:00:00'){
			$outData['updateTime'.$c.''] = '';
		}else{
			$outData['updateTime'.$c.''] = ' (' . $day['upTime'] . ')';
		}
		
		if($role>9 || $day['userId']==$userId){
		$outData['daySave'.$c.''] ='';
		}else{
		$outData['daySave'.$c.''] ='disabled';	
		}
		
		$daycountTitle = '<a href="javascript:;" style="color:white" onclick="tj.dayData('.$day['id'].','.$c.')" ><u>'.$day['dayDatatitle'].'</u></a>';
		$outData['daycount'.$c.''] = $daycount;
		$outData['daycountTitle'.$c.''] = $daycountTitle;
		$c=$c+1;
		}

	}
	
	$safeData = Config::get('db')->get_results("SELECT s.*, CURDATE() AS currentDate, (s.dueDate - CURDATE()) as isDue, IFNULL(b.typeDesc,'') as typeDesc, IFNULL(a.safetyDesc,'') as typea, d.dept FROM `productiveSafety` s 
	LEFT JOIN `ProductiveDept` as d on d.id=s.deptId
	LEFT OUTER JOIN `productivesafetyConfig` as a on a.id=s.safetyConfig
	LEFT OUTER JOIN `productivesafetyType` as b on b.id=a.safetyType 	
	WHERE s.deptId={$deptId} and s.active=1");
	
	if($safeData){
		$c=1;
		foreach($safeData as $safe){
		
		if($safe['roomId1'] !='' && intval($role)>5){
			$outData['safeTitle'.$c.''.$i.''] = "<a href=\"javascript:;\" onclick=\"tj.rptSafety(" . $safe['id'] . ");\" style=\"color:white\"><u>" . $safe['roomId1'] . "</u></a>";
		}else if($safe['roomId1'] =='' && intval($role)>5){
			$outData['safeTitle'.$c.''.$i.''] = "<a href=\"javascript:;\" onclick=\"tj.rptSafety(" . $safe['id'] . ");\" style=\"color:white\"><u>" . $safe['dept'] . "</u></a>";	
		}else if($safe['roomId1'] !=''){
			$outData['safeTitle'.$c.''.$i.''] = $safe['roomId1'];
		}else{
			$outData['safeTitle'.$c.''.$i.''] = $safe['dept'];
		}
		if(intval($safe['isDue'])<=0 && $safe['dueDate'] !='0000-00-00'){
			$outData['dueDate'.$c.''.$i.''] =1;
		}else{
			$outData['dueDate'.$c.''.$i.''] =0;
		}
	
		if(strlen($safe['typea'] ?? '')>0){
		$outData['safeNote'.$c.''.$i.''] = $safe['typea'];
		}else{
		$outData['safeNote'.$c.''.$i.''] = substr($safe['note1'],0,25) . "...";	
		}
		$outData['priority'.$c.''.$i.''] = $safe['priority'];
		
		$outData['safeDate'.$c.''.$i.''] = $safe['currentDate'];
		$outData['safeT'.$c.''.$i.''] =1;
		$outData['safeN'.$c.''.$i.''] =1;
		$c=$c+1;
		}

	}else{
		$outData['safeT1'.$i.''] = '0';
		$outData['safeT2'.$i.''] = '0';
		$outData['safeT3'.$i.''] = '0';
		$outData['safeT4'.$i.''] = '0';
		//$outData['dueDate'.$c.''.$i.''] = '';
		//$outData['safeDate'.$c.''.$i.''] = '';
	}
	
	$outData['timeDiff'.$i.''] = $timeDiff;
	$outData['styleRed'.$i.''] = $stylered;
	$outData['closeButton'.$i.''] = $closeButton;
	$outData['shiftName'.$i.''] = $shiftName;
	$outData['sortHide'] = $sortHide;
	$outData['displayCompliance'] = $displayCompliance;
	$outData['startDiff'] = $startdiff;
	$outData['endDiff'] = $enddiff;
	$outData['useSafety'] = $useSafety;
	$outData['indMeasure'.$i.''] = $indMeasure;
    //$outData['totalPromo'.$i.''] = $totalPromo;
	//$outData['totalPromoVal'.$i.''] = $totalPromoVal;
	$outData['budgetCalc'.$i.''] =$budgetValcalc;
	$outData['hidePlanned'.$i.''] = $hidePlanned;
	$outData['totalProdVal'.$i.''] = $totalProdVal;
	$outData['newRNvar'.$i.''] = $newRNvar;
    
	$outData['accountId'.$i.''] = $accountId;
	$outData['deptId'.$i.''] = $deptId;
	
	
	$outData['newEscalation'.$i.''] = $newEscalation;
	$outData['escComment'.$i.''] = $escComment;
	$outData['escId'.$i.''] = $escId;
	
	$outData['escNote'.$i.''] = $escNote;
	$outData['prodNote'.$i.''] = $prodNote;
	
	$outData['prodTerm'.$i.''] = $prodTerm;
	$outData['hrsTerm'.$i.''] = $hrsTerm;
	
	$outData['dept'.$i.''] = $deptName;
	//$outData['chartLink'.$i.''] = $chartLink;
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
	$outData['totalDischarges'.$i.''] = 0;
	
	
	$outData['blockedbeds'.$i.''] = $plannedVar;
	$outData['totalBlocked'.$i.''] = $totalBlocked;
	$outData['bedsBlocked'.$i.''] = $bedsBlocked;
	$outData['dash5'.$i.''] = $dash5;
	$outData['discharges'.$i.''] = $discharges;
	
		
	//$outData['hppd'.$i.''] = $totalPromo;
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
	$outData['gVariance'] = $gvariance;
	$outData['grnVariance'] = $grnvariance;
	$outData['churn'.$i.''] = $churn;
	//$outData['churnVal'.$i.''] = $churnVal;
	$outData['budgetMeasure'.$i.''] = $budgetMeasure;
	//$outData['PeriodStart'.$i.''] = $currentPeriodStart;
	$outData['deptCount'.$i.''] = $deptCount;
	$outData['compliance'.$i.''] = $compliance;
	$outData['rnThresholdHigh'.$i.''] = $rnThresholdHigh;
	$outData['rnThresholdLow'.$i.''] = $rnThresholdLow;
	$outData['gThresholdHigh'.$i.''] = $gThresholdHigh;
	$outData['gThresholdLow'.$i.''] = $gThresholdLow;
	$outData['dashColor'.$i.''] = $dashColor;
	$outData['showcompliance'.$i.''] = $displayProd;
	$outData['showProd'.$i.''] = $showProd;
	$outData['style'.$i.''] = $style;
	$outData['prodCalc'.$i.''] = $prodCalc;
	//$outData['trackdata'.$i.''] = $trackdata;
	$outData['trackdc'.$i.''] = $trackdc;
	$outData['note'.$i.''] = $note;
	
	
		if($i==1){	
		$outData['hidden11']=1;
		$outData['hidden12']=1;
		//$outData['canGraphData'] = array('labels'=>$newCandidateGraphLabels,'data'=>$newCandidateGraphData);
	//	$outData['smsGraphData'] = array('labels'=>$smsSentGraphLabels,'data'=>$smsSentGraphData);
		//$outData['laborGraphData'] = array('labels'=>$laborGraphLabels,'data'=>$laborGraphData);
		//$outData['postpartumGraphData'] = array('labels'=>$postpartumGraphLabels,'data'=>$postpartumGraphData);
		}else{
		$outData['hidden11']=0;
		$outData['hidden12']=0;
		//$outData['canGraphData'] = array('labels'=>$newCandidateGraphLabels,'data'=>$newCandidateGraphData);
		//$outData['smsGraphData'] = array('labels'=>$smsSentGraphLabels,'data'=>$smsSentGraphData);
		//$outData['laborGraphData'] = array('labels'=>$laborGraphLabels,'data'=>$laborGraphData);
		//$outData['postpartumGraphData'] = array('labels'=>$postpartumGraphLabels,'data'=>$postpartumGraphData);
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


function GetPoliciesTableData() {
	$idsearch = isset($_REQUEST['id']) && ($_REQUEST['id'] != 0) ? $_REQUEST['id'] : '';
    $startDate = isset($_REQUEST['start']) ? $_REQUEST['start'] : date('yyyy-mm-dd');
    $endDate = isset($_REQUEST['end']) ? $_REQUEST['end'] : date('yyyy-mm-dd');
    $userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
	//$deptId = Config::get('account')['deptid'];
	$role = Config::get('account')['role'];
		
    $dbData = Config::get('db')->get_results("SELECT p.*, DATE_FORMAT(p.created,'%m/%d/%Y') as createdDate FROM `policies` p order by p.status desc, p.title asc");
      
    if($dbData){
		$outJobs = array();
        $jobArray = array();
		foreach($dbData as $ante){
			
		if(intval($ante['status'])==1){	
			$checked = '';
		}else{
			$checked = " checked";
		}
			
			
			if (!empty($ante['file_path'])) {
			$doc = '<a href="' . htmlspecialchars($ante['file_path']) . '" target="_blank">' . htmlspecialchars($ante['title']) . '</a>';
		} else {
			$doc = '<a href="javascript:;" onclick="tj.showPolicy('.$ante['id'].')" >' . $ante['title'] . '</a>';
		}
			$created = $ante['createdDate'];
			$title = $ante['title'];
			
			$check1 = "<input type=\"checkbox\" id=\"closePolicy" . $ante['id'] . "\" name=\"closePolicy" . $ante['id'] . "\"" . $checked . " onchange=\"tj.closePolicy(" . $ante['id'] . ");\" ></input>";;
			
			
			$jobArray[] = array(
				'title' => $title,
				'created' => $created,
				'doc' => $doc,
				'check1' => $check1
            );
			}
			
			//$outJobs[] = $ante;
         	
		$outData['data'] = $jobArray;
		echo json_encode($outData);
		//echo json_encode(array('success' => true,$outData));
		}else {
        $outData = array();
		$jobArray[] = array(
				'title' => '',
				'created' => '',
				'doc' => '',
				'check1' => ''
            );
        $outData['data'] = $jobArray;
		 echo json_encode($outData);
       // echo json_encode($outData,JSON_NUMERIC_CHECK);
		}
}

function UploadPolicy() {
	$title = isset($_POST['title']) ? trim($_POST['title']) : '';
	if (empty($title)) { echo json_encode(['success'=>false,'error'=>'Title required']); return; }
	if (!isset($_FILES['policyFile']) || $_FILES['policyFile']['error'] !== UPLOAD_ERR_OK) {
		echo json_encode(['success'=>false,'error'=>'File upload failed']); return;
	}

	$allowed = ['pdf','doc','docx','txt','xls','xlsx','ppt','pptx'];
	$allowedMimes = ['application/pdf','application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document','text/plain','application/vnd.ms-excel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.ms-powerpoint','application/vnd.openxmlformats-officedocument.presentationml.presentation'];
	$ext  = strtolower(pathinfo($_FILES['policyFile']['name'], PATHINFO_EXTENSION));
	$mime = mime_content_type($_FILES['policyFile']['tmp_name']);
	if (!in_array($ext, $allowed) || !in_array($mime, $allowedMimes)) {
		echo json_encode(['success'=>false,'error'=>'File type not allowed']); return;
	}

	$uploadDir = __DIR__ . '/../uploads/policies/';
	if (!is_dir($uploadDir)) { mkdir($uploadDir, 0755, true); }

	$safeName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($_FILES['policyFile']['name']));
	$dest = $uploadDir . $safeName;
	if (!move_uploaded_file($_FILES['policyFile']['tmp_name'], $dest)) {
		echo json_encode(['success'=>false,'error'=>'Could not save file']); return;
	}

	$filePath = 'uploads/policies/' . $safeName;
	$titleEsc = Config::get('db')->filter($title);
	$filePathEsc = Config::get('db')->filter($filePath);
	Config::get('db')->query("INSERT INTO `policies` (title, file_path, status, created) VALUES ('{$titleEsc}', '{$filePathEsc}', 1, NOW())");

	echo json_encode(['success'=>true]);
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
	$demoMode = Config::get('account')['demoMode'];
	$enterpriseId = Config::get('account')['enterpriseId'];
	$label = Config::get('account')['label'];
	$outData = array();
	
	$ciphering = "AES-128-CTR";
	$options = 0;
	$encryption_iv = ENCRYPT_IV;
	$encryption_key = ENCRYPT_KEY;
	
	if(intval($role)<9){
		$searchAdd = "WHERE u.accountId={$accountId} AND u.active>0 AND u.last_name !='' and u.role <={$role} and x.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId} and `primaryUnit`>0 and `primaryUnit`<8)";
	}else if(intval($role)==10 || (intval($role)==11 && intval($demoMode)==1) || (intval($role)==12 && intval($demoMode)==1)){
		$searchAdd = "WHERE (u.accountId={$accountId} AND u.active>0 AND u.role<=10) OR u.id={$userId} ";
	}else if(intval($role)==9){
		$searchAdd = "WHERE u.accountId={$accountId} AND u.active>0 AND (u.role=9 OR u.id={$userId}) ";
	}else if((intval($role)==11 && intval($demoMode)==0) || (intval($role)==12 && intval($demoMode)==0)){
		$searchAdd = "WHERE u.accountId={$accountId} AND u.active>0 ";
	}else if(intval($role)==13){
		$searchAdd = "WHERE u.enterpriseId={$enterpriseId} AND u.last_name !='' and x.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId})";
	}else if(intval($role)>=14){
		$searchAdd = "WHERE u.enterpriseId={$enterpriseId} AND u.last_name !='' ";
	}else{
		$searchAdd = '';
	}
    /*
	$query = "
		SELECT u.*, a.name as accountName, IFNULL(x.deptId,0) as depNum, IFNULL((SELECT count(`userId`) from `productiveLogin` where `userId`=u.id AND `lastlogin`>=CURDATE()),0) as userLogins, x.textAlerts, x.userId, count(x.deptId) as deptCount, d.dept as depName, r.role as userRole FROM `productiveUser` u
        LEFT OUTER JOIN `productiveDeptXref` as x on x.userId=u.id
        LEFT JOIN `productiveUserRoles` as r on r.id=u.role
        LEFT JOIN `ProductiveDept` as d on d.id=x.deptId
		LEFT JOIN `productiveAccount` as a on a.id=u.accountId
        $searchAdd
        GROUP BY u.id ORDER BY u.practiceId,u.last_name,u.first_name ASC
		";
	*/
	$query = "
		SELECT u.*, a.name as accountName, IFNULL(x.deptId,0) as depNum, x.textAlerts, x.userId, count(x.deptId) as deptCount, d.dept as depName, r.role as userRole, IFNULL(u.coverUserId,0) as coverUserId, IFNULL(cu.first_name_E,'') as cover_first_E, IFNULL(cu.last_name_E,'') as cover_last_E FROM `productiveUser` u
        LEFT OUTER JOIN `productiveDeptXref` as x on x.userId=u.id
        LEFT JOIN `productiveUserRoles` as r on r.id=u.role
        LEFT JOIN `ProductiveDept` as d on d.id=x.deptId
		LEFT JOIN `productiveAccount` as a on a.id=u.accountId
		LEFT OUTER JOIN `productiveUser` as cu on cu.id = u.coverUserId
        $searchAdd
        GROUP BY u.id ORDER BY u.practiceId,u.last_name,u.first_name ASC
		";

    $dbData = Config::get('db')->get_results($query);
    //$query = "SELECT FOUND_ROWS() AS found_rows;";
    //$countData = Config::get('db')->get_results($query);
    //$total = $countData[0]['found_rows'];
    $jobArray = array();
    $link = 0;

    //$brandcandidates = Config::get('db')->get_results("SELECT c.*, x.keyword as keyword FROM `candidate` as c LEFT JOIN candidateXref as x on x.candidateId = c.id WHERE x.brandId={$brand} and c.active=1 and (x.promo=1 or x.promo=2)");

    if($dbData){
	foreach ($dbData as $job) {
       
		$roleName = $job['userRole'];
		$dpCount = intval($job['deptCount']);
		$loggedIn = intval($job['id']);
		$text = intval($job['txtactive']);
		$textPaused = intval($job['txtPause']);
		$practiceId = intval($job['practiceId']);
		
		$firstName = openssl_decrypt($job['first_name_E'], $ciphering, $encryption_key, $options, $encryption_iv);
		$lastName = openssl_decrypt($job['last_name_E'], $ciphering, $encryption_key, $options, $encryption_iv);
		$email = openssl_decrypt($job['email2_E'], $ciphering, $encryption_key, $options, $encryption_iv);
		
		//$email = $job['email2'];
		$amp   = '@';
		$pos = strpos($email, $amp);
		
		if($practiceId==1){
			$name = $lastName . ", " . $firstName . " (Shared ID)";
		}else{
			$name = $lastName . ", " . $firstName;
		}
		
		if(intval($role)==11 || intval($role)==12){
		$disable = '';
		}else{
		$disable = "disabled";	
		}
		
		if(intval($job['unitReports'])==1 || intval($job['complianceReports'])==1){
			$reports = 1;
			$checked = " checked";
		}else{
			$reports = 0;
			$checked = '';
		}
		
		/*
		if(intval($job['role'])>=3 && $pos && $job['last_name']!='' && intval($job['active'])>0){
				$check1 = "<label><input type=\"checkbox\" id=\"checkComp" . $job['id'] . "\" name=\"checkComp" . $job['id'] . "\"" . $checked . " onchange=\"tj.compReports(" . $job['id'] . ");\"" . $disable . "></input>  Hospital Detail Report</label>";
		}else if(intval($job['role'])<6 && intval($job['role'])>4 && $pos && $job['last_name']!='' && intval($job['active'])>0){
				$check1 = '';
				//$check1 = "<label><input type=\"checkbox\" id=\"checkUnit" . $job['id'] . "\" name=\"checkUnit" . $job['id'] . "\"" . $checked . " onchange=\"tj.unitReports(" . $job['id'] . ");\"" . $disable . "></input> Unit Detail Report</label>";
			}else{
				$check1 = '';
		}
		*/
		
		/*
		
			$textInactive = '<a href="javascript:;" onclick="tj.activate('.$job['id'].',0)">Inactive';
			$textActive = '<a href="javascript:;" onclick="tj.activate('.$job['id'].',0)">Active';
			$textPaused = '<a href="javascript:;" onclick="tj.activate('.$job['id'].',0)">Paused';
	
		
		if(intval($job['practiceId'])==1){
			$textsub = '';
		}else if(intval($job['txtPause'])==1 && intval($job['txt'])==1 && intval($job['txtactive'])==1 && intval($job['practiceId'])==0  && intval($job['mobile'])!=''){
			$textsub = $textPaused;
		}else if(intval($job['txtPause'])==0 && intval($job['txt'])==1 && intval($job['txtactive'])==1 && intval($job['practiceId'])==0  && intval($job['mobile'])!=''){
			$textsub = $textActive;
		}else{
			$textsub = $textInactive;
		}
		*/
		
		
        //if(intval($role) > intval($job['role']) || intval($userId)==intval($job['id'])){
		if(intval($role) >10 || (intval($userId)==intval($job['id']) && intval($job['role'])>5)){
		$nameLink = '<a href="javascript:;" onclick="tj.activate('.$job['id'].',1)">'. $name .'</a>';
		}else{
		$nameLink = $name;	
		}
		
		if(intval($job['role'])>=8){
			$depName = "All";
		}else if($dpCount>1 && intval($job['role'])<9){
			$depName = "Multiple";
		}else{
			$depName = $job['depName'];
		}
		
		//if(intval($role) > intval($job['role']) || intval($userId)==intval($job['id'])){
		if(intval($role) >10 || (intval($userId)==intval($job['id']) && intval($job['role'])>5)){
		$unitLink = '<a href="javascript:;" onclick="tj.editUnits('.$job['id'].' ,0)">' . $depName . '</a>';
		}else{
		$unitLink = $depName;
		}
		
		//if(intval($job['userLogins'])>99){
		//	$userLogins = '99+';
		//}else{
		//	$userLogins = $job['userLogins'];
		//}
			
		
		if($job['lastlogin']=="0000-00-00 00:00:00"){
		$login = '';
		}else if(intval($role)==11){
		//$login = $job['lastlogin'] . ' (' . $userLogins . ')';
		$login = $job['lastlogin'];
		}else{
		$login = $job['lastlogin'];	
		}
		

		if (intval($job['id']) == intval($userId)) {
			if(intval($job['coverUserId']) > 0 && $job['cover_first_E']) {
				$coverFirst = openssl_decrypt($job['cover_first_E'], $ciphering, $encryption_key, $options, $encryption_iv);
				$coverLast = openssl_decrypt($job['cover_last_E'], $ciphering, $encryption_key, $options, $encryption_iv);
				$cover = '<a href="javascript:;" class="user-coverage-link" data-userid="' . $job['id'] . '">' . $coverLast . ', ' . $coverFirst . '</a> <a href="javascript:;" class="user-coverage-delete text-danger" data-userid="' . $job['id'] . '" title="Remove coverage">&times;</a>';
			} else {
				$cover = '<a href="javascript:;" class="user-coverage-link" data-userid="' . $job['id'] . '">Set</a>';
			}
		} else {
			if(intval($job['coverUserId']) > 0 && $job['cover_first_E']) {
				$coverFirst = openssl_decrypt($job['cover_first_E'], $ciphering, $encryption_key, $options, $encryption_iv);
				$coverLast = openssl_decrypt($job['cover_last_E'], $ciphering, $encryption_key, $options, $encryption_iv);
				$cover = $coverLast . ', ' . $coverFirst;
			} else {
				$cover = '';
			}
		}
		        $jobArray[] = array(
            'Name'=>$nameLink,
			'accountName'=>$job['accountName'],
			'Unit'=>$unitLink,
			'Role'=>$roleName,
			'Login'=>$login,
			'Coverage'=>$cover,
			'Shared'=>$practiceId
        );
    }
    
        $outData['data'] = $jobArray;
        echo json_encode($outData,JSON_NUMERIC_CHECK);
	}else{
		$jobArray[] = array(
            'Name'=>'',
			'accountName'=>'',
			'Unit'=>'',
			'Role'=>'',
			'Login'=>'',
			'Shared'=>''
        );
		$outData['data'] = $jobArray;
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
        $dbLocation = Config::get('db')->get_results("select u.*, x.textAlerts as Alerts, x.emailAlerts, x.userId, x.deptId, d.dept as depName, r.id as roleId, r.role as roleName from `productiveUser` u LEFT JOIN `productiveDeptXref` as x on x.userId = u.id LEFT JOIN `ProductiveDept` as d on d.id = x.deptId LEFT OUTER JOIN `productiveUserRoles` as r on r.id=u.role where u.id={$userId}");
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

function GetReportDetails() {
	$deptId = isset($_REQUEST['deptId']) ? $_REQUEST['deptId'] : 0;
	$accountId = Config::get('account')['accountId'];
	
    if(intval($deptId)>2) {
        $dbLocation = Config::get('db')->get_results("select d.*, d.dept as deptName, a.emailTime from `ProductiveDept` d LEFT JOIN `productiveAccount` as a on a.id = d.accountId where d.id={$deptId}");
        $dbData = $dbLocation[0];
    }else if(intval($deptId)==1){
		$dbLocation = Config::get('db')->get_results("select a.*, 'Combined' as deptName from `productiveAccount` a where a.id={$accountId}");
        $dbData = $dbLocation[0];
    }else{
		$dbLocation = Config::get('db')->get_results("select a.*, a.name as deptName from `productiveAccount` a where a.id={$accountId}");
        $dbData = $dbLocation[0];
    }
	
	if($dbData){
		echo json_encode(array('success' => true,'data'=>$dbData));
	}else{
		echo json_encode(array('success'=> true,'message'=>'Data not found','depName'=>'','reportId'=>'0'));
	}
	return true;
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
    $deptId = (isset($_REQUEST['deptId'])) ? $_REQUEST['deptId'] : 1;
	$userId = (isset($_REQUEST['userId'])) ? $_REQUEST['userId'] : '';
	//$grantText = (isset($_REQUEST['grantText'])) ? $_REQUEST['grantText'] : 0;
	$role = (isset($_REQUEST['role'])) ? $_REQUEST['role'] : 0;
	$accountId = Config::get('account')['accountId'];
	
	if(intval($role)==6 || intval($role)==7){
		$delete = array(
					'deptId' => $deptId,
					'primaryUnit' => $role,
					'unitAssigned' =>0
					);
		Config::get('db')->delete('productiveDeptXref',$delete);
	}
	
	$dbText = Config::get('db')->get_results("select * from `productiveDeptXref` where `deptId`={$deptId} and `userId`={$userId}");
			
	if($dbText) {
		$xId = $dbText[0]['id'];
       
		$data = array('primaryUnit' => $role,
					'unitAssigned' =>0
					);
		$where = array('id' =>$xId);
        
		Config::get('db')->update('productiveDeptXref',$data,$where);
    }else if(intval($role)>8 && intval($role)<13){
		$dbUnits = Config::get('db')->get_results("select * from `ProductiveDept` where `active`=1 AND `accountId`={$accountId} AND `id` NOT IN (SELECT `deptId` FROM `productiveDeptXref` where `userId`={$userId})");
		foreach($dbUnits as $units){
		$data = array(
			'userId' => $userId,
			'primaryUnit' =>$role,
			'deptId' => $units['id']
        );
		Config::get('db')->insert('productiveDeptXref',$data);	
		}
	}else if(intval($deptId)>1){
		$data = array(
			'userId' => $userId,
			'primaryUnit' =>$role,
			'deptId' => $deptId
        );
		Config::get('db')->insert('productiveDeptXref',$data);	
	}else{
		//do nothing;
	}
		echo json_encode(array('success'=>true,'message'=>'Unit Added Successfully'));
        return true;	
    
}


/////////////////////////////////////
// GET DETAILS FOR SINGLE USER
function GetUserDetailsActivate() {
	$role = Config::get('account')['role'];
	$loggedIn = Config::get('account')['id'];
    $userId = (isset($_REQUEST['userId'])) ? $_REQUEST['userId'] : '';
	//echo "store:".$storeId;
    if ($userId) {
        $dbLocation = Config::get('db')->get_results("select u.*, COUNT(x.id) as xcount, SUM(x.emailAlerts) as xemail from `productiveUser` u LEFT OUTER JOIN `productiveDeptXref` as x on x.userId=u.id LEFT OUTER JOIN `ProductiveDept` as d on d.id=x.deptId where u.id={$userId} GROUP BY u.id");
        $dbData = $dbLocation[0];
        echo json_encode(array('success' => true,'data'=>$dbData,'role'=>$role,'loggedIn'=>$loggedIn));
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
	$showArchive = (isset($_REQUEST['showArchive'])) ? $_REQUEST['showArchive'] : 0;

	
    if (intval($deptId) > 0) {
       $data = array(
            'dept' => $deptName,
			'unitId' => $unitId,
            'totalbeds' => $totalbeds,
			'target' => $target,
			'prodMeasure' => $prodMeasure,
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
			'desc6' => $desc6,
			'showArchive' => $showArchive
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
	
	$usernameSafe = Config::get('db')->filter($username);
	$dbUser= Config::get('db')->get_results("select * FROM `productiveUser` where `userName`='{$usernameSafe}'");
	
	if(!$dbUser){	
		
	$pw = "productive2019";
	$pword = password_hash($pw, PASSWORD_BCRYPT);
			$data = array(
				'first_name'=>$first,
                'last_name'=>$last,
				'accountId'=>$accountId,
				'email'=>$username,
				'email2'=>$username,
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
	$role = (isset($_REQUEST['role'])) ? $_REQUEST['role'] : 0;
	$deptId = (isset($_REQUEST['unit'])) ? $_REQUEST['unit'] : '';
	
	$ciphering = "AES-128-CTR";
	$options = 0;
	$encryption_iv = ENCRYPT_IV;
	$encryption_key = ENCRYPT_KEY;
	
	$email = strtolower($newemail);
	$accountId = Config::get('account')['accountId'];
	$dbUser = '';
	//$txt = $vtext + $atext;
	$userRole = Config::get('account')['role'];
	
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$goodEmail=false;
		$practiceId=1;
	}else{
		$goodEmail=true;
		$practiceId = 0;
	}
	/*
	if(intval($role)==3){
		$provider = 1;
	}else{
		$provider = (isset($_REQUEST['provider'])) ? $_REQUEST['provider'] : 0;
	}
	*/
	
	if(intval($role)==9){
		$alertTimes = 3;
	}else{
		$alertTimes = 0;
	}
	
	if(intval($role)==6 && intval($deptId)>0){
		$emailAlerts = 1;
		$emailMissed = 0;
		$newDelete = array(
			'deptId'=>$deptId,
			'primaryUnit'=>$role,
			'unitAssigned'=>0
			);
		$managerDelete = Config::get('db')->delete('productiveDeptXref',$newDelete);
	}else if(intval($role)==7 && intval($deptId)>0){
		$emailAlerts = 0;
		$emailMissed = 0;
		$dirDelete = array(
			'deptId'=>$deptId,
			'primaryUnit'=>$role,
			'unitAssigned'=>0
			);
		$directorDelete = Config::get('db')->delete('productiveDeptXref',$dirDelete);
	}else{
		$emailAlerts = 0;
		$emailMissed = 0;
	}
	
	$dbUser= Config::get('db')->get_results("select * from `productiveUser` where `email`='{$email}' OR `userName`='{$email}'");
	if($dbUser){
		$exists = array(
			'exist'=>true
			);
	echo json_encode(array('success'=>false,'data'=>$exists));
    return true;
	}
	
		
	$dbNew= Config::get('db')->get_results("select a.*, IFNULL(u.id,0) as userId, IFNULL(u.active,0) as userActive, IFNULL(x.deptId,0) as deptId, l.defaultpw, l.tip1_filename, l.id as labelId, l.labelName, l.noreply, l.userLogin, l.videoLink, l.login, IFNULL(d.dept,'') AS dept, IFNULL(u.first_name,'') as first_name, IFNULL(u.last_name,'') as last_name from `productiveAccount` a LEFT JOIN `productiveLabel` as l on l.id = a.label LEFT OUTER JOIN `productiveUser` as u on u.accountId = a.id and u.email='{$email}' LEFT OUTER JOIN `productiveDeptXref` as x on x.userId=u.id LEFT OUTER JOIN `ProductiveDept` as d on d.id = x.deptId WHERE a.id={$accountId}");
	//$dbNew= Config::get('db')->get_results("select u.*, IFNULL(x.deptId,0) as deptId, l.defaultpw, l.tip1_filename, l.id as labelId, l.labelName, l.noreply, l.userLogin, l.videoLink, l.login FROM `productiveUser` u LEFT OUTER JOIN `productiveDeptXref` as x on x.userId=u.id LEFT JOIN `productiveAccount` as a on a.id=u.accountId LEFT OUTER JOIN `productiveLabel` as l on l.id=a.label WHERE u.accountId={$accountId} and (u.email='{$email}' OR u.email2='{$email}')");
	
	$userId = intval($dbNew[0]['userId']);
	$userActive = intval($dbNew[0]['userActive']);
	$deptName = $dbNew[0]['dept'];
	$noreply = $dbNew[0]['noreply'];
	$xdeptId = intval($dbNew[0]['deptId']);
	$labelName = $dbNew[0]['labelName'];
	$labelId = intval($dbNew[0]['labelId']);
	$login = $dbNew[0]['userLogin'];
	$enterpriseId = $dbNew[0]['enterpriseId'];
	$video = $dbNew[0]['videoLink'];
	$defaultpw = $dbNew[0]['defaultpw'];
	$file1 = $dbNew[0]['tip1_filename'];
	
	if(intval($userId)<1000){
	$p0 = '00';
	}else if(intval($userId)<10000){
	$p0 = '0';
	}else{
	$p0 = '';	
	}
	if(intval($accountId)<100){
	$a0 = '0';
	}else{
	$a0 = '';	
	}
	
	$p1 = rand(1000,9999);
	$p2 = rand(1000,9999);
	$p3 = rand(1,9);
	
	if(intval($role)==3){
	$logix2 = $p3 . $p1 . $p0 . $userId . $p2 . $a0 . $accountId . $p3;
	}else{
	$logix2 = 0;	
	}
	
	if($userId>0 && $userActive>0){
		$exists = array(
			'exist'=>true,
			'email'=>stripslashes($email),
			'dept'=>$deptName,
			'user'=>$userId,
			'goodEmail'=>$goodEmail
			);
			echo json_encode(array('success'=>true,'data'=>$exists));
            return true;
		}else if($userId>0 && $userActive=0){
			$dataUpdate = array(
				'first_name'=>$first,
                'last_name'=>$last,
				'accountId'=>$accountId,
				'email'=>$email,
				'userName'=>$email,
				'email2'=>$email,
				'first_name_E'=> openssl_encrypt($first, $ciphering, $encryption_key, $options, $encryption_iv),
                'last_name_E'=> openssl_encrypt($last, $ciphering, $encryption_key, $options, $encryption_iv),
				'email_E'=> openssl_encrypt($email, $ciphering, $encryption_key, $options, $encryption_iv),
				'userName_E'=> openssl_encrypt($email, $ciphering, $encryption_key, $options, $encryption_iv),
				'email2_E'=> openssl_encrypt($email, $ciphering, $encryption_key, $options, $encryption_iv),
				'role'=>$role,
                'active'=>1,
				'provider'=>$provider,
				'practiceId'=>$practiceId,
				'alertTimes'=>$alertTimes,
				'goodEmail'=>$goodEmail
            );
			$whereUpdate = array(
				'id'=>$userId
			);
			
            $updateUser = Config::get('db')->update('productiveUser',$dataUpdate,$whereUpdate);
						
			$dataDelete = array(
				'userId'=>$userId
			);
			$newUserDelete = Config::get('db')->delete('productiveDeptXref',$dataDelete);
			
			if(intval($deptId)>0 && intval($role)<8){
			$dataUpdate = array(
				'primaryUnit'=>$role,
				'userId'=>$userId,
				'deptId'=>$deptId
			);
			$newUserUpdate = Config::get('db')->insert('productiveDeptXref',$dataUpdate);
			}else if(intval($role)>=8){
			$dbDept = Config::get('db')->get_results("select * from `ProductiveDept` where `accountId`={$accountId} AND `active`>0");
				foreach($dbDept as $dbDeptId){
				$data = array(
					'primaryUnit'=>$role,
					'userId'=>$userId,
					'deptId'=>$dbDeptId['id']
				);
				$newUser = Config::get('db')->insert('productiveDeptXref',$data);
				}
			}else{
			//do nothing;	
			}
			$exists = array(
			'exist'=>false,
			'email'=>stripslashes($email),
			'dept'=>$deptName,
			'user'=>$userId,
			'goodEmail'=>$goodEmail
			);
			
			echo json_encode(array('success'=>true,'data'=>$exists));
            return true;
			
			}else{
				
			//if(strlen($defaultpw ?? '')>3){
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				//$pw= $defaultpw;
				$pw = $email;
			}else{
				$pw = generateRandomString();
			}
			$pword = password_hash($pw, PASSWORD_BCRYPT);

				if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$tmpw='';
				}else{
					$tmpw=$pw;
				}
		
            $data = array(
				'first_name'=>$first,
                'last_name'=>$last,
				'accountId'=>$accountId,
				'email'=>$email,
				'email2'=>$email,
				'userName'=>$email,
				'first_name_E'=> openssl_encrypt($first, $ciphering, $encryption_key, $options, $encryption_iv),
                'last_name_E'=> openssl_encrypt($last, $ciphering, $encryption_key, $options, $encryption_iv),
				'email_E'=> openssl_encrypt($email, $ciphering, $encryption_key, $options, $encryption_iv),
				'userName_E'=> openssl_encrypt($email, $ciphering, $encryption_key, $options, $encryption_iv),
				'email2_E'=> openssl_encrypt($email, $ciphering, $encryption_key, $options, $encryption_iv),
				'pwd'=>$pword,
				'temp'=>$pword,
				'tmpw'=>$tmpw,
				'role'=>$role,
                'active'=>1,
				'provider'=>$provider,
				'alertTimes'=>$alertTimes,
				'practiceId'=>$practiceId,
				'logix2'=>$logix2
            );
			
            $newLoc = Config::get('db')->insert('productiveUser',$data);
			
			$dbNew = Config::get('db')->get_results("select * from `productiveUser` where email='{$email}'");
			$newId = $dbNew[0]['id'];
			$newaccountId = $dbNew[0]['accountId'];
			
			if(intval($newId)<1000){
			$p0 = '00';
			}else if(intval($newId)<10000){
			$p0 = '0';
			}else{
			$p0 = '';	
			}
			if(intval($accountId)<100){
			$a0 = '0';
			}else{
			$a0 = '';	
			}
			$p1 = rand(1000,9999);
			$p2 = rand(1000,9999);
			$p3 = rand(1,9);
			$logix2 = $p3 . $p1 . $p0 . $newId . $p2 . $a0 . $newaccountId . $p3;
			//$logix2 = $p1 . $p0 . $userId . $p2 . $a0 . $accountId . $p3;
			
			$logixUpdate = array(
				'logix2'=>$logix2,
				'logp3'=>$p1
            );
			$whereLogix = array(
				'id'=>$newId
			);			
            $updateUser = Config::get('db')->update('productiveUser',$logixUpdate,$whereLogix);
			
			if(intval($deptId)>0 && intval($role)<8){
			$data = array(
				'primaryUnit'=>$role,
				'userId'=>$newId,
				'deptId'=>$deptId
			);
			$newUser = Config::get('db')->insert('productiveDeptXref',$data);
			}else{
			$dbDept = Config::get('db')->get_results("select * from `ProductiveDept` where `accountId`={$accountId} AND `active`>0");
			foreach($dbDept as $dbDeptId){
			$data = array(
				'primaryUnit'=>$role,
				'userId'=>$newId,
				'deptId'=>$dbDeptId['id']
			);
			$newUser = Config::get('db')->insert('productiveDeptXref',$data);
			}
			}
			$exists = array(
			'exist'=>false,
			'role'=>$role,
			'user'=>$newId,
			'goodEmail'=>$goodEmail
			);
            echo json_encode(array('success'=>true,'data'=>$exists));
			return true;
			
								
	}
			
}

function newUserEmail(){
	$accountId = Config::get('account')['accountId'];
	$userId = (isset($_REQUEST['userId'])) ? $_REQUEST['userId'] : '';
	
	if($userId && $accountId){
	$dbNew= Config::get('db')->get_results("select a.*, IFNULL(u.practiceId,0) as practiceId, IFNULL(u.email,'') as email, IFNULL(u.tmpw,'') as tmpw, IFNULL(u.id,0) as userId, IFNULL(u.active,0) as userActive, IFNULL(x.deptId,0) as deptId, l.defaultpw, l.tip1_filename, l.id as labelId, l.labelName, l.noreply, l.userLogin, l.videoLink, l.login, IFNULL(d.dept,'') AS dept, IFNULL(u.first_name,'') as first_name, IFNULL(u.last_name,'') as last_name from `productiveAccount` a LEFT JOIN `productiveLabel` as l on l.id = a.label LEFT OUTER JOIN `productiveUser` as u on u.accountId = a.id and u.id={$userId} LEFT OUTER JOIN `productiveDeptXref` as x on x.userId=u.id LEFT OUTER JOIN `ProductiveDept` as d on d.id = x.deptId WHERE a.id={$accountId}");
		
	$userId = intval($dbNew[0]['userId']);
	$userActive = intval($dbNew[0]['userActive']);
	$deptName = $dbNew[0]['dept'];
	$noreply = $dbNew[0]['noreply'];
	$name = $dbNew[0]['name'];
	$nickName = $dbNew[0]['nickName'];
	$xdeptId = intval($dbNew[0]['deptId']);
	$labelName = $dbNew[0]['labelName'];
	$labelId = $dbNew[0]['labelId'];
	$login = $dbNew[0]['userLogin'];
	$enterpriseId = $dbNew[0]['enterpriseId'];
	$video = $dbNew[0]['videoLink'];
	$defaultpw = $dbNew[0]['defaultpw'];
	$file1 = $dbNew[0]['tip1_filename'];
	$email = $dbNew[0]['email'];
	$pw = $dbNew[0]['tmpw'];
	$first = $dbNew[0]['first_name'];
	$practiceId = $dbNew[0]['practiceId'];
	
	
	if(strlen($file1 ?? '')>3){
		$attachment = "/home/prnadmin/public_html/docs/" . $file1;
		$support = '';	
		}else{
		$support = '';
		$attachment = '';	
		}
		
		if(intval($labelId)==3){
			$thanks = "Thank You!";
		}else{
			$thanks = "Thank you for using " . $labelName . "!";
		}
		
		$subject = $labelName . " New Account";
		$to = $email;
		$url = "https://$login";
		$mailId = stripslashes($email);

		$message = "<html>
		<head>
		<title>Welcome to $labelName</title>
		</head>
		<body>
		<table>
		<h5>$first,</h5>
		
		<tr>A $labelName ID has been created for you.  Your ID and temporary password are below.</tr>
		
		<p>
		<tr>ID: $mailId</tr>
		</p>
		<p>
		<tr>Temporary Password: $pw</tr>
		</p>
		<p>
		<tr>To login, go to <a href=" . $url . ">$labelName</a></tr>
		</p>
		
		
		<th>$thanks</th>
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
		
		$dataUpdate = array(
				'tmpw'=>''
            );
		$whereUpdate = array(
				'id'=>$userId
			);
		$updateUser = Config::get('db')->update('productiveUser',$dataUpdate,$whereUpdate);
		
		if(intval($practiceId)==0){
		/////////////PHP Mailer
		$mail = new PHPMailer(true);
		$mail->IsSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'productivern.com';                 // Specify main and backup server
		//$mail->Host = gethostbyname("smtp.productivern.com"); 
		$mail->Port = 465;                                    // Set the SMTP port
		$mail->SMTPAuth = true;
		$mail->SMTPDebug = 2;		// Enable SMTP authentication
		//$mail->Encoding = '7bit';
		$mail->Username = 'noreply@productivern.com';                // SMTP username
		$mail->Password = 'QL_X^8z@J)90';                  // SMTP password
		//$mail->Password = 'V0.C%Hajl.vf';
		$mail->SMTPSecure = 'ssl';                            // Enable encryption, 'ssl' also accepted

		$mail->From = 'noreply@productivern.com';
		$mail->FromName = ''. $labelName . '';
		$mail->AddAddress(''.$to.'');  // Add a recipient

		// Attachments
		if(strlen($attachment ?? '')>0){
		$mail->addAttachment('' . $attachment . '');// Add attachments
		}
		//$mail->AddEmbeddedImage('/home/prnadmin/public_html/dailyreports/vcImage'.$data2[0]['filedate'].'.jpg', 'vcImage');
		$mail->IsHTML(true);                                  // Set email format to HTML
		$mail->Subject = ''. $subject .'';
		$mail->Body    = ''.$message.'';

		if(!$mail->Send()) {
		   echo 'Message could not be sent.';
		   echo 'Mailer Error: ' . $mail->ErrorInfo;
		   exit;
		}
		}
		return true;
	}else{
		return true;
	}
}

/////////////////////////////////////
// Forgot
function Forgot() {
    require_once __DIR__ . '/ratelimit.php';
    $rlKey = 'forgot_' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
    if (!prd_rate_check($rlKey, 3, 600)) {
        echo json_encode(array('success' => false, 'message' => 'Too many requests. Please wait and try again.'));
        return false;
    }
    prd_rate_hit($rlKey, 600);
    $email1 = (isset($_REQUEST['email'])) ? $_REQUEST['email'] : '';
	$email = trim($email1);
	 //echo json_encode(array('email'=>$email));
	$emailSafe = Config::get('db')->filter($email);
	$dbData = Config::get('db')->get_results("select u.*, l.userLogin, l.labelName, l.noreply, a.label from `productiveUser` u left join `productiveAccount` as a on a.id=u.accountId LEFT JOIN `productiveLabel` as l on l.id = a.label where u.email='{$emailSafe}'");
	
    if ($dbData && intval($dbData[0]['active']) !=0) {
				
		$first = $dbData[0]['first_name'];
		$login = $dbData[0]['userLogin'];
		$label = $dbData[0]['labelName'];
		$noreply = $dbData[0]['noreply'];
		$labelId = intval($dbData[0]['label']);
		
		if($labelId==2 || $labelId==3){
				$pw="perfect";
				$pword = password_hash($pw, PASSWORD_BCRYPT);
				$support="For additional support videos, go <a href=\"https://www.perfectshift.com/learn/smtpublic\">here.</a>";
			}else{
				$support = '';
				$pw = generateRandomString(10);
				$pword = password_hash($pw, PASSWORD_BCRYPT);
			}
		
		if($labelId==3){
			$thanks = "Thank You!";
		}else{
			$thanks = "Thank you for using " . $label . "!";
		}
		
		//$text = intval($atext)+intval($rtext);
            $data = array(
				'pwd'=>$pword,
				'temp'=>$pword,
                'active'=>1
            );
			
		$updateWhere = array('email' =>addslashes($email));

            $newLoc = Config::get('db')->update('productiveUser',$data,$updateWhere);
		
		
		$subject = "ProductiveRN Password Reset";
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
<th>$thanks</th>
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
					
			
            echo json_encode(array('success'=>true,'message'=>'If that email is registered, a temporary password has been sent.'));
            return true;
		}

    echo json_encode(array('success'=>true,'message'=>'If that email is registered, a temporary password has been sent.'));
    return true;
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
    $userId = intval(isset($_REQUEST['userId'])?$_REQUEST['userId']:0);
	$user = Config::get('id')['id'];
	$accountId = intval(Config::get('account')['accountId']);
	if ($userId) {
		$check = Config::get('db')->get_results("SELECT id FROM productiveUser WHERE id={$userId} AND accountId={$accountId}");
		if (!$check) { echo json_encode(array('success'=>false,'message'=>'Not authorized')); return false; }
		$data = array(
				'active'=>0,
				'mobile'=>'',
				'lastupdateBy'=>$user
            );
        $where = array('id'=>$userId, 'accountId'=>$accountId);
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
	$accountId = intval(Config::get('account')['accountId']);
    if (intval($recordId) > 0) {
        $dbLocation = Config::get('db')->get_results("select r.* from `productiveResources` r JOIN `ProductiveDept` d ON d.id=r.deptId WHERE r.id={$recordId} AND d.accountId={$accountId}");
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
    $accountId = intval(Config::get('account')['accountId']);
	//echo "store:".$storeId;
    if ($recordId) {
        $dbLocation = Config::get('db')->get_results("select x.* from `productiveDeptXref` x JOIN `productiveUser` u ON u.id=x.userId WHERE x.id={$recordId} AND u.accountId={$accountId}");
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
	$accountId = intval(Config::get('account')['accountId']);
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
	$accountId = intval(Config::get('account')['accountId']);
	//echo "store:".$storeId;
    if (intval($recordId) > 0) {
        $check = Config::get('db')->get_results("SELECT r.id FROM productiveResources r JOIN ProductiveDept d ON d.id=r.deptId WHERE r.id={$recordId} AND d.accountId={$accountId}");
        if (!$check) { echo json_encode(array('success'=>false,'message'=>'Not authorized')); return false; }
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
    $accountId = intval(Config::get('account')['accountId']);

	if (intval($recordId) > 0) {
		$check = Config::get('db')->get_results("SELECT r.id FROM productiveResources r JOIN ProductiveDept d ON d.id=r.deptId WHERE r.id={$recordId} AND d.accountId={$accountId}");
        if (!$check) { echo json_encode(array('success'=>false,'message'=>'Not authorized')); return false; }
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
	$qblWarnMin = isset($_REQUEST['qblWarnMin']) ? intval($_REQUEST['qblWarnMin']) : 0;

	if($userId){

	$colCheck = Config::get('db')->get_results("SHOW COLUMNS FROM productiveUser LIKE 'qblWarnMin'");
	if (!$colCheck) { Config::get('db')->query("ALTER TABLE productiveUser ADD COLUMN qblWarnMin INT NOT NULL DEFAULT 0"); }

	$dataU = array(
		'role' =>$role,
		'email' =>$email,
		'qblWarnMin' =>$qblWarnMin,
		
			);
			
	$accountId = intval(Config::get('account')['accountId']);
	$check = Config::get('db')->get_results("SELECT id FROM productiveUser WHERE id={$userId} AND accountId={$accountId}");
	if (!$check) { echo json_encode(array('success'=>false,'message'=>'Not authorized')); return false; }
	$whereU = array(
		'id' => $userId,
		'accountId' => $accountId
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
    $accountId = intval(Config::get('account')['accountId']);

	if($recordId){
        $check = Config::get('db')->get_results("SELECT x.id FROM productiveDeptXref x JOIN productiveUser u ON u.id=x.userId WHERE x.id={$recordId} AND u.accountId={$accountId}");
        if (!$check) { echo json_encode(array('success'=>false,'message'=>'Not authorized')); return false; }
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

function UpdateEscAlerts() {
    $recordId = (isset($_REQUEST['recordId'])?$_REQUEST['recordId']:'');
    $textAlerts = (isset($_REQUEST['textAlerts'])?$_REQUEST['textAlerts']:0);
    $accountId = intval(Config::get('account')['accountId']);

	if($recordId){
        $check = Config::get('db')->get_results("SELECT x.id FROM productiveDeptXref x JOIN productiveUser u ON u.id=x.userId WHERE x.id={$recordId} AND u.accountId={$accountId}");
        if (!$check) { echo json_encode(array('success'=>false,'message'=>'Not authorized')); return false; }
		$datax = array(
            'escAlerts' =>$textAlerts
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


// UPDATE USER
function UpdateemailAlerts() {
    $recordId = (isset($_REQUEST['recordId'])?$_REQUEST['recordId']:'');
    $emailAlerts = (isset($_REQUEST['emailAlerts'])?$_REQUEST['emailAlerts']:0);
	$deptId = (isset($_REQUEST['deptId'])?$_REQUEST['deptId']:'');
    $accountId = intval(Config::get('account')['accountId']);

	if($recordId){
        $check = Config::get('db')->get_results("SELECT x.id FROM productiveDeptXref x JOIN productiveUser u ON u.id=x.userId WHERE x.id={$recordId} AND u.accountId={$accountId}");
        if (!$check) { echo json_encode(array('success'=>false,'message'=>'Not authorized')); return false; }
		$datax = array(
            'emailAlerts' =>$emailAlerts
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
	
   
        $dbLocation = Config::get('db')->get_results("SELECT x.*, x.id as Xid, r.role as roleName, u.txt, u.email, u.last_name, u.first_name, u.email2, u.mobile, u.txtactive, u.txtPause, d.dept as deptName FROM `productiveDeptXref` x LEFT JOIN `productiveUser` as u on u.id=x.userId LEFT JOIN `ProductiveDept` as d on d.id = x.deptId LEFT OUTER JOIN `productiveUserRoles` as r on r.id = x.primaryUnit WHERE x.userId={$userId} and d.unitCategory !=4 AND (d.active=1 OR d.active=2) GROUP BY x.userId, x.deptId ORDER BY d.dept ASC");
    
	if ($dbLocation) {
		
	foreach ($dbLocation as $dbData){
		
		if(intval($dbData['textVariance'])==0){
			$txtVar = 'ALL';
		}else{
			$txtVar = '+/- ' . $dbData['textVariance'];
		}
		
		if (intval($dbData['txtPause'])!=0 && $dbData['mobile']!=''){
			$txtStatus = "Paused";
			$txtVariance = "n/a";
		}else if (intval($dbData['txtPause'])==0 && intval($dbData['textAlerts'])>0 && strlen($dbData['mobile'] ?? '')==10){
			$Status = "Yes";
			$txtStatus = "<a href=\"javascript:;\" onclick=\"tj.edittextStatus(" . $dbData['textAlerts'] . "," . $dbData['Xid'] . ");return false;\" >". $Status ."</a>";
			$txtVariance = "<a href=\"javascript:;\" onclick=\"tj.edittextVariance(" . $dbData['textVariance'] . "," . $dbData['userId'] . "," . $dbData['deptId'] . ");return false;\" >". $txtVar ."</a>";
		}else if (intval($dbData['txtPause'])==0 && intval($dbData['textAlerts'])==0 && strlen($dbData['mobile'] ?? '')==10){
			$Status = "No";
			$txtStatus = "<a href=\"javascript:;\" onclick=\"tj.edittextStatus(" . $dbData['textAlerts'] . "," . $dbData['Xid'] . ");return false;\" >". $Status ."</a>";
			$txtVariance = "n/a";
		}else{
			$invalid = "Add Mobile";
			$txtStatus = '<a href="javascript:;" onclick="tj.activate2('.$dbData['userId'].')">'. $invalid .'</a>';
			$txtVariance = "n/a";
			
		}
		
		if (intval($dbData['txtPause'])!=0  && strlen($dbData['mobile'] ?? '')==10){
			$escStatus = "Paused";
		}else if (intval($dbData['txtPause'])==0 && intval($dbData['escAlerts'])>0 && strlen($dbData['mobile'] ?? '')==10){
			$status = "Yes";
			$escStatus = "<a href=\"javascript:;\" onclick=\"tj.editescStatus(" . $dbData['escAlerts'] . "," . $dbData['Xid'] . ");return false;\" >". $status ."</a>";
		}else if (intval($dbData['txtPause'])==0 && intval($dbData['escAlerts'])==0 && strlen($dbData['mobile'] ?? '')==10){
			$status = "No";
			$escStatus = "<a href=\"javascript:;\" onclick=\"tj.editescStatus(" . $dbData['escAlerts'] . "," . $dbData['Xid'] . ");return false;\" >". $status ."</a>";
		}else{
			$invalid = "Add Mobile";
			$escStatus = '<a href="javascript:;" onclick="tj.activate2('.$dbData['userId'].')">'. $invalid .'</a>';
		}
		
		if(!filter_var($dbData['email2'], FILTER_VALIDATE_EMAIL)){
			$email="Add Email";
			$emailStatus = '<a href="javascript:;" onclick="tj.activate2('.$dbData['userId'].')">'. $email .'</a>';
		}else if(intval($dbData['emailAlerts'])==0){
			$email="No";
			$emailStatus = "<a href=\"javascript:;\" onclick=\"tj.editemailStatus(" . $dbData['emailAlerts'] . "," . $dbData['Xid'] . "," . $dbData['deptId'] . ");return false;\" >". $email ."</a>";
		}else if(intval($dbData['emailAlerts'])==1){
			$email="Yes";
			$emailStatus = "<a href=\"javascript:;\" onclick=\"tj.editemailStatus(" . $dbData['emailAlerts'] . "," . $dbData['Xid'] . "," . $dbData['deptId'] . ");return false;\" >". $email ."</a>";
		}else{
			$email="Add Email";
			$emailStatus = '<a href="javascript:;" onclick="tj.activate2('.$dbData['userId'].')">'. $email .'</a>';
		}
		//if(intval($dbData['primaryUnit'])>0 && intval($dbData['role'])==4 && intval($dbData['primaryUnit'])==4) {
		if(intval($dbData['unitAssigned'])==1 && intval($userRole)<7){
			$primary = " (Unit Assigned)";
			//$action = "<a href=\"#units\">Change in Unit Settings</a>";
			$action = "Change in Unit Settings";
		}else if(intval($dbData['unitAssigned'])==1 && intval($userRole)>=7){
			$primary = " (Unit Assigned)";
			$action = "<button type=\"button\" class=\"btn btn-success btn-sm\" onclick=\"tj.removeuserUnit(" . $dbData['deptId'] . " , " . $dbData['userId'] . ")\">Remove</button>";
		}else{
			$primary = '';
			$action = "<button type=\"button\" class=\"btn btn-success btn-sm\" onclick=\"tj.removeuserUnit(" . $dbData['deptId'] . " , " . $dbData['userId'] . ")\">Remove</button>";
		}
		//$user = $dbData['last_name'] . ", " . $dbData['first_name'];
		$position = $dbData['last_name'] . ", " . $dbData['first_name'];	
				
        $jobArray[] = array(
			'unit'=>$dbData['deptName'],
            'text'=>$txtStatus,
			'variance'=>$txtVariance,
			'esc'=>$escStatus,
			'email'=>$emailStatus,
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
				'variance' => '',
				'esc' => '',
				'email'=> '',
				'role' => '',
				'action' => ''
            );
        $outData['data'] = $jobArray;
        echo json_encode($outData,JSON_NUMERIC_CHECK);  
	}
}

function updateUser2() {
    $userId = isset($_REQUEST['userId']) ? $_REQUEST['userId']: 0;
	$alertTimes = isset($_REQUEST['times']) ? $_REQUEST['times']: 0;
	$mobile = isset($_REQUEST['mobile']) ? $_REQUEST['mobile']: '';
	$email = isset($_REQUEST['email']) ? $_REQUEST['email']: '';
	$vtext = isset($_REQUEST['report'])? $_REQUEST['report'] : 0;
	$atext = isset($_REQUEST['escalation']) ? $_REQUEST['escalation'] : 0;
	$audit = isset($_REQUEST['audit']) ? $_REQUEST['audit'] : 0;
	$mtext = isset($_REQUEST['missed']) ? $_REQUEST['missed'] : 0;
	$stop = isset($_REQUEST['pause']) ? $_REQUEST['pause'] : 0;
	$houseAlerts = isset($_REQUEST['houseAlerts']) ? $_REQUEST['houseAlerts'] : 0;
	$qblWarnMin = isset($_REQUEST['qblWarnMin']) ? intval($_REQUEST['qblWarnMin']) : 0;

	$newMobile = isset($_REQUEST['newMobile']) ? $_REQUEST['newMobile']:0;
	$accountId = Config::get('account')['accountId'];
	$txt = isset($_REQUEST['txt'])? $_REQUEST['txt'] : 0;
	
	$currentRole = isset($_REQUEST['currentRole']) ? $_REQUEST['currentRole']:0;
	$newRole = isset($_REQUEST['newRole']) ? $_REQUEST['newRole']:0;
	$updateFirst = isset($_REQUEST['updateFirst']) ? $_REQUEST['updateFirst']:'';
	$updateLast = isset($_REQUEST['updateLast']) ? $_REQUEST['updateLast']:'';
	
	$ciphering = "AES-128-CTR";
	$options = 0;
	$encryption_iv = '1234567891011121';
	//$encryption_iv = '2273645911011121'; // legacy IV, do not use
	$encryption_key = "GoProGoogl3Max";
	
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$emailAlerts=0;
		$emailMissed=0;
	}else{
		$emailAlerts = isset($_REQUEST['emailAlerts']) ? $_REQUEST['emailAlerts'] : 0;
		$emailMissed = isset($_REQUEST['emailMissed']) ? $_REQUEST['emailMissed'] : 0;
	}
	
	$data = array(
            'txt' => $txt,
			'txtactive' => $vtext,
			'reportMissed' => $mtext,
			'txtEscalation' => $atext,
			'txtPause' => $stop,
			'role' => $newRole,
			'mobile' => $mobile,
			'alertTimes' => $alertTimes,
			'emailMissed' => $emailMissed,
			'emailEscalations' => $emailAlerts,
			'email2' => $email,
			'logAlerts' => $audit,
			'first_name' => $updateFirst,
			'last_name' => $updateLast,
			'first_name_E' => openssl_encrypt($updateFirst, $ciphering, $encryption_key, $options, $encryption_iv),
			'last_name_E' => openssl_encrypt($updateLast, $ciphering, $encryption_key, $options, $encryption_iv),
			'email2_E' => openssl_encrypt($email, $ciphering, $encryption_key, $options, $encryption_iv),
			'mobile_E' => openssl_encrypt($mobile, $ciphering, $encryption_key, $options, $encryption_iv),
			'houseAlerts' => $houseAlerts,
		'qblWarnMin' => $qblWarnMin
        );
		$check = Config::get('db')->get_results("SELECT id FROM productiveUser WHERE id={$userId} AND accountId={$accountId}");
		if (!$check) { echo json_encode(array('success'=>false,'message'=>'Not authorized')); return false; }
		$colCheck = Config::get('db')->get_results("SHOW COLUMNS FROM productiveUser LIKE 'qblWarnMin'");
		if (!$colCheck) { Config::get('db')->query("ALTER TABLE productiveUser ADD COLUMN qblWarnMin INT NOT NULL DEFAULT 0"); }
        $where = array('id'=>$userId, 'accountId'=>$accountId);
        Config::get('db')->update('productiveUser',$data,$where);

		$xdata = array(
            'emailAlerts' => $emailAlerts,
			'escAlerts' => $atext
			);
        $xwhere = array('userId'=>$userId);
        Config::get('db')->update('productiveDeptXref',$xdata,$xwhere);


	if($newRole != $currentRole && (intval($newRole)==6 || intval($newRole)==7 || intval($currentRole)==6 || intval($currentRole)==7)){
        $delwhere = array('userId'=>$userId
		);
        Config::get('db')->delete('productiveDeptXref',$delwhere);
	}
	
	if($newRole != $currentRole && intval($newRole)!=6 && intval($newRole)!=7 && intval($currentRole)!=6 && intval($currentRole)!=7){
		$xupdate = array(
            'primaryUnit' => $newRole
			);
        $where = array('userId'=>$userId
		);
        Config::get('db')->update('productiveDeptXref',$xupdate,$where);
	}
	
	if(intval($newMobile)==1 && $mobile){
	$dbLabel = Config::get('db')->get_results("SELECT l.* FROM `productiveLabel` l LEFT JOIN `productiveAccount` as a on a.label = l.id LEFT JOIN `productiveUser` u on u.accountId = a.id WHERE u.mobile={$mobile} and u.accountId={$accountId}");
	
	if($dbLabel && intval($dbLabel[0]['id'])!=1){
	$labelName = $dbLabel[0]['labelName'];
	}else{
	$labelName = "ProductiveRN";
	}
	
	$message = "Welcome to " . $labelName . " Alerts! Reply HELP for Help, or STOP to cancel. 1 msg per event.  Msg&Data rates may apply. www.productivern.com/terms ";
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
					'message' => $messages

				);
				$result = '';				
				
		$result = sendNow($smsoptions);	
		//$escresult = sendescNow($smsoptions);	
	}
	
    echo json_encode(array('success'=>true,'message'=>'User Updated'));
    return true;
}
/*	
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
*/
	
	
function demoMode(){
	$demoMode = isset($_POST['demo']) ? $_POST['demo'] : 0;
	$id = isset($_POST['id']) ? $_POST['id'] : 0;
		
	if(intval($id)>0){
		$data = array(
				'demoMode'=>$demoMode
            );
        $where = array('id'=>$id);
        Config::get('db')->update('productiveUser',$data,$where);
		
		echo json_encode(array('success'=>true));
	}else{
	echo json_encode(array('success'=>false));
	}
	return true;
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
        $dbLocation = Config::get('db')->get_results("SELECT x.*, r.role as roleName, IFNULL(u.mobile,0) as mobile, IFNULL(u.active,0) as active, u.last_name, u.first_name, u.role, u.txtPause FROM `productiveDeptXref` x LEFT OUTER JOIN `productiveUser` as u on u.id = x.userId LEFT JOIN `productiveUserRoles` as r on r.id = u.role WHERE u.active>0 AND (u.role<9 OR (u.role>8 AND x.textAlerts>0)) AND x.deptId={$deptId}");
    if ($dbLocation){
	foreach ($dbLocation as $dbData){
		if (intval($dbData['txtPause'])==0 && intval($dbData['textAlerts'])>0 && intval($dbData['mobile'])!=0 && intval($dbData['mobile'])!=''){
			$txtStatus = "Yes";
		}else{
			$txtStatus = "No";
		}
		//$user = $dbData['last_name'] . ", " . $dbData['first_name'];
		//$txtStatus = "<a href=\"javascript:;\" onclick=\"tj.edittextStatus(".$dbData['id'].");return false;\" >". $Status ."</a>";
		$position = $dbData['last_name'] . ", " . $dbData['first_name'];
		$action = "<button type=\"button\" class=\"btn btn-danger\" onclick=\"tj.deletetextResource(" . $dbData['id'] . ")\">Remove</button>";	
		if (intval($dbData['unitAssigned'])==0 && (intval($dbData['primaryUnit'])==6 || intval($dbData['primaryUnit'])==7)){
			$roleName = $dbData['roleName'] . " (Primary)";
		}else{
			$roleName = $dbData['roleName'];
		}		
        $jobArray[] = array(
			'user'=>$position,
            'role'=>$roleName,
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


function ChangeCoverage() {
	$accountId = Config::get('account')['accountId'];
	$role = Config::get('account')['role'];
    $userId = (isset($_REQUEST['userId'])?$_REQUEST['userId']:0);
	$deptId = (isset($_REQUEST['deptId'])?$_REQUEST['deptId']:'');
	$end = (isset($_REQUEST['endDate'])?$_REQUEST['endDate']:'0000-00-00');
	$cover = 2;
	if (intval($userId)==0) {
		    $where = array('deptId' => $deptId,
						'unitAssigned' => $cover
						);
            Config::get('db')->delete('productiveDeptXref', $where);
		
        echo json_encode(array('success'=>true,'message'=>'User Removed'));
        return true;
    }else{
		$where = array('deptId' => $deptId,
						'unitAssigned' => $cover
						);
        Config::get('db')->delete('productiveDeptXref', $where);
		
		$add = array('deptId' => $deptId,
					'accountId' => $accountId,
					'userId' => $userId,
					'textAlerts' => 1,
					'escAlerts' => 1,
					'primaryUnit' => $role,
					'unitAssigned' => $cover,
					'endCoverage' => $end
						);
        Config::get('db')->insert('productiveDeptXref', $add);
			
    echo json_encode(array('success'=>true,'message'=>'User Added'));
    return true;
	}
}

function copyUnit() {
	$accountId = Config::get('account')['accountId'];
	$role = Config::get('account')['role'];
    $newaccountId = (isset($_REQUEST['accountId'])?$_REQUEST['accountId']:0);
	$deptId = (isset($_REQUEST['deptId'])?$_REQUEST['deptId']:'');
	//$currentDate = (isset($_REQUEST['currentDate'])?$_REQUEST['currentDate']:'0000-00-00');

	if (intval($newaccountId)>0 && intval($deptId)>0) {
		$dbDept = Config::get('db')->get_results("SELECT *, CURRENT_TIMESTAMP() AS timeNow, DATE_FORMAT(CURDATE(),'%Y-%m-%d') as dateToday FROM `ProductiveDept` WHERE `id`={$deptId}");
		
		$newDept = array('dept' => 'Copy of' . $dbDept[0]['dept'],
						'totalbeds' => $dbDept[0]['totalbeds'],
						'accountId' => $newaccountId,
						'director' => $dbDept[0]['director'],
						'viewPage' => $dbDept[0]['viewPage'],
						'modalPage' => $dbDept[0]['modalPage'],
						'unitId' => $dbDept[0]['dept'] . ' ' . $dbDept[0]['timeNow'],
						'mgrId' => $dbDept[0]['mgrId'],
						'txtId' => $dbDept[0]['txtId'],
						'unitCategory' => $dbDept[0]['unitCategory'],
						'prodMeasure' => $dbDept[0]['prodMeasure'],
						'prodMeasure1' => $dbDept[0]['prodMeasure1'],
						'indMeasure' => $dbDept[0]['indMeasure'],
						'budgetMeasure' => $dbDept[0]['budgetMeasure'],
						'uosRollup' => $dbDept[0]['uosRollup'],
						'uosDisplay' => $dbDept[0]['uosDisplay'],
						'uosValue' => $dbDept[0]['uosValue'],
						'uosDefault' => $dbDept[0]['uosDefault'],
						'uosDesc' => $dbDept[0]['uosDesc'],
						'nurseDesc' => $dbDept[0]['nurseDesc'],
						'hppd' => $dbDept[0]['hppd'],
						'target' => $dbDept[0]['target'],
						'encounters' => $dbDept[0]['encounters'],
						'shift' => $dbDept[0]['shift'],
						'shiftsperDay' => $dbDept[0]['shiftsperDay'],
						'active' => 3,
						'charge' => $dbDept[0]['charge'],
						'countcharge' => $dbDept[0]['countcharge'],
						'chargeDesc' => $dbDept[0]['chargeDesc'],
						'rnCount' => $dbDept[0]['rnCount'],
						'rn1Count' => $dbDept[0]['rn1Count'],
						'rn2Count' => $dbDept[0]['rn2Count'],
						'roundrn' => $dbDept[0]['roundrn'],
						'oneto6' => $dbDept[0]['oneto6'],
						'oneto7' => $dbDept[0]['oneto7'],
						'oneto7Acuity' => $dbDept[0]['oneto7Acuity'],
						'oneto8' => $dbDept[0]['oneto8'],
						'oneto8Acuity' => $dbDept[0]['oneto8Acuity'],
						'desc8' => $dbDept[0]['desc8'],
						'oneto9' => $dbDept[0]['oneto9'],
						'oneto9Acuity' => $dbDept[0]['oneto9Acuity'],
						'desc9' => $dbDept[0]['desc9'],
						'oneto10' => $dbDept[0]['oneto10'],
						'oneto10Acuity' => $dbDept[0]['oneto10Acuity'],
						'desc10' => $dbDept[0]['desc10'],
						'oneto11' => $dbDept[0]['oneto11'],
						'oneto11Acuity' => $dbDept[0]['oneto11Acuity'],
						'desc11' => $dbDept[0]['desc11'],
						'desc12' => $dbDept[0]['desc12'],
						'oneto12' => $dbDept[0]['oneto12'],
						'oneto12Acuity' => $dbDept[0]['oneto12Acuity'],
						'oneto5' => $dbDept[0]['oneto5'],
						'oneto4' => $dbDept[0]['oneto4'],
						'oneto3' => $dbDept[0]['oneto3'],
						'oneto2' => $dbDept[0]['oneto2'],
						'oneto1' => $dbDept[0]['oneto1'],
						'oneto1Acuity' => $dbDept[0]['oneto1Acuity'],
						'oneto2Acuity' => $dbDept[0]['oneto2Acuity'],
						'oneto3Acuity' => $dbDept[0]['oneto3Acuity'],
						'oneto4Acuity' => $dbDept[0]['oneto4Acuity'],
						'oneto5Acuity' => $dbDept[0]['oneto5Acuity'],
						'oneto6Acuity' => $dbDept[0]['oneto6Acuity'],
						'bedcount1' => $dbDept[0]['bedcount1'],
						'bedcount2' => $dbDept[0]['bedcount2'],
						'bedcount3' => $dbDept[0]['bedcount3'],
						'bedcount4' => $dbDept[0]['bedcount4'],
						'bedcount5' => $dbDept[0]['bedcount5'],
						'bedcount6' => $dbDept[0]['bedcount6'],
						'twoto1' => $dbDept[0]['twoto1'],
						'desc1' => $dbDept[0]['desc1'],
						'desc2' => $dbDept[0]['desc2'],
						'desc3' => $dbDept[0]['desc3'],
						'desc4' => $dbDept[0]['desc4'],
						'desc5' => $dbDept[0]['desc5'],
						'desc6' => $dbDept[0]['desc6'],
						'desc7' => $dbDept[0]['desc7'],
						'desc21' => $dbDept[0]['desc21'],
						'linkedUnit' => $dbDept[0]['linkedUnit'],
						'linkedUnits' => $dbDept[0]['linkedUnits'],
						'linkUnits' => $dbDept[0]['linkUnits'],
						'escalations' => $dbDept[0]['escalations'],
						'skill1' => $dbDept[0]['skill1'],
						'skill2' => $dbDept[0]['skill2'],
						'skill3' => $dbDept[0]['skill3'],
						'skill4' => $dbDept[0]['skill4'],
						'skill5' => $dbDept[0]['skill5'],
						'skill6' => $dbDept[0]['skill6'],
						'skill7' => $dbDept[0]['skill7'],
						'skill8' => $dbDept[0]['skill8'],
						'skill9' => $dbDept[0]['skill9'],
						'skill10' => $dbDept[0]['skill10'],
						'skill11' => $dbDept[0]['skill11'],
						'skilldesc1' => $dbDept[0]['skilldesc1'],
						'skilldesc2' => $dbDept[0]['skilldesc2'],
						'skilldesc3' => $dbDept[0]['skilldesc3'],
						'skilldesc4' => $dbDept[0]['skilldesc4'],
						'skilldesc5' => $dbDept[0]['skilldesc5'],
						'skilldesc6' => $dbDept[0]['skilldesc6'],
						'skilldesc7' => $dbDept[0]['skilldesc7'],
						'skilldesc8' => $dbDept[0]['skilldesc8'],
						'skilldesc9' => $dbDept[0]['skilldesc9'],
						'skilldesc10' => $dbDept[0]['skilldesc10'],
						'skilldesc11' => $dbDept[0]['skilldesc11'],
						'skill1calc' => $dbDept[0]['skill1calc'],
						'skill2calc' => $dbDept[0]['skill2calc'],
						'skill3calc' => $dbDept[0]['skill3calc'],
						'skill4calc' => $dbDept[0]['skill4calc'],
						'skill5calc' => $dbDept[0]['skill5calc'],
						'skill6calc' => $dbDept[0]['skill6calc'],
						'skill7calc' => $dbDept[0]['skill7calc'],
						'skill8calc' => $dbDept[0]['skill8calc'],
						'skill9calc' => $dbDept[0]['skill9calc'],
						'skill10calc' => $dbDept[0]['skill10calc'],
						'skill11calc' => $dbDept[0]['skill11calc'],
						'skillbudget1' => $dbDept[0]['skillbudget1'],
						'skillbudget2' => $dbDept[0]['skillbudget2'],
						'skillbudget3' => $dbDept[0]['skillbudget3'],
						'skillbudget4' => $dbDept[0]['skillbudget4'],
						'skillbudget5' => $dbDept[0]['skillbudget5'],
						'thresholdHigh' => $dbDept[0]['thresholdHigh'],
						'thresholdLow' => $dbDept[0]['thresholdLow'],
						'churn' => $dbDept[0]['churn'],
						'customChurnLabel' => $dbDept[0]['customChurnLabel'],
						'showProdWHP' => $dbDept[0]['showProdWHP'],
						'category' => $dbDept[0]['category'],
						'adjustment' => $dbDept[0]['adjustment'],
						'nurse1' => $dbDept[0]['nurse1'],
						'nurse1Desc' => $dbDept[0]['nurse1Desc'],
						'nurse1Variance' => $dbDept[0]['nurse1Variance'],
						'sittersNEWDesc' => $dbDept[0]['sittersNEWDesc'],
						'nurse2Desc' => $dbDept[0]['nurse2Desc'],
						'other1Desc' => $dbDept[0]['other1Desc'],
						'other2Desc' => $dbDept[0]['other2Desc'],
						'other3Desc' => $dbDept[0]['other3Desc'],
						'other1Calc' => $dbDept[0]['other1Calc'],
						'other2Calc' => $dbDept[0]['other2Calc'],
						'other3Calc' => $dbDept[0]['other3Calc'],
						'secCalc' => $dbDept[0]['secCalc'],
						'techCalc' => $dbDept[0]['techCalc'],
						'inshiftProd' => $dbDept[0]['inshiftProd'],
						'censusCalc' => $dbDept[0]['censusCalc'],
						'rnThreshold' => $dbDept[0]['rnThreshold'],
						'gThreshold' => $dbDept[0]['gThreshold'],
						'hrsThreshold' => $dbDept[0]['hrsThreshold'],
						'createdDate' => $dbDept[0]['createdDate'],
						'createdBy' => $dbDept[0]['createdBy'],
						'censusAvg' => $dbDept[0]['censusAvg'],
						'patientTotalDesc' => $dbDept[0]['patientTotalDesc'],
						'patientTotalDesc2' => $dbDept[0]['patientTotalDesc2'],
						'patientTotalDesc3' => $dbDept[0]['patientTotalDesc3'],
						'patientTotalDesc4' => $dbDept[0]['patientTotalDesc4'],
						'ptTotal2' => $dbDept[0]['ptTotal2'],
						'ptTotal3' => $dbDept[0]['ptTotal3'],
						'ptTotal4' => $dbDept[0]['ptTotal4'],
						'unlockedPatientCount' => $dbDept[0]['unlockedPatientCount'],
						'los' => $dbDept[0]['los'],
						'secView' => $dbDept[0]['secView'],
						'secLabel' => $dbDept[0]['secLabel'],
						'techLabel' => $dbDept[0]['techLabel'],
						'showPlannedDash' => $dbDept[0]['showPlannedDash'],
						'o_Calc' => $dbDept[0]['o_Calc'],
						'visitHoursReqd' => $dbDept[0]['visitHoursReqd'],
						'showPlanned' => $dbDept[0]['showPlanned'],
						'halfNurse' => $dbDept[0]['halfNurse'],
						'avgWage' => $dbDept[0]['avgWage'],
						'useavgWage' => $dbDept[0]['useavgWage'],
						'add_comment' => $dbDept[0]['add_comment'],
						'crashLog' => $dbDept[0]['crashLog'],
						'hrs1Desc' => $dbDept[0]['hrs1Desc'],
						'hrs2Desc' => $dbDept[0]['hrs2Desc'],
						'hrs3Desc' => $dbDept[0]['hrs3Desc'],
						'hrs1Calc' => $dbDept[0]['hrs1Calc'],
						'hrs2Calc' => $dbDept[0]['hrs2Calc'],
						'hrs3Calc' => $dbDept[0]['hrs3Calc'],
						'customWHP' => $dbDept[0]['customWHP'],
						'customWHPvalue' => $dbDept[0]['customWHPvalue'],
						'customDesc' => $dbDept[0]['customDesc'],
						'customWHP2' => $dbDept[0]['customWHP2'],
						'customWHP2value' => $dbDept[0]['customWHP2value'],
						'customDesc2' => $dbDept[0]['customDesc2'],
						'customWHP3' => $dbDept[0]['customWHP3'],
						'customWHP3value' => $dbDept[0]['customWHP3value'],
						'planVal3' => $dbDept[0]['planVal3'],
						'customDesc3' => $dbDept[0]['customDesc3'],
						'customWHP4' => $dbDept[0]['customWHP4'],
						'customWHP4value' => $dbDept[0]['customWHP4value'],
						'customDesc4' => $dbDept[0]['customDesc4'],
						'customWHP5' => $dbDept[0]['customWHP5'],
						'customWHP5value' => $dbDept[0]['customWHP5value'],
						'customDesc5' => $dbDept[0]['customDesc5'],
						'patientCustomDivider' => $dbDept[0]['patientCustomDivider'],
						'daysWk' => $dbDept[0]['daysWk'],
						'indUOS' => $dbDept[0]['indUOS'],
						'indHRS' => $dbDept[0]['indHRS'],
						'indUOSDesc' => $dbDept[0]['indUOSDesc'],
						'indHRSDesc' => $dbDept[0]['indHRSDesc'],
						'dashColor' => $dbDept[0]['dashColor'],
						'useDayRank' => $dbDept[0]['useDayRank'],
						'deptType' => $dbDept[0]['deptType'],
						'rnVariance' => $dbDept[0]['rnVariance'],
						'useGrid' => $dbDept[0]['useGrid'],
						'trackdc' => $dbDept[0]['trackdc'],
						'trackdata' => $dbDept[0]['trackdata'],
						'trackptdata' => $dbDept[0]['trackptdata'],
						'track1Desc' => $dbDept[0]['track1Desc'],
						'track2Desc' => $dbDept[0]['track2Desc'],
						'track3Desc' => $dbDept[0]['track3Desc'],
						'track4Desc' => $dbDept[0]['track4Desc'],
						'typetrack1' => $dbDept[0]['typetrack1'],
						'typetrack2' => $dbDept[0]['typetrack2'],
						'typetrack3' => $dbDept[0]['typetrack3'],
						'typetrack4' => $dbDept[0]['typetrack4'],
						'updatedBy' => $dbDept[0]['updatedBy'],
						'updatedDate' => $dbDept[0]['updatedDate'],
						'showrn' => $dbDept[0]['showrn'],
						'showrntest' => $dbDept[0]['showrntest'],
						'varianceMsg' => $dbDept[0]['varianceMsg'],
						'actionPlan' => $dbDept[0]['actionPlan'],
						'showVisits' => $dbDept[0]['showVisits'],
						'patientcountTitle' => $dbDept[0]['patientcountTitle'],
						'resourcesTitle' => $dbDept[0]['resourcesTitle'],
						'showEpic' => $dbDept[0]['showEpic'],
						'showEpicNurse' => $dbDept[0]['showEpicNurse'],
						'minRN' => $dbDept[0]['minRN'],
						'flow1' => $dbDept[0]['flow1'],
						'flow2' => $dbDept[0]['flow2'],
						'flow3' => $dbDept[0]['flow3'],
						'flow4' => $dbDept[0]['flow4'],
						'flow5' => $dbDept[0]['flow5'],
						'flow6' => $dbDept[0]['flow6'],
						'flow7' => $dbDept[0]['flow7'],
						'flow8' => $dbDept[0]['flow8'],
						'flow9' => $dbDept[0]['flow9'],
						'averageAcuity' => $dbDept[0]['averageAcuity'],
						'useFlow' => $dbDept[0]['useFlow'],
						'showArchive' => $dbDept[0]['showArchive'],
						'supportHrs' => $dbDept[0]['supportHrs'],
						'autoPop' => $dbDept[0]['autoPop'],
						'showHPPS' => $dbDept[0]['showHPPS'],
						'houseAlert' => $dbDept[0]['houseAlert'],
						'gridOpt1' => $dbDept[0]['gridOpt1'],
						'gridOpt2' => $dbDept[0]['gridOpt2'],
						'gridOpt3' => $dbDept[0]['gridOpt3'],
						'usersType' => $dbDept[0]['usersType']
						);
        Config::get('db')->insert('ProductiveDept', $newDept);	
		
		$newId = Config::get('db')->get_results("select MAX(`id`) as id FROM `ProductiveDept`");
		$newdeptId = $newId[0]['id'];
		
		$dbCopy = Config::get('db')->get_results("SELECT *, DATE_FORMAT(CURDATE(),'%Y-%m-%d') as dateToday, DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL 1 DAY),'%Y-%m-%d') as dateTomorrow FROM `productiveAccountXref` WHERE `dept`={$deptId}");
		foreach($dbCopy as $copy){
		$add = array('accountId' => $newaccountId,
					'dept' => $newdeptId,
					'shift' => $copy['shift'],
					'shiftName' => $copy['shiftName'],
					'active' => 1,
					'type' => $copy['type'],
					'shiftOrder' => $copy['shiftOrder']
						);
        Config::get('db')->insert('productiveAccountXref', $add);
		
		$addShift1 = array('accountId' => $newaccountId,
					'deptId' => $newdeptId,
					'shift' => $copy['shift'],
					'dayDate' => $copy['dateToday'],
					'type' => $copy['type'],
					'shiftOrder' => $copy['shiftOrder']
						);
        Config::get('db')->insert('productiveNewData', $addShift1);
		
		/*
		$addShift2 = array('accountId' => $newaccountId,
					'deptId' => $newdeptId,
					'shift' => $copy['shift'],
					'dayDate' => $copy['dateTomorrow'],
					'type' => $copy['type'],
					'shiftOrder' => $copy['shiftOrder']
						);
        Config::get('db')->insert('productiveNewData', $addShift2);
		*/
		}	
		$dbGrid = Config::get('db')->get_results("SELECT * FROM `productiveStaffingGrid` WHERE `griddeptId`={$deptId}");
		if($dbGrid){
		foreach($dbGrid as $grid){
		$addGrid = array('accountId' => $newaccountId,
					'griddeptId' => $newdeptId,
					'unitName' => $grid['unitName'],
					'shiftTime' => $grid['shiftTime'],
					'census' => $grid['census'],
					'cn' => $grid['cn'],
					'rn' => $grid['rn'],
					'rn1' => $grid['rn1'],
					'rn2' => $grid['rn2'],
					'pct' => $grid['pct'],
					'sitter' => $grid['sitter'],
					'sec' => $grid['sec'],
					'other1' => $grid['other1'],
					'other2' => $grid['other2'],
					'other3' => $grid['other3'],
					'dayNight' => $grid['dayNight'],
					'unused' => $grid['unused'],
					'dow' => $grid['dow'],
					'day' => $grid['day'],
					'easTarget' => $grid['easTarget'],
					'easMax' => $grid['easMax'],
					'ampm' => $grid['ampm']
						);
        Config::get('db')->insert('productiveStaffingGrid', $addGrid);
		}
		}
    echo json_encode(array('success'=>true,'message'=>'Unit Copied'));
    return true;
	}
	
}

function Getcoverage() {
    $accountId = Config::get('account')['accountId'];
	$userId = Config::get('account')['id'];
	$role = Config::get('account')['role'];
	$jobArray = array();
	$outData = array();

    if ($userId) {
        $dbLocation = Config::get('db')->get_results("SELECT x.*, u.role, d.dept FROM `productiveDeptXref` x LEFT JOIN `ProductiveDept` as d on d.id = x.deptId LEFT JOIN `productiveUser` as u on u.id={$userId} WHERE x.userId={$userId} AND x.primaryUnit={$role} AND x.unitAssigned=0 AND d.active=1 GROUP by x.deptId ORDER BY d.dept ASC");
    
	if ($dbLocation){
	
	foreach ($dbLocation as $dbData){
		
    	$dbCoverage = Config::get('db')->get_results("SELECT * from `productiveUser` where `accountId`={$_SESSION['account']['accountId']} AND `role`<8 AND `role`>4 AND `active`>0 GROUP BY `id` order by `last_name` ASC, `first_name` ASC");
		$unit = $dbData['dept'];
		$deptId = $dbData['deptId'];
		$role = $dbData['role'];
		$option ='';
		$option .='';
	
		foreach($dbCoverage as $cover){
		$dbCover = Config::get('db')->get_results("SELECT IFNULL(x.userId,0) as userId, IFNULL(x.endCoverage,'0000-00-00') as endCoverage, IFNULL(u.last_name,'') as last_name, IFNULL(u.first_name,'') as first_name FROM `productiveDeptXref` x LEFT OUTER JOIN `productiveUser` as u on u.id = x.userId WHERE x.deptId={$deptId} AND x.unitAssigned=2 GROUP by x.userId LIMIT 0,1");
		$current = $dbCover[0]['userId'];
		$currentLn = $dbCover[0]['last_name'];
		$currentFn = $dbCover[0]['first_name'];
		if($dbCover[0]['endCoverage']=='0000-00-00'){
			$coverEnd = '';
		}else{
			$coverEnd = $dbCover[0]['endCoverage'];
		}
		
		if(intval($current)==0){
			$noCover = '<option value="0">Select</option>';
		}else{
			$noCover = '<option value="' . $current . '">' . $currentLn . ', ' . $currentFn . '</option><option value="0">REMOVE COVERAGE</option>';
		}
		//$currentId = '<input id="curId' . $deptId . '"  value="' . $current . '" type="text"></input>';
		//$currentEnd ='<input id="curEnd' . $deptId . '"  value="' . $coverEnd . '" type="date"></input>'; 
		$coverId = $cover['id'];
		$coverLn = $cover['last_name'];
		$coverFn = $cover['first_name'];
		$end = '<input id="end' . $deptId . '" type="date" name="endCoverage" onchange="tj.changeCover(' . $deptId . ' );" value="' . $coverEnd . '" ></input>';
		$option .= '<option value="' . $coverId . '">' . $coverLn . ', ' . $coverFn . '</option>';
		}
		$user = '<select id="selectCoverage' . $deptId . '" onchange="tj.changeCover(' . $deptId . ' );" type="select">' . $noCover . '' . $option . '</select>';
		
		
        $jobArray[] = array(
			'unit'=> $unit,
            'cover'=> $user,
			'end'=> $end,
			'role'=> $role
        );
		
    }
    echo json_encode(array('success'=>true,'data'=>$jobArray));  
	}else{
	$jobArray[] = array(
				'unit' => '',
				'cover' => '',
				'end' => '',
				'role' => ''
            );
    echo json_encode(array('success'=>true,'data'=>$jobArray));  
	}
}
}




/////////////////////////////////////
// ADD RESOURCE
function AddtextResource() {
    $deptId = (isset($_REQUEST['deptId'])) ? $_REQUEST['deptId'] : '';
	$userId = (isset($_REQUEST['userId'])) ? $_REQUEST['userId'] : '';
	//$grantText = (isset($_REQUEST['grantText'])) ? $_REQUEST['grantText'] : '';
	
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
    $accountId = intval(Config::get('account')['accountId']);

	if ($recordId) {
        $check = Config::get('db')->get_results("SELECT x.id FROM productiveDeptXref x JOIN productiveUser u ON u.id=x.userId WHERE x.id={$recordId} AND u.accountId={$accountId}");
        if (!$check) { echo json_encode(array('success'=>false,'message'=>'Not authorized')); return false; }
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
     $accountId = intval(Config::get('account')['accountId']);

	if(intval($userId)>0) {
        $check = Config::get('db')->get_results("SELECT id FROM ProductiveDept WHERE id={$deptId} AND accountId={$accountId}");
        if (!$check) { echo json_encode(array('success'=>false,'message'=>'Not authorized')); return false; }
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
    $accountId = intval(Config::get('account')['accountId']);

	if ($recordId) {
        $check = Config::get('db')->get_results("SELECT x.id FROM productiveDeptXref x JOIN productiveUser u ON u.id=x.userId WHERE x.id={$recordId} AND u.accountId={$accountId}");
        if (!$check) { echo json_encode(array('success'=>false,'message'=>'Not authorized')); return false; }
		    $data = array('textAlerts' => $textval);
			$where = array('id' => $recordId);
            Config::get('db')->update('productiveDeptXref',$data,$where);
		
        echo json_encode(array('success'=>true,'message'=>'User Update'));
        return true;
    }else{
    echo json_encode(array('success'=>true,'message'=>'Update Failed'));
    return false;
	}
}

function UpdatetxtVar() {
    $userId = (isset($_REQUEST['userId']) ? $_REQUEST['userId']:0);
	$deptId = (isset($_REQUEST['deptId']) ? $_REQUEST['deptId']:0);
	$txtVar = (isset($_REQUEST['txtVar']) ? $_REQUEST['txtVar']:0);
    $accountId = intval(Config::get('account')['accountId']);

	if (intval($userId)>0 && intval($deptId)>0) {
        $check = Config::get('db')->get_results("SELECT x.id FROM productiveDeptXref x JOIN productiveUser u ON u.id=x.userId WHERE x.userId={$userId} AND x.deptId={$deptId} AND u.accountId={$accountId}");
        if (!$check) { echo json_encode(array('success'=>false,'message'=>'Not authorized')); return false; }
		    $data = array('textVariance' => $txtVar);
			$where = array('userId' => $userId,
							'deptId' => $deptId);
            Config::get('db')->update('productiveDeptXref',$data,$where);
		
        echo json_encode(array('success'=>true,'message'=>'User Update'));
        return true;
    }else{
    echo json_encode(array('success'=>true,'message'=>'Update Failed'));
    return false;
	}
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
	$demoMode = Config::get('account')['demoMode'];
	$enterpriseId = Config::get('account')['enterpriseId'];
	$label = Config::get('account')['label'];
	
	if(intval($userId)==1450 && intval($demoMode)==0){
		$searchAdd = '';
	}else if(intval($role)<8){
		$searchAdd = "WHERE d.accountId ={$accountId} AND d.id in (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId} and `primaryUnit`>=5)";
	}else if((intval($role)>=8 && intval($role)<=10) || (intval($role)==11 && intval($demoMode)==1) || (intval($role)==12 && intval($demoMode)==1)){
		$searchAdd = "WHERE d.accountId ={$accountId} AND (d.active=1 OR d.active=2)";
	}else if((intval($role)==11 && intval($demoMode)==0) || (intval($role)==12 && intval($demoMode)==0)){
		$searchAdd = "WHERE d.accountId ={$accountId}";
	}else if(intval($role)>=13){
		$searchAdd = "WHERE a.enterpriseId ={$enterpriseId} AND d.id in (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId})";
	}else if(intval($role)>=14){
		$searchAdd = "WHERE a.enterpriseId ={$enterpriseId}";
	}else{
		$searchAdd = '';
	}
    
        $query = "SELECT SQL_CALC_FOUND_ROWS
		d.*, c.categoryName, (SELECT `id` FROM `productiveStaffingGrid` WHERE `griddeptId`= d.id LIMIT 0,1) as hasGrid, unitId as number, a.nickName, a.enterpriseId, a.name as accountName, IFNULL(u.last_name,'') as manager, m.name as prodname, IFNULL(u.first_name,'') as first_name, IFNULL(u.role,0) as role    
        FROM `ProductiveDept` d
		LEFT JOIN `productiveMeasures` as m on m.id = d.prodMeasure
		LEFT JOIN `productiveAccount` as a on a.id = d.accountId		
		LEFT OUTER JOIN `productiveDeptXref` as x on x.deptId = d.id AND x.primaryUnit=6 AND x.unitAssigned=0
		LEFT OUTER JOIN `productiveUser` as u on u.id = x.userId
		LEFT OUTER JOIN `productiveCategory` as c on c.id = d.category
        $searchAdd 
		GROUP BY d.id ORDER BY a.name ASC, d.active DESC, d.dept ASC
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
		
		$dbCover = Config::get('db')->get_results("SELECT IFNULL(x.userId,0) as userId, IFNULL(x.endCoverage,'0000-00-00') as endCoverage, IFNULL(u.last_name,'') as last_name, IFNULL(u.first_name,'') as first_name FROM `productiveDeptXref` x LEFT OUTER JOIN `productiveUser` as u on u.id = x.userId WHERE x.deptId={$job['id']} AND x.unitAssigned=2 GROUP by x.userId LIMIT 0,1");
		if($dbCover){
		$current = $dbCover[0]['userId'];
		$currentLn = $dbCover[0]['last_name'];
		$currentFn = $dbCover[0]['first_name'];
		
		$cover = '<a href="javascript:;" onclick="tj.unitCoverage(' . $job['id'] . ')">' . $currentLn . ', ' . $currentFn . '</a>';
		//$cover = $currentLn . ', ' . $currentFn;
		}else{
		$cover = '<a href="javascript:;" onclick="tj.unitCoverage(' . $job['id'] . ')">Add</a>';
		}
        
		if(intval($userId)==1450){
		$name = $job['accountName'] . " - " . $job['dept'];
		}else{
		$name = $job['dept'];
		}
		
		if(intval($job['showArchive'])==0){
			$archive = '';
		}else{
			$archive = " - Show Archive";
		}
		
		if(intval($job['active'])==0){
			$name = $name. "<span style=\"color:red\"> (Inactive" . $archive . ")</span>";
		}
		if(intval($job['active'])==2){
			$name = $name. "<span style=\"color:red\"> (Customer Setup)</span>";
		}
		if(intval($job['active'])==3){
			$name = $name. "<span style=\"color:red\"> (Admin Setup)</span>";
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
		
		if($job['hasGrid'] && intval($userId) !=1450){
		$grid = "<form id=\"dateFormgrid\" name=\"dateFormgrid\" action=\"inc/reports.php?req=downloadgrid\" method=\"post\"><button id=\"downloadGrid\" class=\"btn btn-info btn-sm\" type=\"submit\">Grid</button><input type=\"number\" name=\"griddeptId\" value=\"" . $job['id'] . "\" hidden></form>";
		$copy = '';
		}else if($job['hasGrid'] && intval($userId) ==1450){
		$grid = "<form id=\"dateFormgrid\" name=\"dateFormgrid\" action=\"inc/reports.php?req=downloadgrid\" method=\"post\"><button id=\"downloadGrid\" class=\"btn btn-info btn-sm\" type=\"submit\">Grid</button><input type=\"number\" name=\"griddeptId\" value=\"" . $job['id'] . "\" hidden></form>";
		$copy ='<a href="javascript:;" onclick="tj.copyUnit(' . $job['id'] . ')">Copy</a>';
		}else if(intval($userId)==1450 && !$job['hasGrid']){		
		$grid="<button type=\"button\" id=\"addGridbutton\" class=\"btn btn-success btn-sm\" onclick=\"tj.addGrid(" . $job['id'] . "); \" >Add</button>";
		$copy ='<a href="javascript:;" onclick="tj.copyUnit(' . $job['id'] . ')">Copy</a>';
		}else if(intval($userId)!=1450 && intval($role)==11 && !$job['hasGrid']){		
		$grid="<button type=\"button\" id=\"addGridbutton\" class=\"btn btn-success btn-sm\" onclick=\"tj.addGrid(" . $job['id'] . "); \" >Add</button>";
		$copy ='';
		}else{		
		$grid= '';
		$copy = '';
		}
				
        $jobArray[] = array(
            'Name'=>$unitLink,
			'Category'=>$job['categoryName'],
            'Value'=>$manager,
			'Manager'=> $cover,
			'Number'=>$grid . '' . $copy,
			'Active'=>$job['active']
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
	
	if(intval($role)>=7 && intval($role)<14){
		$searchAdd = "WHERE a.active>0 AND a.id={$accountId}";
	}else if(intval($role)>=14){
		$searchAdd = "WHERE a.active>0 AND a.enterpriseId={$enterpriseId}";
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

function GetUnitCoverage() {
    $deptId = (isset($_REQUEST['deptId'])) ? $_REQUEST['deptId'] : '';
	$accountId = Config::get('account')['accountId'];
	
	$dbUnits = Config::get('db')->get_results("select IFNULL(x.userId,0) as userId, d.dept, IFNULL(x.endCoverage,'') as endCoverage, IFNULL(u.last_name,'') as last_name, IFNULL(u.first_name,'') as first_name from `productiveDeptXref` x LEFT JOIN `ProductiveDept` as d on d.id=x.deptId LEFT OUTER JOIN `productiveUser` as u on u.id=x.userId where x.deptId={$deptId} and x.unitAssigned=2");
               
		if($deptId && $dbUnits){
		$dbData = $dbUnits[0];	
		$dbCover = array(
				'userId'=> $dbData['userId'],
				'lastname'=> $dbData['last_name'],
				'firstname'=> $dbData['first_name'],
				'deptId'=> $deptId,
				'dept'=>  $dbData['dept'],
				'endCover' => $dbData['endCoverage']
				);
		echo json_encode(array('success' => true,'data'=>$dbCover));
        return true;
		}else{
		$dbUnits = Config::get('db')->get_results("select * from `ProductiveDept` where `id`={$deptId}");
		$dbData = $dbUnits[0];	
		$dbCover = array(
				'userId'=> '0',
				'lastname'=> '',
				'firstname'=> '',
				'deptId'=> $deptId,
				'dept'=> $dbData['dept'],
				'endCover' => ''
				);
	    echo json_encode(array('success' => true,'data'=>$dbCover));
		return true;
		}
}

/////////////////////////////////////
// GET USER COVERAGE
function GetUserCoverage() {
	$targetUserId = (isset($_REQUEST['userId'])) ? intval($_REQUEST['userId']) : 0;
	$accountId = Config::get('account')['accountId'];
	$ciphering = "AES-128-CTR";
	$options = 0;
	$encryption_iv = ENCRYPT_IV;
	$encryption_key = ENCRYPT_KEY;

	$dbUser = Config::get('db')->get_results("SELECT u.id, u.first_name_E, u.last_name_E, IFNULL(u.coverUserId,0) as coverUserId, IFNULL(u.coverStartDate,'') as coverStartDate, IFNULL(u.coverStartTime,'') as coverStartTime, IFNULL(u.coverEndDate,'') as coverEndDate, IFNULL(u.coverEndTime,'') as coverEndTime, IFNULL(u.coverPauseSms,0) as coverPauseSms, IFNULL(u.coverVarianceMin,1) as coverVarianceMin, IFNULL(u.coverQblMin,300) as coverQblMin FROM `productiveUser` u WHERE u.id={$targetUserId} AND u.accountId={$accountId}");

	if ($dbUser) {
		$u = $dbUser[0];
		$firstName = openssl_decrypt($u['first_name_E'], $ciphering, $encryption_key, $options, $encryption_iv);
		$lastName = openssl_decrypt($u['last_name_E'], $ciphering, $encryption_key, $options, $encryption_iv);
		$data = array(
			'userId' => $targetUserId,
			'name' => $lastName . ', ' . $firstName,
			'coverUserId' => intval($u['coverUserId']),
			'coverEndDate' => ($u['coverEndDate'] == '0000-00-00' ? '' : $u['coverEndDate']),
			'coverStartDate' => $u['coverStartDate'],
			'coverStartTime' => substr($u['coverStartTime'], 0, 5),
			'coverEndTime' => ($u['coverEndTime'] == '00:00:00' || $u['coverEndTime'] == '' ? '' : substr($u['coverEndTime'], 0, 5)),
			'coverPauseSms' => intval($u['coverPauseSms']),
			'coverVarianceMin' => intval($u['coverVarianceMin']),
			'coverQblMin' => intval($u['coverQblMin'])
		);
		echo json_encode(array('success' => true, 'data' => $data));
	} else {
		echo json_encode(array('success' => false, 'message' => 'User not found'));
	}
}

/////////////////////////////////////
// CHANGE USER COVERAGE
function ChangeUserCoverage() {
	$accountId = Config::get('account')['accountId'];
	$targetUserId = (isset($_REQUEST['userId'])) ? intval($_REQUEST['userId']) : 0;
	$coverUserId = (isset($_REQUEST['coverUserId'])) ? intval($_REQUEST['coverUserId']) : 0;
	$endDate = (isset($_REQUEST['endDate']) && $_REQUEST['endDate'] != '') ? $_REQUEST['endDate'] : '0000-00-00';
	$endTime = (isset($_REQUEST['endTime']) && $_REQUEST['endTime'] != '') ? $_REQUEST['endTime'] : '';
	$startDate = (isset($_REQUEST['startDate']) && $_REQUEST['startDate'] != '') ? $_REQUEST['startDate'] : date('Y-m-d');
	$startTime = (isset($_REQUEST['startTime']) && $_REQUEST['startTime'] != '') ? $_REQUEST['startTime'] : '';
	$pauseSms = (isset($_REQUEST['pauseSms'])) ? intval($_REQUEST['pauseSms']) : 0;
	$varianceMin = (isset($_REQUEST['varianceMin'])) ? intval($_REQUEST['varianceMin']) : 1;
	$qblMin = (isset($_REQUEST['qblMin'])) ? intval($_REQUEST['qblMin']) : 300;
	if ($targetUserId > 0) {
		$check = Config::get('db')->get_results("SELECT id FROM `productiveUser` WHERE `id`={$targetUserId} AND `accountId`={$accountId}");
		if (!$check) { echo json_encode(array('success'=>false,'message'=>'Not authorized')); return false; }
		if ($coverUserId == 0) {
			$data = array('coverUserId'=>0,'coverStartDate'=>'0000-00-00','coverStartTime'=>'','coverEndDate'=>'0000-00-00','coverEndTime'=>'','coverPauseSms'=>0,'coverVarianceMin'=>1,'coverQblMin'=>300);
		} else {
			$data = array(
				'coverUserId' => $coverUserId,
				'coverStartDate' => $startDate,
				'coverStartTime' => $startTime,
				'coverEndDate' => $endDate,
				'coverEndTime' => $endTime,
				'coverPauseSms' => $pauseSms,
				'coverVarianceMin' => $varianceMin,
				'coverQblMin' => $qblMin
			);
		}
		$where = array('id' => $targetUserId);

		// Get users + label before update (need prev coverUserId for removal SMS)
		$ciphering = 'AES-128-CTR';
		$opts = 0;
		$enc_iv  = ENCRYPT_IV;
		$enc_key = ENCRYPT_KEY;

		$dbOrig = Config::get('db')->get_results("
			SELECT u.first_name_E, u.last_name_E, u.coverUserId as prevCoverUserId
			FROM `productiveUser` u
			WHERE u.id = {$targetUserId}
			LIMIT 1
		");

		Config::get('db')->update('productiveUser', $data, $where);

		if ($dbOrig) {
			$origFirst   = openssl_decrypt($dbOrig[0]['first_name_E'], $ciphering, $enc_key, $opts, $enc_iv);
			$origLast    = openssl_decrypt($dbOrig[0]['last_name_E'],  $ciphering, $enc_key, $opts, $enc_iv);
			$prevCoverId = intval($dbOrig[0]['prevCoverUserId']);

			if ($coverUserId > 0 && $coverUserId !== $prevCoverId) {
				// Send SMS to covering user
				$dbCover = Config::get('db')->get_results("
					SELECT mobile FROM `productiveUser`
					WHERE id = {$coverUserId} AND LENGTH(mobile)=10 LIMIT 1
				");
				if ($dbCover) {
					$startDisp = date('m/d/Y', strtotime($startDate));
					if ($startTime != '') { $startDisp .= ' at ' . date('g:i A', strtotime($startTime)); }
					$endDisp = ($endDate != '0000-00-00' && $endDate != '') ? date('m/d/Y', strtotime($endDate)) : 'open-ended';
					if ($endDate != '0000-00-00' && $endDate != '' && $endTime != '') { $endDisp .= ' at ' . date('g:i A', strtotime($endTime)); }

					$psword   = 'J8775bcgEE2065';
					$keyword4 = 'PN18337370906';
					$slooce   = 'jobalarm45';
					$now      = time();
					$covMob   = $dbCover[0]['mobile'];
					$assignMsg = 'You have been designated as coverage for ' . $origLast . ', ' . $origFirst . ' from ' . $startDisp . ' to ' . $endDisp . '.';
					$assignXml = '<message id="' . $covMob . $now . '"><partnerpassword>' . $psword . '</partnerpassword><content><![CDATA[' . $assignMsg . ']]></content></message>';
					$url = 'https://jobalarm.cloud.sloocetech.net/slooce_apps/spi/jobalarm45/1' . $covMob . '/' . $keyword4 . '/messages/startmt';
					$header = array('Content-Type: application/xml');
					curl_request($slooce, $url, $header, $assignXml);
				}
			} elseif ($coverUserId == 0 && $prevCoverId > 0) {
				// Coverage removed — send SMS to previous covering user
				$dbPrev = Config::get('db')->get_results("
					SELECT mobile FROM `productiveUser` WHERE id = {$prevCoverId} AND LENGTH(mobile)=10 LIMIT 1
				");
				if ($dbPrev) {
					$psword   = 'J8775bcgEE2065';
					$keyword4 = 'PN18337370906';
					$slooce   = 'jobalarm45';
					$now      = time();
					$covMob   = $dbPrev[0]['mobile'];
					$endMsg   = 'Your coverage assignment for ' . $origLast . ', ' . $origFirst . ' has ended. No further alerts will be routed to you.';
					$endXml   = '<message id="' . $covMob . $now . '"><partnerpassword>' . $psword . '</partnerpassword><content><![CDATA[' . $endMsg . ']]></content></message>';
					$url = 'https://jobalarm.cloud.sloocetech.net/slooce_apps/spi/jobalarm45/1' . $covMob . '/' . $keyword4 . '/messages/startmt';
					$header = array('Content-Type: application/xml');
					curl_request($slooce, $url, $header, $endXml);
				}
			} elseif ($coverUserId > 0 && $coverUserId === $prevCoverId) {
				// Same person resaved — if end date/time already past, send expiry SMS and clear
				$todayNow = date('Y-m-d');
				$timeNow  = date('H:i:s');
				$alreadyExpired = false;
				if ($endDate != '' && $endDate != '0000-00-00') {
					if ($endDate < $todayNow) {
						$alreadyExpired = true;
					} elseif ($endDate == $todayNow && $endTime != '' && $timeNow > $endTime) {
						$alreadyExpired = true;
					}
				}
				if ($alreadyExpired) {
					Config::get('db')->update('productiveUser',
						array('coverUserId'=>0,'coverStartDate'=>'0000-00-00','coverStartTime'=>'','coverEndDate'=>'0000-00-00','coverEndTime'=>'','coverPauseSms'=>0,'coverVarianceMin'=>1,'coverQblMin'=>300),
						array('id'=>$targetUserId)
					);
					$dbExp = Config::get('db')->get_results("SELECT mobile FROM `productiveUser` WHERE id = {$coverUserId} AND LENGTH(mobile)=10 LIMIT 1");
					if ($dbExp) {
						$psword   = 'J8775bcgEE2065';
						$keyword4 = 'PN18337370906';
						$slooce   = 'jobalarm45';
						$now      = time();
						$covMob   = $dbExp[0]['mobile'];
						$endMsg   = 'Your coverage assignment for ' . $origLast . ', ' . $origFirst . ' has ended. No further alerts will be routed to you.';
						$endXml   = '<message id="' . $covMob . $now . '"><partnerpassword>' . $psword . '</partnerpassword><content><![CDATA[' . $endMsg . ']]></content></message>';
						$url      = 'https://jobalarm.cloud.sloocetech.net/slooce_apps/spi/jobalarm45/1' . $covMob . '/' . $keyword4 . '/messages/startmt';
						curl_request($slooce, $url, array('Content-Type: application/xml'), $endXml);
					}
				}
			}
		}

		echo json_encode(array('success' => true, 'message' => 'Coverage updated'));
		return true;
	}
	echo json_encode(array('success' => false, 'message' => 'Invalid user'));
	return false;
}


/////////////////////////////////////
// CHECK IF USER HAS ACTIVE COVERAGE
function checkUserCoverage($userId) {
	$today = date('Y-m-d');
	$nowTime = date('H:i:s');
	$result = Config::get('db')->get_results("
		SELECT u.coverUserId, u.coverPauseSms, u.coverVarianceMin, u.coverQblMin,
		       u.coverStartDate, u.coverStartTime, u.coverEndDate, u.coverEndTime,
		       u.first_name_E, u.last_name_E,
		       IFNULL(cu.mobile,'') as mobile, cu.id as coverUserDbId
		FROM `productiveUser` u
		LEFT JOIN `productiveUser` cu ON cu.id = u.coverUserId
		WHERE u.id = {$userId}
		AND u.coverUserId > 0
		LIMIT 1
	");
	if (!$result) return false;
	$r = $result[0];
	// Check if coverage has started yet
	if ($r['coverStartDate'] != '' && $r['coverStartDate'] != '0000-00-00') {
		if ($r['coverStartDate'] > $today) return false;
		if ($r['coverStartDate'] == $today && $r['coverStartTime'] != '' && $r['coverStartTime'] != '00:00:00') {
			if ($nowTime < $r['coverStartTime']) return false;
		}
	}
	// Check if coverage has expired (date or time-based) — send SMS and clear
	$expired = false;
	$endDate = $r['coverEndDate'];
	$endTime = $r['coverEndTime'];
	if ($endDate != '' && $endDate != '0000-00-00') {
		if ($endDate < $today) {
			$expired = true;
		} elseif ($endDate == $today && $endTime != '' && $endTime != '00:00:00' && $nowTime > $endTime) {
			$expired = true;
		}
	}
	if ($expired) {
		// Clear coverage record
		Config::get('db')->update('productiveUser',
			array('coverUserId'=>0,'coverStartDate'=>'0000-00-00','coverStartTime'=>'','coverEndDate'=>'0000-00-00','coverEndTime'=>'','coverPauseSms'=>0,'coverVarianceMin'=>1,'coverQblMin'=>300),
			array('id'=>$userId)
		);
		// Send expiry SMS to covering user
		if (strlen($r['mobile']) == 10) {
			$ciphering = 'AES-128-CTR';
			$origFirst = openssl_decrypt($r['first_name_E'], $ciphering, ENCRYPT_KEY, 0, ENCRYPT_IV);
			$origLast  = openssl_decrypt($r['last_name_E'],  $ciphering, ENCRYPT_KEY, 0, ENCRYPT_IV);
			$psword    = 'J8775bcgEE2065';
			$keyword4  = 'PN18337370906';
			$slooce    = 'jobalarm45';
			$now       = time();
			$covMob    = $r['mobile'];
			$expMsg    = 'Your coverage assignment for ' . $origLast . ', ' . $origFirst . ' has ended. No further alerts will be routed to you.';
			$expXml    = '<message id="' . $covMob . $now . '"><partnerpassword>' . $psword . '</partnerpassword><content><![CDATA[' . $expMsg . ']]></content></message>';
			$url       = 'https://jobalarm.cloud.sloocetech.net/slooce_apps/spi/jobalarm45/1' . $covMob . '/' . $keyword4 . '/messages/startmt';
			curl_request($slooce, $url, array('Content-Type: application/xml'), $expXml);
		}
		return false;
	}
	return array(
		'userId'          => intval($r['coverUserDbId']),
		'mobile'          => $r['mobile'],
		'hasMobile'       => (strlen($r['mobile']) == 10),
		'coverPauseSms'   => intval($r['coverPauseSms']),
		'coverVarianceMin' => intval($r['coverVarianceMin']),
		'coverQblMin'     => intval($r['coverQblMin'])
	);
}


/////////////////////////////////////
// GET DETAILS FOR SINGLE USER
function GetUnitDetails() {
    $deptId = (isset($_REQUEST['deptId'])) ? $_REQUEST['deptId'] : '';
	$accountId = Config::get('account')['accountId'];
	$role = Config::get('account')['role'];
	$enterpriseId = Config::get('account')['enterpriseId'];
	
        $dbUnits = Config::get('db')->get_results("select d.*, m.prodTitle, IFNULL((SELECT `shift` FROM `productiveAccountXref` where `dept`=d.id ORDER BY `shift` DESC LIMIT 0,1),0) as censusShift, (CASE WHEN d.averageAcuity>0 THEN d.averageAcuity ELSE ROUND((((d.oneto1 * d.oneto1Acuity) + (d.oneto2 * d.oneto2Acuity) + (d.oneto3 * d.oneto3Acuity) + (d.oneto4 * d.oneto4Acuity) + (d.oneto5 * d.oneto5Acuity) + (d.oneto6 * d.oneto6Acuity)) / (d.oneto1 + d.oneto2 + d.oneto3 + d.oneto4 + d.oneto5 + d.oneto6)),1) END) as avgAcuity, a.transferPortal, a.eas, a.easTerm, a.updateLevel, IFNULL((SELECT GROUP_CONCAT(`shift` ORDER BY `shift` DESC SEPARATOR ',') from `productiveAccountXref` where `dept`=d.id),0) as Shifts, d.dept as unitName, IFNULL(u.last_name,'') as last_name, IFNULL(u.id,0) as userId, IFNULL(u.first_name,'') as first_name, (SELECT `id` FROM `productiveStaffingGrid` WHERE `griddeptId`= d.id LIMIT 0,1) as hasGrid from `ProductiveDept` d LEFT OUTER JOIN `productiveDeptXref` as x on x.deptId = d.id and x.primaryUnit=6 and x.unitAssigned=0 LEFT OUTER JOIN `productiveUser` as u on u.id=x.userId LEFT JOIN `productiveMeasures` as m on m.id=d.prodMeasure LEFT JOIN `productiveAccount` as a on a.id=d.accountId where d.id={$deptId} GROUP BY d.id");
        $dbData = $dbUnits[0];
		$dbDir = Config::get('db')->get_results("select d.*, d.dept as unitName, m.prodDesc, IFNULL(u.last_name,'') as last_name2, IFNULL(u.id,0) as userId2, IFNULL(u.first_name,'') as first_name2 from `ProductiveDept` d LEFT OUTER JOIN `productiveDeptXref` as x on x.deptId = d.id and x.primaryUnit=7 and x.unitAssigned=0 LEFT OUTER JOIN `productiveUser` as u on u.id=x.userId LEFT JOIN `productiveMeasures` as m on m.id=d.prodMeasure1 where d.id={$deptId} GROUP BY d.id");
        
		if($dbDir){
		$dbdirector = $dbDir[0];
		}else{
		$dbDirector = '';
		}
		
		if($dbUnits && intval($dbData['censusShift'])==0){
		$shifts = array();
		$linklist = array();
		$shiftname = '';
		}else{
		$shifts = array();
		$shifts = explode (",", $dbData['Shifts']); 
		$dbshifts = Config::get('db')->get_results("select * FROM `productiveAccountXref` WHERE `dept`={$deptId} GROUP BY `shift` ORDER by `shift` DESC");
        $shiftname = "";
		$shiftname .= "";
		$linklist = array();
		foreach($dbshifts as $shiftn){
		$shiftname .= $shiftn['shiftName'] . '</br>';
		}
		}		
		
		if($dbUnits && intval($dbUnits[0]['prodMeasure'])==2){
		$dbLinked = Config::get('db')->get_results("SELECT d.* FROM `ProductiveDept` d WHERE d.unitCategory !=4 AND d.accountId ={$accountId} and d.prodMeasure=2 AND d.active>0 group by d.id order by d.dept ASC");
		}else{
		$dbLinked = Config::get('db')->get_results("SELECT d.* FROM `ProductiveDept` d WHERE d.unitCategory !=4 AND d.accountId ={$accountId} and d.prodMeasure!=2 AND d.active>0 group by d.id order by d.dept ASC");	
		}
		foreach($dbLinked as $link){
		$linklist[] = array(
		'deptId' => $link['id'],
		'deptName' => $link['dept']
		);	
		}
        
		if($dbUnits){
		//echo json_encode(array('success' => true,'data'=>$dbData,'data2'=>$dbdirector,'shifts'=>$shifts,'shiftnames'=>$shiftname,'linklist'=>$linklist,'role'=>$role));
		echo json_encode(array('success' => true,'data'=>$dbData,'data2'=>$dbdirector,'shifts'=>$shifts,'shiftnames'=>$shiftname,'linklist'=>$linklist,'role'=>$role));
        return true;
		}else{
	    echo json_encode(array('success'=>true,'message'=>'Unit not found.'));
		return false;
		}
}


/////////////////////////////////////
// RUN UNIT CHECK
function UnitCheck() {
	$accountId = Config::get('account')['accountId'];
	//$role = Config::get('account')['role'];
	
        $dbUsers = Config::get('db')->get_results("select * FROM `productiveUser` where `accountId`={$accountId} AND `active`=1 AND `role`>8 AND `role`<13 GROUP BY `id`");
		$dbXref = Config::get('db')->get_results("select x.*, d.active FROM `productiveDeptXref` x LEFT JOIN `ProductiveDept` as d on d.id=x.deptId where d.accountId={$accountId} AND x.primaryUnit<11 AND d.active=0 GROUP BY x.id");
		if($dbUsers){
		foreach($dbUsers as $check){
		$checkId = $check['id'];
		$checkRole = $check['role'];
		$dbUnits = Config::get('db')->get_results("select d.*, x.deptId FROM `ProductiveDept` d LEFT OUTER JOIN `productiveDeptXref` as x on x.deptId=d.id where d.accountId={$accountId} AND (d.active=1 OR d.active=2) AND d.id NOT IN (SELECT `deptId` FROM `productiveDeptXref` WHERE `userId`={$checkId}) GROUP BY d.id");
		if($dbUnits){
		foreach($dbUnits as $ucheck){
		$newXref = array(
				'primaryUnit'=>$checkRole,
				'deptId'=>$ucheck['id'],
				'userId'=>$checkId
            );
				
            $newDept = Config::get('db')->insert('productiveDeptXref',$newXref);			
		
		}
		}
		}
	}
	if($dbXref){
		foreach($dbXref as $xcheck){
		$xcheckId = $xcheck['id'];
		
		$Xref = array(
				'id'=>$xcheckId
            );
				
            $newXref = Config::get('db')->delete('productiveDeptXref',$Xref);			
		
		}
	}

	echo json_encode(array('success'=>true,'message'=>'Updated'));
	return true;
		
}

// GET DETAILS FOR SINGLE USER
function GetAccountDetails() {
    $account = (isset($_REQUEST['accountId'])) ? $_REQUEST['accountId'] : 0;
	if(intval($account)==0){
	$accountId = Config::get('account')['accountId'];
	}else{
	$accountId = $account;	
	}
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
	$prodMeasure = (isset($_REQUEST['prodMeasure'])) ? $_REQUEST['prodMeasure'] : 0;
	//$prodValue = (isset($_REQUEST['unitprodValue'])) ? $_REQUEST['unitprodValue'] : 0;
	$unitCategory = (isset($_REQUEST['unitCategory'])) ? $_REQUEST['unitCategory'] : 0;
	//$uosDesc = (isset($_REQUEST['uosDesc'])) ? $_REQUEST['uosDesc'] : '';
	//$target = (isset($_REQUEST['unitTarget'])) ? $_REQUEST['unitTarget'] : 100;
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
		$rnThreshold =2;
		$inshiftProd =0;
		$charge =0.0;
		$shiftsperDay = 0;
		$showVisits = 0;
		$uosDesc = "Units of Service";
		$resourcesTitle = "Staff Hours";
		$skill1 = 1;
		$rnVariance = 1;
		$showProd = 3;
		$showrntest = "[\"0\"]";
		$prodMeasure1 = 2;
	}else if(intval($prodMeasure)==3){
		$viewPage ="w.php?i=";
		$censusShift =0;
		$rnThreshold =2;
		$inshiftProd =0;
		$charge =0.0;
		$shiftsperDay = 0;
		$showVisits = 0;
		$uosDesc = "Units of Service";
		$resourcesTitle = "Staff Hours";
		$skill1 = 1;
		$rnVariance = 1;
		$showProd = 3;
		$showrntest = "[\"0\"]";
		$prodMeasure1 = 3;
	}else if(intval($prodMeasure)==4){
		$viewPage ="v.php?i=";
		$censusShift =52;
		$rnThreshold =2;
		$inshiftProd =1;
		$charge =1.0;
		$shiftsperDay = 0;
		$showVisits = 1;
		$uosDesc = "Visits";
		$resourcesTitle = "Resources Currently in Staffing";
		$skill1 = 0;
		$rnVariance = 0;
		$showProd = 1;
		$showrntest = "[\"1\"]";
		$prodMeasure1 = 4;
	}else{
		$viewPage ="v.php?i=";
		$censusShift =52;
		$rnThreshold =2;
		$inshiftProd =1;
		$charge =1.0;
		$shiftsperDay = 0;
		$showVisits = 0;
		$uosDesc = "HPPD";
		$resourcesTitle = "Resources Currently in Staffing";
		$skill1 = 0;
		$rnVariance = 0;
		$showProd = 1;
		$showrntest = "[\"1\"]";
		$prodMeasure1 = 1;
	}
	
    $nameSafe   = Config::get('db')->filter($name);
    $numberSafe = Config::get('db')->filter($number);
    $dbUnit = Config::get('db')->get_results("select * FROM `ProductiveDept` WHERE `accountId`={$accountId} and `unitId`='{$numberSafe}'");
	
	if (!$dbUnit) {
			
            $data = array(
				'dept'=>$name,
                'unitId'=>$number,
				'prodMeasure'=>$prodMeasure,
				'prodMeasure1'=>$prodMeasure1,
				'uosDesc'=>$uosDesc,
				'viewPage'=>$viewPage,
				'totalBeds'=>$beds,
				'accountId'=>$accountId,
				'shift'=>$censusShift,
                'active'=>3,
				'inshiftProd'=>$inshiftProd,
				'rnThreshold'=>$rnThreshold,
				'charge'=>$charge,
				'createdBy'=>$userId,
				'unitCategory'=>$unitCategory,
				'deptType'=>$unitCategory,
				'showVisits'=>$showVisits,
				'rnVariance'=>$rnVariance,
				'showProdWHP'=>$showProd,
				'uosDesc'=>$uosDesc,
				'skill1'=>$skill1,
				'showrntest'=>$showrntest,
				'resourcesTitle'=>$resourcesTitle
            );

            Config::get('db')->insert('ProductiveDept',$data);
		
			$dbNew = Config::get('db')->get_results("select * FROM `ProductiveDept` WHERE `dept`='{$nameSafe}' and `accountId`={$accountId} and `unitId`='{$numberSafe}'");
            $newId = $dbNew[0]['id'];
		
			if(intval($director)>0 && intval($newId)>0){
			$primary ="7";
			$dataNewDir = array(
				'primaryUnit'=>7,
				'deptId'=>$newId,
				'userId'=>$director,
				'accountId'=>$accountId
            );
				
            Config::get('db')->insert('productiveDeptXref',$dataNewDir);
			}
			
			if(intval($manager)>0 && intval($newId)>0){
			$primary ="6";
			$dataNewMgr = array(
				'primaryUnit'=>6,
				'deptId'=>$newId,
				'userId'=>$manager,
				'accountId'=>$accountId
            );
				
            Config::get('db')->insert('productiveDeptXref',$dataNewMgr);
			}	
		
			if(intval($prodMeasure)==2 || intval($prodMeasure)==3){
			$dbXref = Config::get('db')->get_results("select * FROM `productiveShifts` WHERE `shiftsperDay`=1 ORDER BY `id` DESC");
			}else{
			$dbXref = Config::get('db')->get_results("select * FROM `productiveShifts` WHERE `shiftsperDay`=6 ORDER BY `id` DESC");	
			}
			$s=0;
			if(intval($unitCategory)!=4 && $dbXref){
			foreach ($dbXref as $NewXref){
				$shift = $NewXref['id'];
				$whpPlan=0;
				$name = $NewXref['shift'];
				
				if(intval($prodMeasure)!=2){
				$s=$s+1;
				}else{
				$s=0;	
				}
			
			$dataNewXref = array(
				'accountId'=>$accountId,
				'dept'=>$newId,
				'shift'=>$shift,
				'value'=>0,
				'shifthrs'=>0,
				'whpPlan'=>$whpPlan,
				'shiftName'=>$name,
				'active'=>1,
				'shiftOrder'=>$s
            );
				
            Config::get('db')->insert('productiveAccountXref',$dataNewXref);
			
			
			$dataNewData = array(
				'accountId'=>$accountId,
				'deptId'=>$newId,
				'shift'=>$shift,
				'whpPlan'=>0,
				'type'=>1,
				'dayDate'=>$currentDate,
				'chargecount'=>$charge,
				'shiftOrder'=>$s
            );
				
            Config::get('db')->insert('productiveNewData',$dataNewData);
			
			}
			
			}
			
			for($i=1;$i<7;$i++)	{			
			$date = strtotime($currentDate . " +" . $i . " day");
			//$date = strtotime($currentDate);
			$newDate = date('Y-m-d',$date);
			$dayofweek = date('w', $date);
			$weekDay = intval($dayofweek);
			
			if(($prodMeasure==2 || $prodMeasure==3) && $opDays==1){
			
			$dataNew = array(
				'accountId'=>$accountId,
				'deptId'=>$newId,
				'shift'=>$shift,
				'whpPlan'=>0,
				'type'=>1,
				'dayDate'=>$newDate
            );
				
            Config::get('db')->insert('productiveNewData',$dataNew);
			
			}else if(($prodMeasure==2 || $prodMeasure==3) && $opDays==2 && $weekDay !=6){
			
			$dataNew = array(
				'accountId'=>$accountId,
				'deptId'=>$newId,
				'shift'=>$shift,
				'whpPlan'=>0,
				'type'=>1,
				'dayDate'=>$newDate
            );
				
            Config::get('db')->insert('productiveNewData',$dataNew);
			}else if(($prodMeasure==2 || $prodMeasure==3) && $opDays==3 && $weekDay !=0 && $weekDay !=6){
			
			$dataNew = array(
				'accountId'=>$accountId,
				'deptId'=>$newId,
				'shift'=>$shift,
				'whpPlan'=>0,
				'type'=>1,
				'dayDate'=>$newDate
            );
				
            Config::get('db')->insert('productiveNewData',$dataNew);
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
    $accountId = intval(Config::get('account')['accountId']);

	if ($userId > 0) {
		$check = Config::get('db')->get_results("SELECT id FROM productiveUser WHERE id={$userId} AND accountId={$accountId}");
        if (!$check) { echo json_encode(array('success'=>false,'message'=>'Not authorized')); return false; }
		$data = array(
                'txt'=>0,
				'txtactive'=>0,
				'active'=>0
            );
        $where = array('id'=>$userId, 'accountId'=>$accountId);
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
        $deptCheck = Config::get('db')->get_results("SELECT id FROM ProductiveDept WHERE id={$deptId} AND accountId={$accountId}");
        if (!$deptCheck) { echo json_encode(array('success'=>false,'message'=>'Not authorized')); return false; }
		$whereRemove = array(
				'dept'=> $deptId,
				'accountId'=> $accountId
				);
		Config::get('db')->delete('productiveAccountXref',$whereRemove);
	$i = 0;
	foreach($shifts as $shift){
		$i=$i+1;
		$shiftName = Config::get('db')->get_results("SELECT * from `productiveShifts` WHERE `id`={$shift}");
				
		$data = array(
            'accountId' => $accountId,
            'dept' => $deptId,
			'shiftName' => $shiftName[0]['shift'],
			'shift' => $shift,
			'active' =>1,
			'shiftOrder' =>$i
        );
		
        Config::get('db')->insert('productiveAccountXref',$data);
	}
	
	$dbshifts = Config::get('db')->get_results("select * FROM `productiveAccountXref` WHERE `dept`={$deptId} GROUP BY `shift` ORDER by `shift` DESC");
		$census = array(
            'shift' => $dbshifts[0]['shift']
        );
		$where = array(
            'id' => $deptId,
            'accountId' => $accountId
        );
		Config::get('db')->update('ProductiveDept',$census,$where);

		$shiftname = "";
		$shiftname .="";
		foreach($dbshifts as $shiftn){
		$shiftname .= $shiftn['shiftName'] . '</br>';
		}
	echo json_encode(array('success'=>true,'shiftnames'=>$shiftname));
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
	$accountId = Config::get('account')['accountId'];
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
			'hppd' => $value,
			'shift' => $censusShift
        );
		}
        $where = array('id'=>$unitId, 'accountId'=>$accountId);
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
		$serviceNameSafe = Config::get('db')->filter($serviceName);
		$svcDb = Config::get('db')->get_results("SELECT * from `productiveCategory` WHERE (`accountId`={$accountId} OR `accountId`=0) AND `categoryName`='{$serviceNameSafe}'");

		if(!$svcDb){
		$data = array(
            'categoryName' => $serviceName,
            'accountId' => $accountId
        );
        Config::get('db')->insert('productiveCategory',$data);

		$newSvc = Config::get('db')->get_results("SELECT * from `productiveCategory` WHERE `accountId`={$accountId} AND `categoryName`='{$serviceNameSafe}'");
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

function updateepicData() {
   
		$svcDb = Config::get('db')->get_results("SELECT n.id as dataId, n.shift, n.dayDate, n.accountId, n.deptId, n.shiftOrder, n.epicScore, a.shiftOrder as aShift, a.epicScore as aEpic, b.epicScore as bEpic, b.shiftOrder as bShift, b.dayDate as newDate from `productiveNewData` n LEFT JOIN `productiveNewData` as a on a.deptId=n.deptId and a.dayDate=n.dayDate and a.shiftOrder=(n.shiftOrder+1) LEFT JOIN `productiveNewData` as b on b.deptId=n.deptId and b.dayDate=DATE_SUB(n.dayDate,INTERVAL 1 DAY) and b.shiftOrder=1 WHERE n.accountId=20 AND n.shiftOrder>0 and n.dayDate>'2022-06-30' group by n.id order by n.dayDate ASC, n.deptId ASC, n.shift ASC");
		
		foreach($svcDb as $ndata){
		
		if(intval($ndata['shiftOrder'])==6){
		$newEpic = $ndata['bEpic'];
		}else{
		$newEpic = $ndata['aEpic'];
		}
		
		$data = array(
            'epicScore' => $newEpic
        );
		
		$datawhere = array(
            'id' => $ndata['dataId']
        );
        Config::get('db')->update('productiveNewData',$data,$datawhere);
		
	}
	  echo json_encode(array('success'=>true));
       return true;

}

/////////////////////////////////////
// UPDATE DATA2

function UpdateData() {
	$dept = (isset($_REQUEST['deptId']) ? $_REQUEST['deptId']:'');
	$startDate = (isset($_REQUEST['startDate']) ? $_REQUEST['startDate']:'');
	$endDate = (isset($_REQUEST['endDate']) ? $_REQUEST['endDate']:'');
	$hppd = (isset($_REQUEST['uosValue']) ? floatval($_REQUEST['uosValue']):'');
		
	//$dbData1 = Config::get('db')->get_results("select n.*, (select count(`dept`) from `productiveAccountXref` where `type`<2 AND `whpPlan` !=1 and `dept`=n.deptId) as shiftCount, s.value, s.shiftOrder, h.useFlow, h.averageAcuity, h.avgWage, h.minRN, h.showEpic, h.ptTotal2, h.ptTotal3, h.ptTotal4, h.desc7, h.desc8, h.desc9, h.actionPlan, h.desc10, h.countcharge, h.useGrid, h.typetrack1, h.typetrack2, h.typetrack3, h.typetrack4, h.rnCount, h.rn1Count, h.rn2Count, h.roundrn, h.oneto1Acuity, h.oneto2Acuity, h.oneto3Acuity, h.oneto4Acuity, h.oneto5Acuity, h.oneto6Acuity, h.hppd, h.escalations, h.trackdc, h.trackdata, h.prodMeasure, h.thresholdHigh, h.thresholdLow, h.rnThreshold, h.track1Desc, h.track2Desc, h.track3Desc, h.track4Desc, h.adjustment, a.displayProd, a.productivityPosNeg, h.viewPage, h.inshiftProd, h.los, h.dept, h.rnVariance, h.churn, h.shiftsperDay, h.nurse1Variance, h.shift as censusShift from `productiveNewData` n left join `ProductiveDept` as h on h.id=n.deptId left join `productiveAccount` as a on a.id = h.accountId left join `productiveShifts` as t on t.id = n.shift left join `productiveAccountXref` as s on s.dept = n.deptId and s.shift = n.shift LEFT OUTER JOIN `productiveDeptXref` as x on x.deptId=n.deptId where n.deptId={$dept} AND n.dayDate >='{$startDate}' AND n.dayDate<='{$endDate}' AND n.userId>0 AND n.type<2 group by n.id");
	$dbData1 = Config::get('db')->get_results("select n.*, (CASE WHEN (n.userId=0 AND n.dayDate>=DATE_FORMAT(CURDATE(),'%Y-%m-%d')) OR (n.dayDate>DATE_FORMAT(CURDATE(),'%Y-%m-%d')) THEN 1 ELSE 0 END) as plannedRecord, g.griddeptId, g.census, IFNULL(g.cn,0) AS cn, IFNULL(g.rn,0) AS rn, IFNULL(g.rn1,0) AS rn1, IFNULL(g.rn2,0) AS rn2, IFNULL(g.pct,0) AS pct, IFNULL(g.sitter,0) AS sitter, IFNULL(g.sec,0) as sec, IFNULL(g.other1,0) AS other1, IFNULL(g.other2,0) AS other2, IFNULL(g.other3,0) AS other3, g.dayNight, g.dow, (select count(`dept`) from `productiveAccountXref` where `type`<2 AND `whpPlan` !=1 and `dept`=n.deptId) as shiftCount, a.payPeriod, a.displayProd, a.payPeriodFirst, a.productivityPosNeg, h.typetrack1, h.typetrack2, h.typetrack3, h.typetrack4, h.hppd, h.rnVariance, h.indMeasure, h.useGrid, h.rnThreshold, h.thresholdHigh, h.thresholdLow, h.adjustment, h.avgWage, h.trackdata, h.churn, h.uosValue, h.budgetMeasure, h.escalations, h.prodMeasure1, h.viewPage, h.dept, h.skill1calc, h.skill2calc, h.skill3calc, h.skill4calc, h.skill5calc, h.skill6calc, h.skill7calc, h.skill8calc, h.skill9calc, h.skill10calc, h.skill11calc, h.skillbudget1, h.customWHP, h.customWHP2, h.customWHP3, h.customWHP4, h.customWHP5, h.customWHPvalue, h.customWHP2value, h.customWHP3value, h.customWHP4value, h.customWHP5value, h.skillbudget2, h.skillbudget3, h.skillbudget4, h.skillbudget5, h.shift as censusShift, s.shifthrs from `productiveNewData` n left join `ProductiveDept` as h on h.id=n.deptId left join `productiveAccount` as a on a.id = h.accountId left outer join `productiveStaffingGrid` as g on g.griddeptId=n.deptId AND (g.dayNight=n.shift OR g.dayNight=((n.shift)-1)) AND g.dow=DAYOFWEEK(n.dayDate) LEFT JOIN `productiveAccountXref` as s on s.dept = n.deptId and s.shift=n.shift where n.deptId={$dept} AND n.dayDate >='{$startDate}' AND n.dayDate<='{$endDate}' AND n.userId>0 AND n.type<2 group by n.id");
	
	if($dbData1){
	foreach($dbData1 as $dbData){
		$dataOrig = array(
				'dataId' =>$dbData['id'],
				'accountId' =>$dbData['accountId'],
				'userId' =>$dbData['userId'],
				'deptId' =>$dbData['deptId'],
				'entered' =>$dbData['entered'],
				'dayDate' =>$dbData['dayDate'],
				'shift' =>$dbData['shift'],
				'chargecount' =>$dbData['chargecount'],
                'techcount' =>$dbData['techcount'],
				'tech1count' =>$dbData['tech1count'],
				'aptechcount' =>$dbData['aptechcount'],
				'seccount' =>$dbData['seccount'],
				'sittercount' =>$dbData['sittercount'],
				'antecount' =>$dbData['antecount'],
				'ldcount' =>$dbData['ldcount'],
				'ocount' =>$dbData['ocount'],
				'acs' =>$dbData['acs'],
				'am1' =>$dbData['am1'],
                'awcm' =>$dbData['awcm'],
				'obed' =>$dbData['obed'],
				'obed1' =>$dbData['obed1'],
                'ev' =>$dbData['ev'],
				'scs' =>$dbData['scs'],
				'cr' =>$dbData['cr'],
				'pt' =>$dbData['pt'],
                'ccs' =>$dbData['ccs'],
				'ps1' =>$dbData['ps1'],
				'pp' =>$dbData['pp'],
				'avariance' =>$dbData['avariance'],
				'lvariance' =>$dbData['lvariance'],
				'nvariance' =>$dbData['nvariance'],
				'gridrnvariance' =>$dbData['gridrnvariance'],
				'gvariance' =>$dbData['gvariance'],
				'note' =>addslashes($dbData['note']),
				'atotal' =>$dbData['atotal'],
				'patientCount2' =>$dbData['patientCount2'],
				'patientCount3' =>$dbData['patientCount3'],
				'patientCount4' =>$dbData['patientCount4'],
				'ltotal' =>$dbData['ltotal'],
				'aproductivity' =>$dbData['aproductivity'],
				'lproductivity' =>$dbData['lproductivity'],
				'nproductivity' =>$dbData['nproductivity'],
				'gridrnproductivity' =>$dbData['gridrnproductivity'],
				'customProd' =>$dbData['customProd'],
				'oneto1' =>$dbData['oneto1'],
				'oneto2' =>$dbData['oneto2'],
				'oneto3' =>$dbData['oneto3'],
				'oneto4' =>$dbData['oneto4'],
				'oneto5' =>$dbData['oneto5'],
				'oneto6' =>$dbData['oneto6'],
				'oneto7' =>$dbData['oneto7'],
				'whpPlan' =>$dbData['whpPlan'],
				'skill1val' =>$dbData['skill1val'],
				'skill2val' =>$dbData['skill2val'],
				'skill3val' =>$dbData['skill3val'],
				'skill4val' =>$dbData['skill4val'],
				'skill5val' =>$dbData['skill5val'],
				'skill6val' =>$dbData['skill6val'],
				'skill7val' =>$dbData['skill7val'],
				'skill8val' =>$dbData['skill8val'],
				'skill9val' =>$dbData['skill9val'],
				'skill10val' =>$dbData['skill10val'],
				'skill11val' =>$dbData['skill11val'],
				'addResourceHrs' =>$dbData['addResourceHrs'],
				'twoto1' =>$dbData['twoto1'],
				'procedureCount' =>$dbData['procedureCount'],
				'actualWHP' =>$dbData['actualWHP'],
				'actualWHPimport' =>$dbData['actualWHPimport'],
				'uosph' =>$dbData['uosph'],
				'plannedHours' =>$dbData['plannedHours'],
				'plannedProcs' =>$dbData['plannedProcs'],
				'admits' =>$dbData['admits'],
				'transfers' =>$dbData['transfers'],
				'discharges' =>$dbData['discharges'],
				'customChurn' =>$dbData['customChurn'],
				'budgetValue' =>$dbData['budgetValue'],
				'churnValue' =>$dbData['churnValue'],
				'visits' =>$dbData['visits'],
				'totalHours' =>$dbData['totalHours'],
				'customNurse' =>$dbData['customNurse'],
				'customNurse2' =>$dbData['customNurse2'],
				'otherNurse2' =>$dbData['otherNurse2'],
				'otherNurse1' =>$dbData['otherNurse1'],
				'otherNurse3' =>$dbData['otherNurse3'],
				'oneto8' =>$dbData['oneto8'],
				'oneto9' =>$dbData['oneto9'],
				'oneto10' =>$dbData['oneto10'],
				'oneto11' =>$dbData['oneto11'],
				'oneto12' =>$dbData['oneto12'],
				'epicScore' =>$dbData['epicScore'],
				'hrsVariance' =>$dbData['hrsVariance'],
				'ghrsVariance' =>$dbData['ghrsVariance'],
				'ahrsVariance' =>$dbData['ahrsVariance'],
				'rhrsVariance' =>$dbData['rhrsVariance'],
				'gridrnhrsVariance' =>$dbData['gridrnhrsVariance'],
				'hrsVarianceActual' =>$dbData['hrsVarianceActual'],
				'activeRecord' =>$dbData['activeRecord'],
				'censusTotal' =>$dbData['censusTotal'],
				'planSubmitted' =>$dbData['planSubmitted'],
				'visitsSubmitted' =>$dbData['visitsSubmitted'],
				'hoursSubmitted' =>$dbData['hoursSubmitted'],
				'hrs1Submitted' =>$dbData['hrs1Submitted'],
				'hrs2Submitted' =>$dbData['hrs2Submitted'],
				'hrs3Submitted' =>$dbData['hrs3Submitted'],
				'whpCustom' =>$dbData['whpCustom'],
				'whpCustom2' =>$dbData['whpCustom2'],
				'whpCustom3' =>$dbData['whpCustom3'],
				'whpCustom4' =>$dbData['whpCustom4'],
				'whpCustom5' =>$dbData['whpCustom5'],
				'type' =>$dbData['type'],
				'IndUOS' =>$dbData['IndUOS'],
				'IndHRS' =>$dbData['IndHRS'],
				'indUserId' =>$dbData['indUserId'],
				'provId' =>$dbData['provId'],
				'budgetVar' =>$dbData['budgetVar'],
				'gbudgetVar' =>$dbData['gbudgetVar'],
				'rbudgetVar' =>$dbData['rbudgetVar'],
				'gridrnbudgetVar' =>$dbData['gridrnbudgetVar'],
				'track1' =>$dbData['track1'],
				'track2' =>$dbData['track2'],
				'track3' =>$dbData['track3'],
				'track4' =>$dbData['track4'],
				'track1Detail' =>$dbData['track1Detail'],
				'track2Detail' =>$dbData['track2Detail'],
				'track3Detail' =>$dbData['track3Detail'],
				'track4Detail' =>$dbData['track4Detail'],
				'notetrack1' =>$dbData['notetrack1'],
				'notetrack2' =>$dbData['notetrack2'],
				'notetrack3' =>$dbData['notetrack3'],
				'notetrack4' =>$dbData['notetrack4'],
				'countDischarges' =>$dbData['countDischarges'],
				'submittedby' =>$dbData['submittedby'],
				'submitCount' =>$dbData['submitCount'],
				'rnVariance1' =>$dbData['rnVariance1'],
				'hppd2' =>$dbData['hppd'],
				'rnAcuity' =>$dbData['rnAcuity'],
				'oneto1Count' =>$dbData['oneto1Count'],
				'oneto2Count' =>$dbData['oneto2Count'],
				'oneto3Count' =>$dbData['oneto3Count'],
				'oneto3p5Count' =>$dbData['oneto3p5Count'],
				'oneto4Count' =>$dbData['oneto4Count'],
				'oneto4p5Count' =>$dbData['oneto4p5Count'],
				'oneto5Count' =>$dbData['oneto5Count'],
				'oneto5p5Count' =>$dbData['oneto5p5Count'],
				'oneto6Count' =>$dbData['oneto6Count'],
				'twoto1Count' =>$dbData['twoto1Count'],
				'threeto1Count' =>$dbData['threeto1Count'],
				'epicOrig' =>$dbData['epicOrig'],
				'shiftOrder' =>$dbData['shiftOrder'],
				'grid1' => $dbData['grid1'],
				'grid2' => $dbData['grid2'],
				'grid3' => $dbData['grid3'],
				'grid4' => $dbData['grid4'],
				'grid5' => $dbData['grid5'],
				'grid6' => $dbData['grid6'],
				'grid7' => $dbData['grid7'],
				'grid8' => $dbData['grid8'],
				'grid9' => $dbData['grid9'],
				'grid10' => $dbData['grid10'],
				'gridvar1' => $dbData['gridvar1'],
				'gridvar2' => $dbData['gridvar2'],
				'gridvar3' => $dbData['gridvar3'],
				'gridvar4' => $dbData['gridvar4'],
				'gridvar5' => $dbData['gridvar5'],
				'gridvar6' => $dbData['gridvar6'],
				'gridvar7' => $dbData['gridvar7'],
				'gridvar8' => $dbData['gridvar8'],
				'gridvar9' => $dbData['gridvar9'],
				'gridvar10' => $dbData['gridvar10']			
				);

	$newRecord = Config::get('db')->insert('productiveNewDataOrig',$dataOrig);
	
		//$hppd = floatval($dbData['hppd']);
		$dataId = $dbData['id'];
		$procedureCount = floatval($dbData['procedureCount']);
		
		$rnVariance = intval($dbData['rnVariance']);
		$gvariance = floatval($dbData['gvariance']);
		$avgWage = floatval($dbData['avgWage']);
		$actualWHP = floatval($dbData['actualWHP']);
			
		$skillval1 = floatval($dbData['skill1val']);
		$skillval2 = floatval($dbData['skill2val']);
		$skillval3 = floatval($dbData['skill3val']);
		$skillval4 = floatval($dbData['skill4val']);
		$skillval5 = floatval($dbData['skill5val']);
		$skillval6 = floatval($dbData['skill6val']);
		$skillval7 = floatval($dbData['skill7val']);
		$skillval8 = floatval($dbData['skill8val']);
		$skillval9 = floatval($dbData['skill9val']);
		$skillval10 =floatval($dbData['skill10val']);
		//$skillval11 =floatval($dbData['skill11val']);
		$skillTotal = $skillval1 + $skillval2 + $skillval3 + $skillval4 + $skillval5 + $skillval6 + $skillval7 + $skillval8 + $skillval9 + $skillval10;
		
		$gridTotal = intval($dbData['cn'])+intval($dbData['rn'])+intval($dbData['rn1'])+intval($dbData['rn2'])+intval($dbData['pct'])+intval($dbData['sitter'])+intval($dbData['sec'])+intval($dbData['other1'])+intval($dbData['other2'])+intval($dbData['other3']);
					
		$skill1calc = floatval($dbData['skill1calc']);
		$skill2calc = floatval($dbData['skill2calc']);
		$skill3calc = floatval($dbData['skill3calc']);
		$skill4calc = floatval($dbData['skill4calc']);
		$skill5calc = floatval($dbData['skill5calc']);
		$skill6calc = floatval($dbData['skill6calc']);
		$skill7calc = floatval($dbData['skill7calc']);
		$skill8calc = floatval($dbData['skill8calc']);
		$skill9calc = floatval($dbData['skill9calc']);
		$skill10calc = floatval($dbData['skill10calc']);
		
		$nurseAdd = floatval($dbData['addResourceHrs']);
		$useGrid = $dbData['useGrid'];	
		$prodCalc = intval($dbData['productivityPosNeg']);
		
		$shiftCount = intval($dbData['shiftCount']);
		
		if($shiftCount<=0){
		$shiftCount =1;
		}
			
		$totHours = (($skillval1 * $skill1calc) + ($skillval2 * $skill2calc) + ($skillval3 * $skill3calc) + ($skillval4 * $skill4calc) + ($skillval5 * $skill5calc) + ($skillval6 * $skill6calc) + ($skillval7 * $skill7calc) + ($skillval8 * $skill8calc) + ($skillval9 * $skill9calc) + ($skillval10 * $skill10calc)) + $nurseAdd;
	
	if($totHours ==0){
		$totalHours=$hppd;
	}else{
		$totalHours=$totHours;
	}			
	
	if((intval($useGrid)==2 || intval($useGrid)==3) && $gridTotal>0 && $rnVariance==5){
				//$gridReqd = $gridTotal;
				//$gproductivity = (1-(($totalHours - $gridReqd) / $gridReqd)) * 100;
				//$ghrsVariance = 0;
				$actualHoursVariance = 0;		
	}else if(intval($useGrid)==2 && $gridTotal>0 && $rnVariance!=5){
				//$gridReqd = $gridTotal;
				//$gproductivity = (1-(($totalHours - $gridReqd) / $gridReqd)) * 100;
				//$ghrsVariance = $gvariance;
				$actualHoursVariance = round(($totalHours - ($hppd * $procedureCount)),2);
	}else if(intval($useGrid)==3 && $gridTotal>0 && $rnVariance!=5){
				//$gridReqd = $gridTotal;
				//$gproductivity = (1-(($totalHours - $gridReqd) / $gridReqd)) * 100;
				//$ghrsVariance = $gvariance;
				$actualHoursVariance = round($gvariance,2);
	}else{
				//$gridReqd = $gridTotal;
				//$gproductivity = 0.00;
				//$ghrsVariance = 0.0;
				$actualHoursVariance = round(($totalHours - ($hppd * $procedureCount)),2);
	}
	/*
	if($rnVariance==5){
		$actualWHP = 0;
	}else if($procedureCount >0 && $totalHours>0){
		$actualWHP = round($totalHours / $procedureCount,2);
	}else if($procedureCount==0 && $totalHours>0){
		$actualWHP = round($totalHours,2);	
	}else{
		$actualWHP = 0;
	}
	*/
	
	if($hppd >0){
	$aprod = (1-(($actualWHP - $hppd) / $hppd)) * 100;
	}else{
	$aprod =0;	
	}
	
	if($prodCalc==1){
	$aproductivity = round($aprod,1);
	$nvariance = $actualHoursVariance;
	$budgetVar = $nvariance * $avgWage * -1;
	}else{
	$aproductivity = round($aprod,1);
	$nvariance = $actualHoursVariance * -1;
	$budgetVar = $nvariance * $avgWage;	
	}
	
		    $data = array(
				'hrsVariance' => $nvariance,
				'ahrsVariance' => $nvariance,
				'nproductivity' => $aproductivity,
				'aproductivity' => $aproductivity,
				'budgetVar' => $budgetVar
            );
			$updateWhere = array('id' => $dataId);
            
			$newProd = Config::get('db')->update('productiveNewData',$data,$updateWhere);
			//$newProd = Config::get('db')->insert('productiveNewDataOrig',$data);
			
			
	}
	echo json_encode(array('success'=>true,'message'=>true));
	return true;
	}else{
	echo json_encode(array('success'=>false));
	return true;
	}

}

/////////////////////////////////////
// UPDATE DATA

function UpdateData1() {
	$dept = (isset($_REQUEST['deptId']) ? $_REQUEST['deptId']:'');
	$startDate = (isset($_REQUEST['startDate']) ? $_REQUEST['startDate']:'');
	$endDate = (isset($_REQUEST['endDate']) ? $_REQUEST['endDate']:'');
	$hppd = (isset($_REQUEST['uosValue']) ? floatval($_REQUEST['uosValue']):'');
		
	$dbData1 = Config::get('db')->get_results("select n.*, (select count(`dept`) from `productiveAccountXref` where `type`<2 AND `whpPlan` !=1 and `dept`=n.deptId) as shiftCount, s.value, s.shiftOrder, h.useFlow, h.averageAcuity, h.avgWage, h.minRN, h.showEpic, h.ptTotal2, h.ptTotal3, h.ptTotal4, h.desc7, h.desc8, h.desc9, h.actionPlan, h.desc10, h.countcharge, h.useGrid, h.typetrack1, h.typetrack2, h.typetrack3, h.typetrack4, h.rnCount, h.rn1Count, h.rn2Count, h.roundrn, h.oneto1Acuity, h.oneto2Acuity, h.oneto3Acuity, h.oneto4Acuity, h.oneto5Acuity, h.oneto6Acuity, h.hppd, h.escalations, h.trackdc, h.trackdata, h.prodMeasure, h.thresholdHigh, h.thresholdLow, h.rnThreshold, h.track1Desc, h.track2Desc, h.track3Desc, h.track4Desc, h.adjustment, a.displayProd, a.productivityPosNeg, h.viewPage, h.inshiftProd, h.los, h.dept, h.rnVariance, h.churn, h.shiftsperDay, h.nurse1Variance, h.shift as censusShift from `productiveNewData` n left join `ProductiveDept` as h on h.id=n.deptId left join `productiveAccount` as a on a.id = h.accountId left join `productiveShifts` as t on t.id = n.shift left join `productiveAccountXref` as s on s.dept = n.deptId and s.shift = n.shift LEFT OUTER JOIN `productiveDeptXref` as x on x.deptId=n.deptId where n.deptId={$dept} AND n.dayDate >='{$startDate}' AND n.dayDate<='{$endDate}' AND n.userId>0 AND n.type<2 group by n.id");
	
	if($dbData1){
	foreach($dbData1 as $dbData){
		$dataOrig = array(
				'dataId' =>$dbData['id'],
				'accountId' =>$dbData['accountId'],
				'userId' =>$dbData['userId'],
				'deptId' =>$dbData['deptId'],
				'entered' =>$dbData['entered'],
				'dayDate' =>$dbData['dayDate'],
				'shift' =>$dbData['shift'],
				'chargecount' =>$dbData['chargecount'],
                'techcount' =>$dbData['techcount'],
				'tech1count' =>$dbData['tech1count'],
				'aptechcount' =>$dbData['aptechcount'],
				'seccount' =>$dbData['seccount'],
				'sittercount' =>$dbData['sittercount'],
				'antecount' =>$dbData['antecount'],
				'ldcount' =>$dbData['ldcount'],
				'ocount' =>$dbData['ocount'],
				'acs' =>$dbData['acs'],
				'am1' =>$dbData['am1'],
                'awcm' =>$dbData['awcm'],
				'obed' =>$dbData['obed'],
				'obed1' =>$dbData['obed1'],
                'ev' =>$dbData['ev'],
				'scs' =>$dbData['scs'],
				'cr' =>$dbData['cr'],
				'pt' =>$dbData['pt'],
                'ccs' =>$dbData['ccs'],
				'ps1' =>$dbData['ps1'],
				'pp' =>$dbData['pp'],
				'avariance' =>$dbData['avariance'],
				'lvariance' =>$dbData['lvariance'],
				'nvariance' =>$dbData['nvariance'],
				'gridrnvariance' =>$dbData['gridrnvariance'],
				'gvariance' =>$dbData['gvariance'],
				'note' =>addslashes($dbData['note']),
				'atotal' =>$dbData['atotal'],
				'patientCount2' =>$dbData['patientCount2'],
				'patientCount3' =>$dbData['patientCount3'],
				'patientCount4' =>$dbData['patientCount4'],
				'ltotal' =>$dbData['ltotal'],
				'aproductivity' =>$dbData['aproductivity'],
				'lproductivity' =>$dbData['lproductivity'],
				'nproductivity' =>$dbData['nproductivity'],
				'gridrnproductivity' =>$dbData['gridrnproductivity'],
				'customProd' =>$dbData['customProd'],
				'oneto1' =>$dbData['oneto1'],
				'oneto2' =>$dbData['oneto2'],
				'oneto3' =>$dbData['oneto3'],
				'oneto4' =>$dbData['oneto4'],
				'oneto5' =>$dbData['oneto5'],
				'oneto6' =>$dbData['oneto6'],
				'oneto7' =>$dbData['oneto7'],
				'whpPlan' =>$dbData['whpPlan'],
				'skill1val' =>$dbData['skill1val'],
				'skill2val' =>$dbData['skill2val'],
				'skill3val' =>$dbData['skill3val'],
				'skill4val' =>$dbData['skill4val'],
				'skill5val' =>$dbData['skill5val'],
				'skill6val' =>$dbData['skill6val'],
				'skill7val' =>$dbData['skill7val'],
				'skill8val' =>$dbData['skill8val'],
				'skill9val' =>$dbData['skill9val'],
				'skill10val' =>$dbData['skill10val'],
				'skill11val' =>$dbData['skill11val'],
				'addResourceHrs' =>$dbData['addResourceHrs'],
				'twoto1' =>$dbData['twoto1'],
				'procedureCount' =>$dbData['procedureCount'],
				'actualWHP' =>$dbData['actualWHP'],
				'actualWHPimport' =>$dbData['actualWHPimport'],
				'uosph' =>$dbData['uosph'],
				'plannedHours' =>$dbData['plannedHours'],
				'plannedProcs' =>$dbData['plannedProcs'],
				'admits' =>$dbData['admits'],
				'transfers' =>$dbData['transfers'],
				'discharges' =>$dbData['discharges'],
				'customChurn' =>$dbData['customChurn'],
				'budgetValue' =>$dbData['budgetValue'],
				'churnValue' =>$dbData['churnValue'],
				'visits' =>$dbData['visits'],
				'totalHours' =>$dbData['totalHours'],
				'customNurse' =>$dbData['customNurse'],
				'customNurse2' =>$dbData['customNurse2'],
				'otherNurse2' =>$dbData['otherNurse2'],
				'otherNurse1' =>$dbData['otherNurse1'],
				'otherNurse3' =>$dbData['otherNurse3'],
				'oneto8' =>$dbData['oneto8'],
				'oneto9' =>$dbData['oneto9'],
				'oneto10' =>$dbData['oneto10'],
				'oneto11' =>$dbData['oneto11'],
				'oneto12' =>$dbData['oneto12'],
				'epicScore' =>$dbData['epicScore'],
				'hrsVariance' =>$dbData['hrsVariance'],
				'ghrsVariance' =>$dbData['ghrsVariance'],
				'ahrsVariance' =>$dbData['ahrsVariance'],
				'rhrsVariance' =>$dbData['rhrsVariance'],
				'gridrnhrsVariance' =>$dbData['gridrnhrsVariance'],
				'hrsVarianceActual' =>$dbData['hrsVarianceActual'],
				'activeRecord' =>$dbData['activeRecord'],
				'censusTotal' =>$dbData['censusTotal'],
				'planSubmitted' =>$dbData['planSubmitted'],
				'visitsSubmitted' =>$dbData['visitsSubmitted'],
				'hoursSubmitted' =>$dbData['hoursSubmitted'],
				'hrs1Submitted' =>$dbData['hrs1Submitted'],
				'hrs2Submitted' =>$dbData['hrs2Submitted'],
				'hrs3Submitted' =>$dbData['hrs3Submitted'],
				'whpCustom' =>$dbData['whpCustom'],
				'whpCustom2' =>$dbData['whpCustom2'],
				'whpCustom3' =>$dbData['whpCustom3'],
				'whpCustom4' =>$dbData['whpCustom4'],
				'whpCustom5' =>$dbData['whpCustom5'],
				'type' =>$dbData['type'],
				'IndUOS' =>$dbData['IndUOS'],
				'IndHRS' =>$dbData['IndHRS'],
				'indUserId' =>$dbData['indUserId'],
				'provId' =>$dbData['provId'],
				'budgetVar' =>$dbData['budgetVar'],
				'gbudgetVar' =>$dbData['gbudgetVar'],
				'rbudgetVar' =>$dbData['rbudgetVar'],
				'gridrnbudgetVar' =>$dbData['gridrnbudgetVar'],
				'track1' =>$dbData['track1'],
				'track2' =>$dbData['track2'],
				'track3' =>$dbData['track3'],
				'track4' =>$dbData['track4'],
				'track1Detail' =>$dbData['track1Detail'],
				'track2Detail' =>$dbData['track2Detail'],
				'track3Detail' =>$dbData['track3Detail'],
				'track4Detail' =>$dbData['track4Detail'],
				'notetrack1' =>$dbData['notetrack1'],
				'notetrack2' =>$dbData['notetrack2'],
				'notetrack3' =>$dbData['notetrack3'],
				'notetrack4' =>$dbData['notetrack4'],
				'countDischarges' =>$dbData['countDischarges'],
				'submittedby' =>$dbData['submittedby'],
				'submitCount' =>$dbData['submitCount'],
				'rnVariance1' =>$dbData['rnVariance1'],
				'hppd2' =>$dbData['hppd'],
				'rnAcuity' =>$dbData['rnAcuity'],
				'oneto1Count' =>$dbData['oneto1Count'],
				'oneto2Count' =>$dbData['oneto2Count'],
				'oneto3Count' =>$dbData['oneto3Count'],
				'oneto3p5Count' =>$dbData['oneto3p5Count'],
				'oneto4Count' =>$dbData['oneto4Count'],
				'oneto4p5Count' =>$dbData['oneto4p5Count'],
				'oneto5Count' =>$dbData['oneto5Count'],
				'oneto5p5Count' =>$dbData['oneto5p5Count'],
				'oneto6Count' =>$dbData['oneto6Count'],
				'twoto1Count' =>$dbData['twoto1Count'],
				'threeto1Count' =>$dbData['threeto1Count'],
				'epicOrig' =>$dbData['epicOrig'],
				'shiftOrder' =>$dbData['shiftOrder']				
				);

	$newRecord = Config::get('db')->insert('productiveNewDataOrig',$dataOrig);
	
	$dataId = $dbData['id'];
	$chargecount = $dbData['chargecount'];
	$nurse1count = $dbData['customNurse'];
	$nurse2count = $dbData['customNurse2'];
	$other1count = $dbData['otherNurse1'];
	$other2count = $dbData['otherNurse2'];
	$other3count = $dbData['otherNurse3'];
	$techcount = $dbData['techcount'];
	$seccount = $dbData['seccount'];
	$nursecount = $dbData['antecount'];
	$sittercount = $dbData['sittercount'];
	$oneto1 = $dbData['oneto1'];
	$oneto2 = $dbData['oneto2'];
	$high = $dbData['oneto3'];
	$med = $dbData['oneto4'];
	$low = $dbData['oneto5'];
	$oneto6 = $dbData['oneto6'];
	
	$gridCount = floatval($nursecount) + floatval($chargecount) + intval($nurse1count) + intval($nurse2count) + intval($other1count) + intval($other2count) + intval($other3count) + intval($seccount) + intval($sittercount) + intval($techcount);
	
	if(intval($dbData['censusTotal'])>0){
	$patienttotal = intval($dbData['censusTotal']);
	}else{
	$patienttotal = intval($dbData['atotal']);
	}
	
	$nproductivity = $dbData['nproductivity'];
	$nvariance = $dbData['nvariance'];
	$rnhrsVariance = $dbData['rhrsVariance'];
	
	$gproductivity = $dbData['gproductivity'];
	$gvariance = $dbData['gvariance'];
	$gridhrsVariance = $dbData['gridhrsVariance'];
	
	$gridrnproductivity = $dbData['gridrnproductivity'];
	$gridrnvariance = $dbData['gridrnvariance'];
	$gridrnhrsVariance = $dbData['gridrnhrsVariance'];
	
	
	//$hppd = floatval($dbData['hppd']);
	$thresholdHigh = floatval($dbData['thresholdHigh']);
	$thresholdLow = floatval($dbData['thresholdLow']);
	$value = floatval($dbData['value']);
	$useGrid = intval($dbData['useGrid']);
	$deptId = intval($dbData['deptId']);
	$trackdc = $dbData['trackdc'];
	$trackdata = intval($dbData['trackdata']);
	$currentDischarges = 0;
	$dayDate = $dbData['dayDate'];
	$censusShift = $dbData['censusShift'];
	$inshiftProd = intval($dbData['inshiftProd']);
	$viewPage = $dbData['viewPage'];
	$prodUpdate = $dbData['nproductivity'];
	$escalations = intval($dbData['escalations']);
	$user = intval($dbData['userId']);
	$dep = $dbData['dept'];
	$countcharge = intval($dbData['countcharge']);
	$rnCount = intval($dbData['rnCount']);
	$rn1Count = intval($dbData['rn1Count']);
	$rn2Count = intval($dbData['rn2Count']);
	$roundrn = intval($dbData['roundrn']);
	
	$shiftCount = intval($dbData['shiftCount']);
	
	$prodMeasure = intval($dbData['prodMeasure']);
	$nurse1Variance = intval($dbData['nurse1Variance']);
	$procedureCount = floatval($dbData['procedureCount']);
	$actualWHP = floatval($dbData['actualWHP']);
	$displayProd = intval($dbData['rnVariance']);
	$prodnurseCount = ((floatval($chargecount) * $countcharge) + (floatval($nursecount) * $rnCount) + (floatval($nurse1count) * $rn1Count) + (floatval($nurse2count) * $rn2Count));
	$prodCalc = intval($dbData['productivityPosNeg']);
	$avgWage = floatval($dbData['avgWage']);
	$nurseAdd = floatval($dbData['addResourceHrs']);
	
	
/*	
		$rnThresholdHigh = intval($dbData['rnThreshold']);
		$rnThresholdLow = $rnThresholdHigh * -1;
		$oneto1Acuity = $dbData['oneto1Acuity'];
		$oneto2Acuity = $dbData['oneto2Acuity'];
		$oneto3Acuity = $dbData['oneto3Acuity'];
		$oneto4Acuity = $dbData['oneto4Acuity'];
		$oneto5Acuity = $dbData['oneto5Acuity'];
		$oneto6Acuity = $dbData['oneto6Acuity'];
		$oneto1Count = 0;
		$oneto2Count = 0;
		$oneto3Count = 0;
		$oneto4Count = 0;
		$oneto5Count = 0;
		$oneto6Count = 0;
		$twoto1Count = 0;
		$threeto1Count = 0;
		$oneto3p5Count = 0;
		$oneto4p5Count = 0;
		$oneto5p5Count = 0;
		
		$aproductivity2 = 0;
		$aproductivity = 0;
		$lproductivity = 0;
		$actualWHP = 0;
		
		
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
		*/
		/*
		if($useGrid==1 && $gridCount>0){
				$gridReqd = $gridCount - $gvariance;
				$prodnurseReqd = $prodnurseCount - $gridrnvariance;
				$gproductivity = (1-(($gridCount - $gridReqd) / $gridReqd)) * 100;
				$gridrnproductivity = (1-(($prodnurseCount - $prodnurseReqd) / $prodnurseReqd)) * 100;
		}else{
				$gproductivity = 0.00;
				$gridrnproductivity=0.00;
		}
		*/
		/*
			$nurseprodReqd = $prodnurseCount - $nvariance;
					if($nurseprodReqd>0){
					$nproductivity = (1-(($prodnurseCount - $nurseprodReqd) / $nurseprodReqd)) * 100;
					}else{
					$nproductivity = 0.0;
					}
			*/
			$antenurses = ((floatval($nursecount) + floatval($chargecount) + intval($nurse1count) + intval($nurse2count) + intval($other1count) + intval($other2count) + intval($other3count) + intval($seccount) + intval($sittercount) + intval($techcount)) * 24 / $shiftCount) + $nurseAdd;
			$antehours = ($patienttotal * $hppd) / $shiftCount;
					if(intval($antehours)>0 && $prodMeasure==1){
					$aprod = (1-(($antenurses - $antehours) / $antehours)) * 100;
					$lproductivity = round($aprod,2);
					}else{
					$lproductivity = 0.0;	
					}
			
			if($prodCalc!=1 && $prodMeasure!=4){
			$hrsVariance = round((($patienttotal * $hppd / $shiftCount) - $antenurses),3);
			$hrsbudgetVar = $hrsVariance * $avgWage;
				
			//$gridhrsVariance = round((($gvariance * 24) / $shiftCount) * -1,1);
			//$gridbudgetVar = $gridhrsVariance * $avgWage;
			//$gridrnhrsVariance = round((($gridrnvariance * 24) / $shiftCount) * -1,1);
			//$gridrnbudgetVar = $gridrnhrsVariance * $avgWage;
				
			//$rnhrsVariance = round((($nvariance * 24) / $shiftCount) * -1,1);
			//$rnbudgetVar = $rnhrsVariance * $avgWage;
			
			}else if($prodCalc==1 && $prodMeasure!=4){
			$hrsVariance = round((($patienttotal * $hppd / $shiftCount) - $antenurses) * -1,3);
			$hrsbudgetVar = $hrsVariance * $avgWage * -1;
				
			//$gridhrsVariance = round((($gvariance * 24) / $shiftCount),1);
			//$gridbudgetVar = $gridhrsVariance * $avgWage * -1;
			//$gridrnhrsVariance = round((($gridrnvariance * 24) / $shiftCount),1);
			//$gridrnbudgetVar = $gridrnhrsVariance * $avgWage * -1;
				
			//$rnhrsVariance = round((($nvariance * 24) / $shiftCount),1);
			//$rnbudgetVar = $rnhrsVariance * $avgWage * -1;
			}else{
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
				//$rbudgetVar = $rnbudgetVar;
				//$gbudgetVar = $gridbudgetVar;
				$hrsVariance = $hrsVariance;
				//$rhrsVariance = $rnhrsVariance;
				//$ghrsVariance = $gridhrsVariance;
			 /*
			 $data = array(
				'avariance' => $avariance,
				'nvariance' => $nvariance,
				'aproductivity' => $aproductivity,
				'nproductivity' => $nproductivity,
				'lproductivity' => $lproductivity,
				'customProd' => $gproductivity,
				'hrsVariance' => $hrsVariance,
				'ahrsVariance' => $ahrsVariance,
				'ghrsVariance' => $ghrsVariance,
				'rhrsVariance' => $rhrsVariance,
				'actualWHP' => $actualWHP,
				'budgetVar' =>$budgetVar,
				'gbudgetVar' =>$gbudgetVar,
				'rbudgetVar' =>$rbudgetVar,
				'gvariance' => $gvariance,
				'actualWHP' => $actualWHP,
				'gridrnvariance' => $gridrnvariance,
				'gridrnproductivity' => $gridrnproductivity,
				'gridrnbudgetVar' => $gridrnbudgetVar,
				'gridrnhrsVariance' => $gridrnhrsVariance
            );
			*/
				
		    $data = array(
				'avariance' => $avariance,
				'aproductivity' => $aproductivity,
				'lproductivity' => $lproductivity,
				'hrsVariance' => $hrsVariance,
				'ahrsVariance' => $ahrsVariance,
				'budgetVar' => $budgetVar
            );
			$updateWhere = array('id' => $dataId);
            
			$newProd = Config::get('db')->update('productiveNewData',$data,$updateWhere);
			//$newProd = Config::get('db')->insert('productiveNewDataOrig',$data);
			
	}
	echo json_encode(array('success'=>true,'message'=>true));
    return true;	
	}else{
	echo json_encode(array('success'=>true,'message'=>false));
    return true;	
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
	$bedCount = (isset($_REQUEST['bedCount'])) ? $_REQUEST['bedCount'] : 0;
	$useSafety = (isset($_REQUEST['useSafety'])) ? $_REQUEST['useSafety'] : 0;
	$safetyTypes = (isset($_REQUEST['safetyTypes'])) ? $_REQUEST['safetyTypes'] : 0;
	$label = (isset($_REQUEST['accountLabel'])) ? $_REQUEST['accountLabel'] : 1;
	$image = (isset($_REQUEST['accountImage'])) ? $_REQUEST['accountImage'] : '';
	$dashColor = (isset($_REQUEST['dashColor'])) ? $_REQUEST['dashColor'] : 1;
	$prodCalc = (isset($_REQUEST['prodCalc'])) ? $_REQUEST['prodCalc'] : -1;
	$rnvarianceLive = (isset($_REQUEST['rnvarianceLive'])) ? $_REQUEST['rnvarianceLive'] : 0;
	$defaultSort = (isset($_REQUEST['defaultSort'])) ? $_REQUEST['defaultSort'] : 0;
	$eas = (isset($_REQUEST['eas'])) ? $_REQUEST['eas'] : 0;
	$vids = (isset($_REQUEST['vids'])) ? $_REQUEST['vids'] : 0;
	$varmessage = (isset($_REQUEST['varmessage'])) ? $_REQUEST['varmessage'] : '';
	$accountName = (isset($_REQUEST['accountName'])) ? $_REQUEST['accountName'] : '';
	$updateLevel = (isset($_REQUEST['updateLevel'])) ? $_REQUEST['updateLevel'] : 11;
	$tportal = (isset($_REQUEST['tportal'])) ? $_REQUEST['tportal'] : 0;
	$acctEscalation = (isset($_REQUEST['acctEscalation'])) ? $_REQUEST['acctEscalation'] : 0;
	$houseLogins = (isset($_REQUEST['houseLogins'])) ? $_REQUEST['houseLogins'] : 0;
	$emailTime = (isset($_REQUEST['emailTime'])) ? $_REQUEST['emailTime'] : 0;
	$prodTerm = (isset($_REQUEST['prodTerm'])) ? $_REQUEST['prodTerm'] : 'Productivity';
	$hrsTerm = (isset($_REQUEST['hrsTerm'])) ? $_REQUEST['hrsTerm'] : 'WHPUOS';
	$dashTerm = (isset($_REQUEST['dashTerm'])) ? $_REQUEST['dashTerm'] : 'Dashboard';
	$easTerm = (isset($_REQUEST['easTerm'])) ? $_REQUEST['easTerm'] : 'Epic Score';
	$crashcart = (isset($_REQUEST['crashcart'])) ? $_REQUEST['crashcart'] : 0;
	$crashcarttime = (isset($_REQUEST['crashcarttime'])) ? $_REQUEST['crashcarttime'] : 0;
	$housealert = (isset($_REQUEST['housealert'])) ? $_REQUEST['housealert'] : 99.00;	
    if ($accountId) {
		$data = array(
            'accountAddress' => $address,
            'accountCity' => $city,
			'accountState' => $state,
			'accountZip' => $zip,
			'contactName' => $contactName,
			'contactEmail' => $contactEmail,
			'contactPhone' => $contactPhone,
			'enterpriseId' => $enterpriseId,
			'prodIndicator' => $dashColor,
			'productivityPosNeg' => $prodCalc,
			'rnvarianceLive' => $rnvarianceLive,
			'dashboardSort' => $defaultSort,
			'eas' => $eas,
			'logs' => $crashcart,
			'crashcartTime' => $crashcarttime,
			'label' => $label,
			'varMessage' => $varmessage,
			'name' => $accountName,
			'updateLevel' => $updateLevel,
			'acctEscalation' => $acctEscalation,
			'houseLogins' => $houseLogins,
			'emailTime' => $emailTime,
			'prodTerm' => $prodTerm,
			'dashTerm' => $dashTerm,
			'hrsTerm' => $hrsTerm,
			'easTerm' => $easTerm,
			'payPeriodFirst' => $payFirst,
			'payPeriod' => $payPeriod,
			'houseAlert' => $housealert,
			'bedCount' => $bedCount,
			'useSafety' => $useSafety,
			'safetyTypes' => $safetyTypes
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
	//$unitType = (isset($_REQUEST['unitType'])?$_REQUEST['unitType']:1);
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
	$skill9 = (isset($_REQUEST['skill9'])) ? $_REQUEST['skill9'] : 0;
	$skilldesc9 = (isset($_REQUEST['skilldesc9'])) ? $_REQUEST['skilldesc9'] : '';
	$skill10 = (isset($_REQUEST['skill10'])) ? $_REQUEST['skill10'] : 0;
	$skilldesc10 = (isset($_REQUEST['skilldesc10'])) ? $_REQUEST['skilldesc10'] : '';
	
	$uos1 = (isset($_REQUEST['uos1'])) ? $_REQUEST['uos1'] : 0;
	$uosdesc1 = (isset($_REQUEST['uosdesc1'])) ? $_REQUEST['uosdesc1'] : '';
	$uos2 = (isset($_REQUEST['uos2'])) ? $_REQUEST['uos2'] : 0;
	$uosdesc2 = (isset($_REQUEST['uosdesc2'])) ? $_REQUEST['uosdesc2'] : '';
	$uos3 = (isset($_REQUEST['uos3'])) ? $_REQUEST['uos3'] : 0;
	$uosdesc3 = (isset($_REQUEST['uosdesc3'])) ? $_REQUEST['uosdesc3'] : '';
	$uos4 = (isset($_REQUEST['uos4'])) ? $_REQUEST['uos4'] : 0;
	$uosdesc4 = (isset($_REQUEST['uosdesc4'])) ? $_REQUEST['uosdesc4'] : '';
	
	$ptuosdesc = (isset($_REQUEST['ptuosdesc'])) ? $_REQUEST['ptuosdesc'] : '';
	$ptuos2 = (isset($_REQUEST['ptuos2'])) ? $_REQUEST['ptuos2'] : 0;
	$ptuosdesc2 = (isset($_REQUEST['ptuosdesc2'])) ? $_REQUEST['ptuosdesc2'] : '';
	$ptuos3 = (isset($_REQUEST['ptuos3'])) ? $_REQUEST['ptuos3'] : 0;
	$ptuosdesc3 = (isset($_REQUEST['ptuosdesc3'])) ? $_REQUEST['ptuosdesc3'] : '';
	$ptuos4 = (isset($_REQUEST['ptuos4'])) ? $_REQUEST['ptuos4'] : 0;
	$ptuosdesc4 = (isset($_REQUEST['ptuosdesc4'])) ? $_REQUEST['ptuosdesc4'] : '';
	
	$WHPvalue = (isset($_REQUEST['WHPvalue'])) ? $_REQUEST['WHPvalue'] : 0;
	$WHP2value = (isset($_REQUEST['WHP2value'])) ? $_REQUEST['WHP2value'] : 0;
	$WHP3value = (isset($_REQUEST['WHP3value'])) ? $_REQUEST['WHP3value'] : 0;
	$WHP4value = (isset($_REQUEST['WHP4value'])) ? $_REQUEST['WHP4value'] : 0;
	$WHP5value = (isset($_REQUEST['WHP5value'])) ? $_REQUEST['WHP5value'] : 0;
		
	$daydataTitle = (isset($_REQUEST['daydataTitle'])) ? $_REQUEST['daydataTitle'] : '';
	
	if(floatval($WHPvalue)==0 || floatval($WHPvalue)==1 || intval($uos1)==0){
	$WHPvalue = $uos1;
	}
	if(floatval($WHP2value)==0 || floatval($WHP2value)==1 || intval($uos2)==0){
	$WHP2value = $uos2;
	}
	if(floatval($WHP3value)==0 || floatval($WHP3value)==1 || intval($uos3)==0){
	$WHP3value = $uos3;
	}
	if(floatval($WHP4value)==0 || floatval($WHP4value)==1 || intval($uos4)==0){
	$WHP4value = $uos4;
	}

	$thresholdLow = (isset($_REQUEST['thresholdLow'])) ? $_REQUEST['thresholdLow'] : 0;
	$thresholdHigh = (isset($_REQUEST['thresholdHigh'])) ? $_REQUEST['thresholdHigh'] : 0;
	$churn = (isset($_REQUEST['churn'])) ? $_REQUEST['churn'] : 0;
	$grid = (isset($_REQUEST['grid'])) ? $_REQUEST['grid'] : 0;
	$charge = (isset($_REQUEST['charge'])) ? $_REQUEST['charge'] : 0;
	$useHuddle = (isset($_REQUEST['useHuddle'])) ? $_REQUEST['useHuddle'] : 0;
	$budgetMeasure = (isset($_REQUEST['budgetMeasure'])) ? $_REQUEST['budgetMeasure'] : 0;
	$budgetValue = (isset($_REQUEST['budgetValue'])) ? $_REQUEST['budgetValue'] : 0;
	$skillbudget1 = (isset($_REQUEST['skillbudget1'])) ? $_REQUEST['skillbudget1'] : 0;
	$skillbudget2 = (isset($_REQUEST['skillbudget2'])) ? $_REQUEST['skillbudget2'] : 0;
	$skillbudget3 = (isset($_REQUEST['skillbudget3'])) ? $_REQUEST['skillbudget3'] : 0;
	$skillbudget4 = (isset($_REQUEST['skillbudget4'])) ? $_REQUEST['skillbudget4'] : 0;
	$skillbudget5 = (isset($_REQUEST['skillbudget5'])) ? $_REQUEST['skillbudget5'] : 0;
	$calc1 = (isset($_REQUEST['calc1'])) ? $_REQUEST['calc1'] : 1;
	$calc2 = (isset($_REQUEST['calc2'])) ? $_REQUEST['calc2'] : 2;
	$calc3 = (isset($_REQUEST['calc3'])) ? $_REQUEST['calc3'] : 3;
	$calc4 = (isset($_REQUEST['calc4'])) ? $_REQUEST['calc4'] : 4;
	$calc5 = (isset($_REQUEST['calc5'])) ? $_REQUEST['calc5'] : 5;
	$calc6 = (isset($_REQUEST['calc6'])) ? $_REQUEST['calc6'] : 6;
	$accountId = Config::get('account')['accountId'];
	$inshiftProd = (isset($_REQUEST['inshiftProd'])) ? $_REQUEST['inshiftProd'] : 0;
	$rnThreshold = (isset($_REQUEST['rnThreshold'])) ? $_REQUEST['rnThreshold'] : 0;
	$hrsThreshold = (isset($_REQUEST['hrsThreshold'])) ? $_REQUEST['hrsThreshold'] : 0;
	$gThreshold = (isset($_REQUEST['gThreshold'])) ? $_REQUEST['gThreshold'] : 0;
	$showProd = (isset($_REQUEST['showProd'])) ? $_REQUEST['showProd'] : 0;
	$showPlanned = (isset($_REQUEST['showPlanned'])) ? $_REQUEST['showPlanned'] : 0;
	$avgWage = (isset($_REQUEST['avgWage'])) ? $_REQUEST['avgWage'] : 1;
	$daysWk = (isset($_REQUEST['daysWk'])) ? $_REQUEST['daysWk'] : 7;
	$rndisplay = (isset($_REQUEST['rndisplay'])) ? $_REQUEST['rndisplay'] : 1;
	$countcharge = (isset($_REQUEST['countcharge'])) ? $_REQUEST['countcharge'] : 0;
	//$showrn = (isset($_REQUEST['showrn'])) ? $_REQUEST['showrn'] : 0;
	$showrntest = (isset($_REQUEST['showrntest'])) ? $_REQUEST['showrntest'] : '';
	$roundrn = (isset($_REQUEST['roundrn'])) ? $_REQUEST['roundrn'] : 0;
	$minRN = (isset($_REQUEST['minRN'])) ? $_REQUEST['minRN'] : 0;
	$showEpic = (isset($_REQUEST['showEpic'])) ? $_REQUEST['showEpic'] : 0;
	$dash5 = (isset($_REQUEST['dash5'])) ? $_REQUEST['dash5'] : 0;
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
	$linkedNew = array();
	/*
	$flow1 = (isset($_REQUEST['flow1'])) ? $_REQUEST['flow1'] : 0;
	$flow2 = (isset($_REQUEST['flow2'])) ? $_REQUEST['flow2'] : 0;
	$flow3 = (isset($_REQUEST['flow3'])) ? $_REQUEST['flow3'] : 0;
	$flow4 = (isset($_REQUEST['flow4'])) ? $_REQUEST['flow4'] : 0;
	$flow5 = (isset($_REQUEST['flow5'])) ? $_REQUEST['flow5'] : 0;
	$flow6 = (isset($_REQUEST['flow6'])) ? $_REQUEST['flow6'] : 0;
	$flow7 = (isset($_REQUEST['flow7'])) ? $_REQUEST['flow7'] : 0;
	$flow8 = (isset($_REQUEST['flow8'])) ? $_REQUEST['flow8'] : 0;
	*/
	$othercalc1 = (isset($_REQUEST['other1calc'])) ? $_REQUEST['other1calc'] : 1;
	//$othercalc1whp = (isset($_REQUEST['other1calcwhp'])) ? $_REQUEST['other1calcwhp'] : 1;
	$gridOpt1 = (isset($_REQUEST['gridOpt1'])) ? $_REQUEST['gridOpt1'] : 0;
	$gridOpt2 = (isset($_REQUEST['gridOpt2'])) ? $_REQUEST['gridOpt2'] : 0;
	$gridOpt3 = (isset($_REQUEST['gridOpt3'])) ? $_REQUEST['gridOpt3'] : 0;
	//$houseAlert = (isset($_REQUEST['houseAlert'])) ? $_REQUEST['houseAlert'] : 0;
	$linkedUnits = (isset($_REQUEST['linkedUnits'])) ? $_REQUEST['linkedUnits'] : '';
	$linkedNew = (isset($_REQUEST['linkedNew'])) ? $_REQUEST['linkedNew'] : '';
	$linkOrig = (isset($_REQUEST['linkOrig'])) ? $_REQUEST['linkOrig'] : '';
	$linkedresult = (isset($_REQUEST['linkedresult'])) ? $_REQUEST['linkedresult'] : 1;
	if(intval($linkedresult)>0){
		$links = implode(",",$linkedNew);
	}else{
		$links = '';
	}
	
	if(intval($othercalc1)==0){
		$other1calc = .5;
	}else{
		$other1calc = 1;
	}
	
	
	//$useFlow = (isset($_REQUEST['useFlow'])) ? $_REQUEST['useFlow'] : 0;
	$avgAcuity = (isset($_REQUEST['avgAcuity'])) ? $_REQUEST['avgAcuity'] : 0;
	
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
	$showArchive = (isset($_REQUEST['showArchive'])) ? $_REQUEST['showArchive'] : 0;
	
	$actionPlan = (isset($_REQUEST['actionPlan'])) ? addslashes($_REQUEST['actionPlan']) : '';
	
	if(intval($calc1)==0){
		$calc1=1;
	}
	if(intval($calc2)==0){
		$calc2=2;
	}
	if(intval($calc3)==0){
		$calc3=3;
	}
	if(intval($calc4)==0){
		$calc4=4;
	}
	if(intval($calc5)==0){
		$calc5=5;
	}
	if(intval($calc6)==0){
		$calc6=6;
	}
	
	if(strlen($trackdesc1 ?? '')>1 || strlen($trackdesc2 ?? '')>1 || strlen($trackdesc3 ?? '')>1 || strlen($trackdesc4 ?? '')>1){
		$trackdata = 1;
	}else{
		$trackdata = 0;
	}
	if(strlen($desc7 ?? '')>1 || strlen($desc8 ?? '')>1 || strlen($desc9 ?? '')>1 || strlen($desc10 ?? '')>1){
		$trackptdata = 1;
	}else{
		$trackptdata = 0;
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
			'dash5' => $dash5,
			'oneto8' => $oneto8,
			'desc8' => $desc8,
			'oneto9' => $oneto9,
			'desc9' => $desc9,
			'oneto10' => $oneto10,
			'desc10' => $desc10,
			'oneto1Acuity' => $calc1,
			'oneto2Acuity' => $calc2,
			'oneto3Acuity' => $calc3,
			'oneto4Acuity' => $calc4,
			'oneto5Acuity' => $calc5,
			'oneto6Acuity' => $calc6,
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
			'skill9' => $skill9,
			'skilldesc9' => $skilldesc9,
			'skill10' => $skill10,
			'skilldesc10' => $skilldesc10,
			'thresholdLow' => $thresholdLow,
			'thresholdHigh' => $thresholdHigh,
			'budgetMeasure' => $budgetMeasure,
			'uosValue' => $budgetValue,
			'uosDesc' => $uosDesc,
			'patientTotalDesc' => $ptuosdesc,
			'patientTotalDesc2' => $ptuosdesc2,
			'patientTotalDesc3' => $ptuosdesc3,
			'patientTotalDesc4' => $ptuosdesc4,
			'ptTotal2' => $ptuos2,
			'ptTotal3' => $ptuos3,
			'ptTotal4' => $ptuos4,
			'churn' => $churn,
			'inshiftProd' => $inshiftProd,
			'rnThreshold' => $rnThreshold,
			'hrsThreshold' => $hrsThreshold,
			'gThreshold' => $gThreshold,
			'charge' => $charge,
			'showProdWHP' => $showProd,
			'active' => $active,
			'showPlanned' => $showPlanned,
			'avgWage' => $avgWage,
			'daysWk' => $daysWk,
			'unitCategory' => $unitCategory,
			'trackdc' => $dc1,
			'trackdata' => $trackdata,
			'trackptdata' => $trackptdata,
			'track1Desc' => $trackdesc1,
			'track2Desc' => $trackdesc2,
			'track3Desc' => $trackdesc3,
			'track4Desc' => $trackdesc4,
			'rnVariance' => $rndisplay,
			'updatedDate' =>date("Y-m-d H:i:s",$viewDate),
			'updatedBy'=>$userId,
			'useGrid'=>$grid,
			'showrntest'=>$showrntest,
			'roundrn'=>$roundrn,
			'minRN'=>$minRN,
			'typetrack1'=>$typetrack1,
			'typetrack2'=>$typetrack2,
			'typetrack3'=>$typetrack3,
			'typetrack4'=>$typetrack4,
			'uosDefault'=>$uosDefault,
			'actionPlan'=>$actionPlan,
			'showVisits'=>$showVisits,
			'customWHP' => $uos1,
			'customWHPvalue' => $WHPvalue,
			'customDesc' => $uosdesc1,
			'customWHP2' => $uos2,
			'customWHP2value' => $WHP2value,
			'customDesc2' => $uosdesc2,
			'customWHP3' => $uos3,
			'customWHP3value' => $WHP3value,
			'customDesc3' => $uosdesc3,
			'customWHP4' => $uos4,
			'customWHP4value' => $WHP4value,
			'customDesc4' => $uosdesc4,
			'showEpic' => $showEpic,
			'averageAcuity' => $avgAcuity,
			'linkedUnits' => $linkedUnits,
			'linkUnits' => $links,
			'showArchive' => $showArchive,
			'gridOpt1' => $gridOpt1,
			'gridOpt2' => $gridOpt2,
			'gridOpt3' => $gridOpt3,
			'other1Calc' => $other1calc,
			'useHuddle' => $useHuddle,
			'dayDataTitle' => $daydataTitle
			
        );
	    $where = array('id'=>$unitId, 'accountId'=>$accountId);
        Config::get('db')->update('ProductiveDept',$data,$where);

		if((intval($newMgr) != intval($mgrOrig) && intval($newMgr)>0) || (intval($newMgr)<0  && intval($mgrOrig)>0)){
			$whereRemove = array(
				'userId'=> $mgrOrig,
				'deptId'=> $unitId
				);	
			
			Config::get('db')->delete('productiveDeptXref',$whereRemove);
		}
		
		if(intval($newMgr) != intval($mgrOrig) && intval($newMgr)>0){
				$dataNewM = array(
				'primaryUnit'=>6,
				'userId'=> $newMgr,
				'deptId'=> $unitId
				);
			Config::get('db')->insert('productiveDeptXref',$dataNewM);
					
		}
		
		if((intval($newDir) != intval($dirOrig) && intval($newDir)>0) || (intval($newDir)<0 && intval($dirOrig) >0)){
			$whereremove = array(
				'userId'=> $dirOrig,
				'deptId'=> $unitId
				);
			Config::get('db')->delete('productiveDeptXref',$whereremove);
		}
			
		if(intval($newDir) != intval($dirOrig) && intval($newDir)>0){
				$dataNewD = array(
				'primaryUnit'=>7,
				'userId'=> $newDir,
				'deptId'=> $unitId
				);
			Config::get('db')->insert('productiveDeptXref',$dataNewD);
				
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
				
			if(intval($shift)==38 && (intval($prodMeasure)==2 || intval($prodMeasure)==3)){
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
		/*
		if(intval($linkOrig) != intval($linkedId) && intval($unitId) != intval($linkedId)){
		$updateLink = updateLink($unitId,$linkedId);
		}
		*/
		if($linkedUnits!=$linkOrig){
		$nolink = '[\"0\"]';
		$selflink = '[\"'.$unitId.'\"]';
		if($linkedNew && $linkedUnits!=$nolink && $linkedUnits!=$selflink){
			$updatelink1 = array(
				'linkedUnits' => $nolink,
				'linkUnits' => '',
				'linkedUnit' =>0
				);
			$updatewhere1 = array('linkedUnits' => $linkOrig);
			$updateLink1 = Config::get('db')->update('ProductiveDept',$updatelink1,$updatewhere1);
			foreach($linkedNew as $newlinks){
				$updatelink1 = array(
				'linkedUnits' => $linkedUnits,
				'linkUnits' => $links,
				'linkedUnit' => $linkedresult
				);
				$updatewhere1 = array('id' => $newlinks);
				$updateLink1 = Config::get('db')->update('ProductiveDept',$updatelink1,$updatewhere1);
			}
		}
		if(intval($linkedresult)==0){
				$updatelink1 = array(
				'linkedUnits' => $linkedUnits,
				'linkUnits' => '',
				'linkedUnit' =>0
				);
				$updatewhere1 = array('linkedUnits' => $linkOrig);
				$updateLink1 = Config::get('db')->update('ProductiveDept',$updatelink1,$updatewhere1);
		}
		}
        echo json_encode(array('success'=>true,'message'=>'Unit Updated'));
        return true;
    }else{
	 echo json_encode(array('success'=>false,'message'=>'Unit Failed to Update'));
    return false;		
	}
   
}

//////////////////////////////////////
// LINK UNITS
function updateLink($deptId,$linkedId) {
	$accountId = (isset($_REQUEST['accountId'])?$_REQUEST['accountId']:'');
	$enterpriseId = (isset($_REQUEST['enterpriseId'])?$_REQUEST['enterpriseId']:'');
	$userId = (isset($_REQUEST['id'])?$_REQUEST['id']:'');
	
		$updatelink1 = array(
		'linkedUnit' => $linkedId
		);
		$updatewhere1 = array('id' => $deptId);
		$updateLink1 = Config::get('db')->update('ProductiveDept',$updatelink1,$updatewhere1);
	
	if(intval($linkedId)>0){
		$dbLinked = Config::get('db')->get_results("SELECT * from `ProductiveDept` where `id`={$linkedId}");
		if(intval($dbLinked[0]['linkedUnit'])==0){
		$updatelink = array(
		'linkedUnit' => $deptId
		);
		$updatewhere = array('id' => $linkedId);
		$updateLink = Config::get('db')->update('ProductiveDept',$updatelink,$updatewhere);
		}
	}else{
		$unlink = array(
		'linkedUnit' => 0
		);
		$unlinkwhere = array('linkedUnit' => $deptId);
		$updateunLink = Config::get('db')->update('ProductiveDept',$unlink,$unlinkwhere);
	}
		
     return true;
	
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
	$idsearch = isset($_REQUEST['id']) && ($_REQUEST['id'] != 0) ? $_REQUEST['id'] : '';
	$prodStart = isset($_REQUEST['prodStart']) && ($_REQUEST['prodStart'] != '') ? $_REQUEST['prodStart'] : '';
	$prodEnd = isset($_REQUEST['prodEnd']) && ($_REQUEST['prodEnd'] != '') ? $_REQUEST['prodEnd'] : '';
	$todayDate = isset($_REQUEST['todayDate']) && ($_REQUEST['todayDate'] != '') ? $_REQUEST['todayDate'] : time();
    $user = isset($_REQUEST['user']) ? $_REQUEST['user'] : '';
	$categoryId = isset($_REQUEST['category']) ? intval($_REQUEST['category']) : 0;
    $userId = Config::get('account')['id'];
	$demoMode = Config::get('account')['demoMode'];
    $accountId = Config::get('account')['accountId'];
	$enterpriseId = Config::get('account')['enterpriseId'];
    $role = Config::get('account')['role'];
	$total = 0;
    $variance = 0;
	$now = strtotime($todayDate);
	$nowDate = date('m/d/y',$now);
	
	$startDate = isset($_REQUEST['start']) ? $_REQUEST['start'] : date('yyyy-mm-dd');
	$endDate = isset($_REQUEST['end']) ? $_REQUEST['end'] : date('yyyy-mm-dd');
	
	
	if(intval($role)<8){
		$searchAdd = " AND a.unitCategory !=5 AND n.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId` ={$userId}) AND (a.active=1 OR a.active=2 OR a.showArchive=1)";
	}else if((intval($role)>=8 && intval($role)<=10) || (intval($role)==11 && intval($demoMode)==1) || (intval($role)==12 && intval($demoMode)==1)) {
		$searchAdd = " AND a.unitCategory !=5 AND n.accountId={$accountId} AND (a.active=1 OR a.active=2 OR a.showArchive=1)";
	}else if((intval($role)==11 && intval($demoMode)==0) || (intval($role)==12 && intval($demoMode)==0)) {
		$searchAdd = " AND a.unitCategory !=5 AND n.accountId={$accountId} AND (a.active>0 OR a.showArchive=1)";
	}else if(intval($role)==13) {
		$searchAdd = " AND a.unitCategory !=5 AND t.enterpriseId={$enterpriseId} AND (a.active=1 OR a.active=2 OR a.showArchive=1) AND n.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId` ={$userId})";
	}else if(intval($role)>=14) {
		$searchAdd = " AND a.unitCategory !=5 AND t.enterpriseId={$enterpriseId} AND (a.active=1 OR a.active=2 OR a.showArchive=1)";
	}else{
		$searchAdd = " AND a.unitCategory !=5 AND n.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId` ={$userId}) AND (a.active=1 OR a.showArchive=1)";
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
	
	
	if(strtotime($startDate)> strtotime('today UTC')){
		$orderBy = " ORDER BY n.dayDate ASC, a.dept ASC, n.shift DESC";
	}else{
		$orderBy = " ORDER BY n.dayDate DESC, a.dept ASC, n.shift DESC";
	}
	
    //$dbData = Config::get('db')->get_results("SELECT n.*, DATE_FORMAT(n.dayDate,'%d') as reportDay, (CASE WHEN n.dayDate=curdate() THEN 1 ELSE 0 END) as todayDate, n.id as nid, t.enterpriseId, t.nickName, t.name as accountName, t.varMessage, t.prodIndicator, t.displayProd, a.o_Calc, a.showArchive, a.rnCount, a.rn1Count, a.rn2Count, a.dashColor, a.roundrn, a.minRN, a.prodMeasure, a.shift as censusShift, a.showEpic, a.showEpicNurse, a.inshiftProd, a.modalPage, a.rnVariance, a.hppd, a.dept, (select count(`dept`) from `productiveAccountXref` where `type`<2 AND `whpPlan` !=1 and `dept`=a.id) as shiftCount, a.showProdWHP, a.thresholdHigh, a.rnThreshold, a.gThreshold, a.active as activeDept, a.thresholdLow, DATE_FORMAT(n.dayDate,'%m/%d/%y') as newDate, c.id as escId, c.response, IFNULL(c.note,'') as escalationNote, IFNULL(l.escalation,'') as escalationType, IFNULL(c.escalation,0) as escalationId, a.viewPage, s.hourofDay, s.shift as shiftName, SUBSTRING(u.first_name,1,1) as first_init, u.last_name FROM `productiveNewData` n LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as a on a.id = n.deptId LEFT JOIN `productiveAccount` as t on t.id = a.accountId LEFT OUTER JOIN `productiveUser` as u on u.id = n.userId LEFT OUTER JOIN `productiveEscalations` as c on c.dataId = n.id LEFT OUTER JOIN `productiveAcctEscalations` as l on l.id = c.escalation WHERE a.prodMeasure !=2 AND a.prodMeasure !=3 AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}'$searchAdd$searchAdd2$searchAdd3 group by n.deptId, n.dayDate, n.shift$orderBy");
    $dbData = Config::get('db')->get_results("SELECT n.*, DATE_FORMAT(n.dayDate,'%d') as reportDay, n.id as nid, t.enterpriseId, t.nickName, t.name as accountName, u.practiceId, t.varMessage, t.prodIndicator, t.displayProd, a.add1, a.add2, a.add3, a.add4, a.add5, a.add6, a.extadd6, a.o_Calc, a.showArchive, a.rnCount, a.rn1Count, a.rn2Count, a.dashColor, a.roundrn, a.minRN, a.prodMeasure, a.patienttitleExt, a.shift as censusShift, a.showEpic, a.showEpicNurse, a.staff1, a.staff2, a.staff3, a.staff4, a.staff5, a.staff6, a.staff7, a.staff8, a.staff9, a.staff10, a.inshiftProd, a.modalPage, a.rnVariance, a.hppd, a.dept, (select count(`dept`) from `productiveAccountXref` where `type`<2 AND `whpPlan` !=1 and `dept`=a.id) as shiftCount, a.showProdWHP, a.thresholdHigh, a.rnThreshold, a.gThreshold, a.active as activeDept, a.thresholdLow, DATE_FORMAT(n.dayDate,'%m/%d/%y') as newDate, c.id as escId, c.response, IFNULL(c.note,'') as escalationNote, IFNULL(l.escalation,'') as escalationType, IFNULL(c.escalation,0) as escalationId, a.viewPage, s.hourofDay, s.shift as shiftName, SUBSTRING(u.first_name,1,1) as first_init, u.last_name FROM `productiveNewData` n LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as a on a.id = n.deptId LEFT JOIN `productiveAccount` as t on t.id = a.accountId LEFT OUTER JOIN `productiveUser` as u on u.id = n.userId LEFT OUTER JOIN `productiveEscalations` as c on c.dataId = n.id AND c.active=1 LEFT OUTER JOIN `productiveAcctEscalations` as l on l.id = c.escalation WHERE a.prodMeasure !=2 AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}'$searchAdd$searchAdd2$searchAdd3 group by n.deptId, n.dayDate, n.shift$orderBy");
    
	
    if($dbData){
		$outJobs = array();
        $jobArray = array();
		
		
		foreach($dbData as $ante){
			$nUser = intval($ante['userId']);
			$date = $ante['newDate'];
			$oCalc = intval($ante['o_Calc']);
			$prodMeasure = intval($ante['prodMeasure']);
			$shift = intval($ante['shift']);
			$censusShift = intval($ante['censusShift']);
			$depName = $ante['dept'];
			$activeDept = intval($ante['activeDept']);
			$accountName = $ante['nickName'];
			$dateshift =  strtotime($date) + intval($shift);
			$dataId = intval($ante['nid']);
			$shiftName = $date . " - " . $ante['shiftName'];
			$hod = intval($ante['hourofDay']);
			$dow = intval($ante['reportDay']);
			$patienttitleExt = $ante['patienttitleExt'];
			
			
				
			
			if($activeDept==0){
				$nameAdd = "<span style=\"color:red\"> (Inactive)</span>";
			}else if($activeDept==2){
				$nameAdd = "<span style=\"color:red\"> (Customer Setup)</span>";
			}else if($activeDept==3){
				$nameAdd = "<span style=\"color:red\"> (Admin Setup)</span>";
			}else{
				$nameAdd = '';
			}
			
			if(intval($role)>=14){
			$deptName = $accountName . " - " . $depName.$nameAdd; // . " " . $userName;	
			}else{
			$deptName = $depName.$nameAdd; // . " " . $userName;	
			}
			
			if($nUser==0){
		
				if(intval($role)>4 && intval($role)!=9 && intval($role)<13 && $prodMeasure !=2 && strtotime($date) < strtotime($nowDate) && intval($shift)==$censusShift && $oCalc>0 && intval($accountId)!=20){
					$shiftDisplay = $shiftName . "</br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-success btn-sm\" onclick=\"tj.editNEW(" . $dataId . "," . $hod . "," . $dow . "); \">Add</button><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-danger btn-sm\" onclick=\"tj.clearRecord(" . $dataId . "); \">Del</button><button type=\"button\" id=\"addVisits\" class=\"btn btn-success btn-sm\" onclick=\"tj.addvisits(" . $dataId . "); \" >UOS</button>";
				}else if($prodMeasure !=2 && strtotime($date) < strtotime($nowDate) && intval($shift)==$censusShift && $oCalc>0 && intval($accountId)==20 && (intval($role)==11 || intval($role)==12)){
					$shiftDisplay = $shiftName . "</br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-success btn-sm\" onclick=\"tj.editNEW(" . $dataId . "," . $hod . "," . $dow . "); \">Add</button><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-danger btn-sm\" onclick=\"tj.clearRecord(" . $dataId . "); \">Del</button><button type=\"button\" id=\"addVisits\" class=\"btn btn-success btn-sm\" onclick=\"tj.addvisits(" . $dataId . "); \" >UOS</button>";
				}else if(intval($role)<6 && $prodMeasure !=2 && strtotime($date) < strtotime($nowDate) && $oCalc>0 && intval($shift)==$censusShift){
					$shiftDisplay = $shiftName . "</br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-success btn-sm\" onclick=\"tj.editNEW(" . $dataId . "," . $hod . "," . $dow . "); \" >Add</button>";
				}else if(intval($role)<6 && $prodMeasure !=2 && strtotime($date) < strtotime($nowDate)){
					$shiftDisplay = $shiftName . "</br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-success btn-sm\" onclick=\"tj.editNEW(" . $dataId . "," . $hod . "," . $dow . "); \" >Add</button>";
				}else if(intval($role)==11 || intval($role)==7 || intval($role)==6 || intval($role)==12){
					$shiftDisplay = $shiftName . "</br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-success btn-sm\" onclick=\"tj.editNEW(" . $dataId . "," . $hod . "," . $dow . "); \">Add</button><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-danger btn-sm\" onclick=\"tj.clearRecord(" . $dataId . "); \">Del</button>";
				}else if(intval($role)==10 || intval($role)==9){
					$shiftDisplay = $shiftName . "</br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-success btn-sm\" onclick=\"tj.editNEW(" . $dataId . "," . $hod . "," . $dow . "); \">Add</button>";
				}else if(intval($role)<6){
					$shiftDisplay = $shiftName . "</br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-success btn-sm\" onclick=\"tj.editNEW(" . $dataId . "," . $hod . "," . $dow . "); \">Add</button>";
				}else{
					$shiftDisplay = $shiftName;
				}
				
				
			$style="0";
			$note='';
			$nvarianceNull = '';
			$aprodNull = '';
			$nurseNull = '';
			$techNull = '';
			$secNull = '';
			$patientsNull = '';
			$chargeNull = '';
			$otherNull = '';
			$totalNull = '';
			$nvarianceNew = '';
			
			//if($nvariance>0 || $nvariance <0){
			//	$nvarianceNew = $nvariance;
			//}else{
			//	$nvarianceNew = '';
			//}
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
			$staff1 = intval($ante['staff1']);
			$staff2 = intval($ante['staff2']);
			$staff3 = intval($ante['staff3']);
			$staff4 = intval($ante['staff4']);
			$staff5 = intval($ante['staff5']);
			$staff6 = intval($ante['staff6']);
			$staff7 = intval($ante['staff7']);
			$staff8 = intval($ante['staff8']);
			$staff9 = intval($ante['staff9']);
			$staff10 = intval($ante['staff10']);
			$antecount = floatval($ante['antecount']);
			$sitters = intval($ante['sittercount']);
			$other1count = intval($ante['otherNurse1']);
			$other2count = intval($ante['otherNurse2']);
			$other3count = intval($ante['otherNurse3']);
			$chargecount = floatval($ante['chargecount']);
			$nurse1 = intval($ante['customNurse']);
			$nurse2 = intval($ante['customNurse2']);
			$atotal = intval($ante['atotal']);
			$patientCount2 = intval($ante['patientCount2']);
			$patientCount3 = intval($ante['patientCount3']);
			$patientCount4 = intval($ante['patientCount4']);
			$rnCount = intval($ante['rnCount']);
			$rn1Count = intval($ante['rn1Count']);
			$rn2Count = intval($ante['rn2Count']);
			$showEpic = intval($ante['showEpic']);
			$showEpicNurse = intval($ante['showEpicNurse']);
			$dashColor = intval($ante['prodIndicator']);
			$roundrn = intval($ante['roundrn']);
			$minRN = intval($ante['minRN']);
			$showArchive = intval($ante['showArchive']);
			$totalNurses = ($antecount * $rnCount) + ($nurse1 * $rn1Count) + ($nurse2 * $rn2Count);
			$totaltechcount = intval($ante['techcount']);
			$seccount = intval($ante['seccount']);
			$orientationcount = intval($ante['otherNurse2']);
			
			$totalCount = ($chargecount * $staff1) + ($antecount * $staff2) + ($nurse1 * $staff3) + ($nurse2 * $staff4) + ($seccount * $staff5) + ($sitters * $staff8) + ($totaltechcount * $staff6) + ($other1count * $staff7) + ($orientationcount * $staff9) + ($other3count * $staff10);
			$otherTotal = $totalCount - $chargecount - $totalNurses - $orientationcount;
			
			$displayProd = intval($ante['rnVariance']);
			$showProd = intval($ante['showProdWHP']);
			
			$inshiftProd = intval($ante['inshiftProd']);
			$shiftCount = intval($ante['shiftCount']);
			
			$hppd = $ante['hppd'];
			$whpPlan = intval($ante['whpPlan']);
			
			$epicScore = floatval($ante['epicScore']);
			
			if($antecount>0){
				$wit = ROUND(($epicScore / $antecount),2);
			}else{
				$wit=0;
			}
			
			if($showEpic==1 && $showEpicNurse==0){
				$patients = $atotal . " (". $epicScore .")" ;
			}else if($showEpic==1 && $showEpicNurse==1){
				$patients = $atotal . " (". $wit .")" ;
			}else{
				$patients = $atotal;
			}
			
			if(intval($ante['practiceId'])==1){
			$userName = " -" . $ante['submittedby'];
			}else{
			$userName = " -" . $ante['first_init'] . " " . $ante['last_name'] ;
			}
			
			//$deptName = $depName;
			$deptId = intval($ante['deptId']);
			
			$dayDate = $ante['dayDate'];
					
			$thresholdHigh = floatval($ante['thresholdHigh']);
			$thresholdLow = floatval($ante['thresholdLow']);			
			$aprod = floatval($ante['aproductivity']);
			//$modalPage = $ante['modalPage'];
			$escalationType = $ante['escalationType'];
			$escalationNote = $ante['escalationNote'];
			$escalationResponse = $ante['response'];
			$escalationId = intval($ante['escalationId']);
			$escId = intval($ante['escId']);
			
			
			$newnote1 = $ante['note'];
			if(strlen($newnote1 ?? '')<3){
				$newnote = "<a href=\"javascript:;\" onclick=\"tj.newNote(" . $ante['nid'] . "," . $ante['deptId'] . ",0);\"> Add Note</a>";;
			}else if($newnote1=="Note Skipped"){
				$newnote = "<a href=\"javascript:;\" onclick=\"tj.newNote(" . $ante['nid'] . "," . $ante['deptId'] . ",0);\"> Note Skipped</a>";;
			}else{
				$newnote = stripslashes($ante['note']);
			}
			
			
			$viewPage = $ante['viewPage'];
			$rnThresholdHigh = floatval($ante['rnThreshold']);
			$rnThresholdLow = $rnThresholdHigh * -1;
			$hoursThresholdHigh = ($rnThresholdHigh * 24) / $shiftCount;
			$hoursThresholdLow = (($rnThresholdHigh * 24) / $shiftCount) * -1;
			$gThresholdHigh = floatval($ante['gThreshold']);
			$gThresholdLow = $gThresholdHigh * -1;
			$hrsV=0;
			$rnV=0;
			$grnV=0;
			$ghrsV=0;
			$gV=0;
			
			if($displayProd==1){
				$hours = " hrs";
				$nvariance = floatval($ante['hrsVariance']) . $hours;
				$hrsV = floatval($ante['hrsVariance']);
			}else if($displayProd==0 && $roundrn==0){
				$hours = " (RNs)";
				$nvariance = round(floatval($ante['nvariance']),2) . $hours;
				$rnV = round(floatval($ante['nvariance']),2);
			}else if($displayProd==0 && $roundrn==1){
				$hours = " (RNs)";
				$nvariance = round(floatval($ante['nvariance']),0) . $hours;
				$rnV = round(floatval($ante['nvariance']),0);
			}else if($displayProd==0 && $roundrn==2){
				$hours = " (RNs)";
				$nvariance = floor(floatval($ante['nvariance'])) . $hours;
				$rnV = floor(floatval($ante['nvariance']));
			}else if($displayProd==0 && $roundrn==3){
				$hours = " (RNs)";
				$nvariance = ceil(floatval($ante['nvariance'])) . $hours;
				$rnV = ceil(floatval($ante['nvariance']));
			}else if($displayProd==3){
				$hours = " (RNs)";
				$nvariance = floatval($ante['gridrnvariance']) . $hours;
				$grnV = floatval($ante['gridrnvariance']);
			}else if($displayProd==4){
				$hours = " (hrs)";
				$nvariance = floatval($ante['ghrsVariance']) . $hours;
				$ghrsV = floatval($ante['ghrsVariance']);
			}else if($displayProd==5){
				$hours = " (NEDOCS)";
				$nvariance = ROUND(intval($ante['nedocScore']),0) . $hours;
				$ghrsV = floatval($ante['ghrsVariance']);
			}else{
				$hours = " (Grid)";
				$nvariance = floatval($ante['gvariance']) . $hours;	
				$gV = floatval($ante['gvariance']);	
			}
			

			
			if($aprod > 0){
				$aproductivity = $aprod . "%";
			}else{
				$aproductivity ='';
			}

				
				if(intval($role)>4 && intval($role)!=9 && intval($role)<13 && $prodMeasure !=2 && strtotime($date) < strtotime($nowDate) && intval($shift)==$censusShift && $oCalc>0 && intval($accountId)!=20){
					$shiftDisplay = $shiftName . "</br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-info btn-sm\" onclick=\"tj.editNEW(" . $dataId . "," . $hod . "," . $dow . "); \">Edit</button><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-danger btn-sm\" onclick=\"tj.clearRecord(" . $dataId . "); \">Del</button><button type=\"button\" id=\"addVisits\" class=\"btn btn-success btn-sm\" onclick=\"tj.addvisits(" . $dataId . "); \" >UOS</button>";
				}else if($prodMeasure !=2 && strtotime($date) < strtotime($nowDate) && intval($shift)==$censusShift && $oCalc>0 && intval($accountId)==20 && (intval($role)==11 || intval($role)==12)){
					$shiftDisplay = $shiftName . "</br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-info btn-sm\" onclick=\"tj.editNEW(" . $dataId . "," . $hod . "," . $dow . "); \">Edit</button><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-danger btn-sm\" onclick=\"tj.clearRecord(" . $dataId . "); \">Del</button><button type=\"button\" id=\"addVisits\" class=\"btn btn-success btn-sm\" onclick=\"tj.addvisits(" . $dataId . "); \" >UOS</button>";
				}else if(intval($role)<6 && $prodMeasure !=2 && strtotime($date) < strtotime($nowDate) && $oCalc>0 && intval($shift)==$censusShift){
					$shiftDisplay = $shiftName . "</br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-info btn-sm\" onclick=\"tj.editNEW(" . $dataId . "," . $hod . "," . $dow . "); \">Edit</button>";
				}else if(intval($role)<6 && $prodMeasure !=2 && strtotime($date) < strtotime($nowDate)){
					$shiftDisplay = $shiftName . "</br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-info btn-sm\" onclick=\"tj.editNEW(" . $dataId . "," . $hod . "," . $dow . "); \">Edit</button>";
				}else if(intval($role)==11 || intval($role)==7 || intval($role)==6 || intval($role)==12){
					$shiftDisplay = $shiftName . "</br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-info btn-sm\" onclick=\"tj.editNEW(" . $dataId . "," . $hod . "," . $dow . "); \">Edit</button><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-danger btn-sm\" onclick=\"tj.clearRecord(" . $dataId . "); \">Del</button>";
				}else if(intval($role)==10 || intval($role)==9){
					$shiftDisplay = $shiftName . "</br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-info btn-sm\" onclick=\"tj.editNEW(" . $dataId . "," . $hod . "," . $dow . "); \">View</button>";
				}else if(intval($role)<6){
					$shiftDisplay = $shiftName . "</br><button type=\"button\" id=\"addButtonStaffing\" class=\"btn btn-info btn-sm\" onclick=\"tj.editNEW(" . $dataId . "," . $hod . "," . $dow . "); \">Edit</button>";
				}else{
					$shiftDisplay = $shiftName;
				}
				
								
				if($escalationId >=1){
				$style = "1";
				$note = $newnote. "<a href=\"javascript:;\" onclick=\"tj.getEscalation(" . $escId . ");\"> ESCALATION: " . strtoupper($escalationType) . "</a> - " . $escalationNote;
				}else if(strlen($newnote ?? '')==0 && $displayProd==0 && intval($rnThresholdHigh) !=0 && ($rnV >= $rnThresholdHigh || $rnV <= $rnThresholdLow)){
				$style = "1";
				$note = $newnote;
				}else if(strlen($newnote ?? '')==0 && ($displayProd==2 || $showProd==6) && intval($gThresholdHigh) !=0 && ($gV >= $gThresholdHigh || $gV <= $gThresholdLow)){
				$style = "1";
				$note = $newnote;
				}else if(strlen($newnote ?? '')==0 && ($displayProd==3 || $showProd==5) && intval($rnThresholdHigh) !=0 && ($grnV >= $rnThresholdHigh || $grnV <= $rnThresholdLow)){
				$style = "1";
				$note = $newnote;
				}else{
				$style = "0";
				$note = $newnote;
				}
				
				
				
				$jobArray[] = array(
					'unit' => $deptName,
					'shift' => $shiftDisplay,
					'variance' => $nvariance,
					'patients' => $patients,
					'total' => $totalCount,
					'charge' => $chargecount,
					'nursecount' => $totalNurses,
					'techs' => $orientationcount,
					'other' => $otherTotal,
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

    
    $userId = Config::get('account')['id'];
	$demoMode = Config::get('account')['demoMode'];
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
	
	/*
	if(intval($role)<5){
		$searchAdd = " AND a.active>0 AND n.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId` ={$userId}) AND (n.userId=0 OR n.userId={$userId}) AND ((a.rnVariance=5 AND n.accountId !=8 AND n.accountId={$accountId} AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}') OR (a.rnVariance !=5 AND n.accountId!=8 AND n.accountId={$accountId} AND n.dayDate>=DATE_SUB('{$startDate}',INTERVAL 1 DAY) AND n.dayDate<=DATE_SUB('{$endDate}', INTERVAL 1 DAY)) OR (n.accountId=8 AND n.accountId={$accountId} AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}'))";
	}else if(intval($role)<8 && intval($role)>=5){
		$searchAdd = " AND a.active>0 AND n.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId` ={$userId}) AND ((a.rnVariance=5 AND n.accountId !=8 AND n.accountId={$accountId} AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}') OR (a.rnVariance !=5 AND n.accountId!=8 AND n.accountId={$accountId} AND n.dayDate >=DATE_SUB('{$startDate}',INTERVAL 1 DAY) AND n.dayDate <=DATE_SUB('{$endDate}', INTERVAL 1 DAY)) OR (n.accountId=8 AND n.accountId={$accountId} AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}'))";
	}else if(intval($role)>=8 && intval($role)<11) {
		$searchAdd = " AND a.active>0 AND ((a.rnVariance=5 AND n.accountId !=8 AND AND n.accountId={$accountId} n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}') OR (a.rnVariance !=5 AND n.accountId!=8 AND n.accountId={$accountId} AND n.dayDate >=DATE_SUB('{$startDate}',INTERVAL 1 DAY) AND n.dayDate <=DATE_SUB('{$endDate}',INTERVAL 1 DAY)) OR (n.accountId=8 AND n.accountId={$accountId} AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}'))";
	}else if(intval($role)==11) {
		$searchAdd = " AND ((a.rnVariance=5 AND n.accountId !=8 AND n.accountId={$accountId} AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}') OR (a.rnVariance !=5 AND n.accountId!=8 AND n.accountId={$accountId} AND n.dayDate >=DATE_SUB('{$startDate}',INTERVAL 1 DAY) AND n.dayDate<=DATE_SUB('{$endDate}',INTERVAL 1 DAY)) OR (n.accountId=8 AND n.accountId={$accountId} AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}'))";
	}else if(intval($role)>=12 && intval($role)<100) {
		$searchAdd = " AND a.active>0 AND ((a.rnVariance=5 AND n.accountId !=8 AND n.accountId={$accountId} AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}') OR (a.rnVariance !=5 AND n.accountId!=8 AND n.accountId={$accountId} AND n.dayDate >=DATE_SUB('{$startDate}',INTERVAL 1 DAY) AND n.dayDate <=DATE_SUB('{$endDate}',INTERVAL 1 DAY)) OR (n.accountId=8 AND n.accountId={$accountId} AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}'))";
	}else{
		$searchAdd = " AND a.active>0 AND n.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId` ={$userId}) AND ((a.rnVariance=5 AND n.accountId !=8 AND n.accountId={$accountId} AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}') OR (a.rnVariance !=5 AND n.accountId!=8 AND n.accountId={$accountId} AND n.dayDate >=DATE_SUB('{$startDate}',INTERVAL 1 DAY) AND n.dayDate <=DATE_SUB('{$endDate}',INTERVAL 1 DAY)) OR (n.accountId=8 AND n.accountId={$accountId} AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}'))";
	}
	*/
	
	if(intval($role)<5){
		$searchAdd = "  AND n.accountId={$accountId} AND (a.active=1 OR a.active=2 OR a.showArchive=1) AND n.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId` ={$userId}) AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}'";
	}else if(intval($role)<8 && intval($role)>=5){
		$searchAdd = "  AND n.accountId={$accountId} AND (a.active=1 OR a.active=2 OR a.showArchive=1) AND n.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId` ={$userId}) AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}'";
	}else if((intval($role)>=8 && intval($role)<=10) || (intval($role)==11 && intval($demoMode)==1) || (intval($role)==12 && intval($demoMode)==1)){
		$searchAdd = " AND (a.active=1 OR a.active=2 OR a.showArchive=1) AND n.accountId={$accountId} AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}'";
	}else if((intval($role)==11 && intval($demoMode)==0) || (intval($role)==12 && intval($demoMode)==0)) {
		$searchAdd = " AND n.accountId={$accountId} AND (a.active>0 OR a.showArchive=1) AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}'";
	}else if(intval($role)==13) {
		$searchAdd = " AND (a.active=1 OR a.active=2 OR a.showArchive=1) AND n.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId` ={$userId}) AND t.enterpriseId={$enterpriseId} AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}'";
	}else if(intval($role)>=14) {
		$searchAdd = " AND (a.active=1 OR a.active=2 OR a.showArchive=1) AND t.enterpriseId={$enterpriseId} AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}'";
	}else{
		$searchAdd = " AND (a.active=1 OR a.active=2 OR a.showArchive=1) AND n.accountId={$accountId} AND n.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId` ={$userId}) AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}'";
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
    
	$dbData = Config::get('db')->get_results("SELECT n.*, (CASE WHEN n.dayDate>DATE_FORMAT(n.entered,'%Y-%m-%d') THEN 1 ELSE 0 END) as plannedRecord, IFNULL(g.categoryName,'') as categoryName, DATE_FORMAT(CURDATE(),'%Y-%m-%d') as dateToday, u.last_name, IFNULL((select `last_name` from `productiveUser` where `id`=n.provId),'') as provName, n.id as nid, x.shiftName as shiftTitle, t.name as accountName, t.nickName, t.enterpriseId, t.prodIndicator, a.useGrid, a.dashColor, a.skill1calc, a.skill2calc, a.skill3calc, a.skill4calc, a.showArchive, a.supportHrs, a.skill5calc, a.skill6calc, a.skill7calc, a.skill8calc, a.skill9calc, a.skill10calc, a.prodMeasure, a.rnVariance, a.showPlanned, a.modalPage, a.hppd, a.customWHPvalue, a.customWHP2value, a.customWHP3value, a.customWHP4value, a.customWHP, a.customWHP2, a.customWHP3, a.customWHP4, a.dept, a.indMeasure, a.thresholdHigh, a.thresholdLow, DATE_FORMAT(n.dayDate,'%m/%d/%y') as newDate, IFNULL(c.note,'') as escalationNote, IFNULL(l.escalation,'') as escalationType, c.id as escId, c.response, IFNULL(c.escalation,0) as escalationId, a.viewPage, a.active, s.shift as shiftName, u.first_name, u.last_name FROM `productiveNewData` n LEFT JOIN `productiveAccountXref` as x on x.dept = n.deptId and x.shift = n.shift LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as a on a.id = n.deptId LEFT JOIN `productiveCategory` as g on g.id = a.category LEFT JOIN `productiveAccount` as t on t.id = a.accountId LEFT OUTER JOIN `productiveUser` as u on u.id = n.userId LEFT OUTER JOIN `productiveEscalations` as c on c.dataId = n.id AND c.active=1 LEFT OUTER JOIN `productiveAcctEscalations` as l on l.id = c.escalation WHERE a.prodMeasure=2 AND a.unitCategory>1 AND a.unitCategory<5$searchAdd$searchAdd3$group$orderBy");
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
			$type = intval($ante['type']);
			//$shifthrs = floatval($ante['shifthrs']);
			
			$today = date('Y-m-d',strtotime($ante['dateToday']));
			$daydate = date('Y-m-d',strtotime($ante['dayDate']));
			$plannedRecord = intval($ante['plannedRecord']);
			$deptId = $ante['deptId'];
			$procedures1 = floatval($ante['procedureCount']);
			$customVal = floatval($ante['whpCustom']);
			$whpCustom2 = floatval($ante['whpCustom2']);
			$whpCustom3 = floatval($ante['whpCustom3']);
			$whpCustom4 = floatval($ante['whpCustom4']);
			$supportHrs = floatval($ante['supportHrs']);
			
			$customWHPvalue = floatval($ante['customWHP']);
			$customWHP2value = floatval($ante['customWHP2']);
			$customWHP3value = floatval($ante['customWHP3']);
			$customWHP4value = floatval($ante['customWHP4']);
			
			if(($daydate>$today && $type!=2)  || ($nUser>0 && $plannedRecord==1 && $type!=2)){
					$style = "3";
					$planned = " (Plan)";
				}else{
					$style = "0";
					$planned = '';
				}
			if(intval($deptId)==435 && intval($procedures1)==0 && $whpCustom2>0){
				$procedures = $whpCustom2;
				$style = "3";
				$planned = " (Plan)";
			}else{
				$procedures = $procedures1;
				$style = "0";
				$planned = '';
			}
			
			$shiftTitle = $ante['shiftName'] . $planned;
			$sitters = intval($ante['sittercount']);
			$atotal = intval($ante['atotal']);
			$dashColor = intval($ante['prodIndicator']);
			$patients = $atotal;
			$chargecount = intval($ante['chargecount']);
			$totalNurses = $antecount;
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
			$rnVariance = intval($ante['rnVariance']);
			$activeDept = intval($ante['active']);
			
			$depName = $ante['dept'];
			$accountName = $ante['nickName'];
			
			if($activeDept==0){
				$nameAdd = "<span style=\"color:red\"> (Inactive)</span>";
			}else if($activeDept==2){
				$nameAdd = "<span style=\"color:red\"> (Customer Setup)</span>";
			}else if($activeDept==3){
				$nameAdd = "<span style=\"color:red\"> (Admin Setup)</span>";
			}else{
				$nameAdd = '';
			}
			
			if(intval($role)>=14){
			$deptName = $accountName . " - " . $depName.$nameAdd;	
			}else{
			$deptName = $depName.$nameAdd;	
			}
			
			
			
			
			$dayDate = strtotime($ante['newDate'])-21600;
			$date = $ante['newDate'];
			$shift = intval($ante['shift']);
			$dateP = $ante['newDate'] . " " . $shiftTitle;
			
			if($indMeasure>0 && $type==2 && $nUser!=0){
			$dateA = $ante['newDate'] . " " . $shiftTitle . " - " . $provName;
			}else if($type==2 && $nUser==0){
			$dateA = $ante['newDate'] . " (Provider)";
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
		
		$provHours1 = floatval($ante['provHours1']);
		$provHours2 = floatval($ante['provHours2']);
		$provHours3 = floatval($ante['provHours3']);
		$provHours4 = floatval($ante['provHours4']);
		$provHours5 = floatval($ante['provHours5']);
		$provHours6 = floatval($ante['provHours6']);
		$provhours = $provHours1 + $provHours2 + $provHours3 + $provHours4 + $provHours5 + $provHours6; 
			
			$addHrs = floatval($ante['addResourceHrs']);
			$gvariance = round(floatval($ante['gvariance']),2);
			//$procedureCount = $procedures + ($customVal * $customWHPvalue) + ($customWHP2value * $whpCustom2) + ($customWHP3value * $whpCustom3) + ($customWHP4value * $whpCustom4);
			$procedureCount = $procedures;			
			if(intval($ante['useGrid'])==2 && $rnVariance==5){
			//$skillTotal = $skill1 + $skill2 + $skill3 + $skill4 + $skill5 + $skill6 + $skill7 + $skill8 + $skill9 + $skill10 + $skill11;
			$skillTotal = round(((($skill1 * $skill1calc) + ($skill2 * $skill2calc) + ($skill3 * $skill3calc) + ($skill4 * $skill4calc) + ($skill5 * $skill5calc) + ($skill6 * $skill6calc) + ($skill7 * $skill7calc) + ($skill8 * $skill8calc) + ($skill9 * $skill9calc) + ($skill10 * $skill10calc))),0);
			$hoursVariance = $gvariance;
			$varType = " FTE";
			}else if(intval($ante['useGrid'])==2 && $rnVariance==2){
			//$skillTotal = $skill1 + $skill2 + $skill3 + $skill4 + $skill5 + $skill6 + $skill7 + $skill8 + $skill9 + $skill10 + $skill11;
			$skillTotal = round(((($skill1 * $skill1calc) + ($skill2 * $skill2calc) + ($skill3 * $skill3calc) + ($skill4 * $skill4calc) + ($skill5 * $skill5calc) + ($skill6 * $skill6calc) + ($skill7 * $skill7calc) + ($skill8 * $skill8calc) + ($skill9 * $skill9calc) + ($skill10 * $skill10calc))),0);
			$hoursVariance = $gvariance;
			$varType = " Hrs";
			}else if($type==2){
			$skillTotal = round($skill11,1);
			$hoursVariance = round($ante['hrsVariance'],2);
			$varType = " Hrs";
			}else{
			$skillTotal = round(((((($skill1 * $skill1calc) + ($skill2 * $skill2calc) + ($skill3 * $skill3calc) + ($skill4 * $skill4calc) + ($skill5 * $skill5calc) + ($skill6 * $skill6calc) + ($skill7 * $skill7calc) + ($skill8 * $skill8calc) + ($skill9 * $skill9calc) + ($skill10 * $skill10calc)))) * $supportHrs) + $addHrs,1);
			$hoursVariance = round($ante['hrsVariance'],2);	
			$varType = " Hrs";			
			}
		
		//if(intval($deptId)==455){
		//$skillTotal = $skillTotal + $provhours;	
		//}
			
						
			//if(floatval($aprod) > 0 && intval($deptId)!=1){
			if($aprod > 0){
				$aproductivity = $aprod . "%";
			}else{
				$aproductivity ='';
			}
			
			
			/*
			if($nUser==0 && intval($userId)==1271){
				
			if(intval($role)==11 || intval($role)==7 || intval($role)==12 || intval($role)==6){
				$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $dateA . "</a></br><button type=\"button\" id=\"addButtonWHP\" class=\"btn btn-info btn-sm\" onclick=\"tj.editWHP2(" . $dataId . "); \">Edit</button><button type=\"button\" id=\"addButtonWHP\" class=\"btn btn-danger btn-sm\" onclick=\"tj.clearwhpRecord(" . $dataId . "); \">Del</button>";
			}else if(intval($role)<6){
				$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $dateA . "</a></br><button type=\"button\" id=\"addButtonWHP\" class=\"btn btn-info btn-sm\" onclick=\"tj.editWHP2(" . $dataId . "); \">Edit</button>";
			}else{
				$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $dateA . "</a>";
			}
			

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
					'style' => $style,
					'plannedRecord'=>$plannedRecord
				);
			
			}else 
			*/
				
			if($nUser==0){
				
			if(intval($role)==11 || intval($role)==7 || intval($role)==6 || intval($role)==12){
				$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $dateA . "</a></br><button type=\"button\" id=\"addButtonWHP\" class=\"btn btn-success btn-sm\" onclick=\"tj.editWHP(" . $dataId . "); \">Add</button><button type=\"button\" id=\"addButtonWHP\" class=\"btn btn-danger btn-sm\" onclick=\"tj.clearwhpRecord(" . $dataId . "); \">Del</button>";
			}else if(intval($role)==10 || intval($role)==9){
				$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $dateA . "</a></br><button type=\"button\" id=\"addButtonWHP\" class=\"btn btn-success btn-sm\" onclick=\"tj.editWHP(" . $dataId . "); \">Add</button>";
			}else if(intval($role)<6){
				$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $dateA . "</a></br><button type=\"button\" id=\"addButtonWHP\" class=\"btn btn-success btn-sm\" onclick=\"tj.editWHP(" . $dataId . "); \">Add</button>";
			}else{
				$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $dateA . "</a>";
			}
			

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
					'style' => $style,
					'plannedRecord'=>$plannedRecord
				);
			
			}else{
				
					
			//if(intval($role)>7 && $whpPlan==1 && intval($role)!=11){
			//	$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $dateP . "</a>";
			//}else if(intval($role)>7 && $whpPlan!=1 && intval($role)!=11){
			//	$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $dateA . "</a>";
			//}else 
				
			if(intval($role)==11 || intval($role)==7 || intval($role)==6 || intval($role)==12){
				$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $dateA . "</a></br><button type=\"button\" id=\"addButtonWHP\" class=\"btn btn-info btn-sm\" onclick=\"tj.editWHP(" . $dataId . "); \">Edit</button><button type=\"button\" id=\"addButtonWHP\" class=\"btn btn-danger btn-sm\" onclick=\"tj.clearwhpRecord(" . $dataId . "); \">Del</button>";
			}else if(intval($role)==10){
				$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $dateA . "</a></br><button type=\"button\" id=\"addButtonWHP\" class=\"btn btn-info btn-sm\" onclick=\"tj.editWHP(" . $dataId . "); \">Edit</button>";
			}else if(intval($role)<6){
				$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $dateA . "</a></br><button type=\"button\" id=\"addButtonWHP\" class=\"btn btn-info btn-sm\" onclick=\"tj.editWHP(" . $dataId . "); \">Edit</button>";
			}else{
				$shiftDisplay = "<a href=\"#reportView?i=" . $dataId . "\">" . $dateA . "</a>";
			}
			
				
				if($escalationId >0 && $nUser >0){
				$style = "1";
				$note = $newnote.  "<a href=\"javascript:;\" onclick=\"tj.getEscalationwhp(" . $escId . ");\"> ESCALATION: " . strtoupper($escalationType) . "</a> - " . stripslashes($escalationNote) . " " . stripslashes($escalationResponse);
				}else{
				$style = "0";
				$note = $newnote;
				}
				
				
				$jobArray[] = array(
					'unit' => $deptName,
					'shift' => $shiftDisplay,
					'aprod' => $actualWHP,
					'variance' => $hoursVariance . $varType,
					'patients' => $procedureCount,
					'total' => $skillTotal . $varType,
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
					'style' => $style,
					'plannedRecord'=>$plannedRecord
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
/*
/////////////////////////////////////
// GET PDF INFO
function GetReportMgrTableData() {
    $accountId = Config::get('account')['accountId'];
	$userId = Config::get('account')['id'];
	$role = Config::get('account')['role'];
	$enterpriseId = Config::get('account')['enterpriseId'];
	
	if(intval($role)>5){
		$searchAdd = " WHERE u.accountId={$accountId} AND u.active>0 AND u.email LIKE '%@%' AND u.last_name !='' AND ((u.role <{$role} and x.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId})) OR u.id={$userId}) ";
	}else{
		$searchAdd = " WHERE u.accountId={$accountId} AND u.active>0 AND u.id={$userId} AND u.email LIKE '%@%' AND u.last_name !='' ";
	}

    $dbData = Config::get('db')->get_results("SELECT u.*, a.enterpriseId, a.nickName
        FROM
            `productiveUser` u
		LEFT OUTER JOIN 
			`productiveDeptXref` as x on x.userId = u.id
		LEFT OUTER JOIN 
			`ProductiveDept` as d on d.id = x.deptId
		LEFT OUTER JOIN 
			`productiveAccount` as a on a.id = d.accountId
        $searchAdd
        ORDER BY u.last_name asc");
		
    if($dbData){
		$outJobs = array();
        $jobArray = array();
				
		foreach($dbData as $ante){
	
			if(intval($ante['role'])>7){
				$check1 = "<input type=\"checkbox\" id=\"checkComp" . $ante['id'] . "\" name=\"checkComp" . $ante['id'] . "\"></input>";
				$check2 = '';
			}else if(intval($ante['role'])<=7 && intval($ante['role'])>4){
				$check1 = '';
				$check2 = "<input type=\"checkbox\" id=\"checkUnit" . $ante['id'] . "\" name=\"checkUnit" . $ante['id'] . "\"></input>";
			}else{
				$check1 = '';
				$check2 = '';
			}
			
			
			$jobArray[] = array(
				'user' => $userName,
				'daily' => $check1,
				'unit' => $check2
            );
			
		}	
					
		$outData = array();
		$outData['data'] = $jobArray;
		echo json_encode($outData,JSON_NUMERIC_CHECK);
		}else{
        $outData = array();
		$jobArray[] = array(
				'user' => '',
				'daily' => '',
				'unit' => ''
            );
        $outData['data'] = $jobArray;
        echo json_encode($outData,JSON_NUMERIC_CHECK);
		}
}
*/

/////////////////////////////////////
// GET PDF INFO

function GetPerformanceTableData() {
	$deptId = (isset($_REQUEST['deptId'])?$_REQUEST['deptId']:'');
	$pdfId = (isset($_REQUEST['pdfId'])?$_REQUEST['pdfId']:'');
	$idsearch = isset($_REQUEST['id']) && ($_REQUEST['id'] != 0) ? $_REQUEST['id'] : '';
    //$startDate = isset($_REQUEST['start']) ? $_REQUEST['start'] : date('yyyy-mm-dd');
    //$endDate = isset($_REQUEST['end']) ? $_REQUEST['end'] : date('yyyy-mm-dd');
    $userId = Config::get('account')['id'];
	$countDept = Config::get('account')['countDept'];
    $accountId = Config::get('account')['accountId'];
	$demoMode = Config::get('account')['demoMode'];
	$role = Config::get('account')['role'];
	$enterpriseId = Config::get('account')['enterpriseId'];
	
	if(intval($role)<=7){
		$searchAdd = "(d.active=1 OR d.active=2) AND d.unitCategory !=4 AND d.id in (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId} and `primaryUnit`>0)";
	}else if((intval($role)>7 && intval($role)<=10) || (intval($role)==11 && intval($demoMode)==1) || (intval($role)==12 && intval($demoMode)==1)){
		$searchAdd = "(d.active=1 OR d.active=2) AND d.unitCategory !=4 AND d.accountId={$accountId}";
	}else if((intval($role)==11 && intval($demoMode)==0) || (intval($role)==12 && intval($demoMode)==0)){
		$searchAdd = "(d.active=1 OR d.active=2) AND d.unitCategory !=4 AND d.accountId={$accountId}";
	}else if(intval($role)==13){
		$searchAdd = "(d.active=1 OR d.active=2) AND d.unitCategory !=4 AND a.enterpriseId={$enterpriseId} AND d.id in (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId} and `primaryUnit`>0)";
	}else if(intval($role)>=14){
		$searchAdd = "(d.active=1 OR d.active=2) d.unitCategory !=4 AND AND a.enterpriseId={$enterpriseId}";
	}else{
		$searchAdd = "(d.active=1 OR d.active=2) d.unitCategory !=4 AND AND d.accountId={$accountId}";	
	}

    $dbData = Config::get('db')->get_results("SELECT d.*, a.enterpriseId, a.nickName
        FROM
            `ProductiveDept` d
		LEFT JOIN 
			`productiveAccount` as a on a.id = d.accountId
        WHERE
            $searchAdd
        ORDER BY d.dept asc");
		
	
		
    if($dbData){
		$outJobs = array();
        $jobArray = array();
		
		if(intval($countDept)>1 || intval($role)>7){
		
		$pdfbutton3 = "<form id=\"allunitFormwhp" . $dbData[0]['id'] . "\" name=\"allunitFormwhp" . $dbData[0]['id'] . "\" action=\"inc/reports.php?req=allunit&u=0\" method=\"post\"><button id=\"downloadallunit" . $dbData[0]['id'] . "\" class=\"btn btn-info btn-sm\" type=\"submit\">PDF</button><input type=\"number\" name=\"deptallunit\" value=\"" . $dbData[0]['id'] . "\" hidden></form>";
						
			$jobArray[] = array(
				'unit' => " All",
				'desc' => " Unit Variance Summary",
				'pdf' => $pdfbutton3
            );
			
		$pdfbutton4 = "<form id=\"allshiftFormwhp" . $dbData[0]['id'] . "\" name=\"allshiftFormwhp" . $dbData[0]['id'] . "\" action=\"inc/reports.php?req=allshift&s=0\" method=\"post\"><button id=\"downloadallshift" . $dbData[0]['id'] . "\" class=\"btn btn-success btn-sm\" type=\"submit\">PDF</button><input type=\"number\" name=\"deptallshift\" value=\"" . $dbData[0]['id'] . "\" hidden></form>";
						
			$jobArray[] = array(
				'unit' => " All",
				'desc' => " Shift Variance Summary",
				'pdf' => $pdfbutton4
            );
			
		 if(intval($role)<=7){
				$searchAdd = "AND d.unitCategory !=4 AND d.active=1 AND d.id in (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId} and `primaryUnit`>0) AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate";
			}else{
				$searchAdd = "AND d.unitCategory !=4 AND d.active=1 AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate";
			}

		$getvoice = Config::get('db') -> get_results("select n.*, u.lastStartDate, u.lastEndDate, d.prodMeasure1, o.practiceId, DATE_FORMAT(n.dayDate,'%m/%d') as newDate, s.shift as shiftName, o.last_name as submitted, d.dept as deptName, DATE_FORMAT(CURDATE(),'%m%d%y') as filedate, a.name as accountName FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveShifts` as s on s.id = n.shift left join `productiveAccount` as a on a.id=n.accountId left outer join `productiveUser` as o on o.id=n.userId left join `productiveUser` as u on u.id={$userId} where n.accountId={$accountId} AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate AND LENGTH(n.note)>15 $searchAdd group by n.accountId, n.deptId, n.dayDate, n.shift ORDER BY d.dept ASC, n.dayDate DESC, n.shift DESC");
	
		if($getvoice ?? ''){		
			$pdfbutton6 = "<form id=\"allvoiceFormwhp" . $dbData[0]['id'] . "\" name=\"allvoiceFormwhp" . $dbData[0]['id'] . "\" action=\"inc/reports.php?req=allvoice&r=0\" method=\"post\"><button id=\"downloadallvoice" . $dbData[0]['id'] . "\" class=\"btn btn-warning btn-sm\" type=\"submit\">PDF</button><input type=\"number\" name=\"deptallvoice\" value=\"" . $dbData[0]['id'] . "\" hidden></form>";
			//$pdfbutton5 = '';
		}else{
			$pdfbutton6 = '';
		}			
			$jobArray[] = array(
				'unit' => " All",
				'desc' => " Voice of the Unit",
				'pdf' => $pdfbutton6
            );
			
			if(intval($role)<=7){
				$searchAdd = "WHERE e.active>0 AND e.accountId ={$accountId} AND d.unitCategory !=4 AND d.active=1 AND d.id in (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId} and `primaryUnit`>0) AND DATE(e.dateSubmitted) >=y.lastStartDate AND DATE(e.dateSubmitted) <=y.lastEndDate";
			}else{
				$searchAdd = "WHERE e.active>0 AND e.accountId ={$accountId} AND d.unitCategory !=4 AND d.active=1 AND DATE(e.dateSubmitted) >=y.lastStartDate AND DATE(e.dateSubmitted) <=y.lastEndDate";
			}
			
			$allesc = Config::get('db') -> get_results("select e.*, DATE_FORMAT(e.dateSubmitted,'%m%d%y') as newDate, c.last_name as closedLast, c.first_name as closedFirst, a.escalation as escType, IFNULL(n.submittedby,'') as submittedby, d.dept, u.last_name, u.first_name FROM `productiveEscalations` e LEFT OUTER JOIN `productiveUser` as c on c.id = e.closedId LEFT JOIN `productiveAcctEscalations` as a on a.id=e.escalation LEFT OUTER JOIN `productiveNewData` as n on n.id=e.dataId LEFT JOIN `productiveUser` as y on y.id = {$userId} LEFT JOIN `ProductiveDept` as d on d.id=e.deptId LEFT OUTER JOIN `productiveUser` as u on u.id=e.userId $searchAdd group by e.id ORDER BY e.dateSubmitted DESC");
			
			if($allesc ?? ''){
			$pdfbutton7 = "<form id=\"allescalationFormwhp" . $dbData[0]['id'] . "\" name=\"allescalationFormwhp" . $dbData[0]['id'] . "\" action=\"inc/reports.php?req=allescalation&r=0\" method=\"post\"><button id=\"downloadallescalation" . $dbData[0]['id'] . "\" class=\"btn btn-danger btn-sm\" type=\"submit\">PDF</button><input type=\"number\" name=\"deptallescalation\" value=\"" . $dbData[0]['id'] . "\" hidden></form>";
			}else{
			$pdfbutton7 = '';
			}	
			
			$jobArray[] = array(
				'unit' => " All",
				'desc' => " Escalation Summary",
				'pdf' => $pdfbutton7
            );

		}
		
		foreach($dbData as $ante){
		$activeDept = intval($ante['active']);
		$deptId = intval($ante['id']);
		$userId = Config::get('account')['id'];
		$role = Config::get('account')['role'];
		$accountId = Config::get('account')['accountId'];
		$getshift = Config::get('db') -> get_results("select n.*, u.lastStartDate, u.lastEndDate, d.prodMeasure1, o.practiceId, DATE_FORMAT(n.dayDate,'%m/%d') as newDate, s.shift as shiftName, o.last_name as submitted, d.dept as deptName, DATE_FORMAT(CURDATE(),'%m%d%y') as filedate, a.name as accountName FROM `productiveNewData` n left join `ProductiveDept` as d on d.id = n.deptId left join `productiveShifts` as s on s.id = n.shift left join `productiveAccount` as a on a.id=n.accountId left outer join `productiveUser` as o on o.id=n.userId left join `productiveUser` as u on u.id={$userId} where n.accountId={$accountId} AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate AND n.userId>0 AND d.id={$deptId} group by n.accountId, n.deptId, n.dayDate, n.shift ORDER BY d.dept ASC, n.dayDate DESC, n.shift DESC");
		$getVoice = Config::get('db') -> get_results("SELECT n.deptId, n.note, n.dayDate, d.active, d.dept, d.prodMeasure1, a.name as accountName, a.displayProd, u.lastStartDate, u.lastEndDate, DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%m/%d/%y') as reportDate from `productiveNewData` n LEFT JOIN `productiveAccount` as a on a.id=n.accountId LEFT JOIN `ProductiveDept` as d on d.id=n.deptId left join `productiveUser` as u on u.id={$userId} WHERE d.unitCategory !=4 AND n.accountId={$accountId} AND LENGTH(n.note)>15 AND n.deptId={$deptId} AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate GROUP BY n.deptId ORDER BY d.prodMeasure ASC, d.dept ASC");
		$getEsc = Config::get('db') -> get_results("select e.*, DATE_FORMAT(e.dateSubmitted,'%m%d%y') as newDate, c.last_name as closedLast, c.first_name as closedFirst, a.escalation as escType, IFNULL(n.submittedby,'') as submittedby, d.dept, u.last_name, u.first_name FROM `productiveEscalations` e LEFT OUTER JOIN `productiveUser` as c on c.id = e.closedId LEFT JOIN `productiveAcctEscalations` as a on a.id=e.escalation LEFT OUTER JOIN `productiveNewData` as n on n.id=e.dataId LEFT JOIN `productiveUser` as y on y.id = {$userId} LEFT JOIN `ProductiveDept` as d on d.id=e.deptId LEFT OUTER JOIN `productiveUser` as u on u.id=e.userId WHERE e.active>0 AND e.accountId ={$accountId} AND e.deptId={$deptId} AND DATE(e.dateSubmitted)>=y.lastStartDate AND DATE(e.dateSubmitted)<=y.lastEndDate group by e.id ORDER BY e.dateSubmitted DESC");
			
		if(intval($ante['prodMeasure'])!=2){
			if($activeDept==0){
				$nameAdd = "<span style=\"color:red\"> (Inactive)</span>";
			}else if($activeDept==2){
				$nameAdd = "<span style=\"color:red\"> (Customer Setup)</span>";
			}else if($activeDept==3){
				$nameAdd = "<span style=\"color:red\"> (Admin Setup)</span>";
			}else{
				$nameAdd = '';
			}
			$accountName = $ante['nickName'];
			if(intval($role)>=12){
			$unitName = $accountName . " - " . $ante['dept'].$nameAdd;	
			}else{
			$unitName = $ante['dept'].$nameAdd;	
			}
			
		
		if($getshift ?? ''){		
			$pdfbutton1 = "<form id=\"shiftFormwhp" . $ante['id'] . "\" name=\"shiftFormwhp" . $ante['id'] . "\" action=\"inc/reports.php?req=allshift&s=" . $ante['id'] . "\" method=\"post\"><button id=\"downloadshift" . $ante['id'] . "\" class=\"btn btn-success btn-sm\" type=\"submit\">PDF</button><input type=\"number\" name=\"deptshift\" value=\"" . $ante['id'] . "\" hidden></form>";
		}else{
			$pdfbutton1 = '';
		}	
			
						
			$jobArray[] = array(
				'unit' => $unitName,
				'desc' => " Shift Variance Summary",
				'pdf' => $pdfbutton1
            );

		/*
		if($getshift ?? ''){		
			$pdfbutton2 = "<form id=\"userFormwhp" . $ante['id'] . "\" name=\"userFormwhp" . $ante['id'] . "\" action=\"inc/reports.php?req=alluser&e=" . $ante['id'] . "\" method=\"post\"><button id=\"downloaduser" . $ante['id'] . "\" class=\"btn btn-info btn-sm\" type=\"submit\">PDF</button><input type=\"number\" name=\"deptuser\" value=\"" . $ante['id'] . "\" hidden></form>";
			}else{
			$pdfbutton2 = '';
			}
			
						
			$jobArray[] = array(
				'unit' => $unitName,
				'desc' => " User Variance Summary",
				'pdf' => $pdfbutton2
            );
		*/	
		if($getshift ?? ''){		
			$pdfbutton3 = "<form id=\"unitFormwhp" . $ante['id'] . "\" name=\"unitFormwhp" . $ante['id'] . "\" action=\"inc/reports.php?req=allunits&u=" . $ante['id'] . "\" method=\"post\"><button id=\"downloadunit" . $ante['id'] . "\" class=\"btn btn-info btn-sm\" type=\"submit\">PDF</button><input type=\"number\" name=\"deptunit\" value=\"" . $ante['id'] . "\" hidden></form>";
			}else{
			$pdfbutton3 = '';
			}
			
						
			$jobArray[] = array(
				'unit' => $unitName,
				'desc' => " Unit Variance Summary",
				'pdf' => $pdfbutton3
            );
			
		if($getVoice ?? ''){
			$pdfbutton4 = "<form id=\"voiceFormwhp" . $ante['id'] . "\" name=\"voiceFormwhp" . $ante['id'] . "\" action=\"inc/reports.php?req=allvoice&r=" . $ante['id'] . "\" method=\"post\"><button id=\"downloadvoice" . $ante['id'] . "\" class=\"btn btn-warning btn-sm\" type=\"submit\">PDF</button><input type=\"number\" name=\"deptvoice\" value=\"" . $ante['id'] . "\" hidden></form>";
			}else{
			$pdfbutton4 = '';
			}
			$jobArray[] = array(
				'unit' => $unitName,
				'desc' => " Voice of the Unit",
				'pdf' => $pdfbutton4
            );
						
			
		if($getEsc ?? ''){
			$pdfbutton5 = "<form id=\"escalationFormwhp" . $ante['id'] . "\" name=\"escalationFormwhp" . $ante['id'] . "\" action=\"inc/reports.php?req=allescalation&r=" . $ante['id'] . "\" method=\"post\"><button id=\"downloadescalation" . $ante['id'] . "\" class=\"btn btn-danger btn-sm\" type=\"submit\">PDF</button><input type=\"number\" name=\"deptescalation\" value=\"" . $ante['id'] . "\" hidden></form>";
			}else{
			$pdfbutton5 ='';
			}		
			
			$jobArray[] = array(
				'unit' => $unitName,
				'desc' => " Escalation Summary",
				'pdf' => $pdfbutton5
            );
			}
			
			//$outJobs[] = $ante;
		}	
		
		$outData = array();
		$outData['data'] = $jobArray;
		echo json_encode($outData,JSON_NUMERIC_CHECK);
		}else {
        $outData = array();
		$jobArray[] = array(
				'unit' => '',
				'desc' => '',
				'pdf' => ''
            );
        $outData['data'] = $jobArray;
        echo json_encode($outData,JSON_NUMERIC_CHECK);
		}
}


/////////////////////////////////////
// GET PERFORMANCE INFO
function GetPerformanceTableData2() {
	$idsearch = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
	$shift = isset($_REQUEST['shift']) ? $_REQUEST['shift'] : '';
	$deptId = isset($_REQUEST['dept']) ? $_REQUEST['dept'] : '';
    $startDate = isset($_REQUEST['start']) ? $_REQUEST['start'] : date('yyyy-mm-dd');
    $endDate = isset($_REQUEST['end']) ? $_REQUEST['end'] : date('yyyy-mm-dd');
	$group = isset($_REQUEST['group']) ? $_REQUEST['group'] : 0;
    $userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
	$enterpriseId = Config::get('account')['enterpriseId'];
	//$deptId = Config::get('account')['deptid'];
	$role = Config::get('account')['role'];
	//echo $idsearch;
	//echo $deptId;
	
	if(intval($group)==0 && !$idsearch){
		$groupby = "group by n.deptId";
		$searchAdd = '';
	}else if(intval($group)==1 && !$idsearch){
		$groupby = "group by n.userId";
		$searchAdd = '';
	}else if(intval($group)==2 && !$idsearch){
		$groupby = "group by n.deptId, n.shift";
		$searchAdd = '';
	}else if(intval($idsearch)==1 && intval($deptId)>0){
		$searchAdd = "AND n.deptId={$deptId}";
		$groupby = "group by n.deptId, n.userId";
	}else if(intval($idsearch)==2 && intval($deptId)>0){
		$searchAdd = "AND n.deptId={$deptId} AND n.shift={$shift}";
		$groupby = "group by n.deptId, n.dayDate, n.shift";
	}else if(intval($idsearch)>2){
		$searchAdd = "AND n.userId={$idsearch}";
		$groupby = "group by n.dayDate, n.deptId, n.shift";
	}else{
		$searchAdd = '';
		$groupby = "group by n.deptId, n.dayDate, n.shift";
	}
	
	if(intval($role)<5){
	$dbData = Config::get('db')->get_results("SELECT n.id as nid, n.shift, n.dayDate, d.rnVariance, n.deptId, s.shift as shiftName, AVG(NULLIF(n.nvariance ,0)) as avgprod, AVG(NULLIF(n.hrsVariance ,0)) as avghrs, AVG(NULLIF(n.gvariance ,0)) as avggrid, AVG(NULLIF(n.gridrnvariance ,0)) as avggridrn, SUM(n.nvariance) as runningVar, AVG(n.actualWHP) as avgWHP, n.userId, n.entered as lastEntered, AVG(n.avariance + n.lvariance ) as avgvar, AVG(n.atotal) as avgpat, count(n.id) as records, u.first_name, u.last_name, d.id as did, d.hppd, d.viewPage, d.dept as depName, d.prodMeasure FROM `productiveNewData` n LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as d on d.id = n.deptId LEFT JOIN `productiveUser` as u on u.id = n.userId WHERE d.active=1 AND n.userId>0 AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}' AND n.accountId={$accountId} AND n.userId={$userId} $groupby order by `avgprod` DESC");	
	}else if(intval($role)>=5 && intval($role)<8){
    $dbData = Config::get('db')->get_results("SELECT n.id as nid, n.shift, n.dayDate, d.rnVariance, n.deptId, s.shift as shiftName, AVG(NULLIF(n.nvariance ,0)) as avgprod, AVG(NULLIF(n.hrsVariance ,0)) as avghrs, AVG(NULLIF(n.gvariance ,0)) as avggrid, AVG(NULLIF(n.gridrnvariance ,0)) as avggridrn, SUM(n.nvariance) as runningVar, AVG(n.actualWHP) as avgWHP, n.userId, n.entered as lastEntered, AVG(n.avariance + n.lvariance ) as avgvar, AVG(n.atotal) as avgpat, count(n.id) as records, u.first_name, u.last_name, d.id as did, d.hppd, d.viewPage, d.dept as depName, d.prodMeasure FROM `productiveNewData` n LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as d on d.id = n.deptId LEFT JOIN `productiveUser` as u on u.id = n.userId WHERE d.active=1 AND n.userId>0 AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}' AND n.accountId={$accountId} AND n.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId} and `primaryUnit`>=5) $searchAdd $groupby order by `avgprod` DESC");
	}else if(intval($role)>=8 && intval($role)<=12){
    $dbData = Config::get('db')->get_results("SELECT n.id as nid, n.shift, n.dayDate, d.rnVariance, n.deptId, s.shift as shiftName, AVG(NULLIF(n.nvariance ,0)) as avgprod, AVG(NULLIF(n.hrsVariance ,0)) as avghrs, AVG(NULLIF(n.gvariance ,0)) as avggrid, AVG(NULLIF(n.gridrnvariance ,0)) as avggridrn, SUM(n.nvariance) as runningVar, AVG(n.actualWHP) as avgWHP, n.userId, n.entered as lastEntered, AVG(n.avariance + n.lvariance ) as avgvar, AVG(n.atotal) as avgpat, count(n.id) as records, u.first_name, u.last_name, d.id as did, d.hppd, d.viewPage, d.dept as depName, d.prodMeasure, a.id FROM `productiveNewData` n LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as d on d.id = n.deptId LEFT JOIN `productiveUser` as u on u.id = n.userId LEFT JOIN `productiveAccount` as a on a.id = d.accountId WHERE d.active=1 AND n.userId>0 AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}' AND n.accountId={$accountId} $searchAdd $groupby order by `avgprod` DESC");
	}else if(intval($role)==13){
    $dbData = Config::get('db')->get_results("SELECT n.id as nid, n.shift, n.dayDate, d.rnVariance, n.deptId, s.shift as shiftName, AVG(NULLIF(n.nvariance ,0)) as avgprod, AVG(NULLIF(n.hrsVariance ,0)) as avghrs, AVG(NULLIF(n.gvariance ,0)) as avggrid, AVG(NULLIF(n.gridrnvariance ,0)) as avggridrn, SUM(n.nvariance) as runningVar, AVG(n.actualWHP) as avgWHP, n.userId, n.entered as lastEntered, AVG(n.avariance + n.lvariance ) as avgvar, AVG(n.atotal) as avgpat, count(n.id) as records, u.first_name, u.last_name, d.id as did, d.hppd, d.viewPage, d.dept as depName, d.prodMeasure, a.id FROM `productiveNewData` n LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as d on d.id = n.deptId LEFT JOIN `productiveUser` as u on u.id = n.userId LEFT JOIN `productiveAccount` as a on a.id = d.accountId WHERE d.active=1 AND n.userId>0 AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}' AND u.enterpriseId={$enterpriseId} AND n.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId}) $searchAdd $groupby order by `avgprod` DESC");
	}else if(intval($role)>=14){
    $dbData = Config::get('db')->get_results("SELECT n.id as nid, n.shift, n.dayDate, d.rnVariance, n.deptId, s.shift as shiftName, AVG(NULLIF(n.nvariance ,0)) as avgprod, AVG(NULLIF(n.hrsVariance ,0)) as avghrs, AVG(NULLIF(n.gvariance ,0)) as avggrid, AVG(NULLIF(n.gridrnvariance ,0)) as avggridrn, SUM(n.nvariance) as runningVar, AVG(n.actualWHP) as avgWHP, n.userId, n.entered as lastEntered, AVG(n.avariance + n.lvariance ) as avgvar, AVG(n.atotal) as avgpat, count(n.id) as records, u.first_name, u.last_name, d.id as did, d.hppd, d.viewPage, d.dept as depName, d.prodMeasure, a.id FROM `productiveNewData` n LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as d on d.id = n.deptId LEFT JOIN `productiveUser` as u on u.id = n.userId LEFT JOIN `productiveAccount` as a on a.id = d.accountId WHERE d.active=1 AND n.userId>0 AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}' AND u.enterpriseId={$enterpriseId} $searchAdd $groupby order by `avgprod` DESC");
	}else{
	$dbData='';
	}
	
    if($dbData){
		$outJobs = array();
        $jobArray = array();
		foreach($dbData as $ante){
			
			if(intval($ante['rnVariance'])==1){
				$measure = "Hours";
				$prod = round($ante['avghrs'],1);
			}else if(intval($ante['rnVariance'])==2){
				$measure = "Grid Total";
				$prod = round($ante['avggrid'],1);
			}else if(intval($ante['rnVariance'])==3){
				$measure = "Grid RN";
				$prod = round($ante['avggridrn'],1);
			}else{
				$measure = "RN";
				$prod = round($ante['avgprod'],1);
			}
			
			if(intval($idsearch)==1){
			$user = "<a href=\"#performance?id=".$ante['userId']."&d=".$ante['deptId']."\" >" . $ante['last_name'].", ".$ante['first_name'] . "</a>";
			$ndate = '';
			$unit = $ante['depName'];
			//$ndate = $ante['lastEntered'];
			}else if(intval($idsearch)==2){
			$user = "<a href=\"#reportView?i=" . $ante['nid'] . "\">" . $ante['last_name'].", ".$ante['first_name'] . "</a>";
			$ndate = $ante['dayDate'] . " - " . $ante['shiftName'];
			$unit = $ante['depName'];
			}else if(intval($idsearch)>3){
			$user = "<a href=\"#reportView?i=" . $ante['nid'] . "\">" . $ante['last_name'].", ".$ante['first_name'] . "</a>";
			$ndate = $ante['dayDate'] . " - " . $ante['shiftName'];
			$unit = $ante['depName'];
			//$ndate = $ante['lastEntered'];
			}else if(!$idsearch && intval($group)==1){
			$user = "<a href=\"#performance?id=".$ante['userId']."&d=".$ante['deptId']."\" >" . $ante['last_name'].", ".$ante['first_name'] . "</a>";
			$ndate = '';
			$unit = $ante['depName'];
			}else if(!$idsearch && intval($group)==2){
			$user = '';
			$ndate = $ante['shiftName'];
			$unit = "<a href=\"#performance?id=2&s=".$ante['shift']."&d=".$ante['deptId']."\" >" . $ante['depName'] . "</a>";
			}else{
			$user = '';
			$ndate = '';
			$unit = "<a href=\"#performance?id=1&d=".$ante['deptId']."\" >" . $ante['depName'] . "</a>";
			}
			
					
			if(intval($ante['prodMeasure'])!=2){
			$var = '';
			$hppd = '';
			
			//$ndate = $ante['lastEntered'];
			$avgpat = '';
			$count = intval($ante['records']);
			$style = 0;
			$header1 = "Avg. Variance";
			$header2 = "Avg. Patients";
			}else{
			
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
				'header2'=> $header2,
				'measure'=> $measure
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
	$dbData = Config::get('db')->get_results("SELECT n.id as nid, n.dayDate, n.deptId, s.shift as shiftName, AVG(NULLIF(n.hrsVariance ,0)) as avgprod, SUM(n.nvariance) as runningVar, AVG(n.actualWHP) as avgWHP, n.userId, n.entered as lastEntered, AVG(n.avariance + n.lvariance ) as avgvar, AVG(n.atotal) as avgpat, count(n.id) as records, u.first_name, u.last_name, d.id as did, d.hppd, d.viewPage, d.dept as depName, d.prodMeasure FROM `productiveNewData` n LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as d on d.id = n.deptId LEFT JOIN `productiveUser` as u on u.id = n.userId WHERE d.active>0 AND n.userId>0 AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}' AND n.accountId={$accountId} AND n.whpPlan !=1 AND n.userId={$userId} group by n.userId, n.deptId order by `avgprod` DESC");	
	}else if(intval($role)>=5 && intval($role)<=8 && !$idsearch){
    //$dbData = Config::get('db')->get_results("SELECT n.id as nid, AVG(NULLIF(n.nproductivity ,0)) as avgprod, SUM(n.nvariance) as runningVar, AVG(n.actualWHP) as avgWHP, n.userId, n.entered, DATE_FORMAT(z.entered,'%m/%d/%Y') as lastEntered, AVG(n.avariance + n.lvariance ) as avgvar, AVG(n.atotal+n.ltotal) as avgpat, count(n.id) as records, u.first_name, u.last_name, d.id as did, d.hppd, d.viewPage, d.dept as depName, d.prodMeasure FROM `productiveNewData` n LEFT JOIN `productiveNewData` as z on z.id =(SELECT `id` from `productiveNewData` where `userId` = n.userId order by `id` DESC LIMIT 1) LEFT JOIN `ProductiveDept` as d on d.id = n.deptId LEFT JOIN `productiveUser` as u on u.id = n.userId WHERE n.userId>0 AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}' AND n.accountId={$accountId} AND n.whpPlan !=1 AND n.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId} and `primaryUnit`>5) group by n.userId order by `avgprod` DESC");
	$dbData = Config::get('db')->get_results("SELECT n.id as nid, n.dayDate, n.deptId, s.shift as shiftName, AVG(NULLIF(n.hrsVariance ,0)) as avgprod, SUM(n.nvariance) as runningVar, AVG(n.actualWHP) as avgWHP, n.userId, n.entered as lastEntered, AVG(n.avariance + n.lvariance ) as avgvar, AVG(n.atotal) as avgpat, count(n.id) as records, u.first_name, u.last_name, d.id as did, d.hppd, d.viewPage, d.dept as depName, d.prodMeasure FROM `productiveNewData` n LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as d on d.id = n.deptId LEFT JOIN `productiveUser` as u on u.id = n.userId WHERE d.active>0 AND n.userId>0 AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}' AND n.accountId={$accountId} AND n.whpPlan !=1 AND n.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId} and `primaryUnit`>5) group by n.userId, n.deptId order by `avgprod` DESC");
	}else if(intval($role)>8 && !$idsearch){
    $dbData = Config::get('db')->get_results("SELECT n.id as nid, n.dayDate, n.deptId, s.shift as shiftName, AVG(NULLIF(n.hrsVariance ,0)) as avgprod, SUM(n.nvariance) as runningVar, AVG(n.actualWHP) as avgWHP, n.userId, n.entered as lastEntered, AVG(n.avariance + n.lvariance ) as avgvar, AVG(n.atotal) as avgpat, count(n.id) as records, u.first_name, u.last_name, d.id as did, d.hppd, d.viewPage, d.dept as depName, d.prodMeasure, a.id FROM `productiveNewData` n LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as d on d.id = n.deptId LEFT JOIN `productiveUser` as u on u.id = n.userId LEFT JOIN `productiveAccount` as a on a.id = d.accountId WHERE d.active>0 AND n.userId>0 AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}' AND n.whpPlan !=1 AND n.accountId={$accountId} group by n.userId, n.deptId order by `avgprod` DESC");
	}else if(intval($role)<=8 && intval($role)>=5 && $idsearch){
	$dbData = Config::get('db')->get_results("SELECT n.id as nid, n.dayDate, n.deptId, s.shift as shiftName, AVG(NULLIF(n.hrsVariance ,0)) as avgprod, SUM(n.nvariance) as runningVar, AVG(n.actualWHP) as avgWHP, n.userId, n.entered as lastEntered, AVG(n.avariance + n.lvariance ) as avgvar, AVG(n.atotal) as avgpat, count(n.id) as records, u.first_name, u.last_name, d.id as did, d.hppd, d.viewPage, d.dept as depName, d.prodMeasure FROM `productiveNewData` n LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as d on d.id = n.deptId LEFT JOIN `productiveUser` as u on u.id = n.userId WHERE d.active>0 AND n.userId>0 AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}' AND n.accountId={$accountId} AND n.userId={$idsearch} and n.deptId={$deptId} AND n.whpPlan !=1 AND n.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId}) group by n.dayDate, n.deptId, n.shift order by n.dayDate DESC, n.shift DESC");		
	}else if(intval($role)<5 && $idsearch){
	$dbData = Config::get('db')->get_results("SELECT n.id as nid, n.dayDate, n.deptId, s.shift as shiftName, AVG(NULLIF(n.hrsVariance ,0)) as avgprod, SUM(n.nvariance) as runningVar, AVG(n.actualWHP) as avgWHP, n.userId, n.entered as lastEntered, AVG(n.avariance + n.lvariance ) as avgvar, AVG(n.atotal) as avgpat, count(n.id) as records, u.first_name, u.last_name, d.id as did, d.hppd, d.viewPage, d.dept as depName, d.prodMeasure FROM `productiveNewData` n LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as d on d.id = n.deptId LEFT JOIN `productiveUser` as u on u.id = n.userId WHERE d.active>0 AND n.userId>0 AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}' AND n.accountId={$accountId} AND n.whpPlan !=1 AND n.userId={$idsearch} and n.deptId={$deptId} group by n.dayDate, n.shift order by n.dayDate DESC, n.shift DESC");		
	}else if(intval($role)>8 && $idsearch){
	$dbData = Config::get('db')->get_results("SELECT n.id as nid, n.dayDate, n.deptId, s.shift as shiftName, AVG(NULLIF(n.hrsVariance ,0)) as avgprod, SUM(n.nvariance) as runningVar, AVG(n.actualWHP) as avgWHP, n.userId, n.entered as lastEntered, AVG(n.avariance + n.lvariance ) as avgvar, AVG(n.atotal) as avgpat, count(n.id) as records, u.first_name, u.last_name, d.id as did, d.hppd, d.viewPage, d.dept as depName, d.prodMeasure FROM `productiveNewData` n LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as d on d.id = n.deptId LEFT JOIN `productiveUser` as u on u.id = n.userId WHERE d.active>0 AND n.userId>0 AND n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}' AND n.accountId={$accountId} AND n.whpPlan !=1 AND n.userId={$idsearch} and n.deptId={$deptId} group by n.dayDate, n.deptId, n.shift order by n.dayDate DESC, n.shift DESC");		
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
	}else if(intval($role)>7 && intval($role)<14 && !$idsearch){
		$searchAdd = " AND n.accountId={$accountId}";
	}else if(Intval($role)>=14 && !$idsearch){
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

function GetchartData() {
	$deptId = (isset($_REQUEST['deptId'])?$_REQUEST['deptId']:'');
    $startDate = isset($_REQUEST['start']) ? $_REQUEST['start'] : date('yyyy-mm-dd');
    $endDate = isset($_REQUEST['end']) ? $_REQUEST['end'] : date('yyyy-mm-dd');
    $userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
	$role = Config::get('account')['role'];
	$dow = (isset($_REQUEST['dow'])?$_REQUEST['dow']:8);
	$dbDays = array();
	$dbNights = array();

	if(intval($deptId)>0 && intval($dow)==8){
    $dbDays = Config::get('db')->get_results("SELECT n.dayDate, AVG(n.nvariance) FROM `productiveNewData` n
        LEFT JOIN `productiveUser` as u on u.id={$userId}
		WHERE
		n.deptId={$deptId} AND n.userId>0 AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate
		GROUP BY n.dayDate
        ORDER BY n.dayDate ASC");
	$dbNights = array();
	}else if(intval($deptId)>0 && intval($dow)==9){	
	$dbDays = Config::get('db')->get_results("SELECT n.dayDate, AVG(n.nvariance) FROM `productiveNewData` n
        LEFT JOIN `productiveUser` as u on u.id={$userId}
		WHERE
		n.deptId={$deptId} AND n.userId>0 AND n.shift<=38 AND n.shift>14 AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate
		GROUP BY n.dayDate
        ORDER BY n.dayDate ASC");
	$dbNights = Config::get('db')->get_results("SELECT n.dayDate, AVG(n.nvariance) FROM `productiveNewData` n
        LEFT JOIN `productiveUser` as u on u.id={$userId}
		WHERE
		n.deptId={$deptId} AND n.userId>0 AND (n.shift>38 OR n.shift<=14) AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate
		GROUP BY n.dayDate
        ORDER BY n.dayDate ASC");
	}else{
	 $dbDays = Config::get('db')->get_results("SELECT n.dayDate, AVG(n.nvariance) FROM `productiveNewData` n
        LEFT JOIN `productiveUser` as u on u.id={$userId}
		WHERE
		n.deptId={$deptId} AND n.userId>0 AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate
		GROUP BY n.dayDate
        ORDER BY n.dayDate ASC");
	$dbNights = array();		
	}
	echo json_encode(array('success' => true,'days'=>$dbDays,'nights'=>$dbNights), JSON_NUMERIC_CHECK);
		
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
	$demoMode = Config::get('account')['demoMode'];
	$role = Config::get('account')['role'];
	$enterpriseId = Config::get('account')['enterpriseId'];
	$activeDept = Config::get('account')['activeDept'];
	
	if(intval($role)<=7){
		$searchAdd = "(d.active=1 OR d.active=2 OR d.showArchive=1) AND d.unitCategory !=4 AND d.id in (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId} and `primaryUnit`>0)";
	}else if((intval($role)>7 && intval($role)<=10) || (intval($role)==11 && intval($demoMode)==1) || (intval($role)==12 && intval($demoMode)==1)){
		$searchAdd = "(d.active=1 OR d.active=2 OR d.showArchive=1) AND d.unitCategory !=4 AND d.accountId={$accountId}";
	}else if((intval($role)==11 && intval($demoMode)==0) || (intval($role)==12 && intval($demoMode)==0)){
		$searchAdd = "(d.active=1 OR d.active=2 OR d.showArchive=1) AND d.unitCategory !=4 AND d.accountId={$accountId}";
	}else if(intval($role)==13){
		$searchAdd = "(d.active=1 OR d.active=2 OR d.showArchive=1) AND d.unitCategory !=4 AND a.enterpriseId={$enterpriseId} AND d.id in (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId} and `primaryUnit`>0)";
	}else if(intval($role)>=14){
		$searchAdd = "(d.active=1 OR d.active=2 OR d.showArchive=1) AND d.unitCategory !=4 AND a.enterpriseId={$enterpriseId}";
	}else{
		$searchAdd = "(d.active=1 OR d.active=2 OR d.showArchive=1) AND d.unitCategory !=4 AND d.accountId={$accountId}";	
	}


    $dbData = Config::get('db')->get_results("SELECT d.*, IFNULL(l.active,'0') as logActive, IFNULL(l.logType,'0') as logType, a.enterpriseId, a.nickName, IFNULL((SELECT MAX(`dayDate`) FROM `productiveNewData` where `deptId`=d.id AND `userId`>0),'') as lastDay
        FROM
            `ProductiveDept` d
		LEFT JOIN 
			`productiveAccount` as a on a.id = d.accountId
		LEFT OUTER JOIN 
			`productivelogData` as l on l.deptId = d.id and l.logType=1
        WHERE
            $searchAdd
		GROUP BY d.id
        ORDER BY d.dept DESC");
		
    if($dbData){
		$outJobs = array();
        $jobArray = array();
		
		if(intval($role)>7){
		$allName = "<a href=\"javascript:;\" onclick=\"tj.emailDist(" . $dbData[0]['accountId'] . ",0);\"> All Units</a>";
		$pdfbutton = "<form id=\"dateFormwhp0\" name=\"dateFormwhp0\" action=\"inc/reports.php?req=rprt&r=0\" method=\"post\"><button id=\"downloadPDF0\" class=\"btn btn-info btn-sm\" type=\"submit\" >PDF</button><input type=\"number\" name=\"deptNum\" value=\"0\" hidden></form>";
		$jobArray[] = array(
				'unit' => $allName,
				'desc' => "Daily Shift Report Details",
				'type' => $pdfbutton,
				'type2' => ''
            );
		}
		if(intval($activeDept)>1){
		$allName = "<a href=\"javascript:;\" onclick=\"tj.emailDist(" . $dbData[0]['accountId'] . ",1);\"> Multi-Unit Leaders</a>";
		$pdfbutton = "<form id=\"dateFormwhp1\" name=\"dateFormwhp1\" action=\"inc/reports.php?req=rprt&r=1\" method=\"post\"><button id=\"downloadPDF1\" class=\"btn btn-info btn-sm\" type=\"submit\" >PDF</button><input type=\"number\" name=\"deptNum\" value=\"1\" hidden></form>";
		$jobArray[] = array(
				'unit' => $allName,
				'desc' => "Daily Shift Report Details",
				'type' => $pdfbutton,
				'type2' => ''
            );	
			
		}
		
		/*	
			if(intval($role)<=7){
				$searchAdd = "WHERE e.active>0 AND e.accountId ={$accountId} AND d.active=1 AND d.id in (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId} and `primaryUnit`>0) AND DATE(e.dateSubmitted) >=y.lastStartDate AND DATE(e.dateSubmitted) <=y.lastEndDate";
			}else{
				$searchAdd = "WHERE e.active>0 AND e.accountId ={$accountId} AND d.active=1 AND DATE(e.dateSubmitted) >=y.lastStartDate AND DATE(e.dateSubmitted) <=y.lastEndDate";
			}
			
			$allesc = Config::get('db') -> get_results("select e.*, DATE_FORMAT(e.dateSubmitted,'%m%d%y') as newDate, c.last_name as closedLast, c.first_name as closedFirst, a.escalation as escType, IFNULL(n.submittedby,'') as submittedby, d.dept, u.last_name, u.first_name FROM `productiveEscalations` e LEFT OUTER JOIN `productiveUser` as c on c.id = e.closedId LEFT JOIN `productiveAcctEscalations` as a on a.id=e.escalation LEFT OUTER JOIN `productiveNewData` as n on n.id=e.dataId LEFT JOIN `productiveUser` as y on y.id = {$userId} LEFT JOIN `ProductiveDept` as d on d.id=e.deptId LEFT OUTER JOIN `productiveUser` as u on u.id=e.userId $searchAdd group by e.id ORDER BY e.dateSubmitted DESC");
			
			if($allesc ?? ''){
			$escpdfbutton = "<form id=\"dateFormesc0\" name=\"dateFormesc0\" action=\"inc/reports.php?req=allescalation&r=0\" method=\"post\"><button id=\"downloadESC0\" class=\"btn btn-info btn-sm\" type=\"submit\" >PDF</button></form>";
			}else{
			$escpdfbutton = '';
			}	
		
		if(intval($role)>7){
		$allName = "<a href=\"javascript:;\" onclick=\"tj.emailDist(" . $dbData[0]['accountId'] . ",2);\"> Escalations</a>";
		$jobArray[] = array(
				'unit' => $allName,
				'desc' => "Daily Escalations Summary",
				'type' => $escpdfbutton,
				'type2' => ''
            );
		}
		*/
		foreach($dbData as $ante){
		$activeDept = intval($ante['active']);
		$deptId = intval($ante['id']);
		
			if($activeDept==0 && intval($ante['lastDay'])==''){
				$nameAdd = "<span style=\"color:red\"> (Inactive)</span>";	
			}else if($activeDept==0){
				$nameAdd = "<span style=\"color:red\"> (Inactive as of ". $ante['lastDay'] ." )</span>";
			}else if($activeDept==2){
				$nameAdd = "<span style=\"color:red\"> (Customer Setup)</span>";
			}else if($activeDept==3){
				$nameAdd = "<span style=\"color:red\"> (Admin Setup)</span>";
			}else{
				$nameAdd = '';
			}
		
			
		$accountName = $ante['nickName'];
			if(intval($role)>4){
			$unitName = "<a href=\"javascript:;\" onclick=\"tj.emailDist(" . $ante['accountId'] . "," . $ante['id'] . ");\">" . $ante['dept'].$nameAdd . "</a>";
			}else{
			$unitName = $ante['dept'].$nameAdd;	
			}
			
			if(intval($role)>4){
			//$pdfbutton = "<form><div class=\"form-group\"><div id=\"dateFormwhp" . $ante['id'] . "\" name=\"dateFormwhp" . $ante['id'] . "\" action=\"inc/reports.php?req=rprt&r=" . $ante['id'] . "\" method=\"post\"><button id=\"downloadPDF" . $ante['id'] . "\" class=\"btn btn-info btn-sm\" type=\"submit\">PDF</button><input type=\"number\" name=\"deptNum\" value=\"" . $ante['id'] . "\" hidden></div>&nbsp;<div id=\"csvFormwhp" . $ante['id'] . "\" name=\"csvFormwhp" . $ante['id'] . "\" action=\"inc/reports.php?req=reportCSV&x=" . $ante['id'] . "\" method=\"post\"><button id=\"downloadcsv" . $ante['id'] . "\" class=\"btn btn-success btn-sm\" type=\"submit\">CSV</button><input type=\"number\" name=\"deptCSV\" value=\"" . $ante['id'] . "\" hidden></div>&nbsp;<div id=\"dateShift" . $ante['id'] . "\" name=\"dateShift" . $ante['id'] . "\" action=\"inc/reports.php?req=shft&r=" . $ante['id'] . "\" method=\"post\"><button id=\"downloadShift" . $ante['id'] . "\" class=\"btn btn-info btn-sm\" type=\"submit\">Shift</button><input type=\"number\" name=\"deptNumShift\" value=\"" . $ante['id'] . "\" hidden></div></div></form>";
			$pdfbutton = "<form id=\"dateFormwhp" . $ante['id'] . "\" name=\"dateFormwhp" . $ante['id'] . "\" action=\"inc/reports.php?req=rprt&r=" . $ante['id'] . "\" method=\"post\"><button id=\"downloadPDF" . $ante['id'] . "\" class=\"btn btn-info btn-sm\" type=\"submit\">PDF</button><input type=\"number\" name=\"deptNum\" value=\"" . $ante['id'] . "\" hidden></form>";
			$pdfbutton2 = "<form id=\"csvFormwhp" . $ante['id'] . "\" name=\"csvFormwhp" . $ante['id'] . "\" action=\"inc/reports.php?req=reportCSV&x=" . $ante['id'] . "\" method=\"post\"><button id=\"downloadcsv" . $ante['id'] . "\" class=\"btn btn-success btn-sm\" type=\"submit\">CSV</button><input type=\"number\" name=\"deptCSV\" value=\"" . $ante['id'] . "\" hidden></form>";
			}else{
			$pdfbutton = "<form id=\"dateFormwhp" . $ante['id'] . "\" name=\"dateFormwhp" . $ante['id'] . "\" action=\"inc/reports.php?req=rprt&r=" . $ante['id'] . "\" method=\"post\"><button id=\"downloadPDF" . $ante['id'] . "\" class=\"btn btn-info btn-sm\" type=\"submit\">PDF</button><input type=\"number\" name=\"deptNum\" value=\"" . $ante['id'] . "\" hidden></form>";
			$pdfbutton2 = '';
			}
			
			$jobArray[] = array(
				'unit' => $unitName,
				'desc' => $ante['dept'] . " Shift Report Details",
				'type' => $pdfbutton,
				'type2' => $pdfbutton2
            );
			}
			
		foreach($dbData as $ante){
		$activeDept = intval($ante['active']);
		$deptId = intval($ante['id']);
		
			if($activeDept==0 && intval($ante['lastDay'])==''){
				$nameAdd = "<span style=\"color:red\"> (Inactive)</span>";	
			}else if($activeDept==0){
				$nameAdd = "<span style=\"color:red\"> (Inactive as of ". $ante['lastDay'] ." )</span>";
			}else if($activeDept==2){
				$nameAdd = "<span style=\"color:red\"> (Customer Setup)</span>";
			}else if($activeDept==3){
				$nameAdd = "<span style=\"color:red\"> (Admin Setup)</span>";
			}else{
				$nameAdd = '';
			}
		
			
		$accountName = $ante['nickName'];
			if(intval($role)>4){
			$unitName = "<a href=\"javascript:;\" onclick=\"tj.emailDist(" . $ante['accountId'] . "," . $ante['id'] . ");\">" . $ante['dept'].$nameAdd . "</a>";
			}else{
			$unitName = $ante['dept'].$nameAdd;	
			}
			
			if(intval($role)>4 && intval($ante['logType'])==1){
			//$pdfbutton = "<form><div class=\"form-group\"><div id=\"dateFormwhp" . $ante['id'] . "\" name=\"dateFormwhp" . $ante['id'] . "\" action=\"inc/reports.php?req=rprt&r=" . $ante['id'] . "\" method=\"post\"><button id=\"downloadPDF" . $ante['id'] . "\" class=\"btn btn-info btn-sm\" type=\"submit\">PDF</button><input type=\"number\" name=\"deptNum\" value=\"" . $ante['id'] . "\" hidden></div>&nbsp;<div id=\"csvFormwhp" . $ante['id'] . "\" name=\"csvFormwhp" . $ante['id'] . "\" action=\"inc/reports.php?req=reportCSV&x=" . $ante['id'] . "\" method=\"post\"><button id=\"downloadcsv" . $ante['id'] . "\" class=\"btn btn-success btn-sm\" type=\"submit\">CSV</button><input type=\"number\" name=\"deptCSV\" value=\"" . $ante['id'] . "\" hidden></div>&nbsp;<div id=\"dateShift" . $ante['id'] . "\" name=\"dateShift" . $ante['id'] . "\" action=\"inc/reports.php?req=shft&r=" . $ante['id'] . "\" method=\"post\"><button id=\"downloadShift" . $ante['id'] . "\" class=\"btn btn-info btn-sm\" type=\"submit\">Shift</button><input type=\"number\" name=\"deptNumShift\" value=\"" . $ante['id'] . "\" hidden></div></div></form>";
			$pdfbutton = "<form id=\"dateFormwhp" . $ante['id'] . "\" name=\"dateFormwhp" . $ante['id'] . "\" action=\"inc/reports.php?req=allcrash&r=" . $ante['id'] . "\" method=\"post\"><button id=\"downloadPDF" . $ante['id'] . "\" class=\"btn btn-info btn-sm\" type=\"submit\">PDF</button><input type=\"number\" name=\"deptNum\" value=\"" . $ante['id'] . "\" hidden></form>";
			$pdfbutton2 = "<form id=\"csvFormwhp" . $ante['id'] . "\" name=\"csvFormwhp" . $ante['id'] . "\" action=\"inc/reports.php?req=crashCSV&x=" . $ante['id'] . "\" method=\"post\"><button id=\"downloadcsv" . $ante['id'] . "\" class=\"btn btn-success btn-sm\" type=\"submit\">CSV</button><input type=\"number\" name=\"deptCSV\" value=\"" . $ante['id'] . "\" hidden></form>";
			//$pdfbutton2 = '';
			}else if(intval($ante['logType'])==0){
			$pdfbutton = '';
			$pdfbutton2 = '';
			}else{
			$pdfbutton = "<form id=\"dateFormwhp" . $ante['id'] . "\" name=\"dateFormwhp" . $ante['id'] . "\" action=\"inc/reports.php?req=allcrash&r=" . $ante['id'] . "\" method=\"post\"><button id=\"downloadPDF" . $ante['id'] . "\" class=\"btn btn-info btn-sm\" type=\"submit\">PDF</button><input type=\"number\" name=\"deptNum\" value=\"" . $ante['id'] . "\" hidden></form>";
			$pdfbutton2 = '';	
			}
			
			$jobArray[] = array(
				'unit' => $unitName,
				'desc' => $ante['dept'] . " Crash Cart Logs",
				'type' => $pdfbutton,
				'type2' => $pdfbutton2
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
// email Change

function EmailChange() {
    $accountId = Config::get('account')['accountId'];
	$role = Config::get('account')['role'];
	$user = Config::get('account')['id'];
	$enterpriseId = Config::get('account')['enterpriseId'];
	$deptId = isset($_REQUEST['deptId']) ? $_REQUEST['deptId'] : 0;
	$userId = isset($_REQUEST['userId']) ? $_REQUEST['userId'] : 0;
	$recordId = isset($_REQUEST['recordId']) ? $_REQUEST['recordId'] : 0;
	$sendEmail = isset($_REQUEST['sendEmail']) ? $_REQUEST['sendEmail'] : 0;
	$curTime = (isset($_REQUEST['currentTime'])) ? $_REQUEST['currentTime'] : time();
	$viewDate = strtotime($curTime);
	
	if(intval($userId)>0){
	$dbEmail = Config::get('db')->get_results("select * FROM `productiveUser` where `id`= {$userId}");
	$email = trim($dbEmail[0]['email2']);
	$first = $dbEmail[0]['first_name'];
	$last = $dbEmail[0]['last_name'];
    }else if(intval($recordId)>0){
	$dbEmail = Config::get('db')->get_results("select * FROM `productiveEmailDist` where `id`= {$recordId}");
	$email = trim($dbEmail[0]['emailAddress']);
	}else{
	$email = '';
	}
	
	if(strlen($email ?? '')>5 && intval($sendEmail)==0){
		$deleteWhere = array(
					'emailAddress' => $email,
					'deptId' => $deptId,
					'accountId' => $accountId,
					'sendEmail' =>1
					
					);
		$delete = array(
					'sendEmail' =>0,
					'removedDate' =>date("Y-m-d H:i:s",$viewDate),
					'removedBy' => $user
					);
		Config::get('db')->update('productiveEmailDist',$delete,$deleteWhere);
	
	}else if(strlen($email ?? '')>5 && intval($sendEmail)==1 && intval($deptId)==1){
		$insert = array(
					'userId' => $userId,
					'deptId' => $deptId,
					'accountId' => $accountId,
					'sendEmail' => $sendEmail,
					'emailAddress' => $email,
					'first_name' => $first,
					'last_name' => $last,
					'addedDate' =>date("Y-m-d H:i:s",$viewDate),
					'addedBy' => $user,
					'unitsId' => $user
					);
		Config::get('db')->insert('productiveEmailDist',$insert);
	}else if(strlen($email ?? '')>5 && intval($sendEmail)==1 && intval($deptId)!=1){
		$insert = array(
					'userId' => $userId,
					'deptId' => $deptId,
					'accountId' => $accountId,
					'sendEmail' => $sendEmail,
					'emailAddress' => $email,
					'first_name' => $first,
					'last_name' => $last,
					'addedDate' =>date("Y-m-d H:i:s",$viewDate),
					'addedBy' => $user
					);
		Config::get('db')->insert('productiveEmailDist',$insert);
	}else{
		//do nothing;
	}	
	echo json_encode(array('success'=>true));
	return true;
	
}

/////////////////////////////////////
// Add Non User

function AddNonUser() {
    $accountId = Config::get('account')['accountId'];
	$role = Config::get('account')['role'];
	$user = Config::get('account')['id'];
	$enterpriseId = Config::get('account')['enterpriseId'];
	$deptId = isset($_REQUEST['deptId']) ? $_REQUEST['deptId'] : 0;
	$email = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
	$first = isset($_REQUEST['first']) ? $_REQUEST['first'] : '';
	$last = isset($_REQUEST['last']) ? $_REQUEST['last'] : '';
	$curTime = (isset($_REQUEST['currentTime'])) ? $_REQUEST['currentTime'] : time();
	$viewDate = strtotime($curTime);
		
	if(strlen($email ?? '')>5 && intval($deptId)!=1){
		$insert = array(
					'emailAddress' => $email,
					'deptId' => $deptId,
					'accountId' => $accountId,
					'first_name' => $first,
					'last_name' => $last,
					'sendEmail' => 1,
					'userId' => 0,
					'addedDate' =>date("Y-m-d H:i:s",$viewDate),
					'addedBy' => $user
					);
		Config::get('db')->insert('productiveEmailDist',$insert);
	
	}else if(strlen($email ?? '')>5 && intval($deptId)==1){
		$insert = array(
					'emailAddress' => $email,
					'deptId' => $deptId,
					'accountId' => $accountId,
					'first_name' => $first,
					'last_name' => $last,
					'sendEmail' => 1,
					'userId' => 0,
					'addedDate' =>date("Y-m-d H:i:s",$viewDate),
					'addedBy' => $user,
					'unitsId' => $user
					);
		Config::get('db')->insert('productiveEmailDist',$insert);
	
	}else{
		//do nothing;
	}
	echo json_encode(array('success'=>true));
	return true;	
	
}



/////////////////////////////////////
// Email Dist

function GetEmailDist() {
    $accountId = Config::get('account')['accountId'];
	$activeDept = Config::get('account')['activeDept'];
	$userId = intval(Config::get('account')['id']);
	$role = Config::get('account')['role'];
	$enterpriseId = Config::get('account')['enterpriseId'];
	$deptId = isset($_REQUEST['deptId']) ? $_REQUEST['deptId'] : 0;
	$outData = array();
	
	//if(intval($role)>6 && intval($role)!=9){
	//if(intval($deptId)==0 || intval($deptId)==2){
		$searchAdd = " AND u.role<={$role} AND u.role>=5 AND u.role<=7 AND u.role !=9 AND u.id in (SELECT `userId` from `productiveDeptXref` where `primaryUnit`>0 AND `deptId` IN (SELECT `deptId` FROM `productiveDeptXref` WHERE `userId`={$userId}))";
		$searchAdd2 = " AND (u.role>9 OR (u.role<={$role} AND u.role>=5 AND u.role!=9 AND u.id IN (SELECT `userId` FROM `productiveDeptXref` WHERE `deptId`={$deptId})))";
		$searchAdd3 = " AND u.role !=9";
	//}else if(intval($role)<7 && intval($role)!=9){
	//}else if(intval($deptId)==1){
		//$searchAdd = " AND u.role<={$role} AND u.role>=5 AND u.role<=7 AND u.id in (SELECT `userId` from `productiveDeptXref` where `primaryUnit`>0 AND `deptId` IN (SELECT `deptId` FROM `productiveDeptXref` WHERE `userId`={$userId}))";
		//$searchAdd2 = " AND u.role<={$role} AND u.role>=5 AND u.role<=7 AND u.id IN (SELECT `userId` FROM `productiveDeptXref` WHERE `deptId`={$deptId})";
		//$searchAdd3 = '';
	//}else{
		//$searchAdd = " AND u.id={$userId} ";
		//$searchAdd2 = " AND u.id={$userId} ";
		//$searchAdd3 = " AND d.unitsId={$userId} ";
	//}
		
		
		
	if(intval($deptId)==0){
		$dbData = Config::get('db')->get_results("SELECT u.id as userId, '0' as recordId, u.last_name as last_name, u.first_name as first_name, u.email2 as emailAddress, u.accountId, '0' as sendEmail, '0' as deptId FROM `productiveUser` u
		WHERE u.accountId={$accountId} AND u.active>0 AND u.email2 LIKE '%@%' AND u.role>=7 AND u.role<=11 AND u.id NOT IN (SELECT `userId` FROM `productiveEmailDist` WHERE `userId`=u.id AND `deptId`=0 AND `accountId`=u.accountId AND `sendEmail`=1)$searchAdd3
		UNION
		SELECT d.userId, d.id as recordId, d.last_name as last_name, d.first_name as first_name, d.emailAddress as emailAddress, d.accountId, d.sendEmail as sendEmail, d.deptId FROM `productiveEmailDist` d
		WHERE d.accountId={$accountId} AND d.deptId=0 AND d.emailAddress LIKE '%@%' AND d.sendEmail=1
		GROUP BY `emailAddress` ORDER BY `sendEmail` DESC, `last_name` ASC, `first_name` ASC
		");
	}else if(intval($deptId)==1){
		$dbData = Config::get('db')->get_results("SELECT u.id as userId, '0' as recordId, u.last_name as last_name, u.first_name as first_name, u.email2 as emailAddress, u.accountId, '0' as sendEmail, '1' as deptId FROM `productiveUser` u
		WHERE u.accountId={$accountId} AND u.active>0 AND u.email2 LIKE '%@%' AND u.id NOT IN (SELECT `userId` FROM `productiveEmailDist` WHERE `userId`=u.id AND `deptId`=1 AND `accountId`=u.accountId AND `sendEmail`=1)$searchAdd
		UNION
		SELECT d.userId, d.id as recordId, d.last_name as last_name, d.first_name as first_name, d.emailAddress as emailAddress, d.accountId, d.sendEmail as sendEmail, d.deptId FROM `productiveEmailDist` d
		WHERE d.accountId={$accountId} AND d.deptId=1 AND d.emailAddress LIKE '%@%' AND d.sendEmail=1
		GROUP BY `emailAddress` ORDER BY `sendEmail` DESC, `last_name` ASC, `first_name` ASC
		");
	}else if(intval($deptId)>2){
		$dbData = Config::get('db')->get_results("SELECT u.id as userId, '0' as recordId, u.last_name as last_name, u.first_name as first_name, u.email2 as emailAddress, u.accountId, '0' as sendEmail, '1' as deptId FROM `productiveUser` u
		WHERE u.accountId={$accountId} AND u.active>0 AND u.email2 LIKE '%@%' AND u.id NOT IN (SELECT `userId` FROM `productiveEmailDist` WHERE `userId`=u.id AND `deptId`=$deptId AND `accountId`=u.accountId AND `sendEmail`=1) $searchAdd2
		UNION
		SELECT d.userId, d.id as recordId, d.last_name as last_name, d.first_name as first_name, d.emailAddress as emailAddress, d.accountId, d.sendEmail as sendEmail, d.deptId FROM `productiveEmailDist` d
		WHERE d.accountId={$accountId} AND d.deptId=$deptId AND d.emailAddress LIKE '%@%' AND d.sendEmail=1
		GROUP BY `emailAddress` ORDER BY `sendEmail` DESC, `last_name` ASC, `first_name` ASC
		");
		/*
		$dbData = Config::get('db')->get_results("SELECT u.id as userId, '0' as recordId, u.last_name as last_name, u.first_name as first_name, u.email2 as emailAddress, u.accountId, '0' as sendEmail, IFNULL(e.deptId,'{$deptId}') as deptId FROM `productiveUser` u
		LEFT OUTER JOIN `productiveEmailDist` as e on e.userId=u.id AND e.deptId={$deptId} AND e.accountId=u.accountId AND e.sendEmail=1
		WHERE u.accountId={$accountId} AND u.active>0 AND u.email2 LIKE '%@%' AND u.id NOT IN (SELECT `userId` FROM `productiveEmailDist` WHERE `userId`=u.id AND `deptId`={$deptId} AND `sendEmail`=1) $searchAdd2
		UNION
		SELECT IFNULL(d.userId,0) as userId, IFNULL(d.id,0) as recordId, d.last_name as last_name, d.first_name as first_name, d.emailAddress as emailAddress, d.accountId, d.sendEmail as sendEmail, d.deptId as deptId FROM `productiveEmailDist` d
		WHERE d.accountId={$accountId} AND d.deptId={$deptId} AND d.emailAddress LIKE '%@%' AND d.sendEmail=1
		GROUP BY `emailAddress` ORDER BY `sendEmail` DESC, `last_name` ASC, `first_name` ASC
		");
		*/
	}else{
		//do nothing;
	}
    
    
    $jobArray = array();
   
    if($dbData){
	foreach ($dbData as $job) {
        $name = $job['last_name'].", ".$job['first_name'];
		if(ISSET($job['roleName'])){
			$roleName = $job['roleName'];
		}else{
			$roleName = '';
		}
		$recordId = $job['recordId'];
		$usrId = $job['userId'];
		$newId = $usrId . $recordId;
		
		if(intval($usrId)==0){
			$style="(External)";
		}else{
			$style='';
		}
				
		$email = trim($job['emailAddress']);
		$amp   = '@';
		$pos = strpos($email, $amp);
		
		if(intval($role)>=5){
		$disable = '';
		}else{
		$disable = "disabled";	
		}
		
		if(intval($deptId)==0 && intval($accountId)>0 && intval($job['sendEmail'])==1){
			$reports = 1;
			$checked = " checked";
		}else if(intval($deptId)>0 && intval($accountId)>0 && intval($job['sendEmail'])==1){
			$reports = 1;
			$checked = " checked";
		}else{
			$reports = 0;
			$checked = '';
		}
		
		
		if(intval($deptId)>=0 && intval($accountId)>0){
				$check1 = "<label><input type=\"checkbox\" id=\"emailChange" . $newId . "\" name=\"emailChange" . $newId . "\"" . $checked . " onchange=\"tj.emailChange(" . $usrId . "," . $recordId . "," . $accountId . "," . $deptId . ");\"" . $disable . "></input></label>";
		}else{
				$check1 = '';
		}		
				
        $jobArray[] = array(
            'user'=>trim($job['last_name']) . ', ' . trim($job['first_name'].$style),
			'email'=>$email,
			'dist'=>$check1,
			'reports'=>$reports
        );
    }
    
        $outData['data'] = $jobArray;
        echo json_encode($outData,JSON_NUMERIC_CHECK);
	}else{
		$jobArray[] = array(
            'user'=>'',
			'email'=>'',
			'dist'=>'',
			'reports'=>''
        );
		$outData['data'] = $jobArray;
        echo json_encode($outData,JSON_NUMERIC_CHECK);
	}
}


/////////////////////////////////////
// GET SAFETY INFO

function GetSafetyTableData() {
	//$idsearch = isset($_REQUEST['id']) && ($_REQUEST['id'] != 0) ? $_REQUEST['id'] : '';
    $startDate = isset($_REQUEST['start']) ? $_REQUEST['start'] : date('yyyy-mm-dd');
    $endDate = isset($_REQUEST['end']) ? $_REQUEST['end'] : date('yyyy-mm-dd');
    $userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
	//$deptId = Config::get('account')['deptid'];
	$role = Config::get('account')['role'];
	$enterpriseId = Config::get('account')['enterpriseId'];
	
	$dbUser = Config::get('db')->get_results("SELECT * from `productiveUser` where `id`={$userId}");
	
	//if($dbUser[0]['lastStartDate'] != $dbUser[0]['lastEndDate']){
		$active = " AND c.active=1 OR (c.dayDate >= u.lastStartDate AND c.dayDate <= u.lastEndDate)";
	//}else{
	//	$active = " AND c.active=1";
	//}
	
	if(intval($role)>=6 && intval($role)<14){
		$searchAdd = '';
	}else if(Intval($role)>=14){
		$searchAdd = "AND a.enterpriseId={$enterpriseId}";
	}else{
		$searchAdd = "AND (c.deptId=0 OR c.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId} and `primaryUnit`>0))";	
	}

    $dbData = Config::get('db')->get_results("SELECT c.*, IFNULL(t.safetyDesc,'') AS safetyDesc, IFNULL(s.typeDesc,'') AS typeDesc, IFNULL(r.first_name,'') as first, IFNULL(r.last_name,'') as last, u.role, (case when c.deptId IN (SELECT `deptId` FROM `productiveDeptXref` WHERE `userId` ={$userId} and `primaryUnit`>5) then '1' else '0' end) as xaccess, u.practiceId, CONCAT(SUBSTRING(u.first_name,1,1), '. ', u.last_name) as submitted, (case when c.createdBy IN (SELECT `userId` FROM `productiveDeptXref` WHERE `deptId` IN (SELECT `deptId` FROM `productiveDeptXref` where `userId`={$userId}) AND `primaryUnit`<8) then '1' else '0' end) as access, CURDATE() AS daytoday, p.id acctId, DATEDIFF(CURDATE(),c.openedDate) as daysOpen, DATE_FORMAT(c.dueDate, '%m/%d/%y') as newDate, DATE_FORMAT(c.openedDate, '%m/%d/%y') as opened, IFNULL(d.dept,'') as deptName 
	FROM `productiveSafety` c 
	LEFT JOIN `productiveUser` as u on u.id=c.createdBy
    LEFT JOIN `productiveAccount` as p on p.id=u.accountId
	LEFT OUTER JOIN `ProductiveDept` as d on d.id=c.deptId
	LEFT OUTER JOIN `productivesafetyConfig` as t on t.id=c.safetyConfig
	LEFT OUTER JOIN `productivesafetyType` as s on s.id=t.safetyType
	LEFT OUTER JOIN `productiveUser` as r on r.id=c.updatedBy
	WHERE p.id={$accountId}{$active}
	GROUP BY c.id
	ORDER BY c.priority DESC, c.dueDate ASC, c.active DESC, d.dept ASC, c.roomId1 ASC");
      
    if($dbData){
		
        $jobArray = array();
		foreach($dbData as $ante){
			$selectName = $ante['typeDesc'] . ' - ' . $ante['safetyDesc'];
			if($ante['deptId']==1){
			$unitName = "Hospital";
			}else{
			$unitName = $ante['deptName'];	
			}
			$note = $ante['note1'];
			
			if($ante['practiceId']==1){
			$unit = $unitName;
			}else{
			$unit = $unitName . " (" . $ante['submitted'] . ")";	
			}
			/*
			if(strlen($ante['type1'] ?? '')>0){
				$s = $ante['type1'];
			}else if(strlen($ante['type2'] ?? '')>0){
				$s = $ante['type2'];
			}else if(strlen($ante['type3'] ?? '')>0){
				$s = $ante['type3'];
			}else{
				$s = '';
			}
			*/
			if(intval($ante['priority'])==1){
				$style = "1";
			}else if($ante['dueDate']!='0000-00-00' && strtotime($ante['dueDate']) <= strtotime($ante['daytoday'])){
				$style = "2";
			}else{
				$style = "0";
			}
			
			
			
			
			
			if(intval($ante['active'])==1){
				//$reports = 1;
				$checked = " checked";
			}else{
				//$reports = 0;
				$checked = '';
			}
			$newId = $ante['id'];
			
			if(intval($ante['updatedBy'])>0){
				$updated = " - " . substr($ante['first'],0,1) . ". " . $ante['last'];
			}else{
				$updated = '';
			}
			
			$desc = $note . $updated;
			
			if(intval($role)>9 || intval($ante['createdBy'])==$userId || intval($ante['access'])==1 || intval($ante['xaccess'])==1){
			$check1 = "<input type=\"checkbox\" id=\"closeSafety" . $newId . "\" name=\"closeSafety" . $newId . "\"" . $checked . " onchange=\"tj.closeSafety(" . $newId . ");\"></input>";
			$descName = "<a href=\"javascript:;\" onclick=\"tj.editSafety(" . $ante['id'] . ");\">" . $desc . "</a>";
			}else{
			$check1 = $check1 = $check1 = "<input type=\"checkbox\" id=\"closeSafety" . $newId . "\" name=\"closeSafety" . $newId . "\"" . $checked . " onchange=\"tj.closeSafety(" . $newId . ");\" disabled></input>";;
			$descName = $desc;	
			}
			
			
			
			
			if($ante['dueDate']=='' || $ante['dueDate']=='0000-00-00'){
				$dueDate='';
			}else{
				//$dueDate = "<a href=\"javascript:;\" onclick=\"tj.editdueDate(" . $ante['id'] . ");\">" . $ante['newDate'] . "</a>";
				$dueDate= " (" . $ante['newDate'] . ")";
			}
			$opened = $ante['opened'] . " (" . $ante['daysOpen'] . ")";
		
			$closeBtn = ' <button type="button" class="btn btn-warning btn-sm" onclick="tj.closeSafetyItem(' . $newId . ');">Close</button>';
			$jobArray[] = array(
				'unit' => $unit,
				'date' => $opened,
				'room' => $ante['roomId1'],
				'type' => $selectName,
				'desc' => $descName . $closeBtn,
				'active' => $check1 . " " . $dueDate,
				'priority' =>$ante['priority'],
				'style' => $style

            );
			}
			
		$outData = array();
		$outData['data'] = $jobArray;
		echo json_encode($outData,JSON_NUMERIC_CHECK);
		}else {
        $outData = array();
		$jobArray[] = array(
				'unit' => '',
				'date' => '',
				'room' => '',
				'type' => '',
				'desc' => '',
				'active' => '',
				'style' => '',
				'priority' => ''
            );
        $outData['data'] = $jobArray;
        echo json_encode($outData,JSON_NUMERIC_CHECK);
		}
}
/////////////////////////////////////
// GET CRASH INFO

function GetTasksTableData() {
	//$idsearch = isset($_REQUEST['id']) && ($_REQUEST['id'] != 0) ? $_REQUEST['id'] : '';
    $startDate = isset($_REQUEST['start']) ? $_REQUEST['start'] : date('yyyy-mm-dd');
    $endDate = isset($_REQUEST['end']) ? $_REQUEST['end'] : date('yyyy-mm-dd');
    $userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
	//$deptId = Config::get('account')['deptid'];
	$role = Config::get('account')['role'];
	$enterpriseId = Config::get('account')['enterpriseId'];
	
	if(intval($role)>7 && intval($role)<14){
		$searchAdd = '';
	}else if(Intval($role)>=14){
		$searchAdd = " AND a.enterpriseId={$enterpriseId}";
	}else{
		$searchAdd = " AND c.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId} and `primaryUnit`>0)";	
	}

    $dbData = Config::get('db')->get_results("SELECT c.*, u.id as txtuserId, u.txtPause, u.mobile, IFNULL(t.userId,0) as textId, IFNULL(t.textMe,0) as textMe, d.dept, IFNULL((SELECT `submittedDateTime` FROM `productivecrashLog` where `logId`=c.id and `userId`>0 ORDER BY `id` DESC LIMIT 0,1),'') as entered, s.shift as shiftName 
	FROM `productivelogData` c 
	LEFT JOIN `productiveUser` as u on u.id={$userId} 
	LEFT JOIN `ProductiveDept` as d on d.id=c.deptId 
	LEFT OUTER JOIN `productivetaskText` as t on t.taskId = c.id AND t.userId={$userId} 
	LEFT JOIN `productiveShifts` as s on s.id=c.shift 
	WHERE c.accountId={$accountId}$searchAdd GROUP BY c.id ORDER BY c.active DESC, d.dept ASC, c.title ASC");
      
    if($dbData){
		//$outJobs = array();
        $jobArray = array();
		foreach($dbData as $ante){
			
			if($ante['entered']=='0000-00-00 00:00:00'){
				$dateCompleted = '';
			}else{
				$dateCompleted = $ante['entered'];
			}
			$shiftName = $ante['shiftName'];
			//$cartDesc= $ante['title'];
			$dow= $ante['dow'];
			$unitName = $ante['dept'];
			$textMe = $ante['textMe'];
			$textuserId = intval($ante['textId']);
			$taskId = $ante['id'];
			$active = $ante['active'];
			$pause = $ante['txtPause'];
			$mobile = $ante['mobile'];
			
			if(intval($active)==1){
				$inactive = '';
			}else{
				$inactive = "<span style=\"color:red\"> (Inactive)</span>";
			}
			
			
			
			if(intval($role)>=5){
			$disable = '';
			}else{
			$disable = "disabled";	
			}
			
				$newDay = '';
				$newDay .='';
					
				if(str_contains($dow,"8")){
				$newDay .= "All Days,";
				}
				if(str_contains($dow,"2")){
				$newDay .= "Mon,";
				}
				if(str_contains($dow,"3")){
				$newDay .= "Tue,";
				}
				if(str_contains($dow,"4")){
				$newDay .= "Wed,";
				}
				if(str_contains($dow,"5")){
				$newDay .= "Thu,";
				}
				if(str_contains($dow,"6")){
				$newDay .= "Fri,";
				}
				if(str_contains($dow,"7")){
				$newDay .= "Sat,";
				}
				if(str_contains($dow,"1")){
				$newDay .= "Sun,";
				}
				$days = trim($newDay,',');
			
			
			if(intval($taskId)>0 && intval($textuserId)==$userId && intval($textMe)>0 && intval($active==1)){
				$checked = " checked";
			}else{
				$checked = '';
			}
		
			$auditName = "<a href=\"javascript:;\" onclick=\"tj.editTask(" . $ante['id'] . ");\">" . $ante['title'] . "</a>";				
			
			if (intval($ante['txtPause'])!=0 && strlen($ante['mobile'] ?? '')==10){
				$invalid = "Paused";
				$txtLink = '<a href="javascript:;" onclick="tj.activate2('. $userId .')">'. $invalid .'</a>';
			}else if (intval($pause)==0 && strlen($mobile)==10){
				$txtLink = "<label><input type=\"checkbox\" id=\"text" . $taskId . "\" name=\"text" . $taskId . "\"" . $checked . " onchange=\"tj.textMe(" . $userId. "," . $taskId . "," . intval($active) . ");\"" . $disable . "></input></label>";
			}else{
				$invalid = "Add Mobile";
				$txtLink = '<a href="javascript:;" onclick="tj.activate2('. $userId .')">'. $invalid .'</a>';
			}
						
			$jobArray[] = array(
				'unit' => $unitName,
				'name' => $auditName . $inactive,
				'days' => $days,
				'time' => $shiftName,
				'text' => $txtLink,
				'entered' => $dateCompleted,
				'dow' => $dow,
				'active' => $active
				
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
				'days' => '',
				'time' => '',
				'text' => '',
				'entered' => '',
				'dow' => '',
				'active' => ''
            );
        $outData['data'] = $jobArray;
        echo json_encode($outData,JSON_NUMERIC_CHECK);
		}
}

/////////////////////////////////////
// Grid Update
function updateTaskText() {
	$accountId = Config::get('account')['accountId'];
	$userId = Config::get('account')['id'];
	$taskId = (isset($_REQUEST['taskId']) ? $_REQUEST['taskId']:0);
	//$userId = (isset($_REQUEST['userId']) ? $_REQUEST['userId']:0);
	$textMe = (isset($_REQUEST['textMe']) ? $_REQUEST['textMe']:0);

	if(intval($taskId)>0 && intval($userId)>0){
		$whereEsc = array(
				'taskId'=>$taskId,
				'userId'=>$userId
				);
		Config::get('db')->delete('productivetaskText',$whereEsc);
	}
	
	if(intval($taskId)>0 && intval($userId)>0 && intval($textMe)>0){
	
		$data = array(
		'accountId' =>$accountId,
            'taskId' =>$taskId,
			'userId' =>$userId,
			'textMe' => 1
        );
            
	$newView = Config::get('db')->insert('productivetaskText',$data); 	
	
	$userdata = array(
		'txtPause' =>0
        );
	$userwhere = array(
		'id' =>$userId
        );
            
	$unPause = Config::get('db')->update('productiveUser',$userdata,$userwhere); 
	}
	
	
	echo json_encode(array('success' => true,'message'=>'Updated'));
    return true;
}

/////////////////////////////////////
// Safety Huddle

function addSafety() {
	$accountId = Config::get('account')['accountId'];
	$userId = Config::get('account')['id'];
	$deptId = (isset($_REQUEST['deptId']) ? $_REQUEST['deptId']:'');
	$roomId1 = (isset($_REQUEST['roomId1']) ? $_REQUEST['roomId1']:'');
	$peri1 = (isset($_REQUEST['peri1']) ? $_REQUEST['peri1']:'');
	$hr1 = (isset($_REQUEST['hr1']) ? $_REQUEST['hr1']:'');
	$note1 = (isset($_REQUEST['note1']) ? $_REQUEST['note1']:'');
	$dueDate = (isset($_REQUEST['dueDate']) ? $_REQUEST['dueDate']:'');
	$currentTime = (isset($_REQUEST['currentTime'])?$_REQUEST['currentTime']: '');
	$priority = (isset($_REQUEST['priority']) ? $_REQUEST['priority']:0);
	$submittedBy = (isset($_REQUEST['submittedBy']) ? trim($_REQUEST['submittedBy']):'');

	if($deptId){

		$data = array(
		'deptId' =>$deptId,
        'roomId1' =>$roomId1,
		'periEvent1' =>$peri1,
		'safetyConfig' =>$peri1,
		'hr1' =>$hr1,
		'periGen1' =>$submittedBy,
		'note1' =>$note1,
		'dueDate' =>$dueDate,
		'openedDate' =>$currentTime,
		'active' =>1,
		'dayDate' =>$currentTime,
		'createdBy' =>$userId,
		'priority' =>$priority,
		'accountId' =>$accountId
        );

	$safetyUpdate = Config::get('db')->insert('productiveSafety',$data);
	$safetyId = intval(Config::get('db')->get_results("SELECT MAX(id) as id FROM productiveSafety WHERE accountId={$accountId}")[0]['id']);

	// SMS alert to escAlerts users for this dept
	$deptIdInt = intval($deptId);
	if($deptIdInt > 1){
		$recipients = Config::get('db')->get_results("SELECT x.userId, u.mobile, d.dept as deptName FROM productiveDeptXref x LEFT JOIN ProductiveDept d ON d.id=x.deptId LEFT JOIN productiveUser u ON u.id=x.userId WHERE x.deptId={$deptIdInt} AND x.escAlerts=1 AND u.txtPause=0 AND LENGTH(u.mobile)=10 GROUP BY u.mobile");
	} else {
		$recipients = Config::get('db')->get_results("SELECT x.userId, u.mobile, 'Hospital' as deptName FROM productiveDeptXref x LEFT JOIN productiveUser u ON u.id=x.userId WHERE x.accountId={$accountId} AND x.escAlerts=1 AND u.txtPause=0 AND LENGTH(u.mobile)=10 GROUP BY u.mobile");
	}

	if($recipients && $safetyId){
		$deptName = $recipients[0]['deptName'];
		$byLine = $submittedBy ? ' Submitted by: ' . $submittedBy . '.' : '';
		$expires = date('Y-m-d H:i:s', strtotime('+4 hours'));

		$psword  = "J8775bcgEE2065";
		$keyword2 = "JOBALARM58046";
		$slooce  = "jobalarm45";
		$numbers = []; $messages = []; $keywords = [];
		$now = time();

		foreach($recipients as $r){
			$mobile = $r['mobile'];
			$token = bin2hex(random_bytes(32));
			Config::get('db')->insert('manager_tokens', array('token' => $token, 'user_id' => $r['userId'], 'account_id' => $accountId, 'context' => 'safety', 'ref_id' => $safetyId, 'expires_at' => $expires));
			$link = "https://www.productivern.com/manager-view.php?t=" . $token;
			$message = "A safety issue has been reported in " . $deptName . "." . $byLine . "  " . $link;

			$msgId   = $mobile . $now;
			$xmlMsg  = "<message id=\"{$msgId}\">";
			$xmlMsg .= "<partnerpassword>{$psword}</partnerpassword>";
			$xmlMsg .= "<content><![CDATA[{$message}]]></content>";
			$xmlMsg .= "</message>";
			$messages[] = $xmlMsg;
			$numbers[]  = "1" . $mobile;
			$keywords[] = $keyword2;
		}

		$smsoptions = array(
			'numbers' => $numbers,
			'message' => $messages,
			'keyword' => $keywords,
			'login'   => $slooce
		);
		sendescNow($smsoptions);
	}
	}

	echo json_encode(array('success' => true,'message'=>'Updated'));
    return true;
}

function closeSafety() {
	$accountId = Config::get('account')['accountId'];
	$userId = Config::get('account')['id'];
	$dataId = (isset($_REQUEST['dataId']) ? $_REQUEST['dataId']:'');
	$active = (isset($_REQUEST['active']) ? $_REQUEST['active']:0);
	$currentTime = (isset($_REQUEST['currentTime'])?$_REQUEST['currentTime']: '');
	
	
	if(intval($active)==1){
		$closed = '0000-00-00 00:00:00';
	}else{
		$closed = $currentTime;
	}
	
	if($dataId){

		$data = array(
		'active' =>$active,
		'dayDate' =>$currentTime,
		'closedDate' =>$closed
        );
		$where = array(
		'id' =>$dataId,
		'accountId' =>$accountId
        );

	$safetyUpdate = Config::get('db')->update('productiveSafety',$data,$where);
	}

	echo json_encode(array('success' => true,'message'=>'Updated'));
    return true;
}

function closeSafetyToken() {
	$token = isset($_REQUEST['token']) ? preg_replace('/[^a-f0-9]/', '', $_REQUEST['token']) : '';
	$dataId = intval($_REQUEST['dataId'] ?? 0);
	if (empty($token) || $dataId < 1) {
		echo json_encode(array('success' => false));
		return;
	}
	$tokRow = Config::get('db')->get_results("SELECT * FROM manager_tokens WHERE token='{$token}'");
	if (!$tokRow || strtotime($tokRow[0]['expires_at']) < time()) {
		echo json_encode(array('success' => false, 'message' => 'Invalid or expired token'));
		return;
	}
	$accountId = intval($tokRow[0]['account_id']);
	$check = Config::get('db')->get_results("SELECT id FROM productiveSafety WHERE id={$dataId} AND accountId={$accountId} AND active=1");
	if (!$check) {
		echo json_encode(array('success' => false));
		return;
	}
	$now = date('Y-m-d H:i:s');
	Config::get('db')->update('productiveSafety', array('active' => 0, 'closedDate' => $now), array('id' => $dataId));
	echo json_encode(array('success' => true));
}

function closeEscalationToken() {
	$token  = isset($_REQUEST['token'])  ? preg_replace('/[^a-f0-9]/', '', $_REQUEST['token']) : '';
	$dataId = intval($_REQUEST['dataId'] ?? 0);
	if (empty($token) || $dataId < 1) { echo json_encode(['success' => false]); return; }
	$tokRow = Config::get('db')->get_results("SELECT * FROM manager_tokens WHERE token='{$token}'");
	if (!$tokRow || strtotime($tokRow[0]['expires_at']) < time()) {
		echo json_encode(['success' => false, 'message' => 'Invalid or expired token']);
		return;
	}
	$accountId = intval($tokRow[0]['account_id']);
	$check = Config::get('db')->get_results("SELECT id FROM productiveEscalations WHERE id={$dataId} AND accountId={$accountId} AND active=1");
	if (!$check) { echo json_encode(['success' => false]); return; }
	Config::get('db')->update('productiveEscalations', ['active' => 0], ['id' => $dataId]);
	echo json_encode(['success' => true]);
}

function closeQBLToken() {
	$token  = isset($_REQUEST['token'])  ? preg_replace('/[^a-f0-9]/', '', $_REQUEST['token']) : '';
	$dataId = intval($_REQUEST['dataId'] ?? 0);
	if (empty($token) || $dataId < 1) { echo json_encode(['success' => false]); return; }
	$db = Config::get('db');
	$tokRow = $db->get_results("SELECT * FROM manager_tokens WHERE token='{$token}'");
	if (!$tokRow || strtotime($tokRow[0]['expires_at']) < time()) {
		echo json_encode(['success' => false, 'message' => 'Invalid or expired token']);
		return;
	}
	$accountId = intval($tokRow[0]['account_id']);
	$check = $db->get_results("SELECT id FROM productiveQBLlog WHERE id={$dataId} AND accountId={$accountId}");
	if (!$check) { echo json_encode(['success' => false]); return; }
	$colCheck = $db->get_results("SHOW COLUMNS FROM productiveQBLlog LIKE 'managerClosed'");
	if (!$colCheck) {
		$db->query("ALTER TABLE productiveQBLlog ADD COLUMN managerClosed TINYINT NOT NULL DEFAULT 0");
	}
	$db->update('productiveQBLlog', ['managerClosed' => 1], ['id' => $dataId]);
	echo json_encode(['success' => true]);
}

function updateSafety() {
	$accountId = Config::get('account')['accountId'];
	$userId = Config::get('account')['id'];
	$dataId = (isset($_REQUEST['dataId']) ? $_REQUEST['dataId']:0);
	$deptId = (isset($_REQUEST['deptId']) ? $_REQUEST['deptId']:'');
	$roomId1 = (isset($_REQUEST['roomId1']) ? $_REQUEST['roomId1']:'');
	$peri1 = (isset($_REQUEST['peri1']) ? $_REQUEST['peri1']:'');
	$hr1 = (isset($_REQUEST['hr1']) ? $_REQUEST['hr1']:'');
	$periGen1 = (isset($_REQUEST['periGen1']) ? $_REQUEST['periGen1']:'');
	$note1 = (isset($_REQUEST['note1']) ? $_REQUEST['note1']:'');
	$dueDate = (isset($_REQUEST['dueDate']) ? $_REQUEST['dueDate']:'');
	$priority = (isset($_REQUEST['priority']) ? $_REQUEST['priority']:0);
	$active = (isset($_REQUEST['active']) ? $_REQUEST['active']:1);
	
	
	if(intval($dataId)>0){
	
		$data = array(
		'deptId' =>$deptId,
        'roomId1' =>$roomId1,
		'periEvent1' =>$peri1,
		'safetyConfig' =>$peri1,
		'hr1' =>$hr1,
		'periGen1' =>$periGen1,
		'note1' =>$note1,
		'dueDate' =>$dueDate,
		'priority' =>$priority,
		'active' =>$active,
		'updatedBy' =>$userId
        );
		$where = array(
		'id' =>$dataId,
		'accountId' =>$accountId
        );

	$safetyUpdate = Config::get('db')->update('productiveSafety',$data,$where);
	}

	echo json_encode(array('success' => true,'message'=>'Updated'));
    return true;
}

/////////////////////////////////////
// GET CRASH INFO


function GetMsgTableData() {
	$userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
	$role = Config::get('account')['role'];
	$enterpriseId = Config::get('account')['enterpriseId'];
	$countDept = $_SESSION['account']['countDept'];
	$outJobs = array();
    $jobArray = array();
	
	if(intval($countDept)>1){
		$dbMsg = Config::get('db')->get_results("SELECT m.*, DATE_FORMAT(CURDATE(), '%Y-%m-%d') as currentDate FROM
            `productiveMessaging` m
        WHERE
            m.createdBy={$userId} AND m.deptId=0 AND m.accountId={$accountId}
		GROUP BY m.id");
	
		
		if($dbMsg){
		$msg0 = '<a href="javascript:;" onclick="tj.editMsg(' . $dbMsg[0]['id'] . ',0);return false;" >' . $dbMsg[0]['message'] . '</a>';
		$start0 = $dbMsg[0]['createdDate'] . '  ' . '<button class="btn btn-sm btn-danger" type="button" onclick="tj.clearMsg(' . $dbMsg[0]['id'] . ');" class="close">DEL</button>';
		//$exp0 = $dbMsg[0]['expDate'];
		}else{
		$msg0 = '<a href="javascript:;" onclick="tj.addMsg(' . $userId . ',0);return false;" >Add Message</a>';
		$start0 = '';
		//$exp0 = '';
		}
		
		if($dbMsg && $dbMsg[0]['expDate'] && $dbMsg[0]['expDate']!='0000-00-00' && $dbMsg[0]['expDate']!=''){
				//$exp0 = '<a href="javascript:;" onclick="tj.editExpire(' . $dbMsg[0]['id'] . ');return false;" >' . $dbMsg[0]['expDate'] . '</a>';
				$exp0 = $dbMsg[0]['expDate'];
		}else{
				//$exp0 = '<a href="javascript:;" onclick="tj.addExpire(' . $dbMsg[0]['id'] . ');return false;" >Add</a>';
				$exp0 = '';
		}
		
		$all = 'All My Units';
		
		if($dbMsg && strtotime($dbMsg[0]['expDate'])>0 && strtotime($dbMsg[0]['expDate']) < strtotime($dbMsg[0]['currentDate'])){
				$style0 = "1";
			}else{
				$style0 = "0";
			}

		
	$jobArray[] = array(
				'unit' => $all,
				'msg' => $msg0,
				'start' => $start0,
				'end' => $exp0,
				'style' => $style0
            );
	}
	
	if(intval($role)>7 && intval($role)<14){
		$searchAdd = "AND d.accountId={$accountId}";
	}else{
		$searchAdd = "AND d.accountId={$accountId} AND d.id in (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId} and `primaryUnit`>0)";	
	}	
	
	$dbData = Config::get('db')->get_results("SELECT d.*, IFNULL(m.id,'') as msgId, DATE_FORMAT(CURDATE(), '%Y-%m-%d') as currentDate, IFNULL(u.first_name,'') as first, IFNULL(u.last_name,'') as last, IFNULL(m.message,'') as message, IFNULL(m.createdDate,'') AS createdDate, IFNULL(m.expDate,'') as expDate FROM
            `ProductiveDept` d
        LEFT JOIN 
			`productiveMessaging` as m on m.deptId = d.id AND m.createdBy={$userId}
		LEFT OUTER JOIN
        	`productiveUser` as u on u.id = m.createdBy
        WHERE
           d.active>0 AND d.unitCategory!=4 $searchAdd
		GROUP BY d.id
		ORDER BY d.dept ASC");
			
	
    if($dbData){
		
		foreach($dbData as $ante){
			$unitName= $ante['dept'];
			
			if(strtotime($ante['expDate'])>0 && strtotime($ante['expDate']) < strtotime($ante['currentDate'])){
				$style = "1";
			}else{
				$style = "0";
			}
			
			
			if($ante['expDate'] && $ante['expDate']!='0000-00-00' && $ante['expDate']!=''){
				//$expDate = '<a href="javascript:;" onclick="tj.editExpire(' . $ante['msgId'] . ');return false;" >' . $ante['expDate'] . '</a>';
				$expDate = $ante['expDate'];
			//}else if($ante['expDate'] && ($ante['expDate']=='0000-00-00' || $ante['expDate']=='')){
				//$expDate = '<a href="javascript:;" onclick="tj.addExpire(' . $ante['msgId'] . ');return false;" >Add</a>';
			}else{
				$expDate = '';
			}
			
			if($ante['message']){
				$msg1 = '<a href="javascript:;" onclick="tj.editMsg(' . $ante['msgId'] . ',' . $ante['id'] . ');return false;" >' . $ante['message'] . '</a>';
				$dateCreated = $ante['createdDate'] . '  ' . '<button class="btn btn-sm btn-danger" type="button" onclick="tj.clearMsg(' . $ante['msgId'] . ');" class="close">DEL</button>';
			}else{
				$msg1 = '<a href="javascript:;" onclick="tj.addMsg(' . $userId . ',' . $ante['id'] . ');return false;" >Add Message</a>';
				$dateCreated = '';
			}
						
						
			$jobArray[] = array(
				'unit' => $unitName,
				'msg' => $msg1,
				'start' => $dateCreated,
				'end' => $expDate,
				'style' => $style
            );
			}
         	
		$outData = array();
		$outData['data'] = $jobArray;
		echo json_encode($outData,JSON_NUMERIC_CHECK);
		}else {
        $outData = array();
		$jobArray[] = array(
				'unit' => '',
				'msg' => '',
				'start' => '',
				'end' => '',
				'style' => ''
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
	$taskTime = isset($_REQUEST['tasksTime']) ? $_REQUEST['tasksTime'] : '';
	$taskHour = isset($_REQUEST['tasksHour']) ? $_REQUEST['tasksHour'] : '8';
    $userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
	//$deptId = Config::get('account')['deptid'];
	$role = Config::get('account')['role'];
	$enterpriseId = Config::get('account')['enterpriseId'];
	$today = date("Y-m-d");
	
	if(intval($role)>=8 && intval($role)<14){
		$searchAdd = " AND c.accountId={$accountId}";
	}else if(intval($role)>=14){
		$searchAdd = " AND a.enterpriseId={$enterpriseId}";
	}else{
		$searchAdd = " AND c.accountId={$accountId} AND c.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId} and `primaryUnit`>0)";	
	}
	
	if(intval($taskHour)>19 && $startDate==$today){
    $dbData = Config::get('db')->get_results("SELECT c.*, r.first_name as first, r.last_name as last, l.title, IFNULL(u.logix2,'') as logix2, l.logType, l.active, d.active, DATE_FORMAT(s.shiftTime, '%H:%i') as shiftTime, s.shift as shiftName, DATE_FORMAT(CURDATE(), '%Y-%m-%d') as currentDate, DATE_FORMAT(c.dayDate, '%m/%d/%y') as crashDate, (case when c.submittedDateTime='0000-00-00 00:00:00' then '' else c.submittedDateTime end) as newDate, d.dept, IFNULL(u.first_name,'') as first_name, IFNULL(u.last_name,'') as last_name, IFNULL(u.practiceId,'0') as practiceId, IFNULL(r.role,'0') as userRole FROM
            `productivecrashLog` c
        LEFT JOIN 
			`productivelogData` as l on l.id = c.logId
		LEFT JOIN 
			`ProductiveDept` as d on d.id = c.deptId
		LEFT JOIN 
			`productiveAccount` as a on a.id = d.accountId
		LEFT JOIN 
			`productiveShifts` as s on s.id = c.shift
		LEFT OUTER JOIN
        	`productiveUser` as u on u.id = c.userId
		LEFT OUTER JOIN
        	`productiveUser` as r on r.id = c.viewId
        WHERE
            c.dayDate =DATE_ADD(CURDATE(), INTERVAL 1 DAY)) $searchAdd
		GROUP BY c.id
		ORDER BY d.dept ASC, c.dayDate ASC, c.shift DESC");
	}else{	
	$dbData = Config::get('db')->get_results("SELECT c.*, r.first_name as first, r.last_name as last, l.title, IFNULL(u.logix2,'') as logix2, l.logType, l.active, d.active, DATE_FORMAT(s.shiftTime, '%H:%i') as shiftTime, s.shift as shiftName, DATE_FORMAT(CURDATE(), '%Y-%m-%d') as currentDate, DATE_FORMAT(c.dayDate, '%m/%d/%y') as crashDate, (case when c.submittedDateTime='0000-00-00 00:00:00' then '' else c.submittedDateTime end) as newDate, d.dept, IFNULL(u.first_name,'') as first_name, IFNULL(u.last_name,'') as last_name, IFNULL(u.practiceId,'0') as practiceId, IFNULL(r.role,'0') as userRole FROM
            `productivecrashLog` c
        LEFT JOIN 
			`productivelogData` as l on l.id = c.logId
		LEFT JOIN 
			`ProductiveDept` as d on d.id = c.deptId
		LEFT JOIN 
			`productiveAccount` as a on a.id = d.accountId
		LEFT JOIN 
			`productiveShifts` as s on s.id = c.shift
		LEFT OUTER JOIN
        	`productiveUser` as u on u.id = c.userId
		LEFT OUTER JOIN
        	`productiveUser` as r on r.id = c.viewId
        WHERE
            c.dayDate ='{$startDate}' $searchAdd
		GROUP BY c.id
		ORDER BY d.dept ASC, c.dayDate DESC, c.shift DESC");
	} 
	
	// WHERE ((l.logType=2 AND c.dayDate >= '{$startDate}' AND c.dayDate <= '{$endDate}') OR (l.logType!=2 AND c.dayDate >= DATE_SUB(CURDATE(), INTERVAL 1 DAY)) AND c.dayDate <= CURDATE()) AND d.active>0 AND l.active>0$searchAdd
	// WHERE c.dayDate >= '{$startDate}' AND c.dayDate <= '{$endDate}' AND d.active>0 AND l.active>0$searchAdd
    
	if($dbData){
		$outJobs = array();
        $jobArray = array();
		foreach($dbData as $ante){
			
			if(intval($ante['userId'])>0 && intval($ante['practiceId'])==1 && strlen($ante['submittedBy'] ?? '')>0){
			$userName = $ante['submittedBy'];
			}else if(intval($ante['userId'])>0){
			$userName = $ante['first_name'] . " " . $ante['last_name'] ;
			}else{
			$userName = '';	
			}
			
			$dateCompleted = $ante['crashDate'];
			
			$cartDesc= $ante['title'];
			
			if(intval($ante['userId'])>0 || intval($ante['viewId'])>0){
			$dateEntered= $ante['newDate'];
			}else{
			$dateEntered= '';
			}
			
			if(((strtotime($taskTime) > strtotime($ante['shiftTime']) && $ante['dayDate']==$ante['currentDate']) || ($ante['dayDate']<$ante['currentDate'])) && intval($ante['viewId'])==0 && intval($ante['userId'])==0 && intval($ante['logType'])!=2){
				$style = "1";
			}else if(intval($ante['viewId'])>0 && intval($ante['logType'])==2 && intval($ante['userRole'])>0 && intval($ante['userRole'])<6 && (strlen($ante['q1'] ?? '')>0 || strlen($ante['q2'] ?? '')>0 || strlen($ante['q3'] ?? '')>0)){
				$style = "2";
				//$userName = "Viewed by " . $ante['first'] . " " . $ante['last'];
			}else if(intval($ante['viewId'])>0 && intval($ante['logType'])==2 && intval($ante['userRole'])>0 && intval($ante['userRole'])<6){
				$style = "2";
				$userName = "Viewed by " . $ante['first'] . " " . $ante['last'];
			}else if(intval($ante['userId'])>0 && (strlen($ante['q1'] ?? '')>0 || strlen($ante['q2'] ?? '')>0 || strlen($ante['q3'] ?? '')>0)){
				$style = "2";
			}else{
				$style = "0";
			}
			
			
			if(intval($ante['logix2'])>0){
			$p1 = rand(10,97);
				$qrcode = '<a href="javascript:;" onclick="tj.generateQRCode(' . $ante['logix2'] . ',' . $p1 . ');return false;" >QR Code</a>';
			}else{
				$qrcode = '';
			}
			//$qr = '';
		
			//$unitName = "<a href=\"#" . $view . "?id=" . $ante['deptId'] . "&s=" . $startDate . "&e=" . $endDate . "\" >" . $ante['dept'] . "</a>";
			$auditName =  $ante['title'];
			
			if(intval($ante['logType'])!=2){
			$unitName = "<a href=\"javascript:;\" onclick=\"tj.editCrash(" . $ante['id'] . ");\">" . $ante['dept'] . ' ' . $ante['title'] . "</a>";				
			$shiftName =  " (" . $ante['shiftTime']. ")";

			$print = '';
			}else{
			$unitName = "<a href=\"javascript:;\" onclick=\"tj.editHuddle(" . $ante['id'] . ");\">" . $ante['dept'] . ' ' . $ante['title'] . "</a>";				
			$shiftName = '';
			$print = "<a href=\"#huddleView?i=" . $ante['id'] . "\">  (Print)</a>";
			//$print = "<button type=\"button\" id=\"printhuddle\" class=\"btn btn-info btn-sm\" onclick=\"tj.printhuddle(" . $ante['id'] . "); \">Print</button>";
			}
						
			$jobArray[] = array(
				'unit' => $unitName,
				'date' => $dateCompleted . $shiftName,
				'user' => $userName,
				'entered' => $dateEntered . " " . $print,
				'style' => $style
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
				'user' => '',
				'entered' => '',
				'style' => ''
            );
        $outData['data'] = $jobArray;
        echo json_encode($outData,JSON_NUMERIC_CHECK);
		}
}

/////////////////////////////////////
// GET CRASH INFO
function Printqrcodes() {
	$userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
	$role = Config::get('account')['role'];
	$enterpriseId = Config::get('account')['enterpriseId'];
	$deptId = isset($_REQUEST['deptId']) ? $_REQUEST['deptId'] : '';
	
    $dbData = Config::get('db')->get_results("Select d.*, DATE_FORMAT(d.qrUpdated,'%m-%d-%y') as updated, u.logix2 FROM `ProductiveDept` d LEFT OUTER JOIN `productiveDeptXref` as x on x.deptId=d.id LEFT OUTER JOIN `productiveUser` as u on u.id=x.userId  WHERE d.id={$deptId} AND u.role<5 and u.practiceId=1 AND u.logix2!=''");
    
	if($dbData){
    $dbQRcode= $dbData[0];
    echo json_encode(array('success' => true,'data'=>$dbQRcode));
    return true;
    }else{
    echo json_encode(array('success'=>false,'message'=>'Record not found.'));
	return false;
	}
}

/////////////////////////////////////
// GET CRASH INFO
function GetqrcodesTable() {
	Config::get('db')->query("CREATE TABLE IF NOT EXISTS `productiveQrTokens` (`token` VARCHAR(64) NOT NULL, `userId` INT NOT NULL, `deptId` INT NOT NULL DEFAULT 0, `page` VARCHAR(20) NOT NULL, `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (`token`), INDEX `idx_deptId` (`deptId`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
	$userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
	$role = Config::get('account')['role'];
	$enterpriseId = Config::get('account')['enterpriseId'];
	
	if(intval($role)>7 && intval($role)<14){
		$searchAdd = " AND u.accountId={$accountId}";
	}else if(Intval($role)>=14){
		$searchAdd = " AND a.enterpriseId={$enterpriseId}";
	}else{
		$searchAdd = " AND u.accountId={$accountId} AND x.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId} and `primaryUnit`>0)";	
	}

    $dbData = Config::get('db')->get_results("Select u.*, x.deptId as deptNum, DATE_FORMAT(d.qrUpdated,'%m-%d-%y') as updated, d.dept as deptName, a.active as aactive, IFNULL(l.deptId,'0') as title, IFNULL(b.deptId,'0') as qbldeptId, IFNULL(qt_qbl.token,'') as qbl_token, IFNULL(qt_tasks.token,'') as tasks_token, IFNULL(qt_staffing.token,'') as staffing_token from `productiveUser` u LEFT JOIN `productiveDeptXref` as x on x.userId=u.id LEFT JOIN `ProductiveDept` as d on d.id = x.deptId LEFT OUTER JOIN `productiveAccountXref` as a on a.dept=x.deptId and a.active=1 LEFT JOIN `productivelogData` as l on l.deptId=d.id LEFT JOIN `productiveQBLconfig` as b on b.deptId=d.id LEFT JOIN `productiveQrTokens` as qt_qbl ON qt_qbl.deptId=x.deptId AND qt_qbl.page='qbl' LEFT JOIN `productiveQrTokens` as qt_tasks ON qt_tasks.deptId=x.deptId AND qt_tasks.page='tasks' LEFT JOIN `productiveQrTokens` as qt_staffing ON qt_staffing.deptId=x.deptId AND qt_staffing.page='staffing' where u.active>0 and d.active>0 and (u.role=4 OR u.role=3) and u.practiceId=1 and LENGTH(u.logix2)>10$searchAdd GROUP BY a.dept, l.deptId, b.deptId");
    
    
	if($dbData){
		$outJobs = array();
        $jobArray = array();
		
		foreach($dbData as $ante){
			$unitName = $ante['deptName'];
			if($ante['updated']=='00-00-00'){
				$updated = '';
			}else{
				$updated = $ante['updated'];
			}
			
			//$log = 	strval($ante['logix2']);
			
			$qrcode = '<a href="javascript:;" onclick="tj.resetqrcodes(' . $ante['deptNum'] . ');return false;" >Reset</a>';
			//$printqr = '<a href="javascript:;" onclick="tj.printqrcodes(' . $ante['deptNum'] . ',' . $ante['logix2'] . ');return false;" >Print</a>';
			if(intval($role)>4){
			$printqr = "<a href=\"#qrView?i=" . $ante['deptNum'] . "\">Print</a>";
			}else{
			$printqr = "Print";
			}
			$desc = 'PRINT PAGE OR RESET ALL QR CODES FOR THIS UNIT';
				$jobArray[] = array(
				'unit' => $unitName,
				'qrcode' => $printqr . ' / ' . $qrcode,
				'updated' => $updated,
				'desc' => $desc	
            );
			
			
			if(intval($ante['qbldeptId'])!=0){
			$desc = 'QBL Calculator &nbsp; <a href="javascript:;" onclick="tj.openQBLConfig(' . $ante['deptNum'] . ');return false;" style="font-size:12px;">[Configure]</a>';
			$qrcode = $ante['qbl_token']
				? '<a href="javascript:;" onclick="tj.gogenerateQRCode(\'' . $ante['qbl_token'] . '\');return false;" >Get QR Code</a>'
				: '<span style="color:#999">Reset to generate</span>';
				$jobArray[] = array(
				'unit' => $unitName,
				'desc' => $desc,
				'updated' => $updated,
				'qrcode' => $qrcode
            );
			}else{
			$desc = '';
			$qrcode = '';
			}
			/*
			if(intval($ante['qbldeptId'])!=0){		
			$p1 = rand(1,5);
			$qrcode = '<a href="javascript:;" onclick="tj.gogenerateQRCode(' . $ante['logix2'] . ',' . $p1 . ');return false;" >Get QR Code</a>';
			$desc = 'ABL Calculator';
				$jobArray[] = array(
				'unit' => $unitName,
				'desc' => $desc,
				'updated' => $updated,
				'qrcode' => $qrcode
            );
			}else{
			$desc = '';
			$qrcode = '';
			}
			*/			
		
			}
			
			
			/*
		foreach($dbData as $ante){
			$unitName = $ante['deptName'] . ' - Urgent QBL';	
			
			if(intval($ante['qbldeptId'])!=0){		
			$p1 = rand(1,9);
			$qrcode = '<a href="javascript:;" onclick="tj.gogenerateQRCode(' . $ante['logix2'] . ',10);return false;" >Get QR Code</a>';
			$desc = 'QBL Calculator';
				$jobArray[] = array(
				'unit' => $unitName,
				'desc' => $desc,
				'qrcode' => $qrcode
            );
			}else{
			$desc = '';
			$qrcode = '';
			}
						
		
			}
			*/
		foreach($dbData as $ante){
			if(intval($ante['title'])==0){
			$desc = '';
			$qrcode = '';				
			}else{
			$unitName = $ante['deptName'];	
			$desc = 'Tasks(ie. Crash Cart)';
			$qrcode = $ante['tasks_token']
				? '<a href="javascript:;" onclick="tj.gogenerateQRCode(\'' . $ante['tasks_token'] . '\');return false;" >Get QR Code</a>'
				: '<span style="color:#999">Reset to generate</span>';
			$jobArray[] = array(
				'unit' => $unitName,
				'desc' => $desc,
				'updated' => $updated,
				'qrcode' => $qrcode
            );
			}
						
		}	
		
		foreach($dbData as $ante){
			$unitName = $ante['deptName'];	
			
			if(intval($ante['aactive'])==1){
			$desc = 'Staffing Report';
			$qrcode = $ante['staffing_token']
				? '<a href="javascript:;" onclick="tj.gogenerateQRCode(\'' . $ante['staffing_token'] . '\');return false;" >Get QR Code</a>'
				: '<span style="color:#999">Reset to generate</span>';
			$jobArray[] = array(
				'unit' => $unitName,
				'desc' => $desc,
				'updated' => $updated,
				'qrcode' => $qrcode
            );
			
			}else{
			$desc = '';
			$qrcode = '';
			}
						
			
			}
			//$outJobs[] = $ante;
         	
		$outData = array();
		$outData['data'] = $jobArray;
		echo json_encode($outData,JSON_NUMERIC_CHECK);
		}else {
        $outData = array();
		$jobArray[] = array(
				'unit' => '',
				'desc' => '',
				'updated' => '',
				'qrcode' => ''
            );
        $outData['data'] = $jobArray;
        echo json_encode($outData,JSON_NUMERIC_CHECK);
		}
}

/////////////////////////////////////
// GET CRASH INFO
function GetqblTableData() {
	//$idsearch = isset($_REQUEST['id']) && ($_REQUEST['id'] != 0) ? $_REQUEST['id'] : '';
    $startDate = isset($_REQUEST['start']) ? $_REQUEST['start'] : date('yyyy-mm-dd');
    $endDate = isset($_REQUEST['end']) ? $_REQUEST['end'] : date('yyyy-mm-dd');
	$taskTime = isset($_REQUEST['tasksTime']) ? $_REQUEST['tasksTime'] : '';
    $userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
	//$deptId = Config::get('account')['deptid'];
	$role = Config::get('account')['role'];
	$enterpriseId = Config::get('account')['enterpriseId'];
	
	if(intval($role)>7 && intval($role)<14){
		$searchAdd = " AND d.accountId={$accountId}";
	}else if(Intval($role)>=14){
		$searchAdd = " AND a.enterpriseId={$enterpriseId}";
	}else{
		$searchAdd = " AND d.accountId={$accountId} AND l.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId} and `primaryUnit`>0)";	
	}

    $dbData = Config::get('db')->get_results("SELECT l.*, r.lastStartDate, r.lastEndDate, IFNULL(u.logix2,'0') as logix2, DATE_FORMAT(l.dateTime,'%m-%d-%y') as dayDate, d.dept as deptName FROM
            `productiveQBLlog` l
        LEFT JOIN 
			`ProductiveDept` as d on d.id = l.deptId
		LEFT JOIN
        	`productiveUser` as r on r.id =$userId
		LEFT OUTER JOIN
        	`productiveUser` as u on u.id = l.userId
        WHERE
            l.dayDate >= r.lastStartDate AND l.dayDate <= r.lastEndDate AND l.totalWt >0 AND l.totalQBL < l.totalWt AND l.reset=0 AND d.active>0$searchAdd		
		ORDER BY l.dateTime DESC, d.dept ASC");
      
    if($dbData){
		$outJobs = array();
        $jobArray = array();
		foreach($dbData as $ante){
			
			$dateCompleted = $ante['dateTime'];
			
			$weight= $ante['totalWt'] . 'g';
			$qblName= ROUND(FLOATVAL($ante['totalQBL']),0) . 'ml';
			$note= $ante['note'];
			
			if(floatval($ante['abl'])>0){
			$ablName= round(floatval($ante['abl']),0) . 'ml';
			}else{
			$ablName = '';
			}
			
		
			
			if(intval($ante['logix2'])>0){
			$p1 = rand(1,9);
				$qrcode = '<a href="javascript:;" onclick="tj.qgenerateQRCode(' . $ante['logix2'] . ',' . $p1 . ');return false;" >QR Code</a>';
			}else{
				$qrcode = '';
			}
		
			//$unitName = "<a href=\"#" . $view . "?id=" . $ante['deptId'] . "&s=" . $startDate . "&e=" . $endDate . "\" >" . $ante['dept'] . "</a>";
			$qbl = "<a href=\"javascript:;\" onclick=\"tj.editQBL(" . $ante['id'] . ");\">" . $qblName . "</a>";
			$abl = "<a href=\"javascript:;\" onclick=\"tj.editABL(" . $ante['id'] . ");\">" . $ablName . "</a>";
			$unitName = $ante['deptName'];	
			
						
			$jobArray[] = array(
				'unit' => $unitName,
				'date' => $dateCompleted,
				'weight' => $weight,
				'qbl' => $qbl,
				'abl' => $abl,
				'note' => $note
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
				'weight' => '',
				'qbl' => '',
				'abl' => '',
				'note' => ''
            );
        $outData['data'] = $jobArray;
        echo json_encode($outData,JSON_NUMERIC_CHECK);
		}
}



/////////////////////////////////////
// GET PERFORMANCE INFO
function GetEscalationsTableData() {
	$idsearch = isset($_REQUEST['id']) && ($_REQUEST['id'] != 0) ? $_REQUEST['id'] : '';
	$unitsearch = isset($_REQUEST['u']) && ($_REQUEST['u'] != 0) ? $_REQUEST['u'] : '';
    $startDate = isset($_REQUEST['start']) ? $_REQUEST['start'] : date('yyyy-mm-dd');
    $endDate = isset($_REQUEST['end']) ? $_REQUEST['end'] : date('yyyy-mm-dd');
    $userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
	//$deptId = Config::get('account')['deptid'];
	$role = Config::get('account')['role'];
	$enterpriseId = Config::get('account')['enterpriseId'];
	
	if(intval($role)<=8 && !$idsearch && !$unitsearch){
		$searchAdd = " AND e.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId} and `primaryUnit`>0)";
	}else if(intval($role)>8 && intval($role)<14 && !$idsearch && !$unitsearch){
		$searchAdd = " AND e.accountId={$accountId}";
	}else if(intval($role)>=14 && !$idsearch && !$unitsearch){
		$searchAdd = " AND t.enterpriseId={$enterpriseId}";
	}else if($idsearch && !$unitsearch){
		$searchAdd = " AND e.userId={$idsearch}";
	}else if($unitsearch && !$idsearch){
		$searchAdd = " AND e.deptId={$unitsearch}";
	}else{
		$searchAdd = " AND e.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId`={$userId} and `primaryUnit`>0)";
	}

    $dbData = Config::get('db')->get_results("SELECT e.*, n.submittedby, c.last_name as closedLast, c.first_name as closedFirst, t.name as accountName, t.nickName, t.enterpriseId, a.escalation as escName, u.first_name, u.last_name, d.dept FROM `productiveEscalations` e LEFT OUTER JOIN `productiveNewData` as n on n.id=e.dataId LEFT OUTER JOIN `productiveUser` as c on c.id = e.closedId LEFT JOIN `productiveAcctEscalations` as a on a.id = e.escalation LEFT JOIN `ProductiveDept` as d on d.id = e.deptId LEFT JOIN `productiveAccount` as t on t.id = d.accountId LEFT JOIN `productiveUser` as u on u.id = e.userId WHERE d.active>0 AND e.active=1$searchAdd group by e.id order by e.dateSubmitted desc");
      
    if($dbData){
		$outJobs = array();
        $jobArray = array();
		foreach($dbData as $ante){
		$comment = '';
		$comment .='';
		
		$respData = Config::get('db')->get_results("SELECT r.*, u.last_name, u.first_name FROM `productiveEscResponse` r LEFT JOIN `productiveEscalations` as e on e.id = r.escId LEFT JOIN `productiveUser` as u on u.id=r.userId where `escId`={$ante['id']} ORDER BY r.id DESC");
     	
		if($respData){	
		foreach($respData as $resp){
		$comment .= $resp['responseTime'] . ' - ' . $resp['first_name'] . ' ' . $resp['last_name'] . ': ' . $resp['response'] . ' < > ';
		}
		}
			if($ante['submittedby']){
			//$user = "<a href=\"#escalations?id=".$ante['userId']."\" >" . $ante['last_name'].", ".$ante['first_name'] . "</a>";
			$user = $ante['submittedby'];
			}else{
			$user = $ante['last_name'].", ".$ante['first_name'];
			}
			
			if(intval($role)==11 || intval($role)==12){
			//$date = '<a href="javascript:;" onclick="tj.getEsc(' . $ante['id'] . ')" >' . $ante['dateSubmitted'] . '</a>' . "<button type=\"button\" id=\"clearEsc\" class=\"btn btn-danger btn-sm\" onclick=\"tj.clearEsc(" . $ante['id'] . "); \">Del</button>";
			$date = $ante['dateSubmitted'];
			}else{
			$date = $ante['dateSubmitted'];
			}
			
			$accountName = $ante['nickName'];
			$unitName = $ante['dept'];
			
			if(intval($ante['active'])==2){
			$type = $ante['escName'] . " (Closed by " . $ante['closedFirst'] . " " . $ante['closedLast'] . " on " . $ante['closedDate'] . ")";
			}else{
			$type = $ante['escName'] . " " . $ante['escalationName'];
			}
			$note = $ante['note'] . "<button type=\"button\" id=\"clearEsc\" class=\"btn btn-danger btn-sm\" onclick=\"tj.clearEsc(" . $ante['id'] . "); \">Del</button>" . " <button type=\"button\" class=\"btn btn-warning btn-sm\" onclick=\"tj.closeEsc(" . $ante['id'] . ");\">Close</button>";
			
			if(intval($ante['escType'])==1){
			$style = 0;
			}else{
			$style = 0;
			}
			
			$jobArray[] = array(
				'date' => $date,
				'unit' => $unitName,
				'user' => $user,
				'type' => $type,
				'notes' => $note,
				'accountName' => $accountName,
				'style' => $style
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
				'notes' => '',
				'accountName' => '',
				'style' => ''
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
	}else if(intval($role)>8 && intval($role)<14){
		$searchAdd = " AND d.accountId={$accountId}";
	}else if(intval($role)>=14){
		$searchAdd = " AND t.enterpriseId={$enterpriseId}";
	}else{
		$searchAdd = " AND e.userId={$idsearch}";	
	}
	
	if($idsearch){
		$groupBy = " AND e.deptId ={$idsearch} GROUP BY e.id";
	}else{
		$groupBy = " GROUP BY e.deptId";
	}
	

    $dbData = Config::get('db')->get_results("SELECT e.*, SUM(e.empEngagement) as engageSum, SUM((CASE WHEN e.empEngagement>0 THEN 1 ELSE 0 END)) as engageCount, count(e.id) as eCount, t.nickName as accountName, t.enterpriseId, u.first_name, u.last_name, d.dept FROM `productiveDayRank` e LEFT JOIN `ProductiveDept` as d on d.id = e.deptId LEFT JOIN `productiveAccount` as t on t.id = d.accountId LEFT JOIN `productiveUser` as u on u.id = e.userId WHERE d.active>0 AND DATE_FORMAT(e.dayDate,'%Y-%m-%d') >='{$startDate}' AND DATE_FORMAT(e.dayDate,'%Y-%m-%d') <='{$endDate}'$searchAdd$groupBy order by e.dayDate desc, d.dept asc");
      
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
			
			$accountName = $ante['nickName'];
			if(Intval($role)>=14){
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
function SavedayData() {
	$dataId = (isset($_REQUEST['dataId'])?$_REQUEST['dataId']:0);
	$data1 = (isset($_REQUEST['data1'])?$_REQUEST['data1']:0);
	$data2 = (isset($_REQUEST['data2'])?$_REQUEST['data2']:0);
	$data3 = (isset($_REQUEST['data3'])?$_REQUEST['data3']:0);
	$data4 = (isset($_REQUEST['data4'])?$_REQUEST['data4']:0);
	$currentTime = (isset($_REQUEST['currentTime'])?$_REQUEST['currentTime']: '');
	$userId = Config::get('account')['id'];
		
	
	if($dataId && intval($dataId)!=0){
	$updatedata = array(
		'dayCount1' => $data1,
		'dayCount2' => $data2,
		'dayCount3' => $data3,
		'dayCount4' => $data4,
		'updateTime' => $currentTime,
		'updatedBy' => $userId
		);
	$updateWhere = array('id' => $dataId);
				
	$updatedayData = Config::get('db')->update('productivedeptDayData',$updatedata,$updateWhere);
   
    }
	    
        echo json_encode(array('success' => true));
        return true;
}

function GetdayData() {
	$dataId = (isset($_REQUEST['dataId'])?$_REQUEST['dataId']:0);
	$startDate = isset($_REQUEST['start']) ? $_REQUEST['start'] : '';
	$endDate = isset($_REQUEST['end']) ? $_REQUEST['end'] : '';
	
	$userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
    $role = Config::get('account')['role'];
	$dbData = array();
	$dayData = Config::get('db')->get_results("SELECT a.*, DATE_FORMAT(a.updateTime,'%m/%d %H:%i') as upTime, '{$userId}' as logId, '{$role}' as roleId, IFNULL(u.id,'0') as userId, IFNULL(r.last_name,'') as lastname, IFNULL(r.first_name,'') as firstname, d.dept as deptName, (a.dayCount1 + a.dayCount2 + a.dayCount3 + a.dayCount4) as dayCount, d.dayDatatitle, d.track1Desc, d.track2Desc, d.track3Desc, d.track4Desc, d.dayDataTitle 
		FROM `productivedeptDayData` a 
		LEFT JOIN `productiveDeptXref` as x on x.deptId=a.deptId and x.primaryUnit=7
		LEFT JOIN `ProductiveDept` as d on d.id = a.deptId
		LEFT OUTER JOIN `productiveUser` as u on u.id = x.userId
		LEFT OUTER JOIN `productiveUser` as r on r.id = a.updatedBy
		WHERE a.id ={$dataId} AND a.dayDate >= '{$startDate}' AND a.dayDate <= '{$endDate}'
		");
		if($dayData){
        $dbData = $dayData[0];
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
		
	$dbLinks = Config::get('db')->get_results("select d.linkUnits from `ProductiveDept` d LEFT JOIN `productiveNewData` as n on n.deptId=d.id WHERE n.id={$dataId}");
	if(strlen($dbLinks[0]['linkUnits'] ?? '')>1){
	$links = $dbLinks[0]['linkUnits'];
	}else{
	$links = "0,0";	
	}
	
	$dbProd = Config::get('db')->get_results("select n.*, c.bedCount, c.easTerm, c.eanTerm, a.hidenote1, a.nurseTrack, a.hidenote2, a.hidenote3, a.hidenote4, a.linkUnits, a.trackptdata, a.resourcesTitle, a.linkedUnit, (CASE WHEN a.linkedUnit>0 THEN (SELECT `dept` FROM `ProductiveDept` WHERE `id`=(SELECT `deptId` FROM `productiveNewData` WHERE `userId`=0 AND `deptId` IN ({$links}) AND `dayDate`=n.dayDate and `shift`=n.shift and `deptId`!=n.deptId ORDER BY `deptId` DESC LIMIT 0,1)) ELSE 0 END) as linkedDept, (CASE WHEN a.linkedUnit>0 THEN (SELECT `id` FROM `productiveNewData` WHERE `userId`=0 AND `deptId` IN ({$links}) AND `dayDate`=n.dayDate and `shift`=n.shift and `deptId`!=n.deptId ORDER BY `deptId` DESC LIMIT 0,1) ELSE 0 END) as linkedId, p.id as adminId, p.role, g.griddeptId, g.census, g.cn, g.rn, g.rn1, g.rn2, g.pct, g.sitter, g.sec, g.other1, g.other2, g.other3, g.dayNight, h.griddeptId as ggriddeptId, h.census as gcensus, h.cn as gcn, h.rn as grn, h.rn1 as grn1, h.rn2 as grn2, h.pct as gpct, h.sitter as gsitter, h.sec as gsec, h.other1 as gother1, h.other2 as gother2, h.other3 as gother3, h.dayNight as gdayNight, a.patientcountTitle, h.dow as gdow, IFNULL(a.customChurnLabel,0) as churnLabel, ROUND(n.chargecount,1) as chargecount1, ROUND(n.antecount,1) as antecount1, ROUND(n.nvariance,1) as roundnvariance, IFNULL(e.escalation,0) as escalationValue, IFNULL(e.note,'') as escalationNote, IFNULL(ae.escalation,'') as escalationType, a.showEpic, a.showEpicNurse, IFNULL(a.other1Desc,0) as other1Label, a.useFlow, a.showrntest, a.ptTotal2, a.ptTotal3, a.ptTotal4, a.averageAcuity, a.gridOpt1, a.gridOpt2, a.gridOpt3, a.checkboxName, a.varianceName, a.chargeDesc, a.nedocs, a.showHPPS, IFNULL(a.sittersNEWDesc,0) as sittersLabel, IFNULL(a.other2Desc,0) as other2Label, IFNULL(a.nurse1Desc,0) as nurse1Label, IFNULL(a.nurse2Desc,0) as nurse2Label, IFNULL(a.other3Desc,0) as other3Label, a.sittersNEWDesc, a.uosDefault, a.unitCategory, a.trackdc, a.trackdata, a.roundrn, a.other1Calc, a.minRN, a.track1Desc, a.countcharge, a.actionPlan, a.rnCount, a.rn1Count, a.rn2Count, a.track2Desc, a.track3Desc, a.add1, a.add2, a.add3, a.add4, a.add5, a.add6, a.extadd6, a.unlockedPatientCount, a.track4Desc, a.typetrack1, a.typetrack2, a.patienttitleExt, a.typetrack3, a.typetrack4, a.indHRS, a.indUOS, a.secView, a.indHRSDesc, a.indUOSDesc, a.uosDisplay, a.rnVariance as displayProd, a.dept as depname, a.nurseDesc, a.showVisits, a.patientCustomDivider, a.customWHP, a.customWHPvalue, a.customWHP2value, a.customWHP3value, a.customWHP4value, a.customDesc, a.customWHP2, a.customDesc2, a.customWHP3, a.customDesc3, a.customWHP4, a.customDesc4, a.customWHP5, a.customDesc5, a.halfNurse, a.inshiftProd, a.prodMeasure, a.hppd, ROUND((a.hppd / (select count(`dept`) from `productiveAccountXref` where `type`<2 AND `whpPlan` !=1 and `dept`=n.deptId)),1) as hppsTarget, ROUND((n.actualWHP / (select count(`dept`) from `productiveAccountXref` where `type`<2 AND `whpPlan` !=1 and `dept`=n.deptId)),1) as hppsActual, a.useGrid, a.nurse1, a.nurse1Desc, a.nurse2Desc, a.showrn, a.indMeasure, a.patientTotalDesc, a.patientTotalDesc2, a.patientTotalDesc3, a.patientTotalDesc4, ROUND(a.charge,1) as charge1, ROUND(n.hrsVariance,2) as currentVar, n.id as dataId2, DATE_FORMAT(DATE_SUB(n.dayDate, INTERVAL 1 DAY),'%m/%d/%Y') as reportdate2, DATE_FORMAT(n.entered,'%m/%d/%Y %H:%i') as reportdate, TIME_FORMAT(s.shiftTime,'%H:%i') as reportshift, n.atotal as totalpatients, a.totalbeds, a.churn, a.uosDesc, (a.oneto1 + a.oneto2 + a.oneto3 + a.oneto4 + a.oneto5 + a.oneto6) as acuityTotal, (a.oneto1 + a.oneto2 + a.oneto3 + a.oneto4 + a.oneto5 + a.oneto6) as acuityTotal2, a.oneto1Acuity, a.oneto2Acuity, a.oneto3Acuity, a.oneto4Acuity, a.oneto5Acuity, a.oneto6Acuity, a.oneto1 as acuity1, a.oneto2 as acuity2, a.oneto3 as acuity3, a.oneto4 as acuity4, a.oneto5 as acuity5, a.extoneto6 as extacuity6, a.oneto6 as acuity6, a.oneto7 as acuity7, a.oneto7Acuity, a.oneto8 as acuity8, a.oneto8Acuity, a.oneto9 as acuity9, a.oneto9Acuity, c.prodTerm, c.hrsTerm, c.varMessage, c.rnvarianceLive, a.oneto10 as acuity10, a.oneto10Acuity, a.oneto11 as acuity11, a.oneto11Acuity, a.oneto12 as acuity12, a.oneto12Acuity, a.desc1, a.desc2, a.desc3, a.desc4, a.desc5, a.extdesc6, a.desc6, a.dash5, a.desc7, a.desc8, a.desc9, a.desc10, a.desc11, a.desc12, a.skill1, a.skill2, a.skill3, a.skill4, a.skill5, a.skill6, a.skill7, a.skill8, a.skill9, a.skill10, a.skill11, IFNULL(a.nurseDesc,0) as nurseLabel, IFNULL(a.secLabel,0) as sLabel, a.secLabel, IFNULL(a.techLabel,0) as tLabel, a.techLabel, a.skilldesc1, a.skilldesc2, a.skilldesc3, a.skilldesc4, a.skilldesc5, a.skilldesc6, a.skilldesc7, a.skilldesc8, a.skilldesc9, a.skilldesc10, a.skilldesc11, IFNULL(u.first_name,'') as first_name, IFNULL(u.last_name,'') as last_name, p.practiceId, IFNULL(b.blockedBeds,0) as blockedBeds, (a.totalbeds-n.atotal-b.blockedBeds) as openbeds, (n.chargecount - g.cn) as cnvar, (n.antecount - g.rn) as rnvar, (n.techcount - g.pct) as pctvar, (n.seccount - g.sec) as secvar, (n.customNurse - g.rn1) as rn1var, (n.customNurse2 - g.rn2) as rn2var, ((n.otherNurse1 * a.other1Calc) - g.other1) as other1var, (n.otherNurse2 - g.other2) as other2var, (n.otherNurse3 - g.other3) as other3var, (n.sittercount - g.sitter) as sitvar, (n.chargecount - h.cn) as gcnvar, (n.antecount - h.rn) as grnvar, (n.techcount - h.pct) as gpctvar, (n.seccount - h.sec) as gsecvar, (n.customNurse - h.rn1) as grn1var, (n.customNurse2 - h.rn2) as grn2var, ((n.otherNurse1 * a.other1Calc) - h.other1) as gother1var, (n.otherNurse2 - h.other2) as gother2var, (n.otherNurse3 - h.other3) as gother3var, (n.sittercount - h.sitter) as gsitvar from `productiveNewData` n left outer join `productiveStaffingGrid` as h on h.griddeptId=n.deptId AND (h.dayNight=n.shift OR h.dayNight=((n.shift)-1)) AND h.dow=DAYOFWEEK(n.dayDate) left outer join `productiveStaffingGrid` as g on g.griddeptId=n.deptId and g.dayNight=n.shift and g.census=n.atotal left join `productiveAccount` as c on c.id=n.accountId left join `productiveShifts` as s on s.id = n.shift left join `ProductiveDept` as a on a.id = n.deptId left join `productiveUser` as p on p.id={$userId} left join `productiveUser` as u on u.id = n.userId left outer join `productiveblockedBeds` as b on b.accountId = n.accountId and b.deptId = n.deptId LEFT OUTER JOIN `productiveEscalations` as e on e.dataId = n.id LEFT OUTER JOIN `productiveAcctEscalations` as ae on ae.id = e.escalation where n.id={$dataId} group by n.id");
	
	/*
	$dbProd = Config::get('db')->get_results("select n.*, g.easTarget, g.easMax, c.easTerm, c.eanTerm, a.linkUnits, a.trackptdata, a.resourcesTitle, a.linkedUnit, IFNULL((SELECT SUM(`flowCount`)as flowCount FROM `productiveFlow` WHERE `flowdeptId`=n.deptId AND `flowdayDate`=n.dayDate AND `flowShift`=n.shift),0)as inFlow, (CASE WHEN a.linkedUnit>0 THEN (SELECT `dept` FROM `ProductiveDept` WHERE `id`=(SELECT `deptId` FROM `productiveNewData` WHERE `userId`=0 AND `deptId` IN ({$links}) AND `dayDate`=n.dayDate and `shift`=n.shift and `deptId`!=n.deptId ORDER BY `deptId` DESC LIMIT 0,1)) ELSE 0 END) as linkedDept, (CASE WHEN a.linkedUnit>0 THEN (SELECT `id` FROM `productiveNewData` WHERE `userId`=0 AND `deptId` IN ({$links}) AND `dayDate`=n.dayDate and `shift`=n.shift and `deptId`!=n.deptId ORDER BY `deptId` DESC LIMIT 0,1) ELSE 0 END) as linkedId, (CASE WHEN a.flow1>0 THEN (SELECT `dept` FROM `ProductiveDept` WHERE `id`=a.flow1) ELSE 0 END) as flowDept1, (CASE WHEN a.flow2>0 THEN (SELECT `dept` FROM `ProductiveDept` WHERE `id`=a.flow2) ELSE 0 END) as flowDept2, (CASE WHEN a.flow3>0 THEN (SELECT `dept` FROM `ProductiveDept` WHERE `id`=a.flow3) ELSE 0 END) as flowDept3, (CASE WHEN a.flow4>0 THEN (SELECT `dept` FROM `ProductiveDept` WHERE `id`=a.flow4) ELSE 0 END) as flowDept4, (CASE WHEN a.flow5>0 THEN (SELECT `dept` FROM `ProductiveDept` WHERE `id`=a.flow5) ELSE 0 END) as flowDept5, (CASE WHEN a.flow6>0 THEN (SELECT `dept` FROM `ProductiveDept` WHERE `id`=a.flow6) ELSE 0 END) as flowDept6, (CASE WHEN a.flow7>0 THEN (SELECT `dept` FROM `ProductiveDept` WHERE `id`=a.flow7) ELSE 0 END) as flowDept7, (CASE WHEN a.flow8>0 THEN (SELECT `dept` FROM `ProductiveDept` WHERE `id`=a.flow8) ELSE 0 END) as flowDept8, p.id as adminId, p.role, g.griddeptId, g.census, g.cn, g.rn, g.rn1, g.rn2, g.pct, g.sitter, g.sec, g.other1, g.other2, g.other3, g.dayNight, h.griddeptId as ggriddeptId, h.census as gcensus, h.cn as gcn, h.rn as grn, h.rn1 as grn1, h.rn2 as grn2, h.pct as gpct, h.sitter as gsitter, h.sec as gsec, h.other1 as gother1, h.other2 as gother2, h.other3 as gother3, h.dayNight as gdayNight, a.patientcountTitle, h.dow as gdow, IFNULL(a.customChurnLabel,0) as churnLabel, ROUND(n.chargecount,1) as chargecount1, ROUND(n.antecount,1) as antecount1, ROUND(n.nvariance,1) as roundnvariance, IFNULL(e.escalation,0) as escalationValue, IFNULL(e.note,'') as escalationNote, IFNULL(ae.escalation,'') as escalationType, a.showEpic, a.showEpicNurse, IFNULL(a.other1Desc,0) as other1Label, a.useFlow, a.showrntest, a.ptTotal2, a.ptTotal3, a.ptTotal4, a.averageAcuity, a.gridOpt1, a.gridOpt2, a.chargeDesc, a.showHPPS, IFNULL(a.sittersNEWDesc,0) as sittersLabel, IFNULL(a.other2Desc,0) as other2Label, IFNULL(a.nurse1Desc,0) as nurse1Label, IFNULL(a.nurse2Desc,0) as nurse2Label, IFNULL(a.other3Desc,0) as other3Label, a.sittersNEWDesc, a.uosDefault, a.unitCategory, a.trackdc, a.trackdata, a.roundrn, a.track1Desc, a.countcharge, a.actionPlan, a.rnCount, a.rn1Count, a.rn2Count, a.track2Desc, a.track3Desc, a.unlockedPatientCount, a.track4Desc, a.typetrack1, a.typetrack2, a.typetrack3, a.typetrack4, a.flow1, a.flow2, a.flow3, a.flow4, a.flow5, a.flow6, a.flow7, a.flow8, a.indHRS, a.indUOS, a.secView, a.indHRSDesc, a.indUOSDesc, a.uosDisplay, a.rnVariance as displayProd, a.dept as depname, a.nurseDesc, a.showVisits, a.patientCustomDivider, a.customWHP, a.customWHPvalue, a.customWHP2value, a.customWHP3value, a.customWHP4value, a.customDesc, a.customWHP2, a.customDesc2, a.customWHP3, a.customDesc3, a.customWHP4, a.customDesc4, a.customWHP5, a.customDesc5, a.halfNurse, a.inshiftProd, a.prodMeasure, a.hppd, ROUND((a.hppd / (select count(`dept`) from `productiveAccountXref` where `type`<2 AND `whpPlan` !=1 and `dept`=n.deptId)),1) as hppsTarget, ROUND((n.actualWHP / (select count(`dept`) from `productiveAccountXref` where `type`<2 AND `whpPlan` !=1 and `dept`=n.deptId)),1) as hppsActual, a.useGrid, a.nurse1, a.nurse1Desc, a.nurse2Desc, a.showrn, a.indMeasure, a.patientTotalDesc, a.patientTotalDesc2, a.patientTotalDesc3, a.patientTotalDesc4, ROUND(a.charge,1) as charge1, ROUND(n.hrsVariance,2) as currentVar, n.id as dataId2, DATE_FORMAT(DATE_SUB(n.dayDate, INTERVAL 1 DAY),'%m/%d/%Y') as reportdate2, DATE_FORMAT(n.dayDate,'%m/%d/%Y') as reportdate, TIME_FORMAT(s.shiftTime,'%H:%i') as reportshift, n.atotal as totalpatients, a.totalbeds, a.churn, a.uosDesc, (a.oneto1 + a.oneto2 + a.oneto3 + a.oneto4 + a.oneto5 + a.oneto6) as acuityTotal, (a.oneto1 + a.oneto2 + a.oneto3 + a.oneto4 + a.oneto5 + a.oneto6) as acuityTotal2, a.oneto1Acuity, a.oneto2Acuity, a.oneto3Acuity, a.oneto4Acuity, a.oneto5Acuity, a.oneto6Acuity, a.oneto1 as acuity1, a.oneto2 as acuity2, a.oneto3 as acuity3, a.oneto4 as acuity4, a.oneto5 as acuity5, a.oneto6 as acuity6, a.oneto7 as acuity7, a.oneto7Acuity, a.oneto8 as acuity8, a.oneto8Acuity, a.oneto9 as acuity9, a.oneto9Acuity, c.prodTerm, c.hrsTerm, c.varMessage, c.rnvarianceLive, a.oneto10 as acuity10, a.oneto10Acuity, a.oneto11 as acuity11, a.oneto11Acuity, a.oneto12 as acuity12, a.oneto12Acuity, a.desc1, a.desc2, a.desc3, a.desc4, a.desc5, a.desc6, a.desc7, a.desc8, a.desc9, a.desc10, a.desc11, a.desc12, a.skill1, a.skill2, a.skill3, a.skill4, a.skill5, a.skill6, a.skill7, a.skill8, a.skill9, a.skill10, a.skill11, IFNULL(a.nurseDesc,0) as nurseLabel, IFNULL(a.secLabel,0) as sLabel, a.secLabel, IFNULL(a.techLabel,0) as tLabel, a.techLabel, a.skilldesc1, a.skilldesc2, a.skilldesc3, a.skilldesc4, a.skilldesc5, a.skilldesc6, a.skilldesc7, a.skilldesc8, a.skilldesc9, a.skilldesc10, a.skilldesc11, IFNULL(u.first_name,'') as first_name, IFNULL(u.last_name,'') as last_name, p.practiceId, IFNULL(b.blockedBeds,0) as blockedBeds, (a.totalbeds-n.atotal-b.blockedBeds) as openbeds, (n.chargecount - g.cn) as cnvar, (n.antecount - g.rn) as rnvar, (n.techcount - g.pct) as pctvar, (n.seccount - g.sec) as secvar, (n.customNurse - g.rn1) as rn1var, (n.customNurse2 - g.rn2) as rn2var, (n.otherNurse1 - g.other1) as other1var, (n.otherNurse2 - g.other2) as other2var, (n.otherNurse3 - g.other3) as other3var, (n.sittercount - g.sitter) as sitvar, (n.chargecount - h.cn) as gcnvar, (n.antecount - h.rn) as grnvar, (n.techcount - h.pct) as gpctvar, (n.seccount - h.sec) as gsecvar, (n.customNurse - h.rn1) as grn1var, (n.customNurse2 - h.rn2) as grn2var, (n.otherNurse1 - h.other1) as gother1var, (n.otherNurse2 - h.other2) as gother2var, (n.otherNurse3 - h.other3) as gother3var, (n.sittercount - h.sitter) as gsitvar from `productiveNewData` n left outer join `productiveStaffingGrid` as h on h.griddeptId=n.deptId AND (h.dayNight=n.shift OR h.dayNight=((n.shift)-1)) AND h.dow=DAYOFWEEK(n.dayDate) left outer join `productiveStaffingGrid` as g on g.griddeptId=n.deptId and g.dayNight=n.shift and g.census=n.atotal left join `productiveAccount` as c on c.id=n.accountId left join `productiveShifts` as s on s.id = n.shift left join `ProductiveDept` as a on a.id = n.deptId left join `productiveUser` as p on p.id={$userId} left join `productiveUser` as u on u.id = n.userId left outer join `productiveblockedBeds` as b on b.accountId = n.accountId and b.deptId = n.deptId LEFT OUTER JOIN `productiveEscalations` as e on e.dataId = n.id LEFT OUTER JOIN `productiveAcctEscalations` as ae on ae.id = e.escalation where n.id={$dataId} group by n.id");

	$dbTime = Config::get('db')->get_results("SELECT t.*, s.shiftTime, n.shift, n.id as dataId FROM `taImport` t LEFT JOIN `productiveNewData` as n on n.deptId=t.deptId LEFT JOIN `productiveShifts` as s on s.id=n.shift WHERE n.id={$dataId} AND TIME(t.recordTime) <= s.shiftTime AND TIME(t.recordTime) >= s.shiftTime - INTERVAL 1 HOUR");
	
	if($dbTime){
		$timeData=$dbTime[0];
		$isTime = 1;
	}else{
		$timeData = array();
		$isTime = 0;
	}
	*/
	/*
	$dbTransfers = Config::get('db')->get_results("SELECT f.* FROM `productiveFlow` f LEFT JOIN `productiveNewData` as n on n.dayDate=f.flowdayDate and n.deptId=f.outdeptId and n.shift=f.flowShift WHERE n.id={$dataId} GROUP BY f.id");
	if(count($dbTransfers)>0){
		$transfers = array();
		
		foreach($dbTransfers as $trDetail){
		$i= $trDetail['flowposId'];
		$transfers['outflowCount'.$i.''] = $trDetail['outflowCount'];	
		}		
	}
	*/
	$transfers = array();
		
	if($dbProd){
    $dbData = $dbProd[0];	
    //echo json_encode(array('success' => true,'data'=>$dbData,'transfers'=>$transfers,'timeData'=>$timeData,'isTime'=>$isTime), JSON_NUMERIC_CHECK);
	echo json_encode(array('success' => true,'data'=>$dbData,'transfers'=>$transfers), JSON_NUMERIC_CHECK);
    return true;
    }else{
    echo json_encode(array('success'=>false,'message'=>'Record not found.'));
	return false;
	}
}

function GetunscheduledDetails() {
	$dataId = (isset($_REQUEST['dataId']) ? $_REQUEST['dataId']:'');
	$deptId = (isset($_REQUEST['deptId']) ? $_REQUEST['deptId']:'');
	$userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
    $role = Config::get('account')['role'];
		
	$dbLinks = Config::get('db')->get_results("select d.linkUnits from `ProductiveDept` d WHERE d.id={$deptId}");
	if(strlen($dbLinks[0]['linkUnits'] ?? '')>1){
	$links = $dbLinks[0]['linkUnits'];
	}else{
	$links = "0,0";	
	}
	
	$dbProd = Config::get('db')->get_results("select n.*, c.easTerm, c.eanTerm, a.linkUnits, a.trackptdata, a.resourcesTitle, a.linkedUnit, (CASE WHEN a.linkedUnit>0 THEN (SELECT `dept` FROM `ProductiveDept` WHERE `id`=(SELECT `deptId` FROM `productiveNewData` WHERE `userId`=0 AND `deptId` IN ({$links}) AND `dayDate`=n.dayDate and `shift`=n.shift and `deptId`!=n.deptId ORDER BY `deptId` DESC LIMIT 0,1)) ELSE 0 END) as linkedDept, (CASE WHEN a.linkedUnit>0 THEN (SELECT `id` FROM `productiveNewData` WHERE `userId`=0 AND `deptId` IN ({$links}) AND `dayDate`=n.dayDate and `shift`=n.shift and `deptId`!=n.deptId ORDER BY `deptId` DESC LIMIT 0,1) ELSE 0 END) as linkedId, p.id as adminId, p.role, g.griddeptId, g.census, g.cn, g.rn, g.rn1, g.rn2, g.pct, g.sitter, g.sec, g.other1, g.other2, g.other3, g.dayNight, h.griddeptId as ggriddeptId, h.census as gcensus, h.cn as gcn, h.rn as grn, h.rn1 as grn1, h.rn2 as grn2, h.pct as gpct, h.sitter as gsitter, h.sec as gsec, h.other1 as gother1, h.other2 as gother2, h.other3 as gother3, h.dayNight as gdayNight, a.patientcountTitle, h.dow as gdow, IFNULL(a.customChurnLabel,0) as churnLabel, ROUND(n.chargecount,1) as chargecount1, ROUND(n.antecount,1) as antecount1, ROUND(n.nvariance,1) as roundnvariance, IFNULL(e.escalation,0) as escalationValue, IFNULL(e.note,'') as escalationNote, IFNULL(ae.escalation,'') as escalationType, a.showEpic, a.showEpicNurse, IFNULL(a.other1Desc,0) as other1Label, a.useFlow, a.showrntest, a.ptTotal2, a.ptTotal3, a.ptTotal4, a.averageAcuity, a.gridOpt1, a.gridOpt2, a.gridOpt3, a.chargeDesc, a.showHPPS, IFNULL(a.sittersNEWDesc,0) as sittersLabel, IFNULL(a.other2Desc,0) as other2Label, IFNULL(a.nurse1Desc,0) as nurse1Label, IFNULL(a.nurse2Desc,0) as nurse2Label, IFNULL(a.other3Desc,0) as other3Label, a.sittersNEWDesc, a.uosDefault, a.unitCategory, a.trackdc, a.trackdata, a.roundrn, a.other1Calc, a.minRN, a.track1Desc, a.countcharge, a.actionPlan, a.rnCount, a.rn1Count, a.rn2Count, a.track2Desc, a.track3Desc, a.unlockedPatientCount, a.track4Desc, a.typetrack1, a.typetrack2, a.typetrack3, a.typetrack4, a.indHRS, a.indUOS, a.secView, a.indHRSDesc, a.indUOSDesc, a.uosDisplay, a.rnVariance as displayProd, a.dept as depname, a.nurseDesc, a.showVisits, a.patientCustomDivider, a.customWHP, a.customWHPvalue, a.customWHP2value, a.customWHP3value, a.customWHP4value, a.customDesc, a.customWHP2, a.customDesc2, a.customWHP3, a.customDesc3, a.customWHP4, a.customDesc4, a.customWHP5, a.customDesc5, a.halfNurse, a.inshiftProd, a.prodMeasure, a.hppd, ROUND((a.hppd / (select count(`dept`) from `productiveAccountXref` where `type`<2 AND `whpPlan` !=1 and `dept`=n.deptId)),1) as hppsTarget, ROUND((n.actualWHP / (select count(`dept`) from `productiveAccountXref` where `type`<2 AND `whpPlan` !=1 and `dept`=n.deptId)),1) as hppsActual, a.useGrid, a.nurse1, a.nurse1Desc, a.nurse2Desc, a.showrn, a.indMeasure, a.patientTotalDesc, a.patientTotalDesc2, a.patientTotalDesc3, a.patientTotalDesc4, ROUND(a.charge,1) as charge1, ROUND(n.hrsVariance,2) as currentVar, n.id as dataId2, DATE_FORMAT(DATE_SUB(n.dayDate, INTERVAL 1 DAY),'%m/%d/%Y') as reportdate2, DATE_FORMAT(n.dayDate,'%m/%d/%Y') as reportdate, TIME_FORMAT(s.shiftTime,'%H:%i') as reportshift, n.atotal as totalpatients, a.totalbeds, a.churn, a.uosDesc, (a.oneto1 + a.oneto2 + a.oneto3 + a.oneto4 + a.oneto5 + a.oneto6) as acuityTotal, (a.oneto1 + a.oneto2 + a.oneto3 + a.oneto4 + a.oneto5 + a.oneto6) as acuityTotal2, a.oneto1Acuity, a.oneto2Acuity, a.oneto3Acuity, a.oneto4Acuity, a.oneto5Acuity, a.oneto6Acuity, a.oneto1 as acuity1, a.oneto2 as acuity2, a.oneto3 as acuity3, a.oneto4 as acuity4, a.oneto5 as acuity5, a.oneto6 as acuity6, a.oneto7 as acuity7, a.oneto7Acuity, a.oneto8 as acuity8, a.oneto8Acuity, a.oneto9 as acuity9, a.oneto9Acuity, c.prodTerm, c.hrsTerm, c.varMessage, c.rnvarianceLive, a.oneto10 as acuity10, a.oneto10Acuity, a.oneto11 as acuity11, a.oneto11Acuity, a.oneto12 as acuity12, a.oneto12Acuity, a.desc1, a.desc2, a.desc3, a.desc4, a.desc5, a.desc6, a.desc7, a.desc8, a.desc9, a.desc10, a.desc11, a.desc12, a.skill1, a.skill2, a.skill3, a.skill4, a.skill5, a.skill6, a.skill7, a.skill8, a.skill9, a.skill10, a.skill11, IFNULL(a.nurseDesc,0) as nurseLabel, IFNULL(a.secLabel,0) as sLabel, a.secLabel, IFNULL(a.techLabel,0) as tLabel, a.techLabel, a.skilldesc1, a.skilldesc2, a.skilldesc3, a.skilldesc4, a.skilldesc5, a.skilldesc6, a.skilldesc7, a.skilldesc8, a.skilldesc9, a.skilldesc10, a.skilldesc11, IFNULL(u.first_name,'') as first_name, IFNULL(u.last_name,'') as last_name, p.practiceId, IFNULL(b.blockedBeds,0) as blockedBeds, (a.totalbeds-n.atotal-b.blockedBeds) as openbeds, (n.chargecount - g.cn) as cnvar, (n.antecount - g.rn) as rnvar, (n.techcount - g.pct) as pctvar, (n.seccount - g.sec) as secvar, (n.customNurse - g.rn1) as rn1var, (n.customNurse2 - g.rn2) as rn2var, ((n.otherNurse1 * a.other1Calc) - g.other1) as other1var, (n.otherNurse2 - g.other2) as other2var, (n.otherNurse3 - g.other3) as other3var, (n.sittercount - g.sitter) as sitvar, (n.chargecount - h.cn) as gcnvar, (n.antecount - h.rn) as grnvar, (n.techcount - h.pct) as gpctvar, (n.seccount - h.sec) as gsecvar, (n.customNurse - h.rn1) as grn1var, (n.customNurse2 - h.rn2) as grn2var, ((n.otherNurse1 * a.other1Calc) - h.other1) as gother1var, (n.otherNurse2 - h.other2) as gother2var, (n.otherNurse3 - h.other3) as gother3var, (n.sittercount - h.sitter) as gsitvar from `productiveNewData` n left outer join `productiveStaffingGrid` as h on h.griddeptId=n.deptId AND (h.dayNight=n.shift OR h.dayNight=((n.shift)-1)) AND h.dow=DAYOFWEEK(n.dayDate) left outer join `productiveStaffingGrid` as g on g.griddeptId=n.deptId and g.dayNight=n.shift and g.census=n.atotal left join `productiveAccount` as c on c.id=n.accountId left join `productiveShifts` as s on s.id = n.shift left join `ProductiveDept` as a on a.id = n.deptId left join `productiveUser` as p on p.id={$userId} left join `productiveUser` as u on u.id = n.userId left outer join `productiveblockedBeds` as b on b.accountId = n.accountId and b.deptId = n.deptId LEFT OUTER JOIN `productiveEscalations` as e on e.dataId = n.id LEFT OUTER JOIN `productiveAcctEscalations` as ae on ae.id = e.escalation where n.deptId={$deptId} and n.userId=0 group by n.deptId");
	
	$transfers = array();
		
	if($dbProd){
    $dbData = $dbProd[0];	
	echo json_encode(array('success' => true,'data'=>$dbData,'transfers'=>$transfers), JSON_NUMERIC_CHECK);
    return true;
    }else{
    echo json_encode(array('success'=>false,'message'=>'Record not found.'));
	return false;
	}
}


///////////////////////////
//Get Transfers

function getTransfers() {
	$dataId = (isset($_REQUEST['dataId']) ? $_REQUEST['dataId']:'');
	$dbFlow = Config::get('db')->get_results("select f.*, (CASE WHEN f.outdeptId<=1 THEN 'External' ELSE d.dept END) as dept FROM `productiveFlow` f LEFT OUTER JOIN `ProductiveDept` as d on d.id=f.outdeptId LEFT JOIN `productiveNewData` as n on n.deptId=f.flowdeptId and n.shift=f.flowShift and n.dayDate=f.flowdayDate where f.flowdeptId=(SELECT `deptId` FROM `productiveNewData` where `id`={$dataId}) AND f.flowShift=(SELECT `shift` FROM `productiveNewData` where `id`={$dataId}) AND f.flowdayDate=(SELECT `dayDate` FROM `productiveNewData` where `id`={$dataId})");  
	$flowlist = "";
	$flowlist .="";
	if(count($dbFlow)>0){
	foreach($dbFlow as $dflow){
		if(intval($dflow['flowCount'])>0){
		$flowlist .=$dflow['dept'] . "  -  " . $dflow['flowCount']  . "</br> Note: " . $dflow['note']. "</br></br>";
		}
	}
	$tfrs=array(
	'tfrs' =>$flowlist
	);
	
	}	
 echo json_encode(array('success' => true,'data'=>$tfrs), JSON_NUMERIC_CHECK);
 return true;

}

/////////////////////////////////////
// GET DETAILS FOR SINGLE USER
function GetProdDetailswhp() {
	$dataId = (isset($_REQUEST['dataId']) ? $_REQUEST['dataId']:'');
	//$gridId = (isset($_REQUEST['gridId']) ? $_REQUEST['gridId']:'');
		
		$userId = Config::get('account')['id'];
        $accountId = Config::get('account')['accountId'];
        $role = Config::get('account')['role'];
		
		$dbLinks = Config::get('db')->get_results("select d.linkUnits, d.useGrid from `ProductiveDept` d LEFT JOIN `productiveNewData` as n on n.deptId=d.id WHERE n.id={$dataId}");
		if(strlen($dbLinks[0]['linkUnits'] ?? '')>1){
		$links = $dbLinks[0]['linkUnits'];
		$gridId = $dbLinks[0]['useGrid'];
		}else{
		$links = "0,0";	
		$gridId = $dbLinks[0]['useGrid'];
		}
		
		if(intval($gridId)==6){
		$dbProd = Config::get('db')->get_results("select n.*, a.autoPop, a.linkUnits, c.productivityPosNeg, a.resourcesTitle, a.linkedUnit, (CASE WHEN a.linkedUnit>0 THEN (SELECT `dept` FROM `ProductiveDept` WHERE `id`=(SELECT `deptId` FROM `productiveNewData` WHERE `userId`=0 AND `deptId` IN ({$links}) AND `dayDate`=n.dayDate and `shift`=n.shift and `deptId`!=n.deptId ORDER BY `deptId` DESC LIMIT 0,1)) ELSE 0 END) as linkedDept, (CASE WHEN a.linkedUnit>0 THEN (SELECT `id` FROM `productiveNewData` WHERE `userId`=0 AND `deptId` IN ({$links}) AND `dayDate`=n.dayDate and `shift`=n.shift and `deptId`!=n.deptId ORDER BY `deptId` DESC LIMIT 0,1) ELSE 0 END) as linkedId, p.id as adminId, p.role, g.griddeptId, g.census, g.cn, g.rn, g.rn1, g.rn2, g.pct, g.sitter, g.sec, g.other1, g.other2, g.other3, g.dayNight, (j.cn + j.rn + j.rn1 + j.rn2 + j.pct + j.sitter + j.sec + j.other1 + j.other2 + j.other3) as jhours, j.griddeptId as jgriddeptId, j.census as jcensus, j.cn as jcn, j.rn as jrn, j.rn1 as jrn1, j.rn2 as jrn2, j.pct as jpct, j.sitter as jsitter, j.sec as jsec, j.other1 as jother1, j.other2 as jother2, j.other3 as jother3, j.dayNight as jdayNight, j.dow as jdow, ((n.skill1val - j.cn) * c.productivityPosNeg) as jcnvar, ((n.skill2val - j.rn) * c.productivityPosNeg) as jrnvar, ((n.skill5val - j.sec) * c.productivityPosNeg) as jsecvar, ((n.skill3val - j.rn1) * c.productivityPosNeg) as jrn1var, ((n.skill4val - j.rn2) * c.productivityPosNeg) as jrn2var, ((n.skill6val - j.other1) * c.productivityPosNeg) as jother1var, ((n.skill7val - j.other2) * c.productivityPosNeg) as jother2var, ((n.skill8val - j.other3) * c.productivityPosNeg) as jother3var, ((n.skill9val - j.pct) * c.productivityPosNeg) as jpctvar, ((n.skill10val - j.sitter) * c.productivityPosNeg) as jsitvar, (h.cn + h.rn + h.rn1 + h.rn2 + h.pct + h.sitter + h.sec + h.other1 + h.other2 + h.other3) as hhours, h.griddeptId as hgriddeptId, h.census as hcensus, h.cn as hcn, h.rn as hrn, h.rn1 as hrn1, h.rn2 as hrn2, h.pct as hpct, h.sitter as hsitter, h.sec as hsec, h.other1 as hother1, h.other2 as hother2, h.other3 as hother3, h.dayNight as hdayNight, h.dow as hdow, ((n.skill1val - h.cn) * c.productivityPosNeg) as hcnvar, ((n.skill2val - h.rn) * c.productivityPosNeg) as hrnvar, ((n.skill5val - h.sec) * c.productivityPosNeg) as hsecvar, ((n.skill3val - h.rn1) * c.productivityPosNeg) as hrn1var, ((n.skill4val - h.rn2) * c.productivityPosNeg) as hrn2var, ((n.skill6val - h.other1) * c.productivityPosNeg) as hother1var, ((n.skill7val - h.other2) * c.productivityPosNeg) as hother2var, ((n.skill8val - h.other3) * c.productivityPosNeg) as hother3var, ((n.skill9val - h.pct) * c.productivityPosNeg) as hpctvar, ((n.skill10val - h.sitter) * c.productivityPosNeg) as hsitvar, a.patientcountTitle, IFNULL(a.customChurnLabel,0) as churnLabel, ROUND(n.chargecount,1) as chargecount1, ROUND(n.antecount,1) as antecount1, ROUND(n.nvariance,1) as nvariance1, IFNULL(e.escalation,0) as escalationValue, IFNULL(e.note,'') as escalationNote, IFNULL(ae.escalation,'') as escalationType, z.procedureCount as todayProcs, z.actualWHP as todayWHP, z.plannedHours as todayHours, ROUND((z.actualWHP - a.hppd)* z.procedureCount,2) as todayVar, IFNULL(a.other1Desc,0) as other1Label, a.showrntest, a.linkedUnit, a.chargeDesc, IFNULL(a.sittersNEWDesc,0) as sittersLabel, IFNULL(a.other2Desc,0) as other2Label, IFNULL(a.nurse1Desc,0) as nurse1Label, IFNULL(a.nurse2Desc,0) as nurse2Label, IFNULL(a.other3Desc,0) as other3Label, a.sittersNEWDesc, a.uosDefault, a.unitCategory, a.trackdc, a.trackdata, a.roundrn, a.track1Desc, a.countcharge, a.actionPlan, a.rnCount, a.rn1Count, a.rn2Count, a.track2Desc, a.track3Desc, a.track4Desc, a.typetrack1, a.typetrack2, a.typetrack3, a.typetrack4, a.indHRS, a.indUOS, a.secView, a.indHRSDesc, a.indUOSDesc, a.uosDisplay, a.rnVariance as displayProd, a.dept as depname, a.nurseDesc, a.showVisits, a.patientCustomDivider, a.customWHP, a.customWHPvalue, a.customWHP2value, a.customWHP3value, a.customWHP4value, a.customWHP5value, a.customDesc, a.customWHP2, a.customDesc2, a.customWHP3, a.customDesc3, a.customWHP4, a.customDesc4, a.customWHP5, a.customDesc5, a.halfNurse, a.inshiftProd, a.prodMeasure, a.hppd, a.useGrid, a.nurse1, a.nurse1Desc, a.nurse2Desc, a.showrn, a.indMeasure, a.patientTotalDesc, a.patientTotalDesc2, a.patientTotalDesc3, a.patientTotalDesc4, ROUND(a.charge,1) as charge1, ROUND(n.hrsVariance,2) as currentVar, n.id as dataId2, DATE_FORMAT(DATE_SUB(n.dayDate, INTERVAL 1 DAY),'%m/%d/%Y') as reportdate2, DATE_FORMAT(n.dayDate,'%m/%d/%Y') as reportdate, s.shiftName as reportshift, n.atotal as totalpatients, a.totalbeds, a.churn, a.uosDesc, (a.oneto1 + a.oneto2 + a.oneto3 + a.oneto4 + a.oneto5 + a.oneto6) as acuityTotal, (a.oneto1 + a.oneto2 + a.oneto3 + a.oneto4 + a.oneto5 + a.oneto6) as acuityTotal2, a.oneto1Acuity, a.oneto2Acuity, a.oneto3Acuity, a.oneto4Acuity, a.oneto5Acuity, a.oneto6Acuity, a.oneto1 as acuity1, a.oneto2 as acuity2, a.oneto3 as acuity3, a.oneto4 as acuity4, a.oneto5 as acuity5, a.oneto6 as acuity6, a.oneto7 as acuity7, a.oneto7Acuity, a.oneto8 as acuity8, a.oneto8Acuity, a.oneto9 as acuity9, a.oneto9Acuity, c.varMessage, c.prodTerm, c.hrsTerm, c.rnvarianceLive, a.oneto10 as acuity10, a.oneto10Acuity, a.oneto11 as acuity11, a.oneto11Acuity, a.oneto12 as acuity12, a.oneto12Acuity, a.desc12, a.desc1, a.desc2, a.desc3, a.desc4, a.desc5, a.desc6, a.desc7, a.desc8, a.desc9, a.desc10, a.desc11, a.skill1, a.skill2, a.skill3, a.skill4, a.skill5, a.skill6, a.skill7, a.skill8, a.skill9, a.skill10, a.skill11, IFNULL(a.secLabel,0) as sLabel, a.planVal3, a.secLabel, IFNULL(a.techLabel,0) as tLabel, a.techLabel, a.skilldesc1, a.skilldesc2, a.skilldesc3, a.skilldesc4, a.skilldesc5, a.skilldesc6, a.skilldesc7, a.skilldesc8, a.skilldesc9, a.skilldesc10, a.skilldesc11, IFNULL(u.first_name,'') as first_name, IFNULL(u.last_name,'') as last_name, u.practiceId as practice, p.practiceId, IFNULL(b.blockedBeds,0) as blockedBeds, (a.totalbeds-n.atotal-b.blockedBeds) as openbeds, ((n.skill1val - g.cn) * c.productivityPosNeg) as cnvar, ((n.skill2val - g.rn) * c.productivityPosNeg) as rnvar, ((n.skill5val - g.sec) * c.productivityPosNeg) as secvar, ((n.skill3val - g.rn1) * c.productivityPosNeg) as rn1var, ((n.skill4val - g.rn2) * c.productivityPosNeg) as rn2var, ((n.skill6val - g.other1) * c.productivityPosNeg) as other1var, ((n.skill7val - g.other2) * c.productivityPosNeg) as other2var, ((n.skill8val - g.other3) * c.productivityPosNeg) as other3var, ((n.skill9val - g.pct) * c.productivityPosNeg) as pctvar, ((n.skill10val - g.sitter) * c.productivityPosNeg) as sittervar, (g.cn + g.rn + g.rn1 + g.rn2 + g.pct + g.sitter + g.sec + g.other1 + g.other2 + g.other3) as ghours, g.griddeptId as ggriddeptId, g.census as gcensus, g.cn as gcn, g.rn as grn, g.rn1 as grn1, g.rn2 as grn2, g.pct as gpct, g.sitter as gsitter, g.sec as gsec, g.other1 as gother1, g.other2 as gother2, g.other3 as gother3, g.dayNight as gdayNight, g.dow as gdow, ((n.skill1val - g.cn) * c.productivityPosNeg) as gcnvar, ((n.skill2val - g.rn) * c.productivityPosNeg) as grnvar, ((n.skill5val - g.sec) * c.productivityPosNeg) as gsecvar, ((n.skill3val - g.rn1) * c.productivityPosNeg) as grn1var, ((n.skill4val - g.rn2) * c.productivityPosNeg) as grn2var, ((n.skill6val - g.other1) * c.productivityPosNeg) as gother1var, ((n.skill7val - g.other2) * c.productivityPosNeg) as gother2var, ((n.skill8val - g.other3) * c.productivityPosNeg) as gother3var, ((n.skill9val - g.pct) * c.productivityPosNeg) as gpctvar, ((n.skill10val - g.sitter) * c.productivityPosNeg) as gsitvar from `productiveNewData` n left outer join `productiveStaffingGrid` as j on j.griddeptId=n.deptId AND (j.dayNight=n.shift OR j.dayNight=((n.shift)-1)) AND j.dow=DAYOFWEEK(n.dayDate) and j.census=n.procedureCount left outer join `productiveStaffingGrid` as h on h.griddeptId=n.deptId AND (h.dayNight=n.shift OR h.dayNight=((n.shift)-1)) AND h.dow=DAYOFWEEK(n.dayDate) left outer join `productiveStaffingGrid` as g on g.griddeptId=n.deptId and g.dayNight=n.shift and g.census=n.procedureCount AND g.dow=DAYOFWEEK(n.dayDate) left join `productiveAccount` as c on c.id=n.accountId left join `productiveAccountXref` as s on s.shift = n.shift AND s.dept=n.deptId left join `ProductiveDept` as a on a.accountId = n.accountId and a.id = n.deptId left join `productiveUser` as p on p.id={$userId} left join `productiveUser` as u on u.id = n.userId left outer join `productiveblockedBeds` as b on b.accountId = n.accountId and b.deptId = n.deptId LEFT JOIN `productiveNewData` as z on z.deptId = n.deptId and z.dayDate = n.dayDate and z.shift=n.shift LEFT OUTER JOIN `productiveEscalations` as e on e.dataId = n.id LEFT OUTER JOIN `productiveAcctEscalations` as ae on ae.id = e.escalation where n.accountId ={$accountId} AND n.id={$dataId} group by n.id");
		}else{
		$dbProd = Config::get('db')->get_results("select n.*, a.autoPop, a.linkUnits, c.productivityPosNeg, a.resourcesTitle, a.linkedUnit, (CASE WHEN a.linkedUnit>0 THEN (SELECT `dept` FROM `ProductiveDept` WHERE `id`=(SELECT `deptId` FROM `productiveNewData` WHERE `userId`=0 AND `deptId` IN ({$links}) AND `dayDate`=n.dayDate and `shift`=n.shift and `deptId`!=n.deptId ORDER BY `deptId` DESC LIMIT 0,1)) ELSE 0 END) as linkedDept, (CASE WHEN a.linkedUnit>0 THEN (SELECT `id` FROM `productiveNewData` WHERE `userId`=0 AND `deptId` IN ({$links}) AND `dayDate`=n.dayDate and `shift`=n.shift and `deptId`!=n.deptId ORDER BY `deptId` DESC LIMIT 0,1) ELSE 0 END) as linkedId, p.id as adminId, p.role, g.griddeptId, g.census, g.cn, g.rn, g.rn1, g.rn2, g.pct, g.sitter, g.sec, g.other1, g.other2, g.other3, g.dayNight, (j.cn + j.rn + j.rn1 + j.rn2 + j.pct + j.sitter + j.sec + j.other1 + j.other2 + j.other3) as jhours, j.griddeptId as jgriddeptId, j.census as jcensus, j.cn as jcn, j.rn as jrn, j.rn1 as jrn1, j.rn2 as jrn2, j.pct as jpct, j.sitter as jsitter, j.sec as jsec, j.other1 as jother1, j.other2 as jother2, j.other3 as jother3, j.dayNight as jdayNight, j.dow as jdow, ((n.skill1val - j.cn) * c.productivityPosNeg) as jcnvar, ((n.skill2val - j.rn) * c.productivityPosNeg) as jrnvar, ((n.skill5val - j.sec) * c.productivityPosNeg) as jsecvar, ((n.skill3val - j.rn1) * c.productivityPosNeg) as jrn1var, ((n.skill4val - j.rn2) * c.productivityPosNeg) as jrn2var, ((n.skill6val - j.other1) * c.productivityPosNeg) as jother1var, ((n.skill7val - j.other2) * c.productivityPosNeg) as jother2var, ((n.skill8val - j.other3) * c.productivityPosNeg) as jother3var, ((n.skill9val - j.pct) * c.productivityPosNeg) as jpctvar, ((n.skill10val - j.sitter) * c.productivityPosNeg) as jsitvar, (h.cn + h.rn + h.rn1 + h.rn2 + h.pct + h.sitter + h.sec + h.other1 + h.other2 + h.other3) as hhours, h.griddeptId as hgriddeptId, h.census as hcensus, h.cn as hcn, h.rn as hrn, h.rn1 as hrn1, h.rn2 as hrn2, h.pct as hpct, h.sitter as hsitter, h.sec as hsec, h.other1 as hother1, h.other2 as hother2, h.other3 as hother3, h.dayNight as hdayNight, h.dow as hdow, ((n.skill1val - h.cn) * c.productivityPosNeg) as hcnvar, ((n.skill2val - h.rn) * c.productivityPosNeg) as hrnvar, ((n.skill5val - h.sec) * c.productivityPosNeg) as hsecvar, ((n.skill3val - h.rn1) * c.productivityPosNeg) as hrn1var, ((n.skill4val - h.rn2) * c.productivityPosNeg) as hrn2var, ((n.skill6val - h.other1) * c.productivityPosNeg) as hother1var, ((n.skill7val - h.other2) * c.productivityPosNeg) as hother2var, ((n.skill8val - h.other3) * c.productivityPosNeg) as hother3var, ((n.skill9val - h.pct) * c.productivityPosNeg) as hpctvar, ((n.skill10val - h.sitter) * c.productivityPosNeg) as hsitvar, a.patientcountTitle, IFNULL(a.customChurnLabel,0) as churnLabel, ROUND(n.chargecount,1) as chargecount1, ROUND(n.antecount,1) as antecount1, ROUND(n.nvariance,1) as nvariance1, IFNULL(e.escalation,0) as escalationValue, IFNULL(e.note,'') as escalationNote, IFNULL(ae.escalation,'') as escalationType, z.procedureCount as todayProcs, z.actualWHP as todayWHP, z.plannedHours as todayHours, ROUND((z.actualWHP - a.hppd)* z.procedureCount,2) as todayVar, IFNULL(a.other1Desc,0) as other1Label, a.showrntest, a.linkedUnit, a.chargeDesc, IFNULL(a.sittersNEWDesc,0) as sittersLabel, IFNULL(a.other2Desc,0) as other2Label, IFNULL(a.nurse1Desc,0) as nurse1Label, IFNULL(a.nurse2Desc,0) as nurse2Label, IFNULL(a.other3Desc,0) as other3Label, a.sittersNEWDesc, a.uosDefault, a.unitCategory, a.trackdc, a.trackdata, a.roundrn, a.track1Desc, a.countcharge, a.actionPlan, a.rnCount, a.rn1Count, a.rn2Count, a.track2Desc, a.track3Desc, a.track4Desc, a.typetrack1, a.typetrack2, a.typetrack3, a.typetrack4, a.indHRS, a.indUOS, a.secView, a.indHRSDesc, a.indUOSDesc, a.uosDisplay, a.rnVariance as displayProd, a.dept as depname, a.nurseDesc, a.showVisits, a.patientCustomDivider, a.customWHP, a.customWHPvalue, a.customWHP2value, a.customWHP3value, a.customWHP4value, a.customWHP5value, a.customDesc, a.customWHP2, a.customDesc2, a.customWHP3, a.customDesc3, a.customWHP4, a.customDesc4, a.customWHP5, a.customDesc5, a.halfNurse, a.inshiftProd, a.prodMeasure, a.hppd, a.useGrid, a.nurse1, a.nurse1Desc, a.nurse2Desc, a.showrn, a.indMeasure, a.patientTotalDesc, a.patientTotalDesc2, a.patientTotalDesc3, a.patientTotalDesc4, ROUND(a.charge,1) as charge1, ROUND(n.hrsVariance,2) as currentVar, n.id as dataId2, DATE_FORMAT(DATE_SUB(n.dayDate, INTERVAL 1 DAY),'%m/%d/%Y') as reportdate2, DATE_FORMAT(n.dayDate,'%m/%d/%Y') as reportdate, s.shiftName as reportshift, n.atotal as totalpatients, a.totalbeds, a.churn, a.uosDesc, (a.oneto1 + a.oneto2 + a.oneto3 + a.oneto4 + a.oneto5 + a.oneto6) as acuityTotal, (a.oneto1 + a.oneto2 + a.oneto3 + a.oneto4 + a.oneto5 + a.oneto6) as acuityTotal2, a.oneto1Acuity, a.oneto2Acuity, a.oneto3Acuity, a.oneto4Acuity, a.oneto5Acuity, a.oneto6Acuity, a.oneto1 as acuity1, a.oneto2 as acuity2, a.oneto3 as acuity3, a.oneto4 as acuity4, a.oneto5 as acuity5, a.oneto6 as acuity6, a.oneto7 as acuity7, a.oneto7Acuity, a.oneto8 as acuity8, a.oneto8Acuity, a.oneto9 as acuity9, a.oneto9Acuity, c.varMessage, c.prodTerm, c.hrsTerm, c.rnvarianceLive, a.oneto10 as acuity10, a.oneto10Acuity, a.oneto11 as acuity11, a.oneto11Acuity, a.oneto12 as acuity12, a.oneto12Acuity, a.desc12, a.desc1, a.desc2, a.desc3, a.desc4, a.desc5, a.desc6, a.desc7, a.desc8, a.desc9, a.desc10, a.desc11, a.skill1, a.skill2, a.skill3, a.skill4, a.skill5, a.skill6, a.skill7, a.skill8, a.skill9, a.skill10, a.skill11, IFNULL(a.secLabel,0) as sLabel, a.planVal3, a.secLabel, IFNULL(a.techLabel,0) as tLabel, a.techLabel, a.skilldesc1, a.skilldesc2, a.skilldesc3, a.skilldesc4, a.skilldesc5, a.skilldesc6, a.skilldesc7, a.skilldesc8, a.skilldesc9, a.skilldesc10, a.skilldesc11, IFNULL(u.first_name,'') as first_name, IFNULL(u.last_name,'') as last_name, u.practiceId as practice, p.practiceId, IFNULL(b.blockedBeds,0) as blockedBeds, (a.totalbeds-n.atotal-b.blockedBeds) as openbeds, ((n.skill1val - g.cn) * c.productivityPosNeg) as cnvar, ((n.skill2val - g.rn) * c.productivityPosNeg) as rnvar, ((n.skill5val - g.sec) * c.productivityPosNeg) as secvar, ((n.skill3val - g.rn1) * c.productivityPosNeg) as rn1var, ((n.skill4val - g.rn2) * c.productivityPosNeg) as rn2var, ((n.skill6val - g.other1) * c.productivityPosNeg) as other1var, ((n.skill7val - g.other2) * c.productivityPosNeg) as other2var, ((n.skill8val - g.other3) * c.productivityPosNeg) as other3var, ((n.skill9val - g.pct) * c.productivityPosNeg) as pctvar, ((n.skill10val - g.sitter) * c.productivityPosNeg) as sittervar, (g.cn + g.rn + g.rn1 + g.rn2 + g.pct + g.sitter + g.sec + g.other1 + g.other2 + g.other3) as ghours, g.griddeptId as ggriddeptId, g.census as gcensus, g.cn as gcn, g.rn as grn, g.rn1 as grn1, g.rn2 as grn2, g.pct as gpct, g.sitter as gsitter, g.sec as gsec, g.other1 as gother1, g.other2 as gother2, g.other3 as gother3, g.dayNight as gdayNight, g.dow as gdow, ((n.skill1val - g.cn) * c.productivityPosNeg) as gcnvar, ((n.skill2val - g.rn) * c.productivityPosNeg) as grnvar, ((n.skill5val - g.sec) * c.productivityPosNeg) as gsecvar, ((n.skill3val - g.rn1) * c.productivityPosNeg) as grn1var, ((n.skill4val - g.rn2) * c.productivityPosNeg) as grn2var, ((n.skill6val - g.other1) * c.productivityPosNeg) as gother1var, ((n.skill7val - g.other2) * c.productivityPosNeg) as gother2var, ((n.skill8val - g.other3) * c.productivityPosNeg) as gother3var, ((n.skill9val - g.pct) * c.productivityPosNeg) as gpctvar, ((n.skill10val - g.sitter) * c.productivityPosNeg) as gsitvar from `productiveNewData` n left outer join `productiveStaffingGrid` as j on j.griddeptId=n.deptId AND (j.dayNight=n.shift OR j.dayNight=((n.shift)-1)) AND j.dow=DAYOFWEEK(n.dayDate) and j.census=n.procedureCount left outer join `productiveStaffingGrid` as h on h.griddeptId=n.deptId AND (h.dayNight=n.shift OR h.dayNight=((n.shift)-1)) AND h.dow=DAYOFWEEK(n.dayDate) left outer join `productiveStaffingGrid` as g on g.griddeptId=n.deptId and g.dayNight=n.shift AND g.dow=DAYOFWEEK(n.dayDate) left join `productiveAccount` as c on c.id=n.accountId left join `productiveAccountXref` as s on s.shift = n.shift AND s.dept=n.deptId left join `ProductiveDept` as a on a.accountId = n.accountId and a.id = n.deptId left join `productiveUser` as p on p.id={$userId} left join `productiveUser` as u on u.id = n.userId left outer join `productiveblockedBeds` as b on b.accountId = n.accountId and b.deptId = n.deptId LEFT JOIN `productiveNewData` as z on z.deptId = n.deptId and z.dayDate = n.dayDate and z.shift=n.shift LEFT OUTER JOIN `productiveEscalations` as e on e.dataId = n.id LEFT OUTER JOIN `productiveAcctEscalations` as ae on ae.id = e.escalation where n.accountId ={$accountId} AND n.id={$dataId} group by n.id");
		}
		
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

		if($dbProd){
        $dbData = $dbProd[0];
		//$userD = $userData[0];
        echo json_encode(array('success' => true,'data'=>$dbData,'providers'=>$prData,'links'=>$links), JSON_NUMERIC_CHECK);
        return true;
    }else{
    echo json_encode(array('success'=>false,'message'=>'Record not found.'));
	return false;
	}
}

/////////////////////////////////////
// Grid Update
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
	$oneto12 = (isset($_REQUEST['oneto12']) ? $_REQUEST['oneto12']:0);
	$acuityTotal = (isset($_REQUEST['acuityTotal']) ? $_REQUEST['acuityTotal']:0);
	
	
		
	//$ccount1 = intval($oneto1) + intval($oneto2) + intval($oneto3) + intval($oneto4) + intval($oneto5) + intval($oneto6) + intval($oneto7) + intval($oneto8) + intval($oneto9) + intval($oneto10) + intval($oneto11);
    
	//if(intval($acuityTotal)==1){
	//	$ccount = $ccount1;
	//}else{
		$ccount = $census;
	//}
	
	if(intval($useGrid)==2 || intval($useGrid)==3){
		$gridtype = "g.griddeptId={$deptId} AND g.dayNight={$shift} AND g.dow=DAYOFWEEK('{$dayDate}')";
	}else{
		$gridtype = "g.griddeptId={$deptId} AND g.dayNight={$shift} AND g.census={$ccount}";
	}
		
		if($ccount>=0 && $deptId && $shift){
		$dbProd = Config::get('db')->get_results("select g.*, d.gridOpt1, d.gridOpt2, d.gridOpt3, d.minRN, d.other1Calc from `productiveStaffingGrid` g LEFT JOIN `ProductiveDept` as d on d.id=g.griddeptId where $gridtype");
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
// Grid Update
function gridUpdate5() {
	$userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
	$ccount = (isset($_REQUEST['census']) ? $_REQUEST['census']:0);
	$census1 = (isset($_REQUEST['census1']) ? $_REQUEST['census1']:0);
	$census2 = (isset($_REQUEST['census2']) ? $_REQUEST['census2']:0);
	$census3 = (isset($_REQUEST['census2']) ? $_REQUEST['census3']:0);
	$deptId = (isset($_REQUEST['deptId']) ? $_REQUEST['deptId']:'');
	$shift = (isset($_REQUEST['shift']) ? $_REQUEST['shift']:'');
	$useGrid = (isset($_REQUEST['useGrid']) ? $_REQUEST['useGrid']:0);
	$dayDate = (isset($_REQUEST['dayDate']) ? $_REQUEST['dayDate']:0);
		
	if(intval($useGrid)==2 || intval($useGrid)==3){
		$gridtype1 = "g.griddeptId={$deptId} AND g.dayNight={$shift} AND g.dow=DAYOFWEEK('{$dayDate}')";
		$gridtype2 = "g.griddeptId={$deptId} AND g.dayNight={$shift} AND g.dow=DAYOFWEEK('{$dayDate}')";
		$gridtype3 = "g.griddeptId={$deptId} AND g.dayNight={$shift} AND g.dow=DAYOFWEEK('{$dayDate}')";
	}else if(intval($useGrid)==7){
		$gridtype1 = "g.griddeptId={$deptId} AND g.dayNight={$shift} AND g.census={$census1}";
		$gridtype2 = "g.griddeptId={$deptId} AND g.dayNight={$shift} AND g.census={$census2}";
		$gridtype3 = "g.griddeptId={$deptId} AND g.dayNight={$shift} AND g.census={$census3}";
	}else{
		$gridtype1 = "g.griddeptId={$deptId} AND g.dayNight={$shift} AND g.census={$ccount}";
		$gridtype2 = "g.griddeptId={$deptId} AND g.dayNight={$shift} AND g.census={$ccount}";
		$gridtype3 = "g.griddeptId={$deptId} AND g.dayNight={$shift} AND g.census={$ccount}";
	}
	
		
		if($deptId && $shift){
		$dbProd = Config::get('db')->get_results("select g.*, d.gridOpt1, d.gridOpt2, d.gridOpt3, d.minRN, d.other1Calc from `productiveStaffingGrid` g LEFT JOIN `ProductiveDept` as d on d.id = g.griddeptId where $gridtype1");
		$dbProd2 = Config::get('db')->get_results("select g.*, d.gridOpt1, d.gridOpt2, d.gridOpt3, d.minRN, d.other1Calc from `productiveStaffingGrid` g LEFT JOIN `ProductiveDept` as d on d.id = g.griddeptId where $gridtype2");
		$dbProd3 = Config::get('db')->get_results("select g.*, d.gridOpt1, d.gridOpt2, d.gridOpt3, d.minRN, d.other1Calc from `productiveStaffingGrid` g LEFT JOIN `ProductiveDept` as d on d.id = g.griddeptId where $gridtype3");
       	}else{
		$dbProd = false;
		}
	
	if($dbProd){
        $dbData = $dbProd[0];
		$dbData2 = $dbProd2[0];
		$dbData3 = $dbProd3[0];
        echo json_encode(array('success' =>true,'data'=>$dbData,'data2'=>$dbData2,'data3'=>$dbData3));
        return true;
    }else{
    echo json_encode(array('success'=>false,'message'=>'Record not found.'));
	return false;
	}
}

/////////////////////////////////////
// Multi Census Grid
function gridUpdate3() {
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
	$oneto12 = (isset($_REQUEST['oneto12']) ? $_REQUEST['oneto12']:0);
	$acuityTotal = (isset($_REQUEST['acuityTotal']) ? $_REQUEST['acuityTotal']:0);
	
		$ccount = $census;
	
	if(intval($useGrid)==2 || intval($useGrid)==3){
		$gridtype = "`griddeptId`={$deptId} AND `dayNight`={$shift} AND `dow`=DAYOFWEEK('{$dayDate}')";
	}else{
		$gridtype = "`griddeptId`={$deptId} AND `dayNight`={$shift} AND `census`={$ccount}";
	}
		
		if($ccount>=0 && $deptId && $shift){
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
function gridUpdatewhp() {
	$ccount=0;
	$userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
	
	$census = (isset($_REQUEST['census']) ? $_REQUEST['census']:'');
	$deptId = (isset($_REQUEST['deptId']) ? $_REQUEST['deptId']:'');
	$shift = (isset($_REQUEST['shift']) ? $_REQUEST['shift']:'');
	$useGrid = (isset($_REQUEST['useGrid']) ? $_REQUEST['useGrid']:0);
	$dayDate = (isset($_REQUEST['dayDate']) ? $_REQUEST['dayDate']:0);
	
	$skill1 = (isset($_REQUEST['skill1']) ? $_REQUEST['skill1']:0);
	$skill2 = (isset($_REQUEST['skill2']) ? $_REQUEST['skill2']:0);
	$skill3 = (isset($_REQUEST['skill3']) ? $_REQUEST['skill3']:0);
	$skill4 = (isset($_REQUEST['skill4']) ? $_REQUEST['skill4']:0);
	$skill5 = (isset($_REQUEST['skill5']) ? $_REQUEST['skill5']:0);
	$skill6 = (isset($_REQUEST['skill6']) ? $_REQUEST['skill6']:0);
	$skill7 = (isset($_REQUEST['skill7']) ? $_REQUEST['skill7']:0);
	$skill8 = (isset($_REQUEST['skill8']) ? $_REQUEST['skill8']:0);
	$skill9 = (isset($_REQUEST['skill9']) ? $_REQUEST['skill9']:0);
	$skill10 = (isset($_REQUEST['skill10']) ? $_REQUEST['skill10']:0);
		
	$ccount1 = intval($skill1) + intval($skill2) + intval($skill3) + intval($skill4) + intval($skill5) + intval($skill6) + intval($skill7) + intval($skill8) + intval($skill9) + intval($skill10);
    
	$ccount = $census;
	
	if(intval($useGrid)==6){
	$gridtype = "`griddeptId`={$deptId} AND `dayNight`={$shift} AND `dow`=DAYOFWEEK('{$dayDate}') AND `census`={$census}";
	}else{
	$gridtype = "`griddeptId`={$deptId} AND `dayNight`={$shift} AND `dow`=DAYOFWEEK('{$dayDate}')";	
	}
		
		if($deptId && $shift){
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
function GetMsgDetails() {
	$dataId = (isset($_REQUEST['dataId'])?$_REQUEST['dataId']:'');
	$deptId = (isset($_REQUEST['deptId'])?$_REQUEST['deptId']:'0');
	$userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
    $role = Config::get('account')['role'];
	
	$dbLog = Config::get('db')->get_results("select m.*, DATE_FORMAT(m.expDate,'%m/%d/%Y') as expire, IFNULL(d.dept,'All Units') as dept FROM `productiveMessaging` m LEFT OUTER JOIN `ProductiveDept` as d on d.id=m.deptId WHERE m.accountId={$accountId} and m.id={$dataId}");
        	
	if($dbLog){
		$dbData = $dbLog[0];
    echo json_encode(array('success' => true,'data'=>$dbData));
    return true;
    }else{
    echo json_encode(array('success'=>false,'message'=>'Record not found.'));
	return false;
	}
}

function UpdateMsg() {
	$msgId = (isset($_REQUEST['msgId']) ? $_REQUEST['msgId']:false);
	$expDate = (isset($_REQUEST['expDate']) ? $_REQUEST['expDate']:'');
	$message = (isset($_REQUEST['message']) ? $_REQUEST['message']:'');
	$createdDate = (isset($_REQUEST['createdDate']) ? $_REQUEST['createdDate']:'');
	$deptId = (isset($_REQUEST['deptId']) ? $_REQUEST['deptId']:'0');
	
	$userId = Config::get('account')['id'];
	$accountId = Config::get('account')['accountId'];	
		
	if($msgId){
		
		$data = array(
				'expDate'=>$expDate,
				'message'=>addslashes($message)
				);
			
		$where = array(
				'id'=>$msgId,
				'accountId'=>$accountId
				);
		Config::get('db')->update('productiveMessaging',$data,$where);
		
		$msgdata = array(
				'expDate'=>$expDate,
				'message'=>addslashes($message),
				'accountId'=>$accountId,
				'createdDate'=>$createdDate,
				'deptId'=>$deptId,
				'createdBy'=>$userId
				);
			
		Config::get('db')->insert('productiveMessagingData',$msgdata);		
		
		echo json_encode(array('success'=>true,'updated'=>true));
		return true;
		}else{
		echo json_encode(array('success'=>true,'updated'=>false));
		return true;	
		}
}

function ClearMsg() {
	$dataId = (isset($_REQUEST['dataId']) ? $_REQUEST['dataId']:'');
	$userId = Config::get('account')['id'];
	$accountId = Config::get('account')['accountId'];	
	
	if($dataId){
		
		$data = array(
				'id'=>$dataId,
				'accountId'=>$accountId
				);

		Config::get('db')->delete('productiveMessaging',$data);
				
		
		echo json_encode(array('success'=>true,'updated'=>true));
		return true;
		}else{
		echo json_encode(array('success'=>true,'updated'=>false));
		return true;	
		}
}

function NewMsg() {
	$deptId = (isset($_REQUEST['deptId']) ? $_REQUEST['deptId']:'');
	$expDate = (isset($_REQUEST['expDate']) ? $_REQUEST['expDate']:'');
	$message = (isset($_REQUEST['message']) ? $_REQUEST['message']:'');
	$currentDate = (isset($_REQUEST['currentDate']) ? $_REQUEST['currentDate']:'');
	$userId = Config::get('account')['id'];
	$accountId = Config::get('account')['accountId'];	
	
	if($message){
		
		$data = array(
				'expDate'=>$expDate,
				'message'=>addslashes($message),
				'deptId'=>$deptId,
				'createdDate'=>$currentDate,
				'createdBy'=>$userId,
				'accountId'=>$accountId
				);
	
		Config::get('db')->insert('productiveMessaging',$data);
				
		
		echo json_encode(array('success'=>true,'updated'=>true));
		return true;
		}else{
		echo json_encode(array('success'=>true,'updated'=>false));
		return true;	
		}
}

/////////////////////////////////////
// GET DETAILS FOR SINGLE USER
function GetCrashDetails() {
	$dataId = (isset($_REQUEST['dataId'])?$_REQUEST['dataId']:'');
	$userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
    $role = Config::get('account')['role'];
	$select1 = array();
	$cdate = (isset($_REQUEST['cdate'])?$_REQUEST['cdate']:'');
	
	if(intval($role)<6){
	$data = array(
            'viewId' =>$userId,
			'submittedDateTime' =>$cdate
        );
	$updateWhere = array('id' =>$dataId);       
	$newBlock = Config::get('db')->update('productivecrashLog',$data,$updateWhere); 
	}
	
	$dbProd = Config::get('db')->get_results("select c.*, IFNULL(q.safetyDesc,'') as safetyDesc1, IFNULL(r.safetyDesc,'') as safetyDesc2, IFNULL(v.safetyDesc,'') as safetyDesc3, IFNULL(t.safetyDesc,'') as safetyDesc4, u.role, l.title, DATE_FORMAT(s.shiftTime, '%H:%i') as shiftTime, l.qType1, l.r1, l.qType2, l.r2, l.qType3, l.r3, l.qType4, l.r4, l.qType5, l.r5, l.qType6, l.r6, l.qType7, l.r7, l.qType8, l.r8, l.qType9, l.r9, l.qType10, l.r10, l.qType11, l.r11, l.qType12, l.r12, l.qType13, l.r13, l.qType14, l.r14, l.qType15, l.r15, l.instructions, DATE_FORMAT(c.dayDate,'%m/%d/%Y') as reportDate, d.dept, IFNULL(u.first_name,'') as first_name, IFNULL(u.last_name,'') as last_name, IFNULL(u.practiceId,'0') as practiceId FROM `productivecrashLog` c LEFT OUTER JOIN `productivesafetyConfig` as q on q.id=c.q2 LEFT OUTER JOIN `productivesafetyConfig` as r on r.id=c.q7 LEFT OUTER JOIN `productivesafetyConfig` as v on v.id=c.q12 LEFT OUTER JOIN `productivesafetyConfig` as t on t.id=c.q17 LEFT JOIN `productivelogData` as l on l.id=c.logId LEFT JOIN `productiveShifts` as s on s.id=l.shift LEFT OUTER JOIN `ProductiveDept` as d on d.id=c.deptId LEFT JOIN `productiveUser` as u on u.id={$userId} WHERE c.id={$dataId} GROUP BY c.id");
	$deptId = $dbProd[0]['deptId'];
	
	$dbMsgs = Config::get('db')->get_results("select m.*, u.last_name, u.first_name, u.role FROM `productiveMessaging` m LEFT JOIN `productiveUser` as u on u.id=m.createdBy WHERE m.accountId=26 AND (m.deptId={$deptId} OR (m.deptId=0 AND m.createdBy IN (SELECT `userId` FROM `productiveDeptXref` where `deptId`={$deptId}))) and (m.expDate > CURDATE() OR m.expDate='0000-00-00') GROUP BY m.id ORDER BY u.role ASC");
	
	
	
	
	if($dbMsgs){
		$newmsg1 = '';
		$newmsg1 .='';
		foreach($dbMsgs as $msg){
		$newmsg1 .= '</br><h5>' . $msg['first_name'] . ' ' . $msg['last_name'] . ': </h5>' . $msg['message'];
		}
	}else{
		$newmsg1 = '';
	}
	$newmsg2 = str_replace(".",".</br></br>",$newmsg1);
	$newmsg = str_replace(";",".</br></br>",$newmsg2);
	$logId = $dbProd[0]['logId'];
	$dbLog = Config::get('db')->get_results("select * FROM `productivelogData` WHERE `accountId`={$accountId} and `id`={$logId}");
        	
		if($dbLog){
        $dbData = $dbProd[0];
		$qData = $dbLog[0];
        echo json_encode(array('success' => true,'data'=>$dbData,'qData'=>$qData,'msg'=>$newmsg));
        return true;
    }else{
    echo json_encode(array('success'=>false,'message'=>'Record not found.'));
	return false;
	}
}

// GET DETAILS FOR SINGLE USER
function Getqbl() {
	$resetqbl = (isset($_REQUEST['resetqbl'])?$_REQUEST['resetqbl']:'0');
	$time = (isset($_REQUEST['currentTime'])?$_REQUEST['currentTime']:'');
	$userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
    $role = Config::get('account')['role'];
	$logId = (isset($_REQUEST['logId'])?$_REQUEST['logId']:'');
	$alertId = (isset($_REQUEST['alertId'])?$_REQUEST['alertId']:0);

	if(intval($resetqbl)==1 && intval($logId)>0){
	$data = array(
            'reset' =>1,
			'dateTime' =>$time
        );
	$updateWhere = array('id' =>$logId);

	$newBlock = Config::get('db')->update('productiveQBLlog',$data,$updateWhere);
	}


	$dbProd = Config::get('db')->get_results("select c.*, DATE_FORMAT(CURDATE(),'%Y-%m-%d') as dayDate, d.dept from `productiveQBLconfig` c left join `productiveDeptXref` as x on x.deptId=c.deptId LEFT JOIN `ProductiveDept` as d on d.id=c.deptId where x.userId=$userId and x.primaryUnit=4 and c.active=1");

	if($dbProd){
        $dbData = $dbProd[0];
        echo json_encode(array('success' => true,'data'=>$dbData));
        return true;
    }else{
    echo json_encode(array('success'=>false,'message'=>'Record not found.'));
	return false;
	}
}

function Getabl() {
	$dataId = (isset($_REQUEST['userId'])?$_REQUEST['userId']:'');
	//$roomId = (isset($_REQUEST['roomId'])?$_REQUEST['roomId']:'');
	$resetqbl = (isset($_REQUEST['resetqbl'])?$_REQUEST['resetqbl']:'0');
	$time = (isset($_REQUEST['currentTime'])?$_REQUEST['currentTime']:'');
	$userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
    $role = Config::get('account')['role'];
	$logId = (isset($_REQUEST['logId'])?$_REQUEST['logId']:'');
	//$alertId = (isset($_REQUEST['alertId'])?$_REQUEST['alertId']:'');
	
	/*
	if(intval($resetqbl)==1 && intval($logId)>0){
	$data = array(
            'reset' =>1,
			'dateTime' =>$time
        );
	$updateWhere = array('id' =>$logId);
            
	$newBlock = Config::get('db')->update('productiveQBLlog',$data,$updateWhere); 	
	}
	*/
	
	
	$dbProd = Config::get('db')->get_results("SELECT d.*, DATE_FORMAT(CURDATE(),'%Y-%m-%d') as dayDate FROM `ProductiveDept` d LEFT JOIN `productiveDeptXref` as x on x.deptId=d.id WHERE x.userId={$dataId}");
	
	if($dbProd){
		$deptId = $dbProd[0]['id'];
		$data = array(
            'deptId' =>$deptId,
			'userId' =>$dataId,
			'dateTime' =>$time,
			'dayDate' =>$dbProd[0]['dayDate']
        );
            
		Config::get('db')->insert('productiveQBLlog',$data);
		$newArray = array('id' => Config::get('db')->lastid());

		
        $dbData = $dbProd[0];
        echo json_encode(array('success' => true,'data'=>$dbData,'log'=>$newArray));
        return true;
    }else{
    echo json_encode(array('success'=>false,'message'=>'Record not found.'));
	return false;
	}
}

function qblDetails() {
	$dataId = (isset($_REQUEST['dataId'])?$_REQUEST['dataId']:'');
	$userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
    $role = Config::get('account')['role'];
	
	$dbProd = Config::get('db')->get_results("SELECT q.*, c.image1, c.value1, c.desc1, c.image2, c.value2, c.desc2, c.image3, c.value3, c.desc3, c.image4, c.value4, c.desc4, c.image5, c.value5, c.desc5, c.image6, c.value6, c.desc6, c.image7, c.value7, c.desc7, c.image8, c.value8, c.desc8, c.image9, c.value9, c.desc9, c.image10, c.value10, c.desc10, c.image11, c.value11, c.desc11, c.image12, c.value12, c.desc12, c.image13, c.value13, c.desc13, c.image14, c.value14, c.desc14, c.image15, c.value15, c.desc15, c.image16, c.value16, c.desc16, c.image17, c.value17, c.desc17, c.image18, c.value18, c.desc18, c.image19, c.value19, c.desc19, c.image20, c.value20, c.desc20, d.dept as deptName from `productiveQBLlog` q LEFT JOIN `productiveQBLconfig` as c on c.id=q.locId LEFT JOIN `ProductiveDept` as d on d.id=q.deptId LEFT JOIN `productiveUser` as u on u.id=q.userId where q.id={$dataId}");
   	
		if($dbProd){
        $dbData = $dbProd[0];
        echo json_encode(array('success' => true,'data'=>$dbData));
        return true;
    }else{
    echo json_encode(array('success'=>false,'message'=>'Record not found.'));
	return false;
	}
}

function Resetqrcodes() {
	$deptId = (isset($_REQUEST['deptId'])?$_REQUEST['deptId']:'');
	$updated = (isset($_REQUEST['currentTime'])?$_REQUEST['currentTime']:'');
	$userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
    $role = Config::get('account')['role'];
	$dbProd = Config::get('db')->get_results("SELECT u.*, x.deptId FROM `productiveUser` u LEFT JOIN `productiveDeptXref` as x on x.userId=u.id WHERE u.practiceId=1 and (u.role=3 OR u.role=4) and x.deptId={$deptId} LIMIT 1");
   	
	if($dbProd){
	$dbData = $dbProd[0];
	
	if(intval($dbData['id'])<1000){
	$p0 = '00';
	}else if(intval($dbData['id'])<10000){
	$p0 = '0';
	}else{
	$p0 = '';	
	}
	if(intval($accountId)<100){
	$a0 = '0';
	}else{
	$a0 = '';	
	}
	
	$p1 = rand(1000,9999);
	$p2 = rand(1000,9999);
	$p3 = rand(1,9);
	
	$logix2 = $p3 . $p1 . $p0 . $dbData['id'] . $p2 . $a0 . $accountId . $p3;
	
	$logix = array(
			'id'=>$logix2
			);
	
	$data = array(
           	'logix2' =>$logix2,
			'logp3'=>$p1
        );
	$datawhere = array(
            'id' =>$dbData['id']
		);
            
	$newBlock = Config::get('db')->update('productiveUser',$data,$datawhere);		
	
	$dept = array(
           	'qrUpdated' =>$updated
        );
	$deptwhere = array(
            'id' =>$deptId
		);
            
	$newdept = Config::get('db')->update('ProductiveDept',$dept,$deptwhere);

	// Create token table if not exists
	Config::get('db')->query("CREATE TABLE IF NOT EXISTS `productiveQrTokens` (`token` VARCHAR(64) NOT NULL, `userId` INT NOT NULL, `deptId` INT NOT NULL DEFAULT 0, `page` VARCHAR(20) NOT NULL, `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (`token`), INDEX `idx_deptId` (`deptId`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

	// Replace tokens for this dept
	Config::get('db')->query("DELETE FROM `productiveQrTokens` WHERE `deptId`=" . intval($deptId));
	$tokens = array();
	foreach (array('staffing', 'qbl', 'tasks') as $qrpage) {
		$tok = bin2hex(random_bytes(32));
		$tokens[$qrpage] = $tok;
		Config::get('db')->insert('productiveQrTokens', array(
			'token'   => $tok,
			'userId'  => $dbData['id'],
			'deptId'  => intval($deptId),
			'page'    => $qrpage,
			'created' => date('Y-m-d H:i:s'),
		));
	}

    echo json_encode(array('success' =>true,'data'=>$dbData, 'logix'=>$logix, 'tokens'=>$tokens));
    return true;
    }else{
    echo json_encode(array('success'=>false,'message'=>'Record not found.'));
	return false;
	}
}
/////////////////////////////////////
// GET DETAILS FOR SINGLE USER
function GettaskDetails() {
	$dataId = (isset($_REQUEST['dataId'])?$_REQUEST['dataId']:'');
	$userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
    $role = Config::get('account')['role'];
	
	$dbProd = Config::get('db')->get_results("SELECT * from `productivelogData` where `id`={$dataId}");
   	
		if($dbProd){
        $dbData = $dbProd[0];
        echo json_encode(array('success' => true,'data'=>$dbData));
        return true;
    }else{
    echo json_encode(array('success'=>false,'message'=>'Record not found.'));
	return false;
	}
}

function GetsafetyDetails() {
	$dataId = (isset($_REQUEST['dataId'])?$_REQUEST['dataId']:'');
	$userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
    $role = Config::get('account')['role'];
	
	$dbProd = Config::get('db')->get_results("SELECT s.*, DATE_FORMAT(s.openedDate,'%m-%d-%Y') as submitDate, a.safetyTypes from `productiveSafety` s LEFT JOIN `productiveAccount` as a on a.id=s.accountId where s.id={$dataId}");
   	
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

function saveTask() {
	$task1 = (isset($_REQUEST['task1'])?$_REQUEST['task1']:'');
	$task2 = (isset($_REQUEST['task2'])?$_REQUEST['task2']:'');
	$task3 = (isset($_REQUEST['task3'])?$_REQUEST['task3']:'');
	$task4 = (isset($_REQUEST['task4'])?$_REQUEST['task4']:'');
	$task5 = (isset($_REQUEST['task5'])?$_REQUEST['task5']:'');
	$task6 = (isset($_REQUEST['task6'])?$_REQUEST['task6']:'');
	$task7 = (isset($_REQUEST['task7'])?$_REQUEST['task7']:'');
	$task8 = (isset($_REQUEST['task8'])?$_REQUEST['task8']:'');
	$task9 = (isset($_REQUEST['task9'])?$_REQUEST['task9']:'');
	$task10 = (isset($_REQUEST['task10'])?$_REQUEST['task10']:'');
	$task11 = (isset($_REQUEST['task11'])?$_REQUEST['task11']:'');
	$task12 = (isset($_REQUEST['task12'])?$_REQUEST['task12']:'');
	$task13 = (isset($_REQUEST['task13'])?$_REQUEST['task13']:'');
	$task14 = (isset($_REQUEST['task14'])?$_REQUEST['task14']:'');
	$task15 = (isset($_REQUEST['task15'])?$_REQUEST['task15']:'');
	$type1 = (isset($_REQUEST['type1'])?$_REQUEST['type1']:0);
	$type2 = (isset($_REQUEST['type2'])?$_REQUEST['type2']:0);
	$type3 = (isset($_REQUEST['type3'])?$_REQUEST['type3']:0);
	$type4 = (isset($_REQUEST['type4'])?$_REQUEST['type4']:0);
	$type5 = (isset($_REQUEST['type5'])?$_REQUEST['type5']:0);
	$type6 = (isset($_REQUEST['type6'])?$_REQUEST['type6']:0);
	$type7 = (isset($_REQUEST['type7'])?$_REQUEST['type7']:0);
	$type8 = (isset($_REQUEST['type8'])?$_REQUEST['type8']:0);
	$type9 = (isset($_REQUEST['type9'])?$_REQUEST['type9']:0);
	$type10 = (isset($_REQUEST['type10'])?$_REQUEST['type10']:0);
	$type11 = (isset($_REQUEST['type11'])?$_REQUEST['type11']:0);
	$type12 = (isset($_REQUEST['type12'])?$_REQUEST['type12']:0);
	$type13 = (isset($_REQUEST['type13'])?$_REQUEST['type13']:0);
	$type14 = (isset($_REQUEST['type14'])?$_REQUEST['type14']:0);
	$type15 = (isset($_REQUEST['type15'])?$_REQUEST['type15']:0);
	$r1 = (isset($_REQUEST['r1'])?$_REQUEST['r1']:0);
	$r2 = (isset($_REQUEST['r2'])?$_REQUEST['r2']:0);
	$r3 = (isset($_REQUEST['r3'])?$_REQUEST['r3']:0);
	$r4 = (isset($_REQUEST['r4'])?$_REQUEST['r4']:0);
	$r5 = (isset($_REQUEST['r5'])?$_REQUEST['r5']:0);
	$r6 = (isset($_REQUEST['r6'])?$_REQUEST['r6']:0);
	$r7 = (isset($_REQUEST['r7'])?$_REQUEST['r7']:0);
	$r8 = (isset($_REQUEST['r8'])?$_REQUEST['r8']:0);
	$r9 = (isset($_REQUEST['r9'])?$_REQUEST['r9']:0);
	$r10 = (isset($_REQUEST['r10'])?$_REQUEST['r10']:0);
	$r11 = (isset($_REQUEST['r11'])?$_REQUEST['r11']:0);
	$r12 = (isset($_REQUEST['r12'])?$_REQUEST['r12']:0);
	$r13 = (isset($_REQUEST['r13'])?$_REQUEST['r13']:0);
	$r14 = (isset($_REQUEST['r14'])?$_REQUEST['r14']:0);
	$r15 = (isset($_REQUEST['r15'])?$_REQUEST['r15']:0);
	$taskName = (isset($_REQUEST['taskName'])? $_REQUEST['taskName']:'');
	$taskUnit = (isset($_REQUEST['taskUnit'])? $_REQUEST['taskUnit']:'');
	$taskDays = (isset($_REQUEST['taskDays'])? $_REQUEST['taskDays']:'');
	$taskDue = (isset($_REQUEST['taskDue'])? $_REQUEST['taskDue']:'');
	$taskIns = (isset($_REQUEST['taskIns'])? $_REQUEST['taskIns']:'');
	
	$userId = Config::get('account')['id'];
	$accountId = Config::get('account')['accountId'];
	if($taskDays){
		$taskDay = implode(",",$taskDays);
	}else{
		$taskDay = '';
	}
	if($taskDue){
		$taskTime = $taskDue . ":00";
		}else{
		$taskTime = "0";
	}
	
	
	if(intval($taskUnit)>0){
	$data = array(
            'q1' =>$task1,
			'q2' =>$task2,
			'q3' =>$task3,
			'q4' =>$task4,
			'q5' =>$task5,
			'q6' =>$task6,
			'q7' =>$task7,
			'q8' =>$task8,
			'q9' =>$task9,
			'q10' =>$task10,
			'q11' =>$task11,
			'q12' =>$task12,
			'q13' =>$task13,
			'q14' =>$task14,
			'q15' =>$task15,
			'qType1' =>$type1,
			'qType2' =>$type2,
			'qType3' =>$type3,
			'qType4' =>$type4,
			'qType5' =>$type5,
			'qType6' =>$type6,
			'qType7' =>$type7,
			'qType8' =>$type8,
			'qType9' =>$type9,
			'qType10' =>$type10,
			'qType11' =>$type11,
			'qType12' =>$type12,
			'qType13' =>$type13,
			'qType14' =>$type14,
			'qType15' =>$type15,
			'r1' =>$r1,
			'r2' =>$r2,
			'r3' =>$r3,
			'r4' =>$r4,
			'r5' =>$r5,
			'r6' =>$r6,
			'r7' =>$r7,
			'r8' =>$r8,
			'r9' =>$r9,
			'r10' =>$r10,
			'r11' =>$r11,
			'r12' =>$r12,
			'r13' =>$r13,
			'r14' =>$r14,
			'r15' =>$r15,
			'title' =>$taskName,
			'active' =>1,
			'accountId' =>$accountId,
			'deptId' =>$taskUnit,
			'dow' =>$taskDay,
			'shift' =>$taskTime,
			'instructions' =>$taskIns,
			'createdBy' =>$userId
        );
            
	$newBlock = Config::get('db')->insert('productivelogData',$data);

	$newtaskId = Config::get('db')->get_results("select MAX(`id`) as id, DATE_FORMAT(CURDATE(),'%Y-%m-%d') as dayDate FROM `productivelogData` where `createdBy`=$userId");
	
	if($newtaskId){
	$newId = $newtaskId[0]['id'];
	$dayDate = $newtaskId[0]['dayDate'];
	
	$logdata = array(
            'accountId' =>$accountId,
			'deptId' =>$taskUnit,
			'logId' =>$newId,
			'shift' =>$taskTime,
			'dayDate' =>$dayDate
			);
	$newT = Config::get('db')->insert('productivecrashLog',$logdata);
	}
	
	echo json_encode(array('success' => true));
    return true;
	}else{
    echo json_encode(array('success'=>false,'message'=>'update failed.'));
    return false;
	}
}

function CloseTask() {
	$dataId = (isset($_REQUEST['taskId'])?$_REQUEST['taskId']:'');
	$active = (isset($_REQUEST['active'])?$_REQUEST['active']:0);
	$userId = Config::get('account')['id'];
	$accountId = Config::get('account')['accountId'];
	
	if(intval($dataId)>0){
	$data = array(
           	'lastupdatedBy' =>$userId,
			'active' =>$active
        );
	$datawhere = array(
            'id' =>$dataId,
            'accountId' =>$accountId
		);

	$newBlock = Config::get('db')->update('productivelogData',$data,$datawhere);



	echo json_encode(array('success' => true));
    return true;
	}else{
    echo json_encode(array('success'=>false,'message'=>'update failed.'));
    return false;
	}
}

function copyTask() {
	$dataId = (isset($_REQUEST['dataId'])?$_REQUEST['dataId']:0);
	$task1 = (isset($_REQUEST['task1'])?$_REQUEST['task1']:'');
	$task2 = (isset($_REQUEST['task2'])?$_REQUEST['task2']:'');
	$task3 = (isset($_REQUEST['task3'])?$_REQUEST['task3']:'');
	$task4 = (isset($_REQUEST['task4'])?$_REQUEST['task4']:'');
	$task5 = (isset($_REQUEST['task5'])?$_REQUEST['task5']:'');
	$task6 = (isset($_REQUEST['task6'])?$_REQUEST['task6']:'');
	$task7 = (isset($_REQUEST['task7'])?$_REQUEST['task7']:'');
	$task8 = (isset($_REQUEST['task8'])?$_REQUEST['task8']:'');
	$task9 = (isset($_REQUEST['task9'])?$_REQUEST['task9']:'');
	$task10 = (isset($_REQUEST['task10'])?$_REQUEST['task10']:'');
	$task11 = (isset($_REQUEST['task11'])?$_REQUEST['task11']:'');
	$task12 = (isset($_REQUEST['task12'])?$_REQUEST['task12']:'');
	$task13 = (isset($_REQUEST['task13'])?$_REQUEST['task13']:'');
	$task14 = (isset($_REQUEST['task14'])?$_REQUEST['task14']:'');
	$task15 = (isset($_REQUEST['task15'])?$_REQUEST['task15']:'');
	$type1 = (isset($_REQUEST['type1'])?$_REQUEST['type1']:0);
	$type2 = (isset($_REQUEST['type2'])?$_REQUEST['type2']:0);
	$type3 = (isset($_REQUEST['type3'])?$_REQUEST['type3']:0);
	$type4 = (isset($_REQUEST['type4'])?$_REQUEST['type4']:0);
	$type5 = (isset($_REQUEST['type5'])?$_REQUEST['type5']:0);
	$type6 = (isset($_REQUEST['type6'])?$_REQUEST['type6']:0);
	$type7 = (isset($_REQUEST['type7'])?$_REQUEST['type7']:0);
	$type8 = (isset($_REQUEST['type8'])?$_REQUEST['type8']:0);
	$type9 = (isset($_REQUEST['type9'])?$_REQUEST['type9']:0);
	$type10 = (isset($_REQUEST['type10'])?$_REQUEST['type10']:0);
	$type11 = (isset($_REQUEST['type11'])?$_REQUEST['type11']:0);
	$type12 = (isset($_REQUEST['type12'])?$_REQUEST['type12']:0);
	$type13 = (isset($_REQUEST['type13'])?$_REQUEST['type13']:0);
	$type14 = (isset($_REQUEST['type14'])?$_REQUEST['type14']:0);
	$type15 = (isset($_REQUEST['type15'])?$_REQUEST['type15']:0);
	$r1 = (isset($_REQUEST['r1'])?$_REQUEST['r1']:0);
	$r2 = (isset($_REQUEST['r2'])?$_REQUEST['r2']:0);
	$r3 = (isset($_REQUEST['r3'])?$_REQUEST['r3']:0);
	$r4 = (isset($_REQUEST['r4'])?$_REQUEST['r4']:0);
	$r5 = (isset($_REQUEST['r5'])?$_REQUEST['r5']:0);
	$r6 = (isset($_REQUEST['r6'])?$_REQUEST['r6']:0);
	$r7 = (isset($_REQUEST['r7'])?$_REQUEST['r7']:0);
	$r8 = (isset($_REQUEST['r8'])?$_REQUEST['r8']:0);
	$r9 = (isset($_REQUEST['r9'])?$_REQUEST['r9']:0);
	$r10 = (isset($_REQUEST['r10'])?$_REQUEST['r10']:0);
	$r11 = (isset($_REQUEST['r11'])?$_REQUEST['r11']:0);
	$r12 = (isset($_REQUEST['r12'])?$_REQUEST['r12']:0);
	$r13 = (isset($_REQUEST['r13'])?$_REQUEST['r13']:0);
	$r14 = (isset($_REQUEST['r14'])?$_REQUEST['r14']:0);
	$r15 = (isset($_REQUEST['r15'])?$_REQUEST['r15']:0);
	$taskName = (isset($_REQUEST['taskName'])? $_REQUEST['taskName']:'');
	$taskUnit = (isset($_REQUEST['taskUnit'])? $_REQUEST['taskUnit']:'');
	$taskDays = (isset($_REQUEST['taskDays'])? $_REQUEST['taskDays']:'');
	$taskDue = (isset($_REQUEST['taskDue'])? $_REQUEST['taskDue']:'');
	$taskIns = (isset($_REQUEST['taskIns'])? $_REQUEST['taskIns']:'');
	$active = (isset($_REQUEST['active'])? $_REQUEST['active']:0);
	$logType = (isset($_REQUEST['logType'])? $_REQUEST['logType']:0);
	$logLock = (isset($_REQUEST['logLock'])? $_REQUEST['logLock']:0);
	
	$userId = Config::get('account')['id'];
	$accountId = Config::get('account')['accountId'];
	if($taskDays){
		$taskDay = implode(",",$taskDays);
	}else{
		$taskDay = '';
	}
	if($taskDue){
		$taskTime = $taskDue . ":00";
		}else{
		$taskTime = "0";
	}
	
	
	if(intval($dataId)>0){
	$data = array(
            'q1' =>$task1,
			'q2' =>$task2,
			'q3' =>$task3,
			'q4' =>$task4,
			'q5' =>$task5,
			'q6' =>$task6,
			'q7' =>$task7,
			'q8' =>$task8,
			'q9' =>$task9,
			'q10' =>$task10,
			'q11' =>$task11,
			'q12' =>$task12,
			'q13' =>$task13,
			'q14' =>$task14,
			'q15' =>$task15,
			'qType1' =>$type1,
			'qType2' =>$type2,
			'qType3' =>$type3,
			'qType4' =>$type4,
			'qType5' =>$type5,
			'qType6' =>$type6,
			'qType7' =>$type7,
			'qType8' =>$type8,
			'qType9' =>$type9,
			'qType10' =>$type10,
			'qType11' =>$type11,
			'qType12' =>$type12,
			'qType13' =>$type13,
			'qType14' =>$type14,
			'qType15' =>$type15,
			'r1' =>$r1,
			'r2' =>$r2,
			'r3' =>$r3,
			'r4' =>$r4,
			'r5' =>$r5,
			'r6' =>$r6,
			'r7' =>$r7,
			'r8' =>$r8,
			'r9' =>$r9,
			'r10' =>$r10,
			'r11' =>$r11,
			'r12' =>$r12,
			'r13' =>$r13,
			'r14' =>$r14,
			'r15' =>$r15,
			'title' =>'Copy of ' . $taskName,
			'active' =>1,
			'accountId' =>$accountId,
			'deptId' =>$taskUnit,
			'dow' =>$taskDay,
			'shift' =>$taskTime,
			'instructions' =>$taskIns,
			'createdBy' =>$userId,
			'active' =>$active,
			'copyTask' => 1,
			'logType' => $logType,
			'logLock' => $logLock
			
        );
            
	$newBlock = Config::get('db')->insert('productivelogData',$data);
	
	

	echo json_encode(array('success' => true));
    return true;
	}else{
    echo json_encode(array('success'=>false,'message'=>'update failed.'));
    return false;
	}
}

function updateTask() {
	$dataId = (isset($_REQUEST['dataId'])?$_REQUEST['dataId']:0);
	$task1 = (isset($_REQUEST['task1'])?$_REQUEST['task1']:'');
	$task2 = (isset($_REQUEST['task2'])?$_REQUEST['task2']:'');
	$task3 = (isset($_REQUEST['task3'])?$_REQUEST['task3']:'');
	$task4 = (isset($_REQUEST['task4'])?$_REQUEST['task4']:'');
	$task5 = (isset($_REQUEST['task5'])?$_REQUEST['task5']:'');
	$task6 = (isset($_REQUEST['task6'])?$_REQUEST['task6']:'');
	$task7 = (isset($_REQUEST['task7'])?$_REQUEST['task7']:'');
	$task8 = (isset($_REQUEST['task8'])?$_REQUEST['task8']:'');
	$task9 = (isset($_REQUEST['task9'])?$_REQUEST['task9']:'');
	$task10 = (isset($_REQUEST['task10'])?$_REQUEST['task10']:'');
	$task11 = (isset($_REQUEST['task11'])?$_REQUEST['task11']:'');
	$task12 = (isset($_REQUEST['task12'])?$_REQUEST['task12']:'');
	$task13 = (isset($_REQUEST['task13'])?$_REQUEST['task13']:'');
	$task14 = (isset($_REQUEST['task14'])?$_REQUEST['task14']:'');
	$task15 = (isset($_REQUEST['task15'])?$_REQUEST['task15']:'');
	$type1 = (isset($_REQUEST['type1'])?$_REQUEST['type1']:0);
	$type2 = (isset($_REQUEST['type2'])?$_REQUEST['type2']:0);
	$type3 = (isset($_REQUEST['type3'])?$_REQUEST['type3']:0);
	$type4 = (isset($_REQUEST['type4'])?$_REQUEST['type4']:0);
	$type5 = (isset($_REQUEST['type5'])?$_REQUEST['type5']:0);
	$type6 = (isset($_REQUEST['type6'])?$_REQUEST['type6']:0);
	$type7 = (isset($_REQUEST['type7'])?$_REQUEST['type7']:0);
	$type8 = (isset($_REQUEST['type8'])?$_REQUEST['type8']:0);
	$type9 = (isset($_REQUEST['type9'])?$_REQUEST['type9']:0);
	$type10 = (isset($_REQUEST['type10'])?$_REQUEST['type10']:0);
	$type11 = (isset($_REQUEST['type11'])?$_REQUEST['type11']:0);
	$type12 = (isset($_REQUEST['type12'])?$_REQUEST['type12']:0);
	$type13 = (isset($_REQUEST['type13'])?$_REQUEST['type13']:0);
	$type14 = (isset($_REQUEST['type14'])?$_REQUEST['type14']:0);
	$type15 = (isset($_REQUEST['type15'])?$_REQUEST['type15']:0);
	$r1 = (isset($_REQUEST['r1'])?$_REQUEST['r1']:0);
	$r2 = (isset($_REQUEST['r2'])?$_REQUEST['r2']:0);
	$r3 = (isset($_REQUEST['r3'])?$_REQUEST['r3']:0);
	$r4 = (isset($_REQUEST['r4'])?$_REQUEST['r4']:0);
	$r5 = (isset($_REQUEST['r5'])?$_REQUEST['r5']:0);
	$r6 = (isset($_REQUEST['r6'])?$_REQUEST['r6']:0);
	$r7 = (isset($_REQUEST['r7'])?$_REQUEST['r7']:0);
	$r8 = (isset($_REQUEST['r8'])?$_REQUEST['r8']:0);
	$r9 = (isset($_REQUEST['r9'])?$_REQUEST['r9']:0);
	$r10 = (isset($_REQUEST['r10'])?$_REQUEST['r10']:0);
	$r11 = (isset($_REQUEST['r11'])?$_REQUEST['r11']:0);
	$r12 = (isset($_REQUEST['r12'])?$_REQUEST['r12']:0);
	$r13 = (isset($_REQUEST['r13'])?$_REQUEST['r13']:0);
	$r14 = (isset($_REQUEST['r14'])?$_REQUEST['r14']:0);
	$r15 = (isset($_REQUEST['r15'])?$_REQUEST['r15']:0);
	$taskName = (isset($_REQUEST['taskName'])? $_REQUEST['taskName']:'');
	$taskUnit = (isset($_REQUEST['taskUnit'])? $_REQUEST['taskUnit']:'');
	$taskDays = (isset($_REQUEST['taskDays'])? $_REQUEST['taskDays']:'');
	$taskDue = (isset($_REQUEST['taskDue'])? $_REQUEST['taskDue']:'');
	$taskIns = (isset($_REQUEST['taskIns'])? $_REQUEST['taskIns']:'');
	$active = (isset($_REQUEST['active'])? $_REQUEST['active']:0);
	$copyTask = (isset($_REQUEST['copyTask'])? $_REQUEST['copyTask']:0);
	$dayDate = (isset($_REQUEST['dayDate'])? $_REQUEST['dayDate']:'');
	
	$userId = Config::get('account')['id'];
	$accountId = Config::get('account')['accountId'];
	if($taskDays){
		$taskDay = implode(",",$taskDays);
	}else{
		$taskDay = '';
	}
	if($taskDue){
		$taskTime = $taskDue . ":00";
		}else{
		$taskTime = "0";
	}
	
	
	if(intval($dataId)>0){
	$data = array(
            'q1' =>$task1,
			'q2' =>$task2,
			'q3' =>$task3,
			'q4' =>$task4,
			'q5' =>$task5,
			'q6' =>$task6,
			'q7' =>$task7,
			'q8' =>$task8,
			'q9' =>$task9,
			'q10' =>$task10,
			'q11' =>$task11,
			'q12' =>$task12,
			'q13' =>$task13,
			'q14' =>$task14,
			'q15' =>$task15,
			'qType1' =>$type1,
			'qType2' =>$type2,
			'qType3' =>$type3,
			'qType4' =>$type4,
			'qType5' =>$type5,
			'qType6' =>$type6,
			'qType7' =>$type7,
			'qType8' =>$type8,
			'qType9' =>$type9,
			'qType10' =>$type10,
			'qType11' =>$type11,
			'qType12' =>$type12,
			'qType13' =>$type13,
			'qType14' =>$type14,
			'qType15' =>$type15,
			'r1' =>$r1,
			'r2' =>$r2,
			'r3' =>$r3,
			'r4' =>$r4,
			'r5' =>$r5,
			'r6' =>$r6,
			'r7' =>$r7,
			'r8' =>$r8,
			'r9' =>$r9,
			'r10' =>$r10,
			'r11' =>$r11,
			'r12' =>$r12,
			'r13' =>$r13,
			'r14' =>$r14,
			'r15' =>$r15,
			'title' =>$taskName,
			'active' =>1,
			'accountId' =>$accountId,
			'deptId' =>$taskUnit,
			'dow' =>$taskDay,
			'shift' =>$taskTime,
			'instructions' =>$taskIns,
			'createdBy' =>$userId,
			'active' =>$active,
			'copyTask' => 0
        );
		$datawhere = array(
            'id' =>$dataId,
            'accountId' =>$accountId
		);

	$newBlock = Config::get('db')->update('productivelogData',$data,$datawhere);

	if(intval($copyTask)==1){	
		$logdata = array(
				'accountId' =>$accountId,
				'deptId' =>$taskUnit,
				'logId' =>$dataId,
				'shift' =>$taskTime,
				'dayDate' =>$dayDate
				);
		$newT = Config::get('db')->insert('productivecrashLog',$logdata);
	}
	

	echo json_encode(array('success' => true));
    return true;
	}else{
    echo json_encode(array('success'=>false,'message'=>'update failed.'));
    return false;
	}
}

function GetQblById() {
	$logId = intval(isset($_REQUEST['logId']) ? $_REQUEST['logId'] : 0);
	$accountId = intval(Config::get('account')['accountId']);
	$userId = intval(Config::get('account')['id']);
	if($logId < 1){ echo json_encode(array('success'=>false)); return; }
	$log = Config::get('db')->get_results("SELECT l.*, c.* FROM productiveQBLlog l JOIN productiveQBLconfig c ON c.id=l.locId WHERE l.id={$logId} AND l.accountId={$accountId} AND l.userId={$userId} LIMIT 1");
	if(!$log){ echo json_encode(array('success'=>false)); return; }
	echo json_encode(array('success'=>true, 'log'=>$log[0]));
}

function GetQBLConfig() {
	$deptId = intval(isset($_REQUEST['deptId']) ? $_REQUEST['deptId'] : 0);
	$userId = Config::get('account')['id'];
	$accountId = Config::get('account')['accountId'];
	$role = intval(Config::get('account')['role']);

	// Ensure columns 16-20 exist in config table
	$check = Config::get('db')->get_results("SHOW COLUMNS FROM `productiveQBLconfig` LIKE 'desc16'");
	if (!$check) {
		Config::get('db')->query("ALTER TABLE `productiveQBLconfig` ADD COLUMN `desc16` VARCHAR(100) DEFAULT '', ADD COLUMN `value16` FLOAT DEFAULT 0, ADD COLUMN `image16` VARCHAR(100) DEFAULT '', ADD COLUMN `desc17` VARCHAR(100) DEFAULT '', ADD COLUMN `value17` FLOAT DEFAULT 0, ADD COLUMN `image17` VARCHAR(100) DEFAULT '', ADD COLUMN `desc18` VARCHAR(100) DEFAULT '', ADD COLUMN `value18` FLOAT DEFAULT 0, ADD COLUMN `image18` VARCHAR(100) DEFAULT '', ADD COLUMN `desc19` VARCHAR(100) DEFAULT '', ADD COLUMN `value19` FLOAT DEFAULT 0, ADD COLUMN `image19` VARCHAR(100) DEFAULT '', ADD COLUMN `desc20` VARCHAR(100) DEFAULT '', ADD COLUMN `value20` FLOAT DEFAULT 0, ADD COLUMN `image20` VARCHAR(100) DEFAULT ''");
	}
	// Ensure v16-v20 exist in log table
	$checkLog = Config::get('db')->get_results("SHOW COLUMNS FROM `productiveQBLlog` LIKE 'v16'");
	if (!$checkLog) {
		Config::get('db')->query("ALTER TABLE `productiveQBLlog` ADD COLUMN `v16` FLOAT DEFAULT 0, ADD COLUMN `v17` FLOAT DEFAULT 0, ADD COLUMN `v18` FLOAT DEFAULT 0, ADD COLUMN `v19` FLOAT DEFAULT 0, ADD COLUMN `v20` FLOAT DEFAULT 0");
	}

	if ($deptId < 1) {
		echo json_encode(array('success' => false, 'message' => 'Invalid department.'));
		return;
	}

	// Verify user has access to this dept
	$access = Config::get('db')->get_results("SELECT c.* FROM `productiveQBLconfig` c WHERE c.deptId={$deptId} AND c.active=1 LIMIT 1");
	if (!$access) {
		echo json_encode(array('success' => false, 'message' => 'Config not found.'));
		return;
	}

	// Get available images
	$imgDir = __DIR__ . '/../qblimages/';
	$images = array();
	if (is_dir($imgDir)) {
		foreach (scandir($imgDir) as $f) {
			if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $f)) {
				$images[] = $f;
			}
		}
		sort($images);
	}

	echo json_encode(array('success' => true, 'data' => $access[0], 'images' => $images));
}

function UpdateQBLConfig() {
	$deptId = intval(isset($_REQUEST['deptId']) ? $_REQUEST['deptId'] : 0);
	$configId = intval(isset($_REQUEST['configId']) ? $_REQUEST['configId'] : 0);
	$userId = Config::get('account')['id'];
	$accountId = Config::get('account')['accountId'];
	$role = intval(Config::get('account')['role']);

	if ($configId < 1 || $deptId < 1) {
		echo json_encode(array('success' => false, 'message' => 'Invalid config.'));
		return;
	}

	$data = array();
	for ($i = 1; $i <= 20; $i++) {
		$data["desc{$i}"] = substr(trim(isset($_REQUEST["desc{$i}"]) ? $_REQUEST["desc{$i}"] : ''), 0, 100);
		$data["value{$i}"] = floatval(isset($_REQUEST["value{$i}"]) ? $_REQUEST["value{$i}"] : 0);
		$data["image{$i}"] = basename(isset($_REQUEST["image{$i}"]) ? $_REQUEST["image{$i}"] : '');
	}

	$where = array('id' => $configId, 'deptId' => $deptId);
	Config::get('db')->update('productiveQBLconfig', $data, $where);
	echo json_encode(array('success' => true));
}

function UploadQBLImage() {
	$role = intval(Config::get('account')['role']);
	if ($role < 8) {
		echo json_encode(array('success' => false, 'message' => 'Unauthorized.'));
		return;
	}
	if (!isset($_FILES['imgfile']) || $_FILES['imgfile']['error'] !== UPLOAD_ERR_OK) {
		echo json_encode(array('success' => false, 'message' => 'Upload error.'));
		return;
	}
	$file = $_FILES['imgfile'];
	$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
	if (!in_array($ext, array('jpg', 'jpeg', 'png', 'gif'))) {
		echo json_encode(array('success' => false, 'message' => 'Invalid file type. Use jpg, jpeg, png, or gif.'));
		return;
	}
	if (!getimagesize($file['tmp_name'])) {
		echo json_encode(array('success' => false, 'message' => 'Not a valid image.'));
		return;
	}
	$base = preg_replace('/[^a-z0-9_-]/', '', strtolower(pathinfo($file['name'], PATHINFO_FILENAME)));
	if (empty($base)) { $base = 'img_' . time(); }
	$filename = $base . '.' . $ext;
	$dest = __DIR__ . '/../qblimages/' . $filename;
	if (!move_uploaded_file($file['tmp_name'], $dest)) {
		echo json_encode(array('success' => false, 'message' => 'Failed to save file.'));
		return;
	}
	echo json_encode(array('success' => true, 'filename' => $filename));
}

function Updateqbl() {
	$userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
	$logId = (isset($_REQUEST['logId'])?$_REQUEST['logId']:'');
	$q1 = (isset($_REQUEST['q1'])?$_REQUEST['q1']:'0');
	$q2 = (isset($_REQUEST['q2'])?$_REQUEST['q2']:'0');
	$q3 = (isset($_REQUEST['q3'])?$_REQUEST['q3']:'0');
	$q4 = (isset($_REQUEST['q4'])?$_REQUEST['q4']:'0');
	$q5 = (isset($_REQUEST['q5'])?$_REQUEST['q5']:'0');
	$q6 = (isset($_REQUEST['q6'])?$_REQUEST['q6']:'0');
	$q7 = (isset($_REQUEST['q7'])?$_REQUEST['q7']:'0');
	$q8 = (isset($_REQUEST['q8'])?$_REQUEST['q8']:'0');
	$q9 = (isset($_REQUEST['q9'])?$_REQUEST['q9']:'0');
	$q10 = (isset($_REQUEST['q10'])?$_REQUEST['q10']:'0');
	$q11 = (isset($_REQUEST['q11'])?$_REQUEST['q11']:'0');
	$q12 = (isset($_REQUEST['q12'])?$_REQUEST['q12']:'0');
	$q13 = (isset($_REQUEST['q13'])?$_REQUEST['q13']:'0');
	$q14 = (isset($_REQUEST['q14'])?$_REQUEST['q14']:'0');
	$q15 = (isset($_REQUEST['q15'])?$_REQUEST['q15']:'0');
	$q16 = (isset($_REQUEST['q16'])?$_REQUEST['q16']:'0');
	$q17 = (isset($_REQUEST['q17'])?$_REQUEST['q17']:'0');
	$q18 = (isset($_REQUEST['q18'])?$_REQUEST['q18']:'0');
	$q19 = (isset($_REQUEST['q19'])?$_REQUEST['q19']:'0');
	$q20 = (isset($_REQUEST['q20'])?$_REQUEST['q20']:'0');
	$currentTime = (isset($_REQUEST['currentTime'])?$_REQUEST['currentTime']:'');
	$total = (isset($_REQUEST['total1'])?$_REQUEST['total1']:0);
	$newqb2 = (isset($_REQUEST['newqb2'])? $_REQUEST['newqb2']:0);
	$taskNote = (isset($_REQUEST['note'])? $_REQUEST['note']:'');
	$currentDay = (isset($_REQUEST['currentDay'])?$_REQUEST['currentDay']:'');
	$deptId = (isset($_REQUEST['deptId'])?$_REQUEST['deptId']:0);
	$locId = intval(isset($_REQUEST['locId']) ? $_REQUEST['locId'] : 0);
	$trainingOnly = (isset($_REQUEST['trainingOnly'])?intval($_REQUEST['trainingOnly']):0);

	$ptselect = (isset($_REQUEST['ptselect'])?$_REQUEST['ptselect']:0);
	$weight = (isset($_REQUEST['weight'])?$_REQUEST['weight']:0);
	$type = (isset($_REQUEST['type'])?$_REQUEST['type']:0);
	$ih = (isset($_REQUEST['ih'])?$_REQUEST['ih']:0);
	$fh = (isset($_REQUEST['fh'])?$_REQUEST['fh']:0);
	$ebv = (isset($_REQUEST['estabv'])?$_REQUEST['estabv']:0);
	$abl = (isset($_REQUEST['abl'])?$_REQUEST['abl']:0);
	$abv = (isset($_REQUEST['abv'])?$_REQUEST['abv']:0);
	//$newqb1 = ROUND((FLOATVAL($newqb2) * 0.9465),0);
	
	if($trainingOnly==1){
        echo json_encode(array('success' => true));
        return true;
    }
	if(intval($total)>0){
	$data = array(
            'v1' =>$q1,
			'v2' =>$q2,
			'v3' =>$q3,
			'v4' =>$q4,
			'v5' =>$q5,
			'v6' =>$q6,
			'v7' =>$q7,
			'v8' =>$q8,
			'v9' =>$q9,
			'v10' =>$q10,
			'v11' =>$q11,
			'v12' =>$q12,
			'v13' =>$q13,
			'v14' =>$q14,
			'v15' =>$q15,
			'v16' =>$q16,
			'v17' =>$q17,
			'v18' =>$q18,
			'v19' =>$q19,
			'v20' =>$q20,
			'totalWt' =>$total,
			'totalQBL' =>$newqb2,
			'dateTime' =>$currentTime,
			'note' =>$taskNote,
			'dayDate' =>$currentDay,
			'ptweight' =>$weight,
			'ptselect' =>$ptselect,
			'ebv' =>$ebv,
			'abl' =>$abl,
			'abv' =>$abv,
			'ih' =>$ih,
			'fh' =>$fh,
			'weightType' =>$type

        );
	$saved = intval(isset($_REQUEST['saved']) ? $_REQUEST['saved'] : 0);
	if(intval($logId) > 0){
		$updateWhere = array('id' =>$logId, 'accountId' =>$accountId);
		Config::get('db')->update('productiveQBLlog',$data,$updateWhere);
		echo json_encode(array('success' => true));
	} elseif($saved == 1) {
		$data['deptId'] = intval($deptId);
		$data['locId'] = $locId;
		$data['userId'] = $userId;
		$data['accountId'] = $accountId;
		$data['dayDate'] = date('Y-m-d');
		Config::get('db')->insert('productiveQBLlog', $data);
		$newId = intval(Config::get('db')->lastid());
		echo json_encode(array('success' => true, 'logId' => $newId));
	} else {
		echo json_encode(array('success' => true));
	}
        return true;
	}else{
    echo json_encode(array('success'=>false,'message'=>'update failed.'));
    return false;
	}
}

function SendDemoQblSms() {
	$mobile = preg_replace('/\D/', '', isset($_REQUEST['mobile']) ? $_REQUEST['mobile'] : '');
	if (strlen($mobile) != 10) {
		echo json_encode(array('success'=>false,'message'=>'Invalid mobile number'));
		return;
	}
	$qbl = floatval(isset($_REQUEST['qbl']) ? $_REQUEST['qbl'] : 1150);
	$totalWeight = floatval(isset($_REQUEST['totalWeight']) ? $_REQUEST['totalWeight'] : 0);
	$suppliesJson = isset($_REQUEST['supplies']) ? $_REQUEST['supplies'] : '';
	$ip = $_SERVER['REMOTE_ADDR'] ?? '';
	Config::get('db')->insert('qbl_demo_leads', array(
		'mobile'=>$mobile, 'ip'=>$ip, 'qbl_value'=>$qbl, 'total_weight'=>$totalWeight, 'supplies_json'=>$suppliesJson, 'created_at'=>date('Y-m-d H:i:s')
	));
	$leadId = intval(Config::get('db')->lastid());
	$token = bin2hex(random_bytes(32));
	$expires = date('Y-m-d H:i:s', strtotime('+24 hours'));
	Config::get('db')->insert('manager_tokens', array(
		'token'=>$token, 'user_id'=>0, 'account_id'=>0,
		'context'=>'qbl_demo', 'ref_id'=>$leadId, 'expires_at'=>$expires
	));
	$link = "https://qbl.productivern.com/demo-manager-view.php?t=" . $token;
	$message = "A QBL ALERT has been created in Labor & Delivery. " . $link;
	$now = time();
	$psword = "J8775bcgEE2065";
	$xmlMsg = "<message id=\"demo{$mobile}{$now}\"><partnerpassword>{$psword}</partnerpassword><content><![CDATA[{$message}]]></content></message>";
	sendescNow(array('numbers'=>array("1{$mobile}"), 'message'=>array($xmlMsg), 'keyword'=>array('PN18337370906'), 'login'=>'jobalarm45'));
	$notifyMsg = "QBL demo completed by {$mobile}. QBL: {$qbl}ml.";
	$notifyXml = "<message id=\"demonotify{$now}\"><partnerpassword>{$psword}</partnerpassword><content><![CDATA[{$notifyMsg}]]></content></message>";
	sendescNow(array('numbers'=>array("12148500163"), 'message'=>array($notifyXml), 'keyword'=>array('PN18337370906'), 'login'=>'jobalarm45'));
	echo json_encode(array('success'=>true));
}

function SubmitDemoLead() {
	$name     = htmlspecialchars(trim(isset($_REQUEST['name'])     ? $_REQUEST['name']     : ''));
	$hospital = htmlspecialchars(trim(isset($_REQUEST['hospital']) ? $_REQUEST['hospital'] : ''));
	$email    = filter_var(trim(isset($_REQUEST['email'])    ? $_REQUEST['email']    : ''), FILTER_SANITIZE_EMAIL);
	$phone    = preg_replace('/\D/', '', isset($_REQUEST['phone'])   ? $_REQUEST['phone']   : '');
	$msg      = htmlspecialchars(trim(isset($_REQUEST['message'])  ? $_REQUEST['message']  : ''));
	$leadId   = intval(isset($_REQUEST['leadId']) ? $_REQUEST['leadId'] : 0);
	if (empty($name) || empty($email)) {
		echo json_encode(array('success'=>false,'message'=>'Name and email required'));
		return;
	}
	$data = array('contact_name'=>$name,'contact_hospital'=>$hospital,'contact_email'=>$email,'contact_phone'=>$phone,'contact_message'=>$msg);
	if ($leadId > 0) {
		Config::get('db')->update('qbl_demo_leads', $data, array('id'=>$leadId));
	} else {
		$data['created_at'] = date('Y-m-d H:i:s');
		Config::get('db')->insert('qbl_demo_leads', $data);
	}
	echo json_encode(array('success'=>true));
}

function SubmitQblCalc() {
	$token = isset($_REQUEST['token']) ? preg_replace('/[^a-f0-9]/i', '', $_REQUEST['token']) : '';
	if (strlen($token) !== 64) { echo json_encode(['success'=>false,'message'=>'Invalid token']); return; }

	$db = Config::get('db');
	$tokenRow = $db->get_results("SELECT * FROM productiveQrTokens WHERE token='" . $db->filter($token) . "' AND page='qbl' LIMIT 1");
	if (!$tokenRow) { echo json_encode(['success'=>false,'message'=>'Token not found']); return; }

	$deptId = intval($tokenRow[0]['deptId']);
	$dept = $db->get_results("SELECT d.accountId, c.id as configId FROM ProductiveDept d JOIN productiveQBLconfig c ON c.deptId=d.id WHERE d.id={$deptId} AND d.active=1 LIMIT 1");
	if (!$dept) { echo json_encode(['success'=>false,'message'=>'Unit not found']); return; }

	$accountId = intval($dept[0]['accountId']);
	$configId  = intval($dept[0]['configId']);

	// Minimal account context so sendqblEsc can run without a session
	Config::set('account', ['id' => 0, 'accountId' => $accountId]);

	$totalWt   = floatval($_REQUEST['totalWt']  ?? 0);
	$totalQBL  = floatval($_REQUEST['totalQBL'] ?? 0);
	$note      = substr(trim($_REQUEST['note']       ?? ''), 0, 255);
	$nurseName = substr(trim($_REQUEST['nurseName']  ?? ''), 0, 100);
	$cumQbl    = floatval($_REQUEST['cumQbl']   ?? $totalQBL);
	$sessionId = substr(preg_replace('/[^a-f0-9\-]/i', '', $_REQUEST['sessionId'] ?? ''), 0, 36);

	$data = ['deptId'=>$deptId, 'locId'=>$configId, 'userId'=>0, 'accountId'=>$accountId,
	         'totalWt'=>$totalWt, 'totalQBL'=>$totalQBL, 'cum_qbl'=>$cumQbl, 'session_id'=>$sessionId,
	         'dateTime'=>date('Y-m-d H:i:s'), 'dayDate'=>date('Y-m-d'), 'note'=>$db->filter($note), 'nurse_name'=>$db->filter($nurseName)];
	for ($i = 1; $i <= 20; $i++) {
		$data["v{$i}"] = intval($_REQUEST["v{$i}"] ?? 0);
	}

	$db->insert('productiveQBLlog', $data);
	$logId = intval($db->lastid());
	if (!$logId) { echo json_encode(['success'=>false,'message'=>'Save failed']); return; }

	// Determine alert level using cumulative QBL
	$qblRatio = ($totalWt > 0) ? ($totalQBL / $totalWt * 100) : 0;
	$threshRow = $db->get_results("SELECT MIN(u.qblWarnMin) as qblWarnMin FROM productiveDeptXref x JOIN productiveUser u ON u.id=x.userId WHERE x.deptId={$deptId} AND x.escAlerts=1 AND u.qblWarnMin>0 LIMIT 1");
	$qblWarnMin = ($threshRow && $threshRow[0]['qblWarnMin'] !== null) ? intval($threshRow[0]['qblWarnMin']) : 0;
	if ($cumQbl >= 1000)      { $msgId = 1; }
	elseif ($qblRatio >= 75)  { $msgId = 3; }
	else                      { $msgId = 2; }

	$sendAlert = true;
	if ($msgId == 2 && $qblWarnMin > 0 && $cumQbl < $qblWarnMin) $sendAlert = false;

	if ($sendAlert) {
		$qblToken   = bin2hex(random_bytes(32));
		$qblExpires = date('Y-m-d H:i:s', strtotime('+12 hours'));
		$db->insert('qbl_tokens', ['token'=>$qblToken,'qbl_id'=>$logId,'expires_at'=>$qblExpires]);
		$db->update('productiveQBLlog', ['txtSent'=>1], ['id'=>$logId]);
		sendqblEsc($deptId, $msgId, $cumQbl, $qblToken, false, $cumQbl > $totalQBL);
	}

	echo json_encode(['success'=>true, 'logId'=>$logId, 'alertSent'=>$sendAlert, 'nurseName'=>$nurseName]);
}

function UpdateQblCalc() {
	$db    = Config::get('db');
	$token = preg_replace('/[^a-f0-9]/i', '', $_REQUEST['token'] ?? '');
	$logId = intval($_REQUEST['logId'] ?? 0);
	if (!$token || !$logId) { echo json_encode(['success'=>false,'message'=>'Invalid request']); return; }

	$tokenRow = $db->get_results("SELECT deptId FROM productiveQrTokens WHERE token='{$token}' AND page='qbl' LIMIT 1");
	if (!$tokenRow) { echo json_encode(['success'=>false,'message'=>'Invalid token']); return; }
	$deptId = intval($tokenRow[0]['deptId']);

	$dept = $db->get_results("SELECT d.accountId FROM ProductiveDept d WHERE d.id={$deptId} AND d.active=1 LIMIT 1");
	if (!$dept) { echo json_encode(['success'=>false,'message'=>'Unit not found']); return; }
	$accountId = intval($dept[0]['accountId']);

	$existing = $db->get_results("SELECT id FROM productiveQBLlog WHERE id={$logId} AND deptId={$deptId} AND accountId={$accountId} LIMIT 1");
	if (!$existing) { echo json_encode(['success'=>false,'message'=>'Record not found']); return; }

	Config::set('account', ['id' => 0, 'accountId' => $accountId]);

	$totalWt   = floatval($_REQUEST['totalWt']  ?? 0);
	$totalQBL  = floatval($_REQUEST['totalQBL'] ?? 0);
	$note      = substr(trim($_REQUEST['note']      ?? ''), 0, 255);
	$nurseName = substr(trim($_REQUEST['nurseName'] ?? ''), 0, 100);
	$cumQbl    = floatval($_REQUEST['cumQbl']   ?? $totalQBL);
	$sessionId = substr(preg_replace('/[^a-f0-9\-]/i', '', $_REQUEST['sessionId'] ?? ''), 0, 36);

	$data = ['totalWt'=>$totalWt,'totalQBL'=>$totalQBL,'cum_qbl'=>$cumQbl,'session_id'=>$sessionId,
	         'note'=>$db->filter($note),'nurse_name'=>$db->filter($nurseName)];
	for ($i = 1; $i <= 20; $i++) { $data["v{$i}"] = intval($_REQUEST["v{$i}"] ?? 0); }
	$db->update('productiveQBLlog', $data, ['id' => $logId]);

	$qblRatio   = ($totalWt > 0) ? ($totalQBL / $totalWt * 100) : 0;
	$threshRow  = $db->get_results("SELECT MIN(u.qblWarnMin) as qblWarnMin FROM productiveDeptXref x JOIN productiveUser u ON u.id=x.userId WHERE x.deptId={$deptId} AND x.escAlerts=1 AND u.qblWarnMin>0 LIMIT 1");
	$qblWarnMin = ($threshRow && $threshRow[0]['qblWarnMin'] !== null) ? intval($threshRow[0]['qblWarnMin']) : 0;
	if ($cumQbl >= 1000)     { $msgId = 1; }
	elseif ($qblRatio >= 75) { $msgId = 3; }
	else                     { $msgId = 2; }
	$sendAlert = true;
	if ($msgId == 2 && $qblWarnMin > 0 && $cumQbl < $qblWarnMin) $sendAlert = false;

	if ($sendAlert) {
		$qblToken   = bin2hex(random_bytes(32));
		$qblExpires = date('Y-m-d H:i:s', strtotime('+12 hours'));
		$db->insert('qbl_tokens', ['token'=>$qblToken,'qbl_id'=>$logId,'expires_at'=>$qblExpires]);
		$db->update('productiveQBLlog', ['txtSent'=>1], ['id'=>$logId]);
		sendqblEsc($deptId, $msgId, $cumQbl, $qblToken, true, $cumQbl > $totalQBL);
	}

	echo json_encode(['success'=>true, 'logId'=>$logId, 'alertSent'=>$sendAlert, 'nurseName'=>$nurseName]);
}

function SendQblSms() {
	$logId = (isset($_REQUEST['logId'])?intval($_REQUEST['logId']):0);
	$deptId = (isset($_REQUEST['deptId'])?intval($_REQUEST['deptId']):0);
	if($logId < 1 || $deptId < 1){
		echo json_encode(array('success'=>false));
		return;
	}
	$row = Config::get('db')->get_results("SELECT totalQBL, cum_qbl, totalWt, txtSent FROM productiveQBLlog WHERE id=$logId LIMIT 1");
	if(!$row || intval($row[0]['txtSent'])>=2){
		echo json_encode(array('success'=>false));
		return;
	}
	$cumQbl  = floatval($row[0]['cum_qbl'] ?? $row[0]['totalQBL']);
	$totalWt = floatval($row[0]['totalWt']);
	$threshRow = Config::get('db')->get_results("SELECT MIN(u.qblWarnMin) as qblWarnMin FROM productiveDeptXref x JOIN productiveUser u ON u.id=x.userId WHERE x.deptId={$deptId} AND x.escAlerts=1 AND u.qblWarnMin > 0 LIMIT 1");
	$qblWarnMin = ($threshRow && isset($threshRow[0]['qblWarnMin']) && $threshRow[0]['qblWarnMin'] !== null) ? intval($threshRow[0]['qblWarnMin']) : 0;
	$qblRatio = ($totalWt > 0) ? ($totalQBL / $totalWt * 100) : 0;
	if ($cumQbl >= 1000) { $autoMsgId = 1; }
	elseif ($qblRatio >= 75) { $autoMsgId = 3; }
	else { $autoMsgId = 2; }
	if ($autoMsgId == 2 && $qblWarnMin > 0 && $cumQbl < $qblWarnMin) {
		echo json_encode(array('success'=>false,'reason'=>'below_threshold'));
		return;
	}
	$isUpdate = intval($row[0]['txtSent']) == 1;
	Config::get('db')->update('productiveQBLlog', array('txtSent'=> intval($row[0]['txtSent']) + 1), array('id'=>$logId));
	$qblToken = bin2hex(random_bytes(32));
	$qblExpires = date('Y-m-d H:i:s', strtotime('+12 hours'));
	Config::get('db')->insert('qbl_tokens', array('token'=>$qblToken,'qbl_id'=>$logId,'expires_at'=>$qblExpires));
	sendqblEsc($deptId, $autoMsgId, $cumQbl, $qblToken, $isUpdate, floatval($row[0]['cum_qbl']) > floatval($row[0]['totalQBL']));
	echo json_encode(array('success'=>true));
}

function UpdateCrash() {
	//$userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
	$dataId = (isset($_REQUEST['dataId'])?$_REQUEST['dataId']:'');
	$deptId = (isset($_REQUEST['deptId'])?$_REQUEST['deptId']:'');
	$taskName = (isset($_REQUEST['taskName'])?$_REQUEST['taskName']:'');
	$select1 = (isset($_REQUEST['select1'])?$_REQUEST['select1']:0);
	$select2 = (isset($_REQUEST['select2'])?$_REQUEST['select2']:0);
	$select3 = (isset($_REQUEST['select3'])?$_REQUEST['select3']:0);
	$select4 = (isset($_REQUEST['select4'])?$_REQUEST['select4']:0);
	$select1Orig = (isset($_REQUEST['select1Orig'])?$_REQUEST['select1Orig']:0);
	$select2Orig = (isset($_REQUEST['select2Orig'])?$_REQUEST['select2Orig']:0);
	$select3Orig = (isset($_REQUEST['select3Orig'])?$_REQUEST['select3Orig']:0);
	$select4Orig = (isset($_REQUEST['select4Orig'])?$_REQUEST['select4Orig']:0);
	$s1escOrig = (isset($_REQUEST['s1escOrig'])?$_REQUEST['s1escOrig']:0);
	$s2escOrig = (isset($_REQUEST['s2escOrig'])?$_REQUEST['s2escOrig']:0);
	$s3escOrig = (isset($_REQUEST['s3escOrig'])?$_REQUEST['s3escOrig']:0);
	$taskEscOrig = (isset($_REQUEST['taskEscOrig'])?$_REQUEST['taskEscOrig']:0);
	$q1 = (isset($_REQUEST['q1'])?$_REQUEST['q1']:'');
	$q2 = (isset($_REQUEST['q2'])?$_REQUEST['q2']:'');
	$q3 = (isset($_REQUEST['q3'])?$_REQUEST['q3']:'');
	$q4 = (isset($_REQUEST['q4'])?$_REQUEST['q4']:'');
	$q5 = (isset($_REQUEST['q5'])?$_REQUEST['q5']:'');
	$q6 = (isset($_REQUEST['q6'])?$_REQUEST['q6']:'');
	$q7 = (isset($_REQUEST['q7'])?$_REQUEST['q7']:'');
	$q8 = (isset($_REQUEST['q8'])?$_REQUEST['q8']:'');
	$q9 = (isset($_REQUEST['q9'])?$_REQUEST['q9']:'');
	$q10 = (isset($_REQUEST['q10'])?$_REQUEST['q10']:'');
	$q11 = (isset($_REQUEST['q11'])?$_REQUEST['q11']:'');
	$q12 = (isset($_REQUEST['q12'])?$_REQUEST['q12']:'');
	$q13 = (isset($_REQUEST['q13'])?$_REQUEST['q13']:'');
	$q14 = (isset($_REQUEST['q14'])?$_REQUEST['q14']:'');
	$q15 = (isset($_REQUEST['q15'])?$_REQUEST['q15']:'');
	$q16 = (isset($_REQUEST['q16'])?$_REQUEST['q16']:'');
	$q17 = (isset($_REQUEST['q17'])?$_REQUEST['q17']:'');
	$q18 = (isset($_REQUEST['q18'])?$_REQUEST['q18']:'');
	$q19 = (isset($_REQUEST['q19'])?$_REQUEST['q19']:'');
	$q20 = (isset($_REQUEST['q20'])?$_REQUEST['q20']:'');
	$q21 = (isset($_REQUEST['q21'])?$_REQUEST['q21']:'');
	$submittedBy = (isset($_REQUEST['submittedBy'])?$_REQUEST['submittedBy']:'');
	$cdate = (isset($_REQUEST['cdate'])?$_REQUEST['cdate']:'');
	$taskEsc = (isset($_REQUEST['taskEsc'])? $_REQUEST['taskEsc']:0);
	$huddle = (isset($_REQUEST['huddle'])? $_REQUEST['huddle']:0);
	$taskNote = (isset($_REQUEST['taskNote'])? $_REQUEST['taskNote']:'');
	
	$userId = Config::get('account')['id'];
	
	
	if(intval($dataId)>0){
	$data = array(
            'q1' =>$q1,
			'q2' =>$q2,
			'q3' =>$q3,
			'q4' =>$q4,
			'q5' =>$q5,
			'q6' =>$q6,
			'q7' =>$q7,
			'q8' =>$q8,
			'q9' =>$q9,
			'q10' =>$q10,
			'q11' =>$q11,
			'q12' =>$q12,
			'q13' =>$q13,
			'q14' =>$q14,
			'q15' =>$q15,
			'q16' =>$q16,
			'q17' =>$q17,
			'q18' =>$q18,
			'q19' =>$q19,
			'q20' =>$q20,
			'q21' =>addslashes($q21),
			'submittedBy' =>$submittedBy,
			'userId' =>$userId,
			'submittedDateTime' =>$cdate,
			'taskEsc' =>$taskEsc,
			'taskNote' =>addslashes($taskNote),
			'select1' => $select1,
			'select2' => $select2,
			'select3' => $select3,
			'select4' => $select4
        );
	$updateWhere = array('id' =>$dataId, 'accountId' =>$accountId);

	$newBlock = Config::get('db')->update('productivecrashLog',$data,$updateWhere); 
	
	if(intval($select1Orig)==0 && intval($select1)>0){
		$sel1data = array(
            'deptId' =>$deptId,
			'huddleId' =>$dataId,
			'roomId1' =>$q1,
			'accountId' =>$accountId,
			'note1' =>addslashes($q3),
			'openedDate' =>$cdate,
			'dayDate' =>$cdate,
			'createdBy' =>$userId,
			'accountId' =>$accountId,
			'safetyConfig' =>$select1,
			'escalated' =>$q16,
			'active' =>1,
			'huddleRecord' =>1
        );

    Config::get('db')->insert('productiveSafety', $sel1data);
			
	}else if(intval($select1Orig)>0){
		$sel1data = array(
            'deptId' =>$deptId,
			'huddleId' =>$dataId,
			'roomId1' =>$q1,
			'accountId' =>$accountId,
			'note1' =>addslashes($q3),
			'openedDate' =>$cdate,
			'dayDate' =>$cdate,
			'createdBy' =>$userId,
			'accountId' =>$accountId,
			'safetyConfig' =>$select1,
			'escalated' =>$q16,
			'active' =>1
        );
		$sel1where = array(
            'huddleId' =>$dataId,
			'huddleRecord' =>1
        );

    Config::get('db')->update('productiveSafety', $sel1data, $sel1where);
			
	}else{
	//do nothing;	
	}
	
	if(intval($select2Orig)==0 && intval($select2)>0){
		$sel2data = array(
            'deptId' =>$deptId,
			'huddleId' =>$dataId,
			'roomId1' =>$q6,
			'accountId' =>$accountId,
			'note1' =>addslashes($q8),
			'openedDate' =>$cdate,
			'dayDate' =>$cdate,
			'createdBy' =>$userId,
			'accountId' =>$accountId,
			'safetyConfig' =>$select2,
			'escalated' =>$q17,
			'active' =>1,
			'huddleRecord' =>2
        );

    Config::get('db')->insert('productiveSafety', $sel2data);
			
	}else if(intval($select2Orig)>0){
		$sel2data = array(
            'deptId' =>$deptId,
			'huddleId' =>$dataId,
			'roomId1' =>$q6,
			'accountId' =>$accountId,
			'note1' =>addslashes($q8),
			'openedDate' =>$cdate,
			'dayDate' =>$cdate,
			'createdBy' =>$userId,
			'accountId' =>$accountId,
			'safetyConfig' =>$select2,
			'escalated' =>$q17,
			'active' =>1
        );
		$sel2where = array(
            'huddleId' =>$dataId,
			'huddleRecord' =>2
        );

    Config::get('db')->update('productiveSafety', $sel2data, $sel2where);
			
	}else{
	//do nothing;	
	}
	
	if(intval($select3Orig)==0 && intval($select3)>0){
		$sel3data = array(
            'deptId' =>$deptId,
			'huddleId' =>$dataId,
			'roomId1' =>$q11,
			'accountId' =>$accountId,
			'note1' =>addslashes($q13),
			'openedDate' =>$cdate,
			'dayDate' =>$cdate,
			'createdBy' =>$userId,
			'accountId' =>$accountId,
			'safetyConfig' =>$select3,
			'escalated' =>$q18,
			'active' =>1,
			'huddleRecord' =>3
        );

    Config::get('db')->insert('productiveSafety', $sel3data);
			
	}else if(intval($select3Orig)>0){
		$sel3data = array(
            'deptId' =>$deptId,
			'huddleId' =>$dataId,
			'roomId1' =>$q11,
			'accountId' =>$accountId,
			'note1' =>addslashes($q13),
			'openedDate' =>$cdate,
			'dayDate' =>$cdate,
			'createdBy' =>$userId,
			'accountId' =>$accountId,
			'safetyConfig' =>$select3,
			'escalated' =>$q18,
			'active' =>1
        );
		$sel3where = array(
            'huddleId' =>$dataId,
			'huddleRecord' =>3
        );

    Config::get('db')->update('productiveSafety', $sel3data, $sel3where);
			
	}else{
	//do nothing;	
	}
	/*
	if(intval($select4Orig)==0 && intval($select4)>0){
		$sel4data = array(
            'deptId' =>$deptId,
			'huddleId' =>$dataId,
			'roomId1' =>$q16,
			'accountId' =>$accountId,
			'note1' =>addslashes($q18),
			'openedDate' =>$cdate,
			'dayDate' =>$cdate,
			'createdBy' =>$userId,
			'accountId' =>$accountId,
			'safetyConfig' =>$select4,
			'active' =>1,
			'huddleRecord' =>4
        );

    Config::get('db')->insert('productiveSafety', $sel4data);
			
	}else if(intval($select4Orig)>0){
		$sel4data = array(
            'deptId' =>$deptId,
			'huddleId' =>$dataId,
			'roomId1' =>$q16,
			'accountId' =>$accountId,
			'note1' =>addslashes($q18),
			'openedDate' =>$cdate,
			'dayDate' =>$cdate,
			'createdBy' =>$userId,
			'accountId' =>$accountId,
			'safetyConfig' =>$select4,
			'active' =>1
        );
		$sel4where = array(
            'huddleId' =>$dataId,
			'huddleRecord' =>4
        );

    Config::get('db')->update('productiveSafety', $sel4data, $sel4where);
			
	}else{
	//do nothing;	
	}
	
	*/
	
	if(intval($taskEsc)==1 && intval($taskEscOrig)==0){
		$data = array(
            'escalationName' =>$taskName,
			'dataId' =>1,
			'taskId' =>$dataId,
			'escalation' =>69,
			'deptId' =>$deptId,
			'accountId' =>$accountId,
			'note' =>addslashes($taskNote),
            'userId' =>$userId,
			'dateSubmitted' =>$cdate,
			'escType' =>1
        );

        Config::get('db')->insert('productiveEscalations', $data);
		
	$sendText = sendtaskEsc($deptId);	
	}
	
	if(intval($huddle)==2 && intval($q16)==1 && intval($s1escOrig)==0){
		$data = array(
            'escalationName' =>$q1,
			'dataId' =>2,
			'taskId' =>$dataId,
			'escalation' =>10,
			'deptId' =>$deptId,
			'accountId' =>$accountId,
			'note' =>addslashes($q3),
            'userId' =>$userId,
			'dateSubmitted' =>$cdate,
			'escType' =>1
        );

        Config::get('db')->insert('productiveEscalations', $data);
		
	$sendText = sendtaskEscalation($deptId);	
	}
	
	if(intval($huddle)==2 && intval($q17)==1 && intval($s2escOrig)==0){
		$data = array(
            'escalationName' =>$q6,
			'dataId' =>2,
			'taskId' =>$dataId,
			'escalation' =>10,
			'deptId' =>$deptId,
			'accountId' =>$accountId,
			'note' =>addslashes($q8),
            'userId' =>$userId,
			'dateSubmitted' =>$cdate,
			'escType' =>1
        );

        Config::get('db')->insert('productiveEscalations', $data);
		
	$sendText = sendtaskEscalation($deptId);	
	}
	
	if(intval($huddle)==2 && intval($q18)==1 && intval($s3escOrig)==0){
		$data = array(
            'escalationName' =>$q11,
			'dataId' =>2,
			'taskId' =>$dataId,
			'escalation' =>10,
			'deptId' =>$deptId,
			'accountId' =>$accountId,
			'note' =>addslashes($q13),
            'userId' =>$userId,
			'dateSubmitted' =>$cdate,
			'escType' =>1
        );

        Config::get('db')->insert('productiveEscalations', $data);
		
	$sendText = sendtaskEscalation($deptId);	
	}
		

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
		$dbVisits = Config::get('db')->get_results("select n.*, a.uosDesc, ROUND(n.chargecount,1) as chargecount1, (select count(`dept`) from `productiveAccountXref` where `type`<2 AND `whpPlan` !=1 and `dept`=n.deptId) as shiftCount, (select count(`dayDate`) from `productiveNewData` where `deptId`=n.deptId and `userId`>0 and `whpPlan`=0 and `type`<2 and `dayDate`=n.dayDate) as shiftActual, ROUND(n.nvariance,0) as nvariance1, IFNULL(e.escalation,0) as escalationValue, IFNULL(e.note,'') as escalationNote, IFNULL(ae.escalation,'') as escalationType, z.procedureCount as todayProcs, z.actualWHP as todayWHP, z.plannedHours as todayHours, ROUND((z.actualWHP - a.hppd)* z.procedureCount,2) as todayVar, a.dept as depname, a.visitHoursReqd, a.prodMeasure, a.hppd, a.hrs1Calc, a.hrs2Calc, a.hrs3Calc, a.hrs1Desc, a.hrs2Desc, a.hrs3Desc, ROUND((n.actualWHP - a.hppd)* n.procedureCount,2) as currentVar, n.id as dataId2, DATE_FORMAT(n.dayDate,'%m/%d/%Y') as reportdate, s.shiftName as reportshift, n.atotal as totalpatients, a.totalbeds, a.churn, a.uosDesc, (a.oneto1 + a.oneto2 + a.oneto3 + a.oneto4 + a.oneto5 + a.oneto6) as acuityTotal,  a.oneto1 as acuity1, a.oneto2 as acuity2, a.oneto3 as acuity3, a.oneto4 as acuity4, a.oneto5 as acuity5, a.oneto6 as acuity6, a.desc1, a.desc2, a.desc3, a.desc4, a.desc5, a.desc6, a.skill1, a.skill2, a.skill3, a.skill4, a.skill5, a.skilldesc1, a.skilldesc2, a.skilldesc3, a.skilldesc4, a.skilldesc5, IFNULL(u.first_name,'') as first_name, IFNULL(u.last_name,'') as last_name, IFNULL(b.blockedBeds,0) as blockedBeds, a.totalbeds-n.atotal-b.blockedBeds as openbeds from `productiveNewData` n left join `productiveAccountXref` as s on s.shift = n.shift and s.dept = n.deptId left join `ProductiveDept` as a on a.accountId = n.accountId and a.id = n.deptId left join `productiveUser` as u on u.id = n.userId left outer join `productiveblockedBeds` as b on b.accountId = n.accountId and b.deptId = n.deptId LEFT JOIN `productiveNewData` as z on z.deptId = n.deptId and z.dayDate = n.dayDate and z.shift=n.shift LEFT OUTER JOIN `productiveEscalations` as e on e.dataId = n.id LEFT OUTER JOIN `productiveAcctEscalations` as ae on ae.id = e.escalation where n.id={$dataId} group by n.id");
               		
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
	$dataId = (isset($_REQUEST['dataId']) ? $_REQUEST['dataId']:false);
	$accountId = Config::get('account')['accountId'];
	//$dayDate = (isset($_REQUEST['dayDate']) ? $_REQUEST['dayDate']:'');
	//$accountId = (isset($_REQUEST['accountId']) ? $_REQUEST['accountId']:'');
	//$deptId = (isset($_REQUEST['deptId']) ? $_REQUEST['deptId']:'');
	$visitTotal = (isset($_REQUEST['visitTotal']) ? $_REQUEST['visitTotal']:0);
	$hoursTotal = (isset($_REQUEST['hoursTotal']) ? floatval($_REQUEST['hoursTotal']):0);
	//$hrs1 = (isset($_REQUEST['hrs1']) ? floatval($_REQUEST['hrs1']):0);
	//$hrs2 = (isset($_REQUEST['hrs2']) ? floatval($_REQUEST['hrs2']):0);
	//$hrs3 = (isset($_REQUEST['hrs3']) ? floatval($_REQUEST['hrs3']):0);
	//$hoursrqd = (isset($_REQUEST['hoursrqd']) ? intval($_REQUEST['hoursrqd']):1);
	
		if($dataId){
		
		$data = array(
				'visitsSubmitted'=>$visitTotal,
				'hoursSubmitted'=>$hoursTotal
				);
			
		$where = array(
				'id'=>$dataId,
				'accountId'=>$accountId
				);
		Config::get('db')->update('productiveNewData',$data,$where);

		echo json_encode(array('success'=>true,'updated'=>false));
		return true;
		}else{
		echo json_encode(array('success'=>false,'updated'=>false));
		return true;	
		}
}

function UpdateVisits2() {
	$dataId = (isset($_REQUEST['dataId']) ? $_REQUEST['dataId']:false);
	$dayDate = (isset($_REQUEST['dayDate']) ? $_REQUEST['dayDate']:'');
	$accountId = (isset($_REQUEST['accountId']) ? $_REQUEST['accountId']:'');
	$deptId = (isset($_REQUEST['deptId']) ? $_REQUEST['deptId']:'');
	$ocalc = (isset($_REQUEST['ocalc']) ? $_REQUEST['ocalc']:0);
	$visitTotal = (isset($_REQUEST['visitTotal']) ? $_REQUEST['visitTotal']:0);
	$hoursTotal = (isset($_REQUEST['hoursTotal']) ? floatval($_REQUEST['hoursTotal']):0);
	$hrs1 = (isset($_REQUEST['hrs1']) ? floatval($_REQUEST['hrs1']):0);
	$hrs2 = (isset($_REQUEST['hrs2']) ? floatval($_REQUEST['hrs2']):0);
	$hrs3 = (isset($_REQUEST['hrs3']) ? floatval($_REQUEST['hrs3']):0);
	//$hoursrqd = (isset($_REQUEST['hoursrqd']) ? intval($_REQUEST['hoursrqd']):1);
	
	if($dataId && intval($visitTotal)>0 && $ocalc==1){
		
		$data = array(
				'visitsSubmitted'=>$visitTotal,
				'hoursSubmitted'=>$hoursTotal
				);
			
		$where = array(
				'id'=>$dataId
				);
		Config::get('db')->update('productiveNewData',$data,$where);
		
	}else if($dataId && intval($visitTotal)>0 && $ocalc==2){
		
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
		}else{
			//do nothing;
		}
		echo json_encode(array('success'=>true,'updated'=>false));
		return true;
}

/////////////////////////////////////
// Clear Nursing Record
function clearRecord() {
	$dataId = (isset($_REQUEST['dataId']) ? $_REQUEST['dataId']:'');
	$accountId = Config::get('account')['accountId'];

		$dbProd = Config::get('db')->get_results("SELECT n.*, d.charge, IFNULL(e.id,'') as escalationId from `productiveNewData` n LEFT JOIN `ProductiveDept` as d on d.id=n.deptId LEFT OUTER JOIN `productiveEscalations` as e on e.dataId = n.id WHERE n.id={$dataId} AND n.accountId={$accountId} group by n.id");
     if($dbProd && intval($dbProd[0]['shift'])!=0){
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
				'nvariance1' =>0,
				'nvariance2' =>0,
				'nvariance3' =>0,
				'hrsVariance' =>0,
				'hrsVarianceActual' =>0,
				'atotal' =>0,
				'patientCount2' =>0,
				'patientCount3' =>0,
				'patientCount4' =>0,
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
				'oneto11' =>0,
				'oneto12' =>0,
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
				'gridrnvariance' =>0,
				'gridrnproductivity' =>0.00,
				'gbudgetVar' =>0.00,
				'rbudgetVar' =>0.00,
				'gridrnbudgetVar' =>0.00,
				'ghrsVariance' =>0.00,
				'ahrsVariance' =>0.00,
				'rhrsVariance' =>0.00,
				'gridrnhrsVariance' =>0.00,
				'submitCount' =>0,
				'epicScore' =>0.00,
				'rnVariance1' =>0.00,
				'hppd2' =>0.00,
				'rnAcuity' =>0.00,
				'oneto1Count' =>0,
				'oneto2Count' =>0,
				'oneto3Count' =>0,
				'oneto3p5Count' =>0,
				'oneto4Count' =>0,
				'oneto4p5Count' =>0,
				'oneto5Count' =>0,
				'oneto5p5Count' =>0,
				'oneto6Count' =>0,
				'twoto1Count' =>0,
				'threeto1Count' =>0,
				'epicOrig' =>0.00,
				'track1Detail' =>'',
				'track2Detail' =>'',
				'track3Detail' =>'',
				'track4Detail' =>'',
				'censusTotal' =>0,
				'planSubmitted' =>0,
				'visitsSubmitted' =>0,
				'hoursSubmitted' =>0,
				'hrs1Submitted' =>0,
				'hrs2Submitted' =>0,
				'hrs3Submitted' =>0,
				'whpCustom' =>0,
				'whpCustom2' =>0,
				'whpCustom3' =>0,
				'whpCustom4' =>0,
				'whpCustom5' =>0,
				'IndUOS' =>0,
				'IndHRS' =>0,
				'indUserId' =>0,
				'provId' =>0,
				'trackDischarges' =>0,
				'visits' =>0,
				'totalHours' =>0,
				'nedocScore' =>0,
				'checkBox' =>0,
				'grid1' =>0.0,
				'grid2' =>0.0,
				'grid3' =>0.0,
				'grid4' =>0.0,
				'grid5' =>0.0,
				'grid6' =>0.0,
				'grid7' =>0.0,
				'grid8' =>0.0,
				'grid9' =>0.0,
				'grid10' =>0.0,
				'gridvar1' =>0.0,
				'gridvar2' =>0.0,
				'gridvar3' =>0.0,
				'gridvar4' =>0.0,
				'gridvar5' =>0.0,
				'gridvar6' =>0.0,
				'gridvar7' =>0.0,
				'gridvar8' =>0.0,
				'gridvar9' =>0.0,
				'gridvar10' =>0.0,
				'updatedby' =>0
				);
		$where = array(
				'id'=>$dataId,
				'accountId'=>$accountId
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
    }else if($dbProd && intval($dbProd[0]['shift'])==0){
        $dbData = $dbProd[0];
		$escId = $dbData['escalationId'];
		$charge = $dbData['charge'];

		$where = array(
				'id'=>$dataId
				);
		Config::get('db')->delete('productiveNewData',$where);
		
		if($escId){
		$whereEsc = array(
				'dataId'=>$dataId
				);
		Config::get('db')->delete('productiveEscalations',$whereEsc);
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
	$accountId = Config::get('account')['accountId'];

	$dbProd = Config::get('db')->get_results("SELECT * from `productiveEscalations` WHERE `id`={$escId} AND `accountId`={$accountId}");

	if($dbProd){

		$updateEsc = array(
				'active'=>0
				);

		$whereEsc = array(
				'id'=>$escId,
				'accountId'=>$accountId
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
				'patientCount3' =>0,
				'patientCount4' =>0,
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
				'gridrnvariance' =>0,
				'gridrnproductivity' =>0.00,
				'gbudgetVar' =>0.00,
				'rbudgetVar' =>0.00,
				'gridrnbudgetVar' =>0.00,
				'ghrsVariance' =>0.00,
				'ahrsVariance' =>0.00,
				'rhrsVariance' =>0.00,
				'gridrnhrsVariance' =>0.00,
				'submitCount' =>0,
				'epicScore' =>0.00,
				'rnVariance1' =>0.00,
				'hppd2' =>0.00,
				'rnAcuity' =>0.00,
				'oneto1Count' =>0,
				'oneto2Count' =>0,
				'oneto3Count' =>0,
				'oneto3p5Count' =>0,
				'oneto4Count' =>0,
				'oneto4p5Count' =>0,
				'oneto5Count' =>0,
				'oneto5p5Count' =>0,
				'oneto6Count' =>0,
				'twoto1Count' =>0,
				'threeto1Count' =>0,
				'epicOrig' =>0.00,
				'track1Detail' =>'',
				'track2Detail' =>'',
				'track3Detail' =>'',
				'track4Detail' =>'',
				'censusTotal' =>0,
				'planSubmitted' =>0,
				'visitsSubmitted' =>0,
				'hoursSubmitted' =>0,
				'hrs1Submitted' =>0,
				'hrs2Submitted' =>0,
				'hrs3Submitted' =>0,
				'whpCustom' =>0,
				'whpCustom2' =>0,
				'whpCustom3' =>0,
				'whpCustom4' =>0,
				'whpCustom5' =>0,
				'IndUOS' =>0,
				'IndHRS' =>0,
				'indUserId' =>0,
				'nedocScore' =>0,
				'checkBox' =>0,
				'provId' =>0,
				'trackDischarges' =>0,
				'visits' =>0,
				'totalHours' =>0,
				'grid1' =>0.0,
				'grid2' =>0.0,
				'grid3' =>0.0,
				'grid4' =>0.0,
				'grid5' =>0.0,
				'grid6' =>0.0,
				'grid7' =>0.0,
				'grid8' =>0.0,
				'grid9' =>0.0,
				'grid10' =>0.0,
				'gridvar1' =>0.0,
				'gridvar2' =>0.0,
				'gridvar3' =>0.0,
				'gridvar4' =>0.0,
				'gridvar5' =>0.0,
				'gridvar6' =>0.0,
				'gridvar7' =>0.0,
				'gridvar8' =>0.0,
				'gridvar9' =>0.0,
				'gridvar10' =>0.0
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
	$acs = $demo['acs'];
	$am1 = $demo['am1'];
	$awcm = $demo['awcm'];
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
				'acs' => $acs,
				'am1' => $am1,
				'awcm' => $awcm,
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
            'accountId' =>$accountId,
			'deptId' =>$deptId,
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

/////////////////////////////////////
//Get Transfers
function getPortal() {
	$accountId = Config::get('account')['accountId'];
	$userId = Config::get('account')['id'];
	$role = Config::get('account')['role'];
	
	$dbShift =  Config::get('db')->get_results("select n.shift, d.prodMeasure FROM `productiveNewData` n LEFT JOIN `ProductiveDept` as d on d.id=n.deptId where n.userId>0 AND n.accountId={$accountId} AND d.prodMeasure !=2 AND n.dayDate=CURDATE() GROUP BY n.shift ORDER BY n.shift ASC");           
    $dbDepts =  Config::get('db')->get_results("select d.id, d.dept, d.useFlow FROM `ProductiveDept` d WHERE d.active>0 AND d.active<3 AND d.accountId={$accountId} AND d.prodMeasure !=2 GROUP BY d.id ORDER BY d.dept ASC");           	
	
	if (count($dbShift)>0 && $dbDepts){
	$shift = $dbShift[0]['shift'];
	$dbTransfers =  Config::get('db')->get_results("select f.*, e.dept, (CASE when n.userId>0 THEN n.id ELSE 0 END) as flowrecordId, (CASE WHEN n.userId>0 THEN n.note ELSE '' END) as Note, (CASE WHEN n.userId>0 THEN ROUND(n.nvariance,1) ELSE '' END) as rnvar, (CASE WHEN n.userId>0 THEN (e.totalbeds - n.atotal) ELSE '' END) as beds, s.shift as shiftName, e.dept as inDept, e.useFlow FROM `productiveFlow` f LEFT JOIN `productiveShifts` as s on s.id=f.flowShift LEFT OUTER JOIN `ProductiveDept` as d on d.id=f.outdeptId LEFT JOIN `ProductiveDept` as e on e.id=f.flowdeptId LEFT JOIN `productiveNewData` as n on n.dayDate=f.flowdayDate AND n.deptId=f.flowdeptId AND n.shift=f.flowShift WHERE f.outflowCount>0 AND f.flowShift={$shift} AND f.flowaccountId={$accountId} AND f.flowdeptId>0 GROUP BY f.id ORDER BY e.dept ASC");           	
	
	echo json_encode(array('success' => true,'data'=>$dbTransfers,'depts'=>$dbDepts));
    return true;
	}
	$dbNull = array();
    echo json_encode(array('success'=>false,'data'=>$dbNull,'depts'=>$dbDepts));
    return false;
}

/////////////////////////////////////
//Get Transfers
function getportalDepts() {
	$accountId = Config::get('account')['accountId'];
	$userId = Config::get('account')['id'];
	$role = Config::get('account')['role'];
	
	//$dbShift =  Config::get('db')->get_results("select n.shift, d.prodMeasure FROM `productiveNewData` n LEFT JOIN `ProductiveDept` as d on d.id=n.deptId where n.userId>0 AND n.accountId={$accountId} AND d.prodMeasure !=2 AND n.dayDate=CURDATE() GROUP BY n.shift ORDER BY n.shift ASC");           
    $dbDepts =  Config::get('db')->get_results("select d.id, d.dept, d.useFlow FROM `ProductiveDept` d WHERE d.active>0 AND d.active<3 AND d.accountId={$accountId} AND d.prodMeasure !=2 GROUP BY d.id ORDER BY d.dept ASC");           	
	
	if ($dbDepts){
	//$shift = $dbShift[0]['shift'];
	//$dbTransfers =  Config::get('db')->get_results("select f.*, (CASE when n.userId>0 THEN n.id ELSE 0 END) as flowrecordId, (CASE WHEN n.userId>0 THEN n.note ELSE '' END) as Note, (CASE WHEN n.userId>0 THEN ROUND(n.nvariance,1) ELSE '' END) as rnvar, (CASE WHEN n.userId>0 THEN (e.totalbeds - n.atotal) ELSE '' END) as beds, s.shift as shiftName, d.dept as outDept, e.dept as inDept, e.useFlow FROM `productiveFlow` f LEFT JOIN `productiveShifts` as s on s.id=f.flowShift LEFT JOIN `ProductiveDept` as d on d.id=f.outdeptId LEFT JOIN `ProductiveDept` as e on e.id=f.flowdeptId LEFT JOIN `productiveNewData` as n on n.dayDate=f.flowdayDate AND n.deptId=f.flowdeptId AND n.shift=f.flowShift WHERE f.outflowCount>0 AND f.flowShift={$shift} AND d.accountId={$accountId} AND f.flowdayDate=CURDATE() AND f.flowdeptId!=0 GROUP BY f.id ORDER BY d.dept ASC, e.dept ASC");           	
	
	echo json_encode(array('success'=>true,'depts'=>$dbDepts));
    return true;
	}
	$dbNull = array();
    echo json_encode(array('success'=>false,'depts'=>$dbNull));
    return false;
}

function changeTransfer() {
	$deptId = (isset($_REQUEST['deptId'])?$_REQUEST['deptId']:'');
	$flowId = (isset($_REQUEST['flowId'])?$_REQUEST['flowId']:0);
	$shift = (isset($_REQUEST['shift'])?$_REQUEST['shift']:0);
	$dayDate = (isset($_REQUEST['dayDate'])?$_REQUEST['dayDate']:0);
	if($dayDate && $dayDate !=0){
	$newDate = date("Y-m-d",($dayDate / 1000));
	}
	$userId = Config::get('account')['id'];
	$role = Config::get('account')['role'];
	
	if(intval($flowId)>0){
	$dbShift = Config::get('db')->get_results("select f.id, (CASE WHEN n.userId>0 THEN n.nvariance ELSE '' END) as nvariance, (CASE WHEN n.userId>0 THEN n.note ELSE '' END) as note, (CASE WHEN n.userId>0 THEN (d.totalbeds - n.atotal) ELSE '' END) as beds FROM `productiveFlow` f LEFT JOIN `ProductiveDept` as d on d.id={$deptId} LEFT JOIN `productiveNewData` as n on n.deptId={$deptId} AND n.shift=f.flowShift AND n.dayDate=f.flowdayDate WHERE f.id={$flowId} GROUP BY f.id");           
    }else if(intval($flowId)==0 && intval($shift)>0 && $deptId){
	$dbShift = Config::get('db')->get_results("SELECT (CASE WHEN n.userId>0 THEN n.nvariance ELSE '' END) as nvariance, (CASE WHEN n.userId>0 THEN n.note ELSE '' END) as note, (CASE WHEN n.userId>0 THEN (d.totalbeds - n.atotal) ELSE '' END) as beds FROM `productiveNewData` n LEFT JOIN `ProductiveDept` as d on d.id=n.deptId WHERE n.id=(SELECT `id` FROM `productiveNewData` where `deptId`={$deptId} AND `shift`={$shift} AND `userId`>0 AND `dayDate`>=(DATE_SUB(CURDATE(), INTERVAL 1 DAY)) ORDER BY `dayDate` DESC LIMIT 0,1) GROUP BY n.id");           
	}else{
	$dbShift = Config::get('db')->get_results("SELECT (CASE WHEN n.userId>0 THEN n.nvariance ELSE '' END) as nvariance, (CASE WHEN n.userId>0 THEN n.note ELSE '' END) as note, (CASE WHEN n.userId>0 THEN (d.totalbeds - n.atotal) ELSE '' END) as beds FROM `productiveNewData` n LEFT JOIN `ProductiveDept` as d on d.id=n.deptId WHERE n.id=(SELECT `id` FROM `productiveNewData` where `deptId`={$deptId} AND `userId`>0 AND `dayDate`>=(DATE_SUB(CURDATE(), INTERVAL 1 DAY)) ORDER BY `dayDate` DESC, `shift` ASC LIMIT 0,1) GROUP BY n.id");
	}
	if ($dbShift){
	//$change= $dbShift[0];
	//$dbTransfers =  Config::get('db')->get_results("select f.*, (CASE when n.userId>0 THEN n.id ELSE 0 END) as flowrecordId, (CASE WHEN n.userId>0 THEN ROUND(n.nvariance,1) ELSE '' END) as rnvar, (CASE WHEN n.userId>0 THEN (e.totalbeds - n.atotal) ELSE '' END) as beds, s.shift as shiftName, d.dept as outDept, e.dept as inDept, e.useFlow FROM `productiveFlow` f LEFT JOIN `productiveShifts` as s on s.id=f.flowShift LEFT JOIN `ProductiveDept` as d on d.id=f.outdeptId LEFT JOIN `ProductiveDept` as e on e.id=f.flowdeptId LEFT JOIN `productiveNewData` as n on n.dayDate=f.flowdayDate AND n.deptId=f.flowdeptId AND n.shift=f.flowShift WHERE f.outflowCount>0 AND f.flowShift={$shift} AND d.accountId={$accountId} AND f.flowdayDate=CURDATE() GROUP BY f.id ORDER BY d.dept ASC, e.dept ASC");           	
	//$dbDepts =  Config::get('db')->get_results("select d.id, d.dept, d.useFlow FROM `ProductiveDept` d WHERE d.active>0 AND d.active<3 AND d.accountId={$accountId} AND d.prodMeasure !=2 GROUP BY d.id ORDER BY d.dept ASC");           	
	
	echo json_encode(array('success' => true,'data'=>$dbShift[0]));
    return true;
	}
	$dbArray = array();
    echo json_encode(array('success'=>false,'data'=>$dbArray));
    return false;
}



/////////////////////////////////////
//Get Transfers

function updatePortal() {
	$userId = Config::get('account')['id'];
	$accountId = Config::get('account')['accountId'];
	$role = Config::get('account')['role'];
	$flowid0 = (isset($_REQUEST['flowid0'])?$_REQUEST['flowid0']:'');
	$outCount0 = (isset($_REQUEST['outCount0'])?$_REQUEST['outCount0']:'');
	$inCount0 = (isset($_REQUEST['inCount0'])?$_REQUEST['inCount0']:'');
	
	$flowid1 = (isset($_REQUEST['flowid1'])?$_REQUEST['flowid1']:'');
	$outCount1 = (isset($_REQUEST['outCount1'])?$_REQUEST['outCount1']:'');
	$inCount1 = (isset($_REQUEST['inCount1'])?$_REQUEST['inCount1']:'');
	
	$flowid2 = (isset($_REQUEST['flowid2'])?$_REQUEST['flowid2']:'');
	$outCount2 = (isset($_REQUEST['outCount2'])?$_REQUEST['outCount2']:'');
	$inCount2 = (isset($_REQUEST['inCount2'])?$_REQUEST['inCount2']:'');
	
	$flowid3 = (isset($_REQUEST['flowid3'])?$_REQUEST['flowid3']:'');
	$outCount3 = (isset($_REQUEST['outCount3'])?$_REQUEST['outCount3']:'');
	$inCount3 = (isset($_REQUEST['inCount3'])?$_REQUEST['inCount3']:'');
	
	$flowid4 = (isset($_REQUEST['flowid4'])?$_REQUEST['flowid4']:'');
	$outCount4 = (isset($_REQUEST['outCount4'])?$_REQUEST['outCount4']:'');
	$inCount4 = (isset($_REQUEST['inCount4'])?$_REQUEST['inCount4']:'');
	
	$flowid5 = (isset($_REQUEST['flowid5'])?$_REQUEST['flowid5']:'');
	$outCount5 = (isset($_REQUEST['outCount5'])?$_REQUEST['outCount5']:'');
	$inCount5 = (isset($_REQUEST['inCount5'])?$_REQUEST['inCount5']:'');
	
	$flowid6 = (isset($_REQUEST['flowid6'])?$_REQUEST['flowid6']:'');
	$outCount6 = (isset($_REQUEST['outCount6'])?$_REQUEST['outCount6']:'');
	$inCount6 = (isset($_REQUEST['inCount6'])?$_REQUEST['inCount6']:'');
	
	$flowid7 = (isset($_REQUEST['flowid7'])?$_REQUEST['flowid7']:'');
	$outCount7 = (isset($_REQUEST['outCount7'])?$_REQUEST['outCount7']:'');
	$inCount7 = (isset($_REQUEST['inCount7'])?$_REQUEST['inCount7']:'');
	
	$note0 = (isset($_REQUEST['note0'])?$_REQUEST['note0']:'');
	$note1 = (isset($_REQUEST['note1'])?$_REQUEST['note1']:'');
	$note2 = (isset($_REQUEST['note2'])?$_REQUEST['note2']:'');
	$note3 = (isset($_REQUEST['note3'])?$_REQUEST['note3']:'');
	$note4 = (isset($_REQUEST['note4'])?$_REQUEST['note4']:'');
	$note5 = (isset($_REQUEST['note5'])?$_REQUEST['note5']:'');
	$note6 = (isset($_REQUEST['note6'])?$_REQUEST['note6']:'');
	$note7 = (isset($_REQUEST['note7'])?$_REQUEST['note7']:'');
	
	$useFlow0 = (isset($_REQUEST['useFlow0'])?$_REQUEST['useFlow0']:0);
	$useFlow1 = (isset($_REQUEST['useFlow1'])?$_REQUEST['useFlow1']:0);
	$useFlow2 = (isset($_REQUEST['useFlow2'])?$_REQUEST['useFlow2']:0);
	$useFlow3 = (isset($_REQUEST['useFlow3'])?$_REQUEST['useFlow3']:0);
	$useFlow4 = (isset($_REQUEST['useFlow4'])?$_REQUEST['useFlow4']:0);
	$useFlow5 = (isset($_REQUEST['useFlow5'])?$_REQUEST['useFlow5']:0);
	$useFlow6 = (isset($_REQUEST['useFlow6'])?$_REQUEST['useFlow6']:0);
	$useFlow7 = (isset($_REQUEST['useFlow7'])?$_REQUEST['useFlow7']:0);
	
	$indeptId0 = (isset($_REQUEST['indeptId0'])?$_REQUEST['indeptId0']:0);
	$indeptId1 = (isset($_REQUEST['indeptId1'])?$_REQUEST['indeptId1']:0);
	$indeptId2 = (isset($_REQUEST['indeptId2'])?$_REQUEST['indeptId2']:0);
	$indeptId3 = (isset($_REQUEST['indeptId3'])?$_REQUEST['indeptId3']:0);
	$indeptId4 = (isset($_REQUEST['indeptId4'])?$_REQUEST['indeptId4']:0);
	$indeptId5 = (isset($_REQUEST['indeptId5'])?$_REQUEST['indeptId5']:0);
	$indeptId6 = (isset($_REQUEST['indeptId6'])?$_REQUEST['indeptId6']:0);
	$indeptId7 = (isset($_REQUEST['indeptId7'])?$_REQUEST['indeptId7']:0);
	
	$outdeptId0 = (isset($_REQUEST['outdeptId0'])?$_REQUEST['outdeptId0']:0);
	$outdeptId1 = (isset($_REQUEST['outdeptId1'])?$_REQUEST['outdeptId1']:0);
	$outdeptId2 = (isset($_REQUEST['outdeptId2'])?$_REQUEST['outdeptId2']:0);
	$outdeptId3 = (isset($_REQUEST['outdeptId3'])?$_REQUEST['outdeptId3']:0);
	$outdeptId4 = (isset($_REQUEST['outdeptId4'])?$_REQUEST['outdeptId4']:0);
	$outdeptId5 = (isset($_REQUEST['outdeptId5'])?$_REQUEST['outdeptId5']:0);
	$outdeptId6 = (isset($_REQUEST['outdeptId6'])?$_REQUEST['outdeptId6']:0);
	$outdeptId7 = (isset($_REQUEST['outdeptId7'])?$_REQUEST['outdeptId7']:0);
	
	$flowId0 = (isset($_REQUEST['flowId0'])?$_REQUEST['flowId0']:0);
	$flowId1 = (isset($_REQUEST['flowId1'])?$_REQUEST['flowId1']:0);
	$flowId2 = (isset($_REQUEST['flowId2'])?$_REQUEST['flowId2']:0);
	$flowId3 = (isset($_REQUEST['flowId3'])?$_REQUEST['flowId3']:0);
	$flowId4 = (isset($_REQUEST['flowId4'])?$_REQUEST['flowId4']:0);
	$flowId5 = (isset($_REQUEST['flowId5'])?$_REQUEST['flowId5']:0);
	$flowId6 = (isset($_REQUEST['flowId6'])?$_REQUEST['flowId6']:0);
	$flowId7 = (isset($_REQUEST['flowId7'])?$_REQUEST['flowId7']:0);
	
	$flowrecordId0 = (isset($_REQUEST['flowrecordId0'])?$_REQUEST['flowrecordId0']:0);
	$flowrecordId1 = (isset($_REQUEST['flowrecordId1'])?$_REQUEST['flowrecordId1']:0);
	$flowrecordId2 = (isset($_REQUEST['flowrecordId2'])?$_REQUEST['flowrecordId2']:0);
	$flowrecordId3 = (isset($_REQUEST['flowrecordId3'])?$_REQUEST['flowrecordId3']:0);
	$flowrecordId4 = (isset($_REQUEST['flowrecordId4'])?$_REQUEST['flowrecordId4']:0);
	$flowrecordId5 = (isset($_REQUEST['flowrecordId5'])?$_REQUEST['flowrecordId5']:0);
	$flowrecordId6 = (isset($_REQUEST['flowrecordId6'])?$_REQUEST['flowrecordId6']:0);
	$flowrecordId7 = (isset($_REQUEST['flowrecordId7'])?$_REQUEST['flowrecordId7']:0);
	
	$newRecord0 = (isset($_REQUEST['newRecord0'])?$_REQUEST['newRecord0']:0);
	$newRecord1 = (isset($_REQUEST['newRecord1'])?$_REQUEST['newRecord1']:0);
	$newRecord2 = (isset($_REQUEST['newRecord2'])?$_REQUEST['newRecord2']:0);
	$newRecord3 = (isset($_REQUEST['newRecord3'])?$_REQUEST['newRecord3']:0);
	$newRecord4 = (isset($_REQUEST['newRecord4'])?$_REQUEST['newRecord4']:0);
	$newRecord5 = (isset($_REQUEST['newRecord5'])?$_REQUEST['newRecord5']:0);
	$newRecord6 = (isset($_REQUEST['newRecord6'])?$_REQUEST['newRecord6']:0);
	$newRecord7 = (isset($_REQUEST['newRecord7'])?$_REQUEST['newRecord7']:0);
	
	$shift = (isset($_REQUEST['shift'])?$_REQUEST['shift']:0);
	$newDate = (isset($_REQUEST['dayDate'])?$_REQUEST['dayDate']:0);
	
	
	$useflow = array();
	
	if($flowid0 && intval($flowid0)>0){
		if(intval($useFlow0)==2 && intval($flowrecordId0)>0){
		$useflow[] = array(
		'useFlow' => $indeptId0,
		'flowrecordId' => $flowrecordId0,
		'inCount' => $inCount0
			);
		}
	}
		if(intval($newRecord0==0)){
		$flowupdate = array(
			'outflowCount' => $outCount0,
			'flowdeptId' => $indeptId0,
			'flowCount' => $inCount0,
			'note' => $note0
			);
		$flowwhere = array(
			'id' => $flowid0
			);
		$newTransfer = Config::get('db')->update('productiveFlow',$flowupdate,$flowwhere);
		}else{
		$flowupdate = array(
			'outdeptId' => $outdeptId0,
			'flowaccountId' => $accountId,
			'outflowCount' => $outCount0,
			'flowdeptId' => $indeptId0,
			'flowCount' => $inCount0,
			'flowShift' => $shift,
			'flowdayDate' => date("Y-m-d"),
			'note' => $note0
			);
		$newTransfer = Config::get('db')->insert('productiveFlow',$flowupdate);
			
		}
	
	if($flowid1 && intval($flowid1)>0){
		if(intval($useFlow1)==2 && intval($flowrecordId1)>0){
		$useflow[] = array(
		'useFlow' => $indeptId1,
		'flowrecordId' => $flowrecordId1,
		'inCount' => $inCount1
			);
		}
	}
	if(intval($newRecord1==0)){
	$flowupdate = array(
		'outflowCount' => $outCount1,
		'flowCount' => $inCount1,
		'flowdeptId' => $indeptId1,
		'note' => $note1
		);
	$flowwhere = array(
		'id' => $flowid1
		);
	$newTransfer = Config::get('db')->update('productiveFlow',$flowupdate,$flowwhere);
	}else{
		$flowupdate = array(
			'outdeptId' => $outdeptId1,
			'flowaccountId' => $accountId,
			'outflowCount' => $outCount1,
			'flowdeptId' => $indeptId1,
			'flowCount' => $inCount1,
			'flowShift' => $shift,
			'flowdayDate' => date("Y-m-d"),
			'note' => $note1
			);
		$newTransfer = Config::get('db')->insert('productiveFlow',$flowupdate);
			
		}
	
	if($flowid2 && intval($flowid2)>0){
		if(intval($useFlow2)==2 && intval($flowrecordId2)>0){
		$useflow[] = array(
		'useFlow' => $indeptId2,
		'flowrecordId' => $flowrecordId2,
		'inCount' => $inCount2
		);
		}
	}
	if(intval($newRecord2==0)){
	$flowupdate = array(
		'outflowCount' => $outCount2,
		'flowCount' => $inCount2,
		'flowdeptId' => $indeptId2,
		'note' => $note2
		);
	$flowwhere = array(
		'id' => $flowid2
		);
	$newTransfer = Config::get('db')->update('productiveFlow',$flowupdate,$flowwhere);
	}else{
		$flowupdate = array(
			'outdeptId' => $outdeptId2,
			'flowaccountId' => $accountId,
			'outflowCount' => $outCount2,
			'flowdeptId' => $indeptId2,
			'flowCount' => $inCount2,
			'flowShift' => $shift,
			'flowdayDate' => date("Y-m-d"),
			'note' => $note2
			);
		$newTransfer = Config::get('db')->insert('productiveFlow',$flowupdate);
			
		}
	
	if($flowid3 && intval($flowid3)>0){
		if(intval($useFlow3)==2 && intval($flowrecordId3)>0){
		$useflow[] = array(
		'useFlow' => $indeptId3,
		'flowrecordId' => $flowrecordId3,
		'inCount' => $inCount3
		);
		}
	}
	if(intval($newRecord3==0)){
	$flowupdate = array(
		'outflowCount' => $outCount3,
		'flowCount' => $inCount3,
		'flowdeptId' => $indeptId3,
		'note' => $note3
		);
	$flowwhere = array(
		'id' => $flowid3
		);
	$newTransfer = Config::get('db')->update('productiveFlow',$flowupdate,$flowwhere);
	}else{
		$flowupdate = array(
			'outdeptId' => $outdeptId3,
			'flowaccountId' => $accountId,
			'outflowCount' => $outCount3,
			'flowdeptId' => $indeptId3,
			'flowCount' => $inCount3,
			'flowShift' => $shift,
			'flowdayDate' => date("Y-m-d"),
			'note' => $note3
			);
		$newTransfer = Config::get('db')->insert('productiveFlow',$flowupdate);
			
		}
	
	if($flowid4 && intval($flowid4)>0){
		if(intval($useFlow4)==2 && intval($flowrecordId4)>0){
		$useflow[] = array(
		'useFlow' => $indeptId4,
		'flowrecordId' => $flowrecordId4,
		'inCount' => $inCount4
		);
		}
	}
	if(intval($newRecord4==0)){
	$flowupdate = array(
		'outflowCount' => $outCount4,
		'flowCount' => $inCount4,
		'flowdeptId' => $indeptId4,
		'note' => $note4
		);
	$flowwhere = array(
		'id' => $flowid4
		);
	$newTransfer = Config::get('db')->update('productiveFlow',$flowupdate,$flowwhere);
	}else{
		$flowupdate = array(
			'outdeptId' => $outdeptId4,
			'flowaccountId' => $accountId,
			'outflowCount' => $outCount4,
			'flowdeptId' => $indeptId4,
			'flowCount' => $inCount4,
			'flowShift' => $shift,
			'flowdayDate' => date("Y-m-d"),
			'note' => $note4
			);
		$newTransfer = Config::get('db')->insert('productiveFlow',$flowupdate);
			
		}
	
	if($flowid5 && intval($flowid5)>0){
		if(intval($useFlow5)==2 && intval($flowrecordId5)>0){
		$useflow[] = array(
		'useFlow' => $indeptId5,
		'flowrecordId' => $flowrecordId5,
		'inCount' => $inCount5
		);
		}
	}
	if(intval($newRecord5==0)){
	$flowupdate = array(
		'outflowCount' => $outCount5,
		'flowCount' => $inCount5,
		'flowdeptId' => $indeptId5,
		'note' => $note5
		);
	$flowwhere = array(
		'id' => $flowid5
		);
	$newTransfer = Config::get('db')->update('productiveFlow',$flowupdate,$flowwhere);
	}else{
		$flowupdate = array(
			'outdeptId' => $outdeptId5,
			'flowaccountId' => $accountId,
			'outflowCount' => $outCount5,
			'flowdeptId' => $indeptId5,
			'flowCount' => $inCount5,
			'flowShift' => $shift,
			'flowdayDate' => date("Y-m-d"),
			'note' => $note5
			);
		$newTransfer = Config::get('db')->insert('productiveFlow',$flowupdate);
			
		}
	
	if($flowid6 && intval($flowid6)>0){
		if(intval($useFlow6)==2 && intval($flowrecordId6)>0){
		$useflow[] = array(
		'useFlow' => $indeptId6,
		'flowrecordId' => $flowrecordId6,
		'inCount' => $inCount6
		);
		}
	}
	if(intval($newRecord6==0)){
	$flowupdate = array(
		'outflowCount' => $outCount6,
		'flowCount' => $inCount6,
		'flowdeptId' => $indeptId6,
		'note' => $note6
		);
	$flowwhere = array(
		'id' => $flowid6
		);
	$newTransfer = Config::get('db')->update('productiveFlow',$flowupdate,$flowwhere);
	}else{
		$flowupdate = array(
			'outdeptId' => $outdeptId6,
			'flowaccountId' => $accountId,
			'outflowCount' => $outCount6,
			'flowdeptId' => $indeptId6,
			'flowCount' => $inCount6,
			'flowShift' => $shift,
			'flowdayDate' => date("Y-m-d"),
			'note' => $note6
			);
		$newTransfer = Config::get('db')->insert('productiveFlow',$flowupdate);
			
		}
	
	if($flowid7 && intval($flowid7)>0){
		if(intval($useFlow7)==2 && intval($flowrecordId7)>0){
		$useflow[] = array(
		'useFlow' => $indeptId7,
		'flowrecordId' => $flowrecordId7,
		'inCount' => $inCount7
		);
		}
	}
	if(intval($newRecord7==0)){
	$flowupdate = array(
		'outflowCount' => $outCount7,
		'flowCount' => $inCount7,
		'flowdeptId' => $indeptId7,
		'note' => $note7
		);
	$flowwhere = array(
		'id' => $flowid7
		);
	$newTransfer = Config::get('db')->update('productiveFlow',$flowupdate,$flowwhere);
	}else{
		$flowupdate = array(
			'outdeptId' => $outdeptId7,
			'flowaccountId' => $accountId,
			'outflowCount' => $outCount7,
			'flowdeptId' => $indeptId7,
			'flowCount' => $inCount7,
			'flowShift' => $shift,
			'flowdayDate' => date("Y-m-d"),
			'note' => $note7
			);
		$newTransfer = Config::get('db')->insert('productiveFlow',$flowupdate);
			
		}
	
	//if(count($useflow)>0){
	//	foreach($useflow as $flowrecord){
	//	updateflowrecords($flowrecord['flowrecordId'],$flowrecord['inCount']);
	//	}
	//}
	
	echo json_encode(array('success' => true,'useFlow' =>count($useflow)));
    return true;
}


function updateflowrecords($recordId,$count) {
	$accountId = Config::get('account')['accountId'];
	$inflow = $count;
	$dataId = $recordId;
	
	$dbData = Config::get('db')->get_results("select n.*, (select count(`dept`) from `productiveAccountXref` where `type`<2 AND `whpPlan` !=1 and `dept`=n.deptId) as shiftCount, s.value, s.shiftOrder, h.useFlow, h.averageAcuity, h.avgWage, h.minRN, h.showEpic, h.ptTotal2, h.ptTotal3, h.ptTotal4, h.desc7, h.desc8, h.desc9, h.actionPlan, h.desc10, h.countcharge, h.useGrid, h.typetrack1, h.typetrack2, h.typetrack3, h.typetrack4, h.rnCount, h.rn1Count, h.rn2Count, h.roundrn, h.oneto1Acuity, h.oneto2Acuity, h.oneto3Acuity, h.oneto4Acuity, h.oneto5Acuity, h.oneto6Acuity, h.hppd, h.escalations, h.trackdc, h.trackdata, h.prodMeasure1, h.thresholdHigh, h.thresholdLow, h.rnThreshold, h.track1Desc, h.track2Desc, h.track3Desc, h.track4Desc, h.adjustment, a.displayProd, a.productivityPosNeg, h.viewPage, h.inshiftProd, h.los, h.dept, h.rnVariance, h.churn, h.shiftsperDay, h.nurse1Variance, h.shift as censusShift from `productiveNewData` n left join `ProductiveDept` as h on h.id=n.deptId left join `productiveAccount` as a on a.id = h.accountId left join `productiveShifts` as t on t.id = n.shift left join `productiveAccountXref` as s on s.dept = n.deptId and s.shift = n.shift LEFT OUTER JOIN `productiveDeptXref` as x on x.deptId=n.deptId where n.id={$dataId} group by n.id");
		
		$useFlow = intval($dbData[0]['useFlow']);
		
		if($useFlow==2 && intval($dbData[0]['averageAcuity'])>0 && intval($inflow)>0){
		$acuityFlow = floatval($dbData[0]['averageAcuity']);
		$ptflow = intval($inflow);
		$flowAc = floatval($ptflow) / floatval($acuityFlow);
		}else{
		$acuityFlow = 0.0;
		$ptflow = 0;
		$flowAc = 0.0;
		}
		
		$gridCount = floatval($dbData[0]['antecount']) + floatval($dbData[0]['chargecount']) + floatval($dbData[0]['customNurse']) + floatval($dbData[0]['customNurse2']) + intval($dbData[0]['otherNurse1']) + intval($dbData[0]['otherNurse2']) + intval($dbData[0]['otherNurse3']) + intval($dbData[0]['seccount']) + intval($dbData[0]['sittercount']) + intval($dbData[0]['techcount']);
	
		$oneto1 = floatval($dbData[0]['oneto1']);
		$oneto2 = floatval($dbData[0]['oneto2']);
		$high = floatval($dbData[0]['oneto3']);
		$med = floatval($dbData[0]['oneto4']);
		$low = floatval($dbData[0]['oneto5']);
		$oneto6 = floatval($dbData[0]['oneto6']);
		$gvariance = floatval($dbData[0]['gvariance']);
		$gridrnvariance = floatval($dbData[0]['gridrnvariance']);
		
		
		$hppd = floatval($dbData[0]['hppd']);
		$useGrid = intval($dbData[0]['useGrid']);
		$deptId = intval($dbData[0]['deptId']);
		$dayDate = $dbData[0]['dayDate'];
		$inshiftProd = intval($dbData[0]['inshiftProd']);
		
		$countcharge = intval($dbData[0]['countcharge']);
		$rnCount = intval($dbData[0]['rnCount']);
		$rn1Count = intval($dbData[0]['rn1Count']);
		$rn2Count = intval($dbData[0]['rn2Count']);
		$roundrn = intval($dbData[0]['roundrn']);
	
		$shiftCount = intval($dbData[0]['shiftCount']);
		
		$displayProd = intval($dbData[0]['rnVariance']);
		$prodnurseCount = ((floatval($dbData[0]['chargecount']) * $countcharge) + (floatval($dbData[0]['antecount']) * $rnCount) + (floatval($dbData[0]['customNurse']) * $rn1Count) + (floatval($dbData[0]['customNurse2']) * $rn2Count));
		$prodCalc = intval($dbData[0]['productivityPosNeg']);	
		
		$oneto1Acuity = $dbData[0]['oneto1Acuity'];
		$oneto2Acuity = $dbData[0]['oneto2Acuity'];
		$oneto3Acuity = $dbData[0]['oneto3Acuity'];
		$oneto4Acuity = $dbData[0]['oneto4Acuity'];
		$oneto5Acuity = $dbData[0]['oneto5Acuity'];
		$oneto6Acuity = $dbData[0]['oneto6Acuity'];
		
		$aproductivity = 0;
		$lproductivity = 0;
		
		$avgWage = floatval($dbData[0]['avgWage']);
		
		$patienttotal = intval($dbData[0]['atotal']) + $ptflow;

		$addHours = floatval($dbData[0]['addResourceHrs']);	
		
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
				
		$updated=true;
		
			if(intval($nursecount)==0){
				$nursecheck = false;
			}else{
				$nursecheck = true;
			}
		
			$acheck = $high+$med+$low+$oneto1+$oneto2+$oneto6+$ptflow;
			if($acheck == $patienttotal){
			$antecheck = true;
			}else{
			$antecheck = false;
			}
			
			if($useGrid==1 && $gridCount>0){
				$gridReqd = $gridCount - $gvariance;
				$prodnurseReqd = $prodnurseCount - $gridrnvariance;
				$gproductivity = (1-(($gridCount - $gridReqd) / $gridReqd)) * 100;
				$gridrnproductivity = (1-(($prodnurseCount - $prodnurseReqd) / $prodnurseReqd)) * 100;
				}else{
					$gproductivity = 0.00;
					$gridrnproductivity=0.00;
				}
			
			$nurseprodReqd = $prodnurseCount - $nvariance;
					if($nurseprodReqd>0){
					$nproductivity = (1-(($prodnurseCount - $nurseprodReqd) / $nurseprodReqd)) * 100;
					}else{
					$nproductivity = 0.0;
					}
			
			$antenurses = (($gridCount * 24) / $shiftCount) + $nurseAdd;
			$antehours = ($patienttotal * $hppd) / $shiftCount;
					if(intval($antehours)>0 && $prodMeasure==1){
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
				
			
			if($prodMeasure==4){	
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
					
		    $data = array(
                'entered' =>date("Y-m-d H:i:s",$currentTime),
				'avariance' => $avariance,
				'nvariance' => $nvariance,
				'atotal' => $patienttotal,
				'patientCount2' => $patienttotal2,
				'patientCount3' => $patienttotal3,
				'patientCount4' => $patienttotal4,
				'addResourceHrs' => $addResourceHrs,
				'aproductivity' => $aproductivity,
				'nproductivity' => $nproductivity,
				'lproductivity' => $lproductivity,
				'customProd' => $gproductivity,
				'transfers' => $transfers,
				'hrsVariance' => $hrsVariance,
				'ahrsVariance' => $ahrsVariance,
				'ghrsVariance' => $ghrsVariance,
				'rhrsVariance' => $rhrsVariance,
				'budgetVar' =>$budgetVar,
				'gbudgetVar' =>$gbudgetVar,
				'rbudgetVar' =>$rbudgetVar,
				'gvariance' => $gvariance,
				'gridrnvariance' => $gridrnvariance,
				'gridrnproductivity' => $gridrnproductivity,
				'gridrnbudgetVar' => $gridrnbudgetVar,
				'gridrnhrsVariance' => $gridrnhrsVariance,
				'inflow' => $inflow
            );
			$updateWhere = array('id' => $dataId);
            
			$newProd = Config::get('db')->update('productiveNewData',$data,$updateWhere);
		
	echo json_encode(array('success'=>true));
	return true;
	
}

// Staff Summary
function getStaff() {
	$dataId = (isset($_REQUEST['dataId'])?$_REQUEST['dataId']:'');
	$prodMeasure = (isset($_REQUEST['prodMeasure'])?$_REQUEST['prodMeasure']:1);
	$userId = Config::get('account')['id'];
	$accountId = Config::get('account')['accountId'];
     
	if(intval($prodMeasure==2) || intval($prodMeasure)==3){
	$staffSummary = Config::get('db')->get_results("select n.track1, n.track2, n.track3, n.track4, SUM(n.chargecount) as chargeCount, n.addResourceHrs, SUM(n.antecount) as anteCount, SUM(n.customNurse) as customNurse, SUM(n.customNurse2) as customNurse2, SUM(n.techcount) as techcount, SUM(n.seccount) as seccount, SUM(n.sittercount) as sittercount, SUM(n.otherNurse1) as otherNurse1, SUM(n.otherNurse2) as otherNurse2, SUM(otherNurse3) as otherNurse3, d.dept, d.prodMeasure, d.trackdata, IFNULL(d.track1Desc,0) as track1Desc, IFNULL(d.track2Desc,0) as track2Desc, IFNULL(d.track3Desc,0) as track3Desc, IFNULL(d.track4Desc,0) as track4Desc, IFNULL(d.techLabel,0) as techLabel, IFNULL(d.secLabel,0) as secLabel, IFNULL(d.nurseDesc,0) as nurseDesc, IFNULL(d.nurse1Desc,0) as nurse1Desc, IFNULL(d.sittersNEWDesc,0) as sittersNEWDesc, IFNULL(d.other1Desc,0) as other1Desc, IFNULL(d.other2Desc,0) as other2Desc, IFNULL(d.other3Desc,0) as other3Desc, d.skilldesc1, d.skilldesc2, d.skilldesc3, d.skilldesc4, d.skilldesc5, d.skilldesc6, d.skilldesc7, d.skilldesc8, d.skilldesc9, d.skilldesc10,  d.skill1, d.skill2, d.skill3, d.skill4, d.skill5, d.skill6, d.skill7, d.skill8, d.skill9, d.skill10, SUM(n.skill1val) as skill1val, SUM(n.skill2val) as skill2val, SUM(n.skill3val) as skill3val, SUM(n.skill4val) as skill4val, SUM(n.skill5val) as skill5val, SUM(n.skill6val) as skill6val, SUM(n.skill7val) as skill7val, SUM(n.skill8val) as skill8val, SUM(n.skill9val) as skill9val, SUM(n.skill10val) as skill10val, n.note  from `productiveNewData` n left join `ProductiveDept` as d on d.id=n.deptId where n.deptId=(SELECT `deptId` FROM `productiveNewData` where `id`={$dataId}) and n.dayDate=(SELECT `dayDate` FROM `productiveNewData` where `id`={$dataId}) and n.type=1 and n.activeRecord>0 AND n.accountId={$accountId} GROUP BY n.deptId");           
	}else{
	$staffSummary = Config::get('db')->get_results("select n.*, d.staff1, d.staff2, d.staff3, d.staff4, d.staff5, d.staff6, d.staff7, d.staff8, d.staff9, d.staff10, d.useGrid, d.gridOpt1, d.gridOpt2, d.gridOpt3, d.other1Calc, (CASE WHEN n.chargecount<1 AND n.chargecount>0 THEN round((n.chargecount * d.staff1),1) ELSE round(n.chargecount,0) END) as chargeCount, round(n.antecount,0) as anteCount, d.dept, d.prodMeasure, IFNULL(d.techLabel,0) as techLabel, IFNULL(d.secLabel,0) as secLabel, d.trackdata, IFNULL(d.track1Desc,0) as track1Desc, IFNULL(d.track2Desc,0) as track2Desc, IFNULL(d.track3Desc,0) as track3Desc, IFNULL(d.track4Desc,0) as track4Desc, IFNULL(d.nurseDesc,0) as nurseDesc, IFNULL(d.nurse1Desc,0) as nurse1Desc, IFNULL(d.nurse2Desc,0) as nurse2Desc, IFNULL(d.sittersNEWDesc,0) as sittersNEWDesc, IFNULL(d.other1Desc,0) as other1Desc, IFNULL(d.other2Desc,0) as other2Desc, IFNULL(d.other3Desc,0) as other3Desc, d.skilldesc1, d.skilldesc2, d.skilldesc3, d.skilldesc4, d.skilldesc5, d.skilldesc6, d.skilldesc7, d.skilldesc8, d.skilldesc9, d.skilldesc10, d.skill1, d.skill2, d.skill3, d.skill4, d.skill5, d.skill6, d.skill7, d.skill8, d.skill9, d.skill10, IFNULL(CEIL(n.chargecount - g.cn),0) as cnvar, ROUND(IFNULL((n.antecount - g.rn),0),0) as rnvar, IFNULL((n.techcount - g.pct),0) as pctvar, IFNULL((n.seccount - g.sec),0) as secvar, IFNULL((n.customNurse - g.rn1),0) as rn1var, IFNULL((n.customNurse2 - g.rn2),0) as rn2var, ((n.otherNurse1 * d.other1Calc) - g.other1) as other1var, IFNULL((n.otherNurse2 - g.other2),0) as other2var, IFNULL((n.otherNurse3 - g.other3),0) as other3var, IFNULL((n.sittercount - g.sitter),0) as sitvar, ROUND(IFNULL((n.chargecount - h.cn),0),0) as gcnvar, ROUND(IFNULL((n.antecount - h.rn),0),0) as grnvar, IFNULL((n.techcount - h.pct),0) as gpctvar, IFNULL((n.seccount - h.sec),0) as gsecvar, IFNULL((n.customNurse - h.rn1),0) as grn1var, IFNULL((n.customNurse2 - h.rn2),0) as grn2var, ((n.otherNurse1 * d.other1Calc) - h.other1) as gother1var, IFNULL((n.otherNurse2 - h.other2),0) as gother2var, IFNULL((n.otherNurse3 - h.other3),0) as gother3var, IFNULL((n.sittercount - h.sitter),0) as gsitvar from `productiveNewData` n left outer join `productiveStaffingGrid` as h on h.griddeptId=n.deptId AND (h.dayNight=n.shift OR h.dayNight=((n.shift)-1)) AND h.dow=DAYOFWEEK(n.dayDate) left outer join `productiveStaffingGrid` as g on g.griddeptId=n.deptId and g.dayNight=n.shift and g.census=n.atotal left join `ProductiveDept` as d on d.id=n.deptId where n.id={$dataId} and n.activeRecord>0 AND n.accountId={$accountId}");
    }
	//select n.*, d.useGrid, (CASE WHEN n.chargecount<1 AND n.chargecount>0 THEN round(n.chargecount,1) ELSE round(n.chargecount,0) END) as chargeCount, round(n.antecount,0) as anteCount, d.dept, d.prodMeasure, IFNULL(d.techLabel,0) as techLabel, IFNULL(d.secLabel,0) as secLabel, d.trackdata, IFNULL(d.track1Desc,0) as track1Desc, IFNULL(d.track2Desc,0) as track2Desc, IFNULL(d.track3Desc,0) as track3Desc, IFNULL(d.track4Desc,0) as track4Desc, IFNULL(d.nurseDesc,0) as nurseDesc, IFNULL(d.nurse1Desc,0) as nurse1Desc, IFNULL(d.nurse2Desc,0) as nurse2Desc, IFNULL(d.sittersNEWDesc,0) as sittersNEWDesc, IFNULL(d.other1Desc,0) as other1Desc, IFNULL(d.other2Desc,0) as other2Desc, IFNULL(d.other3Desc,0) as other3Desc, d.skilldesc1, d.skilldesc2, d.skilldesc3, d.skilldesc4, d.skilldesc5, d.skilldesc6, d.skilldesc7, d.skilldesc8, d.skilldesc9, d.skilldesc10, d.skill1, d.skill2, d.skill3, d.skill4, d.skill5, d.skill6, d.skill7, d.skill8, d.skill9, d.skill10, CEIL(((n.chargecount - g.cn) * a.productivityPosNeg)) as cnvar, ROUND(IFNULL((((n.antecount - g.rn) * a.productivityPosNeg)),0),0) as rnvar, IFNULL((((n.techcount - g.pct) * a.productivityPosNeg)),0) as pctvar, IFNULL((((n.seccount - g.sec) * a.productivityPosNeg)),0) as secvar, IFNULL((((n.customNurse - g.rn1) * a.productivityPosNeg)),0) as rn1var, IFNULL((((n.customNurse2 - g.rn2) * a.productivityPosNeg)),0) as rn2var, IFNULL((((n.otherNurse1 - g.other1) * a.productivityPosNeg)),0) as other1var, IFNULL((((n.otherNurse2 - g.other2) * a.productivityPosNeg)),0) as other2var, IFNULL((((n.otherNurse3 - g.other3) * a.productivityPosNeg)),0) as other3var, IFNULL((((n.sittercount - g.sitter) * a.productivityPosNeg)),0) as sitvar, ROUND(IFNULL((((n.chargecount - h.cn) * a.productivityPosNeg)),0),0) as gcnvar, ROUND(IFNULL((((n.antecount - h.rn) * a.productivityPosNeg)),0),0) as grnvar, IFNULL((((n.techcount - h.pct) * a.productivityPosNeg)),0) as gpctvar, IFNULL((((n.seccount - h.sec) * a.productivityPosNeg)),0) as gsecvar, IFNULL((((n.customNurse - h.rn1) * a.productivityPosNeg)),0) as grn1var, IFNULL((((n.customNurse2 - h.rn2) * a.productivityPosNeg)),0) as grn2var, IFNULL((((n.otherNurse1 - h.other1) * a.productivityPosNeg)),0) as gother1var, IFNULL((((n.otherNurse2 - h.other2) * a.productivityPosNeg)),0) as gother2var, IFNULL((((n.otherNurse3 - h.other3) * a.productivityPosNeg)),0) as gother3var, IFNULL((((n.sittercount - h.sitter) * a.productivityPosNeg)),0) as gsitvar from `productiveNewData` n left outer join `productiveStaffingGrid` as h on h.griddeptId=n.deptId AND (h.dayNight=n.shift OR h.dayNight=((n.shift)-1)) AND h.dow=DAYOFWEEK(n.dayDate) left outer join `productiveStaffingGrid` as g on g.griddeptId=n.deptId and g.dayNight=n.shift and g.census=n.atotal left join `ProductiveDept` as d on d.id=n.deptId where n.id={$dataId} and n.activeRecord>0 AND n.accountId={$accountId}");
	
	if($staffSummary){
	echo json_encode(array('success' => true, 'data'=>$staffSummary[0]));
    return true;
	}else{
	echo json_encode(array('success'=>false,'message'=>'update failed.'));
    return false;
	}
}



/////////////////////////////////////
// UPDATE PROD TEST 
function UpdateProdTest() { 
	$shift = (isset($_REQUEST['shift'])) ? $_REQUEST['shift'] : '';
	$dataId = (isset($_REQUEST['dataId'])) ? $_REQUEST['dataId'] : '';
	$deptNum = (isset($_REQUEST['deptId'])) ? $_REQUEST['deptId'] : '';
	//echo 'dataid ' . $dataId;
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
	$extoneto6 = (isset($_REQUEST['extoneto6'])) ? $_REQUEST['extoneto6'] : 0;
	$checkBox = (isset($_REQUEST['checkBox'])) ? $_REQUEST['checkBox'] : 0;
	$nedocs = (isset($_REQUEST['nedocs'])) ? $_REQUEST['nedocs'] : 0;
	$oneto7 = (isset($_REQUEST['oneto7'])) ? $_REQUEST['oneto7'] : 0;
	$oneto8 = (isset($_REQUEST['oneto8'])) ? $_REQUEST['oneto8'] : 0;
	$oneto9 = (isset($_REQUEST['oneto9'])) ? $_REQUEST['oneto9'] : 0;
	$oneto10 = (isset($_REQUEST['oneto10'])) ? $_REQUEST['oneto10'] : 0;
	$oneto11 = (isset($_REQUEST['oneto11'])) ? $_REQUEST['oneto11'] : 0;
	$oneto12 = (isset($_REQUEST['oneto12'])) ? $_REQUEST['oneto12'] : 0;
	$epicScore = (isset($_REQUEST['epicScore'])) ? $_REQUEST['epicScore'] : 0;
	
	$gvariance = (isset($_REQUEST['gvariance'])) ? FLOATVAL($_REQUEST['gvariance']) : 0;
	$gridrnvariance = (isset($_REQUEST['rnvariance'])) ? INTVAL($_REQUEST['rnvariance']) : 0;
	$actualvariance = (isset($_REQUEST['showrnvariance'])) ? FLOATVAL($_REQUEST['showrnvariance']) : 0;
	$nvariance = (isset($_REQUEST['showrnvariance'])) ? FLOATVAL($_REQUEST['showrnvariance']) : 0;
	$nvariance1 = (isset($_REQUEST['showrnvariance1'])) ? FLOATVAL($_REQUEST['showrnvariance1']) : 0;
	$nvariance2 = (isset($_REQUEST['showrnvariance2'])) ? FLOATVAL($_REQUEST['showrnvariance2']) : 0;
	$nvariance3 = (isset($_REQUEST['showrnvariance3'])) ? FLOATVAL($_REQUEST['showrnvariance3']) : 0;
	$gridCount = floatval($nursecount) + floatval($chargecount) + intval($nurse1count) + intval($nurse2count) + intval($other1count) + intval($other2count) + intval($other3count) + intval($seccount) + intval($sittercount) + intval($techcount);
		
	//$oneto11Acuity = (isset($_REQUEST['oneto11Acuity'])) ? $_REQUEST['oneto11Acuity'] : 0;
	$high = (isset($_REQUEST['high'])) ? $_REQUEST['high'] : 0;
	$med = (isset($_REQUEST['med'])) ? $_REQUEST['med'] : 0;
	$low = (isset($_REQUEST['low'])) ? $_REQUEST['low'] : 0;
	$acuityTotal = (isset($_REQUEST['acuityTotal'])) ? $_REQUEST['acuityTotal'] : 0;
	//$admissions = (isset($_REQUEST['admissions'])) ? $_REQUEST['admissions'] : 0;
	//$transfers = (isset($_REQUEST['transfers'])) ? $_REQUEST['transfers'] : 0;
	//$discharges = (isset($_REQUEST['discharges'])) ? $_REQUEST['discharges'] : 0;
	$trackDischarges = (isset($_REQUEST['trackDischarges'])) ? $_REQUEST['trackDischarges'] : 0;
	$trackInsert = (isset($_REQUEST['trackInsert'])) ? $_REQUEST['trackInsert'] : '';
	$track1 = (isset($_REQUEST['track1'])) ? $_REQUEST['track1'] : 0;
	$track2 = (isset($_REQUEST['track2'])) ? $_REQUEST['track2'] : 0;
	$track3 = (isset($_REQUEST['track3'])) ? $_REQUEST['track3'] : 0;
	$track4 = (isset($_REQUEST['track4'])) ? $_REQUEST['track4'] : 0;
	$trackNote1 = (isset($_REQUEST['trackNote1'])) ? addslashes($_REQUEST['trackNote1']) : '';
	$trackNote2 = (isset($_REQUEST['trackNote2'])) ? addslashes($_REQUEST['trackNote2']) : '';
	$trackNote3 = (isset($_REQUEST['trackNote3'])) ? addslashes($_REQUEST['trackNote3']) : '';
	$trackNote4 = (isset($_REQUEST['trackNote4'])) ? addslashes($_REQUEST['trackNote4']) : '';
	$submittedby = (isset($_REQUEST['submittedby'])) ? addslashes($_REQUEST['submittedby']) : '';
	$newRecord = (isset($_REQUEST['newRecord'])) ? addslashes($_REQUEST['newRecord']) : 0;
	
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
	$patienttotal = (isset($_REQUEST['patienttotal'])) ? intval($_REQUEST['patienttotal']) : 0;	
	$patienttotal2 = (isset($_REQUEST['patienttotal2'])) ? intval($_REQUEST['patienttotal2']) : 0;
	$patienttotal3 = (isset($_REQUEST['patienttotal3'])) ? intval($_REQUEST['patienttotal3']) : 0;
	$patienttotal4 = (isset($_REQUEST['patienttotal4'])) ? intval($_REQUEST['patienttotal4']) : 0;
	$zeropts = (isset($_REQUEST['zeropts'])) ? INTVAL($_REQUEST['zeropts']) : 0;
	
	if($patienttotal>0 || ($patienttotal==0 && $zeropts==1)){
		$ptzero = 1;
	}else{
		$ptzero = 0;
	}
	
	$grid1 = (isset($_REQUEST['grid1'])) ? ($_REQUEST['grid1']) : 0;
	$grid2 = (isset($_REQUEST['grid2'])) ? ($_REQUEST['grid2']) : 0;	
	$grid3 = (isset($_REQUEST['grid3'])) ? ($_REQUEST['grid3']) : 0;	
	$grid4 = (isset($_REQUEST['grid4'])) ? ($_REQUEST['grid4']) : 0;	
	$grid5 = (isset($_REQUEST['grid5'])) ? ($_REQUEST['grid5']) : 0;	
	$grid6 = (isset($_REQUEST['grid6'])) ? ($_REQUEST['grid6']) : 0;	
	$grid7 = (isset($_REQUEST['grid7'])) ? ($_REQUEST['grid7']) : 0;	
	$grid8 = (isset($_REQUEST['grid8'])) ? ($_REQUEST['grid8']) : 0;
	$grid9 = (isset($_REQUEST['grid9'])) ? ($_REQUEST['grid9']) : 0;	
	$grid10 = (isset($_REQUEST['grid10'])) ? ($_REQUEST['grid10']) : 0;

	$gridvar1 = (isset($_REQUEST['gridvar1'])) ? ($_REQUEST['gridvar1']) : 0;
	$gridvar2 = (isset($_REQUEST['gridvar2'])) ? ($_REQUEST['gridvar2']) : 0;
	$gridvar3 = (isset($_REQUEST['gridvar3'])) ? ($_REQUEST['gridvar3']) : 0;
	$gridvar4 = (isset($_REQUEST['gridvar4'])) ? ($_REQUEST['gridvar4']) : 0;
	$gridvar5 = (isset($_REQUEST['gridvar5'])) ? ($_REQUEST['gridvar5']) : 0;
	$gridvar6 = (isset($_REQUEST['gridvar6'])) ? ($_REQUEST['gridvar6']) : 0;
	$gridvar7 = (isset($_REQUEST['gridvar7'])) ? ($_REQUEST['gridvar7']) : 0;
	$gridvar8 = (isset($_REQUEST['gridvar8'])) ? ($_REQUEST['gridvar8']) : 0;	
	$gridvar9 = (isset($_REQUEST['gridvar9'])) ? ($_REQUEST['gridvar9']) : 0;
	$gridvar10 = (isset($_REQUEST['gridvar10'])) ? ($_REQUEST['gridvar10']) : 0;
	$ptRatio = (isset($_REQUEST['ptRatio'])) ? ($_REQUEST['ptRatio']) : 0;
	$unscheduled = (isset($_REQUEST['unscheduled'])) ? ($_REQUEST['unscheduled']) : 0;	
		
	
	$transferarray = array();
	
	if(intval($newRecord)==0){
	$userId = Config::get('account')['id'];
	$updatedby = 0;
	}else{
	$updatedby = Config::get('account')['id'];
	$userId = $newRecord;	
	}
	$prodTerm = Config::get('account')['prodTerm'];
	$hrsTerm = Config::get('account')['hrsTerm'];
	//echo 'query: ' . "select n.*, (select count(`dept`) from `productiveAccountXref` where `type`<2 AND `whpPlan` !=1 and `dept`=n.deptId) as shiftCount, s.value, s.shifthrs, s.shiftOrder, h.chargeDesc, h.nurseDesc, h.nurse1Desc, h.nurse2Desc, h.sittersNEWDesc, h.other1Desc, h.other2Desc, h.other3Desc, h.techLabel, h.secLabel, h.patientTotalDesc, h.patientTotalDesc2, h.patientTotalDesc3, h.patientTotalDesc4, h.useFlow, h.averageAcuity, h.avgWage, h.prodMeasure, h.minRN, h.showEpic, h.ptTotal2, h.ptTotal3, h.ptTotal4, h.desc7, h.desc8, h.desc9, h.actionPlan, h.desc10, h.desc11, h.desc12, h.other1Calc, h.other2Calc, h.other3Calc, h.secCalc, h.techCalc, h.countcharge, h.useGrid, h.typetrack1, h.typetrack2, h.typetrack3, h.typetrack4, h.rnCount, h.rn1Count, h.rn2Count, h.roundrn, h.minRN, h.oneto1Acuity, h.oneto2Acuity, h.oneto3Acuity, h.oneto4Acuity, h.oneto5Acuity, h.oneto6Acuity, h.hppd, h.escalations, h.trackdc, h.trackdata, h.prodMeasure1, h.thresholdHigh, h.thresholdLow, h.rnThreshold, h.gThreshold, h.showProdWHP, h.track1Desc, h.track2Desc, h.track3Desc, h.track4Desc, h.adjustment, a.displayProd, a.productivityPosNeg, h.viewPage, h.inshiftProd, h.los, h.dept, h.rnVariance, h.churn, h.shiftsperDay, h.nurse1Variance, h.shift as censusShift from `productiveNewData` n left join `ProductiveDept` as h on h.id=n.deptId left join `productiveAccount` as a on a.id = h.accountId left join `productiveShifts` as t on t.id = n.shift left join `productiveAccountXref` as s on s.dept = n.deptId and s.shift = n.shift LEFT OUTER JOIN `productiveDeptXref` as x on x.deptId=n.deptId where n.id= {$dataId} group by n.id";
	
	if(intval($unscheduled)==1 && intval($deptNum)>0){
		$dbData = Config::get('db')->get_results("select n.*, (select count(`dept`) from `productiveAccountXref` where `type`<2 AND `whpPlan` !=1 and `dept`=n.deptId) as shiftCount, s.value, s.shifthrs, s.shiftOrder, h.add1, h.add2, h.add3, h.add4, h.add5, h.add6, h.extadd6, h.staff1, h.staff2, h.staff3, h.staff4, h.staff5, h.staff6, h.staff7, h.staff8, h.staff9, h.staff10, h.chargeDesc, h.nurseDesc, h.nurse1Desc, h.nurse2Desc, h.sittersNEWDesc, h.other1Desc, h.other2Desc, h.other3Desc, h.techLabel, h.secLabel, h.patientTotalDesc, h.patientTotalDesc2, h.patientTotalDesc3, h.patientTotalDesc4, h.useFlow, h.averageAcuity, h.avgWage, h.prodMeasure, h.showEpic, h.ptTotal2, h.ptTotal3, h.ptTotal4, h.desc7, h.desc8, h.desc9, h.actionPlan, h.desc10, h.desc11, h.desc12, h.other1Calc, h.other2Calc, h.other3Calc, h.secCalc, h.techCalc, h.countcharge, h.useGrid, h.typetrack1, h.typetrack2, h.typetrack3, h.typetrack4, h.rnCount, h.rn1Count, h.rn2Count, h.roundrn, h.minRN, h.oneto1Acuity, h.oneto2Acuity, h.oneto3Acuity, h.oneto4Acuity, h.oneto5Acuity, h.oneto6Acuity, h.hppd, h.escalations, h.trackdc, h.trackdata, h.prodMeasure1, h.thresholdHigh, h.thresholdLow, h.rnThreshold, h.gThreshold, h.showProdWHP, h.track1Desc, h.track2Desc, h.track3Desc, h.track4Desc, h.adjustment, a.displayProd, a.productivityPosNeg, h.viewPage, h.inshiftProd, h.los, h.dept, h.rnVariance, h.churn, h.shiftsperDay, h.nurse1Variance, h.shift as censusShift from `productiveNewData` n left join `ProductiveDept` as h on h.id=n.deptId left join `productiveAccount` as a on a.id = h.accountId left join `productiveShifts` as t on t.id = n.shift left join `productiveAccountXref` as s on s.dept = n.deptId and s.shift = n.shift LEFT OUTER JOIN `productiveDeptXref` as x on x.deptId=n.deptId where n.deptId=" . $deptNum . " and n.userId=0 group by n.deptId");
	}else{
		$dbData = Config::get('db')->get_results("select n.*, (select count(`dept`) from `productiveAccountXref` where `type`<2 AND `whpPlan` !=1 and `dept`=n.deptId) as shiftCount, s.value, s.shifthrs, s.shiftOrder, h.add1, h.add2, h.add3, h.add4, h.add5, h.add6, h.extadd6, h.staff1, h.staff2, h.staff3, h.staff4, h.staff5, h.staff6, h.staff7, h.staff8, h.staff9, h.staff10, h.chargeDesc, h.nurseDesc, h.nurse1Desc, h.nurse2Desc, h.sittersNEWDesc, h.other1Desc, h.other2Desc, h.other3Desc, h.techLabel, h.secLabel, h.patientTotalDesc, h.patientTotalDesc2, h.patientTotalDesc3, h.patientTotalDesc4, h.useFlow, h.averageAcuity, h.avgWage, h.prodMeasure, h.showEpic, h.ptTotal2, h.ptTotal3, h.ptTotal4, h.desc7, h.desc8, h.desc9, h.actionPlan, h.desc10, h.desc11, h.desc12, h.other1Calc, h.other2Calc, h.other3Calc, h.secCalc, h.techCalc, h.countcharge, h.useGrid, h.typetrack1, h.typetrack2, h.typetrack3, h.typetrack4, h.rnCount, h.rn1Count, h.rn2Count, h.roundrn, h.minRN, h.oneto1Acuity, h.oneto2Acuity, h.oneto3Acuity, h.oneto4Acuity, h.oneto5Acuity, h.oneto6Acuity, h.hppd, h.escalations, h.trackdc, h.trackdata, h.prodMeasure1, h.thresholdHigh, h.thresholdLow, h.rnThreshold, h.gThreshold, h.showProdWHP, h.track1Desc, h.track2Desc, h.track3Desc, h.track4Desc, h.adjustment, a.displayProd, a.productivityPosNeg, h.viewPage, h.inshiftProd, h.los, h.dept, h.rnVariance, h.churn, h.shiftsperDay, h.nurse1Variance, h.shift as censusShift from `productiveNewData` n left join `ProductiveDept` as h on h.id=n.deptId left join `productiveAccount` as a on a.id = h.accountId left join `productiveShifts` as t on t.id = n.shift left join `productiveAccountXref` as s on s.dept = n.deptId and s.shift = n.shift LEFT OUTER JOIN `productiveDeptXref` as x on x.deptId=n.deptId where n.id=" . $dataId . " group by n.id");
	}	
		/*
		$useFlow = intval($dbData[0]['useFlow']);
		if($useFlow==2 && intval($dbData[0]['averageAcuity'])>0 && intval($inflow)>0){
		$acuityFlow = floatval($dbData[0]['averageAcuity']);
		$ptflow = intval($inflow);
		$flowAc = floatval($ptflow) / floatval($acuityFlow);
		}else{
		$acuityFlow = 0.0;
		$ptflow = 0;
		$flowAc = 0.0;
		}
		*/
		
		$hppd = floatval($dbData[0]['hppd']);
		$thresholdHigh = floatval($dbData[0]['thresholdHigh']);
		$thresholdLow = floatval($dbData[0]['thresholdLow']);
		
		$useGrid = intval($dbData[0]['useGrid']);
		$deptId = intval($dbData[0]['deptId']);
		$dataId = intval($dbData[0]['id']);
		//echo 'new dataId ' . $dataId;
		$trackdc = $dbData[0]['trackdc'];
		$trackdata = intval($dbData[0]['trackdata']);
		$currentDischarges = 0;
		$dayDate = $dbData[0]['dayDate'];
		$censusShift = $dbData[0]['censusShift'];
		$inshiftProd = intval($dbData[0]['inshiftProd']);
		$viewPage = $dbData[0]['viewPage'];
		$prodUpdate = $dbData[0]['nproductivity'];
		$escalations = $dbData[0]['escalations'];
		$user = intval($dbData[0]['userId']);
		$dep = $dbData[0]['dept'];
		$countcharge = intval($dbData[0]['countcharge']);
		$rnCount = intval($dbData[0]['rnCount']);
		$rn1Count = intval($dbData[0]['rn1Count']);
		$rn2Count = intval($dbData[0]['rn2Count']);
		$roundrn = intval($dbData[0]['roundrn']);
		$minRN = intval($dbData[0]['minRN']);
		$value = floatval($dbData[0]['value']);
		
		$staff1 = intval($dbData[0]['staff1']);
		$staff2 = intval($dbData[0]['staff2']);
		$staff3 = intval($dbData[0]['staff3']);
		$staff4 = intval($dbData[0]['staff4']);
		$staff5 = intval($dbData[0]['staff5']);
		$staff6 = intval($dbData[0]['staff6']);
		$staff7 = intval($dbData[0]['staff7']);
		$staff8 = intval($dbData[0]['staff8']);
		$staff9 = intval($dbData[0]['staff9']);
		$staff10 = intval($dbData[0]['staff10']);
		
		if(intval($dbData[0]['shifthrs'])==0){
		$shiftCount = intval($dbData[0]['shiftCount']);
		}else{
		$shiftCount = 24 / floatval($dbData[0]['shifthrs']);	
		}
		
		$entered = $dbData[0]['entered'];
		$adjustment = floatval($dbData[0]['adjustment']);
		$recordId = $dbData[0]['id'];
		$prodMeasure = intval($dbData[0]['prodMeasure']);
		//$minRN = intval($dbData[0]['minRN']);
		$churnMeasure = intval($dbData[0]['churn']);
		$other1Calc = floatval($dbData[0]['other1Calc']);
		$other2Calc = floatval($dbData[0]['other2Calc']);
		$other3Calc = floatval($dbData[0]['other3Calc']);
		$secCalc = floatval($dbData[0]['secCalc']);
		$techCalc = floatval($dbData[0]['techCalc']);
		$los = floatval($dbData[0]['los']);
		$nurse1Variance = intval($dbData[0]['nurse1Variance']);
		$censusTotal = intval($dbData[0]['censusTotal']);
		$procedureCount = floatval($dbData[0]['procedureCount']);
		$actualWHP = floatval($dbData[0]['actualWHP']);
		$displayProd = intval($dbData[0]['rnVariance']);
		$showProd = intval($dbData[0]['showProdWHP']);
		$prodnurseCount = ((floatval($chargecount) * $countcharge) + (floatval($nursecount) * $rnCount) + (floatval($nurse1count) * $rn1Count) + (floatval($nurse2count) * $rn2Count));
		
		
		if($prodnurseCount==1 && $actualvariance>0){
		$nvariance=0;
		}else if($roundrn==1){
		$nvariance = round($actualvariance,0);
		}else if($roundrn==2){
		$nvariance = floor($actualvariance);
		}else if($roundrn==3){
		$nvariance = ceil($actualvariance);
		}else{
		$nvariance = round($actualvariance,2);
		}
		
		
				
		if($minRN>0){
		$adjustedVar = $prodnurseCount - $minRN;
		}else{
		$adjustedVar = 0;
		}
		
		
		if($adjustedVar < $nvariance && $minRN>0){
		$minAdjusted = 1;
		$nvarAdjusted = $adjustedVar;
		$nvariance = $adjustedVar;
		$newVarnote = " (*Adjusted for Core Staffing) ";
		}else{
		$minAdjusted = 0;
		$nvarAdjusted = 0.0;
		$newVarnote = '';		
		}
		
		if($shiftCount==0){
		$visitsToday = 0;
		}else{
		$visitsToday = intval($trackVisits) / $shiftCount;
		}
		$typetrack1 = intval($dbData[0]['typetrack1']);
		$typetrack2 = intval($dbData[0]['typetrack2']);
		$typetrack3 = intval($dbData[0]['typetrack3']);
		$typetrack4 = intval($dbData[0]['typetrack4']);
		$submitCount1 = intval($dbData[0]['submitCount']);
		$submitCount = $submitCount1 + 1;
		$rnVariance1 = intval($dbData[0]['rnVariance1']);
		$useEpic = intval($dbData[0]['showEpic']);
		$shiftOrder = intval($dbData[0]['shiftOrder']);
		if($user==0){
			$rnVarianceFirst = $actualvariance;
		}else{
			$rnVarianceFirst = $rnVariance1;
		}
		$prodCalc = intval($dbData[0]['productivityPosNeg']);	
		$rnThresholdHigh = floatval($dbData[0]['rnThreshold']);
		$rnThresholdLow = $rnThresholdHigh * -1;
		$gThresholdHigh = floatval($dbData[0]['gThreshold']);
		$gThresholdLow = $gThresholdHigh * -1;
		$oneto1Acuity = $dbData[0]['oneto1Acuity'];
		$oneto2Acuity = $dbData[0]['oneto2Acuity'];
		$oneto3Acuity = $dbData[0]['oneto3Acuity'];
		$oneto4Acuity = $dbData[0]['oneto4Acuity'];
		$oneto5Acuity = $dbData[0]['oneto5Acuity'];
		$oneto6Acuity = $dbData[0]['oneto6Acuity'];
		$oneto1Count = 0;
		$oneto2Count = 0;
		$oneto3Count = 0;
		$oneto4Count = 0;
		$oneto5Count = 0;
		$oneto6Count = 0;
		$twoto1Count = 0;
		$threeto1Count = 0;
		$oneto3p5Count = 0;
		$oneto4p5Count = 0;
		$oneto5p5Count = 0;
		
		$aproductivity2 = 0;
		$aproductivity = 0;
		$lproductivity = 0;
		//$actualWHP = 0;
		$avgWage = floatval($dbData[0]['avgWage']);
		$actionPlan = $dbData[0]['actionPlan'];
		$ptTotal2 = intval($dbData[0]['ptTotal2']);
		$ptTotal3 = intval($dbData[0]['ptTotal3']);
		$ptTotal4 = intval($dbData[0]['ptTotal4']);
		

		switch ($oneto1Acuity) {
		case "1":
			$oneto1Count = $oneto1Count + intval($oneto1);
			break;
		case "1.0":
			$oneto1Count = $oneto1Count + intval($oneto1);
			break;
		case "1.00":
			$oneto1Count = $oneto1Count + intval($oneto1);
			break;
		case "2":
			$oneto2Count = $oneto2Count + intval($oneto1);
			break;
		case "2.0":
			$oneto2Count = $oneto2Count + intval($oneto1);
			break;
		case "2.00":
			$oneto2Count = $oneto2Count + intval($oneto1);
			break;
		case "3":
			$oneto3Count = $oneto3Count + intval($oneto1);
			break;
		case "3.0":
			$oneto3Count = $oneto3Count + intval($oneto1);
			break;
		case "3.00":
			$oneto3Count = $oneto3Count + intval($oneto1);
			break;
		case "4":
			$oneto4Count = $oneto4Count + intval($oneto1);
			break;
		case "4.0":
			$oneto4Count = $oneto4Count + intval($oneto1);
			break;
		case "4.00":
			$oneto4Count = $oneto4Count + intval($oneto1);
			break;
		case "5":
			$oneto5Count = $oneto5Count + intval($oneto1);
			break;
		case "5.0":
			$oneto5Count = $oneto5Count + intval($oneto1);
			break;
		case "5.00":
			$oneto5Count = $oneto5Count + intval($oneto1);
			break;
		case "6":
			$oneto6Count = $oneto6Count + intval($oneto1);
			break;
		case "6.0":
			$oneto6Count = $oneto6Count + intval($oneto1);
			break;
		case "6.00":
			$oneto6Count = $oneto6Count + intval($oneto1);
			break;
		case ".5":
			$twoto1Count = $twoto1Count + intval($oneto1);
			break;
		case "0.5":
			$twoto1Count = $twoto1Count + intval($oneto1);
			break;
		case ".50":
			$twoto1Count = $twoto1Count + intval($oneto1);
			break;
		case "0.50":
			$twoto1Count = $twoto1Count + intval($oneto1);
			break;
		case ".33":
			$threeto1Count = $threeto1Count + intval($oneto1);
			break;
		case "0.33":
			$threeto1Count = $threeto1Count + intval($oneto1);
			break;
		case "3.5":
			$oneto3p5Count = $oneto3p5Count + intval($oneto1);
			break;
		case "3.50":
			$oneto3p5Count = $oneto3p5Count + intval($oneto1);
			break;
		case "4.5":
			$oneto4p5Count = $oneto4p5Count + intval($oneto1);
			break;
		case "4.50":
			$oneto4p5Count = $oneto4p5Count + intval($oneto1);
			break;
		case "5.5":
			$oneto5p5Count = $oneto5p5Count + intval($oneto1);
			break;
		case "5.50":
			$oneto5p5Count = $oneto5p5Count + intval($oneto1);
			break;
		default:
			//nothing;
		}
		
		switch ($oneto2Acuity) {
		case "1":
			$oneto1Count = $oneto1Count + intval($oneto2);
			break;
		case "1.0":
			$oneto1Count = $oneto1Count + intval($oneto2);
			break;
		case "1.00":
			$oneto1Count = $oneto1Count + intval($oneto2);
			break;
		case "2":
			$oneto2Count = $oneto2Count + intval($oneto2);
			break;
		case "2.0":
			$oneto2Count = $oneto2Count + intval($oneto2);
			break;
		case "2.00":
			$oneto2Count = $oneto2Count + intval($oneto2);
			break;
		case "3":
			$oneto3Count = $oneto3Count + intval($oneto2);
			break;
		case "3.0":
			$oneto3Count = $oneto3Count + intval($oneto2);
			break;
		case "3.00":
			$oneto3Count = $oneto3Count + intval($oneto2);
			break;
		case "4":
			$oneto4Count = $oneto4Count + intval($oneto2);
			break;
		case "4.0":
			$oneto4Count = $oneto4Count + intval($oneto2);
			break;
		case "4.00":
			$oneto4Count = $oneto4Count + intval($oneto2);
			break;
		case "5":
			$oneto5Count = $oneto5Count + intval($oneto2);
			break;
		case "5.0":
			$oneto5Count = $oneto5Count + intval($oneto2);
			break;
		case "5.00":
			$oneto5Count = $oneto5Count + intval($oneto2);
			break;
		case "6":
			$oneto6Count = $oneto6Count + intval($oneto2);
			break;
		case "6.0":
			$oneto6Count = $oneto6Count + intval($oneto2);
			break;
		case "6.00":
			$oneto6Count = $oneto6Count + intval($oneto2);
			break;
		case ".5":
			$twoto1Count = $twoto1Count + intval($oneto2);
			break;
		case "0.5":
			$twoto1Count = $twoto1Count + intval($oneto2);
			break;
		case ".50":
			$twoto1Count = $twoto1Count + intval($oneto2);
			break;
		case "0.50":
			$twoto1Count = $twoto1Count + intval($oneto2);
			break;
		case ".33":
			$threeto1Count = $threeto1Count + intval($oneto2);
			break;
		case "0.33":
			$threeto1Count = $threeto1Count + intval($oneto2);
			break;
		case "3.5":
			$oneto3p5Count = $oneto3p5Count + intval($oneto2);
			break;
		case "3.50":
			$oneto3p5Count = $oneto3p5Count + intval($oneto2);
			break;
		case "4.5":
			$oneto4p5Count = $oneto4p5Count + intval($oneto2);
			break;
		case "4.50":
			$oneto4p5Count = $oneto4p5Count + intval($oneto2);
			break;
		case "5.5":
			$oneto5p5Count = $oneto5p5Count + intval($oneto2);
			break;
		case "5.50":
			$oneto5p5Count = $oneto5p5Count + intval($oneto2);
			break;
		default:
			//nothing;
		}
		
		switch ($oneto3Acuity) {
		case "1":
			$oneto1Count = $oneto1Count + intval($high);
			break;
		case "1.0":
			$oneto1Count = $oneto1Count + intval($high);
			break;
		case "1.00":
			$oneto1Count = $oneto1Count + intval($high);
			break;
		case "2":
			$oneto2Count = $oneto2Count + intval($high);
			break;
		case "2.0":
			$oneto2Count = $oneto2Count + intval($high);
			break;
		case "2.00":
			$oneto2Count = $oneto2Count + intval($high);
			break;
		case "3":
			$oneto3Count = $oneto3Count + intval($high);
			break;
		case "3.0":
			$oneto3Count = $oneto3Count + intval($high);
			break;
		case "3.00":
			$oneto3Count = $oneto3Count + intval($high);
			break;
		case "4":
			$oneto4Count = $oneto4Count + intval($high);
			break;
		case "4.0":
			$oneto4Count = $oneto4Count + intval($high);
			break;
		case "4.00":
			$oneto4Count = $oneto4Count + intval($high);
			break;
		case "5":
			$oneto5Count = $oneto5Count + intval($high);
			break;
		case "5.0":
			$oneto5Count = $oneto5Count + intval($high);
			break;
		case "5.00":
			$oneto5Count = $oneto5Count + intval($high);
			break;
		case "6":
			$oneto6Count = $oneto6Count + intval($high);
			break;
		case "6.0":
			$oneto6Count = $oneto6Count + intval($high);
			break;
		case "6.00":
			$oneto6Count = $oneto6Count + intval($high);
			break;
		case ".5":
			$twoto1Count = $twoto1Count + intval($high);
			break;
		case "0.5":
			$twoto1Count = $twoto1Count + intval($high);
			break;
		case ".50":
			$twoto1Count = $twoto1Count + intval($high);
			break;
		case "0.50":
			$twoto1Count = $twoto1Count + intval($high);
			break;
		case ".33":
			$threeto1Count = $threeto1Count + intval($high);
			break;
		case "0.33":
			$threeto1Count = $threeto1Count + intval($high);
			break;
		case "3.5":
			$oneto3p5Count = $oneto3p5Count + intval($high);
			break;
		case "3.50":
			$oneto3p5Count = $oneto3p5Count + intval($high);
			break;
		case "4.5":
			$oneto4p5Count = $oneto4p5Count + intval($high);
			break;
		case "4.50":
			$oneto4p5Count = $oneto4p5Count + intval($high);
			break;
		case "5.5":
			$oneto5p5Count = $oneto5p5Count + intval($high);
			break;
		case "5.50":
			$oneto5p5Count = $oneto5p5Count + intval($high);
			break;
		default:
			//nothing;
		}
		
		switch ($oneto4Acuity) {
		case "1":
			$oneto1Count = $oneto1Count + intval($med);
			break;
		case "1.0":
			$oneto1Count = $oneto1Count + intval($med);
			break;
		case "1.00":
			$oneto1Count = $oneto1Count + intval($med);
			break;
		case "2":
			$oneto2Count = $oneto2Count + intval($med);
			break;
		case "2.0":
			$oneto2Count = $oneto2Count + intval($med);
			break;
		case "2.00":
			$oneto2Count = $oneto2Count + intval($med);
			break;
		case "3":
			$oneto3Count = $oneto3Count + intval($med);
			break;
		case "3.0":
			$oneto3Count = $oneto3Count + intval($med);
			break;
		case "3.00":
			$oneto3Count = $oneto3Count + intval($med);
			break;
		case "4":
			$oneto4Count = $oneto4Count + intval($med);
			break;
		case "4.0":
			$oneto4Count = $oneto4Count + intval($med);
			break;
		case "4.00":
			$oneto4Count = $oneto4Count + intval($med);
			break;
		case "5":
			$oneto5Count = $oneto5Count + intval($med);
			break;
		case "5.0":
			$oneto5Count = $oneto5Count + intval($med);
			break;
		case "5.00":
			$oneto5Count = $oneto5Count + intval($med);
			break;
		case "6":
			$oneto6Count = $oneto6Count + intval($med);
			break;
		case "6.0":
			$oneto6Count = $oneto6Count + intval($med);
			break;
		case "6.00":
			$oneto6Count = $oneto6Count + intval($med);
			break;
		case ".5":
			$twoto1Count = $twoto1Count + intval($med);
			break;
		case "0.5":
			$twoto1Count = $twoto1Count + intval($med);
			break;
		case ".50":
			$twoto1Count = $twoto1Count + intval($med);
			break;
		case "0.50":
			$twoto1Count = $twoto1Count + intval($med);
			break;
		case ".33":
			$threeto1Count = $threeto1Count + intval($med);
			break;
		case "0.33":
			$threeto1Count = $threeto1Count + intval($med);
			break;
		case "3.5":
			$oneto3p5Count = $oneto3p5Count + intval($med);
			break;
		case "3.50":
			$oneto3p5Count = $oneto3p5Count + intval($med);
			break;
		case "4.5":
			$oneto4p5Count = $oneto4p5Count + intval($med);
			break;
		case "4.50":
			$oneto4p5Count = $oneto4p5Count + intval($med);
			break;
		case "5.5":
			$oneto5p5Count = $oneto5p5Count + intval($med);
			break;
		case "5.50":
			$oneto5p5Count = $oneto5p5Count + intval($med);
			break;
		default:
			//nothing;
		}
		
		switch ($oneto5Acuity) {
		case "1":
			$oneto1Count = $oneto1Count + intval($low);
			break;
		case "1.0":
			$oneto1Count = $oneto1Count + intval($low);
			break;
		case "1.00":
			$oneto1Count = $oneto1Count + intval($low);
			break;
		case "2":
			$oneto2Count = $oneto2Count + intval($low);
			break;
		case "2.0":
			$oneto2Count = $oneto2Count + intval($low);
			break;
		case "2.00":
			$oneto2Count = $oneto2Count + intval($low);
			break;
		case "3":
			$oneto3Count = $oneto3Count + intval($low);
			break;
		case "3.0":
			$oneto3Count = $oneto3Count + intval($low);
			break;
		case "3.00":
			$oneto3Count = $oneto3Count + intval($low);
			break;
		case "4":
			$oneto4Count = $oneto4Count + intval($low);
			break;
		case "4.0":
			$oneto4Count = $oneto4Count + intval($low);
			break;
		case "4.00":
			$oneto4Count = $oneto4Count + intval($low);
			break;
		case "5":
			$oneto5Count = $oneto5Count + intval($low);
			break;
		case "5.0":
			$oneto5Count = $oneto5Count + intval($low);
			break;
		case "5.00":
			$oneto5Count = $oneto5Count + intval($low);
			break;
		case "6":
			$oneto6Count = $oneto6Count + intval($low);
			break;
		case "6.0":
			$oneto6Count = $oneto6Count + intval($low);
			break;
		case "6.00":
			$oneto6Count = $oneto6Count + intval($low);
			break;
		case ".5":
			$twoto1Count = $twoto1Count + intval($low);
			break;
		case "0.5":
			$twoto1Count = $twoto1Count + intval($low);
			break;
		case ".50":
			$twoto1Count = $twoto1Count + intval($low);
			break;
		case "0.50":
			$twoto1Count = $twoto1Count + intval($low);
			break;
		case ".33":
			$threeto1Count = $threeto1Count + intval($low);
			break;
		case "0.33":
			$threeto1Count = $threeto1Count + intval($low);
			break;
		case "3.5":
			$oneto3p5Count = $oneto3p5Count + intval($low);
			break;
		case "3.50":
			$oneto3p5Count = $oneto3p5Count + intval($low);
			break;
		case "4.5":
			$oneto4p5Count = $oneto4p5Count + intval($low);
			break;
		case "4.50":
			$oneto4p5Count = $oneto4p5Count + intval($low);
			break;
		case "5.5":
			$oneto5p5Count = $oneto5p5Count + intval($low);
			break;
		case "5.50":
			$oneto5p5Count = $oneto5p5Count + intval($low);
			break;
		default:
			//nothing;
		}
		
		switch ($oneto6Acuity) {
		case "1":
			$oneto1Count = $oneto1Count + intval($oneto6);
			break;
		case "1.0":
			$oneto1Count = $oneto1Count + intval($oneto6);
			break;
		case "1.00":
			$oneto1Count = $oneto1Count + intval($oneto6);
			break;
		case "2":
			$oneto2Count = $oneto2Count + intval($oneto6);
			break;
		case "2.0":
			$oneto2Count = $oneto2Count + intval($oneto6);
			break;
		case "2.00":
			$oneto2Count = $oneto2Count + intval($oneto6);
			break;
		case "3":
			$oneto3Count = $oneto3Count + intval($oneto6);
			break;
		case "3.0":
			$oneto3Count = $oneto3Count + intval($oneto6);
			break;
		case "3.00":
			$oneto3Count = $oneto3Count + intval($oneto6);
			break;
		case "4":
			$oneto4Count = $oneto4Count + intval($oneto6);
			break;
		case "4.0":
			$oneto4Count = $oneto4Count + intval($oneto6);
			break;
		case "4.00":
			$oneto4Count = $oneto4Count + intval($oneto6);
			break;
		case "5":
			$oneto5Count = $oneto5Count + intval($oneto6);
			break;
		case "5.0":
			$oneto5Count = $oneto5Count + intval($oneto6);
			break;
		case "5.00":
			$oneto5Count = $oneto5Count + intval($oneto6);
			break;
		case "6":
			$oneto6Count = $oneto6Count + intval($oneto6);
			break;
		case "6.0":
			$oneto6Count = $oneto6Count + intval($oneto6);
			break;
		case "6.00":
			$oneto6Count = $oneto6Count + intval($oneto6);
			break;
		case ".5":
			$twoto1Count = $twoto1Count + intval($oneto6);
			break;
		case "0.5":
			$twoto1Count = $twoto1Count + intval($oneto6);
			break;
		case ".50":
			$twoto1Count = $twoto1Count + intval($oneto6);
			break;
		case "0.50":
			$twoto1Count = $twoto1Count + intval($oneto6);
			break;
		case ".33":
			$threeto1Count = $threeto1Count + intval($oneto6);
			break;
		case "0.33":
			$threeto1Count = $threeto1Count + intval($oneto6);
			break;
		case "3.5":
			$oneto3p5Count = $oneto3p5Count + intval($oneto6);
			break;
		case "3.50":
			$oneto3p5Count = $oneto3p5Count + intval($oneto6);
			break;
		case "4.5":
			$oneto4p5Count = $oneto4p5Count + intval($oneto6);
			break;
		case "4.50":
			$oneto4p5Count = $oneto4p5Count + intval($oneto6);
			break;
		case "5.5":
			$oneto5p5Count = $oneto5p5Count + intval($oneto6);
			break;
		case "5.50":
			$oneto5p5Count = $oneto5p5Count + intval($oneto6);
			break;
		default:
			//nothing;
		}
		

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
			
			$prodnurseReqd = $prodnurseCount - $gridrnvariance;
			
			if($useGrid==1 && $gridCount>0 && $prodnurseReqd>0){
				$gridReqd = $gridCount - $gvariance;
				
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
			$nurseprodReqd = $prodnurseCount - $actualvariance;
					if($nurseprodReqd>0){
					$nproductivity = (1-(($prodnurseCount - $nurseprodReqd) / $nurseprodReqd)) * 100;
					//$nproductivity = ((($nurseprodReqd - $prodnurseCount) / $prodnurseCount) + 1) * 100;
					}else{
					$nproductivity = 0.0;
					}
			
			if($shiftCount>0){
			$antenurses = (((floatval($chargecount) * $staff1) + (floatval($nursecount) * $staff2) + (intval($nurse1count) * $staff3) + (intval($nurse2count) * $staff4) + (intval($other1count) * $other1Calc * $staff5) + (intval($other2count) * $other2Calc * $staff6) + (intval($other3count) * $other3Calc * $staff7) + (intval($seccount) * $secCalc * $staff8) + (intval($sittercount) * $staff9) + (intval($techcount) * $techCalc * $staff10)) * 24 / $shiftCount) + $nurseAdd;
			$antehours = ($patienttotal * $hppd) / $shiftCount;
			}else{
			$antenurses =0;
			$antehours = 0;
			}
			if($antenurses>0 && $patienttotal>0 && $shiftCount>0){
			$actualWHP = $antenurses / ($patienttotal / $shiftCount);
			}else{
			$actualWHP = 0;	
			}
			
			if(intval($antehours)>0 && $prodMeasure==1){
			$aprod = (1-(($antenurses - $antehours) / $antehours)) * 100;
			//$aprod = ((($antehours - $antenurses) / $antehours) + 1) * 100;
			$lproductivity = round($aprod,2);
			}else{
			$lproductivity = 0.0;	
			}
			
			if($prodCalc!=1){
			$hrsVariance = round((($patienttotal * $hppd / $shiftCount) - $antenurses),3);
			$hrsbudgetVar = $hrsVariance * $avgWage;
			$wageCalc = 1;
			$gridhrsVariance = round((($gvariance * 24) / $shiftCount) * -1,1);
			$gridbudgetVar = $gridhrsVariance * $avgWage * $wageCalc;
			$gridrnhrsVariance = round((($gridrnvariance * 24) / $shiftCount) * -1,1);
			$gridrnbudgetVar = $gridrnhrsVariance * $avgWage;
				
			$rnhrsVariance = round((($actualvariance * 24) / $shiftCount) * -1,1);
			$rnbudgetVar = $rnhrsVariance * $avgWage;
			
			}else{
			$wageCalc = -1;
			$hrsVariance = round((($patienttotal * $hppd / $shiftCount) - $antenurses) * -1,3);
			$hrsbudgetVar = $hrsVariance * $avgWage * $wageCalc;
				
			$gridhrsVariance = round((($gvariance * 24) / $shiftCount),1);
			$gridbudgetVar = $gridhrsVariance * $avgWage * -1;
			$gridrnhrsVariance = round((($gridrnvariance * 24) / $shiftCount),1);
			$gridrnbudgetVar = $gridrnhrsVariance * $avgWage * -1;
				
			$rnhrsVariance = round((($actualvariance * 24) / $shiftCount),1);
			$rnbudgetVar = $rnhrsVariance * $avgWage * -1;
			}				
				
			if(intval($trackVisits)>0){
				$gridactualWHP = $antenurses / (intval($trackVisits) / $shiftCount);
			}else{
				$gridactualWHP = 0.0;
			}
			
			if($prodMeasure!=1){	
			$hrsVariance = 0;
			$hrsbudgetVar = 0;
			}	
			
			//if($minRN>0 && intval($prodnurseCount)<=($minRN +1) && intval($prodnurseCount)>=$minRN && $nvariance>0){
				//$nvariance = $prodnurseCount - $minRN;
			//}
			
			
				
			if(intval($displayProd)==1){
				$aproductivity = $lproductivity;
				$avariance = $hrsVariance;
				$ahrsVariance = $hrsVariance;
			}else if(intval($displayProd)==0){
				$aproductivity = $nproductivity;
				$avariance = $actualvariance;
				$ahrsVariance = $rnhrsVariance;
			}else if(intval($displayProd)==2){
				$aproductivity = $gproductivity;
				$avariance = $gvariance;
				$ahrsVariance = $gridhrsVariance;
			}else if(intval($displayProd)==3){
				$aproductivity = $gridrnproductivity;
				$avariance = $gridrnvariance;
				$ahrsVariance = $gridrnhrsVariance;
			}else if(intval($displayProd)==5){
				$aproductivity = $nproductivity;
				$avariance = $actualvariance;
				$ahrsVariance = $rnhrsVariance;
			}else{
				$aproductivity = $nproductivity;
				$avariance = $actualvariance;
				$ahrsVariance = $rnhrsVariance;			
			}
			
				$budgetVar = $hrsbudgetVar;
				$rbudgetVar = $rnbudgetVar;
				$gbudgetVar = $gridbudgetVar;
				$hrsVariance = $hrsVariance;
				$rhrsVariance = $rnhrsVariance;
				$ghrsVariance = $gridhrsVariance;
			/*
			if($prodMeasure==1 && intval($shift)==intval($censusShift)){
				$updateProd = Config::get('db')->get_results("select n.*, (Select (Sum((`chargecount`+`techcount`+`seccount`+`sittercount`+`antecount`+`customNurse`+`customNurse2`+`otherNurse1`+`otherNurse2`+`otherNurse3`)*24/{$shiftCount})+Sum(`addResourceHrs`))/{$patienttotal} from `productiveNewData` where `dayDate`=DATE_SUB('{$day}', INTERVAL 1 DAY) AND `deptId`={$deptId} and `userId`>0 group by `deptId`) as hrsSum, (Select count(`userId`) from `productiveNewData` where `dayDate`=DATE_SUB('{$day}', INTERVAL 1 DAY) AND `deptId`={$deptId} and `userId`>0 group by `deptId`) as rptCount, h.hppd from `productiveNewData` n left join `ProductiveDept` as h on h.accountId = n.accountId and h.id=n.deptId where n.dayDate=DATE_SUB('{$day}', INTERVAL 1 DAY) AND n.deptId={$deptId} and n.userId>0 group by n.shift order by n.shift DESC");
								
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
					$rptCount = intval($updateShift['rptCount']);
					
					if($rptCount == $shiftCount){
					$hppd2 = ROUND(floatval($updateShift['hrsSum']),2);
					}else{
					$hppd2 = 0.0;	
					}
					
					$updateantehours = ($patienttotal * $updatehppd) / $shiftCount;
					$updateantenurses = (($updateantecount + $updatechargecount + $updatenurse1 + $updatecustom2 + $updateother1 + $updateother2 + $updateother3 + $updateseccount + $updatesitter + $updatetechcount) * 24 / $shiftCount) + $updatenurseAdd;
					
					if($updateantehours >0){
					$updateaprod = ((($updateantehours - $updateantenurses) / $updateantehours) + 1) * 100;
					$updatelproductivity = round($updateaprod,2);
					}else{
					$updatelproductivity = 0.0;
					}
					
					$updatehrsVariance = round((($patienttotal * $updatehppd / $shiftCount) - $updateantenurses),3);
					$updatebudgetVar = $updatehrsVariance * $avgWage * $wageCalc;
						
					$updatedata = array(
						//'lproductivity' => $updatelproductivity,
						'hrsVariance' => $updatehrsVariance,
						'censusTotal' => $patienttotal,
						'budgetVar'=>$updatebudgetVar,
						'hppd2'=>$hppd2
					);
					$updateWhere = array('id' => $updatedataId);
					
					$newProd = Config::get('db')->update('productiveNewData',$updatedata,$updateWhere);
					}										
								
			}else if($prodMeasure==4 && intval($trackVisits)>0 && intval($shift)==intval($censusShift)){
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
					$updatebudgetVar = $updatehrsVariance * $avgWage * $wageCalc;
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
			
			*/
			
			//$ahrsvariance=0;
					
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
			
				
			
			if($isnote==false && ($displayProd ==0 && $nvariance <= $rnThresholdLow) || (($displayProd ==3 || $showProd==5) && $gridrnvariance <= $rnThresholdLow)){
				$varType = "under-staffed";
				$varianceType = "Your Staffing Report shows a possible Under-Staffed RN Variance for " . $dep . ".  Please add a note with an explanation or action plan to correct the variance.  These notes are critical in the successful collaboration of staffing needs.";
				$staffing = true;
				$staffingTitle = "Variance Action Plan";
			}else if($isnote==false && (($displayProd ==2 || $showProd==6) && $gvariance <= $gThresholdLow)){
				$varType = "under-staffed";
				$varianceType = "Your Staffing Report shows a possible Under-Staffed Grid Variance for " . $dep . ".  Please add a note with an explanation or action plan to correct the variance.  These notes are critical in the successful collaboration of staffing needs.";
				$staffing = true;
				$staffingTitle = "Variance Action Plan";
			}else if($isnote==false && ($displayProd ==0 && $nvariance >= $rnThresholdHigh) || (($displayProd ==3 || $showProd==5) && $gridrnvariance >= $rnThresholdHigh)){
				$varType = "under-staffed";
				$varianceType = "Your Staffing Report shows a possible Over-Staffed RN Variance for " . $dep . ".  Please add a note with an explanation or action plan to correct the variance.  These notes are critical in the successful collaboration of staffing needs.";
				$staffing = true;
				$staffingTitle = "Variance Action Plan";
			}else if($isnote==false && (($displayProd ==2 || $showProd==6) && $gvariance >= $gThresholdHigh)){
				$varType = "under-staffed";
				$varianceType = "Your Staffing Report shows a possible Over-Staffed Grid Variance for " . $dep . ".  Please add a note with an explanation or action plan to correct the variance.  These notes are critical in the successful collaboration of staffing needs.";
				$staffing = true;
				$staffingTitle = "Variance Action Plan";
			}else if($submitCount>1){
				$varianceType = '';
				$staffing = false;
				$staffingTitle = "Staffing Comments";
				$note = str_replace("Note Skipped","",$note);
			}else{
				$varianceType = '';
				$staffing = false;
				$staffingTitle = "Staffing Comments";
			}
			
			if($updated !=false && intval($unscheduled)==0){
			//$now = time()-21600;		
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
				'extoneto6' => $extoneto6,
				'checkBox' => $checkBox,
				'oneto7' => $oneto7,
				'oneto8' => $oneto8,
				'oneto9' => $oneto9,
				'oneto10' => $oneto10,
				'oneto11' => $oneto11,
				'oneto12' => $oneto12,
				'avariance' => $actualvariance,
				'nvariance' => $nvariance,
				'nvariance1' => $nvariance1,
				'nvariance2' => $nvariance2,
				'nvariance3' => $nvariance3,
				'atotal' => $patienttotal,
				'patientCount2' => $patienttotal2,
				'patientCount3' => $patienttotal3,
				'patientCount4' => $patienttotal4,
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
				'note' => Config::get('db')->filter($note) . $newVarnote,
				'hrsVariance' => $hrsVariance,
				'ahrsVariance' => $ahrsVariance,
				'ghrsVariance' => $ghrsVariance,
				'rhrsVariance' => $rhrsVariance,
				'actualWHP' => $actualWHP,
				'activeRecord' =>1,
				'budgetVar' =>$budgetVar,
				'gbudgetVar' =>$gbudgetVar,
				'rbudgetVar' =>$rbudgetVar,
                'track1' => $track1,
				'track2' => $track2,
				'notetrack1' => addslashes($trackNote1),
				'notetrack2' => addslashes($trackNote2),
				'track3' => $track3,
				'notetrack3' => addslashes($trackNote3),
				'notetrack4' => addslashes($trackNote4),
				'track4' => $track4,
				'track1Detail'=>$dbData[0]['track1Desc'],
				'track2Detail'=>$dbData[0]['track2Desc'],
				'track3Detail'=>$dbData[0]['track3Desc'],
				'track4Detail'=>$dbData[0]['track4Desc'],
				'submittedby' => $submittedby,
				'gvariance' => $gvariance,
				'actualWHP' => $actualWHP,
				'visitsSubmitted' => $trackVisits,
				'submitCount' => $submitCount,
				'gridrnvariance' => $gridrnvariance,
				'gridrnproductivity' => $gridrnproductivity,
				'gridrnbudgetVar' => $gridrnbudgetVar,
				'gridrnhrsVariance' => $gridrnhrsVariance,
				'rnVariance1' => $rnVarianceFirst,
				'rnAcuity' => $prodnurseCount,
				'epicScore' => $epicScore,
				'oneto1Count' => $oneto1Count,
				'oneto2Count' => $oneto2Count,
				'oneto3Count' => $oneto3Count,
				'oneto3p5Count' => $oneto3p5Count,
				'oneto4Count' => $oneto4Count,
				'oneto4p5Count' => $oneto4p5Count,
				'oneto5Count' => $oneto5Count,
				'oneto5p5Count' => $oneto5p5Count,
				'oneto6Count' => $oneto6Count,
				'twoto1Count' => $twoto1Count,
				'threeto1Count' => $threeto1Count,
				'epicOrig' => $epicScore,
				'grid1' => $grid1,
				'grid2' => $grid2,
				'grid3' => $grid3,
				'grid4' => $grid4,
				'grid5' => $grid5,
				'grid6' => $grid6,
				'grid7' => $grid7,
				'grid8' => $grid8,
				'grid9' => $grid9,
				'grid10' => $grid10,
				'gridvar1' => $gridvar1,
				'gridvar2' => $gridvar2,
				'gridvar3' => $gridvar3,
				'gridvar4' => $gridvar4,
				'gridvar5' => $gridvar5,
				'gridvar6' => $gridvar6,
				'gridvar7' => $gridvar7,
				'gridvar8' => $gridvar8,
				'gridvar9' => $gridvar9,
				'gridvar10' => $gridvar10,
				'ptRatio' =>$ptRatio,
				'minAdjusted' =>$minAdjusted,
				'nvarAdjusted' =>$nvarAdjusted,
				'nedocScore' =>$nedocs,
				'updatedby' =>$updatedby
            );
			$updateWhere = array('id' => $dataId);
            
			$newProd = Config::get('db')->update('productiveNewData',$data,$updateWhere);
			}else{
			$data = array(
                'chargecount' => $chargecount,
                'techcount' => $techcount,
				'seccount' => $seccount,
				'accountId' => $accountId,
				'deptId' => $deptId,
				'sittercount' => $sittercount,
				'antecount' => $nursecount,
				'dayDate' =>date("Y-m-d",$currentTime),
				'entered' =>date("Y-m-d H:i:s",$currentTime),
				'userId' => $userId,
				'oneto3' => $high,
				'oneto4' => $med,
                'oneto5' => $low,
				'oneto1' => $oneto1,
				'oneto2' => $oneto2,
                'oneto6' => $oneto6,
				'extoneto6' => $extoneto6,
				'checkBox' => $checkBox,
				'oneto7' => $oneto7,
				'oneto8' => $oneto8,
				'oneto9' => $oneto9,
				'oneto10' => $oneto10,
				'oneto11' => $oneto11,
				'oneto12' => $oneto12,
				'avariance' => $actualvariance,
				'nvariance' => $nvariance,
				'nvariance1' => $nvariance1,
				'nvariance2' => $nvariance2,
				'nvariance3' => $nvariance3,
				'atotal' => $patienttotal,
				'patientCount2' => $patienttotal2,
				'patientCount3' => $patienttotal3,
				'patientCount4' => $patienttotal4,
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
				'note' => Config::get('db')->filter($note),
				'hrsVariance' => $hrsVariance,
				'ahrsVariance' => $ahrsVariance,
				'ghrsVariance' => $ghrsVariance,
				'rhrsVariance' => $rhrsVariance,
				'actualWHP' => $actualWHP,
				'activeRecord' =>1,
				'budgetVar' =>$budgetVar,
				'gbudgetVar' =>$gbudgetVar,
				'rbudgetVar' =>$rbudgetVar,
                'track1' => $track1,
				'track2' => $track2,
				'notetrack1' => addslashes($trackNote1),
				'notetrack2' => addslashes($trackNote2),
				'track3' => $track3,
				'notetrack3' => addslashes($trackNote3),
				'notetrack4' => addslashes($trackNote4),
				'track4' => $track4,
				'track1Detail'=>$dbData[0]['track1Desc'],
				'track2Detail'=>$dbData[0]['track2Desc'],
				'track3Detail'=>$dbData[0]['track3Desc'],
				'track4Detail'=>$dbData[0]['track4Desc'],
				'submittedby' => $submittedby,
				'gvariance' => $gvariance,
				'actualWHP' => $actualWHP,
				'visitsSubmitted' => $trackVisits,
				'submitCount' => $submitCount,
				'gridrnvariance' => $gridrnvariance,
				'gridrnproductivity' => $gridrnproductivity,
				'gridrnbudgetVar' => $gridrnbudgetVar,
				'gridrnhrsVariance' => $gridrnhrsVariance,
				'rnVariance1' => $rnVarianceFirst,
				'rnAcuity' => $prodnurseCount,
				'epicScore' => $epicScore,
				'oneto1Count' => $oneto1Count,
				'oneto2Count' => $oneto2Count,
				'oneto3Count' => $oneto3Count,
				'oneto3p5Count' => $oneto3p5Count,
				'oneto4Count' => $oneto4Count,
				'oneto4p5Count' => $oneto4p5Count,
				'oneto5Count' => $oneto5Count,
				'oneto5p5Count' => $oneto5p5Count,
				'oneto6Count' => $oneto6Count,
				'twoto1Count' => $twoto1Count,
				'threeto1Count' => $threeto1Count,
				'epicOrig' => $epicScore,
				'grid1' => $grid1,
				'grid2' => $grid2,
				'grid3' => $grid3,
				'grid4' => $grid4,
				'grid5' => $grid5,
				'grid6' => $grid6,
				'grid7' => $grid7,
				'grid8' => $grid8,
				'grid9' => $grid9,
				'grid10' => $grid10,
				'gridvar1' => $gridvar1,
				'gridvar2' => $gridvar2,
				'gridvar3' => $gridvar3,
				'gridvar4' => $gridvar4,
				'gridvar5' => $gridvar5,
				'gridvar6' => $gridvar6,
				'gridvar7' => $gridvar7,
				'gridvar8' => $gridvar8,
				'gridvar9' => $gridvar9,
				'gridvar10' => $gridvar10,
				'ptRatio' =>$ptRatio,
				'minAdjusted' =>$minAdjusted,
				'nvarAdjusted' =>$nvarAdjusted,
				'nedocScore' =>$nedocs,
				'updatedby' =>$updatedby
            );
			//$updateWhere = array('id' => $dataId);
            
			$newProd = Config::get('db')->insert('productiveNewData',$data);	
			}
		
		if($dbData && intval($unscheduled)==1){
		$newdata = array(
                'dataId' => $dataId,
				'ptTrack1' => $oneto7,
				'trackDischarges' => 0,
				'ptTrack2' => $oneto8,
				'ptTrack3' => $oneto9,
				'ptTrack4' => $oneto10,
				'ptTrack5' => $oneto11,
				'ptTrack6' => $oneto12,
				'ptType1' => $dbData[0]['desc7'],
				'ptType2' => $dbData[0]['desc8'],
				'ptType3' => $dbData[0]['desc9'],
				'ptType4' => $dbData[0]['desc10'],
				'ptType5' => $dbData[0]['desc11'],
				'ptType6' => $dbData[0]['desc12'],
				'deptId' => $deptId,
				'dayDate' => $dayDate,
				'shift' => $shift,
				'dayTime' => $entered,
				'userId' => $userId,
				'track1' => $track1,
				'track2' => $track2,
				'track3' => $track3,
				'track4' => $track4,
				'trackNote1' => $trackNote1,
				'trackNote2' => $trackNote2,
				'trackNote3' => $trackNote3,
				'trackNote4' => $trackNote4,
				'trackType1'=>$dbData[0]['track1Desc'],
				'trackType2'=>$dbData[0]['track2Desc'],
				'trackType3'=>$dbData[0]['track3Desc'],
				'trackType4'=>$dbData[0]['track4Desc'],
				'posDesc1'=>$dbData[0]['chargeDesc'],
				'posDesc2'=>$dbData[0]['nurseDesc'],
				'posDesc3'=>$dbData[0]['nurse1Desc'],
				'posDesc4'=>$dbData[0]['nurse2Desc'],
				'posDesc5'=>$dbData[0]['techLabel'],
				'posDesc6'=>$dbData[0]['secLabel'],
				'posDesc7'=>$dbData[0]['other1Desc'],
				'posDesc8'=>$dbData[0]['sittersNEWDesc'],
				'posDesc9'=>$dbData[0]['other2Desc'],
				'posDesc10'=>$dbData[0]['other3Desc'],
				'posDesc11'=>'',
				'countDesc'=>$dbData[0]['patientTotalDesc'],
				'countDesc2'=>$dbData[0]['patientTotalDesc2'],
				'countDesc3'=>$dbData[0]['patientTotalDesc3'],
				'countDesc4'=>$dbData[0]['patientTotalDesc4'],
				'uosDesc1'=>'',
				'uosDesc2'=>'',
				'uosDesc3'=>'',
				'uosDesc4'=>'',
				'uosDesc5'=>''
            );
            
			$newData = Config::get('db')->insert('productiveTracking',$newdata);	
			
		}
		
		/*
		if($currentDischarges != $trackDischarges){
		$updateDischarges = updatedischarges($deptId,$dayDate,$trackDischarges);
		}
		*/
		
		if($submitCount==1){
		$updateMail = sendupdateEmail($dataId,$aMsg,$hrsVar);		
		}
		
				
		$dbAdmin = Config::get('db')->get_results("select n.*, d.roundrn, SUM(CASE WHEN u.role=11 THEN 1 ELSE 0 END) as countAdmin, u.role, u.houseAlerts, count(u.role) as countRole, u.id as textId, l.id as labelId, a.houseAlert, d.useGrid, d.active as dactive, IFNULL((Select `escAlerts` FROM `productiveDeptXref` where `deptId`=n.deptId AND `primaryUnit` < 12 AND `escAlerts`=1 LIMIT 0,1),0) as escAlerts, l.login as accountLogin, l.noreply, l.labelName, s.dayNight, u.alertTimes, u.mobile, u.txtPause, d.rnThreshold, IFNULL(x.textVariance,0) as textVariance, IFNULL(x.textAlerts,0) as textAlerts FROM `productiveNewData` n LEFT OUTER JOIN `productiveDeptXref` as x on x.deptId = n.deptId LEFT JOIN `ProductiveDept` as d on d.id = n.deptId LEFT OUTER JOIN `productiveUser` as u on u.id = x.userId LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `productiveAccount` as a on a.id = n.accountId LEFT JOIN `productiveLabel` as l on l.id = a.label where n.id={$dataId} and d.active>0 AND u.txtPause=0 and n.daydate = curdate() and u.active>=1 AND u.role>=5 and LENGTH(u.mobile)=10 group by u.mobile order by x.escAlerts DESC, u.txtPause ASC");
		
		
		//UNION
		//select n.*, l.id as labelId, d.active as dactive, l.login as accountLogin, l.noreply, l.labelName, s.dayNight, u.alertTimes, u.mobile, u.txtPause, d.rnThreshold, d.houseAlert, IFNULL(x.textAlerts,0) as textAlerts FROM `productiveNewData` n LEFT OUTER JOIN `productiveDeptXref` as x on x.deptId = n.deptId LEFT JOIN `ProductiveDept` as d on d.id = n.deptId LEFT OUTER JOIN `productiveUser` as u on u.accountId = n.accountId LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `productiveAccount` as a on a.id = n.accountId LEFT JOIN `productiveLabel` as l on l.id = a.label where n.id={$dataId} and d.active>0 AND u.txtactive>=1 and u.txtPause=0 and n.daydate = curdate() and u.active>=1 and u.txt>=1 and u.role=9 and u.mobile !='' AND d.rnThreshold !=0 AND d.houseAlert=1 AND (n.nvariance >= d.rnThreshold OR n.nvariance <= (d.rnThreshold * -1)) group by u.mobile");
			
		if($dbAdmin && $submitCount==1){
			$textAlerts = $dbAdmin[0]['textAlerts'];
			$escAlerts = $dbAdmin[0]['escAlerts'];
			$txtpause = $dbAdmin[0]['txtPause'];
			$noreply = $dbAdmin[0]['noreply'];
			$alertName = $dbAdmin[0]['labelName'];
			$roundrn = intval($dbAdmin[0]['roundrn']);
			$nedocscore = intval($dbAdmin[0]['nedocScore']);			
	
					
			$summaryStaff = array();
			$summaryStaff[] = array('lbl' => ($dbData[0]['chargeDesc'] ?: 'Charge'), 'val' => round($chargecount, 1));
			if(!empty($dbData[0]['nurse1Desc']) && $nurse1count > 0) $summaryStaff[] = array('lbl' => $dbData[0]['nurse1Desc'], 'val' => round($nurse1count, 1));
			if(!empty($dbData[0]['nurseDesc'])  && $nursecount > 0)  $summaryStaff[] = array('lbl' => $dbData[0]['nurseDesc'],  'val' => round($nursecount, 1));
			if(!empty($dbData[0]['nurse2Desc']) && $nurse2count > 0) $summaryStaff[] = array('lbl' => $dbData[0]['nurse2Desc'], 'val' => round($nurse2count, 1));
			if(!empty($dbData[0]['techLabel'])  && $techcount > 0)   $summaryStaff[] = array('lbl' => $dbData[0]['techLabel'],  'val' => round($techcount, 1));
			if(!empty($dbData[0]['secLabel'])   && $seccount > 0)    $summaryStaff[] = array('lbl' => $dbData[0]['secLabel'],   'val' => round($seccount, 1));
			if(!empty($dbData[0]['sittersNEWDesc']) && $sittercount > 0) $summaryStaff[] = array('lbl' => $dbData[0]['sittersNEWDesc'], 'val' => round($sittercount, 1));
			if(!empty($dbData[0]['other1Desc']) && $other1count > 0) $summaryStaff[] = array('lbl' => $dbData[0]['other1Desc'], 'val' => round($other1count, 1));
			if(!empty($dbData[0]['other2Desc']) && $other2count > 0) $summaryStaff[] = array('lbl' => $dbData[0]['other2Desc'], 'val' => round($other2count, 1));
			if(!empty($dbData[0]['other3Desc']) && $other3count > 0) $summaryStaff[] = array('lbl' => $dbData[0]['other3Desc'], 'val' => round($other3count, 1));
			$summary = array('dept' => $dep, 'nvariance' => $nvariance, 'patients' => $patienttotal, 'staff' => $summaryStaff);
						$var = array(
				'message'=>$staffing,
				'dataId'=>$dataId,
				'deptId'=>$deptId,
				'updated'=>$updated,
				'varianceType'=>$varianceType,
				'staffingTitle'=>$staffingTitle,
				'note'=>$note,
				'escalations'=>$escalations,
				'txtpause'=>$txtpause,
				'escAlerts'=>$escAlerts,
				'summary'=>$summary
				);
			$x=0;
			foreach ($dbAdmin as $dbText){
				$alert = $dbText['textAlerts'];
				$grid = intval($dbText['useGrid']);
				$houseAlerts = $dbText['houseAlerts'];
				$pause = $dbText['txtPause'];
				$dayNight = $dbText['dayNight'];
				$alertTimes = $dbText['alertTimes'];
				$mobile = $dbText['mobile'];
				$textId =  $dbText['textId'];
				$textRole =  intval($dbText['role']);
				$nvariance = floatval($dbText['nvariance']);
				$gvariance = floatval($dbText['gvariance']);
				
				$houseAlertHigh =  floatval($dbText['textVariance']);
				$houseAlertLow =  floatval($dbText['textVariance']) * -1;
				
				if($nedocscore>0){
					$newVar = "with a NEDOCS Score of " . $nedocscore;
				}else if($nvariance==0){
					$newVar = "with a RN variance of " . $nvariance;
				}else if($nvariance >= $houseAlertHigh){
					$newVar = "with a RN variance of +" . $nvariance;
				}else if($nvariance <= $houseAlertLow){
					$newVar = "with a RN variance of " . $nvariance;
				}else if($gvariance >= $houseAlertHigh){
					$newVar = "with a Staffing Grid Variance of +" . $gvariance;	
				}else if($gvariance <= $houseAlertLow){
					$newVar = "with a Staffing Grid Variance of " . $gvariance;	
				}else{
					$newVar = '';	
				}
						
				if(($nvariance >= $houseAlertHigh || $nvariance <= $houseAlertLow || ($gvariance >= $houseAlertHigh && $grid !=0 && $grid !=5) || ($gvariance <= $houseAlertLow && $grid !=0 && $grid !=5)) && intval($alert)==1 && $ptzero==1 && (intval($dayNight) == intval($alertTimes) || intval($alertTimes)==3) && !(intval($escAlerts)==1 && intval($escalations)==0)){
				
				//if((((intval($houseAlerts)==1 && $textRole>=9) && ($nvariance >= $houseAlertHigh || $nvariance <= $houseAlertLow || ($gvariance >= $houseAlertHigh && $grid !=0 && $grid !=5) || ($gvariance <= $houseAlertLow && $grid !=0 && $grid !=5))) || intval($alert)==1) && $ptzero==1 && (intval($dayNight) == intval($alertTimes) || intval($alertTimes)==3)){
				
				$pw = generateRandomString(10);
				$logix = $pw . $dbText['accountId'];
				$data = array('logix'=>$logix,'logixTime'=>date("Y-m-d"));
				$updateWhere = array('id' =>$textId);
				Config::get('db')->update('productiveUser',$data,$updateWhere);
				$staffToken = bin2hex(random_bytes(32));
				$staffExpires = date('Y-m-d H:i:s', strtotime('+12 hours'));
				Config::get('db')->insert('staffing_tokens', array('token'=>$staffToken,'user_id'=>$textId,'account_id'=>$dbText['accountId'],'expires_at'=>$staffExpires));
				$newLink = "https://www.productivern.com/manager-view.php?t=" . $staffToken;
				
				$x=1;						
				$message = $alertName . " Report Alert: A ".$prodTerm." Report has been submitted ". $newVar ." in ". $dep . ". " . $newLink;
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
				
				$coverage = checkUserCoverage($textId);
				$coverPause = ($coverage) ? $coverage['coverPauseSms'] : 0;
				if (!($coverage && $coverPause == 1)) {
					$messages[] = $xmlMsg;
					$numbers[] = "1" . $mobile;
					$keywords[] = $keyword2;
				}
				if ($coverage && $coverage['hasMobile'] && ($coverage['coverVarianceMin'] == 0 || abs($nvariance) >= $coverage['coverVarianceMin'])) {
					$covTok = bin2hex(random_bytes(32));
					$covExp = date('Y-m-d H:i:s', strtotime('+12 hours'));
					Config::get('db')->insert('staffing_tokens', array('token'=>$covTok,'user_id'=>$coverage['userId'],'account_id'=>$dbText['accountId'],'expires_at'=>$covExp));
					$covLink = "https://www.productivern.com/manager-view.php?t=" . $covTok;
					$covMsg = $alertName . " Report Alert: A ".$prodTerm." Report has been submitted with a variance of ".($nvariance >= 0 ? "+" : "").$nvariance." in ".$dep.". ".$covLink;
					$covXml = "<message id=\"".$coverage['mobile'].$now."\"><partnerpassword>".$psword."</partnerpassword><content><![CDATA[".$covMsg."]]></content></message>";
					$messages[] = $covXml;
					$numbers[] = "1" . $coverage['mobile'];
					$keywords[] = $keyword2;
				}
				}
				
				$smsoptions = Array(
					'numbers' => ($numbers ?? ''),
					'message' => ($messages ?? '')

				);
				
				}
								
				if($x==1){
				$result = sendNow($smsoptions);
				}
				echo json_encode(array('success'=>true,'data'=>$var));
				return true;
				}else{
				$textAlerts = 0;
				$escAlerts = 0;
				$textactive = 0;
				$textescalation = 0;
				$txtpause = 0;
				$noreply = '';
				$alertName = '';
				
				$var = array(
				'message'=>$staffing,
				'dataId'=>$dataId,
				'deptId'=>$deptId,
				'updated'=>$updated,
				'varianceType'=>$varianceType,
				'staffingTitle'=>$staffingTitle,
				'note'=>$note,
				'escalations'=>$escalations,
				'txtpause'=>$txtpause,
				'escAlerts'=>$escAlerts,
				'summary'=>$summary
				);
				echo json_encode(array('success'=>true,'data'=>$var));
				return true;
				}
			
}

function sendescalationText($escId){
		$userId = Config::get('account')['id'];	
		$accountId = Config::get('account')['accountId'];	
		$dbAdmin = Config::get('db')->get_results("select r.*, e.deptId, e.accountId, x.escAlerts, u.mobile, u.role, u.id as textId, z.first_name, z.last_name, d.dept FROM `productiveEscResponse` r LEFT JOIN `productiveEscalations` as e on e.id = r.escId LEFT JOIN `ProductiveDept` as d on d.id = e.deptId LEFT OUTER JOIN `productiveDeptXref` as x on x.deptId = e.deptId LEFT OUTER JOIN `productiveUser` as u on u.id = x.userId LEFT OUTER JOIN `productiveUser` as z on z.id=r.userId where r.id={$escId} AND u.id != r.userId AND u.txtPause=0 and u.active>=1 AND u.role>=5 AND LENGTH(u.mobile)=10 group by u.mobile order by r.id DESC");
			
		if($dbAdmin){
					
			foreach ($dbAdmin as $dbText){
				
				$mobile = $dbText['mobile'];
				$escAlerts = $dbText['escAlerts'];
				$textId =  $dbText['textId'];
				$textRole =  intval($dbText['role']);
				$deptId =  $dbText['deptId'];
				$deptName =  $dbText['dept'];
				$response =  $dbText['response'];
				$escUser =  $dbText['first_name'] . ' ' . $dbText['last_name'];
				
				if(intval($escAlerts)==1){
				
				$pw = generateRandomString(10);
				$logix = $pw . $dbText['accountId'];
				$data = array('logix'=>$logix,'logixTime'=>date("Y-m-d"));
				$updateWhere = array('id' =>$textId);
				Config::get('db')->update('productiveUser',$data,$updateWhere);
				$escToken = bin2hex(random_bytes(32));
				$escExpires = date('Y-m-d H:i:s', strtotime('+12 hours'));
				Config::get('db')->insert('staffing_tokens', array('token'=>$escToken,'user_id'=>$textId,'account_id'=>$dbText['accountId'],'expires_at'=>$escExpires));
				$newLink = "https://www.productivern.com/manager-view.php?t=" . $escToken;
										
				$message = " Escalation Response for " . $deptName . " from " . $escUser . ": ". $response . ". " . $newLink;
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

				$coverage = checkUserCoverage($textId);
				$coverPause = ($coverage) ? $coverage['coverPauseSms'] : 0;
				if (!($coverage && $coverPause == 1)) {
					$messages[] = $xmlMsg;
					$numbers[] = "1" . $mobile;
					$keywords[] = $keyword2;
				}
				if ($coverage && $coverage['hasMobile']) {
					$covEscTok = bin2hex(random_bytes(32));
					$covEscExp = date('Y-m-d H:i:s', strtotime('+12 hours'));
					Config::get('db')->insert('staffing_tokens', array('token'=>$covEscTok,'user_id'=>$coverage['userId'],'account_id'=>$dbText['accountId'],'expires_at'=>$covEscExp));
					$covEscLink = "https://www.productivern.com/manager-view.php?t=" . $covEscTok;
					$covEscMsg = " Escalation Response for ".$deptName." from ".$escUser.": ".$response.". ".$covEscLink;
					$covEscXml = "<message id=\"".$coverage['mobile'].$now."\"><partnerpassword>".$psword."</partnerpassword><content><![CDATA[".$covEscMsg."]]></content></message>";
					$messages[] = $covEscXml;
					$numbers[] = "1" . $coverage['mobile'];
					$keywords[] = $keyword2;
				}
				}
				
				$smsoptions = Array(
					'numbers' => ($numbers ?? ''),
					'message' => ($messages ?? ''),
					'keyword' => $keywords,
					'login' => $slooce,
					'shortCode' => $shortCode

				);
				
				}
								
				$result = sendescNow($smsoptions);
			}	
			
			
}

function sendtaskEscalation($deptId){
	$userId = Config::get('account')['id'];	
		$accountId = Config::get('account')['accountId'];

		//$dbAdmin = Config::get('db')->get_results("select c.*, u.id as textId, u.mobile, u.logAlerts, d.dept FROM `productivecrashLog` c LEFT JOIN `ProductiveDept` as d on d.id=c.deptId LEFT JOIN `productiveDeptXref` as x on x.deptId=c.deptId lEFT OUTER JOIN `productivetaskText` as t on t.taskId = c.logId AND t.userId={$userId} AND t.textMe=1 LEFT OUTER JOIN `productiveUser` as u on u.id = t.userId where c.id={$escId} AND u.txtPause=0 and u.active>=1 AND u.role>=5 AND LENGTH(u.mobile)=10 group by u.mobile order by c.id DESC");
		$dbAdmin = Config::get('db')->get_results("SELECT x.*, u.txtPause, u.mobile, d.dept as deptName from `productiveDeptXref` x LEFT JOIN `ProductiveDept` as d on d.id=x.deptId LEFT JOIN `productiveUser` as u on u.id=x.userId where u.active=1 AND u.role >=5 AND x.deptId={$deptId} AND x.escAlerts=1 AND u.txtPause=0 AND LENGTH(u.mobile)=10");	
		
		if($dbAdmin){
					
			foreach ($dbAdmin as $dbText){
				
				$mobile = $dbText['mobile'];
				$textId =  $dbText['userId'];
				$deptName =  $dbText['deptName'];
				
				$pw = generateRandomString(10);
				$logix = $pw . $accountId;
				$data = array('logix'=>$logix,'logixTime'=>date("Y-m-d"));
				$updateWhere = array('id' =>$textId);
				Config::get('db')->update('productiveUser',$data,$updateWhere);
				$escToken2 = bin2hex(random_bytes(32));
				$escExpires2 = date('Y-m-d H:i:s', strtotime('+12 hours'));
				Config::get('db')->insert('staffing_tokens', array('token'=>$escToken2,'user_id'=>$textId,'account_id'=>$accountId,'expires_at'=>$escExpires2));
				$newLink = "https://www.productivern.com/manager-view.php?t=" . $escToken2;
										
				$message = " A Safety Huddle Escalation has just been submitted by " . $deptName . ". " . $newLink;
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

				$textId = $dbText['userId'];
				$coverage = checkUserCoverage($textId);
				$coverPause = ($coverage) ? $coverage['coverPauseSms'] : 0;
				if (!($coverage && $coverPause == 1)) {
					$messages[] = $xmlMsg;
					$numbers[] = "1" . $mobile;
					$keywords[] = $keyword2;
				}
				if ($coverage && $coverage['hasMobile']) {
					$covQTok = bin2hex(random_bytes(32));
					$covQExp = date('Y-m-d H:i:s', strtotime('+12 hours'));
					Config::get('db')->insert('manager_tokens', array('token'=>$covQTok,'user_id'=>$coverage['userId'],'account_id'=>$accountId,'context'=>'qbl','ref_id'=>intval($deptId),'expires_at'=>$covQExp));
					$covQLink = "https://www.productivern.com/manager-view.php?t=" . $covQTok;
					if(intval($msgId)==1){ $covQMsg = "Please contact ".$deptName." immediately regarding a possible hemorrhage in process.  ".$covQLink; }
					elseif(intval($msgId)==3){ $covQMsg = "Please contact ".$deptName." immediately regarding a QBL with a ratio over 75%. This can be an early hemorrhage warning.  ".$covQLink; }
					else { $covQMsg = "A QBL Report has been created in ".$deptName.".  ".$covQLink; }
					$covQXml = "<message id=\"".$coverage['mobile'].$now."\"><partnerpassword>".$psword."</partnerpassword><content><![CDATA[".$covQMsg."]]></content></message>";
					$messages[] = $covQXml;
					$numbers[] = "1" . $coverage['mobile'];
					$keywords[] = $keyword2;
				}
				
				$smsoptions = Array(
					'numbers' => ($numbers ?? ''),
					'message' => ($messages ?? ''),
					'keyword' => $keywords,
					'login' => $slooce

				);
				
				}
								
				$result = sendescNow($smsoptions);
			}	
			
			
}

function sendtaskEsc($deptId){
	$userId = Config::get('account')['id'];	
		$accountId = Config::get('account')['accountId'];

		//$dbAdmin = Config::get('db')->get_results("select c.*, u.id as textId, u.mobile, u.logAlerts, d.dept FROM `productivecrashLog` c LEFT JOIN `ProductiveDept` as d on d.id=c.deptId LEFT JOIN `productiveDeptXref` as x on x.deptId=c.deptId lEFT OUTER JOIN `productivetaskText` as t on t.taskId = c.logId AND t.userId={$userId} AND t.textMe=1 LEFT OUTER JOIN `productiveUser` as u on u.id = t.userId where c.id={$escId} AND u.txtPause=0 and u.active>=1 AND u.role>=5 AND LENGTH(u.mobile)=10 group by u.mobile order by c.id DESC");
		$dbAdmin = Config::get('db')->get_results("SELECT x.*, u.txtPause, u.mobile, d.dept as deptName from `productiveDeptXref` x LEFT JOIN `ProductiveDept` as d on d.id=x.deptId LEFT JOIN `productiveUser` as u on u.id=x.userId where u.active=1 AND u.txtPause=0 AND u.role >=5 AND x.deptId={$deptId} AND x.escAlerts=1 and LENGTH(u.mobile)=10");	
			
		if($dbAdmin){
					
			foreach ($dbAdmin as $dbText){
				
				$mobile = $dbText['mobile'];
				$textId =  $dbText['userId'];
				$deptName =  $dbText['deptName'];
			
				$taskToken = bin2hex(random_bytes(32));
				$taskExpires = date('Y-m-d H:i:s', strtotime('+12 hours'));
				Config::get('db')->insert('staffing_tokens', array('token'=> $taskToken,'user_id'=> $textId,'account_id'=> $accountId,'expires_at'=> $taskExpires));
				$newLink = "https://www.productivern.com/manager-view.php?t=" . $taskToken;
				$message = " A Task Escalation has just been submitted by " . $deptName . ". " . $newLink;
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

				$textId = $dbText['userId'];
				$coverage = checkUserCoverage($textId);
				$coverPause = ($coverage) ? $coverage['coverPauseSms'] : 0;
				if (!($coverage && $coverPause == 1)) {
					$messages[] = $xmlMsg;
					$numbers[] = "1" . $mobile;
					$keywords[] = $keyword2;
				}
				if ($coverage && $coverage['hasMobile']) {
					$covTaskTok = bin2hex(random_bytes(32));
					$covTaskExp = date('Y-m-d H:i:s', strtotime('+12 hours'));
					Config::get('db')->insert('staffing_tokens', array('token'=>$covTaskTok,'user_id'=>$coverage['userId'],'account_id'=>$accountId,'expires_at'=>$covTaskExp));
					$covTaskLink = "https://www.productivern.com/manager-view.php?t=" . $covTaskTok;
					$covTaskMsg = "A Task Escalation has just been submitted by " . $deptName . ". " . $covTaskLink;
					$covTaskXml = "<message id=\"".$coverage['mobile'].$now."\"><partnerpassword>".$psword."</partnerpassword><content><![CDATA[".$covTaskMsg."]]></content></message>";
					$messages[] = $covTaskXml;
					$numbers[] = "1" . $coverage['mobile'];
					$keywords[] = $keyword2;
				}
				
				$smsoptions = Array(
					'numbers' => ($numbers ?? ''),
					'message' => ($messages ?? ''),
					'keyword' => $keywords,
					'login' => $slooce

				);
				
				}
								
				$result = sendescNow($smsoptions);
			}	
			
			
}

function SendQblHemorrhageAlert() {
	$db = Config::get('db');
	$token = preg_replace('/[^a-f0-9]/i', '', $_REQUEST['token'] ?? '');
	if (!$token) { echo json_encode(['success'=>false]); return; }
	$tokenRow = $db->get_results("SELECT deptId FROM productiveQrTokens WHERE token='{$token}' AND page='qbl' LIMIT 1");
	if (!$tokenRow) { echo json_encode(['success'=>false]); return; }
	$deptId = intval($tokenRow[0]['deptId']);
	$dept = $db->get_results("SELECT d.accountId FROM ProductiveDept d WHERE d.id={$deptId} AND d.active=1 LIMIT 1");
	if (!$dept) { echo json_encode(['success'=>false]); return; }
	$accountId = intval($dept[0]['accountId']);
	Config::set('account', ['id' => 0, 'accountId' => $accountId]);
	$_REQUEST['dataId'] = $deptId;
	$_REQUEST['msgId']  = 1;
	sendqblAlert();
}

function sendqblAlert(){
	$userId = Config::get('account')['id'];
	$accountId = Config::get('account')['accountId'];
	$deptId = intval(isset($_REQUEST['dataId']) ? $_REQUEST['dataId'] : 0);
	$msgId = (isset($_REQUEST['msgId'])) ? $_REQUEST['msgId'] : 0;
		
		//$dbAdmin = Config::get('db')->get_results("SELECT u.*, d.dept as deptName from `productiveUser` u LEFT JOIN `ProductiveDept` as d on d.id=$deptId where u.id IN (SELECT `userId` from `productiveDeptXref` where `deptId`=$deptId) and u.txtEscalation=1 and LENGTH(u.mobile)=10");
		$dbAdmin = Config::get('db')->get_results("SELECT x.*, u.txtPause, u.logix, u.mobile, d.dept as deptName from `productiveDeptXref` x LEFT JOIN `ProductiveDept` as d on d.id=x.deptId LEFT JOIN `productiveUser` as u on u.id=x.userId where x.deptId={$deptId} AND x.escAlerts=1 AND u.txtPause=0 and LENGTH(u.mobile)=10 GROUP BY u.mobile");	
			
		if($dbAdmin){
					
			foreach ($dbAdmin as $dbText){
					$mobile = $dbText['mobile'];
				$escToken3 = bin2hex(random_bytes(32));
				$escExpires3 = date('Y-m-d H:i:s', strtotime('+12 hours'));
				Config::get('db')->insert('manager_tokens', array('token'=> $escToken3, 'user_id'=> $dbText['userId'], 'account_id'=> $accountId, 'context'=> 'qbl', 'ref_id'=> intval($deptId), 'expires_at'=> $escExpires3));
				$newLink = "https://www.productivern.com/manager-view.php?t=" . $escToken3;
				$deptName = $dbText['deptName'];
				if(intval($msgId)==1){
				$message = "Please contact " . $deptName . " immediately regarding a possible hemorrhage in process.  " . $newLink;
				}elseif(intval($msgId)==3){
				$message = "Please contact " . $deptName . " immediately regarding a QBL with a ratio over 75%. This can be an early hemorrhage warning.  " . $newLink;
				}else{
				$message = "A QBL Report has been created in " . $deptName . ".  " . $newLink;
				}
				$psword = "J8775bcgEE2065";
				$keyword2 = "JOBALARM58046";
				$shortcode = "58046";
				$slooce = "jobalarm45";
				
				$now = time();
				
                $smsMessageId = $mobile . $now;
                
                $xmlMsg = "";
                $xmlMsg .= "<message id=\"".$smsMessageId."\">";
                $xmlMsg .= "<partnerpassword>".$psword."</partnerpassword>";
                $xmlMsg .= "<content><![CDATA[" . $message . "]]></content>";
                $xmlMsg .= "</message>";

				$textId = $dbText['userId'];
				$coverage = checkUserCoverage($textId);
				$coverPause = ($coverage) ? $coverage['coverPauseSms'] : 0;
				if (!($coverage && $coverPause == 1)) {
					$messages[] = $xmlMsg;
					$numbers[] = "1" . $mobile;
					$keywords[] = $keyword2;
				}
				if ($coverage && $coverage['hasMobile'] && floatval($qbl) >= $coverage['coverQblMin']) {
					$covQETok = bin2hex(random_bytes(32));
					$covQEExp = date('Y-m-d H:i:s', strtotime('+12 hours'));
					Config::get('db')->insert('manager_tokens', array('token'=>$covQETok,'user_id'=>$coverage['userId'],'account_id'=>Config::get('account')['accountId'],'context'=>'qbl','ref_id'=>intval($deptId),'expires_at'=>$covQEExp));
					$covQELink = "https://www.productivern.com/manager-view.php?t=" . $covQETok;
					if(intval($msgId)==1){ $covQEMsg = " A QBL ALERT has been created in ".$deptName.".  ".$covQELink; }
					elseif(intval($msgId)==3){ $covQEMsg = "Please contact ".$deptName." immediately regarding a QBL with a ratio over 75%. This can be an early hemorrhage warning.  ".$covQELink; }
					else { $qblLabelCov = floatval($qbl)>=1000 ? $qbl."ml Possible Hemorrhage" : $qbl."ml"; $covQEMsg = "A QBL Notification (".$qblLabelCov.") has been created in ".$deptName.".  ".$covQELink; }
					$covQEXml = "<message id=\"".$coverage['mobile'].$now."\"><partnerpassword>".$psword."</partnerpassword><content><![CDATA[".$covQEMsg."]]></content></message>";
					$messages[] = $covQEXml;
					$numbers[] = "1" . $coverage['mobile'];
					$keywords[] = $keyword2;
				}
				
				$smsoptions = Array(
					'numbers' => ($numbers ?? ''),
					'message' => ($messages ?? ''),
					'keyword' => $keywords,
					'login' => $slooce

				);
				
				}
								
				$result = sendescNow($smsoptions);
			}	
			
			
}

function sendqblEsc($deptId,$msgId,$qbl,$qblToken='',$isUpdate=false,$isCumulative=false){
	$deptId = intval($deptId);
	$userId = Config::get('account')['id'];	
		$accountId = Config::get('account')['accountId'];
		
		//$dbAdmin = Config::get('db')->get_results("SELECT u.*, d.dept as deptName from `productiveUser` u LEFT JOIN `ProductiveDept` as d on d.id=$deptId where u.id IN (SELECT `userId` from `productiveDeptXref` where `deptId`=$deptId) and u.txtEscalation=1 and LENGTH(u.mobile)=10");
		$dbAdmin = Config::get('db')->get_results("SELECT x.*, u.txtPause, u.logix, u.mobile, u.qblWarnMin, d.dept as deptName from `productiveDeptXref` x LEFT JOIN `ProductiveDept` as d on d.id=x.deptId LEFT JOIN `productiveUser` as u on u.id=x.userId where u.active=1 AND u.txtPause=0 AND u.role >=5 AND x.deptId={$deptId} AND x.escAlerts=1 and LENGTH(u.mobile)=10 GROUP BY u.mobile");	
			
		if($dbAdmin){
					
			foreach ($dbAdmin as $dbText){
					if(intval($msgId)==2 && intval($dbText['qblWarnMin'])>0 && floatval($qbl)<intval($dbText['qblWarnMin'])) continue;
					if(strlen($dbText['logix'] ?? '')==0){
					$pw = generateRandomString(10);
					$logix = $pw . $accountId;
					$data = array('logix'=>$logix,
								'logixTime'=>date("Y-m-d"));
					$updateWhere = array('id' => $dbText['userId']);
					Config::get('db')->update('productiveUser',$data,$updateWhere);
					}else{
					$logix = $dbText['logix'];	
					}
				$mobile = $dbText['mobile'];
				$deptName = $dbText['deptName'];
				$mgrTok = bin2hex(random_bytes(32));
				$mgrExp = date('Y-m-d H:i:s', strtotime('+12 hours'));
				Config::get('db')->insert('manager_tokens', array('token'=> $mgrTok, 'user_id'=> $dbText['userId'], 'account_id'=> Config::get('account')['accountId'], 'context'=> 'qbl', 'ref_id'=> intval($deptId), 'expires_at'=> $mgrExp));
				$newLink = "https://www.productivern.com/manager-view.php?t=" . $mgrTok;
				$cumLabel = $isCumulative ? "Cumulative " : "";
				if(intval($msgId)==1){
				$message = "A " . $cumLabel . "QBL ALERT (" . $cumLabel . round($qbl) . "ml) has been created in " . $deptName . ".  " . $newLink;
				}elseif(intval($msgId)==3){
				$message = "Please contact " . $deptName . " immediately regarding a " . $cumLabel . "QBL of " . round($qbl) . "ml with a ratio over 75%. This can be an early hemorrhage warning.  " . $newLink;
				}else{
				$qblLabel = floatval($qbl) >= 1000 ? round($qbl) . "ml Possible Hemorrhage" : round($qbl) . "ml";
				$message = "A " . $cumLabel . "QBL Notification (" . $qblLabel . ") has been created in " . $deptName . ".  " . $newLink;
				}
				if ($isUpdate) $message = "UPDATED: " . ltrim($message);
				//else{
				//$message = " A QBL has exceeded the Total Weight of 1500g in " . $deptName . ".  " . $newLink;	
				//}
				$psword = "J8775bcgEE2065";
				$keyword2 = "JOBALARM58046";
				$shortcode = "58046";
				$slooce = "jobalarm45";
				
				$now = time();
				
                $msgId1 = "";
                $msgId1 .= "";
                $msgId1 .= $mobile . $now;
                
                $xmlMsg = "";
                $xmlMsg .= "<message id=\"".$msgId1."\">";
                $xmlMsg .= "<partnerpassword>".$psword."</partnerpassword>";
                $xmlMsg .= "<content><![CDATA[" . $message . "]]></content>";
                $xmlMsg .= "</message>";

				$textId = $dbText['userId'];
				$coverage = checkUserCoverage($textId);
				$coverPause = ($coverage) ? $coverage['coverPauseSms'] : 0;
				if (!($coverage && $coverPause == 1)) {
					$messages[] = $xmlMsg;
					$numbers[] = "1" . $mobile;
					$keywords[] = $keyword2;
				}
				if ($coverage && $coverage['hasMobile'] && floatval($qbl) >= $coverage['coverQblMin']) {
					$covQTok = bin2hex(random_bytes(32));
					$covQExp = date('Y-m-d H:i:s', strtotime('+12 hours'));
					Config::get('db')->insert('manager_tokens', array('token'=>$covQTok,'user_id'=>$coverage['userId'],'account_id'=>$accountId,'context'=>'qbl','ref_id'=>intval($deptId),'expires_at'=>$covQExp));
					$covQLink = "https://www.productivern.com/manager-view.php?t=" . $covQTok;
					if(intval($msgId)==1){ $covQMsg = "Please contact ".$deptName." immediately regarding a possible hemorrhage in process.  ".$covQLink; }
					elseif(intval($msgId)==3){ $covQMsg = "Please contact ".$deptName." immediately regarding a QBL with a ratio over 75%. This can be an early hemorrhage warning.  ".$covQLink; }
					else { $covQMsg = "A QBL Notification (".$qblLabel.") has been created in ".$deptName.".  ".$covQLink; }
					if ($isUpdate) $covQMsg = "UPDATED: " . $covQMsg;
					$covQXml = "<message id=\"".$coverage['mobile'].$now."\"><partnerpassword>".$psword."</partnerpassword><content><![CDATA[".$covQMsg."]]></content></message>";
					$messages[] = $covQXml;
					$numbers[] = "1" . $coverage['mobile'];
					$keywords[] = $keyword2;
				}
				
				$smsoptions = Array(
					'numbers' => ($numbers ?? ''),
					'message' => ($messages ?? ''),
					'keyword' => $keywords,
					'login' => $slooce

				);
				
				}
								
				$result = sendescNow($smsoptions);
			}	
			
			

		// Coverage-only pass: users with coverUserId set but escAlerts=0
		$dbCov2 = Config::get('db')->get_results("SELECT x.userId, d.dept as deptName FROM `productiveDeptXref` x LEFT JOIN `ProductiveDept` as d on d.id=x.deptId LEFT JOIN `productiveUser` as u on u.id=x.userId where u.active=1 AND u.role >=5 AND x.deptId={$deptId} AND u.coverUserId > 0 AND NOT (x.escAlerts=1 AND u.txtPause=0)");
		if ($dbCov2) {
			$covNums = array(); $covMsgs = array(); $covKwds = array();
			$cpword = "J8775bcgEE2065"; $ckw = "JOBALARM58046"; $cslooce = "jobalarm45"; $cnow = time();
			foreach ($dbCov2 as $dc) {
				$cov = checkUserCoverage($dc['userId']);
				if ($cov && $cov['hasMobile'] && floatval($qbl) >= $cov['coverQblMin']) {
					$cTok = bin2hex(random_bytes(32));
					$cExp = date('Y-m-d H:i:s', strtotime('+12 hours'));
					Config::get('db')->insert('manager_tokens', array('token'=>$cTok,'user_id'=>$cov['userId'],'account_id'=>$accountId,'context'=>'qbl','ref_id'=>intval($deptId),'expires_at'=>$cExp));
					$cLink = "https://www.productivern.com/manager-view.php?t=" . $cTok;
					if(intval($msgId)==1){ $cMsg = "Please contact ".$dc['deptName']." immediately regarding a possible hemorrhage in process.  ".$cLink; }
					elseif(intval($msgId)==3){ $cMsg = "Please contact ".$dc['deptName']." immediately regarding a QBL with a ratio over 75%. This can be an early hemorrhage warning.  ".$cLink; }
					else { $cLabel = floatval($qbl)>=1000 ? $qbl."ml Possible Hemorrhage" : $qbl."ml"; $cMsg = "A QBL Notification (".$cLabel.") has been created in ".$dc['deptName'].".  ".$cLink; }
				if ($isUpdate) $cMsg = "UPDATED: " . $cMsg;
					$cXml = "<message id=\"".$cov['mobile'].$cnow."\"><partnerpassword>".$cpword."</partnerpassword><content><![CDATA[".$cMsg."]]></content></message>";
					$covNums[] = "1".$cov['mobile']; $covMsgs[] = $cXml; $covKwds[] = $ckw;
				}
			}
			if (!empty($covNums)) {
				sendescNow(Array('numbers'=>$covNums,'message'=>$covMsgs,'keyword'=>$covKwds,'login'=>$cslooce));
			}
		}
}

function updateCensus($accountId){
	$getDept = Config::get('db')->get_results("select n.*, h.prodMeasure, h.shift as censusShift, h.dept FROM `productiveNewData` n left join `ProductiveDept` as h on h.id=n.deptId where n.accountId={$accountId} AND h.prodMeasure=1 AND n.shift=h.shift and n.userId>0 group by n.id order by n.id DESC");
	
	foreach($getDept as $updateDept){
	$day = $updateDept['dayDate'];
	$deptId = $updateDept['deptId'];
	$census = $updateDept['atotal'];
	$updateProd = Config::get('db')->get_results("select n.* from `productiveNewData` n where n.dayDate=DATE_SUB('{$day}', INTERVAL 1 DAY) AND n.deptId={$deptId} and n.userId>0 group by n.shift order by n.shift DESC");
	foreach($updateProd as $updateShift){
	
	$updateCensus = array(
					'censusTotal' => $census
					);
	$updateNow = array('id' => $updateShift['id']);
	$newProd = Config::get('db')->update('productiveNewData',$updateCensus,$updateNow);
	}
	}	
		
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

/*
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
				'note' => addslashes($note)
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
*/
	
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
	$trackNote1 = (isset($_REQUEST['trackNote1'])) ? addslashes($_REQUEST['trackNote1']) : '';
	$trackNote2 = (isset($_REQUEST['trackNote2'])) ? addslashes($_REQUEST['trackNote2']) : '';
	$trackNote3 = (isset($_REQUEST['trackNote3'])) ? addslashes($_REQUEST['trackNote3']) : '';
	$trackNote4 = (isset($_REQUEST['trackNote4'])) ? addslashes($_REQUEST['trackNote4']) : '';
	$gvariance = (isset($_REQUEST['gvar'])) ? floatval($_REQUEST['gvar']) : 0;
	
	//$pHours = ($skillval1 + $skillval2 + $skillval3 + $skillval4 + $skillval5 + $skillval6 + $skillval7 + $skillval8 + $skillval9 + $skillval10);
	$admissions = (isset($_REQUEST['admissions'])) ? intval($_REQUEST['admissions']) : 0;
	$transfers = (isset($_REQUEST['transfers'])) ? intval($_REQUEST['transfers']) : 0;
	$discharges = (isset($_REQUEST['discharges'])) ? intval($_REQUEST['discharges']) : 0;
	$submittedby = (isset($_REQUEST['submittedby'])) ? addslashes($_REQUEST['submittedby']) : '';
	//$pHours = floatval($plannedHours);
	//$procedureCount = floatval($procedures);
	//$resourceCount = floatval($chargecount)+intval($techcount)+intval($seccount)+intval($sittercount)+intval($nursecount);
	$note2 = (isset($_REQUEST['note'])) ? $_REQUEST['note'] : '';
	$skillTotal = $skillval1 +  $skillval2 +  $skillval3 +  $skillval4 +  $skillval5 +  $skillval6 +  $skillval7 +  $skillval8 +  $skillval9 +  $skillval10 +  $skillval11;
	//$gridCount = $skillTotal;
	$prov1 = (isset($_REQUEST['prov1'])) ? intval($_REQUEST['prov1']) : 0;
	$procs1 = (isset($_REQUEST['procs1'])) ? floatval($_REQUEST['procs1']) : 0;
	$hours1 = (isset($_REQUEST['hours1'])) ? floatval($_REQUEST['hours1']) : 0;
	$prov2 = (isset($_REQUEST['prov2'])) ? intval($_REQUEST['prov2']) : 0;
	$procs2 = (isset($_REQUEST['procs2'])) ? floatval($_REQUEST['procs2']) : 0;
	$hours2 = (isset($_REQUEST['hours2'])) ? floatval($_REQUEST['hours2']) : 0;
	$prov3 = (isset($_REQUEST['prov3'])) ? intval($_REQUEST['prov3']) : 0;
	$procs3 = (isset($_REQUEST['procs3'])) ? floatval($_REQUEST['procs3']) : 0;
	$hours3 = (isset($_REQUEST['hours3'])) ? floatval($_REQUEST['hours3']) : 0;
	$prov4 = (isset($_REQUEST['prov4'])) ? intval($_REQUEST['prov4']) : 0;
	$procs4 = (isset($_REQUEST['procs4'])) ? floatval($_REQUEST['procs4']) : 0;
	$hours4 = (isset($_REQUEST['hours4'])) ? floatval($_REQUEST['hours4']) : 0;
	$prov5 = (isset($_REQUEST['prov5'])) ? intval($_REQUEST['prov5']) : 0;
	$procs5 = (isset($_REQUEST['procs5'])) ? floatval($_REQUEST['procs5']) : 0;
	$hours5 = (isset($_REQUEST['hours5'])) ? floatval($_REQUEST['hours5']) : 0;
	$prov6 = (isset($_REQUEST['prov6'])) ? intval($_REQUEST['prov6']) : 0;
	$procs6 = (isset($_REQUEST['procs6'])) ? floatval($_REQUEST['procs6']) : 0;
	$hours6 = (isset($_REQUEST['hours6'])) ? floatval($_REQUEST['hours6']) : 0;
	
	$provhours = $hours1 + $hours2 + $hours3 + $hours4 + $hours5 + $hours6;
	
	$grid1 = (isset($_REQUEST['grid1'])) ? ($_REQUEST['grid1']) : 0;
	$grid2 = (isset($_REQUEST['grid2'])) ? ($_REQUEST['grid2']) : 0;	
	$grid3 = (isset($_REQUEST['grid3'])) ? ($_REQUEST['grid3']) : 0;	
	$grid4 = (isset($_REQUEST['grid4'])) ? ($_REQUEST['grid4']) : 0;	
	$grid5 = (isset($_REQUEST['grid5'])) ? ($_REQUEST['grid5']) : 0;	
	$grid6 = (isset($_REQUEST['grid6'])) ? ($_REQUEST['grid6']) : 0;	
	$grid7 = (isset($_REQUEST['grid7'])) ? ($_REQUEST['grid7']) : 0;	
	$grid8 = (isset($_REQUEST['grid8'])) ? ($_REQUEST['grid8']) : 0;
	$grid9 = (isset($_REQUEST['grid9'])) ? ($_REQUEST['grid9']) : 0;	
	$grid10 = (isset($_REQUEST['grid10'])) ? ($_REQUEST['grid10']) : 0;
	
	$gridvar1 = (isset($_REQUEST['gridvar1'])) ? ($_REQUEST['gridvar1']) : 0;
	$gridvar2 = (isset($_REQUEST['gridvar2'])) ? ($_REQUEST['gridvar2']) : 0;
	$gridvar3 = (isset($_REQUEST['gridvar3'])) ? ($_REQUEST['gridvar3']) : 0;
	$gridvar4 = (isset($_REQUEST['gridvar4'])) ? ($_REQUEST['gridvar4']) : 0;
	$gridvar5 = (isset($_REQUEST['gridvar5'])) ? ($_REQUEST['gridvar5']) : 0;
	$gridvar6 = (isset($_REQUEST['gridvar6'])) ? ($_REQUEST['gridvar6']) : 0;
	$gridvar7 = (isset($_REQUEST['gridvar7'])) ? ($_REQUEST['gridvar7']) : 0;
	$gridvar8 = (isset($_REQUEST['gridvar8'])) ? ($_REQUEST['gridvar8']) : 0;	
	$gridvar9 = (isset($_REQUEST['gridvar9'])) ? ($_REQUEST['gridvar9']) : 0;
	$gridvar10 = (isset($_REQUEST['gridvar10'])) ? ($_REQUEST['gridvar10']) : 0;
	
	if($note2){
	$note = stripslashes($note2);
	}else{
	$note = false;	
	}
	//$prodCalc = -1;
	$nurseAdd = 0;
	$accountId = Config::get('account')['accountId'];
	$prodTerm = Config::get('account')['prodTerm'];
	$hrsTerm = Config::get('account')['hrsTerm'];
	
	$userId = Config::get('account')['id'];	
	
	$dbData = Config::get('db')->get_results("select n.*, (CASE WHEN (n.userId=0 AND n.dayDate>=DATE_FORMAT(CURDATE(),'%Y-%m-%d')) OR (n.dayDate>DATE_FORMAT(CURDATE(),'%Y-%m-%d')) THEN 1 ELSE 0 END) as plannedRecord, g.griddeptId, g.census, IFNULL(g.cn,0) AS cn, IFNULL(g.rn,0) AS rn, IFNULL(g.rn1,0) AS rn1, IFNULL(g.rn2,0) AS rn2, IFNULL(g.pct,0) AS pct, IFNULL(g.sitter,0) AS sitter, IFNULL(g.sec,0) as sec, IFNULL(g.other1,0) AS other1, IFNULL(g.other2,0) AS other2, IFNULL(g.other3,0) AS other3, g.dayNight, g.dow, (select count(`dept`) from `productiveAccountXref` where `type`<2 AND `whpPlan` !=1 and `dept`=n.deptId) as shiftCount, a.payPeriod, a.displayProd, a.payPeriodFirst, a.productivityPosNeg, h.typetrack1, h.typetrack2, h.typetrack3, h.typetrack4, h.supportHrs, h.hppd, h.rnVariance, h.indMeasure, h.useGrid, h.rnThreshold, h.thresholdHigh, h.thresholdLow, h.adjustment, h.avgWage, h.trackdata, h.churn, h.uosValue, h.budgetMeasure, h.escalations, h.prodMeasure1, h.id, h.viewPage, h.dept, h.skill1calc, h.skill2calc, h.skill3calc, h.skill4calc, h.skill5calc, h.skill6calc, h.skill7calc, h.skill8calc, h.skill9calc, h.skill10calc, h.skill11calc, h.skillbudget1, h.customWHP, h.customWHP2, h.customWHP3, h.customWHP4, h.customWHPvalue, h.customWHP2value, h.customWHP3value, h.customWHP4value, h.customWHP5value, h.skillbudget2, h.skillbudget3, h.skillbudget4, h.skillbudget5, h.shift as censusShift, s.shifthrs from `productiveNewData` n left join `ProductiveDept` as h on h.id=n.deptId left join `productiveAccount` as a on a.id = h.accountId left outer join `productiveStaffingGrid` as g on g.griddeptId=n.deptId AND (g.dayNight=n.shift OR g.dayNight=((n.shift)-1)) AND g.dow=DAYOFWEEK(n.dayDate) LEFT JOIN `productiveAccountXref` as s on s.dept = n.deptId and s.shift=n.shift where n.id={$dataId}");
	
	if($dbData){
		$prodCalc = intval($dbData[0]['productivityPosNeg']);
		//$gvariance = $prodCalc * $gvariance1;
		$hppd = floatval($dbData[0]['hppd']);
		//$value = floatval($dbData[0]['value']);
		$nUser = intval($dbData[0]['userId']);
		$adjustment = floatval($dbData[0]['adjustment']);
		$deptId = $dbData[0]['deptId'];
		$viewPage = $dbData[0]['viewPage'];
		$escalations = intval($dbData[0]['escalations']);
		$dep = $dbData[0]['dept'];
		$prodMeasure = intval($dbData[0]['prodMeasure']);
		$rnVariance = intval($dbData[0]['rnVariance']);
		$unitCat = intval($dbData[0]['unitCategory']);
		$indMeasure = intval($dbData[0]['indMeasure']);
		$type = intval($dbData[0]['type']);
		$budgetMeasure = intval($dbData[0]['budgetMeasure']);
		$uosValue = floatval($dbData[0]['uosValue']);
		$customWHPvalue = floatval($dbData[0]['customWHPvalue']);
		$customWHP2value = floatval($dbData[0]['customWHP2value']);
		$customWHP3value = floatval($dbData[0]['customWHP3value']);
		$customWHP4value = floatval($dbData[0]['customWHP4value']);
		$customWHP5value = floatval($dbData[0]['customWHP5value']);
		
		$customWHP = intval($dbData[0]['customWHP']);
		$customWHP2 = intval($dbData[0]['customWHP2']);
		$customWHP3 = intval($dbData[0]['customWHP3']);
		$customWHP4 = intval($dbData[0]['customWHP4']);
		$customWHP5 = intval($dbData[0]['customWHP5']);
		$supportHrs = floatval($dbData[0]['supportHrs']);
		$plannedRecord = intval($dbData[0]['plannedRecord']);
		
		$avgWage = floatval($dbData[0]['avgWage']);
		$trackdata = $dbData[0]['trackdata'];
		$gridTotal = (intval($dbData[0]['cn'])+intval($dbData[0]['rn'])+intval($dbData[0]['rn1'])+intval($dbData[0]['rn2'])+intval($dbData[0]['pct'])+intval($dbData[0]['sitter'])+intval($dbData[0]['sec'])+intval($dbData[0]['other1'])+intval($dbData[0]['other2'])+intval($dbData[0]['other3'])) * $supportHrs;
		
		$typetrack1 = intval($dbData[0]['typetrack1']);
		$typetrack2 = intval($dbData[0]['typetrack2']);
		$typetrack3 = intval($dbData[0]['typetrack3']);
		$typetrack4 = intval($dbData[0]['typetrack4']);
		
		if(intval($deptId)==435 && intval($procedures)==0 && $customVal2>0){
		$procedureCount = $customVal2;
		}else{
		$procedureCount = $procedures;
		}

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
		
		$rnThresholdHigh = floatval($dbData[0]['rnThreshold']);
		$rnThresholdLow = $rnThresholdHigh * -1;
		$thresholdHigh = floatval($dbData[0]['thresholdHigh']) / 100;
		$thresholdLow = floatval($dbData[0]['thresholdLow']) / 100;
		
		$shiftCount = intval($dbData[0]['shiftCount']);
		
		
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
					$addBudget = floatval($budgetresource['SunTotal']);
					break;
				case "2":
					$addBudget = floatval($budgetresource['MonTotal']);
					break;
				case "3":
					$addBudget = floatval($budgetresource['TueTotal']);
					break;
				case "4":
					$addBudget = floatval($budgetresource['WedTotal']);
					break;
				case "5":
					$addBudget = floatval($budgetresource['ThuTotal']);
					break;
				case "6":
					$addBudget = floatval($budgetresource['FriTotal']);
					break;
				case "7":
					$addBudget = floatval($budgetresource['SatTotal']);
					break;
				default:
					$addBudget = 0.00;
				}	
					$budgetAdd = $budgetAdd + $addBudget;
				}		
				$budgetHours = (($skillval1 * $skillbudget1) + ($skillval2 * $skillbudget2) + ($skillval3 * $skillbudget3) + ($skillval4 * $skillbudget4) + ($skill1val5 * $skillbudget5) + $budgetAdd);
				
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
	
	$totHours = (((($skillval1 * $skill1calc) + ($skillval2 * $skill2calc) + ($skillval3 * $skill3calc) + ($skillval4 * $skill4calc) + ($skillval5 * $skill5calc) + ($skillval6 * $skill6calc) + ($skillval7 * $skill7calc) + ($skillval8 * $skill8calc) + ($skillval9 * $skill9calc) + ($skillval10 * $skill10calc) + ($skillval11 * $skill11calc))) * $supportHrs) + $nurseAdd;
	
	if($totHours ==0){
		$totalHours=$hppd;
	}else{
		$totalHours=$totHours;
	}
	
	//if(intval($deptId)==455){
	//	$totalHours = $totalHours + $provhours;
	//}
				
	
	if((intval($useGrid)==2 || intval($useGrid)==3) && $gridTotal>0 && $rnVariance==5){
				$gridReqd = $gridTotal;
				$gproductivity = (1-(($totalHours - $gridReqd) / $gridReqd)) * 100;
				$ghrsVariance = 0;
				$actualHoursVariance = 0;		
	}else if((intval($useGrid)==2 || intval($useGrid)==6) && $gridTotal>0 && $rnVariance!=5){
				$gridReqd = $gridTotal;
				$gproductivity = (1-(($totalHours - $gridReqd) / $gridReqd)) * 100;
				$ghrsVariance = $gvariance;
				$actualHoursVariance = round(($totalHours - ($hppd * $procedureCount)),2);
	}else if(intval($useGrid)==3 && $gridTotal>0 && $rnVariance!=5){
				$gridReqd = $gridTotal;
				$gproductivity = (1-(($totalHours - $gridReqd) / $gridReqd)) * 100;
				$ghrsVariance = $gvariance;
				$actualHoursVariance = round($gvariance,2);
	}else{
				$gproductivity = 0.00;
				$ghrsVariance = 0.0;
				$actualHoursVariance = 0;
				$actualHoursVariance = round(($totalHours - ($hppd * $procedureCount)),2);
	}
	
	if($rnVariance==5){
		$actualWHP = 0;
	}else if($procedureCount >0 && $totalHours>0){
		$actualWHP = round($totalHours / $procedureCount,2);
	}else if($procedureCount==0 && $totalHours>0){
		$actualWHP = round($totalHours,2);
		//$actualWHP = round($procedureCount / $totalHours,2);	
	}else{
		$actualWHP = 0;
	}
	
	if($hppd >0){
	$aprod = (1-(($actualWHP - $hppd) / $hppd)) * 100;
	}else{
	$aprod =0;	
	}
	
	if($prodCalc==1){
	$aproductivity = round($aprod,1);
	$nvariance = $actualHoursVariance;
	$budgetVar = $nvariance * $avgWage * -1;
	//$ghrsVariance = $ghrsVariance;
	$gbudgetVar = 0 * $gvariance * $avgWage * -1;
	}else{
	$aproductivity = round($aprod,1);
	$nvariance = $actualHoursVariance * -1;
	//$gvariance = $gvariance * -1;
	//$ghrsVariance = $ghrsVariance * -1;
	$budgetVar = $nvariance * $avgWage;	
	$gbudgetVar = 0 * $gvariance * $avgWage;
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
				'accountId' =>$accountId,
				'userId' => $userId,
				'hrsVariance' => $nvariance,
				'ahrsVariance' => $nvariance,
				'ghrsVariance' => $ghrsVariance,
				'customProd' => $gproductivity,
				'gvariance' => $gvariance,
				'nproductivity' => $aproductivity,
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
				'notetrack1' => addslashes($trackNote1),
				'notetrack2' => addslashes($trackNote2),
				'track3' => $track3,
				'notetrack3' => addslashes($trackNote3),
				'notetrack4' => addslashes($trackNote4),
				'track4' => $track4,
				'submittedby' => $submittedby,
				'submitCount' => $plannedRecord,
				'Prov1' => $prov1,
				'provProcs1' => $procs1,
				'provHours1' => $hours1,
				'Prov2' => $prov2,
				'provProcs2' => $procs2,
				'provHours2' => $hours2,
				'Prov3' => $prov3,
				'provProcs3' => $procs3,
				'provHours3' => $hours3,
				'Prov4' => $prov4,
				'provProcs4' => $procs4,
				'provHours4' => $hours4,
				'Prov5' => $prov5,
				'provProcs5' => $procs5,
				'provHours5' => $hours5,
				'Prov6' => $prov6,
				'provProcs6' => $procs6,
				'provHours6' => $hours6,
				'grid1' => $grid1,
				'grid2' => $grid2,
				'grid3' => $grid3,
				'grid4' => $grid4,
				'grid5' => $grid5,
				'grid6' => $grid6,
				'grid7' => $grid7,
				'grid8' => $grid8,
				'grid9' => $grid9,
				'grid10' => $grid10,
				'gridvar1' => $gridvar1,
				'gridvar2' => $gridvar2,
				'gridvar3' => $gridvar3,
				'gridvar4' => $gridvar4,
				'gridvar5' => $gridvar5,
				'gridvar6' => $gridvar6,
				'gridvar7' => $gridvar7,
				'gridvar8' => $gridvar8,
				'gridvar9' => $gridvar9,
				'gridvar10' => $gridvar10
            );
			$updateWhere = array('id' => $dataId);
            
			$newProd = Config::get('db')->update('productiveNewData',$data,$updateWhere);
			
		}else if($indMeasure==1 && $nUser==0 && $type==2){
			
			$data = array(
                'entered' =>date("Y-m-d H:i:s",$currentTime),
				'accountId' =>$accountId,
				'dayDate' =>$dayDate,
				'shift' =>$shift,
				'accountId' =>$accountId,
				'deptId' =>$deptId,
				'userId' => $userId,
				'hrsVariance' => $nvariance,
				'ahrsVariance' => $nvariance,
				'gvariance' => $gvariance,
				'hrsVarianceActual' => 0,
				'procedureCount' => $procedures,
				'ghrsVariance' => $ghrsVariance,
				'nproductivity' => $aproductivity,
				'customProd' => $gproductivity,
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
				'notetrack1' => addslashes($trackNote1),
				'notetrack2' => addslashes($trackNote2),
				'track3' => $track3,
				'notetrack3' => addslashes($trackNote3),
				'notetrack4' => addslashes($trackNote4),
				'track4' => $track4,
				'submittedby' => $submittedby,
				'submitCount' => $plannedRecord,
				'Prov1' => $prov1,
				'provProcs1' => $procs1,
				'provHours1' => $hours1,
				'Prov2' => $prov2,
				'provProcs2' => $procs2,
				'provHours2' => $hours2,
				'Prov3' => $prov3,
				'provProcs3' => $procs3,
				'provHours3' => $hours3,
				'Prov4' => $prov4,
				'provProcs4' => $procs4,
				'provHours4' => $hours4,
				'Prov5' => $prov5,
				'provProcs5' => $procs5,
				'provHours5' => $hours5,
				'Prov6' => $prov6,
				'provProcs6' => $procs6,
				'provHours6' => $hours6,
				'grid1' => $grid1,
				'grid2' => $grid2,
				'grid3' => $grid3,
				'grid4' => $grid4,
				'grid5' => $grid5,
				'grid6' => $grid6,
				'grid7' => $grid7,
				'grid8' => $grid8,
				'grid9' => $grid9,
				'grid10' => $grid10,
				'gridvar1' => $gridvar1,
				'gridvar2' => $gridvar2,
				'gridvar3' => $gridvar3,
				'gridvar4' => $gridvar4,
				'gridvar5' => $gridvar5,
				'gridvar6' => $gridvar6,
				'gridvar7' => $gridvar7,
				'gridvar8' => $gridvar8,
				'gridvar9' => $gridvar9,
				'gridvar10' => $gridvar10
            );
			            
			$newProd = Config::get('db')->insert('productiveNewData',$data);
			$newdataId = Config::get('db')->get_results("select `id` from `productiveNewData` where `provId`={$selectUser} and `dayDate`='{$dayDate}' and `shift`={$shift} and `procedureCount`={$procedureCount}");
			$dataId = $newdataId[0]['id'];
		}else{
			
			$data = array(
                'entered' =>date("Y-m-d H:i:s",$currentTime),
				'accountId' =>$accountId,
				'userId' => $userId,
				'hrsVariance' => $nvariance,
				'ahrsVariance' => $nvariance,
				'gvariance' => $gvariance,
				'hrsVarianceActual' => 0,
				'procedureCount' => $procedures,
				'nproductivity' => $aproductivity,
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
				'gbudgetVar' => $gbudgetVar,
				'budgetValue' => $budgetTotal,
				'type' => $type,
				'provId' => $selectUser,
				'activeRecord' => 1,
				'note' => addslashes($note),
				'budgetVar' => $budgetVar,
				'track1' => $track1,
				'track2' => $track2,
				'notetrack1' => addslashes($trackNote1),
				'notetrack2' => addslashes($trackNote2),
				'track3' => $track3,
				'notetrack3' => addslashes($trackNote3),
				'notetrack4' => addslashes($trackNote4),
				'track4' => $track4,
				'submittedby' => $submittedby,
				'submitCount' => $plannedRecord,
				'Prov1' => $prov1,
				'provProcs1' => $procs1,
				'provHours1' => $hours1,
				'Prov2' => $prov2,
				'provProcs2' => $procs2,
				'provHours2' => $hours2,
				'Prov3' => $prov3,
				'provProcs3' => $procs3,
				'provHours3' => $hours3,
				'Prov4' => $prov4,
				'provProcs4' => $procs4,
				'provHours4' => $hours4,
				'Prov5' => $prov5,
				'provProcs5' => $procs5,
				'provHours5' => $hours5,
				'Prov6' => $prov6,
				'provProcs6' => $procs6,
				'provHours6' => $hours6,
				'grid1' => $grid1,
				'grid2' => $grid2,
				'grid3' => $grid3,
				'grid4' => $grid4,
				'grid5' => $grid5,
				'grid6' => $grid6,
				'grid7' => $grid7,
				'grid8' => $grid8,
				'grid9' => $grid9,
				'grid10' => $grid10,
				'gridvar1' => $gridvar1,
				'gridvar2' => $gridvar2,
				'gridvar3' => $gridvar3,
				'gridvar4' => $gridvar4,
				'gridvar5' => $gridvar5,
				'gridvar6' => $gridvar6,
				'gridvar7' => $gridvar7,
				'gridvar8' => $gridvar8,
				'gridvar9' => $gridvar9,
				'gridvar10' => $gridvar10
            );
			$updateWhere = array('id' => $dataId);
            
			$newProd = Config::get('db')->update('productiveNewData',$data,$updateWhere);
			
		}
		
		if($plannedRecord==1){
			$planneddata = array(
				'dataId' => $dataId,
                'entered' =>date("Y-m-d H:i:s",$currentTime),
				'accountId' =>$accountId,
				'userId' => $userId,
				'hrsVariance' => $nvariance,
				'ahrsVariance' => $nvariance,
				'gvariance' => $gvariance,
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
				'gbudgetVar' => $gbudgetVar,
				'budgetValue' => $budgetTotal,
				'type' => $type,
				'provId' => $selectUser,
				'activeRecord' => 1,
				'note' => addslashes($note),
				'budgetVar' => $budgetVar,
				'track1' => $track1,
				'track2' => $track2,
				'notetrack1' => addslashes($trackNote1),
				'notetrack2' => addslashes($trackNote2),
				'track3' => $track3,
				'notetrack3' => addslashes($trackNote3),
				'notetrack4' => addslashes($trackNote4),
				'track4' => $track4,
				'submittedby' => $submittedby
            );

            
			$newPlanned = Config::get('db')->insert('productiveNewDataPlanned',$planneddata);
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
		
			
			if($actualWHP >0){
				$aMsg = "a ".$hrsTerm." of ".$actualWHP." and ";
				$hrsVar = $nvariance . "hours";
			}else{
				$aMsg = '';
				$hrsVar = '';
			}
			
			$updateMail = sendupdateEmail($dataId,$aMsg,$hrsVar);
			
			$dbAdmin = Config::get('db')->get_results("select n.*, l.id as labelId, l.login as accountLogin, l.alertName, l.noreply, l.labelName, s.dayNight, u.alertTimes, u.mobile, u.txt, u.txtactive, u.txtEscalation, x.textAlerts, u.txtPause FROM `productiveNewData` n LEFT OUTER JOIN `productiveDeptXref` as x on x.deptId = n.deptId LEFT OUTER JOIN `productiveUser` as u on u.id = x.userId LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `productiveAccount` as a on a.id=n.accountId LEFT JOIN `productiveLabel` as l on l.id = a.label where n.id={$dataId} and n.daydate = curdate() and u.txtPause=0 and u.active>=1 and u.role>=5 and x.textAlerts >=1 AND u.mobile !='' AND (s.dayNight=u.alertTimes or u.alertTimes=3) and n.whpPlan=0 group by u.mobile");
			
			if($dbAdmin){
			$txtescalation = $dbAdmin[0]['txtEscalation'];
			$textAlerts = $dbAdmin[0]['textAlerts'];
			$txtactive = $dbAdmin[0]['txtactive'];
			$txtpause = $dbAdmin[0]['txtPause'];
			$noreply = $dbAdmin[0]['noreply'];
			$alertName = $dbAdmin[0]['alertName'];
			}else{
			$txtescalation=0;
			$textAlerts=0;
			$txtactive=0;
			$txtpause=0;
			$noreply="noreply@productivern.com";
			$alertName="ProductiveRN";
			}
			
					
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
			
			if($note==false && intval($rnThresholdHigh) !=999 && ($actualWHP < ($hppd * $thresholdLow) || $actualWHP > ($hppd * $thresholdHigh))){			
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
				'note'=>addslashes($note),
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
				'note'=>addslashes($note),
				'emailAlerts'=>$updateMail
				);				
				}
							
			
			if($dbAdmin){
						
			foreach ($dbAdmin as $dbText){
				$mobile = $dbText['mobile'];
				$accountLogin = $dbText['accountLogin'];
				$message = $alertName . " Report Alert: A ".$prodTerm." Report has been submitted with ".$aMsg."a variance of ". $nvariance ." hours in ". $dep .".  productivern.com/views/v.php?i=" . $dataId . "&l=" . $accountLogin;
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
    $dbEsca = Config::get('db')->get_results("select n.*, IFNULL(d.escalations,0) as escalations, IFNULL(u.txtactive,0) as txtactive, IFNULL(u.txtPause,0) as txtpause, IFNULL(u.txtEscalation,0) as txtescalation, IFNULL(x.escAlerts,0) as escAlerts, IFNULL(x.textAlerts,0) as textAlerts, IFNULL(u.emailAlerts,0) as emailAlerts from `productiveNewData` n LEFT JOIN `ProductiveDept` as d on d.id = n.deptId LEFT OUTER JOIN `productiveDeptXref` as x on x.deptId = n.deptId LEFT OUTER JOIN `productiveUser` as u on u.id = x.userId WHERE n.id={$dataId} and x.escAlerts>0 group by u.mobile");
    
	if ($note && $dataId) {
	  $existing = Config::get('db')->get_results("SELECT note FROM productiveNewData WHERE id=" . intval($dataId) . " LIMIT 1");
	  $existingNote = $existing ? $existing[0]['note'] : '';
	  if(strpos($existingNote, '(*Adjusted for Core Staffing)') !== false && strpos($note, '(*Adjusted for Core Staffing)') === false){
	    $note .= ' (*Adjusted for Core Staffing)';
	  }
	  $note2 = addslashes($note);
      $data = array(
	        'note' =>$note2,
            'userId' =>$userId
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
	$curTime = isset($_REQUEST['currentTime']) ? $_REQUEST['currentTime'] : false;
	$currentTime = strtotime($curTime);
	//$dayDate = date('Y-m-d',strtotime($currentTime));
	
	if(intval($daId)==0 && $deptId && $currentTime){
	$dbescId = Config::get('db')->get_results("select * from `productiveNewData` where `deptId`={$deptId} AND `dayDate`='{$dayDate}' AND `type`!=2 order by `shift` desc");
	$dataId = $dbescId[0]['id'];
    }else{
	$dataId = $daId;
	}
	
	if ($userId && intval($esc) > 0) {
		$data = array(
            'escalation' =>$esc,
			'dataId' =>$dataId,
			'deptId' =>$deptId,
			'accountId' =>$accountId,
			'note' =>Config::get('db')->filter($comment),
            'userId' =>$userId,
			'dateSubmitted' =>date("Y-m-d H:i:s",$currentTime)
        );

        Config::get('db')->insert('productiveEscalations', $data);
		}
		
	if (intval($esc)==10){
		
		$safety = array(
			'deptId' =>$deptId,
			'accountId' =>$accountId,
			'note1' =>Config::get('db')->filter($comment),
            'createdBy' =>$userId,
			'active' =>1,
			'openedDate' =>date("Y-m-d",$currentTime),
			'dayDate' =>date("Y-m-d",$currentTime)
        );

        Config::get('db')->insert('productiveSafety', $safety);
		}
	
		
		
		$dbAdmin = Config::get('db')->get_results("select p.*, (SELECT `id` FROM `productiveEscalations` WHERE `userId`={$userId} order by `id` DESC LIMIT 0,1) as escId, d.escalations, d.prodMeasure, x.textAlerts, IFNULL(x.textVariance,0) as textVariance, a.escalation, l.login, l.id as labelId, l.labelName, l.alertName FROM `productiveUser` p LEFT JOIN `productiveDeptXref` as x on x.userId = p.id AND x.deptId={$deptId} LEFT JOIN `ProductiveDept` as d on d.id=x.deptId LEFT OUTER JOIN `productiveAcctEscalations` as a on a.id={$esc} LEFT OUTER JOIN `productiveAccount` as c on c.id=p.accountId LEFT OUTER JOIN `productiveLabel` as l on l.id=c.label where p.accountId={$accountId} and p.txtPause=0 and p.active>=1 and x.escAlerts>0 and LENGTH(p.mobile)=10 AND d.escalations=0");

				//$alertName = $dbText['labelName'];
		// For no-escalation path, also query textAlerts users
				$dbAdminText = (intval($esc)==0) ? Config::get('db')->get_results("select p.*, l.alertName, IFNULL(x.textVariance,0) as textVariance, IFNULL(x.textAlerts,0) as textAlerts FROM productiveUser p LEFT JOIN productiveDeptXref as x on x.userId=p.id AND x.deptId={$deptId} LEFT JOIN productiveAccount as c on c.id=p.accountId LEFT JOIN productiveLabel as l on l.id=c.label where p.accountId={$accountId} and p.txtPause=0 and p.active>=1 and x.textAlerts=1 and LENGTH(p.mobile)=10") : null;
				$sendList = (intval($esc)==0) ? ($dbAdmin ?: $dbAdminText) : $dbAdmin;
				$metaList = $dbAdmin ?: $dbAdminText;
				if($sendList){
				$alertName = $metaList ? $metaList[0]['alertName'] : '';
				$prodMeasure = $metaList ? $metaList[0]['prodMeasure'] : 0;
				$label = $metaList ? $metaList[0]['login'] : '';
				$escId = $dbAdmin ? $dbAdmin[0]['escId'] : 0;

				$staffInfo = Config::get('db')->get_results("SELECT n.nvariance, d.dept, s.dayNight FROM productiveNewData n JOIN ProductiveDept d ON d.id=n.deptId LEFT JOIN productiveShifts s ON s.id=n.shift WHERE n.id={$dataId} LIMIT 1");
				$sNvariance = $staffInfo ? round(floatval($staffInfo[0]['nvariance']), 0) : 0;
				$sDept = $staffInfo ? $staffInfo[0]['dept'] : '';
				$sDayNight = $staffInfo ? intval($staffInfo[0]['dayNight']) : 0;
				$sNewVar = "RN Variance: " . ($sNvariance > 0 ? "+" : "") . $sNvariance;
				foreach ($sendList as $dbText){
					$mobile = $dbText['mobile'];

				if (intval($esc) == 0) {
					$userTextVariance = floatval($dbText['textVariance']);
					$userAlertTimes = intval($dbText['alertTimes']);
					if ($userTextVariance > 0 && abs($sNvariance) < $userTextVariance) continue;
					if ($userAlertTimes != 3 && $sDayNight != $userAlertTimes) continue;
				}

				$escToken4 = bin2hex(random_bytes(32));
				$escExpires4 = date('Y-m-d H:i:s', strtotime('+12 hours'));
				Config::get('db')->insert('staffing_tokens', array('token'=> $escToken4, 'user_id'=> $dbText['id'], 'account_id'=> $accountId, 'expires_at'=> $escExpires4));
				$newLink = "https://www.productivern.com/manager-view.php?t=" . $escToken4;
				$escalation = $dbText['escalation'];

				if (intval($esc) > 0) {
					$message = "ESCALATION ALERT: " . $escalation . ". " . $alertName . " Report submitted " . $sNewVar . " in " . $sDept . ". " . $newLink;
				} else {
					$message = $alertName . " Report submitted " . $sNewVar . " in " . $sDept . ". " . $newLink;
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
				
				$textId = $dbText['id'];
				$coverage = checkUserCoverage($textId);
				$coverPause = ($coverage) ? $coverage['coverPauseSms'] : 0;
				if (!($coverage && $coverPause == 1)) {
					sendescNow(Array('numbers' => ["1" . $mobile], 'message' => [$xmlMsg]));
				}
				if ($coverage && $coverage['hasMobile'] && ($coverage['coverVarianceMin'] == 0 || abs($sNvariance) >= $coverage['coverVarianceMin'])) {
					$covEscTok = bin2hex(random_bytes(32));
					$covEscExp = date('Y-m-d H:i:s', strtotime('+12 hours'));
					Config::get('db')->insert('staffing_tokens', array('token'=>$covEscTok,'user_id'=>$coverage['userId'],'account_id'=>$accountId,'expires_at'=>$covEscExp));
					$covEscLink = "https://www.productivern.com/manager-view.php?t=" . $covEscTok;
					if (intval($esc) > 0) {
						$covEscMsg = "ESCALATION ALERT:" . $escalation . ". " . $alertName . " Report submitted with a variance of ".($sNvariance >= 0 ? "+" : "").$sNvariance." in " . $sDept . ". " . $covEscLink;
					} else {
						$covEscMsg = $alertName . " Report submitted with a variance of".($sNvariance >= 0 ? "+" : "").$sNvariance." in " . $sDept . ". " . $covEscLink;
					}
					$covEscXml = "<message id=\"".$coverage['mobile'].$now."\"><partnerpassword>".$psword."</partnerpassword><content><![CDATA[".$covEscMsg."]]></content></message>";
					sendescNow(Array('numbers' => ["1" . $coverage['mobile']], 'message' => [$covEscXml]));
				}
				}
				$escalationMail = sendescalationEmail($dataId,$deptId,$esc,$comment,$escId,$label);
				
			}else{
		//do nothing;
			}
		
	
	echo json_encode(array('success' => true, 'note' => 'Added Successfully'));
    return true;
    
}

function getpolicy() {
	$dataId = (isset($_REQUEST['dataId'])?$_REQUEST['dataId']:'');

	$dbProd = Config::get('db')->get_results("SELECT p.*, DATE_FORMAT(p.created,'%m/%d/%Y') as createdDate FROM `policies` p WHERE p.id={$dataId}");
	
	
	if($dbProd){
	
		$data = array(
            'policyModalTitle' =>$dbProd[0]['title'],
			'policyContent' =>$dbProd[0]['content'],
			'policyCreated' =>$dbProd[0]['createdDate'],
        );
    
	echo json_encode(array('success' => true,'data'=>$data));
    return true;    
	
	}else{
	//do nothing;
	}
   
}

/////////////////////////////////////
// GET DETAILS FOR SINGLE USER
function GetEscalation() {
	$dataId = (isset($_REQUEST['dataId'])?$_REQUEST['dataId']:'');

	$dbProd = Config::get('db')->get_results("select e.*, r.userId as responseUser, n.submittedby, r.response as responseText, r.responseTime, d.dept, u.first_name, u.last_name, z.first_name as responseFirst, z.last_name as responseLast, a.escalation as escName FROM `productiveEscalations` e LEFT JOIN `productiveAcctEscalations` as a on a.id=e.escalation LEFT OUTER JOIN `productiveNewData` as n on n.id=e.dataId LEFT OUTER JOIN `ProductiveDept` as d on d.id=e.deptId LEFT OUTER JOIN `productiveEscResponse` as r on r.escId=e.id LEFT OUTER JOIN `productiveUser` as z on z.id=r.userId LEFT OUTER JOIN `productiveUser` as u on u.id=e.userId WHERE e.id={$dataId} ORDER BY r.responseTime DESC");
	$comment = '';
	$comment .= '';
	
	if($dbProd){
	
	$submittedBy = (isset($dbProd[0])) ? $dbProd[0]['submittedby'] : '';
	$escType = $dbProd[0]['escType'];
	$escalation = $dbProd[0]['escName'];
	$escalationName = $dbProd[0]['escalationName'];
	
	if(strlen($submittedBy ?? '')>0){
	$firstName = $submittedBy;
	$lastName = '';
	}else{
	$firstName = $dbProd[0]['first_name'];
	$lastName = $dbProd[0]['last_name'];	
	}
	
	if(intval($escType)>0){
	$escName = $escalation . " - " . $escalationName;
	}else{
	$escName = $escalation;
	}
	
	foreach($dbProd as $resp){
    
	if(intval($resp['responseUser'])>0){
	$comment .= $resp['responseTime'] . ' - ' . $resp['responseFirst'] . ' ' . $resp['responseLast'] . ': ' . $resp['responseText'] . ' < > ';
	}

	}	
		$data = array(
            'dept' =>$dbProd[0]['dept'],
			'id' =>$dbProd[0]['id'],
			'note' =>$dbProd[0]['note'],
			'dateSubmitted' =>$dbProd[0]['dateSubmitted'],
			'first_name' =>$firstName,
			'last_name' =>$lastName,
			'escalationName' =>$escName,
			'response' =>$comment,
			'active' =>$dbProd[0]['active']
        );
    
	echo json_encode(array('success' => true,'data'=>$data));
    return true;    
	
	}else{
	//do nothing;
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
	$active = isset($_REQUEST['active']) ? $_REQUEST['active'] : 1;
	
	if(intval($active)==2){
		$closedId = $userId;
		$closedDate = $currentTime;
	}else{
		$closedId = 0;
		$closedDate = '';
	}
    
	if($dataId){
		
		$update = array(
            'active' =>$active,
			'closedId' =>$closedId,
			'closedDate' =>$closedDate
        );
		$accountId = Config::get('account')['accountId'];
		$updateWhere = array(
            'id' =>$dataId,
            'accountId' =>$accountId
        );

        Config::get('db')->update('productiveEscalations', $update, $updateWhere);
		
		if(strlen($response ?? '')>0){
		$data = array(
            'userId' =>$userId,
			'escId' =>$dataId,
			'responseTime' =>$currentTime,
			'response' =>$response			
        );

        Config::get('db')->insert('productiveEscResponse', $data);
		}
		
		$newId = Config::get('db')->get_results("select MAX(`id`) as id FROM `productiveEscResponse` where `userId`={$userId} AND `escId`={$dataId}");
	
		$newescId = $newId[0]['id'];
		if(intval($newescId)>0){
		$escalationText = sendescalationText($newescId);
		}
	//$escalationMail = sendescalationResponse($dataId);
	echo json_encode(array('success' => true, 'note' => 'Escalation Updated'));
    return true;
    
	}else{
	echo json_encode(array('success' => true, 'note' => 'Nothing to update'));
    return true;
}

}

/////////////////////////////////////
// UPDATE ESCALATION
function UpdateEscalationOld() {
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






/*

         _   _ _____ _____ _     _____ _____ _____ _____ _____
        | | | |_   _|_   _| |   |_   _|_   _|_   _|  ___/  ___|
        | | | | | |   | | | |     | |   | |   | | | |__ \ `--.
        | | | | | |   | | | |     | |   | |   | | |  __| `--. \
        | |_| | | |  _| |_| |_____| |_  | |  _| |_| |___/\__/ /
         \___/  \_/  \___/\_____/\___/  \_/  \___/\____/\____/


*/


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
		$keys = array('account_ID','unit_ID','unit_Name','prod_Measure','UOS_Value','date_shift','date_submitted','submitted_by','nursing_charge_hours','nursing_rn_hours','nursing_custom_rn_hours','nursing_tech_hours','nursing_admin_hours','nursing_sitter_hours','nursing_custom1_hours','nursing_custom2_hours','nursing_custom3_hours','nursing_acuity_1to1','nursing_acuity_1to2','nursing_acuity_1to3','nursing_acuity_1to4','nursing_acuity_1to5','nursing_acuity_1to6','nursing_acuity_custom1','nursing_acuity_custom2','nursing_acuity_custom3','nursing_total_pts','support_UOS','customUOS1','customUOS2','customUOS3','support_hours','support_rn1_hours','support_rn2_hours','support_tech1_hours','support_tech2_hours','support_admin_hours','support_custom1_hours','support_custom2_hours','support_custom3_hours','support_other_hours','support_provider_hours','hrs_variance_submitted','WHPUOS_submitted','prod_submitted','hrs_variance_imported','WHPUOS_imported','totalHrs_imported','indirect_hrs','escalation_Type','escalation_Note','churn_admits','churn_discharges','churn_transfers','churn_custom','visitsSubmitted','hoursSubmitted','hrs1Submitted','hrs2Submitted','hrs3Submitted','report_notes','shift_count','SD','EE','deptID','Provider','dateNew','dateExisting','provName','recId','escResponse','escResponder','referrals','customUOS','customUOS2','customUOS3','nursing_custom10','nursing_custom11','nursing_rn2_hours','nursing_acuity_custom4','rnVariance','gridRNVariance','gridTotalVariance','hrsProductivity','rnProductivity','gridRNProductivity','gridTotalProductivity','gridHrsVariance','rnHrsVariance','gridrnHrsVariance','hrsVariance','trackDischarges','hrsbudgetVar','gridTotalbudgetVariance','rnbudgetVariance','gridrnbudgetVariance','trackItem1','trackItem2','trackItem3','trackItem4','trackNote1','trackNote2','trackNote3','trackNote4','submittedCount','oneto1Count','oneto2Count','oneto3Count','oneto4Count','oneto5Count','oneto6Count','oneto3p5Count','oneto4p5Count','oneto5p5Count','twoto1Count','threeto1Count','Epic','track1Info','track2Info','track3Info','track4Info','epicScore2','pt1Info','pt2Info','pt3Info','pt4Info','nursing1Desc','nursing1Count','nursing2Desc','nursing2Count','nursing3Desc','nursing3Count','nursing4Desc','nursing4Count','nursing5Desc','nursing5Count','nursing6Desc','nursing6Count','nursing7Desc','nursing7Count','nursing8Desc','nursing8Count','nursing9Desc','nursing9Count','nursing10Desc','nursing10Count','prov_AM_1','prov_AM_1_procs','prov_AM_1_hrs','prov_AM_2','prov_AM_2_procs','prov_AM_2_hrs','prov_PM_1','prov_PM_1_procs','prov_PM_1_hrs','prov_PM_2','prov_PM_2_procs','prov_PM_2_hrs','prov_EV_1','prov_EV_1_procs','prov_EV_1_hrs');
     
        fputcsv($output,$keys);
		foreach($dbData2 as $dbDept){
		
			$shiftCount = intval($dbDept['shift_count']);
			
			$deptId = $dbDept['deptId'];
			//$dbData = Config::get('db')->get_results("SELECT a.accountId as account_ID, a.unitId as unit_ID, a.dept as unit_Name, m.name as prod_Measure, a.hppd as UOS_Value, CONCAT(DATE_FORMAT(n.dayDate,'%m/%d/%y'), '  ', s.shift) as date_shift, DATE_FORMAT(n.entered,'%m/%d/%Y %T') as date_submitted, CONCAT(SUBSTRING(i.first_name,1,1), '. ', i.last_name) as submitted_by, (n.chargecount * 24 / {$shiftCount}) as nursing_charge_hours, ((n.antecount+n.ldcount+n.ocount) * 24 / {$shiftCount}) as nursing_rn_hours, ((n.customNurse) * 24 / {$shiftCount}) as nursing_custom_rn_hours, ((n.techcount) * 24 / {$shiftCount}) as nursing_tech_hours, ((n.seccount) * 24 / {$shiftCount}) as nursing_admin_hours, ((n.sittercount) * 24 / {$shiftCount}) as nursing_sitter_hours, ((n.otherNurse1) * 24 / {$shiftCount}) as nursing_custom1_hours, ((n.otherNurse2) * 24 / {$shiftCount}) as nursing_custom2_hours, ((n.otherNurse3) * 24 / {$shiftCount}) as nursing_custom3_hours, n.oneto1 as nursing_acuity_1to1, n.oneto2 as nursing_acuity_1to2, n.oneto3 as nursing_acuity_1to3, n.oneto4 as nursing_acuity_1to4, n.oneto5 as nursing_acuity_1to5, n.oneto6 as nursing_acuity_1to6, n.oneto7 as nursing_acuity_custom1, n.oneto8 as nursing_acuity_custom2, n.oneto9 as nursing_acuity_custom3, (n.oneto1+n.oneto2+n.oneto3+n.oneto4+n.oneto5+n.oneto6) as nursing_total_pts, n.procedureCount as support_UOS, n.whpCustom as custom1visits, n.whpCustom2 as custom2visits, n.whpCustom3 as custom3visits, n.skill1val as support_hours, n.skill2val as support_rn1_hours, n.skill3val as support_rn2_hours, n.skill4val as support_tech1_hours, n.skill5val as support_tech2_hours, n.skill6val as support_admin_hours, n.skill7val as support_custom1_hours, n.skill8val as support_custom2_hours, n.skill9val as support_custom3_hours, n.skill10val as support_other_hours, n.skill11val as support_provider_hours, n.hrsVariance as hrs_variance_submitted, n.actualWHP as WHPUOS_submitted, n.nproductivity as prod_submitted, n.hrsVarianceActual as hrs_variance_imported, n.actualWHPimport as WHPUOS_imported, n.totalHours as totalHrs_imported, n.addResourceHrs as indirect_hrs, IFNULL(l.escalation,'') as escalation_Type, IFNULL(c.note,'') as escalation_Note, n.admits as churn_admits, n.discharges as churn_discharges, n.transfers as churn_transfers, n.customChurn as churn_custom, n.visitsSubmitted, n.hoursSubmitted, n.hrs1Submitted, n.hrs2Submitted, n.hrs3Submitted, n.note as report_notes,(select count(`dayDate`) from `productiveNewData` where `deptId`=n.deptId and `whpPlan`=0 and `dayDate`=n.dayDate) as shift_count, (CASE WHEN r.dayRank >0 THEN r.dayRank ELSE '' END) as SD, (CASE WHEN r.empEngagement>0 THEN r.empEngagement ELSE '' END) as EE, n.deptId as deptID, (CASE WHEN n.type=2 THEN 'Yes' ELSE 'No' END) as provider, (CASE WHEN r.dateNew='0000-00-00' THEN '' ELSE r.dateNew END) as dateNew, (CASE WHEN r.dateExisting='0000-00-00' THEN '' ELSE r.dateExisting END) as dateExst, IFNULL((select CONCAT(SUBSTRING(`first_name`,1,1), '. ', `last_name`) from `productiveUser` where `id`=n.provId),'') as provName, n.id, (select `response` from `productiveEscalations` where `dataId`=0 limit 0,1) as escResponse, (select i.last_name from `productiveUser` i  inner join `productiveEscalations` as b on b.lastupdateId=i.id and b.dataId=0 limit 0,1) as escResponder, r.referrals FROM `productiveNewData` n LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as a on a.id = n.deptId LEFT JOIN `productiveMeasures` as m on m.id=a.prodMeasure LEFT OUTER JOIN `productiveDayRank` as r on r.dayDate=n.dayDate AND r.deptId=n.deptId LEFT JOIN `productiveAccount` as t on t.id = a.accountId LEFT JOIN `productiveUser` as u on u.id ={$userId} LEFT OUTER JOIN `productiveUser` as i on i.id=n.userId LEFT OUTER JOIN `productiveEscalations` as c on c.dataId = n.id LEFT OUTER JOIN `productiveAcctEscalations` as l on l.id = c.escalation WHERE n.deptId={$deptId} AND n.userId>0 AND n.activeRecord>0 AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate $searchAdd2$searchAdd3 group by n.deptId, n.dayDate, n.id ORDER BY n.dayDate DESC, a.dept ASC, n.shift DESC");
			$dbData = Config::get('db')->get_results("SELECT n.*, (CASE WHEN n.Prov1>0 THEN (SELECT CONCAT(`last_name`,', ',`first_name`) FROM `productiveUser` where `id`=n.Prov1) ELSE '' END) as prov1, (CASE WHEN n.Prov2>0 THEN (SELECT CONCAT(`last_name`,', ',`first_name`) FROM `productiveUser` where `id`=n.Prov2) ELSE '' END) as prov2, (CASE WHEN n.Prov3>0 THEN (SELECT CONCAT(`last_name`,', ',`first_name`) FROM `productiveUser` where `id`=n.Prov3) ELSE '' END) as prov3, (CASE WHEN n.Prov4>0 THEN (SELECT CONCAT(`last_name`,', ',`first_name`) FROM `productiveUser` where `id`=n.Prov4) ELSE '' END) as prov4, (CASE WHEN n.Prov5>0 THEN (SELECT CONCAT(`last_name`,', ',`first_name`) FROM `productiveUser` where `id`=n.Prov5) ELSE '' END) as prov5, a.chargeDesc, a.nurseDesc, a.nurse1Desc, a.nurse2Desc, a.techLabel, a.secLabel, a.other1Desc, a.sittersNEWDesc, a.other2Desc, a.other3Desc, a.desc7, a.desc8, a.desc9, a.desc10, e.shiftOrder as aShift, e.epicScore as aEpic, b.epicScore as bEpic, b.shiftOrder as bShift, (CASE WHEN n.shiftOrder=(SELECT max(`shiftOrder`) FROM `productiveAccountXref` where `dept`={$deptId}) THEN b.epicScore ELSE e.epicScore END) as newEpic, b.dayDate as newDate, i.practiceId, a.unitId as unit_ID, a.dept as unit_Name, a.oneto1Acuity, a.oneto2Acuity, a.oneto3Acuity, a.oneto4Acuity, a.oneto5Acuity, a.oneto6Acuity, m.name as prod_Measure, a.hppd as UOS_Value, CONCAT(DATE_FORMAT(n.dayDate,'%m/%d/%y'), '  ', s.shift) as date_shift, DATE_FORMAT(n.entered,'%m/%d/%Y %T') as date_submitted, (CASE WHEN i.practiceId=1 THEN n.submittedby ELSE CONCAT(SUBSTRING(i.first_name,1,1), '. ', i.last_name) END) as submitted_by, (n.chargecount * 24 / {$shiftCount}) as nursing_charge_hours, ((n.antecount+n.ldcount+n.ocount) * 24 / {$shiftCount}) as nursing_rn_hours, ((n.customNurse2) * 24 / {$shiftCount}) as nursing_rn2_hours, ((n.customNurse) * 24 / {$shiftCount}) as nursing_custom_rn_hours, ((n.techcount) * 24 / {$shiftCount}) as nursing_tech_hours, ((n.seccount) * 24 / {$shiftCount}) as nursing_admin_hours, ((n.sittercount) * 24 / {$shiftCount}) as nursing_sitter_hours, ((n.otherNurse1) * 24 / {$shiftCount}) as nursing_custom1_hours, ((n.otherNurse2) * 24 / {$shiftCount}) as nursing_custom2_hours, ((n.otherNurse3) * 24 / {$shiftCount}) as nursing_custom3_hours, n.oneto1 as nursing_acuity_1to1, n.oneto2 as nursing_acuity_1to2, n.oneto3 as nursing_acuity_1to3, n.oneto4 as nursing_acuity_1to4, n.oneto5 as nursing_acuity_1to5, n.oneto6 as nursing_acuity_1to6, n.oneto7 as nursing_acuity_custom1, n.oneto8 as nursing_acuity_custom2, n.oneto9 as nursing_acuity_custom3, n.oneto10 as nursing_acuity_custom4, (n.oneto1+n.oneto2+n.oneto3+n.oneto4+n.oneto5+n.oneto6) as nursing_total_pts, n.procedureCount as support_UOS, n.whpCustom as custom1visits, n.whpCustom2 as custom2visits, n.whpCustom3 as custom3visits, n.whpCustom4 as custom4visits, n.whpCustom5 as custom5visits, n.skill1val as support_hours, n.skill2val as support_rn1_hours, n.skill3val as support_rn2_hours, n.skill4val as support_tech1_hours, n.skill5val as support_tech2_hours, n.skill6val as support_admin_hours, n.skill7val as support_custom1_hours, n.skill8val as support_custom2_hours, n.skill9val as support_custom3_hours, n.skill10val as support_other_hours, n.skill11val as support_provider_hours, n.hrsVariance as hrs_variance_submitted, n.actualWHP as WHPUOS_submitted, n.nproductivity as prod_submitted, n.hrsVarianceActual as hrs_variance_imported, n.actualWHPimport as WHPUOS_imported, n.totalHours as totalHrs_imported, n.addResourceHrs as indirect_hrs, IFNULL(l.escalation,'') as escalation_Type, IFNULL(c.note,'') as escalation_Note, n.admits as churn_admits, n.discharges as churn_discharges, n.transfers as churn_transfers, n.customChurn as churn_custom, n.visitsSubmitted, n.hoursSubmitted, n.hrs1Submitted, n.hrs2Submitted, n.hrs3Submitted, n.note as report_notes,(select count(`dayDate`) from `productiveNewData` where `deptId`=n.deptId and `whpPlan`=0 and `dayDate`=n.dayDate) as shift_count, (CASE WHEN r.dayRank >0 THEN r.dayRank ELSE '' END) as SD, (CASE WHEN r.empEngagement>0 THEN r.empEngagement ELSE '' END) as EE, n.deptId as deptID, (CASE WHEN n.type=2 THEN 'Yes' ELSE 'No' END) as provider, (CASE WHEN r.dateNew='0000-00-00' THEN '' ELSE r.dateNew END) as dateNew, (CASE WHEN r.dateExisting='0000-00-00' THEN '' ELSE r.dateExisting END) as dateExst, IFNULL((select CONCAT(SUBSTRING(`first_name`,1,1), '. ', `last_name`) from `productiveUser` where `id`=n.provId),'') as provName, c.response as escResponse, (select `last_name` from `productiveUser` where `id` = c.lastupdateId) as escResponder, (CASE WHEN r.userId=0 AND r.referrals=0 THEN '' ELSE r.referrals END) as referrals, a.customWHPvalue, a.customWHP2value, a.customWHP3value, a.customWHP4value, a.customWHP5value, (CASE WHEN a.prodMeasure1=2 THEN (n.procedureCount + (n.whpCustom * a.customWHPvalue) + (n.whpCustom2 * a.customWHP2value) + (n.whpCustom3 * a.customWHP3value) + (n.whpCustom4 * a.customWHP4value) + (n.whpCustom5 * a.customWHP5value)) ELSE '' END) as totalVisits FROM `productiveNewData` n LEFT JOIN `productiveNewData` as e on e.deptId=n.deptId and e.dayDate=n.dayDate and e.shiftOrder=(n.shiftOrder+1) LEFT JOIN `productiveNewData` as b on b.deptId=n.deptId and b.dayDate=DATE_SUB(n.dayDate,INTERVAL 1 DAY) and b.shiftOrder=1 LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as a on a.id = n.deptId LEFT JOIN `productiveMeasures` as m on m.id=a.prodMeasure1 LEFT OUTER JOIN `productiveDayRank` as r on r.dayDate=n.dayDate AND r.deptId=n.deptId LEFT JOIN `productiveAccount` as t on t.id = a.accountId LEFT JOIN `productiveUser` as u on u.id ={$userId} LEFT OUTER JOIN `productiveUser` as i on i.id=n.userId LEFT OUTER JOIN `productiveEscalations` as c on c.dataId = n.id LEFT OUTER JOIN `productiveAcctEscalations` as l on l.id = c.escalation WHERE n.deptId={$deptId} AND n.userId>0 AND n.activeRecord>0 AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate group by n.deptId, n.dayDate, n.id ORDER BY n.dayDate DESC, a.dept ASC, n.shift DESC");
			 
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
					'prov_EV_1_hrs' =>$v['provHours5']
					);
					
					fputcsv($output,$out);
				}
			}else{
			//do nothing;
			}
			}
	}else if($dbData2 && INTVAL($deptCount)==1){
		$deptKeys = Config::get('db')->get_results("SELECT (CASE WHEN d.nurse1Desc='' THEN 'nursing_custom_rn_hours' ELSE d.nurse1Desc END) as nurse1Desc, (CASE WHEN d.other1Desc='' THEN 'nursing_custom1_hours' ELSE d.other1Desc END) as other1Desc, (CASE WHEN d.other2Desc='' THEN 'nursing_custom2_hours' ELSE d.other2Desc END) as other2Desc, (CASE WHEN d.other3Desc='' THEN 'nursing_custom3_hours' ELSE d.other3Desc END) as other3Desc, (CASE WHEN d.desc7='' THEN 'nursing_acuity_custom1' ELSE d.desc7 END) as desc7, (CASE WHEN d.desc8='' THEN 'nursing_acuity_custom2' ELSE d.desc8 END) as desc8, (CASE WHEN d.desc9='' THEN 'nursing_acuity_custom3' ELSE d.desc9 END) as desc9, (CASE WHEN d.desc10='' THEN 'nursing_acuity_custom4' ELSE d.desc10 END) as desc10, (CASE WHEN d.skilldesc1='' THEN 'support_hours' ELSE d.skilldesc1 END) as skilldesc1, (CASE WHEN d.skilldesc2='' THEN 'support_rn1_hours' ELSE d.skilldesc2 END) as skilldesc2, (CASE WHEN d.skilldesc3='' THEN 'support_rn2_hours' ELSE d.skilldesc3 END) as skilldesc3, (CASE WHEN d.skilldesc4='' THEN 'support_tech1_hours' ELSE d.skilldesc4 END) as skilldesc4, (CASE WHEN d.skilldesc5='' THEN 'support_tech2_hours' ELSE d.skilldesc5 END) as skilldesc5, (CASE WHEN d.skilldesc6='' THEN 'support_admin_hours' ELSE d.skilldesc6 END) as skilldesc6, (CASE WHEN d.skilldesc7='' THEN 'support_custom1_hours' ELSE d.skilldesc7 END) as skilldesc7, (CASE WHEN d.skilldesc8='' THEN 'support_custom2_hours' ELSE d.skilldesc8 END) as skilldesc8, (CASE WHEN d.skilldesc9='' THEN 'support_custom3_hours' ELSE d.skilldesc9 END) as skilldesc9, (CASE WHEN d.skilldesc10='' THEN 'support_other_hours' ELSE d.skilldesc10 END) as skilldesc10, (CASE WHEN d.skilldesc11='' THEN 'support_provider_hours' ELSE d.skilldesc11 END) as skilldesc11, (CASE WHEN d.customChurnLabel='' THEN 'churn_custom' ELSE d.customChurnLabel END) as customChurnLabel, (CASE WHEN d.customDesc='' THEN 'visits_custom' ELSE d.customDesc END) as customVisits, (CASE WHEN d.customDesc2='' THEN 'visits_custom2' ELSE d.customDesc2 END) as customVisits2, (CASE WHEN d.customDesc3='' THEN 'visits_custom3' ELSE d.customDesc3 END) as customVisits3 FROM `ProductiveDept` d WHERE `id`=(SELECT `deptId` FROM `productiveDeptXref` WHERE `userId`={$userId})");
		$keys = array('account_ID','unit_ID','unit_Name','prod_Measure','UOS_Value','date_shift','date_submitted','submitted_by','nursing_charge_hours','nursing_rn_hours','' . $deptKeys[0]['nurse1Desc'] . '','nursing_tech_hours','nursing_admin_hours','nursing_sitter_hours','' . $deptKeys[0]['other1Desc'] . '','' . $deptKeys[0]['other2Desc'] . '','' . $deptKeys[0]['other3Desc'] . '','nursing_acuity_1to1','nursing_acuity_1to2','nursing_acuity_1to3','nursing_acuity_1to4','nursing_acuity_1to5','nursing_acuity_1to6','nursing_acuity_custom1','nursing_acuity_custom2','nursing_acuity_custom3','nursing_total_pts','support_UOS','' . $deptKeys[0]['customVisits'] . '','' . $deptKeys[0]['customVisits2'] . '','' . $deptKeys[0]['customVisits3'] . '','support_hours','' . $deptKeys[0]['skilldesc2'] . '','support_rn2_hours','support_tech1_hours','support_tech2_hours','support_admin_hours','support_custom1_hours','support_custom2_hours','support_custom3_hours','support_other_hours','support_provider_hours','hrs_variance_submitted','WHPUOS_submitted','prod_submitted','hrs_variance_imported','WHPUOS_imported','totalHrs_imported','indirect_hrs','escalation_Type','escalation_Note','churn_admits','churn_discharges','churn_transfers','churn_custom','submitted_visits','submitted_total_hrs','submitted_hrs1','submitted_hrs2','submitted_hrs3','report_notes','shift_count','SD','EE','deptID','Provider','dateNew','dateExst','provName','escResponse','escResponder','referrals','totalVisits','customUOS4','customUOS5','nursing_custom10','nursing_custom11','nursing_rn2_hours','' . $deptKeys[0]['nurse2Desc'] . '','nursing_acuity_custom4','rnVariance','gridRNVariance','gridTotalVariance','hrsProductivity','rnProductivity','gridRNProductivity','gridTotalProductivity','gridHrsVariance','rnHrsVariance','gridrnHrsVariance','hrsVariance','trackDischarges','hrsbudgetVar','gridTotalbudgetVariance','rnbudgetVariance','gridrnbudgetVariance','trackItem1','trackItem2','trackItem3','trackItem4','trackNote1','trackNote2','trackNote3','trackNote4','submittedCount','oneto1Count','oneto2Count','oneto3Count','oneto4Count','oneto5Count','oneto6Count','oneto3p5Count','oneto4p5Count','oneto5p5Count','twoto1Count','threeto1Count','Epic','track1Info','track2Info','track3Info','track4Info','epicScore2','pt1Info','pt2Info','pt3Info','pt4Info','nursing1Desc','nursing1Count','nursing2Desc','nursing2Count','nursing3Desc','nursing3Count','nursing4Desc','nursing4Count','nursing5Desc','nursing5Count','nursing6Desc','nursing6Count','nursing7Desc','nursing7Count','nursing8Desc','nursing8Count','nursing9Desc','nursing9Count','nursing10Desc','nursing10Count','prov_AM_1','prov_AM_1_procs','prov_AM_1_hrs','prov_AM_2','prov_AM_2_procs','prov_AM_2_hrs','prov_PM_1','prov_PM_1_procs','prov_PM_1_hrs','prov_PM_2','prov_PM_2_procs','prov_PM_2_hrs','prov_EV_1','prov_EV_1_procs','prov_EV_1_hrs');
		fputcsv($output,$keys);
		foreach($dbData2 as $dbDept){
			$shiftCount = intval($dbDept['shift_count']);
			$deptId = $dbDept['deptId'];
			//$dbData = Config::get('db')->get_results("SELECT n.*, a.unitId as unit_ID, a.oneto1Acuity, a.oneto2Acuity, a.oneto3Acuity, a.oneto4Acuity, a.oneto5Acuity, a.oneto6Acuity, a.dept as unit_Name, m.name as prod_Measure, a.hppd as UOS_Value, CONCAT(DATE_FORMAT(n.dayDate,'%m/%d/%y'), '  ', s.shift) as date_shift, DATE_FORMAT(n.entered,'%m/%d/%Y %T') as date_submitted, CONCAT(SUBSTRING(i.first_name,1,1), '. ', i.last_name) as submitted_by, (n.chargecount * 24 / {$shiftCount}) as nursing_charge_hours, ((n.antecount+n.ldcount+n.ocount) * 24 / {$shiftCount}) as nursing_rn_hours, ((n.customNurse2) * 24 / {$shiftCount}) as nursing_rn2_hours, ((n.customNurse) * 24 / {$shiftCount}) as nursing_custom_rn_hours, ((n.techcount) * 24 / {$shiftCount}) as nursing_tech_hours, ((n.seccount) * 24 / {$shiftCount}) as nursing_admin_hours, ((n.sittercount) * 24 / {$shiftCount}) as nursing_sitter_hours, ((n.otherNurse1) * 24 / {$shiftCount}) as nursing_custom1_hours, ((n.otherNurse2) * 24 / {$shiftCount}) as nursing_custom2_hours, ((n.otherNurse3) * 24 / {$shiftCount}) as nursing_custom3_hours, n.oneto1 as nursing_acuity_1to1, n.oneto2 as nursing_acuity_1to2, n.oneto3 as nursing_acuity_1to3, n.oneto4 as nursing_acuity_1to4, n.oneto5 as nursing_acuity_1to5, n.oneto6 as nursing_acuity_1to6, n.oneto7 as nursing_acuity_custom1, n.oneto8 as nursing_acuity_custom2, n.oneto9 as nursing_acuity_custom3, n.oneto10 as nursing_acuity_custom4, (n.oneto1+n.oneto2+n.oneto3+n.oneto4+n.oneto5+n.oneto6) as nursing_total_pts, n.procedureCount as support_UOS, n.whpCustom as custom1visits, n.whpCustom2 as custom2visits, n.whpCustom3 as custom3visits, n.whpCustom4 as custom4visits, n.whpCustom5 as custom5visits, n.skill1val as support_hours, n.skill2val as support_rn1_hours, n.skill3val as support_rn2_hours, n.skill4val as support_tech1_hours, n.skill5val as support_tech2_hours, n.skill6val as support_admin_hours, n.skill7val as support_custom1_hours, n.skill8val as support_custom2_hours, n.skill9val as support_custom3_hours, n.skill10val as support_other_hours, n.skill11val as support_provider_hours, n.hrsVariance as hrs_variance_submitted, n.actualWHP as WHPUOS_submitted, n.nproductivity as prod_submitted, n.hrsVarianceActual as hrs_variance_imported, n.actualWHPimport as WHPUOS_imported, n.totalHours as totalHrs_imported, n.addResourceHrs as indirect_hrs, IFNULL(l.escalation,'') as escalation_Type, IFNULL(c.note,'') as escalation_Note, n.admits as churn_admits, n.discharges as churn_discharges, n.transfers as churn_transfers, n.customChurn as churn_custom, n.visitsSubmitted, n.hoursSubmitted, n.hrs1Submitted, n.hrs2Submitted, n.hrs3Submitted, n.note as report_notes,(select count(`dayDate`) from `productiveNewData` where `deptId`=n.deptId and `whpPlan`=0 and `dayDate`=n.dayDate) as shift_count, (CASE WHEN r.dayRank >0 THEN r.dayRank ELSE '' END) as SD, (CASE WHEN r.empEngagement>0 THEN r.empEngagement ELSE '' END) as EE, n.deptId as deptID, (CASE WHEN n.type=2 THEN 'Yes' ELSE 'No' END) as provider, (CASE WHEN r.dateNew='0000-00-00' THEN '' ELSE r.dateNew END) as dateNew, (CASE WHEN r.dateExisting='0000-00-00' THEN '' ELSE r.dateExisting END) as dateExst, IFNULL((select CONCAT(SUBSTRING(`first_name`,1,1), '. ', `last_name`) from `productiveUser` where `id`=n.provId),'') as provName, c.response as escResponse, (select `last_name` from `productiveUser` where `id` = c.lastupdateId) as escResponder, (CASE WHEN r.userId=0 AND r.referrals=0 THEN '' ELSE r.referrals END) as referrals, a.customWHPvalue, a.customWHP2value, a.customWHP3value, a.customWHP4value, a.customWHP5value, (CASE WHEN a.prodMeasure1=2 THEN (n.procedureCount + (n.whpCustom * a.customWHPvalue) + (n.whpCustom2 * a.customWHP2value) + (n.whpCustom3 * a.customWHP3value) + (n.whpCustom4 * a.customWHP4value) + (n.whpCustom5 * a.customWHP5value)) ELSE '' END) as totalVisits FROM `productiveNewData` n LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as a on a.id = n.deptId LEFT JOIN `productiveMeasures` as m on m.id=a.prodMeasure1 LEFT OUTER JOIN `productiveDayRank` as r on r.dayDate=n.dayDate AND r.deptId=n.deptId LEFT JOIN `productiveAccount` as t on t.id = a.accountId LEFT JOIN `productiveUser` as u on u.id ={$userId} LEFT OUTER JOIN `productiveUser` as i on i.id=n.userId LEFT OUTER JOIN `productiveEscalations` as c on c.dataId = n.id LEFT OUTER JOIN `productiveAcctEscalations` as l on l.id = c.escalation WHERE n.deptId={$deptId} AND n.userId>0 AND n.activeRecord>0 AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate $searchAdd2$searchAdd3 group by n.deptId, n.dayDate, n.id ORDER BY n.dayDate DESC, a.dept ASC, n.shift DESC");
			$dbData = Config::get('db')->get_results("SELECT n.*, (CASE WHEN n.Prov1>0 THEN (SELECT CONCAT(`last_name`,', ',`first_name`) FROM `productiveUser` where `id`=n.Prov1) ELSE '' END) as prov1, (CASE WHEN n.Prov2>0 THEN (SELECT CONCAT(`last_name`,', ',`first_name`) FROM `productiveUser` where `id`=n.Prov2) ELSE '' END) as prov2, (CASE WHEN n.Prov3>0 THEN (SELECT CONCAT(`last_name`,', ',`first_name`) FROM `productiveUser` where `id`=n.Prov3) ELSE '' END) as prov3, (CASE WHEN n.Prov4>0 THEN (SELECT CONCAT(`last_name`,', ',`first_name`) FROM `productiveUser` where `id`=n.Prov4) ELSE '' END) as prov4, (CASE WHEN n.Prov5>0 THEN (SELECT CONCAT(`last_name`,', ',`first_name`) FROM `productiveUser` where `id`=n.Prov5) ELSE '' END) as prov5, a.chargeDesc, a.nurseDesc, a.nurse1Desc, a.nurse2Desc, a.techLabel, a.secLabel, a.other1Desc, a.sittersNEWDesc, a.other2Desc, a.other3Desc, e.shiftOrder as aShift, e.epicScore as aEpic, b.epicScore as bEpic, b.shiftOrder as bShift, (CASE WHEN n.shiftOrder=(SELECT max(`shiftOrder`) FROM `productiveAccountXref` where `dept`={$deptId}) THEN b.epicScore ELSE e.epicScore END) as newEpic, b.dayDate as newDate, i.practiceId, a.unitId as unit_ID, a.dept as unit_Name, a.oneto1Acuity, a.oneto2Acuity, a.oneto3Acuity, a.oneto4Acuity, a.oneto5Acuity, a.oneto6Acuity, a.desc7, a.desc8, a.desc9, a.desc10, m.name as prod_Measure, a.hppd as UOS_Value, CONCAT(DATE_FORMAT(n.dayDate,'%m/%d/%y'), '  ', s.shift) as date_shift, DATE_FORMAT(n.entered,'%m/%d/%Y %T') as date_submitted, (CASE WHEN i.practiceId=1 THEN n.submittedby ELSE CONCAT(SUBSTRING(i.first_name,1,1), '. ', i.last_name) END) as submitted_by, (n.chargecount * 24 / {$shiftCount}) as nursing_charge_hours, ((n.antecount+n.ldcount+n.ocount) * 24 / {$shiftCount}) as nursing_rn_hours, ((n.customNurse2) * 24 / {$shiftCount}) as nursing_rn2_hours, ((n.customNurse) * 24 / {$shiftCount}) as nursing_custom_rn_hours, ((n.techcount) * 24 / {$shiftCount}) as nursing_tech_hours, ((n.seccount) * 24 / {$shiftCount}) as nursing_admin_hours, ((n.sittercount) * 24 / {$shiftCount}) as nursing_sitter_hours, ((n.otherNurse1) * 24 / {$shiftCount}) as nursing_custom1_hours, ((n.otherNurse2) * 24 / {$shiftCount}) as nursing_custom2_hours, ((n.otherNurse3) * 24 / {$shiftCount}) as nursing_custom3_hours, n.oneto1 as nursing_acuity_1to1, n.oneto2 as nursing_acuity_1to2, n.oneto3 as nursing_acuity_1to3, n.oneto4 as nursing_acuity_1to4, n.oneto5 as nursing_acuity_1to5, n.oneto6 as nursing_acuity_1to6, n.oneto7 as nursing_acuity_custom1, n.oneto8 as nursing_acuity_custom2, n.oneto9 as nursing_acuity_custom3, n.oneto10 as nursing_acuity_custom4, (n.oneto1+n.oneto2+n.oneto3+n.oneto4+n.oneto5+n.oneto6) as nursing_total_pts, n.procedureCount as support_UOS, n.whpCustom as custom1visits, n.whpCustom2 as custom2visits, n.whpCustom3 as custom3visits, n.whpCustom4 as custom4visits, n.whpCustom5 as custom5visits, n.skill1val as support_hours, n.skill2val as support_rn1_hours, n.skill3val as support_rn2_hours, n.skill4val as support_tech1_hours, n.skill5val as support_tech2_hours, n.skill6val as support_admin_hours, n.skill7val as support_custom1_hours, n.skill8val as support_custom2_hours, n.skill9val as support_custom3_hours, n.skill10val as support_other_hours, n.skill11val as support_provider_hours, n.hrsVariance as hrs_variance_submitted, n.actualWHP as WHPUOS_submitted, n.nproductivity as prod_submitted, n.hrsVarianceActual as hrs_variance_imported, n.actualWHPimport as WHPUOS_imported, n.totalHours as totalHrs_imported, n.addResourceHrs as indirect_hrs, IFNULL(l.escalation,'') as escalation_Type, IFNULL(c.note,'') as escalation_Note, n.admits as churn_admits, n.discharges as churn_discharges, n.transfers as churn_transfers, n.customChurn as churn_custom, n.visitsSubmitted, n.hoursSubmitted, n.hrs1Submitted, n.hrs2Submitted, n.hrs3Submitted, n.note as report_notes,(select count(`dayDate`) from `productiveNewData` where `deptId`=n.deptId and `whpPlan`=0 and `dayDate`=n.dayDate) as shift_count, (CASE WHEN r.dayRank >0 THEN r.dayRank ELSE '' END) as SD, (CASE WHEN r.empEngagement>0 THEN r.empEngagement ELSE '' END) as EE, n.deptId as deptID, (CASE WHEN n.type=2 THEN 'Yes' ELSE 'No' END) as provider, (CASE WHEN r.dateNew='0000-00-00' THEN '' ELSE r.dateNew END) as dateNew, (CASE WHEN r.dateExisting='0000-00-00' THEN '' ELSE r.dateExisting END) as dateExst, IFNULL((select CONCAT(SUBSTRING(`first_name`,1,1), '. ', `last_name`) from `productiveUser` where `id`=n.provId),'') as provName, c.response as escResponse, (select `last_name` from `productiveUser` where `id` = c.lastupdateId) as escResponder, (CASE WHEN r.userId=0 AND r.referrals=0 THEN '' ELSE r.referrals END) as referrals, a.customWHPvalue, a.customWHP2value, a.customWHP3value, a.customWHP4value, a.customWHP5value, (CASE WHEN a.prodMeasure1=2 THEN (n.procedureCount + (n.whpCustom * a.customWHPvalue) + (n.whpCustom2 * a.customWHP2value) + (n.whpCustom3 * a.customWHP3value) + (n.whpCustom4 * a.customWHP4value) + (n.whpCustom5 * a.customWHP5value)) ELSE '' END) as totalVisits FROM `productiveNewData` n LEFT JOIN `productiveNewData` as e on e.deptId=n.deptId and e.dayDate=n.dayDate and e.shiftOrder=(n.shiftOrder+1) LEFT JOIN `productiveNewData` as b on b.deptId=n.deptId and b.dayDate=DATE_SUB(n.dayDate,INTERVAL 1 DAY) and b.shiftOrder=1 LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT JOIN `ProductiveDept` as a on a.id = n.deptId LEFT JOIN `productiveMeasures` as m on m.id=a.prodMeasure1 LEFT OUTER JOIN `productiveDayRank` as r on r.dayDate=n.dayDate AND r.deptId=n.deptId LEFT JOIN `productiveAccount` as t on t.id = a.accountId LEFT JOIN `productiveUser` as u on u.id ={$userId} LEFT OUTER JOIN `productiveUser` as i on i.id=n.userId LEFT OUTER JOIN `productiveEscalations` as c on c.dataId = n.id LEFT OUTER JOIN `productiveAcctEscalations` as l on l.id = c.escalation WHERE n.deptId={$deptId} AND n.userId>0 AND n.activeRecord>0 AND n.dayDate >=u.lastStartDate AND n.dayDate <=u.lastEndDate group by n.deptId, n.dayDate, n.id ORDER BY n.dayDate DESC, a.dept ASC, n.shift DESC");
			 
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
					'prov_EV_1_hrs' =>$v['provHours5']
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


function CompReports(){
$userId = (isset($_REQUEST['userId']) ? $_REQUEST['userId'] : '');
$check1 = (isset($_REQUEST['check1']) ? $_REQUEST['check1'] : '');
$check2 = (isset($_REQUEST['check2']) ? $_REQUEST['check2'] : '');


if(intval($check1)==2 && (intval($check2)==1 || intval($check2)==0)){
$data = array(
				'unitReports'=>$check2
            );
$updateWhere = array('id' =>$userId);
$newDates = Config::get('db')->update('productiveUser',$data,$updateWhere);
echo json_encode(array('success'=>true,'message'=>'updated'));
return true;
}else if(intval($check2)==2 && (intval($check1)==1 || intval($check1)==0)){
$data = array(
				'complianceReports'=>$check1
            );
$updateWhere = array('id' =>$userId);
$newDates = Config::get('db')->update('productiveUser',$data,$updateWhere);
echo json_encode(array('success'=>true,'message'=>'updated'));
return true;
}else{
echo json_encode(array('success'=>true,'message'=>'no updates'));
return true;	
}

}
/*
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
*/
////Update Epic`
function updateEpic($shiftOrder,$epicScore,$deptId,$dayDate,$shiftCount) {
		
		if(intval($shiftOrder)>1){
		$shiftNew = intval($shiftOrder) - 1;
		//newDate = strtotime($dayDate '-5 days');
		$newDate = date('Y-m-d', strtotime($dayDate));
		$data = array(
				'epicScore'=>$epicScore
            );
			
		$updateWhere = array('deptId' =>$deptId,
							'dayDate' =>$newDate,
							'shiftOrder' =>$shiftNew
							);
		$newDates = Config::get('db')->update('productiveNewData',$data,$updateWhere);
		
		}else{
		$shiftNew = $shiftCount;
		$newDate = date('Y-m-d', strtotime($dayDate . '- 1 days'));
		$data = array(
				'epicScore'=>$epicScore
            );
			
		$updateWhere = array('deptId' =>$deptId,
							'dayDate' =>$dayDate,
							'shiftOrder' =>$shiftNew
							);
		$newDates = Config::get('db')->update('productiveNewData',$data,$updateWhere);
			
		}
		return $output;
}

////SEND Update Email`
function sendupdateEmail($dataId,$aMsg,$hrsVar) {
	
		$dbEmail = Config::get('db')->get_results("select n.*, d.dept, u.first_name, u.email2, x.emailAlerts, l.id as labelId, l.labelName, l.login, l.noreply, l.supportEmail FROM `productiveNewData` n LEFT JOIN `ProductiveDept` as d on d.id = n.deptId LEFT OUTER JOIN `productiveDeptXref` as x on x.deptId = n.deptId LEFT OUTER JOIN `productiveUser` as u on u.id = x.userId LEFT JOIN `productiveAccount` as a on a.id = n.accountId LEFT JOIN `productiveLabel` as l on l.id = a.label where n.id={$dataId} and n.daydate = curdate() AND x.emailAlerts>0 and u.active>=1 AND u.email2 LIKE '%@%' group by u.email2");
		//$output = '';
		//$output = 1;
		if($dbEmail){
		
		foreach($dbEmail as $mail){
		$subject = $mail['labelName'] . " Report Alert";
		$labelName = $mail['labelName'];
		$labelId = $mail['labelId'];
		$noreply = $mail['noreply'];
		$supportEmail = $mail['supportEmail'];
		//$alertName = $mail['labelName'];
		$first = $mail['first_name'];
		$dept = $mail['dept'];
		//$prod = $mail['nproductivity'] . "%";
		//$var = $mail['nvariance'];
		$to = $mail['email2'];
		$link = "views/v.php?i=" . $dataId . "&l=" . $mail['login'];
		$url = "https://www.productivern.com/" . $link;
		
		if(intval($labelId)==3){
			$thanks = "Thank You!";
		}else{
			$thanks = "Thank you for using " . $labelName . "!";
		}
		
		


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
		<th>$thanks</th>
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
		/*
		// Always set content-type when sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		// More headers
		$headers .= 'From: <' . $noreply . '>' . "\r\n";
		mail($to,$subject,$message,$headers);
		*/
		/////////////PHP Mailer
		$mail = new PHPMailer(true);
		$mail->IsSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'productivern.com';                 // Specify main and backup server
		$mail->Port = 465;                                    // Set the SMTP port
		$mail->SMTPAuth = true;
		//$mail->SMTPDebug = 2;		// Enable SMTP authentication
		$mail->Username = 'noreply@productivern.com';                // SMTP username
		$mail->Password = 'QL_X^8z@J)90';                  // SMTP password
		//$mail->Password = 'V0.C%Hajl.vf';
		$mail->SMTPSecure = 'ssl';                            // Enable encryption, 'ssl' also accepted

		$mail->From = 'noreply@productivern.com';
		$mail->FromName = ''. $labelName . '';
		$mail->AddAddress(''.$to.'');  // Add a recipient

		$mail->IsHTML(true);                                  // Set email format to HTML
		$mail->Subject = ''. $subject .'';
		$mail->Body    = ''.$message.'';

		if(!$mail->Send()) {
		   echo 'Message could not be sent.';
		   echo 'Mailer Error: ' . $mail->ErrorInfo;
		   exit;
		}
		
		}
		return true;
		}else{
		return true;
		}
}

////SEND Update Email`
function sendescalationResponse($dataId,$response) {
$userId = Config::get('account')['id'];
$accountId = Config::get('account')['accountId'];
$role = Config::get('account')['role'];
		
		$dbEscalation = Config::get('db')->get_results("select n.*, d.dept, ae.escalation as escType, x.primaryUnit, u.first_name, u.role, u.email2, l.labelName, l.noreply, l.login FROM `productiveEscalations` n LEFT JOIN `ProductiveDept` as d on d.id = n.deptId LEFT OUTER JOIN `productiveAcctEscalations` as ae on ae.id =n.escalation LEFT OUTER JOIN `productiveDeptXref` as x on x.deptId=n.deptId LEFT OUTER JOIN `productiveUser` as u on u.id=x.userId LEFT JOIN `productiveAccount` as a on a.id = n.accountId LEFT JOIN `productiveLabel` as l on l.id = a.label where (u.id=n.userId OR (x.primaryUnit>=5 AND u.role>=5 AND u.emailEscalations>0)) and u.active>=1 AND n.id={$dataId} and u.email2 like '%@%' group by u.email2");
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
		$to = $mail['email2'];
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
		$dbEscalation = Config::get('db')->get_results("select n.*, d.escalations, d.dept, ae.escalation as escType, u.first_name, u.role, u.email2, l.labelName, l.supportEmail, l.noreply, l.login FROM `productiveNewData` n LEFT JOIN `ProductiveDept` as d on d.id = n.deptId LEFT OUTER JOIN `productiveAcctEscalations` as ae on ae.id ={$esc} LEFT OUTER JOIN `productiveDeptXref` as x on x.deptId = n.deptId LEFT OUTER JOIN `productiveUser` as u on u.id =x.userId LEFT JOIN `productiveAccount` as a on a.id = n.accountId LEFT JOIN `productiveLabel` as l on l.id = a.label where x.primaryUnit>=5 AND x.emailAlerts>0 AND u.role>=5 AND n.id={$dataId} and u.active>=1 and d.escalations=0 AND u.email2 like '%@%' group by u.email2");
		}else{
		$dbEscalation = Config::get('db')->get_results("select n.*, a.nickName, d.escalations, d.dept, ae.escalation as escType, u.first_name, u.role, u.email2, l.labelName, l.supportEmail, l.noreply, l.login FROM `productiveNewData` n LEFT JOIN `ProductiveDept` as d on d.id = n.deptId LEFT OUTER JOIN `productiveAcctEscalations` as ae on ae.id ={$esc} LEFT OUTER JOIN `productiveDeptXref` as x on x.deptId = n.deptId LEFT OUTER JOIN `productiveUser` as u on u.id =x.userId LEFT JOIN `productiveAccount` as a on a.id = n.accountId LEFT JOIN `productiveLabel` as l on l.id = a.label where x.primaryUnit>=5 AND x.emailAlerts>0 AND u.role>=5 AND n.deptId={$deptId} and u.active>=1 and d.escalations=0 AND u.email2 like '%@%' group by u.email2");
		}
		
		$output = '';
		
		if($dbEscalation){
		foreach($dbEscalation as $mail){
		$subject = $mail['labelName'] . " Escalation Alert";
		$labelName = $mail['labelName'];
		$nickName = $mail['nickName'];
		$first = $mail['first_name'];
		$dept = $mail['dept'];
		$escType = $mail['escType'];
		$prodMeasure = intval($mail['prodMeasure']);
		$to = $mail['email2'];
		$noreply = $mail['noreply'];
		$supportEmail = $mail['supportEmail'];
		
		if($prodMeasure==2){
		$url = "https://www.productivern.com/views/st.php?c=" . $escId . "&l=" . $label;
		}else{
		$url = "https://www.productivern.com/views/sts.php?c=" . $escId . "&l=" . $label;	
		}

		$message = "<html>
		<head>
		<title>$nickName Escalation Alert for $dept</title>
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
		<th>Thank you!</th>
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
// SEND SMS NOW?
function sendNow($options) {
    //if (is_array($options['numbers']) && count($options['numbers']) > 0) {
    //file_put_contents("sendsmslog.txt", print_r($options,true));
    $post = '';
    $keyword = '';
    $header = Array("Content-Type: application/xml");
    $mobile = '';
    $post = $options['message'];
    //$keyword = $options['keyword'];
    //$slooce = $options['login'];
	$slooce = "jobalarm45";
    //$shortCode = $options['shortCode'];
    //$keyword2 = "JOBALARM58046";
	$keyword3 = "PN18339330602";
    $output = "";

    foreach($options['numbers'] as $k => $n) {
       $mobile = $n;      
	   $url = 'https://jobalarm.cloud.sloocetech.net/slooce_apps/spi/jobalarm45/' . $mobile . '/' . $keyword3 . '/messages/startmt';
       $output .= curl_request($slooce, $url, $header, $post[$k]);
	 
    }
    return $output;
}

function sendescNow($options) {
    //if (is_array($options['numbers']) && count($options['numbers']) > 0) {
    //file_put_contents("sendsmslog.txt", print_r($options,true));
    $post = '';
    $keyword = '';
    $header = Array("Content-Type: application/xml");
    $mobile = '';
    $post = $options['message'];
    //$keyword = $options['keyword'];
    //$slooce = $options['login'];
	$slooce = "jobalarm45";
    //$shortCode = $options['shortCode'];
    //$keyword2 = "JOBALARM58046";
	//$keyword3 = "PN18339330602";
	$keyword4 ="PN18337370906";
    $output = "";

    foreach($options['numbers'] as $k => $n) {
        $mobile = $n;      
	   $url = 'https://jobalarm.cloud.sloocetech.net/slooce_apps/spi/jobalarm45/' . $mobile . '/' . $keyword4 . '/messages/startmt';

        $output .= curl_request($slooce, $url, $header, $post[$k]);

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
    $output = curl_request($slooce, $url, $header, $post);
	
    
   
    return $output; 
    
}

///////////////////////////////////
///SUPPORT REQUEST

function Support() {
//if ( isset($_POST['email']) && isset($_POST['name']) && isset($_POST['message']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ) {
$userId = Config::get('account')['id'];
$name = isset($_REQUEST['name']) && ($_REQUEST['name'] != '') ? $_REQUEST['name'] : '';
$message = isset($_REQUEST['message']) && ($_REQUEST['message'] != '') ? $_REQUEST['message'] : '';
$email = isset($_REQUEST['email']) && ($_REQUEST['email'] != '') ? $_REQUEST['email']  : '';
$type = isset($_REQUEST['type']) && ($_REQUEST['type'] != '') ? $_REQUEST['type'] : '';
$phone = isset($_REQUEST['phone']) && ($_REQUEST['phone'] != '') ? $_REQUEST['phone'] : '';



$test = "/(content-type|bcc:|cc:|to:)/i";
  /*
  foreach ( $_POST as $key => $val ) {
    if ( preg_match( $test, $val ) ) {
	   echo json_encode(array('success'=>false));
      exit();
    }
  }
  */
  
  //$company = (isset($_POST["name"])  && strlen($_POST['name'] ?? '') > 0) ?  ", ".$_POST['company'] : '';
 
  mail( "rstrenger@productivern.com", "ProductiveRN Support Request: ".$type, $name."\r\n\r\n".$phone."\r\n\r\n".$email."\r\n\r\n".$message, "From: ProductiveRN Support <support@productivern.com>"  );
   echo json_encode(array('success'=>true));
}


////////////////////////////////////////
// SEND A WEB CURL REQUEST
function curl_request($user, $url, $header, $postdata = null){
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
