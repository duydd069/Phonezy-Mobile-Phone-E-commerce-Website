@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Thêm mã khuyến mãi</h2>
    <form action="{{ route('admin.coupons.store') }}" method="POST" novalidate>
        @csrf
        <div class="mb-3">
            <label>Mã code</label>
            <input type="text" name="code" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="fw-bold">Loại khuyến mãi (Phạm vi) <span class="text-danger">*</span></label>
            <select name="type" id="coupon_type" class="form-control form-select" required>
                <option value="public">Công khai (Mọi người dùng được sử dụng)</option>
                <option value="private">Riêng tư (Chỉ một số user được chỉ định)</option>
            </select>
            <small class="form-text text-muted">
                <strong>Công khai:</strong> Tất cả người dùng đều có thể sử dụng mã này.<br>
                <strong>Riêng tư:</strong> Chỉ những user được chọn ở phần bên dưới mới có thể sử dụng.
            </small>
        </div>

        <div class="mb-3">
            <label class="fw-bold">Áp dụng cho <span class="text-danger">*</span></label>
            <select name="promotion_type" id="promotion_type" class="form-control form-select" required>
                <option value="order">Cho đơn hàng (Áp dụng cho toàn bộ đơn hàng)</option>
                <option value="product">Cho từng sản phẩm (Áp dụng cho các sản phẩm được chọn)</option>
            </select>
            <small class="form-text text-muted">
                <strong>Cho đơn hàng:</strong> Giảm giá tính trên tổng giá trị đơn hàng.<br>
                <strong>Cho từng sản phẩm:</strong> Giảm giá tính trên từng sản phẩm được chọn. Bạn cần chọn các sản phẩm ở phần bên dưới.
            </small>
        </div>

        <div class="mb-3" id="products_section" style="display: none;">
            <label class="fw-bold">Chọn sản phẩm được áp dụng <span class="text-danger">*</span></label>
            <div class="alert alert-info">
                <i class="fa fa-info-circle"></i> <strong>Lưu ý:</strong> Bạn đã chọn "Cho từng sản phẩm". Vui lòng chọn ít nhất một sản phẩm để áp dụng khuyến mãi này.
            </div>
            <select name="product_ids[]" id="product_ids" class="form-control" multiple size="10">
                @foreach($allProducts as $product)
                    <option value="{{ $product->id }}">
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
        </div>

        <div class="mb-3" id="users_section" style="display: none;">
            <label>Chọn users được phép sử dụng</label>
            <select name="user_ids[]" id="user_ids" class="form-control" multiple size="10">
                @foreach($allUsers as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                @endforeach
            </select>
            <small class="form-text text-muted">Giữ Ctrl (hoặc Cmd trên Mac) để chọn nhiều user</small>
        </div>

        <div class="mb-3">
            <label>Loại giảm giá</label>
            <select name="discount_type" class="form-control">
                <option value="percent">Giảm theo %</option>
                <option value="fixed">Giảm theo số tiền</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Giá trị giảm</label>
            <input type="number" name="discount_value" class="form-control" step="0.01" min="1" required>
            <small class="form-text text-muted">Nếu chọn %, nhập số phần trăm (ví dụ: 20). Nếu chọn số tiền, nhập số tiền (ví dụ: 50000)</small>
        </div>

        <div class="mb-3">
            <label>Đơn hàng tối thiểu (đ)</label>
            <input type="number" name="min_order_value" class="form-control" step="0.01" min="0">
            <small class="form-text text-muted">Để trống nếu không có yêu cầu đơn hàng tối thiểu</small>
        </div>

        <div class="mb-3">
            <label>Giảm giá tối đa (đ) <span class="text-danger">*</span> (Chỉ áp dụng cho giảm theo %)</label>
            <input type="number" name="max_discount" class="form-control" step="0.01" min="0">
            <small class="form-text text-muted">Giới hạn số tiền giảm tối đa (quan trọng để tránh giảm quá nhiều). Để trống nếu không giới hạn</small>
        </div>

        <div class="mb-3">
            <label>Số lần sử dụng tối đa (toàn hệ thống)</label>
            <input type="number" name="usage_limit" class="form-control" min="1">
            <small class="form-text text-muted">Để trống nếu không giới hạn số lần sử dụng</small>
        </div>

        <div class="mb-3">
            <label>Số lần sử dụng mỗi người</label>
            <input type="number" name="usage_per_user" class="form-control" min="1">
            <small class="form-text text-muted">Để trống nếu không giới hạn số lần sử dụng mỗi user</small>
        </div>

        <div class="mb-3">
            <label>Ngày bắt đầu</label>
            <input type="datetime-local" name="starts_at" class="form-control">
            <small class="form-text text-muted">Để trống nếu muốn khuyến mãi có hiệu lực ngay lập tức</small>
        </div>

        <div class="mb-3">
            <label>Ngày hết hạn</label>
            <input type="datetime-local" name="expires_at" class="form-control">
            <small class="form-text text-muted">Để trống nếu khuyến mãi không có thời hạn</small>
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
    });
</script>
@endsection
