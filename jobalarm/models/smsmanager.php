<?php

class SmsManager {

	public static function addSMS($surveyId, $mobileNum, $message, $options) {
        $companyId = 0;
        $currentSenderId = 0;
        $staticvars = Survey::getStaticVars($surveyId);
        if (isset($options['userId']) && $options['userId'] > 0) {
            $currentSenderId = $options['userId'];
        }
        if (isset($options['userId'])) {
            $user = User::load($options['userId']);
            if ($user && isset($user['companyId']) && $user['companyId'] > 0) {
                $companyId = $user['companyId'];
            }
        }
        if (isset($options['type']) && isset($options['reply']) && $options['type'] == 1 && $options['reply'] == 1) {
            $person = Person::read($mobileNum,true);
            if ($person && isset($person['currentSenderId'])) {
                $currentSenderId = $person['currentSenderId'];
            } 
       }
        //last ditch effort to set id.
        if ($currentSenderId == 0) {
            if (isset($staticvars['tpl_defaultadmin']) && $staticvars['tpl_defaultadmin'] > 0) {
                $currentSenderId = $staticvars['tpl_defaultadmin'];
            }
        }
        
        $smsData = Array(
            'surveyId' => (isset($options['peopleId'])) ? Person::getCurrentSurvey($options['peopleId']) : 1,
            'mobileNum' => $mobileNum,
            'message' => Config::get('db')->filter($message),
            'peopleId' => (isset($options['peopleId']) ? $options['peopleId'] : 0),
            'userId' => $currentSenderId,
            'companyId' => $companyId,
            'messageDate' => (isset($options['date']) ? $options['date'] : date('Y-m-d H:i:s')),
            'type' => (isset($options['type']) ? $options['type'] : 1),
            'isReply' => (isset($options['reply']) ? $options['reply'] : 0),
            'viewed' => (isset($options['viewed']) ? $options['viewed'] : 0)
        );
        return self::create($smsData);
    }

    /* currently the only difference between send_messages, send_messages_with_optout,
     * and send_optouts is send_optouts does not include the call to Person::incSmsCount()
     */
    public static function send_messages($people, $message, $nolog) {
        if (count($people) < 1) /* should never get here but just in case... */
            return -1;

        $post = '';

        $numbers = '';


        //var_dump($people);
        $userId = (Config::get('loggedIn') > 0) ? Config::get('loggedIn') : 0;

        foreach($people as $person) {
            $mobile_num = $person['mobileNum'];
            $survey_id = $person['surveyId'];

            $numbers .= "&PhoneNumbers[]={$mobile_num}";

            if (!isset($nolog)) {
                $user = Person::read($mobile_num, true);
                Person::update($user['id'],array('currentSenderId'=>$userId));
                self::addSMS($survey_id, $mobile_num, $message, array('peopleId' => $user['id'], 'userId' => User::getId(), 'type' => 2));
            } else {
                $user = Person::read($mobile_num, true);
                self::addSMS($survey_id, $mobile_num, $message, array('peopleId' => $user['id'], 'userId' => User::getId(), 'type' => 3));                
            }
            if (!self::isPerformingSMSSurvey($mobile_num))
                Person::incSmsCount($mobile_num);
        }

        $post .= $numbers;
        $post .= "&Message=" . urlencode(substr($message, 0, 160));
        $post .= "&MessageTypeID=1";

        return Utility::curl_request(EZ_LOGIN, EZ_PASSWORD, EZ_SEND_URL, $post);
    }

    public static function send_messages_with_optout($people, $message, $nolog) {
        if (count($people) < 1) /* should never get here but just in case... */
            return -1;

        $post = '';
        $numbers = '';

        $userId = (Config::get('loggedIn') > 0) ? Config::get('loggedIn') : 0;

        foreach($people as $person) {
            $mobile_num = $person['mobileNum'];
            $survey_id = $person['surveyId'];

            $numbers .= "&PhoneNumbers[]={$mobile_num}";

            if (!isset($nolog)) {
                $user = Person::read($mobile_num, true);
                Person::update($user['id'],array('currentSenderId'=>$userId));

                self::addSMS($survey_id, $mobile_num, $message, array('peopleId' => $user['id'], 'userId' => User::getId(), 'type' => 2));
            }

            Person::incSmsCount($mobile_num);
        }

        $post .= $numbers;
        $post .= "&Message=" . urlencode(substr($message, 0, 160));
        $post .= "&MessageTypeID=1";

        return Utility::curl_request(EZ_LOGIN, EZ_PASSWORD, EZ_SEND_URL, $post);
    }

