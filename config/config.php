<?php

return [

    // 默认驱动
    'default' => env('SMS_DEFAULT', 'aliyun'),
    'aliyun' => [
        // Access Key ID.
        'accessKeyId' => env('ALIDAYU_ACCESS_KEY_ID'),
        // Access Secret.
        'accessKeySecret' => env('ALIDAYU_ACCESS_KEY_SECRET'),
        // 短信签名
        'sign' => env('ALIDAYU_SIGN', '大鱼测试'),
        // 使用沙箱环境
        'sandbox' => env('ALIDAYU_SANDBOX', false),
        // 是否使用HTTPs
        'secure' => env('ALIDAYU_SECURE', false)
    ]
];
