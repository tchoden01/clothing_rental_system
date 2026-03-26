@extends('layouts.app')

@section('title', 'Manage Orders - Admin')

@section('content')
<div class="container">
    <style>
        .order-items-stack {
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
        }

        .order-item-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.2rem 0.45rem;
            border: 1px solid #e4ddd3;
            border-radius: 999px;
            background: #f8f4ee;
            font-size: 0.78rem;
            color: #4a3d2e;
        }

        .badge-order-confirmed { background: #0d6efd; }
        .badge-order-ongoing { background: #6f42c1; }
        .badge-order-completed { background: #6c757d; }

        .badge-pickup-pending { background: #ffc107; color: #212529; }
        .badge-pickup-ready { background: #0d6efd; }
        .badge-pickup-picked-up { background: #198754; }
        .badge-pickup-returned { background: #fd7e14; color: #fff; }
    </style>

    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Order Management</h2>
            <p class="text-muted">Monitor and manage all orders</p>
        </div>
    </div>

    @if($orders->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Total Amount</th>
                        <th>Payment Status</th>
                        <th>Order Status</th>
                        <th>Pickup Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td><strong>#{{ $order->id }}</strong></td>
                            <td>
                                {{ $order->user->name }}<br>
                                <small class="text-muted">{{ $order->user->email }}</small>
                            </td>
                            <td>
                                <div class="order-items-stack">
                                    <small class="text-muted">{{ $order->orderItems->count() }} item(s)</small>
                                    @foreach($order->orderItems->take(2) as $item)
                                        <span class="order-item-chip">
                                            {{ optional($item->product)->name ?? 'Item' }}
                                            <span class="text-muted">x{{ $item->quantity }}</span>
                                        </span>
                                    @endforeach
                                    @if($order->orderItems->count() > 2)
                                        <small class="text-muted">+{{ $order->orderItems->count() - 2 }} more item(s)</small>
                                    @endif
                                </div>
                            </td>
                            <td>Nu. {{ number_format($order->calculated_total, 2) }}</td>
                            <td>
                                @if($order->display_payment_status === 'paid')
                                    <span class="badge bg-success">Paid</span>
                                @elseif($order->display_payment_status === 'refunded')
                                    <span class="badge bg-secondary">Refunded</span>
                                @elseif($order->display_payment_status === 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @else
                                    <span class="badge bg-danger">Failed</span>
                                @endif
                            </td>
                            <td>
                                @if($order->display_order_status === 'completed')
                                    <span class="badge badge-order-completed">Completed</span>
                                @elseif($order->display_order_status === 'ongoing')
                                    <span class="badge badge-order-ongoing">Ongoing</span>
                                @else
                                    <span class="badge badge-order-confirmed">Confirmed</span>
                                @endif
                            </td>
                            <td>
                                @if($order->display_pickup_status === 'returned')
                                    <span class="badge badge-pickup-returned">Returned</span>
                                @elseif($order->display_pickup_status === 'picked_up')
                                    <span class="badge badge-pickup-picked-up">Picked Up</span>
                                @elseif($order->display_pickup_status === 'ready')
                                    <span class="badge badge-pickup-ready">Ready</span>
                                @else
                                    <span class="badge badge-pickup-pending">Pending</span>
                                @endif
                            </td>
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <a href="{{ route('admin.pickups', ['order_id' => $order->id]) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-truck"></i> Manage Pickup
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                    <div class="app-pagination w-100">
                        {{ $orders->links('pagination::tailwind') }}
                    </div>
            </div>
        </div>
    @else
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> No orders found.
        </div>
    @endif
</div>
@endsection
