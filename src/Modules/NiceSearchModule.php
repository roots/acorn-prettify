<?php

namespace Roots\AcornPretty\Modules;

class NiceSearchModule extends AbstractModule
{
    /**
     * The default search endpoint.
     */
    protected string $defaultEndpoint = '/?s=';

    /**
     * The pretty search endpoint.
     */
    protected string $prettyEndpoint = '/search/';

    /**
     * Handle the module.
     */
    public function handle(): void
    {
        if (! $this->enabled()) {
            return;
        }

        $this
            ->handleRedirect()
            ->handleCompatibility();
    }

    /**
     * Redirect query string search results to the pretty URL.
     */
    protected function handleRedirect(): self
    {
        add_filter('template_redirect', function () {
            global $wp_rewrite;

            if (
                ! isset($_SERVER['REQUEST_URI']) ||
                ! isset($wp_rewrite) ||
                ! is_object($wp_rewrite) ||
                ! $wp_rewrite->get_search_permastruct()
            ) {
                return;
            }

            $request = wp_unslash(filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL));

            if (
                is_search() &&
                ! str_contains($request, "/{$wp_rewrite->search_base}/") &&
                ! str_contains($request, '&')
            ) {
                if (wp_safe_redirect(get_search_link())) {
                    exit;
                }
            }
        });

        return $this;
    }

    /**
     * Handle compatibility with third-party plugins.
     */
    protected function handleCompatibility(): self
    {
        return $this->handleYoastSeo();
    }

    /**
     * Handle Yoast SEO compatibility.
     */
    protected function handleYoastSeo(): self
    {
        $this->filter('wpseo_json_ld_search_url', 'rewriteUrl');

        return $this;
    }

    /**
     * Rewrite the search query string to a pretty URL.
     */
    public function rewriteUrl(string $url): string
    {
        return str_replace(
            $this->defaultEndpoint,
            $this->prettyEndpoint,
            $url
        );
    }
}
