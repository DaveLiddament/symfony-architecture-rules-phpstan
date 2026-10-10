<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ValueObjectProperties;

use DaveLiddament\SymfonyArchitecture\Attribute\ValueObject;

#[ValueObject]
final readonly class Currency
{
    public function __construct(
        public string $code,
    ) {
    }
}