    public static function send_optouts($people, $message, $nolog) {
        if (count($people) < 1) /* should never get here but just in case... */
            return -1;

        $post = '';
        $numbers = '';
        $userId = (Config::get('loggedIn') > 0) ? Config::get('loggedIn') : 0;

        foreach($people as $person) {
            $mobile_num = $person['mobileNum'];
            $user = Person::read($mobile_num, true);
            //$survey_id = $person['surveyId'];
            Person::update($user['id'],array('currentSenderId'=>$userId));

            $numbers .= "&PhoneNumbers[]={$mobile_num}";

        }

        $post .= $numbers;
        $post .= "&Message=" . urlencode(substr($message, 0, 160));
        $post .= "&MessageTypeID=1";

        return Utility::curl_request(EZ_LOGIN, EZ_PASSWORD, EZ_SEND_URL, $post);
    }

    public static function sendSMS($options) {
        //if (is_array($options['numbers']) && count($options['numbers']) > 0) {
             file_put_contents("sendsmslog.txt", print_r($options,true));
            $post = '';
            $keyword = '';
            $header = Array("Content-Type: application/xml");
    		$mobile = '';
    		$post = $options['message'];
    		$keyword = $options['keyword'];
    		$slooce = $options['login'];
			$shortCode = $options['shortCode'];
			$keyword2 = "JOBALARM58046";
    		$output = "";

            foreach($options['numbers'] as $k => $n) {
            //foreach($options['numbers'] as $n) {
         		$mobile = $n;
				if (intval($shortCode)==47711){
          		$url = 'http://sloocetech.net:8084/spi-war/spi/jobalarm45/' . $mobile . '/' . $keyword[$k] . '/messages/mt';
          		}else{
				$url = 'https://jobalarm.cloud.sloocetech.net/slooce_apps/spi/jobalarm45/' . $mobile . '/' . $keyword2 . '/messages/mt';
        		}
          		//cho $mobile;
          		//echo $keyword[$k];
          		//echo $post[$k];
          		//echo $url;
          	
          		
          		
          		$output .= Utility::curl_request($slooce, $url, $post[$k], $header);
    
       	}
    return $output; 
    }
    
   
//}

    //  public static function sendSMS($options) {
    //    $poststring = "User=" . urlencode(EZ_LOGIN);
    //    $poststring .= "&Password=" . urlencode(EZ_PASSWORD);
    //
    //    $numberstring = '';
    //
    //    if (is_array($options['numbers']) && count($options['numbers']) > 0) {
    //      foreach ($options['numbers'] as $person) {
    //        $number = $person['mobileNum'];
    //
    //        if (!self::isOptOut($number) && !Person::onHold($number) && !Person::isOptOut($number)) {
    //          $surveyId = $person['surveyId'];
    //          $numberstring .= "&PhoneNumbers[]={$number}";
    //
    //          if (!isset($options['nolog'])) {
    //            $userData = Person::read($number, true);
    //            //$userData = SurveyAdmin::getPerson($number);
    //            $suboptions = array();
    //            $suboptions['peopleId'] = $userData['id'];
    //            $suboptions['userId'] = User::getId();
    //            $suboptions['type'] = 2;
    //            self::addSMS($surveyId, $number, $options['message'], $suboptions);
    //          }
    //        }
    //      }
    //      $poststring .= $numberstring;
    //      $poststring .= "&Message=" . urlencode(substr($options['message'], 0, 160));
    //      $poststring .= "&MessageTypeID=1";
    //
    //      $response = Utility::curl_request(EZ_LOGIN, EZ_PASSWORD, EZ_SEND_URL, $poststring);
    //      return $response;
    //    }
    //
    //    return json_encode(array('success' => false));
    //    //return "Success";
    //  }

