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
            'status'         => 'required|in:pending,confirmed,processing,ready,shipped,delivered,cancelled,payment_pending,payment_confirmed,preparation,ready_collection,collected',
            'payment_status' => 'nullable|in:paid,unpaid,refunded,failed',
            'shipping_fee'   => 'nullable|numeric|min:0',
            'admin_notes'    => 'nullable|string|max:1000',
        ]);

        $shippingFee = $request->has('shipping_fee') ? (float) $request->shipping_fee : (float) $order->shipping_fee;

        $data = [
            'status'       => $request->status,
            'shipping_fee' => $shippingFee,
            'total'        => $order->subtotal + $shippingFee,
        ];

        if ($request->has('admin_notes')) {
            $data['admin_notes'] = $request->admin_notes;
        }

        if ($request->filled('payment_status')) {
            $data['payment_status'] = $request->payment_status;
            if ($request->payment_status === 'paid' && !$order->paid_at) {
                $data['paid_at'] = now();
            }
        }

        $order->update($data);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success'       => true,
                'message'       => 'Order status updated to ' . ucfirst($order->status) . '.',
                'status'        => $order->status,
                'status_badge'  => $order->status_badge,
                'payment_status'=> $order->payment_status,
                'payment_badge' => $order->payment_badge,
            ]);
        }

        return back()->with('success', 'Order status updated successfully.');
    }

    public function invoice(Order $order)
    {
        $order->load('items.product', 'user');
        return view('admin.orders.invoice', compact('order'));
    }
}
