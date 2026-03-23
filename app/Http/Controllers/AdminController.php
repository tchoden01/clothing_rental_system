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
use App\Models\Pickup;
use App\Notifications\AdminDecisionNotification;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
        $pendingSellers = Seller::where('status', 'pending')->count();
        $totalProducts = Product::count();
        $pendingProducts = Product::where('status', 'pending')->count();
        $totalOrders = Order::count();
        $completedOrders = Order::where('status', 'completed')->count();
        
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');
        $totalCommission = Order::where('payment_status', 'paid')->sum('platform_commission');
        $activeRentals = Order::whereNotIn('status', ['completed', 'cancelled'])->count();
        $weeklyRevenue = Payment::where('status', 'completed')
            ->where('created_at', '>=', now()->subDays(7))
            ->sum('amount');

        $pendingSellerList = Seller::with('user')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $pendingProductList = Product::with(['seller.user', 'category'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $recentOrders = Order::with(['user', 'orderItems.product.seller'])
            ->latest()
            ->take(6)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalSellers', 'pendingSellers',
            'totalProducts', 'pendingProducts', 'totalOrders',
            'completedOrders', 'totalRevenue', 'totalCommission',
            'activeRentals', 'weeklyRevenue',
            'pendingSellerList', 'pendingProductList', 'recentOrders'
        ));
    }

    // List all sellers
    public function sellers()
    {
        $sellers = Seller::with('user')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.sellers.index', compact('sellers'));
    }

    // Show seller details
    public function showSeller($id)
    {
        $seller = Seller::with('user')->findOrFail($id);
        return view('admin.sellers.show', compact('seller'));
    }

    // List customers
    public function customers(Request $request)
    {
        $search = trim((string) $request->input('search', ''));

        $customers = User::where('role', 'customer')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('contact_number', 'like', '%' . $search . '%');
                });
            })
            ->withCount('orders')
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.customers.index', compact('customers', 'search'));
    }

    // Suspend or reactivate customer account
    public function toggleCustomerSuspension($id)
    {
        $customer = User::where('role', 'customer')->findOrFail($id);
        $customer->is_suspended = !$customer->is_suspended;
        $customer->save();

        $message = $customer->is_suspended
            ? 'Customer account suspended successfully.'
            : 'Customer account reactivated successfully.';

        return back()->with('success', $message);
    }

    // View customer order history
    public function customerOrders($id)
    {
        $customer = User::where('role', 'customer')->findOrFail($id);

        $orders = Order::with(['orderItems.product', 'payment'])
            ->where('user_id', $customer->id)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('admin.customers.orders', compact('customer', 'orders'));
    }

    // Verify seller
    public function verifySeller($id)
    {
        $seller = Seller::with('user')->findOrFail($id);

        if ($seller->status !== 'pending') {
            return back()->with('error', 'Only pending sellers can be approved.');
        }

        $seller->is_verified = true;
        $seller->status = 'verified';
        $seller->rejection_reason = null;
        $seller->save();

        if ($seller->user) {
            $this->notifySellerIfUnique($seller->user,
                'Seller account verified',
                'Your seller account has been approved. You can now list products and request categories.',
                route('seller.dashboard'),
                'Open seller dashboard'
            );
        }

        return back()->with('success', 'Seller verified successfully!');
    }

    // Reject seller
    public function rejectSeller(Request $request, $id)
    {
        $seller = Seller::with('user')->findOrFail($id);

        if ($seller->status !== 'pending') {
            return back()->with('error', 'Only pending sellers can be rejected.');
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $seller->is_verified = false;
        $seller->status = 'rejected';
        $seller->rejection_reason = $validated['reason'];
        $seller->save();

        if ($seller->user) {
            $this->notifySellerIfUnique($seller->user,
                'Seller account update',
                'Your seller application was not approved. Please review the rejection reason and resubmit your details.',
                route('seller.application.rejected'),
                'Review application status'
            );
        }

        return back()->with('success', 'Seller rejected!');
    }

    // List all products for approval
    public function products()
    {
        $products = Product::with(['seller.user', 'category'])
            ->where('status', '!=', 'rejected')
            ->orderBy('is_approved', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    // View product details for admin review
    public function showProduct($id)
    {
        $product = Product::with(['seller.user', 'category'])->findOrFail($id);

        return view('admin.products.show', compact('product'));
    }

    // Approve product
    public function approveProduct($id)
    {
        $product = Product::with('seller.user')->findOrFail($id);

        if ($product->status === 'rejected') {
            return back()->with('error', 'Rejected products cannot be approved again. Seller must upload a new product.');
        }

        $product->is_approved = true;
        $product->status = 'approved';
        $product->save();

        if ($product->seller && $product->seller->user) {
            $this->notifySellerIfUnique($product->seller->user,
                'Product approved',
                'Your product "' . $product->name . '" has been approved and is now visible to customers.',
                route('seller.products'),
                'View my products'
            );
        }

        return back()->with('success', 'Product approved successfully!');
    }

    // Reject product
    public function rejectProduct($id)
    {
        $product = Product::with('seller.user')->findOrFail($id);
        $product->is_approved = false;
        $product->status = 'rejected';
        $product->save();

        if ($product->seller && $product->seller->user) {
            $this->notifySellerIfUnique($product->seller->user,
                'Product rejected',
                'Your product "' . $product->name . '" was rejected. Please upload a new product listing if you want to submit it again.',
                route('seller.products.create'),
                'Upload new product'
            );
        }

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

        $commissionRate = (float) PlatformSetting::get('commission_rate', 15);

        $orderItemsBreakdown = $order->orderItems->map(function ($item) use ($order, $commissionRate) {
            $startDateRaw = $item->rental_start_date ?: $order->rental_start_date;
            $endDateRaw = $item->rental_end_date ?: $order->rental_end_date;

            $rentalDays = 1;
            if ($startDateRaw && $endDateRaw) {
                $rentalDays = max(Carbon::parse($startDateRaw)->diffInDays(Carbon::parse($endDateRaw)), 1);
            }

            $pricePerDay = (float) ($item->rental_price ?? optional($item->product)->rental_price ?? 0);
            $pricePerDay = max($pricePerDay, 0);

            $quantity = max((int) $item->quantity, 0);
            $itemSubtotal = $pricePerDay * $rentalDays * $quantity;
            $itemCommission = $itemSubtotal * ($commissionRate / 100);
            $itemSellerEarnings = $itemSubtotal - $itemCommission;

            $item->calc_rental_days = $rentalDays;
            $item->calc_start_date = $startDateRaw;
            $item->calc_end_date = $endDateRaw;
            $item->calc_price_per_day = $pricePerDay;
            $item->calc_subtotal = max($itemSubtotal, 0);
            $item->calc_platform_commission = max($itemCommission, 0);
            $item->calc_seller_earnings = max($itemSellerEarnings, 0);

            return $item;
        });

        $orderSubtotal = (float) $orderItemsBreakdown->sum('calc_subtotal');
        $orderPlatformCommission = (float) $orderItemsBreakdown->sum('calc_platform_commission');
        $orderSellerEarnings = (float) $orderItemsBreakdown->sum('calc_seller_earnings');
        $orderTotalPaid = $orderSubtotal;

        return view('admin.orders.show', compact(
            'order',
            'commissionRate',
            'orderItemsBreakdown',
            'orderSubtotal',
            'orderPlatformCommission',
            'orderSellerEarnings',
            'orderTotalPaid'
        ));
    }

    // Update order status
    public function updateOrderStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,collected_from_seller,picked_up_by_customer,in_use,returned_by_customer,returned_to_seller,completed,cancelled',
        ]);

        $order = Order::with('orderItems.product')->findOrFail($id);
        $order->status = $validated['status'];
        $order->save();

        // Create pickup records when the order enters logistics lifecycle.
        if (in_array($order->status, [
            'confirmed',
            'collected_from_seller',
            'picked_up_by_customer',
            'in_use',
            'returned_by_customer',
            'returned_to_seller',
            'completed',
        ], true)) {
            Pickup::ensureForOrder($order);
        }

        return back()->with('success', 'Order status updated successfully!');
    }

    // Pickup management list
    public function pickups()
    {
        $logisticsStatuses = [
            'confirmed',
            'collected_from_seller',
            'picked_up_by_customer',
            'in_use',
            'returned_by_customer',
            'returned_to_seller',
            'completed',
        ];

        // Backfill pickup records for confirmed/in-progress orders.
        Order::with('orderItems.product')
            ->whereIn('status', $logisticsStatuses)
            ->get()
            ->each(function ($order) {
                Pickup::ensureForOrder($order);
            });

        $pickups = Pickup::with(['order', 'orderItem.product', 'seller.user', 'customer'])
            ->orderBy('pickup_date')
            ->orderByDesc('id')
            ->paginate(20);

        $statusLabels = Pickup::statusLabels();

        return view('admin.pickups.index', compact('pickups', 'statusLabels'));
    }

    // Update pickup status
    public function updatePickupStatus(Request $request, $id)
    {
        $allowedStatuses = array_keys(Pickup::statusLabels());

        $validated = $request->validate([
            'pickup_status' => 'required|in:' . implode(',', $allowedStatuses),
        ]);

        $pickup = Pickup::findOrFail($id);
        $pickup->pickup_status = $validated['pickup_status'];
        $pickup->save();

        return back()->with('success', 'Pickup status updated successfully.');
    }

    // Notifications center for pending admin actions
    public function notifications()
    {
        $pendingSellerRequests = Seller::with('user')
            ->where('status', 'pending')
            ->latest()
            ->take(8)
            ->get();

        $pendingProducts = Product::with(['seller.user', 'category'])
            ->where('status', 'pending')
            ->latest()
            ->take(8)
            ->get();

        $pendingCategories = Category::with('seller.user')
            ->where('is_approved', false)
            ->latest()
            ->take(8)
            ->get();

        $pendingSummary = [
            'sellers' => $pendingSellerRequests->count(),
            'products' => $pendingProducts->count(),
            'categories' => $pendingCategories->count(),
        ];

        return view('admin.notifications.index', compact(
            'pendingSellerRequests',
            'pendingProducts',
            'pendingCategories',
            'pendingSummary'
        ));
    }

    // Manage categories
    public function categories()
    {
        $categories = Category::withCount('products')
            ->with('seller.user')
            ->orderBy('is_approved', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
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

        Category::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_approved' => true,
            'seller_id' => null,
        ]);

        return redirect()->route('admin.categories')->with('success', 'Category created successfully!');
    }

    // Approve seller-submitted category
    public function approveCategory($id)
    {
        $category = Category::with('seller.user')->findOrFail($id);

        if ($category->is_approved) {
            return back()->with('error', 'This category is already approved.');
        }

        $category->is_approved = true;
        $category->save();

        if ($category->seller && $category->seller->user) {
            $this->notifySellerIfUnique($category->seller->user,
                'Category request approved',
                'Your category request "' . $category->name . '" has been approved and can now be used for products.',
                route('seller.products.create'),
                'Add product'
            );
        }

        return back()->with('success', 'Category approved successfully!');
    }

    // Reject seller-submitted category
    public function rejectCategory($id)
    {
        $category = Category::with('seller.user')->findOrFail($id);

        if ($category->is_approved) {
            return back()->with('error', 'Only pending category requests can be rejected.');
        }

        if ($category->seller && $category->seller->user) {
            $this->notifySellerIfUnique($category->seller->user,
                'Category request not approved',
                'Your category request "' . $category->name . '" was not approved. You can submit a revised request.',
                route('seller.categories.request'),
                'Request category again'
            );
        }

        $category->delete();

        return back()->with('success', 'Category request rejected.');
    }

    private function notifySellerIfUnique(User $user, string $title, string $message, string $actionUrl, string $actionLabel): void
    {
        $recentSimilarUnread = $user->unreadNotifications()
            ->where('type', AdminDecisionNotification::class)
            ->where('created_at', '>=', now()->subMinutes(10))
            ->get()
            ->contains(function ($notification) use ($title, $message, $actionUrl, $actionLabel) {
                return ($notification->data['title'] ?? null) === $title
                    && ($notification->data['message'] ?? null) === $message
                    && ($notification->data['action_url'] ?? null) === $actionUrl
                    && ($notification->data['action_label'] ?? null) === $actionLabel;
            });

        if ($recentSimilarUnread) {
            return;
        }

        $user->notify(new AdminDecisionNotification($title, $message, $actionUrl, $actionLabel));
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
