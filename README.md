# Zero configuration CSV exports for your Filament resources.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ryangjchandler/filament-easy-export.svg?style=flat-square)](https://packagist.org/packages/ryangjchandler/filament-easy-export)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/ryangjchandler/filament-easy-export/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/ryangjchandler/filament-easy-export/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/ryangjchandler/filament-easy-export/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/ryangjchandler/filament-easy-export/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/ryangjchandler/filament-easy-export.svg?style=flat-square)](https://packagist.org/packages/ryangjchandler/filament-easy-export)



This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require ryangjchandler/filament-easy-export
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-easy-export-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-easy-export-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-easy-export-views"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$easyExport = new RyanChandler\EasyExport();
echo $easyExport->echoPhrase('Hello, RyanChandler!');
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

- [Ryan Chandler](https://github.com/ryangjchandler)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
