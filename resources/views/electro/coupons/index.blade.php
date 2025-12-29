@extends('electro.layout')

@section('title', 'Mã khuyến mãi của tôi')

@section('content')
<div class="section">
    <div class="container">
        <div class="row">
            @include('electro.account._sidebar')
            
            <div class="col-md-9">
                <div class="section-title">
                    <h3 class="title">Mã khuyến mãi của tôi</h3>
                    <p class="text-muted">Danh sách các mã khuyến mãi bạn có thể sử dụng</p>
                </div>

        @if($allCoupons->isEmpty())
            <div class="alert alert-info text-center">
                <h4>Bạn chưa có mã khuyến mãi nào</h4>
                <p>Hãy theo dõi để nhận được các mã khuyến mãi hấp dẫn từ chúng tôi!</p>
            </div>
        @else
            <div class="row">
                @foreach($allCoupons as $coupon)
                    @php
                        $isExpired = $coupon->expires_at && $coupon->expires_at->isPast();
                        $isPrivate = ($coupon->type ?? 'public') == 'private';
                        $discountText = $coupon->discount_type === 'percent' 
                            ? $coupon->discount_value . '%' 
                            : number_format($coupon->discount_value, 0, ',', '.') . ' ₫';
                        $minOrder = $coupon->min_order_value;
                        $maxDiscount = $coupon->max_discount;
                        $scopeText = ($coupon->promotion_type ?? 'order') === 'order'
                            ? 'Áp dụng cho toàn bộ đơn hàng'
                            : 'Áp dụng cho một số sản phẩm nhất định';
                    @endphp
                    
                    <div class="col-md-6 col-lg-4 mb-5 pb-2" style="margin-bottom: 30px !important;">
                        <div class="card h-100 {{ $isExpired ? 'border-secondary' : 'border-primary' }}" 
                             style="box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                            <div class="card-header {{ $isExpired ? 'bg-secondary' : ($isPrivate ? 'bg-warning' : 'bg-primary') }} text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">
                                        <strong>{{ $coupon->code }}</strong>
                                    </h5>
                                    @if($isPrivate)
                                        <span class="badge bg-light text-dark">Riêng tư</span>
                                    @else
                                        <span class="badge bg-light text-dark">Công khai</span>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="text-center mb-3">
                                    <h2 class="text-primary mb-0">
                                        <strong>{{ $discountText }}</strong>
                                    </h2>
                                    <small class="text-muted">
                                        @if($coupon->discount_type === 'percent')
                                            Giảm giá theo phần trăm
                                        @else
                                            Giảm giá cố định
                                        @endif
                                    </small>
                                </div>

                                <hr>

                                <div class="mb-2">
                                    <strong>Mã code:</strong>
                                    <code class="bg-light p-2 d-block text-center mt-1" style="font-size: 1.2em; letter-spacing: 2px;">
                                        {{ $coupon->code }}
                                    </code>
                                </div>

                                @if($coupon->expires_at)
                                    <div class="mb-2">
                                        <strong>Hết hạn:</strong>
                                        <span class="{{ $isExpired ? 'text-danger' : 'text-success' }}">
                                            {{ $coupon->expires_at->format('d/m/Y H:i') }}
                                        </span>
                                    </div>
                                @else
                                    <div class="mb-2">
                                        <span class="badge bg-success">Không giới hạn thời gian</span>
                                    </div>
                                @endif

                                <div class="mb-2">
                                    <strong>Điều kiện sử dụng:</strong>
                                    <ul class="list-unstyled" style="margin-bottom: 0; margin-top: 4px; font-size: 13px; color: #555;">
                                        @if($minOrder)
                                            <li>• Đơn tối thiểu: {{ number_format($minOrder, 0, ',', '.') }} ₫ (chưa gồm phí ship)</li>
                                        @else
                                            <li>• Không yêu cầu giá trị đơn hàng tối thiểu</li>
                                        @endif

                                        <li>• {{ $scopeText }}</li>

                                        @if($coupon->discount_type === 'percent' && $maxDiscount)
                                            <li>• Giảm tối đa: {{ number_format($maxDiscount, 0, ',', '.') }} ₫ mỗi đơn</li>
                                        @endif

                                        <li>• Mỗi đơn hàng chỉ áp dụng 1 mã khuyến mãi</li>
                                    </ul>
                                </div>

                                @if($isExpired)
                                    <div class="alert alert-danger mb-0 mt-3">
                                        <small><i class="fa fa-exclamation-triangle"></i> Mã này đã hết hạn</small>
                                    </div>
                                @else
                                    <a href="{{ route('client.checkout', ['coupon_code' => $coupon->code]) }}" 
                                       class="btn btn-primary btn-block mt-3">
                                        <i class="fa fa-shopping-cart"></i> Sử dụng ngay
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($privateCoupons->count() > 0 && $publicCoupons->count() > 0)
                <div class="mt-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="alert alert-info">
                                <strong><i class="fa fa-gift"></i> Mã công khai:</strong> 
                                {{ $publicCoupons->count() }} mã (Mọi người đều có thể sử dụng)
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="alert alert-warning">
                                <strong><i class="fa fa-star"></i> Mã riêng tư:</strong> 
                                {{ $privateCoupons->count() }} mã (Chỉ dành riêng cho bạn)
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endif

            </div>
        </div>
    </div>
</div>
@endsection
