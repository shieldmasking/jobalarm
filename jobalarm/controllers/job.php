<?php

class Job {
    public static function doView($rid=0) {
        if ($rid > 0) {
            $query = "
                select * from job j
                where j.id=$rid
            ";
            $dbData = Config::get('db')->get_results($query);

            if (count($dbData) > 0) {

                $response = $dbData[0];

                Config::set('surveyId',$response['surveyId']);
                $staticVars = Survey::getStaticVars($response['surveyId']);
                //
                if (strlen(trim($response['image'])) > 0) {
                    if (strlen(trim($response['image'])) > 0) {
                        Config::set('postimage','<img src="'.Config::get('base_url').'dat/jobfiles/127884/'.$response['id'].'/'.$response['image'].'" alt="JobAlarm" />');
                    } else {
                        Config::set('postimage','<img src="'.Config::get('base_url').'img/header-main2.png'.'" alt="JobAlarm" />');
                    }
                } else {
                    Config::set('postimage','<img src="'.Config::get('base_url').'img/header-main2.png'.'" alt="JobAlarm" />');
                }
                Config::set('postdate',date("m/d/Y", strtotime($response['postDate'])));
                Config::set('posttitle',$response['position']);
                Config::set('postcompany',$response['company']);
                Config::set('postcity',$response['city']);
                Config::set('poststate',$response['state']);
                Config::set('postzip',$response['zip']);
                Config::set('postcompensation',$response['compensation']);
                Config::set('postdescription',$response['description']);
                Config::set('postrequirements',$response['requirements']);
                Config::set('postuserid',$response['userId']);
                Config::set('postid',$rid);
                Config::set('postkeyword',Company::getJobKeyword($rid));
                $job_url = (isset($staticVars['tpl_joburl']) && strlen(trim($staticVars['tpl_joburl'])) > 0) ? trim($staticVars['tpl_joburl']) : 'http://m.jobalarm.com/s/chm';
                Config::set('joburl',$job_url.'/?p=1&u='.Config::get('postuserid').'&pt='.urlencode(Config::get('posttitle')).'&z='.Config::get('postid'));
            } else {
                die ('Job Not Found');
            }
        }

        Config::push('scripts','views/job/job.js');

        Config::set('noheader',true);

        require_once('views/job/job.php');
    }

    public static function view($rid) {
        self::doView($rid);
    }

    public static function run() {
        self::doView();
    }

    public static function getPosting($postId=0) {
        $postId = (isset($_REQUEST['recid'])) ? $_REQUEST['recid'] : $postId;
        $job = User::getJob($postId);
        $job['created'] = date("m/d/Y", strtotime($job['postDate']));
        $candidateCount = Company::getJobCandidateCount($job['id']);
        $job['candidates'] = '<a href="candidates?job='.$job['id'].'">'.$candidateCount.'</a>';
        $job['positiondisplay'] = '<a href="'.Config::get('base_url').'job/view/'.$job['id'].'" target="_blank">'.$job['position'].'</a>';
        $job['keywordtext'] = array('id'=>$job['keyword'],'text'=>$job['keywordtext']);
        $job['email'] = User::getEmail($job['userId']);
        $job['description'] = stripcslashes($job['description']);
        $job['requirements'] = stripcslashes($job['requirements']);

        $job['twitterlink'] = '<a class="fa fa-twitter fa-2x" href="javascript:;" onclick="wus.postToTwitter('.$job['id'].');"></a>';
        
        $outArray = array('status'=>'success','record'=>$job);
        
        echo json_encode($outArray);
        
        
    }
    
