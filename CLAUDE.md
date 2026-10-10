# Architecture Rules for PHPStan

A PHPStan extension (PHPStan v2, PHP 8.3–8.5) providing architecture rules for PHP apps, with Symfony and Doctrine support.
Modelled on [phpstan-php-language-extensions](https://github.com/DaveLiddament/phpstan-php-language-extensions).

- The role attributes live in their own package, `dave-liddament/architecture-rules-attributes`
  (namespace `DaveLiddament\Architecture\Attribute`). Its README explains what each role means; this
  README only covers what each rule checks and how to configure it.
- Layout: `src/Rules/Boundaries/`, `src/Rules/Roles/` and `src/Rules/Placement/` hold the rules; `build/` holds
  internal-only PHPStan rules for this repo.
- The placement rules are advice, not a gate: they live in `placement.neon`, which users include only when they want
  the placement report. Everything else is in `extension.neon`.
- Each rule group has config in `extension.neon` (`architecture.<group>`), wired via `conditionalTags`.
  Keep README concise and starting with the problem the package solves.
- Rules live in `src/Rules/` and MUST be registered in `extension.neon` (or `placement.neon`), either tagged
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
