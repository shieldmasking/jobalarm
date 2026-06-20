<?php
include '../inc/class.db.php';
include '../inc/class.jatwitter.php';
include '../inc/config.php';


//$local_file = '/home/tweetedjobs/feeds/snag/parkers.xml';

clearjob();
getGpmFeed();
updategpm();
cleargpm();
//cleardollar();
//getDollarFeed();
//updatedollar();


function clearjob(){
$dbDelete = Config::get('db') -> query("delete from `job` where `brand`=130");
//$dbDelete = Config::get('db') -> query("truncate table gpm");
}

function cleargpm(){
//$dbDelete = Config::get('db') -> query("delete from `job` where `brand`=130");
$dbDelete = Config::get('db') -> query("truncate table gpm");
}
	
function getGpmFeed(){
$filename = '/home/tweetedjobs/feeds/gpm.csv';
    //get the csv file
$handle = fopen($filename,"r");

    //loop through the csv file and insert into database
    do {
        if ($data[0]) {
			
			$data = array(
                'brandName'=>addslashes($data[0]),
                'jobTitle'=>addslashes($data[1]),
                'address'=>addslashes($data[2]),
                'city'=>addslashes($data[3]),
                'state'=>$data[4],
                'zip'=>substr($data[5],0,5),
                'date'=>date('Y-m-d H:i:s',strtotime($data[6])),
                'url'=>$data[7],
                'jobFolder'=>$data[8]
            );

            Config::get('db')->insert('gpm',$data);		
        }
    } while ($data = fgetcsv($handle));

}

function updategpm(){
	$newjobs = Config::get('db')->get_results("select g.*, c.state_code from gpm g inner join `states` as c on c.state_name = g.state group by g.url");
	$i=1;
	foreach($newjobs as $job) {
	$newId = "GPM" . $i;
	$brandName = substr($job['brandName'],0,3);
	$gpmName = "GPM";
	
	if($brandName==$gpmName){
	$job_text = "{$job['jobTitle']} position with GPM Investments in {$job['city']}, {$job['state_code']}. Mobile Apply Now.";
	}else{
	$job_text = "{$job['jobTitle']} position with {$job['brandName']} (GPM Investments) at {$job['address']} in {$job['city']}, {$job['state_code']}. Mobile Apply Now.";	
	}
	if(strlen($job['url'])>0){
		$job_data = array(
				'twitterId' => $newId,
				'city' => filter_var($job['city'], FILTER_SANITIZE_STRING),
				'state' => $job['state_code'],
				'postDate' => $job['date'],
				'zipCode' => $job['zip'],
				'title' => filter_var($job['jobTitle'], FILTER_SANITIZE_STRING),
				'text' => filter_var($job_text, FILTER_SANITIZE_STRING),
				'hashTags' => "GPMJobs,JobAlarm",
				'brand' => 130,
				'userName' => "GPM Investments",
				'campaignId'=>0,
				'jobCats'=>68,
				'postId'=>334,
				'urls' => $job['url']
			);
		Config::get('db')->insert('job',$job_data);
	}else{
			//skip;
	}
		$i=$i+1;
	}
	echo "updated records: ".$i;
}

				
 
?>