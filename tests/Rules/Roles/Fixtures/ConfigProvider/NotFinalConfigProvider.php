<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ConfigProvider;

use DaveLiddament\SymfonyArchitecture\Attribute\ConfigProvider;

#[ConfigProvider] // ERROR Config provider DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ConfigProvider\NotFinalConfigProvider must be final and readonly.
readonly class NotFinalConfigProvider
{
}
