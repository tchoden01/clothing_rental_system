<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    // Home page
    public function home()
    {
        $products = Product::with(['seller', 'category'])
            ->where('is_approved', true)
            ->whereIn('status', ['approved', 'available'])
            ->latest()
            ->take(12)
            ->get();

        $categories = Category::where('is_approved', true)->orderBy('name')->get();

        return view('home', compact('products', 'categories'));
    }

    // Customer profile
    public function profile()
    {
        return view('customer.profile');
    }

    // Update profile
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_number' => 'required|string',
            'address' => 'required|string',
        ]);

        $user = Auth::user();
        $user->update($validated);

        return back()->with('success', 'Profile updated successfully!');
    }
}
