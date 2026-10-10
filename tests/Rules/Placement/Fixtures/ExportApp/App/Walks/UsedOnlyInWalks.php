<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Placement\Fixtures\ExportApp\App\Walks;

use DaveLiddament\Architecture\Attribute\Exported;

#[Exported] // ERROR No other domain uses DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Placement\Fixtures\ExportApp\App\Walks\UsedOnlyInWalks, so it needn't be #[Exported].
final class UsedOnlyInWalks
{
}
