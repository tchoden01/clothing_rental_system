<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'total_price',
        'platform_commission',
        'delivery_method',
        'delivery_address',
        'rental_start_date',
        'rental_end_date',
        'status',
        'payment_status',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'platform_commission' => 'decimal:2',
        'rental_start_date' => 'date',
        'rental_end_date' => 'date',
    ];

    /**
     * Get the user that owns the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order items for the order.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get pickup records for this order.
     */
    public function pickups()
    {
        return $this->hasMany(Pickup::class);
    }

    /**
     * Get the payment for the order.
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Get the damage reports for the order.
     */
    public function damageReports()
    {
        return $this->hasMany(DamageReport::class);
    }

    /**
     * Generate unique order number.
     */
    public static function generateOrderNumber()
    {
        return 'ORD-' . date('YmdHis') . '-' . rand(1000, 9999);
    }
}
