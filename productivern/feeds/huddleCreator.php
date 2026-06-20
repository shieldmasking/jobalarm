<?php
ini_set('display_errors',1);
require_once '../inc/class.db.php';
require_once '../inc/config.php';
//set_time_limit(0);

//$local_file =  '/home/tweetedjobs/feeds/snag/Snagajob-Job-Feed_CUSTOM.XML';

//getDataFeed();
//parsexml2($local_file);
//statuszero();

createSafety();



function createSafety(){	
$dbSafety = Config::get('db') -> get_results("SELECT c.*, l.useHuddle, DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL 1 DAY),'%Y-%m-%d') as dateTomorrow FROM `productivelogData` c LEFT JOIN `ProductiveDept` as l on l.id=c.deptId WHERE l.useHuddle=1 AND l.active=1 and c.logType=2 GROUP BY c.id");

if($dbSafety){
foreach($dbSafety as $log) {
	$deptId = $log['deptId'];
	$logId = $log['id'];
	//$shift = $log['shift'];
	//$dir = $log['q21'];
	$accountId = $log['accountId'];
	//$datetime = new DateTime('tomorrow');
	//$newDate = date_format($datetime,"Y-m-d");
	$newDate = $log['dateTomorrow'];
		$safetydata = array(
                'deptId'=>$deptId,
				'accountId'=>$accountId,
				'dayDate'=>$newDate,
				'logId'=>$logId
            );
			
    Config::get('db')->insert('productivecrashLog',$safetydata);
	
}
}
}


?>
