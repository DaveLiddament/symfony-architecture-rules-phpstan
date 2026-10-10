<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Rules\Roles;

use DaveLiddament\PhpstanArchitectureRules\Roles\Role;

/**
 * A #[Repository] class must be declared "final readonly".
 */
final class RepositoryDeclarationRule extends AbstractDeclarationRule
{
    #[\Override]
    protected function getRole(): Role
    {
        return Role::Repository;
    }

    #[\Override]
    protected function getRoleName(): string
    {
        return 'Repository';
    }

    #[\Override]
    protected function mustBeReadonly(): bool
    {
        return true;
    }

    #[\Override]
    protected function getIdentifier(): string
    {
        return 'repository.finalReadonly';
    }
}
