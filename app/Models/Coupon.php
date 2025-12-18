<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'promotion_type',
        'discount_type',
        'discount_value',
        'starts_at',
        'expires_at',
        'min_order_value',
        'max_discount',
        'usage_limit',
        'usage_per_user',
        'used_count',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'discount_value' => 'float',
        'min_order_value' => 'float',
        'max_discount' => 'float',
        'usage_limit' => 'integer',
        'usage_per_user' => 'integer',
        'used_count' => 'integer',
    ];

    /**
     * Check if promotion has started
     */
    public function hasStarted(): bool
    {
        if (!$this->starts_at) {
            return true; // Nếu không có ngày bắt đầu, coi như đã bắt đầu
        }
        return $this->starts_at->isPast() || $this->starts_at->isToday();
    }

    /**
     * Check if promotion has expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if promotion is currently active
     */
    public function isValid(): bool
    {
        return $this->hasStarted() && !$this->isExpired();
    }

    /**
     * Check if promotion is for orders
     */
    public function isForOrder(): bool
    {
        return ($this->promotion_type ?? 'order') === 'order';
    }

    /**
     * Check if promotion is for products
     */
    public function isForProduct(): bool
    {
        return ($this->promotion_type ?? 'order') === 'product';
    }

    /**
     * Calculate discount amount based on subtotal (for order-level promotions)
     */
    public function calculateDiscount(float $subtotal): float
    {
        if (!$this->isValid()) {
            return 0;
        }

        if ($this->discount_type === 'percent') {
            $discount = ($subtotal * $this->discount_value) / 100;
            // Áp dụng max_discount nếu có (quan trọng để tránh giảm quá nhiều)
            if ($this->max_discount !== null && $this->max_discount > 0) {
                $discount = min($discount, $this->max_discount);
            }
            // Đảm bảo không giảm quá số tiền đơn hàng
            return min($discount, $subtotal);
        } else {
            // Fixed discount - đảm bảo không giảm quá số tiền đơn hàng
            return min($this->discount_value, $subtotal);
        }
    }

    /**
     * Calculate discount for a single product price (for product-level promotions)
     */
    public function calculateProductDiscount(float $productPrice): float
    {
        if (!$this->isValid() || !$this->isForProduct()) {
            return 0;
        }

        if ($this->discount_type === 'percent') {
            $discount = ($productPrice * $this->discount_value) / 100;
            return min($discount, $productPrice);
        } else {
            // Fixed discount per product
            return min($this->discount_value, $productPrice);
        }
    }

    /**
     * Check if coupon applies to a specific product
     */
    public function appliesToProduct(int $productId): bool
    {
        if ($this->isForOrder()) {
            return true; // Order-level coupons apply to all products
        }

        if ($this->isForProduct()) {
            // Product-level coupons only apply to selected products
            return $this->products()->where('product_id', $productId)->exists();
        }

        return false;
    }

    /**
     * Find and validate coupon by code
     * @param string $code
     * @param int|null $userId
     * @param array|null $productIds Optional: array of product IDs in cart to validate product-level coupons
     * @param float|null $subtotal Optional: subtotal to validate min_order_value
     * @return self|null
     */
    public static function validateCode(string $code, ?int $userId = null, ?array $productIds = null, ?float $subtotal = null): ?self
    {
        $coupon = self::where('code', strtoupper(trim($code)))->first();

        if (!$coupon) {
            return null;
        }

        // Kiểm tra coupon có hợp lệ không (đã bắt đầu và chưa hết hạn)
        if (!$coupon->isValid()) {
            return null;
        }

        // Kiểm tra user có quyền dùng coupon không
        if (!$coupon->canBeUsedBy($userId)) {
            return null;
        }

        // Kiểm tra giới hạn số lần sử dụng toàn hệ thống
        if ($coupon->hasReachedUsageLimit()) {
            return null;
        }

        // Kiểm tra giới hạn số lần sử dụng mỗi user
        if ($coupon->hasReachedUserUsageLimit($userId)) {
            return null;
        }

        // Kiểm tra đơn hàng tối thiểu (nếu có subtotal)
        if ($subtotal !== null && !$coupon->canBeAppliedToSubtotal($subtotal)) {
            return null;
        }

        // Nếu là coupon cho sản phẩm, kiểm tra có sản phẩm nào trong giỏ hàng áp dụng được không
        if ($coupon->isForProduct() && $productIds !== null) {
            $applicableProducts = $coupon->products()->whereIn('product_id', $productIds)->count();
            if ($applicableProducts === 0) {
                return null; // Không có sản phẩm nào trong giỏ hàng được áp dụng coupon này
            }
        }

        return $coupon;
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Users who can use this coupon (for private coupons)
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'coupon_user')
                    ->withTimestamps();
    }

    /**
     * Products that this coupon applies to (for product-level promotions)
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'coupon_product')
                    ->withTimestamps();
    }

    /**
     * Usage records for this coupon
     */
    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    /**
     * Check if coupon is public
     */
    public function isPublic(): bool
    {
        return ($this->type ?? 'public') === 'public';
    }

    /**
     * Check if coupon is private
     */
    public function isPrivate(): bool
    {
        return ($this->type ?? 'public') === 'private';
    }

    /**
     * Check if user can use this coupon
     */
    public function canBeUsedBy(?int $userId): bool
    {
        // Public coupon: ai cũng dùng được
        if ($this->isPublic()) {
            return true;
        }

        // Private coupon: chỉ user được chỉ định mới dùng được
        if ($this->isPrivate()) {
            if (!$userId) {
                return false;
            }
            return $this->users()->where('user_id', $userId)->exists();
        }

        return false;
    }

    /**
     * Check if coupon can be applied to subtotal (min order value)
     */
    public function canBeAppliedToSubtotal(float $subtotal): bool
    {
        // Nếu có yêu cầu đơn hàng tối thiểu
        if ($this->min_order_value !== null && $this->min_order_value > 0) {
            return $subtotal >= $this->min_order_value;
        }
        return true;
    }

    /**
     * Check if coupon has reached usage limit
     */
    public function hasReachedUsageLimit(): bool
    {
        // Kiểm tra giới hạn toàn hệ thống
        if ($this->usage_limit !== null && $this->usage_limit > 0) {
            if ($this->used_count >= $this->usage_limit) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get number of times a user has used this coupon
     */
    public function usedByUser(?int $userId): int
    {
        if (!$userId) {
            return 0;
        }
        return $this->usages()->where('user_id', $userId)->count();
    }

    /**
     * Check if user has reached usage limit per user
     */
    public function hasReachedUserUsageLimit(?int $userId): bool
    {
        // Kiểm tra giới hạn mỗi user
        if ($this->usage_per_user !== null && $this->usage_per_user > 0) {
            if ($userId && $this->usedByUser($userId) >= $this->usage_per_user) {
                return true;
            }
        }
        return false;
    }

    /**
     * Record coupon usage
     */
    public function recordUsage(?int $userId, ?int $orderId = null): CouponUsage
    {
        $usage = CouponUsage::create([
            'coupon_id' => $this->id,
            'user_id' => $userId,
            'order_id' => $orderId,
            'used_at' => now(),
        ]);

        // Tăng counter
        $this->increment('used_count');

        return $usage;
    }
}
