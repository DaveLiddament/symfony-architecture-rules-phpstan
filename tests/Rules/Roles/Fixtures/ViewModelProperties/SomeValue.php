<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ViewModelProperties;

use DaveLiddament\Architecture\Attribute\ValueObject;

#[ValueObject]
final readonly class SomeValue
{
    public function __construct(
        public string $content,
    ) {
    }
}
