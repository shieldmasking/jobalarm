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

function buildKeywordQueryNew($field,$keywords) {
    $keywords = strtolower($keywords);
    $buildArray = array();
    $outString = "";
    $orArray = explode(" or ", $keywords);
    foreach ($orArray as $subs) {
        $tempSubs = explode(" and ", $subs);
        $outString = "";
        $subArray = array();
        foreach ($tempSubs as $word) {
            $word = addslashes($word);
            $comparator = ' +';
            if (substr($word, 0, 1) == '-') {
                $comparator = ' -';
                $word = substr($word, 1);
            }
            $subArray[] = "{$comparator}{$word}";
        }
        $outString .= implode(" ", $subArray);
        $buildArray[] = $outString;
    }
    $outSearch = 'AND (';
    $outSearchArray = array();
    foreach($buildArray as $searchParams) {
        $outSearchArray[] = "match({$field}) against('{$searchParams}' IN BOOLEAN MODE)";
    }
    $outSearch .= implode(' OR ',$outSearchArray);
    $outSearch .= ') ';
    return $outSearch;
}

function buildKeywordQuerySphinx($field,$keywords) {
    $keywords = strtolower($keywords);
    $buildArray = array();
    $outString = "";
    $orArray = explode(" or ", $keywords);
    foreach ($orArray as $subs) {
        $tempSubs = explode(" and ", $subs);
        $outString = "";
        $subArray = array();
        foreach ($tempSubs as $word) {
		$subwords = explode(" ",$word);
		foreach($subwords as $sword) {
            		$sword = addslashes($sword);
		        $comparator = '';
        		if (substr($sword, 0, 1) == '-') {
                		$comparator = ' !';
                		$word = substr($word, 1);
            		}
            		$subArray[] = "{$comparator}{$sword}";
		}
        }
        $outString .= '('.implode(" ", $subArray).')';
        $buildArray[] = $outString;
    }
    $outSearch = '(';
    $outSearchArray = array();
    foreach($buildArray as $searchParams) {
        $outSearchArray[] = "{$searchParams}";
    }
    $outSearch .= implode(' | ',$outSearchArray);
    $outSearch .= ') ';
    return $outSearch;
}

function randomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array();
    $alphaLength = strlen($alphabet) - 1; 
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass);
}


function buildKeywordQuery($outvar, $keywords) {
    $keywords = strtolower($keywords);
    $buildArray = array();
    $outString = "";
    $orArray = explode(" or ", $keywords);
    foreach ($orArray as $subs) {
      $tempSubs = explode(" and ", $subs);
      $outString = "(";
      $subArray = array();
      foreach ($tempSubs as $word) {
        $word = addslashes($word);
        $comparator = 'LIKE';
        if (substr($word, 0, 1) == '-') {
          $comparator = 'NOT LIKE';
          $word = substr($word, 1);
        }
        $subArray[] = "{$outvar} {$comparator} '%{$word}%'";
      }
      $outString .= implode(" AND ", $subArray);
      $outString .= ")";
      $buildArray[] = $outString;
    }

    return "(" . implode(" OR ", $buildArray) . ")";
}

/*
function getDistanceQuery($db,$zipCode, $distance) {
    //  die($zipCode.' : '.$distance);

    $dbData = $db->get_results("select zip,latitude,longitude from cities_extended where zip='{$zipCode}'");
    foreach($dbData as $res) {
        if (count($res) > 0) {
            $lat1 = $res['latitude'];
            $lon1 = $res['longitude'];
            $d = $distance;
            $r = 3959;
            $latN = rad2deg(asin(sin(deg2rad($lat1)) * cos($d / $r) + cos(deg2rad($lat1)) * sin($d / $r) * cos(deg2rad(0))));
            $latS = rad2deg(asin(sin(deg2rad($lat1)) * cos($d / $r) + cos(deg2rad($lat1)) * sin($d / $r) * cos(deg2rad(180))));
            $lonE = rad2deg(deg2rad($lon1) + atan2(sin(deg2rad(90)) * sin($d / $r) * cos(deg2rad($lat1)), cos($d / $r) - sin(deg2rad($lat1)) * sin(deg2rad($latN))));
            $lonW = rad2deg(deg2rad($lon1) + atan2(sin(deg2rad(270)) * sin($d / $r) * cos(deg2rad($lat1)), cos($d / $r) - sin(deg2rad($lat1)) * sin(deg2rad($latN))));
            $zipres = $db->get_results("SELECT * FROM cities_extended WHERE (latitude <= $latN AND latitude >= $latS AND longitude <= $lonE AND longitude >= $lonW) AND (latitude != $lat1 AND longitude != $lon1) AND city != '' ORDER BY state_code, city, latitude, longitude");
            $ziplist = array($zipCode);
            foreach ($zipres as $zip) {
                $ziplist[] = $zip['zip'];
            }
            return "(" . implode(',', $ziplist) . ")";
        }
    }
} */

function extract_zipcode($address, $remove_statecode = false) {
    preg_match( '/(\d{5})/', $address,  $matches);
    $result = $matches[count($matches) - 1];
    return $result;
}

//Database Configuration

define('DB_HOST','localhost');
define('DB_USER','prnadmin_ownrdba');
define('DB_PASS','h6OwQMZAExky');
define('DB_NAME','prnadmin_siteprod');

//Twitter login
//define('TWITTER_CONSUMER_KEY', 'IdiBYwURb9iWDixvl8qODOCw1'); //beta config
//define('TWITTER_CONSUMER_SECRET', 'pV6GcItd3PLxbg6PQ5w0WuJjFH84z5Lro0toST4HdPpErXmHHH');
define('TWITTER_CONSUMER_KEY', 'yGsGc0ep2A3SVz3G6KueZsmGX'); //live config
define('TWITTER_CONSUMER_SECRET', 'mn5DdbZa1uMycnHhnOVQTKGNkVy3l6aBYy8O0DVSjSiurWQzT9');
define('TWITTER_OAUTH_CALLBACK', 'http://jobalarm.com/twitterlogin.php');

if(file_exists('lib/twitteroauth/autoload.php' )){
    require_once('lib/twitteroauth/autoload.php');
}

//Our master database object
$DB = new DB();
Config::set('db',$DB);


