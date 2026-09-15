@extends('layouts.app')
@section('title', 'Walk-in Express Checkout — MST Import and Export Sdn Bhd')

@section('content')
<!-- Walk-in Header Banner & Process Stepper -->
<div style="background:linear-gradient(135deg, #f0fdfa, #ccfbf1);border-bottom:1px solid #99f6e4;padding:var(--space-5) 0;padding-top:calc(75px + var(--space-4))">
    <div class="container">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:var(--space-4);margin-bottom:var(--space-4)">
            <div style="display:flex;align-items:center;gap:var(--space-3)">
                <div style="font-size:2rem;background:#99f6e4;width:48px;height:48px;display:flex;align-items:center;justify-content:center;border-radius:12px">🏪</div>
                <div>
                    <h1 style="font-size:1.4rem;font-family:var(--font-heading);color:var(--seagreen-800);margin-bottom:2px">Walk-in Express Checkout</h1>
                    <p class="text-xs text-muted" style="margin:0">Pay on your phone · Pick up immediately at Store Counter</p>
                </div>
            </div>
            <div>
                <a href="{{ route('walkin.shop') }}" class="btn btn-secondary btn-sm" style="font-weight:600">
                    ← Back to Catalogue
                </a>
            </div>
        </div>

        <!-- 5-Step Process Bar -->
        <div class="walkin-stepper" style="display:flex;align-items:center;justify-content:space-between;background:var(--white);border:1px solid #ccfbf1;border-radius:12px;padding:10px 16px;box-shadow:0 1px 3px rgba(15,118,110,0.05);overflow-x:auto;gap:8px">
            <div style="display:flex;align-items:center;gap:6px;font-size:0.8rem;color:var(--seagreen-700);font-weight:600;white-space:nowrap">
                <span style="background:var(--seagreen-100);color:var(--seagreen-800);width:20px;height:20px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:0.7rem">✓</span>
                1. QR Code
            </div>
            <span style="color:#99f6e4;font-size:0.8rem">→</span>
            <div style="display:flex;align-items:center;gap:6px;font-size:0.8rem;color:var(--seagreen-700);font-weight:600;white-space:nowrap">
                <span style="background:var(--seagreen-100);color:var(--seagreen-800);width:20px;height:20px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:0.7rem">✓</span>
                2. Pick Seafood
            </div>
            <span style="color:#99f6e4;font-size:0.8rem">→</span>
            <div style="display:flex;align-items:center;gap:6px;font-size:0.8rem;color:var(--seagreen-800);font-weight:700;white-space:nowrap;background:var(--seagreen-50);padding:4px 8px;border-radius:6px;border:1px solid var(--seagreen-200)">
                <span style="background:var(--seagreen-600);color:white;width:20px;height:20px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:0.7rem">3</span>
                3. Fast Pay
            </div>
            <span style="color:#99f6e4;font-size:0.8rem">→</span>
            <div style="display:flex;align-items:center;gap:6px;font-size:0.8rem;color:var(--gray-400);font-weight:500;white-space:nowrap">
                <span style="background:var(--gray-100);color:var(--gray-500);width:20px;height:20px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:0.7rem">4</span>
                4. Collection Token
            </div>
        </div>
    </div>
</div>

