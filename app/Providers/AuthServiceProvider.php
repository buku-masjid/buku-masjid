<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Loan' => 'App\Policies\LoanPolicy',
        'App\Partner' => 'App\Policies\PartnerPolicy',
        'App\Category' => 'App\Policies\CategoryPolicy',
        'App\Transaction' => 'App\Policies\TransactionPolicy',
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Register passport routes
        Passport::routes(function ($router) {
            $router->forAccessTokens();
        });
    }
}
