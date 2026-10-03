<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$order = \App\Models\Order::latest()->first();

foreach (['zh' => 'Chinese', 'bm' => 'Malay', 'en' => 'English'] as $locale => $name) {
    app()->setLocale($locale);
    session(['locale' => $locale]);
    
    $rendered = view('checkout.success', compact('order'))->render();
    
    echo "==================== LOCAL: {$name} ({$locale}) ====================\n";
    // Check some key localized strings in the HTML
    if ($locale === 'zh') {
        $hasConfirmation = str_contains($rendered, '订单已确认') || str_contains($rendered, '自选订单已确认');
        $hasItems = str_contains($rendered, '订单商品明细');
        $hasReceipt = str_contains($rendered, '付款与发票明细');
        $hasPrint = str_contains($rendered, '打印收据');
        echo "ZH Translations Test: " . ($hasConfirmation && $hasItems && $hasReceipt && $hasPrint ? "ALL PASSED ✓" : "FAILED ✗") . "\n";
    } elseif ($locale === 'bm') {
        $hasConfirmation = str_contains($rendered, 'Pesanan');
        $hasItems = str_contains($rendered, 'Item Pesanan');
        $hasReceipt = str_contains($rendered, 'Butiran Pembayaran & Invois');
        $hasPrint = str_contains($rendered, 'Cetak Resit');
        echo "BM Translations Test: " . ($hasConfirmation && $hasItems && $hasReceipt && $hasPrint ? "ALL PASSED ✓" : "FAILED ✗") . "\n";
    } elseif ($locale === 'en') {
        $hasConfirmation = str_contains($rendered, 'Order Confirmed') || str_contains($rendered, 'Confirmed');
        $hasItems = str_contains($rendered, 'Order Items');
        $hasReceipt = str_contains($rendered, 'Payment & Invoice Details');
        $hasPrint = str_contains($rendered, 'Print Receipt');
        echo "EN Translations Test: " . ($hasConfirmation && $hasItems && $hasReceipt && $hasPrint ? "ALL PASSED ✓" : "FAILED ✗") . "\n";
    }
}
