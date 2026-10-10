<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Roles;

use DaveLiddament\PhpstanArchitectureRules\Frameworks\Framework;
use PHPStan\Reflection\ClassReflection;

/**
 * The classes that talk to storage (an entity manager, a database
 * connection, ...), which only a #[Repository] may hold. They are the
 * project's own plus those of each enabled framework preset.
 *
 * A class counts if it is, extends or implements one of them. Names of
 * classes that don't exist (e.g. a framework that isn't installed) never
 * match.
 */
final readonly class PersistenceClasses
{
    /** @var list<string> */
    private array $classes;

    /**
     * @param list<string> $persistenceClasses
     * @param array<string, bool> $frameworks framework => enabled
     */
    public function __construct(array $persistenceClasses, array $frameworks = [])
    {
        foreach (Framework::enabledIn($frameworks) as $framework) {
            $persistenceClasses = [...$persistenceClasses, ...$framework->persistenceClasses()];
        }

        $this->classes = array_values(array_unique(array_map(
            static fn (string $class): string => ltrim($class, '\\'),
            $persistenceClasses,
        )));
    }

    /**
     * The persistence class that the class is, extends or implements, or
     * null if it is not a persistence class.
     */
    public function matching(ClassReflection $classReflection): ?string
    {
        foreach ($this->classes as $class) {
            if (
                $classReflection->getName() === $class
                || $classReflection->isSubclassOf($class)
                || $classReflection->implementsInterface($class)
            ) {
                return $class;
            }
        }

        return null;
    }
}
