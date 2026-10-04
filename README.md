# Symfony Architecture Rules for PHPStan

[![PHP versions: 8.3 to 8.5](https://img.shields.io/badge/php-8.3|8.4|8.5-blue.svg)](https://packagist.org/packages/dave-liddament/symfony-architecture-rules-phpstan)
[![PHPStan max level](https://img.shields.io/badge/PHPStan-max%20level-brightgreen.svg)](phpstan.neon)

**Work in progress:** no rules have been added yet.

## Installation

```shell
composer require --dev dave-liddament/symfony-architecture-rules-phpstan
```

If you use [phpstan/extension-installer](https://github.com/phpstan/extension-installer) you're ready to go. Otherwise,
include the extension in your `phpstan.neon`:

```neon
includes:
    - vendor/dave-liddament/symfony-architecture-rules-phpstan/extension.neon
```

## Contributing

See [Contributing](CONTRIBUTING.md).
