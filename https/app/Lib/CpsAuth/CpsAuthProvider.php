<?php

namespace App\Lib\CpsAuth;

use Illuminate\Support\ServiceProvider;

class CpsAuthProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('cpsauthfacade', function () {
            return new CpsAuth();
        });

        $this->app->booting(function () {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('CpsAuth', 'App\Lib\CpsAuth\CpsAuthFacade');
        });
    }
}
