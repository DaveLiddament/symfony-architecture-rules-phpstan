<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Rules\Roles;

use DaveLiddament\Architecture\Attribute\Service;

/**
 * A #[Service] class must be declared "final readonly".
 */
final class ServiceDeclarationRule extends AbstractDeclarationRule
{
    #[\Override]
    protected function getAttributeClass(): string
    {
        return Service::class;
    }

    #[\Override]
    protected function getRoleName(): string
    {
        return 'Service';
    }

    #[\Override]
    protected function mustBeReadonly(): bool
    {
        return true;
    }

    #[\Override]
    protected function getIdentifier(): string
    {
        return 'service.finalReadonly';
    }
}
