<?php

class Company {

    public static function create($data) {
        Config::get('db')->insert('company', $data);
        return Config::get('db')->lastid();
    }

    public static function update($companyId, $data) {
        $where = array('id'=>$companyId);
        Config::get('db')->update('company',$data,$where);
        return true;
    }

    public static function read($companyId = NULL) {

        $whereAdd = '';
        if ($companyId) {
            $whereAdd = " AND id={$companyId} ";
        }
        $query = "SELECT * FROM `company` WHERE `active`>0 {$whereAdd} ORDER BY name ASC";

        $dbData = Config::get('db')->get_results($query);
        if (count($dbData) > 0) {
            if ($companyId) {
                return $dbData[0];
            } else {
                return $dbData;
            }
        }
        return false;
    }

    public static function getList($search = NULL) {
        $whereadd = '';
        if ($search && count($search) > 0) {
            foreach ($search as $data) {
                switch ($data['field']) {
                    case 'companyname':
                        $whereadd .= " AND name like '{$data['value']}%'";
                        break;
                    case 'companydesc':
                        $whereadd .= " AND description like '{$data['value']}%'";
                        break;
                    default:
                        break;
                }
            }
        }

        $query = "SELECT * FROM `company` WHERE `active`>0 {$whereadd} ORDER BY name ASC";

        $result = Config::get('db')->get_results($query);
        if (count($result) > 0) {
            return $result;
        }
        return false;
    }

    public static function delete($companyId) {
        $where = array('id' => $companyId);
        $data = array('active' => 0);
        Config::get('db')->update('company', $data, $where);
        return true;
    }

    public static function addSurveyAccess($companyId, $surveyId) {
        if (is_array($surveyId)) {
            foreach ($surveyId as $sid) {
                if (!self::canAccess($companyId, $sid)) {
                    $data = array('companyId' => $companyId, 'surveyId' => $sid, 'access' => 1);
                    Config::get('db')->insert('companyaccess', $data);
                }
            }
        } else {
            if (!self::canAccess($companyId, $surveyId)) {
                $data = array('companyId' => $companyId, 'surveyId' => $surveyId, 'access' => 1);
                Config::get('db')->insert('companyaccess', $data);
            }
        }
    }

    public static function addSurveyAdmin($companyId, $surveyId) {
        if (is_array($surveyId)) {
            foreach ($surveyId as $sid) {
                if (!self::canAccessAdmin($companyId, $sid)) {
                    $data = array('companyId' => $companyId, 'surveyId' => $sid, 'access' => 1);
                    Config::get('db')->insert('companyadmin', $data);
                }
            }
        } else {
            if (!self::canAccessAdmin($companyId, $surveyId)) {
                $data = array('companyId' => $companyId, 'surveyId' => $surveyId, 'access' => 1);
                Config::get('db')->insert('companyadmin', $data);
            }
        }
    }
    
    public static function remSurveyAccess($companyId, $surveyId) {
        if (is_array($surveyId)) {
            foreach ($surveyId as $sid) {
                if (self::canAccess($companyId, $sid)) {
                    $where = array('companyId' => $companyId, 'surveyId' => $sid);
                    Config::get('db')->delete('companyaccess', $where, 1);
                }
            }
        } else {
            if (self::canAccess($companyId, $surveyId)) {
                $where = array('companyId' => $companyId, 'surveyId' => $surveyId);
                Config::get('db')->delete('companyaccess', $where, 1);
            }
        }
    }

    public static function remAllSurveyAccess($companyId) {
        $where = array('companyId' => $companyId);
        Config::get('db')->delete('companyaccess', $where);
    }

    public static function canAccess($companyId, $surveyId) {
        $query = "select surveyId from companyaccess where companyId={$companyId} AND surveyId={$surveyId}";
        $dbData = Config::get('db')->get_results($query);
        return (count($dbData) > 0);
    }

    public static function getSurveyAccessArray($companyId) {
        $query = "select surveyId from companyaccess where companyId={$companyId}";
        $dbData = Config::get('db')->get_results($query);
        $accessArray = array();
        foreach ($dbData as $survey) {
            $accessArray[] = $survey['surveyId'];
        }
        return $accessArray;
    }

    
    public static function remAllSurveyAdmin($companyId) {
        $where = array('companyId' => $companyId);
        Config::get('db')->delete('companyadmin', $where);
    }

    public static function canAccessAdmin($companyId, $surveyId) {
        $query = "select surveyId from companyadmin where companyId={$companyId} AND surveyId={$surveyId}";
        $dbData = Config::get('db')->get_results($query);
        return (count($dbData) > 0);
    }
    
    public static function getSurveyAdminAccess($companyId) {
        $query = "select surveyId from companyadmin where companyId={$companyId}";
        $dbData = Config::get('db')->get_results($query);
        $accessArray = array();
        foreach ($dbData as $survey) {
            $accessArray[] = $survey['surveyId'];
        }
        return $accessArray;
    }

    
    public static function getAllSurveyAccessList($companyId, $fullList = 0) {
        $sqlAdd = '';
        if ($fullList == 0) {
            $sqlAdd = "and s.surveyId in (select surveyId from companyaccess where companyId={$companyId})";
        }
        $query = "select s.surveyId,s.name from survey s where active>0 {$sqlAdd} order by name asc";
        
        $dbData = Config::get('db')->get_results($query);
        $accessArray = array();
        foreach ($dbData as $survey) {
            if (User::getData('companyId') == 4 && $survey['name'] == 'Safety') { $survey['name'] = 'CH'; }
            $accessArray[] = array(
                'recid' => $survey['surveyId'],
                'surveyId' => $survey['surveyId'],
                'name' => $survey['name'],
                'selected' => 'true'
            );
        }
        return $accessArray;
    }

