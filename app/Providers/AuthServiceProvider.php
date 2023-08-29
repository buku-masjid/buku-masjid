<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        'App\Models\LecturingSchedule' => 'App\Policies\LecturingSchedulePolicy',
        'App\Models\Book' => 'App\Policies\BookPolicy',
        'App\Models\Category' => 'App\Policies\CategoryPolicy',
        'App\Transaction' => 'App\Policies\TransactionPolicy',
        'App\Models\BankAccount' => 'App\Policies\BankAccountPolicy',
    ];

    public function boot()
    {
        //
    }
}
