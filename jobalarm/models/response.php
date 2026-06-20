<?php

class Response {

    private static $_file_db;

    public static function readPri($surveyId, $responseId = null) {
        $whereAdd = "";

        if (isset($responseId) && $responseId > 0)
            $whereAdd = "AND surveyResponseId = {$responseId}";

        $query = "SELECT *
              FROM response
              WHERE surveyId = {$surveyId}
              {$whereAdd}
              AND active > 0";

        $responseData = Config::get('db')->get_results($query);

        if (count($responseData) == 1)
            return $responseData[0];
        if (count($responseData) > 1)
            return $responseData;

        return array();
    }

    public static function readSec($surveyId, $responseId = null) {
        $whereAdd = "";

        if (isset($responseId) && $responseId > 0) {
            $whereAdd = "WHERE responseId = {$responseId}";
        }

        $query = "SELECT *
              FROM survey{$surveyId}
              {$whereAdd}";

        $responseData = Config::get('db')->get_results($query);

        return (count($responseData) > 0) ? $responseData : array();
    }

    public static function add($surveyId, $responseData, $smsResponse = false) {
        $dataArray = self::getStaticFieldsFromData($surveyId, $responseData);
        $jobpostId = 0;
        //// HACK: FOR POST SURVEY
        if ($surveyId == 127884) {
            //var_dump($responseData);
            $dataArray['mobileNum'] = str_pad($responseData['_id'],10,'0',STR_PAD_LEFT);
            //add entry to jobs
            $keywordId = isset($responseData['23WUAsIaOi']) ? Company::getDefaultKeyword(User::getCompanyId($responseData['23WUAsIaOi'])) : 0;
            $jobData = array(
                'postDate' =>  substr(str_replace('T',' ',$responseData['_created_at']),0,19),
                'company' => isset($responseData['I4aL59gxZz']) ? Config::get('db')->filter($responseData['I4aL59gxZz']) : '',
                'position' => isset($responseData['slDsIKOH9I']) ? Config::get('db')->filter($responseData['slDsIKOH9I']) : '',

                'description' => isset($responseData['PixroJcgOX']) ? nl2br(Config::get('db')->filter($responseData['PixroJcgOX'])) : '',
                'requirements' => isset($responseData['TNSfTUJTPQ']) ? nl2br(Config::get('db')->filter($responseData['TNSfTUJTPQ'])) : '',
                'city' => isset($responseData['TQ0CvUVUXN_0']) ? Config::get('db')->filter($responseData['TQ0CvUVUXN_0']) : '',
                'state' => isset($responseData['TQ0CvUVUXN_1']) ? Config::get('db')->filter($responseData['TQ0CvUVUXN_1']) : '',
                'zip' => isset($responseData['TQ0CvUVUXN_2']) ? Config::get('db')->filter($responseData['TQ0CvUVUXN_2']) : '',
                'compensation' => isset($responseData['keqxqvwMSS']) ? Config::get('db')->filter($responseData['keqxqvwMSS']) : '',
                'image' => isset($responseData['268QKmhHaq']) ? basename($responseData['268QKmhHaq']) : '',
                'surveyId' => isset($responseData['3JewWMWTsv']) ? $responseData['3JewWMWTsv'] : '',
                'userId' => isset($responseData['23WUAsIaOi']) ? $responseData['23WUAsIaOi'] : '',
                'companyId' => isset($responseData['23WUAsIaOi']) ? User::getCompanyId($responseData['23WUAsIaOi']) : '',
                'keyword' => $keywordId
            );
            Config::get('db')->insert('job',$jobData);

            $jobpostId = Config::get('db')->lastid();

            $jobURL = Config::get('base_url').'job/view/'.$jobpostId;


            \Codebird\Codebird::setConsumerKey("3kkH7zqxgOBJTB6GCO73vNjum", "IYTHCTOdCIlHns0yP3PoZJKhz3jLqNVfthHD7Y0uvbJsx57tob");
            $cb = \Codebird\Codebird::getInstance();
            $cb->setToken("26564418-lXp9wi6Uxkla425phZmE4W2Tp0pTevlR33xjjftge", "ZNXgDmVRxzTHwxi9HqzIwRaQ3n8xzT2TGqrXtimmlk0KG");

            $citystate = trim(str_replace(" ","",$jobData["city"].$jobData['state']));

            $params = array(
              'status' => "{$jobData['position']} position available in {$jobData['city']}, {$jobData['state']} {$jobURL} #Jobs"
            );
            $reply = $cb->statuses_update($params);
        }
        //// END HACK


        //$staticVars = Survey::getStaticVars($surveyId);
        $uploadField = (isset(Survey::$_configData[STATIC_VARS::SURVEYFILE])) ? Survey::$_configData[STATIC_VARS::SURVEYFILE] : null;
        if (isset($dataArray['mobileNum']) && strlen(Utility::parsePhone($dataArray['mobileNum'])) == 10) {
            //echo "importing mobileNum: ".$dataArray['mobileNum']."<br />";
            $priId = 0;
            //step 1: check if person exists
            $personId = Person::exists(Utility::parsePhone($dataArray['mobileNum']));

            if (!$personId) {
                //If not, create them
                $personData = array(
                    'mobileNum' => Utility::parsePhone($dataArray['mobileNum']),
                    'firstName' => isset($dataArray['firstName']) ? Config::get('db')->filter($dataArray['firstName']) : '',
                    'lastName' => isset($dataArray['lastName']) ? Config::get('db')->filter($dataArray['lastName']) : '',
                    'email' => isset($dataArray['email']) ? Config::get('db')->filter($dataArray['email']) : '',
                    'zipCode' => isset($dataArray['zipCode']) ? $dataArray['zipCode'] : '',
                    'currentSurvey' => $surveyId,
                    'optOut' => 0,
                    'status' => 2);
                $personId = Person::create($personData);
                //SmsManager::optOut(Utility::parsePhone($dataArray['mobileNum']));
                if (isset($dataArray['optin']) && is_array($dataArray['optin']) && $dataArray['optin'][0] == 1) {
                    Person::update($personId, array('optOut' => 1));
                }
//                if (isset($dataArray['optin']) && is_array($dataArray['optin']) && $dataArray['optin'][0] == 0) {
//                    Person::update($personId, array('optOut' => 0));
//                    SmsManager::optIn(Utility::parsePhone($dataArray['mobileNum']));
//                }
                if (!Person::isOptOut($dataArray['mobileNum'])) {
                    $smsoptions = array(
                        'nolog' => false,
                        'numbers' => array(array('surveyId'=>$surveyId,'mobileNum'=>$dataArray['mobileNum'])),
                        'message' => "Welcome to JobAlarm where Employers can text u about jobs. Remember that you can reply STOP to Optout at anytime.  Also, search 2M jobs at www.tweetedjobs.com"
                    );
                    $result = SmsManager::sendSMS($smsoptions);
                }
            }
            if ($personId > 0) {
                $data = $dataArray;
                $data['response'] = $responseData;
                $data['personId'] = $personId;
                $data['mobileNum'] = Utility::parsePhone($dataArray['mobileNum']);
                $responseId = self::existsPri($surveyId, Utility::parsePhone($dataArray['mobileNum']));
                if ($responseId) {
                    self::deleteSec($responseId);
                    self::deletePri($responseId);
                }
                $priId = self::createPri($surveyId, $data, $smsResponse);
                $data['responseId'] = (isset($responseData['_id']) && $responseData['_id'] > 0) ? $responseData['_id'] : $priId;
                $data['response']['_id'] = $data['responseId'];
                $priId = $data['responseId'];
                //$secId = self::createSec($surveyId,$data,$smsResponse);

                //SmsManager::optOut(Utility::parsePhone($dataArray['mobileNum']));

                if (isset($dataArray['optin']) && is_array($dataArray['optin']) && $dataArray['optin'][0] == 1) {
                    Person::update($personId, array('optOut' => 1));
                }
                //if (isset($dataArray['optin']) && is_array($dataArray['optin']) && $dataArray['optin'][0] == 0) {
                //    Person::update($personId, array('optOut' => 0));
                //    SmsManager::optIn(Utility::parsePhone($dataArray['mobileNum']));
                //}

                if (isset($responseData['_id']) && $responseData['_id'] > 0) {
                    self::updateSearch($surveyId, $responseData['_id']);
                } else {
                    self::updateSearch($surveyId, $priId);
                }
                self::updatePriId($priId, array('surveyResponseId' => $priId));
                self::updateSec($priId, $smsResponse);
            }
            if ($uploadField && $priId > 0 && isset($dataArray['upload']) && strlen($dataArray['upload']) > 0) {
                self::downloadFile($surveyId, $responseData['_id'], Utility::parsePhone($dataArray['mobileNum']), $uploadField, basename($dataArray['upload']),$jobpostId);
                //echo "PRI ID: {$priId}<br />";
                //var_dump($dataArray);
                //echo "<h3>";
                //echo Config::get('db')->filter(basename($dataArray['upload']));
                //echo "</h3>";
                //Response::updatePri($responseData['_id'],array('responseFile'=>Config::get('db')->filter(basename($dataArray['upload']))));
            }
            return $priId;
        } else {
            Log::add('Unable to add person due to missing or invalid Mobile Number : ResponseID:' . $responseData['_id'], LOG_TYPES::PEOPLE, LOG_SEVERITIES::WARNING);
            //@TODO: handle not being able to add person/response
        }
        return false;
    }

    public static function createPri($surveyId, $data, $smsResponse = false) {

        $responseId = (isset($data['response']['_id'])) ? max(0, $data['response']['_id']) : 0;
        $insertData = array(
            'surveyId' => $surveyId,
            'peopleId' => $data['personId'],
            'surveyResponseId' => $responseId,
            'mobileNum' => Utility::parsePhone($data['mobileNum']),
            'response' => Config::get('db')->filter(json_encode($data['response'])),
            'responseEdit' => '',
            'responseSMS' => '',
            'responseFile' => '',
            'uploadFile' => '',
            'searchData' => Config::get('db')->filter(json_encode($data['response'])),
            'updated' => substr(str_replace('T', ' ', $data['response']['_updated_at']), 0, 19),
            'created' => date('Y-m-d H:i:s'),
            'type' => 0,
            'viewed' => 0,
            'status' => 1
        );
        Config::get('db')->insert('response', $insertData);
        return Config::get('db')->lastid();
    }

