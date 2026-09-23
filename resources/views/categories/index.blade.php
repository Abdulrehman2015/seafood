@extends('layouts.app')

@section('title', 'Product Categories — MST Import and Export Sdn Bhd')
@section('meta_description', 'Explore our full range of imported frozen seafood, Meltique beef, sashimi scallops, steamboat ingredients, and dim sum in Malaysia.')

@section('content')
<!-- Page Header -->
<div class="page-header" style="padding-top:calc(75px + var(--space-8));background:linear-gradient(135deg, #07152b 0%, #0c234b 45%, #1d4ed8 100%);color:white;border-bottom:1px solid #1e3a8a;padding-bottom:var(--space-10);position:relative;overflow:hidden">
    <div style="position:absolute;inset:0;opacity:0.07;background-image:radial-gradient(#38bdf8 1px, transparent 1px);background-size:20px 20px"></div>
    <div class="container page-header-content" style="position:relative;z-index:2">
        <div class="breadcrumb" style="margin-bottom:var(--space-3)">
            <a href="{{ route('home') }}" style="color:#bae6fd;text-decoration:none;display:inline-flex;align-items:center;gap:4px">🏠 Home</a>
            <span class="breadcrumb-sep" style="color:#60a5fa">›</span>
            <span style="font-weight:600;color:#ffffff">Product Categories</span>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px">
            <div style="max-width:720px">
                <span style="display:inline-flex;align-items:center;gap:6px;background:rgba(56,189,248,0.18);border:1px solid rgba(186,230,253,0.35);padding:4px 12px;border-radius:999px;font-size:0.75rem;font-weight:700;color:#7dd3fc;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:10px">
                    ❄️ Cold-Chain Sourcing &amp; Supply Platform
                </span>
                <h1 style="color:#ffffff;font-family:var(--font-heading);font-size:clamp(1.9rem,4vw,2.8rem);margin-bottom:10px;letter-spacing:-0.02em;line-height:1.2">
                    Browse All Product Categories
                </h1>
                <p style="color:#e0f2fe;font-size:1.02rem;line-height:1.6;margin:0">
                    Supplying seafood, meat, frozen food and selected food ingredients to commercial customers across Malaysia, Singapore and regional markets.
                </p>
            </div>
            <div style="display:flex;gap:10px;flex-wrap:wrap">
                <a href="{{ route('shop.index') }}" class="btn btn-primary" style="background:linear-gradient(135deg, #f59e0b 0%, #d97706 100%);color:#091a36;font-weight:800;border:none;padding:10px 20px;border-radius:10px;display:inline-flex;align-items:center;gap:8px;box-shadow:0 4px 14px rgba(245,158,11,0.35)">
                    <span>View All Products</span>
                    <span>→</span>
                </a>
                <a href="https://wa.me/601112710260?text=Hello%20MST%20Import%20%26%20Export%2C%20I%20would%20like%20to%20request%20the%20complete%20wholesale%20product%20catalog%20and%20price%20list." target="_blank" class="btn" style="background:rgba(255,255,255,0.15);color:white;border:1px solid rgba(255,255,255,0.3);font-weight:600;padding:10px 18px;border-radius:10px;display:inline-flex;align-items:center;gap:6px">
                    <span>💬 Request Catalog PDF</span>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container" style="padding-top:var(--space-10);padding-bottom:var(--space-16)">

    <!-- Department Filter Bar -->
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:var(--space-8);border-bottom:1px solid var(--gray-200);padding-bottom:var(--space-4)">
        <div style="display:flex;align-items:center;gap:8px;overflow-x:auto;padding-bottom:4px;width:100%;max-width:100%">
            <button type="button" class="cat-filter-tab active" onclick="filterDepartment('all', this)">
                🌐 All Departments ({{ $categories->count() }})
            </button>
            <button type="button" class="cat-filter-tab" onclick="filterDepartment('seafood', this)">
                🐟 Fresh &amp; Frozen Seafood
            </button>
            <button type="button" class="cat-filter-tab" onclick="filterDepartment('meat', this)">
                🥩 Meltique &amp; Prime Meats
            </button>
            <button type="button" class="cat-filter-tab" onclick="filterDepartment('prepared', this)">
                🍲 Steamboat, Dim Sum &amp; Ready Meals
            </button>
        </div>
    </div>

    <!-- Category Grid -->
    <div class="category-showcase-grid" id="categoryGrid">
        @php
            $catIcons = [
                'fish' => '🐟',
                'fish-fillet' => '🔪',
                'prawns-shrimps' => '🦐',
                'squid' => '🦑',
                'crab' => '🦀',
                'shellfish' => '🦪',
                'seafood-products' => '🍥',
                'other-frozen-seafood' => '🍱',
                'steamboat' => '🍲',
                'meat-beef' => '🥩',
                'meat-lamb' => '🍖',
                'meat-chicken' => '🍗',
                'meat-duck' => '🦆',
                'frozen-product-food' => '📦',
                'dimsum' => '🥟',
                'ready-to-eat' => '🥗',
                'snack-food' => '🍤',
                'dessert' => '🍡',
            ];

            $catThumbnails = [
                'fish' => 'products/salmon_fillet_premium.jpg',
                'fish-fillet' => 'products/barramundi_seabass.jpg',
                'prawns-shrimps' => 'products/black_tiger_prawns.jpg',
                'squid' => 'products/loligo_squid.jpg',
                'crab' => 'products/mud_crabs_live.jpg',
                'shellfish' => 'products/canadian_scallops.jpg',
                'seafood-products' => 'products/prawn_paste_tube.jpg',
                'other-frozen-seafood' => 'products/loligo_squid.jpg',
                'steamboat' => 'products/steamboat_hotpot_combo.jpg',
                'meat-beef' => 'products/meltique_beef_steak.jpg',
                'meat-lamb' => 'products/meltique_beef_steak.jpg',
                'meat-chicken' => 'products/barramundi_seabass.jpg',
                'meat-duck' => 'products/meltique_beef_steak.jpg',
                'frozen-product-food' => 'products/seafood_tofu_platter.jpg',
                'dimsum' => 'products/dimsum_har_kow.jpg',
                'ready-to-eat' => 'products/seafood_tofu_platter.jpg',
                'snack-food' => 'products/seafood_tofu_platter.jpg',
                'dessert' => 'products/dimsum_har_kow.jpg',
            ];

            $catDescriptions = [
                'fish' => 'Whole ocean catches, Norwegian Atlantic Salmon, Asian Seabass (Siakap), Red Snapper, and Spanish Mackerel.',
                'fish-fillet' => 'Boneless, skinless, IQF flash-frozen fillets with zero bone residue for culinary convenience.',
                'prawns-shrimps' => 'Wild Sea Black Tiger Prawns, Sea White Prawns (Udang Kertas), and Peeled & Deveined King Prawn cutlets.',
                'squid' => 'Loligo needle squid (Sotong Jarum) and pre-cut crispy calamari squid rings ready for frying.',
                'crab' => 'Live Grade-A Mangrove Mud Crabs (Ketam Nipah), Blue Swimmer Flower Crabs, and 100% edible Soft Shell Crabs.',
                'shellfish' => 'Canadian colossal sea scallops (sashimi grade), New Zealand half-shell green mussels, and clean sea cockles.',
                'seafood-products' => 'Handcrafted 95% pure prawn meat squeeze tubes, surimi pastes, and value-added ocean items.',
                'other-frozen-seafood' => 'Japanese seasoned Unagi Kabayaki, octopus tentacles, and specialty marine delicacies.',
                'steamboat' => 'Golden seafood tofu, cheese fish balls, lobster balls, and all-in-one steamboat hotpot harvest platters.',
                'meat-beef' => 'Premium Australian Meltique beef striploin & ribeye steaks with wagyu-grade buttery marbling.',
                'meat-lamb' => 'Pasture-fed Australian frenched lamb cutlets and tender bone-in lamb shanks.',
                'meat-chicken' => '100% Halal certified boneless chicken leg and thigh fillets for high-volume kitchen prep.',
                'meat-duck' => 'Hardwood-smoked whole duck breast fillets with aromatic spices and crispy skin potential.',
                'frozen-product-food' => 'Japanese Kanikama imitation crab sticks, chuka wakame seaweed salad, and frozen food essentials.',
                'dimsum' => 'Handmade crystal shrimp Har Kow, authentic Hong Kong style seafood Siew Mai, and steamed delicacies.',
                'ready-to-eat' => 'Thaw-and-serve Japanese seasoned seaweed salad, edamame beans, and cold appetizers.',
                'snack-food' => 'Crispy golden seafood spring rolls, prawn crackers, and banquet cocktail finger foods.',
                'dessert' => 'Japanese artisanal matcha and tropical mango gelato ice mochi in soft rice flour wrappers.',
            ];

            $catDepartments = [
                'fish' => 'seafood',
                'fish-fillet' => 'seafood',
                'prawns-shrimps' => 'seafood',
                'squid' => 'seafood',
                'crab' => 'seafood',
                'shellfish' => 'seafood',
                'seafood-products' => 'seafood',
                'other-frozen-seafood' => 'seafood',
                'steamboat' => 'prepared',
                'meat-beef' => 'meat',
                'meat-lamb' => 'meat',
                'meat-chicken' => 'meat',
                'meat-duck' => 'meat',
                'frozen-product-food' => 'prepared',
                'dimsum' => 'prepared',
                'ready-to-eat' => 'prepared',
                'snack-food' => 'prepared',
                'dessert' => 'prepared',
            ];
        @endphp

        @foreach($categories as $category)
            @php
                $slug = $category->slug;
                $icon = $catIcons[$slug] ?? '📦';
                $thumb = $catThumbnails[$slug] ?? 'products/salmon_fillet_premium.jpg';
                $desc = $catDescriptions[$slug] ?? 'Premium quality frozen seafood and cold storage supply.';
                $dept = $catDepartments[$slug] ?? 'seafood';
                $count = $category->products_count ?? $category->products()->active()->count();
            @endphp
            <div class="category-card-item" data-dept="{{ $dept }}">
                <div class="cat-card-img-wrapper">
                    <img src="{{ asset('storage/' . $thumb) }}" alt="{{ $category->name }}" loading="lazy">
                    <div class="cat-card-overlay"></div>
                    <span class="cat-badge-count">{{ $count }} {{ Str::plural('item', $count) }}</span>
                    <span class="cat-badge-icon">{{ $icon }}</span>
                </div>
                <div class="cat-card-content">
                    <h3 class="cat-card-title">
                        <a href="{{ route('shop.index', ['category' => $category->slug]) }}">
                            {{ $category->name }}
                        </a>
                    </h3>
                    <p class="cat-card-desc">{{ $desc }}</p>
                    <div class="cat-card-footer">
                        <a href="{{ route('shop.index', ['category' => $category->slug]) }}" class="cat-explore-link">
                            <span>Explore Category</span>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </a>
                        @if(auth()->check() && auth()->user()->customer_group === 'trading' && auth()->user()->isApproved())
                        <a href="{{ route('quotations.create') }}" class="cat-wa-link" title="Trading Partner RFQ">
                            📋 RFQ
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Cold Chain & Logistics Capabilities Banner (Surpassing Standard Cold Storage & SFS) -->
    <div style="margin-top:var(--space-16);background:linear-gradient(135deg,#042f2e 0%,#0f766e 100%);border-radius:24px;padding:var(--space-10) var(--space-8);color:white;position:relative;overflow:hidden">
        <div style="max-width:700px;position:relative;z-index:2">
            <span style="background:rgba(94,234,212,0.2);color:#5eead4;padding:4px 12px;border-radius:20px;font-size:0.75rem;font-weight:700;letter-spacing:0.05em;text-transform:uppercase">
                ❄️ Complete Temperature-Controlled Logistics
            </span>
            <h2 style="color:white;font-family:var(--font-heading);font-size:clamp(1.6rem,3vw,2.2rem);margin-top:12px;margin-bottom:12px;line-height:1.2">
                Unbroken Cold Chain Guarantee from SILC Iskandar Puteri
            </h2>
            <p style="color:#ccfbf1;font-size:0.98rem;line-height:1.6;margin-bottom:24px">
                Equipped with -25°C deep-freeze cold storage warehousing and an active fleet of temperature-monitored refrigerated trucks. We deliver across Johor, Klang Valley, Penang, and export directly across the border to Singapore daily.
            </p>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;margin-bottom:24px">
                <div style="background:rgba(255,255,255,0.1);backdrop-filter:blur(8px);padding:14px;border-radius:12px;border:1px solid rgba(255,255,255,0.15)">
                    <div style="font-size:1.4rem;font-weight:800;color:#5eead4">-18°C ~ -25°C</div>
                    <div style="font-size:0.8rem;color:#e2e8f0;margin-top:2px">Deep Frozen Storage</div>
                </div>
                <div style="background:rgba(255,255,255,0.1);backdrop-filter:blur(8px);padding:14px;border-radius:12px;border:1px solid rgba(255,255,255,0.15)">
                    <div style="font-size:1.4rem;font-weight:800;color:#5eead4">Quality Assured</div>
                    <div style="font-size:0.8rem;color:#e2e8f0;margin-top:2px">Food-Safety &amp; Cold-Chain Handling</div>
                </div>
                <div style="background:rgba(255,255,255,0.1);backdrop-filter:blur(8px);padding:14px;border-radius:12px;border:1px solid rgba(255,255,255,0.15)">
                    <div style="font-size:1.4rem;font-weight:800;color:#5eead4">Direct Sourcing</div>
                    <div style="font-size:0.8rem;color:#e2e8f0;margin-top:2px">Norway, Japan, Australia</div>
                </div>
            </div>
            <div style="display:flex;gap:12px;flex-wrap:wrap">
                <a href="{{ route('contact') }}" class="btn" style="background:#5eead4;color:#042f2e;font-weight:700;padding:10px 22px;border-radius:10px">
                    Contact Commercial Sales
                </a>
                <a href="{{ route('about') }}" class="btn" style="background:rgba(255,255,255,0.15);color:white;font-weight:600;padding:10px 20px;border-radius:10px;border:1px solid rgba(255,255,255,0.3)">
                    Our Cold Storage Facilities
                </a>
            </div>
        </div>
    </div>

