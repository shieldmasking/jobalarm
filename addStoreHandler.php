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


if (isset($_REQUEST['addstore'])) {
    $accountId = $account_data['accountId'];
    $brand = isset($_REQUEST['brand']) ? $_REQUEST['brand'] : '';
	$storeNum = isset($_REQUEST['storeNum']) ? $_REQUEST['storeNum'] : '';
	$address= isset($_REQUEST['address']) ? $_REQUEST['address'] : '';
	$city = isset($_REQUEST['city']) ? $_REQUEST['city'] : '';
    $st = isset($_REQUEST['state']) ? $_REQUEST['state'] : '';
    $zip_code = isset($_REQUEST['zipcode']) ? $_REQUEST['zipcode'] : '';
	$zip_code = substr($zip_code,0,5);
	$user= isset($_REQUEST['assign']) ? $_REQUEST['assign'] : '0';
	$id="";
	$active=1;
	
	
		if ($brand) {

			Config::get('db')->query("insert into sms_stores (accountId,brandId,storeNum,address,city,st,zip,active) values({$accountId},{$brand},'{$storeNum}','{$address}','{$city}','{$st}','{$zip_code}',{$active}) on duplicate key update city='{$city}'");
		    $id = Config::get('db')->lastid();
			if ($user != -1){
			Config::get('db')->query("insert into assign_store (storeId,userId) values({$id},{$user}) on duplicate key update userId={$user}");
		    }
				
			/*$query = "SELECT s.*, u.id as brandUrl FROM `sms_jobs` s LEFT JOIN `accountUrls` as u on u.brandId = s.brandId where s.brandId =" . $brand;

			$dbJobs = Config::get('db')->get_results($query);
			

			foreach($dbJobs as $j) {
			
			$jobid = $j['id'];
			$brandUrl = $j['brandUrl'];
			
			
			Config::get('db')->query("insert into `sms_posts` (jobId,storeId,url) values({$jobid},{$id},{$brandUrl}) on duplicate key update storeId={$id}");

			}*/
		}
	
	echo '{"success": true}';
}

?>