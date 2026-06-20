<?php

class Brand {

	public static function run() {

	}

	public static function getBrands($accountId) {
        // $dbconn = mysqli_connect(DB_HOST,DB_USER,DB_PASS);
        // mysqli_select_db($dbconn,'tweetedj_tweetedjobs');
        //$query = "select * from candidate where account={$accountId} and promo=1";
        //$query = "SELECT  
        //DISTINCT s.keyword as brand
        //FROM `candidate` as c 
        /*LEFT JOIN `candidateXref` as x on x.candidateId = c.id*/ 
        //LEFT JOIN `account` as a on a.id = {$accountId}
        //LEFT JOIN `sms_brand` as s on s.id = a.brandId
        //WHERE (c.active = 1 and c.mobile != '') and (a.id={$accountId} or a.id=212)
        //GROUP BY s.storeBrand
        //ORDER BY s.storeBrand ASC ";
		$query = "SELECT  
        DISTINCT s.keyword as brand
        FROM `sms_brand` as s
        LEFT JOIN `account_brand` as a on a.brandId=s.id
        WHERE a.accountId={$accountId}
        GROUP BY s.storeBrand
        ORDER BY s.storeBrand ASC";
        // $result = mysqli_query($dbconn,$query);
        $dbData = Config::get('db')->get_results($query);
        $dataArray = array();
        if ($dbData && count($dbData) > 0)
            foreach($dbData as $k=>$row) {
                $dataArray[] = array(
    				'brand' => $row['brand']
                );
            }

        $outArray = array(
            'status' => 'success',
            'total' => count($dataArray),
            'records' => $dataArray
        );
        echo json_encode($outArray);        


	}

}