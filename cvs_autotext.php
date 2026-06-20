<?php
include 'inc/class.db.php';
include 'inc/config.php';


		
	$dbData = Config::get('db') -> get_results("SELECT j.*, c.latitude, c.longitude, c.zip, a.title, a.id as tid, l.city, l.st FROM `job` j LEFT JOIN `cvs_autotext_locations` as l on l.city = j.city and l.st = j.state LEFT JOIN cities_extended as c on c.city = j.city and c.state_code = j.state LEFT JOIN job_autotext_titles as a on a.brandId = j.brand and a.title = j.title WHERE `brand`=9 and `postDate` > now() - interval 2 day and l.city !='' and a.title = j.title group by j.state, j.city");
    $miles=10;

if (count($dbData) > 0) {
foreach($dbData as $job) {
	
	$lat = $job['latitude'];
	$lon = $job['longitude'];
	$brandId = $job['brand'];
	$title = $job['tid'];
	
	$candidates = Config::get('db')->get_results("SELECT x.*, c.mobile, cg.groupId as cgGroup, c.active, c.zip as czip, COUNT(case m.type when '1' then 1 else null end) + count(case m.type when '2' then 1 else null end) + count(case m.type when '3' then 1 else null end) - count(case m.type when '0' then 1 else null end) AS msgCount FROM `candidateXref` as x LEFT JOIN `candidate` as c on c.id = x.candidateId LEFT OUTER JOIN `candidate_group` as cg on cg.candidateId = x.candidateId LEFT OUTER JOIN `sms_messages` as m on m.candidateId = x.candidateId and m.brandId = x.brandId and m.msgDate BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW() LEFT JOIN cities_extended ce on ce.zip = c.zip WHERE ((3963*ACOS(SIN({$lat}/57.2958)*SIN(ce.latitude/57.2958)+ COS({$lat}/57.2958) *COS(ce.latitude/57.2958)*COS((ce.longitude/57.2958)-({$lon}/57.2958))))<=10 and c.active = 1 and c.mobile != '' and x.promo<3) and (x.brandId=9 or x.brandId=6 or x.brandId=19) GROUP BY c.mobile HAVING msgCount<4 ORDER BY msgCount ASC LIMIT 30");

	if (count($candidates) > 0) {
		
		foreach($candidates as $text){
		$text_data = array(
				'jobId' => $job['id'],
				'trackId' => $job['twitterId'],
				'datePosted' => date('Y-m-d H:i:s'),
				'candidateId' => $text['id']
			);
		Config::get('db')->insert('jobautopostXref',$text_data);
		Config::get('db')->query("update candidateXref set userXref =60 WHERE id ={$text['id']} and (brandId ={$brandId} or brandId =6 or brandId=19)");
		
		$account=258;
		$groupId = 20;
		$data = array(
		'accountId'=>Config::get('db')->filter($account),
		'groupId'=>Config::get('db')->filter($groupId),
		'candidateId'=>Config::get('db')->filter($text['candidateId']),
		'groupdate'=>date('Y-m-d H:i:s')
		);
				
		Config::get('db')->insert('candidate_group',$data); 
				
				
                if ($group > 0) {
                    Group::updateCandidate($account,$candidateId,$group);
                }
		
		$zip = $text['czip'];
		//$message = "CVS just posted a ".$job['title']." position in ".$job['city'].", ".$job['state'].". Be the first to apply at www.jobalarm.com/m1.php?z=".$zip."&b=".$brandId."&t=".$title.". Not a fit?  Reply NOT A FIT";
		$message = "CVS just posted a {$job['title']} position near you. Be the first to apply at www.jobalarm.com/m1.php?z={$zip}&amp;b={$brandId}&amp;t={$title}. Not a fit?  Reply Not4Me";
		
		$mobile = "1".$text['mobile'];
		$type = 1;
		$userId = 60;
		$key = $text['keyword'];
		$now = time();
	
     
       $psword = "J8775bcgEE2065";
	   $slooce = "jobalarm45";
       
	  
		$msgId = $mobile . $now;
		   
		
		$smsAlex = "";
		$smsAlex .= "";
		$smsAlex .= "<message id=\"".$msgId."\">";
       $smsAlex .= "<partnerpassword>".$psword."</partnerpassword>";
       $smsAlex .= "<content>" . $message . "</content>";
       $smsAlex .= "</message>";
	   
	   
		$messages[] = $smsAlex;					
		$numbers[] = $mobile;
		$keywords[] = $key;
	

   $data = array(
       'brandId'=>$brandId,
	   'userId'=>$userId,
       'candidateId'=>$text['candidateId'],
       'type'=>$type,
       'message'=>Config::get('db')->filter($message),
       'messageId'=>Config::get('db')->filter($msgId)
       );
       Config::get('db')->insert('sms_messages',$data);


	}
	$smsoptions = Array(
                'numbers' => $numbers,
                'message' => $messages,
                'keyword' => $keywords,
                'login' => $slooce

            );
            $result = '';

            if ($message && strlen(trim($message)) > 0) {
                
                $result = sendSMS($smsoptions); 
				
                }
				echo json_encode($result);
	}
  }
}
		function sendSMS($options) {
            file_put_contents("sendsmslog.txt", print_r($options,true));
            $post = '';
            $keyword = '';
            $header = Array("Content-Type: application/xml");
    		$mobile = '';
    		$post = $options['message'];
    		$keyword = $options['keyword'];
    		$slooce = $options['login'];
    		$output = "";

            foreach($options['numbers'] as $k => $n) {
         		$mobile = $n;

          		$url = 'http://sloocetech.net:8084/spi-war/spi/jobalarm45/' . $mobile . '/' . $keyword[$k] . '/messages/mt';
				//echo $mobile;
				//echo $keyword[$k];
				//echo $post[$k];
          		//echo $url;
				//echo $header;
          		$output .= curl_request($slooce, $url, $post[$k], $header);
    
       	}
    return $output; 
    }

   function curl_request($user, $url, $postdata = null, $header) {
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_POST, 1);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    //curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	    //curl_setopt($ch, CURLOPT_SSLVERSION, 3);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	    //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	    //curl_setopt($ch, CURLOPT_HEADER, false);
	    //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
	    $server_output = curl_exec($ch);
	    curl_close($ch);
	    echo $server_output;
	    return $server_output;
	}
?>