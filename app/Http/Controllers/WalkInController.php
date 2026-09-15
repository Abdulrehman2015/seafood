<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\PricingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class WalkInController extends Controller
{
    public function __construct(protected PricingService $pricing) {}

    /**
     * QR Code entry point — sets the walk-in session and redirects to catalogue.
     */
    public function entry(Request $request)
    {
        // Set walk-in session (valid for 24 hours)
        Session::put('walkin_session', true);
        Session::put('walkin_started_at', now()->toDateTimeString());

        return redirect()->route('walkin.shop');
    }

    /**
     * Walk-in product catalogue.
     */
    public function shop(Request $request)
    {
        $products = Product::walkinAvailable()
            ->with('category')
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })
            ->when($request->filled('category'), function ($q) use ($request) {
                $q->whereHas('category', fn($cat) => $cat->where('slug', $request->category));
            })
            ->orderBy('sort_order')
            ->paginate(16)
            ->withQueryString();

        $categories = \App\Models\Category::has('products')->orderBy('name')->get();

        return view('walkin.shop', compact('products', 'categories'));
    }

    /**
     * Walk-in product detail.
     */
    public function show(Product $product)
    {
        if (!$product->is_walkin_available || !$product->is_active) {
            abort(404);
        }

        $price = $product->walkin_price;
        $moq   = 1;

        return view('walkin.show', compact('product', 'price', 'moq'));
    }

    /**
     * Walk-in checkout — self-collection only, name+phone required.
     */
    public function checkout(Request $request)
    {
        $cartService = app(\App\Services\CartService::class);
        $items  = $cartService->getItems();
        $totals = $cartService->totals();

        if ($items->isEmpty()) {
            return redirect()->route('walkin.shop')->with('error', 'Your cart is empty.');
        }

        return view('walkin.checkout', compact('items', 'totals'));
    }

    /**
     * Admin: Generate walk-in QR code.
     */
    public function generateQr(Request $request)
    {
        $url = route('walkin.entry');

        if ($request->get('format') === 'svg') {
            $qr = QrCode::size(400)->errorCorrection('H')->generate($url);
            return response($qr)
                ->header('Content-Type', 'image/svg+xml')
                ->header('Content-Disposition', 'attachment; filename="mst-walkin-qr.svg"');
        }

        $qrCodeSvg = QrCode::size(280)->errorCorrection('H')->generate($url);

        return view('admin.walkin-qr', compact('url', 'qrCodeSvg'));
    }

    /**
     * Exit walk-in session.
     */
    public function exit()
    {
        Session::forget(['walkin_session', 'walkin_started_at']);
        return redirect()->route('home');
    }
}
