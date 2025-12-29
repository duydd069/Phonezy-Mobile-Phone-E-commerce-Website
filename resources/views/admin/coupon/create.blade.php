@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Thêm mã khuyến mãi mới</h2>
    <form action="{{ route('admin.coupons.store') }}" method="POST" novalidate>
        @csrf
        <div class="mb-3">
            <label>Mã khuyến mãi</label>
            <input type="text" name="code" class="form-control" value="{{ old('code') }}" required>
            @error('code')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="fw-bold">Loại mã khuyến mãi <span class="text-danger">*</span></label>
            <select name="type" id="coupon_type" class="form-control form-select" required>
                <option value="public" {{ old('type', 'public') === 'public' ? 'selected' : '' }}>Công khai (Mọi người dùng được sử dụng)</option>
                <option value="private" {{ old('type') === 'private' ? 'selected' : '' }}>Riêng tư (Chỉ một số user được chỉ định)</option>
            </select>
            <small class="form-text text-muted">
                <strong>Công khai:</strong> Bất kỳ khách hàng nào cũng có thể sử dụng.<br>
                <strong>Riêng tư:</strong> Chỉ những khách hàng được chọn ở bên dưới mới sử dụng được mã này.
            </small>
            @error('type')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="fw-bold">Phạm vi áp dụng <span class="text-danger">*</span></label>
            <select name="promotion_type" id="promotion_type" class="form-control form-select" required>
                <option value="order" {{ old('promotion_type', 'order') === 'order' ? 'selected' : '' }}>Cho đơn hàng (Áp dụng cho toàn bộ đơn hàng)</option>
                <option value="product" {{ old('promotion_type') === 'product' ? 'selected' : '' }}>Cho từng sản phẩm (Áp dụng cho các sản phẩm được chọn)</option>
            </select>
            <small class="form-text text-muted">
                <strong>Cho đơn hàng:</strong> Mã giảm áp dụng trên tổng tiền của cả đơn hàng.<br>
                <strong>Cho từng sản phẩm:</strong> Mã giảm chỉ áp dụng cho các sản phẩm được chọn bên dưới.
            </small>
            @error('promotion_type')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3" id="products_section" style="display: {{ old('promotion_type', 'order') === 'product' ? 'block' : 'none' }};">
            <label class="fw-bold">Sản phẩm áp dụng mã <span class="text-danger">*</span></label>
            <div class="alert alert-info">
                <i class="fa fa-info-circle"></i> <strong>Lưu ý:</strong> Bạn đã chọn "Cho từng sản phẩm". Vui lòng chọn ít nhất một sản phẩm để áp dụng khuyến mãi này.
            </div>
            <select name="product_ids[]" id="product_ids" class="form-control" multiple size="10">
                @foreach($allProducts as $product)
                    <option value="{{ $product->id }}" {{ collect(old('product_ids', []))->contains($product->id) ? 'selected' : '' }}>
                        {{ $product->name }} 
                        @if($product->category)
                            ({{ $product->category->name }})
                        @endif
                    </option>
                @endforeach
            </select>
            <small class="form-text text-muted">
                <i class="fa fa-mouse-pointer"></i> Giữ <kbd>Ctrl</kbd> (hoặc <kbd>Cmd</kbd> trên Mac) để chọn nhiều sản phẩm. 
                Chỉ các sản phẩm được chọn mới được áp dụng khuyến mãi này.
            </small>
            @error('product_ids')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3" id="users_section" style="display: {{ old('type', 'public') === 'private' ? 'block' : 'none' }};">
            <label>Khách hàng được phép sử dụng mã</label>
            <select name="user_ids[]" id="user_ids" class="form-control" multiple size="10">
                @foreach($allUsers as $user)
                    <option value="{{ $user->id }}" {{ collect(old('user_ids', []))->contains($user->id) ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>
            <small class="form-text text-muted">Giữ Ctrl (hoặc Cmd trên Mac) để chọn nhiều khách hàng</small>
            @error('user_ids')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label>Hình thức giảm giá</label>
            <select name="discount_type" id="discount_type" class="form-control">
                <option value="percent" {{ old('discount_type', 'percent') === 'percent' ? 'selected' : '' }}>Giảm theo %</option>
                <option value="fixed" {{ old('discount_type') === 'fixed' ? 'selected' : '' }}>Giảm theo số tiền</option>
            </select>
            @error('discount_type')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label>Giá trị giảm</label>
            <input type="number" name="discount_value" class="form-control" step="0.01" min="1" value="{{ old('discount_value') }}" required>
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
            <input type="number" name="min_order_value" class="form-control" step="0.01" min="0" value="{{ old('min_order_value') }}">
            <small class="form-text text-muted">Mã chỉ áp dụng khi tổng tiền hàng (chưa gồm phí ship) lớn hơn hoặc bằng số tiền này.</small>
            @error('min_order_value')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3" id="max_discount_section" style="display: {{ old('discount_type', 'percent') === 'percent' ? 'block' : 'none' }};">
            <label>Giảm tối đa (đ) <span class="text-danger">*</span> (bắt buộc với mã giảm theo %)</label>
            <input type="number" name="max_discount" class="form-control" step="0.01" min="0" value="{{ old('max_discount') }}">
            <small class="form-text text-muted">Giới hạn số tiền được giảm tối đa cho một đơn hàng, để tránh giảm quá lớn.</small>
            @error('max_discount')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label>Tổng số lần được sử dụng (toàn hệ thống)</label>
            <input type="number" name="usage_limit" class="form-control" min="1" value="{{ old('usage_limit') }}">
            <small class="form-text text-muted">Để trống nếu không giới hạn, hoặc nhập số lần tối đa toàn hệ thống.</small>
            @error('usage_limit')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label>Số lần mỗi khách hàng được dùng</label>
            <input type="number" name="usage_per_user" class="form-control" min="1" value="{{ old('usage_per_user') }}">
            <small class="form-text text-muted">Để trống nếu không giới hạn, hoặc nhập số lần tối đa cho mỗi khách hàng.</small>
            @error('usage_per_user')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label>Ngày bắt đầu áp dụng</label>
            <input type="datetime-local" name="starts_at" class="form-control" value="{{ old('starts_at') }}">
            <small class="form-text text-muted">Để trống nếu muốn mã có hiệu lực ngay sau khi tạo.</small>
            @error('starts_at')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label>Ngày kết thúc áp dụng</label>
            <input type="datetime-local" name="expires_at" class="form-control" value="{{ old('expires_at') }}">
            <small class="form-text text-muted">Để trống nếu muốn mã không giới hạn thời gian.</small>
            @error('expires_at')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Lưu</button>
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
        toggleUsersSection(); // Initialize on page load
        toggleProductsSection(); // Initialize on page load

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
