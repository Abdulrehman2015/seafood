@extends('layouts.app')
@section('title', 'Walk-in Catalogue — MST Import and Export Sdn Bhd')

@section('content')
<!-- Walk-in Header Banner & Process Stepper -->
<div style="background:linear-gradient(135deg,#f0fdfa,#e6fffa);border-bottom:1px solid #ccfbf1;padding:var(--space-5) 0;padding-top:calc(75px + var(--space-4))">
    <div class="container">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:var(--space-4);margin-bottom:var(--space-4)">
            <div style="display:flex;align-items:center;gap:var(--space-3)">
                <div style="font-size:2rem;background:#ccfbf1;width:48px;height:48px;display:flex;align-items:center;justify-content:center;border-radius:12px">🏪</div>
                <div>
                    <h1 style="font-size:1.4rem;font-family:var(--font-heading);margin-bottom:2px;color:var(--seagreen-800)">Walk-in Express Catalogue</h1>
                    <p class="text-xs text-muted" style="margin:0">SILC Industrial, Iskandar Puteri · In-Store Counter Pricing · Instant Pickup</p>
                </div>
            </div>
            <div style="display:flex;gap:var(--space-2);align-items:center">
                <a href="{{ route('cart.index') }}" class="cart-btn" style="position:relative;padding:8px 14px;border-radius:10px;background:var(--white);border:1px solid var(--gray-200);display:flex;align-items:center;gap:6px;font-weight:600;color:var(--seagreen-800);text-decoration:none">
                    🛒 Cart (<span id="walkinCartCount">0</span>)
                </a>
                <a href="{{ route('walkin.checkout') }}" class="btn btn-primary btn-sm" style="font-weight:600;padding:8px 14px">
                    Checkout →
                </a>
                <a href="{{ route('walkin.exit') }}" class="btn btn-secondary btn-sm" style="padding:8px 10px;font-size:0.75rem;color:var(--gray-500)" title="Exit in-store mode">
                    ✕ Exit
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
            <div style="display:flex;align-items:center;gap:6px;font-size:0.8rem;color:var(--seagreen-800);font-weight:700;white-space:nowrap;background:var(--seagreen-50);padding:4px 8px;border-radius:6px;border:1px solid var(--seagreen-200)">
                <span style="background:var(--seagreen-600);color:white;width:20px;height:20px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:0.7rem">2</span>
                2. Pick Seafood
            </div>
            <span style="color:#99f6e4;font-size:0.8rem">→</span>
            <div style="display:flex;align-items:center;gap:6px;font-size:0.8rem;color:var(--gray-400);font-weight:500;white-space:nowrap">
                <span style="background:var(--gray-100);color:var(--gray-500);width:20px;height:20px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:0.7rem">3</span>
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

<!-- Search & Category Filters -->
<div style="background:var(--white);border-bottom:1px solid var(--gray-200);padding:var(--space-3) 0;position:sticky;top:60px;z-index:30;box-shadow:0 2px 4px rgba(0,0,0,0.02)">
    <div class="container">
        <form method="GET" action="{{ route('walkin.shop') }}" style="margin-bottom:var(--space-3)">
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            <div style="position:relative">
                <input type="text" name="search" class="form-control" placeholder="🔍 Search seafood (e.g. Tiger Prawns, Salmon, Cod)..."
                       value="{{ request('search') }}" style="padding-left:14px;border-radius:10px;height:42px;font-size:0.9rem">
            </div>
        </form>

        <!-- Category Pill Bar -->
        <div style="display:flex;gap:8px;overflow-x:auto;padding-bottom:4px;scrollbar-width:none">
            <a href="{{ route('walkin.shop', request()->only('search')) }}"
               class="category-pill {{ !request('category') ? 'active' : '' }}"
               style="text-decoration:none;padding:6px 14px;border-radius:20px;font-size:0.825rem;font-weight:600;white-space:nowrap;transition:all 0.15s;border:1px solid {{ !request('category') ? 'var(--seagreen-600)' : 'var(--gray-200)' }};background:{{ !request('category') ? 'var(--seagreen-600)' : 'var(--white)' }};color:{{ !request('category') ? 'white' : 'var(--gray-700)' }}">
               All Products
            </a>
            @if(isset($categories))
                @foreach($categories as $category)
                <a href="{{ route('walkin.shop', array_merge(request()->only('search'), ['category' => $category->slug])) }}"
                   class="category-pill {{ request('category') === $category->slug ? 'active' : '' }}"
                   style="text-decoration:none;padding:6px 14px;border-radius:20px;font-size:0.825rem;font-weight:600;white-space:nowrap;transition:all 0.15s;border:1px solid {{ request('category') === $category->slug ? 'var(--seagreen-600)' : 'var(--gray-200)' }};background:{{ request('category') === $category->slug ? 'var(--seagreen-600)' : 'var(--white)' }};color:{{ request('category') === $category->slug ? 'white' : 'var(--gray-700)' }}">
                   {{ $category->name }}
                </a>
                @endforeach
            @endif
        </div>
    </div>
