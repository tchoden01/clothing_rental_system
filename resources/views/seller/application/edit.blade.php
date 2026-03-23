@extends('layouts.app')

@section('title', 'Edit Seller Application')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="mb-0">Edit Application</h2>
                        <span class="badge bg-danger fs-6">Rejected</span>
                    </div>

                    <p class="text-muted">Update your information and resubmit for review. Status will return to <strong>pending</strong>.</p>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('seller.application.resubmit') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="full_name" class="form-control" value="{{ old('full_name', $seller->full_name ?? $seller->user->name) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $seller->email ?? $seller->user->email) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Shop Name</label>
                                <input type="text" name="shop_name" class="form-control" value="{{ old('shop_name', $seller->shop_name) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Location</label>
                                <input type="text" name="location" class="form-control" value="{{ old('location', $seller->location ?? $seller->address) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">CID Number</label>
                                <input type="text" name="cid_number" class="form-control" value="{{ old('cid_number', $seller->cid_number) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Business License (optional if already uploaded)</label>
                                <input type="file" name="business_license" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Bank Name</label>
                                <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name', $seller->bank_name) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Account Number</label>
                                <input type="text" name="account_number" class="form-control" value="{{ old('account_number', $seller->account_number) }}" required>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2 mt-4">
                            <button type="submit" class="btn btn-dark">Resubmit Application</button>
                            <a href="{{ route('seller.application.rejected') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