    public static function createSec($surveyId, $data, $smsResponse = false) {
        //if (isset($data['response']['_id']) && self::existsSec($surveyId,$data['response']['_id'])) {
        //    self::deleteSec($responseId);
        //}

        if (!($surveyId > 0))
            return false;
        Survey::load($surveyId);
        $surveyConfigData = Survey::getConfigData($surveyId);
        $mobileNumStatic = null;
        if (isset($surveyConfigData[STATIC_VARS::MOBILENUM])) {
            $mobileNumStatic = $surveyConfigData[STATIC_VARS::MOBILENUM];
        }
        $questions = Survey::$_questions;
        $insertData = Survey::getQuestionInsertFields($surveyId);
        //modify array
        if (isset($data['response']) && is_array($data['response'])) {
            if (!$smsResponse) {
                $insertData['responseId'] = $data['response']['_id'];
            } else {
                $insertData['responseId'] = $data['responseId'];
                if (isset($data['responseSMS']) && is_array($data['responseSMS'])) {
                    $data['response'] = array_merge($data['response'], $data['responseSMS']);
                }
                $data['response']['_id'] = $data['responseId'];
                $data['response']['_updated_at'] = date('Y-m-d H:i:s');
            }
            $insertData['TMPTBLviewed'] = 0;
            foreach ($data['response'] as $k => $v) {

                if (isset($questions[$k])) {
                    if ($k == $mobileNumStatic) {
                        $v = Utility::parsePhone($v);
                    }
                    if (!$smsResponse) {
                        $questions[$k]->setAnswer($v);
                        $questions[$k]->setAnswerText($v);
                    }
                    if (isset($insertData['TMPTBL' . $k])) {
                        $answer = $questions[$k]->getAnswerValue();
                        if ($questions[$k]->getType() == 'datetime') {
                            $answer = substr(str_replace('T', ' ', $answer), 0, 19);
                        }
                        if (!$smsResponse) {
                            $insertData['TMPTBL' . $k] = Config::get('db')->filter($answer);
                            $answerText = $questions[$k]->getAnswerText();
                            $insertData['TMPTBLDisplay' . $k] = Config::get('db')->filter($answerText);
                        } else {
                            $insertData['TMPTBLDisplay' . $k] = $v;
                        }
                    }
                }
            }
        }
        //var_dump($insertData);

        Config::get('db')->insert('survey' . $surveyId, $insertData);
        return Config::get('db')->lastid();
    }

    public static function existsPri($surveyId, $mobileNum) {
        $query = "SELECT surveyResponseId FROM response WHERE surveyId={$surveyId} AND mobileNum='{$mobileNum}'";
        $dbData = Config::get('db')->get_results($query);
        if ($dbData && count($dbData) > 0) {
            return $dbData[0]['surveyResponseId'];
        }
        return false;
    }

    public static function existsSec($surveyId, $responseId) {
        $query = "SELECT id FROM survey{$surveyId} WHERE responseId={$responseId}";
        $dbData = Config::get('db')->get_results($query);
        if ($dbData && count($dbData) > 0) {
            return $dbData[0]['id'];
        }
        return false;
    }

    public static function addFieldToSec($surveyId, $id, $fieldType) {
        if (!($surveyId > 0))
            return false;
        switch ($fieldType) {
            case 'boolean':
            case 'single-meta':
            case 'multi':
            case 'string':
            case 'hidden':
            case 'file':
            default:
                $type = "VARCHAR(50)";
                break;
            case 'single':
                $type = "INT(11)";
                break;
            case 'datetime':
                $type = "DATETIME";
                break;
        }

        $query = "ALTER TABLE survey{$surveyId}
                    ADD COLUMN `TMPTBL{$id}` {$type},
                    ADD COLUMN `TMPTBLDisplay{$id}` TEXT";
        Config::get('db')->query($query);
    }

    public static function inQueue($surveyId, $responseId) {
        $query = "SELECT responseId FROM responsequeue where surveyId={$surveyId} && responseId={$responseId} && processed=0";
        $dbData = Config::get('db')->get_results($query);
        if ($dbData && count($dbData) > 0) {
            return $dbData[0]['responseId'];
        }
        return false;
    }

    public static function isProcessed($surveyId, $responseId, $responseDate = '0000-00-00 00:00:00') {
        $query = "SELECT responseId FROM responsequeue where surveyId={$surveyId} && responseId={$responseId} && processed=1 && importTime<'{$responseDate}'";
        $dbData = Config::get('db')->get_results($query);
        if ($dbData && count($dbData) > 0) {
            return $dbData[0]['responseId'];
        }
        return false;
    }

    public static function delFromQueue($responseId) {
        $where = array('responseId' => $responseId);
        Config::get('db')->delete('responsequeue', $where, 1);
    }

    public static function getLastUpdateSurvey($surveyId) {
        $query = "
          SELECT
            DATE(MAX(importTime)-INTERVAL 1 DAY) as last_update
          FROM responsequeue
          WHERE
            surveyId={$surveyId}
        ";
        $dbData = Config::get('db')->get_results($query);
        if (count($dbData) > 0 && $dbData[0]['last_update'] != NULL) {
            $datetime = $dbData[0]['last_update'];
            $outtime = substr(str_replace(' ', 'T', $datetime), 0, 19);
            return $outtime;
        } else
            return '2000-01-01';
    }

    public static function importNewResponses($surveyId, $options = NULL) {
        set_time_limit(0);
        $responseData = self::getLive($surveyId, $options);

        $importCount = 0;
        if (isset($responseData['responses']) && count($responseData['responses']) > 0) {
            foreach ($responseData['responses'] as $response) {
                if (!Response::existsSec($surveyId, $response['_id'])) {
                    if (self::inQueue($surveyId, $response['_id'])) {
                        self::delFromQueue($response['_id']);
                    }
                    if (!self::isProcessed($surveyId, $response['_id'], substr(str_replace('T', ' ', $response['_updated_at']), 0, 19))) {
                        $data = array(
                            'responseId' => $response['_id'],
                            'surveyId' => $surveyId,
                            'responseData' => Config::get('db')->filter(json_encode($response)),
                            'processed' => 0
                        );
                        Config::get('db')->insert('responsequeue', $data);
                        $importCount++;
                    } else {
                        Log::add('Already Processed : ' . $response['_id'], LOG_TYPES::SURVEY, LOG_SEVERITIES::COMMON);
                    }
                }
            }
        }
        return $importCount;
    }

    public static function importNewResponsesTest($surveyId, $options = NULL) {
        set_time_limit(0);
        $time_start = microtime(true);
        echo "<br />";
        var_dump($options);
        echo "<br />";
        $responseData = self::getLive($surveyId, $options);

        echo "importNewResponses: ".(microtime(true)-$time_start)." ".__LINE__."<br />";

        $importCount = 0;
        if (isset($responseData['responses']) && count($responseData['responses']) > 0) {
            foreach ($responseData['responses'] as $response) {
                if (!Response::existsSec($surveyId, $response['_id'])) {
                    if (self::inQueue($surveyId, $response['_id'])) {
                        self::delFromQueue($response['_id']);
                    }
                    if (!self::isProcessed($surveyId, $response['_id'], substr(str_replace('T', ' ', $response['_updated_at']), 0, 19))) {
                        $data = array(
                            'responseId' => $response['_id'],
                            'surveyId' => $surveyId,
                            'responseData' => Config::get('db')->filter(json_encode($response)),
                            'processed' => 0
                        );
                        Config::get('db')->insert('responsequeue', $data);
                        $importCount++;
                    } else {
                        Log::add('Already Processed : ' . $response['_id'], LOG_TYPES::SURVEY, LOG_SEVERITIES::COMMON);
                    }
                }
            }
        }
        echo "importNewResponses: ".(microtime(true)-$time_start)." ".__LINE__."<br />";
        return $importCount;
    }


    public static function getLive($surveyId, $options = NULL) {
        set_time_limit(0);
        $filter = "";
        $limit = 0;
        $offset = 0;
        if ($options && is_array($options))
            foreach ($options as $key => $value)
                $$key = $value;
        $jsonData = Survey::_getSurveyWebData(FS_URL . "{$surveyId}/responses/?limit={$limit}&offset={$offset}{$filter}");
        $returnarray = false;
        if (strlen($jsonData) > 0) {
            try {
                $returnObj = json_decode($jsonData, true);
                return $returnObj;
            }
            catch (Exception $e) {
                Log::add('Failed to get responses for survey: ' . $surveyId, LOG_TYPES::SURVEY, LOG_SEVERITIES::CRITICAL);
            }
        }
        return false;
    }

    public static function getStaticFieldsFromData($surveyId, $responseData) {
        $surveyConfigData = Survey::getSurveyConfig($surveyId);
        $outArray = array();
        if (is_array($responseData)) {
            if (isset($responseData[$surveyConfigData[STATIC_VARS::MOBILENUM]])) {
                $outArray['mobileNum'] = $responseData[$surveyConfigData[STATIC_VARS::MOBILENUM]];
            }
            if (isset($responseData[$surveyConfigData[STATIC_VARS::FIRSTNAME]])) {
                $outArray['firstName'] = $responseData[$surveyConfigData[STATIC_VARS::FIRSTNAME]];
            }
            if (isset($responseData[$surveyConfigData[STATIC_VARS::LASTNAME]])) {
                $outArray['lastName'] = $responseData[$surveyConfigData[STATIC_VARS::LASTNAME]];
            }
            if (isset($responseData[$surveyConfigData[STATIC_VARS::EMAIL]])) {
                $outArray['email'] = $responseData[$surveyConfigData[STATIC_VARS::EMAIL]];
            }
            if (isset($responseData[$surveyConfigData[STATIC_VARS::ZIPCODE]])) {
                $outArray['zipCode'] = $responseData[$surveyConfigData[STATIC_VARS::ZIPCODE]];
            }
            if (isset($responseData[$surveyConfigData[STATIC_VARS::POSITION]])) {
                $outArray['position'] = $responseData[$surveyConfigData[STATIC_VARS::POSITION]];
            }
            if (isset($responseData[$surveyConfigData[STATIC_VARS::LOCATION]])) {
                $outArray['location'] = $responseData[$surveyConfigData[STATIC_VARS::LOCATION]];
            }
            if (isset($responseData[$surveyConfigData[STATIC_VARS::SURVEYFILE]])) {
                $outArray['upload'] = $responseData[$surveyConfigData[STATIC_VARS::SURVEYFILE]];
            }
            if (isset($surveyConfigData[STATIC_VARS::REFERRAL]) && isset($responseData[$surveyConfigData[STATIC_VARS::REFERRAL]])) {
                $outArray['referral'] = $responseData[$surveyConfigData[STATIC_VARS::REFERRAL]];
            }
            if (isset($surveyConfigData[STATIC_VARS::OPTIN]) && isset($responseData[$surveyConfigData[STATIC_VARS::OPTIN]])) {
                $outArray['optin'] = $responseData[$surveyConfigData[STATIC_VARS::OPTIN]];
            }
        }
        return $outArray;
    }

