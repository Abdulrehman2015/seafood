import json
import re

# 1. Update lang/zh.json
with open('lang/zh.json', 'r', encoding='utf-8') as f:
    zh_content = f.read()

# Replace 客制化采购 with 定制化采购
zh_content = zh_content.replace('客制化采购', '定制化采购')
zh_data = json.loads(zh_content)

zh_data['auth.fulfilment_walkin'] = '自提'
zh_data['fulfilment_walkin'] = '自提'
zh_data['auth.auth.fulfilment_walkin'] = '自提'
zh_data['auth.field_existing_customer_question'] = '现行 MST 客户：是 / 否'
zh_data['field_existing_customer_question'] = '现行 MST 客户：是 / 否'
zh_data['auth.auth.field_existing_customer_question'] = '现行 MST 客户：是 / 否'
zh_data['auth.verification_in_progress'] = '审核中'
zh_data['auth.application_received_lead'] = '感谢您，'
zh_data['auth.application_received_body'] = '您申请的'
zh_data['auth.account_word'] = '账户'
zh_data['auth.application_with_mst'] = '（所属企业：'
zh_data['auth.application_has_been_received'] = '）已提交，现由 MST 团队进行人工审核。'
zh_data['auth.registered_business_entity'] = '注册企业 / 实体'
zh_data['auth.what_happens_next'] = '接下来的流程'
zh_data['auth.review_step_1'] = '我们的商业团队将在 1–2 个工作日内完成企业资质审核。'
zh_data['auth.review_step_2'] = '审核通过后，您将获得批发 / 贸易专属定价及相关业务功能权限。'
zh_data['auth.review_step_3'] = '系统将发送邮件通知确认您的账户审批结果。'
zh_data['auth.btn_refresh_status'] = '刷新审核状态'
zh_data['auth.btn_sign_out'] = '退出登录'
zh_data['auth.need_urgent_help'] = '如有紧急需求？'
zh_data['auth.whatsapp_our_desk'] = '通过 WhatsApp 联系我们'
zh_data['auth.auto_checking_status'] = '后台正在自动检测审批状态...'
zh_data['auth.application_not_approved_title'] = '申请未获通过'
zh_data['auth.rejection_p1'] = '很抱歉，您申请的'
zh_data['auth.rejection_p2'] = '账户（所属企业：'
zh_data['auth.rejection_p3'] = '）目前未能通过审核。'
zh_data['auth.reason_from_admin'] = '管理团队审核意见：'
zh_data['auth.assistance_title'] = '需要协助或重新申请？'
zh_data['auth.rejection_step_1'] = '如需补充资质文件或更新企业信息，请联系客服团队。'
zh_data['auth.rejection_step_2'] = '一旦管理员重新审核批准，此页面将自动跳转至您的控制面板。'
zh_data['auth.rejection_step_3'] = '您亦可直接通过 WhatsApp 或邮件联系我们以加快处理。'
zh_data['shop.variable_weight_title'] = '称重浮动商品'
zh_data['shop.estimated_reference_weight'] = '参考 / 预估重量：'
zh_data['shop.variable_weight_billing_notice_title'] = '称重说明：'
zh_data['shop.variable_weight_billing_notice'] = '所显示的重量（如 ±800g）为参考 / 预估重量，非最终计费重量。最终计费金额将在称重出库后按 实际最终重量 × 适用单价 计算。'

with open('lang/zh.json', 'w', encoding='utf-8') as f:
    json.dump(zh_data, f, ensure_ascii=False, indent=2)
print("Updated lang/zh.json")

# 2. Update lang/en.json
with open('lang/en.json', 'r', encoding='utf-8') as f:
    en_data = json.load(f)

