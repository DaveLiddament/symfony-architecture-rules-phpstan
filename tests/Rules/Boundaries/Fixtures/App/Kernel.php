<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\App;

final class Kernel
{
    public function usesAnything(
        Gym\Repository\GymRepository $repository,
        Nursery\NewFeature $feature,
        Shared\User $user,
        \DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\Lib\Clock $clock,
    ): void {
    }
}
