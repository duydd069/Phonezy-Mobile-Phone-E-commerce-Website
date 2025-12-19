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
.btn-wishlist {
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
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}
.btn-wishlist:disabled {
    background: #8D99AE;
    cursor: not-allowed;
    opacity: 0.6;
}
.btn-wishlist:hover {
    background: #1A1C2E;
}
.btn-wishlist.in-wishlist {
    background: #D10024;
}
.btn-wishlist.in-wishlist:hover {
    background: #B8001F;
}
.btn-wishlist.in-wishlist .fa-heart {
    color: #fff;
}
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
                            // Luôn lấy giá từ biến thể (product gốc không chứa giá)
                            $currentVariant = $product->variants->first();
                            $currentPrice = $currentVariant->price ?? 0;
                            $currentPriceSale = $currentVariant->price_sale ?? null;
                            $hasVariants = ($product->has_variant || ($product->variants && $product->variants->count() > 0));
                            
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
                            // Đảm bảo lấy đúng số lượng từ biến thể đầu tiên
                            if ($hasVariants && $product->variants && $product->variants->count() > 0) {
                                // Lấy biến thể đầu tiên (đã được định nghĩa ở trên)
                                $currentStock = $currentVariant ? ($currentVariant->stock ?? 0) : 0;
                            } else {
                                $currentStock = 0;
                            }
                        @endphp
                        <span class="stock-badge {{ $currentStock > 0 ? 'in-stock' : 'out-of-stock' }}" id="stock-badge">
                            <i class="fa fa-{{ $currentStock > 0 ? 'check-circle' : 'times-circle' }}"></i>
                            @if($hasVariants && $currentVariant)
                                {{ $currentStock > 0 ? 'Còn hàng (' . $currentStock . ' sản phẩm - số lượng riêng của biến thể này)' : 'Đã hết hàng' }}
                            @else
                                {{ $currentStock > 0 ? 'Còn hàng (' . $currentStock . ' sản phẩm)' : 'Đã hết hàng' }}
                            @endif
                        </span>
                        @if($hasVariants && $currentVariant)
                            <small class="text-muted" style="display: block; margin-top: 5px; font-size: 12px;">
                                <i class="fa fa-info-circle"></i> Mỗi biến thể có số lượng tồn kho riêng, không tính chung với các biến thể khác
                            </small>
                        @endif
                    </div>
                    
                    @php
                        $hasVariants = ($product->has_variant || ($product->variants && $product->variants->count() > 0));
                    @endphp
                    @if($hasVariants && $product->variants && $product->variants->count() > 0)
                        @php
                            // Tách riêng versions, storages, và colors
                            $uniqueVersions = [];
                            $uniqueStorages = [];
                            $uniqueColors = [];
                            $allVariantsData = [];
                            
                            // Lấy variant đầu tiên available làm mặc định
                            $firstAvailableVariant = null;
                            
                            foreach($product->variants as $variant) {
                                $isAvailable = ($variant->stock ?? 0) > 0 && $variant->status === 'available';
                                $storageId = $variant->storage_id ?? 'none';
                                $versionId = $variant->version_id ?? 'none';
                                $colorId = $variant->color_id ?? 'none';
                                
                                $variantImage = $variant->image ? (preg_match('/^https?:\\/\\//', $variant->image) ? $variant->image : asset('storage/' . $variant->image)) : $mainImage;
                                
                                // Lưu tất cả variants data
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
                                    'storage_name' => $variant->storage ? $variant->storage->storage : '',
                                    'version_name' => $variant->version ? $variant->version->name : '',
                                    'color_name' => $variant->color ? $variant->color->name : '',
                                    'color_hex' => $variant->color ? $variant->color->hex_code : null,
                                ];
                                
                                // Thu thập unique versions
                                if($versionId !== 'none' && $variant->version && !isset($uniqueVersions[$versionId])) {
                                    $uniqueVersions[$versionId] = [
                                        'id' => $versionId,
                                        'name' => $variant->version->name,
                                    ];
                                }
                                
                                // Thu thập unique storages
                                if($storageId !== 'none' && $variant->storage && !isset($uniqueStorages[$storageId])) {
                                    $uniqueStorages[$storageId] = [
                                        'id' => $storageId,
                                        'name' => $variant->storage->storage,
                                    ];
                                }
                                
                                // Thu thập unique colors
                                if($colorId !== 'none' && $variant->color && !isset($uniqueColors[$colorId])) {
                                    $uniqueColors[$colorId] = [
                                        'id' => $colorId,
                                        'name' => $variant->color->name,
                                        'hex_code' => $variant->color->hex_code,
                                    ];
                                }
                                
                                // Lưu variant đầu tiên available
                                if(!$firstAvailableVariant && $isAvailable) {
                                    $firstAvailableVariant = $variant;
                                }
                            }
                            
                            // Nếu không có variant available, lấy variant đầu tiên
                            if(!$firstAvailableVariant && count($allVariantsData) > 0) {
                                $firstAvailableVariant = $product->variants->first();
                            }
                            
                            // Lấy giá trị mặc định từ variant đầu tiên
                            $defaultVersionId = $firstAvailableVariant ? ($firstAvailableVariant->version_id ?? 'none') : (count($uniqueVersions) > 0 ? array_key_first($uniqueVersions) : 'none');
                            $defaultStorageId = $firstAvailableVariant ? ($firstAvailableVariant->storage_id ?? 'none') : (count($uniqueStorages) > 0 ? array_key_first($uniqueStorages) : 'none');
                            $defaultColorId = $firstAvailableVariant ? ($firstAvailableVariant->color_id ?? 'none') : (count($uniqueColors) > 0 ? array_key_first($uniqueColors) : 'none');
                        @endphp
                        
                        {{-- Chọn Phiên bản --}}
                        @if(count($uniqueVersions) > 0)
                        <div class="variant-selection">
                            <label class="variant-label">Chọn phiên bản:</label>
                            <div class="variant-options" id="version-options">
                                @foreach($uniqueVersions as $versionId => $version)
                                    @php 
                                        $isSelected = $versionId == $defaultVersionId;
                                    @endphp
                                    <div class="variant-option version-option {{ $isSelected ? 'selected' : '' }}" 
                                         data-version-id="{{ $versionId }}">
                                        @if($isSelected)
                                            <i class="fa fa-check"></i>
                                        @endif
                                        <div style="font-weight: bold;">{{ $version['name'] }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        {{-- Chọn Dung lượng --}}
                        @if(count($uniqueStorages) > 0)
                        <div class="variant-selection">
                            <label class="variant-label">Chọn dung lượng:</label>
                            <div class="variant-options" id="storage-options">
                                @foreach($uniqueStorages as $storageId => $storage)
                                    @php 
                                        $isSelected = $storageId == $defaultStorageId;
                                    @endphp
                                    <div class="variant-option storage-option {{ $isSelected ? 'selected' : '' }}" 
                                         data-storage-id="{{ $storageId }}">
                                        @if($isSelected)
                                            <i class="fa fa-check"></i>
                                        @endif
                                        <div style="font-weight: bold;">{{ $storage['name'] }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        {{-- Chọn Màu sắc --}}
                        @if(count($uniqueColors) > 0)
                        <div class="variant-selection" id="color-selection-container">
                            <label class="variant-label">Chọn màu sắc:</label>
                            <div class="variant-options" id="color-options">
                                @foreach($uniqueColors as $colorId => $color)
                                    @php 
                                        $isSelected = $colorId == $defaultColorId;
                                        // Tìm variant image cho màu này với version và storage mặc định
                                        $colorVariant = null;
                                        foreach($allVariantsData as $v) {
                                            if($v['color_id'] == $colorId && 
                                               ($v['version_id'] == $defaultVersionId || $defaultVersionId == 'none') &&
                                               ($v['storage_id'] == $defaultStorageId || $defaultStorageId == 'none')) {
                                                $colorVariant = $v;
                                                break;
                                            }
                                        }
                                        // Nếu không tìm thấy với combo mặc định, lấy variant đầu tiên có màu này
                                        if(!$colorVariant) {
                                            foreach($allVariantsData as $v) {
                                                if($v['color_id'] == $colorId) {
                                                    $colorVariant = $v;
                                                    break;
                                                }
                                            }
                                        }
                                        $colorImage = $colorVariant ? $colorVariant['image'] : $mainImage;
                                    @endphp
                                    <div class="variant-option color-option {{ $isSelected ? 'selected' : '' }}" 
                                         data-color-id="{{ $colorId }}">
                                        @if($isSelected)
                                            <i class="fa fa-check"></i>
                                        @endif
                                        <div style="text-align: center;">
                                            <img src="{{ $colorImage }}" alt="{{ $color['name'] }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px; margin-bottom: 8px; display: block; margin-left: auto; margin-right: auto;">
                                            <div style="font-weight: bold; margin-top: 8px; font-size: 13px;">{{ $color['name'] ?: 'Màu sắc' }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        {{-- Lưu tất cả variants data vào JavaScript --}}
                        <script>
                            window.productVariantsData = @json($allVariantsData);
                            window.defaultVersionId = @json($defaultVersionId);
                            window.defaultStorageId = @json($defaultStorageId);
                            window.defaultColorId = @json($defaultColorId);
                        </script>
                    @endif
                    
                    <div class="quantity-selector">
                        <label class="variant-label" style="margin: 0;">Số lượng:</label>
                        <div class="quantity-input-group">
                            @php
                                $initialStock = $currentVariant->stock ?? 0;
                                $initialMax = max(1, $initialStock);
                                $quantityDisabled = $initialStock <= 0 ? 'disabled' : '';
                            @endphp
                            <button type="button" class="quantity-btn" id="quantity-decrease">-</button>
                            <input type="number"
                                   class="quantity-input"
                                   id="quantity-input"
                                   value="1"
                                   min="1"
                                   max="{{ $initialMax }}"
                                   {{ $quantityDisabled }}>
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
                        <button class="btn-wishlist {{ $inWishlist ?? false ? 'in-wishlist' : '' }}" id="btn-wishlist" data-product-id="{{ $product->id }}">
                            <i class="fa fa-heart"></i>
                            {{ $inWishlist ?? false ? 'Đã thêm vào yêu thích' : 'Thêm vào yêu thích' }}
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
                    <div class="alert alert-info" style="margin-bottom: 15px; padding: 10px 15px; font-size: 13px;">
                        <i class="fa fa-info-circle"></i> <strong>Lưu ý:</strong> Mỗi biến thể có số lượng tồn kho riêng, không tính chung với sản phẩm gốc hay các biến thể khác.
                    </div>
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
                                    <th class="text-center">Tồn kho<br><small class="text-muted">(riêng biến thể)</small></th>
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
	
	/* Prevent further nesting - all child replies at same level */
	.comment-replies .comment-replies {
		margin-left: 0 !important;
		border-left: none;
	}
	
	/* Admin badge */
	.admin-badge {
		background: #D10024;
		color: white;
		padding: 2px 8px;
		border-radius: 3px;
		font-size: 10px;
		font-weight: bold;
		margin-left: 8px;
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}
	
	/* Reply mention @username */
	.reply-to {
		color: #2196F3;
		font-weight: 600;
		margin-right: 5px;
		background: #E3F2FD;
		padding: 2px 6px;
		border-radius: 3px;
		font-size: 13px;
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
				const repliedToUserId = this.dataset.repliedToUserId;
				const repliedToName = this.dataset.repliedToName;
				const replyForm = document.getElementById('replyForm-' + parentId);
				
				if (replyForm) {
					replyForm.classList.toggle('active');
					if (replyForm.classList.contains('active')) {
						const textarea = replyForm.querySelector('textarea');
						// Pre-fill with @mention
						textarea.value = '@' + repliedToName + ' ';
						textarea.focus();
						// Move cursor to end
						textarea.setSelectionRange(textarea.value.length, textarea.value.length);
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
				
				// Get replied_to_user_id from form element
				if (formElement) {
					const repliedToUserId = formElement.getAttribute('data-replied-to-user-id');
					if (repliedToUserId) {
						formData.append('replied_to_user_id', repliedToUserId);
					}
				}
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
    const btnWishlist = document.getElementById('btn-wishlist');
    const quantityInput = document.getElementById('quantity-input');
    const quantityDecrease = document.getElementById('quantity-decrease');
    const quantityIncrease = document.getElementById('quantity-increase');
    let selectedVariantId = null;

    function updateStockUI(stock) {
        // Đảm bảo stock là số hợp lệ
        stock = parseInt(stock) || 0;
        if (isNaN(stock)) stock = 0;
        
        if (stockBadge) {
            if (stock > 0) {
                stockBadge.className = 'stock-badge in-stock';
                stockBadge.innerHTML = '<i class="fa fa-check-circle"></i> Còn hàng (' + stock + ' sản phẩm - số lượng riêng của biến thể này)';
            } else {
                stockBadge.className = 'stock-badge out-of-stock';
                stockBadge.innerHTML = '<i class="fa fa-times-circle"></i> Đã hết hàng';
            }
        }
        if (quantityInput) {
            const max = Math.max(1, stock);
            quantityInput.setAttribute('max', max);
            if (parseInt(quantityInput.value) > stock) {
                quantityInput.value = stock > 0 ? stock : 1;
            }
            quantityInput.disabled = stock === 0;
        }
    }
    
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
    
    // Helper function to select an option visually
    function selectOption(optionElement, optionGroup) {
        const parentGroup = optionElement.closest('.variant-selection');
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
        
        optionElement.classList.add('selected');
        optionElement.style.borderColor = '#D10024';
        optionElement.style.background = '#FFF5F5';
        
        const checkIcon = document.createElement('i');
        checkIcon.className = 'fa fa-check';
        optionElement.insertBefore(checkIcon, optionElement.firstChild);
    }
    
    // Track if user has interacted with variant selection (clicked any option)
    let userHasInteracted = false;
    
    // Function to find matching variant based on selected version, storage, and color
    function findMatchingVariant(versionId, storageId, colorId, allowBestMatch = false) {
        if (!window.productVariantsData) return null;
        
        // Convert 'none' to null for easier handling
        const vId = (versionId === 'none' || versionId === null || versionId === undefined) ? null : String(versionId);
        const sId = (storageId === 'none' || storageId === null || storageId === undefined) ? null : String(storageId);
        const cId = (colorId === 'none' || colorId === null || colorId === undefined) ? null : String(colorId);
        
        // Find exact match - stricter matching: NULL only matches NULL, not any value
        let variant = window.productVariantsData.find(function(v) {
            const vVersionId = (v.version_id === 'none' || v.version_id === null || v.version_id === undefined) ? null : String(v.version_id);
            const vStorageId = (v.storage_id === 'none' || v.storage_id === null || v.storage_id === undefined) ? null : String(v.storage_id);
            const vColorId = (v.color_id === 'none' || v.color_id === null || v.color_id === undefined) ? null : String(v.color_id);
            
            // Strict matching: NULL only matches NULL, not any value
            // Both must be NULL OR both must be non-NULL and equal
            const versionMatch = (vVersionId === null && vId === null) || 
                                (vVersionId !== null && vId !== null && vVersionId == vId);
            
            const storageMatch = (vStorageId === null && sId === null) || 
                                (vStorageId !== null && sId !== null && vStorageId == sId);
            
            const colorMatch = (vColorId === null && cId === null) || 
                              (vColorId !== null && cId !== null && vColorId == cId);
            
            // Also check availability and stock
            return versionMatch && storageMatch && colorMatch && v.is_available && v.stock > 0;
        });
        
        // Only use best match when initializing page, NOT when user has interacted
        if (!variant && allowBestMatch && !userHasInteracted) {
            let bestMatch = null;
            let bestScore = -1;
            
            window.productVariantsData.forEach(function(v) {
                const vVersionId = (v.version_id === 'none' || v.version_id === null || v.version_id === undefined) ? null : String(v.version_id);
                const vStorageId = (v.storage_id === 'none' || v.storage_id === null || v.storage_id === undefined) ? null : String(v.storage_id);
                const vColorId = (v.color_id === 'none' || v.color_id === null || v.color_id === undefined) ? null : String(v.color_id);
                
                let score = 0;
                // Give higher weight to storage match (most important for price)
                if (sId !== null && vStorageId !== null && vStorageId === sId) score += 3;
                if (vId !== null && vVersionId !== null && vVersionId === vId) score += 2;
                if (cId !== null && vColorId !== null && vColorId === cId) score += 1;
                
                // Only consider available variants with stock
                if (score > bestScore && v.is_available && v.stock > 0) {
                    bestScore = score;
                    bestMatch = v;
                }
            });
            
            variant = bestMatch;
        }
        
        return variant;
    }
    
    // Function to update product info based on selected variant
    function updateSelectedVariant() {
        // Get selected values
        const selectedVersion = document.querySelector('.version-option.selected');
        const selectedStorage = document.querySelector('.storage-option.selected');
        const selectedColor = document.querySelector('.color-option.selected');
        
        const versionId = selectedVersion ? selectedVersion.getAttribute('data-version-id') : (window.defaultVersionId || 'none');
        const storageId = selectedStorage ? selectedStorage.getAttribute('data-storage-id') : (window.defaultStorageId || 'none');
        const colorId = selectedColor ? selectedColor.getAttribute('data-color-id') : (window.defaultColorId || 'none');
        
        // Find matching variant - allow best match only if user hasn't interacted yet
        const variant = findMatchingVariant(versionId, storageId, colorId, !userHasInteracted);
        
        if (variant) {
            // Update variant ID
            selectedVariantId = variant.id;
            if (btnAddCart) {
                btnAddCart.setAttribute('data-variant-id', variant.id);
                // Enable/disable button based on stock
                if (variant.stock > 0 && variant.is_available) {
                    btnAddCart.disabled = false;
                    btnAddCart.style.opacity = '1';
                    btnAddCart.style.cursor = 'pointer';
                } else {
                    btnAddCart.disabled = true;
                    btnAddCart.style.opacity = '0.6';
                    btnAddCart.style.cursor = 'not-allowed';
                }
            }
            
            // Update SKU
            if (currentSku) {
                currentSku.textContent = variant.sku || '';
            }
            
            // Update price
            if (mainPrice) {
                const displayPrice = variant.price_sale || variant.price;
                const priceText = parseInt(displayPrice).toLocaleString('vi-VN') + ' ₫';
                if (variant.price_sale && parseFloat(variant.price_sale) < parseFloat(variant.price)) {
                    const originalPrice = parseInt(variant.price).toLocaleString('vi-VN') + ' ₫';
                    mainPrice.innerHTML = '<span style="color: #D10024; font-weight: bold; font-size: 28px;">' + priceText + '</span>' +
                                         '<span style="color: #8D99AE; text-decoration: line-through; font-size: 18px; margin-left: 10px;">' + originalPrice + '</span>';
                } else {
                    mainPrice.innerHTML = '<span style="color: #2B2D42; font-weight: bold; font-size: 28px;">' + priceText + '</span>';
                }
            }
            
            // Update stock
            updateStockUI(variant.stock || 0);
            
            // Update image if variant has image
            if (variant.image && mainImage) {
                mainImage.style.opacity = '0.7';
                setTimeout(function() {
                    if (mainImage) {
                        mainImage.src = variant.image;
                        mainImage.style.opacity = '1';
                    }
                    // Update thumbnail active state
                    updateThumbnailActive(variant.image);
                }, 150);
            }
        } else if (userHasInteracted) {
            // If user has interacted but no exact match found, show warning
            if (btnAddCart) {
                btnAddCart.disabled = true;
                btnAddCart.style.opacity = '0.6';
                btnAddCart.style.cursor = 'not-allowed';
            }
            // Optionally show a message to user
            // console.log('Tùy chọn này không khả dụng');
        }
    }
    
    // Function to filter and update available colors based on selected storage and version
    function updateAvailableColors() {
        const selectedVersion = document.querySelector('.version-option.selected');
        const selectedStorage = document.querySelector('.storage-option.selected');
        const colorOptionsContainer = document.getElementById('color-options');
        
        if (!colorOptionsContainer || !window.productVariantsData) return;
        
        const versionId = selectedVersion ? selectedVersion.getAttribute('data-version-id') : (window.defaultVersionId || 'none');
        const storageId = selectedStorage ? selectedStorage.getAttribute('data-storage-id') : (window.defaultStorageId || 'none');
        
        const vId = (versionId === 'none' || versionId === null || versionId === undefined) ? null : String(versionId);
        const sId = (storageId === 'none' || storageId === null || storageId === undefined) ? null : String(storageId);
        
        // Get all available color IDs for the selected storage and version
        // Strict matching: NULL only matches NULL, not any value
        const availableColorIds = new Set();
        window.productVariantsData.forEach(function(v) {
            const vVersionId = (v.version_id === 'none' || v.version_id === null || v.version_id === undefined) ? null : String(v.version_id);
            const vStorageId = (v.storage_id === 'none' || v.storage_id === null || v.storage_id === undefined) ? null : String(v.storage_id);
            const vColorId = (v.color_id === 'none' || v.color_id === null || v.color_id === undefined) ? null : String(v.color_id);
            
            // Strict matching: NULL only matches NULL
            const versionMatch = (vVersionId === null && vId === null) || 
                                (vVersionId !== null && vId !== null && vVersionId == vId);
            
            const storageMatch = (vStorageId === null && sId === null) || 
                                (vStorageId !== null && sId !== null && vStorageId == sId);
            
            // If variant matches selected storage and version, add its color to available list
            if (versionMatch && storageMatch && vColorId !== null && v.is_available && v.stock > 0) {
                availableColorIds.add(vColorId);
            }
        });
        
        // Show/hide color options based on availability
        const colorOptions = colorOptionsContainer.querySelectorAll('.color-option');
        let hasSelectedColor = false;
        let firstAvailableColor = null;
        
        colorOptions.forEach(function(colorOption) {
            const colorId = colorOption.getAttribute('data-color-id');
            const colorIdStr = (colorId === 'none' || colorId === null || colorId === undefined) ? null : String(colorId);
            
            if (colorIdStr !== null && availableColorIds.has(colorIdStr)) {
                // Color is available - show it
                colorOption.style.display = '';
                
                // Update color image to match the selected storage/version
                const variant = window.productVariantsData.find(function(v) {
                    const vVersionId = (v.version_id === 'none' || v.version_id === null || v.version_id === undefined) ? null : String(v.version_id);
                    const vStorageId = (v.storage_id === 'none' || v.storage_id === null || v.storage_id === undefined) ? null : String(v.storage_id);
                    const vColorId = (v.color_id === 'none' || v.color_id === null || v.color_id === undefined) ? null : String(v.color_id);
                    
                    // Strict matching: NULL only matches NULL
                    const versionMatch = (vVersionId === null && vId === null) || 
                                        (vVersionId !== null && vId !== null && vVersionId == vId);
                    const storageMatch = (vStorageId === null && sId === null) || 
                                        (vStorageId !== null && sId !== null && vStorageId == sId);
                    
                    return versionMatch && storageMatch && vColorId === colorIdStr;
                });
                
                if (variant && variant.image) {
                    const img = colorOption.querySelector('img');
                    if (img) {
                        img.src = variant.image;
                    }
                }
                
                if (!firstAvailableColor) {
                    firstAvailableColor = colorOption;
                }
                
                if (colorOption.classList.contains('selected')) {
                    hasSelectedColor = true;
                }
            } else {
                // Color is not available - hide it
                colorOption.style.display = 'none';
                
                // If this was selected, deselect it
                if (colorOption.classList.contains('selected')) {
                    colorOption.classList.remove('selected');
                    colorOption.style.borderColor = '#E4E7ED';
                    colorOption.style.background = '#fff';
                    const checkIcon = colorOption.querySelector('.fa-check');
                    if (checkIcon) {
                        checkIcon.remove();
                    }
                }
            }
        });
        
        // If selected color is no longer available, select first available color
        if (!hasSelectedColor && firstAvailableColor) {
            selectOption(firstAvailableColor, 'color');
        }
    }
    
    // Version Selection
    const versionOptions = document.querySelectorAll('.version-option');
    versionOptions.forEach(function(option) {
        option.addEventListener('click', function() {
            userHasInteracted = true; // Mark that user has interacted
            selectOption(this, 'version');
            updateAvailableColors(); // Update available colors first
            updateSelectedVariant();
        });
    });
    
    // Storage Selection
    const storageOptions = document.querySelectorAll('.storage-option');
    storageOptions.forEach(function(option) {
        option.addEventListener('click', function() {
            userHasInteracted = true; // Mark that user has interacted
            selectOption(this, 'storage');
            updateAvailableColors(); // Update available colors first
            updateSelectedVariant();
        });
    });
    
    // Color Selection
    const colorOptions = document.querySelectorAll('.color-option');
    colorOptions.forEach(function(option) {
        option.addEventListener('click', function() {
            userHasInteracted = true; // Mark that user has interacted
            selectOption(this, 'color');
            updateSelectedVariant();
        });
    });
    
    // Initialize with default variant on page load (allow best match during initialization)
    setTimeout(function() {
        updateAvailableColors(); // Filter colors based on default storage/version
        updateSelectedVariant(); // This will use best match since userHasInteracted is still false
    }, 100);
    
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
        
        // Update stock - lấy số lượng từ biến thể được chọn
        const stock = parseInt(variantElement.getAttribute('data-stock')) || 0;
        if (isNaN(stock)) {
            console.warn('Stock value is not a valid number:', variantElement.getAttribute('data-stock'));
        }
        updateStockUI(stock);
        
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
        
        // Update quantity max
        updateStockUI(stock);
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
            formData.append('product_id', {{ $product->id }}); // Validate variant belongs to product
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
    
    // Wishlist toggle
    if (btnWishlist) {
        btnWishlist.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            
            if (!productId) {
                showToast('Không tìm thấy sản phẩm!', 'error');
                return;
            }
            
            const originalText = btnWishlist.innerHTML;
            const originalDisabled = btnWishlist.disabled;
            btnWishlist.disabled = true;
            btnWishlist.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Đang xử lý...';
            
            fetch('{{ route("client.wishlist.toggle") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    product_id: productId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.in_wishlist) {
                        btnWishlist.classList.add('in-wishlist');
                        btnWishlist.innerHTML = '<i class="fa fa-heart"></i> Đã thêm vào yêu thích';
                    } else {
                        btnWishlist.classList.remove('in-wishlist');
                        btnWishlist.innerHTML = '<i class="fa fa-heart"></i> Thêm vào yêu thích';
                    }
                    showToast(data.message || 'Đã cập nhật wishlist!', 'success');
                } else {
                    if (data.message === 'Vui lòng đăng nhập để thêm vào wishlist.') {
                        window.location.href = '{{ route("client.login") }}';
                    } else {
                        showToast(data.message || 'Có lỗi xảy ra!', 'error');
                        btnWishlist.innerHTML = originalText;
                    }
                }
                btnWishlist.disabled = originalDisabled;
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Có lỗi xảy ra khi cập nhật wishlist!', 'error');
                btnWishlist.disabled = originalDisabled;
                btnWishlist.innerHTML = originalText;
            });
        });
    }
    
    // Initialize selected variant on page load
    // This is handled by updateSelectedVariant() which is called after a timeout
    
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
            // Lấy số lượng từ biến thể được chọn
            const stock = parseInt(this.getAttribute('data-stock')) || 0;
            if (isNaN(stock)) {
                console.warn('Stock value is not a valid number:', this.getAttribute('data-stock'));
            }
            
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
            
            // Update stock + quantity UI
            updateStockUI(stock);
            
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
            
            // Update quantity max
        updateStockUI(stock);
            
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
