<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'store_id',
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'markup_price',
        'category',
        'images',
        'is_banned',
        'status',
    ];

    protected $casts = [
        'images' => 'array',
        'price' => 'decimal:2',
        'markup_price' => 'decimal:2',
        'is_banned' => 'boolean',
    ];

    /**
     * Scope a query to only include active products.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Active')->where('is_banned', false);
    }

    /**
     * Get the store that owns the product.
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Get the category that the product belongs to.
     */
    public function category_rel(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Get the reviews for the product.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the average rating of the product.
     * Defaults to 5 if no reviews exist.
     */
    public function getAverageRatingAttribute()
    {
        if ($this->reviews()->count() === 0) {
            return 5;
        }

        return round($this->reviews()->avg('rating'), 1);
    }

    /**
     * Get the number of reviews for the product.
     */
    public function getReviewsCountAttribute()
    {
        return $this->reviews()->count();
    }
}
