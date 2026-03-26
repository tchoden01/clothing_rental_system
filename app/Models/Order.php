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
        'total_amount',
        'platform_commission',
        'delivery_method',
        'delivery_address',
        'rental_start_date',
        'rental_end_date',
        'status',
        'payment_status',
        'cancelled_at',
        'cancelled_by',
        'cancellation_hours_before_start',
        'refundable_base_amount',
        'refund_percentage',
        'refund_amount',
        'platform_fee_amount',
        'platform_fee_refunded',
        'refund_processed_at',
        'is_refund_overridden',
        'refund_overridden_by',
        'refund_override_at',
        'refund_override_note',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'platform_commission' => 'decimal:2',
        'rental_start_date' => 'date',
        'rental_end_date' => 'date',
        'cancelled_at' => 'datetime',
        'cancellation_hours_before_start' => 'integer',
        'refundable_base_amount' => 'decimal:2',
        'refund_percentage' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'platform_fee_amount' => 'decimal:2',
        'platform_fee_refunded' => 'boolean',
        'refund_processed_at' => 'datetime',
        'is_refund_overridden' => 'boolean',
        'refund_override_at' => 'datetime',
    ];

    /**
     * Get the user that owns the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * User who cancelled the order.
     */
    public function cancelledBy()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    /**
     * Admin who overrode refund policy.
     */
    public function refundOverriddenBy()
    {
        return $this->belongsTo(User::class, 'refund_overridden_by');
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
