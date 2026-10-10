# Architecture Rules for PHPStan

[![PHP versions: 8.3 to 8.5](https://img.shields.io/badge/php-8.3|8.4|8.5-blue.svg)](https://packagist.org/packages/dave-liddament/phpstan-architecture-rules)
[![PHPStan max level](https://img.shields.io/badge/PHPStan-max%20level-brightgreen.svg)](phpstan.neon)
[![License](https://poser.pugx.org/dave-liddament/phpstan-architecture-rules/license)](https://github.com/DaveLiddament/phpstan-architecture-rules/blob/main/LICENSE.md)

## The problem

Symfony doesn't tell you how to structure your application, so every team invents its own rules: "features don't
reach into each other's internals", "shared code doesn't depend on features", "services are stateless". Those rules
live in people's heads, in a wiki page, or in code review comments. As the app grows, they erode one shortcut at a
time, until nobody can say where anything belongs.

This [PHPStan](https://phpstan.org) extension turns those rules into errors. You get a sensible architecture out of
the box, every rule is configurable, and any part can be switched off.

It covers:

- **[Architectural boundaries](#architectural-boundaries)**: which parts of the app may depend on which.
- **[Role contracts](#role-contracts)**: every class declares its role, e.g. `#[Service]`, and each role has rules.
- **[Configuration](#configuration)**: the defaults, and how to change them or turn rules off.

## Installation

Install the role attributes as a normal dependency, because your production code uses them, and the rules as a dev
dependency:

```shell
composer require dave-liddament/architecture-rules-attributes
composer require --dev dave-liddament/phpstan-architecture-rules
```

If you use [phpstan/extension-installer](https://github.com/phpstan/extension-installer) you're ready to go. Otherwise,
include the extension in your `phpstan.neon`:

```neon
includes:
    - vendor/dave-liddament/phpstan-architecture-rules/extension.neon
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
and are not checked.

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
| <a id="role-location"></a>`RoleLocationRule` | `architecture.roleLocation` | A controller, CLI command or entity outside its role directory, e.g. `App\Registration\Controller`, or a repository outside `Repository\` or the area root |

Role directories are relative to the area's root, so the same applies inside Shared (`App\Shared\Entity`) and the
Nursery. Roles are recognised by their attribute or a [role alias](#role-aliases).

Every class name used in code is checked (type declarations, `new`, static calls, `instanceof`, `extends`,
attributes, ...). Class names that appear only in PHPDoc are not.

To configure or turn off these rules, see [Configuration](#configuration).

## Role contracts

Every class in a domain, Shared or the Nursery must declare the role it plays, using an attribute from
`DaveLiddament\Architecture\Attribute`, or one of your own mapped onto a role with a [role alias](#role-aliases)
(`architecture.roleRequired`). A class without a role is invisible to the role rules, so it is reported. Enums, interfaces, traits,
framework glue directly in `App\` and ignored namespaces are exempt.

```php
use DaveLiddament\Architecture\Attribute\Service;

#[Service]
final readonly class InvoiceSender
{
}
```

The roles:

| Attribute | What it is |
|---|---|
| [`#[CliCommand]`](#clicommand) | A command line entry point |
| [`#[ConfigProvider]`](#configprovider) | The one place primitive configuration lives |
| [`#[Controller]`](#controller) | An HTTP entry point |
| [`#[Dto]`](#dto) | A plain data carrier between layers |
| [`#[Entity]`](#entity) | An object with identity |
| [Form type](#formtype) | A Symfony form type (no attribute: recognised by the [Symfony preset](#framework-presets)) |
| [`#[QueueGateway]`](#queuegateway) | Sends messages to a queue |
| [`#[QueueProcessor]`](#queueprocessor) | A message handler |
| [`#[Repository]`](#repository) | Wraps the ORM |
| [`#[Serializer]`](#serializer) | Converts to and from wire formats |
| [`#[Service]`](#service) | A stateless collaborator |
| [`#[ValueObject]`](#valueobject) | A value with no identity |
| [`#[ViewModel]`](#viewmodel) | A snapshot handed to a template |

Each role's rules can be turned off with its switch under `architecture.roles` (see
[Configuration](#configuration)).

### CliCommand

- Must be `final` (`cliCommand.final`), but needn't be `readonly`: a Symfony command extends `Command`, which isn't.
- Must live in a `CliCommand` directory, e.g. `App\Registration\CliCommand` ([role location](#role-location)).
- Turn off with `roles.cliCommand: false`.

### ConfigProvider

- Must be `final readonly` (`configProvider.finalReadonly`).
- Properties are scalars or `list<>`s of scalars, not even enums or dates (`configProvider.propertyType`).
- Only a `#[Service]` may hold a config provider. This is checked on every class (`configProvider.onlyInService`).
- Turn off with `roles.configProvider: false`.

### Controller

- Every public method declares an allowed return type (or a subclass of one), or a union of these, and is never
  nullable (`architecture.controllerReturnType`). With the [Symfony preset](#framework-presets) the only allowed type
  is `Symfony\Component\HttpFoundation\Response`, so any response is fine. Without a preset or a configured list,
  return types aren't checked.
- Must live in a `Controller` directory, e.g. `App\Registration\Controller` ([role location](#role-location)).
- Turn off with `roles.controller: false`.

To allow only specific return types, list them. They replace the preset's default:

```neon
parameters:
    architecture:
        controllerReturnTypes:
            - 'App\Shared\Page'
            - 'Symfony\Component\HttpFoundation\JsonResponse'
            - 'Symfony\Component\HttpFoundation\RedirectResponse'
```

### Dto

- Must be `final` (`dto.final`).
- Deliberately not a value object: a DTO may carry things a value never would, such as entities.
- Turn off with `roles.dto: false`.

### Entity

- Must be `final` (`entity.final`). A `@final` PHPDoc tag also counts, so an ORM can still extend it at runtime for
  lazy-loading proxies.
- Must live in an `Entity` directory, e.g. `App\Registration\Entity` ([role location](#role-location)).
- Doctrine's `#[ORM\Entity]` counts as `#[Entity]` through the [Doctrine preset](#framework-presets).
- Turn off with `roles.entity: false`.

### FormType

- Must be `final` (`formType.final`). It extends Symfony's `AbstractType`, so it can't be `readonly`.
- There is no attribute: a class extending `AbstractType` is a form type through the
  [Symfony preset](#framework-presets).
- Turn off with `roles.formType: false`.

### QueueGateway

- No rules of its own. It marks a collaborator that a [`#[Service]`](#service) may depend on.

### QueueProcessor

- Must be `final readonly` (`queueProcessor.finalReadonly`).
- Turn off with `roles.queueProcessor: false`.

### Repository

- Must be `final readonly` (`repository.finalReadonly`).
- Every property comes from `Doctrine\` (`repository.dependencyType`).
- Only a repository may hold the entity manager. This is checked on every class (`entityManager.onlyInRepository`).
- Must live in a `Repository` directory or at the root of its area ([role location](#role-location)).
- Public methods use a fixed vocabulary (`repository.methodName`, `repository.methodReturn`):

  | Prefix | Meaning | Must return |
  |---|---|---|
  | `find*` | Look up a single entity | A nullable, non-iterable value |
  | `get*` | Query (collections, counts, ...) | Anything except `null` |
  | `persist*` / `update*` / `delete*` | Writes, which flush internally | `void` |
  | `has*` / `is*` | Boolean queries | Anything |

  The prefix must end at a camelCase boundary, so `getaway()` is not a `get`. A nullable iterable is never allowed:
  return an empty list instead.
- Turn off with `roles.repository: false`.

### Serializer

- Must be `final` (`serializer.final`).
- The only class whose public methods may return array shapes (`array{...}`). On every other class, a public method
  returning a shape, directly or inside a list, is reported: it should return a value object, DTO or view model
  instead. Private methods may use shapes freely (`arrayShape.onlyInSerializer`).
- Turn off with `roles.serializer: false`.

### Service

- Must be `final readonly` (`service.finalReadonly`).
- Every property is a collaborator: a class with `#[Service]`, `#[Repository]`, `#[QueueGateway]`, `#[Serializer]` or
  `#[ConfigProvider]`, an interface, code from outside the app namespace (vendor code, Lib), or an iterable of these.
  No primitives: configuration arrives through a config provider (`service.dependencyType`).
- Turn off with `roles.service: false`.

### ValueObject

- Must be `final readonly` (`valueObject.finalReadonly`).
- Every property is a value: a primitive, `\DateTimeImmutable`, an enum, another value object, or a `list<>` of these.
  Nullable variants are fine; bare `array`, string-keyed maps, `\DateTime` and untyped properties are not
  (`valueObject.propertyType`).
- Turn off with `roles.valueObject: false`.

### ViewModel

- Must be `final readonly` (`viewModel.finalReadonly`).
- Properties follow the value object rule, but hold other view models instead of value objects: a view model renders
  formatted values rather than carrying domain values (`viewModel.propertyType`).
- Turn off with `roles.viewModel: false`.

## Configuration

All the settings and their defaults. Override any of them in the `parameters` section of your `phpstan.neon`:

```neon
parameters:
    architecture:
        appNamespace: 'App'                  # where your application code lives
        ignoredNamespaces:                   # code the rules ignore
            - 'App\Tests'
        frameworks:                          # see Framework presets
            doctrine: true
            symfony: true
        controllerReturnTypes: []            # see Controller
        boundaries:
            enabled: true
            libNamespace: 'Lib'
            sharedNamespace: 'App\Shared'
            nurseryNamespace: 'App\Nursery'
        roles:
            cliCommand: true
            configProvider: true
            controller: true
            dto: true
            entity: true
            formType: true
            queueProcessor: true
            repository: true
            serializer: true
            service: true
            valueObject: true
            viewModel: true
        roleAliases: []                      # see Role aliases
        roleRequired:
            enabled: true
            exemptClasses: []                # framework glue that may stay roleless
```

PHPStan adds list values to the defaults. To replace a list instead, add `!` to its key, e.g. `ignoredNamespaces!:`.

### Framework presets

The Symfony and Doctrine presets are on by default. Each one only recognises classes that use its framework, so it
does nothing in a project that doesn't use it. Turn one off with `frameworks.symfony: false` or
`frameworks.doctrine: false`.

| Preset | Recognises | Also |
|---|---|---|
| Doctrine | `#[ORM\Entity]` as an entity | |
| Symfony | `#[AsController]` or extending `AbstractController` as a controller; `#[AsCommand]` or extending `Command` as a CLI command; `#[AsMessageHandler]` as a queue processor; extending `AbstractType` as a form type | Controllers may return any `Response` |

### Role aliases

If your project already has its own attributes, parent classes or interfaces for a role, map them onto the role
rather than adding a second attribute. A class matching any alias plays that role, and every rule for the role applies
to it:

```neon
parameters:
    architecture:
        roleAliases:
            entity:
                attributes:
                    - 'App\Shared\Attribute\AggregateRoot'
            controller:
                extends:
                    - 'App\Shared\Controller\BaseController'
            queueProcessor:
                implements:
                    - 'App\Shared\Queue\MessageHandler'
```

Each role takes `attributes`, `extends` and `implements` lists. The role names are the keys under `roles`. An alias
attribute only counts on the class that declares it, while `extends` and `implements` match any descendant. Your
aliases are added to those of the [framework presets](#framework-presets).

### Turning rules off

| To turn off | Set |
|---|---|
| All the architectural boundary rules | `boundaries.enabled: false` |
| `LibIsolationRule` | `boundaries.libNamespace: null` |
| `SharedIsolationRule` | `boundaries.sharedNamespace: null` (`App\Shared` then becomes an ordinary domain) |
| `NurseryIsolationRule` | `boundaries.nurseryNamespace: null` (`App\Nursery` then becomes an ordinary domain) |
| All the rules for one role | `roles.<role>: false`, e.g. `roles.service: false` |
| Requiring every class to declare a role | `roleRequired.enabled: false` |
| The role requirement for a single class | Add it to `roleRequired.exemptClasses` |

Shared and the Nursery can also be moved to any namespace, not just under `App\`.

To silence one kind of error without turning a rule off, ignore it by its identifier using PHPStan's
[`ignoreErrors`](https://phpstan.org/user-guide/ignoring-errors), e.g. `identifier: repository.methodName`.

## Contributing

See [Contributing](CONTRIBUTING.md).
