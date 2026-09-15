@extends('layouts.app')
@section('title', $product->name . ' (Walk-in) — MST Import and Export Sdn Bhd')

@section('content')
<!-- Walk-in Header Banner -->
<div style="background:linear-gradient(135deg,#f0fdfa,#f5f3ff);border-bottom:1px solid #ccfbf1;padding:var(--space-5) 0;padding-top:calc(80px + var(--space-5))">
    <div class="container" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:var(--space-4)">
        <div style="display:flex;align-items:center;gap:var(--space-4)">
            <div style="font-size:2.2rem">🏪</div>
            <div>
                <h1 style="font-size:1.6rem;font-family:var(--font-heading);margin-bottom:var(--space-1);color:var(--seagreen-800)">Walk-in Product Detail</h1>
                <p class="text-sm text-muted">In-store exclusive counter prices · Instant Store Collection</p>
            </div>
        </div>
        <div style="display:flex;gap:var(--space-3);align-items:center">
            <a href="{{ route('walkin.shop') }}" class="btn btn-secondary">← Back to Catalogue</a>
            <a href="{{ route('walkin.checkout') }}" class="btn btn-primary">Walk-in Checkout →</a>
        </div>
    </div>
</div>

<div class="container" style="padding:var(--space-8) 0 var(--space-16)">
    <div class="product-detail-grid">
        <!-- Product Images -->
        <div>
            <div style="background:#f8fafc;border:1px solid var(--gray-200);border-radius:var(--radius-xl);overflow:hidden;margin-bottom:var(--space-4);aspect-ratio:4/3;display:flex;align-items:center;justify-content:center;">
                @if($product->thumbnail)
                    <img id="mainImage" src="{{ asset('storage/'.$product->thumbnail) }}" alt="{{ $product->name }}" style="width:100%;height:100%;object-fit:cover;">
                @else
                    <div style="font-size:5rem;">🐟</div>
                @endif
            </div>

            @if(!empty($product->images) && is_array($product->images))
                <div style="display:flex;gap:var(--space-3);overflow-x:auto;">
                    @if($product->thumbnail)
                        <img src="{{ asset('storage/'.$product->thumbnail) }}" alt="Thumb" style="width:72px;height:72px;object-fit:cover;border-radius:var(--radius-md);cursor:pointer;border:2px solid var(--seagreen-600);" onclick="document.getElementById('mainImage').src=this.src">
                    @endif
                    @foreach($product->images as $img)
                        <img src="{{ asset('storage/'.$img) }}" alt="Gallery" style="width:72px;height:72px;object-fit:cover;border-radius:var(--radius-md);cursor:pointer;border:1px solid var(--gray-300);" onclick="document.getElementById('mainImage').src=this.src">
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Product Info -->
        <div>
            <div style="display:flex;gap:var(--space-2);margin-bottom:var(--space-3);flex-wrap:wrap;">
                <span class="badge badge-walkin" style="font-size:0.8rem;padding:4px 12px">🏪 Walk-in Counter Item</span>
                @if($product->category)
                    <span class="badge badge-info" style="font-size:0.8rem;padding:4px 12px">{{ $product->category->name }}</span>
                @endif
            </div>

            <h1 style="font-family:var(--font-heading);font-size:2rem;font-weight:800;margin-bottom:var(--space-2);color:var(--text-primary)">
                {{ $product->name }}
            </h1>

            @if($product->sku)
                <div class="text-xs text-muted mb-4">SKU: {{ $product->sku }}</div>
            @endif

            <!-- Price Box -->
            <div style="background:var(--seagreen-50);border:1px solid var(--seagreen-200);border-radius:var(--radius-lg);padding:var(--space-5);margin-bottom:var(--space-6)">
                <div style="font-size:0.85rem;text-transform:uppercase;letter-spacing:0.05em;color:var(--seagreen-700);margin-bottom:var(--space-1);font-weight:700">In-Store Walk-in Price</div>
                <div style="display:flex;align-items:baseline;gap:var(--space-3)">
                    <span style="font-family:var(--font-heading);font-size:2.5rem;font-weight:800;color:var(--seagreen-700)">
                        RM {{ number_format($price, 2) }}
                    </span>
                    <span class="text-muted">/ {{ $product->unit }}</span>
                </div>
                <div style="font-size:0.875rem;color:var(--text-secondary);margin-top:var(--space-2)">
                    📍 Pay in-store or online and collect directly at the retail frozen counter.
                </div>
            </div>

            @if($product->short_description)
                <p style="color:var(--text-secondary);line-height:1.6;margin-bottom:var(--space-6)">
                    {{ $product->short_description }}
                </p>
            @endif

            <!-- Add to Cart Form -->
            <form action="{{ route('cart.add') }}" method="POST" class="mb-6">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <div style="display:flex;gap:var(--space-4);align-items:center;margin-bottom:var(--space-5);flex-wrap:wrap">
                    <div>
                        <label class="form-label" style="font-size:0.8rem;text-transform:uppercase;letter-spacing:0.05em;color:var(--text-muted);display:block;margin-bottom:var(--space-2)">Quantity</label>
                        <div style="display:inline-flex;align-items:center;background:var(--white);border:1.5px solid var(--gray-300);border-radius:var(--radius-md)">
                            <button type="button" onclick="const q=document.getElementById('qty');if(q.value>1)q.value--" style="background:none;border:none;color:var(--text-primary);padding:10px 16px;cursor:pointer;font-size:1.1rem">−</button>
                            <input type="number" id="qty" name="quantity" value="1" min="1" style="width:60px;text-align:center;background:none;border:none;color:var(--text-primary);font-weight:700;font-size:1.1rem" readonly>
                            <button type="button" onclick="const q=document.getElementById('qty');q.value++" style="background:none;border:none;color:var(--text-primary);padding:10px 16px;cursor:pointer;font-size:1.1rem">+</button>
                        </div>
                    </div>

                    <div style="flex:1;min-width:200px;margin-top:24px">
                        <button type="submit" class="btn btn-primary" style="width:100%;padding:14px 24px;font-size:1rem;display:flex;align-items:center;justify-content:center;gap:var(--space-2)">
                            🛒 Add to Walk-in Cart
                        </button>
                    </div>
                </div>
            </form>

            <!-- Specifications -->
            <div class="card" style="padding:var(--space-5);background:var(--white)">
                <h4 style="font-size:0.95rem;font-weight:700;margin-bottom:var(--space-4);color:var(--text-primary)">Product Specifications</h4>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-3);font-size:0.875rem">
                    @if($product->weight)
                        <div class="text-muted">Weight / Spec:</div>
                        <div style="color:var(--text-primary);font-weight:500">{{ $product->weight }}</div>
                    @endif
                    @if($product->origin)
                        <div class="text-muted">Origin:</div>
                        <div style="color:var(--text-primary);font-weight:500">{{ $product->origin }}</div>
                    @endif
                    @if($product->storage_temp)
                        <div class="text-muted">Storage Temp:</div>
                        <div style="color:var(--text-primary);font-weight:500">{{ $product->storage_temp }}</div>
                    @endif
                    @if($product->brand)
                        <div class="text-muted">Brand:</div>
                        <div style="color:var(--text-primary);font-weight:500">{{ $product->brand }}</div>
                    @endif
                    <div class="text-muted">Fulfillment:</div>
                    <div style="color:var(--teal-300);font-weight:600">Store Self-Collection</div>
                </div>
            </div>

            @if($product->description)
                <div style="margin-top:var(--space-6)">
                    <h4 style="font-size:0.95rem;font-weight:700;margin-bottom:var(--space-3);color:var(--text-primary)">Description</h4>
                    <div style="color:var(--text-secondary);font-size:0.9rem;line-height:1.7">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
