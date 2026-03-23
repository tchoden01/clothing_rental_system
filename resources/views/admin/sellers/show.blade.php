@extends('layouts.app')

@section('title', 'Seller Details')

@push('styles')
<style>
    .seller-detail-card {
        border: 0;
        border-radius: 14px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    }

    .seller-detail-label {
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #7b746a;
        margin-bottom: 0.2rem;
    }

    .seller-detail-value {
        font-size: 1rem;
        color: #2b2723;
        margin-bottom: 1rem;
        word-break: break-word;
    }

    .seller-license-preview {
        width: 100%;
        max-height: 330px;
        object-fit: contain;
        border: 1px solid #ded8cc;
        border-radius: 10px;
        background: #fff;
    }

    .status-badge {
        font-size: 0.8rem;
        padding: 0.35rem 0.7rem;
    }

    .btn-approve {
        background: #800020;
        border-color: #800020;
        color: #fff;
    }

    .btn-approve:hover {
        background: #650019;
        border-color: #650019;
        color: #fff;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Seller Details</h2>
            <p class="text-muted mb-0">Review seller profile and registration documents.</p>
        </div>
        <a href="{{ route('admin.sellers') }}" class="btn btn-outline-secondary">Back to Sellers</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card seller-detail-card">
        <div class="card-body p-4 p-lg-5">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                <h4 class="mb-0">Application Information</h4>
                @if($seller->status === 'verified')
                    <span class="badge bg-success status-badge">Verified</span>
                @elseif($seller->status === 'rejected')
                    <span class="badge bg-danger status-badge">Rejected</span>
                @else
                    <span class="badge bg-warning text-dark status-badge">Pending</span>
                @endif
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="seller-detail-label">Full Name</div>
                    <div class="seller-detail-value">{{ $seller->full_name ?? $seller->user->name ?? 'N/A' }}</div>
                </div>

                <div class="col-md-6">
                    <div class="seller-detail-label">Email</div>
                    <div class="seller-detail-value">{{ $seller->email ?? $seller->user->email ?? 'N/A' }}</div>
                </div>

                <div class="col-md-6">
                    <div class="seller-detail-label">Phone</div>
                    <div class="seller-detail-value">{{ $seller->phone_number ?? $seller->contact_number ?? 'N/A' }}</div>
                </div>

                <div class="col-md-6">
                    <div class="seller-detail-label">Shop Name</div>
                    <div class="seller-detail-value">{{ $seller->shop_name ?? 'N/A' }}</div>
                </div>

                <div class="col-md-6">
                    <div class="seller-detail-label">Shop Description</div>
                    <div class="seller-detail-value">{{ $seller->shop_description ?? 'N/A' }}</div>
                </div>

                <div class="col-md-6">
                    <div class="seller-detail-label">Location</div>
                    <div class="seller-detail-value">{{ $seller->location ?? $seller->address ?? 'N/A' }}</div>
                </div>

                <div class="col-md-6">
                    <div class="seller-detail-label">CID Number</div>
                    <div class="seller-detail-value">{{ $seller->cid_number ?? 'N/A' }}</div>
                </div>

                <div class="col-md-6">
                    <div class="seller-detail-label">Status</div>
                    <div class="seller-detail-value text-capitalize">{{ $seller->status ?? 'N/A' }}</div>
                </div>
            </div>

            <hr class="my-4">

            <div class="seller-detail-label mb-2">Business License</div>
            @php
                $licensePath = $seller->business_license ? asset('storage/' . ltrim($seller->business_license, '/')) : null;
                $licenseExt = $seller->business_license ? strtolower(pathinfo($seller->business_license, PATHINFO_EXTENSION)) : null;
                $isImage = in_array($licenseExt, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
            @endphp

            @if($licensePath)
                @if($isImage)
                    <img src="{{ $licensePath }}" alt="Business License" class="seller-license-preview mb-3">
                    <div>
                        <a href="{{ $licensePath }}" target="_blank" class="btn btn-outline-secondary btn-sm">Open Full Image</a>
                    </div>
                @else
                    <a href="{{ $licensePath }}" target="_blank" class="btn btn-outline-secondary">View Business License File</a>
                @endif
            @else
                <p class="text-muted mb-0">No business license uploaded.</p>
            @endif

            @if($seller->status === 'rejected' && $seller->rejection_reason)
                <hr class="my-4">
                <div class="seller-detail-label">Rejection Reason</div>
                <div class="seller-detail-value mb-0">{{ $seller->rejection_reason }}</div>
            @endif

            <hr class="my-4">

            <div class="d-flex flex-wrap gap-2">
                @if($seller->status === 'pending')
                    <form action="{{ route('admin.sellers.verify', $seller->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-approve">
                            <i class="bi bi-check-circle"></i> Approve Seller
                        </button>
                    </form>

                    <form action="{{ route('admin.sellers.reject', $seller->id) }}" method="POST" class="d-inline-flex flex-column gap-2" style="min-width: 320px;">
                        @csrf
                        <textarea name="reason" class="form-control" rows="2" placeholder="Enter rejection reason" required></textarea>
                        <button type="submit" class="btn btn-danger align-self-start">
                            <i class="bi bi-x-circle"></i> Reject Seller
                        </button>
                    </form>
                @else
                    <div class="alert alert-light border mb-0">
                        Approve/Reject actions are only available while seller status is <strong>pending</strong>.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
