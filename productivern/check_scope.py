content = open('C:/Users/rstre/Projects/jobalarm/productivern/scripts/main.js', 'rb').read().decode('utf-8')

cov_idx = content.find('tj.userCoverage = function')

depth = 0
in_string = False
string_char = ''
i = 0
while i < cov_idx:
    c = content[i]
    if in_string:
        if c == chr(92):  # backslash
            i += 2
            continue
        if c == string_char:
            in_string = False
    else:
        if c in ('"', "'", '`'):
            in_string = True
            string_char = c
        elif c == '{':
            depth += 1
        elif c == '}':
            depth -= 1
    i += 1

print(f'Brace depth at tj.userCoverage: {depth}')

if depth > 0:
    # Find the last unclosed brace
    d = 0
    last_open = -1
    for j in range(cov_idx):
        c = content[j]
        if c == '{':
            d += 1
            if d == depth:
                last_open = j
        elif c == '}':
            d -= 1
    print(f'Unclosed brace at {last_open}:')
    print(repr(content[last_open-100:last_open+100]))