</div>

@push('styles')
<style>
.cat-filter-tab {
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
    padding: 8px 18px;
    border-radius: 999px;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    white-space: nowrap;
    transition: all 0.2s ease;
}
.cat-filter-tab:hover {
    background: #e2e8f0;
    color: #0f172a;
}
.cat-filter-tab.active {
    background: #1d4ed8;
    color: #ffffff;
    border-color: #1d4ed8;
    box-shadow: 0 2px 8px rgba(29, 78, 216, 0.25);
}

.category-showcase-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 24px;
}

.category-card-item {
    background: #ffffff;
    border-radius: 18px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    display: flex;
    flex-direction: column;
}

.category-card-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 16px 32px rgba(29, 78, 216, 0.1);
    border-color: #bfdbfe;
}

.cat-card-img-wrapper {
    position: relative;
    width: 100%;
    height: 190px;
    background: #f8fafc;
    overflow: hidden;
}

.cat-card-img-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.category-card-item:hover .cat-card-img-wrapper img {
    transform: scale(1.06);
}

.cat-card-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(15,23,42,0.65) 0%, transparent 60%);
}

.cat-badge-count {
    position: absolute;
    bottom: 12px;
    left: 12px;
    background: rgba(12, 35, 75, 0.9);
    backdrop-filter: blur(4px);
    color: #ffffff;
    font-size: 0.75rem;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,0.2);
}