    public static function sendVoiceSMS($options) {
        $poststring = "User=" . urlencode(EZ_LOGIN);
        $poststring .= "&Password=" . urlencode(EZ_PASSWORD);

        $numberstring = '';
        $fileURL = Config::get('base_url') . '/dat/temp/' . $options['wavfile'];
        //$fileURL = "http://jobalarm.com/admin/dat/temp/outputfile.wav";
        if (is_array($options['numbers']) && count($options['numbers']) > 0) {
            foreach ($options['numbers'] as $person) {
                $number = $person['mobileNum'];
                //if (!self::isOptOut($number) && !Person::onHold($number) && !Person::isOptOut($number)) {
                //    $surveyId = $person['surveyId'];
                $numberstring .= "&Phonenumbers[]={$number}";

                //if (!isset($options['nolog'])) {
                //    $userData = Person::read($number,true);
                //    //$userData = SurveyAdmin::getPerson($number);
                //    $suboptions = array();
                //    $suboptions['peopleId'] = $userData['id'];
                //    $suboptions['userId'] = User::getId();
                //    $suboptions['type'] = 2;
                //    self::addSMS($surveyId, $number, $options['message'], $suboptions);
                //}
                //}
            }
            $poststring .= $numberstring;
            $poststring .= "&Soundsource=" . urlencode($fileURL);
            $poststring .= "&Callerid=" . EZ_CALLER_ID;

            $response = Utility::curl_request(EZ_LOGIN, EZ_PASSWORD, EZ_VOICE_URL, $poststring);
            return $response;
        }
        return json_encode(array('success' => false));
        //return "Success";
    }

    public static function read($mobileNum, $surveyId = null) {
        $query = "
        SELECT *
        FROM sms_history
        WHERE
        mobileNum='{$mobileNum}'
        AND surveyId={$surveyId}
        AND active>0
    ";
        $dbData = Config::get('db')->get_results($query);
        if ($dbData && count($dbData) > 0) {
            return $dbData;
        }
        return false;
    }

    public static function create($data) {
        Config::get('db')->insert('sms_history', $data);
        return Config::get('db')->lastid();
    }

    public static function update($smsId, $data) {
        $where = array('id' => $smsId);
        Config::get('db')->update('sms_history', $data, $where, 1);
    }

    public static function delete($smsId) {
        $data = array('active' => 0);
        self::update($smsId, $data);
    }

    //Check if performing SMS Survey Responses
    public static function isPerformingSMSSurvey($mobileNum) {
        $query = "SELECT currentQuestionTime from people where mobileNum='{$mobileNum}' and currentQuestionTime > DATE_SUB( NOW() , INTERVAL 1 DAY )";
        $dbData = Config::get('db')->get_results($query);
        if (count($dbData) > 0) {
            return true;
        }
        return false;
    }

    //Get Current SMS Survey Question ID
    public static function getCurrentSMSSurveyQuestionId($mobileNum) {
        $query = "SELECT currentSurvey,currentQuestion from people where mobileNum='{$mobileNum}'";
        $dbData = Config::get('db')->get_results($query);
        if (count($dbData) > 0) {
            $questionId = $dbData[0]['currentQuestion'];
            return $questionId;
        }
        return false;
    }

    //Get Current SMS Survey Question
    public static function getCurrentSMSSurveyQuestion($mobileNum) {
        $query = "SELECT currentSurvey,currentQuestion from people where mobileNum='{$mobileNum}'";
        $dbData = Config::get('db')->get_results($query);
        if (count($dbData) > 0) {
            $surveyId = $dbData[0]['currentSurvey'];
            $questionId = $dbData[0]['currentQuestion'];
            if (!($surveyId > 0)) {
                Log::add('[getCurrentSMSSurveyQuestion] Person Current Survey ID Invalid: ' . $surveyId . ' for mobileNum: ' . $mobileNum, LOG_TYPES::PEOPLE, LOG_SEVERITIES::CRITICAL);
                return false;
            }
            //echo "<h1>SURVEY ID: $surveyId</h1>";
            Survey::load($surveyId);
            $smsConfig = Survey::$_smsView;

            $smsQuestionList = Array();
            //echo "<br />yeah1</br />";
            // var_dump($smsConfig);
            //echo "<br />yeah2</br />";

            foreach ($smsConfig as $question) {
                $smsQuestionList[$question['id']] = $question['displayname'];
            }
            // var_dump($smsQuestionList);
            //echo "<br />yeah3</br />";
            if (isset($smsQuestionList[$questionId]))
                return $smsQuestionList[$questionId];
        }
        return false;
    }

    //Get Current SMS Survey Answer Matrix
    public static function getCurrentSMSSurveyAnswerMatrix($mobileNum) {
        $query = "SELECT currentAnswers from people where mobileNum='{$mobileNum}'";
        $dbData = Config::get('db')->get_results($query);
        $answers = array();
        if (count($dbData) > 0) {
            if (strlen($dbData[0]['currentAnswers']) > 2) {
                $answers = json_decode($dbData[0]['currentAnswers'], true);
            }
        }
        return $answers;
    }

