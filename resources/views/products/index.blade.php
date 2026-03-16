@extends('layouts.app')

@section('title', 'Browse Products')

@push('styles')
<style>
    .filter-sidebar {
        background: #fff;
        border-right: 1px solid #e0e0e0;
        min-height: 80vh;
        padding: 0;
    }
    .filter-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e0e0e0;
    }
    .filter-section {
        border-bottom: 1px solid #e0e0e0;
        padding: 1.5rem;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    .filter-section:hover {
        background-color: #f9f9f9;
    }
    .filter-section-title {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 1rem;
        color: #333;
        font-weight: 400;
    }
    .filter-section-content {
        margin-top: 1rem;
        display: none;
    }
    .filter-section-content.show {
        display: block;
    }
    .filter-option {
        padding: 0.5rem 0;
        color: #666;
        font-size: 0.95rem;
        cursor: pointer;
        transition: color 0.2s;
    }
    .filter-option:hover {
        color: #4a90e2;
    }
    .filter-option input[type="checkbox"] {
        margin-right: 0.5rem;
    }
    .view-all-link {
        color: #333;
        text-decoration: none;
        font-weight: 500;
        display: block;
    }
    .view-all-link:hover {
        color: #4a90e2;
    }
    .product-grid {
        padding-left: 2rem;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 16px rgba(0,0,0,0.15) !important;
    }
    @media (max-width: 768px) {
        .filter-sidebar {
            border-right: none;
            border-bottom: 1px solid #e0e0e0;
            margin-bottom: 2rem;
            min-height: auto;
        }
        .product-grid {
            padding-left: 0;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Left Sidebar Filter -->
        <div class="col-lg-3 col-md-4">
            <div class="filter-sidebar">
                <div class="filter-header">
                    <a href="{{ route('products.index') }}" class="view-all-link">View All Styles</a>
                </div>

                @php
                    $selectedCategories = array_map('strval', (array) request('category', []));
                    $selectedSizes = array_map('strval', (array) request('size', []));
                    $selectedColors = array_map('strval', (array) request('color', []));
                    $selectedOccasions = array_map('strval', (array) request('occasion', []));
                @endphp

                <!-- Category Filter -->
                <div class="filter-section" onclick="toggleFilter('category')">
                    <div class="filter-section-title">
                        <span>Category</span>
                        <i class="bi bi-chevron-right" id="category-icon"></i>
                    </div>
                    <div class="filter-section-content" id="category-content">
                        @foreach($categories as $category)
                            <div class="filter-option">
                                <label style="cursor: pointer; display: block;">
                                    <input type="checkbox" name="category[]" value="{{ $category->id }}" 
                                           {{ in_array((string) $category->id, $selectedCategories, true) ? 'checked' : '' }}
                                           onchange="applyFilter()">
                                    {{ $category->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Size Filter -->
                <div class="filter-section" onclick="toggleFilter('size')">
                    <div class="filter-section-title">
                        <span>Size + Fit</span>
                        <i class="bi bi-chevron-right" id="size-icon"></i>
                    </div>
                    <div class="filter-section-content" id="size-content">
                        @php
                            $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'Free Size'];
                        @endphp
                        @foreach($sizes as $size)
                            <div class="filter-option">
                                <label style="cursor: pointer; display: block;">
                                    <input type="checkbox" name="size[]" value="{{ $size }}" 
                                           {{ in_array((string) $size, $selectedSizes, true) ? 'checked' : '' }}
                                           onchange="applyFilter()">
                                    {{ $size }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Color Filter -->
                <div class="filter-section" onclick="toggleFilter('color')">
                    <div class="filter-section-title">
                        <span>Colors</span>
                        <i class="bi bi-chevron-right" id="color-icon"></i>
                    </div>
                    <div class="filter-section-content" id="color-content">
                        @php
                            $colors = ['Red', 'Blue', 'Green', 'Yellow', 'Black', 'White', 'Multi-color'];
                        @endphp
                        @foreach($colors as $color)
                            <div class="filter-option">
                                <label style="cursor: pointer; display: block;">
                                    <input type="checkbox" name="color[]" value="{{ $color }}" 
                                           {{ in_array((string) $color, $selectedColors, true) ? 'checked' : '' }}
                                           onchange="applyFilter()">
                                    {{ $color }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Occasion Filter -->
                <div class="filter-section" onclick="toggleFilter('occasion')">
                    <div class="filter-section-title">
                        <span>Occasion</span>
                        <i class="bi bi-chevron-right" id="occasion-icon"></i>
                    </div>
                    <div class="filter-section-content" id="occasion-content">
                        @php
                            $occasions = ['Wedding', 'Festival', 'Ceremonial', 'Casual', 'Formal'];
                        @endphp
                        @foreach($occasions as $occasion)
                            <div class="filter-option">
                                <label style="cursor: pointer; display: block;">
                                    <input type="checkbox" name="occasion[]" value="{{ $occasion }}" 
                                           {{ in_array((string) $occasion, $selectedOccasions, true) ? 'checked' : '' }}
                                           onchange="applyFilter()">
                                    {{ $occasion }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-lg-9 col-md-8 product-grid">
            <div class="mb-4">
                <h2 style="font-weight: 300; font-size: 2rem; color: #333;">All Styles</h2>
                <p style="color: #666;">{{ $products->total() }} items</p>
            </div>

            <div class="row">
            <div class="row">
        @forelse($products as $product)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100" style="border: none; border-radius: 0; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: all 0.3s ease;">
                    @if($product->images && count($product->images) > 0)
                        <img src="{{ asset('storage/' . $product->images[0]) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 350px; object-fit: cover;">
                    @else
                        <div class="d-flex align-items-center justify-content-center" style="height: 350px; background: linear-gradient(135deg, #e8e3dc 0%, #d4cfc4 100%);">
                            <i class="bi bi-image" style="font-size: 3rem; color: #999;"></i>
                        </div>
                    @endif
                    <div class="card-body" style="padding: 1.5rem;">
                        <span class="badge mb-2" style="background-color: #4a90e2; font-weight: 400;">{{ $product->category->name }}</span>
                        <h5 class="card-title" style="font-weight: 400; font-size: 1.1rem; color: #333;">{{ $product->name }}</h5>
                        <p class="card-text" style="color: #999; font-size: 0.9rem;">{{ Str::limit($product->description, 60) }}</p>
                        @if($product->size)
                            <p class="mb-1" style="font-size: 0.85rem; color: #666;"><strong>Size:</strong> {{ $product->size }}</p>
                        @endif
                        @if($product->color)
                            <p class="mb-1" style="font-size: 0.85rem; color: #666;"><strong>Color:</strong> {{ $product->color }}</p>
                        @endif
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <p class="mb-0" style="color: #4a90e2; font-weight: 500; font-size: 1.1rem;">Nu. {{ number_format($product->rental_price, 0) }}/day</p>
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm" style="background-color: #4a90e2; color: white; border-radius: 20px; padding: 0.5rem 1.5rem;">View</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert" style="background-color: #f8f8f8; border: none; color: #666; text-align: center; padding: 3rem;">
                    <i class="bi bi-info-circle" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                    <p style="margin: 0;">No products found. Try adjusting your filters.</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="row mt-4">
        <div class="col-12">
            {{ $products->links() }}
        </div>
    </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleFilter(filterId) {
        const content = document.getElementById(filterId + '-content');
        const icon = document.getElementById(filterId + '-icon');
        
        if (content.classList.contains('show')) {
            content.classList.remove('show');
            icon.classList.remove('bi-chevron-down');
            icon.classList.add('bi-chevron-right');
        } else {
            content.classList.add('show');
            icon.classList.remove('bi-chevron-right');
            icon.classList.add('bi-chevron-down');
        }
        
        event.stopPropagation();
    }

    function applyFilter() {
        const form = document.createElement('form');
        form.method = 'GET';
        form.action = '{{ route("products.index") }}';
        
        // Collect all checked checkboxes
        const checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');
        checkboxes.forEach(checkbox => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = checkbox.name;
            input.value = checkbox.value;
            form.appendChild(input);
        });
        
        document.body.appendChild(form);
        form.submit();
    }

    // Auto-expand category filter if a category is selected
    document.addEventListener('DOMContentLoaded', function() {
        @if(request()->has('category'))
            toggleFilter('category');
        @endif
    });
</script>
@endpush
@endsection
