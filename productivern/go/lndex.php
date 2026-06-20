<?php
ini_set('display_errors', 1);
//ini_set("allow_url_fopen", 1);
session_start();

include_once '../inc/class.db.php';
include_once '../inc/config.php';


//$login_error = 0;

$dataId = (isset($_REQUEST['m'])) ? $_REQUEST['m'] : '';
$accountId = (isset($_REQUEST['m'])) ? substr($_REQUEST['m'],10) : '';
$unitId = (isset($_REQUEST['s'])) ? $_REQUEST['s'] : '';
$page = (isset($_REQUEST['p'])) ? $_REQUEST['p'] : 0;

if ($dataId && $accountId) {
	          
        $query = "SELECT u.*, r.label, IFNULL(DATEDIFF(CURDATE(),DATE_ADD(a.payPeriodFirst, INTERVAL (FLOOR(DATEDIFF(CURDATE(),a.payPeriodFirst)/a.payPeriod)*a.payPeriod) DAY)),0) as newStartPay, IFNULL(DATEDIFF(CURDATE(),DATE_ADD(a.payPeriodFirst, INTERVAL (FLOOR(DATEDIFF(CURDATE(),a.payPeriodFirst)/a.payPeriod)*a.payPeriod) DAY))-(a.payPeriod-1),0) as newEndPay, UNIX_TIMESTAMP() as login_time, e.image as enterpriselogo, a.useSafety, a.acctEscalation, a.trainingVids, a.transferPortal, a.enterpriseId, a.dashTerm, a.prodTerm, a.hrsTerm, (SELECT COUNT(`deptId`) FROM `productiveDeptXref` WHERE `userId`=u.id AND `primaryUnit`>0) as countDept, (SELECT COUNT(`id`) FROM `ProductiveDept` WHERE d.active =1 AND `id` IN (SELECT `deptId` from `productiveDeptXref` where `userId`=u.id AND `primaryUnit`>0)) as activeDept, IFNULL(d.prodMeasure,1) as prodMeasure, d.id as deptNum, d.rnVariance, d.indMeasure, a.logs, a.eas, d.useDayRank, a.dashboardSort, a.dayRank, a.productivityPosNeg, IFNULL(a.payPeriod,0) as payPeriod, DAYOFYEAR(curdate()) as currentDay, curdate() as todayDate, DAYOFYEAR(a.payPeriodFirst) as firstDay, COUNT(DISTINCT d.prodMeasure) as prodDistinct, a.payPeriodFirst as startPay, IFNULL(a.image,'') as image, count(IFNULL(d.prodMeasure,1)) as prodCount, a.type, a.status, a.displayProd, a.name as company, a.timeZone, IFNULL(l.login,'') as labelLogin, IFNULL(l.logout,'') as labelLogout, IFNULL(l.id,'') as labelId, IFNULL(l.logo,'') as logo, IFNULL(l.favicon,'') as favicon, IFNULL(l.labelName,'') as labelName, l.labelName as labelTitle, IFNULL(l.supportEmail,'') as supportEmail, IFNULL(l.supportLink,'') as supportLink, IFNULL(l.videoLink,'') as videoLink, d.dept as deptName, d.secLabel, d.unitId, a.active as acctActive FROM `productiveUser` u LEFT OUTER JOIN `productiveDeptXref` as x on x.userId = u.id LEFT JOIN `productiveUserRoles` as r on r.id=u.role LEFT OUTER JOIN `ProductiveDept` as d on d.id = x.deptId LEFT JOIN `productiveAccount` as a on a.id = u.accountId LEFT OUTER JOIN `productiveEnterprise` as e on e.id = a.enterpriseId LEFT JOIN `productiveLabel` as l on l.id = a.label WHERE u.accountId={$accountId} AND u.logix='{$dataId}' GROUP BY u.id";
        $dbData = Config::get('db')->get_results($query);
        if (count($dbData)==1){
            $_SESSION['account'] = $dbData[0];
            
			//$now = time() - (3 + intval($dbData[0]['timeZone'])) * 60 * 60;
			$now = time();
            $data = array('lastlogin' => date("Y-m-d H:i:s", $now));
            $where = array('id' => $_SESSION['account']['id']);
            Config::get('db')->update('productiveUser', $data, $where, 1);
			$role = $_SESSION['account']['role'];
			$userId = $_SESSION['account']['id'];
			$accountId = $_SESSION['account']['accountId'];			
			
			$logindata = array(
				'userId' =>$userId,
				'lastlogin' =>date("Y-m-d H:i:s", $now),
				'role' =>$role,
				'accountId' =>$accountId
				);
			Config::get('db')->insert('productiveLogin', $logindata); 
			
            if ($_SESSION['account']['active']== 0 || $_SESSION['account']['acctActive']== 0) {
				session_destroy();
                header('location: /login.php');
            }else if (trim($_SESSION['account']['pwd']) == $_SESSION['account']['temp']) {
                header('location: /pwupdate.php');
            }else if (intval($page)==99) {
				header('location: /dashboard.php#qblLog');
			}else if (intval($page)==98) {
				header('location: /dashboard.php#logs');
			}else{
                header('location: /dashboard.php#reports?i=' . $unitId . '');
            }
			
            exit();
        }else {
			session_destroy();
            header('location: /login.php');
        }
    //}

}
?>

