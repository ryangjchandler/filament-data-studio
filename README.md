# Zero configuration CSV exports for your Filament resources.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ryangjchandler/filament-easy-export.svg?style=flat-square)](https://packagist.org/packages/ryangjchandler/filament-easy-export)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/ryangjchandler/filament-easy-export/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/ryangjchandler/filament-easy-export/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/ryangjchandler/filament-easy-export/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/ryangjchandler/filament-easy-export/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/ryangjchandler/filament-easy-export.svg?style=flat-square)](https://packagist.org/packages/ryangjchandler/filament-easy-export)

Data Studio provides a zero-configuration export flow for your users. Add a single action to your `Resource` table and get a powerful export configurator ready in seconds.

## Installation

You can install the package via Composer:

```bash
composer require ryangjchandler/filament-data-studio
```

Once the package is installed, us the `filament-data-studio:install` command to publish and run migrations.

```php
php artisan filament-data-studio:install
```

## Usage

Watch [the introduction video]() to learn how Data Studio works.

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
