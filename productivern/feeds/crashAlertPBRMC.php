<?php
error_reporting(E_ALL); 
ini_set('display_errors', 1);
include '../inc/class.db.php';
include '../inc/config.php';

matrixAlerts1();

function matrixAlerts1(){	
$dbBrands = Config::get('db') -> get_results("SELECT n.*, d.active, d.dept, s.shift as shiftname, DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -2 HOUR), '%Y-%m-%d') as todayDate FROM `productivecrashLog` n left join `ProductiveDept` as d on d.id=n.deptId left join `productiveShifts` as s on s.id=n.shift where d.active=1 and n.shift=28 and d.accountId=26 and n.userId=0 and n.dayDate= CURDATE() group by n.deptId");
$output = ""; 
if($dbBrands){
	$brandName = '';
	$brandName .='';
foreach($dbBrands as $brand) {
	$brandName .= $brand['dept'] . ", ";
	}
	$units = rtrim($brandName, ", ");
	if(count($dbBrands)==1){
	$reports = "Audit is";	
	}else{
		$reports = "Audits are";
	}
$dbMobile = Config::get('db') -> get_results("SELECT n.*, u.mobile, d.active, d.dept, s.shift as shiftname, DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -2 HOUR), '%Y-%m-%d') as todayDate FROM `productivecrashLog` n left join `ProductiveDept` as d on d.id=n.deptId left join `productiveShifts` as s on s.id=n.shift LEFT JOIN `productiveDeptXref` as x on x.deptId=n.deptId and x.primaryUnit>5 LEFT OUTER JOIN `productiveUser` as u on u.id=x.userId where d.active=1 and n.shift=28 and d.accountId=26 and u.accountId=26 and u.logAlerts=1 and n.userId=0 and n.dayDate= CURDATE() group by u.mobile");	

foreach($dbMobile as $mobile) {
	
	$message = "ProductiveRN Alert: Crash Cart " . $reports . " now overdue for " . $units . "";
	$mobile = "1" . $mobile['mobile'];
	//$mobile = "12148500163";
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
    $output = curl_request($slooce, $url, $post, $header);
	}
}
	return $output;
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








?>
