<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Setting;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StaticController extends Controller
{
    public function about(): View
    {
        $reviews = Review::approved()->orderBy('sort_order')->limit(6)->get();
        return view('about', compact('reviews'));
    }

    public function contact(): View
    {
        $settings = Setting::allKeyed();
        $reviews  = Review::approved()->orderBy('sort_order')->limit(3)->get();
        $categories = Category::active()
            ->with(['products' => function($q) {
                $q->active()->select('id', 'category_id', 'name');
            }])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('contact', compact('settings', 'reviews', 'categories'));
    }

    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:200',
            'email'   => 'required|email|max:255',
            'phone'   => 'nullable|string|max:30',
            'subject' => 'nullable|string|max:200',
            'product' => 'nullable|string|max:200',
            'budget'  => 'nullable|string|max:200',
            'message' => 'required|string|max:5000',
        ]);

        $product = $request->product ?: $request->budget;
        $messageBody = $request->message;
        if (!empty($product)) {
            $messageBody = "[Selected Product: " . $product . "]\n\n" . $messageBody;
        }

        ContactMessage::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'subject'    => $request->subject ?? 'General Inquiry',
            'message'    => $messageBody,
            'ip_address' => $request->ip(),
        ]);

        if (\App\Models\Setting::get('module_inquiry_emails', '1') === '1') {
            try {
                \App\Models\Setting::configureMailer();
                $adminEmails = \App\Models\Setting::getAdminNotificationEmails();
                if (!empty($adminEmails)) {
                    \Illuminate\Support\Facades\Mail::raw(
                        "New Website Inquiry from {$request->name}\n\n" .
                        "Email: {$request->email}\n" .
                        "Phone: " . ($request->phone ?? 'N/A') . "\n" .
                        "Subject: " . ($request->subject ?? 'General Inquiry') . "\n\n" .
                        "Message:\n" . $messageBody . "\n\n" .
                        "View in Admin Console: " . route('admin.messages.index'),
                        function ($m) use ($adminEmails, $request) {
                            $m->to($adminEmails)
                              ->subject("New Inquiry: " . ($request->subject ?? 'General Inquiry') . " from {$request->name} — " . config('app.name', 'MST Import & Export'));
                        }
                    );
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error("Contact inquiry email notification failed: " . $e->getMessage());
            }
        }

        return back()->with('success', 'Thank you! Your message has been received. Our team will contact you within 24 hours.');
    }
}
