<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\WalkInController;
use App\Http\Controllers\StaticController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Route;

// ─── Public Routes ───────────────────────────────────────────────────────────

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [StaticController::class, 'about'])->name('about');
Route::get('/contact', [StaticController::class, 'contact'])->name('contact');
Route::post('/contact', [StaticController::class, 'contactSubmit'])->middleware('throttle:5,1')->name('contact.submit');

// Product Catalogue (public / retail)
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/categories', [ShopController::class, 'categories'])->name('categories.index');
Route::get('/category', fn() => redirect()->route('categories.index'));
Route::get('/shop/{product:slug}', [ShopController::class, 'show'])->name('shop.show');

// Currency Switcher (RM, SGD, USD)
Route::get('/currency/{code}', [\App\Http\Controllers\CurrencyController::class, 'switch'])->name('currency.switch');
Route::match(['get', 'post'], '/currency/switch/{code?}', [\App\Http\Controllers\CurrencyController::class, 'switch'])->name('currency.switch.post');

// Newsletter Subscription
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->middleware('throttle:15,1')->name('newsletter.subscribe');

// Cart (session + user, public)
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->middleware('throttle:60,1')->name('add');
    Route::patch('/{cartId}', [CartController::class, 'update'])->middleware('throttle:60,1')->name('update');
    Route::delete('/{cartId}', [CartController::class, 'remove'])->name('remove');
    Route::get('/count', [CartController::class, 'count'])->name('count');
});

// Approval status pages & live check
Route::get('/pending-approval', [HomeController::class, 'approvalPending'])->name('approval.pending');
Route::get('/account-rejected', [HomeController::class, 'approvalRejected'])->name('approval.rejected');
Route::get('/api/check-approval-status', function () {
    if (!auth()->check()) {
        return response()->json(['logged_in' => false, 'approved' => false]);
    }
    $user = auth()->user()->fresh();
    return response()->json([
        'logged_in' => true,
        'approved'  => $user->isApproved(),
        'status'    => $user->approval_status,
        'redirect'  => $user->isApproved() ? route($user->isAdmin() ? 'admin.dashboard' : 'account.dashboard') : null,
    ]);
})->name('approval.check_status');

// ─── Walk-in Routes ───────────────────────────────────────────────────────────

Route::prefix('walkin')->name('walkin.')->group(function () {
    Route::get('/enter', [WalkInController::class, 'entry'])->name('entry');
    Route::get('/exit', [WalkInController::class, 'exit'])->name('exit');

    // Protected by walk-in session
    Route::middleware(\App\Http\Middleware\WalkInMiddleware::class)->group(function () {
        Route::get('/', [WalkInController::class, 'shop'])->name('shop');
        Route::get('/product/{product:slug}', [WalkInController::class, 'show'])->name('show');
        Route::get('/checkout', [WalkInController::class, 'checkout'])->name('checkout');
    });
});

// ─── Checkout Routes ────────────────────────────────────────────────────────
Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->middleware('auth')->name('index');
    Route::get('/quotation/{quotation}', [CheckoutController::class, 'fromQuotation'])->middleware('auth')->name('fromQuotation');
    Route::post('/payment-intent', [CheckoutController::class, 'createPaymentIntent'])->middleware('throttle:15,1')->name('paymentIntent');
    Route::post('/', [CheckoutController::class, 'store'])->middleware('throttle:10,1')->name('store');
    Route::get('/stripe/success', [CheckoutController::class, 'stripeSuccess'])->name('stripe.success');
    Route::get('/stripe/cancel', [CheckoutController::class, 'stripeCancel'])->name('stripe.cancel');
    Route::get('/success/{order}', [CheckoutController::class, 'success'])->name('success');
});

// ─── Authenticated Routes ─────────────────────────────────────────────────────

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('account.dashboard');
    })->name('dashboard');

    // Account (approved customers only for wholesale/trading-specific features)
    Route::prefix('account')->name('account.')->group(function () {
        Route::get('/', [AccountController::class, 'dashboard'])->name('dashboard');
        Route::get('/orders', [AccountController::class, 'orders'])->name('orders');
        Route::get('/orders/{order}', [AccountController::class, 'orderShow'])->name('orders.show');
        Route::get('/orders/{order}/invoice', [AccountController::class, 'invoice'])->name('orders.invoice');
        Route::post('/orders/{order}/reorder', [AccountController::class, 'reorder'])->name('orders.reorder');
        Route::get('/profile', [AccountController::class, 'profile'])->name('profile');
        Route::patch('/profile', [AccountController::class, 'updateProfile'])->name('profile.update');
    });

    // Quotations (trading customers only)
    Route::prefix('quotations')->name('quotations.')->middleware(\App\Http\Middleware\ApprovedCustomerMiddleware::class)->group(function () {
        Route::get('/', [QuotationController::class, 'index'])->name('index');
        Route::get('/create', [QuotationController::class, 'create'])->name('create');
        Route::post('/', [QuotationController::class, 'store'])->middleware('throttle:10,1')->name('store');
        Route::get('/{quotation}', [QuotationController::class, 'show'])->name('show');
        Route::post('/{quotation}/accept', [QuotationController::class, 'accept'])->name('accept');
        Route::post('/{quotation}/reject', [QuotationController::class, 'reject'])->name('reject');
    });

});

