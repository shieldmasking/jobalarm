<?php
require_once('models/user.php');

class Jobsms {
    
    public static function doView() {
        if (!User::checkLogin()) {
            header('location: home');
        }
        if (Router::getGetVar('job')) {
            $job = Company::getJob(Router::getGetVar('job'));
            $surveyId = $job['surveyId'];
            $staticVars = Survey::getStaticVars($surveyId);
            Config::push('jsvars',array('postidvar'=>$staticVars['tpl_postid']));
            Config::push('jsvars',array('postid'=>$job['id']));
            Config::push('jsvars',array('posttitle'=>$job['position']));
            Config::push('jsvars', array('surveyId' => $surveyId));
        }        
        Config::push('scripts','views/shared/sharedfunctions.js');
        Config::push('scripts','views/jobsms/jobsms.js');
        Config::set('mainmenu',true);
        Config::set('sysblock',true);
        require_once('views/jobsms/jobsms.php');
    }
    
    public static function run() {
        self::doView();
    }
    

}
