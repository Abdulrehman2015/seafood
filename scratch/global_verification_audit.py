import os
import re

directories = ['resources/views', 'lang', 'app', 'routes', 'public', 'config']
extensions = ('.php', '.blade.php', '.json', '.js', '.css', '.html', '.xml', '.xsl')

forbidden_checks = [
    ('WhatsApp linking to 60132800168', re.compile(r'wa\.me/60132800168', re.IGNORECASE)),
    ('MST Import & Export (disallowed company name)', re.compile(r'MST\s+(?:Import\s*&\s*Export|Import\s+and\s+Export\s+Sdn\s+Bhd(?!\.))', re.IGNORECASE)),
    ('Mika Import and Export', re.compile(r'Mika\s+Import\s+and\s+Export', re.IGNORECASE)),
    ('Pasaran terpilih in translations/views', re.compile(r'pasaran\s+terpilih', re.IGNORECASE)),
    ('精选市场 in translations/views', re.compile(r'精选市场')),
    ('Mengalir dengan Integriti (translated slogan)', re.compile(r'Mengalir\s+dengan\s+Integriti', re.IGNORECASE)),
]

results = {check[0]: [] for check in forbidden_checks}

for d in directories:
    for root, _, files in os.walk(d):
        for f in files:
            if not f.endswith(extensions):
                continue
            path = os.path.join(root, f)
            with open(path, 'r', encoding='utf-8', errors='ignore') as fp:
                content = fp.read()
            for name, pattern in forbidden_checks:
                for line_no, line in enumerate(content.splitlines(), 1):
                    if pattern.search(line):
                        results[name].append((path, line_no, line.strip()))

with open('scratch/global_audit_results.txt', 'w', encoding='utf-8') as out:
    for name, matches in results.items():
        out.write(f"\n==================== {name} ({len(matches)} matches) ====================\n")
        for p, lno, text in matches:
            out.write(f"  {p}:{lno} => {text[:140]}\n")

print("Global verification audit written to scratch/global_audit_results.txt")
