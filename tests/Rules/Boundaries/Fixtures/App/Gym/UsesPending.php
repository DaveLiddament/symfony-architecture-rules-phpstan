<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Gym;

use DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Pending\Helper;
use DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Pending\PendingApi;

final class UsesPending
{
    public function usesExportedPendingCode(PendingApi $api): void
    {
    }

    public function usesPendingCode(Helper $helper): void // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Pending\Helper is internal to domain "Pending" and cannot be used from domain "Gym" (only #[Exported] classes are public).
    {
    }
}
