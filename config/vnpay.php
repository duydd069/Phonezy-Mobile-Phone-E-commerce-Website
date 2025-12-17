<?php

return [
    'tmn_code'      => env('VNP_TMN_CODE', 'ZF37K25R'), // Sandbox Terminal ID
    'hash_secret'   => env('VNP_HASH_SECRET', 'UDDF345GXRG2101T0189ISCX5MVLX7TS'), // Sandbox Secret Key
    'payment_url'   => env('VNP_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
    'return_url'    => env('VNP_RETURN_URL', env('APP_URL') . '/payment/vnpay/return'),
    'ipn_url'       => env('VNP_IPN_URL', env('APP_URL') . '/payment/vnpay/ipn'),
    'refund_url'    => env('VNP_REFUND_URL', 'https://sandbox.vnpayment.vn/merchant_webapi/api/transaction'),
    'version'       => '2.1.0',
    'locale'        => 'vn',
    'curr_code'     => 'VND',
    'command'       => 'pay',
];


