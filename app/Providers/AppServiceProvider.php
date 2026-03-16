<?php

namespace App\Providers;

use App\Models\Cart;
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

            if (Auth::check()) {
                $user = Auth::user();

                if ($user->isCustomer()) {
                    $cartBadgeCount = Cart::where('user_id', $user->id)->sum('quantity');
                } else {
                    $cartBadgeCount = Cart::whereHas('user', function ($query) {
                        $query->where('role', 'customer');
                    })->sum('quantity');
                }
            }

            $view->with('cartBadgeCount', $cartBadgeCount);
        });
    }
}
