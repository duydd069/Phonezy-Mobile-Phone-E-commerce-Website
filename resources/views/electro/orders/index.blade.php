@extends('electro.layout')

@section('title', 'Đơn hàng của tôi')

@section('content')
<div class="section">
    <div class="container">
        <div class="row">
            @include('electro.account._sidebar')
            
            <div class="col-md-9">
                <div class="section-title">
                    <h3 class="title">Đơn hàng của tôi</h3>
                </div>

        @if($orders->isEmpty())
            <div class="alert alert-info text-center">
                <h4>Bạn chưa có đơn hàng nào</h4>
                <p>Hãy bắt đầu mua sắm để tạo đơn hàng đầu tiên!</p>
                <a href="{{ route('client.index') }}" class="btn btn-primary mt-3">
                    <i class="fa fa-shopping-bag"></i> Mua sắm ngay
                </a>
            </div>
        @else
            <!-- Filter by status -->
            <div class="mb-4">
                <form method="GET" action="{{ route('client.orders.index') }}" class="d-flex gap-2">
                    <select name="status" class="form-control" style="max-width: 200px;" onchange="this.form.submit()">
                        <option value="">Tất cả trạng thái</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Mã đơn hàng</th>
                            <th>Ngày đặt</th>
                            <th>Sản phẩm</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Thanh toán</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>
                                    <strong>#{{ $order->id }}</strong>
                                </td>
                                <td>
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td>
                                    <small>
                                        {{ $order->items->count() }} sản phẩm
                                        @if($order->items->count() > 0)
                                            <br>
                                            <span class="text-muted">{{ $order->items->first()->product_name }}</span>
                                            @if($order->items->count() > 1)
                                                <span class="text-muted">và {{ $order->items->count() - 1 }} sản phẩm khác</span>
                                            @endif
                                        @endif
                                    </small>
                                </td>
                                <td>
                                    <strong>{{ number_format($order->total, 0, ',', '.') }} ₫</strong>
                                    @if($order->discount_amount > 0)
                                        <br>
                                        <small class="text-success">
                                            Đã giảm: {{ number_format($order->discount_amount, 0, ',', '.') }} ₫
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusClasses = [
                                            'pending' => 'warning',
                                            'processing' => 'info',
                                            'completed' => 'success',
                                            'cancelled' => 'danger'
                                        ];
                                        $statusLabels = [
                                            'pending' => 'Chờ xử lý',
                                            'processing' => 'Đang xử lý',
                                            'completed' => 'Hoàn thành',
                                            'cancelled' => 'Đã hủy'
                                        ];
                                        $badgeClass = $statusClasses[$order->status] ?? 'secondary';
                                        $statusLabel = $statusLabels[$order->status] ?? ucfirst($order->status);
                                    @endphp
                                    <span class="badge bg-{{ $badgeClass }}">{{ $statusLabel }}</span>
                                </td>
                                <td>
                                    @php
                                        $paymentClasses = [
                                            'pending' => 'warning',
                                            'paid' => 'success',
                                            'failed' => 'danger',
                                            'refunded' => 'info'
                                        ];
                                        $paymentLabels = [
                                            'pending' => 'Chờ thanh toán',
                                            'paid' => 'Đã thanh toán',
                                            'failed' => 'Thất bại',
                                            'refunded' => 'Đã hoàn tiền'
                                        ];
                                        $paymentBadgeClass = $paymentClasses[$order->payment_status] ?? 'secondary';
                                        $paymentLabel = $paymentLabels[$order->payment_status] ?? ucfirst($order->payment_status);
                                    @endphp
                                    <span class="badge bg-{{ $paymentBadgeClass }}">{{ $paymentLabel }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('client.orders.show', $order->id) }}" 
                                       class="btn btn-sm btn-primary">
                                        <i class="fa fa-eye"></i> Xem chi tiết
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        @endif

            </div>
        </div>
    </div>
</div>
@endsection
