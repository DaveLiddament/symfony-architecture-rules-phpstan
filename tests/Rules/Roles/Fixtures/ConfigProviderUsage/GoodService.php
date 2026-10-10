<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ConfigProviderUsage;

use DaveLiddament\SymfonyArchitecture\Attribute\Service;

#[Service]
final readonly class GoodService
{
    public function __construct(
        private TheConfig $config,
        private ?TheConfig $maybeConfig,
    ) {
    }
}
