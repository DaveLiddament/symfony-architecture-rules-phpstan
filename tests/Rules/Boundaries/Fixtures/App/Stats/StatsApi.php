<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Stats;

use DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Home\ForStats;

final class StatsApi
{
    public function usesExportedToItsDomain(ForStats $forStats): void
    {
    }
}
