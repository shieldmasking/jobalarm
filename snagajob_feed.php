<?php
include 'inc/class.db.php';
include 'inc/class.jatwitter.php';
include 'inc/config.php';

use Abraham\TwitterOAuth\TwitterOAuth;

define('DEFAULT_URL','https://cecentertainment.mobolt.com/job/json?auth_token=FxAsPNyjBDQjuJaNBPOjO5pyUJFtt3YWnQTxPcoF');
function get_feed_data($url,&$outarray) {
	echo $url."<br />";
	$json_input = file_get_contents($url); 	
	$json_decode = json_decode($json_input,true);
	$json_data = $json_decode['data'];
	$start = $json_data['start'];
	$end = $json_data['end'];
	$total = $json_data['total_results'];
	$totalpages = ceil($total/100);
	$current_page = $json_data['page_number'];
	echo $start." : ".$end." : ".$total." : ".$totalpages." : ".$current_page."<br />";
	$outarray = array_merge($outarray,$json_data['results']);
	if ($end < $total) {
		$new_url = DEFAULT_URL."&page=".($current_page+1);
		get_feed_data($new_url,$outarray);
	} 
} 
$job_data = array();
get_feed_data(DEFAULT_URL,$job_data);
foreach($job_data as $job) {
	$dupe_check = Config::get('db')->get_results("select id from cec_job where twitterId='".$job['Job']['reqID']."'");
	if (!(count($dupe_check) > 0)) {
		$job_data = array(
				'twitterId' => $job['Job']['reqID'],
				'postDate' => date('Y-m-d H:i:s'),
				'city' => $job['Job']['city'],
				'state' => $job['Job']['state'],
				'zipCode' => $job['Job']['zip'],
				'text' => $job['Job']['jobtitle'],
				'title' => $job['Job']['jobtitle'],
				'brand' => 7,
				'userName' => "CEC_Careers",
				'campaignId'=>88,
				'urls' => $job['Job']['moboltApplyUrl']
			);
		Config::get('db')->insert('cec_job',$job_data);
		$job_text = "Chuck E Cheese\'s is hiring {$job['Job']['jobtitle']} in {$job['Job']['city']}, {$job['Job']['state']}.  Apply here {$job['Job']['moboltApplyUrl']} #JobAlarm";
		$job_data = array(
				'twitterId' => $job['Job']['reqID'],
				'postDate' => date('Y-m-d H:i:s'),
				'city' => $job['Job']['city'],
				'state' => $job['Job']['state'],
				'zipCode' => $job['Job']['zip'],
				'title' => $job['Job']['jobtitle'],
				'text' => $job_text,
				'brand' => 7,
				'userName' => "CEC_Careers",
				'campaignId'=>88,
				'urls' => $job['Job']['moboltApplyUrl']
			);
		Config::get('db')->insert('job',$job_data);
			
		$connection = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, '3237288151-qYy7armaxcM1b5nNwfzzn6x2FG2aGm9yxoYXCcM','4wpCSOlVfZFCWJbKOKdolmhCpKuGdSMAtZUB6hyFfvmLw');
        $twitter_data = (array) $connection->get("account/verify_credentials", array("include_entities" => false, "skip_status" => true));    
            
        $result = $connection->post('statuses/update',array('status'=>$job_text));
        var_dump($result);
	}
}
?>