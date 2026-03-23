@extends('layouts.app')

@push('styles')
<style>
    .seller-action-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .seller-action-group form {
        margin: 0;
    }

    .seller-action-btn {
        min-width: 96px;
        white-space: nowrap;
    }

    .seller-action-verify,
    .seller-action-verify:hover,
    .seller-action-verify:focus {
        background-color: #198754 !important;
        border-color: #198754 !important;
        color: #fff !important;
    }

    .seller-action-reject,
    .seller-action-reject:hover,
    .seller-action-reject:focus {
        background-color: #dc3545 !important;
        border-color: #dc3545 !important;
        color: #fff !important;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Sellers</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Seller Name</th>
                            <th>Email</th>
                            <th>Shop Name</th>
                            <th>Business License</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Registered</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sellers as $seller)
                            <tr>
                                <td>{{ $seller->id }}</td>
                                <td>{{ $seller->user->name }}</td>
                                <td>{{ $seller->user->email }}</td>
                                <td>{{ $seller->shop_name }}</td>
                                <td>{{ $seller->business_license ?? 'N/A' }}</td>
                                <td>{{ $seller->phone_number ?? $seller->contact_number ?? 'N/A' }}</td>
                                <td>
                                    @if($seller->status === 'verified')
                                        <span class="badge bg-success">Verified</span>
                                    @elseif($seller->status === 'rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>
                                <td>{{ $seller->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="seller-action-group">
                                        <a href="{{ route('admin.sellers.show', $seller->id) }}" class="btn btn-sm btn-outline-dark seller-action-btn">
                                            <i class="bi bi-eye"></i> View
                                        </a>

                                        @if($seller->status === 'pending')
                                            <form action="{{ route('admin.sellers.verify', $seller->id) }}" method="POST" class="seller-action-form">
                                                @csrf
                                                <button
                                                    type="button"
                                                    class="btn btn-sm seller-action-btn seller-action-verify seller-action-trigger"
                                                    data-action="verify"
                                                >
                                                    <i class="bi bi-check-circle"></i> Verify
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.sellers.reject', $seller->id) }}" method="POST" class="seller-action-form">
                                                @csrf
                                                <button
                                                    type="button"
                                                    class="btn btn-sm seller-action-btn seller-action-reject seller-action-trigger"
                                                    data-action="reject"
                                                >
                                                    <i class="bi bi-x-circle"></i> Reject
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <p class="text-muted mb-0">No sellers found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $sellers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const actionButtons = document.querySelectorAll('.seller-action-trigger');

        actionButtons.forEach(function (button) {
            button.addEventListener('click', function (event) {
                event.preventDefault();

                const form = button.closest('form');
                const action = button.dataset.action;

                if (!form || !action) {
                    return;
                }

                let config = null;

                if (action === 'verify') {
                    config = {
                        title: 'Verify Seller',
                        text: 'Are you sure you want to approve this seller? They will be able to upload and rent items.',
                        icon: 'question',
                        showCancelButton: true,
                        cancelButtonText: 'Cancel',
                        confirmButtonText: 'Yes, Verify',
                        confirmButtonColor: '#800020',
                        cancelButtonColor: '#6c757d',
                    };
                }

                if (action === 'reject') {
                    config = {
                        title: 'Reject Seller',
                        text: 'Are you sure you want to reject this seller? They will not be allowed to use the platform.',
                        icon: 'warning',
                        input: 'textarea',
                        inputLabel: 'Rejection reason',
                        inputPlaceholder: 'Enter reason for rejection...',
                        inputAttributes: {
                            maxlength: 1000,
                        },
                        showCancelButton: true,
                        cancelButtonText: 'Cancel',
                        confirmButtonText: 'Reject',
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        inputValidator: function (value) {
                            if (!value || !value.trim()) {
                                return 'Please provide a rejection reason.';
                            }

                            return null;
                        }
                    };
                }

                if (!config) {
                    return;
                }

                Swal.fire(config).then(function (result) {
                    if (result.isConfirmed) {
                        if (action === 'reject') {
                            let reasonInput = form.querySelector('input[name="reason"]');

                            if (!reasonInput) {
                                reasonInput = document.createElement('input');
                                reasonInput.type = 'hidden';
                                reasonInput.name = 'reason';
                                form.appendChild(reasonInput);
                            }

                            reasonInput.value = (result.value || '').trim();
                        }

                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endpush
