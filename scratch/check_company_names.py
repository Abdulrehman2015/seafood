import json
import re

files = ['lang/en.json', 'lang/zh.json', 'lang/bm.json', 'lang/ms.json']

bad_patterns = [
    r'MST\s+Import\s*&\s*Export(?:\s+Sdn\.?\s*Bhd\.?)?',
    r'MST\s+Import\s+and\s+Export\s+Sdn\s+Bhd(?!\.)',
    r'Mika\s+Import\s+and\s+Export',
    r'MST\s+Import\s+and\s+Export(?!\s+Sdn\.\s*Bhd\.)',
]

with open('scratch/company_name_flags.txt', 'w', encoding='utf-8') as out:
    for f in files:
        with open(f, 'r', encoding='utf-8') as fp:
            data = json.load(fp)
        out.write(f"\n==================== {f} ====================\n")
        for k, v in data.items():
            v_str = str(v)
            for pat in bad_patterns:
                matches = re.findall(pat, v_str, re.IGNORECASE)
                if matches:
                    out.write(f"[{matches[0]}] {k} => {v_str[:120]}\n")
                    break

print("Company name scan written to scratch/company_name_flags.txt")
