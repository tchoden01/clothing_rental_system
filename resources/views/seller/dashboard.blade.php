@extends('layouts.app')

@section('title', 'Seller Dashboard')

@section('content')
<div class="container">
    <h2 class="mb-4">Seller Dashboard</h2>

    @if(!$seller->is_verified)
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle"></i> Your account is pending verification. You can add products after admin approval.
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Products</h5>
                    <h2>{{ $products }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Approved Products</h5>
                    <h2>{{ $approvedProducts }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Pending Approval</h5>
                    <h2>{{ $pendingProducts }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Earnings</h5>
                    <h2>Rs. {{ number_format($totalEarnings, 2) }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-box"></i> Product Management</h5>
                    <p class="card-text">Manage your rental items and inventory.</p>
                    <a href="{{ route('seller.products') }}" class="btn btn-primary">View Products</a>
                    @if($seller->is_verified)
                        <a href="{{ route('seller.products.create') }}" class="btn btn-success">Add New Product</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-receipt"></i> Orders</h5>
                    <p class="card-text">View and manage your orders.</p>
                    <a href="{{ route('seller.orders') }}" class="btn btn-primary">View Orders</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Shop Information -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Shop Information</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th width="30%">Shop Name:</th>
                            <td>{{ $seller->shop_name }}</td>
                        </tr>
                        <tr>
                            <th>Contact Number:</th>
                            <td>{{ $seller->contact_number }}</td>
                        </tr>
                        <tr>
                            <th>Address:</th>
                            <td>{{ $seller->address }}</td>
                        </tr>
                        <tr>
                            <th>Verification Status:</th>
                            <td>
                                @if($seller->is_verified)
                                    <span class="badge bg-success">Verified</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
