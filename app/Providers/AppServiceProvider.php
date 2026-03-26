<?php

namespace App\Providers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $cartBadgeCount = 0;
            $adminPendingNotificationsCount = 0;
            $sellerUnreadNotificationsCount = 0;
            $pendingSellersBadgeCount = 0;
            $pendingItemApprovalsBadgeCount = 0;

            if (Auth::check()) {
                $user = Auth::user();

                if ($user->isCustomer()) {
                    $cartBadgeCount = Cart::where('user_id', $user->id)->sum('quantity');
                } elseif ($user->isAdmin()) {
                    $pendingSellersBadgeCount = Seller::where('status', 'pending')->count();
                    $pendingItemApprovalsBadgeCount = Product::where('status', 'pending')->count();
                    $adminPendingNotificationsCount =
                        $pendingSellersBadgeCount +
                        $pendingItemApprovalsBadgeCount +
                        Category::where('is_approved', false)->count();
                } elseif ($user->isSeller()) {
                    $sellerUnreadNotificationsCount = $user->unreadNotifications()->count();
                } else {
                    $cartBadgeCount = Cart::whereHas('user', function ($query) {
                        $query->where('role', 'customer');
                    })->sum('quantity');
                }
            }

            $view->with('cartBadgeCount', $cartBadgeCount);
            $view->with('adminPendingNotificationsCount', $adminPendingNotificationsCount);
            $view->with('sellerUnreadNotificationsCount', $sellerUnreadNotificationsCount);
            $view->with('pendingSellersBadgeCount', $pendingSellersBadgeCount);
            $view->with('pendingItemApprovalsBadgeCount', $pendingItemApprovalsBadgeCount);
        });
    }
}