    public static function fixJobCount() {
        
        $dbData = Config::get('db')->get_results("select id from job");
        foreach($dbData as $job) {
            $candidateCount = Company::getJobCandidateCount($job['id']);
            $data = array('num_candidates'=>$candidateCount);
            $where = array('id'=>$job['id']);
            Config::get('db')->update('job',$data,$where);
            //echo $job['id']." : ".$candidateCount."<br />\r\n";
        }
        
    }
    public static function getJobPostings($userId=0,$archived=0,$viewcompany=0) {
        /*
         *  changes[0][keywordtext][hidden...	false
        changes[0][keywordtext][id...	7
        changes[0][keywordtext][text...	test7
        changes[0][recid]	16
         */
        self::fixJobCount();
        
        
        if (isset($_REQUEST['cmd']) && $_REQUEST['cmd'] == 'save-records') {
            $changes = (isset($_REQUEST['changes'])) ? $_REQUEST['changes'] : array();
            foreach($changes as $change) {
                $recordid = $change['recid'];
                $keywordid = $change['keywordtext']['id'];
                $data = array('keyword'=>$keywordid);
                $where = array('id'=>$recordid);
                Config::get('db')->update('job',$data,$where,1);
            }

        }

        Response::importNewResponses(127884, array('filter' => '&_completed=1&_updated_at>' . Response::getLastUpdateSurvey(127884)));

        $query = "SELECT * FROM responsequeue WHERE surveyId = 127884 AND processed = 0 LIMIT 0,100";
        $dbData = Config::get('db')->get_results($query);

        if ($dbData && count($dbData) > 0) {
            foreach ($dbData as $response) {
                $responseData = json_decode($response['responseData'], true);
                Response::add(127884, $responseData, false);
                Response::queueMarkProcessed($response['responseId']);
            }
        }
        $total = 0;
        $result = User::getJobList($userId,$archived,$_REQUEST['offset'],$_REQUEST['limit'],$total,$viewcompany);

        //                $result['copylink'];
        //                $result['created'] = date("m/d/Y", strtotime($result['Display_created_at']));
        //                $result['DisplayslDsIKOH9I'] = '<a href="#">'.$result['DisplayslDsIKOH9I'].'</a>';
        //                $result['candidates'] = '<a href="#">0</a>';
        $outData = array();
        foreach($result as $k => $job) {
            if ($job['keywordpost'] == 1) {
                $job['copylink'] = '';
                $job['twitterlink'] = '';
            } else { 
                if ($archived == 0) {
                    $job['copylink']  = '<a href="javascript:;" onclick="wus.editJob('.$job['id'].')" class="dashboard_edit_btn" title="Edit Job"><img src="img/edit.png" alt="Edit Job" /> </a> <button id="copy-button-'.$job['id'].'" class="clipboard_button" data-clipboard-text="'.Config::get('base_url').'job/view/'.$job['id'].'" title="Click to copy me.">Copy URL</button>  <a href="#" class="dashboard_archive_btn" onclick="wus.archiveJob('.$job['id'].')" title="Archive Job"><img src="img/archive.png" alt="Archive" /> </a> <a href="javascript:;" onclick="wus.duplicateJob('.$job['id'].')" class="dashboard_copy_btn" title="Duplicate Job"><img src="img/copy.png" alt="Duplicate" /> </a>';
                    $job['twitterlink'] = '<a class="fa fa-twitter fa-2x" href="javascript:;" onclick="wus.postToTwitter('.$job['id'].');"></a>';
                    
                } else {
                    $job['copylink']  = ' <a href="#" class="dashboard_archive_btn" onclick="wus.unarchiveJob('.$job['id'].')" title="UnArchive Job"><img src="img/archive.png" alt="UnArchive" /> ';
                }
            }
            
            $job['created'] = date("m/d/Y", strtotime($job['postDate']));
            
            $job['candidates'] = '<a href="candidates?job='.$job['id'].'">'.$job['num_candidates'].'</a>';
            $job['positiondisplay'] = '<a href="'.Config::get('base_url').'job/view/'.$job['id'].'" target="_blank">'.$job['position'].'</a>';
            $job['keywordtext'] = array('id'=>$job['keyword'],'text'=>$job['keywordtext']);
            $job['email'] = User::getEmail($job['userId']);
            $job['description'] = stripcslashes($job['description']);
            $job['requirements'] = stripcslashes($job['requirements']);

            $outData[] = $job;
        }
        $outArray = array(
            'status' => 'success',
            'total' => $total,
            'numOptOut' => 0,
            'records' => $outData
        );

        echo json_encode($outArray,JSON_NUMERIC_CHECK );

    }

