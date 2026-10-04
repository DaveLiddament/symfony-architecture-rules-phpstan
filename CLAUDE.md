# Symfony Architecture Rules for PHPStan

A PHPStan extension (PHPStan v2, PHP 8.3–8.5) providing architecture rules for Symfony apps.
Modelled on [phpstan-php-language-extensions](https://github.com/DaveLiddament/phpstan-php-language-extensions).

- Layout: `attributes/` holds the role attributes (namespace `DaveLiddament\SymfonyArchitecture\Attribute`, kept
  separate so they can move to their own package without a namespace change); `src/Rules/Boundaries/` and
  `src/Rules/Roles/` hold the rules; `build/` holds internal-only PHPStan rules for this repo.
- Each rule group has config in `extension.neon` (`symfonyArchitecture.<group>`), wired via `conditionalTags`.
  Keep README concise and starting with the problem the package solves.
- Rules live in `src/Rules/` and MUST be registered in `extension.neon`, either tagged
  `phpstan.rules.rule` or via `conditionalTags` (for rules behind an `enabled` switch). This is enforced
  by the internal `build/PHPStan/Rules/CheckRuleIsInExtension.php` rule, which is only loaded by this
  project's `phpstan.neon` and never shipped.
- Every rule MUST have a `<RuleName>Test.php` under `tests/` with a non-empty `Fixtures/` directory next to
  it (several rules may share one). Enforced by the internal `build/PHPStan/Rules/CheckRuleHasTest.php`,
  which also checks the rules in `build/`.
- Every rule's config switches are tested by loading `extension.neon` into a PHPStan container
  (see `tests/Config/`).
- PHPStan is a phar, so new `PHPStan\*` / `PhpParser\*` symbols used in `src/` must be added to
  `composer-require-checker.json`.
- Rule tests use `dave-liddament/phpstan-rule-test-helper`; fixtures live in
  `tests/**/Fixtures/` and are excluded from analysis and code style.

## Docker

This project uses Docker for all PHP operations. There is one service per PHP version
(`php83`, `php84`, `php85`). Always use `docker compose run --rm <service>` to run PHP commands.

Examples:
- `docker compose run --rm php85 composer setup`
- `docker compose run --rm php85 composer ci`
- `docker compose run --rm php85 vendor/bin/phpunit`

`vendor/` is shared between services: run `composer update` when switching PHP version.
To rebuild after Dockerfile changes: `docker compose build`

Xdebug is enabled by default. To disable it, set `XDEBUG_MODE=off` in `docker-compose.yml`.
