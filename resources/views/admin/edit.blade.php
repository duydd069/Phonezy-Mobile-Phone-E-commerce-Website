@extends('layouts.admin')

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
@endsection
