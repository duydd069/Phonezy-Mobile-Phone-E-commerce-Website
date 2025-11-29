<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Bank Transfer Configuration
    |--------------------------------------------------------------------------
    |
    | Cấu hình thông tin tài khoản ngân hàng để nhận thanh toán
    | Bạn có thể thêm nhiều tài khoản ngân hàng
    |
    */

    'accounts' => [
        [
            'bank_name' => env('BANK_NAME', 'Ngân hàng ABC'),
            'account_number' => env('BANK_ACCOUNT_NUMBER', '1234567890'),
            'account_holder' => env('BANK_ACCOUNT_HOLDER', 'Tên chủ tài khoản'),
            'branch' => env('BANK_BRANCH', 'Chi nhánh'),
            'qr_code' => env('BANK_QR_CODE', ''), // URL hoặc path đến ảnh QR code (tùy chọn)
        ],
        // Bạn có thể thêm nhiều tài khoản ngân hàng khác ở đây
        // [
        //     'bank_name' => 'Ngân hàng XYZ',
        //     'account_number' => '0987654321',
        //     'account_holder' => 'Tên chủ tài khoản',
        //     'branch' => 'Chi nhánh',
        //     'qr_code' => '',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Hướng dẫn thanh toán
    |--------------------------------------------------------------------------
    */
    'instructions' => [
        'Bước 1: Chuyển khoản đúng số tiền: {amount}',
        'Bước 2: Nội dung chuyển khoản: Mã đơn hàng #{order_id}',
        'Bước 3: Sau khi chuyển khoản, vui lòng gửi ảnh chứng từ (nếu có)',
        'Bước 4: Chúng tôi sẽ xác nhận và xử lý đơn hàng trong vòng 24h',
    ],

    /*
    |--------------------------------------------------------------------------
    | Hiển thị trạng thái thanh toán ở client
    |--------------------------------------------------------------------------
    | Nếu false, sẽ ẩn trạng thái thanh toán ở trang checkout-success
    | Chỉ admin mới thấy trạng thái thanh toán
    */
    'show_payment_status' => env('BANK_SHOW_PAYMENT_STATUS', false),

    /*
    |--------------------------------------------------------------------------
    | Tự động xác nhận thanh toán
    |--------------------------------------------------------------------------
    | Nếu true, hệ thống sẽ tự động cập nhật payment_status = 'paid' sau khi nhận được thông báo
    | (Cần tích hợp với API ngân hàng hoặc webhook)
    | Hiện tại: false - cần xác nhận thủ công qua admin hoặc webhook
    */
    'auto_confirm' => env('BANK_AUTO_CONFIRM', false),

    /*
    |--------------------------------------------------------------------------
    | Webhook Secret Key
    |--------------------------------------------------------------------------
    | Key để xác thực webhook từ ngân hàng hoặc dịch vụ bên thứ 3
    | Để trống nếu không sử dụng webhook
    */
    'webhook_secret' => env('BANK_WEBHOOK_SECRET', ''),
];

