content = open('C:/Users/rstre/Projects/jobalarm/productivern/inc/data.php', 'rb').read().decode('utf-8')
results = []

# Fix checkUserCoverage (broken \$ + add coverStartDate/Time logic)
old_fn = (
    "// CHECK IF USER HAS ACTIVE COVERAGE\r\n"
    "function checkUserCoverage(\\$userId) {\r\n"
    "\t\\$today = date('Y-m-d');\r\n"
    "\t\\$nowTime = date('H:i:s');\r\n"
    "\t\\$result = Config::get('db')->get_results(\"\r\n"
    "\t\tSELECT u.coverUserId, u.coverPauseSms, u.coverVarianceMin, u.coverQblMin,\r\n"
    "\t\t       u.coverEndDate, u.coverEndTime, cu.mobile, cu.id as coverUserDbId\r\n"
    "\t\tFROM `productiveUser` u\r\n"
    "\t\tLEFT JOIN `productiveUser` cu ON cu.id = u.coverUserId\r\n"
    "\t\tWHERE u.id = {\\$userId}\r\n"
    "\t\tAND u.coverUserId > 0\r\n"
    "\t\tAND (u.coverEndDate = '0000-00-00' OR u.coverEndDate >= '{\\$today}')\r\n"
    "\t\tAND LENGTH(cu.mobile) = 10\r\n"
    "\t\tLIMIT 1\r\n"
    "\t\");\r\n"
    "\tif (!\\$result) return false;\r\n"
    "\t\\$r = \\$result[0];\r\n"
    "\tif (\\$r['coverEndDate'] == \\$today && \\$r['coverEndTime'] != '' && \\$r['coverEndTime'] != '00:00:00') {\r\n"
    "\t\tif (\\$nowTime > \\$r['coverEndTime']) return false;\r\n"
    "\t}\r\n"
    "\treturn array(\r\n"
    "\t\t'userId'          => intval(\\$r['coverUserDbId']),\r\n"
    "\t\t'mobile'          => \\$r['mobile'],\r\n"
    "\t\t'coverPauseSms'   => intval(\\$r['coverPauseSms']),\r\n"
    "\t\t'coverVarianceMin' => intval(\\$r['coverVarianceMin']),\r\n"
    "\t\t'coverQblMin'     => intval(\\$r['coverQblMin'])\r\n"
    "\t);\r\n"
    "}\r\n"
)
new_fn = (
    "// CHECK IF USER HAS ACTIVE COVERAGE\r\n"
    "function checkUserCoverage($userId) {\r\n"
    "\t$today = date('Y-m-d');\r\n"
    "\t$nowTime = date('H:i:s');\r\n"
    "\t$result = Config::get('db')->get_results(\"\r\n"
    "\t\tSELECT u.coverUserId, u.coverPauseSms, u.coverVarianceMin, u.coverQblMin,\r\n"
    "\t\t       u.coverStartDate, u.coverStartTime, u.coverEndDate, u.coverEndTime,\r\n"
    "\t\t       cu.mobile, cu.id as coverUserDbId\r\n"
    "\t\tFROM `productiveUser` u\r\n"
    "\t\tLEFT JOIN `productiveUser` cu ON cu.id = u.coverUserId\r\n"
    "\t\tWHERE u.id = {$userId}\r\n"
    "\t\tAND u.coverUserId > 0\r\n"
    "\t\tAND (u.coverEndDate = '0000-00-00' OR u.coverEndDate >= '{$today}')\r\n"
    "\t\tAND LENGTH(cu.mobile) = 10\r\n"
    "\t\tLIMIT 1\r\n"
    "\t\");\r\n"
    "\tif (!$result) return false;\r\n"
    "\t$r = $result[0];\r\n"
    "\t// Check if coverage has started yet\r\n"
    "\tif ($r['coverStartDate'] != '' && $r['coverStartDate'] != '0000-00-00') {\r\n"
    "\t\tif ($r['coverStartDate'] > $today) return false;\r\n"
    "\t\tif ($r['coverStartDate'] == $today && $r['coverStartTime'] != '' && $r['coverStartTime'] != '00:00:00') {\r\n"
    "\t\t\tif ($nowTime < $r['coverStartTime']) return false;\r\n"
    "\t\t}\r\n"
    "\t}\r\n"
    "\t// Check if coverage has expired\r\n"
    "\tif ($r['coverEndDate'] == $today && $r['coverEndTime'] != '' && $r['coverEndTime'] != '00:00:00') {\r\n"
    "\t\tif ($nowTime > $r['coverEndTime']) return false;\r\n"
    "\t}\r\n"
    "\treturn array(\r\n"
    "\t\t'userId'          => intval($r['coverUserDbId']),\r\n"
    "\t\t'mobile'          => $r['mobile'],\r\n"
    "\t\t'coverPauseSms'   => intval($r['coverPauseSms']),\r\n"
    "\t\t'coverVarianceMin' => intval($r['coverVarianceMin']),\r\n"
    "\t\t'coverQblMin'     => intval($r['coverQblMin'])\r\n"
    "\t);\r\n"
    "}\r\n"
)
if old_fn in content:
    content = content.replace(old_fn, new_fn, 1)
    results.append('checkUserCoverage fix+start OK')
