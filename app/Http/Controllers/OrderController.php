<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Payment;
use App\Models\PlatformSetting;
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
            // Get commission rate
            $commissionRate = PlatformSetting::get('commission_rate', 15) / 100;

            // Calculate total
            $total = $cartItems->sum(function($item) {
                return $item->product->rental_price * $item->quantity;
            });

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
                $itemTotal = $cartItem->product->rental_price * $cartItem->quantity;
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
        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        if ($order->status !== 'pending' && $order->status !== 'confirmed') {
            return back()->with('error', 'Cannot cancel this order.');
        }

        $order->status = 'cancelled';
        $order->save();

        return back()->with('success', 'Order cancelled successfully!');
    }
}
