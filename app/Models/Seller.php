<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'email',
        'phone_number',
        'password',
        'shop_name',
        'shop_description',
        'location',
        'cid_number',
        'business_license',
        'bank_name',
        'account_number',
        'account_holder_name',
        'contact_number',
        'address',
        'is_verified',
        'status',
        'rejection_reason',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
    ];

    /**
     * Get the user that owns the seller.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the products for the seller.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the order items for the seller.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get category requests submitted by this seller.
     */
    public function submittedCategories()
    {
        return $this->hasMany(Category::class);
    }
}
