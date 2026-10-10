<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Rules\Roles;

use DaveLiddament\PhpstanArchitectureRules\Roles\Role;

/**
 * A #[QueueProcessor] class must be declared "final readonly".
 */
final class QueueProcessorDeclarationRule extends AbstractDeclarationRule
{
    #[\Override]
    protected function getRole(): Role
    {
        return Role::QueueProcessor;
    }

    #[\Override]
    protected function getRoleName(): string
    {
        return 'Queue processor';
    }

    #[\Override]
    protected function mustBeReadonly(): bool
    {
        return true;
    }

    #[\Override]
    protected function getIdentifier(): string
    {
        return 'queueProcessor.finalReadonly';
    }
}
