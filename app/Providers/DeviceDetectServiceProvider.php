<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\DeviceDetect;
use  App\Facade\DeviceDetectFacade;
use App\Models\Device;

class DeviceDetectServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind( DeviceDetect::class, function ($app) {
            return new DeviceDetect;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
