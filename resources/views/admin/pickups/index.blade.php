@extends('admin.layouts.app')

@section('title', 'Pickup Management')

@section('content')
<div class="container py-3 py-md-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h2 class="mb-1">Pickup Management</h2>
            <p class="text-muted mb-0">Track seller pickup, customer delivery, and return pickup lifecycle.</p>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Item Name</th>
                            <th>Seller</th>
                            <th>Customer</th>
                            <th>Pickup Date</th>
                            <th>Return Date</th>
                            <th>Pickup Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pickups as $pickup)
                            <tr>
                                <td><strong>#{{ $pickup->order_id }}</strong></td>
                                <td>{{ optional(optional($pickup->orderItem)->product)->name ?? 'N/A' }}</td>
                                <td>{{ optional(optional($pickup->seller)->user)->name ?? 'N/A' }}</td>
                                <td>{{ optional($pickup->customer)->name ?? optional($pickup->order)->user->name ?? 'N/A' }}</td>
                                <td>{{ optional($pickup->pickup_date)->format('d M Y') ?? 'N/A' }}</td>
                                <td>{{ optional($pickup->return_date)->format('d M Y') ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $statusLabels[$pickup->pickup_status] ?? ucwords(str_replace('_', ' ', $pickup->pickup_status)) }}
                                    </span>
                                </td>
                                <td class="text-end" style="min-width: 280px;">
                                    <form action="{{ route('admin.pickups.status', $pickup->id) }}" method="POST" class="d-flex gap-2 justify-content-end">
                                        @csrf
                                        <select name="pickup_status" class="form-select form-select-sm" style="max-width: 190px;" required>
                                            @foreach($statusLabels as $value => $label)
                                                <option value="{{ $value }}" {{ $pickup->pickup_status === $value ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">No pickup records found yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $pickups->links() }}
    </div>
</div>
@endsection
