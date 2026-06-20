<?php

class Template {

  public static function get_all_sms() {
    $q = "SELECT
          t.id as id,
          t.name as name,
          t.message as message,
          t.active as active
          from templates_sms as t
          where active > 0 AND accountId=".Config::get('loggedIn');

    if (count($r = Config::get('db')->get_results($q)) > 0)
      return $r;

    return false;
  }

  public static function get_sms_list() {
    $q = "SELECT
          t.id as id,
          t.survey_id as survey,
          t.name as name,
          t.message as message,
          t.active as active
          from templates_sms as t
          where active > 0 AND accountId=".Config::get('loggedIn');

    if (count($r = Config::get('db')->get_results($q)) > 0) {
      foreach ($r as $row) {
        $data[] = array(
            'recid' => $row['id'],
            'template_survey_id' => $row['survey'],
            'template_name' => $row['name'],
            'template_message' => $row['message']
        );
      }

      $out = array(
          'status' => 'success',
          'total' => max(0, count($data)),
          'records' => $data
      );

      echo json_encode($out);
    }
  }

  public static function get_sms_list_searchable($search = NULL) {
    $where = '';

    if ($search && (count($search) > 0)) {
      foreach ($search as $opt) {
        switch ($opt['field']) {
          case 'template_name':
            $where .= " AND name LIKE '{$opt['value']}%'";
            break;
          case 'template_message':
            $where .= " AND message LIKE '{$opt['value']}%'";
            break;
          default:
            break;
        }
      }
    }

    $q = "SELECT
          t.id as id,
          t.name as name,
          t.message as message,
          t.active as active
          from templates_sms as t
          where active > 0 AND accountId=".Config::get('loggedIn')."
          {$where}";

    if (count($r = Config::get('db')->get_results($q)) > 0)
      return $r;

    return false;
  }

  public static function find_sms_by_id($tid) {
    $q = "SELECT
          t.id as id,
          t.name as name,
          t.message as message,
          t.active as active
          from templates_sms as t
          where id = '{$tid}'
          and active > 0  AND accountId=".Config::get('loggedIn');

    if (count($r = Config::get('db')->get_results($q)) > 0)
      return $r[0];

    return false;
  }
  
  public static function find_sms_by_surveys_id($sid) {
    $q = "SELECT
          t.id as id,
          t.name as name,
          t.message as message,
          t.active as active
          from templates_sms as t
          where survey = '{$sid}'
          and active > 0 AND accountId=".Config::get('loggedIn');

    if (count($r = Config::get('db')->get_results($q)) > 0)
      return $r;

    return false;
  }

  public static function create_sms($d) {
    Config::get('db')->insert('`templates_sms`', $d);
    return Config::get('db')->lastid();
  }

  public static function update_sms($template_id, $d) {
    $where = array('id' => $template_id);
    return Config::get('db')->update('`templates_sms`', $d, $where);
  }

  public static function delete_sms($template_id) {
    $d = array('active' => 0);
    $where = array('id' => $template_id);
    return Config::get('db')->update('`templates_sms`', $d, $where);
  }

}