    //Set Current SMS Survey Question
    public static function setCurrentSMSSurveyQuestion($mobileNum, $questionId) {
        $data = Array('currentQuestion' => $questionId, 'currentQuestionTime' => date('Y-m-d H:i:s'));
        $personId = Person::getUserIdFromMobile($mobileNum);
        Person::update($personId, $data);
    }

    //Begin the SMS Survey
    public static function startSMSSurvey($mobileNum) {
        //$person = self::getPerson($mobileNum);
        $startLog = '';
        $personId = Person::getUserIdFromMobile($mobileNum);
        $startLog .= 'PERSONID: ' . $personId . "\r\n";
        $surveyId = Person::getCurrentSurvey($personId);
        $startLog .= 'surveyId: ' . $surveyId . "\r\n";
        if (!($surveyId > 0)) {
            Log::add('[startSMSSurvey] Person Current Survey ID Invalid: ' . $surveyId . ' for mobileNum: ' . $mobileNum, LOG_TYPES::PEOPLE, LOG_SEVERITIES::CRITICAL);
            return false;
        }
        Survey::load($surveyId);
        $smsConfig = Survey::$_smsView;
        $startLog .= 'smsConfig: ' . print_r($smsConfig, true) . "\r\n";
        $smsQuestionList = Array();
        foreach ($smsConfig as $question) {
            $smsQuestionList[$question['id']] = $question['displayname'];
        }
        $startLog .= 'smsQuestionList: ' . print_r($smsQuestionList, true) . "\r\n";
        if (count($smsQuestionList) > 0) {
            reset($smsQuestionList);
            $questionId = key($smsQuestionList);
            $startLog .= 'questionId: ' . $questionId . "\r\n";

            $firstQuestion = current($smsQuestionList);
            $startLog .= 'firstQuestion: ' . $firstQuestion . "\r\n";

            //echo $questionId.' : '.$firstQuestion;
            self::setCurrentSMSSurveyQuestion($mobileNum, $questionId);
            Person::update($personId, Array('currentAnswers' => ''));
            file_put_contents('startLog.txt', $startLog);
            return $firstQuestion;
        }
        file_put_contents('startLog.txt', $startLog);
        return false;
    }

    //Advanced to Next SMS Survey Question
    public static function advanceSMSSurveyQuestion($mobileNum) {
        $query = "SELECT currentSurvey,currentQuestion from people where mobileNum='{$mobileNum}'";
        $dbData = Config::get('db')->get_results($query);
        if (count($dbData) > 0) {
            $surveyId = $dbData[0]['currentSurvey'];
            $questionId = $dbData[0]['currentQuestion'];
            Survey::load($surveyId);
            $smsConfig = Survey::$_smsView;
            $smsQuestionList = Array();
            foreach ($smsConfig as $question) {
                $smsQuestionList[$question['id']] = $question['displayname'];
            }
            $nextQuestion = Utility::getArrayNext($smsQuestionList, $questionId);
            if ($nextQuestion) {
                self::setCurrentSMSSurveyQuestion($mobileNum, $nextQuestion);
                return $nextQuestion;
            } else {
                return false;
                //we've reached the end
            }
        }
        return false;
    }

    //Update SMS Survey Data for Person
    public static function updateSMSSurveyAnswerData($mobileNum, $data) {
        $answerData = array_merge(self::getCurrentSMSSurveyAnswerMatrix($mobileNum), $data);
        $personData = Array('currentAnswers' => Config::get('db')->filter(json_encode($answerData)));
        $updatePersonWhere = Array('mobileNum' => $mobileNum);
        Config::get('db')->update('people', $personData, $updatePersonWhere);

        $personId = Person::getUserIdFromMobile($mobileNum);
        Person::read($personId);
        $surveyId = Person::getCurrentSurvey();
        $query = "select surveyResponseId FROM response WHERE surveyId={$surveyId} && mobileNum='{$mobileNum}'";
        $dbData = Config::get('db')->get_results($query);
        if ($dbData && count($dbData) > 0) {
            $responseId = $dbData[0]['surveyResponseId'];
            Person::updateFromResponse($surveyId, $personId, $responseId, RESPONSE_TYPES::RESPONSESMS);
            Person::updateFromCurrentAnswers($personId);
        }
        //Response::getSurveyId()
        //Config::get('db')->update('people', $data, $updatePersonWhere, 1);
    }

