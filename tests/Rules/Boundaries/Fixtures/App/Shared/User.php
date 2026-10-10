<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\App\Shared;

final class User
{
    public function usesLib(\DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Lib\Clock $clock): void
    {
    }

    public function usesOtherSharedCode(Money $money): void
    {
    }
}
