<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Placement\Fixtures\PendingApp\App\Pending;

final class UsesWalks // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Placement\Fixtures\PendingApp\App\Pending\UsesWalks uses only domain "Walks" and no domain uses it, so it can move out of Pending into that domain.
{
    public function uses(
        \DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Placement\Fixtures\PendingApp\App\Walks\WalkApi $walkApi,
        \DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Placement\Fixtures\PendingApp\App\Shared\Clock $clock,
    ): void {
    }
}
