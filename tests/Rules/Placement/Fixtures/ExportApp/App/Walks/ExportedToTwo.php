<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Placement\Fixtures\ExportApp\App\Walks;

use DaveLiddament\Architecture\Attribute\Exported;

#[Exported(to: ['Registration', 'Stats'])] // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Placement\Fixtures\ExportApp\App\Walks\ExportedToTwo is exported to domain "Stats", which doesn't use it.
final class ExportedToTwo
{
}
