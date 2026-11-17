import sys

content = open('C:/Users/rstre/Projects/jobalarm/productivern/inc/data.php', 'rb').read().decode('utf-8')

results = []

# ─────────────────────────────────────────────
# 1. UPDATE GetUserCoverage
# ─────────────────────────────────────────────
old1 = 'IFNULL(u.coverEndDate,\'\') as coverEndDate, IFNULL(u.coverEndTime,\'\') as coverEndTime FROM `productiveUser` u WHERE u.id={$targetUserId} AND u.accountId={$accountId}")'
new1 = 'IFNULL(u.coverEndDate,\'\') as coverEndDate, IFNULL(u.coverEndTime,\'\') as coverEndTime, IFNULL(u.coverPauseSms,0) as coverPauseSms, IFNULL(u.coverVarianceMin,1) as coverVarianceMin, IFNULL(u.coverQblMin,300) as coverQblMin FROM `productiveUser` u WHERE u.id={$targetUserId} AND u.accountId={$accountId}")'
if old1 in content:
    content = content.replace(old1, new1, 1)
    results.append('1a. GetUserCoverage query OK')
else:
    results.append('1a. GetUserCoverage query NOT FOUND')

old1b = ("'coverEndTime' => ($u['coverEndTime'] == '00:00:00' ? '' : $u['coverEndTime'])\r\n"
         "\t\t);\r\n"
         "\t\techo json_encode(array('success' => true, 'data' => $data));")
new1b = ("'coverEndTime' => ($u['coverEndTime'] == '00:00:00' ? '' : $u['coverEndTime']),\r\n"
         "\t\t\t'coverPauseSms' => intval($u['coverPauseSms']),\r\n"
         "\t\t\t'coverVarianceMin' => intval($u['coverVarianceMin']),\r\n"
         "\t\t\t'coverQblMin' => intval($u['coverQblMin'])\r\n"
         "\t\t);\r\n"
         "\t\techo json_encode(array('success' => true, 'data' => $data));")
if old1b in content:
    content = content.replace(old1b, new1b, 1)
    results.append('1b. GetUserCoverage return OK')
else:
    results.append('1b. GetUserCoverage return NOT FOUND')

# ─────────────────────────────────────────────
# 2. UPDATE ChangeUserCoverage
# ─────────────────────────────────────────────
old2 = ("\t$endTime = (isset($_REQUEST['endTime']) && $_REQUEST['endTime'] != '') ? $_REQUEST['endTime'] : '';\r\n"
        "\r\n"
        "\tif (\$targetUserId > 0) {\r\n"
        "\t\t$check = Config::get('db')->get_results(\"SELECT id FROM `productiveUser` WHERE `id`={$targetUserId} AND `accountId`={$accountId}\");\r\n"
        "\t\tif (!$check) { echo json_encode(array('success'=>false,'message'=>'Not authorized')); return false; }\r\n"
        "\t\t$data = array(\r\n"
        "\t\t\t'coverUserId' => $coverUserId,\r\n"
        "\t\t\t'coverEndDate' => $endDate,\r\n"
        "\t\t\t'coverEndTime' => $endTime\r\n"
        "\t\t);")
