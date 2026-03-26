@extends('layouts.app')

@section('title', 'Manage Orders - Admin')

@section('content')
<div class="container">
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
                                {{ $order->orderItems->count() }} item(s)<br>
                                <small class="text-muted">
                                    @foreach($order->orderItems->take(2) as $item)
                                        {{ $item->product->name }}@if(!$loop->last), @endif
                                    @endforeach
                                    @if($order->orderItems->count() > 2)
                                        ...
                                    @endif
                                </small>
                            </td>
                            <td>Nu. {{ number_format($order->total_amount, 2) }}</td>
                            <td>
                                @if($order->payment_status == 'paid')
                                    <span class="badge bg-success">Paid</span>
                                @elseif($order->payment_status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @else
                                    <span class="badge bg-danger">Failed</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ ucwords(str_replace('_', ' ', $order->status)) }}</span>
                            </td>
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> View
                                </a>
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
