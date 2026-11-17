<?php
ini_set('display_errors', 0);
session_set_cookie_params(['lifetime' => 0, 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict']);
session_start();

include_once '../inc/class.db.php';
include_once '../inc/config.php';

$token = isset($_REQUEST['t']) ? preg_replace('/[^a-f0-9]/i', '', $_REQUEST['t']) : '';

if (strlen($token) !== 64) {
    header('location: /go/index.php'); exit();
}

$tokenRow = Config::get('db')->get_results("SELECT * FROM `productiveQrTokens` WHERE `token`='{$token}' LIMIT 1");

if (!$tokenRow) {
    header('location: /go/index.php'); exit();
}

$userId = intval($tokenRow[0]['userId']);
$page   = $tokenRow[0]['page'];

$query = "SELECT u.*, r.label, IFNULL(DATEDIFF(CURDATE(),DATE_ADD(a.payPeriodFirst, INTERVAL (FLOOR(DATEDIFF(CURDATE(),a.payPeriodFirst)/a.payPeriod)*a.payPeriod) DAY)),0) as newStartPay, IFNULL(DATEDIFF(CURDATE(),DATE_ADD(a.payPeriodFirst, INTERVAL (FLOOR(DATEDIFF(CURDATE(),a.payPeriodFirst)/a.payPeriod)*a.payPeriod) DAY))-(a.payPeriod-1),0) as newEndPay, UNIX_TIMESTAMP() as login_time, e.image as enterpriselogo, a.useSafety, a.acctEscalation, a.trainingVids, a.transferPortal, a.enterpriseId, a.dashTerm, a.prodTerm, a.hrsTerm, (SELECT COUNT(`deptId`) FROM `productiveDeptXref` WHERE `userId`=u.id AND `primaryUnit`>0) as countDept, (SELECT COUNT(`id`) FROM `ProductiveDept` WHERE d.active =1 AND `id` IN (SELECT `deptId` from `productiveDeptXref` where `userId`=u.id AND `primaryUnit`>0)) as activeDept, IFNULL(d.prodMeasure,1) as prodMeasure, d.id as deptNum, d.rnVariance, d.indMeasure, a.logs, a.eas, d.useDayRank, a.dashboardSort, a.dayRank, a.productivityPosNeg, IFNULL(a.payPeriod,0) as payPeriod, DAYOFYEAR(curdate()) as currentDay, curdate() as todayDate, DAYOFYEAR(a.payPeriodFirst) as firstDay, COUNT(DISTINCT d.prodMeasure) as prodDistinct, a.payPeriodFirst as startPay, IFNULL(a.image,'') as image, count(IFNULL(d.prodMeasure,1)) as prodCount, a.type, a.status, a.displayProd, a.name as company, a.timeZone, IFNULL(l.login,'') as labelLogin, IFNULL(l.logout,'') as labelLogout, IFNULL(l.id,'') as labelId, IFNULL(l.logo,'') as logo, IFNULL(l.favicon,'') as favicon, IFNULL(l.labelName,'') as labelName, l.labelName as labelTitle, IFNULL(l.supportEmail,'') as supportEmail, IFNULL(l.supportLink,'') as supportLink, IFNULL(l.videoLink,'') as videoLink, d.dept as deptName, d.secLabel, d.unitId, a.active as acctActive FROM `productiveUser` u LEFT OUTER JOIN `productiveDeptXref` as x on x.userId = u.id LEFT JOIN `productiveUserRoles` as r on r.id=u.role LEFT OUTER JOIN `ProductiveDept` as d on d.id = x.deptId LEFT JOIN `productiveAccount` as a on a.id = u.accountId LEFT OUTER JOIN `productiveEnterprise` as e on e.id = a.enterpriseId LEFT JOIN `productiveLabel` as l on l.id = a.label WHERE u.id={$userId} GROUP BY u.id";

$dbData = Config::get('db')->get_results($query);

if (!$dbData) {
    header('location: /go/index.php'); exit();
}

$_SESSION['account'] = $dbData[0];
$_SESSION['account']['temp'] = ''; // prevent forced-pwchange redirect for QR logins

$now = time() - (3 + intval($dbData[0]['timeZone'])) * 60 * 60;
Config::get('db')->update('productiveUser', array('lastlogin' => date("Y-m-d H:i:s", $now)), array('id' => $dbData[0]['id']), 1);
Config::get('db')->insert('productiveLogin', array(
    'userId'    => $dbData[0]['id'],
    'lastlogin' => date("Y-m-d H:i:s", $now),
    'role'      => $dbData[0]['role'],
    'accountId' => $dbData[0]['accountId'],
));

if ($_SESSION['account']['active'] == 0 || $_SESSION['account']['acctActive'] == 0) {
    session_destroy();
    header('location: /go/index.php'); exit();
}

$destinations = array(
    'staffing' => '/dashboard.php#staffing',
    'qbl'      => '/dashboard.php#qblNow',
    'tasks'    => '/dashboard.php#logs',
);

$dest = isset($destinations[$page]) ? $destinations[$page] : '/dashboard.php';
echo '<!DOCTYPE html><html><head></head><body><script>window.location.replace(' . json_encode($dest) . ');</script></body></html>';
exit();
