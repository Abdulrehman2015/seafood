@extends('layouts.admin')
@section('title', 'Edit ' . $product->name . ' — MST Admin')

@section('content')
<div class="admin-topbar" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:24px;padding-bottom:18px;border-bottom:1px solid var(--gray-200);">
    <div>
        <a href="{{ route('admin.products.index') }}" class="text-sm" style="color:var(--seagreen-700);text-decoration:none;display:inline-flex;align-items:center;gap:6px;margin-bottom:6px;font-weight:600">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Back to Products
        </a>
        <h1 class="admin-page-title" style="margin:0;font-size:clamp(1.4rem,3vw,1.85rem);">Edit Product: {{ $product->name }}</h1>
        <p class="text-sm text-muted" style="margin:4px 0 0;">Update multi-tier pricing, inventory counts, specifications, and media assets</p>
    </div>
    <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
        <a href="{{ route('shop.show', $product->slug) }}" class="btn btn-secondary" target="_blank" style="display:inline-flex;align-items:center;gap:6px;">
            <span>👁</span> View in Shop
        </a>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
        <button type="submit" form="productForm" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:8px;">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            Save Changes
        </button>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger mb-6" style="background:#fee2e2;border:1px solid #fca5a5;color:#991b1b;border-radius:12px;padding:14px 18px;">
        <div style="font-weight:700;margin-bottom:4px;display:flex;align-items:center;gap:8px;">
            <span>⚠️</span> Please correct the errors below before submitting:
        </div>
        <ul style="margin:0;padding-left:20px;font-size:0.875rem;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form id="productForm" action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="admin-form-layout">

        <!-- ─── Main Information Column ─────────────────────────────────── -->
        <div style="display:flex;flex-direction:column;gap:20px;min-width:0;">

            <!-- Basic Info Card -->
            <div class="card">
                <div class="card-header" style="display:flex;align-items:center;gap:10px;margin-bottom:18px;">
                    <span style="font-size:1.25rem;">📦</span>
                    <div class="card-title" style="font-size:1.1rem;font-weight:700;">General Information</div>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label">Product Name <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                           value="{{ old('name', $product->name) }}" placeholder="e.g. Atlantic Salmon Fillet (500g)" required>
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-grid-2 mb-4">
                    <div class="form-group mb-0">
                        <label class="form-label">SKU / Code</label>
                        <input type="text" name="sku" class="form-control {{ $errors->has('sku') ? 'is-invalid' : '' }}"
                               value="{{ old('sku', $product->sku) }}" placeholder="e.g. FISH-SAL-500">
                        <div class="form-hint" style="font-size:0.75rem;color:var(--text-muted);margin-top:4px;">Unique inventory stock keeping unit</div>
                        @error('sku')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-control">
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-hint" style="font-size:0.75rem;color:var(--text-muted);margin-top:4px;">Organizes products in storefront and filters</div>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label">Short Description</label>
                    <input type="text" name="short_description" class="form-control" maxlength="500"
                           value="{{ old('short_description', $product->short_description) }}" placeholder="Crisp 1-2 sentence highlight shown in catalogue cards">
                </div>

                <div class="form-group mb-0">
                    <label class="form-label">Full Description</label>
                    <textarea name="description" class="form-control" rows="5" placeholder="Detailed product culinary advisory, tasting notes, thaw instructions, packaging details...">{{ old('description', $product->description) }}</textarea>
                </div>
            </div>

            <!-- Pricing & Tier Rules Card -->
            <div class="card">
                <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;flex-wrap:wrap;gap:8px;">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <span style="font-size:1.25rem;">💰</span>
                        <div class="card-title" style="font-size:1.1rem;font-weight:700;">Multi-Tier Pricing &amp; MOQ</div>
                    </div>
                    <span style="font-size:0.78rem;background:#dbeafe;color:#1d4ed8;font-weight:700;padding:4px 10px;border-radius:999px;">
                        4 Customer Tiers
                    </span>
                </div>

                <div class="alert alert-info mb-4" style="background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;border-radius:10px;padding:12px 16px;font-size:0.875rem;display:flex;align-items:flex-start;gap:10px;">
                    <span style="font-size:1.1rem;line-height:1;">ℹ️</span>
                    <div>
                        Each customer group sees only their specific price on the storefront.
                        Enter <strong>0.00</strong> or standard rates for retail/walk-in. Leave Trading empty if items are strictly Request for Quotation (RFQ).
                    </div>
                </div>

                <div class="form-grid-2 mb-4">
                    <!-- Retail Price -->
                    <div class="form-group mb-0" style="background:#f8fafc;padding:14px;border-radius:12px;border:1px solid #e2e8f0;">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                            <label class="form-label mb-0" style="font-weight:700;color:var(--text-primary);">
                                🛒 Retail Price <span class="required">*</span>
                            </label>
                            <span style="font-size:0.7rem;color:var(--text-muted);">B2C Online</span>
                        </div>
                        <div class="currency-input-group">
                            <span class="currency-badge">RM</span>
                            <input type="number" name="retail_price" step="0.01" min="0"
                                   class="form-control {{ $errors->has('retail_price') ? 'is-invalid' : '' }}"
                                   value="{{ old('retail_price', $product->retail_price) }}" placeholder="0.00" required>
                        </div>
                        <div style="margin-top:10px;">
                            <label style="font-size:0.78rem;color:var(--text-muted);display:block;margin-bottom:3px;">Retail Default MOQ</label>
                            <input type="number" name="moq" min="1" class="form-control form-control-sm" value="{{ old('moq', $product->moq ?? 1) }}" placeholder="1">
                        </div>
                        @error('retail_price')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <!-- Walk-in Price -->
                    <div class="form-group mb-0" style="background:#f8fafc;padding:14px;border-radius:12px;border:1px solid #e2e8f0;">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                            <label class="form-label mb-0" style="font-weight:700;color:var(--text-primary);">
                                🏪 Walk-in Price <span class="required">*</span>
                            </label>
                            <span style="font-size:0.7rem;color:var(--text-muted);">In-store QR</span>
                        </div>
                        <div class="currency-input-group">
                            <span class="currency-badge">RM</span>
                            <input type="number" name="walkin_price" step="0.01" min="0"
                                   class="form-control {{ $errors->has('walkin_price') ? 'is-invalid' : '' }}"
                                   value="{{ old('walkin_price', $product->walkin_price) }}" placeholder="0.00" required>
                        </div>
                        <div class="form-hint" style="font-size:0.75rem;color:var(--text-muted);margin-top:10px;">
                            Self-pickup counter rate for store visitors
                        </div>
                        @error('walkin_price')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <!-- Wholesale Price -->
                    <div class="form-group mb-0" style="background:#f8fafc;padding:14px;border-radius:12px;border:1px solid #e2e8f0;">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                            <label class="form-label mb-0" style="font-weight:700;color:var(--text-primary);">
                                🏭 Wholesale Price <span class="required">*</span>
                            </label>
                            <span style="font-size:0.7rem;color:#2563eb;font-weight:700;">Approved B2B</span>
                        </div>
                        <div class="currency-input-group">
                            <span class="currency-badge">RM</span>
                            <input type="number" name="wholesale_price" step="0.01" min="0"
                                   class="form-control {{ $errors->has('wholesale_price') ? 'is-invalid' : '' }}"
                                   value="{{ old('wholesale_price', $product->wholesale_price) }}" placeholder="0.00" required>
                        </div>
                        <div style="margin-top:10px;">
                            <label style="font-size:0.78rem;color:var(--text-muted);display:block;margin-bottom:3px;">Wholesale MOQ (Units)</label>
                            <input type="number" name="moq_wholesale" min="1" class="form-control form-control-sm" value="{{ old('moq_wholesale', $product->moq_wholesale ?? 5) }}" placeholder="5">
                        </div>
                        @error('wholesale_price')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <!-- Trading Price -->
                    <div class="form-group mb-0" style="background:#f8fafc;padding:14px;border-radius:12px;border:1px solid #e2e8f0;">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                            <label class="form-label mb-0" style="font-weight:700;color:var(--text-primary);">
                                📦 Trading Price
                            </label>
                            <span style="font-size:0.7rem;color:#7c3aed;font-weight:700;">Bulk / RFQ</span>
                        </div>
                        <div class="currency-input-group">
                            <span class="currency-badge">RM</span>
                            <input type="number" name="trading_price" step="0.01" min="0"
                                   class="form-control"
                                   value="{{ old('trading_price', $product->trading_price) }}" placeholder="Leave blank for RFQ">
                        </div>
                        <div style="margin-top:10px;">
                            <label style="font-size:0.78rem;color:var(--text-muted);display:block;margin-bottom:3px;">Trading MOQ (Units / Cartons)</label>
                            <input type="number" name="moq_trading" min="1" class="form-control form-control-sm" value="{{ old('moq_trading', $product->moq_trading ?? 10) }}" placeholder="10">
                        </div>
                    </div>
                </div>

                <!-- Multi-Currency Manual Pricing Section (SGD & USD) -->
                <div style="margin-top:20px;padding-top:16px;border-top:1.5px dashed #e2e8f0;">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;flex-wrap:wrap;gap:8px;">
                        <label class="form-label mb-0" style="font-weight:700;color:var(--text-primary);display:flex;align-items:center;gap:6px;">
                            <span>🌍</span> Multi-Currency Pricing (Manual SGD &amp; USD Overrides)
                        </label>
                        <span style="font-size:0.75rem;color:var(--text-muted);background:#f1f5f9;padding:2px 8px;border-radius:6px;">
                            Active when Auto Conversion is OFF
                        </span>
                    </div>

                    <div class="form-grid-2">
                        <!-- Retail SGD -->
                        <div class="form-group mb-0" style="background:#f8fafc;padding:12px 14px;border-radius:10px;border:1px solid #e2e8f0;">
                            <label class="form-label" style="font-weight:600;font-size:0.8rem;color:var(--text-primary);margin-bottom:4px;">
                                🇸🇬 Retail Price (SGD)
                            </label>
                            <div class="currency-input-group">
                                <span class="currency-badge" style="background:#e0f2fe;color:#0369a1;font-weight:700;">S$</span>
                                <input type="number" name="price_sgd" step="0.01" min="0" class="form-control"
                                       value="{{ old('price_sgd', $product->price_sgd) }}" placeholder="e.g. 9.50 (Optional)">
                            </div>
                        </div>

                        <!-- Retail USD -->
                        <div class="form-group mb-0" style="background:#f8fafc;padding:12px 14px;border-radius:10px;border:1px solid #e2e8f0;">
                            <label class="form-label" style="font-weight:600;font-size:0.8rem;color:var(--text-primary);margin-bottom:4px;">
                                🇺🇸 Retail Price (USD)
                            </label>
                            <div class="currency-input-group">
                                <span class="currency-badge" style="background:#ecfdf5;color:#047857;font-weight:700;">$</span>
                                <input type="number" name="price_usd" step="0.01" min="0" class="form-control"
                                       value="{{ old('price_usd', $product->price_usd) }}" placeholder="e.g. 7.20 (Optional)">
                            </div>
                        </div>

                        <!-- Wholesale SGD -->
                        <div class="form-group mb-0" style="background:#f8fafc;padding:12px 14px;border-radius:10px;border:1px solid #e2e8f0;">
                            <label class="form-label" style="font-weight:600;font-size:0.8rem;color:var(--text-primary);margin-bottom:4px;">
                                🇸🇬 Wholesale Price (SGD)
                            </label>
                            <div class="currency-input-group">
                                <span class="currency-badge" style="background:#e0f2fe;color:#0369a1;font-weight:700;">S$</span>
                                <input type="number" name="wholesale_price_sgd" step="0.01" min="0" class="form-control"
                                       value="{{ old('wholesale_price_sgd', $product->wholesale_price_sgd) }}" placeholder="Optional">
                            </div>
                        </div>

                        <!-- Wholesale USD -->
                        <div class="form-group mb-0" style="background:#f8fafc;padding:12px 14px;border-radius:10px;border:1px solid #e2e8f0;">
                            <label class="form-label" style="font-weight:600;font-size:0.8rem;color:var(--text-primary);margin-bottom:4px;">
                                🇺🇸 Wholesale Price (USD)
                            </label>
                            <div class="currency-input-group">
                                <span class="currency-badge" style="background:#ecfdf5;color:#047857;font-weight:700;">$</span>
                                <input type="number" name="wholesale_price_usd" step="0.01" min="0" class="form-control"
                                       value="{{ old('wholesale_price_usd', $product->wholesale_price_usd) }}" placeholder="Optional">
                            </div>
                        </div>

                        <!-- Trading SGD -->
                        <div class="form-group mb-0" style="background:#f8fafc;padding:12px 14px;border-radius:10px;border:1px solid #e2e8f0;">
                            <label class="form-label" style="font-weight:600;font-size:0.8rem;color:var(--text-primary);margin-bottom:4px;">
                                🇸🇬 Trading Price (SGD)
                            </label>
                            <div class="currency-input-group">
                                <span class="currency-badge" style="background:#e0f2fe;color:#0369a1;font-weight:700;">S$</span>
                                <input type="number" name="trading_price_sgd" step="0.01" min="0" class="form-control"
                                       value="{{ old('trading_price_sgd', $product->trading_price_sgd) }}" placeholder="Optional">
                            </div>
                        </div>

                        <!-- Trading USD -->
                        <div class="form-group mb-0" style="background:#f8fafc;padding:12px 14px;border-radius:10px;border:1px solid #e2e8f0;">
                            <label class="form-label" style="font-weight:600;font-size:0.8rem;color:var(--text-primary);margin-bottom:4px;">
                                🇺🇸 Trading Price (USD)
                            </label>
                            <div class="currency-input-group">
                                <span class="currency-badge" style="background:#ecfdf5;color:#047857;font-weight:700;">$</span>
                                <input type="number" name="trading_price_usd" step="0.01" min="0" class="form-control"
                                       value="{{ old('trading_price_usd', $product->trading_price_usd) }}" placeholder="Optional">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Physical Details & Inventory -->
            <div class="card">
                <div class="card-header" style="display:flex;align-items:center;gap:10px;margin-bottom:18px;">
                    <span style="font-size:1.25rem;">⚖️</span>
                    <div class="card-title" style="font-size:1.1rem;font-weight:700;">Physical Specifications &amp; Inventory</div>
                </div>

                <div class="form-grid-3 mb-4">
                    <div class="form-group mb-0">
                        <label class="form-label">Weight / Packaging</label>
                        <input type="text" name="weight" class="form-control" value="{{ old('weight', $product->weight) }}" placeholder="e.g. 500g, 1kg pack">
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label">Sales Unit <span class="required">*</span></label>
                        <select name="unit" class="form-control" required>
                            @foreach(['pcs' => 'Pieces (pcs)', 'kg' => 'Kilogram (kg)', 'pack' => 'Pack', 'box' => 'Box', 'tray' => 'Tray', 'bag' => 'Bag', 'carton' => 'Carton'] as $val => $lbl)
                                <option value="{{ $val }}" {{ old('unit', $product->unit ?? 'pack') == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label">Stock Quantity <span class="required">*</span></label>
                        <input type="number" name="stock_quantity" min="0" class="form-control" value="{{ old('stock_quantity', $product->stock_quantity ?? 0) }}" required>
                    </div>
                </div>

                <div class="form-grid-3">
                    <div class="form-group mb-0">
                        <label class="form-label">Country of Origin</label>
                        <input type="text" name="origin" class="form-control" value="{{ old('origin', $product->origin) }}" placeholder="e.g. Norway, Malaysia, Japan">
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label">Storage Temperature</label>
                        <input type="text" name="storage_temp" class="form-control" value="{{ old('storage_temp', $product->storage_temp ?? '-18°C') }}" placeholder="e.g. -18°C Frozen">
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label">Brand / Producer</label>
                        <input type="text" name="brand" class="form-control" value="{{ old('brand', $product->brand ?? 'Mika Seafood') }}" placeholder="e.g. Mika Brand">
                    </div>
                </div>
            </div>

            <!-- Custom Specifications Key-Value Card -->
            <div class="card">
                <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;margin-bottom:14px;">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <span style="font-size:1.25rem;">📋</span>
                        <div class="card-title" style="font-size:1.1rem;font-weight:700;">Custom Specifications</div>
                    </div>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="addSpecRow()">
                        + Add Custom Row
                    </button>
                </div>

                <!-- Preset Suggestions -->
                <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;margin-bottom:14px;">
                    <span style="font-size:0.75rem;color:var(--text-muted);font-weight:600;">Quick Add:</span>
                    <button type="button" class="badge" style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;cursor:pointer;padding:4px 8px;font-size:0.72rem;border-radius:6px;" onclick="addPresetSpec('Certification', 'Halal & HACCP')">+ Certification</button>
                    <button type="button" class="badge" style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;cursor:pointer;padding:4px 8px;font-size:0.72rem;border-radius:6px;" onclick="addPresetSpec('Glazing', '10% Protective Ice')">+ Glazing %</button>
                    <button type="button" class="badge" style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;cursor:pointer;padding:4px 8px;font-size:0.72rem;border-radius:6px;" onclick="addPresetSpec('Processing Method', 'IQF Frozen at Sea')">+ Freezing Method</button>
                    <button type="button" class="badge" style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;cursor:pointer;padding:4px 8px;font-size:0.72rem;border-radius:6px;" onclick="addPresetSpec('Shelf Life', '24 Months from Production')">+ Shelf Life</button>
                </div>

                <div id="specsContainer">
                    @if($product->specifications && is_array($product->specifications))
                        @foreach($product->specifications as $key => $val)
                        <div class="spec-row-item">
                            <div>
                                <input type="text" name="spec_keys[]" class="form-control form-control-sm" value="{{ $key }}" placeholder="e.g. Attribute Name">
                            </div>
                            <div>
                                <input type="text" name="spec_values[]" class="form-control form-control-sm" value="{{ $val }}" placeholder="e.g. Value">
                            </div>
                            <div style="text-align:center;">
                                <button type="button" class="btn btn-danger btn-sm" onclick="removeSpecRow(this)" style="padding:5px 8px;border-radius:8px;" title="Remove Attribute">✕</button>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
                <div id="noSpecsNotice" style="display:{{ (!empty($product->specifications) && count($product->specifications)) ? 'none' : 'block' }};text-align:center;padding:18px;background:#f8fafc;border-radius:8px;border:1px dashed #cbd5e1;color:#64748b;font-size:0.85rem;">
                    No custom specifications added yet. Click <strong>+ Add Custom Row</strong> or a Quick Add chip above.
                </div>
            </div>

        </div>

        <!-- ─── Sidebar Column: Media & Settings ────────────────────────── -->
        <div style="display:flex;flex-direction:column;gap:20px;min-width:0;">

            <!-- Product Thumbnail Card -->
            <div class="card">
                <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <span>📷</span>
                        <div class="card-title" style="font-size:0.98rem;font-weight:700;">Product Thumbnail</div>
                    </div>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="pickProductThumbnail()" style="font-size:0.76rem;padding:4px 8px;">
                        🖼️ Gallery
                    </button>
                </div>

                <div id="thumbPreviewBox" style="width:100%;height:190px;background:#f8fafc;border-radius:12px;border:1.5px dashed var(--gray-300);display:flex;flex-direction:column;align-items:center;justify-content:center;margin-bottom:12px;overflow:hidden;position:relative;cursor:pointer;transition:all 0.2s ease"
                     onclick="document.getElementById('thumbnailFileInput').click()">
                    
                    <div id="thumbPlaceholder" style="display:{{ $product->thumbnail ? 'none' : 'block' }};text-align:center;padding:16px;">
                        <div style="font-size:2.8rem;line-height:1;margin-bottom:6px;">🐟</div>
                        <div style="font-weight:600;font-size:0.85rem;color:var(--text-primary);">Click to upload thumbnail</div>
                        <div style="font-size:0.72rem;color:var(--text-muted);margin-top:2px;">or choose from media gallery</div>
                    </div>

                    <img id="thumbPreviewImg" src="{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : '' }}"
                         alt="Thumbnail preview" style="display:{{ $product->thumbnail ? 'block' : 'none' }};width:100%;height:100%;object-fit:cover">
                    
                    <button type="button" id="thumbClearBtn" onclick="event.stopPropagation(); clearThumbnailSelection()"
                            style="display:{{ $product->thumbnail ? 'flex' : 'none' }};position:absolute;top:8px;right:8px;background:rgba(15,23,42,0.75);color:white;border:none;border-radius:50%;width:26px;height:26px;cursor:pointer;font-size:0.75rem;align-items:center;justify-content:center;backdrop-filter:blur(4px);">
                        ✕
                    </button>
                </div>

                <div style="display:flex;gap:8px;">
                    <button type="button" class="btn btn-secondary btn-sm" style="flex:1;font-size:0.78rem;" onclick="document.getElementById('thumbnailFileInput').click()">
                        📁 Upload File
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" style="flex:1;font-size:0.78rem;" onclick="pickProductThumbnail()">
                        🖼️ Gallery
                    </button>
                </div>

                <input type="file" name="thumbnail" id="thumbnailFileInput" accept="image/*" style="display:none" onchange="handleThumbnailFile(this)">
                <input type="hidden" name="gallery_thumbnail" id="galleryThumbnailInput" value="{{ $product->thumbnail }}">
            </div>

            <!-- Additional Gallery Images Card -->
            <div class="card">
                <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <span>🖼️</span>
                        <div class="card-title" style="font-size:0.98rem;font-weight:700;">Gallery Images</div>
                    </div>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="pickProductGalleryImages()" style="font-size:0.76rem;padding:4px 8px;">
                        + Gallery
                    </button>
                </div>

                <div id="gallerySelectedBox" style="display:flex;gap:8px;flex-wrap:wrap;min-height:48px;margin-bottom:12px;">
                    @if($product->images && is_array($product->images) && count($product->images))
                        @foreach($product->images as $img)
                        <div style="position:relative;width:68px;height:68px;border-radius:10px;overflow:hidden;border:1.5px solid #cbd5e1;background:#f1f5f9;box-shadow:0 2px 6px rgba(0,0,0,0.06)">
                            <img src="{{ asset('storage/'.$img) }}" style="width:100%;height:100%;object-fit:cover">
                            <input type="hidden" name="existing_images[]" value="{{ $img }}">
                            <button type="button" onclick="this.parentElement.remove()" style="position:absolute;top:3px;right:3px;background:rgba(0,0,0,0.75);color:white;border:none;border-radius:50%;width:18px;height:18px;cursor:pointer;font-size:10px;display:flex;align-items:center;justify-content:center">✕</button>
                        </div>
                        @endforeach
                    @else
                        <div id="galleryEmptyHint" style="width:100%;font-size:0.78rem;color:var(--text-muted);text-align:center;padding:12px;background:#f8fafc;border-radius:8px;border:1px dashed #cbd5e1;">
                            No gallery images added
                        </div>
                    @endif
                </div>

                <div style="display:flex;gap:8px;">
                    <button type="button" class="btn btn-secondary btn-sm" style="flex:1;font-size:0.78rem;" onclick="document.getElementById('nativeGalleryInput').click()">
                        📁 Upload More
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" style="flex:1;font-size:0.78rem;" onclick="pickProductGalleryImages()">
                        + From Gallery
                    </button>
                </div>

                <input type="file" name="images[]" id="nativeGalleryInput" multiple accept="image/*" style="display:none" onchange="handleNativeGallery(this)">
            </div>

            <!-- Settings & Visibility Toggles Card -->
            <div class="card">
                <div class="card-header" style="display:flex;align-items:center;gap:8px;margin-bottom:14px;">
                    <span>⚙️</span>
                    <div class="card-title" style="font-size:0.98rem;font-weight:700;">Visibility &amp; Settings</div>
                </div>

                <div style="display:flex;flex-direction:column;gap:8px;">
                    <!-- Active in Store -->
                    <div class="toggle-switch-card">
                        <label for="is_active" class="toggle-card-label">
                            <span class="toggle-card-title">🟢 Active in Store</span>
                            <span class="toggle-card-desc">Visible to customers in public catalogue</span>
                        </label>
                        <label class="switch-control">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $product->is_active ? '1' : '0') == '1' ? 'checked' : '' }}>
                            <span class="switch-slider"></span>
                        </label>
                    </div>

                    <!-- Available for Walk-in -->
                    <div class="toggle-switch-card">
                        <label for="is_walkin_available" class="toggle-card-label">
                            <span class="toggle-card-title">🏪 Walk-in Available</span>
                            <span class="toggle-card-desc">Enabled for counter QR code visitors</span>
                        </label>
                        <label class="switch-control">
                            <input type="hidden" name="is_walkin_available" value="0">
                            <input type="checkbox" name="is_walkin_available" id="is_walkin_available" value="1" {{ old('is_walkin_available', $product->is_walkin_available ? '1' : '0') == '1' ? 'checked' : '' }}>
                            <span class="switch-slider"></span>
                        </label>
                    </div>

                    <!-- Featured Product -->
                    <div class="toggle-switch-card">
                        <label for="is_featured" class="toggle-card-label">
                            <span class="toggle-card-title">⭐ Featured Highlight</span>
                            <span class="toggle-card-desc">Display in Best Sellers on homepage</span>
                        </label>
                        <label class="switch-control">
                            <input type="hidden" name="is_featured" value="0">
                            <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $product->is_featured ? '1' : '0') == '1' ? 'checked' : '' }}>
                            <span class="switch-slider"></span>
                        </label>
                    </div>

                    <!-- RFQ Only -->
                    <div class="toggle-switch-card">
                        <label for="is_rfq_only" class="toggle-card-label">
                            <span class="toggle-card-title">💬 RFQ Only (Trading)</span>
                            <span class="toggle-card-desc">Quote requests only for wholesale/trading</span>
                        </label>
                        <label class="switch-control">
                            <input type="hidden" name="is_rfq_only" value="0">
                            <input type="checkbox" name="is_rfq_only" id="is_rfq_only" value="1" {{ old('is_rfq_only', $product->is_rfq_only ? '1' : '0') == '1' ? 'checked' : '' }}>
                            <span class="switch-slider"></span>
                        </label>
                    </div>

                    <!-- Track Stock -->
                    <div class="toggle-switch-card">
                        <label for="track_stock" class="toggle-card-label">
                            <span class="toggle-card-title">📊 Track Stock</span>
                            <span class="toggle-card-desc">Auto-decrement inventory upon checkout</span>
                        </label>
                        <label class="switch-control">
                            <input type="hidden" name="track_stock" value="0">
                            <input type="checkbox" name="track_stock" id="track_stock" value="1" {{ old('track_stock', $product->track_stock ? '1' : '0') == '1' ? 'checked' : '' }}>
                            <span class="switch-slider"></span>
                        </label>
                    </div>
                </div>

                <div class="form-group mt-4 mb-0">
                    <label class="form-label" style="font-weight:700;">Catalog Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $product->sort_order ?? 0) }}" min="0">
                    <div class="form-hint" style="font-size:0.75rem;color:var(--text-muted);margin-top:4px;">Lower numbers (0, 1, 2...) appear first</div>
                </div>
            </div>

            <!-- Submit Buttons Box -->
            <div class="card action-buttons-card" style="padding:20px;display:flex;flex-direction:column;gap:14px;">
                <button type="submit" class="btn btn-primary btn-lg btn-block" style="padding:14px;font-size:1rem;font-weight:700;display:flex;align-items:center;justify-content:center;gap:8px;margin:0;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    Save Changes
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-block" style="text-align:center;padding:12px;margin:0;">
                    Cancel &amp; Return
                </a>
            </div>

        </div>

    </div>
