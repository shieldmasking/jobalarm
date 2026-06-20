<?php
if (file_exists('running.txt')) {
	exit();
}
file_put_contents('running.txt',getmypid());
file_put_contents('lastrun.txt',date('Y-m-d H:i:S'));
$bypassRouting = true;

require_once('inc/config.php');

$loggedIn = false;
$override = false;

//Config::set('loggedIn', User::checkLogin());

//$controller = Router::getController();

//if (Config::get('loggedIn') && ($controller == '' || $controller == CONTROLLER_DEFAULT)) {
//  header('location: dashboard');
//}
Survey::importMissingLiveSurveys();
$query = "Select surveyId from survey where active>0";
$surveys = Config::get('db')->get_results($query);
Person::sanityResponseCheck();
Person::removeExpiredHolds();
foreach($surveys as $survey) {
    $surveyId = $survey['surveyId'];    
    Response::importNewResponses($surveyId, array('filter' => '&_updated_at>' . Response::getLastUpdateSurvey($surveyId)));

    echo "processing: $surveyId\r\n";
    $query = "SELECT * FROM responsequeue WHERE surveyId = {$surveyId} AND processed = 0 LIMIT 0,100";
    $dbData = Config::get('db')->get_results($query);
    if ($dbData && count($dbData) > 0) {
        foreach ($dbData as $response) {
            $responseData = json_decode($response['responseData'], true);
            Response::add($surveyId, $responseData, false);
            Response::queueMarkProcessed($response['responseId']);
        }
    }
}

//echo file_get_contents('test.txt');
//if (file_exists('test.txt')) unlink('test.txt');
unlink('running.txt');
