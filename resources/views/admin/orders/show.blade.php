@extends('layouts.app')

@section('title', 'Order Details - Admin')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Order #{{ $order->id }}</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.orders') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Orders
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Order Summary -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Order Items</h5>
                </div>
                <div class="card-body">
                    @foreach($order->orderItems as $item)
                        <div class="row mb-3 pb-3 border-bottom">
                            <div class="col-md-2">
                                @if($item->product->images && count($item->product->images) > 0)
                                    <img src="{{ asset('storage/' . $item->product->images[0]) }}" 
                                         class="img-fluid" alt="{{ $item->product->name }}">
                                @endif
                            </div>
                            <div class="col-md-10">
                                <h6>{{ $item->product->name }}</h6>
                                <p class="text-muted mb-1">
                                    <small>Seller: {{ $item->product->seller->user->name }}</small>
                                </p>
                                <p class="mb-1">
                                    <strong>Rental Period:</strong> 
                                    {{ \Carbon\Carbon::parse($item->rental_start_date)->format('d M Y') }} - 
                                    {{ \Carbon\Carbon::parse($item->rental_end_date)->format('d M Y') }}
                                </p>
                                <p class="mb-1">
                                    <strong>Quantity:</strong> {{ $item->quantity }} | 
                                    <strong>Price:</strong> Nu. {{ number_format($item->price, 2) }}
                                </p>
                                <p class="mb-0">
                                    <small class="text-muted">
                                        Platform Commission: Nu. {{ number_format($item->platform_commission, 2) }} | 
                                        Seller Earnings: Nu. {{ number_format($item->seller_earnings, 2) }}
                                    </small>
                                </p>
                            </div>
                        </div>
                    @endforeach

                    <div class="row mt-3">
                        <div class="col-md-8 offset-md-4">
                            <table class="table">
                                <tr>
                                    <td><strong>Subtotal:</strong></td>
                                    <td class="text-end">Nu. {{ number_format($order->total_amount - $order->platform_commission, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Platform Commission:</strong></td>
                                    <td class="text-end">Nu. {{ number_format($order->platform_commission, 2) }}</td>
                                </tr>
                                <tr class="table-active">
                                    <td><strong>Total Amount:</strong></td>
                                    <td class="text-end"><strong>Nu. {{ number_format($order->total_amount, 2) }}</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Info -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Customer Information</h5>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>Name:</strong> {{ $order->user->name }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $order->user->email }}</p>
                    <p class="mb-0"><strong>Phone:</strong> {{ $order->user->phone ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Order Status</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.status', $order->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="status" class="form-label">Update Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="collected_from_seller" {{ $order->status == 'collected_from_seller' ? 'selected' : '' }}>Collected from Seller</option>
                                <option value="picked_up_by_customer" {{ $order->status == 'picked_up_by_customer' ? 'selected' : '' }}>Picked up by Customer</option>
                                <option value="in_use" {{ $order->status == 'in_use' ? 'selected' : '' }}>In Use</option>
                                <option value="returned_by_customer" {{ $order->status == 'returned_by_customer' ? 'selected' : '' }}>Returned by Customer</option>
                                <option value="returned_to_seller" {{ $order->status == 'returned_to_seller' ? 'selected' : '' }}>Returned to Seller</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Update Status</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Payment Information</h5>
                </div>
                <div class="card-body">
                    <p class="mb-1">
                        <strong>Payment Status:</strong> 
                        @if($order->payment_status == 'paid')
                            <span class="badge bg-success">Paid</span>
                        @elseif($order->payment_status == 'pending')
                            <span class="badge bg-warning">Pending</span>
                        @else
                            <span class="badge bg-danger">Failed</span>
                        @endif
                    </p>
                    @if($order->payment)
                        <p class="mb-1"><strong>Method:</strong> {{ ucfirst($order->payment->payment_method) }}</p>
                        <p class="mb-0"><strong>Transaction ID:</strong> {{ $order->payment->transaction_id ?? 'N/A' }}</p>
                    @endif
                    <p class="mb-0 mt-2"><small class="text-muted">Order Date: {{ $order->created_at->format('d M Y, h:i A') }}</small></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
