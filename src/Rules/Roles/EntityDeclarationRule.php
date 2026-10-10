<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Rules\Roles;

use DaveLiddament\PhpstanArchitectureRules\Roles\Role;

/**
 * An #[Entity] class must be declared "final", either with the keyword or
 * with a PHPDoc final tag. The tag lets an ORM extend it at runtime for
 * lazy-loading proxies, while PHPStan still stops anyone else extending it.
 */
final class EntityDeclarationRule extends AbstractDeclarationRule
{
    #[\Override]
    protected function getRole(): Role
    {
        return Role::Entity;
    }

    #[\Override]
    protected function getRoleName(): string
    {
        return 'Entity';
    }

    #[\Override]
    protected function mustBeReadonly(): bool
    {
        return false;
    }

    #[\Override]
    protected function getIdentifier(): string
    {
        return 'entity.final';
    }

    #[\Override]
    protected function acceptsPhpDocFinal(): bool
    {
        return true;
    }
}
