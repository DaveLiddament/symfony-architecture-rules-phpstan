<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\Lib;

use DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\App\Gym\GymApi;

final class BadUtil
{
    public function usesDomainCode(GymApi $api): void // ERROR Lib code must stay application-agnostic and cannot depend on DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\App\Gym\GymApi.
    {
    }

    public function usesSharedCode(\DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\App\Shared\User $user): void // ERROR Lib code must stay application-agnostic and cannot depend on DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\App\Shared\User.
    {
    }

    public function usesNurseryCode(\DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\App\Nursery\Helper $helper): void // ERROR Lib code must stay application-agnostic and cannot depend on DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\App\Nursery\Helper.
    {
    }

    public function usesAppRootCode(\DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\App\Kernel $kernel): void // ERROR Lib code must stay application-agnostic and cannot depend on DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\App\Kernel.
    {
    }
}
