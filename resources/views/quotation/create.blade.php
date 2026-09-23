@extends('layouts.app')
@section('title', __t('rfq.page_title', 'Request a Quotation (RFQ) — ' . ($settings['store_name'] ?? 'MST Import and Export Sdn Bhd')))

@section('content')
<!-- Page Header -->
<div class="page-header" style="padding-top:calc(75px + var(--space-6));background:linear-gradient(135deg, #091a36 0%, #0f274a 45%, #1e3a8a 100%);color:#ffffff;border-bottom:1px solid #1e3a8a;padding-bottom:var(--space-8);position:relative;overflow:hidden">
    <div style="position:absolute;inset:0;opacity:0.07;background-image:radial-gradient(#38bdf8 1px, transparent 1px);background-size:20px 20px"></div>
    <div class="container page-header-content" style="position:relative;z-index:2">
        <div class="breadcrumb" style="margin-bottom:6px">
            <a href="{{ route('home') }}" style="display:inline-flex;align-items:center;gap:4px;color:#bae6fd;text-decoration:none">🏠 @t('nav.home', 'Home')</a>
            <span class="breadcrumb-sep" style="color:#60a5fa">›</span>
            <a href="{{ route('quotations.index') }}" style="color:#bae6fd;text-decoration:none">@t('nav.my_rfqs', 'Quotations & RFQs')</a>
            <span class="breadcrumb-sep" style="color:#60a5fa">›</span>
            <span style="font-weight:600;color:#ffffff">@t('rfq.title', 'New RFQ Request')</span>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px">
            <div>
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;flex-wrap:wrap">
                    <span style="background:rgba(56,189,248,0.18);border:1px solid rgba(186,230,253,0.35);padding:3px 11px;border-radius:999px;font-size:0.72rem;font-weight:700;color:#7dd3fc;text-transform:uppercase;letter-spacing:0.05em">
                        💬 @t('rfq.tiered_pricing_badge', 'Volume-Tiered B2B Pricing')
                    </span>
                    <span style="color:#bae6fd;font-size:0.8rem">@t('rfq.evaluation_badge', 'Container & Pallet Trade Evaluation')</span>
                </div>
                <h1 class="page-title" style="color:#ffffff;font-family:var(--font-heading);font-size:clamp(1.6rem,3.5vw,2.15rem);margin-bottom:6px;letter-spacing:-0.02em;line-height:1.2">
                    @t('rfq.title', 'Request for Quotation (RFQ)')
                </h1>
                <p class="page-subtitle" style="color:#e0f2fe;font-size:0.92rem;max-width:720px;line-height:1.55;margin:0">
                    @t('rfq.subtitle', 'Submit product requirements for volume-tiered wholesale and customised sourcing quotation.')
                </p>
            </div>
            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                <a href="{{ route('quotations.index') }}" class="btn btn-secondary btn-sm" style="background:rgba(255,255,255,0.12);color:#ffffff;border:1px solid rgba(255,255,255,0.25);border-radius:10px;font-weight:600;padding:8px 16px;display:inline-flex;align-items:center;gap:6px">
                    <span>←</span>
                    <span>@t('rfq.back_to_rfqs', 'Back to My RFQs')</span>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="rfq-page-body">
    <div class="container" style="max-width:1040px">

        <!-- 1. Informational Notice Card -->
        <div class="rfq-notice-card">
            <div class="rfq-notice-icon">💡</div>
            <div class="rfq-notice-text">
                <div class="rfq-notice-title">@t('rfq.notice_title', 'B2B Sourcing & Trading Advantage')</div>
                <div class="rfq-notice-desc">
                    @t('rfq.notice_desc', 'Our commercial team reviews your volume inquiry and product specifications, providing a competitive quotation within 1-2 business days. Can\'t find a product in our list? You can specify custom products in the notes below or contact our sourcing team.')
                </div>
            </div>
        </div>

        <form action="{{ route('quotations.store') }}" method="POST" id="rfqForm">
            @csrf

            <!-- 2. Products Required Section -->
            <div class="rfq-main-card">
                <div class="rfq-card-header">
                    <div>
                        <div class="rfq-card-tag">📦 @t('rfq.step_1_tag', 'STEP 1: PRODUCT SELECTION')</div>
                        <h2 class="rfq-card-title">@t('rfq.products_required_title', 'Products Required')</h2>
                        <p class="rfq-card-subtitle">
                            @t('rfq.products_required_sub', 'Select category, product, quantity and sizing notes for each required item.')
                        </p>
                    </div>
                    <button type="button" class="btn-add-row" onclick="addItemRow()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>@t('rfq.add_item_btn', 'Add Another Item')</span>
                    </button>
                </div>

                <div id="itemsContainer" class="rfq-items-list">
                    <!-- Default Item Card 0 -->
                    <div class="rfq-item-card" id="rfqRow0" data-row-id="0">
                        <div class="rfq-item-card-header">
                            <div class="rfq-item-card-header-left">
                                <div class="rfq-item-pill">
                                    <span class="rfq-item-dot"></span>
                                    <span class="rfq-item-num-text">Item #1</span>
                                </div>
                                <span class="rfq-item-header-guide">Product Specifications & Volume</span>
                            </div>
                            <button type="button" class="rfq-btn-remove-clean" onclick="removeRow(this)" title="Remove Item" aria-label="Remove Item">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                </svg>
                                <span>Remove</span>
                            </button>
                        </div>

                        <div class="rfq-item-card-body">
                            <!-- Primary Row: Category, Product, Quantity -->
                            <div class="rfq-fields-row-primary">
                                <!-- 1. Searchable Category Dropdown -->
                                <div class="rfq-field-group rfq-field-cat-col">
                                    <label class="rfq-label">
                                        <span class="rfq-label-text">@t('rfq.col_category', 'Category')</span>
                                    </label>
                                    <div class="rfq-searchable-select" id="cat_select_wrap_0" data-type="category" data-row="0">
                                        <input type="hidden" class="rfq-cat-val" id="cat_val_0" value="{{ $selectedProduct ? $selectedProduct->category_id : '' }}">
                                        <button type="button" class="rfq-searchable-trigger" id="cat_trigger_0" aria-haspopup="listbox" aria-expanded="false">
                                            <span class="rfq-searchable-trigger-text" id="cat_text_0">
                                                @if($selectedProduct && $selectedProduct->category)
                                                    {{ $selectedProduct->category->name }}
                                                @else
                                                    @t('rfq.all_categories_opt', 'All Categories (All Items)')
                                                @endif
                                            </span>
                                            <svg class="rfq-searchable-chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="6 9 12 15 18 9"></polyline>
                                            </svg>
                                        </button>
                                        <div class="rfq-searchable-panel" id="cat_panel_0">
                                            <div class="rfq-searchable-searchbox">
                                                <svg class="rfq-search-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                    <circle cx="11" cy="11" r="8"></circle>
                                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                                </svg>
                                                <input type="text" class="rfq-searchable-input" placeholder="@t('rfq.search_category', 'Search category...')" autocomplete="off">
                                                <button type="button" class="rfq-search-clear" style="display:none;" title="Clear search">✕</button>
                                            </div>
                                            <div class="rfq-searchable-options-list" role="listbox">
                                                <div class="rfq-searchable-option {{ empty($selectedProduct?->category_id) ? 'selected' : '' }}" data-value="" data-label="@t('rfq.all_categories_opt', 'All Categories (All Items)')">
                                                    <span class="rfq-option-label">🌐 @t('rfq.all_categories_opt', 'All Categories (All Items)')</span>
                                                    <span class="rfq-option-check">✓</span>
                                                </div>
                                                @foreach($categories as $cat)
                                                    <div class="rfq-searchable-option {{ ($selectedProduct && $selectedProduct->category_id == $cat->id) ? 'selected' : '' }}" data-value="{{ $cat->id }}" data-label="{{ $cat->name }}">
                                                        <span class="rfq-option-label">{{ $cat->name }}</span>
                                                        <span class="rfq-option-check">✓</span>
                                                    </div>
                                                @endforeach
                                                <div class="rfq-searchable-empty" style="display:none;">@t('rfq.no_category_match', 'No matching categories found')</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- 2. Searchable Product Dropdown -->
                                <div class="rfq-field-group rfq-field-prod-col">
                                    <label class="rfq-label">
                                        <span class="rfq-label-text">@t('rfq.col_product', 'Product')</span>
                                        <span class="req-star">*</span>
                                    </label>
                                    <div class="rfq-searchable-select" id="prod_select_wrap_0" data-type="product" data-row="0">
                                        <input type="hidden" name="items[0][product_id]" class="rfq-prod-val" id="prod_val_0" value="{{ $selectedProduct ? $selectedProduct->id : '' }}" required>
                                        <button type="button" class="rfq-searchable-trigger {{ empty($selectedProduct) ? 'is-placeholder' : '' }}" id="prod_trigger_0" aria-haspopup="listbox" aria-expanded="false">
                                            <span class="rfq-searchable-trigger-text" id="prod_text_0">
                                                @if($selectedProduct)
                                                    {{ $selectedProduct->name }} ({{ $selectedProduct->unit }}) @if($selectedProduct->origin) — {{ $selectedProduct->origin }} @endif
                                                @else
                                                    @t('rfq.select_product_placeholder', 'Select product from catalogue...')
                                                @endif
                                            </span>
                                            <svg class="rfq-searchable-chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="6 9 12 15 18 9"></polyline>
                                            </svg>
                                        </button>
                                        <div class="rfq-searchable-panel" id="prod_panel_0">
                                            <div class="rfq-searchable-searchbox">
                                                <svg class="rfq-search-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                    <circle cx="11" cy="11" r="8"></circle>
                                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                                </svg>
                                                <input type="text" class="rfq-searchable-input" placeholder="@t('rfq.search_product', 'Search product by name, origin, unit...')" autocomplete="off">
                                                <button type="button" class="rfq-search-clear" style="display:none;" title="Clear search">✕</button>
                                            </div>
                                            <div class="rfq-searchable-options-list" role="listbox">
                                                @foreach($products as $prod)
                                                    <div class="rfq-searchable-option {{ ($selectedProduct && $selectedProduct->id == $prod->id) ? 'selected' : '' }}"
                                                         data-value="{{ $prod->id }}"
                                                         data-category-id="{{ $prod->category_id }}"
                                                         data-label="{{ $prod->name }} ({{ $prod->unit }}) {{ $prod->origin ? '— ' . $prod->origin : '' }}"
                                                         data-search="{{ strtolower($prod->name . ' ' . $prod->unit . ' ' . $prod->origin . ' ' . ($prod->category?->name ?? '')) }}">
                                                        <div class="rfq-product-item-info">
                                                            <div class="rfq-product-item-name">{{ $prod->name }}</div>
                                                            <div class="rfq-product-item-meta">
                                                                <span class="rfq-badge-unit">{{ $prod->unit }}</span>
                                                                @if($prod->origin)
                                                                    <span class="rfq-badge-origin">📍 {{ $prod->origin }}</span>
                                                                @endif
                                                                @if($prod->category)
                                                                    <span class="rfq-badge-cat">{{ $prod->category->name }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <span class="rfq-option-check">✓</span>
                                                    </div>
                                                @endforeach
                                                <div class="rfq-searchable-empty" style="display:none;">@t('rfq.no_product_match', 'No matching products found')</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- 3. Quantity Input -->
                                <div class="rfq-field-group rfq-field-qty-col">
                                    <label class="rfq-label" for="qty_input_0">
                                        <span class="rfq-label-text">@t('rfq.col_quantity', 'Quantity')</span>
                                        <span class="req-star">*</span>
                                    </label>
                                    <input type="number" name="items[0][quantity]" id="qty_input_0" class="rfq-input" min="1" placeholder="e.g. 50" required>
                                </div>
                            </div>

                            <!-- Row 2: Specifications & Sizing Notes (Full Width) -->
                            <div class="rfq-fields-row-notes">
                                <div class="rfq-field-group" style="width:100%">
                                    <label class="rfq-label" for="notes_input_0">
                                        <span class="rfq-label-text">@t('rfq.col_notes', 'Notes / Sizing Specs (Optional)')</span>
                                        <span class="rfq-label-hint">Special processing, target size, packaging or FOB terms</span>
                                    </label>
                                    <input type="text" name="items[0][notes]" id="notes_input_0" class="rfq-input" placeholder="@t('rfq.notes_placeholder', 'e.g. IQF / Block frozen, 20-30 count, 1kg pack, custom labeling')">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Custom Sourcing Tip Inside Items Card -->
                <div class="rfq-sourcing-tip-box">
                    <div class="rfq-tip-icon-box">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </div>
                    <div class="rfq-tip-content">
                        <strong class="rfq-tip-title">@t('rfq.unlisted_tip_title', 'Need a custom cut, specific origin, or unlisted product?')</strong>
                        <p class="rfq-tip-desc">@t('rfq.unlisted_tip_desc', 'You can type your specifications in the Notes field above or describe your complete sourcing requirements in the Commercial Notes section below.')</p>
                    </div>
                </div>
            </div>

            <!-- 3. Commercial & Logistics Notes Card -->
            <div class="rfq-main-card" style="margin-top:28px;">
                <div class="rfq-card-header" style="margin-bottom:18px;">
                    <div>
                        <div class="rfq-card-tag">🚚 @t('rfq.step_2_tag', 'STEP 2: LOGISTICS & SPECIFICATIONS')</div>
                        <h2 class="rfq-card-title">@t('rfq.commercial_notes_title', 'Commercial & Logistics Notes')</h2>
                        <p class="rfq-card-subtitle">
                            @t('rfq.commercial_notes_sub', 'Provide port, delivery, temperature, packaging, or target timeline requirements.')
                        </p>
                    </div>
                </div>

                <!-- Quick Helper Chips -->
                <div class="rfq-chips-container">
                    <span class="rfq-chips-label">@t('rfq.quick_tags_label', 'Quick Add:')</span>
                    <button type="button" class="rfq-chip" onclick="appendNote('Delivery to Johor Bahru / Singapore')">+ Delivery to JB / SG</button>
                    <button type="button" class="rfq-chip" onclick="appendNote('Port Klang / Pasir Gudang Port FOB')">+ Port Klang / Pasir Gudang</button>
                    <button type="button" class="rfq-chip" onclick="appendNote('Keep strictly frozen at -18°C IQF')">+ -18°C IQF Storage</button>
                    <button type="button" class="rfq-chip" onclick="appendNote('Custom carton packaging required')">+ Custom Packaging</button>
                    <button type="button" class="rfq-chip" onclick="appendNote('FCL Full Container Load requirement')">+ FCL Container</button>
                </div>

                <div class="form-group mb-0">
                    <label class="rfq-label" for="customer_notes">
                        <span class="rfq-label-text">@t('rfq.delivery_terms_label', 'Delivery Terms / Port / Special Requests')</span>
                    </label>
                    <textarea name="customer_notes" id="customer_notes" class="rfq-textarea" rows="4" placeholder="@t('rfq.textarea_placeholder', 'Specify temperature requirements, preferred cold store delivery location, packaging carton labels, target arrival timeline, or payment terms...')">{{ old('customer_notes') }}</textarea>
                </div>
            </div>

            <!-- 4. Submit & Action Buttons -->
            <div class="rfq-action-bar">
                <a href="{{ route('quotations.index') }}" class="btn btn-secondary rfq-btn-cancel">
                    @t('rfq.btn_cancel', 'Cancel')
                </a>
                <button type="submit" class="btn btn-primary rfq-btn-submit" id="submitRfqBtn">
                    <span>@t('rfq.btn_submit', 'Submit RFQ for Evaluation')</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </button>
            </div>
        </form>

    </div>
</div>
@endsection

@push('styles')
<style>
/* ════════════════════════════════════════
   RFQ CREATE PAGE — ENTERPRISE B2B STYLING
   ════════════════════════════════════════ */

.rfq-page-body {
    padding-top: var(--space-8);
    padding-bottom: var(--space-16);
    background: #f8fafc;
    min-height: calc(100vh - 220px);
}

/* Notice Card */
.rfq-notice-card {
    background: linear-gradient(135deg, #eff6ff 0%, #f0f9ff 100%);
    border: 1px solid #bfdbfe;
    border-left: 5px solid #2563eb;
    border-radius: 16px;
    padding: 18px 22px;
    box-shadow: 0 4px 16px rgba(37, 99, 235, 0.05);
    display: flex;
    gap: 16px;
    align-items: flex-start;
    margin-bottom: 28px;
}
.rfq-notice-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
    box-shadow: 0 2px 8px rgba(37, 99, 235, 0.12);
    flex-shrink: 0;
}
.rfq-notice-title {
    font-weight: 800;
    color: #1e3a8a;
    font-size: 0.96rem;
    margin-bottom: 3px;
    letter-spacing: -0.01em;
}
.rfq-notice-desc {
    font-size: 0.88rem;
    color: #334155;
    line-height: 1.6;
}

/* Main Cards */
.rfq-main-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
    padding: 28px 30px;
    position: relative;
}

