<?php
include 'inc/class.db.php';
include 'inc/class.jatwitter.php';
include 'inc/config.php';
include 'inc/pagination.class.php';

require 'vendor/autoload.php';


use Foolz\SphinxQL\SphinxQL;
use Foolz\SphinxQL\Connection;

$keywords = (isset($_REQUEST['search_keyword'])) ? $_REQUEST['search_keyword'] : ((isset($_GET['k'])) ? $_GET['k'] : '');
$location = (isset($_REQUEST['search_location'])) ? $_REQUEST['search_location'] : ((isset($_GET['l'])) ? $_GET['l'] :'');
$industry = (isset($_GET['i'])) ? $_GET['i'] : 0;

$directjobid = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : false;

if (strlen($keywords) > 0 || strlen($location) > 0) {
	$data = array('keywords'=>Config::get('db')->filter($keywords),'location'=>Config::get('db')->filter($location));
	Config::get('db')->insert('search_history',$data);
}
$sqlAdd = '';
$kwdMsg = '';
if (!$directjobid && strlen(trim($keywords)) == 0) { $sqlAdd = ' AND 2=1 '; $kwdMsg = '<h3>You must enter a keyword in order to search Jobs.</h3>'; }

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

$zipcode = extract_zipcode($location);
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

    $result->option('max_matches',2000000);

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
    foreach($dbData as $tweet) {
        $searchIds[] = $tweet['id'];
    }

/////////////////////////////////////////////////////////////////////////////////////////////
// GET FEATURED JOBS

    //get featured
    $fresult = SphinxQL::create($conn)->select('id','tweet','city','state','cid','job_industries','postDate')
        ->from('job_index');

    $fresult->option('max_matches',2000000);

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
    $searchIds[] = $directjobid;    
}

//////////////////////////////////////////////////////////////////////////////////////////
// GET INDUSTRIES

//get industry counts
$jcresult = SphinxQL::create($conn)->select('i_id','i_name',SphinxQL::expr('count(*) as i_count'))
    ->from('jobcat_index');

$jcresult->option('max_matches',2000000);

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

//industry condition 
if ($industry  && $industry > 0) {
	$jcresult = $jcresult->where('i_id',intval($industry));
}

$jcresult = $jcresult->where('jobid','>',intval(0));

$jcresult = $jcresult->orderBy('i_name','asc');

$jcresult = $jcresult->groupBy('i_id');


$jcresult = $jcresult->limit($startat,100)
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
	SELECT SQL_CALC_FOUND_ROWS *,job.id as jobId,'featured' as featured FROM `job`
	left join jobcat
	on jobcat.jobId=job.id
    left join campaign
    on campaign.id = job.campaignId

	where 
        job.id in (".implode(",",$featuredIds).")
        and     job.campaignId>0
	and job.status=1 and job.postDate > DATE_SUB(NOW(), INTERVAL 30 DAY)
	order by campaign.click_budget desc, postDate desc
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
	SELECT SQL_CALC_FOUND_ROWS *,job.id as jobId FROM `job`

	where 
        job.id in (".implode(",",$searchIds).") and job.postDate > DATE_SUB(NOW(), INTERVAL 30 DAY)
	
	order by postDate desc
	
";

//echo $query;

$dbData = count($searchIds) > 0 ? Config::get('db')->get_results($query) : array();

