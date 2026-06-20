<?php

class NoteManager {
    
    public static function getNotesByResponse($responseId) {
        $query = "SELECT *,id AS recid FROM response where surveyResponseID={$responseId}";
        $dbData = Config::get('db')->get_results($query);
        if (count($dbData) > 0) {
            $noteslist = Utility::w2uiFormatResults(self::getNotesBySurvey($dbData[0]['surveyId'], $dbData[0]['peopleId']));
            for ($i = 0; $i < count($noteslist["records"]); $i++) {
                if (isset($noteslist["records"][$i]['username']) && strlen($noteslist["records"][$i]['username']) > 0) {
                    //$user = User::load($noteslist["records"][$i]['userId']);
                    //$noteslist["records"][$i]['username'] = $user['firstName'] . ' ' . $user['lastName'];
                } else
                    $noteslist["records"][$i]['username'] = 'unknown';
            }
            return $noteslist;
        }        
    }


    public static function getNotesByCandidate($candidateId) {
        if (Config::get('loggedIn')) {
			$userId = Config::get('loggedIn');
			$userx = (isset($_COOKIE['jobalarm_userId'])) ? $_COOKIE['jobalarm_userId'] : 0;
    		if ($userx == 0 && Router::getGetVar('u')) {
            $userx = Router::getGetVar('u');
			}
            
            $query = "
              SELECT 
                n.id as recid,
                n.id as id,
                n.accountId as userId,
				n.userId,
                n.noteBody as noteBody,
                DATE_FORMAT(n.noteDate,'%m/%d/%y %H:%i') as noteDate,
                n.noteType as noteType,
                u.fullName as username,
				z.last_name as recruiter
              FROM note n
              LEFT JOIN account u
                on u.id = n.accountId
			  LEFT OUTER JOIN users z
                on z.id = n.userId
              WHERE 
                n.candidateId = {$candidateId} and u.id = {$userId}
              AND
                n.active = 1
				
              ORDER by n.noteDate DESC
            ";
            $notes = Config::get('db')->get_results($query);
        } else $notes = false;
        
		foreach ($notes as $k => $v) {
            $notes[$k]['style'] = ($notes[$k]['noteType'] > 1) ? 'color:blue' : 'color:red';
        }
        $noteslist = Utility::w2uiFormatResults($notes);
        return $noteslist;        
    }

    public static function getNotesBySurvey($surveyId,$personId) {
        if (Config::get('loggedIn')) {
            //$user = User::load(Config::get('loggedIn'));
            //$companyId = $user['accountId'];
			$user = Config::get('loggedIn');
            
            $query = "
              SELECT 
                n.id as recid,
                n.id as id,
                n.candidateId as userId,
                n.noteBody as noteBody,
                DATE_FORMAT(n.noteDate,'%m/%d/%y %H:%i') as noteDate,
                n.noteType as noteType,
                CONCAT(u.firstName,' ',u.lastName) as username
              FROM note n
              LEFT JOIN candidate u
                on u.id = n.candidateId
              WHERE 
                n.accountId = {$user}
              and 
                n.candidateId = {$candidateId}
              AND
                n.active = 1
              ORDER by n.noteDate DESC
            ";
            $notes = Config::get('db')->get_results($query);
        } else $notes = false;
        
        return $notes;        
    }
    
    public static function addNote($accountId, $candidateId, $noteBody, $noteType = 1) {
        if ($candidateId > 0) {
			$query = "
              SELECT 
                u.accountId as accountid
              FROM users u
              WHERE 
                u.id = {$accountId}
             ";
            $acct = Config::get('db')->get_results($query);
            $account = $acct[0]['accountid'];
			
			$noteData = Array(
                'accountId' => $account,
                'candidateId' => Config::get('db')->filter($candidateId),
                'noteBody' => Config::get('db')->filter($noteBody),
                'noteDate' => date('Y-m-d H:i:s'),
                'noteType' => $noteType,
				'userId' => $accountId
            );
            if (Config::get('db')->insert('note', $noteData)) {
                return Config::get('db')->lastid();
            }
        } else {
            return false;
        }
        return NULL;        
    }
    
    public static function syncNotesToCompany() {
    
        $query = "update note n set companyId=(select companyId from user u where u.id=n.userId)";
        
        Config::get('db')->query($query);
        
        return true;
        
        
    }
    
}