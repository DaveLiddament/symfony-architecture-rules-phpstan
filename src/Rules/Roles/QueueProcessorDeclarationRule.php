<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles;

use DaveLiddament\SymfonyArchitecture\Attribute\QueueProcessor;

/**
 * A #[QueueProcessor] class must be declared "final readonly".
 */
final class QueueProcessorDeclarationRule extends AbstractDeclarationRule
{
    #[\Override]
    protected function getAttributeClass(): string
    {
        return QueueProcessor::class;
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
