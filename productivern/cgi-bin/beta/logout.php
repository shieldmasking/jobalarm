<?php
session_start();

include_once './inc/class.db.php';
include_once './inc/config.php';

$labelId = $_SESSION['account']['labelId'];
$label = $_SESSION['account']['label'];
$labelLogin = $_SESSION['account']['labelLogin'];
//echo json_encode(array('labelId ' =>$labelId));
$userId = $_SESSION['account']['id'];

if(intval($userId)>0){

$ctrData = Config::get('db')->get_results("SELECT * from `productiveLogin` where `userId`={$userId} order by `id` desc"); 


$id = $ctrData[0]['id'];
			
$now = time() - 21600;
$data = array('logout' => date("Y-m-d H:i:s", $now));
$where = array('id' => $id);
Config::get('db')->update('productiveLogin', $data, $where);

if(intval($labelId)>1 || intval($label)>1){
	session_destroy();
	$header = 'location: /' . $labelLogin . '';
	header($header);
	//header('Location: /perfectshift/');
}else{

	if (isset($_GET['c'])) {
		session_destroy();
		header('Location: login2.php');
		}else if (isset($_GET['a'])) {
		session_destroy();
		header('Location: login.php');
		}else{
	session_destroy();
	header('Location: login.php');
	}
}
}else{
	session_destroy();
	header('Location: login.php');
}
?>