new2 = ("\t$endTime = (isset($_REQUEST['endTime']) && $_REQUEST['endTime'] != '') ? $_REQUEST['endTime'] : '';\r\n"
        "\t\$pauseSms = (isset($_REQUEST['pauseSms'])) ? intval($_REQUEST['pauseSms']) : 0;\r\n"
        "\t\$varianceMin = (isset($_REQUEST['varianceMin'])) ? intval($_REQUEST['varianceMin']) : 1;\r\n"
        "\t\$qblMin = (isset($_REQUEST['qblMin'])) ? intval($_REQUEST['qblMin']) : 300;\r\n"
        "\r\n"
        "\tif (\$targetUserId > 0) {\r\n"
        "\t\t$check = Config::get('db')->get_results(\"SELECT id FROM `productiveUser` WHERE `id`={$targetUserId} AND `accountId`={$accountId}\");\r\n"
        "\t\tif (!$check) { echo json_encode(array('success'=>false,'message'=>'Not authorized')); return false; }\r\n"
        "\t\tif (\$coverUserId == 0) {\r\n"
        "\t\t\t\$data = array('coverUserId'=>0,'coverEndDate'=>'0000-00-00','coverEndTime'=>'','coverPauseSms'=>0,'coverVarianceMin'=>1,'coverQblMin'=>300);\r\n"
        "\t\t} else {\r\n"
        "\t\t\t$data = array(\r\n"
        "\t\t\t\t'coverUserId' => $coverUserId,\r\n"
        "\t\t\t\t'coverEndDate' => $endDate,\r\n"
        "\t\t\t\t'coverEndTime' => $endTime,\r\n"
        "\t\t\t\t'coverPauseSms' => \$pauseSms,\r\n"
        "\t\t\t\t'coverVarianceMin' => \$varianceMin,\r\n"
        "\t\t\t\t'coverQblMin' => \$qblMin\r\n"
        "\t\t\t);\r\n"
        "\t\t}")
if old2 in content:
    content = content.replace(old2, new2, 1)
    results.append('2. ChangeUserCoverage OK')
else:
    results.append('2. ChangeUserCoverage NOT FOUND')

# ─────────────────────────────────────────────
# 3. ADD checkUserCoverage helper
# ─────────────────────────────────────────────
helper = ("\r\n\r\n/////////////////////////////////////\r\n"
          "// CHECK IF USER HAS ACTIVE COVERAGE\r\n"
          "function checkUserCoverage(\$userId) {\r\n"
          "\t\$today = date('Y-m-d');\r\n"
          "\t\$nowTime = date('H:i:s');\r\n"
          "\t\$result = Config::get('db')->get_results(\"\r\n"
          "\t\tSELECT u.coverUserId, u.coverPauseSms, u.coverVarianceMin, u.coverQblMin,\r\n"
          "\t\t       u.coverEndDate, u.coverEndTime, cu.mobile, cu.id as coverUserDbId\r\n"
          "\t\tFROM `productiveUser` u\r\n"
          "\t\tLEFT JOIN `productiveUser` cu ON cu.id = u.coverUserId\r\n"
          "\t\tWHERE u.id = {\$userId}\r\n"
          "\t\tAND u.coverUserId > 0\r\n"
          "\t\tAND (u.coverEndDate = '0000-00-00' OR u.coverEndDate >= '{\$today}')\r\n"
          "\t\tAND LENGTH(cu.mobile) = 10\r\n"
          "\t\tLIMIT 1\r\n"
          "\t\");\r\n"
          "\tif (!\$result) return false;\r\n"
          "\t\$r = \$result[0];\r\n"
          "\tif (\$r['coverEndDate'] == \$today && \$r['coverEndTime'] != '' && \$r['coverEndTime'] != '00:00:00') {\r\n"
          "\t\tif (\$nowTime > \$r['coverEndTime']) return false;\r\n"
          "\t}\r\n"
          "\treturn array(\r\n"
          "\t\t'userId'          => intval(\$r['coverUserDbId']),\r\n"
          "\t\t'mobile'          => \$r['mobile'],\r\n"
          "\t\t'coverPauseSms'   => intval(\$r['coverPauseSms']),\r\n"
          "\t\t'coverVarianceMin' => intval(\$r['coverVarianceMin']),\r\n"
          "\t\t'coverQblMin'     => intval(\$r['coverQblMin'])\r\n"
          "\t);\r\n"
          "}\r\n")

anchor = "\r\n\r\n/////////////////////////////////////\r\n// GET DETAILS FOR SINGLE USER\r\nfunction GetUnitDetails()"
if anchor in content:
    content = content.replace(anchor, helper + anchor, 1)
    results.append('3. checkUserCoverage helper OK')
