@props([
    'reviews' => null,
    'title' => 'What Our Customers Say',
    'subtitle' => null,
    'badge' => 'CUSTOMER TESTIMONIALS'
])

@php
    $items = $reviews ?? \App\Models\Review::approved()->featured()->orderBy('sort_order')->limit(6)->get();
    $storeName = $settings['store_name'] ?? 'MST Import and Export Sdn Bhd';
    $displaySubtitle = $subtitle ?? "From five-star hotel executive chefs to home cooking enthusiasts, discover why seafood lovers choose {$storeName}.";
@endphp

@if($items && $items->count())
<section class="section reviews-section" style="padding:var(--space-16) 0;background:linear-gradient(180deg,rgba(240,253,250,0.5) 0%,rgba(255,255,255,1) 100%)">
    <div class="container">
        <!-- Section Header -->
        <div style="text-align:center;max-width:720px;margin:0 auto var(--space-12)">
            <div class="section-eyebrow" style="color:var(--seagreen-700);font-weight:700;letter-spacing:0.1em;font-size:0.8rem;text-transform:uppercase;margin-bottom:8px">
                {{ $badge }}
            </div>
            <h2 style="font-family:var(--font-heading);font-size:2.1rem;color:var(--gray-900);margin-bottom:12px;letter-spacing:-0.02em">
                {{ $title }}
            </h2>
            <p style="color:var(--gray-600);font-size:1.05rem;line-height:1.6;margin-bottom:16px">
                {{ $displaySubtitle }}
            </p>

            <!-- Rating Trust Pill -->
            <div class="reviews-trust-pill">
                <div class="trust-stars-group">
                    <span class="trust-stars">★★★★★</span>
                    <span class="trust-score">4.9 / 5.0 Rating</span>
                </div>
                <span class="trust-sep">·</span>
                <span class="trust-count">Over 1,500+ Happy Customers</span>
            </div>
        </div>

        <!-- Reviews Grid -->
        <div class="reviews-grid">
            @foreach($items as $rev)
            <div class="card review-card" style="background:white;border:1px solid #e2e8f0;border-radius:16px;padding:24px;box-shadow:0 4px 15px rgba(0,0,0,0.03);display:flex;flex-direction:column;transition:transform 0.2s,box-shadow 0.2s"
                 onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 24px rgba(13,148,136,0.1)'"
                 onmouseout="this.style.transform='none';this.style.boxShadow='0 4px 15px rgba(0,0,0,0.03)'">
                
                <!-- Stars & Quote Icon -->
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px">
                    <div style="color:#f59e0b;font-size:1.15rem;letter-spacing:2px">
                        @for($i = 1; $i <= 5; $i++)
                            {{ $i <= $rev->rating ? '★' : '☆' }}
                        @endfor
                    </div>
                    <div style="font-size:1.6rem;color:#ccfbf1;line-height:1;font-family:serif">❝</div>
                </div>

                <!-- Review Content -->
                <p style="color:var(--gray-700);font-size:0.95rem;line-height:1.7;flex:1;margin-bottom:20px;font-style:italic">
                    “{{ $rev->comment }}”
                </p>

                <!-- Customer Identity Info -->
                <div style="display:flex;align-items:center;gap:12px;border-top:1px solid #f1f5f9;padding-top:14px">
                    @if($rev->avatar)
                        <img src="{{ cdn_storage($rev->avatar) }}" alt="{{ $rev->name }}" loading="lazy"
                             style="width:46px;height:46px;border-radius:50%;object-fit:cover;border:2px solid #bfdbfe;flex-shrink:0">
                    @else
                        <div style="width:46px;height:46px;border-radius:50%;background:linear-gradient(135deg,#1d4ed8,#0f244a);color:white;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.95rem;flex-shrink:0;box-shadow:0 2px 6px rgba(29,78,216,0.25)">
                            {{ $rev->initials }}
                        </div>
                    @endif

                    <div style="overflow:hidden;flex:1">
                        <div style="display:flex;align-items:center;gap:6px">
                            <span style="font-weight:700;color:var(--gray-900);font-size:0.92rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                {{ $rev->name }}
                            </span>
                            <span title="Verified Seafood Order" style="color:#1d4ed8;font-size:0.85rem;display:inline-flex;align-items:center" aria-label="Verified">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="#1d4ed8"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                            </span>
                        </div>
                        <span style="font-size:0.78rem;color:var(--gray-500);display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                            {{ $rev->role_or_company ?? 'Verified Seafood Buyer' }}
                        </span>
                    </div>
                </div>

            </div>
            @endforeach
        </div>

    </div>
</section>
@endif
