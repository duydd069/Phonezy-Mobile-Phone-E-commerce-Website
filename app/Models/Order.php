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
        'transaction_id',
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

    /**
     * Get status label in English
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Pending',
            'processing' => 'Processing',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
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
     * Get payment status label in English
     */
    public function getPaymentStatusLabelAttribute(): string
    {
        return match($this->payment_status) {
            'pending' => 'Pending',
            'paid' => 'Paid',
            'failed' => 'Failed',
            'refunded' => 'Refunded',
            default => ucfirst($this->payment_status),
        };
    }
}