    public static function getAllSurveyAdminList($companyId, $fullList = 0) {
        $sqlAdd = '';
        if ($fullList == 0) {
            $sqlAdd = "and s.surveyId in (select surveyId from companyadmin where companyId={$companyId})";
        }
        $query = "select s.surveyId,s.name from survey s where active>0 {$sqlAdd} order by name asc";
        echo $query;
        $dbData = Config::get('db')->get_results($query);
        $accessArray = array();
        foreach ($dbData as $survey) {
            if (User::getData('companyId') == 4 && $survey['name'] == 'Safety') { $survey['name'] = 'CH'; }
            $accessArray[] = array(
                'recid' => $survey['surveyId'],
                'surveyId' => $survey['surveyId'],
                'name' => $survey['name'],
                'selected' => 'true'
            );
        }
        return $accessArray;
    }

    
    
    public static function getSurveyAccessString($companyId) {
        $accessArray = self::getSurveyAccessArray($companyId);
        $accessList = (count($accessArray) > 0) ? implode(",", $accessArray) : "";
        return $accessList;
    }

    public static function getKeywords($companyId) {

        $query = "SELECT *,companykeyword.id as recid from companykeyword where companyId={$companyId} and active>0";
        $dbData = Config::get('db')->get_results($query);

        return $dbData;
    }

    public static function addKeyword($companyId, $keyword) {

        $data = array(
            'keyword' => $keyword,
            'companyId' => $companyId
        );
        Config::get('db')->insert('companykeyword', $data);
        return Config::get('db')->lastid();
    }

    public static function toggleKeyword($keywordId) {

        $where = array('id' => $keywordId);
        $data = array('active' => 0);
        Config::get('db')->update('companykeyword', $data, $where);
        return true;
    }
    
    public static function getJobKeyword($jobId) {
        
        $query = "select ck.keyword from companykeyword ck where ck.id=(select j.keyword from job j where j.id={$jobId})";
        $dbData = Config::get('db')->get_results($query);
        if (count($dbData) > 0 ){
            return $dbData[0]['keyword'];
        } else {
            return "jobs";
        }
    }
    
    public static function getMaxPostings($companyId) {
    
        $query = "select maxpostings from company where id={$companyId}";
        $dbData = Config::get('db')->get_results($query);
        if (count($dbData) > 0)  { 
            return $dbData[0]['maxpostings']; 
        }
        return 0;      
        
    }
    
    public static function getNumJobPostings($companyId) {
    
        $query = "select count(id) as numpostings from job where companyId={$companyId}";
        $dbData = Config::get('db')->get_results($query);
        if (count($dbData) > 0)  { 
            return $dbData[0]['numpostings']; 
        }
        return 0;      
        
        
    }
    
    public static function getJob($jobId) {
        $query = "select * from job where id={$jobId}";
        $dbData = Config::get('db')->get_results($query);
        if (count($dbData) > 0) {
            return $dbData[0];        
        }
        return null;
    }
    
    public static function getJobCandidateCount($jobId) {
        $job = self::getJob($jobId);
        $surveyId = $job['surveyId'];
        $staticVars = Survey::getStaticVars($surveyId);
        $postVar = $staticVars['tpl_postid'];
        $query = "select count(*) as candidates from survey{$surveyId} where TMPTBL{$postVar} = {$jobId}";
        $dbData = Config::get('db')->get_results($query);
        if (count($dbData) > 0) {
            return $dbData[0]['candidates'];
        }
        return 0;    
    }
    
    public static function getDefaultKeyword($companyId) {
        $query = "select defaultkeyword from company where id={$companyId}";
        $dbData = Config::get('db')->get_results($query);
        if (count($dbData) > 0)  { 
            return $dbData[0]['defaultkeyword']; 
        }
        return 0;     
    }
    
    public static function setDefaultKeyword($companyId,$keywordId) {
        $data = array('defaultkeyword'=>$keywordId);
        $where = array('id'=>$companyId);
        Config::get('db')->update('company',$data,$where,1);
        return true;
    }
    
    
    public static function getSMSReport($companyId,$startDate,$endDate) {
        $query = "
            SELECT 
            s.messageDate,
            s.mobileNum,
            sv.name as surveyName,
            CONCAT(p.firstName,' ',p.lastName) as contactName,
            s.message,
            CONCAT(u.firstName,' ',u.lastName) as userName
            FROM sms_history s
            LEFT JOIN survey sv on sv.surveyId=s.surveyId
            LEFT JOIN user u on u.id = s.userId
            LEFT JOIN people p on p.id=s.peopleId
            WHERE (s.companyId = {$companyId} or s.userId in (select id from user where companyId={$companyId}))
            AND s.messageDate >= '{$startDate} 00:00:00'
            AND s.messageDate <= '{$endDate} 23:59:59'
            AND (s.type = 2 or s.type = 3) 
            AND s.isReply = 0
        ";
        
        $dbData = Config::get('db')->get_results($query);
        return $dbData;
        
    }
    
}
