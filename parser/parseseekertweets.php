<?php

ini_set('display_errors',1);

ini_set('display_startup_errors',1);

ini_set('mongo.native_long', 1);

require_once('lib/codebird.php');

require_once('lib/twitteroauth/autoload.php');

use Abraham\TwitterOAuth\TwitterOAuth;


require_once('cronhelper.php');

function is_blocked($username) {
	$query = "select id from blocklist where '{$username}' like concat(username,'%')";
	$dbData = Config::get('db')->get_results($query);
	return (count($dbData) > 0);
}


//EMPLOYER FUNCTIONS

function can_get_message($username) {
	$query = "select sent_date from notification where username = '{$username}' and (sent_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY))";
	$dbData = Config::get('db')->get_results($query);
	return (count($dbData) > 0) ? false : true;
}

function add_sent_message($username) {
	$data = array('username'=>$username,'sent_date'=>date('Y-m-d H:i:s'));
	Config::get('db')->insert('notification',$data);
}


function last_tweet_count() {
	$query = "SELECT COUNT(*) AS sentcount from tweetedj_tweetedjobs.notification where DATE_SUB(sent_date, INTERVAL 6 HOUR) > DATE_SUB(NOW(), interval 30 minute)";
	$dbData = Config::get('db')->get_results($query);
	if (count($dbData) >0) {
		return $dbData[0]['sentcount'];
	}
	return 80;
}

//SEEKER FUNCTIONS

function can_seeker_get_message($username) {
	$query = "select sent_date from seekernotification where username = '{$username}' and (sent_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY))";
	$dbData = Config::get('db')->get_results($query);
	return (count($dbData) > 0) ? false : true;
}

function add_seeker_sent_message($username) {
	$data = array('username'=>$username,'sent_date'=>date('Y-m-d H:i:s'));
	Config::get('db')->insert('seekernotification',$data);
}


function last_seeker_tweet_count() {
	$query = "SELECT COUNT(*) AS sentcount from tweetedj_tweetedjobs.seekernotification where DATE_SUB(sent_date, INTERVAL 6 HOUR) > DATE_SUB(NOW(), interval 30 minute)";
	$dbData = Config::get('db')->get_results($query);
	if (count($dbData) >0) {
		return $dbData[0]['sentcount'];
	}
	return 80;
}


if(($pid = cronHelper::lock()) !== FALSE) {



    include "lib/class.db.php";

    include "lib/class.jatwitter.php";

    include "inc/config.php";

	//Twitter login
	define('TWITTER_CONSUMER_KEY', 'yGsGc0ep2A3SVz3G6KueZsmGX');
	define('TWITTER_CONSUMER_SECRET', 'mn5DdbZa1uMycnHhnOVQTKGNkVy3l6aBYy8O0DVSjSiurWQzT9');
	define('TWITTER_OAUTH_CALLBACK', 'http://jobalarm.com/twitterlogin.php');

	
	//$connection = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET);
	//$access_token = $connection->oauth("oauth/request_token", array("oauth_callback" => "http://tweetedjobs.com/twitterlogin.php"));
	//$account = $connection->get('account/verify_credentials');


	if (!file_exists('seeker_current_message.txt')) {

		file_put_contents('seeker_current_message.txt','1');

	}


	$seeker_current_message = file_get_contents('seeker_current_message.txt');

    file_put_contents('seekerlastrun.txt',date('Y-m-d H:i:s'));



/////////////////////////////////////////// SEEKERS ////////////////////////////////////////////////

   $seekers = JATwitter::GetSeekerTweets(500);

    $seekers->sort(array('timestamp_ms'=>1));

    $lastId = 0;

    \Codebird\Codebird::setConsumerKey(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET);
    $cb = \Codebird\Codebird::getInstance();
    $cb->setToken("3014533789-d4WbBgZSZ0HGw51mAEOjIDgKm1BP4k6b2Lt3teS", "k5KugeCyDM05gRHpdw4aObsSBZuMkIzrIYLdYZmvSkMm6");
        

    foreach($seekers as $tweet) {
    	if (substr($tweet['text'],0,2) != 'RT') {
/*

@XXXX Need a job?  Search the jobs that Employers are tweeting about right now at www.tweetedjobs.com
@XXXX If you are looking for a job, try www.tweetedjobs.com to find the jobs that Employers are tweeting about
@XXXX www.tweetedjobs.com is the place to go if you are looking for a job.  Search the jobs that Employers are tweeting about.
@XXXX Do you need a new job?  Search the best jobs at www.tweetedjobs.com.  
@XXXX Employers are tweeting their best jobs at www.tweetedjobs.com.  Search jobs by location, title  and more.

*/
			switch($seeker_current_message) {
				case '1':
					$send_message = '@'.$tweet['user']['screen_name'].' Need a job?  Search the jobs that Employers are tweeting about right now at www.jobalarm.com';
					$seeker_current_message = 2;
				break;
				case '2':
					$send_message = '@'.$tweet['user']['screen_name'].' If you are looking for a job, try www.jobalarm.com to find the jobs that Employers are tweeting about.';
					$seeker_current_message = 3;
				break;
				case '3':
					$send_message = '@'.$tweet['user']['screen_name'].' www.jobalarm.com is the place to go if you are looking for a job. Search the jobs that Employers are tweeting about.';
					$seeker_current_message = 4;
				break;
				case '4':
					$send_message = '@'.$tweet['user']['screen_name'].' Do you need a new job? Search the best jobs at www.jobalarm.com';
					$seeker_current_message = 5;
				break;
				case '5':
					$send_message = '@'.$tweet['user']['screen_name'].' Employers are tweeting their best jobs at www.jobalarm.com. Search jobs by location, title and more.';
					$seeker_current_message = 1;
				break;
			}
	 

	        echo "Seeker Tweet Imported : {$tweet['_id']} : ! \r\n";

	        if (last_seeker_tweet_count() < 80 && !is_blocked($tweet['user']['screen_name']) && can_seeker_get_message($tweet['user']['screen_name'])) {
	        	echo "Sending seeker message: {$send_message}\r\n";
			    $params = array(
			      'status' => $send_message
			    );
			    
			    $reply = $cb->statuses_update($params);
		        //var_dump($reply);
		        add_seeker_sent_message($tweet['user']['screen_name']);
	        }

	        file_put_contents('seeker_current_message.txt', $seeker_current_message);

	        $text = JATwitter::CleanString($tweet['text']);

	        $text = JATwitter::RemoveURLs($text);



	        $jobId = JATwitter::ImportSeekerTweet($tweet);

	            // JATwitter::AddTweetIndustryToJob($jobId,$tweet);

	        
		}  

        $lastId = $tweet['_id'];

        //echo $lastId."\r\n";

        //JATwitter::RemoveTweet($tweet['id_str']);







    }

    $result = JATwitter::RemoveSeekerTweets($lastId);    

    echo "finished:\r\n";



    //var_dump($result);

    //echo "\r\n";

    cronHelper::unlock();

}

