<?php
session_start();
ini_set('display_errors',1);
include_once 'inc/class.db.php';
include_once 'inc/class.jatwitter.php';
include_once 'inc/config.php';

if (!isset($_SESSION['account'])) {
    header('location: login.php');
}

$account_data = $_SESSION['account'];
if (!isset($account_data['accountId'])) {
	header('location: login.php');
	exit();
}


if (isset($_REQUEST['addjob'])) {
    $accountId = $account_data['accountId'];
    $brand = isset($_REQUEST['jobbrand']) ? $_REQUEST['jobbrand'] : '';
	$storeNum = isset($_REQUEST['jobstoreNum']) ? $_REQUEST['jobstoreNum'] : '';
	$title= isset($_REQUEST['jobtitle']) ? $_REQUEST['jobtitle'] : '';
	//$description = isset($_REQUEST['jobdescription']) ? $_REQUEST['jobdescription'] : '';
    $id='';
	$hashTags="";
	$text="";
	$twitterId="";
	$status=2;
	
	$dbTwitterId = Config::get('db') -> get_results("SELECT * from job where `id` in (select MAX(id) FROM job)");
	$twitter = intval($dbTwitterId[0]['id']) + 1;
	$twitterId = "JA".$twitter;
	
	
	$dbData = Config::get('db') -> get_results("select s.*, b.storeBrand, b.searchKeys from `sms_stores` s left join `sms_brand` as b on b.id = s.brandId where s.id={$storeNum}");
	$store = $dbData[0];
	$hashtag = "JobAlarm".",".$store['searchKeys'];
	$text = $title." position available with ".addslashes($store['storeBrand'])." in ".$store['city'].", ".$store['st']." Mobile Apply Now!";
	//$title = $title." JA Mobile Apply";
	
		if ($dbData) {

			Config::get('db')->query("insert into job (twitterId,text,title,hashTags,brand,city,state,zipCode,status,postId) values('{$twitterId}','{$text}','{$title}','{$hashtag}',{$brand},'{$store['city']}','{$store['st']}','{$store['zip']}',{$status},{$accountId}) on duplicate key update city='{$city}'");
		    $id = Config::get('db')->lastid();
						
		}
	
	echo '{"success": true}';
}

?>