<?php

class Recruiters {

	public static function run() {

	}

	public static function getRecruiters($accountId) {
      
		$query = "SELECT  
        u.first_name as firstName, u.last_name as lastName
        FROM `users` as u
        LEFT JOIN `account` as a on a.id = u.accountId
        WHERE a.id={$accountId}
        ORDER BY u.last_name ASC";
        // $result = mysqli_query($dbconn,$query);
        $dbData = Config::get('db')->get_results($query);
        $dataArray = array();
        if ($dbData && count($dbData) > 0)
            foreach($dbData as $k=>$row) {
                $dataArray[] = array(
    				'firstName' => $row['firstName'],
					'lastName' => $row['lastName']
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