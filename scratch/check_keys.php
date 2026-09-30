<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Translation;

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

foreach ($keysToCheck as [$group, $key]) {
    $t = Translation::where('group', $group)->where('key', $key)->first();
    if ($t) {
        echo "[$group.$key] EN: '{$t->text_en}' | ZH: '{$t->text_zh}' | BM: '{$t->text_bm}'\n";
    } else {
        echo "[$group.$key] NOT FOUND in MySQL translations table\n";
    }
}
