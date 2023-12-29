<?php

namespace Roots\AcornPrettify;

use Illuminate\Support\Collection;
use Roots\Acorn\Application;
use Roots\AcornPrettify\Modules\AbstractModule;

class AcornPrettify
{
    /**
     * The Application instance.
     */
    protected Application $app;

    /**
     * The package configuration.
     */
    protected Collection $config;

    /**
     * The Acorn Prettify modules.
     */
    protected array $modules = [
        Modules\CleanUpModule::class,
        Modules\NiceSearchModule::class,
        Modules\RelativeUrlsModule::class,
    ];

    /**
     * Create a new Acorn Prettify instance.
     *
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->config = collect(
            $this->app->config->get('prettify', [])
        )->map(fn ($value) => collect($value));

        add_filter('init', fn () => collect($this->modules)
            ->reject(fn ($module) => $module instanceof AbstractModule)
            ->each(fn ($module) => $module::make($this->app, $this->config))
        );
    }

    /**
     * Make a new instance of the Acorn Prettify.
     */
    public static function make(Application $app): self
    {
        return new static($app);
    }
}
