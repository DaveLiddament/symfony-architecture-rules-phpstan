<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Rules\Roles;

use DaveLiddament\PhpstanArchitectureRules\Roles\Role;

/**
 * A #[Serializer] class must be declared "final".
 */
final class SerializerDeclarationRule extends AbstractDeclarationRule
{
    #[\Override]
    protected function getRole(): Role
    {
        return Role::Serializer;
    }

    #[\Override]
    protected function getRoleName(): string
    {
        return 'Serializer';
    }

    #[\Override]
    protected function mustBeReadonly(): bool
    {
        return false;
    }

    #[\Override]
    protected function getIdentifier(): string
    {
        return 'serializer.final';
    }
}
