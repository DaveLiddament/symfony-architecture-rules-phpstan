<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\PersistenceUsage;

final class Unrelated
{
    public string $anything = 'no entity manager here';

    public \stdClass $object;
}
