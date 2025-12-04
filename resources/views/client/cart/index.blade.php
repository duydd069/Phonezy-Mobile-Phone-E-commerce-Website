@extends('electro.layout')

@section('title', 'Giỏ hàng')

@section('content')
    <!-- SECTION -->
    <div class="section">
        <div class="container">
            <div class="row">

                {{-- Cột trái: danh sách sản phẩm --}}
                <div class="col-md-8">
                    <div class="section-title">
                        <h3 class="title">Giỏ hàng của bạn</h3>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($items->isEmpty())
                        <p>Giỏ hàng đang trống.</p>
                        <a href="{{ url('/client') }}" class="primary-btn">
                            Tiếp tục mua sắm
                        </a>
                    @else
                        <div class="shopping-cart">
                            @foreach($items as $item)
                                @php
                                    $variant = $item->variant;
                                    $product = $variant ? $variant->product : null;
                                    $price = $variant ? ($variant->price_sale ?? $variant->price ?? 0) : 0;
                                    
                                    // Lấy ảnh từ variant hoặc product
                                    $image = null;
                                    if ($variant && $variant->image) {
                                        $image = preg_match('/^https?:\/\//', $variant->image) ? $variant->image : asset('storage/' . $variant->image);
                                    } elseif ($product && $product->image) {
                                        $image = preg_match('/^https?:\/\//', $product->image) ? $product->image : asset('storage/' . $product->image);
                                    }
                                    
                                    // Tạo tên sản phẩm với thông tin variant
                                    $productName = $product ? $product->name : 'Sản phẩm';
                                    $variantInfo = [];
                                    if ($variant) {
                                        if ($variant->storage) $variantInfo[] = $variant->storage->storage;
                                        if ($variant->version) $variantInfo[] = $variant->version->name;
                                        if ($variant->color) $variantInfo[] = $variant->color->name;
                                    }
                                    if (!empty($variantInfo)) {
                                        $productName .= ' (' . implode(', ', $variantInfo) . ')';
                                    }
                                @endphp

                                <div class="product-widget" style="border-bottom: 1px solid #f0f0f0; padding-bottom: 15px; margin-bottom: 15px;">
                                    <div class="product-img">
                                        @if($image)
                                            <img src="{{ $image }}" alt="{{ $productName }}">
                                        @else
                                            <img src="{{ asset('electro/img/product01.png') }}" alt="">
                                        @endif
                                    </div>
                                    <div class="product-body">
                                        <h3 class="product-name">
                                            <a href="{{ $product ? route('client.product.show', $product->slug) : '#' }}">
                                                {{ $productName }}
                                            </a>
                                        </h3>
                                        @if($variant && $variant->sku)
                                            <p class="text-muted small" style="margin: 5px 0;">SKU: {{ $variant->sku }}</p>
                                        @endif

                                        <h4 class="product-price">
                                            <span class="qty">{{ $item->quantity }}x</span>
                                            {{ number_format($price, 0, ',', '.') }} ₫
                                        </h4>

                                        {{-- Form cập nhật số lượng --}}
                                        <form action="{{ route('cart.update') }}" method="POST" class="form-inline" style="margin-top: 5px;">
                                            @csrf
                                            <input type="hidden" name="cart_item_id" value="{{ $item->id }}">
                                            <input type="number"
                                                   name="quantity"
                                                   value="{{ $item->quantity }}"
                                                   min="1"
                                                   class="input"
                                                   style="width: 80px; display:inline-block; margin-right:5px;">
                                            <button type="submit" class="primary-btn">
                                                Cập nhật
                                            </button>
                                        </form>
                                    </div>

                                    {{-- Xóa sản phẩm --}}
                                    <form action="{{ route('cart.remove') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="cart_item_id" value="{{ $item->id }}">
                                        <button class="delete" onclick="return confirm('Xóa sản phẩm này?')">
                                            <i class="fa fa-close"></i>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>

                        {{-- Nút xóa toàn bộ --}}
                        <form action="{{ route('cart.clear') }}" method="POST" style="margin-top: 10px;">
                            @csrf
                            <button type="submit" class="primary-btn cta-btn"
                                    style="background: #fff; color:#D10024; border:1px solid #D10024;"
                                    onclick="return confirm('Xóa toàn bộ giỏ hàng?')">
                                Xóa toàn bộ giỏ
                            </button>
                        </form>
                    @endif
                </div>

                {{-- Cột phải: tóm tắt đơn hàng --}}
                <div class="col-md-4">
                    <div class="order-details">
                        <div class="section-title text-center">
                            <h3 class="title"> Giỏ hàng</h3>
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
                                        $product = $variant ? $variant->product : null;
                                        $price = $variant ? ($variant->price_sale ?? $variant->price ?? 0) : 0;
                                        $productName = $product ? $product->name : 'Sản phẩm';
                                        
                                        // Thêm thông tin variant vào tên
                                        $variantInfo = [];
                                        if ($variant) {
                                            if ($variant->storage) $variantInfo[] = $variant->storage->storage;
                                            if ($variant->version) $variantInfo[] = $variant->version->name;
                                            if ($variant->color) $variantInfo[] = $variant->color->name;
                                        }
                                        if (!empty($variantInfo)) {
                                            $productName .= ' (' . implode(', ', $variantInfo) . ')';
                                        }
                                    @endphp
                                    <div class="order-col">
                                        <div>{{ $item->quantity }}x {{ $productName }}</div>
                                        <div>{{ number_format($price * $item->quantity, 0, ',', '.') }} ₫</div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="order-col">
                                <div><strong>TỔNG CỘNG</strong></div>
                                <div><strong class="order-total">
                                        {{ number_format($total, 0, ',', '.') }} ₫
                                    </strong>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('client.checkout') }}" class="primary-btn order-submit">
                            Tiến hành thanh toán
                        </a>

                        <a href="{{ url('/client') }}" class="primary-btn cta-btn" style="margin-top:10px;">
                            ← Tiếp tục mua sắm
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- /SECTION -->
@endsection
