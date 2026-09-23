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
        $rules = [
            'name'        => 'required|string|max:200',
            'email'       => 'required|email|max:255',
            'phone'       => 'required|string|max:30',
            'interests'   => 'nullable|array',
            'interests.*' => 'string|max:100',
            'subject'     => 'nullable|string|max:200',
            'product'     => 'nullable|string|max:200',
            'budget'      => 'nullable|string|max:200',
            'message'     => 'required|string|max:5000',
        ];

        if (\App\Models\Setting::isRecaptchaEnabled('contact')) {
            $rules['g-recaptcha-response'] = [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    if (!\App\Models\Setting::verifyRecaptcha($value, $request->ip())) {
                        $fail(__t('auth.recaptcha_failed', 'reCAPTCHA verification failed. Please complete the captcha again.'));
                    }
                },
            ];
        }

        $request->validate($rules, [
            'g-recaptcha-response.required' => __t('auth.recaptcha_required', 'Please verify that you are not a robot.'),
        ]);

        $interestsList = !empty($request->interests) && is_array($request->interests)
            ? implode(', ', $request->interests)
            : null;

        $product = $request->product ?: $request->budget;

        $headerDetails = [];
        if ($interestsList) {
            $headerDetails[] = "Interests / Scope: " . $interestsList;
        }
        if (!empty($request->subject) && $request->subject !== 'General Inquiry') {
            $headerDetails[] = "Category: " . $request->subject;
        }
        if (!empty($product)) {
            $headerDetails[] = "Product / Requirement: " . $product;
        }

        $formattedHeader = !empty($headerDetails) ? "[" . implode(" | ", $headerDetails) . "]\n\n" : "";
        $messageBody = $formattedHeader . $request->message;

        $inquirySubject = $request->subject;
        if (empty($inquirySubject)) {
            $inquirySubject = $interestsList ? "Sourcing RFQ: " . $interestsList : 'Sourcing & Product Inquiry';
        }

        ContactMessage::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'subject'    => $inquirySubject,
            'message'    => $messageBody,
            'ip_address' => $request->ip(),
        ]);

        if (\App\Models\Setting::get('module_inquiry_emails', '1') === '1') {
            try {
                \App\Models\Setting::configureMailer();
                $adminEmails = \App\Models\Setting::getAdminNotificationEmails();
                if (!empty($adminEmails)) {
                    \Illuminate\Support\Facades\Mail::raw(
                        "New Website Sourcing Inquiry from {$request->name}\n\n" .
                        "Email: {$request->email}\n" .
                        "Phone / WhatsApp: " . ($request->phone ?? 'N/A') . "\n" .
                        "Interests: " . ($interestsList ?? 'General') . "\n" .
                        "Subject: " . $inquirySubject . "\n\n" .
                        "Requirements & Message:\n" . $messageBody . "\n\n" .
                        "View in Admin Console: " . route('admin.messages.index'),
                        function ($m) use ($adminEmails, $request, $inquirySubject) {
                            $m->to($adminEmails)
                              ->subject("New RFQ / Inquiry: {$inquirySubject} from {$request->name} — MST Import and Export Sdn Bhd");
                        }
                    );
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error("Contact inquiry email notification failed: " . $e->getMessage());
            }
        }

        return back()->with('success', 'Thank you! Your sourcing inquiry has been received. Our team will review your requirements and contact you within 24 hours.');
    }
}
