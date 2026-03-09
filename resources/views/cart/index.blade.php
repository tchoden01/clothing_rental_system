@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="container">
    <h2 class="mb-4">Shopping Cart</h2>

    @if($cartItems->count() > 0)
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        @foreach($cartItems as $item)
                            <div class="row mb-3 pb-3 border-bottom">
                                <div class="col-md-2">
                                    @if($item->product->images && count($item->product->images) > 0)
                                        <img src="{{ asset('storage/' . $item->product->images[0]) }}" class="img-fluid" alt="{{ $item->product->name }}">
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
                                    <p class="mb-0"><strong>Rs. {{ number_format($item->product->rental_price, 2) }}</strong></p>
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
                                    <p class="mb-2"><strong>Rs. {{ number_format($item->product->rental_price * $item->quantity, 2) }}</strong></p>
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
                            <strong>Rs. {{ number_format($total, 2) }}</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <h5>Total:</h5>
                            <h5 class="text-primary">Rs. {{ number_format($total, 2) }}</h5>
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
    @else
        <div class="alert alert-info">
            <i class="bi bi-cart-x"></i> Your cart is empty. <a href="{{ route('products.index') }}">Browse products</a>
        </div>
    @endif
</div>
@endsection
