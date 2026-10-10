<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Rules\Roles;

use DaveLiddament\PhpstanArchitectureRules\Roles\Role;

/**
 * A form type must be declared "final". It extends Symfony's AbstractType,
 * so it can't be readonly.
 */
final class FormTypeDeclarationRule extends AbstractDeclarationRule
{
    #[\Override]
    protected function getRole(): Role
    {
        return Role::FormType;
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
