<?php

require_once('models/user.php');
define('SLOOCE_LOGIN', 'jobalarm45');
define('SLOOCE_PW', 'wet#%DFG^&FHHJ');
define('SLOOCE_API', 'http://sloocetech.net:8084/spi-war/spi/');


class Sms {

    public static function doView() {
        if (!User::checkLogin()) {
            header('location: home');
        }
        if (Router::getGetVar('sid')) {
            Config::push('jsvars', array('surveyId' => Router::getGetVar('sid')));
            $dbData = Config::get('db')->get_results('SELECT name from survey where surveyId=' . Router::getGetVar('sid'));
            Config::push('jsvars', array('surveyName' => $dbData[0]['name']));
        }
        Config::push('scripts', 'views/shared/sharedfunctions.js');
        Config::push('scripts', 'views/sms/sms.js');
        Config::set('mainmenu', true);
        Config::set('sysblock', true);
        require_once('views/sms/sms.php');
    }

    public static function run() {
        self::doView();
    }

  public static function send() {
        if (isset($_REQUEST['recips']) && count($_REQUEST['recips']) > 0) {
            $recips = $_REQUEST['recips'];
            $accounts = $_REQUEST['accounts'];
			$brands = $_REQUEST['brands'];
            $from = (isset($_REQUEST['from'])) ? $_REQUEST['from'] : null;
            $group = (isset($_REQUEST['group']) && $_REQUEST['group'] != '') ? $_REQUEST['group'] : null;
            $account = (isset($_REQUEST['account'])) ? $_REQUEST['account'] : null;
			$message = (isset($_REQUEST['message'])) ? stripslashes($_REQUEST['message']) : null;
            $now = time();
			$type = 1;
            $numbers = array();
            $messages = array();
            $keywords = array();
            $psword = "J8775bcgEE2065";
            $slooce = "jobalarm45";
			$accountId = 0;
			$brandId = 0;
            //$header = "Content-Type: application/xml";
                        
            $recip_counter = 0;
            foreach ($recips as $candidateId) {
            	$accountId = $accounts[$recip_counter];
				$brandId = $brands[$recip_counter];
                $recip_counter++;
                $query = "select c.*, x.keyword, x.accountId as accountOrig, x.brandId as brandId from candidate c LEFT JOIN candidateXref as x on x.candidateId = c.id where x.accountId = $accountId and c.id = $candidateId";
                //$query = "select c.*, x.keyword from candidate LEFT JOIN candidateXref as x on x.candidateId = c.id and x.accountId = $accountID where id={$candidateId}";
                $dbData = Config::get('db')->get_results($query); 
                
                $mobile = (isset($dbData[0]['mobile'])) ? $dbData[0]['mobile'] : 0;
                $origAccount = (isset($dbData[0]['accountOrig'])) ? $dbData[0]['accountOrig'] : 0;
                $zip = (isset($dbData[0]['zip'])) ? $dbData[0]['zip'] : 0;
		        //$brandId = (isset($dbData[0]['brandId'])) ? $dbData[0]['brandId'] : 0;
                $keyword = (isset($dbData[0]['keyword'])) ? $dbData[0]['keyword'] : 0;
                
                                
                $msgId = "";
				$msgId .= "";
				$msgId .= $mobile . $zip . $now;
				
				             
				$data = array(
				'accountId'=>Config::get('db')->filter($account),
                'origAccount'=>$accountId,
				// 'origAccount'=>Config::get('db')->filter($origAccount),
				'brandId'=>$brandId,
				'candidateId'=>Config::get('db')->filter($candidateId),
				'type'=>Config::get('db')->filter($type),
				'message'=>Config::get('db')->filter($message),
				'messageId'=>Config::get('db')->filter($msgId)
				);
				
				
				Config::get('db')->insert('sms_messages',$data);
				
				$xmlMsg = "";
				$xmlMsg .= "<message id=\"".$msgId."\">";
				$xmlMsg .= "<partnerpassword>".$psword."</partnerpassword>";
				$xmlMsg .= "<content>" . $message . "</content>";
				$xmlMsg .= "</message>";
				
				
				$messages[] = $xmlMsg;
				
				$mobile = "1" . $mobile;	
				
				$numbers[] = $mobile;
				
				$keywords[] = $keyword;
				
                if ($group > 0) {
                    Group::updateCandidate($account,$candidateId,$group);
                }	
                //echo $mobile;
				//echo $accountId;
				//echo $storeId;
				//echo $keyword;
				//echo $slooce;
				//echo $xmlMsg;
	
            }


            $smsoptions = Array(
                'numbers' => $numbers,
                'message' => $messages,
                'keyword' => $keywords,
                'login' => $slooce

            );
            $result = '';

            if ($message && strlen(trim($message)) > 0) {
                
                $result = (new SmsManager) ->sendSMS($smsoptions);     
                       
           }
            
            echo json_encode($result);
        }
    }

