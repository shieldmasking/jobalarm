<?php
include_once 'inc/class.db.php';
include_once 'inc/class.jatwitter.php';
include_once 'inc/config.php';
include_once 'inc/pagination.class.php';

require 'vendor/autoload.php';


use Foolz\SphinxQL\SphinxQL;
use Foolz\SphinxQL\Connection;

$keywords = (isset($_REQUEST['search_keyword'])) ? $_REQUEST['search_keyword'] : ((isset($_GET['k'])) ? $_GET['k'] : '');
$location = (isset($_REQUEST['search_location'])) ? $_REQUEST['search_location'] : ((isset($_GET['l'])) ? $_GET['l'] :'');
$industry = (isset($_GET['i'])) ? $_GET['i'] : 0;
$username = (isset($_REQUEST['u'])) ? $_REQUEST["u"] : '';
$emailaddress = (isset($_REQUEST['e'])) ? $_REQUEST["e"] : '';
$zip = '';

if (preg_match('/^[0-9]{5}([- ]?[0-9]{4})?$/', $location)) {
	$zip = $location;
}
//$keywords = str_replace("'","",$keywords);

$directjobid = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : '';

$referral = (isset($_REQUEST['cf'])) ? ",'fb'" : ((isset($_REQUEST['cx'])) ? ",'txt'" : '');

if (strlen($keywords) > 0 || strlen($location) > 0) {
	$data = array('keywords'=>Config::get('db')->filter($keywords),'location'=>Config::get('db')->filter($location));
	Config::get('db')->insert('search_history',$data);
}
$sqlAdd = '';
$kwdMsg = '';
if (!$directjobid && strlen(trim($keywords)) == 0 && strlen($username) == 0) { $sqlAdd = ' AND 2=1 '; $kwdMsg = '<h3>Please enter a keyword for better search results.</h3>'; }

//$kwdquery = (strlen($keywords) > 0) ? " AND ".buildKeywordQuery('job.text',$keywords) : '';
$kwdquery = (strlen($keywords) > 0) ? buildKeywordQuerySphinx('job.text',$keywords) : '';
$keywordArray = explode(' ',trim($keywords));
//echo $kwdquery."<br />";
$iquery = ($industry > 0) ? " and jobcat.industryId={$industry}" : '';

$industryName = "ALL";

if ($industry > 0) {
	$query = "select * from industry where id={$industry}";
	$dbData = Config::get('db')->get_results($query);
	$industryName = $dbData[0]['name'];
}

$parseloc = JATwitter::GetLocation(strtoupper($location),false);

//var_dump($state);

if ($zip){
	$zipcode = $zip;
}else{
$zipcode = extract_zipcode($location);
}

$zipsearch = '';

$locationName = 'All';

if ($zipcode) {

	$zipcodes = getDistanceQuery(Config::get('db'),$zipcode,25);
	$query = "select distinct city,state_code from cities_extended where zip in {$zipcodes} group by city";
	$cities = Config::get('db')->get_results($query);
	$citiesarray = array();
	foreach($cities as $city) {
		$citiesarray[] = "'{$city['city']}'";
	}
	$zipsearch = "and job.city in (".implode(",",$citiesarray).") and job.state='".$cities[0]['state_code']."'";
	$locationName = $zipcode;
	//echo $zipsearch;
} else {

	if (is_array($parseloc)) {
		if ($parseloc['city'] && $parseloc['state']) {
			$query = "select max(zip) as zipmax from cities_extended where city='{$parseloc['city']}' and state_code='{$parseloc['state']}'";
			$dbData = Config::get('db')->get_results($query);
			if ($dbData && count($dbData) > 0) {
				$zipcodes = getDistanceQuery(Config::get('db'),$dbData[0]['zipmax'],25);
				$query = "select distinct city from cities_extended where zip in {$zipcodes} group by city";
				$cities = Config::get('db')->get_results($query);
				$citiesarray = array();
				foreach($cities as $city) {
					$citiesarray[] = $city['city'];
				}
				$zipsearch = "and job.city in (".implode(",",$citiesarray).") and job.state='{$parseloc['state']}' ";
			}
			$locationName = $parseloc['city'].', '.$parseloc['state'];
		} else if ($parseloc['state']) {
			$query = "select zip from cities_extended where state_code='{$parseloc['state']}'";
			$dbData = Config::get('db')->get_results($query);
			$zipArray = array();
			foreach($dbData as $row) {
				$zipArray[] = $row['zip'];
			}
			$query = "select distinct city from cities_extended where zip in (".implode(",",$zipArray).") group by city";
			$cities = Config::get('db')->get_results($query);
			$citiesarray = array();
			foreach($cities as $city) {
				$citiesarray[] = $city['city'];
			}
			$zipsearch = "and job.city in (".implode(",",$citiesarray).") and job.state='{$parseloc['state']}'";
			$locationName = $parseloc['state'];
		} else if ($parseloc['city']) {
			$query = "select zip from cities_extended where city='{$parseloc['city']}'";
			$dbData = Config::get('db')->get_results($query);
			$zipArray = array();
			foreach($dbData as $row) {
				$zipArray[] = $row['zip'];
			}
			$query = "select distinct city from cities_extended where zip in (".implode(",",$zipArray).") group by city";
			$cities = Config::get('db')->get_results($query);
			$citiesarray = array();
			foreach($cities as $city) {
				$citiesarray[] = $city['city'];
			}
			$zipsearch = "and job.city in (".implode(",",$citiesarray).")";
			$locationName = $parseloc['city'];
		}

	}

}

