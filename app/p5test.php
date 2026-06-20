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
}

if (!isset($_SESSION['candidate'])) {
//$dbMobile = Config::get('db')->get_results("SELECT c.*, x.id as xid, s.id as brandOrig, s.storeBrand, s.twitterDemo, s.color, s.keyword, s.positions, s.storeImage from `candidate` c left outer join `candidateXref` as x on x.candidateId = c.id and x.brandOrig ={$brandOrig} left join `sms_brand` as s on s.id ={$brandOrig} where c.mobile ={$mobile}");
$dbMobile = Config::get('db')->get_results("SELECT * from `sms_brand` where id={$brandOrig}");

$image = ($dbMobile[0]['storeImage']) ? $dbMobile[0]['storeImage'] : '';
$brColor = ($dbMobile[0]['color']) ? $dbMobile[0]['color'] : '';

}else{
$image = ($accountData['storeImage']) ? $accountData['storeImage'] : '';
$brColor = ($accountData['color']) ? $accountData['color'] : '';
}

/////Test Data
	
	$bgColor = '';
	$emailAddress = '';
	$cc = '';
	$yes = "Yes";
	$no = "NO";
	$emailed = 1;

	if (strlen($brColor)>0) {
	$bgColor = " style=\"background-color:#".$brColor."\"";	
}
   
	$dbMail = Config::get('db') -> get_results("SELECT a.*, ce.city, ce.state_code, c.zip, c.email, u.email as Umail, u.id as Uid, u.first_name as fName, u.website, u.status as acctStatus, c.first_name as first, c.last_name as last, c.mobile, c.zip as zip, s.email as storeEmail, s.cc, s.address as storeAddress from `candidateApply` a left outer join `account` as u on u.id = a.accountId left outer join `candidate` as c on c.id = a.candidateId left outer join `cities_extended` as ce on ce.zip = c.zip left outer join `sms_stores` as s on s.id = a.storeId where a.id =$applyId");
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
	
	if ($first || $last){
		$fullName = "<tr><td>Name:  ".$first." ".$last."</td></tr>";
	} else {
		$fullName = '';
	}
	
	$mobileNum = $dbMail[0]['mobile'];
	$zip = $dbMail[0]['zip'];
	
	$email = $dbMail[0]['email'];
	if ($email){
		$fullEmail = "<td>Email:  ".$email."</td>";
	} else {
		$fullEmail = '';
	}
	
	$city = $dbMail[0]['city'];
	$st = $dbMail[0]['state_code'];
	if ($city || $st){
		$fullCity = "<tr><td>Location:  ".$city." ".$st."</td></tr>";
	} else {
		$fullCity = '';
	}
	
	$trans = $dbMail[0]['trans'];
	if ($trans){
		$fullTrans = "<td>Reliable Transportation:  ".$trans."</td>";
	} else {
		$fullTrans = '';
	}
	
	$legal = $dbMail[0]['legal'];
	if ($legal){
		$fullLegal = "<td>Eligible to work in the US?:  ".$legal."</td>";
	} else {
		$fullLegal = '';
	}
	$age = $dbMail[0]['age'];
	if ($age){
		$fullAge = "<td>Over 18?:  ".$age."</td>";
	} else {
		$fullAge = '';
	}
	$workPermit = $dbMail[0]['workPermit'];
	if ($workPermit){
		$fullPermit = "<td>Work Permit?:  ".$workPermit."</td>";
	} else {
		$fullPermit = '';
	}
	
	
	$education = $dbMail[0]['education'];
	if ($education){
		$fullEducation = "<tr><td>Education:  ".$education."</td></tr>";
	} else {
		$fullEducation = '';
	}
	
	$current = $dbMail[0]['current'];
	$currentLong = $dbMail[0]['currentLong'];
	$currentReference = $dbMail[0]['currentReference'];
	if ($current){
		$fullCurrent = "<tr><th>Current Employer Information</th></tr><tr><td>Employer:  ".$current."</td></tr><tr><td>How Long:  ".$currentLong."</td></tr><tr><td>Reference:  ".$currentReference."</td></tr>";
	} else {
		$fullCurrent = '';
	}
	
	$previous = $dbMail[0]['previous'];
	$pastLong = $dbMail[0]['pastLong'];
	$pastReference = $dbMail[0]['pastReference'];
	if ($previous){
		$fullPrevious = "<tr><th>Previous Employer Information</th></tr><tr><td>Employer:  ".$previous."</td></tr><tr><td>How Long:  ".$pastLong."</td></tr><tr><td>Reference:  ".$pastReference."</td></tr>";
	} else {
		$fullPrevious = '';
	}
	
	$position = $dbMail[0]['position'];
	$location = $dbMail[0]['storeAddress'];
	$experience = $dbMail[0]['experience'];
	if($position || $location || $experience){
		$fullInfo = "<tr><th>Work Info</th></tr><tr><td>Preferred Location:  ".$location."</td></tr><tr><td>Position Desired:  ".$position."</td></tr><tr><td>Restaurant Experience:  ".$experience."</td></tr>";
	} else {
		$fullInfo = '';
	}
	
	$amount = $dbMail[0]['amount'];
	$perHourYear = $dbMail[0]['perHourYear'];
	if ($amount){
		$fullAmount = "<tr><td>Min. Expected Pay:  %".$amount." ".$perHourYear."</td></tr>";
	} else {
		$fullAmount = '';
	}
	
	$jobType = $dbMail[0]['jobType'];
	if ($jobType){
		$fulljobType = "<tr><td>Job Type (Full Time, Part Time, Temp):  ".$jobType."</td></tr>";
	} else {
		$fulljobType = '';
	}
	
	$schedule = $dbMail[0]['schedule'];
	$prefer1 = $dbMail[0]['prefer1'];
	$prefer2 = $dbMail[0]['prefer2'];
	$days = $dbMail[0]['day'];
	if ($prefer1 || $prefer2 || $days){
		$fullSchedule = "<tr><td>Flexible Schedule (nights, weekends, etc):  ".$schedule."</td></tr><tr><td>Preferred Shift #1:  ".$prefer1."</td></tr><tr><td>Preferred Shift #2:  ".$prefer2."</td></tr><tr><td>Preferred Days:  ".$days."</td></tr><tr>";
	} else {
		$fullSchedule = "<tr><td>Flexible Schedule (nights, weekends, etc):  ".$schedule."</td></tr>";
	}
	
	$emailed = $dbMail[0]['emailed'];
	
	if (strtoupper($age) != strtoupper($no)) {
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

$to = "rstrenger@jobalarm.com";
mail($to,$subject,$message,$headers);

if ($applyId) {
		$updatedata = array(
			'pasteResume'=>$resume,
			'emailed'=>1
			);
		$updatewhere = array('id'=>$applyId);
		Config::get('db')->update('candidateApply',$updatedata,$updatewhere);
			
	}


}



	if ($website && intval($acctStatus)==4){
	$url = $website;
	}else if ($zip && $mobileNum && $brandOrig && intval($acctStatus) !=4) {
	$url = "http://www.jobalarm.com/m.php?z=$zip&m=$mobileNum&b=$brandOrig";
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
