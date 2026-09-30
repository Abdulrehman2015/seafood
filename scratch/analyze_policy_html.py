import re
import glob

files = glob.glob('scratch/policy_*.html')

with open('scratch/policy_analysis_report.txt', 'w', encoding='utf-8') as rep:
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
        contacts = re.findall(r'601[0-9]{8,9}', content)
        rep.write("CONTACT NUMBERS FOUND:\n")
        for c in set(contacts):
            rep.write(f"  {c}\n")
            
        # Check cookie links
        cookie_links = re.findall(r'<a[^>]+(?:cookie|kuki)[^>]*>.*?</a>', content, re.IGNORECASE)
        rep.write("COOKIE LINKS FOUND:\n")
        for cl in cookie_links[:5]:
            rep.write(f"  {cl}\n")

print("Analysis report written to scratch/policy_analysis_report.txt")
