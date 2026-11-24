<?php

namespace Roots\AcornPrettify\Modules;

class DisableRandomContentModule extends AbstractModule
{
    /**
     * Determine if the module is enabled.
     *
     * Unlike other modules, this also runs in the admin so that rewrite rules
     * flushed there (e.g. when saving permalinks or after a core update) do
     * not include the random content rules.
     */
    protected function enabled(): bool
    {
        return (bool) $this->config->get('enabled', false);
    }

    /**
     * Handle the module.
     */
    public function handle(): void
    {
        if (! $this->enabled()) {
            return;
        }

        add_filter('wp_enable_random_content_redirect', '__return_false');
    }
}
