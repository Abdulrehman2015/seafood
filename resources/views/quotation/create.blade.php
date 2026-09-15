@extends('layouts.app')
@section('title', 'Request a Quotation (RFQ) — MST Import and Export Sdn Bhd')

@section('content')
<!-- Page Header -->
<div class="page-header" style="padding-top:calc(75px + var(--space-6));background:linear-gradient(135deg, #091a36 0%, #0f274a 45%, #1e3a8a 100%);color:#ffffff;border-bottom:1px solid #1e3a8a;padding-bottom:var(--space-8);position:relative;overflow:hidden">
    <div style="position:absolute;inset:0;opacity:0.07;background-image:radial-gradient(#38bdf8 1px, transparent 1px);background-size:20px 20px"></div>
    <div class="container page-header-content" style="position:relative;z-index:2">
        <div class="breadcrumb" style="margin-bottom:4px">
            <a href="{{ route('home') }}" style="display:inline-flex;align-items:center;gap:4px;color:#bae6fd;text-decoration:none">🏠 Home</a>
            <span class="breadcrumb-sep" style="color:#60a5fa">›</span>
            <a href="{{ route('quotations.index') }}" style="color:#bae6fd;text-decoration:none">Quotations &amp; RFQs</a>
            <span class="breadcrumb-sep" style="color:#60a5fa">›</span>
            <span style="font-weight:600;color:#ffffff">New RFQ Request</span>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
            <div>
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px">
                    <span style="background:rgba(56,189,248,0.18);border:1px solid rgba(186,230,253,0.35);padding:2px 9px;border-radius:999px;font-size:0.7rem;font-weight:700;color:#7dd3fc;text-transform:uppercase;letter-spacing:0.05em">
                        💬 Volume-Tiered B2B Pricing
                    </span>
                    <span style="color:#bae6fd;font-size:0.78rem">Container &amp; Pallet Trade Evaluation</span>
                </div>
                <h1 class="page-title" style="color:#ffffff;font-family:var(--font-heading);font-size:clamp(1.5rem,3vw,1.95rem);margin-bottom:4px;letter-spacing:-0.02em">
                    Request for Quotation (RFQ)
                </h1>
                <p class="page-subtitle" style="color:#e0f2fe;font-size:0.88rem;max-width:680px;line-height:1.4;margin:0">
                    Submit bulk frozen seafood requirements for volume-tiered container and pallet pricing.
                </p>
            </div>
            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                <a href="{{ route('quotations.index') }}" class="btn btn-secondary btn-sm" style="background:rgba(255,255,255,0.12);color:#ffffff;border:1px solid rgba(255,255,255,0.25);border-radius:10px;font-weight:600">
                    ← Back to My RFQs
                </a>
            </div>
        </div>
    </div>
</div>