    //Sync latest SMS Survey answers to response
    public static function syncCurrentSMSResponses($mobileNum) {
        //$person = Person::read($mobileNum, true);
        $surveyId = Person::getCurrentSurvey();
        $smsSurveyAnswers = self::getCurrentSMSSurveyAnswerMatrix($mobileNum);
        $query = "select surveyResponseId FROM response WHERE surveyId={$surveyId} && mobileNum='{$mobileNum}'";
        $dbData = Config::get('db')->get_results($query);
        if ($dbData && count($dbData) > 0) {
            $responseId = $dbData[0]['surveyResponseId'];
        } else {
            Log::add('[syncCurrentSMSResponses] Failed to get response ID', LOG_TYPES::SMS, LOG_SEVERITIES::CRITICAL);
            return false;
        }
        Response::getResponseAnswers($surveyId, $responseId);
        $responseAnswers = Response::getResponseAnswers($surveyId, $responseId);
        $finalAnswers = (isset($responseAnswers['responseSMS'])) ? array_merge($responseAnswers['responseSMS'], $smsSurveyAnswers) : $smsSurveyAnswers;
        $finalAnswers['_updated_at'] = date('Y-m-d H:i:s');
        Response::updatePri($responseId, Array('responseSMS' => Config::get('db')->filter(json_encode($finalAnswers))));
        Response::updateSec($responseId, true);
        //        SurveyAdmin::updateResponse($surveyId, $mobileNum, Array('responseSMS'=>json_encode($finalAnswers)));
        return true;
    }

    //Complete SMS Survey and create response from answers
    public static function completeSMSSurvey($mobileNum) {
        self::syncCurrentSMSResponses($mobileNum);
        $updateData = Array(
            'currentQuestion' => '',
            'currentQuestionTime' => '0000-00-00 00:00:00',
            'currentAnswers' => ''
        );
        Config::get('db')->update('people', $updateData, Array('mobileNum' => $mobileNum));
        return true;
    }

    //Receive SMS Survey Question Answer
    public static function receiveSMSSurveyQuestionAnswer($mobileNum, $data) {
        $person = Person::read($mobileNum);
        $surveyId = $person['currentSurvey'];
        $currentQuestionId = self::getCurrentSMSSurveyQuestionId($mobileNum);
        $updateData = Array();
        $updateData[$currentQuestionId] = $data;
        file_put_contents("log/receivesms_sqa.txt", "DATA:", FILE_APPEND);
        file_put_contents("log/receivesms_sqa.txt", print_r($data, true), FILE_APPEND);
        file_put_contents("log/receivesms_sqa.txt", "\r\n", FILE_APPEND);
        self::updateSMSSurveyAnswerData($mobileNum, $updateData);
        $incomplete = self::advanceSMSSurveyQuestion($mobileNum);
        file_put_contents("log/receivesms_sqa.txt", "INCOMPLETE:", FILE_APPEND);
        file_put_contents("log/receivesms_sqa.txt", print_r($incomplete, true), FILE_APPEND);
        file_put_contents("log/receivesms_sqa.txt", "\r\n", FILE_APPEND);
        if (!$incomplete) {
            //we are done.
            $staticVars = Survey::getStaticVars($surveyId);
            $message = $staticVars['tpl_smssurveydone'];
            file_put_contents("log/receivesms_sqa.txt", "COMPLETE MESSAGE:", FILE_APPEND);
            file_put_contents("log/receivesms_sqa.txt", print_r($message, true), FILE_APPEND);
            file_put_contents("log/receivesms_sqa.txt", "\r\n", FILE_APPEND);
            self::completeSMSSurvey($mobileNum);
        } else {
            self::syncCurrentSMSResponses($mobileNum);
            $message = self::getCurrentSMSSurveyQuestion($mobileNum);
            file_put_contents("log/receivesms_sqa.txt", "NEXT MESSAGE:", FILE_APPEND);
            file_put_contents("log/receivesms_sqa.txt", print_r($message, true), FILE_APPEND);
            file_put_contents("log/receivesms_sqa.txt", "\r\n", FILE_APPEND);
        }
        if ($message) {
            return $message;
        } else {
            return NULL;
        }
    }

