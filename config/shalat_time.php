<?php

return [
    'default_provider' => env('SHALAT_TIME_PROVIDER', 'myquran_api'),

    'providers' => [
        'myquran_api' => [
            'name' => 'MyQuran API',
            'website_url' => 'https://api.myquran.com',
            'api' => [
                'base_url' => 'https://api.myquran.com/v2',
                'cities_endpoint' => '/sholat/kota/semua',
            ],
        ],
    ],
];
