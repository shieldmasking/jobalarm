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
		
	////////////////////////////////
    // SMS PRODUCTION PAGE
    case 'getProd':
        header('Content-Type: application/json');
        GetProdTableData();
        break;
	case 'updateProd':
        header('Content-Type: application/json');
        UpdateProdMatrix();
        break;
	case 'getProdDetails':
        header('Content-Type: application/json');
        GetProdDetails();
        break;
	case 'prodNote':
        header('Content-Type: application/json');
        AddProdNote();
        break;
	case 'opt':
		header('Content-Type: application/json');
		optIn();
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
           AVG(`nvariance`) AS canTotal
        FROM
            `productiveNewData`
        WHERE
            `accountId`={$accountId} AND `ldcount` > 0 
        AND 
            `dayDate` >= '{$startDate}' AND 
            `dayDate` <= '{$endDate}'            
    ");
	

    $totalCan1 = (isset($canData[0])) ? $canData[0]['canTotal'] : 0;
	
	$totalCan = round($totalCan1,0);
	/*
	if($totalCan2 <0){
		$totalCan = $totalCan2 * -1;
	}else{
		$totalCan = $totalCan2;
	}*/
	
	
	
	
	
    $canGraphData = Config::get('db')->get_results("
        SELECT 
            *, `nvariance` as daycount, DATE(`dayDate`) as groupDate
        FROM
            `productiveNewData`
        WHERE
            `accountId`={$accountId} AND `ldcount` > 0 
		AND
            `dayDate` >= '{$startDate}' AND 
            `dayDate` <= '{$endDate}' 
                
        GROUP BY DATE(`dayDate`)    
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
      SELECT 
           AVG(`aproductivity`) AS postTotal
        FROM
            `productiveNewData`
        WHERE
            `accountId`={$accountId} AND `ldcount` > 0 
        AND 
            `dayDate` >= '{$startDate}' AND 
            `dayDate` <= '{$endDate}'
    ");
    $totalPromo = (isset($promoData[0])) ? round($promoData[0]['postTotal'],2) : 0;
   

    $smsData = Config::get('db')->get_results("
      SELECT 
           AVG(`lvariance`) AS laborTotal
        FROM
            `productiveNewData`
        WHERE
            `accountId`={$accountId} AND `ldcount` > 0 
        AND 
            `dayDate` >= '{$startDate}' AND 
            `dayDate` <= '{$endDate}'
    ");

    $totalMsg1 = (isset($smsData[0])) ? $smsData[0]['laborTotal'] : 0;
	$totalMsg = round($totalMsg1,0);
	

    $smsGraphData = Config::get('db')->get_results("
      SELECT 
            *, `avariance` as daycount, DATE(`dayDate`) as groupDate
        FROM
            `productiveNewData`
        WHERE
            `accountId`={$accountId} AND `ldcount` > 0 
		AND 
            `dayDate` >= '{$startDate}' AND 
            `dayDate` <= '{$endDate}' 
                
        GROUP BY DATE(`dayDate`)
    ");

    $smsSentGraphLabels = array();
    $smsSentGraphData = array();

    if (count($smsGraphData) > 0) {
        foreach ($smsGraphData as $graphItem) {
            $smsSentGraphLabels[] = $graphItem['groupDate'];
            $smsSentGraphData[] = $graphItem['daycount'];
        }
    }
	
	$laborData = Config::get('db')->get_results("
      SELECT 
            *, `lvariance` as daycount, DATE(`dayDate`) as groupDate
        FROM
            `productiveNewData`
        WHERE
            `accountId`={$accountId} AND `ldcount` > 0 
		AND 
            `dayDate` >= '{$startDate}' AND 
            `dayDate` <= '{$endDate}' 
                
        GROUP BY DATE(`dayDate`)
    ");

    $laborGraphLabels = array();
    $laborGraphData = array();

    if (count($laborData) > 0) {
        foreach ($laborData as $laborgraphItem) {
            $laborGraphLabels[] = $laborgraphItem['groupDate'];
            $laborGraphData[] = $laborgraphItem['daycount'];
        }
    }
	
	$postpartumData = Config::get('db')->get_results("
      SELECT 
            *, `aproductivity` as daycount, DATE(`dayDate`) as groupDate
        FROM
            `productiveNewData`
        WHERE
            `accountId`={$accountId} AND `ldcount` > 0 
		AND 
            `dayDate` >= '{$startDate}' AND 
            `dayDate` <= '{$endDate}' 
                
        GROUP BY DATE(`dayDate`)
    ");

    $postpartumGraphLabels = array();
    $postpartumGraphData = array();

    if (count($postpartumData) > 0) {
        foreach ($postpartumData as $postpartumgraphItem) {
            $postpartumGraphLabels[] = $postpartumgraphItem['groupDate'];
            $postpartumGraphData[] = $postpartumgraphItem['daycount'];
        }
    }

    $ctrData = Config::get('db')->get_results("
        SELECT 
           AVG(`avariance`) AS anteTotal
        FROM
            `productiveNewData`
        WHERE
            `accountId`={$accountId} AND `ldcount` > 0 
        AND 
            `dayDate` >= '{$startDate}' AND 
            `dayDate` <= '{$endDate}'          
            
    ");

    $totalCTR1 = (isset($ctrData[0])) ? $ctrData[0]['anteTotal'] : 0;
	$totalCTR = round($totalCTR1,0);
	
	
    //$ctrP = $totalCTR / $totalCan * 100;
    //$ctrPercent = number_format((float)$ctrP, 1, '.', '');
    //$percentCTR = $totalCTR . " (" . $ctrPercent . "%)";
	

    $outData = array();

    $outData['totalPromo'] = $totalPromo;
    $outData['totalMsg'] = $totalMsg;
    $outData['totalCan'] = $totalCan;
    $outData['totalCTR'] = $totalCTR;
    $outData['canGraphData'] = array('labels'=>$newCandidateGraphLabels,'data'=>$newCandidateGraphData);
    $outData['smsGraphData'] = array('labels'=>$smsSentGraphLabels,'data'=>$smsSentGraphData);
	$outData['laborGraphData'] = array('labels'=>$laborGraphLabels,'data'=>$laborGraphData);
	$outData['postpartumGraphData'] = array('labels'=>$postpartumGraphLabels,'data'=>$postpartumGraphData);

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
    
        $query = "
        SELECT SQL_CALC_FOUND_ROWS
        u.*, r.id as roleId, r.role as userRole FROM `productiveUser` u
		LEFT JOIN `productiveUserRoles` as r on r.id=u.role
		WHERE u.accountId={$accountId} and u.role < 6
		GROUP BY u.id ORDER BY u.last_name,u.first_name ASC
		";

    $dbData = Config::get('db')->get_results($query);
    $query = "SELECT FOUND_ROWS() AS found_rows;";
    $countData = Config::get('db')->get_results($query);
    $total = $countData[0]['found_rows'];
    $jobArray = array();
    $link = 0;

    //$brandcandidates = Config::get('db')->get_results("SELECT c.*, x.keyword as keyword FROM `candidate` as c LEFT JOIN candidateXref as x on x.candidateId = c.id WHERE x.brandId={$brand} and c.active=1 and (x.promo=1 or x.promo=2)");

    foreach ($dbData as $job) {
        $name = $job['last_name'].", ".$job['first_name'];
		$role = $job['userRole'];
		
		$text = intval($job['text']);
		
		if($text==0){
		$textsub = "No";
		}else{
		$textsub = "Yes";
		}
        
		$nameLink = '<a href="javascript:;" onclick="tj.editUser('.$job['id'].');return false;" data-toggle="modal" data-target="#edit_user">'. $name .'</a>';
		$locationsLink = $textsub;
		//$locationsLink = '<a href="javascript:;" onclick="tj.showUserLocations('.$job['id'].');return false;" data-toggle="modal" data-target="#usersAssigned">'. $job['stores'] .'</a>';
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
        $dbLocation = Config::get('db')->get_results("select u.*, r.id as roleId, r.role as roleName from `productiveUser` u LEFT OUTER JOIN `productiveUserRoles` as r on r.id=u.role where u.id={$userId}");
        $dbData = $dbLocation[0];
        echo json_encode(array('success' => true,'data'=>$dbData));
        return true;
    }
    echo json_encode(array('success'=>false,'message'=>'User not found.'));
    return false;
}

/////////////////////////////////////
// ADD NEW USER
function AddNewUser() {
    $first = (isset($_REQUEST['first'])) ? $_REQUEST['first'] : '';
	$last = (isset($_REQUEST['last'])) ? $_REQUEST['last'] : '';
	$email = (isset($_REQUEST['email'])) ? $_REQUEST['email'] : '';
	$role = (isset($_REQUEST['role'])) ? $_REQUEST['role'] : 0;
	$mobile = (isset($_REQUEST['mobile'])) ? $_REQUEST['mobile'] : '';
	//$atext = (isset($_REQUEST['atext'])) ? $_REQUEST['atext'] : 0;
	//$rtext = (isset($_REQUEST['rtext'])) ? $_REQUEST['rtext'] : 0;
	$accountId = Config::get('account')['accountId'];
	
	
    if ($first && $email) {
		$pw = generateRandomString();
		$pword = md5($pw);
		
		//$dbData = Config::get('db')->get_results("select * FROM `sms_storesHERE `userId`='{$userId}' AND `storeId`='{$locationId}'");
		//$text = intval($atext)+intval($rtext);
            $data = array(
				'first_name'=>$first,
                'last_name'=>$last,
				'accountId'=>$accountId,
                'email'=>$email,
				'mobile'=>$mobile,
				'pwd'=>$pword,
				'temp'=>$pword,
				'role'=>$role,
                'active'=>1
            );

            $newLoc = Config::get('db')->insert('productiveUser',$data);
			$subject = "ProductiveHR Access";
			$to = $email;

$message = "
<html>
<head>
<title>Welcome to ProductiveHR</title>
</head>
<body>
<table>
<tr>
<td>$first,</td>
</tr>
<tr></tr>
<tr>
<td>A ProductiveHR ID has been created for you.  Your ID and temporary password are below.</td>
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
<td>To login, go to www.jobalarm.com/productive</td>
</tr>
<th></th>
<tr>
<th>Thank you for using ProductiveHR!</th>
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
					
			
            echo json_encode(array('success'=>true,'message'=>'Location entered successfully.'));
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
		$userDb = Config::get('db')->get_results("SELECT * from `productiveUser` where `id`={$userId}");
		$data = array(
                'status'=>0
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
// UPDATE USER
function UpdateUser() {
    $userId = (isset($_REQUEST['userId'])?$_REQUEST['userId']:0);
    $email = (isset($_REQUEST['email'])?$_REQUEST['email']:'');
    $role = (isset($_REQUEST['role'])?$_REQUEST['role']:'');
	$mobile = (isset($_REQUEST['mobile'])?$_REQUEST['mobile']:'');
	//$atext = (isset($_REQUEST['atext'])) ? $_REQUEST['atext'] : 0;
	//$rtext = (isset($_REQUEST['rtext'])) ? $_REQUEST['rtext'] : 0;


    if ($userId > 0) {
		$text = intval($atext)+intval($rtext);
        $data = array(
            'email' => $email,
            'role' => $role,
			'mobile' => $mobile
        );
        $where = array('id'=>$userId);
        Config::get('db')->update('productiveUser',$data,$where);
        echo json_encode(array('success'=>true,'message'=>'User Updated'));
        return true;
    }
    echo json_encode(array('success'=>false,'message'=>'User Failed to Update'));
    return false;
}
	////////////////////////////////////////////////
//////// OptIn
function optIn(){
include_once 'inc/class.db.php';
include_once 'inc/config.php';
	$mobile = isset($_POST['mobile']) ? $_POST['mobile'] : false;
		
	$cand = array(
                    'mobile'=>$mobile
                     );
                Config::get('db')->insert('candidate',$cand);
	
	$dbCandx = Config::get('db')->get_results("select * from `candidate` where `mobile`='{$mobile}'");
	$candidateId = $dbCandx[0]['id'];
	$keyword = "PRODUCTIVE";
	
	$candx = array(
                    'candidateId' => $candidateId,
					'brandId' => 178,
					'brandOrig' => 178,
					'keyword' => $keyword,
					'keyword2' => $keyword2
                     );
                Config::get('db')->insert('candidateXref',$candx);		
	
	if ($mobile){		
		
			$number = "1" . $mobile;
		    $now = time();
			
			$msgId = "";
			$msgId .= "";
			$msgId .= $mobile . $now;
			$psword = "J8775bcgEE2065";
			$keyword = $keyword;

			$xmlmsg = "";
			$xmlmsg .= "";		
			$xmlmsg .= "<message id=\"".$msgId."\">";
			$xmlmsg .= "<partnerpassword>".$psword."</partnerpassword>";
			$xmlmsg .= "<content></content>";
			$xmlmsg .= "</message>"
			;		
		
		$sms_result = send_messages_start($xmlmsg,$number,$keyword);
		
		echo json_encode(array('success'=>true));
		
	exit();
	}
	echo json_encode(array('success'=>false));
	exit();
	
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
    //$dept = isset($_REQUEST['dept']) ? $_REQUEST['dept'] : '';
    $startDate = isset($_REQUEST['start']) ? $_REQUEST['start'] : date('yyyy-mm-dd');
    $endDate = isset($_REQUEST['end']) ? $_REQUEST['end'] : date('yyyy-mm-dd');
    $userId = Config::get('account')['id'];
    $accountId = Config::get('account')['accountId'];
    $role = Config::get('account')['role'];

    $total = 0;
    $variance = 0;

    $dbData = Config::get('db')->get_results("SELECT n.*, DATE_FORMAT(n.dayDate,'%m/%d/%y') as newDate, s.shift as shiftName, u.first_name, u.last_name FROM `productiveNewData` n LEFT JOIN `productiveShifts` as s on s.id = n.shift LEFT OUTER JOIN `productiveUser` as u on u.id = n.userId WHERE n.dayDate >='{$startDate}' AND n.dayDate <='{$endDate}' AND n.accountId={$accountId} group by n.dayDate, n.shift");
     
    if($dbData){
		$outJobs = array();
        $jobArray = array();
		foreach($dbData as $ante){
			$qty = intval($ante['ldcount']);
			$antecount = intval($ante['antecount']);
			$chargecount = intval($ante['chargecount']);
			$techcount = intval($ante['techcount']);
			$seccount = intval($ante['seccount']);
			$nurses = $qty + $antecount;
			$shift = intval($ante['shift']);
			$date = $ante['newDate'];
			$shiftName = $date . " - " . $ante['shiftName'];
			$dateshift = $date.$shift;
			$aprod = $ante['aproductivity'];
			$avariance = $ante['avariance'];
			$lvariance = $ante['lvariance'];
			$nvariance = "A: " . $avariance . ", L: " . $lvariance;
			$note = $ante['note'];
			$dataId = intval($ante['id']);
			
			if(floatval($aprod) > 0){
			$aproductivity = $aprod . "%";	
			}else{
			$aproductivity ='';
			}
			
			if($qty == 0){
				$shiftName = "<a href=\"javascript:;\" onclick=\"tj.editProd(" . $dataId . ");\">". $shiftName . "</a>";
			}else{
				$shiftName = "<a href=\"javascript:;\" onclick=\"tj.editProd(" . $dataId . ");\">". $shiftName . "</a>";
			} 
			
			$outJobs[] = $ante;
            $jobArray[] = array(
				'shift' => $shiftName,
				'variance' => $nvariance,
                'aprod' => $aproductivity,
				'nursecount' => $nurses,
				'techs' => $techcount,
				'secs' => $seccount,
				'note' => $note,
                'shiftnum' => $dateshift
            );
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
// GET DETAILS FOR SINGLE USER
function GetProdDetails() {
	$dataId = (isset($_REQUEST['dataId'])?$_REQUEST['dataId']:'');
		
		$userId = Config::get('account')['id'];
        $accountId = Config::get('account')['accountId'];
        $role = Config::get('account')['role'];
    
	
	if ($dataId) {
        $dbProd = Config::get('db')->get_results("select n.*, DATE_FORMAT(n.dayDate,'%m/%d/%Y') as reportdate, s.shift as reportshift, n.atotal+n.ltotal as totalpatients, a.totalbeds, a.totalbeds-n.atotal-n.ltotal as openbeds, u.first_name, u.last_name from `productiveNewData` n left join `productiveShifts` as s on s.id = n.shift left join `productiveAccount` as a on a.id = n.accountId left join `productiveUser` as u on u.id = n.userId where n.id={$dataId}");
        
        $dbData = $dbProd[0];
        echo json_encode(array('success' => true,'data'=>$dbData));
        return true;
    }
    echo json_encode(array('success'=>false,'message'=>'Matrix not found.'));
    return false;
}


/////////////////////////////////////
// ADD NEW MATRIX
function UpdateProdMatrix() {
    $shift = (isset($_REQUEST['shift'])) ? $_REQUEST['shift'] : '';
	$dataId = (isset($_REQUEST['dataId'])) ? $_REQUEST['dataId'] : '';
	$day = (isset($_REQUEST['day'])) ? $_REQUEST['day'] : '';
	$chargecount = (isset($_REQUEST['chargecount'])) ? $_REQUEST['chargecount'] : 0;
	$techcount = (isset($_REQUEST['techcount'])) ? $_REQUEST['techcount'] : 0;
	$seccount = (isset($_REQUEST['seccount'])) ? $_REQUEST['seccount'] : 0;
	$antecount = (isset($_REQUEST['antecount'])) ? $_REQUEST['antecount'] : 0;
	$acs = (isset($_REQUEST['acs'])) ? $_REQUEST['acs'] : 0;
	$am1 = (isset($_REQUEST['am1'])) ? $_REQUEST['am1'] : 0;
	$awcm = (isset($_REQUEST['awcm'])) ? $_REQUEST['awcm'] : 0;
	$obed = (isset($_REQUEST['obed'])) ? $_REQUEST['obed'] : 0;
	$ldcount = (isset($_REQUEST['ldcount'])) ? $_REQUEST['ldcount'] : 0;
	$ev = (isset($_REQUEST['ev'])) ? $_REQUEST['ev'] : 0;
	$scs = (isset($_REQUEST['scs'])) ? $_REQUEST['scs'] : 0;
	$cr = (isset($_REQUEST['cr'])) ? $_REQUEST['cr'] : 0;
	$pt = (isset($_REQUEST['pt'])) ? $_REQUEST['pt'] : 0;
	$ccs = (isset($_REQUEST['ccs'])) ? $_REQUEST['ccs'] : 0;
	$ps1 = (isset($_REQUEST['ps1'])) ? $_REQUEST['ps1'] : 0;
	$atotal = (isset($_REQUEST['atotal'])) ? $_REQUEST['atotal'] : 0;
	$ltotal = (isset($_REQUEST['ltotal'])) ? $_REQUEST['ltotal'] : 0;	
	$note = (isset($_REQUEST['note'])) ? $_REQUEST['note'] : '';
	$antecheck = 0;
	$laborcheck = 0;
	$antetotal = 0;
	$acheck = 0;
	$lchek = 0;
	
	$dbData = Config::get('db')->get_results("select n.*, s.value, a.hppd from `productiveNewData` n left join `productiveAccount` as a on a.id = n.accountId left join `productiveShifts` as s on s.id = n.shift where n.id={$dataId}");
	
	if($dbData){
		$hppd = floatval($dbData[0]['hppd']);
		$value = floatval($dbData[0]['value']);
		//$shifthrs = floatval($dbData[0]['shifthrs']);
	}else{
		$hppd =0;
		$value =0;
		//$shifthrs=0;
	}
	
	$accountId = Config::get('account')['accountId'];
	$userId = Config::get('account')['id'];	
	
			$acs1 = intval($acs) * 0.3;
			$am11 = intval($am1) * 0.5;
			$awcm1 = intval($awcm);
			$obed1 = intval($obed) * 0.3;
			
			$antepartumnurses = ceil($acs1+$am11+$awcm1+$obed1);
			$avariance = $antepartumnurses - intval($antecount);
			
			$ev1 = intval($ev) * 0.5;
			$scs1 = intval($scs) * 0.5;
			$cr1 = intval($cr) * 0.5;
			$pt1 = intval($pt);
			$ccs1 = intval($ccs);
			$ps11 = intval($ps1);
			
			$labornurses = ceil($ev1+$scs1+$cr1+$pt1+$ccs1+$ps11);
			$lvariance = $labornurses - intval($ldcount);			
			$nvariance = ($antepartumnurses + $labornurses) - ($antecount + $ldcount);
			
			if (intval($shift) !=4){
				$dbAprod = Config::get('db')->get_results("select n.*, s.value, a.hppd from `productiveNewData` n left join `productiveAccount` as a on a.id = n.accountId left join `productiveShifts` as s on s.id = n.shift where n.dayDate='{$day}' AND n.accountId={$accountId} AND n.shift=4");
				$antetotal = intval($dbAprod[0]['atotal']);
			}else{
				$antetotal = intval($atotal);
			}
			
			if($antetotal>0 && $antecount>0){
			$antehours = $value * $antetotal * $hppd;
			$antenurses = $antecount * 24 * $value;
			$aprod = ($antehours / $antenurses)*100;
			$aproductivity = round($aprod,2);
			}else{
			$aproductivity = 0.0;
			}	
			
			$acheck = $acs+$am1+$obed+$awcm;
			if($acheck == $atotal){
				$antecheck = true;
			}else{
				$antecheck = false;
			}
			$lcheck = $ev+$scs+$cr+$pt+$ccs+$ps1;
			if($lcheck == $ltotal){
				$laborcheck = true;
			}else{
				$laborcheck = false;
			}	
	if (intval($ldcount)>0) {			
		    $data = array(
                'chargecount' => $chargecount,
                'techcount' => $techcount,
				'seccount' => $seccount,
				'antecount' => $antecount,
				'userId' => $userId,
				'acs' => $acs,
				'am1' => $am1,
                'awcm' => $awcm,
				'obed' => $obed,
				'ldcount' => $ldcount,
                'ev' => $ev,
				'scs' => $scs,
				'cr' => $cr,
				'pt' => $pt,
                'ccs' => $ccs,
				'ps1' => $ps1,
				'avariance' => $avariance,
				'lvariance' => $lvariance,
				'nvariance' => $nvariance,
				'atotal' => $atotal,
				'ltotal' => $ltotal,
				'aproductivity' => $aproductivity,
				'note' => $note
            );
			$updateWhere = array('id' => $dataId);
            
			$newProd = Config::get('db')->update('productiveNewData',$data,$updateWhere);
			
			}
			
			if($avariance < 0 || $lvariance < 0){
						
				if($avariance <0 && $lvariance >=0){
					$rvariance = $avariance;
					$dep = "Antepartum Acuity";
				}else if($avariance >=0 && $lvariance <0){
					$rvariance = $lvariance;
					$dep = "Labor Acuity";
				}else if($avariance <0 && $lvariance <0){
					$rvariance = $avariance + $lvariance;
					$dep = "Total Acuity";
				}else{
					$rvariance = 0;
					$dep = "Total Acuity";
				}
				
				
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
				'note'=>$note
				);
			
				$dbAdmin = Config::get('db')->get_results("select * FROM `productiveUser` where `accountId`={$accountId} and `role`>4 and (`text`=1 or `text`=3)");
				$mobile = $dbAdmin[0]['mobile'];
				//$varnumber = $nvariance * -1;
				$message = "ProductiveHR Alert.  A Staffing Report has just been submitted with a ". $dep ." variance of " . $rvariance. ".  www.jobalarm.com/productive";
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

				$result = sendNow($smsoptions);
				
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



/////////////////////////////////////
// ADD CANDIDATE NOTE
function AddProdNote() {
    $note = isset($_REQUEST['note']) ? $_REQUEST['note'] : '';
    $dataId = isset($_REQUEST['dataId']) ? $_REQUEST['dataId'] : '';
	$accountId = Config::get('account')['accountId'];
	$userId = Config::get('account')['id'];
    
    if ($note && $dataId) {

       $data = array(
            'note' => Config::get('db')->filter($note),
            'userId' => Config::get('db')->filter($userId)
        );
		$updateWhere = array('id' => $dataId);

        Config::get('db')->update('productiveNewData', $data, $updateWhere);
//            Config::get('db')->update('sms_messages',array("groupId"=>intval($group)),array('id'=>intval($target)),1);
		
		echo json_encode(array('success' => true, 'note' => 'Added Successfully'));
        return true;
    }
    echo json_encode(array('success' => false, 'note' => 'Request Failed'));
    return false;
}



/////////////////////////////////////
// UPDATE MATRIX
function UpdateMatrix() {
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

function send_messages_start($smsAlex,$number,$keyword) {
    $post = '';
    $mobile = '';
    $header = 'Content-Type: application/xml';
    $post = $smsAlex;
    $output = "";
    $mobile = $number;
   
    $url = 'https://jobalarm.cloud.sloocetech.net/slooce_apps/spi/jobalarm45/' . $mobile . '/' . $keyword . '/messages/start';
    $output = curl_request(SLOOCE_LOGIN, $url, $post, $header);
    
    
   
    return $output; 
    
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