</form>

@include('components.gallery-picker-modal')
@endsection

@push('scripts')
<script>
// ─── Thumbnail handlers ───────────────────────────────────────────────────────
function handleThumbnailFile(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.getElementById('thumbPreviewImg');
            img.src = e.target.result;
            img.style.display = 'block';
            document.getElementById('thumbPlaceholder').style.display = 'none';
            document.getElementById('thumbClearBtn').style.display = 'flex';
            document.getElementById('galleryThumbnailInput').value = '';
        };
        reader.readAsDataURL(file);
    }
}

function pickProductThumbnail() {
    openGalleryPicker(function(path, url, name) {
        document.getElementById('galleryThumbnailInput').value = path;
        const img = document.getElementById('thumbPreviewImg');
        img.src = url;
        img.style.display = 'block';
        document.getElementById('thumbPlaceholder').style.display = 'none';
        document.getElementById('thumbClearBtn').style.display = 'flex';
        document.getElementById('thumbnailFileInput').value = '';
    }, false);
}

function clearThumbnailSelection() {
    document.getElementById('galleryThumbnailInput').value = '';
    document.getElementById('thumbnailFileInput').value = '';
    const img = document.getElementById('thumbPreviewImg');
    img.src = '';
    img.style.display = 'none';
    document.getElementById('thumbPlaceholder').style.display = 'block';
    document.getElementById('thumbClearBtn').style.display = 'none';
}

