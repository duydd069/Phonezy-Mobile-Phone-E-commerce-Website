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

    public function isValid()
    {
        return !$this->isExpired();
    }

    /**
     * Calculate discount amount based on subtotal
     */
    public function calculateDiscount(float $subtotal): float
    {
        if (!$this->isValid()) {
            return 0;
        }

        if ($this->discount_type === 'percent') {
            $discount = ($subtotal * $this->discount_value) / 100;
            // Đảm bảo không giảm quá số tiền đơn hàng
            return min($discount, $subtotal);
        } else {
            // Fixed discount - đảm bảo không giảm quá số tiền đơn hàng
            return min($this->discount_value, $subtotal);
        }
    }

    /**
     * Find and validate coupon by code
     */
    public static function validateCode(string $code, ?int $userId = null): ?self
    {
        $coupon = self::where('code', strtoupper(trim($code)))->first();

        if (!$coupon) {
            return null;
        }

        if ($coupon->isExpired()) {
            return null;
        }

        // Kiểm tra user có quyền dùng coupon không
        if (!$coupon->canBeUsedBy($userId)) {
            return null;
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
}
