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

$accountId = $account_data['accountId'];


if (isset($_REQUEST['assignStore'])) {


$userId = isset($_POST['userId']) ? $_POST['userId'] : '';
	$store = isset($_POST['storeId']) ? $_POST['storeId'] : '';
	
	//$dbData = Config::get('db')->query("update sms_stores set userId = {$userId} where id={$store}");
	$dbData = Config::get('db')->query("insert into assign_store (storeId,userId) values({$store},{$userId})");
	
	echo '{"success": true}';

}else{
	echo '{"success": false}';
}



?>