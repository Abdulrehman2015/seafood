<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuotationController extends Controller
{
    public function index()
    {
        $quotations = Quotation::where('user_id', Auth::id())
            ->with('items.product')
            ->latest()
            ->paginate(10);

        return view('account.quotations', compact('quotations'));
    }

    public function create(Request $request)
    {
        $categories = \App\Models\Category::where('is_active', true)
            ->whereHas('products', function ($q) {
                $q->active();
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $products = Product::active()
            ->with('category')
            ->orderBy('name')
            ->get();

        $selectedProduct = null;
        if ($request->filled('product')) {
            $selectedProduct = Product::with('category')->find($request->product);
        }

        return view('quotation.create', compact('products', 'categories', 'selectedProduct'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
            'customer_notes'     => 'nullable|string|max:1000',
        ]);

        $quotation = Quotation::create([
            'user_id'        => Auth::id(),
            'customer_notes' => $request->customer_notes,
        ]);

        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            QuotationItem::create([
                'quotation_id'       => $quotation->id,
                'product_id'         => $product->id,
                'product_name'       => $product->name,
                'quantity_requested' => $item['quantity'],
                'notes'              => $item['notes'] ?? null,
            ]);
        }

        return redirect()->route('quotations.show', $quotation)
            ->with('success', 'Your quotation request has been submitted. We will respond within 1-2 business days.');
    }

    public function show(Quotation $quotation)
    {
        if ($quotation->user_id !== Auth::id()) {
            abort(403);
        }
        $quotation->load('items.product');

        return view('quotation.show', compact('quotation'));
    }

    public function accept(Quotation $quotation)
    {
        if ($quotation->user_id !== Auth::id()) {
            abort(403);
        }

        if ($quotation->status !== 'quoted') {
            return back()->with('error', 'This quotation cannot be accepted at this stage.');
        }

        $quotation->update(['status' => 'accepted']);

        return redirect()->route('checkout.fromQuotation', $quotation)
            ->with('success', 'Quotation accepted! Please proceed to checkout.');
    }

    public function reject(Quotation $quotation)
    {
        if ($quotation->user_id !== Auth::id()) {
            abort(403);
        }

        $quotation->update(['status' => 'rejected']);

        return redirect()->route('quotations.index')
            ->with('info', 'Quotation rejected.');
    }
}
