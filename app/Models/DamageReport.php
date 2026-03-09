<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DamageReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'damage_type',
        'damage_fee',
        'description',
        'reported_by',
        'status',
    ];

    protected $casts = [
        'damage_fee' => 'decimal:2',
    ];

    /**
     * Get the order that owns the damage report.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the product that owns the damage report.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user who reported the damage.
     */
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
}
