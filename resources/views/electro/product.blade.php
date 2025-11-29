@extends('electro.layout')

@section('title', $product->name . ' - Electro')

@push('styles')
<style>
.product-gallery {
    position: relative;
}
.product-gallery-main {
    margin-bottom: 15px;
    border: 1px solid #E4E7ED;
    border-radius: 4px;
    overflow: hidden;
    background: #fff;
}
.product-gallery-main img {
    width: 100%;
    height: auto;
    display: block;
    transition: opacity 0.3s ease;
}
.product-gallery-thumbs-wrapper {
    position: relative;
    margin-top: 15px;
    padding: 0 40px;
}
.product-gallery-thumbs {
    display: flex;
    gap: 10px;
    overflow-x: auto;
    overflow-y: hidden;
    scroll-behavior: smooth;
    padding: 5px 0;
    margin: 0;
    scrollbar-width: thin;
    scrollbar-color: #D10024 #E4E7ED;
}
.product-gallery-thumbs::-webkit-scrollbar {
    height: 6px;
}
.product-gallery-thumbs::-webkit-scrollbar-track {
    background: #E4E7ED;
    border-radius: 3px;
}
.product-gallery-thumbs::-webkit-scrollbar-thumb {
    background: #D10024;
    border-radius: 3px;
}
.product-gallery-thumbs::-webkit-scrollbar-thumb:hover {
    background: #B8001F;
}
.product-gallery-thumb {
    flex: 0 0 auto;
    width: 100px;
    height: 100px;
    border: 2px solid #E4E7ED;
    border-radius: 4px;
    overflow: hidden;
    cursor: pointer;
    transition: all 0.3s;
    position: relative;
    background: #fff;
}
.product-gallery-thumb:hover {
    border-color: #D10024;
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.product-gallery-thumb.active {
    border-color: #D10024;
    border-width: 3px;
    box-shadow: 0 0 0 2px rgba(209, 0, 36, 0.1);
}
.product-gallery-thumb.active::after {
    content: '';
    position: absolute;
    top: 5px;
    right: 5px;
    width: 20px;
    height: 20px;
    background: #D10024;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.product-gallery-thumb.active::before {
    content: '✓';
    position: absolute;
    top: 5px;
    right: 5px;
    width: 20px;
    height: 20px;
    color: #fff;
    font-size: 12px;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1;
}
.product-gallery-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}
.product-gallery-thumb:hover img {
    transform: scale(1.1);
}
.gallery-nav-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 35px;
    height: 35px;
    background: #fff;
    border: 2px solid #E4E7ED;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
    transition: all 0.3s;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.gallery-nav-btn:hover {
    background: #D10024;
    border-color: #D10024;
    color: #fff;
}
.gallery-nav-btn.prev {
    left: 0;
}
.gallery-nav-btn.next {
    right: 0;
}
.gallery-nav-btn:disabled {
    opacity: 0.3;
    cursor: not-allowed;
}
.gallery-nav-btn:disabled:hover {
    background: #fff;
    border-color: #E4E7ED;
    color: inherit;
}
.product-gallery-thumb.feature-thumb {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #FF6B9D 0%, #FF8E53 100%);
    color: #fff;
    font-size: 12px;
    font-weight: 600;
    text-align: center;
    padding: 10px;
}
.product-gallery-thumb.feature-thumb i {
    font-size: 24px;
    margin-bottom: 5px;
}
.product-gallery-thumb.feature-thumb:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 107, 157, 0.4);
}
@media (max-width: 768px) {
    .product-gallery-thumbs-wrapper {
        padding: 0 30px;
    }
    .gallery-nav-btn {
        width: 30px;
        height: 30px;
        font-size: 12px;
    }
    .product-gallery-thumb {
        width: 80px;
        height: 80px;
    }
}
.product-info {
    padding-left: 30px;
}
.product-name {
    font-size: 28px;
    font-weight: 600;
    margin-bottom: 15px;
    color: #2B2D42;
}
.product-meta {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid #E4E7ED;
}
.product-rating {
    color: #FFC107;
}
.product-views {
    color: #8D99AE;
    font-size: 14px;
}
.product-price-section {
    margin-bottom: 25px;
    padding: 20px;
    background: #F8F9FA;
    border-radius: 8px;
    border-left: 4px solid #D10024;
}
.product-price {
    font-size: 36px;
    font-weight: 700;
    color: #D10024;
    margin-bottom: 8px;
    line-height: 1.2;
}
.product-price-old {
    font-size: 22px;
    color: #8D99AE;
    text-decoration: line-through;
    margin-right: 15px;
    font-weight: 500;
}
.product-price-current {
    font-size: 36px;
    font-weight: 700;
    color: #D10024;
}
.product-discount {
    display: inline-block;
    background: linear-gradient(135deg, #D10024 0%, #FF1744 100%);
    color: #fff;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 700;
    box-shadow: 0 2px 8px rgba(209, 0, 36, 0.3);
    margin-left: 10px;
}
.price-wrapper {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}
.price-label {
    font-size: 14px;
    color: #8D99AE;
    margin-right: 5px;
}
.product-stock-info {
    margin-bottom: 20px;
}
.stock-badge {
    display: inline-block;
    padding: 5px 12px;
    border-radius: 3px;
    font-size: 14px;
    font-weight: 600;
}
.stock-badge.in-stock {
    background: #E8F5E9;
    color: #2E7D32;
}
.stock-badge.out-of-stock {
    background: #FFEBEE;
    color: #C62828;
}
.variant-selection {
    margin: 25px 0;
}
.variant-label {
    font-weight: 600;
    margin-bottom: 12px;
    color: #2B2D42;
    display: block;
}
.variant-options {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}
.variant-option {
    position: relative;
    border: 2px solid #E4E7ED;
    padding: 12px 20px;
    cursor: pointer;
    border-radius: 4px;
    min-width: 120px;
    text-align: center;
    transition: all 0.3s;
    background: #fff;
}
.variant-option:hover {
    border-color: #D10024;
}
.variant-option.selected {
    border-color: #D10024;
    background: #FFF5F5;
}
.variant-option .fa-check {
    position: absolute;
    top: 5px;
    right: 5px;
    color: #D10024;
    font-size: 14px;
}
.variant-option.color-variant {
    padding: 12px;
    min-width: auto;
    max-width: 120px;
    text-align: center;
}
.variant-option.color-variant img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 4px;
    margin-bottom: 8px;
    display: block;
    margin-left: auto;
    margin-right: auto;
}
.quantity-selector {
    display: flex;
    align-items: center;
    gap: 15px;
    margin: 25px 0;
}
.quantity-input-group {
    display: flex;
    border: 1px solid #E4E7ED;
    border-radius: 4px;
    overflow: hidden;
}
.quantity-btn {
    width: 40px;
    height: 40px;
    border: none;
    background: #F8F9FA;
    cursor: pointer;
    font-size: 18px;
    color: #2B2D42;
    transition: all 0.3s;
}
.quantity-btn:hover {
    background: #E4E7ED;
}
.quantity-input {
    width: 60px;
    height: 40px;
    border: none;
    text-align: center;
    font-size: 16px;
    font-weight: 600;
}
.product-actions {
    display: flex;
    gap: 15px;
    margin-top: 30px;
}
.btn-add-cart {
    flex: 1;
    padding: 15px 30px;
    background: #D10024;
    color: #fff;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}
.btn-add-cart:hover {
    background: #B8001F;
}
.btn-add-cart:disabled {
    background: #8D99AE;
    cursor: not-allowed;
    opacity: 0.6;
}
.btn-buy-now {
    flex: 1;
    padding: 15px 30px;
    background: #2B2D42;
    color: #fff;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}
.btn-buy-now:disabled {
    background: #8D99AE;
    cursor: not-allowed;
    opacity: 0.6;
}
}
.btn-buy-now:hover {
    background: #1A1C2E;
}
.product-features {
    margin-top: 30px;
    padding-top: 30px;
    border-top: 1px solid #E4E7ED;
}
.feature-item {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 15px;
    color: #2B2D42;
}
.feature-item i {
    color: #D10024;
    width: 20px;
}
.product-tabs {
    margin-top: 50px;
}
.nav-tabs {
    border-bottom: 2px solid #E4E7ED;
}
.nav-tabs .nav-link {
    border: none;
    color: #8D99AE;
    font-weight: 600;
    padding: 15px 25px;
}
.nav-tabs .nav-link.active {
    color: #D10024;
    border-bottom: 2px solid #D10024;
}
.tab-content {
    padding: 30px 0;
}
.breadcrumb {
    background: transparent;
    padding: 15px 0;
    margin-bottom: 30px;
}
.breadcrumb-item a {
    color: #8D99AE;
    text-decoration: none;
}
.breadcrumb-item.active {
    color: #2B2D42;
}
.variants-table {
    margin-top: 20px;
}
.select-variant-btn {
    padding: 5px 15px;
    font-size: 14px;
}
.select-variant-btn:hover {
    background: #B8001F;
    border-color: #B8001F;
}
.product-album {
    margin-top: 30px;
}
.album-item {
    position: relative;
    cursor: pointer;
    border-radius: 8px;
    overflow: hidden;
    border: 2px solid #E4E7ED;
    transition: all 0.3s;
    aspect-ratio: 1;
}
.album-item:hover {
    border-color: #D10024;
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
.album-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}
.album-item:hover img {
    transform: scale(1.1);
}
.album-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s;
}
.album-item:hover .album-overlay {
    opacity: 1;
}
.album-overlay i {
    color: #fff;
    font-size: 32px;
}
#imageModal .modal-body {
    padding: 0;
}
#imageModal .carousel-item img {
    max-height: 70vh;
}
/* Toast Notification */
.toast-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background: #fff;
    padding: 15px 20px;
    border-radius: 4px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    z-index: 10000;
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 300px;
    opacity: 0;
    transform: translateX(400px);
    transition: all 0.3s ease;
    font-size: 14px;
    font-weight: 500;
}
.toast-notification.show {
    opacity: 1;
    transform: translateX(0);
}
.toast-notification i {
    font-size: 18px;
}
.toast-success {
    border-left: 4px solid #2E7D32;
    color: #2E7D32;
}
.toast-success i {
    color: #2E7D32;
}
.toast-error {
    border-left: 4px solid #D10024;
    color: #D10024;
}
.toast-error i {
    color: #D10024;
}
    object-fit: contain;
    background: #f8f9fa;
}
#imageCounter {
    font-size: 14px;
    color: #8D99AE;
    font-weight: 600;
}
.section-title {
    margin-bottom: 30px;
}
.section-title .title {
    font-size: 24px;
    font-weight: 600;
    color: #2B2D42;
    margin: 0;
}
</style>
@endpush

