<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = [
        'gifter_id',
        'hitpay_transaction_id',
        'total_amount',
        'status',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function gifter(): BelongsTo
    {
        return $this->belongsTo(Gifter::class, 'gifter_id');
    }

    public function vouchers(): HasMany
    {
        return $this->hasMany(Voucher::class);
    }
}
