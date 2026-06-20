<?php
require("inc/initializer.php");
require_once '../inc/class.db.php';
require_once '../inc/config.php';

// If not logged in, redirect.
if (!isset($_SESSION['account'])) {
    header('location: logout.php');
    exit();
}

// Set the user account
Config::set('account',$_SESSION['account']);

// If no account ID, redirect
if (!isset(Config::get('account')['accountId'])) {
    header('location: logout.php');
    exit();
}

$dbData = Config::get('db')->get_results("select * from `productiveAccount` where id=".$_SESSION['account']['accountId']);
if ($dbData[0]['image']) {
$logo = "../img/".$dbData[0]['image'];
}else{
$logo = "../img/logo1.png";
}

// Load Page
require("header.php");
require("body.php");
require("footer.php");