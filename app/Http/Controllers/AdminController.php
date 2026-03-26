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

        $commissionRate = (float) PlatformSetting::get('commission_rate', 20);
        $completedOrdersQuery = Order::where('status', 'completed');

        $totalRevenue = (float) (clone $completedOrdersQuery)
            ->selectRaw('COALESCE(SUM(COALESCE(total_amount, total_price)), 0) as aggregate_total')
            ->value('aggregate_total');
        $platformCommission = round($totalRevenue * ($commissionRate / 100), 2);
        $sellerPayout = round($totalRevenue - $platformCommission, 2);

        $activeRentals = Order::whereNotIn('status', ['completed', 'cancelled'])->count();
        $weeklyRevenue = (float) Order::where('status', 'completed')
            ->where('created_at', '>=', now()->subDays(7))
            ->selectRaw('COALESCE(SUM(COALESCE(total_amount, total_price)), 0) as aggregate_total')
            ->value('aggregate_total');

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
            'completedOrders', 'totalRevenue', 'platformCommission', 'sellerPayout', 'commissionRate',
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
    public function orders(Request $request)
    {
        $orders = Order::with(['user', 'orderItems.product', 'payment', 'pickups'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $orders->getCollection()->transform(function ($order) {
            $order->calculated_total = (float) $order->orderItems->sum(function ($item) {
                $unitPrice = (float) ($item->rental_price ?? optional($item->product)->rental_price ?? 0);
                $quantity = max((int) $item->quantity, 0);

                return max($unitPrice, 0) * $quantity;
            });

            $hasActualPaidPayment = $order->payment
                && (float) $order->payment->amount > 0
                && $order->payment->status === 'paid';

            if (($order->payment_status === 'refunded') || ($order->payment && $order->payment->status === 'refunded')) {
                $order->display_payment_status = 'refunded';
            } elseif ($hasActualPaidPayment) {
                $order->display_payment_status = 'paid';
            } else {
                $order->display_payment_status = 'pending';
            }

            $ongoingStatuses = [
                'collected_from_seller',
                'picked_up_by_customer',
                'in_use',
                'returned_by_customer',
                'returned_to_seller',
            ];

            if ($order->status === 'completed') {
                $order->display_order_status = 'completed';
            } elseif (in_array($order->status, $ongoingStatuses, true)) {
                $order->display_order_status = 'ongoing';
            } else {
                $order->display_order_status = 'confirmed';
            }

            $pickupRanking = [
                'pending' => 1,
                'ready' => 2,
                'picked_up' => 3,
                'in_use' => 4,
                'returned' => 5,
                'completed' => 6,
            ];

            $mostAdvancedPickupStatus = $order->pickups
                ->pluck('pickup_status')
                ->filter()
                ->sortByDesc(fn ($status) => $pickupRanking[$status] ?? 0)
                ->first();

            if (in_array($mostAdvancedPickupStatus, ['returned', 'completed'], true)) {
                $order->display_pickup_status = 'returned';
            } elseif (in_array($mostAdvancedPickupStatus, ['picked_up', 'in_use'], true)) {
                $order->display_pickup_status = 'picked_up';
            } elseif ($mostAdvancedPickupStatus === 'ready') {
                $order->display_pickup_status = 'ready';
            } else {
                $order->display_pickup_status = 'pending';
            }

            return $order;
        });

        return view('admin.orders.index', compact('orders'));
    }

    // View single order
    public function showOrder($id)
    {
        $order = Order::with(['user', 'orderItems.product.seller', 'payment', 'refundOverriddenBy'])
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

    public function overrideOrderRefund(Request $request, $id)
    {
        $validated = $request->validate([
            'refund_percentage' => 'required|numeric|min:0|max:100',
            'refund_override_note' => 'nullable|string|max:1000',
        ]);

        $order = Order::with('payment')->findOrFail($id);

        if ($order->status !== 'cancelled') {
            return back()->with('error', 'Refund override is allowed only for cancelled orders.');
        }

        $refund = $this->calculateRefundDetailsForOrder($order, (float) $validated['refund_percentage']);

        $order->is_refund_overridden = true;
        $order->refund_overridden_by = Auth::id();
        $order->refund_override_at = now();
        $order->refund_override_note = $validated['refund_override_note'] ?? null;
        $order->cancellation_hours_before_start = $refund['hours_before_start'];
        $order->refundable_base_amount = $refund['refundable_base_amount'];
        $order->refund_percentage = $refund['refund_percentage'];
        $order->refund_amount = $refund['refund_amount'];
        $order->platform_fee_amount = $refund['platform_fee_amount'];
        $order->platform_fee_refunded = $refund['platform_fee_refunded'];
        $order->refund_processed_at = $order->payment_status === 'paid' ? now() : $order->refund_processed_at;

        if ($order->payment_status === 'paid' && (float) $refund['refund_amount'] > 0) {
            $order->payment_status = 'refunded';
        }

        $order->save();

        if ($order->payment && $order->payment_status === 'refunded') {
            $order->payment->status = 'refunded';
            $order->payment->save();
        }

        return back()->with('success', 'Refund override updated. New refund: Nu. ' . number_format((float) $refund['refund_amount'], 2));
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

        $pickupsQuery = Pickup::with(['order', 'orderItem.product', 'seller.user', 'customer']);

        $filterOrderId = request()->integer('order_id');
        if ($filterOrderId) {
            $pickupsQuery->where('order_id', $filterOrderId);
        }

        $pickups = $pickupsQuery
            ->orderBy('pickup_date')
            ->orderByDesc('id')
            ->paginate(20);

        $statusLabels = Pickup::statusLabels();
        $statusBadgeClasses = Pickup::statusBadgeClasses();

        return view('admin.pickups.index', compact('pickups', 'statusLabels', 'statusBadgeClasses'));
    }

    // Optional admin override for pickup status
    public function forceUpdatePickupStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'pickup_status' => 'required|in:' . implode(',', Pickup::statuses()),
        ]);

        $pickup = Pickup::findOrFail($id);

        if (!$pickup->pickup_status) {
            return back()->with('error', 'Pickup status is missing and cannot be updated.');
        }

        $pickup->transitionTo($validated['pickup_status'], true);

        return back()->with('success', 'Pickup status force updated successfully.');
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
            ->with([
                'seller.user',
                'children' => function ($query) {
                    $query->withCount('products')
                        ->with('seller.user')
                        ->orderBy('name');
                },
            ])
            ->whereNull('parent_id')
            ->where('is_approved', true)
            ->orderBy('name')
            ->get();

        $pendingCategories = Category::with('seller.user', 'parent')
            ->where('is_approved', false)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.categories.index', compact('categories', 'pendingCategories'));
    }

    // Create category
    public function createCategory(Request $request)
    {
        $selectedParentId = $request->integer('parent_id') ?: null;

        $parentCategories = Category::whereNull('parent_id')
            ->where('is_approved', true)
            ->orderBy('name')
            ->get();

        return view('admin.categories.create', compact('parentCategories', 'selectedParentId'));
    }

    // Edit category
    public function editCategory($id)
    {
        $category = Category::findOrFail($id);

        $parentCategories = Category::whereNull('parent_id')
            ->where('is_approved', true)
            ->where('id', '!=', $category->id)
            ->orderBy('name')
            ->get();

        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    // Store category
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        Category::create([
            'name' => $validated['name'],
            'parent_id' => $validated['parent_id'] ?? null,
            'description' => $validated['description'] ?? null,
            'is_approved' => true,
            'seller_id' => null,
        ]);

        return redirect()->route('admin.categories')->with('success', 'Category created successfully!');
    }

    // Update category
    public function updateCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $parentId = $validated['parent_id'] ?? null;
        if ($parentId && (int) $parentId === (int) $category->id) {
            return back()->with('error', 'A category cannot be its own parent.');
        }

        $category->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'parent_id' => $parentId,
        ]);

        return redirect()->route('admin.categories')->with('success', 'Category updated successfully!');
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

        if ($category->children()->count() > 0) {
            return back()->with('error', 'Cannot delete a parent category that still has subcategories!');
        }
        
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
        $refundPlatformFee = filter_var(PlatformSetting::get('refund_platform_fee', '0'), FILTER_VALIDATE_BOOLEAN);

        return view('admin.settings', compact('commissionRate', 'refundPlatformFee'));
    }

    // Update settings
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'commission_rate' => 'required|numeric|min:0|max:100',
            'refund_platform_fee' => 'nullable|boolean',
        ]);

        PlatformSetting::set('commission_rate', $validated['commission_rate'], 'Platform commission rate in percentage');
        PlatformSetting::set(
            'refund_platform_fee',
            !empty($validated['refund_platform_fee']) ? '1' : '0',
            'Whether platform fee is refundable during order cancellation.'
        );

        return back()->with('success', 'Settings updated successfully!');
    }

    // Payments report
    public function paymentsReport()
    {
        $payments = Payment::with(['order.user'])
            ->latest()
            ->paginate(20);

        $commissionRate = (float) PlatformSetting::get('commission_rate', 20);

        $paidCompletedOrders = Order::where('status', 'completed')
            ->where('payment_status', 'paid');

        $totalAmount = (float) (clone $paidCompletedOrders)
            ->selectRaw('COALESCE(SUM(COALESCE(total_amount, total_price)), 0) as aggregate_total')
            ->value('aggregate_total');

        $platformCommission = round($totalAmount * ($commissionRate / 100), 2);
        $sellerPayout = round($totalAmount - $platformCommission, 2);

        $payoutSummary = [
            'pending' => (float) OrderItem::where('payout_status', 'pending')->sum('seller_earnings'),
            'released' => (float) OrderItem::where('payout_status', 'released')->sum('seller_earnings'),
        ];

        $payoutItems = OrderItem::with(['order.user', 'product', 'seller.user', 'payoutReleasedBy'])
            ->whereHas('order', function ($query) {
                $query->where('status', 'completed')->where('payment_status', 'paid');
            })
            ->orderByRaw("CASE WHEN payout_status = 'pending' THEN 0 ELSE 1 END")
            ->orderByDesc('created_at')
            ->paginate(20, ['*'], 'payout_page');

        $paymentSummary = [
            'completed' => (float) Payment::whereIn('status', ['paid', 'completed'])->sum('amount'),
            'pending' => (float) Payment::where('status', 'pending')->sum('amount'),
            'failed' => (float) Payment::where('status', 'failed')->sum('amount'),
            'refunded' => (float) Payment::where('status', 'refunded')->sum('amount'),
        ];

        return view('admin.payments.index', compact(
            'payments',
            'paymentSummary',
            'totalAmount',
            'platformCommission',
            'sellerPayout',
            'commissionRate',
            'payoutSummary',
            'payoutItems'
        ));
    }

    // Release payout to seller for completed paid order item.
    public function releaseSellerPayout($orderItemId)
    {
        $orderItem = OrderItem::with('order')->findOrFail($orderItemId);

        if (!$orderItem->order || $orderItem->order->status !== 'completed') {
            return back()->with('error', 'Payout can only be released for completed orders.');
        }

        if ($orderItem->order->payment_status !== 'paid') {
            return back()->with('error', 'Payout can only be released after customer payment is marked paid.');
        }

        if ($orderItem->payout_status === 'released') {
            return back()->with('info', 'This payout has already been released.');
        }

        $orderItem->payout_status = 'released';
        $orderItem->payout_released_at = now();
        $orderItem->payout_released_by = Auth::id();
        $orderItem->save();

        return back()->with('success', 'Seller payout released successfully.');
    }

    // Commission report
    public function commissionReports()
    {
        $commissionRate = (float) PlatformSetting::get('commission_rate', 20);

        $completedOrders = Order::with(['user', 'payment'])
            ->where('status', 'completed')
            ->latest()
            ->paginate(20);

        $totalRevenue = (float) Order::where('status', 'completed')
            ->selectRaw('COALESCE(SUM(COALESCE(total_amount, total_price)), 0) as aggregate_total')
            ->value('aggregate_total');
        $platformCommission = round($totalRevenue * ($commissionRate / 100), 2);
        $sellerPayout = round($totalRevenue - $platformCommission, 2);

        return view('admin.reports.commission', compact(
            'commissionRate',
            'completedOrders',
            'totalRevenue',
            'platformCommission',
            'sellerPayout'
        ));
    }

    private function calculateRefundDetailsForOrder(Order $order, ?float $overridePercentage = null): array
    {
        $hoursBeforeStart = (int) max(now()->diffInHours(Carbon::parse($order->rental_start_date)->startOfDay(), false), 0);

        $refundPercentage = $overridePercentage;
        if ($refundPercentage === null) {
            if ($hoursBeforeStart < 24) {
                $refundPercentage = 0.0;
            } elseif ($hoursBeforeStart < 48) {
                $refundPercentage = 50.0;
            } else {
                $refundPercentage = 100.0;
            }
        }

        $refundPercentage = min(max((float) $refundPercentage, 0), 100);

        $platformFeeAmount = (float) $order->platform_commission;
        $platformFeeRefunded = filter_var(PlatformSetting::get('refund_platform_fee', '0'), FILTER_VALIDATE_BOOLEAN);
        $refundableBaseAmount = (float) $order->total_price;

        if (!$platformFeeRefunded) {
            $refundableBaseAmount = max($refundableBaseAmount - $platformFeeAmount, 0);
        }

        $refundAmount = round(($refundableBaseAmount * $refundPercentage) / 100, 2);

        return [
            'hours_before_start' => $hoursBeforeStart,
            'refundable_base_amount' => $refundableBaseAmount,
            'refund_percentage' => $refundPercentage,
            'refund_amount' => $refundAmount,
            'platform_fee_amount' => $platformFeeAmount,
            'platform_fee_refunded' => $platformFeeRefunded,
        ];
    }
}
