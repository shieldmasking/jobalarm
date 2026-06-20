<?php

//ini_set('display_errors',1);
session_start();

/*if (!isset($_SESSION['account'])) {
    exit();
}
$account_data = $_SESSION['account'];*/

if (isset($_SESSION['account'])) {
    $account_data = $_SESSION['account'];
}else if(isset($_SESSION['profile'])) {
	$account_data = $_SESSION['profile'];
	}
	else{
	exit();
	}

include_once 'inc/class.db.php';
include_once 'inc/class.jatwitter.php';
include_once 'inc/config.php';
//eztext configuration
define('EZ_LOGIN', 'rstrenger');
define('EZ_PASSWORD', 'Premier2000!');
define('EZ_URL', 'https://app.eztexting.com/incoming-messages?format=json&');
define('EZ_SEND_URL', 'https://app.eztexting.com/sending/messages?format=json&User=' . EZ_LOGIN . '&Password=' . EZ_PASSWORD);
define('EZ_FILE_URL', 'http://surveys.walkupscreener.com/media/assets/survey-uploads/');
define('EZ_VOICE_URL', 'https://app.eztexting.com/api/voicemessages');
define('EZ_CALLER_ID', '2149343360');

define('SLOOCE_LOGIN', 'jobalarm45');
define('SLOOCE_PW', 'wet#%DFG^&FHHJ');
define('SLOOCE_API', 'http://sloocetech.net:8084/spi-war/spi/');
define('SLOOCE_SEND_URL', 'http://sloocetech.net:8084/spi-war/spi/' . SLOOCE_LOGIN . '/' . $numberlist . '/' . $keyword . '/messages/mt');

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

function send_messages_with_optout($smsAlex,$numberlist,$keywords) {
    $post = '';
    $key = '';
    $mobile = '';
    $header = 'Content-Type: application/xml';
    $key = $keywords;
    $post = $smsAlex;
    $output = "";
	

    foreach($numberlist as $k => $n) {
         $mobile = $n;
         //echo $key[$k];
   
    $url = 'http://sloocetech.net:8084/spi-war/spi/jobalarm45/' . $mobile . '/' . $key[$k] . '/messages/mt';
    $output .= curl_request(SLOOCE_LOGIN, $url, $post[$k], $header);
    
    }
   
    return $output; 
    
}

function getCampaign($campaignId) {
    $campaignData = Config::get('db')->get_results("select * from campaign where id={$campaignId}");
    if ($campaignData && count($campaignData) > 0) {
        return $campaignData[0];
    }
    return false;
}

function getAccount($account_id) {
    $query = "select * from account where id={$account_id}";
    $dbData = Config::get('db')->get_results($query);
    if ($dbData && count($dbData)) {
        return $dbData[0];
    }
    return false;
}

function getDailyTransactionTotal($campaignId) {
    $query = "select sum(amount) as total from transaction where transactionDate >= date_sub(now(),interval 24 hour) and campaignId={$campaignId}";
    $dbdata = Config::get('db')->get_results($query);
    if ($dbData && count($dbData)) {
        return $dbData[0]['total'];
    }
    return 0;
}

function addCampaignTransaction($campaignId,$amount) {

}

function getZiplist($zipCode, $distance) {
        //  die($zipCode.' : '.$distance);
        $result = Config::get('db')->get_results("select zip,latitude,longitude from cities_extended where zip='{$zipCode}'");
        if ($result) {
            $res = $result[0];
            if (count($res) > 0) {
                $lat1 = $res['latitude'];
                $lon1 = $res['longitude'];
                $d = $distance;
                $r = 3959;
                $latN = rad2deg(asin(sin(deg2rad($lat1)) * cos($d / $r) + cos(deg2rad($lat1)) * sin($d / $r) * cos(deg2rad(0))));
                $latS = rad2deg(asin(sin(deg2rad($lat1)) * cos($d / $r) + cos(deg2rad($lat1)) * sin($d / $r) * cos(deg2rad(180))));
                $lonE = rad2deg(deg2rad($lon1) + atan2(sin(deg2rad(90)) * sin($d / $r) * cos(deg2rad($lat1)), cos($d / $r) - sin(deg2rad($lat1)) * sin(deg2rad($latN))));
                $lonW = rad2deg(deg2rad($lon1) + atan2(sin(deg2rad(270)) * sin($d / $r) * cos(deg2rad($lat1)), cos($d / $r) - sin(deg2rad($lat1)) * sin(deg2rad($latN))));
                $zipres = Config::get('db')->get_results("SELECT * FROM cities_extended WHERE (latitude <= $latN AND latitude >= $latS AND longitude <= $lonE AND longitude >= $lonW) AND city != '' ORDER BY state_code, city, latitude, longitude");
                foreach ($zipres as $zip) {
                    $ziplist[] = "'".$zip['zip']."'";
                }
                return "(" . implode(',', $ziplist) . ")";
            }
        }
        
        return null;
    }


////////////////////////////////////////////////
//////// CLICK TRACK

if (isset($_GET['ct'])) {
    $jobId = $_REQUEST['jid'];
    $referrer = (isset($_REQUEST['ref'])) ? $_REQUEST['ref'] : 'web';

    switch($referrer) {
    	case 'fb' : 
        	Config::get('db')->query("insert into clicks (jobId,facebook) values({$jobId},1) on duplicate key update facebook=facebook+1");
    	break;
    	case 'txt' :
        	Config::get('db')->query("insert into clicks (jobId,text) values({$jobId},1) on duplicate key update text=text+1");
    	break;
    	case 'web' :
    	default:
        	Config::get('db')->query("insert into clicks (jobId,jobalarm) values({$jobId},1) on duplicate key update jobalarm=jobalarm+1");
    	break;
    }
    //var_dump($_REQUEST);
    //$dbData = Config::get('db')->get_results("select j.id,j.campaignId from job j left join campaign c on j.campaignId=c.id where j.id={$jobId}");
    // if ($dbData && count($dbData) > 0) {
    //     $jobId = (isset($dbData[0]['id'])) ? $dbData[0]['id'] : 0;
    //     $campaignId = (isset($dbData[0]['campaignId'])) ? $dbData[0]['campaignId'] : 0;
    //     if ($jobId > 0) {
    //         Config::get('db')->query("update job set numClicks = numClicks + 1 where id={$jobId}");
    //         if ($campaignId > 0) {
    //             $campaign = getCampaign($campaignId);
    //             if ($campaign) {
    //                 $dailyBudget = $campaign['daily_budget'];
    //                 $clickBudget = $campaign['click_budget'];
                    
    //                 $txTotal = getDailyTransactionTotal($campaignId);                   
                    
    //                 $account = getAccount($campaign['account_id']);
                    
    //                 $accountBalance = $account['balance'];
                    
                    
    //                 //business rules
                    
    //                 //check if campaign has reached it's daily budget
    //                 if ($txTotal + $clickBudget > $dailyBudget) {
    //                     return false;
    //                     //we are at max budget or the day
    //                 }
                    
    //                 //check if click will bankrupt account
    //                 if ($clickBudget > $accountBalance) {
    //                     return false;
    //                     //we are not able to process the transaction due to it creating a negative balance                        
    //                 }
                    
    //                 //create transaction
    //                 Config::get('db')->query("update account set balance=balance-'{$clickBudget}' where id=".$account['id']);
                    
    //                 $transaction = array(
    //                     'accountId' => $campaign['account_id'],
    //                     'campaignId' => $campaignId,
    //                     'jobId' => $jobId,
    //                     'transactionDate' => date('Y-m-d H:i:s'),
    //                     'amount' => $clickBudget
    //                 );
                    
    //                 Config::get('db')->insert('transaction',$transaction);
                    
    //                 echo json_encode(array('success'=>true));
    //                 return true;
    //             }
    //         }        
    //     }
    // }
}


////////////////////////////////////////////////
//////// Get Jobs from milage radius


if (isset($_REQUEST['jf'])) {
    $jobId = $_REQUEST['jid'];
	echo "here $jobId";
       $dbData = Config::get('db')->get_results("select j.id,j.campaignId from job j left join campaign c on j.campaignId=c.id where j.id={$jobId}");
     echo 'got it';
   echo $dbData;
}

////////////////////////////////////////////////
//////// CLICK TRACK TEXT

if (isset($_GET['cx'])) {
    $jobId = $_REQUEST['jid'];
    $dbData = Config::get('db')->get_results("select j.id,j.campaignId from job j left join campaign c on j.campaignId=c.id where j.id={$jobId}");
    if ($dbData && count($dbData) > 0) {
        $jobId = (isset($dbData[0]['id'])) ? $dbData[0]['id'] : 0;
        $campaignId = (isset($dbData[0]['campaignId'])) ? $dbData[0]['campaignId'] : 0;
        if ($jobId > 0) {
            Config::get('db')->query("update job set numClicksX = numClicksX + 1 where id={$jobId}");
            if ($campaignId > 0) {
                $campaign = getCampaign($campaignId);
                if ($campaign) {
                    $dailyBudget = $campaign['daily_budget'];
                    $clickBudget = $campaign['click_budget'];
                    
                    $txTotal = getDailyTransactionTotal($campaignId);                   
                    
                    $account = getAccount($campaign['account_id']);
                    
                    $accountBalance = $account['balance'];
                    
                    
                    //business rules
                    
                    //check if campaign has reached it's daily budget
                    if ($txTotal + $clickBudget > $dailyBudget) {
                        return false;
                        //we are at max budget or the day
                    }
                    
                    //check if click will bankrupt account
                    if ($clickBudget > $accountBalance) {
                        return false;
                        //we are not able to process the transaction due to it creating a negative balance                        
                    }
                    
                    //create transaction
                    Config::get('db')->query("update account set balance=balance-'{$clickBudget}' where id=".$account['id']);
                    
                    $transaction = array(
                        'accountId' => $campaign['account_id'],
                        'campaignId' => $campaignId,
                        'jobId' => $jobId,
                        'transactionDate' => date('Y-m-d H:i:s'),
                        'amount' => $clickBudget
                    );
                    
                    Config::get('db')->insert('transaction',$transaction);
                    
                    echo json_encode(array('success'=>true));
                    return true;
                }
            }        
        }
    }
}



