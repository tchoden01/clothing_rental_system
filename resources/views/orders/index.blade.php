@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h2>My Orders</h2>
        </div>
    </div>

    @if($orders->count() > 0)
        <div class="row">
            @foreach($orders as $order)
                @php
                    $refundPreview = $refundPreviews[$order->id] ?? null;
                    $canCancelOrder = in_array($order->status, ['pending', 'confirmed'], true)
                        && !empty($refundPreview)
                        && !empty($refundPreview['can_cancel']);
                @endphp
                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">Order #{{ $order->id }}</h5>
                                <small class="text-muted">{{ $order->created_at->format('d M Y, h:i A') }}</small>
                            </div>
                            <div>
                                @if($order->payment_status == 'paid')
                                    <span class="badge bg-success">Paid</span>
                                @elseif($order->payment_status == 'refunded')
                                    <span class="badge bg-secondary">Refunded</span>
                                @elseif($order->payment_status == 'pending')
                                    <span class="badge bg-warning">Payment Pending</span>
                                @else
                                    <span class="badge bg-danger">Payment Failed</span>
                                @endif
                                <span class="badge bg-info">{{ ucwords(str_replace('_', ' ', $order->status)) }}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <h6 class="mb-3">Order Items</h6>
                                    @foreach($order->orderItems as $item)
                                        <div class="d-flex mb-3 pb-3 border-bottom">
                                            @if($item->product->images && count($item->product->images) > 0)
                                                <img src="{{ asset('storage/' . $item->product->images[0]) }}" 
                                                     alt="{{ $item->product->name }}" 
                                                     style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px;" 
                                                     class="me-3">
                                            @endif
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">{{ $item->product->name }}</h6>
                                                <p class="text-muted mb-1">
                                                    <small>Quantity: {{ $item->quantity }}</small>
                                                </p>
                                                <p class="text-muted mb-0">
                                                    <small>
                                                        <i class="bi bi-calendar-range"></i> 
                                                        {{ \Carbon\Carbon::parse($item->rental_start_date)->format('d M Y') }} - 
                                                        {{ \Carbon\Carbon::parse($item->rental_end_date)->format('d M Y') }}
                                                    </small>
                                                </p>
                                            </div>
                                            <div class="text-end">
                                                <strong>Nu. {{ number_format($item->rental_price * $item->quantity, 2) }}</strong>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="col-md-4">
                                    <div class="bg-light p-3 rounded">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Total:</span>
                                            <strong>Nu. {{ number_format($order->total_price, 2) }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Payment:</span>
                                            <span>{{ ucfirst($order->payment->payment_method ?? 'N/A') }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Delivery:</span>
                                            <span>{{ ucwords(str_replace('_', ' ', $order->delivery_method)) }}</span>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary w-100">
                                            <i class="bi bi-eye"></i> View Details
                                        </a>
                                        @if($canCancelOrder)
                                            <form
                                                action="{{ route('orders.cancel', $order->id) }}"
                                                method="POST"
                                                class="mt-2 cancel-order-form"
                                                data-refund-amount="{{ number_format((float) ($refundPreview['refund_amount'] ?? 0), 2, '.', '') }}"
                                                data-refund-percentage="{{ number_format((float) ($refundPreview['refund_percentage'] ?? 0), 2, '.', '') }}"
                                                data-hours-before="{{ (int) ($refundPreview['hours_before_start'] ?? 0) }}"
                                                data-platform-fee-refunded="{{ !empty($refundPreview['platform_fee_refunded']) ? '1' : '0' }}"
                                                data-platform-fee="{{ number_format((float) ($refundPreview['platform_fee_amount'] ?? 0), 2, '.', '') }}"
                                            >
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger w-100">
                                                    <i class="bi bi-x-circle"></i> Cancel Order
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row">
            <div class="col-12">
                <div class="app-pagination">
                    {{ $orders->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> You haven't placed any orders yet. 
            <a href="{{ route('products.index') }}" class="alert-link">Start shopping</a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cancelForms = document.querySelectorAll('.cancel-order-form');

        cancelForms.forEach(function (cancelForm) {
            cancelForm.addEventListener('submit', function (event) {
                event.preventDefault();

                const refundAmount = cancelForm.dataset.refundAmount || '0.00';
                const refundPercentage = cancelForm.dataset.refundPercentage || '0';
                const hoursBefore = cancelForm.dataset.hoursBefore || '0';
                const platformFee = cancelForm.dataset.platformFee || '0.00';
                const platformFeeRefunded = cancelForm.dataset.platformFeeRefunded === '1';

                Swal.fire({
                    title: 'Cancel this order?',
                    html: 'Your estimated refund is <strong>Nu. ' + refundAmount + '</strong><br>'
                        + '(' + refundPercentage + '% policy rate, ' + hoursBefore + ' hours before start).'
                        + (platformFeeRefunded ? '' : '<br><small class="text-muted">Platform fee (Nu. ' + platformFee + ') is non-refundable.</small>'),
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
    });
</script>
@endpush
