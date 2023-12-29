<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Clean Up
    |--------------------------------------------------------------------------
    |
    | Enabling this option will remove unnecessary <link>'s, inline CSS and JS
    | from WP emoji support, inline CSS used by the Recent Comments widget,
    | inline CSS used by posts with galleries, and self-closing tags.
    |
    */

    'clean-up' => [
        /**
         * Enable clean up.
         */
        'enabled' => true,

        /**
         * Obscure and suppress WordPress-related information.
         */
        'obscurity' => true,

        /**
         * Clean up HTML5 markup.
         */
        'clean-html5-markup' => true,

        /**
         * Disable WordPress emojis.
         */
        'disable-emojis' => true,

        /**
         * Disable Gutenberg block library CSS.
         */
        'disable-gutenberg-block-css' => true,

        /**
         * Disable extra RSS feeds.
         */
        'disable-extra-rss' => true,

        /**
         * Disable recent comments CSS.
         */
        'disable-recent-comments-css' => true,

        /**
         * Disable gallery CSS.
         */
        'disable-gallery-css' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Nice Search
    |--------------------------------------------------------------------------
    |
    | Enabling this option will redirect search results from /?s=query to
    | /search/query/ and convert `%20` to `+`.
    |
    */

    'nice-search' => [
        /**
         * Enable nice search.
         */
        'enabled' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Relative URLs
    |--------------------------------------------------------------------------
    |
    | Enabling this option will convert absolute URLs to relative URLs within
    | the specified hooks.
    |
    */

    'relative-urls' => [
        /**
         * Enable relative URLs.
         */
        'enabled' => false,

        /**
         * The hooks to filter.
         */
        'hooks' => [
            'bloginfo_url',
            'the_permalink',
            'wp_list_pages',
            'wp_list_categories',
            'wp_get_attachment_url',
            'the_content_more_link',
            'the_tags',
            'get_pagenum_link',
            'get_comment_link',
            'month_link',
            'day_link',
            'year_link',
            'term_link',
            'the_author_posts_link',
            'script_loader_src',
            'style_loader_src',
            'theme_file_uri',
            'parent_theme_file_uri',
        ],
    ],

];