</div>

<!-- Products Grid -->
<div class="container" style="padding:var(--space-6) 0 100px">
    @if($products->count())
        <div class="products-grid">
            @foreach($products as $product)
            <div class="product-card" id="product-card-{{ $product->id }}" style="border-radius:12px;overflow:hidden;border:1px solid var(--gray-200);background:white;display:flex;flex-direction:column;transition:transform 0.15s, box-shadow 0.15s">
                <div class="product-card-img" style="position:relative;background:var(--gray-50);aspect-ratio:4/3;overflow:hidden">
                    @if($product->thumbnail)
                        <img src="{{ asset('storage/'.$product->thumbnail) }}" alt="{{ $product->name }}" loading="lazy" style="width:100%;height:100%;object-fit:cover">
                    @else
                        <div class="product-img-placeholder" style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:2.5rem;background:#f0fdfa">🐟</div>
                    @endif
                    <span class="product-badge badge-walkin" style="position:absolute;top:10px;left:10px;background:rgba(15,118,110,0.9);color:white;padding:4px 8px;border-radius:6px;font-size:0.75rem;font-weight:700">🏪 In-Store</span>
                    @if($product->weight)
                        <span style="position:absolute;bottom:8px;right:8px;background:rgba(0,0,0,0.6);color:white;padding:2px 8px;border-radius:6px;font-size:0.7rem">⚖ {{ $product->weight }}</span>
                    @endif
                </div>
                <div class="product-card-body" style="padding:var(--space-4);display:flex;flex-direction:column;flex:1">
                    <div class="product-category" style="font-size:0.75rem;color:var(--seagreen-700);font-weight:600;text-transform:uppercase;margin-bottom:2px">{{ $product->category?->name ?? 'Seafood' }}</div>
                    <h3 class="product-name" style="font-size:1rem;font-family:var(--font-heading);margin-bottom:var(--space-2);line-height:1.3;color:var(--gray-900)">{{ $product->name }}</h3>
                    
                    <div style="margin-top:auto">
                        <div style="display:flex;align-items:baseline;justify-content:space-between;margin-bottom:var(--space-3)">
                            <div>
                                <span class="text-xs text-muted" style="display:block">Walk-in Price</span>
                                <div style="font-size:1.25rem;font-weight:800;color:var(--seagreen-700);font-family:var(--font-heading)">
                                    RM {{ number_format($product->walkin_price, 2) }}
                                    <span style="font-size:0.75rem;font-weight:normal;color:var(--gray-500)">/ {{ $product->unit ?? 'pack' }}</span>
                                </div>
                            </div>
                        </div>

                        <div style="display:flex;gap:8px">
                            <a href="{{ route('walkin.show', $product) }}" class="btn btn-secondary btn-sm" style="padding:8px 12px;font-size:0.8rem" title="View details">Info</a>
                            <button type="button" class="btn btn-primary btn-sm btn-add-ajax" 
                                    data-product-id="{{ $product->id }}" 
                                    style="flex:1;font-weight:600;display:flex;align-items:center;justify-content:center;gap:4px">
                                <span>+ Add to Cart</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($products->hasPages())
        <div class="pagination" style="margin-top:var(--space-8)">{{ $products->links() }}</div>
        @endif

    @else
        <div class="empty-state" style="text-align:center;padding:var(--space-12) var(--space-4);background:white;border-radius:12px;border:1px solid var(--gray-200)">
            <div class="empty-icon" style="font-size:3rem;margin-bottom:var(--space-2)">🐟</div>
            <div class="empty-title" style="font-size:1.2rem;font-weight:700;margin-bottom:var(--space-1)">No Walk-in Products Found</div>
            <p class="empty-text text-muted" style="font-size:0.9rem;margin-bottom:var(--space-4)">Try selecting a different category or clearing your search.</p>
            <a href="{{ route('walkin.shop') }}" class="btn btn-secondary">View All Products</a>
        </div>
    @endif
