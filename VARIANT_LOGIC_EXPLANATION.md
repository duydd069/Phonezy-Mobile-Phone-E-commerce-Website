# 📋 TÓM TẮT LOGIC BIẾN THỂ (VARIANT) VÀ SẢN PHẨM BIẾN THỂ

## 🎯 TỔNG QUAN

Hệ thống sử dụng **Product Variant** để quản lý các biến thể của sản phẩm dựa trên 3 thuộc tính:
- **Version (Phiên bản)**: Ví dụ: iPhone 15, iPhone 15 Pro, iPhone 15 Pro Max
- **Storage (Dung lượng)**: Ví dụ: 128GB, 256GB, 512GB
- **Color (Màu sắc)**: Ví dụ: Đen, Trắng, Hồng, Xanh

Mỗi sản phẩm có thể có nhiều variant khác nhau dựa trên sự kết hợp của 3 thuộc tính này.

---

## 🗄️ CẤU TRÚC DATABASE

### 1. Bảng `products`
```php
- id
- name
- image (ảnh mặc định)
- price (giá mặc định - không dùng nếu có variant)
- has_variant (boolean) - Có variant hay không
- category_id, brand_id, etc.
```

### 2. Bảng `product_variants`
```php
- id
- product_id (FK → products.id)
- version_id (FK → versions.id, nullable)
- storage_id (FK → storages.id, nullable)
- color_id (FK → colors.id, nullable)
- price (Giá bán)
- price_sale (Giá khuyến mãi, nullable)
- stock (Số lượng tồn kho)
- sold (Số lượng đã bán)
- sku (Mã sản phẩm)
- barcode
- image (Ảnh riêng cho variant này, nullable)
- status ('available', 'out_of_stock', 'discontinued')
- description
```

**LƯU Ý QUAN TRỌNG:**
- Mỗi variant có thể có `version_id`, `storage_id`, `color_id` hoặc NULL
- Nếu một thuộc tính là NULL, có nghĩa là variant này không có thuộc tính đó
- Ví dụ: Một sản phẩm có thể chỉ có variant theo màu sắc (không phân biệt version/storage)

### 3. Bảng `versions`, `storages`, `colors`
```php
// versions
- id
- name (ví dụ: "iPhone 15 Pro Max")

// storages  
- id
- storage (ví dụ: "128GB", "256GB")

// colors
- id
- name (ví dụ: "Đen", "Trắng")
- hex_code (Mã màu hex để hiển thị)
```

---

## 🔗 RELATIONSHIPS (Eloquent)

### Product Model
```php
public function variants() {
    return $this->hasMany(ProductVariant::class);
}
```

### ProductVariant Model
```php
public function product() {
    return $this->belongsTo(Product::class);
}

public function version() {
    return $this->belongsTo(Version::class);
}

public function storage() {
    return $this->belongsTo(Storage::class);
}

public function color() {
    return $this->belongsTo(Color::class);
}
```

---

## 🎨 LOGIC HOẠT ĐỘNG TRÊN FRONTEND

### 1. **Khởi tạo dữ liệu (PHP - Blade Template)**

Trong `product.blade.php`, khi load trang sản phẩm:

```php
// 1. Load tất cả variants của sản phẩm
$product->variants()->with(['version', 'storage', 'color'])->get();

// 2. Thu thập các giá trị unique
$uniqueVersions = [];    // Tất cả version IDs có trong variants
$uniqueStorages = [];    // Tất cả storage IDs có trong variants
$uniqueColors = [];      // Tất cả color IDs có trong variants

// 3. Tạo mảng allVariantsData chứa thông tin tất cả variants
$allVariantsData = [
    [
        'id' => 1,
        'version_id' => 5,
        'storage_id' => 2,
        'color_id' => 1,
        'price' => 20000000,
        'price_sale' => 18000000,
        'image' => 'path/to/image.jpg',
        'sku' => 'IP15PM256BLK',
        'stock' => 10,
        'is_available' => true,
        // ... các thông tin khác
    ],
    // ... các variant khác
];

// 4. Xác định variant mặc định (variant đầu tiên có stock > 0)
$defaultVersionId = 'none' | version_id;
$defaultStorageId = 'none' | storage_id;
$defaultColorId = 'none' | color_id;
```

### 2. **Hiển thị các tùy chọn (HTML)**

