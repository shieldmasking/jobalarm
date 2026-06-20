<?php
session_start();
ini_set('display_errors',1);

if (!isset($_SESSION['account'])) {
    header('location: ../login.php?p=1');
}
$account_data = $_SESSION['account'];
if (!($account_data['role'] >2)) { 
header('location: ../login.php?p=1'); exit(); 
} 
  header( "refresh:1; url=http://www.jobalarm.com/messenger/messageApp/messages.html#" ); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>JobAlarm = Real-Local-Jobs</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta content="" name="description">
<meta content="" name="author">
<meta property="og:title" content="Search Real Jobs">
<meta property="og:url" content="http://www.job-alarm.co">
<meta property="og:image" content="http://www.job-alarm.co/job2.jpg">
<meta property="og:description" content="JobAlarm.com allows Job Seekers to search the jobs that Employers are tweeting about.  Search Real Local Jobs">
</head>
</html>