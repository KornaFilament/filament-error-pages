# Filament Error Pages

This plugin provides a more user-friendly error page for Filament panels when an error occurs. Outside of the Filament panel, the default Laravel error page will be displayed.

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

## How does it work?
When an error occurs, the plugin will check if the request is coming from a Filament panel. If it is, the custom error page will be displayed. If it is not, the default Laravel error page will be displayed.

## Usage

Add the plugin to the panel where you want to use it. If you have multiple panels, ensure you add it to each one. If any panel is not set up correctly, a default Laravel error page will be displayed.

### Supported Pages
- 404

```php
->plugins([
    FilamentErrorPagesPlugin::make(),
])
```