// ─── Gallery handlers ─────────────────────────────────────────────────────────
function handleNativeGallery(input) {
    if (input.files && input.files.length) {
        const box = document.getElementById('gallerySelectedBox');
        const hint = document.getElementById('galleryEmptyHint');
        if (hint) hint.style.display = 'none';

        Array.from(input.files).forEach((file) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const wrapper = document.createElement('div');
                wrapper.style = 'position:relative;width:68px;height:68px;border-radius:10px;overflow:hidden;border:1.5px solid #cbd5e1;background:#f1f5f9;box-shadow:0 2px 6px rgba(0,0,0,0.06)';
                wrapper.innerHTML = `
                    <img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover">
                    <span style="position:absolute;bottom:2px;left:2px;background:rgba(0,0,0,0.6);color:white;font-size:9px;padding:1px 4px;border-radius:4px">Upload</span>
                    <button type="button" onclick="this.parentElement.remove()" style="position:absolute;top:3px;right:3px;background:rgba(0,0,0,0.75);color:white;border:none;border-radius:50%;width:18px;height:18px;cursor:pointer;font-size:10px;display:flex;align-items:center;justify-content:center">✕</button>
                `;
                box.appendChild(wrapper);
            };
            reader.readAsDataURL(file);
        });
    }
}

function pickProductGalleryImages() {
    openGalleryPicker(function(path, url, name) {
        const box = document.getElementById('gallerySelectedBox');
        const hint = document.getElementById('galleryEmptyHint');
        if (hint) hint.style.display = 'none';

        // Avoid exact duplicate
        const existing = box.querySelector(`input[value="${path}"]`);
        if (existing) return;

        const wrapper = document.createElement('div');
        wrapper.style = 'position:relative;width:68px;height:68px;border-radius:10px;overflow:hidden;border:1.5px solid #cbd5e1;background:#f1f5f9;box-shadow:0 2px 6px rgba(0,0,0,0.06)';
        wrapper.innerHTML = `
            <img src="${url}" style="width:100%;height:100%;object-fit:cover">
            <input type="hidden" name="gallery_images[]" value="${path}">
            <button type="button" onclick="this.parentElement.remove()" style="position:absolute;top:3px;right:3px;background:rgba(0,0,0,0.75);color:white;border:none;border-radius:50%;width:18px;height:18px;cursor:pointer;font-size:10px;display:flex;align-items:center;justify-content:center">✕</button>
        `;
        box.appendChild(wrapper);
    }, true);
}

