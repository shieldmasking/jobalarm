<?php

class CQuestionFS {

  public $_id = null;
  public $_label = null;
  public $_type = null;
  public $_choices = null;
  public $_answer = null;
  public $_answerText = null;

  public function __construct($data) {
    $this->create($data);
  }

  public function create($data) {
    $this->_id = $data['id'];
    $this->_label = $data['label'];
    $this->_type = $data['type'];
    if (isset($data['choices'])) {
      $this->_choices = $data['choices'];
    }
  }

  public function setAnswer($answer) {
    $this->_answer = $answer;
  }

  public function setAnswerText($answerText) {
    $this->_answerText = $answerText;
  }

  public function getType() {
    return $this->_type;
  }

  public function getAnswerValue() {
    switch ($this->_type) {
      case 'hidden':
      case 'string':
      case 'file':
      case 'boolean':
      case 'datetime':
      case 'single':
      case 'single-meta':
      default:
        if (is_array($this->_answer)) {
          return json_encode($this->_answer);
        }
        break;
      case 'multi':
        if (is_array($this->_answer)) {
          return json_encode($this->_answer);
        }
        break;
    }
    return $this->_answer;
  }

  public function getAnswerText($choice = null) {
    if (!isset($choice)) {
      if (isset($this->_answerText)) {
        $choice = $this->_answerText;
      } else
      if (isset($this->_answer)) {
        $choice = $this->_answer;
      }
    }

    if (isset($choice)) {
      switch ($this->_type) {
        case 'hidden':
        case 'string':
        case 'file':
          if (is_array($choice)) {
            return implode(', ', $choice);
          } else {
            return $choice;
          }
          break;
        case 'single':
        case 'single-meta':
          if (isset($this->_choices) && is_array($this->_choices)) {
            if (is_array($choice) && count($choice) > 0) {
              if (isset($this->_choices[$choice[0]]['label'])) {
                  return $this->_choices[$choice[0]]['label'];
              }
            } else
            if (!is_array($choice)) {
                if (isset($this->_choices[$choice]['label'])) {
                    return $this->_choices[$choice]['label'];
              }
            }
          } else {
            Log::add("Error in getAnswerText single type : id {$this->_id}, label:{$this->_label}, choice:{" . print_r($choice, true) . "}", LOG_TYPES::SURVEY, LOG_SEVERITIES::CRITICAL);
          }
          break;
        case 'multi':
          $choices = $choice;

          $outAnswers = array();
          if (isset($this->_choices) && is_array($this->_choices)) {
              if (is_array($choices) && count($choices) > 0 && !is_array($choices[0])) {
              foreach ($choices as $k => $v) {
                  if (isset($this->_choices[$v]['label'])) {
                      $outAnswers[] = $this->_choices[$v]['label'];
                }
              }
              return implode(', ', $outAnswers);
            } else if (isset($choices[0]) && is_array($choices[0])) {
              foreach ($choices[0] as $k => $v) {
                  if (isset($this->_choices[$v]['label'])) {
                      $outAnswers[] = $this->_choices[$v]['label'];
                }
              }
              return implode(', ', $outAnswers);
            } else if (isset($choices) && is_array($choices) && count($choices) == 0) {
              
                  //whut
                  return '';
                  
              } else {
                try {
                    $outValue = (isset($this->_choices[$choice]['label'])) ? $this->_choices[$choice]['label'] : ''; 
                } catch (Exception $e) {
                    $debugLog = 'EXCEPTION: '.$e->getMessage()."\r\n";
                    $debugLog .= print_r($this,true);
                    file_put_contents('getAnswerTextError.txt',$debugLog,FILE_APPEND);
                    $outValue = '';
                }
                return $outValue;
            }
            //    else {
            //        try {
            //            if (isset($choices[0])) {
            //                if (isset($this->_choices[$choices[0]])) {
            //                    return $this->_choices[$choices[0]];
            //                }
            //            }
            //        } catch (Exception $e) {
            //            Log::add("Error in getAnswerText multi type : id {$this->_id}, label:{$this->_label}, choice:{".print_r($choices,true)."} :: ".$e->getMessage(),LOG_TYPES::SURVEY,LOG_SEVERITIES::CRITICAL);
            //        }
            //    }
            //} else {
            Log::add("Error in getAnswerText multi type : id {$this->_id}, label:{$this->_label}, choice:{" . print_r($choices, true) . "}", LOG_TYPES::SURVEY, LOG_SEVERITIES::CRITICAL);
          }
          break;
        case 'boolean':
          if ($choice == '1' || strtolower($choice) == 'true' || strtolower($choice) == 'yes') {
            return 'True';
          }
          if ($choice == '0' || strtolower($choice) == 'false' || strtolower($choice) == 'no') {
            return 'False';
          }
          break;
        case 'datetime':
          return substr(str_replace('T', ' ', $choice), 0, 19);
          break;
        default:
          return '';
          break;
      }
    }
    return '';
  }

}

class CQuestion {

  public $_id = null;
  public $_name = null;
  public $_displayName = null;

  public function create($data) {
    $this->_id = $data['id'];
    $this->_name = $data['name'];
    $this->_displayName = $data['displayname'];
  }

}

class SystemSurvey {

  private $_status;
  private $_responses;
  private $_creator;
  private $_updated_at;
  private $_deploy_uri;
  private $_responses_url;
  private $_id;
  private $_name;
  private $_created_at;
  private $_uri;
  private $_report_url;
  private $_edit_url;
  private $_raw_config;

  public function __construct($indata) {
    $this->setData($indata);
  }

  private function setData($indata) {
    $this->_status = $indata['status'];
    $this->_responses = $indata['responses'];
    $this->_creator = $indata['creator'];
    $this->_updated_at = $indata['updated_at'];
    $this->_deploy_uri = $indata['deploy_uri'];
    $this->_responses_url = $indata['responses_url'];
    $this->_id = $indata['id'];
    $this->_name = $indata['name'];
    $this->_created_at = $indata['created_at'];
    $this->_uri = $indata['uri'];
    $this->_report_url = $indata['report_url'];
    $this->_edit_url = $indata['edit_url'];
    $this->_raw_config = json_encode($indata);
  }

  public function getStatus() {
    return $this->_status;
  }

  public function getResponseCount() {
    return $this->_responses;
  }

  public function getCreator() {
    return $this->_creator;
  }

  public function getLastUpdate($format = 'Y-m-d H:i:s') {
    $intime = str_replace('T', ' ', $this->_updated_at);
    $timestamp = strtotime($intime);
    $outtime = date($format, $timestamp);
    return $outtime;
  }

  public function getId() {
    return $this->_id;
  }

  public function getName() {
    return $this->_name;
  }

  public function getRaw() {
    return $this->_raw_config;
  }

  public function load($surveyId) {
    $query = "SELECT * FROM survey WHERE surveyId={$surveyId} AND active>0";
    $dbData = Config::get('db')->get_results($query);
    if (count($dbData) > 0) {
      $this->setData(json_decode($dbData[0]['rawConfig']));
    }
  }

}

class Survey {

