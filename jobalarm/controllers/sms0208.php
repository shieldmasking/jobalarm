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
	
	public static function getUserID() {
        $userx = (isset($_COOKIE['jobalarm_userId'])) ? $_COOKIE['jobalarm_userId'] : 0;
        //echo $recruiter.": again";        
    }

	public static function send() {
        if (isset($_REQUEST['recips']) && count($_REQUEST['recips']) > 0) {
            $recips = $_REQUEST['recips'];
            $accounts = $_REQUEST['accounts'];
			//$recruiter = $_REQUEST['recId'];
			//$brands = $_REQUEST['brands'];
			//$brands = (isset($_REQUEST['brands'])) ? $_REQUEST['brands'] : 0;
            $from = (isset($_REQUEST['from'])) ? $_REQUEST['from'] : '';
            $group = (isset($_REQUEST['group']) && $_REQUEST['group'] != '') ? $_REQUEST['group'] : '';
            $account = (isset($_REQUEST['account'])) ? $_REQUEST['account'] : '';
			$message = (isset($_REQUEST['message'])) ? stripslashes($_REQUEST['message']) : '';
			$userx = (isset($_COOKIE['jobalarm_userId'])) ? $_COOKIE['jobalarm_userId'] : 0;
            if ($userx == 0 && Router::getGetVar('u')) {
            $userx = Router::getGetVar('u');
			}
			$now = time();
			$type = 1;
            $numbers = array();
            $messages = array();
            $keywords = array();
            $psword = "J8775bcgEE2065";
            $slooce = "jobalarm45";
			$accountId = 0;
			$brandId = 0;
			$user = User::load(Config::get('loggedIn'));
			$userId = Config::get('loggedIn');
            //$header = "Content-Type: application/xml";
			
                        
            $recip_counter = 0;
			
            foreach ($recips as $candidateId) {
				$accountId = $accounts[$recip_counter];
				
				//$brandId = $brands[$recip_counter];
				//$recId = $recruiter[$recip_counter];


                $recip_counter++;
				//$query = "select c.*, x.keyword, x.userXref, x.accountId as accountOrig, x.promo, rx.userId as userId, x.brandId as brandId, cg.groupId as cgGroup from candidate c LEFT OUTER JOIN `candidate_group` as cg on c.id = cg.candidateId and cg.accountId={$userId} LEFT OUTER JOIN `recruiterXref` as rx on rx.candidateId = c.id and rx.accountId={$userId} LEFT JOIN candidateXref as x on x.candidateId = c.id where c.id ={$candidateId}";
				$query = "select c.*, x.keyword, x.keyword2, x.brandId as brandX, x.brandOrig, x.promo, x.shortCode, cg.groupId as cgGroup from `candidate` c LEFT OUTER JOIN `candidate_group` as cg on c.id = cg.candidateId and cg.accountId={$userId} LEFT JOIN `candidateXref` as x on x.candidateId = c.id LEFT JOIN `account_brand` as a on a.brandId=x.brandId where c.id ={$candidateId} and (x.brandId = a.brandId or x.brandId=6 or x.brandId=19) group by c.mobile";
                
				
                $dbData = Config::get('db')->get_results($query); 
                
                $mobile = (isset($dbData[0]['mobile'])) ? $dbData[0]['mobile'] : 0;
				$shortCode = (isset($dbData[0]['shortCode'])) ? $dbData[0]['shortCode'] : 0;
				$promo = (isset($dbData[0]['promo'])) ? $dbData[0]['promo'] : 0;
                $origAccount = (isset($dbData[0]['accountOrig'])) ? $dbData[0]['accountOrig'] : 0;
                $zip = (isset($dbData[0]['zip'])) ? $dbData[0]['zip'] : 0;
		        $brandId = (isset($dbData[0]['brandX'])) ? $dbData[0]['brandX'] : 0;
                $brandOrig = (isset($dbData[0]['brandOrig'])) ? $dbData[0]['brandOrig'] : 0;
                $keyword = (isset($dbData[0]['keyword'])) ? $dbData[0]['keyword'] : '';
				$keyword2 = (isset($dbData[0]['keyword2'])) ? $dbData[0]['keyword2'] : '';
				$cxId = (isset($dbData[0]['id'])) ? $dbData[0]['id'] : 0;
				$accountXref = (isset($dbData[0]['accountXref'])) ? $dbData[0]['accountXref'] : 0;
				//$userXref = (isset($dbData[0]['userXref'])) ? $dbData[0]['userXref'] : 0;
				$recId = (isset($dbData[0]['recId'])) ? $dbData[0]['recId'] : 0;
                
                if (intval($promo) > 0 && intval($promo) < 3) {         
                
				$msgId = "";
				$msgId .= "";
				$msgId .= $mobile . $zip . $now;
				
				             
				$data = array(
				'accountId'=>$userId,
                'origAccount'=>$accountId,
				'userId'=>$userx,
				'brandId'=>$brandOrig,
				'candidateId'=>$candidateId,
				'type'=>$type,
				'message'=>Config::get('db')->filter($message),
				'messageId'=>Config::get('db')->filter($msgId)
				);
				Config::get('db')->insert('sms_messages',$data);
					
				//if ($userXref != $userx){
				//	Config::get('db')->query("update candidateXref set userXref = '{$userx}' WHERE candidateId ='{$candidateId}' and (brandId ='{$brandId}' or brandId =6 or brandId=19)");
				//	}    
				
				$xmlMsg = "";
				$xmlMsg .= "<message id=\"".$msgId."\">";
				$xmlMsg .= "<partnerpassword>".$psword."</partnerpassword>";
				$xmlMsg .= "<content><![CDATA[" . $message . "]]></content>";
				$xmlMsg .= "</message>";
				
				$messages[] = $xmlMsg;				
				$mobile = "1" . $mobile;
				$numbers[] = $mobile;
				$keywords[] = $keyword2;
				
				if (!$dbData[0]['cgGroup'] && !$group){
				$grp = 13;
				 Group::updateCandidate($account,$candidateId,$grp,$userx);
               	 }
				
                if ($group) {
                  Group::updateCandidate($account,$candidateId,$group,$userx);
                }
									
				}
            }
		


            $smsoptions = Array(
                'numbers' => $numbers,
                'message' => $messages,
                'keyword' => $keywords,
                'login' => $slooce,
				'shortCode' => $shortCode

            );
            $result = '';

            if ($message && strlen(trim($message)) > 0) {
                
                $result = SmsManager::sendSMS($smsoptions);     
                       
           }
            
            echo json_encode($result);
        }
    }

    public static function getAllCandidate($candidateId) {
        $userId = Config::get('loggedIn');
        $user = User::load($userId);
		$userx = (isset($_COOKIE['jobalarm_userId'])) ? $_COOKIE['jobalarm_userId'] : 0;
          
        $query = "SELECT 
            id as recid,
            DATE_FORMAT(msgDate,'%m/%d/%y %H:%i') as smsDate,
            message as smsMsg,
            type as smsType
            from sms_messages
            where candidateId={$candidateId}
			AND accountId IN(select `accountId` from `users` where id={$userx})
            AND type <2
			ORDER BY msgDate DESC
            ";
        $dbData = Config::get('db')->get_results($query);
        foreach ($dbData as $k => $v) {
            $dbData[$k]['smsMsg'] = stripslashes($dbData[$k]['smsMsg']);
            $dbData[$k]['style'] = ($dbData[$k]['smsType'] > 0) ? 'color:blue' : 'color:red';
        }
        $outArray = array(
            'status' => 'success',
            'total' => "" . count($dbData) . "",
            'records' => $dbData
        );
        echo json_encode($outArray);
        
    }

    public static function getAllResponse($responseId) {
        //$surveyId = Response::getSurveyId($responseId);
        //$personId = Response::getPersonId($responseId);
        $user = User::load(Config::get('loggedIn'));
        $userId = Config::get('loggedIn');
        
        /*$query = "SELECT 
            id as recid,
            DATE_FORMAT(messageDate,'%m/%d/%y %H:%i') as smsDate,
            message as smsMsg,
            type as smsType
            from sms_history
            where surveyId={$surveyId}
            AND peopleId={$personId}
            AND ((type=1 AND isReply=1 AND userId={$userId}) or (type=2 AND userId={$userId}))               
            ORDER BY messageDate DESC
            "; */
		$query = "SELECT  
            id as recid,
            DATE_FORMAT(msgDate,'%m/%d/%y %H:%i') as smsDate,
            message as smsMsg,
            type as smsType
            from sms_messages
            where type<2 AND candidateId={$responseId} AND (accountId={$userId} OR accountId=0) 
			ORDER BY msgDate DESC
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

    public static function send_sms_message($message,$mobile,$keyword,$brandId,$candidateId,$acct) {
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
		usleep(500000);

        $output .= self::curl_request(SLOOCE_LOGIN, $url, $smsAlex, $header);
        
		return $output; 
    
    }
	
	    public static function send_sms_message2($message,$mobile,$keyword,$brandId,$candidateId,$acct) {
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

        $output .= self::curl_request(SLOOCE_LOGIN, $url, $smsAlex, $header);
        
		return $output; 
    
    }
	
	public static function send_baylor_message($message,$mobile,$keyword) {
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
		usleep(500000);

        $output .= self::curl_request(SLOOCE_LOGIN, $url, $smsAlex, $header);
        
		return $output; 
    
    }
	function send_messages_with_optout($smsAlex,$number,$keyword) {
    $header = 'Content-Type: application/xml';
	$output = "";
	$post = '';
    $mobile = '';
	$keywords = "";
	$keywords = $keyword;
    
    $post = $smsAlex;
    $mobile = $number;
   
    $url = 'http://sloocetech.net:8084/spi-war/spi/jobalarm45/' . $mobile . '/' . $keywords . '/messages/start';
    $output = self::curl_request(SLOOCE_LOGIN, $url, $post, $header);
      
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
		$rcvdcmd = isset($input_array["command"]) ? $input_array["command"] : '';
        $isreply = (strlen(trim($rcvdmsg)) > 0) ? true : false;
		$iscmd = (strlen(trim($rcvdcmd)) > 0) ? true : false;
		$account = (isset($_REQUEST['account'])) ? $_REQUEST['account'] : null;
		$zip = substr($rcvdmsg,0,5);
		$keyword2 = $keyword;
		$promo = 0;
		$acct = 0;
		$brandOrig = 0;
		$linkAdd = '';
		
		$demo = array("BOJANGLES","CHURCHS","TOOLS","FISH","MURPHY","PAPA","ADVANCE","SHAKE","BOSTON","KING","NAPA","CAPTAIN","WALGREENS","RITE","SPEEDWAY","GAP","CASTLE","TACO","CHICK","ZAXBYS","HOWIES","MCDONALDS","HUT","KFC","POLLO","WHOPPER","WICH","CARE","BANK","PILOT","SPECTRUM");
		If (strtoupper(substr($keyword,0,3)) == 'FUN' && (substr($user,0,4) == '1305' || substr($user,0,4) == '1786')){
			return;
		}
		If (strtoupper(substr($keyword,0,7)) == 'POPEYES' && strtoupper($rcvdmsg) == '') {
                        $outmessage2 = $user." just tried JobAlarm for POPEYES ";
						$user2 = "12148500163";
						$brandId2 = 36;
						$candidateId2 = 4717;
                        $debug2 = self::send_sms_message($outmessage2,$user2,$keyword2,$brandId2,$candidateId2);
		}
		$debug = "";
        if (strlen($user) == 11 && strlen($keyword) > 0) {
            $mobileNum = substr($user,1);
			$promo = 1;
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
                If (strtoupper(substr($keyword,0,5)) == 'DEMO2') {
					If (in_array(strtoupper($rcvdmsg),$demo)) {
					$branddata = Config::get('db')->get_results("select * from sms_brand where keyword='".strtoupper($keyword3)."'");
					}else{
						$demodata = Config::get('db')->get_results("select * from candidateXref where candidateId={$candidateId} and keyword='".strtoupper($keyword)."'");
						$keywordDemo = $demodata[0]['keyword2'];
						$branddata = Config::get('db')->get_results("select * from sms_brand where keyword='".strtoupper($keywordDemo)."'");
					}
				}else{
					$branddata = Config::get('db')->get_results("select * from sms_brand where keyword='".strtoupper($keyword)."'");
                }
			if ($branddata && count($branddata) > 0) {
                    $thisbrand = $branddata[0];
					$brandId = $thisbrand['id'];
					$acct = $thisbrand['type'];
				
				If ((strtoupper(substr($keyword,0,6)) == 'WENDYS' || strtoupper(substr($keyword,0,4)) == 'BELL') && strtoupper($rcvdmsg) == 'JOBS') {
						$acct = 280;
						$isreply = false;
						}
					
				if (strtoupper(substr($keyword,0,3)) == 'CVS' && (strtoupper(substr($rcvdmsg,0,6)) == 'RETAIL')){
					$isreply = false;
					$keyword = "RETAIL";
				}
				if (strtoupper(substr($keyword,0,3)) == 'CVS' && (strtoupper(substr($rcvdmsg,0,4)) == 'JOBS')){
					$isreply = false;
					$keyword = "CVSJOBS";
				}
								
				If (strtoupper(substr($keyword,0,5)) == 'DEMO2') {
						$brandOrig =50;
					}else{
						$brandOrig = $brandId;
						}
										               
                if (!$isreply && !$iscmd) {
                  //if ((strtoupper(substr($keyword,0,3)) == 'CVS' && in_array(strtoupper($rcvdmsg),$demo)) || (!$isreply && !$iscmd)) {   
						$xrefdata = Config::get('db')->get_results("select x.*, c.accountXref from `candidateXref` as x left join `candidate` as c on x.candidateId = c.id where x.brandOrig={$brandOrig} and x.candidateId={$candidateId}");	
						
						$type = 8;
						
						$updatedata = array(
						'brandId'=>$brandId,
						'accountId'=>$acct,
						'candidateId'=>$candidateId,
						'type'=>$type,
						'message'=> $keyword
                         );
						Config::get('db')->insert('sms_messages',$updatedata);
						
						if ($xrefdata && count($xrefdata) > 0) {
							$promo = $xrefdata[0]['promo'];
							$acct = $xrefdata[0]['accountId'];
							
							if (strtoupper($keyword) == 'RETAIL'){
							$updateKey = "RETAIL";
							}else if (strtoupper($keyword) == 'CVSJOBS'){
							$updateKey = "CVSJOBS";
							}else{
							$updateKey = $keyword2;
							}
							
								$updatedata = array('promo'=>1,
													'brandId'=>$brandOrig,
													'shortCode' =>47711,
													'keyword'=>$updateKey);
								
								$updatewhere = array('id'=>$xrefdata[0]['id']);
								Config::get('db')->update('candidateXref',$updatedata,$updatewhere);
								$outmessage = "Welcome to {$thisbrand['storeBrand']} Job Alerts! Plz reply with your ZIP CODE to receive your local jobs. Reply HELP for help or STOP to cancel. Msg&Data Rates May Apply";
								$debug = self::send_sms_message($outmessage,$user,$keyword2,$brandId,$candidateId,$acct);
								
								if(strtoupper($keyword) == 'RETAIL'){
								usleep(30000000);
								$outmessage = "Thanks for your interest in {$thisbrand['storeBrand']} retail jobs. A Recruiter will be in contact very soon regarding our open positions.";
								$debug = self::send_sms_message($outmessage,$user,$keyword2,$thisbrand['id'],$candidateId,$acct);
								}
								exit();
                        } else {
							
							        $insertXrefData = array(
                                    'candidateId' => $candidateId,
                                    'accountId' => $acct,
									'shortCode' =>47711,
                                    'promo' => 1,
                                    'keyword' => $keyword,
									'keyword2' => $thisbrand['keyword'],
                                    'brandId' => $brandOrig,
									'brandOrig' => $thisbrand['id']
                                );
                            								                           
							Config::get('db')->insert('candidateXref',$insertXrefData);
                            $candidateXrefId = Config::get('db')->lastid();
                            
							$outmessage = "Welcome to {$thisbrand['storeBrand']} Job Alerts! Reply with your ZIP CODE to receive your local jobs. Reply HELP for help or STOP to cancel. Msg&Data Rates May Apply";
                            $debug = self::send_sms_message($outmessage,$user,$keyword2,$thisbrand['id'],$candidateId,$acct);
                            
							if(strtoupper($keyword) == 'RETAIL'){
							usleep(30000000);
							$outmessage = "Thanks for your interest in {$thisbrand['storeBrand']} retail jobs. A Recruiter will be in contact very soon regarding our open positions.";
                            $debug = self::send_sms_message($outmessage,$user,$keyword2,$thisbrand['id'],$candidateId,$acct);
                           	}
							exit();
                        }
                    } else {
                            
                        if (strtoupper(substr($keyword,0,5)) == 'DEMO2') {
						$xrefdata = Config::get('db')->get_results("select x.*, c.accountXref from `candidateXref` as x left join `candidate` as c on x.candidateId = c.id where keyword2='{$thisbrand['keyword']}' and x.candidateId={$candidateId}");
						//$xrefdata = Config::get('db')->get_results("select * from candidateXref where keyword2='{$thisbrand['keyword']}' and brandId='{$thisbrand['id']}' order by id desc");
						}else{
						$xrefdata = Config::get('db')->get_results("select x.*, c.accountXref from `candidateXref` as x left join `candidate` as c on x.candidateId = c.id where x.brandId={$brandId} and x.candidateId={$candidateId}");
						}
						$acct = $xrefdata[0]['accountId'];
						
						if (strtoupper($xrefdata[0]['keyword']) == 'RETAIL'){
							$linkAdd = "&s=2";
						}
						if (strtoupper($xrefdata[0]['keyword']) == 'CVSJOBS'){
							$linkAdd = "&s=1";
						}
						//if (intval($xrefdata[0]['accountId']) > 0){
						//	$linkAdd = "&a=".$xrefdata[0]['accountId'];
						//}
						
						if (strtoupper(substr($rcvdmsg,0,5)) == 'DEALS') {
							$promo=2;
							$updatedata = array('promo'=>2);
                            $updatewhere = array('candidateId'=>$xrefdata[0]['candidateId']);
                            Config::get('db')->update('candidateXref',$updatedata,$updatewhere);
                            $outmessage = "You are subscribed to {$thisbrand['storeBrand']} DEALS and DISCOUNTS. Msg&Data rates may apply. Reply STOP to Cancel or HELP for Support.";
                            $debug = self::send_sms_message($outmessage,$user,$keyword2,$brandId,$candidateId,$acct);
								$type = 8;
								$updatedata = array(
								'brandId'=>$brandId,
								'accountId'=>$acct,
								'candidateId'=>$candidateId,
								'type'=>$type,
								'message'=> $rcvdmsg
								 );
								Config::get('db')->insert('sms_messages',$updatedata);
						
						}else if (strtoupper(substr($rcvdcmd,0,1)) == 'H') {
                            $outmessage = "For Support with {$thisbrand['storeBrand']} Job Alerts, please email support@jobalarm.com. Msg&Data Rates May Apply. Reply STOP to Cancel. ";
                            $debug = self::send_sms_message($outmessage,$user,$keyword2,$brandId,$candidateId,$acct); 
								$type = 8;
								$updatedata = array(
								'brandId'=>$brandId,
								'accountId'=>$acct,
								'candidateId'=>$candidateId,
								'type'=>$type,
								'message'=> $rcvdcmd
								 );
								Config::get('db')->insert('sms_messages',$updatedata);
								exit();
                        }else if (strtoupper(substr($rcvdcmd,0,1)) == 'Q') {
							$promo = 0;
                            $updatedata = array('promo'=>0);
                            $updatewhere = array('candidateId'=>$xrefdata[0]['candidateId']);
                            Config::get('db')->update('candidateXref',$updatedata,$updatewhere);
                            $outmessage = "You have opted out of {$thisbrand['storeBrand']} Job Alerts and will no longer receive msgs. For support, please email support@jobalarm.com. Msg&Data rates may apply.";
                            $debug = self::send_sms_message($outmessage,$user,$keyword2,$brandId,$candidateId,$acct);
								$type = 8;
								$updatedata = array(
								'brandId'=>$brandId,
								'accountId'=>$acct,
								'candidateId'=>$candidateId,
								'type'=>$type,
								'message'=> $rcvdcmd
								 );
								Config::get('db')->insert('sms_messages',$updatedata);
								exit();
						//} else if (strlen(trim($rcvdmsg)) > 4) {
						}else if (strtoupper(substr($rcvdmsg,0,6)) == 'NOT4ME') {
							$outmessage = "Sorry about that.  To search all CVS jobs, please go to https://jobs.cvshealth.com";
                            $debug = self::send_sms_message($outmessage,$user,$keyword2,$brandId,$candidateId,$acct);
								$type = 8;
								$updatedata = array(
								'brandId'=>$brandId,
								'accountId'=>$acct,
								'candidateId'=>$candidateId,
								'type'=>$type,
								'message'=> $rcvdmsg
								 );
								Config::get('db')->insert('sms_messages',$updatedata);
						//} else if (strlen(trim($rcvdmsg)) > 4) {
						}else if (preg_match('/^[0-9]{5}([- ]?[0-9]{4})?$/', $zip)) { 
    						$updatedata = array('zip'=>str_pad($zip, 5, '0', STR_PAD_LEFT));
                            $updatewhere = array('mobile'=>$mobileNum);
                            Config::get('db')->update('candidate',$updatedata,$updatewhere);
							$dough = array("29","30","31","35","36");
                            
							if (strtoupper(substr($keyword,0,7)) == 'DOMINOS' && in_array(substr($zip,0,2),$dough)){
								$brandId = 74;
							} 
							
							if ($xrefdata && count($xrefdata) > 0) {
                                $outmessage = "Thank You!  Here are your local {$thisbrand['storeBrand']} jobs: http://jobalarm.com/m.php?z={$zip}&m={$mobileNum}&b={$brandId}$linkAdd";
								$debug = self::send_sms_message($outmessage,$user,$keyword2,$brandId,$candidateId,$acct);   
							}
							if (intval($promo) > 0 && intval($promo) < 3) {
								$candidateCount = Config::get('db')->get_results("select * from cities_extended where zip={$zip}");
								$lat = $candidateCount[0]['latitude'];
								$lon = $candidateCount[0]['longitude']; 
								
								$storeList = Config::get('db')->get_results("select s.* from sms_stores s LEFT JOIN cities_extended ce on ce.zip = s.zip WHERE 3963*ACOS(SIN({$lat}/57.2958)*SIN(ce.latitude/57.2958)+ COS({$lat}/57.2958) *COS(ce.latitude/57.2958)*COS((ce.longitude/57.2958)-({$lon}/57.2958)))<=12");
								
								foreach ($storeList as $store) {
								$updatedata = array(
								'candidateId'=>$candidateId,
								'zip'=>$store['zip']
								 );
								Config::get('db')->insert('candidatecountXref',$updatedata);
																
								}
								                        
                            }
								$type = 8;
								$updatedata = array(
								'brandId'=>$brandId,
								'accountId'=>$acct,
								'candidateId'=>$candidateId,
								'type'=>$type,
								'message'=> $rcvdmsg
								 );
								Config::get('db')->insert('sms_messages',$updatedata);
						} else {
								$userXdata = Config::get('db')->get_results("SELECT * FROM `sms_messages` WHERE `candidateId`={$candidateId} and `type`=1 and `userId`>0 order by id desc");	
								$userX = 0;
								$userX = $userXdata[0]['userId'];	
								$updatedata = array(
									'userId'=>intval($userX),
									'accountId'=>$acct,
									'brandId'=>intval($brandId),
									'candidateId'=>intval($candidateId),
									'message'=> $rcvdmsg
                                    );
								Config::get('db')->insert('sms_messages',$updatedata);
									
							}
							exit();
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
	
		public static function receive2($keyword='') {
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
        //$keyword = isset($input_array["keyword"]) ? $input_array["keyword"] : '';
		$rcvdcmd = '';
		$search = '';
		$rcvdcmd = isset($input_array["command"]) ? $input_array["command"] : '';
		$iscmd = (strlen(trim($rcvdcmd)) > 0) ? true : false;
		$acct = 0;
		$rcvdmsg = isset($input_array["content"]) ? $input_array["content"] : '';
		$keyword2 = str_replace("'", "", $rcvdmsg);
		//$keyword = $keyword2;
		$keyword = '';
				
				if ((strtoupper(substr($keyword2,0,11)) == 'WENDYS JOBS' || strtoupper(substr($keyword2,0,10)) == 'WENDYSJOBS')) {
						$rcvdmsg = '';
						$keyword2 = 'WENDYS';
						$rcvdcmd = '';
						$acct = 280;
						$isreply = false;
						}
				if ((strtoupper(substr($keyword2,0,9)) == 'BELL JOBS' || strtoupper(substr($keyword2,0,8)) == 'BELLJOBS')) {
						$rcvdmsg = '';
						$keyword2 = 'BELL';
						$acct = 280;
						$isreply = false;
						}
				if (strtoupper(trim($keyword2)) == 'CHEESE CREW' || strtoupper(trim($keyword2)) == 'CHEESE MANAGER' || strtoupper(trim($keyword2)) == 'CHEESE MANAGERS' || strtoupper(trim($keyword2)) == 'CHEESE TECH') {
						$rcvdmsg = '';
						$keyword = trim(substr($keyword2,6));
						$keyword2 = 'CHEESE';
						$acct = 126;
						$isreply = false;
						}
				if (strtoupper(trim($keyword2)) == 'CVS RETAIL' || strtoupper(trim($keyword2)) == 'CVS MANAGER' || strtoupper(trim($keyword2)) == 'CVS CALL CENTER'){
					$isreply = false;
					$keyword = trim(substr($keyword2,4));
					$keyword2 = 'CVS';
					$acct = 258;
				}
						
		
		$keywords = Config::get('db')->get_results("SELECT * FROM sms_brand");
			//$row=mysql_fetch_array($keywords);
			//$count=mysql_num_rows($keywords);
			$rows=[];

			foreach($keywords as $row){
				$rows[] .= $row['keyword'];
								
			}
			
			if (in_array(strtoupper($keyword2),$rows)) {
			$keyword2 = strtoupper($keyword2);
			$rcvdmsg = '';
			}else{
			$keyword2 = '';
			}	
		
		$isreply = (strlen(trim($rcvdmsg)) > 0) ? true : false;
		$account = (isset($_REQUEST['account'])) ? $_REQUEST['account'] : null;
		$zip = substr($rcvdmsg,0,5);
		//$keyword2 = $keyword;
		$promo = 0;
		$brandOrig = 0;
		$linkAdd = '';
		
		//$demo = array("BOJANGLES","CHURCHS","TOOLS","FISH","MURPHY","PAPA","ADVANCE","SHAKE","BOSTON","KING","NAPA","CAPTAIN","WALGREENS","RITE","SPEEDWAY","GAP","CASTLE","TACO","CHICK","ZAXBYS","HOWIES","MCDONALDS","HUT","KFC","POLLO","WHOPPER","WICH","CARE","BANK","PILOT","SPECTRUM");
		If (strtoupper(substr($keyword2,0,3)) == 'FUN' && (substr($user,0,4) == '1305' || substr($user,0,4) == '1786' || substr($user,0,4) == '1813')){
			return;
		}
		If (strtoupper(substr($keyword2,0,5)) == 'ARBYS' && strtoupper($rcvdmsg) == '') {
                        $outmessage2 = $user." just tried JobAlarm for ARBYS ";
						$user2 = "12148500163";
						$brandId2 = 27;
						$candidateId2 = 4717;
                        $debug2 = self::send_sms_message2($outmessage2,$user2,$keyword2,$brandId2,$candidateId2);
		}
		If (strtoupper(substr($keyword2,0,5)) == 'CHILL' && strtoupper($rcvdmsg) == '') {
                        $outmessage2 = $user." just tried JobAlarm for DAIRY QUEEN ";
						$user2 = "12148500163";
						$brandId2 = 4;
						$candidateId2 = 4717;
                        $debug2 = self::send_sms_message2($outmessage2,$user2,$keyword2,$brandId2,$candidateId2);
		}
		If (strtoupper(substr($keyword2,0,6)) == 'WENDYS' && strtoupper($rcvdmsg) == '') {
                        $outmessage2 = $user." just tried JobAlarm for WENDYS ";
						$user2 = "12148500163";
						$brandId2 = 42;
						$candidateId2 = 4717;
                        $debug2 = self::send_sms_message2($outmessage2,$user2,$keyword2,$brandId2,$candidateId2);
		}
		If (strtoupper(substr($keyword2,0,7)) == 'POPEYES' && strtoupper($rcvdmsg) == '') {
                        $outmessage2 = $user." just tried JobAlarm for POPEYES ";
						$user2 = "12148500163";
						$brandId2 = 36;
						$candidateId2 = 4717;
                        $debug2 = self::send_sms_message2($outmessage2,$user2,$keyword2,$brandId2,$candidateId2);
		}
		If (strtoupper(substr($keyword2,0,4)) == 'KING' && strtoupper($rcvdmsg) == '') {
                        $outmessage2 = $user." just tried JobAlarm for BURGER KING ";
						$user2 = "12148500163";
						$brandId2 = 38;
						$candidateId2 = 4717;
                        $debug2 = self::send_sms_message2($outmessage2,$user2,$keyword2,$brandId2,$candidateId2);
		}
		If (strtoupper(substr($keyword2,0,7)) == 'CHURCHS' && strtoupper($rcvdmsg) == '') {
                        $outmessage2 = $user." just tried JobAlarm for CHURCHS ";
						$user2 = "12148500163";
						$brandId2 = 38;
						$candidateId2 = 4717;
                        $debug2 = self::send_sms_message2($outmessage2,$user2,$keyword2,$brandId2,$candidateId2);
		}
		If (strtoupper(substr($keyword2,0,7)) == 'EXPRESS' && strtoupper($rcvdmsg) == '') {
                        $outmessage2 = $user." just tried JobAlarm for EXPRESS ";
						$user2 = "12148500163";
						$brandId2 = 38;
						$candidateId2 = 4717;
                        $debug2 = self::send_sms_message2($outmessage2,$user2,$keyword2,$brandId2,$candidateId2);
		}
		If (strtoupper(substr($keyword2,0,3)) == 'KFC' && strtoupper($rcvdmsg) == '') {
                        $outmessage2 = $user." just tried JobAlarm for KFC ";
						$user2 = "12148500163";
						$brandId2 = 38;
						$candidateId2 = 4717;
                        $debug2 = self::send_sms_message2($outmessage2,$user2,$keyword2,$brandId2,$candidateId2);
		}
		$debug = "";
        //if (strlen($user) == 11) {
		if ($user) {
            $mobileNum = substr($user,1);
			$promo = 1;
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
				if (in_array(strtoupper($keyword2),$rows)) {
                $branddata = Config::get('db')->get_results("SELECT `id` as brandId, type, keyword, storeBrand from sms_brand where keyword='".strtoupper($keyword2)."'");
                }else{
				$branddata = Config::get('db')->get_results("SELECT c.*, x.brandOrig as brandId, s.type, s.keyword, s.storeBrand FROM `candidate` c left outer join `candidateXref` as x on x.candidateId = c.id left outer join `sms_brand` as s on s.id = x.brandOrig WHERE c.mobile ='{$mobileNum}' order by x.subscribeDate desc");	
				}
			if ($branddata && count($branddata) > 0) {
                    $thisbrand = $branddata[0];
					$brandId = $thisbrand['brandId'];
					if ($acct ==0){
					$acct = $thisbrand['type'];
					}
					$brandOrig = $brandId;
												
				if (!$isreply && !$iscmd) {
                  //if ((strtoupper(substr($keyword,0,3)) == 'CVS' && in_array(strtoupper($rcvdmsg),$demo)) || (!$isreply && !$iscmd)) {   
						$xrefdata = Config::get('db')->get_results("select x.*, c.accountXref from `candidateXref` as x left join `candidate` as c on x.candidateId = c.id where x.brandOrig={$brandOrig} and x.candidateId={$candidateId}");	
						
						$type = 8;
						
						$updatedata = array(
						'brandId'=>$brandId,
						'accountId'=>$acct,
						'candidateId'=>$candidateId,
						'type'=>$type,
						'message'=> $keyword2
                         );
						Config::get('db')->insert('sms_messages',$updatedata);
						
						if ($xrefdata && count($xrefdata) > 0) {
							$promo = $xrefdata[0]['promo'];
							$acct = $xrefdata[0]['accountId'];
							
							//if (strtoupper($keyword) == 'RETAIL'){
							//$updateKey = "RETAIL";
							//}else if (strtoupper($keyword) == 'CVSJOBS'){
							//$updateKey = "CVSJOBS";
							//}else{
							//$updateKey = strtoupper($keyword2);
							//}
							
								$updatedata = array('promo'=>1,
													'brandId'=>$brandOrig,
													'shortCode' =>58046,
													'keyword'=>strtoupper($keyword)
													);
								
								$updatewhere = array('id'=>$xrefdata[0]['id']);
								Config::get('db')->update('candidateXref',$updatedata,$updatewhere);
								$outmessage = "Welcome to {$thisbrand['storeBrand']} Job Alerts! Plz reply with your ZIP CODE to receive your local jobs. Reply HELP for help or STOP to cancel. Msg&Data Rates May Apply";
								$debug = self::send_sms_message2($outmessage,$user,$keyword2,$brandId,$candidateId,$acct);
								exit();
                        } else {
							
							        $insertXrefData = array(
                                    'candidateId' => $candidateId,
                                    'accountId' => $acct,
									'shortCode' =>58046,
                                    'promo' => 1,
                                    'keyword' => strtoupper($keyword),
									'keyword2' => $thisbrand['keyword'],
                                    'brandId' => $brandOrig,
									'brandOrig' => $thisbrand['brandId']
                                );
                            								                           
							Config::get('db')->insert('candidateXref',$insertXrefData);
                            $candidateXrefId = Config::get('db')->lastid();
                            
							$outmessage = "Welcome to {$thisbrand['storeBrand']} Job Alerts! Reply with your ZIP CODE to receive your local jobs. Reply HELP for help or STOP to cancel. Msg&Data Rates May Apply";
                            $debug = self::send_sms_message2($outmessage,$user,$keyword2,$thisbrand['id'],$candidateId,$acct);
                            exit();
                        }
                    } else {
                            
                        $xrefdata = Config::get('db')->get_results("select x.*, c.accountXref from `candidateXref` as x left join `candidate` as c on x.candidateId = c.id where x.brandId={$brandId} and x.candidateId={$candidateId}");
						$acct = $xrefdata[0]['accountId'];
						$keySearch = $xrefdata[0]['keyword'];
						
						if ($keySearch != '' && $keySearch != 'JOBALARM'){
							$search = $keySearch; 
							$linkAdd = "&s=".$search;
						}
												
						//if (strtoupper($xrefdata[0]['keyword']) == 'RETAIL'){
						//	$linkAdd = "&s=2";
						//}
						//if (strtoupper($xrefdata[0]['keyword']) == 'CVSJOBS'){
						//	$linkAdd = "&s=1";
						//}
						//if ($search){
						//	$linkAdd = "&s=".$search;
						//}						
						
						if (strtoupper(substr($rcvdmsg,0,5)) == 'DEALS') {
							$promo=2;
							$updatedata = array('promo'=>2);
                            $updatewhere = array('candidateId'=>$xrefdata[0]['candidateId']);
                            Config::get('db')->update('candidateXref',$updatedata,$updatewhere);
                            $outmessage = "You are subscribed to {$thisbrand['storeBrand']} DEALS and DISCOUNTS. Msg&Data rates may apply. Reply STOP to Cancel or HELP for Support.";
                            $debug = self::send_sms_message2($outmessage,$user,$keyword2,$brandId,$candidateId,$acct);
								$type = 8;
								$updatedata = array(
								'brandId'=>$brandId,
								'accountId'=>$acct,
								'candidateId'=>$candidateId,
								'type'=>$type,
								'message'=> $rcvdmsg
								 );
								Config::get('db')->insert('sms_messages',$updatedata);
						
						}else if (strtoupper(substr($rcvdcmd,0,1)) == 'H') {
                            $outmessage = "For Support with {$thisbrand['storeBrand']} Job Alerts, please email support@jobalarm.com. Msg&Data Rates May Apply. Reply STOP to Cancel. ";
                            $debug = self::send_sms_message2($outmessage,$user,$keyword2,$brandId,$candidateId,$acct); 
								$type = 8;
								$updatedata = array(
								'brandId'=>$brandId,
								'accountId'=>$acct,
								'candidateId'=>$candidateId,
								'type'=>$type,
								'message'=> $rcvdcmd
								 );
								Config::get('db')->insert('sms_messages',$updatedata);
								exit();
                        }else if (strtoupper(substr($rcvdcmd,0,1)) == 'Q') {
							$promo = 0;
                            $updatedata = array('promo'=>0);
                            $updatewhere = array('candidateId'=>$xrefdata[0]['candidateId']);
                            Config::get('db')->update('candidateXref',$updatedata,$updatewhere);
                            $outmessage = "You have opted out of {$thisbrand['storeBrand']} Job Alerts and will no longer receive msgs. For support, please email support@jobalarm.com. Msg&Data rates may apply.";
                            $debug = self::send_sms_message2($outmessage,$user,$keyword2,$brandId,$candidateId,$acct);
								$type = 8;
								$updatedata = array(
								'brandId'=>$brandId,
								'accountId'=>$acct,
								'candidateId'=>$candidateId,
								'type'=>$type,
								'message'=> $rcvdcmd
								 );
								Config::get('db')->insert('sms_messages',$updatedata);
								exit();
						//} else if (strlen(trim($rcvdmsg)) > 4) {
						}else if (strtoupper(substr($rcvdmsg,0,6)) == 'NOT4ME') {
							$outmessage = "Sorry about that.  To search all CVS jobs, please go to https://jobs.cvshealth.com";
                            $debug = self::send_sms_message2($outmessage,$user,$keyword2,$brandId,$candidateId,$acct);
								$type = 8;
								$updatedata = array(
								'brandId'=>$brandId,
								'accountId'=>$acct,
								'candidateId'=>$candidateId,
								'type'=>$type,
								'message'=> $rcvdmsg
								 );
								Config::get('db')->insert('sms_messages',$updatedata);
						//} else if (strlen(trim($rcvdmsg)) > 4) {
						}else if (preg_match('/^[0-9]{5}([- ]?[0-9]{4})?$/', $zip)) { 
    						$updatedata = array('zip'=>str_pad($zip, 5, '0', STR_PAD_LEFT));
                            $updatewhere = array('mobile'=>$mobileNum);
                            Config::get('db')->update('candidate',$updatedata,$updatewhere);
							$dough = array("29","30","31","35","36");
                            
							if (strtoupper(substr($keyword2,0,7)) == 'DOMINOS' && in_array(substr($zip,0,2),$dough)){
								$brandId = 74;
							} 
							
							if ($xrefdata && count($xrefdata) > 0) {
                                $outmessage = "Thank You!  Here are your local {$thisbrand['storeBrand']} jobs: http://jobalarm.com/m.php?z={$zip}&m={$mobileNum}&b={$brandId}$linkAdd";
								$debug = self::send_sms_message2($outmessage,$user,$keyword2,$brandId,$candidateId,$acct);   
							}
							if (intval($promo) > 0 && intval($promo) < 3) {
								$candidateCount = Config::get('db')->get_results("select * from cities_extended where zip={$zip}");
								$lat = $candidateCount[0]['latitude'];
								$lon = $candidateCount[0]['longitude']; 
								
								$storeList = Config::get('db')->get_results("select s.* from sms_stores s LEFT JOIN cities_extended ce on ce.zip = s.zip WHERE 3963*ACOS(SIN({$lat}/57.2958)*SIN(ce.latitude/57.2958)+ COS({$lat}/57.2958) *COS(ce.latitude/57.2958)*COS((ce.longitude/57.2958)-({$lon}/57.2958)))<=12");
								
								foreach ($storeList as $store) {
								$updatedata = array(
								'candidateId'=>$candidateId,
								'zip'=>$store['zip']
								 );
								Config::get('db')->insert('candidatecountXref',$updatedata);
																
								}
								                        
                            }
								$type = 8;
								$updatedata = array(
								'brandId'=>$brandId,
								'accountId'=>$acct,
								'candidateId'=>$candidateId,
								'type'=>$type,
								'message'=> $rcvdmsg
								 );
								Config::get('db')->insert('sms_messages',$updatedata);
						} else {
								$userXdata = Config::get('db')->get_results("SELECT * FROM `sms_messages` WHERE `candidateId`={$candidateId} and `type`=1 and `userId`>0 order by id desc");	
								$userX = 0;
								$userX = $userXdata[0]['userId'];	
								$updatedata = array(
									'userId'=>intval($userX),
									'accountId'=>$acct,
									'brandId'=>intval($brandId),
									'candidateId'=>intval($candidateId),
									'message'=> $rcvdmsg
                                    );
								Config::get('db')->insert('sms_messages',$updatedata);
									
							}   exit();                                        
                    }
                }else{
				//do nothing;	
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
