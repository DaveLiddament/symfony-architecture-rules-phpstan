<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Placement\Fixtures\ExportApp\App\Walks;

use DaveLiddament\Architecture\Attribute\Exported;

#[Exported] // ERROR No other domain uses DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Placement\Fixtures\ExportApp\App\Walks\UnusedExport, so it needn't be #[Exported].
final class UnusedExport
{
}
