<?php

/*
 * Left Msg, Contacted, Interviewed, Submitted, Offer Extended, Hired, Rejected
 */



class Group {

    public static function getAll($account){        
        
		$accountId = Config::get('loggedIn');
		
        $query = "SELECT * FROM `group` WHERE (accountId={$accountId} or accountId=0) AND active>0  ORDER BY id ASC";
        $dbData = Config::get('db')->get_results($query);
        return $dbData;
        $outData = array();
        foreach($outData as $group) {
            $outData[] = array($group['id'],$group['groupName']);
        }
        return $outData;
    }
    
    public static function getLookup($accountId = null) {
        $groups = Config::get('db')->get_results('select * from `group`');
        $lookup = array();
        if ($groups && count($groups) > 0) {
           foreach($groups as $group) {
               $lookup[$group['id']] = $group['groupName'];
           }
        }
        return $lookup;
    }
    
    public static function getName($groupId) {
        $query = "SELECT groupName FROM group WHERE id={$groupId}";
        $dbData = Config::get('db')->get_results($query);
        if (count($dbData) > 0) {
           return $dbData[0]['name'];
        }

        return 'None';
    }
    
    public static function updateCandidate($accountId,$candidateId,$inGroupId,$user) {
        //$groupId = self::exists($candidateId);
		$firstName = '';
		$lastName = '';
		$gname = '';
		
		if ($user){
		$userData = Config::get('db')->get_results("select * FROM `users` where id=$user");
		$firstName = substr($userData[0]['first_name'],1);
		$lastName = $userData[0]['last_name'];
		}
        /*if ($inGroupId == 0) {
            if ($groupId) {
                Config::get('db')->delete('candidate_group',array('id'=>$groupId),1);
            }
            return 0;
        } */
        if(intval($inGroupId)>0){
			$groupName = Config::get('db')->get_results("select * FROM `group` where id={$inGroupId}");
			$gname = $groupName[0]['groupName'];
		}
		
		//if ($groupId>0 && $inGroupId>0) {
        //    $data = array(
        //        'groupId'=>$inGroupId,
		//		'userId'=>$user,
        //       'groupdate'=>($inGroupId > 0) ? date('Y-m-d H:i:s') : null
        //        );
        //    $where = array('id'=>$groupId);
        //    Config::get('db')->update('candidate_group',$data,$where,1);
        //} 
		//if (!$groupId) {
			if (intval($inGroupId)>0){
            $data = array(
                'candidateId'=>$candidateId,
                'accountId'=>$accountId,
				'userId'=>$user,
                'groupId'=>$inGroupId,
                'groupdate'=>($inGroupId > 0) ? date('Y-m-d H:i:s') : null
                );
            Config::get('db')->insert('candidate_group',$data);
            $inGroupId = Config::get('db')->lastid();
			}
			//else{
			//$texted=13;
			//$gname="Texted";
            //$data = array(
            //    'candidateId'=>$candidateId,
            //    'accountId'=>$accountId,
			//	'userId'=>$user,
            //    'groupId'=>$texted,
            //    'groupdate'=>date('Y-m-d H:i:s')
            //    );
            //Config::get('db')->insert('candidate_group',$data);
            //$inGroupId = Config::get('db')->lastid();
			//}
		//}
		if ($inGroupId){
			$data = array(
                'candidateId'=>$candidateId,
                'accountId'=>$accountId,
				'userId'=>$user,
				'noteType'=>1,
				'active'=>1,
                'noteBody'=>"Group set to ".$gname,
                'noteDate'=>date('Y-m-d H:i:s')
                );
            Config::get('db')->insert('note',$data);
		}
        return $inGroupId;
    }
    
    public static function exists($candidateId) {
        $accountId = Config::get('loggedIn');
        $query = "SELECT id FROM candidate_group WHERE accountId={$accountId} AND candidateId={$candidateId}";
        $dbData = Config::get('db')->get_results($query);
        if (count($dbData) > 0) {
            return $dbData[0]['id'];
        }
        return false;        
    }
    
    public static function create($data) {
        Config::get('db')->insert('`group`',$data);
        return Config::get('db')->lastid();
    }
    
    public static function update($groupId,$data) {
        $where = array('id'=>$groupId);
        Config::get('db')->update('group',$data,$where);
        return true;
    }
    
    public static function delete($groupId) {
        $where = array('id'=>$groupId);
        $data = array('active'=>0);
        Config::get('db')->update('group',$data,$where);
        return true;
    }
    
}