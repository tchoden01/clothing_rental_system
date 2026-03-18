@extends('admin.layouts.app')

@section('title', 'Notifications - Admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h2 class="mb-1">Notifications</h2>
            <p class="text-muted mb-0">Pending requests that require admin action.</p>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Pending Seller Verifications</div>
                    <div class="fs-3 fw-bold">{{ $pendingSummary['sellers'] }}</div>
                    <a href="{{ route('admin.sellers') }}" class="btn btn-sm btn-outline-primary mt-2">Review Sellers</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Pending Product Approvals</div>
                    <div class="fs-3 fw-bold">{{ $pendingSummary['products'] }}</div>
                    <a href="{{ route('admin.products') }}" class="btn btn-sm btn-outline-primary mt-2">Review Products</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Pending Category Requests</div>
                    <div class="fs-3 fw-bold">{{ $pendingSummary['categories'] }}</div>
                    <a href="{{ route('admin.categories') }}" class="btn btn-sm btn-outline-primary mt-2">Review Categories</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Seller Requests</h6>
                    <a href="{{ route('admin.sellers') }}" class="small">View all</a>
                </div>
                <div class="card-body p-0">
                    @if($pendingSellerRequests->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($pendingSellerRequests as $seller)
                                <li class="list-group-item">
                                    <div class="fw-semibold">{{ $seller->shop_name }}</div>
                                    <small class="text-muted">{{ $seller->user->name }} • {{ $seller->created_at->diffForHumans() }}</small>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="p-3 text-muted">No pending seller requests.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Product Requests</h6>
                    <a href="{{ route('admin.products') }}" class="small">View all</a>
                </div>
                <div class="card-body p-0">
                    @if($pendingProducts->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($pendingProducts as $product)
                                <li class="list-group-item">
                                    <div class="fw-semibold">{{ $product->name }}</div>
                                    <small class="text-muted">{{ $product->seller->user->name }} • {{ $product->created_at->diffForHumans() }}</small>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="p-3 text-muted">No pending product requests.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Category Requests</h6>
                    <a href="{{ route('admin.categories') }}" class="small">View all</a>
                </div>
                <div class="card-body p-0">
                    @if($pendingCategories->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($pendingCategories as $category)
                                <li class="list-group-item">
                                    <div class="fw-semibold">{{ $category->name }}</div>
                                    <small class="text-muted">
                                        {{ optional($category->seller)->shop_name ?? 'System' }} • {{ $category->created_at->diffForHumans() }}
                                    </small>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="p-3 text-muted">No pending category requests.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
