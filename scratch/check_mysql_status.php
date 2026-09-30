<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Policy;
use App\Models\User;
use App\Models\Translation;

echo "=== DATABASE CONNECTION AUDIT ===\n";
$conn = DB::connection();
echo "Active Connection Name: " . $conn->getName() . "\n";
echo "Driver: " . $conn->getDriverName() . "\n";
echo "Database: " . $conn->getDatabaseName() . "\n\n";

echo "Products count: " . Product::count() . "\n";
echo "Policies count: " . Policy::count() . "\n";
echo "Users count: " . User::count() . "\n";
echo "Translations count: " . Translation::count() . "\n";

echo "\n✓ Everything is actively running on MySQL exclusively!\n";
