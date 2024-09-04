<?php

use App\Models\Partner;
use App\User;
use Faker\Generator as Faker;

$factory->define(Partner::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'type_code' => 'partner',
        'gender_code' => 'm',
        'phone' => $faker->phoneNumber,
        'is_active' => Partner::STATUS_ACTIVE,
        'creator_id' => function () {
            return factory(User::class)->create()->id;
        },
    ];
});
