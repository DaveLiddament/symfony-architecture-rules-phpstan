<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Rules\Roles;

use DaveLiddament\PhpstanArchitectureRules\Roles\Role;

/**
 * A #[Dto] class must be declared "final".
 */
final class DtoDeclarationRule extends AbstractDeclarationRule
{
    #[\Override]
    protected function getRole(): Role
    {
        return Role::Dto;
    }

    #[\Override]
    protected function getRoleName(): string
    {
        return 'Dto';
    }

    #[\Override]
    protected function mustBeReadonly(): bool
    {
        return false;
    }

    #[\Override]
    protected function getIdentifier(): string
    {
        return 'dto.final';
    }
}
