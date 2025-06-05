<?php

return [
    'theme' => env('JAMMASJID_THEME', 'default'),
    'prayer_start_in' => env('PRAYER_START_IN', 5), // Default 5 minutes
    'prayer_end_in' => env('PRAYER_END_IN', 10), // Default 10 minutes
    'friday_end_in' => env('FRIDAY_END_IN', 40), // Default 40 minutes
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
