# YeastarForLaravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/coverterror/yeastarforlaravel.svg?style=flat-square)](https://packagist.org/packages/coverterror/yeastarforlaravel)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/coverterror/yeastarforlaravel/run-tests.yml?label=tests&style=flat-square)](https://github.com/coverterror/yeastarforlaravel/actions?query=workflow%3Arun-tests)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/coverterror/yeastarforlaravel/Check%20&%20fix%20styling?label=code%20style&style=flat-square)](https://github.com/coverterror/yeastarforlaravel/actions?query=workflow%3A"Check+%26+fix+styling")
[![Total Downloads](https://img.shields.io/packagist/dt/coverterror/yeastarforlaravel.svg?style=flat-square)](https://packagist.org/packages/coverterror/yeastarforlaravel)

This package provides an integration layer between Laravel and Yeastar devices, making it easier to interact with Yeastar's functionalities directly from a Laravel application. Here's a simple example of making a phone call using the package:

```php
use Coverterror\YeastarForLaravel\Facades\Yeastar;

Yeastar::makeCall('100', '101');
```

## Installation

You can install the package via composer:

```bash
composer require coverterror/yeastarforlaravel
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="yeastarforlaravel-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="yeastarforlaravel-config"
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [coverterror](https://github.com/CovertError)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
