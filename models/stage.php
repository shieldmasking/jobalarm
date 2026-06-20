<?php

/*
 * Left Msg, Contacted, Interviewed, Submitted, Offer Extended, Hired, Rejected
 */



class Stage {
    public static $stageList = array(
        '1'=>'Left Msg',
        '2'=>'Contacted',
        '3'=>'Interviewed',
        '4'=>'Submitted',
        '5'=>'Offer Extended',
        '6'=>'Hired',
        '7'=>'Rejected',
        '999' => 'Removed'
    );
    public static function getAll($surveyId = null){        
        //$whereAdd = ($surveyId) ? " AND surveyId={$surveyId}" : '';
        //$userId = Config::get('loggedIn');
        //$query = "SELECT * FROM stage WHERE userId={$userId} AND active>0 {$whereAdd} ORDER BY id ASC";
        //$dbData = Config::get('db')->get_results($query);
        //return $dbData;
        $outData = array();
        foreach(self::$stageList as $k=>$v) {
            $outData[] = array('id'=>$k,'name'=>$v);
        }
        return $outData;
    }
    
    public static function getLookup($surveyId = null) {
        //$stages = Config::get('db')->get_results('select * from stage');
        //$lookup = array();
        //if ($stages && count($stages) > 0) {
        //    foreach($stages as $stage) {
        //        $lookup[$stage['id']] = $stage['name'];
        //    }
        //}
        return self::$stageList;
    }
    
    public static function getName($stageId) {
        //$query = "SELECT name FROM stage WHERE id={$stageId}";
        //$dbData = Config::get('db')->get_results($query);
        //if (count($dbData) > 0) {
        //    return $dbData[0]['name'];
        //}
        if (isset(self::$stageList[$stageId])) {
            return self::$stageList[$stageId];
        }
        return 'None';
    }
    
    public static function updateResponse($responseId,$inStageId) {
        $stageId = self::exists($responseId);
        if ($inStageId == 0) {
            if ($stageId) {
                Config::get('db')->delete('responsestage',array('id'=>$stageId),1);
            }
            return 0;
        } 
        if ($stageId) {
            $data = array(
                'stageId'=>$inStageId,
                'stageDate'=>($inStageId > 0) ? date('Y-m-d H:i:s') : null
                );
            $where = array('id'=>$stageId);
            Config::get('db')->update('responsestage',$data,$where,1);
        } else {
            $data = array(
                'responseId'=>$responseId,
                'userId'=>Config::get('loggedIn'),
                'stageId'=>$inStageId,
                'stageDate'=>($inStageId > 0) ? date('Y-m-d H:i:s') : null
                );
            Config::get('db')->insert('responsestage',$data);
            $stageId = Config::get('db')->lastid();
        }
        return $stageId;
    }
    
    public static function exists($responseId) {
        $userId = Config::get('loggedIn');
        $query = "SELECT id FROM responsestage WHERE userId={$userId} AND responseId={$responseId}";
        $dbData = Config::get('db')->get_results($query);
        if (count($dbData) > 0) {
            return $dbData[0]['id'];
        }
        return false;        
    }
    
    public static function create($data) {
        Config::get('db')->insert('stage',$data);
        return Config::get('db')->lastid();
    }
    
    public static function update($stageId,$data) {
        $where = array('id'=>$stageId);
        Config::get('db')->update('stage',$data,$where);
        return true;
    }
    
    public static function delete($stageId) {
        $where = array('id'=>$stageId);
        $data = array('active'=>0);
        Config::get('db')->update('stage',$data,$where);
        return true;
    }
    
}