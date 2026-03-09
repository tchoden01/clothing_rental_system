<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Display cart items
    public function index()
    {
        $cartItems = Cart::with('product.seller')
            ->where('user_id', Auth::id())
            ->get();

        $total = $cartItems->sum(function($item) {
            return $item->product->rental_price * $item->quantity;
        });

        return view('cart.index', compact('cartItems', 'total'));
    }

    // Add product to cart
    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'rental_start_date' => 'nullable|date',
            'rental_end_date' => 'nullable|date|after:rental_start_date',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        // Check if product is available
        if ($product->quantity < $validated['quantity']) {
            return back()->with('error', 'Insufficient quantity available.');
        }

        // Check if item already in cart
        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $validated['quantity'];
            $cartItem->save();
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
                'rental_start_date' => $validated['rental_start_date'] ?? null,
                'rental_end_date' => $validated['rental_end_date'] ?? null,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Product added to cart!');
    }

    // Update cart item quantity
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = Cart::where('user_id', Auth::id())->findOrFail($id);
        
        if ($cartItem->product->quantity < $validated['quantity']) {
            return back()->with('error', 'Insufficient quantity available.');
        }

        $cartItem->quantity = $validated['quantity'];
        $cartItem->save();

        return back()->with('success', 'Cart updated!');
    }

    // Remove item from cart
    public function remove($id)
    {
        $cartItem = Cart::where('user_id', Auth::id())->findOrFail($id);
        $cartItem->delete();

        return back()->with('success', 'Item removed from cart!');
    }

    // Clear all cart items
    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();

        return back()->with('success', 'Cart cleared!');
    }
}
