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
        <div class="row">
            @foreach($categories as $category)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $category->name }}</h5>
                            <p class="card-text text-muted">{{ $category->description }}</p>
                            <p class="mb-2">
                                @if($category->is_approved)
                                    <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pending Approval</span>
                                @endif
                            </p>
                            @if($category->seller)
                                <p class="mb-2">
                                    <small class="text-muted">
                                        Requested by: {{ $category->seller->shop_name }}
                                        @if($category->seller->user)
                                            ({{ $category->seller->user->name }})
                                        @endif
                                    </small>
                                </p>
                            @endif
                            <p class="mb-0">
                                <small class="text-muted">
                                    <i class="bi bi-box"></i> {{ $category->products_count }} product(s)
                                </small>
                            </p>
                        </div>
                        <div class="card-footer bg-transparent">
                            @if(!$category->is_approved)
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
                            @else
                                <form action="{{ route('admin.categories.delete', $category->id) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this category?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                            {{ $category->products_count > 0 ? 'disabled' : '' }}>
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                                @if($category->products_count > 0)
                                    <small class="text-muted d-block mt-2">
                                        Cannot delete category with products
                                    </small>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row">
            <div class="col-12">
                {{ $categories->links() }}
            </div>
        </div>
    @else
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> No categories found. 
            <a href="{{ route('admin.categories.create') }}" class="alert-link">Create your first category</a>
        </div>
    @endif
</div>
@endsection
