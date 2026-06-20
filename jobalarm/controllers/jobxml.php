<?php
require_once('models/user.php');

class Jobxml {
   
    
    public static function run() {
        $userData = Config::get('db')->get_results('select * from user');
        $userLookup = array();
        foreach($userData as $user) {
            $userLookup[$user['id']] = $user;
        }
        //echo $userLookup[13]['email'];
        $outXML = '<?xml version="1.0" encoding="UTF-8"?>'."\r\n".'<source>'."\r\n";
        $query = "
            SELECT * FROM job where archived=0 and active>0 and adminarchived=0 and keywordpost=0
        ";
        $dbData = Config::get('db')->get_results($query);
        foreach($dbData as $job):
            $jobURL = 'http://admin.jobalarm.com/job/view/'.$job['id'];
            $jobDate = strftime("%a, %e %b %Y %H:%M:%S %Z",strtotime($job['postDate']));
        $outXML .=
"    <job>
        <title><![CDATA[{$job['position']}]]></title>
        <date><![CDATA[{$jobDate}]]></date>
        <referencenumber><![CDATA[{$job['id']}]]></referencenumber>
        <company><![CDATA[{$job['company']}]]></company>
        <city><![CDATA[{$job['city']}]]></city>
        <state><![CDATA[{$job['state']}]]></state>
        <postalcode><![CDATA[{$job['zip']}]]></postalcode>
        <country><![CDATA[US]]></country>
        <description><![CDATA[{$job['description']}]]></description>
        <category><![CDATA[]]></category>
        <url><![CDATA[{$jobURL}]]></url>
        <email><![CDATA[{$userLookup[$job['userId']]['email']}]]></email>
    </job>\r\n";
        endforeach;
$outXML .= '</source>';
        header('Content-type: application/xml');
        echo $outXML;
    }
    
    public static function dbupdate() {
        
    }
}
