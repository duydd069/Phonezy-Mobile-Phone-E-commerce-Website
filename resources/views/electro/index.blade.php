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
    <nav aria-label="breadcrumb" class="breadcrumb-wrapper">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Sản phẩm mới</li>
        </ol>
    </nav>

    <section class="section categories-section">
        <div class="container">
            <div class="row">

                {{-- Category 1 --}}
                <div class="col-md-4 col-xs-6">
                    <article class="shop">
                        <div class="shop-img">
                            <img src="{{ asset('electro/img/shop01.png') }}" alt="Laptop chính hãng - Electro">
                        </div>
                        <div class="shop-body">
                            <h2 class="category-title">Laptop Chính Hãng</h2>
                            <a href="#" class="cta-btn">Xem ngay <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </article>
                </div>

                {{-- Category 2 --}}
                <div class="col-md-4 col-xs-6">
                    <article class="shop">
                        <div class="shop-img">
                            <img src="{{ asset('electro/img/shop03.png') }}" alt="Phụ kiện công nghệ - Electro">
                        </div>
                        <div class="shop-body">
                            <h2 class="category-title">Phụ Kiện Công Nghệ</h2>
                            <a href="#" class="cta-btn">Xem ngay <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </article>
                </div>

                {{-- Category 3 --}}
                <div class="col-md-4 col-xs-6">
                    <article class="shop">
                        <div class="shop-img">
                            <img src="{{ asset('electro/img/shop02.png') }}" alt="Máy ảnh và thiết bị quay chụp - Electro">
                        </div>
                        <div class="shop-body">
                            <h2 class="category-title">Máy Ảnh - Camera</h2>
                            <a href="#" class="cta-btn">Xem ngay <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </article>
                </div>

            </div>
        </div>
    </section>

    <section class="section new-products-section">
        <div class="container">

            <header class="section-header d-flex justify-content-between align-items-center">
                <h2 class="title">Sản Phẩm Mới Nhất</h2>
                <ul class="section-tab-nav tab-nav">
                    <li class="active"><a data-toggle="tab" href="#tab-laptops">Laptop</a></li>
                    <li><a data-toggle="tab" href="#tab-phones">Smartphone</a></li>
                    <li><a data-toggle="tab" href="#tab-cameras">Camera</a></li>
                    <li><a data-toggle="tab" href="#tab-accessories">Phụ kiện</a></li>
                </ul>
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
                                            <span itemprop="ratingValue">5</span> ⭐
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

    {{-- Hot Deal Section --}}
    <section id="hot-deal" class="section hot-deal-section">
        <div class="container">
            <div class="hot-deal text-center">
                <ul class="hot-deal-countdown">
                    <li>
                        <div>
                            <h3>02</h3><span>Ngày</span>
                        </div>
                    </li>
                    <li>
                        <div>
                            <h3>10</h3><span>Giờ</span>
                        </div>
                    </li>
                    <li>
                        <div>
                            <h3>34</h3><span>Phút</span>
                        </div>
                    </li>
                    <li>
                        <div>
                            <h3>60</h3><span>Giây</span>
                        </div>
                    </li>
                </ul>
                <h2 class="text-uppercase">Khuyến mãi tuần này</h2>
                <p>Giảm giá lên đến 50%</p>
                <a class="primary-btn cta-btn" href="#">Mua ngay</a>
            </div>
        </div>
    </section>

@endsection
