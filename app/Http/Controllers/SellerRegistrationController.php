<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SellerRegistrationController extends Controller
{
    public function create()
    {
        return view('auth.register_seller');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email|unique:sellers,email',
            'password' => 'required|string|min:8',

            'shop_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',

            'cid_number' => 'required|string|max:100|unique:sellers,cid_number',
            'business_license' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',

            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:100',
        ]);

        $user = User::create([
            'name' => $validated['full_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'seller',
            'contact_number' => '',
            'address' => $validated['location'],
        ]);

        $businessLicensePath = $request->file('business_license')->store('seller-licenses', 'public');

        Seller::create([
            'user_id' => $user->id,
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'phone_number' => '',
            'password' => Hash::make($validated['password']),
            'shop_name' => $validated['shop_name'],
            'shop_description' => 'Seller onboarding submission',
            'location' => $validated['location'],
            'cid_number' => $validated['cid_number'],
            'business_license' => $businessLicensePath,
            'bank_name' => $validated['bank_name'],
            'account_number' => $validated['account_number'],
            'account_holder_name' => $validated['full_name'],
            'contact_number' => '',
            'address' => $validated['location'],
            'is_verified' => false,
            'status' => 'pending',
        ]);

        return redirect()->route('register.seller.success')
            ->with('success', 'Your seller account has been submitted and is pending verification.');
    }

    public function success()
    {
        return view('auth.register_seller_success');
    }
}
