<?php
session_start();
ini_set('display_errors',1);
include_once '../inc/class.db.php';
include_once '../inc/class.jatwitter.php';
include_once '../inc/config.php';

if (isset($_SESSION['candidate'])){
    $accountData = $_SESSION['candidate'];
}


if (isset($_REQUEST['app'])) {
    $applyId = isset($_REQUEST['aId']) ? $_REQUEST['aId'] : '';
	$brandOrig = isset($_REQUEST['brandOrig']) ? $_REQUEST['brandOrig'] : '';	
	$resume = isset($_REQUEST['resume_paste']) ? $_REQUEST['resume_paste'] : '';
	$url = isset($_REQUEST['url']) ? $_REQUEST['url'] : '';
	$accountId = isset($_REQUEST['accountId']) ? $_REQUEST['accountId'] : '';
}
$acctDb = Config::get('db')->get_results("SELECT * FROM `account` where `id`='{$accountId}'");

if (!isset($_SESSION['candidate'])) {
//$dbMobile = Config::get('db')->get_results("SELECT c.*, x.id as xid, s.id as brandOrig, s.storeBrand, s.twitterDemo, s.color, s.keyword, s.positions, s.storeImage from `candidate` c left outer join `candidateXref` as x on x.candidateId = c.id and x.brandOrig ={$brandOrig} left join `sms_brand` as s on s.id ={$brandOrig} where c.mobile ={$mobile}");
$dbMobile = Config::get('db')->get_results("SELECT * from `sms_brand` where id=51");


$brColor = ($dbMobile[0]['color']) ? $dbMobile[0]['color'] : '';
$image = ($dbMobile[0]['storeImage']) ? $dbMobile[0]['storeImage'] : 'logo1.png';

}else{
$image = ($accountData['storeImage']) ? $accountData['storeImage'] : '';
$brColor = ($accountData['color']) ? $accountData['color'] : '';
}

if (intval($accountId)==391){
$image = $acctDb[0]['logo'];
}else{
$image = ($dbMobile[0]['storeImage']) ? $dbMobile[0]['storeImage'] : '';	
}

	
	$bgColor = '';
	$emailAddress = '';
	$cc = '';
	$yes = "Yes";
	$no = "NO";
	$emailed = 1;

	if (strlen($brColor)>0) {
	$bgColor = " style=\"background-color:#".$brColor."\"";	
}
   
	$dbMail = Config::get('db') -> get_results("SELECT a.*, ce.city, ce.state_code, c.zip, c.email, u.email as Umail, u.id as Uid, u.first_name as fName, u.website, u.status as acctStatus, c.first_name as first, c.last_name as last, c.mobile, c.zip as zip, s.email as storeEmail, s.cc, s.address as storeAddress, s.storeNum from `candidateApply` a left outer join `account` as u on u.id = a.accountId left outer join `candidate` as c on c.id = a.candidateId left outer join `cities_extended` as ce on ce.zip = c.zip left outer join `sms_stores` as s on s.id = a.storeId where a.id ={$applyId}");
	$Uid = $dbMail[0]['Uid'];
	$storeEmail = $dbMail[0]['storeEmail'];
	$cc = $dbMail[0]['cc'];
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
	
	if (strtoupper($age) !=strtoupper($no) && !$age21) {
		$updatedata = array(
			'age'=>$yes
			);
		$updatewhere = array('id'=>$applyId);
		Config::get('db')->update('candidateApply',$updatedata,$updatewhere);
		$age = $yes;
	}
	
	
if ($emailAddress && intval($emailed)==0){
		if ($storeEmail && $cc){		
		$to = $storeEmail.", ".$cc;
		}else if($storeEmail && !$cc){
		$to = $storeEmail.", ".$emailAddress;
		}else{
		$to = $emailAddress;
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
$headers .= 'From: <candidates@jobalarm.com>' . "\r\n";

mail($to,$subject,$message,$headers);

//$to = "rstrenger@jobalarm.com";
//mail($to,$subject,$message,$headers);

if ($applyId) {
		$updatedata = array(
			'pasteResume'=>$resume,
			'emailed'=>1
			);
		$updatewhere = array('id'=>$applyId);
		Config::get('db')->update('candidateApply',$updatedata,$updatewhere);
		
		$data = array(
			'sentTo'=>$to,
			'sentCC'=>$cc,
			'message'=>$message
			);
		Config::get('db')->insert('email_record',$data);
	
			
	}


}



	if ($website && intval($acctStatus)==4){
	$url = $website;
	}else if ($zip && $mobileNum && $brandOrig && intval($acctStatus) !=4) {
	$url = "http://www.jobalarm.com/m.php?z=$zip&m=$mobileNum&b=$brandOrig&g=2&a=$accountId";
	}else {
		$url = "http://www.jobalarm.com/search.php";
	}
	
	echo "<script>
alert('Youre information has been successfully submitted to the Company associated with this job.');
window.location.href='$url';
</script>";

session_destroy();
exit ();	



?>
