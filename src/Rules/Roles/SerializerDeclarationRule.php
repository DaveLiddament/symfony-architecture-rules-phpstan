<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Rules\Roles;

use DaveLiddament\SymfonyArchitecture\Attribute\Serializer;

/**
 * A #[Serializer] class must be declared "final".
 */
final class SerializerDeclarationRule extends AbstractDeclarationRule
{
    #[\Override]
    protected function getAttributeClass(): string
    {
        return Serializer::class;
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
