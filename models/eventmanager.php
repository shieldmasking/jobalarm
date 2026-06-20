<?php

class EventManager {
    public static function getAll($surveyId = null){
        $whereAdd = ($surveyId) ? " AND surveyId={$surveyId}" : '';
        $userId = Config::get('loggedIn');
        $query = "SELECT * FROM event WHERE userId={$userId} AND active>0 {$whereAdd} ORDER BY id ASC";
        $dbData = Config::get('db')->get_results($query);
        return $dbData;
    }
    
    public static function getLookup($surveyId = null) {
        $events = Config::get('db')->get_results('select * from event');
        $lookup = array();
        if ($events && count($events) > 0) {
            foreach($events as $event) {
                $lookup[$event['id']] = $event['name'];
            }
        }
        return $lookup;
    }
    
    public static function getName($eventId) {
        $query = "SELECT name FROM event WHERE id={$eventId}";
        $dbData = Config::get('db')->get_results($query);
        if (count($dbData) > 0) {
            return $dbData[0]['name'];
        }
        return 'None';
    }
    
    public static function updateResponse($responseId,$inEventId) {
        $eventId = self::exists($responseId);
        if ($inEventId == 0) {
            if ($eventId) {
                Config::get('db')->delete('responseevent',array('id'=>$eventId),1);
            }
            return 0;
        } 
        if ($eventId) {
            $data = array(
                'eventId'=>$inEventId,
                'eventDate'=>date('Y-m-d H:i:s')
                );
            $where = array('id'=>$eventId);
            Config::get('db')->update('responseevent',$data,$where,1);
        } else {
            $data = array(
                'responseId'=>$responseId,
                'userId'=>Config::get('loggedIn'),
                'eventId'=>$inEventId,
                'eventDate'=>($inEventId > 0) ? date('Y-m-d H:i:s') : null
                );
            Config::get('db')->insert('responseevent',$data);
            $eventId = Config::get('db')->lastid();
        }
        return $eventId;
    }
    
    public static function exists($responseId) {
        $userId = Config::get('loggedIn');
        $query = "SELECT id FROM responseevent WHERE userId={$userId} AND responseId={$responseId}";
        $dbData = Config::get('db')->get_results($query);
        if (count($dbData) > 0) {
            return $dbData[0]['id'];
        }
        return false;        
    }
    
    public static function create($data) {
        Config::get('db')->insert('event',$data);
        return Config::get('db')->lastid();
    }
    
    public static function update($eventId,$data) {
        $where = array('id'=>$eventId);
        Config::get('db')->update('event',$data,$where);
        return true;
    }
    
    public static function delete($eventId) {
        $where = array('id'=>$eventId);
        $data = array('active'=>0);
        Config::get('db')->update('event',$data,$where);
        return true;
    }
    
}