    public static function receive($mobileNum, $message, $options) {
        $surveyId = 0;
        $type = 1;
        $reply = 0;
        $isEmpRef = false;
        $smsData = false;
        try {
            $keywordList = Survey::getSurveyKeywordList();
            $refKeywords = Survey::getSurveyRefKeywordList();
            $msgDate = (isset($options['date'])) ? $options['date'] : date('Y-m-d H:i:s');
            $person = Person::read($mobileNum, true);
            $personId = ($person) ? $person['id'] : false;
            $surveyId = (isset($options['key']) && strlen(trim($options['key'])) > 0) ? (isset($keywordList[$options['key']]) ? $keywordList[$options['key']] : Person::getCurrentSurvey()) : Person::getCurrentSurvey();
            $keywordUsed = (isset($options['key']) && strlen(trim($options['key'])) > 0);
            $responseId = ($surveyId) ? Response::existsPri($surveyId, $mobileNum) : false;
            $surveyResponseId = ($surveyId && $responseId) ? Response::existsSec($surveyId, $responseId) : false;
            $staticVars = ($surveyId) ? Survey::getStaticVars($surveyId) : array();
            $startsmskey = ($staticVars) ? (isset($staticVars['tpl_surveykeyword'])) ? $staticVars['tpl_surveykeyword'] : false : false;
            $first_word = null;
            list($first_word) = explode(' ', trim($message));

            if ($first_word && strlen($first_word) > 0) {
                if (array_key_exists(strtolower($first_word), $refKeywords)) {
                    $surveyId = $refKeywords[strtolower($first_word)];
                    $isEmpRef = true;
                }
            }
            if ($person && $person['empRef'] == 1) {
                $isEmpRef = true;
            }
        }
        catch (Exception $e) {
            $debugData = "
                ERROR:" . $e->getMessage() . "\r\n
                PERSONID: $personId\r\n
                SURVEYID: $surveyId\r\n
                RESPONSEID: $responseId\r\n
                SURVEYRESPONSEID: $surveyResponseId\r\n
                STATICVARS: " . print_r($staticVars, true) . "\r\n
                STARTSMSKEY: $startsmskey\r\n
                ";
            file_put_contents("log/receive" . $mobileNum . '.txt', $debugData);
        }
        $outMessage = '';

        //if person exists:
        if (!$person) {
            if (isset($options['key'])) {
                $data = array(
                    'currentSurvey' => $surveyId,
                    'mobileNum' => $mobileNum,
                    'status' => 1
                );
                $personId = Person::create($data);
            }
        }
        if (!$isEmpRef) {
            //create response if doesn't exist
            if (!$responseId && $surveyId) {
                try {
                    //if no response created for this survey for this person, create it
                    $data = array(
                        'mobileNum' => $mobileNum
                    );
                    $blankResponse = Response::generateBlank($surveyId, $data);

                    $responseId = Response::add($surveyId, $blankResponse, true);
                    //$responseId = Response::createPri($surveyId,$mobileNum,array('peopleId'=>$personId,'response'=>$blankResponse,'searchData'=>$mobileNum));
                    //if (!$surveyResponseId) {
                    //    //if no survey##### response created, create it
                    //    $responseData = array();
                    //    //setup initial mobile Num data
                    //    if (isset($staticVars['tpl_mobilenum']) && strlen($staticVars['tpl_mobilenum']) > 0) {
                    //        $responseData['TMPTBL'.$staticVars['tpl_mobilenum']] = $mobileNum;
                    //        $responseData['TMPTBLDisplay'.$staticVars['tpl_mobilenum']] = $mobileNum;
                    //    }
                    //    Response::createSec($surveyId,$responseId,$responseData);
                    //}
                }
                catch (Exception $e) {
                    Log::add("Unable to add new response due to: " . $e->getMessage, LOG_TYPES::SMS, LOG_SEVERITIES::WARNING);
                }
            }

            $smsData = Array(
                'peopleId' => $personId,
                'type' => $type,
                'reply' => $reply,
                'date' => $msgDate
            );
            if (isset($options['reply'])) {
                $smsData['reply'] = 1;
                if (!self::isPerformingSMSSurvey($mobileNum)) {

                    if ((strlen($startsmskey) > 1) && (0 === strpos(strtolower($message), strtolower($startsmskey)))) {
                        $surveyId = Person::getCurrentSurvey($personId);
                        self::addSMS($surveyId, $mobileNum, $message, $smsData);


                        $staticVars = Survey::getStaticVars($surveyId);
                        $message = $staticVars['tpl_smssurveymsg'];

                        $smsoptions = Array(
                            'nolog' => true,
                            'numbers' => Array(Array('mobileNum' => $mobileNum, 'surveyId' => $surveyId)),
                            'message' => $message
                        );
                        $result = self::sendSMS($smsoptions);

                        if ($surveyId == 84505) {
                            $smsoptions = Array(
                                'nolog' => true,
                                'numbers' => Array(Array('mobileNum' => '2144159935', 'surveyId' => $surveyId)),
                                'message' => 'A Crisis Request has been received.  Call number is ' . $mobileNum
                            );
                            $result = self::sendSMS($smsoptions);
                            return true;
                        }

                        $outQuestion = self::startSMSSurvey($mobileNum);
                        if ($outQuestion) {
                            sleep(5);
                            $smsoptions = Array(
                                'nolog' => true,
                                'numbers' => Array(Array('mobileNum' => $mobileNum, 'surveyId' => $surveyId)),
                                'message' => $outQuestion
                            );
                            $result = self::sendSMS($smsoptions);
                            echo '{' . str_replace("\\", " ", $outQuestion) . '}';
                            return true;
                        }
                    }
                }
                if (self::isPerformingSMSSurvey($mobileNum)) {
                    $outQuestion = self::receiveSMSSurveyQuestionAnswer($mobileNum, $message);
                    if ($outQuestion) {
                        $smsoptions = Array(
                            'nolog' => true,
                            'numbers' => Array(Array('mobileNum' => $mobileNum, 'surveyId' => $surveyId)),
                            'message' => str_replace("\\", " ", $outQuestion)
                        );
                        $result = self::sendSMS($smsoptions);
                        echo '{' . str_replace("\\", " ", $outQuestion) . '}';
                        
                        $smsData['type'] = 3;
                        $smsData['reply'] = 1;
                        self::addSMS($surveyId, $mobileNum, $message, $smsData);
                        return true;
                    }
                }
            } else {
                if ($surveyId > 0) {
                    Person::update($personId, array('currentSurvey' => $surveyId));

                    $staticVars = Survey::getStaticVars($surveyId);
                    echo "{" . str_replace("\\", " ", $staticVars['tpl_smsmsg']) . "}";
                }
            }
        } else {
            $person = Person::read($personId);
            $survey = Survey::read($surveyId);
            //message a
            $messageA = str_replace('[company]', $survey['companyName'], $survey['messageA']);
            $messageA = str_replace('[mobilenum]', $mobileNum, $messageA);
            $messageA = str_replace('[surveylink]', $survey['surveyLink'], $messageA);
            //message b
            $messageB = str_replace('[company]', $survey['companyName'], $survey['messageB']);
            $messageB = str_replace('[mobilenum]', $mobileNum, $messageB);
            $messageB = str_replace('[surveylink]', $survey['surveyLink'], $messageB);
            if (isset($options['reply'])) {
                if ($person['empRef'] == 0) {
                    $smsoptions = Array(
                        'nolog' => true,
                        'numbers' => Array(Array('mobileNum' => $mobileNum, 'surveyId' => $surveyId)),
                        'message' => $messageA
                    );
                    $result = self::sendSMS($smsoptions);
                    Person::update($personId, array('currentSurvey' => $surveyId, 'empRef' => 1));
                } else
                    if ($person['empRef'] == 1) {
                        $data = array('surveyId' => $surveyId, 'refDate' => date('Y-m-d H:i:s'), 'fullName' => Config::get('db')->filter($message), 'mobileNum' => $mobileNum);
                        Config::get('db')->insert('emp_ref', $data);
                        $smsoptions = Array(
                            'nolog' => true,
                            'numbers' => Array(Array('mobileNum' => $mobileNum, 'surveyId' => $surveyId)),
                            'message' => $messageB
                        );
                        $result = self::sendSMS($smsoptions);
                        Person::update($personId, array('empRef' => 0));
                    }
            }
        }
        if ($keywordUsed) {
            if (SmsManager::isOptOut($mobileNum)) {
                SmsManager::optIn($mobileNum);
            }
            if (Person::isOptOut($mobileNum)) {
                Person::optIn($mobileNum);
            }
        }
        if (SmsManager::isDrivingMessage($message)) {
            Person::setHold($mobileNum);
        }
        return self::addSMS($surveyId, $mobileNum, $message, $smsData);
    }

