<?php

class People {
 
    public static function run() {
    }
    
    
    public static function update($data) {
    
    }
    
    public static function setHold() {
        $people = $_REQUEST['people'];
        $from = $_REQUEST['from'];
        foreach($people as $responseId) {
            if ($from == 'smsview') {
                $responseId = Smsmanager::getResponseId($responseId);
            }
            $mobileNum = Response::getMobileNum($responseId);
            if ($mobileNum) {
                //echo $mobileNum;
                Person::setHold($mobileNum);
            }
        }
        
    }
    
    public static function getProfile($mobileNum) {
        if (strlen($mobileNum)) {
            $person = Person::read($mobileNum);
            if ($person) {
                $profilePic = Config::get('base_url')."img/no-photo.png";
                if (strlen($person['picture']) > 0 && file_exists('dat/userfiles/'.$person['personId'].'/'.$person['picture'])) {
                    $profilePic = 'dat/userfiles/'.$person['personId'].'/'.$person['picture'];
                }
                echo <<<HTML
     <div class="profileView">
        <img src="{$profilePic}" alt="profile photo" />            
     </div>   
HTML;
                return true;
            }
            echo "Profile Not Found.";
        } else {
            echo "Please select a person to view.";
        }
    }
}