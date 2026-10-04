<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitecture\Attribute;

/**
 * A Symfony console command. Must be final; it extends Symfony's Command, so it cannot be readonly.
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
final class Command
{
}