@section('content')
<!-- Breadcrumb -->
<div class="section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('client.index') }}">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="#">{{ $product->category->name ?? 'Danh mục' }}</a></li>
                <li class="breadcrumb-item active">{{ $product->name }}</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Product Detail -->
<div class="section">
    <div class="container">
        <div class="row">
            <!-- Product Gallery -->
            <div class="col-md-5">
                <div class="product-gallery">
                    @php
                        $allImages = collect();
                        $mainImage = $product->image ? (preg_match('/^https?:\\/\\//', $product->image) ? $product->image : asset('storage/' . $product->image)) : asset('electro/img/product01.png');
                        $allImages->push($mainImage);
                        
                        // Add product images
                        if($product->images && $product->images->count() > 0) {
                            foreach($product->images as $img) {
                                $imgUrl = preg_match('/^https?:\\/\\//', $img->image_url) ? $img->image_url : asset('storage/' . $img->image_url);
                                if(!$allImages->contains($imgUrl)) {
                                    $allImages->push($imgUrl);
                                }
                            }
                        }
                        
                        // If has variants, add variant images
                        $hasVariants = ($product->has_variant || ($product->variants && $product->variants->count() > 0));
                        if($hasVariants && $product->variants && $product->variants->count() > 0) {
                            $firstVariant = $product->variants->first();
                            if($firstVariant->image) {
                                $variantImg = preg_match('/^https?:\\/\\//', $firstVariant->image) ? $firstVariant->image : asset('storage/' . $firstVariant->image);
                                if(!$allImages->contains($variantImg)) {
                                    $allImages->push($variantImg);
                                }
                            }
                            
                            // Add variant images from variant_images table
                            if($firstVariant->images && $firstVariant->images->count() > 0) {
                                foreach($firstVariant->images as $vImg) {
                                    $vImgUrl = preg_match('/^https?:\\/\\//', $vImg->image_url) ? $vImg->image_url : asset('storage/' . $vImg->image_url);
                                    if(!$allImages->contains($vImgUrl)) {
                                        $allImages->push($vImgUrl);
                                    }
                                }
                            }
                        }
                    @endphp
                    
                    <div class="product-gallery-main">
                        <img id="main-product-image" src="{{ $allImages->first() }}" alt="{{ $product->name }}">
                    </div>
                    
                    <div class="product-gallery-thumbs-wrapper" id="gallery-thumbs-wrapper">
                        <button class="gallery-nav-btn prev" id="gallery-prev" aria-label="Previous" style="display: none;">
                            <i class="fa fa-chevron-left"></i>
                        </button>
                        <div class="product-gallery-thumbs" id="product-gallery-thumbs">
                            {{-- Thumbnail đặc biệt cho tính năng nổi bật --}}
                            <div class="product-gallery-thumb feature-thumb" data-feature="true">
                                <i class="fa fa-star"></i>
                                <span>Tính năng nổi bật</span>
                            </div>
                            @if($allImages->count() > 0)
                                @foreach($allImages as $index => $thumb)
                                <div class="product-gallery-thumb {{ $loop->first ? 'active' : '' }}" data-image="{{ $thumb }}" data-index="{{ $index }}">
                                    <img src="{{ $thumb }}" alt="{{ $product->name }} - Ảnh {{ $index + 1 }}" loading="lazy">
                                </div>
                                @endforeach
                            @endif
                        </div>
                        <button class="gallery-nav-btn next" id="gallery-next" aria-label="Next" style="display: none;">
                            <i class="fa fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Product Info -->
            <div class="col-md-7">
                <div class="product-info">
                    <h1 class="product-name">{{ $product->name }}</h1>
                    
                    <div class="product-meta">
                        <div class="product-rating">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                        </div>
                        <div class="product-views">
                            <i class="fa fa-eye"></i> {{ number_format($product->views) }} lượt xem
                        </div>
                        <div class="product-sku">
                            <span style="color: #8D99AE;">SKU:</span> 
                            <span id="current-sku" style="font-weight: 600; color: #2B2D42;">
                                @php
                                    $hasVariants = ($product->has_variant || ($product->variants && $product->variants->count() > 0));
                                    $initialSku = $product->id;
                                    if($hasVariants && $product->variants && $product->variants->count() > 0) {
                                        $firstVariant = $product->variants->first();
                                        $initialSku = $firstVariant->sku ?? $product->id;
                                    }
                                @endphp
                                {{ $initialSku }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="product-price-section">
                        @php
                            // Logic: Nếu có variant -> lấy giá từ variant, không thì lấy từ product
                            $currentPrice = $product->price;
                            $currentPriceSale = null;
                            $currentVariant = null;
                            $hasVariants = ($product->has_variant || ($product->variants && $product->variants->count() > 0));
                            
                            if($hasVariants && $product->variants && $product->variants->count() > 0) {
                                $currentVariant = $product->variants->first();
                                $currentPrice = $currentVariant->price;
                                $currentPriceSale = $currentVariant->price_sale;
                            }
                            
                            // Giá hiển thị: ưu tiên giá sale, nếu không có thì dùng giá gốc
                            $displayPrice = $currentPriceSale ?? $currentPrice;
                            
                            // Tính % giảm giá
                            $discountPercent = 0;
                            if($currentPriceSale && $currentPrice > 0) {
                                $discountPercent = round((($currentPrice - $currentPriceSale) / $currentPrice) * 100);
                            }
                            
                            // Kiểm tra có đang giảm giá không
                            $hasDiscount = $currentPriceSale && $currentPriceSale < $currentPrice;
                        @endphp
                        
                        @if($hasDiscount)
                            {{-- Có giảm giá: Hiển thị giá sale lớn, giá gốc gạch ngang, % giảm --}}
                            <div style="margin-bottom: 10px;">
                                <span class="price-label">Giá khuyến mãi:</span>
                                <span class="product-price-current" id="main-product-price">
                                    {{ number_format($displayPrice, 0, ',', '.') }} ₫
                                </span>
                            </div>
                            <div class="price-wrapper">
                                <span class="product-price-old" id="old-price">
                                    {{ number_format($currentPrice, 0, ',', '.') }} ₫
                                </span>
                                <span class="product-discount" id="discount-badge">
                                    -{{ $discountPercent }}%
                                </span>
                            </div>
                            <div style="margin-top: 8px; font-size: 13px; color: #2E7D32;">
                                <i class="fa fa-tag"></i> Tiết kiệm: <strong>{{ number_format($currentPrice - $currentPriceSale, 0, ',', '.') }} ₫</strong>
                            </div>
                        @else
                            {{-- Không giảm giá: Chỉ hiển thị giá gốc --}}
                            <div>
                                <span class="price-label">Giá bán:</span>
                                <span class="product-price" id="main-product-price">
                                    {{ number_format($displayPrice, 0, ',', '.') }} ₫
                                </span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="product-stock-info">
                        @php
                            $hasVariants = ($product->has_variant || ($product->variants && $product->variants->count() > 0));
                            $currentStock = ($hasVariants && $currentVariant) ? $currentVariant->stock : 0;
                        @endphp
                        <span class="stock-badge {{ $currentStock > 0 ? 'in-stock' : 'out-of-stock' }}" id="stock-badge">
                            <i class="fa fa-{{ $currentStock > 0 ? 'check-circle' : 'times-circle' }}"></i>
                            {{ $currentStock > 0 ? 'Còn hàng (' . $currentStock . ' sản phẩm)' : 'Đã hết hàng' }}
                        </span>
                    </div>
                    
                    @php
                        $hasVariants = ($product->has_variant || ($product->variants && $product->variants->count() > 0));
                    @endphp
                    @if($hasVariants && $product->variants && $product->variants->count() > 0)
                        @php
                            // Nhóm variants theo storage_id và version_id để tạo các combination
                            $storageVersionCombos = [];
                            $allVariantsData = [];
                            
                            foreach($product->variants as $variant) {
                                $isAvailable = ($variant->stock ?? 0) > 0 && $variant->status === 'available';
                                $storageId = $variant->storage_id ?? 'none';
                                $versionId = $variant->version_id ?? 'none';
                                $colorId = $variant->color_id ?? 'none';
                                
                                $comboKey = $storageId . '_' . $versionId;
                                
                                if(!isset($storageVersionCombos[$comboKey])) {
                                    $storageVersionCombos[$comboKey] = [
                                        'storage_id' => $storageId,
                                        'version_id' => $versionId,
                                        'storage_name' => $variant->storage ? $variant->storage->storage : '',
                                        'version_name' => $variant->version ? $variant->version->name : '',
                                        'variants' => []
                                    ];
                                }
                                
                                $variantImage = $variant->image ? (preg_match('/^https?:\\/\\//', $variant->image) ? $variant->image : asset('storage/' . $variant->image)) : $mainImage;
                                
                                $allVariantsData[] = [
                                    'id' => $variant->id,
                                    'storage_id' => $storageId,
                                    'version_id' => $versionId,
                                    'color_id' => $colorId,
                                    'price' => $variant->price,
                                    'price_sale' => $variant->price_sale,
                                    'image' => $variantImage,
                                    'sku' => $variant->sku,
                                    'stock' => $variant->stock,
                                    'is_available' => $isAvailable,
                                    'color_name' => $variant->color ? $variant->color->name : '',
                                    'color_hex' => $variant->color ? $variant->color->hex_code : null,
                                ];
                                
                                $storageVersionCombos[$comboKey]['variants'][] = [
                                    'id' => $variant->id,
                                    'color_id' => $colorId,
                                    'color_name' => $variant->color ? $variant->color->name : '',
                                    'color_hex' => $variant->color ? $variant->color->hex_code : null,
                                    'price' => $variant->price,
                                    'price_sale' => $variant->price_sale,
                                    'image' => $variantImage,
                                    'sku' => $variant->sku,
                                    'stock' => $variant->stock,
                                    'is_available' => $isAvailable,
                                ];
                            }
                            
                            // Lấy combo đầu tiên làm mặc định
                            $firstCombo = null;
                            $firstComboKey = null;
                            if(!empty($storageVersionCombos)) {
                                $firstComboKey = array_key_first($storageVersionCombos);
                                $firstCombo = $storageVersionCombos[$firstComboKey];
                            }
                        @endphp
                        
                        @if(count($storageVersionCombos) > 0)
                        {{-- Chọn Storage + Version --}}
                        <div class="variant-selection">
                            <label class="variant-label">Chọn dung lượng và phiên bản:</label>
                            <div class="variant-options">
                                @foreach($storageVersionCombos as $comboKey => $combo)
                                    @php 
                                        $isFirst = $loop->first;
                                        $displayName = trim(($combo['storage_name'] ?? '') . ' ' . ($combo['version_name'] ?? ''));
                                        if(empty($displayName)) {
                                            $displayName = 'Mặc định';
                                        }
                                        // Lấy giá từ variant đầu tiên của combo này
                                        $firstVariantInCombo = $combo['variants'][0] ?? null;
                                        $comboPrice = $firstVariantInCombo ? ($firstVariantInCombo['price_sale'] ?? $firstVariantInCombo['price']) : 0;
                                    @endphp
                                    <div class="variant-option storage-version-combo {{ $isFirst ? 'selected' : '' }}" 
                                         data-combo-key="{{ $comboKey }}"
                                         data-storage-id="{{ $combo['storage_id'] }}"
                                         data-version-id="{{ $combo['version_id'] }}"
                                         data-price="{{ $comboPrice }}">
                                        @if($isFirst)
                                            <i class="fa fa-check"></i>
                                        @endif
                                        <div style="font-weight: bold;">{{ $displayName }}</div>
                                        <div style="font-size: 12px; color: #8D99AE; margin-top: 5px;">
                                            Từ {{ number_format($comboPrice, 0, ',', '.') }} ₫
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        {{-- Chọn Màu sắc (sẽ được cập nhật động theo combo đã chọn) --}}
                        <div class="variant-selection" id="color-selection-container">
                            <label class="variant-label">Chọn màu sắc:</label>
                            <div class="variant-options" id="color-options">
                                @if($firstCombo && count($firstCombo['variants']) > 0)
                                    @foreach($firstCombo['variants'] as $index => $colorVariant)
                                        @php 
                                            $isFirst = $index === 0;
                                            $colorImage = $colorVariant['image'] ?? $mainImage;
                                            $isAvailable = $colorVariant['is_available'] && ($colorVariant['stock'] ?? 0) > 0;
                                            $availabilityText = $isAvailable ? 'Còn hàng' : 'Đã hết hàng';
                                            $availabilityClass = $isAvailable ? 'text-success' : 'text-danger';
                                        @endphp
                                        <div class="variant-option color-variant {{ $isFirst ? 'selected' : '' }}" 
                                             data-variant-id="{{ $colorVariant['id'] }}"
                                             data-storage-id="{{ $firstCombo['storage_id'] }}"
                                             data-version-id="{{ $firstCombo['version_id'] }}"
                                             data-color-id="{{ $colorVariant['color_id'] }}"
                                             data-price="{{ $colorVariant['price'] }}"
                                             data-price-sale="{{ $colorVariant['price_sale'] ?? '' }}"
                                             data-image="{{ $colorImage }}"
                                             data-sku="{{ $colorVariant['sku'] }}"
                                             data-stock="{{ $colorVariant['stock'] }}"
                                             data-available="{{ $isAvailable ? '1' : '0' }}">
                                            @if($isFirst)
                                                <i class="fa fa-check"></i>
                                            @endif
                                            <div style="text-align: center;">
                                                <img src="{{ $colorImage }}" alt="{{ $colorVariant['color_name'] }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px; margin-bottom: 8px; display: block; margin-left: auto; margin-right: auto;">
                                                <div style="font-weight: bold; margin-top: 8px; font-size: 13px;">{{ $colorVariant['color_name'] ?: 'Màu sắc' }}</div>
                                                <div style="font-size: 12px; color: #8D99AE; margin-top: 5px;">
                                                    {{ number_format($colorVariant['price_sale'] ?? $colorVariant['price'], 0, ',', '.') }} ₫
                                                </div>
                                                <div class="mt-1" style="font-size: 12px;">
                                                    <span class="{{ $availabilityClass }}">{{ $availabilityText }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        
                        {{-- Lưu tất cả variants data vào JavaScript --}}
                        <script>
                            window.productVariantsData = @json($allVariantsData);
                            window.storageVersionCombos = @json($storageVersionCombos);
                        </script>
                        @endif
                    @endif
                    
                    <div class="quantity-selector">
                        <label class="variant-label" style="margin: 0;">Số lượng:</label>
                        <div class="quantity-input-group">
                            <button type="button" class="quantity-btn" id="quantity-decrease">-</button>
                            <input type="number" class="quantity-input" id="quantity-input" value="1" min="1" max="100">
                            <button type="button" class="quantity-btn" id="quantity-increase">+</button>
                        </div>
                    </div>
                    
                    <div class="product-actions">
                        @php
                            $hasVariants = ($product->has_variant || ($product->variants && $product->variants->count() > 0));
                            $initialVariantId = null;
                            
                            // Lấy variant đầu tiên available làm mặc định
                            if($hasVariants && $product->variants && $product->variants->count() > 0) {
                                // Tìm variant đầu tiên có stock > 0 và status = available
                                $firstAvailableVariant = $product->variants->firstWhere(function($v) {
                                    return $v->status === 'available' && ($v->stock ?? 0) > 0;
                                });
                                
                                // Nếu không có variant available, lấy variant đầu tiên
                                $initialVariantId = $firstAvailableVariant ? $firstAvailableVariant->id : $product->variants->first()->id;
                            }
                        @endphp
                        <button class="btn-add-cart" id="btn-add-cart" data-variant-id="{{ $initialVariantId ?? '' }}">
                            <i class="fa fa-shopping-cart"></i>
                            Thêm vào giỏ hàng
                        </button>
                        <button class="btn-buy-now" id="btn-buy-now" data-variant-id="{{ $initialVariantId ?? '' }}">
                            Mua ngay
                        </button>
                    </div>
                    
                    <div class="product-features">
                        <div class="feature-item">
                            <i class="fa fa-truck"></i>
                            <span>Miễn phí vận chuyển cho đơn hàng trên 500.000₫</span>
                        </div>
                        <div class="feature-item">
                            <i class="fa fa-shield"></i>
                            <span>Bảo hành chính hãng 12 tháng</span>
                        </div>
                        <div class="feature-item">
                            <i class="fa fa-undo"></i>
                            <span>Đổi trả trong 7 ngày nếu lỗi sản phẩm</span>
                        </div>
                        <div class="feature-item">
                            <i class="fa fa-headphones"></i>
                            <span>Hỗ trợ 24/7</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Product Tabs -->
        <div class="product-tabs">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#description">Mô tả sản phẩm</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#specifications">Thông số kỹ thuật</a>
                </li>
                @php
                    $hasVariants = ($product->has_variant || ($product->variants && $product->variants->count() > 0));
                @endphp
                @if($hasVariants && $product->variants && $product->variants->count() > 0)
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#variants">Tất cả biến thể</a>
                </li>
                @endif
            </ul>
            <div class="tab-content">
                <div id="description" class="tab-pane fade show active">
                    <p>{!! nl2br(e($product->description ?? 'Đang cập nhật...')) !!}</p>
                </div>
                <div id="specifications" class="tab-pane fade">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td><strong>Danh mục:</strong></td>
                                <td>{{ $product->category->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Thương hiệu:</strong></td>
                                <td>{{ $product->brand->name ?? 'N/A' }}</td>
                            </tr>
                            @if($currentVariant)
                            <tr>
                                <td><strong>SKU:</strong></td>
                                <td>{{ $currentVariant->sku }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                @php
                    $hasVariants = ($product->has_variant || ($product->variants && $product->variants->count() > 0));
                @endphp
                @if($hasVariants && $product->variants && $product->variants->count() > 0)
                <div id="variants" class="tab-pane fade">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>STT</th>
                                    <th>Ảnh</th>
                                    <th>SKU</th>
                                    <th>Dung lượng</th>
                                    <th>Phiên bản</th>
                                    <th>Màu sắc</th>
                                    <th class="text-end">Giá niêm yết</th>
                                    <th class="text-end">Giá khuyến mãi</th>
                                    <th class="text-center">Tồn kho</th>
                                    <th class="text-center">Trạng thái</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($product->variants as $index => $variant)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($variant->image)
                                            <img src="{{ preg_match('/^https?:\\/\\//', $variant->image) ? $variant->image : asset('storage/' . $variant->image) }}" 
                                                 alt="{{ $variant->sku }}" 
                                                 style="width: 60px; height: 60px; object-fit: contain; border: 1px solid #E4E7ED; border-radius: 4px;">
                                        @else
                                            <img src="{{ $mainImage }}" 
                                                 alt="{{ $variant->sku }}" 
                                                 style="width: 60px; height: 60px; object-fit: contain; border: 1px solid #E4E7ED; border-radius: 4px;">
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $variant->sku }}</strong>
                                        @if($variant->barcode)
                                            <br><small class="text-muted">Barcode: {{ $variant->barcode }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($variant->storage)
                                            <span class="badge bg-secondary">{{ $variant->storage->storage }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($variant->version)
                                            <span class="badge bg-info">{{ $variant->version->name }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($variant->color)
                                            <span class="badge" style="background-color: {{ $variant->color->hex_code ?? '#6c757d' }}; color: white;">
                                                {{ $variant->color->name }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <strong>{{ number_format($variant->price, 0, ',', '.') }} ₫</strong>
                                    </td>
                                    <td class="text-end">
                                        @if($variant->price_sale)
                                            <strong class="text-danger">{{ number_format($variant->price_sale, 0, ',', '.') }} ₫</strong>
                                            @php
                                                $discount = round((($variant->price - $variant->price_sale) / $variant->price) * 100);
                                            @endphp
                                            <br><small class="text-success">-{{ $discount }}%</small>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($variant->stock > 0)
                                            <span class="badge bg-success">{{ $variant->stock }}</span>
                                        @else
                                            <span class="badge bg-danger">0</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($variant->status == 'available')
                                            <span class="badge bg-success">Còn hàng</span>
                                        @elseif($variant->status == 'out_of_stock')
                                            <span class="badge bg-warning">Hết hàng</span>
                                        @else
                                            <span class="badge bg-secondary">Ngừng bán</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-primary select-variant-btn" 
                                                data-variant-id="{{ $variant->id }}"
                                                data-price="{{ $variant->price }}"
                                                data-price-sale="{{ $variant->price_sale ?? '' }}"
                                                data-image="{{ $variant->image ? (preg_match('/^https?:\\/\\//', $variant->image) ? $variant->image : asset('storage/' . $variant->image)) : $mainImage }}"
                                                data-sku="{{ $variant->sku }}"
                                                data-stock="{{ $variant->stock }}">
                                            Chọn
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Product Image Album -->
        @php
            $albumImages = collect();
            
            // Thêm ảnh chính của sản phẩm
            if($product->image) {
                $mainImg = preg_match('/^https?:\\/\\//', $product->image) ? $product->image : asset('storage/' . $product->image);
                $albumImages->push($mainImg);
            }
            
            // Thêm ảnh từ product_images
            if($product->images && $product->images->count() > 0) {
                foreach($product->images as $img) {
                    $imgUrl = preg_match('/^https?:\\/\\//', $img->image_url) ? $img->image_url : asset('storage/' . $img->image_url);
                    if(!$albumImages->contains($imgUrl)) {
                        $albumImages->push($imgUrl);
                    }
                }
            }
            
            // Thêm ảnh từ variants
            if($product->variants && $product->variants->count() > 0) {
                foreach($product->variants as $variant) {
                    if($variant->image) {
                        $variantImg = preg_match('/^https?:\\/\\//', $variant->image) ? $variant->image : asset('storage/' . $variant->image);
                        if(!$albumImages->contains($variantImg)) {
                            $albumImages->push($variantImg);
                        }
                    }
                    
                    // Thêm ảnh từ variant_images
                    if($variant->images && $variant->images->count() > 0) {
                        foreach($variant->images as $vImg) {
                            $vImgUrl = preg_match('/^https?:\\/\\//', $vImg->image_url) ? $vImg->image_url : asset('storage/' . $vImg->image_url);
                            if(!$albumImages->contains($vImgUrl)) {
                                $albumImages->push($vImgUrl);
                            }
                        }
                    }
                }
            }
        @endphp
        
        @php
            $albumTotal = $albumImages->count();
        @endphp
        @if($albumTotal > 1)
        {{-- <div class="section" style="margin-top: 50px;">
            <div class="container">
                <div class="section-title">
                    <h3 class="title">Album ảnh sản phẩm</h3>
                </div>
                <div class="product-album">
                    <div class="row g-3">
                        @foreach($albumImages as $index => $albumImg)
                        <div class="col-md-3 col-sm-4 col-6">
                            <div class="album-item" data-bs-toggle="modal" data-bs-target="#imageModal" data-image-index="{{ $index }}">
                                <img src="{{ $albumImg }}" alt="{{ $product->name }} - Ảnh {{ $index + 1 }}" class="img-fluid">
                                <div class="album-overlay">
                                    <i class="fa fa-search-plus"></i>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div> --}}
        
        <!-- Image Modal -->
       
        @endif
        
        <!-- Related Products -->
        @if(isset($relatedProducts) && $relatedProducts->count() > 0)
        <div class="section" style="margin-top: 50px;">
            <div class="container">
                <div class="section-title">
                    <h3 class="title">Sản phẩm liên quan</h3>
                </div>
                <div class="row">
                    @foreach($relatedProducts as $related)
                    <div class="col-md-3 col-xs-6">
                        <div class="product">
                            <div class="product-img">
                                <img src="{{ $related->image ? (preg_match('/^https?:\\/\\//', $related->image) ? $related->image : asset('storage/' . $related->image)) : asset('electro/img/product01.png') }}" alt="{{ $related->name }}">
                            </div>
                            <div class="product-body">
                                <p class="product-category">{{ $related->category->name ?? 'N/A' }}</p>
                                <h3 class="product-name"><a href="{{ route('client.product.show', $related->slug) }}">{{ $related->name }}</a></h3>
                                <h4 class="product-price">{{ number_format($related->price, 0, ',', '.') }} ₫</h4>
                            </div>
                            <div class="add-to-cart">
                                <a href="{{ route('client.product.show', $related->slug) }}" class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i> Xem chi tiết</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Comments Section -->
<div class="section">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="section-title">
					<h3 class="title">Bình luận ({{ $product->comments->count() }})</h3>
				</div>

				<!-- Comment Form -->
				@auth
					<div class="comment-form-wrapper" style="margin-bottom: 40px;">
						<form id="commentForm" class="comment-form">
							@csrf
							<div class="form-group">
								<textarea 
									name="content" 
									id="commentContent" 
									class="form-control" 
									rows="4" 
									placeholder="Viết bình luận của bạn..." 
									required
									style="resize: vertical; min-height: 100px;"
								></textarea>
							</div>
							<button type="submit" class="btn btn-primary">
								<i class="fa fa-paper-plane"></i> Gửi bình luận
							</button>
						</form>
					</div>
				@else
					<div class="alert alert-info" style="margin-bottom: 40px;">
						<p>Vui lòng <a href="{{ route('client.login') }}" style="color: #D10024; font-weight: bold;">đăng nhập</a> để bình luận.</p>
					</div>
				@endauth

				<!-- Comments List -->
				<div id="commentsList" class="comments-list">
					@if($product->comments->count() > 0)
						@foreach($product->comments as $comment)
							@include('electro.partials.comment', ['comment' => $comment, 'level' => 0])
						@endforeach
					@else
						<p class="text-muted">Chưa có bình luận nào. Hãy là người đầu tiên bình luận!</p>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>

@push('styles')
<style>
	.comment-form-wrapper {
		background: #f8f9fa;
		padding: 20px;
		border-radius: 8px;
		margin-bottom: 30px;
	}
	
	.comment-item {
		background: #fff;
		border: 1px solid #e4e7ed;
		border-radius: 8px;
		padding: 15px;
		margin-bottom: 15px;
		transition: box-shadow 0.3s;
	}
	
	.comment-item:hover {
		box-shadow: 0 2px 8px rgba(0,0,0,0.1);
	}
	
	.comment-header {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 10px;
	}
	
	.comment-author {
		font-weight: 600;
		color: #2B2D42;
		font-size: 14px;
	}
	
	.comment-time {
		color: #8D99AE;
		font-size: 12px;
	}
	
	.comment-content {
		color: #434343;
		line-height: 1.6;
		margin-bottom: 10px;
		white-space: pre-wrap;
		word-wrap: break-word;
	}
	
	.comment-actions {
		display: flex;
		gap: 15px;
	}
	
	.reply-btn {
		background: none;
		border: none;
		color: #D10024;
		cursor: pointer;
		font-size: 13px;
		padding: 5px 0;
		transition: color 0.3s;
	}
	
	.reply-btn:hover {
		color: #B8001F;
		text-decoration: underline;
	}
	
	.reply-form {
		margin-top: 15px;
		padding-top: 15px;
		border-top: 1px solid #e4e7ed;
		display: none;
	}
	
	.reply-form.active {
		display: block;
	}
	
	.reply-form textarea {
		width: 100%;
		padding: 10px;
		border: 1px solid #e4e7ed;
		border-radius: 4px;
		resize: vertical;
		min-height: 80px;
		margin-bottom: 10px;
	}
	
	.reply-form .btn-group {
		display: flex;
		gap: 10px;
	}
	
	.comment-replies {
		margin-top: 15px;
		margin-left: 40px;
		padding-left: 20px;
		border-left: 2px solid #e4e7ed;
	}
	
	.comment-replies .comment-item {
		background: #f8f9fa;
	}
	
	.btn-primary {
		background-color: #D10024;
		border-color: #D10024;
		color: #fff;
		padding: 8px 20px;
		border-radius: 4px;
		transition: background-color 0.3s;
	}
	
	.btn-primary:hover {
		background-color: #B8001F;
		border-color: #B8001F;
	}
	
	.btn-secondary {
		background-color: #8D99AE;
		border-color: #8D99AE;
		color: #fff;
		padding: 8px 20px;
		border-radius: 4px;
	}
	
	.alert-info {
		background-color: #E3F2FD;
		border-color: #BBDEFB;
		color: #1976D2;
		padding: 15px;
		border-radius: 4px;
	}
</style>
@endpush

@push('scripts')
<script>
	document.addEventListener('DOMContentLoaded', function() {
		const commentForm = document.getElementById('commentForm');
		const commentsList = document.getElementById('commentsList');
		const productId = {{ $product->id }};
		const commentUrl = '{{ route("client.comments.store", $product->slug) }}';
		
		// Handle main comment form
		if (commentForm) {
			commentForm.addEventListener('submit', function(e) {
				e.preventDefault();
				submitComment(null);
			});
		}
		
		// Handle reply buttons
		document.querySelectorAll('.reply-btn').forEach(btn => {
			btn.addEventListener('click', function() {
				const parentId = this.dataset.parentId;
				const replyForm = document.getElementById('replyForm-' + parentId);
				if (replyForm) {
					replyForm.classList.toggle('active');
					if (replyForm.classList.contains('active')) {
						replyForm.querySelector('textarea').focus();
					}
				}
			});
		});
		
		// Handle reply form submissions
		document.querySelectorAll('.reply-form form').forEach(form => {
			form.addEventListener('submit', function(e) {
				e.preventDefault();
				const parentId = this.dataset.parentId;
				submitComment(parentId, this);
			});
		});
		
		function submitComment(parentId, formElement = null) {
			const content = formElement 
				? formElement.querySelector('textarea').value 
				: document.getElementById('commentContent').value;
			
			if (!content.trim()) {
				alert('Vui lòng nhập nội dung bình luận');
				return;
			}
			
			const formData = new FormData();
			formData.append('content', content);
			formData.append('_token', '{{ csrf_token() }}');
			if (parentId) {
				formData.append('parent_id', parentId);
			}
			
			fetch(commentUrl, {
				method: 'POST',
				body: formData,
				headers: {
					'X-Requested-With': 'XMLHttpRequest'
				}
			})
			.then(response => response.json())
			.then(data => {
				if (data.success) {
					// Reload page to show new comment
					location.reload();
				} else {
					if (data.message === 'Vui lòng đăng nhập để bình luận') {
						window.location.href = '{{ route("client.login") }}';
					} else {
						alert(data.message || 'Có lỗi xảy ra');
					}
				}
			})
			.catch(error => {
				console.error('Error:', error);
				alert('Có lỗi xảy ra khi gửi bình luận');
			});
		}
	});
</script>
@endpush
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const variantOptions = document.querySelectorAll('.variant-option');
    const mainImage = document.getElementById('main-product-image');
    const mainPrice = document.getElementById('main-product-price');
    const stockBadge = document.getElementById('stock-badge');
    const currentSku = document.getElementById('current-sku');
    const btnAddCart = document.getElementById('btn-add-cart');
    const btnBuyNow = document.getElementById('btn-buy-now');
    const quantityInput = document.getElementById('quantity-input');
    const quantityDecrease = document.getElementById('quantity-decrease');
    const quantityIncrease = document.getElementById('quantity-increase');
    let selectedVariantId = null;
    
    // Gallery thumbnails navigation
    const galleryThumbsContainer = document.getElementById('product-gallery-thumbs');
    const galleryPrevBtn = document.getElementById('gallery-prev');
    const galleryNextBtn = document.getElementById('gallery-next');
    
    function updateGalleryNavButtons() {
        if (!galleryThumbsContainer) return;
        
        const container = galleryThumbsContainer;
        const scrollLeft = container.scrollLeft;
        const scrollWidth = container.scrollWidth;
        const clientWidth = container.clientWidth;
        
        // Ensure container is visible
        const wrapper = container.closest('.product-gallery-thumbs-wrapper');
        if (wrapper && wrapper.style.display === 'none') {
            wrapper.style.display = 'block';
        }
        
        // Show/hide prev button
        if (galleryPrevBtn) {
            if (scrollLeft > 5) {
                galleryPrevBtn.style.display = 'flex';
                galleryPrevBtn.disabled = false;
            } else {
                galleryPrevBtn.style.display = 'none';
                galleryPrevBtn.disabled = true;
            }
        }
        
        // Show/hide next button
        if (galleryNextBtn) {
            const canScrollRight = scrollLeft < (scrollWidth - clientWidth - 10);
            if (canScrollRight) {
                galleryNextBtn.style.display = 'flex';
                galleryNextBtn.disabled = false;
            } else {
                galleryNextBtn.style.display = 'none';
                galleryNextBtn.disabled = true;
            }
        }
    }
    
    // Scroll gallery
    if (galleryPrevBtn) {
        galleryPrevBtn.addEventListener('click', function() {
            if (galleryThumbsContainer) {
                galleryThumbsContainer.scrollBy({
                    left: -120,
                    behavior: 'smooth'
                });
            }
        });
    }
    
    if (galleryNextBtn) {
        galleryNextBtn.addEventListener('click', function() {
            if (galleryThumbsContainer) {
                galleryThumbsContainer.scrollBy({
                    left: 120,
                    behavior: 'smooth'
                });
            }
        });
    }
    
    // Update nav buttons on scroll
    if (galleryThumbsContainer) {
        galleryThumbsContainer.addEventListener('scroll', updateGalleryNavButtons);
        // Check on load and resize
        window.addEventListener('resize', updateGalleryNavButtons);
        // Wait for images to load before checking scroll
        window.addEventListener('load', function() {
            setTimeout(updateGalleryNavButtons, 200);
        });
        // Also check immediately and after a delay
        setTimeout(updateGalleryNavButtons, 100);
        setTimeout(updateGalleryNavButtons, 500);
    }
    
    // Gallery thumbnails click handler
    const galleryThumbs = document.querySelectorAll('.product-gallery-thumb');
    galleryThumbs.forEach(function(thumb) {
        thumb.addEventListener('click', function() {
            // Skip feature thumb
            if (this.getAttribute('data-feature') === 'true') {
                return;
            }
            
            galleryThumbs.forEach(t => {
                if (t.getAttribute('data-feature') !== 'true') {
                    t.classList.remove('active');
                }
            });
            this.classList.add('active');
            const imageUrl = this.getAttribute('data-image');
            if (imageUrl && mainImage) {
                mainImage.style.opacity = '0.7';
                setTimeout(function() {
                    if (mainImage) {
                        mainImage.src = imageUrl;
                        mainImage.style.opacity = '1';
                    }
                }, 150);
            }
        });
    });
    
    // Storage + Version Combo Selection
    const storageVersionCombos = document.querySelectorAll('.storage-version-combo');
    storageVersionCombos.forEach(function(combo) {
        combo.addEventListener('click', function() {
            // Remove selected from all combos
            storageVersionCombos.forEach(function(c) {
                c.classList.remove('selected');
                c.style.borderColor = '#E4E7ED';
                c.style.background = '#fff';
                const checkIcon = c.querySelector('.fa-check');
                if (checkIcon) {
                    checkIcon.remove();
                }
            });
            
            // Add selected to clicked combo
            this.classList.add('selected');
            this.style.borderColor = '#D10024';
            this.style.background = '#FFF5F5';
            
            const checkIcon = document.createElement('i');
            checkIcon.className = 'fa fa-check';
            this.insertBefore(checkIcon, this.firstChild);
            
            // Get combo data
            const comboKey = this.getAttribute('data-combo-key');
            const storageId = this.getAttribute('data-storage-id');
            const versionId = this.getAttribute('data-version-id');
            
            // Update color options based on selected combo
            updateColorOptions(comboKey, storageId, versionId);
            
            // Note: variant_id will be updated when first color option is auto-selected in updateColorOptions
        });
    });
    
    // Color Selection (for existing color options)
    const colorOptions = document.querySelectorAll('.color-variant');
    colorOptions.forEach(function(option) {
        option.addEventListener('click', function() {
            const parentGroup = this.closest('.variant-selection');
            const allOptionsInGroup = parentGroup.querySelectorAll('.variant-option');
            allOptionsInGroup.forEach(function(opt) {
                opt.classList.remove('selected');
                opt.style.borderColor = '#E4E7ED';
                opt.style.background = '#fff';
                const checkIcon = opt.querySelector('.fa-check');
                if (checkIcon) {
                    checkIcon.remove();
                }
            });
            
            this.classList.add('selected');
            this.style.borderColor = '#D10024';
            this.style.background = '#FFF5F5';
            
            const checkIcon = document.createElement('i');
            checkIcon.className = 'fa fa-check';
            this.insertBefore(checkIcon, this.firstChild);
            
            // Update variant ID immediately when color is selected
            const variantId = this.getAttribute('data-variant-id');
            if (variantId) {
                selectedVariantId = variantId;
                if (btnAddCart) {
                    btnAddCart.setAttribute('data-variant-id', variantId);
                    console.log('Updated variant ID after color selection:', variantId);
                }
                if (btnBuyNow) {
                    btnBuyNow.setAttribute('data-variant-id', variantId);
                }
            }
            
            // Update product info based on selected color variant
            updateProductInfo(this);
        });
    });
    
    // Function to update color options based on selected storage+version combo
    function updateColorOptions(comboKey, storageId, versionId) {
        if (!window.storageVersionCombos || !window.storageVersionCombos[comboKey]) {
            return;
        }
        
        const combo = window.storageVersionCombos[comboKey];
        const colorOptionsContainer = document.getElementById('color-options');
        
        if (!colorOptionsContainer || !combo.variants || combo.variants.length === 0) {
            return;
        }
        
        // Clear existing color options
        colorOptionsContainer.innerHTML = '';
        
        // Add new color options
        combo.variants.forEach(function(colorVariant, index) {
            const isFirst = index === 0;
            const colorImage = colorVariant.image || (mainImage ? mainImage.src : '');
            const displayPrice = colorVariant.price_sale || colorVariant.price;
            const hasDiscount = colorVariant.price_sale && parseFloat(colorVariant.price_sale) < parseFloat(colorVariant.price);
            const isAvailable = colorVariant.is_available && parseInt(colorVariant.stock) > 0;
            const availabilityText = isAvailable ? 'Còn hàng' : 'Đã hết hàng';
            const availabilityClass = isAvailable ? 'text-success' : 'text-danger';
            
            const colorOption = document.createElement('div');
            colorOption.className = 'variant-option color-variant' + (isFirst ? ' selected' : '');
            colorOption.setAttribute('data-variant-id', colorVariant.id);
            colorOption.setAttribute('data-storage-id', storageId);
            colorOption.setAttribute('data-version-id', versionId);
            colorOption.setAttribute('data-color-id', colorVariant.color_id);
            colorOption.setAttribute('data-price', colorVariant.price);
            colorOption.setAttribute('data-price-sale', colorVariant.price_sale || '');
            colorOption.setAttribute('data-image', colorImage);
            colorOption.setAttribute('data-sku', colorVariant.sku);
            colorOption.setAttribute('data-stock', colorVariant.stock);
            colorOption.setAttribute('data-available', isAvailable ? '1' : '0');
            
            if (isFirst) {
                colorOption.style.borderColor = '#D10024';
                colorOption.style.background = '#FFF5F5';
            }
            
            let priceHtml = '';
            if (hasDiscount) {
                priceHtml = '<div style="font-size: 12px; color: #D10024; margin-top: 5px; font-weight: bold;">' +
                    parseInt(colorVariant.price_sale).toLocaleString('vi-VN') + ' ₫' +
                    '</div>' +
                    '<div style="font-size: 11px; color: #8D99AE; text-decoration: line-through;">' +
                    parseInt(colorVariant.price).toLocaleString('vi-VN') + ' ₫' +
                    '</div>';
            } else {
                priceHtml = '<div style="font-size: 12px; color: #8D99AE; margin-top: 5px;">' +
                    parseInt(displayPrice).toLocaleString('vi-VN') + ' ₫' +
                    '</div>';
            }
            
            colorOption.innerHTML = 
                (isFirst ? '<i class="fa fa-check"></i>' : '') +
                '<div style="text-align: center;">' +
                    '<img src="' + colorImage + '" alt="' + (colorVariant.color_name || 'Màu sắc') + '" style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px; margin-bottom: 8px; display: block; margin-left: auto; margin-right: auto;">' +
                    '<div style="font-weight: bold; margin-top: 8px; font-size: 13px;">' + (colorVariant.color_name || 'Màu sắc') + '</div>' +
                    priceHtml +
                    '<div style="margin-top: 4px; font-size: 12px;" class="' + availabilityClass + '">' + availabilityText + '</div>' +
                '</div>';
            
            // Add click event
            colorOption.addEventListener('click', function() {
                const allColorOptions = colorOptionsContainer.querySelectorAll('.color-variant');
                allColorOptions.forEach(function(opt) {
                    opt.classList.remove('selected');
                    opt.style.borderColor = '#E4E7ED';
                    opt.style.background = '#fff';
                    const checkIcon = opt.querySelector('.fa-check');
                    if (checkIcon) {
                        checkIcon.remove();
                    }
                });
                
                this.classList.add('selected');
                this.style.borderColor = '#D10024';
                this.style.background = '#FFF5F5';
                
                const checkIcon = document.createElement('i');
                checkIcon.className = 'fa fa-check';
                this.insertBefore(checkIcon, this.firstChild);
                
                updateProductInfo(this);
            });
            
            colorOptionsContainer.appendChild(colorOption);
        });
        
        // Auto-select first color and update product info
        const firstColorOption = colorOptionsContainer.querySelector('.color-variant.selected');
        if (firstColorOption) {
            // Update SKU immediately when combo changes
            const firstSku = firstColorOption.getAttribute('data-sku');
            if (firstSku && currentSku) {
                currentSku.textContent = firstSku;
            }
            // Update variant ID and button immediately
            const firstVariantId = firstColorOption.getAttribute('data-variant-id');
            if (firstVariantId && btnAddCart) {
                selectedVariantId = firstVariantId;
                btnAddCart.setAttribute('data-variant-id', firstVariantId);
                console.log('Updated variant ID after combo change:', firstVariantId);
            }
            if (firstVariantId && btnBuyNow) {
                btnBuyNow.setAttribute('data-variant-id', firstVariantId);
            }
            updateProductInfo(firstColorOption);
        }
    }
    
    // Function to update product info (image, price, SKU, stock) based on selected variant
    function updateProductInfo(variantElement) {
        if (!variantElement) return;
        
        // Get variant ID to find all images
        const variantId = variantElement.getAttribute('data-variant-id');
        let variantImages = [];
        
        // Try to get all images from variant data
        if (variantId && window.productVariantsData) {
            const variantData = window.productVariantsData.find(v => v.id == variantId);
            if (variantData && variantData.images && variantData.images.length > 0) {
                variantImages = variantData.images;
            } else {
                // Fallback to single image
                const newImage = variantElement.getAttribute('data-image');
                if (newImage) {
                    variantImages = [newImage];
                }
            }
        } else {
            // Fallback to single image
            const newImage = variantElement.getAttribute('data-image');
            if (newImage) {
                variantImages = [newImage];
            }
        }
        
        // Clear old variant-specific thumbnails before applying new ones
        clearVariantThumbnails();

        // Update main image with first image
        if (variantImages.length > 0 && mainImage) {
            const newImage = variantImages[0];
            mainImage.style.opacity = '0.7';
            setTimeout(function() {
                if (mainImage) {
                    mainImage.src = newImage;
                    mainImage.style.opacity = '1';
                }
            }, 150);
            
            // Update thumbnail active state
            updateThumbnailActive(newImage);
            
            // Add all variant images to gallery
            variantImages.forEach(function(imgUrl) {
                addImageToGallery(imgUrl, { isVariantImage: true });
            });
        }
        
        // Continue with price, stock, SKU updates
        updateProductInfoContinue(variantElement);
    }
    
    // Function to update thumbnail active state
    function updateThumbnailActive(imageUrl) {
        const galleryThumbs = document.querySelectorAll('.product-gallery-thumb');
        galleryThumbs.forEach(function(thumb) {
            // Skip feature thumb
            if (thumb.getAttribute('data-feature') === 'true') {
                return;
            }
            
            const thumbImage = thumb.getAttribute('data-image');
            if (thumbImage === imageUrl) {
                thumb.classList.add('active');
            } else {
                thumb.classList.remove('active');
            }
        });
    }
    
    // Function to remove variant-specific thumbnails
    function clearVariantThumbnails() {
        const galleryThumbs = document.getElementById('product-gallery-thumbs');
        if (!galleryThumbs) return;

        const variantThumbs = galleryThumbs.querySelectorAll('.product-gallery-thumb[data-variant-thumb="true"]');
        variantThumbs.forEach(function(thumb) {
            thumb.remove();
        });

        setTimeout(updateGalleryNavButtons, 100);
    }

    // Function to add image to gallery if not exists
    function addImageToGallery(imageUrl, options) {
        if (!imageUrl) return;
        
        const galleryThumbs = document.getElementById('product-gallery-thumbs');
        if (!galleryThumbs) return;

        options = options || {};
        const isVariantImage = options && options.isVariantImage;
        
        // Check if image already exists
        const existingThumb = galleryThumbs.querySelector('[data-image="' + imageUrl + '"]');
        if (existingThumb) {
            // Image exists, just make it active
            updateThumbnailActive(imageUrl);
            return;
        }
        
        // Add new thumbnail
        const newThumb = document.createElement('div');
        newThumb.className = 'product-gallery-thumb active';
        newThumb.setAttribute('data-image', imageUrl);
        if (isVariantImage) {
            newThumb.setAttribute('data-variant-thumb', 'true');
        }
        
        const thumbImg = document.createElement('img');
        thumbImg.src = imageUrl;
        thumbImg.alt = '{{ $product->name }}';
        
        newThumb.appendChild(thumbImg);
        
        // Remove active from all other thumbs (except feature thumb)
        const allThumbs = galleryThumbs.querySelectorAll('.product-gallery-thumb');
        allThumbs.forEach(function(thumb) {
            if (thumb.getAttribute('data-feature') !== 'true') {
                thumb.classList.remove('active');
            }
        });
        
        // Add click event
        newThumb.addEventListener('click', function() {
            const allThumbsNew = galleryThumbs.querySelectorAll('.product-gallery-thumb');
            allThumbsNew.forEach(function(t) {
                if (t.getAttribute('data-feature') !== 'true') {
                    t.classList.remove('active');
                }
            });
            this.classList.add('active');
            const imageUrl = this.getAttribute('data-image');
            if (mainImage) {
                mainImage.style.opacity = '0.7';
                setTimeout(function() {
                    if (mainImage) {
                        mainImage.src = imageUrl;
                        mainImage.style.opacity = '1';
                    }
                }, 150);
            }
        });
        
        galleryThumbs.appendChild(newThumb);
        
        // Show wrapper if hidden
        const wrapper = galleryThumbs.closest('.product-gallery-thumbs-wrapper');
        if (wrapper) {
            wrapper.style.display = 'block';
        }
        
        // Update navigation buttons after image loads
        if (thumbImg.complete) {
            setTimeout(updateGalleryNavButtons, 100);
        } else {
            thumbImg.addEventListener('load', function() {
                setTimeout(updateGalleryNavButtons, 100);
            });
        }
    }
    
    // Function to update product info (image, price, SKU, stock) based on selected variant - CONTINUED
    function updateProductInfoContinue(variantElement) {
        if (!variantElement) return;
        
        // Update price
        const priceSale = variantElement.getAttribute('data-price-sale');
        const price = variantElement.getAttribute('data-price');
        const displayPrice = priceSale && priceSale !== '' ? priceSale : price;
        const hasDiscount = priceSale && priceSale !== '' && parseFloat(priceSale) < parseFloat(price);
        
        if (displayPrice) {
            const priceSection = document.querySelector('.product-price-section');
            if (priceSection) {
                if (hasDiscount) {
                    const discountPercent = Math.round(((parseFloat(price) - parseFloat(priceSale)) / parseFloat(price)) * 100);
                    const savings = parseFloat(price) - parseFloat(priceSale);
                    
                    priceSection.innerHTML = 
                        '<div style="margin-bottom: 10px;">' +
                            '<span class="price-label">Giá khuyến mãi:</span>' +
                            '<span class="product-price-current" id="main-product-price">' +
                                parseInt(priceSale).toLocaleString('vi-VN') + ' ₫' +
                            '</span>' +
                        '</div>' +
                        '<div class="price-wrapper">' +
                            '<span class="product-price-old" id="old-price">' +
                                parseInt(price).toLocaleString('vi-VN') + ' ₫' +
                            '</span>' +
                            '<span class="product-discount" id="discount-badge">' +
                                '-' + discountPercent + '%' +
                            '</span>' +
                        '</div>' +
                        '<div style="margin-top: 8px; font-size: 13px; color: #2E7D32;">' +
                            '<i class="fa fa-tag"></i> Tiết kiệm: <strong>' + parseInt(savings).toLocaleString('vi-VN') + ' ₫</strong>' +
                        '</div>';
                } else {
                    priceSection.innerHTML = 
                        '<div>' +
                            '<span class="price-label">Giá bán:</span>' +
                            '<span class="product-price" id="main-product-price">' +
                                parseInt(displayPrice).toLocaleString('vi-VN') + ' ₫' +
                            '</span>' +
                        '</div>';
                }
                
                // Re-assign mainPrice reference after update
                mainPrice = document.getElementById('main-product-price');
            }
        }
        
        // Update stock
        const stock = parseInt(variantElement.getAttribute('data-stock'));
        if (stockBadge) {
            if (stock > 0) {
                stockBadge.className = 'stock-badge in-stock';
                stockBadge.innerHTML = '<i class="fa fa-check-circle"></i> Còn hàng (' + stock + ' sản phẩm)';
            } else {
                stockBadge.className = 'stock-badge out-of-stock';
                stockBadge.innerHTML = '<i class="fa fa-times-circle"></i> Đã hết hàng';
            }
        }
        
        // Update SKU
        const sku = variantElement.getAttribute('data-sku');
        if (sku && currentSku) {
            currentSku.textContent = sku;
            // Add animation effect
            currentSku.style.transition = 'all 0.3s';
            currentSku.style.color = '#D10024';
            setTimeout(function() {
                if (currentSku) {
                    currentSku.style.color = '';
                }
            }, 300);
        }
        
        // Update variant ID
        selectedVariantId = variantElement.getAttribute('data-variant-id');
        console.log('updateProductInfoContinue - Updating variant ID to:', selectedVariantId);
        const isAvailable = parseInt(variantElement.getAttribute('data-available')) === 1 && stock > 0;
        
        if (btnAddCart && selectedVariantId) {
            btnAddCart.setAttribute('data-variant-id', selectedVariantId);
            console.log('Button data-variant-id updated to:', selectedVariantId);
            // Enable/disable button based on availability
            if (isAvailable) {
                btnAddCart.disabled = false;
                btnAddCart.style.opacity = '1';
                btnAddCart.style.cursor = 'pointer';
            } else {
                btnAddCart.disabled = true;
                btnAddCart.style.opacity = '0.6';
                btnAddCart.style.cursor = 'not-allowed';
            }
        }
        if (btnBuyNow) {
            btnBuyNow.setAttribute('data-variant-id', selectedVariantId);
            // Enable/disable button based on availability
            if (isAvailable) {
                btnBuyNow.disabled = false;
                btnBuyNow.style.opacity = '1';
                btnBuyNow.style.cursor = 'pointer';
            } else {
                btnBuyNow.disabled = true;
                btnBuyNow.style.opacity = '0.6';
                btnBuyNow.style.cursor = 'not-allowed';
            }
        }
        
        // Update quantity max
        if (quantityInput) {
            quantityInput.setAttribute('max', stock);
            if (parseInt(quantityInput.value) > stock) {
                quantityInput.value = stock;
            }
            if (stock === 0) {
                quantityInput.disabled = true;
            } else {
                quantityInput.disabled = false;
            }
        }
    }
    
    // Quantity controls
    if (quantityDecrease) {
        quantityDecrease.addEventListener('click', function() {
            const current = parseInt(quantityInput.value);
            if (current > 1) {
                quantityInput.value = current - 1;
            }
        });
    }
    
    if (quantityIncrease) {
        quantityIncrease.addEventListener('click', function() {
            const current = parseInt(quantityInput.value);
            const max = parseInt(quantityInput.getAttribute('max')) || 100;
            if (current < max) {
                quantityInput.value = current + 1;
            }
        });
    }
    
    // Function to show toast notification
    function showToast(message, type = 'success') {
        // Remove existing toast if any
        const existingToast = document.querySelector('.toast-notification');
        if (existingToast) {
            existingToast.remove();
        }
        
        // Create toast element
        const toast = document.createElement('div');
        toast.className = 'toast-notification toast-' + type;
        toast.innerHTML = '<i class="fa fa-' + (type === 'success' ? 'check-circle' : 'exclamation-circle') + '"></i> ' + message;
        document.body.appendChild(toast);
        
        // Show toast
        setTimeout(() => {
            toast.classList.add('show');
        }, 10);
        
        // Hide and remove toast after 3 seconds
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 3000);
    }
    
    // Function to update cart count
    function updateCartCount(addedQuantity = 1) {
        const headerCartCount = document.querySelector('header .qty');
        if (headerCartCount) {
            const currentCount = parseInt(headerCartCount.textContent.trim()) || 0;
            const newCount = currentCount + addedQuantity;
            headerCartCount.textContent = newCount;
            
            // Add animation
            headerCartCount.style.transform = 'scale(1.3)';
            headerCartCount.style.transition = 'transform 0.3s';
            setTimeout(() => {
                headerCartCount.style.transform = 'scale(1)';
            }, 300);
        }
    }
    
    // Add to cart
    if (btnAddCart) {
        btnAddCart.addEventListener('click', function() {
            const variantId = this.getAttribute('data-variant-id');
            const quantity = parseInt(quantityInput.value) || 1;
            
            const hasVariants = {{ ($product->has_variant || ($product->variants && $product->variants->count() > 0)) ? 'true' : 'false' }};
            if (!variantId && hasVariants) {
                showToast('Vui lòng chọn biến thể sản phẩm (dung lượng, màu sắc, phiên bản)', 'error');
                return;
            }
            
            if (!variantId) {
                showToast('Không thể thêm sản phẩm này vào giỏ hàng. Vui lòng liên hệ với chúng tôi.', 'error');
                return;
            }
            
            // Check if disabled (out of stock)
            if (this.disabled) {
                showToast('Sản phẩm này đã hết hàng!', 'error');
                return;
            }
            
            // Disable button during request
            const originalText = btnAddCart.innerHTML;
            const originalDisabled = btnAddCart.disabled;
            btnAddCart.disabled = true;
            btnAddCart.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Đang thêm...';
            
            // Debug: Log variant ID để kiểm tra
            console.log('Adding to cart - Variant ID:', variantId, 'Quantity:', quantity);
            
            // Validate variant ID
            if (!variantId || variantId === 'null' || variantId === 'undefined') {
                showToast('Vui lòng chọn biến thể sản phẩm trước khi thêm vào giỏ hàng!', 'error');
                btnAddCart.disabled = originalDisabled;
                btnAddCart.innerHTML = originalText;
                return;
            }
            
            // Create form data
            const formData = new FormData();
            formData.append('product_variant_id', variantId);
            formData.append('quantity', quantity);
            formData.append('_token', '{{ csrf_token() }}');
            
            // Send AJAX request
            fetch('{{ route("cart.add") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Có lỗi xảy ra');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Show success message
                    showToast('Đã thêm sản phẩm vào giỏ hàng!', 'success');
                    
                    // Update cart count
                    updateCartCount(quantity);
                    
                    // Reset button
                    btnAddCart.disabled = originalDisabled;
                    btnAddCart.innerHTML = originalText;
                    
                    // Reset quantity to 1
                    if (quantityInput) {
                        quantityInput.value = 1;
                    }
                } else {
                    showToast(data.message || 'Có lỗi xảy ra khi thêm vào giỏ hàng!', 'error');
                    btnAddCart.disabled = originalDisabled;
                    btnAddCart.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast(error.message || 'Có lỗi xảy ra khi thêm vào giỏ hàng!', 'error');
                btnAddCart.disabled = originalDisabled;
                btnAddCart.innerHTML = originalText;
            });
        });
    }
    
    // Buy now
    if (btnBuyNow) {
        btnBuyNow.addEventListener('click', function() {
            const variantId = this.getAttribute('data-variant-id');
            const quantity = parseInt(quantityInput.value);
            
            const hasVariants = {{ ($product->has_variant || ($product->variants && $product->variants->count() > 0)) ? 'true' : 'false' }};
            if (!variantId && hasVariants) {
                alert('Vui lòng chọn biến thể sản phẩm');
                return;
            }
            
            // TODO: Implement buy now functionality
            alert('Tính năng đang phát triển');
        });
    }
    
    // Initialize selected variant and load all images
    const firstColorVariant = document.querySelector('.color-variant.selected');
    if (firstColorVariant) {
        selectedVariantId = firstColorVariant.getAttribute('data-variant-id');
        console.log('Initial variant from color-variant.selected:', selectedVariantId);
        if (selectedVariantId && btnAddCart) {
            btnAddCart.setAttribute('data-variant-id', selectedVariantId);
        }
        if (selectedVariantId && btnBuyNow) {
            btnBuyNow.setAttribute('data-variant-id', selectedVariantId);
        }
        updateProductInfo(firstColorVariant);
    } else if (variantOptions.length > 0) {
        const firstSelected = document.querySelector('.variant-option.selected');
        if (firstSelected) {
            selectedVariantId = firstSelected.getAttribute('data-variant-id');
            console.log('Initial variant from variant-option.selected:', selectedVariantId);
            if (selectedVariantId && btnAddCart) {
                btnAddCart.setAttribute('data-variant-id', selectedVariantId);
            }
            if (selectedVariantId && btnBuyNow) {
                btnBuyNow.setAttribute('data-variant-id', selectedVariantId);
            }
            // Check if it's a color variant with availability info
            const isAvailable = parseInt(firstSelected.getAttribute('data-available')) === 1;
            const stock = parseInt(firstSelected.getAttribute('data-stock')) || 0;
            if (btnAddCart && (!isAvailable || stock === 0)) {
                btnAddCart.disabled = true;
                btnAddCart.style.opacity = '0.6';
                btnAddCart.style.cursor = 'not-allowed';
            }
            if (btnBuyNow && (!isAvailable || stock === 0)) {
                btnBuyNow.disabled = true;
                btnBuyNow.style.opacity = '0.6';
                btnBuyNow.style.cursor = 'not-allowed';
            }
        }
    } else {
        // No variants - check if button has initial variant ID from server
        const initialVariantId = btnAddCart ? btnAddCart.getAttribute('data-variant-id') : null;
        if (initialVariantId && initialVariantId !== '') {
            selectedVariantId = initialVariantId;
            console.log('Initial variant from button data-variant-id:', selectedVariantId);
        } else {
            selectedVariantId = null;
            console.log('No variant found - product may not have variants');
        }
    }
    
    // Final check: Log current state
    console.log('Final selectedVariantId:', selectedVariantId);
    console.log('Button data-variant-id:', btnAddCart ? btnAddCart.getAttribute('data-variant-id') : 'N/A');
    
    // Ensure gallery is visible on load
    const galleryWrapper = document.getElementById('gallery-thumbs-wrapper');
    if (galleryWrapper) {
        galleryWrapper.style.display = 'block';
        // Wait for all images to load, then update nav buttons
        const galleryImages = galleryWrapper.querySelectorAll('img');
        let loadedCount = 0;
        const totalImages = galleryImages.length;
        
        if (totalImages > 0) {
            galleryImages.forEach(function(img) {
                if (img.complete) {
                    loadedCount++;
                } else {
                    img.addEventListener('load', function() {
                        loadedCount++;
                        if (loadedCount === totalImages) {
                            setTimeout(updateGalleryNavButtons, 200);
                        }
                    });
                    img.addEventListener('error', function() {
                        loadedCount++;
                        if (loadedCount === totalImages) {
                            setTimeout(updateGalleryNavButtons, 200);
                        }
                    });
                }
            });
            
            if (loadedCount === totalImages) {
                setTimeout(updateGalleryNavButtons, 200);
            }
        } else {
            setTimeout(updateGalleryNavButtons, 200);
        }
    }
    
    // Select variant from table
    const selectVariantBtns = document.querySelectorAll('.select-variant-btn');
    selectVariantBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            const variantId = this.getAttribute('data-variant-id');
            const price = this.getAttribute('data-price');
            const priceSale = this.getAttribute('data-price-sale');
            const image = this.getAttribute('data-image');
            const sku = this.getAttribute('data-sku');
            const stock = parseInt(this.getAttribute('data-stock'));
            
            // Update main image
            if (mainImage) {
                mainImage.src = image;
            }
            
            // Update price
            const displayPrice = priceSale && priceSale !== '' ? priceSale : price;
            const hasDiscount = priceSale && priceSale !== '' && parseFloat(priceSale) < parseFloat(price);
            
            if (displayPrice) {
                const priceSection = document.querySelector('.product-price-section');
                if (priceSection) {
                    if (hasDiscount) {
                        const discountPercent = Math.round(((parseFloat(price) - parseFloat(priceSale)) / parseFloat(price)) * 100);
                        const savings = parseFloat(price) - parseFloat(priceSale);
                        
                        priceSection.innerHTML = `
                            <div style="margin-bottom: 10px;">
                                <span class="price-label">Giá khuyến mãi:</span>
                                <span class="product-price-current" id="main-product-price">
                                    ${parseInt(priceSale).toLocaleString('vi-VN')} ₫
                                </span>
                            </div>
                            <div class="price-wrapper">
                                <span class="product-price-old" id="old-price">
                                    ${parseInt(price).toLocaleString('vi-VN')} ₫
                                </span>
                                <span class="product-discount" id="discount-badge">
                                    -${discountPercent}%
                                </span>
                            </div>
                            <div style="margin-top: 8px; font-size: 13px; color: #2E7D32;">
                                <i class="fa fa-tag"></i> Tiết kiệm: <strong>${parseInt(savings).toLocaleString('vi-VN')} ₫</strong>
                            </div>
                        `;
                    } else {
                        priceSection.innerHTML = `
                            <div>
                                <span class="price-label">Giá bán:</span>
                                <span class="product-price" id="main-product-price">
                                    ${parseInt(displayPrice).toLocaleString('vi-VN')} ₫
                                </span>
                            </div>
                        `;
                    }
                    
                    // Re-assign mainPrice reference after update
                    mainPrice = document.getElementById('main-product-price');
                }
            }
            
            // Update stock
            if (stockBadge) {
                if (stock > 0) {
                    stockBadge.className = 'stock-badge in-stock';
                    stockBadge.innerHTML = '<i class="fa fa-check-circle"></i> Còn hàng (' + stock + ' sản phẩm)';
                } else {
                    stockBadge.className = 'stock-badge out-of-stock';
                    stockBadge.innerHTML = '<i class="fa fa-times-circle"></i> Đã hết hàng';
                }
            }
            
            // Update SKU
            if (sku && currentSku) {
                currentSku.textContent = sku;
            }
            
            // Update variant ID
            selectedVariantId = variantId;
            const isAvailable = stock > 0;
            
            if (btnAddCart) {
                btnAddCart.setAttribute('data-variant-id', selectedVariantId);
                // Enable/disable button based on availability
                if (isAvailable) {
                    btnAddCart.disabled = false;
                    btnAddCart.style.opacity = '1';
                    btnAddCart.style.cursor = 'pointer';
                } else {
                    btnAddCart.disabled = true;
                    btnAddCart.style.opacity = '0.6';
                    btnAddCart.style.cursor = 'not-allowed';
                }
            }
            if (btnBuyNow) {
                btnBuyNow.setAttribute('data-variant-id', selectedVariantId);
                // Enable/disable button based on availability
                if (isAvailable) {
                    btnBuyNow.disabled = false;
                    btnBuyNow.style.opacity = '1';
                    btnBuyNow.style.cursor = 'pointer';
                } else {
                    btnBuyNow.disabled = true;
                    btnBuyNow.style.opacity = '0.6';
                    btnBuyNow.style.cursor = 'not-allowed';
                }
            }
            
            // Update quantity max
            if (quantityInput) {
                quantityInput.setAttribute('max', stock);
                if (parseInt(quantityInput.value) > stock) {
                    quantityInput.value = stock;
                }
                if (stock === 0) {
                    quantityInput.disabled = true;
                } else {
                    quantityInput.disabled = false;
                }
            }
            
            // Scroll to top of product info
            document.querySelector('.product-info').scrollIntoView({ behavior: 'smooth', block: 'start' });
            
            // Show success message
            alert('Đã chọn biến thể: ' + sku);
        });
    });
    
    // Image Modal Carousel
    const imageModal = document.getElementById('imageModal');
    if (imageModal) {
        imageModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const imageIndex = button ? parseInt(button.getAttribute('data-image-index')) : 0;
            const carousel = imageModal.querySelector('#imageCarousel');
            if (carousel) {
                const carouselInstance = bootstrap.Carousel.getInstance(carousel) || new bootstrap.Carousel(carousel);
                carouselInstance.to(imageIndex);
                updateImageCounter(imageIndex);
            }
        });
        
        const carousel = imageModal.querySelector('#imageCarousel');
        if (carousel) {
            carousel.addEventListener('slid.bs.carousel', function(event) {
                updateImageCounter(event.to);
            });
        }
    }
    
    function updateImageCounter(index) {
        const counter = document.getElementById('imageCounter');
        const total = {{ $albumTotal ?? 1 }};
        if (counter) {
            counter.textContent = (index + 1) + ' / ' + total;
        }
    }
});
</script>
@endpush
