@extends('layouts.app')

@section('title', 'Browse Products')

@push('styles')
<style>
    .explore-page {
        background: #f4f2ed;
        padding-bottom: 2rem;
    }

    .chip-row {
        display: flex;
        flex-wrap: wrap;
        gap: 0.55rem;
        margin: 0.35rem 0 1rem;
    }

    .quick-chip {
        border: 1px solid #d4cec2;
        color: #6b6458;
        background: #fbfaf7;
        border-radius: 999px;
        padding: 0.42rem 0.95rem;
        font-size: 0.85rem;
        line-height: 1;
        transition: all 0.2s ease;
    }

    .quick-chip:hover,
    .quick-chip.active {
        border-color: #cf4439;
        color: #cf4439;
        background: #fff;
    }

    .explore-layout {
        display: grid;
        grid-template-columns: 290px 1fr;
        gap: 1rem;
        align-items: start;
    }

    .filter-sidebar {
        background: #fcfbf8;
        border: 1px solid #dfd8cd;
        border-radius: 14px;
        overflow: hidden;
        position: sticky;
        top: 98px;
    }

    .filter-header {
        padding: 1rem 1.1rem;
        border-bottom: 1px solid #e8e2d7;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .view-all-link {
        color: #2f2b25;
        text-decoration: none;
        font-weight: 600;
    }

    .view-all-link:hover {
        color: #cf4439;
    }

    .filter-section {
        border-bottom: 1px solid #ece6dc;
    }

    .filter-section:last-child {
        border-bottom: none;
    }

    .filter-section-toggle {
        width: 100%;
        border: 0;
        background: transparent;
        padding: 0.95rem 1.1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: #3a352e;
        font-weight: 500;
    }

    .filter-section-content {
        padding: 0 1.1rem 0.9rem;
        display: none;
    }

    .filter-section-content.show {
        display: block;
    }

    .filter-option {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #5f584d;
        font-size: 0.92rem;
        padding: 0.22rem 0;
        cursor: pointer;
    }

    .products-panel {
        background: #fbfaf7;
        border: 1px solid #dfd8cd;
        border-radius: 14px;
        padding: 1rem;
    }

    .products-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
    }

    .products-meta {
        color: #766f63;
        font-size: 0.95rem;
    }

    .sort-select {
        max-width: 220px;
        border-color: #d4cec2;
    }

    .product-card {
        border: 1px solid #e3ddd1;
        border-radius: 12px;
        overflow: hidden;
        background: #fff;
        height: 100%;
        transition: transform 0.22s ease, box-shadow 0.22s ease;
    }

    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 22px rgba(42, 37, 28, 0.14);
    }

    .product-image-wrap {
        background: #f4f1eb;
        height: 290px;
    }

    .product-image-wrap img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .product-card-body {
        padding: 1rem;
    }

    .product-category {
        display: inline-flex;
        padding: 0.18rem 0.58rem;
        border-radius: 999px;
        background: #e8f1ff;
        color: #2f77d5;
        font-size: 0.74rem;
        font-weight: 600;
        margin-bottom: 0.45rem;
    }

    .product-name {
        margin: 0 0 0.32rem;
        color: #29251f;
        font-size: 1.22rem;
        font-weight: 500;
    }

    .product-desc {
        color: #7b7366;
        font-size: 0.9rem;
        margin-bottom: 0.42rem;
    }

    .product-meta {
        color: #5f584d;
        font-size: 0.87rem;
        margin-bottom: 0.2rem;
    }

    .product-footer {
        margin-top: 0.65rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
    }

    .product-price {
        color: #2f77d5;
        font-weight: 700;
        font-size: 1.38rem;
        line-height: 1;
    }

    .btn-view {
        border-radius: 999px;
        padding: 0.36rem 1rem;
        font-weight: 600;
    }

    .empty-state {
        background: #f8f5ef;
        border: 1px dashed #d5cfc4;
        border-radius: 12px;
        text-align: center;
        padding: 3rem 1rem;
        color: #6e675b;
    }

    @media (max-width: 1199px) {
        .explore-layout {
            grid-template-columns: 260px 1fr;
        }
    }

    @media (max-width: 991px) {
        .explore-layout {
            grid-template-columns: 1fr;
        }

        .filter-sidebar {
            position: static;
        }
    }