// ─── Admin Routes ─────────────────────────────────────────────────────────────

Route::prefix('admin')->name('admin.')->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->group(function () {

    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Products
    Route::resource('products', Admin\ProductController::class);
    Route::post('products/{id}/restore', [Admin\ProductController::class, 'restore'])->name('products.restore');

    // Categories
    Route::resource('categories', Admin\CategoryController::class);
    Route::post('categories/{category}/toggle-featured', [Admin\CategoryController::class, 'toggleFeatured'])->name('categories.toggle-featured');

    // Customers
    Route::get('customers', [Admin\CustomerController::class, 'index'])->name('customers.index');
    Route::get('customers/{user}', [Admin\CustomerController::class, 'show'])->name('customers.show');
    Route::patch('customers/{user}', [Admin\CustomerController::class, 'update'])->name('customers.update');
    Route::post('customers/{user}/approve', [Admin\CustomerController::class, 'approve'])->name('customers.approve');
    Route::post('customers/{user}/reject', [Admin\CustomerController::class, 'reject'])->name('customers.reject');

    // Orders
    Route::get('orders', [Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [Admin\OrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}', [Admin\OrderController::class, 'update'])->name('orders.update');
    Route::get('orders/{order}/invoice', [Admin\OrderController::class, 'invoice'])->name('orders.invoice');

    // Quotations
    Route::get('quotations', [Admin\QuotationController::class, 'index'])->name('quotations.index');
    Route::get('quotations/{quotation}', [Admin\QuotationController::class, 'show'])->name('quotations.show');
    Route::patch('quotations/{quotation}', [Admin\QuotationController::class, 'respond'])->name('quotations.respond');

    // Contact Inquiries / Messages
    Route::get('messages', [Admin\MessageController::class, 'index'])->name('messages.index');
    Route::get('messages/{message}', [Admin\MessageController::class, 'show'])->name('messages.show');
    Route::delete('messages/{message}', [Admin\MessageController::class, 'destroy'])->name('messages.destroy');

    // Newsletter Module
    Route::get('newsletter/export', [Admin\NewsletterController::class, 'exportCsv'])->name('newsletter.export');
    Route::post('newsletter/{subscriber}/toggle-status', [Admin\NewsletterController::class, 'toggleStatus'])->name('newsletter.toggleStatus');
    Route::delete('newsletter/{subscriber}', [Admin\NewsletterController::class, 'destroy'])->name('newsletter.destroy');
    Route::get('newsletter', [Admin\NewsletterController::class, 'index'])->name('newsletter.index');

    // Store Settings & SMTP & Stripe
    Route::get('settings', [Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [Admin\SettingController::class, 'update'])->name('settings.update');
    Route::post('settings/test-email', [Admin\SettingController::class, 'testEmail'])->name('settings.testEmail');
    Route::post('settings/test-stripe', [Admin\SettingController::class, 'testStripe'])->name('settings.testStripe');
    Route::post('settings/currency/sync', [\App\Http\Controllers\CurrencyController::class, 'syncRates'])->name('settings.currency.sync');

    // Email Templates & Notifications
    Route::get('emails', [Admin\EmailTemplateController::class, 'index'])->name('emails.index');
    Route::get('emails/{template}/preview', [Admin\EmailTemplateController::class, 'preview'])->name('emails.preview');
    Route::post('emails/{template}/send-test', [Admin\EmailTemplateController::class, 'sendTest'])->name('emails.send-test');

    // Customer Reviews Module
    Route::post('reviews/{review}/toggle-status', [Admin\ReviewController::class, 'toggleStatus'])->name('reviews.toggleStatus');
    Route::post('reviews/{review}/toggle-featured', [Admin\ReviewController::class, 'toggleFeatured'])->name('reviews.toggleFeatured');
    Route::resource('reviews', Admin\ReviewController::class);

    // Page SEO Module
    Route::resource('page-seo', Admin\PageSeoController::class);

    // Media Gallery Module
    Route::get('gallery', [Admin\GalleryController::class, 'index'])->name('gallery.index');
    Route::post('gallery/upload', [Admin\GalleryController::class, 'upload'])->name('gallery.upload');
    Route::get('gallery/api', [Admin\GalleryController::class, 'api'])->name('gallery.api');
    Route::delete('gallery/{media}', [Admin\GalleryController::class, 'destroy'])->name('gallery.destroy');
    Route::post('gallery/folders', [Admin\GalleryController::class, 'createFolder'])->name('gallery.folders.create');
    Route::delete('gallery/folders/{folder}', [Admin\GalleryController::class, 'destroyFolder'])->name('gallery.folders.destroy');
    Route::post('gallery/folders/{folder}/rename', [Admin\GalleryController::class, 'renameFolder'])->name('gallery.folders.rename');
    Route::get('gallery/folders/{folder}/download/{filename?}', [Admin\GalleryController::class, 'downloadFolder'])->name('gallery.folders.download');
    Route::post('gallery/folders/{folder}/move-files', [Admin\GalleryController::class, 'moveFolderFiles'])->name('gallery.folders.moveFiles');
    Route::post('gallery/{media}/move', [Admin\GalleryController::class, 'move'])->name('gallery.move');
    Route::post('gallery/{media}/copy', [Admin\GalleryController::class, 'copy'])->name('gallery.copy');
    Route::post('gallery/bulk-move', [Admin\GalleryController::class, 'bulkMove'])->name('gallery.bulkMove');
    Route::post('gallery/bulk-copy', [Admin\GalleryController::class, 'bulkCopy'])->name('gallery.bulkCopy');
    Route::post('gallery/bulk-delete', [Admin\GalleryController::class, 'bulkDestroy'])->name('gallery.bulkDestroy');
    Route::post('gallery/{media}/rename', [Admin\GalleryController::class, 'rename'])->name('gallery.rename');
    Route::post('gallery/{media}/crop', [Admin\GalleryController::class, 'crop'])->name('gallery.crop');
    Route::get('gallery/{media}/download/{filename?}', [Admin\GalleryController::class, 'download'])->name('gallery.download');

    // Walk-in QR Code
    Route::get('walkin-qr', [WalkInController::class, 'generateQr'])->name('walkin.qr');

    // Database Dump Download
    Route::get('database/download', function () {
        $dumpPath = base_path('oceanfresh_latest.sql');
        $mysqldump = 'C:\\laragon\\bin\\mysql\\mysql-8.4.3-winx64\\bin\\mysqldump.exe';
        if (file_exists($mysqldump)) {
            @exec("{$mysqldump} -u root --default-character-set=utf8mb4 oceanfresh > \"{$dumpPath}\"");
        }
        if (file_exists($dumpPath)) {
            return response()->download($dumpPath, 'mst_' . date('Y-m-d_His') . '.sql', [
                'Content-Type' => 'application/sql',
            ]);
        }
        return abort(404, 'Dump file could not be generated.');
    })->name('admin.database.download');

});

// Direct one-click download for development
Route::get('/download-database', function () {
    $dumpPath = base_path('oceanfresh_latest.sql');
    $mysqldump = 'C:\\laragon\\bin\\mysql\\mysql-8.4.3-winx64\\bin\\mysqldump.exe';
    if (file_exists($mysqldump)) {
        @exec("{$mysqldump} -u root --default-character-set=utf8mb4 oceanfresh > \"{$dumpPath}\"");
    }
    if (file_exists($dumpPath)) {
        return response()->download($dumpPath, 'oceanfresh_latest_' . date('Y-m-d') . '.sql', [
            'Content-Type' => 'application/sql',
        ]);
    }
    return abort(404, 'Database dump not found');
})->name('database.download');

// Local Map Tile Proxy (Serves map tiles from same-origin localhost, completely immune to ad-blockers and Brave Shields)
Route::get('/map-tile/{z}/{x}/{y}', function ($z, $x, $y) {
    $cacheDir = storage_path("app/map-tiles/{$z}/{$x}");
    if (!is_dir($cacheDir)) {
        @mkdir($cacheDir, 0755, true);
    }
    $cacheFile = "{$cacheDir}/{$y}.png";
    if (!file_exists($cacheFile)) {
        $ch = curl_init("https://tile.openstreetmap.org/{$z}/{$x}/{$y}.png");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'MSTImportExportApp/1.0 (info@mst.my)');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 6);
        $data = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($code === 200 && !empty($data)) {
            @file_put_contents($cacheFile, $data);
        } else {
            $im = imagecreatetruecolor(256, 256);
            $bg = imagecolorallocate($im, 241, 245, 249);
            imagefill($im, 0, 0, $bg);
            ob_start();
            imagepng($im);
            $data = ob_get_clean();
            imagedestroy($im);
        }
    } else {
        $data = file_get_contents($cacheFile);
    }
    return response($data, 200)
        ->header('Content-Type', 'image/png')
        ->header('Cache-Control', 'public, max-age=86400');
});

// ─── Auth Routes (Breeze) ──────────────────────────────────────────────────────

require __DIR__ . '/auth.php';
