# Acorn Prettify

![Latest Stable Version](https://img.shields.io/packagist/v/roots/acorn-prettify.svg?style=flat-square)
![Total Downloads](https://img.shields.io/packagist/dt/roots/acorn-prettify.svg?style=flat-square)
![Build Status](https://img.shields.io/github/actions/workflow/status/roots/acorn-prettify/main.yml?branch=main&style=flat-square)

Acorn Prettify contains a collection of modules to apply theme-agnostic front-end modifications to your Acorn-powered WordPress sites.

## Requirements

- [PHP](https://secure.php.net/manual/en/install.php) >= 8.1
- [Acorn](https://github.com/roots/acorn) >= 3.0

## Installation

Install via Composer:

```sh
composer require roots/acorn-prettify
```

## Features

| Feature            | Enabled by default    | Description                               |
|--------------------|-----------------------|-------------------------------------------|
| **Clean Up**       | ✅ &nbsp; Enabled | Cleaner WordPress markup |
| **Nice Search**    | ✅ &nbsp; Enabled | Redirect search results from `/?s=query` to `/search/query/` |
| **Relative URLs**  | ❌ &nbsp; Disabled | Change WordPress absolute URLs to relative URLs |


[See the config file for the full list of features](https://github.com/roots/acorn-prettify/blob/main/config/prettify.php).

## Getting Started

Start by publishing the package configuration:

```sh
wp acorn vendor:publish --tag=prettify-config
```

If this says there are no publishable tags, you may not have initialized Acorn yet:

```sh
wp acorn acorn:init storage
```

Review the published config file to get an understanding of the optimizations that Acorn Prettify has enabled out of the box.

## Usage

Acorn Prettify immediately begins working with a sane set of defaults once installed in your Acorn project.

## Bug Reports

If you discover a bug in Acorn Prettify, please [open an issue](https://github.com/roots/acorn-prettify/issues).

## Contributing

Contributing whether it be through PRs, reporting an issue, or suggesting an idea is encouraged and appreciated.

## License

Acorn Prettify is provided under the [MIT License](LICENSE.md).