    public static function generateBlank($surveyId, $data) {
        $blankTemplate = Survey::getResponseTemplate($surveyId);
        if ($blankTemplate) {
            if (isset($blankTemplate['_updated_at'])) {
                if (isset($data['_updated_at'])) {
                    $blankTemplate['_updated_at'] = $data['_updated_at'];
                }
                else {
                    $blankTemplate['_updated_at'] = str_replace('T', ' ', date('Y-m-d H:i:s'));
                }
            }
            if (isset($blankTemplate['_created_at'])) {
                if (isset($data['_created_at'])) {
                    $blankTemplate['_created_at'] = $data['_created_at'];
                }
                else {
                    $blankTemplate['_created_at'] = str_replace('T', ' ', date('Y-m-d H:i:s'));
                }
            }
            $surveyConfigData = Survey::getConfigData();

            if (is_array($data)) {
                if (isset($data['mobileNum'])) {
                    if (isset($surveyConfigData[STATIC_VARS::MOBILENUM]) && isset($blankTemplate[$surveyConfigData[STATIC_VARS::MOBILENUM]])) {
                        $blankTemplate[$surveyConfigData[STATIC_VARS::MOBILENUM]] = $data['mobileNum'];
                    }
                }
                if (isset($data['firstName'])) {
                    if (isset($surveyConfigData[STATIC_VARS::FIRSTNAME]) && isset($blankTemplate[$surveyConfigData[STATIC_VARS::FIRSTNAME]])) {
                        $blankTemplate[$surveyConfigData[STATIC_VARS::FIRSTNAME]] = $data['firstName'];
                    }
                }
                if (isset($data['lastName'])) {
                    if (isset($surveyConfigData[STATIC_VARS::LASTNAME]) && isset($blankTemplate[$surveyConfigData[STATIC_VARS::LASTNAME]])) {
                        $blankTemplate[$surveyConfigData[STATIC_VARS::LASTNAME]] = $data['lastName'];
                    }
                }
                if (isset($data['email'])) {
                    if (isset($surveyConfigData[STATIC_VARS::EMAIL]) && isset($blankTemplate[$surveyConfigData[STATIC_VARS::EMAIL]])) {
                        $blankTemplate[$surveyConfigData[STATIC_VARS::EMAIL]] = $data['email'];
                    }
                }
                if (isset($data['zipCode'])) {
                    if (isset($surveyConfigData[STATIC_VARS::ZIPCODE]) && isset($blankTemplate[$surveyConfigData[STATIC_VARS::ZIPCODE]])) {
                        $blankTemplate[$surveyConfigData[STATIC_VARS::ZIPCODE]] = $data['zipCode'];
                    }
                }
                if (isset($data['position'])) {
                    if (isset($surveyConfigData[STATIC_VARS::POSITION]) && isset($blankTemplate[$surveyConfigData[STATIC_VARS::POSITION]])) {
                        $blankTemplate[$surveyConfigData[STATIC_VARS::POSITION]] = $data['position'];
                    }
                }
                if (isset($data['location'])) {
                    if (isset($surveyConfigData[STATIC_VARS::LOCATION]) && isset($blankTemplate[$surveyConfigData[STATIC_VARS::LOCATION]])) {
                        $blankTemplate[$surveyConfigData[STATIC_VARS::LOCATION]] = $data['location'];
                    }
                }
                if (isset($data['referral'])) {
                    if (isset($surveyConfigData[STATIC_VARS::REFERRAL]) && isset($blankTemplate[$surveyConfigData[STATIC_VARS::REFERRAL]])) {
                        $blankTemplate[$surveyConfigData[STATIC_VARS::REFERRAL]] = $data['referral'];
                    }
                }
                if (isset($data['optin'])) {
                    if (isset($surveyConfigData[STATIC_VARS::OPTIN]) && isset($blankTemplate[$surveyConfigData[STATIC_VARS::OPTIN]])) {
                        $blankTemplate[$surveyConfigData[STATIC_VARS::OPTIN]] = $data['optin'];
                    }
                }
            }
            return $blankTemplate;
        }
        return false;
    }

    public static function updatePriID($id, $data) {
        $where = array('id' => $id);
        Config::get('db')->update('response', $data, $where, 1);
    }

    public static function updatePri($responseId, $data) {
        $where = array('surveyResponseId' => $responseId);
        Config::get('db')->update('response', $data, $where, 1);
    }

    public static function updateSec($responseId, $smsresponse = false) {

        $surveyId = Response::getSurveyId($responseId);
        $answerList = Response::getResponseAnswers($surveyId, $responseId);
        $answerList['response'] = $answerList;
        $answerList['responseId'] = $responseId;
        $answerList['response']['responseId'] = $responseId;
        $answerList['response']['_id'] = $responseId;
        $updatSecLog = "
        SURVEYID: {$surveyId}\r\n
        RESPONSEID: {$responseId}\r\n
        SMSRESPONSE: " . print_r($smsresponse, true) . "\r\n
        ANSWERLIST: " . print_r($answerList, true) . "\r\n
        ";
        @file_put_contents("log/updateSec.txt", $updatSecLog);
        self::deleteSec($responseId);
        self::createSec($surveyId, $answerList, $smsresponse);
        self::updateSearch($surveyId,$responseId);
        //    $where = array('responseId',$responseId);
        //    $surveyId = self::getSurveyId($responseId);
        //    Config::get('db')->update('survey'.$surveyId,$data,$where,1);
    }

    public static function updateSearch($surveyId, $responseId) {
        $response = '';
        $responseEdit = '';
        $responseSMS = '';

        $staticVars = Survey::getStaticVars($surveyId);
        $keyword = $staticVars['tpl_keywords'];

        $query = "select response,responseEdit,responseSMS from response where surveyResponseId={$responseId}";
        //echo $query;
        $dbData = Config::get('db')->get_results($query);
        if ($dbData && count($dbData) > 0) {
            $response = $dbData[0]['response'];
            $responseEdit = $dbData[0]['responseEdit'];
            $responseSMS = $dbData[0]['responseSMS'];
        }

        $subQuery = "SELECT * FROM survey{$surveyId} WHERE responseId={$responseId}";
        //echo $subQuery;

        $responseData = Config::get('db')->get_results($subQuery);
        $dataArray = array();
        if (count($responseData) > 0) {
            foreach ($responseData[0] as $k => $v) {
                if (strstr($k, 'Display') && strlen($responseData[0][$k]) > 0) {
                    $dataArray[] = $responseData[0][$k];
                }
            }
            $dataString = implode(' , ', $dataArray);
            $data = array('searchData' => Config::get('db')->filter(trim($keyword.' '.$dataString.' '.$response.' '.$responseEdit.' '.$responseSMS)));

            $where = array('surveyResponseId' => $responseId);

            Config::get('db')->update('response', $data, $where);
        }
    }

    public static function getSurveyId($responseId) {
        $query = "select surveyId from response where surveyResponseId={$responseId}";
        $dbData = Config::get('db')->get_results($query);
        if ($dbData && count($dbData) > 0) {
            return $dbData[0]['surveyId'];
        }
        return null;
    }

    public static function getPersonId($responseId) {
        $query = "select peopleId from response where surveyResponseId={$responseId}";
        $dbData = Config::get('db')->get_results($query);
        if ($dbData && count($dbData) > 0) {
            return $dbData[0]['peopleId'];
        }
        return null;
    }

    public static function getMobileNum($responseId) {
        $query = "select mobileNum from response where surveyResponseId={$responseId}";
        $dbData = Config::get('db')->get_results($query);
        if ($dbData && count($dbData) > 0) {
            return $dbData[0]['mobileNum'];
        }
        return null;
    }

    public static function deletePri($responseId) {
        $where = array('surveyResponseId' => $responseId);
        Config::get('db')->delete('response', $where, 1);
    }

    public static function deleteSec($responseId) {
        $surveyId = self::getSurveyId($responseId);
        //echo "<h1> deleting : $responseId : $surveyId</h1>";
        $where = array('responseId' => $responseId);
        Config::get('db')->delete('survey' . $surveyId, $where, 1);
    }

    public static function queueMarkProcessed($responseId) {
        $data = array('processed' => 1);
        $where = array('responseId' => $responseId);
        Config::get('db')->update('responsequeue', $data, $where, 1);
    }

    public static function queueMarkFailed($responseId) {
        $data = array('processed' => 2);
        $where = array('responseId' => $responseId);
        Config::get('db')->update('responsequeue', $data, $where, 1);
    }

