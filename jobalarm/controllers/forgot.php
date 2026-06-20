<?php
require_once('models/user.php');
class Forgot {
    
    public static function doView() {
        Config::push('scripts','views/forgot/forgot.js');
        Config::set('mainmenu',false);
        require_once('views/forgot/forgot.php');
    }
    
    public static function run() {
        self::doView();
    }
}

