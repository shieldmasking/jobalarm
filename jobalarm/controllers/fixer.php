<?php

class Fixer {
    public static function run() {
    
        self::doView();
        
    }
    
    public static function doView() {
        if (!User::checkLogin()) {
            header('location: home');
        }        
        Config::push('scripts','views/shared/sharedfunctions.js');
        Config::push('scripts','views/fixer/fixer.js');
        Config::set('mainmenu',true);
        Config::set('sysblock',true);
        require_once('views/fixer/fixer.php');
    }
}
