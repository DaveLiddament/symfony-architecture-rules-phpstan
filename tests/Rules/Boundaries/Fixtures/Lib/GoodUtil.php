<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries\Fixtures\Lib;

final class GoodUtil
{
    public function usesOtherLibCode(Clock $clock): \DateTimeImmutable
    {
        return $clock->now();
    }
}
