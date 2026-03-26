@extends('admin.layouts.app')

@section('title', 'Product Review - Admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h2 class="mb-1">Product Review</h2>
            <p class="text-muted mb-0">Inspect product details before approval or rejection.</p>
        </div>
        <a href="{{ route('admin.products') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back to Product List
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <strong>Product Images</strong>
                </div>
                <div class="card-body">
                    @if(count($product->image_urls) > 0)
                        <div class="product-images-grid">
                            @foreach($product->image_urls as $imageUrl)
                                <div class="product-image-frame rounded border">
                                    <img
                                        src="{{ $imageUrl }}"
                                        alt="{{ $product->name }}"
                                        class="product-image-fit"
                                    >
                                </div>
                            @endforeach
                        </div>
                    @elseif($product->images && count($product->images) > 0)
                        <div class="alert alert-warning mb-0">
                            Image records exist, but the files were not found in storage. Ask the seller to re-upload photos.
                        </div>
                    @else
                        <div class="text-muted">No product images uploaded.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>{{ $product->name }}</strong>
                    @if($product->status === 'pending')
                        <span class="badge bg-warning text-dark">Pending Approval</span>
                    @elseif(in_array($product->status, ['approved', 'available'], true))
                        <span class="badge bg-success">Approved</span>
                    @elseif($product->status === 'rented')
                        <span class="badge bg-info text-dark">Rented</span>
                    @elseif($product->status === 'rejected')
                        <span class="badge bg-danger">Rejected</span>
                    @else
                        <span class="badge bg-secondary">{{ ucfirst($product->status) }}</span>
                    @endif
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-5 text-muted">Seller</div>
                        <div class="col-7">{{ optional(optional($product->seller)->user)->name ?? 'N/A' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 text-muted">Seller Email</div>
                        <div class="col-7">{{ optional(optional($product->seller)->user)->email ?? 'N/A' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 text-muted">Category</div>
                        <div class="col-7">{{ optional($product->category)->name ?? 'N/A' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 text-muted">Price</div>
                        <div class="col-7">Nu. {{ number_format($product->rental_price, 2) }}/day</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 text-muted">Quantity</div>
                        <div class="col-7">{{ $product->quantity }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 text-muted">Size</div>
                        <div class="col-7">{{ $product->size ?: 'N/A' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 text-muted">Material</div>
                        <div class="col-7">{{ $product->material ?: 'N/A' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 text-muted">Color</div>
                        <div class="col-7">{{ $product->color ?: 'N/A' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 text-muted">Condition</div>
                        <div class="col-7">{{ $product->condition ?: 'N/A' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 text-muted">Location</div>
                        <div class="col-7">{{ $product->location ?: 'N/A' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 text-muted">Submitted</div>
                        <div class="col-7">{{ $product->created_at->format('d M Y, h:i A') }}</div>
                    </div>

                    <hr>
                    <div>
                        <div class="text-muted mb-1">Description</div>
                        <div>{{ $product->description }}</div>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    @if($product->status === 'pending')
                        <div class="d-flex gap-2">
                            <form action="{{ route('admin.products.approve', $product->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="bi bi-check-lg"></i> Approve
                                </button>
                            </form>
                            <form action="{{ route('admin.products.reject', $product->id) }}" method="POST" class="reject-product-form">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-sm">
                                    <i class="bi bi-x-lg"></i> Reject
                                </button>
                            </form>
                        </div>
                    @elseif($product->status === 'rejected')
                        <div class="text-muted small">
                            This product has been rejected. Seller must upload a new product to submit again.
                        </div>
                    @else
                        <form action="{{ route('admin.products.reject', $product->id) }}" method="POST" class="reject-product-form">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm">
                                <i class="bi bi-x-lg"></i> Unapprove
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .container-fluid {
        padding-top: 0.8rem;
    }

    .product-images-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 0.75rem;
    }

    .product-image-frame {
        height: 360px;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        padding: 0.75rem;
    }

    .product-image-fit {
        width: 100%;
        height: 100%;
        object-fit: contain;
        object-position: center center;
    }

    @media (max-width: 768px) {
        .product-image-frame {
            height: 280px;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const rejectForms = document.querySelectorAll('.reject-product-form');

        rejectForms.forEach(function (form) {
            form.addEventListener('submit', function (event) {
                event.preventDefault();

                Swal.fire({
                    icon: 'warning',
                    title: 'Confirm Product Rejection',
                    html: 'Are you sure you want to reject this product?<br>It will not be visible to customers until the seller updates and resubmits it.',
                    showCancelButton: true,
                    confirmButtonText: 'Reject Product',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    reverseButtons: true
                }).then(function (result) {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endpush
