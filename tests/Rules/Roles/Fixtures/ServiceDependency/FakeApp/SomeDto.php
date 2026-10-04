<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ServiceDependency\FakeApp;

use DaveLiddament\SymfonyArchitecture\Attribute\Dto;

#[Dto]
final readonly class SomeDto
{
    public function __construct(
        public string $payload,
    ) {
    }
}
