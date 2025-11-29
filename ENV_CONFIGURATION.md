# Hướng dẫn cấu hình file .env

## 📋 Cấu hình thanh toán MoMo

Thêm các dòng sau vào file `.env`:

```env
# ============================================
# CẤU HÌNH THANH TOÁN MOMO
# ============================================

# Môi trường: 'mock' (test), 'sandbox' (test với API thật), 'production' (thật)
MOMO_ENVIRONMENT=mock

# Thông tin API từ MoMo Business (chỉ cần khi dùng sandbox/production)
MOMO_PARTNER_CODE=
MOMO_ACCESS_KEY=
MOMO_SECRET_KEY=

# URLs (thường không cần thay đổi)
MOMO_RETURN_URL=/client/payment/momo/return
MOMO_NOTIFY_URL=/client/payment/momo/notify
```

### Giải thích:

- **MOMO_ENVIRONMENT=mock**: Dùng chế độ test (không cần API thật)
- **MOMO_ENVIRONMENT=sandbox**: Test với API thật từ MoMo (cần đăng ký)
- **MOMO_ENVIRONMENT=production**: Môi trường thật (cần đăng ký và có giấy phép)

---

## 💳 Cấu hình chuyển khoản ngân hàng

Thêm các dòng sau vào file `.env`:

```env
# ============================================
# CẤU HÌNH CHUYỂN KHOẢN NGÂN HÀNG
# ============================================

# Thông tin tài khoản ngân hàng
BANK_NAME=Vietcombank
BANK_ACCOUNT_NUMBER=1234567890
BANK_ACCOUNT_HOLDER=Nguyễn Văn A
BANK_BRANCH=Chi nhánh Hà Nội
BANK_QR_CODE=  # Tùy chọn: URL hoặc path đến ảnh QR code (ví dụ: /images/qr-code.png)

# Ẩn trạng thái thanh toán ở client
# false = ẩn (khách hàng không thấy trạng thái)
# true = hiển thị (khách hàng thấy trạng thái)
BANK_SHOW_PAYMENT_STATUS=false

# Tự động xác nhận thanh toán qua webhook
# false = xác nhận thủ công qua admin panel
# true = tự động xác nhận khi nhận webhook (cần tích hợp API)
BANK_AUTO_CONFIRM=false

# Secret key để bảo mật webhook (để trống nếu không dùng webhook)
BANK_WEBHOOK_SECRET=
```

### Giải thích:

- **BANK_NAME**: Tên ngân hàng (ví dụ: Vietcombank, Techcombank, BIDV...)
- **BANK_ACCOUNT_NUMBER**: Số tài khoản ngân hàng của bạn
- **BANK_ACCOUNT_HOLDER**: Tên chủ tài khoản (phải khớp với tài khoản)
- **BANK_BRANCH**: Chi nhánh ngân hàng (tùy chọn)
- **BANK_QR_CODE**: Đường dẫn đến ảnh QR code (tùy chọn)
- **BANK_SHOW_PAYMENT_STATUS**: Ẩn/hiện trạng thái thanh toán ở trang client
- **BANK_AUTO_CONFIRM**: Bật/tắt tự động xác nhận qua webhook
- **BANK_WEBHOOK_SECRET**: Key bảo mật cho webhook

---

## 📝 Ví dụ cấu hình đầy đủ

```env
# ============================================
# THANH TOÁN MOMO
# ============================================
MOMO_ENVIRONMENT=mock
MOMO_PARTNER_CODE=
MOMO_ACCESS_KEY=
MOMO_SECRET_KEY=
MOMO_RETURN_URL=/client/payment/momo/return
MOMO_NOTIFY_URL=/client/payment/momo/notify

# ============================================
# CHUYỂN KHOẢN NGÂN HÀNG
# ============================================
BANK_NAME=Vietcombank
BANK_ACCOUNT_NUMBER=1234567890
BANK_ACCOUNT_HOLDER=Nguyễn Văn A
BANK_BRANCH=Chi nhánh Hà Nội
BANK_QR_CODE=
BANK_SHOW_PAYMENT_STATUS=false
BANK_AUTO_CONFIRM=false
BANK_WEBHOOK_SECRET=
```

---

## 🔧 Sau khi cấu hình

Sau khi thêm/sửa các cấu hình trong file `.env`, chạy lệnh sau để clear cache:

```bash
php artisan config:clear
```

---

## ⚠️ Lưu ý quan trọng

1. **Không commit file `.env` lên Git** - File này chứa thông tin nhạy cảm
2. **BANK_SHOW_PAYMENT_STATUS=false**: Khách hàng sẽ không thấy trạng thái thanh toán
3. **MOMO_ENVIRONMENT=mock**: Dùng để test, không cần API thật
4. **BANK_AUTO_CONFIRM=false**: Bạn cần vào admin panel để xác nhận thanh toán thủ công

---

## 🎯 Các tình huống sử dụng

### Tình huống 1: Test/Dự án học tập
```env
MOMO_ENVIRONMENT=mock
BANK_SHOW_PAYMENT_STATUS=false
BANK_AUTO_CONFIRM=false
```

### Tình huống 2: Production với MoMo thật
```env
MOMO_ENVIRONMENT=production
MOMO_PARTNER_CODE=your_partner_code
MOMO_ACCESS_KEY=your_access_key
MOMO_SECRET_KEY=your_secret_key
BANK_SHOW_PAYMENT_STATUS=false
BANK_AUTO_CONFIRM=false
```

### Tình huống 3: Có tích hợp webhook tự động
```env
BANK_AUTO_CONFIRM=true
BANK_WEBHOOK_SECRET=your_secure_secret_key_here
```

