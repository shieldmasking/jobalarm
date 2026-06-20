<?php
include "initializer.php";
require_once '../../inc/class.db.php';
require_once '../../inc/class.jatwitter.php';
require_once '../../inc/config.php';


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
    case 'setJobActive':
        header('Content-Type: application/json');
        SetJobActiveStatus(true);
        break;
    case 'setJobInactive':
        header('Content-Type: application/json');
        SetJobActiveStatus(false);
        break;
    case 'addJob':
        header('Content-Type: application/json');
        AddJob();
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
	case 'traffic':
        header("Content-type: text/x-csv");
        getTraffic();
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
    $endDate = isset($_REQUEST['end']) ? $_REQUEST['end'] : '2020-01-01';
    $accountId = Config::get('account')['accountId'];

    $canData = Config::get('db')->get_results("
        SELECT 
           COUNT(`subscribeDate`) AS canTotal
        FROM
            `candidateXref`
        WHERE
            `brandOrig` = (SELECT brandId from `account_brand` where `accountId`={$accountId})
        AND 
            `subscribeDate` >= '{$startDate}' AND 
            `subscribeDate` <= '{$endDate}'          
            
    ");

    $totalCan = (isset($canData[0])) ? $canData[0]['canTotal'] : 0;

    $canGraphData = Config::get('db')->get_results("
        SELECT 
            *, COUNT(`subscribeDate`) AS daycount, DATE(`subscribeDate`) as groupDate
        FROM
            `candidateXref`
        WHERE
            `brandOrig` = (SELECT brandId from `account_brand` where `accountId`={$accountId}) AND 
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

    $promoData = Config::get('db')->get_results("
      SELECT count(`candidateId`) as promoTotal 
      FROM `candidateXref` 
      WHERE 
      `brandOrig` in (SELECT brandId from `account_brand` where `accountId`={$accountId}) and 
      /*MONTH(`subscribeDate`) = MONTH(CURRENT_DATE()) and */
      `promoMktng`>0 AND 
      `subscribeDate` >=  '{$startDate}' AND
      `subscribeDate` <= '{$endDate}'
    ");
    $totalPromo = (isset($promoData[0])) ? $promoData[0]['promoTotal'] : 0;
    $promoPerc = $totalPromo / $totalCan * 100;
    $promoPercent = number_format((float)$promoPerc, 1, '.', '');
    $percentPromo = $totalPromo . " (" . $promoPercent . "%)";

    $smsData = Config::get('db')->get_results("
      SELECT count(*) as msgTotal 
      FROM `sms_messages` 
      WHERE 
      /*MONTH(`msgDate`) = MONTH(CURRENT_DATE()) and*/
      `msgDate` >= '{$startDate}' AND 
      `msgDate` <= '{$endDate}' AND
      `type`=1 and
      `brandId` in (SELECT brandId from `account_brand` where `accountId`={$accountId})
    ");

    $totalMsg = (isset($smsData[0])) ? $smsData[0]['msgTotal'] : 0;


    $smsGraphData = Config::get('db')->get_results("
      SELECT count(*) as msgTotal,DATE(`msgDate`) as groupDate
      FROM `sms_messages` 
      WHERE 
      /*MONTH(`msgDate`) = MONTH(CURRENT_DATE()) and*/
      `msgDate` >= '{$startDate}' AND 
      `msgDate` <= '{$endDate}' AND
      `type`=1 and
      `brandId` in (SELECT brandId from `account_brand` where `accountId`={$accountId})
      GROUP BY DATE(`msgDate`)
    ");

    $smsSentGraphLabels = array();
    $smsSentGraphData = array();

    if (count($smsGraphData) > 0) {
        foreach ($smsGraphData as $graphItem) {
            $smsSentGraphLabels[] = $graphItem['groupDate'];
            $smsSentGraphData[] = $graphItem['msgTotal'];
        }
    }

    $ctrData = Config::get('db')->get_results("
        SELECT 
        count(distinct c.id) as ctrTotal
        FROM `candidate` c
       LEFT JOIN `candidateXref` as x on x.candidateId = c.id
       LEFT OUTER JOIN `clickTrack` as t on t.candidateId = x.candidateId
        WHERE
            c.zip !='' and x.brandOrig = (SELECT brandId from `account_brand` where `accountId`={$accountId})  and ((c.first_name !='' and t.id is null) or (t.id is not null))
        AND 
            `subscribeDate` >= '{$startDate}' AND 
            `subscribeDate` <= '{$endDate}'          
            
    ");

    $totalCTR = (isset($ctrData[0])) ? $ctrData[0]['ctrTotal'] : 0;
    $ctrP = $totalCTR / $totalCan * 100;
    $ctrPercent = number_format((float)$ctrP, 1, '.', '');
    $percentCTR = $totalCTR . " (" . $ctrPercent . "%)";

    $outData = array();

    $outData['totalPromo'] = $percentPromo;
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
	where x.accountId ='{$accountId}' and `subscribeDate` BETWEEN '2017-09-01 00:00:00' AND '2017-10-01 00:00:00' 
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
function GetCandidateCountByLocation($dbData,$brandId,$lat,$lon,$miles) {
    $candCount = 0;

    foreach($dbData as $k=>$candidate) {
        if (($candidate['xrefBrandId'] == $brandId) &&
            (3963*acos(sin($lat/57.2958)*sin($candidate['latitude']/57.2958)+ cos($lat/57.2958) *cos($candidate['latitude']/57.2958)*cos(($candidate['longitude']/57.2958)-($lon/57.2958))))<=$miles
        ) {
            $candCount+=$candidate['ccount'];
        }
    }

    return $candCount;
}

/////////////////////////////////////////
// GET CAND. COUNT BY LOC/BRAND FROM DATA
function GetCandidateZipListByLocation($dbData,$brandId,$lat,$lon,$x1,$x2,$y1,$y2,$miles) {
    $candZips = array();

    foreach($dbData as $k=>$candidate) {
        if (($candidate['latitude'] < $x1) &&
            ($candidate['latitude'] > $x2) &&
            ($candidate['longitude'] < $y1) &&
            ($candidate['longitude'] > $y2) &&
            ($candidate['xrefBrandId'] == $brandId) &&
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
    $brandQuery = "SELECT * FROM account_brand WHERE accountId=$accountId";

    $dbData = Config::get('db')->get_results($brandQuery);

    $brand = $dbData[0]['brandId'];


    if ($role < 4) {
        $query = "
        SELECT SQL_CALC_FOUND_ROWS
        m.*, s.storeBrand,s.textLimit , cit.*
        FROM `sms_stores` as m 
		LEFT JOIN `assign_brand` as b on b.brandId = m.brandId 
		LEFT JOIN `cities_extended` cit on cit.zip = m.zip
		LEFT JOIN `assign_store` as xs on xs.storeId = m.id
		LEFT JOIN `sms_brand` as s on s.id = m.brandId
		WHERE m.active > 0 AND xs.userId = {$userId} AND m.brandId=b.brandId
		GROUP BY m.id ORDER BY m.zip ASC
		";
    } else {
        $query = "
        SELECT SQL_CALC_FOUND_ROWS 
        m.*, s.storeBrand,s.textLimit , cit.*
        FROM `sms_stores` as m 
		LEFT JOIN `assign_brand` as b on b.brandId = m.brandId 
		LEFT JOIN `cities_extended` cit on cit.zip = m.zip
		LEFT JOIN `assign_store` as xs on xs.storeId = m.id
		LEFT JOIN `sms_brand` as s on s.id = m.brandId
		WHERE m.active > 0 AND m.brandId=b.brandId AND m.accountId={$accountId}
		GROUP BY m.id ORDER BY m.zip ASC
		";
    }

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
             x.brandId={$brand}
             group by x.brandId,latitude,longitude
            ";

    $candidates = Config::get('db')->get_results($canQuery);

    foreach ($dbData as $job) {
        $storeNum = $job['storeNum'];
        $zip = $job['zip'];
        $sessionAccount = $_SESSION['account']['accountId'] * 12345;
        $sessionId = $_SESSION['account']['id'] * 54321;

        if (intval($zip) > 0) {
            $zipOrig = substr($zip, 0, 1);

            if (intval($zipOrig) > 0) {
                $zipLow = intval($zipOrig) - 1;
            } else {
                $zipLow = $zipOrig;
            }

            $zipHigh = intval($zipOrig) + 1;
        }

        $brand = $job['brandId'];
        $limit = 4;
        $miles = 18;
        $lat = $job['latitude'];
        $lon = $job['longitude'];
        //$x1 = $lat + .28;
        //$x2 = $lat - .28;
        //$y1 = $lon + .28;
        //$y2 = $lon - .28;

        //$city = Config::get('db') -> get_results("select * FROM cities_extended where zip={$zip}");

        //$lat = $city[0]["latitude"];
        //$lon = $city[0]["longitude"];
        //echo $lat;

        $search_add = '';
        $search_add = " or x.brandId=6 or x.brandId=19";

        $totalCandidates = 0;
        if (intval($zip) > 0) {
            $totalCandidates = GetCandidateCountByLocation($candidates,$brand,$lat,$lon,$miles);
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
            $storeBrand .= $job['storeBrand'] . " (" . $job['storeNum'] . ")";
        } else {
            $storeBrand = $job['storeBrand'];
        }
        if ($address) {
            $address = $job['address'] . ", " . $job['city'] . ", " . $job['st'] . ", " . $job['zip'];
        } else {
            $address = $job['city'] . ", " . $job['st'] . ", " . $job['zip'];
        }

        $storeNameAlex = str_replace("'", "", $job['storeBrand']);

        $storeButton = "";
        $storeButton .= "<button class=\"btn btn-primary btn-sm blue\" onclick=\"tj.showLocationJobs(" . $job['id'] . ", &#39;" . $job['address'] . "&#39;,&#39;" . $job['city'] . "&#39;,&#39;" . $job['st'] . "&#39;,&#39;" . $job['zip'] . "&#39;,&#39;" . $storeNameAlex . "&#39;,&#39;" . $job['storeNum'] . "&#39;);\" data-toggle=\"modal\" data-target=\"#job_modal\">Jobs</button>";

        $jobArray[] = array(
            'ZipCode'=>$job['zip'],
            'RecordID'=>$job['id'],
            'StoreBrand'=>$storeBrand,
            'Address'=>$address,
            'CandidateLink'=>$candidateLink,
            'StoreButton'=>$storeButton
        );
    }
    $outData = array();
    $outData['data'] = $jobArray;
    echo json_encode($outData,JSON_NUMERIC_CHECK);
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
            $query = "SELECT j.*,(c.facebook + c.jobalarm + c.text) as clickCount, count(x.id) as autoCount, b.storeBrand 
                      FROM `job` j 
                      LEFT JOIN `sms_brand` as b on b.id = j.brand 
                      LEFT JOIN `clicks` as c on c.trackId = j.twitterId 
                      LEFT JOIN jobautopostXref as x on x.trackId = j.twitterId 
                      where j.zipCode={$storezip} and j.brand={$brandId} and j.campaignId ={$storeNum} group by j.id order by j.title ASC";
        } else {
            $query = "SELECT j.*,(c.facebook + c.jobalarm + c.text) as clickCount, count(x.id) as autoCount, b.storeBrand 
                      FROM `job` j 
                      LEFT JOIN `sms_brand` as b on b.id = j.brand 
                      LEFT JOIN `clicks` as c on c.trackId = j.twitterId 
                      LEFT JOIN jobautopostXref as x on x.trackId = j.twitterId 
                      where j.zipCode={$storezip} and j.brand={$brandId} group by j.id order by j.title ASC";
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
        $sessionAccount = $_SESSION['account']['accountId'] * 12345;
        $sessionId = $_SESSION['account']['id'] * 54321;


        foreach ($dbData as $job) {
            $autoCount = 0;
            $autoCount = $job['autoCount'];
            $jobArrayTemp = array();
            $lat = $job['latitude'];
            $lon = $job['longitude'];
            $clickCount = 0;
            $status = "<a href=\"javascript:;\" onclick=\"tj.statusInactive(" . $job['id'] . "," . $storeId . ");\">Active</a>";

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
                $status = "<a href=\"javascript:;\" onclick=\"tj.statusActive(" . $job['id'] . "," . $storeId . ");\">Inactive</a>";
            } else {
                $source = "Unknown";
            }


            if (intval($job['zipCode']) > 0) {
                $zip = $job['zipCode'];
            } else {
                $zip = $storezip;
            }

            if ($autoCount > 0) {
                $candidateLink = '<a href="http://admin.jobalarm.com/login/smslogin/' . $sessionAccount . '/' . $sessionId . '/' . $zip . '/1">' . $autoCount . '</a>';
            } else {
                $candidateLink = 0;
            }


            //$query = "SELECT  c.* FROM `craigslist` as c LEFT JOIN cities_extended ce on ce.zip = c.zip WHERE ((3963*ACOS(SIN({$lat}/57.2958)*SIN(ce.latitude/57.2958)+ COS({$lat}/57.2958) *COS(ce.latitude/57.2958)*COS((ce.longitude/57.2958)-({$lon}/57.2958))))<=25 GROUP BY c.id  LIMIT 20";
            //$dbCraig = Config::get('db')->get_results("SELECT DISTINCT c.* FROM `craigslist` as c LEFT JOIN cities_extended ce on ce.city = c.city and ce.state_code = c.st WHERE ((3963*ACOS(SIN({$lat}/57.2958)*SIN(ce.latitude/57.2958)+ COS({$lat}/57.2958) *COS(ce.latitude/57.2958)*COS((ce.longitude/57.2958)-({$lon}/57.2958))))<=20)");

            $jobArrayTemp[] = $job['title'];
            //$jobArrayTemp[] = $job['storeBrand'];

            //$jobArrayTemp[] = '<a href="'.$job['urls'].'" target="_blank">'.$job['urls'].'</a>';


            if ($job['postDate'] > 0) {
                $time = strtotime($job['postDate']);
                $job['postDate'] = date("m/d/y", $time);
            } else {
                $job['postDate'] = "";
            }

            //$jobArrayTemp[] = $job['postDate'];
            //        $jobArrayTemp[] = $source;
            //$jobArrayTemp[] = $candidateLink;
            //        $jobArrayTemp[] = $status;

            //if ($job['clickCount'] == ''){
            //	$jobArrayTemp[] = $clickCount;
            //}else{
            //	$jobArrayTemp[] = $job['clickCount'];
            //}


            //        $manageJob = "";

            //if ($dbCraig){
            //$cost = $dbCraig[0]['cost'];
            //if($cost==0){
            //$manageGroup .= "<a class=\"btn btn-sm green\" onclick=\"tj.postJob(".$job['pid'].");\">QuickPost </a><a class=\"btn btn-sm blue\" onclick=\"tj.textJob(".$job['pid'].");\">QuickText </a><a class=\"btn btn-sm green\" onclick=\"tj.craigslist(".$query['id'].");\">Craigslist </a>";
            //}else{
            //$manageGroup .= "<a class=\"btn btn-sm green\" onclick=\"tj.postJob(".$job['pid'].");\">QuickPost </a><a class=\"btn btn-sm blue\" onclick=\"tj.textJob(".$job['pid'].");\">QuickText </a><a class=\"btn btn-sm purple\" onclick=\"tj.craigslist(".$query['id'].");\">Craigslist </a>";
            //}
            //}else{
            //$manageJob .= "<a class=\"btn btn-sm green\" href=\"http://admin.jobalarm.com/globals?z=".$zip."&u=".$account_data['id']."&b=".$brandId."&l=http://www.jobalarm.com/ja.php?cx=1&id=".$job['id']."\"><i class= \"fa fa-comment-o\"></i> Text</a><a class=\"btn btn-sm blue\" onclick=\"tj.alex.manageGroups(" . $job['id'] . ")\"><i class=\"fa fa-facebook\"> Groups</i></a>";
            //$manageJob .= "<a class=\"btn btn-sm green\" href=\"http://admin.jobalarm.com/login/smslogin/".$sessionAccount."/".$sessionId."/".$zip."/".$job['id']."\"><i class= \"fa fa-comment-o\"></i> Text</a><a class=\"btn btn-sm blue\" onclick=\"tj.alex.manageGroups(" . $job['id'] . ")\"><i class=\"fa fa-facebook\"> Groups</i></a>";
            //        $manageJob .= "<a class=\"btn btn-sm green\" href=\"http://admin.jobalarm.com/login/smslogin/" . $sessionAccount . "/" . $sessionId . "/" . $zip . "/" . $job['id'] . "\"><i class= \"fa fa-comment-o\"></i> Text</a>";


            //$jobArrayTemp[] = $manageJob;

            $outJobs[] = $job;
            $jobArray[] = array(
                'ID' => $job['id'],
                'StoreID' => $storeId,
                'Position' => $job['title'],
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
// SET JOB STATUS
function SetJobActiveStatus($active=true) {
    $jobId = isset($_REQUEST['jobId']) ? $_REQUEST['jobId'] : 0;
    if ($jobId > 0) {
        $dbData = Config::get('db')->get_results("select * from `job` where `id`={$jobId}");
        if (count($dbData) > 0) {
            $currentStatus = $dbData[0]['status'];
            $prevStatus = $dbData[0]['prevStatus'];
            if (!$active)
                $data = array(
                    'status' => 0,
                    'prevStatus' => $currentStatus
                );
            else
                $data = array('status' => $prevStatus);

            $where = array('id' => $jobId);

            Config::get('db')->update('job', $data, $where);

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
    $locationId = isset($_REQUEST['location']) ? $_REQUEST['location'] : 0;

    if ($locationId > 0) {
        $position = isset($_REQUEST['position']) ? $_REQUEST['position'] : '';
		
		$dbData = Config::get('db')->get_results("select s.*, b.storeBrand, b.searchKeys from `sms_stores` s left join `sms_brand` as b on b.id = s.brandId where s.id={$locationId}");
		
		$text = $position." position with ".$dbData[0]['storeBrand']." at ".$dbData[0]['address']." in ".$dbData[0]['city'].", ".$dbData[0]['st']." Mobile Apply Now.";
        $hashtags = $dbData[0]['searchKeys'].",JobAlarm";
		
        if (strlen($position) > 0 && $source > 0) {
            $data = array(
                'twitterId'=>$dbData[0]['storeNum'],
                'postDate'=>date('Y-m-d H:i:s'),
                'text'=>$text,
                'city'=>$dbData[0]['city'],
                'state'=>$dbData[0]['st'],
                'hashTags'=>$hashtags,
                'rawData'=>'',
                'urls'=>'',
                'title'=>$position,
                'status'=>2,
                'jobDats'=>0,
                'userName'=>'',
                'campaignId'=>$dbData[0]['storeNum'],
                'postId'=>0,
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
    $zipRadiusSearch = isset($_REQUEST['zipradius']) && ($_REQUEST['zipradius'] != 0) ? $_REQUEST['zipradius'] : 18;
    $groupSearch = isset($_REQUEST['group']) && ($_REQUEST['group'] != 0) ? $_REQUEST['group'] : '';
    $brandSearch = isset($_REQUEST['brand']) && ($_REQUEST['brand'] != 0) ? $_REQUEST['brand'] : '';

    $userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
    $role = Config::get('account')['role'];

    $userBrand = '';
    $search_add = '';
    $limit = "LIMIT 0,3000";
    $order_by = " g.groupName ASC,c.entered DESC";
    $brand_add = " AND x.keyword2 != ''";
    $zipCodeRadius = 15;
    $zipLock = 0;

    $brandData = Config::get('db')->get_results("SELECT * FROM assign_brand WHERE userId = {$userId}");
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
    p.position,
    p.pasteResume,
    g.groupName as groupName,
    g.id as groupId,
    u.first_name as firstName,
    u.last_name as lastName,
    u.accountId as accountId,
    t.title as jobTitle,
    m.msgDate as msgDate,
    m.userId as userId,
    sum((case m.type when 1 then 1 else 0 end) + (case m.type when 2 then 1 else 0 end) - (case m.type when 3 then 1 else 0 end)) AS mgCount
    FROM `candidateXref` as x
    LEFT JOIN `candidate` as c on c.id = x.candidateId
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

        $dbmDate = Config::get('db')->get_results("select * from `sms_messages` where `candidateId`={$job['candidateId']} and `accountId`={$accountId} order by `type` asc, `msgDate` desc");

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

        if ($job['promo'] == 0) {
            $style = "3";
        } else if ($job['promo'] > 0 && $job['userId'] > 0 && $dateMsg < 30 && $dateMsg != '' && $dateMsg > 0) {
            $style = "2";
        } else if ($job['promo'] > 0 && $job['userId'] = 0 && $dateMsg > 30 && $dateMsg != '' && $dateMsg > 0) {
            $style = "1";
        } else {
            $style = "0";
        }


    //            $jobArrayTemp[] = $style;

        $jobArray[] = array(
            'Select' => $job['candidateId'],
            'Keyword' => $job['keyword'],
            'Mobile' => $job['mobile'],
            'CandidateId' => $job['candidateId'],
            'Brand' => $brand,
            'Group' => $group,
            'First' => $first,
            'Last' => $last,
            'Position' => $position,
            'Mobile' => $mobile,
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
        $dbCandidate = Config::get('db')->get_results("select * from `candidate` where id={$candidateId}");
        $dbData = $dbCandidate[0];
        echo json_encode(array('success' => true,'data'=>$dbData));
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
            id as Id,
            DATE_FORMAT(msgDate,'%m/%d/%y %H:%i:%S') as MsgDate,
            message as Message,
			userId as userId,
            type as smsType
            from sms_messages
            where candidateId={$candidateId}
			AND (type =1 or type = 3)
			AND (brandId IN(SELECT brandId from `account_brand` where `accountId`={$accountId}))            
			ORDER BY msgDate DESC
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
        DATE_FORMAT(n.noteDate,'%m/%d/%y %H:%i') as MsgDate,
        n.noteType as noteType,
        u.fullName as username,
        z.last_name as recruiter
      FROM note n
      LEFT JOIN account u
        on u.id = n.accountId
      LEFT OUTER JOIN users z
        on z.id = n.userId
      WHERE 
        n.candidateId = {$candidateId} and n.userId = {$userId}
      AND
        n.active = 1
        
      ORDER by n.noteDate DESC
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
// GET NOTE HISTORY FOR CANDIDATE
function UpdateCandidate() {
    $candidateId = (isset($_REQUEST['candidateId'])?$_REQUEST['candidateId']:0);
    $firstName = (isset($_REQUEST['firstName'])?$_REQUEST['firstName']:0);
    $lastName = (isset($_REQUEST['lastName'])?$_REQUEST['lastName']:0);
    $zipCode = (isset($_REQUEST['zipCode'])?$_REQUEST['zipCode']:0);
    $email = (isset($_REQUEST['email'])?$_REQUEST['email']:0);

    if ($candidateId > 0) {
        $data = array(
            'first_name' => $firstName,
            'last_name' => $lastName,
            'zip' => $zipCode,
            'email' => $email
        );
        $where = array('id'=>$candidateId);
        Config::get('db')->update('candidate',$data,$where);
        echo json_encode(array('success'=>true,'message'=>'Candidate Updated'));
        return true;
    }
    echo json_encode(array('success'=>false,'message'=>'Candidate Failed to Update'));
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

    $brandData = Config::get('db')->get_results("SELECT * FROM assign_brand WHERE userId = {$userId}");

    if ($brandData && count($brandData) > 0) {
        foreach ($brandData as $brdata) {
            $userBrand .= " OR x.brandOrig = '" . $brdata['brandId'] . "'";
        }
    }
	
	$candidates = "SELECT *, sum((case `type` when 1 then 1 else 0 end) + (case `type` when 2 then 1 else 0 end) - (case `type` when 3 then 1 else 0 end)) AS mgCount
		FROM sms_messages 
		WHERE `accountId` ={$accountId} AND (`type`=3 or `type`=1 or `type`=2)
        GROUP BY `candidateId`
		HAVING mgCount <4";
	
	
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
        $style = '';
        $dateMsg = '';

        $dbmDate = Config::get('db')->get_results("select s.*,
		x.keyword2,
		x.promo,
		c.mobile,
		c.first_name,
		c.last_name,
		u.first_name as firstname,
		u.last_name as lastname,
		g.id,
		g.groupname
		from `sms_messages` s 
		left join `candidateXref` as x on x.candidateId = s.candidateId and x.accountId={$accountId}
		left join `candidate` as c on c.id = s.candidateId
		left outer join `users` as u on u.id = s.userId
		LEFT OUTER JOIN `group` as g on g.id=(select cg.groupId from `candidate_group` cg where cg.candidateId=s.candidateId and cg.accountId=s.accountId order by cg.groupdate DESC limit 0,1)
		where s.candidateId='{$job['candidateId']}' and s.userId={$userId} AND s.type=3 AND (g.id IS NULL OR (g.id !=16 AND g.id !=17)) 
		group by s.candidateId 
		order by s.msgDate desc");

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
        //$mobile = "<a href=\"updateCandidate.php?c=" . $job['id'] . "\" target=\"_blank\" onclick=\"window.open('http://www.jobalarm.com/dashboard/updateCandidate.php?c=" . $job['id'] . "','popup','width=600,height=800'); return false;\">" . $job['mobile'] . "</a>";
        $mobile = '<a href="javascript:;" onclick="tj.editCandidate('. $dbmDate[0]['candidateId'] .');return false;">'. $dbmDate[0]['mobile'] .'</a>';

        $message = $dbmDate[0]['message'];
        $recuiter = substr($dbmDate[0]['firstName'], 0, 1) . ' ' . $dbmDate[0]['lastName'];


        if ($dbmDate[0]['promo'] == 0) {
            $style = "3";
        } else if ($dbmDate[0]['promo'] > 0 && $dbmDate[0]['accountId'] == $accountId && $dateMsg < 30 && $dateMsg != '' && $dateMsg > 0) {
            $style = "2";
        } else if ($dbmDate[0]['promo'] > 0 && $dbmDate[0]['accountId'] == $accountId && $dateMsg > 30 && $dateMsg != '' && $dateMsg > 0) {
            $style = "1";
        } else {
            $style = "0";
        }

//        $jobArrayTemp[] = $id;
//        $jobArrayTemp[] = $keyword;
//        $jobArrayTemp[] = $mobileNum;

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
            'Recruiter'   => $recuiter
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

            //$query = "select c.*, x.keyword, x.userXref, x.accountId as accountOrig, x.promo, rx.userId as userId, x.brandId as brandId, cg.groupId as cgGroup from candidate c LEFT OUTER JOIN `candidate_group` as cg on c.id = cg.candidateId and cg.accountId={$userId} LEFT OUTER JOIN `recruiterXref` as rx on rx.candidateId = c.id and rx.accountId={$userId} LEFT JOIN candidateXref as x on x.candidateId = c.id where c.id ={$candidateId}";
            $query = "select c.*, x.keyword, x.keyword2, x.brandId as brandX, x.brandOrig, x.promo, x.shortCode, cg.groupId as cgGroup 
				from `candidate` c 
				LEFT OUTER JOIN `candidate_group` as cg on c.id = cg.candidateId and cg.accountId={$accountId} 
				LEFT JOIN `candidateXref` as x on x.candidateId = c.id 
				LEFT JOIN `account_brand` as a on a.brandId=x.brandId 
				where c.id ={$candidateId} and (x.brandId = a.brandId or x.brandId=6) group by c.mobile";


            $dbData = Config::get('db')->get_results($query);
            $mobile = (isset($dbData[0]['mobile'])) ? $dbData[0]['mobile'] : 0;
            $shortCode = (isset($dbData[0]['shortCode'])) ? $dbData[0]['shortCode'] : 0;
            $promo = (isset($dbData[0]['promo'])) ? $dbData[0]['promo'] : 0;
            $zip = (isset($dbData[0]['zip'])) ? $dbData[0]['zip'] : 0;
            $brandId = (isset($dbData[0]['brandX'])) ? $dbData[0]['brandX'] : 0;
            $brandOrig = (isset($dbData[0]['brandOrig'])) ? $dbData[0]['brandOrig'] : 0;
            $keyword = (isset($dbData[0]['keyword'])) ? $dbData[0]['keyword'] : '';
            $keyword2 = (isset($dbData[0]['keyword2'])) ? $dbData[0]['keyword2'] : '';
            $cxId = (isset($dbData[0]['id'])) ? $dbData[0]['id'] : 0;
            $accountXref = (isset($dbData[0]['accountXref'])) ? $dbData[0]['accountXref'] : 0;
            //$userXref = (isset($dbData[0]['userXref'])) ? $dbData[0]['userXref'] : 0;
            $recId = (isset($dbData[0]['recId'])) ? $dbData[0]['recId'] : 0;

            if (intval($promo) > 0 && intval($promo) < 3) {

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
                    Config::get('db')->query("update candidate set stageId =2 WHERE id ='{$candidateId}'");
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
//                    Group::updateCandidate($accountId,$candidateId,$grp,$userId);
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

        echo json_encode(array('success' => true, 'msg' => 'Message Sent', 'output'=>$result));
        //echo json_encode($result);
    } else {
        echo json_encode(array('success' => false, 'msg' => 'Request Failed'));
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
function updateCandidateGroup($accountId,$candidateId,$inGroupId,$user) {
    $firstName = '';
    $lastName = '';
    $gname = '';

    if ($user){
        $userData = Config::get('db')->get_results("select * FROM `users` where id={$user}");
        $firstName = substr($userData[0]['first_name'],1);
        $lastName = $userData[0]['last_name'];
    }

    if(intval($inGroupId)>0){
        $groupName = Config::get('db')->get_results("select * FROM `group` where id={$inGroupId}");
        $gname = $groupName[0]['groupName'];

        $data = array(
            'candidateId'=>$candidateId,
            'accountId'=>$accountId,
            'userId'=>$user,
            'groupId'=>$inGroupId,
            'groupdate'=>($inGroupId > 0) ? date('Y-m-d H:i:s') : null
        );
        Config::get('db')->insert('candidate_group',$data);
        $inGroupId = Config::get('db')->lastid();
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

    if (intval($group) > 0 && count($targets) > 0) {
        foreach ($targets as $target) {

            $dbData = Config::get('db')->get_results("select * from `candidate_group` where `candidateId`={$target} and `accountId`={$accountId}");

            if($dbData){
                $updateData = array(
                    'groupId' => Config::get('db')->filter($group),
                    'groupdate' => date('Y-m-d H:i:s')
                );
                $updateWhere = array('accountId' => $accountId, 'candidateId' => $target);
                Config::get('db')->update('candidate_group', $updateData, $updateWhere, 1);
            }else{

                $data = array(
                    'accountId' => Config::get('db')->filter($accountId),
                    'groupId' => Config::get('db')->filter($group),
                    'userId' => Config::get('db')->filter($userId),
                    'candidateId' => Config::get('db')->filter($target),
                    'groupdate' => date('Y-m-d H:i:s')
                );

                Config::get('db')->insert('candidate_group', $data);
//            Config::get('db')->update('sms_messages',array("groupId"=>intval($group)),array('id'=>intval($target)),1);
            }
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
  mail( "rstrenger@jobalarm.com", "JobAlarm Support Request: ".$_POST['type'].$company, $_POST['name']."\r\n\r\n".$_POST['phone']."\r\n\r\n".$_POST['email']."\r\n\r\n".$_POST['message'], "From: JobAlarm ContactUs <rstrenger@jobalarm.com>"  );
   echo json_encode(array('success'=>true));
   exit();
	}
}

/////////////////////////////
/////Traffic View
function getTraffic() {
//if (isset($_GET['mtr'])) {
    $userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
	
	//$query = "SELECT u.*, b.storeBrand, COUNT(s.id) as storeCount, s.brandId from users u LEFT JOIN sms_stores as s on s.userId = u.id LEFT JOIN sms_brand as b on b.id = s.brandId WHERE u.accountId ={$account} and u.status =1 GROUP BY u.id ORDER BY u.last_name ASC";
	$query = "SELECT x . *, DATE_FORMAT(x.subscribeDate,'%m/%d/%y') as subDate, c.first_name, c.last_name, c.zip, c.entered, c.mobile, j.title, a.brandId2, ce.city, ce.state_code
	FROM  `candidateXref` x
	LEFT JOIN  `candidate` AS c ON x.candidateId = c.id
	LEFT OUTER JOIN `clickTrack` as t on t.candidateId = c.id and t.brand = x.brandOrig
	LEFT OUTER JOIN `job` as j on j.id = t.jobId
	LEFT OUTER JOIN `account` as a on a.id = x.accountId
	LEFT OUTER JOIN `cities_extended` as ce on ce.zip = c.zip
	WHERE x.brandOrig in (SELECT `brandId` from `account_brand` where `accountId`={$accountId})
	GROUP BY x.candidateId
	ORDER BY x.subscribeDate DESC
	LIMIT 100";	
	
	$dbData = Config::get('db') -> get_results($query);
	$query = "SELECT FOUND_ROWS() AS found_rows;";
	$countData = Config::get('db') -> get_results($query);
	$total = $countData[0]['found_rows'];
	$outJobs = array();
	$jobArray = array();
	$jobArrayTemp = array();
	$title = '';
	
	foreach ($dbData as $job) {
		
		$jobArrayTemp = array();
						
		//$name = "";
		$name = $job['first_name']." ".$job['last_name'];
		$jobArrayTemp[] = $job['title']." ".$name;
		
		$jobArrayTemp[] = $job['city'].", ".$job['state_code'];
		
		$jobArrayTemp[] = '<a href="tel:'.$job['mobile'].'">'.$job['mobile'].'</a>';
				
		$jobArrayTemp[] = $job['subDate'];
		 	
		
		$outJobs[] = $job;
		$jobArray[] = $jobArrayTemp;
	}    $outData = array();
	$outData['success'] = true; 
	$outData['name'] = $name;
	$outData['data'] = $jobArray;
	$outData['clickpay'] = $clickpay;
	echo json_encode($outData);
	exit();
	
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