</div>

<!-- Sticky Bottom Checkout Bar (Mobile & Desktop) -->
<div id="stickyCheckoutBar" style="position:fixed;bottom:0;left:0;right:0;background:rgba(255,255,255,0.96);backdrop-filter:blur(10px);border-top:2px solid var(--seagreen-500);padding:12px 16px;box-shadow:0 -4px 15px rgba(0,0,0,0.08);z-index:90;transform:translateY(120%);transition:transform 0.25s cubic-bezier(0.16, 1, 0.3, 1)">
    <div class="container" style="display:flex;align-items:center;justify-content:space-between;gap:12px;padding:0">
        <div style="display:flex;align-items:center;gap:10px">
            <div style="background:var(--seagreen-100);width:42px;height:42px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.2rem">🛒</div>
            <div>
                <div style="font-size:0.8rem;color:var(--gray-500);line-height:1">In Your Cart</div>
                <div style="font-size:1.05rem;font-weight:800;color:var(--seagreen-800)">
                    <span id="bottomBarCount">0</span> items · <span id="bottomBarTotal">RM 0.00</span>
                </div>
            </div>
        </div>
        <div style="display:flex;gap:8px">
            <a href="{{ route('walkin.checkout') }}" class="btn btn-primary" style="padding:10px 20px;font-weight:700;display:flex;align-items:center;gap:6px;box-shadow:0 4px 12px rgba(15,118,110,0.25)">
                <span>Pay & Collect</span>
                <span>→</span>
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

// Update UI with fresh cart data
function refreshCartDisplay(count, totalFormatted) {
    document.getElementById('walkinCartCount').textContent = count;
    document.getElementById('bottomBarCount').textContent = count;
    if (totalFormatted) {
        document.getElementById('bottomBarTotal').textContent = 'RM ' + totalFormatted;
    }

    const stickyBar = document.getElementById('stickyCheckoutBar');
    if (count > 0) {
        stickyBar.style.transform = 'translateY(0)';
    } else {
        stickyBar.style.transform = 'translateY(120%)';
    }
}

// Fetch initial count & total
async function initCart() {
    try {
        const res = await fetch('{{ route("cart.count") }}');
        const data = await res.json();
        refreshCartDisplay(data.count, data.total_formatted);
    } catch(e) {}
}
initCart();

// AJAX 1-Tap Add-to-Cart
document.querySelectorAll('.btn-add-ajax').forEach(button => {
    button.addEventListener('click', async function(e) {
        e.preventDefault();
        const productId = this.getAttribute('data-product-id');
        const origContent = this.innerHTML;

        this.disabled = true;
        this.innerHTML = '<span>⏳ Adding...</span>';

        try {
            const res = await fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    product_id: parseInt(productId),
                    quantity: 1
                })
            });

            const data = await res.json();

            if (data.success) {
                this.style.background = '#059669';
                this.innerHTML = '<span>✓ Added!</span>';
                refreshCartDisplay(data.count, data.total_formatted);

                // Revert button text after 1.2s
                setTimeout(() => {
                    this.disabled = false;
                    this.style.background = '';
                    this.innerHTML = origContent;
                }, 1200);
            } else {
                alert(data.message || 'Could not add to cart');
                this.disabled = false;
                this.innerHTML = origContent;
            }
        } catch(err) {
            alert('Error adding item to cart.');
            this.disabled = false;
            this.innerHTML = origContent;
        }
    });
});
</script>
@endpush
