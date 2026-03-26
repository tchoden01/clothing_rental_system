@extends('layouts.app')

@section('title', 'Customer Order History - Admin')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h2 class="mb-1">Customer Order History</h2>
            <p class="mb-0 text-muted">
                {{ $customer->name }} ({{ $customer->email }})
            </p>
        </div>
        <a href="{{ route('admin.customers') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Customers
        </a>
    </div>

    @if($orders->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Date</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td><strong>{{ $order->order_number }}</strong></td>
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                            <td>{{ $order->orderItems->count() }}</td>
                            <td>Nu. {{ number_format($order->total_price, 2) }}</td>
                            <td>
                                @if($order->payment_status === 'paid')
                                    <span class="badge bg-success">Paid</span>
                                @elseif($order->payment_status === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($order->payment_status) }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info text-dark">{{ ucwords(str_replace('_', ' ', $order->status)) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
                <div class="app-pagination">
                    {{ $orders->links('pagination::tailwind') }}
                </div>
        </div>
    @else
        <div class="alert alert-info mb-0">
            <i class="bi bi-info-circle"></i> This customer has no order history yet.
        </div>
    @endif
</div>
@endsection