$page = isset($_GET['p']) ? $_GET['p'] : 1;
$startat = ($page-1) * 20;
$featuredstartat = ($page-1) * 6;


// create a SphinxQL Connection object to use with SphinxQL
$conn = new Connection();
$conn->setParams(array('host' => '127.0.0.1', 'port' => 9306));

//////////////////////////////////////////////////////////////////////
// GET JOBS

if (!$directjobid) {

    $result = SphinxQL::create($conn)->select('id','tweet','city','state','cid','job_industries','postDate')
        ->from('job_index');

    $result->option('max_matches',500000);

    //keyword condition
    if (strlen($kwdquery) > 0) { 
	    //foreach($keywordArray as $kw) {       
        $result = $result->match('tweet',$kwdquery,true);
	    //}
    }

    //city condition
    if (isset($parseloc['city']) && count($citiesarray)>0) {
        $result = $result->match('city',SphinxQL::expr(addslashes("(".implode(') | (',$citiesarray).")")));
    }

    //state condition
    if (isset($parseloc['state']) && strlen($parseloc['state'])>0) {
        $result = $result->match('state',$parseloc['state']);
    }


    //username condition
    if (strlen($username) > 0) {
    	$result = $result->match('username',$username);
    }

    //industry condition
    if ($industry  && $industry > 0) {
        $result = $result->where('job_industries',intval($industry) );
    }
    //    ->where('cid','>',0)
    //$result = $result->where('cid','>',0);
    $result = $result->where('cid','=',0);

    //Order BY
    $result = $result->orderBy('postDate','desc');

    //Limiter
    $result = $result->limit($startat,20)
        ->enqueue(SphinxQL::create($conn)->query('SHOW META'));

    $result = $result->executeBatch();

    $dbData = $result[0];
    $searchIds = array();
	//$searchIds[] = $directjobid;
    foreach($dbData as $tweet) {
       $searchIds[] = $tweet['id'];
    }

/////////////////////////////////////////////////////////////////////////////////////////////
// GET FEATURED JOBS

    //get featured
    $fresult = SphinxQL::create($conn)->select('id','tweet','city','state','cid','job_industries','postDate')
        ->from('job_index');

    $fresult->option('max_matches',500000);

    //keyword condition
    if (strlen($kwdquery) > 0) {        
	    //foreach($keywordArray as $kw) {       
    	    $fresult = $fresult->match('tweet',$kwdquery,true);
	    //}
    }

    //city condition
    if (isset($parseloc['city']) && count($citiesarray)>0) {
        $fresult = $fresult->match('city',SphinxQL::expr(addslashes("(".implode(') | (',$citiesarray).")")));
    }

    //state condition
    if (isset($parseloc['state']) && strlen($parseloc['state'])>0) {
        $fresult = $fresult->match('state',$parseloc['state']);
    }

    //username condition
    if (strlen($username) > 0) {
    	$fresult = $fresult->match('username',$username);
    }

    //industry condition
    if ($industry  && $industry > 0) {
        $fresult = $fresult->where('job_industries',intval($industry));
    }

    //$fresult = $fresult->where('cid',0);
    $fresult = $fresult->where('cid','>',0);

    //Order BY
    $fresult = $fresult->orderBy('postDate','desc');

    //Limiter
    $fresult = $fresult->limit($featuredstartat,6)
        ->enqueue(SphinxQL::create($conn)->query('SHOW META'));

    $fresult = $fresult->executeBatch();

    //var_dump($fresult);

    $featuredIds = array();
    foreach($fresult[0] as $tweet) {
        $featuredIds[] = $tweet['id'];
    }

} else {
    $featuredIds = array();
    $searchIds = array();
	
	$djData = Config::get('db')->get_results("select * from job where id ={$directjobid}");
	if (!$djData){
	$directjobid = 1;
	}
    $searchIds[] = $directjobid;    
}

