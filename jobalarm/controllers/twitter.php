<?php
require_once('models/user.php');

class Twitter {
    
    public static function doView() {
        include('lib/twitter-feed-parser.php');
    }
    
    public static function run() {
        self::doView();
    }
    
    public static function dbupdate() {
        
    }
}
