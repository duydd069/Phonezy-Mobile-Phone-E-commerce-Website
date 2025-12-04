@extends('electro.layout')

@section('title', 'Chi tiết đơn hàng #' . $order->id)

@section('content')
<div class="section">
    <div class="container">
        <div class="row">
            @include('electro.account._sidebar')
            
            <div class="col-md-9">
                <div class="section-title">
                    <h3 class="title">Chi tiết đơn hàng #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h3>
                </div>

                <div class="row">
            <div class="col-md-8">
                <!-- Order Information -->
                <div class="order-details">
                    <div class="section-title">
                        <h4 class="title">Thông tin đơn hàng</h4>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Mã đơn hàng:</strong><br>
                            <span>#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Ngày đặt hàng:</strong><br>
                            <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Trạng thái:</strong><br>
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
                        </div>
                        <div class="col-md-6">
                            <strong>Thanh toán:</strong><br>
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
                        </div>
                    </div>

                    <!-- Shipping Information -->
                    <div class="section-title mt-4">
                        <h4 class="title">Thông tin giao hàng</h4>
                    </div>
                    <ul class="list-unstyled" style="line-height: 1.8;">
                        <li><strong>Tên người nhận:</strong> {{ $order->shipping_full_name }}</li>
                        <li><strong>Số điện thoại:</strong> {{ $order->shipping_phone }}</li>
                        @if($order->shipping_email)
                            <li><strong>Email:</strong> {{ $order->shipping_email }}</li>
                        @endif
                        <li><strong>Địa chỉ:</strong> {{ $order->shipping_address }}</li>
                        @if($order->shipping_city || $order->shipping_district || $order->shipping_ward)
                            <li>
                                <strong>Khu vực:</strong>
                                {{ collect([$order->shipping_ward, $order->shipping_district, $order->shipping_city])->filter()->implode(', ') }}
                            </li>
                        @endif
                        <li><strong>Phương thức thanh toán:</strong> {{ $paymentMethods[$order->payment_method] ?? strtoupper($order->payment_method) }}</li>
                        @if($order->notes)
                            <li><strong>Ghi chú:</strong> {{ $order->notes }}</li>
                        @endif
                    </ul>
                </div>
            </div>

                    <div class="col-md-4">
                <!-- Order Summary -->
                <div class="order-summary">
                    <div class="section-title">
                        <h4 class="title">Tóm tắt đơn hàng</h4>
                    </div>

                    <div class="order-products">
                        @foreach ($order->items as $item)
                            <div class="order-col mb-2 pb-2" style="border-bottom: 1px solid #eee;">
                                <div>
                                    <strong>{{ $item->quantity }} x {{ $item->product_name }}</strong>
                                    @if($item->product_image)
                                        <br>
                                        <img src="{{ asset($item->product_image) }}" 
                                             alt="{{ $item->product_name }}" 
                                             style="max-width: 60px; max-height: 60px; margin-top: 5px;">
                                    @endif
                                </div>
                                <div>
                                    <strong>{{ number_format($item->total_price, 0, ',', '.') }} ₫</strong>
                                    <br>
                                    <small class="text-muted">{{ number_format($item->unit_price, 0, ',', '.') }} ₫/sản phẩm</small>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="order-col">
                        <div>Tạm tính</div>
                        <div>{{ number_format($order->subtotal, 0, ',', '.') }} ₫</div>
                    </div>
                    <div class="order-col">
                        <div>Phí vận chuyển</div>
                        <div>{{ number_format($order->shipping_fee, 0, ',', '.') }} ₫</div>
                    </div>
                    @if($order->discount_amount > 0)
                        <div class="order-col">
                            <div>Giảm giá</div>
                            <div class="text-success">-{{ number_format($order->discount_amount, 0, ',', '.') }} ₫</div>
                        </div>
                        @if($order->coupon)
                            <div class="order-col">
                                <div>
                                    <small class="text-muted">
                                        <i class="fa fa-tag"></i> Mã: {{ $order->coupon->code }}
                                    </small>
                                </div>
                            </div>
                        @endif
                    @endif
                    <div class="order-col">
                        <div><strong>Tổng cộng</strong></div>
                        <div><strong class="order-total">{{ number_format($order->total, 0, ',', '.') }} ₫</strong></div>
                    </div>
                </div>
            </div>
        </div>

                <div class="text-center mt-4">
                    <a href="{{ route('client.orders.index') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left"></i> Quay lại danh sách đơn hàng
                    </a>
                    <a href="{{ route('client.index') }}" class="btn btn-primary">
                        <i class="fa fa-home"></i> Về trang chủ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
