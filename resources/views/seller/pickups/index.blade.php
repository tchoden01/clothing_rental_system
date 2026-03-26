@extends('seller.layouts.app')

@section('title', 'Pickup Management')

@push('styles')
<style>
    .pickup-card {
        border: 1px solid rgba(132, 112, 83, 0.22);
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.86);
        box-shadow: 0 5px 16px rgba(48, 40, 29, 0.08);
    }

    .pickup-badge-in-use {
        background: #6f42c1;
        color: #fff;
    }

    .pickup-badge-returned {
        background: #fd7e14;
        color: #fff;
    }
</style>
@endpush

@section('content')
<div class="container py-2 py-md-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h2 class="mb-1">Pickup Management</h2>
            <p class="text-muted mb-0">Update each rental step-by-step from pending to completed.</p>
        </div>
    </div>

    <div class="pickup-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Item</th>
                            <th>Customer</th>
                            <th>Pickup Date</th>
                            <th>Return Date</th>
                            <th>Status</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pickups as $pickup)
                            <tr>
                                <td><strong>#{{ $pickup->order_id }}</strong></td>
                                <td>{{ optional(optional($pickup->orderItem)->product)->name ?? 'N/A' }}</td>
                                <td>{{ optional($pickup->customer)->name ?? optional($pickup->order)->user->name ?? 'N/A' }}</td>
                                <td>{{ optional($pickup->pickup_date)->format('d M Y') ?? 'N/A' }}</td>
                                <td>{{ optional($pickup->return_date)->format('d M Y') ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge {{ $pickup->status_badge_class }}">
                                        {{ $pickup->status_label }}
                                    </span>
                                </td>
                                <td class="text-end" style="min-width: 240px;">
                                    @if($pickup->next_pickup_status)
                                        <form action="{{ route('seller.pickups.status', $pickup->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="pickup_status" value="{{ $pickup->next_pickup_status }}">
                                            <button type="submit" class="btn btn-sm btn-success">
                                                {{ $pickup->next_pickup_action_label }}
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted small">No further action</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No pickup records assigned yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $pickups->links('pagination::tailwind') }}
    </div>
</div>
@endsection
