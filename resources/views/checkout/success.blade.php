@extends('layouts.app')
@section('title', ($order->fulfillment_type === 'self_collection' || $order->customer_group === 'walkin') ? 'Collection Token #' . ($order->collection_token ?? $order->order_number) : 'Order Confirmed — MST Import and Export Sdn Bhd')

@section('content')
<!-- Page Header -->
<div class="page-header" style="padding-top:calc(75px + var(--space-6));background:linear-gradient(135deg, #091a36 0%, #0f274a 45%, #1e3a8a 100%);color:#ffffff;border-bottom:1px solid #1e3a8a;padding-bottom:var(--space-8);position:relative;overflow:hidden">
    <div style="position:absolute;inset:0;opacity:0.07;background-image:radial-gradient(#38bdf8 1px, transparent 1px);background-size:20px 20px"></div>
    <div class="container page-header-content" style="position:relative;z-index:2">
        <div class="breadcrumb" style="margin-bottom:4px">
            <a href="{{ route('home') }}" style="display:inline-flex;align-items:center;gap:4px;color:#bae6fd;text-decoration:none">🏠 Home</a>
            <span class="breadcrumb-sep" style="color:#60a5fa">›</span>
            <a href="{{ route('account.orders') }}" style="color:#bae6fd;text-decoration:none">My Orders</a>
            <span class="breadcrumb-sep" style="color:#60a5fa">›</span>
            <span style="font-weight:600;color:#ffffff">Order Confirmed</span>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
            <div>
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px">
                    <span style="background:rgba(56,189,248,0.18);border:1px solid rgba(186,230,253,0.35);padding:2px 9px;border-radius:999px;font-size:0.7rem;font-weight:700;color:#7dd3fc;text-transform:uppercase;letter-spacing:0.05em">
                        🎉 Payment Confirmed
                    </span>
                    <span style="color:#bae6fd;font-size:0.78rem">Order #{{ $order->order_number }}</span>
                </div>
                <h1 class="page-title" style="color:#ffffff;font-family:var(--font-heading);font-size:clamp(1.5rem,3vw,1.95rem);margin-bottom:4px;letter-spacing:-0.02em">
                    Order Confirmation
                </h1>
                <p class="page-subtitle" style="color:#e0f2fe;font-size:0.88rem;max-width:680px;line-height:1.4;margin:0">
                    Thank you for your order! Your payment has been received and your frozen seafood is being prepared.
                </p>
            </div>
            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                <a href="{{ route('account.orders') }}" class="btn btn-secondary btn-sm" style="background:rgba(255,255,255,0.12);color:#ffffff;border:1px solid rgba(255,255,255,0.25);border-radius:10px;font-weight:600">
                    📦 Track Orders
                </a>
                <a href="{{ route('shop.index') }}" class="btn btn-primary btn-sm" style="background:#2563eb;color:#ffffff;border:1px solid #3b82f6;border-radius:10px;font-weight:700;box-shadow:0 2px 8px rgba(37,99,235,0.35)">
                    Continue Shopping →
                </a>
            </div>
        </div>
    </div>
</div>

