# HỆ THỐNG LOGIC VÀ PHÂN CHIA ƯU ĐÃI CỦA KHUYẾN MÃI

## 📋 TỔNG QUAN

Hệ thống khuyến mãi được xây dựng với các thành phần chính:
- **Model Coupon**: Quản lý thông tin và logic tính toán giảm giá
- **CheckoutController**: Xử lý áp dụng coupon trong quá trình thanh toán
- **CouponController (Admin)**: Quản lý tạo/sửa/xóa coupon
- **CouponController (Client)**: Hiển thị danh sách coupon cho user

---

## 🎯 CÁC LOẠI KHUYẾN MÃI

### 1. **Phân loại theo phạm vi áp dụng (promotion_type)**

#### a) **Khuyến mãi cho Đơn hàng (order)**
- Áp dụng cho **toàn bộ đơn hàng**
- Tính giảm giá dựa trên **tổng giá trị đơn hàng (subtotal)**
- Tất cả sản phẩm trong giỏ hàng đều được hưởng ưu đãi

**Ví dụ:**
- Giảm 10% cho đơn hàng
- Giảm 50,000đ cho đơn hàng

#### b) **Khuyến mãi cho Sản phẩm (product)**
- Chỉ áp dụng cho **các sản phẩm được chỉ định**
- Phải gán sản phẩm cụ thể vào coupon
- Tính giảm giá cho **từng sản phẩm** được áp dụng

**Ví dụ:**
- Giảm 20% cho sản phẩm A, B, C
- Giảm 30,000đ cho sản phẩm X, Y

### 2. **Phân loại theo quyền truy cập (type)**

#### a) **Coupon Công khai (public)**
- Tất cả người dùng đều có thể sử dụng
- Không cần gán user cụ thể

#### b) **Coupon Riêng tư (private)**
- Chỉ những user được chỉ định mới sử dụng được
- Phải gán user cụ thể vào coupon
- Kiểm tra quyền sử dụng trong `canBeUsedBy()`

### 3. **Phân loại theo cách giảm giá (discount_type)**

#### a) **Giảm theo phần trăm (percent)**
- Tính: `(Giá trị * discount_value) / 100`
- Đảm bảo không giảm quá giá trị gốc

#### b) **Giảm cố định (fixed)**
- Giảm một số tiền cố định
- Đảm bảo không giảm quá giá trị gốc

---

## 🔍 LOGIC KIỂM TRA VÀ XÁC THỰC COUPON

### Hàm `validateCode()` trong Model Coupon

```php
Coupon::validateCode($code, $userId, $productIds)
```

**Các bước kiểm tra:**

1. **Tìm coupon theo mã code** (không phân biệt hoa thường)
2. **Kiểm tra tính hợp lệ thời gian:**
   - `hasStarted()`: Coupon đã bắt đầu chưa?
   - `isExpired()`: Coupon đã hết hạn chưa?
   - `isValid()`: Coupon đang hoạt động không?

3. **Kiểm tra quyền sử dụng:**
   - Public coupon: Ai cũng dùng được
   - Private coupon: Chỉ user được gán mới dùng được

4. **Kiểm tra áp dụng cho sản phẩm (nếu là product-level coupon):**
   - Kiểm tra có sản phẩm nào trong giỏ hàng được áp dụng không
   - Nếu không có sản phẩm nào phù hợp → coupon không hợp lệ

---

## 💰 LOGIC TÍNH TOÁN GIẢM GIÁ

### 1. **Tính giảm giá cho Đơn hàng** (`calculateDiscount()`)

**File:** `app/Models/Coupon.php` (dòng 74-88)

```php
public function calculateDiscount(float $subtotal): float
{
    if (!$this->isValid()) {
        return 0;
    }

    if ($this->discount_type === 'percent') {
        // Giảm theo phần trăm
        $discount = ($subtotal * $this->discount_value) / 100;
        return min($discount, $subtotal); // Không giảm quá subtotal
    } else {
        // Giảm cố định
        return min($this->discount_value, $subtotal); // Không giảm quá subtotal
    }
}
```

**Ví dụ:**
- Subtotal: 500,000đ
- Coupon: Giảm 10% → Discount: 50,000đ
- Coupon: Giảm 100,000đ → Discount: 100,000đ (không quá subtotal)

