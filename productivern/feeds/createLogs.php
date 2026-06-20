<?php
ini_set('display_errors',1);
require_once '../inc/class.db.php';
require_once '../inc/config.php';

createLogs();


function createLogs(){	
$dbLogs = Config::get('db') -> get_results("SELECT l.*, u.id as userId FROM `productivelogData` l LEFT JOIN `productiveDeptXref` as x on x.deptId=l.deptId and x.primaryUnit=4 LEFT JOIN `productiveUser` as u on u.id=x.userId WHERE u.practiceId=1 AND u.logix2>0 AND l.active=1 GROUP BY l.id");


foreach($dbLogs as $log) {
	$shift = $log['shift'];
	$deptId = $log['deptId'];
	$userId = $log['userId'];
	$accountId = $log['accountId'];
	$datetime = new DateTime('tomorrow');
	$newDate = date_format($datetime,"Y/m/d");

		$logdata = array(
                'dayDate'=>$newDate,
				'deptId'=>$deptId,
                'shift'=>$shift,
                'accountId'=>$accountId,
				'userId'=>$userId	
            );
			
    Config::get('db')->insert('productivecrashLog',$logdata);
	}
echo "success";

}