<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Placement\Fixtures\ExportApp\App\Walks;

final class WalkService
{
    public function uses(
        \DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Placement\Fixtures\ExportApp\App\Walks\UsedOnlyInWalks $usedOnlyInWalks,
    ): void {
    }
}
