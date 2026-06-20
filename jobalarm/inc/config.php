<?php

//Setup our automatic class loading
function __autoload($class_name) {
    if (file_exists(dirname(__FILE__).'/../inc/class.' . strtolower($class_name) . '.php')) {
        include dirname(__FILE__).'/../inc/class.' . strtolower($class_name) . '.php';
    } else
        if (file_exists(dirname(__FILE__)."/../models/".strtolower($class_name).'.php')) {
            include dirname(__FILE__)."/../models/".strtolower($class_name).'.php';
        }        
}

include('lib/class.pdf2text.php');
include('lib/class.doc2text.php');
include "lib/class.rtf2text.php";                        
require_once('lib/codebird.php');

//Site Configuration
define('SITE_NAME','Project Management System');
define('SITE_VERSION','1.0.1b');

//Our Default Controller
define('CONTROLLER_DEFAULT','home');

//Database

//no database debugging
define('DISPLAY_DEBUG',false);

//Jovian
//define('DB_HOST','localhost');
//define('DB_USER','jovianla_walkup');
//define('DB_PASS','4k1r4s4t0');
//define('DB_NAME','jovianla_walkup');

//Walkup
//define('DB_HOST', 'mysql');
//define('DB_USER', 'rstrenger');
//define('DB_PASS', 'Pr3m13r1');
//define('DB_NAME', 'walkupscreener');

//Local Dev
define('DB_HOST','localhost');
define('DB_USER','tweetedj_admin');
define('DB_PASS','Premier2000!');
define('DB_NAME','tweetedj_tweetedjobs');
$DB = new DB();
Config::set('db',$DB);

//fluidsurvey configuration
define('FS_URL', 'http://surveys.walkupscreener.com/api/v2/surveys/');
define('FS_FILEURL','http://surveys.walkupscreener.com/account/surveys/');
define('FS_APIKEY', 'dWNcQf935mQ8nQPZKp8Zs4XCFNmXzS');
define('FS_PASSWORD', 'Premier2000!');

//eztext configuration
define('EZ_LOGIN', 'rstrenger');
define('EZ_PASSWORD', 'Premier2000!');
define('EZ_URL', 'https://app.eztexting.com/incoming-messages?format=json&');
define('EZ_SEND_URL', 'https://app.eztexting.com/sending/messages?format=json&User=' . EZ_LOGIN . '&Password=' . EZ_PASSWORD);
define('EZ_FILE_URL', 'http://surveys.walkupscreener.com/media/assets/survey-uploads/');
define('EZ_VOICE_URL', 'https://app.eztexting.com/api/voicemessages');
define('EZ_CALLER_ID', '2149343360');


//captcha define
define('CAPTCHA_KEY','6Ld5wwATAAAAAJGJAFwH9RTlYL4VK8zPZDRsGO93');
define('CAPTCHA_URL','6Ld5wwATAAAAAEp-NtmruQLWkNirGccXXWqwRzp5');

//set default timezone
date_default_timezone_set('America/Chicago');

//Environment
Config::set('base_url','http://admin.jobalarm.com/');
Config::set('sitecookie','walkupadmin');
//define('PHP_EXECUTABLE','c:\xampp\php\php.exe');   //windows
define('PHP_EXECUTABLE','/usr/bin/php');           //linux
Config::set('sms_optout_message','(Reminder, text STOP 2 optout)');


//Preinitialize
//Survey config enumeration
interface SURVEY_CONFIG {

    const DISPLAY = 1;
    const FILTERS = 2;
    const EDITVIEW = 3;
    const SMSVIEW = 4;

}

//Survey static vars enumeration
interface STATIC_VARS {

    const KEYWORD = '1';
    const RETURNSTR = '2';
    const MOBILENUM = '3';
    const FIRSTNAME = '4';
    const LASTNAME = '5';
    const EMAIL = '6';
    const ZIPCODE = '7';
    const POSITION = '8';
    const LOCATION = '9';
    const SURVEYKEYWORD = '10';
    const SURVEYMESSAGE = '11';
    const SURVEYCOMPLETEMESSAGE = '12';
    const SURVEYFILE = '13';
    const FORWARDINGOPTIONS = '14';
    const REFERRAL = '15';
    const FORWARDEMAIL = '16';
    const FORWARDMSG = '17';
    const EMPREFEMAIL = '18';
    const OPTIN = '19';
    const DEFAULTADMIN = '20';
    const POSTID = '21';
    const JOBURL = '22';
    const DEFAULTPOSTID = '23';
}

//Log categories
interface LOG_TYPES {

    const SYSTEM = 1;
    const SURVEY = 2;
    const PEOPLE = 3;
    const SMS = 4;

}

//Log severities
interface LOG_SEVERITIES {

    const NONE = 10;
    const COMMON = 20;
    const WARNING = 30;
    const CRITICAL = 100;

}

//Response Types
interface RESPONSE_TYPES {
    
    const RESPONSE = 10;
    const RESPONSEEDIT = 20;
    const RESPONSESMS = 30;
    
}

//Setup routes
if (!isset($bypassRouting)) {
    Router::Initialize();
}

//Initialize the Utilities
Utility::Initialize($DB);