//////////////////////////////////////////////////////////////////////////////////////////
// GET INDUSTRIES

//get industry counts
$jcresult = SphinxQL::create($conn)->select('i_id','i_name',SphinxQL::expr('count(*) as i_count'))
    ->from('jobcat_index');

$jcresult->option('max_matches',500000);

//keyword condition
if (strlen($kwdquery) > 0) {        
    $jcresult = $jcresult->match('tweet',$kwdquery,true);
}

//city condition
if (isset($parseloc['city']) && count($citiesarray)>0) {
    $jcresult = $jcresult->match('city',SphinxQL::expr(addslashes("(".implode(') | (',$citiesarray).")")));
}

//$jcresult = $jcresult->match('posDate',SphinxQL::expr('> DATE_SUB(NOW(), INTERVAL 30 DAY)'));
  // $jcresult = $jcresult->where('postDate','>','DATE_SUB(NOW(), INTERVAL 30 DAY)');

//state condition
if (isset($parseloc['state']) && strlen($parseloc['state'])>0) {
    $jcresult = $jcresult->where('state',$parseloc['state']);
}

//username condition
if (strlen($username) > 0) {
	$jcresult = $jcresult->match('username',$username);
}

//industry condition 
if ($industry  && $industry > 0) {
	$jcresult = $jcresult->where('i_id',intval($industry));
}

$jcresult = $jcresult->where('jobid','>',intval(0));

$jcresult = $jcresult->orderBy('i_name','asc');

$jcresult = $jcresult->groupBy('i_id');


$jcresult = $jcresult->limit($startat,20)
    ->enqueue(SphinxQL::create($conn)->query('SHOW META'));

$jcresult = $jcresult->executeBatch();

$industries = array();
if (count($jcresult) > 1) {
	$industries = $jcresult[0];

}

//select i_id,i_name,count(*) from jobcat_index group by i_id;

//$result = $query->execute();

//echo "<pre>";
//var_dump($result[1][0]['Value']);
//var_dump($result[1][1]['Value']);
//var_dump($result[1][2]['Value']);
//
//echo "</pre>";
//die('test');

$query = "
	SELECT SQL_CALC_FOUND_ROWS *,job.id as jobId, sms_brand.userPic as userPic, 'featured' as featured FROM `job`
	left join jobcat
	on jobcat.jobId=job.id
    left join campaign
    on campaign.id = job.campaignId
	LEFT JOIN sms_brand on sms_brand.id = job.brand

	where 
        job.id in (".implode(",",$featuredIds).")
        and     job.campaignId>0
	and job.status=1
	order by postDate desc
	limit 0, 6
";


//echo $query;

$featuredData = count($featuredIds) > 0 ? Config::get('db')->get_results($query) : array();
//var_dump($featuredData);
//$query = "SELECT FOUND_ROWS() AS found_rows;";
//$countData = Config::get('db')->get_results($query);
//$totalFeaturedRecords = $countData[0]['found_rows'];

//print_r($featuredData);
//var_dump($featuredData); 

$query = "
	SELECT SQL_CALC_FOUND_ROWS *,job.id as jobId, sms_brand.userPic as userPic FROM `job`
	LEFT JOIN sms_brand on sms_brand.id = job.brand

	where 
        job.id in (".implode(",",$searchIds).")
	
	order by postDate desc
	
";
//in (".implode(",",$searchIds).")
//echo $query;

