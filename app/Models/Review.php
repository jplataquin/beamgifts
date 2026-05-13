<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'product_id',
        'gifter_id',
        'voucher_id',
        'rating',
        'comment',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function gifter()
    {
        return $this->belongsTo(Gifter::class);
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }
}
