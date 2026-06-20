<?php
error_reporting(E_ALL); 
ini_set('display_errors', 1);
include '../inc/class.db.php';
include '../inc/config.php';

delLogix();

function delLogix(){
$dbLogix = Config::get('db')->get_results("SELECT a.*, IFNULL(DATEDIFF(CURDATE(),DATE_ADD(a.payPeriodFirst, INTERVAL (FLOOR(DATEDIFF(CURDATE(),a.payPeriodFirst)/a.payPeriod)*a.payPeriod) DAY)),0) as newStart, IFNULL(DATEDIFF(CURDATE(),DATE_ADD(a.payPeriodFirst, INTERVAL (FLOOR(DATEDIFF(CURDATE(),a.payPeriodFirst)/a.payPeriod)*a.payPeriod) DAY))-(a.payPeriod-1),0) as newEnd from `productiveAccount` a  WHERE a.active=1 AND a.payPeriod > 0 AND a.payPeriodFirst !='0000-00-00' GROUP BY a.id");

	if($dbLogix){
		foreach($dbLogix as $log){
			$data = array(
						'newStartPay' => $log['newStart'],
						'newEndPay' => $log['newEnd']
						);
			$where = array(
						'id' => $log['id']
						);
			Config::get('db')->update('productiveAccount',$data,$where);
		}
		return true;	
	}else{
		return true;
	}
}


?>