.rfq-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 1px solid #f1f5f9;
}
.rfq-card-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.72rem;
    font-weight: 800;
    color: #2563eb;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    margin-bottom: 4px;
}
.rfq-card-title {
    font-family: var(--font-heading, inherit);
    font-size: 1.3rem;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 3px 0;
    letter-spacing: -0.02em;
}
.rfq-card-subtitle {
    font-size: 0.86rem;
    color: #64748b;
    margin: 0;
    line-height: 1.45;
}

.btn-add-row {
    background: #ffffff;
    border: 1.5px dashed #3b82f6;
    color: #2563eb;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.88rem;
    padding: 10px 20px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    box-shadow: 0 1px 3px rgba(37, 99, 235, 0.08);
}
.btn-add-row:hover {
    background: #eff6ff;
    border-color: #1d4ed8;
    border-style: solid;
    color: #1e40af;
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(37, 99, 235, 0.16);
}

/* Item Rows List */
.rfq-items-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.rfq-item-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 20px 24px;
    box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03);
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
    position: relative;
    z-index: 10;
}
.rfq-item-card:hover {
    border-color: #cbd5e1;
    box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
}
.rfq-item-card.has-open-dropdown {
    z-index: 1000 !important;
}

.rfq-item-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 18px;
    padding-bottom: 12px;
    border-bottom: 1px solid #f1f5f9;
}
.rfq-item-card-header-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.rfq-item-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #eff6ff;
    border: 1px solid #dbeafe;
    padding: 4px 12px;
    border-radius: 999px;
    font-size: 0.76rem;
    font-weight: 800;
    color: #1d4ed8;
    letter-spacing: 0.04em;
    text-transform: uppercase;
}
.rfq-item-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #2563eb;
}
.rfq-item-header-guide {
    font-size: 0.8rem;
    color: #64748b;
    font-weight: 500;
}

