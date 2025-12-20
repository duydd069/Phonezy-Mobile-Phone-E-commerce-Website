@extends('electro.layout')

@section('title', 'Electro - Cửa hàng điện tử | Laptop, Điện thoại, Phụ kiện chính hãng')

@section('meta')
    <meta name="description"
        content="Electro - Mua Laptop, Smartphone, Camera và Phụ kiện chính hãng. Sản phẩm mới, giá tốt, cập nhật liên tục. Giao hàng nhanh toàn quốc.">
    <meta name="keywords" content="laptop, smartphone, camera, phụ kiện, cửa hàng điện tử, bán lẻ công nghệ">
@endsection

@push('styles')
    <link type="text/css" rel="stylesheet" href="{{ asset('electro/css/products.css') }}" />
@endpush

@section('content')

    {{-- Breadcrumb SEO --}}

    {{-- BANNER CAROUSEL --}}
    <div id="banner-carousel" class="carousel slide" data-ride="carousel" data-interval="5000">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <li data-target="#banner-carousel" data-slide-to="0" class="active"></li>
            <li data-target="#banner-carousel" data-slide-to="1"></li>
            <li data-target="#banner-carousel" data-slide-to="2"></li>
            <li data-target="#banner-carousel" data-slide-to="3"></li>
            <li data-target="#banner-carousel" data-slide-to="4"></li>
        </ol>

        <!-- Wrapper for slides -->
        <div class="carousel-inner">
            <div class="item active">
                <img src="{{ url('storage/banners/find-x9-series.png') }}" alt="Find X9 Series Banner">
            </div>
            <div class="item">
                <img src="{{ url('storage/banners/honor-x9d.jpeg') }}" alt="Honor X9D Banner">
            </div>
            <div class="item">
                <img src="{{ url('storage/banners/mua-iphone-17.jpeg') }}" alt="Mua iPhone 17 Banner">
            </div>
            <div class="item">
                <img src="{{ url('storage/banners/mua-xiaomi-15t.jpeg') }}" alt="Mua Xiaomi 15T Banner">
            </div>
            <div class="item">
                <img src="{{ url('storage/banners/poco-m7-pro-copy.jpg') }}" alt="POCO M7 Pro Banner">
            </div>
        </div>

        <!-- Left and right controls -->
        <a class="left carousel-control" href="#banner-carousel" data-slide="prev">
            <span class="fa fa-chevron-left"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#banner-carousel" data-slide="next">
            <span class="fa fa-chevron-right"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
    {{-- /BANNER CAROUSEL --}}

    <section class="section new-products-section">
        <div class="container">

            <header class="section-header d-flex justify-content-between align-items-center">
                <h2 class="title">Sản Phẩm Mới Nhất</h2>
                
            </header>

            <div class="row">
                <div class="products-tabs">

                    {{-- TAB Laptops --}}
                    <div id="tab-laptops" class="tab-pane active">
                        <div class="products-slick" data-nav="#slick-nav-1">

                            @foreach ($products ?? [] as $product)
                                <article class="product" itemscope itemtype="https://schema.org/Product">

                                    {{-- IMAGE --}}
                                    <div class="product-img">
                                        <img src="{{ $product->image ? (preg_match('/^https?:\\/\\//', $product->image) ? $product->image : asset('storage/' . $product->image)) : asset('electro/img/product01.png') }}"
                                            alt="Mua {{ $product->name }} chính hãng giá tốt" itemprop="image">

                                        {{-- Label --}}
                                        <div class="product-label">
                                            @if (($product->created_at ?? null) && \Carbon\Carbon::parse($product->created_at)->gt(now()->subDays(14)))
                                                <span class="new">Mới</span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- BODY --}}
                                    <div class="product-body">
                                        <p class="product-category" itemprop="category">
                                            {{ $product->category->name ?? 'Danh mục' }}
                                        </p>

                                        <h3 class="product-name" itemprop="name">
                                            <a href="{{ route('client.product.show', $product) }}" itemprop="url">
                                                {{ $product->name }}
                                            </a>
                                        </h3>

                                        @php
                                            $variant = $product->variants->first();
                                            $displayPrice = $variant ? ($variant->price_sale ?? $variant->price ?? 0) : 0;
                                        @endphp
                                        <h4 class="product-price">
                                            <span itemprop="price">{{ number_format($displayPrice, 0, ',', '.') }}</span>
                                            ₫
                                            <meta itemprop="priceCurrency" content="VND">
                                        </h4>

                                        {{-- Rating --}}
                                        <div class="product-rating" itemprop="aggregateRating" itemscope
                                            itemtype="https://schema.org/AggregateRating">
                                            <!-- <span itemprop="ratingValue">5</span> ⭐ -->
                                            <meta itemprop="reviewCount" content="1">
                                        </div>

                                        <div class="product-btns">
                                            <button class="add-to-wishlist wishlist-btn" 
                                                    data-product-id="{{ $product->id }}"
                                                    title="Thêm vào wishlist">
                                                <i class="fa fa-heart-o"></i>
                                            </button>
                                            <button class="add-to-compare"><i class="fa fa-exchange"></i></button>
                                            <a href="{{ route('client.product.show', $product) }}" class="quick-view-btn"
                                                title="Xem nhanh">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>

                                    {{-- FORM ADD TO CART --}}
                                    <div class="add-to-cart">
                                        <form action="{{ route('cart.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="add-to-cart-btn">
                                                <i class="fa fa-shopping-cart"></i> add to cart
                                            </button>
                                        </form>
                                    </div>
                                    {{-- END FORM ADD TO CART --}}
                                </article>
                            @endforeach

                        </div>

                        <div id="slick-nav-1" class="products-slick-nav"></div>
                    </div>

                </div>
            </div>

        </div>
    </section>

    {{-- ACCESSORIES FULL SECTION --}}
