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
use Illuminate\Support\Facades\Artisan;

// ─── Direct Root Homepage Route (Zero Redirects for Pingdom 100 Score) ────────
Route::get('/', function (\Illuminate\Http\Request $request) {
    $locale = session('locale', $request->cookie('app_lang', $request->cookie('locale', config('app.locale', 'en'))));
    if (!in_array($locale, ['en', 'zh', 'bm'])) {
        $locale = 'en';
    }
    app()->setLocale($locale);
    return app(\App\Http\Controllers\HomeController::class)->index();
})->name('home.root');

// Root /products & /shop direct routes -> redirect to localized /products
Route::get('/products', function (\Illuminate\Http\Request $request) {
    $locale = session('locale', $request->cookie('app_lang', $request->cookie('locale', config('app.locale', 'en'))));
    if (!in_array($locale, ['en', 'zh', 'bm'])) {
        $locale = 'en';
    }
    return redirect()->to("/{$locale}/products" . ($request->getQueryString() ? '?' . $request->getQueryString() : ''), 301);
});
Route::get('/shop', function (\Illuminate\Http\Request $request) {
    $locale = session('locale', $request->cookie('app_lang', $request->cookie('locale', config('app.locale', 'en'))));
    if (!in_array($locale, ['en', 'zh', 'bm'])) {
        $locale = 'en';
    }
    return redirect()->to("/{$locale}/products" . ($request->getQueryString() ? '?' . $request->getQueryString() : ''), 301);
});

// ─── Global System Routes (No locale prefix needed) ───────────────────────────

// XML Sitemap (Valid, standards-compliant, multilingual)
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

