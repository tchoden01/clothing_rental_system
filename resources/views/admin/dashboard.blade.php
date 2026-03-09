@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container">
    <h2 class="mb-4">Admin Dashboard</h2>

    <!-- Statistics Cards Row 1 -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Users</h6>
                    <h2>{{ $totalUsers }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Sellers</h6>
                    <h2>{{ $totalSellers }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6 class="card-title">Pending Sellers</h6>
                    <h2>{{ $pendingSellers }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Products</h6>
                    <h2>{{ $totalProducts }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards Row 2 -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h6 class="card-title">Pending Products</h6>
                    <h2>{{ $pendingProducts }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-dark text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Orders</h6>
                    <h2>{{ $totalOrders }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Revenue</h6>
                    <h2>Rs. {{ number_format($totalRevenue, 0) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="card-title">Commission Earned</h6>
                    <h2>Rs. {{ number_format($totalCommission, 0) }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-people"></i> Seller Management</h5>
                    <p class="card-text">Verify and manage seller accounts.</p>
                    <a href="{{ route('admin.sellers') }}" class="btn btn-primary">Manage Sellers</a>
                    @if($pendingSellers > 0)
                        <span class="badge bg-warning">{{ $pendingSellers }} pending</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-box"></i> Product Approval</h5>
                    <p class="card-text">Review and approve products.</p>
                    <a href="{{ route('admin.products') }}" class="btn btn-primary">Manage Products</a>
                    @if($pendingProducts > 0)
                        <span class="badge bg-warning">{{ $pendingProducts }} pending</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-receipt"></i> Order Management</h5>
                    <p class="card-text">View and manage all orders.</p>
                    <a href="{{ route('admin.orders') }}" class="btn btn-primary">View Orders</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Management Options -->
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-tags"></i> Category Management</h5>
                    <p class="card-text">Add and manage product categories.</p>
                    <a href="{{ route('admin.categories') }}" class="btn btn-primary">Manage Categories</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-gear"></i> Platform Settings</h5>
                    <p class="card-text">Configure commission rates and settings.</p>
                    <a href="{{ route('admin.settings') }}" class="btn btn-primary">Settings</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
