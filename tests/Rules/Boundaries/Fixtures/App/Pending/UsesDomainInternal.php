<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Pending;

final class UsesDomainInternal
{
    public function usesDomainInternals(\DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Home\Internal\Secret $secret): void // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Home\Internal\Secret is internal to domain "Home" and cannot be used from domain "Pending" (only #[Exported] classes are public).
    {
    }

    public function usesClassExportedToADomain(\DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Home\ForGym $forGym): void // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Home\ForGym is not exported to domain "Pending" (its #[Exported] lists "Gym").
    {
    }
}
