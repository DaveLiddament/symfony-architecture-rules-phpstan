<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Placement\Fixtures\SharedApp\App\Walks;

final class WalkService
{
    public function uses(
        \DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Placement\Fixtures\SharedApp\App\Shared\Money $money,
    ): void {
    }
}
