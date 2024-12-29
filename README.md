# Filament Error Pages

[![Latest Version on Packagist](https://img.shields.io/packagist/v/cmsmaxinc/filament-error-pages.svg?style=flat-square)](https://packagist.org/packages/cmsmaxinc/filament-error-pages)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/cmsmaxinc/filament-error-pages/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/cmsmaxinc/filament-error-pages/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/cmsmaxinc/filament-error-pages/fix-php-code-styling.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/cmsmaxinc/filament-error-pages/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/cmsmaxinc/filament-error-pages.svg?style=flat-square)](https://packagist.org/packages/cmsmaxinc/filament-error-pages)

This is where your description should go. Try and limit it to a paragraph or two.

## Installation

You can install the package via composer:

```bash
composer require cmsmaxinc/filament-error-pages
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-error-pages-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-error-pages-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-error-pages-views"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$filamentErrorPages = new Cmsmaxinc\FilamentErrorPages();
echo $filamentErrorPages->echoPhrase('Hello, Cmsmaxinc!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [CMS Max](https://github.com/cmsmaxinc)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
