<?php
//session_start();
ini_set('display_errors',1);
include_once 'inc/class.db.php';
//include_once 'inc/class.jatwitter.php';
include_once 'inc/config.php';
//include_once 'inc/pagination.class.php';

//require_once 'vendor/autoload.php';

/////Test Data
$zipcode = (isset($_REQUEST['z'])) ? $_REQUEST['z'] : '';
$brandOrig = (isset($_REQUEST['b'])) ? $_REQUEST['b'] : 6;
$acctAdd = (isset($_REQUEST['a'])) ? $_REQUEST['a'] : '';
$candidateId = (isset($_REQUEST['m'])) ? $_REQUEST['m'] : '';
$mobile = (isset($_REQUEST['m'])) ? $_REQUEST['m'] : '';
$searchKey = (isset($_REQUEST['s'])) ? $_REQUEST['s'] : '';
$updatemsg = (isset($_REQUEST['x'])) ? $_REQUEST['x'] : 0;
$priority = (isset($_REQUEST['p'])) ? $_REQUEST['p'] : 0;
$lati = (isset($_REQUEST['lat'])) ? $_REQUEST['lat'] : '';
$long = (isset($_REQUEST['lon'])) ? $_REQUEST['lon'] : '';
$status = (isset($_REQUEST['t'])) ? $_REQUEST['t'] : 0;
$group = (isset($_REQUEST['g'])) ? $_REQUEST['g'] : '';
$accType = 0;
$industry = 0;
$brandDeal = '';
$msg = "Your Jobs Are Listed Below!";

ini_set('memory_limit', '-1');


if ($acctAdd){
$dbMobile = Config::get('db')->get_results("SELECT s.id as brandOrig, s.storeBrand, s.twitterDemo, s.color, s.keyword, s.positions, s.type, s.storeImage, s.deal, a.logo, a.billing_plan, a.brandId as industry, a.logopic, a.email, a.miles, a.status as accType, ad.adImg, ad.active as adActive, ad.adLink, ad.id as adLinkId from `sms_brand` s left outer join `account` as a on a.id ={$acctAdd} left outer join `ads` as ad on ad.brandId = s.id where s.id ={$brandOrig} group by s.id");
$accType = $dbMobile[0]['accType'];
$industry = $dbMobile[0]['industry'];
$distance = intval($dbMobile[0]['miles']);
$plan = intval($dbMobile[0]['billing_plan']);
}else{
$dbMobile = Config::get('db')->get_results("SELECT s.id as brandOrig, s.storeBrand, s.twitterDemo, s.color, s.keyword, s.positions, s.type, s.storeImage, s.deal, a.logo, a.brandId as industry, a.logopic, a.miles, a.status as accType, ad.adImg, ad.active as adActive, ad.adLink, ad.id as adLinkId from `sms_brand` s left outer join `account` as a on a.id = s.accountId left outer join `ads` as ad on ad.brandId = s.id where s.id ={$brandOrig} group by s.id");
//$dbMobile = Config::get('db')->get_results("SELECT c.*, x.id as xid, s.id as brandOrig, s.storeBrand, s.twitterDemo, s.color, s.keyword, s.positions, s.type, s.storeImage, s.deal, ad.adImg, ad.adLink, ad.id as adLinkId from `candidate` c left outer join `candidateXref` as x on x.candidateId = c.id and x.brandOrig ={$brandOrig} left join `sms_brand` as s on s.id ={$brandOrig} left outer join `ads` as ad on ad.brandId = s.id where c.id ={$candidateId} or c.mobile ={$candidateId} group by c.id");
$distance = 25;
$industry = 0;
$accType = 0;
$plan = 1;
}

if(intval($status)>0){
	$accType = $status;
}


$dbResult = $dbMobile[0];
//$_SESSION['candidate'] =$dbResult;

$referral =1;
$brandImage = $dbResult['storeImage'];
//$mobile = $dbResult['mobile'];
$brandName = $dbResult['storeBrand'];
$brandDeal = $dbResult['deal'];
$brandType = $dbResult['type'];
$adLinkId = $dbResult['adLinkId'];
/*
if(intval($adLinkId) > 0){
$adImg = "../img/".$dbResult['adImg'];
$adLink = $dbResult['adLink'];
$active = $dbResult['adActive'];
//$adClick = "tj.adTrack(".$mobile.",".$adLinkId.");window.open(this.href,'targetWindow','toolbar=no, location=no, status=no, menubar=no, scrollbars=yes,resizable=yes, width=920, height=600');return false;";
$adDetails = "<a href=\"" . $adLink . "\"><img src=\"" . $adImg . "\" target=\"_blank\" onclick=\"tj.adTrack(" . $mobile . "," . $adLinkId . ");\"></a>";
}else{
$adDetails = '';
}
*/
$searchAdd = '';
$brandAdd = '';

if($distance > 0){
$zipDist = $distance;
}else{
$zipDist = 20;	
}

