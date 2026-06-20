<?php
require_once('models/user.php');
class Admindashboard {
    
    public static function doView() {
        if (!User::checkLogin()) {
            header('location: home');
        }        
        Config::push('scripts','views/shared/sharedfunctions.js');
        Config::push('scripts','views/admindashboard/admindashboard.js');
        Config::push('jsvars',array('uid'=>Config::get('loggedIn')));
        Config::push('jsvars',array('url'=>Config::get('base_url')));        
        Config::push('jsvars',array('cid'=>User::getData('companyId')));
        $keywords = Company::getKeywords(User::getData('companyId'));
        $keywordArray = array();
        foreach($keywords as $keyword) {
            $keywordArray[] = array('id'=>$keyword['id'],'text'=>$keyword['keyword']);
        }
        Config::push('jsvars',array('keywords'=>json_encode($keywordArray,JSON_NUMERIC_CHECK )));
        Config::set('mainmenu',true);
        Config::set('sysblock',true);
        require_once('views/admindashboard/admindashboard.php');
    }
    
    public static function run() {
        self::doView();
    }
    
}

