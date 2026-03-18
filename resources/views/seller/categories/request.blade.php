@extends('seller.layouts.app')

@section('title', 'Request Category')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Request New Category</h5>
                    <a href="{{ route('seller.products.create') }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Add Product
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('seller.categories.request.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name *</label>
                            <input
                                type="text"
                                class="form-control @error('name') is-invalid @enderror"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                placeholder="e.g., Wedding Collection"
                                required
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea
                                class="form-control @error('description') is-invalid @enderror"
                                id="description"
                                name="description"
                                rows="3"
                                placeholder="Tell admin what type of products this category will contain"
                            >{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info mb-3">
                            <i class="bi bi-info-circle"></i>
                            Admin approval is required before this category appears in product forms.
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send"></i> Submit Request
                        </button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">My Pending Category Requests</h6>
                </div>
                <div class="card-body">
                    @if($pendingRequests->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($pendingRequests as $request)
                                <li class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-start gap-3">
                                        <div>
                                            <div class="fw-semibold">{{ $request->name }}</div>
                                            @if($request->description)
                                                <small class="text-muted">{{ $request->description }}</small>
                                            @endif
                                        </div>
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted mb-0">No pending category requests.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
