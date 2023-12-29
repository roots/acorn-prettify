<?php

namespace Roots\AcornPrettify\Modules;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Roots\Acorn\Application;
use Roots\AcornPrettify\Contracts\Module;

abstract class AbstractModule implements Module
{
    /**
     * The Application instance.
     */
    protected Application $app;

    /**
     * The module config.
     */
    protected Collection $config;

    /**
     * Initialize the Module instance.
     */
    public function __construct(Application $app, Collection $config)
    {
        $this->app = $app;
        $this->config = $config->get($this->getKey());

        $this->boot();
    }

    /**
     * Make a new instance of the module.
     */
    public static function make(Application $app, Collection $config): self
    {
        return new static($app, $config);
    }

    /**
     * Boot the module.
     */
    protected function boot(): void
    {
        if (empty($this->config)) {
            return;
        }

        $method = method_exists($this, 'handle') ? 'handle' : '__invoke';

        $this->app->call([$this, $method]);
    }

    /**
     * Determine if the module is enabled.
     */
    protected function enabled(): bool
    {
        return $this->config->get('enabled', false) &&
            (! is_admin() || wp_doing_ajax());
    }

    /**
     * Get the module key.
     */
    protected function getKey(): string
    {
        $class = get_class($this);

        return Str::of($class)
            ->afterLast('\\')
            ->beforeLast('Module')
            ->snake('-');
    }

    /**
     * Filter a hook handled by a method.
     */
    protected function filter(string $hook, string $method, int $priority = 10, int $args = 1): self
    {
        add_filter($hook, [$this, $method], $priority, $args);

        return $this;
    }

    /**
     * Remove a hook using a method.
     */
    protected function removeFilter(string $hook, string $method, int $priority = 10): self
    {
        remove_filter($hook, [$this, $method], $priority);

        return $this;
    }

    /**
     * Filter multiple hooks by a method.
     */
    protected function filters(array $hooks, string $method, int $priority = 10, int $args = 1): self
    {
        $count = count($hooks);

        array_map(
            [$this, 'filter'],
            (array) $hooks,
            array_fill(0, $count, $method),
            array_fill(0, $count, $priority),
            array_fill(0, $count, $args)
        );

        return $this;
    }

    /**
     * Remove multiple hooks using a method.
     */
    protected function removeFilters(array $hooks, string $method, int $priority = 10): self
    {
        $count = count($hooks);

        array_map(
            [$this, 'removeFilter'],
            (array) $hooks,
            array_fill(0, $count, $method),
            array_fill(0, $count, $priority)
        );

        return $this;
    }
}
