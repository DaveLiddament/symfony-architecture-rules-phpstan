<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\EntityManagerUsage;

final class Unrelated
{
    public string $anything = 'no entity manager here';

    public \stdClass $object;
}