////////////////////////////////////////////////
//////// CLICK TRACK FACEBOOK

if (isset($_GET['cf'])) {
    $jobId = $_REQUEST['jid'];
    $query = 
    $dbData = Config::get('db')->get_results("select j.id,j.campaignId from job j left join campaign c on j.campaignId=c.id where j.id={$jobId}");
    if ($dbData && count($dbData) > 0) {
        $jobId = (isset($dbData[0]['id'])) ? $dbData[0]['id'] : 0;
        $campaignId = (isset($dbData[0]['campaignId'])) ? $dbData[0]['campaignId'] : 0;
        if ($jobId > 0) {
        	Config::get('db')->query("insert into clicks (jobId,facebook) values({$jobId},1) on duplicate key update facebook=facebook+1");
        	
            //Config::get('db')->query("update job set numClicksFB = numClicksFB + 1 where id={$jobId}");
            // if ($campaignId > 0) {
            //     $campaign = getCampaign($campaignId);
            //     if ($campaign) {
            //         $dailyBudget = $campaign['daily_budget'];
            //         $clickBudget = $campaign['click_budget'];
                    
            //         $txTotal = getDailyTransactionTotal($campaignId);                   
                    
            //         $account = getAccount($campaign['account_id']);
                    
            //         $accountBalance = $account['balance'];
                    
                    
            //         //business rules
                    
            //         //check if campaign has reached it's daily budget
            //         if ($txTotal + $clickBudget > $dailyBudget) {
            //             return false;
            //             //we are at max budget or the day
            //         }
                    
            //         //check if click will bankrupt account
            //         if ($clickBudget > $accountBalance) {
            //             return false;
            //             //we are not able to process the transaction due to it creating a negative balance                        
            //         }
                    
            //         //create transaction
            //         Config::get('db')->query("update account set balance=balance-'{$clickBudget}' where id=".$account['id']);
                    
            //         $transaction = array(
            //             'accountId' => $campaign['account_id'],
            //             'campaignId' => $campaignId,
            //             'jobId' => $jobId,
            //             'transactionDate' => date('Y-m-d H:i:s'),
            //             'amount' => $clickBudget
            //         );
                    
            //         Config::get('db')->insert('transaction',$transaction);
                    
            //         echo json_encode(array('success'=>true));
            //         return true;
            //     }
            // }        
        }
    }
}


////////////////////////////////////////////////
//////// GET ACCOUNTS - ADMIN ONLY