if ($plan==0){
$groupBy = "GROUP BY j.brand, j.zipCode, j.title";
$groupBy2 = "GROUP BY j.brand";
$searchAdd2 =" AND j.postId !={$acctAdd} AND j.jobCats !={$industry}";
$brandAdd = " AND j.postId=".$acctAdd." AND j.brand=".$brandOrig;
$zipAdd ='<=' . $zipDist . '';
$newLimit = "LIMIT 0,30";
}else if ($plan==1){
$groupBy = "GROUP BY j.brand, j.city, j.address, j.title";
$groupBy2 = "GROUP BY j.brand, j.id";
//$searchAdd2 =" AND j.postId !={$acctAdd} AND j.jobCats !={$industry}";
$searchAdd2 =" AND j.postId !={$acctAdd} AND j.jobCats !={$industry}";	
$brandAdd = " AND j.postId={$acctAdd}";
$zipAdd ='<=' . $zipDist . '';
$newLimit = "LIMIT 0,8";
}else if ($plan==2){
$groupBy = "GROUP BY j.brand, j.zipCode, j.address, j.title";
$groupBy2 = "GROUP BY j.brand, j.id";
$searchAdd2 =" AND j.postId ={$acctAdd}";	
$brandAdd = " AND j.postId={$acctAdd}";
$zipAdd ='>' . $zipDist . '';
$newLimit = "LIMIT 0,0";
}else{
$groupBy = "GROUP BY j.brand, j.priority, j.zipCode, j.address, j.title";
$groupBy2 = "GROUP BY j.id";	
$searchAdd2 = " AND j.postId ={$acctAdd} AND j.jobCats !={$industry}";
$brandAdd = " AND j.postId={$acctAdd}";
$zipAdd ='<=' . $zipDist . '';
$newLimit = "LIMIT 0,15";
}
/*
if (intval($brandOrig)==276){
$brandAdd = " AND j.brand={$brandOrig}";
}

if(intval($acctAdd)==271){
$searchAdd2 =" AND j.priority != 1";
}
*/

$zipsearch = '';



if($zipcode){
    if(is_numeric($zipcode)){
        $dbCoord = Config::get('db')->get_results("select latitude, longitude, city, state_code from cities_extended where zip={$zipcode}");
    } else {
        $parts = explode(' ', strtoupper(trim($zipcode)));
        $state = array_pop($parts);
        $city = implode(' ', $parts);
        $dbCoord = Config::get('db')->get_results("select latitude, longitude, city, state_code from cities_extended where UPPER(city)='".addslashes($city)."' AND state_code='".addslashes($state)."' LIMIT 1");
    }
    if (!$dbCoord){
        echo "Invalid Zip Code";
        $zipcode = 99950;
        $dbCoord = Config::get('db')->get_results("select latitude, longitude, city, state_code from cities_extended where zip={$zipcode}");
    }
}

if($lati && $long){	
$lat=$lati;
$lon=$long;
$hidden = '';
}else{
$lat=$dbCoord[0]["latitude"];
$lon=$dbCoord[0]["longitude"];	
$hidden = '';
}

