<?php
require_once('models/user.php');

class Prefs {
    
    public static function doView() {
        if (!User::checkLogin()) {
            header('location: home');
        }
        Config::push('scripts','views/prefs/prefs.js');
        Config::set('mainmenu',true);
        Config::set('sysblock',true);
        require_once('views/prefs/prefs.php');
    }
    
    public static function run() {
        self::doView();
    }
}
