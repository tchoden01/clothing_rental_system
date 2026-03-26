@extends('admin.layouts.app')

@section('title', 'Commission Reports')

@section('content')
<div class="container py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Commission Reports</h2>
        <a href="{{ route('admin.settings') }}" class="btn btn-sm btn-outline-secondary">Adjust Rate</a>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="card h-100"><div class="card-body"><small class="text-muted">Total Revenue (Completed Orders)</small><h5 class="mb-0">Nu. {{ number_format($totalRevenue, 2) }}</h5></div></div>
        </div>
        <div class="col-md-4">
            <div class="card h-100"><div class="card-body"><small class="text-muted">Platform Commission ({{ number_format($commissionRate, 2) }}%)</small><h5 class="mb-0">Nu. {{ number_format($platformCommission, 2) }}</h5></div></div>
        </div>
        <div class="col-md-4">
            <div class="card h-100"><div class="card-body"><small class="text-muted">Seller Payout</small><h5 class="mb-0">Nu. {{ number_format($sellerPayout, 2) }}</h5></div></div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><strong>Completed Orders Breakdown</strong></div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Customer</th>
                            <th>Total Revenue</th>
                            <th>Commission</th>
                            <th>Seller Payout</th>
                            <th>Completed Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($completedOrders as $order)
                            @php
                                $orderRevenue = (float) $order->total_price;
                                $orderCommission = round($orderRevenue * ((float) $commissionRate / 100), 2);
                                $orderSellerPayout = round($orderRevenue - $orderCommission, 2);
                            @endphp
                            <tr>
                                <td>#{{ $order->order_number ?: $order->id }}</td>
                                <td>{{ optional($order->user)->name ?? 'N/A' }}</td>
                                <td>Nu. {{ number_format($orderRevenue, 2) }}</td>
                                <td>Nu. {{ number_format($orderCommission, 2) }}</td>
                                <td>Nu. {{ number_format($orderSellerPayout, 2) }}</td>
                                <td>{{ $order->updated_at->format('d M Y, h:i A') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">No completed orders found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(method_exists($completedOrders, 'links'))
            <div class="card-footer"><div class="app-pagination">{{ $completedOrders->links('pagination::tailwind') }}</div></div>
        @endif
    </div>
</div>
@endsection
