<?php
session_start();
include_once '../../inc/dbpdo.php';

$xaction = $_REQUEST["xaction"];
$account = $_SESSION['account'];

if($xaction == "getBrands"){
	$query = "
		select brand.id, brand.storeBrand from tweetedj_tweetedjobs.account  ac
		inner join tweetedj_tweetedjobs.sms_brand brand on brand.id = ac.brandId or brand.id = ac.brandId2
		where ac.id = ".$account["accountId"].";
	";

	try {
   $stmt  = $dbh->prepare($query);
   $stmt->execute();
   $dat = $stmt->fetchAll(PDO::FETCH_ASSOC);
  // echo json_encode($dat);
   
   $ret = array();
   
   $ret["success"] = true;
   $ret["data"] = $dat;
   
  // print_r($ret);
  echo json_encode($ret);
   return ;
}catch (PDOException $e){
	 $ret["success"] = false;
   $ret["error"] = $e->getMessage();

  echo json_encode($ret);
  return;
}
$ret = array();
   
   $ret["success"] = false;
   $ret["error"] = "error after output not in catch";
   
  // print_r($ret);
  echo json_encode($ret);
  return;
}


else if($xaction == "getIncomingData"){
	$brandIds = $_REQUEST["brandId"];
	$query = "
		SELECT *, count(type) as typeCount
        FROM tweetedj_tweetedjobs.candidateXref 
        where brandId in (". $brandIds .") 
        or brandOrig in (". $brandIds .")     
        group by type;
	";

	try {
   $stmt  = $dbh->prepare($query);
   $stmt->execute();
   $dat1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
   }catch (PDOException $e){
	 $ret["success"] = false;
	 $ret["error"] = $e->getMessage();

  echo json_encode($ret);
  return;
}

$query1 = "
		SELECT type, count(type) as typeCount FROM tweetedj_tweetedjobs.sms_messages where brandId in (". $brandIds .")  group by type;
	";

	try {
   $stmt  = $dbh->prepare($query1);
   $stmt->execute();
   $dat2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
   }catch (PDOException $e){
	 $ret["success"] = false;
	 $ret["error"] = $e->getMessage();

  echo json_encode($ret);
  return;
}
   $ret = array();
   
   $ret["success"] = true;
   $ret["data"] = $dat1;
   $ret["data1"] = $dat2;
   
  // print_r($ret);
  echo json_encode($ret);
   return ;

$ret = array();
   
   $ret["success"] = false;
   $ret["error"] = "error after output not in catch";
   
  // print_r($ret);
  echo json_encode($ret);
  return;
}



else if($xaction == "getDataForRange"){
	$brandIds = $_REQUEST["brandId"];
	$days = $_REQUEST["days"];
	$today = date('Y-m-d');
	$pastOfToday = date('Y-m-d',(strtotime ( '-'. $days .' day' , strtotime ( $today) ) ));
	
	//echo $pastOfToday . "  ";
	//echo $today;
	
	$query = "		
		SELECT *, count(type) as typeCount
		FROM tweetedj_tweetedjobs.candidateXref  WHERE subscribeDate BETWEEN '".$pastOfToday."' and '".$today."'
         and (brandId in (". $brandIds .") 
        or brandOrig in (". $brandIds ."))
        group by type;
	";

	try {
   $stmt  = $dbh->prepare($query);
   $stmt->execute();
   $dat1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
   }catch (PDOException $e){
	 $ret["success"] = false;
	 $ret["error"] = $e->getMessage();

  echo json_encode($ret);
  return;
}

$query1 = "
		 SELECT type, count(type) as typeCount 
        FROM tweetedj_tweetedjobs.sms_messages 
        WHERE msgDate BETWEEN '".$pastOfToday."' and '".$today."' 
        and brandId in (". $brandIds .")  
        group by type;
	";

	try {
   $stmt  = $dbh->prepare($query1);
   $stmt->execute();
   $dat2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
   }catch (PDOException $e){
	 $ret["success"] = false;
	 $ret["error"] = $e->getMessage();

  echo json_encode($ret);
  return;
}
   $ret = array();
   
   $ret["success"] = true;
   $ret["data"] = $dat1;
   $ret["data1"] = $dat2;
   
  // print_r($ret);
  echo json_encode($ret);
   return ;

$ret = array();
   
   $ret["success"] = false;
   $ret["error"] = "error after output not in catch";
   
  // print_r($ret);
  echo json_encode($ret);
  return;
}



else{
	$ret["success"] = false;
   $ret["error"] = "invalid request";
   echo json_encode($ret);
   return;
}



?>