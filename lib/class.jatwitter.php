<?php



/**

 * class short summary.

 *

 * class description.

 *

 * @version 1.0

 * @author setzor

 */

class JATwitter {

    private $_data;

    

    public static function LoadSearchData() {

        $query = "select * from city";

        $dbData = Config::get('db')->get_results($query);

        self::$_data['cities'] = $dbData;

    }

    

    public static function HashtagIsIndustry($hashtag) {

        $query = "select id from industry where name='{$hashtag}'";

        $dbData = Config::get('db')->get_results($query);

        return (count($dbData) > 0) ? $dbData[0]['id'] : false;    

    }

    

    public static function AddIndustryToJob($jobId,$industryId) {

        $data = array('jobId'=>$jobId,'industryId'=>$industryId,'dateSet'=>date('Y-m-d H:i:s'));

        Config::get('db')->insert('jobcat',$data);

    }

    

    public static function AddTweetIndustryToJob($jobId,$tweet) {

       foreach($tweet['entities']['hashtags'] as $hashtag) {

            $industryId = self::HashtagIsIndustry($hashtag['text']);

            if ($industryId) {

                self::AddIndustryToJob($jobId,$industryId);

            }

        }

    

    }



    public static function retroFixUser() {

        $query = "SELECT id,rawData FROM job where userName='' ";

        $numRows = Config::get('db')->num_rows($query);

        $pages = ceil($numRows / 500);

        for($i=0;$i<$pages;$i++) {

            $newQuery = $query." limit 0, 500";

            $dbData = Config::get('db')->get_results($newQuery);

            foreach($dbData as $job) {

                $jobId = $job['id'];

                $tweet = json_decode($job['rawData'],true);

                $user = $tweet['user'];

                $screen_name = $user['screen_name'];

                Config::get('db')->update('job',array('userName'=>$screen_name),array('id'=>$job['id']),1);

                echo $screen_name."<br />";

            }

        }

    }

    

    public static function retroFixIndustry() {

        $query = "SELECT id,hashTags FROM job ";

        $numRows = Config::get('db')->num_rows($query);

        $pages = ceil($numRows / 500);

        for($i=0;$i<$pages;$i++) {

            $newQuery = $query." limit ".($i*500).", 500";

            $dbData = Config::get('db')->get_results($newQuery);

            foreach($dbData as $job) {

                $jobId = $job['id'];

                $hashTags = explode(',',$job['hashTags']);

                foreach($hashTags as $hashTag) {               

                    $industryId = self::HashtagIsIndustry($hashTag);

                    if ($industryId) {

                        self::AddIndustryToJob($jobId,$industryId);

                    }

                }

            }

        }

    }

    

    public static function retroFixCategory() {

        $query = "SELECT id,hashTags FROM job where postDate >= date_sub('".date('Y-m-d H:i:s')."',interval 24 hour)";

        $numRows = Config::get('db')->num_rows($query);

        $pages = ceil($numRows / 500);

        for($i=0;$i<$pages;$i++) {

            $newQuery = $query." limit ".($i*500).", 500";

            $dbData = Config::get('db')->get_results($newQuery);

            foreach($dbData as $job) {

                $hashTags = explode(',',$job['hashTags']);

                foreach($hashTags as $hashTag) {               

                    $updatequery = "update category set count=count+1 where hashTag = '{$hashTag}';";

                    Config::get('db')->query($updatequery);

                }

            }

        }

    }


   
    public static function TweetExists($tweet) {

        $dbLink = Config::get('db')->getLink();

        if ($stmt = $dbLink->prepare("SELECT id FROM job WHERE twitterId=?")) {



            /* bind parameters for markers */

            $stmt->bind_param("s", $tweet['id_str']);



            /* execute query */

            $stmt->execute();



            /* bind result variables */

            //$stmt->bind_result($id);



            /* fetch value */

            $stmt->fetch();



            $tweetExists = ($stmt->num_rows > 0);



            /* close statement */

            $stmt->close();

            

            return $tweetExists;

        }        

        return false;
		

        //$tweetText = Config::get('db')->filter($tweet['text']);

        //$query = "

        //    SELECT id FROM job WHERE twitterId={$tweet['id_str']}

        //    OR text='{$tweetText}'

        //";

        //$dbData = Config::get('db')->get_results($query);

        //return (count($dbData) > 0) ? $dbData[0]['id'] : false;        

    }

    

    public static function CategoryExists($name) {

        $query = "SELECT id FROM category WHERE hashTag='{$name}'";

        $dbData = Config::get('db')->get_results($query);

        return (count($dbData) > 0) ? $dbData[0]['id'] : false;

    }

    

