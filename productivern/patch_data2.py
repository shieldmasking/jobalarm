content = open('C:/Users/rstre/Projects/jobalarm/productivern/inc/data.php', 'rb').read().decode('utf-8')
results = []

# ─────────────────────────────────────────────
# 2. UPDATE ChangeUserCoverage
# ─────────────────────────────────────────────
old2 = (
    "$endTime = (isset($_REQUEST['endTime']) && $_REQUEST['endTime'] != '') ? $_REQUEST['endTime'] : '';\r\n"
    "\r\n"
    "\tif ($targetUserId > 0) {\r\n"
    "\t\t$check = Config::get('db')->get_results(\"SELECT id FROM `productiveUser` WHERE `id`={$targetUserId} AND `accountId`={$accountId}\");\r\n"
    "\t\tif (!$check) { echo json_encode(array('success'=>false,'message'=>'Not authorized')); return false; }\r\n"
    "\t\t$data = array(\r\n"
    "\t\t\t'coverUserId' => $coverUserId,\r\n"
    "\t\t\t'coverEndDate' => $endDate,\r\n"
    "\t\t\t'coverEndTime' => $endTime\r\n"
    "\t\t);"
)
new2 = (
    "$endTime = (isset($_REQUEST['endTime']) && $_REQUEST['endTime'] != '') ? $_REQUEST['endTime'] : '';\r\n"
    "\t$pauseSms = (isset($_REQUEST['pauseSms'])) ? intval($_REQUEST['pauseSms']) : 0;\r\n"
    "\t$varianceMin = (isset($_REQUEST['varianceMin'])) ? intval($_REQUEST['varianceMin']) : 1;\r\n"
    "\t$qblMin = (isset($_REQUEST['qblMin'])) ? intval($_REQUEST['qblMin']) : 300;\r\n"
    "\r\n"
    "\tif ($targetUserId > 0) {\r\n"
    "\t\t$check = Config::get('db')->get_results(\"SELECT id FROM `productiveUser` WHERE `id`={$targetUserId} AND `accountId`={$accountId}\");\r\n"
    "\t\tif (!$check) { echo json_encode(array('success'=>false,'message'=>'Not authorized')); return false; }\r\n"
    "\t\tif ($coverUserId == 0) {\r\n"
    "\t\t\t$data = array('coverUserId'=>0,'coverEndDate'=>'0000-00-00','coverEndTime'=>'','coverPauseSms'=>0,'coverVarianceMin'=>1,'coverQblMin'=>300);\r\n"
    "\t\t} else {\r\n"
    "\t\t\t$data = array(\r\n"
    "\t\t\t\t'coverUserId' => $coverUserId,\r\n"
    "\t\t\t\t'coverEndDate' => $endDate,\r\n"
    "\t\t\t\t'coverEndTime' => $endTime,\r\n"
    "\t\t\t\t'coverPauseSms' => $pauseSms,\r\n"
    "\t\t\t\t'coverVarianceMin' => $varianceMin,\r\n"
    "\t\t\t\t'coverQblMin' => $qblMin\r\n"
    "\t\t\t);\r\n"
    "\t\t}"
)
if old2 in content:
    content = content.replace(old2, new2, 1)
    results.append('2. ChangeUserCoverage OK')
else:
    results.append('2. ChangeUserCoverage NOT FOUND')

