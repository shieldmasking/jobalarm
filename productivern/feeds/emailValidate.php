<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../inc/class.db.php';
include '../inc/config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../inc/PHPMailer/src/Exception.php';
require '../inc/PHPMailer/src/PHPMailer.php';
require '../inc/PHPMailer/src/SMTP.php';

/**
 * SMTP probe — returns false only on definitive 550 (mailbox does not exist).
 * Returns null on connect failure, catch-all, or any ambiguous response.
 */
function checkEmailSMTP($email) {
    $domain = substr(strrchr($email, '@'), 1);

    if (!checkdnsrr($domain, 'MX')) return null;

    getmxrr($domain, $mxhosts, $mxweights);
    if (empty($mxhosts)) return null;

    array_multisort($mxweights, SORT_ASC, $mxhosts);

    $sock = @fsockopen($mxhosts[0], 25, $errno, $errstr, 10);
    if (!$sock) return null;

    stream_set_timeout($sock, 10);

    $line = fgets($sock, 512);
    if (substr($line, 0, 3) !== '220') { fclose($sock); return null; }

    fputs($sock, "EHLO productivern.com\r\n");
    do { $line = fgets($sock, 512); } while ($line && $line[3] === '-');

    fputs($sock, "MAIL FROM:<noreply@productivern.com>\r\n");
    $line = fgets($sock, 512);
    if (!$line || intval(substr($line, 0, 3)) !== 250) { fclose($sock); return null; }

    fputs($sock, "RCPT TO:<{$email}>\r\n");
    $line = fgets($sock, 512);
    $code = intval(substr($line, 0, 3));

    fputs($sock, "QUIT\r\n");
    fclose($sock);

    return ($code === 550) ? false : null;
}

// All accounts with at least one escAlerts=1 user
$accounts = Config::get('db')->get_results(
    "SELECT DISTINCT u.accountId, a.name AS accountName
     FROM productiveUser u
     JOIN productiveDeptXref x ON x.userId = u.id
     JOIN productiveAccount a ON a.id = u.accountId
     WHERE x.escAlerts = 1 AND u.active > 0 AND u.email LIKE '%@%'
     ORDER BY u.accountId"
);

if (!$accounts) exit;

foreach ($accounts as $acct) {
    $accountId = intval($acct['accountId']);

    $users = Config::get('db')->get_results(
        "SELECT DISTINCT u.id, u.first_name, u.last_name, u.email
         FROM productiveUser u
         JOIN productiveDeptXref x ON x.userId = u.id
         WHERE u.accountId = {$accountId} AND x.escAlerts = 1
         AND u.active > 0 AND u.email LIKE '%@%'"
    );

    $invalid = [];
    foreach ($users as $user) {
        if (checkEmailSMTP($user['email']) === false) {
            $invalid[] = $user;
        }
    }

    if (empty($invalid)) continue;

    // Directors/executives for this account (role >= 8)
    $managers = Config::get('db')->get_results(
        "SELECT u.first_name, u.last_name, u.email
         FROM productiveUser u
         WHERE u.accountId = {$accountId} AND u.role >= 8 AND u.active > 0
         AND u.email LIKE '%@%'
         GROUP BY u.email"
    );

    if (!$managers) continue;

    $rows = '';
    foreach ($invalid as $u) {
        $rows .= '<tr><td style="padding:6px 12px">' . htmlspecialchars($u['first_name'] . ' ' . $u['last_name']) . '</td>'
               . '<td style="padding:6px 12px">' . htmlspecialchars($u['email']) . '</td></tr>';
    }

    $body = '<p style="font-family:Arial,sans-serif;font-size:14px">The following user(s) at <strong>'
        . htmlspecialchars($acct['accountName'])
        . '</strong> have invalid email addresses and are currently enabled for QBL SMS alerts. '
        . 'Please review and remove them from QBL notifications if they are no longer with your organization.</p>'
        . '<table border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;font-family:Arial,sans-serif;font-size:14px;border-color:#ddd">'
        . '<tr style="background:#f5f5f5"><th style="padding:6px 12px;text-align:left">Name</th><th style="padding:6px 12px;text-align:left">Email</th></tr>'
        . $rows
        . '</table>'
        . '<p style="font-family:Arial,sans-serif;font-size:12px;color:#888;margin-top:16px">To remove a user from QBL alerts: QBL &rarr; SMS Settings in ProductivRN.</p>';

    $mail = new PHPMailer(true);
    $mail->IsSMTP();
    $mail->Host       = 'productivern.com';
    $mail->Port       = 465;
    $mail->SMTPAuth   = true;
    $mail->Username   = 'noreply@productivern.com';
    $mail->Password   = 'QL_X^8z@J)90';
    $mail->SMTPSecure = 'ssl';
    $mail->From       = 'noreply@productivern.com';
    $mail->FromName   = 'ProductivRN';

    foreach ($managers as $mgr) {
        $mail->addAddress(trim($mgr['email']), $mgr['first_name'] . ' ' . $mgr['last_name']);
    }
    $mail->addBCC('rstrenger@productivern.com');

    $mail->isHTML(true);
    $mail->Subject = 'Action Required: Invalid Email — QBL Alert User at ' . $acct['accountName'];
    $mail->Body    = $body;

    try {
        $mail->send();
        echo "Alert sent: {$acct['accountName']}\n";
    } catch (Exception $e) {
        echo "Failed: {$acct['accountName']} — " . $mail->ErrorInfo . "\n";
    }

    $mail->clearAddresses();
}
