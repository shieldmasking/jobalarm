<?php  ini_set('display_errors', 1);
session_start();
include 'inc/class.db.php';
include 'inc/class.jatwitter.php';
include 'inc/config.php';

if (isset($_SESSION['account'])) {
    $account_data = $_SESSION['account'];
}
else if(isset($_SESSION['sms_stores'])) {
	$account_data = $_SESSION['sms_stores'];
	}
	else if(isset($_SESSION['profile'])) {
	$account_data = $_SESSION['profile'];
	}else {
	header('location: index.php');
	}
	
	//echo json_encode($account_data);


function debug_to_console( $data ) {

    if ( is_array( $data ) )
        $output = "<script>console.log( 'Debug array " . implode( ',', $data) . "' );</script>";
    else
        $output = "<script>console.log( 'Debug not array: " . $data . "' );</script>";

    echo $output;
}
if (isset($_GET['fb'])) {
    $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 0;
	$limit = 20;
	//$start = $page * $limit;
	$start =  $_REQUEST['start'];
	$user = $account_data['id'];
	$role = $account_data['role'];
	//$twitter_handle = $account_data['twitter_handle'];
	//$userData = Config::get('db') -> get_results("SELECT c.*, x.promo FROM `candidate` c LEFT JOIN `candidateXref` as x on x.candidateId=c.id WHERE x.candidateId=$candidate and x.promo>0 ORDER BY x.promo DESC");
	//$query = "SELECT  j.*,(c.facebook + c.jobalarm + c.text) as clickCount FROM `job` as j LEFT JOIN `clicks` as c on c.jobId = j.id WHERE userName ='{$twitter_handle}' and status > 0 and j.twitterId !='' and campaignId > 0 ORDER BY id DESC LIMIT 0,500";
	if (intval($user)==60){
		$searchAdd = " and (j.title like \"store manager in training\" or j.title like \"%call center%\") ";
	}else{
		$searchAdd = " and j.zipCode in (select s.zip from `sms_stores` s left join `assign_store` as a on a.storeId = s.id where a.userId={$user})";
	}
	
	
	$query = "SELECT  j.*,(c.facebook + c.jobalarm + c.text) as clickCount FROM `job` as j 
LEFT JOIN `clicks` as c on c.trackId = j.twitterId 
WHERE j.brand in (select `brandId` from `assign_brand` where `userId`={$user})
	{$searchAdd}
ORDER BY id DESC
LIMIT 0,1000";
	
	$dbData = Config::get('db') -> get_results($query);
// query clicks table as leftjoin clicks on jobId and facebook with job query response
  //$fbJobClickCount    = get_results;
	$query = "SELECT FOUND_ROWS() AS found_rows;";
	$countData = Config::get('db') -> get_results($query);
	$total = $countData[0]['found_rows'];
	$outJobs = array();
	$jobArray = array();
	$jobArrayTemp = array();
	$return = "";
	$return .= "<a class=\"btn btn-sm green\" >Join <i class=\"fa fa-gears\"></i></a>";
	$return .= "<a class=\"btn btn-sm yellow\" >Pending <i class=\"fa fa-gears\"></i></a>";
	$return .= "<a class=\"btn btn-sm purple\" >Post <i class=\"fa fa-gears\"></i></a>";
	$return .= "<a class=\"btn btn-sm blue\" >Posted <i class=\"fa fa-gears\"></i></a>";
	$return .= "<a class=\"btn btn-sm red\" >Bump <i class=\"fa fa-gears\"></i></a>";
	$return .= "<a class=\"btn btn-sm red\" >Bumped <i class=\"fa fa-gears\"></i></a>";

	foreach ($dbData as $job) {
		$jobArrayTemp = array();
		$job['rawData'] = stripslashes(stripslashes(stripslashes($job['rawData'])));
		$job['rawData'] = json_decode(strip_tags($job['rawData']), true);
		$job['urls'] = stripslashes($job['urls']);
		//$time = strtotime('-6 hours', strtotime($job['postDate']));
		$time = strtotime($job['postDate']);
		$job['postDate'] = date("m/d/y g:i A", $time);
//    $job['bumpDate'] = date("m/d/y g:i A", $time + (60*60*24*7));
//    $fbPostDate = date('Y-m-d H:i:s',strtotime(-6 $date));

		$urls = explode(',', $job['urls']);
		$jobUrl = 'javascript:;';
		$onClick = '';
		$job['onclick'] = '';
		$twitterprofilelink = (isset($user['screen_name'])) ? 'https://twitter.com/intent/follow?screen_name=' . $job['userName'] : 'https://twitter.com';
		if (count($urls) > 0) {            $job['url'] = $urls[0];
			$onClick = "window.open(". $job['url']  .",'targetWindow','toolbar=no, location=no, status=no, menubar=no, scrollbars=yes,resizable=yes, width=800, height=600');return false;";
			$job['onclick'] = $onClick;
		}         $jobArrayTemp[] = $job['urls'];
		$jobArrayTemp[] = $job['postDate'];
		$jobArrayTemp[] = "<a href=\" " . $job['url'] . "\" target=\"_blank\">" . "" . $job['text'] . "</a>";

		$jobArrayTemp[] = "" . $job["clickCount"];  // numClicks moved to clicks table
		//$jobArrayTemp[] = "" . $query["clicks"];

		$manageGroup = "";
		$manageGroup .= "<a class=\"btn btn-sm blue\" onclick=\"tj.alex.manageGroups(" . $job['id'] . ");\"><i class=\"fa fa-facebook\"> Groups</i></a>";
		//<a class=\"btn btn-sm green\" onclick=\"tj.repost(".$job['id'].");\"><i class=\"fa fa-gears\"> Re-Post</i></a><a class=\"btn btn-sm red\" onclick=\"tj.closejob(".$job['id'].");\"><i class=\"fa fa-gears\"> Close</i></a>";
		
		$jobArrayTemp[] = $manageGroup;
		$jobArrayTemp[] = $job['userName'];
		$outJobs[] = $job;
		$jobArray[] = $jobArrayTemp;
	}    $outData = array();
	$outData['sucess'] = true;
	$outData['total'] = $total;
	$outData['records'] = $outJobs;
	$outData['data'] = $jobArray;
	$outData['recordsTotal'] = $total;
	$outData['recordsFiltered'] = $total;
	$outData['draw'] = 10;
	$outData['start'] = $start;
	$outData['twitHandle1'] = $twitter_handle;
	echo json_encode($outData);
	exit();
}

