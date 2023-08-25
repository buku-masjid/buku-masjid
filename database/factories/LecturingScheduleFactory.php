<?php

use App\Models\LecturingSchedule;
use App\User;
use Faker\Generator as Faker;

$factory->define(LecturingSchedule::class, function (Faker $faker) {
    return [
        'title' => $this->faker->word,
        'description' => $this->faker->sentence,
        'creator_id' => function () {
            return factory(User::class)->create()->id;
        },
    ];
});
