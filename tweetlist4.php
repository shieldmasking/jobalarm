<?php
include 'inc/class.db.php';
include 'inc/class.jatwitter.php';
include 'inc/config.php';
include 'inc/pagination.class.php';

require 'vendor/autoload.php';


use Foolz\SphinxQL\SphinxQL;
use Foolz\SphinxQL\Connection;


if (isset($_REQUEST['submitting_candidate'])) {
    $user = isset($_REQUEST['for_company']) ? $_REQUEST['for_company'] : '';
	$account = isset($_REQUEST['accountId']) ? $_REQUEST['accountId'] : '';
	$promos = isset($_REQUEST['promos']) ? $_REQUEST['promos'] : '';
    $job_type = isset($_REQUEST['job_type']) ? $_REQUEST['job_type'] : '';
    $first_name = isset($_REQUEST['contact_name']) ? $_REQUEST['contact_name'] : '';
    $last_name = isset($_REQUEST['contact_lastname']) ? $_REQUEST['contact_lastname'] : '';
    $mobile = isset($_REQUEST['contact_mobile']) ? $_REQUEST['contact_mobile'] : '';
    $email = isset($_REQUEST['contact_email']) ? $_REQUEST['contact_email'] : '';
    $zip_code = isset($_REQUEST['zipcode']) ? $_REQUEST['zipcode'] : '75024';
	$resume_paste = isset($_REQUEST['resume_paste']) ? $_REQUEST['resume_paste'] : '';
    $resume_filename = '';
    if ($_FILES['resume_file']) {
        $target_dir = "resumes/";
        $target_file = $target_dir . basename($_FILES["resume_file"]["name"]);
        $resume_filename = basename($_FILES["resume_file"]["name"]);
        move_uploaded_file($_FILES["resume_file"]["tmp_name"], $target_file);
    } 

    $insert_array = array(
        'account'=>$account,
		'promo'=>$promos,
        'job_type'=>$job_type,
        'first_name'=>$first_name,
        'last_name'=>$last_name,
        'mobile'=>$mobile,
        'email'=>$email,
        'zip'=>$zip_code,
        'resume'=>$resume_paste,
        'resume_file'=>$resume_filename
        );
    Config::get('db')->insert('candidate',$insert_array);

}

$keywords = (isset($_REQUEST['search_keyword'])) ? $_REQUEST['search_keyword'] : ((isset($_GET['k'])) ? $_GET['k'] : '');
$location = (isset($_REQUEST['search_location'])) ? $_REQUEST['search_location'] : ((isset($_GET['l'])) ? $_GET['l'] :'');
$industry = (isset($_GET['i'])) ? $_GET['i'] : 0;
$username = $_REQUEST["username"];


$referral = (isset($_REQUEST['cx'])) ? ",'txt'" : ",'txt'";

//$zipcode = extract_zipcode($location);
$zipcode = (isset($_REQUEST['zipcode'])) ? $_REQUEST['zipcode'] : 92802;

$zipsearch = '';

$locationName = 'All';

$dbCoord = Config::get('db') -> get_results("select latitude, longitude, city, state_code from cities_extended where zip={$zipcode}");
$lat=$dbCoord[0]["latitude"];
$lon=$dbCoord[0]["longitude"];
$locationName = $dbCoord[0]["city"];
$industryName = 'All';

if (isset($_REQUEST['username'])) {
$query = "
	SELECT SQL_CALC_FOUND_ROWS j.*, j.id as jobId 
	FROM job j
	  inner join cities_extended ce on ce.city = j.city and ce.state_code = j.state
	where 
        (3963*ACOS(SIN({$lat}/57.2958)*SIN(ce.latitude/57.2958)+ COS({$lat}/57.2958) *COS(ce.latitude/57.2958)*COS((ce.longitude/57.2958)-({$lon}/57.2958))))<=25 and j.status > 0 and j.userName LIKE '$username'  
	group by j.id
	order by j.postDate desc
";
}else{
$query = "
	SELECT SQL_CALC_FOUND_ROWS j.*, j.id as jobId 
	FROM job j
	  inner join cities_extended ce on ce.city = j.city and ce.state_code = j.state
	where 
        (3963*ACOS(SIN({$lat}/57.2958)*SIN(ce.latitude/57.2958)+ COS({$lat}/57.2958) *COS(ce.latitude/57.2958)*COS((ce.longitude/57.2958)-({$lon}/57.2958))))<=25 and j.status > 0 and j.text LIKE '%$keywords%'  
	group by j.id
	order by j.postDate desc
";

}



//echo $query;

$dbData = Config::get('db')->get_results($query);

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
	/*foreach($featuredData as $job) {
	    $jobsList[] = $job;
	    $jobsCounter++;
	 
	}
   */
}


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
			<div class="page-logo" align="center">
				<a href="index.php"><img src="img/logo1.png" longdesc="http://www.jobalarm.com"></a>			</div>
			<!-- END LOGO -->
			<!-- BEGIN RESPONSIVE MENU TOGGLER -->
			
			<!-- END RESPONSIVE MENU TOGGLER -->
		</div>
	<div class="container font-md">
	  <div align="center">
	    <p>&nbsp;</p>
	    <p><strong>Your information has been successfully saved and a we may be in contact via text or email when jobs open in your area.</strong></p>
        <p><strong>In the mean time, please scroll down for a list of current openings near you.</strong> </p>
	  </div>
	</div>
	</div>
	<!-- END HEADER TOP -->
	<!-- BEGIN HEADER MENU -->
  
	<!-- END HEADER MENU -->
</div>
<div align="center"> 
	  <button onClick="newSearch()"><span class="font-lg style1">Start New Search</span></button>
      
<p id="demo"></p>

<script>
function newSearch() {
    window.open("http://www.jobalarm.com");
}
</script>
</div>
<!-- END HEADER -->
<!-- BEGIN PAGE CONTAINER -->
<div class="page-container">
	<!-- BEGIN PAGE HEAD -->
	
	<!-- END PAGE HEAD -->
	<!-- BEGIN PAGE CONTENT -->
	<div class="page-content">
	
		 
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
							<div class="container">
			<div style="margin:5px 0;text-align:center">
			<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
			<!-- TJ2 -->
			<ins class="adsbygoogle"
			     style="display:inline-block;width:368px;height:60px"
			     data-ad-client="ca-pub-2545585330917467"
			     data-ad-slot="3881560883"></ins>
			<script>
			(adsbygoogle = window.adsbygoogle || []).push({});
			</script>
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
		$onClick = "tj.clickTrack(".$id.$referral.");window.open(this.href,'targetWindow','toolbar=no, location=no, status=no, menubar=no, scrollbars=yes,resizable=yes, width=920, height=600');return false;";
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
