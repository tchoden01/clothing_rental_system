<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pickup extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'order_item_id',
        'seller_id',
        'customer_id',
        'pickup_date',
        'return_date',
        'pickup_status',
    ];

    protected $casts = [
        'pickup_date' => 'date',
        'return_date' => 'date',
    ];

    public const STATUS_LABELS = [
        'pending_pickup' => 'Pending Pickup',
        'scheduled' => 'Scheduled',
        'picked_up_from_seller' => 'Picked Up from Seller',
        'delivered_to_customer' => 'Delivered to Customer',
        'return_pickup_scheduled' => 'Return Pickup Scheduled',
        'returned_from_customer' => 'Returned from Customer',
        'completed' => 'Completed',
    ];

    public static function statusLabels(): array
    {
        return self::STATUS_LABELS;
    }

    public static function ensureForOrder(Order $order): void
    {
        $order->loadMissing(['orderItems.product', 'user']);

        foreach ($order->orderItems as $item) {
            self::firstOrCreate(
                [
                    'order_id' => $order->id,
                    'order_item_id' => $item->id,
                ],
                [
                    'seller_id' => $item->seller_id ?: optional($item->product)->seller_id,
                    'customer_id' => $order->user_id,
                    'pickup_date' => $item->rental_start_date ?: $order->rental_start_date,
                    'return_date' => $item->rental_end_date ?: $order->rental_end_date,
                    'pickup_status' => 'pending_pickup',
                ]
            );
        }
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
