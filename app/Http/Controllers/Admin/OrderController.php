<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('group')) {
            $query->where('customer_group', $request->group);
        }
        if ($request->filled('payment')) {
            $query->where('payment_status', $request->payment);
        }
        if ($request->filled('fulfillment')) {
            $query->where('fulfillment_type', $request->fulfillment);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhere('collection_token', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_email', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_phone', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->get('sort') === 'oldest') {
            $query->oldest();
        } elseif ($request->get('sort') === 'total_high') {
            $query->orderBy('total', 'desc');
        } elseif ($request->get('sort') === 'total_low') {
            $query->orderBy('total', 'asc');
        } else {
            $query->latest();
        }

        $orders = $query->paginate(20)->withQueryString();

        $stats = [
            'total'        => Order::count(),
            'pending'      => Order::whereIn('status', ['pending', 'confirmed'])->count(),
            'processing'   => Order::whereIn('status', ['processing', 'ready', 'shipped'])->count(),
            'unpaid'       => Order::where('payment_status', 'unpaid')->count(),
            'paid_revenue' => Order::where('payment_status', 'paid')->sum('total'),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    public function show(Order $order)
    {
        $order->load('items.product', 'user');
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status'       => 'required|in:pending,confirmed,processing,ready,shipped,delivered,cancelled',
            'shipping_fee' => 'nullable|numeric|min:0',
        ]);

        $shippingFee = $request->shipping_fee ?? $order->shipping_fee;

        $order->update([
            'status'       => $request->status,
            'shipping_fee' => $shippingFee,
            'total'        => $order->subtotal + $shippingFee,
            'admin_notes'  => $request->admin_notes ?? $order->admin_notes,
        ]);

        return back()->with('success', 'Order updated.');
    }

    public function invoice(Order $order)
    {
        $order->load('items.product', 'user');
        return view('admin.orders.invoice', compact('order'));
    }
}
