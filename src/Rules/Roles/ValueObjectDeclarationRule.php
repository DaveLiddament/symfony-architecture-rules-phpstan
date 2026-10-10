<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Rules\Roles;

use DaveLiddament\SymfonyArchitecture\Attribute\ValueObject;

/**
 * A #[ValueObject] class must be declared "final readonly".
 */
final class ValueObjectDeclarationRule extends AbstractDeclarationRule
{
    #[\Override]
    protected function getAttributeClass(): string
    {
        return ValueObject::class;
    }

    #[\Override]
    protected function getRoleName(): string
    {
        return 'Value object';
    }

    #[\Override]
    protected function mustBeReadonly(): bool
    {
        return true;
    }

    #[\Override]
    protected function getIdentifier(): string
    {
        return 'valueObject.finalReadonly';
    }
}
