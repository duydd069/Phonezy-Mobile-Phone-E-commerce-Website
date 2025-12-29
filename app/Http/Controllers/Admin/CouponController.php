<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
        // Tên hiển thị tiếng Việt cho các trường
        $attributes = [
            'code'            => 'mã khuyến mãi',
            'type'            => 'loại mã (công khai/riêng tư)',
            'promotion_type'  => 'phạm vi khuyến mãi',
            'discount_type'   => 'kiểu giảm giá',
            'discount_value'  => 'giá trị giảm',
            'min_order_value' => 'giá trị đơn hàng tối thiểu',
            'max_discount'    => 'giảm tối đa',
            'usage_limit'     => 'giới hạn số lần sử dụng',
            'usage_per_user'  => 'giới hạn số lần sử dụng mỗi người',
            'starts_at'       => 'ngày bắt đầu',
            'expires_at'      => 'ngày hết hạn',
            'user_ids'        => 'danh sách người dùng',
            'product_ids'     => 'danh sách sản phẩm áp dụng',
        ];

        // Thông báo lỗi tiếng Việt cho các rule quan trọng
        $messages = [
            'code.required'    => 'Vui lòng nhập :attribute.',
            'code.unique'      => ':attribute đã tồn tại, vui lòng dùng mã khác.',

            'discount_type.required' => 'Vui lòng chọn :attribute.',
            'discount_type.in'       => ':attribute không hợp lệ.',

            'promotion_type.required' => 'Vui lòng chọn :attribute.',
            'promotion_type.in'       => ':attribute không hợp lệ.',

            'discount_value.required' => 'Vui lòng nhập :attribute.',
            'discount_value.numeric'  => ':attribute phải là một số.',
            'discount_value.min'      => ':attribute phải lớn hơn 0.',
            'discount_value.lt'       => 'Giá trị giảm phải nhỏ hơn giá trị đơn hàng tối thiểu để tránh giảm vượt quá giới hạn cho phép.',

            'min_order_value.required' => 'Vui lòng nhập :attribute.',
            'min_order_value.numeric'  => ':attribute phải là một số.',
            'min_order_value.min'      => ':attribute không được âm.',

            'max_discount.required' => 'Vui lòng nhập :attribute cho mã giảm theo %.',
            'max_discount.numeric'  => ':attribute phải là một số.',
            'max_discount.min'      => ':attribute phải lớn hơn hoặc bằng 0.',
            'max_discount.lt'       => ':attribute phải nhỏ hơn :value để đảm bảo không giảm vượt tổng tiền đơn.',

            'product_ids.required_if' => 'Vui lòng chọn ít nhất một sản phẩm áp dụng khi tạo khuyến mãi cho từng sản phẩm.',

            'starts_at.date'  => ':attribute không đúng định dạng ngày giờ.',
            'expires_at.date' => ':attribute không đúng định dạng ngày giờ.',
            'expires_at.after_or_equal' => 'Ngày hết hạn phải lớn hơn hoặc bằng ngày bắt đầu.',
        ];

        $baseRules = [
            'code' => 'required|unique:coupons,code',
            'type' => 'required|in:public,private',
            'promotion_type' => 'required|in:order,product',
            'discount_type' => ['required', Rule::in(['percent', 'fixed'])],
            'usage_limit' => 'nullable|integer|min:1',
            'usage_per_user' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
            'product_ids' => 'required_if:promotion_type,product|array',
            'product_ids.*' => 'exists:products,id',
        ];

        // Áp dụng validation chi tiết theo loại giảm giá
        if ($request->input('discount_type') === 'percent') {
            // Giảm theo %: bắt buộc có max_discount, có min_order_value
            $typeSpecificRules = [
                'discount_value'   => 'required|numeric|min:1|max:100',
                'min_order_value'  => 'required|numeric|min:0',
                // max_discount < min_order_value để đảm bảo không có case giảm ≥ min_order
                'max_discount'     => 'required|numeric|min:1|lt:min_order_value',
            ];
        } else {
            // Giảm tiền cố định: discount_value < min_order_value
            $typeSpecificRules = [
                'discount_value'   => 'required|numeric|min:1|lt:min_order_value',
                'min_order_value'  => 'required|numeric|min:0',
                // max_discount nếu có cũng phải < min_order_value
                'max_discount'     => 'nullable|numeric|min:0|lt:min_order_value',
            ];
        }

        $request->validate(
            array_merge($baseRules, $typeSpecificRules),
            $messages,
            $attributes
        );

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
        $attributes = [
            'code'            => 'mã khuyến mãi',
            'type'            => 'loại mã (công khai/riêng tư)',
            'promotion_type'  => 'phạm vi khuyến mãi',
            'discount_type'   => 'kiểu giảm giá',
            'discount_value'  => 'giá trị giảm',
            'min_order_value' => 'giá trị đơn hàng tối thiểu',
            'max_discount'    => 'giảm tối đa',
            'usage_limit'     => 'giới hạn số lần sử dụng',
            'usage_per_user'  => 'giới hạn số lần sử dụng mỗi người',
            'starts_at'       => 'ngày bắt đầu',
            'expires_at'      => 'ngày hết hạn',
            'user_ids'        => 'danh sách người dùng',
            'product_ids'     => 'danh sách sản phẩm áp dụng',
        ];

        $messages = [
            'code.required'    => 'Vui lòng nhập :attribute.',
            'code.unique'      => ':attribute đã tồn tại, vui lòng dùng mã khác.',

            'discount_type.required' => 'Vui lòng chọn :attribute.',
            'discount_type.in'       => ':attribute không hợp lệ.',

            'promotion_type.required' => 'Vui lòng chọn :attribute.',
            'promotion_type.in'       => ':attribute không hợp lệ.',

            'discount_value.required' => 'Vui lòng nhập :attribute.',
            'discount_value.numeric'  => ':attribute phải là một số.',
            'discount_value.min'      => ':attribute phải lớn hơn 0.',
            'discount_value.lt'       => 'Giá trị giảm phải nhỏ hơn giá trị đơn hàng tối thiểu để tránh giảm vượt quá giới hạn cho phép.',

            'min_order_value.required' => 'Vui lòng nhập :attribute.',
            'min_order_value.numeric'  => ':attribute phải là một số.',
            'min_order_value.min'      => ':attribute không được âm.',

            'max_discount.required' => 'Vui lòng nhập :attribute cho mã giảm theo %.',
            'max_discount.numeric'  => ':attribute phải là một số.',
            'max_discount.min'      => ':attribute phải lớn hơn hoặc bằng 0.',
            'max_discount.lt'       => ':attribute phải nhỏ hơn :value để đảm bảo không giảm vượt tổng tiền đơn.',

            'product_ids.required_if' => 'Vui lòng chọn ít nhất một sản phẩm áp dụng khi tạo khuyến mãi cho từng sản phẩm.',

            'starts_at.date'  => ':attribute không đúng định dạng ngày giờ.',
            'expires_at.date' => ':attribute không đúng định dạng ngày giờ.',
            'expires_at.after_or_equal' => 'Ngày hết hạn phải lớn hơn hoặc bằng ngày bắt đầu.',
        ];

        $baseRules = [
            'code' => 'required|unique:coupons,code,' . $coupon->id,
            'type' => 'required|in:public,private',
            'promotion_type' => 'required|in:order,product',
            'discount_type' => ['required', Rule::in(['percent', 'fixed'])],
            'usage_limit' => 'nullable|integer|min:1',
            'usage_per_user' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
            'product_ids' => 'required_if:promotion_type,product|array',
            'product_ids.*' => 'exists:products,id',
        ];

        if ($request->input('discount_type') === 'percent') {
            $typeSpecificRules = [
                'discount_value'   => 'required|numeric|min:1|max:100',
                'min_order_value'  => 'required|numeric|min:0',
                'max_discount'     => 'required|numeric|min:1|lt:min_order_value',
            ];
        } else {
            $typeSpecificRules = [
                'discount_value'   => 'required|numeric|min:1|lt:min_order_value',
                'min_order_value'  => 'required|numeric|min:0',
                'max_discount'     => 'nullable|numeric|min:0|lt:min_order_value',
            ];
        }

        $request->validate(
            array_merge($baseRules, $typeSpecificRules),
            $messages,
            $attributes
        );

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
