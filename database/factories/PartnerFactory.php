<?php

use App\Models\Partner;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Partner::class, function (Faker $faker) {
    return [
        'name' => 'Bank '.strtoupper(Str::random(4)),
        'phone' => Str::random(10),
        'name' => $faker->name,
        'is_active' => Partner::STATUS_ACTIVE,
        'creator_id' => function () {
            return factory(User::class)->create()->id;
        },
    ];
});
