<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Translation;

echo "=== COMPREHENSIVE VERIFICATION FOR ITEMS #31-#40 ===\n\n";

$issues = [];

// 1. Check old terms in MySQL translations table
$oldTerms = [
    'Keperluan Perdagangan',
    'Perolehan Tersuai',
    'Semua Kategori / Perolehan Tersuai',
    'Semua Kategori / Penyumberan Tersuai',
    'Pembayaran Selamat',
];

foreach ($oldTerms as $ot) {
    $rows = DB::table('translations')->where('text_bm', 'LIKE', "%$ot%")->get();
    foreach ($rows as $r) {
        $issues[] = "MySQL translation [$r->group.$r->key] contains old term '$ot': '$r->text_bm'";
    }
}

// Check Perdagangan in MySQL translations
$perdaganganRows = DB::table('translations')->where('text_bm', 'LIKE', '%Perdagangan%')->get();
foreach ($perdaganganRows as $r) {
    $issues[] = "MySQL translation [$r->group.$r->key] contains 'Perdagangan': '$r->text_bm'";
}

// Check cart.title in BM
$cartTitle = DB::table('translations')->where('group', 'cart')->where('key', 'title')->first();
if ($cartTitle && $cartTitle->text_bm !== 'Troli Beli-belah') {
    $issues[] = "MySQL cart.title BM is '{$cartTitle->text_bm}', expected 'Troli Beli-belah'";
}

// Check breadcrumb_products_sourcing in BM
$shopBreadcrumb = DB::table('translations')->where('group', 'shop')->where('key', 'breadcrumb_products_sourcing')->first();
if ($shopBreadcrumb && $shopBreadcrumb->text_bm !== 'Produk & Penyumberan') {
    $issues[] = "MySQL shop.breadcrumb_products_sourcing BM is '{$shopBreadcrumb->text_bm}', expected 'Produk & Penyumberan'";
}

echo "1. Database Checks: " . (count($issues) === 0 ? "PASSED (0 issues)\n" : "FAILED (" . count($issues) . " issues)\n");
foreach ($issues as $iss) {
    echo "   - $iss\n";
}

// 2. Render routes and test actual HTML output
echo "\n2. Testing Rendered Pages HTML:\n";

$routesToTest = [
    '/bm/register' => 'BM Registration (Default / Retail)',
    '/bm/register?type=wholesale' => 'BM Registration (Wholesale)',
    '/bm/register?type=trading' => 'BM Registration (Trading)',
    '/bm/products' => 'BM Products Catalog',
    '/bm/cart' => 'BM Cart Page',
    '/bm/walkin' => 'BM Walk-in Express Shop',
    '/en/cart' => 'EN Cart Page',
    '/zh/cart' => 'ZH Cart Page',
    '/en/products' => 'EN Products Catalog',
    '/zh/products' => 'ZH Products Catalog',
];

$routeIssues = [];

