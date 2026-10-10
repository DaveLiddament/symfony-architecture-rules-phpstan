<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Cycles\App\Registration;

final class Signup
{
    public function usesWalk(\DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Cycles\App\Walks\Walk $value): void // ERROR Domains "Registration", "Walks" depend on each other in a cycle: Registration → Walks → Registration (DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Cycles\App\Registration\Signup uses DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Cycles\App\Walks\Walk; DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Cycles\App\Walks\WalkPlanner uses DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Cycles\App\Registration\Member).
    {
    }

    public function usesClock(\DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Cycles\App\Shared\Clock $value): void
    {
    }
}
