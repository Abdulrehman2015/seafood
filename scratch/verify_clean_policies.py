import re
import glob

files = glob.glob('scratch/clean_policy_*.html')

with open('scratch/clean_policy_analysis_report.txt', 'w', encoding='utf-8') as rep:
    for f in sorted(files):
        with open(f, 'r', encoding='utf-8') as fp:
            content = fp.read()
        rep.write(f"\n==================== {f} ({len(content)} chars) ====================\n")
        
        # Check dates
        dates = re.findall(r'(?:effective|updated|生效|更新|berkuat|dikemas kini)[^\n<]{0,60}', content, re.IGNORECASE)
        rep.write("DATES FOUND:\n")
        for d in dates:
            rep.write(f"  {d}\n")
            
        # Check absolute liability statements
        disclaimers = re.findall(r'[^\n.<>]{0,40}(?:not responsible|bears no responsibility|no liability|shall not be liable|概不承担|不承担任何责任|tidak bertanggungjawab|tidak mempunyai liabiliti)[^\n.<>]{0,60}', content, re.IGNORECASE)
        rep.write("DISCLAIMERS FOUND:\n")
        for dis in disclaimers[:10]:
            rep.write(f"  {dis.strip()}\n")
            
        # Check phone / WhatsApp
        wa_6013 = re.findall(r'wa\.me/60132800168', content)
        if wa_6013:
            rep.write(f"  [ERROR] Found wa.me/60132800168!\n")
        else:
            rep.write(f"  [OK] No wa.me/60132800168\n")
            
        # Check javascript:void(0)
        js_void = re.findall(r'javascript:void\(0\)', content)
        if js_void:
            rep.write(f"  [ERROR] Found javascript:void(0)!\n")
        else:
            rep.write(f"  [OK] No javascript:void(0)\n")

print("Clean policy analysis report written to scratch/clean_policy_analysis_report.txt")
