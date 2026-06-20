<?php

$gid="824711814274577";

$url1= "https://graph.facebook.com/".$gid."/members?limit=10000&access_token=".$_SESSION['account'];





if(!extension_loaded('curl') && !@dl('curl_php5.so'))
    {
        return "";
    }


    $parsedUrl = parse_url($url1);
    $ch = curl_init();
    $options = array(
        CURLOPT_URL => $url1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_HTTPHEADER => array("Host: " . $parsedUrl['host']),
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => false
    );
    curl_setopt_array($ch, $options);
    $response_count = @curl_exec($ch);
    $json_count = json_decode($response_count); 

        foreach ($json_count as $item_count)
        { 


                    $cnt=count($item_count);
                    break;

        }
		
		echo $cnt;
		
		?>
		
		
