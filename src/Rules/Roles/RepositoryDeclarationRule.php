<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles;

use DaveLiddament\SymfonyArchitecture\Attribute\Repository;

/**
 * A #[Repository] class must be declared "final readonly".
 */
final class RepositoryDeclarationRule extends AbstractDeclarationRule
{
    #[\Override]
    protected function getAttributeClass(): string
    {
        return Repository::class;
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
