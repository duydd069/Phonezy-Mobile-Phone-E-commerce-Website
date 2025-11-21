@extends('electro.layout')

@section('title', 'Thanh toán')

@section('content')
    <div class="section">
        <div class="container">
            <form action="{{ route('client.checkout.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-7">
                        <div class="section-title">
                            <h3 class="title">Thông tin giao hàng</h3>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>Vui lòng kiểm tra lại thông tin:</strong>
                                <ul style="margin-bottom: 0; padding-left: 20px;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="billing-details">
                            <div class="form-group">
                                <input class="input" type="text" name="full_name"
                                       value="{{ old('full_name', $prefill['full_name']) }}"
                                       placeholder="Họ và tên *">
                                @error('full_name') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="form-group">
                                <input class="input" type="email" name="email"
                                       value="{{ old('email', $prefill['email']) }}"
                                       placeholder="Email">
                                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="form-group">
                                <input class="input" type="text" name="phone"
                                       value="{{ old('phone', $prefill['phone']) }}"
                                       placeholder="Số điện thoại *">
                                @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="form-group">
                                <input class="input" type="text" name="address"
                                       value="{{ old('address', $prefill['address']) }}"
                                       placeholder="Địa chỉ nhận hàng *">
                                @error('address') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="form-group">
                                <input class="input" type="text" name="city"
                                       value="{{ old('city', $prefill['city']) }}"
                                       placeholder="Tỉnh / Thành phố">
                                @error('city') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="form-group">
                                <input class="input" type="text" name="district"
                                       value="{{ old('district', $prefill['district']) }}"
                                       placeholder="Quận / Huyện">
                                @error('district') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="form-group">
                                <input class="input" type="text" name="ward"
                                       value="{{ old('ward', $prefill['ward']) }}"
                                       placeholder="Phường / Xã">
                                @error('ward') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="order-notes">
                                <textarea class="input" name="notes" placeholder="Ghi chú thêm">{{ old('notes', $prefill['notes']) }}</textarea>
                                @error('notes') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        <div class="payment-method">
                            <div class="section-title">
                                <h3 class="title">Phương thức thanh toán</h3>
                            </div>
                            @foreach ($paymentMethods as $method => $label)
                                <div class="input-radio">
                                    <input type="radio"
                                           name="payment_method"
                                           id="payment-{{ $method }}"
                                           value="{{ $method }}"
                                           {{ old('payment_method', $method === 'cod' ? 'cod' : null) === $method ? 'checked' : '' }}>
                                    <label for="payment-{{ $method }}">
                                        <span></span>{{ $label }}
                                    </label>
                                </div>
                            @endforeach
                            @error('payment_method') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    <div class="col-md-5 order-details">
                        <div class="section-title text-center">
                            <h3 class="title">Đơn hàng của bạn</h3>
                        </div>

                        <div class="order-summary">
                            <div class="order-col">
                                <div><strong>SẢN PHẨM</strong></div>
                                <div><strong>TỔNG</strong></div>
                            </div>

                            <div class="order-products">
                                @foreach($items as $item)
                                    @php
                                        $product = $item->product;
                                        $price = $product->price ?? 0;
                                    @endphp
                                    <div class="order-col">
                                        <div>{{ $item->quantity }} x {{ $product->name ?? 'Sản phẩm' }}</div>
                                        <div>{{ number_format($price * $item->quantity, 0, ',', '.') }} ₫</div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="order-col">
                                <div>Tạm tính</div>
                                <div>{{ number_format($summary['subtotal'], 0, ',', '.') }} ₫</div>
                            </div>
                            <div class="order-col">
                                <div>Phí vận chuyển</div>
                                <div>{{ number_format($summary['shipping_fee'], 0, ',', '.') }} ₫</div>
                            </div>
                            <div class="order-col">
                                <div>Giảm giá</div>
                                <div>{{ number_format($summary['discount'], 0, ',', '.') }} ₫</div>
                            </div>
                            <div class="order-col">
                                <div><strong>Tổng cộng</strong></div>
                                <div><strong class="order-total">{{ number_format($summary['total'], 0, ',', '.') }} ₫</strong></div>
                            </div>
                        </div>

                        <button type="submit" class="primary-btn order-submit">
                            Xác nhận đặt hàng
                        </button>

                        <a href="{{ route('cart.index') }}" class="primary-btn cta-btn" style="margin-top: 15px; display: block; text-align: center;">
                            ← Quay về giỏ hàng
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