/* Clean Minimalist Remove Button */
.rfq-btn-remove-clean {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px;
    border-radius: 8px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #64748b;
    cursor: pointer;
    font-size: 0.78rem;
    font-weight: 600;
    transition: all 0.15s ease;
}
.rfq-btn-remove-clean:hover {
    background: #fef2f2;
    border-color: #fecaca;
    color: #dc2626;
}

/* Item Card Body & Modern Grid */
.rfq-item-card-body {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.rfq-fields-row-primary {
    display: grid;
    grid-template-columns: 3fr 5fr 2fr;
    gap: 16px;
    align-items: end;
}

.rfq-fields-row-notes {
    display: flex;
    width: 100%;
}

.rfq-field-group {
    display: flex;
    flex-direction: column;
    min-width: 0;
    position: relative;
}

.rfq-label {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 6px;
    margin-bottom: 6px;
}
.rfq-label-text {
    font-size: 0.75rem;
    font-weight: 700;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    white-space: nowrap;
}
.rfq-label-hint {
    font-size: 0.72rem;
    font-weight: 500;
    color: #94a3b8;
    text-transform: none;
    letter-spacing: normal;
}
.req-star {
    color: #ef4444;
    font-weight: 800;
    margin-left: 2px;
}

/* ════════════════════════════════════════
   CUSTOM SEARCHABLE SELECT STYLES
   ════════════════════════════════════════ */
.rfq-searchable-select {
    position: relative;
    width: 100%;
}
.rfq-searchable-select.is-open {
    z-index: 1000 !important;
}

.rfq-searchable-trigger {
    width: 100%;
    height: 46px;
    padding: 0 14px;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 11px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    cursor: pointer;
    font-size: 0.88rem;
    font-weight: 600;
    color: #0f172a;
    text-align: left;
    outline: none;
    transition: all 0.15s ease;
    box-sizing: border-box;
    user-select: none;
}
.rfq-searchable-trigger:hover {
    border-color: #93c5fd;
    background: #f8fafc;
}
.rfq-searchable-trigger:focus-visible,
.rfq-searchable-select.is-open .rfq-searchable-trigger {
    border-color: #2563eb;
    box-shadow: 0 0 0 3.5px rgba(37, 99, 235, 0.12);
    background: #ffffff;
}
.rfq-searchable-trigger.is-invalid {
    border-color: #ef4444 !important;
    box-shadow: 0 0 0 3.5px rgba(239, 68, 68, 0.15) !important;
}
.rfq-searchable-trigger-text {
    flex: 1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.rfq-searchable-trigger.is-placeholder .rfq-searchable-trigger-text {
    color: #94a3b8;
    font-weight: 400;
}
.rfq-searchable-chevron {
    color: #64748b;
    flex-shrink: 0;
    transition: transform 0.2s ease, color 0.2s ease;
}
.rfq-searchable-select.is-open .rfq-searchable-chevron {
    transform: rotate(180deg);
    color: #2563eb;
}

/* Dropdown Panel */
.rfq-searchable-panel {
    position: absolute;
    top: calc(100% + 6px);
    left: 0;
    right: 0;
    min-width: 280px;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 14px;
    box-shadow: 0 16px 36px -4px rgba(15, 23, 42, 0.2), 0 4px 14px rgba(15, 23, 42, 0.08);
    padding: 8px;
    z-index: 99999 !important;
    display: none;
    animation: rfqDropdownFadeIn 0.15s ease-out forwards;
}
@keyframes rfqDropdownFadeIn {
    from { opacity: 0; transform: translateY(-4px); }
    to { opacity: 1; transform: translateY(0); }
}
.rfq-searchable-select.is-open .rfq-searchable-panel {
    display: block;
}

/* Search Box inside dropdown */
.rfq-searchable-searchbox {
    position: relative;
    margin-bottom: 6px;
    display: flex;
    align-items: center;
}
.rfq-search-icon {
    position: absolute;
    left: 10px;
    color: #94a3b8;
    pointer-events: none;
}
.rfq-searchable-input {
    width: 100%;
    height: 38px;
    padding: 0 32px 0 34px !important;
    font-size: 0.86rem;
    color: #0f172a;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    outline: none;
    transition: all 0.15s ease;
    box-sizing: border-box;
}
.rfq-searchable-input:focus {
    background: #ffffff;
    border-color: #2563eb;
    box-shadow: 0 0 0 2.5px rgba(37, 99, 235, 0.12);
}
.rfq-search-clear {
    position: absolute;
    right: 8px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #e2e8f0;
    color: #64748b;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    font-weight: 700;
    line-height: 1;
    padding: 0;
    transition: all 0.15s ease;
}
.rfq-search-clear:hover {
    background: #cbd5e1;
    color: #0f172a;
}

/* Options List */
.rfq-searchable-options-list {
    max-height: 250px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 3px;
    padding-right: 2px;
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 transparent;
}
.rfq-searchable-options-list::-webkit-scrollbar {
    width: 5px;
}
.rfq-searchable-options-list::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

/* Option Item */
.rfq-searchable-option {
    padding: 9px 12px;
    border-radius: 8px;
    font-size: 0.88rem;
    color: #1e293b;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    transition: background-color 0.15s, color 0.15s;
    user-select: none;
}
.rfq-searchable-option:hover {
    background: #f1f5f9;
    color: #1d4ed8;
}
.rfq-searchable-option.selected {
    background: #eff6ff;
    color: #1d4ed8;
    font-weight: 700;
}
.rfq-searchable-option.is-hidden {
    display: none !important;
}

.rfq-product-item-info {
    display: flex;
    flex-direction: column;
    gap: 3px;
    min-width: 0;
    flex: 1;
}
.rfq-product-item-name {
    font-size: 0.88rem;
    font-weight: 600;
    color: inherit;
    line-height: 1.35;
}
.rfq-product-item-meta {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}
.rfq-badge-unit {
    font-size: 0.72rem;
    font-weight: 700;
    background: #e2e8f0;
    color: #334155;
    padding: 1.5px 6px;
    border-radius: 5px;
}
.rfq-badge-origin {
    font-size: 0.72rem;
    font-weight: 600;
    background: #fef3c7;
    color: #92400e;
    padding: 1.5px 6px;
    border-radius: 5px;
}
.rfq-badge-cat {
    font-size: 0.72rem;
    font-weight: 600;
    background: #e0f2fe;
    color: #0369a1;
    padding: 1.5px 6px;
    border-radius: 5px;
}

.rfq-option-check {
    font-size: 0.95rem;
    color: #2563eb;
    font-weight: 800;
    display: none;
    flex-shrink: 0;
}
.rfq-searchable-option.selected .rfq-option-check {
    display: inline-block;
}

.rfq-searchable-empty {
    padding: 18px 12px;
    text-align: center;
    font-size: 0.85rem;
    color: #94a3b8;
    font-style: italic;
}

/* Regular Inputs & Textarea */
.rfq-input {
    width: 100%;
    height: 46px;
    padding: 8px 14px;
    font-size: 0.88rem;
    font-weight: 500;
    color: #0f172a;
    background-color: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 11px;
    outline: none;
    transition: all 0.15s ease;
    box-sizing: border-box;
}
.rfq-input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3.5px rgba(37, 99, 235, 0.12);
}

.rfq-textarea {
    width: 100%;
    padding: 14px 16px;
    font-size: 0.9rem;
    line-height: 1.6;
    color: #0f172a;
    background-color: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 12px;
    outline: none;
    transition: all 0.15s ease;
    resize: vertical;
    box-sizing: border-box;
}
.rfq-textarea:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3.5px rgba(37, 99, 235, 0.12);
}

