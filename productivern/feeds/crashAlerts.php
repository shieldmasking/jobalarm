<?php
error_reporting(E_ALL); 
ini_set('display_errors', 1);
require_once '.././inc/class.db.php';
require_once '.././inc/config.php';

logAlerts();

function logAlerts(){	
$dbLogs = Config::get('db') -> get_results("SELECT u.* FROM `productiveUser` u WHERE u.active>0 and u.txtPause=0 and LENGTH(u.mobile)=10 group by u.id");
$output = ""; 
	
if(count($dbLogs)>0){
foreach($dbLogs as $log) {
	$userId = $log['id'];
	$mobile1 = $log['mobile'];
	$accountId = $log['accountId'];
	 
	$dbMobile = Config::get('db') -> get_results("SELECT t.*, c.userId as logUser, a.title, a.dow, DAYOFWEEK(CURDATE()) as daya, DATE_FORMAT(s.shiftTime, '%H:%i') as shiftTime, TIME_FORMAT(CURTIME(), '%H:%i') as currentTime, a.deptId, d.dept as deptName FROM `productivetaskText` t LEFT JOIN `productivecrashLog` as c on c.logId = t.taskId LEFT JOIN `productivelogData` as a on a.id=t.taskId LEFT JOIN `ProductiveDept` d on d.id = a.deptId LEFT JOIN `productiveShifts` as s on s.id=a.shift WHERE c.dayDate=CURDATE() and c.userId=0 AND a.active>0 AND t.userId={$userId} AND t.textMe=1 AND t.accountid={$accountId} GROUP by t.taskId ORDER BY d.dept ASC");
	
	$reports = '';
	$sinle = '';
	$units = '';
	$brandName = '';
	$brandName .='';
	
	if(count($dbMobile)>0){
	$i=0;	
	
	foreach($dbMobile as $brand) {
	$dow = $brand['dow'];
	$daya = $brand['daya'];
	$deptId = $brand['deptId'];
	$userId = $brand['userId'];
	$timeDiff = strtotime($brand['currentTime']) - strtotime($brand['shiftTime']);
	
	if(strtotime($brand['currentTime']) > strtotime($brand['shiftTime']) && (str_contains($dow,$daya) || str_contains($dow,'8')) && $timeDiff<3600){
	$brandName .= $brand['deptName'] . ", ";
	$i=$i+1;
	}
	}
	
	if($i==1){
	$single = $brand['title'] . " is";
	}else{
	$single = "You have multiple tasks";
	}
	
	if($i>0){
		$pw = generateRandomString(10);
		$logix = $pw . $brand['accountId'];
		$newLink = "https://www.productivern.com/go/lndex.php?s=" . $deptId . "&p=98&m=" . $logix;
		$data = array('logix'=>$logix,
							'logixTime'=>date("Y-m-d"));
		$updateWhere = array('id' =>$userId);
		Config::get('db')->update('productiveUser',$data,$updateWhere);
		
	$units = rtrim($brandName, ", ");	
	$message = "ProductiveRN Alert: " . $single . " overdue for " . $units . ".  " . $newLink . "";
	$mobile = "1" . $mobile1;
	//echo 'mobile: ' . $mobile;
	$psword = "J8775bcgEE2065";
	$now = time();
	$msgId = "";
    $msgId .= "";
    $msgId .= $mobile . $now;
    $xmlMsg = "";
    $xmlMsg .= "<message id=\"".$msgId."\">";
    $xmlMsg .= "<partnerpassword>".$psword."</partnerpassword>";
    $xmlMsg .= "<content><![CDATA[" . $message . "]]></content>";
    $xmlMsg .= "</message>";
	
	$post = '';
    $keyword = '';
    $header = Array("Content-Type: application/xml");
    $post = $xmlMsg;
	$keyword3 = "PN18339330602";
	$slooce = "jobalarm45";
    $output = "";     
	$url = 'https://jobalarm.cloud.sloocetech.net/slooce_apps/spi/jobalarm45/' . $mobile . '/' . $keyword3 . '/messages/startmt';
    $output = curl_request($slooce, $url, $header, $post);
	}
	}
}
}
	return $output;
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

function generateRandomString($length=6) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}






?>
