@extends('seller.layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h2>Order Items</h2>
            <p class="text-muted">Manage orders for your products</p>
        </div>
    </div>

    @if($orderItems->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Product</th>
                        <th>Customer</th>
                        <th>Quantity</th>
                        <th>Rental Period</th>
                        <th>Total</th>
                        <th>Earnings</th>
                        <th>Payment Status</th>
                        <th>Order Status</th>
                        <th>Pickup Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orderItems as $item)
                        <tr>
                            <td>#{{ $item->order_id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($item->product->primary_image_url)
                                        <img src="{{ $item->product->primary_image_url }}" 
                                             class="me-2" alt="{{ $item->product->name }}" 
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                    @endif
                                    <div>
                                        <strong>{{ $item->product->name }}</strong><br>
                                        <small class="text-muted">{{ $item->product->category->name }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                {{ $item->order->user->name }}<br>
                                <small class="text-muted">{{ $item->order->user->email }}</small>
                            </td>
                            <td>{{ $item->quantity }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($item->rental_start_date)->format('d M Y') }}<br>
                                <small class="text-muted">to</small><br>
                                {{ \Carbon\Carbon::parse($item->rental_end_date)->format('d M Y') }}
                            </td>
                            <td>Nu. {{ number_format($item->line_total, 2) }}</td>
                            <td>
                                <strong class="text-success">Nu. {{ number_format($item->seller_earnings, 2) }}</strong>
                            </td>
                            <td>
                                @if($item->display_payment_status === 'paid')
                                    <span class="badge bg-success">Paid</span>
                                @elseif($item->display_payment_status === 'refunded')
                                    <span class="badge bg-secondary">Refunded</span>
                                @elseif($item->display_payment_status === 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @else
                                    <span class="badge bg-danger">Failed</span>
                                @endif
                            </td>
                            <td>
                                @if($item->display_order_status === 'completed')
                                    <span class="badge bg-secondary">Completed</span>
                                @elseif($item->display_order_status === 'ongoing')
                                    <span class="badge" style="background:#6f42c1; color:#fff;">Ongoing</span>
                                @else
                                    <span class="badge bg-primary">Confirmed</span>
                                @endif
                            </td>
                            <td>
                                @if($item->display_pickup_status === 'returned')
                                    <span class="badge" style="background:#fd7e14; color:#fff;">Returned</span>
                                @elseif($item->display_pickup_status === 'picked_up')
                                    <span class="badge bg-success">Picked Up</span>
                                @elseif($item->display_pickup_status === 'ready')
                                    <span class="badge bg-primary">Ready</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @endif
                            </td>
                            <td>{{ $item->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('seller.pickups', ['order_id' => $item->order_id]) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-truck"></i> Manage Pickup
                                    </a>
                                @if($item->display_payment_status === 'paid' && \Carbon\Carbon::now()->gte($item->rental_end_date))
                                    <a href="{{ route('seller.orders.return', $item->id) }}" class="btn btn-sm btn-outline-primary">
                                        Process Return
                                    </a>
                                @else
                                    <span class="small text-muted align-self-center">Ongoing</span>
                                @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="app-pagination">
                    {{ $orderItems->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> No orders yet for your products.
        </div>
    @endif
</div>
@endsection
