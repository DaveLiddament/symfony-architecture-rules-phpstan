<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Rules\Roles;

use DaveLiddament\PhpstanArchitectureRules\Roles\Role;
use PHPStan\Reflection\ClassReflection;

/**
 * A #[ConfigProvider] is where primitive configuration lives, so its
 * properties are scalars or lists of scalars: not even enums or dates.
 */
final class ConfigProviderPropertyTypeRule extends AbstractValuePropertyTypeRule
{
    #[\Override]
    protected function getRole(): Role
    {
        return Role::ConfigProvider;
    }

    #[\Override]
    protected function getMessage(): string
    {
        return 'Config provider property %s must be a scalar or a list of scalars.';
    }

    #[\Override]
    protected function getIdentifier(): string
    {
        return 'configProvider.propertyType';
    }

    #[\Override]
    protected function isAllowedClass(ClassReflection $classReflection): bool
    {
        return false;
    }
}
