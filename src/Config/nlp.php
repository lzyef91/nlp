<?php

return [
    'debug' => env('NLP_DEBUG', false),
    'timeout' => env('NLP_REQUEST_TIMEOUT', 10),
    'default_gateway' => env('NLP_DEFAULT_GATEWAY', 'tencent'),
    /*
    |--------------------------------------------------------
    | 可用的网关配置
    |--------------------------------------------------------
    */
    'gateways' => [
        'aliyun' => [
            'access_key'     => env('ALIYUN_APP_ACCESS_KEY', ''),
            'access_secret'    => env('ALIYUN_APP_ACCESS_SECRET', ''),
            'region' => 'cn-shanghai',
            'version' => env('NLP_ALIYUN_VERSION', '2018-04-08')
        ],
        'tencent' => [
            'access_key'     => env('TENCENT_APP_ACCESS_KEY', ''),
            'access_secret'    => env('TENCENT_APP_ACCESS_SECRET', ''),
            'region' => 'ap-guangzhou',
            'version' => env('NLP_ALIYUN_VERSION', '2019-04-08')
        ]
    ]

];