    public static function getAllCandidate($candidateId) {
        $userId = Config::get('loggedIn');
        $user = User::load($userId);

        $query = "SELECT 
            id as recid,
            DATE_FORMAT(messageDate,'%m/%d/%y %H:%i') as smsDate,
            message as smsMsg,
            type as smsType
            from sms_history
            where candidateId={$candidateId}
            AND accountId={$userId}
            AND ((type=1 AND isReply=1 AND accountId={$userId}) or (type=2 AND accountId={$userId}))               
            ORDER BY messageDate DESC
            ";
        $dbData = Config::get('db')->get_results($query);
        foreach ($dbData as $k => $v) {
            $dbData[$k]['smsMsg'] = stripslashes($dbData[$k]['smsMsg']);
            $dbData[$k]['style'] = ($dbData[$k]['smsType'] == 1) ? 'color:blue' : 'color:red';
        }
        $outArray = array(
            'status' => 'success',
            'total' => "" . count($dbData) . "",
            'records' => $dbData
        );
        echo json_encode($outArray);
        
    }

    public static function getAllResponse($responseId) {
        $surveyId = Response::getSurveyId($responseId);
        $personId = Response::getPersonId($responseId);
        $user = User::load(Config::get('loggedIn'));
        $userId = Config::get('loggedIn');
        
        $query = "SELECT 
            id as recid,
            DATE_FORMAT(messageDate,'%m/%d/%y %H:%i') as smsDate,
            message as smsMsg,
            type as smsType
            from sms_history
            where surveyId={$surveyId}
            AND peopleId={$personId}
            AND ((type=1 AND isReply=1 AND userId={$userId}) or (type=2 AND userId={$userId}))               
            ORDER BY messageDate DESC
            ";
        //echo $query;
        $dbData = Config::get('db')->get_results($query);
        foreach ($dbData as $k => $v) {
            $dbData[$k]['smsMsg'] = stripslashes($dbData[$k]['smsMsg']);
            $dbData[$k]['style'] = ($dbData[$k]['smsType'] == 1) ? 'color:blue' : 'color:red';
        }
        $outArray = array(
            'status' => 'success',
            'total' => "" . count($dbData) . "",
            'records' => $dbData
        );
        echo json_encode($outArray);
    }

    public static function markViewed($smsid) {
        $data = array('viewed' => 1);
        $where = array('id' => $smsid);
        Config::get('db')->update('sms_history', $data, $where);
    }
    

