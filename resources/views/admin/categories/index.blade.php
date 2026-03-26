@extends('admin.layouts.app')

@section('title', 'Manage Categories - Admin')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Category Management</h2>
            <p class="text-muted">Manage product categories</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add New Category
            </a>
        </div>
    </div>

    @if($categories->count() > 0)
        @foreach($categories as $parentCategory)
            <div class="card mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">{{ $parentCategory->name }}</h5>
                        <small class="text-muted">{{ $parentCategory->description ?: 'Main category' }}</small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary">{{ $parentCategory->children->count() }} subcategories</span>
                        <a href="{{ route('admin.categories.edit', $parentCategory->id) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <a href="{{ route('admin.categories.create', ['parent_id' => $parentCategory->id]) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-diagram-2"></i> Add Subcategory
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($parentCategory->children->count() > 0)
                        <div class="row">
                            @foreach($parentCategory->children as $childCategory)
                                <div class="col-md-4 mb-3">
                                    <div class="border rounded p-3 h-100">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="mb-0">{{ $childCategory->name }}</h6>
                                            <span class="badge bg-success">Approved</span>
                                        </div>
                                        <p class="text-muted small mb-2">{{ $childCategory->description ?: 'Subcategory' }}</p>
                                        <p class="mb-2">
                                            <small class="text-muted">
                                                <i class="bi bi-box"></i> {{ $childCategory->products_count }} product(s)
                                            </small>
                                        </p>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.categories.edit', $childCategory->id) }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.categories.delete', $childCategory->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this category?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    {{ $childCategory->products_count > 0 ? 'disabled' : '' }}>
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="text-muted mb-0">No subcategories yet for this section.</p>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.categories.create', ['parent_id' => $parentCategory->id]) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-diagram-2"></i> Add Subcategory
                                </a>
                                <form action="{{ route('admin.categories.delete', $parentCategory->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this category?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                        {{ $parentCategory->products_count > 0 ? 'disabled' : '' }}>
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    @else
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> No categories found.
            <a href="{{ route('admin.categories.create') }}" class="alert-link">Create your first category</a>
        </div>
    @endif

    @if($pendingCategories->count() > 0)
        <div class="card mt-4">
            <div class="card-header bg-warning-subtle">
                <h5 class="mb-0">Pending Category Requests</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($pendingCategories as $category)
                        <div class="col-md-4 mb-3">
                            <div class="border rounded p-3 h-100">
                                <h6 class="mb-1">{{ $category->name }}</h6>
                                @if($category->parent)
                                    <p class="small text-muted mb-1">Parent: {{ $category->parent->name }}</p>
                                @endif
                                <p class="small text-muted mb-2">{{ $category->description ?: 'No description provided.' }}</p>
                                @if($category->seller)
                                    <p class="small text-muted mb-2">
                                        Requested by: {{ $category->seller->shop_name }}
                                    </p>
                                @endif
                                <div class="d-flex gap-2">
                                    <form action="{{ route('admin.categories.approve', $category->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="bi bi-check-circle"></i> Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.categories.reject', $category->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Reject this category request?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-x-circle"></i> Reject
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
