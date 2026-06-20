<?php

function namesort($a, $b) {
    if ($a['name'] == $b['name']) {
        return 0;
    }
    return ($a['name'] < $b['name']) ? -1 : 1;
}

class Surveys {

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
        Config::push('scripts', 'views/surveys/surveys.js');
        Config::set('mainmenu', true);
        Config::set('sysblock', true);
        require_once('views/surveys/surveys.php');
    }

    public static function run() {
        self::doView();
    }

    public static function getAllLive() {
        $dataArray = array();
        $surveys = Survey::getAllLiveSurveys();
        foreach ($surveys as $survey) {

            $dataArray[] = array(
                'recid' => $survey->getId(),
                'surveyid' => $survey->getId(),
                'sname' => $survey->getName(),
                'updated' => $survey->getLastUpdate(),
                'responses' => $survey->getResponseCount(),
                'smsresponses' => '0',
                'totalresponses' => $survey->getResponseCount(),
                'actions' => '<button>View</button> <button>Download Report</button>'
            );
        }
        $outArray = array(
            'status' => 'success',
            'total' => count($dataArray),
            'records' => $dataArray
        );
        echo json_encode($outArray);
    }

    public static function getList() {

    }
    
    public static function getAll($isAdmin=false) {
        Survey::getList($isAdmin);
    }

    public static function getAllPerson($personId = 0) {

    }

    public static function getQuestions($surveyId) {
        Survey::load($surveyId);
        $questions = Survey::$_questions;
        $records = array();
        $i = 0;
        $records[] = array(
            'recid'=>$i++,
            'sysid'=>'stagename',
            'qtype'=>'string',
            'name'=>'Stage'
            );
        $records[] = array(
            'recid'=>$i++,
            'sysid'=>'stagedate',
            'qtype'=>'string',
            'name'=>'Stage Date'
            );
        $records[] = array(
            'recid'=>$i++,
            'sysid'=>'eventname',
            'qtype'=>'string',
            'name'=>'Event'
            );
        $records[] = array(
            'recid'=>$i++,
            'sysid'=>'eventdate',
            'qtype'=>'string',
            'name'=>'Event Date'
            );
        foreach ($questions as $question) {
            $records[] = array(
                'recid' => $i++,
                'sysid' => $question->_id,
                'qtype' => $question->_type,
                'name' => $question->_label
            );
        }

        $outData = array(
            'status' => 'success',
            'total' => count($questions),
            'records' => $records
        );
        echo json_encode($outData);        
    }

    public static function getSurveyViewColumns($surveyId) {
        Survey::load($surveyId);
        $surveyviewcfg = Survey::$_editView;

        $records = array('status'=>'success','total'=>0,'records'=>array());
        if (count($surveyviewcfg) > 0) {
            $records = array(
                'status' => 'success',
                'total' => max(count($surveyviewcfg),0),
                'records' => $surveyviewcfg
            );        
        }
        echo json_encode($records);   
    }

    public static function getViewConfig($surveyId) {

    }

    public static function getSurveyConfigFields($surveyId) {
        $cmd = (isset($_REQUEST['cmd'])) ? $_REQUEST['cmd'] : '';
        switch ($cmd) {
            case 'save-record':
                break;
            case 'get-record':
            default:
                break;
        }
    }

    public static function cat($surveyId) {

    }
    
    public static function getSurveyDetailsFields($surveyId) {
        $cmd = (isset($_REQUEST['cmd'])) ? $_REQUEST['cmd'] : '';
        switch ($cmd) {
            case 'save-record':
                break;
            case 'get-record':
            default:
                break;
        }
    }

    public static function getSurveyConfigOptions($surveyId) {
    
    }

    public static function getGridViewColumns($surveyId) {
        Survey::load($surveyId);
        $displaycfg = Survey::$_displayView;

        $records = array('status'=>'success','total'=>0,'records'=>array());
        if (count($displaycfg) > 0) {
            $records = array(
                'status' => 'success',
                'total' => max(count($displaycfg),0),
                'records' => $displaycfg
            );        
        }
        echo json_encode($records);    
    }

    public static function getSurveyFiltersColumns($surveyId) {
        Survey::load($surveyId);
        $filtercfg = Survey::$_filtersView;

        $records = array('status'=>'success','total'=>0,'records'=>array());
        if (count($filtercfg) > 0) {
            $records = array(
                'status' => 'success',
                'total' => max(count($filtercfg),0),
                'records' => $filtercfg
            );        
        }
        echo json_encode($records);   
    }

    public static function getSMSSurveyColumns($surveyId) {
        Survey::load($surveyId);
        $smssurveycfg = Survey::$_smsView;

        $records = array('status'=>'success','total'=>0,'records'=>array());
        if (count($smssurveycfg) > 0) {
            $records = array(
                'status' => 'success',
                'total' => max(count($smssurveycfg),0),
                'records' => $smssurveycfg
            );        
        }
        echo json_encode($records);   
    }

    public static function getViewColumnConfig($surveyId) {
        Survey::load($surveyId);
        $displaycfg = (array) Survey::$_displayView;
        $columns = array();
        $columns[] = array(
                    'field' => 'status',
                    'caption' => '',
                    'size' => '23px',
                    'resizable' => false,
                    'sortable' => false
            
            );
        if (count($displaycfg) > 0) {
            foreach ($displaycfg as $column) {
                $columns[] = array(
                    'field' => 'Display' . $column['id'],
                    'caption' => $column['displayname'],
                    'size' => '100px',
                    'resizable' => true,
                    'sortable' => true
                );
            }
        }
        echo json_encode($columns);
    }

    public static function getFilters($surveyId) {
        echo Survey::getFilters($surveyId);
    }

    public static function save($surveyId, $whichview) {
        $gridViewConfig = isset($_REQUEST['gridViewConfig']) ? json_decode(stripslashes($_REQUEST['gridViewConfig'])) : array();
        foreach ($gridViewConfig as $field) {
            $field->displayname = $field->text;
            $field->id = $field->sysid;
            unset($field->text);
        }
        $tempArray = array('totalCount' => count($gridViewConfig), 'records' => $gridViewConfig);
        switch ($whichview) {
            case 'gridview':
                $saveArray = array('displayView' => Config::get('db')->filter(json_encode($tempArray)));
                break;
            case 'surveyview':
                $saveArray = array('editView' => Config::get('db')->filter(json_encode($tempArray)));
                break;
            case 'surveyfilters':
                $saveArray = array('filtersView' => Config::get('db')->filter(json_encode($tempArray)));
                break;
            case 'smssurvey':
                $saveArray = array('smsView' => Config::get('db')->filter(json_encode($tempArray)));
                break;
        }
        Survey::update($surveyId,$saveArray);
        echo json_encode(array('success' => true));
    }

    public static function addStage($surveyId) {
        $stageName = $_REQUEST['stageName'];
        $userId = Config::get('loggedIn');
        $data = array(
            'companyId' => 0,
            'userId' => $userId,
            'surveyId' => $surveyId,
            'name' => $stageName
        );
        $stageId = Stage::create($data);
        echo json_encode(array('status' => 'success', 'newid' => $stageId));
    }

    public static function getStageList($surveyId) {
        $stages = Stage::getAll($surveyId);
        $dataArray = array();
        foreach ($stages as $stage) {
            $dataArray[] = array(
                'id' => $stage['id'],
                'text' => $stage['name']
            );
        }
        echo json_encode(array('items' => $dataArray));
    }

    public static function getStages($surveyId) {
        $cmd = (isset($_REQUEST['cmd'])) ? $_REQUEST['cmd'] : '';
        switch ($cmd) {
            case 'save-records':
                $records = (isset($_REQUEST['changed'])) ? $_REQUEST['changed'] : array();
                if (count($records) > 0) {
                    foreach ($records as $record) {
                        Stage::update($record['recid'], array('name' => $record['name']));
                    }
                }
                echo '{"status":"success","total":0,"records":[]}';
                break;
            case 'delete-records':
                $records = (isset($_REQUEST['selected'])) ? $_REQUEST['selected'] : array();
                if (count($records) > 0) {
                    foreach ($records as $record) {
                        Stage::delete($record);
                    }
                }
                echo '{"status":"success","total":0,"records":[]}';
                break;
            case 'get-records':
            default:
                $stages = Stage::getAll($surveyId);
                $dataArray = array();
                foreach ($stages as $stage) {
                    $dataArray[] = array(
                        'recid' => $stage['id'],
                        'name' => $stage['name']
                    );
                }
                $outArray = array(
                    'status' => 'success',
                    'total' => count($stages),
                    'records' => $dataArray
                );
                echo json_encode($outArray);
                break;
        }
    }

    public static function addEvent($surveyId) {
        $eventName = $_REQUEST['eventName'];
        $data = array(
            'userId' => Config::get('loggedIn'),
            'companyId' => 0,
            'surveyId' => $surveyId,
            'name' => $eventName
        );
        $eventId = EventManager::create($data);
        echo json_encode(array('status' => 'success', 'newid' => $eventId));
    }

    public static function getEventList($surveyId) {
        $events = EventManager::getAll($surveyId);
        $dataArray = array();
        foreach ($events as $event) {
            $dataArray[] = array(
                'id' => $event['id'],
                'text' => $event['name']
            );
        }
        echo json_encode(array('items' => $dataArray));
    }

    public static function getEvents($surveyId) {
        $cmd = (isset($_REQUEST['cmd'])) ? $_REQUEST['cmd'] : '';
        switch ($cmd) {
            case 'save-records':
                $records = (isset($_REQUEST['changed'])) ? $_REQUEST['changed'] : array();
                if (count($records) > 0) {
                    foreach ($records as $record) {
                        EventManager::update($record['recid'], array('name' => $record['name']));
                    }
                }
                echo '{"status":"success","total":0,"records":[]}';
                break;
            case 'delete-records':
                $records = (isset($_REQUEST['selected'])) ? $_REQUEST['selected'] : array();
                if (count($records) > 0) {
                    foreach ($records as $record) {
                        EventManager::delete($record);
                    }
                }
                echo '{"status":"success","total":0,"records":[]}';
                break;
            case 'get-records':
            default:
                $events = EventManager::getAll($surveyId);
                $dataArray = array();
                foreach ($events as $event) {
                    $dataArray[] = array(
                        'recid' => $event['id'],
                        'name' => $event['name']
                    );
                }
                $outArray = array(
                    'status' => 'success',
                    'total' => count($events),
                    'records' => $dataArray
                );
                echo json_encode($outArray);
                break;
        }
    }
    
    public static function getConfigOptions($surveyId) {
        if(!Survey::$_loaded) Survey::load($surveyId);
        $questions = Survey::$_questions;
        $records = array();
        $i = 0;
        foreach ($questions as $question) {
            $records[] = array(
                'id' => $question->_id,
                'text' => $question->_label
            );
        }

        $out = array('items' => $records);

        echo json_encode($out);
    }

    
    public static function getConfigFields($surveyId) {
        Survey::getConfigFields($surveyId);
    }

    public static function setConfigFields($surveyId) {
        Survey::setConfigFields($surveyId);
    }
    
    public static function getDetailsFields($surveyId) {
        Survey::getDetailsFields($surveyId);
    }

    public static function setDetailsFields($surveyId) {
        Survey::setDetailsFields($surveyId);
    }
    
    public static function getEmpRefFields($surveyId) {
        Survey::getEmpRefFields($surveyId);
    }

    public static function setEmpRefFields($surveyId) {
        Survey::setEmpRefFields($surveyId);
    }
    
    public static function getEmpRefs($surveyId) {
        Survey::getEmpRefs($surveyId);
    }
    
    public static function uploadFile() {
        $targetFolder = 'dat/temp'; // Relative to the root
        if (isset($_REQUEST['surveyId'])) {
            $surveyId = $_REQUEST['surveyId'];
           
            if (!is_dir(dirname($_SERVER['SCRIPT_FILENAME']) . "/{$targetFolder}")) {
                mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . '/' . $targetFolder);
            }
            if (!is_dir(dirname($_SERVER['SCRIPT_FILENAME']) . "/{$targetFolder}/{$surveyId}")) {
                mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . "/{$targetFolder}/{$surveyId}");
            }

            if (!empty($_FILES)) {
                $tempFile = $_FILES['Filedata']['tmp_name'];
                $targetPath = dirname($_SERVER['SCRIPT_FILENAME']) . "/{$targetFolder}/{$surveyId}";
                $targetFile = rtrim($targetPath, '/') . '/' . $_FILES['Filedata']['name'];

                // Validate the file type
                $fileTypes = array('csv'); // File extensions
                $fileParts = pathinfo($_FILES['Filedata']['name']);

                $survey = Survey::load($surveyId);
                
                if (in_array($fileParts['extension'], $fileTypes)) {
                    move_uploaded_file($tempFile, $targetFile);
                    $row = 1;
                    if (($handle = fopen($targetFile, "r")) !== FALSE) {
                        $data = fgetcsv($handle,10000,',');
                        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {                           
                            $row++;
                            $mobileNum = (isset($data[0])) ? trim($data[0]) : '';
                            if (strlen($mobileNum) == 10) {

                                $lastName = (isset($data[1])) ? trim($data[1]) : '';
                                $firstName = (isset($data[2])) ? trim($data[2]) : '';
                                $email = (isset($data[3])) ? trim($data[3]) : '';
                                $zipCode = (isset($data[4])) ? trim($data[4]) : '';  
                                $responseData = array('mobileNum'=>$mobileNum,'firstName'=>$firstName,'lastName'=>$lastName,'email'=>$email,'zipCode'=>$zipCode);
                                $response = Response::generateBlank($surveyId,$responseData);
                                $responseId = Response::add($surveyId,$response,false);
                                $personId = Response::getPersonId($responseId);
                                Person::update($personId,array('optOut' => true));
                            }
                        }
                        fclose($handle);
                    }
                    
                    echo json_encode(array('success' => true, 'fileURL' => $_FILES['Filedata']['name']));
                } else {
                    echo json_encode(array('success' => false, 'msg' => 'Invalid File Type'));
                }
            }
        }        
    }
    
}
