<?php

class Templates {

  public static function run() {
    
  }

  public static function get_sms_list() {
    $cmd = (isset($_REQUEST['cmd'])) ? $_REQUEST['cmd'] : '';

    switch ($cmd) {
      case 'delete-records':
        $records = (isset($_REQUEST['selected'])) ? $_REQUEST['selected'] : array();
        if (count($records > 0)) {
          foreach ($records as $record) {
            Template::delete_sms($record);
          }
        }

        echo '{"status":"success","total":0,"records":[]}';
        break;
      default:
        $search = (isset($_REQUEST['search'])) ? $_REQUEST['search'] : NULL;
        $templates = Template::get_sms_list_searchable($search);
        $data = array();

        if (is_array($templates)) {
          foreach ($templates as $template) {
            $data[] = array(
                'recid' => $template['id'],
                'template_name' => $template['name'],
                'template_message' => $template['message']
            );
          }
        }

        $out = array(
            'status' => 'success',
            'total' => max(0, count($data)),
            'records' => $data
        );
        
        echo json_encode($out);
        break;
    }
  }

  public static function add() {
    Template::create_sms(array('name' => $_REQUEST['template_name'], 'message' => $_REQUEST['template_message'], 'accountId'=>Config::get('loggedIn')));
    echo '{"status":"success","total":0,"records":[]}';
  }

}
