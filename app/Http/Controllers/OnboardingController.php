<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class OnboardingController extends Controller
{
    public function start()
    {
        return view('onboarding.welcome');
    }

    public function createAccount()
    {
        return view('onboarding.create-account');
    }

    public function storeAccount(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'contact_number' => 'required|string|max:20',
            'address' => 'required|string|max:500',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'contact_number' => $validated['contact_number'],
            'address' => $validated['address'],
            'role' => 'customer',
        ]);

        Auth::login($user);

        return redirect()->route('onboarding.subscribe');
    }

    public function selectSubscription()
    {
        $plans = SubscriptionPlan::where('is_active', true)
            ->orderBy('price')
            ->get();
        
        return view('onboarding.subscribe', compact('plans'));
    }

    public function storeSubscription(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
        ]);

        $plan = SubscriptionPlan::findOrFail($request->plan_id);

        UserSubscription::create([
            'user_id' => Auth::id(),
            'subscription_plan_id' => $plan->id,
            'start_date' => now(),
            'next_billing_date' => now()->addDays($plan->swap_days),
            'status' => 'active',
            'is_first_month' => true,
        ]);

        return redirect()->route('onboarding.profile');
    }

    public function buildProfile()
    {
        return view('onboarding.profile');
    }

    public function storeProfile(Request $request)
    {
        $validated = $request->validate([
            'size_preference' => 'nullable|string|max:50',
            'style_preference' => 'nullable|string|max:255',
        ]);

        // You can add additional profile fields to users table if needed
        // For now, we'll just complete the onboarding
        
        return redirect()->route('onboarding.complete');
    }

    public function complete()
    {
        return view('onboarding.complete');
    }
}
