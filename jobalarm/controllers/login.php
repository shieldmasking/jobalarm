<?php
require_once('models/user.php');
class Login {
    public static function run() {
        $record = isset($_REQUEST['record']) ? $_REQUEST['record'] : false;
        if ($record) {
            $username = $record['username'];
            $password = $record['password'];
            Config::set('loggedIn',User::login($username,$password));
            if (User::$_loggedIn) {
                User::load(User::$_loggedIn);
                echo json_encode(Array('status'=>'success','id'=>Config::get('loggedIn')));
                return true;
            }
        }
        echo json_encode(Array('status'=>'error','message'=>'Invalid Username and or Password'));
        return false;
    }
    public static function smslogin($accountId,$userId=0,$zip,$link) {
        if ($link == 0){
			$joblink = '';
		}else if ($link == 1){
			$joblink = "&m=".$link;
		}else{
			$joblink = "&l=http://www.jobalarm.com/ja.php?cx=1&id=".$link;
		}
		
		$accountId = $accountId/12345;
		$userId = $userId/54321;
		
		
		$query = "select * from account where id={$accountId}";
        $dbData = Config::get('db')->get_results($query);
		if ($dbData && count($dbData) > 0) {
            $account = $dbData[0];
            Config::set('loggedIn',User::quicklogin($account['email'],$account['password']));
            if (User::$_loggedIn) {
                User::load(User::$_loggedIn);
	            setcookie('jobalarm_userId',$userId,time()+(86400 * 30),'/','.jobalarm.com');
                //echo json_encode(Array('status'=>'success','id'=>Config::get('loggedIn')));
                header("location: ../globals?u=".$userId."&z=".$zip.$joblink);
				
                return true;
            }            
        }
    }
}
