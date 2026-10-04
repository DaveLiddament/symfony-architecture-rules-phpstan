<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitecture\Attribute;

/**
 * Sends messages to a queue. A collaborator a #[Service] may depend on.
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
final class QueueGateway
{
}
