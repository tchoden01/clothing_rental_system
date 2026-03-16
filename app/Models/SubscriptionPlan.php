<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'item_limit',
        'price',
        'first_month_price',
        'swap_days',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'first_month_price' => 'decimal:2',
    ];

    public function userSubscriptions()
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function isUnlimited()
    {
        return $this->item_limit === -1;
    }

    public function getDisplayPrice()
    {
        if ($this->first_month_price) {
            return "$" . number_format($this->first_month_price, 0) . " first month ($" . number_format($this->price, 0) . "/month after)";
        }
        return "$" . number_format($this->price, 0) . " per month";
    }
}