```html
<!-- Chọn Phiên bản -->
<div class="variant-options" id="version-options">
    <div class="variant-option version-option" data-version-id="5">iPhone 15 Pro Max</div>
    <div class="variant-option version-option" data-version-id="6">iPhone 15 Pro</div>
</div>

<!-- Chọn Dung lượng -->
<div class="variant-options" id="storage-options">
    <div class="variant-option storage-option" data-storage-id="2">256GB</div>
    <div class="variant-option storage-option" data-storage-id="3">512GB</div>
</div>

<!-- Chọn Màu sắc -->
<div class="variant-options" id="color-options">
    <div class="variant-option color-option" data-color-id="1">
        <span style="background: #000000"></span> Đen
    </div>
    <div class="variant-option color-option" data-color-id="2">
        <span style="background: #FFFFFF"></span> Trắng
    </div>
</div>
```

### 3. **JavaScript - Logic xử lý khi người dùng chọn**

#### a. **Tìm variant khớp (`findMatchingVariant`)**

```javascript
function findMatchingVariant(versionId, storageId, colorId) {
    // Chuyển 'none' thành null
    const vId = (versionId === 'none') ? null : String(versionId);
    const sId = (storageId === 'none') ? null : String(storageId);
    const cId = (colorId === 'none') ? null : String(colorId);
    
    // Tìm variant khớp hoàn toàn
    let variant = window.productVariantsData.find(v => {
        const vVersionId = (v.version_id === 'none' || !v.version_id) ? null : String(v.version_id);
        const vStorageId = (v.storage_id === 'none' || !v.storage_id) ? null : String(v.storage_id);
        const vColorId = (v.color_id === 'none' || !v.color_id) ? null : String(v.color_id);
        
        const versionMatch = (vId === null) || (vVersionId == vId);
        const storageMatch = (sId === null) || (vStorageId == sId);
        const colorMatch = (cId === null) || (vColorId == cId);
        
        return versionMatch && storageMatch && colorMatch && v.is_available && v.stock > 0;
    });
    
    // Nếu không tìm thấy khớp hoàn toàn, tìm "best match"
    if (!variant) {
        // Tính điểm ưu tiên: storage > version > color
        // Chọn variant có điểm cao nhất và có sẵn hàng
    }
    
    return variant;
}
```

**LOGIC MATCHING:**
- Nếu user chọn version=5, storage=2, color=1
- Tìm variant có: `version_id=5 AND storage_id=2 AND color_id=1`
- Nếu không tìm thấy, tìm variant "best match" dựa trên điểm ưu tiên
- **Điểm ưu tiên:** storage (3 điểm) > version (2 điểm) > color (1 điểm)

#### b. **Cập nhật màu sắc khả dụng (`updateAvailableColors`)**

Khi user chọn version và storage, hệ thống **tự động ẩn/hiện các màu sắc** phù hợp:

```javascript
function updateAvailableColors() {
    const selectedVersionId = document.querySelector('.version-option.selected')?.getAttribute('data-version-id') || 'none';
    const selectedStorageId = document.querySelector('.storage-option.selected')?.getAttribute('data-storage-id') || 'none';
    
    // Duyệt qua tất cả các option màu sắc
    allColorOptions.forEach(colorOption => {
        const colorId = colorOption.getAttribute('data-color-id');
        
        // Kiểm tra xem có variant nào khớp không
        const hasMatchingVariant = window.productVariantsData.some(v =>
            (v.version_id == selectedVersionId || selectedVersionId === 'none') &&
            (v.storage_id == selectedStorageId || selectedStorageId === 'none') &&
            (v.color_id == colorId) &&
            v.is_available && v.stock > 0
        );
        
        if (hasMatchingVariant) {
            colorOption.style.display = 'flex'; // Hiển thị
        } else {
            colorOption.style.display = 'none'; // Ẩn
        }
    });
    
    // Tự động chọn màu đầu tiên khả dụng nếu màu hiện tại không còn khả dụng
}
```

**VÍ DỤ:**
- User chọn: Version = "iPhone 15 Pro Max", Storage = "256GB"
- Hệ thống chỉ hiển thị các màu có variant với combo này
- Nếu chỉ còn màu "Hồng" khả dụng → chỉ hiển thị màu Hồng

#### c. **Cập nhật thông tin sản phẩm (`updateSelectedVariant`)**

