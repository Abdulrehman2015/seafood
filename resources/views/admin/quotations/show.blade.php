@extends('layouts.admin')
@section('title', 'Quotation ' . $quotation->quotation_number . ' — Admin')

@section('content')
<div class="admin-topbar">
    <div>
        <h1 class="admin-page-title">Quotation {{ $quotation->quotation_number }}</h1>
        <p class="text-sm text-muted">Submitted on {{ $quotation->created_at->format('d M Y, h:i A') }}</p>
    </div>
    <a href="{{ route('admin.quotations.index') }}" class="btn btn-secondary">← Back to RFQs</a>
</div>

<div style="display:grid;grid-template-columns:1fr 340px;gap:var(--space-6);align-items:start">

    <!-- Left: Items & Respond -->
    <div>
        <div class="card mb-5">
            <div class="card-header">
                <div class="card-title">Requested Products</div>
                {!! $quotation->status_badge !!}
            </div>

            @foreach($quotation->items as $item)
            <div style="padding:var(--space-4) 0;border-bottom:1px solid var(--glass-border)">
                <div style="display:grid;grid-template-columns:1fr auto;gap:var(--space-4)">
                    <div>
                        <div style="font-weight:600;color:var(--text-primary)">{{ $item->product?->name ?? $item->product_name }}</div>
                        @if($item->product)
                            <div class="text-xs text-muted">SKU: {{ $item->product->sku ?? '—' }} | Weight: {{ $item->product->weight ?? '—' }}</div>
                        @endif
                        <div class="text-sm text-muted mt-1">Requested Qty: <strong>{{ $item->quantity_requested }}</strong></div>
                        @if($item->notes)
                            <div class="text-sm text-muted italic mt-1">"{{ $item->notes }}"</div>
                        @endif
                    </div>
                    <div style="text-align:right">
                        @if($item->quoted_price)
                            <div class="text-teal font-bold">RM {{ number_format($item->quoted_price, 2) }}/unit</div>
                            <div class="text-xs text-muted">= RM {{ number_format($item->subtotal ?? ($item->quoted_price * $item->quantity_requested), 2) }}</div>
                        @else
                            <span class="badge badge-warning">Pending Quote</span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach

            @if($quotation->total_quoted)
            <div style="display:flex;justify-content:flex-end;padding-top:var(--space-4)">
                <div style="text-align:right">
                    <span class="text-muted text-sm">Total Quoted: </span>
                    <span class="font-bold text-teal" style="font-size:1.25rem">RM {{ number_format($quotation->total_quoted, 2) }}</span>
                </div>
            </div>
            @endif
        </div>

        <!-- Respond Form -->
        @if(in_array($quotation->status, ['pending','quoted']))
        <div class="card">
            <div class="card-header"><div class="card-title">📝 Respond / Update RFQ</div></div>
            <form action="{{ route('admin.quotations.respond', $quotation) }}" method="POST">
                @csrf @method('PATCH')

                @foreach($quotation->items as $index => $item)
                <div style="margin-bottom:var(--space-5);padding:var(--space-4);background:var(--gray-50);border:1px solid var(--gray-200);border-radius:var(--radius-md)">
                    <input type="hidden" name="items[{{ $index }}][quotation_item_id]" value="{{ $item->id }}">
                    <div style="font-weight:600;font-size:0.875rem;margin-bottom:var(--space-3);color:var(--text-primary)">
                        {{ $item->product?->name ?? $item->product_name }}
                        <span class="text-xs text-muted">(Requested: {{ $item->quantity_requested }} units)</span>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-3)">
                        <div class="form-group mb-0">
                            <label class="form-label">Quoted Price (RM/unit) <span class="required">*</span></label>
                            <input type="number" name="items[{{ $index }}][quoted_price]" step="0.01" min="0"
                                   class="form-control" value="{{ old('items.'.$index.'.quoted_price', $item->quoted_price ?? '') }}" placeholder="0.00" required>
                        </div>
                        <div class="form-group mb-0">
                            <label class="form-label">Calculated Subtotal</label>
                            <input type="text" class="form-control" value="RM {{ number_format(($item->quoted_price ?? 0) * $item->quantity_requested, 2) }}" readonly style="opacity:0.7">
                        </div>
                    </div>
                </div>
                @endforeach

                <div class="form-group">
                    <label class="form-label">Quote Validity Date <span class="required">*</span></label>
                    <input type="date" name="valid_until" class="form-control"
                           value="{{ old('valid_until', $quotation->valid_until ? $quotation->valid_until->format('Y-m-d') : now()->addDays(14)->format('Y-m-d')) }}"
                           min="{{ now()->addDay()->format('Y-m-d') }}" required>
                    <div class="form-hint">Set the expiration date for this quotation offer.</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Message / Terms for Customer</label>
                    <textarea name="admin_notes" class="form-control" rows="3" placeholder="Add terms of sale, delivery timeline, payment terms...">{{ old('admin_notes', $quotation->admin_notes) }}</textarea>
                </div>

                <div style="display:flex;gap:var(--space-3);margin-top:var(--space-5)">
                    <button type="submit" class="btn btn-primary">Send Official Quotation to Customer →</button>
                </div>
            </form>
        </div>
        @endif
    </div>

    <!-- Right: Customer -->
    <div>
        <div class="card mb-4">
            <div class="card-title mb-4">Customer Information</div>
            <div style="display:flex;flex-direction:column;gap:var(--space-3)">
                <div>
                    <div class="text-xs text-muted">Customer Name</div>
                    <div style="font-weight:600">{{ $quotation->user?->name }}</div>
                </div>
                <div>
                    <div class="text-xs text-muted">Company</div>
                    <div class="text-sm font-bold text-primary">{{ $quotation->user?->company_name ?? '—' }}</div>
                </div>
                <div>
                    <div class="text-xs text-muted">Email</div>
                    <div class="text-sm"><a href="mailto:{{ $quotation->user?->email }}" class="text-teal">{{ $quotation->user?->email }}</a></div>
                </div>
                <div>
                    <div class="text-xs text-muted">Phone</div>
                    <div class="text-sm">{{ $quotation->user?->phone ?? '—' }}</div>
                </div>
            </div>
        </div>

        @if($quotation->customer_notes)
        <div class="card">
            <div class="card-title mb-3">Customer Notes & Logistics Requirements</div>
            <p class="text-sm text-secondary leading-relaxed">{{ $quotation->customer_notes }}</p>
        </div>
        @endif
    </div>
</div>
@endsection
