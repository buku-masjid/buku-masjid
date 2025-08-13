<?php

return [
    'lecturings' => [
        'is_active' => env('FEATURES_LECTURINGS_IS_ACTIVE', true),
    ],
    'donors' => [
        'is_active' => env('FEATURES_DONORS_IS_ACTIVE', false),
    ],
    'shalat_time' => [
        'is_active' => env('FEATURES_SHALAT_TIME_IS_ACTIVE', false),
    ],
    'public_display' => [
        'is_active' => env('FEATURES_PUBLIC_DISPLAY_IS_ACTIVE', false),
    ],
];
