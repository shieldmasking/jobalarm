<?php
include '../inc/class.db.php';
include '../inc/config.php';
//set_time_limit(0);

//$local_file =  '/home/tweetedjobs/feeds/snag/Snagajob-Job-Feed_CUSTOM.XML';

//getDataFeed();
//parsexml2($local_file);
//statuszero();

createDays();
//createWHP();
//createDayRank();




function createDays(){	
$dbBrands = Config::get('db') -> get_results("select a.*, d.prodMeasure, d.crashLog, d.hppd, d.daysWk, d.charge, DAYOFWEEK(curdate()) as weekDay from `productiveAccountXref` a LEFT JOIN `ProductiveDept` as d on d.id = a.dept where d.active>0 AND d.prodMeasure !=2 AND d.prodMeasure !=3 GROUP by a.id");


foreach($dbBrands as $brand) {
	$shift = $brand['shift'];
	$dept = $brand['dept'];
	$type = $brand['type'];
	$shiftOrder = $brand['shiftOrder'];
	$prodMeasure = intval($brand['prodMeasure']);
	//$crashLog = intval($brand['crashLog']);
	$accountId = $brand['accountId'];
	//$whpPlan = $brand['whpPlan'];
	$datetime = new DateTime('today');
	$newDate = date_format($datetime,"Y/m/d");
	$daysWk = intval($brand['daysWk']);
	$weekDay = intval($brand['weekDay']);
	$chargeCount=$brand['charge'];
	
	if($daysWk >= $weekDay){
		$data = array(
                'dayDate'=>$newDate,
				'deptId'=>$dept,
				'chargecount'=>$chargeCount,
                'shift'=>$shift,
                'accountId'=>$accountId,
				'type'=>$type,
				'shiftOrder'=>$shiftOrder,
				'hppdOrig'=>$brand['hppd']
            );
			
    Config::get('db')->insert('productiveNewData',$data);
	}else{
		//do nothing;
	}

}
echo "success";

}

function createDemoData(){	
$dbDemo = Config::get('db') -> get_results("select a.*, d.active, d.prodMeasure from `productiveNewData` a LEFT JOIN `ProductiveDept` as d on d.id = a.deptId where (d.accountId =9 OR d.accountId =8) AND d.active>0 and d.prodMeasure=2 and a.dayDate = curdate() - INTERVAL 7 DAY");


foreach($dbDemo as $demo) {
	$accountId = $demo['accountId'];
	$userId = $demo['userId'];
	$deptId = $demo['deptId'];
	$dayDate = $demo['dayDate'];
	$shift = $demo['shift'];
	$chargecount = $demo['chargecount'];
	$techcount = $demo['techcount'];
	$aptechcount = $demo['aptechcount'];
	$seccount = $demo['seccount'];
	$sittercount = $demo['sittercount'];
	$antecount = $demo['antecount'];
	$ldcount = $demo['ldcount'];
	$ocount = $demo['ocount'];
	$acs = $demo['acs'];
	$am1 = $demo['am1'];
	$awcm = $demo['awcm'];
	$obed = $demo['obed'];
	$obed1 = $demo['obed1'];
	$ev = $demo['ev'];
	$scs = $demo['scs'];
	$cr = $demo['cr'];
	$pt = $demo['pt'];
	$ccs = $demo['ccs'];
	$ps1 = $demo['ps1'];
	$pp = $demo['pp'];
	$avariance = $demo['avariance'];
	$lvariance = $demo['lvariance'];
	$nvariance = $demo['nvariance'];
	$note = $demo['note'];
	$atotal = $demo['atotal'];
	$ltotal = $demo['ltotal'];
	$aproductivity = $demo['aproductivity'];
	$lproductivity = $demo['lproductivity'];
	$nproductivity = $demo['nproductivity'];
	$oneto1 = $demo['oneto1'];
	$oneto2 = $demo['oneto2'];
	$oneto3 = $demo['oneto3'];
	$oneto4 = $demo['oneto4'];
	$oneto5 = $demo['oneto5'];
	$oneto6 = $demo['oneto6'];
	$whpPlan = $demo['whpPlan']; 
	$skill1val = $demo['skill1val'];
	$skill2val = $demo['skill2val'];
	$skill3val = $demo['skill3val'];
	$skill4val = $demo['skill4val'];
	$skill5val = $demo['skill5val'];
	$skill6val = $demo['skill6val'];
	$addResourceHrs = $demo['addResourceHrs'];
	$twoto1 = $demo['twoto1'];
	$procedureCount = $demo['procedureCount'];
	$actualWHP = $demo['actualWHP'];
	$plannedHours = $demo['plannedHours'];
	$plannedProcs = $demo['plannedProcs'];
	$admits = $demo['admits'];
	$transfers = $demo['transfers'];
	$discharges = $demo['discharges'];
	$budgetValue = $demo['budgetValue'];
	$churnValue = $demo['churnValue'];
	
	$datetime = new DateTime('today');
	$newDate = date_format($datetime,"Y-m-d");
	
		$demodata = array(
                'accountId' => $accountId,
				'userId' => $userId,
				'deptId' => $deptId,
				'dayDate' => $newDate,
				'shift' => $shift,
				'chargecount' => $chargecount, 
				'techcount' => $techcount,
				'aptechcount' => $aptechcount,
				'seccount' => $seccount,
				'sittercount' => $sittercount,
				'antecount' => $antecount,
				'ldcount' => $ldcount,
				'ocount' => $ocount,
				'acs' => $acs,
				'am1' => $am1,
				'awcm' => $awcm,
				'obed' => $obed,
				'obed1' => $obed1,
				'ev' => $ev,
				'scs' => $scs,
				'cr' => $cr,
				'pt' => $pt,
				'ccs' => $ccs,
				'ps1' => $ps1,
				'pp' => $pp,
				'avariance' => $avariance,
				'lvariance' => $lvariance,
				'nvariance' => $nvariance,
				'note' => $note,
				'atotal' => $atotal,
				'ltotal' => $ltotal,
				'aproductivity' => $aproductivity, 
				'lproductivity' => $lproductivity,
				'nproductivity' => $nproductivity,
				'oneto1' => $oneto1,
				'oneto2' => $oneto2,
				'oneto3' => $oneto3,
				'oneto4' => $oneto4,
				'oneto5' => $oneto5,
				'oneto6' => $oneto6,
				'whpPlan' => $whpPlan,
				'skill1val' => $skill1val,
				'skill2val' => $skill2val,
				'skill3val' => $skill3val,
				'skill4val' => $skill4val,
				'skill5val' => $skill5val,
				'skill6val' => $skill6val,
				'addResourceHrs' => $addResourceHrs,
				'twoto1' => $twoto1,
				'procedureCount' => $procedureCount, 
				'actualWHP' => $actualWHP,
				'plannedHours' => $plannedHours,
				'plannedProcs' => $plannedProcs,
				'admits' => $admits,
				'transfers' => $transfers,
				'discharges' => $discharges,
				'budgetValue' => $budgetValue,
				'churnValue' => $churnValue
            );
		$demowhere = array(
                'dayDate'=>$newDate,
				'deptId'=>$deptId,
                'shift'=>$shift,
                'accountId'=>$accountId
            );
			
            Config::get('db')->update('productiveNewData',$demodata,$demowhere);
}

echo "success";	

}

