<?php
require_once('models/user.php');

class Admin {
    
    public static function doView() {
        if (!User::checkLogin() || !User::isAdmin()) {
            header('location: home');
        }
        Config::push('scripts','views/admin/managelocalsurveys.js');
        Config::push('scripts','views/admin/managelivesurveys.js');
        Config::push('scripts','views/admin/manageglobals.js');
        Config::push('scripts','views/admin/managecompanies.js');
        Config::push('scripts','views/admin/manageusers.js');
        Config::push('scripts', 'views/admin/templates_sms.js');
        Config::push('scripts','views/admin/admin.js');
        
        Config::push('jsvars',array('cid'=>User::getData('companyId')));
        
        Config::set('mainmenu',true);
        Config::set('sysblock',true);
        require_once('views/admin/admin.php');
    }
    
    public static function run() {
        self::doView();
    }
    
    public static function dbupdate() {
        
    }
}
