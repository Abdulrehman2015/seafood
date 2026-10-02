<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

\App\Models\Setting::configureStripe();
$secret = \App\Models\Setting::getStripeSecretKey();
echo "Stripe Secret: " . substr($secret, 0, 10) . "..." . PHP_EOL;

\Stripe\Stripe::setApiKey($secret);

try {
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'myr',
                'product_data' => ['name' => 'Atlantic Salmon Fillet (500g)'],
                'unit_amount' => 2590,
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => 'http://127.0.0.1:8005/en/checkout/stripe/success?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => 'http://127.0.0.1:8005/en/checkout/stripe/cancel',
        'customer_email' => 'ss4871836@gmail.com',
    ]);
    echo "SUCCESS: " . $session->url . PHP_EOL;
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . PHP_EOL;
}
