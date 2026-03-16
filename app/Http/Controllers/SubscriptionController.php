<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::where('is_active', true)
            ->orderBy('price')
            ->get();
        
        $currentSubscription = null;
        if (Auth::check()) {
            $currentSubscription = UserSubscription::where('user_id', Auth::id())
                ->where('status', 'active')
                ->with('subscriptionPlan')
                ->first();
        }
        
        return view('subscriptions.index', compact('plans', 'currentSubscription'));
    }

    public function subscribe(Request $request, SubscriptionPlan $plan)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to subscribe');
        }

        // Check if user already has an active subscription
        $existingSubscription = UserSubscription::where('user_id', Auth::id())
            ->where('status', 'active')
            ->first();

        if ($existingSubscription) {
            return redirect()->back()->with('error', 'You already have an active subscription');
        }

        // Create new subscription
        $subscription = UserSubscription::create([
            'user_id' => Auth::id(),
            'subscription_plan_id' => $plan->id,
            'start_date' => now(),
            'next_billing_date' => now()->addDays($plan->swap_days),
            'status' => 'active',
            'is_first_month' => true,
        ]);

        return redirect()->route('subscriptions.index')
            ->with('success', 'Successfully subscribed to ' . $plan->name . '!');
    }

    public function cancel(UserSubscription $subscription)
    {
        if ($subscription->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        $subscription->update([
            'status' => 'cancelled',
            'end_date' => now(),
        ]);

        return redirect()->route('subscriptions.index')
            ->with('success', 'Subscription cancelled successfully');
    }
}
