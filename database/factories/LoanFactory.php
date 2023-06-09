<?php

use App\Loan;
use App\Partner;
use App\User;
use Faker\Generator as Faker;

$factory->define(Loan::class, function (Faker $faker) {
    return [
        'partner_id' => function () {
            return factory(Partner::class)->create()->id;
        },
        'type_id' => Loan::TYPE_DEBT,
        'amount' => 2000,
        'description' => $faker->sentence,
        'creator_id' => function () {
            return factory(User::class)->create()->id;
        },
    ];
});
