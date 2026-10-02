<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Services\CartService;
use App\Services\PricingService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(
        protected CartService   $cart,
        protected PricingService $pricing
    ) {}

    public function index()
    {
        $items  = $this->cart->getItems();
        $totals = $this->cart->totals();
        $group  = $this->pricing->resolveGroup();

        return view('cart.index', compact('items', 'totals', 'group'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'buy_now'    => 'nullable|boolean',
            'group'      => 'nullable|string',
        ]);

        $group = $request->input('group') ?: $this->pricing->resolveGroup();
        $result = $this->cart->add((int) $request->product_id, (int) $request->quantity, $group);

        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            $totals = $this->cart->totals($group);
            return response()->json(array_merge($result, [
                'count'           => $this->cart->count($group),
                'total'           => $totals['total'],
                'total_formatted' => number_format($totals['total'], 2),
                'redirect'        => $request->buy_now && $result['success'] ? route($group === 'walkin' ? 'walkin.checkout' : 'checkout.index') : null,
            ]));
        }

        if ($result['success']) {
            if ($request->buy_now) {
                return redirect()->route($group === 'walkin' ? 'walkin.checkout' : 'checkout.index');
            }
            return back()->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }

    public function update(Request $request, int $cartId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'group'    => 'nullable|string',
        ]);

        $cartItem = \App\Models\Cart::find($cartId);
        $targetGroup = $cartItem ? $cartItem->customer_group : ($request->input('group') ?: $this->pricing->resolveGroup());

        $result = $this->cart->update($cartId, (int) $request->quantity);

        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            $totals = $this->cart->totals($targetGroup);
            $items  = $this->cart->getItems($targetGroup);
            $item   = $items->firstWhere('id', $cartId);
            $itemSubtotal = $item ? $item->subtotal : 0;

            $currencyService = app(\App\Services\CurrencyService::class);
            $currentCurrency = $currencyService->getCurrentCurrency();
            $symbol = $currencyService->getSymbol($currentCurrency);

            $convertedSubtotal = 0;
            $convertedItemSubtotal = 0;

            foreach ($items as $cItem) {
                $pPrice = $cItem->product ? $cItem->product->getDisplayPrice($cItem->customer_group, $currentCurrency) : null;
                $uAmt = $pPrice && $pPrice['amount'] !== null
                    ? $pPrice['amount']
                    : $currencyService->convert($cItem->product?->getPriceForGroup($cItem->customer_group) ?? 0, $currentCurrency);
                $lineAmount = round($uAmt * $cItem->quantity, 2);
                $convertedSubtotal += $lineAmount;
                if ($cItem->id === $cartId) {
                    $convertedItemSubtotal = $lineAmount;
                }
            }

            if ($convertedItemSubtotal === 0 && $item) {
                $convertedItemSubtotal = $currencyService->convert($itemSubtotal, $currentCurrency);
            }
            if ($convertedSubtotal === 0 && $totals['subtotal'] > 0) {
                $convertedSubtotal = $currencyService->convert($totals['subtotal'], $currentCurrency);
            }
            $convertedTotal = $convertedSubtotal;

            return response()->json(array_merge($result, [
                'count'                             => $this->cart->count($targetGroup),
                'item_subtotal'                     => $itemSubtotal,
                'item_subtotal_formatted'           => number_format($itemSubtotal, 2),
                'currency'                          => $currentCurrency,
                'currency_symbol'                   => $symbol,
                'currency_item_subtotal_amount'     => $convertedItemSubtotal,
                'currency_item_subtotal_formatted'  => $symbol . ' ' . number_format($convertedItemSubtotal, 2),
                'subtotal'                          => $totals['subtotal'],
                'subtotal_formatted'                => number_format($totals['subtotal'], 2),
                'currency_subtotal_formatted'       => $symbol . ' ' . number_format($convertedSubtotal, 2),
                'total'                             => $totals['total'],
                'total_formatted'                   => number_format($totals['total'], 2),
                'currency_total_formatted'          => $symbol . ' ' . number_format($convertedTotal, 2),
            ]));
        }

        return back()->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function remove(Request $request, int $cartId)
    {
        $cartItem = \App\Models\Cart::find($cartId);
        $targetGroup = $cartItem ? $cartItem->customer_group : ($request->input('group') ?: $this->pricing->resolveGroup());

        $this->cart->remove($cartId);

        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            $totals = $this->cart->totals($targetGroup);
            $items  = $this->cart->getItems($targetGroup);
            $currencyService = app(\App\Services\CurrencyService::class);
            $currentCurrency = $currencyService->getCurrentCurrency();
            $symbol = $currencyService->getSymbol($currentCurrency);

            $convertedSubtotal = 0;
            foreach ($items as $cItem) {
                $pPrice = $cItem->product ? $cItem->product->getDisplayPrice($cItem->customer_group, $currentCurrency) : null;
                $uAmt = $pPrice && $pPrice['amount'] !== null
                    ? $pPrice['amount']
                    : $currencyService->convert($cItem->product?->getPriceForGroup($cItem->customer_group) ?? 0, $currentCurrency);
                $convertedSubtotal += round($uAmt * $cItem->quantity, 2);
            }
            if ($convertedSubtotal === 0 && $totals['subtotal'] > 0) {
                $convertedSubtotal = $currencyService->convert($totals['subtotal'], $currentCurrency);
            }
            $convertedTotal = $convertedSubtotal;

            return response()->json([
                'success'                     => true,
                'message'                     => 'Item removed from cart.',
                'count'                       => $this->cart->count($targetGroup),
                'currency'                    => $currentCurrency,
                'currency_symbol'             => $symbol,
                'subtotal'                    => $totals['subtotal'],
                'subtotal_formatted'          => number_format($totals['subtotal'], 2),
                'currency_subtotal_formatted' => $symbol . ' ' . number_format($convertedSubtotal, 2),
                'total'                       => $totals['total'],
                'total_formatted'             => number_format($totals['total'], 2),
                'currency_total_formatted'    => $symbol . ' ' . number_format($convertedTotal, 2),
                'is_empty'                    => $this->cart->count($targetGroup) === 0,
            ]);
        }

        return back()->with('success', 'Item removed from cart.');
    }

    public function count(Request $request)
    {
        $group = $request->input('group') ?: $this->pricing->resolveGroup();
        $totals = $this->cart->totals($group);
        return response()->json([
            'count'           => $this->cart->count($group),
            'total'           => $totals['total'],
            'total_formatted' => number_format($totals['total'], 2),
        ]);
    }
}