<div class="container" style="padding:var(--space-6) 0 var(--space-16)">
    <form action="{{ route('checkout.store') }}" method="POST" id="checkoutForm">
        @csrf
        <input type="hidden" name="fulfillment_type" value="self_collection">

        <div class="checkout-layout">

            <!-- Left: Customer Info & Payment -->
            <div>
                <!-- Store Counter Pickup Notice -->
                <div class="card mb-4" style="border-left:4px solid var(--seagreen-600);background:#f0fdfa;padding:var(--space-4)">
                    <div style="display:flex;gap:var(--space-3);align-items:center">
                        <div style="font-size:1.8rem">🏬</div>
                        <div>
                            <div style="font-weight:700;color:var(--seagreen-900);font-size:0.95rem">Johor Bahru (SILC) Store Counter Self-Collection</div>
                            <div class="text-xs text-muted">Your seafood will be packed and waiting at Counter 2 once your payment is confirmed.</div>
                        </div>
                    </div>
                </div>

                <!-- Simple Customer Details -->
                <div class="card mb-4" style="background:white;border:1px solid var(--gray-200);border-radius:12px;padding:var(--space-5)">
                    <div class="card-header" style="padding:0 0 var(--space-3);border-bottom:1px solid var(--gray-100);margin-bottom:var(--space-4)">
                        <div class="card-title" style="font-size:1.05rem;color:var(--gray-900)">👤 Who is collecting?</div>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-4)">
                        <div class="form-group" style="margin-bottom:var(--space-3)">
                            <label class="form-label" style="font-weight:600;font-size:0.85rem">Your Name <span class="required" style="color:#ef4444">*</span></label>
                            <input type="text" name="customer_name" class="form-control" placeholder="e.g. John Tan" value="{{ old('customer_name', auth()->user()?->name) }}" required style="border-radius:8px">
                            @error('customer_name')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group" style="margin-bottom:var(--space-3)">
                            <label class="form-label" style="font-weight:600;font-size:0.85rem">Mobile Phone <span class="required" style="color:#ef4444">*</span></label>
                            <input type="tel" name="customer_phone" class="form-control" placeholder="e.g. 012-3456789" value="{{ old('customer_phone', auth()->user()?->phone) }}" required style="border-radius:8px">
                            @error('customer_phone')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label" style="font-size:0.8rem;color:var(--gray-500)">Email for e-receipt (Optional)</label>
                        <input type="email" name="customer_email" class="form-control" placeholder="e.g. john@example.com" value="{{ old('customer_email', auth()->user()?->email) }}" style="border-radius:8px">
                    </div>
                </div>

                <!-- Special Packing Requests -->
                <div class="card mb-4" style="background:white;border:1px solid var(--gray-200);border-radius:12px;padding:var(--space-5)">
                    <div class="card-header" style="padding:0 0 var(--space-2);border-bottom:1px solid var(--gray-100);margin-bottom:var(--space-3)">
                        <div class="card-title" style="font-size:0.95rem;color:var(--gray-900)">📝 Special Packaging Request (Optional)</div>
                    </div>
                    <textarea name="customer_notes" class="form-control" rows="2" placeholder="e.g. Please add extra ice bag, pack separately, etc..." style="border-radius:8px;font-size:0.85rem">{{ old('customer_notes') }}</textarea>
                </div>

                <!-- Payment Section -->
                <div class="card" style="background:white;border:1px solid var(--gray-200);border-radius:12px;padding:var(--space-5)">
                    <div class="card-header" style="padding:0 0 var(--space-3);border-bottom:1px solid var(--gray-100);margin-bottom:var(--space-3)">
                        <div class="card-title" style="font-size:1.05rem;color:var(--gray-900)">💳 Secure In-Store Payment</div>
                    </div>
                    <div class="alert alert-info mb-3" style="font-size:0.85rem;background:#f0fdfa;border:1px solid #99f6e4;color:var(--seagreen-900);border-radius:8px">
                        🔒 Pay with Card, Apple Pay, or Google Pay. Immediately receive your counter collection token.
                    </div>

                    <!-- Payment Element Container -->
                    <div id="payment-element" style="background:var(--gray-50);border:1px solid var(--gray-200);border-radius:8px;padding:var(--space-4);min-height:70px">
                        <p class="text-muted text-sm text-center" style="padding:var(--space-3)">Connecting to payment gateway...</p>
                    </div>
                    <div id="payment-message" class="alert alert-danger mt-3" style="display:none;font-size:0.85rem"></div>
                    <input type="hidden" name="payment_intent_id" id="paymentIntentId">
                </div>
            </div>

            <!-- Right: Order Summary -->
            <div class="order-summary" style="position:sticky;top:90px;background:white;border:1px solid var(--gray-200);border-radius:12px;padding:var(--space-5);box-shadow:0 4px 12px rgba(0,0,0,0.03)">
                <div class="card-header" style="padding:0 0 var(--space-3);border-bottom:1px solid var(--gray-100);margin-bottom:var(--space-3)">
                    <div class="card-title" style="font-size:1.05rem;color:var(--gray-900)">Order Summary ({{ $items->count() }} items)</div>
                </div>

                <div style="max-height:300px;overflow-y:auto;margin-bottom:var(--space-4);padding-right:4px">
                    @foreach($items as $item)
                        @php $price = $item->product?->walkin_price ?? $item->product?->retail_price ?? 0; @endphp
                        <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid var(--gray-100);font-size:0.875rem">
                            <div style="flex:1">
                                <div style="color:var(--gray-900);font-weight:600">{{ $item->product?->name }}</div>
                                <div class="text-xs text-muted">{{ $item->quantity }} × RM {{ number_format($price, 2) }}</div>
                            </div>
                            <div style="font-weight:700;color:var(--seagreen-800);margin-left:8px">
                                RM {{ number_format($price * $item->quantity, 2) }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="summary-row" style="display:flex;justify-content:space-between;padding:6px 0;font-size:0.9rem;color:var(--gray-600)">
                    <span>Subtotal</span>
                    <span>RM {{ number_format($totals['subtotal'], 2) }}</span>
                </div>
                <div class="summary-row" style="display:flex;justify-content:space-between;padding:6px 0;font-size:0.9rem">
                    <span>Fulfillment</span>
                    <span style="color:var(--seagreen-700);font-weight:700">Counter Self-Collection (FREE)</span>
                </div>
                <div class="summary-row" style="display:flex;justify-content:space-between;margin-top:var(--space-2);padding-top:var(--space-3);border-top:2px dashed var(--gray-200);font-size:1.15rem">
                    <span class="font-bold" style="color:var(--gray-900)">Total to Pay</span>
                    <span style="font-weight:800;color:var(--seagreen-700);font-family:var(--font-heading)">RM {{ number_format($totals['total'], 2) }}</span>
                </div>

                <button type="submit" class="btn btn-primary btn-lg btn-block" id="submitBtn" style="margin-top:var(--space-4);padding:14px;font-weight:700;font-size:1rem;box-shadow:0 4px 14px rgba(15,118,110,0.3)">
                    🔒 Pay & Get Collection Token
                </button>

                <div style="display:flex;align-items:center;justify-content:center;gap:6px;margin-top:var(--space-3);font-size:0.75rem;color:var(--gray-500)">
                    <span>⚡ Instant Token Generation</span> · <span>Johor Bahru (SILC) Counter</span>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
let stripe, elements, paymentElement;
const stripeKey = '{{ config("services.stripe.key") }}';

async function initStripe() {
    const isPlaceholder = !stripeKey || stripeKey.includes('YOUR_PUBLISHABLE_KEY');

    if (isPlaceholder) {
        showMockPaymentUI();
        return;
    }

    try {
        stripe = Stripe(stripeKey);
        const res = await fetch('{{ route("checkout.paymentIntent") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        const data = await res.json();
        if (data.clientSecret) {
            elements = stripe.elements({
                clientSecret: data.clientSecret,
                appearance: {
                    theme: 'stripe',
                    variables: { colorPrimary: '#0f766e', fontFamily: 'Inter, sans-serif' }
                }
            });
            paymentElement = elements.create('payment');
            paymentElement.mount('#payment-element');
        } else {
            showMockPaymentUI();
        }
    } catch (err) {
        showMockPaymentUI();
    }
}

function showMockPaymentUI() {
    document.getElementById('payment-element').innerHTML = `
        <div style="text-align:center;padding:12px;background:#f0fdfa;border-radius:8px">
            <div style="font-weight:700;color:var(--seagreen-900);font-size:0.9rem;margin-bottom:4px">
                ⚡ Express Demo Payment Mode Active
            </div>
            <p class="text-xs text-muted" style="margin-bottom:12px">
                Live sandbox enabled. Tap below to simulate instant payment and generate your collection token.
            </p>
            <button type="button" class="btn btn-primary btn-sm" onclick="simulateTestPayment()" style="padding:8px 18px;font-weight:600">
                ⚡ Simulate Instant Payment (One-Touch)
            </button>
        </div>
    `;
}

function simulateTestPayment() {
    document.getElementById('paymentIntentId').value = 'pi_test_walkin_' + Math.random().toString(36).substring(2, 12);
    document.getElementById('payment-element').innerHTML = `
        <div style="background:#d1fae5;color:#065f46;padding:12px;border-radius:8px;text-align:center;font-weight:700;font-size:0.9rem;border:1px solid #a7f3d0">
            ✓ Payment Authorized (Simulated Test Mode)
        </div>
    `;
    const btn = document.getElementById('submitBtn');
    btn.disabled = false;
    btn.innerHTML = '🔒 Complete & Get Collection Token →';
}

initStripe();

document.getElementById('checkoutForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = document.getElementById('submitBtn');

    // If simulated payment has been authorized
    if (document.getElementById('paymentIntentId').value) {
        btn.disabled = true;
        btn.innerHTML = 'Generating Collection Token...';
        e.target.submit();
        return;
    }

    // If Stripe is not loaded or in mock mode
    if (!stripe || !elements) {
        simulateTestPayment();
        btn.disabled = true;
        btn.innerHTML = 'Generating Collection Token...';
        e.target.submit();
        return;
    }

    btn.disabled = true;
    btn.innerHTML = 'Processing Payment...';

    const { error, paymentIntent } = await stripe.confirmPayment({
        elements,
        redirect: 'if_required'
    });

    if (error) {
        const msgEl = document.getElementById('payment-message');
        msgEl.style.display = 'block';
        msgEl.textContent = error.message;
        btn.disabled = false;
        btn.innerHTML = '🔒 Pay & Get Collection Token';
        return;
    }

    document.getElementById('paymentIntentId').value = paymentIntent.id;
    e.target.submit();
});
</script>
@endpush