    public static function CreateCategory($name) {

        $data = array('hashTag'=>$name,'created'=>date('Y-m-d H:i:s'));

        Config::get('db')->insert('category',$data);

        return Config::get('db')->lastid();

    }

    

    public static function DeactivateCategory($id) {

        $data = array('status'=>0);

        $where = array('id'=>$id);

        Config::get('db')->update('job',$data,$where,1);

    }

        

    public static function AssignCategory($jobId,$categoryId) {

        $data = array('category'=>$categoryId);

        $where = array('id'=>$jobId);

        Config::get('db')->update('job',$data,$where,1);

    }

    

    public static function UpdateJobFromTweet($jobId,$tweet) {

        $hashtags = '';

        if (isset($tweet['entities']) && isset($tweet['entities']['hashtags']) && count($tweet['entities']['hashtags']) > 0) {



            $hashtagarray = array();

            foreach($tweet['entities']['hashtags'] as $hashtag) {

                $hashtagarray[] = $hashtag['text'];

                if (!self::CategoryExists($hashtag['text'])) {

                    self::CreateCategory($hashtag['text']);

                }

            }

            $hashtags = implode(',',$hashtagarray);



        }



        // echo json_encode($matches);





        $urls = '';

        if (isset($tweet['entities']) && isset($tweet['entities']['urls']) && count($tweet['entities']['urls']) > 0) {



            $urlarray = array();

            foreach($tweet['entities']['urls'] as $url) {

                $urlarray[] = $url['expanded_url'];

            }

            $urls = implode(',',$urlarray);



        }

        $tweetData = array(

            'twitterId' => $tweet['id_str'],

            'postDate' => date('Y-m-d H:i:s',(int)substr($tweet['timestamp_ms'],0,10)),

            'text' => Config::get('db')->filter($tweet['text']),

            'hashTags'=>$hashtags,

            'urls'=>$urls,

            'rawData'=>Config::get('db')->filter(json_encode($tweet))

            );

        

        $where = array('id'=>$jobId);

        

        Config::get('db')->update('job',$tweetData,$where,1);

    }



    public static function CleanString($string) {

        return preg_replace('/[\x00-\x1F\x80-\xFF]/', ' ', $string);

    }

        

    public static function RemoveURLs($string) {

        return preg_replace('/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', '', $string); 

    }

    

    

    public static function FindState($string) {

        preg_match_all('/\b(AK|AL|AR|AZ|CA|CO|CT|DC|DE|FL|GA|HI|IA|ID|IL|IN|KS|KY|LA|MA|MD|ME|MI|MN|MO|MS|MT|NC|ND|NE|NH|NJ|NM|NV|NY|OH|OK|OR|PA|RI|SC|SD|TN|TX|UT|VA|VT|WA|WI|WV|WY)\b/',$string,$matches);

        if (is_array($matches) && count($matches) > 0 && count($matches[0]) > 0) {

            return $matches[0][count($matches[0])-1];

        }

        return false;

    }

    

    public static function FindCity($string,$state) {

        $string = addslashes($string);

        $sql = "

            SELECT * FROM city

            WHERE '{$string}' REGEXP CONCAT('(^|[\s]|[^a-zA-Z]|\b)',city,'([^a-zA-Z]|\s|$)') and state_code='{$state}'

            OR '{$string}' REGEXP CONCAT('(^|[\s]|[^a-zA-Z]|\b)',REPLACE(city,' ',''),'([^a-zA-Z]|\s|$)') and state_code='{$state}'

            OR '{$string}' REGEXP CONCAT('(^|[\s]|[^a-zA-Z]|\b)',REPLACE(REPLACE(city,'Saint','St'),' ',''),'([^a-zA-Z]|\s|$)') and state_code='{$state}'

            order by city desc

        ";

        $dbData = Config::get('db')->get_results($sql);

        return (count($dbData) > 0) ? $dbData[0] : false;

    }

    

    public static function GetLocation($string,$requireboth=true) {

        $state = self::FindState($string);

        $result_state = false;

        $result_city = false;

        if (!$state && $requireboth) {

            return false;

        } else {

            $result_state = $state;

        }

        $city = self::FindCity($string,$state);

        if (!$city && $requireboth) {

            return false;

        } else {

            $result_city = $city['city'];

        }

        

        return array('city'=>$result_city,'state'=>$result_state);        

    }





//Employer tweets functions    

    public static function GetTweets($count,$params=array()) {

        return Config::get('mongodb')->find($params)->limit($count);

    }

    