<div style="min-height:calc(100vh - 220px);display:flex;align-items:center;justify-content:center;padding:2rem 1rem 4rem;background:#f8fafc">
    <div style="max-width:580px;width:100%">

        @if($order->fulfillment_type === 'self_collection' || $order->customer_group === 'walkin')
            <!-- ═══════════════════════════════════════════════════════════════════════ -->
            <!-- STORE COLLECTION PASS (WALK-IN / SELF-COLLECTION)                     -->
            <!-- ═══════════════════════════════════════════════════════════════════════ -->
            
            <div style="text-align:center;margin-bottom:var(--space-4)">
                <div style="font-size:2.8rem;margin-bottom:6px;animation:bounceIn 0.6s ease">🎉</div>
                <h1 style="font-size:1.6rem;font-family:var(--font-heading);color:var(--seagreen-900);margin-bottom:4px">
                    Payment Successful!
                </h1>
                <p class="text-sm text-muted" style="margin:0">
                    Your seafood order is placed and being prepared at our counter.
                </p>
            </div>

            <!-- Digital Collection Pass Card -->
            <div class="card" style="background:white;border:2px solid #bfdbfe;border-radius:16px;box-shadow:0 10px 30px rgba(29,78,216,0.08);overflow:hidden;margin-bottom:var(--space-5)">
                
                <!-- Pass Header -->
                <div style="background:linear-gradient(135deg,var(--seagreen-700),var(--seagreen-800));color:white;padding:var(--space-4) var(--space-5);display:flex;justify-content:space-between;align-items:center">
                    <div>
                        <div style="font-size:0.75rem;text-transform:uppercase;letter-spacing:1px;opacity:0.9">MST In-Store Pass</div>
                        <div style="font-weight:700;font-size:1rem">Johor Bahru (SILC) Store</div>
                    </div>
                    <div style="background:rgba(255,255,255,0.2);padding:4px 10px;border-radius:20px;font-size:0.75rem;font-weight:600">
                        Self-Collection
                    </div>
                </div>

                <!-- Big Token Section -->
                <div style="text-align:center;padding:var(--space-6) var(--space-4);background:linear-gradient(180deg,#eff6ff,#ffffff);border-bottom:2px dashed #bfdbfe">
                    <div style="font-size:0.85rem;color:var(--seagreen-700);font-weight:700;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px">
                        Your Collection Token
                    </div>
                    <div style="display:inline-block;background:#dbeafe;color:#1e3a8a;padding:8px 24px;border-radius:12px;border:2px solid #93c5fd;margin-bottom:var(--space-3);box-shadow:0 4px 12px rgba(29,78,216,0.12)">
                        <span style="font-size:2.8rem;font-weight:900;font-family:var(--font-heading);letter-spacing:2px;line-height:1">
                            {{ $order->collection_token ?? 'W-' . str_pad($order->id, 3, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>
                    
                    <div style="margin-bottom:var(--space-3)">
                        <span style="display:inline-flex;align-items:center;gap:6px;background:#fef3c7;color:#92400e;padding:6px 14px;border-radius:20px;font-weight:700;font-size:0.8rem;border:1px solid #fde68a">
                            <span style="width:8px;height:8px;background:#f59e0b;border-radius:50%;animation:pulseDot 1.5s infinite"></span>
                            Preparing at Store Counter
                        </span>
                    </div>

                    <p style="font-size:0.85rem;color:var(--gray-600);max-width:400px;margin:0 auto;line-height:1.4">
                        Please proceed to <strong>Counter 2 (Express Collection)</strong> and show this token to collect your packed seafood.
                    </p>
                </div>

                <!-- QR Code & Order Verification -->
                <div style="padding:var(--space-5);border-bottom:1px solid var(--gray-100);display:flex;align-items:center;gap:var(--space-4);flex-wrap:wrap">
                    <div style="background:white;padding:8px;border:1px solid var(--gray-200);border-radius:10px;width:96px;height:96px;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 6px rgba(0,0,0,0.04)">
                        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(80)->generate(url('/admin/orders/' . $order->id)) !!}
                    </div>
                    <div style="flex:1;min-width:180px">
                        <div style="font-size:0.75rem;color:var(--gray-500)">Order Reference</div>
                        <div style="font-weight:700;color:var(--gray-900);font-size:0.95rem;margin-bottom:4px">{{ $order->order_number }}</div>
                        <div style="font-size:0.8rem;color:var(--gray-600)">
                            Customer: <strong>{{ $order->customer_name }}</strong>
                        </div>
                        @if($order->customer_phone)
                            <div style="font-size:0.8rem;color:var(--gray-600)">
                                Phone: <strong>{{ $order->customer_phone }}</strong>
                            </div>
                        @endif
                        <div style="font-size:0.75rem;color:var(--seagreen-700);margin-top:2px">
                            Paid: RM {{ number_format($order->total, 2) }} ({{ ucfirst(str_replace('_', ' ', $order->payment_method ?? 'card')) }})
                        </div>
                    </div>
                </div>

                <!-- Order Items Checklist -->
                <div style="padding:var(--space-4) var(--space-5)">
                    <div style="font-size:0.8rem;font-weight:700;color:var(--gray-700);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:var(--space-2)">
                        Items to Collect ({{ $order->items->count() }})
                    </div>
                    <div style="max-height:220px;overflow-y:auto">
                        @foreach($order->items as $item)
                        <div style="display:flex;justify-content:space-between;align-items:center;padding:6px 0;border-bottom:1px solid var(--gray-100);font-size:0.85rem">
                            <div style="display:flex;align-items:center;gap:8px">
                                <span style="color:var(--seagreen-600);font-weight:bold">☑</span>
                                <span>{{ $item->product_name }}</span>
                                <span style="color:var(--gray-500);font-size:0.75rem">× {{ $item->quantity }}</span>
                            </div>
                            <span style="font-weight:600;color:var(--gray-700)">RM {{ number_format($item->subtotal, 2) }}</span>
                        </div>
                        @endforeach
                    </div>

                    @if($order->customer_notes)
                    <div style="margin-top:var(--space-3);background:var(--gray-50);padding:8px 12px;border-radius:6px;font-size:0.8rem;color:var(--gray-600)">
                        <strong>Note to staff:</strong> {{ $order->customer_notes }}
                    </div>
                    @endif
                </div>

                <!-- Footer Hint -->
                <div style="background:var(--gray-50);padding:10px 16px;text-align:center;font-size:0.75rem;color:var(--gray-500);border-top:1px solid var(--gray-100)">
                    💡 Keep this screen open or take a screenshot to show at the counter.
                </div>
            </div>

            <!-- Action Buttons -->
            <div style="display:flex;gap:var(--space-3);justify-content:center;flex-wrap:wrap">
                <a href="{{ route('walkin.shop') }}" class="btn btn-primary" style="padding:10px 20px;font-weight:600">
                    🛒 Order More Seafood
                </a>
                <button type="button" onclick="window.print()" class="btn btn-secondary" style="padding:10px 18px;font-weight:600">
                    🖨️ Print / Save Pass
                </button>
            </div>

        @else
            <!-- ═══════════════════════════════════════════════════════════════════════ -->
            <!-- STANDARD DELIVERY / ONLINE ORDER CONFIRMATION                         -->
            <!-- ═══════════════════════════════════════════════════════════════════════ -->
            
            <div style="text-align:center;margin-bottom:var(--space-5)">
                <div style="font-size:3.5rem;margin-bottom:var(--space-3);animation:bounceIn 0.6s ease">🎉</div>
                <h1 style="font-family:var(--font-heading);margin-bottom:var(--space-2);color:var(--gray-900)">Order Confirmed!</h1>
                <p class="text-muted" style="font-size:0.95rem">
                    Thank you for ordering with MST Import and Export Sdn Bhd! We are preparing your shipment.
                </p>
            </div>

            <div class="card p-6 mb-6" style="background:white;border:1px solid var(--gray-200);border-radius:12px">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:var(--space-4)">
                    <div>
                        <div class="text-xs text-muted">Order Number</div>
                        <div style="font-size:1.2rem;font-weight:700;color:var(--seagreen-700);font-family:var(--font-heading)">{{ $order->order_number }}</div>
                    </div>
                    <div>{!! $order->status_badge !!}</div>
                </div>

                @foreach($order->items as $item)
                <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--gray-100);font-size:0.875rem">
                    <span style="color:var(--gray-700)">{{ $item->product_name }} × {{ $item->quantity }}</span>
                    <span style="font-weight:600;color:var(--gray-900)">RM {{ number_format($item->subtotal, 2) }}</span>
                </div>
                @endforeach

                <div style="display:flex;justify-content:space-between;margin-top:var(--space-4);font-family:var(--font-heading);font-size:1.1rem">
                    <strong>Total Paid</strong>
                    <strong style="color:var(--seagreen-700)">RM {{ number_format($order->total, 2) }}</strong>
                </div>

                <div style="margin-top:var(--space-4);padding-top:var(--space-3);border-top:1px solid var(--gray-100)">
                    <div class="text-xs text-muted">Fulfillment</div>
                    <div style="font-weight:600;margin-top:2px;font-size:0.9rem">
                        🚚 Delivery to {{ $order->shipping_address['city'] ?? 'your address' }}
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:var(--space-3);justify-content:center;flex-wrap:wrap">
                @auth
                    <a href="{{ route('account.orders') }}" class="btn btn-primary">View My Orders</a>
                    <a href="{{ route('shop.index') }}" class="btn btn-secondary">Continue Shopping</a>
                @else
                    <a href="{{ route('home') }}" class="btn btn-primary">Back to Home</a>
                @endauth
            </div>
        @endif

    </div>
</div>
@endsection

@push('styles')
<style>
@keyframes bounceIn {
    0% { transform: scale(0); opacity: 0; }
    60% { transform: scale(1.08); }
    100% { transform: scale(1); opacity: 1; }
}
@keyframes pulseDot {
    0% { transform: scale(0.95); opacity: 0.6; }
    50% { transform: scale(1.3); opacity: 1; }
    100% { transform: scale(0.95); opacity: 0.6; }
}
@media print {
    header, footer, .btn, .walkin-stepper { display: none !important; }
    body { background: white !important; }
}
</style>
@endpush