else:
    results.append('3. checkUserCoverage anchor NOT FOUND')

# ─────────────────────────────────────────────
# 4. GetAllUsers – own row clickable only
# ─────────────────────────────────────────────
old4 = ("\t\tif(intval($job['coverUserId']) > 0 && $job['cover_first_E']) {\r\n"
        "\t\t\t\$coverFirst = openssl_decrypt(\$job['cover_first_E'], \$ciphering, \$encryption_key, \$options, \$encryption_iv);\r\n"
        "\t\t\t\$coverLast = openssl_decrypt(\$job['cover_last_E'], \$ciphering, \$encryption_key, \$options, \$encryption_iv);\r\n"
        "\t\t\t\$cover = '<a href=\"javascript:;\" onclick=\"tj.userCoverage(' . \$job['id'] . ')\">' . \$coverLast . ', ' . \$coverFirst . '</a>';\r\n"
        "\t\t} else {\r\n"
        "\t\t\t\$cover = '<a href=\"javascript:;\" onclick=\"tj.userCoverage(' . \$job['id'] . ')\">Add</a>';\r\n"
        "\t\t}")
new4 = ("\t\tif (intval(\$job['id']) == intval(\$userId)) {\r\n"
        "\t\t\tif(intval(\$job['coverUserId']) > 0 && \$job['cover_first_E']) {\r\n"
        "\t\t\t\t\$coverFirst = openssl_decrypt(\$job['cover_first_E'], \$ciphering, \$encryption_key, \$options, \$encryption_iv);\r\n"
        "\t\t\t\t\$coverLast = openssl_decrypt(\$job['cover_last_E'], \$ciphering, \$encryption_key, \$options, \$encryption_iv);\r\n"
        "\t\t\t\t\$cover = '<a href=\"javascript:;\" onclick=\"tj.userCoverage(' . \$job['id'] . ')\">' . \$coverLast . ', ' . \$coverFirst . '</a>';\r\n"
        "\t\t\t} else {\r\n"
        "\t\t\t\t\$cover = '<a href=\"javascript:;\" onclick=\"tj.userCoverage(' . \$job['id'] . ')\">Set</a>';\r\n"
        "\t\t\t}\r\n"
        "\t\t} else {\r\n"
        "\t\t\tif(intval(\$job['coverUserId']) > 0 && \$job['cover_first_E']) {\r\n"
        "\t\t\t\t\$coverFirst = openssl_decrypt(\$job['cover_first_E'], \$ciphering, \$encryption_key, \$options, \$encryption_iv);\r\n"
        "\t\t\t\t\$coverLast = openssl_decrypt(\$job['cover_last_E'], \$ciphering, \$encryption_key, \$options, \$encryption_iv);\r\n"
        "\t\t\t\t\$cover = \$coverLast . ', ' . \$coverFirst;\r\n"
        "\t\t\t} else {\r\n"
        "\t\t\t\t\$cover = '';\r\n"
        "\t\t\t}\r\n"
        "\t\t}")
if old4 in content:
    content = content.replace(old4, new4, 1)
    results.append('4. GetAllUsers coverage column OK')
else:
    results.append('4. GetAllUsers coverage NOT FOUND')

# ─────────────────────────────────────────────
# 5. UpdateProdTest report SMS
# ─────────────────────────────────────────────
old5 = ("				\$messages[] = \$xmlMsg;\r\n"
        "                \$mobile = \"1\" . \$mobile;\r\n"
        "                \$numbers[] = \$mobile;\r\n"
        "                \$keywords[] = \$keyword2;\r\n"
        "\t\t\t\t}\r\n"
        "\t\t\t\t\r\n"
        "\t\t\t\t\$smsoptions = Array(\r\n"
        "\t\t\t\t\t'numbers' => (\$numbers ?? ''),\r\n"
        "\t\t\t\t\t'message' => (\$messages ?? '')\r\n"
        "\r\n"
        "\t\t\t\t);")
