<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class Pickup extends Model
{
    use HasFactory;

    public const STATUSES = [
        'pending',
        'ready',
        'picked_up',
        'in_use',
        'returned',
        'completed',
    ];

    public const TRANSITIONS = [
        'pending' => 'ready',
        'ready' => 'picked_up',
        'picked_up' => 'in_use',
        'in_use' => 'returned',
        'returned' => 'completed',
        'completed' => null,
    ];

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
        'pending' => 'Pending',
        'ready' => 'Ready',
        'picked_up' => 'Picked Up',
        'in_use' => 'In Use',
        'returned' => 'Returned',
        'completed' => 'Completed',
    ];

    public const STATUS_BADGE_CLASSES = [
        'pending' => 'bg-warning text-dark',
        'ready' => 'bg-primary',
        'picked_up' => 'bg-success',
        'in_use' => 'pickup-badge-in-use',
        'returned' => 'pickup-badge-returned',
        'completed' => 'bg-secondary',
    ];

    public const TRANSITION_BUTTON_LABELS = [
        'ready' => 'Mark as Ready',
        'picked_up' => 'Mark as Picked Up',
        'in_use' => 'Mark as In Use',
        'returned' => 'Mark as Returned',
        'completed' => 'Mark as Completed',
    ];

    public static function statusLabels(): array
    {
        return self::STATUS_LABELS;
    }

    public static function statusBadgeClasses(): array
    {
        return self::STATUS_BADGE_CLASSES;
    }

    public static function statuses(): array
    {
        return self::STATUSES;
    }

    public static function nextStatusFor(string $currentStatus): ?string
    {
        return self::TRANSITIONS[$currentStatus] ?? null;
    }

    public static function canTransition(string $fromStatus, string $toStatus, bool $force = false): bool
    {
        if (!in_array($toStatus, self::STATUSES, true)) {
            return false;
        }

        if ($force) {
            return true;
        }

        return self::nextStatusFor($fromStatus) === $toStatus;
    }

    public static function transitionButtonLabel(?string $nextStatus): ?string
    {
        if (!$nextStatus) {
            return null;
        }

        return self::TRANSITION_BUTTON_LABELS[$nextStatus] ?? null;
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->pickup_status] ?? ucwords(str_replace('_', ' ', $this->pickup_status));
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return self::STATUS_BADGE_CLASSES[$this->pickup_status] ?? 'bg-light text-dark border';
    }

    public function getNextPickupStatusAttribute(): ?string
    {
        return self::nextStatusFor($this->pickup_status);
    }

    public function getNextPickupActionLabelAttribute(): ?string
    {
        return self::transitionButtonLabel($this->next_pickup_status);
    }

    public function transitionTo(string $newStatus, bool $force = false): void
    {
        if (!self::canTransition($this->pickup_status, $newStatus, $force)) {
            throw new InvalidArgumentException('Invalid pickup status transition.');
        }

        $this->pickup_status = $newStatus;
        $this->save();
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
                    'pickup_status' => 'pending',
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