	public static function getGlobal() {
        $limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 100;
        $start = isset($_REQUEST['offset']) ? $_REQUEST['offset'] : 0;
        $sort = "updated DESC";

        //$accessArray = User::getSurveyAccessArray(Config::get('loggedIn'));
        $accessWhere = ' AND response.surveyId = 0 ';
        $search_filters = '';

        if (isset($_REQUEST['sort']) && is_array($_REQUEST['sort']) && count($_REQUEST['sort']) > 0) {
            $sort = $_REQUEST['sort'][0]['field'] . ' ' . $_REQUEST['sort'][0]['direction'];
        }

        $userId = Config::get('loggedIn');

        $accessArray = array();
        $companyId = User::getData('companyId');
        $adminSurveyList = Company::getAllSurveyAdminList($companyId);
        //$adminSurveyList = User::getDefaultAdminSurveyList(User::$_loggedIn);
        foreach($adminSurveyList as $survey) {
            $accessArray[] = $survey['surveyId'];
        }
        //var_dump($accessArray);
        $search_filters = " AND ((responsestage.stageId <> 999) OR (responsestage.stageId IS NULL)) ";
        if (isset($_REQUEST['search']))
            $search_filters = self::get_query_search_filters($_REQUEST['search'],$accessArray);

        if (count($accessArray) > 0) {
            $accessList = (count($accessArray) > 0) ? implode(",", $accessArray) : "";
            $accessWhere = " AND response.surveyId IN (" . $accessList . ") ";
        }

        $query = "
            SELECT SQL_CALC_FOUND_ROWS
            response.surveyResponseId,
            response.surveyId,
            response.updated,
            response.peopleId,
            response.viewed,
            people.firstName as firstName,
            people.lastName as lastName,
            people.email as email,
            people.zipCode as zipCode,
            people.mobileNum as mobileNum,
            people.optOut as optOut,
            responsestage.stageId as stageid,
            responsestage.stageId as stage,
            responseevent.eventId as eventid,
            responseevent.eventId as event
            FROM response
            LEFT join people
            ON people.id=response.peopleId
            LEFT JOIN responsestage
            ON responsestage.responseId = response.surveyResponseId
            AND responsestage.userId = {$userId}
            AND responsestage.stageDate <> '0000-00-00 00:00:00'
            LEFT JOIN responseevent
            ON responseevent.responseId = response.surveyResponseId
            AND responseevent.userId = {$userId}
            AND responseevent.eventDate <> '0000-00-00 00:00:00'
            WHERE response.active > 0
            {$search_filters}
            {$accessWhere}
            ORDER BY $sort
            LIMIT {$start},{$limit}
        ";
        // die($query);
        //    var_dump($query);

        $dbData = Config::get('db')->get_results($query);
        $foundrowsquery = "SELECT FOUND_ROWS() as count";
        $countData = Config::get('db')->get_results($foundrowsquery);

        $dataArray = array();
        $stages = Stage::getLookup();
        $events = EventManager::getLookup();
        //$stages = SurveyAdmin::getSurveyStageList();
        //$events = SurveyAdmin::getSurveyEventList();

        foreach ($dbData as $response) {
            $surveydata = Config::get('db')->get_results("SELECT name FROM survey WHERE surveyId={$response['surveyId']}");

            $read = ($response['viewed'] == 1) ? '' : 'font-weight:bold';
            $style = (SmsManager::isOptOut($response['mobileNum']) || Person::isOptOut($response['mobileNum'])) ? 'color:#faa' : $read;
            $style = (Person::onHold($response['mobileNum'])) ? 'color:#FFA500' : $style;
            $referral = '';
            //Survey::load($response['surveyId']);
            $staticVars = Survey::getStaticVars($response['surveyId']);
            $positionVar = '';
            if (isset($staticVars['tpl_position'])) {
                $positionVar = $staticVars['tpl_position'];
            }
            //$responseData = Response::readPri($response['surveyId'], $response['surveyResponseId']);
            //$responseObj = json_decode($responseData['response'], true);

            $responseAnswers = Response::getResponseAnswers($response['surveyId'],$response['surveyResponseId']);

            $staticData = Response::getStaticFieldsFromData($response['surveyId'], $responseAnswers);

            //var_dump($positionVar);
            $responseSec = Response::readSec($response['surveyId'],$response['surveyResponseId']);

            //var_dump($responseSec);

            ////var_dump($staticData);
            if (isset($staticData['referral']) && strlen($staticData['referral']) > 0) {
                $referral = $staticData['referral'];
                $query = "select fullName from emp_ref where mobileNum='{$referral}'";
                $dbData = Config::get('db')->get_results($query);
                $referral = (count($dbData) > 0) ? $dbData[0]['fullName'] : '';

            }

            $position = '';
            if (isset($responseSec[0]['TMPTBLDisplay'.$positionVar])) {
                $position = $responseSec[0]['TMPTBLDisplay'.$positionVar];
            }

            //if (isset($staticData['position']) && is_array($staticData['position'])) {
            //    if (isset($staticVars['tpl_position']) && isset($staticData['position'][0]) && isset(Survey::$_questions[$staticVars['tpl_position']]) && isset(Survey::$_questions[$staticVars['tpl_position']]->_choices) && isset( Survey::$_questions[$staticVars['tpl_position']]->_choices[$staticData['position'][0]])) {
            //        $position = Survey::$_questions[$staticVars['tpl_position']]->_choices[$staticData['position'][0]]['label'];
            //    }
            //} else {
            //    if (isset($staticVars['tpl_position']) && isset($staticData['position']) && isset(Survey::$_questions[$staticVars['tpl_position']]) && isset(Survey::$_questions[$staticVars['tpl_position']]->_choices) && isset( Survey::$_questions[$staticVars['tpl_position']]->_choices[$staticData['position']])) {
            //        $position = Survey::$_questions[$staticVars['tpl_position']]->_choices[$staticData['position']]['label'];
            //    }
            //}
            $statusImage = '';

            $isOptOut = 0;
            if (SmsManager::isOptOut($response['mobileNum'])) {
                $isOptOut = 1;
                $statusImage = '<img src="img/stop.png" alt="stop" />';
            } else if (Person::isOptOut($response['mobileNum'])) {
                $isOptOut = 1;
                $statusImage = '<img src="img/optout.png" alt="stop" />';
            } else if (Person::onHold($response['mobileNum'])) {
                $isOptOut = 1;
                $statusImage = '<img src="img/hold.png" alt="stop" />';
            }

            $dataArray[] = array(
                'recid' => $response['surveyResponseId'],
                'status' => $statusImage,
                'actions' => '<button onclick="">Manage</button>',
                'surveyname' => (isset($surveydata[0])) ? $surveydata[0]['name'] : '',
                'firstname' => stripslashes($response['firstName']),
                'lastname' => stripslashes($response['lastName']),
                'position' => $position,
                'mobilenum' => $response['mobileNum'],
                'email' => $response['email'],
                'zipcode' => $response['zipCode'],
                'stage' => isset($stages[$response['stageid']]) ? $stages[$response['stageid']] : '',
                'event' => isset($events[$response['eventid']]) ? $events[$response['eventid']] : '',
                'updated' => $response['updated'],
                'referral' => $referral,
                'style' => $style,
                'optOut' => $isOptOut
            );
        }
        $outArray = array(
            'status' => 'success',
            'total' => $countData[0]['count'],
            'records' => $dataArray
        );
        echo json_encode($outArray);
    }


