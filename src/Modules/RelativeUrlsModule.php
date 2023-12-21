<?php

namespace Roots\AcornPretty\Modules;

use Illuminate\Support\Str;
use Roots\AcornPretty\Contracts\Module;

class RelativeUrlsModule extends AbstractModule implements Module
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
     *
     * @param  string  $url
     * @return string
     */
    public function relativeUrl($url)
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
    public function imageSrcset($sources)
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
     * Add compatibility with third-party plugins.
     *
     * @return void
     */
    protected function handleCompatibility(): self
    {
        return $this->handleSeoFramework();
    }

    /**
     * Add The SEO Framework compatibility.
     *
     * @return void
     */
    protected function handleSeoFramework(): self
    {
        add_filter('the_seo_framework_do_before_output', fn () => $this->removeFilter('wp_get_attachment_url', 'relativeUrl'));
        add_filter('the_seo_framework_do_after_output', fn () => $this->filter('wp_get_attachment_url', 'relativeUrl'));

        return $this;
    }

    /**
     * Determine if two URLs contain the same base URL.
     *
     * @param  string  $baseUrl
     * @param  string  $inputUrl
     * @param  bool  $strict
     * @return bool
     */
    protected function compareBaseUrl($baseUrl, $inputUrl, $strict = true)
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
