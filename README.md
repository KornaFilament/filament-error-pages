# Filament Error Pages

[![Latest Version on Packagist](https://img.shields.io/packagist/v/cmsmaxinc/filament-error-pages.svg?style=flat-square)](https://packagist.org/packages/cmsmaxinc/filament-error-pages)
[![Total Downloads](https://img.shields.io/packagist/dt/cmsmaxinc/filament-error-pages.svg?style=flat-square)](https://packagist.org/packages/cmsmaxinc/filament-error-pages)
[![License](https://img.shields.io/packagist/l/cmsmaxinc/filament-error-pages.svg?style=flat-square)](https://packagist.org/packages/cmsmaxinc/filament-error-pages)

This plugin provides a more user-friendly error page for Filament panels when an error occurs. Outside the Filament panel, the default Laravel error page will be displayed.

![thumbnail.png](art/thumbnail.png)

## Installation

You can install the package via composer:

```bash
composer require cmsmaxinc/filament-error-pages
```

### Custom Theme

You will need to [create a custom theme](https://filamentphp.com/docs/3.x/panels/themes#creating-a-custom-theme) for the styles to be applied correctly.


Make sure you add the following to your `tailwind.config.js file.

```bash
'./vendor/cmsmaxinc/filament-error-pages/resources/**/*.blade.php',
```

## Translations
If you want to customize the translations, you can publish the translations file.

```bash
php artisan vendor:publish --tag="filament-error-pages-translations"
```

## How does it work?
When an error occurs, the plugin will check if the request is coming from a Filament panel. If it is, the custom error page will be displayed. If it is not, the default Laravel error page will be displayed.

#### Are pages outside the panel covered?
The error pages are part of the Filament panel, and the plugin is designed to work within the panel. The plugin will not cover pages outside the panel. For example if your panel base URL is `/admin`, the plugin will cover `/admin/*` but not anything outside of `/admin`.

## What pages are covered?
The plugin will cover the following error pages:
- 404 (Page not found)
- 403 (Forbidden)

## Usage

Add the plugin to the panel where you want to use it. If you have multiple panels, ensure you add it to each one. If any panel is not set up correctly, a default Laravel error page will be displayed.

```php
->plugins([
    FilamentErrorPagesPlugin::make(),
])
```

### Route Configuration

In some cases, especially when your panel doesn't have a clear prefix in the URL (like when it's at the root `/`), the plugin might have trouble detecting which panel should handle the error. In these cases, you can explicitly configure which URL patterns should be handled by each panel:

```php
->plugins([
    FilamentErrorPagesPlugin::make()
        ->routes([
            'admin/*',    // Will match any path starting with admin/
            'dashboard/*', // Will match any path starting with dashboard/
            '/',          // Will match the root path
            'api/*',      // Will match any path starting with api/
        ]),
])
```

This is particularly useful when:
- Your panel is mounted at the root URL (`/`)
- You have multiple panels with overlapping URL patterns
- You want to ensure specific URL patterns are always handled by a particular panel
- You have custom routes that don't follow the standard panel prefix pattern

The route patterns support the `*` wildcard which matches any characters.

For example:
- `admin/*` will match `admin/dashboard`, `admin/users`, etc.
- `api/*` will match `api/v1`, `api/v2`, etc.
- `/` will match the root path
