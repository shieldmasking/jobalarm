<?php
include 'inc/class.db.php';
include 'inc/class.jatwitter.php';
include 'inc/config.php';
set_time_limit(0);

$local_file =  '/home/tweetedjobs/feeds/snag/Snagajob-Job-Feed_CUSTOM.XML';


getDataFeed();
parsexml2($local_file);




function getDataFeed(){

	$output_filename = '/home/tweetedjobs/feeds/snag/Snagajob-Job-Feed_CUSTOM.XML.gz';

    $user_name = 'ps-ftp_298234';
	$user_pass ='NDzLGcguGk';


		set_time_limit(0);
		//This is the file where we save the    information
		$fp = fopen ($output_filename, 'w+');
		//Here is the file we are downloading, replace spaces with %20
		//$ch = curl_init(str_replace(" ","%20",$url));
		// $host = "http://datatransfer.cj.com/datatransfer/files/4773591/outgoing/productcatalog/189178/Snagajob-Snagajob_Product_Catalog.txt.gz";
		 $host = "ftp://ps-ftp_298234:NDzLGcguGk@products.impactradius.com/Snagajob/Snagajob-Job-Feed_CUSTOM.XML.gz";
		 $ch = curl_init();
		 curl_setopt($ch, CURLOPT_URL, $host);
		curl_setopt($ch, CURLOPT_TIMEOUT, 50);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERPWD, "$user_name:$user_pass");
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		// write curl response to file
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		// get curl response
		$result = curl_exec($ch);

		if(curl_error($c))
		{
    	echo 'error:' . curl_error($c);
		}

		curl_close($ch);
		fclose($fp);

     unzip($output_filename);
}

function unzip($file_name){
		//This input should be from somewhere else, hard-coded in this example
		//$file_name = 'Snagajob-Job-Feed_CUSTOM.XML.gz';

		// Raising this value may increase performance
		$buffer_size = 4096; // read 4kb at a time
		$out_file_name = str_replace('.gz', '', $file_name);

		// Open our files (in binary mode)
		$file = gzopen($file_name, 'rb');
		$out_file = fopen($out_file_name, 'wb');

		// Keep repeating until the end of the input file
		while (!gzeof($file)) {
		    // Read buffer-size bytes
		    // Both fwrite and gzread and binary-safe
		    fwrite($out_file, gzread($file, $buffer_size));
		}

		// Files are done, close files
		fclose($out_file);
		gzclose($file);

}


function parsexml2($x){

	/*$xml = simplexml_load_file($x);

    print_r($xml);

	return;
	*/
	$z = new XMLReader;
	$z->open($x);
	try{
	$doc = new DOMDocument;
	$cnt=0;
	$brands = getBrands();
	// move to the first <product /> node
	while ($z->read() && $z->name !== 'job');



	// now that we're at the right depth, hop to the next <product/> until the end of the tree
	while ($z->name === 'job')
	{
	    // either one should work
	    //$node = new SimpleXMLElement($z->readOuterXML());
	    $node = simplexml_import_dom($doc->importNode($z->expand(), true));



		//  if ($node->company =='Hardee\'s'){//   strpos($node->description, 'hardee') !== false) {
		// 	     $cnt++;
		// 	   echo "*****************************</br>$node->company, $node->referencenumber, $node->city, $node->state, $node->description,$node->title, $node->lastmodifieddate, $node->url</br>";
		//
		// 	}


		$brandId = getBrandId($node->description, $node->company, $brands);


		// now you can use $node without going insane about parsing
		if($brandId != false){

			//$cnt = 5;
			updateDB($node->referencenumber, $node->city, $node->state, $node->description,$node->title, $node->date, $node->url, $brandId["id"], $brandId["keyword"], $brandId["storeBrand"], $node->postalcode);
		}
	 else{
			$brandId = 6;
			$keyword = 'JOBALARM';
			updateDB($node->referencenumber, $node->city, $node->state, $node->description,$node->title, $node->date, $node->url, $brandId, $node->company, $node->company, $node->postalcode);
				 // do nothing probably
	 }



		  //echo "URL:".$node->url;
	    // now you can use $node without going insane about parsing
	   //updateDB($node->referencenumber, $node->city, $node->state, $node->description,$node->title, $node->lastmodifieddate, $node->url);
	   // echo "*****************************</br>$node->referencenumber, $node->city, $node->state, $node->description,$node->title, $node->lastmodifieddate, $node->url</br>";

	  //  if($cnt==5){return;}
	    // go to next <product />

	    $z->next('job');
	}

	 }catch(Exception $e){
	   echo "error ".$e->getMessage();
	 }
	echo "updated records: ".$cnt;
}


