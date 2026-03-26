@extends('admin.layouts.app')

@section('title', 'Payments')

@section('content')
<div class="container py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Payments</h2>
        <a href="{{ route('admin.settings') }}" class="btn btn-sm btn-outline-secondary">Settings</a>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="card h-100"><div class="card-body"><small class="text-muted">Completed</small><h5 class="mb-0">Nu. {{ number_format($paymentSummary['completed'], 2) }}</h5></div></div>
        </div>
        <div class="col-md-3">
            <div class="card h-100"><div class="card-body"><small class="text-muted">Pending</small><h5 class="mb-0">Nu. {{ number_format($paymentSummary['pending'], 2) }}</h5></div></div>
        </div>
        <div class="col-md-3">
            <div class="card h-100"><div class="card-body"><small class="text-muted">Failed</small><h5 class="mb-0">Nu. {{ number_format($paymentSummary['failed'], 2) }}</h5></div></div>
        </div>
        <div class="col-md-3">
            <div class="card h-100"><div class="card-body"><small class="text-muted">Refunded</small><h5 class="mb-0">Nu. {{ number_format($paymentSummary['refunded'], 2) }}</h5></div></div>
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
