<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Placement\Fixtures\PendingApp\App\Pending;

final class OnlyRegistrationUses // ERROR Only domain "Registration" uses DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Placement\Fixtures\PendingApp\App\Pending\OnlyRegistrationUses, so it can move out of Pending into that domain.
{
    public function uses(
        \DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Placement\Fixtures\PendingApp\App\Registration\RegistrationService $registrationService,
        \DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Placement\Fixtures\PendingApp\App\Pending\Helper $helper,
        \DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Placement\Fixtures\PendingApp\App\Shared\Clock $clock,
    ): void {
    }
}
