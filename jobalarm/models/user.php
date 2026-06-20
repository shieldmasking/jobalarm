<?php

/**
 * user short summary.
 *
 * user description.
 *
 * @version 1.0
 * @author Setzor
 */
class User
{
    public static $_loggedIn;
    public static $_data;
    
    public static function login($username,$password) {
        $query = "SELECT id FROM `account` WHERE (email = '{$username}') AND (password='".md5($password)."') AND (status>0)";
        //echo $query;
        $result = Config::get('db')->get_results($query);
        if (count($result) > 0) {
            $userId = $result[0]['id'];
            setcookie(Config::get('sitecookie'),$userId,time()+(86400 * 30));
            self::$_loggedIn = $userId;
            return $userId;
        } 
        self::logout();
        return false;
    }

    public static function quicklogin($username,$password) {
        $query = "SELECT id FROM `account` WHERE (email = '{$username}') AND (password='".$password."') AND (status>0)";
        //echo $query;
        $result = Config::get('db')->get_results($query);
        if (count($result) > 0) {
            $userId = $result[0]['id'];
            setcookie(Config::get('sitecookie'),$userId,time()+(86400 * 30),'/','.jobalarm.com');
            self::$_loggedIn = $userId;
            return $userId;
        } 
        self::logout();
        return false;
    }
    

    
    public static function checkLogin() {
        if (!self::$_loggedIn > 0) {
            self::$_loggedIn = isset($_COOKIE[Config::get('sitecookie')])?$_COOKIE[Config::get('sitecookie')] : false;
        }
        return self::$_loggedIn;
    }
    
    public static function logout() {
        setcookie(Config::get('sitecookie'),'',time()-3600);
        self::$_loggedIn = false;
        return true;
    }
    
    public static function get() {
        
    }
    
    
    public static function getFromEmail($email) {
        $query = "SELECT * FROM `account` WHERE `email` = '{$email}'";
        $dbData = Config::get('db')->get_results($query);
        if ($dbData && count($dbData) > 0) {
            return $dbData[0];            
        }
        return false;
    }
    
    public static function load($inId=0) {
        $id = max($inId,self::$_loggedIn,0);
        $query = "SELECT * FROM `account` WHERE `id`='{$id}' AND `status`>0";
        
        $result = Config::get('db')->get_results($query);
        if (count($result) > 0) {
            self::$_data = $result[0];
            return self::$_data;
        } 
        return false;
    }
    
    public static function getId() {
        return self::getData('id');
    }
    
    public static function isAdmin($inId = 0) {
        $id = max($inId,self::$_loggedIn,0);
        self::load($id);
        if (self::getData('role') >= 90) {
            return true;
        }
        return false;
    }
    
    public static function getData($var) {
        self::load();
        return (isset(self::$_data[$var])) ? self::$_data[$var] : '';        
    }
    
    public static function getList() {
        $query = "SELECT * FROM `account` WHERE `status`>0 ORDER BY lastName";
        
        $result = Config::get('db')->get_results($query);
        if (count($result) > 0) {
            return $result;
        } 
        return false;        
    }
    
    public static function create($data) {
        Config::get('db')->insert('account',$data);
        return true;
    }
    
    public static function update($data) {
        $where = array('id'=>self::$_loggedIn);
        Config::get('db')->update('account',$data,$where);
    }
    
    public static function remove($userId) {
        $data = array('status'=>0);
        $where = array(
            'id' => $userId            
            );
        Config::get('db')->update('user',$data,$where);
        return true;
    }
    
    public static function addSurveyAccess($userId,$surveyId) {
        if (is_array($surveyId)) {
            foreach($surveyId as $sid) {
                if (!self::canAccess($userId,$sid)) {
                    $data=array('userId'=>$userId,'surveyId'=>$sid,'access'=>1);
                    Config::get('db')->insert('useraccess',$data);
                }                
            }
        } else {
            if (!self::canAccess($userId,$surveyId)) {
                $data=array('userId'=>$userId,'surveyId'=>$surveyId,'access'=>1);
                Config::get('db')->insert('useraccess',$data);
            }
        }
    }
    
    public static function remSurveyAccess($userId,$surveyId) {
        if (is_array($surveyId)) {
            foreach($surveyId as $sid) {
                if (self::canAccess($userId,$sid)) {
                    $where=array('userId'=>$userId,'surveyId'=>$sid);
                    Config::get('db')->delete('useraccess',$where,1);
                }                                
            }
        } else {
            if (self::canAccess($userId,$surveyId)) {
                $where=array('userId'=>$userId,'surveyId'=>$surveyId);
                Config::get('db')->delete('useraccess',$where,1);
            }                
        }    
    }
    
    public static function remAllSurveyAccess($userId) {
        $where=array('userId'=>$userId);
        Config::get('db')->delete('useraccess',$where);
    }
    
    public static function canAccess($userId,$surveyId) {
        $query = "select surveyId from useraccess where userId={$userId} AND surveyId={$surveyId}";
        $dbData = Config::get('db')->get_results($query);        
        return (count($dbData) > 0);
    }

    public static function getSurveyAccessArray($userId) {
        $query = "select surveyId from useraccess where userId={$userId}";
        $dbData = Config::get('db')->get_results($query);        
        $accessArray = array();
        foreach($dbData as $survey) {
            $accessArray[] = $survey['surveyId'];
        }  
        return $accessArray;
    }
    
    public static function getAllSurveyAccessList($userId) {
        $query = "select s.surveyId,s.name from survey s where active>0 order by name asc";
        $dbData = Config::get('db')->get_results($query);        
        $accessArray = array();
        foreach($dbData as $survey) {
            $accessArray[] = array(
                'recid'=>$survey['surveyId'],
                'surveyId'=>$survey['surveyId'],
                'name'=>$survey['name'],
                'selected'=>'true'
                );
        }  
        return $accessArray;
    }

