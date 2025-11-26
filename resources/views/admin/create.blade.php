@extends('layouts.admin')

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
@endsection
