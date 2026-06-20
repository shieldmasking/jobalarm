<?php
ini_set('display_errors',1);
include '../inc/class.db.php';
//include '../inc/class.jatwitter.php';
include '../inc/config.php';


cleartable();
getDollarFeed();
updateDollar();



function cleartable(){
$dbDelete = Config::get('db') -> query("truncate table gpm");
}

function getDollarFeed(){
$filename = '/home/tweetedjobs/feeds/cec.csv';
    //get the csv file
$handle = fopen($filename,"r");
$data = fgetcsv($handle);
    //loop through the csv file and insert into database
    do {
        if ($data[0]) {
			
			$data = array(
				'url'=>$data[7],
				'jobTitle'=>addslashes($data[1]),
				'city'=>str_replace("'","",$data[4]),
                'state'=>$data[5],
                'zip'=>substr($data[6],0,5),
                'brandName'=>str_replace("'","",$data[2]),
				'jobFolder'=>$data[3],
				'jobId'=>$data[0]
            );

            Config::get('db')->insert('gpm',$data);		
        }
    } while ($data = fgetcsv($handle));
	
	//$dbCec = Config::get('db') -> query("DELETE FROM `job` WHERE `twitterId` not in (select `jobId` from `gpm`) and (`postId`=126 OR `postId`=443)");
	sleep(5);
	}

function updateDollar(){
	$dbCec = Config::get('db') -> query("DELETE FROM `job` WHERE `twitterId` not in (select `jobId` from `gpm`) and (`postId`=126 OR `postId`=443)");
	$newCec = Config::get('db')->get_results("select g.*, c.state_code from `gpm` g left join `cities_extended` as c on c.state_name = g.state where g.jobId not in (select `twitterId` from `job` where `postId`=126 OR `postId`=443) and g.id >2 group by g.jobId");
	$count = 0;
	$status = 0;
	foreach($newCec as $cec) {
	$sitecec = "Chu";
	$siteadv = "Adv";
	$siteppp = "Pet";
	$sitecec2 = "CEC";
	$siteName= substr($cec['brandName'],0,3);
		
	if($sitecec==$siteName){
		$brandId = 7;
		$name = $cec['brandName'];
		$postId = 126;
		$status = 1;	
	}else if($siteadv==$siteName){
		$brandId = 7;
		$name = $cec['brandName'];
		$postId = 126;
		$status = 1;
	}else if($siteppp==$siteName){
		$brandId = 8;
		$name = $cec['brandName'];
		$postId = 443;
		$status = 1;
	}else{
		$brandId = 7;
		$name = $cec['brandName'];
		$postId = 126;
		$status = 0;
	}
	if(intval($cec['zip'])>0){
	$count = $count +1;
	$job_text = "{$cec['jobTitle']} position with {$cec['brandName']} in {$cec['city']}, {$cec['state_code']}. Mobile Apply Now!";	
	//$jobUrl = "https://sjobs.brassring.com/TGWebHost/jobdetails.aspx?partnerid=25396&siteid=5273&Codes=JOBSRC20&AReq=" . $cec['url'];
	$jobUrl = $cec['url'];
	$jobAddress = "{$cec['city']}, {$cec['state_code']}";
		
		$job_data = array(
				'twitterId' => $cec['jobId'],
				'city' => $cec['city'],
				'state' => $cec['state_code'],
				'zipCode' => $cec['zip'],
				'title' => $cec['jobTitle'],
				'text' => $job_text,
				'hashTags' => "ChuckECheeseJobs,JobAlarm",
				'brand' => $brandId,
				'userName' => $cec['brandName'],
				'campaignId'=> $cec['jobFolder'],
				'status'=>$status,
				'jobCats'=>48,
				'postId'=> $postId,
				'urls' => $jobUrl,
				'address' => $jobAddress
				
			);
		Config::get('db')->insert('job',$job_data);
		}else{
		//do nothing;
		}
	}
	echo "CEC Records updated: " . $count;
}

				
 
?>