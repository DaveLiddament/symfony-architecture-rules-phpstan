<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Gym;

use DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Home\ForStats;
use DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Home\HomeHelper;
use DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Home\Internal\Secret;

final class UsesHomeInternal
{
    public function usesOtherDomainSubdirectory(Secret $secret): void // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Home\Internal\Secret is internal to domain "Home" and cannot be used from domain "Gym" (only #[Exported] classes are public).
    {
    }

    public function usesOtherDomainRoot(HomeHelper $helper): void // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Home\HomeHelper is internal to domain "Home" and cannot be used from domain "Gym" (only #[Exported] classes are public).
    {
    }

    public function usesClassExportedToAnotherDomain(ForStats $forStats): void // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Home\ForStats is not exported to domain "Gym" (its #[Exported] lists "Stats").
    {
    }
}
