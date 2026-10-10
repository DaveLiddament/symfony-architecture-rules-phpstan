<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Placement\Fixtures\ExportApp\App\Registration;

final class RegistrationService
{
    public function uses(
        \DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Placement\Fixtures\ExportApp\App\Walks\WalkApi $walkApi,
        \DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Placement\Fixtures\ExportApp\App\Walks\ExportedToTwo $exportedToTwo,
        \DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Placement\Fixtures\ExportApp\App\Walks\ExportedToGhost $exportedToGhost,
        \DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Placement\Fixtures\ExportApp\App\Pending\PendingApi $pendingApi,
        \DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Placement\Fixtures\ExportApp\App\Shared\Thing $thing,
    ): void {
    }
}
