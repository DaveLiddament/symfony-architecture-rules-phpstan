<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles;

use DaveLiddament\SymfonyArchitecture\Attribute\FormType;

/**
 * A #[FormType] class must be declared "final".
 */
final class FormTypeDeclarationRule extends AbstractDeclarationRule
{
    #[\Override]
    protected function getAttributeClass(): string
    {
        return FormType::class;
    }

    #[\Override]
    protected function getRoleName(): string
    {
        return 'Form type';
    }

    #[\Override]
    protected function mustBeReadonly(): bool
    {
        return false;
    }

    #[\Override]
    protected function getIdentifier(): string
    {
        return 'formType.final';
    }
}