.cat-badge-icon {
    position: absolute;
    top: 12px;
    right: 12px;
    background: rgba(255,255,255,0.92);
    backdrop-filter: blur(4px);
    font-size: 1.15rem;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.cat-card-content {
    padding: 20px;
    display: flex;
    flex-direction: column;
    flex: 1;
}

.cat-card-title {
    font-family: var(--font-heading);
    font-size: 1.22rem;
    color: #0f172a;
    margin: 0 0 8px 0;
    line-height: 1.3;
}

.cat-card-title a {
    color: inherit;
    text-decoration: none;
    transition: color 0.15s ease;
}

.cat-card-title a:hover {
    color: #1d4ed8;
}

.cat-card-desc {
    color: #64748b;
    font-size: 0.85rem;
    line-height: 1.5;
    margin: 0 0 18px 0;
    flex: 1;
}

.cat-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    border-top: 1px solid #f1f5f9;
    padding-top: 14px;
}

.cat-explore-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.84rem;
    font-weight: 700;
    color: #1d4ed8;
    text-decoration: none;
    transition: gap 0.15s ease, color 0.15s ease;
}

.cat-explore-link:hover {
    gap: 10px;
    color: #1e40af;
}

.cat-wa-link {
    background: #25d366;
    color: white !important;
    font-size: 0.75rem;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 999px;
    text-decoration: none;
    transition: transform 0.15s ease, filter 0.15s ease;
}

.cat-wa-link:hover {
    transform: scale(1.05);
    filter: brightness(1.05);
}
</style>
@endpush

@push('scripts')
<script>
function filterDepartment(dept, btn) {
    document.querySelectorAll('.cat-filter-tab').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');

    const items = document.querySelectorAll('.category-card-item');
    items.forEach(item => {
        if (dept === 'all' || item.getAttribute('data-dept') === dept) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
}
</script>
@endpush
@endsection
