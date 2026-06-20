<?php
include '../inc/class.db.php';
include '../inc/class.jatwitter.php';
include '../inc/config.php';


$local_file = '/home/tweetedjobs/feeds/snag/rickers.xml';

cleardata();
getDataFeed();
parsexml2($local_file);
updatejob();

function cleardata(){
$dbDelete = Config::get('db') -> query("truncate table cec_job");	
$dbDelete = Config::get('db') -> query("delete from `job` where `brand`=162");
}
	
function getDataFeed(){
	$output_filename = '/home/tweetedjobs/feeds/snag/rickers.xml';
	$dom = new DOMDocument();
	$dom->load('https://app.clearcompany.com/api/v2/jobs/rickersrewards/xml/');
	$dom->save($output_filename);
}
	
function parsexml2($x){
	
	$z = new XMLReader;
	$z->open($x);
	
	$doc = new DOMDocument;
	$cnt=0;
	// move to the first <product /> node
	while ($z->read() && $z->name !== 'job');
	
	// now that we're at the right depth, hop to the next <product/> until the end of the tree
	while ($z->name === 'job')
	{
	    // either one should work
	    //$node = new SimpleXMLElement($z->readOuterXML());
	    $node = simplexml_import_dom($doc->importNode($z->expand(), true));
	
	    // now you can use $node without going insane about parsing
	   updateDB($node->Id, $node->City, $node->CountrySubdivisionName, $node->OfficeAddress, $node->PositionTitle, $node->OpenDate, $node->ApplyUrl, $node->PostalCode);
	    //echo "*****************************</br>$node->referencenumber, $node->city, $node->state, $node->description,$node->title, $node->lastmodifieddate, $node->url</br>";
	    $cnt++;
	    //if($cnt==5){return;}
	    // go to next <product />
	    $z->next('job');
	}
	echo "updated records: ".$cnt;
}
	
function updateDB($ref,$city,$state,$desc,$title,$postDate,$url,$zip){
	include '../inc/dbpdo.php';
	
	$username='RICKERS';
	$pdate = date('Y-m-d H:i:s', strtotime($postDate));
	$today = date();
	$diff = strtotime($today) - strtotime($postDate);
    $diff_in_hrs = $diff/3600;
	//$title = substr($title,0,-9);
	 
	$campaingnid = 0;
	$brand = 162;
	
	
	if($diff >=1 && $diff_in_hrs <=36){
	 
		$campaingnid= 1;
	}
	
	$stmt = $dbh->prepare('SELECT twitterId FROM cec_job WHERE brand=162 and twitterid=:ref');
	$stmt->bindParam(':ref',$ref, PDO::PARAM_STR);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
     
		if($stmt->rowCount() == 0)
		{
		
		
			try {
		    $stmt3 = $dbh->prepare("insert into cec_job (twitterId,brand,campaignId,city,state,postDate,userName,title,urls,rawData,zipCode)  
		    	 					values(:ref,:brand,:campaignId,:city,:state,:postdate,:username,:title,:url,:rawData,:zipCode)
		    	 					");
									
			$stmt3->bindParam(':ref',$ref, PDO::PARAM_STR);
			$stmt3->bindParam(':brand',$brand, PDO::PARAM_INT);
			$stmt3->bindParam(':campaignId',$campaingnid, PDO::PARAM_INT);
			$stmt3->bindParam(':city',$city, PDO::PARAM_STR);
			$stmt3->bindParam(':state',$state, PDO::PARAM_STR);
			
			$stmt3->bindParam(':postdate',$pdate, PDO::PARAM_STR);
			$stmt3->bindParam(':username',$username, PDO::PARAM_STR);
			$stmt3->bindParam(':title',$title, PDO::PARAM_STR);
			$stmt3->bindParam(':url',$url, PDO::PARAM_STR);
			$stmt3->bindParam(':rawData',$desc, PDO::PARAM_STR);
			$stmt3->bindParam(':zipCode',$zip, PDO::PARAM_STR);
			//$stmt3->bindParam(':title2',$title, PDO::PARAM_STR);
			
			$stmt3->execute();
			$lastId = $dbh->lastInsertId();
			
			$text = "{$title} position with Ricker\'s located at {$desc} in {$city}, {$state}. Mobile Apply Now.";
			
			
			
			$uSql = "update cec_job set text = :text where brand=162 and campaignid=:campaignid and twitterid = :ref";
			
			$stmt2 = $dbh->prepare($uSql);
			
			$stmt2->bindParam(':text',$text, PDO::PARAM_STR);
			$stmt2->bindParam(':ref',$ref, PDO::PARAM_STR);
			$stmt2->bindParam(':campaignid',$campaingnid, PDO::PARAM_INT);
			$stmt2->execute();
			
			
			
			}catch(PDOException $e){
				echo "Error: ".$e->getMessage();
			}
			
		}

}

function updatejob(){
	$newjobs = Config::get('db')->get_results("select * from cec_job where brand=162");
	
	foreach($newjobs as $job) {
	$job_text = "{$job['title']} position with Ricker\'s located at {$job['rawData']} in {$job['city']}, {$job['state']}. Mobile Apply Now.";
			
	$job_data = array(
				'twitterId' => $job['twitterId'],
				'city' => $job['city'],
				'state' => $job['state'],
				'zipCode' => $job['zipCode'],
				'title' => $job['title'],
				'text' => $job_text,
				'hashTags' => "RickersJobs,JobAlarm",
				'brand' => 162,
				'jobCats'=>68,
				'userName' => "Rickers",
				'campaignId'=>0,
				'postId'=>352,
				'urls' => $job['urls']
			);
		Config::get('db')->insert('job',$job_data);
	}
}
				
 
?>