new5 = ("				\$coverage = checkUserCoverage(\$textId);\r\n"
        "\t\t\t\t\$coverPause = (\$coverage) ? \$coverage['coverPauseSms'] : 0;\r\n"
        "\t\t\t\tif (!(\$coverage && \$coverPause == 1)) {\r\n"
        "\t\t\t\t\t\$messages[] = \$xmlMsg;\r\n"
        "\t\t\t\t\t\$numbers[] = \"1\" . \$mobile;\r\n"
        "\t\t\t\t\t\$keywords[] = \$keyword2;\r\n"
        "\t\t\t\t}\r\n"
        "\t\t\t\tif (\$coverage && (\$coverage['coverVarianceMin'] == 0 || abs(\$nvariance) >= \$coverage['coverVarianceMin'])) {\r\n"
        "\t\t\t\t\t\$covTok = bin2hex(random_bytes(32));\r\n"
        "\t\t\t\t\t\$covExp = date('Y-m-d H:i:s', strtotime('+12 hours'));\r\n"
        "\t\t\t\t\tConfig::get('db')->insert('staffing_tokens', array('token'=>\$covTok,'user_id'=>\$coverage['userId'],'account_id'=>\$dbText['accountId'],'expires_at'=>\$covExp));\r\n"
        "\t\t\t\t\t\$covLink = \"https://www.productivern.com/manager-view.php?t=\" . \$covTok;\r\n"
        "\t\t\t\t\t\$covMsg = \$alertName . \" Report Alert (Coverage): A \".\$prodTerm.\" Report has been submitted \".\$newVar.\" in \".\$dep.\". \".\$covLink;\r\n"
        "\t\t\t\t\t\$covXml = \"<message id=\\\"\".\$coverage['mobile'].\$now.\"\\\"><partnerpassword>\".\$psword.\"</partnerpassword><content><![CDATA[\".\$covMsg.\"]]></content></message>\";\r\n"
        "\t\t\t\t\t\$messages[] = \$covXml;\r\n"
        "\t\t\t\t\t\$numbers[] = \"1\" . \$coverage['mobile'];\r\n"
        "\t\t\t\t\t\$keywords[] = \$keyword2;\r\n"
        "\t\t\t\t}\r\n"
        "\t\t\t\t}\r\n"
        "\t\t\t\t\r\n"
        "\t\t\t\t\$smsoptions = Array(\r\n"
        "\t\t\t\t\t'numbers' => (\$numbers ?? ''),\r\n"
        "\t\t\t\t\t'message' => (\$messages ?? '')\r\n"
        "\r\n"
        "\t\t\t\t);")
if old5 in content:
    content = content.replace(old5, new5, 1)
    results.append('5. UpdateProdTest SMS OK')
else:
    results.append('5. UpdateProdTest NOT FOUND')

# ─────────────────────────────────────────────
# 6. sendescalationText SMS
# ─────────────────────────────────────────────
old6 = ("                \$messages[] = \$xmlMsg;\r\n"
        "                \$mobile = \"1\" . \$mobile;\r\n"
        "                \$numbers[] = \$mobile;\r\n"
        "                \$keywords[] = \$keyword2;\r\n"
        "\t\t\t\t}\r\n"
        "\t\t\t\t\r\n"
        "\t\t\t\t\$smsoptions = Array(\r\n"
        "\t\t\t\t\t'numbers' => (\$numbers ?? ''),\r\n"
        "\t\t\t\t\t'message' => (\$messages ?? ''),\r\n"
        "\t\t\t\t\t'keyword' => \$keywords,\r\n"
        "\t\t\t\t\t'login' => \$slooce,\r\n"
        "\t\t\t\t\t'shortCode' => \$shortCode\r\n"
        "\r\n"
        "\t\t\t\t);")
