<?php

return [
    'tmn_code'      => env('VNP_TMN_CODE', ''),
    'hash_secret'   => env('VNP_HASH_SECRET', ''),
    'payment_url'   => env('VNP_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
    'return_url'    => env('VNP_RETURN_URL', env('APP_URL') . '/payment/vnpay/return'),
    'ipn_url'       => env('VNP_IPN_URL', env('APP_URL') . '/payment/vnpay/ipn'),
    'version'       => '2.1.0',
    'locale'        => 'vn',
    'curr_code'     => 'VND',
    'command'       => 'pay',
];


