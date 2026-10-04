<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitecture\Attribute;

/**
 * Wraps the ORM and nothing else. Must be final readonly.
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
final class Repository
{
}
