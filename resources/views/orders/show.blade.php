@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
@php
    $computedOrderTotal = $order->orderItems->sum(function ($item) {
        $days = \Carbon\Carbon::parse($item->rental_start_date)->diffInDays($item->rental_end_date);
        $days = max($days, 1);

        return $item->rental_price * $item->quantity * $days;
    });

    $canCancelOrder = in_array($order->status, ['pending', 'confirmed'], true)
        && \Carbon\Carbon::today()->lt(\Carbon\Carbon::parse($order->rental_start_date));
@endphp
<div class="container order-details-page">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Order #{{ $order->id }}</h2>
            <p class="text-muted">Placed on {{ $order->created_at->format('d M Y, h:i A') }}</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Orders
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Order Items -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Order Items</h5>
                </div>
                <div class="card-body">
                    @foreach($order->orderItems as $item)
                        <div class="row mb-4 pb-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="col-md-3">
                                @if($item->product->images && count($item->product->images) > 0)
                                    <img src="{{ asset('storage/' . $item->product->images[0]) }}" 
                                         alt="{{ $item->product->name }}" 
                                         class="img-fluid rounded">
                                @else
                                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center rounded"
                                         style="height: 150px;">
                                        <i class="bi bi-image" style="font-size: 2rem;"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-9">
                                <h5>{{ $item->product->name }}</h5>
                                <p class="text-muted mb-2">
                                    <small>Seller: {{ $item->product->seller->user->name }}</small>
                                </p>
                                <p class="mb-1">
                                    <strong>Quantity:</strong> {{ $item->quantity }}<br>
                                    <strong>Price:</strong> Nu. {{ number_format($item->rental_price, 2) }} / day
                                </p>
                                <p class="mb-2">
                                    <strong>Rental Period:</strong><br>
                                    <i class="bi bi-calendar-range"></i> 
                                    {{ \Carbon\Carbon::parse($item->rental_start_date)->format('d M Y') }} - 
                                    {{ \Carbon\Carbon::parse($item->rental_end_date)->format('d M Y') }}
                                    ({{ \Carbon\Carbon::parse($item->rental_start_date)->diffInDays($item->rental_end_date) }} days)
                                </p>
                                <p class="mb-0">
                                    <strong class="text-primary">Total: Nu. {{ number_format($item->rental_price * $item->quantity * \Carbon\Carbon::parse($item->rental_start_date)->diffInDays($item->rental_end_date), 2) }}</strong>
                                </p>
                            </div>
                        </div>
                    @endforeach

                    <div class="row mt-4">
                        <div class="col-md-8 offset-md-4">
                            <table class="table">
                                <tr>
                                    <td><strong>Total Amount:</strong></td>
                                    <td class="text-end"><strong class="text-primary">Nu. {{ number_format($computedOrderTotal, 2) }}</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Info & Status -->
        <div class="col-md-4 order-side-column">
            <!-- Order Status -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Order Status</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <span class="badge bg-info p-2 w-100">
                            {{ ucwords(str_replace('_', ' ', $order->status)) }}
                        </span>
                    </div>
                    
                    @if($canCancelOrder)
                        <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="cancel-order-form">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="bi bi-x-circle"></i> Cancel Order
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Payment Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Payment Information</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <strong>Status:</strong> 
                        @if($order->payment_status == 'paid')
                            <span class="badge bg-success">Paid</span>
                        @elseif($order->payment_status == 'refunded')
                            <span class="badge bg-secondary">Refunded</span>
                        @elseif($order->payment_status == 'pending')
                            <span class="badge bg-warning">Pending</span>
                        @else
                            <span class="badge bg-danger">Failed</span>
                        @endif
                    </p>
                    @if($order->payment)
                        <p class="mb-2">
                            <strong>Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment->payment_method)) }}
                        </p>
                        @if($order->payment->transaction_id)
                            <p class="mb-0">
                                <strong>Transaction ID:</strong><br>
                                <small class="text-muted">{{ $order->payment->transaction_id }}</small>
                            </p>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Delivery Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Delivery Information</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <strong>Method:</strong> {{ ucwords(str_replace('_', ' ', $order->delivery_method)) }}
                    </p>
                    @if($order->delivery_method == 'home_delivery')
                        <p class="mb-0">
                            <strong>Address:</strong><br>
                            {{ $order->delivery_address }}
                        </p>
                    @else
                        <p class="mb-0 text-muted">
                            <small>Pickup from seller's location</small>
                        </p>
                    @endif
                </div>
            </div>

            <!-- Customer Information -->
            <div class="card customer-info-card">
                <div class="card-header">
                    <h5 class="mb-0">Customer Information</h5>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>Name:</strong> {{ $order->user->name }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $order->user->email }}</p>
                    <p class="mb-0"><strong>Phone:</strong> {{ $order->user->contact_number ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .order-details-page {
        padding-bottom: 2.5rem;
    }

    .order-side-column .customer-info-card {
        margin-bottom: 2rem;
    }

    @media (max-width: 768px) {
        .order-details-page {
            padding-bottom: 1.5rem;
        }

        .order-side-column .customer-info-card {
            margin-bottom: 1rem;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cancelForm = document.querySelector('.cancel-order-form');

        if (!cancelForm) {
            return;
        }

        cancelForm.addEventListener('submit', function (event) {
            event.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: 'Are you sure you want to cancel this order?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Cancel Order',
                cancelButtonText: 'No',
                confirmButtonColor: '#dc3545',
                reverseButtons: true
            }).then(function (result) {
                if (result.isConfirmed) {
                    cancelForm.submit();
                }
            });
        });
    });
</script>
@endpush