new6 = ("				\$coverage = checkUserCoverage(\$textId);\r\n"
        "\t\t\t\t\$coverPause = (\$coverage) ? \$coverage['coverPauseSms'] : 0;\r\n"
        "\t\t\t\tif (!(\$coverage && \$coverPause == 1)) {\r\n"
        "\t\t\t\t\t\$messages[] = \$xmlMsg;\r\n"
        "\t\t\t\t\t\$numbers[] = \"1\" . \$mobile;\r\n"
        "\t\t\t\t\t\$keywords[] = \$keyword2;\r\n"
        "\t\t\t\t}\r\n"
        "\t\t\t\tif (\$coverage) {\r\n"
        "\t\t\t\t\t\$covEscTok = bin2hex(random_bytes(32));\r\n"
        "\t\t\t\t\t\$covEscExp = date('Y-m-d H:i:s', strtotime('+12 hours'));\r\n"
        "\t\t\t\t\tConfig::get('db')->insert('staffing_tokens', array('token'=>\$covEscTok,'user_id'=>\$coverage['userId'],'account_id'=>\$dbText['accountId'],'expires_at'=>\$covEscExp));\r\n"
        "\t\t\t\t\t\$covEscLink = \"https://www.productivern.com/manager-view.php?t=\" . \$covEscTok;\r\n"
        "\t\t\t\t\t\$covEscMsg = \" Escalation Response for \".\$deptName.\" from \".\$escUser.\": \".\$response.\". \".\$covEscLink;\r\n"
        "\t\t\t\t\t\$covEscXml = \"<message id=\\\"\".\$coverage['mobile'].\$now.\"\\\"><partnerpassword>\".\$psword.\"</partnerpassword><content><![CDATA[\".\$covEscMsg.\"]]></content></message>\";\r\n"
        "\t\t\t\t\t\$messages[] = \$covEscXml;\r\n"
        "\t\t\t\t\t\$numbers[] = \"1\" . \$coverage['mobile'];\r\n"
        "\t\t\t\t\t\$keywords[] = \$keyword2;\r\n"
        "\t\t\t\t}\r\n"
        "\t\t\t\t}\r\n"
        "\t\t\t\t\r\n"
        "\t\t\t\t\$smsoptions = Array(\r\n"
        "\t\t\t\t\t'numbers' => (\$numbers ?? ''),\r\n"
        "\t\t\t\t\t'message' => (\$messages ?? ''),\r\n"
        "\t\t\t\t\t'keyword' => \$keywords,\r\n"
        "\t\t\t\t\t'login' => \$slooce,\r\n"
        "\t\t\t\t\t'shortCode' => \$shortCode\r\n"
        "\r\n"
        "\t\t\t\t);")
if old6 in content:
    content = content.replace(old6, new6, 1)
    results.append('6. sendescalationText OK')
else:
    results.append('6. sendescalationText NOT FOUND')

# ─────────────────────────────────────────────
# 7. sendqblAlert – always send to cover user
# ─────────────────────────────────────────────
old7 = ("                \$messages[] = \$xmlMsg;\r\n"
        "                \$mobile = \"1\" . \$mobile;\r\n"
        "                \$numbers[] = \$mobile;\r\n"
        "                \$keywords[] = \$keyword2;\r\n"
        "\t\t\t\t\r\n"
        "\t\t\t\t\r\n"
        "\t\t\t\t\$smsoptions = Array(\r\n"
        "\t\t\t\t\t'numbers' => (\$numbers ?? ''),\r\n"
        "\t\t\t\t\t'message' => (\$messages ?? ''),\r\n"
        "\t\t\t\t\t'keyword' => \$keywords,\r\n"
        "\t\t\t\t\t'login' => \$slooce\r\n"
        "\r\n"
        "\t\t\t\t);\r\n"
        "\t\t\t\t\r\n"
        "\t\t\t\t}\r\n"
        "\t\t\t\t\t\t\t\r\n"
        "\t\t\t\t\$result = sendescNow(\$smsoptions);\r\n"
        "\t\t\t}")
