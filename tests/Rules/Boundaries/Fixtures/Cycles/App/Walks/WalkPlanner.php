<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Cycles\App\Walks;

final class WalkPlanner
{
    public function usesMember(\DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Cycles\App\Registration\Member $value): void
    {
    }
}
