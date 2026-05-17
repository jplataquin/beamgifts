<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payout extends Model
{
    protected $fillable = [
        'partner_id',
        'status',
    ];

    /**
     * Get the partner receiving this payout.
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * Get the vouchers included in this payout.
     */
    public function vouchers(): HasMany
    {
        return $this->hasMany(Voucher::class);
    }

    /**
     * Calculate the total amount of this payout dynamically.
     */
    public function getTotalAmountAttribute()
    {
        return $this->vouchers->sum(function ($voucher) {
            return $voucher->price ?? $voucher->product->price;
        });
    }
}
