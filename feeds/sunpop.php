<?php
include '../inc/class.db.php';
include '../inc/class.jatwitter.php';
include '../inc/config.php';


$local_file = '/home/tweetedjobs/feeds/sunpop.xml';

cleardata();
getDataFeed();
parsexml2($local_file);
updatejob();

function cleardata(){
$dbDelete = Config::get('db') -> query("truncate table cec_job");	
$dbDelete = Config::get('db') -> query("delete from `job` where `brand`=36 AND `postId`=458");
}
	
function getDataFeed(){
	$output_filename = '/home/tweetedjobs/feeds/sunpop.xml';
	$dom = new DOMDocument();
	$dom->load('https://secure.jobappnetwork.com/commoncfmx/rss_feed/rss.cfm?c=POP&Enhanced=1');
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
	   updateDB($node->clientrefnum, $node->city, $node->state, $node->address, $node->externalName, $node->pubDate, $node->applyurl, $node->zip, $node->brandname, $node->company);
	    //echo "*****************************</br>$node->referencenumber, $node->city, $node->state, $node->description,$node->title, $node->lastmodifieddate, $node->url</br>";
	    $cnt++;
	    //if($cnt==5){return;}
	    // go to next <product />
	    $z->next('item');
	}
	echo "updated records: ".$cnt;
}
	
function updateDB($ref,$city,$state,$desc1,$title,$postDate,$url,$zip,$brandname,$company){
	include '../inc/dbpdo.php';
	
	//$username = $brandname;
	$desc = addslashes($desc1);
	//$position=' position';
	$pdate = date('Y-m-d H:i:s', strtotime($postDate));
	$today = date();
	$diff = strtotime($today) - strtotime($postDate);
    $diff_in_hrs = $diff/3600;
	//$title = substr($title,0,-9);$
	//$title = $title . $position;
	$campaingnid = 0;
	$brand = 36;
	$postId = 458;
	
	
	
	if($diff >=1 && $diff_in_hrs <=36){
	 
		$campaingnid= 1;
	}
	
	$address1 = explode(",", $desc);
	$address = $address1[0];

	$stmt = $dbh->prepare('SELECT twitterId FROM cec_job WHERE postId=458 and twitterid=:ref');
	$stmt->bindParam(':ref',$ref, PDO::PARAM_STR);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
     
		if($stmt->rowCount() == 0)
		{
		
		
			try {
		    $stmt3 = $dbh->prepare("insert into cec_job (twitterId,brand,campaignId,city,state,postDate,userName,title,urls,postId,zipCode,hashTags,rawData)  
		    	 					values(:ref,:brand,:campaignId,:city,:state,:postdate,:username,:title,:url,:postId,:zipCode,:company,:address)
		    	 					");
									
			$stmt3->bindParam(':ref',$ref, PDO::PARAM_STR);
			$stmt3->bindParam(':brand',$brand, PDO::PARAM_INT);
			$stmt3->bindParam(':campaignId',$campaingnid, PDO::PARAM_INT);
			$stmt3->bindParam(':city',$city, PDO::PARAM_STR);
			$stmt3->bindParam(':state',$state, PDO::PARAM_STR);
			
			$stmt3->bindParam(':postdate',$pdate, PDO::PARAM_STR);
			$stmt3->bindParam(':username',$brandname, PDO::PARAM_STR);
			$stmt3->bindParam(':title',$title, PDO::PARAM_STR);
			$stmt3->bindParam(':url',$url, PDO::PARAM_STR);
			$stmt3->bindParam(':postId',$postId, PDO::PARAM_STR);
			$stmt3->bindParam(':zipCode',$zip, PDO::PARAM_STR);
			$stmt3->bindParam(':company',$company, PDO::PARAM_STR);
			$stmt3->bindParam(':address',$address, PDO::PARAM_STR);
			
			$stmt3->execute();
			$lastId = $dbh->lastInsertId();
			
			$text = "{$title} position with Popeyes ({$company}) at {$desc} in {$city}, {$state}. Mobile Apply Now!";
			
			
			
			$uSql = "update cec_job set text = :text where postId=458 and twitterid = :ref";
			
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
	$newjobs = Config::get('db')->get_results("select * from cec_job where postId=458");
	$t = "POP";
	foreach($newjobs as $job) {
	//$job_text = "{$job['title']} at {$job['text']}. Mobile Apply Now.";
	
	$job_data = array(
				'twitterId' => $t . $job['twitterId'],
				'city' => filter_var($job['city'], FILTER_SANITIZE_STRING),
				'state' => $job['state'],
				'zipCode' => $job['zipCode'],
				'title' => filter_var($job['title'], FILTER_SANITIZE_STRING),
				'text' => filter_var($job['text'], FILTER_SANITIZE_STRING),
				'hashTags' => filter_var($job['hashTags'], FILTER_SANITIZE_STRING),
				'brand' => $job['brand'],
				'userName' => filter_var($job['userName'], FILTER_SANITIZE_STRING),
				'address' => filter_var($job['rawData'], FILTER_SANITIZE_STRING),
				'campaignId'=>0,
				'jobCats'=>69,
				'postId'=> $job['postId'],
				'urls' => $job['urls']
			);
		Config::get('db')->insert('job',$job_data);
	}
}
				
 
?>