$dbData = count($searchIds) > 0 ? Config::get('db')->get_results($query) : array();

//var_dump($dbData);
//$query = "SELECT FOUND_ROWS() AS found_rows;";
//$countData = Config::get('db')->get_results($query);
$totalFeaturedRecords = min($fresult[1][1]['Value'],500000);
$totalReturnedFeatured = count($fresult) > 1 ? count($fresult[0]) : 0;
$totalJobRecords = min($result[1][1]['Value'],500000);


$p = new pagination();
$p->items($totalJobRecords);
$p->limit(20);
$p->currentPage($page);
//$p->urlFriendly();
$p->target("?i={$industry}&k={$keywords}&l={$location}&u={$username}");
$p->parameterName("p");

$currentPageTotal = count($featuredData) + count($dbData);
$grandTotal = $totalFeaturedRecords + $totalJobRecords;

$jobsList = array();
$featuredCounter = 0;
$jobsCounter = 0;
$maxFeatured = min(60,$totalReturnedFeatured);
//echo "MAX FEATURED: $maxFeatured";
function RemoveURLs($string) {
    return preg_replace('/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', '', $string); 
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
} else {
	foreach($featuredData as $job) {
	    $jobsList[] = $job;
	    $jobsCounter++;
	}

}


$query = "
SELECT 
    industryId as id, count(jobcat.id) as numItems, industry.name
FROM
    job
        LEFT JOIN
    jobcat ON jobcat.jobId = job.id
        LEFT JOIN
    industry ON jobcat.industryId = industry.id
WHERE
    job.urls NOT LIKE '%short.info%' and job.urls NOT LIKE '%indeed%' and job.urls NOT LIKE '%ziprecruiter%'
    {$iquery}
    {$kwdquery}
    {$zipsearch}
    {$sqlAdd}
GROUP BY industryId
HAVING COUNT(industryId) > 0
";

// SELECT DISTINCT industryId AS id, COUNT( industryId ) AS numItems, industry.name
// FROM jobcat
// LEFT JOIN industry ON industry.id = jobcat.industryId
// LEFT JOIN job on job.id = jobcat.jobId
// WHERE jobcat.jobId in (	SELECT job.id FROM `job`
// 	left join jobcat
// 	on jobcat.jobId=job.id
// 	where 
// 	job.status=1
// 	and job.urls not like '%short.info%'
// 	{$iquery}
// 	{$kwdquery}
// 	{$zipsearch})
// GROUP BY industryId
// HAVING COUNT( industryId ) >0
// ORDER BY industry.name

//echo $query;

//$industries = Config::get('db')->get_results($query);


//$zipSearch = getDistanceQuery(Config::get('db'),75218,20);
//echo $zipSearch."<br />";

$jobsCounter = 0;
$featuredCounter = 0;

?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title>JobAlarm = Real-Local-Jobs</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta content="" name="description">
<meta content="" name="author">
<meta property="og:url" content="http://www.jobalarm.com">
<meta property="og:image" content="http://www.jobalarm.com/img/job2.jpg">
<meta property="og:description" content="JobAlarm.com allows Job Seekers to search the jobs that Employers are tweeting about.  Search Real Local Jobs">

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
.style1 {font-size: 24px}
-->
</style>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<!-- DOC: Apply "page-header-menu-fixed" class to set the mega menu fixed  -->
<!-- DOC: Apply "page-header-top-fixed" class to set the top menu fixed  -->
<body class="page-boxed page-header-fixed page-container-bg-solid page-sidebar-closed-hide-logo page-sidebar-closed">
<!-- BEGIN HEADER -->
<div class="page-header">
	<!-- BEGIN HEADER TOP -->
	<div class="page-header-top">
		<div class="container">
			<!-- BEGIN LOGO -->
			<div class="page-logo">
				<a href="index.php"><img src="img/logo1.png" longdesc="http://www.jobalarm.com"></a>
			</div>
			<!-- END LOGO -->
			<!-- BEGIN RESPONSIVE MENU TOGGLER -->
			<a href="javascript:;" class="menu-toggler"></a>
			<!-- END RESPONSIVE MENU TOGGLER -->

		</div>
	</div>
	<!-- END HEADER TOP -->
	<!-- BEGIN HEADER MENU -->
	<div class="page-header-menu">
		<div class="container">
			<h1 style="width:320px;float:left;margin:0;padding:5px;color:white">Search Results</h1>
			<!-- BEGIN HEADER SEARCH BOX -->
			<form class="search-form form-inline" role="form" action="<?php echo $_SERVER['PHP_SELF'];?>" method="GET" style="text-align:right;float:right;width:50%;background:none">
				<!-- <div class="input-group"> -->
       			    <div class="form-group">
						<input type="text" class="form-control" placeholder="Keywords" name="search_keyword" id="search_keyword" value="<?php echo $keywords;?>" />
					</div>
					<div class="form-group">
						<input type="text" class="form-control" placeholder="City, ST" name="search_location" id="search_location" value="<?php echo $location;?>" />
					</div>
					<input type="hidden" name="u" value="<?php echo $username;?>" />
					<input type="hidden" name="i" value="<?php echo $industry;?>" />
					<div class="form-group">
						<span class="input-group-btn" style="width:42px">
						<a href="javascript:;" class="btn submit" style="color:white">SEARCH</i></a>
						</span>
					</div>
				<!-- </div> -->
			</form>
			<!-- END HEADER SEARCH BOX -->

		</div>
	</div>
	<!-- END HEADER MENU -->