    public static function getResponseId($smsHistoryId) {
        $query = "SELECT * from sms_history where id={$smsHistoryId}";
        $smsdata = Config::get('db')->get_results($query);
        if (count($smsdata) > 0) {
            $query = "
                select surveyResponseId from response where surveyId={$smsdata[0]['surveyId']} && mobileNum='{$smsdata[0]['mobileNum']}'
            ";
            $responsedata = Config::get('db')->get_results($query);
            if (count($responsedata) > 0) {
                return $responsedata[0]['surveyResponseId'];
            }
        }
        return null;
    }

    public static function optOut($mobileNum) {
        $data = array('mobileNum' => $mobileNum);
        Config::get('db')->insert('optout', $data);
    }

    public static function optIn($mobileNum) {
        $data = array('mobileNum' => $mobileNum);
        Config::get('db')->delete('optout', $data, 1);
    }

    public static function isOptOut($mobileNum) {
        $query = "SELECT mobileNum FROM optout WHERE mobileNum='{$mobileNum}'";
        $dbData = Config::get('db')->get_results($query);
        return ($dbData && count($dbData) > 0);
    }

    public static function getResponseIdFromSMSHistoryID($smsHistoryId) {
        $query = "SELECT * from sms_history where id={$smsHistoryId}";
        $smsdata = Config::get('db')->get_results($query);
        if (count($smsdata) > 0) {
            $query = "
                select surveyResponseId from response where surveyId={$smsdata[0]['surveyId']} && mobileNum='{$smsdata[0]['mobileNum']}'
            ";
            $responsedata = Config::get('db')->get_results($query);
            if (count($responsedata) > 0) {
                return $responsedata[0]['surveyResponseId'];
            }
        }
        return null;
    }

