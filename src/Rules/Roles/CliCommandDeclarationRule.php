<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Rules\Roles;

use DaveLiddament\PhpstanArchitectureRules\Roles\Role;

/**
 * A #[CliCommand] class must be declared "final".
 */
final class CliCommandDeclarationRule extends AbstractDeclarationRule
{
    #[\Override]
    protected function getRole(): Role
    {
        return Role::CliCommand;
    }

    #[\Override]
    protected function getRoleName(): string
    {
        return 'CLI command';
    }

    #[\Override]
    protected function mustBeReadonly(): bool
    {
        return false;
    }

    #[\Override]
    protected function getIdentifier(): string
    {
        return 'cliCommand.final';
    }
}
