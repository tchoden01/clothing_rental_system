<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\OnboardingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', [CustomerController::class, 'home'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');

// Subscription plans (public view)
Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');

// Onboarding flow
Route::get('/get-started', [OnboardingController::class, 'start'])->name('onboarding.start');
Route::get('/onboarding/create-account', [OnboardingController::class, 'createAccount'])->name('onboarding.create-account');
Route::post('/onboarding/create-account', [OnboardingController::class, 'storeAccount'])->name('onboarding.store-account');
Route::get('/onboarding/subscribe', [OnboardingController::class, 'selectSubscription'])->name('onboarding.subscribe')->middleware('auth');
Route::post('/onboarding/subscribe', [OnboardingController::class, 'storeSubscription'])->name('onboarding.store-subscription')->middleware('auth');
Route::get('/onboarding/profile', [OnboardingController::class, 'buildProfile'])->name('onboarding.profile')->middleware('auth');
Route::post('/onboarding/profile', [OnboardingController::class, 'storeProfile'])->name('onboarding.store-profile')->middleware('auth');
Route::get('/onboarding/complete', [OnboardingController::class, 'complete'])->name('onboarding.complete')->middleware('auth');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Customer registration
Route::get('/register', [AuthController::class, 'showCustomerRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'registerCustomer'])->name('register.post');

// Seller registration
Route::get('/register/seller', [AuthController::class, 'showSellerRegisterForm'])->name('register.seller');
Route::post('/register/seller', [AuthController::class, 'registerSeller'])->name('register.seller.post');

// Customer routes (authenticated)
Route::middleware(['auth'])->group(function () {
    // Profile
    Route::get('/profile', [CustomerController::class, 'profile'])->name('profile');
    Route::post('/profile', [CustomerController::class, 'updateProfile'])->name('profile.update');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');

    // Orders
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    
    // Subscriptions
    Route::post('/subscriptions/{plan}/subscribe', [SubscriptionController::class, 'subscribe'])->name('subscriptions.subscribe');
    Route::delete('/subscriptions/{subscription}/cancel', [SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
});

// Seller routes (authenticated + seller role)
Route::middleware(['auth'])->prefix('seller')->name('seller.')->group(function () {
    Route::get('/dashboard', [SellerController::class, 'dashboard'])->name('dashboard');
    
    // Products
    Route::get('/products', [SellerController::class, 'products'])->name('products');
    Route::get('/products/create', [SellerController::class, 'createProduct'])->name('products.create');
    Route::post('/products', [SellerController::class, 'storeProduct'])->name('products.store');
    Route::get('/products/{id}/edit', [SellerController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{id}', [SellerController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{id}', [SellerController::class, 'deleteProduct'])->name('products.delete');
    
    // Orders
    Route::get('/orders', [SellerController::class, 'orders'])->name('orders');
    Route::get('/orders/{id}/return', [SellerController::class, 'returnForm'])->name('orders.return');
    Route::post('/orders/{id}/return', [SellerController::class, 'processReturn'])->name('orders.return.process');
});

// Admin routes (authenticated + admin role)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Customers
    Route::get('/customers', [AdminController::class, 'customers'])->name('customers');
    Route::post('/customers/{id}/toggle-suspension', [AdminController::class, 'toggleCustomerSuspension'])->name('customers.toggle-suspension');
    Route::get('/customers/{id}/orders', [AdminController::class, 'customerOrders'])->name('customers.orders');
    
    // Sellers
    Route::get('/sellers', [AdminController::class, 'sellers'])->name('sellers');
    Route::post('/sellers/{id}/verify', [AdminController::class, 'verifySeller'])->name('sellers.verify');
    Route::post('/sellers/{id}/reject', [AdminController::class, 'rejectSeller'])->name('sellers.reject');
    
    // Products
    Route::get('/products', [AdminController::class, 'products'])->name('products');
    Route::post('/products/{id}/approve', [AdminController::class, 'approveProduct'])->name('products.approve');
    Route::post('/products/{id}/reject', [AdminController::class, 'rejectProduct'])->name('products.reject');
    
    // Orders
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::get('/orders/{id}', [AdminController::class, 'showOrder'])->name('orders.show');
    Route::post('/orders/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.status');
    
    // Categories
    Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
    Route::get('/categories/create', [AdminController::class, 'createCategory'])->name('categories.create');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
    Route::delete('/categories/{id}', [AdminController::class, 'deleteCategory'])->name('categories.delete');
    
    // Settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
});
