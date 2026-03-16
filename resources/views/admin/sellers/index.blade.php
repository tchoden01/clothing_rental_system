@extends('layouts.app')

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
                                <td>{{ $seller->phone ?? 'N/A' }}</td>
                                <td>
                                    @if($seller->is_verified)
                                        <span class="badge bg-success">Verified</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>
                                <td>{{ $seller->created_at->format('M d, Y') }}</td>
                                <td>
                                    @if(!$seller->is_verified)
                                        <form action="{{ route('admin.sellers.verify', $seller->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Verify this seller?')">
                                                <i class="bi bi-check-circle"></i> Verify
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.sellers.reject', $seller->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Reject this seller?')">
                                                <i class="bi bi-x-circle"></i> Reject
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">Verified</span>
                                    @endif
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
