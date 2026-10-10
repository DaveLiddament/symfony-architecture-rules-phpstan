<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Lib;

use DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Gym\GymApi;

final class BadUtil
{
    public function usesDomainCode(GymApi $api): void // ERROR Lib code must stay application-agnostic and cannot depend on DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Gym\GymApi.
    {
    }

    public function usesSharedCode(\DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Shared\User $user): void // ERROR Lib code must stay application-agnostic and cannot depend on DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Shared\User.
    {
    }

    public function usesPendingCode(\DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Pending\Helper $helper): void // ERROR Lib code must stay application-agnostic and cannot depend on DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Pending\Helper.
    {
    }

    public function usesAppRootCode(\DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Kernel $kernel): void // ERROR Lib code must stay application-agnostic and cannot depend on DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Kernel.
    {
    }
}
