<?php
session_start();
ini_set('display_errors',1);
include '../inc/class.db.php';
include '../inc/class.jatwitter.php';
include '../inc/config.php';
if (!isset($_SESSION['account'])) {
    header('location: ../index.php');
}
$account_data = $_SESSION['account'];
if (!($account_data['role'] >2)) { 
header('location: ../index.php'); exit(); 
}

$dbGroups = Config::get('db')->get_results("select * from job_group_members_update");
 
foreach ($dbGroups as $job) {
$members = $job['members'];
$groupId = $job['fb_groupId'];

$data = array(
			'member_count'=>$members
			);
		$where = array('group_fb_id'=>$groupId);
		Config::get('db')->update('job_group_members',$data,$where);
		} 
		echo "success";

?>
