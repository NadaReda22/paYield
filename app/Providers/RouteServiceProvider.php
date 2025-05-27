<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /*
    //Centralized Configuration//
By defining the home route in the RouteServiceProvider, you centralize the configuration for where users should be redirected after authentication.

This makes it easy to change the redirect location in one place without modifying multiple controllers.
*/
    public const HOME='/';
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