else:
    results.append('checkUserCoverage NOT FOUND')

# GetUserCoverage query - add coverStartDate/Time
old_q = (
    "IFNULL(u.coverEndDate,'') as coverEndDate, IFNULL(u.coverEndTime,'') as coverEndTime, "
    "IFNULL(u.coverPauseSms,0) as coverPauseSms, IFNULL(u.coverVarianceMin,1) as coverVarianceMin, "
    "IFNULL(u.coverQblMin,300) as coverQblMin FROM `productiveUser` u WHERE u.id={$targetUserId} AND u.accountId={$accountId}"
)
new_q = (
    "IFNULL(u.coverStartDate,'') as coverStartDate, IFNULL(u.coverStartTime,'') as coverStartTime, "
    "IFNULL(u.coverEndDate,'') as coverEndDate, IFNULL(u.coverEndTime,'') as coverEndTime, "
    "IFNULL(u.coverPauseSms,0) as coverPauseSms, IFNULL(u.coverVarianceMin,1) as coverVarianceMin, "
    "IFNULL(u.coverQblMin,300) as coverQblMin FROM `productiveUser` u WHERE u.id={$targetUserId} AND u.accountId={$accountId}"
)
if old_q in content:
    content = content.replace(old_q, new_q, 1)
    results.append('GetUserCoverage query OK')
else:
    results.append('GetUserCoverage query NOT FOUND')

# GetUserCoverage return - add coverStartDate/Time
old_r = (
    "'coverEndTime' => ($u['coverEndTime'] == '00:00:00' ? '' : $u['coverEndTime']),\r\n"
    "\t\t\t'coverPauseSms' => intval($u['coverPauseSms']),\r\n"
)
new_r = (
    "'coverStartDate' => $u['coverStartDate'],\r\n"
    "\t\t\t'coverStartTime' => ($u['coverStartTime'] == '00:00:00' ? '' : $u['coverStartTime']),\r\n"
    "\t\t\t'coverEndTime' => ($u['coverEndTime'] == '00:00:00' ? '' : $u['coverEndTime']),\r\n"
    "\t\t\t'coverPauseSms' => intval($u['coverPauseSms']),\r\n"
)
if old_r in content:
    content = content.replace(old_r, new_r, 1)
    results.append('GetUserCoverage return OK')
else:
    results.append('GetUserCoverage return NOT FOUND')

# ChangeUserCoverage - add startDate/startTime parsing
old_c1 = (
    "$endTime = (isset($_REQUEST['endTime']) && $_REQUEST['endTime'] != '') ? $_REQUEST['endTime'] : '';\r\n"
    "\t$pauseSms"
)
new_c1 = (
    "$endTime = (isset($_REQUEST['endTime']) && $_REQUEST['endTime'] != '') ? $_REQUEST['endTime'] : '';\r\n"
    "\t$startDate = (isset($_REQUEST['startDate']) && $_REQUEST['startDate'] != '') ? $_REQUEST['startDate'] : date('Y-m-d');\r\n"
    "\t$startTime = (isset($_REQUEST['startTime']) && $_REQUEST['startTime'] != '') ? $_REQUEST['startTime'] : '';\r\n"
    "\t$pauseSms"
)
if old_c1 in content:
    content = content.replace(old_c1, new_c1, 1)
    results.append('ChangeUserCoverage parse OK')
else:
    results.append('ChangeUserCoverage parse NOT FOUND')

# ChangeUserCoverage - add to save array
old_c2 = (
    "'coverEndDate' => $endDate,\r\n"
    "\t\t\t\t'coverEndTime' => $endTime,\r\n"
    "\t\t\t\t'coverPauseSms'"
)
new_c2 = (
    "'coverStartDate' => $startDate,\r\n"
    "\t\t\t\t'coverStartTime' => $startTime,\r\n"
    "\t\t\t\t'coverEndDate' => $endDate,\r\n"
    "\t\t\t\t'coverEndTime' => $endTime,\r\n"
    "\t\t\t\t'coverPauseSms'"
)
if old_c2 in content:
    content = content.replace(old_c2, new_c2, 1)
    results.append('ChangeUserCoverage save OK')
else:
    results.append('ChangeUserCoverage save NOT FOUND')

# ChangeUserCoverage reset
old_c3 = "'coverUserId'=>0,'coverEndDate'=>'0000-00-00','coverEndTime'=>'','coverPauseSms'=>0,'coverVarianceMin'=>1,'coverQblMin'=>300"
new_c3 = "'coverUserId'=>0,'coverStartDate'=>'0000-00-00','coverStartTime'=>'','coverEndDate'=>'0000-00-00','coverEndTime'=>'','coverPauseSms'=>0,'coverVarianceMin'=>1,'coverQblMin'=>300"
if old_c3 in content:
    content = content.replace(old_c3, new_c3, 1)
    results.append('ChangeUserCoverage reset OK')
else:
    results.append('ChangeUserCoverage reset NOT FOUND')

open('C:/Users/rstre/Projects/jobalarm/productivern/inc/data.php', 'wb').write(content.encode('utf-8'))
for r in results:
    print(r)
print('data.php saved')
