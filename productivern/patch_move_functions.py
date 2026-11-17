content = open('C:/Users/rstre/Projects/jobalarm/productivern/scripts/main.js', 'rb').read().decode('utf-8')

# The block to move (currently in units section after tj.unitCoverage)
block = (
    "tj.userCoverage = function(userId) {\r\n"
    "\t$.ajax({\r\n"
    "        url:\"inc/data.php?req=getusercoverage\",\r\n"
    "        data: { userId: userId },\r\n"
    "        method:'POST',\r\n"
    "        dataType:'json',\r\n"
    "        success:function(response) {\r\n"
    "\t\t$('#coverUserTarget').val(response.data.userId);\r\n"
    "\t\t$('#coverageUserName').html(' ' + response.data.name);\r\n"
    "\t\t$('#coverUserId').val(response.data.coverUserId);\r\n"
    "\t\tvar today = new Date().toISOString().split('T')[0];\r\n"
    "\t\tvar nowHHMM = new Date().toTimeString().slice(0,5);\r\n"
    "\t\t$('#coverUserStartDate').val(response.data.coverStartDate || today);\r\n"
    "\t\t$('#coverUserStartTime').val(response.data.coverStartTime || nowHHMM);\r\n"
    "\t\t$('#coverUserDate').val(response.data.coverEndDate || '');\r\n"
    "\t\t$('#coverUserTime').val(response.data.coverEndTime || '');\r\n"
    "\t\t$('#coverVarianceMin').val(response.data.coverVarianceMin !== undefined ? response.data.coverVarianceMin : 1);\r\n"
    "\t\t$('#coverQblMin').val(response.data.coverQblMin !== undefined ? response.data.coverQblMin : 300);\r\n"
    "\t\t$('#coverPauseSms').prop('checked', response.data.coverPauseSms == 1);\r\n"
    "\t\t$('#usercoverage').modal('toggle');\r\n"
    "        }\r\n"
    "    });\r\n"
    "};\r\n"
    "\r\n"
    "tj.updateUserCover = function() {\r\n"
    "\tvar coverUserId = $('#coverUserId').val();\r\n"
    "\tvar startDate = $('#coverUserStartDate').val();\r\n"
    "\tvar startTime = $('#coverUserStartTime').val();\r\n"
    "\tvar endDate = $('#coverUserDate').val();\r\n"
    "\tvar endTime = $('#coverUserTime').val();\r\n"
    "\tvar varianceMin = $('#coverVarianceMin').val();\r\n"
    "\tvar qblMin = $('#coverQblMin').val();\r\n"
    "\tvar pauseSms = $('#coverPauseSms').is(':checked') ? 1 : 0;\r\n"
    "\tvar userId = $('#coverUserTarget').val();\r\n"
    "\t\r\n"
    "\t$.ajax({\r\n"
    "        url:\"inc/data.php?req=changeusercoverage\",\r\n"
    "        data: {\r\n"
    "                userId: userId,\r\n"
    "\t\t\t\tcoverUserId: coverUserId,\r\n"
    "\t\t\t\tstartDate: startDate,\r\n"
    "\t\t\t\tstartTime: startTime,\r\n"
    "\t\t\t\tendDate: endDate,\r\n"
    "\t\t\t\tendTime: endTime,\r\n"
    "\t\t\t\tvarianceMin: varianceMin,\r\n"
    "\t\t\t\tqblMin: qblMin,\r\n"
    "\t\t\t\tpauseSms: pauseSms\r\n"
    "\t\t},\r\n"
    "        method:'POST',\r\n"
    "        dataType:'json',\r\n"
    "        success:function(response) {\r\n"
    "\t\ttj.UserTable.ajax.reload();\r\n"
    "\t\t$('#usercoverage').modal('toggle');\r\n"
    "        }\r\n"
    "    });\r\n"
    "};\r\n"
)

# Step 1: Remove from units section
if block in content:
    content = content.replace(block, '', 1)
    print('Removed from units section OK')
else:
    print('Block NOT FOUND — cannot remove')
    exit()

# Step 2: Insert in users section — right after the delegation handler
anchor = (
    "\t$('#UserTable').on('click', '.user-coverage-link', function() {\r\n"
    "\t\ttj.userCoverage($(this).data('userid'));\r\n"
    "\t});\r\n"
    "\t\r\n"
    "/////////////////////////////////////\r\n"
    "// EDIT USER"
)
insert = (
    "\t$('#UserTable').on('click', '.user-coverage-link', function() {\r\n"
    "\t\ttj.userCoverage($(this).data('userid'));\r\n"
    "\t});\r\n"
    "\r\n"
    + block +
    "\r\n/////////////////////////////////////\r\n"
    "// EDIT USER"
)

if anchor in content:
    content = content.replace(anchor, insert, 1)
    print('Inserted in users section OK')
else:
    print('Anchor NOT FOUND')

open('C:/Users/rstre/Projects/jobalarm/productivern/scripts/main.js', 'wb').write(content.encode('utf-8'))
print('main.js saved')
