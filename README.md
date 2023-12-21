# Acorn Pretty

![Latest Stable Version](https://img.shields.io/packagist/v/roots/acorn-pretty.svg?style=flat-square)
![Total Downloads](https://img.shields.io/packagist/dt/roots/acorn-pretty.svg?style=flat-square)
![Build Status](https://img.shields.io/github/actions/workflow/status/roots/acorn-pretty/main.yml?branch=main&style=flat-square)

Acorn Pretty contains a collection of modules to apply theme-agnostic front-end modifications to your Acorn-powered WordPress sites.

## Requirements

- [PHP](https://secure.php.net/manual/en/install.php) >= 8.1
- [Acorn](https://github.com/roots/acorn) >= 3.0

## Installation

Install via Composer:

```sh
$ composer require roots/acorn-pretty
```

## Getting Started

Start by publishing the package configuration:

```sh
$ php artisan vendor:publish --tag=acorn-pretty-config
```

Review the published config file to get an understanding of the optimizations that Acorn Pretty has enabled out of the box.

## Usage

Acorn Pretty immediately begins working with a sane set of defaults once installed in your Acorn project.

## Bug Reports

If you discover a bug in Acorn Pretty, please [open an issue](https://github.com/roots/acorn-pretty/issues).

## Contributing

Contributing whether it be through PRs, reporting an issue, or suggesting an idea is encouraged and appreciated.

## License

Acorn Pretty is provided under the [MIT License](LICENSE.md).
