<?php

use App\Category;
use App\User;
use Faker\Generator as Faker;

$factory->define(Category::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->sentence,
        'color' => '#aabbcc',
        'status_id' => Category::STATUS_ACTIVE,
        'creator_id' => function () {
            return factory(User::class)->create()->id;
        },
    ];
});
