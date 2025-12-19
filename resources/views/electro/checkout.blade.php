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

                        @if (session('error'))
                            <div class="alert alert-danger">
                                <strong>Lỗi:</strong> {{ session('error') }}
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
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

                        <div class="coupon-section" style="margin-top: 20px; margin-bottom: 20px;">
                            <div class="section-title">
                                <h3 class="title">Mã khuyến mãi</h3>
                            </div>
                            
                            @if(isset($availableCoupons) && $availableCoupons->isNotEmpty())
                                <div style="margin-bottom: 15px;">
                                    <p style="margin-bottom: 10px; font-weight: 500;">Mã khuyến mãi của bạn:</p>
                                    <div class="coupon-list" style="display: flex; flex-direction: column; gap: 8px;">
                                        @foreach($availableCoupons as $availableCoupon)
                                            @php
                                                $isSelected = isset($coupon) && $coupon && $coupon->id === $availableCoupon->id;
                                                $discountText = $availableCoupon->discount_type === 'percent' 
                                                    ? "Giảm {$availableCoupon->discount_value}%" 
                                                    : "Giảm " . number_format($availableCoupon->discount_value, 0, ',', '.') . " ₫";
                                                $expiresText = $availableCoupon->expires_at 
                                                    ? "Hết hạn: " . $availableCoupon->expires_at->format('d/m/Y')
                                                    : "Không giới hạn";
                                                $typeLabel = ($availableCoupon->type ?? 'public') === 'private' ? ' (Riêng tư)' : '';
                                            @endphp
                                            <div class="coupon-item" 
                                                 data-code="{{ $availableCoupon->code }}"
                                                 style="border: 2px solid {{ $isSelected ? '#4CAF50' : '#e0e0e0' }}; 
                                                        border-radius: 8px; 
                                                        padding: 12px; 
                                                        cursor: pointer; 
                                                        background: {{ $isSelected ? '#f1f8f4' : '#fff' }};
                                                        transition: all 0.3s;"
                                                 onclick="selectCoupon('{{ $availableCoupon->code }}')"
                                                 onmouseover="if(!this.querySelector('.check-mark')) { this.style.borderColor='#4CAF50'; this.style.background='#f1f8f4'; }"
                                                 onmouseout="if(!this.querySelector('.check-mark')) { this.style.borderColor='{{ $isSelected ? '#4CAF50' : '#e0e0e0' }}'; this.style.background='{{ $isSelected ? '#f1f8f4' : '#fff' }}'; }">
                                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                                    <div>
                                                        <strong style="font-size: 16px; color: #4CAF50;">{{ $availableCoupon->code }}</strong>
                                                        <span style="color: #666; font-size: 12px;">{{ $typeLabel }}</span>
                                                        <div style="margin-top: 4px; font-size: 14px; color: #333;">
                                                            {{ $discountText }}
                                                        </div>
                                                        <div style="margin-top: 2px; font-size: 12px; color: #999;">
                                                            {{ $expiresText }}
                                                        </div>
                                                    </div>
                                                    @if($isSelected)
                                                        <span style="color: #4CAF50; font-weight: bold;">✓ Đã chọn</span>
                                                    @else
                                                        <button type="button" 
                                                                class="btn btn-sm" 
                                                                style="background: #4CAF50; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer;"
                                                                onclick="event.stopPropagation(); selectCoupon('{{ $availableCoupon->code }}');">
                                                            Chọn
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <hr style="margin: 15px 0; border: none; border-top: 1px solid #e0e0e0;">
                            @endif
                            
                            <div class="form-group" style="display: flex; gap: 10px;">
                                <input class="input" 
                                       type="text" 
                                       name="coupon_code" 
                                       id="coupon_code"
                                       value="{{ old('coupon_code', $prefill['coupon_code'] ?? '') }}"
                                       placeholder="Hoặc nhập mã khuyến mãi khác"
                                       style="flex: 1;">
                                <button type="button" 
                                        id="apply-coupon-btn" 
                                        class="primary-btn"
                                        style="white-space: nowrap;">
                                    Áp dụng
                                </button>
                            </div>
                            <div id="coupon-message" style="margin-top: 10px;"></div>
                            @error('coupon_code') <small class="text-danger">{{ $message }}</small> @enderror
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
                                        $variant = $item->variant;
                                        $product = $variant->product ?? $item->product;
                                        // Ưu tiên giá sale nếu có
                                        $price = $variant 
                                            ? ($variant->price_sale ?? $variant->price ?? 0)
                                            : 0;
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
                            <div class="order-col" id="discount-row">
                                <div>Giảm giá</div>
                                <div id="discount-amount">{{ number_format($summary['discount'], 0, ',', '.') }} ₫</div>
                            </div>
                            <div class="order-col">
                                <div><strong>Tổng cộng</strong></div>
                                <div><strong class="order-total" id="total-amount">{{ number_format($summary['total'], 0, ',', '.') }} ₫</strong></div>
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

    <script>
        // Định nghĩa function selectCoupon trước để có thể gọi từ onclick
        function selectCoupon(code) {
            // Kiểm tra xem function đã được export chưa
            if (typeof window.selectCoupon === 'function') {
                window.selectCoupon(code);
            } else {
                // Nếu chưa, đợi một chút rồi thử lại
                setTimeout(function() {
                    if (typeof window.selectCoupon === 'function') {
                        window.selectCoupon(code);
                    } else {
                        // Fallback: kiểm tra xem coupon đã được chọn chưa
                        const currentSelected = typeof window.getCurrentSelectedCoupon === 'function' 
                            ? window.getCurrentSelectedCoupon() 
                            : null;
                        
                        if (currentSelected === code) {
                            // Nếu đã chọn, bỏ chọn
                            if (typeof window.removeCoupon === 'function') {
                                window.removeCoupon();
                            }
                        } else {
                            // Nếu chưa chọn, set giá trị và trigger apply button
                            const couponCodeInput = document.getElementById('coupon_code');
                            if (couponCodeInput) {
                                couponCodeInput.value = code;
                                const applyCouponBtn = document.getElementById('apply-coupon-btn');
                                if (applyCouponBtn) {
                                    applyCouponBtn.click();
                                }
                            }
                        }
                    }
                }, 100);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const couponCodeInput = document.getElementById('coupon_code');
            const applyCouponBtn = document.getElementById('apply-coupon-btn');
            const couponMessage = document.getElementById('coupon-message');
            const discountAmount = document.getElementById('discount-amount');
            const totalAmount = document.getElementById('total-amount');
            
            // Biến để track coupon hiện tại đã chọn
            let currentSelectedCoupon = null;

            function formatNumber(num) {
                return new Intl.NumberFormat('vi-VN').format(num);
            }

            function updateSummary(summary) {
                if (discountAmount) {
                    discountAmount.textContent = formatNumber(summary.discount) + ' ₫';
                }
                if (totalAmount) {
                    totalAmount.textContent = formatNumber(summary.total) + ' ₫';
                }
            }

            function showMessage(message, type = 'success') {
                couponMessage.innerHTML = '<div class="alert alert-' + type + '">' + message + '</div>';
            }

            function applyCoupon(code) {
                if (!code) {
                    showMessage('Vui lòng nhập mã khuyến mãi', 'danger');
                    return;
                }

                // Disable button while validating
                applyCouponBtn.disabled = true;
                applyCouponBtn.textContent = 'Đang kiểm tra...';

                fetch('{{ route("client.checkout.validate-coupon") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ code: code })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.valid) {
                        showMessage(data.message, 'success');
                        if (data.summary) {
                            updateSummary(data.summary);
                        }
                        // Lưu coupon hiện tại đã chọn
                        currentSelectedCoupon = data.coupon.code;
                        // Cập nhật trạng thái các coupon item
                        updateCouponItemsState(data.coupon.code);
                    } else {
                        showMessage(data.message, 'danger');
                        if (data.summary) {
                            updateSummary(data.summary);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showMessage('Có lỗi xảy ra, vui lòng thử lại', 'danger');
                })
                .finally(() => {
                    applyCouponBtn.disabled = false;
                    applyCouponBtn.textContent = 'Áp dụng';
                });
            }

            function removeCoupon() {
                // Xóa coupon khỏi input
                couponCodeInput.value = '';
                // Reset coupon hiện tại đã chọn
                currentSelectedCoupon = null;
                
                // Tính lại summary không có coupon - sử dụng giá trị ban đầu
                const originalSummary = {!! json_encode($summary) !!};
                const summaryWithoutCoupon = {
                    subtotal: originalSummary.subtotal,
                    discount: 0,
                    shipping_fee: originalSummary.shipping_fee,
                    total: originalSummary.subtotal + originalSummary.shipping_fee
                };
                
                updateSummary(summaryWithoutCoupon);
                
                // Reset trạng thái các coupon item
                updateCouponItemsState(null);
                showMessage('Đã bỏ chọn mã khuyến mãi', 'info');
            }

            function updateCouponItemsState(selectedCode) {
                // Cập nhật trạng thái visual của các coupon items
                document.querySelectorAll('.coupon-item').forEach(item => {
                    const code = item.getAttribute('data-code');
                    if (selectedCode && code === selectedCode) {
                        item.style.borderColor = '#4CAF50';
                        item.style.background = '#f1f8f4';
                        const button = item.querySelector('button');
                        if (button) {
                            button.style.display = 'none';
                        }
                        // Thêm dấu check nếu chưa có
                        let checkMark = item.querySelector('.check-mark');
                        if (!checkMark) {
                            checkMark = document.createElement('span');
                            checkMark.className = 'check-mark';
                            checkMark.style.cssText = 'color: #4CAF50; font-weight: bold; margin-left: auto;';
                            checkMark.textContent = '✓ Đã chọn';
                            const contentDiv = item.querySelector('div[style*="display: flex"]');
                            if (contentDiv) {
                                contentDiv.appendChild(checkMark);
                            }
                        }
                    } else {
                        item.style.borderColor = '#e0e0e0';
                        item.style.background = '#fff';
                        const button = item.querySelector('button');
                        if (button) {
                            button.style.display = 'block';
                        }
                        // Xóa dấu check
                        const checkMark = item.querySelector('.check-mark');
                        if (checkMark) {
                            checkMark.remove();
                        }
                    }
                });
            }

            applyCouponBtn.addEventListener('click', function() {
                const code = couponCodeInput.value.trim().toUpperCase();
                applyCoupon(code);
            });

            // Allow Enter key to apply coupon
            couponCodeInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    applyCouponBtn.click();
                }
            });

            // Export functions để có thể gọi từ bên ngoài
            window.applyCoupon = applyCoupon;
            window.removeCoupon = removeCoupon;
            window.selectCoupon = function(code) {
                const couponCodeInput = document.getElementById('coupon_code');
                if (couponCodeInput) {
                    // Kiểm tra nếu coupon này đã được chọn, thì bỏ chọn
                    if (currentSelectedCoupon === code) {
                        removeCoupon();
                    } else {
                        // Nếu chưa chọn, thì chọn và áp dụng
                        couponCodeInput.value = code;
                        applyCoupon(code);
                    }
                }
            };
            
            // Export biến để có thể truy cập từ bên ngoài
            window.getCurrentSelectedCoupon = function() {
                return currentSelectedCoupon;
            };

            // Khởi tạo trạng thái coupon khi trang load
            @if(isset($coupon) && $coupon)
                const initialCouponCode = '{{ $coupon->code }}';
                currentSelectedCoupon = initialCouponCode; // Lưu coupon ban đầu
                updateCouponItemsState(initialCouponCode);
            @endif

            // Xử lý form submit để tránh submit nhiều lần
            const checkoutForm = document.querySelector('form[action="{{ route("client.checkout.store") }}"]');
            const submitButton = document.querySelector('button[type="submit"].order-submit');
            
            if (checkoutForm && submitButton) {
                checkoutForm.addEventListener('submit', function(e) {
                    // Không prevent default - để form submit bình thường
                    // Chỉ disable button để tránh double submit
                    if (submitButton.disabled) {
                        e.preventDefault();
                        return false;
                    }
                    
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Đang xử lý...';
                });
            }
        });

    </script>
@endsection