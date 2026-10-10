<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ServiceDependency\FakeApp;

use DaveLiddament\Architecture\Attribute\ConfigProvider;

#[ConfigProvider]
final readonly class TheConfig
{
    public function __construct(
        public string $value,
    ) {
    }
}
