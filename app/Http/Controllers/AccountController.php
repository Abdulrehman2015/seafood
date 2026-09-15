<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function dashboard()
    {
        $user   = Auth::user();
        $orders = Order::where('user_id', $user->id)->latest()->limit(5)->get();

        return view('account.dashboard', compact('user', 'orders'));
    }

    public function orders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('items')
            ->latest()
            ->paginate(10);

        return view('account.orders', compact('orders'));
    }

    public function orderShow(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('items.product');

        return view('account.order-show', compact('order'));
    }

    public function invoice(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('items.product', 'user');

        return view('admin.orders.invoice', compact('order'));
    }

    public function reorder(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $cartService = app(\App\Services\CartService::class);
        $cartService->clear();

        foreach ($order->items as $item) {
            if ($item->product && $item->product->is_active) {
                $cartService->add($item->product_id, $item->quantity);
            }
        }

        return redirect()->route('cart.index')->with('success', 'Items from your previous order have been added to your cart.');
    }

    public function profile()
    {
        return view('account.profile', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'     => 'required|string|max:255',
            'phone'    => 'required|string|max:20',
            'address'  => 'required|string|max:500',
            'city'     => 'required|string|max:100',
            'state'    => 'required|string|max:100',
            'postcode' => 'required|string|max:10',
        ]);

        $user->update($request->only('name', 'phone', 'address', 'city', 'state', 'postcode'));

        return back()->with('success', 'Profile updated successfully.');
    }
}
