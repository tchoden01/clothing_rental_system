<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Payment;
use App\Models\Product;
use App\Models\PlatformSetting;
use App\Models\Pickup;
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
                'total_amount' => $total,
                'platform_commission' => $commission,
                'delivery_method' => $validated['delivery_method'],
                'delivery_address' => $validated['delivery_address'] ?? Auth::user()->address,
                'rental_start_date' => $validated['rental_start_date'],
                'rental_end_date' => $validated['rental_end_date'],
                'status' => 'confirmed',
                'payment_status' => 'paid',
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
                'status' => 'paid',
                'transaction_id' => 'TXN-' . uniqid(),
            ]);

            // Confirmed paid orders should enter pickup lifecycle immediately.
            Pickup::ensureForOrder($order);

            // Clear cart
            Cart::where('user_id', Auth::id())->delete();

            DB::commit();

            return redirect()->route('orders.show', $order->id)->with('success', 'Order placed successfully!');

        } catch (\RuntimeException $e) {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            DB::rollback();
            report($e);
            return back()->with('error', 'Failed to place order. Please try again.');
        }
    }

    // Show user orders
    public function index()
    {
        $orders = Order::with(['orderItems.product', 'payment', 'pickups'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $orders->getCollection()->transform(function ($order) {
            $order->calculated_total = (float) $order->orderItems->sum(function ($item) {
                $unitPrice = (float) ($item->rental_price ?? optional($item->product)->rental_price ?? 0);
                $quantity = max((int) $item->quantity, 0);

                return max($unitPrice, 0) * $quantity;
            });

            $hasActualPaidPayment = $order->payment
                && (float) $order->payment->amount > 0
                && in_array($order->payment->status, ['paid', 'completed'], true);

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

        $refundPreviews = [];
        foreach ($orders as $order) {
            $refundPreviews[$order->id] = $this->calculateRefundDetails($order);
        }

        return view('orders.index', compact('orders', 'refundPreviews'));
    }

    // Show single order
    public function show($id)
    {
        $order = Order::with(['orderItems.product.seller', 'payment', 'pickups'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        $order->calculated_total = (float) $order->orderItems->sum(function ($item) {
            $unitPrice = (float) ($item->rental_price ?? optional($item->product)->rental_price ?? 0);
            $quantity = max((int) $item->quantity, 0);

            return max($unitPrice, 0) * $quantity;
        });

        $hasActualPaidPayment = $order->payment
            && (float) $order->payment->amount > 0
            && in_array($order->payment->status, ['paid', 'completed'], true);

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

        $refundPreview = $this->calculateRefundDetails($order);

        return view('orders.show', compact('order', 'refundPreview'));
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

        $refund = $this->calculateRefundDetails($order);
        if (!$refund['can_cancel']) {
            return back()->with('error', $refund['reason'] ?: 'Cancellation is not allowed after rental start date.');
        }

        DB::beginTransaction();

        try {
            $order->status = 'cancelled';
            $order->cancelled_at = now();
            $order->cancelled_by = Auth::id();
            $order->cancellation_hours_before_start = $refund['hours_before_start'];
            $order->refundable_base_amount = $refund['refundable_base_amount'];
            $order->refund_percentage = $refund['refund_percentage'];
            $order->refund_amount = $refund['refund_amount'];
            $order->platform_fee_amount = $refund['platform_fee_amount'];
            $order->platform_fee_refunded = $refund['platform_fee_refunded'];
            $order->refund_processed_at = $order->payment_status === 'paid' ? now() : null;

            if ($order->payment_status === 'paid' && (float) $refund['refund_amount'] > 0) {
                $order->payment_status = 'refunded';
            }

            $order->save();

            if ($order->payment) {
                if ($order->payment_status === 'refunded') {
                    $order->payment->status = 'refunded';
                }

                $order->payment->save();
            }

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

            return back()->with(
                'success',
                'Order cancelled successfully. Refund: Nu. ' . number_format((float) $refund['refund_amount'], 2)
            );
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return back()->with('error', 'Unable to cancel order right now. Please try again.');
        }
    }

    private function calculateRefundDetails(Order $order, ?Carbon $cancelAt = null, ?float $overridePercentage = null): array
    {
        $cancelAt = $cancelAt ?: now();
        $rentalStartAt = Carbon::parse($order->rental_start_date)->startOfDay();
        $hoursBeforeStart = (int) max($cancelAt->diffInHours($rentalStartAt, false), 0);
        $canCancel = $cancelAt->copy()->startOfDay()->lt($rentalStartAt);

        $refundPercentage = 0.0;
        $reason = null;

        if (!$canCancel) {
            $reason = 'Cancellation is not allowed on or after the rental start date.';
        } elseif ($overridePercentage !== null) {
            $refundPercentage = min(max($overridePercentage, 0), 100);
        } elseif ($hoursBeforeStart < 24) {
            $refundPercentage = 0.0;
        } elseif ($hoursBeforeStart < 48) {
            $refundPercentage = 50.0;
        } else {
            $refundPercentage = 100.0;
        }

        $platformFeeAmount = (float) $order->platform_commission;
        $refundPlatformFee = filter_var(PlatformSetting::get('refund_platform_fee', '0'), FILTER_VALIDATE_BOOLEAN);
        $refundableBaseAmount = (float) $order->total_price;

        if (!$refundPlatformFee) {
            $refundableBaseAmount = max($refundableBaseAmount - $platformFeeAmount, 0);
        }

        $refundAmount = $canCancel ? round(($refundableBaseAmount * $refundPercentage) / 100, 2) : 0.0;

        return [
            'can_cancel' => $canCancel,
            'reason' => $reason,
            'hours_before_start' => $hoursBeforeStart,
            'refund_percentage' => $refundPercentage,
            'refund_amount' => $refundAmount,
            'refundable_base_amount' => $refundableBaseAmount,
            'platform_fee_amount' => $platformFeeAmount,
            'platform_fee_refunded' => $refundPlatformFee,
        ];
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
