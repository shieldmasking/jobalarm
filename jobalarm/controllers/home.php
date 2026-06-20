<?php
require_once('models/user.php');
class Home {
    
    public static function doView() {
        Config::push('scripts','views/home/home.js');
        Config::set('mainmenu',false);
        require_once('views/home/home.php');
    }
    
    public static function run() {
        self::doView();
    }
}

