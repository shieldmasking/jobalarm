<?php


class Person {
    public static $_currentSurvey = null;
    
    public static function read($criteria=null,$mobileNumSearch=false) {
        $whereAdd = '';
        if ($criteria && strlen($criteria) >0) {
            if ($mobileNumSearch) {
                $whereAdd = " AND people.mobileNum = '{$criteria}' ";
            } else {
                $whereAdd = " AND people.id = {$criteria} ";
            }
        }
        $query = "SELECT * FROM people WHERE active>0 {$whereAdd}";
        //echo $query."<br />";
        $dbData = Config::get('db')->get_results($query);
        if ($dbData && count($dbData)>0) {
            self::$_currentSurvey = $dbData[0]['currentSurvey'];
            return $dbData[0];
        }
        return false;
    }
    
    public static function exists($mobileNum) {
        $query = "SELECT id FROM people WHERE mobileNum='{$mobileNum}'";
        $dbData = Config::get('db')->get_results($query);
        if ($dbData && count($dbData) > 0) {
            return $dbData[0]['id'];
        }
        return false;
    }
    
    public static function getCurrentSurvey($personId=null) {
        if ($personId) {
            self::read($personId);
        }
        return self::$_currentSurvey;
    }
    
    public static function create($data) {
        $data['createDate'] = date('Y-m-d H:i:s');
        $data['lastUpdate'] = date('Y-m-d H:i:s');
        Config::get('db')->insert('people',$data);
        return Config::get('db')->lastid();
    }
    
    public static function update($personId,$data) {
        $where = array('id'=>$personId);
        Config::get('db')->update('people',$data,$where,1);
    }
    
    public static function delete($personId) {
        $where = array('id'=>$personId);
        Config::get('db')->delete('people',$where,1);
    }
    
    public static function getUserIdFromMobile($mobileNum) {
        $query = "SELECT id FROM people WHERE mobileNum='{$mobileNum}'";
        $dbData = Config::get('db')->get_results($query);
        if ($dbData && count($dbData) >0) {
            return $dbData[0]['id'];
        }
        return false;
    }
    
    //Update people data from updated response
    public static function updateFromResponse($surveyId, $peopleId, $responseId, $responseType = RESPONSE_TYPES::RESPONSE) {
        $staticVars = Survey::getStaticVars($surveyId);
        //var_dump($staticVars);
        //$mobileNumStatic = (isset($staticVars[STATIC_VARS::MOBILENUM])) ? $staticVars[STATIC_VARS::MOBILENUM] : '';
        $firstNameStatic = (isset($staticVars['tpl_firstname'])) ? $staticVars['tpl_firstname'] : '';
        $lastNameStatic = (isset($staticVars['tpl_lastname'])) ? $staticVars['tpl_lastname'] : '';
        $emailStatic = (isset($staticVars['tpl_email'])) ? $staticVars['tpl_email'] : '';
        $zipCodeStatic = (isset($staticVars['tpl_zipcode'])) ? $staticVars['tpl_zipcode'] : '';
        //$person = self::getPersonByID($peopleId);
        $response = Response::readPri($surveyId,$responseId);
        //$response = self::getResponse($surveyId, Array('responseId' => $responseId));
        switch ($responseType) {
            case RESPONSE_TYPES::RESPONSE:
                $responseObj = json_decode(stripslashes($response['response']),true);
                break;
            case RESPONSE_TYPES::RESPONSEEDIT:
                $responseObj = json_decode(stripslashes($response['responseEdit']),true);
                break;
            case RESPONSE_TYPES::RESPONSESMS:
                $responseObj = json_decode(stripslashes($response['responseSMS']),true);
                break;
        }
        $updatePerson = Array();
        if (isset($responseObj[$firstNameStatic])) {
            $updatePerson['firstName'] = $responseObj[$firstNameStatic];
        }
        if (isset($responseObj[$lastNameStatic])) {
            $updatePerson['lastName'] = $responseObj[$lastNameStatic];
        }
        if (isset($responseObj[$emailStatic])) {
            $updatePerson['email'] = $responseObj[$emailStatic];
        }
        if (isset($responseObj[$zipCodeStatic])) {
            $updatePerson['zipCode'] = $responseObj[$zipCodeStatic];
        }
        $fileLog = "
        SURVEYID: {$surveyId}\r\n
        PEOPLEID: {$peopleId}\r\n
        RESPONSEID: {$responseId}\r\n
        RESPONSETYPE: {$responseType}\r\n
        STATICVARS: ".print_r($staticVars,true)."\r\n
        RESPONSEOBJ: ".print_r($responseObj,true)."\r\n
        UPDATEPERSON: ".print_r($updatePerson,true)."\r\n
        ";
        //file_put_contents("log/updateFromResponse.txt",$fileLog);
        //var_dump($updatePerson);
        self::update($peopleId,$updatePerson);
    }    

