<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Rules\Roles;

use DaveLiddament\PhpstanArchitectureRules\Roles\Role;

/**
 * A #[ViewModel] class must be declared "final readonly".
 */
final class ViewModelDeclarationRule extends AbstractDeclarationRule
{
    #[\Override]
    protected function getRole(): Role
    {
        return Role::ViewModel;
    }

    #[\Override]
    protected function getRoleName(): string
    {
        return 'View model';
    }

    #[\Override]
    protected function mustBeReadonly(): bool
    {
        return true;
    }

    #[\Override]
    protected function getIdentifier(): string
    {
        return 'viewModel.finalReadonly';
    }
}
