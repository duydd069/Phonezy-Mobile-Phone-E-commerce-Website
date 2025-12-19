@extends('electro.layout')

@section('title', 'Danh mục: ' . $category->name . ' - Electro')

@section('content')

{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="breadcrumb-wrapper">
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('client.index') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('client.index') }}#categories">Danh mục</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
        </ol>
    </div>
</nav>

<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-header">
                    <h2 class="title">Danh mục: {{ $category->name }}</h2>
                    <p class="text-muted">
                        @if($products->total() > 0)
                            Hiển thị {{ $products->count() }} / {{ $products->total() }} sản phẩm
                        @else
                            Không có sản phẩm nào trong danh mục này.
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="row">
            @forelse ($products as $product)
                <div class="col-md-3 col-xs-6">
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
                </div>
            @empty
                <div class="col-md-12">
                    <div class="text-center py-5">
                        <i class="fa fa-box-open" style="font-size: 64px; color: #ccc; margin-bottom: 20px;"></i>
                        <h3>Không có sản phẩm nào.</h3>
                        <p class="text-muted">Danh mục này hiện chưa có sản phẩm. Vui lòng quay lại sau.</p>
                        <a href="{{ route('client.index') }}" class="btn btn-primary mt-3">
                            <i class="fa fa-arrow-left"></i> Về trang chủ
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($products->hasPages())
            <div class="row">
                <div class="col-md-12">
                    <div class="pagination-wrapper mt-4">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

@endsection
