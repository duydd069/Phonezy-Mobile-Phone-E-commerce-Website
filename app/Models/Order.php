<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email',
        'session_id',
        'coupon_id',
        'total_price',
        'status',
        'notes',
        'email_verified_at',
        'verification_token',
        'customer_name',
        'customer_phone',
        'shipping_address',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'total_price' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function isEmailVerified()
    {
        return !is_null($this->email_verified_at);
    }
}

