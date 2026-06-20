<?php
include '../inc/class.db.php';
include '../inc/class.jatwitter.php';
include '../inc/config.php';


$local_file = '/home/tweetedjobs/feeds/jho.xml';

cleardata();
getDataFeed();
parsexml2($local_file);
updatejob();

function cleardata(){
$del = 485;
$dbDelete = Config::get('db') -> query("truncate table cec_job");	
$dbDelete = Config::get('db') -> query("delete from `job` where `postId`={$del}");
}
	
function getDataFeed(){
	$output_filename = '/home/tweetedjobs/feeds/jho.xml';
	$dom = new DOMDocument();
	$dom->load('https://secure.jobappnetwork.com/commoncfmx/rss_feed/rss.cfm?c=jho&Enhanced=1');
	$dom->save($output_filename);
}
	
function parsexml2($x){
	
	$z = new XMLReader;
	$z->open($x);
	
	$doc = new DOMDocument;
	$cnt=0;
	// move to the first <product /> node
	while ($z->read() && $z->name !== 'item');
	
	// now that we're at the right depth, hop to the next <product/> until the end of the tree
	while ($z->name === 'item')
	{
	    // either one should work
	    //$node = new SimpleXMLElement($z->readOuterXML());
	    $node = simplexml_import_dom($doc->importNode($z->expand(), true));
	
	    // now you can use $node without going insane about parsing
	   updateDB($node->clientrefnum, $node->city, $node->state, $node->address, $node->externalName, $node->pubDate, $node->applyurl, $node->zip, $node->brandname, $node->title);
	    //echo "*****************************</br>$node->referencenumber, $node->city, $node->state, $node->description,$node->title, $node->lastmodifieddate, $node->url</br>";
	    $cnt++;
	    //if($cnt==5){return;}
	    // go to next <product />
	    $z->next('item');
	}
	echo "updated records: ".$cnt;
}
	
function updateDB($ref,$city,$state,$desc1,$title,$postDate,$url,$zip,$brandname,$title2){
	include '../inc/dbpdo.php';
	
	//$username = $brandname;
	$desc = addslashes($desc1);
	//$position=' position';
	$pdate = date('Y-m-d H:i:s', strtotime($postDate));
	$today = date();
	$diff = strtotime($today) - strtotime($postDate);
    $diff_in_hrs = $diff/3600;
	
	if(strpos($title2, 'Subway')) { 
    $brand = 57;
	$cat = 68;
	}else if(strpos($title2, 'Burger')) { 
    $brand = 38;
	$cat = 68;
	}else if(strpos($title2, 'Beans')) { 
    $brand = 335;
	$cat = 68;
	}else if(strpos($title2, 'Chevron')) { 
    $brand = 253;
	$cat = 69;
	}else if(strpos($title2, 'Union')) { 
    $brand = 336;
	$cat = 69;
	}else if(strpos($title2, 'Costa')) { 
    $brand = 334;
	$cat = 68;
	}else{
	$brand= 38;
	$cat = 68;	
	}
	$campaingnid = 0;
	//$brand = 298;
	$postId = 483;
	
	
	
	if($diff >=1 && $diff_in_hrs <=36){
	 
		$campaingnid= 1;
	}
	
	$address1 = explode(",", $desc);
	$address = $address1[0];
	
	$stmt = $dbh->prepare('SELECT twitterId FROM cec_job WHERE postId=' . $postId . ' and twitterid=:ref');
	$stmt->bindParam(':ref',$ref, PDO::PARAM_STR);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
     
		if($stmt->rowCount() == 0)
		{
		
		
			try {
		    $stmt3 = $dbh->prepare("insert into cec_job (twitterId,brand,campaignId,city,state,postDate,userName,title,urls,postId,zipCode)  
		    	 					values(:ref,:brand,:campaignId,:city,:state,:postdate,:username,:title,:url,:postId,:zipCode)
		    	 					");
									
			$stmt3->bindParam(':ref',$ref, PDO::PARAM_STR);
			$stmt3->bindParam(':brand',$brand, PDO::PARAM_INT);
			$stmt3->bindParam(':campaignId',$cat, PDO::PARAM_INT);
			$stmt3->bindParam(':city',$city, PDO::PARAM_STR);
			$stmt3->bindParam(':state',$state, PDO::PARAM_STR);
			
			$stmt3->bindParam(':postdate',$pdate, PDO::PARAM_STR);
			$stmt3->bindParam(':username',$brandname, PDO::PARAM_STR);
			$stmt3->bindParam(':title',$title, PDO::PARAM_STR);
			$stmt3->bindParam(':url',$url, PDO::PARAM_STR);
			$stmt3->bindParam(':postId',$postId, PDO::PARAM_STR);
			$stmt3->bindParam(':zipCode',$zip, PDO::PARAM_STR);
			//$stmt3->bindParam(':title2',$title, PDO::PARAM_STR);
			
			$stmt3->execute();
			$lastId = $dbh->lastInsertId();
			
			$text = "{$title} position at {$brandname} (HB Boys L.C.) at {$address} in {$city}, {$state}. Mobile Apply Now.";
			
			
			
			$uSql = "update cec_job set text = :text where postId={$postId} and twitterid = :ref";
			
			$stmt2 = $dbh->prepare($uSql);
			
			$stmt2->bindParam(':text',$text, PDO::PARAM_STR);
			$stmt2->bindParam(':ref',$ref, PDO::PARAM_STR);
			//$stmt2->bindParam(':campaignid',$campaingnid, PDO::PARAM_INT);
			$stmt2->execute();
			
			
			
			}catch(PDOException $e){
				echo "Error: ".$e->getMessage();
			}
			
		}

}

function updatejob(){
	$ja = 483;
	$newjobs = Config::get('db')->get_results("select * from cec_job where postId={$ja}");
	
	foreach($newjobs as $job) {
	//$job_text = "{$job['title']} at {$job['text']}. Mobile Apply Now.";
			
	$job_data = array(
				'twitterId' => $job['twitterId'],
				'city' => filter_var($job['city'], FILTER_SANITIZE_STRING),
				'state' => $job['state'],
				'zipCode' => $job['zipCode'],
				'title' => filter_var($job['title'], FILTER_SANITIZE_STRING),
				'text' => filter_var($job['text'], FILTER_SANITIZE_STRING),
				'hashTags' => "JobAlarm",
				'brand' => $job['brand'],
				'userName' => $job['userName'],
				'campaignId'=>0,
				'jobCats'=>$job['campaignId'],
				'postId'=> $job['postId'],
				'urls' => $job['urls']
			);
		Config::get('db')->insert('job',$job_data);
	}
}
				
 
?>