// ─── Custom Specifications ────────────────────────────────────────────────────
function addSpecRow(key = '', val = '') {
    const container = document.getElementById('specsContainer');
    const notice = document.getElementById('noSpecsNotice');
    if (notice) notice.style.display = 'none';

    const row = document.createElement('div');
    row.className = 'spec-row-item';
    row.innerHTML = `
        <div>
            <input type="text" name="spec_keys[]" class="form-control form-control-sm" value="${key.replace(/"/g, '&quot;')}" placeholder="e.g. Catch Method">
        </div>
        <div>
            <input type="text" name="spec_values[]" class="form-control form-control-sm" value="${val.replace(/"/g, '&quot;')}" placeholder="e.g. Wild Caught / Longline">
        </div>
        <div style="text-align:center;">
            <button type="button" class="btn btn-danger btn-sm" onclick="removeSpecRow(this)" style="padding:5px 8px;border-radius:8px;" title="Remove Attribute">✕</button>
        </div>
    `;
    container.appendChild(row);
}

function removeSpecRow(btn) {
    btn.closest('.spec-row-item').remove();
    const container = document.getElementById('specsContainer');
    if (!container.children.length) {
        const notice = document.getElementById('noSpecsNotice');
        if (notice) notice.style.display = 'block';
    }
}

function addPresetSpec(key, defaultVal) {
    addSpecRow(key, defaultVal);
}
</script>
@endpush
