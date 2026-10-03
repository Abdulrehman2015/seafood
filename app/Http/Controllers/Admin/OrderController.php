<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

use App\Mail\OrderScheduleNotification;
use Illuminate\Support\Facades\Mail;

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
            'status'            => 'required|in:pending,confirmed,processing,ready,shipped,delivered,cancelled,payment_pending,payment_confirmed,preparation,ready_collection,collected',
            'payment_status'    => 'nullable|in:paid,unpaid,refunded,failed',
            'shipping_fee'      => 'nullable|numeric|min:0',
            'admin_notes'       => 'nullable|string|max:1000',
            'confirmed_date'    => 'nullable|string|max:50',
            'confirmed_time'    => 'nullable|string|max:50',
            'collection_date'   => 'nullable|string|max:50',
            'collection_time'   => 'nullable|string|max:50',
            'delivery_date'     => 'nullable|string|max:50',
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

        if ($request->filled('confirmed_date')) {
            $data['confirmed_date'] = $request->confirmed_date;
        }
        if ($request->filled('confirmed_time')) {
            $data['confirmed_time'] = $request->confirmed_time;
        }
        if ($request->filled('collection_date')) {
            $data['collection_date'] = $request->collection_date;
        }
        if ($request->filled('collection_time')) {
            $data['collection_time'] = $request->collection_time;
        }
        if ($request->filled('delivery_date')) {
            $data['delivery_date'] = $request->delivery_date;
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

    public function notifySchedule(Request $request, Order $order)
    {
        $request->validate([
            'confirmed_date'     => 'required|string|max:50',
            'confirmed_time'     => 'nullable|string|max:50',
            'notification_notes' => 'nullable|string|max:1000',
            'target_status'      => 'nullable|in:pending,confirmed,processing,ready,shipped,delivered,cancelled,payment_pending,payment_confirmed,preparation,ready_collection,collected',
            'send_email'         => 'nullable',
        ]);

        $date = trim($request->confirmed_date);
        $time = trim($request->confirmed_time ?? '');
        $notes = $request->notification_notes;

        $updateData = [
            'confirmed_date'     => $date,
            'confirmed_time'     => $time ?: null,
            'notification_notes' => $notes ?: null,
            'notified_at'        => now(),
        ];

        // Also sync primary date fields
        if ($order->isWalkin()) {
            $updateData['collection_date'] = $date;
            if ($time) {
                $updateData['collection_time'] = $time;
            }
        } else {
            $updateData['delivery_date'] = $date;
        }

        if ($request->filled('target_status')) {
            $updateData['status'] = $request->target_status;
        }

        $order->update($updateData);

        $emailSent = false;
        $emailError = null;

        if ($request->has('send_email') && $request->send_email != '0' && !empty($order->customer_email)) {
            try {
                Mail::to($order->customer_email)->send(new OrderScheduleNotification($order, $notes));
                $emailSent = true;
            } catch (\Throwable $e) {
                $emailError = $e->getMessage();
            }
        }

        $actionType = $order->isWalkin() ? 'collection' : 'delivery';
        $msg = "Customer {$actionType} schedule set to {$date}" . ($time ? " ({$time})" : "") . " and recorded successfully.";
        
        if ($emailSent) {
            $msg .= " An email notification has been dispatched to {$order->customer_email}.";
        } elseif ($emailError) {
            $msg .= " Note: Email could not be sent ({$emailError}).";
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success'        => true,
                'message'        => $msg,
                'email_sent'     => $emailSent,
                'confirmed_date' => $order->confirmed_date,
                'confirmed_time' => $order->confirmed_time,
                'notified_at'    => $order->notified_at?->format('d M Y, h:i A'),
            ]);
        }

        return back()->with('success', $msg);
    }

    public function invoice(Order $order)
    {
        $order->load('items.product', 'user');
        return view('admin.orders.invoice', compact('order'));
    }
}
