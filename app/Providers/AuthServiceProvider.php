<?php

namespace App\Providers;

use App\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        'App\Models\Lecturing' => 'App\Policies\LecturingPolicy',
        'App\Models\Book' => 'App\Policies\BookPolicy',
        'App\Models\Category' => 'App\Policies\CategoryPolicy',
        'App\Transaction' => 'App\Policies\TransactionPolicy',
        'App\Models\BankAccount' => 'App\Policies\BankAccountPolicy',
    ];

    public function boot()
    {
        Gate::define('manage_database_backup', function (User $user) {
            return in_array($user->role_id, [User::ROLE_ADMIN]);
        });
        Gate::define('edit_masjid_profile', function (User $user) {
            return in_array($user->role_id, [User::ROLE_ADMIN]);
        });
    }
}
