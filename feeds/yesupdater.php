<?php
include '../inc/class.db.php';
include '../inc/class.jatwitter.php';
include '../inc/config.php';
//set_time_limit(0);

//$local_file =  '/home/tweetedjobs/feeds/snag/Snagajob-Job-Feed_CUSTOM.XML';

//getDataFeed();
//parsexml2($local_file);
//statuszero();
removeDupes();
//statussix();



function removeDupes(){	
$dbBrands = Config::get('db') -> get_results("select j.*, c.zip as newZip from `job` j left join `cities_extended` as c on c.city = j.city and c.state_code = j.state where `twitterId` LIKE \"YESWAY%\" and j.zipCode ='99999' and c.zip !='' group by j.twitterId");


foreach($dbBrands as $brand) {
	$newZip = $brand['newZip'];
	$twitterId = $brand['twitterId'];
	

	$dbData = Config::get('db')-> query("update `job` set `zipCode`='{$newZip}' where `twitterId`='{$twitterId}'");
	
}
echo "success";	

}

function statussix(){	
$dbBrands = Config::get('db') -> get_results("select * from `sms_brand` where `id`=6");

foreach($dbBrands as $brand) {
	$brandId = $brand['id'];
	//$hashTag = $brand['searchKeys'];
	echo "brand = ".$brandId;
//	$dupe_check2 = Config::get('db')->get_results("SELECT j.*, c.twitterId as tweetId FROM `job` j left join `snag_job` as c on c.twitterId=j.twitterId WHERE j.brand ={$brandId} and j.status=1 and c.twitterId is null");
	
//foreach($dupe_check2 as $dupe) {
//	$twitterId = $dupe['twitterId'];
	$dbData = Config::get('db')-> query("update `job` set `status`=0 where `brand`={$brandId} and `status`=3");
//	echo "removed";
//}
	ini_set('memory_limit', '-1');
	ini_set('max_execution_time', 300);
	$countJobs = Config::get('db')->get_results("SELECT count(id) as id FROM `snag_job` WHERE `brand`={$brandId}");
	$countJ = $countJobs[0]['id'];
	$countJ = $countJ/3000;
	$i = 0;
	$times_to_run = floor($countJ);
	while ($i++ < $times_to_run)
	{
    $start = $i * 3000;
	$newJobs = Config::get('db')->get_results("SELECT * FROM `snag_job` WHERE `brand`={$brandId} limit {$start},3000");
	
	if ($newJobs){
	foreach($newJobs as $job) {
	//$newJobs = Config::get('db')->get_results("SELECT * FROM `snag_job` WHERE `brand`={$brandId} limit 0,5000");
	//$newJobs = Config::get('db')->get_results("SELECT * FROM `snag_job` WHERE `brand`={$brandId}");
	//$hash = $hashTag.",JobAlarm";
	$text = $job['text'];
	$city = $job['city'];
	//echo "city ".$city;
	$rawdata = $job['rawData'];
	$title = $job['title'];
	$url = $job['urls'];
	$date = $job['postDate'];
	$hashtag = $job['hashTags'];
	$username = $job['userName'];
	$twitid = $job['twitterId'];
	$status = 3;
	$postId = 1;
	$state = $job['state'];
	$zip = $job['zipCode'];
	$data = array(
				'twitterId'=>Config::get('db')->filter($twitid),
				'postDate'=>date("Y-m-d H:i:s",strtotime($date)),
				'title'=>Config::get('db')->filter($title),
				'text'=>Config::get('db')->filter($text),
				'city'=>Config::get('db')->filter($city),
				'state'=>Config::get('db')->filter($state),
				'hashTags'=>Config::get('db')->filter($hashtag),
				'rawData'=>Config::get('db')->filter($rawdata),
				'urls'=>Config::get('db')->filter($url),
				'status'=>$status,
				'userName'=>Config::get('db')->filter($username),
				'brand'=>$brandId,
				'postId'=>$postId,
				'zipCode'=>Config::get('db')->filter($zip)
				);				
				Config::get('db')->insert('job',$data); 
				}
				echo "inserted";
	}else{
		echo "no jobs";
	}
	}
	
}
echo "success";	

}






?>
