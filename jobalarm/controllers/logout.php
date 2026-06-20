<?php
require_once('models/user.php');
class Logout {
    public static function run() {
        User::logout();
        //header('location: http://www.jobalarm.com/dashboard/index.php');
		header('location: http://www.jobalarm.com/logout.php');
    }
}
