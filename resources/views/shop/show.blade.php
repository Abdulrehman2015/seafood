@extends('layouts.app')
@section('title', $product->name . ' — MST Import and Export Sdn Bhd')
@section('og_title', $product->name)
@section('og_description', $product->short_description ?? $product->name)
@section('og_image', $product->thumbnail ? asset('storage/'.$product->thumbnail) : asset('images/og-default.jpg'))

@section('content')
<div style="padding-top:calc(70px + var(--space-4));padding-bottom:var(--space-16);background:#fcfdfd">
    <div class="container">

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" style="margin-top:var(--space-4);margin-bottom:var(--space-6)">
            <ol class="breadcrumb" style="display:flex;align-items:center;gap:8px;font-size:0.85rem;color:var(--gray-500);list-style:none;padding:0;margin:0;flex-wrap:wrap">
                <li><a href="{{ route('home') }}" style="color:var(--seagreen-700);text-decoration:none">Home</a></li>
                <li>›</li>
                <li><a href="{{ route('shop.index') }}" style="color:var(--seagreen-700);text-decoration:none">Shop</a></li>
                @if($product->category)
                    <li>›</li>
                    <li><a href="{{ route('shop.index', ['category'=>$product->category->slug]) }}" style="color:var(--seagreen-700);text-decoration:none">{{ $product->category->name }}</a></li>
                @endif
                <li>›</li>
                <li style="color:var(--gray-700);font-weight:600">{{ $product->name }}</li>
            </ol>
        </nav>

        <!-- Product Main Container -->
        <div class="product-detail-layout">

            @php
                $allImages = [];
                if ($product->thumbnail) {
                    $allImages[] = asset('storage/' . $product->thumbnail);
                }
                if ($product->images && is_array($product->images)) {
                    foreach ($product->images as $extraImg) {
                        $allImages[] = asset('storage/' . $extraImg);
                    }
                }
            @endphp

            <!-- Left: Interactive Auto-scrolling Gallery -->
            <div class="product-gallery-container" id="productGalleryContainer">
                <div class="card product-gallery-card" style="padding:0;overflow:hidden;border-radius:16px;border:1px solid var(--gray-200);background:white;position:relative">
                    
                    <!-- Main Slider Track -->
                    <div class="product-slider-wrapper" id="productSliderWrapper">
                        @if(count($allImages) > 0)
                            <div class="product-slider-track" id="productSliderTrack">
                                @foreach($allImages as $idx => $imgUrl)
                                    <div class="product-slide">
                                        <img src="{{ $imgUrl }}" alt="{{ $product->name }} - Image {{ $idx + 1 }}" loading="{{ $idx === 0 ? 'eager' : 'lazy' }}">
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div style="font-size:5rem;color:var(--seagreen-300);display:flex;align-items:center;justify-content:center;height:100%">🐟</div>
                        @endif

                        <!-- Floating Badges -->
                        @if($product->origin)
                            <span style="position:absolute;top:14px;left:14px;background:rgba(255,255,255,0.92);backdrop-filter:blur(4px);padding:4px 10px;border-radius:20px;font-size:0.75rem;font-weight:700;color:var(--seagreen-900);border:1px solid #ccfbf1;z-index:5">
                                🌍 {{ $product->origin }}
                            </span>
                        @endif

                        @if($product->is_walkin_available)
                            <span style="position:absolute;top:14px;right:14px;background:rgba(15,118,110,0.9);color:white;padding:4px 10px;border-radius:20px;font-size:0.75rem;font-weight:700;z-index:5">
                                🏪 In-Store Available
                            </span>
                        @endif

                        @if(count($allImages) > 1)
                            <!-- Next / Prev Controls -->
                            <button type="button" class="slider-arrow slider-prev" onclick="prevSlide(event)" aria-label="Previous image">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
                            </button>
                            <button type="button" class="slider-arrow slider-next" onclick="nextSlide(event)" aria-label="Next image">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </button>

                            <!-- Slide Dots Indicator -->
                            <div class="slider-dots" id="sliderDots">
                                @foreach($allImages as $idx => $imgUrl)
                                    <button type="button" class="slider-dot {{ $idx === 0 ? 'active' : '' }}" onclick="goToSlide({{ $idx }})" aria-label="Slide {{ $idx + 1 }}"></button>
                                @endforeach
                            </div>

                            <!-- Slide Counter Badge -->
                            <div class="slider-counter" id="sliderCounter">1 / {{ count($allImages) }}</div>
                        @endif
                    </div>
                </div>

                <!-- Scrollable Thumbnails Row -->
                @if(count($allImages) > 1)
                <div class="product-thumbnails-scroll" id="productThumbsScroll">
                    @foreach($allImages as $idx => $imgUrl)
                    <div class="product-thumb-item {{ $idx === 0 ? 'active' : '' }}" onclick="goToSlide({{ $idx }})" data-index="{{ $idx }}">
                        <img src="{{ $imgUrl }}" alt="Thumb {{ $idx + 1 }}">
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Right: Product Information & Purchase -->
            <div class="product-info-box">
                <div style="font-size:0.8rem;text-transform:uppercase;letter-spacing:1px;color:var(--seagreen-700);font-weight:700;margin-bottom:4px">
                    {{ $product->category?->name ?? 'Fresh Seafood' }}
                </div>
                
                <h1 style="font-size:1.85rem;font-family:var(--font-heading);color:var(--gray-900);line-height:1.25;margin-bottom:var(--space-3)">
                    {{ $product->name }}
                </h1>

                <!-- Stock & Sku Bar -->
                <div style="display:flex;gap:12px;align-items:center;margin-bottom:var(--space-4);flex-wrap:wrap">
                    @if($product->isInStock())
                        <span style="display:inline-flex;align-items:center;gap:6px;background:#d1fae5;color:#065f46;padding:4px 10px;border-radius:20px;font-size:0.75rem;font-weight:700">
                            <span style="width:6px;height:6px;background:#10b981;border-radius:50%"></span>
                            In Stock @if($product->track_stock) ({{ $product->stock_quantity }} {{ $product->unit }} left) @endif
                        </span>
                    @else
                        <span style="display:inline-flex;align-items:center;gap:6px;background:#fee2e2;color:#991b1b;padding:4px 10px;border-radius:20px;font-size:0.75rem;font-weight:700">
                            Out of Stock
                        </span>
                    @endif

                    @if($product->sku)
                        <span class="text-xs text-muted">SKU: <strong style="color:var(--gray-700)">{{ $product->sku }}</strong></span>
                    @endif

                    @if($product->weight)
                        <span class="text-xs text-muted">Weight: <strong style="color:var(--gray-700)">{{ $product->weight }}</strong></span>
                    @endif
                </div>

                <!-- Price Card -->
                @php
                    $displayPrice = $product->getDisplayPrice($group);
                @endphp
                <div class="card mb-5 js-currency-price"
                     data-base-rm="{{ $product->getPriceForGroup($group) ?? 0 }}"
                     data-manual-sgd="{{ $product->price_sgd ?? '' }}"
                     data-manual-usd="{{ $product->price_usd ?? '' }}"
                     @if(in_array($group, ['wholesale','trading']))
                     data-manual-wholesale-sgd="{{ $product->wholesale_price_sgd ?? '' }}"
                     data-manual-wholesale-usd="{{ $product->wholesale_price_usd ?? '' }}"
                     data-group="{{ $group }}"
                     @endif
                     style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:14px;padding:var(--space-4) var(--space-5)"
                >
                    @if($price !== null)
                        <div style="display:flex;align-items:baseline;gap:8px">
                            <span class="price-amount" style="font-size:2rem;font-weight:900;color:#1e40af;font-family:var(--font-heading);line-height:1">
                                {{ $displayPrice['formatted'] }}
                            </span>
                            <span style="font-size:0.9rem;color:var(--gray-600);font-weight:500">
                                / {{ $product->unit ?? 'pack' }}
                            </span>
                        </div>

                        <div class="js-product-approx-note" data-base-rm="{{ $product->getPriceForGroup($group) ?? 0 }}" style="{{ $currentCurrency !== 'MYR' && !empty($displayPrice['base_rm']) ? '' : 'display:none' }};margin-top:4px;font-size:0.85rem;color:#64748b;font-weight:500">
                            Approx. <strong>RM {{ number_format($displayPrice['base_rm'] ?? $product->getPriceForGroup($group), 2) }}</strong> (billed in MYR at checkout)
                        </div>

                        <div style="margin-top:6px;font-size:0.8rem;color:#1d4ed8;display:flex;align-items:center;gap:6px">
                            @auth
                                <span class="badge" style="background:#dbeafe;color:#1e40af;font-weight:700">✓ {{ ucfirst($group) }} Price</span>
                            @else
                                <span>Retail Price · <a href="{{ route('login') }}" style="color:#1d4ed8;font-weight:700;text-decoration:underline">Sign in</a> for Wholesale / B2B rates</span>
                            @endauth
                        </div>
                    @else
                        <div style="font-size:1.6rem;font-weight:800;color:var(--coral)">Price on Request</div>
                        <p class="text-xs text-muted" style="margin-top:4px">Custom quote required based on volume.</p>
                    @endif

                    @if(in_array($group, ['wholesale', 'trading']) && $moq > 1)
                        <div style="margin-top:10px;background:#fef3c7;color:#92400e;padding:6px 12px;border-radius:8px;font-size:0.8rem;font-weight:600;border:1px solid #fde68a">
                            📦 Minimum Order Quantity (MOQ): <strong>{{ $moq }} {{ $product->unit }}</strong>
                        </div>
                    @endif
                </div>

                <!-- Short Description -->
                @if($product->short_description)
                <p style="font-size:0.95rem;color:var(--gray-700);line-height:1.6;margin-bottom:var(--space-5)">
                    {{ $product->short_description }}
                </p>
                @endif

                <!-- Purchase Controls -->
                @if($price !== null && $product->isInStock())
                <form action="{{ route('cart.add') }}" method="POST" id="addToCartForm" class="mb-5">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    <div style="display:flex;gap:var(--space-4);align-items:center;margin-bottom:var(--space-4);flex-wrap:wrap">
                        <label style="font-weight:700;font-size:0.9rem;color:var(--gray-800);margin:0">Quantity:</label>
                        
                        <!-- Tactile Stepper -->
                        <div style="display:inline-flex;align-items:center;border:1.5px solid #cbd5e1;border-radius:10px;background:white;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05)">
                            <button type="button" onclick="changeQty(-1)" 
                                    style="width:40px;height:40px;background:#f8fafc;border:none;font-size:1.15rem;cursor:pointer;color:#334155;font-weight:bold;transition:background 0.15s"
                                    onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">−</button>
                            <input type="number" name="quantity" id="qtyInput" value="{{ $moq }}" min="{{ $moq }}"
                                   max="{{ $product->track_stock ? $product->stock_quantity : 999 }}" inputmode="numeric"
                                   style="width:58px;height:40px;text-align:center;border:none;font-weight:700;font-size:1.05rem;color:#0f172a;outline:none;background:transparent">
                            <button type="button" onclick="changeQty(1)"
                                    style="width:40px;height:40px;background:#f8fafc;border:none;font-size:1.15rem;cursor:pointer;color:#334155;font-weight:bold;transition:background 0.15s"
                                    onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">+</button>
                        </div>

                        <span class="text-xs text-muted" style="color:#64748b;font-weight:500">
                            Min: <strong>{{ $moq }}</strong> {{ $product->unit }}
                        </span>
                    </div>

                    <!-- Action Buttons Cluster -->
                    <div class="product-purchase-cluster">
                        <div class="product-actions-grid">
                            <button type="button" id="btnAddToCartAjax" class="btn-product-cta btn-cta-add-cart">
                                <span class="btn-cta-inner">
                                    <svg class="btn-cta-svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="9" cy="21" r="1"></circle>
                                        <circle cx="20" cy="21" r="1"></circle>
                                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                    </svg>
                                    <span class="btn-cta-label">@t('shop.add_to_cart', 'Add to Cart')</span>
                                </span>
                            </button>

                            <button type="button" id="btnBuyNowAjax" class="btn-product-cta btn-cta-buy-now">
                                <span class="btn-cta-inner">
                                    <svg class="btn-cta-svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path>
                                    </svg>
                                    <span class="btn-cta-label">Buy Now</span>
                                </span>
                            </button>
                        </div>

                        <!-- Dedicated View Cart Bar -->
                        <a href="{{ route('cart.index') }}" class="btn-cta-view-cart">
                            <span class="view-cart-left">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                    <line x1="3" y1="6" x2="21" y2="6"></line>
                                    <path d="M16 10a4 4 0 0 1-8 0"></path>
                                </svg>
                                <span>View Shopping Cart &amp; Checkout</span>
                            </span>
                            <span class="view-cart-right">
                                <span class="view-cart-arrow">→</span>
                            </span>
                        </a>
                    </div>

                    @if(auth()->check() && auth()->user()->customer_group === 'trading' && auth()->user()->isApproved())
                        <!-- RFQ button strictly for registered and approved trading customers -->
                        <div style="margin-top:14px">
                            <a href="{{ route('quotations.create') }}?product={{ $product->id }}" class="btn btn-block btn-lg" style="background:#1e40af;color:white;font-weight:700;padding:12px;border-radius:10px;text-align:center;box-shadow:0 3px 10px rgba(30,64,175,0.25);display:flex;align-items:center;justify-content:center;gap:8px;text-decoration:none">
                                <span>📋 Request for Quotation (RFQ)</span>
                            </a>
                        </div>
                    @endif
                </form>
                @elseif($group === 'trading' && $price === null)
                    <div class="mb-5">
                        <a href="{{ route('quotations.create') }}?product={{ $product->id }}" class="btn btn-primary btn-lg btn-block" style="padding:14px;font-weight:700">
                            📋 Request for Quotation (Bulk Order)
                        </a>
                    </div>
                @elseif(!$product->isInStock())
                    <div class="alert alert-danger mb-5" style="background:#fee2e2;color:#991b1b;border:1px solid #fecaca;padding:12px 16px;border-radius:10px">
                        ⚠ This product is currently out of stock. Please check back soon or submit an inquiry.
                    </div>
                @endif

                <!-- Key Assurances -->
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;padding:var(--space-3) 0;border-top:1px solid var(--gray-200);border-bottom:1px solid var(--gray-200);margin-bottom:var(--space-4);font-size:0.8rem;color:var(--gray-600)">
                    <div style="display:flex;align-items:center;gap:6px">❄️ Flash Frozen at -18°C</div>
                    <div style="display:flex;align-items:center;gap:6px">🚚 Temperature-Controlled Logistics</div>
                    <div style="display:flex;align-items:center;gap:6px">🛡️ Halal & HACCP Certified</div>
                    <div style="display:flex;align-items:center;gap:6px">🏪 Johor Bahru (SILC) Self-Collection Ready</div>
                </div>

                <!-- Share Buttons with Vector SVG Icons -->
                <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
                    <span style="font-size:0.82rem;color:var(--gray-600);font-weight:600">Share Product:</span>
                    
                    <!-- WhatsApp -->
                    <a href="https://wa.me/?text={{ urlencode($product->name . ' — RM ' . number_format($price ?? 0, 2) . ' ' . url()->current()) }}"
                       target="_blank" class="social-share-btn" title="Share on WhatsApp"
                       style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:50%;background:#25D366;color:white;text-decoration:none;transition:transform 0.15s,box-shadow 0.15s;box-shadow:0 2px 6px rgba(37,211,102,0.3)">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0 0 12.04 2zm.01 1.67c4.54 0 8.24 3.7 8.24 8.24 0 2.2-.86 4.27-2.41 5.82a8.18 8.18 0 0 1-5.83 2.42c-1.45 0-2.88-.38-4.14-1.11l-.3-.17-3.12.82.83-3.04-.19-.31a8.21 8.21 0 0 1-1.26-4.43c0-4.54 3.7-8.24 8.24-8.24zm4.52 11.64c-.25-.12-1.47-.72-1.7-.81-.23-.08-.39-.12-.56.12-.17.25-.64.81-.79.97-.14.17-.29.19-.54.06-.25-.12-1.05-.39-2-1.23-.74-.66-1.24-1.47-1.38-1.72-.15-.25-.02-.38.11-.51.11-.11.25-.29.37-.43.12-.15.17-.25.25-.42.08-.17.04-.31-.02-.44-.06-.12-.56-1.34-.76-1.84-.2-.48-.4-.42-.56-.43h-.47c-.17 0-.44.06-.67.31-.23.25-.88.86-.88 2.1 0 1.24.9 2.44 1.03 2.61.12.17 1.78 2.71 4.3 3.8 2.53 1.09 2.53.73 2.99.69.45-.04 1.47-.6 1.68-1.18.2-.58.2-1.08.14-1.18-.06-.1-.22-.16-.47-.28z"/></svg>
                    </a>

                    <!-- Facebook -->
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                       target="_blank" class="social-share-btn" title="Share on Facebook"
                       style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:50%;background:#1877F2;color:white;text-decoration:none;transition:transform 0.15s,box-shadow 0.15s;box-shadow:0 2px 6px rgba(24,119,242,0.3)">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>

                    <!-- X / Twitter -->
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($product->name . ' — Premium Frozen Seafood') }}&url={{ urlencode(url()->current()) }}"
                       target="_blank" class="social-share-btn" title="Share on X"
                       style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:50%;background:#0f172a;color:white;text-decoration:none;transition:transform 0.15s,box-shadow 0.15s;box-shadow:0 2px 6px rgba(15,23,42,0.3)">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>

                    <!-- Copy Link -->
                    <button type="button" onclick="copyLink()" id="copyBtn" class="social-share-btn" title="Copy Product Link"
                            style="display:inline-flex;align-items:center;justify-content:center;gap:6px;height:34px;padding:0 12px;border-radius:17px;background:#f1f5f9;border:1px solid #cbd5e1;color:#334155;font-size:0.78rem;font-weight:600;cursor:pointer;transition:all 0.15s">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
                        <span id="copyBtnText">Copy Link</span>
                    </button>
                </div>
            </div>

        </div>

        <!-- Product Specs & Details Tabs -->
        <div style="margin-top:var(--space-12)">
            <div style="display:flex;gap:4px;border-bottom:2px solid var(--gray-200);margin-bottom:var(--space-6);overflow-x:auto">
                <button class="tab-pill active" onclick="switchTab('desc', this)">Detailed Description</button>
                <button class="tab-pill" onclick="switchTab('specs', this)">Technical Specifications</button>
                <button class="tab-pill" onclick="switchTab('storage', this)">Cold Chain &amp; Handling</button>
                <button class="tab-pill" onclick="switchTab('fulfillment', this)">Delivery &amp; Collection</button>
            </div>

            <!-- Description Content -->
            <div id="tab-desc" class="tab-pane">
                <div class="card" style="padding:var(--space-6);border-radius:14px;border:1px solid var(--gray-200);background:white;max-width:900px;line-height:1.8;color:var(--gray-700)">
                    {!! nl2br(e($product->description ?? 'Premium quality frozen seafood handled under strict cold chain standards.')) !!}
                </div>
            </div>

            <!-- Specs Content -->
            <div id="tab-specs" class="tab-pane" style="display:none">
                <div class="card" style="padding:var(--space-6);border-radius:14px;border:1px solid var(--gray-200);background:white;max-width:850px">
                    <table class="table" style="margin:0">
                        <tbody>
                            <tr>
                                <td style="font-weight:600;color:var(--gray-700);width:35%">Product SKU</td>
                                <td style="color:var(--gray-900);font-family:monospace;font-weight:700">{{ $product->sku ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight:600;color:var(--gray-700)">Country of Origin</td>
                                <td style="color:var(--gray-900)">🌍 {{ $product->origin ?? 'Imported' }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight:600;color:var(--gray-700)">Preservation Method</td>
                                <td style="color:var(--gray-900)">Individually Quick Frozen (IQF) Flash Freezing</td>
                            </tr>
                            <tr>
                                <td style="font-weight:600;color:var(--gray-700)">Glaze Ratio &amp; Weight</td>
                                <td style="color:var(--gray-900)">Protective Ice Glaze (&lt;10%) / 100% Net Weight Guaranteed ({{ $product->weight ?? 'N/A' }})</td>
                            </tr>
                            <tr>
                                <td style="font-weight:600;color:var(--gray-700)">Storage Temperature</td>
                                <td style="color:var(--gray-900);font-weight:700;color:#0f766e">❄️ {{ $product->storage_temp ?? '-18°C' }} or colder</td>
                            </tr>
                            <tr>
                                <td style="font-weight:600;color:var(--gray-700)">Shelf Life</td>
                                <td style="color:var(--gray-900)">24 Months from production date (unopened at -18°C)</td>
                            </tr>
                            <tr>
                                <td style="font-weight:600;color:var(--gray-700)">Wholesale Packaging</td>
                                <td style="color:var(--gray-900)">Master Export Corrugated Carton (MOQ: {{ $product->moq_wholesale }} {{ $product->unit }})</td>
                            </tr>
                            <tr>
                                <td style="font-weight:600;color:var(--gray-700)">Certifications</td>
                                <td style="color:var(--gray-900)">
                                    <span style="display:inline-flex;gap:6px;flex-wrap:wrap">
                                        <span class="badge" style="background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0">✓ JAKIM Halal</span>
                                        <span class="badge" style="background:#eff6ff;color:#1e40af;border:1px solid #bfdbfe">✓ HACCP Safety</span>
                                        <span class="badge" style="background:#f8fafc;color:#475569;border:1px solid #e2e8f0">✓ ISO 22000</span>
                                    </span>
                                </td>
                            </tr>
                            @if($product->specifications && count($product->specifications))
                                @foreach($product->specifications as $key => $val)
                                <tr>
                                    <td style="font-weight:600;color:var(--gray-700)">{{ $key }}</td>
                                    <td style="color:var(--gray-900)">{{ $val }}</td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Storage & Handling -->
            <div id="tab-storage" class="tab-pane" style="display:none">
                <div class="card" style="padding:var(--space-6);border-radius:14px;border:1px solid var(--gray-200);background:white;max-width:800px;line-height:1.7;color:var(--gray-700)">
                    <h3 style="font-size:1.1rem;color:var(--gray-900);font-weight:700;margin-bottom:var(--space-2)">Storage Recommendations</h3>
                    <ul style="padding-left:20px;margin-bottom:var(--space-4)">
                        <li>Keep strictly frozen at <strong>-18°C</strong> or below until ready for preparation.</li>
                        <li>Do not refreeze thawed seafood to preserve texture, moisture, and natural sweetness.</li>
                        <li>For optimal results, thaw in refrigerator (0°C to 4°C) for 8-12 hours before cooking.</li>
                    </ul>
                    @if($product->storage_temp)
                        <div class="text-sm text-muted">Standard Storage Spec: <strong>{{ $product->storage_temp }}</strong></div>
                    @endif
                </div>
            </div>

            <!-- Fulfillment Info -->
            <div id="tab-fulfillment" class="tab-pane" style="display:none">
                <div class="card" style="padding:var(--space-6);border-radius:14px;border:1px solid var(--gray-200);background:white;max-width:800px;line-height:1.7;color:var(--gray-700)">
                    <h3 style="font-size:1.1rem;color:var(--gray-900);font-weight:700;margin-bottom:var(--space-2)">Fulfillment & Collection Options</h3>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-4);margin-top:var(--space-3)">
                        <div style="padding:var(--space-4);border-radius:10px;background:#eff6ff;border:1px solid #bfdbfe">
                            <div style="font-weight:700;color:#1e3a8a;margin-bottom:4px">🏪 Store Self-Collection (FREE)</div>
                            <div class="text-xs text-muted">Collect at our Johor Bahru (SILC) facility. Walk-in customers receive their collection token immediately upon payment.</div>
                        </div>
                        <div style="padding:var(--space-4);border-radius:10px;background:#f8fafc;border:1px solid var(--gray-200)">
                            <div style="font-weight:700;color:var(--gray-900);margin-bottom:4px">🚚 Cold Chain Delivery</div>
                            <div class="text-xs text-muted">Johor &amp; nationwide cold-truck delivery within 24-48 hours. Cross-border Singapore delivery available for wholesale orders.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products Section -->
        @if($related->count())
        <div style="margin-top:var(--space-16)">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:var(--space-6)">
                <h2 style="font-family:var(--font-heading);color:var(--gray-900);font-size:1.5rem;margin:0">Related Seafood Items</h2>
                <a href="{{ route('shop.index') }}" style="color:var(--seagreen-700);font-weight:600;font-size:0.9rem;text-decoration:none">View Full Catalogue →</a>
            </div>
            <div class="products-grid">
                @foreach($related as $rel)
                    @php $relDisplay = $rel->getDisplayPrice($group); @endphp
                    <div class="product-card" style="border-radius:12px;border:1px solid var(--gray-200);background:white;overflow:hidden;display:flex;flex-direction:column">
                        <div class="product-card-img" style="aspect-ratio:4/3;background:#f8fafc;overflow:hidden;position:relative">
                            @if($rel->thumbnail)
                                <img src="{{ asset('storage/'.$rel->thumbnail) }}" alt="{{ $rel->name }}" loading="lazy" style="width:100%;height:100%;object-fit:cover">
                            @else
                                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:2.5rem;color:var(--seagreen-300)">🐟</div>
                            @endif
                            <span class="product-badge" style="position:absolute;top:8px;left:8px;background:rgba(15,118,110,0.85);color:white;padding:3px 8px;border-radius:6px;font-size:0.7rem;font-weight:700">
                                {{ $rel->category?->name ?? 'Fresh' }}
                            </span>
                        </div>
                        <div class="product-card-body" style="padding:var(--space-4);display:flex;flex-direction:column;flex:1">
                            <h3 class="product-name" style="font-size:0.95rem;font-weight:700;margin-bottom:8px;color:var(--gray-900);line-height:1.3">{{ $rel->name }}</h3>
                            <div style="margin-top:auto">
                                <div class="product-price js-currency-price"
                                     data-base-rm="{{ $rel->getPriceForGroup($group) ?? 0 }}"
                                     data-manual-sgd="{{ $rel->price_sgd ?? '' }}"
                                     data-manual-usd="{{ $rel->price_usd ?? '' }}"
                                     @if(in_array($group, ['wholesale','trading']))
                                     data-manual-wholesale-sgd="{{ $rel->wholesale_price_sgd ?? '' }}"
                                     data-manual-wholesale-usd="{{ $rel->wholesale_price_usd ?? '' }}"
                                     data-group="{{ $group }}"
                                     @endif
                                     style="font-size:1.15rem;font-weight:800;color:var(--seagreen-800);font-family:var(--font-heading);margin-bottom:8px"
                                >
                                    <span class="price-amount">{{ $relDisplay['formatted'] }}</span>
                                </div>
                                <a href="{{ route('shop.show', $rel) }}" class="btn btn-secondary btn-sm btn-block" style="font-weight:600">View Product</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>
@endsection

@push('styles')
<style>
.product-detail-layout {
    display: grid;
    grid-template-columns: 1.05fr 1fr;
    gap: var(--space-8, 32px);
    align-items: start;
}

.product-gallery-container {
    position: sticky;
    top: calc(70px + var(--space-6, 24px));
}

.product-slider-wrapper {
    aspect-ratio: 4/3;
    width: 100%;
    overflow: hidden;
    position: relative;
    background: #f8fafc;
    border-radius: 16px;
    user-select: none;
    touch-action: pan-y;
    cursor: grab;
}

.product-slider-wrapper.is-dragging {
    cursor: grabbing;
}

.product-slider-track {
    display: flex;
    width: 100%;
    height: 100%;
    transition: transform 0.45s cubic-bezier(0.22, 1, 0.36, 1);
    will-change: transform;
}

.product-slide {
    min-width: 100%;
    width: 100%;
    height: 100%;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    background: #f8fafc;
}

.product-slide img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    pointer-events: none;
    user-select: none;
}

.slider-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.94);
    border: 1px solid var(--gray-200, #e2e8f0);
    color: var(--gray-800, #1e293b);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
    transition: all 0.2s cubic-bezier(0.22, 1, 0.36, 1);
    opacity: 0.9;
}

.slider-arrow:hover {
    background: #ffffff;
    color: #1d4ed8;
    opacity: 1;
    transform: translateY(-50%) scale(1.1);
    box-shadow: 0 6px 20px rgba(29, 78, 216, 0.25);
}

.slider-prev { left: 12px; }
.slider-next { right: 12px; }

.slider-counter {
    position: absolute;
    bottom: 12px;
    right: 12px;
    background: rgba(15, 23, 42, 0.75);
    backdrop-filter: blur(6px);
    color: white;
    font-size: 0.75rem;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 20px;
    z-index: 8;
    letter-spacing: 0.05em;
    pointer-events: none;
}

.slider-dots {
    position: absolute;
    bottom: 12px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    align-items: center;
    gap: 6px;
    z-index: 8;
    padding: 4px 10px;
    background: rgba(15, 23, 42, 0.4);
    backdrop-filter: blur(4px);
    border-radius: 20px;
}

.slider-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.5);
    border: none;
    padding: 0;
    cursor: pointer;
    transition: all 0.25s ease;
}

