<?php

namespace Roots\AcornPretty\Modules;

use Illuminate\Support\Str;

class RelativeUrlsModule extends AbstractModule
{
    /**
     * The module key.
     */
    protected string $key = 'relative-urls';

    /**
     * Determine if the module is enabled.
     */
    protected function enabled(): bool
    {
        return parent::enabled()
            && ! isset($_GET['sitemap'])
            && ! in_array($GLOBALS['pagenow'], ['wp-login.php', 'wp-register.php'], true);
    }

    /**
     * Handle the module.
     */
    public function handle(): void
    {
        if (! $this->enabled()) {
            return;
        }

        $this
            ->filters($this->urlFilters(), 'relativeUrl')
            ->filter('wp_calculate_image_srcset', 'imageSrcset');

        $this->handleCompatibility();
    }

    /**
     * Convert an absolute URL to a relative URL.
     */
    public function relativeUrl(string $url): string
    {
        if (is_feed()) {
            return $url;
        }

        if ($this->compareBaseUrl(network_home_url(), $url)) {
            return wp_make_link_relative($url);
        }

        return $url;
    }

    /**
     * Convert multiple URL sources to relative URLs.
     *
     * @param  string[]  $sources
     * @return string[]
     */
    public function imageSrcset(string|array $sources): string|array
    {
        if (! is_array($sources)) {
            return $sources;
        }

        return array_map(function ($source) {
            $source['url'] = $this->relativeUrl($source['url']);

            return $source;
        }, $sources);
    }

    /**
     * List of URL hooks to be filtered by this module
     *
     * @return string[]
     */
    protected function urlFilters(): array
    {
        return $this->config->get('hooks', []);
    }

    /**
     * Handle compatibility with third-party plugins.
     */
    protected function handleCompatibility(): self
    {
        return $this->handleSeoFramework();
    }

    /**
     * Handle The SEO Framework compatibility.
     */
    protected function handleSeoFramework(): self
    {
        add_filter('the_seo_framework_do_before_output', fn () => $this->removeFilter('wp_get_attachment_url', 'relativeUrl'));
        add_filter('the_seo_framework_do_after_output', fn () => $this->filter('wp_get_attachment_url', 'relativeUrl'));

        return $this;
    }

    /**
     * Determine if two URLs contain the same base URL.
     */
    protected function compareBaseUrl(string $baseUrl, string $inputUrl, bool $strict = true): bool
    {
        $baseUrl = trailingslashit($baseUrl);
        $inputUrl = trailingslashit($inputUrl);

        if ($baseUrl === $inputUrl) {
            return true;
        }

        $inputUrl = wp_parse_url($inputUrl);

        if (! isset($inputUrl['host'])) {
            return true;
        }

        $baseUrl = wp_parse_url($baseUrl);

        if (! isset($baseUrl['host'])) {
            return false;
        }

        if (! $strict || ! isset($inputUrl['scheme']) || ! isset($baseUrl['scheme'])) {
            $inputUrl['scheme'] = $baseUrl['scheme'] = 'soil';
        }

        if (
            ! Str::is($baseUrl['scheme'], $inputUrl['scheme']) ||
            ! Str::is($baseUrl['host'], $inputUrl['host'])
        ) {
            return false;
        }

        if (isset($baseUrl['port']) || isset($inputUrl['port'])) {
            return isset($baseUrl['port'], $inputUrl['port']) && Str::is($baseUrl['port'], $inputUrl['port']);
        }

        return true;
    }
}
