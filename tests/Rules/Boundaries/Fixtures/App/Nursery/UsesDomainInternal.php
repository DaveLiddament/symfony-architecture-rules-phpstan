<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Nursery;

final class UsesDomainInternal
{
    public function usesDomainInternals(\DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Home\Internal\Secret $secret): void // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Home\Internal\Secret is internal to domain "Home" and cannot be used from the nursery (only domain-root classes are public).
    {
    }
}
