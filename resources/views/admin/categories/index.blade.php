@extends('layouts.app')

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
                            <p class="mb-0">
                                <small class="text-muted">
                                    <i class="bi bi-box"></i> {{ $category->products_count }} product(s)
                                </small>
                            </p>
                        </div>
                        <div class="card-footer bg-transparent">
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
