<?php

use App\Models\BankAccount;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(BankAccount::class, function (Faker $faker) {
    return [
        'name' => 'Bank '.strtoupper(Str::random(4)),
        'number' => Str::random(10),
        'account_name' => $faker->name,
        'is_active' => BankAccount::STATUS_ACTIVE,
        'creator_id' => function () {
            return factory(User::class)->create()->id;
        },
    ];
});
