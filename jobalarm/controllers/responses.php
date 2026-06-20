<?php

class Responses {

    public static function run() {
        echo self::getDistanceQuery('75218',50);
        //Response::read(28421, array('where' => array('mobileNum' => '2149343360')));
    }

    public static function getDistanceQuery($zipCode, $distance) {
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

    public static function getUserID() {
        $userId = (isset($_COOKIE['jobalarm_userId'])) ? $_COOKIE['jobalarm_userId'] : 0;
        //echo $userId.": again";
    }

    public static function getCandidates() {

        $accountId = Config::get('loggedIn');
        $accountId = Router::getGetVar('a');
		$userId = (isset($_COOKIE['jobalarm_userId'])) ? $_COOKIE['jobalarm_userId'] : 0;
		$userBrand ='';
		if ($userId == 0 && Router::getGetVar('u')) {
            $userId = Router::getGetVar('u');
		}
		$brandData = Config::get('db')->get_results("SELECT * FROM assign_brand WHERE userId = {$userId}");

		if ($brandData && count($brandData) > 0) {
			foreach ($brandData as $brdata){
				$userBrand .= " OR x.brandOrig = '".$brdata['brandId']."'";
			}
		}

		//$brand1 = $brandData[0]['brandId'];
		//$brand2 = $brandData[0]['brandId2'];
        $dbconn = mysqli_connect(DB_HOST,DB_USER,DB_PASS);
		$link='';
        mysqli_select_db($dbconn,'tweetedj_tweetedjobs');
        
		$search_add = " AND ((g.id !=16 and g.id !=17) or g.id IS NULL)";
		$search_add2 = '';
        $zipCode = '';
		$keyWord = '';
		$brandId = '';
		$limit = "LIMIT 0,3000";
		//$limit = '';
        $zipCodeRadius = '';
        $brand_add = " AND x.keyword2 != ''";
		$zipLock = 0;
		
		if (isset($_REQUEST['search'])) {
			$limit = '';
			  foreach($_REQUEST['search'] as $search_item) {
                switch($search_item['field']) {
                    case 'filter_brand':
                        if (!Router::getGetVar('b') && ($search_item["value"]!='0'))
						if (!Router::getGetVar('b'))
                            $brand_add .= " AND (x.keyword = '".$search_item["value"]."' OR x.keyword2 = '".$search_item["value"]."')";
                        break;
					case 'filter_keyword':
                        if ($search_item["value"]!='')
                            $search_add .= " AND (c.resume LIKE \"%".$search_item["value"]."%\" OR c.first_name LIKE \"%".$search_item["value"]."%\" OR t.title LIKE \"%".$search_item["value"]."%\" OR p.position LIKE \"%".$search_item["value"]."%\" OR p.pasteResume LIKE \"%".$search_item["value"]."%\" OR c.last_name LIKE \"%".$search_item["value"]."%\" OR c.mobile LIKE \"%".$search_item["value"]."%\" OR x.job_type LIKE \"%".$search_item["value"]."%\")";
                        break;
                    case 'filter_groupid':
						if ($search_item['value'] > 0)
							$search_add = '';
							$search_add .= " AND g.Id=".$search_item['value'];
						if ($search_item['value'] == 0)
                            $search_add = " AND ((g.Id !=16 and g.Id !=17) or g.Id IS NULL)";
					break;
                    case 'filter_zipCode':
                        $zipCode = $search_item['value'];
						$zipLock = 1;
                    break;
                    case 'filter_zipdist':
                        $zipCodeRadius = $search_item['value'];
                    break;
                }
            }
        }
		if (Router::getGetVar('z')) {
			$search_add .= " AND (x.promo=1 or x.promo=2)";
			$limit = '';
            }
		if (Router::getGetVar('z') && $zipLock==0) {
			$zipCode = Router::getGetVar('z');
			$zipCodeRadius = 16;
			$search_add .= " AND (x.promo=1 or x.promo=2)";
			$limit = '';
            }
			
        if ($zipCode != '' && $zipCodeRadius != '') {
            $zipList = self::getDistanceQuery($zipCode,$zipCodeRadius);
            $search_add .= " AND c.zip in ".$zipList;
			$limit = '';
        }
        if ($zipCode != '' && $zipCodeRadius == '') {
            $search_add .= " AND c.zip = '{$zipCode}'";
			$limit = '';
        }
			
		//$link = isset($_REQUEST['m']) ? $_REQUEST['m'] : '';
		
		if (isset($_REQUEST['m'])) {
		$search_add .= " AND (ap.candidateId > 0)";
		$limit = '';
        }

		if (Router::getGetVar('b')) {
            $brandId = Router::getGetVar('b');
            //$brands = Config::get('db')->get_results("select * from sms_brand where id={$brandId}");
            //if ($brandId == 9) {
                //$brandName = $brands[0]['keyword'];
              //  $search_add .= " AND (c.resume LIKE \"%retail%\" OR c.resume ='')";
				//$search_add2 .=" AND x.promo>0";
				//}
        }


        $order_by = "g.groupName ASC,c.entered DESC";


        if (isset($_REQUEST['sort'])) {
            foreach($_REQUEST['sort'] as $sort_item) {
                switch($sort_item['field']) {
                     case 'position':
                        $order_by = "t.title ".$sort_item['direction'];
                     break;
                     case 'zipcode':
                        $order_by = "Czip ".$sort_item['direction'];
                     break;
                     case 'updated':
                        $order_by = "x.subscribeDate ".$sort_item['direction'];
                     break;
					 case 'lastname':
                        $order_by = "c.last_name ".$sort_item['direction'];
                     break;
					 case 'brand':
                        $order_by = "x.keyword ".$sort_item['direction'];
                     break;
					 case 'mobilenum':
                        $order_by = "c.mobile ".$sort_item['direction'];
                     break;
					 case 'resume':
                        $order_by = "c.resume ".$sort_item['direction'];
                     break;
					 case 'email':
                        $order_by = "c.email ".$sort_item['direction'];
                     break;
					 case 'msgcount':
                        $order_by = "mgCount ".$sort_item['direction'];
                     break;
					 case 'recruiter':
                        $order_by = "u.last_name ".$sort_item['direction'];
                     break;
					 case 'group':
                        $order_by = "g.groupName ".$sort_item['direction'];
                     break;
                }
            }
        }
        $query = "
        SELECT x.*,
        c.first_name,
        c.last_name,
        c.active,
        c.mobile,
        c.email,
        LPAD(c.zip, 5, '0') as Czip,
        c.resume_file,
        c.resume,
        c.entered,
		p.position,
		p.pasteResume,
        g.groupName as groupName,
        u.first_name as firstName,
        u.last_name as lastName,
		u.accountId as accountId,
		t.title as jobTitle,
		m.msgDate as msgDate,
		m.userId as userId,
		sum((case m.type when 1 then 1 else 0 end) + (case m.type when 2 then 1 else 0 end) + (case m.type when 9 then 1 else 0 end) - (case m.type when 3 then 1 else 0 end)) AS mgCount
        FROM `candidateXref` as x
        LEFT JOIN `candidate` as c on c.id = x.candidateId
		LEFT OUTER JOIN `candidateApply` as p on p.id =(select ca.id from `candidateApply` ca where ca.candidateId=c.id and ca.brand=x.brandOrig order by ca.id desc limit 0,1)
		LEFT OUTER JOIN `clickTrack` as t on t.id = (select ct.id from `clickTrack` ct where ct.candidateId=c.id and ct.brand=x.brandOrig order by ct.id desc limit 0,1)
		LEFT OUTER JOIN `users` as u on u.id =(select cg.userId from `candidate_group` cg where cg.candidateId=c.id and cg.accountId={$accountId} order by cg.id desc limit 0,1)
		LEFT OUTER JOIN `group` as g on g.Id=(select cg.groupId from `candidate_group` cg where cg.candidateId=c.id and cg.accountId={$accountId} order by cg.id desc limit 0,1)
        LEFT OUTER JOIN `sms_messages` as m on m.candidateId = x.candidateId and m.brandId = x.brandId and m.type<10 and m.msgDate BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW()
        WHERE c.active = 1 and c.mobile != '' and x.promo<2 and (x.brandOrig=0$userBrand)
        $search_add
        $brand_add
        group by x.candidateId
        HAVING mgCount<4
        ORDER BY $order_by
        $limit";
		
		//WHERE c.active = 1 and c.mobile != '' and (x.promo=2 or (x.promo=2 and (x.brandId=6 or x.brandId=19$userBrand)) or (x.promo=0 and x.promoMktng !=2 and (x.brandId=6 or x.brandId=19$userBrand)))
        //$result = mysqli_query($dbconn,$query);
        $dbresult = Config::get('db')->get_results($query);
        $dataArray = array();
        foreach($dbresult as $k => $row) {
            //$dbresult[$k]['style'] = ($dbresult[$k]['promo'] > 0) ? 'color:blue' : 'color:red';
			
			$dbmDate = Config::get('db')->get_results("select * from `sms_messages` where `candidateId`={$row['candidateId']} and `accountId`={$accountId} order by `type` asc, `msgDate` desc");
			$style = '';
			$dateMsg = '';
			
			if ($dbmDate){			
			$now = time(); // or your date as well
			$msgDate = strtotime($dbmDate[0]['msgDate']);
			$datediff = $now - $msgDate;
			$dateMsg = $datediff / (60 * 60 * 24);
			}
			 if ($row['promo']==0){
				$style = 'color:red';				 
			 } else if ($row['promo']>0 && $row['accountId']==$accountId && $dateMsg<30 && $dateMsg !='' && $dateMsg>0){
				 $style = 'color:orange';	
			 } else{
				 $style = 'color:black';	
			 }
			 
			 if ($row['position']!='NULL'){
				 $position = $row['position'];
			 }else{
				 $position = $row['job_type'];
			 }
			 //if ($row['pasteResume']!='NULL'){
			//	 $resume = $row['pasteResume'];
			 //}else{
				 $resume = $row['resume'];
			 //}
			
			
			//$style = ($row['promo'] ==0 || $row['msgCount'] > 3) ? 'color:red' : 'color:black';
			
			$dataArray[] = array(
                'recid' => $row['candidateId'],
				'brand' => $row['keyword2'],
                'group' => $row['groupName'],
				'firstname' => $row['first_name'],
                'lastname' => $row['last_name'],
                'position' => $position.' '.$row['jobTitle'],
                'mobilenum' => $row['mobile'],
                'email' => $row['email'],
                'zipcode' => $row['Czip'],
				'recruiter' => substr($row['firstName'],0,1).' '.$row['lastName'],
				'account1' => $row['accountId'],
				'opt' => $row['promo'],
				//'brand1' => $row['brandId'],
                'stage' => '',
                'event' => '',
                'updated' => $row['entered'],
				'resume' => $resume,
				//'resume' => strlen($row['resume_file']) > 0 ?  '<a href="resumes/'.$row['resume_file'].'">Download</a>' : '',
				'msgcount' => $row['mgCount'],
				'style' => $style
            );
        }
        $outArray = array(
            'status' => 'success',
            'total' => count($dataArray),
            'records' => $dataArray,
			'sql' => $query
        );
        echo json_encode($outArray);
    }

    public static function getAll() {
        Person::sanityResponseCheck();
        Person::removeExpiredHolds();
        Response::getGlobal();
    }

    public static function getAllSurvey($surveyId) {
        set_time_limit(0);
        Person::sanityResponseCheck();
        Person::removeExpiredHolds();
        //Response::importNewResponses($surveyId, array('filter' => '&_updated_at>' . Response::getLastUpdateSurvey($surveyId)));

        //$dbData = 1;

        //while (count($dbData) > 0) {
        //    $query = "SELECT * FROM responsequeue WHERE surveyId = {$surveyId} AND processed = 0 LIMIT 0,100";
        //    $dbData = Config::get('db')->get_results($query);

        //    if ($dbData && count($dbData) > 0) {
        //        foreach ($dbData as $response) {
        //            $responseData = json_decode($response['responseData'], true);
        //            Response::add($surveyId, $responseData, false);
        //            Response::queueMarkProcessed($response['responseId']);
        //        }
        //    }
        //}

        $searchFields = array();
        $searchArray = array();
        $havingAdd = '';

        if (isset($_REQUEST['search'])) {
            $havingAdd = 'HAVING ';

            foreach ($_REQUEST['search'] as $search) {
                $searchFields[$search['field']] = $search['value'];
            }
        }

        $offset = (isset($_REQUEST['offset'])) ? $_REQUEST['offset'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 0;

        $options = array(
            'offset' => $offset,
            'limit' => $limit,
            'filters' => $searchFields
        );

        $responses = Response::getAnswers($surveyId, $options);

        $outArray = array(
            'status' => 'success',
            'total' => $responses['totalCount'],
            'numOptOut' => $responses['numOptOut'],
            'records' => $responses['records']
        );

        echo json_encode($outArray);
    }

    public static function getAllSMS($surveyId = NULL) {
        //Person::sanityResponseCheck();
        //Person::removeExpiredHolds();
		$accountId = Config::get('loggedIn');
		$accountId = Router::getGetVar('a');
        $offset = (isset($_REQUEST['offset'])) ? $_REQUEST['offset'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 100;
        $options = array(
            'offset' => $offset,
            'limit' => $limit
                //,
                //'filters' => $searchFields
        );
        //if ($surveyId) {
        //    $options['surveyId'] = $surveyId;
        //}
        $data = Response::getSMSGridData($options);
        
        echo json_encode($data);
    }

    public static function getForm($responseId) {
        Survey::getForm($responseId);
    }

    public static function submitResponseForm($smsResponse, $responseId, $surveyId) {
        $POST = $_REQUEST;

        $POST['_updated_at'] = str_replace(' ', 'T', date('Y-m-d H:i:s'));

        $targetfield = (isset($POST['smsresponse'])) ? 'responseSMS' : 'responseEdit';
        $targettype = (isset($POST['smsresponse'])) ? RESPONSE_TYPES::RESPONSESMS : RESPONSE_TYPES::RESPONSEEDIT;
        $datar = array($targetfield => Config::get('db')->filter(json_encode($POST)));

        $dbData = Config::get('db')->get_results("SELECT * FROM response WHERE surveyResponseId=" . $responseId);

        if (count($dbData) > 0) {
            Response::updatePri($responseId, $datar);
            NoteManager::addNote(User::getId(), $surveyId, $dbData[0]['peopleId'], 'Updated Response Data');
            Person::updateFromResponse($surveyId, $dbData[0]['peopleId'], $responseId, $targettype);
            Response::updateSec($responseId,(strlen($dbData[0]['responseSMS']) > 0));
            //SurveyAdmin::updatePeopleResponseData($surveyId, $dbData[0]['peopleId'], $responseId, $targettype);
        }

        echo "{'status':'success'}";
    }

    public static function setActions() {
        $people = (isset($_REQUEST['people'])) ? $_REQUEST['people'] : array();
        $accountId = (isset($_REQUEST['accountId'])) ? $_REQUEST['accountId'] : null;
        $from = (isset($_REQUEST['from'])) ? $_REQUEST['from'] : null;
        $group = (isset($_REQUEST['group'])) ? $_REQUEST['group'] : null;
		
		$userx = (isset($_COOKIE['jobalarm_userId'])) ? $_COOKIE['jobalarm_userId'] : 0;
		if ($userx == 0 && Router::getGetVar('u')) {
            $userx = Router::getGetVar('u');
		}
        if (count($people) > 0) {
            foreach ($people as $candidateId) {
                if ($accountId && $group >= 0) {
                    Group::updateCandidate($accountId,$candidateId,$group,$userx);
                    //SurveyAdmin::updateResponseStage($surveyId, $responseId, $stage);
                }
            }
        }

        echo "{'status':'success'}";
    }

    public static function uploadFile() {
        $targetFolder = 'dat/surveyfiles'; // Relative to the root
        if (isset($_REQUEST['responseId'])) {
            $responseId = $_REQUEST['responseId'];
            $surveyId = Response::getSurveyId($responseId);
            $response = Response::readPri($surveyId, $responseId);
            $mobileNum = $response['mobileNum'];

            if (!is_dir(dirname($_SERVER['SCRIPT_FILENAME']) . "/{$targetFolder}")) {
                mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . '/' . $targetFolder);
            }
            if (!is_dir(dirname($_SERVER['SCRIPT_FILENAME']) . "/{$targetFolder}/{$surveyId}")) {
                mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . "/{$targetFolder}/{$surveyId}");
            }
            if (!is_dir(dirname($_SERVER['SCRIPT_FILENAME']) . "/{$targetFolder}/{$surveyId}/{$mobileNum}")) {
                mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . "/{$targetFolder}/{$surveyId}/{$mobileNum}");
            }
            if (!empty($_FILES)) {
                $tempFile = $_FILES['Filedata']['tmp_name'];
                $targetPath = dirname($_SERVER['SCRIPT_FILENAME']) . "/{$targetFolder}/{$surveyId}/{$mobileNum}";
                $targetFile = rtrim($targetPath, '/') . '/' . $_FILES['Filedata']['name'];

                // Validate the file type
                $fileTypes = array('jpg', 'jpeg', 'gif', 'png', 'pdf', 'docx', 'doc'); // File extensions
                $fileParts = pathinfo($_FILES['Filedata']['name']);

                Response::updatePri($responseId, array('uploadFile' => $_FILES['Filedata']['name']));
                //Response::updatePrimary($surveyId, $mobileNum, );

                if (in_array($fileParts['extension'], $fileTypes)) {
                    move_uploaded_file($tempFile, $targetFile);
                    echo json_encode(array('success' => true, 'fileURL' => Config::get('base_url') . "{$targetFolder}/{$surveyId}/{$mobileNum}/" . $_FILES['Filedata']['name']));
                } else {
                    echo json_encode(array('success' => false, 'msg' => 'Invalid File Type'));
                }
            }
        }
    }