    public static function getGlobalTest() {

        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $starttime = $mtime;


        $limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 100;
        $start = isset($_REQUEST['offset']) ? $_REQUEST['offset'] : 0;
        $sort = "updated DESC";

        //$accessArray = User::getSurveyAccessArray(Config::get('loggedIn'));
        $accessWhere = ' AND response.surveyId = 0 ';
        $search_filters = '';

        if (isset($_REQUEST['sort']) && is_array($_REQUEST['sort']) && count($_REQUEST['sort']) > 0) {
            $sort = $_REQUEST['sort'][0]['field'] . ' ' . $_REQUEST['sort'][0]['direction'];
        }

        $userId = Config::get('loggedIn');

        $accessArray = array();
        $adminSurveyList = User::getDefaultAdminSurveyList(User::$_loggedIn);
        foreach($adminSurveyList as $survey) {
            $accessArray[] = $survey['surveyId'];
        }
        //var_dump($accessArray);
        if (isset($_REQUEST['search']))
            $search_filters = self::get_query_search_filters($_REQUEST['search'],$accessArray);

        if (count($accessArray) > 0) {
            $accessList = (count($accessArray) > 0) ? implode(",", $accessArray) : "";
            $accessWhere = " AND response.surveyId IN (" . $accessList . ") ";
        }


        $query = "
            SELECT SQL_CALC_FOUND_ROWS
            response.surveyResponseId,
            response.surveyId,
            response.updated,
            response.peopleId,
            response.viewed,
            people.firstName as firstName,
            people.lastName as lastName,
            people.email as email,
            people.zipCode as zipCode,
            people.mobileNum as mobileNum,
            people.optOut as optOut,
            responsestage.stageId as stageid,
            responsestage.stageId as stage,
            responseevent.eventId as eventid,
            responseevent.eventId as event
            FROM response
            LEFT join people
            ON people.id=response.peopleId
            LEFT JOIN responsestage
            ON responsestage.responseId = response.surveyResponseId
            AND responsestage.userId = {$userId}
            AND responsestage.stageDate <> '0000-00-00 00:00:00'
            LEFT JOIN responseevent
            ON responseevent.responseId = response.surveyResponseId
            AND responseevent.userId = {$userId}
            AND responseevent.eventDate <> '0000-00-00 00:00:00'
            WHERE response.active > 0
            {$search_filters}
            {$accessWhere}
            ORDER BY $sort
            LIMIT {$start},{$limit}
        ";
        // die($query);
        //    var_dump($query);

        $dbData = Config::get('db')->get_results($query);
        $foundrowsquery = "SELECT FOUND_ROWS() as count";
        $countData = Config::get('db')->get_results($foundrowsquery);

        $dataArray = array();
        $stages = Stage::getLookup();
        $events = EventManager::getLookup();
        //$stages = SurveyAdmin::getSurveyStageList();
        //$events = SurveyAdmin::getSurveyEventList();
        foreach ($dbData as $response) {
            $surveydata = Config::get('db')->get_results("SELECT name FROM survey WHERE surveyId={$response['surveyId']}");

            $read = ($response['viewed'] == 1) ? '' : 'font-weight:bold';
            $style = '';
            $style = (SmsManager::isOptOut($response['mobileNum']) || Person::isOptOut($response['mobileNum'])) ? 'color:#faa' : $read;
            $style = (Person::onHold($response['mobileNum'])) ? 'color:#FFA500' : $style;
            $referral = '';
            //Survey::load($response['surveyId']);
            $staticVars = Survey::getStaticVars($response['surveyId']);
            $positionVar = '';
            if (isset($staticVars['tpl_position'])) {
                $positionVar = $staticVars['tpl_position'];
            }

            $responseAnswers = Response::getResponseAnswers($response['surveyId'],$response['surveyResponseId']);

            $staticData = Response::getStaticFieldsFromData($response['surveyId'], $responseAnswers);

            //var_dump($positionVar);
            $responseSec = Response::readSec($response['surveyId'],$response['surveyResponseId']);

            //var_dump($responseSec);

            ////var_dump($staticData);
            if (isset($staticData['referral']) && strlen($staticData['referral']) > 0) {
                $referral = $staticData['referral'];
                $query = "select fullName from emp_ref where mobileNum='{$referral}'";
                $dbData = Config::get('db')->get_results($query);
                $referral = (count($dbData) > 0) ? $dbData[0]['fullName'] : '';

            }

            $position = '';
            if (isset($responseSec[0]['TMPTBLDisplay'.$positionVar])) {
                $position = $responseSec[0]['TMPTBLDisplay'.$positionVar];
            }

            //if (isset($staticData['position']) && is_array($staticData['position'])) {
            //    if (isset($staticVars['tpl_position']) && isset($staticData['position'][0]) && isset(Survey::$_questions[$staticVars['tpl_position']]) && isset(Survey::$_questions[$staticVars['tpl_position']]->_choices) && isset( Survey::$_questions[$staticVars['tpl_position']]->_choices[$staticData['position'][0]])) {
            //        $position = Survey::$_questions[$staticVars['tpl_position']]->_choices[$staticData['position'][0]]['label'];
            //    }
            //} else {
            //    if (isset($staticVars['tpl_position']) && isset($staticData['position']) && isset(Survey::$_questions[$staticVars['tpl_position']]) && isset(Survey::$_questions[$staticVars['tpl_position']]->_choices) && isset( Survey::$_questions[$staticVars['tpl_position']]->_choices[$staticData['position']])) {
            //        $position = Survey::$_questions[$staticVars['tpl_position']]->_choices[$staticData['position']]['label'];
            //    }
            //}
            $statusImage = '';

            $isOptOut = 0;
            if (SmsManager::isOptOut($response['mobileNum'])) {
                $isOptOut = 1;
                $statusImage = '<img src="img/stop.png" alt="stop" />';
            } else if (Person::isOptOut($response['mobileNum'])) {
                $isOptOut = 1;
                $statusImage = '<img src="img/optout.png" alt="stop" />';
            } else if (Person::onHold($response['mobileNum'])) {
                $isOptOut = 1;
                $statusImage = '<img src="img/hold.png" alt="stop" />';
            }

            $dataArray[] = array(
                'recid' => $response['surveyResponseId'],
                'status' => $statusImage,
                'actions' => '<button onclick="">Manage</button>',
                'surveyname' => (isset($surveydata[0])) ? $surveydata[0]['name'] : '',
                'firstname' => stripslashes($response['firstName']),
                'lastname' => stripslashes($response['lastName']),
                'position' => $position,
                'mobilenum' => $response['mobileNum'],
                'email' => $response['email'],
                'zipcode' => $response['zipCode'],
                'stage' => isset($stages[$response['stageid']]) ? $stages[$response['stageid']] : '',
                'event' => isset($events[$response['eventid']]) ? $events[$response['eventid']] : '',
                'updated' => $response['updated'],
                'referral' => $referral,
                'style' => $style,
                'optOut' => $isOptOut
            );
            $mtime = microtime();
            $mtime = explode(" ",$mtime);
            $mtime = $mtime[1] + $mtime[0];
            $endtime = $mtime;
            $totaltime = ($endtime - $starttime);
            echo("<br />duration: ".$totaltime." seconds<br />");
        }

        $outArray = array(
            'status' => 'success',
            'total' => $countData[0]['count'],
            'records' => $dataArray
        );
        echo json_encode($outArray);
        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $endtime = $mtime;
        $totaltime = ($endtime - $starttime);
        //die("duration: ".$totaltime." seconds");
    }
    public static function getAnswerList($surveyId, $responseId) {
        Survey::load($surveyId);

        $questions = array_keys(Survey::$_questions);
        $response = Response::readPri($surveyId, $responseId);
        $outAnswers = Array();
        if ($response) {
            $answers = json_decode(stripslashes($response['response']), true);
            $answerEdit = json_decode(stripslashes($response['responseEdit']), true);
            $answerSMS = json_decode(stripslashes($response['responseSMS']), true);
            if (count($answers) > 0) {
                $outAnswers = $answers;
                foreach ($questions as $question) {
                    //if both answers exist
                    if (isset($answerEdit[$question])) {
                        $outAnswers[$question] = $answerEdit[$question];
                    }
                }
            } else {
                $outAnswers = $answerEdit;
            }
            if (count($answerSMS) > 0) {
                if (is_array($answerEdit)) {
                    $outAnswers['responseSMS'] = array_merge($answerSMS, $answerEdit);
                } else {
                    $outAnswers['responseSMS'] = $answerSMS;
                }
            }
        }
        $outAnswers['responseFile'] = $response['responseFile'];
        $outAnswers['uploadFile'] = $response['uploadFile'];
        $outAnswers['mobileNum'] = $response['mobileNum'];
        return $outAnswers;
    }

