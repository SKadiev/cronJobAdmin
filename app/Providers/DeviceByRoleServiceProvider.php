<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\DeviceByUser;

class DeviceByRoleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind( DeviceByUser::class, function ($app) {
            return new DeviceByUser;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        
    }
}