if (isset($_GET['ga'])) {
    if (!($account_data['role'] == 10)) { exit(); }

    $iDisplayLength = (isset($_REQUEST['length'])) ? intval($_REQUEST['length']) : 10;
    $iDisplayLength = $iDisplayLength < 0 ? 10 : $iDisplayLength; 
    $iDisplayStart = (isset($_REQUEST['start'])) ? intval($_REQUEST['start']) : 0;
    $sEcho = (isset($_REQUEST['draw'])) ? intval($_REQUEST['draw']) : '';
   
    $records = array();
    $records['data'] = array();
    
    $dbData = Config::get('db')->get_results("
        select SQL_CALC_FOUND_ROWS
            a.*,
            a.id as accountId,            
            DATE_FORMAT(date_sub(a.signup_date,interval 5 hour),'%m/%d/%Y') as signup_date,
            DATE_FORMAT(date_sub(a.lastlogin_date,interval 5 hour),'%m/%d/%Y') as lastlogin_date
        from account a
        
        order by id asc
        
        LIMIT {$iDisplayStart},{$iDisplayLength}
    ");
    
    $query = "SELECT FOUND_ROWS() AS found_rows;";

    $countData = Config::get('db')->get_results($query);

    $iTotalRecords = $countData[0]['found_rows'];

    foreach($dbData as $account) {
        $subquery = "select count(id) as tweetCount from job where userName='{$account['twitter_handle']}'";
        $dbData = Config::get('db')->get_results($subquery);
        $tweetCount = max(0,($dbData && count($dbData) > 0) ? $dbData[0]['tweetCount'] : 0);
        $records['data'][] = array(
            $account['id'],
            $account['fullName'],
            '<a target="_blank" href="mailto:'.$account['email'].'">'.$account['email'].'</a>',
            '<a target="_blank" href="https://twitter.com/'.$account['twitter_handle'].'">'.$account['twitter_handle'].'</a>',
            $tweetCount,
            ($account['website'] != '') ? '<a target="_blank" href="'.$account['website'].'">Link</a>' : '',
            $account['balance'],
            $account['signup_date'],            
            $account['lastlogin_date'],            
            '<a class="btn green" href="userbilling.php?aid='.$account['accountId'].'">$</a>'.
            '<a class="btn blue" href="dashboard.php?la='.$account['twitter_handle'].'"><i class="fa fa-user"></i></a>'
            );
    }
    //$records['data'] = $dbData;
    $records["draw"] = $sEcho;
    $records["recordsTotal"] = $iTotalRecords;
    $records["recordsFiltered"] = $iTotalRecords;

    echo json_encode($records);
}


////////////////////////////////////////////////
//////// GET PROMO CODES - ADMIN ONLY

if (isset($_GET['gpc'])) {
    if (!($account_data['role'] == 10)) { exit(); }
    $iDisplayLength = (isset($_REQUEST['length'])) ? intval($_REQUEST['length']) : 10;
    $iDisplayLength = $iDisplayLength < 0 ? 10 : $iDisplayLength; 
    $iDisplayStart = (isset($_REQUEST['start'])) ? intval($_REQUEST['start']) : 0;
    $sEcho = (isset($_REQUEST['draw'])) ? intval($_REQUEST['draw']) : '';

    $records = array();
    $records["data"] = array(); 
    
    $query = "
        SELECT SQL_CALC_FOUND_ROWS * FROM coupon
        WHERE status>0
        ORDER BY setupDate desc
        LIMIT {$iDisplayStart},{$iDisplayLength}
    ";
    
    $dbData = Config::get('db')->get_results($query);
    $query = "SELECT FOUND_ROWS() AS found_rows;";
    $countData = Config::get('db')->get_results($query);
    foreach($dbData as $coupon) {
        $couponType = '';
        switch($coupon['type']) {
            case 0:
            default:
                $couponType = 'Invalid';
                break;
            case 1:
                $couponType = 'Multiplier';
                break;
            case 2:
                $couponType = 'Percentage';
                break;
            case 3:
                $couponType = 'AddedBonus';
                break;
                
        }
        $records['data'][] = array(
            $coupon['id'],
            $coupon['name'],
            $coupon['code'],
            $coupon['setupDate'],
            $coupon['expireDate'],
            $couponType,
            $coupon['value'],
            $coupon['requiredAmount'],
            '<a class="btn blue" href="promocode_edit.php?pcid='.$coupon['id'].'">Edit</a>'
            );
    }
    $iTotalRecords = $countData[0]['found_rows'];
    $records["draw"] = $sEcho;
    $records["recordsTotal"] = $iTotalRecords;
    $records["recordsFiltered"] = $iTotalRecords;
    echo json_encode($records);
    
    exit();
}

////////////////////////////////////////////////
//////// GET CAMPAIGNS

if (isset($_GET['gc'])) {
    $iDisplayLength = (isset($_REQUEST['length'])) ? intval($_REQUEST['length']) : 10;
    $iDisplayLength = $iDisplayLength < 0 ? 10 : $iDisplayLength; 
    $iDisplayStart = (isset($_REQUEST['start'])) ? intval($_REQUEST['start']) : 0;
    $sEcho = (isset($_REQUEST['draw'])) ? intval($_REQUEST['draw']) : '';
    
    $query = "SELECT SQL_CALC_FOUND_ROWS * FROM campaign WHERE account_id={$account_data['accountId']} and status > 0 order by id desc LIMIT {$iDisplayStart},{$iDisplayLength}";    
    $dbData = Config::get('db')->get_results($query);

    $query = "SELECT FOUND_ROWS() AS found_rows;";
    $countData = Config::get('db')->get_results($query);
    $iTotalRecords = $countData[0]['found_rows'];

    $records = array();
    $records["data"] = array(); 

    foreach($dbData as $campaign) {
        $tweetData = Config::get('db')->get_results("select count(j.id) as totalTweets from job j where j.status > 0 and twitterId != '' and campaignId=".$campaign['id']);
        $totalTweets = max(0,(count($tweetData) > 0 && isset($tweetData[0]['totalTweets'])) ? $tweetData[0]['totalTweets'] : 0);
        $spendData = Config::get('db')->get_results("select sum(amount) as campaignSpent from transaction where campaignId=".$campaign['id']);
        $spendTotal = max(0.00,(count($spendData) > 0 && isset($spendData[0]['campaignSpent'])) ? $spendData[0]['campaignSpent'] : 0);
       
        $records["data"][] = array(
            $campaign['id'],
            $campaign['name'],
            date("m/d/Y",strtotime($campaign['start_date'])),
            date("m/d/Y",strtotime($campaign['end_date'])),
            $totalTweets,
            ($campaign['limited']>=1) ? 'Included' : '$'.$campaign['click_budget'],
            ($campaign['limited']>=1) ? 'Included' : '$'.$campaign['daily_budget'],
            ($campaign['limited']>=1) ? 'Included' : '$'.$campaign['budget'],
            ($campaign['limited']>=1) ? 'Included' : '$'.number_format((float)($campaign['budget'] - $spendTotal), 2, '.', ''),
            ($campaign['limited']>=1 ? ($campaign['limited']>=2 ? '<a class="btn btn-sm green" href="campaign_edit_ab.php?cid='.$campaign['id'].'">Edit <i class="fa fa-edit"></i></a> <a class="btn btn-sm blue" href="upgrade.php?cid='.$campaign['id'].'">Upgrade <i class="fa fa-edit"></i></a>' : '<a class="btn btn-sm green" href="campaign_edit_sb.php?cid='.$campaign['id'].'">Edit <i class="fa fa-edit"></i></a> <a class="btn btn-sm blue" href="upgrade.php?cid='.$campaign['id'].'">Upgrade <i class="fa fa-edit"></i></a>') : '<a class="btn btn-sm green" href="campaign_edit.php?cid='.$campaign['id'].'">Edit <i class="fa fa-edit"></i></a> <a class="btn btn-sm blue" href="upgrade.php?cid='.$campaign['id'].'">Upgrade <i class="fa fa-edit"></i></a>'
        ));
    }
	//<a class="btn btn-sm red" onclick="tj.removeCampaign('.$campaign['id'].');" data-target="#remove_campaign" data-id="'.$campaign['id'].'" data-toggle="modal">Remove <i class="fa fa-remove"></i></a>
    
    
    $records["draw"] = $sEcho;
    $records["recordsTotal"] = $iTotalRecords;
    $records["recordsFiltered"] = $iTotalRecords;

    echo json_encode($records);
    
    exit();
}

////////////////////////////////////////////////
//////// GET ONE CAMPAIGN

if (isset($_GET['goc'])) {
    
    $query = "SELECT 
    id as edit_campaign_id,
    name as edit_campaign_name,
    DATE_FORMAT(start_date,'%m/%d/%Y') as edit_campaign_from,
    DATE_FORMAT(end_date,'%m/%d/%Y') as edit_campaign_to,
    click_budget as edit_campaign_budget_click,
    daily_budget as edit_campaign_budget_daily,
    budget as edit_campaign_budget_total,
    details as edit_campaign_notes
    FROM campaign WHERE account_id={$account_data['accountId']} and id={$_REQUEST['cid']} and status > 0";    
    $dbData = Config::get('db')->get_results($query);

    $start = 0;
    $limit = 20;
    
    $twitter_handle = trim($account_data['twitter_handle']," ");
   
    $query = "SELECT SQL_CALC_FOUND_ROWS *,DATE_FORMAT(postDate,'%m/%d/%Y %h:%i%p') as postDate FROM job WHERE userName='{$twitter_handle}' and (campaignId = 0 or campaignId IS NULL) and status > 0 ORDER BY id DESC LIMIT {$start},{$limit}";
    $tweetData = Config::get('db')->get_results($query);
    
    $tweetPoolContent = (count($tweetData) > 0) ? '<ol class="dd-list">' : '<div class="dd-empty"></div>';
    foreach($tweetData as $tweet) {
        $tweetPoolContent .= '<li class="dd-item dd3-item" data-id="'.$tweet['id'].'"><div class="dd-handle dd3-handle"></div><div class="dd3-content">'.$tweet['text'].'<br /><b>'.$tweet['postDate'].' - '.$tweet['city'].', '.$tweet['state'].'</b></div></li>';
    }
    $tweetPoolContent .= (count($tweetData) > 0) ? '<ol class="dd-list">' : '';

    $query = "SELECT SQL_CALC_FOUND_ROWS *,DATE_FORMAT(postDate,'%m/%d/%Y %h:%i%p') as postDate FROM job WHERE userName='{$twitter_handle}' and (campaignId = {$_REQUEST['cid']}) and status > 0 ORDER BY id DESC LIMIT {$start},{$limit}";
    $tweetData = Config::get('db')->get_results($query);
    
    $tweetCampaignContent = (count($tweetData) > 0) ? '<ol class="dd-list">' : '<div class="dd-empty"></div>';
    foreach($tweetData as $tweet) {
        $tweetCampaignContent .= '<li class="dd-item dd3-item" data-id="'.$tweet['id'].'"><div class="dd-handle dd3-handle"></div><div class="dd3-content">'.$tweet['text'].'<br /><b>'.$tweet['postDate'].' - '.$tweet['city'].', '.$tweet['state'].'</b></div></li>';
    }
    $tweetCampaignContent .= (count($tweetData) > 0) ? '<ol class="dd-list">' : '';
    
    
    $record = array();
    
    $record['success'] = (count($dbData) > 0) ? true : false;
    $record["data"] = (count($dbData) > 0) ? $dbData[0] : array();
    $record['tweetpool'] = $tweetPoolContent;
    $record['tweetlist'] = $tweetCampaignContent;
    
    echo json_encode($record);
    
    exit();
}


////////////////////////////////////////////////
//////// ADD CAMPAIGN

if (isset($_GET['ac'])) {
    $account_id = $account_data['accountId'];
    $name = $_POST['campaign_name'];
    $from = $_POST['campaign_from'];
    $to = $_POST['campaign_to'];
    $click = $_POST['campaign_budget_click'];
    $daily = $_POST['campaign_budget_daily'];
    $total = $_POST['campaign_budget_total'];
    $items = isset($_POST['campaign_items']) ? $_POST['campaign_items'] : array();
    $data = array(
        'account_id' => $account_id,
        'name' => $name,
        'start_date' => date("Y-m-d H:i:s",strtotime($from)),
        'end_date' => date("Y-m-d H:i:s",strtotime($to)),
        'click_budget' => $click,
        'daily_budget' => $daily,
        'budget' => $total
        );
        
    Config::get('db')->insert('campaign',$data);
    $campaignId = Config::get('db')->lastid();
    
    foreach($items as $jobId) {
        $data = array('campaignId'=>$campaignId);
        $where = array('id'=>$jobId);
        Config::get('db')->update('job',$data,$where);
    }
    
    echo json_encode(array('success'=>true,'campaignId'=>$campaignId));
    exit();
}

////////////////////////////////////////////////
//////// EDIT CAMPAIGN

if (isset($_GET['ec'])) {
    $account_id = $account_data['accountId'];
    $name = $_POST['campaign_name'];
    $from = $_POST['campaign_from'];
    $to = $_POST['campaign_to'];
    $click = $_POST['campaign_budget_click'];
    $daily = $_POST['campaign_budget_daily'];
    $total = $_POST['campaign_budget_total'];
    $details = isset($_POST['campaign_notes']) ? $_POST['campaign_notes'] : '';
    $jobs = isset($_POST['campaign_items']) ? $_POST['campaign_items'] : array();
    
    $data = array('campaignId'=>0);
    $where = array('campaignId'=>$_POST['campaign_id']);
    Config::get('db')->update('job',$data,$where);
    foreach($jobs as $jobId) {
        $data = array('campaignId'=>$_POST['campaign_id']);
        $where = array('id'=>$jobId);
        Config::get('db')->update('job',$data,$where);
    }
    $data = array(
        //'name' => $name,
        //'start_date' => date("Y-m-d H:i:s",strtotime($from)),
        //'end_date' => date("Y-m-d H:i:s",strtotime($to)),
        'click_budget' => $click,
        'daily_budget' => $daily,
        'budget' => $total,
        'details' => $details
        );
    
    Config::get('db')->update('campaign',$data,array('id'=>$_POST['campaign_id'],'account_id'=>$account_id));
    echo json_encode(array('success'=>true));
    exit();
}


////////////////////////////////////////////////
//////// REMOVE CAMPAIGN

if (isset($_GET['rc'])) {
    $campaignId = $_REQUEST['cid'];
    $account_id = $account_data['accountId'];
    $data = array('campaignId'=>0);
    $where = array('campaignId'=>$campaignId);
    Config::get('db')->update('job',$data,$where);
    $where = array('id'=>$campaignId);
    $data = array('status'=>0);
    Config::get('db')->update('campaign',$data,$where,1);
    echo json_encode(array('success'=>true));
    exit();
}

////////////////////////////////////////////////
//////// DELETE USER

if (isset($_GET['du'])) {
    $user = $_REQUEST['userId'];
    $data = array('status'=>0);
    $where = array('id'=>$user);
    Config::get('db')->update('users',$data,$where);
    
    $where = array('userId'=>$user);
    $data = array('userId'=>0);
    Config::get('db')->update('sms_stores',$data,$where);
    echo json_encode(array('success'=>true));
    exit();
}

////////////////////////////////////////////////
//////// REMOVE USER FROM STORE

if (isset($_GET['rem'])) {
    $user = $_REQUEST['userId'];
    $store = $_REQUEST['storeId'];
    $data = array('userId'=>0);
    $where = array('id'=>$store);
    Config::get('db')->update('sms_stores',$data,$where);
    
    echo json_encode(array('success'=>true));
    exit();
}




////////////////////////////////////////////////
//////// GET ONE TWEET
if (isset($_GET['got'])) {
    $jobId = isset($_REQUEST['jid']) ? $_REQUEST['jid'] : false;
    if ($jobId) {
        $dbData = Config::get('db')->get_results("select * from job where id={$jobId}");
        if (count($dbData) >0) {
            $tweet = $dbData[0];
            
            echo $tweet['text'];            
        } else {
            echo "Job Not Found.";
        }
    } else {
        echo "Job Not Found.";
    }
    exit();
}

////////////////////////////////////////////////
//////// GET PAYMENT SUMMARY
if (isset($_GET['gps'])) {
    //$account_id = (isset($_REQUEST['aid']) && $account_data['role'] == 10) ? $_REQUEST['aid'] : $account_data['accountId'];
    $account_id = $account_data['accountId'];
    $iDisplayLength = (isset($_REQUEST['length'])) ? intval($_REQUEST['length']) : 10;
    $iDisplayLength = $iDisplayLength < 0 ? 10 : $iDisplayLength; 
    $iDisplayStart = (isset($_REQUEST['start'])) ? intval($_REQUEST['start']) : 0;
    $sEcho = (isset($_REQUEST['draw'])) ? intval($_REQUEST['draw']) : '';
    
    $query = "SELECT SQL_CALC_FOUND_ROWS 
            *,p.id as payId
            FROM payment p 
            WHERE p.accountId={$account_id} 
                and p.status = 2 
            ORDER BY p.paymentDate DESC 
            LIMIT {$iDisplayStart},{$iDisplayLength}";    

    //echo $query;

    $dbData = Config::get('db')->get_results($query);

    $query = "SELECT FOUND_ROWS() AS found_rows;";
    $countData = Config::get('db')->get_results($query);
    $iTotalRecords = $countData[0]['found_rows'];  
    $records = array();
    $records['data'] = array();
    
    foreach($dbData as $payment) {
        $records["data"][] = array(
            $payment['payId'],
            date("m/d/Y",strtotime($payment['paymentDate'])),
            '$'.$payment['amount']
        );
    }
    

    $records["draw"] = $sEcho;
    $records["recordsTotal"] = $iTotalRecords;
    $records["recordsFiltered"] = $iTotalRecords;

    echo json_encode($records);    
    exit();
}


////////////////////////////////////////////////
//////// GET SPEND SUMMARY
if (isset($_GET['gss'])) {
    //$account_id = (isset($_REQUEST['aid']) && $account_data['role'] == 10) ? $_REQUEST['aid'] : $account_data['id'];
    $account_id = $account_data['accountId'];
    $iDisplayLength = (isset($_REQUEST['length'])) ? intval($_REQUEST['length']) : 10;
    $iDisplayLength = $iDisplayLength < 0 ? 10 : $iDisplayLength; 
    $iDisplayStart = (isset($_REQUEST['start'])) ? intval($_REQUEST['start']) : 0;
    $sEcho = (isset($_REQUEST['draw'])) ? intval($_REQUEST['draw']) : '';
    
    $query = "SELECT SQL_CALC_FOUND_ROWS *,t.id as txId, c.name as campaignName FROM transaction t LEFT JOIN campaign c on c.id=t.campaignId WHERE t.accountId={$account_id} and t.status > 0 ORDER BY t.transactionDate DESC LIMIT {$iDisplayStart},{$iDisplayLength}";    
    //echo $query;
    $dbData = Config::get('db')->get_results($query);

    $query = "SELECT FOUND_ROWS() AS found_rows;";
    $countData = Config::get('db')->get_results($query);
    $iTotalRecords = $countData[0]['found_rows'];  
    $records = array();
    $records['data'] = array();
    
    foreach($dbData as $transaction) {
        $records["data"][] = array(
            $transaction['txId'],
            $transaction['campaignName'],
            '<a href="javascript:;" id="t'.$transaction['txId'].'" onmouseover="tj.popoverTweet(\'#t'.$transaction['txId'].'\','.$transaction['jobId'].');" onmouseout="$(this).popover(\'hide\');">View</a>',
            date("m/d/Y",strtotime($transaction['transactionDate'])),
            '$'.$transaction['amount']
        );
    }
    

    $records["draw"] = $sEcho;
    $records["recordsTotal"] = $iTotalRecords;
    $records["recordsFiltered"] = $iTotalRecords;

    echo json_encode($records);    
    exit();    
}

////////////////////////////////////////////////
//////// GET NON CAMPAIGN TWEETS
if (isset($_GET['gnct'])) {
    $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 0;
    $campaignId = $_REQUEST['cid'];
    $limit = 500;
    $start = $page * $limit;
    $twitter_handle = trim($account_data['twitter_handle']," ");
    $query = "SELECT SQL_CALC_FOUND_ROWS * FROM job WHERE userName='{$twitter_handle}' and status > 0 and (campaignId = 0 or campaignId IS NULL) ORDER BY id DESC LIMIT {$start},{$limit}";
    $dbData = Config::get('db')->get_results($query);

    $query = "SELECT FOUND_ROWS() AS found_rows;";
    $countData = Config::get('db')->get_results($query);
    $total = $countData[0]['found_rows'];
    
    $outJobs = array();
    foreach($dbData as $job) {
        
        $job['rawData'] = stripslashes(stripslashes(stripslashes($job['rawData'])));
        $job['rawData'] = json_decode(strip_tags($job['rawData']),true);
        $job['urls'] = stripslashes($job['urls']);
               
        $time = strtotime($job['postDate']);
		//$time = strtotime('-6 hours', strtotime($job['postDate']));

        $job['postDate'] = date("m/d/y g:i A", $time);

        $urls = explode(',',$job['urls']);
        $jobUrl = 'javascript:;';
        $onClick = '';
        $job['onclick'] = ''; 
        $twitterprofilelink = (isset($user['screen_name'])) ? 'https://twitter.com/intent/follow?screen_name='.$job['userName'] : 'https://twitter.com';
        if (count($urls) > 0) {
            $job['url'] = $urls[0];
            $onClick = "window.open(this.href,'targetWindow','toolbar=no, location=no, status=no, menubar=no, scrollbars=yes,resizable=yes, width=800, height=600');return false;";
            $job['onclick'] = $onClick;
        }
        
        $outJobs[] = $job;
    }
    
    $outData = array();
    $outData['sucess'] = true;
    $outData['total'] = $total;
    $outData['records'] = $outJobs;
    
    echo json_encode($outData);
    exit();
}

////////////////////////////////////////////////
//////// GET CAMPAIGN TWEETS
if (isset($_GET['gct'])) {
    $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 0;
    $campaignId = $_REQUEST['cid'];
    $limit = 500;
    $start = $page * $limit;
    $twitter_handle = trim($account_data['twitter_handle']," ");
    $query = "SELECT SQL_CALC_FOUND_ROWS * FROM job WHERE status > 0 and twitterId !='' and campaignId={$campaignId} ORDER BY id DESC LIMIT {$start},{$limit}";
    $dbData = Config::get('db')->get_results($query);

    $query = "SELECT FOUND_ROWS() AS found_rows;";
    $countData = Config::get('db')->get_results($query);
    $total = $countData[0]['found_rows'];
    
    $outJobs = array();
    foreach($dbData as $job) {
        
        $job['rawData'] = stripslashes(stripslashes(stripslashes($job['rawData'])));
        $job['rawData'] = json_decode(strip_tags($job['rawData']),true);
        $job['urls'] = stripslashes($job['urls']);
        
        $time = strtotime($job['postDate']);
        //$time = strtotime('-6 hours', strtotime($job['postDate']));
        $job['postDate'] = date("m/d/y g:i A", $time);

        $urls = explode(',',$job['urls']);
        $jobUrl = 'javascript:;';
        $onClick = '';
        $job['onclick'] = ''; 
        $twitterprofilelink = (isset($user['screen_name'])) ? 'https://twitter.com/intent/follow?screen_name='.$job['userName'] : 'https://twitter.com';
        if (count($urls) > 0) {
            $job['url'] = $urls[0];
            $onClick = "window.open(this.href,'targetWindow','toolbar=no, location=no, status=no, menubar=no, scrollbars=yes,resizable=yes, width=800, height=600');return false;";
            $job['onclick'] = $onClick;
        }
        
        $outJobs[] = $job;
    }
    
    $outData = array();
    $outData['sucess'] = true;
    $outData['total'] = $total;
    $outData['records'] = $outJobs;
    
    echo json_encode($outData);
    exit();
}

////////////////////////////////////////////////
//////// GET TWEETS
if (isset($_GET['gt'])) {
    $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 0;
    $limit = 20;
    $start = $page * $limit;
    $twitter_handle = trim($account_data['twitter_handle']," ");
    $query = "SELECT SQL_CALC_FOUND_ROWS *,IFNULL(c.text,0) as txtcount,IFNULL(c.facebook,0) as facebook, IFNULL(c.jobalarm,0) as jobalarm, job.text as jobtext FROM job LEFT JOIN clicks c on c.jobId=job.id WHERE userName='{$twitter_handle}' and twitterId != '' and status > 0 ORDER BY id DESC LIMIT {$start},{$limit}";
    $dbData = Config::get('db')->get_results($query);

    $query = "SELECT FOUND_ROWS() AS found_rows;";
    $countData = Config::get('db')->get_results($query);
    $total = $countData[0]['found_rows'];
    
    $outJobs = array();
    foreach($dbData as $job) {
        
        $job['rawData'] = stripslashes(stripslashes(stripslashes($job['rawData'])));
        $job['rawData'] = json_decode(strip_tags($job['rawData']),true);
        $job['urls'] = stripslashes($job['urls']);
       
        
        $time = strtotime($job['postDate']);
        $job['postDate'] = date("m/d/y g:i A", $time);

        $urls = explode(',',$job['urls']);
        $jobUrl = 'javascript:;';
        $onClick = '';
        $job['onclick'] = ''; 
        $twitterprofilelink = (isset($user['screen_name'])) ? 'https://twitter.com/intent/follow?screen_name='.$job['userName'] : 'https://twitter.com';
        if (count($urls) > 0) {
            $job['url'] = $urls[0];
            $onClick = "window.open(this.href,'targetWindow','toolbar=no, location=no, status=no, menubar=no, scrollbars=yes,resizable=yes, width=800, height=600');return false;";
            $job['onclick'] = $onClick;
        }
        
        $outJobs[] = $job;
    }
        
    $outData = array();
    $outData['sucess'] = true;
    $outData['total'] = $total;
    $outData['records'] = $outJobs;
    
    echo json_encode($outData);
    exit();
}


////////////////////////////////////////////////
//////// SET HASHTAGS
if (isset($_GET['sh'])) {
    $hashtags = $_REQUEST['hashtags'];
    Config::get('db')->query("truncate streamtag;");
    foreach($hashtags as $hashtag) {
        Config::get('db')->insert('streamtag',array('hashtag'=>$hashtag));        
    }
    echo json_encode(array('success'=>true));
    exit();
}


////////////////////////////////////////////////
//////// CHECK BALANCE
if (isset($_GET['cb'])) {
    $account_id = $account_data['accountId'];
    $addCheck = 0.00;
    $accountBalance = 0.00;
    
    if (isset($_REQUEST['cct'])) {
        $campaignData = Config::get('db')->get_results('select sum(budget) as campaignTotal from campaign where account_id='.$account_id.' AND status=1 GROUP BY account_id');
        if ($campaignData && count($campaignData) > 0) {
            $addCheck = $campaignData[0]['campaignTotal'];
        }
    }
    $amount = isset($_REQUEST['total']) ? $_REQUEST['total'] : 0;
    $account = getAccount($account_data['accountId']);
    //$accountBalance = $account['balance'];
    if ((floatval($amount)+floatval($addCheck)) > floatval($accountBalance)) {
        echo 'false';
    } else {
        echo 'true';
    }
    exit();
}


////////////////////////////////////////////////
//////// ADD TWEET
use Abraham\TwitterOAuth\TwitterOAuth;

if (isset($_GET['atw'])) {
$message = isset($_POST['message']) ? $_POST['message'] : 0;

	if (strlen(trim($message)) > 0) {
			
		$connection = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, $account_data['twitter_key'],$account_data['twitter_passcode']);
        $twitter_data = (array) $connection->get("account/verify_credentials", array("include_entities" => false, "skip_status" => true));    
            
        $result = $connection->post('statuses/update',array('status'=>$message));
          echo json_encode(array('success'=>true));
    } else {
        echo json_encode(array('success'=>false));
    }

}

