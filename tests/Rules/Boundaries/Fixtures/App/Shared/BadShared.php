<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Shared;

final class BadShared
{
    public function usesDomainRoot(\DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Gym\GymApi $api): void // ERROR Shared code cannot depend on domain code (DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Gym\GymApi is in domain "Gym").
    {
    }

    public function usesDomainInternals(\DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Gym\Repository\GymRepository $repository): void // ERROR Shared code cannot depend on domain code (DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Gym\Repository\GymRepository is in domain "Gym").
    {
    }

    public function usesPendingCode(\DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Pending\Helper $helper): void // ERROR Shared code cannot depend on domain code (DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Pending\Helper is in domain "Pending").
    {
    }
}
