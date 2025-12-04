<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\User;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::with('users')->latest()->paginate(10);
        return view('admin.coupon.index', compact('coupons'));
    }

    public function create()
    {
        $allUsers = User::orderBy('name')->get();
        return view('admin.coupon.create', compact('allUsers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:coupons,code',
            'type' => 'required|in:public,private',
            'discount_type' => 'required|in:percent,fixed',
            'discount_value' => 'required|numeric|min:1',
            'expires_at' => 'nullable|date',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        $data = $request->all();
        if (empty($data['expires_at'])) {
            $data['expires_at'] = null;
        }

        $userIds = $data['user_ids'] ?? [];
        unset($data['user_ids']);

        $coupon = Coupon::create($data);

        // Nếu là private coupon, gán users
        if ($coupon->isPrivate() && !empty($userIds)) {
            $coupon->users()->sync($userIds);
        }

        return redirect()->route('admin.coupons.index')->with('success', 'Tạo mã khuyến mãi thành công!');
    }

    public function edit(Coupon $coupon)
    {
        $coupon->load('users');
        $allUsers = User::orderBy('name')->get();
        return view('admin.coupon.edit', compact('coupon', 'allUsers'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'code' => 'required|unique:coupons,code,' . $coupon->id,
            'type' => 'required|in:public,private',
            'discount_type' => 'required|in:percent,fixed',
            'discount_value' => 'required|numeric|min:1',
            'expires_at' => 'nullable|date',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        $data = $request->all();
        if (empty($data['expires_at'])) {
            $data['expires_at'] = null;
        }

        $userIds = $data['user_ids'] ?? [];
        unset($data['user_ids']);

        $coupon->update($data);

        // Xử lý users cho private coupon
        if ($coupon->isPrivate()) {
            $coupon->users()->sync($userIds);
        } else {
            // Nếu chuyển từ private sang public, xóa tất cả users
            $coupon->users()->detach();
        }

        return redirect()->route('admin.coupons.index')->with('success', 'Cập nhật mã khuyến mãi thành công!');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('admin.coupons.index')->with('success', 'Đã xóa mã khuyến mãi!');
    }
}
