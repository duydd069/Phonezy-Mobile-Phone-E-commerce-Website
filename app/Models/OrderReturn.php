<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'return_code',
        'contact_phone',
        'refund_method',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'reason',
        'returned_at',
        'received_at',
        'refunded_at',
        'shipping_status',
        'status',
        'admin_note',
        'refunded_by',
    ];

    protected $casts = [
        'returned_at' => 'datetime',
        'received_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function images()
    {
        return $this->hasMany(OrderReturnImage::class);
    }

    public function refundedBy()
    {
        return $this->belongsTo(User::class, 'refunded_by');
    }

    /**
     * Generate unique return code
     */
    public static function generateReturnCode(): string
    {
        $date = now()->format('Ym');
        $count = self::whereRaw('DATE_FORMAT(created_at, "%Y%m") = ?', [$date])->count() + 1;
        return 'RT-' . $date . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Check if return can be approved
     */
    public function canApprove(): bool
    {
        return $this->status === 'Chưa giải quyết';
    }

    /**
     * Check if return can be rejected
     */
    public function canReject(): bool
    {
        return $this->status === 'Chưa giải quyết';
    }

    /**
     * Check if customer can mark as shipped
     */
    public function canMarkAsShipped(): bool
    {
        return $this->status === 'Thông qua' && $this->shipping_status === 'Chưa vận chuyển';
    }

    /**
     * Check if admin can confirm received
     */
    public function canConfirmReceived(): bool
    {
        return $this->status === 'Đang vận chuyển';
    }

    /**
     * Check if admin can process refund
     */
    public function canProcessRefund(): bool
    {
        return $this->status === 'Đã nhận';
    }

    /**
     * Get status label attribute
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->status;
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'Chưa giải quyết' => 'bg-warning',
            'Thông qua' => 'bg-info',
            'Từ chối' => 'bg-danger',
            'Đang vận chuyển' => 'bg-primary',
            'Đã nhận' => 'bg-success',
            'Đã hoàn tiền' => 'bg-success',
            default => 'bg-secondary',
        };
    }

    /**
     * Get shipping status label
     */
    public function getShippingStatusLabelAttribute(): string
    {
        return $this->shipping_status ?? '-';
    }

    /**
     * Approve the return request
     */
    public function approve(): bool
    {
        if (!$this->canApprove()) {
            return false;
        }

        return $this->update([
            'status' => 'Thông qua',
        ]);
    }

    /**
     * Reject the return request
     */
    public function reject(string $adminNote): bool
    {
        if (!$this->canReject()) {
            return false;
        }

        return $this->update([
            'status' => 'Từ chối',
            'admin_note' => $adminNote,
        ]);
    }

    /**
     * Mark as shipped by customer
     */
    public function markAsShipped(): bool
    {
        if (!$this->canMarkAsShipped()) {
            return false;
        }

        return $this->update([
            'status' => 'Đang vận chuyển',
            'shipping_status' => 'Đang vận chuyển',
            'returned_at' => now(),
        ]);
    }

    /**
     * Confirm received by admin
     */
    public function confirmReceived(): bool
    {
        if (!$this->canConfirmReceived()) {
            return false;
        }

        return $this->update([
            'status' => 'Đã nhận',
            'shipping_status' => 'Đã vận chuyển',
            'received_at' => now(),
        ]);
    }

    /**
     * Process refund
     */
    public function processRefund(int $adminId): bool
    {
        if (!$this->canProcessRefund()) {
            return false;
        }

        // Update return status
        $updated = $this->update([
            'status' => 'Đã hoàn tiền',
            'refunded_at' => now(),
            'refunded_by' => $adminId,
        ]);

        // Update main order status
        if ($updated) {
            $this->order->update(['status' => 'da_hoan_tien']);
        }

        return $updated;
    }
}
