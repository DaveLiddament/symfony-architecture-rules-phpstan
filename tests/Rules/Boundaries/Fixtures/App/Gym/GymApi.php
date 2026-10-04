<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\App\Gym;

use DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\App\Gym\Repository\GymRepository;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\App\Home\HomeApi;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\App\Shared\User;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\Lib\Clock;

final class GymApi
{
    public function usesOwnInternals(GymRepository $repository): void
    {
    }

    public function usesOtherDomainRoot(HomeApi $api): void
    {
    }

    public function usesShared(User $user): void
    {
    }

    public function usesLib(Clock $clock): void
    {
    }

    public function usesAppRoot(\DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\App\Kernel $kernel): void
    {
    }
}
