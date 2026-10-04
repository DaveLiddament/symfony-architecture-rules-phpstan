# Contributing

Contributions are welcome.

## PHP versions supported

This library supports PHP 8.3, 8.4 and 8.5. It MUST support all PHP versions that are in
[active or security](https://www.php.net/supported-versions.php) support.

## PHPStan versions supported

This library only supports PHPStan v2.

## Docker setup

All development is done through Docker. There is one service per supported PHP version:
`php83`, `php84` and `php85`.

### Install dependencies

`vendor/` is shared between all PHP versions, so install dependencies with the PHP
version you want to work in (e.g. 8.5):

```shell
docker compose run --rm php85 composer setup
```

If you switch PHP versions, run `composer update` in the new version first.

### Run composer scripts

```shell
docker compose run --rm php<version> composer <script>
```

For example, to fix code style on PHP 8.5:

```shell
docker compose run --rm php85 composer cs-fix
```

To run all checks on every supported PHP version:

```shell
for v in 83 84 85; do docker compose run --rm php$v sh -c 'composer update -n && composer ci' || break; done
```

See the `scripts` section of [composer.json](composer.json) for all available scripts.

### Shell access

```shell
docker compose run --rm php83 bash
```

### Xdebug

Xdebug is installed and `XDEBUG_MODE` is `debug` by default. To disable it, set
`XDEBUG_MODE=off` in `docker-compose.yml`.
