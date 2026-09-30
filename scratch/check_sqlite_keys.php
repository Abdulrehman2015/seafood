<?php
$sqliteDb = new PDO('sqlite:database/database.sqlite');
$keysToCheck = [
    ['cart', 'title'],
    ['cart', 'secure_checkout'],
    ['cart', 'trust_secure'],
    ['cart', 'trust_encrypted'],
    ['shop', 'breadcrumb_products_sourcing'],
    ['shop', 'all_categories'],
    ['common', 'all_categories'],
    ['contact', 'all_categories'],
    ['auth', 'marketing_consent_title'],
    ['auth', 'consent_whatsapp'],
    ['auth', 'consent_email'],
    ['auth', 'field_company_ssm_required'],
    ['auth', 'section_business_address'],
    ['auth', 'field_business_address'],
    ['auth', 'field_business_country'],
    ['auth', 'same_as_business_address'],
    ['auth', 'section_target_market'],
    ['auth', 'section_trading_requirements'],
    ['auth', 'section_product_requirements'],
    ['auth', 'section_additional_requirements'],
    ['walkin', 'step_indicator'],
    ['walkin', 'step_4_desc'],
    ['walkin', 'counter_desc'],
    ['walkin', 'service_desc'],
    ['walkin', 'proceed_to_payment'],
    ['checkout', 'encrypted_checkout_badge'],
];

$stmt = $sqliteDb->prepare("SELECT text_en, text_zh, text_bm FROM translations WHERE `group` = :group AND `key` = :key");
foreach ($keysToCheck as [$group, $key]) {
    $stmt->execute([':group' => $group, ':key' => $key]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        echo "SQLite [$group.$key] EN: '{$row['text_en']}' | ZH: '{$row['text_zh']}' | BM: '{$row['text_bm']}'\n";
    } else {
        echo "SQLite [$group.$key] NOT FOUND\n";
    }
}
