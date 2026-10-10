<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Rules\Roles;

use DaveLiddament\Architecture\Attribute\CliCommand;

/**
 * A #[CliCommand] class must be declared "final".
 */
final class CliCommandDeclarationRule extends AbstractDeclarationRule
{
    #[\Override]
    protected function getAttributeClass(): string
    {
        return CliCommand::class;
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
