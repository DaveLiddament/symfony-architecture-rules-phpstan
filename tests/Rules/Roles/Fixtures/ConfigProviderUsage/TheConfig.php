<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ConfigProviderUsage;

use DaveLiddament\SymfonyArchitecture\Attribute\ConfigProvider;

#[ConfigProvider]
final readonly class TheConfig
{
    public function __construct(
        public string $value,
    ) {
    }
}