### 2. **Tính giảm giá cho Sản phẩm** (`calculateProductDiscount()`)

**File:** `app/Models/Coupon.php` (dòng 93-106)

```php
public function calculateProductDiscount(float $productPrice): float
{
    if (!$this->isValid() || !$this->isForProduct()) {
        return 0;
    }

    if ($this->discount_type === 'percent') {
        $discount = ($productPrice * $this->discount_value) / 100;
        return min($discount, $productPrice);
    } else {
        return min($this->discount_value, $productPrice);
    }
}
```

**Ví dụ:**
- Giá sản phẩm: 200,000đ
- Coupon: Giảm 15% → Discount: 30,000đ/sản phẩm
- Coupon: Giảm 50,000đ → Discount: 50,000đ/sản phẩm

---

## 🛒 QUY TRÌNH ÁP DỤNG COUPON TRONG CHECKOUT

### File: `app/Http/Controllers/Client/CheckoutController.php`

### Bước 1: **Lấy và validate coupon** (dòng 50-63, 117-130)

```php
$couponCode = request('coupon_code') ?? session('checkout_coupon_code');
$productIds = $items->pluck('variant.product_id')->unique()->toArray();
$coupon = Coupon::validateCode($couponCode, $userId, $productIds);
```

### Bước 2: **Tính toán summary với coupon** (`buildSummary()`)

**File:** `app/Http/Controllers/Client/CheckoutController.php` (dòng 283-329)

#### **Trường hợp 1: Coupon cho Đơn hàng**

```php
if ($coupon->isForOrder()) {
    // Tính subtotal của toàn bộ đơn hàng
    $subtotal = $items->sum(function ($item) {
        $variant = $item->variant;
        return $this->getVariantPrice($variant) * $item->quantity;
    });
    
    // Tính discount cho toàn bộ đơn hàng
    $discount = $coupon->calculateDiscount($subtotal);
}
```

**Ví dụ:**
- Sản phẩm A: 100,000đ x 2 = 200,000đ
- Sản phẩm B: 150,000đ x 1 = 150,000đ
- **Subtotal: 350,000đ**
- Coupon giảm 10% → **Discount: 35,000đ**

#### **Trường hợp 2: Coupon cho Sản phẩm**

```php
else {
    // Tính discount cho từng sản phẩm được áp dụng
    $subtotal = 0;
    $discount = 0;
    
    foreach ($items as $item) {
        $variant = $item->variant;
        $productPrice = $this->getVariantPrice($variant);
        $itemSubtotal = $productPrice * $item->quantity;
        $subtotal += $itemSubtotal;
        
        // Kiểm tra coupon có áp dụng cho sản phẩm này không
        if ($coupon->appliesToProduct($variant->product_id)) {
            // Tính discount cho từng sản phẩm
            $productDiscount = $coupon->calculateProductDiscount($productPrice);
            $discount += $productDiscount * $item->quantity; // Nhân với số lượng
        }
    }
}
```

**Ví dụ:**
- Sản phẩm A (được áp dụng): 100,000đ x 2 = 200,000đ
  - Discount: 20,000đ/sản phẩm x 2 = **40,000đ**
- Sản phẩm B (không được áp dụng): 150,000đ x 1 = 150,000đ
  - Discount: 0đ
- **Subtotal: 350,000đ**
- **Tổng Discount: 40,000đ**

### Bước 3: **Tính tổng cuối cùng**

```php
$total = max($subtotal - $discount + $shippingFee, 0);
```

**Đảm bảo:**
- Tổng không âm (tối thiểu = 0)
- Cộng thêm phí vận chuyển

### Bước 4: **Lưu vào đơn hàng**

```php
Order::create([
    'coupon_id' => $coupon?->id,
    'subtotal' => $summary['subtotal'],
    'discount_amount' => $summary['discount'],
    'total' => $summary['total'],
    // ...
]);
```

---

## 🔐 KIỂM TRA QUYỀN SỬ DỤNG

### Hàm `canBeUsedBy()` trong Model Coupon

**File:** `app/Models/Coupon.php` (dòng 203-219)

```php
public function canBeUsedBy(?int $userId): bool
{
    // Public coupon: ai cũng dùng được
    if ($this->isPublic()) {
        return true;
    }
    
    // Private coupon: chỉ user được chỉ định mới dùng được
    if ($this->isPrivate()) {
        if (!$userId) {
            return false; // Chưa đăng nhập
        }
        return $this->users()->where('user_id', $userId)->exists();
    }
    
    return false;
}
```