</div>
<!-- END HEADER -->
<!-- BEGIN PAGE CONTAINER -->
<div class="page-container">
	<!-- BEGIN PAGE HEAD -->
	<div class="page-head">
		<div class="container">
			<div style="margin:5px 0;text-align:center">
			<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
			<!-- TJ2 -->
			<ins class="adsbygoogle"
			     style="display:inline-block;width:330px;height:55px"
			     data-ad-client="ca-pub-2545585330917467"
			     data-ad-slot="3881560883"></ins>
			<script>
			(adsbygoogle = window.adsbygoogle || []).push({});
			</script>
			</div>
		</div>
	</div>
	<!-- END PAGE HEAD -->
	<!-- BEGIN PAGE CONTENT -->
	<div class="page-content">
		<div class="container">
			<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
			<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
							<h4 class="modal-title">Modal title</h4>
						</div>
						<div class="modal-body">
							 Widget settings form goes here
						</div>
						<div class="modal-footer">
							<button type="button" class="btn blue">Save changes</button>
							<button type="button" class="btn default" data-dismiss="modal">Close</button>
						</div>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>
			<!-- /.modal -->
			<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
			<!-- BEGIN PAGE BREADCRUMB -->
			<ul class="page-breadcrumb breadcrumb">
				<li>
					<a href="index.php">Home</a><i class="fa fa-circle"></i>
				</li>
<!-- 				<li>
					<a href="page_todo.html">Pages</a>
					<i class="fa fa-circle"></i>
				</li> -->
				<li class="active">
					 Search Results
				</li>
			</ul>
			<!-- END PAGE BREADCRUMB -->
			<!-- BEGIN PAGE CONTENT INNER -->
			<div class="row">
				<div class="col-md-10">
				<!-- BEGIN TODO SIDEBAR -->
					
					<!--<div class="todo-sidebar">
					
						<div class="portlet light">
							<div class="portlet-title">
								<div class="caption" data-toggle="collapse" align="center">
									<span class="caption-subject font-blue-sharp bold uppercase">JobAlarm Lets You Talk to Employers via Text Message! </span>
								</div>
							</div>
						<div class="form-group" align="center">
						<a href="http://www.jobalarm.com/apply.php?b=6" target="_blank">CHECK IT OUT!</a>
						</div>
													
						</div>
				
					
					<div class="todo-sidebar">
						<div class="portlet light">
							<div class="portlet-title">
								<div class="caption" data-toggle="collapse" data-target=".todo-project-list-content">
									<span class="caption-subject font-blue-sharp bold uppercase">INDUSTRIES </span>
									<span class="caption-helper visible-sm-inline-block visible-xs-inline-block">click to view</span>
								</div>
							</div>
							<div class="portlet-body todo-project-list-content">
								<div class="todo-project-list">
									<ul class="nav nav-pills nav-stacked">
										<li<?php echo ($industry == 0) ? ' class="active"' : '';?>>
											<a href="javascript:;" onClick="tj.selectIndustry(0);">											
											All
											</a>
										</li>
