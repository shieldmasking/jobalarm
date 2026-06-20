<?php
require_once('models/user.php');
class Reports {
    
    public static function doView() {
        if (!User::checkLogin()) {
            header('location: home');
        }        
        if (Router::getGetVar('sid')) {
            Config::push('jsvars', array('surveyId' => Router::getGetVar('sid')));
            $dbData = Config::get('db')->get_results('SELECT name from survey where surveyId=' . Router::getGetVar('sid'));
            Config::push('jsvars', array('surveyName' => $dbData[0]['name']));
        }
        Config::push('scripts','views/shared/sharedfunctions.js');
        Config::push('scripts','views/reports/reports.js');
        Config::set('mainmenu',true);
        Config::set('sysblock',true);
        require_once('views/reports/reports.php');
    }
    
    public static function run() {
        self::doView();
    }
    
    public static function getAll($surveyId) {
        $limit = $_REQUEST['limit'];
        $start = $_REQUEST['offset'];
        $searchFields = array();
        $searchArray = array();
        $whereArray = array();
        $havingAdd = '';
        $whereAdd = '';
        $userId = Config::get('loggedIn');
        if (isset($_REQUEST['search'])) {
            foreach ($_REQUEST['search'] as $search) {
                $searchFields[$search['field']] = $search['value'];
            }
            foreach ($searchFields as $field => $value) {
                if ($field == 'filter_zipCode' && !isset($searchFields['filter_zipdist'])) {
                    $searchArray[] = " zipCode = '{$value}'";
                } else if ($field == 'filter_zipdist' && isset($searchFields['filter_zipCode']) && $searchFields['filter_zipCode'] != '') {
                    $zipCodes = self::getDistanceQuery($searchFields['filter_zipCode'], $value);
                    $searchArray[] = " zipCode IN {$zipCodes} ";
                } else if ($field == 'filter_keyword') {
                    //$whereArray[] = " (response.searchData LIKE '%{$value}%' or response.resumeSearch LIKE '%{$value}%')";
                    $whereArray[] =  " (".Utility::buildKeywordQuery('response.searchData',$value)." OR ".Utility::buildKeywordQuery('response.resumeSearch',$value).")";
                } else if ($field != 'filter_zipCode' && $field != 'filter_zipdist') {
                    if (is_array($value)) {
                        $subSearchArray = array();

                        foreach ($value as $subval) {
                            if ($subval != "-1") {
                                $subSearchArray[] = substr($field, 7) . " LIKE '" . $subval . "%'";
                            }
                        }
                        $searchArray[] = "(" . implode(" OR ", $subSearchArray) . ")";
                    } else {
                        if ($value != "-1") {
                            $searchArray[] = substr($field, 7) . " LIKE '" . $value . "%'";
                        }
                    }
                }
            }

            if (count($searchArray) > 0) {
                $havingAdd = "HAVING " . implode(' AND ', $searchArray);
            }
            if (count($whereArray) > 0) {
                $whereAdd = "AND " . implode(' AND ', $whereArray);
            }
        }
        Survey::load($surveyId);
        $staticVars = Survey::getStaticVars($surveyId);
        $positionVar = '';
        if (isset($staticVars['tpl_position'])) {
            $positionVar = $staticVars['tpl_position'];
        }
        //self::$_questions[$key]->_choices
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
            responsestage.stageId as stageid,
            responseevent.eventId as eventid
            FROM response
            LEFT join people
            ON people.id=response.peopleId
            LEFT JOIN responsestage
            ON responsestage.responseId = response.surveyResponseId
            AND responsestage.userId={$userId}
            AND responsestage.stageDate <> '0000-00-00 00:00:00'            
            LEFT JOIN responseevent
            ON responseevent.responseId = response.surveyResponseId          
            AND responseevent.userId={$userId}
            AND responseevent.eventDate <> '0000-00-00 00:00:00'
            WHERE response.active>0
            {$whereAdd}
            {$havingAdd}
            ORDER BY updated DESC
            LIMIT {$start},{$limit}
        ";

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
            $style = (SmsManager::isOptOut($response['mobileNum'])) ? 'color:#faa' : $read;
            $style = (Person::onHold($response['mobileNum'])) ? 'color:#FFA500' : $style;
            $referral = '';
            $refFirstName = '';
            $refLastName = '';
            $responseData = Response::readPri($response['surveyId'],$response['surveyResponseId']);
            
            $responseObj = json_decode($responseData['response'],true);
            $staticData = Response::getStaticFieldsFromData($response['surveyId'],$responseObj);
            //var_dump($staticData);
            if (isset($staticData['referral']) && strlen($staticData['referral']) > 0) {
                $referral = $staticData['referral'];
                $refPerson = Person::read($referral,true);
                if ($refPerson) {
                    $refFirstName = $refPerson['firstName'];
                    $refLastName = $refPerson['lastName'];
                    $referral = $refFirstName.' '.$refLastName;
                }
                $position = '';
                if (strlen($positionVar) > 0) {
                    if (isset(Survey::$_questions[$positionVar]) && isset(Survey::$_questions[$positionVar]->_choices[$staticData['position']])) {
                        $position = Survey::$_questions[$positionVar]->_choices[$staticData['position']]; 
                    }
                }
                $dataArray[] = array(
                    'recid' => $response['surveyResponseId'],
                    'actions' => '<button onclick="">Manage</button>',
                    'surveyname' => (isset($surveydata[0])) ? $surveydata[0]['name'] : '',
                    'firstname' => stripslashes($response['firstName']),
                    'lastname' => stripslashes($response['lastName']),
                    'reflastname' => $refLastName,
                    'reffirstname' => $refFirstName,
                    'appdate' => $response['updated'],
                    'mobilenum' => $response['mobileNum'],
                    'position' => $position,
                    'email' => $response['email'],
                    'zipcode' => $response['zipCode'],
                    'stage' => isset($stages[$response['stageid']]) ? $stages[$response['stageid']] : '',
                    'event' => isset($events[$response['eventid']]) ? $events[$response['eventid']] : '',
                    'updated' => $response['updated'],
                    'referral' => $referral,
                    'style' => $style
                );
            }
        }
        $outArray = array(
            'status' => 'success',
            'total' => count($dataArray),
            'records' => $dataArray
        );
        echo json_encode($outArray);   
    }
    
}

