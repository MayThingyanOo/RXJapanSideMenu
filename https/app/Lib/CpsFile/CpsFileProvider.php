<?php

namespace App\Lib\CpsFile;

use Illuminate\Support\ServiceProvider;

class CpsFileProvider extends ServiceProvider
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

        $this->app->singleton('cpsfilefacade', function () {
            return new CpsFile;
        });

        $this->app->booting(function () {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('CpsFile', 'App\Lib\CpsFile\CpsFileFacade');
        });
    }
}
