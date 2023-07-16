<?php

use App\Models\Book;
use App\User;
use Faker\Generator as Faker;

$factory->define(Book::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->sentence,
        'status_id' => Book::STATUS_ACTIVE,
        'creator_id' => function () {
            return factory(User::class)->create()->id;
        },
    ];
});