new7 = ("				\$textId = \$dbText['userId'];\r\n"
        "\t\t\t\t\$coverage = checkUserCoverage(\$textId);\r\n"
        "\t\t\t\t\$coverPause = (\$coverage) ? \$coverage['coverPauseSms'] : 0;\r\n"
        "\t\t\t\tif (!(\$coverage && \$coverPause == 1)) {\r\n"
        "\t\t\t\t\t\$messages[] = \$xmlMsg;\r\n"
        "\t\t\t\t\t\$numbers[] = \"1\" . \$mobile;\r\n"
        "\t\t\t\t\t\$keywords[] = \$keyword2;\r\n"
        "\t\t\t\t}\r\n"
        "\t\t\t\tif (\$coverage) {\r\n"
        "\t\t\t\t\t\$covQTok = bin2hex(random_bytes(32));\r\n"
        "\t\t\t\t\t\$covQExp = date('Y-m-d H:i:s', strtotime('+12 hours'));\r\n"
        "\t\t\t\t\tConfig::get('db')->insert('manager_tokens', array('token'=>\$covQTok,'user_id'=>\$coverage['userId'],'account_id'=>\$accountId,'context'=>'qbl','ref_id'=>intval(\$deptId),'expires_at'=>\$covQExp));\r\n"
        "\t\t\t\t\t\$covQLink = \"https://www.productivern.com/manager-view.php?t=\" . \$covQTok;\r\n"
        "\t\t\t\t\tif(intval(\$msgId)==1){ \$covQMsg = \"Please contact \".\$deptName.\" immediately regarding a possible hemorrhage in process.  \".\$covQLink; }\r\n"
        "\t\t\t\t\telse { \$covQMsg = \"A QBL Report has been created in \".\$deptName.\".  \".\$covQLink; }\r\n"
        "\t\t\t\t\t\$covQXml = \"<message id=\\\"\".\$coverage['mobile'].\$now.\"\\\"><partnerpassword>\".\$psword.\"</partnerpassword><content><![CDATA[\".\$covQMsg.\"]]></content></message>\";\r\n"
        "\t\t\t\t\t\$messages[] = \$covQXml;\r\n"
        "\t\t\t\t\t\$numbers[] = \"1\" . \$coverage['mobile'];\r\n"
        "\t\t\t\t\t\$keywords[] = \$keyword2;\r\n"
        "\t\t\t\t}\r\n"
        "\t\t\t\t\r\n"
        "\t\t\t\t\$smsoptions = Array(\r\n"
        "\t\t\t\t\t'numbers' => (\$numbers ?? ''),\r\n"
        "\t\t\t\t\t'message' => (\$messages ?? ''),\r\n"
        "\t\t\t\t\t'keyword' => \$keywords,\r\n"
        "\t\t\t\t\t'login' => \$slooce\r\n"
        "\r\n"
        "\t\t\t\t);\r\n"
        "\t\t\t\t\r\n"
        "\t\t\t\t}\r\n"
        "\t\t\t\t\t\t\t\r\n"
        "\t\t\t\t\$result = sendescNow(\$smsoptions);\r\n"
        "\t\t\t}")
if old7 in content:
    content = content.replace(old7, new7, 1)
    results.append('7. sendqblAlert OK')
else:
    results.append('7. sendqblAlert NOT FOUND')

# ─────────────────────────────────────────────
# 8. sendqblEsc – QBL threshold check
# ─────────────────────────────────────────────
old8 = ("                \$messages[] = \$xmlMsg;\r\n"
        "                \$mobile = \"1\" . \$mobile;\r\n"
        "                \$numbers[] = \$mobile;\r\n"
        "                \$keywords[] = \$keyword2;\r\n"
        "\t\t\t\t\r\n"
        "\t\t\t\t\r\n"
        "\t\t\t\t\$smsoptions = Array(\r\n"
        "\t\t\t\t\t'numbers' => (\$numbers ?? ''),\r\n"
        "\t\t\t\t\t'message' => (\$messages ?? ''),\r\n"
        "\t\t\t\t\t'keyword' => \$keywords,\r\n"
        "\t\t\t\t\t'login' => \$slooce\r\n"
        "\r\n"
        "\t\t\t\t);\r\n"
        "\t\t\t\t\r\n"
        "\t\t\t\t}")