/* Quick Tags / Chips */
.rfq-chips-container {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 12px;
}
.rfq-chips-label {
    font-size: 0.74rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}
.rfq-chip {
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    color: #1e3a8a;
    padding: 5px 12px;
    border-radius: 999px;
    font-size: 0.78rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s ease;
}
.rfq-chip:hover {
    background: #eff6ff;
    border-color: #bfdbfe;
    color: #1d4ed8;
    transform: translateY(-1px);
}

/* Sourcing Tip Box */
.rfq-sourcing-tip-box {
    margin-top: 22px;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border: 1px solid #e2e8f0;
    border-left: 4px solid #2563eb;
    border-radius: 14px;
    padding: 16px 20px;
    display: flex;
    align-items: flex-start;
    gap: 14px;
}
.rfq-tip-icon-box {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    background: #eff6ff;
    border: 1px solid #dbeafe;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    margin-top: 1px;
}
.rfq-tip-content {
    flex: 1;
}
.rfq-tip-title {
    display: block;
    font-size: 0.88rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 3px;
}
.rfq-tip-desc {
    margin: 0;
    font-size: 0.84rem;
    color: #475569;
    line-height: 1.55;
}

/* Action Bar */
.rfq-action-bar {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
    margin-top: 32px;
}

.rfq-btn-cancel {
    padding: 12px 26px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.92rem;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    color: #475569;
}
.rfq-btn-cancel:hover {
    background: #f1f5f9;
    color: #0f172a;
}

