import re

def clean_policy(slug, lang, text):
    # 1. Dates standardisation
    if lang == 'en':
        text = re.sub(r'<strong>Effective Date:</strong>\s*(?:September\s+\d{1,2},\s*2026|\d{1,2}\s+September\s+2026)', '<strong>Effective Date:</strong> September 25, 2026', text)
        text = re.sub(r'Effective Date:\s*(?:September\s+\d{1,2},\s*2026|\d{1,2}\s+September\s+2026)', 'Effective Date: September 25, 2026', text)
    elif lang == 'zh':
        text = re.sub(r'<strong>生效日期：</strong>\s*2026年9月\d{1,2}日', '<strong>生效日期：</strong>2026年9月25日', text)
        text = re.sub(r'生效日期：\s*2026年9月\d{1,2}日', '生效日期：2026年9月25日', text)
    elif lang == 'bm':
        text = re.sub(r'<strong>(?:Tarikh\s+)?Berkuat Kuasa:</strong>\s*\d{1,2}\s+September\s+2026', '<strong>Tarikh Berkuat Kuasa:</strong> 25 September 2026', text)
        text = re.sub(r'(?:Tarikh\s+)?Berkuat Kuasa:\s*\d{1,2}\s+September\s+2026', 'Tarikh Berkuat Kuasa: 25 September 2026', text)

    # 2. Fix WhatsApp link to 601112710260 and separate Phone / WhatsApp
    text = text.replace('https://wa.me/60132800168', 'https://wa.me/601112710260')
    
    # Specific fix in refund-policy for Phone / WhatsApp label pointing to 6013
    if slug == 'refund-policy':
        if lang == 'en':
            text = text.replace(
                '<p class="mb-3"><i class="bi bi-whatsapp text-success me-2"></i><strong>Phone / WhatsApp:</strong> <a href="https://wa.me/601112710260" target="_blank" rel="noopener noreferrer" class="text-decoration-none">+60 13-280 0168</a></p>',
                '<p class="mb-1"><i class="bi bi-telephone text-primary me-2"></i><strong>Phone:</strong> <a href="tel:+60132800168" class="text-decoration-none">+60 13-280 0168</a></p>\n        <p class="mb-3"><i class="bi bi-whatsapp text-success me-2"></i><strong>WhatsApp:</strong> <a href="https://wa.me/601112710260" target="_blank" rel="noopener noreferrer" class="text-decoration-none">+60 11-1271 0260</a></p>'
            )
        elif lang == 'zh':
            text = text.replace(
                '<strong>电话 / WhatsApp：</strong>',
                '<strong>电话：</strong> <a href="tel:+60132800168" class="text-decoration-none">+60 13-280 0168</a></p>\n        <p class="mb-3"><i class="bi bi-whatsapp text-success me-2"></i><strong>WhatsApp：</strong>'
            )
            text = text.replace(
                '<a href="https://wa.me/601112710260" target="_blank" rel="noopener noreferrer" class="text-decoration-none">+60 13-280 0168</a>',
                '<a href="https://wa.me/601112710260" target="_blank" rel="noopener noreferrer" class="text-decoration-none">+60 11-1271 0260</a>'
            )
        elif lang == 'bm':
            text = text.replace(
                '<strong>Telefon / WhatsApp:</strong>',
                '<strong>Telefon:</strong> <a href="tel:+60132800168" class="text-decoration-none">+60 13-280 0168</a></p>\n        <p class="mb-3"><i class="bi bi-whatsapp text-success me-2"></i><strong>WhatsApp:</strong>'
            )
            text = text.replace(
                '<a href="https://wa.me/601112710260" target="_blank" rel="noopener noreferrer" class="text-decoration-none">+60 13-280 0168</a>',
                '<a href="https://wa.me/601112710260" target="_blank" rel="noopener noreferrer" class="text-decoration-none">+60 11-1271 0260</a>'
            )

    # Specific fix in shipping-policy for phone / WhatsApp
    if slug == 'shipping-policy':
        if lang == 'en':
            old_contact = '''        <p class="mb-2">
            <i class="bi bi-telephone-fill text-primary me-2"></i><strong>Main Phone:</strong> 
            <a href="tel:+60132800168" class="text-decoration-none fw-semibold me-3">+60 13-280 0168</a>
            <a href="https://wa.me/601112710260" target="_blank" rel="noopener noreferrer" class="badge bg-success-subtle text-success border border-success-subtle text-decoration-none">
                <i class="bi bi-whatsapp me-1"></i>WhatsApp
            </a>
        </p>
        <p class="mb-0">
            <i class="bi bi-whatsapp text-success me-2"></i><strong>Customer Support:</strong> 
            <a href="tel:+601112710260" class="text-decoration-none fw-semibold me-3">+60 11-1271 0260</a>
            <a href="https://wa.me/601112710260" target="_blank" rel="noopener noreferrer" class="badge bg-success text-white text-decoration-none">
                <i class="bi bi-whatsapp me-1"></i>Chat on WhatsApp
            </a>
        </p>'''
            new_contact = '''        <p class="mb-2">
            <i class="bi bi-telephone-fill text-primary me-2"></i><strong>Phone:</strong> 
            <a href="tel:+60132800168" class="text-decoration-none fw-semibold">+60 13-280 0168</a>
        </p>
        <p class="mb-0">
            <i class="bi bi-whatsapp text-success me-2"></i><strong>WhatsApp:</strong> 
            <a href="https://wa.me/601112710260" target="_blank" rel="noopener noreferrer" class="text-decoration-none fw-semibold me-2">+60 11-1271 0260</a>
            <a href="https://wa.me/601112710260" target="_blank" rel="noopener noreferrer" class="badge bg-success text-white text-decoration-none">
                <i class="bi bi-whatsapp me-1"></i>Chat on WhatsApp
            </a>
        </p>'''
            text = text.replace(old_contact, new_contact)

    # 3. Cookie policy settings link
    if slug == 'cookie-policy':
        text = text.replace(
            '<a href="javascript:void(0)" onclick="if(window.openCookieSettings)window.openCookieSettings();">',
            '<a href="#cookie-settings" class="js-open-cookie-settings" data-cookie-settings="true" onclick="event.preventDefault(); if (typeof window.openCookieSettings === \'function\') window.openCookieSettings();">'
        )

    # 4. Disclaimers: Replace absolute liability statements
    if lang == 'en':
        # Privacy policy
        text = text.replace(
            'MST is not responsible for the privacy practices, content or security of third-party websites.',
            'To the extent permitted by applicable law, MST is not liable for the privacy practices, content or security of third-party websites or services. Nothing in this Privacy Policy limits or excludes any statutory rights under the Personal Data Protection Act 2010 (PDPA) that cannot be lawfully excluded.'
        )
        # Refund policy
        text = text.replace(
            'MST is not responsible for deterioration caused by improper storage, handling, thawing, cooking, delay or circumstances outside MST\'s reasonable control.',
            'To the extent permitted by applicable law, MST is not liable for deterioration caused by improper customer storage, handling, thawing, cooking, delayed acceptance, or circumstances outside MST\'s reasonable control.'
        )
        # Shipping policy
        text = text.replace(
            'MST is not responsible for delays or additional charges caused by inaccurate or incomplete information.',
            'To the extent permitted by applicable law, MST is not liable for delays or additional charges caused by inaccurate or incomplete information provided by the customer.'
        )
        text = text.replace(
            'MST is not responsible for deterioration caused after delivery due to improper storage, thawing, handling or other circumstances attributable to the customer.',
            'To the extent permitted by applicable law, MST is not liable for deterioration occurring after delivery due to improper customer storage, thawing, handling, or other circumstances attributable to the customer.'
        )
        # Terms & conditions
        text = text.replace(
            'MST is not responsible for deterioration caused by improper storage, handling, thawing, cooking or delays attributable to the customer.',
            'To the extent permitted by applicable law, MST is not liable for product deterioration caused by improper customer storage, handling, thawing, cooking or delays attributable to the customer.'
        )
        text = text.replace(
            'MST is not responsible for matters outside its reasonable control involving third-party services.',
            'To the extent permitted by applicable law, MST is not liable for matters outside its reasonable control involving third-party services.'
        )
        text = text.replace(
            'MST will not be responsible for delay or failure to perform caused by circumstances beyond its reasonable control',
            'To the extent permitted by applicable law, MST will not be liable for delay or failure to perform caused by circumstances beyond its reasonable control'
        )

    elif lang == 'zh':
        # Privacy policy
        text = text.replace(
            'MST 对任何第三方网站的隐私政策、内容或安全性不承担任何责任。',
            '在适用法律允许的范围内，MST 对第三方网站的隐私政策、内容或安全性不承担责任。本隐私政策的任何内容均不排除或限制客户在《2010年个人数据保护法》(PDPA) 及其他适用法律下享有的不可依法排除的法定权利。'
        )
        # Refund policy
        text = text.replace(
            '对于因客户储存不当、操作失误、解冻不当、烹饪失误、延误收货或超出 MST 合理控制范围的其他情况导致的产品变质，MST 概不承担责任。',
            '在适用法律允许的范围内，对于因客户自身储存不当、操作失误、解冻不当、烹饪失误、延误收货或超出 MST 合理控制范围的其他情况导致的产品变质，MST 不承担责任。'
        )
        text = text.replace(
            '烹饪失误、延误收货或超出 MST 合理控制范围的其他情况导致的产品变质，MST 概不承担责任。',
            '烹饪失误、延误收货或超出 MST 合理控制范围的其他情况导致的产品变质，在适用法律允许的范围内，MST 不承担责任。'
        )
        # Shipping policy
        text = text.replace(
            '因信息不准确或不完整而导致的配送延误或额外运费，MST 概不承担责任。',
            '在适用法律允许的范围内，对于因客户提供的信息不准确或不完整而导致的配送延误或额外运费，MST 不承担责任。'
        )
        text = text.replace(
            '对于成功签收后因客户自身储存不当、解冻不当、操作失误或其他客户原因导致的产品变质，MST 概不承担责任。',
            '在适用法律允许的范围内，对于成功签收后因客户自身储存不当、解冻不当、操作失误或其他客户原因导致的产品变质，MST 不承担责任。'
        )
        # Terms & conditions
        text = text.replace(
            '因客户自身储存不当、操作失误、解冻不当、烹调或延迟签收而导致的产品变质，MST 概不承担责任。',
            '在适用法律允许的范围内，对于因客户自身储存不当、操作失误、解冻不当、烹调或延迟签收而导致的产品变质，MST 不承担责任。'
        )
        text = text.replace(
            '对于超出 MST 合理控制范围的涉及第三方服务的事项，MST 概不承担责任。',
            '在适用法律允许的范围内，对于超出 MST 合理控制范围的涉及第三方服务的事项，MST 不承担责任。'
        )

    elif lang == 'bm':
        # Privacy policy
        text = text.replace(
            'MST tidak bertanggungjawab terhadap amalan privasi, kandungan atau keselamatan laman web pihak ketiga.',
            'Setakat yang dibenarkan oleh undang-undang yang terpakai, MST tidak bertanggungjawab terhadap amalan privasi, kandungan atau keselamatan laman web pihak ketiga. Tiada apa-apa dalam Dasar Privasi ini yang mengecualikan atau mengehadkan sebarang hak berkanun di bawah Akta Perlindungan Data Peribadi 2010 (PDPA) yang tidak boleh dikecualikan secara sah.'
        )
        # Refund policy
        text = text.replace(
            'MST tidak bertanggungjawab terhadap sebarang kemerosotan kualiti yang berpunca daripada penyimpanan pelanggan yang tidak wajar, pengendalian, penyahbekuan, masakan, penerimaan lewat atau keadaan lain di luar kawalan munasabah MST.',
            'Setakat yang dibenarkan oleh undang-undang yang terpakai, MST tidak bertanggungjawab terhadap sebarang kemerosotan kualiti yang berpunca daripada penyimpanan pelanggan yang tidak wajar, pengendalian, penyahbekuan, masakan, penerimaan lewat atau keadaan lain di luar kawalan munasabah MST.'
        )
        # Shipping policy
        text = text.replace(
            'MST tidak bertanggungjawab terhadap kelewatan atau caj tambahan yang disebabkan oleh maklumat yang tidak tepat atau tidak lengkap.',
            'Setakat yang dibenarkan oleh undang-undang yang terpakai, MST tidak bertanggungjawab terhadap kelewatan atau caj tambahan yang disebabkan oleh maklumat yang tidak tepat atau tidak lengkap.'
        )
        text = text.replace(
            'MST tidak bertanggungjawab ke atas kerosakan selepas penghantaran akibat penyimpanan yang tidak wajar, penyahbekuan, pengendalian atau keadaan lain yang disebabkan oleh pelanggan.',
            'Setakat yang dibenarkan oleh undang-undang yang terpakai, MST tidak bertanggungjawab ke atas kerosakan selepas penghantaran akibat penyimpanan pelanggan yang tidak wajar, penyahbekuan, pengendalian atau keadaan lain yang disebabkan oleh pelanggan.'
        )
        # Terms & conditions
        text = text.replace(
            'MST tidak bertanggungjawab terhadap kemerosotan kualiti yang disebabkan oleh penyimpanan, pengendalian, penyahbekuan, masakan atau kelewatan penerimaan oleh pelanggan.',
            'Setakat yang dibenarkan oleh undang-undang yang terpakai, MST tidak bertanggungjawab terhadap kemerosotan kualiti yang disebabkan oleh penyimpanan pelanggan yang tidak wajar, pengendalian, penyahbekuan, masakan atau kelewatan penerimaan oleh pelanggan.'
        )
        text = text.replace(
            'MST tidak bertanggungjawab ke atas perkara di luar kawalan munasabahnya yang melibatkan perkhidmatan pihak ketiga.',
            'Setakat yang dibenarkan oleh undang-undang yang terpakai, MST tidak bertanggungjawab ke atas perkara di luar kawalan munasabahnya yang melibatkan perkhidmatan pihak ketiga.'
        )
        text = text.replace(
            'MST tidak akan bertanggungjawab terhadap kelewatan atau kegagalan pelaksanaan yang disebabkan oleh keadaan di luar kawalan munasabahnya',
            'Setakat yang dibenarkan oleh undang-undang yang terpakai, MST tidak akan bertanggungjawab terhadap kelewatan atau kegagalan pelaksanaan yang disebabkan oleh keadaan di luar kawalan munasabahnya'
        )

    # 5. Delivery / Order Thresholds: Clear communication that orders below threshold can still be placed
    if slug == 'terms-and-conditions':
        if lang == 'en':
            text = text.replace(
                '<p>Local door-to-door delivery is available subject to the following minimum order values:</p>\n<ul>\n  <li><strong>B2B / Wholesale:</strong> RM350</li>\n  <li><strong>B2C / Retail:</strong> RM100</li>\n</ul>\n<p>These minimum delivery values currently apply to selected areas within Johor Bahru and Nusajaya. Delivery availability, minimum order requirements and delivery charges may vary according to location, order type and delivery arrangements.</p>\n<p>Orders below the applicable minimum may still be accepted, but delivery charges will apply.</p>',
                '<p>Local door-to-door delivery is available based on standard reference delivery thresholds:</p>\n<ul>\n  <li><strong>B2B / Wholesale:</strong> Reference threshold RM350</li>\n  <li><strong>B2C / Retail:</strong> Reference threshold RM100</li>\n</ul>\n<p>These amounts serve as delivery/order reference thresholds and do not function as hard minimum-order restrictions. Customers can still place or continue orders below these reference amounts where delivery service is available, subject to an additional transportation / delivery fee based on delivery location / zone and logistics requirements. Standard delivery coverage applies to Johor Bahru and Iskandar Puteri / Nusajaya.</p>'
            )
        elif lang == 'zh':
            text = text.replace(
                '本地送货上门服务须满足以下最低订单金额门槛：',
                '本地送货上门服务依据以下标准配送门槛参考执行（非硬性禁止下单限制）：'
            )
            text = text.replace(
                '低于适用门槛金额的订单，MST 仍可酌情受理，但需加收相应的配送运费。',
                '低于参考门槛金额的订单仍可安排配送，将根据配送区域与物流要求加收相应配送运费。标准本地配送范围为新山（Johor Bahru）及依斯干达公主城 / 努沙再也（Iskandar Puteri / Nusajaya）。'
            )
        elif lang == 'bm':
            text = text.replace(
                'Penghantaran pintu ke pintu tempatan disediakan tertakluk kepada nilai pesanan minimum berikut:',
                'Penghantaran terus ke pintu premis disediakan berdasarkan ambang rujukan penghantaran berikut:'
            )
            text = text.replace(
                'Pesanan di bawah had minimum masih boleh diterima, namun caj penghantaran akan dikenakan.',
                'Pesanan di bawah ambang rujukan masih boleh dibuat dan diteruskan di mana perkhidmatan penghantaran tersedia, tertakluk kepada caj pengangkutan atau penghantaran tambahan berdasarkan zon/lokasi penghantaran. Liputan standard terpakai untuk Johor Bahru dan Iskandar Puteri / Nusajaya.'
            )

    return text

slugs = ['privacy-policy', 'terms-and-conditions', 'refund-policy', 'shipping-policy', 'cookie-policy']
langs = ['en', 'zh', 'bm']

for slug in slugs:
    for lang in langs:
        fname = f'scratch/policy_{slug}_{lang}.html'
        with open(fname, 'r', encoding='utf-8') as f:
            content = f.read()
        cleaned = clean_policy(slug, lang, content)
        out_name = f'scratch/clean_policy_{slug}_{lang}.html'
        with open(out_name, 'w', encoding='utf-8') as f:
            f.write(cleaned)

print("All cleaned policies saved to scratch/clean_policy_*")
