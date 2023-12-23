<?php

namespace App\Providers;

use Illuminate\Database\Events\MigrationsEnded;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Event::listen(function (MigrationsEnded $event) {
            $oldMigrationLog = DB::table('migrations')
                ->where('migration', '2023_08_25_103840_create_lecturing_schedules_table')
                ->first();

            if (is_null($oldMigrationLog)) {
                return;
            }

            DB::table('migrations')->where('migration', '2023_08_25_103840_create_lecturings_table')->delete();
            DB::table('migrations')->where('migration', '2023_08_25_103840_create_lecturing_schedules_table')
                ->update(['migration' => '2023_08_25_103840_create_lecturings_table']);
        });
    }
}
