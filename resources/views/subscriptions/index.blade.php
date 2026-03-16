@extends('layouts.app')

@section('title', 'Subscription Plans')

@section('content')
<style>
    .plans-header {
        text-align: center;
        padding: 60px 0 40px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        margin-bottom: 50px;
    }
    
    .plans-header h1 {
        font-size: 2.5rem;
        font-weight: 300;
        margin-bottom: 10px;
    }
    
    .plans-header p {
        font-size: 1.1rem;
        opacity: 0.95;
    }
    
    .plan-card {
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        padding: 40px 30px;
        text-align: center;
        transition: all 0.3s ease;
        height: 100%;
        background: white;
        position: relative;
    }
    
    .plan-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        border-color: #764ba2;
    }
    
    .plan-badge {
        position: absolute;
        top: -15px;
        right: 20px;
        background: #764ba2;
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    
    .plan-name {
        font-size: 1.1rem;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 10px;
        font-weight: 600;
    }
    
    .plan-title {
        font-size: 1.8rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 20px;
    }
    
    .plan-price {
        font-size: 2rem;
        font-weight: 700;
        color: #764ba2;
        margin-bottom: 5px;
    }
    
    .plan-price-detail {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 25px;
    }
    
    .plan-features {
        list-style: none;
        padding: 0;
        margin: 25px 0;
    }
    
    .plan-features li {
        padding: 10px 0;
        color: #555;
        font-size: 1rem;
    }
    
    .plan-btn {
        background: #764ba2;
        color: white;
        border: none;
        padding: 15px 40px;
        border-radius: 50px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        margin-top: 20px;
    }
    
    .plan-btn:hover {
        background: #667eea;
        color: white;
        transform: scale(1.05);
    }
    
    .current-plan {
        border-color: #764ba2;
        background: #f8f5ff;
    }
    
    .current-badge {
        background: #28a745;
    }
</style>

<div class="plans-header">
    <h1>Plans For Every Budget</h1>
    <p>All plans allow you to keep your at-home items until your new case arrives, so you're never without a great wardrobe!</p>
</div>

<div class="container mb-5">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($currentSubscription)
        <div class="alert alert-info text-center">
            <strong>Current Plan:</strong> {{ $currentSubscription->subscriptionPlan->name }}
            <form action="{{ route('subscriptions.cancel', $currentSubscription) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to cancel your subscription?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger ms-3">Cancel Subscription</button>
            </form>
        </div>
    @endif

    <div class="row">
        @foreach($plans as $index => $plan)
            <div class="col-md-4 mb-4">
                <div class="plan-card {{ $currentSubscription && $currentSubscription->subscription_plan_id == $plan->id ? 'current-plan' : '' }}">
                    @if($index == 2)
                        <div class="plan-badge">Best Value</div>
                    @elseif($currentSubscription && $currentSubscription->subscription_plan_id == $plan->id)
                        <div class="plan-badge current-badge">Current Plan</div>
                    @endif
                    
                    <div class="plan-name">
                        @if($index == 0)
                            THE WARDROBE PICK-ME-UP
                        @elseif($index == 1)
                            THE WARDROBE ENHANCER
                        @else
                            THE WARDROBE REPLACER
                        @endif
                    </div>
                    
                    <div class="plan-title">{{ $plan->name }}</div>
                    
                    <div class="plan-price">
                        ${{ $plan->first_month_price ? number_format($plan->first_month_price, 0) : number_format($plan->price, 0) }} 
                        <span style="font-size: 1rem;">{{ $plan->first_month_price ? 'first month' : 'per month' }}</span>
                    </div>
                    
                    @if($plan->first_month_price)
                        <div class="plan-price-detail">
                            (${{ number_format($plan->price, 0) }}/month after)
                        </div>
                    @endif
                    
                    <ul class="plan-features">
                        <li>
                            Rent {{ $plan->isUnlimited() ? '6 items at a time' : $plan->item_limit . ' items at a time' }}.
                        </li>
                        <li>
                            @if($plan->isUnlimited())
                                <strong>Unlimited</strong> Swaps.
                            @else
                                Swap every {{ $plan->swap_days }} days.
                            @endif
                        </li>
                    </ul>
                    
                    @if(!$currentSubscription || $currentSubscription->subscription_plan_id != $plan->id)
                        <form action="{{ route('subscriptions.subscribe', $plan) }}" method="POST">
                            @csrf
                            <button type="submit" class="plan-btn">
                                Try {{ $plan->name }}
                            </button>
                        </form>
                    @else
                        <button class="plan-btn" disabled style="background: #28a745;">
                            Current Plan
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
