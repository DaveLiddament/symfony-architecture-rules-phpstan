<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Placement\Fixtures\ExportApp\App\Walks;

use DaveLiddament\Architecture\Attribute\Exported;

#[Exported(to: ['Registration', 'Ghost'])]
final class ExportedToGhost
{
}