.slider-dot.active {
    width: 22px;
    border-radius: 10px;
    background: #ffffff;
}

.product-thumbnails-scroll {
    display: flex;
    gap: 10px;
    margin-top: 14px;
    overflow-x: auto;
    padding: 4px 2px;
    scrollbar-width: thin;
    -webkit-overflow-scrolling: touch;
    scroll-behavior: smooth;
}

.product-thumbnails-scroll::-webkit-scrollbar {
    height: 5px;
}
.product-thumbnails-scroll::-webkit-scrollbar-thumb {
    background: var(--gray-300, #cbd5e1);
    border-radius: 10px;
}

.product-thumb-item {
    width: 76px;
    height: 76px;
    border-radius: 12px;
    overflow: hidden;
    border: 2px solid var(--gray-200, #e2e8f0);
    cursor: pointer;
    flex-shrink: 0;
    background: #f8fafc;
    transition: all 0.2s cubic-bezier(0.22, 1, 0.36, 1);
}

.product-thumb-item:hover {
    border-color: #60a5fa;
    transform: translateY(-2px);
}

.product-thumb-item.active {
    border-color: #2563eb !important;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.25);
    transform: scale(1.04);
}

.product-thumb-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    pointer-events: none;
}

.tab-pill {
    padding: 10px 20px;
    font-weight: 600;
    font-size: 0.9rem;
    color: var(--gray-600);
    border: none;
    border-bottom: 3px solid transparent;
    background: none;
    cursor: pointer;
    transition: all 0.15s ease;
    white-space: nowrap;
}
.tab-pill.active {
    color: var(--seagreen-700);
    border-bottom-color: var(--seagreen-600);
    font-weight: 700;
}
.tab-pill:hover {
    color: var(--seagreen-600);
}

@media (max-width: 850px) {
    .product-detail-layout {
        grid-template-columns: 1fr !important;
        gap: var(--space-6) !important;
    }
    .product-gallery-container {
        position: static;
    }
    .slider-arrow {
        width: 36px;
        height: 36px;
    }
}

/* Product Purchase Cluster */
.product-purchase-cluster {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: var(--space-5, 20px);
}

.product-actions-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.btn-product-cta {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 12px 14px;
    min-height: 48px;
    border-radius: 12px;
    font-family: inherit;
    font-size: 0.95rem;
    font-weight: 700;
    line-height: 1.2;
    white-space: nowrap;
    cursor: pointer;
    border: none;
    outline: none;
    transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
    user-select: none;
    -webkit-tap-highlight-color: transparent;
}

.btn-cta-inner {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    white-space: nowrap;
}

.btn-cta-svg {
    flex-shrink: 0;
    transition: transform 0.2s ease;
}

/* Add to Cart: Royal / Deep Blue */
.btn-cta-add-cart {
    background: linear-gradient(135deg, #1e40af 0%, #0f274a 100%);
    color: #ffffff;
    box-shadow: 0 4px 14px rgba(30, 64, 175, 0.28);
}
.btn-cta-add-cart:hover {
    background: linear-gradient(135deg, #2563eb 0%, #1e3a8a 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(30, 64, 175, 0.38);
}
.btn-cta-add-cart:hover .btn-cta-svg {
    transform: scale(1.1);
}
.btn-cta-add-cart:active {
    transform: scale(0.98);
}

/* Buy Now: High-Conversion Amber/Orange */
.btn-cta-buy-now {
    background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%);
    color: #ffffff;
    box-shadow: 0 4px 14px rgba(234, 88, 12, 0.32);
}
.btn-cta-buy-now:hover {
    background: linear-gradient(135deg, #fbbf24 0%, #f97316 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(234, 88, 12, 0.42);
}
.btn-cta-buy-now:hover .btn-cta-svg {
    transform: scale(1.15) rotate(-5deg);
}
.btn-cta-buy-now:active {
    transform: scale(0.98);
}

/* View Cart Secondary Bar */
.btn-cta-view-cart {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 16px;
    min-height: 44px;
    background: #ffffff;
    border: 1.5px solid #cbd5e1;
    border-radius: 11px;
    color: #1e3a8a;
    font-size: 0.88rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
}
.btn-cta-view-cart:hover {
    background: #f8fafc;
    border-color: #94a3b8;
    color: #0f172a;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
}
.btn-cta-view-cart:hover .view-cart-arrow {
    transform: translateX(4px);
}
.view-cart-left {
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.view-cart-arrow {
    font-size: 1rem;
    color: #64748b;
    transition: transform 0.2s ease;
}

.btn-spinner {
    width: 16px;
    height: 16px;
    border: 2px solid rgba(255,255,255,0.35);
    border-top-color: #ffffff;
    border-radius: 50%;
    animation: btnSpin 0.7s linear infinite;
    display: inline-block;
    flex-shrink: 0;
}
@keyframes btnSpin {
    to { transform: rotate(360deg); }
}

@media (min-width: 640px) {
    .product-actions-grid {
        gap: 12px;
    }
    .btn-product-cta {
        padding: 13px 20px;
        font-size: 1rem;
        min-height: 50px;
    }
    .btn-cta-view-cart {
        padding: 11px 18px;
        font-size: 0.92rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
const moq = {{ $moq }};
const maxStock = {{ $product->track_stock ? $product->stock_quantity : 9999 }};

function changeQty(delta) {
    const input = document.getElementById('qtyInput');
    let val = (parseInt(input.value) || moq) + delta;
    val = Math.max(moq, Math.min(maxStock, val));
    input.value = val;
}

const qtyEl = document.getElementById('qtyInput');
if (qtyEl) {
    qtyEl.addEventListener('change', function() {
        let val = parseInt(this.value);
        if (isNaN(val) || val < moq) val = moq;
        if (val > maxStock) val = maxStock;
        this.value = val;
    });
}

// Product Image Slider: Manual Scroll, Swipe, Drag & Thumbnails
let currentSlide = 0;
const totalSlides = {{ count($allImages) }};
const sliderTrack = document.getElementById('productSliderTrack');
const sliderWrapper = document.getElementById('productSliderWrapper');
const sliderCounter = document.getElementById('sliderCounter');
const sliderDots = document.querySelectorAll('.slider-dot');
const thumbItems = document.querySelectorAll('.product-thumb-item');

let isDragging = false;
let startX = 0;
let currentTranslate = 0;
let prevTranslate = 0;

function updateSlider(index, animate = true) {
    if (totalSlides <= 0 || !sliderTrack) return;
    currentSlide = (index + totalSlides) % totalSlides;

    if (!animate) {
        sliderTrack.style.transition = 'none';
    } else {
        sliderTrack.style.transition = 'transform 0.45s cubic-bezier(0.22, 1, 0.36, 1)';
    }

    currentTranslate = -currentSlide * 100;
    prevTranslate = currentTranslate;
    sliderTrack.style.transform = `translateX(${currentTranslate}%)`;

    // Update Counter
    if (sliderCounter) {
        sliderCounter.textContent = `${currentSlide + 1} / ${totalSlides}`;
    }

    // Update Dots
    sliderDots.forEach((dot, i) => {
        dot.classList.toggle('active', i === currentSlide);
    });

    // Update Thumbnails
    thumbItems.forEach((thumb, i) => {
        const isActive = i === currentSlide;
        thumb.classList.toggle('active', isActive);
        if (isActive) {
            thumb.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
        }
    });
}

function nextSlide(e) {
    if (e && e.stopPropagation) e.stopPropagation();
    updateSlider(currentSlide + 1);
}

function prevSlide(e) {
    if (e && e.stopPropagation) e.stopPropagation();
    updateSlider(currentSlide - 1);
}

function goToSlide(index) {
    updateSlider(index);
}

// User Interactive Gestures: Touch Swipe, Mouse Drag, Wheel Scroll
if (sliderWrapper) {
    // Touch Swipe Support (Mobile & Tablet)
    let touchStartX = 0;
    let touchEndX = 0;
    let touchStartY = 0;

    sliderWrapper.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].clientX;
        touchStartY = e.changedTouches[0].clientY;
    }, { passive: true });

    sliderWrapper.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].clientX;
        const touchEndY = e.changedTouches[0].clientY;
        const diffX = touchEndX - touchStartX;
        const diffY = touchEndY - touchStartY;

        // Ensure horizontal swipe is dominant
        if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 30) {
            if (diffX < 0) {
                nextSlide();
            } else {
                prevSlide();
            }
        }
    }, { passive: true });

    // Mouse Drag / Swipe Support (Desktop)
    let dragStartX = 0;
    let dragDistance = 0;

    sliderWrapper.addEventListener('mousedown', (e) => {
        if (e.target.closest('.slider-arrow') || e.target.closest('.slider-dot')) return;
        isDragging = true;
        dragStartX = e.clientX;
        dragDistance = 0;
        sliderWrapper.classList.add('is-dragging');
        sliderTrack.style.transition = 'none';
    });

    window.addEventListener('mousemove', (e) => {
        if (!isDragging) return;
        dragDistance = e.clientX - dragStartX;
        const wrapperWidth = sliderWrapper.offsetWidth || 1;
        const percentMoved = (dragDistance / wrapperWidth) * 100;
        sliderTrack.style.transform = `translateX(${prevTranslate + percentMoved}%)`;
    });

    window.addEventListener('mouseup', (e) => {
        if (!isDragging) return;
        isDragging = false;
        sliderWrapper.classList.remove('is-dragging');
        sliderTrack.style.transition = 'transform 0.45s cubic-bezier(0.22, 1, 0.36, 1)';
        
        if (dragDistance < -40) {
            nextSlide();
        } else if (dragDistance > 40) {
            prevSlide();
        } else {
            updateSlider(currentSlide);
        }
    });

    // Horizontal Mouse Wheel / Trackpad Scroll on Slider
    let wheelTimeout = null;
    sliderWrapper.addEventListener('wheel', (e) => {
        if (Math.abs(e.deltaX) > Math.abs(e.deltaY) && Math.abs(e.deltaX) > 20) {
            e.preventDefault();
            if (!wheelTimeout) {
                if (e.deltaX > 0) nextSlide();
                else prevSlide();
                wheelTimeout = setTimeout(() => { wheelTimeout = null; }, 350);
            }
        }
    }, { passive: false });
}

