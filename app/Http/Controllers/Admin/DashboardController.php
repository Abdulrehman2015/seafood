<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\Quotation;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_orders'      => Order::count(),
            'pending_orders'    => Order::where('status', 'pending')->count(),
            'total_revenue'     => Order::where('payment_status', 'paid')->sum('total'),
            'pending_approvals' => User::whereIn('customer_group', ['wholesale', 'trading'])
                                       ->where('approval_status', 'pending')->count(),
            'low_stock'         => Product::where('track_stock', true)->where('stock_quantity', '<=', 5)->count(),
            'pending_rfq'       => Quotation::where('status', 'pending')->count(),
            'total_customers'   => User::where('customer_group', '!=', 'admin')->count(),
        ];

        $recentOrders = Order::with('user')
            ->latest()
            ->limit(8)
            ->get();

        $pendingApprovals = User::whereIn('customer_group', ['wholesale', 'trading'])
            ->where('approval_status', 'pending')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'pendingApprovals'));
    }
}
