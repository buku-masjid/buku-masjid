<?php

return [
    'partner_types' => env('PARTNER_TYPES'),
    'partner_levels' => env('PARTNER_LEVELS'),
    'income_default_value' => env('PARTNER_INCOME_DEFAULT_VALUE'),
    'spending_default_value' => env('PARTNER_SPENDING_DEFAULT_VALUE'),
    'age_groups' => [
        'old' => ['<=', 65],
        'mature' => [40, 65],
        'young' => [25, 40],
        'teenager' => [12, 25],
        'kids' => ['>=', 12],
    ],
];