    public static function getJobPostingsTest($userId=0,$archived=0,$viewcompany=0) {
        /*
         *  changes[0][keywordtext][hidden...	false
        changes[0][keywordtext][id...	7
        changes[0][keywordtext][text...	test7
        changes[0][recid]	16
         */
        $start_time = microtime(true);
        
        if (isset($_REQUEST['cmd']) && $_REQUEST['cmd'] == 'save-records') {
            $changes = (isset($_REQUEST['changes'])) ? $_REQUEST['changes'] : array();
            foreach($changes as $change) {
                $recordid = $change['recid'];
                $keywordid = $change['keywordtext']['id'];
                $data = array('keyword'=>$keywordid);
                $where = array('id'=>$recordid);
                Config::get('db')->update('job',$data,$where,1);
            }

        }

        $current_time = microtime(true)-$start_time;
        echo $current_time." : ".__LINE__."<br />";
        
        Response::importNewResponsesTest(127884, array('filter' => '&_completed=1&_updated_at>' . Response::getLastUpdateSurvey(127884)));

        $current_time = microtime(true)-$start_time;
        echo $current_time." : ".__LINE__."<br />";
        $query = "SELECT * FROM responsequeue WHERE surveyId = 127884 AND processed = 0 LIMIT 0,100";
        $dbData = Config::get('db')->get_results($query);
        $current_time = microtime(true)-$start_time;
        echo $current_time." : ".__LINE__."<br />";

        if ($dbData && count($dbData) > 0) {
            foreach ($dbData as $response) {
                $responseData = json_decode($response['responseData'], true);
                Response::add(127884, $responseData, false);
                Response::queueMarkProcessed($response['responseId']);
            }
        }
        $current_time = microtime(true)-$start_time;
        echo $current_time." : ".__LINE__."<br />";
        $total = 0;
        $offset = isset($_REQUEST['offset']) ? $_REQUEST['offset'] : 0;
        $limit = isset($_REQEUEST['limit']) ? $_REQUEST['limit'] : 100;
        
        $result = User::getJobListTest($userId,$archived,$offset,$limit,$total,$viewcompany);
        $current_time = microtime(true)-$start_time;
        echo $current_time." : ".__LINE__."<br />";

        //                $result['copylink'];
        //                $result['created'] = date("m/d/Y", strtotime($result['Display_created_at']));
        //                $result['DisplayslDsIKOH9I'] = '<a href="#">'.$result['DisplayslDsIKOH9I'].'</a>';
        //                $result['candidates'] = '<a href="#">0</a>';
        $outData = array();
        foreach($result as $k => $job) {
            if ($job['keywordpost'] == 1) {
                $job['copylink'] = '';
                $job['twitterlink'] = '';
            } else { 
                if ($archived == 0) {
                    $job['copylink']  = '<a href="javascript:;" onclick="wus.editJob('.$job['id'].')" class="dashboard_edit_btn" title="Edit Job"><img src="img/edit.png" alt="Edit Job" /> </a> <button id="copy-button-'.$job['id'].'" class="clipboard_button" data-clipboard-text="'.Config::get('base_url').'job/view/'.$job['id'].'" title="Click to copy me.">Copy URL</button>  <a href="#" class="dashboard_archive_btn" onclick="wus.archiveJob('.$job['id'].')" title="Archive Job"><img src="img/archive.png" alt="Archive" /> </a> <a href="javascript:;" onclick="wus.duplicateJob('.$job['id'].')" class="dashboard_copy_btn" title="Duplicate Job"><img src="img/copy.png" alt="Duplicate" /> </a>';
                    $job['twitterlink'] = '<a class="fa fa-twitter fa-2x" href="javascript:;" onclick="wus.postToTwitter('.$job['id'].');"></a>';
                    
                } else {
                    $job['copylink']  = ' <a href="#" class="dashboard_archive_btn" onclick="wus.unarchiveJob('.$job['id'].')" title="UnArchive Job"><img src="img/archive.png" alt="UnArchive" /> ';
                }
            }
            
            $job['created'] = date("m/d/Y", strtotime($job['postDate']));
            $candidateCount = Company::getJobCandidateCount($job['id']);
            $job['candidates'] = '<a href="candidates?job='.$job['id'].'">'.$candidateCount.'</a>';
            $job['positiondisplay'] = '<a href="'.Config::get('base_url').'job/view/'.$job['id'].'" target="_blank">'.$job['position'].'</a>';
            $job['keywordtext'] = array('id'=>$job['keyword'],'text'=>$job['keywordtext']);
            $job['email'] = User::getEmail($job['userId']);
            $job['description'] = stripcslashes($job['description']);
            $job['requirements'] = stripcslashes($job['requirements']);

            $outData[] = $job;
        }
        $outArray = array(
            'status' => 'success',
            'total' => $total,
            'numOptOut' => 0,
            'records' => $outData
        );
        $current_time = microtime(true)-$start_time;
        echo $current_time." : ".__LINE__."<br />";

        echo json_encode($outArray,JSON_NUMERIC_CHECK );

    }
    
    
    public static function getJobCandidates($jobId) {


    }