    //get all local answers from survey
    public static function getAnswers($surveyId, $options = NULL) {
        //initialize needed master variables
        $userId = Config::get('loggedIn');

        $page = 1;
        $limit = 100;

        if ($options)
            foreach ($options as $key => $value)
                if ($key != 'filters')
                    $$key = $value;

        $getReport = (isset($options['report']));


        $stageLookup = Stage::getLookup($surveyId);
        $eventLookup = EventManager::getLookup($surveyId);

        //$orderBy = " order by id ASC";
        //$orderBy = " order by STR_TO_DATE(TMPTBLDisplay_updated_at,'%Y-%m-%d %h:%i:%s') DESC";
        $orderBy = " ORDER BY TMPTBLDisplay_updated_at DESC ";

        if (isset($_REQUEST['sort']) && isset($_REQUEST['sort'][0]) && strlen($_REQUEST['sort'][0]['field']) > 0) {

            $sortfield = "{$_REQUEST['sort'][0]['field']}";

            if ($sortfield == 'Displaystagename') {
                $sortfield = 'stage.name';
            } else if ($sortfield == 'Displayeventname') {
                $sortfield = 'event.name';
            }

            $sortprefix = ($sortfield != 'stage.name' && $sortfield != 'event.name') ? 'TMPTBL' : '';
            $sortdir = "{$_REQUEST['sort'][0]['direction']}";
            $orderBy = " order by {$sortprefix}{$sortfield} {$sortdir} ";
        }

        $startAt = (isset($offset)) ? $offset : 0;

        //        Response::importNewResponses($surveyId);
        //$command = PHP_EXECUTABLE." {$_SERVER['DOCUMENT_ROOT']}/process.php {$surveyId}";
        ////echo $command."<br />";
        //Utility::execInBackground($command);

        if ($surveyId == 0) {
            die('<br />FAILURE in getAnswers<br />');
        }

        Survey::load($surveyId);

        foreach (Survey::$_questions as $question) {
            $typeLookupTable['TMPTBL' . $question->_id] = $question->_type;
        }

        $staticFields = Survey::$_configData;

        //    var_dump($staticFields);
        $buf_keyword = '';
        $where_add = '';
        $masterFilters = Array();
        $optoutfilter = '';
        $searchRemoved = false;

        //get our dynamic filter information
        if (is_array($options) && isset($options['filters']) && is_array($options['filters'])) {
            foreach ($options['filters'] as $filter => $value) {
                //var_dump($filter);
                //var_dump($value);
                if (substr($filter,0,13) == 'TMPTBLDisplay') {
                    $filter = 'filter_'.substr($filter,13);

                }
                if (substr($filter, 0, 7) == 'filter_') {
                    if (substr($filter, 7, 14) == 'mobileNum') {
                        $masterFilters[] = '(TMPTBL' . $staticFields[STATIC_VARS::MOBILENUM] . " LIKE '" . $value . "%')";
                    } else
                        if (substr($filter, 7, 14) == 'zipdist_static') {
                            //ignore
                        } else
                            if (substr($filter, 7, 14) == 'zipcode_static') {
                                if (strlen($staticFields[STATIC_VARS::ZIPCODE]) > 0) {
                                    $static_dist = (isset($options['filters']['filter_zipdist_static'])) ? $options['filters']['filter_zipdist_static'] : 0;
                                    if ($static_dist > 0) {
                                        $outFilter = '(TMPTBL' . $staticFields[STATIC_VARS::ZIPCODE] . ' in ' . self::getDistanceQuery($value, $static_dist) . ')';
                                    } else {
                                        $outFilter = '(TMPTBL' . $staticFields[STATIC_VARS::ZIPCODE] . ' = ' . $value . ')';
                                    }
                                    $masterFilters[] = $outFilter;
                                }
                            } else if (substr($filter, 7, 8) == 'zipcode_') {
                                $distance = MAX(0, $options['filters']['filter_zipdist']);
                                $outFilter = '(TMPTBL' . substr($filter, 15) . ' in ' . self::getDistanceQuery($value, $distance) . ')';
                                $masterFilters[] = $outFilter;
                            } else {
                                $filter = str_replace('[]', '', $filter);
                                $filtervar = substr($filter, 7);
                                //var_dump($filtervar);
                                $decodevalue = $value;

                                if ($filtervar == 'keyword') {
                                    if (is_array($decodevalue)) {
                                        //yay... crap code, do nothing
                                    } else {
                                        $buf_keyword = " AND (" . Utility::buildKeywordQuery('response.searchData', $decodevalue) . " OR " . Utility::buildKeywordQuery('response.resumeSearch', $decodevalue) . ")";
                                    }
                                }

                                if ($filtervar == 'optout') {

                                    $decodevalue--;
                                    if ($decodevalue >= 0) {

                                        if ($decodevalue == 0) {
                                            $optoutfilter = " AND ((people.optOut = 0) AND (people.mobileNum NOT IN (SELECT mobileNum FROM optout))) ";
                                        }
                                        if ($decodevalue == 1) {
                                            $optoutfilter = " AND ((people.optOut = 1) OR (people.mobileNum IN (SELECT mobileNum FROM optout))) ";
                                        }
                                    }
                                }

                                //check type of filter var for comparison operators
                                if ($filtervar == 'stageid') {
                                    if (is_array($decodevalue)) {
                                        $masterFilters[] = "(responsestage.stageId IN ('" . implode("','", $decodevalue) . "'))";
                                    } else {
                                        if ($decodevalue > 0) {
                                            $masterFilters[] = "(responsestage.stageId = '{$decodevalue}')";

                                            if ($decodevalue == 999)
                                                $searchRemoved = true;
                                        } else
                                            if ($decodevalue == 0) {
                                                $masterFilters[] = "(responsestage.stageId IS NULL)";
                                            }
                                    }
                                }
                                if ($filtervar == 'eventid') {
                                    if (is_array($decodevalue)) {
                                        $masterFilters[] = "(responseevent.eventId IN ('" . implode("','", $decodevalue) . "'))";
                                    } else {
                                        if ($decodevalue > 0) {
                                            $masterFilters[] = "(responseevent.eventId = '{$decodevalue}')";
                                        } else
                                            if ($decodevalue == 0) {
                                                $masterFilters[] = "(responseevent.eventId IS NULL)";
                                            }
                                    }
                                }
                                //if ($filtervar == 'stagename') {
                                //    if (is_array($decodevalue))
                                //        $masterFilters[] = "(responsestage.stageId IN ('" . implode("','", $decodevalue) . "'))";
                                //    else
                                //        $masterFilters[] = "(responsestage.stageId  = '{$decodevalue}')";
                                //}
                                //if ($filtervar == 'eventname') {
                                //    if (is_array($decodevalue))
                                //        $masterFilters[] = "(responseevent.eventId IN ('" . implode("','", $decodevalue) . "'))";
                                //    else
                                //        $masterFilters[] = "(responseevent.eventId = '{$decodevalue}')";
                                //}
                            }
                    if (!empty($filtervar) && isset($typeLookupTable['TMPTBL' . $filtervar])) {
                        switch ($typeLookupTable['TMPTBL' . $filtervar]) {
                            case 'file':
                            case 'string':
                                $outFilter = "(TMPTBL{$filtervar} LIKE '{$value}%')";
                                break;
                            case 'datetime':
                                $outFilter = "(TMPTBL{$filtervar} >= '{$value}')";
                                break;
                            case 'single':
                            case 'single-meta':
                                if (is_array($decodevalue)) {
                                    $outFilter = "(TMPTBL{$filtervar} IN ('" . implode("','", $decodevalue) . "'))";
                                } else
                                    $outFilter = "(TMPTBL{$filtervar} = '{$value}')";
                                break;
                            case 'multi':
                                if (is_array($decodevalue)) {
                                    $outTempArray = array();
                                    foreach ($decodevalue as $tmpvalue) {
                                        $outTempArray[] = "((TMPTBL{$filtervar}) LIKE '%,{$tmpvalue},%')";
                                    }
                                    $outFilter = "(" . implode(" AND ", $outTempArray) . ")";
                                } else
                                    $outFilter = "(TMPTBL{$filtervar} like '%{$value}%')";
                                break;
                            case 'boolean':
                                if ($value == 'yes') {
                                    $outFilter = "(TMPTBL{$filtervar} = '1')";
                                } else {
                                    $outFilter = "(TMPTBL{$filtervar} <> '1')";
                                }
                                break;
                            default:
                                $outFilter = "(TMPTBL{$filtervar} = '{$value}')";
                                break;
                        }
                        $masterFilters[] = $outFilter;
                    }
                }
            }
        }

        if (!$searchRemoved)
            $where_add .= ' AND (responsestage.stageId <> 999 or responsestage.stageId is null) ';

        $filterstr = '';
        if (count($masterFilters) > 0)
            $filterstr = '  AND ' . implode(' AND ', $masterFilters);

        if (strlen($buf_keyword) > 0 && strlen($filterstr) > 0) {
            $filterstr .= ' AND ' . substr($filterstr, 6);
        }
        $filterstr .= $optoutfilter;

        //get count
        $sql = "SELECT count(*) AS count FROM survey{$surveyId} LEFT JOIN responsestage
                ON responsestage.responseId = survey{$surveyId}.responseId
                AND responsestage.userId={$userId}
                LEFT JOIN responseevent
                ON responseevent.responseId = survey{$surveyId}.responseId
                AND responseevent.userId={$userId}
                LEFT JOIN response on response.surveyResponseId = survey{$surveyId}.responseId
                LEFT JOIN people on people.mobileNum = response.mobileNum
                WHERE 1=1 AND response.surveyResponseId IS NOT NULL
                {$buf_keyword} {$filterstr} {$where_add}";
        //echo $sql;
        //echo "<br /><br />";
        $dbData = Config::get('db')->get_results($sql);
        $count = $dbData[0]['count'];

        $responsesql = "
            SELECT
                survey{$surveyId}.*,
                survey{$surveyId}.responseId AS recid ,
                responsestage.stageId as TMPTBLstage,
                responsestage.stageId as teststage,
                response.surveyResponseId as surveyResponseId,
                stage.name as TMPTBLDisplaystagename,
                responsestage.stageDate as TMPTBLDisplaystagedate,
                responseevent.eventId as TMPTBLevent,
                event.name as TMPTBLDisplayeventname,
                event.name as Displayeventname,
                responseevent.eventDate as TMPTBLDisplayeventdate,
                response.viewed as viewed ,
                response.mobileNum as mobileNum,
                people.optOut as optOut
                FROM survey{$surveyId}
                LEFT JOIN response
                ON response.surveyResponseId=survey{$surveyId}.responseId
                LEFT JOIN responsestage
                ON responsestage.responseId = survey{$surveyId}.responseId
                AND responsestage.userId={$userId}
                AND responsestage.stageDate <> '0000-00-00 00:00:00'
                LEFT JOIN responseevent
                ON responseevent.responseId = survey{$surveyId}.responseId
                AND responseevent.userId={$userId}
                AND responseevent.eventDate <> '0000-00-00 00:00:00'
                LEFT JOIN stage on stage.id=responsestage.stageId
                LEFT JOIN event on event.id=responseevent.eventId
                LEFT JOIN people on people.mobileNum = response.mobileNum
                WHERE 1=1
                {$buf_keyword}
                {$filterstr} {$where_add}
                {$orderBy}
                LIMIT {$startAt},{$limit}
        ";
        //file_put_contents("log/getAnswersQuery.txt", $responsesql);
        //die($responsesql);
        $responseresult = Config::get('db')->get_results($responsesql);
        $masterArray = Array();
        $optOutCounter = 0;
        if ($responseresult && count($responseresult) > 0) {
            try {
                foreach ($responseresult as $response) {
                    $tempResponse = Array();
                    foreach ($response as $k => $v) {
                        if ($k == 'TMPTBLDisplaystagename') {
                            $v = ($response['TMPTBLstage'] > 0) ? Stage::getName($response['TMPTBLstage']) : '';
                        }
                        $tempResponse[str_replace('TMPTBL', '', $k)] = $v;
                    }
                    $tempResponse['mobileNum'] = $response['mobileNum'];
	            $tempResponse['refId'] = str_pad($response['surveyResponseId'],10,'0',STR_PAD_LEFT);
                    $tempResponse['optOut'] = ($response['optOut'] > 0 || SmsManager::isOptOut($response['mobileNum'])) ? 1 : 0;
                    $masterArray[] = $tempResponse;
                }
            }
            catch (Exception $e) {
                Log::add('Error looping through surveys: ' . $e->getMessage(), LOG_TYPES::SURVEY, LOG_SEVERITIES::CRITICAL);
            }
        }

        $outData = Array();
        $outData['records'] = Array();
        foreach ($masterArray as $result) {
            //var_dump($result);
            if ($result['optOut'] == "1" || SmsManager::isOptOut($result['mobileNum'])) {
                $optOutCounter++;
            }
            $result['id'] = $result['responseId'];
            //$result['style'] = ($result['viewed'] == 1) ? '' : 'font-weight:bold';
            $read = ($result['viewed'] == 1) ? '' : 'font-weight:bold';
            if (!$result['surveyResponseId']) {
                continue;
            }

            $responseData = Response::readPri($surveyId, $result['surveyResponseId']);
            $responseObj = json_decode($responseData['response'], true);
            $staticData = Response::getStaticFieldsFromData($surveyId, $responseObj);
            //var_dump($staticData);

            $firstname = '';
            $lastname = '';
            $referral = '';

            if (isset($staticData['firstName']) && strlen($staticData['firstName']) > 0)
                $firstname = $staticData['firstName'];
            if (isset($staticData['lastName']) && strlen($staticData['lastName']) > 0)
                $lastname = $staticData['lastName'];

            if (isset($staticData['referral']) && strlen($staticData['referral']) > 0) {
                $referral = $staticData['referral'];
                $query = "select fullName from emp_ref where mobileNum='{$referral}'";
                $dbData = Config::get('db')->get_results($query);
                $referral = (count($dbData) > 0) ? $dbData[0]['fullName'] : '';
            }
            $result['firstname'] = $firstname;
            $result['lastname'] = $lastname;
            $result['referral'] = $referral;
            //var_dump($result);
            $statusImage = '';

            if (SmsManager::isOptOut($result['mobileNum'])) {
                $statusImage = '<img src="img/stop.png" alt="stop" />';
                $style = 'color:#faa';
            } else if (Person::isOptOut($result['mobileNum'])) {
                $statusImage = '<img src="img/optout.png" alt="stop" />';
                $style = 'color:#faa';
            } else if (Person::onHold($result['mobileNum'])) {
                $statusImage = '<img src="img/hold.png" alt="stop" />';
                $style = 'color:#FFA500';
            } else
                $style = $read;
            $result['status'] = $statusImage;
            $result['style'] = $style;
            $result['Displaystagename'] = isset($result['stage']) ? $stageLookup[$result['stage']] : '';
            $result['Displayeventname'] = isset($result['event']) ? $eventLookup[$result['event']] : '';
            $outData['records'][] = $result;
        }
        $outData['totalCount'] = $count;
        $outData['numOptOut'] = $optOutCounter;
        return $outData;
    }

