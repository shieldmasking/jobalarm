<?php

class Utility {

  private static $_db;

  public static function Initialize($db) {
    self::$_db = $db;
  }

  public static function genCode($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
  }

  public static function GUID() {
    if (function_exists('com_create_guid') === true) {
      return trim(com_create_guid(), '{}');
    }
    return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
  }

  public static function parsePhone($innumber) {
    $number = preg_replace("/[^0-9]/", "", $innumber);
    if (strlen($number) == 10)
      return $number;
    if (strlen($number) == 11 && substr($number, 0, 1) == '1')
      return substr($number, 1);
    return NULL;
  }

  public static function getArrayNext(&$array, $curr_key) {
    $next = FALSE;
    reset($array);

    do {
      $tmp_key = key($array);
      $res = next($array);
    } while (($tmp_key != $curr_key) && $res);

    if ($res) {
      $next = key($array);
    }

    return $next;
  }

  //public static function curl_request($user, $password, $url, $postdata = null) {
    //$ch = curl_init();
    //curl_setopt($ch, CURLOPT_URL, $url);
   // curl_setopt($ch, CURLOPT_POST, 1);
    //curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    //curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
    //$server_output = curl_exec($ch);
    //curl_close($ch);
    //return $server_output;
  //}
  
  	//define('SLOOCE_LOGIN', 'jobalarm45');
	//define('SLOOCE_PW', 'wet#%DFG^&FHHJ');
	//define('SLOOCE_API', 'http://sloocetech.net:8084/spi-war/spi/');
	//define('SLOOCE_SEND_URL', 'http://sloocetech.net:8084/spi-war/spi/' . SLOOCE_LOGIN . '/' . $numberlist . '/' . $keyword . '/messages/mt');
	
	public static function curl_request($user, $url, $postdata = null, $header) {
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_POST, 1);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    //curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	    //curl_setopt($ch, CURLOPT_SSLVERSION, 3);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	    //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	    //curl_setopt($ch, CURLOPT_HEADER, false);
	    //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
	    $server_output = curl_exec($ch);
	    curl_close($ch);
	    echo $server_output;
	    return $server_output;
	}


  public static function extFormatResults($results) {
    $output = Array();
    $output['totalCount'] = 0;
    $output['records'] = Array();
    if ($results && is_array($results)) {
      $output['totalCount'] = count($results);
      $output['records'] = $results;
    }
    return $output;
  }

  public static function w2uiFormatResults($results) {
    $outArray = array();
    $outArray['status'] = 'success';
    $outArray['total'] = count($results);
    $outArray['records'] = $results;
    return $outArray;
  }

  public static function createArrayKeyCSV($array) {
    $outData = array();
    if ($array && is_array($array)) {
      $outData = array_keys($array);
      return implode(',', $outData);
    }
    return '';
  }

  public static function createArrayValCSV($array) {
    $outData = array();
    if ($array && is_array($array)) {
      $outData = array_values($array);
      return "'" . implode("','", $outData) . "'";
    }
    return '';
  }

  public static function execInBackground($cmd) {
    if (substr(php_uname(), 0, 7) == "Windows") {
      pclose(popen("start /B " . $cmd, "r"));
    } else {
      exec($cmd . " > /dev/null &");
    }
  }

  public static function getPHPExecutableFromPath() {
    $paths = explode(PATH_SEPARATOR, getenv('PATH'));
    foreach ($paths as $path) {
      // we need this for XAMPP (Windows)
      if (strstr($path, 'php.exe') && isset($_SERVER["WINDIR"]) && file_exists($path) && is_file($path)) {
        return $path;
      } else {
        $php_executable = $path . DIRECTORY_SEPARATOR . "php" . (isset($_SERVER["WINDIR"]) ? ".exe" : "");
        if (file_exists($php_executable) && is_file($php_executable)) {
          return $php_executable;
        }
      }
    }
    return FALSE; // not found
  }

  public static function buildKeywordQuery($outvar, $keywords) {
    $keywords = strtolower($keywords);
    $buildArray = array();
    $outString = "";
    $orArray = explode(" or ", $keywords);
    foreach ($orArray as $subs) {
      $tempSubs = explode(" and ", $subs);
      $outString = "(";
      $subArray = array();
      foreach ($tempSubs as $word) {
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
}
