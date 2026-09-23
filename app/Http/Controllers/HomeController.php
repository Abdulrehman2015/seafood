<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use App\Models\Category;
use App\Services\PricingService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct(protected PricingService $pricing) {}

    public function index()
    {
        $group = $this->pricing->resolveGroup();

        $featuredProducts = Product::featured()->inStock()->with('category')->limit(8)->get();
        
        // Show active root categories on homepage
        $categories = Category::active()->whereNull('parent_id')->orderBy('sort_order')->orderBy('name')->get();

        $newArrivals      = Product::active()->inStock()->latest()->limit(4)->get();
        $reviews          = Review::approved()->featured()->orderBy('sort_order')->limit(6)->get();

        return view('home', compact('featuredProducts', 'categories', 'newArrivals', 'reviews', 'group'));
    }

    public function approvalPending(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user()->fresh();

        // 1. If approved -> redirect straight to Dashboard
        if ($user->isApproved()) {
            return redirect()->route($user->isAdmin() ? 'admin.dashboard' : 'account.dashboard')
                ->with('success', 'Your account has been approved! Welcome to your dashboard.');
        }

        // 2. If rejected -> redirect to rejection page
        if ($user->isRejected()) {
            return redirect()->route('approval.rejected');
        }

        return view('auth.approval-pending', compact('user'));
    }

    public function approvalRejected(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user()->fresh();

        // 1. If approved -> redirect straight to Dashboard
        if ($user->isApproved()) {
            return redirect()->route($user->isAdmin() ? 'admin.dashboard' : 'account.dashboard')
                ->with('success', 'Your account has been approved! Welcome to your dashboard.');
        }

        // 2. If pending -> redirect to pending approval page
        if ($user->isPending()) {
            return redirect()->route('approval.pending');
        }

        return view('auth.approval-rejected', compact('user'));
    }

    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }
}
