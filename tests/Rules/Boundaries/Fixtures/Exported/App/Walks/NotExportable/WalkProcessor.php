<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Exported\App\Walks\NotExportable;

use DaveLiddament\Architecture\Attribute\Exported;
use DaveLiddament\Architecture\Attribute\QueueProcessor;

#[Exported, QueueProcessor] // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Exported\App\Walks\NotExportable\WalkProcessor has the queueProcessor role, so it cannot be exported.
final readonly class WalkProcessor
{
}