# ─────────────────────────────────────────────
# 4. GetAllUsers – own row clickable only
# ─────────────────────────────────────────────
old4 = (
    "\t\tif(intval($job['coverUserId']) > 0 && $job['cover_first_E']) {\r\n"
    "\t\t\t$coverFirst = openssl_decrypt($job['cover_first_E'], $ciphering, $encryption_key, $options, $encryption_iv);\r\n"
    "\t\t\t$coverLast = openssl_decrypt($job['cover_last_E'], $ciphering, $encryption_key, $options, $encryption_iv);\r\n"
    "\t\t\t$cover = '<a href=\"javascript:;\" onclick=\"tj.userCoverage(' . $job['id'] . ')\">' . $coverLast . ', ' . $coverFirst . '</a>';\r\n"
    "\t\t} else {\r\n"
    "\t\t\t$cover = '<a href=\"javascript:;\" onclick=\"tj.userCoverage(' . $job['id'] . ')\">Add</a>';\r\n"
    "\t\t}"
)
new4 = (
    "\t\tif (intval($job['id']) == intval($userId)) {\r\n"
    "\t\t\tif(intval($job['coverUserId']) > 0 && $job['cover_first_E']) {\r\n"
    "\t\t\t\t$coverFirst = openssl_decrypt($job['cover_first_E'], $ciphering, $encryption_key, $options, $encryption_iv);\r\n"
    "\t\t\t\t$coverLast = openssl_decrypt($job['cover_last_E'], $ciphering, $encryption_key, $options, $encryption_iv);\r\n"
    "\t\t\t\t$cover = '<a href=\"javascript:;\" onclick=\"tj.userCoverage(' . $job['id'] . ')\">' . $coverLast . ', ' . $coverFirst . '</a>';\r\n"
    "\t\t\t} else {\r\n"
    "\t\t\t\t$cover = '<a href=\"javascript:;\" onclick=\"tj.userCoverage(' . $job['id'] . ')\">Set</a>';\r\n"
    "\t\t\t}\r\n"
    "\t\t} else {\r\n"
    "\t\t\tif(intval($job['coverUserId']) > 0 && $job['cover_first_E']) {\r\n"
    "\t\t\t\t$coverFirst = openssl_decrypt($job['cover_first_E'], $ciphering, $encryption_key, $options, $encryption_iv);\r\n"
    "\t\t\t\t$coverLast = openssl_decrypt($job['cover_last_E'], $ciphering, $encryption_key, $options, $encryption_iv);\r\n"
    "\t\t\t\t$cover = $coverLast . ', ' . $coverFirst;\r\n"
    "\t\t\t} else {\r\n"
    "\t\t\t\t$cover = '';\r\n"
    "\t\t\t}\r\n"
    "\t\t}"
)
if old4 in content:
    content = content.replace(old4, new4, 1)
    results.append('4. GetAllUsers coverage OK')
else:
    results.append('4. GetAllUsers coverage NOT FOUND')

