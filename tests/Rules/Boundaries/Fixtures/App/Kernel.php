<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App;

final class Kernel
{
    public function usesAnything(
        Gym\Repository\GymRepository $repository,
        Pending\NewFeature $feature,
        Shared\User $user,
        \DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Lib\Clock $clock,
    ): void {
    }
}
