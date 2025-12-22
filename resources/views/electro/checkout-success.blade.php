@extends('electro.layout')

@section('title', 'Đặt hàng thành công')

@section('content')
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="text-center">
                        <div class="section-title">
                            <h2 class="title">Cảm ơn bạn đã đặt hàng!</h2>
                            <p>Mã đơn hàng: <strong>#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</strong></p>
                        </div>
                        <p>Chúng tôi đã ghi nhận thông tin và sẽ liên hệ để xác nhận trong thời gian sớm nhất.</p>
                    </div>

                    <div class="order-details" style="margin-top: 30px;">
                        <div class="section-title">
                            <h3 class="title">Thông tin đơn hàng</h3>
                        </div>
                        <ul class="list-unstyled" style="line-height: 1.8;">
                            <li><strong>Tên khách hàng:</strong> {{ $order->shipping_full_name }}</li>
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
                            <li><strong>Trạng thái thanh toán:</strong> {{ ucfirst($order->payment_status) }}</li>
                        </ul>

                        <div class="order-summary">
                            <div class="order-col">
                                <div><strong>SẢN PHẨM</strong></div>
                                <div><strong>TỔNG</strong></div>
                            </div>

                            <div class="order-products">
                                @foreach ($order->items as $item)
                                    <div class="order-col">
                                        <div>{{ $item->quantity }} x {{ $item->product_name }}</div>
                                        <div>{{ number_format($item->total_price, 0, ',', '.') }} ₫</div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="order-col">
                                <div>Tạm tính</div>
                                <div>{{ number_format($order->calculateSubtotalFromItems(), 0, ',', '.') }} ₫</div>
                            </div>
                            <div class="order-col">
                                <div>Phí vận chuyển</div>
                                <div>{{ number_format($order->shipping_fee, 0, ',', '.') }} ₫</div>
                            </div>
                            <div class="order-col">
                                <div>Giảm giá</div>
                                <div>{{ number_format($order->discount_amount, 0, ',', '.') }} ₫</div>
                            </div>
                            <div class="order-col">
                                <div><strong>Tổng cộng</strong></div>
                                <div><strong class="order-total">{{ number_format(max($order->calculateSubtotalFromItems() - $order->discount_amount + $order->shipping_fee, 0), 0, ',', '.') }} ₫</strong></div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center" style="margin-top: 30px;">
                        <a href="{{ route('client.index') }}" class="primary-btn">Tiếp tục mua sắm</a>
                        <a href="{{ route('client.store') }}" class="primary-btn cta-btn" style="margin-left: 10px;">Xem sản phẩm khác</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

