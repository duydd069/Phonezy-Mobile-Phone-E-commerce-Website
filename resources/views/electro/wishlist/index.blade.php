@extends('electro.layout')

@section('title', 'Wishlist của tôi - Electro')

@section('content')
<!-- BREADCRUMB -->
<div id="breadcrumb" class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <ul class="breadcrumb-tree">
                    <li><a href="{{ route('client.index') }}">Trang chủ</a></li>
                    <li><a href="{{ route('client.account.index') }}">Tài khoản</a></li>
                    <li class="active">Wishlist</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- /BREADCRUMB -->

<!-- SECTION -->
<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title">
                    <h3 class="title">Danh sách yêu thích</h3>
                </div>
            </div>
        </div>

        @if($wishlists->isEmpty())
            <div class="row">
                <div class="col-md-12">
                    <div class="empty-wishlist text-center" style="padding: 60px 20px;">
                        <i class="fa fa-heart-o" style="font-size: 64px; color: #ccc; margin-bottom: 20px;"></i>
                        <h4>Wishlist của bạn đang trống</h4>
                        <p style="color: #999; margin-bottom: 30px;">Hãy thêm sản phẩm yêu thích vào wishlist để dễ dàng tìm lại sau!</p>
                        <a href="{{ route('client.index') }}" class="primary-btn">Tiếp tục mua sắm</a>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                @foreach($wishlists as $wishlist)
                    @php
                        $product = $wishlist->product;
                        $variant = $product->variants->where('status', 'available')->sortBy('price')->first();
                        $displayPrice = $variant ? ($variant->price_sale ?? $variant->price ?? 0) : 0;
                        $isInWishlist = true; // Đã có trong wishlist
                    @endphp
                    
                    <div class="col-md-3 col-xs-6 product-item" data-product-id="{{ $product->id }}">
                        <div class="product">
                            <div class="product-img">
                                <img src="{{ $product->image ? (preg_match('/^https?:\/\//', $product->image) ? $product->image : asset('storage/' . $product->image)) : asset('electro/img/product01.png') }}"
                                    alt="{{ $product->name }}">
                                <div class="product-label">
                                    @if(($product->created_at ?? null) && \Carbon\Carbon::parse($product->created_at)->gt(now()->subDays(14)))
                                        <span class="new">Mới</span>
                                    @endif
                                </div>
                            </div>
                            <div class="product-body">
                                <p class="product-category">{{ $product->category->name ?? 'Danh mục' }}</p>
                                <h3 class="product-name">
                                    <a href="{{ route('client.product.show', $product) }}">{{ $product->name }}</a>
                                </h3>
                                <h4 class="product-price">
                                    {{ number_format($displayPrice, 0, ',', '.') }} ₫
                                </h4>
                                <div class="product-rating">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </div>
                                <div class="product-btns">
                                    <button class="add-to-wishlist wishlist-btn active" 
                                            data-product-id="{{ $product->id }}"
                                            title="Xóa khỏi wishlist">
                                        <i class="fa fa-heart"></i>
                                    </button>
                                    <a href="{{ route('client.product.show', $product) }}" class="quick-view-btn" title="Xem chi tiết">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="add-to-cart">
                                @if($variant)
                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_variant_id" value="{{ $variant->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="add-to-cart-btn">
                                            <i class="fa fa-shopping-cart"></i> Thêm vào giỏ
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('client.product.show', $product) }}" class="add-to-cart-btn">
                                        <i class="fa fa-shopping-cart"></i> Xem chi tiết
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
<!-- /SECTION -->

@push('styles')
<style>
.empty-wishlist {
    background: #f9f9f9;
    border-radius: 8px;
}

.product-item {
    margin-bottom: 30px;
}

.wishlist-btn.active i {
    color: #e74c3c;
}

.wishlist-btn:not(.active) i {
    color: #999;
}

.wishlist-btn:hover i {
    color: #e74c3c;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Xử lý toggle wishlist
    $('.wishlist-btn').on('click', function(e) {
        e.preventDefault();
        var $btn = $(this);
        var productId = $btn.data('product-id');
        var $productItem = $btn.closest('.product-item');
        
        $.ajax({
            url: '{{ route("client.wishlist.toggle") }}',
            method: 'POST',
            data: {
                product_id: productId,
                _token: '{{ csrf_token() }}'
            },
            beforeSend: function() {
                $btn.prop('disabled', true);
            },
            success: function(response) {
                if (response.success) {
                    if (!response.in_wishlist) {
                        // Xóa khỏi wishlist - ẩn sản phẩm với animation
                        $productItem.fadeOut(300, function() {
                            $(this).remove();
                            // Kiểm tra nếu không còn sản phẩm nào thì reload trang
                            if ($('.product-item').length === 0) {
                                location.reload();
                            }
                        });
                        // Cập nhật số lượng wishlist trong header
                        updateWishlistCount();
                    } else {
                        $btn.addClass('active');
                        $btn.find('i').removeClass('fa-heart-o').addClass('fa-heart');
                    }
                }
                // Hiển thị thông báo
                showNotification(response.message, response.success ? 'success' : 'error');
            },
            error: function(xhr) {
                var message = 'Có lỗi xảy ra. Vui lòng thử lại.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                showNotification(message, 'error');
            },
            complete: function() {
                $btn.prop('disabled', false);
            }
        });
    });
    
    function updateWishlistCount() {
        // Reload để cập nhật số lượng wishlist trong header
        $.get('{{ route("client.wishlist.index") }}', function() {
            location.reload();
        });
    }
    
    function showNotification(message, type) {
        // Tạo thông báo đơn giản
        var bgColor = type === 'success' ? '#5cb85c' : '#d9534f';
        var $notification = $('<div>')
            .css({
                'position': 'fixed',
                'top': '20px',
                'right': '20px',
                'background': bgColor,
                'color': '#fff',
                'padding': '15px 20px',
                'border-radius': '4px',
                'z-index': '9999',
                'box-shadow': '0 2px 10px rgba(0,0,0,0.2)'
            })
            .text(message)
            .appendTo('body');
        
        setTimeout(function() {
            $notification.fadeOut(300, function() {
                $(this).remove();
            });
        }, 3000);
    }
});
</script>
@endpush

@endsection