function generateJobTweet($jobId) {
	         
    
    $jobData = Config::get('db')->get_results("select p.*, b.storeBrand, j.position, a.twitter_handle, s.address, s.city, s.st, s.msgCount, s.brandId as brandId, c.id as campaignId, l.url as jobUrl from sms_posts as p LEFT JOIN sms_stores as s on s.id = p.storeId LEFT JOIN sms_jobs as j on j.id = p.jobId LEFT JOIN account as a on a.id = s.accountId LEFT JOIN campaign as c on c.account_id = s.accountId LEFT JOIN accountUrls as l on l.id = p.url LEFT JOIN sms_brand as b on b.id = s.brandId where p.id=".$jobId);
    if (count($jobData) > 0) {
        $jobmsg = $jobData[0];
    } else {
        //echo "alex 33333";
        echo json_encode(array('success'=>false));
        exit(); 
    }

    $message2 = $jobmsg['storeBrand']." in ".$jobmsg['city'].", ".$jobmsg['st']." has immediate openings for ".$jobmsg['position'].". Apply online now ".$jobmsg['jobUrl'];
	
		if ($message2) {
		//$dbData = Config::get('db') -> get_results("SELECT  a.*, j.jobTitle, s.address, s.city, s.st, s.msgCount, t.storeBrand FROM `account` as a LEFT JOIN `sms_stores` as s on s.accountId = a.id LEFT JOIN `sms_jobs` as j on j.accountId = a.id LEFT JOIN `stores` as t on s.storeId = t.id  WHERE j.id = {$jobId}");
		
		//$dbData = Config::get('db') -> get_results("SELECT p.*, a.twitter_handle, a.tLimit, c.id as campaign, j.jobTitle, s.address, s.city, s.st, s.msgCount, s.storeId, t.storeBrand, p.id FROM `sms_posts` as p LEFT JOIN `sms_stores` as s on s.storeNum = p.storeNum LEFT JOIN `sms_jobs` as j on j.id = p.jobId LEFT JOIN `campaign` as c on c.account_id = s.accountId LEFT JOIN `account` as a on a.id = s.accountId LEFT JOIN `stores` as t on s.storeId = t.id  WHERE p.id = {$jobId} and a.id = {$account}");
				
		$now = time();
		$nowDate = $now;
		$upDate = date("Y-m-d H:i:s", $nowDate);
		
		$twitterName = $jobData [0]["twitter_handle"];
		$brandId = $jobData [0]["brandId"];
		$storeId = $jobData [0]["storeId"];
		$userName = trim($twitterName," ");
		$company = $jobData [0]["storeBrand"];
		$campaign = $jobData [0]["campaignId"];
		$jobTitle = $jobData [0]["position"];
		$city = $jobData [0]["city"];
		$state = $jobData [0]["st"];
		$address = $jobData [0]["address"];
		$msgs = $jobData [0]["msgCount"];
		//$tLimit = $jobData [0]["tLimit"];
		
		
		$text = addslashes($jobmsg['storeBrand'])." in ".$jobmsg['city'].", ".$jobmsg['st']." has immediate ".$jobmsg['position']." openings. Apply online now ".$jobmsg['jobUrl']; 
		$url = $jobmsg['jobUrl'];
		

		if (strlen($jobTitle) > 0) {
	
	$data = array(
		'postDate' => date("Y-m-d H:i:s", $nowDate),
		'text'=>Config::get('db')->filter($text),
		'city'=>Config::get('db')->filter($city),
		'state'=>Config::get('db')->filter($state),
		'rawData'=>Config::get('db')->filter($message2),
		'urls'=>Config::get('db')->filter($url),
		'userName'=>Config::get('db')->filter($userName),
		'brand'=>Config::get('db')->filter($brandId),
		'campaignId'=>Config::get('db')->filter($campaign),
		'postId'=>Config::get('db')->filter($jobId)
		);
		
		
		$jobdata = Config::get('db') -> get_results("SELECT id FROM job WHERE postId =".$jobId);
		$job1 = $jobdata[0]['id'];
		
		if ($job1 > 0) {
             Config::get('db')->query("update job set postDate = '{$upDate}', status = 1, text = '{$text}' WHERE postId ='{$jobId}'");
			 $message = addslashes($jobmsg['storeBrand'])." in ".$jobmsg['city'].", ".$jobmsg['st']." has immediate ".$jobmsg['position']." openings.  For details, go to www.jobalarm.com/ja.php?id=".$job1;
         } else {
             Config::get('db')->insert('job',$data);
			 $job = Config::get('db') -> get_results("SELECT id FROM job WHERE postId ='{$jobId}'");
			 $job2 = $job[0]['id'];
			 if ($job2 > 0) {
			 $message = addslashes($jobmsg['storeBrand'])." in ".$jobmsg['city'].", ".$jobmsg['st']." has immediate ".$jobmsg['position']." openings.  For details, go to www.jobalarm.com/ja.php?id=".$job2;
			 } else {
				$message = addslashes($jobmsg['storeBrand'])." in ".$jobmsg['city'].", ".$jobmsg['st']." has immediate ".$jobmsg['position']." openings.  For details go to www.jobalarm.com/ja.php?u=".$jobmsg['twitter_handle']; 
			 }
         }
		
		}
	}
	
	echo json_encode(array('success'=>true,'message'=>$message));
	//echo $smsAlex;
}