# ─────────────────────────────────────────────
# 5. UpdateProdTest report SMS
# ─────────────────────────────────────────────
old5 = (
    "\t\t\t\t$messages[] = $xmlMsg;\r\n"
    "                $mobile = \"1\" . $mobile;\r\n"
    "                $numbers[] = $mobile;\r\n"
    "                $keywords[] = $keyword2;\r\n"
    "\t\t\t\t}\r\n"
    "\t\t\t\t\r\n"
    "\t\t\t\t$smsoptions = Array(\r\n"
    "\t\t\t\t\t'numbers' => ($numbers ?? ''),\r\n"
    "\t\t\t\t\t'message' => ($messages ?? '')\r\n"
    "\r\n"
    "\t\t\t\t);\r\n"
    "\t\t\t\t\r\n"
    "\t\t\t\t}\r\n"
    "\t\t\t\t\t\t\t\t\r\n"
    "\t\t\t\tif($x==1){\r\n"
    "\t\t\t\t$result = sendNow($smsoptions);\r\n"
    "\t\t\t\t}"
)
new5 = (
    "\t\t\t\t$coverage = checkUserCoverage($textId);\r\n"
    "\t\t\t\t$coverPause = ($coverage) ? $coverage['coverPauseSms'] : 0;\r\n"
    "\t\t\t\tif (!($coverage && $coverPause == 1)) {\r\n"
    "\t\t\t\t\t$messages[] = $xmlMsg;\r\n"
    "\t\t\t\t\t$numbers[] = \"1\" . $mobile;\r\n"
    "\t\t\t\t\t$keywords[] = $keyword2;\r\n"
    "\t\t\t\t}\r\n"
    "\t\t\t\tif ($coverage && ($coverage['coverVarianceMin'] == 0 || abs($nvariance) >= $coverage['coverVarianceMin'])) {\r\n"
    "\t\t\t\t\t$covTok = bin2hex(random_bytes(32));\r\n"
    "\t\t\t\t\t$covExp = date('Y-m-d H:i:s', strtotime('+12 hours'));\r\n"
    "\t\t\t\t\tConfig::get('db')->insert('staffing_tokens', array('token'=>$covTok,'user_id'=>$coverage['userId'],'account_id'=>$dbText['accountId'],'expires_at'=>$covExp));\r\n"
    "\t\t\t\t\t$covLink = \"https://www.productivern.com/manager-view.php?t=\" . $covTok;\r\n"
    "\t\t\t\t\t$covMsg = $alertName . \" Report Alert (Coverage): A \".$prodTerm.\" Report has been submitted \".$newVar.\" in \".$dep.\". \".$covLink;\r\n"
    "\t\t\t\t\t$covXml = \"<message id=\\\"\".$coverage['mobile'].$now.\"\\\"><partnerpassword>\".$psword.\"</partnerpassword><content><![CDATA[\".$covMsg.\"]]></content></message>\";\r\n"
    "\t\t\t\t\t$messages[] = $covXml;\r\n"
    "\t\t\t\t\t$numbers[] = \"1\" . $coverage['mobile'];\r\n"
    "\t\t\t\t\t$keywords[] = $keyword2;\r\n"
    "\t\t\t\t}\r\n"
    "\t\t\t\t}\r\n"
    "\t\t\t\t\r\n"
    "\t\t\t\t$smsoptions = Array(\r\n"
    "\t\t\t\t\t'numbers' => ($numbers ?? ''),\r\n"
    "\t\t\t\t\t'message' => ($messages ?? '')\r\n"
    "\r\n"
    "\t\t\t\t);\r\n"
    "\t\t\t\t\r\n"
    "\t\t\t\t}\r\n"
    "\t\t\t\t\t\t\t\t\r\n"
    "\t\t\t\tif($x==1){\r\n"
    "\t\t\t\t$result = sendNow($smsoptions);\r\n"
    "\t\t\t\t}"
)
if old5 in content:
    content = content.replace(old5, new5, 1)
    results.append('5. UpdateProdTest OK')
else:
    results.append('5. UpdateProdTest NOT FOUND')