function switchTab(id, btn) {
    document.querySelectorAll('.tab-pane').forEach(t => t.style.display = 'none');
    document.querySelectorAll('.tab-pill').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + id).style.display = 'block';
    btn.classList.add('active');
}

async function copyLink() {
    try {
        await navigator.clipboard.writeText(window.location.href);
        const textEl = document.getElementById('copyBtnText');
        if (textEl) {
            textEl.textContent = 'Copied!';
            setTimeout(() => textEl.textContent = 'Copy Link', 2000);
        }
    } catch(e) {}
}

// AJAX 1-Tap Add to Cart on Detail Page
const cartForm = document.getElementById('addToCartForm');
const btnAdd = document.getElementById('btnAddToCartAjax');
if (cartForm && btnAdd) {
    cartForm.addEventListener('submit', function(e) {
        e.preventDefault();
        btnAdd.click();
    });
}
if (btnAdd) {
    const origAddHtml = btnAdd.innerHTML;
    btnAdd.addEventListener('click', async function() {
        const qty = parseInt(document.getElementById('qtyInput').value) || 1;
        this.disabled = true;
        this.innerHTML = '<span class="btn-cta-inner"><span class="btn-spinner"></span><span>Adding...</span></span>';

        try {
            const res = await fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    product_id: {{ $product->id }},
                    quantity: qty
                })
            });
            const data = await res.json();
            if (data.success) {
                this.style.background = 'linear-gradient(135deg, #059669 0%, #047857 100%)';
                this.style.boxShadow = '0 4px 14px rgba(5, 150, 105, 0.4)';
                this.innerHTML = '<span class="btn-cta-inner"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg><span>Added to Cart!</span></span>';
                
                if (typeof updateCartCount === 'function') updateCartCount();
                
                if (typeof showGlobalToast === 'function') {
                    showGlobalToast('✓ Added ' + qty + 'x to your cart!', 'success');
                } else {
                    showDetailToast('✓ Added ' + qty + 'x to your cart!');
                }

                setTimeout(() => {
                    this.disabled = false;
                    this.style.background = '';
                    this.style.boxShadow = '';
                    this.innerHTML = origAddHtml;
                }, 2000);
            } else {
                if (typeof showGlobalToast === 'function') {
                    showGlobalToast(data.message || 'Could not add to cart', 'error');
                } else {
                    alert(data.message || 'Could not add to cart');
                }
                this.disabled = false;
                this.innerHTML = origAddHtml;
            }
        } catch (e) {
            if (typeof showGlobalToast === 'function') {
                showGlobalToast('Error adding product to cart.', 'error');
            } else {
                alert('Error adding product to cart.');
            }
            this.disabled = false;
            this.innerHTML = origAddHtml;
        }
    });
}

