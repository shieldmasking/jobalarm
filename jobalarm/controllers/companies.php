<?php

require_once('models/company.php');

class Companies {

    public static function run() {
        
    }

    public static function getList() {
        $companies = Company::read();
        $outData = array();
        if (count($companies) > 0) {
            foreach ($companies as $company) {
                array_push($outData, array('id' => $company['id'], 'text' => $company['name']));
            }
        }
        echo json_encode($outData);
    }

    public static function getAll() {
        $cmd = (isset($_REQUEST['cmd'])) ? $_REQUEST['cmd'] : '';
        switch ($cmd) {
            case 'delete-records':
                $records = (isset($_REQUEST['selected'])) ? $_REQUEST['selected'] : array();

                if (count($records > 0)) {
                    foreach ($records as $record) {
                        Company::delete($record);
                    }
                }

                echo '{"status":"success","total":0,"records":[]}';
                break;
            case 'save-records':
                
                $changes = (isset($_REQUEST['changes']) && is_array($_REQUEST['changes'])) ? $_REQUEST['changes'] : array();
                if ($changes && count($changes) > 0) {
                    foreach($changes as $change) {                        
                        Company::update($change['recid'],array('maxpostings'=>$change['maxpostings']['id']));
                    }
                }
                
            default:
                $search = (isset($_REQUEST['search'])) ? $_REQUEST['search'] : NULL;
                $companies = Company::getList($search);
                $dataArray = array();

                if (is_array($companies)) {
                    foreach ($companies as $company) {
                        $dataArray[] = array(
                            'recid' => $company['id'],
                            'companyname' => $company['name'],
                            'companydesc' => $company['description'],
                            'numpostings' => Company::getNumJobPostings($company['id']),
                            'maxpostings' => array('id'=>$company['maxpostings'],'text'=>$company['maxpostings'])
                        );
                    }
                }

                $outArray = array(
                    'status' => 'success',
                    'total' => count($dataArray),
                    'records' => $dataArray
                );

                echo json_encode($outArray);
                break;
        }
    }

    public static function Add() {
        Company::create(array('name' => $_REQUEST['companyName'], 'description' => $_REQUEST['companyDesc']));
        echo '{"status":"success","total":0,"records":[]}';
    }

    public static function getSurveyAccess($companyId) {
        $surveyAccess = Company::getSurveyAccessArray($companyId);
        echo json_encode(array('surveyList' => $surveyAccess));
    }
    
    public static function getSurveyAdmin($companyId) {
        $surveyAccess = Company::getSurveyAdminAccess($companyId);
        echo json_encode(array('surveyList' => $surveyAccess));        
    }

    public static function saveSurveyAccess($companyId) {
        Company::remAllSurveyAccess($companyId);
        Company::addSurveyAccess($companyId, $_REQUEST['surveys']);
        echo json_encode(array('status' => 'success'));
    }

    public static function saveSurveyAdmin($companyId) {
        Company::remAllSurveyAdmin($companyId);
        Company::addSurveyAdmin($companyId, $_REQUEST['surveys']);
        echo json_encode(array('status' => 'success'));
    }

    
    public static function getFullAccessList($companyId, $fullList) {
        $access = Company::getAllSurveyAccessList($companyId, $fullList);
        

        $outArray = array(
            'status' => 'success',
            'total' => count($access),
            'records' => $access
        );
        echo json_encode($outArray);
    }
    
    public static function getAccessList($companyId, $fullList = 0) {
        $access = Company::getAllSurveyAdminList($companyId, $fullList);
        //User::getDefaultAdminSurveyList(User::$_loggedIn);
        //$access = User::getDefaultAdminSurveyList(User::$_loggedIn);
        $outArray = array(
            'status' => 'success',
            'total' => count($access),
            'records' => $access
        );
        echo json_encode($outArray);
    }
    
    public static function getKeywords($companyId) {
        
        $keywords = company::getKeywords($companyId);
        $defaultKeyword = Company::getDefaultKeyword($companyId);
        $outKeywords = array();
        foreach($keywords as $keyword) {
            if ($keyword['id'] == $defaultKeyword) {
                $keyword['style'] = 'font-weight:bold';
            }
            $outKeywords[] = $keyword;
        }
        $outArray = array(
            'status' => 'success',
            'total' => count($keywords),
            'records' => $outKeywords
        );
        echo json_encode($outArray);
        
        
    }
    
    public static function addKeyword($companyId) {
        $keyword = $_REQUEST['keyword'];
        Company::addKeyword($companyId,$keyword);
        echo json_encode(array('success'=>true));
    }
    
    public static function delKeyword() {
        Company::toggleKeyword($_REQUEST['keywordId']);
        echo json_encode(array('success'=>true));
    }

    public static function setDefaultKeyword($companyId) {
        $keywordId = $_REQUEST['kid'];
        Company::setDefaultKeyword($companyId,$keywordId);
        echo json_encode(array('success'=>true));
    }
    
    public static function getReport() {
        $companyId = Router::getGetVar('cid');
        $startDate = Router::getGetVar('start');
        $endDate = Router::getGetVar('end');
        
        $company = Company::read($companyId);
        $companyName = str_replace(' ','',$company['name']);
        $reportFileName = "JobAlarm_".trim($companyName)."_".date('Y-m-d').".csv";
        
        
        $data = Company::getSMSReport($companyId,$startDate,$endDate);
        
        
        //var_dump($data);
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename={$reportFileName}");
        header("Pragma: no-cache");
        header("Expires: 0");
        
        $csv_header = array('Date','Mobile Number','Survey','Candidate','Message','User');
        $tempfile = fopen("dat/tempReport.csv","w");
        fputcsv($tempfile,$csv_header,",");
        foreach($data as $sms) {
            fputcsv($tempfile,$sms,",",'"');           
        }
        fclose($tempfile);
        echo file_get_contents("dat/tempReport.csv");
        
    }
    
}
