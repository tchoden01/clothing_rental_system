<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subscription_plan_id',
        'start_date',
        'next_billing_date',
        'end_date',
        'status',
        'items_currently_rented',
        'is_first_month',
    ];

    protected $casts = [
        'start_date' => 'date',
        'next_billing_date' => 'date',
        'end_date' => 'date',
        'is_first_month' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscriptionPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    public function canRentMore()
    {
        $plan = $this->subscriptionPlan;
        
        if ($plan->isUnlimited()) {
            return true;
        }
        
        return $this->items_currently_rented < $plan->item_limit;
    }
}
