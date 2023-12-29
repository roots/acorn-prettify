<?php

namespace Roots\AcornPrettify;

use Illuminate\Support\ServiceProvider;

class AcornPrettifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/prettify.php', 'prettify');

        $this->app->singleton('Roots\AcornPrettify', fn () => AcornPrettify::make($this->app));
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/prettify.php' => $this->app->configPath('prettify.php'),
        ], 'prettify-config');

        $this->app->make('Roots\AcornPrettify');
    }
}
