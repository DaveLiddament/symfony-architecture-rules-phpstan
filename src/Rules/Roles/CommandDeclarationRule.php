<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Rules\Roles;

use DaveLiddament\SymfonyArchitecture\Attribute\Command;

/**
 * A #[Command] class must be declared "final".
 */
final class CommandDeclarationRule extends AbstractDeclarationRule
{
    #[\Override]
    protected function getAttributeClass(): string
    {
        return Command::class;
    }

    #[\Override]
    protected function getRoleName(): string
    {
        return 'Command';
    }

    #[\Override]
    protected function mustBeReadonly(): bool
    {
        return false;
    }

    #[\Override]
    protected function getIdentifier(): string
    {
        return 'command.final';
    }
}
