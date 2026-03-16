@extends('layouts.app')

@section('title', 'Manage Customers - Admin')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h2 class="mb-1">Manage Customers</h2>
            <p class="text-muted mb-0">View customers, suspend accounts, and inspect order history.</p>
        </div>
        <form method="GET" action="{{ route('admin.customers') }}" class="d-flex" style="max-width: 360px; width: 100%;">
            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search name, email, or contact"
                value="{{ $search }}"
            >
            <button class="btn btn-outline-primary ms-2" type="submit">Search</button>
        </form>
    </div>

    @if($customers->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Contact</th>
                        <th>Orders</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $customer)
                        <tr>
                            <td>#{{ $customer->id }}</td>
                            <td>
                                <div class="fw-semibold">{{ $customer->name }}</div>
                                <small class="text-muted">{{ $customer->email }}</small>
                            </td>
                            <td>{{ $customer->contact_number ?: 'N/A' }}</td>
                            <td>{{ $customer->orders_count }}</td>
                            <td>
                                @if($customer->is_suspended)
                                    <span class="badge bg-danger">Suspended</span>
                                @else
                                    <span class="badge bg-success">Active</span>
                                @endif
                            </td>
                            <td>{{ $customer->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('admin.customers.orders', $customer->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-clock-history"></i> Order History
                                    </a>

                                    <form action="{{ route('admin.customers.toggle-suspension', $customer->id) }}" method="POST">
                                        @csrf
                                        <button
                                            type="submit"
                                            class="btn btn-sm {{ $customer->is_suspended ? 'btn-success' : 'btn-danger' }}"
                                            onclick="return confirm('{{ $customer->is_suspended ? 'Reactivate this customer account?' : 'Suspend this customer account?' }}')"
                                        >
                                            <i class="bi {{ $customer->is_suspended ? 'bi-unlock' : 'bi-lock' }}"></i>
                                            {{ $customer->is_suspended ? 'Reactivate' : 'Suspend' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $customers->links() }}
        </div>
    @else
        <div class="alert alert-info mb-0">
            <i class="bi bi-info-circle"></i> No customer records found.
        </div>
    @endif
</div>
@endsection