# ─────────────────────────────────────────────
# 6. sendescalationText SMS
# ─────────────────────────────────────────────
old6 = (
    "                $messages[] = $xmlMsg;\r\n"
    "                $mobile = \"1\" . $mobile;\r\n"
    "                $numbers[] = $mobile;\r\n"
    "                $keywords[] = $keyword2;\r\n"
    "\t\t\t\t}\r\n"
    "\t\t\t\t\r\n"
    "\t\t\t\t$smsoptions = Array(\r\n"
    "\t\t\t\t\t'numbers' => ($numbers ?? ''),\r\n"
    "\t\t\t\t\t'message' => ($messages ?? ''),\r\n"
    "\t\t\t\t\t'keyword' => $keywords,\r\n"
    "\t\t\t\t\t'login' => $slooce,\r\n"
    "\t\t\t\t\t'shortCode' => $shortCode\r\n"
    "\r\n"
    "\t\t\t\t);\r\n"
    "\t\t\t\t\r\n"
    "\t\t\t\t}\r\n"
    "\t\t\t\t\t\t\t\t\r\n"
    "\t\t\t\t$result = sendescNow($smsoptions);\r\n"
    "\t\t\t}"
)
new6 = (
    "\t\t\t\t$coverage = checkUserCoverage($textId);\r\n"
    "\t\t\t\t$coverPause = ($coverage) ? $coverage['coverPauseSms'] : 0;\r\n"
    "\t\t\t\tif (!($coverage && $coverPause == 1)) {\r\n"
    "\t\t\t\t\t$messages[] = $xmlMsg;\r\n"
    "\t\t\t\t\t$numbers[] = \"1\" . $mobile;\r\n"
    "\t\t\t\t\t$keywords[] = $keyword2;\r\n"
    "\t\t\t\t}\r\n"
    "\t\t\t\tif ($coverage) {\r\n"
    "\t\t\t\t\t$covEscTok = bin2hex(random_bytes(32));\r\n"
    "\t\t\t\t\t$covEscExp = date('Y-m-d H:i:s', strtotime('+12 hours'));\r\n"
    "\t\t\t\t\tConfig::get('db')->insert('staffing_tokens', array('token'=>$covEscTok,'user_id'=>$coverage['userId'],'account_id'=>$dbText['accountId'],'expires_at'=>$covEscExp));\r\n"
    "\t\t\t\t\t$covEscLink = \"https://www.productivern.com/manager-view.php?t=\" . $covEscTok;\r\n"
    "\t\t\t\t\t$covEscMsg = \" Escalation Response for \".$deptName.\" from \".$escUser.\": \".$response.\". \".$covEscLink;\r\n"
    "\t\t\t\t\t$covEscXml = \"<message id=\\\"\".$coverage['mobile'].$now.\"\\\"><partnerpassword>\".$psword.\"</partnerpassword><content><![CDATA[\".$covEscMsg.\"]]></content></message>\";\r\n"
    "\t\t\t\t\t$messages[] = $covEscXml;\r\n"
    "\t\t\t\t\t$numbers[] = \"1\" . $coverage['mobile'];\r\n"
    "\t\t\t\t\t$keywords[] = $keyword2;\r\n"
    "\t\t\t\t}\r\n"
    "\t\t\t\t}\r\n"
    "\t\t\t\t\r\n"
    "\t\t\t\t$smsoptions = Array(\r\n"
    "\t\t\t\t\t'numbers' => ($numbers ?? ''),\r\n"
    "\t\t\t\t\t'message' => ($messages ?? ''),\r\n"
    "\t\t\t\t\t'keyword' => $keywords,\r\n"
    "\t\t\t\t\t'login' => $slooce,\r\n"
    "\t\t\t\t\t'shortCode' => $shortCode\r\n"
    "\r\n"
    "\t\t\t\t);\r\n"
    "\t\t\t\t\r\n"
    "\t\t\t\t}\r\n"
    "\t\t\t\t\t\t\t\t\r\n"
    "\t\t\t\t$result = sendescNow($smsoptions);\r\n"
    "\t\t\t}"
)
if old6 in content:
    content = content.replace(old6, new6, 1)
    results.append('6. sendescalationText OK')
else:
    results.append('6. sendescalationText NOT FOUND')