    public static function getDefaultAdminSurveyList($userId) {
        $query = "select surveyId,name from survey where active>0";
        $dbData = Config::get('db')->get_results($query);
        $accessArray = array();
        foreach($dbData as $survey) {
            $staticVars = Survey::getStaticVars($survey['surveyId']);
            if ($staticVars['tpl_defaultadmin'] == $userId) {
                $accessArray[] = array(
                    'recid' => $survey['surveyId'],
                    'surveyId' => $survey['surveyId'],
                    'name' => $survey['name'],
                    'selected' => 'true'
                );                
            }
        }
        
        return $accessArray;
    
    }
    
    public static function getSurveyAccessString($userId) {
        $accessArray = self::getSurveyAccessArray($userId);
        $accessList = (count($accessArray) > 0) ? implode(",",$accessArray) : "";
        return $accessList;
    }
    
    
    public static function getJob($postId) {
        $query = "select SQL_CALC_FOUND_ROWS j.*,j.id as recid,(select keyword from companykeyword kw where kw.id=j.keyword) as keywordtext from job j where id={$postId}";                    
        $dbData = Config::get('db')->get_results($query);
        if (count($dbData) > 0) {
            return $dbData[0];
        }
        
        return false;
    }
    
    public static function getJobList($userId,$archived=0,$offset=null,$limit=null,&$total=null,$viewcompany) {
        $candidate_order = '';
        if (isset($_REQUEST['sort']) && count($_REQUEST['sort'])>0) {
            if (isset($_REQUEST['sort'][0]['field']) && $_REQUEST['sort'][0]['field'] == 'candidates') {
                $candidate_order = ",j.num_candidates ".$_REQUEST['sort'][0]['direction'];
            }
        }
        
        if ($offset >= 0) {
            $limitadd = " limit {$offset},{$limit}";
        }
        if ($userId > 0) {
            if ($viewcompany == 1) {
                $query = "select SQL_CALC_FOUND_ROWS j.*,j.id as recid,(select keyword from companykeyword kw where kw.id=j.keyword) as keywordtext from job j where userId={$userId} and active=1 and archived={$archived} order by case when keywordtext in ('CDL','SAFETY','CHEMIST') then -1 else 1 end {$candidate_order},j.postDate desc {$limitadd}";            
            } else {
                $companyId = self::getCompanyId(self::$_loggedIn);
                $subquery = "select id from user where companyId={$companyId}";
                $query = "select SQL_CALC_FOUND_ROWS j.*,j.id as recid,(select keyword from companykeyword kw where kw.id=j.keyword) as keywordtext from job j where userId IN ($subquery) and active=1 and archived={$archived} order by case when keywordtext in ('CDL','SAFETY','CHEMIST') then -1 else 1 end {$candidate_order}, j.postDate desc {$limitadd}";            
            }
            //echo $query;
        } else {
            $query = "select SQL_CALC_FOUND_ROWS j.*,j.id as recid,(select keyword from companykeyword kw where kw.id=j.keyword) as keywordtext from job j where active=1 and adminarchived={$archived} order by case when keywordtext in ('CDL','SAFETY','CHEMIST') then -1 else 1 end {$candidate_order}, j.postDate desc {$limitadd}";
        }
        //echo $query;
        $dbData = Config::get('db')->get_results($query);
        $foundrowsquery = "SELECT FOUND_ROWS() as count";
        $countData = Config::get('db')->get_results($foundrowsquery);        
        $total = $countData[0]['count'];
        return $dbData;
        
    }
    
    public static function getJobListTest($userId,$archived=0,$offset=null,$limit=null,&$total=null,$viewcompany) {
        if ($offset >= 0) {
            $limitadd = " limit {$offset},{$limit}";
        }
        if ($userId > 0) {
            if ($viewcompany == 1) {
                $query = "select SQL_CALC_FOUND_ROWS j.*,j.id as recid,(select keyword from companykeyword kw where kw.id=j.keyword) as keywordtext from job j where userId={$userId} and active=1 and archived={$archived} order by j.postDate desc {$limitadd}";            
            } else {
                $companyId = self::getCompanyId(self::$_loggedIn);
                $subquery = "select id from user where companyId={$companyId}";
                $query = "select SQL_CALC_FOUND_ROWS j.*,j.id as recid,(select keyword from companykeyword kw where kw.id=j.keyword) as keywordtext from job j where userId IN ($subquery) and active=1 and archived={$archived} order by j.postDate desc {$limitadd}";            
            }
            //echo $query;
        } else {
            $query = "select SQL_CALC_FOUND_ROWS j.*,j.id as recid,(select keyword from companykeyword kw where kw.id=j.keyword) as keywordtext from job j where active=1 and adminarchived={$archived} order by j.postDate desc {$limitadd}";
        }
        echo "<br />".$query."<br />";
        $dbData = Config::get('db')->get_results($query);
        $foundrowsquery = "SELECT FOUND_ROWS() as count";
        $countData = Config::get('db')->get_results($foundrowsquery);        
        $total = $countData[0]['count'];
        return $dbData;
        
    }
    
    public static function getCompanyId($userId) {
        $query = "select companyId from account where id={$userId}";
        $dbData = Config::get('db')->get_results($query);        
        if ($dbData && count($dbData) > 0) return $dbData[0]['companyId'];
        return 0;
        
    }
    
    public static function getEmail($userId) {
        $query = "select email from account where id={$userId}";
        $dbData = Config::get('db')->get_results($query);        
        if ($dbData && count($dbData) > 0) return $dbData[0]['email'];
        return '';
        
    }
    
}