//Account Grid
if (isset($_GET['ma'])) {
    $candidate = $account_data['id'];
	//$candidateData = Config::get('db') -> get_results("SELECT c.*, x.promo, x.brandId, x.brandOrig FROM `candidate` c LEFT JOIN `candidateXref` as x on x.candidateId=c.id WHERE x.candidateId=$candidate and x.promo>0 ORDER BY x.promo DESC");
	//$cpromo = $candidateData[0]['promo'];
	//echo $cpromo;
	//if ($cpromo) {	
	
	$dbData = Config::get('db') -> get_results("select x.*, s.storeBrand, s.id as sid, s.storeImage as storeImage FROM `candidateXref` as x LEFT JOIN `sms_brand` AS s on s.id = x.brandId where x.candidateId ={$candidate} GROUP BY x.brandOrig ORDER BY s.storeBrand ASC");
	if ($dbData) {
	$query = "SELECT FOUND_ROWS() AS found_rows;";
	$countData = Config::get('db') -> get_results($query);
	$total = $countData[0]['found_rows'];
	$cpromo = $dbData[0]['promo'];
	$mpromo = $dbData[0]['promoMktng'];
	$outJobs = array();
	$jobArray = array();
	$jobArrayTemp = array();
	
	if (intval($cpromo) !=2) {
	
	foreach ($dbData as $job) {
		$jobArrayTemp = array();
		$brandOrig = $job['brandOrig'];
		$time = strtotime($job['subscribeDate']);
		$job['subscribeDate'] = date("m/d/y", $time);
		
		$storeImage = "";
		$storeImage .= "<img src=\"../img\/" . $job['storeImage'] ."\" width=\"70\">";
		
		$jobArrayTemp[] = $storeImage;
		$jobArrayTemp[] = $job['subscribeDate'];
		if($job['promo'] >0 && $job['promoMktng']==0) {
		$promo = "Jobs Only";
		}else if($job['promo'] >0 && $job['promoMktng']>0){
		$promo = "Jobs & Promotions";
		}else if($job['promo'] == 0 && $job['promoMktng']>0){
		$promo = "Promotions Only";
		}else{
		$promo = "Opted Out";
		}
		
		$jobArrayTemp[] = $promo; 
		//$jobArrayTemp[] = $job["msgCount"];  

		$manageGroup = "";
		//$manageGroup = "<a class=\"btn btn-sm blue\" data-id=\"" . $job['sid'] . "\" data-toggle=\"modal\" data-target=\"#editSMS\">Edit </a>";
		$manageGroup = "<a class=\"btn btn-sm blue\" onclick=\"tj.removeSMS(" . $job['sid'] . ");\" >Remove </a>";

		//$manageGroup .= "<a class=\"btn btn-sm blue\" onclick=\"tj.editSubscription(" . $job['id'] . ");\");\">Edit <i class=\"fa fa-gears\"></i></a>";
		//$manageGroup = "<a href=\"#editSMS\" id=\"" . $job['sid'] . "\" data-toggle=\"modal\">Edit</a>";

		$jobArrayTemp[] = $manageGroup;
		$jobArrayTemp[] = $brandOrig;
		$outJobs[] = $job;
		$jobArray[] = $jobArrayTemp;
	}    $outData = array();
	$outData['success'] = true;
	$outData['data'] = $jobArray;
	echo json_encode($outData);
	exit();
	}else{
	$jobArrayTemp = array();
				
			$storeImage = "";
			$storeImage = "<img src=\"img\/logo1.png\" width=\"70\">";
			
			$subscribe = "";
			$about = "You are currently subscribed to Jobs and Promotions from all Employers";
			$jobArrayTemp[] = $storeImage;
			$jobArrayTemp[] = $about;
			$jobArrayTemp[] = $subscribe;
			$manageGroup = "";
			$manageGroup = "<a class=\"btn btn-sm blue\" data-id=\"" . $job['sid'] . "\" onclick=\"tj.removeSMS(" . $job['id'] . ");\" >Remove </a>";

			//$manageGroup = "<a class=\"btn btn-sm blue\" onclick=\"tj.editSubscription(" . $job['id'] . ");\");\">Edit <i class=\"fa fa-gears\"></i></a>";
			$jobArrayTemp[] = $manageGroup;
			$jobArray[] = $jobArrayTemp;
			$outData = array();
			$outData['success'] = true;
			$outData['data'] = $jobArray;
			echo json_encode($outData);
			exit();		
		}
	}
}

////////////////////////////////////////////
/////////////Find Companies

if (isset($_GET['fc'])) {

	$findco = isset($_GET['fc']) ? $_GET['fc'] : false;
	$userId = $account_data['accountId'];
	$joinStatus = "9";
	$zip = $account_data['zip'];
	$candidate = $account_data['id'];
	$candidateData = Config::get('db') -> get_results("SELECT c.*, x.promo FROM `candidate` c LEFT JOIN `candidateXref` as x on x.candidateId=c.id WHERE x.candidateId=$candidate and x.promo>0 ORDER BY x.promo DESC");
	$promo = $candidateData[0]['promo'];
	
	if ($promo) {
	$outJobs = array();
	$jobArray = array();
	$jobArrayTemp = array();
	//echo $promo;
  
	if (intval($promo) !=2) {
	
	$query = "SELECT s.* FROM `sms_brand` s LEFT JOIN `candidateXref` as x on x.brandId !=s.id where s.active=1 and x.candidateId={$candidate} and s.id !=6 and s.id !=19 and s.active=1 group by s.id order by s.storeBrand ASC";
	$dbCompanyData = Config::get('db') -> get_results($query);

	$query = "SELECT FOUND_ROWS() AS found_rows;";
	$countData = Config::get('db') -> get_results($query);
	$total = $countData[0]['found_rows'];
	
		foreach ($dbCompanyData as $group) {
			$jobArrayTemp = array();
				
			$storeImage = "";
			$storeImage .= "<img src=\"../img\/" . $group['storeImage'] ."\" width=\"70\">";
			
			$subscribe = "";
			$subscribe .= "Text " . $group['keyword'] . " to 58046"; 
			
			//$subscribe = "<a class=\"btn btn-sm green\" id=\"addSMS\" data-toggle=\"modal\" data-target=\"#editSMS\">Add </a>";
			
			//$subscribe = "<a class=\"btn btn-sm green\" href=\"#editSMS\" data-id=\"".$group['id']."\" data-toggle=\"modal\" data-target=\"#editSMS\">Add</a>";

			$about = $group['responseMsg'];
			$jobArrayTemp[] = $storeImage;
			$jobArrayTemp[] = $about;
			$jobArrayTemp[] = $subscribe;
			$outJobs[] = $group;
			$jobArray[] = $jobArrayTemp;
			} 
			$outData = array();
			$outData['success'] = true;
			$outData['data'] = $jobArray;
			echo json_encode($outData);
			exit();
			}
			else{
			$jobArrayTemp = array();
				
			$storeImage = "";
			$storeImage .= "<img src=\"img\/logo1.png\" width=\"70\">";
			
			$subscribe = "";
			$about = "You are currently subscribed to Jobs and Promotions from all Employers";
			$jobArrayTemp[] = $storeImage;
			$jobArrayTemp[] = $about;
			$jobArrayTemp[] = $subscribe;
			$outJobs[] = $group;
			$jobArray[] = $jobArrayTemp;
			$outData = array();
			$outData['success'] = true;
			$outData['data'] = $jobArray;
			echo json_encode($outData);
			exit();		
		}
	}
}