Khi tìm được variant khớp, cập nhật:

```javascript
function updateSelectedVariant() {
    const versionId = ...;
    const storageId = ...;
    const colorId = ...;
    
    const variant = findMatchingVariant(versionId, storageId, colorId);
    
    if (variant) {
        // Cập nhật hình ảnh chính
        document.getElementById('main-product-image').src = variant.image;
        
        // Cập nhật giá
        const priceElement = document.querySelector('.product-price');
        if (variant.price_sale) {
            priceElement.innerHTML = `
                <span class="price-old">${formatPrice(variant.price)}</span>
                <span class="price-new">${formatPrice(variant.price_sale)}</span>
            `;
        } else {
            priceElement.textContent = formatPrice(variant.price);
        }
        
        // Cập nhật SKU
        document.getElementById('product-sku').textContent = variant.sku;
        
        // Cập nhật stock
        const stockElement = document.getElementById('product-stock');
        if (variant.stock > 0) {
            stockElement.textContent = `Còn ${variant.stock} sản phẩm`;
            stockElement.className = 'in-stock';
            // Enable nút "Thêm vào giỏ"
            btnAddCart.disabled = false;
        } else {
            stockElement.textContent = 'Hết hàng';
            stockElement.className = 'out-of-stock';
            // Disable nút "Thêm vào giỏ"
            btnAddCart.disabled = true;
        }
        
        // Lưu variant ID được chọn
        selectedVariantId = variant.id;
    }
}
```

---

## 🛒 LOGIC TRONG GIỎ HÀNG VÀ CHECKOUT

### CartItem
```php
// Bảng cart_items
- id
- cart_id
- product_id (FK → products.id) - Sản phẩm gốc
- product_variant_id (FK → product_variants.id, nullable) - Variant được chọn
- quantity
```

**LƯU Ý:**
- Nếu sản phẩm không có variant → `product_variant_id = NULL`
- Nếu sản phẩm có variant → BẮT BUỘC phải có `product_variant_id`

### Logic tính giá trong CheckoutController

```php
protected function getVariantPrice(ProductVariant $variant): float
{
    // Ưu tiên giá sale, nếu không có thì dùng giá thường
    return $variant->price_sale ?? $variant->price;
}

// Khi tính tổng giá trị đơn hàng
$subtotal = $items->sum(function ($item) {
    $variant = $item->variant; // ProductVariant model
    return $this->getVariantPrice($variant) * $item->quantity;
});
```

---

## 🔄 FLOW HOÀN CHỈNH

### 1. **Khi user xem trang sản phẩm:**

```
1. Load sản phẩm + tất cả variants (với relationships: version, storage, color)
2. Thu thập unique versions, storages, colors
3. Xác định variant mặc định (variant đầu tiên có stock > 0)
4. Render HTML với các tùy chọn version/storage/color
5. Pass dữ liệu variants sang JavaScript (window.productVariantsData)
6. Khởi tạo: Chọn variant mặc định → Hiển thị thông tin (giá, SKU, stock, hình ảnh)
```

### 2. **Khi user chọn version/storage/color:**

```
1. User click vào option (ví dụ: chọn "256GB")
2. Event listener bắt sự kiện click
3. Cập nhật class "selected" cho option được chọn
4. Gọi updateAvailableColors() → Ẩn/hiện màu sắc phù hợp
5. Gọi updateSelectedVariant() → Tìm variant khớp
6. Nếu tìm thấy variant:
   - Cập nhật hình ảnh chính
   - Cập nhật giá (hiển thị price_sale nếu có)
   - Cập nhật SKU
   - Cập nhật stock (enable/disable nút thêm vào giỏ)
   - Lưu variant ID vào biến selectedVariantId
7. Nếu không tìm thấy variant khớp:
   - Giữ nguyên thông tin variant trước đó
   - Có thể hiển thị thông báo "Tùy chọn này không khả dụng"
```

### 3. **Khi user thêm vào giỏ hàng:**

```
1. User click nút "Thêm vào giỏ"
2. Kiểm tra selectedVariantId có hợp lệ không
3. Gửi AJAX request với:
   - product_id
   - product_variant_id = selectedVariantId
   - quantity
4. Backend xử lý:
   - Kiểm tra variant có tồn tại và có stock không
   - Kiểm tra xem user đã có variant này trong giỏ chưa
   - Nếu có → tăng quantity
   - Nếu chưa → tạo CartItem mới
5. Trả về response → Cập nhật UI (hiển thị số lượng trong giỏ)
```

