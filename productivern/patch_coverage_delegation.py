content = open('C:/Users/rstre/Projects/jobalarm/productivern/inc/data.php', 'rb').read().decode('utf-8')
results = []

# Replace onclick-based links with data-userid attribute links (for event delegation)
old1 = (
    "\t\t\t\t$cover = '<a href=\"javascript:;\" onclick=\"tj.userCoverage(' . $job['id'] . ')\">' . $coverLast . ', ' . $coverFirst . '</a>';\r\n"
    "\t\t\t} else {\r\n"
    "\t\t\t\t$cover = '<a href=\"javascript:;\" onclick=\"tj.userCoverage(' . $job['id'] . ')\">Set</a>';\r\n"
    "\t\t\t}"
)
new1 = (
    "\t\t\t\t$cover = '<a href=\"javascript:;\" class=\"user-coverage-link\" data-userid=\"' . $job['id'] . '\">' . $coverLast . ', ' . $coverFirst . '</a>';\r\n"
    "\t\t\t} else {\r\n"
    "\t\t\t\t$cover = '<a href=\"javascript:;\" class=\"user-coverage-link\" data-userid=\"' . $job['id'] . '\">Set</a>';\r\n"
    "\t\t\t}"
)
if old1 in content:
    content = content.replace(old1, new1, 1)
    results.append('own-row links OK')
else:
    results.append('own-row links NOT FOUND')

# Also fix the read-only (other-row) branch — it already has no onclick, just text, so no change needed

open('C:/Users/rstre/Projects/jobalarm/productivern/inc/data.php', 'wb').write(content.encode('utf-8'))
for r in results:
    print(r)
print('done')
