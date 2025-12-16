@extends('layouts.app')

@section('content')
<div class="container-fluid p-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="m-0">
            <i class="fa fa-shopping-cart"></i> Quản Lý Đơn Hàng
            @if($orders->total() > 0)
                <small class="text-muted">({{ $orders->total() }} đơn hàng)</small>
            @endif
        </h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Filter and Search --}}
    <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-2 mb-3">
        <div class="col-auto">
            <input type="text" name="q" value="{{ request('q') }}" class="form-control" 
                   placeholder="Tìm kiếm theo ID, tên, số điện thoại, email...">
        </div>
        <div class="col-auto">
            <select name="status" class="form-select">
                <option value="">Tất cả trạng thái</option>
                @php
                    $allStatuses = \App\Models\Order::getAvailableStatuses();
                @endphp
                @foreach($allStatuses as $statusKey => $statusLabel)
                    <option value="{{ $statusKey }}" {{ request('status') === $statusKey ? 'selected' : '' }}>
                        {{ $statusLabel }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <select name="payment_status" class="form-select">
                <option value="">Tất cả trạng thái thanh toán</option>
                <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Chờ thanh toán</option>
                <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Thanh toán thất bại</option>
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-outline-primary">Tìm kiếm</button>
            @if(request('q') || request('status') || request('payment_status'))
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">Xóa bộ lọc</a>
            @endif
        </div>
    </form>

    {{-- Orders Table --}}
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>Số điện thoại</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Thanh toán</th>
                    <th>Ngày đặt</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td><strong>#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</strong></td>
                        <td>
                            <div>{{ $order->shipping_full_name }}</div>
                            @if($order->shipping_email)
                                <small class="text-muted">{{ $order->shipping_email }}</small>
                            @endif
                            @if($order->user)
                                <small class="text-muted d-block">Mã người dùng: {{ $order->user_id }}</small>
                            @endif
                        </td>
                        <td>{{ $order->shipping_phone }}</td>
                        <td>
                            <strong style="color: #F7941D;">
                                {{ number_format($order->total, 0, ',', '.') }} ₫
                            </strong>
                        </td>
                        <td>
                            <span class="badge {{ $order->status_badge_class }}" style="font-size: 12px; padding: 6px 10px;">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : ($order->payment_status === 'failed' ? 'bg-danger' : 'bg-warning') }}" style="font-size: 12px; padding: 6px 10px;">
                                {{ $order->payment_status_label }}
                            </span>
                        </td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}" 
                               class="btn btn-sm btn-primary">
                                <i class="fa fa-eye"></i> Xem chi tiết
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-3">Không tìm thấy đơn hàng nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($orders->hasPages())
        <div class="mt-3">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection