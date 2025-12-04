@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Thêm mã khuyến mãi</h2>
    <form action="{{ route('admin.coupons.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Mã code</label>
            <input type="text" name="code" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Loại khuyến mãi</label>
            <select name="type" id="coupon_type" class="form-control" required>
                <option value="public">Công khai (Mọi người dùng được)</option>
                <option value="private">Riêng tư (Chỉ một số user)</option>
            </select>
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
            <input type="number" name="discount_value" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Ngày hết hạn</label>
            <input type="date" name="expires_at" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Lưu</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('coupon_type');
        const usersSection = document.getElementById('users_section');
        const userIdsSelect = document.getElementById('user_ids');

        function toggleUsersSection() {
            if (typeSelect.value === 'private') {
                usersSection.style.display = 'block';
                userIdsSelect.setAttribute('required', 'required');
            } else {
                usersSection.style.display = 'none';
                userIdsSelect.removeAttribute('required');
            }
        }

        typeSelect.addEventListener('change', toggleUsersSection);
        toggleUsersSection(); // Initialize on page load
    });
</script>
@endsection
