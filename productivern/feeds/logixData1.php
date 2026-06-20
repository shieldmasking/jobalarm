<?php
error_reporting(E_ALL); 
ini_set('display_errors', 1);
include '../inc/class.db.php';
include '../inc/config.php';

delLogix();
delCoverage();

function delLogix(){
$dbLogix = Config::get('db')->get_results("SELECT * from `productiveUser` WHERE `logixTime` < DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND `logixTime` !='' AND `logixTime` >'0000-00-00' ");
	if($dbLogix){
		foreach($dbLogix as $log){
			$data = array(
						'logix' => '',
						'logixTime' => '0000-00-00'
						);
			$where = array(
						'id' => $log['id']
						);
			Config::get('db')->update('productiveUser',$data,$where);
		}
		return true;	
	}else{
		return true;
	}
}

function delCoverage(){
$dbCover = Config::get('db')->get_results("SELECT * from `productiveDeptXref` where `unitAssigned`=2 AND `endCoverage` !='' AND `endCoverage` >'0000-00-00' AND `endCoverage` < CURDATE()");
	if($dbCover){
		foreach($dbCover as $cover){
			$del = array(
						'id' => $cover['id']
						);
			Config::get('db')->delete('productiveDeptXref',$del);
		}
		return true;	
	}else{
		return true;
	}
}


?>
