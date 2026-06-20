<?php
ini_set('display_errors',1);
include_once '../inc/class.db.php';
include_once '../inc/class.jatwitter.php';
include_once '../inc/config.php';


if (isset($_REQUEST['updateCandidate'])) {

$aId = isset($_POST['aId']) ? $_POST['aId'] : '';
$permitYes = isset($_POST['permitYes']) ? $_POST['permitYes'] : '';
$permitNo = isset($_POST['permitNo']) ? $_POST['permitNo'] : '';
$ref = '';
$true = "TRUE";
$no = "No";

if (strtoupper($permitYes) == strtoupper($true)){
	$ref = "Yes";
}
if (strtoupper($permitNo) == strtoupper($true)){
	$ref = "No";
}


	//$dbData = Config::get('db')->query("update sms_stores set userId = {$userId} where id={$store}");
	//$dbData = Config::get('db')->query("insert into assign_store (storeId,userId) values({$store},{$userId})");
	
		$updatedata = array(
			'workPermit'=>$ref,
			'age'=>$no
			);
		$updatewhere = array('id'=>$aId);
		Config::get('db')->update('candidateApply',$updatedata,$updatewhere);
			
	
	echo '{"success": true}';

}else{
	echo '{"success": false}';
}

if (isset($_REQUEST['updateCandidateOver'])) {

$aId = isset($_POST['aId']) ? $_POST['aId'] : '';
$permitYes = isset($_POST['permitYes']) ? $_POST['permitYes'] : '';
$permitNo = isset($_POST['permitNo']) ? $_POST['permitNo'] : '';
$ref = '';
$true = "TRUE";
$no = "No";

if (strtoupper($permitYes) == strtoupper($true)){
	$ref = "Yes";
}
if (strtoupper($permitNo) == strtoupper($true)){
	$ref = "No";
}


	//$dbData = Config::get('db')->query("update sms_stores set userId = {$userId} where id={$store}");
	//$dbData = Config::get('db')->query("insert into assign_store (storeId,userId) values({$store},{$userId})");
	
		$updatedata = array(
			'over21'=>$ref,
			'age'=>$ref
			);
		$updatewhere = array('id'=>$aId);
		Config::get('db')->update('candidateApply',$updatedata,$updatewhere);
			
	
	echo '{"success": true}';

}else{
	echo '{"success": false}';
}

if (isset($_REQUEST['updateCandidate2'])) {

$aId = isset($_POST['aId']) ? $_POST['aId'] : '';
$long = isset($_POST['howlong']) ? $_POST['howlong'] : '';
$current = isset($_POST['current']) ? $_POST['current'] : '';
$referenceYes = isset($_POST['referenceYes']) ? $_POST['referenceYes'] : '';
$referenceNo = isset($_POST['referenceNo']) ? $_POST['referenceNo'] : '';
//$currentEmpYes = isset($_POST['currentEmpYes']) ? $_POST['currentEmpYes'] : '';
//$currentEmpNo = isset($_POST['currentEmpNo']) ? $_POST['currentEmpNo'] : '';

$ref = '';
$empYes = "Yes";
$true = "TRUE";

if (strtoupper($referenceYes) == strtoupper($true)){
	$ref = "Yes";
}
if (strtoupper($referenceNo) == strtoupper($true)){
	$ref = "No";
}


	//$dbData = Config::get('db')->query("update sms_stores set userId = {$userId} where id={$store}");
	//$dbData = Config::get('db')->query("insert into assign_store (storeId,userId) values({$store},{$userId})");
	
		$updatedata = array(
			'current'=>$current,
			'currentLong'=>$long,
			'currentReference'=>$ref,
			'currentEmp'=>$empYes
			);
		$updatewhere = array('id'=>$aId);
		Config::get('db')->update('candidateApply',$updatedata,$updatewhere);
			
	
	echo '{"success": true}';

}else{
	echo '{"success": false}';
}


if (isset($_REQUEST['updateCandidate3'])) {

$aId = isset($_POST['aId']) ? $_POST['aId'] : '';
$pastLong = isset($_POST['pastLong']) ? $_POST['pastLong'] : '';
$previous = isset($_POST['previous']) ? $_POST['previous'] : '';
$pastReferenceYes = isset($_POST['pastReferenceYes']) ? $_POST['pastReferenceYes'] : '';
$pastReferenceNo = isset($_POST['pastReferenceNo']) ? $_POST['pastReferenceNo'] : '';

$ref = '';
$past = "Yes";
$true = "TRUE";

if (strtoupper($pastReferenceYes) == strtoupper($true)){
	$ref = "Yes";
}
if (strtoupper($pastReferenceNo) == strtoupper($true)){
	$ref = "No";
}


	//$dbData = Config::get('db')->query("update sms_stores set userId = {$userId} where id={$store}");
	//$dbData = Config::get('db')->query("insert into assign_store (storeId,userId) values({$store},{$userId})");
	
		$updatedata = array(
			'previous'=>$previous,
			'pastLong'=>$pastLong,
			'pastReference'=>$ref,
			'pastEmp'=>$past			
			);
		$updatewhere = array('id'=>$aId);
		Config::get('db')->update('candidateApply',$updatedata,$updatewhere);
			
	
	echo '{"success": true}';

}else{
	echo '{"success": false}';
}

if (isset($_REQUEST['updateCandidate4'])) {

$aId = isset($_POST['aId']) ? $_POST['aId'] : '';
$prefer1 = isset($_POST['prefer1']) ? $_POST['prefer1'] : '';
$prefer2 = isset($_POST['prefer2']) ? $_POST['prefer2'] : '';
$day = isset($_POST['day']) ? $_POST['day'] : '';
$no = "No";
$days = "";
$days .="";


foreach ($day as $dayz){
	$days .= $dayz.", ";
	
}

print_r(array_values($day));

echo print_r(array_values($day));

	
		$updatedata = array(
			'prefer1'=>$prefer1,
			'prefer2'=>$prefer2,
			'day'=>$days,
			'schedule'=>$no
			);
		$updatewhere = array('id'=>$aId);
		Config::get('db')->update('candidateApply',$updatedata,$updatewhere);
			
	
	echo '{"success": true}';

}else{
	echo '{"success": false}';
}

if (isset($_REQUEST['updateCandidate5'])) {

$aId = isset($_POST['aId']) ? $_POST['aId'] : '';
$type5 = isset($_POST['type5']) ? $_POST['type5'] : '';
//$position = '';

$dbCandidate = Config::get('db')->get_results("SELECT * FROM `candidateApply` where `id`={$aId}");
if ($dbCandidate[0]['position']){
$position = $dbCandidate[0]['position'];
$positions = $position.",".$type5;
}else{
$positions = $type5;
}
	
		$updatedata = array(
			'position'=>$positions
			);
		$updatewhere = array('id'=>$aId);
		Config::get('db')->update('candidateApply',$updatedata,$updatewhere);
			
	
	echo '{"success": true}';

}else{
	echo '{"success": false}';
}



?>