import json

files = ['lang/en.json', 'lang/zh.json', 'lang/bm.json', 'lang/ms.json']

with open('scratch/translation_flags.txt', 'w', encoding='utf-8') as out:
    for f in files:
        with open(f, 'r', encoding='utf-8') as fp:
            data = json.load(fp)
        out.write(f"\n==================== {f} ({len(data)} keys) ====================\n")
        for k, v in data.items():
            v_str = str(v)
            flags = []
            v_lower = v_str.lower()
            if 'mengalir dengan' in v_lower:
                flags.append('SLOGAN_BM')
            if 'selected market' in v_lower or 'selected regional' in v_lower:
                flags.append('SELECTED_MARKETS_EN')
            if 'pasaran terpilih' in v_lower:
                flags.append('SELECTED_MARKETS_BM')
            if '精选市场' in v_str:
                flags.append('SELECTED_MARKETS_ZH')
            if '60132800168' in v_str and ('wa.me' in v_str or 'whatsapp' in k.lower() or 'whatsapp' in v_lower):
                flags.append('WHATSAPP_6013')
            if k in ['cookie.accept_all', 'cookie.essential_only', 'cookie.cookie_settings']:
                flags.append(f'COOKIE_BTN:{k}={v}')
            if any(term in k for term in ['sourcing_desc', 'footer_desc']):
                flags.append(f'FOOTER_DESC:{k}={v}')
            if flags:
                out.write(f"[{', '.join(flags)}] {k} => {v}\n")

print("Done scanning, output written to scratch/translation_flags.txt")
