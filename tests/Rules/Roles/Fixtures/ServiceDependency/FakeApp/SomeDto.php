<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ServiceDependency\FakeApp;

use DaveLiddament\Architecture\Attribute\Dto;

#[Dto]
final readonly class SomeDto
{
    public function __construct(
        public string $payload,
    ) {
    }
}
