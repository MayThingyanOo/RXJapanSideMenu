<?php

namespace App\Providers;

use App\Models\Exhibition;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (config('app.debug') && request()->header('host') != "localhost") {
            if (env('DEBUGBAR')) {
                /**
                 * TODO: apply csp for debugbar.
                 * debugbar will check both constant value of DEBUGBAR and APP_DEBUG from env file,
                 * not just APP_DEBUG value.
                 */
                $this->app->registerCoreContainerAliases();
                AliasLoader::getInstance()->alias('Debugbar', 'Barryvdh\Debugbar\Facade');
            } else {
                app(\Illuminate\Contracts\Http\Kernel::class)->pushMiddleware(function ($request, $next) {
                    $response = $next($request);
                    $response->headers->set("content-security-policy", "default-src 'none';"
                        . " connect-src 'self' https://adobeid-na1.services.adobe.com https://www.e-uketsuke.jp;"
                        . " object-src 'self';"
                        . " font-src 'self';"
                        . " frame-ancestors 'none';"
                        . " img-src 'self' https://s3.ap-northeast-1.amazonaws.com;"
                        . " script-src 'self' https://static.adobelogin.com https://pt01.mul-pay.jp;"
                        . " style-src 'self' 'unsafe-inline' ");
                    return $response;
                });
            }

            $sqlNo = 0;
            \DB::listen(function ($query) use (&$sqlNo) {
                if (!\Request::is("api/*")) {
                    return;
                }
                if (!$sqlNo) {
                    \Log::info('FOR URL ' . \Request::url());
                }
                // $query->bindings
                $sqlNo++;
                \Log::info('sql.' . $sqlNo . ' ' . $query->sql . ' ' . $query->time);
            });
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \App\Lib\CpsBlade\Extension::extendBlade();

        \View::composer('*', function ($view) {
            \View::share('__view', (object) ['name' => $view->getname()]);
            $view->with('__self', (object) ['name' => $view->getname()]);
        });

        // Relation::morphMap([
        //     'exhibition' => Exhibition::class,
        // ]);
    }
}