if($searchKey && intval($priority)!=1){
		$zipDist = $distance;
		$groupBy = '';
		$groupBy2 = "GROUP BY j.brand, j.id ";
		if(strpos(strtoupper($searchKey), 'MANAGER') !== false) {
			$searchAdd = " and (UPPER(j.text) LIKE \"%MANAGER%\") ";
		}else if(strpos(strtoupper($searchKey), 'COOK') !== false) {
			$searchAdd = " and (UPPER(j.text) LIKE \"%COOK%\" OR UPPER(j.text) LIKE \"%CHEF%\" OR UPPER(j.text) LIKE \"%KITCHEN%\" OR UPPER(j.text) LIKE \"%DELI%\") ";
		}else if(strpos(strtoupper($searchKey), 'KITCHEN') !== false) {
			$searchAdd = " and (UPPER(j.text) LIKE \"%KITCHEN%\" OR UPPER(j.text) LIKE \"%COOK%\" OR UPPER(j.text) LIKE \"%CHEF%\" OR UPPER(j.text) LIKE \"%DELI%\") ";
		}else if(strpos(strtoupper($searchKey), 'WAREHOUSE') !== false) {
			$searchAdd = " and (UPPER(j.text) LIKE \"%WAREHOUSE%\" OR UPPER(j.text) LIKE \"%MATERIAL HANDLER%\") ";
		}else if(strpos(strtoupper(str_replace("_"," ",$searchKey)), 'MATERIAL HANDLER') !== false) {
			$searchAdd = " and (UPPER(j.text) LIKE \"%WAREHOUSE%\" OR UPPER(j.text) LIKE \"%MATERIAL HANDLER%\") ";
		}else if(strpos(strtoupper($searchKey), 'COORDINATOR') !== false) {
			$searchAdd = " and (UPPER(j.text) LIKE \"%COORDINATOR%\") ";
		}else if(strpos(strtoupper($searchKey), 'ADMIN') !== false) {
			$searchAdd = " and (UPPER(j.text) LIKE \"%ADMIN%\") ";
		}else if(strpos(strtoupper(str_replace("_"," ",$searchKey)), 'CALL CENTER') !== false) {
			$searchAdd = " and (UPPER(j.text) LIKE \"%CONTACT CENTER%\" OR UPPER(j.text) LIKE \"%CALL CENTER%\") ";
		}else if(strpos(strtoupper(str_replace("_"," ",$searchKey)), 'CONTACT CENTER') !== false) {
			$searchAdd = " and (UPPER(j.text) LIKE \"%CALL CENTER%\" OR UPPER(j.text) LIKE \"%CONTACT CENTER%\") ";
		}else if(strpos(strtoupper($searchKey), 'CHEF') !== false) {
			$searchAdd = " and (UPPER(j.text) LIKE \"%CHEF%\") ";
		}else if(strpos(strtoupper($searchKey), 'DRIVER') !== false) {
			$searchAdd = " and (UPPER(j.text) LIKE \"%DRIVER%\") ";
		}else if(strpos(strtoupper($searchKey), 'FOOD') !== false) {
			$searchAdd = " and (UPPER(j.text) LIKE \"%FOOD%\") ";
		}else if(strpos(strtoupper(str_replace("_"," ",$searchKey)), 'CAST MEMBER') !== false) {
			$searchAdd = " and (UPPER(j.text) LIKE \"%CREW MEMBER%\" OR UPPER(j.text) LIKE \"%TEAM MEMBER%\" OR UPPER(j.text) LIKE \"%ASSOCIATE%\" OR UPPER(j.text) LIKE \"%CASHIER%\") ";
		}else if(strpos(strtoupper(str_replace("_"," ",$searchKey)), 'CREW MEMBER') !== false) {
			$searchAdd = " and (UPPER(j.text) LIKE \"%CREW MEMBER%\" OR UPPER(j.text) LIKE \"%TEAM MEMBER%\" OR UPPER(j.text) LIKE \"%ASSOCIATE%\" OR UPPER(j.text) LIKE \"%CASHIER%\") ";
		}else if(strpos(strtoupper(str_replace("_"," ",$searchKey)), 'STORE EMPLOYEE') !== false) {
			$searchAdd = " and (UPPER(j.text) LIKE \"%CREW MEMBER%\" OR UPPER(j.text) LIKE \"%TEAM MEMBER%\" OR UPPER(j.text) LIKE \"%ASSOCIATE%\" OR UPPER(j.text) LIKE \"%CASHIER%\") ";
		}else if(strpos(strtoupper(str_replace("_"," ",$searchKey)), 'TEAM MEMBER') !== false) {
			$searchAdd = " and (UPPER(j.text) LIKE \"%CREW MEMBER%\" OR UPPER(j.text) LIKE \"%TEAM MEMBER%\" OR UPPER(j.text) LIKE \"%ASSOCIATE%\" OR UPPER(j.text) LIKE \"%CASHIER%\") ";
		}else if(strpos(strtoupper($searchKey), 'CASHIER') !== false) {
			$searchAdd = " and (UPPER(j.text) LIKE \"%CREW MEMBER%\" OR UPPER(j.text) LIKE \"%TEAM MEMBER%\" OR UPPER(j.text) LIKE \"%ASSOCIATE%\" OR UPPER(j.text) LIKE \"%CASHIER%\") ";
		}else if(strpos(strtoupper($searchKey), 'ASSOCIATE') !== false) {
			$searchAdd = " and (UPPER(j.text) LIKE \"%CREW MEMBER%\" OR UPPER(j.text) LIKE \"%TEAM MEMBER%\" OR UPPER(j.text) LIKE \"%ASSOCIATE%\" OR UPPER(j.text) LIKE \"%CASHIER%\") ";
		}else{
			$searchAdd = " and (UPPER(j.text) LIKE \"%" . strtoupper(str_replace(" ","_",$searchKey)) . "%\") ";
		}
}else if($searchKey && intval($priority)==1){
	$searchAdd = " and (j.priority=1) ";
	//$searchAdd = '';
}else{
	$searchAdd = '';
}
$totalFeaturedRecords=0;