    public static function postToTwitter($jobId) {
        $dbData = Config::get('db')->get_results("select * from job where id={$jobId}");
        if (count($dbData) > 0) {
            $job = $dbData[0];
            $citystate = trim(str_replace(" ","",$job["city"].$job['state']));
            $jobURL = Config::get('base_url').'job/view/'.$job['id'];
            $twitterstatus = "{$job['position']} position available in {$job['city']}, {$job['state']} {$jobURL} #Jobs #{$citystate}Jobs";            
            
            \Codebird\Codebird::setConsumerKey("3kkH7zqxgOBJTB6GCO73vNjum", "IYTHCTOdCIlHns0yP3PoZJKhz3jLqNVfthHD7Y0uvbJsx57tob");
            $cb = \Codebird\Codebird::getInstance();
            $cb->setToken("26564418-lXp9wi6Uxkla425phZmE4W2Tp0pTevlR33xjjftge", "ZNXgDmVRxzTHwxi9HqzIwRaQ3n8xzT2TGqrXtimmlk0KG");
                        
            $params = array(
              'status' => $twitterstatus
            );
            $reply = $cb->statuses_update($params);            
        }
        echo json_encode(array('status'=>'success','message'=>$reply));
    }
    
    public static function duplicate($jobId) {

        $dbData = Config::get('db')->get_results("select * from job where id={$jobId}");
        if (count($dbData) > 0) {
            $job = $dbData[0];
            unset($job['id']);
            unset($job['image']);
            $job['description'] = $job['description'];
            $job['requirements'] = $job['requirements'];
            $job['company'] = Config::get('db')->filter($job['company']);
            $job['position'] = Config::get('db')->filter($job['position']);
            $job['postDate'] = date('Y-m-d H:i:s');
            Config::get('db')->insert('job',$job);
            $jobId = Config::get('db')->lastid();
            echo json_encode(array('success'=>true,'newJobId'=>$jobId));
            return true;
        }

        echo json_encode(array('success'=>false));

    }

