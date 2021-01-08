<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ChannelsFromCsv;

class ChannelsFromCsvServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind( ChannelsFromCsv::class, function ($app) {
            return new ChannelsFromCsv(config('services.youtube')['key']);
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
