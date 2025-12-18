<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::with('users', 'products')->latest()->paginate(10);
        return view('admin.coupon.index', compact('coupons'));
    }

    public function create()
    {
        $allUsers = User::orderBy('name')->get();
        $allProducts = Product::with('category')->orderBy('name')->get();
        return view('admin.coupon.create', compact('allUsers', 'allProducts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:coupons,code',
            'type' => 'required|in:public,private',
            'promotion_type' => 'required|in:order,product',
            'discount_type' => 'required|in:percent,fixed',
            'discount_value' => 'required|numeric|min:1',
            'min_order_value' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_per_user' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
            'product_ids' => 'required_if:promotion_type,product|array',
            'product_ids.*' => 'exists:products,id'
        ]);

        $data = $request->all();
        if (empty($data['starts_at'])) {
            $data['starts_at'] = null;
        }
        if (empty($data['expires_at'])) {
            $data['expires_at'] = null;
        }

        $userIds = $data['user_ids'] ?? [];
        $productIds = $data['product_ids'] ?? [];
        unset($data['user_ids'], $data['product_ids']);

        $coupon = Coupon::create($data);

        // Nếu là private coupon, gán users
        if ($coupon->isPrivate() && !empty($userIds)) {
            $coupon->users()->sync($userIds);
        }

        // Nếu là product-level coupon, gán products
        if ($coupon->isForProduct() && !empty($productIds)) {
            $coupon->products()->sync($productIds);
        }

        return redirect()->route('admin.coupons.index')->with('success', 'Tạo mã khuyến mãi thành công!');
    }

    public function edit(Coupon $coupon)
    {
        $coupon->load('users', 'products');
        $allUsers = User::orderBy('name')->get();
        $allProducts = Product::with('category')->orderBy('name')->get();
        return view('admin.coupon.edit', compact('coupon', 'allUsers', 'allProducts'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'code' => 'required|unique:coupons,code,' . $coupon->id,
            'type' => 'required|in:public,private',
            'promotion_type' => 'required|in:order,product',
            'discount_type' => 'required|in:percent,fixed',
            'discount_value' => 'required|numeric|min:1',
            'min_order_value' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_per_user' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
            'product_ids' => 'required_if:promotion_type,product|array',
            'product_ids.*' => 'exists:products,id'
        ]);

        $data = $request->all();
        if (empty($data['starts_at'])) {
            $data['starts_at'] = null;
        }
        if (empty($data['expires_at'])) {
            $data['expires_at'] = null;
        }

        $userIds = $data['user_ids'] ?? [];
        $productIds = $data['product_ids'] ?? [];
        unset($data['user_ids'], $data['product_ids']);

        $coupon->update($data);

        // Xử lý users cho private coupon
        if ($coupon->isPrivate()) {
            $coupon->users()->sync($userIds);
        } else {
            // Nếu chuyển từ private sang public, xóa tất cả users
            $coupon->users()->detach();
        }

        // Xử lý products cho product-level coupon
        if ($coupon->isForProduct()) {
            $coupon->products()->sync($productIds ?? []);
        } else {
            // Nếu chuyển từ product sang order, xóa tất cả products
            $coupon->products()->detach();
        }

        return redirect()->route('admin.coupons.index')->with('success', 'Cập nhật mã khuyến mãi thành công!');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('admin.coupons.index')->with('success', 'Đã xóa mã khuyến mãi!');
    }
}
