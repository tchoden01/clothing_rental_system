<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Payment;
use App\Models\Product;
use App\Models\PlatformSetting;
use App\Notifications\AdminDecisionNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Show checkout page
    public function checkout()
    {
        $cartItems = Cart::with('product.seller')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        $total = $cartItems->sum(function($item) {
            return $item->product->rental_price * $item->quantity;
        });

        return view('orders.checkout', compact('cartItems', 'total'));
    }

    // Place order
    public function store(Request $request)
    {
        $validated = $request->validate([
            'delivery_method' => 'required|in:pickup,home_delivery',
            'delivery_address' => 'required_if:delivery_method,home_delivery|string',
            'rental_start_date' => 'required|date|after_or_equal:today',
            'rental_end_date' => 'required|date|after:rental_start_date',
            'payment_method' => 'required|in:digital,cash_on_delivery',
        ]);

        $cartItems = Cart::with('product.seller')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        DB::beginTransaction();

        try {
            $rentalDays = Carbon::parse($validated['rental_start_date'])
                ->diffInDays(Carbon::parse($validated['rental_end_date']));

            if ($rentalDays < 1) {
                $rentalDays = 1;
            }

            // Get commission rate
            $commissionRate = PlatformSetting::get('commission_rate', 15) / 100;

            // Calculate total
            $total = $cartItems->sum(function($item) {
                return $item->product->rental_price * $item->quantity;
            }) * $rentalDays;

            $commission = $total * $commissionRate;

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => Order::generateOrderNumber(),
                'total_price' => $total,
                'platform_commission' => $commission,
                'delivery_method' => $validated['delivery_method'],
                'delivery_address' => $validated['delivery_address'] ?? Auth::user()->address,
                'rental_start_date' => $validated['rental_start_date'],
                'rental_end_date' => $validated['rental_end_date'],
                'status' => 'pending',
                'payment_status' => 'pending',
            ]);

            // Create order items
            foreach ($cartItems as $cartItem) {
                if (!$cartItem->product->is_approved || !in_array($cartItem->product->status, ['approved', 'available'], true)) {
                    throw new \RuntimeException('One or more products are not available for rental.');
                }

                if ($cartItem->product->quantity < $cartItem->quantity) {
                    throw new \RuntimeException('One or more products do not have enough quantity.');
                }

                $itemTotal = $cartItem->product->rental_price * $cartItem->quantity * $rentalDays;
                $itemCommission = $itemTotal * $commissionRate;
                $sellerEarnings = $itemTotal - $itemCommission;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'seller_id' => $cartItem->product->seller_id,
                    'quantity' => $cartItem->quantity,
                    'rental_price' => $cartItem->product->rental_price,
                    'seller_earnings' => $sellerEarnings,
                    'rental_start_date' => $validated['rental_start_date'],
                    'rental_end_date' => $validated['rental_end_date'],
                ]);

                $remainingQuantity = $cartItem->product->quantity - $cartItem->quantity;
                $cartItem->product->quantity = max($remainingQuantity, 0);
                $cartItem->product->status = $cartItem->product->quantity > 0 ? 'available' : 'rented';
                $cartItem->product->save();
            }

            // Create payment record
            $payment = Payment::create([
                'order_id' => $order->id,
                'payment_method' => $validated['payment_method'],
                'amount' => $total,
                'status' => $validated['payment_method'] === 'digital' ? 'completed' : 'pending',
                'transaction_id' => $validated['payment_method'] === 'digital' ? 'TXN-' . uniqid() : null,
            ]);

            // Update order payment status if digital payment
            if ($validated['payment_method'] === 'digital') {
                $order->payment_status = 'paid';
                $order->status = 'confirmed';
                $order->save();
            }

            // Clear cart
            Cart::where('user_id', Auth::id())->delete();

            DB::commit();

            return redirect()->route('orders.show', $order->id)->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to place order. Please try again.');
        }
    }

    // Show user orders
    public function index()
    {
        $orders = Order::with(['orderItems.product', 'payment'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    // Show single order
    public function show($id)
    {
        $order = Order::with(['orderItems.product.seller', 'payment'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('orders.show', compact('order'));
    }

    // Cancel order
    public function cancel($id)
    {
        $order = Order::with(['payment', 'orderItems.product.seller.user'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        if ($order->status !== 'pending' && $order->status !== 'confirmed') {
            return back()->with('error', 'Cannot cancel this order.');
        }

        if (!Carbon::today()->lt(Carbon::parse($order->rental_start_date))) {
            return back()->with('error', 'This order can only be cancelled before the rental period starts.');
        }

        DB::beginTransaction();

        try {
            $order->status = 'cancelled';

            if ($order->payment_status === 'paid') {
                $order->payment_status = 'refunded';

                if ($order->payment) {
                    $order->payment->status = 'refunded';
                    $order->payment->save();
                }
            }

            $order->save();

            foreach ($order->orderItems as $item) {
                if ($item->product) {
                    $item->product->quantity += $item->quantity;
                    $item->product->status = 'available';
                    $item->product->save();
                }
            }

            // Optionally notify sellers that order was cancelled and items are available again.
            $sellerUsers = $order->orderItems
                ->map(function ($item) {
                    return optional(optional($item->product)->seller)->user;
                })
                ->filter()
                ->unique('id');

            foreach ($sellerUsers as $sellerUser) {
                $this->notifySellerIfUnique($sellerUser,
                    'Order cancelled by customer',
                    'An upcoming order was cancelled. Your item is available for rental again.',
                    route('seller.orders'),
                    'View seller orders'
                );
            }

            DB::commit();

            return back()->with('success', 'Order cancelled successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Unable to cancel order right now. Please try again.');
        }
    }

    private function notifySellerIfUnique($user, string $title, string $message, string $actionUrl, string $actionLabel): void
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
}
