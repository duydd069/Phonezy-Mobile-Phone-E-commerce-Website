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
                            @if(config('bank.show_payment_status', false))
                                <li><strong>Trạng thái thanh toán:</strong> {{ ucfirst($order->payment_status) }}</li>
                            @endif
                        </ul>

                        @if($order->payment_method === 'bank_transfer' && $order->payment_status === 'pending')
                            <div class="bank-transfer-info" style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 5px; border: 2px solid #D10024;">
                                <div class="section-title">
                                    <h3 class="title" style="color: #D10024;">
                                        <i class="fa fa-university"></i> Thông tin chuyển khoản
                                    </h3>
                                </div>
                                
                                <div style="background: #fff; padding: 20px; border-radius: 5px; margin-top: 15px;">
                                    @php
                                        $bankAccounts = config('bank.accounts', []);
                                        $bankInstructions = config('bank.instructions', []);
                                    @endphp
                                    
                                    @if(!empty($bankAccounts))
                                        @foreach($bankAccounts as $account)
                                            <div style="margin-bottom: 25px; {{ !$loop->last ? 'border-bottom: 1px solid #e0e0e0; padding-bottom: 20px;' : '' }}">
                                                <h4 style="color: #D10024; margin-bottom: 15px;">
                                                    <i class="fa fa-bank"></i> {{ $account['bank_name'] }}
                                                </h4>
                                                <div style="line-height: 2;">
                                                    <div><strong>Số tài khoản:</strong> 
                                                        <span style="font-size: 18px; color: #D10024; font-weight: bold;">{{ $account['account_number'] }}</span>
                                                    </div>
                                                    <div><strong>Chủ tài khoản:</strong> {{ $account['account_holder'] }}</div>
                                                    @if(!empty($account['branch']))
                                                        <div><strong>Chi nhánh:</strong> {{ $account['branch'] }}</div>
                                                    @endif
                                                    @if(!empty($account['qr_code']))
                                                        <div style="margin-top: 15px;">
                                                            <strong>QR Code:</strong><br>
                                                            <img src="{{ $account['qr_code'] }}" alt="QR Code" style="max-width: 200px; margin-top: 10px; border: 1px solid #ddd; padding: 5px; background: #fff;">
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div style="color: #666;">
                                            <p>Vui lòng liên hệ với chúng tôi để nhận thông tin tài khoản ngân hàng.</p>
                                        </div>
                                    @endif
                                    
                                    <div style="margin-top: 20px; padding: 15px; background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 3px;">
                                        <strong style="color: #856404;">Hướng dẫn thanh toán:</strong>
                                        <ul style="margin-top: 10px; margin-bottom: 0; padding-left: 20px; color: #856404;">
                                            @foreach($bankInstructions as $instruction)
                                                <li>{!! str_replace(['{amount}', '{order_id}'], [number_format($order->total, 0, ',', '.'), str_pad($order->id, 6, '0', STR_PAD_LEFT)], $instruction) !!}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    
                                    <div style="margin-top: 20px; padding: 15px; background: #d1ecf1; border-left: 4px solid #0c5460; border-radius: 3px;">
                                        <strong style="color: #0c5460;">Lưu ý quan trọng:</strong>
                                        <p style="margin-top: 10px; margin-bottom: 0; color: #0c5460;">
                                            Vui lòng chuyển khoản đúng số tiền <strong>{{ number_format($order->total, 0, ',', '.') }} ₫</strong> 
                                            và ghi rõ nội dung: <strong>#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</strong>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

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
                                <div>{{ number_format($order->subtotal, 0, ',', '.') }} ₫</div>
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
                                <div><strong class="order-total">{{ number_format($order->total, 0, ',', '.') }} ₫</strong></div>
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

