<?php

class Careers {
    public static function doView($rid=0) {

        Config::push('scripts','views/careers/careers.js');

        Config::set('noheader',true);
        
        $query = "select * from job where active>0 and archived=0 and adminarchived=0 order by postDate desc";
        $jobs = Config::get('db')->get_results($query);
        Config::set('joblist',$jobs);
        
        require_once('views/careers/careers.php');
    }
    
    public static function view($rid) {
        self::doView($rid);
    }
    
    public static function run() {
        self::doView();
    }
 
    
}
