import os
import re

migrations_dir = 'sug-portal/database/migrations'
files = sorted([f for f in os.listdir(migrations_dir) if f.endswith('.php')])

for file in files:
    path = os.path.join(migrations_dir, file)
    with open(path, 'r', encoding='utf-8') as f:
        content = f.read()
        # Find all Schema::create calls
        matches = re.finditer(r"Schema::create\(['\"]([^'\"]+)['\"]\s*,\s*function\s*\([^)]*\)\s*\{", content)
        for match in matches:
            table_name = match.group(1)
            start_pos = match.start()
            # Find the matching closing brace for the function
            brace_count = 0
            end_pos = -1
            for i in range(start_pos, len(content)):
                if content[i] == '{':
                    brace_count += 1
                elif content[i] == '}':
                    brace_count -= 1
                    if brace_count == 0:
                        end_pos = i + 1
                        break
            if end_pos != -1:
                block = content[start_pos:end_pos]
                print(f"FILE: {file}\nTABLE: {table_name}\nBLOCK:\n{block}\n{'-'*40}")