    //Update people data from sms survey current answers
    public static function updateFromCurrentAnswers($peopleId) {
        $person = self::read($peopleId);
        if (isset($person['currentAnswers']) && strlen($person['currentAnswers']) > 4) { 
            $staticVars = Survey::getStaticVars($person['currentSurvey']);
            //var_dump($staticVars);
            //$mobileNumStatic = (isset($staticVars[STATIC_VARS::MOBILENUM])) ? $staticVars[STATIC_VARS::MOBILENUM] : '';
            $firstNameStatic = (isset($staticVars['tpl_firstname'])) ? $staticVars['tpl_firstname'] : '';
            $lastNameStatic = (isset($staticVars['tpl_lastname'])) ? $staticVars['tpl_lastname'] : '';
            $emailStatic = (isset($staticVars['tpl_email'])) ? $staticVars['tpl_email'] : '';
            $zipCodeStatic = (isset($staticVars['tpl_zipcode'])) ? $staticVars['tpl_zipcode'] : '';
            //$person = self::getPersonByID($peopleId);
            
            $responseObj = json_decode(stripslashes($person['currentAnswers']),true);
            $updatePerson = Array();
            if (isset($responseObj[$firstNameStatic])) {
                $updatePerson['firstName'] = $responseObj[$firstNameStatic];
            }
            if (isset($responseObj[$lastNameStatic])) {
                $updatePerson['lastName'] = $responseObj[$lastNameStatic];
            }
            if (isset($responseObj[$emailStatic])) {
                $updatePerson['email'] = $responseObj[$emailStatic];
            }
            if (isset($responseObj[$zipCodeStatic])) {
                $updatePerson['zipCode'] = $responseObj[$zipCodeStatic];
            }

            //var_dump($updatePerson);
            self::update($peopleId,$updatePerson);
        }
    }    
    
    public static function sanityResponseCheck() {
        $query = "select * from response left join people on response.peopleId = people.id where people.firstName = '' and surveyResponseId > 100000 and responseSMS=''";
        $result = Config::get('db')->get_results($query);
        foreach($result as $response) {
            //echo $response['surveyResponseId']."<br />";
            Person::updateFromResponse($response['surveyId'],$response['peopleId'],$response['surveyResponseId'],RESPONSE_TYPES::RESPONSE);
        }    
    }
    
    public static function setHold($mobileNum) {
        $data = array('onHold'=>1,'holdDate'=>date('Y-m-d H:i:s'));
        $where = array('mobileNum'=>$mobileNum);
        Config::get('db')->update('people',$data,$where,1);
    }
    
    public static function onHold($mobileNum) {
        $query = "select id from people where onHold=1 and mobileNum='{$mobileNum}'";
        $dbData = Config::get('db')->get_results($query);
        return ($dbData && count($dbData) > 0);
    }
    
    public static function isOptOut($mobileNum) {
        $query = "select id from people where optOut=1 and mobileNum='{$mobileNum}'";
        $dbData = Config::get('db')->get_results($query);
        return ($dbData && count($dbData) > 0);        
    }
    
    public static function optOut($mobileNum) {
        $data = array('optOut'=>1);
        $where = array('mobileNum'=>$mobileNum);
        Config::get('db')->update('people',$data,$where,1);
    }
    
    public static function optIn($mobileNum) {
        $data = array('optOut'=>0);
        $where = array('mobileNum'=>$mobileNum);
        Config::get('db')->update('people',$data,$where,1);        
    }

    public static function removeExpiredHolds() {
        $query = "update people set onHold=0 where onHold=1 and holdDate < NOW() - INTERVAL 12 HOUR";
        Config::get('db')->query($query);
    }
    
    public static function getSmsCount($mobileNum) {
        $query = "SELECT smsCount FROM people WHERE mobileNum='$mobileNum'";
        $dbData = Config::get('db')->get_results($query);
        if ($dbData && count($dbData) > 0) {
            return $dbData[0]['smsCount'];
        }
        return 0;
    }
    
    public static function incSmsCount($mobileNum) {
        $query = "UPDATE people SET smsCount = smsCount + 1 WHERE mobileNum = '$mobileNum'";
        Config::get('db')->query($query);
        return self::getSmsCount($mobileNum);
    }
    
}