en_data['auth.fulfilment_walkin'] = 'Self-Collection'
en_data['fulfilment_walkin'] = 'Self-Collection'
en_data['auth.auth.fulfilment_walkin'] = 'Self-Collection'
en_data['auth.field_existing_customer_question'] = 'Existing MST Customer: Yes / No'
en_data['field_existing_customer_question'] = 'Existing MST Customer: Yes / No'
en_data['auth.auth.field_existing_customer_question'] = 'Existing MST Customer: Yes / No'
en_data['auth.walkin_supporting_note'] = 'Just shopping through our Walk-in Menu? You do not need an account to browse or place an order for self-collection.'
en_data['walkin_supporting_note'] = 'Just shopping through our Walk-in Menu? You do not need an account to browse or place an order for self-collection.'
en_data['auth.auth.walkin_supporting_note'] = 'Just shopping through our Walk-in Menu? You do not need an account to browse or place an order for self-collection.'
en_data['auth.trading_pricing_disclaimer'] = 'Trading pricing and supply arrangements are subject to MST review and approval, product availability, specifications, order volume, destination and applicable trading requirements.'
en_data['auth.verification_in_progress'] = 'Verification in Progress'
en_data['auth.application_received_lead'] = 'Thank you,'
en_data['auth.application_received_body'] = 'Your application for a'
en_data['auth.account_word'] = 'Account'
en_data['auth.application_with_mst'] = 'with'
en_data['auth.application_has_been_received'] = 'has been received and is pending MST review.'
en_data['auth.registered_business_entity'] = 'Registered Business Entity'
en_data['auth.what_happens_next'] = 'What happens next?'
en_data['auth.review_step_1'] = 'Our team reviews your business information within 1–2 business days.'
en_data['auth.review_step_2'] = 'Once approved by MST, you will be able to access wholesale/trading pricing and business features.'
en_data['auth.review_step_3'] = 'You will also receive a notification confirming your account approval status.'
en_data['auth.btn_refresh_status'] = 'Refresh Status'
en_data['auth.btn_sign_out'] = 'Sign Out'
en_data['auth.need_urgent_help'] = 'Need urgent assistance?'
en_data['auth.whatsapp_our_desk'] = 'WhatsApp Our Desk'
en_data['auth.auto_checking_status'] = 'Auto-checking status in background...'
en_data['auth.application_not_approved_title'] = 'Application Not Approved'
en_data['auth.rejection_p1'] = 'Unfortunately, your application for a'
en_data['auth.rejection_p2'] = 'account with'
en_data['auth.rejection_p3'] = 'could not be approved at this time.'
en_data['auth.reason_from_admin'] = 'Reason from Administration:'
en_data['auth.assistance_title'] = 'Need Assistance or Re-application?'
en_data['auth.rejection_step_1'] = 'If you believe this was in error, please contact our support desk to provide updated credentials or SSM documentation.'
en_data['auth.rejection_step_2'] = 'Once an admin approves or updates your status, this page will automatically redirect you into your Dashboard.'
en_data['auth.rejection_step_3'] = 'You may also reach our team via WhatsApp or email directly for expedited review.'
en_data['shop.variable_weight_title'] = 'Variable-Weight Product'
en_data['shop.estimated_reference_weight'] = 'Reference / Estimated Weight:'
en_data['shop.variable_weight_billing_notice_title'] = 'Variable-Weight Notice:'
en_data['shop.variable_weight_billing_notice'] = 'The displayed weight (e.g. ±800g) is an estimated/reference weight, not a guaranteed final weight. Where applicable, final billing is calculated as: Actual Final Weight × Applicable Unit Price upon weighing and fulfilment.'

with open('lang/en.json', 'w', encoding='utf-8') as f:
    json.dump(en_data, f, ensure_ascii=False, indent=2)
print("Updated lang/en.json")

