<?php
require_once('models/user.php');

class Smsview {
    
    public static function doView() {
        if (!User::checkLogin()) {
            header('location: home');
        }
        if (Router::getGetVar('sid')) {
            Config::push('jsvars', array('surveyId' => Router::getGetVar('sid')));
            $dbData = Config::get('db')->get_results('SELECT name from survey where surveyId=' . Router::getGetVar('sid'));
            Config::push('jsvars', array('surveyName' => $dbData[0]['name']));
        }        
        Config::push('scripts','views/shared/sharedfunctions.js');
        Config::push('scripts','views/smsview/smsview.js');
        Config::set('mainmenu',true);
        Config::set('sysblock',true);
        require_once('views/smsview/smsview.php');
    }
    
    public static function run() {
        self::doView();
    }
    

}
