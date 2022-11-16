<?php

namespace App\Lib\CpsMail;

use Illuminate\Support\ServiceProvider;

class CpsMailProvider extends ServiceProvider
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

        $this->app->singleton('cpsmailfacade', function () {
            return new CpsMail;
        });

        $this->app->booting(function () {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('CpsMail', 'App\Lib\CpsMail\CpsMailFacade');
        });
    }
}