    public static function getResponseAnswers($surveyId, $responseId) {
        if (!Survey::$_surveyId == $surveyId) {
            Survey::load($surveyId);
        }
        $questions = Survey::$_questions;
        $response = self::readPri($surveyId, $responseId);
        //var_dump($response);
        $outAnswers = Array();
        if ($response) {
            $answers = json_decode($response["response"], true);
            $answerEdit = json_decode($response["responseEdit"], true);
            $answerSMS = json_decode($response["responseSMS"], true);
            if (count($answers) > 0) {
                $outAnswers = $answers;
            }
            if (count($answerSMS) > 0) {
                $outAnswers['responseSMS'] = $answerSMS;
            }
            if (count($answerEdit) > 0) {
                foreach ($questions as $question) {
                    //if both answers exist
                    if (isset($answerEdit[$question->_id])) {
                        $outAnswers[$question->_id] = $answerEdit[$question->_id];
                    }
                }
            }
            if (count($answerEdit) > 0 && count($answerSMS) > 0) {
                foreach ($questions as $question) {
                    //if both answers exist
                    if (isset($answerEdit[$question->_id])) {
                        $outAnswers['responseSMS'][$question->_id] = $answerEdit[$question->_id];
                    }
                }
            }
        }
        $outAnswers['responseFile'] = $response['responseFile'];
        $outAnswers['uploadFile'] = $response['uploadFile'];
        $outAnswers['mobileNum'] = $response['mobileNum'];
        return $outAnswers;
    }


    public static function getSMSGridData($options = NULL) {
        $orderBy = " sms_messages.msgDate DESC ";
        $userId = Config::get('loggedIn');
		$userx = (isset($_COOKIE['jobalarm_userId'])) ? $_COOKIE['jobalarm_userId'] : 0;
		if ($userx == 0 && Router::getGetVar('u')) {
            $userx = Router::getGetVar('u');
		}

        if (isset($_REQUEST['sort']) && is_array($_REQUEST['sort'])) {
            $sorting = $_REQUEST['sort'];
            $sort = $sorting[0]['field'];
            $dir = $sorting[0]['direction'];
            $orderBy = " $sort $dir ";
        }

        $startAt = (isset($options['offset'])) ? $options['offset'] : 0;
        $limit = 100;
        //$surveyId = (isset($options['surveyId'])) ? $options['surveyId'] : NULL;
        //$whereAdd1 = (isset($options['surveyId'])) ? "AND sms_history.surveyId={$surveyId}" : '';
        //$accessArray = Company::getSurveyAccessArray(User::getData('companyId'));
        //$accessWhere = ' AND sms_history.surveyId = 0 ';

        //if (count($accessArray) > 0) {
        //    $accessList = Company::getSurveyAccessString(User::getData('companyId'));
        //    $accessWhere = " AND sms_history.surveyId IN (" . $accessList . ") ";
        //}

        //$stageLookup = Stage::getLookup($surveyId);
        //$eventLookup = EventManager::getLookup($surveyId);
        $searchFields = array();
        $searchArray = array();
        $whereArray = array();
        $havingAdd = '';
        $whereAdd = '';
        $search_add =  '';
        $responseAppend = '';
        $searchRemoved = false;
        $zipCode = '';
        $zipCodeRadius = '';

		//$brandData = Config::get('db')->get_results("SELECT * FROM account WHERE id = {$userId}");
		//$brand1 = $brandData[0]['brandId'];
		//$brand2 = $brandData[0]['brandId2'];
//var_dump($_REQUEST);
          if (isset($_REQUEST['search'])) {
            foreach($_REQUEST['search'] as $search_item) {
                switch($search_item['field']) {
                    case 'filter_stageid':

                            $whereAdd .= " AND g.id = ".$search_item["value"]." ";
                        break;
                    case 'filter_zipCode':
                        $zipCode = $search_item['value'];
                    break;
                    case 'filter_zipdist':
                        $zipCodeRadius = $search_item['value'];
                    break;
                }
            }
        }
        if ($zipCode != '' && $zipCodeRadius != '') {
            $zipList = self::getDistanceQuery($zipCode,$zipCodeRadius);
            $search_add .= " AND candidate.zip in ".$zipList;
        }
        if ($zipCode != '' && $zipCodeRadius == '') {
            $search_add .= " AND candidate.zip = '{$zipCode}'";
        }
        $sqlAdd = '';
        //if (Config::get('loggedIn') == 8) {
        //    $sqlAdd = " AND sms_history.surveyId IN (65310,65624) ";
        //}
        $responseSubQuery = '';
        //if (strlen($responseAppend) > 0) {
        //    $responseSubQuery = " AND response.surveyResponseId IN (SELECT surveyResponseId FROM response WHERE {$responseAppend})";
        //}

        $query = "
        SELECT SQL_CALC_FOUND_ROWS
        sms_messages.candidateId as id,
		sms_messages.message,
        sms_messages.viewed,
        sms_messages.msgDate,
        sms_messages.msgDate as updated,
        candidate.first_name,
		candidate.last_name,
		candidate.email,
		candidate.zip,
		candidate.mobile,
		candidateXref.brandId,
		candidateXref.keyword,
		g.groupName as groupName,
		u.first_name as firstName,
		u.last_name as lastName,
		u.accountId as accountId
		FROM sms_messages
		LEFT JOIN candidate on candidate.id=sms_messages.candidateId
		LEFT JOIN candidateXref on candidateXref.candidateId =sms_messages.candidateId
		LEFT OUTER JOIN users as u on u.id = sms_messages.userId
		LEFT OUTER JOIN `group` as g on g.id=(select cg.groupId from `candidate_group` cg where cg.candidateId=candidate.id and cg.accountId=u.accountId order by cg.id DESC limit 0,1)
		INNER JOIN (select sms_messages.id,sms_messages.candidateId,MAX(sms_messages.msgDate) maxmsgDate from sms_messages where userId=$userx AND type=3 group by sms_messages.candidateId) latest_messages on latest_messages.candidateId=sms_messages.candidateId and sms_messages.msgDate = latest_messages.maxmsgDate
		WHERE (sms_messages.userId =$userx or sms_messages.userId=0) AND sms_messages.type = 3
        {$whereAdd}
        {$search_add}
        GROUP BY candidate.mobile

		ORDER BY {$orderBy}

        LIMIT {$startAt},{$limit}";

        //die($query);
        $dbData = Config::get('db')->get_results($query);
        $rowsFound = Config::get('db')->get_results('SELECT FOUND_ROWS() as rowCount');


        $total = $rowsFound[0]['rowCount'];
        $outputarray = array();
        $outputarray['status'] = 'success';
        $outputarray['total'] = $total;

        foreach ($dbData as $record) {
            $viewed = ($record['viewed'] == 1) ? '' : 'font-weight:bold';
            $statusImage = '';
            $isOptOut = 0;
            //if (SmsManager::isOptOut($record['mobileNum'])) {
            //    $statusImage = '<img src="img/stop.png" alt="stop" />';
            //    $isOptOut = 1;
            //    $style = 'color:#faa';
            //} else if (Person::isOptOut($record['mobileNum'])) {
            //    $statusImage = '<img src="img/optout.png" alt="stop" />';
            //    $isOptOut = 1;
            //    $style = 'color:#faa';
           // } else if (Person::onHold($record['mobileNum'])) {
            //    $statusImage = '<img src="img/hold.png" alt="stop" />';
            //    $isOptOut = 1;
            //    $style = 'color:#FFA500';
            //} else
                $style = $viewed;

            //$responseData = Response::readPri($record['surveyId'], $record['surveyResponseId']);
            //var_dump($responseData);
            //echo "<br /><Br />";
            //$responseObj = json_decode($responseData['response'], true);
            // var_dump($responseObj);
            //echo "<br /><Br />";
            //$staticData = Response::getStaticFieldsFromData($record['surveyId'], $responseObj);
            //var_dump($staticData);

            $referral = '';

            //if (isset($staticData['referral']) && strlen($staticData['referral']) > 0) {
            //    $referral = $staticData['referral'];
            //    $query = "select fullName from emp_ref where mobileNum='{$referral}'";
            //    $subdbData = Config::get('db')->get_results($query);
             //   $referral = (count($subdbData) > 0) ? $subdbData[0]['fullName'] : '';
            //}

            $outputarray['records'][] = array(
                'id' => $record['id'],
                'recid' => $record['id'],
				'group' => $record['groupName'],
                'status' => $statusImage,
                //'stage' => $record['groupId'],
                //'event' => '',
                'responseId' => $record['id'],
                'firstname' => $record['first_name'],
                'lastname' => $record['last_name'],
                'mobilenum' => $record['mobile'],
                'zipcode' => $record['zip'],
				//'recruiter' => $record['lastName'],
                'email' => $record['email'],
                'message' => stripslashes($record['message']),
                'updated' => $record['msgDate'],
                'viewed' => $record['viewed'],
				'recruiter' => substr($record['firstName'],0,1).' '.$record['lastName'],
                'style' => $style,
                'surveyId' => '',
                'referral' =>  '',
				'account1' => $record['accountId'],
				//'recId' => $record['userId'],
				'brand1' => $record['brandId'],
                //'optOut' => ''
            );
        }
        return $outputarray;
    }

