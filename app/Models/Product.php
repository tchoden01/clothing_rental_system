<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'category_id',
        'for',
        'gender',
        'kid_type',
        'name',
        'size',
        'material',
        'color',
        'condition',
        'location',
        'description',
        'rental_price',
        'quantity',
        'images',
        'is_approved',
        'status',
    ];

    protected $casts = [
        'images' => 'array',
        'is_approved' => 'boolean',
        'rental_price' => 'decimal:2',
    ];

    /**
     * Get the seller that owns the product.
     */
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the occasions tagged for this product.
     */
    public function occasions()
    {
        return $this->belongsToMany(Occasion::class, 'product_occasion');
    }

    /**
     * Get the cart items for the product.
     */
    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Get the order items for the product.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the damage reports for the product.
     */
    public function damageReports()
    {
        return $this->hasMany(DamageReport::class);
    }
}
