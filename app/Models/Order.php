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
        'shipping_status',
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

    public function returns()
    {
        return $this->hasMany(OrderReturn::class);
    }

    /**
     * Check if order can be returned
     * Only orders with status giao_thanh_cong or hoan_thanh within 7 days
     */
    public function canBeReturned(): bool
    {
        // Check status
        if (!in_array($this->status, ['giao_thanh_cong', 'hoan_thanh'])) {
            return false;
        }

        // Check if already has pending/approved/in-progress return (exclude rejected)
        if ($this->returns()->whereIn('status', ['Chưa giải quyết', 'Thông qua', 'Đang vận chuyển', 'Đã nhận'])->exists()) {
            return false;
        }

        // Check 7 day limit from delivery/completion
        $limitDate = now()->subDays(7);
        return $this->updated_at >= $limitDate;
    }


    /**
     * Get all available order statuses
     */
    public static function getAvailableStatuses(): array
    {
        return [
            'cho_xac_nhan' => 'Chờ xác nhận',
            'cho_thanh_toan' => 'Chờ thanh toán',
            'da_xac_nhan' => 'Đã xác nhận',
            'chuan_bi_hang' => 'Chuẩn bị hàng',
            'dang_giao_hang' => 'Đang giao hàng',
            'giao_thanh_cong' => 'Giao thành công',
            'giao_that_bai' => 'Giao thất bại',
            'hoan_thanh' => 'Hoàn thành',
            'da_huy' => 'Đã hủy',
            'da_hoan_tien' => 'Đã hoàn tiền',
        ];
    }

    /**
     * Get valid next statuses for current status
     */
    public function getValidNextStatuses(): array
    {
        $statusFlow = [
            'cho_xac_nhan' => ['da_xac_nhan', 'da_huy'],
            'cho_thanh_toan' => ['da_xac_nhan', 'da_huy'],
            'da_xac_nhan' => ['chuan_bi_hang', 'da_huy'],
            'chuan_bi_hang' => ['dang_giao_hang'],
            'dang_giao_hang' => ['giao_thanh_cong', 'giao_that_bai'],
            'giao_thanh_cong' => ['hoan_thanh'],
            'giao_that_bai' => ['da_huy', 'dang_giao_hang'],
            'hoan_thanh' => [],
            'da_huy' => [],
            'da_hoan_tien' => [],
        ];

        $validStatuses = $statusFlow[$this->status] ?? [];

        // Nếu đơn hàng dùng online payment và chưa thanh toán, không cho phép xác nhận
        if ($this->requiresPaymentBeforeConfirmation()) {
            // Loại bỏ các trạng thái xác nhận nếu chưa thanh toán
            $validStatuses = array_filter($validStatuses, function($status) {
                return !in_array($status, ['da_xac_nhan', 'chuan_bi_hang', 'dang_giao_hang', 'giao_thanh_cong', 'hoan_thanh']);
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
            'cho_xac_nhan' => 'bg-warning',
            'cho_thanh_toan' => 'bg-warning',
            'da_xac_nhan' => 'bg-info',
            'chuan_bi_hang' => 'bg-primary',
            'dang_giao_hang' => 'bg-info',
            'giao_thanh_cong' => 'bg-success',
            'giao_that_bai' => 'bg-danger',
            'hoan_thanh' => 'bg-success',
            'da_huy' => 'bg-danger',
            'da_hoan_tien' => 'bg-secondary',
            default => 'bg-secondary',
        };
    }

    /**
     * Get payment status label in Vietnamese
     */
    public function getPaymentStatusLabelAttribute(): string
    {
        return match((string)$this->payment_status) {
            'pending', '0' => 'Chờ thanh toán',
            'paid', '1' => 'Đã thanh toán',
            'failed', '2' => 'Thanh toán thất bại',
            'refunded' => 'Đã hoàn tiền',
            default => ucfirst($this->payment_status),
        };
    }

    /**
     * Check if order can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['cho_xac_nhan', 'cho_thanh_toan', 'da_xac_nhan']);
    }

    /**
     * Get shipping status label in Vietnamese
     */
    public function getShippingStatusLabelAttribute(): string
    {
        if (!$this->shipping_status) {
            return '-';
        }
        
        return match($this->shipping_status) {
            'chua_giao' => 'Chưa giao',
            'dang_giao_hang' => 'Đang giao',
            'giao_thanh_cong' => 'Giao thành công',
            'giao_that_bai' => 'Giao thất bại',
            default => ucfirst($this->shipping_status),
        };
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
        return $this->status === 'hoan_thanh';
    }

    /**
     * Check if order is cancelled
     */
    public function isCancelled(): bool
    {
        return $this->status === 'da_huy';
    }

    /**
     * Automatically update payment status based on order status
     */
    public function updatePaymentStatusBasedOnOrderStatus(): void
    {
        $updates = [];

        // Nếu đơn hàng hoàn thành và chưa thanh toán, tự động đánh dấu đã thanh toán
        if ($this->status === 'hoan_thanh' && $this->payment_status == 0) {
            $updates['payment_status'] = 1;
            $updates['paid_at'] = now();
        }

        // Nếu đơn hàng bị hủy và đã thanh toán, đánh dấu cần hoàn tiền
        if ($this->status === 'da_huy' && $this->payment_status == 1) {
            // Keep payment status as paid since refund needs to be processed
        }

        // Nếu đơn hàng được hoàn tiền
        if ($this->status === 'da_hoan_tien') {
            // Mark as paid since refund was processed
            $updates['payment_status'] = 1;
        }

        if (!empty($updates)) {
            $this->update($updates);
        }
    }
    // hủy đơn khi chưa xác nhận
        const STATUS_PENDING = 'cho_xac_nhan';
        const STATUS_WAIT_PAYMENT = 'cho_thanh_toan';
        const STATUS_CONFIRMED = 'da_xac_nhan';
        const STATUS_CANCELLED = 'da_huy';

        public function isPending()
        {
            return $this->status === self::STATUS_PENDING;
        }

        public function getStatusLabel()
        {
            return match ($this->status) {
                self::STATUS_PENDING => 'Chờ xác nhận',
                self::STATUS_WAIT_PAYMENT => 'Chờ thanh toán',
                self::STATUS_CONFIRMED => 'Đã xác nhận',
                self::STATUS_CANCELLED => 'Đã hủy',
                default => 'Không xác định',
            };
        }
}

