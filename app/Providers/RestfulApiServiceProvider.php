<?php

namespace App\Providers;

use App\RestAPI\َApiResponseBuilder;
use Illuminate\Support\ServiceProvider;

class RestfulApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('apiResponse', fn() => new َApiResponseBuilder());
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
