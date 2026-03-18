@extends('seller.layouts.app')

@section('title', 'Add New Product')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Add New Product</h5>
                    <a href="{{ route('seller.products') }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Products
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="category_id" class="form-label mb-0">Category *</label>
                                <a href="{{ route('seller.categories.request') }}" class="small">Request a new category</a>
                            </div>
                            <select class="form-select @error('category_id') is-invalid @enderror" 
                                    id="category_id" name="category_id" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Only admin-approved categories are available here.</small>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="size" class="form-label">Size</label>
                                <input type="text" class="form-control @error('size') is-invalid @enderror" 
                                       id="size" name="size" value="{{ old('size') }}" 
                                       placeholder="e.g., S, M, L, XL, Free Size">
                                @error('size')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="material" class="form-label">Material</label>
                                <input type="text" class="form-control @error('material') is-invalid @enderror" 
                                       id="material" name="material" value="{{ old('material') }}" 
                                       placeholder="e.g., Cotton, Silk, Wool blend">
                                @error('material')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="color" class="form-label">Color</label>
                                <input type="text" class="form-control @error('color') is-invalid @enderror" 
                                       id="color" name="color" value="{{ old('color') }}" 
                                       placeholder="e.g., Red, Blue, Multi-color">
                                @error('color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="condition" class="form-label">Condition</label>
                                <select class="form-select @error('condition') is-invalid @enderror" 
                                        id="condition" name="condition">
                                    <option value="">Select Condition</option>
                                    <option value="New" {{ old('condition') == 'New' ? 'selected' : '' }}>New</option>
                                    <option value="Excellent" {{ old('condition') == 'Excellent' ? 'selected' : '' }}>Excellent</option>
                                    <option value="Good" {{ old('condition') == 'Good' ? 'selected' : '' }}>Good</option>
                                    <option value="Fair" {{ old('condition') == 'Fair' ? 'selected' : '' }}>Fair</option>
                                </select>
                                @error('condition')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                       id="location" name="location" value="{{ old('location') }}" 
                                       placeholder="e.g., Thimphu, Paro">
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="rental_price" class="form-label">Rental Price (Nu./day) *</label>
                                <input type="number" class="form-control @error('rental_price') is-invalid @enderror" 
                                       id="rental_price" name="rental_price" value="{{ old('rental_price') }}" 
                                       min="0" step="0.01" required>
                                @error('rental_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="quantity" class="form-label">Quantity *</label>
                                <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                       id="quantity" name="quantity" value="{{ old('quantity', 1) }}" 
                                       min="1" required>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="images" class="form-label">Product Images</label>
                            <input type="file" class="form-control @error('images.*') is-invalid @enderror" 
                                   id="images" name="images[]" accept="image/*" multiple>
                            <small class="text-muted">You can select multiple images. Max 2MB per image.</small>
                            @error('images.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> 
                            <strong>Note:</strong> Your product will be submitted for admin approval before it appears on the marketplace.
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Add Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
