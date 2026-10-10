<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Placement\Fixtures\PendingApp\App\Registration;

final class RegistrationService
{
    public function uses(
        \DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Placement\Fixtures\PendingApp\App\Pending\OnlyRegistrationUses $onlyRegistrationUses,
        \DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Placement\Fixtures\PendingApp\App\Pending\UsedByTwo $usedByTwo,
        \DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Placement\Fixtures\PendingApp\App\Pending\UsedByRegistrationUsesWalks $usedByRegistrationUsesWalks,
    ): void {
    }
}
