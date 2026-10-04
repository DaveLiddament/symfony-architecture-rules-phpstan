<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\QueueProcessor;

use DaveLiddament\SymfonyArchitecture\Attribute\QueueProcessor;

#[QueueProcessor] // ERROR Queue processor DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\QueueProcessor\NotFinalQueueProcessor must be final and readonly.
readonly class NotFinalQueueProcessor
{
}
