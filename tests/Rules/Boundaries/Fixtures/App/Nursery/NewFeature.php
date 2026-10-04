<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\App\Nursery;

final class NewFeature
{
    public function usesDomainRoot(\DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\App\Gym\GymApi $api): void
    {
    }

    public function usesShared(\DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\App\Shared\User $user): void
    {
    }

    public function usesLib(\DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\Lib\Clock $clock): void
    {
    }

    public function usesOtherNurseryCode(Helper $helper): void
    {
    }
}