if ($plan==0){
$dbData = Config::get('db')->get_results("SELECT SQL_CALC_FOUND_ROWS j.*, j.id as jobId, a.status as acctStatus, s.userPic as userPic, s.storeBrand, (3963*ACOS(SIN({$lat}/57.2958)*SIN(ce.latitude/57.2958)+ COS({$lat}/57.2958) *COS(ce.latitude/57.2958)*COS((ce.longitude/57.2958)-({$lon}/57.2958)))) as dist 
	FROM job j
		LEFT JOIN sms_brand as s on s.id = j.brand
		LEFT OUTER JOIN account as a on a.id = j.postId
	    inner join cities_extended ce on ce.zip = j.zipCode
	where 
        (3963*ACOS(SIN(({$lat}+.000001)/57.2958)*SIN(ce.latitude/57.2958)+ COS(({$lat}+.000001)/57.2958) *COS(ce.latitude/57.2958)*COS((ce.longitude/57.2958)-(({$lon}+.000001)/57.2958))))$zipAdd and j.status>0 and j.postId>0$brandAdd$searchAdd
	$groupBy2 
	order by dist asc, j.brand asc
	$newLimit");
	$countData=count($dbData);
}else{
$dbData = Config::get('db')->get_results("SELECT SQL_CALC_FOUND_ROWS j.*, j.id as jobId, a.status as acctStatus, a.brandId as industry, s.userPic as userPic, s.storeBrand, (3963*ACOS(SIN({$lat}/57.2958)*SIN(ce.latitude/57.2958)+ COS({$lat}/57.2958) *COS(ce.latitude/57.2958)*COS((ce.longitude/57.2958)-({$lon}/57.2958)))) as dist 
	FROM job j
		LEFT JOIN sms_brand as s on s.id = j.brand
		LEFT OUTER JOIN account as a on a.id = j.postId
	    inner join cities_extended ce on ce.zip = j.zipCode
	where 
        (3963*ACOS(SIN(({$lat}+.000001)/57.2958)*SIN(ce.latitude/57.2958)+ COS(({$lat}+.000001)/57.2958) *COS(ce.latitude/57.2958)*COS((ce.longitude/57.2958)-(({$lon}+.000001)/57.2958))))$zipAdd and j.status>0$searchAdd2
		$groupBy2 
		order by dist ASC, j.brand ASC, j.priority DESC
		$newLimit");
	
	$countData=count($dbData);
	
	if(count($dbData)>0 && intval($accType)<=6 && intval($acctAdd)!=126){
		$limit = "LIMIT 0,20";
	}else if(count($dbData)>0 && intval($accType)>=7){
		$limit = "LIMIT 0,5";
	}else if(intval($acctAdd)==126){
		$limit = "LIMIT 0,100";
	}else{
		$limit = "LIMIT 0,5";
	}

$featuredData = Config::get('db')->get_results("SELECT SQL_CALC_FOUND_ROWS j.*, j.id as jobId, j.postId as featured, a.status as acctStatus, s.userPic as userPic, s.storeBrand, (3963*ACOS(SIN({$lat}/57.2958)*SIN(ce.latitude/57.2958)+ COS({$lat}/57.2958) *COS(ce.latitude/57.2958)*COS((ce.longitude/57.2958)-({$lon}/57.2958)))) as dist 
	FROM job j
		LEFT JOIN sms_brand as s on s.id = j.brand
		LEFT OUTER JOIN account as a on a.id = j.postId
	    inner join cities_extended ce on ce.zip = j.zipCode
	where 
        (3963*ACOS(SIN(({$lat}+.000001)/57.2958)*SIN(ce.latitude/57.2958)+ COS(({$lat}+.000001)/57.2958) *COS(ce.latitude/57.2958)*COS((ce.longitude/57.2958)-(({$lon}+.000001)/57.2958))))<=$zipDist and j.status>0$brandAdd$searchAdd
	$groupBy2
	order by dist ASC, j.brand DESC
	$limit
	");
	/*
	if (!$dbData) {
		$countData=0;
	$dbData = Config::get('db')->get_results("SELECT SQL_CALC_FOUND_ROWS j.*, j.id as jobId, j.postId as featured, a.status as acctStatus, s.userPic as userPic, s.storeBrand, (3963*ACOS(SIN({$lat}/57.2958)*SIN(ce.latitude/57.2958)+ COS({$lat}/57.2958) *COS(ce.latitude/57.2958)*COS((ce.longitude/57.2958)-({$lon}/57.2958)))) as dist 
	FROM job j
		LEFT JOIN sms_brand as s on s.id = j.brand
		LEFT OUTER JOIN account as a on a.id = j.postId
	    inner join cities_extended ce on ce.zip = j.zipCode
	where 
        (3963*ACOS(SIN(({$lat}+.000001)/57.2958)*SIN(ce.latitude/57.2958)+ COS(({$lat}+.000001)/57.2958) *COS(ce.latitude/57.2958)*COS((ce.longitude/57.2958)-(({$lon}+.000001)/57.2958))))<=$zipDist and j.status>0$brandAdd$searchAdd
	$groupBy
	order by dist asc, j.title desc
	Limit 6,20");
	}
	*/
}	
	
	if(!$featuredData){
	$totalFeaturedRecords=0;
	//$featuredData = Config::get('db')->get_results("SELECT SQL_CALC_FOUND_ROWS j.*, j.id as jobId, j.postId as featured, a.status as acctStatus, s.userPic as userPic, s.storeBrand 
	//FROM job j
	//	LEFT JOIN sms_brand as s on s.id = j.brand
	//	LEFT OUTER JOIN account as a on a.id = j.postId
	//where j.status=2$brandAdd 
	//$groupBy
	//order by j.title desc");	
	}else{
	$totalFeaturedRecords=count($featuredData);	
	}

//echo "query".$query;
/*
if (!$dbData && !$featuredData) {
	if(intval($brandOrig) ==42 || intval($brandOrig) ==36 || intval($brandOrig) ==29 || intval($brandOrig) ==38 || intval($brandOrig) ==30 || intval($brandOrig) ==4 || intval($brandOrig) ==62 || intval($brandOrig) ==81){
	$query = "
	SELECT SQL_CALC_FOUND_ROWS job.*,job.id as jobId, sms_brand.userPic as userPic, sms_brand.storeBrand, account.status as acctStatus FROM `job`
	LEFT JOIN sms_brand on sms_brand.id = job.brand
	LEFT OUTER JOIN account as account on account.id = job.postId

	where 
        job.id<50 and job.brand ={$brandOrig}
	";
	}else{
	
	$query = "
	SELECT SQL_CALC_FOUND_ROWS *,job.id as jobId, sms_brand.userPic as userPic, sms_brand.storeBrand FROM `job`
	LEFT JOIN sms_brand on sms_brand.id = job.brand

	where 
        job.id=2
	";
	}
$dbData = Config::get('db')->get_results($query);	
}
*/
$totalJobRecords = count($dbData);
$page=0;
/*
$p = new pagination();
$p->items($totalJobRecords);
$p->limit(30);
$p->currentPage($page);

//$p->target("?i={$industry}&k={$keywords}&l={$location}");
$p->parameterName("p");
*/
$currentPageTotal = $totalFeaturedRecords + $countData;
$grandTotal = $totalFeaturedRecords + $totalJobRecords;

$jobsList = array();
$jobsCounter = 0;
$featuredCounter = 0;
//$maxFeatured = min(60,$totalReturnedFeatured);

function RemoveURL($string) {
    return preg_replace('/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', '', $string); 
}

if ($totalFeaturedRecords > 0) {
	foreach($featuredData as $job) {
	    $jobsList[] = $job;
	    $jobsCounter++;
	 }
}

if (count($dbData) > 0) {
	foreach($dbData as $job) {
	    $listFeatured = (($jobsCounter % 10) == 0);
	    if ($listFeatured) {
	        if (isset($featuredData[$featuredCounter])) $jobsList[] = $featuredData[$featuredCounter];
	        $featuredCounter++;
	        if (isset($featuredData[$featuredCounter])) $jobsList[] = $featuredData[$featuredCounter];
	        $featuredCounter++;
	        if (isset($featuredData[$featuredCounter])) $jobsList[] = $featuredData[$featuredCounter];        
	        $featuredCounter++;
	    }
		$jobsList[] = $job;
	    $jobsCounter++;
	}	
}




?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-125801493-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-125801493-1');
</script>
<!--




<head>
<meta charset="utf-8"/>
<title>JobAlarm Mobile</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta content="" name="description"/>
<meta content="" name="author"/>
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="theme/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="theme/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css">
<link href="theme/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="theme/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css">
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="theme/assets/global/plugins/bootstrap-datepicker/css/datepicker3.css"/>
<link rel="stylesheet" type="text/css" href="theme/assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="theme/assets/admin/pages/css/todo.css"/>
<!-- END PAGE LEVEL STYLES -->
<!-- BEGIN THEME STYLES -->
<link href="theme/assets/global/css/components-rounded.css" id="style_components" rel="stylesheet" type="text/css">
<link href="theme/assets/global/css/plugins.css" rel="stylesheet" type="text/css">
<link href="theme/assets/admin/layout3/css/layout.css" rel="stylesheet" type="text/css">
<link href="theme/assets/admin/layout3/css/themes/blue-steel.css" rel="stylesheet" type="text/css" id="style_color">
<link href="theme/assets/admin/layout3/css/custom.css" rel="stylesheet" type="text/css">
<style type="text/css">
	.bg-new {
		background-color: #ecf0f1;
	}
	
   	div.pagination {
		padding: 3px;
		margin: 3px;
		text-align:center;
	}
	
	div.pagination a {
		padding: 2px 5px 2px 5px;
		margin: 2px;
		border: 1px solid #AAAADD;
		
		text-decoration: none; /* no underline */
		color: #000099;
	}
	div.pagination a:hover, div.digg a:active {
		border: 1px solid #000099;

		color: #000;
	}
	div.pagination span.current {
		padding: 2px 5px 2px 5px;
		margin: 2px;
		border: 1px solid #000099;
		
		font-weight: bold;
		background-color: #000099;
		color: #FFF;
	}
	div.pagination span.disabled {
		padding: 2px 5px 2px 5px;
		margin: 2px;
		border: 1px solid #EEE;

		color: #DDD;
	}

	#photon-dropdown {
		position: absolute;
		background: #fff;
		border: 1px solid #ccc;
		z-index: 1000;
		width: 100%;
		max-height: 220px;
		overflow-y: auto;
		box-shadow: 0 2px 6px rgba(0,0,0,0.15);
	}
	.photon-item {
		padding: 8px 12px;
		cursor: pointer;
		font-size: 14px;
	}
	.photon-item:hover {
		background: #f0f4f8;
	}

</style>
<!-- END THEME STYLES -->
<link rel="shortcut icon" href="favicon.ico"/>
<style type="text/css">
<!--
.style1 {font-size: 16px}
.style2 {color: #FFFFFF}
.style3 {color: #FF0000}
-->
</style>
</head>

<!-- END HEAD -->
<!-- BEGIN HEADER -->
<!-- END HEADER -->
<!-- BEGIN BODY -->
<!-- DOC: Apply "page-header-menu-fixed" class to set the mega menu fixed  -->
<!-- DOC: Apply "page-header-top-fixed" class to set the top menu fixed  -->
<body class="page-boxed page-header-fixed page-container-bg-solid page-sidebar-closed-hide-logo page-sidebar-closed">
<!--
<script type="text/javascript">

function newloc() {
	alert("you selected text");
    window.location.replace('http://www.jobalarm.biz/m.php?a=271&b=27&z=75024');
  }
</script>
-->
<!--
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
-->

<script>

var photonTimer = null;
var stateMap = {
    'Alabama':'AL','Alaska':'AK','Arizona':'AZ','Arkansas':'AR','California':'CA',
    'Colorado':'CO','Connecticut':'CT','Delaware':'DE','Florida':'FL','Georgia':'GA',
    'Hawaii':'HI','Idaho':'ID','Illinois':'IL','Indiana':'IN','Iowa':'IA',
    'Kansas':'KS','Kentucky':'KY','Louisiana':'LA','Maine':'ME','Maryland':'MD',
    'Massachusetts':'MA','Michigan':'MI','Minnesota':'MN','Mississippi':'MS','Missouri':'MO',
    'Montana':'MT','Nebraska':'NE','Nevada':'NV','New Hampshire':'NH','New Jersey':'NJ',
    'New Mexico':'NM','New York':'NY','North Carolina':'NC','North Dakota':'ND','Ohio':'OH',
    'Oklahoma':'OK','Oregon':'OR','Pennsylvania':'PA','Rhode Island':'RI','South Carolina':'SC',
    'South Dakota':'SD','Tennessee':'TN','Texas':'TX','Utah':'UT','Vermont':'VT',
    'Virginia':'VA','Washington':'WA','West Virginia':'WV','Wisconsin':'WI','Wyoming':'WY',
    'District of Columbia':'DC'
};

function redirectSearch(val) {
    var brandOrig = document.getElementById('brandOrig').value;
    var acctAdd = document.getElementById('acctAdd').value;
    if (!val) return;
    window.location.replace('https://www.jobalarm.biz/m.php?a=' + acctAdd + '&b=' + brandOrig + '&z=' + encodeURIComponent(val));
}

document.addEventListener('DOMContentLoaded', function() {
    var input = document.getElementById('city');
    var dropdown = document.getElementById('photon-dropdown');

    input.addEventListener('input', function() {
        var val = this.value.trim();
        clearTimeout(photonTimer);
        dropdown.innerHTML = '';
        if (!val || val.length < 2 || /^\d+$/.test(val)) return;

        photonTimer = setTimeout(function() {
            fetch('https://photon.komoot.io/api/?q=' + encodeURIComponent(val) + '&limit=8&layer=city&lang=en')
                .then(function(r){ return r.json(); })
                .then(function(data) {
                    dropdown.innerHTML = '';
                    if (!data.features) return;
                    data.features.forEach(function(f) {
                        var p = f.properties;
                        if (p.country_code !== 'us') return;
                        var city = p.name;
                        var stateCode = stateMap[p.state] || p.state;
                        if (!city || !stateCode) return;
                        var searchVal = city.toUpperCase() + ' ' + stateCode;
                        var div = document.createElement('div');
                        div.className = 'photon-item';
                        div.textContent = city + ', ' + stateCode;
                        div.addEventListener('mousedown', function(e) {
                            e.preventDefault();
                            input.value = searchVal;
                            dropdown.innerHTML = '';
                            redirectSearch(searchVal);
                        });
                        dropdown.appendChild(div);
                    });
                });
        }, 300);
    });

    input.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            var val = this.value.trim();
            dropdown.innerHTML = '';
            redirectSearch(val);
        }
    });

    document.addEventListener('click', function(e) {
        if (!input.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.innerHTML = '';
        }
    });
});
	
</script>

<div class="page-header-top">
		<div class="container" align="center">
			<!-- BEGIN LOGO -->
			<?php
			   //$brandData = Config::get('db')->get_results("SELECT * from sms_brand where id = $brand");
				 
				//if (strlen($dbResult['logo']) > 0) {
			    //    echo '<img src="img/'.$dbResult['logo'].'" />';   
			    //}else
				
				if (strlen($dbResult['storeImage']) > 0) {
					echo '<img src="img/'.$dbResult['storeImage'].'" />';
				}else{
					echo '<img src="img/logo1.png" />';			
				}?>
			<!-- END LOGO -->
			<!-- BEGIN RESPONSIVE MENU TOGGLER -->
			
			<!-- END RESPONSIVE MENU TOGGLER -->
		</div>
		</div>

<!-- BEGIN PAGE CONTENT -->
<div class="m-content">

<!--	<div class="container"> -->


		<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
		
		<!-- /.modal -->
		<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
		<!-- BEGIN PAGE BREADCRUMB -->
				<!-- END PAGE BREADCRUMB -->
		<!-- BEGIN PAGE CONTENT INNER -->
		<div class="m-portlet m-portlet--mobile">
              <div class="m-portlet__body">
			  <div class="row" align="center" style="margin-bottom:20px;">
					
					<img src="img/career.png" alt=""/>
					</div>
				<div class="row">
						  <div class="col-md-12">
								<!-- BEGIN FORM-->
								
                           <h2 class="form-section" style="text-align: center"><strong><?php echo $msg; ?></strong></h2></br>
						   <!--
							<?php if($brandDeal): ?>
							<div class="container" style="text-align: center"><span><h5><strong>
							Get <?php echo $brandName; ?><a target="_blank" href="<?php echo $brandDeal; ?>" onClick="tj.appClick(<?php echo $mobile .",". $brandOrig.",". $acctAdd; ?>)"> App/Rewards.</a></strong></h5></span>
							</br></br>
							</div>	
							<?php endif;?>
							-->
						</div>
						</div>
										
						


<!-- JAMobile -->




						
						
							<!-- PROJECT HEAD -->
							<div class="container bg-new">
					
				
							<!-- end PROJECT HEAD -->
							
							<input id="zipcode" type="hidden" value="<?php echo $zipcode; ?>" />
							<input id="brandOrig" type="hidden" value="<?php echo $brandOrig; ?>" />
							<input id="mobile" type="hidden" value="<?php echo $mobile; ?>" />
							<input id="acctAdd" type="hidden" value="<?php echo $acctAdd; ?>" />
							<input id="long" type="hidden" value="<?php echo $long; ?>" />
							<input id="lati" type="hidden" value="<?php echo $lati; ?>" />
							<input id="status" type="hidden" value="<?php echo $status; ?>" />
	
								<div class="row">
								
									<div class="col-md-12 col-sm-12">
									
								
                                        <div style="color:#999;width:100%;text-align:right;margin-bottom:4px;">
										
                                        
										
                                        </div>
<div style="" data-always-visible="0" data-rail-visible="0" data-handle-color="#dae3e7">                                           
<div class="todo-tasklist">


<div class="form-group m-form__group" id="cityadd">

<label for="city" class="col-4 col-form-label"><strong>Enter City ST or Zip:3</strong>
</label>

<div class="col-lg-6" style="position:relative">
<input type="text" id="city" name="city" autocomplete="off" class="form-control m-input" value="<?php echo htmlspecialchars($zipcode); ?>" placeholder="City ST or Zip" />
<div id="photon-dropdown"></div>
</div>
</div>

<div id="googlead">											
<img src="img/career2.png" alt=""/>
</div>	

<?php

$featuring = false;
$justPosted = false;
$now = time();
foreach($jobsList as $tweet) {
    $tweet['text'] = RemoveURL($tweet['text']);
	/*
    if ($featuring ===false && isset($tweet['featured'])) {
        $featuring = true;
        if ($jobsCounter == 0) {
           echo '<div style="height:3px;margin-bottom:25px 0;width:100%;border-bottom:3px solid #87A9C7"></div>';
			//echo '<div style="height:3px;margin:25px 0;width:100%;border-bottom:3px solid #87A9C7"></div><script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script><ins class="adsbygoogle" style="display:inline-block;width:280px;height:60px" data-ad-client="ca-pub-2545585330917467" data-ad-slot="1689787287"></ins><script>(adsbygoogle = window.adsbygoogle || []).push({});</script>';
        } else {
			echo '<div style="height:3px;margin:25px 0;width:100%;border-bottom:3px solid #87A9C7"></div><script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script><ins class="adsbygoogle" style="display:inline-block;width:280px;height:60px" data-ad-client="ca-pub-2545585330917467" data-ad-slot="1689787287"></ins><script>(adsbygoogle = window.adsbygoogle || []).push({});</script>';
            //echo '<div style="height:3px;margin:25px 0;width:100%;border-bottom:3px solid #87A9C7"></div>';
        }
    } 
	
    if ($featuring === true && !isset($tweet['featured'])) {
        $featuring = false;
        echo '<div style="height:3px;margin:5px 0;width:100%;border-bottom:3px solid #87A9C7"></div><ins class="adsbygoogle" style="display:inline-block;width:280px;height:60px" data-ad-client="ca-pub-2545585330917467" data-ad-slot="1689787287"></ins><div style="height:3px;margin:25px 0;width:100%;border-bottom:3px solid #87A9C7"></div>';
     	//echo '<div style="height:3px;margin:25px 0;width:100%;border-bottom:3px solid #87A9C7"></div><script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script><ins class="adsbygoogle" style="display:inline-block;width:280px;height:60px" data-ad-client="ca-pub-2545585330917467" data-ad-slot="1689787287"></ins><script>(adsbygoogle = window.adsbygoogle || []).push({});</script><div style="height:3px;margin:25px 0;width:100%;border-bottom:3px solid #87A9C7"></div>';
    } 
	*/
	if ($featuring == false && isset($tweet['featured'])) {
        $featuring = true;
        if ($jobsCounter == 0) {
            echo '<div style="height:3px;margin-bottom:25px;width:100%;border-bottom:3px solid #87A9C7"></div>';
        } else {
            echo '<div style="height:3px;margin:20px 0;width:100%;border-bottom:3px solid #87A9C7"></div>';	
        }
    } 
    if ($featuring == true && !isset($tweet['featured'])) {
        $featuring = false;
		echo '<div> </div>';
        //echo '<div style="height:3px;margin:20px 0;width:100%;border-bottom:3px solid #87A9C7"></div><script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script><ins class="adsbygoogle" style="display:inline-block;width:280px;height:60px" data-ad-client="ca-pub-2545585330917467" data-ad-slot="1689787287"></ins><script>(adsbygoogle = window.adsbygoogle || []).push({});</script><div style="height:3px;margin:20px 0;width:100%;border-bottom:3px solid #87A9C7"></div>';
    } 
	
    $jobsCounter++;
    $id = $tweet['jobId'];

	
	$jobTitle = $tweet['title'];
	if($jobTitle){
	$moreSearch = str_replace(" ","_",$tweet['title']);
	}else{
	$moreSearch = '';
	}
	$morePriority = $tweet['priority'];
	
	$jobbrand = $tweet['brand'];
	$acct = $tweet['postId'];
	//$storeName = $tweet['storeBrand'];
	$jobStatus = $tweet['status'];
	$acctStatus = $tweet['acctStatus'];
	$address = stripslashes($tweet['address']);
	$nowDate = date("Y-m-d", $now);
	$dateDiff = (strtotime($nowDate)-strtotime($tweet['postDate']))/(60*60*24);
	$mobile = intval($mobile);
	
	if ($searchAdd){
		$moreLike = '<a href="http://www.jobalarm.biz/m1.php?z='.$zipcode.'&lat='.$lat.'&lon='.$lon.'&a='.$acctAdd.'&m='.$candidateId.'&b='.$brandOrig.'">Back</a>';
	}else{
		$moreLike = '<a href="http://www.jobalarm.biz/m1.php?z='.$zipcode.'&lat='.$lat.'&lon='.$lon.'&a='.$acctAdd.'&m='.$candidateId.'&b='.$brandOrig.'&s='.$moreSearch.'&p='.$morePriority.'">More jobs like this one</a>';
	}
	
	
	
	$rawData = json_decode($tweet['rawData'],true);
	//$user = $rawData['user'];
	$userPic = "/img/".$tweet['userPic'];
	$time = strtotime('-6 hours', strtotime($tweet['postDate']));
	$tweetDate = date("m/d/y g:i A", $time);
	$urls = explode(',',$tweet['urls']);
	$jobUrl = 'javascript:;';
	$onClick = '';
	//$twitterprofilelink = (isset($user['screen_name'])) ? 'https://twitter.com/intent/follow?screen_name='.$user['screen_name'] : 'https://twitter.com';
	if (strlen($id) > 0) {
		$jobUrl = $urls[0];
	    if  ( $ret = parse_url($jobUrl) ) {

	    if ( !isset($ret["scheme"]) )
	    {
	       $jobUrl = "http://{$jobUrl}";
	    }
		}	
			
		//if (intval($tweet['acctStatus']) > 1) {
		if (intval($jobStatus) ==2) {
			//if ($acctStatus2 !=2){			
			$jobUrl = "http://www.jobalarm.biz/app/index.php?b={$jobbrand}&a={$acct}&z={$zipcode}&m={$mobile}";
			//}else{
			//$jobUrl = "http://www.jobalarm.com/app/index.php?b={$jobbrand}&a={$acct2}&z={$zipcode}&m={$mobile}";
			//}
		}
		
		$onClick = "tj.clickTrack(".$id.",".$referral.",".$mobile.",".$brandOrig.",".$acctAdd.");window.open(this.href,'targetWindow','toolbar=no, location=no, status=no, menubar=no, scrollbars=yes,resizable=yes, width=920, height=600');return false;";
	}
    $listClass = (isset($tweet['featured'])) ? 'todo-tasklist-item todo-tasklist-item-border-red todo-tasklist-item-featured' : 'todo-tasklist-item todo-tasklist-item-border-green';
?>	
									
												<div class="<?php echo $listClass; ?>">
												
												
													<img class="todo-userpic pull-left" src="<?php echo (isset($user['profile_image_url'])) ? $user['profile_image_url'] : $userPic;?>" width="27px" height="27px">
													<div class="todo-tasklist-item-title">
														<!--
														 <a target="_blank" href="<?php echo $twitterprofilelink; ?>" class="tooltips" data-container="body" data-placement="top" data-original-title="Follow This User"><?php echo (isset($user['screen_name'])) ? $user['screen_name'] : ''; ?></a>  <?php echo $tweet['city'].' '.$tweet['state']; ?>
                                                         -->
														 <?php if ($dateDiff < 2): ?>
                                                            <div style="float:right;display:inline-block;height:20px;line-height:20px;"><i class="fa fa-star fa-lg" style="line-height:20px;margin-top:0;vertical-align:middle;color:gold"></i> <span style="display:inline-block;line-height:20px;color:#E5C100">Just Posted</span></div>
                                                         <?php endif; ?>
														 <?php if (strlen($address)>5): ?>
                                                            <div><span style="display:inline-block"><?php echo $tweet['address']; ?></span></div>
                                                         <?php endif; ?>
													</div>
													<div class="todo-tasklist-item-text">
														<a href="<?php echo $jobUrl; ?>" onClick="<?php echo $onClick; ?>"> <?php echo stripslashes($tweet['text']); ?> </a>
														<!--class="tooltips" data-container="body" data-placement="top" data-original-title="Go To This Job"-->
													</div>
													<div class="todo-tasklist-controls pull-right">
														
														<?php echo $moreLike; ?>
													
													</div>
												</div>
												
													<?php
													}
													?>


											</div>
										    <!-- BEGIN FOOTER -->




<!-- JAMobile 
<div id="googlead2">
<ins class="adsbygoogle"
     style="display:inline-block;width:280px;height:60px"
     data-ad-client="ca-pub-2545585330917467"
     data-ad-slot="1689787287">
	 </ins>
</div>	 
-->
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>	

</div>	
</div>
</div>
<img src="img/career3.png" alt=""/>
</div>
<div style="height:3px;margin:20px 0;width:100%;border-bottom:3px solid #87A9C7"></div>


<!--
<div id="googlead3">
<ins class="adsbygoogle"
     style="display:inline-block;width:280px;height:60px"
     data-ad-client="ca-pub-2545585330917467"
     data-ad-slot="1689787287">
	 </ins>
</div>
-->	 

<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>



								
<div class="page-footer">
	<div class="container">
		 <p>2018 &copy; Harrelson Group LLC. All Rights Reserved. | <a href="http://www.jobalarm.biz/terms.html" target="_blank" class="style2">Terms of Use</a> | <a href="http://www.jobalarm.biz/privacy.html" target="_blank" class="style2">Privacy Policy</a> </p>
		 </div>
</div>
</div>
<!-- JAMobile -->

										
										



</div>
<!--
</div>
-->		  
					<!-- END TODO CONTENT -->
		
			
			<!-- END PAGE CONTENT INNER -->
 
	<!-- END PAGE CONTENT -->
    
    <!-- END PAGE CONTAINER -->
	

<!--
<div class="scroll-to-top">
	<i class="icon-arrow-up"></i>
</div>
-->
<!-- END FOOTER -->
</div>
<!--</div>-->

<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="theme/assets/global/plugins/respond.min.js"></script>
<script src="theme/assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->

<script src="theme/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="theme/assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="theme/assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="theme/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="theme/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="theme/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="theme/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="theme/assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="theme/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>

<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE PLUGINS & SCRIPTS -->
<!--
<script type="text/javascript" src="theme/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="theme/assets/global/plugins/select2/select2.min.js"></script>
<script src="theme/assets/admin/pages/scripts/todo.js" type="text/javascript"></script>
-->
<!-- END PAGE PLUGINS & SCRIPTS -->
<script src="theme/assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="theme/assets/admin/layout3/scripts/layout.js" type="text/javascript"></script>
<script src="theme/assets/admin/layout3/scripts/demo.js" type="text/javascript"></script>
<script src="inc/tweetedjobs-mainTest.js" type="text/javascript"></script>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-59491934-1', 'auto');
  ga('send', 'pageview');

</script>

<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
