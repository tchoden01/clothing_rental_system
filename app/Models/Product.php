<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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

    /**
     * Get normalized image paths stored for this product.
     */
    public function getNormalizedImagesAttribute(): array
    {
        $images = $this->images ?? [];

        if (is_string($images)) {
            $images = json_decode($images, true) ?: [];
        }

        $normalized = array_map(function ($path) {
            if (!is_string($path) || trim($path) === '') {
                return null;
            }

            $cleanPath = ltrim(trim($path), '/');

            if (str_starts_with($cleanPath, 'storage/')) {
                $cleanPath = substr($cleanPath, 8);
            }

            if (str_starts_with($cleanPath, 'public/')) {
                $cleanPath = substr($cleanPath, 7);
            }

            return $cleanPath;
        }, $images);

        return array_values(array_filter($normalized));
    }

    /**
     * Get publicly accessible URLs for product images that exist on disk.
     */
    public function getImageUrlsAttribute(): array
    {
        $urls = [];

        foreach ($this->normalized_images as $path) {
            if (Storage::disk('public')->exists($path)) {
                $urls[] = asset('storage/' . $path);
            }
        }

        return $urls;
    }

    /**
     * Get the first valid image URL or null.
     */
    public function getPrimaryImageUrlAttribute(): ?string
    {
        return $this->image_urls[0] ?? null;
    }
}