</style>
@endpush

@section('content')
@php
    $selectedCategories = array_map('strval', (array) request('category', []));
    $selectedSizes = array_map('strval', (array) request('size', []));
    $selectedColors = array_map('strval', (array) request('color', []));
    $selectedOccasions = array_map('strval', (array) request('occasion', []));
    $selectedSort = request('sort', 'newest');

    $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'Free Size'];
    $colors = ['Red', 'Blue', 'Green', 'Yellow', 'Black', 'White', 'Multi-color', 'Pink', 'Mix'];
    $occasions = ['Wedding', 'Festival', 'Ceremonial', 'Casual', 'Formal'];
@endphp

<div class="container-fluid explore-page">
    <div class="container-fluid px-lg-4">
        <form id="exploreFilterForm" method="GET" action="{{ route('products.index') }}">
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif

            <section class="chip-row">
                <button type="button" class="quick-chip {{ empty($selectedCategories) ? 'active' : '' }}" data-clear="category">All</button>
                @foreach($categories as $category)
                    <button
                        type="button"
                        class="quick-chip {{ in_array((string) $category->id, $selectedCategories, true) ? 'active' : '' }}"
                        data-filter-type="category"
                        data-filter-value="{{ $category->id }}"
                    >
                        {{ $category->name }}
                    </button>
                @endforeach
            </section>

            <div class="explore-layout">
                <aside class="filter-sidebar">
                    <div class="filter-header">
                        <a href="{{ route('products.index') }}" class="view-all-link">View All Styles</a>
                        <a href="{{ route('products.index') }}" class="small text-muted">Reset</a>
                    </div>

                    <section class="filter-section">
                        <button type="button" class="filter-section-toggle" data-filter-target="category-content" data-filter-icon="category-icon">
                            <span>Category</span>
                            <i class="bi bi-chevron-down" id="category-icon"></i>
                        </button>
                        <div class="filter-section-content show" id="category-content">
                            @foreach($categories as $category)
                                <label class="filter-option">
                                    <input type="checkbox" name="category[]" value="{{ $category->id }}" {{ in_array((string) $category->id, $selectedCategories, true) ? 'checked' : '' }} onchange="applyFilter()">
                                    <span>{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </section>

                    <section class="filter-section">
                        <button type="button" class="filter-section-toggle" data-filter-target="size-content" data-filter-icon="size-icon">
                            <span>Size + Fit</span>
                            <i class="bi bi-chevron-down" id="size-icon"></i>
                        </button>
                        <div class="filter-section-content show" id="size-content">
                            @foreach($sizes as $size)
                                <label class="filter-option">
                                    <input type="checkbox" name="size[]" value="{{ $size }}" {{ in_array((string) $size, $selectedSizes, true) ? 'checked' : '' }} onchange="applyFilter()">
                                    <span>{{ $size }}</span>
                                </label>
                            @endforeach
                        </div>
                    </section>

                    <section class="filter-section">
                        <button type="button" class="filter-section-toggle" data-filter-target="color-content" data-filter-icon="color-icon">
                            <span>Colors</span>
                            <i class="bi bi-chevron-down" id="color-icon"></i>
                        </button>
                        <div class="filter-section-content show" id="color-content">
                            @foreach($colors as $color)
                                <label class="filter-option">
                                    <input type="checkbox" name="color[]" value="{{ $color }}" {{ in_array((string) $color, $selectedColors, true) ? 'checked' : '' }} onchange="applyFilter()">
                                    <span>{{ $color }}</span>
                                </label>
                            @endforeach
                        </div>
                    </section>

                    <section class="filter-section">
                        <button type="button" class="filter-section-toggle" data-filter-target="occasion-content" data-filter-icon="occasion-icon">
                            <span>Occasion</span>
                            <i class="bi bi-chevron-down" id="occasion-icon"></i>
                        </button>
                        <div class="filter-section-content show" id="occasion-content">
                            @foreach($occasions as $occasion)
                                <label class="filter-option">
                                    <input type="checkbox" name="occasion[]" value="{{ $occasion }}" {{ in_array((string) $occasion, $selectedOccasions, true) ? 'checked' : '' }} onchange="applyFilter()">
                                    <span>{{ $occasion }}</span>
                                </label>
                            @endforeach
                        </div>
                    </section>
                </aside>

                <section class="products-panel">
                    <div class="products-toolbar">
                        <div>
                            <h2 class="mb-0" style="font-weight: 500; color: #2e2a23;">All Styles</h2>
                            <div class="products-meta">{{ $products->total() }} results</div>
                        </div>
                        <select class="form-select sort-select" name="sort" onchange="applyFilter()">
                            <option value="newest" {{ $selectedSort === 'newest' ? 'selected' : '' }}>Newest</option>
                            <option value="oldest" {{ $selectedSort === 'oldest' ? 'selected' : '' }}>Oldest</option>
                            <option value="price_asc" {{ $selectedSort === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_desc" {{ $selectedSort === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        </select>
                    </div>

                    <div class="row g-3">
                        @forelse($products as $product)
                            <div class="col-xl-4 col-md-6">
                                <article class="product-card">
                                    <div class="product-image-wrap">
                                        @if($product->images && count($product->images) > 0)
                                            <img src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}">
                                        @else
                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                                <i class="bi bi-image" style="font-size: 2.8rem; color: #9f968a;"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="product-card-body">
                                        <span class="product-category">{{ $product->category->name }}</span>
                                        <h3 class="product-name">{{ $product->name }}</h3>
                                        <p class="product-desc">{{ Str::limit($product->description, 68) }}</p>
                                        @if($product->size)
                                            <p class="product-meta"><strong>Size:</strong> {{ $product->size }}</p>
                                        @endif
                                        @if($product->color)
                                            <p class="product-meta"><strong>Color:</strong> {{ $product->color }}</p>
                                        @endif
                                        <div class="product-footer">
                                            <div class="product-price">Nu. {{ number_format($product->rental_price, 0) }}<span style="font-size: 0.95rem; font-weight: 500;">/day</span></div>
                                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary btn-view">View</a>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="empty-state">
                                    <i class="bi bi-info-circle" style="font-size: 2rem; margin-bottom: 0.8rem;"></i>
                                    <p class="mb-0">No products found. Try adjusting your filters.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-4">
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                </section>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function applyFilter() {
        const form = document.getElementById('exploreFilterForm');
        if (form) {
            form.submit();
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.filter-section-toggle').forEach(function(toggleButton) {
            toggleButton.addEventListener('click', function() {
                const targetId = this.getAttribute('data-filter-target');
                const iconId = this.getAttribute('data-filter-icon');
                const content = document.getElementById(targetId);
                const icon = document.getElementById(iconId);

                if (!content || !icon) {
                    return;
                }

                content.classList.toggle('show');
                icon.classList.toggle('bi-chevron-down', content.classList.contains('show'));
                icon.classList.toggle('bi-chevron-right', !content.classList.contains('show'));
            });
        });

        document.querySelectorAll('.quick-chip[data-filter-type="category"]').forEach(function(chip) {
            chip.addEventListener('click', function() {
                const value = this.getAttribute('data-filter-value');
                const input = document.querySelector('input[name="category[]"][value="' + value + '"]');
                if (!input) {
                    return;
                }

                input.checked = !input.checked;
                applyFilter();
            });
        });

        document.querySelectorAll('.quick-chip[data-clear="category"]').forEach(function(chip) {
            chip.addEventListener('click', function() {
                document.querySelectorAll('input[name="category[]"]').forEach(function(input) {
                    input.checked = false;
                });
                applyFilter();
            });
        });
    });
</script>
@endpush
@endsection
