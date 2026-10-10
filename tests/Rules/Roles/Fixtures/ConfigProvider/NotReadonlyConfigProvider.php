<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ConfigProvider;

use DaveLiddament\Architecture\Attribute\ConfigProvider;

#[ConfigProvider] // ERROR Config provider DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ConfigProvider\NotReadonlyConfigProvider must be final and readonly.
final class NotReadonlyConfigProvider
{
}
