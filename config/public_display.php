<?php

return [
    'theme' => env('PUBLIC_DISPLAY_THEME', 'default'),
    'available_themes' => [
        'default',
        'light',
    ],
    'iqamah_interval_in_minutes' => [
        'fajr' => env('PUBLIC_DISPLAY_IQAMAH_INTERVAL_IN_MINUTES_FAJR', 25),
        'dzuhr' => env('PUBLIC_DISPLAY_IQAMAH_INTERVAL_IN_MINUTES_DZUHR', 15),
        'ashr' => env('PUBLIC_DISPLAY_IQAMAH_INTERVAL_IN_MINUTES_ASHR', 15),
        'maghrib' => env('PUBLIC_DISPLAY_IQAMAH_INTERVAL_IN_MINUTES_MAGHRIB', 15),
        'isya' => env('PUBLIC_DISPLAY_IQAMAH_INTERVAL_IN_MINUTES_ISYA', 15),
    ],
    'shalat_interval_in_minutes' => [
        'fajr' => env('PUBLIC_DISPLAY_IQAMAH_INTERVAL_IN_MINUTES_FAJR', 15),
        'dzuhr' => env('PUBLIC_DISPLAY_IQAMAH_INTERVAL_IN_MINUTES_DZUHR', 15),
        'ashr' => env('PUBLIC_DISPLAY_IQAMAH_INTERVAL_IN_MINUTES_ASHR', 15),
        'maghrib' => env('PUBLIC_DISPLAY_IQAMAH_INTERVAL_IN_MINUTES_MAGHRIB', 15),
        'isya' => env('PUBLIC_DISPLAY_IQAMAH_INTERVAL_IN_MINUTES_ISYA', 15),
        'friday' => env('PUBLIC_DISPLAY_IQAMAH_INTERVAL_IN_MINUTES_FRIDAY', 40),
    ],
    'sharing_info' => [
        [
            'quote' => 'Sesungguhnya, hanya orang-orang yang bersabarlah yang dicukupkan pahala mereka tanpa batas.',
            'source' => '(QS. Az-Zumar : 10)',
        ],
        [
            'quote' => 'Barangsiapa yang memudahkan urusan orang lain, Allah akan memudahkan urusannya di dunia dan akhirat.',
            'source' => '(HR. Muslim)',
        ],
        // Add more quotes as needed
    ],
];
