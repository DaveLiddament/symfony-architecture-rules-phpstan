<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Gym;

use DaveLiddament\Architecture\Attribute\Exported;
use DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Gym\Repository\GymRepository;
use DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Home\ForGym;
use DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Home\HomeApi;
use DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Shared\User;
use DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Lib\Clock;

#[Exported]
final class GymApi
{
    public function usesOwnInternals(GymRepository $repository): void
    {
    }

    public function usesOtherDomainExported(HomeApi $api): void
    {
    }

    public function usesExportedToItsDomain(ForGym $forGym): void
    {
    }

    public function usesShared(User $user): void
    {
    }

    public function usesLib(Clock $clock): void
    {
    }

    public function usesAppRoot(\DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Kernel $kernel): void
    {
    }
}
