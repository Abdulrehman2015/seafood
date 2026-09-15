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
        
        // Show featured categories on homepage; fallback to top-level active categories if none marked featured
        $featuredCategories = Category::active()->featured()->orderBy('sort_order')->get();
        $categories         = $featuredCategories->isNotEmpty()
            ? $featuredCategories
            : Category::active()->whereNull('parent_id')->orderBy('sort_order')->limit(8)->get();

        $newArrivals      = Product::active()->inStock()->latest()->limit(4)->get();
        $reviews          = Review::approved()->featured()->orderBy('sort_order')->limit(6)->get();

        return view('home', compact('featuredProducts', 'categories', 'newArrivals', 'reviews', 'group'));
    }

    public function approvalPending(Request $request)
    {
        // 1. Non-logged-in users -> redirect to homepage
        if (!auth()->check()) {
            return redirect()->route('home');
        }

        $user = auth()->user()->fresh();

        // 2. Once an admin approves their account, when user refreshes, redirect to Dashboard
        if ($user->isApproved()) {
            if (session('was_on_pending_approval') || str_contains($request->headers->get('referer', ''), 'pending-approval')) {
                session()->forget('was_on_pending_approval');
                return redirect()->route($user->isAdmin() ? 'admin.dashboard' : 'account.dashboard')
                    ->with('success', 'Your account has been approved! Welcome to your dashboard.');
            }

            // Users with approved accounts accessing /pending-approval by default redirect to homepage
            return redirect()->route('home');
        }

        // 3. If rejected -> redirect to rejection page
        if ($user->isRejected()) {
            return redirect()->route('approval.rejected');
        }

        // 4. Any other non-pending status -> redirect to homepage
        if (!$user->isPending()) {
            return redirect()->route('home');
        }

        // 5. Logged-in user with Pending Approval account -> allowed to view pending approval page
        session(['was_on_pending_approval' => true]);

        return view('auth.approval-pending', compact('user'));
    }

    public function approvalRejected()
    {
        return view('auth.approval-rejected');
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