<div style="padding-top:var(--space-8);padding-bottom:var(--space-16);background:#f8fafc;min-height:calc(100vh - 220px)">
    <div class="container" style="max-width:960px">

        <form action="{{ route('quotations.store') }}" method="POST" id="rfqForm">
            @csrf

            <!-- Notice card -->
            <div class="card mb-5" style="background:#eff6ff;border-left:4px solid #2563eb;border-radius:14px;padding:16px 20px;box-shadow:0 2px 8px rgba(37,99,235,0.06)">
                <div style="display:flex;gap:12px;align-items:flex-start">
                    <div style="font-size:1.4rem;line-height:1">💡</div>
                    <div style="font-size:0.88rem;color:#1e3a8a;line-height:1.6">
                        <strong>B2B Trading Advantage:</strong> Our wholesale trade desk will review your volume inquiry and return a competitive price with validity period within 1-2 business days. You can review and accept the quotation online.
                    </div>
                </div>
            </div>

            <!-- Items Section -->
            <div class="card mb-5" style="background:#ffffff;border:1px solid #e2e8f0;border-radius:16px;box-shadow:0 4px 16px rgba(15,23,42,0.03);padding:22px">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;flex-wrap:wrap;gap:10px">
                    <div>
                        <h3 style="font-family:var(--font-heading);font-size:1.15rem;font-weight:800;color:#0f172a;margin:0 0 2px 0">Seafood Items Required</h3>
                        <p style="font-size:0.8rem;color:#64748b;margin:0">Add the products and quantities you need quoted.</p>
                    </div>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="addItemRow()" style="border-radius:10px;font-weight:700;padding:8px 16px;background:#f1f5f9;color:#1e293b;border:1.5px solid #cbd5e1">
                        + Add Another Item
                    </button>
                </div>

                <div id="itemsContainer">
                    <!-- Default Row 0 -->
                    <div class="rfq-item-row" id="rfqRow0">
                        <div class="rfq-grid">
                            <div class="form-group mb-0">
                                <label class="rfq-label">Product <span style="color:#ef4444">*</span></label>
                                <select name="items[0][product_id]" class="rfq-select" required>
                                    <option value="">Select seafood product...</option>
                                    @foreach($products as $prod)
                                        <option value="{{ $prod->id }}">
                                            {{ $prod->name }} ({{ $prod->unit }}) @if($prod->origin) — {{ $prod->origin }} @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-0">
                                <label class="rfq-label">Quantity <span style="color:#ef4444">*</span></label>
                                <input type="number" name="items[0][quantity]" class="rfq-input" min="1" placeholder="e.g. 100" required>
                            </div>
                            <div class="form-group mb-0">
                                <label class="rfq-label">Notes / Sizing Specs (Optional)</label>
                                <input type="text" name="items[0][notes]" class="rfq-input" placeholder="e.g. IQF / Block frozen, 20-30 count">
                            </div>
                            <div class="rfq-remove-col">
                                <button type="button" class="rfq-btn-remove" onclick="removeRow(this)" title="Remove Item">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                    <span class="rfq-remove-text" style="display:none">Remove</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Instructions -->
            <div class="card mb-5" style="background:#ffffff;border:1px solid #e2e8f0;border-radius:16px;box-shadow:0 4px 16px rgba(15,23,42,0.03);padding:22px">
                <div style="margin-bottom:14px">
                    <h3 style="font-family:var(--font-heading);font-size:1.15rem;font-weight:800;color:#0f172a;margin:0 0 2px 0">Commercial &amp; Logistics Notes</h3>
                    <p style="font-size:0.8rem;color:#64748b;margin:0">Provide port, delivery, temperature, or packaging requirements.</p>
                </div>
                <div class="form-group mb-0">
                    <label class="rfq-label">Delivery Terms / Port / Special Requests</label>
                    <textarea name="customer_notes" class="rfq-textarea" rows="4" placeholder="Specify any temperature requirements, preferred cold store delivery location, packaging carton labels, or timeline expectations...">{{ old('customer_notes') }}</textarea>
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;align-items:center;gap:14px;flex-wrap:wrap">
                <a href="{{ route('quotations.index') }}" class="btn btn-secondary" style="padding:12px 24px;border-radius:12px;font-weight:600">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary" style="padding:12px 32px;border-radius:12px;font-weight:700;background:#1d4ed8;box-shadow:0 4px 14px rgba(29,78,216,0.3)">
                    Submit RFQ for Evaluation →
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
.rfq-label {
    display: block;
    font-size: 0.76rem;
    font-weight: 700;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    margin-bottom: 6px;
}
.rfq-select {
    width: 100%;
    height: 44px;
    padding: 8px 36px 8px 12px;
    font-size: 0.88rem;
    font-weight: 600;
    color: #0f172a;
    background-color: #ffffff;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23475569' stroke-width='2.2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 15px;
    border: 1.5px solid #cbd5e1;
    border-radius: 10px;
    outline: none;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
    appearance: none;
    -webkit-appearance: none;
}
.rfq-select:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
}
.rfq-input {
    width: 100%;
    height: 44px;
    padding: 8px 12px;
    font-size: 0.88rem;
    font-weight: 500;
    color: #0f172a;
    background-color: #ffffff;
    border: 1.5px solid #cbd5e1;
    border-radius: 10px;
    outline: none;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
    box-sizing: border-box;
}
.rfq-input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
}
.rfq-textarea {
    width: 100%;
    padding: 12px 14px;
    font-size: 0.9rem;
    line-height: 1.5;
    color: #0f172a;
    background-color: #ffffff;
    border: 1.5px solid #cbd5e1;
    border-radius: 10px;
    outline: none;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
    resize: vertical;
    box-sizing: border-box;
}
.rfq-textarea:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
}
.rfq-item-row {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 16px;
    margin-bottom: 14px;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
}
.rfq-item-row:hover {
    border-color: #cbd5e1;
}
.rfq-grid {
    display: grid;
    grid-template-columns: 2.2fr 1fr 2fr auto;
    gap: 12px;
    align-items: end;
}
.rfq-btn-remove {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    background: #fef2f2;
    border: 1.5px solid #fee2e2;
    color: #ef4444;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.15s ease;
    flex-shrink: 0;
}
.rfq-btn-remove:hover {
    background: #ef4444;
    color: #ffffff;
    border-color: #dc2626;
}

@media (max-width: 768px) {
    .rfq-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    .rfq-remove-col {
        display: flex;
        justify-content: flex-end;
        padding-top: 4px;
    }
    .rfq-btn-remove {
        width: 100%;
        height: 38px;
        gap: 6px;
        font-size: 0.85rem;
        font-weight: 700;
    }
    .rfq-remove-text {
        display: inline !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
let rowIndex = 1;
const productsJson = @json($products);

function addItemRow() {
    const container = document.getElementById('itemsContainer');
    const row = document.createElement('div');
    row.className = 'rfq-item-row';

    let options = '<option value="">Select seafood product...</option>';
    productsJson.forEach(p => {
        options += `<option value="${p.id}">${p.name} (${p.unit}) ${p.origin ? '— ' + p.origin : ''}</option>`;
    });

    row.innerHTML = `
        <div class="rfq-grid">
            <div class="form-group mb-0">
                <label class="rfq-label">Product <span style="color:#ef4444">*</span></label>
                <select name="items[${rowIndex}][product_id]" class="rfq-select" required>
                    ${options}
                </select>
            </div>
            <div class="form-group mb-0">
                <label class="rfq-label">Quantity <span style="color:#ef4444">*</span></label>
                <input type="number" name="items[${rowIndex}][quantity]" class="rfq-input" min="1" placeholder="e.g. 100" required>
            </div>
            <div class="form-group mb-0">
                <label class="rfq-label">Notes / Sizing Specs (Optional)</label>
                <input type="text" name="items[${rowIndex}][notes]" class="rfq-input" placeholder="e.g. IQF / Block frozen, 20-30 count">
            </div>
            <div class="rfq-remove-col">
                <button type="button" class="rfq-btn-remove" onclick="removeRow(this)" title="Remove Item">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                    <span class="rfq-remove-text" style="display:none">Remove</span>
                </button>
            </div>
        </div>
    `;

    container.appendChild(row);
    rowIndex++;
}

function removeRow(btn) {
    const rows = document.querySelectorAll('.rfq-item-row');
    if (rows.length <= 1) {
        if (typeof showGlobalToast === 'function') {
            showGlobalToast('At least one product item is required in the quotation.', 'error');
        } else {
            alert('At least one product item is required in the quotation.');
        }
        return;
    }
    btn.closest('.rfq-item-row').remove();
}
</script>
@endpush
