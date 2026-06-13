<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ZoomService;

class ZoomServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('zoom.service', function ($app) {
            return new ZoomService();
        });
    }

    public function boot()
    {
        //
    }
}
