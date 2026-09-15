<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    /**
     * Handle a newsletter subscription request.
     */
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'max:255'],
        ], [
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first('email'),
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $email = strtolower(trim($request->input('email')));
        $subscriber = NewsletterSubscriber::where('email', $email)->first();

        if ($subscriber) {
            if ($subscriber->status === 'unsubscribed') {
                $subscriber->update([
                    'status' => 'active',
                    'ip_address' => $request->ip(),
                ]);

                $message = 'Welcome back! Your subscription to MST IMPORT AND EXPORT SDN BHD updates has been reactivated.';
            } else {
                $message = 'You are already subscribed to MST IMPORT AND EXPORT SDN BHD catch alerts & updates!';
            }
        } else {
            NewsletterSubscriber::create([
                'email' => $email,
                'status' => 'active',
                'ip_address' => $request->ip(),
            ]);

            $message = 'Thank you for subscribing! You are now on the MST IMPORT AND EXPORT SDN BHD catch updates list.';
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }
}
