<?php

return [

    // 默认驱动
    'default' => env('SMS_DEFAULT', 'alidayu'),
    'alidayu' => [
        // App Key.
        'appKey' => env('ALIDAYU_APP_KEY'),
        // App Secret.
        'appSecret' => env('ALIDAYU_APP_SECRET'),
        // 短信签名
        'sign' => env('ALIDAYU_SIGN'),
        // 使用沙箱环境
        'sandbox' => env('ALIDAYU_SANDBOX', false),
        // 是否使用HTTPs
        'secure' => env('ALIDAYU_SECURE', false)
    ]
];