# ─────────────────────────────────────────────
# 7. sendqblAlert – always send to cover user
# ─────────────────────────────────────────────
old7 = (
    "                $messages[] = $xmlMsg;\r\n"
    "                $mobile = \"1\" . $mobile;\r\n"
    "                $numbers[] = $mobile;\r\n"
    "                $keywords[] = $keyword2;\r\n"
    "\t\t\t\t\r\n"
    "\t\t\t\t\r\n"
    "\t\t\t\t$smsoptions = Array(\r\n"
    "\t\t\t\t\t'numbers' => ($numbers ?? ''),\r\n"
    "\t\t\t\t\t'message' => ($messages ?? ''),\r\n"
    "\t\t\t\t\t'keyword' => $keywords,\r\n"
    "\t\t\t\t\t'login' => $slooce\r\n"
    "\r\n"
    "\t\t\t\t);\r\n"
    "\t\t\t\t\r\n"
    "\t\t\t\t}\r\n"
    "\t\t\t\t\t\t\t\t\r\n"
    "\t\t\t\t$result = sendescNow($smsoptions);\r\n"
    "\t\t\t}\t\r\n"
    "\t\t\t\r\n"
)
new7 = (
    "\t\t\t\t$textId = $dbText['userId'];\r\n"
    "\t\t\t\t$coverage = checkUserCoverage($textId);\r\n"
    "\t\t\t\t$coverPause = ($coverage) ? $coverage['coverPauseSms'] : 0;\r\n"
    "\t\t\t\tif (!($coverage && $coverPause == 1)) {\r\n"
    "\t\t\t\t\t$messages[] = $xmlMsg;\r\n"
    "\t\t\t\t\t$numbers[] = \"1\" . $mobile;\r\n"
    "\t\t\t\t\t$keywords[] = $keyword2;\r\n"
    "\t\t\t\t}\r\n"
    "\t\t\t\tif ($coverage) {\r\n"
    "\t\t\t\t\t$covQTok = bin2hex(random_bytes(32));\r\n"
    "\t\t\t\t\t$covQExp = date('Y-m-d H:i:s', strtotime('+12 hours'));\r\n"
    "\t\t\t\t\tConfig::get('db')->insert('manager_tokens', array('token'=>$covQTok,'user_id'=>$coverage['userId'],'account_id'=>$accountId,'context'=>'qbl','ref_id'=>intval($deptId),'expires_at'=>$covQExp));\r\n"
    "\t\t\t\t\t$covQLink = \"https://www.productivern.com/manager-view.php?t=\" . $covQTok;\r\n"
    "\t\t\t\t\tif(intval($msgId)==1){ $covQMsg = \"Please contact \".$deptName.\" immediately regarding a possible hemorrhage in process.  \".$covQLink; }\r\n"
    "\t\t\t\t\telse { $covQMsg = \"A QBL Report has been created in \".$deptName.\".  \".$covQLink; }\r\n"
    "\t\t\t\t\t$covQXml = \"<message id=\\\"\".$coverage['mobile'].$now.\"\\\"><partnerpassword>\".$psword.\"</partnerpassword><content><![CDATA[\".$covQMsg.\"]]></content></message>\";\r\n"
    "\t\t\t\t\t$messages[] = $covQXml;\r\n"
    "\t\t\t\t\t$numbers[] = \"1\" . $coverage['mobile'];\r\n"
    "\t\t\t\t\t$keywords[] = $keyword2;\r\n"
    "\t\t\t\t}\r\n"
    "\t\t\t\t\r\n"
    "\t\t\t\t$smsoptions = Array(\r\n"
    "\t\t\t\t\t'numbers' => ($numbers ?? ''),\r\n"
    "\t\t\t\t\t'message' => ($messages ?? ''),\r\n"
    "\t\t\t\t\t'keyword' => $keywords,\r\n"
    "\t\t\t\t\t'login' => $slooce\r\n"
    "\r\n"
    "\t\t\t\t);\r\n"
    "\t\t\t\t\r\n"
    "\t\t\t\t}\r\n"
    "\t\t\t\t\t\t\t\t\r\n"
    "\t\t\t\t$result = sendescNow($smsoptions);\r\n"
    "\t\t\t}\t\r\n"
    "\t\t\t\r\n"
)
if old7 in content:
    content = content.replace(old7, new7, 1)
    results.append('7. sendqblAlert OK')
else:
    results.append('7. sendqblAlert NOT FOUND')

