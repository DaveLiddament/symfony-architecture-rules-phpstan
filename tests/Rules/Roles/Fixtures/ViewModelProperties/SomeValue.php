<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ViewModelProperties;

use DaveLiddament\SymfonyArchitecture\Attribute\ValueObject;

#[ValueObject]
final readonly class SomeValue
{
    public function __construct(
        public string $content,
    ) {
    }
}
