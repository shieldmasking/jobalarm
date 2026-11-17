<?php
session_start();
ini_set('display_errors',1);
//include_once '.././inc/class.db.php';
//include_once '.././inc/config.php';

//$dataId = (isset($_REQUEST['d'])) ? $_REQUEST['d'] : '';
$label = (isset($_REQUEST['l'])) ? $_REQUEST['l'] : '';
$d = rand();
// If not logged in, redirect.

if (!isset($_SESSION['account'])) {
    header('location: ../' . $label . '?d=' . $d);
    exit();
}else{
	//Config::set('account',$_SESSION['account']);
	header('location: ../dashboard.php#escalations');
	exit();
}

// Set the user account


?>

