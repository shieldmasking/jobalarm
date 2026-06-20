<?php

//require_once('models/user.php');
define('SLOOCE_LOGIN', 'jobalarm45');
define('SLOOCE_PW', 'wet#%DFG^&FHHJ');
define('SLOOCE_API', 'http://sloocetech.net:8084/spi-war/spi/');


class Sms {	
		
	public static function receive2() {
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
		$key1 = array();
		$rcvdcmd = isset($input_array["command"]) ? $input_array["command"] : '';
		$iscmd = (strlen(trim($rcvdcmd)) > 0) ? true : false;
		$acct = 0;
		$acctStatus = 0;
		$rcvdmsg = isset($input_array["content"]) ? $input_array["content"] : '';
		$keyword2 = str_replace("'", '', $rcvdmsg);
		
		$key1 = explode(' ',trim($rcvdmsg));
		$keyword3 = preg_replace('/[^A-Za-z0-9\-]/', '', $key1[0]);		
						
										
		$keywords = Config::get('db')->get_results("SELECT s.*, a.keyword as alias, a.accountId as aAcct FROM `sms_brand` s left join `sms_brand_alias` as a on a.brandId = s.id ORDER BY a.keyword DESC");
	
			$rows=[];
			$alias=[];

			foreach($keywords as $row){
				$rows[] .= $row['keyword'];
				$alias[] .= $row['alias'];
												
			}
						
			if (in_array(strtoupper($keyword2),$alias)){
			$keydb = Config::get('db')->get_results("SELECT s.*, a.keyword as alias, a.deal as aliasDeal, a.type as aliasType FROM `sms_brand` s left join `sms_brand_alias` as a on a.brandId = s.id where a.keyword ='".strtoupper($keyword2)."'");
			$keyData = $keydb[0];
			$keyword2 = $keyData['keyword'];
			$keyAlias = trim($keyData['alias']);
			$aliasType = $keyData['aliasType'];
			$aliasDeal = $keyData['aliasDeal'];
				if (intval($aliasType)==1){
					$search = '';
					$rcvdmsg = '';
				}else if (intval($aliasType)==3){
					if(strpos($keyAlias, " ")>0){
					$search = substr($keyAlias, strpos($keyAlias, " ") + 1);
					$rcvdmsg = '';
					}else{
					$search = $keyAlias;
					$rcvdmsg = '';
					}
				
				}else{
					if(strpos($keyAlias, " ")>0){
					$rcvdmsg = substr($keyAlias, strpos($keyAlias, " ") + 1);
					}else{
					$rcvdmsg = $keyAlias;
					}
				}
			}else if(in_array(strtoupper($keyword3),$rows)) {
			$keyword2 = strtoupper($keyword3);
			$rcvdmsg = '';
			}else{
			//do nothing;
			}
		
				
		$isreply = (strlen(trim($rcvdmsg)) > 0) ? true : false;
		$account = (isset($_REQUEST['account'])) ? $_REQUEST['account'] : null;
		$zip = substr($rcvdmsg,0,5);
		//$keyword2 = $keyword;
		$promo = 0;
		$brandOrig = 0;
		$linkAdd = '';
		$debug = "";
        
		if ($user) {
            $mobileNum = substr($user,1);
			$promo = 1;
            $candidatedata = Config::get('db')->get_results("select * from candidate where mobile='{$mobileNum}'");
            $candidateId = 0;
        if ($candidatedata && count($candidatedata) > 0) {
                $candidateId = $candidatedata[0]['id'];
				$candidateZip = $candidatedata[0]['zip'];
            } else {
                $insertdata = array('mobile'=>$mobileNum);
                Config::get('db')->insert('candidate',$insertdata);
                $candidateId = Config::get('db')->lastid();
            }
            $outmessage = '';
            if ($candidateId > 0) {
				if (in_array(strtoupper($keyword2),$rows)) {
                $branddata = Config::get('db')->get_results("SELECT s.id as brandId, s.type as type, s.keyword, s.website, s.textLimit, s.storeBrand, s.active as brandActive, a.id as acct, a.status as acctStatus, a.pText as budget from `sms_brand` s left outer join `account_brand` as ab on ab.brandId = s.id left outer join `account` as a on a.id = ab.accountId where `keyword`='{$keyword2}'");
                }else{
				$branddata = Config::get('db')->get_results("SELECT c.*, x.brandOrig as brandId, s.accountId as acct, s.type as type, s.keyword, s.storeBrand, s.textLimit, s.active as brandActive, s.website FROM `candidate` c left outer join `candidateXref` as x on x.candidateId = c.id left outer join `sms_brand` as s on s.id = x.brandOrig WHERE c.mobile ='{$mobileNum}' order by x.subscribeDate desc");	
				}
			if ($branddata && count($branddata) > 0) {
                    $thisbrand = $branddata[0];
					$brandId = $thisbrand['brandId'];
					$brandOrig = $brandId;					
					$brandType = $thisbrand['type'];
					$acctLink = $thisbrand['website'];
					//$budget = intval($thisbrand['budget']);
					
					if (intval($brandType) >0){
					$acct = $thisbrand['acct'];
					}
					
																						
				if (!$isreply && !$iscmd) {						
						if (intval($thisbrand['textLimit'])==1) {
		                $outmessage2 = $user." just tried JobAlarm for ".$keyword2;
						$user2 = "12148500163";
						$brandId2 = 6;
						$msgType = 9;
						$candidateId2 = 4717;
						$debug2 = self::send_sms_message2($outmessage2,$user2,$keyword2,$brandId2,$candidateId2,$msgType);
						}						
						
						$xrefdata = Config::get('db')->get_results("select x.*, c.accountXref, b.type as brandType, b.active as brandActive from `candidateXref` as x left join `candidate` as c on x.candidateId = c.id left join `sms_brand` as b on b.id = x.brandOrig where x.brandOrig={$brandOrig} and x.candidateId={$candidateId}");	
						
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
														
							
								if (intval($thisbrand['brandActive'])>=1) {
								$updatedata = array('promo'=>1,
													'brandId'=>$brandOrig,
													'shortCode' =>58046,
													'search' =>$search,
													'keyword'=>strtoupper($keyword)
													);
								
								$updatewhere = array('id'=>$xrefdata[0]['id']);
								Config::get('db')->update('candidateXref',$updatedata,$updatewhere);
								}else{
								$updatedata = array('promo'=>1,
													'brandId'=>$brandOrig,
													'shortCode' =>58046,
													'search' =>$search,
													'groupId' =>1,
													'keyword'=>strtoupper($keyword)
													);
								
								$updatewhere = array('id'=>$xrefdata[0]['id']);
								Config::get('db')->update('candidateXref',$updatedata,$updatewhere);								
								exit();
								}
								
								if (intval($brandType) <=2 || intval($brandType)==4){
								$outmessage = "Welcome to {$thisbrand['storeBrand']} Job Alerts! Reply with your ZIP CODE to receive your local jobs. Reply HELP for help or STOP to cancel. Msg&Data Rates May Apply";
								}else if (intval($brandType) ==3){
								$outmessage = "Welcome to {$thisbrand['storeBrand']}! Complete your profile @ www.jobalarm.biz/app/?b={$brandOrig}&a={$acct}&m={$mobileNum}. Reply HELP for help or STOP to cancel. Msg&Data Rates May Apply";	
								}else if (intval($brandType) ==97){
								$outmessage = "{$thisbrand['storeBrand']} Deals. Up to 4 msgs/mo. Msg&Data Rates May Apply. Reply HELP for help or STOP to cancel.  Did you dine with us? Please take our Experience Survey here: https://bit.ly/2yDEJnd";
								}else if (intval($brandType) ==98){
								$outmessage = "Medical City Plano is the best place to have a baby!!  Take the survey here: https://goo.gl/81zEEQ and have a chance to win a $250 Visa gift card.  Msg&Data Rates May Apply. Reply HELP for help or STOP to cancel.";
								}else if (intval($brandType) ==99){
								$outmessage = "Welcome to Chef Sean's Surveys.  Please complete your Experience Survey here: https://bit.ly/2yDEJnd.  Message and Data Rates May Apply. Reply HELP for help or STOP to cancel.";
								}else{
								$outmessage = "{$thisbrand['storeBrand']} Job Alerts. Submit your application @ {$acctLink}. Reply HELP for help or STOP to cancel. Msg&Data Rates May Apply";	
								}
								$msgType = 9;
								$debug = self::send_sms_message2($outmessage,$user,$keyword2,$brandId,$candidateId,$acct,$msgType);
								exit();
                        } else {
							if (intval($thisbrand['brandActive'])>=1) {
							        $insertXrefData = array(
                                    'candidateId' => $candidateId,
                                    'accountId' => $acct,
									'shortCode' =>58046,
                                    'promo' => 1,
                                    'keyword' => $thisbrand['keyword'],
									'keyword2' => $thisbrand['keyword'],
									'search' => $search,
                                    'brandId' => $brandOrig,
									'brandOrig' => $thisbrand['brandId']
                                );
                            								                           
							Config::get('db')->insert('candidateXref',$insertXrefData);
                            $candidateXrefId = Config::get('db')->lastid();
							
							$updatedata = array('stageId'=>0);
                            $updatewhere = array('id'=>$candidateId);
                            Config::get('db')->update('candidate',$updatedata,$updatewhere);
							}else{
								$insertXrefData = array(
                                    'candidateId' => $candidateId,
                                    'accountId' => $acct,
									'shortCode' =>58046,
                                    'promo' => 1,
                                    'keyword' => $thisbrand['keyword'],
									'keyword2' => $thisbrand['keyword'],
									'search' => $search,
									'groupId' => 1,
                                    'brandId' => $brandOrig,
									'brandOrig' => $thisbrand['brandId']
                                );
                            								                           
							Config::get('db')->insert('candidateXref',$insertXrefData);
                            $candidateXrefId = Config::get('db')->lastid();
							
							$updatedata = array('stageId'=>0);
                            $updatewhere = array('id'=>$candidateId);
                            Config::get('db')->update('candidate',$updatedata,$updatewhere);
							exit();
							}
							
							if (intval($brandType) <=4){
								$outmessage = "Welcome to {$thisbrand['storeBrand']} Job Alerts! Reply with your ZIP CODE to receive your local jobs. Reply HELP for help or STOP to cancel. Msg&Data Rates May Apply";
								//}else if (intval($brandType) ==3){
								//$outmessage = "Welcome to {$thisbrand['storeBrand']} JobAlarm! Go to: www.jobalarm.biz/app/?b={$brandOrig}&a={$acct}&m={$candidateId}. Reply HELP for help or STOP to stop. Msg&Data Rates May Apply";
								//$outmessage = "{$thisbrand['storeBrand']} Alerts! www.jobalarm.biz/app/?b={$brandOrig}&a={$acct}&m={$candidateId}. No data? Just reply with your name.  Reply HELP for help or STOP to stop. Msg&Data Rates May Apply";
								}else if(intval($brandType) ==97){
								$outmessage = "{$thisbrand['storeBrand']} Deals. Up to 4 msgs/mo. Msg&Data Rates May Apply. Reply HELP for help or STOP to cancel.  Did you dine with us? Please take our Experience Survey here: https://bit.ly/2yDEJnd";
								}else if(intval($brandType) ==98){
								$outmessage = "Medical City Plano is the best place to have a baby!!  Take the survey here: https://goo.gl/81zEEQ and have a chance to win a $250 Visa gift card.  Msg&Data Rates May Apply. Reply HELP for help or STOP to cancel.";
								}else if(intval($brandType) ==99){
								$outmessage = "Welcome to Chef Sean's Surveys.  Please complete your Experience Survey here: https://bit.ly/2yDEJnd.  Message and Data Rates May Apply. Reply HELP for help or STOP to cancel.";
								}else{
								$outmessage = "{$thisbrand['storeBrand']} Job Alerts. Submit your application @ {$acctLink}. Reply HELP for help or STOP to cancel. Msg&Data Rates May Apply";	
								}	
                            $msgType = 9;
							//$outmessage = "Welcome to {$thisbrand['storeBrand']} Job Alerts! Reply with your ZIP CODE to receive your local jobs. Reply HELP for help or STOP to cancel. Msg&Data Rates May Apply";
                            $debug = self::send_sms_message2($outmessage,$user,$keyword2,$brandId,$candidateId,$acct,$msgType);
                            
							exit();
                        }
                    } else {
                            
                        $xrefdata = Config::get('db')->get_results("select x.*, a.status as accType, c.accountXref, c.zip, c.stageId as stage, b.type as brandType, b.website from `candidateXref` as x left outer join `account` as a on a.id = x.accountId left join `candidate` as c on x.candidateId = c.id left join `job` as j on j.brand = x.brandOrig left join `clickTrack` as l on l.mobile = c.mobile and l.trackId = j.twitterId left join `sms_brand` as b on b.id = x.brandOrig where x.brandOrig={$brandOrig} and x.candidateId={$candidateId} group by l.trackId order by x.subscribeDate DESC");
						$acct = $xrefdata[0]['accountId'];
						$keySearch = $xrefdata[0]['keyword'];
						$brandOrig = $xrefdata[0]['brandOrig'];
						$click = $xrefdata[0]['trackId'];
						$zipCode = $xrefdata[0]['zip'];
						$stageId = $xrefdata[0]['stage'];
						$bType = $xrefdata[0]['brandType'];
						$accType = $xrefdata[0]['accType'];
						$linkAdd .='';
						
						
												
						//if (strtoupper(substr($rcvdmsg,0,5)) == 'DEALS') {
						if (intval($aliasType) == 2) {
							
							if(count($xrefdata)>0){
							$updatedata = array('promo'=>$aliasType);
                            $updatewhere = array('candidateId'=>$xrefdata[0]['candidateId']);
                            Config::get('db')->update('candidateXref',$updatedata,$updatewhere);                   
                            }else{
								$insertXrefData = array(
                                    'candidateId' => $candidateId,
                                    'shortCode' =>58046,
                                    'promoMktng' => $aliasType,
                                    'keyword' => $keyword2,
									'keyword2' => $keyword2,
									'brandId' => $brandOrig,
									'brandOrig' => $brandOrig
                                );                            								                           
							Config::get('db')->insert('candidateXref',$insertXrefData);
							}
							$msgType = 7;
							$outmessage = "{$thisbrand['storeBrand']} deals! Your first deal is here: {$aliasDeal}  Up to 4msgs/month. Msg&Data rates may apply. Reply STOP to Cancel or HELP for Support.";
                            $debug = self::send_sms_message2($outmessage,$user,$keyword2,$brandId,$candidateId,$acct,$msgType);
							exit();
						}else if (strtoupper(substr($rcvdcmd,0,1)) == 'H') {
							$msgType = 6;
                            $outmessage = "For Support with {$thisbrand['storeBrand']} Job Alerts, please email support@jobalarm.biz. Msg&Data Rates May Apply. Reply STOP to Cancel. ";
                            $debug = self::send_sms_message2($outmessage,$user,$keyword2,$brandId,$candidateId,$acct,$msgType); 
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
							$msgType = 6;
							$promo = 0;
                            $updatedata = array('promo'=>0,
												'groupOld'=>18
												);
                            $updatewhere = array('candidateId'=>$xrefdata[0]['candidateId']);
                            Config::get('db')->update('candidateXref',$updatedata,$updatewhere);
                            $outmessage = "You have opted out of {$thisbrand['storeBrand']} Job Alerts and will no longer receive msgs. For support, please email support@jobalarm.biz. Msg&Data rates may apply.";
                            $debug = self::send_sms_message2($outmessage,$user,$keyword2,$brandId,$candidateId,$acct,$msgType);
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
							$msgType = 9;
							$outmessage = "Sorry about that.  To search all CVS jobs, please go to https://jobs.cvshealth.com";
                            $debug = self::send_sms_message2($outmessage,$user,$keyword2,$brandId,$candidateId,$acct,$msgType);
								
						}else if (preg_match('/^[0-9]{5}([- ]?[0-9]{4})?$/', $zip)) { 
    						
							$msgType = 2;
							$updatedata = array('zip'=>str_pad($zip, 5, '0', STR_PAD_LEFT));
                            $updatewhere = array('mobile'=>$mobileNum);
                            Config::get('db')->update('candidate',$updatedata,$updatewhere);
							
							$accType = self::findLocation($candidateId,$brandOrig,$zip);							
							
							if ($xrefdata[0]['search']){
							$linkAdd .= "&s=".$xrefdata[0]['search'];
							}
							if (intval($zip)>=30000 && intval($brandId)==170){
								$linkAdd .= "&a=391";							
							}
							if (intval($accType)>0){
								$linkAdd .= "&a=".$accType;						
							}else{
								$linkAdd .= "&a=".$acct;
							}							
							if (intval($bType)==4){
								$jobLink = $xrefdata[0]['website']."".$zip;							
							}else{
								$jobLink = "http://jobalarm.biz/m.php?z=".$zip."&m=".$candidateId."&b=".$brandOrig."".$linkAdd.". Mobile Apply Now to Complete Your Next Step!";
							}
																					
								$type = 8;
								$updatezipdata = array(
								'brandId'=>$brandOrig,
								'accountId'=>$acct,
								'candidateId'=>$candidateId,
								'type'=>$type,
								'message'=> $rcvdmsg
								);
								Config::get('db')->insert('sms_messages',$updatezipdata);						
							//usleep(100000);
							
							if ($xrefdata && count($xrefdata) > 0 && $brandOrig ==9) {
                                $outmessage = " Find your CVS job at https://jobs.cvshealth.com/";
								$debug = self::send_sms_message2($outmessage,$user,$keyword2,$brandId,$candidateId,$acct,$msgType);   
							}
							if ($xrefdata && count($xrefdata) > 0 && $brandId !=6 && $brandOrig !=9) {
                                $outmessage = "Thank You!  Here are your local {$thisbrand['storeBrand']} jobs: {$jobLink}";
								$debug = self::send_sms_message2($outmessage,$user,$keyword2,$brandId,$candidateId,$acct,$msgType);   
							}
							if ($xrefdata && count($xrefdata) > 0 && $brandId == 6 && $brandOrig !=9) {
                                $outmessage = "Thank You! Please go here to complete your profile: http://jobalarm.biz/app/?b=6&a=301&z={$zip}&m={$candidateId}";
								$debug = self::send_sms_message2($outmessage,$user,$keyword2,$brandId,$candidateId,$acct,$msgType);   
							}				
								
								
						}else if($zipCode && !$click && intval($stageId) < 2 && intval($brandType)!=3){
							
								$names = explode(' ',$rcvdmsg);
								
								$stageId = $stageId + 1;
																
								if (count($names) > 1){
									$firstName = $names[0];
									$lastName = $names[1];
								}else{
									$firstName = $names[0];
									$lastName = '';
								}
								
								if (intval($stageId) <2){
								$updatedata = array(
									'first_name'=>$firstName,
									'last_name'=>$lastName,
									'stageId'=>$stageId,					
									 );
									 $newApp = self::newapply($candidateId,$brandOrig);
									 
									 //$updateApp = array('pasteResume'=>$rcvdmsg);
									 //$updateAppWhere = array('candidateId'=>$candidateId);
									 //Config::get('db')->update('candidateApply',$updateApp,$updateAppWhere);
									 $updatewhere = array('mobile'=>$mobileNum);
									 Config::get('db')->update('candidate',$updatedata,$updatewhere);
								}else{
									$updatedata = array(
									'resume'=>$rcvdmsg
									);									
									
								$updatewhere = array('mobile'=>$mobileNum);
								Config::get('db')->update('candidate',$updatedata,$updatewhere);
								}
								
								$userXdata = Config::get('db')->get_results("SELECT * FROM `sms_messages` WHERE `candidateId`={$candidateId} and `type`=1 and `userId`>0 order by id desc");	
								$userX = 0;
								$userX = $userXdata[0]['userId'];
								$msgType = 8;
								$updatedata = array(
									'userId'=>intval($userX),
									'accountId'=>$acct,
									'type'=>$msgType,
									'brandId'=>intval($brandId),
									'candidateId'=>intval($candidateId),
									'message'=> $rcvdmsg
                                    );
								Config::get('db')->insert('sms_messages',$updatedata);
								
								if (intval($stageId) <2 && $brandId != 187 && $brandId != 188){
								$outmessage = "Thank you! Please reply with the position you are interested in and any skills or experience you have.";
								}else if (intval($stageId) <2 && intval($brandId) == 187){
								$outmessage = "We got ur message and will do our best to coordinate help asap. If we get confirmed help, we will text you. If your situation is dire, please call 911.";
								}else if (intval($stageId) <2 && intval($brandId) == 188){
								$outmessage = "Thank you!  We will send you help requests. When you receive a request, please confirm whether or not you can respond.";
								}else{
								$outmessage = "Thank you for using JobAlarm!  Your information will be submitted to this employer for consideration.";
								}	
								$msgType = 9;
								$debug = self::send_sms_message2($outmessage,$user,$keyword2,$brandId,$candidateId,$acct,$msgType);	
								
								$updatedata = array(
									'stageId'=>$stageId
									 );									
									 
								$updatewhere = array('mobile'=>$mobileNum);
								Config::get('db')->update('candidate',$updatedata,$updatewhere);
							
							}else if(intval($brandType) >=3){
							
								$names = explode(' ',$rcvdmsg);
								$stageId = $stageId + 1;
								
								if (count($names) > 1){
									$firstName = $names[0];
									$lastName = $names[1];
								}else{
									$firstName = $names[0];
								}
								
								if (intval($stageId)<2){
								$updatedata = array(
									'first_name'=>$firstName,
									'last_name'=>$lastName,
									'stageId'=>$stageId
									 );
									 $newApp = self::newapply($candidateId,$brandOrig);
									 $updatewhere = array('mobile'=>$mobileNum);
									Config::get('db')->update('candidate',$updatedata,$updatewhere);
								}else if (intval($stageId)==2){
								$updatedata = array(
									'stageId'=>$stageId,
									'resume'=>$rcvdmsg
									 );		
									$updatewhere = array('mobile'=>$mobileNum);
									Config::get('db')->update('candidate',$updatedata,$updatewhere);									 
								}else{
									//do nothing;
								}					
								
								
								if (intval($stageId) <2 && intval($brandId)!= 188 && intval($brandId)!= 187){
								$msgType = 9;
								$outmessage = "Thank you! Please reply with the position you are interested in and any skills or experience you have.";
								$debug = self::send_sms_message2($outmessage,$user,$keyword2,$brandId,$candidateId,$acct,$msgType);
								}else if (intval($stageId)==2 && intval($brandId)!= 188 && intval($brandId)!= 187){
								$msgType = 9;
								$outmessage = "Thank you for using JobAlarm!  Your information will be submitted to this employer for consideration.";
								$debug = self::send_sms_message2($outmessage,$user,$keyword2,$brandId,$candidateId,$acct,$msgType);
								}else if (intval($stageId)<2 && intval($brandId)== 187){
								$msgType = 9;
								$outmessage = "We got ur message and will do our best to coordinate help asap. If we get confirmed help, we will text you. If your situation is dire, please call 911.";
								$debug = self::send_sms_message2($outmessage,$user,$keyword2,$brandId,$candidateId,$acct,$msgType);
								}else if (intval($stageId)<2 && intval($brandId)== 188){
								$msgType = 9;
								$outmessage = "Thank you!  We will send you help requests. When you receive a request, please confirm whether or not you can respond.";
								$debug = self::send_sms_message2($outmessage,$user,$keyword2,$brandId,$candidateId,$acct,$msgType);
								}else{
								//do nothing;
								
								}								
									
							
							}else {
								$msgType = 3;
								$userXdata = Config::get('db')->get_results("SELECT * FROM `sms_messages` WHERE `candidateId`={$candidateId} and `type`=1 and `userId`>0 order by id desc");	
								$userX = 0;
								$userX = $userXdata[0]['userId'];	
								$updatedata = array(
									'userId'=>intval($userX),
									'accountId'=>$acct,
									'type'=>$msgType,
									'brandId'=>intval($brandId),
									'candidateId'=>intval($candidateId),
									'message'=> $rcvdmsg
                                    );
								Config::get('db')->insert('sms_messages',$updatedata);
								
								$xrefdata = array(
									'subscribeDate'=>date('Y-m-d H:i:s'),
									'type'=>3
                                    );
								$xrefwhere = array(
									'candidateId'=>$candidateId,
									'brandOrig'=>$brandId
                                    );
								Config::get('db')->update('candidateXref',$xrefdata,$xrefwhere);
								//$reset = tj.resetSMS();
							}   
							
						exit();                                        
                    }
                }else{
				//do nothing;	
				}
            }
            file_put_contents("sendsmslog.txt", $debug);

        }
             
    }

}