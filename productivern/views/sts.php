<?php
session_start();
ini_set('display_errors',1);
//include_once '.././inc/class.db.php';
//include_once '.././inc/config.php';

$dataId = (isset($_REQUEST['c'])) ? $_REQUEST['c'] : '';
$label = (isset($_REQUEST['l'])) ? $_REQUEST['l'] : '';
$z = rand();
// If not logged in, redirect.

if (!isset($_SESSION['account'])) {
    header('location: ../' . $label . '?c=' . $dataId . '&z=' . $z);
    exit();
}else{
	//Config::set('account',$_SESSION['account']);
	header('location: ../dashboard.php#supportstaffing?c=' . $dataId);
	exit();
}

// Set the user account


?>

