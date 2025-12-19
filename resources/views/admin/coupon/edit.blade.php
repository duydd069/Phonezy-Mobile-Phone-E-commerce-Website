@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Sửa mã khuyến mãi: {{ $coupon->code }}</h2>

    <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Mã code</label>
            <input type="text" name="code" class="form-control" value="{{ old('code', $coupon->code) }}" required>
        </div>

        <div class="mb-3">
            <label>Loại khuyến mãi (Phạm vi)</label>
            <select name="type" id="coupon_type" class="form-control" required>
                <option value="public" {{ ($coupon->type ?? 'public') == 'public' ? 'selected' : '' }}>Công khai (Mọi người dùng được)</option>
                <option value="private" {{ ($coupon->type ?? 'public') == 'private' ? 'selected' : '' }}>Riêng tư (Chỉ một số user)</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Áp dụng cho</label>
            <select name="promotion_type" id="promotion_type" class="form-control" required>
                <option value="order" {{ ($coupon->promotion_type ?? 'order') == 'order' ? 'selected' : '' }}>Cho đơn hàng</option>
                <option value="product" {{ ($coupon->promotion_type ?? 'order') == 'product' ? 'selected' : '' }}>Cho từng sản phẩm</option>
            </select>
            <small class="form-text text-muted">Chọn "Cho đơn hàng" để áp dụng giảm giá cho toàn bộ đơn hàng, "Cho từng sản phẩm" để áp dụng cho từng sản phẩm riêng lẻ</small>
        </div>

        <div class="mb-3" id="products_section" style="display: {{ ($coupon->promotion_type ?? 'order') == 'product' ? 'block' : 'none' }};">
            <label>Chọn sản phẩm được áp dụng <span class="text-danger">*</span></label>
            <select name="product_ids[]" id="product_ids" class="form-control" multiple size="10">
                @foreach($allProducts as $product)
                    <option value="{{ $product->id }}" {{ $coupon->products->contains($product->id) ? 'selected' : '' }}>
                        {{ $product->name }} 
                        @if($product->category)
                            ({{ $product->category->name }})
                        @endif
                    </option>
                @endforeach
            </select>
            <small class="form-text text-muted">Giữ Ctrl (hoặc Cmd trên Mac) để chọn nhiều sản phẩm. Chỉ các sản phẩm được chọn mới được áp dụng khuyến mãi này.</small>
            @if($coupon->products->count() > 0)
                <div class="mt-2">
                    <strong>Đã chọn {{ $coupon->products->count() }} sản phẩm:</strong>
                    <ul class="list-unstyled">
                        @foreach($coupon->products as $product)
                            <li>- {{ $product->name }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div class="mb-3" id="users_section" style="display: {{ ($coupon->type ?? 'public') == 'private' ? 'block' : 'none' }};">
            <label>Chọn users được phép sử dụng</label>
            <select name="user_ids[]" id="user_ids" class="form-control" multiple size="10">
                @foreach($allUsers as $user)
                    <option value="{{ $user->id }}" {{ $coupon->users->contains($user->id) ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>
            <small class="form-text text-muted">Giữ Ctrl (hoặc Cmd trên Mac) để chọn nhiều user</small>
            @if($coupon->users->count() > 0)
                <div class="mt-2">
                    <strong>Đã chọn {{ $coupon->users->count() }} user(s):</strong>
                    <ul class="list-unstyled">
                        @foreach($coupon->users as $user)
                            <li>- {{ $user->name }} ({{ $user->email }})</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label>Loại giảm giá</label>
            <select name="discount_type" class="form-control">
                <option value="percent" {{ $coupon->discount_type == 'percent' ? 'selected' : '' }}>Giảm theo %</option>
                <option value="fixed" {{ $coupon->discount_type == 'fixed' ? 'selected' : '' }}>Giảm theo số tiền</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Giá trị giảm</label>
            <input type="number" name="discount_value" class="form-control" step="0.01" min="1" value="{{ old('discount_value', $coupon->discount_value) }}" required>
            <small class="form-text text-muted">Nếu chọn %, nhập số phần trăm (ví dụ: 20). Nếu chọn số tiền, nhập số tiền (ví dụ: 50000)</small>
        </div>

        <div class="mb-3">
            <label>Đơn hàng tối thiểu (đ)</label>
            <input type="number" name="min_order_value" class="form-control" step="0.01" min="0" value="{{ old('min_order_value', $coupon->min_order_value) }}">
            <small class="form-text text-muted">Để trống nếu không có yêu cầu đơn hàng tối thiểu</small>
        </div>

        <div class="mb-3">
            <label>Giảm giá tối đa (đ) (Chỉ áp dụng cho giảm theo %)</label>
            <input type="number" name="max_discount" class="form-control" step="0.01" min="0" value="{{ old('max_discount', $coupon->max_discount) }}">
            <small class="form-text text-muted">Giới hạn số tiền giảm tối đa (quan trọng để tránh giảm quá nhiều). Để trống nếu không giới hạn</small>
        </div>

        <div class="mb-3">
            <label>Số lần sử dụng tối đa (toàn hệ thống)</label>
            <input type="number" name="usage_limit" class="form-control" min="1" value="{{ old('usage_limit', $coupon->usage_limit) }}">
            <small class="form-text text-muted">Đã sử dụng: {{ $coupon->used_count ?? 0 }} lần. Để trống nếu không giới hạn số lần sử dụng</small>
        </div>

        <div class="mb-3">
            <label>Số lần sử dụng mỗi người</label>
            <input type="number" name="usage_per_user" class="form-control" min="1" value="{{ old('usage_per_user', $coupon->usage_per_user) }}">
            <small class="form-text text-muted">Để trống nếu không giới hạn số lần sử dụng mỗi user</small>
        </div>

        <div class="mb-3">
            <label>Ngày bắt đầu</label>
            <input type="datetime-local" name="starts_at" class="form-control" value="{{ $coupon->starts_at ? $coupon->starts_at->format('Y-m-d\TH:i') : '' }}">
            <small class="form-text text-muted">Để trống nếu muốn khuyến mãi có hiệu lực ngay lập tức</small>
        </div>

        <div class="mb-3">
            <label>Ngày hết hạn</label>
            <input type="datetime-local" name="expires_at" class="form-control" value="{{ $coupon->expires_at ? $coupon->expires_at->format('Y-m-d\TH:i') : '' }}">
            <small class="form-text text-muted">Để trống nếu khuyến mãi không có thời hạn</small>
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('coupon_type');
        const promotionTypeSelect = document.getElementById('promotion_type');
        const usersSection = document.getElementById('users_section');
        const productsSection = document.getElementById('products_section');
        const userIdsSelect = document.getElementById('user_ids');
        const productIdsSelect = document.getElementById('product_ids');

        function toggleUsersSection() {
            if (typeSelect.value === 'private') {
                usersSection.style.display = 'block';
                userIdsSelect.setAttribute('required', 'required');
            } else {
                usersSection.style.display = 'none';
                userIdsSelect.removeAttribute('required');
            }
        }

        function toggleProductsSection() {
            if (promotionTypeSelect.value === 'product') {
                productsSection.style.display = 'block';
                productIdsSelect.setAttribute('required', 'required');
            } else {
                productsSection.style.display = 'none';
                productIdsSelect.removeAttribute('required');
            }
        }

        typeSelect.addEventListener('change', toggleUsersSection);
        promotionTypeSelect.addEventListener('change', toggleProductsSection);
    });
</script>
@endsection
