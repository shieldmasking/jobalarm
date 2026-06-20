<?php 
session_start();
ini_set('display_errors',1);

//if (!isset($_SESSION['oauth_token'])) {
if (!isset($_SESSION['account'])) {
    header('location: ../../login.php?p=messenger');
}else{
	$account_data = $_SESSION['account'];

//print_r($_SESSION);
   //If(!ISSET($_SESSION[oauth_token])){
   //	  echo '{"success":false,"error":"Your session has timed out"}';
//	  header('location: login.php');
	//} else {


include_once '../../inc/class.db.php';
include_once '../../inc/config.php';


   	 $xaction = $_REQUEST["xaction"];


	  if($xaction=='getMessages'){
		getMessages($account_data['id']);
	}

  else if($xaction=='getConversation'){
  getConversation($account_data['id'], $_REQUEST["candidateId"]);
}

else if($xaction=='sendTextMessage'){
sendTextMessage($_REQUEST["message"],$_REQUEST["mobile"],$_REQUEST["keyword"],$_REQUEST["brandId"],$_REQUEST["candidateId"],$_REQUEST["shortCode"],$account_data['accountId'],$account_data['id']);
}

	else if($xaction==''){

	}


	else{
		echo '{success: false, "error": "Bad Request"}';
		return;
	}
return;

   }


   function getMessages($userId){
   	 $query = "SELECT *, DATE_FORMAT(`msgDate`,'%m/%d/%y %H:%i') as MsgDate";
     $query .= " , (select  message from `sms_messages` ms where ms.userId = {$userId} and (type=3) and ms.candidateId = c.id order by ms.msgDate desc limit 1) as msg";
     $query .= " , (select  DATE_FORMAT(`msgDate`,'%m/%d/%y %H:%i') from `sms_messages` ms where ms.userId = {$userId} and (type=3)  and ms.candidateId = c.id order by ms.msgDate desc limit 1) as lastDate";
     $query .= " FROM `sms_messages`  msg";

   	 $query .= " inner join candidate c on c.id = msg.candidateId";
     $query .= " inner join candidateXref x on x.candidateId = c.id ";
   	 $query .= " where userId={$userId} and (msg.type=3) ";
   	 $query .= " group by msg.candidateId";
   	 $query .= " order by msgDate DESC;";


    $dbData = Config::get('db')->get_results($query);

    if ($dbData && count($dbData)) {
    	$ret = array();
    	$ret["success"] = true;
    	$ret["data"] = $dbData;

       echo JSON_encode($ret);
    }
   }

   function getConversation($userId, $candidateId){
   	 $query = "SELECT msg.*, DATE_FORMAT(msg.msgDate,'%m/%d/%y %H:%i') as MsgDate, c.first_name, c.last_name, c.mobile, x.brandOrig ";

   	 $query .= " FROM `sms_messages` msg";
     $query .= " left join candidate c on c.id = msg.candidateId";
     $query .= " left join candidateXref x on x.candidateId = c.id ";
     $query .= " WHERE msg.type IN (3,1)";
     $query .= " AND userId={$userId}";
     $query .= " and msg.candidateId = {$candidateId}";
	 $query .= " group by id";
     $query .= " order by msgDate ASC";

    $dbData = Config::get('db')->get_results($query);

    if ($dbData && count($dbData)) {
    	$ret = array();
    	$ret["success"] = true;
    	$ret["data"] = $dbData;

       echo JSON_encode($ret);
    }
   }

   function sendTextMessage($message,$mobile,$keyword,$brandId,$candidateId,$shortCode,$accountId,$userId) {
     $mobile = "1".$mobile;
	 $keyword2 = "JOBALARM58046";
	 //$accountId = $account_data['accountId'];
	 //$userId = $account_data['id'];
     //$mobile = "12148500163"; // ryan test number
     //$mobile = "18179158271";// alex test number


     define('SLOOCE_LOGIN', 'jobalarm45');
     define('SLOOCE_PW', 'J8775bcgEE2065');
     //define('SLOOCE_API', 'http://sloocetech.net:8084/spi-war/spi/');


       //$header = 'Content-Type: application/xml';
       $header = Array("Content-Type: application/xml");
       $output = "";
	   
	   if (intval($shortCode)==47711){
            $url = 'http://sloocetech.net:8084/spi-war/spi/jobalarm45/' . $mobile . '/' . $keyword[$k] . '/messages/mt';
        }else{
            $url = 'https://jobalarm.cloud.sloocetech.net/slooce_apps/spi/jobalarm45/' . $mobile . '/' . $keyword2 . '/messages/mt';
        }

       //$url = 'http://sloocetech.net:8084/spi-war/spi/jobalarm45/' . $mobile . '/' . $keyword . '/messages/mt';


       file_put_contents("sendsmsurl.txt",$url);

       $psword = "J8775bcgEE2065";
       $msgId = $mobile . time();

       $smsAlex = "<message id=\"".$msgId."\">";
       $smsAlex .= "<partnerpassword>".$psword."</partnerpassword>";
       $smsAlex .= "<content>" . $message . "</content>";
       $smsAlex .= "</message>";
   $type = 1;

   $data = array(
       'brandId'=>$brandId,
	   'accountId'=>$accountId,
	   'userId'=>$userId,
       'candidateId'=>$candidateId,
       'type'=>$type,
       'message'=>Config::get('db')->filter($message),
       'messageId'=>Config::get('db')->filter($msgId),
       'userId'=>Config::get('db')->filter($userId)
       );
       Config::get('db')->insert('sms_messages',$data);

       file_put_contents("sendsmsxml.txt",$smsAlex);
ECHO 11;
       $output .= curl_request(SLOOCE_LOGIN, $url, $smsAlex, $header);

   return $output;

   }

   function curl_request($user, $url, $postdata = null, $header) {
     echo $url . " 2222 ";
       $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, $url);
       curl_setopt($ch, CURLOPT_POST, 1);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
       //curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
       //curl_setopt($ch, CURLOPT_SSLVERSION, 3);
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
       //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
       curl_setopt($ch, CURLOPT_HTTPHEADER, array($header));
       //curl_setopt($ch, CURLOPT_HEADER, false);
       //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);


       $server_output = curl_exec($ch);
       curl_close($ch);
       echo $server_output;
       return $server_output;
   }



?>
