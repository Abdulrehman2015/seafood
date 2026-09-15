@extends('layouts.app')
@section('title', 'Quotation ' . $quotation->quotation_number . ' — MST Import and Export Sdn Bhd')

@section('content')
<div style="padding-top:80px;padding-bottom:var(--space-16)">
    <div class="container" style="max-width:960px">
        <!-- Page Header -->
        <div style="display:flex;align-items:center;justify-content:space-between;margin-top:var(--space-8);margin-bottom:var(--space-8);flex-wrap:wrap;gap:var(--space-4)">
            <div>
                <div style="display:flex;align-items:center;gap:var(--space-3);margin-bottom:var(--space-1)">
                    <h1 style="font-family:var(--font-heading);font-size:1.75rem">Quotation {{ $quotation->quotation_number }}</h1>
                    {!! $quotation->status_badge !!}
                </div>
                <p class="text-sm text-muted">Submitted on {{ $quotation->created_at->format('d F Y \a\t h:i A') }}</p>
            </div>
            <div>
                <a href="{{ route('quotations.index') }}" class="btn btn-secondary">← Back to My RFQs</a>
            </div>
        </div>

        @if($quotation->status === 'quoted')
            <!-- Action Banner -->
            <div class="card mb-6" style="background:linear-gradient(135deg,rgba(20,160,165,0.2),rgba(10,25,41,0.6));border:1px solid var(--teal-400)">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:var(--space-4)">
                    <div>
                        <h3 style="font-size:1.1rem;font-weight:700;color:var(--text-primary);margin-bottom:4px">
                            🎉 Quotation Ready for Your Review!
                        </h3>
                        <p class="text-sm text-secondary">
                            This quote is valid until <strong>{{ $quotation->valid_until ? $quotation->valid_until->format('d M Y') : 'Further Notice' }}</strong>. Please accept to convert into an order.
                        </p>
                    </div>
                    <div style="display:flex;gap:var(--space-3)">
                        <form action="{{ route('quotations.accept', $quotation) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary" style="padding:12px 24px">
                                ✓ Accept Quotation & Order
                            </button>
                        </form>
                        <form action="{{ route('quotations.reject', $quotation) }}" method="POST" onsubmit="return confirm('Are you sure you want to decline this quote?');">
                            @csrf
                            <button type="submit" class="btn btn-danger" style="padding:12px 20px">
                                Decline
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @elseif($quotation->status === 'accepted')
            <div class="alert alert-success mb-6" style="display:flex;justify-content:space-between;align-items:center">
                <div>✓ You have accepted this quotation. Proceed to checkout to finalize delivery and payment.</div>
                <a href="{{ route('checkout.fromQuotation', $quotation) }}" class="btn btn-primary btn-sm">Proceed to Checkout →</a>
            </div>
        @endif

        <div class="card mb-6">
            <div class="card-header">
                <div class="card-title">Quoted Seafood Products</div>
            </div>
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Requested Qty</th>
                            <th>Custom Spec / Notes</th>
                            <th style="text-align:right">Quoted Price/Unit</th>
                            <th style="text-align:right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($quotation->items as $item)
                        <tr>
                            <td>
                                <div style="font-weight:600;color:var(--text-primary)">{{ $item->product_name }}</div>
                                @if($item->product && $item->product->origin)
                                    <div class="text-xs text-muted">Origin: {{ $item->product->origin }} | Unit: {{ $item->product->unit }}</div>
                                @endif
                            </td>
                            <td class="font-bold">{{ $item->quantity_requested }}</td>
                            <td class="text-sm text-secondary">{{ $item->notes ?? '—' }}</td>
                            <td style="text-align:right">
                                @if($item->quoted_price)
                                    RM {{ number_format($item->quoted_price, 2) }}
                                @else
                                    <span class="text-muted text-sm">Under Review</span>
                                @endif
                            </td>
                            <td style="text-align:right;font-weight:700;color:var(--teal-400)">
                                @if($item->subtotal)
                                    RM {{ number_format($item->subtotal, 2) }}
                                @else
                                    <span class="text-muted text-sm">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="display:flex;justify-content:flex-end;padding:var(--space-6) var(--space-4) 0;border-top:1px solid var(--glass-border)">
                <div style="min-width:280px">
                    <div class="summary-row">
                        <span class="font-bold">Total Quoted Amount</span>
                        <span class="summary-total" style="color:var(--teal-400)">
                            @if($quotation->total_quoted)
                                RM {{ number_format($quotation->total_quoted, 2) }}
                            @else
                                Pending Review
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-6)">
            <!-- Customer Notes -->
            <div class="card">
                <div class="card-header"><div class="card-title">Your Specifications & Requirements</div></div>
                <p class="text-sm text-secondary leading-relaxed">
                    {{ $quotation->customer_notes ?? 'No additional notes provided.' }}
                </p>
            </div>

            <!-- Admin Response Notes -->
            <div class="card">
                <div class="card-header"><div class="card-title">Mika Commercial Response</div></div>
                @if($quotation->admin_notes)
                    <p class="text-sm text-secondary leading-relaxed">
                        {{ $quotation->admin_notes }}
                    </p>
                    @if($quotation->valid_until)
                        <div class="text-xs text-teal mt-3">
                            Quote Validity: {{ $quotation->valid_until->format('d M Y') }}
                        </div>
                    @endif
                @else
                    <p class="text-sm text-muted">
                        Our trading team is currently assessing cold storage logistics and container availability. You will receive an email update once pricing is published.
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
