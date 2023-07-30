<?php

use App\Models\Book;
use App\Models\Category;
use App\User;
use Faker\Generator as Faker;

$factory->define(Category::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->sentence,
        'color' => '#aabbcc',
        'status_id' => Category::STATUS_ACTIVE,
        'report_visibility_code' => Category::REPORT_VISIBILITY_PUBLIC,
        'creator_id' => function () {
            return factory(User::class)->create()->id;
        },
        'book_id' => function () {
            return factory(Book::class)->create()->id;
        },
    ];
});
