@extends(request()->is('admin/*') ? 'layouts.admin' : 'layouts.app')

@section('title', __t('invoice.title', 'INVOICE') . ' #' . $order->order_number . ' — MST Import and Export SDN BHD')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
    <style>
        :root {
            --primary: #0a1929;
            --primary-dark: #06111c;
            --accent: #0d7377;
            --teal: #14a0a5;
            --teal-light: #e0f2fe;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
        }

        .invoice-body-reset {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: var(--gray-800);
            line-height: 1.45;
            -webkit-font-smoothing: antialiased;
            width: 100%;
        }

        /* Top Action Bar (hidden when printing) */
        .no-print-bar {
            max-width: 880px;
            margin: 0 auto 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            font-weight: 700;
            font-size: 0.875rem;
            border-radius: 8px;
            text-decoration: none;
            cursor: pointer;
            border: 1px solid transparent;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent) 0%, var(--teal) 100%);
            color: #fff;
            box-shadow: 0 4px 12px rgba(13, 115, 119, 0.25);
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #095457 0%, var(--accent) 100%);
            transform: translateY(-1px);
        }

        .btn-outline {
            background: #fff;
            border-color: var(--gray-300);
            color: var(--gray-700);
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .btn-outline:hover {
            background: var(--gray-50);
            border-color: var(--gray-400);
        }

        /* Invoice Container */
        .invoice-container {
            max-width: 880px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
            padding: 40px 48px;
            border: 1px solid var(--gray-200);
        }

        /* Invoice Header */
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 24px;
            padding-bottom: 24px;
            border-bottom: 2px solid var(--gray-200);
            margin-bottom: 24px;
        }

        .company-brand-box {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            flex: 1;
        }

        .company-logo-img {
            width: 72px;
            height: 72px;
            object-fit: contain;
            border-radius: 10px;
            flex-shrink: 0;
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            padding: 4px;
        }

        .company-brand-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .company-chinese-name {
            font-family: 'Noto Sans SC', 'Microsoft YaHei', sans-serif;
            font-size: 1.25rem;
            font-weight: 900;
            color: var(--primary);
            letter-spacing: 0.08em;
            line-height: 1.2;
        }

        .company-english-name {
            font-family: 'Outfit', sans-serif;
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--primary);
            letter-spacing: 0.02em;
            line-height: 1.25;
            text-transform: uppercase;
        }

        .company-reg-number {
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--accent);
            letter-spacing: 0.05em;
            margin-bottom: 4px;
        }

        .company-address-lines {
            font-size: 0.78rem;
            color: var(--gray-600);
            line-height: 1.5;
            font-weight: 500;
        }

        .company-contact-line {
            font-size: 0.78rem;
            color: var(--gray-700);
            font-weight: 600;
            margin-top: 2px;
        }

        /* Invoice Meta */
        .invoice-meta-box {
            text-align: right;
            min-width: 220px;
        }

        .invoice-main-title {
            font-family: 'Outfit', sans-serif;
            font-size: 2.2rem;
            font-weight: 900;
            color: var(--primary);
            letter-spacing: 0.08em;
            text-transform: uppercase;
            line-height: 1;
            margin-bottom: 12px;
        }

        .meta-table {
            margin-left: auto;
            border-collapse: collapse;
            font-size: 0.82rem;
        }

        .meta-table td {
            padding: 3px 0;
        }

        .meta-table td.meta-label {
            color: var(--gray-500);
            font-weight: 600;
            text-align: left;
            padding-right: 12px;
            white-space: nowrap;
        }

        .meta-table td.meta-val {
            color: var(--gray-800);
            font-weight: 700;
            text-align: right;
            white-space: nowrap;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 9999px;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-top: 6px;
        }

        .badge-paid {
            background: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }

        .badge-pending {
            background: #fef9c3;
            color: #854d0e;
            border: 1px solid #fef08a;
        }

        /* Customer Section (Bill To #) */
        .invoice-billing-grid {
            display: grid;
            grid-template-columns: 1.4fr 1fr;
            gap: 24px;
            margin-bottom: 24px;
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            border-radius: 10px;
            padding: 16px 20px;
        }

        .bill-to-header {
            font-family: 'Outfit', sans-serif;
            font-size: 0.85rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--primary);
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .bill-to-company {
            font-size: 0.95rem;
            font-weight: 800;
            color: var(--gray-800);
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .bill-to-address {
            font-size: 0.8rem;
            color: var(--gray-600);
            line-height: 1.5;
            margin-bottom: 6px;
        }

        .bill-to-detail {
            font-size: 0.8rem;
            color: var(--gray-700);
            margin-top: 2px;
        }

        .bill-to-detail strong {
            color: var(--gray-800);
            font-weight: 700;
        }

        .fulfillment-col {
            border-left: 1px dashed var(--gray-300);
            padding-left: 20px;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .items-table thead th {
            background: var(--primary);
            color: #ffffff;
            font-family: 'Outfit', sans-serif;
            font-size: 0.74rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 10px 12px;
            border: 1px solid var(--primary);
        }

        .items-table thead th:first-child {
            border-top-left-radius: 6px;
        }
        .items-table thead th:last-child {
            border-top-right-radius: 6px;
        }

        .items-table tbody td {
            padding: 11px 12px;
            border: 1px solid var(--gray-200);
            font-size: 0.84rem;
            color: var(--gray-800);
            vertical-align: top;
        }

        .items-table tbody tr:nth-child(even) {
            background: #fafbfc;
        }

        .col-no { width: 5%; text-align: center; font-weight: 600; color: var(--gray-500); }
        .col-desc { width: 44%; }
        .col-qty { width: 14%; text-align: center; font-weight: 700; }
        .col-price { width: 12%; text-align: right; font-weight: 600; }
        .col-disc { width: 10%; text-align: right; color: var(--gray-500); }
        .col-amount { width: 15%; text-align: right; font-weight: 800; color: var(--primary); }

        .item-name {
            font-weight: 700;
            color: var(--gray-800);
            text-transform: uppercase;
            font-size: 0.84rem;
        }

        .item-spec {
            font-size: 0.72rem;
            color: var(--gray-500);
            margin-top: 2px;
        }

        /* Summary & Words Section */
        .summary-words-grid {
            display: grid;
            grid-template-columns: 1.3fr 1fr;
            gap: 20px;
            margin-bottom: 24px;
            align-items: start;
        }

        .words-box {
            background: var(--gray-50);
            border: 1.5px solid var(--gray-200);
            border-radius: 8px;
            padding: 12px 16px;
        }

        .words-label {
            font-size: 0.72rem;
            font-weight: 800;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            display: block;
            margin-bottom: 4px;
        }

        .words-val {
            font-family: 'Outfit', sans-serif;
            font-size: 0.95rem;
            font-weight: 800;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 0.02em;
            line-height: 1.3;
        }

        .totals-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.84rem;
            border: 1px solid var(--gray-200);
            border-radius: 8px;
            overflow: hidden;
        }

        .totals-table tr td {
            padding: 8px 14px;
            border-bottom: 1px solid var(--gray-200);
        }

        .totals-table tr td:first-child {
            color: var(--gray-600);
            font-weight: 600;
        }

        .totals-table tr td:last-child {
            text-align: right;
            font-weight: 700;
            color: var(--gray-800);
        }

        .totals-table tr.total-row {
            background: #f0fdf4;
            border-top: 2px solid #bbf7d0;
        }

        .totals-table tr.total-row td {
            padding: 10px 14px;
            font-size: 1.05rem;
            font-weight: 900;
            color: var(--accent);
            border-bottom: none;
        }

        .totals-table tr.total-row td:first-child {
            font-family: 'Outfit', sans-serif;
            color: var(--primary);
        }

        /* Important Note & Declarations */
        .notes-declaration-grid {
            display: grid;
            grid-template-columns: 1.4fr 1fr;
            gap: 20px;
            margin-bottom: 32px;
            padding-top: 12px;
            border-top: 1px dashed var(--gray-300);
        }

        .note-card {
            font-size: 0.72rem;
            color: var(--gray-600);
            line-height: 1.55;
        }

        .note-card-title {
            font-size: 0.75rem;
            font-weight: 800;
            color: var(--gray-800);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 6px;
        }

        .note-card ol {
            padding-left: 14px;
        }

        .note-card li {
            margin-bottom: 4px;
        }

        .note-card strong {
            color: var(--gray-800);
        }

        .declaration-card {
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 10px;
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            border-radius: 8px;
            padding: 14px 16px;
            text-align: center;
        }

        .declaration-text {
            font-size: 0.7rem;
            font-weight: 800;
            color: var(--gray-700);
            letter-spacing: 0.05em;
            text-transform: uppercase;
            line-height: 1.35;
        }

        /* Signatures Section */
        .signatures-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            margin-top: 40px;
            padding-top: 20px;
        }

        .signature-box {
            text-align: center;
        }

        .signature-line {
            height: 1px;
            background: var(--gray-400);
            margin-bottom: 8px;
        }

        .signature-title {
            font-family: 'Outfit', sans-serif;
            font-size: 0.78rem;
            font-weight: 800;
            color: var(--gray-700);
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        /* Responsive Screen Styles for Mobile & Tablet */
        @media (max-width: 860px) {
            .invoice-container {
                padding: 20px 14px !important;
                border-radius: 8px !important;
            }
            .invoice-header {
                flex-direction: column !important;
                gap: 16px !important;
                align-items: flex-start !important;
            }
            .invoice-header-right,
            .invoice-meta-box,
            .meta-table {
                width: 100% !important;
            }
            .invoice-billing-grid {
                grid-template-columns: 1fr !important;
                gap: 14px !important;
                padding: 14px !important;
            }
            .fulfillment-col {
                border-left: none !important;
                border-top: 1px dashed var(--gray-300) !important;
                padding-left: 0 !important;
                padding-top: 14px !important;
            }
            .summary-words-grid {
                grid-template-columns: 1fr !important;
                gap: 16px !important;
            }
            .notes-declaration-grid {
                grid-template-columns: 1fr !important;
                gap: 16px !important;
            }
            .signatures-grid {
                grid-template-columns: 1fr !important;
                gap: 28px !important;
                margin-top: 24px !important;
            }
        }

        /* Print Specific Styles */
        @media print {
            @page {
                size: A4 portrait;
                margin: 10mm 12mm;
            }

            .admin-sidebar,
            .admin-global-header,
            .admin-mobile-header,
            .admin-sidebar-backdrop,
            .no-print-bar,
            .flash-container,
            header,
            nav,
            footer {
                display: none !important;
            }

            body {
                background: #ffffff !important;
                color: #000000 !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .admin-main {
                margin: 0 !important;
                margin-left: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                background: #ffffff !important;
            }

            .invoice-container {
                max-width: 100% !important;
                box-shadow: none !important;
                border: none !important;
                border-radius: 0 !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .items-table thead th {
                background: #0a1929 !important;
                color: #ffffff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .totals-table tr.total-row {
                background: #f0fdf4 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .words-box, .invoice-billing-grid, .declaration-card {
                background: #f8fafc !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .signatures-grid {
                page-break-inside: avoid;
            }

            .notes-declaration-grid {
                page-break-inside: avoid;
            }
        }
    </style>
@endpush
@section('content')
<div class="invoice-body-reset">

<!-- Interactive Header Action Bar (Screen Only) -->
<div class="no-print-bar">
    <a href="{{ url()->previous() ?: route('admin.orders.index') }}" class="btn btn-outline">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
        <span>{{ __t('invoice.back', 'Back') }}</span>
    </a>
    <div style="display: flex; gap: 8px;">
        <button onclick="window.print()" class="btn btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
            <span>{{ __t('invoice.print_pdf', 'Print Invoice / Save as PDF') }}</span>
        </button>
    </div>
</div>

<div class="invoice-container">

    <!-- 1. Header with Company Branding & Invoice Meta -->
    <div class="invoice-header">
        <div class="company-brand-box">
            <img src="{{ asset('images/logo.webp') }}" alt="MST Import and Export" class="company-logo-img">
            <div class="company-brand-info">
                <div class="company-chinese-name">{{ __t('invoice.company_chinese', '鎂嘉国际贸易有限公司') }}</div>
                <div class="company-english-name">MST IMPORT AND EXPORT SDN BHD</div>
                <div class="company-reg-number">202401053472</div>
                <div class="company-address-lines">
                    7 JALAN SILC 2/18<br>
                    KAWASAN PERINDUSTRIAN SILC<br>
                    79200 NUSAJAYA
                </div>
                <div class="company-contact-line">
                    Tel No. : +60132800168 / +601112710260
                </div>
            </div>
        </div>

        <div class="invoice-meta-box">
            <div class="invoice-main-title">{{ __t('invoice.title', 'INVOICE') }}</div>
            <table class="meta-table">
                <tr>
                    <td class="meta-label">{{ __t('invoice.meta_no', 'No. :') }}</td>
                    <td class="meta-val">{{ $order->order_number }}</td>
                </tr>
                <tr>
                    <td class="meta-label">{{ __t('invoice.meta_date', 'Date :') }}</td>
                    <td class="meta-val">{{ $order->created_at->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td class="meta-label">{{ __t('invoice.meta_po_ref', 'P/O Ref. :') }}</td>
                    <td class="meta-val">{{ $order->po_ref ?? $order->order_number }}</td>
                </tr>
                <tr>
                    <td class="meta-label">{{ __t('invoice.meta_terms', 'Terms :') }}</td>
                    <td class="meta-val">
                        @if($order->payment_method === 'cash_on_delivery')
                            {{ __t('invoice.terms_cod', 'C.O.D.') }}
                        @elseif($order->payment_status === 'paid')
                            {{ __t('invoice.terms_paid_online', 'PAID (ONLINE)') }}
                        @else
                            {{ __t('invoice.terms_cod', 'C.O.D.') }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="meta-label">{{ __t('invoice.meta_page', 'Page :') }}</td>
                    <td class="meta-val">1</td>
                </tr>
            </table>

            <div style="margin-top: 4px;">
                <span class="status-badge {{ $order->payment_status === 'paid' ? 'badge-paid' : 'badge-pending' }}">
                    ● {{ ucfirst($order->payment_status) }}
                </span>
            </div>
        </div>
    </div>

    <!-- 2. Bill To # (Customer Details) -->
    <div class="invoice-billing-grid">
        <div class="bill-to-col">
            <div class="bill-to-header">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                <span>{{ __t('invoice.bill_to', 'Bill To #') }}</span>
            </div>
            <div class="bill-to-company">
                {{ $order->user && $order->user->company_name ? $order->user->company_name : $order->customer_name }}
            </div>
            <div class="bill-to-address">
                @if($order->shipping_address && is_array($order->shipping_address))
                    {{ $order->shipping_address['address'] ?? '' }}<br>
                    {{ $order->shipping_address['city'] ?? '' }} {{ $order->shipping_address['postcode'] ?? '' }} {{ $order->shipping_address['state'] ?? '' }}, Malaysia
                @elseif($order->user && $order->user->address)
                    {{ $order->user->address }}<br>
                    {{ $order->user->city ?? '' }} {{ $order->user->postcode ?? '' }} {{ $order->user->state ?? '' }}, Malaysia
                @else
                    {{ __t('invoice.store_pickup_address', 'Store Self-Collection (SILC Cold-Chain Facility)') }}
                @endif
            </div>
            <div class="bill-to-detail">
                <strong>{{ __t('invoice.attention', 'Attention :') }}</strong> {{ $order->customer_name }}
            </div>
            <div class="bill-to-detail">
                <strong>{{ __t('invoice.tel', 'Tel :') }}</strong> {{ $order->customer_phone ?? ($order->user->phone ?? '—') }}
            </div>
        </div>

        <div class="fulfillment-col">
            <div class="bill-to-header">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                <span>{{ __t('invoice.fulfillment_logistics', 'Fulfillment & Logistics') }}</span>
            </div>
            <div class="bill-to-detail" style="margin-top: 4px;">
                <strong>{{ __t('invoice.method', 'Method:') }}</strong> {{ $order->fulfillment_type === 'self_collection' ? __t('invoice.method_pickup', 'Store Self-Collection') : __t('invoice.method_delivery', 'Cold-Chain Delivery') }}
            </div>
            <div class="bill-to-detail">
                <strong>{{ __t('invoice.account_type', 'Account Type:') }}</strong> {{ ucfirst($order->customer_group ?? 'Retail') }}
            </div>
            @if($order->customer_email)
            <div class="bill-to-detail">
                <strong>{{ __t('invoice.email', 'Email:') }}</strong> {{ $order->customer_email }}
            </div>
            @endif
            @if($order->customer_notes)
            <div class="bill-to-detail" style="margin-top: 4px; font-style: italic; color: var(--gray-600);">
                <strong>{{ __t('invoice.notes', 'Notes:') }}</strong> "{{ $order->customer_notes }}"
            </div>
            @endif
        </div>
    </div>

    <!-- 3. Items Table -->
    <div class="table-wrapper" style="width:100%;overflow-x:auto;-webkit-overflow-scrolling:touch;margin-bottom:18px">
        <table class="items-table">
            <thead>
                <tr>
                    <th class="col-no">{{ __t('invoice.col_no', 'No.') }}</th>
                    <th class="col-desc">{{ __t('invoice.col_desc', 'Description') }}</th>
                    <th class="col-qty">{{ __t('invoice.col_qty', 'Qty') }}</th>
                    <th class="col-price">{{ __t('invoice.col_price', 'Price') }}</th>
                    <th class="col-disc">{{ __t('invoice.col_disc', 'Discount') }}</th>
                    <th class="col-amount">{{ __t('invoice.col_amount', 'Amount') }}<br><span style="font-size:0.65rem;font-weight:600;opacity:0.85">RM</span></th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $index => $item)
                <tr>
                    <td class="col-no">{{ $index + 1 }}</td>
                    <td class="col-desc">
                        <div class="item-name">{{ $item->product_name }}</div>
                        @if($item->product && $item->product->weight)
                            <div class="item-spec">{{ __t('invoice.spec', 'Spec:') }} {{ $item->product->weight }} {{ $item->product->unit ?? 'KG' }}</div>
                        @endif
                    </td>
                    <td class="col-qty">
                        {{ number_format($item->quantity, 2) }} {{ strtoupper($item->product->unit ?? 'KG') }}
                    </td>
                    <td class="col-price">{{ number_format($item->unit_price, 2) }}</td>
                    <td class="col-disc">0.00</td>
                    <td class="col-amount">{{ number_format($item->subtotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- 4. Ringgit In Words & Totals Summary -->
    <div class="summary-words-grid">
        <div class="words-box">
            <span class="words-label">{{ __t('invoice.ringgit_label', "RINGGIT M'SIA") }}</span>
            <div class="words-val">
                {{ \App\Helpers\InvoiceHelper::amountInWords($order->total) }}
            </div>
        </div>

        <div>
            <table class="totals-table">
                <tr>
                    <td>{{ __t('invoice.subtotal', 'Total') }}</td>
                    <td>{{ number_format($order->subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td>{{ __t('invoice.rounding_adj', 'Rounding Adj.') }}</td>
                    <td>0.00</td>
                </tr>
                @if($order->shipping_fee > 0)
                <tr>
                    <td>{{ __t('invoice.shipping_logistics', 'Shipping & Logistics') }}</td>
                    <td>{{ number_format($order->shipping_fee, 2) }}</td>
                </tr>
                @endif
                <tr class="total-row">
                    <td>{{ __t('invoice.grand_total', 'Grand Total') }}</td>
                    <td>{{ number_format($order->total, 2) }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- 5. Important Notes & Declarations -->
    <div class="notes-declaration-grid">
        <div class="note-card">
            <div class="note-card-title">{{ __t('invoice.important_note', 'Important Note:') }}</div>
            <ol>
                <li>{{ __t('invoice.note_1', 'Please notice us of discrepancy if any, within 7 days , otherwise this invoice will be considered confirmed.') }}</li>
                <li>{{ __t('invoice.note_2', 'Goods sold and delivered are not returnable and exchangeable. Otherwise a cancellation fee of 20% on purchase price will be imposed.') }}</li>
                <li>{{ __t('invoice.note_3', 'All cheques to be crossed & made payable to "MST IMPORT AND EXPORT SDN BHD" or to our MAYBANK A/C NO:551342155505 and email the bank in slip to mikatrading15@gmail.com or 011-14360109.') }}</li>
                <li>{{ __t('invoice.note_4', 'Interest will be charged at 1.5% per month on overdue payments.') }}</li>
            </ol>
        </div>

        <div class="declaration-card">
            <div class="declaration-text">
                {{ __t('invoice.goods_received_condition', 'GOODS RECEIVED IN GOOD CONDITION') }}
            </div>
            <div style="height: 1px; background: var(--gray-200); width: 60%; margin: 0 auto;"></div>
            <div class="declaration-text" style="color: var(--accent);">
                {{ __t('invoice.goods_sold_condition', 'GOODS SOLD ARE NEITHER RETURNABLE NOR REFUNDABLE') }}
            </div>
        </div>
    </div>

    <!-- 6. Driver & Customer Signatures -->
    <div class="signatures-grid">
        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-title">{{ __t('invoice.driver_signature', 'DRIVER SIGNATURE') }}</div>
        </div>
        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-title">{{ __t('invoice.customer_signature', 'CUSTOMER SIGNATURE & STAMP') }}</div>
        </div>
    </div>
</div>
</div>
@endsection
