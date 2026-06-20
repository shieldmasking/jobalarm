<?php
include '../../inc/class.db.php';
include '../../inc/class.jatwitter.php';
include '../../inc/config.php';
include '../../inc/class.phpmailer.php';

define('SLOOCE_LOGIN', 'jobalarm45');
define('SLOOCE_PW', 'wet#%DFG^&FHHJ');
define('SLOOCE_API', 'http://sloocetech.net:8084/spi-war/spi/');

sendReminders();
updateReminders();
sendEmails();
storeEmail();
  
    function sendReminders() {
		
		//echo "day ".intval(date("N"));
         
        if (intval(date("N")) == 1){
		$query = "SELECT 
		c.*, 
		x.keyword2, 
		x.brandOrig,
		x.accountId,
		x.subscribeDate,
		b.storeBrand,
		a.customResponse,
		t.jobId
		FROM `candidate` c 
		left join `candidateXref` as x on x.candidateId = c.id
		left join `sms_brand` as b on b.id = x.brandOrig
		left join `clickTrack` as t on t.candidateId = c.id
		left outer join `account` as a on a.id = x.accountId
		WHERE  c.first_name = '' AND ((c.zip =  '' AND t.jobId IS NULL)OR (c.zip !=  '' AND t.jobId IS NULL))
		and x.subscribeDate >= now() - INTERVAL 74 HOUR
		and x.subscribeDate <= now() - INTERVAL 2 HOUR
		and c.entered >= now() - INTERVAL 196 HOUR
		and x.promo >0
		and x.brandOrig !=264
		GROUP BY c.mobile
		ORDER BY c.entered DESC
            ";
		}else {
		$query = "SELECT 
		c.*, 
		x.keyword2, 
		x.brandOrig,
		x.accountId,
		x.subscribeDate,
		x.shortCode,
		x.candidateId,
		b.storeBrand,
		a.customResponse,
		t.jobId
		FROM `candidate` c 
		left join `candidateXref` as x on x.candidateId = c.id
		left join `sms_brand` as b on b.id = x.brandOrig
		left join `clickTrack` as t on t.candidateId = c.id
		left outer join `account` as a on a.id = x.accountId
		WHERE c.first_name = '' AND ((c.zip =  '' AND t.jobId IS NULL)OR (c.zip !=  '' AND t.jobId IS NULL))
		and x.subscribeDate >= now() - INTERVAL 26 HOUR
		and x.subscribeDate <= now() - INTERVAL 2 HOUR
		and c.entered > now() - INTERVAL 196 HOUR
		and x.promo >0
		and b.active!=3
		GROUP BY c.mobile
		ORDER BY c.entered DESC
            ";
		}
        $dbData = Config::get('db')->get_results($query);
        foreach ($dbData as $remind) {
			$keyword2 = $remind['keyword2'];
			$brandOrig = $remind['brandOrig'];
			$candidateId = $remind['id'];
			$mobile = "1".$remind['mobile'];
			$shortCode = $remind['shortCode'];
			$acct = $remind['accountId'];
			$storeBrand = $remind['storeBrand'];
			$customResponse = $remind['customResponse'];
			$link = "www.jobalarm.com/m.php?z=".$remind['zip']."&m=".$remind['id']."&b=".$brandOrig."";
			
			if ($remind['zip']=='' && $remind['jobId']=='' && $customResponse==''){
			$outmessage = "JobAlarm Reminder...Reply with your zip code to receive great jobs near you or update your profile at http://my.jobalarm.com";
            }else if ($remind['jobId']=='' && $remind['zip']!='' && $customResponse==''){
			$outmessage = "JobAlarm Reminder..Still looking for a new job? Here are the jobs near you ".$link;
            }else if ($customResonse !='') {
			$outmessage = $customResponse;
			}else{
				//do nothing;
			}
			
			
			if (intval($shortCode) == 47711){
			$debug = send_sms_message($outmessage,$mobile,$keyword2,$brandOrig,$candidateId,$acct);
			}else{
			$debug = send_sms_message2($outmessage,$mobile,$keyword2,$brandOrig,$candidateId,$acct);
			}
		
		
		}
        $outArray = array(
            'status' => 'success',
            'total' => "" . count($dbData) . "",
            'records' => $dbData
        );
        echo json_encode($outArray);
        
    }
	
	function updateReminders() {
				
		$query = "SELECT c.*, x.brandOrig, x.accountId, x.subscribeDate, t.jobId, a.id as acct, a.status, p.id as pid FROM `candidate` c left join `candidateXref` as x on x.candidateId = c.id left join `clickTrack` as t on t.candidateId = c.id left join `candidateApply` as p on p.candidateId = c.id left join `account_brand` as ab on ab.brandId = x.brandId left join `account` as a on a.id = x.accountId WHERE ((c.zip = '' AND t.jobId IS NULL AND p.id IS NULL)OR (c.zip != '' AND t.jobId IS NULL AND p.id IS NULL)) and x.subscribeDate >= now() - INTERVAL 72 HOUR and x.subscribeDate <= now() - INTERVAL 48 HOUR  AND a.status <4 AND a.status >1 and c.entered >= now() - INTERVAL 96 HOUR GROUP BY c.mobile ORDER BY c.entered DESC
            ";
		
		$dbApply = Config::get('db')->get_results($query);
		
		foreach ($dbApply as $remind2) {
			$brandOrig2 = $remind2['brandOrig'];
			$candidateId2 = $remind2['id'];
			$acct2 = $remind2['acct'];
			$resume2 = $remind2['resume'];
						
			$data2 = array(
				'brand'=>$brandOrig2,				
				'candidateId'=>$candidateId2,
				'accountId'=>$acct2,
				'pasteResume'=>$resume2
				);				
				Config::get('db')->insert('candidateApply',$data2);
		}
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
        curl_setopt($ch, CURLOPT_HTTPHEADER, array($header));
        //curl_setopt($ch, CURLOPT_HEADER, false);
        //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        $server_output = curl_exec($ch);
        curl_close($ch);
        echo $server_output;
        return $server_output;
    }

    function send_sms_message($message,$mobile,$keyword,$brandId,$candidateId,$acct) {
        $header = 'Content-Type: application/xml';
        $output = "";
        
        $url = 'http://sloocetech.net:8084/spi-war/spi/jobalarm45/' . $mobile . '/' . $keyword . '/messages/mt';

        file_put_contents("sendsmsurl.txt",$url);
        
        $psword = "J8775bcgEE2065";
        $msgId = $mobile . time();
        
        $smsAlex = "<message id=\"".$msgId."\">";
        $smsAlex .= "<partnerpassword>".$psword."</partnerpassword>";
        $smsAlex .= "<content><![CDATA[" . $message . "]]></content>";
        $smsAlex .= "</message>";
		$type = 9;
		
		$data = array(
				'brandId'=>$brandId,
				'accountId'=>$acct,
				'candidateId'=>$candidateId,
				'type'=>$type,
				'message'=>Config::get('db')->filter($message),
				'messageId'=>Config::get('db')->filter($msgId)
				);
				Config::get('db')->insert('sms_messages',$data);

        file_put_contents("sendsmsxml.txt",$smsAlex);
		

        $output .= curl_request(SLOOCE_LOGIN, $url, $smsAlex, $header);
        
		return $output; 
    
    }
	
	function send_sms_message2($message,$mobile,$keyword,$brandId,$candidateId,$acct) {
        $header = 'Content-Type: application/xml';
        $output = "";
		$keyword2 = "JOBALARM58046";
		
		$url = 'https://jobalarm.cloud.sloocetech.net/slooce_apps/spi/jobalarm45/' . $mobile . '/' . $keyword2 . '/messages/mt';
        
        //$url = 'http://sloocetech.net:8084/spi-war/spi/jobalarm45/' . $mobile . '/' . $keyword . '/messages/mt';

        file_put_contents("sendsmsurl.txt",$url);
        
        $psword = "J8775bcgEE2065";
		
        $msgId = $mobile . time();
        
        $smsAlex = "<message id=\"".$msgId."\">";
        $smsAlex .= "<partnerpassword>".$psword."</partnerpassword>";
        $smsAlex .= "<content><![CDATA[" . $message . "]]></content>";
        $smsAlex .= "</message>";
		$type = 9;
		
		$data = array(
				'brandId'=>$brandId,
				'accountId'=>$acct,
				'candidateId'=>$candidateId,
				'type'=>$type,
				'message'=>Config::get('db')->filter($message),
				'messageId'=>Config::get('db')->filter($msgId)
				);
				Config::get('db')->insert('sms_messages',$data);

        file_put_contents("sendsmsxml.txt",$smsAlex);
		usleep(500000);

        $output .= curl_request(SLOOCE_LOGIN, $url, $smsAlex, $header);
        
		return $output; 
    
    }
	
