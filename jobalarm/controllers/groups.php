<?php


class Groups {
	//DO IT!
	public static function run() {
		$id = Group::create(array('accountId'=>175,'groupName'=>'test1'));
		echo $id;
	}	

	public static function add($accountId) {
		$groupName = isset($_REQUEST['groupName']) ? $_REQUEST['groupName'] : '';
		if ($groupName != '') {
			$newGroup = array('accountId'=>$accountId,'groupName'=>$groupName);
			$newId = Group::create($newGroup);
			echo json_encode(array('status'=>'success','newid'=>$newId));			
		} else
			echo json_encode(array('status'=>'error','newid'=>'-1'));
	}

	public static function get($accountId) {

		$groups = Group::getAll($accountId);
		$outData = array('status'=>'success','records'=>$groups);

		echo json_encode($outData);
	}


}