.rfq-btn-submit {
    padding: 12px 32px;
    border-radius: 12px;
    font-weight: 800;
    font-size: 0.95rem;
    background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
    border: none;
    color: #ffffff;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 6px 18px rgba(29, 78, 216, 0.28);
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}
.rfq-btn-submit:hover {
    background: linear-gradient(135deg, #1e40af 0%, #1d4ed8 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(29, 78, 216, 0.35);
}

/* ════════════════════════════════════════
   RESPONSIVENESS (PC, TABLET, MOBILE)
   ════════════════════════════════════════ */

/* Tablet (769px to 1024px) */
@media (max-width: 1024px) and (min-width: 769px) {
    .rfq-fields-row-primary {
        grid-template-columns: 1fr 1fr 120px;
        gap: 12px;
    }
}

/* Mobile & Small Tablets (<= 768px) */
@media (max-width: 768px) {
    .rfq-main-card {
        padding: 20px 16px;
        border-radius: 16px;
    }
    .rfq-card-header {
        flex-direction: column;
        align-items: stretch;
        gap: 12px;
    }
    .btn-add-row {
        width: 100%;
        justify-content: center;
        padding: 11px 16px;
    }

    .rfq-fields-row-primary {
        grid-template-columns: 1fr;
        gap: 14px;
    }

    .rfq-searchable-panel {
        min-width: 100%;
    }

    .rfq-action-bar {
        flex-direction: column-reverse;
        gap: 10px;
    }
    .rfq-btn-cancel,
    .rfq-btn-submit {
        width: 100%;
        justify-content: center;
        text-align: center;
        padding: 13px 20px;
    }
}
</style>
@endpush

@push('scripts')
<script>
let rowIndex = 1;
const productsData = @json($products);
const categoriesData = @json($categories);

const i18n = {
    allCategories: {!! json_encode(__t('rfq.all_categories_opt', 'All Categories (All Items)')) !!},
    selectProduct: {!! json_encode(__t('rfq.select_product_placeholder', 'Select product from catalogue...')) !!},
    searchCategory: {!! json_encode(__t('rfq.search_category', 'Search category...')) !!},
    searchProduct: {!! json_encode(__t('rfq.search_product', 'Search product by name, origin, unit...')) !!},
    noCategoryMatch: {!! json_encode(__t('rfq.no_category_match', 'No matching categories found')) !!},
    noProductMatch: {!! json_encode(__t('rfq.no_product_match', 'No matching products found')) !!},
    selectProductErr: {!! json_encode(__t('rfq.select_product_err', 'Please select a product from the list.')) !!}
};

// Close all open dropdowns
function closeAllSearchableDropdowns() {
    document.querySelectorAll('.rfq-searchable-select.is-open').forEach(el => {
        el.classList.remove('is-open');
        const trigger = el.querySelector('.rfq-searchable-trigger');
        if (trigger) trigger.setAttribute('aria-expanded', 'false');
    });
    document.querySelectorAll('.rfq-item-card.has-open-dropdown').forEach(card => {
        card.classList.remove('has-open-dropdown');
    });
}

// Global click listener to close dropdown when clicked outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('.rfq-searchable-select')) {
        closeAllSearchableDropdowns();
    }
});

