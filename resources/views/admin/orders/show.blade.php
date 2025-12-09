@extends('layouts.app')

@section('content')
<div class="container-fluid p-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="m-0">Chi Tiết Đơn Hàng #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h3>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">← Quay lại</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        {{-- Left Column: Order Information --}}
        <div class="col-md-8">
            {{-- Customer Information --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="m-0">Thông Tin Khách Hàng</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <strong>Họ và tên:</strong>
                            <div>{{ $order->shipping_full_name }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong>Số điện thoại:</strong>
                            <div>{{ $order->shipping_phone }}</div>
                        </div>
                        @if($order->shipping_email)
                            <div class="col-md-6">
                                <strong>Email:</strong>
                                <div>{{ $order->shipping_email }}</div>
                            </div>
                        @endif
                        @if($order->user)
                            <div class="col-md-6">
                                <strong>Tài khoản:</strong>
                                <div>
                                    <a href="{{ route('admin.users.show', $order->user_id) }}">
                                        {{ $order->user->name }} (ID: {{ $order->user_id }})
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Shipping Address --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="m-0">Địa Chỉ Giao Hàng</h5>
                </div>
                <div class="card-body">
                    <p>
                        <strong>Địa chỉ:</strong> {{ $order->shipping_address }}
                    </p>
                    @if($order->shipping_ward || $order->shipping_district || $order->shipping_city)
                        <p>
                            <strong>Khu vực:</strong>
                            {{ collect([$order->shipping_ward, $order->shipping_district, $order->shipping_city])->filter()->implode(', ') }}
                        </p>
                    @endif
                    @if($order->notes)
                        <p>
                            <strong>Ghi chú:</strong> {{ $order->notes }}
                        </p>
                    @endif
                </div>
            </div>

            {{-- Order Items --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="m-0">Sản Phẩm Đơn Hàng</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th class="text-end">Đơn giá</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-end">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            @if($item->product_image)
                                                <img src="{{ $item->product_image }}" 
                                                     alt="{{ $item->product_name }}"
                                                     style="width: 50px; height: 50px; object-fit: cover; margin-right: 10px;">
                                            @endif
                                            {{ $item->product_name }}
                                            @if($item->product_id)
                                                <br><small class="text-muted">Mã sản phẩm: {{ $item->product_id }}</small>
                                            @endif
                                        </td>
                                        <td class="text-end">{{ number_format($item->unit_price, 0, ',', '.') }} ₫</td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">
                                            <strong>{{ number_format($item->total_price, 0, ',', '.') }} ₫</strong>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Status and Payment Information --}}
        <div class="col-md-4">
            {{-- Order Status --}}
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="m-0">Trạng Thái Đơn Hàng</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Trạng thái hiện tại:</strong>
                        <div>
                            <span class="badge {{ $order->status_badge_class }}" style="font-size: 14px; padding: 8px 12px;">
                                {{ $order->status_label }}
                            </span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.orders.update-status', $order) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="status" class="form-label">Thay đổi trạng thái:</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100" 
                                onclick="return confirm('Bạn có chắc chắn muốn thay đổi trạng thái đơn hàng?');">
                            Cập nhật trạng thái
                        </button>
                    </form>
                </div>
            </div>

            {{-- Payment Information --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="m-0">Thông Tin Thanh Toán</h5>
                </div>
                <div class="card-body">
                    <p>
                        <strong>Phương thức:</strong><br>
                        {{ $paymentMethods[$order->payment_method] ?? strtoupper($order->payment_method) }}
                    </p>
                    <p>
                        <strong>Trạng thái:</strong><br>
                        <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-warning' }}">
                            {{ $order->payment_status_label }}
                        </span>
                    </p>
                    @if($order->paid_at)
                        <p>
                            <strong>Thời gian thanh toán:</strong><br>
                            {{ $order->paid_at->format('d/m/Y H:i') }}
                        </p>
                    @endif
                </div>
            </div>

            {{-- Order Summary --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="m-0">Tóm Tắt Đơn Hàng</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tạm tính:</span>
                        <strong>{{ number_format($order->subtotal, 0, ',', '.') }} ₫</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Phí vận chuyển:</span>
                        <strong>{{ number_format($order->shipping_fee, 0, ',', '.') }} ₫</strong>
                    </div>
                    @if($order->discount_amount > 0)
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span>Giảm giá:</span>
                            <strong>-{{ number_format($order->discount_amount, 0, ',', '.') }} ₫</strong>
                        </div>
                    @endif
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Tổng cộng:</strong>
                        <strong style="color: #F7941D; font-size: 18px;">
                            {{ number_format($order->total, 0, ',', '.') }} ₫
                        </strong>
                    </div>
                </div>
            </div>

            {{-- Order Information --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="m-0">Thông Tin Đơn Hàng</h5>
                </div>
                <div class="card-body">
                    <p><strong>Ngày đặt hàng:</strong><br>{{ $order->created_at->format('d/m/Y H:i') }}</p>
                    @if($order->updated_at != $order->created_at)
                        <p><strong>Cập nhật lần cuối:</strong><br>{{ $order->updated_at->format('d/m/Y H:i') }}</p>
                    @endif
                    <p><strong>Mã đơn hàng:</strong><br>#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection