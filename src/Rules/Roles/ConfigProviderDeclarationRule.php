<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles;

use DaveLiddament\SymfonyArchitecture\Attribute\ConfigProvider;

/**
 * A #[ConfigProvider] class must be declared "final readonly".
 */
final class ConfigProviderDeclarationRule extends AbstractDeclarationRule
{
    #[\Override]
    protected function getAttributeClass(): string
    {
        return ConfigProvider::class;
    }

    #[\Override]
    protected function getRoleName(): string
    {
        return 'Config provider';
    }

    #[\Override]
    protected function mustBeReadonly(): bool
    {
        return true;
    }

    #[\Override]
    protected function getIdentifier(): string
    {
        return 'configProvider.finalReadonly';
    }
}
