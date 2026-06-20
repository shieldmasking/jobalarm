<?php
//session_start();
//ini_set('display_errors',1);
include_once 'inc/class.db.php';
include_once 'inc/class.jatwitter.php';
include_once 'inc/config.php';
include_once 'inc/pagination.class.php';

require_once 'vendor/autoload.php';

/////Test Data
$zipcode = (isset($_REQUEST['z'])) ? $_REQUEST['z'] : '';
$brandOrig = (isset($_REQUEST['b'])) ? $_REQUEST['b'] : 6;
$distance = (isset($_REQUEST['d'])) ? $_REQUEST['d'] : 20;
$candidateId = (isset($_REQUEST['m'])) ? $_REQUEST['m'] : '';
$searchKey = (isset($_REQUEST['s'])) ? $_REQUEST['s'] : '';
$acctAdd = (isset($_REQUEST['a'])) ? $_REQUEST['a'] : '';
$group = (isset($_REQUEST['g'])) ? $_REQUEST['g'] : '';
$accType = 0;
$industry = 0;
$brandDeal = '';

if (intval($brandOrig)==9){
	echo "<script>
window.location.href='https://jobs.cvshealth.com';
</script>";
}


if ($acctAdd){
$dbMobile = Config::get('db')->get_results("SELECT c.*, x.id as xid, s.id as brandOrig, s.storeBrand, s.twitterDemo, s.color, s.keyword, s.positions, s.type, s.storeImage, s.deal, a.logo, a.brandId as industry, a.logopic, a.status as accType, ad.adImg, ad.active as adActive, ad.adLink, ad.id as adLinkId from `candidate` c left outer join `candidateXref` as x on x.candidateId = c.id and x.brandOrig ={$brandOrig} left join `sms_brand` as s on s.id ={$brandOrig} left join `account` as a on a.id ={$acctAdd} left outer join `ads` as ad on ad.brandId = s.id where c.id ={$candidateId} or c.mobile ={$candidateId} group by c.id");
$accType = $dbMobile[0]['accType'];
$industry = $dbMobile[0]['industry'];
}else{
$dbMobile = Config::get('db')->get_results("SELECT c.*, x.id as xid, s.id as brandOrig, s.storeBrand, s.twitterDemo, s.color, s.keyword, s.positions, s.type, s.storeImage, s.deal, ad.adImg, ad.adLink, ad.id as adLinkId from `candidate` c left outer join `candidateXref` as x on x.candidateId = c.id and x.brandOrig ={$brandOrig} left join `sms_brand` as s on s.id ={$brandOrig} left outer join `ads` as ad on ad.brandId = s.id where c.id ={$candidateId} or c.mobile ={$candidateId} group by c.id");
}

$dbResult = $dbMobile[0];
//$_SESSION['candidate'] =$dbResult;

$referral =1;

//echo $brandOrig;

$brandImage = $dbResult['storeImage'];
$mobile = $dbResult['mobile'];
$brandName = $dbResult['storeBrand'];
$brandDeal = $dbResult['deal'];
$brandType = $dbResult['type'];
//$twitterDemo = $dbResult["twitterDemo"];

$adLinkId = $dbResult['adLinkId'];

if(intval($adLinkId) > 0){
$adImg = "../img/".$dbResult['adImg'];
$adLink = $dbResult['adLink'];
$active = $dbResult['adActive'];
//$adClick = "tj.adTrack(".$mobile.",".$adLinkId.");window.open(this.href,'targetWindow','toolbar=no, location=no, status=no, menubar=no, scrollbars=yes,resizable=yes, width=920, height=600');return false;";
$adDetails = "<a href=\"" . $adLink . "\"><img src=\"" . $adImg . "\" target=\"_blank\" onclick=\"tj.adTrack(" . $mobile . "," . $adLinkId . ");\"></a>";
}else{
$adDetails = '';
}

if (intval($accType)==2 || intval($accType)>=5){
$groupBy = "GROUP BY j.brand, j.city, j.title";
$groupBy2 = "GROUP BY j.brand, j.city, j.title";
}else{
$groupBy = '';
$groupBy2 = '';
}

$zipsearch = '';
$zipDist = $distance;
$dbCoord = Config::get('db') -> get_results("select latitude, longitude, city, state_code from cities_extended where zip={$zipcode}");
if (!$dbCoord){
	echo "Invalid Zip Code";
	$zipcode = 99950;
$dbCoord = Config::get('db') -> get_results("select latitude, longitude, city, state_code from cities_extended where zip={$zipcode}");
}

