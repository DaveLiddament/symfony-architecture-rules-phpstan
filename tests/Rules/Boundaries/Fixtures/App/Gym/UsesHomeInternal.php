<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\App\Gym;

use DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\App\Home\Internal\Secret;

final class UsesHomeInternal
{
    public function usesOtherDomainInternals(Secret $secret): void // ERROR DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\App\Home\Internal\Secret is internal to domain "Home" and cannot be used from domain "Gym" (only domain-root classes are public).
    {
    }
}