foreach ($routesToTest as $uri => $label) {
    $request = Request::create($uri, 'GET');
    $response = $kernel->handle($request);
    $status = $response->getStatusCode();
    $content = $response->getContent();
    
    // Gzip decode if gzipped
    if (str_starts_with($content, "\x1f\x8b")) {
        $content = gzdecode($content);
    }
    
    echo "  Testing $label ($uri) -> HTTP $status\n";
    
    if ($status !== 200) {
        $routeIssues[] = "$label ($uri) returned HTTP $status";
        continue;
    }

    // Specific checks per page
    if (str_contains($uri, '/bm/cart')) {
        // Check breadcrumb
        if (str_contains($content, 'Walk-in Express') && str_contains($content, 'breadcrumb')) {
            $routeIssues[] = "$label contains 'Walk-in Express' in breadcrumb!";
        }
        if (!str_contains($content, 'Troli Beli-belah')) {
            $routeIssues[] = "$label missing 'Troli Beli-belah'!";
        }
        if (str_contains($content, '🔒 Secure Checkout') || str_contains($content, '🔒 Pembayaran Selamat')) {
            $routeIssues[] = "$label contains unsupported secure checkout claim!";
        }
        if (!str_contains($content, 'Teruskan ke Pembayaran')) {
            $routeIssues[] = "$label missing neutral 'Teruskan ke Pembayaran'!";
        }
    }

    if (str_contains($uri, '/en/cart')) {
        if (!str_contains($content, 'Shopping Cart')) {
            $routeIssues[] = "$label missing 'Shopping Cart'!";
        }
        if (!str_contains($content, 'Proceed to Payment')) {
            $routeIssues[] = "$label missing neutral 'Proceed to Payment'!";
        }
    }

    if (str_contains($uri, '/zh/cart')) {
        if (!str_contains($content, '您的购物车')) {
            $routeIssues[] = "$label missing '您的购物车'!";
        }
        if (!str_contains($content, '前往结账')) {
            $routeIssues[] = "$label missing neutral '前往结账'!";
        }
    }

    if (str_contains($uri, '/bm/products')) {
        if (str_contains($content, 'Produk &amp; Perolehan') || str_contains($content, 'Produk & Perolehan')) {
            $routeIssues[] = "$label contains old breadcrumb 'Produk & Perolehan'!";
        }
        if (!str_contains($content, 'Produk &amp; Penyumberan') && !str_contains($content, 'Produk & Penyumberan')) {
            $routeIssues[] = "$label missing 'Produk & Penyumberan'!";
        }
        if (str_contains($content, 'Semua Kategori / Perolehan Tersuai') || str_contains($content, 'Semua Kategori / Penyumberan Tersuai')) {
            $routeIssues[] = "$label contains combined sourcing in category filter!";
        }
    }

    if (str_contains($uri, '/bm/register?type=wholesale')) {
        if (str_contains($content, 'Company Registration No. / SSM / UEN / Other')) {
            $routeIssues[] = "$label contains English 'Company Registration No. / SSM / UEN / Other'!";
        }
        if (!str_contains($content, 'No. Pendaftaran Syarikat / SSM / UEN / Lain-lain')) {
            $routeIssues[] = "$label missing 'No. Pendaftaran Syarikat / SSM / UEN / Lain-lain'!";
        }
        if (str_contains($content, '2. Business Address')) {
            $routeIssues[] = "$label contains English '2. Business Address'!";
        }
        if (!str_contains($content, '2. Alamat Syarikat')) {
            $routeIssues[] = "$label missing '2. Alamat Syarikat'!";
        }
        if (!str_contains($content, 'Alamat penghantaran adalah sama dengan Alamat Syarikat')) {
            $routeIssues[] = "$label missing 'Alamat penghantaran adalah sama dengan Alamat Syarikat'!";
        }
        if (!str_contains($content, 'Maklumat &amp; Komunikasi Pemasaran (Pilihan)') && !str_contains($content, 'Maklumat & Komunikasi Pemasaran (Pilihan)')) {
            $routeIssues[] = "$label missing marketing consent title 'Maklumat & Komunikasi Pemasaran (Pilihan)'!";
        }
        if (!str_contains($content, 'Saya bersetuju untuk menerima maklumat terkini MST melalui WhatsApp.')) {
            $routeIssues[] = "$label missing WhatsApp consent exact wording!";
        }
        if (!str_contains($content, 'Saya bersetuju untuk menerima maklumat terkini MST melalui e-mel.')) {
            $routeIssues[] = "$label missing Email consent exact wording!";
        }
    }

    if (str_contains($uri, '/bm/register?type=trading')) {
        if (str_contains($content, 'Perdagangan')) {
            $routeIssues[] = "$label contains 'Perdagangan'!";
        }
        if (!str_contains($content, 'Dagangan')) {
            $routeIssues[] = "$label missing 'Dagangan'!";
        }
        if (!str_contains($content, '3. Pasaran Sasaran &amp; Destinasi') && !str_contains($content, '3. Pasaran Sasaran & Destinasi')) {
            $routeIssues[] = "$label missing '3. Pasaran Sasaran & Destinasi'!";
        }
        if (!str_contains($content, '4. Keperluan Dagangan')) {
            $routeIssues[] = "$label missing '4. Keperluan Dagangan'!";
        }
        // Check trading requirement options
        $reqs = ['Import', 'Eksport', 'Pengedaran', 'Pembelian Pukal', 'Penyumberan Tersuai', 'Lain-lain'];
        foreach ($reqs as $rq) {
            if (!str_contains($content, $rq)) {
                $routeIssues[] = "$label missing trading requirement option '$rq'!";
            }
        }
    }

    if (str_contains($uri, '/bm/walkin')) {
        if (str_contains($content, 'Semua Kategori / Perolehan Tersuai') || str_contains($content, 'Semua Kategori / Penyumberan Tersuai')) {
            $routeIssues[] = "$label contains combined category filter!";
        }
        if (str_contains($content, 'Ambil pesanan yang telah disediakan di MST Kaunter 2.')) {
            $routeIssues[] = "$label contains exposed MST Kaunter 2 in step 4!";
        }
        if (!str_contains($content, 'Pengambilan sendiri sahaja · Tiada penghantaran')) {
            $routeIssues[] = "$label missing prominent notice 'Pengambilan sendiri sahaja · Tiada penghantaran'!";
        }
        if (!str_contains($content, 'Walk-in Express dikhususkan untuk pembelian runcit dan pengambilan sendiri di premis.')) {
            $routeIssues[] = "$label missing service desc 'Walk-in Express dikhususkan untuk pembelian runcit dan pengambilan sendiri di premis.'!";
        }
    }

    // Global check on all pages: no page-specific #cookie-settings
    if (str_contains($content, 'href="#cookie-settings"')) {
        $routeIssues[] = "$label contains href='#cookie-settings'!";
    }

    $kernel->terminate($request, $response);
}

echo "\nRendered Page Verification: " . (count($routeIssues) === 0 ? "ALL 10 ROUTES 100% PASSED (0 issues)!\n" : "FAILED (" . count($routeIssues) . " issues)\n");
foreach ($routeIssues as $iss) {
    echo "   - $iss\n";
}