<?php
foreach ($industries as $ind):
?>
										<li<?php echo ($industry == $ind['i_id']) ? ' class="active"' : '';?>>
											<a href="javascript:;" onClick="tj.selectIndustry(<?php echo $ind['i_id']; ?>);">
											<span class="badge <?php echo ($industry == $ind['i_id']) ? 'badge-active' : 'badge-default';?>"> <?php echo $ind['i_count']; ?> </span> 
											<?php echo $ind['i_name']; ?>
											</a>
										</li>
<?php
endforeach;
?>
									</ul>
								</div>
							</div>
						</div>
						</div>
						-->
<!-- 						<div class="portlet light">
							<div class="portlet-title">
								<div class="caption" data-toggle="collapse" data-target=".todo-user-list-content">
									<span class="caption-subject font-blue-sharp bold uppercase">USERS </span>
									<span class="caption-helper visible-sm-inline-block visible-xs-inline-block">click to view</span>
								</div>
							</div>
							<div class="portlet-body todo-user-list-content">
								<div class="todo-project-list">
									<ul class="nav nav-pills nav-stacked">
									</ul>
								</div>
							</div>
						</div>
					</div>
					-->
					<!-- END TODO SIDEBAR -->
					<!-- BEGIN TODO CONTENT -->
					<div class="todo-content" style="height:auto !important;">
						<div class="portlet light">
							<!-- PROJECT HEAD -->
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-bar-chart font-blue-sharp hide"></i>
									<?php if (strlen($keywords) > 0):?>
									<span class="caption-helper">SEARCH:</span> &nbsp; <span class="caption-subject font-blue-sharp bold uppercase" style="padding-right:20px;"><?php echo $keywords; ?></span>
									<?php endif; ?>
									<span class="caption-helper">INDUSTRY:</span> &nbsp; <span class="caption-subject font-blue-sharp bold uppercase"><?php echo $industryName; ?></span>
  								    <span class="caption-helper" style="padding-left:20px">LOCATION:</span> &nbsp; <span class="caption-subject font-blue-sharp bold uppercase"><?php echo $locationName; ?></span>
								</div>

							</div>
							<!-- end PROJECT HEAD -->
							<div class="portlet-body">
								<div class="row">
									<div class="col-md-12 col-sm-12">
                                                                <?php echo $kwdMsg; ?>
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
foreach($jobsList as $tweet) {
    $tweet['text'] = RemoveURLs($tweet['text']);
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
	$jobbrand = $tweet['brand'];
	$acct = $tweet['postId'];
	$userPic = "/img/".$tweet['userPic'];
	$rawData = json_decode($tweet['rawData'],true);
	$user = $rawData['user'];
	$time = strtotime('-6 hours', strtotime($tweet['postDate']));
	$tweetDate = date("m/d/y g:i A", $time);
	$urls = explode(',',$tweet['urls']);
	$jobUrl = 'javascript:;';
	$onClick = '';

	$twitterprofilelink = (isset($user['screen_name'])) ? 'https://twitter.com/intent/follow?screen_name='.$user['screen_name'] : 'https://twitter.com';
	if (count($urls) > 0) {
		$jobUrl = $urls[0];
		
		if (!(strtolower(substr($jobUrl,0,4)) == 'http')) $jobUrl = 'http://'.$jobUrl;
		
		if (intval($tweet['status']) == 2 && intval($acct) > 0) {
			
			$jobUrl = "http://www.jobalarm.com/app/index.php?b={$jobbrand}&a={$acct}";
		}
		$onClick = "tj.clickTrack(".$id.$referral.");window.open(this.href,'targetWindow','toolbar=no, location=no, status=no, menubar=no, scrollbars=yes,resizable=yes, width=920, height=600');return false;";
	}
    $listClass = (isset($tweet['featured'])) ? 'todo-tasklist-item todo-tasklist-item-border-red todo-tasklist-item-featured' : 'todo-tasklist-item todo-tasklist-item-border-green';
?>										
												<div class="<?php echo $listClass; ?>">
													<img class="todo-userpic pull-left" src="<?php echo (isset($user['profile_image_url'])) ? $user['profile_image_url'] : $userPic;?>" width="27px" height="27px">
													<div class="todo-tasklist-item-title">
														 <a target="_blank" href="<?php echo $twitterprofilelink; ?>" class="tooltips" data-container="body" data-placement="top" data-original-title="Follow This User"><?php echo (isset($user['screen_name'])) ? $user['screen_name'] : ''; ?></a> - <?php echo $tweet['city'].', '.$tweet['state']; ?>
                                                         <?php if (isset($tweet['featured'])): ?>
                                                            <div style="float:right;display:inline-block;height:22px;line-height:22px;"><i class="fa fa-star fa-lg" style="line-height:22px;margin-top:0;vertical-align:middle;color:gold"></i> <span style="display:inline-block;line-height:22px;color:#E5C100">Sponsored Post</span></div>
                                                         <?php endif; ?>
													</div>
													<div class="todo-tasklist-item-text">
														<a href="<?php echo $jobUrl; ?>" onClick="<?php echo $onClick; ?>" class="tooltips" data-container="body" data-placement="top" data-original-title="Go To This Job"> <?php echo $tweet['text']; ?> </a>
													</div>
													<div class="todo-tasklist-controls pull-left">
														<!--<span class="todo-tasklist-date">Tweeted: <?php echo $tweetDate; ?> </span>-->
														<?php
    $hashTags = explode(',',$tweet['hashTags']);
    foreach($hashTags as $hashtag):
														?>
														<span class="todo-tasklist-badge badge badge-roundless"><a target="_blank" href="https://twitter.com/hashtag/<?php echo $hashtag; ?>?f=realtime">#<?php echo $hashtag; ?></a></span>
														<?php
    endforeach;
														?>
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
    window.open("http://www.jobalarm.com/search.php");
}
</script>
</div>


			
							</div>
							</div>

							<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
			<!-- TJ2 -->
			<ins class="adsbygoogle"
			     style="display:inline-block;width:330px;height:55px"
			     data-ad-client="ca-pub-2545585330917467"
			     data-ad-slot="3881560883"></ins>
			<script>
			(adsbygoogle = window.adsbygoogle || []).push({});
			</script>
						
					</div>
					<!-- END TODO CONTENT -->
				</div>
				
			</div>
			<!-- END PAGE CONTENT INNER -->
		</div>
	</div>
	<!-- END PAGE CONTENT -->
