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
     * Get all available order statuses
     */
    public static function getAvailableStatuses(): array
    {
        return [
            'pending' => 'Chờ xử lý',
            'confirmed' => 'Đã xác nhận',
            'processing' => 'Đang xử lý',
            'shipping' => 'Đang giao hàng',
            'delivered' => 'Đã giao hàng',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
            'refunded' => 'Đã hoàn tiền',
        ];
    }

    /**
     * Get valid next statuses for current status
     */
    public function getValidNextStatuses(): array
    {
        $statusFlow = [
            'pending' => ['confirmed', 'processing', 'cancelled'],
            'confirmed' => ['processing', 'cancelled'],
            'processing' => ['shipping', 'cancelled'],
            'shipping' => ['delivered', 'cancelled'],
            'delivered' => ['completed', 'refunded'],
            'completed' => [], // Không thể chuyển từ completed
            'cancelled' => [], // Không thể chuyển từ cancelled
            'refunded' => [], // Không thể chuyển từ refunded
        ];

        $validStatuses = $statusFlow[$this->status] ?? [];

        // Nếu đơn hàng dùng online payment và chưa thanh toán, không cho phép xác nhận
        if ($this->requiresPaymentBeforeConfirmation()) {
            // Loại bỏ các trạng thái xác nhận nếu chưa thanh toán
            $validStatuses = array_filter($validStatuses, function($status) {
                return !in_array($status, ['confirmed', 'processing', 'shipping', 'delivered', 'completed']);
            });
        }

        return array_values($validStatuses);
    }

    /**
     * Check if status transition is valid
     */
    public function canTransitionTo(string $newStatus): bool
    {
        return in_array($newStatus, $this->getValidNextStatuses());
    }

    /**
     * Get status label in Vietnamese
     */
    public function getStatusLabelAttribute(): string
    {
        $statuses = self::getAvailableStatuses();
        return $statuses[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Get badge class for status
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'pending' => 'bg-warning',
            'confirmed' => 'bg-info',
            'processing' => 'bg-primary',
            'shipping' => 'bg-info',
            'delivered' => 'bg-success',
            'completed' => 'bg-success',
            'cancelled' => 'bg-danger',
            'refunded' => 'bg-secondary',
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

    /**
     * Check if order can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'confirmed', 'processing']);
    }

    /**
     * Check if order requires payment before confirmation
     */
    public function requiresPaymentBeforeConfirmation(): bool
    {
        // VNPay và các phương thức thanh toán online khác cần thanh toán trước khi xác nhận
        // COD (cash on delivery) không cần thanh toán trước
        $onlinePaymentMethods = ['vnpay', 'momo', 'zalopay', 'paypal'];
        $codMethods = ['cod', 'cash_on_delivery'];
        
        // Nếu là COD thì không cần thanh toán trước
        if (in_array($this->payment_method, $codMethods)) {
            return false;
        }
        
        // Nếu là online payment và chưa thanh toán thì cần thanh toán trước
        return in_array($this->payment_method, $onlinePaymentMethods) && $this->payment_status === 'pending';
    }

    /**
     * Check if order is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if order is cancelled
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Automatically update payment status based on order status
     */
    public function updatePaymentStatusBasedOnOrderStatus(): void
    {
        $updates = [];

        // Nếu đơn hàng hoàn thành và chưa thanh toán, tự động đánh dấu đã thanh toán
        if ($this->status === 'completed' && $this->payment_status === 'pending') {
            $updates['payment_status'] = 'paid';
            $updates['paid_at'] = now();
        }

        // Nếu đơn hàng bị hủy và đã thanh toán, đánh dấu cần hoàn tiền
        if ($this->status === 'cancelled' && $this->payment_status === 'paid') {
            $updates['payment_status'] = 'refunded';
        }

        // Nếu đơn hàng được hoàn tiền
        if ($this->status === 'refunded' && $this->payment_status !== 'refunded') {
            $updates['payment_status'] = 'refunded';
        }

        if (!empty($updates)) {
            $this->update($updates);
        }
    }
}

