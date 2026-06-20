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

if (isset($_REQUEST['adduser'])) {
    $accountId = $account_data['accountId'];
    $role = isset($_REQUEST['role']) ? $_REQUEST['role'] : '';
    $first = isset($_REQUEST['firstName']) ? $_REQUEST['firstName'] : '';
	$last = isset($_REQUEST['lastName']) ? $_REQUEST['lastName'] : '';
	$email= isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
	
	$id="";
	$temp = randomPassword();

	
	
	if ($email) {

	Config::get('db')->query("insert into users (role,accountId,first_name,last_name,email,password,temp) values({$role},{$accountId},'{$first}','{$last}','{$email}',md5('{$temp}'),md5('{$temp}')) on duplicate key update role={$role}");

	
	//if ( isset($_POST['email']) && isset($_POST['firstName']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ) {
	if ( isset($_POST['email']) && isset($_POST['firstName'])) {

 
	  // detect & prevent header injections
	  $test = "/(content-type|bcc:|cc:|to:)/i";
	  foreach ( $_POST as $key => $val ) {
	    if ( preg_match( $test, $val ) ) {
		   echo json_encode(array('success'=>false));
	      exit;
	    }
	  }
  		$company = (isset($_POST["company"])  && strlen($_POST['company']) > 0) ?  ", ".$_POST['company'] : '';
  	//send email
	  //mail( "rstrenger@jobalarm.com", "JobAlarm Login Info", $_POST['name']."\r\n\r\n".$_POST['company']."\r\n\r\n".$_POST['email']."\r\n\r\n".$_POST['text'], "From: JobAlarm ContactUs <rstrenger@jobalarm.com>"  );
	  $message ="A JobAlarm account has been created for you.  To access JobAlarm, please go to www.jobalarm.com/login.php.  Your ID is your email address and your temporary password is: ".$temp." to login.";
	  
	  mail( $_POST['email'], "JobAlarm Access", $_POST['firstName']."\r\n\r\n".$message,"From: JobAlarm Admin <rstrenger@jobalarm.com>". "\r\n" .
"CC: ".$account_data['email'] );
	  	  //echo json_encode(array('success'=>true));
	  exit();
}






   }
	
}



?>