if (isset($_GET['gjt'])) {
    //$jobid = isset($_GET['gjt']) ? $_GET['gjt'] : false;
   
	

    $jobId = isset($_POST['jid']) ? $_POST['jid'] : 0;
	//echo json_encode($jobId);
    generateJobTweet($jobId);
    //echo json_encode($jobId);
}

//Get All Companies

if (isset($_GET['gaj'])) {
    
    $accountId = $account_data['accountId'];
	
	$query = "SELECT m.*, s.storeBrand, u.last_name, u.first_name, s.textLimit FROM `sms_stores` as m LEFT JOIN `users` as u on u.id = m.userId LEFT JOIN `sms_brand` as s on s.id = m.brandId WHERE m.accountId={$accountId} AND m.active > 0 GROUP BY m.id ORDER BY m.zip ASC";
		
	$dbData = Config::get('db') -> get_results($query);
	$query = "SELECT FOUND_ROWS() AS found_rows;";
	$countData = Config::get('db') -> get_results($query);
	$total = $countData[0]['found_rows'];
	$outJobs = array();
	$jobArray = array();
	$jobArrayAll = array();
	
	foreach ($dbData as $all) {
		$jobArrayAll= array();
		$storeNum = $all['storeNum'];
		$storeBrand = "";
		if ($storeNum) {
		$storeBrand .= $all['storeBrand']." (".$all['storeNum'].")";
		}else{
		$storeBrand = $all['storeBrand'];
		}
		
		$address = "";
		$address .= $all['address'].", ".$all['city'].", ".$all['st'];
		
		$name = "";
		if (strlen($all['last_name']) > 0) {		
		$name .= $all['last_name'].", ".$all['first_name'];
		}else{
		$name = "Not Assigned";
		}
		
		$storeId = $all['id'];
		
		$jobArrayAll[] = $storeBrand;
		$jobArrayAll[] = $address;
		$jobArrayAll[] = $name;
		
		$url = 'http://www.jobalarm.com/assign.php?s=' . $storeId;
		
		//echo $url;

				
		$storeButton = "";
		//$storeButton .= "<a class=\"btn btn-sm green\" onclick=\"window.open('$url','assign','width=400, height=300')\">Assign </a><a class=\"btn btn-sm red\" onclick=\"tj.removeStore(".$all['userId'].",".$storeId.");\">Remove </a>";
        $storeButton .= "<a class=\"btn btn-sm green\" onclick=\"assignStore.initialize(".$storeId.")\">Assign </a><a class=\"btn btn-sm red\" onclick=\"tj.removeStore(".$all['userId'].",".$storeId.");\">Remove </a>";
;
		

		//$storeButton .= "<a class=\"btn btn-sm green\" onclick=\"window.open(\"$url\",\"\",\"width=300, height=200\")>Assign </a><a class=\"btn btn-sm red\" onclick=\"tj.removeStore(".$all['userId'].",".$storeId.");\">Remove </a>";
		
		//echo $storeButton;
		
		$jobArrayAll[] = $storeButton;
		
		$outJobs[] = $all;
		$jobArray[] = $jobArrayAll;
	}    $outData = array();
	$outData['success'] = true; 
	$outData['data'] = $jobArray;
	echo json_encode($outData);
	exit();
}


//Get Companies

