<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Exported\App\Walks\ExportedTo;

use DaveLiddament\Architecture\Attribute\Exported;
use DaveLiddament\Architecture\Attribute\Service;

#[Exported(to: ['Pending']), Service]
final readonly class ForPending
{
}
