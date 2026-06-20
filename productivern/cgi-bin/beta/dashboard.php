<?php
require("inc/initializer.php");
require_once './inc/class.db.php';
require_once './inc/config.php';

//echo json_encode(array('now'=>$_SESSION['account']['login_time']));
  //echo json_encode(array('time'=>time()));

if (!isset($_SESSION['account']) || ($_SESSION['account']['pwd'] == $_SESSION['account']['temp'])) {
	session_destroy();
    header('location: login.php');
    exit();
} 
$time = time();
$login = intval($SESSION['account']['login_time']);
/*
if (($time - $login)>600){
		header('location: logout.php');
	}else{
		$SESSION['account']['login_time'] = time();
	}
*/


// Set the user account
Config::set('account',$_SESSION['account']);

$logo1 = "../img/logowhite.png";

if(intval($_SESSION['account']['role'])<12){
$dbData = Config::get('db')->get_results("select a.*, e.image as enterpriseImage from `productiveAccount` a left join `productiveEnterprise` as e on e.id = a.enterpriseId where a.id=".$_SESSION['account']['accountId']."");
$logo1 = "../img/".$dbData[0]['image'];
}else if(intval($_SESSION['account']['role'])>=12 && intval($_SESSION['account']['role'])<90){
$dbData = Config::get('db')->get_results("select e.* FROM `productiveEnterprise` e where e.id=".$_SESSION['account']['enterpriseId']."");	
$logo1 = "../img/".$dbData[0]['image'];
}else{
$logo1 = "../img/logowhite.png";
}


// Load Page
require("header.php");
require("body.php");
require("footer.php");