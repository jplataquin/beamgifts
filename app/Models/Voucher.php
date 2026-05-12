<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Voucher extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'price',
        'markup_price',
        'unique_token',
        'qr_payload',
        'personal_message',
        'closing_remark',
        'custom_photo',
        'status',
        'expires_at',
        'claimed_at',
        'claimed_branch_id',
        'claimed_by',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'claimed_at' => 'datetime',
        'price' => 'decimal:2',
        'markup_price' => 'decimal:2',
    ];

    /**
     * Get the order that contains this voucher.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the product this voucher is for.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the branch where the voucher was claimed.
     */
    public function claimedBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'claimed_branch_id');
    }
}
