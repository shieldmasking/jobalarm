<?php
session_start();
ini_set('display_errors',1);
include_once '.././inc/class.db.php';
include_once '.././inc/config.php';

$dataId = (isset($_REQUEST['i'])) ? $_REQUEST['i'] : '';
$label = (isset($_REQUEST['l'])) ? $_REQUEST['l'] : '';
// If not logged in, redirect.

if (!isset($_SESSION['account'])) {
    header('location: ../' . $label . '?i=' . $dataId);
    exit();
}else{
	//Config::set('account',$_SESSION['account']);
	header('location: ../dashboard.php#reportView?i=' . $dataId);
	exit();
}

// Set the user account


?>

