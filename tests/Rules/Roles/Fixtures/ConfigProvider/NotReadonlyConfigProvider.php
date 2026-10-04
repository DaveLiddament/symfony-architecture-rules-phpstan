<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ConfigProvider;

use DaveLiddament\SymfonyArchitecture\Attribute\ConfigProvider;

#[ConfigProvider] // ERROR Config provider DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ConfigProvider\NotReadonlyConfigProvider must be final and readonly.
final class NotReadonlyConfigProvider
{
}