@if(isset($accessories) && $accessories->count())
<section class="section accessories-full-section">
    <div class="container">

        {{-- HEADER --}}
        <div class="section-header d-flex justify-content-between align-items-center mb-4">
            <h2 class="title">Phụ kiện</h2>
            <a href="{{ route('client.store') }}?category=accessories" class="view-all">
                Xem tất cả
            </a>
        </div>

        <div class="row">
            {{-- LEFT: ACCESSORIES GRID --}}
            <div class="col-md-9">
                <div class="row">

                    @foreach($accessories as $product)
                        <div class="col-md-4 col-sm-6 mb-4">
                            <article class="product">
                                <div class="product-img">
                                    <img
                                        src="{{ $product->image
                                            ? (preg_match('/^https?:\\/\\//', $product->image)
                                                ? $product->image
                                                : asset('storage/' . $product->image))
                                            : asset('electro/img/product01.png') }}"
                                        alt="{{ $product->name }}">
                                </div>

                                <div class="product-body">
                                    <p class="product-category">
                                        {{ $product->category->name ?? 'Phụ kiện' }}
                                    </p>

                                    <h3 class="product-name">
                                        <a href="{{ route('client.product.show', $product) }}">
                                            {{ $product->name }}
                                        </a>
                                    </h3>

                                    @php
                                        $variant = $product->variants->first();
                                        $price = $variant ? ($variant->price_sale ?? $variant->price ?? 0) : 0;
                                    @endphp

                                    <h4 class="product-price">
                                        {{ number_format($price, 0, ',', '.') }} ₫
                                    </h4>

                                    <div class="product-btns">
                                        <button class="add-to-wishlist wishlist-btn"
                                                data-product-id="{{ $product->id }}">
                                            <i class="fa fa-heart-o"></i>
                                        </button>

                                        <a href="{{ route('client.product.show', $product) }}"
                                           class="quick-view-btn">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        </div>
                    @endforeach

                </div>
            </div>

            {{-- RIGHT: PROMO IMAGE --}}
            <div class="col-md-3 hidden-sm hidden-xs">
                <div class="accessories-banner">
                    <a href="#">
                        <img
                            src="https://cdn2.cellphones.com.vn/insecure/rs:fill:321:960/q:90/plain/https://media-asset.cellphones.com.vn/page_configs/01K8WKEW3CH6FD5HGP201X8WAC.png"
                            alt="Accessories Banner"
                            style="width:100%; border-radius:10px;">
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>
@endif



    {{-- Hot Deal Section --}}
    <section id="hot-deal" class="section hot-deal-section">
        <div class="container">
            <div class="hot-deal text-center">
                <ul class="hot-deal-countdown">
                    <li>
                        <div>
                            <h3 id="countdown-days">00</h3><span>Ngày</span>
                        </div>
                    </li>
                    <li>
                        <div>
                            <h3 id="countdown-hours">00</h3><span>Giờ</span>
                        </div>
                    </li>
                    <li>
                        <div>
                            <h3 id="countdown-minutes">00</h3><span>Phút</span>
                        </div>
                    </li>
                    <li>
                        <div>
                            <h3 id="countdown-seconds">00</h3><span>Giây</span>
                        </div>
                    </li>
                </ul>
                <h2 class="text-uppercase">Khuyến mãi tuần này</h2>
                <p>Giảm giá lên đến 50%</p>
                <a class="primary-btn cta-btn" href="#">Mua ngay</a>
            </div>
        </div>
    </section>

    <!-- {{-- Accessories Section: carousel left + promo image right --}}
    @if(isset($accessories) && $accessories->count() > 0)
    <section class="section accessories-section">
        <div class="container">
            <div class="section-header d-flex justify-content-between align-items-center mb-3">
                <h2 class="title">Phụ kiện nổi bật</h2>
                <a href="{{ route('client.store') }}#categories" class="view-all">Xem tất cả phụ kiện</a>
            </div>

            <div class="row align-items-start">
                <div class="col-md-9">
                    <div class="products-slick" data-nav="#accessories-nav">
                        @foreach($accessories as $product)
                        <article class="product">
                            <div class="product-img">
                                <img src="{{ $product->image ? (preg_match('/^https?:\\/\\//', $product->image) ? $product->image : asset('storage/' . $product->image)) : asset('electro/img/product01.png') }}" alt="{{ $product->name }}">
                            </div>
                            <div class="product-body">
                                <p class="product-category">{{ $product->category->name ?? '' }}</p>
                                <h3 class="product-name"><a href="{{ route('client.product.show', $product) }}">{{ $product->name }}</a></h3>
                                @php $variant = $product->variants->first(); $displayPrice = $variant ? ($variant->price_sale ?? $variant->price ?? 0) : 0; @endphp
                                <h4 class="product-price">{{ number_format($displayPrice,0,',','.') }} ₫</h4>
                                <div class="product-btns">
                                    <button class="add-to-wishlist wishlist-btn" data-product-id="{{ $product->id }}" title="Thêm vào wishlist"><i class="fa fa-heart-o"></i></button>
                                    <a href="{{ route('client.product.show', $product) }}" class="quick-view-btn" title="Xem nhanh"><i class="fa fa-eye"></i></a>
                                </div>
                            </div>
                        </article>
                        @endforeach
                    </div>
                    <div id="accessories-nav" class="products-slick-nav mt-2"></div>
                </div>

                <div class="col-md-3 hidden-xs hidden-sm">
                    <div class="accessories-promo">
                        <a href="#">
                            <img src="https://cdn2.cellphones.com.vn/insecure/rs:fill:321:960/q:90/plain/https://media-asset.cellphones.com.vn/page_configs/01K8WKEW3CH6FD5HGP201X8WAC.png" alt="Promo" style="width:100%; border-radius:8px;">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif -->

