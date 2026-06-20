<?php
include '../inc/class.db.php';
include '../inc/class.jatwitter.php';
include '../inc/config.php';


//$local_file = '/home/tweetedjobs/feeds/snag/parkers.xml';

//cleargpm();
//getGpmFeed();
//updategpm();

clearbyrne();
getByrneFeed();
updatebyrne();


function clearbyrne(){
$dbDelete = Config::get('db') -> query("truncate table gpm");
sleep(5);
}

function getByrneFeed(){
$filename = '/home/tweetedjobs/feeds/byrne.csv';
    //get the csv file
$handle = fopen($filename,"r");

    //loop through the csv file and insert into database
    do {
        if ($data[1]) {
			
			$data = array(
				'date'=>date('Y-m-d H:i:s',strtotime($data[12])),
				'url'=>$data[9],
				'jobTitle'=>addslashes($data[1]),
				'city'=>addslashes($data[6]),
                'state'=>$data[7],
                'zip'=>substr($data[8],0,5),
				'jobFolder'=>$data[4],
                'brandName'=>addslashes($data[0])
            );

            Config::get('db')->insert('gpm',$data);		
        }
    } while ($data = fgetcsv($handle));
	
	$dbDelete = Config::get('db') -> query("DELETE FROM `job` WHERE `postId`=436 AND `urls` not in (select `url` from `gpm`)");
	sleep(5);
}

function updatebyrne(){
	$newDollar = Config::get('db')->get_results("select g.* from `gpm` g where g.url not in (select `urls` from `job` where `postId`=436) group by g.url order by g.id ASC limit 2000");
	$i=0;
	foreach($newDollar as $dollar) {
	$newId = "BYRNE" . $i;

	$job_text = "{$dollar['jobTitle']} position with Byrne Dairy at {$dollar['jobFolder']} in {$dollar['city']}, {$dollar['state']}. Mobile Apply Now!";	
		
		if($i>0){
		$job_data = array(
				'twitterId' => $newId,
				'city' => addslashes($dollar['city']),
				'postDate' => $dollar['date'],
				'state' => $dollar['state'],
				'zipCode' => $dollar['zip'],
				'title' => addslashes($dollar['jobTitle']),
				'text' => addslashes($job_text),
				'hashTags' => "ByrneJobs,JobAlarm",
				'brand' => 142,
				'userName' => "Byrne Dairy",
				'campaignId'=>0,
				'jobCats'=>68,
				'postId'=>436,
				'urls' => $dollar['url']
			);
		Config::get('db')->insert('job',$job_data);
		$i=$i+1;
		}else{
		$i=$i+1;
		}
	}
}

				
 
?>