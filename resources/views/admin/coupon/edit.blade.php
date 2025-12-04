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
            <label>Loại khuyến mãi</label>
            <select name="type" id="coupon_type" class="form-control" required>
                <option value="public" {{ ($coupon->type ?? 'public') == 'public' ? 'selected' : '' }}>Công khai (Mọi người dùng được)</option>
                <option value="private" {{ ($coupon->type ?? 'public') == 'private' ? 'selected' : '' }}>Riêng tư (Chỉ một số user)</option>
            </select>
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
            <input type="number" name="discount_value" class="form-control" value="{{ old('discount_value', $coupon->discount_value) }}" required>
        </div>

        <div class="mb-3">
            <label>Ngày hết hạn</label>
            <input type="date" name="expires_at" class="form-control" value="{{ $coupon->expires_at ? $coupon->expires_at->format('Y-m-d') : '' }}">
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">Quay lại</a>
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
    });
</script>
@endsection