@push('scripts')
<script>
    $(document).ready(function(){
        if($.fn.slick){
            $('.products-slick').not('.slick-initialized').slick({
                slidesToShow: 4,
                slidesToScroll: 1,
                infinite: false,
                dots: true,
                arrows: true,
                appendArrows: $('#accessories-nav'),
                responsive: [
                    { breakpoint: 992, settings: { slidesToShow: 3 }},
                    { breakpoint: 768, settings: { slidesToShow: 2 }},
                    { breakpoint: 480, settings: { slidesToShow: 1 }}
                ]
            });
        }
    });
</script>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Đặt thời gian kết thúc khuyến mãi (7 ngày từ bây giờ)
    const endDate = new Date();
    endDate.setDate(endDate.getDate() + 7);
    endDate.setHours(23, 59, 59, 999); // Cuối ngày
    
    function updateCountdown() {
        const now = new Date().getTime();
        const distance = endDate.getTime() - now;
        
        if (distance < 0) {
            // Khuyến mãi đã kết thúc
            document.getElementById('countdown-days').textContent = '00';
            document.getElementById('countdown-hours').textContent = '00';
            document.getElementById('countdown-minutes').textContent = '00';
            document.getElementById('countdown-seconds').textContent = '00';
            return;
        }
        
        // Tính toán thời gian còn lại
        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        // Cập nhật DOM
        document.getElementById('countdown-days').textContent = String(days).padStart(2, '0');
        document.getElementById('countdown-hours').textContent = String(hours).padStart(2, '0');
        document.getElementById('countdown-minutes').textContent = String(minutes).padStart(2, '0');
        document.getElementById('countdown-seconds').textContent = String(seconds).padStart(2, '0');
    }
    
    // Cập nhật ngay lập tức
    updateCountdown();
    
    // Cập nhật mỗi giây
    setInterval(updateCountdown, 1000);
});
</script>
@endpush

@endsection
