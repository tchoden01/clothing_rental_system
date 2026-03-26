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
    public function dashboard(Request $request)
    {
        $seller = Auth::user()->seller;

        if (!$seller) {
            return redirect()->route('home')->with('error', 'You are not registered as a seller.');
        }

        if ($seller->status === 'rejected') {
            return redirect()->route('seller.application.rejected');
        }

        $products = Product::where('seller_id', $seller->id)->count();
        $approvedProducts = Product::where('seller_id', $seller->id)
            ->where('is_approved', true)
            ->whereIn('status', ['approved', 'available'])
            ->count();
        $pendingProducts = Product::where('seller_id', $seller->id)
            ->where('status', 'pending')
            ->count();
        
        $totalEarnings = OrderItem::where('seller_id', $seller->id)
            ->whereHas('order', function($q) {
                $q->where('payment_status', 'paid');
            })
            ->sum('seller_earnings');
        
        // Items rented out count
        $itemsRentedOut = OrderItem::where('seller_id', $seller->id)
            ->whereHas('order', function($q) {
                $q->where('status', '!=', 'cancelled');
            })
            ->count();
        
        // Pending orders
        $pendingOrdersCount = OrderItem::where('seller_id', $seller->id)
            ->whereHas('order', function($q) {
                $q->where('status', 'pending');
            })
            ->count();
        
        // Returns handling
        $goodReturns = OrderItem::where('seller_id', $seller->id)
            ->where('return_status', 'returned')
            ->whereDoesntHave('damageReport')
            ->count();
            
        $damagedReturns = OrderItem::where('seller_id', $seller->id)
            ->whereHas('damageReport')
            ->count();
        
        // Pending returns
        $pendingReturns = OrderItem::where('seller_id', $seller->id)
            ->where('return_status', 'pending')
            ->with(['product', 'order'])
            ->latest()
            ->take(5)
            ->get();
        
        // Platform commission
        $commission = PlatformSetting::where('key', 'commission_rate')->first();
        $commissionRate = $commission ? $commission->value : 15;
        
        // Monthly earnings
        $currentMonthEarnings = OrderItem::where('seller_id', $seller->id)
            ->whereHas('order', function($q) {
                $q->where('payment_status', 'paid')
                  ->whereYear('created_at', date('Y'))
                  ->whereMonth('created_at', date('m'));
            })
            ->sum('seller_earnings');
        
        // Weekly earnings increase
        $weeklyEarnings = OrderItem::where('seller_id', $seller->id)
            ->whereHas('order', function($q) {
                $q->where('payment_status', 'paid')
                  ->whereBetween('created_at', [now()->subWeek(), now()]);
            })
            ->sum('seller_earnings');

        $focusSection = $request->query('section');

        return view('seller.dashboard', compact(
            'seller', 
            'products', 
            'approvedProducts', 
            'pendingProducts', 
            'totalEarnings',
            'itemsRentedOut',
            'pendingOrdersCount',
            'goodReturns',
            'damagedReturns',
            'pendingReturns',
            'commissionRate',
            'currentMonthEarnings',
            'weeklyEarnings',
            'focusSection'
        ));
    }

    public function rejectedApplication()
    {
        $seller = Auth::user()->seller;

        if (!$seller) {
            return redirect()->route('home')->with('error', 'You are not registered as a seller.');
        }

        if ($seller->status !== 'rejected') {
            return redirect()->route('seller.dashboard');
        }

        return view('seller.application.rejected', compact('seller'));
    }

    public function editApplication()
    {
        $seller = Auth::user()->seller;

        if (!$seller) {
            return redirect()->route('home')->with('error', 'You are not registered as a seller.');
        }

        if ($seller->status !== 'rejected') {
            return redirect()->route('seller.dashboard')->with('info', 'Your application is not currently rejected.');
        }

        return view('seller.application.edit', compact('seller'));
    }

    public function resubmitApplication(Request $request)
    {
        $user = Auth::user();
        $seller = $user->seller;

        if (!$seller) {
            return redirect()->route('home')->with('error', 'You are not registered as a seller.');
        }

        if ($seller->status !== 'rejected') {
            return redirect()->route('seller.dashboard')->with('info', 'Your application is not currently rejected.');
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id . '|unique:sellers,email,' . $seller->id,
            'shop_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'cid_number' => 'required|string|max:100|unique:sellers,cid_number,' . $seller->id,
            'business_license' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:100',
        ]);

        if (!$seller->business_license && !$request->hasFile('business_license')) {
            return back()->withErrors([
                'business_license' => 'Business license is required.',
            ])->withInput();
        }

        $businessLicensePath = $seller->business_license;
        if ($request->hasFile('business_license')) {
            $businessLicensePath = $request->file('business_license')->store('seller-licenses', 'public');
        }

        $user->update([
            'name' => $validated['full_name'],
            'email' => $validated['email'],
            'address' => $validated['location'],
        ]);

        $seller->update([
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'shop_name' => $validated['shop_name'],
            'location' => $validated['location'],
            'cid_number' => $validated['cid_number'],
            'business_license' => $businessLicensePath,
            'bank_name' => $validated['bank_name'],
            'account_number' => $validated['account_number'],
            'address' => $validated['location'],
            'is_verified' => false,
            'status' => 'pending',
            'rejection_reason' => null,
        ]);

        return redirect()->route('seller.dashboard')
            ->with('success', 'Application resubmitted successfully. Your status is now pending admin review.');
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
        $categories = Category::where('is_approved', true)
            ->whereNull('parent_id')
            ->with(['children' => function ($query) {
                $query->where('is_approved', true)->orderBy('name');
            }])
            ->orderBy('name')
            ->get();

        return view('seller.products.create', compact('categories'));
    }

    // Store new product
    public function storeProduct(Request $request)
    {
        $seller = Auth::user()->seller;

        if ($seller->status !== 'verified') {
            return back()->with('error', 'Your account must be verified before adding products.');
        }

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id,is_approved,1',
            'gender' => 'required|in:men,women,kids,unisex',
            'kid_type' => 'nullable|in:boys,girls|required_if:gender,kids',
            'name' => 'required|string|max:255',
            'size' => 'nullable|string',
            'material' => 'nullable|string|max:255',
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
            'gender' => $validated['gender'],
            'kid_type' => $validated['gender'] === 'kids' ? ($validated['kid_type'] ?? null) : null,
            'name' => $validated['name'],
            'size' => $validated['size'],
            'material' => $validated['material'] ?? null,
            'color' => $validated['color'],
            'condition' => $validated['condition'],
            'location' => $validated['location'],
            'description' => $validated['description'],
            'rental_price' => $validated['rental_price'],
            'quantity' => $validated['quantity'],
            'images' => $images,
            'is_approved' => false,
            'status' => 'pending',
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
        $categories = Category::where('is_approved', true)
            ->whereNull('parent_id')
            ->with(['children' => function ($query) {
                $query->where('is_approved', true)->orderBy('name');
            }])
            ->orderBy('name')
            ->get();

        return view('seller.products.edit', compact('product', 'categories'));
    }

    // Update product
    public function updateProduct(Request $request, $id)
    {
        $seller = Auth::user()->seller;
        $product = Product::where('seller_id', $seller->id)->findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id,is_approved,1',
            'gender' => 'required|in:men,women,kids,unisex',
            'kid_type' => 'nullable|in:boys,girls|required_if:gender,kids',
            'name' => 'required|string|max:255',
            'size' => 'nullable|string',
            'material' => 'nullable|string|max:255',
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
            'gender' => $validated['gender'],
            'kid_type' => $validated['gender'] === 'kids' ? ($validated['kid_type'] ?? null) : null,
            'name' => $validated['name'],
            'size' => $validated['size'],
            'material' => $validated['material'] ?? null,
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
        $orderItem = OrderItem::with(['order', 'product'])
            ->where('seller_id', $seller->id)
            ->findOrFail($orderItemId);

        $validated = $request->validate([
            'condition' => 'required|in:good,damaged',
            'damage_type' => 'required_if:condition,damaged|nullable|in:minor_tear,major_tear,stain,missing_accessory,other',
            'damage_description' => 'required_if:condition,damaged|nullable|string',
        ]);

        $orderItem->update([
            'return_status' => 'returned',
        ]);

        if ($orderItem->product) {
            $orderItem->product->quantity += $orderItem->quantity;
            $orderItem->product->status = 'available';
            $orderItem->product->save();
        }

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

    // Show request category form
    public function requestCategory()
    {
        $seller = Auth::user()->seller;

        if (!$seller) {
            return redirect()->route('home')->with('error', 'You are not registered as a seller.');
        }

        $pendingRequests = Category::where('seller_id', $seller->id)
            ->where('is_approved', false)
            ->latest()
            ->get();

        return view('seller.categories.request', compact('pendingRequests'));
    }

    // Store category request for admin approval
    public function storeCategoryRequest(Request $request)
    {
        $seller = Auth::user()->seller;

        if (!$seller) {
            return redirect()->route('home')->with('error', 'You are not registered as a seller.');
        }

        if ($seller->status !== 'verified') {
            return back()->with('error', 'Your account must be verified before requesting categories.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        Category::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'seller_id' => $seller->id,
            'is_approved' => false,
        ]);

        return redirect()->route('seller.categories.request')
            ->with('success', 'Category request submitted. Admin approval is required before it can be used.');
    }

    // Seller notifications list
    public function notifications()
    {
        $user = Auth::user();

        if (!$user->isSeller()) {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }

        $notifications = $user->notifications()->paginate(20);

        return view('seller.notifications.index', compact('notifications'));
    }

    // Open one notification and mark it as read
    public function openNotification(string $id)
    {
        $user = Auth::user();

        if (!$user->isSeller()) {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }

        $notification = $user->notifications()->where('id', $id)->firstOrFail();

        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        $actionUrl = $notification->data['action_url'] ?? route('seller.notifications');

        return redirect()->to($actionUrl);
    }

    // Mark all seller notifications as read
    public function markNotificationsRead()
    {
        $user = Auth::user();

        if (!$user->isSeller()) {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }

        $user->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read.');
    }
}
