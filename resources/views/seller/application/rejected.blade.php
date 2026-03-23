@extends('layouts.app')

@section('title', 'Application Not Approved')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="mb-0">Application Not Approved</h2>
                        <span class="badge bg-danger fs-6">Rejected</span>
                    </div>

                    <p class="text-muted mb-4">
                        Your seller application was reviewed and could not be approved at this time.
                        Please review the reason below, update your details, and resubmit your application.
                    </p>

                    <div class="alert alert-warning" role="alert">
                        <strong>Admin reason:</strong><br>
                        {{ $seller->rejection_reason ?: 'No specific reason provided.' }}
                    </div>

                    <div class="d-flex flex-wrap gap-2 mt-4">
                        <a href="{{ route('seller.application.edit') }}" class="btn btn-dark">Edit Application</a>
                        <a href="mailto:support@rentique.bt?subject=Seller%20Application%20Support" class="btn btn-outline-secondary">Contact Support</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
