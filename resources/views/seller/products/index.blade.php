@extends('seller.layouts.app')

@section('title', 'My Products')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>My Products</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('seller.products.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add New Product
            </a>
        </div>
    </div>

    @if($products->count() > 0)
        <div class="row">
            @foreach($products as $product)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        @if($product->images && count($product->images) > 0)
                            <img src="{{ asset('storage/' . $product->images[0]) }}" 
                                 class="card-img-top" alt="{{ $product->name }}" 
                                 style="height: 200px; object-fit: cover;">
                        @else
                            <div class="bg-secondary text-white d-flex align-items-center justify-content-center" 
                                 style="height: 200px;">
                                <i class="bi bi-image" style="font-size: 3rem;"></i>
                            </div>
                        @endif
                        
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                @if($product->is_approved)
                                    <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </div>
                            
                            <p class="card-text text-muted small">{{ Str::limit($product->description, 60) }}</p>
                            
                            <div class="mb-2">
                                <small class="text-muted">Category:</small> 
                                <span class="badge bg-primary">{{ $product->category->name }}</span>
                            </div>
                            
                            @if($product->size)
                                <p class="mb-1"><small><strong>Size:</strong> {{ $product->size }}</small></p>
                            @endif
                            
                            @if($product->color)
                                <p class="mb-1"><small><strong>Color:</strong> {{ $product->color }}</small></p>
                            @endif
                            
                            <p class="mb-1"><small><strong>Quantity:</strong> {{ $product->quantity }}</small></p>
                            <p class="text-primary fw-bold mb-3">Nu. {{ number_format($product->rental_price, 2) }} / day</p>
                            
                            <div class="d-flex gap-2">
                                <a href="{{ route('seller.products.edit', $product->id) }}" 
                                   class="btn btn-sm btn-outline-primary flex-fill">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('seller.products.delete', $product->id) }}" 
                                      method="POST" class="flex-fill"
                                      onsubmit="return confirm('Are you sure you want to delete this product?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row">
            <div class="col-12">
                {{ $products->links() }}
            </div>
        </div>
    @else
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> You haven't added any products yet. 
            <a href="{{ route('seller.products.create') }}" class="alert-link">Add your first product</a>
        </div>
    @endif
</div>
@endsection
