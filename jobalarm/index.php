<?php
	error_reporting(-1);
	ini_set('display_errors', 'On');

	require_once 'inc/config.php';
	require_once 'inc/class.db.php';
	
    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }


$loggedIn = false;
$override = false;

Config::set('loggedIn', User::checkLogin());

$controller = Router::getController();

if (Config::get('loggedIn') && ($controller == '' || $controller == CONTROLLER_DEFAULT)) {
  header('location: dashboard');
}

Router::Process($override);

