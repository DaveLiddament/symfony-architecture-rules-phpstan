<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Placement\Fixtures\PendingApp\App\Pending;

final class UsedByRegistrationUsesWalks
{
    public function uses(
        \DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Placement\Fixtures\PendingApp\App\Walks\WalkApi $walkApi,
    ): void {
    }
}
