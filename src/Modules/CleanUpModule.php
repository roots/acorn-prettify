<?php

namespace Roots\AcornPretty\Modules;

use Illuminate\Support\Str;
use Roots\AcornPretty\Contracts\Module;
use Roots\AcornPretty\Document;

class CleanUpModule extends AbstractModule implements Module
{
    /**
     * The module key.
     */
    protected string $key = 'clean-up';

    /**
     * Handle the module.
     */
    public function handle(): void
    {
        if (! $this->enabled()) {
            return;
        }

        $this
            ->handleObscurity()
            ->handleCleanHtmlMarkup()
            ->handleDisableEmojis()
            ->handleDisableGutenbergBlockCss()
            ->handleDisableExtraRss()
            ->handleDisableRecentCommentsCss()
            ->handleDisableGalleryCss();
    }

    /**
     * Obscure and suppress WordPress information.
     */
    protected function handleObscurity(): self
    {
        if (! $this->config->get('obscurity')) {
            return $this;
        }

        foreach ([
            'rsd_link',
            'wlwmanifest_link',
            'wp_generator',
            'wp_oembed_add_discovery_links',
            'wp_oembed_add_host_js',
        ] as $hook) {
            remove_filter('wp_head', $hook);
        }

        foreach ([
            'adjacent_posts_rel_link_wp_head',
            'wp_shortlink_wp_head',
            'rest_output_link_wp_head',
        ] as $hook) {
            remove_filter('wp_head', $hook, 20);
        }

        add_filter('get_bloginfo_rss', fn ($value) => ! Str::is($value, __('Just another WordPress site')) ? $value : '');
        add_filter('the_generator', '__return_false');

        return $this;
    }

    /**
     * Clean HTML5 markup.
     */
    protected function handleCleanHtmlMarkup(): self
    {
        if (! $this->config->get('clean-html5-markup')) {
            return $this;
        }

        $this
            ->filter('body_class', 'bodyClass')
            ->filter('language_attributes', 'languageAttributes')
            ->filter('style_loader_tag', 'cleanStylesheetLinks')
            ->filter('script_loader_tag', 'cleanScriptTags')
            ->filters([
                'get_avatar',
                'comment_id_fields',
                'post_thumbnail_html',
            ], 'removeSelfClosingTags');

        add_filter('site_icon_meta_tags', fn ($tags) => array_map([$this, 'removeSelfClosingTags'], $tags), 20);

        return $this;
    }

    /**
     * Disable WordPress emojis.
     */
    protected function handleDisableEmojis(): self
    {
        if (! $this->config->get('disable-emojis')) {
            return $this;
        }

        add_filter('emoji_svg_url', '__return_false');
        remove_filter('wp_head', 'print_emoji_detection_script', 7);

        foreach ([
            'admin_print_scripts' => 'print_emoji_detection_script',
            'wp_print_styles' => 'print_emoji_styles',
            'admin_print_styles' => 'print_emoji_styles',
            'the_content_feed' => 'wp_staticize_emoji',
            'comment_text_rss' => 'wp_staticize_emoji',
            'wp_mail' => 'wp_staticize_emoji_for_email',
        ] as $hook => $function) {
            remove_filter($hook, $function);
        }

        return $this;
    }

    /**
     * Disable Gutenberg block library CSS.
     */
    protected function handleDisableGutenbergBlockCss(): self
    {
        if (! $this->config->get('disable-gutenberg-block-css')) {
            return $this;
        }

        add_filter('wp_enqueue_scripts', fn () => wp_dequeue_style('wp-block-library'), 200);

        return $this;
    }

    /**
     * Disable extra RSS feeds.
     */
    protected function handleDisableExtraRss(): self
    {
        if (! $this->config->get('disable-extra-rss')) {
            return $this;
        }

        add_filter('feed_links_show_comments_feed', '__return_false');
        remove_filter('wp_head', 'feed_links_extra', 3);

        return $this;
    }

    /**
     * Disable recent comments CSS.
     *
     * @return self
     */
    protected function handleDisableRecentCommentsCss()
    {
        if (! $this->config->get('disable-recent-comments-css')) {
            return $this;
        }

        add_filter('show_recent_comments_widget_style', '__return_false');

        return $this;
    }

    /**
     * Disable gallery CSS.
     */
    protected function handleDisableGalleryCss(): self
    {
        if (! $this->config->get('disable-gallery-css')) {
            return $this;
        }

        add_filter('use_default_gallery_style', '__return_false');

        return $this;
    }

    /**
     * Clean up output of stylesheet <link> tags.
     */
    public function cleanStylesheetLinks(string $html): string
    {
        return Document::make($html)->each(static function ($link) {
            $link->removeAttribute('type');
            $link->removeAttribute('id');

            if (($media = $link->getAttribute('media')) && $media !== 'all') {
                return;
            }

            $link->removeAttribute('media');
        })->html();
    }

    /**
     * Clean up the output of <script> tags.
     */
    public function cleanScriptTags(string $html): string
    {
        return Document::make($html)->each(static function ($script) {
            $script->removeAttribute('type');
            $script->removeAttribute('id');
        })->html();
    }

    /**
     * Add and remove body_class() classes.
     *
     * @param  array  $classes
     * @param  array  $disallowedClasses
     * @return array
     */
    public function bodyClass($classes, $disallowedClasses = ['page-template-default'])
    {
        if (is_single() || is_page() && ! is_front_page()) {
            if (! in_array($slug = basename(get_permalink()), $classes, true)) {
                $classes[] = $slug;
            }
        }

        if (is_front_page()) {
            $disallowedClasses[] = 'page-id-'.get_option('page_on_front');
        }

        return collect($classes)
            ->diff($disallowedClasses)
            ->values()
            ->all();
    }

    /**
     * Clean up language_attributes() used in <html> tag.
     *
     * @return void
     */
    public function languageAttributes()
    {
        $attributes = [];

        if (is_rtl()) {
            $attributes[] = 'dir="rtl"';
        }

        $lang = esc_attr(get_bloginfo('language'));

        if ($lang) {
            $attributes[] = "lang=\"{$lang}\"";
        }

        return implode(' ', $attributes);
    }

    /**
     * Remove self-closing tags.
     *
     * @param  string|string[]  $html
     * @return string|string[]
     */
    public function removeSelfClosingTags($html)
    {
        return str_replace(' />', '>', $html);
    }
}
