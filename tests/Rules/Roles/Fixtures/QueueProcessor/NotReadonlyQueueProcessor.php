<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\QueueProcessor;

use DaveLiddament\SymfonyArchitecture\Attribute\QueueProcessor;

#[QueueProcessor] // ERROR Queue processor DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\QueueProcessor\NotReadonlyQueueProcessor must be final and readonly.
final class NotReadonlyQueueProcessor
{
}