const btnBuyNow = document.getElementById('btnBuyNowAjax');
if (btnBuyNow) {
    const origBuyHtml = btnBuyNow.innerHTML;
    btnBuyNow.addEventListener('click', async function() {
        const qty = parseInt(document.getElementById('qtyInput').value) || 1;
        this.disabled = true;
        this.innerHTML = '<span class="btn-cta-inner"><span class="btn-spinner"></span><span>Redirecting...</span></span>';

        try {
            const res = await fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    product_id: {{ $product->id }},
                    quantity: qty,
                    buy_now: 1
                })
            });
            const data = await res.json();
            if (data.success && data.redirect) {
                window.location.href = data.redirect;
            } else if (data.success) {
                window.location.href = '{{ route("checkout.index") }}';
            } else {
                alert(data.message || 'Could not initiate buy now.');
                this.disabled = false;
                this.innerHTML = origBuyHtml;
            }
        } catch (e) {
            alert('Error initiating checkout.');
            this.disabled = false;
            this.innerHTML = origBuyHtml;
        }
    });
}

// Reset button states when user navigates back (bfcache restore)
window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
        const btnBuyNowEl = document.getElementById('btnBuyNowAjax');
        if (btnBuyNowEl) {
            btnBuyNowEl.disabled = false;
            btnBuyNowEl.innerHTML = origBuyHtml;
            btnBuyNowEl.style.background = '';
            btnBuyNowEl.style.boxShadow = '';
        }
        const btnAddEl = document.getElementById('btnAddToCartAjax');
        if (btnAddEl) {
            btnAddEl.disabled = false;
            btnAddEl.innerHTML = origAddHtml;
            btnAddEl.style.background = '';
            btnAddEl.style.boxShadow = '';
        }
    }
});

