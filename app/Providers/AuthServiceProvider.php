<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Book' => 'App\Policies\BookPolicy',
        'App\Category' => 'App\Policies\CategoryPolicy',
        'App\Transaction' => 'App\Policies\TransactionPolicy',
        'App\Models\BankAccount' => 'App\Policies\BankAccountPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