---

## ⏰ KIỂM TRA THỜI GIAN

### Hàm `isValid()` trong Model Coupon

**File:** `app/Models/Coupon.php` (dòng 50-53)

```php
public function isValid(): bool
{
    return $this->hasStarted() && !$this->isExpired();
}
```

**Logic:**
- `hasStarted()`: Nếu không có `starts_at` → coi như đã bắt đầu. Nếu có → kiểm tra đã qua chưa
- `isExpired()`: Nếu không có `expires_at` → không bao giờ hết hạn. Nếu có → kiểm tra đã qua chưa

---

## 📊 VÍ DỤ TỔNG HỢP

### **Scenario 1: Coupon cho đơn hàng - Giảm phần trăm**

**Thông tin:**
- Coupon: "SALE10" - Giảm 10% cho đơn hàng (public)
- Giỏ hàng:
  - Sản phẩm A: 200,000đ x 1
  - Sản phẩm B: 300,000đ x 2
- Phí vận chuyển: 30,000đ

**Tính toán:**
1. Subtotal = 200,000 + (300,000 x 2) = **800,000đ**
2. Discount = 800,000 x 10% = **80,000đ**
3. Total = 800,000 - 80,000 + 30,000 = **750,000đ**

---

### **Scenario 2: Coupon cho sản phẩm - Giảm cố định**

**Thông tin:**
- Coupon: "PRODUCT50K" - Giảm 50,000đ cho sản phẩm A, B (public)
- Giỏ hàng:
  - Sản phẩm A: 200,000đ x 2 (được áp dụng)
  - Sản phẩm B: 300,000đ x 1 (được áp dụng)
  - Sản phẩm C: 150,000đ x 1 (KHÔNG được áp dụng)
- Phí vận chuyển: 30,000đ

**Tính toán:**
1. Subtotal = (200,000 x 2) + 300,000 + 150,000 = **850,000đ**
2. Discount:
   - Sản phẩm A: 50,000đ x 2 = 100,000đ
   - Sản phẩm B: 50,000đ x 1 = 50,000đ
   - Sản phẩm C: 0đ
   - **Tổng Discount: 150,000đ**
3. Total = 850,000 - 150,000 + 30,000 = **730,000đ**

---

### **Scenario 3: Coupon riêng tư**

**Thông tin:**
- Coupon: "VIP20" - Giảm 20% cho đơn hàng (private, chỉ user ID 5)
- User ID 3 cố gắng sử dụng

**Kết quả:**
- `validateCode()` trả về `null` vì user 3 không có trong danh sách users của coupon
- Coupon không được áp dụng

---

## 🎨 CÁC ĐIỂM QUAN TRỌNG

1. **Đảm bảo không giảm quá giá trị gốc:**
   - Luôn dùng `min($discount, $value)` để đảm bảo discount không vượt quá giá trị

2. **Kiểm tra tính hợp lệ trước khi tính toán:**
   - Luôn gọi `isValid()` trước khi tính discount

3. **Xử lý product-level coupon:**
   - Phải kiểm tra `appliesToProduct()` cho từng sản phẩm
   - Nhân discount với số lượng sản phẩm

4. **Lưu thông tin coupon vào đơn hàng:**
   - Lưu `coupon_id` để tra cứu sau này
   - Lưu `discount_amount` để hiển thị

5. **Session management:**
   - Lưu coupon code vào session khi validate thành công
   - Xóa session sau khi đặt hàng thành công

---

## 📝 TÓM TẮT

**Hệ thống khuyến mãi hoạt động theo 3 tầng:**

1. **Tầng kiểm tra:** Validate code, thời gian, quyền, sản phẩm
2. **Tầng tính toán:** Tính discount dựa trên loại coupon (order/product) và cách giảm (percent/fixed)
3. **Tầng áp dụng:** Áp dụng discount vào tổng đơn hàng và lưu vào database

**Đặc điểm nổi bật:**
- Hỗ trợ cả coupon cho đơn hàng và sản phẩm
- Hỗ trợ cả coupon công khai và riêng tư
- Đảm bảo không giảm quá giá trị gốc
- Kiểm tra đầy đủ tính hợp lệ trước khi áp dụng