    public static function curl_request($user, $url, $postdata = null, $header) {
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

    public static function send_sms_message($message,$mobile,$keyword) {
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

        file_put_contents("sendsmsxml.txt",$smsAlex);

        $output .= self::curl_request(SLOOCE_LOGIN, $url, $smsAlex, $header);
        
              
        return $output; 
    
    }
    public static function receive($keyword='') {
        //get our keyword
        //receive in the phone number and message data
        file_put_contents("smsreceive.txt",print_r($_REQUEST,true));
        file_put_contents("smsreceive_post.txt",print_r($_POST,true));
        file_put_contents("smsreceive_get.txt",print_r($_GET,true));
        file_put_contents("smsreceive_server.txt",print_r($_SERVER,true));
        
        $xmlinput = file_get_contents('php://input');

        file_put_contents("smsreceive_xmlinput.txt",$xmlinput);

        $ob = simplexml_load_string($xmlinput);
        $json = json_encode($ob);
        $input_array = json_decode($json, true);

        ///var_dump($array);
        
        $user = isset($input_array["user"]) ? $input_array["user"] : '';
        $keyword = isset($input_array["keyword"]) ? $input_array["keyword"] : '';
        $rcvdmsg = isset($input_array["content"]) ? $input_array["content"] : '';
        $isreply = (strlen(trim($rcvdmsg)) > 0) ? true : false;

        $debug = "";
        if (strlen($user) == 11 && strlen($keyword) > 0) {
            $mobileNum = substr($user,1);
           
            $candidatedata = Config::get('db')->get_results("select * from candidate where mobile='{$mobileNum}'");
            $candidateId = 0;
            if ($candidatedata && count($candidatedata) > 0) {
                $candidateId = $candidatedata[0]['id']; 
            } else {
                $insertdata = array('mobile'=>$mobileNum);
                Config::get('db')->insert('candidate',$insertdata);
                $candidateId = Config::get('db')->lastid();
            }
            $outmessage = '';
            if ($candidateId > 0) {
                $branddata = Config::get('db')->get_results("select * from sms_brand where keyword='".strtoupper($keyword)."'");
                if ($branddata && count($branddata) > 0) {
                    $thisbrand = $branddata[0];
               
                    if (!$isreply) {
                        //var_dump($thisbrand);
                        $xrefdata = Config::get('db')->get_results("select * from candidateXref where keyword='{$thisbrand['keyword']}' and brandId='{$thisbrand['id']}'");
                        if ($xrefdata && count($xrefdata) > 0) {
                            $outmessage = "Welcome to {$thisbrand['storeBrand']} Job Alerts! Please reply with your ZIP CODE to receive the jobs near you.  Reply HELP for help or STOP to cancel. Msg/Data Rates May Apply";
                            $debug = self::send_sms_message($outmessage,$user,$keyword);
                        } else {
                            $insertXrefData = array(
                                    'candidateId' => $candidateId,
                                    'accountId' => 0,
                                    'promo' => 1,
                                    'keyword' => $thisbrand['keyword'],
                                    'brandId' => $thisbrand['id']
                                );
                            //var_dump($insertXrefData);
                            Config::get('db')->insert('candidateXref',$insertXrefData);
                            $candidateXrefId = Config::get('db')->lastid();
                            $outmessage = "Welcome to {$thisbrand['storeBrand']} Job Alerts! Please reply with your ZIP CODE to receive the jobs near you.  Reply HELP for help or STOP to cancel. Msg/Data Rates May Apply";
                            $debug = self::send_sms_message($outmessage,$user,$keyword);
                            //echo $candidateXrefId;
                        }
                    } else {
                        //logic to make sure is a zipcode
                        //if is reply and yes update xref promo to 2                        
                        $xrefdata = Config::get('db')->get_results("select * from candidateXref where keyword='{$thisbrand['keyword']}' and brandId='{$thisbrand['id']}' order by id desc");
                        if (substr($rcvdmsg,0,5) == 'DEALS') {
                            $updatedata = array('promo'=>2);
                            $updatewhere = array('id'=>$xrefdata[0]['id']);
                            Config::get('db')->update('candidateXref',$updatedata,$updatewhere);
                            $outmessage = "You are subscribed to {$thisbrand['storeBrand']} DEALS and DISCOUNTS. Msg/Data rates may apply. Reply STOP to cancel. Reply HELP for support.";
                            $debug = self::send_sms_message($outmessage,$user,$keyword);                            
                        } else {
                            $updatedata = array('zip'=>intval($rcvdmsg));
                            $updatewhere = array('mobile'=>$mobileNum);
                            Config::get('db')->update('candidate',$updatedata,$updatewhere);
                            if ($xrefdata && count($xrefdata) > 0) {
                                $outmessage = "{$thisbrand['storeBrand']} jobs near you: http://jobalarm.com/m.php?z={$rcvdmsg}&m={$mobileNum}&b={$thisbrand['id']} Also, if you would like DEALS and DISCOUNT msgs from {$thisbrand['storeBrand']}, reply DEALS";
                                $debug = self::send_sms_message($outmessage,$user,$keyword);                            
                            }       
                        }                                         
                    }
                }
            }
            file_put_contents("sendsmslog.txt", $debug);

        }

        // file_put_contents("smsrawpostdata.txt",$xmlinput);
        // $p = xml_parser_create();
        // xml_parse_into_struct($p, $xmlinput, $vals, $index);
        // xml_parser_free($p);
        // echo "index array\n";
        // print_r($index);        
        // echo "\nvals array\n";
        // print_r($vals);

//        $keyword = $vals[2]['value'];
  //      print_r($keyword);
        // $sender = isset($_REQUEST['PhoneNumber']) ? $_REQUEST['PhoneNumber'] : ((isset($_REQUEST['from'])) ? $_REQUEST['from'] : '');
        // $message = isset($_REQUEST['Message']) ? $_REQUEST['Message'] : ((isset($_REQUEST['message'])) ? $_REQUEST['message'] : '');    
        // if (strtolower(substr($message,0,4)) == 'stop') {
        //     SmsManager::optOut($sender);
        // }
        
        // $sendOptions = Array();

        // if (Router::getGetVar('reply')) {
        //     $sendOptions['reply'] = 1;
        // }
        
        // SmsManager::receive($sender, $message, $sendOptions);              
    }
    
    public static function uploadFile() {
        $targetFolder = 'dat/temp'; // Relative to the root
                   
        if (!is_dir(dirname($_SERVER['SCRIPT_FILENAME']) . "/{$targetFolder}")) {
            mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . '/' . $targetFolder);
        }


        if (!empty($_FILES)) {
            $tempFile = $_FILES['Filedata']['tmp_name'];
            $targetPath = dirname($_SERVER['SCRIPT_FILENAME']) . "/{$targetFolder}";
            $targetFile = rtrim($targetPath, '/') . '/' . $_FILES['Filedata']['name'];

            // Validate the file type
            $fileTypes = array('wav'); // File extensions
            $fileParts = pathinfo($_FILES['Filedata']['name']);
                
            if (in_array($fileParts['extension'], $fileTypes)) {
                move_uploaded_file($tempFile, $targetFile);

                echo json_encode(array('success' => true, 'fileURL' => $_FILES['Filedata']['name']));
            } else {
                echo json_encode(array('success' => false, 'msg' => 'Invalid File Type'));
            }
        }
               
    }
    
    public static function sync() {
    
        SmsManager::syncSMSHistoryToUsers();
        
        echo json_encode(array('success'=>true));
    
    }
}
