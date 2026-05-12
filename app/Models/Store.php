<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{
    protected $fillable = [
        'partner_id',
        'name',
        'slug',
        'description',
        'logo',
        'is_banned',
    ];

    protected $casts = [
        'is_banned' => 'boolean',
    ];

    /**
     * Get the partner that owns the store.
     */
    public function partner(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * Get the branches for the store.
     */
    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }

    /**
     * Get the products for the store.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
