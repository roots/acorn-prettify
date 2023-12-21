<?php

namespace Roots\AcornPretty;

use Illuminate\Support\Collection;
use Roots\Acorn\Application;
use Roots\AcornPretty\Modules\AbstractModule;
use Roots\AcornPretty\Modules\CleanUpModule;
use Roots\AcornPretty\Modules\NiceSearchModule;
use Roots\AcornPretty\Modules\RelativeUrlsModule;

class AcornPretty
{
    /**
     * The Application instance.
     */
    protected Application $app;

    /**
     * The package configuration.
     *
     * @var array
     */
    protected Collection $config;

    /**
     * The Acorn Pretty modules.
     */
    protected array $modules = [
        CleanUpModule::class,
        NiceSearchModule::class,
        RelativeUrlsModule::class,
    ];

    /**
     * Create a new Acorn Pretty instance.
     *
     * @param  array  $config
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->config = collect(
            $this->app->config->get('pretty')
        )->map(fn ($value) => is_array($value) ? collect($value) : $value);

        add_filter('init', fn () => collect($this->modules)
            ->reject(fn ($module) => $module instanceof AbstractModule)
            ->each(fn ($module) => new $module($this->app, $this->config))
        );
    }
}
