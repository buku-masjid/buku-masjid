<?php

namespace App\Providers;

use App\Services\ShalatTimes\ShalatTimeService;
use Exception;
use Illuminate\Support\ServiceProvider;

class ShalatTimeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $defaultProvider = config('shalat_time.default_provider');

        if (empty($defaultProvider)) {
            $this->app->bind(ShalatTimeService::class, function () {
                return null;
            });

            return;
        }

        $serviceClass = config("shalat_time.providers.{$defaultProvider}.service_class");

        if (empty($serviceClass)) {
            throw new Exception("Invalid Shalat Time Provider: {$defaultProvider}");
        }

        $this->app->bind(ShalatTimeService::class, $serviceClass);
    }
}