$lat=$dbCoord[0]["latitude"];
$lon=$dbCoord[0]["longitude"];
$searchAdd = '';
$brandAdd = '';
if (intval($acctAdd) >0){
	$brandAdd = " AND j.postId=".$acctAdd." AND j.brand=".$brandOrig;
}else{
	$brandAdd = " AND j.brand=".$brandOrig;
}

if($searchKey){
		$zipDist = 20;
		$groupBy = '';
		$groupBy2 = "GROUP BY j.brand, j.city, j.title ";
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
			$searchAdd = " and (UPPER(j.text) LIKE \"%CALL CENTER%\" OR UPPER(j.text) LIKE \"%CALL CENTER%\") ";
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
}else{
	$searchAdd = '';
}


if (intval($accType)<5 && intval($accType)>0){
$query = "
	SELECT SQL_CALC_FOUND_ROWS j.*, j.id as jobId, a.status as acctStatus, s.userPic as userPic, s.storeBrand, (3963*ACOS(SIN({$lat}/57.2958)*SIN(ce.latitude/57.2958)+ COS({$lat}/57.2958) *COS(ce.latitude/57.2958)*COS((ce.longitude/57.2958)-({$lon}/57.2958)))) as dist 
	FROM job j
		LEFT JOIN sms_brand as s on s.id = j.brand
		LEFT OUTER JOIN account as a on a.id = j.postId
	    inner join cities_extended ce on ce.zip = j.zipCode
	where 
        (3963*ACOS(SIN({$lat}/57.2958)*SIN(ce.latitude/57.2958)+ COS({$lat}/57.2958) *COS(ce.latitude/57.2958)*COS((ce.longitude/57.2958)-({$lon}/57.2958))))<=$zipDist and j.status>0 and j.postId>0$brandAdd$searchAdd
	$groupBy 
	order by dist asc, j.brand asc
	Limit 0,50";
	
	$dbData = Config::get('db')->get_results($query);
}else{
	$query = "
	SELECT SQL_CALC_FOUND_ROWS j.*, j.id as jobId, a.status as acctStatus, a.brandId as industry, s.userPic as userPic, s.storeBrand, (3963*ACOS(SIN({$lat}/57.2958)*SIN(ce.latitude/57.2958)+ COS({$lat}/57.2958) *COS(ce.latitude/57.2958)*COS((ce.longitude/57.2958)-({$lon}/57.2958)))) as dist 
	FROM job j
		LEFT JOIN sms_brand as s on s.id = j.brand
		LEFT OUTER JOIN account as a on a.id = j.postId
	    inner join cities_extended ce on ce.zip = j.zipCode
	where 
        (3963*ACOS(SIN({$lat}/57.2958)*SIN(ce.latitude/57.2958)+ COS({$lat}/57.2958) *COS(ce.latitude/57.2958)*COS((ce.longitude/57.2958)-({$lon}/57.2958))))<=$zipDist and j.status>0 and a.status >=4 and j.brand !={$brandOrig} and (j.postId ={$acctAdd} or a.brandId !={$industry})$searchAdd 
		$groupBy2 
		order by dist ASC
	Limit 0,10";
	
	$dbData = Config::get('db')->get_results($query);
	
	if(count($dbData)>0 && intval($accType)<=6){
		$limit = "LIMIT 0,10";
	}else if(count($dbData)>0 && intval($accType)>=7){
		$limit = "LIMIT 0,5";
	}else{
		$limit = "LIMIT 0,5";
	}
	
$query1 = "
	SELECT SQL_CALC_FOUND_ROWS j.*, j.id as jobId, j.postId as featured, a.status as acctStatus, s.userPic as userPic, s.storeBrand, (3963*ACOS(SIN({$lat}/57.2958)*SIN(ce.latitude/57.2958)+ COS({$lat}/57.2958) *COS(ce.latitude/57.2958)*COS((ce.longitude/57.2958)-({$lon}/57.2958)))) as dist 
	FROM job j
		LEFT JOIN sms_brand as s on s.id = j.brand
		LEFT OUTER JOIN account as a on a.id = j.postId
	    inner join cities_extended ce on ce.zip = j.zipCode
	where 
        (3963*ACOS(SIN({$lat}/57.2958)*SIN(ce.latitude/57.2958)+ COS({$lat}/57.2958) *COS(ce.latitude/57.2958)*COS((ce.longitude/57.2958)-({$lon}/57.2958))))<=$zipDist and j.status>0$brandAdd$searchAdd
	$groupBy
	order by dist asc, j.brand asc 
	$limit
	";

	if (!$dbData) {
	$query = "
	SELECT SQL_CALC_FOUND_ROWS j.*, j.id as jobId, j.postId as featured, a.status as acctStatus, s.userPic as userPic, s.storeBrand, (3963*ACOS(SIN({$lat}/57.2958)*SIN(ce.latitude/57.2958)+ COS({$lat}/57.2958) *COS(ce.latitude/57.2958)*COS((ce.longitude/57.2958)-({$lon}/57.2958)))) as dist 
	FROM job j
		LEFT JOIN sms_brand as s on s.id = j.brand
		LEFT OUTER JOIN account as a on a.id = j.postId
	    inner join cities_extended ce on ce.zip = j.zipCode
	where 
        (3963*ACOS(SIN({$lat}/57.2958)*SIN(ce.latitude/57.2958)+ COS({$lat}/57.2958) *COS(ce.latitude/57.2958)*COS((ce.longitude/57.2958)-({$lon}/57.2958))))<=$zipDist and j.status>0$brandAdd$searchAdd
	$groupBy
	order by dist asc, j.title desc
	Limit 6,20";
	$dbData = Config::get('db')->get_results($query);
	}
	
	$featuredData = Config::get('db')->get_results($query1);
	
	if(!$featuredData){
	$query2 = "
	SELECT SQL_CALC_FOUND_ROWS j.*, j.id as jobId, j.postId as featured, a.status as acctStatus, s.userPic as userPic, s.storeBrand 
	FROM job j
		LEFT JOIN sms_brand as s on s.id = j.brand
		LEFT OUTER JOIN account as a on a.id = j.postId
	where j.status=2$brandAdd 
	$groupBy
	order by j.title desc";
	
	$featuredData = Config::get('db')->get_results($query2);
		
	}
}
//echo "query".$query;

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

