<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Rules\Roles;

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