// Global Escape listener to close dropdowns
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAllSearchableDropdowns();
    }
});

// Setup Searchable Select Component for a container
function setupSearchableSelect(container) {
    if (!container || container.dataset.initialized === 'true') return;
    container.dataset.initialized = 'true';

    const type = container.dataset.type; // 'category' or 'product'
    const rowId = container.dataset.row;
    const trigger = container.querySelector('.rfq-searchable-trigger');
    const triggerText = container.querySelector('.rfq-searchable-trigger-text');
    const input = container.querySelector('input[type="hidden"]');
    const panel = container.querySelector('.rfq-searchable-panel');
    const searchInput = container.querySelector('.rfq-searchable-input');
    const clearBtn = container.querySelector('.rfq-search-clear');
    const optionsList = container.querySelector('.rfq-searchable-options-list');
    const emptyMsg = container.querySelector('.rfq-searchable-empty');
    const itemCard = container.closest('.rfq-item-card');

    // Toggle dropdown open/close
    trigger.addEventListener('click', function(e) {
        e.stopPropagation();
        const isOpen = container.classList.contains('is-open');
        closeAllSearchableDropdowns();

        if (!isOpen) {
            container.classList.add('is-open');
            trigger.setAttribute('aria-expanded', 'true');
            if (itemCard) itemCard.classList.add('has-open-dropdown');
            
            // Focus search input
            setTimeout(() => {
                if (searchInput) {
                    searchInput.focus();
                    searchInput.select();
                }
            }, 50);
        }
    });

    // Panel click propagation prevention
    panel.addEventListener('click', function(e) {
        e.stopPropagation();
    });

    // Search filter handling
    function filterOptions() {
        const query = (searchInput.value || '').trim().toLowerCase();
        if (clearBtn) {
            clearBtn.style.display = query.length > 0 ? 'flex' : 'none';
        }

        const options = optionsList.querySelectorAll('.rfq-searchable-option');
        let visibleCount = 0;

        // In case of product select, we also need to respect category filter
        let activeCatFilter = "";
        if (type === 'product') {
            const catValInput = document.getElementById(`cat_val_${rowId}`);
            if (catValInput && catValInput.value) {
                activeCatFilter = catValInput.value;
            }
        }

        options.forEach(opt => {
            const optVal = opt.dataset.value;
            const searchData = (opt.dataset.search || opt.dataset.label || '').toLowerCase();
            const catId = opt.dataset.categoryId || '';

            let matchesCat = true;
            if (type === 'product' && activeCatFilter) {
                matchesCat = (catId === activeCatFilter);
            }

            let matchesQuery = !query || searchData.includes(query);

            if (matchesCat && matchesQuery) {
                opt.classList.remove('is-hidden');
                visibleCount++;
            } else {
                opt.classList.add('is-hidden');
            }
        });

        if (emptyMsg) {
            emptyMsg.style.display = visibleCount === 0 ? 'block' : 'none';
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', filterOptions);
    }

    if (clearBtn) {
        clearBtn.addEventListener('click', function() {
            if (searchInput) {
                searchInput.value = '';
                filterOptions();
                searchInput.focus();
            }
        });
    }

    // Option item click
    optionsList.addEventListener('click', function(e) {
        const opt = e.target.closest('.rfq-searchable-option');
        if (!opt) return;

        const val = opt.dataset.value || '';
        const label = opt.dataset.label || '';

        // Update hidden value
        if (input) {
            input.value = val;
            input.dispatchEvent(new Event('change'));
        }

        // Update active highlight
        optionsList.querySelectorAll('.rfq-searchable-option').forEach(o => o.classList.remove('selected'));
        opt.classList.add('selected');

        // Update trigger text
        if (type === 'category') {
            triggerText.textContent = label || i18n.allCategories;
            // Notify product dropdown of this row to filter
            onCategoryChanged(rowId, val);
        } else if (type === 'product') {
            if (val) {
                triggerText.textContent = label;
                trigger.classList.remove('is-placeholder');
                trigger.classList.remove('is-invalid');
            } else {
                triggerText.textContent = i18n.selectProduct;
                trigger.classList.add('is-placeholder');
            }
        }

        // Close dropdown
        closeAllSearchableDropdowns();
    });
}

// When Category changes in a row, update and filter Product dropdown in that row
function onCategoryChanged(rowId, selectedCatId) {
    const prodWrap = document.getElementById(`prod_select_wrap_${rowId}`);
    if (!prodWrap) return;

    const prodInput = prodWrap.querySelector('.rfq-prod-val');
    const prodTrigger = prodWrap.querySelector('.rfq-searchable-trigger');
    const prodTriggerText = prodWrap.querySelector('.rfq-searchable-trigger-text');
    const prodOptionsList = prodWrap.querySelector('.rfq-searchable-options-list');
    const prodSearchInput = prodWrap.querySelector('.rfq-searchable-input');
    const emptyMsg = prodWrap.querySelector('.rfq-searchable-empty');

    if (prodSearchInput) prodSearchInput.value = '';

    const currentProdId = prodInput ? prodInput.value : '';
    let currentProdStillValid = false;
    let visibleCount = 0;

    const options = prodOptionsList.querySelectorAll('.rfq-searchable-option');
    options.forEach(opt => {
        const catId = opt.dataset.categoryId || '';
        const optVal = opt.dataset.value || '';

        if (!selectedCatId || catId === selectedCatId) {
            opt.classList.remove('is-hidden');
            visibleCount++;
            if (optVal === currentProdId) {
                currentProdStillValid = true;
            }
        } else {
            opt.classList.add('is-hidden');
        }
    });

    if (emptyMsg) {
        emptyMsg.style.display = visibleCount === 0 ? 'block' : 'none';
    }

    // If current selected product is no longer valid in new category, reset selection
    if (currentProdId && !currentProdStillValid) {
        if (prodInput) prodInput.value = '';
        if (prodTriggerText) prodTriggerText.textContent = i18n.selectProduct;
        if (prodTrigger) prodTrigger.classList.add('is-placeholder');
        options.forEach(o => o.classList.remove('selected'));
    }
}

// Initialize all Searchable Selects in a given row
function initRowSearchables(rowEl) {
    if (!rowEl) return;
    rowEl.querySelectorAll('.rfq-searchable-select').forEach(setupSearchableSelect);
}

// Add Item Row Dynamically
function addItemRow() {
    const container = document.getElementById('itemsContainer');
    const row = document.createElement('div');
    row.className = 'rfq-item-card';
    row.id = `rfqRow${rowIndex}`;
    row.setAttribute('data-row-id', rowIndex);

    const rowNumber = container.querySelectorAll('.rfq-item-card').length + 1;

    // Build Category options
    let catOptionsHtml = `
        <div class="rfq-searchable-option selected" data-value="" data-label="${i18n.allCategories}">
            <span class="rfq-option-label">🌐 ${i18n.allCategories}</span>
            <span class="rfq-option-check">✓</span>
        </div>
    `;
    categoriesData.forEach(c => {
        catOptionsHtml += `
            <div class="rfq-searchable-option" data-value="${c.id}" data-label="${c.name}">
                <span class="rfq-option-label">${c.name}</span>
                <span class="rfq-option-check">✓</span>
            </div>
        `;
    });

    // Build Product options
    let prodOptionsHtml = '';
    productsData.forEach(p => {
        const fullLabel = `${p.name} (${p.unit}) ${p.origin ? '— ' + p.origin : ''}`;
        const searchTerms = `${p.name} ${p.unit} ${p.origin || ''} ${p.category?.name || ''}`.toLowerCase();
        prodOptionsHtml += `
            <div class="rfq-searchable-option"
                 data-value="${p.id}"
                 data-category-id="${p.category_id || ''}"
                 data-label="${fullLabel}"
                 data-search="${searchTerms}">
                <div class="rfq-product-item-info">
                    <div class="rfq-product-item-name">${p.name}</div>
                    <div class="rfq-product-item-meta">
                        <span class="rfq-badge-unit">${p.unit}</span>
                        ${p.origin ? `<span class="rfq-badge-origin">📍 ${p.origin}</span>` : ''}
                        ${p.category ? `<span class="rfq-badge-cat">${p.category.name}</span>` : ''}
                    </div>
                </div>
                <span class="rfq-option-check">✓</span>
            </div>
        `;
    });

    row.innerHTML = `
        <div class="rfq-item-card-header">
            <div class="rfq-item-card-header-left">
                <div class="rfq-item-pill">
                    <span class="rfq-item-dot"></span>
                    <span class="rfq-item-num-text">Item #${rowNumber}</span>
                </div>
                <span class="rfq-item-header-guide">Product Specifications & Volume</span>
            </div>
            <button type="button" class="rfq-btn-remove-clean" onclick="removeRow(this)" title="Remove Item" aria-label="Remove Item">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    <line x1="10" y1="11" x2="10" y2="17"></line>
                    <line x1="14" y1="11" x2="14" y2="17"></line>
                </svg>
                <span>Remove</span>
            </button>
        </div>

        <div class="rfq-item-card-body">
            <!-- Primary Row: Category, Product, Quantity -->
            <div class="rfq-fields-row-primary">
                <!-- 1. Searchable Category Dropdown -->
                <div class="rfq-field-group rfq-field-cat-col">
                    <label class="rfq-label">
                        <span class="rfq-label-text">{{ __t('rfq.col_category', 'Category') }}</span>
                    </label>
                    <div class="rfq-searchable-select" id="cat_select_wrap_${rowIndex}" data-type="category" data-row="${rowIndex}">
                        <input type="hidden" class="rfq-cat-val" id="cat_val_${rowIndex}" value="">
                        <button type="button" class="rfq-searchable-trigger" id="cat_trigger_${rowIndex}" aria-haspopup="listbox" aria-expanded="false">
                            <span class="rfq-searchable-trigger-text" id="cat_text_${rowIndex}">${i18n.allCategories}</span>
                            <svg class="rfq-searchable-chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        <div class="rfq-searchable-panel" id="cat_panel_${rowIndex}">
                            <div class="rfq-searchable-searchbox">
                                <svg class="rfq-search-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                                <input type="text" class="rfq-searchable-input" placeholder="${i18n.searchCategory}" autocomplete="off">
                                <button type="button" class="rfq-search-clear" style="display:none;" title="Clear search">✕</button>
                            </div>
                            <div class="rfq-searchable-options-list" role="listbox">
                                ${catOptionsHtml}
                                <div class="rfq-searchable-empty" style="display:none;">${i18n.noCategoryMatch}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Searchable Product Dropdown -->
                <div class="rfq-field-group rfq-field-prod-col">
                    <label class="rfq-label">
                        <span class="rfq-label-text">{{ __t('rfq.col_product', 'Product') }}</span>
                        <span class="req-star">*</span>
                    </label>
                    <div class="rfq-searchable-select" id="prod_select_wrap_${rowIndex}" data-type="product" data-row="${rowIndex}">
                        <input type="hidden" name="items[${rowIndex}][product_id]" class="rfq-prod-val" id="prod_val_${rowIndex}" value="" required>
                        <button type="button" class="rfq-searchable-trigger is-placeholder" id="prod_trigger_${rowIndex}" aria-haspopup="listbox" aria-expanded="false">
                            <span class="rfq-searchable-trigger-text" id="prod_text_${rowIndex}">${i18n.selectProduct}</span>
                            <svg class="rfq-searchable-chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        <div class="rfq-searchable-panel" id="prod_panel_${rowIndex}">
                            <div class="rfq-searchable-searchbox">
                                <svg class="rfq-search-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                                <input type="text" class="rfq-searchable-input" placeholder="${i18n.searchProduct}" autocomplete="off">
                                <button type="button" class="rfq-search-clear" style="display:none;" title="Clear search">✕</button>
                            </div>
                            <div class="rfq-searchable-options-list" role="listbox">
                                ${prodOptionsHtml}
                                <div class="rfq-searchable-empty" style="display:none;">${i18n.noProductMatch}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Quantity Input -->
                <div class="rfq-field-group rfq-field-qty-col">
                    <label class="rfq-label" for="qty_input_${rowIndex}">
                        <span class="rfq-label-text">{{ __t('rfq.col_quantity', 'Quantity') }}</span>
                        <span class="req-star">*</span>
                    </label>
                    <input type="number" name="items[${rowIndex}][quantity]" id="qty_input_${rowIndex}" class="rfq-input" min="1" placeholder="e.g. 50" required>
                </div>
            </div>

            <!-- Row 2: Specifications & Sizing Notes (Full Width) -->
            <div class="rfq-fields-row-notes">
                <div class="rfq-field-group" style="width:100%">
                    <label class="rfq-label" for="notes_input_${rowIndex}">
                        <span class="rfq-label-text">{{ __t('rfq.col_notes', 'Notes / Sizing Specs (Optional)') }}</span>
                        <span class="rfq-label-hint">Special processing, target size, packaging or FOB terms</span>
                    </label>
                    <input type="text" name="items[${rowIndex}][notes]" id="notes_input_${rowIndex}" class="rfq-input" placeholder="{{ __t('rfq.notes_placeholder', 'e.g. IQF / Block frozen, 20-30 count, 1kg pack, custom labeling') }}">
                </div>
            </div>
        </div>
    `;

    container.appendChild(row);
    initRowSearchables(row);
    rowIndex++;
    updateRowNumbers();
}

function removeRow(btn) {
    const rows = document.querySelectorAll('.rfq-item-card');
    if (rows.length <= 1) {
        if (typeof showGlobalToast === 'function') {
            showGlobalToast('At least one product item is required in the quotation.', 'error');
        } else {
            alert('At least one product item is required in the quotation.');
        }
        return;
    }
    btn.closest('.rfq-item-card').remove();
    updateRowNumbers();
}

function updateRowNumbers() {
    const rows = document.querySelectorAll('.rfq-item-card');
    rows.forEach((row, index) => {
        const numText = row.querySelector('.rfq-item-num-text');
        if (numText) {
            numText.textContent = `Item #${index + 1}`;
        }
    });
}

// Helper chip append to textarea
function appendNote(text) {
    const textarea = document.getElementById('customer_notes');
    if (!textarea) return;
    const currentVal = textarea.value.trim();
    if (currentVal) {
        if (!currentVal.includes(text)) {
            textarea.value = currentVal + '\n• ' + text;
        }
    } else {
        textarea.value = '• ' + text;
    }
    textarea.focus();
}

// Validate form before submission
document.addEventListener('DOMContentLoaded', function() {
    // Initialize initial row
    const initialRow = document.getElementById('rfqRow0');
    if (initialRow) {
        initRowSearchables(initialRow);
        
        // If initial category is set, trigger category filter
        const initialCatVal = document.getElementById('cat_val_0');
        if (initialCatVal && initialCatVal.value) {
            onCategoryChanged(0, initialCatVal.value);
        }
    }

    const rfqForm = document.getElementById('rfqForm');
    if (rfqForm) {
        rfqForm.addEventListener('submit', function(e) {
            let hasError = false;
            let firstInvalidEl = null;

            const rows = document.querySelectorAll('.rfq-item-card');
            rows.forEach((row, idx) => {
                const prodVal = row.querySelector('.rfq-prod-val');
                const prodTrigger = row.querySelector('.rfq-field-prod-col .rfq-searchable-trigger');
                if (!prodVal || !prodVal.value) {
                    hasError = true;
                    if (prodTrigger) {
                        prodTrigger.classList.add('is-invalid');
                        if (!firstInvalidEl) firstInvalidEl = prodTrigger;
                    }
                } else if (prodTrigger) {
                    prodTrigger.classList.remove('is-invalid');
                }
            });

            if (hasError) {
                e.preventDefault();
                if (firstInvalidEl) {
                    firstInvalidEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstInvalidEl.focus();
                }
                if (typeof showGlobalToast === 'function') {
                    showGlobalToast(i18n.selectProductErr, 'error');
                } else {
                    alert(i18n.selectProductErr);
                }
            }
        });
    }
});
</script>
@endpush
