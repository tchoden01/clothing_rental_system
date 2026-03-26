@extends('layouts.app')

@section('title', 'Manage Products - Admin')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Product Management</h2>
            <p class="text-muted">Approve or reject seller products</p>
        </div>
    </div>

    @if($products->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Seller</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>Date Added</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr class="{{ $product->status === 'pending' ? 'table-warning' : '' }}">
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($product->images && count($product->images) > 0)
                                        <img src="{{ asset('storage/' . $product->images[0]) }}" 
                                             class="me-2 admin-product-thumb" alt="{{ $product->name }}">
                                    @else
                                        <div class="admin-product-empty d-flex align-items-center justify-content-center me-2">
                                            <i class="bi bi-image" style="color: #9f968a;"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $product->name }}</strong><br>
                                        <small class="text-muted">{{ Str::limit($product->description, 40) }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                {{ $product->seller->user->name }}<br>
                                <small class="text-muted">{{ $product->seller->user->email }}</small>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $product->category->name }}</span>
                            </td>
                            <td>Nu. {{ number_format($product->rental_price, 2) }}/day</td>
                            <td>{{ $product->quantity }}</td>
                            <td>
                                @if($product->status === 'pending')
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-clock"></i> Pending
                                    </span>
                                @elseif(in_array($product->status, ['approved', 'available'], true))
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle"></i> Approved
                                    </span>
                                @elseif($product->status === 'rented')
                                    <span class="badge bg-info text-dark">
                                        <i class="bi bi-bag-check"></i> Rented
                                    </span>
                                @elseif($product->status === 'rejected')
                                    <span class="badge bg-danger">
                                        <i class="bi bi-x-circle"></i> Rejected
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        {{ ucfirst($product->status) }}
                                    </span>
                                @endif
                            </td>
                            <td>{{ $product->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    @if($product->status === 'pending')
                                        <form action="{{ route('admin.products.approve', $product->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" 
                                                    title="Approve Product">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    @if(in_array($product->status, ['approved', 'available'], true))
                                        <form action="{{ route('admin.products.reject', $product->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-warning" 
                                                    title="Unapprove Product"
                                                    onclick="return confirm('Are you sure you want to unapprove this product?')">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <a href="{{ route('admin.products.show', $product->id) }}" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="View Product Details">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                    <div class="app-pagination">
                        {{ $products->links('pagination::tailwind') }}
                    </div>
            </div>
        </div>
    @else
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> No products found.
        </div>
    @endif
</div>

@push('styles')
<style>
    .table-warning {
        background-color: #fff3cd !important;
    }

    .admin-product-thumb,
    .admin-product-empty {
        width: 60px;
        height: 60px;
        border-radius: 4px;
    }

    .admin-product-thumb {
        object-fit: contain;
        object-position: center center;
        background: #f4f1eb;
        padding: 0.2rem;
    }

    .admin-product-empty {
        background: #f4f1eb;
    }
</style>
@endpush
@endsection
