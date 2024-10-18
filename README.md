# A vault where all Laravel reusable components reside.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/bipin/laravault.svg?style=flat-square)](https://packagist.org/packages/bipin/laravault)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/bipin/laravault/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/bipin/laravault/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/bipin/laravault/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/bipin/laravault/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/bipin/laravault.svg?style=flat-square)](https://packagist.org/packages/bipin/laravault)

WIP.


## Installation

You can install the package via composer:

```bash
composer require bipin/laravault
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laravault-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravault-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="laravault-views"
```

## Usage

```php
$laraVault = new Voidoflimbo\LaraVault();
echo $laraVault->echoPhrase('Hello, Voidoflimbo!');
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

- [Bipin Paneru](https://github.com/voidoflimbo)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
