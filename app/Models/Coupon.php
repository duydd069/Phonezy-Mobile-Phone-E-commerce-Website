<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'discount_value' => 'float',
    ];

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
