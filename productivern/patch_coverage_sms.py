content = open('C:/Users/rstre/Projects/jobalarm/productivern/inc/data.php', 'rb').read().decode('utf-8')
results = []

# Replace email block with SMS for coverage assignment notification
old = (
    "\t\t\tif ($coverUserId > 0) {\r\n"
    "\t\t\t\t// Send email to covering user\r\n"
    "\t\t\t\t$dbCover = Config::get('db')->get_results(\"\r\n"
    "\t\t\t\t\tSELECT first_name_E, last_name_E, email FROM `productiveUser`\r\n"
    "\t\t\t\t\tWHERE id = {$coverUserId} LIMIT 1\r\n"
    "\t\t\t\t\");\r\n"
    "\t\t\t\tif ($dbCover && $dbCover[0]['email']) {\r\n"
    "\t\t\t\t\t$covFirst = openssl_decrypt($dbCover[0]['first_name_E'], $ciphering, $enc_key, $opts, $enc_iv);\r\n"
    "\t\t\t\t\t$covLast  = openssl_decrypt($dbCover[0]['last_name_E'],  $ciphering, $enc_key, $opts, $enc_iv);\r\n"
    "\t\t\t\t\t$to = $dbCover[0]['email'];\r\n"
    "\r\n"
    "\t\t\t\t\t$startDisp = date('m/d/Y', strtotime($startDate));\r\n"
    "\t\t\t\t\tif ($startTime != '') { $startDisp .= ' at ' . date('g:i A', strtotime($startTime)); }\r\n"
    "\t\t\t\t\t$endDisp = ($endDate != '0000-00-00' && $endDate != '') ? date('m/d/Y', strtotime($endDate)) : 'open-ended';\r\n"
    "\t\t\t\t\tif ($endDate != '0000-00-00' && $endDate != '' && $endTime != '') { $endDisp .= ' at ' . date('g:i A', strtotime($endTime)); }\r\n"
    "\r\n"
    "\t\t\t\t\t$subject = $labelName . ' Coverage Assignment';\r\n"
    "\t\t\t\t\t$message = 'Hi ' . $covFirst . ',<br><br>';\r\n"
    "\t\t\t\t\t$message .= 'You have been designated as coverage for <strong>' . $origLast . ', ' . $origFirst . '</strong>';\r\n"
    "\t\t\t\t\t$message .= ' from <strong>' . $startDisp . '</strong> to <strong>' . $endDisp . '</strong>.<br><br>';\r\n"
    "\t\t\t\t\t$message .= 'During this time you will receive staffing report alerts and escalation messages on their behalf.<br><br>';\r\n"
    "\t\t\t\t\t$message .= '&mdash; ' . $labelName;\r\n"
    "\r\n"
    "\t\t\t\t\ttry {\r\n"
    "\t\t\t\t\t\t$mail = new PHPMailer(true);\r\n"
    "\t\t\t\t\t\t$mail->IsSMTP();\r\n"
    "\t\t\t\t\t\t$mail->Host       = 'productivern.com';\r\n"
    "\t\t\t\t\t\t$mail->Port       = 465;\r\n"
    "\t\t\t\t\t\t$mail->SMTPAuth   = true;\r\n"
    "\t\t\t\t\t\t$mail->SMTPDebug  = 0;\r\n"
    "\t\t\t\t\t\t$mail->Username   = 'noreply@productivern.com';\r\n"
    "\t\t\t\t\t\t$mail->Password   = 'QL_X^8z@J)90';\r\n"
    "\t\t\t\t\t\t$mail->SMTPSecure = 'ssl';\r\n"
    "\t\t\t\t\t\t$mail->From       = 'noreply@productivern.com';\r\n"
    "\t\t\t\t\t\t$mail->FromName   = $labelName;\r\n"
    "\t\t\t\t\t\t$mail->AddAddress($to);\r\n"
    "\t\t\t\t\t\t$mail->IsHTML(true);\r\n"
    "\t\t\t\t\t\t$mail->Subject = $subject;\r\n"
    "\t\t\t\t\t\t$mail->Body    = $message;\r\n"
    "\t\t\t\t\t\t$mail->Send();\r\n"
    "\t\t\t\t\t} catch (Exception $e) { /* silent */ }\r\n"
    "\t\t\t\t}\r\n"
    "\t\t\t}"
)
new = (
    "\t\t\tif ($coverUserId > 0) {\r\n"
    "\t\t\t\t// Send SMS to covering user\r\n"
    "\t\t\t\t$dbCover = Config::get('db')->get_results(\"\r\n"
    "\t\t\t\t\tSELECT mobile FROM `productiveUser`\r\n"
    "\t\t\t\t\tWHERE id = {$coverUserId} AND LENGTH(mobile)=10 LIMIT 1\r\n"
    "\t\t\t\t\");\r\n"
    "\t\t\t\tif ($dbCover) {\r\n"
    "\t\t\t\t\t$startDisp = date('m/d/Y', strtotime($startDate));\r\n"
    "\t\t\t\t\tif ($startTime != '') { $startDisp .= ' at ' . date('g:i A', strtotime($startTime)); }\r\n"
    "\t\t\t\t\t$endDisp = ($endDate != '0000-00-00' && $endDate != '') ? date('m/d/Y', strtotime($endDate)) : 'open-ended';\r\n"
    "\t\t\t\t\tif ($endDate != '0000-00-00' && $endDate != '' && $endTime != '') { $endDisp .= ' at ' . date('g:i A', strtotime($endTime)); }\r\n"
    "\r\n"
    "\t\t\t\t\t$psword   = 'J8775bcgEE2065';\r\n"
    "\t\t\t\t\t$keyword4 = 'PN18337370906';\r\n"
    "\t\t\t\t\t$slooce   = 'jobalarm45';\r\n"
    "\t\t\t\t\t$now      = time();\r\n"
    "\t\t\t\t\t$covMob   = $dbCover[0]['mobile'];\r\n"
    "\t\t\t\t\t$assignMsg = 'You have been designated as coverage for ' . $origLast . ', ' . $origFirst . ' from ' . $startDisp . ' to ' . $endDisp . '.';\r\n"
    "\t\t\t\t\t$assignXml = '<message id=\"' . $covMob . $now . '\"><partnerpassword>' . $psword . '</partnerpassword><content><![CDATA[' . $assignMsg . ']]></content></message>';\r\n"
    "\t\t\t\t\t$url = 'https://jobalarm.cloud.sloocetech.net/slooce_apps/spi/jobalarm45/1' . $covMob . '/' . $keyword4 . '/messages/startmt';\r\n"
    "\t\t\t\t\t$header = array('Content-Type: application/xml');\r\n"
    "\t\t\t\t\tcurl_request($slooce, $url, $header, $assignXml);\r\n"
    "\t\t\t\t}\r\n"
    "\t\t\t}"
)

if old in content:
    content = content.replace(old, new, 1)
    results.append('coverage assign SMS OK')
else:
    results.append('NOT FOUND')

open('C:/Users/rstre/Projects/jobalarm/productivern/inc/data.php', 'wb').write(content.encode('utf-8'))
for r in results:
    print(r)
print('done')
