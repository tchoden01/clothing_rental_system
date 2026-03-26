@extends('seller.layouts.app')

@section('title', 'Edit Product')

@push('styles')
<style>
    .seller-edit-thumb {
        width: 100%;
        height: 130px;
        object-fit: contain;
        object-position: center center;
        background: #f4f1eb;
        padding: 0.45rem;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Product</h5>
                    <a href="{{ route('seller.products') }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Products
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('seller.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category *</label>
                            <select class="form-select @error('category_id') is-invalid @enderror" 
                                    id="category_id" name="category_id" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $parentCategory)
                                    @if($parentCategory->children->count() > 0)
                                        <optgroup label="{{ $parentCategory->name }}">
                                            @foreach($parentCategory->children as $childCategory)
                                                <option value="{{ $childCategory->id }}"
                                                    {{ (old('category_id', $product->category_id) == $childCategory->id) ? 'selected' : '' }}>
                                                    {{ $childCategory->name }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @else
                                        <option value="{{ $parentCategory->id }}"
                                            {{ (old('category_id', $product->category_id) == $parentCategory->id) ? 'selected' : '' }}>
                                            {{ $parentCategory->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $product->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="gender" class="form-label">For *</label>
                            <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                                <option value="">Select Audience</option>
                                <option value="men" {{ old('gender', $product->gender ?? $product->{'for'}) === 'men' ? 'selected' : '' }}>Men</option>
                                <option value="women" {{ old('gender', $product->gender ?? $product->{'for'}) === 'women' ? 'selected' : '' }}>Women</option>
                                <option value="kids" {{ old('gender', $product->gender ?? $product->{'for'}) === 'kids' ? 'selected' : '' }}>Kids</option>
                                <option value="unisex" {{ old('gender', $product->gender ?? $product->{'for'}) === 'unisex' ? 'selected' : '' }}>Unisex</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" id="kidTypeWrapper" style="display: none;">
                            <label for="kid_type" class="form-label">Kids Type *</label>
                            <select class="form-select @error('kid_type') is-invalid @enderror" id="kid_type" name="kid_type">
                                <option value="">Select Kids Type</option>
                                <option value="boys" {{ old('kid_type', $product->kid_type) === 'boys' ? 'selected' : '' }}>Boys</option>
                                <option value="girls" {{ old('kid_type', $product->kid_type) === 'girls' ? 'selected' : '' }}>Girls</option>
                            </select>
                            @error('kid_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="size" class="form-label">Size</label>
                                <input type="text" class="form-control @error('size') is-invalid @enderror" 
                                       id="size" name="size" value="{{ old('size', $product->size) }}" 
                                       placeholder="e.g., S, M, L, XL, Free Size">
                                @error('size')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="material" class="form-label">Material</label>
                                <input type="text" class="form-control @error('material') is-invalid @enderror" 
                                       id="material" name="material" value="{{ old('material', $product->material) }}" 
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
                                       id="color" name="color" value="{{ old('color', $product->color) }}" 
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
                                    <option value="New" {{ old('condition', $product->condition) == 'New' ? 'selected' : '' }}>New</option>
                                    <option value="Excellent" {{ old('condition', $product->condition) == 'Excellent' ? 'selected' : '' }}>Excellent</option>
                                    <option value="Good" {{ old('condition', $product->condition) == 'Good' ? 'selected' : '' }}>Good</option>
                                    <option value="Fair" {{ old('condition', $product->condition) == 'Fair' ? 'selected' : '' }}>Fair</option>
                                </select>
                                @error('condition')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                       id="location" name="location" value="{{ old('location', $product->location) }}" 
                                       placeholder="e.g., Thimphu, Paro">
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" required>{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="rental_price" class="form-label">Rental Price (Nu./day) *</label>
                                <input type="number" class="form-control @error('rental_price') is-invalid @enderror" 
                                       id="rental_price" name="rental_price" value="{{ old('rental_price', $product->rental_price) }}" 
                                       min="0" step="0.01" required>
                                @error('rental_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="quantity" class="form-label">Quantity *</label>
                                <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                       id="quantity" name="quantity" value="{{ old('quantity', $product->quantity) }}" 
                                       min="1" required>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        @if(count($product->image_urls) > 0)
                            <div class="mb-3">
                                <label class="form-label">Current Images</label>
                                <div class="row">
                                    @foreach($product->image_urls as $imageUrl)
                                        <div class="col-md-3 mb-2">
                                            <img src="{{ $imageUrl }}" 
                                                 class="img-thumbnail seller-edit-thumb" alt="Product image">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="images" class="form-label">Add More Images</label>
                            <input type="file" class="form-control @error('images.*') is-invalid @enderror" 
                                   id="images" name="images[]" accept="image/*" multiple>
                            <small class="text-muted">You can add more images. Max 2MB per image.</small>
                            @error('images.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Update Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const genderSelect = document.getElementById('gender');
        const kidTypeWrapper = document.getElementById('kidTypeWrapper');
        const kidTypeSelect = document.getElementById('kid_type');

        function toggleKidTypeField() {
            const isKids = genderSelect && genderSelect.value === 'kids';
            kidTypeWrapper.style.display = isKids ? 'block' : 'none';
            kidTypeSelect.required = isKids;
            if (!isKids) {
                kidTypeSelect.value = '';
            }
        }

        if (genderSelect) {
            genderSelect.addEventListener('change', toggleKidTypeField);
            toggleKidTypeField();
        }
    });
</script>
@endpush
