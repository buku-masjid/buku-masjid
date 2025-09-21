<?php

return [
    'default_provider' => env('SHALAT_TIME_PROVIDER', 'myquran_api'),

    'adjustment_in_minutes' => [
        'imsak' => env('SHALAT_TIME_ADJUSTMENT_IN_MINUTES_IMSAK'),
        'fajr' => env('SHALAT_TIME_ADJUSTMENT_IN_MINUTES_FAJR'),
        'sunrise' => env('SHALAT_TIME_ADJUSTMENT_IN_MINUTES_SUNRISE'),
        'dhuha' => env('SHALAT_TIME_ADJUSTMENT_IN_MINUTES_DHUHA'),
        'dzuhr' => env('SHALAT_TIME_ADJUSTMENT_IN_MINUTES_DZUHR'),
        'ashr' => env('SHALAT_TIME_ADJUSTMENT_IN_MINUTES_ASHR'),
        'maghrib' => env('SHALAT_TIME_ADJUSTMENT_IN_MINUTES_MAGHRIB'),
        'isya' => env('SHALAT_TIME_ADJUSTMENT_IN_MINUTES_ISYA'),
    ],

    'providers' => [
        'myquran_api' => [
            'name' => 'MyQuran API',
            'website_url' => 'https://api.myquran.com',
            'api_base_url' => 'https://api.myquran.com/v2',
            'city_name' => env('MYQURAN_CITY_NAME'),
            'service_class' => \App\Services\ShalatTimes\MyQuranShalatTimeService::class,
        ],
    ],
];
