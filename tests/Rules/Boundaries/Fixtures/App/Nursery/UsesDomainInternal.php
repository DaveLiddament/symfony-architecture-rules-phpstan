<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Nursery;

final class UsesDomainInternal
{
    public function usesDomainInternals(\DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Home\Internal\Secret $secret): void // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Home\Internal\Secret is internal to domain "Home" and cannot be used from the nursery (only #[Exported] classes are public).
    {
    }

    public function usesClassExportedToADomain(\DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Home\ForGym $forGym): void // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Home\ForGym is not exported to the nursery (its #[Exported] lists "Gym").
    {
    }
}