# 3. Update lang/bm.json and lang/ms.json
def update_bm_data(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        text = f.read()

    # Replacements for Custom Sourcing
    text = text.replace('Perolehan Tersuai', 'Penyumberan Tersuai')
    text = text.replace('perolehan tersuai', 'penyumberan tersuai')
    text = text.replace('PEROLEHAN TERSUAI', 'PENYUMBERAN TERSUAI')
    text = text.replace('PEROLEHAN penyumberan tersuai', 'PENYUMBERAN TERSUAI')
    text = text.replace('Sumber Tersuai', 'Penyumberan Tersuai')
    text = text.replace('sumber tersuai', 'penyumberan tersuai')

    # Replacements for Trading Terminology:
    # Must use Dagangan, NOT Perdagangan for Trading Account / Category / Pricing / Requirements
    text = text.replace('Akaun Perdagangan', 'Akaun Dagangan')
    text = text.replace('akaun perdagangan', 'akaun dagangan')
    text = text.replace('Daftar Akaun Perdagangan', 'Daftar Akaun Dagangan')
    text = text.replace('daftar akaun perdagangan', 'daftar akaun dagangan')
    text = text.replace('Harga Perdagangan', 'Harga Dagangan')
    text = text.replace('harga perdagangan', 'harga dagangan')
    text = text.replace('Keperluan Perdagangan', 'Keperluan Dagangan')
    text = text.replace('keperluan perdagangan', 'keperluan dagangan')
    text = text.replace('Perdagangan B2B', 'Dagangan B2B')
    text = text.replace('perdagangan B2B', 'dagangan B2B')
    text = text.replace('Tahap Rakan Perdagangan', 'Tahap Rakan Dagangan')
    text = text.replace('Meja Perdagangan B2B', 'Meja Dagangan B2B')
    text = text.replace('Profil Rakan Perdagangan B2B', 'Profil Rakan Dagangan B2B')
    text = text.replace('Penilaian Perdagangan Kontena', 'Penilaian Dagangan Kontena')
    text = text.replace('penilaian perdagangan kontena', 'penilaian dagangan kontena')
    text = text.replace('Kelebihan Perolehan & Perdagangan B2B', 'Kelebihan Penyumberan & Dagangan B2B')
    text = text.replace('Kelebihan Penyumberan & Perdagangan B2B', 'Kelebihan Penyumberan & Dagangan B2B')
    text = text.replace('kelayakan perdagangan', 'kelayakan dagangan')
    text = text.replace('rakan perdagangan', 'rakan dagangan')
    text = text.replace('perdagangan eksport', 'dagangan eksport')
    text = text.replace('Perdagangan & Eksport', 'Dagangan & Eksport')
    text = text.replace('Perdagangan & Bekalan Pukal', 'Dagangan & Bekalan Pukal')
    text = text.replace('Bekalan Perdagangan', 'Bekalan Dagangan')
    text = text.replace('Harga borong dan perdagangan', 'Harga borong dan dagangan')

    # BM Retail Registration: Ambil Sendiri / Kaunter -> pengambilan sendiri
    text = text.replace('membuat pesanan Ambil Sendiri / Kaunter', 'membuat pesanan untuk pengambilan sendiri.')
    text = text.replace('membuat pesanan pengambilan sendiri di MST Kaunter 2.', 'membuat pesanan untuk pengambilan sendiri.')

    bm_data = json.loads(text)

    # Explicit key updates
    bm_data['common.trading'] = 'Dagangan'
    bm_data['trading'] = 'Dagangan'
    bm_data['common.tier_trading'] = 'Dagangan B2B'
    bm_data['tier_trading'] = 'Dagangan B2B'
    bm_data['account.tier_trading'] = 'Dagangan B2B'
    bm_data['auth.fulfilment_walkin'] = 'Pengambilan Sendiri'
    bm_data['fulfilment_walkin'] = 'Pengambilan Sendiri'
    bm_data['auth.auth.fulfilment_walkin'] = 'Pengambilan Sendiri'
    bm_data['auth.fulfilment_delivery'] = 'Penghantaran'
    bm_data['fulfilment_delivery'] = 'Penghantaran'
    bm_data['auth.auth.fulfilment_delivery'] = 'Penghantaran'
    bm_data['auth.fulfilment_not_sure'] = 'Belum Pasti Lagi'
    bm_data['fulfilment_not_sure'] = 'Belum Pasti Lagi'
    bm_data['auth.auth.fulfilment_not_sure'] = 'Belum Pasti Lagi'
    bm_data['auth.field_existing_customer_question'] = 'Pelanggan Sedia Ada MST: Ya / Tidak'
    bm_data['field_existing_customer_question'] = 'Pelanggan Sedia Ada MST: Ya / Tidak'
    bm_data['auth.auth.field_existing_customer_question'] = 'Pelanggan Sedia Ada MST: Ya / Tidak'

    bm_data['auth.walkin_supporting_note'] = 'Hanya membeli melalui Menu Walk-in kami? Anda tidak memerlukan akaun untuk melihat atau membuat pesanan untuk pengambilan sendiri.'
    bm_data['walkin_supporting_note'] = 'Hanya membeli melalui Menu Walk-in kami? Anda tidak memerlukan akaun untuk melihat atau membuat pesanan untuk pengambilan sendiri.'
    bm_data['auth.auth.walkin_supporting_note'] = 'Hanya membeli melalui Menu Walk-in kami? Anda tidak memerlukan akaun untuk melihat atau membuat pesanan untuk pengambilan sendiri.'

    bm_data['auth.trading_pricing_disclaimer'] = 'Harga dagangan dan pengaturan bekalan tertakluk kepada semakan dan kelulusan MST, ketersediaan produk, spesifikasi, jumlah pesanan, destinasi dan keperluan dagangan yang berkenaan.'
    bm_data['auth.auth.trading_pricing_disclaimer'] = 'Harga dagangan dan pengaturan bekalan tertakluk kepada semakan dan kelulusan MST, ketersediaan produk, spesifikasi, jumlah pesanan, destinasi dan keperluan dagangan yang berkenaan.'

    bm_data['auth.verification_in_progress'] = 'Pengesahan Sedang Dijalankan'
    bm_data['auth.application_received_lead'] = 'Terima kasih,'
    bm_data['auth.application_received_body'] = 'Permohonan anda untuk'
    bm_data['auth.account_word'] = 'Akaun'
    bm_data['auth.application_with_mst'] = 'bersama'
    bm_data['auth.application_has_been_received'] = 'telah diterima dan sedang menunggu semakan pihak MST.'
    bm_data['auth.registered_business_entity'] = 'Entiti Perniagaan Berdaftar'
    bm_data['auth.what_happens_next'] = 'Apakah langkah seterusnya?'
    bm_data['auth.review_step_1'] = 'Pasukan komersial kami menyemak maklumat perniagaan anda dalam masa 1–2 hari bekerja.'
    bm_data['auth.review_step_2'] = 'Setelah diluluskan oleh MST, anda akan dapat mengakses harga borong / dagangan serta ciri perniagaan yang berkenaan.'
    bm_data['auth.review_step_3'] = 'Anda juga akan menerima pemberitahuan e-mel yang mengesahkan status kelulusan akaun anda.'
    bm_data['auth.btn_refresh_status'] = 'Muat Semula Status'
    bm_data['auth.btn_sign_out'] = 'Log Keluar'
    bm_data['auth.need_urgent_help'] = 'Perlukan bantuan segera?'
    bm_data['auth.whatsapp_our_desk'] = 'WhatsApp Meja Khidmat Kami'
    bm_data['auth.auto_checking_status'] = 'Menyemak status kelulusan secara automatik di latar belakang...'

    bm_data['auth.application_not_approved_title'] = 'Permohonan Tidak Diluluskan'
    bm_data['auth.rejection_p1'] = 'Dukacita dimaklumkan bahawa permohonan anda untuk'
    bm_data['auth.rejection_p2'] = 'akaun bersama'
    bm_data['auth.rejection_p3'] = 'tidak dapat diluluskan pada masa ini.'
    bm_data['auth.reason_from_admin'] = 'Sebab daripada Pentadbiran:'
    bm_data['auth.assistance_title'] = 'Perlukan Bantuan atau Permohonan Semula?'
    bm_data['auth.rejection_step_1'] = 'Sekiranya terdapat kesilapan, sila hubungi meja sokongan kami untuk mengemukakan maklumat atau dokumen SSM yang dikemas kini.'
    bm_data['auth.rejection_step_2'] = 'Sebaik sahaja pentadbir meluluskan atau mengemas kini status anda, halaman ini akan dialihkan secara automatik ke Papan Pemuka anda.'
    bm_data['auth.rejection_step_3'] = 'Anda juga boleh menghubungi pasukan kami secara terus melalui WhatsApp atau e-mel untuk semakan pantas.'

    bm_data['shop.variable_weight_title'] = 'Produk Berat Boleh Ubah'
    bm_data['shop.estimated_reference_weight'] = 'Anggaran / Berat Rujukan:'
    bm_data['shop.variable_weight_billing_notice_title'] = 'Nota Berat Boleh Ubah:'
    bm_data['shop.variable_weight_billing_notice'] = 'Berat yang dipaparkan (cth. ±800g) adalah anggaran/berat rujukan, bukan berat akhir yang dijamin. Jika berkenaan, pengebilan akhir dikira sebagai: Berat Akhir Sebenar × Harga Unit Berkenaan selepas ditimbang dan dipenuhi.'

    with open(filepath, 'w', encoding='utf-8') as f:
        json.dump(bm_data, f, ensure_ascii=False, indent=2)
    print(f"Updated {filepath}")

update_bm_data('lang/bm.json')
update_bm_data('lang/ms.json')

print("All JSON files successfully updated.")
