<?php
require_once('models/user.php');
class Register {
    
    public static function doView() {
        Config::push('scripts','views/register/register.js');
        Config::set('mainmenu',false);
        require_once('views/register/register.php');
    }
    
    public static function run() {
        self::doView();
    }
}