if (isset($_GET['got'])) {

	$jobId = isset($_GET['got']) ? $_GET['got'] : false;
	$radius = isset($_REQUEST['radius']) ? $_REQUEST['radius'] : 25;
 	
	if (isset($_SESSION['account'])) {
    	$userId = $account_data['accountId'];
		}else{
	 exit();
	}
 
  $bumptime = strtotime('{$fbBumpDate}');
  $bumptime > time()?$showBump = 1:'';

	if ($jobId) {
		$dbData = Config::get('db') -> get_results("select c.latitude, c.longitude, j.* from job j inner join (select c.city, c.state_code, max(c.zip), c.latitude, c.longitude from cities_extended c group by c.city, c.state_code ) c on c.city = j.city and c.state_code = j.state where id={$jobId}");
		
		$lat = $dbData[0]["latitude"];
		$lon = $dbData[0]["longitude"];
		$zip = $dbData[0]['zipCode'];
		
		if ($zip){
		$zipOrig = substr($zip,0,1);
		
		if (intval($zipOrig) > 0){
		$zipLow = intval($zipOrig)-1;
		}else{
			$zipLow = $zipOrig;
		}
		
		$zipHigh = intval($zipOrig)+1;
		}
      
$now = time(); //echo "now ".$now."<br><br>";
$nowDate = date('Y-m-d H:m:s', $now);//echo "now date ".$fbPostDate."<br><br>";
$bumpDate = $now +60*60*24*7;//echo "bumpDate ".$bumpDate."<br><br>";
$fbBumpDate = date('Y-m-d H:m:s', $bumpDate);
$dbGroupData = Config::get('db') -> get_results("select jd.*, ce.zip,
case
   when x.status = 1 and jp.fbBumpDate > '$nowDate' and jp.fbFirstPostDate = jp.fbPostDate then 6
   when x.status = 1 and jp.fbFirstPostDate > jp.fbPostDate then 8
   when x.status = 1 and jp.fbBumpDate < '$nowDate' and jp.fbFirstPostDate = jp.fbPostDate then 7
   else IFNULL( x.status,0)
end
 as joinStatus,jg.member_count as member_count from job_groups jd inner join cities_extended ce on ce.city = jd.group_city and ce.state_code = jd.group_state left join accountGroupXref x on x.fbGroupId = jd.group_fb_id and x.accountId = $userId left join job_group_members jg on jg.group_fb_id = jd.group_fb_id left join job_groups_postings jp on jp.fbGroupId = x.fbGroupId and jp.jobId = {$jobId} where (3963*ACOS(SIN({$lat}/57.2958)*SIN(ce.latitude/57.2958)+ COS({$lat}/57.2958) *COS(ce.latitude/57.2958)*COS((ce.longitude/57.2958)-({$lon}/57.2958))))<={$radius} group by jd.group_id");

		if (count($dbData) > 0) {
			$tweet = $dbData[0];
			$tweet["urls"] = "http://www.jobalarm.com/ja.php?cf=1&id=".$jobId;
			$tempText =  $tweet["text"];
			$pos = strrpos($tempText, "http");
			if($pos){
				$tempText = substr($tempText, 0, $pos);
				$tempText = $tempText." http://www.jobalarm.com/ja.php?cf=1&id=".$jobId;
			}else{
				$pos = strrpos($tempText, "www");
				if($pos){
				$tempText = substr($tempText, 0, $pos);
				$tempText = $tempText." http://www.jobalarm.com/ja.php?cf=1&id=".$jobId;
				}else{
				$tempText = $tempText." http://www.jobalarm.com/ja.php?cf=1&id=".$jobId;
				}
			}
			$tweet["text"] = $tempText;
			$index = 0;
			if (count($dbGroupData) > 0) {
				$tweetData .= "{\"data\": [";
				foreach ($dbGroupData as $group) {
				
				//add url to group name
				$tweetData .= '{"groupName": "'.$group['group_description'].'", "groupMembers": "'.$group['member_count'].'", "groupCount": "'.$dbGroupData2['job_count'].'", "joinStatus": "'.$group['joinStatus'] . '", "fbGroupId": "'.$group['group_fb_id']. '"},';

				//$tweetData .= "{\"groupName\": \"$group[group_description]\", \"groupMembers\": \"$group[member_count]\", \"joinStatus\": " . $group[joinStatus] . ", \"fbGroupId\": " .$group[group_fb_id]. "},";
				//$tweetData .= "{\"groupName\": \"$group[group_description]\", \"joinStatus\": " . $group[joinStatus] . ", \"fbGroupId\": " .$group[group_fb_id]. "},";
					$index++;
				}
				$tweetData = rtrim($tweetData, ",");
				$tweetData .= "],";
			} else {
				$tweetData = "{\"data\": [],";
			}
			$tweetData .= "\"tweet\": " . json_encode($tweet);
			$tweetData .= "}";
			echo $tweetData;
		} else {
		       echo "Job Not Found. count not greater than 0";
		}
	} else {
	   echo "Job Not Found. got not set 11";
	}
    exit();
}

////////////////////////////////////////////
/////////////Added Groups

if (isset($_GET['ag'])) {

	$jobId = isset($_GET['ag']) ? $_GET['ag'] : false;
	$userId = $account_data['accountId'];
   $bumptime = strtotime('{$fbBumpDate}');
   $bumptime > time()?$showBump = 1:'';

if ($jobId) {
		
$now = time(); //echo "now ".$now."<br><br>";
$nowDate = date('Y-m-d H:m:s', $now);//echo "now date ".$fbPostDate."<br><br>";
$bumpDate = $now +60*60*24*7;//echo "bumpDate ".$bumpDate."<br><br>";
$fbBumpDate = date('Y-m-d H:m:s', $bumpDate);
$dbGroupData = Config::get('db') -> get_results("select jd.*,
case
   when x.status = 1 and jp.fbBumpDate > '$nowDate' and jp.fbFirstPostDate = jp.fbPostDate and jp.jobid = '$jobId' then 6
   when x.status = 1 and jp.fbFirstPostDate > jp.fbPostDate and jp.jobid = '$jobId' then 8
   when x.status = 1 and jp.fbBumpDate < '$nowDate' and jp.fbFirstPostDate = jp.fbPostDate and jp.jobid = '$jobId' then 7
   else IFNULL( x.status,0)
end
 as joinStatus,jg.member_count as member_count from job_groups jd left join accountGroupXref x on x.fbGroupId = jd.group_fb_id and x.accountId = $userId left join job_group_members jg on jg.group_fb_id = jd.group_fb_id left join job_groups_postings jp on jp.fbGroupId = x.fbGroupId left join job_groups_add ja on ja.group_fb_id = jd.group_fb_id where ja.jobid = {$jobId} group by jd.group_fb_id");
// debug_to_console('select fbPermaLink from job_groups_postings where jobId = {$jobId}');


// debug_to_console($dbGroupData );

		$index = 0;
			if (count($dbGroupData) > 0) {
				$tweetData .= "{\"data\": [";
				foreach ($dbGroupData as $group) {
				
				//add url to group name
				$tweetData .= '{"groupName": "'.$group['group_description'].'", "groupMembers": "'.$group['member_count'].'", "joinStatus": "'.$group['joinStatus'] . '", "fbGroupId": "'.$group['group_fb_id']. '"},';

				
				$index++;
				}
				$tweetData = rtrim($tweetData, ",");
				$tweetData .= "],";
			} else {
				$tweetData = "{\"data\": [],";
			}
			$tweetData .= "\"tweet\": " . json_encode($tweet);
			$tweetData .= "}";
			echo $tweetData;
		} else {
		       echo "Group Not Found. count not greater than 0";
		}
    exit();
}
////////////////////////////////////////////
/////////////Pending Joins

if (isset($_GET['pj'])) {

	$jobId = isset($_GET['pj']) ? $_GET['pj'] : false;
	$userId = $account_data['accountId'];
   $bumptime = strtotime('{$fbBumpDate}');
   $bumptime > time()?$showBump = 1:'';

if ($jobId) {
		
$now = time(); //echo "now ".$now."<br><br>";
$nowDate = date('Y-m-d H:m:s', $now);//echo "now date ".$fbPostDate."<br><br>";
$bumpDate = $now +60*60*24*7;//echo "bumpDate ".$bumpDate."<br><br>";
$fbBumpDate = date('Y-m-d H:m:s', $bumpDate);
$dbGroupData = Config::get('db') -> get_results("select jd.*,
case
   when x.status = 1 and jp.fbBumpDate > '$nowDate' and jp.fbFirstPostDate = jp.fbPostDate and jp.jobid = '$jobId' then 6
   when x.status = 1 and jp.fbFirstPostDate > jp.fbPostDate and jp.jobid = '$jobId' then 8
   when x.status = 1 and jp.fbBumpDate < '$nowDate' and jp.fbFirstPostDate = jp.fbPostDate and jp.jobid = '$jobId' then 7
   else IFNULL( x.status,0)
end
 as joinStatus,jg.member_count as member_count from job_groups jd left join accountGroupXref x on x.fbGroupId = jd.group_fb_id and x.accountId = $userId left join job_group_members jg on jg.group_fb_id = jd.group_fb_id left join job_groups_postings jp on jp.fbGroupId = x.fbGroupId left join job_groups_add ja on ja.group_fb_id = jd.group_fb_id where x.status = 2 group by jd.group_fb_id");
// debug_to_console('select fbPermaLink from job_groups_postings where jobId = {$jobId}');


// debug_to_console($dbGroupData );

		$index = 0;
			if (count($dbGroupData) > 0) {
				$tweetData .= "{\"data\": [";
				foreach ($dbGroupData as $group) {
				
				//add url to group name
				$tweetData .= '{"groupName": "'.$group['group_description'].'", "groupMembers": "'.$group['member_count'].'", "joinStatus": "'.$group['joinStatus'] . '", "fbGroupId": "'.$group['group_fb_id']. '"},';

				
				$index++;
				}
				$tweetData = rtrim($tweetData, ",");
				$tweetData .= "],";
			} else {
				$tweetData = "{\"data\": [],";
			}
			$tweetData .= "\"tweet\": " . json_encode($tweet);
			$tweetData .= "}";
			echo $tweetData;
		} else {
		       echo "Group Not Found. count not greater than 0";
		}
    exit();
}
////////////////////////////////////////////
/////////////Jobs to Post

if (isset($_GET['jp'])) {

	$jobId = isset($_GET['jp']) ? $_GET['jp'] : false;
	$userId = $account_data['accountId'];
   $bumptime = strtotime('{$fbBumpDate}');
   $bumptime > time()?$showBump = 1:'';

if ($jobId) {
		
$now = time(); //echo "now ".$now."<br><br>";
$nowDate = date('Y-m-d H:m:s', $now);//echo "now date ".$fbPostDate."<br><br>";
$bumpDate = $now +60*60*24*7;//echo "bumpDate ".$bumpDate."<br><br>";
$fbBumpDate = date('Y-m-d H:m:s', $bumpDate);
$dbGroupData = Config::get('db') -> get_results("select jd.*,
case
   when x.status = 1 and jp.fbBumpDate > '$nowDate' and jp.fbFirstPostDate = jp.fbPostDate and jp.jobid = '$jobId' then 6
   when x.status = 1 and jp.fbFirstPostDate > jp.fbPostDate and jp.jobid = '$jobId' then 8
   when x.status = 1 and jp.fbBumpDate < '$nowDate' and jp.fbFirstPostDate = jp.fbPostDate and jp.jobid = '$jobId' then 7
   else IFNULL( x.status,0)
end
 as joinStatus,jg.member_count as member_count from job_groups jd left join accountGroupXref x on x.fbGroupId = jd.group_fb_id and x.accountId = $userId left join job_group_members jg on jg.group_fb_id = jd.group_fb_id left join job_groups_postings jp on jp.fbGroupId = x.fbGroupId left join job_groups_add ja on ja.group_fb_id = jd.group_fb_id where x.status = 1 group by {$jobId}");
// debug_to_console('select fbPermaLink from job_groups_postings where jobId = {$jobId}');


// debug_to_console($dbGroupData );

		$index = 0;
			if (count($dbGroupData) > 0) {
				$tweetData .= "{\"data\": [";
				foreach ($dbGroupData as $group) {
				
				//add url to group name
				$tweetData .= '{"groupName": "'.$group['group_description'].'", "groupMembers": "'.$group['member_count'].'", "joinStatus": "'.$group['joinStatus'] . '", "fbGroupId": "'.$group['group_fb_id']. '"},';

				
				$index++;
				}
				$tweetData = rtrim($tweetData, ",");
				$tweetData .= "],";
			} else {
				$tweetData = "{\"data\": [],";
			}
			$tweetData .= "\"tweet\": " . json_encode($tweet);
			$tweetData .= "}";
			echo $tweetData;
		} else {
		       echo "Group Not Found. count not greater than 0";
		}
    exit();
}
////////////////////////////////////////////
/////////////Posted Jobs

if (isset($_GET['poj'])) {

	$jobId = isset($_GET['poj']) ? $_GET['poj'] : false;
	$userId = $account_data['accountId'];
   $bumptime = strtotime('{$fbBumpDate}');
   $bumptime > time()?$showBump = 1:'';

if ($jobId) {
		
$now = time(); //echo "now ".$now."<br><br>";
$nowDate = date('Y-m-d H:m:s', $now);//echo "now date ".$fbPostDate."<br><br>";
$bumpDate = $now +60*60*24*7;//echo "bumpDate ".$bumpDate."<br><br>";
$fbBumpDate = date('Y-m-d H:m:s', $bumpDate);
$dbGroupData = Config::get('db') -> get_results("select jd.*,
case
   when x.status = 1 and jp.fbBumpDate > '$nowDate' and jp.fbFirstPostDate = jp.fbPostDate and jp.jobid = '$jobId' then 6
   when x.status = 1 and jp.fbFirstPostDate > jp.fbPostDate and jp.jobid = '$jobId' then 8
   when x.status = 1 and jp.fbBumpDate < '$nowDate' and jp.fbFirstPostDate = jp.fbPostDate and jp.jobid = '$jobId' then 7
   else IFNULL( x.status,0)
end
 as joinStatus,jg.member_count as member_count from job_groups jd left join accountGroupXref x on x.fbGroupId = jd.group_fb_id and x.accountId = $userId left join job_group_members jg on jg.group_fb_id = jd.group_fb_id left join job_groups_postings jp on jp.fbGroupId = x.fbGroupId left join job_groups_add ja on ja.group_fb_id = jd.group_fb_id where x.status = 7 OR x.status = 8 group by {$jobId}");
// debug_to_console('select fbPermaLink from job_groups_postings where jobId = {$jobId}');


// debug_to_console($dbGroupData );

		$index = 0;
			if (count($dbGroupData) > 0) {
				$tweetData .= "{\"data\": [";
				foreach ($dbGroupData as $group) {
				
				//add url to group name
				$tweetData .= '{"groupName": "'.$group['group_description'].'", "groupMembers": "'.$group['member_count'].'", "joinStatus": "'.$group['joinStatus'] . '", "fbGroupId": "'.$group['group_fb_id']. '"},';

				
				$index++;
				}
				$tweetData = rtrim($tweetData, ",");
				$tweetData .= "],";
			} else {
				$tweetData = "{\"data\": [],";
			}
			$tweetData .= "\"tweet\": " . json_encode($tweet);
			$tweetData .= "}";
			echo $tweetData;
		} else {
		       echo "Group Not Found. count not greater than 0";
		}
    exit();
}


////////////////////////////////////////////
/////////////Find Groups

if (isset($_GET['fg'])) {

	$jobId = isset($_GET['fg']) ? $_GET['fg'] : false;
	$userId = $account_data['accountId'];
	$joinStatus = "9";
   
if ($jobId) {
		
$dbGroupData = Config::get('db') -> get_results("
	select 
	jd.*,
	jg.member_count as member_count 
	from job_groups jd 
	left join job_group_members jg on jg.group_fb_id = jd.group_fb_id 
	left join job_groups_add ja on ja.group_fb_id = jd.group_fb_id 
	where jd.group_fb_id not in (select group_fb_id from job_groups_add where jobid={$jobId})
	group by jd.group_fb_id
");
// debug_to_console('select fbPermaLink from job_groups_postings where jobId = {$jobId}');


// debug_to_console($dbGroupData );

		$index = 0;
			if (count($dbGroupData) > 0) {
				$tweetData .= "{\"data\": [";
				foreach ($dbGroupData as $group) {
				
				//add url to group name
				$tweetData .= '{"groupName": "'.str_replace('"','\"',$group['group_description']).'", "groupMembers": "'.$group['member_count'].'", "joinStatus": "'.$joinStatus. '", "fbGroupId": "'.$group['group_fb_id']. '"},';
								
				$index++;
				}
				$tweetData = rtrim($tweetData, ",");
				$tweetData .= "],";
			} else {
				$tweetData = "{\"data\": [],";
			}
			$tweetData .= "\"tweet\": " . json_encode($tweet);
			$tweetData .= "}";
			echo $tweetData;
		} else {
		       echo "Group Not Found. count not greater than 0";
		}
    exit();
}



////////////////////////////////////////////////
//////// add Group
if (isset($_GET['addGroup'])) {
	$groupid = isset($_REQUEST['fbGroupId']) ? $_REQUEST['fbGroupId'] : false;
	$jobid = isset($_REQUEST['jobId']) ? $_REQUEST['jobId'] : false;
	
	if (strlen($groupid) > 0 && strlen($jobid) > 0) {
	$data = array(
		'group_fb_id'=>Config::get('db')->filter($groupid),
		'jobid'=>Config::get('db')->filter($jobid)
		);
	Config::get('db')->insert('job_groups_add',$data);
		if (!$dbData) {
             echo "Group Added";
         } else {
             echo "Group Add Failed";
         }

	}else {
         echo "Group Add Failed";
     }
     exit();


}
/////////////////////////////////////////////
/////////////Insert Candidate
if (isset($_REQUEST['sc'])) {
    $first_name = isset($_REQUEST['first_name']) ? $_REQUEST['first_name'] : '';
    $last_name = isset($_REQUEST['last_name']) ? $_REQUEST['last_name'] : '';
    $mobile = isset($_REQUEST['mobile']) ? $_REQUEST['mobile'] : '';
    $email = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
    $zip_code = isset($_REQUEST['zipcode']) ? $_REQUEST['zipcode'] : '';
    $resume_paste = isset($_REQUEST['resume_paste']) ? $_REQUEST['resume_paste'] : '';
    $resume_filename = '';
    if ($_FILES['resume_file']) {
        $target_dir = "resumes/";
        $target_file = $target_dir . basename($_FILES["resume_file"]["name"]);
        $resume_filename = basename($_FILES["resume_file"]["name"]);
        move_uploaded_file($_FILES["resume_file"]["tmp_name"], $target_file);
    } 

    $insert_array = array(
        'first_name'=>$first_name,
        'last_name'=>$last_name,
        'mobile'=>$mobile,
        'email'=>$email,
        'zip'=>$zip_code,
        'resume'=>$resume_paste,
        'resume_file'=>$resume_filename
        );
    Config::get('db')->insert('candidate',$insert_array);
    echo json_encode(array('success'=>'true'));
exit();
} 
/////////////////////////////////////////////
/////////////Add Image
if (isset($_REQUEST['ai'])) {
    $user_id = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : '';
    $default = isset($_REQUEST['default']) ? $_REQUEST['default'] : '';
    $image_filename = '';
    if ($_FILES['image_file']) {
        $target_dir = "img/post_images/";
        $target_file = $target_dir . basename($_FILES["image_file"]["name"]);
        $image_filename = basename($_FILES["image_file"]["name"]);
        move_uploaded_file($_FILES["imager_file"]["tmp_name"], $target_file);
    } 

    $insert_array = array(
        'user_id'=>$user_id,
        'default'=>$default,
        'image_file'=>$image_filename
        );
    Config::get('db')->insert('images',$insert_array);
exit();
} 

/////////////////////////////////////////////
/////////////Save Search
if (isset($_GET['ss'])) {
	$emailaddress = isset($_REQUEST['email']) ? $_REQUEST['email'] : false;
	$keywords = isset($_REQUEST['keywords']) ? $_REQUEST['keywords'] : false;
	$location = isset($_REQUEST['location']) ? $_REQUEST['location'] : false;
	$industry = isset($_REQUEST['industry']) ? $_REQUEST['industry'] : false;

	if (strlen($emailaddress) > 0) {
	$data = array(
		'email'=>Config::get('db')->filter($emailaddress),
		'industry'=>Config::get('db')->filter($industry),
		'keywords'=>Config::get('db')->filter($keywords),
		'location'=>Config::get('db')->filter($location)
		);
	Config::get('db')->insert('saved_searches',$data);
		if (!$dbData) {
             echo "Search Saved";
         } else {
             echo "Save Search Failed";
         }

	}else {
         echo "Save Search Failed";
     }
     exit();


}

////////////////////////////////////////////////
//////// Archive Tweet
if (isset($_GET['archiveTweet'])) {
     $jobId = isset($_REQUEST['jobId']) ? $_REQUEST['jobId'] : false;
     if ($jobId) {
         $dbData = Config::get('db')->query("update job set status = 0 where id={$jobId}");
     } else {
         echo "Job Not Found.";
     }
     exit();
}

////////////////////////////////////////////////
//////// Request Pin
if (isset($_GET['rp'])) {
     $mobile = isset($_REQUEST['mobile']) ? $_REQUEST['mobile'] : false;
     if ($mobile) {
    $result = '';
    for($i = 0; $i < $length; $i++) {
    $result .= mt_rand(0, 9);
    }
	 $dbData = Config::get('db')->query("update candidate set pin = ".md5($result)." where mobile={$mobile}");
     } else {
         echo "Mobile Number Not Found.";
     }
     exit();
}

////////////////////////////////////////////////
//////// Edit Subscription
if (isset($_GET['es'])) {
     $brand = isset($_REQUEST['brand']) ? $_REQUEST['brand'] : false;
	 $accountId = $account_data['accountId'];
    
	 if ($brand) {
         $dbData = Config::get('db')->query("update candidateXref set promo = 0 where id={$accountId} and storeId = {$brand}");
		 
     } else {
         echo "Edit no completed.";
     }
     exit();
}

////////////////////////////////////////////////
//////// Close Job
if (isset($_GET['cj'])) {
     $jobId = isset($_REQUEST['jobId']) ? $_REQUEST['jobId'] : false;
     //echo json_encode($jobId);
	 if ($jobId) {
         //$dbData = Config::get('db')->query("update sms_posts set status = 0, lastpostDate = 'NULL' where id={$jobId}");
		 $dbData = Config::get('db')->query("update job set status = 0 where postId={$jobId}");
		 $dbData = Config::get('db')->query("update sms_posts set lastpostDate = 'NULL' where id={$jobId}");
     } else {
         echo "Job Not Found.";
     }
     exit();
}

////////////////////////////////////////////////
//////// Post job to Twitter and SMS

//if (isset($_GET['pt'])) {
//     $jobId = isset($_REQUEST['jobId']) ? $_REQUEST['jobId'] : false;
//	 $limit = isset($_REQUEST['textLimit']) ? $_REQUEST['textLimit'] : 20;
//	 $userId = $account_data['id'];
//	 $radius = isset($_REQUEST['radius']) ? $_REQUEST['radius'] : 10;
// 	
//	 
//     if ($jobId) {
//     //Twitter Login for Taco Bueno.  We will have different logins for each account.
//	define('TWITTER_CONSUMER_KEY', 'V1s6FpeTyQ277NX0lpPH');
//	define('TWITTER_CONSUMER_SECRET', 'MKkYMFcRaBS5Hfzld8g4A2aecZV1CfMkNLyYw6X1YK1uRXAJcQ');
//	define('TWITTER_OAUTH_CALLBACK', 'http://jobalarm.com/twitterlogin.php');
//
//\Codebird\Codebird::setConsumerKey(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET);
//    $cb = \Codebird\Codebird::getInstance();
//    $cb->setToken("4614958992-LOIDkuZwRhTXJcz4IG7oGhOEIF3R5wiEYBxQhoX", "4kJ9mgP5N1F711SMrT3Xj16GnzZIPE5Bl8nC03i1eaJtk");
//
//$message = //CompanyName + needs + JobTitle + at their + City, ST + location. Apply at $address + #job + link;
//$send_message = $message;
//	 
//	 $dbGroupData = Config::get('db') -> get_results("select c.*, a.companyName, s.address, s.city, s.st, j.jobTitle from candidate c left join sms_jobs j on j.id = c.jobId left join account a on a.id = c.account left join sms_stores s on s.storeNum = c.storeNum and (3963*ACOS(SIN({$lat}/57.2958)*SIN(ce.latitude/57.2958)+ COS({$lat}/57.2958) *COS(ce.latitude/57.2958)*COS((ce.longitude/57.2958)-({$lon}/57.2958))))<={$radius} where c.textCount < 3 and c.accountId ={$userId}");   //This query isn't done.  The job has a city, st and a zip code and the candidate has a zip code.  We need all candidates within XX miles of the store that have applied with that company..
//		 
//     } else {
//         echo "No Candidates Found.";
//     }
//	 
//	 if (count($dbGroupData) > 0) {
//				
//				foreach ($dbGroupData as $candidate) {
//				
//		//send text message to each candidate and increment their textCount by 1
//     
//}
//
//
//exit();
//}

if (isset($_GET['getFbGroups'])) {


        $dbData = Config::get('db')->get_results("select * from job_groups");

        if (count($dbData) >0) {

            $tweet = $dbData[0];


            echo  json_encode($dbData);

        } else {

            echo "Job Not Found. count not greater than 0";

        }



    exit();


	}
if (isset($_GET['upsertFbGroup'])) {
	$exists = exists($_GET['upsertFbGroup']);
	$fbId = $_REQUEST['fbKey'];
	$name = $_REQUEST['description'];
	if ($exists) {
		 $dbData = Config::get('db') -> get_results("update job_groups");
		if (count($dbData) > 0) {
		$tweet = $dbData[0];
			echo json_encode($dbData);
		} else {		            echo "Job Not Found. count not greater than 0";
		}
	} else {
		 $dbData = Config::get('db') -> get_results("insert into job_groups (group_description, group_city, group_state,group_key_words, group_fb_id) VALUES ('{$name}', '', '', 'key words', {$fbId})");
		if (count($dbData) > 0) {				            $tweet = $dbData[0];
			echo json_encode($dbData);
		} else {				            echo "Job Not Found. count not greater than 0";
		}
	}              exit();
}
function exists($fbId) {	$exists = false;
	$dbData = Config::get('db') -> get_results("select * from job_groups where group_fb_id={$fbId}");
	if (count($dbData) > 0) {		$exists = true;
	} else {		$exists = false;
	}
	return $exists;
}

if (isset($_GET['updateFbGroup'])) {
   $exists = exists($_GET['updateFbGroup']);
	$id = $_REQUEST['id'];
	$city = $_REQUEST['city'];
	$state = $_REQUEST['state'];
	$keyWords = $_REQUEST['keyWords'];
	if ($exists) {
		 $dbData = Config::get('db') -> get_results("update job_groups set group_city='{$city}', group_key_words='{$keyWords}', group_state='{$state}' where group_id ={$id} ");
		if (count($dbData) > 0) {		            $tweet = $dbData[0];
			echo json_encode($dbData);
		} else {		            echo "Job Not Found. count not greater than 0";
		}
	} else {			echo "Job Not Found. count not greater than 0";
	}              exit();
}
/*
if (isset ($_GET['bump])) {
// update j_g_p fbBumpDate to timestamp + 7 days
// update fbAlerts for userID post count
}
*/

// Result of "Join" from Manage Groups
if (isset($_GET['joinGroup'])) {
	$fbGroupId = isset($_GET['joinGroup']) ? $_GET['joinGroup'] : false;
	$userId = $account_data['accountId'];
	$name = isset($_GET['name']) ? $_GET['name'] : false;
	$now = time();
	$alert_now = 0;
	$limit = $now + 3600 ;


// get fblimits info action=join: 	timeallowed, count, and alerttext for Join
$limits = Config::get('db') ->get_results("select * from fblimits where id = 1");
$count_limits = $limits[0];

// start join tracking by session account = username from job table
$alerts = Config::get('db') ->get_results("select * from fbalerts where userId = {$userId}");

// check if al record exists for this user
if (count($alerts) > 0 )
	{
	$count_alerts = $alerts[0];

// if so, increment joincount, check count against fblimits
//   if > limit, show warning @ dashboard line 411
    if ($count_limits['count'] > $count_alerts['joinCount'] and $now < $count_alerts['timer'])
    {
      $alerts = Config::get('db') -> query("update fbalerts set joinCount = joinCount + 1 where userId={$userId}");
    }
    else if ($now > $count_alerts['timer'])
    {
      $alerts = Config::get('db') -> query("update fbalerts set joinCount = 1, timer = $limit where userId={$userId}");
    }
    else
    {
	$alerts = Config::get('db') -> query("update fbalerts set joinCount = joinCount + 1 where userId={$userId}");
	$alert_now = 1;
// send join alert message to dashboard via JQuery getElementById(alerts) and show it or display warning in a dialog box
	//echo "Stop joining groups, do something else";
	}
}
else
{

// if not, insert a new one
// set time() + timeallowed
   $alerts = Config::get('db') -> query("insert into fbalerts (userId, timer, joinCount) values ({$userId}, $limit, 1)");
}

		$pending = $_REQUEST['pending'];



	if($pending){
		$dbData = Config::get('db') -> query("insert into accountGroupXref (accountId, fbGroupId, status) values ({$userId}, {$fbGroupId}, 2)");
	}

	$success = mail( "rstrenger@jobalarm.com", "Tweeded Jobs user request to join group for posting.", "User: ".  $name. " wishes to join group: https://www.facebook.com/groups/".$fbGroupId."/");
   echo json_encode(array('success'=>$success, 'test'=>'alex5', 'alert_now'=>$alert_now));
	exit();

	 echo "join group id: ". $userId ." , groupid: ". $fbGroupId . $account_data['accountId']. "";
	if ($fbGroupId) {
} else {        echo "Error";
	}
   exit();
}


if (isset($_GET['updateGroups'])) {

	$groups = json_decode($_REQUEST['groups']);





	$userId = $account_data['accountId'];

	 $dbData = Config::get('db') -> get_results("select * from accountGroupXref where accountId ={$userId}");
		if (count($dbData) > 0) {
			$tweetedGroups = $dbData[0];

		} else {
			$tweetedGroups = array();
		}


		$groupsJoined = array();
		$groupsToDelete = array();
		$groupsPending = array();
		$notJoined = array();

		$tempUserId = array();

		$length = count($groups);
		for($i = 0; $i < $length; $i++){


			$groupsJoined[$i] =	'("'.$userId.'", "'.$groups[$i]->id.'", 1)';
			$groupsToDelete[$i] = $groups[$i]->id;
		}

		$groupsJoined = implode(",", $groupsJoined);
		$groupsToDelete = implode(",", $groupsToDelete);


		delete($userId);

		delete2($userId, $groupsToDelete);

		$dbData = update($userId, $groupsJoined);
		if (count($dbData) > 0) {
			$success = true;

		} else {
			$success = false;
		}
		echo $success;
   exit();
}
if (isset($_GET['checkImg'])) {
 	$userId = $account_data['accountId'];
 	$fileFound = false;
 	$dbData = Config::get('db')->get_results("select * from images where default_img=1 and user_id=".$userId);
 	if ($dbData && count($dbData) > 0) {
 		$fileFound = file_exists('img/post_images/'.$dbData[0]['file_name']);
 	}
 	if ($fileFound) {
 		echo json_encode(array('success'=>'true','imginfo'=>$dbData[0])); 	
 	} else 
 		echo json_encode(array('success'=>'false'));
 	exit();
}

if (isset($_GET['uploadImg'])) {
	    $userId = $account_data['accountId'];
		$uploaddir = 'img/post_images/';
	if ($_FILES['image']) {
	 	if ($_REQUEST['default'] == '1') {
		 	$updateData = array('default_img'=>0);
		 	$updateWhere = array('default_img'=>1,'user_id'=>$userId);
		 	Config::get('db')->update('images',$updateData,$updateWhere,1);
	 	}
	 	$data = array('user_id'=>$userId,'file_name'=>basename($_FILES['image']['name']),'`default_img`'=>$_REQUEST['default']);
	 	Config::get('db')->insert('images',$data);
		$uploadfile = $uploaddir . basename($_FILES['image']['name']);
		move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile);
		echo json_encode(array('status'=>'success','filename'=>$uploadfile));
	} else {
		
		$dbData = Config::get('db')->get_results("select file_name from images where user_id = {$userId} and default_img = 1");
        if (count($dbData) >0) {
			$dev_image = $dbData[0];
			//$dev_image = implode($dev_image);
			$uploadfile = $uploaddir . implode($dev_image);
			echo json_encode(array('status'=>'success','filename'=>$uploadfile));
            } else {
            echo json_encode(array('status'=>'success','filename'=>'img/post_images/jalogo.jpg'));
        }
		}
			
		
	exit();
}

function delete($userId){
	$dbData = Config::get('db') -> query("delete from accountGroupXref where accountId = {$userId} and status = 1");

	return $dbData;
}

function delete2($userId, $groupsToDelete){
	$dbData = Config::get('db') -> query("delete from accountGroupXref where accountId = {$userId} and fbGroupId in ({$groupsToDelete}) and status <> 6");

	return $dbData;
}
// When Join request is approved, set the Post status to 6
function update($userId, $groupsJoined){
	$dbData = Config::get('db') -> query("insert into accountGroupXref (accountId, fbGroupId, status) values {$groupsJoined} on duplicate key update status = 6");
	return $dbData;
}

//start post tracking
// Built by JT 10/12/1015
//function buildPermalink($fbPostId)
//{
//   Job Post Permalink = https://www.facebook.com/groups/$fbGroupId/permalink/$fbPostId/
//   $fblink = explode('_', $fbPostId);
//   $fbPermaLink ="https://www.facebook.com/groups/".$fblink[0]."/permalink/".$fblink[1]."";
//   $fbPermaLink = stripslashes($fbPermaLink);

//    return $fbPermaLink;
//}
// Result of "Bump Post" from Manage Groups
if (isset($_GET['bumpPost']))
{
	$userId = $account_data['accountId'];
   $fbGroupId = $_REQUEST['fbGroupId'];
   //$fbPostId = $_REQUEST['fbPostId'];
   $jobId = $_REQUEST['jobId'];
   //$fbGroupId = $_GET['fbGroupId'];
   //$jobId = $_GET['jobId'];
	$now = time();
	$alert_now = 0;
	$limit = $now + 3600 ;
   $fbPostDate = date('Y-m-d H:m:s', $now);
   $bumpDate = $now +60*60*24*7;
   $fbBumpDate = date('Y-m-d H:m:s', $bumpDate);

   $PermaLink = Config::get('db') ->get_results("select fbPostId from job_groups_postings where fbGroupId = {$fbGroupId} and jobId = $jobId");
   $fbPost = $PermaLink['0'];
   $fbPost =  implode($fbPost);
   
      
// get fblimits info action=join: 	timeallowed, count, and alerttext for Join
   
   //$bumpedPost = Config::get('db') ->get_results("select job_groups_postings_ID, fbPostDate, fbBumpDate from job_groups_postings where fbPostId = {$fbPostId}");
   //$jgpId = $bumpedPost['job_groups_postings_ID'];
   //$upDatePost= Config::get('db') -> query("update job_groups_postings set fbPostDate = $fbPostDate, fbBumpDate = $fbBumpDate where job_groups_postings_ID = {$jgpId}");
   
//record bump
$alerts = Config::get('db') -> query("update job_groups_postings set fbFirstPostDate = NOW() where fbGroupId = {$fbGroupId} and jobId = $jobId");
	
// start join tracking by session account = username from job table
   $limits = Config::get('db') ->get_results("select * from fblimits where id = 3");
   $count_limits = $limits[0];
   
   $alerts = Config::get('db') ->get_results("select * from fbalerts where userId = {$userId}");

// check if al record exists for this user
      if (count($alerts) > 0 )
		{
		$count_alerts = $alerts[0];
		
	  if ($count_limits['count'] > $count_alerts['bumpCount'] and $now < $count_alerts['timer'])
      {
         $alerts = Config::get('db') -> query("update fbalerts set bumpCount = bumpCount + 1 where userId={$userId}");
      }
      else if ($now > $count_alerts['timer'])
      {
         $alerts = Config::get('db') -> query("update fbalerts set bumpCount = 1, timer = $limit where userId={$userId}");
      }
      else
      {
   	   $alerts = Config::get('db') -> query("update fbalerts set bumpCount = bumpCount + 1 where userId={$userId}");
   	   $alert_now = 1;
// send join alert message to dashboard via JQuery getElementById(alerts) and show it or display warning in a dialog box
	//echo "Stop joining groups, do something else";
   	}
   }
   else
   {

// if not, insert a new one
// set time() + timeallowed
      $alerts = Config::get('db') -> query("insert into fbalerts (userId, timer, bumpCount) values ({$userId}, $limit, 1)");
   }
   
   echo json_encode(array('success'=>true, 'alert_now'=>$alert_now, 'fbPost'=>$fbPost, 'fbGroupId'=>$fbGroupId, 'jobId'=>$jobId));
}


if (isset($_GET['updatePosted'])) {
		$fbGroupId = $_REQUEST['fbGroupId'];
      $fbPostId =  $_REQUEST['fbPostId'];
		$userId = $account_data['accountId'];
		$jobId = $_REQUEST['jobId'];
		$now = time();
	$alert_now = 0;
	$limit = $now + 3600 ;


// get fblimits info action=join: 	timeallowed, count, and alerttext for Join
$limits = Config::get('db') ->get_results("select * from fblimits where id = 2");
$count_limits = $limits[0];

// start join tracking by session account = username from job table
$alerts = Config::get('db') ->get_results("select * from fbalerts where userId = {$userId}");

// check if al record exists for this user
if (count($alerts) > 0 )
	{
	$count_alerts = $alerts[0];

// if so, increment joincount, check count against fblimits
//   if > limit, show warning @ dashboard line 411
    if ($count_limits['count'] > $count_alerts['postCount'] and $now < $count_alerts['timer'])
    {
      $alerts = Config::get('db') -> query("update fbalerts set postCount = postCount + 1 where userId={$userId}");
    }
    else if ($now > $count_alerts['timer'])
    {
      $alerts = Config::get('db') -> query("update fbalerts set postCount = 1, timer = $limit where userId={$userId}");
    }
    else
    {
	$alerts = Config::get('db') -> query("update fbalerts set postCount = postCount + 1 where userId={$userId}");
	$alert_now = 1;
// send join alert message to dashboard via JQuery getElementById(alerts) and show it or display warning in a dialog box
	//echo "Stop joining groups, do something else";
	}
}
else
{
   $alerts = Config::get('db') -> query("insert into fbalerts (userId, timer, postCount) values ({$userId}, $limit, 1)");
}


   $fblink = explode('_', $fbPostId);
   $fbPostID = $fblink[1];
   $fbPermaLink ="https://www.facebook.com/groups/".$fblink[0]."/permalink/".$fblink[1]."";
   $now = time();
   //$fbUpdatePost = $now -60*60*6;
   //$fbPostDate = date('Y-m-d H:m:s', $fbUpdatePost);
   $bumpDate = $now +10;
   $fbBumpDate = date('Y-m-d H:m:s', $bumpDate);
   $dbData = Config::get('db') -> query("insert into job_groups_postings (fbGroupId, jobId, fbPostId, fbBumpDate, fbPermalink, fbFirstPostDate) values ({$fbGroupId}, $jobId, '{$fbPostID}', '{$fbBumpDate}', '{$fbPermaLink}', CURRENT_TIMESTAMP)");
 	echo json_encode(array('success'=>true, 'data'=>$dbData, 'alert_now'=>$alert_now));
}

if (isset($_GET['getGroupsToJoin'])) {
		$userId = $account_data['accountId'];

		$dbData = Config::get('db') -> get_results("select * from job_groups jd left join accountGroupXref x on x.fbGroupId = jd.group_fb_id and x.accountId = {$userId} group by jd.group_id");
 		echo json_encode(array('success'=>true, 'data'=>$dbData));
}





