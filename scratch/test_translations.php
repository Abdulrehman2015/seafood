<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$keys = [
    'checkout.collection_token',
    'checkout.order_confirmed_title',
    'nav.home',
    'checkout.track_orders',
    'nav.shop',
    'checkout.order_confirmation',
    'checkout.badge_pay_counter',
    'checkout.payment_confirmed',
    'checkout.order_confirmed_cash',
    'checkout.payment_confirmed_title',
    'checkout.cash_instruction_subtitle',
    'checkout.payment_confirmed_desc',
    'checkout.success_subtitle',
    'checkout.print_invoice',
    'checkout.continue_shopping',
    'checkout.order_progress',
    'checkout.order_cancelled_notice',
    'walkin.step_order_placed',
    'checkout.paid',
    'walkin.pay_counter',
    'walkin.step_preparing',
    'walkin.ready_collection',
    'walkin.step_collected',
    'checkout.step_paid',
    'checkout.step_cold_packing',
    'checkout.step_out_delivery',
    'checkout.step_delivered',
    'walkin.title',
    'walkin.counter_title',
    'checkout.scan_or_show',
    'checkout.screenshot_hint',
    'checkout.order_items',
    'checkout.view_short_receipt',
    'checkout.items_count_label',
    'shop.reference_estimated_weight',
    'shop.variable_weight_checkout_short',
    'checkout.special_notes_label',
    'walkin.fulfillment_info',
    'checkout.shipping_info',
    'checkout.store_pickup',
    'checkout.refrigerated_logistics',
    'checkout.recipient',
    'walkin.collection_location',
    'checkout.shipping_address',
    'checkout.mst_confirmed_collection_date',
    'checkout.scheduled_pickup',
    'walkin.open_maps',
    'checkout.mst_confirmed_delivery_date',
    'checkout.scheduled_delivery',
    'checkout.delivery_date_subject_mst',
    'checkout.cold_chain_promise',
    'checkout.cold_chain_desc',
    'checkout.payment_receipt',
    'checkout.cash_method',
    'checkout.stripe_method',
    'checkout.order_reference_number',
    'common.copy',
    'common.copied',
    'checkout.subtotal',
    'checkout.fulfillment_type',
    'checkout.free_pickup',
    'checkout.shipping_logistics',
    'checkout.free_standard_delivery',
    'checkout.discount',
    'checkout.total_due',
    'checkout.total_paid',
    'checkout.receipt_sent_to',
    'checkout.create_account_optional_title',
    'checkout.create_account_optional_desc',
    'checkout.create_account_btn',
    'checkout.need_help_title',
    'checkout.chat_whatsapp',
    'nav.contact',
    'checkout.view_my_orders',
    'checkout.order_more_seafood',
    'checkout.back_to_home',
    'checkout.short_receipt_preview',
    'common.close',
    'checkout.print_now',
    'order.status.payment_pending',
    'order.status.payment_confirmed',
    'order.status.preparation',
    'order.status.ready_collection',
    'order.status.collected',
    'order.status.shipped',
    'order.status.delivered',
    'order.status.cancelled',
];

$svc = app(\App\Services\TranslationService::class);
$translationsInDb = \App\Models\Translation::whereIn('key', array_map(function($k) {
    return str_contains($k, '.') ? explode('.', $k, 2)[1] : $k;
}, $keys))->get();

echo "Database records found: " . $translationsInDb->count() . PHP_EOL;

foreach ($keys as $k) {
    $zhVal = $svc->translate($k, null, [], 'zh');
    $bmVal = $svc->translate($k, null, [], 'bm');
    $enVal = $svc->translate($k, null, [], 'en');
    echo sprintf("%-45s | ZH: %-30s | EN: %s\n", $k, mb_substr($zhVal ?? 'NULL', 0, 30), mb_substr($enVal ?? 'NULL', 0, 30));
}