new8 = ("				\$textId = \$dbText['userId'];\r\n"
        "\t\t\t\t\$coverage = checkUserCoverage(\$textId);\r\n"
        "\t\t\t\t\$coverPause = (\$coverage) ? \$coverage['coverPauseSms'] : 0;\r\n"
        "\t\t\t\tif (!(\$coverage && \$coverPause == 1)) {\r\n"
        "\t\t\t\t\t\$messages[] = \$xmlMsg;\r\n"
        "\t\t\t\t\t\$numbers[] = \"1\" . \$mobile;\r\n"
        "\t\t\t\t\t\$keywords[] = \$keyword2;\r\n"
        "\t\t\t\t}\r\n"
        "\t\t\t\tif (\$coverage && floatval(\$qbl) >= \$coverage['coverQblMin']) {\r\n"
        "\t\t\t\t\t\$covQETok = bin2hex(random_bytes(32));\r\n"
        "\t\t\t\t\t\$covQEExp = date('Y-m-d H:i:s', strtotime('+12 hours'));\r\n"
        "\t\t\t\t\tConfig::get('db')->insert('manager_tokens', array('token'=>\$covQETok,'user_id'=>\$coverage['userId'],'account_id'=>Config::get('account')['accountId'],'context'=>'qbl','ref_id'=>intval(\$deptId),'expires_at'=>\$covQEExp));\r\n"
        "\t\t\t\t\t\$covQELink = \"https://www.productivern.com/manager-view.php?t=\" . \$covQETok;\r\n"
        "\t\t\t\t\tif(intval(\$msgId)==1){ \$covQEMsg = \" A QBL ALERT has been created in \".\$deptName.\".  \".\$covQELink; }\r\n"
        "\t\t\t\t\telse { \$qblLabelCov = floatval(\$qbl)>=1000 ? \$qbl.\"ml Possible Hemorrhage\" : \$qbl.\"ml\"; \$covQEMsg = \"A QBL Notification (\".\$qblLabelCov.\") has been created in \".\$deptName.\".  \".\$covQELink; }\r\n"
        "\t\t\t\t\t\$covQEXml = \"<message id=\\\"\".\$coverage['mobile'].\$now.\"\\\"><partnerpassword>\".\$psword.\"</partnerpassword><content><![CDATA[\".\$covQEMsg.\"]]></content></message>\";\r\n"
        "\t\t\t\t\t\$messages[] = \$covQEXml;\r\n"
        "\t\t\t\t\t\$numbers[] = \"1\" . \$coverage['mobile'];\r\n"
        "\t\t\t\t\t\$keywords[] = \$keyword2;\r\n"
        "\t\t\t\t}\r\n"
        "\t\t\t\t\r\n"
        "\t\t\t\t\$smsoptions = Array(\r\n"
        "\t\t\t\t\t'numbers' => (\$numbers ?? ''),\r\n"
        "\t\t\t\t\t'message' => (\$messages ?? ''),\r\n"
        "\t\t\t\t\t'keyword' => \$keywords,\r\n"
        "\t\t\t\t\t'login' => \$slooce\r\n"
        "\r\n"
        "\t\t\t\t);\r\n"
        "\t\t\t\t\r\n"
        "\t\t\t\t}")
if old8 in content:
    content = content.replace(old8, new8, 1)
    results.append('8. sendqblEsc OK')
else:
    results.append('8. sendqblEsc NOT FOUND')

open('C:/Users/rstre/Projects/jobalarm/productivern/inc/data.php', 'wb').write(content.encode('utf-8'))

for r in results:
    print(r)
print('data.php saved')
