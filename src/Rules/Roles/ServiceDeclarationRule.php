<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Rules\Roles;

use DaveLiddament\PhpstanArchitectureRules\Roles\Role;

/**
 * A #[Service] class must be declared "final readonly".
 */
final class ServiceDeclarationRule extends AbstractDeclarationRule
{
    #[\Override]
    protected function getRole(): Role
    {
        return Role::Service;
    }

    #[\Override]
    protected function getRoleName(): string
    {
        return 'Service';
    }

    #[\Override]
    protected function mustBeReadonly(): bool
    {
        return true;
    }

    #[\Override]
    protected function getIdentifier(): string
    {
        return 'service.finalReadonly';
    }
}
