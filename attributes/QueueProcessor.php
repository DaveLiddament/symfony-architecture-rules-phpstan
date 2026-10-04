<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitecture\Attribute;

/**
 * A message handler. Must be final readonly.
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
final class QueueProcessor
{
}