    public static function updateJob($jobId) {

        $where = array('id'=>$jobId);
        $data = $_REQUEST;

        if (isset($_REQUEST['imagefile']) && is_array($_REQUEST['imagefile']) && count($_REQUEST['imagefile']) > 0) {
            $uploadfile = $_REQUEST['imagefile'][0];
            $filedata = base64_decode($uploadfile['content']);
            $targetFolder = 'dat/jobfiles';
            $surveyId = 127884;
            $optional = $data['id'];
            if (!is_dir(dirname($_SERVER['SCRIPT_FILENAME']) . "/{$targetFolder}")) {
                mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . '/' . $targetFolder);
            }
            if (!is_dir(dirname($_SERVER['SCRIPT_FILENAME']) . "/{$targetFolder}/{$surveyId}")) {
                mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . "/{$targetFolder}/{$surveyId}");
            }
            if (!is_dir(dirname($_SERVER['SCRIPT_FILENAME']) . "/{$targetFolder}/{$surveyId}/{$optional}")) {
                mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . "/{$targetFolder}/{$surveyId}/{$optional}");
            }
            $jobpostfile = dirname($_SERVER['SCRIPT_FILENAME']) . "/{$targetFolder}/{$surveyId}/{$optional}/{$uploadfile['name']}";
            file_put_contents($jobpostfile, $filedata);
            $data['image'] = $uploadfile['name'];
        }
        if (isset($data['keyword'])) {
            $data['keyword'] = $data['keyword']['id'];
        }
        if (isset($data['params'])) unset($data['params']);
        if (isset($data['id'])) unset($data['id']);
        if (isset($data['postDate'])) unset($data['postDate']);
        if (isset($data['surveyId'])) unset($data['surveyId']);
        if (isset($data['archived'])) unset($data['archived']);
        if (isset($data['active'])) unset($data['active']);
        if (isset($data['recid'])) unset($data['recid']);
        if (isset($data['copylink'])) unset($data['copylink']);
        if (isset($data['created'])) unset($data['created']);
        if (isset($data['candidates'])) unset($data['candidates']);
        if (isset($data['positiondisplay'])) unset($data['positiondisplay']);
        if (isset($data['imagefile'])) unset($data['imagefile']);
        if (isset($data['walkupadmin'])) unset($data['walkupadmin']);
        if (isset($data['keywordtext'])) unset($data['keywordtext']);
        if (isset($data['adminarchived'])) unset($data['adminarchived']);
        if (isset($data['email'])) unset($data['email']);
        if (isset($data['twitterlink'])) unset($data['twitterlink']);
        
        //var_dump($data);
        //var_dump($_REQUEST);

        $jobURL = Config::get('base_url').'job/view/'.$jobId;

        \Codebird\Codebird::setConsumerKey("3kkH7zqxgOBJTB6GCO73vNjum", "IYTHCTOdCIlHns0yP3PoZJKhz3jLqNVfthHD7Y0uvbJsx57tob");
        $cb = \Codebird\Codebird::getInstance();
        $cb->setToken("26564418-lXp9wi6Uxkla425phZmE4W2Tp0pTevlR33xjjftge", "ZNXgDmVRxzTHwxi9HqzIwRaQ3n8xzT2TGqrXtimmlk0KG");
        
        $citystate = trim(str_replace(" ","",$data["city"].$data['state']));
        
        $params = array(
          'status' => "{$data['position']} position available in {$data['city']}, {$data['state']} {$jobURL} #Jobs #{$citystate}Jobs"
        );
        $reply = $cb->statuses_update($params);

        
        $data['description'] = $data['description'];
        $data['requirements'] = $data['requirements'];
        $data['company'] = Config::get('db')->filter($data['company']);
        $data['position'] = Config::get('db')->filter($data['position']);

        Config::get('db')->update('job',$data,$where);
        

        
        //var_dump($data);
        echo json_encode(array('success'=>true));

    }

    public static function archiveJob($jobId) {

        $where = array('id'=>$jobId);
        $data = array('archived'=>1);
        Config::get('db')->update('job',$data,$where);
        echo json_encode(array('success'=>true));

    }

    public static function unarchiveJob($jobId) {

        $where = array('id'=>$jobId);
        $data = array('archived'=>0);
        Config::get('db')->update('job',$data,$where);
        echo json_encode(array('success'=>true));

    }

    public static function adminArchiveJob($jobId) {

        $where = array('id'=>$jobId);
        $data = array('adminarchived'=>1);
        Config::get('db')->update('job',$data,$where);
        echo json_encode(array('success'=>true));

    }

    public static function unAdminArchiveJob($jobId) {

        $where = array('id'=>$jobId);
        $data = array('adminarchived'=>0);
        Config::get('db')->update('job',$data,$where);
        echo json_encode(array('success'=>true));

    }


    public static function changeKeyword() {
        if (isset($_REQUEST['jid']) && isset($_REQUEST['kid'])) {
            $jobId = $_REQUEST['jid'];
            $keywordId = $_REQUEST['kid'];
            $data = array('keyword'=>$keywordId);
            $where = array('id'=>$jobId);
            Config::get('db')->update('job',$data,$where,1);
            echo json_encode(array('success'=>true));
        } else {
            echo json_encode(array('success'=>false));
        }
    }

}
