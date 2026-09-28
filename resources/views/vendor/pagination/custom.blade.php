@if ($paginator->hasPages())
    <nav class="custom-pagination-nav" role="navigation" aria-label="Pagination">
        <div class="custom-pagination-info">
            @if(current_locale() === 'bm')
                Menunjukkan <span class="fw-bold">{{ $paginator->firstItem() }}</span>–<span class="fw-bold">{{ $paginator->lastItem() }}</span> daripada <span class="fw-bold">{{ $paginator->total() }}</span> produk
            @elseif(current_locale() === 'zh')
                显示第 <span class="fw-bold">{{ $paginator->firstItem() }}</span>–<span class="fw-bold">{{ $paginator->lastItem() }}</span> 项（共 <span class="fw-bold">{{ $paginator->total() }}</span> 件商品）
            @else
                @t('shop.showing', 'Showing') <span class="fw-bold">{{ $paginator->firstItem() }}</span>–<span class="fw-bold">{{ $paginator->lastItem() }}</span> @t('shop.of_total', 'of') <span class="fw-bold">{{ $paginator->total() }}</span> @t('shop.products_count', 'products')
            @endif
        </div>

        <div class="custom-pagination-pages">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="pagination-btn disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    &lsaquo; @t('common.prev', 'Prev')
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="pagination-btn" rel="prev" aria-label="@lang('pagination.previous')">
                    &lsaquo; @t('common.prev', 'Prev')
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="pagination-dots" aria-disabled="true">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="pagination-number active" aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="pagination-number">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="pagination-btn" rel="next" aria-label="@lang('pagination.next')">
                    @t('common.next', 'Next') &rsaquo;
                </a>
            @else
                <span class="pagination-btn disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    @t('common.next', 'Next') &rsaquo;
                </span>
            @endif
        </div>
    </nav>
@endif
