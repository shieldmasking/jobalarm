<?php
ini_set('display_errors', 1);
//ini_set("allow_url_fopen", 1);
session_start();

include_once '../inc/class.db.php';
include_once '../inc/config.php';


//$login_error = 0;


//$accountId = (isset($_REQUEST['m'])) ? intval(substr($_REQUEST['m'],14,3)) : '';
$userId = (isset($_REQUEST['m'])) ? intval(substr($_REQUEST['m'],5,5)) : '';
$logix = (isset($_REQUEST['m'])) ? intval(substr($_REQUEST['m'],0,8)) : 9999;
$gopage = (isset($_REQUEST['p'])) ? $_REQUEST['p'] : '';
$p3 = (isset($_REQUEST['m'])) ? substr($_REQUEST['m'],1,4) : 0;


if ($userId && $gopage) {
	          
        $query = "SELECT u.*, r.label, IFNULL(DATEDIFF(CURDATE(),DATE_ADD(a.payPeriodFirst, INTERVAL (FLOOR(DATEDIFF(CURDATE(),a.payPeriodFirst)/a.payPeriod)*a.payPeriod) DAY)),0) as newStartPay, IFNULL(DATEDIFF(CURDATE(),DATE_ADD(a.payPeriodFirst, INTERVAL (FLOOR(DATEDIFF(CURDATE(),a.payPeriodFirst)/a.payPeriod)*a.payPeriod) DAY))-(a.payPeriod-1),0) as newEndPay, UNIX_TIMESTAMP() as login_time, e.image as enterpriselogo, a.useSafety, a.acctEscalation, a.trainingVids, a.transferPortal, a.enterpriseId, a.dashTerm, a.prodTerm, a.hrsTerm, (SELECT COUNT(`deptId`) FROM `productiveDeptXref` WHERE `userId`=u.id AND `primaryUnit`>0) as countDept, (SELECT COUNT(`id`) FROM `ProductiveDept` WHERE d.active =1 AND `id` IN (SELECT `deptId` from `productiveDeptXref` where `userId`=u.id AND `primaryUnit`>0)) as activeDept, IFNULL(d.prodMeasure,1) as prodMeasure, d.id as deptNum, d.rnVariance, d.indMeasure, a.logs, a.eas, d.useDayRank, a.dashboardSort, a.dayRank, a.productivityPosNeg, IFNULL(a.payPeriod,0) as payPeriod, DAYOFYEAR(curdate()) as currentDay, curdate() as todayDate, DAYOFYEAR(a.payPeriodFirst) as firstDay, COUNT(DISTINCT d.prodMeasure) as prodDistinct, a.payPeriodFirst as startPay, IFNULL(a.image,'') as image, count(IFNULL(d.prodMeasure,1)) as prodCount, a.type, a.status, a.displayProd, a.name as company, a.timeZone, IFNULL(l.login,'') as labelLogin, IFNULL(l.logout,'') as labelLogout, IFNULL(l.id,'') as labelId, IFNULL(l.logo,'') as logo, IFNULL(l.favicon,'') as favicon, IFNULL(l.labelName,'') as labelName, l.labelName as labelTitle, IFNULL(l.supportEmail,'') as supportEmail, IFNULL(l.supportLink,'') as supportLink, IFNULL(l.videoLink,'') as videoLink, d.dept as deptName, d.secLabel, d.unitId, a.active as acctActive FROM `productiveUser` u LEFT OUTER JOIN `productiveDeptXref` as x on x.userId = u.id LEFT JOIN `productiveUserRoles` as r on r.id=u.role LEFT OUTER JOIN `ProductiveDept` as d on d.id = x.deptId LEFT JOIN `productiveAccount` as a on a.id = u.accountId LEFT OUTER JOIN `productiveEnterprise` as e on e.id = a.enterpriseId LEFT JOIN `productiveLabel` as l on l.id = a.label WHERE SUBSTRING(u.logix2,1,8)='{$logix}' AND u.id={$userId} GROUP BY u.id";
        $dbData = Config::get('db')->get_results($query);
        if (count($dbData) > 0){
            $_SESSION['account'] = $dbData[0];
            
			$now = time() - (3 + intval($dbData[0]['timeZone'])) * 60 * 60;
            $data = array('lastlogin' => date("Y-m-d H:i:s", $now));
            $where = array('id' => $dbData[0]['id']);
            Config::get('db')->update('productiveUser', $data, $where, 1);
		
			$logindata = array(
				'userId' =>$dbData[0]['id'],
				'lastlogin' =>date("Y-m-d H:i:s", $now),
				'role' =>$dbData[0]['role'],
				'accountId' =>$dbData[0]['accountId']
				);
			Config::get('db')->insert('productiveLogin', $logindata); 
			
            if ($_SESSION['account']['active']== 0 || $_SESSION['account']['acctActive']== 0) {
				session_destroy();
                header('location: /go/index.php');
            }else if (trim($_SESSION['account']['pwd']) == $_SESSION['account']['temp']) {
                header('location: /pwupdate.php');
            }else if (intval($gopage) >100  && intval($dbData[0]['id'])>0 && intval($dbData[0]['logp3'])==intval($p3)) {
				header('location: /dashboard.php#staffing');
			}else if (intval($gopage) >10 && intval($gopage)<100  && intval($dbData[0]['id'])>0 && intval($dbData[0]['logp3'])==intval($p3)>0) {
				header('location: /dashboard.php#logs');
			}else if (intval($gopage) <11 && intval($gopage)>5	&& intval($dbData[0]['id'])>0 && intval($dbData[0]['logp3'])==intval($p3)) {
				header('location: /dashboard.php#qblNow?m=' . $userId. '&s=' . $gopage . '');
			}else if (intval($gopage) <6 && intval($dbData[0]['id'])>0 && intval($dbData[0]['logp3'])==intval($p3)) {
				header('location: /dashboard.php#ablNow?m=' . $userId. '');
			}else{
				session_destroy();
                header('location: /go/index.php');
            }
            exit();
			
			}else {
			session_destroy();
            header('location: /go/index.php');
			}
    //}

}else{
			session_destroy();
            header('location: /go/index.php');
        }
?>

