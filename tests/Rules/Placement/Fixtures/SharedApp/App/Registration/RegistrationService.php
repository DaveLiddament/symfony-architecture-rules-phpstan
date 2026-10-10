<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Placement\Fixtures\SharedApp\App\Registration;

final class RegistrationService
{
    public function uses(
        \DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Placement\Fixtures\SharedApp\App\Shared\Money $money,
        \DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Placement\Fixtures\SharedApp\App\Shared\OnlyRegistrationUses $onlyRegistrationUses,
    ): void {
    }
}
