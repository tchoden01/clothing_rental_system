<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'seller_id',
        'quantity',
        'rental_price',
        'seller_earnings',
        'payout_status',
        'payout_released_at',
        'payout_released_by',
        'rental_start_date',
        'rental_end_date',
        'return_status',
    ];

    protected $casts = [
        'rental_price' => 'decimal:2',
        'seller_earnings' => 'decimal:2',
        'payout_released_at' => 'datetime',
        'rental_start_date' => 'date',
        'rental_end_date' => 'date',
    ];

    /**
     * Get the order that owns the order item.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the product that owns the order item.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the seller that owns the order item.
     */
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    /**
     * Get pickup record for this order item.
     */
    public function pickup()
    {
        return $this->hasOne(Pickup::class);
    }

    /**
     * Get the damage report for this specific order item.
     */
    public function damageReport()
    {
        return $this->hasOne(DamageReport::class, 'product_id', 'product_id')
            ->whereColumn('damage_reports.order_id', 'order_items.order_id');
    }

    /**
     * Admin user who released this payout.
     */
    public function payoutReleasedBy()
    {
        return $this->belongsTo(User::class, 'payout_released_by');
    }
}
