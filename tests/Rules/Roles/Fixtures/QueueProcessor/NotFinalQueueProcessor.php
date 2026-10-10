<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\QueueProcessor;

use DaveLiddament\Architecture\Attribute\QueueProcessor;

#[QueueProcessor] // ERROR Queue processor DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\QueueProcessor\NotFinalQueueProcessor must be final and readonly.
readonly class NotFinalQueueProcessor
{
}
