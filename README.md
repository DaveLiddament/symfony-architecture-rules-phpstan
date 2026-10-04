# Symfony Architecture Rules for PHPStan

[![PHP versions: 8.3 to 8.5](https://img.shields.io/badge/php-8.3|8.4|8.5-blue.svg)](https://packagist.org/packages/dave-liddament/symfony-architecture-rules-phpstan)
[![PHPStan max level](https://img.shields.io/badge/PHPStan-max%20level-brightgreen.svg)](phpstan.neon)

## The problem

Symfony doesn't tell you how to structure your application, so every team invents its own rules: "features don't
reach into each other's internals", "shared code doesn't depend on features", "services are stateless". Those rules
live in people's heads, in a wiki page, or in code review comments. As the app grows, they erode one shortcut at a
time, until nobody can say where anything belongs.

This [PHPStan](https://phpstan.org) extension turns those rules into errors. You get a sensible architecture out of
the box, every rule is configurable, and any part can be switched off.

It checks two things:

- **[Architectural boundaries](#architectural-boundaries)**: which parts of the app may depend on which.
- **[Role contracts](#role-contracts)**: mark a class with its role, e.g. `#[Service]`, and its declaration is checked.

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

Your application code is assumed to live in `App\`. If it doesn't, set the namespace in your `phpstan.neon`:

```neon
parameters:
    symfonyArchitecture:
        appNamespace: 'Acme'
```

## Architectural boundaries

Code is split into four kinds of area:

| Area | Default namespace | What lives there |
|---|---|---|
| **Lib** | `Lib\` | Code you could take to another project. Any domain word disqualifies it. |
| **Shared** | `App\Shared\` | Application code used throughout the app, e.g. a `User` entity. Permanent. |
| **Nursery** | `App\Nursery\` | New code whose domain isn't clear yet. It moves into a domain once its home is obvious. |
| **Domain** | `App\<Name>\` | Every other namespace directly under `App\`, e.g. `App\Registration\`. |

A domain's root classes (`App\Registration\*`) are its public API. Everything in its subdirectories
(`App\Registration\Entity\*`) is internal to it. Classes directly in `App\` (e.g. `App\Kernel`) are framework glue
and are not checked, nor is `App\Tests\`.

| From ↓ / To → | Lib | Shared | Domain (public) | Domain (internal) | Nursery |
|---|---|---|---|---|---|
| **Lib** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **Shared** | ✅ | ✅ | ❌ | ❌ | ❌ |
| **Domain** | ✅ | ✅ | ✅ | own domain only | ❌ |
| **Nursery** | ✅ | ✅ | ✅ | ❌ | ✅ |

Dependencies flow one way: domains → Shared → Lib. Nothing may depend on the Nursery. When a domain needs a nursery
class, that's the signal to move it.

| Rule | Identifier | Reports |
|---|---|---|
| `LibIsolationRule` | `architecture.libIsolation` | Lib depending on application code |
| `SharedIsolationRule` | `architecture.sharedIsolation` | Shared depending on a domain or the Nursery |
| `DomainInternalRule` | `architecture.domainInternal` | A domain's internals used from another domain or the Nursery |
| `NurseryIsolationRule` | `architecture.nurseryIsolation` | A domain depending on the Nursery |

Every class name used in code is checked (type declarations, `new`, static calls, `instanceof`, `extends`,
attributes, ...). Class names that appear only in PHPDoc are not.

### Configuration

Defaults, which you can override in your `phpstan.neon`:

```neon
parameters:
    symfonyArchitecture:
        boundaries:
            enabled: true                    # false turns off all the boundary rules
            libNamespace: 'Lib'              # null turns off LibIsolationRule
            sharedNamespace: 'App\Shared'    # null turns off SharedIsolationRule
            nurseryNamespace: 'App\Nursery'  # null turns off NurseryIsolationRule
            ignoredNamespaces:
                - 'App\Tests'
```

- Shared and the Nursery can be any namespace, not just under `App\`. When one is `null`, its namespace becomes an
  ordinary domain.
- PHPStan merges lists with the defaults. To replace `ignoredNamespaces` instead, write `ignoredNamespaces!:`.

## Role contracts

Mark each class with the role it plays, using an attribute from `DaveLiddament\SymfonyArchitecture\Attribute`:

```php
use DaveLiddament\SymfonyArchitecture\Attribute\Service;

#[Service]
final readonly class InvoiceSender
{
}
```

| Attribute | Must be | Also checked |
|---|---|---|
| `#[Command]` | `final` | |
| `#[ConfigProvider]` | `final readonly` | Properties are scalars or `list<>`s of scalars (`configProvider.propertyType`). Only a `#[Service]` may hold one (`configProvider.onlyInService`) |
| `#[Dto]` | `final` | |
| `#[FormType]` | `final` | |
| `#[QueueGateway]` | | A collaborator a `#[Service]` may depend on |
| `#[QueueProcessor]` | `final readonly` | |
| `#[Repository]` | `final readonly` | Properties come from `Doctrine\` (`repository.dependencyType`). Public methods use a fixed vocabulary<sup>3</sup> (`repository.methodName`, `repository.methodReturn`) |
| `#[Serializer]` | `final` | |
| `#[Service]` | `final readonly` | Properties are collaborators<sup>2</sup> (`service.dependencyType`) |
| `#[ValueObject]` | `final readonly` | Properties are values (`valueObject.propertyType`)<sup>1</sup> |
| `#[ViewModel]` | `final readonly` | Properties are values, but may hold view models instead of value objects (`viewModel.propertyType`)<sup>1</sup> |

<sup>1</sup> A value is a primitive, `\DateTimeImmutable`, an enum, another class with the same attribute, or a `list<>`
of these. Nullable variants are fine; bare `array`, string-keyed maps, `\DateTime` and untyped properties are not.

<sup>2</sup> A collaborator is a class with `#[Service]`, `#[Repository]`, `#[QueueGateway]`, `#[Serializer]` or
`#[ConfigProvider]`, an interface, code from outside the app namespace (vendor code, Lib), or an iterable of these. No
primitives: configuration arrives through a `#[ConfigProvider]`.

<sup>3</sup> Repository methods:

| Prefix | Meaning | Must return |
|---|---|---|
| `find*` | Look up a single entity | A nullable, non-iterable value |
| `get*` | Query (collections, counts, ...) | Anything except `null` |
| `persist*` / `update*` / `delete*` | Writes, which flush internally | `void` |
| `has*` / `is*` | Boolean queries | Anything |

The prefix must end at a camelCase boundary, so `getaway()` is not a `get`. A nullable iterable is never allowed:
return an empty list instead.

The "must be" rules have the identifier `<role>.final` or `<role>.finalReadonly`, e.g. `service.finalReadonly`.
Commands and form types extend non-readonly Symfony base classes, so they only need to be `final`.

### Configuration

Every role is on by default. Set a role to `false` to turn off all the rules for it:

```neon
parameters:
    symfonyArchitecture:
        roles:
            command: true
            configProvider: true
            dto: true
            formType: true
            queueProcessor: true
            repository: true
            serializer: true
            service: true
            valueObject: true
            viewModel: true
```

### The attributes and `require-dev`

The attributes currently ship in this package, which you install with `--dev`, but you use them in production code.
That is safe: PHP doesn't load an attribute's class unless something instantiates it. Tools that check your
dependencies will notice, though. For `composer-require-checker`, add the attributes to `symbol-whitelist`.

The attributes may move to their own package in future. Their namespace won't change.

## Contributing

See [Contributing](CONTRIBUTING.md).
