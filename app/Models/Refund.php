<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Refund extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'amount',
        'transaction_no',
        'vnpay_response_code',
        'vnpay_response_data',
        'refunded_by',
        'reason',
        'status',
        'refunded_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'vnpay_response_data' => 'array',
        'refunded_at' => 'datetime',
    ];

    /**
     * Quan hệ với Order
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Quan hệ với User (người thực hiện hoàn tiền)
     */
    public function refundedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'refunded_by');
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Đang xử lý',
            'success' => 'Thành công',
            'failed' => 'Thất bại',
            default => ucfirst($this->status),
        };
    }
}
