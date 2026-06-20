<?php

ini_set('display_errors',1);
require_once '../inc/class.db.php';
require_once '../inc/config.php';
//set_time_limit(0);


createLogs();


function createLogs(){	
$dbLogs = Config::get('db') -> get_results("SELECT * FROM `productivelogData` WHERE `active`=1");


foreach($dbLogs as $log) {
	$shift = $log['shift'];
	$deptId = $log['deptId'];
	$logId = $log['id'];
	$accountId = $log['accountId'];
	$datetime = new DateTime('tomorrow');
	$newDate = date_format($datetime,"Y/m/d");

		$logdata = array(
                'dayDate'=>$newDate,
				'deptId'=>$deptId,
				'logId'=>$logId,
                'shift'=>$shift,
                'accountId'=>$accountId
            );
			
    Config::get('db')->insert('productivecrashLog',$logdata);
	}
echo "success";

}

?>