if (isset($_GET['gco'])) {
    $userId = $account_data['id'];
	$accountId = $account_data['accountId'];
	$role = $account_data['role'];
	$brand1 = $account_data['brand1'];
	$brand2 = $account_data['brand2'];
	
	if ($role >= 3) {
		$query = "SELECT m.*, s.storeBrand,s.textLimit , cit.*
		FROM `sms_stores` as m 
		LEFT JOIN `sms_brand` as s on s.id = m.brandId 
		LEFT JOIN `cities_extended` cit on cit.zip = m.zip
		WHERE m.active > 0 AND m.accountId = {$accountId} AND (m.brandId = {$brand1} OR m.brandId = {$brand2})
		GROUP BY m.id ORDER BY m.zip ASC";
	
	//$query = "SELECT m.*, s.storeBrand, s.textLimit FROM `sms_stores` as m LEFT JOIN `sms_brand` as s on s.id = m.brandId WHERE m.active > 0 AND m.accountId = {$accountId} AND (m.brandId = {$brand1} OR m.brandId = {$brand2}) GROUP BY m.id ORDER BY m.zip ASC";
	}else{
	
	$query = "SELECT m.*, s.storeBrand, s.textLimit, cit.* FROM `sms_stores` as m LEFT JOIN `sms_brand` as s on s.id = m.brandId LEFT JOIN `cities_extended` cit on cit.zip = m.zip WHERE m.active > 0 AND m.userId = {$userId} AND m.accountId = {$accountId} GROUP BY m.id ORDER BY m.zip ASC
";
	}
		
	$dbData = Config::get('db') -> get_results($query);
	$query = "SELECT FOUND_ROWS() AS found_rows;";
	$countData = Config::get('db') -> get_results($query);
	$total = $countData[0]['found_rows'];
	$outJobs = array();
	$jobArray = array();
	$jobArrayTemp = array();
	$radius = 10;
	$brand = 0;
	
		
	foreach ($dbData as $job) {
		$jobArrayTemp = array();
		$storeNum = $job['storeNum'];
		$zip = $job['zip'];
		$brand = $job['brandId'];
		$limit = 4;
		$miles = 12;
		$lat = $job['latitude'];
		$lon = $job['longitude'];
	
		//$city = Config::get('db') -> get_results("select * FROM cities_extended where zip={$zip}");
	
		//$lat = $city[0]["latitude"];
		//$lon = $city[0]["longitude"];
		//echo $lat;
		
		$search_add = '';
		$search_add = " or x.brandId=6 or x.brandId=19";
		 	 
		
		$candidates = Config::get('db')->get_results("SELECT c.*, x.keyword as keyword,(count(case m.type when '1' then 1 else null end) + count(case m.type when '2' then 1 else null end) + count(case m.type when '3' then 1 else null end) - count(case m.type when '0' then 1 else null end)) AS msgCount FROM `candidate` as c LEFT JOIN candidateXref as x on x.candidateId = c.id LEFT OUTER JOIN `sms_messages` as m on c.id = m.candidateId and m.brandId = x.brandId and m.msgDate BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW() LEFT JOIN cities_extended ce on ce.zip = c.zip WHERE ((3963*ACOS(SIN({$lat}/57.2958)*SIN(ce.latitude/57.2958)+ COS({$lat}/57.2958) *COS(ce.latitude/57.2958)*COS((ce.longitude/57.2958)-({$lon}/57.2958))))<=$miles and c.active = 1 and c.mobile != '' and x.promo<3 and x.promo>0) and (x.brandId={$brand}{$search_add}) GROUP BY c.id HAVING msgCount<$limit ORDER BY msgCount ASC");

				
		$query = "SELECT FOUND_ROWS() AS found_rows;";
		$countData = Config::get('db') -> get_results($query);
				
		if(!$candidates){
			$totalCandidates = 0;
		}else{
			$brand = $job['brandId'];
			$totalCandidates = $countData[0]['found_rows'];
		}
			$candidateLink = '<a href="http://admin.jobalarm.com/globals?z='.$zip.'&u='.$account_data['id'].'&b='.$brand.'">'.$totalCandidates.'</a>';
		
		
		$storeBrand = "";
		
		if ($storeNum) {
		$storeBrand .= $job['storeBrand']." (".$job['storeNum'].")";
		}else{
		$storeBrand = $job['storeBrand'];
		}
		
		$address = "";
		$address .= $job['address'].", ".$job['city'].", ".$job['st'].", ".$job['zip'];
		
		$jobArrayTemp[] = $storeBrand;
		$jobArrayTemp[] = $address;
		$jobArrayTemp[] = $candidateLink;
		
        $storeNameAlex = str_replace("'", "", $job['storeBrand']);

		$storeButton = "";
		$storeButton .= "<a class=\"btn btn-sm blue\" onclick=\"tj.alex.jobxGrid(" . $job['id'] . ", &#39;" . $job['address'] . "&#39;,&#39;" . $job['city'] . "&#39;,&#39;" . $job['st'] . "&#39;,&#39;" . $job['zip'] . "&#39;,&#39;" . $storeNameAlex . "&#39;,&#39;" . $job['storeNum'] . "&#39;);\">Open </a>";
		
		$jobArrayTemp[] = $storeButton;
		
		$outJobs[] = $job;
		$jobArray[] = $jobArrayTemp;
	}    $outData = array();
	$outData['success'] = true; 
	//$outData['store'] = $address;
	$outData['data'] = $jobArray;
	echo json_encode($outData);
	exit();
}

//Get jobs

if (isset($_GET['jx'])) {
    //$brandId = isset($_POST['brandId']) ? $_POST['brandId'] : 2;
    $storeId = isset($_GET['jx']) ? $_GET['jx'] : 0;
    $user = $account_data['id'];
    $role = $account_data['role'];
	
    //echo "store ".$storeId;
    //echo "user ".$user;
    //echo "role ".$role;

	if ($role >0) {
	$query = "SELECT j.*,(c.facebook + c.jobalarm + c.text) as clickCount, jb.id, p.id as pid, p.lastpostDate, p.lasttextDate, p.storeId, s.address, s.city, s.st, s.storeNum FROM `sms_jobs` j LEFT JOIN `sms_posts` as p on p.jobId = j.id LEFT JOIN `sms_stores` as s on s.id = p.storeId LEFT OUTER JOIN `job` as jb on jb.postId = p.id LEFT OUTER JOIN `clicks` as c on c.jobId = jb.id where p.storeId={$storeId}";
	}else{
	$query = "SELECT j.*,(c.facebook + c.jobalarm + c.text) as clickCount, jb.id, p.id as pid, p.lastpostDate, p.lasttextDate, p.storeId, s.address, s.city, s.st, s.storeNum FROM `sms_jobs` j LEFT JOIN `sms_posts` as p on p.jobId = j.id LEFT JOIN `sms_stores` as s on s.id = p.storeId LEFT OUTER JOIN `job` as jb on jb.postId = p.id LEFT OUTER JOIN `clicks` as c on c.jobId = jb.id where s.userId={$user} ORDER BY s.id
";
	}

	$dbData = Config::get('db') -> get_results($query);
	$query = "SELECT FOUND_ROWS() AS found_rows;";
	$countData = Config::get('db') -> get_results($query);
	$total = $countData[0]['found_rows'];
	$outJobs = array();
	$jobArray = array();
	$jobArrayTemp = array();
	
	$store = "Store " . $dbData['storeNum'];
	$address = $dbData['address'];
	$city .= $dbData['city'].", ".$dbData['st'];
	

	foreach ($dbData as $job) {
		$jobArrayTemp = array();
		$lat = $job['latitude'];
		$lon = $job['longitude'];
		$clickCount = 0;
		
		//$query = "SELECT  c.* FROM `craigslist` as c LEFT JOIN cities_extended ce on ce.zip = c.zip WHERE ((3963*ACOS(SIN({$lat}/57.2958)*SIN(ce.latitude/57.2958)+ COS({$lat}/57.2958) *COS(ce.latitude/57.2958)*COS((ce.longitude/57.2958)-({$lon}/57.2958))))<=25 GROUP BY c.id  LIMIT 20";
		//$dbCraig = Config::get('db')->get_results("SELECT DISTINCT c.* FROM `craigslist` as c LEFT JOIN cities_extended ce on ce.city = c.city and ce.state_code = c.st WHERE ((3963*ACOS(SIN({$lat}/57.2958)*SIN(ce.latitude/57.2958)+ COS({$lat}/57.2958) *COS(ce.latitude/57.2958)*COS((ce.longitude/57.2958)-({$lon}/57.2958))))<=20)");
				
		$jobArrayTemp[] = $job['position'];
		//$jobArrayTemp[] = $job['storeBrand'];
		
		if ($job['lastpostDate'] > 0) {
			$time = strtotime($job['lastpostDate']);
			$job['lastpostDate'] = date("m/d/y", $time);
			}else{
			$job['lastpostDate'] = "";
			}		
		
		$jobArrayTemp[] = $job['lastpostDate'];
		
						
		$manageGroup = "";
		
	if ($dbCraig){
		$cost = $dbCraig[0]['cost'];
		if($cost==0){
		$manageGroup .= "<a class=\"btn btn-sm blue\" onclick=\"tj.postJob(".$job['pid'].");\">QuickPost </a><a class=\"btn btn-sm green\" onclick=\"tj.textJob(".$job['pid'].");\">QuickText </a><a class=\"btn btn-sm red\" onclick=\"tj.closejob(".$job['pid'].");\">CloseJob </a><a class=\"btn btn-sm green\" onclick=\"tj.craigslist(".$query['pid'].");\">Craigslist </a>";
		}else{
		$manageGroup .= "<a class=\"btn btn-sm blue\" onclick=\"tj.postJob(".$job['pid'].");\">QuickPost </a><a class=\"btn btn-sm green\" onclick=\"tj.textJob(".$job['pid'].");\">QuickText </a><a class=\"btn btn-sm red\" onclick=\"tj.closejob(".$job['pid'].");\">CloseJob </a><a class=\"btn btn-sm purple\" onclick=\"tj.craigslist(".$query['pid'].");\">Craigslist </a>";
		}	
	}else{
		$manageGroup .= "<a class=\"btn btn-sm blue\" onclick=\"tj.postJob(".$job['pid'].");\">QuickPost </a><a class=\"btn btn-sm green\" onclick=\"tj.textJob(".$job['pid'].");\">QuickText </a><a class=\"btn btn-sm red\" onclick=\"tj.closejob(".$job['pid'].");\">CloseJob </a>";
	}
		
		$jobArrayTemp[] = $manageGroup;
		
		if ($job['lasttextDate'] > 0) {
			$time = strtotime($job['lasttextDate']);
			$job['lasttextDate'] = date("m/d/y", $time);
			}else{
			$job['lasttextDate'] = "";
			}
			
		$jobArrayTemp[] = $job['lasttextDate'];
		
		if ($job['clickCount'] == ''){
			$jobArrayTemp[] = $clickCount;
		}else{
			$jobArrayTemp[] = $job['clickCount'];
		}

		
		$outJobs[] = $job;
		$jobArray[] = $jobArrayTemp;
	}    $outData = array();
	$outData['success'] = true;
	$outData['store'] = $store; 
	$outData['data'] = $jobArray;
	echo json_encode($outData);
	exit();
}

