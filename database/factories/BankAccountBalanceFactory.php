<?php

use App\Models\BankAccount;
use App\Models\BankAccountBalance;
use App\User;
use Faker\Generator as Faker;

$factory->define(BankAccountBalance::class, function (Faker $faker) {
    return [
        'date' => today(),
        'amount' => 1000001,
        'description' => $faker->sentence,
        'bank_account_id' => function () {
            return factory(BankAccount::class)->create()->id;
        },
        'creator_id' => function () {
            return factory(User::class)->create()->id;
        },
    ];
});
