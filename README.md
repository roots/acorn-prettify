# Acorn Prettify

![Latest Stable Version](https://img.shields.io/packagist/v/roots/acorn-prettify.svg?style=flat-square)
[![Packagist Downloads](https://img.shields.io/packagist/dt/roots/acorn-prettify?label=downloads&colorB=2b3072&colorA=525ddc&style=flat-square)](https://packagist.org/packages/roots/acorn-prettify)
![Build Status](https://img.shields.io/github/actions/workflow/status/roots/acorn-prettify/main.yml?branch=main&style=flat-square)
[![Follow Roots](https://img.shields.io/badge/follow%20@rootswp-1da1f2?logo=twitter&logoColor=ffffff&message=&style=flat-square)](https://twitter.com/rootswp)
[![Sponsor Roots](https://img.shields.io/badge/sponsor%20roots-525ddc?logo=github&style=flat-square&logoColor=ffffff&message=)](https://github.com/sponsors/roots)

Acorn Prettify contains a collection of modules to apply theme-agnostic front-end modifications to your Acorn-powered WordPress sites.

## Support us

Roots is an independent open source org, supported only by developers like you. Your sponsorship funds [WP Packages](https://wp-packages.org/) and the entire Roots ecosystem, and keeps them independent. Support us by purchasing [Radicle](https://roots.io/radicle/) or [sponsoring us on GitHub](https://github.com/sponsors/roots) — sponsors get access to our private Discord.

## Requirements

- [PHP](https://secure.php.net/manual/en/install.php) >= 8.1
- [Acorn](https://github.com/roots/acorn) >= 3.0

## Installation

Install via Composer:

```sh
$ composer require roots/acorn-prettify
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
$ wp acorn vendor:publish --tag=prettify-config
```

Review the published config file to get an understanding of the optimizations that Acorn Prettify has enabled out of the box.

## Usage

Acorn Prettify immediately begins working with a sane set of defaults once installed in your Acorn project.

## Community

Keep track of development and community news.

- Join us on Discord by [sponsoring us on GitHub](https://github.com/sponsors/roots)
- Join us on [Roots Discourse](https://discourse.roots.io/)
- Follow [@rootswp on Twitter](https://twitter.com/rootswp)
- Follow the [Roots Blog](https://roots.io/blog/)
- Subscribe to the [Roots Newsletter](https://roots.io/subscribe/)