function createWHP(){	
$dbBrands = Config::get('db') -> get_results("select a.*, d.active, d.hppd, d.daysWk, DAYOFWEEK(curdate()) as weekDay from `productiveAccountXref` a LEFT JOIN `ProductiveDept` as d on d.id = a.dept where d.active>0 AND d.prodMeasure=2");
//$dbBrands = Config::get('db') -> get_results("select a.*, d.active, d.daysWk, DAYOFWEEK(curdate()) as weekDay from `productiveAccountXref` a LEFT JOIN `ProductiveDept` as d on d.id = a.dept LEFT JOIN `productiveNewData` as n on n.deptId=a.dept and n.shift=a.shift where d.active>0 and d.prodMeasure=2 AND n.dayDate=CURDATE() and a.whpPlan=0 GROUP BY a.dept, a.shift");


foreach($dbBrands as $brand) {
	$shift = $brand['shift'];
	$dept = $brand['dept'];
	$type = intval($brand['type']);
	$daysWk = intval($brand['daysWk']);
	$accountId = $brand['accountId'];
	$datetime = new DateTime('tomorrow');
	$date = strtotime("+7 day");
	//$newDate = date_format($datetime,"Y/m/d");
	$newDate = date('Y/m/d',$date);
	$dayofweek = date('w', $date);
	$weekDay = intval($dayofweek);
	
	if($daysWk == 7 || ($daysWk==5 && $weekDay !=0 && $weekDay !=6) || ($daysWk==6 && $weekDay !=0)){
		$data = array(
                'dayDate'=>$newDate,
				'deptId'=>$dept,
                'shift'=>$shift,
                'accountId'=>$accountId,
				'activeRecord'=>0,
				'type'=>$type,
				'hppdOrig'=>$brand['hppd']
            );
			
            Config::get('db')->insert('productiveNewData',$data);
	}else{
	//do nothing
	}

}

echo "success";	

}

function createDayRank(){	
$dbDays = Config::get('db') -> get_results("select a.*, c.dayRank, d.active from `productiveAccountXref` a LEFT JOIN `productiveAccount` as c on c.id=a.accountId LEFT JOIN `ProductiveDept` as d on d.id = a.dept where d.active>0 AND c.dayRank=1 GROUP BY a.dept");

foreach($dbDays as $rank) {
	$dept = $rank['dept'];
	$datetime = new DateTime('tomorrow');
	$newDate = date_format($datetime,"Y-m-d");
	
	if($dbDays){
		$data = array(
                'dayDate'=>$newDate,
				'deptId'=>$dept
            );
			
    Config::get('db')->insert('productiveDayRank',$data);
	}else{
		//do nothing;
	}

}

echo "success";	

}








?>
