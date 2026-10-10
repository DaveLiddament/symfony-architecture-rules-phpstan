<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Roles;

use PHPStan\Reflection\ClassReflection;

/**
 * Works out which roles a class plays.
 *
 * A class plays a role when it carries the role's attribute, or matches one
 * of the role's aliases: it carries an alias attribute, extends an alias
 * class or implements an alias interface. Attributes count only on the
 * class that declares them, not on its subclasses. Aliases naming classes
 * that don't exist (e.g. a framework that isn't installed) never match.
 */
final readonly class RoleResolver
{
    /** @var array<string, array{attributes: list<string>, extends: list<string>, implements: list<string>}> role => aliases */
    private array $roleAliases;

    private string $attributeNamespace;

    /**
     * @param array<string, array{attributes?: list<string>, extends?: list<string>, implements?: list<string>}> $roleAliases role => aliases
     */
    public function __construct(array $roleAliases)
    {
        $normalised = [];
        foreach ($roleAliases as $role => $aliases) {
            $normalised[Role::from($role)->value] = [
                'attributes' => array_map(self::normalise(...), $aliases['attributes'] ?? []),
                'extends' => array_map(self::normalise(...), $aliases['extends'] ?? []),
                'implements' => array_map(self::normalise(...), $aliases['implements'] ?? []),
            ];
        }
        $this->roleAliases = $normalised;

        $serviceAttribute = Role::Service->attributeClass();
        $this->attributeNamespace = substr($serviceAttribute, 0, (int) strrpos($serviceAttribute, '\\'));
    }

    public function plays(ClassReflection $classReflection, Role $role): bool
    {
        $attributes = $this->attributeNames($classReflection);
        if (in_array($role->attributeClass(), $attributes, true)) {
            return true;
        }

        $aliases = $this->roleAliases[$role->value] ?? null;
        if (null === $aliases) {
            return false;
        }

        if ([] !== array_intersect($aliases['attributes'], $attributes)) {
            return true;
        }

        foreach ($aliases['extends'] as $class) {
            if ($classReflection->isSubclassOf($class)) {
                return true;
            }
        }

        foreach ($aliases['implements'] as $interface) {
            if ($classReflection->implementsInterface($interface)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return list<Role>
     */
    public function rolesOf(ClassReflection $classReflection): array
    {
        return array_values(array_filter(
            Role::cases(),
            fn (Role $role): bool => $this->plays($classReflection, $role),
        ));
    }

    /**
     * Whether the class plays any role. Any attribute from the role attribute
     * namespace counts, so a role added to the attributes package is
     * recognised before these rules know about it.
     */
    public function hasAnyRole(ClassReflection $classReflection): bool
    {
        foreach ($this->attributeNames($classReflection) as $attribute) {
            if (str_starts_with($attribute, $this->attributeNamespace.'\\')) {
                return true;
            }
        }

        return [] !== $this->rolesOf($classReflection);
    }

    /**
     * The namespace the role attributes live in.
     */
    public function getAttributeNamespace(): string
    {
        return $this->attributeNamespace;
    }

    /**
     * @return list<string>
     */
    private function attributeNames(ClassReflection $classReflection): array
    {
        $names = [];
        foreach ($classReflection->getNativeReflection()->getAttributes() as $attribute) {
            $names[] = $attribute->getName();
        }

        return $names;
    }

    private static function normalise(string $className): string
    {
        return ltrim($className, '\\');
    }
}
