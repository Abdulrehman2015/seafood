<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

app()->setLocale('zh');
session(['locale' => 'zh']);

$order = \App\Models\Order::find(23) ?? \App\Models\Order::latest()->first();

if (!$order) {
    echo "No order found!\n";
    exit(1);
}

echo "Testing Order ID: {$order->id} (Status: {$order->status}, Fulfillment: {$order->fulfillment_type}, Payment: {$order->payment_method})\n\n";

$view = view('checkout.success', compact('order'))->render();

// Check for key English text that was previously un-translated
$checks = [
    'Pay Cash at Counter',
    'In-Store Order Confirmed!',
    'Your order is recorded in our system. Please show your collection token at Counter 2 to pay and collect your fresh seafood.',
    'Print Receipt',
    'Order Progress & Live Status',
    'Token Created',
    'Pending Pay',
    'Packing at SILC',
    'Counter 2',
    'Order Collected',
    'Final Step',
    'Payment & Invoice Details',
    'Counter Cash',
    'ORDER REFERENCE NUMBER',
    'Copy',
    'Items Subtotal',
    'Total Payable at Counter',
    'Itemized receipt & order confirmation sent to:',
    'Show this screen or QR code upon arrival at Counter 2',
    'MST Cold-Chain Facility · SILC Industrial Park',
    'OFFICIAL IN-STORE COLLECTION SLIP',
    'ITEM DESCRIPTION',
    'TOTAL CASH PAYABLE:'
];

$foundUntranslated = 0;
foreach ($checks as $englishStr) {
    if (str_contains($view, $englishStr)) {
        echo "[WARNING] Found untranslated English string: \"{$englishStr}\"\n";
        $foundUntranslated++;
    } else {
        echo "[PASS] Not found in English: \"{$englishStr}\"\n";
    }
}

if ($foundUntranslated === 0) {
    echo "\n===> ALL CHECKOUT SUCCESS PAGE TEXT SUCCESSFULLY TRANSLATED TO CHINESE (ZH)! <===\n";
} else {
    echo "\n===> Found {$foundUntranslated} untranslated strings. <===\n";
}