    ////Get response form
    //public static function getForm($responseId) {
    //    echo "test";
    //    $surveyData = Config::get('db')->get_results("SELECT surveyId FROM response WHERE surveyResponseId={$responseId}");
    //    $surveyId = $surveyData[0]['surveyId'];
    //    echo $responseId;
    //    Config::get('db')->update("response", Array('viewed' => 1), Array('surveyResponseId' => $responseId));
    //    echo SurveyAdmin::getSurveyForm($responseId, $surveyId);
    //}
    //Get list of Zipcodes based on distance from input zip code
    public static function getDistanceQuery($zipCode, $distance) {
        //  die($zipCode.' : '.$distance);

        $pathstring = getcwd() . "/dat/zipcodes.sqlite";

        self::$_file_db = new PDO("sqlite:" . $pathstring);
        self::$_file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $result = self::$_file_db->query("select zipCode,latitude,longitude from zips where zipcode='{$zipCode}'");
        if ($result) {
            $res = $result->fetch(PDO::FETCH_ASSOC);
            if (count($res) > 0) {
                $lat1 = $res['latitude'];
                $lon1 = $res['longitude'];
                $d = $distance;
                $r = 3959;
                $latN = rad2deg(asin(sin(deg2rad($lat1)) * cos($d / $r) + cos(deg2rad($lat1)) * sin($d / $r) * cos(deg2rad(0))));
                $latS = rad2deg(asin(sin(deg2rad($lat1)) * cos($d / $r) + cos(deg2rad($lat1)) * sin($d / $r) * cos(deg2rad(180))));
                $lonE = rad2deg(deg2rad($lon1) + atan2(sin(deg2rad(90)) * sin($d / $r) * cos(deg2rad($lat1)), cos($d / $r) - sin(deg2rad($lat1)) * sin(deg2rad($latN))));
                $lonW = rad2deg(deg2rad($lon1) + atan2(sin(deg2rad(270)) * sin($d / $r) * cos(deg2rad($lat1)), cos($d / $r) - sin(deg2rad($lat1)) * sin(deg2rad($latN))));
                $zipres = self::$_file_db->query("SELECT * FROM zips WHERE (latitude <= $latN AND latitude >= $latS AND longitude <= $lonE AND longitude >= $lonW) AND (latitude != $lat1 AND longitude != $lon1) AND city != '' ORDER BY state, city, latitude, longitude");
                $ziplist = array($zipCode);
                foreach ($zipres as $zip) {
                    $ziplist[] = $zip['zipcode'];
                }
                return "(" . implode(',', $ziplist) . ")";
            }
        }
        self::$_file_db = null;
        return null;
    }

    //Get response count for given survey Id
    public static function getCount($surveyId, $options = NULL) {
        $queryAdd = '';
        if (isset($options['surveyOnly'])) {
            $queryAdd = "AND responseSMS=''";
        } else if (isset($options['smsOnly'])) {
            $queryAdd = "AND responseSMS<>''";
        }
        $query = "SELECT count(*) AS count FROM response WHERE active > 0 AND surveyId={$surveyId} {$queryAdd}";
        $dbData = Config::get('db')->get_results($query);
        if (count($dbData) > 0) {
            return $dbData[0]['count'];
        }
        return 0;
    }

    //Sync survey files
    public static function downloadFile($surveyId, $responseId, $mobileNum, $fieldId, $fileName, $optional=0) {
        try {
            $targetFolder = 'dat/surveyfiles';
            $fileURL = EZ_FILE_URL . "{$surveyId}/{$responseId}-{$fieldId}/{$fileName}";
            if (!is_dir(dirname($_SERVER['SCRIPT_FILENAME']) . "/{$targetFolder}")) {
                mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . '/' . $targetFolder);
            }
            if (!is_dir(dirname($_SERVER['SCRIPT_FILENAME']) . "/{$targetFolder}/{$surveyId}")) {
                mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . "/{$targetFolder}/{$surveyId}");
            }
            if (!is_dir(dirname($_SERVER['SCRIPT_FILENAME']) . "/{$targetFolder}/{$surveyId}/{$mobileNum}")) {
                mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . "/{$targetFolder}/{$surveyId}/{$mobileNum}");
            }
            $file = dirname($_SERVER['SCRIPT_FILENAME']) . "/{$targetFolder}/{$surveyId}/{$mobileNum}/{$fileName}";

            $fileURL = str_replace(" ", "%20", trim($fileURL));

            if (!file_exists($file)) {

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, $fileURL);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                $source = curl_exec($ch);
                curl_close($ch);

                if (strpos($source, '404 Not Found'))
                    return false;

                @file_put_contents($file, $source);

            }

            $resumeSearch = '';
            try {
                $ext = pathinfo($fileName, PATHINFO_EXTENSION);

                switch (strtolower($ext)) {
                    case 'pdf' :
                        $a = new PDF2Text();
                        $a->setFilename($file);
                        $a->decodePDF();
                        $resumeSearch = $a->output();
                        break;
                    case 'doc' :
                    case 'docx' :
                    case 'pptx' :
                    case 'xlsx' :
                        $docObj = new DocxConversion($file);
                        $resumeSearch = $docObj->convertToText();
                        break;
                    case 'rtf' :
                        $resumeSearch = rtf2text($file);
                        break;
                    case 'txt' :
                        $resumeSearch = file_get_contents($file);
                        break;
                    default:
                        break;
                }
                $resumeSearch = preg_replace('/[[:^print:]]/', '', $resumeSearch);
            }
            catch (Exception $e) {
                // TODO: add error handling to file download resume parser
            }

            //// HACK: FOR POST SURVEY - MOVE DOWNLOADED IMAGE
            if ($surveyId == 127884) {
                $targetFolder = 'dat/jobfiles';
                if (!is_dir(dirname($_SERVER['SCRIPT_FILENAME']) . "/{$targetFolder}")) {
                    mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . '/' . $targetFolder);
                }
                if (!is_dir(dirname($_SERVER['SCRIPT_FILENAME']) . "/{$targetFolder}/{$surveyId}")) {
                    mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . "/{$targetFolder}/{$surveyId}");
                }
                if (!is_dir(dirname($_SERVER['SCRIPT_FILENAME']) . "/{$targetFolder}/{$surveyId}/{$optional}")) {
                    mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . "/{$targetFolder}/{$surveyId}/{$optional}");
                }
                $jobpostfile = dirname($_SERVER['SCRIPT_FILENAME']) . "/{$targetFolder}/{$surveyId}/{$optional}/{$fileName}";
                copy($file,$jobpostfile);
            }
            //// END HACK

            $data = array('responseFile' => Config::get('db')->filter($fileName), 'resumeSearch' => Config::get('db')->filter($resumeSearch));
            $where = array('surveyId' => $surveyId, 'surveyResponseId' => $responseId);


            Config::get('db')->update('response', $data, $where, 1);
        }
        catch (Exception $e) {
            return false;
        }
        return true;
    }

    public static function get_query_search_filters($search,&$accessArray=null) {
        $filter = array();
        $have_list = array();
        $where_list = array();
        $search_removed_stage = false;
        $where = '';
        $have = '';

        foreach ($search as $option)
            $filter[$option['field']] = $option['value'];

        foreach ($filter as $k => $v) {
            if (($k == 'filter_zipCode') && (!isset($filter['filter_zipdist'])))
                $have_list[] = " zipCode = '{$v}'";
            else if ($k == 'filter_zipdist' && isset($filter['filter_zipCode']) && $filter['filter_zipCode'] != '')
                $have_list[] = " zipCode IN " . self::getDistanceQuery($filter['filter_zipCode'], $v) . " ";
            else if ($k == 'filter_keyword')
                $where_list[] = " (" . Utility::buildKeywordQuery("response.searchData", $v) . " OR " . Utility::buildKeywordQuery("response.resumeSearch", $v) . ") ";
            else if ($k == 'filter_optout') {
                if (($v - 1) == 0)
                    $where_list[] = " ((people.optOut = 0) AND (people.mobileNum NOT IN (SELECT mobileNum FROM optout))) ";
                else if (($v - 1) == 1)
                    $where_list[] = " ((people.optOut = 1) OR (people.mobileNum IN (SELECT mobileNum FROM optout))) ";
            } else if ($k == 'filter_surveyselect') {
                if ($v == 1) {
                    $accessArray = Company::getSurveyAccessArray(User::getData('companyId'));
                }

            } else if ($k == 'filter_stageid') {
                if (is_array($v)) {
                    $search_null = false;

                    foreach ($v as $value) {
                        if ($value == 0)
                            $search_null = true;
                        else if ($value == 999)
                            $search_removed_stage = true;
                    }

                    $have_list[] = " (responsestage.stageId IN ('" . implode("','", $v) . "')" . ($search_null ? " OR responsestage.stageId IS NULL " : "") . ") ";
                } else {
                    if ($v <= 0)
                        $have_list[] = " (responsestage.stageId IS NULL) ";
                    else {
                        $have_list[] = " (responsestage.stageId = {$v}) ";

                        if ($v == 999)
                            $search_removed_stage = true;
                    }
                }
            } else if ($k == 'filter_eventid') {
                if (is_array($v)) {
                    $search_null = false;

                    foreach ($v as $value) {
                        if ($value == 0)
                            $search_null = true;
                    }

                    $have_list[] = " (responseevent.eventId IN ('" . implode("','", $v) . "')" . ($search_null ? " OR responseevent.eventId IS NULL " : "") . ") ";
                } else {
                    if ($v <= 0)
                        $have_list[] = " (responseevent.eventId IS NULL) ";
                    else
                        $have_list[] = " (responseevent.eventId = {$v}) ";
                }
            } else {
                if (($k != 'filter_zipCode') && ($k != 'filter_zipdist')) {
                    if (is_array($v)) {
                        $temp = array();

                        foreach ($v as $subv)
                            if ($subv != -1)
                                $temp[] = substr($k, 7) . " LIKE '" . $subv . "%' ";

                        if (count($temp) > 0)
                            $have_list[] = " (" . implode(" OR ", $temp) . ") ";
                    } else {
                        if ($v != -1)
                            $have_list[] = substr($k, 7) . " LIKE '" . $v . "%' ";
                    }
                }
            }
        }

        if (count($have_list) > 0)
            $have = " HAVING " . implode(" AND ", $have_list);

        if (count($where_list) > 0)
            $where = " AND " . implode(" AND ", $where_list);

        if (!$search_removed_stage)
            $where .= " AND ((responsestage.stageId <> 999) OR (responsestage.stageId IS NULL)) ";

        return $where . $have;
    }

}