### 4. **Khi user checkout:**

```
1. Load tất cả CartItem với variant
2. Với mỗi item:
   - Lấy variant.price_sale hoặc variant.price
   - Tính: price * quantity
3. Tính tổng subtotal
4. Áp dụng coupon (nếu có)
5. Tính total = subtotal - discount + shipping
6. Khi tạo Order:
   - Lưu variant ID vào OrderItem
   - Trừ stock của variant: variant.stock -= quantity
   - Tăng sold: variant.sold += quantity
```

---

## ⚠️ CÁC ĐIỂM QUAN TRỌNG

### 1. **Xử lý NULL values**
- `version_id`, `storage_id`, `color_id` có thể là NULL
- Trong JavaScript, chuyển thành `'none'` để xử lý dễ dàng
- Khi match, nếu giá trị là `null` hoặc `'none'` → bỏ qua điều kiện đó

### 2. **Stock Management**
- Mỗi variant có `stock` riêng
- Khi đặt hàng thành công → trừ stock của variant đó
- Nếu variant có `stock = 0` hoặc `status != 'available'` → Không cho phép mua

### 3. **Best Match Algorithm**
- Khi không tìm thấy variant khớp hoàn toàn
- Tính điểm ưu tiên: Storage (3 điểm) > Version (2 điểm) > Color (1 điểm)
- Chọn variant có điểm cao nhất và có sẵn hàng

### 4. **Dynamic Color Filtering**
- Khi user chọn version/storage → Tự động filter màu sắc
- Chỉ hiển thị màu có variant khả dụng với combo đã chọn
- Tự động chọn màu đầu tiên khả dụng nếu màu hiện tại không còn khả dụng

### 5. **Image Handling**
- Variant có thể có `image` riêng
- Nếu variant không có image → Dùng image của product
- Khi chọn variant → Cập nhật hình ảnh chính theo variant đó

---

## 📝 VÍ DỤ THỰC TẾ

### Sản phẩm: iPhone 15 Pro Max

**Variants trong database:**
```
Variant 1: version_id=5 (Pro Max), storage_id=2 (256GB), color_id=1 (Đen)
  → Price: 28,000,000đ, Stock: 10, SKU: IP15PM256BLK

Variant 2: version_id=5 (Pro Max), storage_id=2 (256GB), color_id=2 (Trắng)
  → Price: 28,000,000đ, Stock: 0 (Hết hàng), SKU: IP15PM256WHT

Variant 3: version_id=5 (Pro Max), storage_id=3 (512GB), color_id=1 (Đen)
  → Price: 32,000,000đ, Stock: 5, SKU: IP15PM512BLK
```

**User flow:**
1. Trang load → Hiển thị Variant 1 (mặc định)
2. User chọn "256GB" → Vẫn hiển thị Variant 1
3. User chọn "Đen" → Vẫn hiển thị Variant 1
4. User chọn "Trắng" → Hệ thống tìm Variant 2
   - Nhưng Variant 2 hết hàng → Giữ nguyên Variant 1 hoặc hiển thị thông báo
5. User chọn "512GB" → Tìm Variant 3 → Cập nhật giá lên 32,000,000đ
6. User thêm vào giỏ → Lưu variant_id=3 vào CartItem

---

## 🎯 TÓM TẮT NGẮN GỌN

1. **Mỗi sản phẩm có thể có nhiều variants** dựa trên Version/Storage/Color
2. **Mỗi variant có giá, stock, SKU, hình ảnh riêng**
3. **Frontend hiển thị 3 bộ lọc độc lập** (Version, Storage, Color)
4. **JavaScript tự động tìm variant khớp** khi user chọn
5. **Màu sắc được filter động** dựa trên Version/Storage đã chọn
6. **Thông tin sản phẩm (giá, SKU, stock, hình ảnh) cập nhật theo variant được chọn**
7. **Giỏ hàng lưu variant_id**, checkout tính giá theo variant

---

**File chính:**
- `app/Models/ProductVariant.php` - Model
- `resources/views/electro/product.blade.php` - Frontend template và JavaScript
- `app/Http/Controllers/Client/ProductController.php` - Controller xử lý

