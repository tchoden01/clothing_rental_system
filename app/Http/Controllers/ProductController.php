<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    // Display all approved products
    public function index(Request $request)
    {
        $query = Product::with(['seller', 'category'])
            ->where('is_approved', true)
            ->where('status', 'available');

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        $products = $query->paginate(12);
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    // Show single product details
    public function show($id)
    {
        $product = Product::with(['seller.user', 'category'])
            ->where('is_approved', true)
            ->findOrFail($id);

        return view('products.show', compact('product'));
    }

    // Search products
    public function search(Request $request)
    {
        $search = $request->get('query');
        
        $products = Product::with(['seller', 'category'])
            ->where('is_approved', true)
            ->where('status', 'available')
            ->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            })
            ->paginate(12);

        $categories = Category::all();

        return view('products.index', compact('products', 'categories', 'search'));
    }
}
