<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use App\Services\PricingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Stripe\PaymentIntent;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService    $cart,
        protected PricingService $pricing
    ) {}

    public function index()
    {
        $items  = $this->cart->getItems();
        $totals = $this->cart->totals();
        $group  = $this->pricing->resolveGroup();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Block unapproved wholesale/trading from checkout
        if (Auth::check() && Auth::user()->needsApproval()) {
            return redirect()->route('approval.pending');
        }

        return view('checkout.index', compact('items', 'totals', 'group'));
    }

    /**
     * Load accepted quotation items into checkout.
     */
    public function fromQuotation(\App\Models\Quotation $quotation)
    {
        if ($quotation->user_id !== Auth::id()) {
            abort(403);
        }

        if ($quotation->status !== 'accepted') {
            return redirect()->route('quotations.index')->with('error', 'Quotation is not accepted yet.');
        }

        if ($quotation->valid_until && $quotation->valid_until->isPast()) {
            return redirect()->route('quotations.index')->with('error', 'This quotation has expired.');
        }

        $this->cart->clear();

        foreach ($quotation->items as $item) {
            \App\Models\Cart::create([
                'user_id'        => Auth::id(),
                'product_id'     => $item->product_id,
                'quantity'       => $item->quantity_requested,
                'customer_group' => 'trading',
            ]);
        }

        return redirect()->route('checkout.index')->with('success', 'Quotation items ready for checkout.');
    }

    /**
     * Create Stripe PaymentIntent and return client secret (legacy helper).
     */
    public function createPaymentIntent(Request $request)
    {
        \App\Models\Setting::configureStripe();

        $stripeSecret = \App\Models\Setting::getStripeSecretKey();
        $currency     = strtolower(\App\Models\Setting::get('stripe_currency', 'myr'));
        $mode         = \App\Models\Setting::get('stripe_mode', 'test');

        $isPlaceholder = empty($stripeSecret) || str_contains($stripeSecret, 'YOUR_TEST_SECRET') || str_contains($stripeSecret, 'YOUR_SECRET');

        if ($isPlaceholder) {
            return response()->json([
                'clientSecret' => 'pi_test_' . bin2hex(random_bytes(10)) . '_secret_' . bin2hex(random_bytes(10)),
                'isMock'       => true,
                'mode'         => $mode,
            ]);
        }

        Stripe::setApiKey($stripeSecret);

        $totals = $this->cart->totals();
        $amount = (int) round($totals['total'] * 100);

        try {
            $intent = PaymentIntent::create([
                'amount'   => $amount,
                'currency' => $currency,
                'metadata' => [
                    'customer_group' => $this->pricing->resolveGroup(),
                    'user_id'        => Auth::id() ?? 'guest',
                    'environment'    => $mode,
                ],
            ]);

            return response()->json(['clientSecret' => $intent->client_secret, 'mode' => $mode]);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Stripe Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Initiate Stripe Official Hosted Checkout Session (redirects directly to checkout.stripe.com).
     */
    public function store(Request $request)
    {
        \App\Models\Setting::configureStripe();

        $group    = $this->pricing->resolveGroup();
        $isWalkin = $group === 'walkin';

        $rules = [
            'fulfillment_type' => 'required|in:delivery,self_collection',
            'customer_notes'   => 'nullable|string|max:1000',
        ];

        if ($isWalkin) {
            $rules['customer_name']  = 'required|string|max:255';
            $rules['customer_phone'] = 'required|string|max:20';
        }

        if ($request->fulfillment_type === 'delivery' && !$isWalkin) {
            $rules['address']  = 'required|string';
            $rules['city']     = 'required|string';
            $rules['state']    = 'required|string';
            $rules['postcode'] = 'required|string';
        }

        $request->validate($rules);

        $cartItems = $this->cart->getItems();

        if ($cartItems->isEmpty()) {
            return redirect()->route('shop.index')->with('error', 'Cart is empty.');
        }

        foreach ($cartItems as $item) {
            if (!$item->product || !$item->product->is_active) {
                return redirect()->route('cart.index')->with('error', 'One or more items in your cart are no longer available.');
            }
        }

        $stripeSecret = \App\Models\Setting::getStripeSecretKey();
        $currency     = strtolower(\App\Models\Setting::get('stripe_currency', 'myr'));

        // Check if API secret key is placeholder or empty
        $isPlaceholder = empty($stripeSecret) || str_contains($stripeSecret, 'YOUR_TEST_SECRET') || str_contains($stripeSecret, 'YOUR_SECRET');

        if ($isPlaceholder) {
            return back()->with('error', '⚠️ Stripe Secret Key is not configured yet. Please paste your Stripe Secret Key (sk_test_... or sk_live_...) in Admin Panel → Store Settings → Payment Integrations to redirect to Stripe\'s Official Hosted Checkout page.');
        }

        $payload = [
            'fulfillment_type' => $request->fulfillment_type,
            'customer_name'    => $request->customer_name ?? Auth::user()?->name ?? 'Customer',
            'customer_email'   => $request->customer_email ?? Auth::user()?->email,
            'customer_phone'   => $request->customer_phone ?? Auth::user()?->phone,
            'customer_notes'   => $request->customer_notes,
            'address'          => $request->address,
            'city'             => $request->city,
            'state'            => $request->state,
            'postcode'         => $request->postcode,
            'group'            => $group,
            'user_id'          => Auth::id(),
        ];

        // Set Stripe Secret Key
        Stripe::setApiKey($stripeSecret);

        $lineItems = [];
        foreach ($cartItems as $item) {
            $price = $item->product->getPriceForGroup($group);
            $lineItems[] = [
                'price_data' => [
                    'currency'     => $currency,
                    'product_data' => [
                        'name'        => $item->product->name,
                        'description' => 'SKU: ' . ($item->product->sku ?? 'N/A'),
                    ],
                    'unit_amount'  => (int) round($price * 100),
                ],
                'quantity'   => $item->quantity,
            ];
        }

        session(['stripe_checkout_payload' => $payload]);

        try {
            $session = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items'           => $lineItems,
                'mode'                 => 'payment',
                'success_url'          => route('checkout.stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'           => route('checkout.stripe.cancel'),
                'customer_email'       => $payload['customer_email'] ?: null,
                'metadata'             => [
                    'user_id'          => Auth::id() ?? 'guest',
                    'fulfillment_type' => $request->fulfillment_type,
                    'customer_group'   => $group,
                ],
            ]);

            // Redirect user to Stripe's official hosted checkout page URL (checkout.stripe.com)
            return redirect()->away($session->url);
        } catch (\Throwable $e) {
            return back()->with('error', 'Stripe Hosted Checkout Error: ' . $e->getMessage());
        }
    }

    /**
     * Handle return from Stripe Official Hosted Checkout Page upon payment completion.
     */
    public function stripeSuccess(Request $request)
    {
        $sessionId = $request->query('session_id');

        if (!$sessionId) {
            return redirect()->route('checkout.index')->with('error', 'Invalid Stripe Session reference.');
        }

        \App\Models\Setting::configureStripe();
        $stripeSecret = \App\Models\Setting::getStripeSecretKey();

        Stripe::setApiKey($stripeSecret);

        try {
            $stripeSession = StripeSession::retrieve($sessionId);

            if ($stripeSession->payment_status !== 'paid') {
                return redirect()->route('checkout.index')->with('error', 'Payment was not completed. Please try again.');
            }

            $paymentRef = $stripeSession->payment_intent ?? $stripeSession->id;

            // Prevent duplicate orders
            if (Order::where('stripe_payment_intent', $paymentRef)->exists()) {
                $existingOrder = Order::where('stripe_payment_intent', $paymentRef)->first();
                session(['last_placed_order_id' => $existingOrder->id]);
                return redirect()->route('checkout.success', $existingOrder);
            }

            $payload   = session('stripe_checkout_payload', []);
            $cartItems = $this->cart->getItems();

            if ($cartItems->isEmpty()) {
                return redirect()->route('shop.index')->with('error', 'Cart is empty.');
            }

            return $this->processOrderDirectly($payload, $cartItems, 'stripe', $paymentRef);
        } catch (\Throwable $e) {
            return redirect()->route('checkout.index')->with('error', 'Stripe Verification Error: ' . $e->getMessage());
        }
    }

    /**
     * Handle cancellation from Stripe Hosted Checkout Page.
     */
    public function stripeCancel()
    {
        return redirect()->route('checkout.index')->with('info', 'Checkout process was canceled on Stripe.');
    }

    /**
     * Internal helper to create Order database record & send notifications.
     */
    protected function processOrderDirectly(array $payload, $cartItems, string $paymentMethod, string $paymentRef)
    {
        $totals = $this->cart->totals();
        $group  = $payload['group'] ?? $this->pricing->resolveGroup();
        $order  = null;

        DB::transaction(function () use ($payload, $cartItems, $group, $totals, $paymentMethod, $paymentRef, &$order) {
            $user = Auth::user();

            $shippingAddress = null;
            if (($payload['fulfillment_type'] ?? 'delivery') === 'delivery') {
                $shippingAddress = [
                    'address'  => $payload['address']  ?? $user?->address,
                    'city'     => $payload['city']     ?? $user?->city,
                    'state'    => $payload['state']    ?? $user?->state,
                    'postcode' => $payload['postcode'] ?? $user?->postcode,
                ];
            }

            $order = Order::create([
                'user_id'               => $user?->id ?? ($payload['user_id'] ?? null),
                'customer_group'        => $group,
                'customer_name'         => $payload['customer_name'] ?? $user?->name ?? 'Customer',
                'customer_email'        => $payload['customer_email'] ?? $user?->email,
                'customer_phone'        => $payload['customer_phone'] ?? $user?->phone,
                'status'                => 'confirmed',
                'payment_status'        => 'paid',
                'payment_method'        => $paymentMethod,
                'payment_reference'     => $paymentRef,
                'paid_at'               => now(),
                'fulfillment_type'      => $payload['fulfillment_type'] ?? 'delivery',
                'shipping_address'      => $shippingAddress,
                'subtotal'              => $totals['subtotal'],
                'total'                 => $totals['total'],
                'stripe_payment_intent' => $paymentRef,
                'customer_notes'        => $payload['customer_notes'] ?? null,
            ]);

            foreach ($cartItems as $item) {
                $price = $item->product->getPriceForGroup($group);
                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $item->product_id,
                    'product_name' => $item->product->name,
                    'product_sku'  => $item->product->sku,
                    'quantity'     => $item->quantity,
                    'unit_price'   => $price,
                    'subtotal'     => $price * $item->quantity,
                    'price_group'  => $group,
                ]);

                if ($item->product->track_stock) {
                    $item->product->decrement('stock_quantity', $item->quantity);
                }
            }

            $this->cart->clear();
            session()->forget('stripe_checkout_payload');
        });

        session(['last_placed_order_id' => $order->id]);

        // Dispatch confirmation emails
        \App\Models\Setting::configureMailer();

        // 1. Dispatch Customer Order Confirmation
        try {
            if (!empty($order->customer_email)) {
                \Illuminate\Support\Facades\Mail::to($order->customer_email)->send(new \App\Mail\OrderConfirmation($order->load('items')));
                \Illuminate\Support\Facades\Log::info("Order confirmation email sent to {$order->customer_email} for #{$order->order_number}");
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Failed to send order confirmation to {$order->customer_email}: " . $e->getMessage());
        }

        // 2. Dispatch Admin New Order Notification to configured store recipients
        try {
            $adminEmails = \App\Models\Setting::getAdminNotificationEmails();
            if (!empty($adminEmails)) {
                \Illuminate\Support\Facades\Mail::to($adminEmails)->send(new \App\Mail\AdminNewOrderNotification($order->load('items')));
                \Illuminate\Support\Facades\Log::info("Admin order notification for #{$order->order_number} sent to: " . implode(', ', $adminEmails));
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Failed to send admin order notification for #{$order->order_number}: " . $e->getMessage());
        }

        return redirect()->route('checkout.success', $order)->with('success', 'Order placed successfully!');
    }

    public function success(Order $order)
    {
        if ($order->user_id) {
            if (Auth::id() !== $order->user_id && !Auth::user()?->isAdmin()) {
                abort(403, 'Unauthorized to view this order.');
            }
        } else {
            if (session('last_placed_order_id') !== $order->id && !Auth::user()?->isAdmin()) {
                abort(403, 'Unauthorized to view this order.');
            }
        }

        $order->load('items');

        return view('checkout.success', compact('order'));
    }
}
