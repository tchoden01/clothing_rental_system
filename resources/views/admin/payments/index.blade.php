@extends('admin.layouts.app')

@section('title', 'Payments')

@section('content')
<div class="container py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Payments & Payouts</h2>
        <a href="{{ route('admin.settings') }}" class="btn btn-sm btn-outline-secondary">Settings</a>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="card h-100"><div class="card-body"><small class="text-muted">Total Amount (Paid + Completed)</small><h5 class="mb-0">Nu. {{ number_format($totalAmount, 2) }}</h5></div></div>
        </div>
        <div class="col-md-3">
            <div class="card h-100"><div class="card-body"><small class="text-muted">Platform Commission ({{ number_format($commissionRate, 2) }}%)</small><h5 class="mb-0">Nu. {{ number_format($platformCommission, 2) }}</h5></div></div>
        </div>
        <div class="col-md-3">
            <div class="card h-100"><div class="card-body"><small class="text-muted">Seller Payout</small><h5 class="mb-0">Nu. {{ number_format($sellerPayout, 2) }}</h5></div></div>
        </div>
        <div class="col-md-3">
            <div class="card h-100"><div class="card-body"><small class="text-muted">Pending Seller Payouts</small><h5 class="mb-0">Nu. {{ number_format($payoutSummary['pending'], 2) }}</h5></div></div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header"><strong>Seller Payout Management</strong></div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Seller</th>
                            <th>Customer</th>
                            <th>Item</th>
                            <th>Total Amount</th>
                            <th>Commission</th>
                            <th>Seller Payout</th>
                            <th>Payout Status</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payoutItems as $item)
                            @php
                                $itemRevenue = (float) ($item->rental_price * $item->quantity);
                                $itemCommission = max($itemRevenue - (float) $item->seller_earnings, 0);
                            @endphp
                            <tr>
                                <td>#{{ optional($item->order)->order_number ?: $item->order_id }}</td>
                                <td>{{ optional(optional($item->seller)->user)->name ?? 'N/A' }}</td>
                                <td>{{ optional(optional($item->order)->user)->name ?? 'N/A' }}</td>
                                <td>{{ optional($item->product)->name ?? 'Item' }} x{{ $item->quantity }}</td>
                                <td>Nu. {{ number_format($itemRevenue, 2) }}</td>
                                <td>Nu. {{ number_format($itemCommission, 2) }}</td>
                                <td><strong>Nu. {{ number_format((float) $item->seller_earnings, 2) }}</strong></td>
                                <td>
                                    @if($item->payout_status === 'released')
                                        <span class="badge bg-success">Released</span>
                                        @if($item->payout_released_at)
                                            <div><small class="text-muted">{{ $item->payout_released_at->format('d M Y, h:i A') }}</small></div>
                                        @endif
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if($item->payout_status === 'pending')
                                        <form action="{{ route('admin.payouts.release', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary" onclick="return confirm('Release payment to seller for this item?')">
                                                Release Payment
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted small">Released</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="text-center text-muted py-4">No payout records available.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(method_exists($payoutItems, 'links'))
            <div class="card-footer"><div class="app-pagination">{{ $payoutItems->appends(['page' => request('page')])->links('pagination::tailwind') }}</div></div>
        @endif
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="card h-100"><div class="card-body"><small class="text-muted">Payments Completed</small><h5 class="mb-0">Nu. {{ number_format($paymentSummary['completed'], 2) }}</h5></div></div>
        </div>
        <div class="col-md-3">
            <div class="card h-100"><div class="card-body"><small class="text-muted">Payments Pending</small><h5 class="mb-0">Nu. {{ number_format($paymentSummary['pending'], 2) }}</h5></div></div>
        </div>
        <div class="col-md-3">
            <div class="card h-100"><div class="card-body"><small class="text-muted">Payments Failed</small><h5 class="mb-0">Nu. {{ number_format($paymentSummary['failed'], 2) }}</h5></div></div>
        </div>
        <div class="col-md-3">
            <div class="card h-100"><div class="card-body"><small class="text-muted">Payments Refunded</small><h5 class="mb-0">Nu. {{ number_format($paymentSummary['refunded'], 2) }}</h5></div></div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><strong>Payment Transactions</strong></div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Customer</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Amount</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td>#{{ optional($payment->order)->order_number ?: $payment->order_id }}</td>
                                <td>{{ optional(optional($payment->order)->user)->name ?? 'N/A' }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                <td>{{ ucfirst($payment->status) }}</td>
                                <td>Nu. {{ number_format((float) $payment->amount, 2) }}</td>
                                <td>{{ $payment->created_at->format('d M Y, h:i A') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">No payment records found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(method_exists($payments, 'links'))
            <div class="card-footer"><div class="app-pagination">{{ $payments->links('pagination::tailwind') }}</div></div>
        @endif
    </div>
</div>
@endsection