function sendEmails(){
	$cc = '';
	$storeEmail = '';
	
	/*
	SELECT 
	a.*, ce.city, ce.state_code, c.zip, c.email, c.entered, c.resume, u.status, u.email as Umail, u.id as Uid, u.first_name as fName, c.first_name as first, c.last_name as last, c.mobile, c.zip as zip, s.email as storeEmail, s.cc, s.address, s.storeNum, us.first_name as userName, us.email as userEmail
	from `candidateApply` a 
	left join `account` as u on u.id = a.accountId 
	left join `candidate` as c on c.id = a.candidateId
	left join `candidateXref` as x on x.candidateId = a.candidateId
	left outer join `cities_extended` as ce on ce.zip = c.zip
	left outer join `sms_stores` as s on s.id = x.stageId
    left outer join `assign_store` as sa on sa.storeId = s.id
    left outer join `users` as us on us.id = sa.userId
	where a.emailed =0
	and u.status >=2
	and a.applyDate >= now() - INTERVAL 100 HOUR
	and a.applyDate <= now() - INTERVAL 26 HOUR
	and c.entered > now() - INTERVAL 196 HOUR
    group by a.candidateId, us.id*/
	
	if (intval(date("N")) == 1){
	$dbMail = Config::get('db') -> get_results("SELECT 
	a.*, ce.city, ce.state_code, c.zip, c.email, c.entered, c.resume, u.status, u.email as Umail, u.id as Uid, u.first_name as fName, c.first_name as first, c.last_name as last, c.mobile, c.zip as zip, s.email as storeEmail, s.cc, s.address, s.storeNum
	from `candidateApply` a 
	left join `account` as u on u.id = a.accountId 
	left join `candidate` as c on c.id = a.candidateId
	left join `candidateXref` as x on x.candidateId = a.candidateId
	left outer join `cities_extended` as ce on ce.zip = c.zip
	left outer join `sms_stores` as s on s.id = x.stageId
	where a.emailed =0
	and u.status >=2
	and a.applyDate >= now() - INTERVAL 100 HOUR
	and a.applyDate <= now() - INTERVAL 26 HOUR
	and c.entered > now() - INTERVAL 196 HOUR
    group by a.candidateId
	");
	}else{
	$dbMail = Config::get('db') -> get_results("SELECT 
	a.*, ce.city, ce.state_code, c.zip, c.email, c.entered, c.resume, u.status, u.email as Umail, u.id as Uid, u.first_name as fName, c.first_name as first, c.last_name as last, c.mobile, c.zip as zip, s.email as storeEmail, s.cc, s.address, s.storeNum
	from `candidateApply` a 
	left join `account` as u on u.id = a.accountId 
	left join `candidate` as c on c.id = a.candidateId
	left join `candidateXref` as x on x.candidateId = a.candidateId
	left outer join `cities_extended` as ce on ce.zip = c.zip 
	left outer join `sms_stores` as s on s.id = x.stageId
	where a.emailed =0
	and u.status >=2
	and a.applyDate >= now() - INTERVAL 52 HOUR
	and a.applyDate <= now() - INTERVAL 26 HOUR
	and c.entered > now() - INTERVAL 196 HOUR
    group by a.candidateId
	");	
	}
	
	foreach ($dbMail as $send) {
	usleep(500000);
	$cc = $send['cc'];
	
	if($cc){
	$to = $cc;
	$emailAddress = $send['Umail'];
	$storeEmail = $send['storeEmail'];
	$firstName = $send['fName'];
	$first = $send['first'];
	$last = $send['last'];
	$storeaddress = $send['address'];
	$storenum = $send['storeNum'];
	
	if ($first || $last){
		$fullName = "<tr><td>Name:  ".$first." ".$last."</td></tr>";
	} else {
		$fullName = '';
	}
	
	$mobileNum = $send['mobile'];
	$zip = $send['zip'];
	
	if ($storeaddress){
		$store = "<td>Nearest Store:  ".$storeaddress."</td><tr><td>Store Number:  ".$storenum."</td></tr>";
	} else {
		$store = '';
	}
		
	$email = $send['email'];
	if ($email){
		$fullEmail = "<td>Email:  ".$email."</td>";
	} else {
		$fullEmail = '';
	}
	
	$city = $send['city'];
	$st = $send['state_code'];
	if ($city || $st){
		$fullCity = "<tr><td>Location:  ".$city." ".$st." ".$zip."</td></tr>";
	} else {
		$fullCity = '';
	}
	
	
	$trans = $send['trans'];
	if ($trans){
		$fullTrans = "<td>Reliable Transportation:  ".$trans."</td>";
	} else {
		$fullTrans = '';
	}
	$legal = $send['legal'];
	if ($legal){
		$fullLegal = "<td>Eligible to work in the US?:  ".$legal."</td>";
	} else {
		$fullLegal = '';
	}
	
	$age = $send['age'];
	if ($age){
		$fullAge = "<td>At least 18?:  ".$age."</td>";
	} else {
		$fullAge = '';
	}
	$age21 = $send['over21'];
	if ($age21){
		$fullAge = "<td>At least 21?:  ".$age21."</td>";
	} else {
		$fullAge = '';
	}
	
	$workPermit = $send['workPermit'];
	if ($workPermit){
		$fullPermit = "<td>Work Permit?:  ".$workPermit."</td>";
	} else {
		$fullPermit = '';
	}
	
	$education = $send['education'];
	if ($education){
		$fullEducation = "<tr><td>Education:  ".$education."</td></tr>";
	} else {
		$fullEducation = '';
	}
	
	$current = $send['current'];
	$currentLong = $send['currentLong'];
	$currentReference = $send['currentReference'];
	if ($current){
		$fullCurrent = "<tr><th>Current Employer Information</th></tr><tr><td>Employer:  ".$current."</td></tr><tr><td>How Long:  ".$currentLong."</td></tr><tr><td>Reference:  ".$currentReference."</td></tr>";
	} else {
		$fullCurrent = '';
	}
	
	$previous = $send['previous'];
	$pastLong = $send['pastLong'];
	$pastReference = $send['pastReference'];
	if ($previous){
		$fullPrevious = "<tr><th>Previous Employer Information</th></tr><tr><td>Employer:  ".$previous."</td></tr><tr><td>How Long:  ".$pastLong."</td></tr><tr><td>Reference:  ".$pastReference."</td></tr>";
	} else {
		$fullPrevious = '';
	}
	
	$position = $send['position'];
	$location = $send['location'];
	$experience = $send['experience'];
	if($position || $location || $experience){
		$fullInfo = "<tr><th>Work Info</th></tr><tr><td>Preferred Location:  ".$location."</td></tr><tr><td>Position Desired:  ".$position."</td></tr><tr><td>Restaurant Experience:  ".$experience."</td></tr>";
	} else {
		$fullInfo = '';
	}
	
	$amount = $send['amount'];
	$perHourYear = $send['perHourYear'];
	if ($amount){
		$fullAmount = "<tr><td>Min. Expected Pay:  %".$amount." ".$perHourYear."</td></tr>";
	} else {
		$fullAmount = '';
	}	
	
	$jobType = $send['jobType'];
	if ($jobType){
		$fulljobType = "<tr><td>Job Type (Full Time, Part Time, Temp):  ".$jobType."</td></tr>";
	} else {
		$fulljobType = '';
	}
	
	
	$schedule = $send['schedule'];
	$prefer1 = $send['prefer1'];
	$prefer2 = $send['prefer2'];
	$days = $send['day'];
	if ($prefer1 || $prefer2 || $days){
		$fullSchedule = "<tr><td>Flexible Schedule (nights, weekends, etc):  ".$schedule."</td></tr><tr><td>Preferred Shift #1:  ".$prefer1."</td></tr><tr><td>Preferred Shift #2:  ".$prefer2."</td></tr><tr><td>Preferred Days:  ".$days."</td></tr><tr>";
	} else {
		//$fullSchedule = "<tr><td>Flexible Schedule (nights, weekends, etc):  ".$schedule."</td></tr>";
		$fullSchedule = '';
	}	
	
	$resume = $send['resume'];
	if ($resume){
		$fullResume = "<tr><td>Resume/Skills:  ".$resume."</td></tr>";
	} else {
		$fullResume = '';
	}
		/*	
		if ($emailAddress){
			if ($storeEmail && $cc){		
			$to = $storeEmail.", ".$cc;
			}else if($storeEmail && !$cc){
			$to = $storeEmail;
			}else{
			$to = $emailAddress;
			} */
			
		if ($position){
		$subject = "New JobAlarm Candidate for $position";
		}else{
		$subject = "New JobAlarm Candidate";	
		}

		$message = "
		<html>
		<head>
		<title>HTML email</title>
		</head>
		<body>
		<table>
		<tr>
		<td>$firstName,</td>
		</tr>
		<tr>
		<td>Below are the details received from a JobAlarm Candidate that is interested in employment with your company.</td>
		</tr>
		<tr>
		<th>Candidate Details</th>
		</tr>
		$fullName
		<tr>
		<td>Mobile #:  $mobileNum</td>
		$fullEmail
		</tr>
		$fullCity
		<tr>
		$store
		</tr>
		<tr>
		$fullAge
		$fullPermit
		</tr>
		$fullEducation
		<tr>
		$fullTrans
		$fullLegal
		</tr>
		$fullCurrent
		$fullPast
		$fullInfo
		$fullAmount
		$fulljobType
		$fullSchedule
		$fullResume
		<th></th>
		<tr>
		<th>Thank you for using JobAlarm!</th>
		</tr><tr></tr><tr></tr>
		<tr><p><h5>
		NOTE: The information contained in this message may be privileged and confidential and protected from disclosure.  If the reader of this message is not the intended recipient, or an employee or agent responsible for delivering this message to the intended recipient, you are hereby notified that any dissemination, distribution or copying of this communication is strictly prohibited. If you have received this communication in error, please notify us immediately by replying to support@jobalarm.com and deleting it from your computer.
		</h5></p></tr>
		<tr><p><h5>
		Thank you for considering the impact of printing emails on our environment.  Please don’t print unless it is necessary!
		</h5></p></tr>


		</table>
		</body>
		</html>
		";

		// Always set content-type when sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

		// More headers
		$headers .= 'From: <candidates@jobalarm.com>' . "\r\n";

		mail($to,$subject,$message,$headers);
		
		//$to = "rstrenger@jobalarm.com";
		//mail($to,$subject,$message,$headers);
		
		$data = array(
			'emailed'=>2
			);
		$where = array('id'=>$send['id']);
		Config::get('db')->update('candidateApply',$data,$where);
		
		$emailData = array(
			'sentCC'=>$cc,
			'message'=>$message
			);
		Config::get('db')->insert('email_record',$emailData);
		}
	}
		
	}

function storeEmail(){
	$storeEmail = '';
	$cc = '';
		
	if (intval(date("N")) == 1){
	$dbMail = Config::get('db') -> get_results("SELECT 
	a.*, ce.city, ce.state_code, c.zip, c.email, c.entered, c.resume, ac.email as Umail, u.email as storeEmail, u.first_name as fName, c.first_name as first, c.last_name as last, c.mobile, c.zip as zip, s.cc, s.address, s.storeNum
	from `candidateApply` a 
    left join `candidateXref` as x on x.candidateId = a.candidateId
    left outer join `sms_stores` as s on s.id = x.stageId
    left outer join `assign_store` as sa on sa.storeId = s.id 
	left outer join `users` as u on u.id = sa.userId 
    left join `account` as ac on ac.id = a.accountId
	left join `candidate` as c on c.id = a.candidateId
	left outer join `cities_extended` as ce on ce.zip = c.zip
	where (a.emailed =0 or a.emailed=2) 
	and a.applyDate >= now() - INTERVAL 100 HOUR
	and a.applyDate <= now() - INTERVAL 26 HOUR
	and c.entered > now() - INTERVAL 196 HOUR
    group by a.candidateId, sa.userId
	");
	}else{
	$dbMail = Config::get('db') -> get_results("SELECT 
	a.*, ce.city, ce.state_code, c.zip, c.email, c.entered, c.resume, ac.email as Umail, u.email as storeEmail, u.first_name as fName, c.first_name as first, c.last_name as last, c.mobile, c.zip as zip, s.cc, s.address, s.storeNum
	from `candidateApply` a 
    left join `candidateXref` as x on x.candidateId = a.candidateId
    left outer join `sms_stores` as s on s.id = x.stageId
    left outer join `assign_store` as sa on sa.storeId = s.id 
	left outer join `users` as u on u.id = sa.userId 
    left join `account` as ac on ac.id = a.accountId
	left join `candidate` as c on c.id = a.candidateId
	left outer join `cities_extended` as ce on ce.zip = c.zip
	where (a.emailed =0 or a.emailed=2) 
	and a.applyDate >= now() - INTERVAL 52 HOUR
	and a.applyDate <= now() - INTERVAL 26 HOUR
	and c.entered > now() - INTERVAL 196 HOUR
    group by a.candidateId, sa.userId
	");	
	}
	
	foreach ($dbMail as $send) {
	usleep(500000);
	$storeEmail = $send['storeEmail'];
	$cc = $send['cc'];
		
	if($storeEmail){	
	$to = $storeEmail;
	$firstName = $send['fName'];
	$first = $send['first'];
	$last = $send['last'];
	$storeaddress = $send['address'];
	$storenum = $send['storeNum'];
	
	if ($first || $last){
		$fullName = "<tr><td>Name:  ".$first." ".$last."</td></tr>";
	} else {
		$fullName = '';
	}
	
	$mobileNum = $send['mobile'];
	$zip = $send['zip'];
	
	if ($storeaddress){
		$store = "<td>Nearest Store:  ".$storeaddress."</td><tr><td>Store Number:  ".$storenum."</td></tr>";
	} else {
		$store = '';
	}
		
	$email = $send['email'];
	if ($email){
		$fullEmail = "<td>Email:  ".$email."</td>";
	} else {
		$fullEmail = '';
	}
	
	$city = $send['city'];
	$st = $send['state_code'];
	if ($city || $st){
		$fullCity = "<tr><td>Location:  ".$city." ".$st." ".$zip."</td></tr>";
	} else {
		$fullCity = '';
	}
	
	
	$trans = $send['trans'];
	if ($trans){
		$fullTrans = "<td>Reliable Transportation:  ".$trans."</td>";
	} else {
		$fullTrans = '';
	}
	$legal = $send['legal'];
	if ($legal){
		$fullLegal = "<td>Eligible to work in the US?:  ".$legal."</td>";
	} else {
		$fullLegal = '';
	}
	
	$age = $send['age'];
	if ($age){
		$fullAge = "<td>At least 18?:  ".$age."</td>";
	} else {
		$fullAge = '';
	}
	$age21 = $send['over21'];
	if ($age21){
		$fullAge = "<td>At least 21?:  ".$age21."</td>";
	} else {
		$fullAge = '';
	}
	
	$workPermit = $send['workPermit'];
	if ($workPermit){
		$fullPermit = "<td>Work Permit?:  ".$workPermit."</td>";
	} else {
		$fullPermit = '';
	}
	
	$education = $send['education'];
	if ($education){
		$fullEducation = "<tr><td>Education:  ".$education."</td></tr>";
	} else {
		$fullEducation = '';
	}
	
	$current = $send['current'];
	$currentLong = $send['currentLong'];
	$currentReference = $send['currentReference'];
	if ($current){
		$fullCurrent = "<tr><th>Current Employer Information</th></tr><tr><td>Employer:  ".$current."</td></tr><tr><td>How Long:  ".$currentLong."</td></tr><tr><td>Reference:  ".$currentReference."</td></tr>";
	} else {
		$fullCurrent = '';
	}
	
	$previous = $send['previous'];
	$pastLong = $send['pastLong'];
	$pastReference = $send['pastReference'];
	if ($previous){
		$fullPrevious = "<tr><th>Previous Employer Information</th></tr><tr><td>Employer:  ".$previous."</td></tr><tr><td>How Long:  ".$pastLong."</td></tr><tr><td>Reference:  ".$pastReference."</td></tr>";
	} else {
		$fullPrevious = '';
	}
	
	$position = $send['position'];
	$location = $send['location'];
	$experience = $send['experience'];
	if($position || $location || $experience){
		$fullInfo = "<tr><th>Work Info</th></tr><tr><td>Preferred Location:  ".$location."</td></tr><tr><td>Position Desired:  ".$position."</td></tr><tr><td>Restaurant Experience:  ".$experience."</td></tr>";
	} else {
		$fullInfo = '';
	}
	
	$amount = $send['amount'];
	$perHourYear = $send['perHourYear'];
	if ($amount){
		$fullAmount = "<tr><td>Min. Expected Pay:  %".$amount." ".$perHourYear."</td></tr>";
	} else {
		$fullAmount = '';
	}	
	
	$jobType = $send['jobType'];
	if ($jobType){
		$fulljobType = "<tr><td>Job Type (Full Time, Part Time, Temp):  ".$jobType."</td></tr>";
	} else {
		$fulljobType = '';
	}
	
	
	$schedule = $send['schedule'];
	$prefer1 = $send['prefer1'];
	$prefer2 = $send['prefer2'];
	$days = $send['day'];
	if ($prefer1 || $prefer2 || $days){
		$fullSchedule = "<tr><td>Flexible Schedule (nights, weekends, etc):  ".$schedule."</td></tr><tr><td>Preferred Shift #1:  ".$prefer1."</td></tr><tr><td>Preferred Shift #2:  ".$prefer2."</td></tr><tr><td>Preferred Days:  ".$days."</td></tr><tr>";
	} else {
		//$fullSchedule = "<tr><td>Flexible Schedule (nights, weekends, etc):  ".$schedule."</td></tr>";
		$fullSchedule = '';
	}	
	
	$resume = $send['resume'];
	if ($resume){
		$fullResume = "<tr><td>Resume/Skills:  ".$resume."</td></tr>";
	} else {
		$fullResume = '';
	}
		/*	
		if ($emailAddress){
			if ($storeEmail && $cc){		
			$to = $storeEmail.", ".$cc;
			}else if($storeEmail && !$cc){
			$to = $storeEmail;
			}else{
			$to = $emailAddress;
			} */
			
		if ($position){
		$subject = "New JobAlarm Candidate for $position";
		}else{
		$subject = "New JobAlarm Candidate";	
		}

		$message = "
		<html>
		<head>
		<title>HTML email</title>
		</head>
		<body>
		<table>
		<tr>
		<td>$firstName,</td>
		</tr>
		<tr>
		<td>Below are the details received from a JobAlarm Candidate that is interested in employment with your company.</td>
		</tr>
		<tr>
		<th>Candidate Details</th>
		</tr>
		$fullName
		<tr>
		<td>Mobile #:  $mobileNum</td>
		$fullEmail
		</tr>
		$fullCity
		<tr>
		$store
		</tr>
		<tr>
		$fullAge
		$fullPermit
		</tr>
		$fullEducation
		<tr>
		$fullTrans
		$fullLegal
		</tr>
		$fullCurrent
		$fullPast
		$fullInfo
		$fullAmount
		$fulljobType
		$fullSchedule
		$fullResume
		<th></th>
		<tr>
		<th>Thank you for using JobAlarm!</th>
		</tr><tr></tr><tr></tr>
		<tr><p><h5>
		NOTE: The information contained in this message may be privileged and confidential and protected from disclosure.  If the reader of this message is not the intended recipient, or an employee or agent responsible for delivering this message to the intended recipient, you are hereby notified that any dissemination, distribution or copying of this communication is strictly prohibited. If you have received this communication in error, please notify us immediately by replying to support@jobalarm.com and deleting it from your computer.
		</h5></p></tr>
		<tr><p><h5>
		Thank you for considering the impact of printing emails on our environment.  Please don’t print unless it is necessary!
		</h5></p></tr>


		</table>
		</body>
		</html>
		";

		// Always set content-type when sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

		// More headers
		$headers .= 'From: <candidates@jobalarm.com>' . "\r\n";

		mail($to,$subject,$message,$headers);
		
		//$to = "rstrenger@jobalarm.com";
		//mail($to,$subject,$message,$headers);
		
		$data = array(
			'emailed'=>1
			);
		$where = array('id'=>$send['id']);
		Config::get('db')->update('candidateApply',$data,$where);
		
		$emailData = array(
			'sentTo'=>$to,
			'message'=>$message
			);
		Config::get('db')->insert('email_record',$emailData);
		
		}else if(!$storeEmail && !$cc){
		$to = $send['Umail'];
		$firstName = $send['fName'];
		$first = $send['first'];
		$last = $send['last'];
		$storeaddress = $send['address'];
		$storenum = $send['storeNum'];
		
		if ($first || $last){
			$fullName = "<tr><td>Name:  ".$first." ".$last."</td></tr>";
		} else {
			$fullName = '';
		}
		
		$mobileNum = $send['mobile'];
		$zip = $send['zip'];
		
		if ($storeaddress){
			$store = "<td>Nearest Store:  ".$storeaddress."</td><tr><td>Store Number:  ".$storenum."</td></tr>";
		} else {
			$store = '';
		}
			
		$email = $send['email'];
		if ($email){
			$fullEmail = "<td>Email:  ".$email."</td>";
		} else {
			$fullEmail = '';
		}
		
		$city = $send['city'];
		$st = $send['state_code'];
		if ($city || $st){
			$fullCity = "<tr><td>Location:  ".$city." ".$st." ".$zip."</td></tr>";
		} else {
			$fullCity = '';
		}
		
		
		$trans = $send['trans'];
		if ($trans){
			$fullTrans = "<td>Reliable Transportation:  ".$trans."</td>";
		} else {
			$fullTrans = '';
		}
		$legal = $send['legal'];
		if ($legal){
			$fullLegal = "<td>Eligible to work in the US?:  ".$legal."</td>";
		} else {
			$fullLegal = '';
		}
		
		$age = $send['age'];
		if ($age){
			$fullAge = "<td>At least 18?:  ".$age."</td>";
		} else {
			$fullAge = '';
		}
		$age21 = $send['over21'];
		if ($age21){
			$fullAge = "<td>At least 21?:  ".$age21."</td>";
		} else {
			$fullAge = '';
		}
		
		$workPermit = $send['workPermit'];
		if ($workPermit){
			$fullPermit = "<td>Work Permit?:  ".$workPermit."</td>";
		} else {
			$fullPermit = '';
		}
		
		$education = $send['education'];
		if ($education){
			$fullEducation = "<tr><td>Education:  ".$education."</td></tr>";
		} else {
			$fullEducation = '';
		}
		
		$current = $send['current'];
		$currentLong = $send['currentLong'];
		$currentReference = $send['currentReference'];
		if ($current){
			$fullCurrent = "<tr><th>Current Employer Information</th></tr><tr><td>Employer:  ".$current."</td></tr><tr><td>How Long:  ".$currentLong."</td></tr><tr><td>Reference:  ".$currentReference."</td></tr>";
		} else {
			$fullCurrent = '';
		}
		
		$previous = $send['previous'];
		$pastLong = $send['pastLong'];
		$pastReference = $send['pastReference'];
		if ($previous){
			$fullPrevious = "<tr><th>Previous Employer Information</th></tr><tr><td>Employer:  ".$previous."</td></tr><tr><td>How Long:  ".$pastLong."</td></tr><tr><td>Reference:  ".$pastReference."</td></tr>";
		} else {
			$fullPrevious = '';
		}
		
		$position = $send['position'];
		$location = $send['location'];
		$experience = $send['experience'];
		if($position || $location || $experience){
			$fullInfo = "<tr><th>Work Info</th></tr><tr><td>Preferred Location:  ".$location."</td></tr><tr><td>Position Desired:  ".$position."</td></tr><tr><td>Restaurant Experience:  ".$experience."</td></tr>";
		} else {
			$fullInfo = '';
		}
		
		$amount = $send['amount'];
		$perHourYear = $send['perHourYear'];
		if ($amount){
			$fullAmount = "<tr><td>Min. Expected Pay:  %".$amount." ".$perHourYear."</td></tr>";
		} else {
			$fullAmount = '';
		}	
		
		$jobType = $send['jobType'];
		if ($jobType){
			$fulljobType = "<tr><td>Job Type (Full Time, Part Time, Temp):  ".$jobType."</td></tr>";
		} else {
			$fulljobType = '';
		}
		
		
		$schedule = $send['schedule'];
		$prefer1 = $send['prefer1'];
		$prefer2 = $send['prefer2'];
		$days = $send['day'];
		if ($prefer1 || $prefer2 || $days){
			$fullSchedule = "<tr><td>Flexible Schedule (nights, weekends, etc):  ".$schedule."</td></tr><tr><td>Preferred Shift #1:  ".$prefer1."</td></tr><tr><td>Preferred Shift #2:  ".$prefer2."</td></tr><tr><td>Preferred Days:  ".$days."</td></tr><tr>";
		} else {
			//$fullSchedule = "<tr><td>Flexible Schedule (nights, weekends, etc):  ".$schedule."</td></tr>";
			$fullSchedule = '';
		}	
		
		$resume = $send['resume'];
		if ($resume){
			$fullResume = "<tr><td>Resume/Skills:  ".$resume."</td></tr>";
		} else {
			$fullResume = '';
		}
			/*	
			if ($emailAddress){
				if ($storeEmail && $cc){		
				$to = $storeEmail.", ".$cc;
				}else if($storeEmail && !$cc){
				$to = $storeEmail;
				}else{
				$to = $emailAddress;
				} */
				
			if ($position){
			$subject = "New JobAlarm Candidate for $position";
			}else{
			$subject = "New JobAlarm Candidate";	
			}

			$message = "
			<html>
			<head>
			<title>HTML email</title>
			</head>
			<body>
			<table>
			<tr>
			<td>$firstName,</td>
			</tr>
			<tr>
			<td>Below are the details received from a JobAlarm Candidate that is interested in employment with your company.</td>
			</tr>
			<tr>
			<th>Candidate Details</th>
			</tr>
			$fullName
			<tr>
			<td>Mobile #:  $mobileNum</td>
			$fullEmail
			</tr>
			$fullCity
			<tr>
			$store
			</tr>
			<tr>
			$fullAge
			$fullPermit
			</tr>
			$fullEducation
			<tr>
			$fullTrans
			$fullLegal
			</tr>
			$fullCurrent
			$fullPast
			$fullInfo
			$fullAmount
			$fulljobType
			$fullSchedule
			$fullResume
			<th></th>
			<tr>
			<th>Thank you for using JobAlarm!</th>
			</tr><tr></tr><tr></tr>
			<tr><p><h5>
			NOTE: The information contained in this message may be privileged and confidential and protected from disclosure.  If the reader of this message is not the intended recipient, or an employee or agent responsible for delivering this message to the intended recipient, you are hereby notified that any dissemination, distribution or copying of this communication is strictly prohibited. If you have received this communication in error, please notify us immediately by replying to support@jobalarm.com and deleting it from your computer.
			</h5></p></tr>
			<tr><p><h5>
			Thank you for considering the impact of printing emails on our environment.  Please don’t print unless it is necessary!
			</h5></p></tr>


			</table>
			</body>
			</html>
			";

			// Always set content-type when sending HTML email
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

			// More headers
			$headers .= 'From: <candidates@jobalarm.com>' . "\r\n";

			mail($to,$subject,$message,$headers);
			
			//$to = "rstrenger@jobalarm.com";
			//mail($to,$subject,$message,$headers);
			
			$data = array(
				'emailed'=>1
				);
			$where = array('id'=>$send['id']);
			Config::get('db')->update('candidateApply',$data,$where);
			
			$emailData = array(
				'sentTo'=>$to,
				'message'=>$message
				);
			Config::get('db')->insert('email_record',$emailData);
			
			}else{
			//do nothing;
		}
	}
		
	}
	
	


	

    
   
    
    