# ─────────────────────────────────────────────
# 8. sendqblEsc – QBL threshold check
# ─────────────────────────────────────────────
old8 = (
    "                $messages[] = $xmlMsg;\r\n"
    "                $mobile = \"1\" . $mobile;\r\n"
    "                $numbers[] = $mobile;\r\n"
    "                $keywords[] = $keyword2;\r\n"
    "\t\t\t\t\r\n"
    "\t\t\t\t\r\n"
    "\t\t\t\t$smsoptions = Array(\r\n"
    "\t\t\t\t\t'numbers' => ($numbers ?? ''),\r\n"
    "\t\t\t\t\t'message' => ($messages ?? ''),\r\n"
    "\t\t\t\t\t'keyword' => $keywords,\r\n"
    "\t\t\t\t\t'login' => $slooce\r\n"
    "\r\n"
    "\t\t\t\t);\r\n"
    "\t\t\t\t\r\n"
    "\t\t\t\t}"
)
new8 = (
    "\t\t\t\t$textId = $dbText['userId'];\r\n"
    "\t\t\t\t$coverage = checkUserCoverage($textId);\r\n"
    "\t\t\t\t$coverPause = ($coverage) ? $coverage['coverPauseSms'] : 0;\r\n"
    "\t\t\t\tif (!($coverage && $coverPause == 1)) {\r\n"
    "\t\t\t\t\t$messages[] = $xmlMsg;\r\n"
    "\t\t\t\t\t$numbers[] = \"1\" . $mobile;\r\n"
    "\t\t\t\t\t$keywords[] = $keyword2;\r\n"
    "\t\t\t\t}\r\n"
    "\t\t\t\tif ($coverage && floatval($qbl) >= $coverage['coverQblMin']) {\r\n"
    "\t\t\t\t\t$covQETok = bin2hex(random_bytes(32));\r\n"
    "\t\t\t\t\t$covQEExp = date('Y-m-d H:i:s', strtotime('+12 hours'));\r\n"
    "\t\t\t\t\tConfig::get('db')->insert('manager_tokens', array('token'=>$covQETok,'user_id'=>$coverage['userId'],'account_id'=>Config::get('account')['accountId'],'context'=>'qbl','ref_id'=>intval($deptId),'expires_at'=>$covQEExp));\r\n"
    "\t\t\t\t\t$covQELink = \"https://www.productivern.com/manager-view.php?t=\" . $covQETok;\r\n"
    "\t\t\t\t\tif(intval($msgId)==1){ $covQEMsg = \" A QBL ALERT has been created in \".$deptName.\".  \".$covQELink; }\r\n"
    "\t\t\t\t\telse { $qblLabelCov = floatval($qbl)>=1000 ? $qbl.\"ml Possible Hemorrhage\" : $qbl.\"ml\"; $covQEMsg = \"A QBL Notification (\".$qblLabelCov.\") has been created in \".$deptName.\".  \".$covQELink; }\r\n"
    "\t\t\t\t\t$covQEXml = \"<message id=\\\"\".$coverage['mobile'].$now.\"\\\"><partnerpassword>\".$psword.\"</partnerpassword><content><![CDATA[\".$covQEMsg.\"]]></content></message>\";\r\n"
    "\t\t\t\t\t$messages[] = $covQEXml;\r\n"
    "\t\t\t\t\t$numbers[] = \"1\" . $coverage['mobile'];\r\n"
    "\t\t\t\t\t$keywords[] = $keyword2;\r\n"
    "\t\t\t\t}\r\n"
    "\t\t\t\t\r\n"
    "\t\t\t\t$smsoptions = Array(\r\n"
    "\t\t\t\t\t'numbers' => ($numbers ?? ''),\r\n"
    "\t\t\t\t\t'message' => ($messages ?? ''),\r\n"
    "\t\t\t\t\t'keyword' => $keywords,\r\n"
    "\t\t\t\t\t'login' => $slooce\r\n"
    "\r\n"
    "\t\t\t\t);\r\n"
    "\t\t\t\t\r\n"
    "\t\t\t\t}"
)
if old8 in content:
    content = content.replace(old8, new8, 1)
    results.append('8. sendqblEsc OK')
else:
    results.append('8. sendqblEsc NOT FOUND')

open('C:/Users/rstre/Projects/jobalarm/productivern/inc/data.php', 'wb').write(content.encode('utf-8'))

for r in results:
    print(r)
print('data.php saved')
