<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\App\Shared;

final class BadShared
{
    public function usesDomainRoot(\DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\App\Gym\GymApi $api): void // ERROR Shared code cannot depend on domain code (DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\App\Gym\GymApi is in domain "Gym").
    {
    }

    public function usesDomainInternals(\DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\App\Gym\Repository\GymRepository $repository): void // ERROR Shared code cannot depend on domain code (DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\App\Gym\Repository\GymRepository is in domain "Gym").
    {
    }

    public function usesNurseryCode(\DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\App\Nursery\Helper $helper): void // ERROR Shared code cannot depend on nursery code (DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\App\Nursery\Helper).
    {
    }
}
