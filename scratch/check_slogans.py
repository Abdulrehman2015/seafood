import json

for fname in ['lang/en.json', 'lang/zh.json', 'lang/bm.json', 'lang/ms.json']:
    with open(fname, 'r', encoding='utf-8') as f:
        data = json.load(f)
    print(f'=== {fname} ===')
    for k, v in data.items():
        v_str = str(v)
        v_lower = v_str.lower()
        if 'integrity' in v_lower or 'integriti' in v_lower or 'strength' in v_lower or 'kekuatan' in v_lower or 'motto' in k.lower() or 'slogan' in k.lower() or 'tagline' in k.lower():
            if 'motto' in k.lower() or 'slogan' in k.lower() or 'tagline' in k.lower():
                print(f'  {k} => {v_str}')