//var_dump($dbData);
//$query = "SELECT FOUND_ROWS() AS found_rows;";
//$countData = Config::get('db')->get_results($query);
$totalFeaturedRecords = min($fresult[1][1]['Value'],2000000);
$totalReturnedFeatured = count($fresult) > 1 ? count($fresult[0]) : 0;
$totalJobRecords = min($result[1][1]['Value'],2000000);


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
    job.urls NOT LIKE '%short.info%' and job.postDate > DATE_SUB(NOW(), INTERVAL 30 DAY)  and job.urls NOT LIKE '%indeed%' and job.urls NOT LIKE '%ziprecruiter%'
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
<title>TweetJobs | Job Tweet Search</title>
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
			<div class="page-logo" align="center">
				<a href="index.php"><img src="img/logo1.png" longdesc="http://www.jobalarm.com"></a>			</div>
			<!-- END LOGO -->
			<!-- BEGIN RESPONSIVE MENU TOGGLER -->
			
			<!-- END RESPONSIVE MENU TOGGLER -->
		</div>
	<div class="container font-md">
	  <div align="center">
	    
	  </div>
	</div>
	</div>
	<!-- END HEADER TOP -->
	<!-- BEGIN HEADER MENU -->
  
	<!-- END HEADER MENU -->
</div>
<!-- END HEADER -->
<!-- BEGIN PAGE CONTAINER -->
<div class="page-container">
	<!-- BEGIN PAGE HEAD -->
	
	<!-- END PAGE HEAD -->
	<!-- BEGIN PAGE CONTENT -->
	<div class="page-content">
		<div class="container">
			<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
			
			<!-- /.modal -->
			<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
			<!-- BEGIN PAGE BREADCRUMB -->
			<ul class="page-breadcrumb breadcrumb">
				<li>
					<a href="index.php">New Search</a><i class="fa fa-circle"></i>
				</li>
<!-- 				<li>
					<a href="page_todo.html">Pages</a>
					<i class="fa fa-circle"></i>
				</li> -->
				<li class="active">
					 Search Results
				</li>
			</ul>
	  </div>
			<!-- END PAGE BREADCRUMB -->
			<!-- BEGIN PAGE CONTENT INNER -->
			<div class="row">
				<div class="col-md-10">
					<!-- BEGIN TODO SIDEBAR -->
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
		$onClick = "tj.clickTrack(".$id.");window.open(this.href,'targetWindow','toolbar=no, location=no, status=no, menubar=no, scrollbars=yes,resizable=yes, width=920, height=600');return false;";
	}
    $listClass = (isset($tweet['featured'])) ? 'todo-tasklist-item todo-tasklist-item-border-red todo-tasklist-item-featured' : 'todo-tasklist-item todo-tasklist-item-border-green';
?>										
												<div class="<?php echo $listClass; ?>">
													<img class="todo-userpic pull-left" src="<?php echo (isset($user['profile_image_url'])) ? $user['profile_image_url'] : '';?>" width="27px" height="27px">
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
														<span class="todo-tasklist-date">Tweeted: <?php echo $tweetDate; ?> </span>
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
							
							</div>
						
						</div>
						
						
				  </div>
				  <div class="container">
			<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
			
			<!-- /.modal -->
			<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
			<!-- BEGIN PAGE BREADCRUMB -->
			<ul class="page-breadcrumb breadcrumb">
				<li>
					<a href="index.php">New Search</a><i class="fa fa-circle"></i>
				</li>
<!-- 				<li>
					<a href="page_todo.html">Pages</a>
					<i class="fa fa-circle"></i>
				</li> -->
				<li class="active">
					 Search Results
				</li>
			</ul>
	  </div>
				  
					<!-- END TODO CONTENT -->
			  </div>
			  
				<div>
					<div class="row">
						<div class="col-md-2 col-sm-2">
							<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- TJR -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-2545585330917467"
     data-ad-slot="5165292084"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>


						</div>
					</div>
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
tj.selectIndustry = function(id) {
	var base_url = window.location.href.split('?')[0];
	window.location = base_url+"?i="+id+'&k=<?php echo $keywords?>&l=<?php echo $location;?>' ;
}
if(isset($_GET["src"])){
tj.clickTrack = function(jobId) {
    $.ajax({
        url: 'data.php?cf=1',
        data: { jid: jobId },
        method: 'post',
        success: function (response) {

        }
	}
    }else{
	tj.clickTrack = function(jobId) {
    $.ajax({
        url: 'data.php?ct=1',
        data: { jid: jobId },
        method: 'post',
        success: function (response) {

        }
	}
	
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
