<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Category;
use App\Models\PlatformSetting;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Admin dashboard
    public function dashboard()
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }

        $totalUsers = User::where('role', 'customer')->count();
        $totalSellers = Seller::count();
        $pendingSellers = Seller::where('is_verified', false)->count();
        $totalProducts = Product::count();
        $pendingProducts = Product::where('is_approved', false)->count();
        $totalOrders = Order::count();
        $completedOrders = Order::where('status', 'completed')->count();
        
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');
        $totalCommission = Order::where('payment_status', 'paid')->sum('platform_commission');

        return view('admin.dashboard', compact(
            'totalUsers', 'totalSellers', 'pendingSellers',
            'totalProducts', 'pendingProducts', 'totalOrders',
            'completedOrders', 'totalRevenue', 'totalCommission'
        ));
    }

    // List all sellers
    public function sellers()
    {
        $sellers = Seller::with('user')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.sellers.index', compact('sellers'));
    }

    // Verify seller
    public function verifySeller($id)
    {
        $seller = Seller::findOrFail($id);
        $seller->is_verified = true;
        $seller->save();

        return back()->with('success', 'Seller verified successfully!');
    }

    // Reject seller
    public function rejectSeller($id)
    {
        $seller = Seller::findOrFail($id);
        $seller->is_verified = false;
        $seller->save();

        return back()->with('success', 'Seller rejected!');
    }

    // List all products for approval
    public function products()
    {
        $products = Product::with(['seller.user', 'category'])
            ->orderBy('is_approved', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    // Approve product
    public function approveProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->is_approved = true;
        $product->save();

        return back()->with('success', 'Product approved successfully!');
    }

    // Reject product
    public function rejectProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->is_approved = false;
        $product->save();

        return back()->with('success', 'Product rejected!');
    }

    // List all orders
    public function orders()
    {
        $orders = Order::with(['user', 'orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    // View single order
    public function showOrder($id)
    {
        $order = Order::with(['user', 'orderItems.product.seller', 'payment'])
            ->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    // Update order status
    public function updateOrderStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,collected_from_seller,picked_up_by_customer,in_use,returned_by_customer,returned_to_seller,completed,cancelled',
        ]);

        $order = Order::findOrFail($id);
        $order->status = $validated['status'];
        $order->save();

        return back()->with('success', 'Order status updated successfully!');
    }

    // Manage categories
    public function categories()
    {
        $categories = Category::withCount('products')->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }

    // Create category
    public function createCategory()
    {
        return view('admin.categories.create');
    }

    // Store category
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
        ]);

        Category::create($validated);

        return redirect()->route('admin.categories')->with('success', 'Category created successfully!');
    }

    // Delete category
    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Cannot delete category with products!');
        }

        $category->delete();
        return back()->with('success', 'Category deleted successfully!');
    }

    // Platform settings
    public function settings()
    {
        $commissionRate = PlatformSetting::get('commission_rate', 15);
        return view('admin.settings', compact('commissionRate'));
    }

    // Update settings
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'commission_rate' => 'required|numeric|min:0|max:100',
        ]);

        PlatformSetting::set('commission_rate', $validated['commission_rate'], 'Platform commission rate in percentage');

        return back()->with('success', 'Settings updated successfully!');
    }
}
