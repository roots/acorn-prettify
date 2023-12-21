<?php

namespace Roots\AcornPretty;

use Illuminate\Support\ServiceProvider;

class AcornPrettyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/pretty.php', 'pretty');

        $this->app->singleton('Roots\AcornPretty', function () {
            return new AcornPretty($this->app);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/pretty.php' => $this->app->configPath('pretty.php'),
        ], 'acorn-pretty-config');

        $this->app->make('Roots\AcornPretty');
    }
}
