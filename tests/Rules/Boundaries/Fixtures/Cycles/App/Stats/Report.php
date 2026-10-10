<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Cycles\App\Stats;

final class Report
{
    public function usesFeature(\DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Cycles\App\Pending\Feature $value): void
    {
    }
}