function getBrandId($description, $company, $brands){
	$brandId = false;
	//print_r($brands);
	foreach ($brands as $row) {
		if((strpos(str_replace("'","",$description), str_replace("'","",$row["storeBrand"])) != false) || (strpos(str_replace("'","",$company), str_replace("'","",$row["storeBrand"])) != false)){
			$brandId = $row;
			break;
		}
	}
	return $brandId;
}


function getBrands(){
	include('inc/dbpdo.php');
	$stmt6 = $dbh->prepare("SELECT keyword, id, storeBrand from tweetedj_tweetedjobs.sms_brand where active=1");
	$stmt6->execute();
	$rows = $stmt6->fetchall();

 // $brands = array();
 // $temp;
 // foreach($rows as $row){
 // 	$temp = array();
 // 	$temp["keyword"] = $row->keyword;
 // 	$temp["id"] = $row->id;
 // 	$temp["storeBrand"] = $row->storeBrand;
 // 	array_push($brands, $temp);
 // }

	return $rows;
}

function updateDB($ref,$city,$state,$desc,$title,$postDate,$url, $brand, $keyword, $storeBrand, $zip){
	include 'inc/dbpdo.php';


	$username='JOBALARM';
	$pdate = date('Y-m-d H:i:s', strtotime($postDate));
	$today = date();
	$diff = strtotime($today) - strtotime($postDate);
    $diff_in_hrs = $diff/3600;

	$campaingnid = 0;
	//$brand = 9;

	//$stmt6 = $dbh->prepare("SELECT s.state_code from state s where s.state =:state");
	//$stmt6->bindParam(':state',$state, PDO::PARAM_STR);
//	$stmt6->execute();
//	$row = $stmt6->fetch(PDO::FETCH_ASSOC);
//	echo 111;
	//print_r($row);
	//$state_code= $row["state_code"];

	if($diff >=1 && $diff_in_hrs <=24){

		$campaingnid= 1;
	}

	$stmt = $dbh->prepare('SELECT twitterId FROM job WHERE brand=:brandId and twitterid=:ref');
	$stmt->bindParam(':ref',$ref, PDO::PARAM_STR);
	$stmt->bindParam(':brandId',$brandId, PDO::PARAM_STR);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if($stmt->rowCount() == 0)
		{


			try {

		    $stmt3 = $dbh->prepare("insert into job (twitterId,brand,campaignId,city,state,postDate,username,title,urls,rawData, zipCode)
		    	 					values(:ref,:brand,:campaignId,:city,:state,:postdate,:username,:title,:url, :rawData, :zip)
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
			$stmt3->bindParam(':zip',$zip, PDO::PARAM_STR);
			//$stmt3->bindParam(':city2',$city, PDO::PARAM_STR);
			//$stmt3->bindParam(':title2',$title, PDO::PARAM_STR);

			$stmt3->execute();
		//	echo 3333;
			//echo "  " . $ref . "  ";
			$lastId = $dbh->lastInsertId();
		//	echo 44;
		//	echo "  " . $lastId . "  ";

			$text = "$storeBrand $title position available in {$city}, {$state}.  Click to apply or go to www.jobalarm.com/ja.php?id=$lastId #jobs";


//echo $text;
		//	$uSql = "update job set text = :text where brand=:brandId and campaignid=:campaignid and twitterid = :ref";
			$uSql = "update job set text = :text where id=:id";


			$stmt2 = $dbh->prepare($uSql);

			$stmt2->bindParam(':text',$text, PDO::PARAM_STR);
			$stmt2->bindParam(':id',$lastId, PDO::PARAM_STR);
			//$stmt2->bindParam(':ref',$ref, PDO::PARAM_STR);
			//$stmt2->bindParam(':campaignid',$campaingnid, PDO::PARAM_INT);
			$stmt2->execute();



			}catch(PDOException $e){
				echo "Error: ".$e->getMessage();
			}

		}

}

?>
