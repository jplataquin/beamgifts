<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Partner extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone1',
        'phone2',
        'password',
        'business_name',
        'is_banned',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_banned' => 'boolean',
    ];

    /**
     * Get the store owned by the partner.
     */
    public function store(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Store::class);
    }
}
