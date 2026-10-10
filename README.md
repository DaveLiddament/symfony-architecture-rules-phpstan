# Architecture Rules for PHPStan

[![PHP versions: 8.3 to 8.5](https://img.shields.io/badge/php-8.3|8.4|8.5-blue.svg)](https://packagist.org/packages/dave-liddament/phpstan-architecture-rules)
[![PHPStan max level](https://img.shields.io/badge/PHPStan-max%20level-brightgreen.svg)](phpstan.neon)
[![License](https://poser.pugx.org/dave-liddament/phpstan-architecture-rules/license)](https://github.com/DaveLiddament/phpstan-architecture-rules/blob/main/LICENSE.md)

## The problem

Frameworks don't tell you how to structure your application, so every team invents its own rules: "features don't
reach into each other's internals", "shared code doesn't depend on features", "services are stateless". Those rules
live in people's heads, in a wiki page, or in code review comments. As the app grows, they erode one shortcut at a
time, until nobody can say where anything belongs.

This [PHPStan](https://phpstan.org) extension turns those rules into errors. Classes state their role, and which
other domains may use them, with [attributes](https://github.com/DaveLiddament/architecture-rules-attributes), and this extension checks them. You get a sensible architecture
out of the box, with Symfony and Doctrine support. Every rule is configurable, and any part can be switched off.

It covers:

- **[Architectural boundaries](#architectural-boundaries)**: which parts of the app may depend on which.
- **[Role contracts](#role-contracts)**: every class declares its role, e.g. `#[Service]`, and each role has rules.
- **[Placement report](#placement-report)**: an occasional, non-blocking check that classes still live in the right
  place.
- **[Configuration](#configuration)**: the defaults, and how to change them or turn rules off.

This README covers what each rule checks and how to configure it. What each role and `#[Exported]` mean is in the
[attributes README](https://github.com/DaveLiddament/architecture-rules-attributes#the-roles).

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
| **Pending** | `App\Pending\` | Code whose domain isn't clear yet. It acts as a domain called `Pending` until its classes move to their real home. |
| **Domain** | `App\<Name>\` | Every other namespace directly under `App\`, e.g. `App\Registration\`. |

A domain's classes are internal to it, wherever they live in the domain. Another domain may use a class only if it is
marked [`#[Exported]`](https://github.com/DaveLiddament/architecture-rules-attributes#exporting-to-other-domains), and, when the export lists domains, only
if it is one of them. Classes directly in `App\` (e.g. `App\Kernel`) are framework glue and are not checked.

```php
use DaveLiddament\Architecture\Attribute\Exported;

#[Entity]
#[Exported]                             // any domain may use it
final class Walk {}

#[Service]
#[Exported(to: ['Stats', 'Billing'])]   // only Stats and Billing may use it
final readonly class WalkPlanner {}
```

| From ↓ / To → | Lib | Shared | Domain or Pending (exported) | Domain or Pending (not exported) |
|---|---|---|---|---|
| **Lib** | ✅ | ❌ | ❌ | ❌ |
| **Shared** | ✅ | ✅ | ❌ | ❌ |
| **Domain or Pending** | ✅ | ✅ | ✅ (if listed in `to`) | own domain only |

Dependencies flow one way: domains → Shared → Lib. Pending is treated like any other domain: another domain may use a
Pending class only if it is exported, and `to` can name it, e.g. `#[Exported(to: ['Pending'])]`. Anything used
throughout the app belongs in Shared rather than being exported (see
[Exported or Shared?](https://github.com/DaveLiddament/architecture-rules-attributes#exported-or-shared)).

| Rule | Identifier | Reports |
|---|---|---|
| `LibIsolationRule` | `architecture.libIsolation` | Lib depending on application code |
| `SharedIsolationRule` | `architecture.sharedIsolation` | Shared depending on a domain or Pending |
| `DomainInternalRule` | `architecture.domainInternal` | A class used from another domain without being exported to it (Pending counts as a domain) |
| `ExportedDeclarationRule` | `architecture.notExportable` | `#[Exported]` on a trait, or on a role nothing outside its domain should use: CLI command, config provider, controller, form type, queue processor, serializer or view model |
| | `architecture.exportedToNone` | `#[Exported(to: [])]`, which exports to no domain |
| `ExportedToUnknownDomainRule` | `architecture.exportedToUnknownDomain` | A domain in `to` that doesn't exist, e.g. a typo or a renamed domain |
| `CrossDomainRepositoryWriteRule` | `architecture.repositoryWrite` | Another domain calling a repository method that isn't a read (`find*`, `get*`, `has*`, `is*`). Writes go through a service the owning domain exports |
| <a id="role-location"></a>`RoleLocationRule` | `architecture.roleLocation` | A controller, CLI command or entity outside its role directory, e.g. `App\Registration\Controller`, or a repository outside `Repository\` or the area root |

A domain exists when at least one analysed class lives in it, so `ExportedToUnknownDomainRule` needs the whole app
analysed: analysing a single directory may report domains outside it as unknown.

Role directories are relative to the area's root, so the same applies inside Shared (`App\Shared\Entity`) and
Pending. Roles are recognised by their attribute or a [role alias](#role-aliases).

Every class name used in code is checked (type declarations, `new`, static calls, `instanceof`, `extends`,
attributes, ...). Class names that appear only in PHPDoc are not.

To configure or turn off these rules, see [Configuration](#configuration).

## Role contracts

Every class in a domain, Shared or Pending must declare the role it plays, using an attribute from
`DaveLiddament\Architecture\Attribute`, or one of your own mapped onto a role with a [role alias](#role-aliases)
(`architecture.roleRequired`). A class without a role is invisible to the role rules, so it is reported. Enums,
interfaces, traits, framework glue directly in `App\` and ignored namespaces are exempt.

```php
use DaveLiddament\Architecture\Attribute\Service;

#[Service]
final readonly class InvoiceSender
{
}
```

What each role means, and how to choose one, is in the [attributes README](https://github.com/DaveLiddament/architecture-rules-attributes#the-roles).
The rules for each role are below. Each role's rules can be turned off with its switch under `architecture.roles`
(see [Configuration](#configuration)).

A form type is a role with no attribute: it only exists in Symfony, so the [Symfony preset](#framework-presets)
recognises it.

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

- Must be `final` (`dto.final`), but needn't be `readonly`, and may hold anything, including entities.
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

- No rules of its own. A [`#[Service]`](#service) may depend on it.

### QueueProcessor

- Must be `final readonly` (`queueProcessor.finalReadonly`).
- Turn off with `roles.queueProcessor: false`.

### Repository

- Must be `final readonly` (`repository.finalReadonly`).
- Every property is a [persistence class](#persistence-classes), another repository, or a `list<>` of entities or value
  objects, which covers in-memory and generated-data repositories (`repository.dependencyType`). Services, config and
  clocks belong in the caller.
- Only a repository may hold a persistence class. This is checked on every class (`persistence.onlyInRepository`).
- Must live in a `Repository` directory or at the root of its area ([role location](#role-location)).
- Public methods use the [fixed vocabulary](https://github.com/DaveLiddament/architecture-rules-attributes#repository) (`repository.methodName`), and each prefix has a
  return contract (`repository.methodReturn`):

  | Prefix | Must return |
  |---|---|
  | `find*` | A nullable, non-iterable value |
  | `get*` | Anything except `null` |
  | `persist*` / `update*` / `delete*` | `void` |
  | `has*` / `is*` | Anything |

  The prefix must end at a camelCase boundary, so `getaway()` is not a `get`. A nullable iterable is never allowed:
  return an empty list instead.
- Other domains may only call its reads, `find*`, `get*`, `has*` and `is*` (`architecture.repositoryWrite`, a
  [boundary rule](#architectural-boundaries)).
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
- Properties follow the value object rule, but hold other view models instead of value objects
  (`viewModel.propertyType`).
- Turn off with `roles.viewModel: false`.

## Placement report

Pending, Shared and `#[Exported]` drift over time: a Pending class finds its home, a Shared class ends up used by one
domain, an export stops being used. Whether to act on that is a judgement call, so these checks aren't part of the
normal run. Run them now and then, on the whole app, with a config that adds `placement.neon` to your usual one:

```neon
# phpstan-placement.neon
includes:
    - phpstan.neon
    - vendor/dave-liddament/phpstan-architecture-rules/placement.neon
```

```shell
vendor/bin/phpstan analyse -c phpstan-placement.neon
```

Your normal run already passes, so everything reported is placement advice.

| Rule | Identifier | Reports |
|---|---|---|
| `PendingWayOutRule` | `placement.pendingUsedByOneDomain` | A Pending class only one domain uses, which uses no other domain: move it into that domain |
| | `placement.pendingUsesOneDomain` | A Pending class no domain uses, which uses only one domain: move it into that domain |
| `SharedUsageRule` | `placement.sharedUsedByOneDomain` | A Shared class only one domain uses (Pending counts as a domain) |
| | `placement.sharedUnused` | A Shared class no domain uses |
| `UnusedExportRule` | `placement.exportUnused` | An `#[Exported]` class no other domain uses |
| | `placement.exportedToUnusedDomain` | A domain in `to` that doesn't use the class |

A Shared class used by other Shared code is a building block, so `SharedUsageRule` leaves it alone. When a Pending
class involves several domains, it stays in Pending until you choose between Shared and one domain exporting it.

Turn off part of the report with `placement.pendingWayOut`, `placement.sharedUsage` or `placement.unusedExports` set
to `false`.

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
        persistenceClasses: []               # see Persistence classes
        boundaries:
            enabled: true
            libNamespace: 'Lib'
            sharedNamespace: 'App\Shared'
            pendingNamespace: 'App\Pending'
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
        placement:                           # see Placement report
            pendingWayOut: true
            sharedUsage: true
            unusedExports: true
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
| Doctrine | `#[ORM\Entity]` as an entity | `EntityManagerInterface`, `ObjectManager`, `ManagerRegistry` and DBAL's `Connection` are [persistence classes](#persistence-classes) |
| Symfony | `#[AsController]` or extending `AbstractController` as a controller; `#[AsCommand]` or extending `Command` as a CLI command; `#[AsMessageHandler]` as a queue processor; extending `AbstractType` as a form type | Controllers may return any `Response` |

### Persistence classes

Persistence classes talk to storage, so only a [repository](#repository) may hold one. A class counts if it is, extends
or implements a listed class. Your list is added to the [Doctrine preset's](#framework-presets):

```neon
parameters:
    architecture:
        persistenceClasses:
            - 'PDO'
```

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
| The Pending area | `boundaries.pendingNamespace: null` (`App\Pending` then becomes an ordinary domain, and the placement report stops treating it as Pending) |
| All the rules for one role | `roles.<role>: false`, e.g. `roles.service: false` |
| Requiring every class to declare a role | `roleRequired.enabled: false` |
| The role requirement for a single class | Add it to `roleRequired.exemptClasses` |
| Part of the placement report | `placement.<part>: false`, e.g. `placement.sharedUsage: false` |

Shared and Pending can also be moved to any namespace, not just under `App\`. Pending is named after the last part
of its namespace, e.g. `Incubating` for `App\Incubating`.

To silence one kind of error without turning a rule off, ignore it by its identifier using PHPStan's
[`ignoreErrors`](https://phpstan.org/user-guide/ignoring-errors), e.g. `identifier: repository.methodName`.

## Contributing

See [Contributing](CONTRIBUTING.md).
