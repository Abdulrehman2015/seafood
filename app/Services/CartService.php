<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected PricingService $pricing;

    public function __construct(PricingService $pricing)
    {
        $this->pricing = $pricing;
    }

    protected function getSessionId(): string
    {
        return Session::getId();
    }

    protected function getGroup(): string
    {
        return $this->pricing->resolveGroup();
    }

    /**
     * Get all cart items for the current user/session.
     */
    public function getItems()
    {
        $query = Cart::with('product.category');

        if (Auth::check()) {
            return $query->where('user_id', Auth::id())->get();
        }

        return $query->where('session_id', $this->getSessionId())->get();
    }

    /**
     * Add a product to the cart.
     */
    public function add(int $productId, int $quantity = 1): array
    {
        $product = Product::active()->findOrFail($productId);
        $group   = $this->getGroup();
        $moq     = $product->getMoqForGroup($group);

        if ($quantity < $moq) {
            return ['success' => false, 'message' => "Minimum order quantity is {$moq} {$product->unit}."];
        }

        if ($product->track_stock && $product->stock_quantity < $quantity) {
            return ['success' => false, 'message' => 'Insufficient stock available.'];
        }

        $cartData = [
            'product_id'     => $productId,
            'customer_group' => $group,
        ];

        if (Auth::check()) {
            $cartData['user_id'] = Auth::id();
            $existing = Cart::where('user_id', Auth::id())->where('product_id', $productId)->first();
        } else {
            $cartData['session_id'] = $this->getSessionId();
            $existing = Cart::where('session_id', $this->getSessionId())->where('product_id', $productId)->first();
        }

        if ($existing) {
            $newQty = $existing->quantity + $quantity;
            if ($product->track_stock && $product->stock_quantity < $newQty) {
                return ['success' => false, 'message' => 'Not enough stock for the requested quantity.'];
            }
            $existing->update(['quantity' => $newQty]);
        } else {
            Cart::create(array_merge($cartData, ['quantity' => $quantity]));
        }

        return ['success' => true, 'message' => 'Product added to cart.', 'count' => $this->count()];
    }

    /**
     * Update cart item quantity.
     */
    public function update(int $cartId, int $quantity): array
    {
        $item = $this->findItem($cartId);
        if (!$item || !$item->product) {
            return ['success' => false, 'message' => 'Cart item not found.'];
        }

        $product = $item->product;
        $moq     = $product->getMoqForGroup($item->customer_group);

        if ($quantity < $moq) {
            return ['success' => false, 'message' => "Minimum order quantity is {$moq}."];
        }

        if ($product->track_stock && $product->stock_quantity < $quantity) {
            return ['success' => false, 'message' => 'Insufficient stock.'];
        }

        $item->update(['quantity' => $quantity]);
        return ['success' => true, 'message' => 'Cart updated.'];
    }

    /**
     * Remove a cart item.
     */
    public function remove(int $cartId): void
    {
        $this->findItem($cartId)?->delete();
    }

    /**
     * Clear the entire cart.
     */
    public function clear(): void
    {
        if (Auth::check()) {
            Cart::where('user_id', Auth::id())->delete();
        } else {
            Cart::where('session_id', $this->getSessionId())->delete();
        }
    }

    /**
     * Get the cart item count.
     */
    public function count(): int
    {
        if (Auth::check()) {
            return Cart::where('user_id', Auth::id())->sum('quantity');
        }
        return Cart::where('session_id', $this->getSessionId())->sum('quantity');
    }

    /**
     * Get cart totals.
     */
    public function totals(): array
    {
        $items    = $this->getItems();
        $subtotal = $items->sum(fn($item) => $item->subtotal);

        return [
            'subtotal' => $subtotal,
            'total'    => $subtotal,
            'count'    => $items->sum('quantity'),
        ];
    }

    protected function findItem(int $cartId): ?Cart
    {
        $query = Cart::where('id', $cartId);
        if (Auth::check()) {
            return $query->where('user_id', Auth::id())->first();
        }
        return $query->where('session_id', $this->getSessionId())->first();
    }

    /**
     * Migrate session cart to user cart after login.
     */
    public function migrateToUser(int $userId): void
    {
        Cart::where('session_id', $this->getSessionId())
            ->update(['user_id' => $userId, 'session_id' => null]);
    }
}
