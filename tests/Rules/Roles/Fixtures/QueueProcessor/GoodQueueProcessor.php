<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\QueueProcessor;

use DaveLiddament\SymfonyArchitecture\Attribute\QueueProcessor;

#[QueueProcessor]
final readonly class GoodQueueProcessor
{
}
