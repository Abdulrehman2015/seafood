import json
import sys

sys.stdout.reconfigure(encoding='utf-8')

files = {
    'en': 'lang/en.json',
    'zh': 'lang/zh.json',
    'bm': 'lang/bm.json',
    'ms': 'lang/ms.json',
}

data = {}
for lang, path in files.items():
    with open(path, 'r', encoding='utf-8') as f:
        data[lang] = json.load(f)

# Search for any prohibited terms in each lang:
# Term checks
print("=== AUDIT REPORT ===")
for lang, d in data.items():
    print(f"\n--- Language: {lang.upper()} ---")
    for k, v in d.items():
        vl = v.lower()
        # 1. Company name issues
        if any(bad.lower() in vl for bad in ['mika import and export', 'mst import & export', 'mst import and export sdn bhd', 'mst import & export sdn bhd']):
            print(f"  [BAD COMPANY NAME] {k}: {v}")
        # 2. Market issues
        if any(bad in vl for bad in ['selected market', 'selected markets', 'pasaran terpilih', '精选市场', 'selected regional']):
            print(f"  [BAD MARKET] {k}: {v}")
        if any(bad in vl for bad in ['global supply network', 'international supply network', 'regional distribution network', '全球供应网络', '国际供应网络', 'rangkaian bekalan global', 'rangkaian bekalan antarabangsa']):
            print(f"  [BAD NETWORK] {k}: {v}")
        # 3. Wrong WhatsApp links
        if 'wa.me/60132800168' in vl or 'wa.me/+60132800168' in vl:
            print(f"  [BAD WA LINK] {k}: {v}")
        # 4. Slogan translated
        if k in ['footer.tagline', 'common.tagline', 'tagline', 'common.motto', 'motto', 'common.slogan', 'slogan', 'home.slogan', 'footer.footer.tagline', 'footer.footer.slogan', 'footer.slogan', 'nav.nav.slogan', 'nav.slogan']:
            if v != 'Flow with Integrity, Grow with Strength.':
                print(f"  [SLOGAN NOT STANDARD] {k}: {v}")
        # 5. Cookie buttons
        if k == 'cookie.essential_only':
            expected = {'en': 'Essential Only', 'zh': '仅必要 Cookie', 'bm': 'Kuki Penting Sahaja', 'ms': 'Kuki Penting Sahaja'}
            if v != expected.get(lang):
                print(f"  [COOKIE ESSENTIAL] {k}: '{v}' != '{expected.get(lang)}'")
        if k == 'cookie.accept_all':
            expected = {'en': 'Accept All', 'zh': '接受全部', 'bm': 'Terima Semua', 'ms': 'Terima Semua'}
            if v != expected.get(lang):
                print(f"  [COOKIE ACCEPT] {k}: '{v}' != '{expected.get(lang)}'")
        if k == 'cookie.cookie_settings':
            expected = {'en': 'Cookie Settings', 'zh': 'Cookie 设置', 'bm': 'Tetapan Kuki', 'ms': 'Tetapan Kuki'}
            if v != expected.get(lang):
                print(f"  [COOKIE SETTINGS] {k}: '{v}' != '{expected.get(lang)}'")

print("\nAudit completed.")
