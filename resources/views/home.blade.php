@extends('layouts.app')

@section('title', 'Home - Clothing Rental System')

@section('content')
<div class="container">
    <!-- Hero Section -->
    <div class="jumbotron bg-light p-5 rounded-3 mb-4">
        <h1 class="display-4">Welcome to Clothing Rental Marketplace</h1>
        <p class="lead">Rent traditional clothing from verified sellers across the region.</p>
        <hr class="my-4">
        <p>Browse our collection of high-quality traditional wear for all occasions.</p>
        <a class="btn btn-primary btn-lg" href="{{ route('products.index') }}" role="button">
            <i class="bi bi-search"></i> Browse Products
        </a>
    </div>

    <!-- Categories Section -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-3">Categories</h2>
            <div class="row">
                @forelse($categories as $category)
                    <div class="col-md-3 mb-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <h5 class="card-title">{{ $category->name }}</h5>
                                <p class="card-text">{{ $category->description }}</p>
                                <a href="{{ route('products.index', ['category' => $category->id]) }}" class="btn btn-sm btn-outline-primary">
                                    View Products
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-muted">No categories available yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Featured Products -->
    <div class="row">
        <div class="col-12">
            <h2 class="mb-3">Featured Products</h2>
        </div>
        
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
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text text-muted">{{ Str::limit($product->description, 80) }}</p>
                        <p class="text-primary fw-bold">Rs. {{ number_format($product->rental_price, 2) }} / day</p>
                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary btn-sm">View Details</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> No products available yet. Please check back later.
                </div>
            </div>
        @endforelse
    </div>

    @if($products->count() > 0)
        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                    View All Products <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
