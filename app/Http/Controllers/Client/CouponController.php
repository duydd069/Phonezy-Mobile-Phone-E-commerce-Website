<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Hiển thị danh sách coupons mà user có thể sử dụng
     */
    public function index()
    {
        $user = auth()->user();
        $userId = $user ? $user->id : null;

        // Lấy tất cả public coupons (chưa hết hạn)
        // Kiểm tra nếu cột type tồn tại, nếu không thì lấy tất cả (backward compatibility)
        $publicCoupons = Coupon::where(function ($query) {
                $query->where('type', 'public')
                      ->orWhereNull('type'); // Cho phép coupons cũ không có type
            })
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Lấy private coupons được gán cho user này
        $privateCoupons = Coupon::where('type', 'private')
            ->whereHas('users', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Gộp tất cả coupons
        $allCoupons = $publicCoupons->merge($privateCoupons);

        return view('electro.coupons.index', compact('allCoupons', 'publicCoupons', 'privateCoupons'));
    }
}