    public static function isDrivingMessage($message) {
        $driving_keys = array(
            'driving',
            'driveing',
            'dvg',
            'drvg',
            'drivin',
            'drivng',
            'drvng'
        );
        foreach ($driving_keys as $value)
            if (stripos($message, $value) !== false)
                return true;
        return false;
    }


    public static function syncSMSHistoryToUsers() {

        $query = "select surveyId from survey where active >0";

        $dbData = Config::get('db')->get_results($query);

        foreach($dbData as $survey) {
            //echo $survey['surveyId']."<br /><br />";
            $subQueryA = "
                SELECT id, userId, mobileNum,messageDate, type, isReply
                from sms_history
                WHERE
                ((type=2) OR (type=1 AND isReply = 1) OR (type=3))
                AND surveyId={$survey['surveyId']}
                AND active = 1
                order by mobileNum asc, messageDate asc
            ";
            $staticvars = Survey::getStaticVars($survey['surveyId']);
            $subDBDataA = Config::get('db')->get_results($subQueryA);
            $currentUserId = 0;
            $currentMobileNum = '';
            foreach($subDBDataA as $sms) {
                //var_dump($sms);
                if ($currentMobileNum != $sms['mobileNum']) {
                    //echo "<br />didn't equal mobileNum<br /><br />";
                    //if we are on a new mobileNumber lets reset
                    $currentMobileNum = $sms['mobileNum'];
                    $currentUserId = 0;
                    
                }
                if ($currentUserId == 0) {
                    if (isset($staticvars['tpl_defaultadmin']) && $staticvars['tpl_defaultadmin'] > 0) {
                        $currentUserId = $staticvars['tpl_defaultadmin'];
                    }
                }                
                if ($sms['type'] == 2 || ($sms['type'] == 3 && $sms['isReply'] == 0)) {
                
                    //echo "<br />hit type 2<br /><br />";
                    if ($sms['userId'] > 0) {
                        $currentUserId = $sms['userId'];
                    } 
                    
                } else {
                   
                    if (($sms['type'] == 3 || $sms['type'] == 1) && $sms['isReply'] == 1 && $currentUserId > 0) {
                        $data = array('userId' => $currentUserId);
                        $where = array('id'=>$sms['id'],'surveyId'=>$survey['surveyId'],'mobileNum'=>$currentMobileNum);
                    
                        Config::get('db')->update('sms_history',$data,$where);

                        $query = "update sms_history set userId={$currentUserId} where and surveyId={$survey['surveyId']} and mobileNum='{$currentMobileNum}' userId=0 and (type=3 or type=1) and isReply = 1 and messageDate < '{$sms['messageDate']}'";
                        Config::get('db')->query($query);
                    }
                }
            }


        }

        return true;
    }

}