    public static function sendmail($responseId) {
        $surveyId = Response::getSurveyId($responseId);
        $personId = Response::getPersonId($responseId);
        $stage = (isset($_POST['stage'])) ? $_POST['stage'] : -1;
        $event = (isset($_POST['event'])) ? $_POST['event'] : -1;
        if ($surveyId && $stage >= 0) {
            NoteManager::addNote(User::getId(), $surveyId, $personId, 'Updated Stage: ' . Stage::getName($stage));
            Stage::updateResponse($responseId, $stage);
            //SurveyAdmin::updateResponseStage($surveyId, $responseId, $stage);
        }
        if ($surveyId && $event >= 0) {
            NoteManager::addNote(User::getId(), $surveyId, $personId, 'Updated Event: ' . EventManager::getName($event));
            EventManager::updateResponse($responseId, $event);
            //SurveyAdmin::updateResponseEvent($surveyId, $responseId, $event);
        }
        Survey::sendEmail(User::getId(), Response::getSurveyId($responseId), $responseId, $_POST['name'], $_POST['email'], $_POST['cc'], $_POST['message']);
        //    SurveyAdmin::sendEmail(User::getId(), SurveyAdmin::getSurveyIdFromResponse($responseId), $responseId, $_POST['name'], $_POST['email'], $_POST['cc'], $_POST['message']);
    }

    public static function beginVerify($surveyId) {

        $responses = Response::getLive($surveyId);

        file_put_contents('adv_response_data.json', json_encode($responses['responses']));

        echo json_encode(array('success' => true, 'responseCount' => count($responses['responses'])));
    }

    public static function compareVerify($surveyId) {

        //$responseLive = json_decode(file_get_contents('adv_response_data.json'),true);
        $liveResponses = json_decode(file_get_contents('adv_response_data.json'), true);

        $query = "select surveyResponseId from response WHERE surveyId={$surveyId}";
        $dbData = Config::get('db')->get_results($query);
        $checkArray = array();
        foreach ($dbData as $localResponse) {
            $checkArray[$localResponse['surveyResponseId']] = 1;
        }

        $missingLocal = 0;
        foreach ($liveResponses as $liveResponse) {
            if (!array_key_exists($liveResponse['_id'], $checkArray)) {
                $missingLocal++;
            }
        }
        echo json_encode(array('success' => true, 'missingLocal' => $missingLocal));
    }

}
