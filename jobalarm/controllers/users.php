<?php
require_once('models/user.php');

class Users {
    
    public static function run() {
    }
    
    public static function forgot() {
        include "lib/phpMailer/class.phpmailer.php";
        
        $record = $_REQUEST['record'];
        $email = $record['emailaddy'];
        
        $user = User::getFromEmail(trim($email));
        
        if (!$user) {
            echo json_encode(array('status'=>'failure','msg'=>'Invalid email address.'));
            return false;
        }
        
        $subject = "JobAlarm - Password Reminder";
        $mail = new PHPMailer();
        //$mail->IsSMTP();                                      // set mailer to use SMTP
        //$mail->SMTPAuth = true;     // turn on SMTP authentication
        //$mail->SMTPSecure = 'tls';
        //$mail->Host = "smtp.gmail.com";  // specify main and backup server
        //$mail->Port = 587;
        //$mail->Username = "setzor@gmail.com";  // SMTP username
        //$mail->Password = "J0v14nM4st3r!"; // SMTP password    
        //$mail->SMTPDebug = 2;
        $mail->CharSet="UTF-8";
        //
        $mail->IsSendmail();

        $mail->From = 'noreply@jobalarm.com';
        $mail->AddReplyTo('noreply@jobalarm.com');
        $mail->FromName = 'JobAlarm Services';
        $mail->AddAddress($user['email'], $user['fullName']);  // Add a recipient

        $mail->IsHTML(true);                                  // Set email format to HTML

        $mail->Subject = $subject;

        
        $output = '<!DOCTYPE HTML>' . "\r\n";
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
        $output .= '<img style="display:block;float:left" src="http://jobalarm.com/admin/img/logo2.png" alt="JobAlarm" />'."\r\n";
        $output .= '</td></tr>' . "\r\n";
        $output .= "<tr><td colspan=\"2\">You have been sent this email as a password reminder.</td></tr>";
        $output .= "<tr><td colspan=\"2\"><br /><br />Your username is: <strong>{$user['username']}</strong></td></tr>";
        $output .= "<tr><td colspan=\"2\">Your password is: <strong>{$user['password']}</strong></td></tr>";
        $output .= "<tr><td colspan=\"2\"><br /><br />Please visit: <a target=\"_blank\" href=\"http://www.jobalarm.com/admin\">www.jobalarm.com/admin</a> to log in.</td></tr>";
        $output .= "</table>";
        $output .= "</body>";
        $output .= "</html>";
        $mail->Body = $output;

        //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        if (!$mail->Send()) {
            echo 'Mailer Error: ' . $mail->ErrorInfo;
            exit;
        }
        echo json_encode(array('status'=>'success'));
    }
    
    public static function getAll() {
        $cmd = (isset($_REQUEST['cmd'])) ? $_REQUEST['cmd'] : '';
        switch($cmd) {
            case 'delete-records':
                $records = $_REQUEST['selected'];
                foreach($records as $record) {
                    User::remove($record);
                }
                break;
        }
        $users = User::getList();
        $dataArray = array();
        if (is_array($users)) {
            foreach($users as $user) {
                $company = NULL;
                if ($user['companyId'] > 0) {
                    $company = Company::read($user['companyId']);
                }
                $companyText = ($company) ? $company['name'] : '';
                $dataArray[] = array(               
                    'recid'=>$user['id'],
                    'firstname'=>$user['firstName'],
                    'lastname'=>$user['lastName'],
                    'username'=>$user['username'],
                    'companyid'=>$user['companyId'],
                    'phone'=>$user['phone'],
                    'company'=>$companyText,
                    'email'=>$user['email'],
                    'security'=>($user['security']=='100') ? 'Admin' : 'User'
                    );
            }
        }
        $outArray = array(
            'status'=>'success',
            'total'=>count($dataArray),
            'records'=>$dataArray            
            );
        echo json_encode($outArray);   
    }
    public static function addUser() {
        $indata = $_REQUEST['record'];
        $userdata = array(
            'firstName'=>$indata['first_name'],
            'lastName'=>$indata['last_name'],
            'fullName'=>$indata['first_name'].' '.$indata['last_name'],
            'email'=>$indata['email'],
            'username'=>$indata['username'],
            'password'=>$indata['password'],
            'companyId'=>$indata['companyId'],
            'security'=>($indata['security'] == 'User') ? 10:100,
            'created'=>date('Y-m-d H:i:s'),
            'updated'=>date('Y-m-d H:i:s')
        );
        User::create($userdata);

        include "lib/phpMailer/class.phpmailer.php";
        
        $email = $indata['email'];
        
        
        $subject = "Welcome to JobAlarm - Account Details";
        $mail = new PHPMailer();
        //$mail->IsSMTP();                                      // set mailer to use SMTP
        //$mail->SMTPAuth = true;     // turn on SMTP authentication
        //$mail->SMTPSecure = 'tls';
        //$mail->Host = "smtp.gmail.com";  // specify main and backup server
        //$mail->Port = 587;
        //$mail->Username = "setzor@gmail.com";  // SMTP username
        //$mail->Password = "J0v14nM4st3r!"; // SMTP password    
        //$mail->SMTPDebug = 2;
        $mail->CharSet="UTF-8";
        //
        $mail->IsSendmail();

        $mail->From = 'noreply@jobalarm.com';
        $mail->AddReplyTo('noreply@jobalarm.com');
        $mail->FromName = 'JobAlarm Services';
        $mail->AddAddress($userdata['email'], $userdata['fullName']);  // Add a recipient

        $mail->IsHTML(true);                                  // Set email format to HTML

        $mail->Subject = $subject;

        
        $output = '<!DOCTYPE HTML>' . "\r\n";
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
        $output .= '<img style="display:block;float:left" src="http://jobalarm.com/admin/img/logo2.png" alt="JobAlarm" />'."\r\n";
        $output .= '</td></tr>' . "\r\n";
        $output .= "<tr><td colspan=\"2\">Your account on JobAlarm has been created.</td></tr>";
        $output .= "<tr><td colspan=\"2\"><br /><br />Your username is: <strong>{$userdata['username']}</strong></td></tr>";
        $output .= "<tr><td colspan=\"2\">Your password is: <strong>{$userdata['password']}</strong></td></tr>";
        $output .= "<tr><td colspan=\"2\"><br /><br />Please visit: <a target=\"_blank\" href=\"http://www.jobalarm.com/admin\">www.jobalarm.com/admin</a> to log in.</td></tr>";
        $output .= "<tr><td colspan=\"2\"><br /><br />Thanks,<br />JobAlarm Administration</td></tr>";
        $output .= "</table>";
        $output .= "</body>";
        $output .= "</html>";
        $mail->Body = $output;

        //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        if (!$mail->Send()) {
            echo json_encode(array('status'=>'success','msg'=>'Mailer Error: ' . $mail->ErrorInfo));
            exit;
        }        
        
        $response = array(
            'status'=>'success'
            );
        echo json_encode($response);
    }
    
    public static function getUserData($userId) {
        $user = User::load($userId);
        echo json_encode($user);
    }
    
    public static function editUser($userId) {
        $record = $_REQUEST['record'];
        $firstName = $record['firstName'];
        $lastName = $record['lastName'];
        $email = $record['email'];
        $userName = $record['username'];
        $password = $record['password'];
        $companyId = $record['companyId'];
        $security = $record['security'];
        
        $data = array(
            'firstName'=>$firstName,
            'lastName'=>$lastName,
            'email'=>$email,
            'username'=>$userName,
            'password'=>$password,
            'companyId'=>$companyId,
            'security' => $security
            );
        
        
        $where = array('id'=>$userId);
        
        Config::get('db')->update('user',$data,$where);
        
        echo json_encode(array('success'=>true));
        
    }
    
    public static function changePass() {
        $oldPass = $_REQUEST['record']['currentPass'];
        $newPass = $_REQUEST['record']['newPass'];
        $user = User::load();
        if ($oldPass != $user['password']) {
            echo json_encode(array('status'=>'error','message'=>'Invalid Password'));
            return false;
        }
        User::update(array('password'=>$newPass));
        echo json_encode(array('status'=>'success','message'=>'Password Changed Successfully'));
    }
    
    
    public static function getSurveyAccess($userId) {
        $surveyAccess = User::getSurveyAccessArray($userId);
        echo json_encode(array('surveyList'=>$surveyAccess));
    }
    
    public static function saveSurveyAccess($userId) {
        User::remAllSurveyAccess($userId);
        User::addSurveyAccess($userId,$_REQUEST['surveys']);
        echo json_encode(array('status'=>'success'));
    }
    
    public static function getAccessList($userId) {
        $access = User::getAllSurveyAccessList($userId);
        $outArray = array(
            'status'=>'success',
            'total'=>count($access),
            'records'=>$access
            );
        echo json_encode($outArray);
    }
    
    public static function register() {
        //$captcha = $_REQUEST['record']['g-recaptcha-response'];
        //$response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".CAPTCHA_KEY."&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']);
        //$obj = json_decode($response);
        //if($obj->success==false)
        //{
        //    echo json_encode(array('status'=>'error','message'=>'Failed captcha'));
        //}   else
        
        $indata = $_REQUEST['record'];

        $user = User::getFromEmail($indata['email']);
        if ($user) {
            echo json_encode(array('status'=>'error','message'=>'Email already exists.'));                        
            return false;
        }
        $userdata = array(
            'username' => $indata['email'],
            'email' => $indata['email'],
            'companyName' => $indata['company'],
            'firstName' => $indata['firstname'],
            'lastName' => $indata['lastname'],
            'password'=> $indata['password'],
            'phone' => $indata['phone'],
            'fullName' => $indata['firstname'].' '.$indata['lastname'],
            'security' => 10
            );
        Config::get('db')->insert('user',$userdata);
        if (Config::get('db')->lastid() > 0) 
        {            
            include "lib/phpMailer/class.phpmailer.php";
            
            $email = $indata['email'];
            
            
            $subject = "Welcome to JobAlarm - Account Details";
            $mail = new PHPMailer();
            $mail->CharSet="UTF-8";
            $mail->IsSendmail();

            $mail->From = 'noreply@jobalarm.com';
            $mail->AddReplyTo('noreply@jobalarm.com');
            $mail->FromName = 'JobAlarm Services';
            $mail->AddAddress($userdata['email'], $userdata['fullName']);  // Add a recipient
            
            $mail->AddBCC('rstrenger@premierssg.com','Ryan Strenger');
            
            $mail->IsHTML(true);                                  // Set email format to HTML

            $mail->Subject = $subject;

            
            $output = '<!DOCTYPE HTML>' . "\r\n";
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
            $output .= '<img style="display:block;float:left" src="http://jobalarm.com/admin/img/logo2.png" alt="JobAlarm" />'."\r\n";
            $output .= '</td></tr>' . "\r\n";
            $output .= "<tr><td colspan=\"2\">Your account on JobAlarm has been created.</td></tr>";
            $output .= "<tr><td colspan=\"2\">An Account Representative will be in contact with you shortly to activate your account.</td></tr>";
            $output .= "<tr><td colspan=\"2\"><br /><br />Your username is: <strong>{$userdata['username']}</strong></td></tr>";
            $output .= "<tr><td colspan=\"2\">Your password is: <strong>{$userdata['password']}</strong></td></tr>";
            $output .= "<tr><td colspan=\"2\"><br /><br />Please visit: <a target=\"_blank\" href=\"http://www.jobalarm.com/admin\">www.jobalarm.com/admin</a> to log in.</td></tr>";
            $output .= "<tr><td colspan=\"2\"><br /><br />Thanks,<br />JobAlarm Administration</td></tr>";
            $output .= "</table>";
            $output .= "</body>";
            $output .= "</html>";
            $mail->Body = $output;

            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            if (!$mail->Send()) {
                echo json_encode(array('status'=>'success','msg'=>'Mailer Error: ' . $mail->ErrorInfo));
                exit;
            }        

            $mail = new PHPMailer();
            $mail->CharSet="UTF-8";
            $mail->IsSendmail();

            $mail->From = 'noreply@jobalarm.com';
            $mail->AddReplyTo('noreply@jobalarm.com');
            $mail->FromName = 'JobAlarm Services';
            $mail->AddAddress('2148500163@txt.att.net', 'Ryan Strenger');  // Add a recipient
            
            $mail->IsHTML(false);                                  // Set email format to HTML

            $mail->Subject = 'New JobAlarm User Notification';

            
            $mail->Body = $userdata['fullName']. ' - '.$userdata['email'].' - '.$userdata['phone'];

            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            if (!$mail->Send()) {
                echo json_encode(array('status'=>'success','msg'=>'Mailer Error: ' . $mail->ErrorInfo));
                exit;
            }        
            
            echo json_encode(array('status'=>'success'));
        } else {        
            echo json_encode(array('status'=>'error','message'=>'Error registering new user.'));            
        }
    }
    
}