</div>
<!-- END PAGE CONTAINER -->

<!-- BEGIN FOOTER -->
<div class="page-footer">
	<div class="container">
		 2015 &copy; Premier SSG, Inc. All Rights Reserved.
	</div>
</div>
<div class="scroll-to-top">
	<i class="icon-arrow-up"></i>
</div>
<!-- END FOOTER -->
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
<script>
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core components
Layout.init(); // init current layout
Demo.init(); // init demo features
Todo.init(); // init todo page
tj = {};
tj.industry = <?php echo ($industry > 0) ? $industry : "''"; ?>;
tj.selectIndustry = function(id) {
	var base_url = window.location.href.split('?')[0];
	window.location = base_url+"?i="+id+'&k=<?php echo $keywords?>&l=<?php echo $location;?>&u=<?php echo $username; ?>' ;
}
tj.saveSearch = function() {
	var email_address = $('#email_address').val();
	var kw = $('#search_keyword').val();
	var loc = $('#search_location').val();
	$.ajax({
		url:'dataTest.php?ss=1',
		method:'POST',
		data:{
			email:email_address,
			industry:tj.industry,
		    keywords:kw,
			location:loc
		},
		success:function(data) {
			$('#email_address').replaceWith($('<strong>Success!<br> We will continue to send jobs that match your search.</strong>'));
			$('#save_email_btn').addClass('disabled');
			var win = window.open('http://www.jobalarm.com/register.php?email='+email_address, "JobAlarm Registration", "width=600, height=800, scrollbars=yes", '_blank');
			if(win){
			    //Browser has allowed it to be opened
			    win.focus();
			}else{
			    //Broswer has blocked it
			    alert('Please allow popups for this site');
			}
		},
		error:function(xhr,e) {

		}
	});	
};
tj.clickTrack = function(jobId,ref) {
	var referrer = ref || '';
    $.ajax({
        url: 'data.php?ct=1',
        data: { jid: jobId, ref: referrer },
        method: 'post',
        success: function (response) {

        }
    })
}
});
</script>
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
