<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'user_id',
        'coupon_id',
        'subtotal',
        'shipping_fee',
        'discount_amount',
        'total',
        'payment_method',
        'payment_status',
        'status',
        'shipping_full_name',
        'shipping_email',
        'shipping_phone',
        'shipping_city',
        'shipping_district',
        'shipping_ward',
        'shipping_address',
        'notes',
        'paid_at',
    ];

    protected $casts = [
        'subtotal'        => 'float',
        'shipping_fee'    => 'float',
        'discount_amount' => 'float',
        'total'           => 'float',
        'paid_at'         => 'datetime',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

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

    /**
     * Get status label in Vietnamese
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Chờ xử lý',
            'processing' => 'Đang xử lý',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
            default => ucfirst($this->status),
        };
    }

    /**
     * Get badge class for status
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'pending' => 'bg-warning',
            'processing' => 'bg-info',
            'completed' => 'bg-success',
            'cancelled' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    /**
     * Get payment status label in Vietnamese
     */
    public function getPaymentStatusLabelAttribute(): string
    {
        return match($this->payment_status) {
            'pending' => 'Chờ thanh toán',
            'paid' => 'Đã thanh toán',
            'failed' => 'Thanh toán thất bại',
            'refunded' => 'Đã hoàn tiền',
            default => ucfirst($this->payment_status),
        };
    }
}

