<?php
include '../inc/class.db.php';
include '../inc/class.jatwitter.php';
include '../inc/config.php';


//$local_file = '/home/tweetedjobs/feeds/snag/parkers.xml';

//cleargpm();
//getGpmFeed();
//updategpm();
//cleartable();
//getDollarFeed();
updateDollar();



function cleartable(){
$dbDelete = Config::get('db') -> query("truncate table gpm");
}

function getDollarFeed(){
$filename = '/home/tweetedjobs/feeds/dollar.csv';
    //get the csv file
$handle = fopen($filename,"r");

$dcs = array("06095","13441","18603","22630","23320","28105","29330","31407","32448","34473","38654","40351","43334","46705","52060","60436","64093","72303","73448","73533","77471","79766","84790","92407","95206","98642");
$sfa = "SALES FLOOR ASSOCIATE";
$csr = "CUSTOMER SERVICE REPRESENTATIVE";
$asm = "ASSISTANT STORE MANAGER";
$dct = "DC";

    //loop through the csv file and insert into database
    do {
        if ($data[0]) {
			
			if((in_array(substr($data[6],0,5),$dcs) && $data[2]!=$sfa && $data[2]!=$csr && $data[2]!=$asm) || substr($data[1],0,2)==$dct){
				$priority = 1;
			}else{
				$priority = 2;
			}
			
			$data = array(
				'url'=>$data[0],
				'jobTitle'=>addslashes($data[2]),
				'address'=>addslashes($data[3]),
				'city'=>addslashes($data[4]),
                'state'=>$data[5],
                'zip'=>substr($data[6],0,5),
                'brandName'=>substr($data[8],0,3),
				'jobFolder'=>substr($data[7],0,3),
				'priority'=>$priority
            );

            Config::get('db')->insert('gpm',$data);		
        }
    } while ($data = fgetcsv($handle));
	
	$dbCec = Config::get('db') -> query("DELETE FROM `job` WHERE `twitterId` not in (select `url` from `gpm`) and `postId`=271");
	sleep(5);
	}

function updateDollar(){
	$newDollar = Config::get('db')->get_results("select g.*, c.state_code from `gpm` g left join `cities_extended` as c on c.state_name = g.state where g.url not in (select `twitterId` from `job` where `postId`=271) and g.id !=1 group by g.url order by g.date DESC LIMIT 0,3000");
	
	foreach($newDollar as $cec) {
	$sitecec = "Dol";
	$siteid = "Hrl";
	$siteName= substr($cec['brandName'],0,3);
	$siteId= substr($cec['jobFolder'],0,3);
		
	if($sitecec==$siteName){
		$brandId = 27;
		$name = "Dollar Tree";
	}else{
		$brandId = 12;
		$name = "Family Dollar";
	}
	if($siteid==$siteId){
		$siteNum = "5477";
	}else{
		$siteNum = "5258";
	}
	if(intval($cec['zip'])>0){
	
	$job_text = "{$cec['jobTitle']} position with {$name} in {$cec['city']}, {$cec['state_code']}. Mobile Apply Now!";	
	$jobUrl = "https://sjobs.brassring.com/TGnewUI/Search/home/HomeWithPreLoad?PageType=JobDetails&partnerid=25600&siteid=" . $siteNum . "&areq=" . $cec['url'] . "&Codes=JbAlrm";
		
		$job_data = array(
				'twitterId' => $cec['url'],
				'city' => filter_var($cec['city'], FILTER_SANITIZE_STRING),
				'state' => $cec['state_code'],
				'postDate' => $cec['date'],
				'zipCode' => $cec['zip'],
				'title' => filter_var($cec['jobTitle'], FILTER_SANITIZE_STRING),
				'text' => filter_var($job_text, FILTER_SANITIZE_STRING),
				'hashTags' => "JobAlarm",
				'brand' => $brandId,
				'userName' => "Dollar Tree",
				'campaignId'=>0,
				'jobCats'=>74,
				'postId'=>271,
				'urls' => $jobUrl,
				'address' => filter_var($cec['address'], FILTER_SANITIZE_STRING),
				'priority' => $cec['priority']
			);
		Config::get('db')->insert('job',$job_data);
		}else{
		//do nothing;
		}
	}
}

				
 
?>