// ─── Favicon & Static File Routes (with proper Cache + No-Cookie headers) ────
// These explicit routes ensure correct headers even on PHP built-in dev server
// (which doesn't process .htaccess). On production Apache, .htaccess takes over.
Route::get('/favicon.ico', function () {
    $path = public_path('favicon.ico');
    if (!file_exists($path)) abort(404);
    $content = file_get_contents($path);
    $etag = '"' . md5($content) . '"';
    if (request()->header('If-None-Match') === $etag) {
        return response('', 304, [
            'ETag'          => $etag,
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }
    $response = response($content, 200, [
        'Content-Type'   => 'image/x-icon',
        'Cache-Control'  => 'public, max-age=31536000, immutable',
        'Expires'        => gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT',
        'ETag'           => $etag,
        'Content-Length' => strlen($content),
    ]);
    $response->headers->remove('Set-Cookie');
    $response->headers->remove('Cookie');
    return $response;
})->withoutMiddleware('web');

Route::get('/apple-touch-icon.png', function () {
    $path = public_path('apple-touch-icon.png');
    if (!file_exists($path)) abort(404);
    $content = file_get_contents($path);
    $etag = '"' . md5($content) . '"';
    if (request()->header('If-None-Match') === $etag) {
        return response('', 304, [
            'ETag'          => $etag,
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }
    $response = response($content, 200, [
        'Content-Type'   => 'image/png',
        'Cache-Control'  => 'public, max-age=31536000, immutable',
        'Expires'        => gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT',
        'ETag'           => $etag,
        'Content-Length' => strlen($content),
    ]);
    $response->headers->remove('Set-Cookie');
    $response->headers->remove('Cookie');
    return $response;
})->withoutMiddleware('web');

Route::get('/site.webmanifest', function () {
    $path = public_path('site.webmanifest');
    if (!file_exists($path)) abort(404);
    $content = file_get_contents($path);
    return response($content, 200, [
        'Content-Type'   => 'application/manifest+json',
        'Cache-Control'  => 'public, max-age=86400',
        'Expires'        => gmdate('D, d M Y H:i:s', time() + 86400) . ' GMT',
        'Content-Length' => strlen($content),
    ]);
})->withoutMiddleware('web');

// Cookie-free fonts delivery
Route::get('/fonts/{file}', function (string $file) {
    $file = basename($file);
    $path = public_path('fonts/' . $file);
    if (!file_exists($path)) abort(404);
    $content = file_get_contents($path);
    $etag = '"' . md5($content) . '"';
    if (request()->header('If-None-Match') === $etag) {
        return response('', 304, [
            'ETag'          => $etag,
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }
    $response = response($content, 200, [
        'Content-Type'   => 'font/woff2',
        'Cache-Control'  => 'public, max-age=31536000, immutable',
        'Expires'        => gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT',
        'ETag'           => $etag,
        'Content-Length' => strlen($content),
    ]);
    $response->headers->remove('Set-Cookie');
    $response->headers->remove('Cookie');
    return $response;
})->withoutMiddleware('web');

// High-Performance Compressed Asset Delivery (Pingdom: GZIP, Expires & Cookie-free)
Route::get('/cdn-assets/css/{file}', function (string $file) {
    $file = basename($file);
    $path = public_path('css/' . $file);
    if (!file_exists($path)) {
        abort(404);
    }

    $rawEncoding = strtolower(
        request()->header('Accept-Encoding') ?? ($_SERVER['HTTP_ACCEPT_ENCODING'] ?? '')
    );
    $clientWantsRaw = $rawEncoding !== ''
        && str_contains($rawEncoding, 'identity')
        && !str_contains($rawEncoding, 'gzip')
        && !str_contains($rawEncoding, '*');

    $content = file_get_contents($path);
    $etag    = '"' . md5_file($path) . '"';

    if (request()->header('If-None-Match') === $etag) {
        return response('', 304, [
            'ETag'          => $etag,
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }

    $headers = [
        'Content-Type'  => 'text/css; charset=UTF-8',
        'Cache-Control' => 'public, max-age=31536000, immutable',
        'Expires'       => gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT',
        'Vary'          => 'Accept-Encoding',
        'ETag'          => $etag,
    ];

    if (!$clientWantsRaw && function_exists('gzencode')) {
        $gzPath = $path . '.gz';
        if (file_exists($gzPath)) {
            $content = file_get_contents($gzPath);
        } else {
            $compressed = gzencode($content, 6);
            if ($compressed !== false) {
                $content = $compressed;
            }
        }
        $headers['Content-Encoding'] = 'gzip';
    }
    $headers['Content-Length'] = strlen($content);

    $response = response($content, 200, $headers);
    $response->headers->remove('Set-Cookie');
    $response->headers->remove('Cookie');
    return $response;
})->name('cdn.css')->withoutMiddleware('web');

// High-Performance Compressed JavaScript Delivery
Route::get('/cdn-assets/js/{file}', function (string $file) {
    $file = basename($file);
    $path = public_path('js/' . $file);
    if (!file_exists($path)) {
        abort(404);
    }

    $rawEncoding = strtolower(
        request()->header('Accept-Encoding') ?? ($_SERVER['HTTP_ACCEPT_ENCODING'] ?? '')
    );
    $clientWantsRaw = $rawEncoding !== ''
        && str_contains($rawEncoding, 'identity')
        && !str_contains($rawEncoding, 'gzip')
        && !str_contains($rawEncoding, '*');

    $content = file_get_contents($path);
    $etag    = '"' . md5_file($path) . '"';

    if (request()->header('If-None-Match') === $etag) {
        return response('', 304, [
            'ETag'          => $etag,
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }

    $headers = [
        'Content-Type'  => 'application/javascript; charset=UTF-8',
        'Cache-Control' => 'public, max-age=31536000, immutable',
        'Expires'       => gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT',
        'Vary'          => 'Accept-Encoding',
        'ETag'          => $etag,
    ];

    if (!$clientWantsRaw && function_exists('gzencode')) {
        $gzPath = $path . '.gz';
        if (file_exists($gzPath)) {
            $content = file_get_contents($gzPath);
        } else {
            $compressed = gzencode($content, 6);
            if ($compressed !== false) {
                $content = $compressed;
            }
        }
        $headers['Content-Encoding'] = 'gzip';
    }
    $headers['Content-Length'] = strlen($content);

    $response = response($content, 200, $headers);
    $response->headers->remove('Set-Cookie');
    $response->headers->remove('Cookie');
    return $response;
})->name('cdn.js')->withoutMiddleware('web');

// Cookie-free CDN route for images (Pingdom: cookie-free domains + Expires headers)
Route::get('/cdn-assets/img/{path}', function (string $path) {
    $path = str_replace(['..', '\\'], '', $path);

    $candidates = [
        storage_path('app/public/' . $path),
        public_path('storage/' . $path),
        public_path('images/' . $path),
        public_path($path),
    ];

    $filePath = null;
    foreach ($candidates as $candidate) {
        if (file_exists($candidate) && is_file($candidate)) {
            $filePath = $candidate;
            break;
        }
    }

    if (!$filePath) {
        abort(404);
    }

    $mimeMap = [
        'jpg'  => 'image/jpeg', 'jpeg' => 'image/jpeg',
        'png'  => 'image/png',  'gif'  => 'image/gif',
        'webp' => 'image/webp', 'svg'  => 'image/svg+xml',
        'ico'  => 'image/x-icon',
    ];
    $ext  = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    $mime = $mimeMap[$ext] ?? mime_content_type($filePath);
    $content = file_get_contents($filePath);
    $etag = '"' . md5($content) . '"';

    if (request()->header('If-None-Match') === $etag) {
        return response('', 304, [
            'ETag'          => $etag,
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }

    $headers = [
        'Content-Type'   => $mime,
        'Cache-Control'  => 'public, max-age=31536000, immutable',
        'Expires'        => gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT',
        'ETag'           => $etag,
        'Vary'           => 'Accept-Encoding',
        'Content-Length' => strlen($content),
    ];

    if ($ext === 'svg' && function_exists('gzencode')) {
        $rawEncoding = strtolower(request()->header('Accept-Encoding') ?? ($_SERVER['HTTP_ACCEPT_ENCODING'] ?? ''));
        if (!str_contains($rawEncoding, 'identity')) {
            $compressed = gzencode($content, 6);
            if ($compressed !== false) {
                $content = $compressed;
                $headers['Content-Encoding'] = 'gzip';
                $headers['Content-Length'] = strlen($content);
            }
        }
    }

    $response = response($content, 200, $headers);
    $response->headers->remove('Set-Cookie');
    $response->headers->remove('Cookie');
    return $response;
})->name('cdn.img')->where('path', '.+')->withoutMiddleware('web');

// Currency Switcher (RM, SGD, USD)
Route::get('/currency/{code}', [\App\Http\Controllers\CurrencyController::class, 'switch'])->name('currency.switch');
Route::match(['get', 'post'], '/currency/switch/{code?}', [\App\Http\Controllers\CurrencyController::class, 'switch'])->name('currency.switch.post');

// Multilingual Language Switcher (EN, ZH, BM)
Route::get('/language/{locale}', [\App\Http\Controllers\LanguageController::class, 'switch'])->name('language.switch');
Route::match(['get', 'post'], '/language/switch/{locale?}', [\App\Http\Controllers\LanguageController::class, 'switch'])->name('language.switch.post');

// Newsletter Subscription
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->middleware('throttle:15,1')->name('newsletter.subscribe');

// Live Registration Field Verification (Company Similarity Warning, Unique SSM, Unique Email)
Route::match(['get', 'post'], '/api/verify-registration-field', function (\Illuminate\Http\Request $request, \App\Services\CompanyVerificationService $verifier) {
    $field = $request->input('field');
    $value = trim($request->input('value', ''));

    if ($field === 'company_name' && !empty($value)) {
        $sim = $verifier->checkSimilarity($value);
        return response()->json([
            'field'              => 'company_name',
            'has_warning'        => $sim['has_similarity'],
            'matched_company'    => $sim['matched_company'],
            'similarity_percent' => $sim['similarity_percent'],
            'message'            => $sim['message'],
        ]);
    }

    if ($field === 'company_reg_no' && !empty($value)) {
        $ssm = $verifier->checkSsmUniqueness($value);
        return response()->json([
            'field'        => 'company_reg_no',
            'is_duplicate' => $ssm['is_duplicate'],
            'matched'      => $ssm['matched'],
            'message'      => $ssm['message'],
        ]);
    }

    if ($field === 'email' && !empty($value)) {
        $em = $verifier->checkEmailUniqueness($value);
        return response()->json([
            'field'    => 'email',
            'is_taken' => $em['is_taken'],
            'message'  => $em['message'],
        ]);
    }

    return response()->json(['valid' => true]);
})->name('register.verify_field');

// Approval status live API check
Route::get('/api/check-approval-status', function () {
    if (!auth()->check()) {
        return response()->json([
            'logged_in' => false,
            'status'    => 'guest',
            'redirect'  => route('login'),
        ]);
    }
    $user = auth()->user()->fresh();

    $redirect = match ($user->approval_status) {
        'approved' => route($user->isAdmin() ? 'admin.dashboard' : 'account.dashboard'),
        'pending'  => route('approval.pending'),
        'rejected' => route('approval.rejected'),
        default    => route('home'),
    };

    return response()->json([
        'logged_in' => true,
        'approved'  => $user->isApproved(),
        'pending'   => $user->isPending(),
        'rejected'  => $user->isRejected(),
        'status'    => $user->approval_status,
        'redirect'  => $redirect,
    ]);
})->name('approval.check_status');

// ─── Localized Application Routes ({locale} = en, zh, bm) ─────────────────────
Route::prefix('{locale}')->whereIn('locale', ['en', 'zh', 'bm'])->group(function () {

    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/about', [StaticController::class, 'about'])->name('about');
    Route::get('/contact', [StaticController::class, 'contact'])->name('contact');
    Route::post('/contact', [StaticController::class, 'contactSubmit'])->middleware('throttle:5,1')->name('contact.submit');

    // Product Catalogue (public / retail)
    Route::get('/products', [ShopController::class, 'index'])->name('shop.index');
    Route::get('/shop', function ($locale) {
        $loc = is_string($locale) ? $locale : 'en';
        return redirect()->route('shop.index', array_merge(['locale' => $loc], request()->query()), 301);
    });
    Route::get('/categories', [ShopController::class, 'categories'])->name('categories.index');
    Route::get('/category', fn($locale) => redirect()->route('categories.index', ['locale' => is_string($locale) ? $locale : 'en'], 301));
    Route::get('/products/{product:slug}', [ShopController::class, 'show'])->name('shop.show');
    Route::get('/shop/{product}', function ($locale, $product) {
        $loc = is_string($locale) ? $locale : 'en';
        $slug = is_object($product) ? ($product->slug ?? $product->id) : $product;
        return redirect()->route('shop.show', ['locale' => $loc, 'product' => $slug], 301);
    });

    // Dynamic Policy & Custom Pages
    Route::get('/policy/{slug}', [\App\Http\Controllers\PolicyController::class, 'show'])->name('policy.show');

    // Cart (session + user, public)
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add', [CartController::class, 'add'])->middleware('throttle:60,1')->name('add');
        Route::patch('/{cartId}', [CartController::class, 'update'])->middleware('throttle:60,1')->name('update');
        Route::delete('/{cartId}', [CartController::class, 'remove'])->name('remove');
        Route::get('/count', [CartController::class, 'count'])->name('count');
    });

    // Approval status pages
    Route::get('/pending-approval', [HomeController::class, 'approvalPending'])->name('approval.pending');
    Route::get('/account-rejected', [HomeController::class, 'approvalRejected'])->name('approval.rejected');

    // Walk-in Routes
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

    // Checkout Routes
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->middleware('auth')->name('index');
        Route::get('/quotation/{quotation}', [CheckoutController::class, 'fromQuotation'])->middleware('auth')->name('fromQuotation');
        Route::post('/payment-intent', [CheckoutController::class, 'createPaymentIntent'])->middleware('throttle:15,1')->name('paymentIntent');
        Route::post('/', [CheckoutController::class, 'store'])->middleware('throttle:10,1')->name('store');
        Route::get('/stripe/success', [CheckoutController::class, 'stripeSuccess'])->name('stripe.success');
        Route::get('/stripe/cancel', [CheckoutController::class, 'stripeCancel'])->name('stripe.cancel');
        Route::get('/success/{order}', [CheckoutController::class, 'success'])->name('success');
    });

    // Authenticated Customer Routes
    Route::middleware('auth')->group(function () {

        Route::get('/dashboard', function () {
            if (auth()->user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('account.dashboard');
        })->name('dashboard');

        // Account
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

    // Auth Routes (Breeze login, register, password reset, etc.)
    require __DIR__ . '/auth.php';
});

// ─── Legacy Unprefixed Route Redirects ─────────────────────────────────────────
$unprefixedRedirects = [
    'about', 'contact', 'shop', 'categories', 'category', 'cart',
    'walkin', 'checkout', 'dashboard', 'account', 'quotations',
    'pending-approval', 'account-rejected', 'login', 'register',
    'forgot-password', 'reset-password', 'policy'
];
foreach ($unprefixedRedirects as $uPath) {
    Route::any($uPath, function (\Illuminate\Http\Request $request) use ($uPath) {
        $locale = session('locale', $request->cookie('app_lang', $request->cookie('locale', config('app.locale', 'en'))));
        if (!in_array($locale, ['en', 'zh', 'bm'])) {
            $locale = 'en';
        }
        $target = '/' . $locale . '/' . $uPath;
        $qs = $request->getQueryString();
        return redirect()->to($target . ($qs ? '?' . $qs : ''));
    });
    Route::any($uPath . '/{any}', function (\Illuminate\Http\Request $request, $any) use ($uPath) {
        $locale = session('locale', $request->cookie('app_lang', $request->cookie('locale', config('app.locale', 'en'))));
        if (!in_array($locale, ['en', 'zh', 'bm'])) {
            $locale = 'en';
        }
        $target = '/' . $locale . '/' . $uPath . '/' . $any;
        $qs = $request->getQueryString();
        return redirect()->to($target . ($qs ? '?' . $qs : ''));
    })->where('any', '.*');
}

// ─── Admin Routes ─────────────────────────────────────────────────────────────

Route::prefix('admin')->name('admin.')->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->group(function () {

    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Admin Profile
    Route::get('profile', [Admin\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [Admin\ProfileController::class, 'update'])->name('profile.update');

    // Products
    Route::resource('products', Admin\ProductController::class);
    Route::post('products/{id}/restore', [Admin\ProductController::class, 'restore'])->name('products.restore');

    // Categories
    Route::resource('categories', Admin\CategoryController::class);

    // Customers
    Route::get('customers', [Admin\CustomerController::class, 'index'])->name('customers.index');
    Route::get('customers/{user}', [Admin\CustomerController::class, 'show'])->name('customers.show');
    Route::patch('customers/{user}', [Admin\CustomerController::class, 'update'])->name('customers.update');
    Route::delete('customers/{user}', [Admin\CustomerController::class, 'destroy'])->name('customers.destroy');
    Route::post('customers/{user}/approve', [Admin\CustomerController::class, 'approve'])->name('customers.approve');
    Route::post('customers/{user}/unblock', [Admin\CustomerController::class, 'unblock'])->name('customers.unblock');
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

    // Cache Clear
    Route::post('cache/clear', function () {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        if (function_exists('opcache_reset')) {
            opcache_reset();
        }
        return response()->json(['success' => true, 'message' => 'All caches cleared successfully.']);
    })->name('cache.clear');

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

    // Policies & Dynamic Pages Module
    Route::post('policies/{policy}/toggle-status', [Admin\PolicyController::class, 'toggleStatus'])->name('policies.toggle-status');
    Route::post('policies/{policy}/translation', [Admin\PolicyController::class, 'updateTranslation'])->name('policies.translation');
    Route::resource('policies', Admin\PolicyController::class);

    // Sitemap Management Module (Dynamic Auto-Generate & Custom Upload)
    Route::get('sitemap', [Admin\SitemapController::class, 'index'])->name('sitemap.index');
    Route::post('sitemap/generate', [Admin\SitemapController::class, 'generate'])->name('sitemap.generate');
    Route::post('sitemap/upload', [Admin\SitemapController::class, 'upload'])->name('sitemap.upload');
    Route::post('sitemap/mode', [Admin\SitemapController::class, 'setMode'])->name('sitemap.mode');
    Route::get('sitemap/download', [Admin\SitemapController::class, 'download'])->name('sitemap.download');
    Route::delete('sitemap/custom', [Admin\SitemapController::class, 'deleteCustom'])->name('sitemap.deleteCustom');

    // Multilingual Translation Manager
    Route::get('translations', [Admin\TranslationController::class, 'index'])->name('translations.index');
    Route::post('translations', [Admin\TranslationController::class, 'update'])->name('translations.update');
    Route::post('translations/create', [Admin\TranslationController::class, 'store'])->name('translations.store');
    Route::delete('translations/{translation}', [Admin\TranslationController::class, 'destroy'])->name('translations.destroy');
    Route::post('translations/sync', [Admin\TranslationController::class, 'sync'])->name('translations.sync');
    Route::post('translations/cache/clear', [Admin\TranslationController::class, 'clearCache'])->name('translations.clearCache');

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

    // Database Management (Admin only) - Download, Import, Restore, Delete, Migrate, Seed
    Route::get('database/download/{filename?}', [Admin\DatabaseController::class, 'download'])->where('filename', '[A-Za-z0-9_.\-]+')->name('database.download');
    Route::post('database/import', [Admin\DatabaseController::class, 'import'])->name('database.import');
    Route::post('database/restore/{filename}', [Admin\DatabaseController::class, 'restore'])->where('filename', '[A-Za-z0-9_.\-]+')->name('database.restore');
    Route::match(['delete', 'post'], 'database/backup/{filename}', [Admin\DatabaseController::class, 'destroy'])->where('filename', '[A-Za-z0-9_.\-]+')->name('database.destroy');
    Route::post('database/migrate', [Admin\DatabaseController::class, 'runMigrations'])->name('database.migrate');
    Route::post('database/seed', [Admin\DatabaseController::class, 'runSeeders'])->name('database.seed');

});

// Local Map Tile Proxy (Safely caches and proxies OSM map tiles with strict bounds)
Route::get('/map-tile/{z}/{x}/{y}', function ($z, $x, $y) {
    $z = (int) $z;
    $x = (int) $x;
    $y = (int) $y;

    // Validate zoom and coordinate ranges
    $maxCoord = (1 << $z);
    if ($z < 0 || $z > 19 || $x < 0 || $y < 0 || $x >= $maxCoord || $y >= $maxCoord) {
        return abort(400, 'Invalid tile coordinates');
    }

    $cacheDir = storage_path("app/map-tiles/{$z}/{$x}");
    if (!is_dir($cacheDir)) {
        @mkdir($cacheDir, 0755, true);
    }
    $cacheFile = "{$cacheDir}/{$y}.png";
    if (!file_exists($cacheFile)) {
        $ch = curl_init("https://tile.openstreetmap.org/{$z}/{$x}/{$y}.png");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'MSTImportExportApp/1.0 (info@mst.my)');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 6);
        $data = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

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
})->whereNumber(['z', 'x', 'y']);
