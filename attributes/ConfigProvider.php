<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitecture\Attribute;

/**
 * The one place primitive configuration lives. Must be final readonly.
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
final class ConfigProvider
{
}
