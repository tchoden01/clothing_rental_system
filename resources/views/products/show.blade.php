@extends('layouts.app')

@section('title', $product->name)

@push('styles')
<style>
    .product-details-page {
        padding-top: 1.2rem;
        padding-bottom: 3rem;
    }

    .product-image-frame {
        background: #f4f1eb;
        border-radius: 10px;
        border: 1px solid rgba(0, 0, 0, 0.08);
        overflow: hidden;
        position: relative;
    }

    .product-image-frame .carousel-item {
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f4f1eb;
        padding: 1rem;
    }

    .product-image-full {
        width: 100%;
        height: 428px;
        object-fit: contain;
        object-position: center center;
        background: #f4f1eb;
    }

    .product-image-empty {
        height: 460px;
        border-radius: 10px;
    }

    .availability-head {
        display: flex;
        align-items: center;
        gap: 0.45rem;
        margin-bottom: 0.4rem;
    }

    .availability-overlay {
        position: absolute;
        inset: 0;
        background: rgba(25, 25, 25, 0.36);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        letter-spacing: 0.02em;
        z-index: 2;
        pointer-events: none;
    }

    @media (max-width: 767px) {
        .product-image-full,
        .product-image-empty {
            height: 320px;
        }

        .product-image-frame .carousel-item {
            padding: 0.75rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container product-details-page">
    <div class="row">
        <div class="col-md-6">
            <div class="availability-head">
                @if($product->is_rentable_now)
                    <span class="badge bg-success">Available</span>
                @else
                    <span class="badge bg-warning text-dark">Currently Rented</span>
                @endif

                @if(!$product->is_rentable_now && $product->return_date)
                    <span class="text-muted small">Returning on {{ $product->return_date->format('d M Y') }}</span>
                @endif
            </div>

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

                    @if(!$product->is_rentable_now)
                        <div class="availability-overlay">Currently Rented</div>
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
                    @if($product->material)
                        <tr>
                            <th>Material:</th>
                            <td>{{ $product->material }}</td>
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
                    @if(!empty($product->gender))
                        <tr>
                            <th>For:</th>
                            <td>
                                {{ ucfirst($product->gender) }}
                                @if($product->gender === 'kids' && !empty($product->kid_type))
                                    ({{ ucfirst($product->kid_type) }})
                                @endif
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <th>Availability:</th>
                        <td>
                            @if($product->is_rentable_now)
                                <span class="badge bg-success">Available ({{ $product->quantity }} in stock)</span>
                            @else
                                <span class="badge bg-warning text-dark">Currently Rented</span>
                                @if($product->return_date)
                                    <span class="text-muted small d-block mt-1">Expected return: {{ $product->return_date->format('d M Y') }}</span>
                                @endif
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
                    @if($product->is_rentable_now)
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" max="{{ $product->quantity }}">
                            </div>

                            <button type="submit" name="rent_now" value="1" class="btn btn-outline-primary btn-lg w-100 mb-2">
                                <i class="bi bi-lightning-charge"></i> Rent Now
                            </button>

                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="bi bi-cart-plus"></i> Add to Cart
                            </button>
                        </form>
                    @else
                        <button class="btn btn-secondary btn-lg w-100 mb-2" disabled>Rent Now</button>
                        <button class="btn btn-secondary btn-lg w-100" disabled>Add to Cart</button>
                        @if($product->return_date)
                            <div class="text-muted small mt-2">This item is currently rented and expected back on {{ $product->return_date->format('d M Y') }}.</div>
                        @endif
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
