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
        'transaction_no',
        'transaction_date',
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
        'transaction_date' => 'datetime',
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

    public function refunds()
    {
        return $this->hasMany(Refund::class);
    }

    public function latestRefund()
    {
        return $this->hasOne(Refund::class)->latestOfMany();
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
            'processing' => ['shipping', 'cancelled', 'refunded'], // Cho phép hoàn tiền từ processing
            'shipping' => ['delivered', 'cancelled', 'refunded'], // Cho phép hoàn tiền từ shipping
            'delivered' => ['completed', 'refunded'], // Cho phép hoàn tiền từ delivered
            'completed' => ['refunded'], // Cho phép hoàn tiền từ completed
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

    /**
     * Kiểm tra đơn hàng có thể hoàn tiền không
     */
    public function canBeRefunded(): bool
    {
        // Chỉ có thể hoàn tiền nếu:
        // 1. Đã thanh toán (payment_status = 'paid')
        // 2. Chưa được hoàn tiền (payment_status != 'refunded')
        // 3. Trạng thái đơn hàng cho phép hoàn tiền
        if ($this->payment_status !== 'paid') {
            return false;
        }

        if ($this->payment_status === 'refunded') {
            return false;
        }

        // Cho phép hoàn tiền từ các trạng thái: processing, shipping, delivered, completed
        $allowedStatusesForRefund = ['processing', 'shipping', 'delivered', 'completed'];
        return in_array($this->status, $allowedStatusesForRefund);
    }

    /**
     * Cập nhật trạng thái đơn hàng khi hoàn tiền thành công
     * 
     * @param float|null $refundAmount Số tiền hoàn (null = hoàn toàn bộ)
     * @return array Thông tin cập nhật
     */
    public function processRefund(?float $refundAmount = null): array
    {
        try {
            if (!$this->canBeRefunded()) {
                return [
                    'success' => false,
                    'message' => 'Đơn hàng không thể hoàn tiền.'
                ];
            }

            // Lưu trạng thái cũ trước khi cập nhật
            $oldStatus = $this->status;
            $oldPaymentStatus = $this->payment_status;
            $oldStatusLabel = $this->getStatusLabelAttribute(); // Lưu label cũ trước khi update

            $updates = [
                'payment_status' => 'refunded', // Luôn cập nhật payment_status
            ];

            // Logic cập nhật status dựa trên trạng thái hiện tại:
            // - Nếu đơn hàng chưa được giao (processing, shipping): chuyển sang refunded
            // - Nếu đơn hàng đã được giao (delivered, completed): 
            //   + Có thể giữ nguyên status (để theo dõi đơn đã giao nhưng đã hoàn tiền)
            //   + Hoặc chuyển sang refunded (để đánh dấu rõ ràng)
            //   Ở đây chúng ta sẽ chuyển sang refunded để đánh dấu rõ ràng
            
            $newStatus = 'refunded';
            if (in_array($this->status, ['processing', 'shipping', 'delivered', 'completed'])) {
                $updates['status'] = $newStatus;
            } else {
                // Nếu không trong danh sách trên, giữ nguyên status
                $newStatus = $this->status;
            }

            // Cập nhật thông tin hoàn tiền
            $this->update($updates);
            
            // Refresh để lấy giá trị mới
            $this->refresh();
            
            // Lấy label mới sau khi update
            $newStatusLabel = $this->getStatusLabelAttribute();

            // Tạo thông báo
            $refundInfo = $refundAmount ? number_format($refundAmount, 0, ',', '.') . ' ₫' : 'toàn bộ';
            
            \Log::info('Order Refund Processed', [
                'order_id' => $this->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'refund_amount' => $refundAmount,
            ]);
            
            return [
                'success' => true,
                'message' => "Hoàn tiền thành công! Đã hoàn {$refundInfo}. Đơn hàng đã được cập nhật từ '{$oldStatusLabel}' sang '{$newStatusLabel}'.",
                'updates' => $updates,
                'old_status' => $oldStatus,
                'old_status_label' => $oldStatusLabel,
                'old_payment_status' => $oldPaymentStatus,
                'new_status' => $newStatus,
                'new_status_label' => $newStatusLabel,
                'new_payment_status' => 'refunded',
            ];
        } catch (\Exception $e) {
            \Log::error('Order processRefund Exception', [
                'order_id' => $this->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return [
                'success' => false,
                'message' => 'Lỗi khi cập nhật trạng thái đơn hàng: ' . $e->getMessage()
            ];
        }
    }
}

