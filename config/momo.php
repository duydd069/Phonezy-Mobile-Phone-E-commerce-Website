<?php

return [
    /*
    |--------------------------------------------------------------------------
    | MoMo Payment Configuration
    |--------------------------------------------------------------------------
    |
    | Cấu hình cho MoMo Payment Gateway
    |
    */

    'partner_code' => env('MOMO_PARTNER_CODE', ''),
    'access_key' => env('MOMO_ACCESS_KEY', ''),
    'secret_key' => env('MOMO_SECRET_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | MoMo API Endpoints
    |--------------------------------------------------------------------------
    */
    'endpoints' => [
        'sandbox' => 'https://test-payment.momo.vn/v2/gateway/api/create',
        'production' => 'https://payment.momo.vn/v2/gateway/api/create',
    ],

    /*
    |--------------------------------------------------------------------------
    | Environment
    |--------------------------------------------------------------------------
    | 'sandbox', 'production', hoặc 'mock' (để test không cần API thật)
    */
    'environment' => env('MOMO_ENVIRONMENT', 'mock'),

    /*
    |--------------------------------------------------------------------------
    | Return URL và Notify URL
    |--------------------------------------------------------------------------
    */
    'return_url' => env('MOMO_RETURN_URL', '/client/payment/momo/return'),
    'notify_url' => env('MOMO_NOTIFY_URL', '/client/payment/momo/notify'),

    /*
    |--------------------------------------------------------------------------
    | Request Type
    |--------------------------------------------------------------------------
    | 'captureWallet' - Thanh toán qua ví MoMo
    */
    'request_type' => 'captureWallet',
];

