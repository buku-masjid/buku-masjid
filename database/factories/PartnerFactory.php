<?php

use App\Models\Partner;
use Faker\Generator as Faker;

$factory->define(Partner::class, function (Faker $faker) {
    $genderCode = ['m', 'f'][array_rand(['m', 'f'])];

    return [
        'name' => $faker->name($genderCode == 'f' ? 'female' : 'male'),
        'type_code' => ['partner'],
        'gender_code' => $genderCode,
        'phone' => '08'.rand(1111111111, 9999999999),
        'pob' => $faker->city,
        'dob' => $faker->dateTimeBetween('1950-01-01', '2015-01-01'),
        'address' => $faker->address,
        'rt' => str_pad(rand(1, 10), 2, STR_PAD_LEFT),
        'rw' => str_pad(rand(1, 10), 2, STR_PAD_LEFT),
        'work_type_id' => array_rand(__('partner.work_types')),
        'marital_status_id' => array_rand(__('partner.marital_statuses')),
        'financial_status_id' => array_rand(__('partner.financial_statuses')),
        'activity_status_id' => array_rand(__('partner.activity_statuses')),
        'religion_id' => 1,
        'is_active' => Partner::STATUS_ACTIVE,
        'creator_id' => function () {
            return 1;
        },
    ];
});
