<?php

namespace MuhamedDidovic\Shortener;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Application as LaravelApplication;
use Laravel\Lumen\Application as LumenApplication;

class ShortenerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('shortener', function (Container $app) {
            return new Cats($app->config->get('cats.names', []));
        });
        $this->app->alias('shortener', Cats::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {


        $source = realpath($raw = __DIR__ . '/config/shortener.php') ?: $raw;
        //        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {

        $this->loadViewsFrom(__DIR__ . '/views', 'shortener');

        $this->publishes([
            $source => config_path('shortener.php'),
        ], 'shortener::config');

        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadMigrationsFrom(__DIR__ . '/migrations');


        $this->publishes([
            __DIR__ . '/views' => resource_path('views/vendor/shortener'),
        ], 'shortener::view');
        //            $this->publishes([
        //                __DIR__.'/path/to/config/courier.php' => config_path('courier.php'),
        //            ]);
        //        }
        //        elseif ($this->app instanceof LumenApplication) {
        //            $this->app->configure('shortener');
        //        }
        $this->mergeConfigFrom($source, 'shortener');
    }

}
