<?php

/**
 * twitparse 
 *
 * all needed configurations for the application
 *
 * @version 1.0
 * @author John McClelland
 */

//Configuration class singleton for global access to config variables
class Config {
    private static $_data;
    public static function get($var) {
        if (isset(self::$_data[$var])) {
            return self::$_data[$var];
        }
        return false;
    }
    public static function set($var,$value) {
        self::$_data[$var] = $value;
    }
    public static function push($var,$value) {
        if (!isset(self::$_data[$var])) {
            self::$_data[$var] = Array();
        }
        array_push(self::$_data[$var],$value);
    }
}


//Database Configuration

define('DB_HOST','localhost');
define('DB_USER','tweetedj_admin');
define('DB_PASS','Premier2000!');
define('DB_NAME','tweetedj_tweetedjobs');


//Our master database object
$DB = new DB();
Config::set('db',$DB);


//Setup our Mongo Connection
$connection = new MongoClient();
$db = $connection->twitter;

$collection = $db->jobtweets;
Config::set('mongodb',$collection);

$seekercollection = $db->seekertweets;
Config::set('seekerdb',$seekercollection);

