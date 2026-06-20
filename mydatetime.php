<!DOCTYPE html>

<html>

<head>
  <title>Hello!</title>
</head>

<body>
Date/Time examples<br><br>
<?php
/*

use date function date ( string $format [, int $timestamp = time() ] )

Use date('c',time()) as format to convert to ISO 8601 date (added in PHP 5) - 2012-04-06T12:45:47+05:30

use date("Y-m-d\TH:i:s\Z",1333699439) to get 2012-04-06T13:33:59Z

Here are some of the formats date function supports
*/

$today = date("F j, Y, g:i a");                 // March 10, 2001, 5:16 pm
$today = date("m.d.y");                         // 03.10.01
$today = date("j, n, Y");                       // 10, 3, 2001
$today = date("Ymd");                           // 20010310
$today = date('h-i-s, j-m-y, it is w Day');     // 05-16-18, 10-03-01, 1631 1618 6 Satpm01
$today = date('\i\t \i\s \t\h\e jS \d\a\y.');   // it is the 10th day.
$today = date("D M j G:i:s T Y");               // Sat Mar 10 17:16:18 MST 2001
$today = date('H:m:s \m \i\s\ \m\o\n\t\h');     // 17:03:18 m is month
$today = date("H:i:s");                         // 17:16:18

$now = time();
echo "now ".$now."<br><br>";

$fbBumpDate = date ('Y-m-d H:m:s', $now);
echo "now date ".$fbBumpDate."<br><br>";

$bumpDate = $now +60*60*24*7;
echo "bumpDate ".$bumpDate."<br><br>";

$fbBumpDate = date ('Y-m-d H:m:s', $bumpDate);
echo "fbBumpDate time stamp".$fbBumpDate."<br><br>";

$time = strtotime($fbBumpDate);
echo "fbBumpDate backwards ".$time."<br><br>";
?>
</body>
</html>