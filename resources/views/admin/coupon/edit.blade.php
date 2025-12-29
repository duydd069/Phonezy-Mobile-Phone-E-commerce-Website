@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Chỉnh sửa mã khuyến mãi: {{ $coupon->code }}</h2>

    <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Mã khuyến mãi</label>
            <input type="text" name="code" class="form-control" value="{{ old('code', $coupon->code) }}" required>
            @error('code')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label>Loại mã khuyến mãi</label>
            <select name="type" id="coupon_type" class="form-control" required>
                @php $oldType = old('type', $coupon->type ?? 'public'); @endphp
                <option value="public" {{ $oldType == 'public' ? 'selected' : '' }}>Công khai (bất kỳ khách hàng nào cũng dùng được)</option>
                <option value="private" {{ $oldType == 'private' ? 'selected' : '' }}>Riêng tư (chỉ khách hàng được chọn)</option>
            </select>
            @error('type')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label>Phạm vi áp dụng</label>
            <select name="promotion_type" id="promotion_type" class="form-control" required>
                @php $oldPromotionType = old('promotion_type', $coupon->promotion_type ?? 'order'); @endphp
                <option value="order" {{ $oldPromotionType == 'order' ? 'selected' : '' }}>Cho toàn bộ đơn hàng</option>
                <option value="product" {{ $oldPromotionType == 'product' ? 'selected' : '' }}>Cho từng sản phẩm</option>
            </select>
            <small class="form-text text-muted">
                Chọn <strong>Cho toàn bộ đơn hàng</strong> nếu mã giảm áp dụng trên tổng tiền đơn.<br>
                Chọn <strong>Cho từng sản phẩm</strong> nếu chỉ muốn giảm cho một số sản phẩm nhất định.
            </small>
            @error('promotion_type')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3" id="products_section" style="display: {{ old('promotion_type', $coupon->promotion_type ?? 'order') == 'product' ? 'block' : 'none' }};">
            <label>Sản phẩm áp dụng mã <span class="text-danger">*</span></label>
            <select name="product_ids[]" id="product_ids" class="form-control" multiple size="10">
                @foreach($allProducts as $product)
                    <option value="{{ $product->id }}" {{ collect(old('product_ids', $coupon->products->pluck('id')->toArray()))->contains($product->id) ? 'selected' : '' }}>
                        {{ $product->name }} 
                        @if($product->category)
                            ({{ $product->category->name }})
                        @endif
                    </option>
                @endforeach
            </select>
            <small class="form-text text-muted">Giữ Ctrl (hoặc Cmd trên Mac) để chọn nhiều sản phẩm. Chỉ các sản phẩm được chọn mới được giảm giá.</small>
            @error('product_ids')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
            @if($coupon->products->count() > 0 && !old('product_ids'))
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

        <div class="mb-3" id="users_section" style="display: {{ old('type', $coupon->type ?? 'public') == 'private' ? 'block' : 'none' }};">
            <label>Khách hàng được phép sử dụng mã</label>
            <select name="user_ids[]" id="user_ids" class="form-control" multiple size="10">
                @foreach($allUsers as $user)
                    <option value="{{ $user->id }}" {{ collect(old('user_ids', $coupon->users->pluck('id')->toArray()))->contains($user->id) ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>
            <small class="form-text text-muted">Giữ Ctrl (hoặc Cmd trên Mac) để chọn nhiều khách hàng.</small>
            @error('user_ids')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
            @if($coupon->users->count() > 0 && !old('user_ids'))
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
            <label>Hình thức giảm giá</label>
            <select name="discount_type" id="discount_type" class="form-control">
                @php $oldDiscountType = old('discount_type', $coupon->discount_type); @endphp
                <option value="percent" {{ $oldDiscountType == 'percent' ? 'selected' : '' }}>Giảm theo %</option>
                <option value="fixed" {{ $oldDiscountType == 'fixed' ? 'selected' : '' }}>Giảm theo số tiền</option>
            </select>
            @error('discount_type')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label>Giá trị giảm</label>
            <input type="number" name="discount_value" class="form-control" step="0.01" min="1" value="{{ old('discount_value', $coupon->discount_value) }}" required>
            <small class="form-text text-muted">
                Nếu chọn <strong>%</strong>: nhập số phần trăm (ví dụ: 20 nghĩa là giảm 20%).<br>
                Nếu chọn <strong>số tiền</strong>: nhập số tiền muốn giảm (ví dụ: 50000 nghĩa là giảm 50.000đ).
            </small>
            @error('discount_value')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label>Giá trị đơn hàng tối thiểu (đ)</label>
            <input type="number" name="min_order_value" class="form-control" step="0.01" min="0" value="{{ old('min_order_value', $coupon->min_order_value) }}">
            <small class="form-text text-muted">Mã chỉ áp dụng khi tổng tiền hàng (chưa gồm phí ship) lớn hơn hoặc bằng số tiền này.</small>
            @error('min_order_value')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3" id="max_discount_section" style="display: {{ old('discount_type', $coupon->discount_type) === 'percent' ? 'block' : 'none' }};">
            <label>Giảm tối đa (đ) (chỉ dùng cho mã giảm theo %)</label>
            <input type="number" name="max_discount" class="form-control" step="0.01" min="0" value="{{ old('max_discount', $coupon->max_discount) }}">
            <small class="form-text text-muted">Số tiền tối đa được giảm cho một đơn hàng, để tránh giảm quá lớn.</small>
            @error('max_discount')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label>Tổng số lần được sử dụng (toàn hệ thống)</label>
            <input type="number" name="usage_limit" class="form-control" min="1" value="{{ old('usage_limit', $coupon->usage_limit) }}">
            <small class="form-text text-muted">Đã sử dụng: {{ $coupon->used_count ?? 0 }} lần. Để trống nếu không giới hạn số lần sử dụng.</small>
            @error('usage_limit')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label>Số lần mỗi khách hàng được dùng</label>
            <input type="number" name="usage_per_user" class="form-control" min="1" value="{{ old('usage_per_user', $coupon->usage_per_user) }}">
            <small class="form-text text-muted">Để trống nếu không giới hạn, hoặc nhập số lần tối đa cho mỗi khách hàng.</small>
            @error('usage_per_user')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label>Ngày bắt đầu áp dụng</label>
            <input type="datetime-local" name="starts_at" class="form-control" value="{{ old('starts_at', $coupon->starts_at ? $coupon->starts_at->format('Y-m-d\TH:i') : '') }}">
            <small class="form-text text-muted">Để trống nếu muốn mã có hiệu lực ngay sau khi lưu.</small>
            @error('starts_at')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label>Ngày kết thúc áp dụng</label>
            <input type="datetime-local" name="expires_at" class="form-control" value="{{ old('expires_at', $coupon->expires_at ? $coupon->expires_at->format('Y-m-d\TH:i') : '') }}">
            <small class="form-text text-muted">Để trống nếu muốn mã không giới hạn thời gian.</small>
            @error('expires_at')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
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

        // Toggle max_discount section based on discount_type
        const discountTypeSelect = document.getElementById('discount_type');
        const maxDiscountSection = document.getElementById('max_discount_section');
        
        function toggleMaxDiscountSection() {
            if (discountTypeSelect.value === 'percent') {
                maxDiscountSection.style.display = 'block';
            } else {
                maxDiscountSection.style.display = 'none';
            }
        }
        
        discountTypeSelect.addEventListener('change', toggleMaxDiscountSection);
        toggleMaxDiscountSection(); // Initialize on page load
    });
</script>
@endsection
