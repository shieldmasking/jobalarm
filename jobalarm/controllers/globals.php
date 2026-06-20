<?php

class Globals {

    public static function doView() {
        if (!User::checkLogin()) {
            header('location: home');
        }
        Config::push('scripts', 'views/shared/sharedfunctions.js');
        Config::push('scripts', 'views/globals/globals.js');
        Config::push('jsvars',array('accountId'=>Config::get('loggedIn')));
        if (Router::getGetVar('z')) {
            Config::push('jsvars',array('refZip'=>Router::getGetVar('z')));
        }
        if (Router::getGetVar('b')) {
            Config::push('jsvars',array('refBrand'=>Router::getGetVar('b')));
        }

        Config::set('mainmenu', true);
        Config::set('sysblock', true);
        require_once('views/globals/globals.php');
    }

    public static function getStages() {
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
                $stages = Stage::getAll(0);
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

    public static function getStageList() {
        $stages = Stage::getAll(0);
        $dataArray = array();
        foreach ($stages as $stage) {
            $dataArray[] = array(
                'id' => $stage['id'],
                'text' => $stage['name']
            );
        }
        echo json_encode(array('items' => $dataArray));
    }

    public static function getEventList() {
        $events = EventManager::getAll(0);
        $dataArray = array();
        foreach ($events as $event) {
            $dataArray[] = array(
                'id' => $event['id'],
                'text' => $event['name']
            );
        }
        echo json_encode(array('items' => $dataArray));
    }

    public static function addStage() {
        $userId = Config::get('loggedIn');
        $stageName = $_REQUEST['stageName'];
        $data = array(
            'companyId' => 0,
            'surveyId' => 0,
            'userId' => $userId,
            'name' => $stageName
        );
        $stageId = Stage::create($data);
        echo json_encode(array('status' => 'success', 'newid' => $stageId));
    }

    public static function getEvents() {
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
                $events = EventManager::getAll(0);
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

    public static function addEvent() {
        $userId = Config::get('loggedIn');
        $eventName = $_REQUEST['eventName'];
        $data = array(
            'companyId' => 0,
            'surveyId' => 0,
            'userId' => $userId,
            'name' => $eventName
        );
        $eventId = EventManager::create($data);
        echo json_encode(array('status' => 'success', 'newid' => $eventId));
    }

    public static function run() {
        self::doView();
    }
}