//Get ATS jobs

if (isset($_GET['atsx'])) {
    //$brandId = isset($_POST['brandId']) ? $_POST['brandId'] : 2;
    $storeId = isset($_GET['atsx']) ? $_GET['atsx'] : 0;
    $user = $account_data['id'];
    $role = $account_data['role'];
	
	$dbZip = Config::get('db') -> get_results("select * from `sms_stores` where id={$storeId}");
    $zip = $dbZip[0]['zip'];
	$brandId = $dbZip[0]['brandId'];
	$city = strval($dbZip[0]['city']);
	$state = strval($dbZip[0]['st']);
    
    //echo "store ".$storeId;
    //echo "user ".$user;
    //echo "role ".$role;


	IF ($brandId ==9){
	$query = "SELECT j.*,(c.facebook + c.jobalarm + c.text) as clickCount, s.storeNum, s.zip, b.storeBrand FROM `job` j LEFT JOIN `sms_brand` as b on b.id = j.brand LEFT JOIN `sms_stores` as s on s.brandId = j.brand LEFT JOIN `clicks` as c on c.jobId = j.id where j.brand={$brandId} and j.city ='{$city}' and j.state ='{$state}' group by j.title order by j.title ASC";
	}else{
	$query = "SELECT j.*,(c.facebook + c.jobalarm + c.text) as clickCount, s.storeNum, s.zip, b.storeBrand FROM `job` j LEFT JOIN `sms_brand` as b on b.id = j.brand LEFT JOIN `sms_stores` as s on s.brandId = j.brand LEFT JOIN `clicks` as c on c.jobId = j.id where j.zipCode={$zip} and j.brand={$brandId} and j.postId=0 group by j.id order by j.id DESC";
	}	
	
	$dbData = Config::get('db') -> get_results($query);
	$query = "SELECT FOUND_ROWS() AS found_rows;";
	$countData = Config::get('db') -> get_results($query);
	$total = $countData[0]['found_rows'];
	$outJobs = array();
	$jobArray = array();
	$jobArrayTemp = array();
	
	$store = "Store " . $dbData['storeNum'];
	$address = $dbData['address'];
	$city .= $dbData['city'].", ".$dbData['st'];
	

	foreach ($dbData as $job) {
		$jobArrayTemp = array();
		$lat = $job['latitude'];
		$lon = $job['longitude'];
		$clickCount = 0;
		
		//$query = "SELECT  c.* FROM `craigslist` as c LEFT JOIN cities_extended ce on ce.zip = c.zip WHERE ((3963*ACOS(SIN({$lat}/57.2958)*SIN(ce.latitude/57.2958)+ COS({$lat}/57.2958) *COS(ce.latitude/57.2958)*COS((ce.longitude/57.2958)-({$lon}/57.2958))))<=25 GROUP BY c.id  LIMIT 20";
		//$dbCraig = Config::get('db')->get_results("SELECT DISTINCT c.* FROM `craigslist` as c LEFT JOIN cities_extended ce on ce.city = c.city and ce.state_code = c.st WHERE ((3963*ACOS(SIN({$lat}/57.2958)*SIN(ce.latitude/57.2958)+ COS({$lat}/57.2958) *COS(ce.latitude/57.2958)*COS((ce.longitude/57.2958)-({$lon}/57.2958))))<=20)");
				
		$jobArrayTemp[] = $job['title'];
		//$jobArrayTemp[] = $job['storeBrand'];
		
		//$jobArrayTemp[] = '<a href="'.$job['urls'].'" target="_blank">'.$job['urls'].'</a>';
		
		
		if ($job['postDate'] > 0) {
			$time = strtotime($job['postDate']);
			$job['postDate'] = date("m/d/y", $time);
			}else{
			$job['postDate'] = "";
			}		
		
		$jobArrayTemp[] = $job['postDate'];
		
		if ($job['clickCount'] == ''){
			$jobArrayTemp[] = $clickCount;
		}else{
			$jobArrayTemp[] = $job['clickCount'];
		}
		
		
						
		$manageJob = "";
		
	//if ($dbCraig){
		//$cost = $dbCraig[0]['cost'];
		//if($cost==0){
		//$manageGroup .= "<a class=\"btn btn-sm green\" onclick=\"tj.postJob(".$job['pid'].");\">QuickPost </a><a class=\"btn btn-sm blue\" onclick=\"tj.textJob(".$job['pid'].");\">QuickText </a><a class=\"btn btn-sm green\" onclick=\"tj.craigslist(".$query['id'].");\">Craigslist </a>";
		//}else{
		//$manageGroup .= "<a class=\"btn btn-sm green\" onclick=\"tj.postJob(".$job['pid'].");\">QuickPost </a><a class=\"btn btn-sm blue\" onclick=\"tj.textJob(".$job['pid'].");\">QuickText </a><a class=\"btn btn-sm purple\" onclick=\"tj.craigslist(".$query['id'].");\">Craigslist </a>";
		//}	
	//}else{
		$manageJob .= "<a class=\"btn btn-sm green\" href=\"http://admin.jobalarm.com/globals?z=".$zip."&u=".$account_data['id']."&b=".$brandId."&l=http://www.jobalarm.com/ja.php?id=".$job['id']."\"><i class= \"fa fa-comment-o\"></i> Text</a><a class=\"btn btn-sm blue\" onclick=\"tj.alex.manageGroups(" . $job['id'] . ")\"><i class=\"fa fa-facebook\"> Groups</i></a>";
	
			
		$jobArrayTemp[] = $manageJob;
		
			
			
		$outJobs[] = $job;
		$jobArray[] = $jobArrayTemp;
	}    $outData = array();
	$outData['success'] = true;
	$outData['store'] = $store; 
	$outData['data'] = $jobArray;
	echo json_encode($outData);
	exit();
}

//Get Users

if (isset($_GET['ug'])) {
    $account = $account_data['accountId'];
	
	$query = "SELECT u.*, b.storeBrand, COUNT(s.id) as storeCount, s.brandId from users u LEFT JOIN sms_stores as s on s.userId = u.id LEFT JOIN sms_brand as b on b.id = s.brandId WHERE u.accountId ={$account} and u.status =1 GROUP BY u.id ORDER BY u.last_name ASC";
		
	$dbData = Config::get('db') -> get_results($query);
	$query = "SELECT FOUND_ROWS() AS found_rows;";
	$countData = Config::get('db') -> get_results($query);
	$total = $countData[0]['found_rows'];
	$outJobs = array();
	$jobArray = array();
	$jobArrayTemp = array();
	
	foreach ($dbData as $job) {
		$jobArrayTemp = array();
		
		$rolenum = "";
		$rolenum = $job['role'];
		
		if ($rolenum == 1) {
		$role = "Single Store";
		}else if ($rolenum == 2) {
		$role = "Multi Store";
		}else {
		$role = "Admin";
		}
		
		$storeCount = "";
		$storeCount = $job['storeCount'];

		
		$name = "";
		$name .= $job['last_name'].", ".$job['first_name'];
		
		$jobArrayTemp[] = $name;
		$jobArrayTemp[] = $role;
		
		$jobArrayTemp[] = $storeCount;

				
		$storeButton = "";
		$storeButton .= "<a class=\"btn btn-sm blue\" onclick=\"tj.alex.userxGrid(" . $job['id'] . ");\">Edit </a><a class=\"btn btn-sm red\" onclick=\"tj.deleteUser(".$job['id'].");\">Remove </a>";
		
		$jobArrayTemp[] = $storeButton;
		
		$outJobs[] = $job;
		$jobArray[] = $jobArrayTemp;
	}    $outData = array();
	$outData['success'] = true; 
	$outData['name'] = $name;
	$outData['data'] = $jobArray;
	echo json_encode($outData);
	exit();
}

//Get jobs

if (isset($_GET['ux'])) {
    //$brandId = isset($_POST['brandId']) ? $_POST['brandId'] : 2;
    //$userId = isset($_GET['ux']) ? $_GET['ux'] : 0;
    $userId = isset($_REQUEST['userId']) ? $_REQUEST['userId'] : 0;
    $name = isset($_REQUEST['name']) ? $_REQUEST['name'] : '';
	
	$query = "SELECT s.*, b.storeBrand, s.userId as user, u.first_name, u.last_name FROM sms_stores s LEFT JOIN sms_brand as b on b.id = s.brandId LEFT JOIN users as u on u.id = s.userId WHERE s.userId ={$userId} ORDER BY s.st, s.city";
		
	$dbData = Config::get('db') -> get_results($query);
	$query = "SELECT FOUND_ROWS() AS found_rows;";
	$countData = Config::get('db') -> get_results($query);
	$total = $countData[0]['found_rows'];
	$outJobs = array();
	$jobArray = array();
	$jobArrayTemp = array();
	
	$name = "";
	$name = $dbData[0]['last_name'].", ".$dbData[0]['first_name'];

	foreach ($dbData as $job) {
		$jobArrayTemp = array();
		
		$store = $job['storeBrand']."(" . $job['storeNum'].")";
		$location = $job['address']." ".$job['city'].", ".$job['st'];
		$storeId = $job['id'];
		
		$jobArrayTemp[] = $store;
		
		$jobArrayTemp[] = $location;

				
		$manageGroup = "";
		
		$manageGroup .= "<a class=\"btn btn-sm red\" onclick=\"tj.removeStore(".$job['user'].",".$storeId.");\">Remove </a>";
		
		
		$jobArrayTemp[] = $manageGroup;
	

		
		$outJobs[] = $job;
		$jobArray[] = $jobArrayTemp;
	}    $outData = array();
	$outData['success'] = true; 
	$outData['name'] = $name;
	$outData['data'] = $jobArray;
	echo json_encode($outData);
	exit();
}


