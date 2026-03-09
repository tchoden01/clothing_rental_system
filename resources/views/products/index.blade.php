@extends('layouts.app')

@section('title', 'Browse Products')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h2>Browse Products</h2>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="row mb-4">
        <div class="col-md-8">
            <form action="{{ route('products.search') }}" method="GET">
                <div class="input-group">
                    <input type="text" class="form-control" name="query" placeholder="Search products..." value="{{ $search ?? '' }}">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-search"></i> Search
                    </button>
                </div>
            </form>
        </div>
        <div class="col-md-4">
            <form action="{{ route('products.index') }}" method="GET">
                <select class="form-select" name="category" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row">
        @forelse($products as $product)
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    @if($product->images && count($product->images) > 0)
                        <img src="{{ asset('storage/' . $product->images[0]) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="bi bi-image" style="font-size: 3rem;"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <span class="badge bg-primary mb-2">{{ $product->category->name }}</span>
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text text-muted small">{{ Str::limit($product->description, 60) }}</p>
                        @if($product->size)
                            <p class="mb-1"><small><strong>Size:</strong> {{ $product->size }}</small></p>
                        @endif
                        @if($product->color)
                            <p class="mb-1"><small><strong>Color:</strong> {{ $product->color }}</small></p>
                        @endif
                        <p class="text-primary fw-bold">Rs. {{ number_format($product->rental_price, 2) }} / day</p>
                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary btn-sm w-100">View Details</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> No products found. Try adjusting your search or filters.
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="row">
        <div class="col-12">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
