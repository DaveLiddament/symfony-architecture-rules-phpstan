<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles;

use PHPStan\Reflection\ClassReflection;

final class RoleAttribute
{
    /**
     * @param class-string $attributeClass
     */
    public static function isOn(ClassReflection $classReflection, string $attributeClass): bool
    {
        return [] !== $classReflection->getNativeReflection()->getAttributes($attributeClass);
    }
}