////////////////////////////////////////////////
//////// Create Job
if (isset($_GET['createJob'])) {
     //$smsId = isset($_REQUEST['smsId']) ? $_REQUEST['smsId'] : false;
     $storeId = isset($_REQUEST['storeId']) ? $_REQUEST['storeId'] : false;
	 $accountId = $account_data['accountId'];

     if ($smsId) {

     	$dbData = Config::get('db') -> get_results("select j.*, u.id as urlId from sms_jobs j LEFT JOIN sms_brand b on b.id = j.brandId LEFT JOIN sms_stores s on s.brandId = b.id LEFT JOIN accountUrls u on u.brandId = j.brandId where s.id ={$storeId} and u.accountId ={$accountId}");
		
		foreach ($dbData as $job) {
		$jobId = $job["id"];
		$url = $job["urlId"];
         
         Config::get('db')->query("insert into sms_posts (jobId,storeId,status,url) values({$jobId},{$storeId},1,{$url}) on duplicate key update status=1");
		
		//$dbData = Config::get('db') -> get_results("select * FROM sms_posts where jobId={$jobId} and storeId={$storeId}");
    	 }
    	 echo json_encode(array('success'=>true));
     
     	} else {
         echo "Jobs Not Found.";
     }
     exit();
}





if (isset($_GET['at'])){
    $message = isset($_POST['message']) ? $_POST['message'] : '';
	//$message = stripslashes($message);

	$jobId = isset($_POST['jobId']) ? $_POST['jobId'] : '';
	$customize = isset($_POST['customize']) ? $_POST['customize'] : false;
	//$zip = $account_data['zip'];
	$radius = 10;
	$limit = 20;
	$account = $account_data['accountId'];
	$userId = $account_data['id'];
	//$userId = $account_data['id'];
	$index = 0;
	$now = time();
	$nowDate = $now;
	$upDate = date("Y-m-d H:i:s", $nowDate);
	
	
	if ($customize) {
        //$jobId = isset($_POST['jobId']) ? $_POST['jobId'] : 0;
		$type = 2;
        $numberList = array();
        $keywords = array();
        
        
        $dbData = Config::get('db') -> get_results("select c.*, p.storeId as storeId, b.id as brandId FROM cities_extended c LEFT JOIN sms_stores s on s.zip = c.zip LEFT JOIN sms_posts p on p.storeId = s.id LEFT JOIN sms_brand as b on s.brandId = b.id where p.id={$jobId}");
		
		$lat = $dbData[0]["latitude"];
		$lon = $dbData[0]["longitude"];
		$storeId = $dbData[0]['storeId'];
		$brandId = $dbData[0]['brandId'];
		
		
		$search_add = '';
		 if ($brandId = 2 || $brandId = 5 || $brandId = 7 || $brandId = 3) {
			 $search_add = " or (x.brandId=6 and c.resume LIKE '%rest%' or c.resume LIKE '%food%' or c.resume LIKE '%cashier%' or c.resume LIKE '%cook%' or c.resume = '')";
		 }
		
		     
		$candidates = Config::get('db')->get_results("SELECT  c.*, x.keyword as keyword, x.brandId, cg.groupId as cgGroup COUNT(m.brandId) as msgCount FROM `candidate` as c LEFT JOIN candidateXref as x on x.candidateId = c.id LEFT OUTER JOIN `candidate_group` as cg on c.id = cg.candidateId LEFT OUTER JOIN `sms_messages` as m on c.id = m.candidateId and m.brandId = x.brandId and m.msgDate BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW() LEFT JOIN cities_extended ce on ce.zip = c.zip WHERE ((3963*ACOS(SIN({$lat}/57.2958)*SIN(ce.latitude/57.2958)+ COS({$lat}/57.2958) *COS(ce.latitude/57.2958)*COS((ce.longitude/57.2958)-({$lon}/57.2958))))<={$radius} and c.active = 1 and c.mobile != '' and x.promo<3) and (x.brandId={$brandId}{$search_add}) GROUP BY c.id HAVING msgCount<4 ORDER BY msgCount ASC LIMIT 20");
		
		//$message = $jobTitle." needed at ".$company." in ".$city.", ".$state.". Go to store at ".$address." to apply today. www.jobalarm.com/ja.php?u=".$userName;	
		
		
		$psword = "J8775bcgEE2065";
		$groupId = 13;
		$messages = array();
								
		//echo json_encode($candidates);
        if (count($candidates) > 0) {
            
			foreach($candidates as $k => $v) {			
				$numberList[] = "1" . $v['mobile'];
				$candidate = $v['id'];
				$brand = $v['brandId'];
				$keywords[] = $v['keyword'];
				$origAccount = $v['accountId'];
				
				$msgId = "";
				$msgId .= "";
				$msgId .= $v['mobile'] . $v['zip'] . $now;
				
				
				$data = array(
				'accountId'=>Config::get('db')->filter($account),
				'userId'=>Config::get('db')->filter($userId),
				'origAccount'=>Config::get('db')->filter($origAccount),
				'storeId'=>Config::get('db')->filter($storeId),
				'brandId'=>Config::get('db')->filter($brand),				
				'candidateId'=>Config::get('db')->filter($candidate),
				'type'=>Config::get('db')->filter($type),
				'postId'=>Config::get('db')->filter($jobId),
				'message'=>Config::get('db')->filter($message),
				'messageId'=>Config::get('db')->filter($msgId)
				);
				
				Config::get('db')->insert('sms_messages',$data);
				
				if (!$v['cgGroup']){
				$data = array(
				'accountId'=>Config::get('db')->filter($account),
				'groupId'=>Config::get('db')->filter($groupId),
				'candidateId'=>Config::get('db')->filter($candidate),
				'groupdate'=>date('Y-m-d H:i:s')
				);
				
				Config::get('db')->insert('candidate_group',$data); 
				}
				
				$dbData = Config::get('db')->query("update candidateXref set recId =$userId where candidateId={$candidate}");
				
				
										
				$smsAlex = "";
				$smsAlex .= "";
			
				$smsAlex .= "<message id=\"".$msgId."\">";
				$smsAlex .= "<partnerpassword>".$psword."</partnerpassword>";
				$smsAlex .= "<content>" . $message . "</content>";
				$smsAlex .= "</message>";
				
				$messages[] = $smsAlex;
							
				$index++;
		        //$accData = Config::get('db')->get_results("select * from account where id=".$account_data['accountId']);
		        }
				
		        $sms_result = send_messages_with_optout($messages,$numberList,$keywords);
		       
		       
		       }		        
				$dbData = Config::get('db')->query("update sms_stores set msgCount = msgCount + 1 where id={$storeId}");
				$dbData = Config::get('db')->query("update sms_posts set status = 1, lasttextDate = '{$upDate}', countSent = $index where id={$jobId}");
				
				echo json_encode(array('success'=>true));
				
	}
								
			if (strlen(trim($message)) > 0) {
			        
        		$connection = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, $account_data['twitter_key'],$account_data['twitter_passcode']);
        		$twitter_data = (array) $connection->get("account/verify_credentials", array("include_entities" => false, "skip_status" => true));    
            
        		$result = $connection->post('statuses/update',array('status'=>$message));
          		}
				$dbData = Config::get('db')->query("update sms_posts set status = 1, lastpostDate = '{$upDate}' where id={$jobId}");
				exit();
}

if (isset($_GET['ja'])){
    $mobile = isset($_POST['ja']) ? $_POST['ja'] : '';
    $numberList[] = "1" . $mobile;
    $now = time();

	
	$msgId = "";
	$msgId .= "";
	$msgId .= $mobile . $now;
	$psword = "J8775bcgEE2065";
	$keyword = "JOBALARM";

	$xmlmsg = "";
	$xmlmsg .= "";
			
	$xmlmsg .= "<message id=\"".$msgId."\">";
	$xmlmsg .= "<partnerpassword>".$psword."</partnerpassword>";
	$xmlmsg .= "<content></content>";
	$xmlmsg .= "</message>";
	
	$messages[] = $xmlmsg;
	
	$sms_result = send_messages_with_optout($messages,$numberList,$keyword);
	exit();
		       
}


if (isset($_GET['ss'])){
    $mobile = isset($_GET['ss']) ? $_GET['ss'] : 0;
    
    $mobile = preg_replace('/[^\dxX]/', '', $mobile1);
	$mobile = ltrim($mobile,"1");
	$mobile .= "1" . $mobile;

	
	$psword = "J8775bcgEE2065";
	$keyword = "hardees";
								
    if ($mobile) {
            
	$msgId = "";
	$msgId .= "";
	$msgId .= $mobile . $now;
										
	$txtMsg = "";
	$txtMsg .= "";
	$txtMsg .= "<message id=\"".$msgId."\">";
	$txtMsg .= "<partnerpassword>".$psword."</partnerpassword>";
	$txtMsg .= "<content></content>";
	$txtMsg .= "</message>";
	
	
	$sms_result = send_messages_with_optout($txtMsg,$mobile,$keyword);
	}
}
		 


/*
$iTotalRecords = 178;
$iDisplayLength = intval($_REQUEST['length']);
$iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
$iDisplayStart = intval($_REQUEST['start']);
$sEcho = intval($_REQUEST['draw']);

$records = array();
$records["data"] = array(); 

$end = $iDisplayStart + $iDisplayLength;
$end = $end > $iTotalRecords ? $iTotalRecords : $end;

$status_list = array(
  array("success" => "Pending"),
  array("info" => "Closed"),
  array("danger" => "On Hold"),
  array("warning" => "Fraud")
);

for($i = $iDisplayStart; $i < $end; $i++) {
    $status = $status_list[rand(0, 2)];
    $id = ($i + 1);
    $records["data"][] = array(
      '<input type="checkbox" name="id[]" value="'.$id.'">',
      $id,
      '12/09/2013',
      'Jhon Doe',
      'Jhon Doe',
      '450.60$',
      rand(1, 10),
      '<span class="label label-sm label-'.(key($status)).'">'.(current($status)).'</span>',
      '<a href="javascript:;" class="btn btn-xs default"><i class="fa fa-search"></i> View</a>',
   );
}

if (isset($_REQUEST["customActionType"]) && $_REQUEST["customActionType"] == "group_action") {
    $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
    $records["customActionMessage"] = "Group action successfully has been completed. Well done!"; // pass custom message(useful for getting status of group actions)
}

$records["draw"] = $sEcho;
$records["recordsTotal"] = $iTotalRecords;
$records["recordsFiltered"] = $iTotalRecords;

echo json_encode($records);
 * 
*/