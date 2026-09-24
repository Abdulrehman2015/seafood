<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        if (request('type') === 'trading') {
            return view('auth.register-trading');
        }
        return view('auth.register');
    }

    public function createTrading(): View
    {
        return view('auth.register-trading');
    }

    public function store(Request $request, \App\Services\CompanyVerificationService $verifier): RedirectResponse
    {
        $validationRules = [
            'name'                   => ['required', 'string', 'max:255'],
            'email'                  => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password'               => ['required', 'confirmed', Rules\Password::defaults()],
            'customer_group'         => ['required', 'in:retail,wholesale,trading'],
            'phone'                  => ['required', 'string', 'max:20'],
            'company_name'           => ['required_if:customer_group,wholesale', 'required_if:customer_group,trading', 'nullable', 'string', 'max:255'],
            'company_reg_no'         => ['nullable', 'string', 'max:100'],
            'business_type'          => ['required_if:customer_group,wholesale', 'required_if:customer_group,trading', 'nullable', 'string', 'max:255'],
            'position_role'          => ['nullable', 'string', 'max:255'],
            'contact_person'         => ['nullable', 'string', 'max:255'],
            'business_location'      => ['nullable', 'string', 'max:255'],
            'country_market'         => ['required_if:customer_group,trading', 'nullable', 'string', 'max:255'],
            'destination_country'    => ['required_if:customer_group,trading', 'nullable', 'string', 'max:255'],
            'destination_market'     => ['nullable', 'string', 'max:255'],
            'product_interest'       => ['required_if:customer_group,trading', 'nullable'],
            'estimated_order_volume' => ['nullable', 'string', 'max:255'],
            'import_requirements'    => ['nullable', 'string', 'max:2000'],
            'additional_message'     => ['nullable', 'string', 'max:2000'],
            'supply_arrangement'     => ['nullable', 'string', 'max:255'],
            'existing_mst_customer'  => ['nullable', 'string', 'max:50'],
            'existing_customer_ref'  => ['nullable', 'string', 'max:255'],
            'preferred_fulfilment'   => ['nullable', 'string', 'max:50'],
            'address'                => ['nullable', 'string', 'max:500'],
            'city'                   => ['nullable', 'string', 'max:100'],
            'state'                  => ['nullable', 'string', 'max:100'],
            'postcode'               => ['nullable', 'string', 'max:10'],
            'terms_consent'          => ['required', 'accepted'],
        ];

        if (\App\Models\Setting::isRecaptchaEnabled('register')) {
            $validationRules['g-recaptcha-response'] = [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    if (!\App\Models\Setting::verifyRecaptcha($value, $request->ip())) {
                        $fail(__t('auth.recaptcha_failed', 'reCAPTCHA verification failed. Please complete the captcha again.'));
                    }
                },
            ];
        }

        $request->validate($validationRules, [
            'terms_consent.accepted'            => __t('auth.terms_required', 'You must agree to the Terms of Service and Privacy Policy to register.'),
            'email.unique'                      => __t('auth.email_already_registered', 'An account with this email already exists. Please Sign In or reset your password.'),
            'company_name.required_if'          => __t('auth.company_name_required', 'Company Name is required for business and trading accounts.'),
            'business_type.required_if'         => __t('auth.business_type_required', 'Business Nature / Type is required.'),
            'country_market.required_if'        => __t('auth.country_market_required', 'Country / Market is required for trading accounts.'),
            'destination_country.required_if'   => __t('auth.destination_country_required', 'Destination / Delivery Country is required for trading accounts.'),
            'product_interest.required_if'      => __t('auth.product_interest_required', 'Please select at least one product or category of interest.'),
            'g-recaptcha-response.required'     => __t('auth.recaptcha_required', 'Please verify that you are not a robot.'),
        ]);

        // 1. Strict SSM Uniqueness Check for business accounts if provided
        if (in_array($request->customer_group, ['wholesale', 'trading']) && $request->filled('company_reg_no')) {
            $ssmCheck = $verifier->checkSsmUniqueness($request->company_reg_no);
            if ($ssmCheck['is_duplicate']) {
                return back()->withInput()->withErrors([
                    'company_reg_no' => $ssmCheck['message'] ?? 'An account with this Company Registration Number (' . e($request->company_reg_no) . ') is already registered. Please check if your company already has an account or contact MST.'
                ]);
            }
        }

        // 2. Company Name Similarity Check (Non-blocking warning alert)
        if (in_array($request->customer_group, ['wholesale', 'trading']) && $request->filled('company_name')) {
            $similarityCheck = $verifier->checkSimilarity($request->company_name);
            if ($similarityCheck['has_similarity']) {
                session()->flash('company_similarity_warning', $similarityCheck['message']);
            }
        }

        // Retail customers are auto-approved; wholesale/trading need verification/review
        $approvalStatus = match ($request->customer_group) {
            'wholesale', 'trading' => 'pending',
            default                => 'approved',
        };

        $marketingOptIn = $request->boolean('marketing_opt_in');

        // Normalize product_interest (handle both array & string)
        $productInterest = $request->input('product_interest');
        if (is_array($productInterest)) {
            $productInterest = implode(', ', array_filter($productInterest));
        }

        $user = User::create([
            'name'                   => $request->name,
            'email'                  => $request->email,
            'preferred_locale'       => current_locale(),
            'password'               => Hash::make($request->password),
            'customer_group'         => $request->customer_group,
            'approval_status'        => $approvalStatus,
            'commercial_access'      => 'Not Assigned',
            'phone'                  => $request->phone,
            'company_name'           => $request->company_name,
            'company_reg_no'         => $request->company_reg_no,
            'business_type'          => $request->business_type,
            'position_role'          => $request->position_role,
            'contact_person'         => $request->contact_person ?? $request->name,
            'business_location'      => $request->business_location ?? $request->destination_country,
            'country_market'         => $request->country_market ?? $request->destination_market,
            'destination_country'    => $request->destination_country ?? $request->destination_market,
            'destination_market'     => $request->destination_market ?? $request->country_market,
            'estimated_order_volume' => $request->estimated_order_volume,
            'product_interest'       => $productInterest,
            'import_requirements'    => $request->import_requirements,
            'additional_message'     => $request->additional_message,
            'supply_arrangement'     => $request->supply_arrangement,
            'existing_mst_customer'  => $request->existing_mst_customer ?? 'no',
            'existing_customer_ref'  => $request->existing_customer_ref,
            'preferred_fulfilment'   => $request->preferred_fulfilment ?? ($request->supply_arrangement ?? 'walkin'),
            'address'                => $request->address,
            'city'                   => $request->city,
            'state'                  => $request->state,
            'postcode'               => $request->postcode,
            'marketing_opt_in'       => $marketingOptIn,
            'marketing_channels'     => $marketingOptIn ? 'email,whatsapp' : null,
        ]);

        if ($marketingOptIn) {
            \App\Models\NewsletterSubscriber::updateOrCreate(
                ['email' => $user->email],
                ['status' => 'active', 'ip_address' => $request->ip()]
            );
        }

        event(new Registered($user));

        // Generate and dispatch 6-digit Email OTP
        $otp = $user->generateEmailOtp();
        \App\Models\Setting::configureMailer();

        try {
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\SendEmailOtp($user, $otp, current_locale()));
            \Illuminate\Support\Facades\Log::info("Registration OTP sent to user: {$user->email} in locale: " . current_locale());
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Registration OTP dispatch error for {$user->email}: " . $e->getMessage());
        }

        // Store user identifier and email in session for OTP verification
        session([
            'otp_verify_user_id' => $user->id,
            'otp_verify_email'   => $user->email,
        ]);

        return redirect()->route('otp.verify')->with('status', __t('auth.otp_register_sent_notice', 'We have sent a 6-digit verification code to :email. Please enter it below to complete your registration.', ['email' => $user->email]));
    }
}