    public static function RemoveTweet($tweetId) {

        return Config::get('mongodb')->remove(array('id_str'=>$tweetId));

    }



    public static function RemoveTweets($tweetId) {

        if ($tweetId)

        return Config::get('mongodb')->remove(array('_id'=>array('$lte'=>new MongoId($tweetId))));

    }



    public static function ImportTweet($tweet,$city,$state) {

        $hashtags = '';

        $catarray = array();

        if (isset($tweet['entities']) && isset($tweet['entities']['hashtags']) && count($tweet['entities']['hashtags']) > 0) {



            $hashtagarray = array();

            foreach($tweet['entities']['hashtags'] as $hashtag) {

                $hashtagarray[] = $hashtag['text'];

                $catId = self::CategoryExists($hashtag['text']); 

                if (!$catId) {

                    $catId = self::CreateCategory($hashtag['text']);

                }

                $catarray[] = $catId;

            }

            $hashtags = implode(',',$hashtagarray);



        }



        // echo json_encode($matches);





        $urls = '';

        if (isset($tweet['entities']) && isset($tweet['entities']['urls']) && count($tweet['entities']['urls']) > 0) {



            $urlarray = array();

            foreach($tweet['entities']['urls'] as $url) {

                $urlarray[] = $url['expanded_url'];

            }

            $urls = implode(',',$urlarray);



        }

        $timestamp = (int)$tweet['timestamp_ms'];

        $postDateStamp = $timestamp / 1000;

        $tweetData = array(

            'twitterId' => $tweet['id_str'],

            'postDate' => date('Y-m-d H:i:s',$postDateStamp),

            'text' => Config::get('db')->filter($tweet['text']),

            'city'=>$city,

            'state'=>$state,

            'hashTags'=>$hashtags,

            'urls'=>$urls,

            'rawData'=>Config::get('db')->filter(json_encode($tweet)),

            'userName'=>$tweet['user']['screen_name']

            );

        //var_dump($tweetData);

        if (Config::get('db')->upsert('job',$tweetData,$tweetData)) {

            $lastId = Config::get('db')->lastid();

            if ($lastId) {

                foreach($catarray as $cat) {

                    $data = array('jobId'=>$lastId,'categoryId'=>$cat);

                    Config::get('db')->insert('jobtag',$data);

                }

            }

            return $lastId;

        } 

    }

    

//Employer tweets functions    

    public static function GetSeekerTweets($count,$params=array()) {

        return Config::get('seekerdb')->find($params)->limit($count);

    }

    

    public static function RemoveSeekerTweet($tweetId) {

        return Config::get('seekerdb')->remove(array('id_str'=>$tweetId));

    }



    public static function RemoveSeekerTweets($tweetId) {

        if ($tweetId)

        return Config::get('seekerdb')->remove(array('_id'=>array('$lte'=>new MongoId($tweetId))));

    }

        

    public static function ImportSeekerTweet($tweet) {

        $hashtags = '';

        $city = '';

        $state = '';

        if (isset($tweet['entities']) && isset($tweet['entities']['hashtags']) && count($tweet['entities']['hashtags']) > 0) {



            $hashtagarray = array();

            foreach($tweet['entities']['hashtags'] as $hashtag) {

                $hashtagarray[] = $hashtag['text'];

            }

            $hashtags = implode(',',$hashtagarray);



        }



        // echo json_encode($matches);





        $urls = '';

        if (isset($tweet['entities']) && isset($tweet['entities']['urls']) && count($tweet['entities']['urls']) > 0) {



            $urlarray = array();

            foreach($tweet['entities']['urls'] as $url) {

                $urlarray[] = $url['expanded_url'];

            }

            $urls = implode(',',$urlarray);



        }

        $timestamp = (int)$tweet['timestamp_ms'];

        $postDateStamp = $timestamp / 1000;

        $tweetData = array(

            'twitterId' => $tweet['id_str'],

            'postDate' => date('Y-m-d H:i:s',$postDateStamp),

            'text' => Config::get('db')->filter($tweet['text']),

            'city'=>$city,

            'state'=>$state,

            'hashTags'=>$hashtags,

            'urls'=>$urls,

            'rawData'=>Config::get('db')->filter(json_encode($tweet)),

            'userName'=>$tweet['user']['screen_name']

            );
        $tweetUpdateData = $tweetData;
        unset($tweetUpdateData['postDate']);
        //var_dump($tweetData);

        if (Config::get('db')->upsert('seeker',$tweetData,$tweetUpdateData)) {

            return Config::get('db')->lastid();

        } 

    }

    



}

