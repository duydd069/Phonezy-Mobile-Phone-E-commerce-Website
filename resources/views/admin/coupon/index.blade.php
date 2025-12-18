@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Danh sách mã khuyến mãi</h2>
    <a href="{{ route('admin.coupons.create') }}" class="btn btn-success mb-3">+ Thêm khuyến mãi</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Mã</th>
                <th>Loại</th>
                <th>Áp dụng cho</th>
                <th>Loại giảm</th>
                <th>Giá trị</th>
                <th>Ngày bắt đầu</th>
                <th>Ngày hết hạn</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
        @forelse($coupons as $coupon)
            <tr>
                <td>{{ $coupon->id }}</td>
                <td><strong>{{ $coupon->code }}</strong></td>
                <td>
                    @if(($coupon->type ?? 'public') == 'private')
                        <span class="badge bg-warning">Riêng tư</span>
                        @if($coupon->users->count() > 0)
                            <br><small>({{ $coupon->users->count() }} user)</small>
                        @endif
                    @else
                        <span class="badge bg-success">Công khai</span>
                    @endif
                </td>
                <td>
                    @if(($coupon->promotion_type ?? 'order') == 'order')
                        <span class="badge bg-info">Đơn hàng</span>
                    @else
                        <span class="badge bg-primary">Sản phẩm</span>
                        @if($coupon->products->count() > 0)
                            <br><small>({{ $coupon->products->count() }} sản phẩm)</small>
                        @endif
                    @endif
                </td>
                <td>{{ $coupon->discount_type === 'percent' ? 'Giảm %' : 'Giảm tiền' }}</td>
                <td>
                    @if($coupon->discount_type === 'percent')
                        {{ rtrim(rtrim(number_format($coupon->discount_value, 2), '0'), '.') }}%
                    @else
                        {{ number_format($coupon->discount_value, 0, ',', '.') }}đ
                    @endif
                </td>
                <td>
                    @if($coupon->starts_at)
                        {{ $coupon->starts_at->format('d/m/Y H:i') }}
                    @else
                        <span class="text-muted">Ngay lập tức</span>
                    @endif
                </td>
                <td>
                    @if($coupon->expires_at)
                        {{ $coupon->expires_at->format('d/m/Y H:i') }}
                    @else
                        <span class="text-muted">Không có</span>
                    @endif
                </td>
                <td>
                    @if($coupon->isValid())
                        <span class="badge bg-success">Đang hoạt động</span>
                    @elseif($coupon->starts_at && !$coupon->hasStarted())
                        <span class="badge bg-secondary">Chưa bắt đầu</span>
                    @else
                        <span class="badge bg-danger">Đã hết hạn</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="btn btn-primary btn-sm">Sửa</a>
                    <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Bạn chắc chắn muốn xóa mã này?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="10" class="text-center">Chưa có mã khuyến mãi nào</td></tr>
        @endforelse
        </tbody>
    </table>

    {{ $coupons->links() }}
</div>
@endsection
