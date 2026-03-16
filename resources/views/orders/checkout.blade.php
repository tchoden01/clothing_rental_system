@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12 mb-4">
            <h2>Checkout</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Cart</a></li>
                    <li class="breadcrumb-item active">Checkout</li>
                </ol>
            </nav>
        </div>
    </div>

    <form action="{{ route('orders.store') }}" method="POST">
        @csrf
        
        <div class="row">
            <!-- Left Column - Order Form -->
            <div class="col-md-8">
                <!-- Rental Period -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-calendar-range"></i> Rental Period</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="rental_start_date" class="form-label">Start Date *</label>
                                <input type="date" 
                                       class="form-control @error('rental_start_date') is-invalid @enderror" 
                                       id="rental_start_date" 
                                       name="rental_start_date" 
                                       value="{{ old('rental_start_date') }}" 
                                       min="{{ date('Y-m-d') }}"
                                       required>
                                @error('rental_start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="rental_end_date" class="form-label">End Date *</label>
                                <input type="date" 
                                       class="form-control @error('rental_end_date') is-invalid @enderror" 
                                       id="rental_end_date" 
                                       name="rental_end_date" 
                                       value="{{ old('rental_end_date') }}" 
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                       required>
                                @error('rental_end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="alert alert-info mb-0">
                            <small><i class="bi bi-info-circle"></i> Please select your rental period carefully. Changing dates after order placement may not be possible.</small>
                        </div>
                    </div>
                </div>

                <!-- Delivery Method -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-truck"></i> Delivery Method</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" 
                                   type="radio" 
                                   name="delivery_method" 
                                   id="pickup" 
                                   value="pickup" 
                                   {{ old('delivery_method', 'pickup') == 'pickup' ? 'checked' : '' }}
                                   onchange="toggleDeliveryAddress()">
                            <label class="form-check-label" for="pickup">
                                <strong>Pickup from Seller</strong><br>
                                <small class="text-muted">Collect items directly from the seller</small>
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" 
                                   type="radio" 
                                   name="delivery_method" 
                                   id="home_delivery" 
                                   value="home_delivery" 
                                   {{ old('delivery_method') == 'home_delivery' ? 'checked' : '' }}
                                   onchange="toggleDeliveryAddress()">
                            <label class="form-check-label" for="home_delivery">
                                <strong>Home Delivery</strong><br>
                                <small class="text-muted">Get items delivered to your address</small>
                            </label>
                        </div>

                        <div id="deliveryAddressField" style="display: none;">
                            <label for="delivery_address" class="form-label">Delivery Address *</label>
                            <textarea class="form-control @error('delivery_address') is-invalid @enderror" 
                                      id="delivery_address" 
                                      name="delivery_address" 
                                      rows="3" 
                                      placeholder="Enter your complete address">{{ old('delivery_address', Auth::user()->address) }}</textarea>
                            @error('delivery_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-credit-card"></i> Payment Method</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" 
                                   type="radio" 
                                   name="payment_method" 
                                   id="digital" 
                                   value="digital" 
                                   {{ old('payment_method', 'digital') == 'digital' ? 'checked' : '' }}>
                            <label class="form-check-label" for="digital">
                                <strong>Digital Payment</strong><br>
                                <small class="text-muted">Pay instantly online (Recommended)</small>
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="radio" 
                                   name="payment_method" 
                                   id="cash_on_delivery" 
                                   value="cash_on_delivery" 
                                   {{ old('payment_method') == 'cash_on_delivery' ? 'checked' : '' }}>
                            <label class="form-check-label" for="cash_on_delivery">
                                <strong>Cash on Delivery</strong><br>
                                <small class="text-muted">Pay when you receive the items</small>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Order Summary -->
            <div class="col-md-4">
                <div class="card sticky-top" style="top: 20px;">
                    <div class="card-header">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="text-muted">Items ({{ $cartItems->count() }})</h6>
                            @foreach($cartItems as $item)
                                <div class="d-flex justify-content-between mb-2">
                                    <div>
                                        <small>{{ $item->product->name }}</small><br>
                                        <small class="text-muted">Qty: {{ $item->quantity }} × Nu. {{ number_format($item->product->rental_price, 2) }}</small>
                                    </div>
                                    <div class="text-end">
                                        <small>Nu. {{ number_format($item->product->rental_price * $item->quantity, 2) }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <strong>Nu. {{ number_format($total, 2) }}</strong>
                        </div>
                        
                        <div class="alert alert-info mt-3 mb-0">
                            <small><i class="bi bi-info-circle"></i> Rental prices are per day</small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary w-100 btn-lg">
                            <i class="bi bi-check-circle"></i> Place Order
                        </button>
                        <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                            <i class="bi bi-arrow-left"></i> Back to Cart
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function toggleDeliveryAddress() {
        const homeDelivery = document.getElementById('home_delivery');
        const addressField = document.getElementById('deliveryAddressField');
        
        if (homeDelivery.checked) {
            addressField.style.display = 'block';
            document.getElementById('delivery_address').required = true;
        } else {
            addressField.style.display = 'none';
            document.getElementById('delivery_address').required = false;
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        toggleDeliveryAddress();
    });
</script>
@endpush
@endsection