$p = new pagination();
$p->items($totalJobRecords);
$p->limit(20);
$p->currentPage($page);
//$p->urlFriendly();
$p->target("?i={$industry}&k={$keywords}&l={$location}");
$p->parameterName("p");

$currentPageTotal = count($featuredData) + count($dbData);
$grandTotal = $totalFeaturedRecords + $totalJobRecords;

$jobsList = array();
$jobsCounter = 0;
$featuredCounter = 0;
//$maxFeatured = min(60,$totalReturnedFeatured);

function RemoveURL($string) {
    return preg_replace('/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', '', $string); 
}

if (count($featuredData) > 0) {
	foreach($featuredData as $job) {
	    $jobsList[] = $job;
	    $jobsCounter++;
	 }
}

if (count($dbData) > 0) {
	foreach($dbData as $job) {
	    $listFeatured = (($jobsCounter % 10) == 0);
	    if (($featuredCounter < $maxFeatured) && ($listFeatured)) {
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

<head>
<meta charset="utf-8"/>
<title>JobAlarm Mobile</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta content="" name="description"/>
<meta content="" name="author"/>
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css">
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
<div class="page-content">
	<div class="container">
		<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
		
		<!-- /.modal -->
		<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
		<!-- BEGIN PAGE BREADCRUMB -->
				<!-- END PAGE BREADCRUMB -->
		<!-- BEGIN PAGE CONTENT INNER -->
		<div class="portlet light">
			<div class="portlet-body">
				<div class="row">
						  <div class="col-md-12">
								<!-- BEGIN FORM-->
								
                           <h4 class="form-section" style="text-align: center"><strong>Your Jobs Are Listed Below!</strong></h4></br>
						   
							<?php if($brandDeal): ?>
							<div class="container">
							<input type="checkbox" data-toggle="modal" data-target="#optinjobalarm"><strong>Receive <?php echo $brandName; ?> Coupon & Discount Messages</strong></input>
							</br></br>
							</div>	
							<?php endif;?>
			

<!--$adClick = '';
$adClick = "tj.adTrack(".$mobile.",".$adLinkId.");window.open(this.href,'targetWindow','toolbar=no, location=no, status=no, menubar=no, scrollbars=yes,resizable=yes, width=920, height=600');return false;";
echo $adClick;-->
<!--<div class="container">-->
<!--<div class="col-md-6">-->

<!--</div>-->

<!--<div class="col-md-6">
					
						
						</div>-->
						</div>
						</div>
						</div>
						
<?php if(intval($active)>0) { 
echo $adDetails;
}else{ ?>

<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js">
</script>
<!-- JAMobile -->
<ins class="adsbygoogle"
     style="display:inline-block;width:290px;height:45px"
     data-ad-client="ca-pub-2545585330917467"
     data-ad-slot="1689787287"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>

<?php } ?>
						
						
							<!-- PROJECT HEAD -->
							<div class="container">
					
				
							<!-- end PROJECT HEAD -->
							<div class="portlet-body">
								<div class="row">
									<div class="col-md-12 col-sm-12">
									<!--<h3 class="form-section" style="text-align: center"><strong>Your Local Jobs</strong></h3>
                                                                <?php echo $kwdMsg; ?>-->
                                        <div style="color:#999;width:100%;text-align:right;margin-bottom:4px;">
                                        <?php
                                        $pageFrom = $startat + 1;
                                        $pageTo = min($startat + 20,$totalJobRecords);
                                        if ($totalJobRecords > 0) echo "Jobs {$pageFrom} to {$pageTo} of {$totalJobRecords}";
                                        ?>
                                        </div>
										<div style="" data-always-visible="0" data-rail-visible="0" data-handle-color="#dae3e7">                                           
											<div class="todo-tasklist">
<?php
$featuring = false;
$justPosted = false;
$now = time();
foreach($jobsList as $tweet) {
    $tweet['text'] = RemoveURL($tweet['text']);
    if (!$featuring && isset($tweet['featured'])) {
        $featuring = true;
        if ($jobsCounter == 0) {
            echo '<div style="height:3px;margin-bottom:25px;width:100%;border-bottom:3px solid #87A9C7"></div>';
        } else {
            echo '<div style="height:3px;margin:25px 0;width:100%;border-bottom:3px solid #87A9C7"></div>';
        }
    } 
    if ($featuring && !isset($tweet['featured'])) {
        $featuring = false;
        echo '<div style="height:3px;margin:25px 0;width:100%;border-bottom:3px solid #87A9C7"></div>';
    } 
    $jobsCounter++;
    $id = $tweet['jobId'];
	
	$jobTitle = $tweet['title'];
	if($jobTitle){
	$moreSearch = str_replace(" ","_",$tweet['title']);
	}else{
	$moreSearch = '';
	}
	
	$jobbrand = $tweet['brand'];
	$acct = $tweet['postId'];
	//$storeName = $tweet['storeBrand'];
	$jobStatus = $tweet['status'];
	$acctStatus = $tweet['acctStatus'];
	$nowDate = date("Y-m-d", $now);
	$dateDiff = (strtotime($nowDate)-strtotime($tweet['postDate']))/(60*60*24);
	$mobile = intval($mobile);
	
	if ($searchAdd){
		$moreLike = '<a href="http://www.jobalarm.biz/m.php?z='.$zipcode.'&a='.$acctAdd.'&m='.$candidateId.'&b='.$brandOrig.'">Back</a>';
	}else{
		$moreLike = '<a href="http://www.jobalarm.biz/m.php?z='.$zipcode.'&a='.$acctAdd.'&m='.$candidateId.'&b='.$brandOrig.'&s='.$moreSearch.'">More jobs like this one</a>';
	}
	
	
	
	$rawData = json_decode($tweet['rawData'],true);
	$user = $rawData['user'];
	$userPic = "/img/".$tweet['userPic'];
	$time = strtotime('-6 hours', strtotime($tweet['postDate']));
	$tweetDate = date("m/d/y g:i A", $time);
	$urls = explode(',',$tweet['urls']);
	$jobUrl = 'javascript:;';
	$onClick = '';
	$twitterprofilelink = (isset($user['screen_name'])) ? 'https://twitter.com/intent/follow?screen_name='.$user['screen_name'] : 'https://twitter.com';
	if (count($id) > 0) {
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
														 <a target="_blank" href="<?php echo $twitterprofilelink; ?>" class="tooltips" data-container="body" data-placement="top" data-original-title="Follow This User"><?php echo (isset($user['screen_name'])) ? $user['screen_name'] : ''; ?></a>  <?php echo $tweet['city'].' '.$tweet['state']; ?>
                                                         <?php if ($dateDiff < 2): ?>
                                                            <div style="float:right;display:inline-block;height:22px;line-height:22px;"><i class="fa fa-star fa-lg" style="line-height:22px;margin-top:0;vertical-align:middle;color:gold"></i> <span style="display:inline-block;line-height:22px;color:#E5C100">Just Posted</span></div>
                                                         <?php endif; ?>
													</div>
													<div class="todo-tasklist-item-text">
														<a href="<?php echo $jobUrl; ?>" onClick="<?php echo $onClick; ?>"> <?php echo $tweet['text']; ?> </a>
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
										
										</div>
										
									</div>
									
								</div>
								
                                <div style="text-align:center;width:100%;margin-top:20px">
                                    <?php $p->show(); ?>
                                </div>
<div align="center"> 
	  <button onClick="newSearch()"><span class="font-lg style1">New Search</span></button>
      
<p id="demo"></p>

<script>
function newSearch() {
    window.open("http://www.jobalarm.biz/search.php");
}
</script>

</div>
</div>





							

<div class="modal fade" id="optinjobalarm" tabindex="-1" role="basic" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
											<h4 class="modal-title"><strong>- JobAlarm Deals & Discounts</strong></h4>
										</div>
										<div id="optBody" class="modal-body">
										<div class="form-group">
										<p><strong>You are currently subcribed to receive only jobs from the Employer whose keyword you texted.  
										<!--<p>JobAlarm also offers you the ability to receive text messages about jobs, deals and discounts from many other great companies.--><p>Upgrade your JobAlarm services here:</strong></p>
										</div>
										<div class="form-group">
										<?php if (intval($brandType) !=8) {?>
										<label class="form-group-label">
										<input type="radio" id="alldeals" name="alldeals" value="1" />
										<strong>Please Allow <span class="style3"><?php echo $brandName; ?></span> To Send Me Text Messages with Deals & Discounts.</strong></br>
										</label>
										<?php } ?>
										<!--
										<label class="form-group-label">
										<input type="radio" id="alldeals" name="alldeals" value="2" />
										<strong>Please Allow <span class="style3">All Companies</span> To Send Me Text Messages with Deals & Discounts.</strong>
										</label>
										-->
										</div>
										<div class="form-group">
										<input type="hidden" name="mobile" id="mobile" value="<?php echo $mobile; ?>" />
										<p><strong>You will receive a confirmation text and up to 4 text messages per month.</strong></p>
										</div>
										<div>
										<p>By entering my information and clicking "Submit",  I am providing express consent to be contacted by JobAlarm.com via email, phone and text message, regarding marketing promotions using automated technology. Standard message and data rates may apply to text messages. You are not required to provide consent to receive services from JobAlarm.com. I also have read and agree to the JobAlarm.com <a href="/terms">Terms of Use and Privacy Policy.</a></p>
										
										<!--<p>* Conversational messages with Employers and administrative messages from JobAlarm.com do not count towards the 4 messages per month limit.</p>-->
										</div>
										<!--<div class="form-group">
										<p>*Subject to JobAlarm <a href="/terms" target="_blank"> Terms & Conditions.</a> </p>
										</div>-->
										<div class="form-group">
										<button type="button" class="btn blue pull-left" onClick="tj.optin()">Submit</button>
										<!--<button type="submit" class="btn btn-success pull-right" id="optinsave">Save</button>-->
										<button type="button" class="btn default pull-left" data-dismiss="modal">Close</button>
										
										</div>
										
				
										
									<!-- /.modal-content -->
								<div class="modal-footer">
																						
								</div>
								<!-- /.modal-dialog -->
							</div>
			</div>							
</div>
							
</div>
</div>

<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- JAMobile -->
<ins class="adsbygoogle"
     style="display:inline-block;width:300px;height:50px"
     data-ad-client="ca-pub-2545585330917467"
     data-ad-slot="1689787287"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>



</div>

</div>
		  
					<!-- END TODO CONTENT -->
		
			
			<!-- END PAGE CONTENT INNER -->
 
	<!-- END PAGE CONTENT -->
    
    <!-- END PAGE CONTAINER -->
	
    <!-- BEGIN FOOTER -->
<div class="page-footer">
	<div class="container">
		 <p>2015 &copy; Premier SSG, Inc. All Rights Reserved. | <a href="http://www.jobalarm.biz/terms.html" target="_blank" class="style2">Terms of Use</a> | <a href="http://www.jobalarm.biz/privacy.html" target="_blank" class="style2">Privacy Policy</a> </p>
		 </div>
</div>
<div class="scroll-to-top">
	<i class="icon-arrow-up"></i>
</div>
<!-- END FOOTER -->
</div>
</div>
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
<script type="text/javascript" src="theme/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="theme/assets/global/plugins/select2/select2.min.js"></script>
<script src="theme/assets/admin/pages/scripts/todo.js" type="text/javascript"></script>
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
