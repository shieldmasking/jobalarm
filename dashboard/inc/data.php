<?php
include "initializer.php";
require_once '../../inc/class.db.php';
require_once '../../inc/class.jatwitter.php';
require_once '../../inc/config.php';
require_once '../../inc/class.phpmailer.php';


// If not logged in, error.
if (!isset($_SESSION['account'])) {
    echo json_encode(array('success'=>false,'message'=>'Not logged In'));
    exit();
}

// Set the user account
Config::set('account',$_SESSION['account']);

// If no account ID, error
if (!isset(Config::get('account')['accountId'])) {
    echo json_encode(array('success'=>false,'message'=>'Not logged In'));
    exit();
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

    ////////////////////////////////
    // LOCATIONS PAGE
    case 'getLocations':
        header('Content-Type: application/json');
        GetLocationsTableData();
        break;
    case 'getJobs':
        header('Content-Type: application/json');
        GetJobsTableData();
        break;
	case 'getallJobs':
        header('Content-Type: application/json');
        GetAllJobsTableData();
        break;
	case 'getUsers':
        header('Content-Type: application/json');
        GetUsersTableData();
        break;
    case 'setJobActive':
        header('Content-Type: application/json');
        SetJobActiveStatus(true);
        break;
    case 'setJobInactive':
        header('Content-Type: application/json');
        SetJobActiveStatus(false);
        break;
	case 'removeUser':
        header('Content-Type: application/json');
        RemoveUser(false);
        break;
    case 'addJob':
        header('Content-Type: application/json');
        AddJob();
        break;
	case 'addUser':
        header('Content-Type: application/json');
        AddUser();
        break;
	case 'addLocation':
        header('Content-Type: application/json');
        AddLocation();
        break;
	case 'getLocationDetails':
        header('Content-Type: application/json');
        GetLocationDetails();
        break;
	case 'updateLocation':
        header('Content-Type: application/json');
        UpdateLocation();
        break;
	case 'deleteLocation':
        header('Content-Type: application/json');
        DeleteLocation();
        break;
	case 'getJobDetails':
        header('Content-Type: application/json');
        GetJobDetails();
        break;
	case 'updateJob':
        header('Content-Type: application/json');
        UpdateJob();
        break;
	case 'updateallJob':
        header('Content-Type: application/json');
        UpdateAllJob();
        break;
		
	    
    ////////////////////////////////
    // CANDIDATES PAGE
    case 'getCandidates':
        header('Content-Type: application/json');
        GetCandidatesTableData();
        break;
    case 'getCandidateDetails':
        header('Content-Type: application/json');
        GetCandidateDetails();
        break;
    case 'updateCandidate':
        header('Content-Type: application/json');
        UpdateCandidate();
        break;
    case 'getCandidateSMSHistory':
        header('Content-Type: application/json');
        GetCandidateSMSHistory();
        break;
    case 'getCandidateNoteHistory':
        header('Content-Type: application/json');
        GetCandidateNoteHistory();
        break;
    case 'addNote':
        header('Content-Type: application/json');
        AddCandidateNote();
        break;
	case 'forwardemail':
        header('Content-Type: application/json');
        forwardEmail();
        break;
	
		
////////////////////////////////
    // RECRUITER PAGE
    case 'getRecruiter':
        header('Content-Type: application/json');
        GetRecruiterTableData();
        break;/*
    case 'getCandidateDetails':
        header('Content-Type: application/json');
        GetCandidateDetails();
        break;
    case 'updateCandidate':
        header('Content-Type: application/json');
        UpdateCandidate();
        break;
    case 'getCandidateSMSHistory':
        header('Content-Type: application/json');
        GetCandidateSMSHistory();
        break;
    case 'getCandidateNoteHistory':
        header('Content-Type: application/json');
        GetCandidateNoteHistory();
        break;
    case 'addNote':
        header('Content-Type: application/json');
        AddCandidateNote();
        break;
		*/
		
////////////////////////////////
    // USEER PAGE
    case 'getUserDetails':
        header('Content-Type: application/json');
        GetUserDetails();
        break;
	case 'getAllUsers':
        header('Content-Type: application/json');
        GetAllUsers();
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
	case 'updateUser':
        header('Content-Type: application/json');
        UpdateUser();
        break;
	case 'appclick':
        header('Content-Type: application/json');
        appClick();
        break;
	case 'uploadfile':
        header('Content-Type: application/json');
        uploadFile();
        break;
    

    ////////////////////////////////
    // SMS INBOX PAGE
    case 'getMessages':
        header('Content-Type: application/json');
        GetMessagesTableData();
        break;

    ////////////////////////////////
    // GENERAL FUNCTIONS
    case 'sendSMS':
        header('Content-Type: application/json');
        SendSMS();
        break;
    case 'updateGroup':
        header('Content-Type: application/json');
        UpdateGroup();
        break;
    case 'downloadReport':
        header("Content-type: text/x-csv");
        DownloadCSV();
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
    $startDate = isset($_REQUEST['start']) ? $_REQUEST['start'] : '2010-01-01';
    $startDate .= " 00:00:00";
    $endDate = isset($_REQUEST['end']) ? $_REQUEST['end'] : '2020-01-01';
    $endDate .= " 23:59:59";
    
    $accountId = Config::get('account')['accountId'];

    $canData = Config::get('db')->get_results("
		
        SELECT 
           COUNT(`subscribeDate`) AS canTotal
        FROM
            `candidateXref`
        WHERE
            `accountId`={$accountId}
        AND 
            `subscribeDate` >= '{$startDate}' AND 
            `subscribeDate` <= '{$endDate}'          
            
    ");
	

    $totalCan = (isset($canData[0])) ? $canData[0]['canTotal'] : 0;
	
	$mData = Config::get('db')->get_results("
		
        SELECT COUNT(x.subscribeDate) AS mTotal
        FROM
            `candidateXref` x
		LEFT JOIN 
			`candidate` as c on c.id=x.candidateId
        WHERE
            x.accountId={$accountId} AND c.zip !=''
        AND 
            x.subscribeDate >= '{$startDate}' AND 
            x.subscribeDate <= '{$endDate}'          
            
    ");

    $mTotal = (isset($mData[0])) ? $mData[0]['mTotal'] : 0;

    $canGraphData = Config::get('db')->get_results("
        SELECT 
            *, COUNT(`subscribeDate`) AS daycount, DATE_FORMAT(`subscribeDate`,'%m-%d') as groupDate
        FROM
            `candidateXref`
        WHERE
            `accountId`={$accountId} AND 
            `subscribeDate` >= '{$startDate}' AND 
            `subscribeDate` <= '{$endDate}' 
                
        GROUP BY DATE(`subscribeDate`)    
    ");

    $newCandidateGraphLabels = array();
    $newCandidateGraphData = array();

    if (count($canGraphData) > 0) {
        foreach ($canGraphData as $graphItem) {
            $newCandidateGraphLabels[] = $graphItem['groupDate'];
            $newCandidateGraphData[] = $graphItem['daycount'];
        }
    }
	/*
    $promoData = Config::get('db')->get_results("
      SELECT count(`candidateId`) as promoTotal 
      FROM `candidateXref` 
      WHERE 
      `accountId`={$accountId} and 
      `promoMktng`>0 AND 
      `subscribeDate` >=  '{$startDate}' AND
      `subscribeDate` <= '{$endDate}'
    ");
	
	$promoData = Config::get('db')->get_results("
      SELECT count(`mobile`) as promoTotal 
      FROM `reward_Clicks` 
      WHERE `brandId` in (SELECT `id` FROM `sms_brand` where `accountId`={$accountId})
    ");
	*/
	$promoData = Config::get('db')->get_results("
        SELECT 
        count(`candidateId`) as promoTotal
        FROM `clickTrack` 
         WHERE
            `jobaccountId`={$accountId} 
        AND 
            `datetime` >= '{$startDate}' AND 
            `datetime` <= '{$endDate}'          
            
    ");
	
    $totalPromo = (isset($promoData[0])) ? $promoData[0]['promoTotal'] : 0;
    if($totalCan==0){
	$percentPromo = 0.0;
	}else{
	$promoPerc = $totalPromo / $totalCan * 100;
    $promoPercent = number_format((float)$promoPerc, 1, '.', '');
    $percentPromo = $totalPromo . " (" . $promoPercent . "%)";
	}
	
	$rewardData = Config::get('db')->get_results("
        SELECT 
        count(`id`) as rewardTotal
        FROM `reward_Clicks` 
        WHERE
            `accountId`={$accountId} 
        AND 
            `clickDate` >= '{$startDate}' AND 
            `clickDate` <= '{$endDate}'          
            
    ");
	
    $rewardPromo = (isset($rewardData[0])) ? $rewardData[0]['rewardTotal'] : 0;
    if(intval($mTotal)==0){
	$percentReward = 0.0;
	}else{
	$rewardPerc = $rewardPromo / $mTotal * 100;
    $rewardPercent = number_format((float)$rewardPerc, 1, '.', '');
    $percentReward = $rewardPromo . " (" . $rewardPercent . "%)";
	}
	/*
    $smsData = Config::get('db')->get_results("
      SELECT count(*) as msgTotal 
      FROM `sms_messages` 
      WHERE 
      `msgDate` >= '{$startDate}' AND 
      `msgDate` <= '{$endDate}' AND
      `type`=1 and
      `accountId`={$accountId}
    ");
	*/
	$smsData = Config::get('db')->get_results("
      SELECT count(*) as msgTotal 
      FROM `job` 
      WHERE 
      `status`>0 and
      `postId`={$accountId}
    ");

    $totalMsg = (isset($smsData[0])) ? $smsData[0]['msgTotal'] : 0;

	/*
    $smsGraphData = Config::get('db')->get_results("
      SELECT count(*) as msgTotal,DATE(`msgDate`) as groupDate
      FROM `sms_messages` 
      WHERE 
      `msgDate` >= '{$startDate}' AND 
      `msgDate` <= '{$endDate}' AND
      `type`=1 and
      `accountId`={$accountId}
      GROUP BY DATE(`msgDate`)
    ");
	*/
	
	 $smsGraphData = Config::get('db')->get_results("
      SELECT count(distinct `candidateId`) as msgTotal, DATE_FORMAT(`datetime`,'%m-%d') as groupDate
      FROM `clickTrack` 
      WHERE 
      `datetime` >= '{$startDate}' AND 
      `datetime` <= '{$endDate}' AND
      `jobaccountId`={$accountId}
      GROUP BY DATE(`datetime`)
    ");
	
    $smsSentGraphLabels = array();
    $smsSentGraphData = array();

    if (count($smsGraphData) > 0) {
        foreach ($smsGraphData as $graphItem) {
            $smsSentGraphLabels[] = $graphItem['groupDate'];
            $smsSentGraphData[] = $graphItem['msgTotal'];
        }
    }
	/*
    $ctrData = Config::get('db')->get_results("
        SELECT 
        count(distinct c.candidateId) as ctrTotal
        FROM `candidateXref` c
       LEFT JOIN `clickTrack` as x on x.candidateId = c.candidateId
        WHERE
            x.jobaccountId={$accountId} 
        AND 
            c.subscribeDate >= '{$startDate}' AND 
            c.subscribeDate <= '{$endDate}'          
            
    ");
	*/
	$ctrData = Config::get('db')->get_results("
        SELECT 
        count(distinct `candidateId`) as ctrTotal
        FROM `clickTrack` 
         WHERE
            `jobaccountId`={$accountId} 
        AND 
            `datetime` >= '{$startDate}' AND 
            `datetime` <= '{$endDate}'          
            
    ");

    $totalCTR = (isset($ctrData[0])) ? $ctrData[0]['ctrTotal'] : 0;
    $ctrP = $totalCTR / $totalCan * 100;
    $ctrPercent = number_format((float)$ctrP, 1, '.', '');
    $percentCTR = $totalCTR . " (" . $ctrPercent . "%)";

    $outData = array();

    $outData['totalPromo'] = $percentPromo;
	$outData['totalReward'] = $percentReward;
    $outData['totalMsg'] = $totalMsg;
    $outData['totalCan'] = $totalCan;
    $outData['totalCTR'] = $percentCTR;
    $outData['canGraphData'] = array('labels'=>$newCandidateGraphLabels,'data'=>$newCandidateGraphData);
    $outData['smsGraphData'] = array('labels'=>$smsSentGraphLabels,'data'=>$smsSentGraphData);

    echo json_encode(array('success' => true, 'data' => $outData), JSON_NUMERIC_CHECK);
}

/////DOWNLOAD REPORT DATA TO CSV

function DownloadCSV() {
	
	$startDate = isset($_REQUEST['start']) ? $_REQUEST['start'] : '2010-01-01';
    $endDate = isset($_REQUEST['end']) ? $_REQUEST['end'] : '2020-01-01';
    $accountId = Config::get('account')['accountId'];
	
	$query = ("SELECT 
	x.subscribeDate as `Subscribe Date`, 
	x.keyword2      as `Keyword`, 
	c.mobile        as `Mobile Num`, 
	c.email         as `Email`, 
	c.first_name    as `First Name`, 
	c.last_name     as `Last Name`, 
	ce.city         as `City`, 
	ce.state_code   as `State`, 
	c.zip           as `Zip`, 
	c.resume        as `Resume`, 
	j.title         as `Title`, 
	x.promo         as `Promo`, 
	x.promoMktng    as `Promo Mktg` 
	from`candidateXref` x left join `candidate` as c on c.id = x.candidateId 
	left outer join `cities_extended` as ce on ce.zip = c.zip 
	left outer join`clickTrack` as ct on ct.mobile = c.mobile 
	left outer join `job` as j on j.twitterId = ct.trackId 
	where x.accountId ='{$accountId}' and `subscribeDate` BETWEEN '{$startDate}' AND '{$endDate}' 
	group by x.candidateId order by x.subscribeDate DESC");
	
	$dbData = Config::get('db')->get_results($query);

    $csv_filename = "JobAlarm_" . date('Y-m-d') . ".csv";

    $output = fopen("php://output",'w') or die("Can't open php://output");
    header("Content-Disposition: attachment; filename=".$csv_filename."");

    if (count($dbData) > 0) {
	    $firstRow = $dbData[0];
	    $keys = array_keys($firstRow);
        fputcsv($output,$keys);
        foreach($dbData as $k=>$v)
            fputcsv($output,$v);
    }

    return true;
}


/*

         _     _____ _____   ___ _____ _____ _____ _   _  _____    ______  ___  _____  _____
        | |   |  _  /  __ \ / _ \_   _|_   _|  _  | \ | |/  ___|   | ___ \/ _ \|  __ \|  ___|
        | |   | | | | /  \// /_\ \| |   | | | | | |  \| |\ `--.    | |_/ / /_\ \ |  \/| |__
        | |   | | | | |    |  _  || |   | | | | | | . ` | `--. \   |  __/|  _  | | __ |  __|
        | |___\ \_/ / \__/\| | | || |  _| |_\ \_/ / |\  |/\__/ /   | |   | | | | |_\ \| |___
        \_____/\___/ \____/\_| |_/\_/  \___/ \___/\_| \_/\____/    \_|   \_| |_/\____/\____/


*/


/////////////////////////////////////////
// GET CAND. COUNT BY LOC/BRAND FROM DATA
function GetCandidateCountByLocation($dbData,$accountId,$lat,$lon,$miles) {
    $candCount = 0;

    foreach($dbData as $k=>$candidate) {
        if (($candidate['accountId'] == $accountId) &&
            (3963*acos(sin($lat/57.2958)*sin($candidate['latitude']/57.2958)+ cos($lat/57.2958) *cos($candidate['latitude']/57.2958)*cos(($candidate['longitude']/57.2958)-($lon/57.2958))))<=$miles
        ) {
            $candCount+=$candidate['ccount'];
        }
    }

    return $candCount;
}

/////////////////////////////////////////
// GET CAND. COUNT BY LOC/BRAND FROM DATA
function GetCandidateZipListByLocation($dbData,$accountId,$lat,$lon,$x1,$x2,$y1,$y2,$miles) {
    $candZips = array();

    foreach($dbData as $k=>$candidate) {
        if (($candidate['latitude'] < $x1) &&
            ($candidate['latitude'] > $x2) &&
            ($candidate['longitude'] < $y1) &&
            ($candidate['longitude'] > $y2) &&
            ($candidate['accountId'] == $accountId) &&
            (3963*acos(sin($lat/57.2958)*sin($candidate['latitude']/57.2958)+ cos($lat/57.2958) *cos($candidate['latitude']/57.2958)*cos(($candidate['longitude']/57.2958)-($lon/57.2958))))<=15
        ) {
            $candZips[] = $candidate['zip'];
        }
    }

    return $candZips;
}

/////////////////////////////////////
// GET LOCATIONS TABLE INFO
function GetLocationsTableData() {
    $userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
    $role = Config::get('account')['role'];

//    $dataTableOptions = isset($_REQUEST['datatable']) ? $_REQUEST['datatable'] : array();

//    $perpage = 10;
//    $page = 1;
//    $field = 'ZipCode';
//    $sort = 'asc';

//    if (count($dataTableOptions) > 0) {
//        $perpage = $dataTableOptions['pagination']['perpage'];
//        $page = $dataTableOptions['pagination']['page'];
//        $sort = $dataTableOptions['sort']['sort'];
//        $field = $dataTableOptions['sort']['field'];
//
//    }
//
//    $limitStart = ($page-1)*$perpage;
//
/*
    $brandQuery = "SELECT * FROM account_brand WHERE accountId=$accountId";

    $dbData = Config::get('db')->get_results($brandQuery);

    $brand = $dbData[0]['brandId'];
*/	
	if(intval($accountId)==126){
	   $accountAdd2 = " AND (m.accountId=126 OR m.accountId=443) ";
   }else{
	   $accountAdd2 = " AND m.accountId={$accountId} ";
   }
   
	if (intval($role)==3){
		$roleAdd = " AND xs.userId = {$userId}";
		$join = " LEFT OUTER JOIN `assign_store` as xs on xs.storeId = m.id";
	}else{
		$roleAdd = '';
		$join = '';
	}
        $query = "
        SELECT SQL_CALC_FOUND_ROWS
        m.*, s.storeBrand,s.textLimit , cit.*
        FROM `sms_stores` as m 
		LEFT OUTER JOIN `cities_extended` cit on cit.zip = m.zip
		{$join}
		LEFT JOIN `sms_brand` as s on s.id = m.brandId
		WHERE m.active > 0 $accountAdd2
		GROUP BY m.id ORDER BY m.zip ASC
		";

    $dbData = Config::get('db')->get_results($query);
    $query = "SELECT FOUND_ROWS() AS found_rows;";
    $countData = Config::get('db')->get_results($query);
    $total = $countData[0]['found_rows'];
    $outJobs = array();
    $jobArray = array();
    $jobArrayTemp = array();
    $radius = 10;
    //$brand = 0;
    $link = 0;

    //$brandcandidates = Config::get('db')->get_results("SELECT c.*, x.keyword as keyword FROM `candidate` as c LEFT JOIN candidateXref as x on x.candidateId = c.id WHERE x.brandId={$brand} and c.active=1 and (x.promo=1 or x.promo=2)");

   if(intval($accountId)==126){
	   $accountAdd = " AND (x.accountId=126 OR x.accountId=443) ";
   }else{
	   $accountAdd = " AND x.accountId={$accountId} ";
   }

   $canQuery = "
             SELECT 
             count(x.brandId) as ccount,
             ce.latitude as latitude,
             ce.longitude as longitude,
             x.brandId as xrefBrandId,
			 x.accountId as accountId,
             ce.zip as zip             
             FROM `candidate` as c 
             LEFT JOIN `candidateXref` as x on x.candidateId = c.id 
             LEFT JOIN `cities_extended` ce on ce.zip = c.zip 
             WHERE 
             c.zip > 0 and 
             c.active=1 and 
             x.promo>0
			 $accountAdd
             group by x.accountId,latitude,longitude
            ";

    $candidates = Config::get('db')->get_results($canQuery);

    foreach ($dbData as $job) {
        $storeNum = $job['storeNum'];
        $zip = $job['zip'];
        //$sessionAccount = $_SESSION['account']['accountId'] * 12345;
        //$sessionId = $_SESSION['account']['id'] * 54321;

        if (intval($zip) > 0) {
            $zipOrig = substr($zip, 0, 1);

            if (intval($zipOrig) > 0) {
                $zipLow = intval($zipOrig) - 1;
            } else {
                $zipLow = $zipOrig;
            }

            $zipHigh = intval($zipOrig) + 1;
        }

        $brand = $job['accountId'];
        $limit = 4;
        $miles = 18;
        $lat = $job['latitude'];
        $lon = $job['longitude'];
        

        $search_add = '';
        //$search_add = " or x.brandId=6 or x.brandId=19";

        $totalCandidates = 0;
        if (intval($zip) > 0) {
            $totalCandidates = GetCandidateCountByLocation($candidates,$accountId,$lat,$lon,$miles);
            //$candidates = Config::get('db')->get_results("SELECT x.candidateId, c.zip FROM `candidateXref` as x LEFT JOIN `candidate` as c on x.candidateId = c.id LEFT JOIN cities_extended ce on ce.zip = c.zip WHERE c.zip > 0 and ce.latitude < $x1 and ce.latitude > $x2 and ce.longitude < $y1 and ce.longitude > $y2 and x.brandId={$brand} and c.active=1 and (x.promo=1 or x.promo=2) and (3963*ACOS(SIN({$lat}/57.2958)*SIN(ce.latitude/57.2958)+ COS({$lat}/57.2958) *COS(ce.latitude/57.2958)*COS((ce.longitude/57.2958)-({$lon}/57.2958))))<=$miles");
        }

//        $query = "SELECT FOUND_ROWS() AS found_rows;";
//        $countData = Config::get('db')->get_results($query);
//
//        if (!$candidates) {
//            $totalCandidates = 0;
//        } else {
//            $brand = $job['brandId'];
//            $totalCandidates = $countData[0]['found_rows'];
//        }
        //$candidateLink = '<a href="http://admin.jobalarm.com/globals?z='.$zip.'&u='.$account_data['id'].'&b='.$brand.'">'.$totalCandidates.'</a>';
        //$candidateLink = '<a href="http://admin.jobalarm.com/login/smslogin/'.$sessionAccount.'/'.$sessionId.'/'.$zip.'/0" target=\"_blank\">'.$totalCandidates.'</a>';
        $candidateLink = "<a href=\"#candidates?zip=".$job['zip']."\" >" . $totalCandidates . "</a>";

        $storeBrand = '';
        $address = $job['address'];

        if ($storeNum) {
            //$storeBrand .= $job['storeBrand'] . " (" . $job['storeNum'] . ")";
			$storeBrand = '<a href="javascript:;" onclick="tj.editLocation('. $job['id'] .');return false;" data-toggle="modal" data-target="#edit_location">'. $job['storeBrand'] . ' (' . $job['storeNum'] . ')</a>';
        } else {
            //$storeBrand = $job['storeBrand'];
			$storeBrand = '<a href="javascript:;" onclick="tj.editLocation('. $job['id'] .');return false;" data-toggle="modal" data-target="#edit_location">'. $job['storeBrand'] .'</a>';
        }
        if ($address) {
            $address = $job['address'] . ", " . $job['city'] . ", " . $job['st'] . ", " . $job['zip'];
        } else {
            $address = $job['city'] . ", " . $job['st'] . ", " . $job['zip'];
        }

        $storeNameAlex = str_replace("'", "", $job['storeBrand']);

        $storeButton = "";
		
		if(intval($role)<5){
        $storeButton .= "<button class=\"btn btn-primary\" onclick=\"tj.showLocationJobs(" . $job['id'] . ", &#39;" . $job['address'] . "&#39;,&#39;" . $job['city'] . "&#39;,&#39;" . $job['st'] . "&#39;,&#39;" . $job['zip'] . "&#39;,&#39;" . $storeNameAlex . "&#39;,&#39;" . $job['storeNum'] . "&#39;);\" data-toggle=\"modal\" data-target=\"#job_modal\">Jobs</button>";
		}else{
		$storeButton .= "<button class=\"btn btn-primary\" onclick=\"tj.showLocationJobs(" . $job['id'] . ", &#39;" . $job['address'] . "&#39;,&#39;" . $job['city'] . "&#39;,&#39;" . $job['st'] . "&#39;,&#39;" . $job['zip'] . "&#39;,&#39;" . $storeNameAlex . "&#39;,&#39;" . $job['storeNum'] . "&#39;);\" data-toggle=\"modal\" data-target=\"#job_modal\">Jobs</button> <button class=\"btn btn-info\" onclick=\"tj.showLocationUsers(" . $job['id'] . ", &#39;" . $job['address'] . "&#39;,&#39;" . $job['city'] . "&#39;,&#39;" . $job['st'] . "&#39;,&#39;" . $job['zip'] . "&#39;,&#39;" . $storeNameAlex . "&#39;,&#39;" . $job['storeNum'] . "&#39;);\" data-toggle=\"modal\" data-target=\"#users_modal\">Users</button>";
		//<button class=\"btn btn-sm red\" onclick=\"tj.editLocation(" . $job['id'] . ", &#39;" . $job['address'] . "&#39;,&#39;" . $job['city'] . "&#39;,&#39;" . $job['st'] . "&#39;,&#39;" . $job['zip'] . "&#39;,&#39;" . $storeNameAlex . "&#39;,&#39;" . $job['storeNum'] . "&#39;);\" data-toggle=\"modal\" data-target=\"#edit_location\">Edit</button>";
		}
        $jobArray[] = array(
            'ZipCode'=>$job['zip'],
            'RecordID'=>$job['id'],
            'StoreBrand'=>$storeBrand,
            'Address'=>$address,
            'CandidateLink'=>$candidateLink
        );
    }
    $outData = array();
    $outData['data'] = $jobArray;
    echo json_encode($outData,JSON_NUMERIC_CHECK);
}

/////////////////////////////////////
// GET DETAILS FOR SINGLE LOCATION
function GetLocationDetails() {
    $storeId = (isset($_REQUEST['storeId'])) ? $_REQUEST['storeId'] : 0;
	//echo "store:".$storeId;
    if ($storeId > 0) {
        $dbLocation = Config::get('db')->get_results("select m.*, b.storeBrand from `sms_stores` m LEFT JOIN `sms_brand` as b on b.id = m.brandId where m.id={$storeId}");
        $dbData = $dbLocation[0];
        echo json_encode(array('success' => true,'data'=>$dbData));
        return true;
    }
    echo json_encode(array('success'=>false,'message'=>'Location not found.'));
    return false;
}

/////////////////////////////////////
// GET LOCATION JOBS INFO
function GetJobsTableData() {

    $storeId = isset($_REQUEST['storeId']) ? $_REQUEST['storeId'] : 0;

    if ($storeId == 0) {

        $jobArray = array();

    } else {

        $userId = Config::get('account')['id'];
        $accountId = Config::get('account')['accountId'];
        $role = Config::get('account')['role'];
        $status = Config::get('account')['status'];

        $dbZip = Config::get('db')->get_results("select * from `sms_stores` where id={$storeId}");
        $storezip = $dbZip[0]['zip'];
        $brandId = $dbZip[0]['brandId'];
        $city = strval($dbZip[0]['city']);
        $state = strval($dbZip[0]['st']);
        $storeNum = $dbZip[0]['storeNum'];


        //echo "store ".$storeId;
        //echo "user ".$user;
        //echo "role ".$role;

        //$query = "SELECT j.*,(c.facebook + c.jobalarm + c.text) as clickCount, count(x.id) as autoCount, b.storeBrand FROM `job` j LEFT JOIN `sms_brand` as b on b.id = j.brand LEFT JOIN `clicks` as c on c.trackId = j.twitterId LEFT JOIN jobautopostXref as x on x.trackId = j.twitterId where (((j.zipCode={$storezip} or (j.city ='{$city}' and j.state='{$state}')))  and j.brand={$brandId}) group by j.id order by j.status DESC";

        if (intval($status) == 2) {
            $query = "SELECT j.*, b.storeBrand 
                      FROM `job` j 
                      LEFT JOIN `sms_brand` as b on b.id = j.brand
                      where j.zipCode={$storezip} and j.brand={$brandId} and j.campaignId ={$storeId} group by j.id order by j.status DESC, j.title ASC";
        } else {
            $query = "SELECT j.*, b.storeBrand 
                      FROM `job` j 
                      LEFT JOIN `sms_brand` as b on b.id = j.brand
                      where j.zipCode={$storezip} and j.brand={$brandId} group by j.id order by j.status DESC, j.title ASC";
        }

        $dbData = Config::get('db')->get_results($query);

        $query = "SELECT FOUND_ROWS() AS found_rows;";
        $countData = Config::get('db')->get_results($query);
        $total = $countData[0]['found_rows'];
        $outJobs = array();
        $jobArray = array();
        $jobArrayTemp = array();

        $store = "Store " . $dbData['storeNum'];
        $address = $dbData['address'];
        $city .= $dbData['city'] . ", " . $dbData['st'];
        


        foreach ($dbData as $job) {
            $jobArrayTemp = array();
            $lat = $job['latitude'];
            $lon = $job['longitude'];
            $clickCount = 0;
            $status = "<a href=\"javascript:;\" onclick=\"tj.statusInactive(" . $job['id'] . ",1);\">Active</a>";

            if (intval($job['status']) == 3) {
                $source = "Snagajob";
            } else if (intval($job['status']) == 2) {
                $source = "JobAlarm";
            } else if (intval($job['status']) == 1) {
                $source = $job['storeBrand'];
            } else if (intval($job['status']) == 0) {
                if (intval($job['prevStatus']) == 3) {
                    $source = "Snagajob";
                } else if (intval($job['prevStatus']) == 2) {
                    $source = "JobAlarm";
                } else if (intval($job['prevStatus']) == 1) {
                    $source = $job['storeBrand'];
                } else {
                    $source = "Unknown";
                }
                $status = "<a href=\"javascript:;\" onclick=\"tj.statusActive(" . $job['id'] . ",1);\">Inactive</a>";
            } else {
                $source = "Unknown";
            }
			
			if (intval($job['status'])==2){
				$positionLink = '<a href="javascript:;" onclick="tj.editJob('. $job['id'] .');return false;">'. $job['title'] .'</a>';
			}else{
				$positionLink = '<a href="'.$job['urls'].'" target="_blank">'.$job['title'].'</a>';
			}


            if (intval($job['zipCode']) > 0) {
                $zip = $job['zipCode'];
            } else {
                $zip = $storezip;
            }

            //if ($autoCount > 0) {
            //    $candidateLink = '<a href="http://admin.jobalarm.com/login/smslogin/' . $sessionAccount . '/' . $sessionId . '/' . $zip . '/1">' . $autoCount . '</a>';
            //} else {
            //    $candidateLink = 0;
            //}


            $jobArrayTemp[] = $job['title'];
            
            if ($job['postDate'] > 0) {
                $time = strtotime($job['postDate']);
                $job['postDate'] = date("m/d/y", $time);
            } else {
                $job['postDate'] = "";
            }

            $outJobs[] = $job;
            $jobArray[] = array(
                'ID' => $job['id'],
                'StoreID' => $storeId,
                'Position' => $positionLink,
                'Source' => $source,
                'Status' => $status
            );
        }
    }
    $outData = array();
    $outData['data'] = $jobArray;
    echo json_encode($outData,JSON_NUMERIC_CHECK);
}

/////////////////////////////////////
// GET ALL JOBS INFO
function GetAllJobsTableData() {

    //$storeId = isset($_REQUEST['storeId']) ? $_REQUEST['storeId'] : 0;
	 $accountId = Config::get('account')['accountId'];
	 //$userId = Config::get('account')['id'];
     $role = Config::get('account')['role'];
	ini_set('memory_limit', '512M');
        $query = "SELECT j.*, b.storeBrand as brandName   
						FROM `job` j 
						LEFT JOIN `sms_brand` as b on b.id = j.brand
						WHERE j.postId={$accountId} group by j.id order by j.state ASC, j.city ASC, j.title ASC";
       

        $dbData = Config::get('db')->get_results($query);
		
		if ($dbData){
		$outJobs = array();
        $jobArray = array();
        //$jobArrayTemp = array();
		//$query = "SELECT FOUND_ROWS() AS found_rows;";
        //$countData = Config::get('db')->get_results($query);
        //$total = $countData[0]['found_rows'];
		
		foreach ($dbData as $job) {
		//$jobArrayTemp = array();
		if(strlen($job['zipCode'])<5){
			$newZip = "0" . $job['zipCode'];
		}else{
			$newZip = $job['zipCode'];
		}
        $city = $job['city'] . ", " . $job['state'] . " " . $newZip;
		$jobId = $job['id'];
		$link = '<a href="javascript:;" onclick="tj.editallJob('. $job['id'] .');return false;">'. $job['title'] .'</a>';
		
		if(intval($job['status'])>0){
		$jobstatus = "<a href=\"javascript:;\" onclick=\"tj.statusInactive(" . $job['id'] . ",0);\">Active</a>";
		}else{
		$jobstatus = "<a href=\"javascript:;\" onclick=\"tj.statusActive(" . $job['id'] . ",0);\">Inactive</a>";	
		}
		//$click = Config::get('db')->get_results("SELECT count(`jobId`) as jobCount FROM `clickTrack` WHERE `jobId`={$jobId}");
  

            $outJobs[] = $job;
            $jobArray[] = array(
                'Date' => $job['postDate'],
                'Title' => $link,
                'Location' => $city,
				'Desc' => $job['text'],
				'Status' => $jobstatus,
				'Zip' => $newZip
            );
		}
    }else{
		$jobArray[] = array(
				'Date' => '',
                'Title' => '',
                'Location' => '',
				'Desc' => '',
				'Status' => ''
				);
	}
	
    $outData = array();
    $outData['data'] = $jobArray;
    echo json_encode($outData,JSON_NUMERIC_CHECK);
}

/////////////////////////////////////
// GET LOCATION USERS INFO
function GetUsersTableData() {

    $storeId = isset($_REQUEST['storeId']) ? $_REQUEST['storeId'] : 0;

    if ($storeId == 0) {

        $jobArray = array();

    } else {

        $userId = Config::get('account')['id'];
        $accountId = Config::get('account')['accountId'];
        $role = Config::get('account')['role'];
        $status = Config::get('account')['status'];

        $dbZip = Config::get('db')->get_results("select * from `sms_stores` where id={$storeId}");
        $storezip = $dbZip[0]['zip'];
        $brandId = $dbZip[0]['brandId'];
        $city = strval($dbZip[0]['city']);
        $state = strval($dbZip[0]['st']);
        $storeNum = $dbZip[0]['storeNum'];

        //echo "store ".$storeId;
        //echo "user ".$user;
        //echo "role ".$role;

        //$query = "SELECT j.*,(c.facebook + c.jobalarm + c.text) as clickCount, count(x.id) as autoCount, b.storeBrand FROM `job` j LEFT JOIN `sms_brand` as b on b.id = j.brand LEFT JOIN `clicks` as c on c.trackId = j.twitterId LEFT JOIN jobautopostXref as x on x.trackId = j.twitterId where (((j.zipCode={$storezip} or (j.city ='{$city}' and j.state='{$state}')))  and j.brand={$brandId}) group by j.id order by j.status DESC";

       $query = "SELECT u.*, s.storeNum, s.address, s.city, s.st, r.role as userRole FROM `users` u LEFT JOIN `assign_store` as a on a.userId = u.id  LEFT JOIN `sms_stores` as s on s.id = a.storeId LEFT JOIN `userRoles` as r on r.id = u.role where a.storeId={$storeId} order by u.role DESC";
       

        $dbData = Config::get('db')->get_results($query);

        $query = "SELECT FOUND_ROWS() AS found_rows;";
        $countData = Config::get('db')->get_results($query);
        $total = $countData[0]['found_rows'];
        $outJobs = array();
        $jobArray = array();
        $jobArrayTemp = array();

        $store = "Store " . $dbData['storeNum'];
        $address = $dbData['address'];
        $city .= $dbData['city'] . ", " . $dbData['st'];
		

        foreach ($dbData as $job) {
            $autoCount = 0;
            $autoCount = $job['autoCount'];
            $jobArrayTemp = array();
            $lat = $job['latitude'];
            $lon = $job['longitude'];
            $clickCount = 0;
            $status = "<a href=\"javascript:;\" onclick=\"tj.removeUser(" . $job['id'] . "," . $storeId . ");\">Remove</a>";
			$name = $job['first_name']." ".$job['last_name'];
			$role = $job['userRole'];
                       
            $outJobs[] = $job;
            $jobArray[] = array(
                'ID' => $job['id'],
                'StoreID' => $storeId,
                'User' => $name,
                'Role' => $role,
                'Action' => $status
            );
        }
    }
    $outData = array();
    $outData['data'] = $jobArray;
    echo json_encode($outData,JSON_NUMERIC_CHECK);
}

/////////////////////////////////////
// SET JOB STATUS
function SetJobActiveStatus($active) {
    $jobId = isset($_REQUEST['jobId']) ? $_REQUEST['jobId'] : false;
    if ($jobId) {
        $dbData = Config::get('db')->get_results("select * from `job` where `id`={$jobId}");
        if(count($dbData) > 0) {
            $currentStatus = $dbData[0]['status'];
            $prevStatus = $dbData[0]['prevStatus'];
            if (!$active){
                $data = array(
                    'status' => 0,
                    'prevStatus' => $currentStatus
                );
            }else{
                $data = array('status' => 1,
							'prevStatus' => $currentStatus
				);
			}
            $where = array('id' => $jobId);

            Config::get('db')->update('job', $data, $where);

            echo json_encode(array('success' => true));
            return true;
        }
    }
    echo json_encode(array('success' => false));
    return false;
}

function uploadFile(){
$filename = $_FILES["file"]["name"];
$accountId = Config::get('account')['accountId'];

// Location
$location1 = '/home/tweetedjobs/public_html/feeds/uploads/' . $filename;

// file extension
$file_extension = pathinfo($location1, PATHINFO_EXTENSION);
$file_extension = strtolower($file_extension);

// Valid image extensions
$image_ext = array("csv");

$response = 0;
if(in_array($file_extension,$image_ext)){
  // Upload file
  $location = '/home/tweetedjobs/public_html/feeds/uploads/' . $accountId . '.csv';
  if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
	$data = array('jobsUploadStatus' => 1
				);
	$where = array('id' => $accountId);
    Config::get('db')->update('account', $data, $where);

	echo json_encode(array('success' =>true, 'message' =>true, 'error' =>false));
    return true;
    //$response = $location;
  }

}
	echo json_encode(array('success' =>true, 'message' =>false, 'error' =>false));
    return true;
	
}

/////////////////////////////////////
// REMOVE USER FROM LOCATION
function RemoveUser($active=true) {
    $userId = isset($_REQUEST['userId']) ? $_REQUEST['userId'] : 0;
	$storeId = isset($_REQUEST['storeId']) ? $_REQUEST['storeId'] : 0;
    
	if ($userId > 0 && $storeId >0) {
        $dbData = Config::get('db')->get_results("select * from `assign_store` where `userId`='{$userId}' AND `storeId`='{$storeId}'");
        if (count($dbData) > 0) {
            $id = $dbData[0]['id'];
            $where = array('id' => $id);
            Config::get('db')->delete('assign_store', $where);

            echo json_encode(array('success' => true));
            return true;
        }
    }
    echo json_encode(array('success' => false));
    return false;
}


/////////////////////////////////////
// ADD JOB POSITION TO LOCATION
function AddJob() {
    $locationId = (isset($_REQUEST['location'])) ? $_REQUEST['location'] : 0;
	$accountId = Config::get('account')['accountId'];
	//echo "location".$locationId;
	
    if (intval($locationId) > 0) {
        $position = (isset($_REQUEST['position'])) ? $_REQUEST['position'] : '';
		//echo "position".$position;
		$dbData = Config::get('db')->get_results("select s.*, b.storeBrand, b.searchKeys from `sms_stores` s left join `sms_brand` as b on b.id = s.brandId where s.id={$locationId}");
		$now = time();
		$pos = str_replace("'","",$position);
		$brandName = str_replace("'","",$dbData[0]['storeBrand']);
				
		$text = $pos." position with ".$brandName." at ".stripslashes($dbData[0]['address'])." in ".$dbData[0]['city'].", ".$dbData[0]['st']." Mobile Apply Now.";
        $hashtags = $dbData[0]['searchKeys'].",JobAlarm";
		$twitterId = "";
        $twitterId  .= "";
        $twitterId  .= $locationId . $now;
		
        if (strlen($position) > 0) {
            $data = array(
                'twitterId'=>$twitterId,
                'postDate'=>date('Y-m-d H:i:s'),
                'text'=>$text,
                'city'=>$dbData[0]['city'],
                'state'=>$dbData[0]['st'],
                'hashTags'=>$hashtags,
                'rawData'=>'',
                'urls'=>'',
                'title'=>$position,
                'status'=>2,
                'jobCats'=>0,
                'userName'=>'',
                'campaignId'=>$dbData[0]['id'],
                'postId'=>$accountId,
                'brand'=>$dbData[0]['brandId'],
                'zipCode'=>$dbData[0]['zip'],
                'prevStatus'=>0
            );

            Config::get('db')->insert('job',$data);
            echo json_encode(array('success'=>true,'message'=>'Job added successfully.'));
            return true;
        }
    }

    echo json_encode(array('success'=>false));
    return false;
}

/////////////////////////////////////
// ADD JOB POSITION TO LOCATION
function AddUser() {
    $locationId = (isset($_REQUEST['location'])) ? $_REQUEST['location'] : 0;
	$userId = (isset($_REQUEST['user'])) ? $_REQUEST['user'] : 0;
	//echo "location".$locationId;
	//echo "user".$userId;
	
    if (intval($locationId) > 0 && intval($userId)>0 ) {
		
		$dbData = Config::get('db')->get_results("select * FROM `assign_store` WHERE `userId`='{$userId}' AND `storeId`='{$locationId}'");
		
		if (!$dbData){
            $data = array(
                'userId'=>$userId,
                'storeId'=>$locationId,
                'status'=>0
            );

            Config::get('db')->insert('assign_store',$data);
            echo json_encode(array('success'=>true,'message'=>'User entered successfully.'));
            return true;
		}
	}

    echo json_encode(array('success'=>false));
    return false;
}

/////////////////////////////////////
// GET DETAILS FOR SINGLE JOB
function GetJobDetails($candidateId) {
    $jobId = (isset($_REQUEST['jobId'])) ? $_REQUEST['jobId'] : 0;
    if ($jobId > 0) {
        $dbJob = Config::get('db')->get_results("select * from `job` where id={$jobId}");
        $dbData = $dbJob[0];
        echo json_encode(array('success' => true,'data'=>$dbData));
        return true;
    }
    echo json_encode(array('success'=>false,'message'=>'Candidate not found.'));
    return false;
}

/////////////////////////////////////
// UPDATE JOB
function UpdateJob() {
    $jobId = (isset($_REQUEST['jobId'])?$_REQUEST['jobId']:0);
    $title = (isset($_REQUEST['title'])?$_REQUEST['title']:'');
    $desc = (isset($_REQUEST['desc'])?$_REQUEST['desc']:'');
    $zipCode = (isset($_REQUEST['zipCode'])?$_REQUEST['zipCode']:'');
	

    if ($jobId > 0) {
		$description = preg_replace('~[\\\\/:*?"<>|]~','', $desc);
		$titleClean = preg_replace('~[\\\\/:*?"<>|]~','', $title);
        $data = array(
            'title' => Config::get('db')->filter($title),
            'text' => Config::get('db')->filter($desc),
            'zipCode' => $zipCode
        );
        $where = array('id'=>$jobId);
        Config::get('db')->update('job',$data,$where);
        echo json_encode(array('success'=>true,'message'=>'Job Updated'));
        return true;
    }
    echo json_encode(array('success'=>false,'message'=>'Job Failed to Update'));
    return false;
}

/////////////////////////////////////
// UPDATE JOB
function UpdateAllJob() {
    $jobId = (isset($_REQUEST['jobId'])?$_REQUEST['jobId']:0);
    $title = (isset($_REQUEST['title'])?$_REQUEST['title']:'');
    $desc = (isset($_REQUEST['desc'])?$_REQUEST['desc']:'');
	$city = (isset($_REQUEST['city'])?$_REQUEST['city']:'');
	$state = (isset($_REQUEST['state'])?$_REQUEST['state']:'');
	$zip = (isset($_REQUEST['zip'])?$_REQUEST['zip']:'');
	$date = (isset($_REQUEST['d'])?$_REQUEST['d']:'');
	

    if ($jobId > 0) {
		$description = preg_replace('~[\\\\/:*?"<>|]~','', $desc);
		$titleClean = preg_replace('~[\\\\/:*?"<>|]~','', $title);
        $data = array(
            'title' => Config::get('db')->filter($title),
            'text' => Config::get('db')->filter($desc),
			'city' => Config::get('db')->filter($city),
			'state' => Config::get('db')->filter($state),
			'zipCode' => Config::get('db')->filter($zip),
			'postDate' => Config::get('db')->filter($date),
			
        );
        $where = array('id'=>$jobId);
        Config::get('db')->update('job',$data,$where);
        echo json_encode(array('success'=>true,'message'=>'Job Updated'));
        return true;
    }
    echo json_encode(array('success'=>false,'message'=>'Job Failed to Update'));
    return false;
}

/////////////////////////////////////
// ADD NEW LOCATION
function AddLocation() {
    $brandId = (isset($_REQUEST['brand'])) ? $_REQUEST['brand'] : '';
	$storeNum = (isset($_REQUEST['storeNum'])) ? $_REQUEST['storeNum'] : '';
	$address = (isset($_REQUEST['address'])) ? $_REQUEST['address'] : '';
	$city = (isset($_REQUEST['city'])) ? $_REQUEST['city'] : '';
	$state = (isset($_REQUEST['state'])) ? $_REQUEST['state'] : '';
	$zip = (isset($_REQUEST['zip'])) ? $_REQUEST['zip'] : '';
	$accountId = Config::get('account')['accountId'];
	$userId = (isset($_REQUEST['user'])) ? $_REQUEST['user'] : '';
	//echo "location".$locationId;
	//echo "user".$userId;
	
    if ($brandId && $city && $state && $zip && $accountId) {
		
		//$dbData = Config::get('db')->get_results("select * FROM `sms_storesHERE `userId`='{$userId}' AND `storeId`='{$locationId}'");
		
            $data = array(
				'accountId'=>$accountId,
                'storeNum'=>$storeNum,
                'brandId'=>$brandId,
				'address'=>$address,
				'city'=>$city,
				'st'=>$state,
				'zip'=>$zip,
                'active'=>1
            );

            $newLoc = Config::get('db')->insert('sms_stores',$data);
			//$lastId =  mysql_insert_id ($newLoc);
			$max = Config::get('db')->get_results("SELECT MAX(id) AS `maxid` FROM `sms_stores`");
			$id = $max[0]['maxid'];
			
			if ($userId){
				$dataB = array(
				'userId'=>$userId,
                'storeId'=>$id,
                'status'=>0
            );
			Config::get('db')->insert('assign_store',$dataB);
				
			}else{
				///do nothing;
			}			
			
			
			
			
            echo json_encode(array('success'=>true,'message'=>'Location entered successfully.'));
            return true;
	}

    echo json_encode(array('success'=>false));
    return false;
}
/////////////////////////////////////
// GET NOTE HISTORY FOR CANDIDATE
function UpdateLocation() {
    $storeId = (isset($_REQUEST['storeId'])?$_REQUEST['storeId']:0);
    $storeNum = (isset($_REQUEST['storeNum'])?$_REQUEST['storeNum']:'');
    $address = (isset($_REQUEST['address'])?$_REQUEST['address']:'');
	$city = (isset($_REQUEST['city'])?$_REQUEST['city']:'');
    $state = (isset($_REQUEST['state'])?$_REQUEST['state']:'');
    $zip = (isset($_REQUEST['zip'])?$_REQUEST['zip']:'');
	$cc = (isset($_REQUEST['cc'])?$_REQUEST['cc']:'');

    if ($storeId > 0) {
        $data = array(
            'storeNum' => $storeNum,
            'address' => $address,
            'city' => $city,
            'st' => $state,
			'zip' => $zip,
			'cc' => $cc
        );
        $where = array('id'=>$storeId);
        Config::get('db')->update('sms_stores',$data,$where);
        echo json_encode(array('success'=>true,'message'=>'Location Updated'));
        return true;
    }
    echo json_encode(array('success'=>false,'message'=>'Location Failed to Update'));
    return false;
}

/////////////////////////////////////
// GET NOTE HISTORY FOR CANDIDATE
function DeleteLocation() {
    $storeId = (isset($_REQUEST['storeId'])?$_REQUEST['storeId']:0);

    if ($storeId > 0) {
        
        $where = array('id'=>$storeId);
        Config::get('db')->delete('sms_stores',$where);
		$where2 = array('storeId'=>$storeId);
		Config::get('db')->delete('assign_store',$where2);
		$where3 = array('campaignId'=>$storeId);
		Config::get('db')->delete('job',$where3);
        echo json_encode(array('success'=>true,'message'=>'Location Deleted'));
        return true;
    }
    echo json_encode(array('success'=>false,'message'=>'Location Failed to Update'));
    return false;
}




/*

         _____   ___   _   _______ ___________  ___ _____ _____ _____    ______  ___  _____  _____
        /  __ \ / _ \ | \ | |  _  \_   _|  _  \/ _ \_   _|  ___/  ___|   | ___ \/ _ \|  __ \|  ___|
        | /  \// /_\ \|  \| | | | | | | | | | / /_\ \| | | |__ \ `--.    | |_/ / /_\ \ |  \/| |__
        | |    |  _  || . ` | | | | | | | | | |  _  || | |  __| `--. \   |  __/|  _  | | __ |  __|
        | \__/\| | | || |\  | |/ / _| |_| |/ /| | | || | | |___/\__/ /   | |   | | | | |_\ \| |___
         \____/\_| |_/\_| \_/___/  \___/|___/ \_| |_/\_/ \____/\____/    \_|   \_| |_/\____/\____/


*/


/////////////////////////////////////
// GET CANDIDATES TABLE INFO
function GetCandidatesTableData() {
    $zipCodeSearch = isset($_REQUEST['zip']) && ($_REQUEST['zip'] != 0) ? $_REQUEST['zip'] : '';
    $zipRadiusSearch = isset($_REQUEST['zipradius']) && ($_REQUEST['zipradius'] != 0) ? $_REQUEST['zipradius'] : 20;
    $groupSearch = isset($_REQUEST['group']) && ($_REQUEST['group'] != 0) ? $_REQUEST['group'] : '';
    $brandSearch = isset($_REQUEST['brand']) && ($_REQUEST['brand'] != 0) ? $_REQUEST['brand'] : '';
	$zipOnly = isset($_REQUEST['ziponly']) && ($_REQUEST['ziponly'] != 0) ? $_REQUEST['ziponly'] :'';

    $userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
    $role = Config::get('account')['role'];
	
	if(intval($accountId)==126) {
		$accountAdd = " AND (x.accountId={$accountId} OR x.accountId=443)";
	}else{
		$accountAdd = " AND x.accountId={$accountId}";
	}

    $userBrand = '';
    $search_add = '';
    $limit = "LIMIT 0,5000";
    $order_by = " x.subscribeDate DESC";
    $brand_add = " AND x.keyword2 != ''";
	$promoAdd = " AND x.promo>0";
    $zipCodeRadius = 15;
    $zipLock = 0;

	
	
	$brandId = 0;
    if ($brandData && count($brandData) > 0) {
        foreach ($brandData as $brdata) {
            $userBrand .= " OR x.brandOrig = '" . $brdata['brandId'] . "'";
            $brandId = $brdata['brandId'];
        }
    }

    if (isset($_REQUEST['search'])) {
        $limit = '';
        $groups = array();
        foreach ($_REQUEST['search'] as $search_item) {
            switch ($search_item['field']) {
                case 'filter_brand':
                    if (($search_item["value"] != '0'))
                        $brand_add .= " AND (x.keyword = '" . $search_item["value"] . "' OR x.keyword2 = '" . $search_item["value"] . "')";
                    //if (!Router::getGetVar('b'))
                    break;
                case 'filter_keyword':
                    if ($search_item["value"] != '')
                        $search_add .= " AND (c.resume LIKE \"%" . $search_item["value"] . "%\" OR c.first_name LIKE \"%" . $search_item["value"] . "%\" OR t.title LIKE \"%" . $search_item["value"] . "%\" OR p.position LIKE \"%" . $search_item["value"] . "%\" OR p.pasteResume LIKE \"%" . $search_item["value"] . "%\" OR c.last_name LIKE \"%" . $search_item["value"] . "%\" OR c.mobile LIKE \"%" . $search_item["value"] . "%\" OR x.job_type LIKE \"%" . $search_item["value"] . "%\")";
                    break;
                case 'filter_groupid':
                    if ($search_item['value'] != '0')
                        $groups[] = "g.Id=".$search_item['value'];
                    // $search_add = '';
                    //$search_add .= " AND g.Id=" . $search_item['value'];
                    if ($search_item['value'] == '0')
                        $groups[] = " (g.groupName = '' or g.groupName is null) ";
                    break;
                case 'filter_zipCode':
                    $zipCode = $search_item['value'];
                    $zipLock = 1;
                    break;
                case 'filter_zipdist':
                    $zipCodeRadius = $search_item['value'];
                    break;
            }
        }
        if (count($groups) > 0)
            $search_add .= " AND (".implode(" OR ",$groups).") ";
    }

/*
    $canQuery = "
             SELECT 
             count(x.brandId) as ccount,
             ce.latitude as latitude,
             ce.longitude as longitude,
             x.brandId as xrefBrandId,
             ce.zip as zip                          
             FROM `candidate` as c 
             LEFT JOIN `candidateXref` as x on x.candidateId = c.id 
             LEFT JOIN `cities_extended` ce on ce.zip = c.zip 
             WHERE 
             c.zip > 0 and 
             c.active=1 and 
             x.promo>0 and
             x.brandId={$brandId}
             group by x.brandId,latitude,longitude
            ";

    $candidates = Config::get('db')->get_results($canQuery); */

    if ($zipCodeSearch != '') {
        $zipList = getDistanceQuery($zipCodeSearch, $zipRadiusSearch);
        $search_add .= " AND c.zip in " . $zipList;
        $limit = 'LIMIT 0,2000';
    }
	if ($brandSearch != '') {
        $search_add .= " AND x.brandOrig = '{$brandSearch}'";
        $limit = 'LIMIT 0,500';
    }
	if ($groupSearch) {
        if (intval($groupSearch)==1){
		$search_add .=" AND x.groupOld=0";
		}else if(intval($groupSearch)==18){
		$promoAdd= " AND x.promo=0";
		}else{
		$search_add .= " AND x.groupOld = '{$groupSearch}'";
		}
        $limit = '';
    }else{
		$search_add .= " AND (x.groupOld=0 OR (x.groupOld !=16 AND x.groupOld !=17 AND x.groupOld !=18))";	
	}
	if (intval($zipOnly)==1) {
        $search_add .= " AND c.zip !='' AND x.groupOld=0 AND c.first_name='' AND x.job_type=''";
        $limit = '';
    }
	/*
	$query = "
    SELECT x.*,
	DATE_FORMAT(x.subscribeDate,'%m/%d/%y') as subscribed,
    c.first_name,
    c.last_name,
    c.mobile,
    c.email,
    LPAD(c.zip, 5, '0') as Czip,
    c.resume_file,
    c.resume,
    c.entered,
    g.groupName as groupName,
    g.id as groupId,
    u.first_name as firstName,
    u.last_name as lastName,
	u.id as User,
    m.msgDate as msgDate,
	m.type as msgType,
    sum((case m.type when 1 then 1 else 0 end) + (case m.type when 2 then 1 else 0 end) - (case m.type when 3 then 1 else 0 end)) AS mgCount
    FROM `candidateXref` as x
    LEFT JOIN `candidate` as c on c.id = x.candidateId
    LEFT OUTER JOIN `users` as u on u.id = x.userXref
    LEFT OUTER JOIN `group` as g on g.id = x.groupOld
    LEFT OUTER JOIN `sms_messages` as m on m.candidateId = x.candidateId and m.accountId = x.accountId and m.msgDate BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW()
    WHERE c.active = 1 and c.mobile != ''$accountAdd
    $promoAdd
	$search_add
    $brand_add
    group by x.candidateId
    ORDER BY $order_by
    $limit";
	*/

    $query = "
    SELECT x.*,
	DATE_FORMAT(x.subscribeDate,'%m/%d/%y') as subscribed,
    c.first_name,
    c.last_name,
    c.mobile,
    c.email,
    LPAD(c.zip, 5, '0') as Czip,
    c.resume_file,
    c.resume,
    c.entered,
    g.groupName as groupName,
    g.id as groupId,
    u.first_name as firstName,
    u.last_name as lastName,
	u.id as User
	FROM `candidateXref` as x
    LEFT JOIN `candidate` as c on c.id = x.candidateId
    LEFT OUTER JOIN `users` as u on u.id = x.userXref
    LEFT OUTER JOIN `group` as g on g.id = x.groupOld
    WHERE c.active = 1 and c.mobile != ''$accountAdd
    $promoAdd
	$search_add
    $brand_add
    group by x.candidateId
    ORDER BY $order_by
    $limit";
	
	ini_set('memory_limit', '512M');
    $dbData = Config::get('db')->get_results($query);
    $query = "SELECT FOUND_ROWS() AS found_rows;";
    $countData = Config::get('db')->get_results($query);
    $total = $countData[0]['found_rows'];
    $outJobs = array();
    $jobArray = array();
    $jobArrayTemp = array();
    $radius = 10;
    $brand = 0;
    $link = 0;
    //$brandcandidates = Config::get('db')->get_results("SELECT c.*, x.keyword as keyword FROM `candidate` as c LEFT JOIN candidateXref as x on x.candidateId = c.id WHERE x.brandId={$brand} and c.active=1 and (x.promo=1 or x.promo=2)");

    foreach ($dbData as $job) {
        $jobArrayTemp = array();
        $style = '';
        $dateMsg = '';

        $dbmDate = Config::get('db')->get_results("select * from `sms_messages` where `candidateId`='{$job['candidateId']}' and `type` !=9 and `accountId`='{$job['accountId']}' order by `msgDate` desc");

        if ($dbmDate) {
            $now = time(); // or your date as well
            $msgDate = strtotime($dbmDate[0]['msgDate']);
            $datediff = $now - $msgDate;
			$dateMsg = $datediff / (60 * 60 * 24);
        }
		
		if ($job['resume_file']){
		$file = "<a href=\"../resumes/".$job['candidateId']."/".$job['resume_file']."\">".$job['resume_file']."</a>";
		}else{
		$file = '';
		}

        $brand = $job['keyword2'];

        if ($job['groupName']) {
            $group = $job['groupName'];
        } else {
            $group = '';
        }
		
		if ($job['Czip']=='00000'){
        $zip ='';
		}else{
		$zip = $job['Czip'];
		}
		
        $first = $job['first_name'];
        $last = $job['last_name'];
        $position = $job['job_type'];
        //$mobile= "<a class=\"edit\" href=\"#editProfile\" id=\"editDialog\">".$job['mobile']."</a>";
        //$mobile= "<a href=\"javascript:;\" onclick=\"tj.updateCandidate(".$job['candidateId'].");\">".$job['mobile']."</a>";
        //$mobile = "<a href=\"updateCandidate.php?c=" . $job['candidateId'] . "\" target=\"_blank\" onclick=\"window.open('http://www.jobalarm.com/dashboard/updateCandidate.php?c=" . $job['candidateId'] . "','popup','width=600,height=800'); return false;\">" . $job['mobile'] . "</a>";
        $mobile = '<a href="javascript:;" onclick="tj.editCandidate('. $job['candidateId'] .');return false;">'. $job['mobile'] .'</a>';

        $skills = $job['resume'];
		
		
        $recruiter = substr($job['firstName'], 0, 1) . ' ' . $job['lastName'];
        $email = "<a href=\"mailto:" . $job['email'] . "\">" . $job['email'] . "</a>";
        $cid = $job['candidateId'];
		
        if ($job['promo'] == 0 || intval($job['mgCount'])>3) {
            $style = "3";
        }else if ($job['promo'] > 0 && $job['User']==$userId && intval($dbmDate[0]['type'])==3 && $dateMsg < 32 && $dateMsg != '' && $dateMsg > 0) {
            $style = "2";
        }else if ($job['promo'] > 0 && $job['User']!=$userId && $job['accountId']==$accountId && intval($dbmDate[0]['type'])==3) {
		//else if ($job['promo'] > 0 && $job['User']!=$userId && $job['accountId']==$accountId && intval($dbmDate[0]['type'])==3 && $dateMsg < 30 && $dateMsg != '' && $dateMsg > 0) {
            $style = "1";
        }else if ($job['promo'] > 0 && $job['accountId']==$accountId && intval($dbmDate[0]['type'])==1) {
		//else if ($job['promo'] > 0 && $job['accountId']==$accountId && intval($dbmDate[0]['type'])==1 &&$dateMsg < 30 && $dateMsg != '' && $dateMsg > 0) {
            $style = "1";
        }else {
            $style = "0";
        }
	


    //            $jobArrayTemp[] = $style;

        $jobArray[] = array(
            'Select' => $job['candidateId'],
            'Keyword' => $job['keyword'],
            'Mobile' => $mobile,
            'CandidateId' => $job['candidateId'],
			'subscribeDate'=> $job['subscribed'],
            'Brand' => $brand,
            'Group' => $group,
            'First' => $first,
            'Last' => $last,
            'Position' => $position,
            'MobileNum' => $job['mobile'],
            'Email' => $email,
            'Zip' => $zip,
            'Skills' => $skills,
			'Resume' => $file,
            'Recruiter' => $recruiter,
            'Style' => $style
        );
    }
    $outData = array();
    $outData['data'] = $jobArray;
    echo json_encode($outData,JSON_NUMERIC_CHECK);
}

/////////////////////////////////////
// GET RECRUITER TABLE INFO
function GetRecruiterTableData() {
    $zipCodeSearch = isset($_REQUEST['zip']) && ($_REQUEST['zip'] != 0) ? $_REQUEST['zip'] : '';
    $zipRadiusSearch = isset($_REQUEST['zipradius']) && ($_REQUEST['zipradius'] != 0) ? $_REQUEST['zipradius'] : 18;
    $groupSearch = isset($_REQUEST['group']) && ($_REQUEST['group'] != 0) ? $_REQUEST['group'] : '';
    $brandSearch = isset($_REQUEST['brand']) && ($_REQUEST['brand'] != 0) ? $_REQUEST['brand'] : '';
	$zipOnly = isset($_REQUEST['ziponly']) && ($_REQUEST['ziponly'] != 0) ? $_REQUEST['ziponly'] :'';

    $userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
    $role = Config::get('account')['role'];

    $userBrand = '';
    $search_add = '';
    $limit = "LIMIT 0,3000";
    $order_by = " m.msgDate DESC,g.groupName ASC";
    $brand_add = " AND x.keyword2 != ''";
    $zipCodeRadius = 15;
    $zipLock = 0;

    $brandData = Config::get('db')->get_results("SELECT * FROM account_brand WHERE accountId = {$accountId}");
    $brandId = 0;
    if ($brandData && count($brandData) > 0) {
        foreach ($brandData as $brdata) {
            $userBrand .= " OR x.brandOrig = '" . $brdata['brandId'] . "'";
            $brandId = $brdata['brandId'];
        }
    }

    if (isset($_REQUEST['search'])) {
        $limit = '';
        $groups = array();
        foreach ($_REQUEST['search'] as $search_item) {
            switch ($search_item['field']) {
                case 'filter_brand':
                    if (($search_item["value"] != '0'))
                        $brand_add .= " AND (x.keyword = '" . $search_item["value"] . "' OR x.keyword2 = '" . $search_item["value"] . "')";
                    //if (!Router::getGetVar('b'))
                    break;
                case 'filter_keyword':
                    if ($search_item["value"] != '')
                        $search_add .= " AND (c.resume LIKE \"%" . $search_item["value"] . "%\" OR c.first_name LIKE \"%" . $search_item["value"] . "%\" OR t.title LIKE \"%" . $search_item["value"] . "%\" OR p.position LIKE \"%" . $search_item["value"] . "%\" OR p.pasteResume LIKE \"%" . $search_item["value"] . "%\" OR c.last_name LIKE \"%" . $search_item["value"] . "%\" OR c.mobile LIKE \"%" . $search_item["value"] . "%\" OR x.job_type LIKE \"%" . $search_item["value"] . "%\")";
                    break;
                case 'filter_groupid':
                    if ($search_item['value'] != '0')
                        $groups[] = "g.Id=".$search_item['value'];
                    // $search_add = '';
                    //$search_add .= " AND g.Id=" . $search_item['value'];
                    if ($search_item['value'] == '0')
                        $groups[] = " (g.groupName = '' or g.groupName is null) ";
                    break;
                case 'filter_zipCode':
                    $zipCode = $search_item['value'];
                    $zipLock = 1;
                    break;
                case 'filter_zipdist':
                    $zipCodeRadius = $search_item['value'];
                    break;
            }
        }
        if (count($groups) > 0)
            $search_add .= " AND (".implode(" OR ",$groups).") ";
    }


    $canQuery = "
             SELECT 
             count(x.brandId) as ccount,
             ce.latitude as latitude,
             ce.longitude as longitude,
             x.brandId as xrefBrandId,
             ce.zip as zip                          
             FROM `candidate` as c 
             LEFT JOIN `candidateXref` as x on x.candidateId = c.id 
             LEFT JOIN `cities_extended` ce on ce.zip = c.zip 
             WHERE 
             c.zip > 0 and 
             c.active=1 and 
             x.promo>0 and
             x.brandId={$brandId}
             group by x.brandId,latitude,longitude
            ";

    $candidates = Config::get('db')->get_results($canQuery);

    if ($zipCodeSearch != '' && $zipRadiusSearch != '') {
        $zipList = getDistanceQuery($zipCodeSearch, $zipRadiusSearch);
        $search_add .= " AND c.zip in " . $zipList;
        $limit = '';
    }
    if ($zipCodeSearch != '' && $zipRadiusSearch == '') {
        $search_add .= " AND c.zip = '{$zipCodeSearch}'";
        $limit = '';
    }
	if ($groupSearch) {
        if (intval($groupSearch)==1){
		$search_add .=" AND g.id IS NULL";
		}else{
		$search_add .= " AND g.id = '{$groupSearch}'";
		}
        $limit = '';
    }else{
		$search_add .= " AND (g.id IS NULL OR (g.id !=16 AND g.id !=17))";	
	}
	if (intval($zipOnly)==1) {
        $search_add .= " AND x.promo >0 AND c.zip !='' AND g.id IS NULL AND c.first_name='' AND t.title IS NULL AND p.position IS NULL";
        $limit = '';
    }



    $query = "
    SELECT x.*,
    c.first_name,
    c.last_name,
    c.active,
    c.mobile,
    c.email,
    x.keyword,
    LPAD(c.zip, 5, '0') as Czip,
    c.resume_file,
    c.resume,
    c.entered,
	ce.city,
	ce.state_code,
    p.position,
    p.pasteResume,
    g.groupName as groupName,
    g.id as groupId,
    u.first_name as firstName,
    u.last_name as lastName,
    u.accountId as accountId,
    t.title as jobTitle,
    m.msgDate as msgDate,
    m.userId as User,
	m.type as msgType,
    sum((case m.type when 1 then 1 else 0 end) + (case m.type when 2 then 1 else 0 end) - (case m.type when 3 then 1 else 0 end)) AS mgCount
    FROM `candidateXref` as x
    LEFT JOIN `candidate` as c on c.id = x.candidateId
	LEFT OUTER JOIN `cities_extended` as ce on ce.zip = c.zip
    LEFT OUTER JOIN `candidateApply` as p on p.id =(select ca.id from `candidateApply` ca where ca.candidateId=c.id and ca.brand=x.brandOrig order by ca.id desc limit 0,1)
    LEFT OUTER JOIN `clickTrack` as t on t.id = (select ct.id from `clickTrack` ct where ct.candidateId=c.id and ct.brand=x.brandOrig order by ct.id desc limit 0,1)
    LEFT OUTER JOIN `users` as u on u.id =(select cg.userId from `candidate_group` cg where cg.candidateId=c.id and cg.accountId={$accountId} order by cg.groupdate desc limit 0,1)
    LEFT OUTER JOIN `group` as g on g.Id=(select cg.groupId from `candidate_group` cg where cg.candidateId=c.id and cg.accountId={$accountId} order by cg.groupdate desc limit 0,1)
    LEFT OUTER JOIN `sms_messages` as m on m.candidateId = x.candidateId and m.brandId = x.brandId and m.type<10 and m.msgDate BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW()
    WHERE c.active = 1 and c.mobile != '' and x.promo<2 and (x.brandOrig=0$userBrand)
    $search_add
    $brand_add
    group by x.candidateId
    HAVING mgCount<4
    ORDER BY $order_by
    $limit";

    $dbData = Config::get('db')->get_results($query);
    $query = "SELECT FOUND_ROWS() AS found_rows;";
    $countData = Config::get('db')->get_results($query);
    $total = $countData[0]['found_rows'];
    $outJobs = array();
    $jobArray = array();
    $jobArrayTemp = array();
    $radius = 10;
    $brand = 0;
    $link = 0;
    //$brandcandidates = Config::get('db')->get_results("SELECT c.*, x.keyword as keyword FROM `candidate` as c LEFT JOIN candidateXref as x on x.candidateId = c.id WHERE x.brandId={$brand} and c.active=1 and (x.promo=1 or x.promo=2)");

    foreach ($dbData as $job) {
        $jobArrayTemp = array();
        $style = '';
        $dateMsg = '';

        $dbmDate = Config::get('db')->get_results("select * from `sms_messages` where `candidateId`={$job['candidateId']} and `accountId`={$accountId} order by `msgDate` desc");

        if ($dbmDate) {
            $now = time(); // or your date as well
            $msgDate = strtotime($dbmDate[0]['msgDate']);
            $datediff = $now - $msgDate;
			$dateMsg = $datediff / (60 * 60 * 24);
        }

        $brand = $job['keyword2'];

        if ($job['groupName']) {
            $group = $job['groupName'];
        } else {
            $group = '';
        }
        $first = $job['first_name'];
        $last = $job['last_name'];
        $position = $job['position'] . ' ' . $job['jobTitle'];
        //$mobile= "<a class=\"edit\" href=\"#editProfile\" id=\"editDialog\">".$job['mobile']."</a>";
        //$mobile= "<a href=\"javascript:;\" onclick=\"tj.updateCandidate(".$job['candidateId'].");\">".$job['mobile']."</a>";
        //$mobile = "<a href=\"updateCandidate.php?c=" . $job['candidateId'] . "\" target=\"_blank\" onclick=\"window.open('http://www.jobalarm.com/dashboard/updateCandidate.php?c=" . $job['candidateId'] . "','popup','width=600,height=800'); return false;\">" . $job['mobile'] . "</a>";
        $mobile = '<a href="javascript:;" onclick="tj.editCandidate('. $job['candidateId'] .');return false;">'. $job['mobile'] .'</a>';

        $skills = $job['resume'];
        $zip = $job['Czip'];
        $recruiter = substr($job['firstName'], 0, 1) . ' ' . $job['lastName'];
        $email = "<a href=\"mailto:" . $job['email'] . "\">" . $job['email'] . "</a>";
        $cid = $job['candidateId'];
		$msg = '<a href="tel:'.$job['mobile'].'">'.$job['mobile'].'</a>';
			
		
		if (intval ($dbmDate[0]['type'])==3 && $job['User']==$userId){
			if ($job['groupName']){
				$info = '<a href="http://www.jobalarm.biz/messenger/messageApp/messages.html" target="_blank">'.$job['title'].' '.$job['first_name'].' '.$job['last_name'].'('.$job['groupName'].')</a>';
			}else{
				$info = '<a href="http://www.jobalarm.biz/messenger/messageApp/messages.html" target="_blank">'.$job['title'].' '.$job['first_name'].' '.$job['last_name'].'</a>';
			}
		}else if ($job['groupName']){
			$info = $job['title'].' '.$job['first_name'].' '.$job['last_name'].'('.$job['groupName'].')';
		}else{
			$info = $job['title'].' '.$job['first_name'].' '.$job['last_name'];
		}

        if ($job['promo'] == 0) {
            $style = "3";
        }else if ($job['promo'] > 0 && $job['User']==$userId && $dbmDate[0]['type']==3 && $dateMsg < 30 && $dateMsg != '' && $dateMsg > 0) {
            $style = "2";
        }else if ($job['promo'] > 0 && $job['User']!=$userId && $job['accountId']==$accountId && $dbmDate[0]['type']==3 && $dateMsg < 30 && $dateMsg !='' && $dateMsg > 0) {
            $style = "1";
        }else if ($job['promo'] > 0 && $job['accountId']==$accountId && $dbmDate[0]['type']==1 &&$dateMsg < 30 && $dateMsg != '' && $dateMsg > 0) {
            $style = "1";
        }else {
            $style = "0";
        }
			
		
		if ($job['Czip']){
			$location = $job['city'].", ".$job['state_code']." ".$job['Czip'];
		}else{
			$location = '';
		}


    //            $jobArrayTemp[] = $style;

        $jobArray[] = array(
            'Select' => $job['candidateId'],
            'Keyword' => $job['keyword'],
            'Mobile' => $msg,
            'CandidateId' => $job['candidateId'],
			'Candidate' => $info,
			'Location' => $location,
			'Date' => $job['msgDate'],
            'Brand' => $brand,
            'Group' => $group,
            'First' => $first,
            'Last' => $last,
            'Position' => $position,
            'MobileNum' => $job['mobile'],
            'Email' => $email,
            'Zip' => $zip,
            'Skills' => $skills,
            'Recruiter' => $recruiter,
            'Style' => $style
        );
    }
    $outData = array();
    $outData['data'] = $jobArray;
    echo json_encode($outData,JSON_NUMERIC_CHECK);
}

/////////////////////////////////////
// GET DETAILS FOR SINGLE CANDIDATE
function GetCandidateDetails($candidateId) {
    $candidateId = (isset($_REQUEST['candidateId'])) ? $_REQUEST['candidateId'] : 0;
    if ($candidateId > 0) {
        $dbCandidate = Config::get('db')->get_results("select c.*, ce.city, ce.state_code from `candidate` c left join `cities_extended` as ce on ce.zip = c.zip where c.id={$candidateId}");
        $dbData = $dbCandidate[0];
		if ($dbData['resume_file']){
		$file = "<a href=\"../resumes/".$candidateId."/".$dbData['resume_file']."\">".$dbData['resume_file']."</a>";
		}else{
		$file = '';
		}
        echo json_encode(array('success' => true,'data'=>$dbData,'file'=>$file));
        return true;
    }
    echo json_encode(array('success'=>false,'message'=>'Candidate not found.'));
    return false;
}

/////////////////////////////////////
// GET SMS HISTORY FOR CANDIDATE
function GetCandidateSMSHistory(){
    $candidateId = (isset($_REQUEST['candidateId'])?$_REQUEST['candidateId']:0);

    $userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];

    $query = "SELECT 
            m.id as Id,
            DATE_FORMAT(m.msgDate,'%m/%d/%y %H:%i:%S') as MsgDate,
            m.message as Message,
			m.userId as userId,
            m.type as smsType,
			u.last_name as User
            from `sms_messages` m
			left outer join `users` as u on u.id = m.userId
            where m.candidateId={$candidateId}
			AND (m.type =1 or m.type =3)
			AND (m.brandId IN(SELECT `brandId` from `account_brand` where `accountId`={$accountId}))            
			ORDER BY m.msgDate DESC
            ";


    $dbData = Config::get('db')->get_results($query);

    foreach ($dbData as $k => $v) {
        //$dbData[$k]['Message'] = //stripslashes($dbData[$k]['smsMsg']);
        //$dbData[$k]['smsMsg'] = null;
        $dbData[$k]['Style'] = ($dbData[$k]['smsType'] == 1) ? 'color:red' : 'color:blue';
    }

    $outArray = array(
        'success' => true,
        'data' => $dbData
    );

    echo json_encode($outArray,JSON_NUMERIC_CHECK);

}

/////////////////////////////////////
// GET NOTE HISTORY FOR CANDIDATE
function GetCandidateNoteHistory() {
    $candidateId = (isset($_REQUEST['candidateId'])?$_REQUEST['candidateId']:0);

    $userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];

    $query = "
      SELECT 
        n.id as recid,
        n.id as id,
        n.accountId as userId,
        n.userId,
        n.noteBody as Message,
        DATE_FORMAT(n.noteDate,'%m/%d/%y') as MsgDate,
        n.noteType as noteType,
        u.fullName as username,
		z.last_name as recruiter
      FROM note n
      LEFT JOIN account u
        on u.id = n.accountId
      LEFT OUTER JOIN users z
        on z.id = n.userId
      WHERE 
        n.candidateId = {$candidateId} and n.accountId = {$accountId}
      AND
        n.active = 1
        
      ORDER by n.id DESC
    ";


//    echo $query;

    $notes = Config::get('db')->get_results($query);

    foreach ($notes as $k => $v) {
        $notes[$k]['Style'] = ($notes[$k]['noteType'] > 1) ? 'color:blue' : 'color:red';
    }

    $outData = array('success'=>true,'data'=>$notes);

    echo json_encode($outData,JSON_NUMERIC_CHECK);

}

/////////////////////////////////////
// UPDATE CANDIDATE
function UpdateCandidate() {
    $candidateId = (isset($_REQUEST['candidateId'])?$_REQUEST['candidateId']:0);
    $firstName = (isset($_REQUEST['firstName'])?$_REQUEST['firstName']:'');
    $lastName = (isset($_REQUEST['lastName'])?$_REQUEST['lastName']:'');
    $zipCode = (isset($_REQUEST['zipCode'])?$_REQUEST['zipCode']:'');
    $email = (isset($_REQUEST['email'])?$_REQUEST['email']:'');
	$resume = (isset($_REQUEST['resume'])?$_REQUEST['resume']:'');

    if ($candidateId > 0) {
        $data = array(
            'first_name' => $firstName,
            'last_name' => $lastName,
            'zip' => $zipCode,
            'email' => $email,
			'resume' => $resume
        );
        $where = array('id'=>$candidateId);
        Config::get('db')->update('candidate',$data,$where);
        echo json_encode(array('success'=>true,'message'=>'Candidate Updated'));
        return true;
    }
    echo json_encode(array('success'=>false,'message'=>'Candidate Failed to Update'));
    return false;
}

/////////////////////////////////////
// EMAIL PROFILE
function forwardEmail() {
    $candidateId = (isset($_REQUEST['candidateId'])?$_REQUEST['candidateId']:0);
    $email = (isset($_REQUEST['sendEmail'])?$_REQUEST['sendEmail']:'');
	$message = (isset($_REQUEST['message'])?$_REQUEST['message']:'');
	$userEmail = Config::get('account')['email'];
	$userName = Config::get('account')['first_name'];
	$elist = array();
	$body .='';
	
	if($email){
		$newemail = str_replace(';',',',$email);
		$emaillist = explode(',', $newemail);
	}
	
	$candDb = Config::get('db')->get_results("SELECT 
	c.*, ce.city, ce.state_code, s.address, s.storeNum
	from `candidate` c
	left join `candidateXref` as x on x.candidateId = c.id
	left outer join `candidateApply` as a on a.candidateId = x.candidateId and a.brand = x.brandOrig 
	left outer join `cities_extended` as ce on ce.zip = c.zip 
	left outer join `sms_stores` as s on s.id = x.stageId
	where c.id = {$candidateId}
	group by c.id
	");
	
	$first = (isset($candDb[0]['first_name'])) ? $candDb[0]['first_name'] : '';
	$last = (isset($candDb[0]['last_name'])) ? $candDb[0]['last_name'] : '';
	$storeaddress = (isset($candDb[0]['address'])) ? $candDb[0]['address'] : '';
	$storenum = (isset($candDb[0]['storeNum'])) ? $candDb[0]['storeNum'] : '';
	$mobileNum = (isset($candDb[0]['mobile'])) ? $candDb[0]['mobile'] : '';
	$zip = (isset($candDb[0]['zip'])) ? $candDb[0]['zip'] : '';
	$resume = (isset($candDb[0]['resume'])) ? $candDb[0]['resume'] : '';
	$resumeFile = (isset($candDb[0]['resume_file'])) ? $candDb[0]['resume_file'] : '';
	    
		 	
	if ($first || $last){
		$fullName = "<tr><td>Name:  ".$first." ".$last."</td></tr>";
	} else {
		$fullName = '';
	}	
	
	if ($storeaddress){
		$store = "<td>Nearest Store:  ".$storeaddress."</td><tr><td>Store Number:  ".$storenum."</td></tr>";
	} else {
		$store = '';
	}
		
	$candemail = (isset($candDb[0]['email'])) ? $candDb[0]['email'] : '';
	if ($candemail){
		$fullEmail = "<td>Email:  ".$candemail."</td>";
	} else {
		$fullEmail = '';
	}
	
	$city = (isset($candDb[0]['city'])) ? $candDb[0]['city'] : '';
	$st = (isset($candDb[0]['state_code'])) ? $candDb[0]['state_code'] : '';
	if ($city || $st){
		$fullCity = "<tr><td>Location:  ".$city." ".$st." ".$zip."</td></tr>";
	} else {
		$fullCity = '';
	}
	
	
	$trans = (isset($candDb[0]['trans'])) ? $candDb[0]['trans'] : '';
	if ($trans){
		$fullTrans = "<td>Reliable Transportation:  ".$trans."</td>";
	} else {
		$fullTrans = '';
	}
	$legal = (isset($candDb[0]['legal'])) ? $candDb[0]['legal'] : '';
	if ($legal){
		$fullLegal = "<td>Eligible to work in the US?:  ".$legal."</td>";
	} else {
		$fullLegal = '';
	}
	
	$age = (isset($candDb[0]['age'])) ? $candDb[0]['age'] : '';
	if ($age){
		$fullAge = "<td>At least 18?:  ".$age."</td>";
	} else {
		$fullAge = '';
	}
	$age21 = (isset($candDb[0]['over21'])) ? $candDb[0]['over21'] : '';
	if ($age21){
		$fullAge = "<td>At least 21?:  ".$age21."</td>";
	} else {
		$fullAge = '';
	}
	
	$workPermit = (isset($candDb[0]['workPermit'])) ? $candDb[0]['workPermit'] : '';
	if ($workPermit){
		$fullPermit = "<td>Work Permit?:  ".$workPermit."</td>";
	} else {
		$fullPermit = '';
	}
	
	$education = (isset($candDb[0]['education'])) ? $candDb[0]['education'] : '';
	if ($education){
		$fullEducation = "<tr><td>Education:  ".$education."</td></tr>";
	} else {
		$fullEducation = '';
	}
	
	$current = (isset($candDb[0]['current'])) ? $candDb[0]['current'] : '';
	$currentLong = (isset($candDb[0]['currentLong'])) ? $candDb[0]['currentLong'] : '';
	$currentReference = (isset($candDb[0]['currentReference'])) ? $candDb[0]['currentReference'] : '';
	if ($current){
		$fullCurrent = "<tr><th>Current Employer Information</th></tr><tr><td>Employer:  ".$current."</td></tr><tr><td>How Long:  ".$currentLong."</td></tr><tr><td>Reference:  ".$currentReference."</td></tr>";
	} else {
		$fullCurrent = '';
	}
	
	$previous = (isset($candDb[0]['previous'])) ? $candDb[0]['previous'] : '';
	$pastLong = (isset($candDb[0]['pastLong'])) ? $candDb[0]['pastLong'] : '';
	$pastReference = (isset($candDb[0]['pastReference'])) ? $candDb[0]['pastReference'] : '';
	if ($previous){
		$fullPrevious = "<tr><th>Previous Employer Information</th></tr><tr><td>Employer:  ".$previous."</td></tr><tr><td>How Long:  ".$pastLong."</td></tr><tr><td>Reference:  ".$pastReference."</td></tr>";
	} else {
		$fullPrevious = '';
	}
	
	$position = (isset($candDb[0]['position'])) ? $candDb[0]['position'] : '';
	$location = (isset($candDb[0]['location'])) ? $candDb[0]['location'] : '';
	$experience = (isset($candDb[0]['experience'])) ? $candDb[0]['experience'] : '';
	if($position || $location || $experience){
		$fullInfo = "<tr><th>Work Info</th></tr><tr><td>Preferred Location:  ".$location."</td></tr><tr><td>Position Desired:  ".$position."</td></tr><tr><td>Restaurant Experience:  ".$experience."</td></tr>";
	} else {
		$fullInfo = '';
	}
	
	$amount = (isset($candDb[0]['amount'])) ? $candDb[0]['amount'] : '';
	$perHourYear = (isset($candDb[0]['perHourYear'])) ? $candDb[0]['perHourYear'] : '';
	if ($amount){
		$fullAmount = "<tr><td>Min. Expected Pay:  %".$amount." ".$perHourYear."</td></tr>";
	} else {
		$fullAmount = '';
	}	
	
	$jobType = (isset($candDb[0]['jobType'])) ? $candDb[0]['jobType'] : '';
	if ($jobType){
		$fulljobType = "<tr><td>Job Type (Full Time, Part Time, Temp):  ".$jobType."</td></tr>";
	} else {
		$fulljobType = '';
	}
		
	$schedule = (isset($candDb[0]['schedule'])) ? $candDb[0]['schedule'] : '';
	$prefer1 = (isset($candDb[0]['prefer1'])) ? $candDb[0]['prefer1'] : '';
	$prefer2 = (isset($candDb[0]['prefer2'])) ? $candDb[0]['prefer2'] : '';
	$days = (isset($candDb[0]['day'])) ? $candDb[0]['day'] : '';
	if ($prefer1 || $prefer2 || $days){
		$fullSchedule = "<tr><td>Flexible Schedule (nights, weekends, etc):  ".$schedule."</td></tr><tr><td>Preferred Shift #1:  ".$prefer1."</td></tr><tr><td>Preferred Shift #2:  ".$prefer2."</td></tr><tr><td>Preferred Days:  ".$days."</td></tr><tr>";
	} else {
		//$fullSchedule = "<tr><td>Flexible Schedule (nights, weekends, etc):  ".$schedule."</td></tr>";
		$fullSchedule = '';
	}	
	
	
	
	if ($resumeFile){
	$attachment = "../../resumes/".$candidateId."/".$resumeFile."";
	}else{
	$attachment = '';
	}
	//echo "attachment ".$attachment;
	
	if ($resume){
		$fullResume = "<tr><td>Resume/Skills:  ".$resume."</td></tr>";
	} else {
		$fullResume = '';
	}
	
		
		if ($position){
		$subject = "JobAlarm Candidate for $position";
		}else{
		$subject = "JobAlarm Candidate";	
		}

		$body .= "
		<html>
		<head>
		<title>HTML email</title>
		</head>
		<body>
		<table>
		<tr>
		$message
		</tr>
		<tr>
		<td><h4>CANDIDATE DETAILS</h4></td>
		</tr>
		<tr>
		$fullName
		</tr>
		<tr>
		<td>Mobile #:  $mobileNum</td>
		</tr>
		<tr>
		$fullEmail
		</tr>
		$fullCity
		<tr>
		$store
		</tr>
		<tr>
		$fullAge
		$fullPermit
		</tr>
		$fullEducation
		<tr>
		$fullTrans
		$fullLegal
		</tr>
		$fullCurrent
		$fullPast
		$fullInfo
		$fullAmount
		$fulljobType
		$fullSchedule
		$fullResume
		<th></th>
		<tr>
		<th>Thank you for using JobAlarm!</th>
		</tr><tr></tr><tr></tr>
		<tr><p><h5>
		NOTE: The information contained in this message may be privileged and confidential and protected from disclosure.  If the reader of this message is not the intended recipient, or an employee or agent responsible for delivering this message to the intended recipient, you are hereby notified that any dissemination, distribution or copying of this communication is strictly prohibited. If you have received this communication in error, please notify us immediately by replying to support@jobalarm.com and deleting it from your computer.
		</h5></p></tr>
		<tr><p><h5>
		Thank you for considering the impact of printing emails on our environment.  Please don’t print unless it is necessary!
		</h5></p></tr>";
		 
		// And the absolute required configurations for sending HTML with attachement
		 
		
		if ($emaillist) {
		foreach($emaillist as $send){
			//echo "send ".$send;
		$mail = new PHPMailer();
		// Now you only need to add the necessary stuff
		$mail->CharSet="UTF-8";
        //
		$mail->IsSendmail();
		// HTML body
		$mail->FromName = ($userName);
		$mail->From = ($userEmail);
		$mail->AddAddress($send, "www.jobalarm.com");
		$mail->MsgHTML($body);
		
		if ($attachment !=''){
		$mail->AddAttachment($attachment);
		}
		$mail->IsHTML(true);                                  // Set email format to HTML
		$mail->Subject = $subject;
		
		if(!$mail->Send()) {
		//echo "There was an error sending the message";
		return false;
		}
		}
		//echo "Message was sent successfully";
        echo json_encode(array('success'=>true,'message'=>'Email Sent'));
        return true;
    }
    echo json_encode(array('success'=>false,'message'=>'Email Failed'));
    return false;
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
	$role = Config::get('account')['role'];
	$ciphering = "AES-128-CTR";
	$options = 0;
	$encryption_iv = '6234567891011126';
	$encryption_key = "ReallGoogl6Max";
    
        $query = "
        SELECT SQL_CALC_FOUND_ROWS
        u.*, r.id as roleId, r.role as userRole, count(a.storeId) as stores FROM `users` u
		LEFT OUTER JOIN `assign_store` as a on a.userId=u.id
		LEFT OUTER JOIN `userRoles` as r on r.id=u.role
		WHERE u.accountId={$accountId} and u.role <={$role} and u.status>0
		GROUP BY u.id ORDER BY u.last_name,u.first_name ASC
		";

    $dbData = Config::get('db')->get_results($query);
    $query = "SELECT FOUND_ROWS() AS found_rows;";
    $countData = Config::get('db')->get_results($query);
    $total = $countData[0]['found_rows'];
    $jobArray = array();
    $link = 0;
	/*
    $mobileZero = "0";
	 for ($i = 0; $i <40; $i++) {
	$mobileData = Config::get('db')->get_results("Select * from `candidate` WHERE `mobileOrig`!='{$mobileZero}' AND `mobileOrig` !='' LIMIT 0,1000");
    //$brandcandidates = Config::get('db')->get_results("SELECT c.*, x.keyword as keyword FROM `candidate` as c LEFT JOIN candidateXref as x on x.candidateId = c.id WHERE x.brandId={$brand} and c.active=1 and (x.promo=1 or x.promo=2)");
	if($mobileData){
		foreach($mobileData as $mobile){
			$updatedata = array('newMobile' =>openssl_encrypt(filter_var($mobile['mobileOrig'], FILTER_SANITIZE_STRING), $ciphering, $encryption_key, $options, $encryption_iv),
								'mobileOrig' => $mobileZero);
			$updateWhere = array('id' => $mobile['id']
			);
             Config::get('db')->update('candidate', $updatedata,$updateWhere);
		}
	}
	}
	*/
	
    foreach ($dbData as $job) {
        $name = $job['last_name'].", ".$job['first_name'];
		$role = $job['userRole'];
        
		$nameLink = '<a href="javascript:;" onclick="tj.editUser('.$job['id'].');return false;" data-toggle="modal" data-target="#edit_user">'. $name .'</a>';
		$locationsLink = '<a href="javascript:;" onclick="tj.showUserLocations('.$job['id'].');return false;" data-toggle="modal" data-target="#usersAssigned">'. $job['stores'] .'</a>';
        //$storeButton = "<button class=\"btn btn-primary btn-sm blue\">Jobs</button>";
				
        $jobArray[] = array(
            'userId'=>$job['id'],
            'Name'=>$nameLink,
            'Role'=>$role,
            'Locations'=>$locationsLink
        );
    }
    $outData = array();
    $outData['data'] = $jobArray;
    echo json_encode($outData,JSON_NUMERIC_CHECK);
}

/////////////////////////////////////
// GET DETAILS FOR SINGLE USER
function GetUserDetails() {
    $userId = (isset($_REQUEST['userId'])) ? $_REQUEST['userId'] : 0;
	//echo "store:".$storeId;
    if ($userId > 0) {
        $dbLocation = Config::get('db')->get_results("select u.*, r.id as roleId, r.role as roleName from `users` u LEFT OUTER JOIN `userRoles` as r on r.id=u.role where u.id={$userId}");
        $dbData = $dbLocation[0];
        echo json_encode(array('success' => true,'data'=>$dbData));
        return true;
    }
    echo json_encode(array('success'=>false,'message'=>'User not found.'));
    return false;
}

/////////////////////////////////////
// GET LOCATION USERS INFO
function GetAssignedTableData() {

    $userId = isset($_REQUEST['userId']) ? $_REQUEST['userId'] : 0;

    if ($userId == 0) {

        $jobArray = array();

    } else {

      
        $dbData = Config::get('db')->get_results("select s.*, b.storeBrand, u.first_name, u.last_name, r.role, a.userId FROM `sms_stores` s LEFT JOIN `sms_brand` as b on b.id=s.brandId LEFT JOIN `users` as u on u.id = {$userId} LEFT JOIN `userRoles` as r on r.id = u.role LEFT OUTER JOIN `assign_store` as a on a.storeId=s.id WHERE a.userId={$userId}");
        
        $jobArray = array();
		

        foreach ($dbData as $job) {
            $status = "<a href=\"javascript:;\" onclick=\"tj.removeUserLocation(" . $userId . "," . $job['id'] . ");\">Remove</a>";
			$store = $job['storeBrand']." (".$job['id'].")";
			$address = $job['address']." ".$job['city'].", ".$job['st'];
			//$name = $job['first_name']." ".$job['last_name']." (".$job['role'].")";
                       
            //$outJobs[] = $job;
            $jobArray[] = array(
				'userId' => $userId,
                'StoreNum' => $store,
                'Location' => $address,
                'Action' => $status
            );
        }
    }
    $outData = array();
    $outData['data'] = $jobArray;
    echo json_encode($outData,JSON_NUMERIC_CHECK);
}

/////////////////////////////////////
// ADD NEW USER
function AddNewUser() {
    $first = (isset($_REQUEST['first'])) ? $_REQUEST['first'] : '';
	$last = (isset($_REQUEST['last'])) ? $_REQUEST['last'] : '';
	$email = (isset($_REQUEST['email'])) ? $_REQUEST['email'] : '';
	$role = (isset($_REQUEST['role'])) ? $_REQUEST['role'] : '';
	$accountId = Config::get('account')['accountId'];
	
	if(intval($role)>4){
		$status=1;
	}else{
		$status=2;
	}
		
	
	
    if ($first && $email) {
		$pw = generateRandomString();
		$pword = md5($pw);
		
		//$dbData = Config::get('db')->get_results("select * FROM `sms_storesHERE `userId`='{$userId}' AND `storeId`='{$locationId}'");
		
            $data = array(
				'first_name'=>$first,
                'last_name'=>$last,
				'accountId'=>$accountId,
                'email'=>$email,
				'password'=>$pword,
				'temp'=>$pword,
				'role'=>$role,
				'status'=>$status
            );

            $newLoc = Config::get('db')->insert('users',$data);
			$subject = "JobAlarm Access";
			$to = $email;

$message = "
<html>
<head>
<title>Welcome to JobAlarm</title>
</head>
<body>
<table>
<tr>
<td>$first,</td>
</tr>
<tr></tr>
<tr>
<td>A JobAlarm ID has been created for you.  Your ID and temporary password are below.</td>
</tr>
<tr></tr>
<tr>
<td>ID: $email</td>
</tr>
<tr>
<td>PW:$pw</td>
</tr>
<th></th>
<tr>
<td>To login, go to <a href=\"http://www.jobalarm.biz\">www.jobalarm.biz</a> and click on Client Login.</td>
</tr>
<th></th>
<tr>
<th>Thank you for using JobAlarm!</th>
</tr>
</table>
<tr></tr><tr></tr>
<tr><p><h5>
NOTE: The information contained in this message may be privileged and confidential and protected from disclosure.  If the reader of this message is not the intended recipient, or an employee or agent responsible for delivering this message to the intended recipient, you are hereby notified that any dissemination, distribution or copying of this communication is strictly prohibited. If you have received this communication in error, please notify us immediately by replying to support@jobalarm.com and deleting it from your computer.
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
$headers .= 'From: <noreply@jobalarm.com>' . "\r\n";

mail($to,$subject,$message,$headers);
					
			
            echo json_encode(array('success'=>true,'message'=>'User added successfully.'));
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
    $userId = (isset($_REQUEST['userId'])?$_REQUEST['userId']:0);
	
	if ($userId > 0) {
		$userDb = Config::get('db')->get_results("SELECT * from `user` where `id`={$userId}");
		$email = $userDb[0]['email'];
		$empty = '';
        
        $where = array('id'=>$userId);
        Config::get('db')->delete('users',$where);
		
		$where2 = array('userId'=>$userId);
		Config::get('db')->delete('assign_store',$where2);
		
		$data3 = array(
			'email'=>$empty
			);
		$where3 = array('email'=>$email);
		Config::get('db')->update('sms_stores',$data3,$where3);
		
		$data4 = array(
			'cc'=>$empty
			);
		$where4 = array('cc'=>$email);
		Config::get('db')->update('sms_stores',$data4,$where4);
		
        echo json_encode(array('success'=>true,'message'=>'User Deleted'));
        return true;
    }
    echo json_encode(array('success'=>false,'message'=>'User Failed to Delete'));
    return false;
}

/////////////////////////////////////
// UPDATE USER
function UpdateUser() {
    $userId = (isset($_REQUEST['userId'])?$_REQUEST['userId']:0);
    $email = (isset($_REQUEST['email'])?$_REQUEST['email']:'');
    $role = (isset($_REQUEST['role'])?$_REQUEST['role']:'');


    if ($userId > 0) {
        $data = array(
            'email' => $email,
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

         ________  ___ _____ _____ _   _ ______  _______   __   ______  ___  _____  _____
        /  ___|  \/  |/  ___|_   _| \ | || ___ \|  _  \ \ / /   | ___ \/ _ \|  __ \|  ___|
        \ `--.| .  . |\ `--.  | | |  \| || |_/ /| | | |\ V /    | |_/ / /_\ \ |  \/| |__
         `--. \ |\/| | `--. \ | | | . ` || ___ \| | | |/   \    |  __/|  _  | | __ |  __|
        /\__/ / |  | |/\__/ /_| |_| |\  || |_/ /\ \_/ / /^\ \   | |   | | | | |_\ \| |___
        \____/\_|  |_/\____/ \___/\_| \_/\____/  \___/\/   \/   \_|   \_| |_/\____/\____/


*/


/////////////////////////////////////
// GET SMS MESSAGE LIST
function GetMessagesTableData() {
    $userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
    $role = Config::get('account')['role'];

    $userBrand = '';
    $limit = " LIMIT 0,3000";
    $orderBy = " sms_messages.msgDate DESC ";
    $searchFields = array();
    $searchArray = array();
    $whereArray = array();
	$candList = array();
    $havingAdd = '';
    $whereAdd = '';
    $search_add = '';
    $responseAppend = '';
    $searchRemoved = false;
    $zipCode = '';
    $zipCodeRadius = '';

    $brandData = Config::get('db')->get_results("SELECT * FROM account_brand WHERE accountId = {$accountId}");

    if ($brandData && count($brandData) > 0) {
        foreach ($brandData as $brdata) {
            $userBrand .= " OR x.brandOrig = '" . $brdata['brandId'] . "'";
        }
    }
	
	$candidates = "SELECT *, sum((case `type` when 1 then 1 else 0 end) + (case `type` when 2 then 1 else 0 end) - (case `type` when 3 then 1 else 0 end)) AS mgCount
		FROM sms_messages 
		WHERE `accountId` ={$accountId} AND `candidateId` in (SELECT `candidateId` from `sms_messages` where `type`=3 group by `candidateId`) 
        GROUP BY `candidateId`
		HAVING `mgCount`<4";
	
	
    /*$query = "
        SELECT SQL_CALC_FOUND_ROWS
        sms_messages.candidateId as id,
		sms_messages.message,
        sms_messages.viewed,
        sms_messages.msgDate,
        sms_messages.msgDate as updated,
        candidate.id as candidateId,
        candidate.first_name,
		candidate.last_name,
		candidate.email,
		candidate.zip,
		candidate.mobile,
		candidateXref.brandId,
		candidateXref.keyword,
		g.groupName as groupName,
		u.first_name as firstName,
		u.last_name as lastName,
		u.accountId as accountId
		FROM sms_messages
		LEFT JOIN candidate on candidate.id=sms_messages.candidateId
		LEFT JOIN candidateXref on candidateXref.candidateId =sms_messages.candidateId
		LEFT OUTER JOIN users as u on u.id = sms_messages.userId
		LEFT OUTER JOIN `group` as g on g.id=(select cg.groupId from `candidate_group` cg where cg.candidateId=candidate.id and cg.accountId=u.accountId order by cg.groupdate DESC limit 0,1)
		INNER JOIN (select sms_messages.id,sms_messages.candidateId,MAX(sms_messages.msgDate) maxmsgDate from sms_messages where userId={$userId} AND type=3 group by sms_messages.candidateId) latest_messages on latest_messages.candidateId=sms_messages.candidateId and sms_messages.msgDate = latest_messages.maxmsgDate
		WHERE (sms_messages.userId ={$userId} or sms_messages.userId=0) AND sms_messages.type=3 AND g.id !=16 AND g.id !=17
        {$whereAdd}
        {$search_add}
        GROUP BY candidate.mobile
		ORDER BY {$orderBy}
        {$limit}";*/

    $dbData = Config::get('db')->get_results($candidates);
    //$candidates = "SELECT FOUND_ROWS() AS found_rows;";
    $countData = Config::get('db')->get_results($candidates);
    $total = $countData[0]['found_rows'];
    $outJobs = array();
    $jobArray = array();
    $jobArrayTemp = array();
    $radius = 10;
    $brand = 0;
    $link = 0;
    //$brandcandidates = Config::get('db')->get_results("SELECT c.*, x.keyword as keyword FROM `candidate` as c LEFT JOIN candidateXref as x on x.candidateId = c.id WHERE x.brandId={$brand} and c.active=1 and (x.promo=1 or x.promo=2)");

    foreach ($dbData as $job) {
        $jobArrayTemp = array();
        $style = 0;
        $dateMsg = '';

        $dbmDate = Config::get('db')->get_results("select x.*,
		s.message,
        s.msgDate,
		c.mobile,
		c.first_name,
		c.last_name,
		u.first_name as firstName,
		u.last_name as lastName,
		g.id,
		g.groupname as groupName
		from `candidateXref` x
        left join `candidate` as c on c.id = x.candidateId
		left outer join `sms_messages` as s on s.id=(SELECT MAX(`id`) from `sms_messages` where `type`=3 and `userId`={$userId} and `candidateId`='{$job['candidateId']}')
        left join `users` as u on u.id = s.userId
		LEFT OUTER JOIN `group` as g on g.id=(select cg.groupId from `candidate_group` cg where cg.candidateId=s.candidateId and cg.accountId=s.accountId order by cg.groupdate DESC limit 0,1)
		where x.candidateId='{$job['candidateId']}' and x.accountId={$accountId} and s.userId={$userId} AND s.type=3 AND (g.id IS NULL OR (g.id !=16 AND g.id !=17)) AND x.promo >0
        group by x.candidateId
        order by s.msgDate DESC");
		
		
		/*
		
		("select s.*,
		x.keyword2,
		x.promo,
		c.mobile,
		c.first_name,
		c.last_name,
		u.first_name as firstname,
		u.last_name as lastname,
		g.id,
		g.groupname as groupName
		from `sms_messages` s 
		left join `candidateXref` as x on x.candidateId = s.candidateId and x.accountId={$accountId}
		left join `candidate` as c on c.id = s.candidateId
		left outer join `users` as u on u.id = s.userId
		LEFT OUTER JOIN `group` as g on g.id=(select cg.groupId from `candidate_group` cg where cg.candidateId=s.candidateId and cg.accountId=s.accountId order by cg.groupdate DESC limit 0,1)
		where s.candidateId='{$job['candidateId']}' and s.userId={$userId} AND s.type=3 AND (g.id IS NULL OR (g.id !=16 AND g.id !=17)) AND x.promo >0 
		group by s.candidateId
		order by s.msgDate desc"); */

        if ($dbmDate) {
            $now = time(); // or your date as well
            $msgDate = strtotime($dbmDate[0]['msgDate']);
            $datediff = $now - $msgDate;
            $dateMsg = $datediff / (60 * 60 * 24);
        

        $brand = $dbmDate[0]['keyword2'];

        if ($dbmDate[0]['groupName']) {
            $group = $dbmDate[0]['groupName'];
        } else {
            $group = '';
        }
        $id = $dbmDate[0]['candidateId'];
        $candidateId = $dbmDate[0]['candidateId'];
        $keyword = $dbmDate[0]['keyword2'];
		$received = $dbmDate[0]['msgDate'];
        $mobileNum = $dbmDate[0]['mobile'];
        $first = $dbmDate[0]['first_name'];
        $last = $dbmDate[0]['last_name'];
        $group = $dbmDate[0]['groupName'];
		$count = $job['mgCount'];
        //$mobile = '<a href="javascript:;" onclick="tj.editCandidate('. $candidateId .');return false;">'. $mobileNum .'</a>';
		$mobile = $dbmDate[0]['mobile'];
        $message = $dbmDate[0]['message'];
        $recuiter = substr($dbmDate[0]['firstName'], 0, 1) . ' ' . $dbmDate[0]['lastName'];

			
		if ($dbmDate[0]['promo'] == 0 || intval($count)>3) {
            $style = "3";
        }else if ($dbmDate[0]['promo'] > 0 && $job[0]['type']==3 && $dateMsg < 30 && $dateMsg != '' && $dateMsg > 0) {
            $style = "2";
        }else if ($dbmDate[0]['promo'] > 0 && $job[0]['type']==1 && $dateMsg < 30 && $dateMsg != '' && $dateMsg > 0) {
            $style = "1";
        }else {
            $style = "0";
        }
		
		      
		

        $outJobs[] = $job;
        $jobArray[] = array(
            'Select'      => $id,
            'CandidateId' => $candidateId,
            'Keyword'     => $keyword,
            'Received'    => $received,
            'First'       => $first,
            'Last'        => $last,
            'Group'       => $group,
            'Mobile'      => $mobile,
            'MobileNum'   => $mobileNum,
            'Message'     => $message,
            'Recruiter'   => $recuiter,
			'Style'		  => $style
        );
		}
    }
    $outData = array();
    $outData['data'] = $jobArray;
    echo json_encode($outData);
}


/*

         _   _ _____ _____ _     _____ _____ _____ _____ _____
        | | | |_   _|_   _| |   |_   _|_   _|_   _|  ___/  ___|
        | | | | | |   | | | |     | |   | |   | | | |__ \ `--.
        | | | | | |   | | | |     | |   | |   | | |  __| `--. \
        | |_| | | |  _| |_| |_____| |_  | |  _| |_| |___/\__/ /
         \___/  \_/  \___/\_____/\___/  \_/  \___/\____/\____/


*/


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
// ADD CANDIDATE NOTE
function AddCandidateNote() {
    $note = isset($_REQUEST['note']) ? $_REQUEST['note'] : '';
    $target = isset($_REQUEST['target']) ? $_REQUEST['target'] : [];
    $userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];

    if ($note && $target > 0) {

       $data = array(
            'accountId' => Config::get('db')->filter($accountId),
            'noteBody' => Config::get('db')->filter($note),
            'userId' => Config::get('db')->filter($userId),
            'candidateId' => Config::get('db')->filter($target),
            'noteDate' => date('Y-m-d H:i:s')
        );

        Config::get('db')->insert('note', $data);
//            Config::get('db')->update('sms_messages',array("groupId"=>intval($group)),array('id'=>intval($target)),1);
		
		echo json_encode(array('success' => true, 'note' => 'Added Successfully'));
        return true;
    }
    echo json_encode(array('success' => false, 'note' => 'Request Failed'));
    return false;
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
    $output = "";

    foreach($options['numbers'] as $k => $n) {
        //foreach($options['numbers'] as $n) {
        $mobile = $n;
        if (intval($shortCode)==47711){
            $url = 'http://sloocetech.net:8084/spi-war/spi/jobalarm45/' . $mobile . '/' . $keyword[$k] . '/messages/mt';
        }else{
            $url = 'https://jobalarm.cloud.sloocetech.net/slooce_apps/spi/jobalarm45/' . $mobile . '/' . $keyword2 . '/messages/mt';
        }
        //cho $mobile;
        //echo $keyword[$k];
        //echo $post[$k];
        //echo $url;



        $output .= curl_request($slooce, $url, $post[$k], $header);

    }
    return $output;
}

/////////////////////////////////////
// UPDATE A CANDIDATES GROUP
function updateCandidateGroup($accountId,$candidateId,$GroupId,$user) {
    $firstName = '';
    $lastName = '';
    $gname = '';

    if ($user){
        $userData = Config::get('db')->get_results("select * FROM `users` where id={$user}");
        $firstName = substr($userData[0]['first_name'],1);
        $lastName = $userData[0]['last_name'];
    }

    if(intval($GroupId)>0){
        $groupName = Config::get('db')->get_results("select * FROM `group` where id={$GroupId}");
        $gname = $groupName[0]['groupName'];

        $data = array(
            'candidateId'=>$candidateId,
            'accountId'=>$accountId,
            'userId'=>$user,
            'groupId'=>$GroupId,
            'groupdate'=>($GroupId > 0) ? date('Y-m-d H:i:s') : null
        );
        Config::get('db')->insert('candidate_group',$data);
		$inGroupId = Config::get('db')->lastid();
        
		Config::get('db')->query("update `candidateXref` set `userXref`='{$user}', `groupOld`='{$GroupId}' WHERE `candidateId`='{$candidateId}' AND `accountId`='{$accountId}'");
		
	}

    if ($inGroupId){
        $data = array(
            'candidateId'=>$candidateId,
            'accountId'=>$accountId,
            'userId'=>$user,
            'noteType'=>1,
            'active'=>1,
            'noteBody'=>"Group set to ".$gname,
            'noteDate'=>date('Y-m-d H:i:s')
        );
        Config::get('db')->insert('note',$data);
    }
    return $inGroupId;
}

/////////////////////////////////////
// UPDATE CANDIDATE GROUP RECORD
function UpdateGroup() {
    $group = isset($_REQUEST['group']) ? $_REQUEST['group'] : 0;
    $targets = isset($_REQUEST['targets']) ? $_REQUEST['targets'] : [];
    $userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
	$gname = '';
	
	$grpData = Config::get('db')->get_results("select * from `group` where `id`={$group}");
	if ($grpData){
		$gname = $grpData[0]['groupName'];
	}

    if (intval($group) > 0 && count($targets) > 0) {
        foreach ($targets as $target) {

            /*$dbData = Config::get('db')->get_results("select * from `candidate_group` where `candidateId`={$target} and `accountId`={$accountId}");

            if($dbData){
                $updateData = array(
                    'groupId' => Config::get('db')->filter($group),
					'accountId' => Config::get('db')->filter($accountId),
					'userId' => Config::get('db')->filter($userId),
					'candidateId' => Config::get('db')->filter($target),
                    'groupdate' => date('Y-m-d H:i:s')
                );
                $updateWhere = array('accountId' => $accountId, 'candidateId' => $target);
                Config::get('db')->insert('candidate_group', $updateData);
				Config::get('db')->query("update `candidateXref` set `groupOld`={$group} WHERE `candidateId`={$target} AND `accountId`='{$accountId}'");
            }else{
				*/

                $data = array(
                    'accountId' => Config::get('db')->filter($accountId),
                    'groupId' => Config::get('db')->filter($group),
                    'userId' => Config::get('db')->filter($userId),
                    'candidateId' => Config::get('db')->filter($target),
                    'groupdate' => date('Y-m-d H:i:s')
                );

                Config::get('db')->insert('candidate_group', $data);
				Config::get('db')->query("update `candidateXref` set `groupOld`={$group} WHERE `candidateId`={$target} AND `accountId`='{$accountId}'");
				
				//if ($inGroupId){
					$notedata = array(
					'candidateId'=> Config::get('db')->filter($target),
					'accountId'=> Config::get('db')->filter($accountId),
					'userId'=> Config::get('db')->filter($userId),
					'noteType'=>1,
					'active'=>1,
					'noteBody'=>"Group set to ".$gname,
					'noteDate'=> date('Y-m-d H:i:s')
					);
					Config::get('db')->insert('note',$notedata);
				//}
            //}
        }
        echo json_encode(array('success' => true, 'msg' => 'Updated Successfully'));
        return true;
    }
    echo json_encode(array('success' => false, 'msg' => 'Request Failed'));
    return false;
}

////////////////////////////////////////
// BUILD A DISTANCE CONDITIONAL FOR SQL
function getDistanceQuery($zipCode, $distance) {
    //  die($zipCode.' : '.$distance);
    $result = Config::get('db')->get_results("select zip,latitude,longitude from cities_extended where zip='{$zipCode}'");
    if ($result) {
        $res = $result[0];
        if (count($res) > 0) {
            $ziplist = array();
            $lat1 = $res['latitude'];
            $lon1 = $res['longitude'];
            $d = $distance;
            $r = 3959;
			$latN = rad2deg(asin(sin(deg2rad($lat1)) * cos($d / $r) + cos(deg2rad($lat1)) * sin($d / $r) * cos(deg2rad(0))));
            $latS = rad2deg(asin(sin(deg2rad($lat1)) * cos($d / $r) + cos(deg2rad($lat1)) * sin($d / $r) * cos(deg2rad(180))));
            $lonE = rad2deg(deg2rad($lon1) + atan2(sin(deg2rad(90)) * sin($d / $r) * cos(deg2rad($lat1)), cos($d / $r) - sin(deg2rad($lat1)) * sin(deg2rad($latN))));
            $lonW = rad2deg(deg2rad($lon1) + atan2(sin(deg2rad(270)) * sin($d / $r) * cos(deg2rad($lat1)), cos($d / $r) - sin(deg2rad($lat1)) * sin(deg2rad($latN))));
            $zipres = Config::get('db')->get_results("SELECT * FROM cities_extended WHERE (latitude <= $latN AND latitude >= $latS AND longitude <= $lonE AND longitude >= $lonW) AND city != '' ORDER BY state_code, city, latitude, longitude");
            foreach ($zipres as $zip) {
                $ziplist[] = "'" . $zip['zip'] . "'";
            }
            return "(" . implode(',', $ziplist) . ")";
        }
    }

    return null;
}

///////////////////////////////////
///SUPPORT REQUEST

if (isset($_GET['sp'])) {
if ( isset($_POST['email']) && isset($_POST['name']) && isset($_POST['message']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ) {
 
  // detect & prevent header injections
  $test = "/(content-type|bcc:|cc:|to:)/i";
  foreach ( $_POST as $key => $val ) {
    if ( preg_match( $test, $val ) ) {
	   echo json_encode(array('success'=>false));
      exit;
    }
  }
  $company = (isset($_POST["name"])  && strlen($_POST['name']) > 0) ?  ", ".$_POST['company'] : '';
  //send email
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

	// More headers
	$headers .= 'From: <support@jobalarm.com>' . "\r\n";
	$subject = "JobAlarm Support Request: ".$_POST['type'].$company;
	$to = "rstrenger@jobalarm.com";
	$message = $_POST['name']."\r\n\r\n".$_POST['phone']."\r\n\r\n".$_POST['email']."\r\n\r\n".$_POST['message'];

	mail($to,$subject,$message,$headers);
  
  //mail( "rstrenger@jobalarm.com", "JobAlarm Support Request: ".$_POST['type'].$company, $_POST['name']."\r\n\r\n".$_POST['phone']."\r\n\r\n".$_POST['email']."\r\n\r\n".$_POST['message'], "From: JobAlarm ContactUs <rstrenger@jobalarm.com>"  );
   echo json_encode(array('success'=>true));
   exit();
	}
}

///////////////////////////////////
///SUPPORT REQUEST

function appClick() {
	$mobile = isset($_REQUEST['mobile']) ? $_REQUEST['mobile'] : '';
	$brand = isset($_REQUEST['brand']) ? $_REQUEST['brand'] : '';
	$accountId = isset($_REQUEST['acct']) ? $_REQUEST['acct'] : 0;	
  
  if($mobile && $brand){
	  $data = array(
                'mobile'=>$mobile,
                'clickDate'=>date('Y-m-d H:i:s'),
				'brandId'=>$brand,
				'accountId'=>$accountId
            );

            Config::get('db')->insert('reward_Clicks',$data);
			echo json_encode(array('success'=>true));
	exit();
	}else{
	echo json_encode(array('success'=>false));
	exit();
	}

}

////////////////////////////////////////
// SEND A WEB CURL REQUEST
function curl_request($user, $url, $postdata = null, $header)
{
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
