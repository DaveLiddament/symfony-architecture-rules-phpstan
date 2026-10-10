<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Rules\Roles;

use DaveLiddament\PhpstanArchitectureRules\Roles\Role;

/**
 * A #[ConfigProvider] class must be declared "final readonly".
 */
final class ConfigProviderDeclarationRule extends AbstractDeclarationRule
{
    #[\Override]
    protected function getRole(): Role
    {
        return Role::ConfigProvider;
    }

    #[\Override]
    protected function getRoleName(): string
    {
        return 'Config provider';
    }

    #[\Override]
    protected function mustBeReadonly(): bool
    {
        return true;
    }

    #[\Override]
    protected function getIdentifier(): string
    {
        return 'configProvider.finalReadonly';
    }
}
