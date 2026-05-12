<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Branch extends Model
{
    protected $fillable = [
        'store_id',
        'city_id',
        'name',
        'address',
        'phone',
        'map_url',
    ];

    /**
     * Get the store that owns the branch.
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Get the city where the branch is located.
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
