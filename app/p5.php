<?php
include_once '../inc/class.db.php';
include_once '../inc/config.php';
require_once '../inc/class.phpmailer.php';
//require_once '../inc/class.smtp.php';

$applyId = (isset($_REQUEST['i'])) ? $_REQUEST['i'] : '';
$bgColor = '';
$emailAddress = '';
$cc = '';
$yes = "Yes";
$no = "NO";
$emailed = 1;
$resume = '';
$first = '';
$last = '';
$message .='';
$attachment = '';

if($applyId){
$dbMail = Config::get('db') -> get_results("SELECT a.*, 
	ce.city, 
	ce.state_code, 
	c.zip, 
	c.email, 
	u.email as Umail, 
	u.id as Uid, 
	u.first_name as fName, 
	u.website, 
	u.status as acctStatus, 
	c.first_name as first, 
	c.last_name as last, 
	c.mobile, 
	c.zip as zip, 
	s.email as storeEmail, 
	s.cc, 
	s.address as storeAddress, 
	s.storeNum 
	from `candidateApply` a 
	left outer join `account` as u on u.id = a.accountId 
	left join `candidate` as c on c.id = a.candidateId 
	left outer join `cities_extended` as ce on ce.zip = c.zip 
	left outer join `sms_stores` as s on s.id = a.storeId 
	where a.id =$applyId");

if (isset($_REQUEST['app5'])) {	
	$resume = isset($_REQUEST['resume_paste']) ? $_REQUEST['resume_paste'] : '';
}
		$updatedata = array(
			'pasteResume'=>$resume,
			'emailed'=>$emailed
			);
		$updatewhere = array('id'=>$applyId);
	Config::get('db')->update('candidateApply',$updatedata,$updatewhere);

	
	if($dbMail){
	$Uid = $dbMail[0]['Uid'];
	$accountId = $dbMail[0]['accountId'];
	$brandOrig = $dbMail[0]['brand'];
	$storeEmail = $dbMail[0]['storeEmail'];
	$copy = $dbMail[0]['cc'];
	$acctStatus = $dbMail[0]['acctStatus'];
	$website = $dbMail[0]['website'];
	
	if (intval($Uid) == 89) {
		$emailAddress = $dbMail[0]['email'];
	}else if(intval($Uid) !=89 && strlen($storeEmail)>1){
		$emailAddress = $dbMail[0]['storeEmail'];
	}else {
		$emailAddress = $dbMail[0]['Umail'];
	}
	$firstName = $dbMail[0]['fName'];
	$first = $dbMail[0]['first'];
	$last = $dbMail[0]['last'];
	$mobileNum = $dbMail[0]['mobile'];
	$zip = $dbMail[0]['zip'];
	$email = $dbMail[0]['email'];
	$city = $dbMail[0]['city'];
	$st = $dbMail[0]['state_code'];
	$trans = $dbMail[0]['trans'];
	$legal = $dbMail[0]['legal'];
	$age = $dbMail[0]['age'];
	$age21 = $dbMail[0]['over21'];
	$workPermit = $dbMail[0]['workPermit'];
	$position = $dbMail[0]['position'];
	$location = $dbMail[0]['storeAddress'];
	$storenum = $dbMail[0]['storeNum'];
	$education = $dbMail[0]['education'];
	$current = $dbMail[0]['current'];
	$currentLong = $dbMail[0]['currentLong'];
	$currentReference = $dbMail[0]['currentReference'];
	$previous = $dbMail[0]['previous'];
	$pastLong = $dbMail[0]['pastLong'];
	$pastReference = $dbMail[0]['pastReference'];
	$experience = $dbMail[0]['experience'];
	$amount = $dbMail[0]['amount'];
	$perHourYear = $dbMail[0]['perHourYear'];
	$schedule = $dbMail[0]['schedule'];
	$jobType = $dbMail[0]['jobType'];
	$prefer1 = $dbMail[0]['prefer1'];
	$prefer2 = $dbMail[0]['prefer2'];
	$days = $dbMail[0]['day'];
	//$resume = $dbMail[0]['pasteResume'];
	$emailed = $dbMail[0]['emailed'];
	
	if (strtoupper($age)!= strtoupper($no) && !$age21) {
		$updatedata = array(
			'age'=>$yes
			);
		$updatewhere = array('id'=>$applyId);
		Config::get('db')->update('candidateApply',$updatedata,$updatewhere);
		$age = $yes;
	}
	
	
if ($emailAddress && intval($emailed)==0){
		if ($storeEmail){		
		$to = $storeEmail;
		}else{
		$to = $emailAddress;
		}
		
		if ($copy){
		$cc = $copy;
		}else{
		$cc = '';
		}
			  
  if ($position && $city && $st){
$subject = "JobAlarm Candidate: ".$first." ".$last." for ".$position." in ".$city.", ".$st;
  }else{
$subject = "JobAlarm Candidate: ".$first." ".$last;	
}

  if ($age21){
	  $ageAdd = "<td>At least 21:  $age21</td>";
  }else{
	  $ageAdd = "<td>At least 18:  $age</td><td>Work Permit:  $workPermit</td>";
  }

$message .= "
<html>
<head>
<title>HTML email</title>
</head>
<body>
<table>
<tr>
<td>Below are the details received from a JobAlarm Candidate that is interested in employment with your company.</td>
</tr>
<tr>
<th>Candidate Details</th>
</tr>
<tr>
<td>$first $last</td>
</tr>
<tr>
<td>Mobile #:  $mobileNum</td>
<td>Email:  $email</td>
</tr>
<tr>
<td>Lives:  $city $st  $zip</td>
</tr>
<tr>$ageAdd</tr>
<tr>
<td>Education:  $education</td>
</tr>
<tr>
<td>Reliable Transportation:  $trans</td>
<td>Eligible to work in the U.S.:  $legal</td>
</tr>
<tr>
<th>Current Employer Information</th>
</tr>
<tr>
<td>Employer:  $current</td>
</tr>
<tr>
<td>How Long:  $currentLong</td>
</tr>
<tr>
<td>Reference:  $currentReference</td>
</tr>
<tr>
<th>Previous Employer Information</th>
</tr>
<tr>
<td>Employer:  $previous</td>
</tr>
<tr>
<td>How Long:  $pastLong</td>
</tr>
<tr>
<td>Reference:  $pastReference</td>
</tr>
<tr>
<th>Work Info</th>
</tr>
<tr>
<td>Preferred Location:  $location</td>
</tr>
<tr>
<td>Store Number:  $storenum</td>
</tr>
<tr>
<td>Position Desired:  $position</td>
</tr>
<tr>
<td>Experience:  $experience</td>
</tr>
<tr>
<td>Job Type (Full Time, Part Time, Temp):  $jobType</td>
</tr>
<tr>
<td>Flexible Schedule (nights, weekends, etc):  $schedule</td>
</tr>
<tr>
<td>Preferred Shift #1:  $prefer1</td>
</tr>
<tr>
<td>Preferred Shift #2:  $prefer2</td>
</tr>
<tr>
<td>Preferred Days:  $days</td>
</tr>
<tr>
<td>Resume/Skills:  $resume</td>
</tr>
<th></th>
</table>
<tr>
<th>Thank you for using JobAlarm!</th>
</tr><tr></tr><tr></tr>
<tr><p><h5>
NOTE: The information contained in this message may be privileged and confidential and protected from disclosure.  If the reader of this message is not the intended recipient, or an employee or agent responsible for delivering this message to the intended recipient, you are hereby notified that any dissemination, distribution or copying of this communication is strictly prohibited. If you have received this communication in error, please notify us immediately by replying to support@jobalarm.com and deleting it from your computer.
</h5></p></tr>
<tr><p><h5>
Thank you for considering the impact of printing emails on our environment.  Please don’t print unless it is necessary!
</h5></p></tr>


</body>
</html>
";

// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: <noreply@jobalarm.biz>' . "\r\n";
//$userName = "JobAlarm";
//$userEmail = "noreply@jobalarm.biz";

			//echo "send ".$send;
		//$mail = new PHPMailer();
		// Now you only need to add the necessary stuff
		//$mail->CharSet="UTF-8";
        //
		//$mail->SMTPSecure = 'ssl';
		//
		//$mail->IsSendmail();
		// HTML body
		//$mail->FromName = ($userName);
		//$mail->From = ($userEmail);
		//$mail->AddAddress($to, "www.jobalarm.biz");
		//$mail->MsgHTML($message);
		
		//if ($attachment !=''){
		//$mail->AddAttachment($attachment);
		//}
		//$mail->IsHTML(true);                                  // Set email format to HTML
		//$mail->Subject = $subject;
	
		//if(!$mail->Send()) {
		//echo "There was an error sending the message";
		//return false;
		//}
		//echo "Message was sent successfully";
        //echo json_encode(array('success'=>true,'message'=>$to));
        //return true;

mail($to,$subject,$message,$headers);

	$data = array(
			'sentTo'=>$to,
			'sentCC'=>$cc,
			'message'=>$message
			);
	Config::get('db')->insert('email_record',$data);

}
if ($zip && $mobileNum && $brandOrig && intval($acctStatus)) {
	$url = 'http://www.jobalarm.biz/m.php?z=' . $zip . '&m=' . $mobileNum . '&b=' . $brandOrig . '&a=' . $accountId . '&x=27873475837098';
	}else{
	$url = 'http://www.jobalarm.biz/search.php';
	}

//alert('Your information has been successfully submitted to the Company associated with this job.');

	
echo "<script>
window.location.href='$url';
</script>";

}else{
echo "<script>
alert('Thank you for using JobAlarm.');
window.location.href='http://www.jobalarm.biz/search.php';
</script>";
}

}else{
echo "<script>
alert('Thank you for using JobAlarm.');
window.location.href='http://www.jobalarm.biz/search.php';
</script>";
}


	
	

	



?>