function showDetailToast(msg) {
    let toast = document.getElementById('productDetailToast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'productDetailToast';
        toast.style.cssText = 'position:fixed;bottom:24px;left:50%;transform:translateX(-50%) translateY(100px);background:#091a36;color:#ffffff;padding:12px 22px;border-radius:999px;box-shadow:0 12px 32px rgba(0,0,0,0.35);z-index:9999;font-size:0.88rem;font-weight:600;display:flex;align-items:center;gap:14px;border:1.5px solid #2563eb;transition:transform 0.35s cubic-bezier(0.16,1,0.3,1), opacity 0.3s ease;opacity:0;pointer-events:none;max-width:92vw;';
        document.body.appendChild(toast);
    }
    toast.innerHTML = '<span style="color:#7dd3fc">' + msg + '</span> <a href="{{ route("cart.index") }}" style="color:#fde047;font-weight:700;text-decoration:underline;white-space:nowrap">View Cart →</a>';
    toast.style.transform = 'translateX(-50%) translateY(0)';
    toast.style.opacity = '1';
    toast.style.pointerEvents = 'auto';

    clearTimeout(toast.timeout);
    toast.timeout = setTimeout(() => {
        toast.style.transform = 'translateX(-50%) translateY(100px)';
        toast.style.opacity = '0';
        toast.style.pointerEvents = 'none';
    }, 4000);
}
</script>
@endpush
