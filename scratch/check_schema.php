<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;

echo "Order Items columns: " . implode(', ', Schema::getColumnListing('order_items')) . "\n";
echo "Carts columns: " . implode(', ', Schema::getColumnListing('carts')) . "\n";
echo "Orders columns: " . implode(', ', Schema::getColumnListing('orders')) . "\n";