  public static $_loaded = false;
  public static $_surveyId = null;
  public static $_projectId = null;
  public static $_companyId = null;
  public static $_name = null;
  public static $_description = null;
  public static $_questions = null;
  public static $_displayView = null;
  public static $_filtersView = null;
  public static $_editView = null;
  public static $_smsView = null;
  public static $_configData = null;
  public static $_lastUpdated = null;
  public static $_importedDate = null;
  public static $_live_surveys = null;
  public static $_response_columns = null;

  //Send data request to the survey site API
  public static function _getSurveyWebData($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_USERPWD, FS_APIKEY . ':' . FS_PASSWORD);
    curl_setopt($curl, CURLOPT_SSLVERSION, 3);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
    curl_setopt($curl, CURLOPT_URL, $url);
    $data = curl_exec($curl);
    curl_close($curl);
    return $data;
  }

  //Get details of a survey from the survey site API
  public static function getLiveSurveyDetails($surveyId) {
    $jsonData = self::_getSurveyWebData(FS_URL . $surveyId . '/');
    $returndata = null;
    if (strlen($jsonData) > 0)
      $returndata = json_decode($jsonData, true);
    return $returndata;
  }

  //Get all local db surveys
  public static function getAllDBSurveys() {
    $query = "select * from survey where active > 0";
    $dbData = Config::get('db')->get_results($query);
    return $dbData;
  }

  //Download all surveys info from survey server
  public static function getAllLiveSurveys() {
    try {
      $jsonData = self::_getSurveyWebData(FS_URL);
      $jsonObject = json_decode($jsonData, true);
      self::$_live_surveys = array();
      for ($i = 0; $i < count($jsonObject['surveys']); $i++) {
        array_push(self::$_live_surveys, new SystemSurvey((array) $jsonObject['surveys'][$i]));
      }
    } catch (Exception $e) {
      //self::log('Unable to download all surveys: ' . $e->getMessage(), LOG_TYPES::SURVEY, LOG_SEVERITIES::WARNING);
    }
    return self::$_live_surveys;
  }

  public static function read($surveyId = null) {
    $whereAdd = '';

    if ($surveyId) {
      $whereAdd = " AND surveyId = {$surveyId} ";
    }

    $query = "SELECT * FROM survey WHERE active > 0 {$whereAdd}";

    $dbData = Config::get('db')->get_results($query);

    if ($dbData && count($dbData) > 0) {
      if ($surveyId) {
        return $dbData[0];
      } else {
        return $dbData;
      }
    }
    
    return null;
  }

  public static function getList($isAdmin = false) {
    $sqlAdd = '';
    if (!$isAdmin) {
        $accessList = Company::getSurveyAccessString(User::getData('companyId'));
      $sqlAdd = " AND surveyId IN (" . $accessList . ") ";
    }
    $noblanks = Router::getGetVar('noBlanks');
    $query = "
            SELECT id, surveyId, companyId, name, lastUpdated, active
            FROM survey
            WHERE active > 0
            {$sqlAdd}
            ORDER BY name ASC
          ";
    $dbData = Config::get('db')->get_results($query);

    $dataArray = array();
    if ($dbData && count($dbData) > 0) {
      foreach ($dbData as $survey) {
        $surveyResponseCount = Response::getCount($survey['surveyId'], array('surveyOnly' => 1));
        $smsResponseCount = Response::getCount($survey['surveyId'], array('smsOnly' => 1));
        $totalResponseCount = $surveyResponseCount + $smsResponseCount;
        if (!$noblanks || ($noblanks && $totalResponseCount > 0)) {
          $dataArray[] = array(
              'recid' => $survey['id'],
              'surveyid' => $survey['surveyId'],
              'companyid' => $survey['companyId'],
              'sname' => '<a href="surveys?sid=' . $survey['surveyId'] . '">' . $survey['name'] . "</a>",
              'name' => $survey['name'],
              'responses' => $surveyResponseCount,
              'smsresponses' => $smsResponseCount,
              'totalresponses' => $totalResponseCount,
              'updated' => $survey['lastUpdated'],
              'admin' => '<button onclick="wus.openSurveyManager(' . $survey['surveyId'] . ',\'' . $survey['name'] . '\')">Manage</button> &nbsp;&nbsp;&nbsp; <button onclick="wus.showEmpRefs(' . $survey['surveyId'] . ',\'' . $survey['name'] . '\')">Reports</button>',
              'reports' => '<button onclick="window.location=\'reports?sid=' . $survey['surveyId'] . '\'">Reports</button>'
          );
        }
      }
    }
    $outArray = array(
        'status' => 'success',
        'total' => max(0, count($dataArray)),
        'records' => $dataArray
    );
    echo json_encode($outArray);
  }

  public static function getQuestionInsertFields($surveyId = null) {
    if (!($surveyId > 0))
      return false;
    if (!self::$_loaded && $surveyId) {
      self::load($surveyId);
    }

    $outFieldsArray = array('responseId' => '');

    if (self::$_questions && count(self::$_questions) > 0) {
      foreach (self::$_questions as $question) {
        if (!isset(self::$_response_columns['TMPTBL' . $question->_id])) {
          Response::addFieldToSec($surveyId, $question->_id, $question->_type);
        }
        $outFieldsArray['TMPTBL' . $question->_id] = '';
        $outFieldsArray['TMPTBLDisplay' . $question->_id] = '';
      }
    }
    return $outFieldsArray;
  }

  public static function load($surveyId) {
    if (!$surveyId)
      return null;
    self::$_loaded = false;
    self::$_surveyId = null;
    self::$_projectId = null;
    self::$_companyId = null;
    self::$_name = null;
    self::$_description = null;
    self::$_questions = null;
    self::$_displayView = null;
    self::$_filtersView = null;
    self::$_editView = null;
    self::$_smsView = null;
    self::$_configData = null;
    self::$_lastUpdated = null;
    self::$_importedDate = null;
    self::$_live_surveys = null;
    self::$_response_columns = null;
    $survey = self::read($surveyId);
    if ($survey) {
      ///////  LOAD SURVEY VARS AND CONFIG   ///////
      self::$_name = $survey['name'];
      self::$_description = $survey['description'];
      self::$_surveyId = $survey['surveyId'];
      self::$_projectId = $survey['projectId'];
      self::$_companyId = $survey['companyId'];
      self::$_lastUpdated = $survey['lastUpdated'];
      self::$_importedDate = $survey['importedDate'];

      $configData = json_decode($survey['configData'], true);
      self::$_configData = $configData;

      /////// LOAD QUESTIONS ///////
      $questionData = json_decode($survey['questions'], true);
      if (is_array($questionData) && count($questionData) > 0) {
        foreach ($questionData as $k => $v) {
          $v['id'] = $k;
          self::$_questions[$k] = new CQuestionFS($v);
        }
      }

      ////////  LOAD VIEWS  ///////
      //DISPLAY
      $displayView = json_decode($survey['displayView'], true);
      $displayRecords = $displayView['records'];
      if (is_array($displayRecords) && count($displayRecords) > 0) {
        foreach ($displayRecords as $k => $v) {
          self::$_displayView[$k] = $v;
        }
      }

      //FILTERS
      $filtesrView = json_decode($survey['filtersView'], true);
      $filtersRecords = $filtesrView['records'];
      if (is_array($filtersRecords) && count($filtersRecords) > 0) {
        foreach ($filtersRecords as $k => $v) {
          self::$_filtersView[$k] = $v;
        }
      }

      //EDIT
      $editView = json_decode($survey['editView'], true);
      $editRecords = $editView['records'];
      if (is_array($editRecords) && count($editRecords) > 0) {
        foreach ($editRecords as $k => $v) {
          self::$_editView[$k] = $v;
        }
      }

      //SMS
      $smsView = json_decode($survey['smsView'], true);
      $smsRecords = $smsView['records'];
      if (is_array($smsRecords) && count($smsRecords) > 0) {
        foreach ($smsRecords as $k => $v) {
          self::$_smsView[$k] = $v;
        }
      }

      //LOAD RESPONSE COLUMNS
      $columns = Config::get('db')->get_results('show columns from survey' . $surveyId);
      if ($columns && count($columns) > 0) {
        foreach ($columns as $column) {
          self::$_response_columns[$column['Field']] = $column['Type'];
        }
      }

      self::$_loaded = $surveyId;
      return true;
    }
    self::$_loaded = false;
    return false;
  }

  public static function unload() {
    self::$_loaded = false;
  }

  public static function getConfigData($surveyId = null) {
    if ($surveyId) {
      self::load($surveyId);
    }
    if (self::$_loaded) {
      return self::$_configData;
    }
    return false;
  }

  public static function getResponseTemplate($surveyId = null) {
    if (!self::$_loaded && $surveyId) {
      self::load($surveyId);
    }
    if (self::$_loaded) {
      $outArray = array();
      foreach (self::$_questions as $k => $v) {
        $outArray[$k] = '';
      }
      return $outArray;
    }
    return false;
  }

  public static function setAnswers($answerData, $answerSMS = null, $answerEdit = null) {
    if ($answerData && count($answerData) > 0) {
      foreach ($answerData as $k => $v) {
        if (isset(self::$_questions[$k])) {
          self::$_questions[$k]->setAnswer($v);
        }
      }
    }
    if ($answerSMS && count($answerSMS) > 0) {
      foreach ($answerSMS as $k => $v) {
        if (isset(self::$_questions[$k])) {
          self::$_questions[$k]->setAnswerText($v);
        }
      }
    }
    if ($answerEdit && count($answerEdit) > 0) {
      foreach ($answerEdit as $k => $v) {
        if (isset(self::$_questions[$k])) {
          self::$_questions[$k]->setAnswer($v);
          self::$_questions[$k]->setAnswerText($v);
        }
      }
    }
  }

  public static function create($data) {
    Config::get('db')->insert('survey', $data);
    return Config::get('db')->lastid();
  }

  public static function update($surveyId, $data) {
    $where = array('surveyId' => $surveyId);
    Config::get('db')->update('survey', $data, $where, 1);
  }

  public static function delete($surveyId) {
    $where = array('surveyId' => $surveyId);
    Config::get('db')->delete('survey', $where, 1);
  }

  public static function exists($surveyId) {
    $query = "SELECT surveyId FROM survey WHERE surveyId={$surveyId}";
    $dbData = Config::get('db')->get_results($query);
    if ($dbData && count($dbData) > 0) {
      return $surveyId;
    }
    return false;
  }

  //Create MySQL table for survey
  public static function createAnswerTable($surveyId) {
    if (!self::$_loaded && $surveyId) {
      self::load($surveyId);
    }
    if (!self::$_questions && !is_array(self::$_questions) && !count(self::$_questions) > 0) {
      return false;
    }
    Config::get('db')->query("DROP TABLE IF EXISTS survey{$surveyId}");
    $createQuery = "
      CREATE TABLE survey{$surveyId} (
          id INT(11) NOT NULL AUTO_INCREMENT,
          responseId INT(11) NOT NULL,
    ";
    $createArray = Array();

    $createArray[] = "TMPTBLviewed TINYINT(2) NOT NULL";

    foreach (self::$_questions as $question) {
      $key = $question->_id;
      switch ($question->_type) {
        case 'boolean':
        case 'single-meta':
        case 'multi':
        case 'string':
        case 'hidden':
        case 'file':
        default:
          $createArray[] = "TMPTBL" . $key . " VARCHAR(50)";
          break;
        case 'single':
          $createArray[] = "TMPTBL" . $key . " INT(11)";
          break;
        case 'datetime':
          $createArray[] = "TMPTBL" . $key . " DATETIME";
          break;
      }
      $createArray[] = "TMPTBLDisplay" . $key . " TEXT";
    }
    $createArray[] = "PRIMARY KEY (id), UNIQUE(id), UNIQUE(responseId)";
    $createQuery .= implode(',', $createArray) . ') ENGINE = MyISAM';
    //echo $createQuery;
    Config::get('db')->query($createQuery);
    return true;
  }

  //Download and import all missing live surveys
  public static function importMissingLiveSurveys() {
    if (!is_array(self::$_live_surveys) || count(self::$_live_surveys) == 0) {
      self::getAllLiveSurveys();
    }
    foreach (self::$_live_surveys as $survey) {
      if (!self::exists($survey->getId())) {
        $surveyId = $survey->getId();
        $securityId = Utility::genCode(40);
        $surveyName = $survey->getName();
        $rawConfig = $survey->getRaw();
        $surveyDetails = self::getLiveSurveyDetails($surveyId);
        $data = array(
            'surveyId' => $surveyId,
            'secId' => $securityId,
            'name' => $surveyName,
            'rawConfig' => $rawConfig,
            'questions' => Config::get('db')->filter(json_encode($surveyDetails['variables'])),
            'importedDate' => date('Y-m-d H:i:s'),
            'lastUpdated' => $survey->getLastUpdate()
        );
        self::create($data);
        self::createAnswerTable($surveyId);
      } else {
        $tmpSurvey = Survey::read($survey->getId());
        if ($tmpSurvey['lastUpdated'] < $survey->getLastUpdate()) {
          //echo "found older version: ".$survey->getId()." system: ".$tmpSurvey['lastUpdated']. " - live: ".$survey->getLastUpdate();
          //echo "<br />";
          $surveyDetails = self::getLiveSurveyDetails($survey->getId());
          //print_r($surveyDetails['variables']);
          //echo "<br />";
          Survey::update($survey->getId(), array('lastUpdated' => date('Y-m-d H:i:s'), 'liveUpdated' => $survey->getLastUpdate(), 'questions' => Config::get('db')->filter(json_encode($surveyDetails['variables']))));
        }
      }
    }
  }

  //Get survey form
  public static function getForm($responseId) {
    //load survey ID from response ID
    $surveyId = Response::getSurveyId($responseId);

    //if no survey Id, bail
    if (!$surveyId)
      return false;

    //if survey not loaded, load it
    if (!Survey::$_loaded)
      Survey::load($surveyId);

    //if survey didn't load, bail
    if (!Survey::$_loaded)
      return false;

    //OLD $outFields = (count(self::$_survey_config[SURVEY_CONFIG::EDITVIEW]) > 0) ? self::$_survey_config[SURVEY_CONFIG::EDITVIEW] : Array();
    $outFields = Survey::$_editView;

    //Initialize our output string
    $output = "";

    //@TODO: Download response file
    //self::downloadResponseFile($surveyId,$responseId,$response['mobileNum'],$fileUploadStatic,basename($response[$fileUploadStatic]));
    //Get survey Question List
    $questions = Survey::$_questions;

    //OLD $answers = self::getResponseAnswers($surveyId, Array('responseId' => $responseId));
    $answers = Response::getResponseAnswers($surveyId, $responseId);

    //begin the form building process
    if (isset($outFields) && count($outFields) > 0) {
      $fileLink = '';
      $responseFileLink = '';
      if (isset($answers['responseFile'])) {
        if (strlen(trim($answers['responseFile'])) > 0) {
          $responseFileLink = '<a target="_blank" href="' . Config::get('base_url') . "dat/surveyfiles/{$surveyId}/{$answers['mobileNum']}/{$answers['responseFile']}" . '">' . $answers['responseFile'] . '</a>';
        }
      }
      if (isset($answers['uploadFile'])) {
        if (strlen(trim($answers['uploadFile'])) > 0) {
          $fileLink = '<a target="_blank" href="' . Config::get('base_url') . "dat/surveyfiles/{$surveyId}/{$answers['mobileNum']}/{$answers['uploadFile']}" . '">' . $answers['uploadFile'] . '</a>';
        }
      }
      $output .= '
	        <form>
                <ul class="surveyFormVars"><li><label>Response File:</label><div>' . $responseFileLink . '</div></li></ul>
	        </form>
	        <form>
                <ul class="surveyFormVars"><li><label>Uploaded File:</label><div id="filelink">' . $fileLink . '</div></li><li>
		        <div id="queue"></div>
		        <input id="file_upload" name="file_upload" type="file" multiple="true">
                </li></ul>
	        </form>
            ';

      if (isset($answers['responseSMS'])) {
        $output .= '<form id="surveyEditForm" method="post" action="responses/submitResponseForm/1/' . $responseId . '/' . $surveyId . '"><ul class="surveyFormVars">' . "\r\n";
        foreach ($outFields as $fielddata) {
          $key = $fielddata['id'];
          if (isset($questions[$key])) {
            $output .= "<li><label>" . $fielddata['displayname'] . "</label>";
            $value = (isset($answers['responseSMS'][$key])) ? $answers['responseSMS'][$key] : '';
            $output .= '<input type="text" name="' . $key . '" value="' . $value . '" />';
            $output .= "</li>\r\n";
          }
        }
        $output .= "</ul></form>";
      } else {
        $output .= '<form id="surveyEditForm" method="post" action="responses/submitResponseForm/0/' . $responseId . '/' . $surveyId . '"><ul class="surveyFormVars">' . "\r\n";
        foreach ($outFields as $fielddata) {
          $key = $fielddata['id'];
          if (isset($questions[$key])) {
            $output .= "<li><label>" . $fielddata['displayname'] . "</label>";
            $value = (isset($answers[$key])) ? $answers[$key] : '';
            switch ($questions[$key]->_type) {
              case 'datetime':
                $value = substr(str_replace('T', ' ', $value), 0, 10);
              case 'string':
                if (is_array($value))
                  $value = implode(',', $value);
                $output .= '<input type="text" name="' . $key . '" value="' . $value . '" />';
                break;
              case 'multi':
                if (is_array($value) && count($value) > 1 && is_array($value[0])) {
                  $value = $value[0];
                }
                $output .= '<select multiple name="' . $key . '">' . "\r\n";
                if (isset($questions[$key]->_choices) && count($questions[$key]->_choices) > 0)
                  foreach ($questions[$key]->_choices as $aval => $aname) {
                    $selected = '';
                    if (is_array($value)) {
                      $selected = (in_array("$aval", $value)) ? ' selected="selected"' : '';
                    } else
                    if (is_string($value) && $value != '') {
                      $selected = ($aval == $value) ? ' selected="selected"' : '';
                    }
                    if (is_array($aname) && isset($aname['label'])) {
                        $output .= '<option value="' . $aval . '"' . $selected . '>' . $aname['label'] . '</option>' . "\r\n";
                    } else {
                        $output .= '<option value="' . $aval . '"' . $selected . '>' . $aname . '</option>' . "\r\n";
                    }
                  }
                $output .= '</select>' . "\r\n";
                break;
              case 'single':
              case 'single-meta':
                if (is_array($value)) {
                  $value = (count($value) > 0) ? $value[0] : '';
                }
                $output .= '<select name="' . $key . '">' . "\r\n";
                $selected = ($value < 0 || strlen($value) == 0) ? ' selected="selected"' : '';
                $output .= '<option value="-1"' . $selected . '>&nbsp;</option>' . "\r\n";
                if (isset($questions[$key]->_choices) && count($questions[$key]->_choices) > 0) {
                  $answer_index = array_values($questions[$key]->_choices);
                  foreach ($answer_index as $aval => $aname) {
                    if (strlen(trim($selected)) == 0)
                      $selected = (isset($value) && $aval == $value) ? ' selected="selected"' : '';
                    else
                      $selected = '';
                    if (is_array($aname) && isset($aname['label'])) {
                        $output .= '<option value="' . $aval . '"' . $selected . '>' . $aname['label'] . '</option>' . "\r\n";
                    } else {
                        $output .= '<option value="' . $aval . '"' . $selected . '>' . $aname . '</option>' . "\r\n";
                    }
                  }
                }
                $output .= '</select>' . "\r\n";
                break;
              case 'boolean':
                $true = (isset($value) && $value == 1);
                $false = (isset($value) && $value == 0);
                $truechecked = ($true) ? 'checked ' : '';
                $falsechecked = ($false) ? 'checked ' : '';
                $output .= '<input class="truebox" type="radio" name="' . $key . '" value="1" ' . $truechecked . '/>True';
                $output .= '<input class="truebox" type="radio" name="' . $key . '" value="0" ' . $falsechecked . '/>False';
                break;
              case 'hidden':
              default:
                break;
            }
            $output .= "</li>\r\n";
          }
        }
        $output .= "</ul></form><br />";
      }
    } else {
      $output = "Survey Not Configured";
    }
    Config::get('db')->update("response", Array('viewed' => 1), Array('surveyResponseId' => $responseId));
    echo $output;
  }

  //Get filter data
  public static function getFilters($surveyId) {
    $userId = Config::get('loggedIn');
    if (!self::$_loaded)
      self::load($surveyId);
    $outFields = (Survey::$_filtersView) ? Survey::$_filtersView : array();
    $output = '';

    $output = '<div id="filterBox" class="filterBox"><form id="filterForm" name="filterForm" action="" method="GET">';

    // keyword block    
    $output .= '<label>Keyword</label><input type="text" id="filter_keyword" name="filter_keyword" value="" /><br />';

    // optout block
    $output .= '<label>Opted In/Out</label><input type="radio" name="filter_optout" id="filter_optout" value="0" checked /> All <input type="radio" name="filter_optout" id="filter_optout" value="1" /> In <input type="radio" name="filter_optout" id="filter_optout" value="2" /> Out';

    // stage block
    $buf = '';
    //$q = "SELECT id, name FROM stage WHERE userId={$userId} AND surveyId = {$surveyId} and active > 0";
    //$result = Config::get('db')->get_results($q);
    $result = Stage::getAll();
    foreach ($result as $s)
      $buf .= '<option value="' . $s['id'] . '">' . $s['name'] . '</option>';

    $output .= '<label>Stage</label><select multiple id="filter_stageid" name="filter_stageid"><option value="-1">Choose...</option><option value="0">[Not Set]</option>' . $buf . '</select><br />';

    // event block
    $buf = '';
    $q = "SELECT id, name FROM event WHERE userId={$userId} AND surveyId = {$surveyId} and active > 0";
    $result = Config::get('db')->get_results($q);

    foreach ($result as $e)
      $buf .= '<option value="' . $e['id'] . '">' . $e['name'] . '</option>';

    $output .= '<label>Job / Event</label><select multiple id="filter_eventid" name="filter_eventid"><option value="-1">Choose...</option><option value="0">[Not Set]</option>' . $buf . '</select><br />';

    // mobile number block
    // $output .= '<label>Mobile Num</label><input type="text" id="filter_mobileNum" name="filter_mobileNum" value="" /><br />';
    // zip code block
    $zipopt = '<option value="0">Distance</option><option value="5">5 Miles</option><option value="10">10 Miles</option><option value="15">15 Miles</option><option value="30">30 Miles</option><option value="50">50 Miles</option><option value="100">100 Miles</option>';
    $output .= '<label>Zip Code</label><input style="width:45px" type="text" id="filter_zipcode_static" name="filter_zipcode_static" value="" /> - <select style="width:85px;height:23px;" id="filter_zipdist_static" name="filter_zipdist_static">' . $zipopt . '</select><br />';
    $output .= '<label>Zip Code Lookup: <a target="_blank" href="https://tools.usps.com/go/ZipLookupAction!input.action">Click Here</a></label>';
    if (count($outFields) > 0) {
      foreach ($outFields as $fields) {
        $fielddata = $fields;
        $key = $fielddata['id'];
        $output .= "<label>" . $fielddata['displayname'] . "</label>";
        if ($key == 'stagename') {

          $output .= '<select multiple id="filter_' . $key . '" name="filter_' . $key . '[]">' . "\r\n";
          //$stages = self::getSurveyStages($surveyId);
          $stages = Stage::getAll($surveyId);
          foreach ($stages as $stage) {

            $output.= '<option value="' . $stage['id'] . '">' . $stage['name'] . '</option>';
          }
          $output .= '</select>' . "\r\n";
        } else
        if ($key == 'eventname') {

          //$output .= '<select multiple id="filter_' . $key . '" name="filter_' . $key . '[]">' . "\r\n";
          //$events = self::getSurveyEvents($surveyId);
          
          //foreach ($events['records'] as $event) {

          //  $output.= '<option value="' . $event['id'] . '">' . $event['name'] . '</option>';
          //}
          //$output .= '</select>' . "\r\n";
        } else {
          switch (self::$_questions[$key]->_type) {
            case 'string':
            case 'datetime':
              if (substr($fielddata['displayname'], 0, 3) == 'Zip') {
                $output .= '<input style="width:50px" type="text" id="filter_zipcode_' . $key . '" name="filter_zipcode_' . $key . '" value="" /> - ';
                $output .= '<select style="width:85px;height:23px;" id="filter_zipdist" name="filter_zipdist">';
                $output .= '<option value="0">Distance</option>';
                $output .= '<option value="5">5 Miles</option>';
                $output .= '<option value="10">10 Miles</option>';
                $output .= '<option value="15">15 Miles</option>';
                $output .= '<option value="30">30 Miles</option>';
                $output .= '<option value="50">50 Miles</option>';
                $output .= '<option value="100">100 Miles</option>';
                $output .= '</select>';
              } else
                $output .= '<input type="text" id="filter_' . $key . '" name="filter_' . $key . '" value="" />';
              break;
            case 'multi':
              $output .= '<select multiple id="filter_' . $key . '" name="filter_' . $key . '[]">' . "\r\n";
              if (isset(self::$_questions[$key]->_choices) && count(self::$_questions[$key]->_choices) > 0)
                foreach (self::$_survey_answermatrix[$key] as $aval => $aname) {
                  $output .= '<option value="' . $aval . '">' . $aname . '</option>' . "\r\n";
                }
              $output .= '</select>' . "\r\n";
              break;
            case 'single':
            case 'single-meta':
              $output .= '<select multiple id="filter_' . $key . '" name="filter_' . $key . '[]">' . "\r\n";
              $output .= '<option value="-1"> </option>' . "\r\n";
              if (isset(self::$_questions[$key]->_choices) && count(self::$_questions[$key]->_choices) > 0) {
                $answer_index = array_values(self::$_questions[$key]->_choices);
                foreach ($answer_index as $aval => $aname) {
                  $output .= '<option value="' . $aval . '">' . $aname . '</option>' . "\r\n";
                }
              }
              $output .= '</select>' . "\r\n";
              break;
            case 'boolean':
              $output .= '<input class="truebox" type="radio" name="filter_' . $key . '" id="filter_' . $key . '" value="yes" />True';
              $output .= '<input class="truebox" type="radio" name="filter_' . $key . '" value="no" />False';
              break;
            case 'file':
            case 'hidden':
            default:
              break;
          }
        }
        $output .= "<br />\r\n";
      }
    }
    $output .= "</form></div>";

    return $output;
  }

  public static function getStaticVars($surveyId) {
      $configData = Survey::getSurveyConfig($surveyId);
    $staticArray = Array(
        'tpl_keywords' => '',
        'tpl_smsmsg' => '',
        'tpl_firstname' => '',
        'tpl_lastname' => '',
        'tpl_mobilenum' => '',
        'tpl_email' => '',
        'tpl_zipcode' => '',
        'tpl_position' => '',
        'tpl_location' => '',
        'tpl_surveykeyword' => '',
        'tpl_smssurveymsg' => '',
        'tpl_smssurveydone' => '',
        'tpl_upload' => '',
        'tpl_referral' => '',
        'tpl_optin' => '',
        'tpl_postid' => '',
        'tpl_defaultadmin' => '',
        'tpl_forwardemail' => '',
        'tpl_forwardemailmsg' => '',
        'tpl_joburl' => '',
        'tpl_defaultpostid' => ''
    );
    if (count($configData) > 0) {
      $staticArray['tpl_keywords'] = (isset($configData[STATIC_VARS::KEYWORD])) ? $configData[STATIC_VARS::KEYWORD] : '';
      $staticArray['tpl_smsmsg'] = (isset($configData[STATIC_VARS::RETURNSTR])) ? $configData[STATIC_VARS::RETURNSTR] : '';
      $staticArray['tpl_surveykeyword'] = (isset($configData[STATIC_VARS::SURVEYKEYWORD])) ? $configData[STATIC_VARS::SURVEYKEYWORD] : '';
      $staticArray['tpl_smssurveymsg'] = (isset($configData[STATIC_VARS::SURVEYMESSAGE])) ? $configData[STATIC_VARS::SURVEYMESSAGE] : '';
      $staticArray['tpl_smssurveydone'] = (isset($configData[STATIC_VARS::SURVEYCOMPLETEMESSAGE])) ? $configData[STATIC_VARS::SURVEYCOMPLETEMESSAGE] : '';
      $staticArray['tpl_firstname'] = (isset($configData[STATIC_VARS::FIRSTNAME])) ? $configData[STATIC_VARS::FIRSTNAME] : '';
      $staticArray['tpl_lastname'] = (isset($configData[STATIC_VARS::LASTNAME])) ? $configData[STATIC_VARS::LASTNAME] : '';
      $staticArray['tpl_mobilenum'] = (isset($configData[STATIC_VARS::MOBILENUM])) ? $configData[STATIC_VARS::MOBILENUM] : '';
      $staticArray['tpl_email'] = (isset($configData[STATIC_VARS::EMAIL])) ? $configData[STATIC_VARS::EMAIL] : '';
      $staticArray['tpl_zipcode'] = (isset($configData[STATIC_VARS::ZIPCODE])) ? $configData[STATIC_VARS::ZIPCODE] : '';
      $staticArray['tpl_position'] = (isset($configData[STATIC_VARS::POSITION])) ? $configData[STATIC_VARS::POSITION] : '';
      $staticArray['tpl_location'] = (isset($configData[STATIC_VARS::LOCATION])) ? $configData[STATIC_VARS::LOCATION] : '';
      $staticArray['tpl_upload'] = (isset($configData[STATIC_VARS::SURVEYFILE])) ? $configData[STATIC_VARS::SURVEYFILE] : '';
      $staticArray['tpl_referral'] = (isset($configData[STATIC_VARS::REFERRAL])) ? $configData[STATIC_VARS::REFERRAL] : '';
      $staticArray['tpl_optin'] = (isset($configData[STATIC_VARS::OPTIN])) ? $configData[STATIC_VARS::OPTIN] : '';
      $staticArray['tpl_postid'] = (isset($configData[STATIC_VARS::POSTID])) ? $configData[STATIC_VARS::POSTID] : '';
      $staticArray['tpl_defaultadmin'] = (isset($configData[STATIC_VARS::DEFAULTADMIN])) ? $configData[STATIC_VARS::DEFAULTADMIN] : '';
      $staticArray['tpl_forwardemail'] = (isset($configData[STATIC_VARS::FORWARDEMAIL])) ? $configData[STATIC_VARS::FORWARDEMAIL] : '';
      $staticArray['tpl_forwardemailmsg'] = (isset($configData[STATIC_VARS::FORWARDMSG])) ? $configData[STATIC_VARS::FORWARDMSG] : '';
      $staticArray['tpl_joburl'] = (isset($configData[STATIC_VARS::JOBURL])) ? $configData[STATIC_VARS::JOBURL] : '';
      $staticArray['tpl_defaultpostid'] = (isset($configData[STATIC_VARS::DEFAULTPOSTID])) ? $configData[STATIC_VARS::DEFAULTPOSTID] : '';
    }
    return $staticArray;
  }

  public static function getConfigFields($surveyId) {
    $static_vars = Survey::getStaticVars($surveyId);

    $out = array(
        'key' => $static_vars['tpl_keywords'],
        'sms_msg' => stripcslashes($static_vars['tpl_smsmsg']),
        'start_key' => $static_vars['tpl_surveykeyword'],
        'sms_start_msg' => stripcslashes($static_vars['tpl_smssurveymsg']),
        'sms_complete_msg' => stripcslashes($static_vars['tpl_smssurveydone']),
        'scf_name_first' => $static_vars['tpl_firstname'],
        'scf_name_last' => $static_vars['tpl_lastname'],
        'scf_mobile_num' => $static_vars['tpl_mobilenum'],
        'scf_zipcode' => $static_vars['tpl_zipcode'],
        'scf_email' => $static_vars['tpl_email'],
        'scf_position' => $static_vars['tpl_position'],
        'scf_location' => $static_vars['tpl_location'],
        'scf_upload' => $static_vars['tpl_upload'],
        'scf_referral' => $static_vars['tpl_referral'],
        'scf_optin' => $static_vars['tpl_optin'],
        'scf_postid' => $static_vars['tpl_postid'],
        'scf_defaultadmin' => $static_vars['tpl_defaultadmin'],
        'cnd_forwardemail' => $static_vars['tpl_forwardemail'],
        'cnd_forwardemailmsg' => $static_vars['tpl_forwardemailmsg'],
        'joburl' => $static_vars['tpl_joburl'],
        'defaultpostid' => $static_vars['tpl_defaultpostid']
    );

    echo json_encode(array('status' => 'success', 'record' => $out));
  }

  public static function setConfigFields($surveyId) {
    $staticFields = $_REQUEST['record'];
    $dbData = Config::get('db')->get_results("SELECT configData FROM survey WHERE surveyId={$surveyId}");
    $surveyConfigData = json_decode($dbData[0]['configData'], true);

    $setData = array(
        STATIC_VARS::KEYWORD => (isset($staticFields['key'])) ? $staticFields['key'] : '',
        STATIC_VARS::RETURNSTR => (isset($staticFields['sms_msg'])) ? $staticFields['sms_msg'] : '',
        STATIC_VARS::SURVEYKEYWORD => (isset($staticFields['start_key'])) ? $staticFields['start_key'] : '',
        STATIC_VARS::SURVEYMESSAGE => (isset($staticFields['sms_start_msg'])) ? $staticFields['sms_start_msg'] : '',
        STATIC_VARS::SURVEYCOMPLETEMESSAGE => (isset($staticFields['sms_complete_msg'])) ? $staticFields['sms_complete_msg'] : '',
        STATIC_VARS::FIRSTNAME => (isset($staticFields['scf_name_first'])) ? $staticFields['scf_name_first'] : '',
        STATIC_VARS::LASTNAME => (isset($staticFields['scf_name_last'])) ? $staticFields['scf_name_last'] : '',
        STATIC_VARS::MOBILENUM => (isset($staticFields['scf_mobile_num'])) ? $staticFields['scf_mobile_num'] : '',
        STATIC_VARS::ZIPCODE => (isset($staticFields['scf_zipcode'])) ? $staticFields['scf_zipcode'] : '',
        STATIC_VARS::EMAIL => (isset($staticFields['scf_email'])) ? $staticFields['scf_email'] : '',
        STATIC_VARS::POSITION => (isset($staticFields['scf_position'])) ? $staticFields['scf_position'] : '',
        STATIC_VARS::LOCATION => (isset($staticFields['scf_location'])) ? $staticFields['scf_location'] : '',
        STATIC_VARS::SURVEYFILE => (isset($staticFields['scf_upload'])) ? $staticFields['scf_upload'] : '',
        STATIC_VARS::REFERRAL => (isset($staticFields['scf_referral'])) ? $staticFields['scf_referral'] : '',
        STATIC_VARS::OPTIN => (isset($staticFields['scf_optin'])) ? $staticFields['scf_optin'] : '',
        STATIC_VARS::POSTID => (isset($staticFields['scf_postid'])) ? $staticFields['scf_postid'] : '',
        STATIC_VARS::DEFAULTADMIN => (isset($staticFields['scf_defaultadmin'])) ? $staticFields['scf_defaultadmin'] : '',
        STATIC_VARS::FORWARDEMAIL => (isset($staticFields['cnd_forwardemail'])) ? $staticFields['cnd_forwardemail'] : '',
        STATIC_VARS::FORWARDMSG => (isset($staticFields['cnd_forwardemailmsg'])) ? $staticFields['cnd_forwardemailmsg'] : '',
        STATIC_VARS::JOBURL => (isset($staticFields['joburl'])) ? $staticFields['joburl'] : '',
        STATIC_VARS::DEFAULTPOSTID => (isset($staticFields['defaultpostid'])) ? $staticFields['defaultpostid'] : ''
    );

    foreach ($setData as $k => $v) {
      $surveyConfigData[$k] = Config::get('db')->filter($v);
    }
    $configData = $surveyConfigData;

    Config::get('db')->update('survey', array('configData' => Config::get('db')->filter(json_encode($configData))), array('surveyId' => $surveyId));
    echo '{"status":"success"}';
  }

  public static function getDetailsFields($surveyId) {
    $dbData = Config::get('db')->get_results("SELECT name as surveyName,description as surveyDescription FROM survey WHERE surveyId={$surveyId}");
    $out = $dbData[0];
    echo json_encode(array('status' => 'success', 'record' => $out));
  }

  public static function setDetailsFields($surveyId) {
    $fields = $_REQUEST['record'];
    $surveyName = $fields['surveyName'];
    $surveyDescription = $fields['surveyDescription'];
    Config::get('db')->update('survey', array('name' => Config::get('db')->filter($surveyName), 'description' => Config::get('db')->filter($surveyDescription)), array('surveyId' => $surveyId));
    echo '{"status":"success"}';
  }

  public static function getEmpRefFields($surveyId) {
    $query = "
            SELECT
                refKeyword,
                surveyLink,
                companyName,
                messageA,
                messageB,
                empref_emailfwd as emprefEmailFwd,
                empref_emailfwdmsg as emprefEmailFwdMsg
            FROM survey
            WHERE surveyId={$surveyId}
        ";

    $dbData = Config::get('db')->get_results($query);
    $out = $dbData[0];
    echo json_encode(array('status' => 'success', 'record' => $out));
  }

  public static function setEmpRefFields($surveyId) {
    $fields = $_REQUEST['record'];
    $refKeyword = $fields['refKeyword'];
    $surveyLink = $fields['surveyLink'];
    $companyName = $fields['companyName'];
    $messageA = $fields['messageA'];
    $messageB = $fields['messageB'];
    $forwardEmail = $fields['emprefEmailFwd'];
    $forwardMsg = $fields['emprefEmailFwdMsg'];
    $data = array(
        'refKeyword' => $refKeyword,
        'surveyLink' => Config::get('db')->filter($surveyLink),
        'companyName' => Config::get('db')->filter($companyName),
        'messageA' => Config::get('db')->filter($messageA),
        'messageB' => Config::get('db')->filter($messageB),
        'empref_emailfwd' => Config::get('db')->filter($forwardEmail),
        'empref_emailfwdmsg' => Config::get('db')->filter($forwardMsg)
    );
    $where = array(
        'surveyId' => $surveyId
    );
    Config::get('db')->update('survey', $data, $where);
    echo '{"status":"success"}';
  }

  public static function getEmpRefs($surveyId) {
    $query = "SELECT * FROM emp_ref WHERE surveyId={$surveyId} AND active>0 ORDER BY refDate DESC";
    $dbData = Config::get('db')->get_results($query);
    $outArray = array(
        'status' => 'success',
        'total' => max(0, count($dbData)),
        'records' => $dbData
    );
    echo json_encode($outArray);
  }

  //generate an email from survey response
  public static function getSurveyEmail($responseId, $surveyId, $msg = '') {
    if (!self::$_loaded)
      self::load($surveyId);
    $outFields = self::$_editView;
    $output = "";
    $responseData = Response::readPri($surveyId, $responseId);
    $mobileNum = (isset($responseData['mobileNum'])) ? $responseData['mobileNum'] : '';
    $answers = Response::getAnswerList($surveyId, $responseId);
    $isSMS = false;

    if (isset($answers['responseSMS'])) {
      $isSMS = true;
      $answers = array_merge($answers, $answers['responseSMS']);
    }
    if (count($outFields) > 0) {
      $output .= '<!DOCTYPE HTML>' . "\r\n";
      $output .= '<html>' . "\r\n";
      $output .= '<head>' . "\r\n";
      $output .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />' . "\r\n";
      $output .= '<style type="text/css">' . "\r\n";
      $output .= '  img {display:block;}' . "\r\n";
      $output .= '  table {border-collapse:collapse}' . "\r\n";
      $output .= '</style>' . "\r\n";
      $output .= '</head>' . "\r\n";
      $output .= '<body>' . "\r\n";
      $output .= '<table cellpadding="4" cellspacing="0" border="0" style="border-collapse:collapse;font-family:Arial sans-serif" width="600">' . "\r\n";
      $output .= '<tr><td width="600" colspan="2" valign="bottom" align="left" style="vertical-align:bottom">' . "\r\n";
      $output .= '<img style="display:block;float:left" src="http://jobalarm.com/admin/img/header-main-small.png" alt="JobAlarm" />' . "\r\n";
      $output .= '</td></tr>' . "\r\n";
      $output .= '<tr><td width="600" colspan="2" style="line-height:120%;margin:0px;padding-top:20px;padding-bottom:20px;font-size:16px;font-weight:normal">' . $msg . '</td></tr>' . "\r\n";
      foreach ($outFields as $field) {
        $output.="<tr>" . "\r\n";
        $key = $field['id'];
        if (isset(self::$_questions[$key])) {
          $output .= '<td width="200" valign="top" align="right"><span style="font-size:14px;font-weight:bold;padding-right:5px;">' . $field['displayname'] . ":</span></td>" . "\r\n";
          $value = (isset($answers[$key])) ? $answers[$key] : '';
          if ($isSMS) {
            $output .= '<td width="400" valign="top" align="left"><span style="font-size:14px">' . $value . '</span></td>' . "\r\n";
          } else
            switch (self::$_questions[$key]->_type) {
              case 'datetime':
                $value = substr(str_replace('T', ' ', $value), 0, 10);
              case 'string':
                if (is_array($value)) {
                  $value = implode(',', $value);
                }
                $output .= '<td width="400" valign="top" align="left"><span style="font-size:14px">' . $value . '</span></td>' . "\r\n";
                break;
              case 'multi':
                $output .= '<td width="400" valign="top" align="left"><span style="font-size:14px">' . "\r\n";
                if (isset(self::$_questions[$key]->_choices) && count(self::$_questions[$key]->_choices) > 0) {
                  foreach (self::$_questions[$key]->_choices as $aval => $aname) {
                    if (is_array($value) && in_array("$aval", $value)) {
                      $output .= $aname . '<br />' . "\r\n";
                    } else
                    if (is_string($value) && $value != '' && $value == $aname) {
                      $output .= $aname . '<br />' . "\r\n";
                    }
                    //                  $output .= '<option value="' . $aval . '"' . $selected . '>' . $aname . '</option>' . "\r\n";
                  }
                }
                $output .= '</span></td>' . "\r\n";
                break;
              case 'single':
              case 'single-meta':
                $output .= '<td width="400" valign="top" align="left"><span style="font-size:14px">' . "\r\n";
                $selected = ($value < 0 || strlen($value) == 0) ? ' selected="selected"' . "\r\n" : '';
                //$output .= '<option value="-1"' . $selected . '>&nbsp;</option>' . "\r\n";
                if (isset(self::$_questions[$key]->_choices) && count(self::$_questions[$key]->_choices) > 0) {
                  $answer_index = array_values(self::$_questions[$key]->_choices);
                  foreach ($answer_index as $aval => $aname) {
                    if (strlen(trim($selected)) == 0) {
                      $output .= (isset($value) && $aval == $value) ? $aname : '';
                    }
                  }
                }
                $output .= '</span></td>' . "\r\n";
                break;
              case 'boolean':
                $output .= '<td width="400" valign="top" align="left"><span style="font-size:14px">' . "\r\n";
                $true = (isset($value) && $value == 1);
                //$false = (isset($value) && $value == 0);
                $truechecked = ($true) ? 'checked ' : '';
                //$falsechecked = ($false) ? 'checked ' : '';
                $output .= ($truechecked) ? 'Yes' . "\r\n" : 'No' . "\r\n";
                $output .= '</span></td>' . "\r\n";
                break;
              case 'hidden':
              default:
                $output .= '<td width="400" valign="top" align="left"><span style="font-size:14px"></span></td>' . "\r\n";
                break;
            }
        }
        $output .= "</tr>" . "\r\n";
      }
      $output .= '<td width="200" valign="top" align="right"><span style="font-size:14px;font-weight:bold;padding-right:5px;">Mobile Number:</span></td>' . "\r\n";
      $output .= '<td width="400" valign="top" align="left"><span style="font-size:14px">' . $mobileNum . '</span></td>' . "\r\n";
      $output .= "</body></html>" . "\r\n";
    } else {
      return NULL;
    }
    return $output;
  }

  public static function sendEmail($userId, $surveyId, $responseId, $name, $to, $cc, $msg) {
    include "lib/phpMailer/class.phpmailer.php";
    if (!Survey::$_loaded)
      Survey::load($surveyId);
    $user = User::load();

    $email = $user['email'];
    $firstName = $user['firstName'];
    $lastName = $user['lastName'];

    $response = Response::readPri($surveyId, $responseId);
    //$response = self::getResponse($surveyId, Array('responseId' => $responseId));
    $person = Person::read($response['peopleId']);
    //$person = self::getPersonByID($response['peopleId']);
    //    $answers = CWalkupScreener::getResponseAnswers($surveyId, Array('responseId' => $responseId));
    //    
    //    $position_val = $answers[self::$_survey_static_vars[STATIC_VARS::POSITION]];
    //    $position = self::$_survey_answermatrix[self::$_survey_static_vars[STATIC_VARS::POSITION]][$position_val];
    //    
    //    $location_val = $answers[self::$_survey_static_vars[STATIC_VARS::LOCATION]];
    //    $location = self::$_survey_answermatrix[self::$_survey_static_vars[STATIC_VARS::LOCATION]][$location_val];

    $subject = "Candidate information for {$person['firstName']} {$person['lastName']}";
    $mail = new PHPMailer();
    //$mail->IsSMTP();                                      // set mailer to use SMTP
    //$mail->SMTPAuth = true;     // turn on SMTP authentication
    //$mail->SMTPSecure = 'tls';
    //$mail->Host = "smtp.gmail.com";  // specify main and backup server
    //$mail->Port = 587;
    //$mail->Username = "setzor@gmail.com";  // SMTP username
    //$mail->Password = "J0v14nM4st3r!"; // SMTP password    
    //$mail->SMTPDebug = 2;
    $mail->CharSet = "UTF-8";
    //
    $mail->IsSendmail();

    $mail->From = 'noreply@walkupscreener.com';
    $mail->AddReplyTo((isset($email)) ? $email : 'noreply@walkupscreener.com');
    $mail->FromName = (isset($firstName)) ? $firstName . ' ' . $lastName : 'WalkupScreener Services';
    $mail->AddAddress($to, $name);  // Add a recipient
    if (strlen($cc) > 0) {
      $mail->AddCC($cc);
    }
    $mail->IsHTML(true);                                  // Set email format to HTML

    $mail->Subject = $subject;

    $answers = Response::getResponseAnswers($surveyId, $responseId);

    if (isset($answers['responseFile'])) {
      if (strlen(trim($answers['responseFile'])) > 0) {
        $mail->AddAttachment("dat/surveyfiles/{$surveyId}/{$answers['mobileNum']}/{$answers['responseFile']}");
      }
    }

    $body = self::getSurveyEmail($responseId, $surveyId, $msg);
    if (!$body) {
      echo "Failure";
      exit;
    }


    $mail->Body = $body;

    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    if (!$mail->Send()) {
      echo 'Mailer Error: ' . $mail->ErrorInfo;
      exit;
    }
    echo 'Mail Sent';
    $data = Array(
        'userId' => $userId,
        'surveyId' => $surveyId,
        'responseId' => $responseId,
        'dateSent' => date('Y-m-d H:i:S'),
        'sentTo' => $to,
        'sentCC' => $cc,
        'recipName' => $name
    );
    Config::get('db')->insert('email_history', $data);
    NoteManager::addNote(User::getId(), $surveyId, $person['id'], "Email sent to {$to}");
  }

  public static function getSurveyList($options = NULL) {
    $sqlAdd = '';
    $surveys = Config::get('db')->get_results("select * from survey where active>0 {$sqlAdd}");
    $surveyList = Array();
    if (count($surveys) > 0) {
      if (isset($options['indexed'])) {
        $surveyList = $surveys;
      } else {
        foreach ($surveys as $survey) {
          $surveyList[$survey['surveyId']] = $survey;
        }
      }
    }
    return $surveyList;
  }

  public static function getSurveyKeywordList() {
    $surveys = self::getSurveyList();
    $outData = array();
    if (count($surveys) > 0) {
      $surveyList = array_keys($surveys);
      foreach ($surveyList as $surveyId) {
        self::load($surveyId);
        $staticVars = self::getStaticVars($surveyId);
        if (isset($staticVars['tpl_keywords']) && strlen($staticVars['tpl_keywords']) > 0) {
          $outData[$staticVars['tpl_keywords']] = $surveyId;
        }
      }
    }
    return $outData;
  }

  public static function getSurveyRefKeywordList() {
    $surveys = self::getSurveyList();
    //var_dump($surveys);
    $outData = array();
    if (count($surveys) > 0) {
      foreach ($surveys as $surveyId => $survey) {
        $refKey = trim($survey['refKeyword']);
        if (strlen($refKey) > 0) {
          $outData[$refKey] = $surveyId;
        }
        //if (strlen($survey['refKeyword'] > 0)) {
        //    $outData[$survey['refKeyword']] = $surveyId;
        //}
      }
    }
    return $outData;
  }
  
  public static function getDefaultPostID($surveyId) {
      $staticVars = self::getStaticVars($surveyId);
      
      return max(0,$staticVars['tpl_defaultpostid']);
  
  }

  public static function getSurveyConfig($surveyId) {
      $query = "select configData from survey where surveyId={$surveyId}";
      $dbData = Config::get('db')->get_results($query);
      if (count($dbData) > 0) {
          return json_decode($dbData[0]['configData'],true);
      }
      return array();
  
  }
  
}
