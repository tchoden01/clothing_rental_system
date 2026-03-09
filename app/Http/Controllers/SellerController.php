<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\DamageReport;
use App\Models\PlatformSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SellerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Seller dashboard
    public function dashboard()
    {
        $seller = Auth::user()->seller;

        if (!$seller) {
            return redirect()->route('home')->with('error', 'You are not registered as a seller.');
        }

        $products = Product::where('seller_id', $seller->id)->count();
        $approvedProducts = Product::where('seller_id', $seller->id)->where('is_approved', true)->count();
        $pendingProducts = Product::where('seller_id', $seller->id)->where('is_approved', false')->count();
        
        $totalEarnings = OrderItem::where('seller_id', $seller->id)
            ->whereHas('order', function($q) {
                $q->where('payment_status', 'paid');
            })
            ->sum('seller_earnings');

        return view('seller.dashboard', compact('seller', 'products', 'approvedProducts', 'pendingProducts', 'totalEarnings'));
    }

    // List seller products
    public function products()
    {
        $seller = Auth::user()->seller;
        $products = Product::where('seller_id', $seller->id)
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('seller.products.index', compact('products'));
    }

    // Show add product form
    public function createProduct()
    {
        $categories = Category::all();
        return view('seller.products.create', compact('categories'));
    }

    // Store new product
    public function storeProduct(Request $request)
    {
        $seller = Auth::user()->seller;

        if (!$seller->is_verified) {
            return back()->with('error', 'Your account must be verified before adding products.');
        }

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'size' => 'nullable|string',
            'color' => 'nullable|string',
            'condition' => 'nullable|string',
            'location' => 'nullable|string',
            'description' => 'required|string',
            'rental_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $images[] = $path;
            }
        }

        $commissionRate = PlatformSetting::get('commission_rate', 15);

        Product::create([
            'seller_id' => $seller->id,
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'size' => $validated['size'],
            'color' => $validated['color'],
            'condition' => $validated['condition'],
            'location' => $validated['location'],
            'description' => $validated['description'],
            'rental_price' => $validated['rental_price'],
            'quantity' => $validated['quantity'],
            'images' => $images,
            'is_approved' => false,
            'status' => 'available',
        ]);

        $platformFee = $validated['rental_price'] * ($commissionRate / 100);
        $sellerEarnings = $validated['rental_price'] - $platformFee;

        return redirect()->route('seller.products')
            ->with('success', "Product added successfully! Platform fee: $platformFee, Your earnings: $sellerEarnings");
    }

    // Show edit product form
    public function editProduct($id)
    {
        $seller = Auth::user()->seller;
        $product = Product::where('seller_id', $seller->id)->findOrFail($id);
        $categories = Category::all();

        return view('seller.products.edit', compact('product', 'categories'));
    }

    // Update product
    public function updateProduct(Request $request, $id)
    {
        $seller = Auth::user()->seller;
        $product = Product::where('seller_id', $seller->id)->findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'size' => 'nullable|string',
            'color' => 'nullable|string',
            'condition' => 'nullable|string',
            'location' => 'nullable|string',
            'description' => 'required|string',
            'rental_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $images = $product->images ?? [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $images[] = $path;
            }
        }

        $product->update([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'size' => $validated['size'],
            'color' => $validated['color'],
            'condition' => $validated['condition'],
            'location' => $validated['location'],
            'description' => $validated['description'],
            'rental_price' => $validated['rental_price'],
            'quantity' => $validated['quantity'],
            'images' => $images,
        ]);

        return redirect()->route('seller.products')->with('success', 'Product updated successfully!');
    }

    // Delete product
    public function deleteProduct($id)
    {
        $seller = Auth::user()->seller;
        $product = Product::where('seller_id', $seller->id)->findOrFail($id);
        $product->delete();

        return back()->with('success', 'Product deleted successfully!');
    }

    // View seller orders
    public function orders()
    {
        $seller = Auth::user()->seller;
        $orderItems = OrderItem::with(['order.user', 'product'])
            ->where('seller_id', $seller->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('seller.orders', compact('orderItems'));
    }

    // Handle return and damage report
    public function returnForm($orderItemId)
    {
        $seller = Auth::user()->seller;
        $orderItem = OrderItem::with(['order', 'product'])
            ->where('seller_id', $seller->id)
            ->findOrFail($orderItemId);

        return view('seller.return', compact('orderItem'));
    }

    // Process return
    public function processReturn(Request $request, $orderItemId)
    {
        $seller = Auth::user()->seller;
        $orderItem = OrderItem::with('order')
            ->where('seller_id', $seller->id)
            ->findOrFail($orderItemId);

        $validated = $request->validate([
            'condition' => 'required|in:good,damaged',
            'damage_type' => 'required_if:condition,damaged|nullable|in:minor_tear,major_tear,stain,missing_accessory,other',
            'damage_description' => 'required_if:condition,damaged|nullable|string',
        ]);

        if ($validated['condition'] === 'damaged') {
            // Calculate damage fee based on damage type
            $damageFees = [
                'minor_tear' => 50,
                'major_tear' => 150,
                'stain' => 100,
                'missing_accessory' => 200,
                'other' => 100,
            ];

            $damageFee = $damageFees[$validated['damage_type']] ?? 100;

            DamageReport::create([
                'order_id' => $orderItem->order_id,
                'product_id' => $orderItem->product_id,
                'damage_type' => $validated['damage_type'],
                'damage_fee' => $damageFee,
                'description' => $validated['damage_description'],
                'reported_by' => Auth::id(),
                'status' => 'pending',
            ]);

            return redirect()->route('seller.orders')
                ->with('success', "Return processed. Damage fee: $damageFee will be charged to customer.");
        }

        return redirect()->route('seller.orders')->with('success', 'Return processed successfully!');
    }
}
