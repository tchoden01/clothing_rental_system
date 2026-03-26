@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="container">
    @php
        $isReadOnly = $isReadOnly ?? false;
    @endphp

    <h2 class="mb-4">{{ $isReadOnly ? 'Cart Monitor' : 'Shopping Cart' }}</h2>

    @if($isReadOnly)
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i>
            Read-only cart view for admin and seller accounts. Customer carts are shown below.
        </div>
    @endif

    @if($cartItems->count() > 0)
        @if($isReadOnly)
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Item</th>
                                    <th>Seller</th>
                                    <th>Qty</th>
                                    <th>Rental Window</th>
                                    <th>Line Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $item)
                                    <tr>
                                        <td>{{ $item->user->name ?? 'N/A' }}</td>
                                        <td>{{ $item->product->name ?? 'N/A' }}</td>
                                        <td>{{ $item->product->seller->shop_name ?? 'N/A' }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>
                                            @if($item->rental_start_date && $item->rental_end_date)
                                                {{ $item->rental_start_date->format('M d, Y') }} - {{ $item->rental_end_date->format('M d, Y') }}
                                            @else
                                                <span class="text-muted">Not set</span>
                                            @endif
                                        </td>
                                        <td>Nu. {{ number_format(($item->product->rental_price ?? 0) * $item->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h5 class="mb-1">Total Value Across Customer Carts</h5>
                        <p class="text-muted mb-0">Current snapshot of all customer cart items.</p>
                    </div>
                    <h4 class="text-primary mb-0">Nu. {{ number_format($total, 2) }}</h4>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            @foreach($cartItems as $item)
                                <div class="row mb-3 pb-3 border-bottom">
                                    <div class="col-md-2">
                                        @if($item->product->primary_image_url)
                                            <img src="{{ $item->product->primary_image_url }}" class="img-fluid" alt="{{ $item->product->name }}">
                                        @else
                                            <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 80px;">
                                                <i class="bi bi-image"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-4">
                                        <h5>{{ $item->product->name }}</h5>
                                        <p class="text-muted small">{{ $item->product->seller->shop_name }}</p>
                                    </div>
                                    <div class="col-md-2">
                                        <p class="mb-0"><strong>Nu. {{ number_format($item->product->rental_price, 2) }}</strong></p>
                                        <small class="text-muted">per day</small>
                                    </div>
                                    <div class="col-md-2">
                                        <form action="{{ route('cart.update', $item->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="number" class="form-control form-control-sm" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->quantity }}" onchange="this.form.submit()">
                                        </form>
                                    </div>
                                    <div class="col-md-2 text-end">
                                        <p class="mb-2"><strong>Nu. {{ number_format($item->product->rental_price * $item->quantity, 2) }}</strong></p>
                                        <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i> Remove
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Order Summary</h5>
                            <hr>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <strong>Nu. {{ number_format($total, 2) }}</strong>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <h5>Total:</h5>
                                <h5 class="text-primary">Nu. {{ number_format($total, 2) }}</h5>
                            </div>
                            <a href="{{ route('checkout') }}" class="btn btn-primary w-100">
                                Proceed to Checkout <i class="bi bi-arrow-right"></i>
                            </a>
                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                                Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @else
        <div class="alert alert-info">
            <i class="bi bi-cart-x"></i>
            @if($isReadOnly)
                No customer cart items yet.
            @else
                Your cart is empty. <a href="{{ route('products.index') }}">Browse products</a>
            @endif
        </div>
    @endif
</div>
@endsection
