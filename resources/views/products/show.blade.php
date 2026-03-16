@extends('layouts.app')

@section('title', $product->name)

@push('styles')
<style>
    .product-details-page {
        padding-bottom: 3rem;
    }

    .product-image-frame {
        background: #f4f1eb;
        border-radius: 10px;
        border: 1px solid rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .product-image-full {
        width: 100%;
        height: 460px;
        object-fit: contain;
        object-position: center;
        background: #f4f1eb;
    }

    .product-image-empty {
        height: 460px;
        border-radius: 10px;
    }

    @media (max-width: 767px) {
        .product-image-full,
        .product-image-empty {
            height: 320px;
        }
    }
</style>
@endpush

@section('content')
<div class="container product-details-page">
    <div class="row">
        <div class="col-md-6">
            @if($product->images && count($product->images) > 0)
                <div id="productCarousel" class="carousel slide product-image-frame" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach($product->images as $index => $image)
                            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                <img src="{{ asset('storage/' . $image) }}" class="d-block w-100 product-image-full" alt="{{ $product->name }}">
                            </div>
                        @endforeach
                    </div>
                    @if(count($product->images) > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    @endif
                </div>
            @else
                <div class="bg-secondary text-white d-flex align-items-center justify-content-center product-image-empty">
                    <i class="bi bi-image" style="font-size: 5rem;"></i>
                </div>
            @endif
        </div>

        <div class="col-md-6">
            <h2>{{ $product->name }}</h2>
            <span class="badge bg-primary mb-2">{{ $product->category->name }}</span>
            
            <h3 class="text-primary mt-3">Nu. {{ number_format($product->rental_price, 2) }} / day</h3>
            
            <hr>
            
            <div class="mb-3">
                <h5>Product Details</h5>
                <table class="table">
                    @if($product->size)
                        <tr>
                            <th width="30%">Size:</th>
                            <td>{{ $product->size }}</td>
                        </tr>
                    @endif
                    @if($product->color)
                        <tr>
                            <th>Color:</th>
                            <td>{{ $product->color }}</td>
                        </tr>
                    @endif
                    @if($product->condition)
                        <tr>
                            <th>Condition:</th>
                            <td>{{ ucfirst($product->condition) }}</td>
                        </tr>
                    @endif
                    <tr>
                        <th>Availability:</th>
                        <td>
                            @if($product->quantity > 0)
                                <span class="badge bg-success">Available ({{ $product->quantity }} in stock)</span>
                            @else
                                <span class="badge bg-danger">Out of Stock</span>
                            @endif
                        </td>
                    </tr>
                    @if($product->location)
                        <tr>
                            <th>Location:</th>
                            <td>{{ $product->location }}</td>
                        </tr>
                    @endif
                </table>
            </div>

            <div class="mb-3">
                <h5>Description</h5>
                <p>{{ $product->description }}</p>
            </div>

            <div class="mb-3">
                <h5>Seller Information</h5>
                <p>
                    <strong>Shop:</strong> {{ $product->seller->shop_name }}<br>
                    <strong>Contact:</strong> {{ $product->seller->contact_number }}
                </p>
            </div>

            @auth
                @if(Auth::user()->isCustomer())
                    @if($product->quantity > 0)
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" max="{{ $product->quantity }}">
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="bi bi-cart-plus"></i> Add to Cart
                            </button>
                        </form>
                    @else
                        <button class="btn btn-secondary btn-lg w-100" disabled>Out of Stock</button>
                    @endif
                @else
                    <div class="alert alert-info mb-3">
                        <i class="bi bi-info-circle"></i> Admin and seller accounts cannot add items to cart.
                    </div>
                    <a href="{{ route('cart.index') }}" class="btn btn-outline-primary btn-lg w-100">
                        <i class="bi bi-cart"></i> View Cart Monitor
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg w-100">
                    Login to Rent
                </a>
            @endauth
        </div>
    </div>
</div>
@endsection
