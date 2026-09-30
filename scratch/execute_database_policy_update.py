import re
import sqlite3
import subprocess

def process_policy_text(slug, lang, text):
    # 1. Date Standardisation
    if slug == 'shipping-policy':
        if lang == 'en':
            text = re.sub(
                r'Effective Date:\s*(?:September\s+\d{1,2},\s*2026|\d{1,2}\s+September\s+2026)',
                'Effective Date: September 25, 2026 | Last Updated: September 25, 2026',
                text
            )
        elif lang == 'zh':
            text = re.sub(
                r'生效日期：\s*2026年9月\d{1,2}日',
                '生效日期：2026年9月25日 | 最后更新：2026年9月25日',
                text
            )
        elif lang == 'bm':
            text = re.sub(
                r'(?:Tarikh\s+)?Berkuat Kuasa:\s*\d{1,2}\s+September\s+2026',
                'Tarikh Berkuat Kuasa: 25 September 2026 | Terakhir Dikemas Kini: 25 September 2026',
                text
            )
    elif slug == 'refund-policy':
        if lang == 'en':
            text = re.sub(
                r'<strong>Effective Date:</strong>\s*(?:September\s+\d{1,2},\s*2026|\d{1,2}\s+September\s+2026)',
                '<strong>Effective Date:</strong> September 25, 2026 | <strong>Last Updated:</strong> September 25, 2026',
                text
            )
        elif lang == 'zh':
            text = re.sub(
                r'<strong>生效日期：</strong>\s*2026年9月\d{1,2}日',
                '<strong>生效日期：</strong>2026年9月25日 | <strong>最后更新：</strong>2026年9月25日',
                text
            )
        elif lang == 'bm':
            text = re.sub(
                r'<strong>(?:Tarikh\s+)?Berkuat Kuasa:</strong>\s*\d{1,2}\s+September\s+2026',
                '<strong>Tarikh Berkuat Kuasa:</strong> 25 September 2026 | <strong>Terakhir Dikemas Kini:</strong> 25 September 2026',
                text
            )
    else:
        # privacy-policy, terms-and-conditions, cookie-policy
        if lang == 'en':
            text = re.sub(
                r'<p><strong>Effective Date:</strong>\s*(?:September\s+\d{1,2},\s*2026|\d{1,2}\s+September\s+2026)</p>',
                '<p><strong>Effective Date:</strong> September 25, 2026 | <strong>Last Updated:</strong> September 25, 2026</p>',
                text
            )
        elif lang == 'zh':
            text = re.sub(
                r'<p><strong>生效日期：</strong>\s*2026年9月\d{1,2}日</p>',
                '<p><strong>生效日期：</strong>2026年9月25日 | <strong>最后更新：</strong>2026年9月25日</p>',
                text
            )
        elif lang == 'bm':
            text = re.sub(
                r'<p><strong>(?:Tarikh\s+)?Berkuat Kuasa:</strong>\s*\d{1,2}\s+September\s+2026</p>',
                '<p><strong>Tarikh Berkuat Kuasa:</strong> 25 September 2026 | <strong>Terakhir Dikemas Kini:</strong> 25 September 2026</p>',
                text
            )

    # 2. WhatsApp links fix: Never link wa.me to 60132800168
    text = text.replace('https://wa.me/60132800168', 'https://wa.me/601112710260')
    
    # Clean contact separation in refund-policy
    if slug == 'refund-policy':
        if lang == 'en':
            text = text.replace(
                '<p class="mb-3"><i class="bi bi-whatsapp text-success me-2"></i><strong>Phone / WhatsApp:</strong> <a href="https://wa.me/601112710260" target="_blank" rel="noopener noreferrer" class="text-decoration-none">+60 13-280 0168</a></p>',
                '<p class="mb-1"><i class="bi bi-telephone text-primary me-2"></i><strong>Phone:</strong> <a href="tel:+60132800168" class="text-decoration-none">+60 13-280 0168</a></p>\n        <p class="mb-3"><i class="bi bi-whatsapp text-success me-2"></i><strong>WhatsApp:</strong> <a href="https://wa.me/601112710260" target="_blank" rel="noopener noreferrer" class="text-decoration-none">+60 11-1271 0260</a></p>'
            )
        elif lang == 'zh':
            text = text.replace(
                '<p class="mb-3"><i class="bi bi-whatsapp text-success me-2"></i><strong>电话 / WhatsApp：</strong> <a href="https://wa.me/601112710260" target="_blank" rel="noopener noreferrer" class="text-decoration-none">+60 13-280 0168</a></p>',
                '<p class="mb-1"><i class="bi bi-telephone text-primary me-2"></i><strong>电话：</strong> <a href="tel:+60132800168" class="text-decoration-none">+60 13-280 0168</a></p>\n        <p class="mb-3"><i class="bi bi-whatsapp text-success me-2"></i><strong>WhatsApp：</strong> <a href="https://wa.me/601112710260" target="_blank" rel="noopener noreferrer" class="text-decoration-none">+60 11-1271 0260</a></p>'
            )
        elif lang == 'bm':
            text = text.replace(
                '<p class="mb-3"><i class="bi bi-whatsapp text-success me-2"></i><strong>Telefon / WhatsApp:</strong> <a href="https://wa.me/601112710260" target="_blank" rel="noopener noreferrer" class="text-decoration-none">+60 13-280 0168</a></p>',
                '<p class="mb-1"><i class="bi bi-telephone text-primary me-2"></i><strong>Telefon:</strong> <a href="tel:+60132800168" class="text-decoration-none">+60 13-280 0168</a></p>\n        <p class="mb-3"><i class="bi bi-whatsapp text-success me-2"></i><strong>WhatsApp:</strong> <a href="https://wa.me/601112710260" target="_blank" rel="noopener noreferrer" class="text-decoration-none">+60 11-1271 0260</a></p>'
            )

    # Clean contact separation in shipping-policy
    if slug == 'shipping-policy':
        if lang == 'en':
            old_sec = '''        <p class="mb-2">
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
            new_sec = '''        <p class="mb-2">
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
            text = text.replace(old_sec, new_sec)
        elif lang == 'zh':
            old_sec_zh = '''        <p class="mb-2">
            <i class="bi bi-telephone-fill text-primary me-2"></i><strong>主要热线：</strong> 
            <a href="tel:+60132800168" class="text-decoration-none fw-semibold me-3">+60 13-280 0168</a>
            <a href="https://wa.me/601112710260" target="_blank" rel="noopener noreferrer" class="badge bg-success-subtle text-success border border-success-subtle text-decoration-none">
                <i class="bi bi-whatsapp me-1"></i>WhatsApp
            </a>
        </p>
        <p class="mb-0">
            <i class="bi bi-whatsapp text-success me-2"></i><strong>客户服务：</strong> 
            <a href="tel:+601112710260" class="text-decoration-none fw-semibold me-3">+60 11-1271 0260</a>
            <a href="https://wa.me/601112710260" target="_blank" rel="noopener noreferrer" class="badge bg-success text-white text-decoration-none">
                <i class="bi bi-whatsapp me-1"></i>WhatsApp 咨询
            </a>
        </p>'''
            new_sec_zh = '''        <p class="mb-2">
            <i class="bi bi-telephone-fill text-primary me-2"></i><strong>联系电话：</strong> 
            <a href="tel:+60132800168" class="text-decoration-none fw-semibold">+60 13-280 0168</a>
        </p>
        <p class="mb-0">
            <i class="bi bi-whatsapp text-success me-2"></i><strong>WhatsApp：</strong> 
            <a href="https://wa.me/601112710260" target="_blank" rel="noopener noreferrer" class="text-decoration-none fw-semibold me-2">+60 11-1271 0260</a>
            <a href="https://wa.me/601112710260" target="_blank" rel="noopener noreferrer" class="badge bg-success text-white text-decoration-none">
                <i class="bi bi-whatsapp me-1"></i>WhatsApp 咨询
            </a>
        </p>'''
            text = text.replace(old_sec_zh, new_sec_zh)
        elif lang == 'bm':
            old_sec_bm = '''        <p class="mb-2">
            <i class="bi bi-telephone-fill text-primary me-2"></i><strong>Talian Utama:</strong> 
            <a href="tel:+60132800168" class="text-decoration-none fw-semibold me-3">+60 13-280 0168</a>
            <a href="https://wa.me/601112710260" target="_blank" rel="noopener noreferrer" class="badge bg-success-subtle text-success border border-success-subtle text-decoration-none">
                <i class="bi bi-whatsapp me-1"></i>WhatsApp
            </a>
        </p>
        <p class="mb-0">
            <i class="bi bi-whatsapp text-success me-2"></i><strong>Sokongan Pelanggan:</strong> 
            <a href="tel:+601112710260" class="text-decoration-none fw-semibold me-3">+60 11-1271 0260</a>
            <a href="https://wa.me/601112710260" target="_blank" rel="noopener noreferrer" class="badge bg-success text-white text-decoration-none">
                <i class="bi bi-whatsapp me-1"></i>Sembang di WhatsApp
            </a>
        </p>'''
            new_sec_bm = '''        <p class="mb-2">
            <i class="bi bi-telephone-fill text-primary me-2"></i><strong>Telefon:</strong> 
            <a href="tel:+60132800168" class="text-decoration-none fw-semibold">+60 13-280 0168</a>
        </p>
        <p class="mb-0">
            <i class="bi bi-whatsapp text-success me-2"></i><strong>WhatsApp:</strong> 
            <a href="https://wa.me/601112710260" target="_blank" rel="noopener noreferrer" class="text-decoration-none fw-semibold me-2">+60 11-1271 0260</a>
            <a href="https://wa.me/601112710260" target="_blank" rel="noopener noreferrer" class="badge bg-success text-white text-decoration-none">
                <i class="bi bi-whatsapp me-1"></i>Sembang di WhatsApp
            </a>
        </p>'''
            text = text.replace(old_sec_bm, new_sec_bm)

    # 3. Cookie Policy settings link & remove javascript:void(0)
    if slug == 'cookie-policy':
        text = text.replace(
            '<a href="javascript:void(0)" onclick="if(window.openCookieSettings)window.openCookieSettings();">',
            '<a href="#cookie-settings" class="js-open-cookie-settings" data-cookie-settings="true" onclick="event.preventDefault(); if (typeof window.openCookieSettings === \'function\') window.openCookieSettings();">'
        )

    # 4. Liability Disclaimers
    if lang == 'en':
        text = text.replace(
            '<p>MST is not responsible for the privacy practices, content or security of third-party websites.</p>',
            '<p>To the extent permitted by applicable law, MST is not liable for the privacy practices, content or security of third-party websites or services. Nothing in this Privacy Policy limits or excludes any statutory rights under the Personal Data Protection Act 2010 (PDPA) that cannot lawfully be excluded.</p>'
        )
        text = text.replace(
            '<p>MST is not responsible for deterioration caused by improper storage, handling, thawing, cooking, delay or circumstances outside MST\'s reasonable control.</p>',
            '<p>To the extent permitted by applicable law, MST is not liable for deterioration caused by improper customer storage, handling, thawing, cooking, delayed acceptance, or circumstances outside MST\'s reasonable control. Nothing in this Refund & Return Policy excludes, limits or restricts any statutory rights or consumer guarantees under the Consumer Protection Act 1999 or other applicable laws that cannot lawfully be excluded or restricted.</p>'
        )
        text = text.replace(
            '<p class="text-muted small">MST is not responsible for delays or additional charges caused by inaccurate or incomplete information.</p>',
            '<p class="text-muted small">To the extent permitted by applicable law, MST is not liable for delays or additional charges caused by inaccurate or incomplete information provided by the customer.</p>'
        )
        text = text.replace(
            '<p class="text-muted small">MST is not responsible for deterioration caused after delivery due to improper storage, thawing, handling or other circumstances attributable to the customer.</p>',
            '<p class="text-muted small">To the extent permitted by applicable law, MST is not liable for deterioration occurring after delivery due to improper customer storage, thawing, handling, or other circumstances attributable to the customer. Nothing in this policy limits or excludes statutory rights that cannot legally be excluded under applicable law.</p>'
        )
        text = text.replace(
            '<p>MST is not responsible for deterioration caused by improper storage, handling, thawing, cooking or delays attributable to the customer.</p>',
            '<p>To the extent permitted by applicable law, MST is not liable for product deterioration caused by improper customer storage, handling, thawing, cooking or delays attributable to the customer.</p>'
        )
        text = text.replace(
            '<p>MST is not responsible for matters outside its reasonable control involving third-party services.</p>',
            '<p>To the extent permitted by applicable law, MST is not liable for matters outside its reasonable control involving third-party services.</p>'
        )
        text = text.replace(
            '<p>MST will not be responsible for delay or failure to perform caused by circumstances beyond its reasonable control, including but not limited to:</p>',
            '<p>To the extent permitted by applicable law, MST will not be liable for delay or failure to perform caused by circumstances beyond its reasonable control, including but not limited to:</p>'
        )

    elif lang == 'zh':
        text = text.replace(
            '<p>MST 对任何第三方网站的隐私政策、内容或安全性不承担任何责任。</p>',
            '<p>在适用法律允许的范围内，MST 对第三方网站的隐私政策、内容或安全性不承担责任。本隐私政策的任何内容均不排除或限制客户在《2010年个人数据保护法》(PDPA) 及其他适用法律下享有的不可依法排除的法定权利。</p>'
        )
        text = text.replace(
            '<p>对于因客户不当储存、操作不当、擅自解冻、烹饪失误、延误收货或超出 MST 合理控制范围的其他情况导致的产品变质，MST 概不承担责任。</p>',
            '<p>在适用法律允许的范围内，对于因客户不当储存、操作不当、擅自解冻、烹饪失误、延误收货或超出 MST 合理控制范围的其他情况导致的产品变质，MST 不承担责任。本退款与退换政策的任何内容均不排除、限制或剥夺消费者根据《1999年消费者保护法》或其它适用法律享有的不可依法排除或限制的法定权利。</p>'
        )
        text = text.replace(
            '<p class="text-muted small">因信息不准确或不完整而导致的配送延误或额外运费，MST 概不承担责任。</p>',
            '<p class="text-muted small">在适用法律允许的范围内，对于因信息不准确或不完整而导致的配送延误或额外费用，MST 不承担责任。本政策不排除或限制适用法律下不可依法排除的法定权利。</p>'
        )
        text = text.replace(
            '<p>因客户自身储存不当、操作失误、解冻不当、烹调或延迟签收而导致的产品变质，MST 概不承担责任。</p>',
            '<p>在适用法律允许的范围内，对于因客户自身储存不当、操作失误、解冻不当、烹调或延迟签收而导致的产品变质，MST 不承担责任。'
        )
        text = text.replace(
            '<p>对于超出 MST 合理控制范围的涉及第三方服务的事项，MST 概不承担责任。</p>',
            '<p>在适用法律允许的范围内，对于超出 MST 合理控制范围的涉及第三方服务的事项，MST 不承担责任。'
        )
        text = text.replace(
            '<p>因超出 MST 合理控制范围的情况导致履约延迟或未能履约，MST 不承担责任，此类情况包括但不限于：</p>',
            '<p>在适用法律允许的范围内，因超出 MST 合理控制范围的情况导致履约延迟或未能履约，MST 不承担责任，此类情况包括但不限于：</p>'
        )

    elif lang == 'bm':
        text = text.replace(
            '<p>MST tidak bertanggungjawab terhadap amalan privasi, kandungan atau keselamatan laman web pihak ketiga.</p>',
            '<p>Setakat yang dibenarkan oleh undang-undang yang terpakai, MST tidak bertanggungjawab terhadap amalan privasi, kandungan atau keselamatan laman web atau perkhidmatan pihak ketiga. Tiada apa-apa dalam Dasar Privasi ini yang mengecualikan atau mengehadkan sebarang hak berkanun di bawah Akta Perlindungan Data Peribadi 2010 (PDPA) yang tidak boleh dikecualikan secara sah.</p>'
        )
        text = text.replace(
            '<p>MST tidak bertanggungjawab terhadap sebarang kemerosotan kualiti yang berpunca daripada penyimpanan yang tidak betul, pengendalian yang salah, penyahfrosan, masakan, kelewatan penerimaan atau keadaan di luar kawalan munasabah MST.</p>',
            '<p>Setakat yang dibenarkan oleh undang-undang yang terpakai, MST tidak bertanggungjawab terhadap sebarang kemerosotan kualiti yang berpunca daripada penyimpanan yang tidak betul, pengendalian yang salah, penyahfrosan, masakan, kelewatan penerimaan atau keadaan di luar kawalan munasabah MST. Tiada apa-apa dalam Dasar Bayaran Balik & Pemulangan ini yang mengecualikan, mengehadkan atau membatalkan sebarang hak berkanun pengguna di bawah Akta Perlindungan Pengguna 1999 atau undang-undang terpakai yang tidak boleh dikecualikan secara sah.</p>'
        )
        text = text.replace(
            '<p class="text-muted small">MST tidak bertanggungjawab terhadap kelewatan atau caj tambahan yang disebabkan oleh maklumat yang tidak tepat atau tidak lengkap.</p>',
            '<p class="text-muted small">Setakat yang dibenarkan oleh undang-undang yang terpakai, MST tidak bertanggungjawab terhadap kelewatan atau caj tambahan yang disebabkan oleh maklumat yang tidak tepat atau tidak lengkap.</p>'
        )
        text = text.replace(
            '<p class="text-muted small">MST tidak bertanggungjawab ke atas kerosakan selepas penghantaran akibat penyimpanan yang tidak betul, penyahbekuan, pengendalian atau keadaan lain yang disebabkan oleh pelanggan.</p>',
            '<p class="text-muted small">Setakat yang dibenarkan oleh undang-undang yang terpakai, MST tidak bertanggungjawab ke atas kerosakan selepas penghantaran akibat penyimpanan yang tidak betul, penyahbekuan, pengendalian atau keadaan lain yang disebabkan oleh pelanggan. Tiada apa-apa dalam dasar ini yang mengehadkan hak berkanun yang tidak boleh dikecualikan di bawah undang-undang yang terpakai.</p>'
        )
        text = text.replace(
            '<p>MST tidak bertanggungjawab terhadap kemerosotan kualiti yang disebabkan oleh penyimpanan, pengendalian, penyahbekuan, memasak yang tidak betul atau kelewatan oleh pelanggan.</p>',
            '<p>Setakat yang dibenarkan oleh undang-undang yang terpakai, MST tidak bertanggungjawab terhadap kemerosotan kualiti yang disebabkan oleh penyimpanan yang tidak betul, pengendalian, penyahbekuan, memasak atau kelewatan oleh pelanggan.</p>'
        )
        text = text.replace(
            '<p>MST tidak bertanggungjawab ke atas perkara di luar kawalan munasabahnya yang melibatkan perkhidmatan pihak ketiga.</p>',
            '<p>Setakat yang dibenarkan oleh undang-undang yang terpakai, MST tidak bertanggungjawab ke atas perkara di luar kawalan munasabahnya yang melibatkan perkhidmatan pihak ketiga.</p>'
        )

    # 5. Delivery / Order Thresholds clarification in terms & shipping
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

# Generate all cleaned contents
slugs = ['privacy-policy', 'terms-and-conditions', 'refund-policy', 'shipping-policy', 'cookie-policy']
cleaned_data = {}

for slug in slugs:
    cleaned_data[slug] = {}
    for lang in ['en', 'zh', 'bm']:
        with open(f'scratch/policy_{slug}_{lang}.html', 'r', encoding='utf-8') as f:
            raw = f.read()
        cleaned_data[slug][lang] = process_policy_text(slug, lang, raw)

print("Policy contents successfully processed in memory.")

# Write PHP script that connects to MySQL and SQLite and updates policies
php_code = f"""<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\\Contracts\\Console\\Kernel::class)->bootstrap();

use Illuminate\\Support\\Facades\\DB;

echo "=== Updating MySQL DB policies ===\\n";
"""

for slug in slugs:
    for lang in ['en', 'zh', 'bm']:
        with open(f'scratch/final_{slug}_{lang}.html', 'w', encoding='utf-8') as f:
            f.write(cleaned_data[slug][lang])

print("Written final HTML files to scratch/final_*")
