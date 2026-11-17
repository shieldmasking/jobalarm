<?php
session_start();
$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $p = session_get_cookie_params();
    setcookie(session_name(), '', time() - 3600, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
}

include_once './inc/class.db.php';
include_once './inc/config.php';

$labelId = $_SESSION['account']['labelId'];
$label = $_SESSION['account']['label'];
$labelLogin = $_SESSION['account']['labelLogin'];
$labelLogout = $_SESSION['account']['labelLogout'];
//echo json_encode(array('labelId ' =>$labelId));
$userId = $_SESSION['account']['id'];

if(intval($userId)>0){

$ctrData = Config::get('db')->get_results("SELECT l.*, a.timeZone from `productiveLogin` l LEFT JOIN `productiveUser` as u on u.id = l.userId LEFT JOIN `productiveAccount` as a on a.id = u.accountId where l.userId={$userId} order by l.id desc"); 


$id = $ctrData[0]['id'];
			
$now = time() - (3 + intval($ctrData[0]['timeZone'])) * 60 *60;
$data = array('logout' => date("Y-m-d H:i:s", $now));
$where = array('id' => $id);
Config::get('db')->update('productiveLogin', $data, $where);

if(intval($labelId)>1 || intval($label)>1){
	session_destroy();
	$header = 'location: ' . $labelLogout . '';
	header($header);
	//header('Location: /perfectshift/');
}else{

	if (isset($_GET['c'])) {
		session_destroy();
		header('Location: /go/index.php');
		}else if (isset($_GET['a'])) {
		session_destroy();
		header('Location: /go/index.php');
		}else{
	session_destroy();
	header('Location: /go/index.php');
	}
}
}else{
	session_destroy();
	header('Location: /go/index.php');
}
?>
