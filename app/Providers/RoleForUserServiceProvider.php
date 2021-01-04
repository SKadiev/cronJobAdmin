<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\RoleByUser;

class RoleForUserServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind( RoleByUser::class, function ($app) {
            return new RoleByUser;
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
