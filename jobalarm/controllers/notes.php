<?php

class Notes {
    public static function run() {
    
    }
    
    public static function getCandidateNotes($candidateId) {
        $notes = NoteManager::getNotesByCandidate($candidateId);
        echo json_encode($notes);
    }

    public static function getResponseNotes($responseId) {
        //$notes = NoteManager::getNotesByResponse($responseId);
		$notes = NoteManager::getNotesByCandidate($responseId);
        echo json_encode($notes);
    }
    
    public static function sync() {
    
        NoteManager::syncNotesToCompany();
        
        echo json_encode(array('success'=>true));
    
    }
    
    public static function add() {
        $candidateId = $_REQUEST['candidateId'];
        $record = $_REQUEST['record'];
        $noteBody = $record['notebody'];
        $noteType = $_REQUEST['notetype'];
		$userx = (isset($_COOKIE['jobalarm_userId'])) ? $_COOKIE['jobalarm_userId'] : 0;
    		if ($userx == 0 && Router::getGetVar('u')) {
            $userx = Router::getGetVar('u');
			}
		$result = NoteManager::addNote($userx,$candidateId,$noteBody,$noteType);
        //$result = NoteManager::addNote(Config::get('loggedIn'),$candidateId,$noteBody,$noteType);
        if ($result) {
            echo json_encode(array('status'=>"success"));            
        } else {
            echo json_encode(array('status'=>'error','message'=>'User not logged in'));
        }
    }
    
    public static function delete() {
            
    }
}