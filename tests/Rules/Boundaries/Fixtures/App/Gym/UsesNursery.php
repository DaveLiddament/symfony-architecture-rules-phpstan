<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Gym;

final class UsesNursery
{
    public function usesNurseryCode(\DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Nursery\Helper $helper): void // ERROR Domain "Gym" cannot depend on nursery code (DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Nursery\Helper). Move it into a domain or Shared first.
    {
    }
}
