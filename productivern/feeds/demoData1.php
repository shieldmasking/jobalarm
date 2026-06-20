<?php
error_reporting(E_ALL); 
ini_set('display_errors', 1);
include '../inc/class.db.php';
include '../inc/config.php';

delData();
newData();

function delData(){
$dbDelete = Config::get('db')->get_results("SELECT * from `productiveNewData` WHERE `accountId`=22 and `deptId`!=431 and `dayDate`>=DATE_SUB(CURDATE(), INTERVAL 1 DAY)");

	foreach($dbDelete as $del){
		$delete = array(
					'id' => $del['id']
					);
		Config::get('db')->delete('productiveNewData',$delete);
	}
return true;	
}

function newData(){	

$dbProd = Config::get('db')->get_results("SELECT * from `productiveNewData` WHERE `deptId` IN ('481','491','518','493','495') and `dayDate`>=DATE_SUB(CURDATE(), INTERVAL 1 DAY) group by `id`");
    
foreach($dbProd as $prod){
		
		if(intval($prod['deptId'])==495 && intval($prod['userId'])>0){
			$userId = 1726;
			$deptId = 517;
			$shift = intval($prod['shift']);
			$submittedby = "Elaine Benes";
		}else if(intval($prod['deptId'])==495 && intval($prod['userId'])==0){
			$userId = 0;
			$deptId = 517;
			$shift = intval($prod['shift']);
			$submittedby = '';
		}else if(intval($prod['deptId'])==493 && intval($prod['userId'])>0){
			$userId = 1725;
			$deptId = 515;
			$shift = intval($prod['shift']);
			$submittedby = "Joe Davola";
		}else if(intval($prod['deptId'])==493 && intval($prod['userId'])==0){
			$userId = 0;
			$deptId = 515;
			$shift = intval($prod['shift']);
			$submittedby = '';
		}else if(intval($prod['deptId'])==518 && intval($prod['userId'])>0){
			$userId = 1724;
			$deptId = 516;
			$shift = intval($prod['shift']);
			$submittedby = "Babs Kramer";
		}else if(intval($prod['deptId'])==518 && intval($prod['userId'])==0){
			$userId = 0;
			$deptId = 516;
			$shift = intval($prod['shift']);
			$submittedby = '';
		}else if(intval($prod['deptId'])==491 && intval($prod['userId'])>0){
			$userId = 1727;
			$deptId = 514;
			$shift = intval($prod['shift']);
			$submittedby = "Marla Penny";
		}else if(intval($prod['deptId'])==491 && intval($prod['userId'])==0){
			$userId = 0;
			$deptId = 514;
			$shift = intval($prod['shift']);
			$submittedby = '';
		}else if(intval($prod['deptId'])==481 && intval($prod['userId'])>0){
			$userId = 1728;
			$deptId = 431;
			$shift = intval($prod['shift']);
			$submittedby = "Sue Ellen Mischke";
		}else if(intval($prod['deptId'])==481 && intval($prod['userId'])==0){
			$userId = 0;
			$deptId = 431;
			$shift = intval($prod['shift']);
			$submittedby = '';
		}else{
			return true;	
		}
		
		$data = array(
				'accountId' =>22,
				'userId' =>$userId,
				'deptId' =>$deptId,
				'entered' =>$prod['entered'],
				'dayDate' =>$prod['dayDate'],
				'shift' =>$shift,
				'chargecount' =>$prod['chargecount'],
                'techcount' =>$prod['techcount'],
				'aptechcount' =>$prod['aptechcount'],
				'seccount' =>$prod['seccount'],
				'sittercount' =>$prod['sittercount'],
				'antecount' =>$prod['antecount'],
				'obed' =>$prod['obed'],
				'obed1' =>$prod['obed1'],
				'ldcount' =>$prod['ldcount'],
				'ocount' =>$prod['ocount'],
				'avariance' =>$prod['avariance'],
				'lvariance' =>$prod['lvariance'],
				'nvariance' =>$prod['nvariance'],
				'nvariance1' =>$prod['nvariance1'],
				'nvariance2' =>$prod['nvariance2'],
				'nvariance3' =>$prod['nvariance3'],
				'hrsVariance' =>$prod['hrsVariance'],
				'hrsVarianceActual' =>$prod['hrsVarianceActual'],
				'atotal' =>$prod['atotal'],
				'patientCount2' =>$prod['patientCount2'],
				'patientCount3' =>$prod['patientCount3'],
				'patientCount4' =>$prod['patientCount4'],
				'ltotal' =>$prod['ltotal'],
				'aproductivity' =>$prod['aproductivity'],
				'note' =>addslashes($prod['note']),
				'lproductivity' =>$prod['lproductivity'],
				'nproductivity' =>$prod['nproductivity'],
				'procedureCount' =>$prod['procedureCount'],
				'actualWHP' =>$prod['actualWHP'],
				'oneto1' =>$prod['oneto1'],
				'oneto2' =>$prod['oneto2'],
				'oneto3' =>$prod['oneto3'],
				'oneto4' =>$prod['oneto4'],
				'oneto5' =>$prod['oneto5'],
				'oneto6' =>$prod['oneto6'],
				'oneto7' =>$prod['oneto7'],
				'oneto8' =>$prod['oneto8'],
				'oneto9' =>$prod['oneto9'],
				'oneto10'=>$prod['oneto10'],
				'oneto11' =>$prod['oneto11'],
				'oneto12' =>$prod['oneto12'],
				'twoto1' =>$prod['twoto1'],
				'skill1val' =>$prod['skill1val'],
				'skill2val' =>$prod['skill2val'],
				'skill3val' =>$prod['skill3val'],
				'skill4val' =>$prod['skill4val'],
				'skill5val' =>$prod['skill5val'],
				'skill6val' =>$prod['skill6val'],
				'skill7val' =>$prod['skill7val'],
				'skill8val' =>$prod['skill8val'],
				'skill9val' =>$prod['skill9val'],
				'skill10val' =>$prod['skill10val'],
				'skill11val' =>$prod['skill11val'],
				'addResourceHrs' =>$prod['addResourceHrs'],
				'plannedHours' =>$prod['plannedHours'],
				'plannedProcs' =>$prod['plannedProcs'],
				'budgetValue' =>$prod['budgetValue'],
				'admits' =>$prod['admits'],
				'transfers' =>$prod['transfers'],
				'discharges' =>$prod['discharges'],
				'churnValue' =>$prod['churnValue'],
				'customNurse' =>$prod['customNurse'],
				'customNurse2' =>$prod['customNurse2'],
				'otherNurse1' =>$prod['otherNurse1'],
				'otherNurse2' =>$prod['otherNurse2'],
				'otherNurse3' =>$prod['otherNurse3'],
				'activeRecord' =>$prod['activeRecord'],
				'budgetVar' =>$prod['budgetVar'],
				'track1' =>$prod['track1'],
				'track2' =>$prod['track2'],
				'track3' =>$prod['track3'],
				'track4' =>$prod['track4'],
				'gvariance' =>$prod['gvariance'],
				'notetrack1' =>addslashes($prod['notetrack1']),
				'notetrack2' =>addslashes($prod['notetrack2']),
				'notetrack3' =>addslashes($prod['notetrack3']),
				'notetrack4' =>addslashes($prod['notetrack4']),
				'submittedby' =>$submittedby,
				'countDischarges' =>$prod['countDischarges'],
				'customProd' =>$prod['customProd'],
				'gridrnvariance' =>$prod['gridrnvariance'],
				'gridrnproductivity' =>$prod['gridrnproductivity'],
				'gbudgetVar' =>$prod['gbudgetVar'],
				'rbudgetVar' =>$prod['rbudgetVar'],
				'gridrnbudgetVar' =>$prod['gridrnbudgetVar'],
				'ghrsVariance' =>$prod['ghrsVariance'],
				'ahrsVariance' =>$prod['ahrsVariance'],
				'rhrsVariance' =>$prod['rhrsVariance'],
				'gridrnhrsVariance' =>$prod['gridrnhrsVariance'],
				'submitCount' =>$prod['submitCount'],
				'epicScore' =>0,
				'rnVariance1' =>$prod['rnVariance1'],
				'hppd2' =>$prod['hppd2'],
				'rnAcuity' =>$prod['rnAcuity'],
				'oneto1Count' =>$prod['oneto1Count'],
				'oneto2Count' =>$prod['oneto2Count'],
				'oneto3Count' =>$prod['oneto3Count'],
				'oneto3p5Count' =>$prod['oneto3p5Count'],
				'oneto4Count' =>$prod['oneto4Count'],
				'oneto4p5Count' =>$prod['oneto4p5Count'],
				'oneto5Count' =>$prod['oneto5Count'],
				'oneto5p5Count' =>$prod['oneto5p5Count'],
				'oneto6Count' =>$prod['oneto6Count'],
				'twoto1Count' =>$prod['twoto1Count'],
				'threeto1Count' =>$prod['threeto1Count'],
				'epicOrig' =>0,
				'track1Detail' =>$prod['track1Detail'],
				'track2Detail' =>$prod['track2Detail'],
				'track3Detail' =>$prod['track3Detail'],
				'track4Detail' =>$prod['track4Detail'],
				'censusTotal' =>$prod['censusTotal'],
				'planSubmitted' =>$prod['planSubmitted'],
				'visitsSubmitted' =>$prod['visitsSubmitted'],
				'hoursSubmitted' =>$prod['hoursSubmitted'],
				'hrs1Submitted' =>$prod['hrs1Submitted'],
				'hrs2Submitted' =>$prod['hrs2Submitted'],
				'hrs3Submitted' =>$prod['hrs3Submitted'],
				'whpCustom' =>$prod['whpCustom'],
				'whpCustom2' =>$prod['whpCustom2'],
				'whpCustom3' =>$prod['whpCustom3'],
				'whpCustom4' =>$prod['whpCustom4'],
				'whpCustom5' =>$prod['whpCustom5'],
				'IndUOS' =>$prod['IndUOS'],
				'IndHRS' =>$prod['IndHRS'],
				'indUserId' =>$prod['indUserId'],
				'provId' =>$prod['provId'],
				'trackDischarges' =>$prod['trackDischarges'],
				'visits' =>$prod['visits'],
				'totalHours' =>$prod['totalHours'],
				'grid1' =>$prod['grid1'],
				'grid2' =>$prod['grid2'],
				'grid3' =>$prod['grid3'],
				'grid4' =>$prod['grid4'],
				'grid5' =>$prod['grid5'],
				'grid6' =>$prod['grid6'],
				'grid7' =>$prod['grid7'],
				'grid8' =>$prod['grid8'],
				'grid9' =>$prod['grid9'],
				'grid10' =>$prod['grid10'],
				'gridvar1' =>$prod['gridvar1'],
				'gridvar2' =>$prod['gridvar2'],
				'gridvar3' =>$prod['gridvar3'],
				'gridvar4' =>$prod['gridvar4'],
				'gridvar5' =>$prod['gridvar5'],
				'gridvar6' =>$prod['gridvar6'],
				'gridvar7' =>$prod['gridvar7'],
				'gridvar8' =>$prod['gridvar8'],
				'gridvar9' =>$prod['gridvar9'],
				'gridvar10' =>$prod['gridvar10']
				);
		
		Config::get('db')->insert('productiveNewData',$data);
	
}

//echo "success";	
return true;

}










?>
